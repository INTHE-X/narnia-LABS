<?php

use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    echo "Running migrations...<br>";
    Artisan::call('migrate', ['--force' => true]);
    echo nl2br(Artisan::output());
    echo "<br>Migrations completed successfully.";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
