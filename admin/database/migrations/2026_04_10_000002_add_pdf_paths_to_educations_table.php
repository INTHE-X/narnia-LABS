<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * educations 테이블에 PDF 파일 경로를 JSON으로 저장하는 컬럼 추가
     */
    public function up(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            // JSON 배열: ['uploads/education_pdfs/uuid.pdf', ...]
            $table->json('pdf_paths')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            $table->dropColumn('pdf_paths');
        });
    }
};
