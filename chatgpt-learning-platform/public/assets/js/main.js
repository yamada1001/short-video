/**
 * 全ページ共通JavaScript
 */

// ユーザーメニューのドロップダウン
document.addEventListener('DOMContentLoaded', function() {
    const userMenuToggle = document.querySelector('.user-menu-toggle');
    const userDropdown = document.querySelector('.user-dropdown');

    if (userMenuToggle && userDropdown) {
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // ドロップダウン外をクリックで閉じる
        document.addEventListener('click', function() {
            userDropdown.classList.remove('show');
        });

        // ドロップダウン内のクリックは閉じない
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
