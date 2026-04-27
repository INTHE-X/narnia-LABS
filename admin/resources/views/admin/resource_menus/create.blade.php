@extends('layouts.admin')
@section('title', '메뉴 추가')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">Resource 메뉴 추가</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">메뉴 정보 입력</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('resource-menus.store') }}" method="POST">
            @csrf
            <div class="field-rows" style="padding:24px;">
                <div class="field-row">
                    <div class="field-label">메뉴명 *</div>
                    <div class="field-value">
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="예: Tech blog" required style="max-width:360px;">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">링크 URL *</div>
                    <div class="field-value">
                        <input type="text" name="url" value="{{ old('url') }}"
                               placeholder="예: https://blog.narnia.ai" required>
                        <p style="font-size:12px;color:#999;margin-top:4px;">http:// 또는 https:// 포함 전체 URL을 입력하세요.</p>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">링크 열기 방식</div>
                    <div class="field-value" style="display:flex;align-items:center;gap:20px;padding:12px 0;">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:14px;">
                            <input type="radio" name="is_external" value="1" {{ old('is_external', '1') == '1' ? 'checked' : '' }}>
                            새 탭에서 열기 (외부 링크)
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:14px;">
                            <input type="radio" name="is_external" value="0" {{ old('is_external') == '0' ? 'checked' : '' }}>
                            현재 탭에서 열기
                        </label>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">노출 여부</div>
                    <div class="field-value" style="display:flex;align-items:center;gap:20px;padding:12px 0;">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:14px;">
                            <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            노출
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:14px;">
                            <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                            숨김
                        </label>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">정렬 순서</div>
                    <div class="field-value">
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:120px;">
                        <p style="font-size:12px;color:#999;margin-top:4px;">숫자가 작을수록 먼저 표시됩니다.</p>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">추가하기</button>
                    <a href="{{ route('resource-menus.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
