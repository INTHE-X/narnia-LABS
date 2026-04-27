<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();     // 'home', 'about', 'news' 등 고유 식별자
            $table->string('page_name');              // 표시용 이름 "홈페이지"
            $table->string('page_url')->nullable();   // /kor/html/company/about.html

            // 기본 메타
            $table->string('meta_title', 100)->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('meta_keywords', 500)->nullable();

            // OG (Open Graph)
            $table->string('og_title', 100)->nullable();
            $table->string('og_description', 320)->nullable();
            $table->string('og_image')->nullable();  // URL

            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image');

            // 기타
            $table->boolean('index_allow')->default(true);  // robots noindex
            $table->string('canonical_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
