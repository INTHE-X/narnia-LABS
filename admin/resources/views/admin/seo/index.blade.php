@extends('layouts.admin')
@section('title', 'SEO 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">SEO 관리</h1>
        <div class="header-actions">
            <a href="https://narnialabs.mycafe24.com/admin/sitemap.xml" target="_blank" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                sitemap.xml 보기
            </a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))
        <div data-flash-success="{{ session('success') }}"></div>
    @endif

    {{-- 전체 SEO 점수 요약 --}}
    @php
        $totalScore = $settings->avg('seo_score') ?? 0;
        $goodCount  = $settings->filter(fn($s) => $s->score_label === 'good')->count();
        $warnCount  = $settings->filter(fn($s) => $s->score_label === 'warning')->count();
        $poorCount  = $settings->filter(fn($s) => $s->score_label === 'poor')->count();
    @endphp
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
        <div class="card" style="padding:1.2rem;text-align:center;">
            <div style="font-size:2rem;font-weight:700;color:#7eb3f5;">{{ round($totalScore) }}</div>
            <div style="font-size:.8rem;color:#888;margin-top:.3rem;">평균 SEO 점수</div>
        </div>
        <div class="card" style="padding:1.2rem;text-align:center;">
            <div style="font-size:2rem;font-weight:700;color:#4ade80;">{{ $goodCount }}</div>
            <div style="font-size:.8rem;color:#888;margin-top:.3rem;">양호 (80점 이상)</div>
        </div>
        <div class="card" style="padding:1.2rem;text-align:center;">
            <div style="font-size:2rem;font-weight:700;color:#fbbf24;">{{ $warnCount }}</div>
            <div style="font-size:.8rem;color:#888;margin-top:.3rem;">개선 필요 (50~79점)</div>
        </div>
        <div class="card" style="padding:1.2rem;text-align:center;">
            <div style="font-size:2rem;font-weight:700;color:#f87171;">{{ $poorCount }}</div>
            <div style="font-size:.8rem;color:#888;margin-top:.3rem;">미설정 (50점 미만)</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">페이지별 SEO 설정</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>페이지</th>
                        <th>URL</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>OG Image</th>
                        <th>점수</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                    @php $s = $settings->get($page['key']); @endphp
                    <tr>
                        <td><strong>{{ $page['name'] }}</strong></td>
                        <td style="font-size:.8rem;color:#888;">{{ $page['url'] }}</td>
                        <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:.85rem;">
                            {{ $s?->meta_title ?: '—' }}
                        </td>
                        <td style="max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:.85rem;color:#999;">
                            {{ $s?->meta_description ?: '—' }}
                        </td>
                        <td>
                            @if($s?->og_image)
                                <span style="color:#4ade80;font-size:.8rem;">✅ 있음</span>
                            @else
                                <span style="color:#f87171;font-size:.8rem;">없음</span>
                            @endif
                        </td>
                        <td>
                            @if($s)
                                @php
                                    $score = $s->seo_score;
                                    $color = $s->score_label === 'good' ? '#4ade80' : ($s->score_label === 'warning' ? '#fbbf24' : '#f87171');
                                @endphp
                                <span style="font-weight:700;color:{{ $color }};">{{ $score }}</span>
                                <div style="width:60px;height:4px;background:#1e3a5f;border-radius:2px;margin-top:3px;display:inline-block;vertical-align:middle;margin-left:6px;">
                                    <div style="width:{{ $score }}%;height:100%;background:{{ $color }};border-radius:2px;"></div>
                                </div>
                            @else
                                <span style="color:#666;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($s)
                                <a href="{{ route('seo.edit', $page['key']) }}" class="btn btn-secondary" style="padding:.3rem .7rem;font-size:.8rem;">수정</a>
                            @else
                                <span style="color:#666;font-size:.8rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- SEO 가이드 --}}
    <div class="card" style="margin-top:1rem;">
        <div class="card-header"><div class="card-title">SEO 작성 가이드</div></div>
        <div class="card-body" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;font-size:.85rem;line-height:1.7;">
            <div>
                <strong style="color:#7eb3f5;">📝 Meta Title</strong>
                <ul style="margin:.5rem 0 0;padding-left:1.2rem;color:#aaa;">
                    <li>권장: 10~60자</li>
                    <li>브랜드명 포함 권장</li>
                    <li>핵심 키워드 앞에 배치</li>
                </ul>
            </div>
            <div>
                <strong style="color:#7eb3f5;">📋 Meta Description</strong>
                <ul style="margin:.5rem 0 0;padding-left:1.2rem;color:#aaa;">
                    <li>권장: 50~160자</li>
                    <li>페이지 내용 요약</li>
                    <li>행동 유도 문구 포함</li>
                </ul>
            </div>
            <div>
                <strong style="color:#7eb3f5;">🖼️ OG Image</strong>
                <ul style="margin:.5rem 0 0;padding-left:1.2rem;color:#aaa;">
                    <li>권장: 1200×630px</li>
                    <li>SNS 공유 시 미리보기 이미지</li>
                    <li>절대 URL 또는 /admin/uploads/ 경로</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
