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
            const article = document.querySelector('.article-main');
            if (!article) return;

            const articleRect = article.getBoundingClientRect();
            const tocRect = tocSidebar.getBoundingClientRect();
            const headerHeight = 80;
            const margin = 20;

            // 記事の開始位置より上にいる場合
            if (articleRect.top > headerHeight + margin) {
                tocSidebar.style.position = 'absolute';
                tocSidebar.style.top = '0';
            }
            // 記事の終了位置より下にいる場合
            else if (articleRect.bottom < tocRect.height + headerHeight + margin) {
                tocSidebar.style.position = 'absolute';
                tocSidebar.style.top = (articleRect.height - tocRect.height) + 'px';
            }
            // 通常の追従
            else {
                tocSidebar.style.position = 'sticky';
                tocSidebar.style.top = (headerHeight + margin) + 'px';
            }
        }
    }

    // アクティブな目次項目のハイライト
    function highlightActiveTocItem() {
        const headings = document.querySelectorAll('[id^="heading-"]');
        let activeId = null;

        headings.forEach(function(heading) {
            const rect = heading.getBoundingClientRect();
            if (rect.top <= 150 && rect.top >= -rect.height) {
                activeId = heading.id;
            }
        });

        tocLinks.forEach(function(link) {
            link.classList.remove('toc-link--active');
            if (activeId && link.dataset.target === activeId) {
                link.classList.add('toc-link--active');
            }
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

            // スムーススクロール
            const headerHeight = 80;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

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
