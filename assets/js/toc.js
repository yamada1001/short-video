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
        const scrollPosition = window.pageYOffset;
        const header = document.getElementById('header');
        const headerOffset = header ? header.offsetHeight + 20 : 100;

        // 現在のスクロール位置に最も近い見出しを見つける
        let closestHeading = null;
        let closestDistance = Infinity;

        headings.forEach(function(heading) {
            // より正確な位置計算
            const rect = heading.getBoundingClientRect();
            const headingTop = rect.top + scrollPosition;
            const adjustedScrollPosition = scrollPosition + headerOffset;

            // 見出しがスクロール位置を超えている場合のみ考慮
            if (adjustedScrollPosition >= headingTop - 10) { // 10pxの余裕を持たせる
                const distance = adjustedScrollPosition - headingTop;
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestHeading = heading;
                }
            }
        });

        if (closestHeading) {
            activeId = closestHeading.id;
        } else if (headings.length > 0) {
            // ページ上部にいる場合は最初の見出しをアクティブに
            activeId = headings[0].id;
        }

        tocLinks.forEach(function(link) {
            link.classList.remove('toc-link--active');
            if (activeId && link.dataset.target === activeId) {
                link.classList.add('toc-link--active');

                // アクティブな項目を目次内でスクロール表示（PC版サイドバー）
                if (window.innerWidth > 768 && tocSidebar && link.classList.contains('toc-link')) {
                    // アクティブな項目を目次内で見えるようにスクロール
                    const linkOffsetTop = link.offsetTop;
                    const sidebarScrollTop = tocSidebar.scrollTop;
                    const sidebarHeight = tocSidebar.clientHeight;
                    const linkHeight = link.offsetHeight;

                    // リンクが見える範囲にない場合のみスクロール
                    if (linkOffsetTop < sidebarScrollTop || linkOffsetTop + linkHeight > sidebarScrollTop + sidebarHeight) {
                        // 目次の上部から1/3の位置に配置
                        const targetScrollTop = linkOffsetTop - sidebarHeight / 3;

                        tocSidebar.scrollTo({
                            top: Math.max(0, targetScrollTop),
                            behavior: 'smooth'
                        });
                    }
                }
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
