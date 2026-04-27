@extends('layouts.admin')
@section('title', '팀 소개 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">팀 소개 관리</h1>
        <div class="header-actions">
            <a href="{{ route('team.create') }}" class="btn btn-primary">
                팀 등록
            </a>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">팀 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>제목</th>
                        <th>카테고리</th>
                        <th>순서</th>
                        <th>이미지</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teams as $team)
                    <tr>
                        <td>{{ $team->id }}</td>
                        <td>{{ $team->title }}</td>
                        <td>{{ $team->category }}</td>
                        <td>{{ $team->sort_order }}</td>
                        <td>{{ $team->image_path ? '있음' : '-' }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('team.edit', $team->id) }}" class="btn-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('team.destroy', $team->id) }}" method="POST" onsubmit="return confirm('삭제하시겠습니까?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-danger">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>등록된 팀 정보가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
