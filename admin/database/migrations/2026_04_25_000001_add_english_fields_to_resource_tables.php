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
            $table->string('title_en', 500)->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        // case_studies
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        // educations
        Schema::table('educations', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        // events
        Schema::table('events', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        // publications
        Schema::table('publications', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        // news
        Schema::table('news', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('content_en')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('tech_blogs', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('educations', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'content_en']);
        });
    }
};
