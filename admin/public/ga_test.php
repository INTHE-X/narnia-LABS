<?php
// GA4 연결 진단 스크립트
$keyFile = __DIR__ . '/../storage/app/google-analytics-key.json';
$propertyId = '389432658';

echo "<pre style='font-family:monospace;font-size:13px;'>";
echo "=== GA4 진단 시작 ===\n\n";

// 1. 키 파일 확인
echo "1. 키 파일 경로: $keyFile\n";
if (!file_exists($keyFile)) {
    echo "   ❌ 키 파일 없음!\n"; exit;
}
echo "   ✅ 키 파일 존재\n";

$keyData = json_decode(file_get_contents($keyFile), true);
if (!$keyData) {
    echo "   ❌ JSON 파싱 실패\n"; exit;
}
echo "   ✅ client_email: " . $keyData['client_email'] . "\n";
echo "   ✅ project_id: " . $keyData['project_id'] . "\n\n";

// 2. JWT 생성
echo "2. JWT 생성 중...\n";
$header  = rtrim(strtr(base64_encode(json_encode(['alg'=>'RS256','typ'=>'JWT'])),'+/','-_'),'=');
$time    = time();
$payload = rtrim(strtr(base64_encode(json_encode([
    'iss'   => $keyData['client_email'],
    'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
    'aud'   => 'https://oauth2.googleapis.com/token',
    'exp'   => $time + 3600,
    'iat'   => $time,
])),'+/','-_'),'=');

$src = "$header.$payload";
$ok  = openssl_sign($src, $sig, $keyData['private_key'], 'SHA256');
if (!$ok) {
    echo "   ❌ 서명 실패 (openssl_sign)\n"; exit;
}
$jwt = "$src." . rtrim(strtr(base64_encode($sig),'+/','-_'),'=');
echo "   ✅ JWT 생성 성공\n\n";

// 3. Access Token 요청
echo "3. Access Token 요청 중...\n";
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion'  => $jwt,
]));
$res = curl_exec($ch);
$curlErr = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    echo "   ❌ curl 오류: $curlErr\n"; exit;
}

$tokenRes = json_decode($res, true);
echo "   응답: " . $res . "\n";
if (empty($tokenRes['access_token'])) {
    echo "   ❌ 토큰 없음 - 위 응답 확인\n"; exit;
}
$token = $tokenRes['access_token'];
echo "   ✅ Access Token 획득 성공\n\n";

// 4. GA4 API 호출
echo "4. GA4 API 호출 중 (property: $propertyId)...\n";
$url = "https://analyticsdata.googleapis.com/v1beta/properties/{$propertyId}:runReport";
$body = json_encode([
    'dateRanges' => [['startDate'=>'30daysAgo','endDate'=>'today']],
    'metrics'    => [['name'=>'totalUsers']],
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '.$token,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
$res = curl_exec($ch);
curl_close($ch);

$apiRes = json_decode($res, true);
echo "   응답: " . $res . "\n\n";

if (!empty($apiRes['error'])) {
    echo "   ❌ API 오류: " . $apiRes['error']['message'] . "\n";
    echo "   코드: " . $apiRes['error']['code'] . "\n";
    echo "   상태: " . $apiRes['error']['status'] . "\n";
    if ($apiRes['error']['code'] == 403) {
        echo "\n   🔑 권한 없음 → GA4 속성 액세스 관리에서 서비스 계정 이메일을 뷰어로 추가해주세요:\n";
        echo "   " . $keyData['client_email'] . "\n";
    }
} else {
    $users = $apiRes['rows'][0]['metricValues'][0]['value'] ?? '없음';
    echo "   ✅ 성공! 총 사용자(30일): $users\n";
}

echo "\n=== 진단 완료 ===";
echo "</pre>";
