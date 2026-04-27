<?php
/**
 * 배포용 엔트리포인트 — OPcache를 우회하기 위해 새 파일명 사용
 * 이 파일은 OPcache에 없으므로 즉시 디스크에서 실행됩니다.
 */

// OPcache 초기화
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// 뷰 캐시 삭제
$viewDir = __DIR__ . '/../storage/framework/views';
if (is_dir($viewDir)) {
    $files = glob($viewDir . '/*.php');
    foreach ($files as $f) { @unlink($f); }
    echo "뷰 캐시 " . count($files) . "개 삭제 완료\n";
}

// 라우트 캐시 삭제
$routeCache = __DIR__ . '/../bootstrap/cache/routes-v7.php';
if (file_exists($routeCache)) { @unlink($routeCache); echo "라우트 캐시 삭제\n"; }

// config 캐시 삭제
$configCache = __DIR__ . '/../bootstrap/cache/config.php';
if (file_exists($configCache)) { @unlink($configCache); echo "설정 캐시 삭제\n"; }

echo "\n✅ 모든 캐시 초기화 완료!\n";
echo "OPcache: " . (function_exists('opcache_reset') ? '초기화됨' : '사용안함') . "\n";
echo "\n이제 /admin/login 으로 접속해주세요.\n";
echo "⚠️ 이 파일은 확인 후 삭제하세요!";
