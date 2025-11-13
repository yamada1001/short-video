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

        // 画面の中央付近（視線の自然な位置）を基準にする
        const viewportMiddle = window.innerHeight / 3; // 上から1/3の位置

        // 画面内に表示されている見出しを探す
        let visibleHeading = null;
        let bestScore = -Infinity;

        headings.forEach(function(heading) {
            const rect = heading.getBoundingClientRect();

            // 見出しが画面内に表示されているか判定
            const isVisible = rect.top >= headerHeight && rect.bottom <= window.innerHeight;

            if (isVisible) {
                // 画面内にある場合、視線位置（1/3の位置）に近いほど高スコア
                const distanceFromIdealPosition = Math.abs(rect.top - viewportMiddle);
                const score = 1000 - distanceFromIdealPosition;

                if (score > bestScore) {
                    bestScore = score;
                    visibleHeading = heading;
                }
            } else if (rect.top < headerHeight) {
                // 画面外（上方）にある場合もスコアを計算
                // ただし、画面内の見出しより優先度を下げる
                const score = -rect.top;

                if (score > bestScore && !visibleHeading) {
                    bestScore = score;
                    visibleHeading = heading;
                }
            }
        });

        if (visibleHeading) {
            activeId = visibleHeading.id;
        } else if (headings.length > 0) {
            // どの見出しも条件に合わない場合は最初の見出しをアクティブに
            activeId = headings[0].id;
        }

        tocLinks.forEach(function(link) {
            link.classList.remove('toc-link--active');
            if (activeId && link.dataset.target === activeId) {
                link.classList.add('toc-link--active');

                // アクティブな項目を目次内でスクロール表示（PC版サイドバー）
                if (window.innerWidth > 768 && tocSidebar && link.classList.contains('toc-link')) {
                    // スクロール処理を次のフレームで実行（レンダリング後に確実に実行）
                    requestAnimationFrame(function() {
                        const linkRect = link.getBoundingClientRect();
                        const sidebarRect = tocSidebar.getBoundingClientRect();

                        // リンクが目次サイドバーの表示範囲内にあるか確認
                        const isLinkVisible = linkRect.top >= sidebarRect.top &&
                                             linkRect.bottom <= sidebarRect.bottom;

                        // リンクが見える範囲にない場合のみスクロール
                        if (!isLinkVisible) {
                            // .toc-navの親要素を取得
                            const tocNav = link.parentElement;

                            // linkのtocNav内での相対位置を取得
                            const linkOffsetTop = link.offsetTop;

                            // 目次サイドバーの現在のスクロール位置
                            const currentScroll = tocSidebar.scrollTop;

                            // 目次の高さ
                            const sidebarHeight = tocSidebar.clientHeight;

                            // 目次コンテナ内でのlinkの絶対位置を計算
                            // .toc-containerのpadding/margin分も考慮
                            const tocContainer = tocSidebar.querySelector('.toc-container');
                            const containerPaddingTop = tocContainer ? parseInt(getComputedStyle(tocContainer).paddingTop) : 0;

                            // 目標スクロール位置：アクティブな項目を上部から1/3の位置に
                            const targetScrollTop = linkOffsetTop - (sidebarHeight / 3) + containerPaddingTop;

                            tocSidebar.scrollTo({
                                top: Math.max(0, targetScrollTop),
                                behavior: 'smooth'
                            });
                        }
                    });
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
