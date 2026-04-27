<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            if (!Schema::hasColumn('publications', 'title_en')) {
                $table->string('title_en', 500)->nullable()->after('title');
            }
            if (!Schema::hasColumn('publications', 'title_jp')) {
                $table->string('title_jp', 500)->nullable()->after('title_en');
            }
            if (!Schema::hasColumn('publications', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('publications', 'description_jp')) {
                $table->text('description_jp')->nullable()->after('description_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            foreach (['title_en', 'title_jp', 'description_en', 'description_jp'] as $col) {
                if (Schema::hasColumn('publications', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
