@extends('layouts.admin')
@section('title', '문의 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">문의 관리</h1></div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">문의 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>번호</th><th>회사명</th><th>담당자</th><th>이메일</th><th>문의 내용</th><th>접수일</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inquiries as $inquiry)
                    <tr data-href="{{ route('inquiry.show', $inquiry->id) }}" style="cursor:pointer;">
                        <td>{{ $inquiry->id }}</td>
                        <td>{{ $inquiry->company }}</td>
                        <td>{{ $inquiry->name }}</td>
                        <td>{{ $inquiry->email }}</td>
                        <td style="max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $inquiry->message }}</td>
                        <td>{{ $inquiry->created_at?->format('Y.m.d H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>접수된 문의가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
