@extends('layouts.admin')
@section('title', '대시보드')
@section('content')

<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">대시보드</h1>
        <div style="font-size:.85rem;color:#80868b;">Google Looker Studio 통계</div>
    </div>
</div>

<div class="content-body">
    <div style="background:#fff; border-radius:10px; border:1px solid #e8eaed; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">
        <iframe
            src="https://lookerstudio.google.com/embed/reporting/e5aa67bb-57e7-4e53-a7c7-74349ba3390c/page/NJegD"
            style="width:100%; height:calc(100vh - 160px); min-height:700px; border:none; display:block;"
            allowfullscreen
            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
        </iframe>
    </div>
</div>

@endsection
