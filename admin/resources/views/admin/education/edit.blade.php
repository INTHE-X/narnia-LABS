@extends('layouts.admin')
@section('title', '교육 수정')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">교육 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">교육 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('education.update', $education->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="img-form-layout" style="grid-template-columns:360px 1fr;">
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:300px; height:170px;">
                        @if($education->image_path)
                            <img id="img-preview" src="{{ $education->image_url }}" alt="이미지" style="width:100%;height:100%;object-fit:cover;">
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
                        <div class="field-value"><input type="text" name="title" value="{{ old('title', $education->title) }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (EN)</div>
                        <div class="field-value"><input type="text" name="title_en" value="{{ old('title_en', $education->title_en) }}" placeholder="Education Title (English)"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">제목 (JP)</div>
                        <div class="field-value"><input type="text" name="title_jp" value="{{ old('title_jp', $education->title_jp) }}" placeholder="教育タイトル (日本語)"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">카테고리 *</div>
                        <div class="field-value">
                            <select name="category" required>
                                @foreach(['Education','Product'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $education->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">게시 날짜</div>
                        <div class="field-value"><input type="date" name="published_at" value="{{ old('published_at', $education->published_at?->format('Y-m-d')) }}"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">외부 링크 URL</div>
                        <div class="field-value"><input type="url" name="link" value="{{ old('link', $education->link) }}" placeholder="https://..."></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $education->sort_order) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">노출 여부</div>
                        <div class="field-value">
                            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;">
                                <span class="toggle-switch {{ old('is_visible', $education->is_visible) ? 'on' : '' }}">
                                    <input type="checkbox" name="is_visible" value="1"
                                        {{ old('is_visible', $education->is_visible) ? 'checked' : '' }}
                                        style="position:absolute;opacity:0;width:0;height:0;"
                                        onchange="var sw=this.closest('.toggle-switch');sw.classList.toggle('on',this.checked);this.closest('label').querySelector('.vis-label').textContent=this.checked?'노출':'비노출';">
                                    <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                </span>
                                <span class="vis-label" style="font-size:13px;font-weight:500;color:#333;">
                                    {{ old('is_visible', $education->is_visible) ? '노출' : '비노출' }}
                                </span>
                            </label>
                            <p style="margin:4px 0 0;font-size:11px;color:#999;">비노출 시 사이트에서 표시되지 않습니다.</p>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">AslanX 노출</div>
                        <div class="field-value">
                            <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;">
                                <span class="toggle-switch {{ old('is_aslanx', $education->is_aslanx) ? 'on' : '' }}">
                                    <input type="checkbox" name="is_aslanx" value="1"
                                        {{ old('is_aslanx', $education->is_aslanx) ? 'checked' : '' }}
                                        style="position:absolute;opacity:0;width:0;height:0;"
                                        onchange="var sw=this.closest('.toggle-switch');sw.classList.toggle('on',this.checked);this.closest('label').querySelector('.asx-label').textContent=this.checked?'노출':'비노출';">
                                    <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                </span>
                                <span class="asx-label" style="font-size:13px;font-weight:500;color:#333;">
                                    {{ old('is_aslanx', $education->is_aslanx) ? '노출' : '비노출' }}
                                </span>
                            </label>
                            <p style="margin:4px 0 0;font-size:11px;color:#999;">AslanX 페이지 영상 섹션에 표시됩니다.</p>
                        </div>
                    </div>


                    {{-- ── 기존 첨부 자료 (파일) ── --}}
                    @if($education->pdf_paths && count($education->pdf_paths) > 0)
                    <div class="field-row align-top">
                        <div class="field-label">등록된 자료</div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <ul id="existing-pdf-list" style="margin:0;padding:0;list-style:none;display:flex;flex-direction:column;gap:6px;">
                                @foreach($education->pdf_paths as $index => $path)
                                @php $origName = preg_replace('/^[a-f0-9\-]{36}__/', '', basename($path)); @endphp
                                <li id="pdf-item-{{ $index }}" style="display:flex;align-items:center;gap:8px;font-size:13px;background:#f5f5f5;padding:7px 12px;border-radius:6px;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#e05" stroke-width="1.8" style="width:14px;height:14px;flex-shrink:0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <span>{{ $origName }}</span>
                                    <a href="/{{ $path }}" target="_blank" style="margin-left:auto;font-size:11px;color:#0066cc;text-decoration:none;">미리보기</a>
                                    <label style="display:flex;align-items:center;gap:4px;font-size:11px;color:#c00;cursor:pointer;margin-left:8px;">
                                        <input type="checkbox" name="remove_pdfs[]"
                                               value="{{ $path }}"
                                               onchange="document.getElementById('pdf-item-{{ $index }}').style.opacity=this.checked?'0.4':'1';">
                                        삭제
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    {{-- ── 새 자료 추가 ── --}}
                    <div class="field-row align-top">
                        <div class="field-label" style="display:block;">자료 추가<span style="display:block;font-size:11px;color:#999;font-weight:400;margin-top:2px;">PDF, PPT, DOC, HWP, ZIP</span></div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <div style="margin-bottom:8px;">
                                <span style="font-size:12px;color:#888;">파일당 최대 20MB까지 업로드 가능합니다.</span>
                            </div>
                            <label for="pdfs" style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:7px 14px;border:1px dashed #aaa;border-radius:6px;font-size:13px;color:#555;background:#fafafa;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:15px;height:15px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                파일 선택 (복수 가능)
                            </label>
                            <input type="file" id="pdfs" name="pdfs[]" multiple
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.hwp,.zip"
                                   style="display:none;" onchange="addPdfFiles(this)">
                            <ul id="pdf-preview-list" style="margin:10px 0 0;padding:0;list-style:none;display:flex;flex-direction:column;gap:5px;"></ul>
                        </div>
                    </div>

                    {{-- ── Google 링크 ── --}}
                    <div class="field-row align-top">
                        <div class="field-label" style="display:block;">Google 링크<span style="display:block;font-size:11px;color:#999;font-weight:400;margin-top:2px;">Drive, Slides, Docs 등</span></div>
                        <div class="field-value" style="padding-top:12px;padding-bottom:12px;">
                            <div id="google-links-container" style="display:flex;flex-direction:column;gap:8px;">
                                @foreach($education->google_links ?? [] as $i => $gl)
                                <div id="gl-row-existing-{{ $i }}" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#ea4335" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;"><path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"/><polyline points="16 2 22 2 22 8"/><line x1="11" y1="13" x2="22" y2="2"/></svg>
                                    <input type="text" name="google_links[{{ $i }}][name]" value="{{ $gl['name'] ?? '' }}" placeholder="링크 이름" style="flex:1;min-width:140px;max-width:220px;padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                                    <input type="url" name="google_links[{{ $i }}][url]" value="{{ $gl['url'] ?? '' }}" placeholder="https://drive.google.com/..." style="flex:2;min-width:240px;padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                                    <button type="button" onclick="document.getElementById('gl-row-existing-{{ $i }}').remove()" style="background:none;border:none;cursor:pointer;padding:4px;color:#aaa;font-size:18px;line-height:1;" title="삭제">×</button>
                                </div>
                                @endforeach
                            </div>
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
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('education.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
        {{-- !! 삭제 폼은 반드시 수정 폼 밖에 위치해야 합니다 (중첩 form 금지) !! --}}
        <div style="padding: 0 24px 24px; display:flex; justify-content:flex-end;">
            <form method="POST" action="{{ route('education.destroy', $education->id) }}"
                  onsubmit="return confirm('삭제하시겠습니까?\n이 작업은 되돌릴 수 없습니다.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">삭제</button>
            </form>
        </div>
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
        var dup = pendingFiles.some(function(p){ return p.name === f.name && p.size === f.size; });
        if (!dup) pendingFiles.push(f);
    });
    input.value = '';
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

// 폼 submit 시 DataTransfer로 누적 파일 주입
document.querySelector('form[method="POST"]').addEventListener('submit', function() {
    if (pendingFiles.length === 0) return;
    var dt = new DataTransfer();
    pendingFiles.forEach(function(f){ dt.items.add(f); });
    document.getElementById('pdfs').files = dt.files;
});

var glIdx = 1000;
function addGoogleLink() {
    var container = document.getElementById('google-links-container');
    var idx = glIdx++;
    var row = document.createElement('div');
    row.id = 'gl-row-' + idx;
    row.style.cssText = 'display:flex;align-items:center;gap:8px;flex-wrap:wrap;';
    row.innerHTML =
        '<svg viewBox="0 0 24 24" fill="none" stroke="#ea4335" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;"><path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"/><polyline points="16 2 22 2 22 8"/><line x1="11" y1="13" x2="22" y2="2"/></svg>'
        + '<input type="text" name="google_links[' + idx + '][name]" placeholder="링크 이름 (예: Google Slides)" style="flex:1;min-width:140px;max-width:220px;padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">'
        + '<input type="url" name="google_links[' + idx + '][url]" placeholder="https://drive.google.com/..." style="flex:2;min-width:240px;padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">'
        + '<button type="button" onclick="document.getElementById(\'gl-row-' + idx + '\').remove()" style="background:none;border:none;cursor:pointer;padding:4px;color:#aaa;font-size:18px;line-height:1;" title="삭제">×</button>';
    container.appendChild(row);
}
</script>
@endsection
