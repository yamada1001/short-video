/**
 * 旅行栞ページ用JavaScript
 * - チェックボックス機能（localStorage保存）
 * - 目次のアクティブ化＆スクロール連動
 * - SPモーダル目次
 */

document.addEventListener('DOMContentLoaded', () => {
    // ========================================
    // 1. チェックボックス機能
    // ========================================
    const checkboxes = document.querySelectorAll('.spot-checkbox');
    const pageKey = `checked_spots_${window.location.pathname}`;

    // 保存されたチェック状態を復元
    const loadCheckedState = () => {
        const savedData = localStorage.getItem(pageKey);
        if (savedData) {
            const checkedIds = JSON.parse(savedData);
            checkedIds.forEach(id => {
                const checkbox = document.getElementById(id);
                if (checkbox) {
                    checkbox.checked = true;
                    checkbox.closest('.spot-item').classList.add('checked');
                }
            });
        }
        updateStats();
    };

    // チェック状態を保存
    const saveCheckedState = () => {
        const checkedIds = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.id);
        localStorage.setItem(pageKey, JSON.stringify(checkedIds));
        updateStats();
    };

    // チェックボックスのイベントリスナー
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', (e) => {
            const spotItem = e.target.closest('.spot-item');
            if (e.target.checked) {
                spotItem.classList.add('checked');
            } else {
                spotItem.classList.remove('checked');
            }
            saveCheckedState();
        });
    });

    // 統計情報を更新
    const updateStats = () => {
        const total = checkboxes.length;
        const checked = Array.from(checkboxes).filter(cb => cb.checked).length;

        const checkedElement = document.querySelector('.checked-count');
        if (checkedElement) {
            checkedElement.textContent = checked;
        }

        const totalElement = document.querySelector('.total-count');
        if (totalElement) {
            totalElement.textContent = total;
        }
    };

    loadCheckedState();

    // ========================================
    // 2. 目次機能（PC: フローティング）
    // ========================================
    const tocLinks = document.querySelectorAll('.toc-link');
    const sections = document.querySelectorAll('.section');

    // スクロール位置に応じて目次をアクティブ化
    const activateTocOnScroll = () => {
        let currentSection = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            const sectionBottom = sectionTop + section.offsetHeight;

            if (window.scrollY >= sectionTop && window.scrollY < sectionBottom) {
                currentSection = section.id;
            }
        });

        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');

                // 目次内のスクロール（PCのみ）
                const tocSidebar = document.querySelector('.toc-sidebar');
                if (tocSidebar && window.innerWidth > 768) {
                    const linkTop = link.offsetTop;
                    const linkHeight = link.offsetHeight;
                    const sidebarHeight = tocSidebar.offsetHeight;
                    const scrollTop = tocSidebar.scrollTop;

                    if (linkTop < scrollTop || linkTop + linkHeight > scrollTop + sidebarHeight) {
                        tocSidebar.scrollTo({
                            top: linkTop - sidebarHeight / 2 + linkHeight / 2,
                            behavior: 'smooth'
                        });
                    }
                }
            }
        });
    };

    // スクロールイベント（throttle）
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                activateTocOnScroll();
                ticking = false;
            });
            ticking = true;
        }
    });

    // 初回実行
    activateTocOnScroll();

    // ========================================
    // 3. SP用モーダル目次
    // ========================================
    const tocToggle = document.querySelector('.toc-toggle');
    const tocModal = document.querySelector('.toc-modal');
    const tocModalClose = document.querySelector('.toc-modal-close');

    if (tocToggle && tocModal) {
        // 目次を開く
        tocToggle.addEventListener('click', () => {
            tocModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // 目次を閉じる
        const closeTocModal = () => {
            tocModal.classList.remove('active');
            document.body.style.overflow = '';
        };

        if (tocModalClose) {
            tocModalClose.addEventListener('click', closeTocModal);
        }

        // 背景クリックで閉じる
        tocModal.addEventListener('click', (e) => {
            if (e.target === tocModal) {
                closeTocModal();
            }
        });

        // 目次リンククリックで閉じる（SP）
        const modalTocLinks = tocModal.querySelectorAll('.toc-link');
        modalTocLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeTocModal();
                }
            });
        });
    }
});
