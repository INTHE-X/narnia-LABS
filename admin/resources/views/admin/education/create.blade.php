@extends('layouts.admin')
@section('title', '교육 등록')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">교육 등록</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">교육 정보 입력</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('education.store') }}" method="POST" enctype="multipart/form-data">
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
                    <span class="img-form-hint">클릭하여 이미지 선택<br>JPG, PNG (최대 5MB)</span>
                </div>
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">제목 *</div>
                        <div class="field-value"><input type="text" name="title" value="{{ old('title') }}" placeholder="교육 제목 (국문)" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (EN)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Education Title (English)"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (JP)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp') }}" placeholder="教育タイトル (日本語)"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리 *</div>
                        <div class="field-value">
                            <select name="category" required>
                                @foreach(['Education','Product'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">게시 날짜</div>
                        <div class="field-value"><input type="date" name="published_at" value="{{ old('published_at') }}"></div>
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
                        <div class="field-label">노출 여부</div>
                        <div class="field-value">
                            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;">
                                <span class="toggle-switch {{ old('is_visible', '1') ? 'on' : '' }}">
                                    <input type="checkbox" name="is_visible" value="1"
                                        {{ old('is_visible', '1') ? 'checked' : '' }}
                                        style="position:absolute;opacity:0;width:0;height:0;"
                                        onchange="var sw=this.closest('.toggle-switch');sw.classList.toggle('on',this.checked);this.closest('label').querySelector('.vis-lbl').textContent=this.checked?'노출':'비노출';">
                                    <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                </span>
                                <span class="vis-lbl" style="font-size:13px;font-weight:500;color:#333;">{{ old('is_visible', '1') ? '노출' : '비노출' }}</span>
                            </label>
                            <p style="margin:4px 0 0;font-size:11px;color:#999;">비노출 시 사이트에서 표시되지 않습니다.</p>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">AslanX 노출</div>
                        <div class="field-value">
                            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;">
                                <span class="toggle-switch {{ old('is_aslanx') ? 'on' : '' }}">
                                    <input type="checkbox" name="is_aslanx" value="1"
                                        {{ old('is_aslanx') ? 'checked' : '' }}
                                        style="position:absolute;opacity:0;width:0;height:0;"
                                        onchange="var sw=this.closest('.toggle-switch');sw.classList.toggle('on',this.checked);this.closest('label').querySelector('.asx-lbl').textContent=this.checked?'노출':'비노출';">
                                    <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                </span>
                                <span class="asx-lbl" style="font-size:13px;font-weight:500;color:#333;">{{ old('is_aslanx') ? '노출' : '비노출' }}</span>
                            </label>
                            <p style="margin:4px 0 0;font-size:11px;color:#999;">AslanX 페이지 영상 섹션에 표시됩니다.</p>
                        </div>
                    </div>


                    {{-- ── PDF / 자료 첨부 ── --}}
                    <div class="field-row align-top">
                        <div class="field-label" style="display:block;">자료 첨부<span style="display:block;font-size:11px;color:#999;font-weight:400;margin-top:2px;">PDF, PPT, DOC, HWP, ZIP</span></div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:8px;">
                            <span style="font-size:12px;color:#888;">파일당 최대 20MB까지 업로드 가능합니다.</span>
                            </div>
                            {{-- 파일 선택 버튼 --}}
                            <label for="pdfs" style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:7px 14px;border:1px dashed #aaa;border-radius:6px;font-size:13px;color:#555;background:#fafafa;transition:background .2s;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:15px;height:15px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                파일 선택 (복수 가능)
                            </label>
                            <input type="file" id="pdfs" name="pdfs[]" multiple
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.hwp,.zip"
                                   style="display:none;" onchange="addPdfFiles(this)">
                            {{-- 선택된 파일 목록 미리보기 --}}
                            <ul id="pdf-preview-list" style="margin:10px 0 0;padding:0;list-style:none;display:flex;flex-direction:column;gap:5px;"></ul>
                        </div>
                    </div>

                    {{-- ── Google / 외부 링크 첨부 ── --}}
                    <div class="field-row align-top">
                        <div class="field-label" style="display:block;">Google 링크<span style="display:block;font-size:11px;color:#999;font-weight:400;margin-top:2px;">Drive, Slides, Docs 등</span></div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <div id="google-links-container" style="display:flex;flex-direction:column;gap:8px;"></div>
                            <button type="button" onclick="addGoogleLink()"
                                style="margin-top:10px;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border:1px dashed #aaa;border-radius:6px;font-size:13px;color:#555;background:#fafafa;cursor:pointer;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                링크 추가
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">등록하기</button>
                    <a href="{{ route('education.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.toggle-switch { position:relative; display:inline-block; width:40px; height:22px; flex-shrink:0; }
.toggle-track  { position:absolute; inset:0; border-radius:11px; background:#ddd; transition:background .22s; cursor:pointer; }
.toggle-thumb  { position:absolute; top:3px; left:3px; width:16px; height:16px; border-radius:50%; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.18); transition:transform .22s; }
.toggle-switch.on .toggle-track  { background:#222; }
.toggle-switch.on .toggle-thumb  { transform:translateX(18px); }
</style>

<script>
/* 파일 누적 업로드 */
var pendingFiles = [];

function addPdfFiles(input) {
    Array.from(input.files).forEach(function(f) {
        // 동일 파일명+크기 중복 제외
        var dup = pendingFiles.some(function(p){ return p.name === f.name && p.size === f.size; });
        if (!dup) pendingFiles.push(f);
    });
    input.value = ''; // 입력값 리셋 → 같은 파일 다시 선택 가능
    renderPdfList();
}

function renderPdfList() {
    var list = document.getElementById('pdf-preview-list');
    list.innerHTML = '';
    pendingFiles.forEach(function(f, i) {
        var li = document.createElement('li');
        li.style.cssText = 'display:flex;align-items:center;gap:6px;font-size:12px;color:#444;background:#f5f5f5;padding:5px 10px;border-radius:4px;';
        var removeBtn = '<button type="button" onclick="removePdfFile(' + i + ')" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#aaa;font-size:16px;line-height:1;padding:0 2px;" title="삭제">×</button>';
        li.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="#e05" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'
                     + '<span>' + f.name + '</span>'
                     + '<span style="color:#999;white-space:nowrap;">' + (f.size/1024/1024).toFixed(1) + 'MB</span>'
                     + removeBtn;
        list.appendChild(li);
    });
}

function removePdfFile(i) {
    pendingFiles.splice(i, 1);
    renderPdfList();
}

// 폼 submit 시 DataTransfer로 누적 파일을 input에 주입
document.querySelector('form[action]').addEventListener('submit', function() {
    if (pendingFiles.length === 0) return;
    var dt = new DataTransfer();
    pendingFiles.forEach(function(f){ dt.items.add(f); });
    document.getElementById('pdfs').files = dt.files;
});

var glIdx = 0;
function addGoogleLink(name, url) {
    var container = document.getElementById('google-links-container');
    var idx = glIdx++;
    var row = document.createElement('div');
    row.id = 'gl-row-' + idx;
    row.style.cssText = 'display:flex;align-items:center;gap:8px;flex-wrap:wrap;';
    row.innerHTML =
        '<svg viewBox="0 0 24 24" fill="none" stroke="#ea4335" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;"><path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"/><polyline points="16 2 22 2 22 8"/><line x1="11" y1="13" x2="22" y2="2"/></svg>'
        + '<input type="text" name="google_links[' + idx + '][name]" value="' + (name||'') + '" placeholder="링크 이름 (예: Google Slides)" style="flex:1;min-width:140px;max-width:220px;padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">'
        + '<input type="url" name="google_links[' + idx + '][url]" value="' + (url||'') + '" placeholder="https://drive.google.com/..." required style="flex:2;min-width:240px;padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">'
        + '<button type="button" onclick="document.getElementById(\'gl-row-' + idx + '\').remove()" style="background:none;border:none;cursor:pointer;padding:4px;color:#aaa;font-size:18px;line-height:1;" title="삭제">×</button>';
    container.appendChild(row);
}
</script>
@endsection
