/* 
 * custom js Document
*/

document.addEventListener('DOMContentLoaded', ()=>{
	checkIfScrolled();
	initializeDrawerMenu();
	initializeSnbMenu();
	mainFunc(); 
});

window.addEventListener('load', ()=>{
	requestAnimationFrame(scrollAnimationEffect);
});

window.addEventListener('scroll', ()=>{
	requestAnimationFrame(scrollAnimationEffect);
});

/**
 * 스크롤 이벤트에 따라 `body` 요소에 스크롤 상태를 나타내는 클래스를 추가하거나 제거합니다.
 * 
 * - 사용자가 스크롤을 일정 기준(OFFSET) 이상 내리면 `body`에 `isScrolled` 클래스를 추가합니다.
 * - 스크롤 위치가 기준값 이하로 내려가면 해당 클래스를 제거합니다.
 * - 브라우저의 성능을 고려하여 `requestAnimationFrame`을 사용해 최적화된 방식으로 클래스 변경을 처리합니다.
 */
function checkIfScrolled() {
    /**
     * @constant {number} OFFSET
     * 기준 스크롤 위치입니다. 
     * - 스크롤 값이 이 값을 초과하면 클래스를 추가합니다.
     * - 현재는 0으로 설정되어 있지만, 필요 시 `window.innerHeight / 2` 등으로 변경할 수 있습니다.
     */
    const OFFSET = 0;

    /**
     * @constant {string} ISSCROLLED
     * 스크롤 상태를 나타내기 위해 `body` 요소에 추가되는 클래스 이름입니다.
     */
    const ISSCROLLED = 'isScrolled';

    // 스크롤 이벤트 등록
    window.addEventListener('scroll', () => {
        // 스크롤 처리 최적화를 위해 requestAnimationFrame 사용
        requestAnimationFrame(() => {
            // 현재 스크롤 위치가 OFFSET을 초과하면 클래스를 추가, 아니면 제거
            document.body.classList.toggle(ISSCROLLED, window.scrollY > OFFSET);
        });
    });
}

/**
 * Drawer 메뉴를 초기화합니다.
 *
 * 주요 기능:
 * - Header의 GNB(nav)를 복사하여 Drawer 메뉴 생성
 * - Drawer 토글 버튼으로 Drawer 열기/닫기
 * - ESC 키 입력 및 백드롭 클릭 시 Drawer 닫기
 * - Drawer 내 GNB에서 서브 메뉴 토글 버튼 생성 및 동작 처리
 *
 * @throws {Error} header 내 `nav` 요소가 없을 경우
 * @throws {Error} `#drawer` 요소가 없을 경우
 * @throws {Error} `#drawer-toggle` 요소가 없을 경우
 * @throws {Error} Drawer 내 `.gnb` 요소가 없을 경우
 */
