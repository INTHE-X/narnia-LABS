<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    if (!Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'is_published')) {
        Illuminate\Support\Facades\Schema::table('case_studies', function ($table) {
            $table->boolean('is_published')->default(true)->after('is_featured');
        });
        echo 'is_published 컬럼 추가 완료!';
    } else {
        echo 'is_published 컬럼이 이미 존재합니다.';
    }
} catch (Exception $e) {
    echo '오류: ' . $e->getMessage();
}
