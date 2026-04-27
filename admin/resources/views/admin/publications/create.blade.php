@extends('layouts.admin')
@section('title', '퍼블리케이션 등록')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">퍼블리케이션 등록</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">퍼블리케이션 정보 입력</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('publications.store') }}" method="POST">
            @csrf
            <div class="field-rows">
                <div class="field-row">
                    <div class="field-label">제목 *</div>
                    <div class="field-value"><input type="text" name="title" value="{{ old('title') }}" placeholder="퍼블리케이션 제목" required></div>
                </div>
                    <div class="field-row">
                        <div class="field-label">Title (English)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Title in English"></div>
                    </div>
                <div class="field-row">
                    <div class="field-label">카테고리 *</div>
                    <div class="field-value">
                        <select name="category" required>
                            @foreach(['International Journal Papers','Korean Journal Papers'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">저자</div>
                    <div class="field-value"><input type="text" name="author" value="{{ old('author') }}" placeholder="저자명"></div>
                </div>
                <div class="field-row">
                    <div class="field-label">외부 링크 URL</div>
                    <div class="field-value"><input type="url" name="link" value="{{ old('link') }}" placeholder="https://..."></div>
                </div>
                <div class="field-row">
                    <div class="field-label">정렬 순서</div>
                    <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:120px;"></div>
                </div>
                <div class="field-row align-top">
                    <div class="field-label">초록 / 설명</div>
                    <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                        <textarea name="description" rows="6" placeholder="초록 또는 설명">{{ old('description') }}</textarea>
                    </div>
                </div>
                    <div class="field-row align-top">
                        <div class="field-label">Description (English)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="description_en" rows="4" placeholder="Description in English">{{ old('description_en') }}</textarea>
                        </div>
                    </div>
                <div class="field-row">
                    <div class="field-label">출처</div>
                    <div class="field-value"><input type="text" name="source" value="{{ old('source') }}" placeholder="출처 (예: Journal of Mechanical Design)"></div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">등록하기</button>
                    <a href="{{ route('publications.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
