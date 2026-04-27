<?php

namespace App\Services;

class GoogleAnalyticsService
{
    private $propertyId;
    private $keyFile;
    private $accessToken = null;

    public function __construct($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->keyFile = storage_path('app/google-analytics-key.json');
    }

    // ── 총 사용자 + 증감률 (최근 30일 vs 이전 30일) ──────────────────
    public function getTotalUsers()
    {
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        $data = [
            'dateRanges' => [
                ['startDate' => '30daysAgo', 'endDate' => 'today', 'name' => 'current'],
                ['startDate' => '60daysAgo', 'endDate' => '31daysAgo', 'name' => 'previous'],
            ],
            'metrics' => [['name' => 'totalUsers']],
        ];
        return $this->apiCall($url, $data);
    }

    // ── 일별 신규 사용자 (꺾은선 차트) ──────────────────────────────
    public function getDailyUsers($days = 90)
    {
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        $data = [
            'dateRanges' => [['startDate' => "{$days}daysAgo", 'endDate' => 'today']],
            'dimensions' => [['name' => 'date']],
            'metrics'    => [['name' => 'newUsers']],
            'orderBys'   => [['dimension' => ['dimensionName' => 'date']]],
        ];
        return $this->apiCall($url, $data);
    }

    // ── 첫 사용자 유입 경로 ──────────────────────────────────────────
    public function getTrafficSources($limit = 10)
    {
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        $data = [
            'dateRanges' => [['startDate' => '30daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'firstUserDefaultChannelGroup'], ['name' => 'firstUserSource'], ['name' => 'firstUserMedium']],
            'metrics'    => [['name' => 'sessions'], ['name' => 'screenPageViews']],
            'orderBys'   => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
            'limit'      => $limit,
        ];
        return $this->apiCall($url, $data);
    }

    // ── 방문 페이지 + 이탈률 ─────────────────────────────────────────
    public function getTopPages($limit = 10)
    {
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        $data = [
            'dateRanges' => [['startDate' => '30daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'pagePath']],
            'metrics'    => [['name' => 'sessions'], ['name' => 'screenPageViews'], ['name' => 'bounceRate']],
            'orderBys'   => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
            'limit'      => $limit,
        ];
        return $this->apiCall($url, $data);
    }

    // ── 국가별 사용자 ────────────────────────────────────────────────
    public function getUsersByCountry($limit = 10)
    {
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        $data = [
            'dateRanges' => [['startDate' => '30daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'country']],
            'metrics'    => [['name' => 'totalUsers']],
            'orderBys'   => [['metric' => ['metricName' => 'totalUsers'], 'desc' => true]],
            'limit'      => $limit,
        ];
        return $this->apiCall($url, $data);
    }

    // ── 범용 runReport (컨트롤러에서 파라미터 직접 구성) ────────────
    public function runReport(array $params)
    {
        // null 값 필터 제거
        $params = array_filter($params, fn($v) => $v !== null);
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        return $this->apiCall($url, $params);
    }

    // ── 기존 호환 ────────────────────────────────────────────────────
    public function getActiveUsers()
    {
        return $this->runReport([
            'dateRanges' => [['startDate' => 'today', 'endDate' => 'today']],
            'metrics'    => [['name' => 'activeUsers'], ['name' => 'sessions'], ['name' => 'screenPageViews']],
        ]);
    }

    // ── JWT → Access Token ───────────────────────────────────────────
    private function getAccessToken()
    {
        if ($this->accessToken) return $this->accessToken;
        if (!file_exists($this->keyFile)) return null;
        $keyData = json_decode(file_get_contents($this->keyFile), true);
        if (!$keyData) return null;

        $header  = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $time    = time();
        $payload = $this->base64UrlEncode(json_encode([
            'iss'   => $keyData['client_email'],
            'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $time + 3600,
            'iat'   => $time,
        ]));

        $src = "$header.$payload";
        if (!openssl_sign($src, $sig, $keyData['private_key'], 'SHA256')) return null;
        $jwt = "$src." . $this->base64UrlEncode($sig);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]));
        $res = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($res, true);
        $this->accessToken = $response['access_token'] ?? null;
        return $this->accessToken;
    }

    private function apiCall($url, $data)
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
