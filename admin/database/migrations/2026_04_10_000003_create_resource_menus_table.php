<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->comment('메뉴 이름');
            $table->string('url', 500)->comment('링크 URL');
            $table->boolean('is_external')->default(true)->comment('새 탭 여부');
            $table->boolean('is_active')->default(true)->comment('노출 여부');
            $table->integer('sort_order')->default(0)->comment('정렬 순서');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_menus');
    }
};
