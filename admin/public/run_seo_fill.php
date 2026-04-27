<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SeoSetting;
use App\Http\Controllers\SeoController;

$pages = SeoController::pages();
foreach ($pages as $p) {
    if ($p['key'] === 'global') continue;
    $title = $p['name'] . " | 나니아랩스 (Narnia Labs)";
    $desc = "나니아랩스(Narnia Labs)의 " . $p['name'] . " 페이지입니다. 딥 제너레이티브 디자인 기술로 제조 산업의 혁신을 선도하는 나니아랩스의 다양한 소식과 솔루션을 만나보세요.";
    $keywords = "나니아랩스, Narnia Labs, 생성형 AI, 딥 제너레이티브 디자인, AI 설계, 최적화 솔루션, 제조 혁신, " . $p['name'];

    SeoSetting::updateOrCreate(
        ['page_key' => $p['key']],
        [
            'page_name'        => $p['name'],
            'page_url'         => $p['url'],
            'meta_title'       => $title,
            'meta_description' => $desc,
            'meta_keywords'    => $keywords,
            'og_title'         => $title,
            'og_description'   => $desc,
            'og_image'         => 'https://narnialabs.mycafe24.com/assets/img/common/logo.png',
            'twitter_card'     => 'summary_large_image',
            'index_allow'      => 1,
            'canonical_url'    => 'https://narnialabs.mycafe24.com' . $p['url']
        ]
    );
}
echo "SEO Update Success";
unlink(__FILE__);
