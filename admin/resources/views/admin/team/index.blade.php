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
                        <th style="text-align:center;width:60px;">번호</th>
                        <th>이미지</th>
                        <th>제목</th>
                        <th>카테고리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teams as $team)
                    <tr data-href="{{ route('team.edit', $team->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $team->id }}</td>
                        <td>
                            @if($team->image_url)
                                <img src="{{ $team->image_url }}"
                                     alt="{{ $team->title }}"
                                     style="width:44px;height:44px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            @else
                                <span style="color:#cbd5e0;">-</span>
                            @endif
                        </td>
                        <td>{{ $team->title }}</td>
                        <td>{{ $team->category }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state"><p>등록된 팀 정보가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
