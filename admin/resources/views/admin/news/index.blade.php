@extends('layouts.admin')
@section('title', '뉴스 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">뉴스 관리</h1>
        <div class="header-actions">
            <a href="{{ route('news.create') }}" class="btn btn-primary">뉴스 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">뉴스 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">번호</th>
                        <th style="text-align:center;">이미지</th>
                        <th>제목</th>
                        <th>출처</th>
                        <th>날짜</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $item)
                    <tr data-href="{{ route('news.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td style="text-align:center;">
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="" style="width:80px;height:50px;object-fit:cover;border-radius:3px;border:1px solid #eee;">
                            @else
                                <span style="color:#bbb;font-size:12px;">없음</span>
                            @endif
                        </td>
                        <td style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->title }}</td>
                        <td style="color:#888;">{{ $item->source }}</td>
                        <td>{{ $item->published_at?->format('Y-m-d') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><p>등록된 뉴스가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
