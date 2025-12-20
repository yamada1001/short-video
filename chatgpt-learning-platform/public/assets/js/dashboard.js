/**
 * ダッシュボード用JavaScript
 * プログレスバーのアニメーション
 */

document.addEventListener('DOMContentLoaded', function() {
    // プログレスバーのアニメーション
    const progressFills = document.querySelectorAll('.progress-fill');

    progressFills.forEach(function(fill) {
        const progress = fill.getAttribute('data-progress');
        if (progress) {
            // アニメーション用に一度0%に設定してから目標値に変更
            fill.style.width = '0%';

            // 少し遅延させてからアニメーション開始
            setTimeout(function() {
                fill.style.transition = 'width 0.6s ease';
                fill.style.width = progress + '%';
            }, 100);
        }
    });
});
