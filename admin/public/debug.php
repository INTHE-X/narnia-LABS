<?php
/**
 * Narnia Labs Admin — 자산 경로 디버그
 * 확인 후 삭제하세요!
 * 접속: https://narnialabs.mycafe24.com/admin/public/debug.php
 */

// Laravel bootstrap
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;} table{border-collapse:collapse;} td,th{padding:8px 12px;border:1px solid #ddd;text-align:left;} th{background:#eee;}</style>";
echo "<h2>어드민 디버그 정보</h2>";

// Helper
$assetFn = app('url');

echo "<h3>1. asset() URL 확인</h3><table>";
echo "<tr><th>함수</th><th>결과</th></tr>";
echo "<tr><td>asset('admin/css/admin.css')</td><td>" . asset('admin/css/admin.css') . "</td></tr>";
echo "<tr><td>asset('admin/js/admin.js')</td><td>" . asset('admin/js/admin.js') . "</td></tr>";
echo "</table>";

echo "<h3>2. 환경 변수</h3><table>";
echo "<tr><th>변수</th><th>값</th></tr>";
echo "<tr><td>APP_URL</td><td>" . env('APP_URL') . "</td></tr>";
echo "<tr><td>ASSET_URL</td><td>" . (env('ASSET_URL') ?: '<span style=\"color:red\">미설정!</span>') . "</td></tr>";
echo "<tr><td>APP_ENV</td><td>" . env('APP_ENV') . "</td></tr>";
echo "</table>";

echo "<h3>3. CSS 파일 존재 확인</h3><table>";
echo "<tr><th>파일 경로</th><th>존재 여부</th></tr>";
$cssPath = __DIR__ . '/admin/css/admin.css';
echo "<tr><td>{$cssPath}</td><td>" . (file_exists($cssPath) ? '✅ 있음' : '❌ 없음') . "</td></tr>";
echo "</table>";

echo "<h3>4. PHP 정보</h3><table>";
echo "<tr><td>PHP 버전</td><td>" . PHP_VERSION . "</td></tr>";
echo "<tr><td>SERVER_NAME</td><td>" . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "</td></tr>";
echo "<tr><td>REQUEST_URI</td><td>" . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</td></tr>";
echo "</table>";

echo "<p style='color:red'>⚠️ 확인 후 이 파일을 삭제하세요!</p>";
