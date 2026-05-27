@extends('layouts.admin')
@section('title', '케이스스터디 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">케이스스터디 관리</h1>
        <div class="header-actions">
            <a href="{{ route('case-studies.create') }}" class="btn btn-primary">케이스스터디 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">케이스스터디 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">번호</th>
                        <th style="text-align:center;">이미지</th>
                        <th>제목</th>
                        <th>카테고리</th>
                        <th>태그</th>
                        <th style="text-align:center;">메인 노출</th>
                        <th style="text-align:center;">게시 공개</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($caseStudies as $item)
                    <tr data-href="{{ route('case-studies.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td style="text-align:center;">
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="" style="width:80px;height:50px;object-fit:cover;border-radius:3px;border:1px solid #eee;">
                            @else
                                <span style="color:#bbb;font-size:12px;">없음</span>
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category }}</td>
                        <td style="font-size:.8rem;color:#aaa;">{{ $item->tags }}</td>
                        <td style="text-align:center;">
                            <span style="display:inline-block;padding:2px 10px;border-radius:12px;font-size:.75rem;font-weight:600;
                                background:{{ $item->is_featured ? '#e8f5e9' : '#f5f5f5' }};
                                color:{{ $item->is_featured ? '#388e3c' : '#aaa' }};">
                                {{ $item->is_featured ? '노출' : '-' }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <span style="display:inline-block;padding:2px 10px;border-radius:12px;font-size:.75rem;font-weight:600;
                                background:{{ $item->is_published ? '#e3f2fd' : '#f5f5f5' }};
                                color:{{ $item->is_published ? '#1565c0' : '#aaa' }};">
                                {{ $item->is_published ? '공개' : '비공개' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state"><p>등록된 케이스스터디가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
