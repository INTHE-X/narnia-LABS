<?php
/**
 * 뷰 캐시 삭제 스크립트
 * 접속: https://narnialabs.mycafe24.com/admin/public/clear-cache.php
 * 확인 후 삭제하세요!
 */

$viewCache = __DIR__ . '/../storage/framework/views';

if (is_dir($viewCache)) {
    $files = glob($viewCache . '/*.php');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    echo "✅ 뷰 캐시 {$count}개 파일 삭제 완료!<br>";
} else {
    echo "❌ 뷰 캐시 디렉토리를 찾을 수 없습니다: {$viewCache}<br>";
}

echo "<br>완료. 이 파일을 삭제하세요.";
