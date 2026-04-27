@extends('layouts.admin')
@section('title', '케이스스터디 수정')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">케이스스터디 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">케이스스터디 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('case-studies.update', $caseStudy->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:300px; height:170px;">
                        @if($caseStudy->image_path)
                            <img id="img-preview" src="{{ $caseStudy->image_url }}" alt="이미지" style="width:100%;height:100%;object-fit:cover;">
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
                    <span class="img-form-hint">클릭하여 이미지 변경<br>(비워두면 유지)<br><small style="color:#aaa;">권장 비율: 16:9 (가로형)</small></span>
                </div>
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">제목 *</div>
                        <div class="field-value"><input type="text" name="title" value="{{ old('title', $caseStudy->title) }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (EN)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en', $caseStudy->title_en ?? '') }}" placeholder="Title in English"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (JP)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp', $caseStudy->title_jp ?? '') }}" placeholder="Title in Japanese"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리 *</div>
                        <div class="field-value">
                            <select name="category" required>
                                @foreach(['Automobile','Electronics','Others'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $caseStudy->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">태그</div>
                        <div class="field-value">
                            <input type="text" name="tags" value="{{ old('tags', $caseStudy->tags) }}" placeholder="#Generator,#Evaluator">
                            <small style="color:#888;display:block;margin-top:.3rem;">#으로 시작하는 태그를 쉼표로 구분하여 입력하세요.</small>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">외부 링크 URL</div>
                        <div class="field-value"><input type="url" name="link" value="{{ old('link', $caseStudy->link) }}" placeholder="https://..."></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $caseStudy->sort_order) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">슬라이드 노출</div>
                        <label class="img-toggle-wrap" for="is_featured">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $caseStudy->is_featured) ? 'checked' : '' }}>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                    <div class="field-row">
                        <div class="field-label">게시 공개</div>
                        <label class="img-toggle-wrap" for="is_published">
                            <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $caseStudy->is_published ?? 1) ? 'checked' : '' }}>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">설명</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description" rows="4">{{ old('description', $caseStudy->description) }}</textarea>
                        </div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">설명 (EN)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description_en" rows="4" placeholder="Description in English">{{ old('description_en', $caseStudy->description_en ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">설명 (JP)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description_jp" rows="4" placeholder="Description in Japanese">{{ old('description_jp', $caseStudy->description_jp ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img-form-actions" style="justify-content:space-between;">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('case-studies.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('cs-delete-form').submit()">삭제</button>
            </div>
        </form>

        {{-- 삭제 form은 수정 form 바깥에 위치 --}}
        <form id="cs-delete-form" method="POST" action="{{ route('case-studies.destroy', $caseStudy->id) }}"
              onsubmit="return confirm('정말 삭제하시겠습니까?')">
            @csrf @method('DELETE')
        </form>
    </div>
</div>
@endsection
