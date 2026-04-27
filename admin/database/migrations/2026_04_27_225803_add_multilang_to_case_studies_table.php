<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            if (!Schema::hasColumn('case_studies', 'title_en')) {
                $table->string('title_en', 500)->nullable()->after('title');
            }
            if (!Schema::hasColumn('case_studies', 'title_jp')) {
                $table->string('title_jp', 500)->nullable()->after('title_en');
            }
            if (!Schema::hasColumn('case_studies', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('case_studies', 'description_jp')) {
                $table->text('description_jp')->nullable()->after('description_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            foreach (['title_en', 'title_jp', 'description_en', 'description_jp'] as $col) {
                if (Schema::hasColumn('case_studies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
