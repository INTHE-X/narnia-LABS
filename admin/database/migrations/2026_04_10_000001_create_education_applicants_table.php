<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_applicants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('education_id')->comment('연결된 교육 콘텐츠 ID');
            $table->string('name',        100)->comment('이름');
            $table->string('email',       200)->comment('이메일');
            $table->string('phone',        30)->nullable()->comment('전화번호');
            $table->string('company',     200)->nullable()->comment('회사명');
            $table->string('jobtitle',    100)->nullable()->comment('직급');
            $table->string('department',  100)->nullable()->comment('부서');
            $table->timestamps();

            $table->foreign('education_id')
                  ->references('id')
                  ->on('educations')
                  ->onDelete('cascade');

            $table->index('education_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_applicants');
    }
};
