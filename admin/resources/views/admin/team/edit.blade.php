@extends('layouts.admin')
@section('title', '팀 수정')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">팀 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">팀 정보 수정</div></div>
        <form action="{{ route('team.update', $team->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="img-form-layout">
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()">
                        @if($team->image_path)
                            <img id="img-preview" src="/admin/{{ $team->image_path }}" alt="이미지">
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
                    <span class="img-form-hint">클릭하여 이미지 변경<br>(비워두면 유지)</span>
                </div>
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">제목 *</div>
                        <div class="field-value"><input type="text" name="title" value="{{ old('title', $team->title) }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리</div>
                        <div class="field-value"><input type="text" name="category" value="{{ old('category', $team->category) }}" placeholder="예: AI"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">노출 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $team->sort_order) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">내용 *</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description" rows="6" required>{{ old('description', $team->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('team.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger"
                    data-confirm-delete
                    data-url="{{ route('team.destroy', $team->id) }}">삭제</button>
            </div>
        </form>
    </div>
</div>
@endsection
