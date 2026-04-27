@extends('layouts.admin')
@section('title', '클라이언트 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">클라이언트 관리</h1>
        <div class="header-actions">
            <a href="{{ route('clients.create') }}" class="btn btn-primary">로고 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">클라이언트 로고 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">번호</th>
                        <th style="text-align:center;">로고</th>
                        <th>회사명</th>
                        <th>링크</th>
                        <th style="text-align:center;">정렬</th>
                        <th style="text-align:center;">공개</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $item)
                    <tr data-href="{{ route('clients.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td style="text-align:center;">
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="height:36px;max-width:100px;object-fit:contain;border:1px solid #eee;border-radius:3px;padding:4px;background:#fff;">
                            @else
                                <span style="color:#bbb;font-size:12px;">없음</span>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td style="font-size:.8rem;color:#aaa;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->link ?? '-' }}</td>
                        <td style="text-align:center;">{{ $item->sort_order }}</td>
                        <td style="text-align:center;">
                            <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-gray' }}">
                                {{ $item->is_active ? '공개' : '비공개' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>등록된 클라이언트 로고가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
