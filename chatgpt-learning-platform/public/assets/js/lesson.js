/**
 * レッスンページ用JavaScript
 *
 * 完了ボタンなどの共通機能
 */

// 完了ボタン
const completeBtn = document.getElementById('completeBtn');

if (completeBtn) {
    completeBtn.addEventListener('click', async () => {
        if (!confirm('このレッスンを完了にしますか？')) {
            return;
        }

        completeBtn.disabled = true;
        completeBtn.textContent = '更新中...';

        try {
            const response = await fetch(`${appUrl}/api/progress.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    lesson_id: lessonId,
                    status: 'completed'
                })
            });

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            // 成功: コースページに戻る
            alert('レッスンを完了しました！');
            window.location.href = `${appUrl}/course.php?id=${courseId}`;

        } catch (error) {
            alert('エラーが発生しました: ' + error.message);
            completeBtn.disabled = false;
            completeBtn.textContent = '✓ 完了にする';
        }
    });
}

// ユーザーメニューのドロップダウン
const userMenuToggle = document.querySelector('.user-menu-toggle');
const userDropdown = document.querySelector('.user-dropdown');

if (userMenuToggle && userDropdown) {
    userMenuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
    });

    // ドロップダウン外をクリックしたら閉じる
    document.addEventListener('click', () => {
        userDropdown.classList.remove('active');
    });

    userDropdown.addEventListener('click', (e) => {
        e.stopPropagation();
    });
}
