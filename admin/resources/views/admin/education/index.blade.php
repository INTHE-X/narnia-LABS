@extends('layouts.admin')
@section('title', '교육 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">교육 관리</h1>
        <div class="header-actions">
            <a href="{{ route('education.create') }}" class="btn btn-primary">교육 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">교육 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:60px;">번호</th><th>이미지</th><th>제목</th><th>카테고리</th><th>날짜</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($educations as $item)
                    <tr data-href="{{ route('education.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $item->id }}</td>
                        <td>
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
                            @else
                                <span style="color:#666;font-size:.8rem;">없음</span>
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->published_at?->format('Y-m-d') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><p>등록된 교육 콘텐츠가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
