/**
 * 目次機能
 * PC: 追従型サイドバー
 * SP: スクロール停止時にボタン表示、モーダル形式で目次表示
 */

(function() {
    'use strict';

    const tocButton = document.getElementById('tocButton');
    const tocModal = document.getElementById('tocModal');
    const tocModalOverlay = document.getElementById('tocModalOverlay');
    const tocModalClose = document.getElementById('tocModalClose');
    const tocSidebar = document.getElementById('tocSidebar');
    const tocLinks = document.querySelectorAll('.toc-link, .toc-modal__link');

    let scrollTimer;
    let isScrolling = false;

    // スクロール検知（SP版：ボタン表示制御）
    function handleScroll() {
        if (window.innerWidth <= 768) {
            // スクロール中はボタンを非表示
            if (tocButton) {
                tocButton.classList.remove('toc-button--visible');
            }
            isScrolling = true;

            // スクロールタイマーをクリア
            clearTimeout(scrollTimer);

            // スクロール停止後1秒でボタンを表示
            scrollTimer = setTimeout(function() {
                isScrolling = false;
                if (tocButton && window.pageYOffset > 300) {
                    tocButton.classList.add('toc-button--visible');
                }
            }, 1000);
        }
    }

    // PC版：サイドバーの追従制御
    function handleSidebarScroll() {
        if (window.innerWidth > 768 && tocSidebar) {
            // stickyポジションを使用するため、特別な制御は不要
            // CSSのposition: stickyに任せる
        }
    }

    // アクティブな目次項目のハイライト
    function highlightActiveTocItem() {
        const headings = document.querySelectorAll('h2[id^="heading-"], h3[id^="heading-"]');
        let activeId = null;
        const header = document.getElementById('header');
        const headerHeight = header ? header.offsetHeight : 80;

        // スクロール位置の基準点（ヘッダーの下＋少し余白）
        const scrollThreshold = headerHeight + 100;

        // 現在のスクロール位置から最も近い見出しを探す
        let closestHeading = null;
        let minDistance = Infinity;

        headings.forEach(function(heading) {
            const rect = heading.getBoundingClientRect();
            const headingTop = rect.top;

            // ヘッダー下の基準点からの距離
            const distance = Math.abs(headingTop - scrollThreshold);

            // 見出しが基準点より上にあり、かつ最も近い場合
            if (headingTop <= scrollThreshold && distance < minDistance) {
                minDistance = distance;
                closestHeading = heading;
            }
        });

        // 見つからない場合は最初の見出しを使用
        if (closestHeading) {
            activeId = closestHeading.id;
        } else if (headings.length > 0) {
            activeId = headings[0].id;
        }

        // 目次内のリンクを更新
        let activeLink = null;
        tocLinks.forEach(function(link) {
            link.classList.remove('toc-link--active');
            if (activeId && link.dataset.target === activeId) {
                link.classList.add('toc-link--active');
                if (link.classList.contains('toc-link')) {
                    activeLink = link;
                }
            }
        });

        // アクティブな項目を目次内でスクロール表示（PC版サイドバー）
        if (window.innerWidth > 768 && tocSidebar && activeLink) {
            scrollTocToActiveItem(activeLink);
        }
    }

    // 目次サイドバーをスクロールしてアクティブな項目を表示
    function scrollTocToActiveItem(activeLink) {
        if (!tocSidebar || !activeLink) return;

        // アクティブなリンクの位置を計算
        const container = tocSidebar.querySelector('.toc-container');
        if (!container) return;

        const navElement = container.querySelector('.toc-nav');
        if (!navElement) return;

        // navElement内の全リンクを取得してインデックスを見つける
        const allLinks = Array.from(navElement.querySelectorAll('.toc-link'));
        const activeIndex = allLinks.indexOf(activeLink);

        if (activeIndex === -1) return;

        // 各リンクの高さを計算（padding含む）
        let totalHeight = 0;
        for (let i = 0; i < activeIndex; i++) {
            totalHeight += allLinks[i].offsetHeight;
        }

        // アクティブなリンクを中央に配置
        const sidebarHeight = tocSidebar.clientHeight;
        const activeLinkHeight = activeLink.offsetHeight;
        const scrollTarget = totalHeight - (sidebarHeight / 2) + (activeLinkHeight / 2);

        // .toc-containerのpadding-topを考慮
        const containerPadding = parseInt(getComputedStyle(container).paddingTop) || 0;
        const titleElement = container.querySelector('.toc-title');
        const titleHeight = titleElement ? titleElement.offsetHeight : 0;

        const finalScrollTarget = scrollTarget + containerPadding + titleHeight;

        tocSidebar.scrollTo({
            top: Math.max(0, finalScrollTarget),
            behavior: 'smooth'
        });
    }

    // モーダル開く
    function openModal() {
        if (tocModal) {
            tocModal.classList.add('toc-modal--open');
            document.body.style.overflow = 'hidden';
        }
    }

    // モーダル閉じる
    function closeModal() {
        if (tocModal) {
            tocModal.classList.remove('toc-modal--open');
            document.body.style.overflow = '';
        }
    }

    // 目次リンククリック時の処理
    function handleTocLinkClick(e) {
        const targetId = this.dataset.target;
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
            e.preventDefault();

            // モーダルを閉じる（SP版）
            closeModal();

            // スムーススクロール（動的なヘッダー高さを使用）
            const header = document.getElementById('header');
            const headerHeight = header ? header.offsetHeight : 80;
            const extraOffset = 20; // 余白
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - extraOffset;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    }

    // イベントリスナー設定
    if (tocButton) {
        tocButton.addEventListener('click', openModal);
    }

    if (tocModalClose) {
        tocModalClose.addEventListener('click', closeModal);
    }

    if (tocModalOverlay) {
        tocModalOverlay.addEventListener('click', closeModal);
    }

    // 目次リンクにクリックイベントを設定
    tocLinks.forEach(function(link) {
        link.addEventListener('click', handleTocLinkClick);
    });

    // スクロールイベント
    window.addEventListener('scroll', function() {
        handleScroll();
        handleSidebarScroll();
        highlightActiveTocItem();
    }, { passive: true });

    // リサイズイベント
    window.addEventListener('resize', function() {
        handleSidebarScroll();

        // SP→PCに切り替わった時はボタンを非表示
        if (window.innerWidth > 768 && tocButton) {
            tocButton.classList.remove('toc-button--visible');
        }
    });

    // 初期化
    handleScroll();
    handleSidebarScroll();
    highlightActiveTocItem();
})();
