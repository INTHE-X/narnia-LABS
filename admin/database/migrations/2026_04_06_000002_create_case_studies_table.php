<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');        // Automobile, Electronics, Others
            $table->string('tags')->nullable(); // "#Generator,#Evaluator" 쉼표 구분
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_featured')->default(false); // 상단 슬라이드 노출
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
