@extends('layouts.admin')
@section('title', '테크블로그 수정')

@push('head')
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
@endpush

@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">테크블로그 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">테크블로그 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('tech-blogs.update', $techBlog->id) }}" method="POST" enctype="multipart/form-data" id="tb-form">
            @csrf @method('PUT')
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">
                {{-- 왼쪽: 썸네일 --}}
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:300px; height:170px;">
                        @if($techBlog->image_url)
                            <img id="img-preview" src="{{ $techBlog->image_url }}" style="width:100%;height:100%;object-fit:cover;" alt="이미지">
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
                    <span class="img-form-hint">클릭하여 썸네일 변경<br>(비워두면 유지)<br><small style="color:#aaa;">권장 비율: 16:9 (가로형)</small></span>
                </div>
                {{-- 오른쪽: 입력 필드 --}}
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">제목 *</div>
                        <div class="field-value"><input type="text" name="title" value="{{ old('title', $techBlog->title) }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (EN)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en', $techBlog->title_en) }}" placeholder="Title in English"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (JP)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp', $techBlog->title_jp) }}" placeholder="Title in Japanese"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리</div>
                        <div class="field-value"><input type="text" name="category" value="{{ old('category', $techBlog->category) }}" placeholder="예: AI, 딥러닝, 클라우드 등"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">작성 날짜</div>
                        <div class="field-value"><input type="date" name="published_date" value="{{ old('published_date', $techBlog->published_date ?? date('Y-m-d')) }}"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $techBlog->sort_order) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">공개 여부</div>
                        <label class="img-toggle-wrap" for="is_active">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $techBlog->is_active) ? 'checked' : '' }}>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- 에디터 영역 (전체 너비) --}}
            <div style="padding:20px 24px 0;">
                <div style="font-size:13px;color:var(--gray-600);font-weight:500;margin-bottom:8px;">내용</div>
                <div id="editor" style="min-height:400px;"></div>
                <textarea name="description" id="description-hidden" style="display:none;">{{ old('description', $techBlog->description) }}</textarea>
            </div>

            <div class="img-form-actions" style="justify-content:space-between;">
                <div class="img-form-actions-left">
                    <button type="button" class="btn btn-primary" onclick="submitForm()">수정하기</button>
                    <a href="{{ route('tech-blogs.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('tb-delete-form').submit()">삭제</button>
            </div>
        </form>

        {{-- 삭제 form --}}
        <form id="tb-delete-form" method="POST" action="{{ route('tech-blogs.destroy', $techBlog->id) }}"
              onsubmit="return confirm('정말 삭제하시겠습니까?')">
            @csrf @method('DELETE')
        </form>
    </div>
</div>

<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<script>
var initialContent = document.getElementById('description-hidden').value || '';

var editor = new toastui.Editor({
    el: document.getElementById('editor'),
    height: '500px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical',
    placeholder: '블로그 내용을 입력하세요. 이미지는 툴바의 이미지 버튼 또는 드래그&드롭으로 추가하세요.',
    initialValue: initialContent,
    hooks: {
        addImageBlobHook: function(blob, callback) {
            var formData = new FormData();
            formData.append('image', blob);
            fetch('/admin/api/tech-blogs/upload-image', {
                method: 'POST',
                body: formData
            })
            .then(function(r){ return r.json(); })
            .then(function(data){ callback(data.url, '이미지'); })
            .catch(function(){ alert('이미지 업로드에 실패했습니다.'); });
        }
    },
    language: 'ko-KR'
});

function submitForm() {
    document.getElementById('description-hidden').value = editor.getHTML();
    document.getElementById('tb-form').submit();
}
</script>
@endsection
