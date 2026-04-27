@extends('layouts.admin')
@section('title', '문의 상세')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">문의 상세</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header">
            <div class="card-title">문의 내용</div>
        </div>
        <div class="card-body">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <colgroup><col style="width:130px;"><col></colgroup>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">회사명</td>
                    <td style="padding:14px 0;">{{ $inquiry->company }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">담당자명</td>
                    <td style="padding:14px 0;">{{ $inquiry->name }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">직책</td>
                    <td style="padding:14px 0;">{{ $inquiry->position ?: '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">이메일</td>
                    <td style="padding:14px 0;">{{ $inquiry->email }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">연락처</td>
                    <td style="padding:14px 0;">{{ $inquiry->phone ?: '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">접수일</td>
                    <td style="padding:14px 0;">{{ $inquiry->created_at?->format('Y.m.d H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding:14px 0; color:#888; font-weight:500; vertical-align:top;">문의 내용</td>
                    <td style="padding:14px 0; line-height:1.8; white-space:pre-wrap;">{{ $inquiry->message }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="form-actions" style="margin-top:0; padding-top:0; border:none;">
        <a href="{{ route('inquiry.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            목록으로
        </a>
        <form method="POST" action="{{ route('inquiry.destroy', $inquiry->id) }}" onsubmit="return confirm('삭제하시겠습니까?')" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">삭제</button>
        </form>
    </div>
</div>
@endsection
