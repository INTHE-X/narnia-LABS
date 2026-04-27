<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('source')->nullable();      // 데일리경제, 연합뉴스 등
            $table->string('category')->default('news'); // news, press
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_featured')->default(false); // 상단 슬라이드 노출
            $table->date('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
