<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '어드민') — NARNIA LABS</title>
    <meta name="description" content="@yield('description', '나니아랩스 관리자 페이지')">
    <link rel="stylesheet" href="https://narnialabs.mycafe24.com/admin/css/admin.css?v=20260428">
    @stack('head')
</head>
<body>

<!-- 토스트 컨테이너 -->
<div id="toast-container" class="toast-container"></div>

<!-- 세션 플래시 메시지 (JS 자동 감지용) -->
@if (session('success'))
    <span data-flash-success="{{ session('success') }}" style="display:none"></span>
@endif
@if (session('error'))
    <span data-flash-error="{{ session('error') }}" style="display:none"></span>
@endif

<div class="admin-wrapper">

    <!-- ========================
         사이드바
         ======================== -->
    <aside class="admin-sidebar" id="admin-sidebar">

        <!-- 로고 -->
        <div class="sidebar-logo">
            <a href="{{ route('dashboard') }}" style="display: flex; justify-content: center;">
                <img src="https://narnialabs.mycafe24.com/assets/img/common/logo.svg" alt="NARNIA LABS" style="max-width: 140px; height: auto; filter: brightness(0) invert(1);">
            </a>
        </div>

        <!-- 로그인 사용자 정보 -->
        <div class="sidebar-user">
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->username ?? Auth::user()->name ?? '관리자' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">로그아웃</button>
            </form>
        </div>

        <!-- 네비게이션 -->
        <nav class="sidebar-nav">

            <!-- 컨텐츠 관리 -->
            <div class="nav-section-title">컨텐츠 관리</div>

            <a href="{{ route('case-studies.index') }}" class="nav-link {{ request()->routeIs('case-studies.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                케이스스터디
            </a>

            <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
                이벤트 관리
            </a>

            <a href="{{ route('publications.index') }}" class="nav-link {{ request()->routeIs('publications.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M9 12h6"/><path d="M9 16h6"/><path d="M9 8h2"/>
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
                퍼블리케이션
            </a>

            <a href="{{ route('tech-blogs.index') }}" class="nav-link {{ request()->routeIs('tech-blogs.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="12" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                테크블로그
            </a>

            <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                뉴스 관리
            </a>

            <a href="{{ route('popup.index') }}" class="nav-link {{ request()->routeIs('popup.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <line x1="9" y1="3" x2="9" y2="21"/>
                </svg>
                팝업 관리
            </a>

            <a href="{{ route('team.index') }}" class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                팀 소개 관리
            </a>

            <!-- 교육 -->
            <div class="nav-section-title">교육</div>

            <div class="nav-item {{ request()->routeIs('education.*') || request()->routeIs('applicants.*') ? 'open' : '' }}" data-submenu>
                <div class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    교육
                    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>
                <div class="nav-submenu">
                    <a href="{{ route('education.index') }}" class="nav-link {{ request()->routeIs('education.*') ? 'active' : '' }}">교육 관리</a>
                    <a href="{{ route('applicants.index') }}" class="nav-link {{ request()->routeIs('applicants.*') ? 'active' : '' }}">신청자 관리</a>
                </div>
            </div>

            <!-- 상담 관리 -->
            <div class="nav-section-title">상담 관리</div>

            <a href="{{ route('inquiry.index') }}" class="nav-link {{ request()->routeIs('inquiry.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                문의 관리
            </a>

            <!-- 운영 설정 -->
            <div class="nav-section-title">운영 설정</div>

            <a href="{{ route('resource-menus.index') }}" class="nav-link {{ request()->routeIs('resource-menus.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                    <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
                Resource 메뉴 관리
            </a>

            <a href="{{ route('seo.index') }}" class="nav-link {{ request()->routeIs('seo.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                SEO 도구
            </a>

            <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.07 4.93l-1.41 1.41M21 12h-2M19.07 19.07l-1.41-1.41M12 21v-2M4.93 19.07l1.41-1.41M2 12h2M4.93 4.93l1.41 1.41"/>
                </svg>
                관리자 설정
            </a>

        </nav>

        <!-- 사이트 바로가기 (하단 고정) -->
        <div class="sidebar-footer">
            <a href="https://narnialabs.mycafe24.com/index.html" target="_blank" class="btn-site-link" id="site-link-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                사이트 바로가기
            </a>
        </div>
    </aside>

    <!-- ========================
         메인 컨텐츠
         ======================== -->
    <main class="admin-content">
        @yield('content')
    </main>

</div>

<!-- 삭제 확인 모달 -->
<div class="modal-overlay" id="delete-modal">
    <div class="modal">
        <h3 class="modal-title">삭제 확인</h3>
        <p class="modal-desc">이 항목을 삭제하면 복구할 수 없습니다.<br>정말 삭제하시겠습니까?</p>
        <form method="POST" id="delete-form">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close>취소</button>
                <button type="submit" class="btn btn-danger">삭제하기</button>
            </div>
        </form>
    </div>
</div>

<script src="https://narnialabs.mycafe24.com/admin/js/admin.js?v=20260428"></script>
@stack('scripts')

</body>
</html>
