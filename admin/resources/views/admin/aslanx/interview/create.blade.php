@extends('layouts.admin')
@section('title', 'AslanX 인터뷰 등록')

@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">인터뷰 등록</h1>
    </div>
</div>

<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">인터뷰 정보 입력</div></div>

        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('aslanx-interviews.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 로고 + 기본 정보 --}}
            <div class="img-form-layout" style="grid-template-columns:280px 1fr;">
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('logo').click()" style="width:220px;height:140px;">
                        <div class="img-placeholder" id="img-ph">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            로고 업로드
                        </div>
                        <img id="img-preview" style="display:none;width:100%;height:100%;object-fit:contain;padding:10px;" alt="">
                    </div>
                    <input type="file" id="logo" name="logo" accept="image/*" style="display:none;"
                        onchange="var p=document.getElementById('img-preview'),ph=document.getElementById('img-ph');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';ph.style.display='none';};r.readAsDataURL(this.files[0]);}">
                    <span class="img-form-hint">클릭하여 로고 선택<br>PNG, SVG, JPG (최대 2MB)</span>
                </div>

                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">회사명 (국문) *</div>
                        <div class="field-value"><input type="text" name="company" value="{{ old('company') }}" placeholder="예: 삼성전자" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">회사명 (EN)</div>
                        <div class="field-value"><input type="text" name="company_en" value="{{ old('company_en') }}" placeholder="Samsung Electronics"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">회사명 (JP)</div>
                        <div class="field-value"><input type="text" name="company_jp" value="{{ old('company_jp') }}" placeholder="サムスン電子"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">직책 (국문)</div>
                        <div class="field-value"><input type="text" name="position" value="{{ old('position') }}" placeholder="예: 해석 엔지니어"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">직책 (EN)</div>
                        <div class="field-value"><input type="text" name="position_en" value="{{ old('position_en') }}" placeholder="Analysis Engineer"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">직책 (JP)</div>
                        <div class="field-value"><input type="text" name="position_jp" value="{{ old('position_jp') }}" placeholder="解析エンジニア"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">노출 여부</div>
                        <label class="img-toggle-wrap" for="is_visible">
                            <input type="checkbox" id="is_visible" name="is_visible" value="1" checked>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- 인터뷰 내용 3열 --}}
            <div style="padding:20px 24px 0;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                @foreach([['ko','인터뷰 내용 (국문) *','content'],['en','인터뷰 내용 (EN)','content_en'],['jp','인터뷰 내용 (JP)','content_jp']] as [$lang,$label,$field])
                <div>
                    <div style="font-size:13px;color:var(--gray-600);font-weight:500;margin-bottom:8px;">{{ $label }}</div>
                    <textarea name="{{ $field }}" placeholder="{{ $lang === 'ko' ? '고객 인터뷰 내용을 입력하세요.' : ($lang === 'en' ? 'Enter interview content in English.' : 'インタビュー内容を日本語で入力してください。') }}"
                        rows="8" style="width:100%;padding:.6rem .8rem;background:#fff;border:1px solid #ddd;border-radius:6px;color:#111;font-size:.9rem;resize:vertical;"
                        {{ $lang === 'ko' ? 'required' : '' }}>{{ old($field) }}</textarea>
                </div>
                @endforeach
            </div>

            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">등록하기</button>
                    <a href="{{ route('aslanx-interviews.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
