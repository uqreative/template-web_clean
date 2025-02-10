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
 * ìŠ¤í¬ë¡¤ ì´ë²¤íŠ¸ì— ë”°ë¼ `body` ìš”ì†Œì— ìŠ¤í¬ë¡¤ ìƒíƒœë¥¼ ë‚˜íƒ€ë‚´ëŠ” í´ëž˜ìŠ¤ë¥¼ ì¶”ê°€í•˜ê±°ë‚˜ ì œê±°í•©ë‹ˆë‹¤.
 * 
 * - ì‚¬ìš©ìžê°€ ìŠ¤í¬ë¡¤ì„ ì¼ì • ê¸°ì¤€(OFFSET) ì´ìƒ ë‚´ë¦¬ë©´ `body`ì— `isScrolled` í´ëž˜ìŠ¤ë¥¼ ì¶”ê°€í•©ë‹ˆë‹¤.
 * - ìŠ¤í¬ë¡¤ ìœ„ì¹˜ê°€ ê¸°ì¤€ê°’ ì´í•˜ë¡œ ë‚´ë ¤ê°€ë©´ í•´ë‹¹ í´ëž˜ìŠ¤ë¥¼ ì œê±°í•©ë‹ˆë‹¤.
 * - ë¸Œë¼ìš°ì €ì˜ ì„±ëŠ¥ì„ ê³ ë ¤í•˜ì—¬ `requestAnimationFrame`ì„ ì‚¬ìš©í•´ ìµœì í™”ëœ ë°©ì‹ìœ¼ë¡œ í´ëž˜ìŠ¤ ë³€ê²½ì„ ì²˜ë¦¬í•©ë‹ˆë‹¤.
 */
function checkIfScrolled() {
    /**
     * @constant {number} OFFSET
     * ê¸°ì¤€ ìŠ¤í¬ë¡¤ ìœ„ì¹˜ìž…ë‹ˆë‹¤. 
     * - ìŠ¤í¬ë¡¤ ê°’ì´ ì´ ê°’ì„ ì´ˆê³¼í•˜ë©´ í´ëž˜ìŠ¤ë¥¼ ì¶”ê°€í•©ë‹ˆë‹¤.
     * - í˜„ìž¬ëŠ” 0ìœ¼ë¡œ ì„¤ì •ë˜ì–´ ìžˆì§€ë§Œ, í•„ìš” ì‹œ `window.innerHeight / 2` ë“±ìœ¼ë¡œ ë³€ê²½í•  ìˆ˜ ìžˆìŠµë‹ˆë‹¤.
     */
    const OFFSET = 0;

    /**
     * @constant {string} ISSCROLLED
     * ìŠ¤í¬ë¡¤ ìƒíƒœë¥¼ ë‚˜íƒ€ë‚´ê¸° ìœ„í•´ `body` ìš”ì†Œì— ì¶”ê°€ë˜ëŠ” í´ëž˜ìŠ¤ ì´ë¦„ìž…ë‹ˆë‹¤.
     */
    const ISSCROLLED = 'isScrolled';

    // ìŠ¤í¬ë¡¤ ì´ë²¤íŠ¸ ë“±ë¡
    window.addEventListener('scroll', () => {
        // ìŠ¤í¬ë¡¤ ì²˜ë¦¬ ìµœì í™”ë¥¼ ìœ„í•´ requestAnimationFrame ì‚¬ìš©
        requestAnimationFrame(() => {
            // í˜„ìž¬ ìŠ¤í¬ë¡¤ ìœ„ì¹˜ê°€ OFFSETì„ ì´ˆê³¼í•˜ë©´ í´ëž˜ìŠ¤ë¥¼ ì¶”ê°€, ì•„ë‹ˆë©´ ì œê±°
            document.body.classList.toggle(ISSCROLLED, window.scrollY > OFFSET);
        });
    });
}

