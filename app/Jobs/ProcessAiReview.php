<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ProductReview;

class ProcessAiReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $review;

    /**
     * Create a new job instance.
     */
    public function __construct(ProductReview $review)
    {
        $this->review = $review;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $review = $this->review;
        $comment = $review->comment;

        if (empty(trim($comment))) {
            $review->status = 'approved';
            $review->save();
            return;
        }

        $geminiApiKey = env('GEMINI_API_KEY');

        if (!$geminiApiKey) {
            $this->fallbackCheck($review, $comment);
            return;
        }

        try {
            $prompt = "Đánh giá của khách hàng: '{$comment}'\n";
            $prompt .= "Nhiệm vụ:\n";
            $prompt .= "1. Kiểm tra xem đánh giá này có chứa từ khóa nhạy cảm, chửi thề, tục tĩu hay không.\n";
            $prompt .= "2. Nếu có, hãy trả về kết quả dưới định dạng JSON CHÍNH XÁC: {\"status\": \"rejected\", \"reply\": \"\"}\n";
            $prompt .= "3. Nếu không, hãy trả về kết quả dưới định dạng JSON CHÍNH XÁC: {\"status\": \"approved\", \"reply\": \"<câu phản hồi lịch sự, thân thiện và cảm ơn khách hàng bằng tiếng Việt>\"}\n";
            $prompt .= "CHÚ Ý QUAN TRỌNG: CHỈ trả về ĐÚNG chuỗi JSON hợp lệ, không kèm Markdown, không kèm ngoặc kép markdown (```json), không kèm bất kỳ văn bản nào khác.";

            $apiRes = Http::withHeaders(['Content-Type' => 'application/json'])
                ->withoutVerifying()
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$geminiApiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

            if ($apiRes->successful()) {
                $geminiText = $apiRes->json('candidates.0.content.parts.0.text');
                Log::info('Gemini Raw Response: ' . $geminiText);
                if ($geminiText) {
                    $geminiText = trim($geminiText);
                    $geminiText = str_replace(['```json', '```'], '', $geminiText);
                    $geminiText = trim($geminiText);
                    
                    Log::info('Gemini Cleaned JSON: ' . $geminiText);

                    $data = json_decode($geminiText, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && isset($data['status'])) {
                        $review->status = $data['status'] === 'rejected' ? 'rejected' : 'approved';
                        if ($review->status === 'approved' && !empty($data['reply'])) {
                            $review->admin_reply = $data['reply'];
                        }
                        $review->save();
                        return;
                    } else {
                        Log::warning('Gemini JSON Decode Failed: ' . json_last_error_msg());
                    }
                }
            } else {
                Log::error('Gemini API Error Response: ' . $apiRes->body());
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Error in ProcessAiReview: ' . $e->getMessage());
        }

        $this->fallbackCheck($review, $comment);
    }

    private function fallbackCheck(ProductReview $review, string $comment)
    {
        $status = 'approved';
        $badWords = ['địt', 'lồn', 'cặc', 'buồi', 'đụ', 'chó', 'điếm', 'đĩ', 'đm', 'vcl', 'vl', 'đcm', 'dkm', 'ngu', 'cc', 'cứt', 'fuck', 'shit', 'bitch'];
        foreach ($badWords as $word) {
            if (preg_match("/\b" . preg_quote($word, '/') . "\b/ui", $comment)) {
                $status = 'rejected';
                break;
            }
        }
        
        $review->status = $status;
        $review->save();
    }
}
