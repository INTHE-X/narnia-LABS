@extends('layouts.admin')
@section('title', '관리자 설정')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">관리자 설정</h1>
        <div class="header-actions">
            <a href="{{ route('members.create') }}" class="btn btn-primary">관리자 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">관리자 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>번호</th><th>아이디</th><th>이름</th><th>휴대전화</th><th>이메일</th><th>권한</th><th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                    <tr data-href="{{ route('members.edit', $member->id) }}" style="cursor:pointer;">
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->username ?? $member->name }}</td>
                        <td>{{ $member->name ?? '-' }}</td>
                        <td>{{ $member->phone ?? '-' }}</td>
                        <td>{{ $member->email ?? '-' }}</td>
                        <td>
                            @if($member->role === 'master')
                                <span class="badge badge-warning">마스터</span>
                            @else
                                <span class="badge badge-info">일반 관리자</span>
                            @endif
                        </td>
                        <td>{{ $member->created_at?->format('Y.m.d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state"><p>등록된 관리자가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
