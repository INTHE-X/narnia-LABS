<?php
/**
 * 마이그레이션 실행 스크립트
 * 실행 후 반드시 삭제하세요!
 */
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre style='font-family:monospace;padding:20px;background:#111;color:#0f0;'>";
echo "=== Artisan migrate --force ===\n\n";

$kernel->call('migrate', ['--force' => true]);
echo $kernel->output();

echo "\n=== 완료 ===\n";
echo "이 파일을 즉시 삭제하세요!\n";
echo "</pre>";