/**
 * Drawer ë©”ë‰´ë¥¼ ì´ˆê¸°í™”í•©ë‹ˆë‹¤.
 *
 * ì£¼ìš” ê¸°ëŠ¥:
 * - Headerì˜ GNB(nav)ë¥¼ ë³µì‚¬í•˜ì—¬ Drawer ë©”ë‰´ ìƒì„±
 * - Drawer í† ê¸€ ë²„íŠ¼ìœ¼ë¡œ Drawer ì—´ê¸°/ë‹«ê¸°
 * - ESC í‚¤ ìž…ë ¥ ë° ë°±ë“œë¡­ í´ë¦­ ì‹œ Drawer ë‹«ê¸°
 * - Drawer ë‚´ GNBì—ì„œ ì„œë¸Œ ë©”ë‰´ í† ê¸€ ë²„íŠ¼ ìƒì„± ë° ë™ìž‘ ì²˜ë¦¬
 *
 * @throws {Error} header ë‚´ `nav` ìš”ì†Œê°€ ì—†ì„ ê²½ìš°
 * @throws {Error} `#drawer` ìš”ì†Œê°€ ì—†ì„ ê²½ìš°
 * @throws {Error} `#drawer-toggle` ìš”ì†Œê°€ ì—†ì„ ê²½ìš°
 * @throws {Error} Drawer ë‚´ `.gnb` ìš”ì†Œê°€ ì—†ì„ ê²½ìš°
 */
