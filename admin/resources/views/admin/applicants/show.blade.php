@extends('layouts.admin')
@section('title', '신청자 상세')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">신청자 상세</h1>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">신청자 정보</div></div>
        <div class="card-body">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <colgroup><col style="width:130px;"><col></colgroup>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">이름</td>
                    <td style="padding:14px 0;">{{ $applicant['name'] ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">이메일</td>
                    <td style="padding:14px 0;">{{ $applicant['email'] ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">휴대폰 번호</td>
                    <td style="padding:14px 0;">{{ $applicant['phone'] ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">회사 이름</td>
                    <td style="padding:14px 0;">{{ $applicant['company'] ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">부서</td>
                    <td style="padding:14px 0;">{{ $applicant['department'] ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:14px 0; color:#888; font-weight:500;">직급</td>
                    <td style="padding:14px 0;">{{ $applicant['position'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:14px 0; color:#888; font-weight:500;">제출일</td>
                    <td style="padding:14px 0;">{{ $applicant['applied_at'] ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="form-actions" style="margin-top:0; padding-top:0; border:none;">
        <a href="{{ route('applicants.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            목록으로
        </a>
    </div>
</div>
@endsection
