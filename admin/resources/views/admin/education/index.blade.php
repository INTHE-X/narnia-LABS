@extends('layouts.admin')
@section('title', '교육 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">교육 관리</h1>
        <div class="header-actions">
            <a href="{{ route('education.create') }}" class="btn btn-primary">교육 등록</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif
    <div class="card">
        <div class="card-header"><div class="card-title">교육 목록</div></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:60px;">번호</th>
                        <th>이미지</th>
                        <th>제목</th>
                        <th>카테고리</th>
                        <th>날짜</th>
                        <th style="text-align:center;width:110px;">노출</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($educations as $item)
                    <tr data-href="{{ route('education.edit', $item->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;">{{ $item->id }}</td>
                        <td>
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
                            @else
                                <span style="color:#666;font-size:.8rem;">없음</span>
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->published_at?->format('Y-m-d') ?? '-' }}</td>
                        <td style="text-align:center;" onclick="event.stopPropagation();">
                            <label class="edu-vis-switch" title="{{ $item->is_visible ? '노출 중 (클릭 시 비노출)' : '비노출 (클릭 시 노출)' }}">
                                <input type="checkbox" class="edu-vis-input"
                                    {{ $item->is_visible ? 'checked' : '' }}
                                    data-url="{{ route('education.toggleVisibility', $item->id) }}"
                                    autocomplete="off">
                                <span class="edu-vis-track">
                                    <span class="edu-vis-thumb"></span>
                                </span>
                                <span class="edu-vis-label">{{ $item->is_visible ? '노출' : '비노출' }}</span>
                            </label>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>등록된 교육 콘텐츠가 없습니다.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* 토글 스위치 */
.edu-vis-switch {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    user-select: none;
}
.edu-vis-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.edu-vis-track {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 22px;
    border-radius: 11px;
    background: #ddd;
    transition: background .22s;
    flex-shrink: 0;
}
.edu-vis-input:checked + .edu-vis-track {
    background: #222;
}
.edu-vis-thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,.18);
    transition: transform .22s;
}
.edu-vis-input:checked + .edu-vis-track .edu-vis-thumb {
    transform: translateX(18px);
}
.edu-vis-label {
    font-size: 12px;
    font-weight: 500;
    color: #999;
    min-width: 36px;
    transition: color .2s;
}
.edu-vis-input:checked ~ .edu-vis-label {
    color: #222;
}
/* 로딩 중 흐리게 */
.edu-vis-switch.loading {
    opacity: .5;
    pointer-events: none;
}
</style>

<script>
document.querySelectorAll('.edu-vis-input').forEach(function(input) {
    input.addEventListener('change', function() {
        var url     = this.dataset.url;
        var label   = this.closest('.edu-vis-switch').querySelector('.edu-vis-label');
        var swLabel = this.closest('.edu-vis-switch');
        var checked = this.checked;

        swLabel.classList.add('loading');
        swLabel.title = checked ? '노출 중 (클릭 시 비노출)' : '비노출 (클릭 시 노출)';

        var csrfToken = document.querySelector('meta[name="csrf-token"]') ?
            document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            swLabel.classList.remove('loading');
            if (!data.success) {
                // 실패 시 원상복구
                input.checked = !checked;
                return;
            }
            label.textContent = data.is_visible ? '노출' : '비노출';
            swLabel.title = data.is_visible ? '노출 중 (클릭 시 비노출)' : '비노출 (클릭 시 노출)';
        })
        .catch(function(err) {
            console.error('토글 실패:', err);
            swLabel.classList.remove('loading');
            input.checked = !checked;
        });
    });
});
</script>
@endsection
