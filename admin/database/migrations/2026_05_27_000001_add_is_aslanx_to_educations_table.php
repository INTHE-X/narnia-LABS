<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            if (!Schema::hasColumn('educations', 'is_aslanx')) {
                $table->boolean('is_aslanx')->default(false)->after('is_visible');
            }
        });
    }

    public function down(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            $table->dropColumn('is_aslanx');
        });
    }
};
