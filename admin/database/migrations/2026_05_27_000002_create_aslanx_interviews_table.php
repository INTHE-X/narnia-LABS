<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aslanx_interviews', function (Blueprint $table) {
            $table->id();
            $table->string('company')->comment('회사명 (국문)');
            $table->string('company_en')->nullable()->comment('회사명 (영문)');
            $table->string('company_jp')->nullable()->comment('회사명 (일문)');
            $table->string('position')->nullable()->comment('직책 (국문)');
            $table->string('position_en')->nullable()->comment('직책 (영문)');
            $table->string('position_jp')->nullable()->comment('직책 (일문)');
            $table->text('content')->comment('인터뷰 내용 (국문)');
            $table->text('content_en')->nullable()->comment('인터뷰 내용 (영문)');
            $table->text('content_jp')->nullable()->comment('인터뷰 내용 (일문)');
            $table->string('logo_path')->nullable()->comment('회사 로고 이미지 경로');
            $table->boolean('is_visible')->default(true)->comment('노출 여부');
            $table->integer('sort_order')->default(0)->comment('정렬 순서');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aslanx_interviews');
    }
};
