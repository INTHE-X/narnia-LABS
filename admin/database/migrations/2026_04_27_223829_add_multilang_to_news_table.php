<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'title_en')) {
                $table->string('title_en', 500)->nullable()->after('title');
            }
            if (!Schema::hasColumn('news', 'title_jp')) {
                $table->string('title_jp', 500)->nullable()->after('title_en');
            }
            if (!Schema::hasColumn('news', 'content_en')) {
                $table->text('content_en')->nullable()->after('content');
            }
            if (!Schema::hasColumn('news', 'content_jp')) {
                $table->text('content_jp')->nullable()->after('content_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $cols = [];
            foreach (['title_en', 'title_jp', 'content_en', 'content_jp'] as $col) {
                if (Schema::hasColumn('news', $col)) $cols[] = $col;
            }
            if ($cols) $table->dropColumn($cols);
        });
    }
};
