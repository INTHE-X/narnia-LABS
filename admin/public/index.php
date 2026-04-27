<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 배포 후 캐시 초기화 (1회 자동 실행)
$deployFlag = __DIR__ . '/../storage/framework/.deploy_v7';
if (!file_exists($deployFlag)) {
    // PHP OPcache 초기화
    if (function_exists('opcache_reset')) { opcache_reset(); }
    // 뷰 캐시 삭제
    $viewDir = __DIR__ . '/../storage/framework/views';
    if (is_dir($viewDir)) {
        foreach (glob($viewDir . '/*.php') as $f) { @unlink($f); }
    }
    @file_put_contents($deployFlag, date('Y-m-d H:i:s'));
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
