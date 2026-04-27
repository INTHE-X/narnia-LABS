<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_jp')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_jp')->nullable()->after('description_en');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_jp', 'description_en', 'description_jp']);
        });
    }
};
