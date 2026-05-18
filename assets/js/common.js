class HTMLIncluder {
    constructor() {
        this.cache = new Map();
        this.eventHandlers = new Map();
    }

    async include(selector, url, options = {}) {
        const { cache = true, reinit = true } = options;

        const container = document.querySelector(selector);
        if (!container) {
            console.warn(`Element "${selector}" not found, skipping include`);
            return null;
        }

        try {
            let html;
            if (cache && this.cache.has(url)) {
                html = this.cache.get(url);
            } else {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${url}`);
                }
                html = await response.text();
                if (cache) this.cache.set(url, html);
            }

            container.innerHTML = html.replace(/\{\{base\}\}/g, projectBase);
            this.executeScripts(container);

            if (reinit) {
                this.reinitializeEvents(container);
            }

            container.dispatchEvent(
                new CustomEvent("included", {
                    detail: { url, selector },
                }),
            );

            return container;
        } catch (error) {
            console.error(`Include 실패 [${url}]:`, error);
            return null;
        }
    }

    executeScripts(container) {
        const scripts = container.querySelectorAll("script");
        scripts.forEach((script) => {
            const newScript = document.createElement("script");
            if (script.src) {
                newScript.src = script.src;
                newScript.async = false;
            } else {
                newScript.textContent = script.textContent;
            }
            Array.from(script.attributes).forEach((attr) => {
                if (attr.name !== "src") {
                    newScript.setAttribute(attr.name, attr.value);
                }
            });
            document.head.appendChild(newScript);
            script.remove();
        });
    }

    reinitializeEvents(container) {
        const elements = container.querySelectorAll("[data-event]");
        elements.forEach((el) => {
            const [eventType, handlerName] = el.dataset.event.split(":");
            if (window[handlerName]) {
                el.addEventListener(eventType, window[handlerName]);
            }
        });
    }

    registerHandler(name, handler) {
        this.eventHandlers.set(name, handler);
        window[name] = handler;
    }
}

const includer = new HTMLIncluder();

const getProjectBase = () => {
    // 서버 배포 환경: 항상 루트 기준 절대경로 사용
    // inc/header.html, inc/footer.html 등은 항상 사이트 루트에 위치
    return "";
};

const projectBase = getProjectBase();

function detectLang() {
    const path = window.location.pathname;
    if (/\/eng\//.test(path)) return "eng";
    if (/\/jp\//.test(path)) return "jp";
    return "kor";
}

async function initPage() {
    const lang = detectLang();
    const headerFile = lang === "kor" ? "header.html" : `${lang}Header.html`;
    const requestFile = lang === "kor" ? "request.html" : `${lang}Request.html`;
    const footerFile = lang === "kor" ? "footer.html" : `${lang}Footer.html`;

    await includer.include("#header", `/include/${headerFile}`);
    await includer.include("#request", `/include/${requestFile}`);
    await includer.include("#footer", `/include/${footerFile}`);
}


function initComingSoonPopup() {
    // 팝업 HTML 동적 생성
    const messages = {
        kor: {
            title: "준비중입니다.",
            desc: "빠른 시일 내에 찾아뵙겠습니다.",
            close: "닫기"
        },
        eng: {
            title: "Coming Soon",
            desc: "We will be back soon.",
            close: "Close"
        },
        jp: {
            title: "準備中です。",
            desc: "近日中に公開いたします。",
            close: "閉じる"
        }
    };
    const lang = detectLang();
    const msg = messages[lang] || messages.kor;

    const popupHTML = `
    <div id="comingSoon_overlay" style="
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    ">
        <div style="
            background: #fff;
            padding: 48px 52px;
            text-align: center;
            max-width: 360px;
            width: 90%;
            position: relative;
        ">
            <button id="comingSoon_close" style="
                position: absolute;
                top: 16px;
                right: 16px;
                background: none;
                border: none;
                cursor: pointer;
                width: 24px;
                height: 24px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            " aria-label="${msg.close}">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L15 15M15 1L1 15" stroke="#1a1a1a" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
            <p style="
                font-family: 'Pretendard', sans-serif;
                font-size: 22px;
                font-weight: 700;
                color: #191919;
                margin-bottom: 10px;
                letter-spacing: -0.02em;
            ">${msg.title}</p>
            <p style="
                font-family: 'Pretendard', sans-serif;
                font-size: 14px;
                font-weight: 400;
                color: rgba(0,0,0,0.45);
                letter-spacing: -0.01em;
                line-height: 1.6;
            ">${msg.desc}</p>
        </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', popupHTML);

    const overlay = document.getElementById('comingSoon_overlay');
    const closeBtn = document.getElementById('comingSoon_close');

    function openPopup() {
        overlay.style.display = 'flex';
    }
    function closePopup() {
        overlay.style.display = 'none';
    }

    // 오버레이 클릭 시 닫기
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closePopup();
    });
    closeBtn.addEventListener('click', closePopup);

    // ESC 키로 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePopup();
    });
}

