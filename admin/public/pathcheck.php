<?php
// Laravel bootstrap
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo '<pre>';
echo 'base_path: ' . base_path() . "\n";
echo 'view_path: ' . resource_path('views') . "\n";
echo 'storage_path: ' . storage_path('framework/views') . "\n";

$editView = resource_path('views/admin/education/edit.blade.php');
echo 'edit.blade.php exists: ' . (file_exists($editView) ? 'YES' : 'NO') . "\n";
echo 'edit.blade.php path: ' . $editView . "\n";
if (file_exists($editView)) {
    echo 'edit.blade.php modified: ' . date('Y-m-d H:i:s', filemtime($editView)) . "\n";
    echo 'contains banner: ' . (strpos(file_get_contents($editView), 'edu-img-banner') !== false ? 'YES ✓' : 'NO ✗') . "\n";
    echo 'contains zip: ' . (strpos(file_get_contents($editView), '.zip') !== false ? 'YES ✓' : 'NO ✗') . "\n";
}
echo '</pre>';
