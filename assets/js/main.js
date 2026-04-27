document.addEventListener('DOMContentLoaded', function(){

    // ── 리사이즈 시 ScrollTrigger.refresh() 스크롤 순간이동 방지 ──
    // refresh 전 뷰포트에 가장 가까운 비-pin 앵커의 위치를 기억하고
    // refresh 후 drift만큼 보정하여 시각적 점프를 막는다.
    var _anchorEl = null, _anchorTop = 0, _isResizeRefresh = false;

    if(typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'){
        gsap.registerPlugin(ScrollTrigger);

        // resize 중인지 추적
        var _resizeFlag = false, _resizeFlagTimer = null;
        window.addEventListener('resize', function(){
            _resizeFlag = true;
            clearTimeout(_resizeFlagTimer);
            _resizeFlagTimer = setTimeout(function(){ _resizeFlag = false; }, 500);
        });

        ScrollTrigger.addEventListener('refreshInit', function(){
            _isResizeRefresh = _resizeFlag;
            if(!_isResizeRefresh) return;
            // pin이 활성 상태라면 GSAP가 pin 내부 progress 보존을 처리하므로 외부 앵커 드리프트 보정은 생략.
            // 적용하면 pin-spacer 밖 섹션(예: main_problemSolve, 60vh 스케일)의 독립적 위치 변화를
            // 기준으로 scrollY가 바뀌어 pin 진행도가 어긋나고, 큰 리사이즈에서는 pin을 조기 해제시킨다.
            // mslPinActive/asnPinActive는 동일 DOMContentLoaded 스코프에서 var 호이스팅으로 접근 가능.
            // 초기화 이전(undefined)이면 falsy로 취급되어 통상 경로를 타므로 추가 가드 불필요.
            if(mslPinActive || asnPinActive){
                _anchorEl = null;
                return;
            }
            var anchors = document.querySelectorAll('[id]:not(.pin-spacer)');
            var bestEl = null, bestDist = Infinity;
            for(var i = 0; i < anchors.length; i++){
                if(anchors[i].closest('.pin-spacer')) continue;
                var d = Math.abs(anchors[i].getBoundingClientRect().top);
                if(d < bestDist){ bestDist = d; bestEl = anchors[i]; }
            }
            _anchorEl = bestEl;
            _anchorTop = bestEl ? bestEl.getBoundingClientRect().top : 0;
        });

        ScrollTrigger.addEventListener('refresh', function(){
            if(!_isResizeRefresh || !_anchorEl){
                _anchorEl = null;
                _isResizeRefresh = false;
                return;
            }
            var drift = _anchorEl.getBoundingClientRect().top - _anchorTop;
            if(Math.abs(drift) > 2){
                window.scrollTo(0, window.pageYOffset + drift);
            }
            _anchorEl = null;
            _isResizeRefresh = false;
        });
    }

    // main_aslan pin-spacer 생성 후 호출할 지연 빌드 함수 (DOM 순서 보장)
    var _deferredMpsBuild = null;

    // #main_problemSolve ScrollTrigger pin + slide fadeIn 애니메이션
    var mpSection = document.getElementById('main_problemSolve');
    var mpsSlide = mpSection ? mpSection.querySelector('.mps_slide') : null;

    if(mpSection && mpsSlide && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'){
        gsap.registerPlugin(ScrollTrigger);

        // .mps_tit h2 fadeIn — h2 하단이 뷰포트 하단에 닿을 때
        var mpsTitH2 = mpSection.querySelector('.mps_tit h2');
        if(mpsTitH2){
            gsap.set(mpsTitH2, { opacity: 0, y: 30 });
            var h2Triggered = false;
            function checkH2(animate){
                if(h2Triggered) return;
                var rect = mpsTitH2.getBoundingClientRect();
                if(rect.bottom <= window.innerHeight){
                    h2Triggered = true;
                    if(animate){
                        gsap.to(mpsTitH2, { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' });
                    } else {
                        gsap.set(mpsTitH2, { opacity: 1, y: 0 });
                    }
                    window.removeEventListener('scroll', onScrollH2);
                }
            }
            function onScrollH2(){ checkH2(true); }
            window.addEventListener('scroll', onScrollH2);
            // reload로 이미 지나친 스크롤 위치에서 복원된 경우 즉시 최종 상태로 세팅
            checkH2(false);
        }

        var slideBox = mpSection.querySelector('.mps_slide_box');
        var slideItems, itemCount, itemOffsets, revealedFlags, gap;
        var mpsST = null;

        var MPS_MOBILE_BP = 864;
        function isMpsMobile(){ return window.innerWidth <= MPS_MOBILE_BP; }

        function clearMpsInlineStyles(){
            var items = mpsSlide.querySelectorAll('.slide_item');
            for(var i = 0; i < items.length; i++){
                items[i].style.height = '';
                gsap.set(items[i], { clearProps: 'opacity' });
            }
            gsap.set(mpsSlide, { clearProps: 'x,y,transform' });
        }

        // 슬라이드 아이템 및 offset 전체 재계산.
        // animate=true: y 변경을 짧은 gsap.to로 보간(리사이즈/breakpoint 교차 시 자연스러운 전환).
        // animate=false(기본): gsap.set으로 즉시 적용(초기 빌드 및 reload 복원).
        function refreshSlideState(animate){
            slideItems = mpsSlide.querySelectorAll('.slide_item');
            itemCount = slideItems.length;
            if(itemCount === 0) return;

            // .bot에 fraction 자동 생성
            ensureMpsFractions();

            gap = parseFloat(getComputedStyle(mpsSlide).gap) || 0;

            var slideBoxH = slideBox.offsetHeight;
            itemOffsets = [];

            if(isMpsMobile()){
                // 모바일(≤864): slide_item은 자연 높이(auto) 유지, 한 번에 한 아이템이 slide_box 중앙에 오도록 offset 계산.
                for(var i = 0; i < itemCount; i++){
                    slideItems[i].style.height = '';
                }
                var cumulative = 0;
                for(var i = 0; i < itemCount; i++){
                    var h = slideItems[i].offsetHeight;
                    itemOffsets.push((slideBoxH / 2) - (h / 2) - cumulative);
                    cumulative += h + gap;
                }
            } else {
                // 데스크톱: 자연 높이 측정 → 최대값으로 통일 후 slide_box 중앙에 오도록 offset 계산.
                for(var i = 0; i < itemCount; i++){
                    slideItems[i].style.height = 'auto';
                }
                var maxH = 0;
                for(var i = 0; i < itemCount; i++){
                    var h = slideItems[i].offsetHeight;
                    if(h > maxH) maxH = h;
                }
                for(var i = 0; i < itemCount; i++){
                    slideItems[i].style.height = maxH + 'px';
                }
                var cumulative = 0;
                for(var i = 0; i < itemCount; i++){
                    var h = slideItems[i].offsetHeight;
                    itemOffsets.push((slideBoxH / 2) - (h / 2) - cumulative);
                    cumulative += h + gap;
                }
            }

            // 현재 progress 기준으로 revealedFlags 동기화
            var progress = mpsST ? mpsST.progress : 0;
            revealedFlags = [];
            for(var i = 0; i < itemCount; i++){
                if(i === 0){
                    revealedFlags.push(true);
                } else {
                    var threshold = (i / (itemCount - 1)) - 0.12;
                    revealedFlags.push(progress >= threshold);
                }
                gsap.set(slideItems[i], { opacity: revealedFlags[i] ? 1 : 0 });
            }

            var targetY = itemOffsets[0] - (itemOffsets[0] - itemOffsets[itemCount - 1]) * progress;
            if(animate){
                gsap.to(mpsSlide, { y: targetY, duration: 0.35, ease: 'power2.out', overwrite: true });
            } else {
                gsap.set(mpsSlide, { y: targetY });
            }
        }

        // .bot에 fraction 동적 생성 (refreshSlideState 밖에서도 호출되도록 분리)
        function ensureMpsFractions(){
            var items = mpsSlide.querySelectorAll('.slide_item');
            var totalStr = String(items.length).padStart(2, '0');
            for(var i = 0; i < items.length; i++){
                var bot = items[i].querySelector('.bot');
                if(bot && !bot.querySelector('.fraction')){
                    var frac = document.createElement('span');
                    frac.className = 'fraction';
                    frac.innerHTML = '<em>' + String(i + 1).padStart(2, '0') + '</em> / ' + totalStr;
                    bot.appendChild(frac);
                }
            }
        }

        // ScrollTrigger 생성 / 재생성
        function buildScrollTrigger(){
            if(mpsST){ mpsST.kill(); mpsST = null; }
            clearMpsInlineStyles();
            refreshSlideState();
            if(itemCount === 0) return;

            var firstUpdateDone = false;

            mpsST = ScrollTrigger.create({
                trigger: mpSection,
                start: 'top top',
                end: '+=' + (itemCount * 60) + '%',
                pin: true,
                scrub: 0.5,
                pinSpacing: true,
                onUpdate: function(self){
                    var progress = self.progress;
                    var currentY = itemOffsets[0] - (itemOffsets[0] - itemOffsets[itemCount - 1]) * progress;

                    // 최초 update — reload 스크롤 복원 등 "이미 진행된 상태"를 애니메이션 없이 즉시 동기화
                    if(!firstUpdateDone){
                        firstUpdateDone = true;
                        gsap.set(mpsSlide, { y: currentY });
                        for(var i = 1; i < itemCount; i++){
                            var thInit = (i / (itemCount - 1)) - 0.12;
                            revealedFlags[i] = progress >= thInit;
                            gsap.set(slideItems[i], { opacity: revealedFlags[i] ? 1 : 0 });
                        }
                        return;
                    }

                    gsap.to(mpsSlide, { y: currentY, duration: 0.3, ease: 'power2.out', overwrite: true });

                    for(var i = 1; i < itemCount; i++){
                        var threshold = (i / (itemCount - 1)) - 0.12;

                        if(!revealedFlags[i] && progress >= threshold){
                            revealedFlags[i] = true;
                            gsap.to(slideItems[i], { opacity: 1, duration: 0.5, ease: 'power1.out' });
                        } else if(revealedFlags[i] && progress < threshold){
                            revealedFlags[i] = false;
                            gsap.to(slideItems[i], { opacity: 0, duration: 0.4, ease: 'power1.in' });
                        }
                    }
                }
            });
        }

        // 초기 빌드 — main_aslan ScrollTrigger(pin)가 먼저 생성되어야 하므로 지연
        _deferredMpsBuild = buildScrollTrigger;

        // 전역 ScrollTrigger.refresh() 직전에 itemOffsets를 동기화.
        // - GSAP의 resize 자동 refresh가 우리 debounce(150ms)보다 먼저 실행되면서
        //   옛 itemOffsets + 새 progress 조합으로 onUpdate가 호출되는 구간이 생긴다.
        // - refreshInit은 trigger들의 start/end 재계산과 onUpdate 직전에 동기 실행되므로
        //   이 시점에 heights/offsets를 최신화해 두면 해당 150ms 시각 어긋남이 사라진다.
        ScrollTrigger.addEventListener('refreshInit', function(){
            if(!mpsST || itemCount === 0) return;
            // 리사이즈/viewport 변동/breakpoint 교차로 refresh가 발생할 때,
            // 두 모드 간 slideBoxH 및 item 높이 차이로 y 타깃이 크게 바뀔 수 있으므로
            // gsap.to로 부드럽게 보간해 순간이동(점프) 없는 전환을 만든다.
            refreshSlideState(true);
        });

        // 리사이즈 대응 (window resize + ResizeObserver 공유 debounce)
        var refreshTimer = null;
        var isRefreshing = false;
        function scheduleRefresh(){
            if(isRefreshing) return;
            clearTimeout(refreshTimer);
            refreshTimer = setTimeout(function(){
                isRefreshing = true;
                // mpsST가 있으면 refresh가 refreshInit → refreshSlideState(true)를 자동 발동.
                // mpsST 없을 땐(초기 빌드 이전 ResizeObserver 등) 직접 호출.
                if(mpsST){
                    mpsST.refresh();
                } else {
                    refreshSlideState();
                }
                isRefreshing = false;
            }, 150);
        }

        window.addEventListener('resize', scheduleRefresh);

        if(typeof ResizeObserver !== 'undefined'){
            var slideRO = new ResizeObserver(scheduleRefresh);
            slideRO.observe(mpsSlide);
        }

        // DOM 동적 추가/삭제 감지 (MutationObserver)
        var moTimer = null;
        var slideMO = new MutationObserver(function(){
            clearTimeout(moTimer);
            moTimer = setTimeout(function(){
                buildScrollTrigger();
            }, 100);
        });
        slideMO.observe(mpsSlide, { childList: true });
    }

    // #main_activeProjects 슬라이드 (active 토글 + autoplay)
    var mpjSlideArea = document.querySelector('#main_activeProjects .mpj_slide_area');
    if(mpjSlideArea){
        var mpjCurrent = mpjSlideArea.querySelector('.mpj_fraction .current');
        var mpjTotal = mpjSlideArea.querySelector('.mpj_fraction .total');
        var mpjIndex = 0;
        var mpjAutoplayDelay = 5000;
        var mpjTimer = null;

        // 동적 추가 대응: 매번 최신 DOM을 조회
        function mpjGetItems(){ return mpjSlideArea.querySelectorAll('.mpj_tab_item'); }
        function mpjGetBullets(){ return mpjSlideArea.querySelectorAll('.mpj_bullet'); }
        function mpjGetDescs(){ return mpjSlideArea.querySelectorAll('.mpj_desc_area > .mpj_desc'); }
        function mpjGetCount(){ return mpjGetItems().length; }

        function mpjUpdateFraction(){
            var count = mpjGetCount();
            if(mpjTotal) mpjTotal.textContent = String(count).padStart(2, '0');
            if(mpjCurrent) mpjCurrent.textContent = String(mpjIndex + 1).padStart(2, '0');
        }

        function mpjGoTo(nextIndex, direction){
            var items = mpjGetItems();
            var descs = mpjGetDescs();
            var count = items.length;
            if(!count) return;
            // 외부 DOM 변경으로 mpjIndex가 범위를 벗어난 경우 clamp
            if(mpjIndex >= count) mpjIndex = count - 1;
            if(mpjIndex < 0) mpjIndex = 0;
            if(nextIndex === mpjIndex) return;
            if(nextIndex < 0 || nextIndex >= count) return;

            var dir = direction || 'next';
            var fadeOffset = dir === 'prev' ? '-80px' : '80px';

            if(items[mpjIndex]) items[mpjIndex].classList.remove('active');
            if(descs[mpjIndex]) descs[mpjIndex].classList.remove('active');

            // mpj_desc fade 리셋
            var desc = descs[nextIndex];
            if(desc){
                desc.style.transition = 'none';
                desc.style.opacity = '0';
                desc.style.transform = 'translateY(' + fadeOffset + ')';
                void desc.offsetWidth;
                desc.style.transition = '';
                desc.style.opacity = '';
                desc.style.transform = '';
            }

            items[nextIndex].classList.add('active');
            if(descs[nextIndex]) descs[nextIndex].classList.add('active');
            mpjIndex = nextIndex;

            mpjUpdateFraction();
            mpjUpdateBullets();
        }

        function mpjNext(){
            var count = mpjGetCount();
            if(count) mpjGoTo((mpjIndex + 1) % count, 'next');
        }

        function mpjStartAutoplay(){
            mpjStopAutoplay();
            mpjTimer = setInterval(mpjNext, mpjAutoplayDelay);
        }

        function mpjStopAutoplay(){
            if(mpjTimer){ clearInterval(mpjTimer); mpjTimer = null; }
        }

        function mpjUpdateBullets(){
            var bullets = mpjGetBullets();
            bullets.forEach(function(el, i){
                if(i === mpjIndex){
                    el.classList.add('active');
                    el.setAttribute('aria-selected', 'true');
                    el.setAttribute('tabindex', '0');
                } else {
                    el.classList.remove('active');
                    el.setAttribute('aria-selected', 'false');
                    el.setAttribute('tabindex', '-1');
                }
            });
        }

        // bullet 클릭/키보드 — 이벤트 위임으로 동적 추가 대응
        var mpjBulletArea = mpjSlideArea.querySelector('.mpj_bullet_scrollBox');
        if(mpjBulletArea){
            mpjBulletArea.addEventListener('click', function(e){
                var bullet = e.target.closest('.mpj_bullet');
                if(!bullet) return;
                e.preventDefault();
                var bullets = mpjGetBullets();
                var i = Array.prototype.indexOf.call(bullets, bullet);
                if(i === -1 || i === mpjIndex) return;
                var dir = i > mpjIndex ? 'next' : 'prev';
                mpjGoTo(i, dir);
                mpjStartAutoplay();
            });

            mpjBulletArea.addEventListener('keydown', function(e){
                var bullet = e.target.closest('.mpj_bullet');
                if(!bullet) return;
                var count = mpjGetCount();
                if(!count) return;
                var newIndex = mpjIndex;
                if(e.key === 'ArrowDown' || e.key === 'ArrowRight'){
                    e.preventDefault();
                    newIndex = (mpjIndex + 1) % count;
                    mpjGoTo(newIndex, 'next');
                } else if(e.key === 'ArrowUp' || e.key === 'ArrowLeft'){
                    e.preventDefault();
                    newIndex = (mpjIndex - 1 + count) % count;
                    mpjGoTo(newIndex, 'prev');
                }
                if(newIndex !== mpjIndex || e.key.startsWith('Arrow')){
                    var bullets = mpjGetBullets();
                    if(bullets[newIndex]) bullets[newIndex].focus();
                    mpjStartAutoplay();
                }
            });
        }

        // 초기 상태 동기화 — HTML의 active와 JS mpjIndex를 강제 맞춤
        // (HTML이 다른 인덱스로 렌더되거나 세 집합의 active가 어긋나는 경우 방지)
        function mpjSyncInitial(){
            var items = mpjGetItems();
            var descs = mpjGetDescs();
            var bullets = mpjGetBullets();
            var count = items.length;
            if(!count) return;

            var found = -1;
            items.forEach(function(el, i){ if(el.classList.contains('active')) found = i; });
            if(found < 0){
                descs.forEach(function(el, i){ if(el.classList.contains('active') && found < 0) found = i; });
            }
            if(found < 0) found = 0;
            mpjIndex = Math.max(0, Math.min(found, count - 1));

            items.forEach(function(el, i){ el.classList.toggle('active', i === mpjIndex); });
            descs.forEach(function(el, i){ el.classList.toggle('active', i === mpjIndex); });
            bullets.forEach(function(el, i){
                var is = i === mpjIndex;
                el.classList.toggle('active', is);
                el.setAttribute('aria-selected', is ? 'true' : 'false');
                el.setAttribute('tabindex', is ? '0' : '-1');
            });
        }

        // 초기 설정
        mpjSyncInitial();
        mpjUpdateFraction();
        mpjStartAutoplay();
    }

    // #main_request .link_item hover → active 토글
    var reqLinkItems = document.querySelectorAll('#main_request .link_area .link_item');
    if(reqLinkItems.length){
        reqLinkItems.forEach(function(item){
            item.addEventListener('mouseenter', function(){
                reqLinkItems.forEach(function(el){ el.classList.remove('active'); });
                item.classList.add('active');
            });
        });
    }

    // #main_clients .mcl_companyList 마키(marquee) 슬라이드 — 칼럼(세로 3행) 단위
    // 동적 아이템 추가 대응: column-first 배치 (3개씩 세로로 채운 뒤 다음 열)
    (function(){
        var container = document.querySelector('#main_clients .mcl_companyList');
        if(!container) return;

        var ROWS = 3; // 칼럼당 최대 행 수
        var speed = 60; // px/s
        var paused = false;
        var track = null;
        var isRebuilding = false;
        var isHovered = false;
        var hasFocusWithin = false;

        function setPlayback(isPaused){
            paused = !!isPaused;
            if(track) track.style.animationPlayState = paused ? 'paused' : 'running';
        }

        function syncPlayback(){
            isHovered = container.matches(':hover');
            hasFocusWithin = container.contains(document.activeElement);
            setPlayback(isDragging || isHovered || hasFocusWithin);
        }

        function getTrackSetWidth(){
            if(!track) return 0;
            return Math.abs(parseFloat(track.style.getPropertyValue('--mcl-set-width'))) || 0;
        }

        // 아이템이 보이는지 판별 (display:none 또는 is_hidden 클래스 제외)
        function isItemVisible(item){
            if(item.classList.contains('is_hidden')) return false;
            if(item.style.display === 'none') return false;
            return true;
        }

        // 아이템 수집: track 밖에 남은 원본 .list_item + track 안 원본(클론 제외)
        // 필터링으로 숨겨진 아이템은 별도 보관하고, 보이는 아이템만 반환
        function collectItems(){
            // track 밖에 직접 놓인 아이템 (동적 추가분)
            var loose = Array.prototype.slice.call(
                container.querySelectorAll(':scope > .list_item')
            );
            // track 안 원본 칼럼에서 아이템 추출
            var inTrack = [];
            if(track){
                var origCols = track.querySelectorAll('.mcl_col:not([data-clone])');
                origCols.forEach(function(col){
                    var children = Array.prototype.slice.call(col.querySelectorAll('.list_item'));
                    children.forEach(function(item){ inTrack.push(item); });
                });
                // 클론 제거
                var clones = track.querySelectorAll('[data-clone]');
                clones.forEach(function(cl){ cl.parentNode.removeChild(cl); });
                // 기존 원본 칼럼도 제거 (재구성)
                origCols.forEach(function(col){ col.parentNode.removeChild(col); });
            }
            var allItems = inTrack.concat(loose);

            // 숨겨진 아이템은 container에 보관 (track 밖), 보이는 아이템만 반환
            var visible = [];
            allItems.forEach(function(item){
                if(isItemVisible(item)){
                    visible.push(item);
                } else {
                    container.appendChild(item);
                }
            });
            return visible;
        }

        // 아이템을 칼럼 단위로 묶기 (column-first: 매 ROWS개씩 한 칼럼)
        // 순서: [0,1,2] → col0, [3,4,5] → col1, [6,7,8] → col2, ...
        // 마지막 칼럼은 ROWS 미만일 수 있음 (부분 칼럼)
        function buildColumns(items){
            var cols = [];
            var totalCols = Math.ceil(items.length / ROWS);
            for(var c = 0; c < totalCols; c++){
                var col = document.createElement('div');
                col.className = 'mcl_col';
                for(var r = 0; r < ROWS; r++){
                    var idx = r * totalCols + c;
                    if(idx < items.length) col.appendChild(items[idx]);
                }
                cols.push(col);
            }
            return cols;
        }

        function markColumns(cols, setIndex, isClone){
            cols.forEach(function(col, index){
                col.setAttribute('data-mcl-set-index', setIndex);
                if(index === 0){
                    col.setAttribute('data-mcl-set-start', '');
                }else{
                    col.removeAttribute('data-mcl-set-start');
                }

                if(isClone){
                    col.setAttribute('data-clone', '');
                    col.setAttribute('aria-hidden', 'true');
                }else{
                    col.removeAttribute('data-clone');
                    col.removeAttribute('aria-hidden');
                }
            });
        }

        function measureSetWidth(cols){
            if(!cols.length) return 0;
            var firstRect = cols[0].getBoundingClientRect();
            var lastRect = cols[cols.length - 1].getBoundingClientRect();
            var setWidth = lastRect.right - firstRect.left;
            return setWidth > 0 ? setWidth : 0;
        }

        function getTrackProgress(){
            var oneSetPx = getTrackSetWidth();
            if(!track || !oneSetPx) return 0;
            return Math.abs(normalizeOffset(getTrackTranslateX(), oneSetPx)) / oneSetPx;
        }

        function buildMarquee(){
            isRebuilding = true;
            var prevProgress = getTrackProgress();
            var items = collectItems();

            // track이 없으면 생성
            if(!track){
                track = document.createElement('div');
                track.className = 'mcl_track';
                container.insertBefore(track, container.firstChild);
            }

            track.style.animation = 'none';
            track.style.animationDelay = '';
            track.style.transform = '';
            void track.offsetHeight; // 강제 reflow — 브라우저가 'none' 상태를 확실히 적용

            // 보이는 아이템이 0개면 트랙 비우고 정지
            if(!items.length){
                track.innerHTML = '';
                track.style.removeProperty('--mcl-set-width');
                setTimeout(function(){ isRebuilding = false; }, 0);
                return;
            }

            // 칼럼 재구성
            var cols = buildColumns(items);
            markColumns(cols, 0, false);
            cols.forEach(function(col){ track.appendChild(col); });

            // 실제 렌더링 좌표 기준으로 한 세트 너비 측정
            var setWidth = measureSetWidth(cols);

            // 너비가 0이면 (모든 아이템 크기 0) 정지
            if(setWidth <= 0){ setTimeout(function(){ isRebuilding = false; }, 0); return; }

            // 화면 3배 이상 채우도록 복제
            var viewW = window.innerWidth || 1920;
            var sets = Math.max(3, Math.ceil((viewW * 3) / setWidth) + 1);

            for(var i = 1; i < sets; i++){
                cols.forEach(function(col, index){
                    var clone = col.cloneNode(true);
                    clone.setAttribute('data-mcl-set-index', i);
                    if(index === 0){
                        clone.setAttribute('data-mcl-set-start', '');
                    }else{
                        clone.removeAttribute('data-mcl-set-start');
                    }
                    clone.setAttribute('data-clone', '');
                    clone.setAttribute('aria-hidden', 'true');
                    track.appendChild(clone);
                });
            }

            // 다음 세트 시작점까지 실제 좌표를 다시 측정해 루프 오차 제거
            var oneSetPx = setWidth;
            var nextSetStart = track.querySelector('.mcl_col[data-clone][data-mcl-set-start]');
            if(nextSetStart){
                var currentSetStartRect = cols[0].getBoundingClientRect();
                var nextSetStartRect = nextSetStart.getBoundingClientRect();
                var measuredLoopPx = nextSetStartRect.left - currentSetStartRect.left;
                if(measuredLoopPx > 0){
                    oneSetPx = measuredLoopPx;
                }
            }

            var duration = oneSetPx / speed;
            var delayOffset = duration * prevProgress;

            track.style.setProperty('--mcl-set-width', '-' + oneSetPx + 'px');
            void track.offsetWidth;
            track.style.animation = 'mclMarquee ' + duration + 's linear infinite';
            track.style.animationDelay = delayOffset ? '-' + delayOffset + 's' : '';
            track.style.transform = '';
            syncPlayback();
            // isRebuilding 해제를 비동기로 지연 — MutationObserver 콜백(microtask)이
            // buildMarquee의 DOM 변경을 감지해도 isRebuilding 가드에 걸리도록
            setTimeout(function(){ isRebuilding = false; }, 0);
        }

        buildMarquee();

        // 외부에서 필터 적용 후 리빌드 호출용
        container.mclRebuild = buildMarquee;

        // hover 일시정지
        container.addEventListener('mouseenter', function(){
            isHovered = true;
            syncPlayback();
        });
        container.addEventListener('mouseleave', function(){
            isHovered = false;
            syncPlayback();
        });

        // 포커스 접근성
        container.addEventListener('focusin', function(){
            hasFocusWithin = true;
            syncPlayback();
        });
        container.addEventListener('focusout', function(e){
            if(!container.contains(e.relatedTarget)){
                hasFocusWithin = false;
            }
            syncPlayback();
        });

        // 마우스 드래그로 슬라이드 이동
        var isDragging = false;
        var dragStartX = 0;
        var dragBaseOffset = 0;

        function getTrackTranslateX(){
            if(!track) return 0;
            var style = window.getComputedStyle(track);
            var matrix = style.transform || style.webkitTransform;
            if(!matrix || matrix === 'none') return 0;
            var m = matrix.match(/matrix(3d)?\((.+)\)/);
            if(m){
                var values = m[2].split(',');
                var txIndex = m[1] ? 12 : 4;
                return parseFloat(values[txIndex]) || 0;
            }
            return 0;
        }

        function normalizeOffset(offset, width){
            var oneSetPx = width || getTrackSetWidth() || 1;
            // offset을 0 ~ -oneSetPx 범위로 정규화 (끊김 없는 루프)
            var normalized = offset % oneSetPx;
            if(normalized > 0) normalized -= oneSetPx;
            return normalized;
        }

        container.addEventListener('mousedown', function(e){
            if(e.button !== 0 || !track) return;
            isDragging = true;
            dragStartX = e.clientX;
            dragBaseOffset = getTrackTranslateX();
            container.classList.add('is_dragging');
            // 애니메이션 멈추고 현재 위치 고정
            track.style.animation = 'none';
            track.style.animationDelay = '';
            track.style.transform = 'translateX(' + dragBaseOffset + 'px)';
            e.preventDefault();
        });

        container.addEventListener('dragstart', function(e){
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e){
            if(!isDragging) return;
            var dx = e.clientX - dragStartX;
            var newOffset = normalizeOffset(dragBaseOffset + dx);
            track.style.transform = 'translateX(' + newOffset + 'px)';
        });

        function stopDrag(){
            if(!isDragging) return;
            isDragging = false;
            container.classList.remove('is_dragging');
            if(!track) return;

            // 현재 드래그 종료 위치에서 애니메이션 재개
            var currentX = getTrackTranslateX();
            var oneSetPx = getTrackSetWidth() || 1;
            var duration = oneSetPx / speed;

            // 현재 위치를 0~-oneSetPx 범위로 정규화
            var normalized = normalizeOffset(currentX, oneSetPx);

            // 남은 비율로 offset 계산 → animation-delay 음수 트릭
            var progress = Math.abs(normalized) / oneSetPx;
            var delayOffset = duration * progress;

            track.style.transform = '';
            track.style.animation = 'mclMarquee ' + duration + 's linear infinite';
            track.style.animationDelay = '-' + delayOffset + 's';
            syncPlayback();
        }

        document.addEventListener('mouseup', function(){
            if(!isDragging) return;
            stopDrag();
        });

        window.addEventListener('blur', function(){
            if(isDragging) stopDrag();
        });

        // 리사이즈 대응 (debounce 200ms)
        var resizeTimer = null;
        window.addEventListener('resize', function(){
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(buildMarquee, 200);
        });

        // DOM 변경 감지 → 동적 아이템 추가/제거/필터 시 자동 리빌드 (MutationObserver)
        if(typeof MutationObserver !== 'undefined'){
            var mutTimer = null;
            new MutationObserver(function(mutations){
                if(isRebuilding) return;
                var changed = mutations.some(function(m){
                    // childList: 아이템 추가/제거
                    if(m.type === 'childList'){
                        var nodes = [].slice.call(m.addedNodes).concat([].slice.call(m.removedNodes));
                        return nodes.some(function(n){
                            return n.nodeType === 1 && !n.hasAttribute('data-clone') && n.classList && n.classList.contains('list_item');
                        });
                    }
                    // attributes: 클래스(is_hidden 등) 또는 style(display) 변경으로 필터링
                    if(m.type === 'attributes'){
                        var target = m.target;
                        if(target.classList && target.classList.contains('list_item') && !target.hasAttribute('data-clone')){
                            return true;
                        }
                    }
                    return false;
                });
                if(changed){
                    clearTimeout(mutTimer);
                    mutTimer = setTimeout(buildMarquee, 100);
                }
            }).observe(container, { childList: true, subtree: true, attributes: true, attributeFilter: ['class', 'style'] });
        }
    })();

    /* msl_visual 주석처리
    // #main_aslan .folder_deco — clip-path로 남는 sharp 배경을 blur clone으로 덮어 유리 질감을 유지
    var folderDeco = document.querySelector('#main_aslan .msl_v_left .bot .folder_deco');
    var folderBlur = folderDeco ? folderDeco.querySelector('.folder_blur') : null;
    var aslanBot = document.querySelector('#main_aslan .msl_v_left .bot');

    if(folderDeco && folderBlur && aslanBot){
        var blurPad = 48;
        var beforeStyle = window.getComputedStyle(aslanBot, '::before');
        var beforeBgImage = beforeStyle.backgroundImage;
        var beforeBgMatch = /url\(["']?(.*?)["']?\)/.exec(beforeBgImage);
        var bgAsset = new Image();

        folderBlur.innerHTML = '';

        // clip-path + backdrop-filter 조합 시 ::before 배경의 sharp 잔상이 남아 항상 clone 방식 사용
        folderDeco.classList.add('is-filter-fallback');

        var blurClone = document.createElement('div');
        blurClone.className = 'folder_blur_clone';
        if(beforeBgImage && beforeBgImage !== 'none'){
            blurClone.style.backgroundImage = beforeBgImage;
        }
        folderBlur.appendChild(blurClone);

        function updateFolderBlurClone(){
            var folderRect = folderDeco.getBoundingClientRect();
            var botRect = aslanBot.getBoundingClientRect();
            var renderedBgHeight;
            var renderedBgWidth;

            blurClone.style.left = (botRect.left - folderRect.left - blurPad) + 'px';
            blurClone.style.top = (botRect.top - folderRect.top - blurPad) + 'px';
            blurClone.style.width = (botRect.width + (blurPad * 2)) + 'px';
            blurClone.style.height = (botRect.height + (blurPad * 2)) + 'px';

            if(bgAsset.naturalWidth && bgAsset.naturalHeight){
                renderedBgHeight = botRect.height * 1.1;
                renderedBgWidth = bgAsset.naturalWidth * (renderedBgHeight / bgAsset.naturalHeight);
                blurClone.style.backgroundSize = renderedBgWidth + 'px ' + renderedBgHeight + 'px';
                blurClone.style.backgroundPosition = (30 + blurPad) + 'px ' + ((botRect.height - renderedBgHeight) + blurPad) + 'px';
            }
        }

        requestAnimationFrame(updateFolderBlurClone);

        if(beforeBgMatch && beforeBgMatch[1]){
            bgAsset.addEventListener('load', updateFolderBlurClone);
            bgAsset.src = beforeBgMatch[1];
        }

        window.addEventListener('load', updateFolderBlurClone);

        var folderResizeTimer = null;
        window.addEventListener('resize', function(){
            clearTimeout(folderResizeTimer);
            folderResizeTimer = setTimeout(updateFolderBlurClone, 100);
        });

        if(typeof ResizeObserver !== 'undefined'){
            var folderBlurObserver = new ResizeObserver(updateFolderBlurClone);
            folderBlurObserver.observe(aslanBot);
            folderBlurObserver.observe(folderDeco);
        }
    }
    msl_visual 주석처리 끝 */

    // #main_visual .mv_bot .mv_tit h2 글자별 blur reveal → blur_txtDeco fade_in
    var mvTitH2 = document.querySelector('#main_visual .mv_bot .mv_tit h2');
    if(mvTitH2){
        var nodes = Array.from(mvTitH2.childNodes);
        mvTitH2.innerHTML = '';

        nodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                mvTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        mvTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        mvTitH2.appendChild(span);
                    }
                }
            }
        });

        var chars = mvTitH2.querySelectorAll('.char');
        var decoEl = document.querySelector('#main_visual .mv_content .blur_txtDeco');

        function startMvH2Anim(){
            chars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        // blur_txtDeco는 로딩 시 즉시 실행
        if(decoEl) decoEl.classList.add('fade_in');

        // h2 하단이 viewport에 들어오면 애니메이션 시작
        var mvH2Observer = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if(entry.isIntersecting){
                    startMvH2Anim();
                    mvH2Observer.disconnect();
                }
            });
        }, { threshold: 1 });
        mvH2Observer.observe(mvTitH2);
    }

    // #aslan_Transform pin-spacer 생성 후 호출할 지연 빌드 함수 (DOM 순서 보장)
    var _deferredAsnBuild = null;

    // #newDesign 초기 상태 세팅 (ScrollTrigger는 DOM 순서 보장을 위해 하단에서 생성)
    var ndSection = document.querySelector('#newDesign');
    var ndContainer = ndSection ? ndSection.querySelector('.pd_container') : null;
    var ndPos1 = ndSection ? ndSection.querySelector('.nd_chart_area.pos1') : null;
    var ndPos2 = ndSection ? ndSection.querySelector('.nd_chart_area.pos2') : null;
    var ndPos3 = ndSection ? ndSection.querySelector('.nd_chart_area.pos3') : null;
    var _deferredNdBuild = null;

    if(ndSection && ndContainer && ndPos1 && ndPos2 && ndPos3 && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'){

        var ndCards = [ndPos1, ndPos2, ndPos3];

        // 내부 차트 서브 요소 초기 상태 세팅
        ndCards.forEach(function(area){
            var polyline = area.querySelector('.nca_chart .chart_line polyline');
            var chartLabel = area.querySelector('.nca_chart .chart_label');
            var counterStrong = area.querySelector('.nca_counter strong');

            if(polyline){
                var len = polyline.getTotalLength();
                gsap.set(polyline, { strokeDasharray: len, strokeDashoffset: len });
            }
            if(chartLabel) gsap.set(chartLabel, { opacity: 0 });
            if(counterStrong) gsap.set(counterStrong, { opacity: 0 });

            if(area.classList.contains('pos2')){
                var barSmall = area.querySelector('.chart_bar_small');
                var barLarge = area.querySelector('.chart_bar_large');
                var chartValue = area.querySelector('.chart_value');
                var chartBrand = area.querySelector('.chart_brand');
                if(barSmall) gsap.set(barSmall, { width: 0 });
                if(barLarge) gsap.set(barLarge, { width: 0 });
                if(chartValue) gsap.set(chartValue, { opacity: 0 });
                if(chartBrand) gsap.set(chartBrand, { opacity: 0 });
            }
        });

        // 카드별 내부 차트 애니메이션 재생 함수
        function playNdChartAnim(area){
            var polyline = area.querySelector('.nca_chart .chart_line polyline');
            var chartLabel = area.querySelector('.nca_chart .chart_label');
            var counterStrong = area.querySelector('.nca_counter strong');
            var counterTarget = counterStrong ? parseFloat(counterStrong.textContent) : 0;
            var lineDur = 2.4;
            var lineEase = 'sine.inOut';
            var labelDelay = lineDur * 0.55;

            if(polyline){
                gsap.to(polyline, { strokeDashoffset: 0, duration: lineDur, ease: lineEase });
            }
            if(chartLabel){
                gsap.to(chartLabel, { opacity: 1, duration: 0.4, delay: polyline ? labelDelay : 0, ease: 'power1.out' });
            }
            if(counterStrong && counterTarget){
                gsap.to(counterStrong, { opacity: 1, duration: 0.3, ease: 'power1.out' });
                var cObj = { val: 0 };
                gsap.to(cObj, {
                    val: counterTarget,
                    duration: labelDelay,
                    ease: 'power2.out',
                    onUpdate: function(){ counterStrong.textContent = Math.round(cObj.val); },
                    onComplete: function(){ counterStrong.textContent = counterTarget; }
                });
            }

            // pos3 gauge
            if(area.classList.contains('pos3') && window.__pos3GaugeTl){
                window.__pos3GaugeTl.play();
            }

            // pos2 bar chart
            if(area.classList.contains('pos2')){
                var barSmall = area.querySelector('.chart_bar_small');
                var barLarge = area.querySelector('.chart_bar_large');
                var chartValue = area.querySelector('.chart_value');
                var chartBrand = area.querySelector('.chart_brand');
                var barEase = 'power2.out';
                var barDur = 1.6;

                if(barSmall) gsap.to(barSmall, { width: 32, duration: barDur, ease: barEase });
                if(barLarge) gsap.to(barLarge, { width: 'calc(100% - 25px)', duration: barDur, ease: barEase });
                if(chartBrand) gsap.to(chartBrand, { opacity: 1, duration: 0.4, delay: barDur, ease: 'power1.out' });
                if(chartValue) gsap.to(chartValue, { opacity: 1, duration: 0.5, delay: barDur, ease: 'power1.out' });
            }
        }

        // 640 이하에서는 x축 중앙 정렬 필요 (CSS transform이 GSAP 인라인 transform에 의해 덮여쓰이므로 JS로 xPercent 동기화)
        function ndComputeXP(){
            return window.matchMedia('(max-width: 640px)').matches ? -50 : 0;
        }

        // 초기 위치: pos1 = y축 중앙(opacity 1), pos2·pos3 = 화면 아래(opacity 0.3)
        gsap.set(ndPos1, { xPercent: ndComputeXP(), top: '50%', bottom: 'auto', yPercent: -50, y: 0, opacity: 1, '--nd-dark': 0 });
        gsap.set(ndPos2, { xPercent: ndComputeXP(), top: '80%', bottom: 'auto', yPercent: 0, y: 0, opacity: 0.3, '--nd-dark': 1 });
        gsap.set(ndPos3, { xPercent: ndComputeXP(), top: '80%', bottom: 'auto', yPercent: 0, y: 0, opacity: 0.3, '--nd-dark': 1 });

        // 리사이즈 시 xPercent 재적용 (타임라인 tween은 xPercent를 건드리지 않으므로 set 만으로 유지됨)
        window.addEventListener('resize', function(){
            gsap.set([ndPos1, ndPos2, ndPos3], { xPercent: ndComputeXP() });
        });

        // ScrollTrigger 생성은 DOM 순서에 맞게 main_proposal 이후 호출
        _deferredNdBuild = function(){
            // 타임라인 구간 설계 (snap 지점에 dwell을 둬서 카드가 중앙에서 정지 → 차트 애니메이션 재생 시간 확보)
            // phase1 [0,   0.5] : pos1 위로 퇴장 + pos2 중앙 진입  → 0.5 시점이 pos2 snap
            // dwell1 [0.5, 0.9] : pos2 중앙 정지 (차트 애니메이션 표시 구간)
            // phase2 [0.9, 1.4] : pos2 위로 퇴장 + pos3 중앙 진입  → 1.4 시점이 pos3 snap
            // dwell2 [1.4, 1.7] : pos3 중앙 정지 (차트 애니메이션 표시 구간 + 다음 섹션 연결 여유)
            var ndPhaseDur = 0.5;
            var ndDwellDur = 0.4;
            var ndTailDur  = 0.3;

            var ndPos1Snap = 0;                                 // pos1은 섹션 진입 시 이미 중앙
            var ndPos2Snap = ndPhaseDur;                        // 0.5
            var ndPos3Snap = ndPhaseDur + ndDwellDur + ndPhaseDur; // 1.4
            var ndTotalDur = ndPos3Snap + ndTailDur;            // 1.7

            var ndPos1Played = false;
            var ndPos2Played = false;
            var ndPos3Played = false;

            var ndScrollTl = gsap.timeline({
                scrollTrigger: {
                    trigger: ndSection,
                    start: 'top top',
                    end: '+=' + Math.round(ndTotalDur * 100) + '%',
                    pin: true,
                    scrub: 1.5,
                    invalidateOnRefresh: true,
                    onRefresh: function(){
                        // 리사이즈/리로드 후 progress가 각 snap 이전이면 플래그 리셋
                        var tlProg = ndScrollTl.progress();
                        var p2 = ndPos2Snap / ndTotalDur;
                        var p3 = ndPos3Snap / ndTotalDur;
                        if(tlProg < 0.001){ ndPos1Played = false; }
                        if(tlProg < p2){    ndPos2Played = false; }
                        if(tlProg < p3){    ndPos3Played = false; }
                    }
                }
            });

            // Phase 1: pos1 위로 퇴장(opacity 0.3 + 어둠), pos2 중앙으로 진입(opacity 1 + 밝음)
            ndScrollTl.to(ndPos1, { top: '10%', yPercent: 0, opacity: 0.3, '--nd-dark': 1, duration: ndPhaseDur, ease: 'power2.inOut' }, 0)
                      .to(ndPos2, { top: '50%', yPercent: -50, opacity: 1, '--nd-dark': 0, duration: ndPhaseDur, ease: 'power2.inOut' }, 0);
            // Phase 2: dwell1 경과 후 pos2 위로 퇴장, pos3 중앙으로 진입
            ndScrollTl.to(ndPos2, { top: '10%', yPercent: 0, opacity: 0.3, '--nd-dark': 1, duration: ndPhaseDur, ease: 'power2.inOut' }, ndPos2Snap + ndDwellDur)
                      .to(ndPos3, { top: '50%', yPercent: -50, opacity: 1, '--nd-dark': 0, duration: ndPhaseDur, ease: 'power2.inOut' }, ndPos2Snap + ndDwellDur);
            // pos3 snap 이후 tail dwell (타임라인 총 길이 확장)
            ndScrollTl.to({}, { duration: ndTailDur }, ndPos3Snap);

            // 각 snap 지점에 도달하는 정확한 타이밍에 차트 애니메이션 트리거
            // — timeline.call() 은 scrub catch-up 중에도 플레이헤드가 해당 위치를 지날 때 확실히 실행됨
            ndScrollTl.call(function(){
                if(!ndPos1Played){ ndPos1Played = true; playNdChartAnim(ndPos1); }
            }, null, ndPos1Snap + 0.001);
            ndScrollTl.call(function(){
                if(!ndPos2Played){ ndPos2Played = true; playNdChartAnim(ndPos2); }
            }, null, ndPos2Snap);
            ndScrollTl.call(function(){
                if(!ndPos3Played){ ndPos3Played = true; playNdChartAnim(ndPos3); }
            }, null, ndPos3Snap);
        };
    }

    // pos3: gauge chart 동적 생성
    var pos3Chart = document.querySelector('#newDesign .nd_chart_area.pos3 .nca_chart');
    if(pos3Chart && !pos3Chart.querySelector('.gauge_wrap')){
        var gaugeValue = 99;
        var gaugeValueRatio = Math.max(0, Math.min(gaugeValue / 100, 1));
        var totalSegs = 44;
        var tailSegs = 5;
        var svgWidth = 320;
        var svgHeight = 102;
        var gaugeCx = 160;
        var gaugeCy = 102;
        var gaugeStartAngle = 180;
        var gaugeEndAngle = 360;
        var outerRadius = 94;
        var innerRadius = 68;
        var coreRadius = 38;
        var gaugeSegments = '';

        function gaugePolarPoint(cx, cy, radius, angleDeg){
            var rad = angleDeg * Math.PI / 180;
            return {
                x: cx + Math.cos(rad) * radius,
                y: cy - Math.sin(rad) * radius
            };
        }

        function gaugeMixNumber(start, end, ratio){
            return start + (end - start) * ratio;
        }

        function gaugeMixColor(startColor, endColor, ratio){
            return 'rgba(' +
                Math.round(gaugeMixNumber(startColor[0], endColor[0], ratio)) + ', ' +
                Math.round(gaugeMixNumber(startColor[1], endColor[1], ratio)) + ', ' +
                Math.round(gaugeMixNumber(startColor[2], endColor[2], ratio)) + ', ' +
                gaugeMixNumber(startColor[3], endColor[3], ratio).toFixed(3) +
            ')';
        }

        function gaugeGradientColor(ratio){
            var clampedRatio = Math.max(0, Math.min(1, ratio));
            var purpleStart = [126, 88, 255, 0.96];
            var purpleMid = [154, 121, 255, 0.98];
            var glowEnd = [255, 255, 255, 0.98];

            if(clampedRatio < 0.72){
                return gaugeMixColor(purpleStart, purpleMid, clampedRatio / 0.72);
            }

            return gaugeMixColor(purpleMid, glowEnd, (clampedRatio - 0.72) / 0.28);
        }

        for(var gi = 0; gi < totalSegs; gi++){
            var angle = 180 - (180 / (totalSegs - 1)) * gi;
            var outerPoint = gaugePolarPoint(gaugeCx, gaugeCy, outerRadius, angle);
            var innerPoint = gaugePolarPoint(gaugeCx, gaugeCy, innerRadius, angle);
            var isTail = gi >= totalSegs - tailSegs;
            var tailIndex = gi - (totalSegs - tailSegs);
            var tailOpacity = isTail ? (0.35 + (tailIndex / Math.max(tailSegs - 1, 1)) * 0.55).toFixed(2) : '';
            var segRatio = totalSegs > 1 ? gi / (totalSegs - 1) : 0;
            var segActiveStroke = gaugeGradientColor(segRatio);
            var segStyle = '--gauge-active-stroke:' + segActiveStroke + ';';

            if(isTail){
                segStyle += '--tail-opacity:' + tailOpacity + ';';
            }

            gaugeSegments += '<line class="gauge_seg' + (isTail ? ' is-tail' : '') + '"' +
                ' style="' + segStyle + '"' +
                ' x1="' + innerPoint.x.toFixed(2) +
                '" y1="' + innerPoint.y.toFixed(2) +
                '" x2="' + outerPoint.x.toFixed(2) +
                '" y2="' + outerPoint.y.toFixed(2) + '"></line>';
        }

        var pointerLen = outerRadius.toFixed(2);

        var coreLeft = (gaugeCx - coreRadius).toFixed(2);
        var coreRight = (gaugeCx + coreRadius).toFixed(2);
        var gaugeHTML = '<div class="gauge_wrap">';
        gaugeHTML += '<div class="gauge_arc" aria-hidden="true">';
        gaugeHTML += '<svg class="gauge_svg" viewBox="0 0 ' + svgWidth + ' ' + svgHeight + '" preserveAspectRatio="xMidYMax meet">';
        gaugeHTML += '<g class="gauge_segments">' + gaugeSegments + '</g>';
        gaugeHTML += '<path class="gauge_core" d="M ' + coreLeft + ' ' + gaugeCy.toFixed(2) + ' A ' + coreRadius + ' ' + coreRadius + ' 0 0 1 ' + coreRight + ' ' + gaugeCy.toFixed(2) + ' L ' + coreLeft + ' ' + gaugeCy.toFixed(2) + ' Z"></path>';
        gaugeHTML += '<g class="gauge_pointer_anchor" transform="translate(' + gaugeCx + ' ' + gaugeCy + ')">';
        gaugeHTML += '<g class="gauge_pointer_group" transform="rotate(' + gaugeStartAngle + ')">';
        gaugeHTML += '<line class="gauge_pointer_glow" x1="0" y1="0" x2="' + pointerLen + '" y2="0"></line>';
        gaugeHTML += '<line class="gauge_pointer" x1="0" y1="0" x2="' + pointerLen + '" y2="0"></line>';
        gaugeHTML += '</g>';
        gaugeHTML += '</g>';
        gaugeHTML += '<circle class="gauge_dot" cx="' + gaugeCx + '" cy="' + gaugeCy + '" r="3.2"></circle>';
        gaugeHTML += '</svg>';
        gaugeHTML += '</div>';
        gaugeHTML += '<div class="gauge_info">';
        gaugeHTML += '<span class="gauge_info_label">ACCURACY</span>';
        gaugeHTML += '<span class="gauge_info_value">' + gaugeValue + '%</span>';
        gaugeHTML += '</div>';
        gaugeHTML += '</div>';
        pos3Chart.insertAdjacentHTML('beforeend', gaugeHTML);

        // gauge 애니메이션: 세그먼트 좌→우 채움 + 포인터 회전
        // (ndChartObserver fade-up 완료 후 발동 — pos3 콜백에서 window.__pos3GaugeTl.play() 호출)
        if(typeof gsap !== 'undefined'){
            var gaugeSegs = pos3Chart.querySelectorAll('.gauge_seg');
            var pointerGroup = pos3Chart.querySelector('.gauge_pointer_group');
            var fillTotal = totalSegs - 2; // 마지막 세그먼트 1개는 회색 유지
            var gaugeProxy = { angle: gaugeStartAngle };
            var targetAngle = gaugeStartAngle + ((gaugeEndAngle - gaugeStartAngle) * gaugeValueRatio);

            function updateGaugeVisual(){
                var angleRange = gaugeEndAngle - gaugeStartAngle;
                var angleProgress = angleRange > 0
                    ? Math.max(0, Math.min(1, (gaugeProxy.angle - gaugeStartAngle) / angleRange))
                    : 0;
                var activeIndex = angleProgress > 0 ? Math.round(angleProgress * fillTotal) : -1;

                pointerGroup.setAttribute('transform', 'rotate(' + gaugeProxy.angle.toFixed(2) + ')');

                for(var si = 0; si < totalSegs; si++){
                    if(si <= activeIndex){
                        gaugeSegs[si].classList.add('is-active');
                    } else {
                        gaugeSegs[si].classList.remove('is-active');
                    }
                }
            }

            // 초기 상태: 포인터 왼쪽(0), 모든 세그먼트 비활성(회색)
            updateGaugeVisual();

            var gaugeTl = gsap.timeline({ paused: true });
            gaugeTl.to(gaugeProxy, {
                angle: targetAngle,
                duration: 1.6,
                ease: 'power2.out',
                onUpdate: updateGaugeVisual,
                onComplete: updateGaugeVisual
            });

            // ndChartObserver의 pos3 콜백에서 사용
            window.__pos3GaugeTl = gaugeTl;
        }
    }

    // nca_chart label 위치를 polyline 마지막 구간에 자동 배치
    var chartEls = document.querySelectorAll('.nca_chart');
    chartEls.forEach(function(chart){
        var polyline = chart.querySelector('polyline');
        var label = chart.querySelector('.chart_label');
        if(!polyline || !label) return;

        var points = polyline.getAttribute('points').trim().split(/\s+/);
        var last = points[points.length - 1].split(',');
        var prev = points[points.length - 2].split(',');
        var svg = polyline.closest('svg');
        var vb = svg.getAttribute('viewBox').split(/\s+/);
        var vbW = parseFloat(vb[2]);
        var vbH = parseFloat(vb[3]);

        var yPos = parseFloat(last[1]);

        label.style.left = 'auto';
        label.style.right = '28px';
        label.style.top = (yPos / vbH * 100) + '%';
        label.style.transform = 'translateY(calc(-100% - 10px))';
    });

    // #main_aslan 공용: 디바운스 리사이즈 + hidden 측정 헬퍼
    var aslanResizeTimer = null;
    var aslanResizeDelay = 100;

    function withStep2Visible(fn){
        var step2 = document.querySelector('#main_aslan .effect_step2');
        if(!step2) return fn();
        var wasHidden = getComputedStyle(step2).display === 'none';
        var prevDisplay, prevVisibility, prevPointerEvents;
        if(wasHidden){
            prevDisplay = step2.style.display;
            prevVisibility = step2.style.visibility;
            prevPointerEvents = step2.style.pointerEvents;
            step2.style.display = 'block';
            step2.style.visibility = 'hidden';
            step2.style.pointerEvents = 'none';
        }
        var result = fn();
        if(wasHidden){
            step2.style.display = prevDisplay;
            step2.style.visibility = prevVisibility;
            step2.style.pointerEvents = prevPointerEvents;
        }
        return result;
    }

    // step1 공통: 이미지 중심 좌표 계산
    function getStep1ImgCenters(){
        var imgs = document.querySelectorAll('#main_aslan .effect_step1 .step1_list_img');
        var centers = [];
        imgs.forEach(function(img){
            var r = img.getBoundingClientRect();
            var li = img.closest('li');
            var tx = (li && typeof gsap !== 'undefined') ? (gsap.getProperty(li, 'x') || 0) : 0;
            centers.push({
                cx: r.left + r.width / 2 - tx,
                cy: r.top + r.height / 2,
                rect: { left: r.left - tx, right: r.right - tx, top: r.top, bottom: r.bottom, width: r.width, height: r.height }
            });
        });
        return centers;
    }

    // step1_connector Canvas 드로잉
    function drawStep1Connectors(){
        var connectors = document.querySelectorAll('#main_aslan .step1_connector');
        if(!connectors.length) return;
        var centers = getStep1ImgCenters();
        if(centers.length < 3) return;

        connectors.forEach(function(container){
            var isTop = container.classList.contains('top');
            var canvas = container.querySelector('canvas');
            if(!canvas){
                canvas = document.createElement('canvas');
                container.appendChild(canvas);
            }

            var conRect = container.getBoundingClientRect();
            var left  = Math.round(centers[0].cx - conRect.left) + 0.5;
            var mid   = Math.round(centers[1].cx - conRect.left) + 0.5;
            var right = Math.round(centers[2].cx - conRect.left) + 0.5;

            var tipH = 5;
            var pad = tipH + 1;
            var cw = container.offsetWidth;
            var w = cw + pad * 2;
            var h = container.offsetHeight;
            var dpr = window.devicePixelRatio || 1;
            canvas.style.left = -pad + 'px';
            canvas.style.width = w + 'px';
            canvas.width = w * dpr;
            canvas.height = h * dpr;

            var ctx = canvas.getContext('2d');
            ctx.scale(dpr, dpr);
            ctx.clearRect(0, 0, w, h);
            ctx.translate(pad, 0);

            var color = 'rgba(255, 255, 255, 1)';
            var R = 20;

            ctx.strokeStyle = color;
            ctx.lineWidth = 1;
            ctx.setLineDash([4, 5]);

            if(isTop){
                var endY = h - tipH;
                var hY = 0.5;

                ctx.beginPath();
                ctx.moveTo(left, endY);
                ctx.arcTo(left, hY, right, hY, R);
                ctx.lineTo(mid + R, hY);
                ctx.stroke();

                ctx.beginPath();
                ctx.moveTo(right, endY);
                ctx.arcTo(right, hY, left, hY, R);
                ctx.arcTo(mid, hY, mid, endY, R);
                ctx.lineTo(mid, endY);
                ctx.stroke();

                ctx.setLineDash([]);
                ctx.strokeStyle = color;
                ctx.lineWidth = 1;
                [left, mid, right].forEach(function(x){
                    ctx.beginPath();
                    ctx.moveTo(x - tipH, endY);
                    ctx.lineTo(x, h);
                    ctx.lineTo(x + tipH, endY);
                    ctx.stroke();
                });

            } else {
                var startY = tipH;
                var hY = h - 0.5;

                ctx.beginPath();
                ctx.moveTo(left, startY);
                ctx.arcTo(left, hY, right, hY, R);
                ctx.lineTo(mid + R, hY);
                ctx.stroke();

                ctx.beginPath();
                ctx.moveTo(right, startY);
                ctx.arcTo(right, hY, left, hY, R);
                ctx.arcTo(mid, hY, mid, startY, R);
                ctx.lineTo(mid, startY);
                ctx.stroke();

                ctx.setLineDash([]);
                ctx.strokeStyle = color;
                ctx.lineWidth = 1;
                [left, mid, right].forEach(function(x){
                    ctx.beginPath();
                    ctx.moveTo(x - tipH, startY);
                    ctx.lineTo(x, 0);
                    ctx.lineTo(x + tipH, startY);
                    ctx.stroke();
                });
            }
        });
    }

    // step1 li 사이 수평 점선 + 화살표
    function drawStep1Horizontals(){
        var list = document.querySelector('#main_aslan .effect_step1 .step1_list');
        if(!list) return;
        var centers = getStep1ImgCenters();
        if(centers.length < 2) return;

        var listRect = list.getBoundingClientRect();
        var inset = 20;

        for(var i = 0; i < centers.length - 1; i++){
            var curR = centers[i].rect;
            var nxtR = centers[i + 1].rect;
            var x1 = curR.right - listRect.left + inset;
            var x2 = nxtR.left - listRect.left - inset;
            var gap = x2 - x1;
            var cy = centers[i].cy - listRect.top;

            var canvas = list.querySelector('.step1_h_line_' + i);
            if(!canvas){
                canvas = document.createElement('canvas');
                canvas.className = 'step1_h_line_' + i;
                canvas.style.cssText = 'position:absolute;pointer-events:none;z-index:1;';
                list.appendChild(canvas);
            }

            if(gap <= 0){
                canvas.style.display = 'none';
                continue;
            }
            canvas.style.display = '';

            var tipW = 5;
            var pad = tipW + 1;
            var drawW = gap + pad * 2;
            var drawH = tipW * 2 + 2;
            var dpr = window.devicePixelRatio || 1;
            canvas.style.left = (x1 - pad) + 'px';
            canvas.style.top = (cy - drawH / 2) + 'px';
            canvas.style.width = drawW + 'px';
            canvas.style.height = drawH + 'px';
            canvas.width = drawW * dpr;
            canvas.height = drawH * dpr;

            var ctx = canvas.getContext('2d');
            ctx.scale(dpr, dpr);
            ctx.clearRect(0, 0, drawW, drawH);

            var color = 'rgba(255, 255, 255, 1)';
            var midY = drawH / 2;

            ctx.strokeStyle = color;
            ctx.lineWidth = 1;
            ctx.setLineDash([4, 5]);
            ctx.beginPath();
            ctx.moveTo(pad, midY + 0.5);
            ctx.lineTo(pad + gap - tipW, midY + 0.5);
            ctx.stroke();

            ctx.setLineDash([]);
            ctx.strokeStyle = color;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(pad + gap - tipW, midY - tipW + 0.5);
            ctx.lineTo(pad + gap, midY + 0.5);
            ctx.lineTo(pad + gap - tipW, midY + tipW + 0.5);
            ctx.stroke();
        }
    }

    function drawStep1All(){
        drawStep1Connectors();
        drawStep1Horizontals();
    }

    // step1 이미지 로드 완료 후 점선 드로잉 (이미지 미로드 시 위치 틀어짐 방지)
    var step1Imgs = document.querySelectorAll('#main_aslan .effect_step1 .step1_list_img img');
    if(step1Imgs.length){
        var loaded = 0;
        var total = step1Imgs.length;
        function onStep1ImgReady(){
            loaded++;
            if(loaded >= total) drawStep1All();
        }
        step1Imgs.forEach(function(img){
            if(img.complete){
                onStep1ImgReady();
            } else {
                img.addEventListener('load', onStep1ImgReady);
                img.addEventListener('error', onStep1ImgReady);
            }
        });
    } else {
        drawStep1All();
    }
    window.addEventListener('load', drawStep1All);

    // step1: display none → visible 전환 시 연결선 재그리기
    var step1ListArea = document.querySelector('#main_aslan .effect_step1 .step1_list_area');
    if(step1ListArea && typeof ResizeObserver !== 'undefined'){
        var step1RO = new ResizeObserver(function(){
            onAslanResize();
        });
        step1RO.observe(step1ListArea);
    }

    // step2_mp_connector Canvas 드로잉 (step1_connector 방식 참조)
    // step2_mp_list: 중앙 세로 점선 + 좌우 브래킷형 점선
    function drawStep2MpLine(){
        withStep2Visible(function(){ drawStep2MpLineInner(); });
    }
    function drawStep2MpLineInner(){
        var area = document.querySelector('#main_aslan .step2_mp_list_area');
        var list = document.querySelector('#main_aslan .step2_mp_list');
        if(!area || !list) return;
        var items = list.querySelectorAll('li');
        if(items.length < 2) return;

        var canvas = list.querySelector('canvas.step2_mp_line');
        if(!canvas){
            canvas = document.createElement('canvas');
            canvas.className = 'step2_mp_line';
            list.appendChild(canvas);
        }

        var legacySideCanvas = list.querySelector('canvas.step2_mp_side_line');
        if(legacySideCanvas) legacySideCanvas.remove();

        var leftConnector = area.querySelector('.step2_mp_side_connector.left');
        var rightConnector = area.querySelector('.step2_mp_side_connector.right');
        if(!leftConnector || !rightConnector) return;

        var leftCanvas = leftConnector.querySelector('canvas');
        if(!leftCanvas){
            leftCanvas = document.createElement('canvas');
            leftConnector.appendChild(leftCanvas);
        }

        var rightCanvas = rightConnector.querySelector('canvas');
        if(!rightCanvas){
            rightCanvas = document.createElement('canvas');
            rightConnector.appendChild(rightCanvas);
        }

        var areaRect = area.getBoundingClientRect();
        var listRect = list.getBoundingClientRect();
        var spans = [];

        items.forEach(function(item){
            var span = item.querySelector('span');
            if(span) spans.push(span);
        });

        if(spans.length < 2){
            canvas.style.display = 'none';
            leftCanvas.style.display = 'none';
            rightCanvas.style.display = 'none';
            return;
        }

        var firstSpan = spans[0];
        var lastSpan = spans[spans.length - 1];
        var firstRect = firstSpan.getBoundingClientRect();
        var lastRect = lastSpan.getBoundingClientRect();

        var startY = (firstRect.top + firstRect.height / 2) - listRect.top;
        var centerEndY = (lastRect.top + lastRect.height / 2) - listRect.top;
        var sideStartY = (firstRect.top + firstRect.height / 2) - areaRect.top;
        var sideEndY = centerEndY;
        var lineH = centerEndY - startY;
        var sideH = sideEndY - startY;

        var leftGutter = Math.max(16, Math.round(listRect.left - areaRect.left));
        var rightGutter = Math.max(16, Math.round(areaRect.right - listRect.right));

        if(lineH <= 0 || sideH <= 0){
            canvas.style.display = 'none';
            leftCanvas.style.display = 'none';
            rightCanvas.style.display = 'none';
            return;
        }

        canvas.style.display = '';
        leftCanvas.style.display = '';
        rightCanvas.style.display = '';

        // --- 중앙 점선 + 화살표 캔버스 (기존) ---
        var w = 20;
        var tipH = 4;
        var dpr = window.devicePixelRatio || 1;

        canvas.style.width = w + 'px';
        canvas.style.height = lineH + 'px';
        canvas.style.top = startY + 'px';
        canvas.width = w * dpr;
        canvas.height = lineH * dpr;

        var ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
        ctx.clearRect(0, 0, w, lineH);

        var step2LineColor = '#fff';
        var step2LineWidth = 1;
        var cx = w / 2;

        // 각 li의 캔버스 내 상하 가장자리 계산
        var liTops = [];
        var liBottoms = [];
        for(var si = 0; si < items.length; si++){
            var liRect = items[si].getBoundingClientRect();
            var liTopY = liRect.top - listRect.top - startY;
            liTops.push(liTopY);
            liBottoms.push(liTopY + liRect.height);
        }

        var desiredGap = 30; // li 가장자리로부터 상하 이상적 간격
        var gapRatio = 0.25; // 공간이 부족할 때 최소 비율 (양쪽 25%씩 여백)
        var minLineLen = 8; // 점선이 그려지기 위한 최소 길이

        // 구간별 점선 세로 라인 (li 간격에 비례하여 gap 조정)
        ctx.strokeStyle = step2LineColor;
        ctx.lineWidth = step2LineWidth;
        ctx.setLineDash([4, 4]);
        for(var i = 0; i < items.length - 1; i++){
            var gapSpace = liTops[i + 1] - liBottoms[i];
            var actualGap = Math.min(desiredGap, gapSpace * gapRatio);
            var segStartY = liBottoms[i] + actualGap;
            var segEndY = liTops[i + 1] - actualGap;
            if(segEndY - segStartY < minLineLen) continue;
            ctx.beginPath();
            ctx.moveTo(cx, segStartY);
            ctx.lineTo(cx, segEndY);
            ctx.stroke();
        }

        // 각 구간 하단에 화살표 (하단 방향)
        for(var i = 0; i < items.length - 1; i++){
            var gapSpace2 = liTops[i + 1] - liBottoms[i];
            var actualGap2 = Math.min(desiredGap, gapSpace2 * gapRatio);
            var arrowY = liTops[i + 1] - actualGap2;
            var segTop = liBottoms[i] + actualGap2;
            if(arrowY - segTop < minLineLen) continue;
            ctx.setLineDash([]);
            ctx.strokeStyle = step2LineColor;
            ctx.lineWidth = step2LineWidth;
            ctx.beginPath();
            ctx.moveTo(cx - tipH, arrowY - tipH);
            ctx.lineTo(cx, arrowY);
            ctx.lineTo(cx + tipH, arrowY - tipH);
            ctx.stroke();
        }

        var sideColor = step2LineColor;

        function drawSideConnector(connector, side){
            var canvasEl = side === 'left' ? leftCanvas : rightCanvas;
            var localWidth = side === 'left' ? leftGutter : rightGutter;
            var connectorStartGap = 8;
            var connectorEndGap = 8;
            var desiredConnectorOffset = 17;
            var arrowSize = 4;
            var arrowPad = arrowSize + 1; // 화살표 잘림 방지 여유
            var totalH = sideH + arrowPad * 2; // 캔버스 높이에 상하 패딩 추가
            var axisOffset = step2LineWidth % 2 ? 0.5 : 0;
            var minX = axisOffset;
            var maxX = Math.max(minX, localWidth - axisOffset);
            var topY = arrowPad + axisOffset;
            var bottomY = Math.max(topY, arrowPad + sideH - axisOffset);
            var availableX = Math.max(0, maxX - minX);
            var edgeX = side === 'left' ? maxX : minX;
            var startOffset = Math.min(connectorStartGap, availableX);
            var endOffset = Math.min(connectorEndGap, availableX);
            var startX = side === 'left' ? edgeX - startOffset : edgeX + startOffset;
            var endX = side === 'left' ? edgeX - endOffset : edgeX + endOffset;
            var railOffset = Math.max(startOffset, endOffset, Math.min(desiredConnectorOffset, availableX));
            var railX = side === 'left' ? edgeX - railOffset : edgeX + railOffset;

            connector.style.width = localWidth + 'px';
            connector.style.top = (sideStartY - arrowPad) + 'px';
            connector.style.height = totalH + 'px';

            canvasEl.style.width = localWidth + 'px';
            canvasEl.style.height = totalH + 'px';
            canvasEl.width = localWidth * dpr;
            canvasEl.height = totalH * dpr;

            var sideCtx = canvasEl.getContext('2d');
            sideCtx.scale(dpr, dpr);
            sideCtx.clearRect(0, 0, localWidth, totalH);
            sideCtx.strokeStyle = sideColor;
            sideCtx.lineWidth = step2LineWidth;
            sideCtx.lineCap = 'butt';
            sideCtx.lineJoin = 'miter';
            sideCtx.setLineDash([4, 4]);

            sideCtx.beginPath();
            sideCtx.moveTo(startX, topY);
            sideCtx.lineTo(railX, topY);
            sideCtx.lineTo(railX, bottomY);
            sideCtx.lineTo(endX, bottomY);
            sideCtx.stroke();

            // 화살표
            sideCtx.setLineDash([]);
            sideCtx.strokeStyle = sideColor;
            sideCtx.lineWidth = step2LineWidth;

            if(side === 'left'){
                // 좌측: 마지막 연결부(하단) - 오른쪽(리스트 방향) 화살표
                sideCtx.beginPath();
                sideCtx.moveTo(endX - arrowSize, bottomY - arrowSize);
                sideCtx.lineTo(endX, bottomY);
                sideCtx.lineTo(endX - arrowSize, bottomY + arrowSize);
                sideCtx.stroke();
            } else {
                // 우측: 처음 연결부(상단) - 왼쪽(리스트 방향) 화살표
                sideCtx.beginPath();
                sideCtx.moveTo(startX + arrowSize, topY - arrowSize);
                sideCtx.lineTo(startX, topY);
                sideCtx.lineTo(startX + arrowSize, topY + arrowSize);
                sideCtx.stroke();
            }
        }

        drawSideConnector(leftConnector, 'left');
        drawSideConnector(rightConnector, 'right');
    }

    drawStep2MpLine();

    // ani_step_box 높이 균등화 (가장 높은 박스 기준)
    function equalizeAniStepBoxHeight(){
        var boxes = document.querySelectorAll('#main_aslan .ani_step_box');
        if(boxes.length < 2) return;
        var maxH = 0;
        var hiddenBoxes = [];
        boxes.forEach(function(box){
            box.style.height = 'auto';
            var isHidden = getComputedStyle(box).display === 'none';
            if(isHidden){
                box.style.display = 'block';
                box.style.visibility = 'hidden';
                box.style.position = 'absolute';
                hiddenBoxes.push(box);
            }
            var h = box.offsetHeight;
            if(h > maxH) maxH = h;
        });
        hiddenBoxes.forEach(function(box){
            box.style.display = '';
            box.style.visibility = '';
            box.style.position = '';
        });
        boxes.forEach(function(box){
            box.style.height = maxH + 'px';
        });
    }

    // step2_sub_process li 높이를 step2_mp_list_area에 맞춤
    function matchSubProcessLiHeight(){
        withStep2Visible(function(){ matchSubProcessLiHeightInner(); });
    }
    function matchSubProcessLiHeightInner(){
        var mpArea = document.querySelector('#main_aslan .step2_mp_list_area');
        var subLis = document.querySelectorAll('#main_aslan .step2_box .step2_sub_process ul li');
        if(!mpArea || !subLis.length) return;
        var h = mpArea.offsetHeight;
        subLis.forEach(function(li){
            li.style.height = h + 'px';
        });
    }

    // step2_box gap을 step2_sub_process ul li 사이 간격에 맞춤
    function matchStep2BoxGap(){
        withStep2Visible(function(){ matchStep2BoxGapInner(); });
    }
    function matchStep2BoxGapInner(){
        var step2Box = document.querySelector('#main_aslan .step2_box');
        var subUl = document.querySelector('#main_aslan .step2_box .step2_sub_process ul');
        var subLis = document.querySelectorAll('#main_aslan .step2_box .step2_sub_process ul li');
        if(!step2Box || !subUl || subLis.length < 2) return;
        var firstLi = subLis[0];
        var secondLi = subLis[1];
        var gap = secondLi.getBoundingClientRect().left - firstLi.getBoundingClientRect().right;
        if(gap > 0){
            step2Box.style.gap = gap + 'px';
        }
    }

    // step2 확장 애니메이션 완료 플래그
    var step2ExpandRevealed = false;

    // main_aslan pin 활성 플래그 — 활성 중 ScrollTrigger.refresh() 방지
    var mslPinActive = false;
    // aslan_Transform pin 활성 플래그 — 활성 중 refresh 시 pin-spacer teardown/rebuild로
    // 스크롤 점프가 발생하고, 476px 경계 리사이즈 시 matchMedia 레이아웃 변화와 겹쳐
    // pin 위치가 어긋나는 문제가 생김. main_aslan과 동일하게 pin 해제 후 refresh로 보류.
    var asnPinActive = false;
    // pin 활성 중 refresh 요청이 들어오면 보류 → pin 해제 후 실행
    var _pendingRefresh = false;

    // window.load + includeReady 양쪽 완료 추적 — 최종 refresh는 둘 다 끝난 후 수행
    var _windowLoaded = false;
    var _includesReady = false;

    // step2_box 갭 사이에 가로 연결선 배치
    function drawStep2GapConnectors(){
        withStep2Visible(function(){ drawStep2GapConnectorsInner(); });
    }
    function drawStep2GapConnectorsInner(){
        var step2Box = document.querySelector('#main_aslan .step2_box');
        if(!step2Box) return;

        // 기존 커넥터 제거
        step2Box.querySelectorAll('.step2_gap_connector').forEach(function(el){ el.remove(); });

        var leftEl = step2Box.querySelector('.left');
        var subLis = step2Box.querySelectorAll('.step2_sub_process ul li');
        if(!leftEl || !subLis.length) return;

        var boxRect = step2Box.getBoundingClientRect();
        var mpArea = step2Box.querySelector('.step2_mp_list_area');

        // 연결 대상 쌍: left→li[0], li[0]→li[1], li[1]→li[2]
        var items = [leftEl];
        subLis.forEach(function(li){ items.push(li); });

        var padding = 20;
        var minVisibleWidth = 10;

        for(var i = 0; i < items.length - 1; i++){
            var rectA = items[i].getBoundingClientRect();
            var rectB = items[i + 1].getBoundingClientRect();
            var rawGap = rectB.left - rectA.right;

            if(rawGap < minVisibleWidth) continue;

            var actualPad = Math.min(padding, Math.floor((rawGap - minVisibleWidth) / 2));
            actualPad = Math.max(0, actualPad);
            var gapLeft = rectA.right - boxRect.left + actualPad;
            var gapWidth = rawGap - actualPad * 2;
            if(gapWidth <= 0) continue;

            // 첫 번째 커넥터: step2_mp_list_area 세로 중앙 기준
            // 나머지 커넥터: li 세로 중앙 기준
            var refRect;
            if(i === 0 && mpArea){
                refRect = mpArea.getBoundingClientRect();
            } else {
                refRect = subLis[i - 1] ? subLis[i - 1].getBoundingClientRect() : rectA;
            }
            var centerY = refRect.top + refRect.height / 2 - boxRect.top;

            var connector = document.createElement('div');
            connector.className = 'step2_gap_connector';
            connector.style.left = gapLeft + 'px';
            connector.style.width = gapWidth + 'px';
            connector.style.top = centerY + 'px';
            connector.style.transform = 'translateY(-50%)';
            connector.innerHTML = '<span class="connector_line"></span><span class="connector_arrow"></span>';
            if(step2ExpandRevealed) connector.style.opacity = '1';
            step2Box.appendChild(connector);
        }
    }

    function refreshMainAslanStep2Layout(){
        equalizeAniStepBoxHeight();
        withStep2Visible(function(){
            matchSubProcessLiHeightInner();
            matchStep2BoxGapInner();
            drawStep2GapConnectorsInner();
            drawStep2MpLineInner();
        });
    }

    // #main_aslan 통합 리사이즈 핸들러 (디바운스)
    // doRefresh: true = 윈도우 리사이즈 등 전역 refresh 필요, false = 내부 재그리기만
    function onAslanResize(doRefresh){
        if(aslanResizeTimer) clearTimeout(aslanResizeTimer);
        aslanResizeTimer = setTimeout(function(){
            try { drawStep1All(); } catch(e) { console.warn('drawStep1All error:', e); }
            try { refreshMainAslanStep2Layout(); } catch(e) { console.warn('refreshMainAslanStep2Layout error:', e); }
            // 전역 refresh는 윈도우 리사이즈 시에만 (ResizeObserver 발동 시 제외)
            if(doRefresh && globalMOReady && !mslPinActive && !asnPinActive && typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
        }, aslanResizeDelay);
    }

    window.addEventListener('load', function(){
        _windowLoaded = true;
        try { refreshMainAslanStep2Layout(); } catch(e) { console.warn('refreshMainAslanStep2Layout error:', e); }
        if(_includesReady){
            // include 이미 완료 + 이미지 로드 완료 → 폰트 대기 후 최종 refresh
            var doLoadRefresh = function(){
                if(mslPinActive || asnPinActive){
                    _pendingRefresh = true;
                } else if(typeof ScrollTrigger !== 'undefined'){
                    ScrollTrigger.refresh();
                    lastDocHeight = document.documentElement.scrollHeight;
                }
                setTimeout(function(){ globalMOReady = true; }, 600);
            };
            if(document.fonts && document.fonts.ready){
                document.fonts.ready.then(function(){ doLoadRefresh(); });
            } else {
                doLoadRefresh();
            }
        }
        // include 미완료 시 includeReady 핸들러에서 최종 refresh 수행
    });
    window.addEventListener('resize', function(){ onAslanResize(true); });

    // GSAP refresh(특히 리사이즈 자동 refresh) 직전에 #main_aslan 내부 레이아웃을 동기적으로 최신화.
    // 미적용 시 GSAP의 내부 디바운스가 onAslanResize(100ms)보다 먼저 실행되면서
    // stale한 ani_step_box/step2 li 높이·gap으로 pin start/end가 측정돼
    // 리사이즈 중 pin 범위가 어긋나고 핀이 조기 해제되어 다음 섹션으로 튀는 현상이 발생한다.
    if(typeof ScrollTrigger !== 'undefined'){
        ScrollTrigger.addEventListener('refreshInit', function(){
            try { drawStep1All(); } catch(e){}
            try { refreshMainAslanStep2Layout(); } catch(e){}
        });
    }

    // span/li 크기 변화 감지 → 연결선 재그리기
    var step2MpListArea = document.querySelector('#main_aslan .step2_mp_list_area');
    if(step2MpListArea && typeof ResizeObserver !== 'undefined'){
        var step2RO = new ResizeObserver(function(){
            onAslanResize();
        });
        step2RO.observe(step2MpListArea);
        var step2SpanEls = step2MpListArea.querySelectorAll('.step2_mp_list li span');
        step2SpanEls.forEach(function(span){ step2RO.observe(span); });
    }

    // #main_aslan — msl_tit 하단 pin + step1_list_folder 스크롤 연동 순차 fadeIn
    var mslSection = document.getElementById('main_aslan');
    var mslTit = mslSection ? mslSection.querySelector('.msl_tit') : null;
    var mslEffectBox = mslSection ? mslSection.querySelector('.msl_effect_box') : null;
    var mslFolders = mslSection ? mslSection.querySelectorAll('.step1_list_folder') : [];

    if(mslTit && mslEffectBox && mslFolders.length && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'){
        gsap.registerPlugin(ScrollTrigger);

        // pos 번호순 정렬
        var sortedFolders = Array.from(mslFolders).sort(function(a, b){
            var aNum = parseInt(a.className.match(/pos(\d+)/)[1]);
            var bNum = parseInt(b.className.match(/pos(\d+)/)[1]);
            return aNum - bNum;
        });
        var folderCount = sortedFolders.length;

        // 864px 경계 resize/reload 대비 — matchMedia로 모드 전환 시 타임라인 자동 재빌드
        var mslMM = gsap.matchMedia();
        mslMM.add({
            narrow: '(max-width: 864px)',
            wide: '(min-width: 865px)'
        }, function(mmContext){
        var mqNarrow = !!mmContext.conditions.narrow;

        // 스크롤 연동 타임라인 — scrub 1로 부드러운 전환
        var fadeDur = 1;
        var gapDur = 0.6;
        var fadeOutGap = 0.8;
        var fadeOutDur = 1;
        var txtChangeGap = 0.6;
        var txtOutDur = 0.8;
        var txtInDur = 1;
        var txt2OutDur = 0.8;
        var txt2InDur = 1;
        var mergeGap = 0.8;
        var mergeDur = 1.2;
        var bgGap = 0.8;
        var bgDur = 1;
        var stepGap = 0.6;
        var stepDur = 1;
        var step2InDur = 2;
        var step2ExpandGap = 0.4;
        var step2ExpandDur = 1.5;
        var txt3OutDur = 0.8;
        var txt3InDur = 1;
        var step2RevealGap = 0.3;
        var step2RevealDur = 1.2;
        // wide(≥865px)는 narrow 대비 15% 단축
        var scrollPerUnit = mqNarrow ? 400 : 340;

        // step2가 보여야 하는 타임라인 progress 임계값 (타임라인 빌드 완료 후 계산)
        var step2VisibleProgress = 1;

        // 타임라인을 paused 상태로 먼저 빌드한 뒤 ScrollTrigger를 바인딩
        // (빈 타임라인에 scrollTrigger 내장 시 duration=0 → scrub 매핑 오류 방지)
        var mslTl = gsap.timeline({ paused: true });

        // 864px 이하 narrow 모드: effect_step1 횡스크롤 이동 + 폴더 가시화 시점 fadeIn
        var effectStep1El_ani = mslSection.querySelector('.effect_step1');

        if(mqNarrow && effectStep1El_ani){
            var narrowBoxW = mslEffectBox.offsetWidth;
            var narrowStepW = effectStep1El_ani.offsetWidth;
            var narrowOverflow = Math.max(0, narrowStepW - narrowBoxW);
            var translationDur = 5;
            var fadeDurNarrow = 0.8;
            var phaseStartLabel = 'narrowRevealStart';
            mslTl.addLabel(phaseStartLabel);

            // effect_step1 횡스크롤 이동 (초기 x=0 → 우측 overflow 노출)
            mslTl.to(effectStep1El_ani, {
                x: function(){
                    var bw = mslEffectBox.offsetWidth;
                    var sw = effectStep1El_ani.offsetWidth;
                    return -Math.max(0, sw - bw);
                },
                duration: translationDur,
                ease: 'none'
            }, phaseStartLabel);

            // 각 폴더의 leftX를 측정 후 가시화 시점 계산
            var stepBoxRectN = effectStep1El_ani.getBoundingClientRect();
            var folderInfos = sortedFolders.map(function(folder){
                var rect = folder.getBoundingClientRect();
                return {
                    folder: folder,
                    leftX: rect.left - stepBoxRectN.left,
                    width: rect.width
                };
            });

            var visibleAtStart = [];
            var hiddenAtStart = [];
            folderInfos.forEach(function(info){
                // 폴더 leftX가 viewport 우측 엣지에 닿는 진행도
                var tTrigger = narrowOverflow > 0 ? (info.leftX - narrowBoxW) / narrowOverflow : -1;
                if(tTrigger <= 0){
                    visibleAtStart.push(info);
                } else {
                    info.tTrigger = Math.min(1, tTrigger);
                    hiddenAtStart.push(info);
                }
            });
            visibleAtStart.sort(function(a, b){ return a.leftX - b.leftX; });

            // 초기 visible 폴더: 초반 35% 구간에 X 위치 순서대로 stagger fadeIn
            var visibleStartPortion = 0.35;
            visibleAtStart.forEach(function(info, i){
                var ratio = visibleAtStart.length > 1 ? i / (visibleAtStart.length - 1) : 0;
                var offset = ratio * visibleStartPortion * translationDur;
                var children = info.folder.querySelectorAll('.top, .bot');
                mslTl.to(children, {
                    opacity: 1,
                    visibility: 'visible',
                    duration: fadeDurNarrow,
                    ease: 'power2.out'
                }, phaseStartLabel + '+=' + offset);
            });

            // 초기 hidden 폴더: 우측 엣지 진입 시점에 fadeIn
            hiddenAtStart.forEach(function(info){
                var offset = info.tTrigger * translationDur;
                var children = info.folder.querySelectorAll('.top, .bot');
                mslTl.to(children, {
                    opacity: 1,
                    visibility: 'visible',
                    duration: fadeDurNarrow,
                    ease: 'power2.out'
                }, phaseStartLabel + '+=' + offset);
            });

            // phase A 종료 지점으로 타임라인 커서 동기화 (> 위치 재조정용 stub)
            mslTl.to({}, { duration: 0.01 }, phaseStartLabel + '+=' + (translationDur + fadeDurNarrow));
        } else {
            // 각 폴더의 .top, .bot을 순차적으로 fadeIn (fade + gap 교대 배치)
            sortedFolders.forEach(function(folder, i){
                var children = folder.querySelectorAll('.top, .bot');
                mslTl.to(children, {
                    opacity: 1,
                    visibility: 'visible',
                    duration: fadeDur,
                    ease: 'power2.out'
                });
                if(i < folderCount - 1){
                    mslTl.to({}, { duration: gapDur });
                }
            });
        }

        // 폴더 fadeIn 완료 후 빈 스크롤 → 연결선 + 폴더 전체 fadeOut
        mslTl.to({}, { duration: fadeOutGap });
        var fadeOutLabel = '>';
        var connectors = mslSection.querySelectorAll('.effect_step1 .step1_list_area .step1_connector');
        var allFolderChildren = mslSection.querySelectorAll('.step1_list_folder .top');
        var allFolderBots = mslSection.querySelectorAll('.step1_list_folder .bot');
        var fadeOutTargets = Array.from(connectors).concat(Array.from(allFolderChildren), Array.from(allFolderBots));
        mslTl.to(fadeOutTargets, {
            opacity: 0,
            duration: fadeOutDur,
            ease: 'power2.out',
            onUpdate: function(){
                var op = 1 - this.progress();
                var hLines = mslSection.querySelectorAll('.effect_step1 .step1_list canvas[class^="step1_h_line_"]');
                hLines.forEach(function(c){ c.style.opacity = op; });
            }
        }, fadeOutLabel);

        // narrow 모드: fadeOut과 동시에 effect_step1을 가로 중앙으로 이동 (후속 merge 단계 정렬)
        if(mqNarrow && effectStep1El_ani){
            mslTl.to(effectStep1El_ani, {
                x: function(){
                    var bw = mslEffectBox.offsetWidth;
                    var sw = effectStep1El_ani.offsetWidth;
                    return -Math.max(0, sw - bw) / 2;
                },
                duration: fadeOutDur,
                ease: 'power2.inOut'
            }, '<');
        }

        // fadeOut 후 → msl_effect_txt p 텍스트 변경 (fadeOut → 교체 → fadeIn)
        var mslEffectTxtP = mslSection.querySelector('.msl_effect_txt p');
        if(mslEffectTxtP){
            var originalTxt = mslEffectTxtP.innerHTML;
            var newTxt = 'AslanX performs the repetitive design exploration process internally through AI.';
            mslTl.to({}, { duration: txtChangeGap });
            mslTl.to(mslEffectTxtP, {
                opacity: 0,
                duration: txtOutDur,
                ease: 'power2.out',
                onComplete: function(){ mslEffectTxtP.innerHTML = newTxt; },
                onReverseComplete: function(){ mslEffectTxtP.innerHTML = originalTxt; }
            });
            mslTl.to(mslEffectTxtP, {
                opacity: 1,
                duration: txtInDur,
                ease: 'power2.out'
            });
        }

        // fadeOut 후 빈 스크롤 → 1, 3번 li가 중앙(2번) 위치로 모이기
        var step1Lis = mslSection.querySelectorAll('.effect_step1 .step1_list li');
        if(step1Lis.length >= 3){
            mslTl.to({}, { duration: mergeGap });

            var mergeLabel = 'mergeToCenter';
            // 2번 li를 최상위로
            mslTl.set(step1Lis[1], { zIndex: 3 }, mergeLabel);
            mslTl.set(step1Lis[0], { zIndex: 1 }, mergeLabel);
            mslTl.set(step1Lis[2], { zIndex: 1 }, mergeLabel);

            // 1번(좌), 3번(우) li를 2번 위치로 이동 + fadeOut
            mslTl.to(step1Lis[0], {
                x: function(){
                    return step1Lis[1].offsetLeft - step1Lis[0].offsetLeft;
                },
                opacity: 0,
                duration: mergeDur,
                ease: 'power2.inOut'
            }, mergeLabel);
            mslTl.to(step1Lis[2], {
                x: function(){
                    return step1Lis[1].offsetLeft - step1Lis[2].offsetLeft;
                },
                opacity: 0,
                duration: mergeDur,
                ease: 'power2.inOut'
            }, mergeLabel);

            // merge와 동시에 텍스트 2차 변경
            if(mslEffectTxtP){
                var newTxt2 = 'Designs generated by AI are passed to the validation stage. <br> Engineers can immediately review optimized results without repetitive iterations.';
                mslTl.to(mslEffectTxtP, {
                    opacity: 0,
                    duration: txt2OutDur,
                    ease: 'power2.out',
                    onComplete: function(){ mslEffectTxtP.innerHTML = newTxt2; },
                    onReverseComplete: function(){ mslEffectTxtP.innerHTML = newTxt; }
                }, mergeLabel);
                mslTl.to(mslEffectTxtP, {
                    opacity: 1,
                    duration: txt2InDur,
                    ease: 'power2.out'
                });
            }
        }

        // 합쳐진 후 빈 스크롤 → 배경색 전환
        mslTl.to({}, { duration: bgGap });
        mslTl.to(mslEffectBox, {
            '--effect-bg': 'rgba(142, 87, 244, 0.1)',
            duration: bgDur,
            ease: 'power2.out'
        });

        // 배경색 전환 후 → step1 fadeOut + step2 fadeIn
        var effectStep1 = mslSection.querySelector('.effect_step1');
        var effectStep2 = mslSection.querySelector('.effect_step2');
        if(effectStep1 && effectStep2){
            mslTl.to({}, { duration: stepGap });
            // step1 fadeOut
            mslTl.to(effectStep1, {
                opacity: 0,
                duration: stepDur,
                ease: 'power2.out',
                onComplete: function(){
                    effectStep1.classList.add('is_hidden');
                },
                onReverseComplete: function(){
                    effectStep1.classList.remove('is_hidden');
                }
            });
            // step1 완전히 제거된 후 간격 → step2 등장 (left만 중앙에, right/연결선 숨김)
            var step2Left = effectStep2.querySelector('.step2_box > .left');
            var step2Right = effectStep2.querySelector('.step2_box > .right');
            var step2Box = effectStep2.querySelector('.step2_box');

            // left 중앙 오프셋 계산 함수
            function getStep2LeftCenterX(){
                if(!step2Left || !step2Box) return 0;
                return withStep2Visible(function(){
                    var boxW = step2Box.offsetWidth;
                    var leftW = step2Left.offsetWidth;
                    return (boxW - leftW) / 2;
                });
            }

            mslTl.to({}, { duration: 0.1 });

            // 초기 상태: effectStep2 opacity 0 + is_visible 부여
            // narrow(≤864px) 모드는 최종 상태(left 원위치, right/connector visible)로 등장
            var step2InitLabel = 'step2Init';
            mslTl.set(effectStep2, {
                opacity: 0,
                onComplete: function(){
                    effectStep2.classList.add('is_visible');
                    if(mqNarrow){
                        drawStep2GapConnectorsInner();
                        var gapConns = step2Box ? step2Box.querySelectorAll('.step2_gap_connector') : [];
                        gapConns.forEach(function(el){ el.style.opacity = '1'; });
                        step2ExpandRevealed = true;
                    }
                },
                onReverseComplete: function(){
                    effectStep2.classList.remove('is_visible');
                    step2ExpandRevealed = false;
                }
            }, step2InitLabel);
            if(step2Left){
                mslTl.set(step2Left, {
                    x: mqNarrow ? 0 : function(){ return getStep2LeftCenterX(); }
                }, step2InitLabel);
            }
            if(step2Right){
                mslTl.set(step2Right, { opacity: mqNarrow ? 1 : 0 }, step2InitLabel);
            }

            // step2 전체 fadeIn
            mslTl.to(effectStep2, {
                opacity: 1,
                duration: step2InDur,
                ease: 'power2.out'
            });

            if(!mqNarrow){
                // 간격 후 → left 원위치 이동 (865px 이상만)
                mslTl.to({}, { duration: step2ExpandGap });
                if(step2Left){
                    mslTl.to(step2Left, {
                        x: 0,
                        duration: step2ExpandDur,
                        ease: 'power2.inOut'
                    });
                }
            }

            // 텍스트 3차 변경 (공통)
            if(mslEffectTxtP){
                var newTxt3 = 'AslanX generates diverse design candidates and predicts performance through AI-based design exploration. <br> AI iterates through generation, prediction, and optimization to automatically derive designs that meet target conditions.';
                mslTl.to(mslEffectTxtP, {
                    opacity: 0,
                    duration: txt3OutDur,
                    ease: 'power2.out',
                    onComplete: function(){ mslEffectTxtP.innerHTML = newTxt3; },
                    onReverseComplete: function(){ mslEffectTxtP.innerHTML = newTxt2; }
                });
                mslTl.to(mslEffectTxtP, {
                    opacity: 1,
                    duration: txt3InDur,
                    ease: 'power2.out'
                });
            }

            if(mqNarrow){
                // narrow: effect_step2 수평 스크롤 reveal (우측 overflow 노출)
                mslTl.to({}, { duration: step2RevealGap });
                mslTl.to(effectStep2, {
                    x: function(){
                        var bw = mslEffectBox.offsetWidth;
                        var sw = effectStep2.offsetWidth;
                        return -Math.max(0, sw - bw);
                    },
                    duration: step2ExpandDur + step2RevealDur,
                    ease: 'none'
                });
            } else {
                // 텍스트 변경 후 간격 → right/연결선 fadeIn
                mslTl.to({}, { duration: step2RevealGap });

                // left 원위치 완료 후 연결선 재생성 (load 시점에는 left가 중앙이라 gap 부족으로 미생성)
                mslTl.call(function(){ drawStep2GapConnectorsInner(); });

                var step2RevealLabel = 'step2Reveal';
                // right fadeIn
                if(step2Right){
                    mslTl.to(step2Right, {
                        opacity: 1,
                        duration: step2RevealDur,
                        ease: 'power2.out'
                    }, step2RevealLabel);
                }
                // gap 연결선 fadeIn (동적 생성 요소이므로 onUpdate에서 직접 처리)
                (function(){
                    var revealProgress = { v: 0 };
                    mslTl.to(revealProgress, {
                        v: 1,
                        duration: step2RevealDur,
                        ease: 'power2.out',
                        onUpdate: function(){
                            var connectors = step2Box ? step2Box.querySelectorAll('.step2_gap_connector') : [];
                            var val = revealProgress.v;
                            connectors.forEach(function(el){ el.style.opacity = val; });
                            step2ExpandRevealed = val >= 1;
                        }
                    }, step2RevealLabel);
                })();
            }
        }

        // 타임라인 빌드 완료 — 실제 duration 기반으로 step2VisibleProgress 계산
        var tlDur = mslTl.duration();
        if(mslTl.labels && mslTl.labels.hasOwnProperty('step2Init') && tlDur > 0){
            step2VisibleProgress = mslTl.labels['step2Init'] / tlDur;
        }

        // 실제 duration 기반 scrollEnd 계산 후 ScrollTrigger 바인딩
        var mslScrollEnd = Math.round(tlDur * scrollPerUnit);

        ScrollTrigger.create({
            trigger: mslSection,
            start: function(){
                // mslEffectBox 상단(margin 포함)이 뷰포트 top에 닿을 때 pin 시작
                var secRect = mslSection.getBoundingClientRect();
                var effRect = mslEffectBox.getBoundingClientRect();
                var marginTop = parseInt(getComputedStyle(mslEffectBox).marginTop) || 0;
                var offset = Math.round((effRect.top - marginTop) - secRect.top);
                return 'top+=' + offset + ' top';
            },
            end: function(){ return '+=' + mslScrollEnd; },
            pin: true,
            pinSpacing: true,
            scrub: 1,
            invalidateOnRefresh: true,
            animation: mslTl,
            onToggle: function(self){
                mslPinActive = self.isActive;
                if(!self.isActive && _pendingRefresh){
                    _pendingRefresh = false;
                    requestAnimationFrame(function(){
                        // rAF 시점에 pin이 다시 활성화됐거나 다른 핀이 활성화돼 있으면 재보류
                        if(mslPinActive || asnPinActive){
                            _pendingRefresh = true;
                            return;
                        }
                        if(typeof ScrollTrigger !== 'undefined'){
                            ScrollTrigger.refresh();
                            lastDocHeight = document.documentElement.scrollHeight;
                        }
                    });
                }
            },
            onRefresh: function(){
                // 리로드/리사이즈 후 현재 progress에 따라 클래스 상태 복원
                var prog = mslTl.progress();
                var dur = mslTl.duration();
                var step1El = mslSection.querySelector('.effect_step1');
                var step2El = mslSection.querySelector('.effect_step2');
                // step1: fadeOut 완료 시점 이후면 is_hidden 부여
                var step1HiddenProgress = dur > 0 ? (step2VisibleProgress - 0.1 / dur) : 1;
                if(step1El){
                    if(prog >= step1HiddenProgress && !step1El.classList.contains('is_hidden')){
                        step1El.classList.add('is_hidden');
                    } else if(prog < step1HiddenProgress && step1El.classList.contains('is_hidden')){
                        step1El.classList.remove('is_hidden');
                    }
                }
                // step2: init 시점 이후면 is_visible 부여
                if(step2El){
                    if(prog >= step2VisibleProgress && !step2El.classList.contains('is_visible')){
                        step2El.classList.add('is_visible');
                    } else if(prog < step2VisibleProgress){
                        if(step2El.classList.contains('is_visible')) step2El.classList.remove('is_visible');
                        step2ExpandRevealed = false;
                    }
                }
                // step2_gap_connector opacity 복원 — pin 해제 시 refresh로 인한 소실 방지
                if(mqNarrow){
                    // narrow: step2 등장(step2Init) 이후면 gap_connector 항상 visible
                    if(prog >= step2VisibleProgress){
                        var gapConnsN = mslSection.querySelectorAll('.step2_box .step2_gap_connector');
                        if(!gapConnsN.length && typeof drawStep2GapConnectorsInner === 'function'){
                            drawStep2GapConnectorsInner();
                            gapConnsN = mslSection.querySelectorAll('.step2_box .step2_gap_connector');
                        }
                        gapConnsN.forEach(function(el){ el.style.opacity = '1'; });
                        step2ExpandRevealed = true;
                    }
                } else if(dur > 0){
                    var step2RevealEndProg = mslTl.labels.hasOwnProperty('step2Reveal')
                        ? (mslTl.labels['step2Reveal'] + step2RevealDur) / dur : 1;
                    if(prog >= step2RevealEndProg - 0.001){
                        step2ExpandRevealed = true;
                        var gapConns = mslSection.querySelectorAll('.step2_box .step2_gap_connector');
                        gapConns.forEach(function(el){ el.style.opacity = '1'; });
                    }
                }
            }
        });

        // matchMedia 모드 전환 시 pin active 상태로 ScrollTrigger가 kill되면
        // onToggle(isActive=false)가 발동되지 않아 mslPinActive가 true로 고정된다.
        // 고정되면 이후 모든 ScrollTrigger.refresh() 경로가 차단돼 다른 pin-spacer
        // 높이/위치가 stale 상태로 남아 섹션 겹침 오작동을 유발하므로 여기서 강제 해제.
        return function(){
            if(!mslPinActive) return;
            mslPinActive = false;
            if(_pendingRefresh && !asnPinActive){
                _pendingRefresh = false;
                requestAnimationFrame(function(){
                    if(mslPinActive || asnPinActive){
                        _pendingRefresh = true;
                        return;
                    }
                    if(typeof ScrollTrigger !== 'undefined'){
                        ScrollTrigger.refresh();
                        lastDocHeight = document.documentElement.scrollHeight;
                    }
                });
            }
        };
        }); // gsap.matchMedia 콜백 종료 — 모드 전환 시 위 타임라인/ScrollTrigger 자동 kill 후 재빌드

    }

    // main_aslan pin-spacer 생성 완료 → main_problemSolve ScrollTrigger를 이제 생성 (DOM 순서 보장)
    if(typeof _deferredMpsBuild === 'function'){
        _deferredMpsBuild();
        _deferredMpsBuild = null;
    }
    if(typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();

    // 모바일(특히 iOS Safari) 스크롤-연동 비디오 첫 프레임 렌더 보정.
    // muted+playsinline 비디오라도 .play()가 한 번도 호출되지 않으면 iOS에서
    // 디코더가 깨어나지 않아 video.currentTime 시킹이 화면에 반영되지 않고
    // 영역이 검게 보인다. play().then(pause())로 첫 프레임을 그리게 한 뒤,
    // 이후 스크롤 기반 시킹이 시각적으로 반영되도록 "prime" 한다. 자동재생이
    // 차단된 경우(드묾) 최초 사용자 인터랙션(touchstart/click/scroll)에서 재시도.
    function primeVideoForScrub(video){
        if(!video || video.__primed) return;
        video.__primed = true;

        try { video.setAttribute('webkit-playsinline', ''); } catch(e){}
        try { video.setAttribute('playsinline', ''); } catch(e){}
        video.muted = true;
        video.defaultMuted = true;

        function armRetry(){
            var retried = false;
            function retry(){
                if(retried) return; retried = true;
                document.removeEventListener('touchstart', retry, true);
                document.removeEventListener('click', retry, true);
                document.removeEventListener('scroll', retry, true);
                var pr = null;
                try { pr = video.play(); } catch(e){}
                if(pr && typeof pr.then === 'function'){
                    pr.then(function(){ try{ video.pause(); }catch(e){} }).catch(function(){});
                } else {
                    try { video.pause(); } catch(e){}
                }
            }
            document.addEventListener('touchstart', retry, { passive: true, capture: true });
            document.addEventListener('click', retry, true);
            document.addEventListener('scroll', retry, { passive: true, capture: true });
        }

        function doPrime(){
            var p = null;
            try { p = video.play(); } catch(e){ armRetry(); return; }
            if(p && typeof p.then === 'function'){
                p.then(function(){ try{ video.pause(); }catch(e){} })
                 .catch(function(){ armRetry(); });
            } else {
                try { video.pause(); } catch(e){}
            }
        }

        if(video.readyState >= 1){
            doPrime();
        } else {
            video.addEventListener('loadedmetadata', doPrime, { once: true });
            try { video.load(); } catch(e){}
        }
    }

    // #main_proposal .mpp_vis_vid 비디오 스크롤 연동 재생 + floor1→floor2 전환
    (function(){
        var mppSection = document.getElementById('main_proposal');
        if(!mppSection || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        var mppTop = mppSection.querySelector('.mpp_top');
        var mppFloor1 = mppSection.querySelector('.mpp_bot.floor1');
        var mppFloor2 = mppSection.querySelector('.mpp_bot.floor2');
        var mppVideo = mppFloor1 ? mppFloor1.querySelector('.mpp_vis_vid video') : null;
        if(!mppTop || !mppFloor1 || !mppFloor2 || !mppVideo) return;

        // iOS에서 첫 프레임 렌더 + 시킹 가시성 보장
        primeVideoForScrub(mppVideo);

        gsap.registerPlugin(ScrollTrigger);

        var mppST = null;
        var mppRafId = null;
        var mppTargetTime = 0;
        var mppCurrentTime = 0;
        var mppEase = 0.06;
        var mppInitialized = false;

        // 구간 비율 — 0~0.6: 비디오 재생 / 0.6~0.75: floor1 fadeout / 0.75~0.9: floor2 fadein / 0.9~1: floor2 유지
        var VIDEO_END = 0.4;
        var F1_FADE_START = 0.5;
        var F1_FADE_END = 0.7;
        var F2_FADE_START = 0.7;
        var F2_FADE_END = 0.9;

        // rAF 루프 — 단일 인스턴스만 유지
        function smoothUpdate(){
            var diff = mppTargetTime - mppCurrentTime;
            if(Math.abs(diff) > 0.001){
                mppCurrentTime += diff * mppEase;
                if(mppVideo.readyState >= 2){
                    mppVideo.currentTime = mppCurrentTime;
                }
            }
            mppRafId = requestAnimationFrame(smoothUpdate);
        }

        // ease-in-out 곡선 (부드러운 시작과 끝)
        function easeInOut(t){
            return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
        }

        // floor 전환 상태 업데이트
        function updateFloorTransition(progress){
            // floor1 fadeout
            if(progress <= F1_FADE_START){
                mppFloor1.style.opacity = '1';
                mppFloor1.style.display = 'flex';
            } else if(progress >= F1_FADE_END){
                mppFloor1.style.opacity = '0';
                mppFloor1.style.display = 'none';
            } else {
                var t1 = (progress - F1_FADE_START) / (F1_FADE_END - F1_FADE_START);
                mppFloor1.style.display = 'flex';
                mppFloor1.style.opacity = 1 - easeInOut(t1);
            }

            // floor2 fadein
            if(progress < F2_FADE_START){
                mppFloor2.style.display = 'none';
                mppFloor2.style.opacity = '0';
            } else if(progress >= F2_FADE_END){
                mppFloor2.style.display = 'flex';
                mppFloor2.style.opacity = '1';
            } else {
                var t2 = (progress - F2_FADE_START) / (F2_FADE_END - F2_FADE_START);
                mppFloor2.style.display = 'flex';
                mppFloor2.style.opacity = easeInOut(t2);
            }
        }

        function initVideoScroll(){
            if(mppInitialized) return;
            mppInitialized = true;

            var duration = mppVideo.duration || 1;

            // floor2 초기 상태
            gsap.set(mppFloor2, { display: 'none', opacity: 0 });

            mppST = ScrollTrigger.create({
                trigger: mppSection,
                start: 'top top',
                end: '+=400%',
                pin: true,
                scrub: true,
                invalidateOnRefresh: true,
                onUpdate: function(self){
                    var p = self.progress;
                    // 비디오: 0~VIDEO_END 구간에서 전체 재생
                    var videoProgress = Math.min(p / VIDEO_END, 1);
                    mppTargetTime = videoProgress * duration;
                    // floor 전환
                    updateFloorTransition(p);
                },
                onRefresh: function(self){
                    duration = mppVideo.duration || 1;
                    var p = self.progress;
                    var videoProgress = Math.min(p / VIDEO_END, 1);
                    mppTargetTime = videoProgress * duration;
                    mppCurrentTime = mppTargetTime;
                    if(mppVideo.readyState >= 2){
                        mppVideo.currentTime = mppCurrentTime;
                    }
                    updateFloorTransition(p);
                }
            });

            // 리로드 시 복원된 스크롤 위치에 맞게 초기 동기화
            var initProgress = mppST.progress || 0;
            var videoProgress = Math.min(initProgress / VIDEO_END, 1);
            mppTargetTime = videoProgress * duration;
            mppCurrentTime = mppTargetTime;
            if(mppVideo.readyState >= 2){
                mppVideo.currentTime = mppCurrentTime;
            }
            updateFloorTransition(initProgress);

            // rAF 루프 시작 (중복 방지)
            if(mppRafId) cancelAnimationFrame(mppRafId);
            mppRafId = requestAnimationFrame(smoothUpdate);

            // main_proposal pin-spacer 생성 완료 → #aslan_Transform ScrollTrigger를 이제 생성 (DOM 순서 보장)
            // aslan_Transform이 없거나 빌드 실패 시엔 바로 #newDesign으로 폴백
            if(typeof _deferredAsnBuild === 'function'){
                _deferredAsnBuild();
                _deferredAsnBuild = null;
            } else if(typeof _deferredNdBuild === 'function'){
                _deferredNdBuild();
                _deferredNdBuild = null;
            }
        }

        if(mppVideo.readyState >= 1){
            initVideoScroll();
        } else {
            mppVideo.addEventListener('loadedmetadata', function(){
                initVideoScroll();
            });
        }
    })();

    // #aslan_Transform pin + .asn_ts_vid_pc / .asn_ts_vid_mo 비디오 스크롤 연동 재생
    // ≤476px: mo 비디오, 그 외: pc 비디오 — matchMedia로 활성 비디오 스왑
    (function(){
        var asnSection = document.getElementById('aslan_Transform');
        if(!asnSection || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        var asnVideoPc = asnSection.querySelector('.asn_ts_vid_pc video');
        var asnVideoMo = asnSection.querySelector('.asn_ts_vid_mo video');
        if(!asnVideoPc && !asnVideoMo) return;

        // iOS에서 첫 프레임 렌더 + 시킹 가시성 보장 (pc/mo 둘 다 prime — muted라 안전)
        primeVideoForScrub(asnVideoPc);
        primeVideoForScrub(asnVideoMo);

        gsap.registerPlugin(ScrollTrigger);

        var asnMQ = window.matchMedia('(max-width: 476px)');
        function pickActive(){
            if(asnMQ.matches && asnVideoMo) return asnVideoMo;
            return asnVideoPc || asnVideoMo;
        }

        var asnActive = pickActive();
        var asnST = null;
        var asnRafId = null;
        var asnTargetTime = 0;
        var asnCurrentTime = 0;
        // 기본 lerp 계수 — 작은 점프에선 부드럽게, 큰 점프(빠른 스크롤)에선
        // 아래 adaptive 배수로 더 빠르게 따라잡아 "재생처럼" 흐르게 한다.
        var asnEase = 0.14;
        // 영상 프레임 간격(초). 30fps 가정 — 이보다 작은 차이는 화면상 변화가
        // 거의 없으므로 currentTime 쓰기를 생략해 decode 큐 적체/지터 방지.
        var asnFrameStep = 1 / 30;
        var asnLastWritten = -1;
        var asnInitialized = false;

        function asnSmoothUpdate(){
            var diff = asnTargetTime - asnCurrentTime;
            var absDiff = Math.abs(diff);
            if(absDiff > 0.0005){
                // adaptive ease — 큰 점프일수록 더 빠르게 따라잡되 상한(0.32)을
                // 둬 급격한 seek을 막고 재생하듯 흐르는 느낌을 유지한다.
                var ease = asnEase;
                if(absDiff > 0.5)      ease = 0.32;
                else if(absDiff > 0.2) ease = 0.22;
                asnCurrentTime += diff * ease;

                if(asnActive && asnActive.readyState >= 2){
                    // 프레임 간격(약 33ms)보다 작은 변화는 시각적 차이가 없고
                    // 오히려 decode 큐만 쌓이므로 쓰기를 생략한다.
                    if(Math.abs(asnCurrentTime - asnLastWritten) >= asnFrameStep * 0.5){
                        asnActive.currentTime = asnCurrentTime;
                        asnLastWritten = asnCurrentTime;
                    }
                }
            }
            asnRafId = requestAnimationFrame(asnSmoothUpdate);
        }

        // 비디오 metadata 로드 콜백 — 이미 로드된 경우 동기 호출
        function waitMeta(v, cb){
            if(!v) return;
            if(v.readyState >= 1) return cb();
            v.addEventListener('loadedmetadata', cb, { once: true });
        }

        // main_proposal이 pin-spacer 생성 후 호출 → 여기서 ScrollTrigger(pin) 생성 → 완료 후 _deferredNdBuild 연쇄 호출
        _deferredAsnBuild = function(){
            function buildAsnST(){
                if(asnInitialized) return;
                asnInitialized = true;

                function activeDuration(){
                    return (asnActive && asnActive.duration) ? asnActive.duration : 1;
                }

                asnST = ScrollTrigger.create({
                    trigger: asnSection,
                    start: 'top top',
                    end: '+=750%',
                    pin: true,
                    // scrub 값이 크면 GSAP 측 스무딩이 길어지고, 아래 rAF lerp와
                    // 이중 스무딩이 되어 "뭉개지는" 지연이 누적된다. 실제 영상
                    // 시간 보간은 asnSmoothUpdate가 담당하므로 scrub는 짧게 둬
                    // 스크롤 입력 노이즈만 제거하는 정도로 사용한다.
                    scrub: 0.8,
                    invalidateOnRefresh: true,
                    onToggle: function(self){
                        asnPinActive = self.isActive;
                        // pin 해제 시 보류된 refresh를 rAF에서 안전하게 실행
                        if(!self.isActive && _pendingRefresh){
                            _pendingRefresh = false;
                            requestAnimationFrame(function(){
                                if(asnPinActive || mslPinActive){
                                    _pendingRefresh = true;
                                    return;
                                }
                                if(typeof ScrollTrigger !== 'undefined'){
                                    ScrollTrigger.refresh();
                                    lastDocHeight = document.documentElement.scrollHeight;
                                }
                            });
                        }
                    },
                    onUpdate: function(self){
                        asnTargetTime = self.progress * activeDuration();
                    },
                    onRefresh: function(self){
                        asnTargetTime = self.progress * activeDuration();
                        asnCurrentTime = asnTargetTime;
                        if(asnActive && asnActive.readyState >= 2){
                            asnActive.currentTime = asnCurrentTime;
                        }
                    }
                });

                // 리로드 복원 스크롤 위치에 맞게 초기 동기화
                var initProgress = asnST.progress || 0;
                asnTargetTime = initProgress * activeDuration();
                asnCurrentTime = asnTargetTime;
                if(asnActive && asnActive.readyState >= 2){
                    asnActive.currentTime = asnCurrentTime;
                }

                if(asnRafId) cancelAnimationFrame(asnRafId);
                asnRafId = requestAnimationFrame(asnSmoothUpdate);

                // 미디어쿼리 전환 시 활성 비디오 스왑 + 새 duration 기준으로 time 재동기화
                // 476px 경계에서 .asn_ts_vid_pc/.asn_ts_vid_mo의 display 토글이 일어나면
                // 섹션 내부 레이아웃이 바뀌므로, pin이 비활성일 때는 즉시 refresh로
                // 위치를 재계산하고, pin 활성 중이면 보류(pin 해제 onToggle에서 실행)한다.
                function onMQChange(){
                    asnActive = pickActive();
                    if(!asnST) return;
                    var p = asnST.progress || 0;
                    asnTargetTime = p * activeDuration();
                    asnCurrentTime = asnTargetTime;
                    if(asnActive && asnActive.readyState >= 2){
                        asnActive.currentTime = asnCurrentTime;
                    }
                    if(asnPinActive || mslPinActive){
                        _pendingRefresh = true;
                    } else if(typeof ScrollTrigger !== 'undefined'){
                        ScrollTrigger.refresh();
                    }
                }
                if(typeof asnMQ.addEventListener === 'function'){
                    asnMQ.addEventListener('change', onMQChange);
                } else if(typeof asnMQ.addListener === 'function'){
                    asnMQ.addListener(onMQChange);
                }

                // aslan_Transform pin-spacer 생성 완료 → #newDesign ScrollTrigger 이제 생성 (DOM 순서 보장)
                if(typeof _deferredNdBuild === 'function'){
                    _deferredNdBuild();
                    _deferredNdBuild = null;
                }
            }

            // pin-spacer를 즉시 생성 — 비디오 metadata 로드를 기다리지 않음.
            // 첫 로드 시 metadata가 늦게 로드되면 pin-spacer가 뒤늦게 삽입돼
            // 유저가 이미 해당 섹션을 지나치면서 "빈 공간"만 보이는 현상을 방지.
            // duration을 모를 때는 activeDuration()이 1로 폴백되고, 비디오가
            // 나중에 준비되면 아래 waitMeta 콜백에서 target time을 재동기화한다.
            buildAsnST();

            // 비디오 metadata가 뒤늦게 로드되면 실제 duration 기반으로 time 재동기화
            function resyncTimeFromMeta(){
                if(!asnST) return;
                var p = asnST.progress || 0;
                var dur = (asnActive && asnActive.duration) ? asnActive.duration : 1;
                asnTargetTime = p * dur;
                asnCurrentTime = asnTargetTime;
                if(asnActive && asnActive.readyState >= 2){
                    asnActive.currentTime = asnCurrentTime;
                }
            }
            waitMeta(asnVideoPc, resyncTimeFromMeta);
            waitMeta(asnVideoMo, resyncTimeFromMeta);
        };
    })();

    // 페이지 전역 DOM 변경 감지 → ScrollTrigger 위치 재계산
    // 문서 높이가 실제로 변했을 때만 refresh (header/footer include 등)
    // — 커넥터·캔버스 동적 생성이나 클래스 토글로 인한 불필요한 refresh 방지
    var globalDomTimer = null;
    var globalMOReady = false;
    var lastDocHeight = 0;
    var globalMO = new MutationObserver(function(mutations){
        if(!globalMOReady) return;

        // pin-spacer 내부 변경은 무시
        var dominated = mutations.every(function(m){
            if(!m.target.closest) return false;
            return !!m.target.closest('.pin-spacer');
        });
        if(dominated) return;

        clearTimeout(globalDomTimer);
        globalDomTimer = setTimeout(function(){
            // 문서 높이가 실질적으로 변한 경우에만 refresh
            var newHeight = document.documentElement.scrollHeight;
            if(Math.abs(newHeight - lastDocHeight) < 2) return;
            lastDocHeight = newHeight;
            if(typeof ScrollTrigger !== 'undefined'){
                if(mslPinActive || asnPinActive){
                    _pendingRefresh = true;
                } else {
                    ScrollTrigger.refresh();
                }
            }
        }, 200);
    });
    var wrap = document.getElementById('wrap');
    if(wrap){
        globalMO.observe(wrap, { childList: true, subtree: true });
    }

    // include(header/footer/request) 비동기 fetch 완료 후 ScrollTrigger 재계산
    // fetch()는 window.load에 추적되지 않으므로, 첫 로드/강력 새로고침 시
    // include가 window.load 이후에 완료될 수 있어 pin 위치가 어긋나는 문제 방지
    //
    // 최종 refresh는 반드시 (window.load + includeReady + 모든 이미지 로드) 이후에 수행
    document.addEventListener('includeReady', function(){
        _includesReady = true;

        // window.load가 아직이면, 모든 이미지 로드(window.load)를 기다림
        if(!_windowLoaded){
            window.addEventListener('load', function(){ doFinalRefreshAfterAll(); });
            return;
        }

        // window.load는 이미 완료 — include로 새로 삽입된 이미지만 대기
        doFinalRefreshAfterAll();
    });

    function doFinalRefreshAfterAll(){
        // include로 동적 삽입된 이미지(헤더 로고 등)가 아직 로드 중일 수 있음
        var allImgs = document.querySelectorAll('img');
        var unloaded = [];
        allImgs.forEach(function(img){ if(!img.complete) unloaded.push(img); });

        if(unloaded.length === 0){
            executeFinalRefresh();
            return;
        }

        var remaining = unloaded.length;
        function check(){ if(--remaining <= 0) executeFinalRefresh(); }
        unloaded.forEach(function(img){
            img.addEventListener('load', check);
            img.addEventListener('error', check);
        });
        // 안전장치: 5초 초과 시 강제 refresh
        setTimeout(function(){ if(remaining > 0){ remaining = 0; executeFinalRefresh(); } }, 5000);
    }

    function executeFinalRefresh(){
        // font-display:swap 폰트가 뒤늦게 로드되면 msl_tit 등 텍스트 높이가 변하여
        // ScrollTrigger start 위치가 틀어질 수 있으므로 폰트 로딩 완료를 대기
        function doRefreshWork(){
            requestAnimationFrame(function(){
                requestAnimationFrame(function(){
                    try { drawStep1All(); } catch(e){}
                    try { refreshMainAslanStep2Layout(); } catch(e){}
                    if(typeof ScrollTrigger !== 'undefined'){
                        if(mslPinActive || asnPinActive){
                            _pendingRefresh = true;
                        } else {
                            ScrollTrigger.refresh();
                            lastDocHeight = document.documentElement.scrollHeight;
                        }
                    }
                    if(!globalMOReady){
                        setTimeout(function(){ globalMOReady = true; }, 300);
                    }
                });
            });
        }

        if(document.fonts && document.fonts.ready){
            document.fonts.ready.then(function(){ doRefreshWork(); });
        } else {
            doRefreshWork();
        }
    }

    // #main_proposal .mpp_visual bar chart 삽입
    var mppVisual = document.querySelector('#main_proposal .mpp_bot.floor2 .mpp_visual');
    if(mppVisual){
        var visImg = document.createElement('div');
        visImg.className = 'mpp_vis_img';
        visImg.innerHTML = '<img src="./assets/img/main/mpp_visual_img02.png" alt="#">';
        mppVisual.appendChild(visImg);

        var barChart = document.createElement('div');
        barChart.className = 'mpp_bar_chart';
        barChart.innerHTML =
            '<div class="mpp_bc_grid">' +
                '<div class="mpp_bc_labels">' +
                    '<span>25938291749...</span>' +
                    '<span>12145939520...</span>' +
                    '<span>49292859102...</span>' +
                '</div>' +
                '<div class="mpp_bc_bars">' +
                    '<div class="mpp_bc_bar"></div>' +
                    '<div class="mpp_bc_bar"></div>' +
                    '<div class="mpp_bc_bar"></div>' +
                    '<div class="mpp_bc_bar"></div>' +
                '</div>' +
            '</div>';
        mppVisual.appendChild(barChart);

        var bcLabelSpans = barChart.querySelectorAll('.mpp_bc_labels span');
        if(bcLabelSpans.length){
            function randomDigits(len){
                var s = '';
                for(var i = 0; i < len; i++) s += Math.floor(Math.random() * 10);
                return s;
            }
            setInterval(function(){
                for(var i = 0; i < bcLabelSpans.length; i++){
                    bcLabelSpans[i].textContent = randomDigits(11) + '...';
                }
            }, 100);
        }
    }

});
