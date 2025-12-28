/**
 * レッスンページ用JavaScript
 *
 * 完了ボタンなどの共通機能
 */

// 完了ボタン
const completeBtn = document.getElementById('completeBtn');

if (completeBtn) {
    completeBtn.addEventListener('click', async () => {
        completeBtn.disabled = true;
        completeBtn.textContent = '更新中...';

        try {
            const response = await fetch(`${appUrl}/public/api/progress.php`, {
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

            // 成功: カスタムモーダルを表示
            showCompletionModal();

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

// レッスン完了モーダルを表示
function showCompletionModal() {
    // モーダルHTML作成
    const modalHTML = `
        <div id="completionModal" class="completion-modal-overlay">
            <div class="completion-modal">
                <div class="completion-modal-icon">🎉</div>
                <h2 class="completion-modal-title">おめでとうございます！</h2>
                <p class="completion-modal-text">レッスンを完了しました</p>
                <div class="completion-modal-countdown">
                    <span id="countdownTimer">3</span>秒後にコース一覧に戻ります
                </div>
                <button id="completionModalBtn" class="completion-modal-btn">
                    すぐにコース一覧に戻る
                </button>
            </div>
        </div>
    `;

    // bodyに追加
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // カウントダウン開始
    let countdown = 3;
    const countdownElement = document.getElementById('countdownTimer');
    const countdownInterval = setInterval(() => {
        countdown--;
        if (countdown > 0) {
            countdownElement.textContent = countdown;
        } else {
            clearInterval(countdownInterval);
            redirectToCourse();
        }
    }, 1000);

    // ボタンクリックでリダイレクト
    document.getElementById('completionModalBtn').addEventListener('click', () => {
        clearInterval(countdownInterval);
        redirectToCourse();
    });
}

// コースページにリダイレクト
function redirectToCourse() {
    window.location.href = `${appUrl}/course.php?id=${courseId}`;
}