function initializeDrawerMenu() {
    // ìƒìˆ˜ ì •ì˜: DOM ìš”ì†Œ ID ë° í´ëž˜ìŠ¤ ì´ë¦„
    const DRAWER = 'drawer';
    const DRAWER_TOGGLE = 'drawer-toggle';
    const DRAWER_BACKDROP = 'drawer-backdrop';
    const DRAWER_CLOSE = 'drawer-close';
    const DRAWER_GNB = 'drawer-gnb';
    const DRAWER_EXPANDED = 'drawerExpanded';
    const IS_EXPANDED = 'isExpanded';

    try {
        // ì£¼ìš” DOM ìš”ì†Œ ì´ˆê¸°í™”
        const navEl = document.querySelector('header nav');
        if (!navEl) throw new Error('header ë‚´ nav ìš”ì†Œë¥¼ ì°¾ì„ ìˆ˜ ì—†ìŠµë‹ˆë‹¤.');

        const drawerEl = document.getElementById(DRAWER);
        if (!drawerEl) throw new Error(`#${DRAWER} ìš”ì†Œë¥¼ ì°¾ì„ ìˆ˜ ì—†ìŠµë‹ˆë‹¤.`);

        const drawerToggleEl = document.getElementById(DRAWER_TOGGLE);
        if (!drawerToggleEl) throw new Error(`#${DRAWER_TOGGLE} ìš”ì†Œë¥¼ ì°¾ì„ ìˆ˜ ì—†ìŠµë‹ˆë‹¤.`);

        const drawerCloseEl = document.getElementById(DRAWER_CLOSE);
        const drawerBackdropEl = document.getElementById(DRAWER_BACKDROP);

        /**
         * Headerì˜ nav ìš”ì†Œë¥¼ ë³µì‚¬í•˜ì—¬ Drawerì— ì¶”ê°€
         */
        function cloneNavToDrawer() {
            const navClone = navEl.cloneNode(true); // nav ìš”ì†Œ ê¹Šì€ ë³µì‚¬
            drawerEl.appendChild(navClone); // Drawerì— ì¶”ê°€

            // ë³µì‚¬ëœ navì˜ gnb í´ëž˜ìŠ¤ ë³€ê²½
            const gnbEl = drawerEl.querySelector('.gnb');
            if (!gnbEl) throw new Error("Drawer ë‚´ gnb ìš”ì†Œë¥¼ ì°¾ì„ ìˆ˜ ì—†ìŠµë‹ˆë‹¤.");
            gnbEl.classList.replace('gnb', DRAWER_GNB);
        }

        /**
         * Drawer ë‚´ GNB í•­ëª©ì— ì„œë¸Œ ë©”ë‰´ í† ê¸€ ë²„íŠ¼ ì¶”ê°€
         */
        function setupMenuButtons() {
            const drawerGnbItems = drawerEl.querySelectorAll(`.${DRAWER_GNB} > li`);

            drawerGnbItems.forEach(item => {
                const subMenuEl = item.querySelector('.sub-menu');
                if (!subMenuEl) return; // ì„œë¸Œ ë©”ë‰´ê°€ ì—†ìœ¼ë©´ ìŠ¤í‚µ

                const gnbLink = item.querySelector('a');
                const visualClass = gnbLink.dataset.visualClass;

                // ì„œë¸Œ ë©”ë‰´ í† ê¸€ ë²„íŠ¼ ìƒì„±
                const toggleButton = document.createElement('button');
                toggleButton.type = 'button';
                toggleButton.id = `${visualClass}-button`;
                toggleButton.textContent = gnbLink.textContent;
				toggleButton.dataset.gnb = 1;
                toggleButton.setAttribute('aria-controls', `${visualClass}-menu`);
                toggleButton.setAttribute('aria-expanded', 'false');

                // ë§í¬ ë’¤ì— ë²„íŠ¼ ì¶”ê°€
                gnbLink.insertAdjacentElement('afterend', toggleButton);

                // ì„œë¸Œ ë©”ë‰´ ì ‘ê·¼ì„± ì„¤ì •
                subMenuEl.id = `${visualClass}-menu`;
                subMenuEl.setAttribute('aria-labelledby', toggleButton.id);
            });
        }

        /**
         * Drawer ì„œë¸Œ ë©”ë‰´ ì—´ê¸°/ë‹«ê¸°
         * @param {MouseEvent} event - í´ë¦­ ì´ë²¤íŠ¸ ê°ì²´
         */
        function toggleSubmenu(event) {
            const button = event.target;
            if (!button.hasAttribute('aria-expanded')) return;

            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            const submenuId = button.getAttribute('aria-controls');
            const submenuEl = document.getElementById(submenuId);

            // ëª¨ë“  ë²„íŠ¼ ë‹«ê¸°
            document.querySelectorAll(`.${DRAWER_GNB} button`).forEach(btn => {
                btn.setAttribute('aria-expanded', 'false');
            });

            // í˜„ìž¬ ë²„íŠ¼ ë° ì„œë¸Œ ë©”ë‰´ ìƒíƒœ í† ê¸€
            if (!isExpanded) {
                button.setAttribute('aria-expanded', 'true');
                submenuEl?.classList.add(IS_EXPANDED);
            } else {
                submenuEl?.classList.remove(IS_EXPANDED);
            }
        }

        /**
         * Drawer ì—´ê¸°/ë‹«ê¸° í† ê¸€
         * @param {boolean|null} open - ì—´ê¸°(true), ë‹«ê¸°(false), í† ê¸€(null)
         */
        function toggleDrawer(open = null) {
            const isCurrentlyExpanded = drawerToggleEl.getAttribute('aria-expanded') === 'true';
            const shouldExpand = open !== null ? open : !isCurrentlyExpanded;

            document.body.classList.toggle(DRAWER_EXPANDED, shouldExpand);
            drawerToggleEl.setAttribute('aria-expanded', shouldExpand);
            drawerEl.classList.toggle(IS_EXPANDED, shouldExpand);
        }

        /**
         * ESC í‚¤ ìž…ë ¥ìœ¼ë¡œ Drawer ë‹«ê¸°
         * @param {KeyboardEvent} event - í‚¤ë³´ë“œ ì´ë²¤íŠ¸ ê°ì²´
         */
        function closeDrawerOnEscape(event) {
            if (event.key === 'Escape') toggleDrawer(false);
        }

        // ì´ˆê¸°í™” ìž‘ì—… ì‹¤í–‰
        cloneNavToDrawer();
        setupMenuButtons();

        // ì´ë²¤íŠ¸ ë¦¬ìŠ¤ë„ˆ ë“±ë¡
		drawerEl.addEventListener('click', toggleSubmenu);
		drawerToggleEl.addEventListener('click', toggleDrawer);
		drawerCloseEl?.addEventListener('click', () => toggleDrawer(false));
		drawerBackdropEl?.addEventListener('click', () => toggleDrawer(false));
		document.addEventListener('keydown', closeDrawerOnEscape);

    } catch (error) {
        console.error('initializeDrawerMenu ì´ˆê¸°í™” ì¤‘ ì˜¤ë¥˜:', error);
    }
}


