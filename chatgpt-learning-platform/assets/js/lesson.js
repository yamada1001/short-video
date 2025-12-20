/**
 * レッスンページの共通JavaScript
 */

// グローバル設定の取得
const config = window.lessonConfig || {};
const lessonId = config.lessonId;
const appUrl = config.appUrl || '';
const courseId = config.courseId;

// 完了ボタンのイベントリスナー
const completeBtn = document.getElementById('completeBtn');
if (completeBtn) {
    completeBtn.addEventListener('click', async function() {
        if (!confirm('このレッスンを完了としてマークしますか？')) {
            return;
        }

        try {
            const response = await fetch(`${appUrl}/api/complete-lesson.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    lesson_id: lessonId
                })
            });

            const data = await response.json();

            if (data.success) {
                alert('レッスンを完了しました！');
                // 次のレッスンがある場合は遷移
                if (data.next_lesson_id) {
                    window.location.href = `${appUrl}/lesson.php?id=${data.next_lesson_id}`;
                } else {
                    // コース詳細に戻る
                    window.location.href = `${appUrl}/course.php?id=${courseId}`;
                }
            } else {
                alert('エラーが発生しました: ' + (data.message || '不明なエラー'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('通信エラーが発生しました。');
        }
    });
}
