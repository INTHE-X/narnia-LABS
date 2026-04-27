<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tech_blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('tech_blogs', 'title_en')) {
                $table->string('title_en', 500)->nullable()->after('title');
            }
            if (!Schema::hasColumn('tech_blogs', 'title_jp')) {
                $table->string('title_jp', 500)->nullable()->after('title_en');
            }
            if (!Schema::hasColumn('tech_blogs', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('tech_blogs', 'description_jp')) {
                $table->text('description_jp')->nullable()->after('description_en');
            }
            if (!Schema::hasColumn('tech_blogs', 'image_path')) {
                $table->string('image_path', 500)->nullable()->after('description_jp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tech_blogs', function (Blueprint $table) {
            foreach (['title_en', 'title_jp', 'description_en', 'description_jp', 'image_path'] as $col) {
                if (Schema::hasColumn('tech_blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
