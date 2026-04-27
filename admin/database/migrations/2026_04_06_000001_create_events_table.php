<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Conference'); // Conference, Exhibition 등
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('image_path')->nullable(); // 업로드 이미지 경로
            $table->string('link')->nullable();       // 외부 링크
            $table->boolean('is_featured')->default(false); // 상단 슬라이드 노출 여부
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
