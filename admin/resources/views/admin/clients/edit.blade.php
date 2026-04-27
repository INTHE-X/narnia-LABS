@extends('layouts.admin')
@section('title', '로고 수정')
@section('content')
<div class="content-header">
    <div class="content-header-inner"><h1 class="page-title">클라이언트 로고 수정</h1></div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">로고 정보 수정</div></div>
        @if($errors->any())
            <div style="margin:16px 24px;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
                <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="img-form-layout" style="grid-template-columns:300px 1fr;">
                {{-- 왼쪽: 로고 이미지 --}}
                <div class="img-form-panel">
                    <div class="img-form-box" onclick="document.getElementById('image').click()" style="width:220px;height:140px;background:#f8f8f8;">
                        @if($client->image_path)
                            <img id="img-preview" src="{{ $client->image_url }}" alt="{{ $client->name }}" style="max-width:100%;max-height:100%;object-fit:contain;padding:10px;">
                        @else
                            <div class="img-placeholder" id="img-ph">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                로고 이미지 업로드
                            </div>
                            <img id="img-preview" style="display:none;max-width:100%;max-height:100%;object-fit:contain;padding:10px;" alt="">
                        @endif
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" style="display:none;"
                        onchange="var p=document.getElementById('img-preview'),ph=document.getElementById('img-ph');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';if(ph)ph.style.display='none';};r.readAsDataURL(this.files[0]);}">
                    <span class="img-form-hint">클릭하여 이미지 변경<br>(비워두면 유지)<br>SVG, PNG, JPG</span>
                </div>

                {{-- 오른쪽: 입력 필드 --}}
                <div class="field-rows">
                    <div class="field-row">
                        <div class="field-label">회사명 *</div>
                        <div class="field-value"><input type="text" name="name" value="{{ old('name', $client->name) }}" required></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">링크 URL</div>
                        <div class="field-value"><input type="url" name="link" value="{{ old('link', $client->link) }}" placeholder="https://..."></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">정렬 순서</div>
                        <div class="field-value"><input type="number" name="sort_order" value="{{ old('sort_order', $client->sort_order) }}" min="0" style="max-width:120px;"></div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">공개 여부</div>
                        <label class="img-toggle-wrap" for="is_active" style="padding:14px 20px;">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                            <span class="img-toggle-btn"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="img-form-actions">
                <div class="img-form-actions-left">
                    <button type="submit" class="btn btn-primary">수정하기</button>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">취소</a>
                </div>
                <button type="button" class="btn btn-danger"
                    data-confirm-delete
                    data-url="{{ route('clients.destroy', $client->id) }}">삭제</button>
            </div>
        </form>
    </div>
</div>
@endsection
