<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * Handle incoming AI chat message
     */
    public function chat(Request $request)
    {
        $userMessage = trim($request->input('message', ''));
        $sessionId   = $request->input('session_id');
        $userId      = session('user_id');
        $lat         = $request->input('lat');
        $lon         = $request->input('lon');

        if (empty($userMessage)) {
            return response()->json(['error' => 'Tin nhắn không được để trống'], 400);
        }

        // Get or Create Chat Session
        $session = null;
        if ($sessionId) {
            $session = ChatSession::find($sessionId);
        }
        if (!$session) {
            $session = ChatSession::create([
                'user_id' => $userId,
                'title'   => mb_substr($userMessage, 0, 30) . '...',
            ]);
        }

        // Save User Message
        ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'user',
            'message'    => $userMessage,
            'created_at' => now(),
        ]);

        // Process message with local smart engine or Gemini API
        $responsePayload = $this->generateAiResponse($userMessage, $userId, $lat, $lon);

        // Save Assistant Message
        ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'assistant',
            'message'    => $responsePayload['text'],
            'created_at' => now(),
        ]);

        return response()->json([
            'session_id' => $session->id,
            'text'       => $responsePayload['text'],
            'type'       => $responsePayload['type'] ?? 'general',
            'data'       => $responsePayload['data'] ?? null,
        ]);
    }

    /**
     * Fetch chat history for a session
     */
    public function history($sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * AI Response Engine: Intelligent vietnamese coffee shop assistant + order/payment helper
     */
    private function generateAiResponse(string $userMsg, $userId, $lat = null, $lon = null): array
    {
        $lowerMsg = mb_strtolower($userMsg, 'UTF-8');

        // 1. Check if user mentions Order Code (ORD-XXXXX)
        if (preg_match('/ORD-[A-Z0-9]+/i', $userMsg, $matches)) {
            $code = strtoupper($matches[0]);
            $order = Order::where('code', $code)->with(['items', 'payment'])->first();

            if ($order) {
                $statusMap = [
                    'PENDING'   => 'Đang chờ xác nhận / Chờ thanh toán VietQR',
                    'CONFIRMED' => 'Đã xác nhận',
                    'PREPARING' => 'Đang pha chế',
                    'DELIVERING'=> 'Đang giao hàng',
                    'COMPLETED' => 'Hoàn thành',
                    'CANCELLED' => 'Đã hủy',
                ];
                $statusStr = $statusMap[strtoupper($order->order_status)] ?? $order->order_status;
                $paymentStatus = $order->payment ? ($order->payment->payment_status === 'COMPLETED' ? 'Đã thanh toán' : 'Chưa thanh toán (VietQR)') : 'Chưa thanh toán';

                $itemsStr = "";
                foreach ($order->items as $idx => $it) {
                    $itemsStr .= "\n- " . ($it->product_name ?? 'Sản phẩm') . " (x" . $it->quantity . ") - " . number_format($it->unit_price, 0, ',', '.') . "đ";
                }

                $replyText = "📦 **Thông tin đơn hàng #" . $order->code . "**:\n";
                $replyText .= "• Trạng thái đơn: **" . $statusStr . "**\n";
                $replyText .= "• Trạng thái thanh toán: **" . $paymentStatus . "**\n";
                $replyText .= "• Người nhận: " . $order->receiver_name . " (" . $order->receiver_phone . ")\n";
                $replyText .= "• Địa chỉ: " . $order->delivery_address . "\n";
                $replyText .= "• Chi tiết món:" . $itemsStr . "\n";
                $replyText .= "• **Tổng tiền**: " . number_format($order->total_amount, 0, ',', '.') . " VNĐ\n\n";

                if ($order->order_status === 'PENDING' && ($order->payment && $order->payment->payment_status === 'PENDING')) {
                    $replyText .= "💡 *Bạn có thể quét mã VietQR để hoàn tất thanh toán nhanh chóng. Nội dung chuyển khoản:* `" . $order->code . "`";
                }

                return [
                    'text' => $replyText,
                    'type' => 'order_status',
                    'data' => [
                        'order_code'     => $order->code,
                        'order_status'   => $order->order_status,
                        'total_amount'   => $order->total_amount,
                        'payment_status' => $paymentStatus,
                    ]
                ];
            } else {
                return [
                    'text' => "🔍 Rất tiếc, AI không tìm thấy đơn hàng mã **{$code}** trong hệ thống. Bạn vui lòng kiểm tra lại mã đơn hàng trên hoá đơn nhé!",
                    'type' => 'general',
                ];
            }
        }

        // 2. Check if user wants order checking without explicit code
        if (str_contains($lowerMsg, 'đơn hàng của tôi') || str_contains($lowerMsg, 'kiểm tra đơn') || str_contains($lowerMsg, 'xem đơn')) {
            if (!$userId) {
                return [
                    'text' => "🔑 Bạn hãy **Đăng nhập** tài khoản để AI hỗ trợ tra cứu các đơn hàng gần nhất của bạn nhé!",
                    'type' => 'general'
                ];
            }

            $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
            if ($customerProfile) {
                $latestOrder = Order::where('customer_id', $customerProfile->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestOrder) {
                    return $this->generateAiResponse("Kiem tra ORD-" . str_replace('ORD-', '', $latestOrder->code), $userId);
                }
            }

            return [
                'text' => "🛒 Bạn chưa có đơn hàng nào trong thời gian gần đây. Hãy chọn các thức uống thơm ngon trên thực đơn CozyHNA để trải nghiệm nhé!",
                'type' => 'general'
            ];
        }

        // 3. Check for Payment / VietQR / Chuyển khoản Help
        if (str_contains($lowerMsg, 'vietqr') || str_contains($lowerMsg, 'chuyển khoản') || str_contains($lowerMsg, 'ngân hàng') || str_contains($lowerMsg, 'momo') || str_contains($lowerMsg, 'thanh toán')) {
            $replyText = "💳 **Hướng dẫn Thanh toán VietQR / Chuyển khoản tại CozyHNA**:\n\n";
            $replyText .= "1. Tại trang **Checkout**, chọn phương thức **'Chuyển khoản VietQR / Ngân hàng'**.\n";
            $replyText .= "2. Hệ thống sẽ tự động tạo **Mã VietQR** khớp chính xác **Số tiền** & **Nội dung chuyển khoản** (`ORD-XXXXX`).\n";
            $replyText .= "3. Mở ứng dụng Ngân hàng (MBBank, Vietcombank, Techcombank, MoMo...) chọn **Quét mã QR**.\n";
            $replyText .= "4. **Thông tin Ngân hàng cửa hàng**:\n";
            $replyText .= "   • Ngân hàng: **MBBank (Ngân hàng Quân Đội)**\n";
            $replyText .= "   • Số tài khoản: **0987654321**\n";
            $replyText .= "   • Chủ tài khoản: **COZYHNA COFFEE AND TEA**\n";
            $replyText .= "5. Sau khi chuyển khoản thành công, tin nhắn xác nhận sẽ tự động cập nhật đơn hàng của bạn!";

            return [
                'text' => $replyText,
                'type' => 'payment_info',
                'data' => [
                    'bank_name'    => 'MBBank',
                    'account_no'   => '0987654321',
                    'account_name' => 'COZYHNA COFFEE AND TEA',
                ]
            ];
        }

        // 4. Check for Drink Recommendation / Menu Suggestions
        if (str_contains($lowerMsg, 'tư vấn') || str_contains($lowerMsg, 'gợi ý') || str_contains($lowerMsg, 'món ngon') || str_contains($lowerMsg, 'hot') || str_contains($lowerMsg, 'bán chạy') || str_contains($lowerMsg, 'trà') || str_contains($lowerMsg, 'cà phê') || str_contains($lowerMsg, 'thực đơn')) {
            $featuredProducts = Product::where('status', true)
                ->with(['productSizes.size', 'category'])
                ->take(4)
                ->get();

            $replyText = "☕ **Gợi ý Đồ uống Thơm Ngon HOT nhất CozyHNA dành cho bạn**:\n\n";
            foreach ($featuredProducts as $idx => $p) {
                $minPrice = $p->productSizes->min('selling_price') ?? 0;
                $catName = $p->category ? $p->category->name : 'Thức uống';
                $replyText .= ($idx + 1) . ". **" . $p->name . "** (" . $catName . ")\n";
                $replyText .= "   • Giá chỉ từ: **" . number_format($minPrice, 0, ',', '.') . " VNĐ**\n";
                if ($p->short_description) {
                    $replyText .= "   • *" . $p->short_description . "*\n";
                }
            }
            $replyText .= "\n✨ Bạn có muốn thử ngụm trà đậm vị hay tách cà phê năng lượng hôm nay không?";

            return [
                'text' => $replyText,
                'type' => 'recommendation',
                'data' => $featuredProducts->map(function($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'price' => number_format($p->productSizes->min('selling_price') ?? 0, 0, ',', '.') . 'đ',
                    ];
                })
            ];
        }

        // 5. Check for Weather Request
        $weatherContextToGemini = null;
        if (str_contains($lowerMsg, 'thời tiết')) {
            $location = 'Hà Nội'; // Default
            $weatherUrl = "";

            if ($lat && $lon) {
                $location = "Vị trí của bạn";
                $weatherUrl = "https://wttr.in/{$lat},{$lon}?format=%C,+nhiệt+độ:+%t,+gió:+%w&lang=vi";
            } else {
                // Extract location if specified: "thời tiết tại Đà Nẵng", "thời tiết ở sài gòn"
                if (preg_match('/thời tiết (?:tại|ở)\s+([a-zA-ZÀ-ỹ\s]+)/iu', $lowerMsg, $matches)) {
                    $extracted = trim($matches[1]);
                    $ignoreWords = ['hôm nay', 'ngày mai', 'đâu', 'thế nào', 'ra sao'];
                    if (!empty($extracted) && !in_array(mb_strtolower($extracted, 'UTF-8'), $ignoreWords)) {
                        $location = mb_convert_case($extracted, MB_CASE_TITLE, "UTF-8");
                    }
                }
                $weatherUrl = "https://wttr.in/" . urlencode($location) . "?format=%C,+nhiệt+độ:+%t,+gió:+%w&lang=vi";
            }

            try {
                $weatherRes = Http::withoutVerifying()->timeout(5)->get($weatherUrl);
                
                if ($weatherRes->successful()) {
                    $weatherData = $weatherRes->body();
                    
                    if (strlen($weatherData) < 100 && !str_contains($weatherData, '<html')) {
                        
                        $wantsSuggestion = false;
                        $keywords = ['uống', 'món', 'gợi ý', 'tư vấn', 'phù hợp', 'gì'];
                        foreach ($keywords as $kw) {
                            if (str_contains($lowerMsg, $kw)) {
                                $wantsSuggestion = true;
                                break;
                            }
                        }

                        if ($wantsSuggestion) {
                            // Chuyển dữ liệu cho Gemini suy nghĩ thay vì trả lời cứng
                            $weatherContextToGemini = "Thời tiết hiện tại ở {$location}: " . trim($weatherData) . ".";
                        } else {
                            $replyText = "🌤️ **Thời tiết hiện tại ở {$location}**:\n\n";
                            $replyText .= "👉 *" . trim($weatherData) . "*\n\n";
                            $replyText .= "☕ Thời tiết này mà thưởng thức một ly nước tại **CozyHNA** thì thật tuyệt vời bạn nhé!";
                            
                            return [
                                'text' => $replyText,
                                'type' => 'weather_info',
                                'data' => ['location' => $location]
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Weather API Error: ' . $e->getMessage());
            }
        }

        // 6. Try calling Gemini API if GEMINI_API_KEY is available in env
        $geminiApiKey = env('GEMINI_API_KEY');
        if ($geminiApiKey) {
            try {
                $prompt = "Bạn là AI Trợ Lý Thông Minh cho Quán Cà Phê & Trà CozyHNA. Trả lời bằng tiếng Việt thân thiện, lịch sự, ngắn gọn và hấp dẫn.\n";
                
                $menuProducts = Product::where('status', true)->take(12)->get();
                if ($menuProducts->count() > 0) {
                    $menuArr = [];
                    foreach ($menuProducts as $p) {
                        $menuArr[] = $p->name;
                    }
                    $prompt .= "Thực đơn tiêu biểu của quán: " . implode(', ', $menuArr) . ".\n";
                }

                if ($weatherContextToGemini) {
                    $prompt .= "{$weatherContextToGemini} Hãy dựa vào thời tiết này để tư vấn món uống phù hợp nhất từ thực đơn cho khách nhé.\n";
                }

                $prompt .= "Khách hàng hỏi: " . $userMsg;

                $apiRes = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->withoutVerifying()
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$geminiApiKey}", [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ]
                    ]);

                if ($apiRes->successful()) {
                    $geminiText = $apiRes->json('candidates.0.content.parts.0.text');
                    if ($geminiText) {
                        return [
                            'text' => $geminiText,
                            'type' => 'ai_generated'
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Error: ' . $e->getMessage());
            }
        }

        // Default Friendly Response
        return [
            'text' => "👋 Xin chào! Tôi là **AI Trợ Lý CozyHNA**. Tôi có thể giúp bạn:\n"
                . "• 🍵 **Tư vấn thức uống thơm ngon** phù hợp với khẩu vị\n"
                . "• 💳 **Hướng dẫn Thanh toán VietQR / Ngân hàng nhanh**\n"
                . "• 📦 **Tra cứu tiến độ & trạng thái đơn hàng** (Hãy gửi mã đơn `ORD-xxxxx` cho tôi)\n\n"
                . "Bạn cần hỗ trợ điều gì hôm nay?",
            'type' => 'general'
        ];
    }
}
