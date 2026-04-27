<?php
/**
 * Narnia Labs Admin — 서버 초기 설정 스크립트
 * 이 파일을 admin/public/ 폴더에 업로드 후 브라우저에서 한 번만 실행하세요.
 * 실행 후 반드시 삭제하세요!
 *
 * 접속: https://narnialabs.mycafe24.com/admin/setup.php
 */

echo "<h2>Narnia Labs Admin — 서버 초기 설정</h2><pre>";

// 1. storage 퍼미션 설정
$dirs = [
    __DIR__ . '/../../storage',
    __DIR__ . '/../../storage/app',
    __DIR__ . '/../../storage/app/public',
    __DIR__ . '/../../storage/framework',
    __DIR__ . '/../../storage/framework/cache',
    __DIR__ . '/../../storage/framework/sessions',
    __DIR__ . '/../../storage/framework/views',
    __DIR__ . '/../../storage/logs',
    __DIR__ . '/../../bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ 생성됨: $dir\n";
        } else {
            echo "❌ 생성 실패: $dir\n";
        }
    } else {
        if (chmod($dir, 0755)) {
            echo "✅ 권한 설정: $dir (755)\n";
        } else {
            echo "⚠️  권한 설정 실패 (계속 진행): $dir\n";
        }
    }
}

// 2. SQLite DB 파일 확인
$dbPath = __DIR__ . '/../../database/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
    echo "✅ SQLite DB 생성: $dbPath\n";
} else {
    echo "✅ SQLite DB 존재: $dbPath\n";
}

// 3. PHP 버전 확인
echo "\n📋 서버 정보:\n";
echo "  PHP 버전: " . PHP_VERSION . "\n";
echo "  SQLite: " . (extension_loaded('pdo_sqlite') ? '✅ 사용가능' : '❌ 미지원') . "\n";
echo "  OpenSSL: " . (extension_loaded('openssl') ? '✅ 사용가능' : '❌ 미지원') . "\n";
echo "  Mbstring: " . (extension_loaded('mbstring') ? '✅ 사용가능' : '❌ 미지원') . "\n";

echo "\n✅ 완료! 이 파일(setup.php)을 서버에서 삭제하세요.\n";
echo "</pre>";
