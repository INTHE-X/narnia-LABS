@extends('layouts.admin')
@section('title', '이벤트 등록')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">이벤트 등록</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">이벤트 정보 입력</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">

                {{-- 왼쪽: 이미지 --}}
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:300px; height:170px;">
                        <div class="img-placeholder" id="img-ph">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            이미지 업로드
                        </div>
                        <img id="img-preview" style="display:none;width:100%;height:100%;object-fit:cover;" alt="">
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" style="display:none;"
                        onchange="var p=document.getElementById('img-preview'),ph=document.getElementById('img-ph');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';ph.style.display='none';};r.readAsDataURL(this.files[0]);}">
                    <span class="img-form-hint">클릭하여 이미지 선택<br>JPG, PNG (최대 5MB)<br><small style="color:#aaa;">권장 비율: 16:9 (가로형)</small></span>
                </div>

                {{-- 오른쪽: 스택형 폼 --}}
                <div class="stacked-form">
                    <div class="stacked-form-row">
                        <div class="stacked-field" style="grid-column:1/-1;">
                            <span class="stacked-label">제목 <span class="req">*</span></span>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="이벤트 제목을 입력하세요" required>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (EN)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Title in English"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (JP)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp') }}" placeholder="日本語タイトル"></div>
                    </div>
                    <div class="stacked-form-row">
                        <div class="stacked-field">
                            <span class="stacked-label">카테고리 <span class="req">*</span></span>
                            <select name="category" required>
                                @foreach(['Conference','Exhibition','Webinar','Workshop'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="stacked-field">
                            <span class="stacked-label">이벤트 기간 <span class="req">*</span></span>
                            <input type="text" id="date_range" placeholder="시작일 ~ 종료일 선택" autocomplete="off" style="cursor:pointer;">
                            <input type="hidden" id="start_date" name="start_date" value="{{ old('start_date') }}">
                            <input type="hidden" id="end_date"   name="end_date"   value="{{ old('end_date') }}">
                        </div>
                    </div>
                    <div class="stacked-form-row">
                        <div class="stacked-field" style="grid-column:1/-1;">
                            <span class="stacked-label">외부 링크 URL</span>
                            <input type="url" name="link" value="{{ old('link') }}" placeholder="https://...">
                        </div>
                    </div>
                    <div class="stacked-form-row">
                        <div class="stacked-field">
                            <span class="stacked-label">정렬 순서</span>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="stacked-form-row">
                        <div class="stacked-field" style="grid-column:1/-1;">
                            <span class="stacked-label">슬라이드 노출</span>
                            <div class="stacked-toggle" style="text-align:left;">
                                <label class="img-toggle-wrap" for="is_featured">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <span class="img-toggle-btn"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">등록하기</button>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
flatpickr('#date_range', {
    locale: 'ko', mode: 'range', dateFormat: 'Y-m-d',
    defaultDate: [
        document.getElementById('start_date').value || null,
        document.getElementById('end_date').value || null
    ].filter(Boolean),
    onChange: function(dates) {
        document.getElementById('start_date').value = dates[0] ? flatpickr.formatDate(dates[0],'Y-m-d') : '';
        document.getElementById('end_date').value   = dates[1] ? flatpickr.formatDate(dates[1],'Y-m-d') : '';
    }
});
</script>
@endsection