// ── 어드민 팝업 (API 연동) ─────────────────────────────
async function initAdminPopup() {
    const API = '/admin/api/popups';
    const DISMISS_PREFIX = 'popup_dismissed_';

    let popups = [];
    try {
        const res = await fetch(API);
        if (!res.ok) return;
        popups = await res.json();
    } catch (e) {
        return;
    }

    // 오늘 닫은 팝업 필터링
    const today = new Date().toISOString().slice(0, 10);
    popups = popups.filter(function(p) {
        return localStorage.getItem(DISMISS_PREFIX + p.id) !== today;
    });

    if (!popups.length) return;

    // 스타일 주입 (한 번만)
    if (!document.getElementById('admin-popup-style')) {
        const style = document.createElement('style');
        style.id = 'admin-popup-style';
        style.textContent = `
            #admin-popup-overlay {
                display: flex;
                position: fixed;
                inset: 0;
                background: transparent;
                z-index: 10000;
                align-items: center;
                justify-content: center;
                pointer-events: none;
            }
            #admin-popup-overlay.pos-bottom-right {
                align-items: flex-end;
                justify-content: flex-end;
                padding: 24px;
            }
            #admin-popup-overlay .admin-popup-box {
                pointer-events: auto;
            }
            .admin-popup-box {
                background: transparent;
                position: relative;
                max-width: 500px;
                width: 92%;
                box-shadow: 0 8px 40px rgba(0,0,0,0.25);
            }
            .admin-popup-box img {
                width: 100%;
                display: block;
            }
            .admin-popup-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 14px 18px;
                background: transparent;
                font-size: 13px;
            }
            .admin-popup-dismiss {
                background: none;
                border: none;
                cursor: pointer;
                font-size: 13px;
                color: #fff;
                padding: 0;
                letter-spacing: -0.01em;
            }
            .admin-popup-dismiss:hover { text-decoration: underline; }
            .admin-popup-x {
                background: none;
                border: none;
                cursor: pointer;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                width: 24px;
                height: 24px;
            }
            .admin-popup-x svg { display: block; }
        `;
        document.head.appendChild(style);
    }

    // 팝업 렌더
    let idx = 0;
    const overlay = document.createElement('div');
    overlay.id = 'admin-popup-overlay';

    function renderPopup(popup) {
        overlay.innerHTML = '';

        const box = document.createElement('div');
        box.className = 'admin-popup-box';

        // 이미지
        if (popup.image) {
            const img = document.createElement('img');
            img.src = '' + popup.image;
            img.alt = popup.title || '팝업';
            if (popup.link_url) {
                img.style.cursor = 'pointer';
                img.addEventListener('click', function() {
                    window.open(popup.link_url, '_blank');
                });
            }
            box.appendChild(img);
        } else {
            const titleEl = document.createElement('p');
            titleEl.style.cssText = 'padding:32px 24px;font-size:18px;font-weight:700;text-align:center;';
            titleEl.textContent = popup.title || '';
            if (popup.link_url) {
                const a = document.createElement('a');
                a.href = popup.link_url;
                a.target = '_blank';
                a.style.color = 'inherit';
                a.appendChild(titleEl);
                box.appendChild(a);
            } else {
                box.appendChild(titleEl);
            }
        }

        // 하단 버튼 영역 (투명 배경)
        const footer = document.createElement('div');
        footer.className = 'admin-popup-footer';

        // 오늘 하루 닫기 (좌)
        const dismissBtn = document.createElement('button');
        dismissBtn.className = 'admin-popup-dismiss';
        const lang = detectLang();
        const dismissText = {
            kor: '오늘은 그만 볼래요',
            eng: "Don't show today",
            jp: '今日はもう見ない'
        };
        dismissBtn.textContent = dismissText[lang] || dismissText.kor;
        dismissBtn.addEventListener('click', function() {
            localStorage.setItem(DISMISS_PREFIX + popup.id, today);
            nextOrClose();
        });

        // X 닫기 버튼 (우)
        const xBtn = document.createElement('button');
        xBtn.className = 'admin-popup-x';
        xBtn.setAttribute('aria-label', '닫기');
        xBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L15 15M15 1L1 15" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg>';
        xBtn.addEventListener('click', function() { closeAll(); });

        footer.appendChild(dismissBtn);
        footer.appendChild(xBtn);

        box.appendChild(footer);
        overlay.appendChild(box);
    }

    function nextOrClose() {
        idx++;
        if (idx < popups.length) {
            renderPopup(popups[idx]);
        } else {
            closeAll();
        }
    }

    function closeAll() {
        if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
    }

    // 오버레이 바깥 클릭 닫기
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeAll();
    });

    renderPopup(popups[0]);

    // position 클래스 적용
    const pos = popups[0].position || 'center';
    if (pos === 'bottom-right') {
        overlay.classList.add('pos-bottom-right');
    }

    document.body.appendChild(overlay);
}

