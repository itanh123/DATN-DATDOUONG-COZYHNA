<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ai_knowledge_base')) {
            Schema::create('ai_knowledge_base', function (Blueprint $table) {
                $table->id();
                $table->string('category', 50);        // 'product_feedback', 'customer_preference', 'faq', 'general_knowledge'
                $table->text('question_pattern');        // Mẫu câu hỏi / từ khóa liên quan
                $table->text('knowledge');               // Kiến thức đã học
                $table->string('source', 30)->default('conversation'); // 'conversation', 'web_search', 'admin'
                $table->unsignedTinyInteger('confidence')->default(50); // 0-100
                $table->unsignedInteger('usage_count')->default(0);
                $table->unsignedBigInteger('learned_from_session_id')->nullable();
                $table->timestamps();

                $table->index('category');
                $table->index('confidence');
            });
        }

        if (!Schema::hasTable('ai_message_feedback')) {
            Schema::create('ai_message_feedback', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained('chat_messages')->onDelete('cascade');
                $table->enum('feedback', ['positive', 'negative']);
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique('message_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('ai_message_feedback');
        Schema::dropIfExists('ai_knowledge_base');
    }
};
