@extends('layouts.admin')

@section('title', '신청자 관리')

@section('content')

<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">교육 신청자 관리</h1>
    </div>
</div>

<div class="content-body">

    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif

    {{-- 교육 필터 카드 --}}
    <div class="card" style="margin-bottom: 16px;">
        <div class="card-body" style="padding: 16px 24px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <span style="font-size:13px; font-weight:600; color:#555; white-space:nowrap;">교육 선택</span>
            <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <a href="{{ route('applicants.index') }}"
                   class="btn btn-sm {{ !$educationId ? 'btn-primary' : 'btn-secondary' }}"
                   style="font-size:12px; padding:5px 12px;">
                    전체
                </a>
                @foreach($educations as $edu)
                <a href="{{ route('applicants.index', ['education_id' => $edu->id]) }}"
                   class="btn btn-sm {{ $educationId == $edu->id ? 'btn-primary' : 'btn-secondary' }}"
                   style="font-size:12px; padding:5px 12px;">
                    {{ $edu->title }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 현재 선택된 교육 제목 --}}
    <div class="card" style="margin-bottom: 16px;">
        <div class="card-body" style="padding: 16px 24px; display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; color:#888;">현재 조회:</span>
            <span style="font-size:15px; font-weight:600; color:#111;">{{ $educationTitle }}</span>
            <span style="font-size:13px; color:#888; margin-left:auto;">총 {{ $applicants->total() }}명</span>
        </div>
    </div>

    {{-- 신청자 목록 카드 --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">신청자 현황</div>
            <div class="header-actions">
                <button id="bulk-delete-btn" class="btn btn-secondary btn-sm" disabled>선택 삭제</button>
            </div>
        </div>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>교육명</th>
                        <th>이름</th>
                        <th>이메일</th>
                        <th>휴대폰 번호</th>
                        <th>회사 이름</th>
                        <th>부서</th>
                        <th>직급</th>
                        <th>제출일</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applicants as $applicant)
                    <tr>
                        <td class="checkbox-col">
                            <input type="checkbox" class="row-checkbox" value="{{ $applicant->id }}">
                        </td>
                        <td style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-size:12px; color:#666;">
                            {{ optional($applicant->education)->title ?? '-' }}
                        </td>
                        <td style="font-weight: 500;">{{ $applicant->name }}</td>
                        <td style="color: #555;">{{ $applicant->email }}</td>
                        <td>{{ $applicant->phone ?? '-' }}</td>
                        <td>{{ $applicant->company ?? '-' }}</td>
                        <td>{{ $applicant->department ?? '-' }}</td>
                        <td>{{ $applicant->jobtitle ?? '-' }}</td>
                        <td style="color: #888;">{{ $applicant->applied_at }}</td>
                        <td>
                            <form method="POST" action="{{ route('applicants.destroy', $applicant->id) }}"
                                  onsubmit="return confirm('삭제하시겠습니까?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm"
                                        style="font-size:11px; padding:3px 8px;">삭제</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                </svg>
                                <p>등록된 신청자가 없습니다.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 페이지네이션 --}}
        @if($applicants->hasPages())
        <div class="pagination">
            {{-- 이전 --}}
            @if($applicants->onFirstPage())
            <span class="page-item disabled">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
            @else
            <a href="{{ $applicants->previousPageUrl() }}" class="page-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            @endif

            {{-- 페이지 번호 --}}
            @for($p = 1; $p <= $applicants->lastPage(); $p++)
            <a href="{{ $applicants->url($p) }}" class="page-item {{ $applicants->currentPage() === $p ? 'active' : '' }}">{{ $p }}</a>
            @endfor

            {{-- 다음 --}}
            @if($applicants->hasMorePages())
            <a href="{{ $applicants->nextPageUrl() }}" class="page-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @else
            <span class="page-item disabled">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- 하단 버튼 --}}
    <div style="margin-top: 16px;">
        <a href="{{ route('education.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            교육 목록으로
        </a>
    </div>

</div>

<script>
// 전체 선택
var selectAll = document.getElementById('select-all');
var checkboxes = document.querySelectorAll('.row-checkbox');
var bulkBtn    = document.getElementById('bulk-delete-btn');

if (selectAll) {
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(function(cb) { cb.checked = selectAll.checked; });
        updateBulkBtn();
    });
}

checkboxes.forEach(function(cb) {
    cb.addEventListener('change', updateBulkBtn);
});

function updateBulkBtn() {
    var checked = document.querySelectorAll('.row-checkbox:checked').length;
    bulkBtn.disabled = checked === 0;
}

if (bulkBtn) {
    bulkBtn.addEventListener('click', function() {
        var checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;
        if (!confirm(checked.length + '명의 신청자를 삭제하시겠습니까?')) return;

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('applicants.bulkDestroy') }}';

        var csrf = document.createElement('input');
        csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        var method = document.createElement('input');
        method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
        form.appendChild(method);

        checked.forEach(function(cb) {
            var input = document.createElement('input');
            input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });
}

</script>

@endsection