/**
 * 전역 파비콘 설정
 */
(function() {
    function setFavicon() {
        if (document.querySelector('link[rel="icon"]')) return;
        const link = document.createElement('link');
        link.rel = 'icon';
        link.type = 'image/svg+xml';
        link.href = '/assets/img/common/favicon.svg';
        document.head.appendChild(link);
    }
    setFavicon();
})();

document.addEventListener("DOMContentLoaded", () => {
    initPage().then(() => {
        if (typeof AOS !== "undefined") {
            AOS.init();
        }
        initPcNav();
        initMobileNav();
        initFooterAccordion();
        initComingSoonPopup();
        initAdminPopup();
    });

    // Header hide on scroll down, show on scroll up
    // let lastScrollY = window.scrollY;

    // window.addEventListener("scroll", () => {
    //     const header = document.querySelector("#header");
    //     if (!header) return;

    //     const currentScrollY = window.scrollY;

    //     if (currentScrollY > lastScrollY && currentScrollY > 85) {
    //         header.classList.add("hide");
    //     } else {
    //         header.classList.remove("hide");
    //     }

    //     lastScrollY = currentScrollY;
    // });

    function initPcNav() {
        const dropdownParents = document.querySelectorAll('#pc_nav .floor1 > li, #header .lang_select');
        dropdownParents.forEach(el => {
            const floor2 = el.querySelector('.floor2');
            if (!floor2) return;

            el.addEventListener('mouseenter', () => {
                floor2.classList.add('is_open');
            });
            el.addEventListener('mouseleave', () => {
                floor2.classList.remove('is_open');
            });
        });

        // 1depth 링크 클릭 시 상단 이동(#) 방지 — 단, 실제 링크(href가 '#'·빈값·javascript:가 아님)면 이동 허용
        document.querySelectorAll('#pc_nav .floor1 > li > a, #header .lang_select > a').forEach(a => {
            a.addEventListener('click', (e) => {
                const href = (a.getAttribute('href') || '').trim();
                if (!href || href === '#' || href.startsWith('javascript:')) {
                    e.preventDefault();
                }
            });
        });
    }

    function initMobileNav() {
        const header = document.querySelector('#header');
        const btnMenu = document.querySelector('.btn_mobile_menu');
        const mobileNav = document.querySelector('.mobile_nav');
        if (!header || !btnMenu || !mobileNav) return;

        // 햄버거 토글
        btnMenu.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('is_open');
            header.classList.toggle('mob_open', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
            btnMenu.setAttribute('aria-label', isOpen ? '메뉴 닫기' : '메뉴 열기');
        });

        // 아코디언 서브메뉴
        const subParents = mobileNav.querySelectorAll('.has_sub');
        subParents.forEach(li => {
            const trigger = li.querySelector(':scope > a');
            const sub = li.querySelector('.mob_sub');
            if (!trigger || !sub) return;
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const isOpening = !li.classList.contains('is_open');
                // 다른 열린 메뉴 닫기
                subParents.forEach(other => {
                    if (other !== li) {
                        other.classList.remove('is_open');
                        const otherSub = other.querySelector('.mob_sub');
                        if (otherSub) otherSub.style.maxHeight = '';
                    }
                });
                li.classList.toggle('is_open', isOpening);
                sub.style.maxHeight = isOpening ? sub.scrollHeight + 'px' : '';

                // 열릴 때: 모든 항목(정적+동적)에 transitionDelay를 JS로 직접 설정
                // CSS nth-child 규칙 의존 제거 → 동적 추가 항목도 항상 올바른 순서
                if (isOpening) {
                    // 1) 모든 항목 transition:none + opacity:0 강제 (시작 상태 확정)
                    Array.from(sub.children).forEach(item => {
                        item.style.transition = 'none';
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(12px)';
                    });
                    // 2) 강제 reflow: 브라우저가 위 상태를 계산
                    void sub.offsetHeight;
                    // 3) transition 복원 + delay를 위치(index) 기반으로 직접 지정
                    Array.from(sub.children).forEach((item, i) => {
                        item.style.transition = '';
                        item.style.opacity = '';
                        item.style.transform = '';
                        item.style.transitionDelay = (0.08 + i * 0.06) + 's';
                    });
                } else {
                    // 닫힐 때: delay 초기화
                    Array.from(sub.children).forEach(item => {
                        item.style.transitionDelay = '';
                    });
                }
            });
        });

        // 링크 클릭 시 메뉴 닫기 (서브메뉴 내 실제 링크)
        mobileNav.querySelectorAll('.mob_sub a, .mob_menu > li:not(.has_sub) > a').forEach(link => {
            link.addEventListener('click', () => {
                mobileNav.classList.remove('is_open');
                header.classList.remove('mob_open');
                document.body.style.overflow = '';
            });
        });

        // 리사이즈 시 모바일 메뉴 상태 정리 (모바일 범위 벗어날 때)
        const mobileBreakpoint = 1024;
        let wasMobile = window.innerWidth <= mobileBreakpoint;

        window.addEventListener('resize', () => {
            const isMobile = window.innerWidth <= mobileBreakpoint;

            // 데스크탑으로 전환 시 모바일 메뉴 상태 정리
            if (wasMobile && !isMobile && mobileNav.classList.contains('is_open')) {
                mobileNav.classList.remove('is_open');
                header.classList.remove('mob_open');
                document.body.style.overflow = '';
                btnMenu.setAttribute('aria-label', '메뉴 열기');
            }

            // 아코디언 서브메뉴: 브레이크포인트 전환 시 또는 열린 상태에서 리사이즈 시 높이 재계산
            subParents.forEach(li => {
                const sub = li.querySelector('.mob_sub');
                if (!sub) return;
                if (!isMobile) {
                    li.classList.remove('is_open');
                    sub.style.maxHeight = '';
                } else if (li.classList.contains('is_open')) {
                    sub.style.maxHeight = sub.scrollHeight + 'px';
                }
            });

            wasMobile = isMobile;
        });
    }

    // Footer 아코디언 (568px 이하)
    function initFooterAccordion() {
        const footerItems = document.querySelectorAll('#footer .right .floor1 > li');
        if (!footerItems.length) return;

        const BREAKPOINT = 568;
        let isAnimating = false;

        // 인라인 스타일 전체 초기화 헬퍼
        function resetAccordionItem(li) {
            li.classList.remove('is_open');
            const floor2 = li.querySelector('.floor2');
            if (floor2) {
                floor2.style.maxHeight = '';
                floor2.querySelectorAll(':scope > li').forEach(item => {
                    item.style.transitionDelay = '';
                });
            }
        }

        footerItems.forEach(li => {
            const strong = li.querySelector(':scope > strong');
            const floor2 = li.querySelector('.floor2');
            if (!strong || !floor2) return;

            strong.addEventListener('click', () => {
                if (window.innerWidth > BREAKPOINT) return;
                if (isAnimating) return;

                isAnimating = true;

                const isOpening = !li.classList.contains('is_open');

                // 다른 열린 메뉴 닫기
                footerItems.forEach(other => {
                    if (other !== li) resetAccordionItem(other);
                });

                const items = floor2.querySelectorAll(':scope > li');

                if (isOpening) {
                    // 클래스 먼저 토글하여 CSS padding-bottom 반영 후 scrollHeight 측정
                    li.classList.add('is_open');
                    floor2.style.maxHeight = floor2.scrollHeight + 'px';
                    // stagger 딜레이
                    items.forEach((item, i) => {
                        item.style.transitionDelay = (i * 0.06 + 0.15) + 's';
                    });
                } else {
                    // 닫힐 때 역순 stagger
                    items.forEach((item, i) => {
                        item.style.transitionDelay = ((items.length - 1 - i) * 0.03) + 's';
                    });
                    li.classList.remove('is_open');
                    floor2.style.maxHeight = '';
                }

                // 애니메이션 종료 후 잠금 해제
                setTimeout(() => { isAnimating = false; }, isOpening ? 850 : 700);
            });
        });

        // 리사이즈 시 아코디언 상태 전체 초기화
        window.addEventListener('resize', () => {
            if (window.innerWidth > BREAKPOINT) {
                footerItems.forEach(resetAccordionItem);
                isAnimating = false;
            }
        });
    }

    // 섹션 진입 시 헤더 클래스 제어
    const header = document.querySelector("#header");
    if (header) {
        const hideHeaderSections = document.querySelectorAll(".section_hideHeader");
        const whiteSections = document.querySelectorAll("section[class*='sec_white']");

        if (hideHeaderSections.length > 0 || whiteSections.length > 0) {
            window.addEventListener("scroll", () => {
                let shouldHide = false;
                let shouldWhite = false;

                hideHeaderSections.forEach((section) => {
                    // ScrollTrigger pin 시 pin-spacer가 실제 스크롤 영역을 담당
                    const pinSpacer = section.closest('.pin-spacer');
                    const checkEl = pinSpacer || section;
                    const rect = checkEl.getBoundingClientRect();
                    if (rect.top <= 0 && rect.bottom > 0) {
                        shouldHide = true;
                    }
                });

                whiteSections.forEach((section) => {
                    const rect = section.getBoundingClientRect();
                    if (rect.top <= 0 && rect.bottom > 0) {
                        shouldWhite = true;
                    }
                });

                header.classList.toggle("hide", shouldHide);
                header.classList.toggle("header_white", shouldWhite);
            });
        }
    }

    // Lenis smooth scroll
    // if (typeof Lenis !== "undefined") {
    //     const lenis = new Lenis({
    //         duration: 1.4,
    //         easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    //         // smoothWheel: true,
    //     });

    //     function raf(time) {
    //         lenis.raf(time);
    //         requestAnimationFrame(raf);
    //     }

    //     requestAnimationFrame(raf);
    // }
});

