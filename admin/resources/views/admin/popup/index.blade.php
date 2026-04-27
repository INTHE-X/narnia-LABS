@extends('layouts.admin')
@section('title', '팝업 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">팝업 관리</h1>
        <div class="header-actions">
            <a href="{{ route('popup.create') }}" class="btn btn-primary">
                팝업 등록
            </a>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">팝업 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">번호</th>
                        <th>미리보기</th>
                        <th>제목</th>
                        <th>노출 기간</th>
                        <th>상태</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($popups as $popup)
                    <tr data-href="{{ route('popup.edit', $popup->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $popups->count() - $loop->index }}</td>
                        <td>
                            @if($popup->image_path)
                                <img src="/admin/{{ $popup->image_path }}" style="width:60px;height:40px;object-fit:cover;border-radius:3px;border:1px solid #eee;">
                            @else
                                <span style="color:#bbb;font-size:12px;">없음</span>
                            @endif
                        </td>
                        <td>{{ $popup->title }}</td>
                        <td>
                            {{ $popup->start_date?->format('Y.m.d') ?? '-' }}
                            ~
                            {{ $popup->end_date?->format('Y.m.d') ?? '-' }}
                        </td>
                        <td>
                            @if($popup->status === 'active')
                                <span class="badge badge-success">활성</span>
                            @else
                                <span class="badge badge-gray">비활성</span>
                            @endif
                        </td>
                        <td>{{ $popup->created_at?->format('Y.m.d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>등록된 팝업이 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
