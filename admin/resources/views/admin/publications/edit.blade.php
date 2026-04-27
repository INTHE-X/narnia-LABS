@extends('layouts.admin')
@section('title', '퍼블리케이션 수정')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">퍼블리케이션 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">퍼블리케이션 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('publications.update', $publication->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="field-rows">
                <div class="field-row">
                    <div class="field-label">제목 *</div>
                    <div class="field-value"><input type="text" name="title" value="{{ old('title', $publication->title) }}" required></div>
                </div>
                <div class="field-row">
                    <div class="field-label">제목 (EN)</div>
                    <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en', $publication->title_en ?? '') }}" placeholder="Title in English"></div>
                </div>
                <div class="field-row">
                    <div class="field-label">제목 (JP)</div>
                    <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp', $publication->title_jp ?? '') }}" placeholder="Title in Japanese"></div>
                </div>
                <div class="field-row">
                    <div class="field-label">카테고리 *</div>
                    <div class="field-value">
                        <select name="category" required>
                            @foreach(['International Journal Papers','Korean Journal Papers'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $publication->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">저자</div>
                    <div class="field-value"><input type="text" name="author" value="{{ old('author', $publication->author) }}"></div>
                </div>
                <div class="field-row">
                    <div class="field-label">외부 링크 URL</div>
                    <div class="field-value"><input type="text" name="link" value="{{ old('link', $publication->link) }}" placeholder="https://..."></div>
                </div>
                <div class="field-row">
                    <div class="field-label">정렬 순서</div>
                    <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $publication->sort_order) }}" min="0" style="max-width:120px;"></div>
                </div>
                <div class="field-row">
                    <div class="field-label">출처</div>
                    <div class="field-value"><input type="text" name="source" value="{{ old('source', $publication->source) }}" placeholder="출처 (예: Journal of Mechanical Design)"></div>
                </div>
                <div class="field-row align-top">
                    <div class="field-label">설명</div>
                    <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                        <textarea name="description" rows="6">{{ old('description', $publication->description) }}</textarea>
                    </div>
                </div>
                <div class="field-row align-top">
                    <div class="field-label">설명 (EN)</div>
                    <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                        <textarea name="description_en" rows="6" placeholder="Description in English">{{ old('description_en', $publication->description_en ?? '') }}</textarea>
                    </div>
                </div>
                <div class="field-row align-top">
                    <div class="field-label">설명 (JP)</div>
                    <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                        <textarea name="description_jp" rows="6" placeholder="Description in Japanese">{{ old('description_jp', $publication->description_jp ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('publications.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger"
                    data-confirm-delete
                    data-url="{{ route('publications.destroy', $publication->id) }}">삭제</button>
            </div>
        </form>
    </div>
</div>
@endsection
