@extends('layouts.admin')
@section('title', '테크블로그 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">테크블로그 관리</h1>
        <div class="header-actions">
            <a href="{{ route('tech-blogs.create') }}" class="btn btn-primary">테크블로그 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    @if(session('error'))
        <div style="margin:0 0 16px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">{{ session('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header"><div class="card-title">테크블로그 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">번호</th>
                        <th style="width:80px;">썸네일</th>
                        <th>제목</th>
                        <th>저자</th>
                        <th>카테고리</th>
                        <th style="text-align:center;">공개</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($techBlogs as $item)
                    <tr data-href="{{ route('tech-blogs.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td>
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="" style="width:60px; height:34px; object-fit:cover; border-radius:4px; display:block;">
                            @else
                                <div style="width:60px; height:34px; background:#f0f0f0; border-radius:4px; display:flex; align-items:center; justify-content:center;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="width:16px;height:16px;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                            @endif
                        </td>
                        <td style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->title }}</td>
                        <td style="color:#888;">{{ $item->author }}</td>
                        <td>{{ $item->category }}</td>
                        <td style="text-align:center;">
                            <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-gray' }}">
                                {{ $item->is_active ? '공개' : '비공개' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>등록된 테크블로그가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
