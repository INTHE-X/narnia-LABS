$(document).ready(function(){

    // sub_mainTit h2 글자별 blur reveal 애니메이션
    var subMainTitH2 = document.querySelector('.sub_mainTit h2');

    if(subMainTitH2){
        var nodes = Array.from(subMainTitH2.childNodes);
        subMainTitH2.innerHTML = '';

        nodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                subMainTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        subMainTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        subMainTitH2.appendChild(span);
                    }
                }
            }
        });

        var chars = subMainTitH2.querySelectorAll('.char');
        var bgEl = document.querySelector('.subPageType01_bg');
        var sptTdImg = document.querySelector('.spt_td_img');

        function startH2Anim(){
            chars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        // bg → deco → h2 (공통 순서)
        var decoEl = sptTdImg || document.querySelector('.subPage_234_top_deco .blur_txtDeco');

        if(bgEl) bgEl.classList.add('fade_in');
        setTimeout(function(){
            if(decoEl) decoEl.classList.add('fade_in');
            setTimeout(function(){
                startH2Anim();
            }, 800);
        }, 800);
    }

    // subSection_visual02 .divider.top .img fadein → .tit h2 글자별 blur reveal 애니메이션
    var genTopImg = document.querySelector('.subSection_visual02 .divider.top .img');
    var genTitH2 = document.querySelector('.subSection_visual02 .divider.bot .tit h2');

    if(genTitH2){
        var genNodes = Array.from(genTitH2.childNodes);
        genTitH2.innerHTML = '';

        genNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                genTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        genTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        genTitH2.appendChild(span);
                    }
                }
            }
        });

        var genChars = genTitH2.querySelectorAll('.char');

        function startGenH2Anim(){
            genChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        // h2 → img 순서
        var genTotalDuration = 35 * (genChars.length - 1) + 600;
        startGenH2Anim();
        if(genTopImg){
            setTimeout(function(){
                genTopImg.classList.add('fade_in');
            }, genTotalDuration);
        }
    }

    // events_visual .ev_tit h2 글자별 blur reveal 애니메이션
    var evTitH2 = document.querySelector('#events_visual .ev_main .ev_tit h2');

    if(evTitH2){
        var evNodes = Array.from(evTitH2.childNodes);
        evTitH2.innerHTML = '';

        evNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                evTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        evTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        evTitH2.appendChild(span);
                    }
                }
            }
        });

        var evChars = evTitH2.querySelectorAll('.char');

        function startEvH2Anim(){
            evChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        startEvH2Anim();
    }

    // news_visual .tit_area .tit 글자별 blur reveal 애니메이션
    var nvTitH2 = document.querySelector('#news_visual .nv_inner .nv_box .tit_area .tit');

    if(nvTitH2){
        var nvNodes = Array.from(nvTitH2.childNodes);
        nvTitH2.innerHTML = '';

        nvNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                nvTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        nvTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        nvTitH2.appendChild(span);
                    }
                }
            }
        });

        var nvChars = nvTitH2.querySelectorAll('.char');

        function startNvH2Anim(){
            nvChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        startNvH2Anim();
    }

    // #news_visual .nv_txt_slide_area — 1024px 이하일 때 자기 높이의 절반만큼 margin-top 음수 적용
    var nvTxtSlideArea = document.querySelector('#news_visual .nv_txt_slide_area');

    if(nvTxtSlideArea){
        function updateNvTxtSlideAreaMargin(){
            if(window.innerWidth <= 1024){
                nvTxtSlideArea.style.marginTop = '';
                var h = nvTxtSlideArea.offsetHeight;
                nvTxtSlideArea.style.marginTop = (-h / 2) + 'px';
            } else {
                nvTxtSlideArea.style.marginTop = '';
            }
        }
        updateNvTxtSlideAreaMargin();
        window.addEventListener('resize', updateNvTxtSlideAreaMargin);
        window.addEventListener('load', updateNvTxtSlideAreaMargin);
    }

    // Location tab hover
    const locTabItems = document.querySelectorAll('#location .loc_tabBtn ul li');
    const locImgItems = document.querySelectorAll('#location .loc_img');

    if(locTabItems.length){
        locTabItems.forEach(function(li, index){
            // 첫번째 li는 헤더/라벨이므로 hover active 로직에서 제외
            if(index === 0) return;
            li.addEventListener('mouseenter', function(){
                locTabItems.forEach(function(item){ item.classList.remove('active'); });
                locImgItems.forEach(function(item){ item.classList.remove('active'); });
                li.classList.add('active');
                // 첫번째 li가 빠졌으므로 loc_img 인덱스는 -1 보정
                if(locImgItems[index - 1]) locImgItems[index - 1].classList.add('active');
            });
        });
    }

    if(document.querySelector('.history_slide')){
        const slides = document.querySelectorAll('.history_slide .swiper-slide');
        let historySwiper = null;
        function initHistorySlide(){
            const isMobile = window.innerWidth <= 864;
            if(historySwiper) historySwiper.destroy(true, true);
            historySwiper = new Swiper('.history_slide', {
                slidesPerView: isMobile ? 1 : 'auto',
                loop: true,
                loopedSlides: slides.length,
                speed: 600,
                slideToClickedSlide: true,
            });
        }
        initHistorySlide();
        window.addEventListener('resize', initHistorySlide);
    }

    // Events 슬라이드 — 동적 추가/삭제 대응 (destroy → reinit 패턴)
    var evMainSlide = null;
    var evInfoSlide = null;
    var evThumbSlide = null;
    var evSlideAnimating = false;
    var isThumbSyncing = false;

    function getTotalEvSlides(){
        return document.querySelectorAll('.ev_main_slide > .swiper-wrapper > .swiper-slide').length;
    }

    function syncEvSlides(){
        var total = getTotalEvSlides();
        if(!evMainSlide || !evThumbSlide || !evInfoSlide || total < 2) return;

        var idx = evMainSlide.realIndex;
        var thumbIdx = (idx + 1) % total;

        if(!isThumbSyncing){
            isThumbSyncing = true;

            var thumbCurrent = evThumbSlide.realIndex;
            var thumbForward = (thumbIdx - thumbCurrent + total) % total;
            var thumbBackward = (thumbCurrent - thumbIdx + total) % total;

            if(thumbForward === 0){
                isThumbSyncing = false;
            } else if(thumbForward <= thumbBackward){
                (function stepForward(remaining){
                    if(remaining <= 0){ isThumbSyncing = false; return; }
                    evThumbSlide.slideNext(600);
                    if(remaining > 1){
                        setTimeout(function(){ stepForward(remaining - 1); }, 620);
                    } else {
                        isThumbSyncing = false;
                    }
                })(thumbForward);
            } else {
                (function stepBackward(remaining){
                    if(remaining <= 0){ isThumbSyncing = false; return; }
                    evThumbSlide.slidePrev(600);
                    if(remaining > 1){
                        setTimeout(function(){ stepBackward(remaining - 1); }, 620);
                    } else {
                        isThumbSyncing = false;
                    }
                })(thumbBackward);
            }
        }

        evInfoSlide.slideTo(idx, 600);
    }

    function destroyEvSlides(){
        isThumbSyncing = false;
        evSlideAnimating = false;
        if(evMainSlide){ evMainSlide.destroy(true, true); evMainSlide = null; }
        if(evInfoSlide){ evInfoSlide.destroy(true, true); evInfoSlide = null; }
        if(evThumbSlide){ evThumbSlide.destroy(true, true); evThumbSlide = null; }
    }

    function initEvSlides(){
        if(!document.querySelector('.ev_main_slide')) return;

        // 기존 인스턴스가 있으면 파괴 후 재생성
        destroyEvSlides();

        var total = getTotalEvSlides();
        if(total === 0) return;

        // 슬라이드 1개일 경우 loop/autoplay 비활성화
        var useLoop = total > 1;

        evInfoSlide = new Swiper('.ev_info_slide', {
            slidesPerView: 1,
            allowTouchMove: false,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 600,
        });

        evThumbSlide = new Swiper('.ev_thumb_slide', {
            slidesPerView: 2,
            spaceBetween: 20,
            allowTouchMove: false,
            loop: useLoop,
            loopedSlides: useLoop ? total : undefined,
            speed: 600,
        });

        evMainSlide = new Swiper('.ev_main_slide', {
            slidesPerView: 1,
            loop: useLoop,
            loopedSlides: useLoop ? total : undefined,
            speed: 600,
            autoplay: useLoop ? {
                delay: 4000,
                disableOnInteraction: false,
            } : false,
            pagination: {
                el: '.ev_pagination',
                clickable: true,
            },
        });

        if(useLoop){
            evMainSlide.on('slideChange', syncEvSlides);
            syncEvSlides();
        }

        // 썸네일 클릭 시 메인 슬라이드 이동
        var thumbContainer = document.querySelector('.ev_thumb_slide');
        if(thumbContainer){
            thumbContainer.addEventListener('click', function(e){
                var currentTotal = getTotalEvSlides();
                if(evSlideAnimating || currentTotal < 2) return;
                var slide = e.target.closest('.swiper-slide');
                if(!slide) return;
                var clickedReal = parseInt(slide.getAttribute('data-swiper-slide-index'), 10);
                if(isNaN(clickedReal)) return;

                var currentReal = evMainSlide.realIndex;
                if(clickedReal === currentReal) return;

                evSlideAnimating = true;

                var forward = (clickedReal - currentReal + currentTotal) % currentTotal;
                var backward = (currentReal - clickedReal + currentTotal) % currentTotal;
                var steps, direction;

                if(forward <= backward){
                    steps = forward;
                    direction = 'next';
                } else {
                    steps = backward;
                    direction = 'prev';
                }

                (function moveStep(remaining){
                    if(remaining <= 0){
                        evSlideAnimating = false;
                        return;
                    }
                    if(direction === 'next'){
                        evMainSlide.slideNext(600);
                    } else {
                        evMainSlide.slidePrev(600);
                    }
                    if(remaining > 1){
                        setTimeout(function(){ moveStep(remaining - 1); }, 620);
                    } else {
                        setTimeout(function(){ evSlideAnimating = false; }, 620);
                    }
                })(steps);
            });
        }
    }

    // 초기 실행
    initEvSlides();

    // 외부(관리자 페이지 등)에서 슬라이드 DOM 변경 후 호출 가능
    // 사용법: window.evSlideManager.reinit()
    window.evSlideManager = {
        reinit: initEvSlides,
        destroy: destroyEvSlides
    };

    // events_board 필터
    var filterItems = document.querySelectorAll('#events_board .filter_area ul li');
    if(filterItems.length){
        filterItems.forEach(function(li){
            var btn = li.querySelector('button');
            if(btn){
                btn.addEventListener('click', function(){
                    filterItems.forEach(function(item){ item.classList.remove('active'); });
                    li.classList.add('active');
                });
            }
        });
    }

    // events_board 게시판 리스트 링크 클릭 시 상단 이동(#) 방지
    // 리스트는 API로 동적 생성되므로 이벤트 위임 사용
    var evBoard = document.querySelector('#events_board .evb_board_list');
    if(evBoard){
        evBoard.addEventListener('click', function(e){
            var a = e.target.closest('ul li a');
            if(!a || !evBoard.contains(a)) return;
            var href = a.getAttribute('href');
            // href가 비어있거나 '#'로 끝나는 경우에만 기본 동작 차단 (실제 링크는 통과)
            if(!href || href === '#' || href === ''){
                e.preventDefault();
            }
        });
    }

    // case_studies_list 리스트 링크 클릭 시 상단 이동(#) 방지
    // 리스트는 API로 동적 생성될 수 있어 이벤트 위임 사용
    var csdlArea = document.querySelector('#case_studies_list .csdl_area');
    if(csdlArea){
        csdlArea.addEventListener('click', function(e){
            var a = e.target.closest('li > a');
            if(!a || a.parentElement.parentElement !== csdlArea) return;
            var href = a.getAttribute('href');
            // href가 비어있거나 '#'인 경우에만 기본 동작 차단 (실제 링크는 통과)
            if(!href || href === '#' || href === ''){
                e.preventDefault();
            }
        });
    }

    // education_board .tit_area h2 글자별 blur reveal 애니메이션
    var eduTitH2 = document.querySelector('#education_board .tit_area h2');

    if(eduTitH2){
        var eduNodes = Array.from(eduTitH2.childNodes);
        eduTitH2.innerHTML = '';

        eduNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                eduTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        eduTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        eduTitH2.appendChild(span);
                    }
                }
            }
        });

        var eduChars = eduTitH2.querySelectorAll('.char');

        function startEduH2Anim(){
            eduChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        startEduH2Anim();
    }

    // education_board 필터
    var eduFilterItems = document.querySelectorAll('#education_board .filter_area ul li');
    if(eduFilterItems.length){
        eduFilterItems.forEach(function(li){
            var btn = li.querySelector('button');
            if(btn){
                btn.addEventListener('click', function(){
                    eduFilterItems.forEach(function(item){ item.classList.remove('active'); });
                    li.classList.add('active');
                });
            }
        });
    }

    // dep_list 아코디언
    function initDepListAccordion(){
        const depListItems = document.querySelectorAll('.dep_list > ul > li');

        if(depListItems.length){
            var resizeTimer = null;

            // accor_btn 높이를 cate 높이에 맞춤
            function syncAccorBtnHeight(){
                depListItems.forEach(function(li){
                    const cate = li.querySelector('.cate');
                    const accorBtn = li.querySelector('.accor_btn');
                    if(cate && accorBtn){
                        accorBtn.style.height = cate.offsetHeight + 'px';
                    }
                });
            }

            // 닫힌 상태: dep_list_inner 높이를 cate 높이에 맞춤
            function setClosedHeight(){
                depListItems.forEach(function(li){
                    const inner = li.querySelector('.dep_list_inner');
                    const cate = li.querySelector('.cate');
                    if(inner && cate && !li.classList.contains('active')){
                        inner.style.height = cate.offsetHeight + 'px';
                    }
                });
            }

            // 열린 상태: dep_list_inner 자연 높이(height:auto)로 측정 후 반영
            function setOpenedHeight(li){
                const inner = li.querySelector('.dep_list_inner');
                if(!inner) return;

                const prevHeight = inner.style.height;
                const prevTransition = inner.style.transition;
                // 측정 동안 트랜지션 비활성화 + 이전 인라인 height 제거하여 자연 높이 획득
                inner.style.transition = 'none';
                inner.style.height = 'auto';
                const naturalHeight = inner.offsetHeight;
                // 원래 높이로 복원 후 reflow (토글 클릭 시 트랜지션 시작점 확보)
                inner.style.height = prevHeight;
                void inner.offsetHeight;
                inner.style.transition = prevTransition;
                inner.style.height = naturalHeight + 'px';
            }

            // 모든 높이 갱신
            function syncAllHeights(){
                syncAccorBtnHeight();
                depListItems.forEach(function(li){
                    if(li.classList.contains('active')){
                        setOpenedHeight(li);
                    }
                });
                setClosedHeight();
            }

            // transition 일시 비활성화 후 높이 갱신
            function syncWithoutTransition(){
                var inners = document.querySelectorAll('.dep_list .dep_list_inner');
                inners.forEach(function(inner){
                    inner.style.transition = 'none';
                });
                syncAllHeights();
                // reflow 강제 후 transition 복원
                document.body.offsetHeight;
                inners.forEach(function(inner){
                    inner.style.transition = '';
                });
            }

            // 초기 높이 세팅
            syncAllHeights();

            // 이미지 로드 완료 후 높이 재계산
            var depImages = document.querySelectorAll('.dep_list .right .img img');
            depImages.forEach(function(img){
                if(!img.complete){
                    img.addEventListener('load', function(){
                        syncWithoutTransition();
                    });
                }
            });

            // 리사이즈: debounce + transition 비활성화
            window.removeEventListener('resize', window._depListResizeHandler);
            window._depListResizeHandler = function(){
                if(resizeTimer) clearTimeout(resizeTimer);
                syncWithoutTransition();
                resizeTimer = setTimeout(function(){
                    syncWithoutTransition();
                }, 150);
            };
            window.addEventListener('resize', window._depListResizeHandler);

            // accor_btn 클릭
            depListItems.forEach(function(li){
                const accorBtn = li.querySelector('.accor_btn');
                if(accorBtn){
                    // 기존 리스너 제거가 어려우므로 클론하여 교체 (간단한 방식)
                    const newAccorBtn = accorBtn.cloneNode(true);
                    accorBtn.parentNode.replaceChild(newAccorBtn, accorBtn);

                    newAccorBtn.addEventListener('click', function(e){
                        e.preventDefault();
                        if(li.classList.contains('active')){
                            li.classList.remove('active');
                            var inner = li.querySelector('.dep_list_inner');
                            var cate = li.querySelector('.cate');
                            if(inner && cate){
                                inner.style.height = cate.offsetHeight + 'px';
                            }
                        } else {
                            // 다른 li active 해제 및 높이 복원
                            depListItems.forEach(function(otherLi){
                                if(otherLi !== li && otherLi.classList.contains('active')){
                                    otherLi.classList.remove('active');
                                    var otherInner = otherLi.querySelector('.dep_list_inner');
                                    var otherCate = otherLi.querySelector('.cate');
                                    if(otherInner && otherCate){
                                        otherInner.style.height = otherCate.offsetHeight + 'px';
                                    }
                                }
                            });
                            li.classList.add('active');
                            setOpenedHeight(li);
                        }
                    });
                }
            });
        }
    }

    // 초기 실행 및 글로벌 노출
    initDepListAccordion();
    window.depListManager = {
        reinit: initDepListAccordion
    };

    // gene_diagonal 대각 슬라이드 (scroll pin 방식)
    var pinWrap = document.querySelector('.gene_visual_pin');
    var geneDiagonal = document.querySelector('.gene_diagonal');

    if(pinWrap && geneDiagonal){
        var diagItems = geneDiagonal.querySelectorAll('.gene_diagonal_item');
        var diagInfo = geneDiagonal.querySelector('.gene_diagonal_info');
        var diagNum = diagInfo ? diagInfo.querySelector('.num') : null;
        var diagName = diagInfo ? diagInfo.querySelector('h3') : null;
        var diagDesc = diagInfo ? diagInfo.querySelector('p') : null;
        var totalSlides = diagItems.length;
        var prevIndex = -1;

        // morph cube SVG: 각 item 당 선택자 캐싱 (Morphing step 만 존재)
        // 큐브 콘텐츠를 viewBox(541x455) 중앙에 항상 고정하기 위해
        // svg 의 자식 요소를 단일 g(.morph_cube_root) 로 감싼다.
        var SVG_NS = 'http://www.w3.org/2000/svg';
        var morphCubes = Array.prototype.map.call(diagItems, function(item){
            var svg = item.querySelector('.morph_cube_svg');
            if(!svg) return null;

            var root = svg.querySelector('.morph_cube_root');
            if(!root){
                root = document.createElementNS(SVG_NS, 'g');
                root.setAttribute('class', 'morph_cube_root');
                while(svg.firstChild){
                    root.appendChild(svg.firstChild);
                }
                svg.appendChild(root);
            }

            return {
                v: root.querySelector('.morph_cube_v'),
                edges: root.querySelector('.morph_cube_edges'),
                ai: root.querySelector('.morph_cube_ai'),
                root: root
            };
        });

        // item 의 중심 거리(absDiff) 에 따라 큐브 하단부 높이를 1/3 → 1 로 보간
        function updateMorphCube(target, absDiff){
            if(!target) return;
            var p = Math.max(0, Math.min(1, 1 - absDiff));
            var t = 1/3 + (2/3) * p;
            var sideBottomY = 156.88 + 140.987 * t;     // V path 좌/우 끝
            var centerBottomY = 312.455 + 141.263 * t;  // V path 중앙(하단 꼭짓점)
            var sideEdgeY = 156.88 + 140.657 * t;       // 세로 모서리 좌/우 끝
            var centerEdgeY = 312.455 + 141.498 * t;    // 세로 모서리 중앙 끝
            if(target.v){
                target.v.setAttribute('d',
                    'M0.530762 ' + sideBottomY +
                    'L270.475 ' + centerBottomY +
                    'L540.417 ' + sideBottomY);
            }
            if(target.edges){
                target.edges.setAttribute('d',
                    'M0.529297 156.88L0.530994 ' + sideEdgeY +
                    'M540.417 156.88L540.419 ' + sideEdgeY +
                    'M270.473 312.455L270.475 ' + centerEdgeY);
            }
            if(target.ai){
                // 좌측 면 중심 Y 이동량은 면 바닥 이동량(≈141)의 절반. 이 값으로 보정해 면 중앙에 고정
                target.ai.setAttribute('transform', 'translate(0,' + ((t - 1) * 70.56) + ')');
            }
            if(target.root){
                // 큐브 시각 중심 Y(viewBox) = 156.382 + 70.49 * t
                // viewBox 중앙 Y = 227.5
                // 차이만큼 root g 를 translate 해 큐브를 항상 viewBox 중앙에 고정
                var dy = 227.5 - (156.382 + 70.49 * t);
                target.root.setAttribute('transform', 'translate(0,' + dy + ')');
            }
        }

        // 대각 오프셋: 1920px 기준 비율, 뷰포트에 비례하여 동적 계산
        var baseOffsetX = 550;
        var baseOffsetY = 300;
        var baseWidth = 1920;

        function getOffsets(){
            var w = window.innerWidth;
            if(w <= 640){
                return {
                    x: 252,
                    y: 140
                };
            }
            if(w <= 1024){
                var smallRatio = Math.max(0.8, w / 1024);
                return {
                    x: 500 * smallRatio,
                    y: 280 * smallRatio
                };
            }
            var ratio = Math.min(w / baseWidth, 1);
            return {
                x: baseOffsetX * ratio,
                y: baseOffsetY * ratio
            };
        }

        // pin 래퍼 높이 설정: 슬라이드 수 × sticky 자식의 실측 높이(px)
        // 모바일 주소창이 펼쳐지고 접힐 때마다 vh 가 재계산되면서 pinWrap 높이/스크롤
        // 가능 거리/innerHeight 가 동시에 흔들려 progress 가 같은 스크롤 위치에서도
        // 다른 값으로 산출되어 아이템들이 매 프레임 떨리는("드드득") 현상이 발생한다.
        // 한 번 측정한 stickyEl.offsetHeight 를 px 로 고정해 사용하고, 진짜 리사이즈
        // (orientation 등)에서만 재계산한다.
        var stickyEl = document.getElementById('gene_visual');
        var stableVh = (stickyEl && stickyEl.offsetHeight) || window.innerHeight;
        pinWrap.style.height = (totalSlides * stableVh) + 'px';

        var diagTrack = geneDiagonal.querySelector('.gene_diagonal_track');

        // 꼬치 점선: 렌더링된 figure 중심을 지나는 SVG 선을 동적으로 생성
        var skewerSvg = document.createElementNS(SVG_NS, 'svg');
        skewerSvg.setAttribute('class', 'gene_skewer_svg');
        skewerSvg.setAttribute('preserveAspectRatio', 'none');
        var skewerLine = document.createElementNS(SVG_NS, 'line');
        skewerSvg.appendChild(skewerLine);
        if(diagTrack) diagTrack.insertBefore(skewerSvg, diagTrack.firstChild);

        function getSkewerCenter(item, trackRect){
            var target = item.querySelector('figure') || item;
            var rect = target.getBoundingClientRect();
            return {
                x: rect.left - trackRect.left + rect.width / 2,
                y: rect.top - trackRect.top + rect.height / 2
            };
        }

        function updateSkewerLine(){
            if(!diagTrack) return;
            var w = diagTrack.clientWidth;
            var h = diagTrack.clientHeight;
            if(!w || !h) return;
            skewerSvg.setAttribute('viewBox', '0 0 ' + w + ' ' + h);

            var trackRect = diagTrack.getBoundingClientRect();
            var centers = Array.prototype.map.call(diagItems, function(item){
                return getSkewerCenter(item, trackRect);
            }).filter(function(center){
                return isFinite(center.x) && isFinite(center.y);
            });

            if(centers.length < 3) return;

            // 2번째 item(index 1) ~ 마지막 item만 잇는다.
            var start = centers[1];
            var end = centers[centers.length - 1];

            skewerLine.setAttribute('x1', start.x);
            skewerLine.setAttribute('y1', start.y);
            skewerLine.setAttribute('x2', end.x);
            skewerLine.setAttribute('y2', end.y);
        }

        function updateDiagonalSlide(progress){
            var offsets = getOffsets();
            // progress 0~1을 슬라이드 인덱스로 변환
            var maxIndex = totalSlides - 1;
            var exactIndex = progress * maxIndex;
            var activeIndex = Math.min(Math.round(exactIndex), maxIndex);

            diagItems.forEach(function(item, i){
                var diff = i - exactIndex;
                var tx = diff * offsets.x;
                var ty = diff * offsets.y;
                var absDiff = Math.abs(diff);
                var scale = Math.max(0.5, 1 - absDiff * 0.25);
                var opacity = Math.max(0.05, 1 - absDiff * 0.6);

                item.style.transform = 'translate(calc(-50% + ' + tx + 'px), calc(-50% + ' + ty + 'px)) scale(' + scale + ')';
                item.style.opacity = opacity;
                item.style.zIndex = 10 - Math.round(absDiff);

                updateMorphCube(morphCubes[i], absDiff);
            });

            updateSkewerLine();

            // gene_diagonal_item 2번(index 1, Mapping) 활성화 시점부터 점선을 위에서 내려오며 표시
            if(activeIndex >= 1){
                skewerSvg.classList.add('is_active');
            } else {
                skewerSvg.classList.remove('is_active');
            }

            // 정보 영역 업데이트 (인덱스 변경 시만)
            if(activeIndex !== prevIndex && diagInfo){
                prevIndex = activeIndex;
                var currentItem = diagItems[activeIndex];
                if(diagNum) diagNum.textContent = '[ ' + (activeIndex + 1) + ' ]';
                if(diagName) diagName.textContent = currentItem.dataset.name || '';
                if(diagDesc) diagDesc.textContent = currentItem.dataset.desc || '';

                // fade-up 애니메이션 재시작 (클래스 제거 후 reflow → 재추가)
                diagInfo.classList.remove('fade_up');
                void diagInfo.offsetWidth;
                diagInfo.classList.add('fade_up');
            }
        }

        function computeAndUpdate(){
            var rect = pinWrap.getBoundingClientRect();
            // window.innerHeight 대신 캐시된 stableVh 사용: 주소창 변동에 무관
            var scrollable = pinWrap.offsetHeight - stableVh;
            if(scrollable <= 0) return;

            var progress = Math.max(0, Math.min(1, -rect.top / scrollable));
            updateDiagonalSlide(progress);
        }

        // 스크롤 이벤트는 모바일에서 프레임당 여러 번 발생할 수 있어
        // requestAnimationFrame 으로 프레임당 1회로 스로틀한다.
        var scrollScheduled = false;
        function onScroll(){
            if(scrollScheduled) return;
            scrollScheduled = true;
            requestAnimationFrame(function(){
                scrollScheduled = false;
                computeAndUpdate();
            });
        }

        function recomputeLayout(){
            var newVh = (stickyEl && stickyEl.offsetHeight) || window.innerHeight;
            // 모바일 주소창 토글로 인한 미세한 innerHeight 변화(보통 50~100px)는 무시.
            // orientation change 등 실제 리사이즈에서만 재계산한다.
            if(Math.abs(newVh - stableVh) > 120){
                stableVh = newVh;
                pinWrap.style.height = (totalSlides * stableVh) + 'px';
            }
            computeAndUpdate();
        }

        // 초기 배치
        computeAndUpdate();

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', recomputeLayout);
        window.addEventListener('load', recomputeLayout);
    }

    // solution_overview fadein 애니메이션
    var slOvSection = document.querySelector('.solution_overview');

    if(slOvSection){
        var slOvMainImg = slOvSection.querySelector('.main_img');
        var slOvDecos = slOvSection.querySelectorAll('.sl_ov_deco');

        // 숫자 순서대로 정렬
        var sortedDecos = Array.from(slOvDecos).sort(function(a, b){
            var numA = parseInt(a.className.match(/num(\d+)/)[1], 10);
            var numB = parseInt(b.className.match(/num(\d+)/)[1], 10);
            return numA - numB;
        });

        var slOvTriggered = false;

        function checkSlOvFadeIn(){
            if(slOvTriggered) return;
            if(!slOvMainImg) return;

            var rect = slOvMainImg.getBoundingClientRect();
            var imgMiddle = rect.top + rect.height / 2;

            if(imgMiddle < window.innerHeight){
                slOvTriggered = true;

                // main_img 먼저 fadein
                slOvMainImg.classList.add('fade_in');

                // sl_ov_deco 순서대로 fadein (main_img 트랜지션 후)
                sortedDecos.forEach(function(deco, index){
                    setTimeout(function(){
                        deco.classList.add('fade_in');
                    }, 800 + (index * 300));
                });
            }
        }

        window.addEventListener('load', checkSlOvFadeIn);
        window.addEventListener('scroll', checkSlOvFadeIn);
    }

    // aslan_platform .aspb_t_deco fadein (visual_area 이미지 진입 시 num 순서대로)
    var aspVisualArea = document.querySelector('#aslan_platform .aspb_t_item.visual_area .img_area');

    if(aspVisualArea){
        var aspDecos = aspVisualArea.querySelectorAll('.aspb_t_deco');

        if(aspDecos.length){
            var sortedAspDecos = Array.from(aspDecos).sort(function(a, b){
                var numA = parseInt(a.className.match(/num(\d+)/)[1], 10);
                var numB = parseInt(b.className.match(/num(\d+)/)[1], 10);
                return numA - numB;
            });

            var aspDecoTriggered = false;

            function checkAspDecoFadeIn(){
                if(window.innerWidth <= 1024){
                    // 1024 이하: 각 deco의 bottom이 viewport bottom에 닿을 때 개별 발동
                    sortedAspDecos.forEach(function(deco){
                        if(deco.classList.contains('fade_in')) return;
                        var decoRect = deco.getBoundingClientRect();
                        if(decoRect.bottom <= window.innerHeight){
                            deco.classList.add('fade_in');
                        }
                    });
                    return;
                }

                if(aspDecoTriggered) return;

                var rect = aspVisualArea.getBoundingClientRect();
                var imgMiddle = rect.top + rect.height / 2;

                if(imgMiddle < window.innerHeight){
                    aspDecoTriggered = true;

                    sortedAspDecos.forEach(function(deco, index){
                        setTimeout(function(){
                            deco.classList.add('fade_in');
                        }, 400 + (index * 300));
                    });
                }
            }

            window.addEventListener('load', checkAspDecoFadeIn);
            window.addEventListener('scroll', checkAspDecoFadeIn);
            window.addEventListener('resize', checkAspDecoFadeIn);
        }
    }

    // sl_vis_container .vis_img_box fadein (인덱스별 딜레이)
    var slVisImgBoxes = document.querySelectorAll('.sl_vis_container .vis_img .vis_img_box');

    if(slVisImgBoxes.length){
        function checkSlVisImgFadeIn(){
            slVisImgBoxes.forEach(function(box, index){
                if(box.classList.contains('fade_in')) return;

                var rect = box.getBoundingClientRect();
                var imgMiddle = rect.top + rect.height / 2;

                if(imgMiddle < window.innerHeight){
                    setTimeout(function(){
                        box.classList.add('fade_in');
                    }, index * 200);
                }
            });
        }

        window.addEventListener('load', checkSlVisImgFadeIn);
        window.addEventListener('scroll', checkSlVisImgFadeIn);
    }

    // solution_info_list .sil_inner fadein (viewport bottom이 .img 중앙에 도달 시)
    var gilItems = document.querySelectorAll('.solution_info_list ul li');

    if(gilItems.length){
        function checkGilFadeIn(){
            gilItems.forEach(function(li, index){
                var gilInner = li.querySelector('.sil_inner');
                var img = li.querySelector('.sil_inner .img');
                if(!gilInner || !img || gilInner.classList.contains('fade_in')) return;

                var rect = img.getBoundingClientRect();
                var imgMiddle = rect.top + rect.height / 2;

                if(imgMiddle < window.innerHeight){
                    setTimeout(function(){
                        gilInner.classList.add('fade_in');
                    }, index * 200);
                }
            });
        }

        window.addEventListener('load', checkGilFadeIn);
        window.addEventListener('scroll', checkGilFadeIn);
    }

    // aslan_flow_list .afl_inner fadein
    var aflLists = document.querySelectorAll('.aslan_flow_list');

    if(aflLists.length){
        function checkAflFadeIn(){
            aflLists.forEach(function(list){
                var items = list.querySelectorAll('ul li');
                items.forEach(function(li, index){
                    var aflInner = li.querySelector('.afl_inner');
                    var img = li.querySelector('.afl_inner .img');
                    if(!aflInner || !img || aflInner.classList.contains('fade_in')) return;

                    var rect = img.getBoundingClientRect();
                    var imgMiddle = rect.top + rect.height / 2;

                    if(imgMiddle < window.innerHeight){
                        setTimeout(function(){
                            aflInner.classList.add('fade_in');
                        }, index * 200);
                    }
                });
            });
        }

        window.addEventListener('load', checkAflFadeIn);
        window.addEventListener('scroll', checkAflFadeIn);
    }

    // aslan_sub_intro .visual_box fadein (복수 대응)
    var aslanVisualBoxes = document.querySelectorAll('.aslan_sub_intro .visual_box');

    if(aslanVisualBoxes.length){
        var aslanVbTriggered = [];
        aslanVisualBoxes.forEach(function(){ aslanVbTriggered.push(false); });

        function checkAslanVbFadeIn(){
            aslanVisualBoxes.forEach(function(box, i){
                if(aslanVbTriggered[i]) return;

                var vbImg = box.querySelector('.img');
                if(!vbImg) return;

                var rect = vbImg.getBoundingClientRect();
                var imgMiddle = rect.top + rect.height / 2;

                if(imgMiddle < window.innerHeight){
                    aslanVbTriggered[i] = true;

                    // img 먼저 fadein
                    vbImg.classList.add('fade_in');

                    // deco_txt, img_desc 순서대로 fadein
                    var decos = box.querySelectorAll('.deco_txt.wh');
                    var desc = box.querySelector('.img_desc');
                    var delay = 800;

                    decos.forEach(function(deco, index){
                        setTimeout(function(){
                            deco.classList.add('fade_in');
                        }, delay + (index * 300));
                    });

                    if(desc){
                        setTimeout(function(){
                            desc.classList.add('fade_in');
                        }, delay + (decos.length * 300));
                    }
                }
            });
        }

        window.addEventListener('load', checkAslanVbFadeIn);
        window.addEventListener('scroll', checkAslanVbFadeIn);
    }

    // 숫자 카운팅 효과 (각 strong 기준, window load 후 체크)
    var countTargets = document.querySelectorAll('#team_info .team_info_list li .desc .txt_area .bot strong');

    if(countTargets.length){
        function animateCount(el, target, duration){
            var startTime = null;

            function step(timestamp){
                if(!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                var current = Math.round(eased * target);
                el.firstChild.nodeValue = current;
                if(progress < 1){
                    requestAnimationFrame(step);
                }
            }

            requestAnimationFrame(step);
        }

        var countData = [];

        countTargets.forEach(function(strong){
            var text = strong.textContent.trim();
            var match = text.match(/^(\d+)/);
            if(!match) return;

            var targetNum = parseInt(match[1], 10);
            strong.firstChild.nodeValue = '0';
            strong.dataset.counted = 'false';
            countData.push(strong);

            strong._targetNum = targetNum;
        });

        function checkCount(strong){
            if(strong.dataset.counted === 'true') return;
            var rect = strong.getBoundingClientRect();
            if(rect.top < window.innerHeight && rect.bottom > 0){
                strong.dataset.counted = 'true';
                animateCount(strong, strong._targetNum, 1500);
            }
        }

        function checkAll(){
            countData.forEach(checkCount);
        }

        window.addEventListener('load', checkAll);
        window.addEventListener('scroll', checkAll);
    }

    // education_detail input placeholder 처리
    var eddInputs = document.querySelectorAll('#education_detail .edd_input_area .edd_txt_input');
    if(eddInputs.length){
        eddInputs.forEach(function(input){
            var placeholder = input.closest('.edd_input_area').querySelector('.edd_input_placeholder');
            var formStack = input.closest('.edd_form_stack');
            if(!placeholder) return;

            function togglePlaceholder(){
                if(input === document.activeElement || input.value.trim() !== ''){
                    placeholder.style.display = 'none';
                } else {
                    placeholder.style.display = '';
                }
            }

            input.addEventListener('focus', function(){
                togglePlaceholder();
                if(formStack) formStack.classList.add('active');
            });
            input.addEventListener('blur', function(){
                togglePlaceholder();
                if(formStack) formStack.classList.remove('active');
            });
            input.addEventListener('input', togglePlaceholder);
        });
    }

    // publication .pb_tit h2 글자별 blur reveal 애니메이션
    var pbTitH2 = document.querySelector('#publication .pb_tit h2');

    if(pbTitH2){
        var pbNodes = Array.from(pbTitH2.childNodes);
        pbTitH2.innerHTML = '';

        pbNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                pbTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        pbTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        pbTitH2.appendChild(span);
                    }
                }
            }
        });

        var pbChars = pbTitH2.querySelectorAll('.char');

        function startPbH2Anim(){
            pbChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        startPbH2Anim();
    }

    // techBlog .tbm_tit h2 글자별 blur reveal 애니메이션
    var tbmTitH2 = document.querySelector('#techBlog_main .tbm_tit h2');

    if(tbmTitH2){
        var tbmNodes = Array.from(tbmTitH2.childNodes);
        tbmTitH2.innerHTML = '';

        tbmNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                tbmTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        tbmTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        tbmTitH2.appendChild(span);
                    }
                }
            }
        });

        var tbmChars = tbmTitH2.querySelectorAll('.char');

        function startTbmH2Anim(){
            tbmChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });

            var totalDuration = 35 * Math.max(tbmChars.length - 1, 0) + 600;
            setTimeout(function(){
                var tbmTxtArea = document.querySelector('#techBlog_main .tbm_news_area .txt_area');
                if(tbmTxtArea) tbmTxtArea.classList.add('fade_in');
            }, totalDuration);
        }

        startTbmH2Anim();
    }

    // techBlog_contents 필터
    var tbcFilterItems = document.querySelectorAll('#techBlog_contents .tba_top .filter_area ul li');
    if(tbcFilterItems.length){
        tbcFilterItems.forEach(function(li){
            var btn = li.querySelector('button');
            if(btn){
                btn.addEventListener('click', function(){
                    tbcFilterItems.forEach(function(item){ item.classList.remove('active'); });
                    li.classList.add('active');
                });
            }
        });
    }

    // publication 공용 함수 (스코프 공유)
    var pbUpdateThumb = null;
    var pbAlignSlideBox = null;

    // publication 커스텀 스크롤바
    var pbapInner = document.querySelector('#publication .pbap_inner');
    var scrollThumb = document.querySelector('#publication .custom_scrollbar .scroll_thumb');

    if(pbapInner && scrollThumb){
        function updateThumb(){
            var scrollHeight = pbapInner.scrollHeight;
            var clientHeight = pbapInner.clientHeight;
            if(scrollHeight <= clientHeight) {
                scrollThumb.style.display = 'none';
                return;
            }
            scrollThumb.style.display = 'block';
            var thumbHeight = Math.max(clientHeight * (clientHeight / scrollHeight), 30);
            var scrollRatio = pbapInner.scrollTop / (scrollHeight - clientHeight);
            var thumbTop = scrollRatio * (clientHeight - thumbHeight);
            scrollThumb.style.height = thumbHeight + 'px';
            scrollThumb.style.top = thumbTop + 'px';
        }

        pbUpdateThumb = updateThumb;
        pbapInner.addEventListener('scroll', updateThumb);
        updateThumb();

        // 동적 콘텐츠 변경 감지
        var pbapObserver = new MutationObserver(updateThumb);
        pbapObserver.observe(pbapInner, { childList: true, subtree: true });

        // thumb 드래그
        var isDragging = false;
        var dragStartY = 0;
        var dragStartScroll = 0;

        scrollThumb.addEventListener('mousedown', function(e){
            isDragging = true;
            dragStartY = e.clientY;
            dragStartScroll = pbapInner.scrollTop;
            e.preventDefault();
        });

        scrollThumb.addEventListener('touchstart', function(e){
            isDragging = true;
            dragStartY = e.touches[0].clientY;
            dragStartScroll = pbapInner.scrollTop;
        }, {passive: true});

        function onDragMove(clientY){
            if(!isDragging) return;
            var scrollHeight = pbapInner.scrollHeight;
            var clientHeight = pbapInner.clientHeight;
            var thumbHeight = Math.max(clientHeight * (clientHeight / scrollHeight), 30);
            var trackHeight = clientHeight - thumbHeight;
            var deltaY = clientY - dragStartY;
            var scrollDelta = deltaY * ((scrollHeight - clientHeight) / trackHeight);
            pbapInner.scrollTop = dragStartScroll + scrollDelta;
        }

        document.addEventListener('mousemove', function(e){
            onDragMove(e.clientY);
        });

        document.addEventListener('touchmove', function(e){
            onDragMove(e.touches[0].clientY);
        }, {passive: true});

        document.addEventListener('mouseup', function(){ isDragging = false; });
        document.addEventListener('touchend', function(){ isDragging = false; });
    }

    // publication pb_slide_box padding-top 정렬
    var pbSlideBox = document.querySelector('#publication .pb_slide_box');
    var pbArticlePagination = document.querySelector('#publication .pb_article_pagination');

    if(pbSlideBox && pbArticlePagination){
        function alignSlideBox(){
            if(window.innerWidth <= 1520){
                pbSlideBox.style.paddingTop = '';
                return;
            }
            var paginationTop = pbArticlePagination.getBoundingClientRect().top;
            var slideBoxParentTop = pbSlideBox.parentElement.getBoundingClientRect().top;
            var slideBoxPaddingTop = parseFloat(getComputedStyle(pbSlideBox.parentElement).paddingTop) || 0;
            var targetPadding = paginationTop - slideBoxParentTop - slideBoxPaddingTop;
            pbSlideBox.style.paddingTop = Math.max(0, targetPadding) + 'px';
        }
        pbAlignSlideBox = alignSlideBox;
        alignSlideBox();
        window.addEventListener('resize', alignSlideBox);
    }

    // publication 슬라이드 네비게이션
    var pbBulletArea = document.querySelector('#publication .pbap_bullet_area');
    var pbSlideWrapper = document.querySelector('#publication .pb_slide_wrapper');

    if(pbBulletArea && pbSlideWrapper){
        var currentVisibleIndex = 0;
        var pbSlideUpdating = false;

        function isPbStackedMode(){
            return window.innerWidth <= 1520;
        }

        // display:none이 아닌 보이는 요소만 반환 (스택 모드에서는 CSS로 숨겨진 슬라이드도 논리적 대상이므로 전체 반환)
        function getVisibleSlides(){
            var all = Array.from(pbSlideWrapper.querySelectorAll('.pb_slide'));
            if(isPbStackedMode()) return all;
            return all.filter(function(s){
                return s.offsetParent !== null || s.offsetHeight > 0;
            });
        }

        function getVisibleBullets(){
            return Array.from(pbBulletArea.querySelectorAll(':scope > li')).filter(function(li){
                return li.offsetParent !== null || li.offsetHeight > 0;
            });
        }

        function getVisibleSlideTop(visibleSlides, index){
            var gap = parseFloat(getComputedStyle(pbSlideWrapper).gap) || 0;
            var top = 0;
            for(var i = 0; i < index; i++){
                if(!visibleSlides[i]) break;
                top += visibleSlides[i].offsetHeight + gap;
            }
            return top;
        }

        function goToSlide(index){
            var visibleSlides = getVisibleSlides();
            var visibleBullets = getVisibleBullets();
            if(!visibleSlides.length) return;
            if(index < 0 || index >= visibleSlides.length) index = 0;
            currentVisibleIndex = index;

            if(isPbStackedMode()){
                pbSlideWrapper.style.transition = 'none';
                pbSlideWrapper.style.transform = '';
            } else {
                pbSlideWrapper.style.transition = 'transform 0.8s cubic-bezier(0.76, 0, 0.24, 1)';
                pbSlideWrapper.style.transform = 'translateY(' + (-getVisibleSlideTop(visibleSlides, index)) + 'px)';
            }

            // class 변경 시 observer 무시
            pbSlideUpdating = true;

            // 모든 슬라이드 상태 초기화 (숨겨진 것 포함)
            pbSlideWrapper.querySelectorAll('.pb_slide').forEach(function(s){
                s.classList.remove('active', 'prev', 'next');
            });
            visibleSlides[index].classList.add('active');
            if(visibleSlides[index - 1]) visibleSlides[index - 1].classList.add('prev');
            if(visibleSlides[index + 1]) visibleSlides[index + 1].classList.add('next');

            // 불릿 active (보이는 것 기준)
            pbBulletArea.querySelectorAll(':scope > li').forEach(function(li){
                li.classList.remove('active');
            });
            if(visibleBullets[index]) visibleBullets[index].classList.add('active');

            setTimeout(function(){ pbSlideUpdating = false; }, 0);
        }

        var pbapInner = document.querySelector('#publication .pbap_inner');
        var pbapScrollAnim = null;

        function scrollBulletToLeft(li, instant){
            if(!pbapInner || !li) return;
            if(!isPbStackedMode()) return;
            var innerRect = pbapInner.getBoundingClientRect();
            var liRect = li.getBoundingClientRect();
            var from = pbapInner.scrollLeft;
            var to = from + (liRect.left - innerRect.left);
            var maxScroll = pbapInner.scrollWidth - pbapInner.clientWidth;
            if(to < 0) to = 0;
            if(to > maxScroll) to = maxScroll;

            if(pbapScrollAnim){ cancelAnimationFrame(pbapScrollAnim); pbapScrollAnim = null; }
            if(pbapMomentumAnim){ cancelAnimationFrame(pbapMomentumAnim); pbapMomentumAnim = null; }

            var distance = Math.abs(to - from);
            if(instant || distance < 1){
                pbapInner.scrollLeft = to;
                return;
            }

            // 거리에 비례한 동적 duration (380~780ms 범위), easeOutQuint 로 부드러운 감속
            var duration = Math.min(780, Math.max(380, 280 + distance * 0.55));
            var startTime = null;

            function ease(t){ return 1 - Math.pow(1 - t, 5); }

            function step(ts){
                if(startTime === null) startTime = ts;
                var elapsed = ts - startTime;
                var t = Math.min(1, elapsed / duration);
                pbapInner.scrollLeft = from + (to - from) * ease(t);
                if(t < 1){
                    pbapScrollAnim = requestAnimationFrame(step);
                } else {
                    pbapScrollAnim = null;
                }
            }
            pbapScrollAnim = requestAnimationFrame(step);
        }

        // pbap_inner: 가로 스크롤 잔량에 따라 edge shadow 토글 (1520 이하)
        if(pbapInner){
            var pbapShadowMQ = window.matchMedia('(max-width: 1520px)');
            function pbapUpdateShadow(){
                if(!pbapShadowMQ.matches){
                    pbapInner.classList.remove('has-left-scroll', 'has-right-scroll');
                    return;
                }
                var max = pbapInner.scrollWidth - pbapInner.clientWidth;
                pbapInner.classList.toggle('has-left-scroll', pbapInner.scrollLeft > 1);
                pbapInner.classList.toggle('has-right-scroll', max > 1 && pbapInner.scrollLeft < max - 1);
            }
            var pbapShadowTicking = false;
            function pbapShadowOnScroll(){
                if(pbapShadowTicking) return;
                pbapShadowTicking = true;
                window.requestAnimationFrame(function(){ pbapUpdateShadow(); pbapShadowTicking = false; });
            }
            pbapInner.addEventListener('scroll', pbapShadowOnScroll, { passive: true });
            window.addEventListener('resize', pbapShadowOnScroll, { passive: true });
            window.addEventListener('load', pbapUpdateShadow);
            if(pbapShadowMQ.addEventListener){
                pbapShadowMQ.addEventListener('change', pbapUpdateShadow);
            }
            if(window.ResizeObserver){
                var pbapShadowRO = new ResizeObserver(pbapUpdateShadow);
                pbapShadowRO.observe(pbapInner);
            }
            pbapUpdateShadow();
        }

        // pbap_inner: Swiper 스타일 마우스 드래그 (stacked 모드 전용)
        if(pbapInner){
            var pbapDragActive = false;
            var pbapDragStartX = 0;
            var pbapDragStartScroll = 0;
            var pbapDragSamples = []; // 최근 {x, t} 샘플, velocity 계산용 moving window
            var pbapDragMoved = false;
            var pbapMomentumAnim = null;
            var PBAP_DRAG_THRESHOLD = 5;
            var PBAP_VELOCITY_WINDOW = 100;  // ms — jitter 완화를 위한 샘플 윈도우
            var PBAP_MAX_VELOCITY = 2.5;     // px/ms — 비정상 속도 캡
            var PBAP_MIN_VELOCITY = 0.04;    // px/ms — 정지 임계치

            function pbapCancelMomentum(){
                if(pbapMomentumAnim){
                    cancelAnimationFrame(pbapMomentumAnim);
                    pbapMomentumAnim = null;
                }
            }

            function pbapComputeVelocity(){
                if(pbapDragSamples.length < 2) return 0;
                var now = performance.now();
                var cutoff = now - PBAP_VELOCITY_WINDOW;
                var samples = [];
                for(var i = 0; i < pbapDragSamples.length; i++){
                    if(pbapDragSamples[i].t >= cutoff) samples.push(pbapDragSamples[i]);
                }
                if(samples.length < 2) samples = pbapDragSamples.slice(-2);
                var first = samples[0];
                var last = samples[samples.length - 1];
                var dt = last.t - first.t;
                if(dt <= 0) return 0;
                return (last.x - first.x) / dt; // px/ms, 양수=우측 이동
            }

            function pbapStartMomentum(velocity){
                pbapCancelMomentum();
                if(Math.abs(velocity) < PBAP_MIN_VELOCITY) return;
                if(velocity > PBAP_MAX_VELOCITY) velocity = PBAP_MAX_VELOCITY;
                else if(velocity < -PBAP_MAX_VELOCITY) velocity = -PBAP_MAX_VELOCITY;
                var lastTime = performance.now();
                function step(ts){
                    var dt = ts - lastTime;
                    lastTime = ts;
                    if(dt > 40) dt = 40; // 탭 전환 등 긴 간격 방어
                    // 시간 기반 지수 감쇠 (1초에 약 100배 감쇠 → 자연스러운 decelerate)
                    var decay = Math.pow(0.01, dt / 1000);
                    pbapInner.scrollLeft -= velocity * dt;
                    velocity *= decay;
                    var maxScroll = pbapInner.scrollWidth - pbapInner.clientWidth;
                    if(pbapInner.scrollLeft <= 0 || pbapInner.scrollLeft >= maxScroll){
                        pbapMomentumAnim = null;
                        return;
                    }
                    if(Math.abs(velocity) < PBAP_MIN_VELOCITY){
                        pbapMomentumAnim = null;
                        return;
                    }
                    pbapMomentumAnim = requestAnimationFrame(step);
                }
                pbapMomentumAnim = requestAnimationFrame(step);
            }

            pbapInner.addEventListener('mousedown', function(e){
                if(e.button !== 0) return;
                if(!isPbStackedMode()) return;
                pbapCancelMomentum();
                if(pbapScrollAnim){ cancelAnimationFrame(pbapScrollAnim); pbapScrollAnim = null; }
                pbapDragActive = true;
                pbapDragMoved = false;
                pbapDragStartX = e.clientX;
                pbapDragStartScroll = pbapInner.scrollLeft;
                pbapDragSamples = [{ x: e.clientX, t: performance.now() }];
            });

            window.addEventListener('mousemove', function(e){
                if(!pbapDragActive) return;
                var dx = e.clientX - pbapDragStartX;
                if(!pbapDragMoved && Math.abs(dx) > PBAP_DRAG_THRESHOLD){
                    pbapDragMoved = true;
                    pbapInner.classList.add('dragging');
                }
                if(pbapDragMoved){
                    e.preventDefault();
                    pbapInner.scrollLeft = pbapDragStartScroll - dx;
                    var now = performance.now();
                    pbapDragSamples.push({ x: e.clientX, t: now });
                    var cutoff = now - PBAP_VELOCITY_WINDOW;
                    while(pbapDragSamples.length > 2 && pbapDragSamples[0].t < cutoff){
                        pbapDragSamples.shift();
                    }
                }
            });

            window.addEventListener('mouseup', function(e){
                if(!pbapDragActive) return;
                var wasMoved = pbapDragMoved;
                pbapDragActive = false;
                pbapInner.classList.remove('dragging');
                if(wasMoved){
                    pbapStartMomentum(pbapComputeVelocity());
                }
            });

            // 드래그 후 발생하는 click은 bullet click으로 전달되지 않도록 차단
            pbapInner.addEventListener('click', function(e){
                if(pbapDragMoved){
                    e.stopPropagation();
                    e.preventDefault();
                    pbapDragMoved = false;
                }
            }, true);

            // 링크의 native dragstart가 우리 드래그를 방해하지 않도록
            pbapInner.addEventListener('dragstart', function(e){
                if(pbapDragActive) e.preventDefault();
            });
        }

        // 이벤트 위임
        pbBulletArea.addEventListener('click', function(e){
            var link = e.target.closest('a');
            if(!link) return;
            e.preventDefault();
            var li = link.closest('li');
            if(!li) return;
            var visibleBullets = getVisibleBullets();
            var index = visibleBullets.indexOf(li);
            if(index === -1) return;
            goToSlide(index);
            scrollBulletToLeft(li);
        });

        window.addEventListener('resize', function(){
            var visibleSlides = getVisibleSlides();
            if(!visibleSlides.length) return;
            if(currentVisibleIndex >= visibleSlides.length) currentVisibleIndex = 0;
            pbSlideWrapper.style.transition = 'none';
            if(isPbStackedMode()){
                pbSlideWrapper.style.transform = '';
                var activeBullet = pbBulletArea.querySelector(':scope > li.active');
                if(activeBullet) scrollBulletToLeft(activeBullet, true);
            } else {
                pbSlideWrapper.style.transform = 'translateY(' + (-getVisibleSlideTop(visibleSlides, currentVisibleIndex)) + 'px)';
                // stacked → non-stacked 전환: 드래그/관성 상태 정리
                if(pbapInner){
                    if(pbapMomentumAnim){ cancelAnimationFrame(pbapMomentumAnim); pbapMomentumAnim = null; }
                    if(pbapScrollAnim){ cancelAnimationFrame(pbapScrollAnim); pbapScrollAnim = null; }
                    pbapDragActive = false;
                    pbapDragMoved = false;
                    pbapDragSamples = [];
                    pbapInner.classList.remove('dragging');
                }
            }
        });

        // 변경 감지 공통 핸들러 (display 변경 + DOM 변경)
        function handleSlideChange(){
            if(pbSlideUpdating) return;
            var visibleSlides = getVisibleSlides();
            if(!visibleSlides.length){
                pbSlideWrapper.style.transition = 'none';
                pbSlideWrapper.style.transform = 'translateY(0)';
                currentVisibleIndex = 0;
                return;
            }

            // 현재 active 슬라이드가 숨겨졌거나 DOM에서 제거됐으면 첫 번째로 이동
            var activeSlide = pbSlideWrapper.querySelector('.pb_slide.active');
            var isActiveVisible = activeSlide && (activeSlide.offsetParent !== null || activeSlide.offsetHeight > 0);

            if(!isActiveVisible || currentVisibleIndex >= visibleSlides.length){
                goToSlide(0);
            } else {
                // 보이는 슬라이드 순서가 바뀌었을 수 있으므로 위치 재계산
                var newIndex = visibleSlides.indexOf(activeSlide);
                if(newIndex === -1) newIndex = 0;
                currentVisibleIndex = newIndex;
                pbSlideWrapper.style.transition = 'none';
                if(isPbStackedMode()){
                    pbSlideWrapper.style.transform = '';
                } else {
                    pbSlideWrapper.style.transform = 'translateY(' + (-getVisibleSlideTop(visibleSlides, currentVisibleIndex)) + 'px)';
                }
            }

            // alignSlideBox, scrollbar 연동 갱신
            if(pbAlignSlideBox) pbAlignSlideBox();
            if(pbUpdateThumb) pbUpdateThumb();
        }

        // display/class 변경 감지 (개별 아이템)
        var pbVisibilityObserver = new MutationObserver(handleSlideChange);

        // 개별 슬라이드·불릿 요소 관찰 등록
        function observeSlideItems(){
            pbVisibilityObserver.disconnect();
            pbSlideWrapper.querySelectorAll('.pb_slide').forEach(function(slide){
                pbVisibilityObserver.observe(slide, { attributes: true, attributeFilter: ['style', 'class'] });
            });
            pbBulletArea.querySelectorAll(':scope > li').forEach(function(li){
                pbVisibilityObserver.observe(li, { attributes: true, attributeFilter: ['style', 'class'] });
            });
        }
        observeSlideItems();

        // DOM 자체 변경 감지 (슬라이드·불릿 추가/삭제)
        var pbDomObserver = new MutationObserver(function(){
            if(pbSlideUpdating) return;
            // 새로 추가된 요소도 관찰 대상에 포함
            observeSlideItems();
            handleSlideChange();
        });
        pbDomObserver.observe(pbSlideWrapper, { childList: true });
        pbDomObserver.observe(pbBulletArea, { childList: true });

        // 외부에서 호출 가능한 수동 갱신 함수
        window.pbSlideManager = {
            refresh: function(){ goToSlide(0); if(pbAlignSlideBox) pbAlignSlideBox(); if(pbUpdateThumb) pbUpdateThumb(); },
            goTo: goToSlide
        };
    }

    // publication 필터
    var pbFilterItems = document.querySelectorAll('#publication .filter_area ul li');
    if(pbFilterItems.length){
        pbFilterItems.forEach(function(li){
            var btn = li.querySelector('button');
            if(btn){
                btn.addEventListener('click', function(){
                    pbFilterItems.forEach(function(item){ item.classList.remove('active'); });
                    li.classList.add('active');
                });
            }
        });
    }

    // case_studies_main h2 글자별 blur reveal 애니메이션
    var csdmTitH2 = document.querySelector('#case_studies_main .csdm_tit h2');

    if(csdmTitH2){
        var csdmNodes = Array.from(csdmTitH2.childNodes);
        csdmTitH2.innerHTML = '';

        csdmNodes.forEach(function(node){
            if(node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR'){
                csdmTitH2.appendChild(node.cloneNode());
            } else if(node.nodeType === Node.TEXT_NODE){
                var text = node.textContent.replace(/\s+/g, ' ').trim();
                if(!text) return;
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        csdmTitH2.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        csdmTitH2.appendChild(span);
                    }
                }
            }
        });

        var csdmChars = csdmTitH2.querySelectorAll('.char');

        function startCsdmH2Anim(){
            csdmChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        startCsdmH2Anim();
    }

    // dgd_visual 진입 시퀀스: top_img_deco → top_tit strong → h2 blur reveal
    var dgdVisual = document.querySelector('#dgd_visual');

    if(dgdVisual){
        var dgdTopImgDeco = dgdVisual.querySelector('.top_img_deco');
        var dgdTopTitStrong = dgdVisual.querySelector('.top_tit strong');
        var dgdTitStrongs = dgdVisual.querySelectorAll('.dgd_vis_bot .dgd_tit h2 strong');

        // h2 글자별 분리 (미리 준비)
        if(dgdTitStrongs.length){
            dgdTitStrongs.forEach(function(strong){
                var text = strong.textContent;
                strong.innerHTML = '';
                for(var i = 0; i < text.length; i++){
                    if(text[i] === ' '){
                        strong.appendChild(document.createTextNode(' '));
                    } else {
                        var span = document.createElement('span');
                        span.textContent = text[i];
                        span.className = 'char';
                        strong.appendChild(span);
                    }
                }
            });
        }

        var dgdChars = dgdVisual.querySelectorAll('.dgd_vis_bot .dgd_tit h2 strong .char');

        function startDgdH2Anim(){
            dgdChars.forEach(function(char, index){
                setTimeout(function(){
                    char.classList.add('revealed');
                }, 35 * index);
            });
        }

        // 순차 애니메이션 시작
        var delay = 300;

        // 1단계: top_img_deco fadein
        setTimeout(function(){
            if(dgdTopImgDeco) dgdTopImgDeco.classList.add('revealed');
        }, delay);

        // 2단계: top_tit strong fadein
        setTimeout(function(){
            if(dgdTopTitStrong) dgdTopTitStrong.classList.add('revealed');
        }, delay + 600);

        // 3단계: h2 blur reveal
        setTimeout(function(){
            startDgdH2Anim();
        }, delay + 1200);
    }

    // dgd_card responsive GSAP animation
    var dgdCardSection = document.querySelector('#dgd_card');

    if(dgdCardSection){
        dgdCardSection.classList.add('section_hideHeader');
    }

    if(dgdCardSection && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'){
        gsap.registerPlugin(ScrollTrigger);

        var dgdCardContainer = dgdCardSection.querySelector('.dgd_card_container');
        var dgdCardStackBox = dgdCardSection.querySelector('.card_stack_box');
        var dgdCards = dgdCardStackBox ? Array.prototype.slice.call(dgdCardStackBox.querySelectorAll('.card_item')) : [];
        var dgdCardTl = null;
        var dgdMobileTween = null;
        var dgdMode = '';
        var dgdMobileIndex = 0;
        var dgdMobileAnimating = false;
        var dgdResizeTimer = null;
        var dgdPagination = dgdCardSection.querySelector('.dgd_pagination');
        var dgdPagCurrent = dgdPagination ? dgdPagination.querySelector('.dgd_pag_current') : null;
        var dgdPagTotal = dgdPagination ? dgdPagination.querySelector('.dgd_pag_total') : null;
        var dgdPagTicks = dgdPagination ? dgdPagination.querySelector('.dgd_pag_ticks') : null;
        var dgdMobileNextBtns = Array.prototype.slice.call(dgdCardSection.querySelectorAll('.dgd_mobile_next'));
        var dgdMobileCurrent = null;
        var dgdTickEls = [];
        var dgdDescItems = Array.prototype.slice.call(dgdCardSection.querySelectorAll('.dgd_card_desc_item'));
        var dgdPrevDescIndex = -1;

        function isDgdMobileSlider(){
            return window.innerWidth <= 1280;
        }

        function buildDgdTicks(tickCount){
            dgdTickEls = [];

            if(!dgdPagTicks){
                return;
            }

            dgdPagTicks.innerHTML = '';

            for(var t = 0; t < tickCount; t++){
                var tick = document.createElement('span');
                dgdPagTicks.appendChild(tick);
                dgdTickEls.push(tick);
            }
        }

        function updateDgdTicks(progress){
            if(!dgdTickEls.length){
                return;
            }

            var safeProgress = Math.max(0, Math.min(progress, 1));
            var activeTicks = Math.round(safeProgress * dgdTickEls.length);

            for(var i = 0; i < dgdTickEls.length; i++){
                if(i < activeTicks){
                    dgdTickEls[i].classList.add('active');
                } else {
                    dgdTickEls[i].classList.remove('active');
                }
            }
        }

        function setDgdCurrent(index, tickProgress){
            if(!dgdCards.length){
                return;
            }

            var safeIndex = Math.max(0, Math.min(index, dgdCards.length - 1));
            var currentText = String(safeIndex + 1).padStart(2, '0');

            if(dgdPagCurrent){
                dgdPagCurrent.textContent = currentText;
            }

            if(dgdMobileCurrent){
                dgdMobileCurrent.textContent = currentText;
            }

            if(dgdDescItems.length > 0 && safeIndex !== dgdPrevDescIndex){
                dgdDescItems.forEach(function(item){ item.classList.remove('active'); });

                if(dgdDescItems[safeIndex]){
                    dgdDescItems[safeIndex].classList.add('active');
                }

                dgdPrevDescIndex = safeIndex;
            }

            if(typeof tickProgress === 'number'){
                updateDgdTicks(tickProgress);
            }
        }

        function clearDgdCardMode(){
            if(dgdCardTl){
                if(dgdCardTl.scrollTrigger){
                    dgdCardTl.scrollTrigger.kill();
                }
                dgdCardTl.kill();
                dgdCardTl = null;
            }

            if(dgdMobileTween){
                dgdMobileTween.kill();
                dgdMobileTween = null;
            }

            dgdMobileAnimating = false;
            dgdCardSection.classList.remove('is-scroll-stack', 'is-mobile-slider');

            if(dgdCardStackBox){
                dgdCardStackBox.style.height = '';
                dgdCardStackBox.style.minHeight = '';
            }

            dgdCards.forEach(function(card){
                card.style.zIndex = '';
                card.style.width = '';
                card.style.height = '';
                card.style.left = '';
                card.style.aspectRatio = '';
                card.classList.remove('is-active', 'is-back', 'is-front', 'is-out');
                gsap.set(card, { clearProps: 'transform,opacity,visibility,pointerEvents,zIndex' });
            });

            if(dgdPagTicks){
                dgdPagTicks.innerHTML = '';
            }

            dgdTickEls = [];
            dgdPrevDescIndex = -1;

            if(dgdPagCurrent){
                dgdPagCurrent.textContent = '01';
            }

            if(dgdMobileCurrent){
                dgdMobileCurrent.textContent = '01';
            }
        }

        function buildDgdCardStack(){
            clearDgdCardMode();

            if(!dgdCardContainer || !dgdCardStackBox || dgdCards.length < 2){
                return;
            }

            dgdMode = 'desktop';
            dgdMobileIndex = 0;

            var firstCard = dgdCards[0];
            var cardHeight = firstCard.offsetHeight;

            if(!cardHeight){
                return;
            }

            var cardGap = Math.min(12, window.innerWidth * 0.00625);
            var pinHoldExtra = window.innerHeight * 0.35;
            var scrollDistance = Math.max(window.innerHeight * 0.45, cardHeight * 0.95) * (dgdCards.length - 1) + pinHoldExtra;

            dgdCardSection.classList.add('is-scroll-stack');
            dgdCardStackBox.style.height = cardHeight + 'px';

            var tickCount = 20;
            buildDgdTicks(tickCount);

            if(dgdPagTotal){
                dgdPagTotal.textContent = String(dgdCards.length).padStart(2, '0');
            }

            setDgdCurrent(0, 0);

            // 애니메이션 진행 비율 (pinHoldExtra 제외)
            var animationRatio = (scrollDistance - pinHoldExtra) / scrollDistance;

            dgdCards.forEach(function(card, index){
                card.style.zIndex = String(index + 1);
                gsap.set(card, {
                    y: index * (cardHeight + cardGap),
                    scale: 1,
                    opacity: 1
                });
            });

            dgdCardTl = gsap.timeline({
                defaults: { ease: 'none' },
                scrollTrigger: {
                    trigger: dgdCardSection,
                    pin: true,
                    start: 'top top',
                    end: '+=' + scrollDistance,
                    scrub: 1,
                    invalidateOnRefresh: true,
                    anticipatePin: 1,
                    onUpdate: function(self){
                        var progress = self.progress;
                        var animProgress = Math.min(progress / animationRatio, 1);

                        // ticks 활성화
                        updateDgdTicks(animProgress);

                        // 현재 카드 인덱스 업데이트 (각 전환 중간 지점에서 번호 변경)
                        var tlProgress = dgdCardTl.progress();
                        var steps = dgdCards.length - 1;
                        var currentIndex = Math.min(Math.round(tlProgress * steps) + 1, dgdCards.length);
                        setDgdCurrent(currentIndex - 1);
                    }
                }
            });

            dgdCards.slice(1).forEach(function(card, index){
                var prevCard = dgdCards[index];

                dgdCardTl.to(card, {
                    y: 0,
                    duration: 1
                }, index);

                dgdCardTl.to(prevCard, {
                    scale: 0.89,
                    opacity: 0,
                    duration: 1
                }, index);
            });
        }

        function getDgdMobileState(cardIndex, mobileIndex){
            if(typeof mobileIndex !== 'number'){
                mobileIndex = dgdMobileIndex;
            }

            var total = dgdCards.length;
            var nextIndex = (mobileIndex + 1) % total;
            var prevIndex = (mobileIndex - 1 + total) % total;
            var boxWidth = dgdCardStackBox ? dgdCardStackBox.offsetWidth : 0;
            var boxHeight = dgdCardStackBox ? dgdCardStackBox.offsetHeight : 0;
            var desiredGap = 120;
            var naturalCardHeight = boxWidth * 420 / 630;
            var cardHeight = Math.max(Math.min(naturalCardHeight, boxHeight - desiredGap), 0);
            var spare = Math.max(boxHeight - cardHeight, 0);

            var activeY = spare / 2;
            var frontY = spare;

            if(cardIndex === mobileIndex){
                return {
                    className: 'is-active',
                    vars: {
                        x: 0,
                        y: activeY,
                        scale: 1,
                        opacity: 1,
                        zIndex: 3,
                        visibility: 'visible',
                        pointerEvents: 'auto'
                    }
                };
            }

            if(cardIndex === nextIndex){
                return {
                    className: 'is-front',
                    vars: {
                        x: 0,
                        y: frontY,
                        scale: 0.96,
                        opacity: 0.4,
                        zIndex: 2,
                        visibility: 'visible',
                        pointerEvents: 'none'
                    }
                };
            }

            if(cardIndex === prevIndex){
                return {
                    className: 'is-back',
                    vars: {
                        x: 0,
                        y: 0,
                        scale: 0.93,
                        opacity: 0.4,
                        zIndex: 1,
                        visibility: 'visible',
                        pointerEvents: 'none'
                    }
                };
            }

            return {
                className: 'is-out',
                vars: {
                    x: 0,
                    y: activeY,
                    scale: 0.85,
                    opacity: 0.4,
                    zIndex: 0,
                    visibility: 'visible',
                    pointerEvents: 'none'
                }
            };
        }

        function applyDgdMobileClassesAndInstant(mobileIndex){
            dgdCards.forEach(function(card, i){
                var state = getDgdMobileState(i, mobileIndex);
                card.classList.remove('is-active', 'is-back', 'is-front', 'is-out');
                card.classList.add(state.className);
                gsap.set(card, {
                    zIndex: state.vars.zIndex,
                    visibility: state.vars.visibility,
                    pointerEvents: state.vars.pointerEvents
                });
            });
        }

        function buildDgdMobileSlider(){
            clearDgdCardMode();

            if(!dgdCardContainer || !dgdCardStackBox || dgdCards.length < 2){
                return;
            }

            dgdMode = 'mobile';
            dgdMobileIndex = 0;

            dgdCardSection.classList.add('is-mobile-slider');

            dgdCardStackBox.style.height = '';

            var sizingBoxWidth = dgdCardStackBox.offsetWidth;
            var sizingBoxHeight = dgdCardStackBox.offsetHeight;
            var sizingDesiredGap = 120;
            var sizingNaturalH = sizingBoxWidth * 420 / 630;
            var sizingCardH = Math.max(Math.min(sizingNaturalH, sizingBoxHeight - sizingDesiredGap), 0);
            var sizingCardW = sizingCardH * 630 / 420;
            var sizingLeft = (sizingBoxWidth - sizingCardW) / 2;

            dgdCards.forEach(function(card){
                card.style.width = sizingCardW + 'px';
                card.style.height = sizingCardH + 'px';
                card.style.left = sizingLeft + 'px';
                card.style.aspectRatio = 'auto';
            });

            buildDgdTicks(20);

            if(dgdPagTotal){
                dgdPagTotal.textContent = String(dgdCards.length).padStart(2, '0');
            }

            // 초기 상태 적용 (currentIndex = 0)
            dgdCards.forEach(function(card, i){
                var state = getDgdMobileState(i, 0);
                card.classList.remove('is-active', 'is-back', 'is-front', 'is-out');
                card.classList.add(state.className);
                gsap.set(card, state.vars);
            });

            setDgdCurrent(0, 0);

            var steps = dgdCards.length - 1;
            var pinHoldExtra = window.innerHeight * 0.3;
            var stepSize = window.innerHeight * 0.55;
            var scrollDistance = stepSize * steps + pinHoldExtra;
            var animationRatio = (scrollDistance - pinHoldExtra) / scrollDistance;

            dgdCardTl = gsap.timeline({
                defaults: { ease: 'none' },
                scrollTrigger: {
                    trigger: dgdCardSection,
                    pin: dgdCardSection,
                    start: 'top top',
                    end: '+=' + scrollDistance,
                    scrub: 1,
                    invalidateOnRefresh: true,
                    anticipatePin: 1,
                    onUpdate: function(self){
                        var progress = self.progress;
                        var animProgress = Math.min(progress / animationRatio, 1);
                        var tlProgress = dgdCardTl.progress();
                        var newIndex = Math.min(Math.round(tlProgress * steps), dgdCards.length - 1);

                        updateDgdTicks(animProgress);

                        if(newIndex !== dgdMobileIndex){
                            dgdMobileIndex = newIndex;
                            applyDgdMobileClassesAndInstant(newIndex);
                            setDgdCurrent(newIndex);
                        }
                    }
                }
            });

            for(var targetIdx = 1; targetIdx <= steps; targetIdx++){
                (function(idx){
                    dgdCards.forEach(function(card, i){
                        var state = getDgdMobileState(i, idx);
                        dgdCardTl.to(card, {
                            x: state.vars.x,
                            y: state.vars.y,
                            scale: state.vars.scale,
                            opacity: state.vars.opacity,
                            duration: 1
                        }, idx - 1);
                    });
                })(targetIdx);
            }
        }

        function rebuildDgdCard(){
            if(isDgdMobileSlider()){
                buildDgdMobileSlider();
            } else {
                buildDgdCardStack();
            }
        }

        dgdMobileNextBtns.forEach(function(btn){
            btn.addEventListener('click', function(){
                if(dgdMode !== 'mobile' || !dgdCardTl || !dgdCardTl.scrollTrigger || dgdCards.length < 2){
                    return;
                }

                var st = dgdCardTl.scrollTrigger;
                var steps = dgdCards.length - 1;
                var nextStepIndex = Math.min(dgdMobileIndex + 1, steps);
                var targetScroll = st.start + (st.end - st.start) * (nextStepIndex / steps);

                window.scrollTo({
                    top: targetScroll,
                    behavior: 'smooth'
                });
            });
        });

        rebuildDgdCard();

        window.addEventListener('resize', function(){
            clearTimeout(dgdResizeTimer);
            dgdResizeTimer = setTimeout(function(){
                rebuildDgdCard();
                ScrollTrigger.refresh();
            }, 150);
        });
    }

    // case_studies_list filter arc navigation (FLIP)
    var csdFilter = document.querySelector('.csd_list_filter ul');
    if(csdFilter){
        var isAnimating = false;
        var arcPaddings = [0, 20, 0];
        var ease = 'cubic-bezier(0.25, 1, 0.5, 1)';
        var duration = 700;

        function assignArcClasses(){
            var items = Array.from(csdFilter.querySelectorAll('li:not(.ghost)'));
            items.forEach(function(li){
                li.className = li.className.replace(/\barc\d\b/g, '').trim();
            });
            items.forEach(function(li, i){
                li.classList.add('arc' + i);
            });
        }

        function setActive(clickedLi){
            var items = Array.from(csdFilter.querySelectorAll('li:not(.ghost)'));
            items.forEach(function(li){
                li.classList.remove('active');
                var a = li.querySelector('a');
                if(a) a.classList.remove('active');
            });
            clickedLi.classList.add('active');
            var clickedA = clickedLi.querySelector('a');
            if(clickedA) clickedA.classList.add('active');
        }

        assignArcClasses();

        csdFilter.addEventListener('click', function(e){
            var link = e.target.closest('a');
            if(!link || isAnimating) return;
            e.preventDefault();

            var clickedLi = link.closest('li');
            var items = Array.from(csdFilter.querySelectorAll('li:not(.ghost)'));
            var clickedIdx = items.indexOf(clickedLi);
            if(clickedIdx < 0) return;

            setActive(clickedLi);

            // data-csdl로 매칭된 csdl_item으로 스크롤 (custom easing)
            var csdlKey = clickedLi.getAttribute('data-csdl');
            var targetItem = document.querySelector('#case_studies_list .csdl_item[data-csdl="' + csdlKey + '"]');
            if(targetItem){
                (function(){
                    var targetTop = targetItem.getBoundingClientRect().top + window.pageYOffset - 120;
                    var startTop = window.pageYOffset;
                    var distance = targetTop - startTop;
                    var dur = Math.min(1200, Math.max(600, Math.abs(distance) * 0.4));
                    var startTime = null;

                    // easeOutQuart — 부드러운 감속
                    function easing(t){
                        return 1 - Math.pow(1 - t, 4);
                    }

                    function step(timestamp){
                        if(!startTime) startTime = timestamp;
                        var elapsed = timestamp - startTime;
                        var progress = Math.min(elapsed / dur, 1);
                        window.scrollTo(0, startTop + distance * easing(progress));
                        if(progress < 1) requestAnimationFrame(step);
                    }

                    requestAnimationFrame(step);
                })();
            }

            // 이미 최상단(현재) 항목이면 재정렬 애니메이션은 생략
            if(clickedIdx === 0) return;

            isAnimating = true;

            var ulRect = csdFilter.getBoundingClientRect();
            var toMove = items.slice(0, clickedIdx);

            // First: 현재 위치 기록
            var firstRects = {};
            items.forEach(function(li, i){
                firstRects[i] = li.getBoundingClientRect();
            });

            // 사라질 항목의 고스트(클론) 생성
            var ghosts = [];
            toMove.forEach(function(item, i){
                var rect = firstRects[i];
                var ghost = item.cloneNode(true);
                ghost.classList.add('ghost');
                ghost.style.position = 'absolute';
                ghost.style.top = (rect.top - ulRect.top) + 'px';
                ghost.style.left = '0';
                ghost.style.width = rect.width + 'px';
                ghost.style.opacity = '1';
                ghost.style.transform = 'translateX(0)';
                ghost.style.transition = 'opacity ' + 400 + 'ms ' + ease + ' ' + (i * 60) + 'ms, transform ' + 400 + 'ms ' + ease + ' ' + (i * 60) + 'ms';
                ghost.style.pointerEvents = 'none';
                csdFilter.appendChild(ghost);
                ghosts.push(ghost);
            });

            // 고스트 fade out (왼쪽으로 슬라이드하며 사라짐)
            requestAnimationFrame(function(){
                requestAnimationFrame(function(){
                    ghosts.forEach(function(ghost){
                        ghost.style.opacity = '0';
                        ghost.style.transform = 'translateX(-15px)';
                    });
                });
            });

            // DOM 이동
            toMove.forEach(function(item){
                csdFilter.appendChild(item);
            });
            assignArcClasses();

            // Last: 새 위치 기록 & FLIP
            var newItems = Array.from(csdFilter.querySelectorAll('li:not(.ghost)'));

            newItems.forEach(function(li, newIdx){
                var oldIdx = items.indexOf(li);
                var wasMoved = toMove.indexOf(li) !== -1;
                var firstRect = firstRects[oldIdx];
                var lastRect = li.getBoundingClientRect();
                var stagger = newIdx * 40;

                if(wasMoved){
                    // 아래로 이동된 항목: 왼쪽에서 fade in (고스트 퇴장 후)
                    var enterDelay = 300 + stagger;
                    li.style.transition = 'none';
                    li.style.transform = 'translateX(-20px)';
                    li.style.opacity = '0';
                    li.style.paddingLeft = arcPaddings[newIdx] + 'px';

                    requestAnimationFrame(function(){
                        requestAnimationFrame(function(){
                            li.style.transition = 'transform ' + duration + 'ms ' + ease + ' ' + enterDelay + 'ms, opacity ' + duration + 'ms ' + ease + ' ' + enterDelay + 'ms, padding-left ' + duration + 'ms ' + ease + ' ' + enterDelay + 'ms';
                            li.style.transform = 'translateX(0)';
                            li.style.opacity = '1';
                        });
                    });
                } else {
                    // 기존 항목: FLIP 위치 보간
                    var dy = firstRect.top - lastRect.top;
                    li.style.transition = 'none';
                    li.style.transform = 'translateY(' + dy + 'px)';
                    li.style.paddingLeft = arcPaddings[newIdx] + 'px';

                    requestAnimationFrame(function(){
                        requestAnimationFrame(function(){
                            li.style.transition = 'transform ' + duration + 'ms ' + ease + ' ' + stagger + 'ms, padding-left ' + duration + 'ms ' + ease + ' ' + stagger + 'ms';
                            li.style.transform = 'translateY(0)';
                        });
                    });
                }
            });

            // 정리
            var totalDuration = duration + newItems.length * 40 + 350;
            setTimeout(function(){
                ghosts.forEach(function(ghost){
                    ghost.remove();
                });
                newItems.forEach(function(li){
                    li.style.transition = '';
                    li.style.transform = '';
                    li.style.opacity = '';
                    li.style.paddingLeft = '';
                });
                isAnimating = false;
            }, totalDuration);
        });
    }

    // news diagonal slide
    if(document.querySelector('.nv_slide')){
        var nvSlideEl = document.querySelector('.nv_slide');
        var nvTxtSlideEl = document.querySelector('.nv_txt_slide');
        var nvTickCount = 22;
        var nvTicksWrap = document.querySelector('.nv_pag_ticks');
        var nvPagCurrent = document.querySelector('.nv_pag_current');
        var nvPagTotal = document.querySelector('.nv_pag_total');
        var nvSlideCount = 0;
        var nvAllSlides = [];
        var nvSlides = [];
        var nvActiveIndex = 0;
        var nvAutoTimer = null;
        var nvRefreshTimer = null;
        var nvDomObserver = null;
        var nvIsRefreshing = false;
        var txtSlide = null;

        function nvFormatCount(count){
            return String(count).padStart(2, '0');
        }

        function nvEnsureTicks(){
            if(!nvTicksWrap) return;
            if(nvTicksWrap.querySelectorAll('span').length === nvTickCount) return;

            nvTicksWrap.innerHTML = '';
            for(var t = 0; t < nvTickCount; t++){
                var tick = document.createElement('span');
                nvTicksWrap.appendChild(tick);
            }
        }

        var nvAutoDelay = 4000;
        var nvFillTimer = null;
        var nvPrevIndex = 0;

        function nvUpdatePaginationNumbers(){
            if(nvPagTotal) nvPagTotal.textContent = nvFormatCount(nvSlideCount);
            if(nvPagCurrent) nvPagCurrent.textContent = nvFormatCount(nvSlideCount ? nvActiveIndex + 1 : 0);
        }

        function nvResetTicks(){
            if(!nvTicksWrap) return;
            var ticks = nvTicksWrap.querySelectorAll('span');
            for(var i = 0; i < ticks.length; i++){
                ticks[i].classList.remove('active');
            }
        }

        function nvStopPlayTimers(){
            if(nvFillTimer) clearInterval(nvFillTimer);
            if(nvAutoTimer) clearTimeout(nvAutoTimer);
            nvFillTimer = null;
            nvAutoTimer = null;
        }

        function nvStartFill(realIndex, forceReset){
            if(!nvTicksWrap) return;
            var ticks = nvTicksWrap.querySelectorAll('span');

            if(nvFillTimer) clearInterval(nvFillTimer);
            nvFillTimer = null;

            if(!nvSlideCount){
                nvResetTicks();
                if(nvPagCurrent) nvPagCurrent.textContent = nvFormatCount(0);
                return;
            }

            if(forceReset){
                nvResetTicks();
            }

            // loop: 마지막→첫번째로 돌아오면 전체 리셋
            if(!forceReset && realIndex === 0 && nvPrevIndex === nvSlideCount - 1){
                nvResetTicks();
            }
            nvPrevIndex = realIndex;

            // 각 슬라이드 구간 계산 (슬라이드별로 tick을 균등 분배)
            var startTick = Math.round(realIndex / nvSlideCount * nvTickCount);
            var endTick = Math.round((realIndex + 1) / nvSlideCount * nvTickCount);
            var ticksToFill = endTick - startTick;
            var current = 0;
            var interval = ticksToFill > 0 ? nvAutoDelay / ticksToFill : nvAutoDelay;

            // 이전 구간까지 즉시 채우기 (리셋 직후가 아닌 경우)
            for(var j = 0; j < startTick; j++){
                ticks[j].classList.add('active');
            }

            if(nvPagCurrent) nvPagCurrent.textContent = String(realIndex + 1).padStart(2, '0');

            nvFillTimer = setInterval(function(){
                if(current < ticksToFill){
                    ticks[startTick + current].classList.add('active');
                    current++;
                } else {
                    clearInterval(nvFillTimer);
                }
            }, interval);
        }

        function nvEnsureStatusEl(slide){
            if(!slide || slide.querySelector('.nv_slide_status')) return;

            var statusEl = document.createElement('span');
            statusEl.className = 'nv_slide_status';
            slide.appendChild(statusEl);
        }

        function nvGetTextSlides(){
            if(!nvTxtSlideEl) return [];
            return Array.from(nvTxtSlideEl.querySelectorAll('.swiper-wrapper > .swiper-slide'));
        }

        function nvSyncText(realIndex, speed){
            if(!txtSlide || txtSlide.destroyed || !nvSlideCount) return;
            if(txtSlide.activeIndex === realIndex) return;
            txtSlide.slideTo(realIndex, speed || 0);
        }

        function nvResetStack(slide){
            var statusEl = slide.querySelector('.nv_slide_status');

            slide.classList.remove('is-stack-visible', 'is-current', 'is-next-card');
            slide.style.removeProperty('--nv-stack-offset-x');
            slide.style.removeProperty('--nv-stack-offset-y');
            slide.style.removeProperty('--nv-stack-scale');
            slide.style.removeProperty('--nv-stack-brightness');
            slide.style.zIndex = '';
            if(statusEl) statusEl.textContent = '';
        }

        function nvCollectSlides(preservedSlide){
            var textSlides = nvGetTextSlides();
            var nextAllSlides = Array.from(nvSlideEl.querySelectorAll('.swiper-wrapper > .swiper-slide'));
            var nextCount = nvTxtSlideEl ? Math.min(nextAllSlides.length, textSlides.length) : nextAllSlides.length;

            nextAllSlides.forEach(nvEnsureStatusEl);

            nvAllSlides = nextAllSlides;
            nvSlides = nextAllSlides.slice(0, nextCount);
            nvSlideCount = nextCount;

            if(!nvSlideCount){
                nvActiveIndex = 0;
                return;
            }

            if(preservedSlide){
                var preservedIndex = nvSlides.indexOf(preservedSlide);
                if(preservedIndex > -1){
                    nvActiveIndex = preservedIndex;
                    return;
                }
            }

            if(nvActiveIndex >= nvSlideCount){
                nvActiveIndex = nvSlideCount - 1;
            }
            if(nvActiveIndex < 0){
                nvActiveIndex = 0;
            }
        }

        function nvGetStackOrder(realIndex){
            var order = [realIndex];

            if(realIndex > 0){
                order.push(realIndex - 1);
            } else if(realIndex < nvSlideCount - 1){
                order.push(realIndex + 1);
            }

            return order;
        }

        function nvApplyStack(realIndex){
            nvAllSlides.forEach(nvResetStack);

            nvGetStackOrder(realIndex).forEach(function(slideIndex, depth){
                var slide = nvSlides[slideIndex];
                var statusEl = slide ? slide.querySelector('.nv_slide_status') : null;
                if(!slide) return;

                slide.classList.add('is-stack-visible');
                if(depth === 0){
                    slide.classList.add('is-current');
                    if(statusEl) statusEl.textContent = 'Current highlight';
                } else if(depth === 1){
                    slide.classList.add('is-next-card');
                    if(statusEl) statusEl.textContent = 'Next highlight';
                }

                slide.style.setProperty('--nv-stack-offset-x', depth === 0 ? '0%' : '14%');
                slide.style.setProperty('--nv-stack-offset-y', depth === 0 ? '0%' : '-20%');
                slide.style.setProperty('--nv-stack-scale', depth === 0 ? '1' : '0.84');
                slide.style.setProperty('--nv-stack-brightness', depth === 0 ? '1' : '0.78');
                slide.style.zIndex = String(nvSlideCount - depth);
            });
        }

        function nvInitTextSlide(){
            if(txtSlide && !txtSlide.destroyed){
                txtSlide.destroy(true, true);
            }

            txtSlide = null;
            if(!nvTxtSlideEl || !nvGetTextSlides().length) return;

            txtSlide = new Swiper('.nv_txt_slide', {
                slidesPerView: 1,
                effect: 'fade',
                fadeEffect: { crossFade: true },
                speed: 800,
                loop: false,
                allowTouchMove: false,
            });

            if(nvSlideCount && nvActiveIndex > 0){
                txtSlide.slideTo(nvActiveIndex, 0);
            }
        }

        function nvScheduleNext(){
            if(nvAutoTimer) clearTimeout(nvAutoTimer);
            if(nvSlideCount < 2) return;

            nvAutoTimer = setTimeout(function(){
                var nextIndex = nvActiveIndex + 1;
                if(nextIndex >= nvSlideCount){
                    nextIndex = 0;
                }
                nvGoTo(nextIndex);
            }, nvAutoDelay);
        }

        function nvRender(options){
            options = options || {};
            nvUpdatePaginationNumbers();

            if(!nvSlideCount){
                nvAllSlides.forEach(nvResetStack);
                nvResetTicks();
                nvPrevIndex = 0;
                nvStopPlayTimers();
                return;
            }

            nvApplyStack(nvActiveIndex);
            nvStartFill(nvActiveIndex, !!options.forceFillReset);
            nvSyncText(nvActiveIndex, options.instantText ? 0 : 800);
            nvScheduleNext();
        }

        function nvGoTo(realIndex, options){
            if(!nvSlideCount) return;

            if(realIndex < 0 || realIndex >= nvSlideCount){
                realIndex = 0;
            }
            nvActiveIndex = realIndex;
            nvRender(options);
        }

        function nvRefreshSlides(preserveCurrent){
            var preservedSlide = preserveCurrent === false ? null : (nvSlides[nvActiveIndex] || null);

            nvIsRefreshing = true;
            nvStopPlayTimers();
            nvCollectSlides(preservedSlide);
            nvInitTextSlide();
            nvRender({ forceFillReset: true, instantText: true });
            nvIsRefreshing = false;
        }

        function nvHasSlideMutation(nodes){
            for(var i = 0; i < nodes.length; i++){
                var node = nodes[i];
                if(!node || node.nodeType !== 1) continue;
                if(node.classList.contains('swiper-slide')) return true;
                if(node.querySelector && node.querySelector('.swiper-slide')) return true;
            }
            return false;
        }

        function nvScheduleRefresh(){
            if(nvRefreshTimer) clearTimeout(nvRefreshTimer);
            nvRefreshTimer = setTimeout(function(){
                nvRefreshTimer = null;
                nvRefreshSlides(true);
            }, 80);
        }

        function nvObserveNewsSlides(){
            if(nvDomObserver) nvDomObserver.disconnect();

            nvDomObserver = new MutationObserver(function(mutations){
                if(nvIsRefreshing) return;

                for(var i = 0; i < mutations.length; i++){
                    var mutation = mutations[i];
                    if(mutation.type !== 'childList') continue;

                    if(nvHasSlideMutation(mutation.addedNodes) || nvHasSlideMutation(mutation.removedNodes)){
                        nvScheduleRefresh();
                        return;
                    }
                }
            });

            nvDomObserver.observe(nvSlideEl, { childList: true, subtree: true });
            if(nvTxtSlideEl){
                nvDomObserver.observe(nvTxtSlideEl, { childList: true, subtree: true });
            }
        }

        nvEnsureTicks();
        nvRefreshSlides(false);
        nvObserveNewsSlides();

        window.nvSlideManager = {
            refresh: function(){
                nvRefreshSlides(true);
            },
            goTo: function(index){
                nvRefreshSlides(true);
                nvGoTo(index, { forceFillReset: true, instantText: true });
            },
            destroy: function(){
                if(nvDomObserver) nvDomObserver.disconnect();
                if(nvRefreshTimer) clearTimeout(nvRefreshTimer);
                nvRefreshTimer = null;
                nvStopPlayTimers();
                if(txtSlide && !txtSlide.destroyed){
                    txtSlide.destroy(true, true);
                }
                txtSlide = null;
                nvAllSlides.forEach(nvResetStack);
                nvSlideCount = 0;
                nvSlides = [];
                nvAllSlides = [];
                nvUpdatePaginationNumbers();
                nvResetTicks();
            }
        };
    }

    // case_studies_main fade swiper
    if(document.querySelector('.csd_swiper')){
        window.csdSwiper = new Swiper('.csd_swiper', {
            slidesPerView: 1,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 600,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
                waitForTransition: false,
            },
            pagination: {
                el: '.csd_pagination',
                clickable: true,
            },
        });
    }

    // news_list 필터 active 토글
    var nlFilterLinks = document.querySelectorAll('#news_list .nl_filter_area ul li a');
    if(nlFilterLinks.length){
        nlFilterLinks.forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                nlFilterLinks.forEach(function(el){
                    el.parentElement.classList.remove('active');
                });
                this.parentElement.classList.add('active');
            });
        });
    }

    /* aslan_combine_slide Swiper */
    if(document.querySelector('.aslan_combine_slide')){
        var aslanCombineSwiper = new Swiper('.aslan_combine_slide', {
            slidesPerView: 1,
            loop: true,
            speed: 800,
            effect: 'creative',
            creativeEffect: {
                prev: { translate: ['-20%', 0, -1], opacity: 0 },
                next: { translate: ['100%', 0, 0], opacity: 0 },
            },
            spaceBetween: 30,
            autoplay: { delay: 4000, disableOnInteraction: false },
            watchOverflow: true,
            pagination: {
                el: '.acs_fraction',
                type: 'fraction',
                formatFractionCurrent: function(number){ return number; },
                formatFractionTotal: function(number){ return number; },
                renderFraction: function(currentClass, totalClass){
                    return '[ <span class="' + currentClass + '"></span> / <span class="' + totalClass + '"></span> ]';
                }
            },
            navigation: {
                prevEl: '.acs_prev',
                nextEl: '.acs_next',
            },
        });

        /* loop 모드에서 첫/마지막 실제 슬라이드일 때 nav 버튼 disable 처리 */
        var acsPrev = document.querySelector('.acs_prev');
        var acsNext = document.querySelector('.acs_next');
        var acsLastRealIndex = aslanCombineSwiper.realIndex;

        function getAcsRealTotal(){
            return document.querySelectorAll('.aslan_combine_slide .swiper-slide:not(.swiper-slide-duplicate)').length;
        }

        function setAcsDirection(direction){
            if(!aslanCombineSwiper || !aslanCombineSwiper.el) return 'next';
            var nextDirection = direction === 'prev' ? 'prev' : 'next';
            aslanCombineSwiper.el.setAttribute('data-acs-direction', nextDirection);
            return nextDirection;
        }

        function getAcsDirection(swiper){
            var currentIndex = swiper.realIndex;
            var total = getAcsRealTotal();

            if(total <= 1 || currentIndex === acsLastRealIndex){
                return swiper.el.getAttribute('data-acs-direction') || 'next';
            }

            if((acsLastRealIndex === total - 1 && currentIndex === 0) || currentIndex === acsLastRealIndex + 1){
                return 'next';
            }

            if((acsLastRealIndex === 0 && currentIndex === total - 1) || currentIndex === acsLastRealIndex - 1){
                return 'prev';
            }

            return currentIndex > acsLastRealIndex ? 'next' : 'prev';
        }

        function updateAcsNav(){
            if(!acsPrev || !acsNext) return;
            var idx = aslanCombineSwiper.realIndex;
            var realTotal = getAcsRealTotal();
            acsPrev.classList.toggle('is_disabled', idx === 0);
            acsNext.classList.toggle('is_disabled', idx === realTotal - 1);
        }
        aslanCombineSwiper.on('slideChange', updateAcsNav);
        setAcsDirection('next');
        updateAcsNav();

        /* acs_fraction 위치를 acs_content 우측 하단에 맞춤 */
        function positionFraction(){
            var slide = document.querySelector('.aslan_combine_slide .swiper-slide-active');
            var content = slide ? slide.querySelector('.acs_content') : null;
            var fraction = document.querySelector('.acs_fraction');
            var swiperEl = document.querySelector('.aslan_combine_slide');
            if(!content || !fraction || !swiperEl) return;

            var swiperRect = swiperEl.getBoundingClientRect();
            var contentRect = content.getBoundingClientRect();
            var top = contentRect.bottom - swiperRect.top - fraction.offsetHeight;
            if(window.innerWidth <= 640){
                top += 28;
            }
            fraction.style.top = top + 'px';
        }

        var fractionRafId = null;
        function schedulePositionFraction(){
            if(fractionRafId) cancelAnimationFrame(fractionRafId);
            fractionRafId = requestAnimationFrame(function(){
                fractionRafId = null;
                positionFraction();
            });
        }

        positionFraction();
        aslanCombineSwiper.on('slideChangeTransitionEnd', function(){
            acsLastRealIndex = this.realIndex;
            positionFraction();
        });
        aslanCombineSwiper.on('resize', schedulePositionFraction);
        aslanCombineSwiper.on('update', schedulePositionFraction);
        window.addEventListener('resize', schedulePositionFraction);
        window.addEventListener('orientationchange', schedulePositionFraction);

        /* 진행 방향에 맞춰 텍스트 fade 방향까지 함께 반전 */
        aslanCombineSwiper.on('slideChangeTransitionStart', function(){
            var direction = setAcsDirection(getAcsDirection(this));
            var fadeOffset = direction === 'prev' ? '-20px' : '20px';
            var activeSlide = this.el.querySelector('.swiper-slide-active');
            if(!activeSlide) return;
            var targets = activeSlide.querySelectorAll('.acs_content p, .acs_info_left');
            targets.forEach(function(el){
                el.style.transition = 'none';
                el.style.opacity = '0';
                el.style.transform = 'translateY(' + fadeOffset + ')';
            });
            void activeSlide.offsetWidth;
            targets.forEach(function(el){
                el.style.transition = '';
                el.style.opacity = '';
                el.style.transform = '';
            });
        });

        var contentEls = document.querySelectorAll('.aslan_combine_slide .acs_content');
        if(contentEls.length && typeof ResizeObserver !== 'undefined'){
            var ro = new ResizeObserver(positionFraction);
            contentEls.forEach(function(el){ ro.observe(el); });
        }
    }

    /* aslan_part_slide – CSS marquee loop (Swiper 미사용) */
    if(document.querySelector('.aslan_part_slide')){
        var apsEl = document.querySelector('.aslan_part_slide');
        var apsTrack = apsEl ? apsEl.querySelector('.swiper-wrapper') : null;
        var apsResizeTimer = null;
        var apsSpeed = 60; /* px per second */
        var apsPaused = false;

        function setApsPlayback(paused){
            if(!apsTrack) return;
            apsPaused = !!paused;
            apsTrack.style.animationPlayState = apsPaused ? 'paused' : 'running';
        }

        function getApsOriginalSlides(){
            if(!apsTrack) return [];
            var cloneAttr = 'data-aps-clone';
            var clones = apsTrack.querySelectorAll('[' + cloneAttr + ']');
            for(var i = 0; i < clones.length; i++){
                clones[i].parentNode.removeChild(clones[i]);
            }
            return Array.prototype.slice.call(apsTrack.children);
        }

        function getApsGap(vertical){
            if(!apsTrack) return 0;
            var trackStyles = window.getComputedStyle(apsTrack);
            var primary = vertical ? trackStyles.rowGap : trackStyles.columnGap;
            var gap = parseFloat(primary || trackStyles.gap || 0);
            return isNaN(gap) ? 0 : gap;
        }

        function markApsSetSlides(slides, setIndex){
            for(var i = 0; i < slides.length; i++){
                slides[i].setAttribute('data-aps-origin-index', i);
                slides[i].setAttribute('data-aps-set-index', setIndex);
                if(i === 0){
                    slides[i].setAttribute('data-aps-set-start', '');
                }else{
                    slides[i].removeAttribute('data-aps-set-start');
                }
            }
        }

        function measureApsSetWidth(slides){
            if(!slides.length) return 0;
            var firstRect = slides[0].getBoundingClientRect();
            var lastRect = slides[slides.length - 1].getBoundingClientRect();
            var setWidth = (lastRect.right - firstRect.left) + getApsGap(false);
            return setWidth > 0 ? setWidth : 0;
        }

        function measureApsSetHeight(slides){
            if(!slides.length) return 0;
            var firstRect = slides[0].getBoundingClientRect();
            var lastRect = slides[slides.length - 1].getBoundingClientRect();
            var setHeight = (lastRect.bottom - firstRect.top) + getApsGap(true);
            return setHeight > 0 ? setHeight : 0;
        }

        function getApsArea(){
            var parent = apsEl ? apsEl.parentNode : null;
            if(parent && parent.classList && parent.classList.contains('aslan_part_slide_area')){
                return parent;
            }
            return null;
        }

        function isApsVertical(){
            return window.innerWidth <= 640;
        }

        function buildApsMarquee(){
            if(!apsEl || !apsTrack) return;

            var apsArea = getApsArea();
            var prevVertical = apsTrack.dataset ? apsTrack.dataset.apsMode === 'vertical' : false;
            var vertical = isApsVertical();

            /* 모드 전환 시 hover/focus paused 상태 누수 방지 */
            if(prevVertical !== vertical){
                apsPaused = false;
            }

            var origSlides = getApsOriginalSlides();
            apsTrack.style.animation = 'none';
            apsTrack.style.transform = vertical ? 'translateY(0)' : 'translateX(0)';
            /* 모드 전환 시 이전 인라인 height 잔재 제거 */
            apsEl.style.height = '';
            if(apsArea){
                apsArea.style.height = '';
            }
            if(!origSlides.length) return;

            apsTrack.style.display = 'flex';
            markApsSetSlides(origSlides, 0);

            /* 세로 모드: 한 슬라이드 높이 × 4 + 간격 3개로 영역 높이 고정 */
            if(vertical){
                var gapPx = getApsGap(true);
                var firstSlideH = origSlides[0].getBoundingClientRect().height;
                if(firstSlideH > 0 && apsArea){
                    var areaH = (firstSlideH * 4) + (gapPx * 3);
                    apsArea.style.height = areaH + 'px';
                    apsEl.style.height = areaH + 'px';
                }
            }

            /* 실제 렌더링 좌표 기준으로 한 세트 이동 거리 측정 */
            var oneSetPx = vertical ? measureApsSetHeight(origSlides) : measureApsSetWidth(origSlides);
            if(!oneSetPx) return;

            /* 화면 크기의 3배 이상 채우도록 복제 */
            var viewSize = vertical
                ? (apsEl.clientHeight || window.innerHeight || 1080)
                : (apsEl.clientWidth || window.innerWidth || 1920);
            var minSize = viewSize * 3;
            var sets = Math.ceil(minSize / oneSetPx) + 1;
            if(sets < 3) sets = 3;

            for(var i = 1; i < sets; i++){
                origSlides.forEach(function(s, index){
                    var clone = s.cloneNode(true);
                    clone.setAttribute('data-aps-clone', '');
                    clone.setAttribute('aria-hidden', 'true');
                    clone.setAttribute('data-aps-origin-index', index);
                    clone.setAttribute('data-aps-set-index', i);
                    if(index === 0){
                        clone.setAttribute('data-aps-set-start', '');
                    }else{
                        clone.removeAttribute('data-aps-set-start');
                    }
                    apsTrack.appendChild(clone);
                });
            }

            /* 실제 다음 세트의 시작 좌표로 다시 측정해 루프 오차 제거 */
            var nextSetStart = apsTrack.querySelector('[data-aps-set-index="1"][data-aps-set-start]');
            if(nextSetStart){
                var currentSetStartRect = origSlides[0].getBoundingClientRect();
                var nextSetStartRect = nextSetStart.getBoundingClientRect();
                var measuredLoopPx = vertical
                    ? (nextSetStartRect.top - currentSetStartRect.top)
                    : (nextSetStartRect.left - currentSetStartRect.left);
                if(measuredLoopPx > 0){
                    oneSetPx = measuredLoopPx;
                }
            }

            var duration = oneSetPx / apsSpeed;

            /* CSS 변수 + 키프레임 분기 */
            if(vertical){
                apsTrack.style.removeProperty('--aps-set-width');
                apsTrack.style.setProperty('--aps-set-height', '-' + oneSetPx + 'px');
            }else{
                apsTrack.style.removeProperty('--aps-set-height');
                apsTrack.style.setProperty('--aps-set-width', '-' + oneSetPx + 'px');
            }
            apsTrack.offsetWidth;
            apsTrack.style.animation = (vertical ? 'apsMarqueeY ' : 'apsMarquee ') + duration + 's linear infinite';
            if(apsTrack.dataset){
                apsTrack.dataset.apsMode = vertical ? 'vertical' : 'horizontal';
            }
            setApsPlayback(apsPaused);
        }

        buildApsMarquee();

        if(document.fonts && document.fonts.ready){
            document.fonts.ready.then(function(){
                buildApsMarquee();
            });
        }

        /* hover 가능한 포인터(데스크톱 마우스)만 정지 토글 활성화.
           모바일 터치 환경에서는 hover hit-test 잔존/스크롤 중 hover 해제 지연으로
           paused↔running이 반복되며 애니메이션이 위로 튀는 jank가 발생하므로 비활성화. */
        var apsSupportsHover = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        if(apsSupportsHover){
            apsEl.addEventListener('mouseenter', function(){
                setApsPlayback(true);
            });

            apsEl.addEventListener('mouseleave', function(){
                setApsPlayback(false);
            });

            apsEl.addEventListener('focusin', function(){
                setApsPlayback(true);
            });

            apsEl.addEventListener('focusout', function(event){
                if(!apsEl.contains(event.relatedTarget)){
                    setApsPlayback(false);
                }
            });
        }

        window.addEventListener('resize', function(){
            if(apsResizeTimer) clearTimeout(apsResizeTimer);
            apsResizeTimer = setTimeout(function(){
                buildApsMarquee();
            }, 200);
        });

        /* DOM 변경 감지 → 자동 리빌드 */
        if(apsTrack && typeof MutationObserver !== 'undefined'){
            var apsMutationTimer = null;
            var apsObserver = new MutationObserver(function(mutations){
                var hasMeaningfulChange = false;
                for(var i = 0; i < mutations.length; i++){
                    var m = mutations[i];
                    if(m.type === 'childList'){
                        var nodes = Array.prototype.slice.call(m.addedNodes).concat(
                            Array.prototype.slice.call(m.removedNodes)
                        );
                        for(var j = 0; j < nodes.length; j++){
                            if(nodes[j].nodeType === 1 && !nodes[j].hasAttribute('data-aps-clone')){
                                hasMeaningfulChange = true;
                                break;
                            }
                        }
                    }
                    if(hasMeaningfulChange) break;
                }
                if(hasMeaningfulChange){
                    if(apsMutationTimer) clearTimeout(apsMutationTimer);
                    apsMutationTimer = setTimeout(function(){
                        buildApsMarquee();
                    }, 100);
                }
            });
            apsObserver.observe(apsTrack, { childList: true });
        }

        /* 외부에서 호출 가능한 리빌드 API */
        window.rebuildApsMarquee = buildApsMarquee;
    }

    // #aslan_slogan pin + slogan_scrollEffect active 순차 부여
    var aslanSloganSection = document.getElementById('aslan_slogan');
    if(aslanSloganSection && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'){
        gsap.registerPlugin(ScrollTrigger);

        var sloganItems = aslanSloganSection.querySelectorAll('.slogan_scrollEffect');
        var sloganPagination = aslanSloganSection.querySelector('.slogan_scroll_pagination span');
        var sloganContainer = aslanSloganSection.querySelector('.asls_container');
        var itemCount = sloganItems.length;

        // 640 이하에서 active desc 높이를 CSS 변수로 노출
        // → pagination 위치가 desc 길이에 맞춰 따라옴
        function updateSloganDescHeight(){
            if(!sloganContainer) return;
            if(window.innerWidth > 640){
                sloganContainer.style.removeProperty('--slogan-desc-h');
                return;
            }
            var activeDesc = aslanSloganSection.querySelector('.slogan_scrollEffect.active .slogan_se_desc');
            if(activeDesc){
                var h = activeDesc.offsetHeight;
                if(h > 0){
                    sloganContainer.style.setProperty('--slogan-desc-h', h + 'px');
                }
            }
        }

        // resize 이벤트는 reflow 이전에 동기적으로 발생할 수 있어
        // offsetHeight가 이전 레이아웃 기준으로 읽히는 경우가 있음.
        // rAF로 두 프레임 미루고, 그래도 settle 안 된 케이스 대비해 setTimeout으로 한 번 더 보정.
        var sloganDescRafId = null;
        var sloganDescTimerId = null;
        function scheduleSloganDescHeight(){
            if(sloganDescRafId !== null) cancelAnimationFrame(sloganDescRafId);
            sloganDescRafId = requestAnimationFrame(function(){
                sloganDescRafId = requestAnimationFrame(function(){
                    sloganDescRafId = null;
                    updateSloganDescHeight();
                });
            });
            if(sloganDescTimerId !== null) clearTimeout(sloganDescTimerId);
            sloganDescTimerId = setTimeout(function(){
                sloganDescTimerId = null;
                updateSloganDescHeight();
            }, 200);
        }

        if(itemCount > 0){
            sloganItems.forEach(function(item, i){
                if(i === 0) item.classList.add('active');
                else item.classList.remove('active');
            });

            if(sloganPagination){
                sloganPagination.textContent = '[ 1 / ' + itemCount + ' ]';
            }

            ScrollTrigger.create({
                trigger: aslanSloganSection,
                start: 'top top',
                end: '+=' + (itemCount * 140) + '%',
                pin: true,
                scrub: true,
                onUpdate: function(self){
                    var idx = Math.min(Math.floor(self.progress * itemCount), itemCount - 1);
                    sloganItems.forEach(function(item, i){
                        if(i === idx) item.classList.add('active');
                        else item.classList.remove('active');
                    });
                    if(sloganPagination){
                        sloganPagination.textContent = '[ ' + (idx + 1) + ' / ' + itemCount + ' ]';
                    }
                    updateSloganDescHeight();
                }
            });

            updateSloganDescHeight();
            window.addEventListener('resize', scheduleSloganDescHeight);

            if(typeof ResizeObserver !== 'undefined'){
                var sloganDescRO = new ResizeObserver(updateSloganDescHeight);
                aslanSloganSection.querySelectorAll('.slogan_se_desc').forEach(function(el){
                    sloganDescRO.observe(el);
                });
            }
        }
    }

    // techBlog_post 공유 aside 스크롤
    var tbpShare = document.querySelector('#techblog_post .tbp_share');
    var tbpContent = document.querySelector('#techblog_post .tbp_content');
    var tbpImg = document.querySelector('#techblog_post .tbp_content .tbp_img');
    if(tbpShare && tbpContent && tbpImg){
        var shareHeight = tbpShare.offsetHeight;
        var initialTop = tbpImg.offsetTop + tbpImg.offsetHeight - shareHeight;
        tbpShare.style.top = initialTop + 'px';

        var bottomGap = 40;

        function recalcShareDimensions(){
            shareHeight = tbpShare.offsetHeight;
            initialTop = tbpImg.offsetTop + tbpImg.offsetHeight - shareHeight;
        }

        function updateSharePosition(){
            var contentRect = tbpContent.getBoundingClientRect();
            var contentPaddingBottom = parseFloat(getComputedStyle(tbpContent).paddingBottom) || 0;
            var imgRect = tbpImg.getBoundingClientRect();
            var asideBottom = imgRect.bottom;
            var contentRight = contentRect.right;
            var docWidth = document.documentElement.clientWidth;
            var fixedBottom = window.innerHeight - shareHeight - bottomGap;
            var triggerLine = window.innerHeight - bottomGap;
            var contentInnerBottom = contentRect.bottom - contentPaddingBottom;

            if(asideBottom > triggerLine){
                tbpShare.classList.remove('is_fixed', 'is_bottom');
                tbpShare.style.top = initialTop + 'px';
                tbpShare.style.right = '';
            } else if(contentInnerBottom > triggerLine){
                tbpShare.classList.add('is_fixed');
                tbpShare.classList.remove('is_bottom');
                tbpShare.style.top = fixedBottom + 'px';
                tbpShare.style.right = (docWidth - contentRight) + 'px';
            } else {
                tbpShare.classList.remove('is_fixed');
                tbpShare.classList.add('is_bottom');
                tbpShare.style.top = '';
                tbpShare.style.right = '';
            }
        }

        updateSharePosition();
        window.addEventListener('scroll', updateSharePosition);
        window.addEventListener('resize', function(){
            recalcShareDimensions();
            updateSharePosition();
        });

        var shareResizeObserver = new ResizeObserver(function(){
            recalcShareDimensions();
            updateSharePosition();
        });
        shareResizeObserver.observe(tbpContent);
        shareResizeObserver.observe(tbpImg);
    }

    // techBlog_post 최신글 Swiper
    var tbprSlideEl = document.querySelector('#techblog_post .tbpr_slide');
    if(tbprSlideEl){
        new Swiper(tbprSlideEl, {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 24,
            speed: 600,
            cssMode: false,
            navigation: {
                prevEl: '.tbpr_prev',
                nextEl: '.tbpr_next'
            },
            observer: true,
            observeParents: true
        });
    }

    // techBlog_list 필터 탭 active 토글
    var tblFilterLinks = document.querySelectorAll('#techBlog_list .tbl_filter ul li a');
    if(tblFilterLinks.length){
        tblFilterLinks.forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                var parentLi = this.closest('li');
                var siblings = parentLi.parentElement.children;
                for(var i = 0; i < siblings.length; i++){
                    siblings[i].classList.remove('active');
                }
                parentLi.classList.add('active');
            });
        });
    }

    // #contact_main .cfa_item: 순차 해금 — 이전 항목이 .filled일 때만 다음이 .active (입력 자체는 항상 가능)
    var cfaItems = document.querySelectorAll('#contact_main .cfa_item');
    if(cfaItems.length){
        var cfaItemsArr = Array.prototype.slice.call(cfaItems);
        var cfaSubmitBtn = document.querySelector('#contact_main .cfa_submit');

        function updateCfaLockStates(){
            cfaItemsArr.forEach(function(item, idx){
                var prevItem = idx === 0 ? null : cfaItemsArr[idx - 1];
                var unlocked = idx === 0 || (prevItem && prevItem.classList.contains('filled'));
                if(unlocked){
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
            if(cfaSubmitBtn){
                var allFilled = cfaItemsArr.every(function(item){
                    return item.classList.contains('filled');
                });
                cfaSubmitBtn.disabled = !allFilled;
                cfaSubmitBtn.classList.toggle('is-ready', allFilled);
            }
        }

        // reload/autofill 대비: 현재 input value 기반으로 .filled 전체 재동기화
        var syncAllCfaFilled = function(){
            cfaItemsArr.forEach(function(item){
                var inputEl = item.querySelector('input, textarea');
                if(!inputEl) return;
                if(inputEl.value.trim().length > 0){
                    item.classList.add('filled');
                } else {
                    item.classList.remove('filled');
                }
            });
            updateCfaLockStates();
        };

        cfaItemsArr.forEach(function(item){
            var inputEl = item.querySelector('input, textarea');
            if(!inputEl) return;
            inputEl.disabled = false;
            var toggleFilled = function(){
                if(inputEl.value.trim().length > 0){
                    item.classList.add('filled');
                } else {
                    item.classList.remove('filled');
                }
                updateCfaLockStates();
            };
            inputEl.addEventListener('input', toggleFilled);
            inputEl.addEventListener('change', toggleFilled);
        });

        // 초기 동기화 + bfcache 복원 시 재동기화 + 늦은 autofill 대응
        syncAllCfaFilled();
        window.addEventListener('pageshow', syncAllCfaFilled);
        window.addEventListener('load', syncAllCfaFilled);

        // #contact_main .ctpg_indicator: cfa_item 작성 진행도 표시
        var ctpgIndicator = document.querySelector('#contact_main .ctpg_indicator');
        if(ctpgIndicator){
            var lang = (typeof detectLang === 'function') ? detectLang() : 'kor';
            var ctpgLabelMapAll = {
                kor: {
                    company: '회사명',
                    manager: '담당자명',
                    position: '직책',
                    email: '회사 이메일',
                    phone: '전화번호',
                    message: '문의사항'
                },
                eng: {
                    company: 'Company',
                    manager: 'Name',
                    position: 'Job Title',
                    email: 'Business Email',
                    phone: 'Phone',
                    message: 'Message'
                },
                jp: {
                    company: '会社名',
                    manager: '担当者名',
                    position: '役職',
                    email: '会社メール',
                    phone: '電話番号',
                    message: 'お問い合わせ内容'
                }
            };
            var ctpgLabelMap = ctpgLabelMapAll[lang] || ctpgLabelMapAll.kor;
            var ctpgPad = function(n){ return n < 10 ? '0' + n : String(n); };

            var ctpgHtml = '<ul class="ctpg_indi_list">';
            cfaItemsArr.forEach(function(item, idx){
                var inputEl = item.querySelector('input, textarea');
                var nameAttr = inputEl ? (inputEl.getAttribute('name') || '') : '';
                var label = ctpgLabelMap[nameAttr] || ('Step ' + (idx + 1));
                ctpgHtml += '<li class="ctpg_indi_item" data-step="' + (idx + 1) + '">';
                ctpgHtml +=     '<span class="ctpg_indi_bar"><i></i></span>';
                ctpgHtml +=     '<span class="ctpg_indi_num">' + ctpgPad(idx + 1) + '</span>';
                ctpgHtml +=     '<span class="ctpg_indi_name">' + label + '</span>';
                ctpgHtml += '</li>';
            });
            ctpgHtml += '</ul>';
            ctpgIndicator.innerHTML = ctpgHtml;

            var ctpgIndiItems = ctpgIndicator.querySelectorAll('.ctpg_indi_item');

            var syncCtpgIndicator = function(){
                cfaItemsArr.forEach(function(item, idx){
                    var indi = ctpgIndiItems[idx];
                    if(!indi) return;
                    indi.classList.remove('is-active', 'is-filled');
                    if(item.classList.contains('filled')){
                        indi.classList.add('is-filled');
                    } else if(idx === 0){
                        indi.classList.add('is-active');
                    }
                });
            };

            if(typeof MutationObserver !== 'undefined'){
                var ctpgObserver = new MutationObserver(syncCtpgIndicator);
                cfaItemsArr.forEach(function(item){
                    ctpgObserver.observe(item, { attributes: true, attributeFilter: ['class'] });
                });
            }
            syncCtpgIndicator();

            // #contact_main .ctpg_indi_list: 모바일에서 contact_main 내에서만 viewport bottom에 fixed (asp_box.bot 패턴)
            var ctpgIndiList = ctpgIndicator.querySelector('.ctpg_indi_list');
            var ctpgHeader = document.querySelector('#header');
            var ctpgContactMain = document.querySelector('#contact_main');
            if(ctpgIndiList && ctpgHeader && ctpgContactMain){
                var ctpgFixBp = 864;
                var ctpgFixGap = 20;

                // slide-out 스크롤 구간을 listH의 2.4배로 늘려 진행을 천천히
                var ctpgSlideMult = 2.4;
                // lerp 스무딩 계수 (0에 가까울수록 더 느리고 부드러움)
                var ctpgSmoothing = 0.12;

                var ctpgTargetY = 0;
                var ctpgTargetOp = 1;
                var ctpgTargetFixed = false;
                var ctpgCurrentY = 0;
                var ctpgCurrentOp = 1;
                var ctpgRafId = null;

                var ctpgClearFixed = function(){
                    if(ctpgIndiList.classList.contains('is-fixed')){
                        ctpgIndiList.classList.remove('is-fixed');
                    }
                    ctpgIndiList.style.bottom = '';
                    ctpgIndiList.style.transform = '';
                    ctpgIndiList.style.opacity = '';
                    ctpgIndiList.style.transition = '';
                    ctpgIndiList.style.willChange = '';
                    ctpgIndicator.style.minHeight = '';
                    ctpgCurrentY = 0;
                    ctpgCurrentOp = 1;
                };

                var ctpgComputeTarget = function(){
                    var isFixMode = window.innerWidth <= ctpgFixBp;
                    if(!isFixMode){
                        ctpgTargetFixed = false;
                        return;
                    }
                    var contactRect = ctpgContactMain.getBoundingClientRect();
                    var viewportH = window.innerHeight;
                    var inSection = contactRect.top <= viewportH && contactRect.bottom > 0;
                    if(!inSection){
                        ctpgTargetFixed = false;
                        return;
                    }
                    ctpgTargetFixed = true;
                    var listH = ctpgIndiList.offsetHeight;
                    var slideDist = Math.max(0, viewportH - contactRect.bottom);
                    var range = Math.max(1, listH * ctpgSlideMult);
                    var progress = Math.min(1, slideDist / range);
                    // smoothstep: 부드러운 가감속
                    var eased = progress * progress * (3 - 2 * progress);
                    ctpgTargetY = eased * listH;
                    ctpgTargetOp = Math.max(0, 1 - progress);
                };

                var ctpgTick = function(){
                    ctpgRafId = null;
                    var wasFixed = ctpgIndiList.classList.contains('is-fixed');
                    ctpgComputeTarget();

                    if(!ctpgTargetFixed){
                        if(wasFixed){
                            ctpgClearFixed();
                        }
                        return;
                    }

                    var justEntered = !wasFixed;
                    if(justEntered){
                        ctpgIndicator.style.minHeight = ctpgIndiList.offsetHeight + 'px';
                        ctpgIndiList.classList.add('is-fixed');
                        ctpgIndiList.style.transition = 'none';
                        ctpgIndiList.style.willChange = 'transform, opacity';
                        // 재진입 시 깜빡임 방지: 현재값을 타겟으로 스냅
                        ctpgCurrentY = ctpgTargetY;
                        ctpgCurrentOp = ctpgTargetOp;
                    } else {
                        ctpgCurrentY += (ctpgTargetY - ctpgCurrentY) * ctpgSmoothing;
                        ctpgCurrentOp += (ctpgTargetOp - ctpgCurrentOp) * ctpgSmoothing;
                    }

                    ctpgIndiList.style.transform = 'translate3d(0, ' + ctpgCurrentY + 'px, 0)';
                    ctpgIndiList.style.opacity = ctpgCurrentOp;

                    // 타겟에 충분히 가까워질 때까지 계속 보간
                    var dy = ctpgTargetY - ctpgCurrentY;
                    var dop = ctpgTargetOp - ctpgCurrentOp;
                    if(Math.abs(dy) > 0.15 || Math.abs(dop) > 0.002){
                        ctpgRafId = window.requestAnimationFrame(ctpgTick);
                    }
                };

                var ctpgRequestUpdate = function(){
                    if(ctpgRafId !== null) return;
                    ctpgRafId = window.requestAnimationFrame(ctpgTick);
                };

                window.addEventListener('scroll', ctpgRequestUpdate, { passive: true });
                window.addEventListener('resize', ctpgRequestUpdate);
                window.addEventListener('load', ctpgRequestUpdate);
                window.addEventListener('pageshow', ctpgRequestUpdate);
                ctpgTick();

                // #contact_main .ctpg_indi_item: 모바일에서 viewport bottom이 각 cfa_item top에 도달할 때마다 active 누적
                var ctpgUpdateScrollActive = function(){
                    var isFixMode = window.innerWidth <= ctpgFixBp;
                    if(!isFixMode){
                        ctpgIndiItems.forEach(function(indi){ indi.classList.remove('is-scroll-active'); });
                        return;
                    }
                    var viewportBottom = window.innerHeight;
                    cfaItemsArr.forEach(function(item, idx){
                        var indi = ctpgIndiItems[idx];
                        if(!indi) return;
                        var itemTop = item.getBoundingClientRect().top;
                        if(itemTop <= viewportBottom){
                            indi.classList.add('is-scroll-active');
                        } else {
                            indi.classList.remove('is-scroll-active');
                        }
                    });
                };
                window.addEventListener('scroll', ctpgUpdateScrollActive, { passive: true });
                window.addEventListener('resize', ctpgUpdateScrollActive);
                window.addEventListener('load', ctpgUpdateScrollActive);
                window.addEventListener('pageshow', ctpgUpdateScrollActive);
                ctpgUpdateScrollActive();

                // #contact_main .ctpg_indi_item: 모바일에서 클릭 시 해당 cfa_item 으로 스크롤
                ctpgIndiItems.forEach(function(indi){
                    indi.style.cursor = 'pointer';
                    indi.addEventListener('click', function(){
                        if(window.innerWidth > ctpgFixBp) return;
                        var step = indi.getAttribute('data-step');
                        if(!step) return;
                        var target = document.querySelector('#contact_main .cfa_item[data-step="' + step + '"]');
                        if(!target) return;
                        var headerH = ctpgHeader.getBoundingClientRect().height;
                        var listH = ctpgIndiList.offsetHeight;
                        var offset = headerH + ctpgFixGap + listH + 20;
                        var targetTop = target.getBoundingClientRect().top + window.scrollY - offset;
                        window.scrollTo({ top: targetTop, behavior: 'smooth' });
                    });
                });
            }
        }
    }

    // #aslan_platform .asp_box.bot 헤더 bottom 도달 시 fixed, #aslan_studio 종료 시 해제
    var aspBoxBot = document.querySelector('#aslan_platform .asp_box.bot');
    var aspStudioSec = document.querySelector('#aslan_studio');
    if(aspBoxBot && aspStudioSec){
        var aspHeaderH = 85;
        var aspPlaceholder = null;
        var aspIsFixed = false;
        // 헤더가 숨겨지는 섹션: 뷰포트 최상단이 이 안에 있으면 effectiveH=0
        var aspHideHeaderSecs = [
            document.querySelector('#aslan_flow'),
            document.querySelector('#aslan_studio')
        ].filter(Boolean);

        function getAspEffectiveHeaderH(){
            for(var i = 0; i < aspHideHeaderSecs.length; i++){
                var rect = aspHideHeaderSecs[i].getBoundingClientRect();
                if(rect.top <= 1 && rect.bottom > 1){
                    return 0;
                }
            }
            return aspHeaderH;
        }

        function updateAspBoxBotFix(){
            var boxH = aspBoxBot.offsetHeight;
            var originalTop;
            if(aspIsFixed && aspPlaceholder){
                originalTop = aspPlaceholder.getBoundingClientRect().top + window.scrollY;
            } else {
                originalTop = aspBoxBot.getBoundingClientRect().top + window.scrollY;
            }
            var studioBottom = aspStudioSec.getBoundingClientRect().bottom + window.scrollY;
            var scrollY = window.scrollY;
            var effectiveH = getAspEffectiveHeaderH();
            var fixStart = originalTop - aspHeaderH;
            var fixEnd = studioBottom - boxH - effectiveH;

            if(scrollY >= fixStart){
                if(!aspIsFixed){
                    aspPlaceholder = document.createElement('div');
                    aspPlaceholder.style.height = boxH + 'px';
                    aspBoxBot.parentNode.insertBefore(aspPlaceholder, aspBoxBot);
                    aspBoxBot.classList.add('is_fixed');
                    aspIsFixed = true;
                }
                if(scrollY > fixEnd){
                    // 해제 애니메이션 구간: transition 제거(스크롤 프레임마다 갱신)
                    aspBoxBot.style.transition = 'none';
                    aspBoxBot.style.top = (effectiveH - (scrollY - fixEnd)) + 'px';
                } else {
                    // 헤더 표시/숨김 경계에서 부드럽게 이동하도록 transition 허용
                    aspBoxBot.style.transition = '';
                    aspBoxBot.style.top = effectiveH + 'px';
                }
            } else {
                if(aspIsFixed){
                    aspBoxBot.classList.remove('is_fixed');
                    aspBoxBot.style.top = '';
                    aspBoxBot.style.transition = '';
                    if(aspPlaceholder && aspPlaceholder.parentNode){
                        aspPlaceholder.parentNode.removeChild(aspPlaceholder);
                    }
                    aspPlaceholder = null;
                    aspIsFixed = false;
                }
            }
        }

        window.addEventListener('scroll', updateAspBoxBotFix, { passive: true });
        window.addEventListener('resize', updateAspBoxBotFix);
        updateAspBoxBotFix();

        // 헤더가 sec_white 이외 섹션에 진입하면 .asp_box.bot을 다크 테마로 전환
        var aspWhiteSections = document.querySelectorAll('section[class*="sec_white"]');
        function updateAspBoxBotTheme(){
            var checkY = 0; // 뷰포트 최상단(헤더 위치) 기준
            var isOverWhite = false;
            aspWhiteSections.forEach(function(sec){
                var rect = sec.getBoundingClientRect();
                if(rect.top <= checkY && rect.bottom > checkY){
                    isOverWhite = true;
                }
            });
            aspBoxBot.classList.toggle('is_dark', !isOverWhite);
        }
        window.addEventListener('scroll', updateAspBoxBotTheme, { passive: true });
        window.addEventListener('resize', updateAspBoxBotTheme);
        updateAspBoxBotTheme();
    }

    // #aslan_platform .aspb_b_item .link_btn → #aslan_flow / #aslan_studio 스크롤
    var aspbLinkBtns = document.querySelectorAll('#aslan_platform .aspb_b_item .link_btn');
    if(aspbLinkBtns.length){
        var aspbTargets = ['#aslan_flow', '#aslan_studio'];
        var aspbTargetEls = [];
        aspbLinkBtns.forEach(function(btn, idx){
            var anchor = btn.querySelector('a');
            if(!anchor) return;
            var targetSel = aspbTargets[idx];
            if(!targetSel) return;
            anchor.setAttribute('href', targetSel);
            anchor.addEventListener('click', function(e){
                e.preventDefault();
                var target = document.querySelector(targetSel);
                if(!target) return;
                var targetTop = target.getBoundingClientRect().top + window.scrollY;
                window.scrollTo({ top: targetTop, behavior: 'smooth' });
            });
            aspbTargetEls[idx] = document.querySelector(targetSel);
        });

        function updateAspbLinkBtnActive(){
            var checkY = window.scrollY + 85 + 1;
            var activeIdx = -1;
            aspbTargetEls.forEach(function(sec, idx){
                if(!sec) return;
                var rect = sec.getBoundingClientRect();
                var top = rect.top + window.scrollY;
                var bottom = rect.bottom + window.scrollY;
                if(checkY >= top && checkY < bottom){
                    activeIdx = idx;
                }
            });
            aspbLinkBtns.forEach(function(btn, idx){
                if(idx === activeIdx){
                    btn.classList.add('is_active');
                } else {
                    btn.classList.remove('is_active');
                }
            });
        }

        window.addEventListener('scroll', updateAspbLinkBtnActive, { passive: true });
        window.addEventListener('resize', updateAspbLinkBtnActive);
        updateAspbLinkBtnActive();
    }

    // .sl_vis_container .vis_area 커스텀 가로 스크롤바 (모바일 상시 표시)
    var visAreas = document.querySelectorAll('.sl_vis_container .vis_area');
    visAreas.forEach(function(area){
        var scrollbar = document.createElement('div');
        scrollbar.className = 'vis_area_scrollbar';
        var thumb = document.createElement('div');
        thumb.className = 'vis_area_scrollbar_thumb';
        scrollbar.appendChild(thumb);
        area.parentNode.insertBefore(scrollbar, area.nextSibling);

        function updateVisScrollbar(){
            var scrollW = area.scrollWidth;
            var clientW = area.clientWidth;
            if(scrollW <= clientW + 1){
                thumb.style.width = '0px';
                return;
            }
            var barWidth = scrollbar.clientWidth;
            var ratio = clientW / scrollW;
            var thumbWidth = Math.max(barWidth * ratio, 24);
            var maxScroll = scrollW - clientW;
            var progress = maxScroll > 0 ? (area.scrollLeft / maxScroll) : 0;
            var thumbLeft = progress * (barWidth - thumbWidth);
            thumb.style.width = thumbWidth + 'px';
            thumb.style.transform = 'translateX(' + thumbLeft + 'px)';
        }

        area.addEventListener('scroll', updateVisScrollbar, { passive: true });
        window.addEventListener('resize', updateVisScrollbar);
        window.addEventListener('load', updateVisScrollbar);
        updateVisScrollbar();

        // thumb 드래그 → vis_area scrollLeft 연동
        var dragStartX = 0;
        var dragStartScroll = 0;
        var isDragging = false;

        function onPointerDown(e){
            var scrollW = area.scrollWidth;
            var clientW = area.clientWidth;
            if(scrollW <= clientW) return;
            e.preventDefault();
            isDragging = true;
            thumb.classList.add('is_dragging');
            dragStartX = (e.touches ? e.touches[0].clientX : e.clientX);
            dragStartScroll = area.scrollLeft;
            document.addEventListener('mousemove', onPointerMove);
            document.addEventListener('mouseup', onPointerUp);
            document.addEventListener('touchmove', onPointerMove, { passive: false });
            document.addEventListener('touchend', onPointerUp);
            document.addEventListener('touchcancel', onPointerUp);
        }

        function onPointerMove(e){
            if(!isDragging) return;
            if(e.cancelable) e.preventDefault();
            var clientX = (e.touches ? e.touches[0].clientX : e.clientX);
            var deltaX = clientX - dragStartX;
            var scrollW = area.scrollWidth;
            var clientW = area.clientWidth;
            var maxScroll = scrollW - clientW;
            if(maxScroll <= 0) return;
            var barWidth = scrollbar.clientWidth;
            var ratio = clientW / scrollW;
            var thumbWidth = Math.max(barWidth * ratio, 24);
            var trackLen = barWidth - thumbWidth;
            if(trackLen <= 0) return;
            var scrollDelta = deltaX * (maxScroll / trackLen);
            area.scrollLeft = dragStartScroll + scrollDelta;
        }

        function onPointerUp(){
            if(!isDragging) return;
            isDragging = false;
            thumb.classList.remove('is_dragging');
            document.removeEventListener('mousemove', onPointerMove);
            document.removeEventListener('mouseup', onPointerUp);
            document.removeEventListener('touchmove', onPointerMove);
            document.removeEventListener('touchend', onPointerUp);
            document.removeEventListener('touchcancel', onPointerUp);
        }

        thumb.addEventListener('mousedown', onPointerDown);
        thumb.addEventListener('touchstart', onPointerDown, { passive: false });

        // 트랙 클릭 → 해당 위치로 점프
        scrollbar.addEventListener('mousedown', function(e){
            if(e.target === thumb) return;
            var scrollW = area.scrollWidth;
            var clientW = area.clientWidth;
            var maxScroll = scrollW - clientW;
            if(maxScroll <= 0) return;
            var barRect = scrollbar.getBoundingClientRect();
            var clickX = e.clientX - barRect.left;
            var barWidth = scrollbar.clientWidth;
            var ratio = clientW / scrollW;
            var thumbWidth = Math.max(barWidth * ratio, 24);
            var targetThumbLeft = Math.min(Math.max(clickX - thumbWidth / 2, 0), barWidth - thumbWidth);
            var trackLen = barWidth - thumbWidth;
            area.scrollLeft = (targetThumbLeft / trackLen) * maxScroll;
        });
    });

    // techBlog_post 공유 aside 스크롤
    var tbpShare = document.querySelector('#techblog_post .tbp_share');
    var tbpContent = document.querySelector('#techblog_post .tbp_content');
    var tbpImg = document.querySelector('#techblog_post .tbp_content .tbp_img');
    if(tbpShare && tbpContent && tbpImg){
        var shareHeight = tbpShare.offsetHeight;
        var initialTop = tbpImg.offsetTop + tbpImg.offsetHeight - shareHeight;
        tbpShare.style.top = initialTop + 'px';

        var bottomGap = 40;

        function recalcShareDimensions(){
            shareHeight = tbpShare.offsetHeight;
            initialTop = tbpImg.offsetTop + tbpImg.offsetHeight - shareHeight;
        }

        function updateSharePosition(){
            var contentRect = tbpContent.getBoundingClientRect();
            var contentPaddingBottom = parseFloat(getComputedStyle(tbpContent).paddingBottom) || 0;
            var imgRect = tbpImg.getBoundingClientRect();
            var asideBottom = imgRect.bottom;
            var contentRight = contentRect.right;
            var docWidth = document.documentElement.clientWidth;
            var fixedBottom = window.innerHeight - shareHeight - bottomGap;
            var triggerLine = window.innerHeight - bottomGap;
            var contentInnerBottom = contentRect.bottom - contentPaddingBottom;

            if(asideBottom > triggerLine){
                tbpShare.classList.remove('is_fixed', 'is_bottom');
                tbpShare.style.top = initialTop + 'px';
                tbpShare.style.right = '';
            } else if(contentInnerBottom > triggerLine){
                tbpShare.classList.add('is_fixed');
                tbpShare.classList.remove('is_bottom');
                tbpShare.style.top = fixedBottom + 'px';
                tbpShare.style.right = (docWidth - contentRight) + 'px';
            } else {
                tbpShare.classList.remove('is_fixed');
                tbpShare.classList.add('is_bottom');
                tbpShare.style.top = '';
                tbpShare.style.right = '';
            }
        }

        updateSharePosition();
        window.addEventListener('scroll', updateSharePosition);
        window.addEventListener('resize', function(){
            recalcShareDimensions();
            updateSharePosition();
        });

        var shareResizeObserver = new ResizeObserver(function(){
            recalcShareDimensions();
            updateSharePosition();
        });
        shareResizeObserver.observe(tbpContent);
        shareResizeObserver.observe(tbpImg);
    }

    // techBlog_post 최신글 Swiper
    var tbprSlideEl = document.querySelector('#techblog_post .tbpr_slide');
    if(tbprSlideEl){
        new Swiper(tbprSlideEl, {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 24,
            speed: 600,
            cssMode: false,
            navigation: {
                prevEl: '.tbpr_prev',
                nextEl: '.tbpr_next'
            },
            observer: true,
            observeParents: true
        });
    }

    // caseStudies_post 공유 aside 스크롤
    var cspShare = document.querySelector('#caseStudies_post .csp_share');
    var cspContent = document.querySelector('#caseStudies_post .csp_content');
    var cspImg = document.querySelector('#caseStudies_post .csp_content .csp_img');
    if(cspShare && cspContent && cspImg){
        var cspShareHeight = cspShare.offsetHeight;
        var cspInitialTop = cspImg.offsetTop + cspImg.offsetHeight - cspShareHeight;
        cspShare.style.top = cspInitialTop + 'px';

        var cspBottomGap = 40;

        function recalcCspShareDimensions(){
            cspShareHeight = cspShare.offsetHeight;
            cspInitialTop = cspImg.offsetTop + cspImg.offsetHeight - cspShareHeight;
        }

        function updateCspSharePosition(){
            var contentRect = cspContent.getBoundingClientRect();
            var contentPaddingBottom = parseFloat(getComputedStyle(cspContent).paddingBottom) || 0;
            var imgRect = cspImg.getBoundingClientRect();
            var asideBottom = imgRect.bottom;
            var contentRight = contentRect.right;
            var docWidth = document.documentElement.clientWidth;
            var fixedBottom = window.innerHeight - cspShareHeight - cspBottomGap;
            var triggerLine = window.innerHeight - cspBottomGap;
            var contentInnerBottom = contentRect.bottom - contentPaddingBottom;

            if(asideBottom > triggerLine){
                cspShare.classList.remove('is_fixed', 'is_bottom');
                cspShare.style.top = cspInitialTop + 'px';
                cspShare.style.right = '';
            } else if(contentInnerBottom > triggerLine){
                cspShare.classList.add('is_fixed');
                cspShare.classList.remove('is_bottom');
                cspShare.style.top = fixedBottom + 'px';
                cspShare.style.right = (docWidth - contentRight) + 'px';
            } else {
                cspShare.classList.remove('is_fixed');
                cspShare.classList.add('is_bottom');
                cspShare.style.top = '';
                cspShare.style.right = '';
            }
        }

        updateCspSharePosition();
        window.addEventListener('scroll', updateCspSharePosition);
        window.addEventListener('resize', function(){
            recalcCspShareDimensions();
            updateCspSharePosition();
        });

        var cspShareResizeObserver = new ResizeObserver(function(){
            recalcCspShareDimensions();
            updateCspSharePosition();
        });
        cspShareResizeObserver.observe(cspContent);
        cspShareResizeObserver.observe(cspImg);
    }

    // caseStudies_post 최신글 Swiper
    var csprSlideEl = document.querySelector('#caseStudies_post .cspr_slide');
    if(csprSlideEl){
        new Swiper(csprSlideEl, {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 24,
            speed: 600,
            cssMode: false,
            navigation: {
                prevEl: '.cspr_prev',
                nextEl: '.cspr_next'
            },
            observer: true,
            observeParents: true
        });
    }

});
