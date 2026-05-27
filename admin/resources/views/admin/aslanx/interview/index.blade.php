@extends('layouts.admin')
@section('title', 'AslanX 인터뷰 관리')

@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">AslanX 인터뷰 관리</h1>
        <div class="header-actions">
            <a href="{{ route('aslanx-interviews.create') }}" class="btn btn-primary">인터뷰 등록</a>
        </div>
    </div>
</div>

<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif

    <div class="card">
        <div class="card-header"><div class="card-title">인터뷰 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:60px">순서</th>
                        <th style="text-align:center;width:90px">로고</th>
                        <th>회사명</th>
                        <th>직책</th>
                        <th>인터뷰 내용 (요약)</th>
                        <th style="text-align:center;width:70px">노출</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $item)
                    <tr data-href="{{ route('aslanx-interviews.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;color:#aaa;">{{ $item->sort_order }}</td>
                        <td style="text-align:center;">
                            @if($item->logo_path)
                                <img src="/{{ $item->logo_path }}" alt="{{ $item->company }}"
                                     style="max-height:30px;max-width:80px;object-fit:contain;">
                            @else
                                <span style="color:#bbb;font-size:12px">없음</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->company }}</strong>
                            @if($item->company_en)
                                <div style="font-size:11px;color:#888">{{ $item->company_en }}</div>
                            @endif
                        </td>
                        <td>{{ $item->position ?? '—' }}</td>
                        <td style="font-size:.85rem;color:#aaa;">{{ Str::limit($item->content, 60) }}</td>
                        <td style="text-align:center;">
                            <span style="display:inline-block;padding:2px 10px;border-radius:12px;font-size:.75rem;font-weight:600;white-space:nowrap;
                                background:{{ $item->is_visible ? '#e8f5e9' : '#f5f5f5' }};
                                color:{{ $item->is_visible ? '#388e3c' : '#aaa' }};">
                                {{ $item->is_visible ? '노출' : '숨김' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>등록된 인터뷰가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
