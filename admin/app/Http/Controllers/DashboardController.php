<?php

namespace App\Http\Controllers;

use App\Services\GoogleAnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $ga;

    public function __construct()
    {
        $this->ga = new GoogleAnalyticsService('389432658');
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start', date('Y-01-01'));
        $endDate   = $request->get('end',   date('Y-m-d'));
        $country   = $request->get('country', '');

        $gaData = $this->fetchGaData($startDate, $endDate, $country);

        return view('admin.dashboard.index', compact('gaData', 'startDate', 'endDate', 'country'));
    }

    private function fetchGaData($startDate, $endDate, $country = '')
    {
        $result = [
            'totalUsers'     => 0,
            'prevUsers'      => 0,
            'changeRate'     => 0,
            'dailyLabels'    => [],
            'dailyValues'    => [],
            'trafficSources' => [],
            'topPages'       => [],
            'countries'      => [],
            'error'          => null,
        ];

        // 날짜 범위 계산 (이전 기간 비교용)
        $days = max(1, (int)((strtotime($endDate) - strtotime($startDate)) / 86400) + 1);
        $prevEnd   = date('Y-m-d', strtotime($startDate) - 86400);
        $prevStart = date('Y-m-d', strtotime($prevEnd) - ($days - 1) * 86400);

        // 국가 필터 (옵션)
        $countryFilter = $country ? [
            'filter' => [
                'fieldName' => 'country',
                'stringFilter' => ['matchType' => 'EXACT', 'value' => $country]
            ]
        ] : null;

        try {
            // 총 사용자 (현재 + 이전 기간)
            $totalData = $this->ga->runReport([
                'dateRanges' => [
                    ['startDate' => $startDate, 'endDate' => $endDate, 'name' => 'current'],
                    ['startDate' => $prevStart, 'endDate' => $prevEnd,   'name' => 'previous'],
                ],
                'metrics'         => [['name' => 'totalUsers']],
                'dimensionFilter' => $countryFilter,
            ]);
            if (!empty($totalData['rows'])) {
                foreach ($totalData['rows'] as $row) {
                    $range = $row['dimensionValues'][0]['value'] ?? '';
                    $val   = (int)($row['metricValues'][0]['value'] ?? 0);
                    if ($range === 'current') $result['totalUsers'] = $val;
                    if ($range === 'previous') $result['prevUsers'] = $val;
                }
                if ($result['prevUsers'] > 0) {
                    $result['changeRate'] = round(
                        ($result['totalUsers'] - $result['prevUsers']) / $result['prevUsers'] * 100, 1
                    );
                }
            }

            // 일별 신규 사용자 (꺾은선 차트)
            $dailyData = $this->ga->runReport([
                'dateRanges'      => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions'      => [['name' => 'date']],
                'metrics'         => [['name' => 'newUsers']],
                'orderBys'        => [['dimension' => ['dimensionName' => 'date']]],
                'dimensionFilter' => $countryFilter,
            ]);
            if (!empty($dailyData['rows'])) {
                foreach ($dailyData['rows'] as $row) {
                    $date = $row['dimensionValues'][0]['value'] ?? '';
                    $result['dailyLabels'][] = substr($date, 0, 4) . '년 ' . ltrim(substr($date, 4, 2), '0') . '월';
                    $result['dailyValues'][] = (int)($row['metricValues'][0]['value'] ?? 0);
                }
            }

            // 유입 경로
            $sourceData = $this->ga->runReport([
                'dateRanges'      => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions'      => [['name' => 'firstUserSource'], ['name' => 'firstUserMedium']],
                'metrics'         => [['name' => 'sessions'], ['name' => 'screenPageViews']],
                'orderBys'        => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                'limit'           => 10,
                'dimensionFilter' => $countryFilter,
            ]);
            if (!empty($sourceData['rows'])) {
                foreach ($sourceData['rows'] as $row) {
                    $src = ($row['dimensionValues'][0]['value'] ?? '-');
                    $med = ($row['dimensionValues'][1]['value'] ?? '-');
                    $result['trafficSources'][] = [
                        'source'   => $src . ' / ' . $med,
                        'sessions' => (int)($row['metricValues'][0]['value'] ?? 0),
                        'views'    => (int)($row['metricValues'][1]['value'] ?? 0),
                    ];
                }
            }

            // 방문 페이지
            $pageData = $this->ga->runReport([
                'dateRanges'      => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions'      => [['name' => 'pagePath']],
                'metrics'         => [['name' => 'sessions'], ['name' => 'screenPageViews'], ['name' => 'bounceRate']],
                'orderBys'        => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                'limit'           => 10,
                'dimensionFilter' => $countryFilter,
            ]);
            if (!empty($pageData['rows'])) {
                foreach ($pageData['rows'] as $row) {
                    $bounce = (float)($row['metricValues'][2]['value'] ?? 0);
                    $result['topPages'][] = [
                        'path'     => $row['dimensionValues'][0]['value'] ?? '-',
                        'sessions' => (int)($row['metricValues'][0]['value'] ?? 0),
                        'views'    => (int)($row['metricValues'][1]['value'] ?? 0),
                        'bounce'   => round($bounce * 100, 2) . '%',
                    ];
                }
            }

            // 국가별 (지도 + 목록)
            $countryData = $this->ga->runReport([
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions' => [['name' => 'country']],
                'metrics'    => [['name' => 'newUsers']],
                'orderBys'   => [['metric' => ['metricName' => 'newUsers'], 'desc' => true]],
                'limit'      => 50,
            ]);
            if (!empty($countryData['rows'])) {
                foreach ($countryData['rows'] as $row) {
                    $result['countries'][] = [
                        'country' => $row['dimensionValues'][0]['value'] ?? '-',
                        'users'   => (int)($row['metricValues'][0]['value'] ?? 0),
                    ];
                }
            }

        } catch (\Exception $e) {
            \Log::error('GA Dashboard Error: ' . $e->getMessage());
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
