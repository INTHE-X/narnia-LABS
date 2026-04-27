<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('migrate', [
    '--path' => 'database/migrations/2026_04_27_000002_add_multi_language_to_teams_table.php',
    '--force' => true
]);

echo "Migration status: " . $status . "\n";
echo $kernel->output();
