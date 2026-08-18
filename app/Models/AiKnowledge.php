<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiKnowledge extends Model
{
    protected $table = 'ai_knowledge_base';

    protected $fillable = [
        'category',
        'question_pattern',
        'knowledge',
        'source',
        'confidence',
        'usage_count',
        'learned_from_session_id',
    ];

    protected $casts = [
        'confidence' => 'integer',
        'usage_count' => 'integer',
    ];

    /**
     * Tìm kiến thức liên quan dựa trên từ khóa
     */
    public static function findRelevant(string $query, int $limit = 5): \Illuminate\Support\Collection
    {
        $keywords = self::extractKeywords($query);

        if (empty($keywords)) {
            return collect();
        }

        $q = self::where('confidence', '>=', 30)
            ->orderByDesc('confidence')
            ->orderByDesc('usage_count');

        $q->where(function ($builder) use ($keywords) {
            foreach ($keywords as $kw) {
                $builder->orWhere('question_pattern', 'LIKE', "%{$kw}%")
                        ->orWhere('knowledge', 'LIKE', "%{$kw}%");
            }
        });

        return $q->take($limit)->get();
    }

    /**
     * Tách từ khóa quan trọng từ câu hỏi
     */
    private static function extractKeywords(string $text): array
    {
        // Loại bỏ các stop words tiếng Việt
        $stopWords = ['là', 'và', 'của', 'có', 'không', 'được', 'cho', 'với', 'này', 'đó',
            'một', 'các', 'những', 'trong', 'từ', 'đến', 'tôi', 'bạn', 'ơi', 'nhé',
            'nha', 'hả', 'vậy', 'thì', 'mà', 'nào', 'gì', 'sao', 'thế', 'đi',
            'rồi', 'lại', 'còn', 'cũng', 'đã', 'sẽ', 'đang', 'hãy', 'hay', 'hoặc',
            'nhưng', 'nên', 'vì', 'nếu', 'khi', 'ở', 'ra', 'lên', 'xuống', 'vào',
            'về', 'theo', 'qua', 'trên', 'dưới', 'giữa', 'sau', 'trước', 'như', 'the',
            'what', 'how', 'where', 'when', 'who', 'which', 'tao', 'mày', 'hỏi', 'muốn'];

        $text = mb_strtolower($text, 'UTF-8');
        // Tách bằng khoảng trắng và dấu câu
        $words = preg_split('/[\s,.\?\!\;\:]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter($words, fn($w) => mb_strlen($w) >= 2 && !in_array($w, $stopWords));

        return array_values(array_unique($words));
    }

    /**
     * Tăng confidence
     */
    public function boostConfidence(int $amount = 5): void
    {
        $this->confidence = min(100, $this->confidence + $amount);
        $this->save();
    }

    /**
     * Giảm confidence
     */
    public function penalizeConfidence(int $amount = 10): void
    {
        $this->confidence = max(0, $this->confidence - $amount);
        $this->save();
    }

    /**
     * Tăng usage_count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
