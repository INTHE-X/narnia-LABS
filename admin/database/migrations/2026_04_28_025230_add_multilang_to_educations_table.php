<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            if (!Schema::hasColumn('educations', 'title_en')) {
                $table->string('title_en', 255)->nullable()->after('title');
            }
            if (!Schema::hasColumn('educations', 'title_jp')) {
                $table->string('title_jp', 255)->nullable()->after('title_en');
            }
            if (!Schema::hasColumn('educations', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('educations', 'description_jp')) {
                $table->text('description_jp')->nullable()->after('description_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            foreach (['title_en', 'title_jp', 'description_en', 'description_jp'] as $col) {
                if (Schema::hasColumn('educations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
