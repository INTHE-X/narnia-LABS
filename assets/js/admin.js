/**
 * Narnia Labs Admin — admin.js
 * 순수 JavaScript (PHP 로직과 완전히 분리)
 * 디자인/인터랙션 수정은 이 파일에서만 하세요
 */

document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 1. 사이드바 아코디언 메뉴
    // ============================================================
    const navItems = document.querySelectorAll('.nav-item[data-submenu]');

    navItems.forEach(item => {
        const link = item.querySelector(':scope > .nav-link');
        if (!link) return;

        link.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpen = item.classList.contains('open');

            // 모두 닫기
            navItems.forEach(i => i.classList.remove('open'));

            // 현재 항목 토글
            if (!isOpen) {
                item.classList.add('open');
            }
        });
    });

    // 현재 URL 기준 활성 메뉴 자동 설정
    const currentPath = window.location.pathname;
    const allLinks = document.querySelectorAll('.nav-link[href]');

    allLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.startsWith(link.getAttribute('href'))) {
            link.classList.add('active');
            // 하위 메뉴인 경우 부모도 열기
            const parentItem = link.closest('.nav-submenu')?.closest('.nav-item');
            if (parentItem) {
                parentItem.classList.add('open');
            }
        }
    });

    // ============================================================
    // 2. 전체 선택 체크박스
    // ============================================================
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkAction();
        });

        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const allChecked = [...rowCheckboxes].every(c => c.checked);
                const someChecked = [...rowCheckboxes].some(c => c.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
                updateBulkAction();
            });
        });
    }

    function updateBulkAction() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const bulkBtn = document.getElementById('bulk-delete-btn');
        if (bulkBtn) {
            bulkBtn.disabled = checked.length === 0;
            bulkBtn.textContent = checked.length > 0
                ? `선택 삭제 (${checked.length})`
                : '선택 삭제';
        }
    }

    // ============================================================
    // 3. 삭제 확인 모달
    // ============================================================
    const deleteModal = document.getElementById('delete-modal');

    // 삭제 버튼 클릭 시 모달 열기
    document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!deleteModal) return;

            const url = this.dataset.url || this.closest('form')?.action || '#';
            const form = deleteModal.querySelector('form');
            if (form) form.action = url;

            deleteModal.classList.add('open');
        });
    });

    // 모달 닫기
    document.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', function () {
            this.closest('.modal-overlay')?.classList.remove('open');
        });
    });

    // 오버레이 클릭으로 닫기
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('open');
        });
    });

    // ============================================================
    // 4. 토스트 알림
    // ============================================================
    window.showToast = function (message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icon = type === 'success'
            ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
            : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';

        toast.innerHTML = `${icon}<span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };

    // 세션 플래시 메시지 자동 표시
    const flashSuccess = document.querySelector('[data-flash-success]')?.dataset.flashSuccess;
    const flashError = document.querySelector('[data-flash-error]')?.dataset.flashError;
    if (flashSuccess) showToast(flashSuccess, 'success');
    if (flashError) showToast(flashError, 'error');

    // ============================================================
    // 5. 모바일 사이드바 토글
    // ============================================================
    const mobileToggle = document.getElementById('mobile-sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function () {
            sidebar.classList.toggle('mobile-open');
        });
    }

    // ============================================================
    // 6. 테이블 행 클릭 → 상세 페이지 이동
    // ============================================================
    document.querySelectorAll('.data-table tbody tr[data-href]').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function (e) {
            // 체크박스, 버튼 클릭 시 제외
            if (e.target.closest('input, button, a')) return;
            window.location.href = this.dataset.href;
        });
    });

    // ============================================================
    // 7. 이미지 미리보기 (파일 업로드)
    // ============================================================
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            if (!preview) return;

            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // ============================================================
    // 8. 폼 제출 확인
    // ============================================================
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm(this.dataset.confirm || '정말 진행하시겠습니까?')) {
                e.preventDefault();
            }
        });
    });

    // ============================================================
    // 9. 자동 사라지는 알림 메시지
    // ============================================================
    document.querySelectorAll('.alert-auto-hide').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

});
