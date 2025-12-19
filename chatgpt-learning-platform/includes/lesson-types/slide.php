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
</script>
