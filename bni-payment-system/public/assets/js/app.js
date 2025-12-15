/**
 * BNI Payment System - フロントエンドJavaScript
 * フォームバリデーション・UX向上
 */

(function() {
    'use strict';

    /**
     * フォーム送信時のバリデーション
     */
    function initPaymentForm() {
        const form = document.querySelector('.payment-form');
        if (!form) return;

        const submitButton = form.querySelector('button[type="submit"]');
        const memberSelect = form.querySelector('#member_id');

        form.addEventListener('submit', function(e) {
            // メンバー選択確認
            if (!memberSelect.value) {
                e.preventDefault();
                alert('メンバーを選択してください');
                memberSelect.focus();
                return false;
            }

            // 送信ボタン無効化（二重送信防止）
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="btn-icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>処理中...</span>
            `;
        });
    }

    /**
     * アラート自動フェードアウト
     */
    function initAlerts() {
        const alerts = document.querySelectorAll('.alert');

        alerts.forEach(function(alert) {
            // 5秒後にフェードアウト
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';

                // アニメーション完了後に削除
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    }

    /**
     * スムーズスクロール
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * メンバー選択時のハイライト
     */
    function initMemberSelect() {
        const memberSelect = document.querySelector('#member_id');
        if (!memberSelect) return;

        memberSelect.addEventListener('change', function() {
            if (this.value) {
                this.classList.add('selected');
            } else {
                this.classList.remove('selected');
            }
        });
    }

    /**
     * ローディングアニメーション用CSS追加
     */
    function injectStyles() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 1s linear infinite;
            }
            .form-select.selected {
                border-color: var(--primary);
                background: var(--gray-50);
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * 初期化
     */
    function init() {
        injectStyles();
        initPaymentForm();
        initAlerts();
        initSmoothScroll();
        initMemberSelect();

        // サンクスページでの自動リダイレクト（オプション）
        if (window.location.pathname.includes('thank-you.php')) {
            // 10秒後に支払いページに戻る（コメントアウト可）
            // setTimeout(function() {
            //     window.location.href = '/index.php';
            // }, 10000);
        }
    }

    // DOMContentLoaded時に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
