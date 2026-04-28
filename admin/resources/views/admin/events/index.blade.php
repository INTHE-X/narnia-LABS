@extends('layouts.admin')
@section('title', '이벤트 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">이벤트 관리</h1>
        <div class="header-actions">
            <a href="{{ route('events.create') }}" class="btn btn-primary">이벤트 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">이벤트 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">번호</th>
                        <th style="text-align:center;">미리보기</th>
                        <th>제목</th>
                        <th>시작일</th>
                        <th>종료일</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                    <tr data-href="{{ route('events.edit', $event->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $event->id }}</td>
                        <td style="text-align:center;">
                            @if($event->image_path)
                                <img src="{{ $event->image_url }}" style="width:80px;height:50px;object-fit:cover;border-radius:3px;border:1px solid #eee;">
                            @else
                                <span style="color:#bbb;font-size:12px;">없음</span>
                            @endif
                        </td>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->start_date }}</td>
                        <td>{{ $event->end_date }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><p>등록된 이벤트가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
