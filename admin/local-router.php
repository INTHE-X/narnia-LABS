<?php
/**
 * 로컬 개발용 PHP 내장 서버 라우터
 * public/admin/ 디렉토리가 있어도 Laravel 라우터로 정상 전달
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 실제로 존재하는 파일은 바로 서빙 (단, /admin/ 디렉토리는 제외하고 index.php로)
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri) && !is_dir(__DIR__ . '/public' . $uri)) {
    return false; // PHP 내장 서버가 직접 서빙
}

// 나머지 모두 Laravel index.php로
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
$_SERVER['SCRIPT_NAME']     = '/index.php';

require __DIR__ . '/public/index.php';
