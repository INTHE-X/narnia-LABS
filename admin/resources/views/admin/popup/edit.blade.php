@extends('layouts.admin')
@section('title', '팝업 수정')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">팝업 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">팝업 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('popup.update', $popup->id ?? 1) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">
                {{-- 왼쪽: 이미지 (세로형) --}}
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:200px; height:300px;">
                        @if($popup->image_path)
                            <img id="img-preview" src="/admin/{{ $popup->image_path }}" alt="이미지" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div class="img-placeholder" id="img-ph">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                이미지 업로드
                            </div>
                            <img id="img-preview" style="display:none;width:100%;height:100%;object-fit:cover;" alt="">
                        @endif
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" style="display:none;"
                        onchange="var p=document.getElementById('img-preview'),ph=document.getElementById('img-ph');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';if(ph)ph.style.display='none';};r.readAsDataURL(this.files[0]);}">
                    <span class="img-form-hint">클릭하여 이미지 변경<br>(비워두면 유지)<br><small style="color:#aaa;">권장 비율: 세로형</small></span>
                </div>

                {{-- 오른쪽: field-rows 폼 --}}
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">팝업 제목 *</div>
                        <div class="field-value"><input type="text" name="title" value="{{ old('title', $popup->title ?? '') }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">노출 상태</div>
                        <div class="field-value">
                            <select name="status">
                                <option value="active" {{ old('status', $popup->status ?? 'active') == 'active' ? 'selected' : '' }}>활성</option>
                                <option value="inactive" {{ old('status', $popup->status ?? '') == 'inactive' ? 'selected' : '' }}>비활성</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">노출 기간</div>
                        <div class="field-value">
                            <input type="text" id="date_range" placeholder="시작일 ~ 종료일 선택" autocomplete="off" style="cursor:pointer;">
                            <input type="hidden" id="start_date" name="start_date" value="{{ old('start_date', $popup->start_date?->format('Y-m-d') ?? '') }}">
                            <input type="hidden" id="end_date"   name="end_date"   value="{{ old('end_date',   $popup->end_date?->format('Y-m-d') ?? '') }}">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">클릭 링크 URL</div>
                        <div class="field-value"><input type="url" name="link_url" value="{{ old('link_url', $popup->link_url ?? '') }}" placeholder="https://example.com"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">팝업 위치</div>
                        <div class="field-value">
                            <div style="display:flex;gap:8px;">
                                <label style="cursor:pointer;">
                                    <input type="radio" name="position" value="center" {{ old('position', $popup->position ?? 'center')=='center' ? 'checked' : '' }} style="display:none;" class="pos-radio">
                                    <span class="pos-btn {{ old('position', $popup->position ?? 'center')=='center' ? 'pos-btn-active' : '' }}">가운데</span>
                                </label>
                                <label style="cursor:pointer;">
                                    <input type="radio" name="position" value="bottom-right" {{ old('position', $popup->position)=='bottom-right' ? 'checked' : '' }} style="display:none;" class="pos-radio">
                                    <span class="pos-btn {{ old('position', $popup->position)=='bottom-right' ? 'pos-btn-active' : '' }}">우측 하단</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('popup.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-form').submit()">삭제</button>
            </div>
        </form>

        {{-- 삭제 폼은 메인 폼 바깥에 별도로 위치 --}}
        <form id="delete-form" method="POST" action="{{ route('popup.destroy', $popup->id) }}"
              onsubmit="return confirm('이 팝업을 삭제하시겠습니까?')" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
<style>
.pos-btn {
    display: inline-block;
    padding: 8px 20px;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    background: #fff;
    transition: all 0.15s;
    user-select: none;
}
.pos-btn-active {
    background: #111;
    color: #fff;
    border-color: #111;
}
</style>
<script>
(function() {
    var startVal = document.getElementById('start_date').value;
    var endVal   = document.getElementById('end_date').value;
    var defaults = [];
    if (startVal) defaults.push(startVal);
    if (endVal)   defaults.push(endVal);

    flatpickr('#date_range', {
        locale: 'ko',
        mode: 'range',
        dateFormat: 'Y-m-d',
        defaultDate: defaults.length ? defaults : null,
        onChange: function(selectedDates) {
            document.getElementById('start_date').value = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : '';
            document.getElementById('end_date').value   = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : '';
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (defaults.length === 2) {
                instance.element.value = defaults[0] + ' ~ ' + defaults[1];
            } else if (defaults.length === 1) {
                instance.element.value = defaults[0];
            }
        }
    });
})();
document.querySelectorAll('.pos-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.pos-btn').forEach(function(btn) { btn.classList.remove('pos-btn-active'); });
        this.nextElementSibling.classList.add('pos-btn-active');
    });
});
</script>
@endsection
