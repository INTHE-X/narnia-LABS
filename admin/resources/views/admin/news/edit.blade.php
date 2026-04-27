@extends('layouts.admin')
@section('title', '뉴스 수정')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">뉴스 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">뉴스 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('news.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:300px; height:170px;">
                        @if($newsItem->image_path)
                            <img id="img-preview" src="{{ $newsItem->image_url }}" alt="이미지" style="width:100%;height:100%;object-fit:cover;">
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
                        <div class="field-value"><input type="text" name="title" value="{{ old('title', $newsItem->title) }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">Title (English)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en', $newsItem->title_en ?? '') }}" placeholder="Title in English"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (일문)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp', $newsItem->title_jp ?? '') }}" placeholder="Title in Japanese"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리</div>
                        <div class="field-value">
                            <select name="category">
                                @foreach(['news','press','award'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $newsItem->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">출처 (언론사)</div>
                        <div class="field-value"><input type="text" name="source" value="{{ old('source', $newsItem->source) }}"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">게시 날짜</div>
                        <div class="field-value">
                            <input type="text" id="published_at_pick" placeholder="날짜 선택" autocomplete="off" style="cursor:pointer;">
                            <input type="hidden" id="published_at" name="published_at" value="{{ old('published_at', $newsItem->published_at?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">기사 링크 URL</div>
                        <div class="field-value"><input type="url" name="link" value="{{ old('link', $newsItem->link) }}" placeholder="https://..."></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $newsItem->sort_order) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">슬라이드 노출</div>
                        <label class="img-toggle-wrap" for="is_featured">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $newsItem->is_featured) ? 'checked' : '' }}>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">내용/요약</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="content" rows="4">{{ old('content', $newsItem->content) }}</textarea>
                        </div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">Content (English)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="content_en" rows="4" placeholder="Content in English">{{ old('content_en', $newsItem->content_en ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="field-row align-top">
                        <div class="field-label">내용 (일문)</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <textarea name="content_jp" rows="4" placeholder="Content in Japanese">{{ old('content_jp', $newsItem->content_jp ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger"
                    data-confirm-delete
                    data-url="{{ route('news.destroy', $newsItem->id) }}">삭제</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    var saved = document.getElementById('published_at').value;
    flatpickr('#published_at_pick', {
        locale: 'ko',
        dateFormat: 'Y-m-d',
        defaultDate: saved || null,
        onChange: function(dates) {
            document.getElementById('published_at').value = dates[0] ? flatpickr.formatDate(dates[0], 'Y-m-d') : '';
        },
        onReady: function(dates, str, instance) {
            if (saved) instance.element.value = saved;
        }
    });
})();
</script>
@endpush