/**
 * SEO 및 전역 추적 스크립트 로더 (쿠키 동의 배너 통합)
 * 어드민 SEO 관리에서 입력한 메타 태그 및 추적 코드를 실시간 반영하며 거부 시 추적을 차단합니다.
 */
(function() {
    const CONSENT_KEY = 'narnia_cookie_consent';
    const apiBase = '/admin/api/seo';

    async function loadSEO() {
        // 1. 현재 페이지 키 파악
        let pageKey = 'home';
        const path = window.location.pathname;
        if (path.includes('about.html')) pageKey = 'about';
        else if (path.includes('team.html')) pageKey = 'team';
        else if (path.includes('news.html')) pageKey = 'news';
        else if (path.includes('aslanX.html')) pageKey = 'aslanx';
        else if (path.includes('caseStudies.html')) pageKey = 'case_studies';
        else if (path.includes('education.html')) pageKey = 'education';
        else if (path.includes('publication.html')) pageKey = 'publication';
        else if (path.includes('events.html')) pageKey = 'events';
        else if (path.includes('techBlog.html')) pageKey = 'tech_blog';
        else if (path.includes('contactUs.html')) pageKey = 'contact';
        else if (path.includes('deepGenerativeDesign.html')) pageKey = 'deep_gen';
        else if (path.includes('evaluation.html')) pageKey = 'evaluation';
        else if (path.includes('generation.html')) pageKey = 'generation';
        else if (path.includes('optimization.html')) pageKey = 'optimization';
        else if (path.includes('recommendation.html')) pageKey = 'recommendation';

        try {
            // 2. 데이터 한번에 가져오기
            const [globalRes, pageRes] = await Promise.all([
                fetch(`${apiBase}?page=global`),
                fetch(`${apiBase}?page=${pageKey}`)
            ]);
            
            const globalData = await globalRes.json();
            const pageData = await pageRes.json();

            // 3. 메타 태그 최적화 (항상 적용 - 동의 불필요)
            if (pageData) applyMetaTags(pageData);

            // 4a. head_script 내 <meta> 태그는 동의 없이 즉시 삽입 (네이버 소유확인 등)
            const allHeadScripts = [];
            if (globalData && globalData.head_script) allHeadScripts.push(globalData.head_script);
            if (pageData && pageData.head_script) allHeadScripts.push(pageData.head_script);
            allHeadScripts.forEach(s => injectMetaTags(s));

            // 4b. <script> 태그는 쿠키 동의 후 실행
            //     단, 국문(kor) 사이트는 쿠키 동의 배너 없이 즉시 실행
            const consent = localStorage.getItem(CONSENT_KEY);
            const scriptsToRun = allHeadScripts;
            const isKor = detectLang() === 'kor';

            if (isKor || consent === 'granted') {
                // 국문이거나 이미 동의함 -> 스크립트 즉시 실행
                scriptsToRun.forEach(s => injectScriptTags(s));
            } else if (!consent) {
                // 동의 정보 없음 -> 배너 띄우기 (영문/일문만)
                showConsentBanner(scriptsToRun);
            } else {
                // 거절함 -> 추적 스크립트 차단
                console.log('Narnia Labs: Tracking scripts are blocked by user consent policy.');
            }

        } catch (e) {
            console.error('SEO 로드 실패:', e);
        }
    }

    function showConsentBanner(scriptsToRun) {
        if (document.getElementById('narnia_cookie_banner')) return;

        const lang = detectLang();
        const bannerText = {
            kor: {
                title: "Cookie Privacy Policy",
                desc: "최적의 웹 경험을 제공하기 위해 쿠키를 사용합니다. 수락하시면 맞춤 콘텐츠와 통계 데이터를 통해 더욱 발전된 서비스를 만나실 수 있습니다.",
                decline: "거부",
                accept: "수락"
            },
            eng: {
                title: "Cookie Privacy Policy",
                desc: "We use cookies to ensure you get the best experience on our website. By accepting, you help us improve our services through tailored content and statistics.",
                decline: "Decline All",
                accept: "Accept All"
            },
            jp: {
                title: "Cookie Privacy Policy",
                desc: "最高のウェブ体験を提供するためにクッキーを使用しています。承諾いただくと、パーソナライズされたコンテンツや統計データを通じて、より進化したサービスをご利用いただけます。",
                decline: "すべて拒否",
                accept: "すべて承諾"
            }
        };
        const msg = bannerText[lang] || bannerText.kor;

        const bannerHtml = `
            <div id="narnia_cookie_banner" style="
                position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
                width: 90%; max-width: 780px; background: rgba(255, 255, 255, 0.75);
                backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255,255,255,0.4); border-radius: 20px;
                padding: 24px 34px; z-index: 99999; display: flex; align-items: center;
                justify-content: space-between; gap: 30px;
                box-shadow: 0 15px 45px rgba(0,0,0,0.12);
                font-family: 'Pretendard', sans-serif;
            ">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 700; color: #1a1a1a; letter-spacing: -0.01em;">${msg.title}</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.55; letter-spacing: -0.01em;">
                        ${msg.desc}
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-shrink: 0;">
                    <button id="narnia_cookie_decline" style="
                        padding: 11px 22px; border-radius: 10px; border: 1px solid #ddd;
                        background: #fff; color: #777; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
                    ">${msg.decline}</button>
                    <button id="narnia_cookie_accept" style="
                        padding: 11px 26px; border-radius: 10px; border: none;
                        background: #111; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
                    ">${msg.accept}</button>
                </div>
            </div>
            <style>
                #narnia_cookie_decline:hover { background: #f8f8f8; color: #333; }
                #narnia_cookie_accept:hover { background: #333; transform: translateY(-1px); }
                @media (max-width: 768px) {
                    #narnia_cookie_banner { flex-direction: column; text-align: center; padding: 24px; bottom: 16px; width: calc(100% - 32px); }
                    #narnia_cookie_banner div { width: 100%; justify-content: center; }
                }
            </style>
        `;

        document.body.insertAdjacentHTML('beforeend', bannerHtml);

        document.getElementById('narnia_cookie_accept').onclick = () => {
            localStorage.setItem(CONSENT_KEY, 'granted');
            document.getElementById('narnia_cookie_banner').remove();
            scriptsToRun.forEach(s => injectScriptTags(s)); // 즉시 실행
            // location.reload(); // 완벽한 적용을 위해 필요한 경우만 활성화
        };

        document.getElementById('narnia_cookie_decline').onclick = () => {
            localStorage.setItem(CONSENT_KEY, 'denied');
            document.getElementById('narnia_cookie_banner').remove();
        };
    }

    function applyMetaTags(data) {
        if (data.meta_title) document.title = data.meta_title;
        updateMeta('description', data.meta_description);
        updateMeta('keywords', data.meta_keywords);
        updateMeta('og:title', data.og_title || data.meta_title);
        updateMeta('og:description', data.og_description || data.meta_description);
        updateMeta('og:image', data.og_image);
        updateMeta('twitter:card', data.twitter_card);
        
        let canonical = document.querySelector('link[rel="canonical"]');
        if (data.canonical_url) {
            if (!canonical) {
                canonical = document.createElement('link');
                canonical.rel = 'canonical';
                document.head.appendChild(canonical);
            }
            canonical.href = data.canonical_url;
        }

        if (data.index_allow === false) {
            updateMeta('robots', 'noindex, nofollow');
        }
    }

    function updateMeta(name, content) {
        if (!content) return;
        let el = document.querySelector(`meta[name="${name}"], meta[property="${name}"]`);
        if (!el) {
            el = document.createElement('meta');
            if (name.includes(':')) el.setAttribute('property', name);
            else el.setAttribute('name', name);
            document.head.appendChild(el);
        }
        el.setAttribute('content', content);
    }

    // <meta> 태그만 즉시 삽입 (동의 불필요 - 네이버 소유확인 등)
    function injectMetaTags(html) {
        if (!html) return;
        const temp = document.createElement('div');
        temp.innerHTML = html.trim();
        Array.from(temp.childNodes).forEach(node => {
            if (node.nodeType === 1 && node.tagName === 'META') {
                // 이미 동일한 name/property가 있으면 덮어쓰기
                const name = node.getAttribute('name') || node.getAttribute('property');
                if (name) {
                    const existing = document.head.querySelector(`meta[name="${name}"], meta[property="${name}"]`);
                    if (existing) { existing.setAttribute('content', node.getAttribute('content')); return; }
                }
                document.head.appendChild(node.cloneNode(true));
            }
        });
    }

    // <script> 태그만 삽입 (쿠키 동의 후)
    function injectScriptTags(html) {
        if (!html) return;
        const temp = document.createElement('div');
        temp.innerHTML = html.trim();
        Array.from(temp.childNodes).forEach(node => {
            if (node.nodeType === 1 && node.tagName === 'SCRIPT') {
                const script = document.createElement('script');
                Array.from(node.attributes).forEach(attr => script.setAttribute(attr.name, attr.value));
                script.innerHTML = node.innerHTML;
                document.head.appendChild(script);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadSEO);
    } else {
        loadSEO();
    }
})();

(function(){
    function init(){
        const screen = document.querySelector('.loading_screen');
        if(!screen) return;
        if(typeof gsap === 'undefined') return;
        const ring = screen.querySelector('.ring_group');
        if(!ring) return;
        const dots = ring.querySelectorAll('.dot');
        if(!dots.length) return;
        const lead = ring.querySelector('.dot_lead');
        const rest = Array.from(dots).slice(1);
        const counterEl = screen.querySelector('.loading_counter .num');
        const barFill = screen.querySelector('.loading_counter .counter_bar i');
        ring.appendChild(lead);

        const visual = screen.querySelector('.loading_visual');
        let logoWrap = visual ? visual.querySelector('.loading_logo') : null;
        if(visual && !logoWrap){
            logoWrap = document.createElement('div');
            logoWrap.className = 'loading_logo';
            const logoImg = document.createElement('img');
            logoImg.src = './assets/img/common/logo_symbol_wh.svg';
            logoImg.alt = '';
            logoImg.setAttribute('aria-hidden', 'true');
            logoWrap.appendChild(logoImg);
            visual.appendChild(logoWrap);
        }
        const counterWrapEl = screen.querySelector('.loading_counter');

        const COUNT = dots.length;
        const RADIUS = 130;
        const xAt = i => RADIUS * Math.cos((i / COUNT) * Math.PI * 2 - Math.PI / 2);
        const yAt = i => RADIUS * Math.sin((i / COUNT) * Math.PI * 2 - Math.PI / 2);

        gsap.set(dots, { x: 0, y: 0, opacity: 0, scale: 1, transformOrigin: '50% 50%' });
        if(counterWrapEl) gsap.set(counterWrapEl, { opacity: 0, y: 12 });
        if(logoWrap) gsap.set(logoWrap, { opacity: 0 });

        const counter = { val: 0 };

        document.documentElement.classList.add('is_loading');

        const startTime = Date.now();
        const MIN_LOADING_TIME = 2500;
        const HOLD_CAP = 92;

        let entryDone = false;
        let countCapped = false;
        let pageReady = (document.readyState === 'complete');
        let exited = false;
        let ringSpin = null;
        let countTween = null;

        if(!pageReady){
            window.addEventListener('load', function onLoad(){
                pageReady = true;
                window.removeEventListener('load', onLoad);
                tryExit();
            });
        }

        function updateCounterDOM(){
            const v = Math.round(counter.val);
            if(counterEl) counterEl.textContent = String(v).padStart(3, '0');
            if(barFill) barFill.style.width = v + '%';
        }

        function tryExit(){
            if(exited) return;
            if(!entryDone || !countCapped || !pageReady) return;
            const elapsed = Date.now() - startTime;
            if(elapsed < MIN_LOADING_TIME){
                setTimeout(tryExit, MIN_LOADING_TIME - elapsed);
                return;
            }
            exited = true;
            playExit();
        }

        function startLoadingPhase(){
            ringSpin = gsap.to(ring, {
                rotation: '+=360',
                svgOrigin: '0 0',
                duration: 2.4,
                ease: 'none',
                repeat: -1
            });

            if(logoWrap){
                gsap.to(logoWrap, {
                    opacity: 1,
                    duration: 1.1,
                    ease: 'power1.inOut'
                });
            }

            countTween = gsap.to(counter, {
                val: HOLD_CAP,
                duration: 1.1,
                ease: 'power1.out',
                onUpdate: updateCounterDOM,
                onComplete(){
                    countCapped = true;
                    tryExit();
                }
            });
        }

        function playExit(){
            if(ringSpin) ringSpin.kill();
            if(countTween) countTween.kill();

            const exitTl = gsap.timeline({
                onComplete(){
                    screen.style.display = 'none';
                    document.documentElement.classList.remove('is_loading');
                }
            });

            exitTl.to(counter, {
                val: 100,
                duration: 0.3,
                ease: 'power2.out',
                onUpdate: updateCounterDOM
            });

            if(counterWrapEl){
                exitTl.to(counterWrapEl, {
                    opacity: 0,
                    y: -8,
                    duration: 0.3,
                    ease: 'power2.in'
                }, '+=0.2');
            } else {
                exitTl.to({}, { duration: 0.3 }, '+=0.2');
            }

            if(logoWrap){
                exitTl.to(logoWrap, {
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in'
                }, '<');
            }

            exitTl.to(dots, {
                x: 0,
                y: 0,
                duration: 0.45,
                ease: 'power3.inOut',
                stagger: { each: 0.014, from: 'random' }
            }, '<+0.05')
            .to(screen, {
                opacity: 0,
                duration: 0.4,
                ease: 'power2.in'
            }, '+=0.1');
        }

        const entryTl = gsap.timeline({
            onComplete(){
                entryDone = true;
                startLoadingPhase();
                tryExit();
            }
        });

        entryTl.to(dots, {
            opacity: 1,
            duration: 0.4,
            ease: 'power2.out'
        })
        .to(dots, {
            scale: 1.35,
            duration: 0.14,
            ease: 'power2.out'
        })
        .to(dots, {
            scale: 1,
            duration: 0.26,
            ease: 'power2.inOut'
        })
        .addLabel('spread', '+=0.1')
        .to(lead, {
            x: 0,
            y: -RADIUS,
            duration: 0.62,
            ease: 'expo.out'
        }, 'spread')
        .to(rest, {
            x: i => xAt(i + 1),
            y: i => yAt(i + 1),
            duration: 0.68,
            stagger: 0.025,
            ease: 'expo.out'
        }, 'spread+=0.06');

        if(counterWrapEl){
            entryTl.to(counterWrapEl, {
                opacity: 1,
                y: 0,
                duration: 0.35,
                ease: 'power2.out'
            }, 'spread+=0.25');
        }
    }
    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
