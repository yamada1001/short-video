<?php
/**
 * 共通フッター
 */
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-column">
                <h3>ChatGPT Learning</h3>
                <p>ChatGPTを実践的に学べるProgate風プラットフォーム</p>
            </div>
            <div class="footer-column">
                <h4>学習</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/course.php">コース一覧</a></li>
                    <li><a href="<?= APP_URL ?>/dashboard.php">ダッシュボード</a></li>
                    <li><a href="<?= APP_URL ?>/my-progress.php">学習進捗</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>サポート</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/help.php">ヘルプ</a></li>
                    <li><a href="<?= APP_URL ?>/contact.php">お問い合わせ</a></li>
                    <li><a href="<?= APP_URL ?>/faq.php">よくある質問</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>会社情報</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/terms.php">利用規約</a></li>
                    <li><a href="<?= APP_URL ?>/privacy.php">プライバシーポリシー</a></li>
                    <li><a href="<?= APP_URL ?>/company.php">運営会社</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> ChatGPT Learning Platform. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="<?= APP_URL ?>/assets/js/main.js"></script>
