<?php
/**
 * スライド形式レッスン
 */
$slides = $content['slides'] ?? [];
?>

<div class="lesson-slide">
    <div class="slide-container">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="slide" data-slide="<?= $index ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                <div class="slide-content">
                    <h1 class="slide-title"><?= h($slide['title'] ?? '') ?></h1>
                    <div class="slide-body">
                        <?= nl2br(h($slide['content'] ?? '')) ?>
                    </div>

                    <?php if (isset($slide['image'])): ?>
                        <img src="<?= h($slide['image']) ?>" alt="<?= h($slide['title'] ?? '') ?>" class="slide-image">
                    <?php endif; ?>

                    <?php if (isset($slide['code'])): ?>
                        <pre class="slide-code"><code><?= h($slide['code']) ?></code></pre>
                    <?php endif; ?>

                    <?php if (isset($slide['prompt'])): ?>
                        <div class="prompt-card">
                            <div class="prompt-header">
                                <h3 class="prompt-title">
                                    <span class="prompt-icon">💬</span>
                                    <?= isset($slide['ai_tool_name']) ? h($slide['ai_tool_name']) : 'Gemini' ?>用プロンプト
                                </h3>
                                <button class="copy-prompt-btn" data-prompt="<?= h($slide['prompt']) ?>" title="コピー">
                                    <span class="copy-icon">📋</span>
                                    <span class="copy-text">コピー</span>
                                </button>
                            </div>
                            <div class="prompt-body">
                                <pre class="prompt-text"><?= h($slide['prompt']) ?></pre>
                            </div>
                            <div class="prompt-footer">
                                <a href="<?= isset($slide['ai_tool_url']) ? h($slide['ai_tool_url']) : 'https://gemini.google.com' ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="try-ai-btn">
                                    <span class="try-icon">🚀</span>
                                    <?= isset($slide['ai_tool_name']) ? h($slide['ai_tool_name']) : 'Gemini' ?>で試す
                                    <span class="external-icon">↗</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="slide-footer">
                    <span class="slide-number"><?= $index + 1 ?> / <?= count($slides) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- スライドナビゲーション -->
    <div class="slide-controls">
        <button id="prevSlide" class="slide-btn" disabled>← 前へ</button>
        <div class="slide-dots">
            <?php foreach ($slides as $index => $slide): ?>
                <span class="dot <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>"></span>
            <?php endforeach; ?>
        </div>
        <button id="nextSlide" class="slide-btn">次へ →</button>
    </div>
</div>

<script>
// スライド制御
let currentSlide = 0;
const totalSlides = <?= count($slides) ?>;

function showSlide(n) {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    slides.forEach(slide => slide.style.display = 'none');
    dots.forEach(dot => dot.classList.remove('active'));

    if (n >= totalSlides) currentSlide = totalSlides - 1;
    if (n < 0) currentSlide = 0;

    slides[currentSlide].style.display = 'block';
    dots[currentSlide].classList.add('active');

    // ボタンの状態更新
    document.getElementById('prevSlide').disabled = currentSlide === 0;
    document.getElementById('nextSlide').disabled = currentSlide === totalSlides - 1;
}

document.getElementById('prevSlide').addEventListener('click', () => {
    currentSlide--;
    showSlide(currentSlide);
});

document.getElementById('nextSlide').addEventListener('click', () => {
    currentSlide++;
    showSlide(currentSlide);
});

// ドットクリック
document.querySelectorAll('.dot').forEach((dot, index) => {
    dot.addEventListener('click', () => {
        currentSlide = index;
        showSlide(currentSlide);
    });
});

// キーボードナビゲーション
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        if (currentSlide > 0) {
            currentSlide--;
            showSlide(currentSlide);
        }
    } else if (e.key === 'ArrowRight') {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
            showSlide(currentSlide);
        }
    }
});

// プロンプトコピー機能
document.querySelectorAll('.copy-prompt-btn').forEach(button => {
    button.addEventListener('click', async function() {
        const promptText = this.getAttribute('data-prompt');
        try {
            await navigator.clipboard.writeText(promptText);
            // コピー成功のフィードバック
            const originalHTML = this.innerHTML;
            this.innerHTML = '<span class="copy-icon">✅</span><span class="copy-text">コピーしました！</span>';
            this.disabled = true;

            // 2秒後に元に戻す
            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.disabled = false;
            }, 2000);
        } catch (error) {
            // フォールバック: 古いブラウザ対応
            const textArea = document.createElement('textarea');
            textArea.value = promptText;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                const originalHTML = this.innerHTML;
                this.innerHTML = '<span class="copy-icon">✅</span><span class="copy-text">コピーしました！</span>';
                this.disabled = true;
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                }, 2000);
            } catch (err) {
                alert('クリップボードへのコピーに失敗しました: ' + err.message);
            }
            document.body.removeChild(textArea);
        }
    });
});
</script>
