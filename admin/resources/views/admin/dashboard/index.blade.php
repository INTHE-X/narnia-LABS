@extends('layouts.admin')
@section('title', '대시보드')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style>
.ga-card { background:#fff; border-radius:10px; border:1px solid #e8eaed; box-shadow:0 1px 4px rgba(0,0,0,.06); }
.ga-label { font-size:11px; font-weight:700; color:#80868b; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
.ga-table { width:100%; border-collapse:collapse; font-size:13px; }
.ga-table th { padding:8px 10px; text-align:left; color:#80868b; font-weight:600; border-bottom:1px solid #e8eaed; white-space:nowrap; }
.ga-table td { padding:7px 10px; border-bottom:1px solid #f1f3f4; color:#202124; }
.ga-table tr:hover td { background:#f8f9fa; }
.ga-table tr:last-child td { border-bottom:none; }
.ga-select {
    background:#fff; color:#3c4043; border:1px solid #dadce0;
    border-radius:6px; padding:8px 32px 8px 14px; font-size:13px; cursor:pointer;
    appearance:none; -webkit-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2380868b' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 10px center; min-width:140px;
}
.ga-select:focus { outline:none; border-color:#1a73e8; box-shadow:0 0 0 2px rgba(26,115,232,.15); }
.date-btn {
    background:#fff; color:#3c4043; border:1px solid #dadce0;
    border-radius:6px; padding:8px 14px; font-size:13px; cursor:pointer; white-space:nowrap;
    display:flex; align-items:center; gap:8px; min-width:200px; justify-content:space-between;
}
.date-btn:hover { border-color:#aaa; }
.badge-up { color:#137333; background:#e6f4ea; padding:2px 8px; border-radius:12px; font-size:12px; font-weight:600; }
.badge-down { color:#c5221f; background:#fce8e6; padding:2px 8px; border-radius:12px; font-size:12px; font-weight:600; }
</style>

<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">대시보드</h1>
        <div style="font-size:.85rem;color:#80868b;">최근 30일 Google Analytics 통계</div>
    </div>
</div>

<div class="content-body">

    {{-- ── 헤더: 총 사용자 + 필터 ── --}}
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
        {{-- 총 사용자 --}}
        <div class="ga-card" style="padding:20px 24px;min-width:160px;">
            <div class="ga-label">총 사용자</div>
            <div style="font-size:2.6rem;font-weight:800;color:#202124;line-height:1.1;">
                {{ number_format($gaData['totalUsers']) ?: '-' }}
            </div>
            @php $rate = $gaData['changeRate']; $isUp = $rate >= 0; @endphp
            @if($gaData['totalUsers'] > 0)
            <div style="margin-top:8px;">
                <span class="{{ $isUp ? 'badge-up' : 'badge-down' }}">
                    {{ $isUp ? '▲' : '▼' }} {{ abs($rate) }}%
                </span>
                <span style="font-size:11px;color:#80868b;margin-left:6px;">전 기간 대비</span>
            </div>
            @endif
        </div>

        <div style="flex:1;"></div>

        {{-- 필터 폼 --}}
        <form method="GET" action="{{ route('dashboard') }}" id="filter-form" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <select class="ga-select" name="country" onchange="document.getElementById('filter-form').submit()">
                <option value="">국가 (전체)</option>
                @foreach($gaData['countries'] as $c)
                    <option value="{{ $c['country'] }}" {{ $country == $c['country'] ? 'selected' : '' }}>
                        {{ $c['country'] }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="start" id="hStart" value="{{ $startDate }}">
            <input type="hidden" name="end"   id="hEnd"   value="{{ $endDate }}">
            <button type="button" class="date-btn" onclick="document.getElementById('fp-trigger').click()">
                <span id="date-display">{{ date('Y. n. j.', strtotime($startDate)) }} – {{ date('Y. n. j.', strtotime($endDate)) }}</span>
                <svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="#80868b" stroke-width="1.5"><rect x="1" y="2" width="14" height="13" rx="2"/><path d="M5 1v2M11 1v2M1 6h14"/></svg>
            </button>
            <input type="text" id="fp-trigger" style="width:0;height:0;opacity:0;position:absolute;">
        </form>
    </div>

    @if(!empty($gaData['error']))
    <div style="padding:12px 16px;background:#fce8e6;border:1px solid #f5c6c6;border-radius:8px;color:#c5221f;font-size:13px;margin-bottom:16px;">
        ⚠️ GA 연동 오류: {{ $gaData['error'] }}
    </div>
    @endif

    {{-- ── 차트 + 지도 ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div class="ga-card" style="padding:20px 24px;">
            <div class="ga-label" style="margin-bottom:12px;">
                <span style="display:inline-block;width:10px;height:2px;background:#1a73e8;margin-right:6px;vertical-align:middle;"></span>
                새 사용자 수
            </div>
            <canvas id="dailyChart" style="max-height:230px;"></canvas>
        </div>
        <div class="ga-card" style="padding:12px;">
            <div id="geo-chart" style="width:100%;height:270px;"></div>
            <div style="font-size:11px;color:#80868b;margin-top:6px;">새 사용자 수 &nbsp;●&nbsp; {{ number_format(collect($gaData['countries'])->sum('users')) }}</div>
        </div>
    </div>

    {{-- ── 3개 테이블 ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">

        <div class="ga-card" style="padding:16px 20px;">
            <div class="ga-label" style="margin-bottom:10px;">첫 사용자 유입경로</div>
            <table class="ga-table">
                <thead><tr><th></th><th>소스 / 매체</th><th style="text-align:right;">방문수 ▼</th><th style="text-align:right;">조회수</th></tr></thead>
                <tbody>
                @forelse($gaData['trafficSources'] as $i => $src)
                <tr>
                    <td style="color:#80868b;width:22px;">{{ $i+1 }}.</td>
                    <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $src['source'] }}">{{ $src['source'] }}</td>
                    <td style="text-align:right;font-weight:600;">{{ number_format($src['sessions']) }}</td>
                    <td style="text-align:right;color:#80868b;">{{ number_format($src['views']) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#80868b;padding:24px;">데이터 없음</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="ga-card" style="padding:16px 20px;">
            <div class="ga-label" style="margin-bottom:10px;">방문 페이지</div>
            <table class="ga-table">
                <thead><tr><th></th><th>페이지</th><th style="text-align:right;">방문수</th><th style="text-align:right;">조회수 ▼</th><th style="text-align:right;">이탈률</th></tr></thead>
                <tbody>
                @forelse($gaData['topPages'] as $i => $page)
                <tr>
                    <td style="color:#80868b;width:22px;">{{ $i+1 }}.</td>
                    <td style="max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $page['path'] }}">{{ $page['path'] }}</td>
                    <td style="text-align:right;font-weight:600;">{{ number_format($page['sessions']) }}</td>
                    <td style="text-align:right;">{{ number_format($page['views']) }}</td>
                    <td style="text-align:right;color:#80868b;">{{ $page['bounce'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#80868b;padding:24px;">데이터 없음</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="ga-card" style="padding:16px 20px;">
            <div class="ga-label" style="margin-bottom:10px;">국가별 새 사용자</div>
            <table class="ga-table">
                <thead><tr><th></th><th>국가</th><th style="text-align:right;">사용자 ▼</th></tr></thead>
                <tbody>
                @forelse($gaData['countries'] as $i => $c)
                <tr>
                    <td style="color:#80868b;width:22px;">{{ $i+1 }}.</td>
                    <td>{{ $c['country'] }}</td>
                    <td style="text-align:right;font-weight:600;">{{ number_format($c['users']) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:#80868b;padding:24px;">데이터 없음</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
(function() {
    const labels = @json($gaData['dailyLabels']);
    const values = @json($gaData['dailyValues']);
    if (!labels.length) return;
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: '새 사용자',
                data: values,
                borderColor: '#1a73e8',
                backgroundColor: 'rgba(26,115,232,.08)',
                borderWidth: 2,
                pointRadius: 2,
                pointBackgroundColor: '#1a73e8',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#f1f3f4' }, ticks: { color: '#80868b', font: { size: 10 }, maxTicksLimit: 8 } },
                y: { grid: { color: '#f1f3f4' }, ticks: { color: '#80868b', font: { size: 10 } } }
            }
        }
    });
})();

google.charts.load('current', { packages: ['geochart'] });
google.charts.setOnLoadCallback(function() {
    var rawData = @json($gaData['countries']);
    var data = new google.visualization.DataTable();
    data.addColumn('string', '국가');
    data.addColumn('number', '새 사용자');
    rawData.forEach(function(r) { data.addRow([r.country, r.users]); });
    var chart = new google.visualization.GeoChart(document.getElementById('geo-chart'));
    chart.draw(data, {
        colorAxis: { colors: ['#c6dafc', '#1a73e8'] },
        backgroundColor: '#fff',
        datalessRegionColor: '#f1f3f4',
        legend: 'none',
        tooltip: { isHtml: true }
    });
});

flatpickr('#fp-trigger', {
    locale: 'ko', mode: 'range', dateFormat: 'Y-m-d',
    defaultDate: ['{{ $startDate }}', '{{ $endDate }}'],
    onChange: function(dates) {
        if (dates.length === 2) {
            document.getElementById('hStart').value = flatpickr.formatDate(dates[0], 'Y-m-d');
            document.getElementById('hEnd').value   = flatpickr.formatDate(dates[1], 'Y-m-d');
            document.getElementById('date-display').textContent =
                flatpickr.formatDate(dates[0], 'Y. n. j.') + ' – ' + flatpickr.formatDate(dates[1], 'Y. n. j.');
            document.getElementById('filter-form').submit();
        }
    }
});
</script>
@endsection
