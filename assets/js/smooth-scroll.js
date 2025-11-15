/**
 * Smooth Scroll - ぬるっとしたスクロール
 * 慣性スクロールを実装
 */

(function() {
    'use strict';

    console.log('🎯 Smooth Scroll - 初期化開始');

    // モバイルではスキップ
    if (window.innerWidth < 1024) {
        console.log('📱 モバイルデバイスのためスムーススクロールをスキップ');
        return;
    }

    let currentScroll = 0;
    let targetScroll = 0;
    let ease = 0.08; // スクロールの滑らかさ（小さいほど滑らか）
    let isScrolling = false;
    let animationFrame = null;

    /**
     * スクロール位置を更新
     */
    function updateScroll() {
        targetScroll = window.pageYOffset;

        if (!isScrolling) {
            isScrolling = true;
            smoothScrollLoop();
        }
    }

    /**
     * 滑らかなスクロールループ
     */
    function smoothScrollLoop() {
        // 現在位置と目標位置の差分
        const diff = targetScroll - currentScroll;

        // 差分が小さければスクロール停止
        if (Math.abs(diff) < 0.5) {
            currentScroll = targetScroll;
            isScrolling = false;
            return;
        }

        // イージングを適用
        currentScroll += diff * ease;

        // bodyを移動
        document.body.style.transform = `translateY(${-currentScroll}px)`;

        // 次のフレームを予約
        animationFrame = requestAnimationFrame(smoothScrollLoop);
    }

    /**
     * 初期化
     */
    function init() {
        // bodyを固定してスクロール可能にする
        document.body.style.position = 'fixed';
        document.body.style.top = '0';
        document.body.style.left = '0';
        document.body.style.width = '100%';
        document.body.style.overflow = 'hidden';

        // スクロール用のコンテナの高さを設定
        const scrollHeight = document.documentElement.scrollHeight;
        document.documentElement.style.height = scrollHeight + 'px';

        // スクロールイベントをリスン
        window.addEventListener('scroll', updateScroll, { passive: true });

        // 初期スクロール位置を設定
        currentScroll = window.pageYOffset;
        targetScroll = currentScroll;

        console.log('✅ Smooth Scroll - 初期化完了');
    }

    /**
     * クリーンアップ
     */
    function destroy() {
        if (animationFrame) {
            cancelAnimationFrame(animationFrame);
        }
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        document.body.style.transform = '';
        document.documentElement.style.height = '';
        window.removeEventListener('scroll', updateScroll);
    }

    // リサイズ時の処理
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // モバイルサイズになったら無効化
            if (window.innerWidth < 1024) {
                destroy();
            } else {
                // 高さを再計算
                const scrollHeight = document.documentElement.scrollHeight;
                document.documentElement.style.height = scrollHeight + 'px';
            }
        }, 250);
    });

    // ページロード後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