function initializeDrawerMenu() {
    // 상수 정의: DOM 요소 ID 및 클래스 이름
    const DRAWER = 'drawer';
    const DRAWER_TOGGLE = 'drawer-toggle';
    const DRAWER_BACKDROP = 'drawer-backdrop';
    const DRAWER_CLOSE = 'drawer-close';
    const DRAWER_GNB = 'drawer-gnb';
    const DRAWER_EXPANDED = 'drawerExpanded';
    const IS_EXPANDED = 'isExpanded';

    try {
        // 주요 DOM 요소 초기화
        const navEl = document.querySelector('header nav');
        if (!navEl) throw new Error('header 내 nav 요소를 찾을 수 없습니다.');

        const drawerEl = document.getElementById(DRAWER);
        if (!drawerEl) throw new Error(`#${DRAWER} 요소를 찾을 수 없습니다.`);

        const drawerToggleEl = document.getElementById(DRAWER_TOGGLE);
        if (!drawerToggleEl) throw new Error(`#${DRAWER_TOGGLE} 요소를 찾을 수 없습니다.`);

        const drawerCloseEl = document.getElementById(DRAWER_CLOSE);
        const drawerBackdropEl = document.getElementById(DRAWER_BACKDROP);

        /**
         * Header의 nav 요소를 복사하여 Drawer에 추가
         */
        function cloneNavToDrawer() {
            const navClone = navEl.cloneNode(true); // nav 요소 깊은 복사
            drawerEl.appendChild(navClone); // Drawer에 추가

            // 복사된 nav의 gnb 클래스 변경
            const gnbEl = drawerEl.querySelector('.gnb');
            if (!gnbEl) throw new Error("Drawer 내 gnb 요소를 찾을 수 없습니다.");
            gnbEl.classList.replace('gnb', DRAWER_GNB);
        }

        /**
         * Drawer 내 GNB 항목에 서브 메뉴 토글 버튼 추가
         */
        function setupMenuButtons() {
            const drawerGnbItems = drawerEl.querySelectorAll(`.${DRAWER_GNB} > li`);

            drawerGnbItems.forEach(item => {
                const subMenuEl = item.querySelector('.sub-menu');
                if (!subMenuEl) return; // 서브 메뉴가 없으면 스킵

                const gnbLink = item.querySelector('a');
                const visualClass = gnbLink.dataset.visualClass;

                // 서브 메뉴 토글 버튼 생성
                const toggleButton = document.createElement('button');
                toggleButton.type = 'button';
                toggleButton.id = `${visualClass}-button`;
                toggleButton.textContent = gnbLink.textContent;
				toggleButton.dataset.gnb = 1;
                toggleButton.setAttribute('aria-controls', `${visualClass}-menu`);
                toggleButton.setAttribute('aria-expanded', 'false');

                // 링크 뒤에 버튼 추가
                gnbLink.insertAdjacentElement('afterend', toggleButton);

                // 서브 메뉴 접근성 설정
                subMenuEl.id = `${visualClass}-menu`;
                subMenuEl.setAttribute('aria-labelledby', toggleButton.id);
            });
        }

        /**
         * Drawer 서브 메뉴 열기/닫기
         * @param {MouseEvent} event - 클릭 이벤트 객체
         */
        function toggleSubmenu(event) {
            const button = event.target;
            if (!button.hasAttribute('aria-expanded')) return;

            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            const submenuId = button.getAttribute('aria-controls');
            const submenuEl = document.getElementById(submenuId);

            // 모든 버튼 닫기
            document.querySelectorAll(`.${DRAWER_GNB} button`).forEach(btn => {
                btn.setAttribute('aria-expanded', 'false');
            });

            // 현재 버튼 및 서브 메뉴 상태 토글
            if (!isExpanded) {
                button.setAttribute('aria-expanded', 'true');
                submenuEl?.classList.add(IS_EXPANDED);
            } else {
                submenuEl?.classList.remove(IS_EXPANDED);
            }
        }

        /**
         * Drawer 열기/닫기 토글
         * @param {boolean|null} open - 열기(true), 닫기(false), 토글(null)
         */
        function toggleDrawer(open = null) {
            const isCurrentlyExpanded = drawerToggleEl.getAttribute('aria-expanded') === 'true';
            const shouldExpand = open !== null ? open : !isCurrentlyExpanded;

            document.body.classList.toggle(DRAWER_EXPANDED, shouldExpand);
            drawerToggleEl.setAttribute('aria-expanded', shouldExpand);
            drawerEl.classList.toggle(IS_EXPANDED, shouldExpand);
        }

        /**
         * ESC 키 입력으로 Drawer 닫기
         * @param {KeyboardEvent} event - 키보드 이벤트 객체
         */
        function closeDrawerOnEscape(event) {
            if (event.key === 'Escape') toggleDrawer(false);
        }

        // 초기화 작업 실행
        cloneNavToDrawer();
        setupMenuButtons();

        // 이벤트 리스너 등록
		drawerEl.addEventListener('click', toggleSubmenu);
		drawerToggleEl.addEventListener('click', toggleDrawer);
		drawerCloseEl?.addEventListener('click', () => toggleDrawer(false));
		drawerBackdropEl?.addEventListener('click', () => toggleDrawer(false));
		document.addEventListener('keydown', closeDrawerOnEscape);

    } catch (error) {
        console.error('initializeDrawerMenu 초기화 중 오류:', error);
    }
}


/**
 * 방문한 페이지의 url과 `[data-gnb]`의 herf를 비교하여 현재 방문 중인 앵커에 `ISVISITING` 클래스를 추가하고,
 * gnb 1, 2차 메뉴명을 `[data-menu-snb]`에 출력합니다.
 */
