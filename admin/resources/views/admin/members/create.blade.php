@extends('layouts.admin')
@section('title', '관리자 등록')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">관리자 등록</h1>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-header"><div class="card-title">관리자 정보</div></div>
        <div class="card-body">
            <form action="{{ route('members.store') }}" method="POST">
                @csrf

                {{-- 아이디 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="username">아이디 *</label>
                    <div class="form-field-control">
                        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="아이디를 입력하세요" required>
                        @error('username') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 비밀번호 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="password">비밀번호 *</label>
                    <div class="form-field-control">
                        <input type="password" id="password" name="password" placeholder="8자 이상 입력하세요" required>
                        @error('password') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 비밀번호 확인 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="password_confirmation">비밀번호 확인 *</label>
                    <div class="form-field-control">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="비밀번호를 다시 입력하세요" required>
                    </div>
                </div>

                {{-- 이름 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="name">이름</label>
                    <div class="form-field-control">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="이름을 입력하세요">
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 휴대전화 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="phone">휴대전화</label>
                    <div class="form-field-control">
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="숫자만 입력바랍니다" inputmode="numeric">
                        @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 이메일 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="email">이메일</label>
                    <div class="form-field-control">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="이메일을 입력하세요">
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 관리 권한 --}}
                <div class="form-field-row">
                    <label class="form-field-label" for="role">관리 권한</label>
                    <div class="form-field-control">
                        <select id="role" name="role">
                            <option value="master" {{ old('role') == 'master' ? 'selected' : '' }}>마스터</option>
                            <option value="admin"  {{ old('role') == 'admin'  ? 'selected' : '' }}>일반 관리자</option>
                        </select>
                        @error('role') <span class="form-error">{{ $message }}</span> @enderror

                        <div class="role-info-box">
                            <div class="role-badges">
                                <span class="role-badge r">r 읽기</span>
                                <span class="role-badge w">w 쓰기</span>
                                <span class="role-badge d">d 삭제</span>
                            </div>
                            <div class="role-desc-list">
                                <div class="role-desc-item">
                                    <span class="role-desc-title">최고 관리자</span>
                                    <span class="role-desc-text">모든 메뉴 접근 가능 / r,w,d 모든 권한</span>
                                </div>
                                <div class="role-desc-item">
                                    <span class="role-desc-title">일반 관리자</span>
                                    <span class="role-desc-text">일부 메뉴 접근 가능(메뉴 요청) / r,w,d 모든 권한</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">등록하기</button>
                    <a href="{{ route('members.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// 휴대전화 숫자만 입력
document.getElementById('phone').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endpush
@endsection
