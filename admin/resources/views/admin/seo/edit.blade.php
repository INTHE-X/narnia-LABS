@extends('layouts.admin')
@section('title', 'SEO 수정 - ' . $setting->page_name)
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">SEO 수정 — {{ $setting->page_name }}</h1>
        <div style="font-size:.85rem;color:#888;">{{ $setting->page_url }}</div>
    </div>
</div>
<div class="content-body">
    @if($errors->any())
        <div style="margin-bottom:1rem;padding:.75rem 1rem;background:#fee;border:1px solid #f99;border-radius:6px;color:#c00;">
            <ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('seo.update', $setting->page_key) }}" method="POST" id="seo-form">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

            {{-- 왼쪽: 입력 폼 --}}
            <div>
                {{-- 기본 메타 --}}
                <div class="card" style="margin-bottom:1rem;">
                    <div class="card-header"><div class="card-title">기본 메타 태그</div></div>
                    <div class="card-body">
                        <div class="form-field" style="margin-bottom:1rem;">
                            <label for="meta_title">
                                Meta Title
                                <span id="title-count" style="font-size:.8rem;color:#888;float:right;">0 / 60자</span>
                            </label>
                            <input type="text" id="meta_title" name="meta_title"
                                value="{{ old('meta_title', $setting->meta_title) }}"
                                placeholder="NARNIA LABS - AI 솔루션 전문 기업"
                                maxlength="100"
                                oninput="countChars(this,'title-count',60)">
                        </div>
                        <div class="form-field" style="margin-bottom:1rem;">
                            <label for="meta_description">
                                Meta Description
                                <span id="desc-count" style="font-size:.8rem;color:#888;float:right;">0 / 160자</span>
                            </label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                placeholder="페이지를 설명하는 50~160자 텍스트"
                                maxlength="320"
                                oninput="countChars(this,'desc-count',160)">{{ old('meta_description', $setting->meta_description) }}</textarea>
                        </div>
                        <div class="form-field">
                            <label for="meta_keywords">Meta Keywords (쉼표로 구분)</label>
                            <input type="text" id="meta_keywords" name="meta_keywords"
                                value="{{ old('meta_keywords', $setting->meta_keywords) }}"
                                placeholder="AI, 나니아랩스, Physical AI, AslanX">
                        </div>
                    </div>
                </div>

                {{-- OG 태그 --}}
                <div class="card" style="margin-bottom:1rem;">
                    <div class="card-header"><div class="card-title">Open Graph (SNS 공유)</div></div>
                    <div class="card-body">
                        <div class="form-field" style="margin-bottom:1rem;">
                            <label for="og_title">OG Title (비우면 Meta Title 사용)</label>
                            <input type="text" id="og_title" name="og_title"
                                value="{{ old('og_title', $setting->og_title) }}"
                                placeholder="SNS 공유 시 제목">
                        </div>
                        <div class="form-field" style="margin-bottom:1rem;">
                            <label for="og_description">OG Description</label>
                            <textarea id="og_description" name="og_description" rows="2"
                                placeholder="SNS 공유 시 설명">{{ old('og_description', $setting->og_description) }}</textarea>
                        </div>
                        <div class="form-field">
                            <label for="og_image">OG Image URL (1200×630 권장)</label>
                            <input type="text" id="og_image" name="og_image"
                                value="{{ old('og_image', $setting->og_image) }}"
                                placeholder="https://narnialabs.mycafe24.com/admin/uploads/...">
                        </div>
                    </div>
                </div>

                {{-- 고급 설정 --}}
                <div class="card" style="margin-bottom:1rem;">
                    <div class="card-header"><div class="card-title">고급 설정</div></div>
                    <div class="card-body">
                        <div class="form-field" style="margin-bottom:1rem;">
                            <label for="canonical_url">Canonical URL (비우면 현재 URL)</label>
                            <input type="text" id="canonical_url" name="canonical_url"
                                value="{{ old('canonical_url', $setting->canonical_url) }}"
                                placeholder="https://narnialabs.mycafe24.com/...">
                        </div>
                        <div class="form-field" style="display:flex;align-items:center;gap:.5rem;">
                            <input type="checkbox" id="index_allow" name="index_allow" value="1"
                                {{ old('index_allow', $setting->index_allow) ? 'checked' : '' }}
                                style="width:16px;height:16px;">
                            <label for="index_allow" style="margin:0;cursor:pointer;">
                                검색엔진 인덱싱 허용 (체크 해제 시 noindex)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- 추적 코드 / 스크립트 --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            추적 코드 (GTM, GA, etc.)
                            @if($setting->page_key === 'global')
                                <span style="font-size:.75rem;color:#4ade80;margin-left:.5rem;font-weight:400;">* 모든 페이지에 공통 상단(HEAD)에 적용됩니다.</span>
                            @else
                                <span style="font-size:.75rem;color:#fbbf24;margin-left:.5rem;font-weight:400;">* 이 페이지만 적용되는 스크립트입니다.</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-field">
                            <label for="head_script">HEAD 스크립트 (스크립트 태그 포함 전체 입력)</label>
                            <textarea id="head_script" name="head_script" rows="8"
                                placeholder="<script> (GA4, GTM 코드 등을 넣으세요) </script>"
                                style="font-family:monospace;font-size:.85rem;line-height:1.4;background:#111;color:#eee;border-color:#333;">{{ old('head_script', $setting->head_script) }}</textarea>
                            <div style="font-size:.75rem;color:#888;margin-top:.5rem;">
                                &lt;script&gt; ... &lt;/script&gt; 태그를 포함하여 전체 내용을 그대로 붙여넣으세요.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 오른쪽: 미리보기 + 점수 --}}
            <div>
                {{-- SEO 점수 --}}
                <div class="card" style="margin-bottom:1rem;">
                    <div class="card-header"><div class="card-title">SEO 점수</div></div>
                    <div class="card-body" style="text-align:center;padding:1.5rem;">
                        @php
                            $score = $setting->seo_score;
                            $color = $setting->score_label === 'good' ? '#4ade80' : ($setting->score_label === 'warning' ? '#fbbf24' : '#f87171');
                        @endphp
                        <div style="font-size:3rem;font-weight:800;color:{{ $color }};">{{ $score }}</div>
                        <div style="font-size:.85rem;color:#888;margin:.3rem 0 1rem;">/ 100점</div>
                        <div style="width:100%;height:8px;background:#1e3a5f;border-radius:4px;">
                            <div style="width:{{ $score }}%;height:100%;background:{{ $color }};border-radius:4px;"></div>
                        </div>
                        <div style="margin-top:1rem;font-size:.82rem;color:#aaa;text-align:left;">
                            @if(!$setting->meta_title) <div>⚠️ Meta Title 없음</div> @endif
                            @if($setting->meta_title && mb_strlen($setting->meta_title) > 60) <div>⚠️ Title이 60자 초과</div> @endif
                            @if(!$setting->meta_description) <div>⚠️ Meta Description 없음</div> @endif
                            @if($setting->meta_description && mb_strlen($setting->meta_description) > 160) <div>⚠️ Description이 160자 초과</div> @endif
                            @if(!$setting->og_image) <div>⚠️ OG Image 없음</div> @endif
                            @if($score >= 80) <div style="color:#4ade80;">✅ SEO 설정 양호</div> @endif
                        </div>
                    </div>
                </div>

                {{-- 구글 검색 미리보기 --}}
                <div class="card" style="margin-bottom:1rem;">
                    <div class="card-header"><div class="card-title">구글 검색 미리보기</div></div>
                    <div class="card-body">
                        <div style="font-size:.75rem;color:#888;margin-bottom:.5rem;">실제 검색 결과 예시</div>
                        <div style="background:#fff;border-radius:8px;padding:1rem;color:#202124;">
                            <div id="preview-title" style="font-size:1.1rem;color:#1a0dab;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $setting->meta_title ?: '(제목 없음)' }}
                            </div>
                            <div style="font-size:.82rem;color:#006621;margin:.2rem 0;">narnialabs.mycafe24.com{{ $setting->page_url }}</div>
                            <div id="preview-desc" style="font-size:.85rem;color:#545454;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $setting->meta_description ?: '(설명 없음)' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SNS 미리보기 --}}
                <div class="card">
                    <div class="card-header"><div class="card-title">SNS 공유 미리보기</div></div>
                    <div class="card-body">
                        <div style="border:1px solid #333;border-radius:8px;overflow:hidden;">
                            @if($setting->og_image)
                                <img src="{{ $setting->og_image }}" alt="OG Image" style="width:100%;height:120px;object-fit:cover;">
                            @else
                                <div style="width:100%;height:120px;background:#1e3a5f;display:flex;align-items:center;justify-content:center;color:#555;font-size:.8rem;">OG 이미지 없음</div>
                            @endif
                            <div style="padding:.7rem;background:#fff;">
                                <div style="font-size:.7rem;color:#888;text-transform:uppercase;">NARNIALABS.MYCAFE24.COM</div>
                                <div style="font-size:.9rem;font-weight:600;color:#1c1e21;margin:.2rem 0;">{{ $setting->og_title ?: $setting->meta_title ?: '(제목 없음)' }}</div>
                                <div style="font-size:.82rem;color:#606770;">{{ $setting->og_description ?: $setting->meta_description ?: '(설명 없음)' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions" style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">저장하기</button>
            <a href="{{ route('seo.index') }}" class="btn btn-secondary">목록으로</a>
        </div>
    </form>
</div>

<script>
function countChars(el, counterId, limit) {
    var len = el.value.length;
    var counter = document.getElementById(counterId);
    if (!counter) return;
    counter.textContent = len + ' / ' + limit + '자';
    counter.style.color = len > limit ? '#f87171' : len > limit * 0.8 ? '#fbbf24' : '#888';
}

// 실시간 미리보기
document.getElementById('meta_title')?.addEventListener('input', function() {
    var el = document.getElementById('preview-title');
    if (el) el.textContent = this.value || '(제목 없음)';
});
document.getElementById('meta_description')?.addEventListener('input', function() {
    var el = document.getElementById('preview-desc');
    if (el) el.textContent = this.value || '(설명 없음)';
});

// 초기 카운터
(function(){
    var t = document.getElementById('meta_title');
    var d = document.getElementById('meta_description');
    if (t) countChars(t, 'title-count', 60);
    if (d) countChars(d, 'desc-count', 160);
})();
</script>
@endsection