function initializeSnbMenu(){
	const ISVISITING = 'isVisiting';
	const urlPath = window.location.pathname;
	const urlSearch = decodeURIComponent(window.location.search);
	const mode = new URL(location.href).searchParams.get('mode');
	const category = new URL(location.href).searchParams.get('cate');
	const parameters = {};
	[...new URLSearchParams(new URL(window.location.href).search)].forEach(function([key, value]) {
		if(key.endsWith('_id')){
			parameters.item = key;
			parameters.value = value;
		}
	});
	const programHref = `${urlPath}?${parameters.item}=${parameters.value}`;
	
	// url 비교해서 방문 중인 앵커에 isVIsiting 클래스 추가, 푸터 메뉴 돔 불러온 뒤에
	document.querySelectorAll('[data-gnb]').forEach(function(element) {
		if(element.getAttribute('href') === undefined) return; // 모바일 토글 버튼 건너뛰기
		if(element.getAttribute('href') === urlPath || element.getAttribute('href') === programHref || (urlPath+urlSearch).includes(element.getAttribute('href')) ){
			// 카테고리별 메뉴가 있을 경우 구분해서 클래스 추가, 미완성 코드 수정 필요
			// if( category == null ){
			// 	element.classList.add(ISVISITING);
			// }else if( element.getAttribute('href') === `${programHref}&cate=${category}` ){
			// 	element.classList.add(ISVISITING);
			// }
			element.classList.add(ISVISITING);

			// 활성화된 1차, 2차 메뉴가 다를 경우 1차 메뉴에 방문 추가
			const gnbLevel = element.getAttribute('data-gnb');
			if( gnbLevel != '1' ){
				element.closest('.sub-menu').previousElementSibling.classList.add(ISVISITING);
				// element.closest('.dropdown-menu').previousElementSibling.classList.add(ISVISITING);
			}

			// 서브 비주얼 클래스 추가
			const visualEl = document.querySelector('.area-subVisual');
			const visualClass = document.querySelector(`.${ISVISITING}[data-gnb="1"]`)?.getAttribute('data-visual-class');
			if( visualClass != undefined ){
				visualEl?.classList.add(visualClass);
			}else{
				visualEl?.classList.add('common'); // 회원 메뉴 다르게 설정하려면 member
			}
		}
	});

	// 1, 2차 타이틀 선언
	const gnbMenu1Visiting = document.querySelector(`.gnb .${ISVISITING}[data-gnb="1"], footer .${ISVISITING}[data-gnb="1"]`);
	const gnbMenu2Visiting = document.querySelector(`.gnb .${ISVISITING}[data-gnb="2"]`);
	const snbMenu1 = document.querySelectorAll('[data-menu-snb="1"]');
	const snbMenu2 = document.querySelectorAll('[data-menu-snb="2"]');
	let gnbTitle1 = gnbMenu1Visiting?.textContent;
	let gnbTitle2 = gnbMenu2Visiting?.textContent;

	// 1, 2차 타이틀 삽입
	if( gnbTitle1 == undefined ){ // 멤버처럼 gnb에 없는 페이지, mode에 따라서 구분
		switch (mode) {
			case "agree" : gnbTitle1 = '회원가입'; break;
			case "join" : gnbTitle1 = '회원가입'; break;
			case "join_proc" : gnbTitle1 = '회원가입'; break;
			case "login" : gnbTitle1 = '로그인'; break;
			case "login_proc" : gnbTitle1 = '로그인'; break;
			case "naver" : gnbTitle1 = '로그인'; break;
			case "kakao" : gnbTitle1 = '로그인'; break;
			case "logout" : gnbTitle1 = '로그아웃'; break;
			case "modify" : gnbTitle1 = '회원정보'; break;
			case "modify_proc" : gnbTitle1 = '회원정보'; break;
			case "secession" : gnbTitle1 = '회원정보'; break;
			case "check" : gnbTitle1 = '회원정보'; break;
			case "find_id" : gnbTitle1 = '아이디 찾기'; break;
			case "find_pw" : gnbTitle1 = '비밀번호 찾기'; break;
			case "find_proc" : gnbTitle1 = '비밀번호 찾기'; break;
			default: const memberTitle = document.querySelector(".join_area h2")?.innerText; gnbTitle1 = memberTitle; break;
		}
	}

	const ISFALLBACK = 'isFallback';
	snbMenu1.forEach(element => {
		element.textContent = gnbTitle1;
	});
	snbMenu2.forEach(element => {
		if( gnbMenu2Visiting && gnbTitle2 != '' ) {
			element.textContent = gnbTitle2;
		} else {
			element.textContent = gnbTitle1;
			element.classList.add(ISFALLBACK); // 2차 메뉴가 없는 페이지에서 제목을 숨겨야할 때 활용
		}
	});

	// lnb
	//1depth on true 2depth save
	if( gnbMenu2Visiting || $(gnbMenu1Visiting).next('ul').length > 0 ){
		$(gnbMenu1Visiting).next('ul').clone().prependTo('.lnb');
	}else{
		$(gnbMenu1Visiting).closest('ul').clone().prependTo('.lnb').find('li:has(a:not(.on))').remove();
	}

	// lnb dropdown
	// gnbMenu1Visiting.next('ul').clone().appendTo('.lnb__gnb.gnb2');
	
	// 1, 2차 메뉴가 모두 필요한 경우, 위 스크립트 주석 처리, appendTo 경로를 바꿔서 사용
	// const level1 = $('.lnbDepth1 nav');
	// const level2 = $('.lnbDepth2 nav');
	// $('.gnb').clone().appendTo(level1).find('.sub_menu').remove();
	// gnbMenu1Visiting.next('ul').clone().appendTo(level2);
	
	// lnb link #container add
	$('.lnb ul > li').each(function(){
		const lnbLink = $(this).find('a').attr('href');
		if( lnbLink.indexOf('#') < 0 ){
			$(this).find('a').attr('href',lnbLink + '#content');
		}
	});

	// sub nav 활성화된 만큼 스크롤, 기본 snb 방식이 아닐 경우 불필요하므로 삭제
	$(window).on('load', function(){
		if($("#header .gnb").is(":hidden")){
			if($('.lnb li').length > 0){
				const lnbLeft = $(`.lnb a.${ISVISITING}`).offset().left - 15;
				$('.lnb').animate( { scrollLeft : lnbLeft }, 1000 );
			}
		}
	});
}