/**
 * ë°©ë¬¸í•œ íŽ˜ì´ì§€ì˜ urlê³¼ `[data-gnb]`ì˜ herfë¥¼ ë¹„êµí•˜ì—¬ í˜„ìž¬ ë°©ë¬¸ ì¤‘ì¸ ì•µì»¤ì— `ISVISITING` í´ëž˜ìŠ¤ë¥¼ ì¶”ê°€í•˜ê³ ,
 * gnb 1, 2ì°¨ ë©”ë‰´ëª…ì„ `[data-menu-snb]`ì— ì¶œë ¥í•©ë‹ˆë‹¤.
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
	
	// url ë¹„êµí•´ì„œ ë°©ë¬¸ ì¤‘ì¸ ì•µì»¤ì— isVIsiting í´ëž˜ìŠ¤ ì¶”ê°€, í‘¸í„° ë©”ë‰´ ë” ë¶ˆëŸ¬ì˜¨ ë’¤ì—
	document.querySelectorAll('[data-gnb]').forEach(function(element) {
		if(element.getAttribute('href') === undefined) return; // ëª¨ë°”ì¼ í† ê¸€ ë²„íŠ¼ ê±´ë„ˆë›°ê¸°
		if(element.getAttribute('href') === urlPath || element.getAttribute('href') === programHref || (urlPath+urlSearch).includes(element.getAttribute('href')) ){
			// ì¹´í…Œê³ ë¦¬ë³„ ë©”ë‰´ê°€ ìžˆì„ ê²½ìš° êµ¬ë¶„í•´ì„œ í´ëž˜ìŠ¤ ì¶”ê°€, ë¯¸ì™„ì„± ì½”ë“œ ìˆ˜ì • í•„ìš”
			// if( category == null ){
			// 	element.classList.add(ISVISITING);
			// }else if( element.getAttribute('href') === `${programHref}&cate=${category}` ){
			// 	element.classList.add(ISVISITING);
			// }
			element.classList.add(ISVISITING);

			// í™œì„±í™”ëœ 1ì°¨, 2ì°¨ ë©”ë‰´ê°€ ë‹¤ë¥¼ ê²½ìš° 1ì°¨ ë©”ë‰´ì— ë°©ë¬¸ ì¶”ê°€
			const gnbLevel = element.getAttribute('data-gnb');
			if( gnbLevel != '1' ){
				element.closest('.sub-menu').previousElementSibling.classList.add(ISVISITING);
				// element.closest('.dropdown-menu').previousElementSibling.classList.add(ISVISITING);
			}

			// ì„œë¸Œ ë¹„ì£¼ì–¼ í´ëž˜ìŠ¤ ì¶”ê°€
			const visualEl = document.querySelector('.area-subVisual');
			const visualClass = document.querySelector(`.${ISVISITING}[data-gnb="1"]`)?.getAttribute('data-visual-class');
			if( visualClass != undefined ){
				visualEl?.classList.add(visualClass);
			}else{
				visualEl?.classList.add('common'); // íšŒì› ë©”ë‰´ ë‹¤ë¥´ê²Œ ì„¤ì •í•˜ë ¤ë©´ member
			}
		}
	});

	// 1, 2ì°¨ íƒ€ì´í‹€ ì„ ì–¸
	const gnbMenu1Visiting = document.querySelector(`.gnb .${ISVISITING}[data-gnb="1"], footer .${ISVISITING}[data-gnb="1"]`);
	const gnbMenu2Visiting = document.querySelector(`.gnb .${ISVISITING}[data-gnb="2"]`);
	const snbMenu1 = document.querySelectorAll('[data-menu-snb="1"]');
	const snbMenu2 = document.querySelectorAll('[data-menu-snb="2"]');
	let gnbTitle1 = gnbMenu1Visiting?.textContent;
	let gnbTitle2 = gnbMenu2Visiting?.textContent;

	// 1, 2ì°¨ íƒ€ì´í‹€ ì‚½ìž…
	if( gnbTitle1 == undefined ){ // ë©¤ë²„ì²˜ëŸ¼ gnbì— ì—†ëŠ” íŽ˜ì´ì§€, modeì— ë”°ë¼ì„œ êµ¬ë¶„
		switch (mode) {
			case "agree" : gnbTitle1 = 'íšŒì›ê°€ìž…'; break;
			case "join" : gnbTitle1 = 'íšŒì›ê°€ìž…'; break;
			case "join_proc" : gnbTitle1 = 'íšŒì›ê°€ìž…'; break;
			case "login" : gnbTitle1 = 'ë¡œê·¸ì¸'; break;
			case "login_proc" : gnbTitle1 = 'ë¡œê·¸ì¸'; break;
			case "naver" : gnbTitle1 = 'ë¡œê·¸ì¸'; break;
			case "kakao" : gnbTitle1 = 'ë¡œê·¸ì¸'; break;
			case "logout" : gnbTitle1 = 'ë¡œê·¸ì•„ì›ƒ'; break;
			case "modify" : gnbTitle1 = 'íšŒì›ì •ë³´'; break;
			case "modify_proc" : gnbTitle1 = 'íšŒì›ì •ë³´'; break;
			case "secession" : gnbTitle1 = 'íšŒì›ì •ë³´'; break;
			case "check" : gnbTitle1 = 'íšŒì›ì •ë³´'; break;
			case "find_id" : gnbTitle1 = 'ì•„ì´ë”” ì°¾ê¸°'; break;
			case "find_pw" : gnbTitle1 = 'ë¹„ë°€ë²ˆí˜¸ ì°¾ê¸°'; break;
			case "find_proc" : gnbTitle1 = 'ë¹„ë°€ë²ˆí˜¸ ì°¾ê¸°'; break;
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
			element.classList.add(ISFALLBACK); // 2ì°¨ ë©”ë‰´ê°€ ì—†ëŠ” íŽ˜ì´ì§€ì—ì„œ ì œëª©ì„ ìˆ¨ê²¨ì•¼í•  ë•Œ í™œìš©
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
	
	// 1, 2ì°¨ ë©”ë‰´ê°€ ëª¨ë‘ í•„ìš”í•œ ê²½ìš°, ìœ„ ìŠ¤í¬ë¦½íŠ¸ ì£¼ì„ ì²˜ë¦¬, appendTo ê²½ë¡œë¥¼ ë°”ê¿”ì„œ ì‚¬ìš©
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

	// sub nav í™œì„±í™”ëœ ë§Œí¼ ìŠ¤í¬ë¡¤, ê¸°ë³¸ snb ë°©ì‹ì´ ì•„ë‹ ê²½ìš° ë¶ˆí•„ìš”í•˜ë¯€ë¡œ ì‚­ì œ
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
 * ì ‘ê·¼ì„± ìžˆëŠ” í† ê¸€ ë²„íŠ¼ì„ ì´ˆê¸°í™”í•©ë‹ˆë‹¤.
 * 
 * - ë²„íŠ¼ í´ë¦­ ì‹œ `aria-expanded` ì†ì„±ì„ ì „í™˜í•˜ì—¬ ëŒ€ìƒ ìš”ì†Œì˜ í‘œì‹œ ìƒíƒœë¥¼ ë‚˜íƒ€ëƒ…ë‹ˆë‹¤.
 * - ëŒ€ìƒ ìš”ì†Œì˜ í´ëž˜ìŠ¤(`isExpanded`)ë¥¼ ì¶”ê°€í•˜ê±°ë‚˜ ì œê±°í•˜ì—¬ ì‹œê°ì  ìƒíƒœë¥¼ ì œì–´í•©ë‹ˆë‹¤.
 * 
 * @param {string} buttonSelector - í† ê¸€ ë²„íŠ¼ì„ ì„ íƒí•˜ê¸° ìœ„í•œ CSS ì„ íƒìž.
 * 
 * @example
 * // HTML ì˜ˆì œ:
 * // <button id="toggleBtn" aria-controls="content" aria-expanded="false">Toggle</button>
 * // <div id="content" class="hidden">ë‚´ìš©</div>
 * 
 * // JavaScript í˜¸ì¶œ:
 * initAccessibleToggle('#toggleBtn');
 * 
 * // ìž‘ë™ ì„¤ëª…:
 * // ë²„íŠ¼ì„ í´ë¦­í•˜ë©´:
 * // 1. ë²„íŠ¼ì˜ `aria-expanded` ì†ì„±ì´ `true` ë˜ëŠ” `false`ë¡œ í† ê¸€ë©ë‹ˆë‹¤.
 * // 2. ëŒ€ìƒ ìš”ì†Œ(`#content`)ì— `isExpanded` í´ëž˜ìŠ¤ê°€ ì¶”ê°€ë˜ê±°ë‚˜ ì œê±°ë©ë‹ˆë‹¤.
 * // 3. ì´ë¥¼ í†µí•´ ëŒ€ìƒ ìš”ì†Œì˜ í‘œì‹œ ìƒíƒœë¥¼ ì œì–´í•  ìˆ˜ ìžˆìŠµë‹ˆë‹¤.
 */
