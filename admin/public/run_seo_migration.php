<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    if (!Illuminate\Support\Facades\Schema::hasColumn('seo_settings', 'head_script')) {
        Illuminate\Support\Facades\Schema::table('seo_settings', function ($table) {
            $table->longText('head_script')->nullable()->after('canonical_url');
        });
        echo 'head_script 컬럼 추가 완료!';
    } else {
        echo 'head_script 컬럼이 이미 존재합니다.';
    }
} catch (Exception $e) {
    echo '오류: ' . $e->getMessage();
}
