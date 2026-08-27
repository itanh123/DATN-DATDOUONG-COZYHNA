<?php

namespace App\Http\Controllers;

use App\Models\AiKnowledge;
use App\Models\AiMessageFeedback;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Topping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $userMsg = ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'user',
            'message'    => $userMessage,
            'created_at' => now(),
        ]);

        // Process message
        $responsePayload = $this->generateAiResponse($userMessage, $userId, $session->id, $lat, $lon);

        // Save Assistant Message
        $assistantMsg = ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'assistant',
            'message'    => $responsePayload['text'],
            'created_at' => now(),
        ]);

        return response()->json([
            'session_id'  => $session->id,
            'message_id'  => $assistantMsg->id, // Để frontend dùng cho feedback
            'text'        => $responsePayload['text'],
            'type'        => $responsePayload['type'] ?? 'general',
            'data'        => $responsePayload['data'] ?? null,
            'used_search' => $responsePayload['used_search'] ?? false,
        ]);
    }

    /**
     * Handle feedback (👍👎) from user
     */
    public function feedback(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:chat_messages,id',
            'feedback'   => 'required|in:positive,negative',
            'comment'    => 'nullable|string|max:500',
        ]);

        $messageId = $request->input('message_id');
        $feedback  = $request->input('feedback');
        $comment   = $request->input('comment');

        // Lưu feedback
        AiMessageFeedback::updateOrCreate(
            ['message_id' => $messageId],
            ['feedback' => $feedback, 'comment' => $comment]
        );

        // Tìm kiến thức liên quan đến tin nhắn này và cập nhật confidence
        $message = ChatMessage::find($messageId);
        if ($message) {
            // Lấy tin nhắn user trước đó trong cùng session
            $userMsg = ChatMessage::where('session_id', $message->session_id)
                ->where('role', 'user')
                ->where('id', '<', $message->id)
                ->orderByDesc('id')
                ->first();

            if ($userMsg) {
                $relatedKnowledge = AiKnowledge::findRelevant($userMsg->message, 3);
                foreach ($relatedKnowledge as $knowledge) {
                    if ($feedback === 'positive') {
                        $knowledge->boostConfidence(5);
                    } else {
                        $knowledge->penalizeConfidence(10);
                    }
                }

                // Nếu feedback tích cực, cũng rút trích kiến thức từ câu trả lời tốt
                if ($feedback === 'positive') {
                    $this->extractAndSaveKnowledge(
                        $userMsg->message,
                        $message->message,
                        $message->session_id,
                        70 // Confidence cao hơn vì được user xác nhận
                    );
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Cảm ơn phản hồi của bạn!']);
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
     * Main AI Response Engine
     */
    private function generateAiResponse(string $userMsg, $userId, $sessionId, $lat = null, $lon = null): array
    {
        $lowerMsg = mb_strtolower($userMsg, 'UTF-8');

        // =====================================================
        // RULE-BASED: Tra cứu đơn hàng (cần dữ liệu chính xác từ DB)
        // =====================================================

        // 1. Kiểm tra mã đơn hàng ORD-XXXXX
        if (preg_match('/ORD-[A-Z0-9]+/i', $userMsg, $matches)) {
            return $this->handleOrderLookup(strtoupper($matches[0]));
        }

        // 2. Kiểm tra yêu cầu xem đơn hàng (không có mã)
        if (str_contains($lowerMsg, 'đơn hàng của tôi') || str_contains($lowerMsg, 'kiểm tra đơn') || str_contains($lowerMsg, 'xem đơn')) {
            return $this->handleMyOrders($userId);
        }

        // =====================================================
        // GEMINI AI: Xử lý tất cả câu hỏi khác bằng AI thông minh
        // =====================================================
        return $this->handleWithGemini($userMsg, $userId, $sessionId, $lat, $lon);
    }

    /**
     * Tra cứu đơn hàng bằng mã ORD-XXXXX (rule-based, dữ liệu chính xác)
     */
    private function handleOrderLookup(string $code): array
    {
        $order = Order::where('code', $code)->with(['items', 'payment'])->first();

        if (!$order) {
            return [
                'text' => "🔍 Rất tiếc, AI không tìm thấy đơn hàng mã **{$code}** trong hệ thống. Bạn vui lòng kiểm tra lại mã đơn hàng trên hoá đơn nhé!",
                'type' => 'general',
            ];
        }

        $statusMap = [
            'PENDING'    => 'Đang chờ xác nhận / Chờ thanh toán',
            'CONFIRMED'  => 'Đã xác nhận',
            'PREPARING'  => 'Đang pha chế',
            'DELIVERING' => 'Đang giao hàng',
            'COMPLETED'  => 'Hoàn thành',
            'CANCELLED'  => 'Đã hủy',
        ];
        $statusStr = $statusMap[strtoupper($order->order_status)] ?? $order->order_status;
        $paymentStatus = $order->payment
            ? ($order->payment->payment_status === 'COMPLETED' ? 'Đã thanh toán' : 'Chưa thanh toán')
            : 'Chưa thanh toán';

        $itemsStr = "";
        foreach ($order->items as $it) {
            $itemsStr .= "\n- " . ($it->product_name ?? 'Sản phẩm') . " (x" . $it->quantity . ") - " . number_format($it->unit_price, 0, ',', '.') . "đ";
        }

        $replyText = "📦 **Thông tin đơn hàng #{$order->code}**:\n";
        $replyText .= "• Trạng thái đơn: **{$statusStr}**\n";
        $replyText .= "• Trạng thái thanh toán: **{$paymentStatus}**\n";
        $replyText .= "• Người nhận: {$order->receiver_name} ({$order->receiver_phone})\n";
        $replyText .= "• Địa chỉ: {$order->delivery_address}\n";
        $replyText .= "• Chi tiết món:" . $itemsStr . "\n";
        $replyText .= "• **Tổng tiền**: " . number_format($order->total_amount, 0, ',', '.') . " VNĐ\n\n";

        if ($order->order_status === 'PENDING' && ($order->payment && $order->payment->payment_status === 'PENDING')) {
            $replyText .= "💡 *Bạn có thể quét mã VietQR để hoàn tất thanh toán nhanh chóng. Nội dung chuyển khoản:* `{$order->code}`";
        }

        return [
            'text' => $replyText,
            'type' => 'order_status',
            'data' => [
                'order_code'     => $order->code,
                'order_status'   => $order->order_status,
                'total_amount'   => $order->total_amount,
                'payment_status' => $paymentStatus,
            ],
        ];
    }

    /**
     * Xem đơn hàng gần nhất của user (rule-based)
     */
    private function handleMyOrders($userId): array
    {
        if (!$userId) {
            return [
                'text' => "🔑 Bạn hãy **Đăng nhập** tài khoản để AI hỗ trợ tra cứu các đơn hàng gần nhất của bạn nhé!",
                'type' => 'general',
            ];
        }

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if ($customerProfile) {
            $latestOrder = Order::where('customer_id', $customerProfile->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestOrder) {
                return $this->handleOrderLookup($latestOrder->code);
            }
        }

        return [
            'text' => "🛒 Bạn chưa có đơn hàng nào trong thời gian gần đây. Hãy chọn các thức uống thơm ngon trên thực đơn CozyHNA để trải nghiệm nhé!",
            'type' => 'general',
        ];
    }

    /**
     * Xử lý bằng Gemini AI (thông minh, có ngữ cảnh, có khả năng học hỏi)
     */
    private function handleWithGemini(string $userMsg, $userId, $sessionId, $lat = null, $lon = null): array
    {
        $geminiApiKey = env('GEMINI_API_KEY');

        if (!$geminiApiKey) {
            return $this->fallbackResponse();
        }

        try {
            // === 1. Xây dựng System Prompt ===
            $systemPrompt = $this->buildSystemPrompt();

            // === 2. Xây dựng context từ database ===
            $contextText = $this->buildContext($userId, $lat, $lon, $userMsg);

            // === 3. Lấy lịch sử hội thoại (conversation memory) ===
            $conversationHistory = $this->getConversationHistory($sessionId);

            // === 4. Gọi Gemini API (lần 1) ===
            $geminiResult = $this->callGeminiApi($geminiApiKey, $systemPrompt, $contextText, $conversationHistory, $userMsg);

            if (!$geminiResult['success']) {
                Log::warning('Gemini API Response Error', $geminiResult['error_details'] ?? []);
                return $this->fallbackResponse();
            }

            $geminiText = $geminiResult['text'];
            $usedSearch = false;

            // === 5. Kiểm tra AI có yêu cầu tìm kiếm web không ===
            if ($this->aiNeedsWebSearch($geminiText)) {
                $searchQuery = $this->extractSearchQuery($geminiText);
                if ($searchQuery) {
                    $searchResults = $this->performGoogleGrounding($geminiApiKey, $userMsg, $systemPrompt, $contextText, $conversationHistory);
                    if ($searchResults) {
                        $geminiText = $searchResults;
                        $usedSearch = true;
                    }
                }
            }

            // === 6. Rút trích & lưu kiến thức mới (async-style, không block response) ===
            $this->extractAndSaveKnowledge($userMsg, $geminiText, $sessionId);

            // === 7. Dọn dẹp text trả về (bỏ JSON block nội bộ nếu có) ===
            $cleanText = $this->cleanResponseText($geminiText);

            return [
                'text'        => $cleanText,
                'type'        => 'ai_generated',
                'used_search' => $usedSearch,
            ];

        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
        }

        return $this->fallbackResponse();
    }

    /**
     * Gọi Gemini API
     */
    private function callGeminiApi(string $apiKey, string $systemPrompt, string $contextText, $conversationHistory, string $userMsg): array
    {
        $contents = [];

        // Thêm lịch sử hội thoại
        foreach ($conversationHistory as $msg) {
            $role = $msg->role === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role'  => $role,
                'parts' => [['text' => $msg->message]],
            ];
        }

        // Thêm tin nhắn hiện tại kèm context
        $currentMessage = $userMsg;
        if (!empty($contextText)) {
            $currentMessage = "[DỮ LIỆU NGỮ CẢNH - Chỉ dùng để tham khảo, KHÔNG lặp lại nguyên văn]\n{$contextText}\n\n[CÂU HỎI CỦA KHÁCH HÀNG]\n{$userMsg}";
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $currentMessage]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents'           => $contents,
            'generationConfig'   => [
                'temperature'     => 0.7,
                'topP'            => 0.9,
                'maxOutputTokens' => 1024,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ],
        ];

        $apiRes = Http::withHeaders(['Content-Type' => 'application/json'])
            ->withoutVerifying()
            ->timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}", $payload);

        if ($apiRes->successful()) {
            $text = $apiRes->json('candidates.0.content.parts.0.text');
            if ($text) {
                return ['success' => true, 'text' => $text];
            }
        }

        return [
            'success'       => false,
            'error_details' => [
                'status' => $apiRes->status(),
                'body'   => $apiRes->body(),
            ],
        ];
    }

    /**
     * Gọi Gemini với Google Search Grounding (tìm kiếm web tích hợp)
     */
    private function performGoogleGrounding(string $apiKey, string $userMsg, string $systemPrompt, string $contextText, $conversationHistory): ?string
    {
        try {
            $contents = [];

            foreach ($conversationHistory as $msg) {
                $role = $msg->role === 'assistant' ? 'model' : 'user';
                $contents[] = [
                    'role'  => $role,
                    'parts' => [['text' => $msg->message]],
                ];
            }

            $currentMessage = $userMsg;
            if (!empty($contextText)) {
                $currentMessage = "[DỮ LIỆU NGỮ CẢNH]\n{$contextText}\n\n[CÂU HỎI]\n{$userMsg}";
            }

            $contents[] = [
                'role'  => 'user',
                'parts' => [['text' => $currentMessage]],
            ];

            $payload = [
                'system_instruction' => [
                    'parts' => [['text' => $systemPrompt . "\n\nBạn đang sử dụng Google Search để tìm thông tin bổ sung. Hãy trả lời dựa trên kết quả tìm kiếm, trích dẫn nguồn nếu phù hợp."]],
                ],
                'contents'         => $contents,
                'generationConfig' => [
                    'temperature'     => 0.7,
                    'maxOutputTokens' => 1024,
                ],
                'tools' => [
                    ['google_search' => new \stdClass()], // Bật Google Search Grounding
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ],
            ];

            $apiRes = Http::withHeaders(['Content-Type' => 'application/json'])
                ->withoutVerifying()
                ->timeout(30)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}", $payload);

            if ($apiRes->successful()) {
                $text = $apiRes->json('candidates.0.content.parts.0.text');
                if ($text) {
                    // Lưu kiến thức từ web search
                    $this->saveWebSearchKnowledge($userMsg, $text);
                    return $text;
                }
            }

            Log::warning('Google Grounding Error', [
                'status' => $apiRes->status(),
                'body'   => mb_substr($apiRes->body(), 0, 500),
            ]);

        } catch (\Exception $e) {
            Log::error('Google Grounding Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * System Prompt - "Huấn luyện" AI về CozyHNA (nâng cấp với khả năng học hỏi)
     */
    private function buildSystemPrompt(): string
    {
        $storeAddress = Setting::get('store_address', 'Hà Nam');

        return <<<PROMPT
Bạn là **AI Trợ Lý CozyHNA** — trợ lý ảo thông minh của quán cà phê & trà **CozyHNA**.

## VAI TRÒ
- Bạn là một nhân viên tư vấn thân thiện, nhiệt tình, am hiểu toàn bộ thực đơn và dịch vụ của CozyHNA.
- Bạn trả lời bằng **tiếng Việt**, giọng điệu tự nhiên, vui vẻ, có sử dụng emoji phù hợp.
- Bạn ưu tiên tư vấn về CozyHNA nhưng cũng có thể trả lời các câu hỏi tổng quát khác một cách hữu ích.

## THÔNG TIN CỬA HÀNG
- Tên: **CozyHNA** (Coffee & Tea)
- Địa chỉ: {$storeAddress}
- Phí giao hàng: 5.000đ/km
- Thanh toán: VietQR (chuyển khoản ngân hàng), Tiền mặt khi nhận hàng (COD)
- Để thanh toán VietQR: Chọn "Chuyển khoản VietQR" khi checkout → Quét mã QR bằng app ngân hàng → Hệ thống tự xác nhận

## QUY TẮC TRẢ LỜI
1. Trả lời ngắn gọn, súc tích (tối đa 200 từ) trừ khi khách hỏi chi tiết.
2. Khi tư vấn thức uống, LUÔN dựa vào dữ liệu thực đơn thực tế được cung cấp trong ngữ cảnh. KHÔNG bịa tên món hay giá.
3. Khi khách hỏi giá, LUÔN trả lời giá chính xác từ dữ liệu. Nếu có nhiều size thì liệt kê giá từng size.
4. Nếu khách muốn đặt hàng, hướng dẫn họ truy cập trang thực đơn trên website.
5. Nếu khách hỏi về đơn hàng, yêu cầu họ cung cấp mã đơn (dạng ORD-XXXXX).
6. Nếu không biết câu trả lời, hãy thành thật nói không biết và đề xuất liên hệ cửa hàng.
7. KHÔNG bao giờ bịa thông tin về đơn hàng, giá cả, hay chính sách mà bạn không có dữ liệu.
8. Khi khách hỏi câu tổng quát (kiến thức, trò chuyện vui), hãy trả lời thân thiện nhưng ngắn gọn, và khéo léo gợi ý quay lại chủ đề CozyHNA nếu phù hợp.

## KHẢ NĂNG HỌC HỎI
- Bạn có phần "KIẾN THỨC ĐÃ HỌC" trong ngữ cảnh. Đây là những kiến thức bạn đã tích lũy từ các cuộc hội thoại trước. Hãy sử dụng chúng để trả lời tốt hơn.
- Khi khách chia sẻ thông tin mới, phản hồi tích cực về sản phẩm, hoặc sở thích cá nhân, hãy ghi nhớ và vận dụng trong tương lai.
- Khi khách hỏi câu hỏi mà bạn không chắc chắn và cần tìm kiếm thêm, hãy thêm dòng sau ở CUỐI câu trả lời (trên 1 dòng riêng):
  [SEARCH_NEEDED: <từ khóa tìm kiếm>]
  Ví dụ: [SEARCH_NEEDED: cà phê arabica vs robusta khác nhau]
  Hệ thống sẽ tự động tìm kiếm và gửi lại kết quả cho bạn.
PROMPT;
    }

    /**
     * Xây dựng context từ database (thực đơn, topping, kiến thức đã học, thông tin user)
     */
    private function buildContext($userId, $lat = null, $lon = null, $userMsg = ''): string
    {
        $context = "";

        // === THỰC ĐƠN ===
        $menuData = $this->getMenuContext();
        if ($menuData) {
            $context .= "## THỰC ĐƠN COZYHNA HIỆN TẠI\n{$menuData}\n\n";
        }

        // === TOPPING ===
        $toppingData = $this->getToppingContext();
        if ($toppingData) {
            $context .= "## TOPPING KHẢ DỤNG\n{$toppingData}\n\n";
        }

        // === KIẾN THỨC ĐÃ HỌC (Knowledge Base) ===
        $knowledgeData = $this->getKnowledgeContext($userMsg);
        if ($knowledgeData) {
            $context .= "## KIẾN THỨC ĐÃ HỌC (từ các cuộc hội thoại trước)\n{$knowledgeData}\n\n";
        }

        // === ĐƠN HÀNG GẦN ĐÂY CỦA USER ===
        if ($userId) {
            $orderData = $this->getUserOrderContext($userId);
            if ($orderData) {
                $context .= "## ĐƠN HÀNG GẦN ĐÂY CỦA KHÁCH\n{$orderData}\n\n";
            }
        }

        // === THỜI TIẾT (nếu liên quan) ===
        $lowerMsg = mb_strtolower($userMsg, 'UTF-8');
        if (str_contains($lowerMsg, 'thời tiết') || str_contains($lowerMsg, 'nóng') || str_contains($lowerMsg, 'lạnh') || str_contains($lowerMsg, 'mưa')) {
            $weatherData = $this->getWeatherContext($lat, $lon, $lowerMsg);
            if ($weatherData) {
                $context .= "## THỜI TIẾT HIỆN TẠI\n{$weatherData}\n\n";
            }
        }

        return $context;
    }

    /**
     * Lấy kiến thức đã học từ Knowledge Base
     */
    private function getKnowledgeContext(string $userMsg): string
    {
        $relevant = AiKnowledge::findRelevant($userMsg, 5);

        if ($relevant->isEmpty()) {
            return '';
        }

        $text = "";
        foreach ($relevant as $k) {
            $confidenceLabel = $k->confidence >= 70 ? '✅ Tin cậy cao' : ($k->confidence >= 40 ? '⚡ Trung bình' : '⚠️ Chưa chắc');
            $text .= "- [{$k->category}] {$k->knowledge} ({$confidenceLabel}, dùng {$k->usage_count} lần)\n";

            // Tăng usage count
            $k->incrementUsage();
        }

        return $text;
    }

    /**
     * Lấy thực đơn từ DB (cache 10 phút)
     */
    private function getMenuContext(): string
    {
        return Cache::remember('ai_menu_context', 600, function () {
            $products = Product::where('status', true)
                ->with(['category', 'productSizes.size', 'toppings'])
                ->orderBy('sold_count', 'desc')
                ->get();

            if ($products->isEmpty()) {
                return '';
            }

            $menuText = "";
            $grouped = $products->groupBy(fn($p) => $p->category ? $p->category->name : 'Khác');

            foreach ($grouped as $catName => $items) {
                $menuText .= "### {$catName}\n";
                foreach ($items as $p) {
                    $menuText .= "- **{$p->name}**";
                    if ($p->short_description) {
                        $menuText .= " — {$p->short_description}";
                    }
                    $menuText .= "\n";

                    // Giá theo size
                    if ($p->productSizes->isNotEmpty()) {
                        $prices = [];
                        foreach ($p->productSizes as $ps) {
                            $sizeName = $ps->size ? $ps->size->name : 'Mặc định';
                            $prices[] = "{$sizeName}: " . number_format($ps->selling_price, 0, ',', '.') . "đ";
                        }
                        $menuText .= "  Giá: " . implode(' | ', $prices) . "\n";
                    }

                    // Đã bán
                    if ($p->sold_count > 0) {
                        $menuText .= "  Đã bán: {$p->sold_count}\n";
                    }
                }
            }

            return $menuText;
        });
    }

    /**
     * Lấy topping từ DB (cache 10 phút)
     */
    private function getToppingContext(): string
    {
        return Cache::remember('ai_topping_context', 600, function () {
            $toppings = Topping::where('status', true)->get();
            if ($toppings->isEmpty()) return '';

            $text = "";
            foreach ($toppings as $t) {
                $text .= "- {$t->name}: " . number_format($t->price, 0, ',', '.') . "đ\n";
            }
            return $text;
        });
    }

    /**
     * Lấy đơn hàng gần đây của user
     */
    private function getUserOrderContext($userId): string
    {
        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) return '';

        $recentOrders = Order::where('customer_id', $customerProfile->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        if ($recentOrders->isEmpty()) return '';

        $text = "";
        foreach ($recentOrders as $o) {
            $statusMap = [
                'PENDING' => 'Chờ xử lý', 'CONFIRMED' => 'Đã xác nhận',
                'PREPARING' => 'Đang pha chế', 'DELIVERING' => 'Đang giao',
                'COMPLETED' => 'Hoàn thành', 'CANCELLED' => 'Đã hủy',
            ];
            $status = $statusMap[strtoupper($o->order_status)] ?? $o->order_status;
            $text .= "- Đơn #{$o->code} | {$status} | " . number_format($o->total_amount, 0, ',', '.') . "đ | " . $o->created_at->format('d/m/Y') . "\n";
        }
        return $text;
    }

    /**
     * Lấy thông tin thời tiết
     */
    private function getWeatherContext($lat, $lon, $lowerMsg): string
    {
        $location = 'Hà Nam'; // Default theo store location

        if ($lat && $lon) {
            $weatherUrl = "https://wttr.in/{$lat},{$lon}?format=%C,+nhiệt+độ:+%t,+gió:+%w&lang=vi";
            $location = "Vị trí của khách";
        } else {
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
                $data = $weatherRes->body();
                if (strlen($data) < 100 && !str_contains($data, '<html')) {
                    return "Thời tiết tại {$location}: " . trim($data);
                }
            }
        } catch (\Exception $e) {
            Log::error('Weather API Error: ' . $e->getMessage());
        }

        return '';
    }

    /**
     * Lấy lịch sử hội thoại (10 tin nhắn gần nhất, không bao gồm tin nhắn hiện tại)
     */
    private function getConversationHistory($sessionId): \Illuminate\Support\Collection
    {
        return ChatMessage::where('session_id', $sessionId)
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at', 'desc')
            ->take(20) // 10 cặp user+assistant
            ->get()
            ->reverse() // Đảo ngược để theo thứ tự thời gian
            ->values()
            ->slice(0, -1); // Bỏ tin nhắn user vừa gửi (đã save trước khi gọi hàm này)
    }

    /**
     * Kiểm tra AI có yêu cầu tìm kiếm web không
     */
    private function aiNeedsWebSearch(string $responseText): bool
    {
        return (bool) preg_match('/\[SEARCH_NEEDED:\s*(.+?)\]/i', $responseText);
    }

    /**
     * Trích xuất query tìm kiếm từ response
     */
    private function extractSearchQuery(string $responseText): ?string
    {
        if (preg_match('/\[SEARCH_NEEDED:\s*(.+?)\]/i', $responseText, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * Rút trích kiến thức từ hội thoại và lưu vào Knowledge Base
     */
    private function extractAndSaveKnowledge(string $userMsg, string $aiResponse, $sessionId, int $defaultConfidence = 50): void
    {
        try {
            $lowerMsg = mb_strtolower($userMsg, 'UTF-8');
            $lowerRes = mb_strtolower($aiResponse, 'UTF-8');

            // === Pattern 1: Khách phản hồi về sản phẩm ===
            $productFeedbackPatterns = [
                '/(?:ngon|tuyệt|thích|hay|xuất sắc|ok|ổn|thơm|béo|mát|sảng khoái|yêu thích|recommend|đỉnh|10 điểm)/u',
            ];
            foreach ($productFeedbackPatterns as $pattern) {
                if (preg_match($pattern, $lowerMsg)) {
                    // Kiểm tra xem có đề cập đến sản phẩm cụ thể không
                    $products = Product::where('status', true)->pluck('name')->toArray();
                    foreach ($products as $productName) {
                        if (str_contains($lowerMsg, mb_strtolower($productName, 'UTF-8'))) {
                            $this->saveKnowledge(
                                'product_feedback',
                                $productName,
                                "Khách hàng khen {$productName}: \"{$userMsg}\"",
                                'conversation',
                                $defaultConfidence,
                                $sessionId
                            );
                            break;
                        }
                    }
                    break;
                }
            }

            // === Pattern 2: Khách hỏi và AI trả lời tốt (FAQ tiềm năng) ===
            $faqPatterns = [
                '/(?:làm sao|thế nào|bao nhiêu|ở đâu|khi nào|tại sao|có không|được không)/u',
            ];
            foreach ($faqPatterns as $pattern) {
                if (preg_match($pattern, $lowerMsg) && mb_strlen($aiResponse) > 50) {
                    // Chỉ lưu nếu chưa có kiến thức tương tự
                    $existing = AiKnowledge::findRelevant($userMsg, 1);
                    if ($existing->isEmpty()) {
                        // Tạo tóm tắt ngắn
                        $summary = mb_substr($aiResponse, 0, 200, 'UTF-8');
                        if (mb_strlen($aiResponse, 'UTF-8') > 200) {
                            $summary .= '...';
                        }

                        $this->saveKnowledge(
                            'faq',
                            $userMsg,
                            $summary,
                            'conversation',
                            $defaultConfidence - 10, // FAQ tự động có confidence thấp hơn
                            $sessionId
                        );
                    }
                    break;
                }
            }

            // === Pattern 3: Khách chia sẻ sở thích ===
            $preferencePatterns = [
                '/(?:thích|yêu thích|hay uống|thường chọn|ghiền|mê|gu của tôi|gu của tao)/u',
            ];
            foreach ($preferencePatterns as $pattern) {
                if (preg_match($pattern, $lowerMsg)) {
                    $this->saveKnowledge(
                        'customer_preference',
                        $userMsg,
                        "Sở thích khách: \"{$userMsg}\"",
                        'conversation',
                        $defaultConfidence,
                        $sessionId
                    );
                    break;
                }
            }

        } catch (\Exception $e) {
            Log::error('Knowledge extraction error: ' . $e->getMessage());
        }
    }

    /**
     * Lưu kiến thức từ web search
     */
    private function saveWebSearchKnowledge(string $query, string $result): void
    {
        try {
            $summary = mb_substr($result, 0, 300, 'UTF-8');
            $this->saveKnowledge(
                'general_knowledge',
                $query,
                $summary,
                'web_search',
                60,
                null
            );
        } catch (\Exception $e) {
            Log::error('Web search knowledge save error: ' . $e->getMessage());
        }
    }

    /**
     * Lưu một mục kiến thức vào Knowledge Base (tránh trùng lặp)
     */
    private function saveKnowledge(string $category, string $questionPattern, string $knowledge, string $source, int $confidence, $sessionId): void
    {
        // Kiểm tra kiến thức tương tự đã tồn tại chưa
        $existing = AiKnowledge::where('category', $category)
            ->where('question_pattern', $questionPattern)
            ->first();

        if ($existing) {
            // Cập nhật confidence nếu được xác nhận lại
            $existing->boostConfidence(3);
            return;
        }

        AiKnowledge::create([
            'category'                => $category,
            'question_pattern'        => $questionPattern,
            'knowledge'               => $knowledge,
            'source'                  => $source,
            'confidence'              => $confidence,
            'learned_from_session_id' => $sessionId,
        ]);
    }

    /**
     * Dọn dẹp response text (bỏ các tag nội bộ)
     */
    private function cleanResponseText(string $text): string
    {
        // Bỏ [SEARCH_NEEDED: ...] tag
        $text = preg_replace('/\[SEARCH_NEEDED:\s*.+?\]\s*/i', '', $text);

        // Bỏ khoảng trắng thừa cuối
        $text = rtrim($text);

        return $text;
    }

    /**
     * Fallback response khi không có API key hoặc lỗi
     */
    private function fallbackResponse(): array
    {
        return [
            'text' => "👋 Xin chào! Tôi là **AI Trợ Lý CozyHNA**. Tôi có thể giúp bạn:\n"
                . "• 🍵 **Tư vấn thức uống thơm ngon** phù hợp với khẩu vị\n"
                . "• 💳 **Hướng dẫn Thanh toán VietQR / Ngân hàng nhanh**\n"
                . "• 📦 **Tra cứu tiến độ & trạng thái đơn hàng** (Hãy gửi mã đơn `ORD-xxxxx` cho tôi)\n\n"
                . "Bạn cần hỗ trợ điều gì hôm nay?",
            'type' => 'general',
        ];
    }
}