/**
 * 접근성 있는 토글 버튼을 초기화합니다.
 * 
 * - 버튼 클릭 시 `aria-expanded` 속성을 전환하여 대상 요소의 표시 상태를 나타냅니다.
 * - 대상 요소의 클래스(`isExpanded`)를 추가하거나 제거하여 시각적 상태를 제어합니다.
 * 
 * @param {string} buttonSelector - 토글 버튼을 선택하기 위한 CSS 선택자.
 * 
 * @example
 * // HTML 예제:
 * // <button id="toggleBtn" aria-controls="content" aria-expanded="false">Toggle</button>
 * // <div id="content" class="hidden">내용</div>
 * 
 * // JavaScript 호출:
 * initAccessibleToggle('#toggleBtn');
 * 
 * // 작동 설명:
 * // 버튼을 클릭하면:
 * // 1. 버튼의 `aria-expanded` 속성이 `true` 또는 `false`로 토글됩니다.
 * // 2. 대상 요소(`#content`)에 `isExpanded` 클래스가 추가되거나 제거됩니다.
 * // 3. 이를 통해 대상 요소의 표시 상태를 제어할 수 있습니다.
 */
function initAccessibleToggle(buttonSelector) {
    /**
     * @constant {HTMLElement|null} toggleBtn
     * CSS 선택자로 찾은 토글 버튼 요소.
     * - 지정된 버튼이 없을 경우 함수는 동작하지 않습니다.
     */
    const toggleBtn = document.querySelector(buttonSelector);

    // 버튼이 존재할 경우 클릭 이벤트 추가
    toggleBtn?.addEventListener('click', () => {
        /**
         * @constant {string|null} controlsId
         * `aria-controls` 속성에 지정된 대상 요소의 ID.
         */
        const controlsId = toggleBtn.getAttribute('aria-controls');

        /**
         * @constant {HTMLElement|null} controlsEl
         * `aria-controls`로 연결된 대상 요소.
         */
        const controlsEl = document.getElementById(controlsId);

        /**
         * @constant {boolean} isExpanded
         * 버튼의 현재 `aria-expanded` 상태 (true/false).
         */
        const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';

        // 상태 변경: aria-expanded 속성 및 클래스 토글
        toggleBtn.setAttribute('aria-expanded', String(!isExpanded));
        controlsEl?.classList.toggle('isExpanded', !isExpanded);
    });
}


