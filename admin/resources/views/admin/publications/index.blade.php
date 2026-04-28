@extends('layouts.admin')
@section('title', '퍼블리케이션 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">퍼블리케이션 관리</h1>
        <div class="header-actions">
            <a href="{{ route('publications.create') }}" class="btn btn-primary">퍼블리케이션 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">퍼블리케이션 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:60px;">번호</th><th>제목</th><th>저자</th><th>카테고리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($publications as $item)
                    <tr data-href="{{ route('publications.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $item->id }}</td>
                        <td style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->title }}</td>
                        <td style="color:#888;">{{ $item->author }}</td>
                        <td>{{ $item->category }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state"><p>등록된 퍼블리케이션이 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
