<?php
// 일회용 마이그레이션 실행 스크립트 - 실행 후 반드시 삭제할 것
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Schema::table('popups', function ($table) {
        if (!Illuminate\Support\Facades\Schema::hasColumn('popups', 'position')) {
            $table->string('position')->default('center')->after('status');
            echo 'position 컬럼 추가 완료!';
        } else {
            echo 'position 컬럼이 이미 존재합니다.';
        }
    });
} catch (Exception $e) {
    echo '오류: ' . $e->getMessage();
}
