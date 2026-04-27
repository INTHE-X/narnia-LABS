@extends('layouts.admin')
@section('title', '케이스스터디 등록')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">케이스스터디 등록</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">케이스스터디 정보 입력</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('case-studies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">
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
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">제목 *</div>
                        <div class="field-value"><input type="text" name="title" value="{{ old('title') }}" placeholder="케이스스터디 제목" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (EN)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Title in English"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (JP)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp') }}" placeholder="Title in Japanese"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리 *</div>
                        <div class="field-value">
                            <select name="category" required>
                                @foreach(['Automobile','Electronics','Others'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">태그</div>
                        <div class="field-value">
                            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="#Generator,#Evaluator">
                            <small style="color:#888;display:block;margin-top:.3rem;">#으로 시작하는 태그를 쉼표로 구분하여 입력하세요.</small>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">외부 링크 URL</div>
                        <div class="field-value"><input type="url" name="link" value="{{ old('link') }}" placeholder="https://..."></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">슬라이드 노출</div>
                        <label class="img-toggle-wrap" for="is_featured">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                    <div class="field-row">
                        <div class="field-label">게시 공개</div>
                        <label class="img-toggle-wrap" for="is_published">
                            <input type="checkbox" id="is_published" name="is_published" value="1" checked>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">설명</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description" rows="4" placeholder="상세 설명">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">설명 (EN)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description_en" rows="4" placeholder="Description in English">{{ old('description_en') }}</textarea>
                        </div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">설명 (JP)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description_jp" rows="4" placeholder="Description in Japanese">{{ old('description_jp') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">등록하기</button>
                    <a href="{{ route('case-studies.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