function scrollAnimationEffect() {
	if (!$('[data-se]').length) return;

	const aniPosition = window.innerHeight / 5;
	const aniList = $('[data-se]');
	const aniListColumn = $('[data-se-column]');
	const columnDelay = 150;

	aniList.each(function (i, el) {
		const $el = $(el);
		const offsetValue = $el.attr('data-se-offset') != undefined && window.matchMedia("(min-width: 1280px)").matches ? Number($el.attr('data-se-offset')) : 0;
		const conditionNum = $el.attr('data-value') != undefined ? 0 : aniPosition + offsetValue;

		if (window.innerHeight > el.getBoundingClientRect().top + conditionNum) {
			if ($el.parents('[data-se-offset]').length < 1) {
				$el.addClass('seActive');
			} else {
				if ($el.parents('[data-se-offset]').hasClass('seActive')) {
					$el.addClass('seActive');
				}
			}
		} else if (window.innerHeight < el.getBoundingClientRect().top + offsetValue) {
			$el.removeClass('seActive');
		}

		if (window.innerHeight > el.getBoundingClientRect().top && $el.attr('data-se-counter') != undefined) {
			if (!$el.is(':animated')) {
				const hasWon = $el.hasClass('won');
				let counterLength = $el.attr('data-se-counter').length;
				if (hasWon) { counterLength += 0.5; }

				$el.css('min-width', `${counterLength}ch`).css('text-align', 'right');
				
				$el.animate({
					Counter: $el.attr('data-se-counter')
				}, {
					duration: 800,
					step: function (now) {
						if ( hasWon ) {
							$el.text(Math.ceil(now).toLocaleString('ko-KR'));
						} else {
							$el.text(Math.ceil(now));
						}
					},
					complete: function () {
						$el.addClass('seComplete');
					}
				});
			}
		} else if (window.innerHeight < el.getBoundingClientRect().top && $el.attr('data-se-counter') != undefined) {
			$el.stop().prop('Counter', 0).removeClass('seComplete').text(0);
			$el.removeAttr('style');
		}

		if (!$el.attr('data-se').indexOf('parallax')) {
			const elementOffsetTop = $el.offset().top;
			const scrollPercentage = ($(window).scrollTop() - elementOffsetTop + $(window).height()) / ($(window).height() + $el.outerHeight());
			const translateValue = (scrollPercentage - 0.5) * 100;

			if ($el.attr('data-se') == 'parallax-l') {
				$el.css('transform', `translateX(${-translateValue}%)`);
			} else if ($el.attr('data-se') == 'parallax-r') {
				$el.css('transform', `translateX(${translateValue}%)`);
			} else if ($el.attr('data-se') == 'parallax-y') {
				const translateYNum = el.getBoundingClientRect().top;
				$el.attr('style', `--translateY:${translateYNum}px`);
			}
		}
	});

	aniListColumn.each(function (i, column) {
		const $column = $(column);
		const delayNum = $column.attr('data-se-delay') == undefined ? columnDelay : $column.attr('data-se-delay');
		$column.attr('style', `--delay: ${delayNum}`);
		$column.find('[data-se]').each(function (index, item) {
			$(item).attr('style', `--index:${index}`);
		});
	});
}

function mainFunc(){
	const visualEl = document.querySelector('.main .visual .swiper');
	if( !visualEl ) return;

	const visualSwiper = new Swiper(visualEl, {
		loop: true,
		effect: 'fade',
		pagination: {
			el: '.main .visual .pagination',
			clickable: true,
		},
		navigation: {
			prevEl: '.main .visual .prev',
			nextEl: '.main .visual .next',
		},
		autoplay: {
			delay: 4000,
		},
	});

	const serviceSwiper = new Swiper('.main .service .swiper', {
		slidesPerView: 'auto',
	})

	// const affiliateSwiper = new Swiper('.main .affiliate .swiper', {
	// 	loop: true,
	// 	slidesPerView: 1.2,
	// 	spaceBetween: 20,
	// 	breakpoints: {
	// 		768: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 50,
	// 		},
	// 	},
	// 	autoplay: {
	// 		delay: 3000,
	// 		pauseOnMouseEnter: true,
	// 	},
	// })

    const affiliateSwiper1 = new Swiper('.main .affiliate .partner-box1 .swiper', {
		loop: true,
		slidesPerView: 1,
		spaceBetween: 20,
		breakpoints: {
			768: {
				slidesPerView: 1,
				spaceBetween: 50,
			},
		},
		autoplay: {
			delay: 3000,
			pauseOnMouseEnter: true,
		},
	})
    const affiliateSwiper2 = new Swiper('.main .affiliate .partner-box2 .swiper', {
		loop: true,
		slidesPerView: 1,
		spaceBetween: 20,
		breakpoints: {
			768: {
				slidesPerView: 1,
				spaceBetween: 50,
			},
		},
		autoplay: {
			delay: 3050,
			pauseOnMouseEnter: true,
		},
	})

	new daum.roughmap.Lander({
		"timestamp" : "1733796321367",
		"key" : "2mgmn",
	}).render();
} 