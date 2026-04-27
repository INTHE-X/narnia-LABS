<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // tech_blogs
        Schema::table('tech_blogs', function (Blueprint $table) {
            $table->string('title_jp', 500)->nullable()->after('title_en');
            $table->text('description_jp')->nullable()->after('description_en');
        });

        // case_studies
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('title_jp')->nullable()->after('title_en');
            $table->text('description_jp')->nullable()->after('description_en');
        });

        // educations
        Schema::table('educations', function (Blueprint $table) {
            $table->string('title_jp')->nullable()->after('title_en');
            $table->text('description_jp')->nullable()->after('description_en');
        });

        // events
        Schema::table('events', function (Blueprint $table) {
            $table->string('title_jp')->nullable()->after('title_en');
            $table->text('description_jp')->nullable()->after('description_en');
        });

        // publications
        Schema::table('publications', function (Blueprint $table) {
            $table->string('title_jp')->nullable()->after('title_en');
            $table->text('description_jp')->nullable()->after('description_en');
        });

        // news
        Schema::table('news', function (Blueprint $table) {
            $table->string('title_jp')->nullable()->after('title_en');
            $table->text('content_jp')->nullable()->after('content_en');
        });
    }

    public function down(): void
    {
        Schema::table('tech_blogs', function (Blueprint $table) {
            $table->dropColumn(['title_jp', 'description_jp']);
        });
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn(['title_jp', 'description_jp']);
        });
        Schema::table('educations', function (Blueprint $table) {
            $table->dropColumn(['title_jp', 'description_jp']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['title_jp', 'description_jp']);
        });
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn(['title_jp', 'description_jp']);
        });
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_jp', 'content_jp']);
        });
    }
};