function initAccessibleToggle(buttonSelector) {
    /**
     * @constant {HTMLElement|null} toggleBtn
     * CSS ì„ íƒìžë¡œ ì°¾ì€ í† ê¸€ ë²„íŠ¼ ìš”ì†Œ.
     * - ì§€ì •ëœ ë²„íŠ¼ì´ ì—†ì„ ê²½ìš° í•¨ìˆ˜ëŠ” ë™ìž‘í•˜ì§€ ì•ŠìŠµë‹ˆë‹¤.
     */
    const toggleBtn = document.querySelector(buttonSelector);

    // ë²„íŠ¼ì´ ì¡´ìž¬í•  ê²½ìš° í´ë¦­ ì´ë²¤íŠ¸ ì¶”ê°€
    toggleBtn?.addEventListener('click', () => {
        /**
         * @constant {string|null} controlsId
         * `aria-controls` ì†ì„±ì— ì§€ì •ëœ ëŒ€ìƒ ìš”ì†Œì˜ ID.
         */
        const controlsId = toggleBtn.getAttribute('aria-controls');

        /**
         * @constant {HTMLElement|null} controlsEl
         * `aria-controls`ë¡œ ì—°ê²°ëœ ëŒ€ìƒ ìš”ì†Œ.
         */
        const controlsEl = document.getElementById(controlsId);

        /**
         * @constant {boolean} isExpanded
         * ë²„íŠ¼ì˜ í˜„ìž¬ `aria-expanded` ìƒíƒœ (true/false).
         */
        const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';

        // ìƒíƒœ ë³€ê²½: aria-expanded ì†ì„± ë° í´ëž˜ìŠ¤ í† ê¸€
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