<!-- Breadcrumb -->
<?php if (!empty($breadcrumbs)): ?>
<nav class="breadcrumb" aria-label="パンくずリスト">
    <div class="container">
        <ol class="breadcrumb__list">
            <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                <?php if ($index === count($breadcrumbs) - 1): ?>
                    <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">
                        <?php echo h($breadcrumb['name']); ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb__item">
                        <a href="<?php echo h($breadcrumb['url']); ?>" class="breadcrumb__link">
                            <?php if ($index === 0): ?>
                                <i class="fa-solid fa-home"></i>
                            <?php endif; ?>
                            <?php echo h($breadcrumb['name']); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
<?php endif; ?>
