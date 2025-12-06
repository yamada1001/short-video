<?php
require_once 'includes/config.php';
define('PAGE_TITLE', SITE_TITLE);
require_once 'includes/header.php';
?>

<div class="container" style="padding: 60px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 48px; font-weight: 400; color: #333; margin-bottom: 16px;">
                <i class="fas fa-book-open" style="color: #4A90E2;"></i>
                旅行の栞
            </h1>
            <p style="font-size: 18px; color: #999;">Your Private Travel Guide</p>
        </div>

        <!-- 旅行先一覧 -->
        <section>
            <h2 style="font-size: 24px; font-weight: 400; color: #333; margin-bottom: 30px; padding-left: 12px; border-left: 4px solid #4A90E2;">
                旅行先一覧
            </h2>

            <?php foreach ($destinations as $slug => $destination): ?>
            <a href="<?php echo $slug; ?>/index.php" class="card" style="display: block; text-decoration: none; margin-bottom: 20px; transition: all 0.3s;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <h3 style="font-size: 28px; font-weight: 400; color: #333; margin: 0;">
                        <?php echo $destination['name']; ?>
                    </h3>
                    <span style="background: #4A90E2; color: #ffffff; padding: 4px 12px; border-radius: 4px; font-size: 14px;">
                        <?php echo $destination['duration']; ?>
                    </span>
                </div>
                <p style="color: #666; margin-bottom: 8px;">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo $destination['dates']; ?>
                </p>
                <p style="color: #999; font-size: 14px;">
                    <i class="fas fa-map-marked-alt"></i>
                    <?php echo $destination['description']; ?> - <?php echo $destination['spots_total']; ?>スポット
                </p>
            </a>
            <?php endforeach; ?>
        </section>

        <!-- 使い方 -->
        <section style="margin-top: 60px;">
            <h2 style="font-size: 24px; font-weight: 400; color: #333; margin-bottom: 30px; padding-left: 12px; border-left: 4px solid #4A90E2;">
                使い方
            </h2>

            <div class="card">
                <ul style="line-height: 2.2; color: #666; list-style: none;">
                    <li>
                        <i class="fas fa-check-circle" style="color: #4A90E2; margin-right: 8px;"></i>
                        各旅行先をクリックすると、詳細な旅程が見られます
                    </li>
                    <li>
                        <i class="fas fa-check-circle" style="color: #4A90E2; margin-right: 8px;"></i>
                        訪問済みのスポットはチェックボックスでチェック
                    </li>
                    <li>
                        <i class="fas fa-check-circle" style="color: #4A90E2; margin-right: 8px;"></i>
                        チェック状態は自動保存されます（localStorage）
                    </li>
                    <li>
                        <i class="fas fa-check-circle" style="color: #4A90E2; margin-right: 8px;"></i>
                        Google Mapsリンクで現地へナビゲート
                    </li>
                    <li>
                        <i class="fas fa-check-circle" style="color: #4A90E2; margin-right: 8px;"></i>
                        目次から各スポットへジャンプ可能
                    </li>
                </ul>
            </div>
        </section>

        <!-- 機能 -->
        <section style="margin-top: 60px;">
            <h2 style="font-size: 24px; font-weight: 400; color: #333; margin-bottom: 30px; padding-left: 12px; border-left: 4px solid #4A90E2;">
                機能
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div class="card" style="text-align: center;">
                    <i class="fas fa-tasks" style="font-size: 40px; color: #4A90E2; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 18px; font-weight: 400; color: #333; margin-bottom: 8px;">チェックリスト</h3>
                    <p style="font-size: 14px; color: #999;">訪問済みスポットを管理</p>
                </div>

                <div class="card" style="text-align: center;">
                    <i class="fas fa-map-marked-alt" style="font-size: 40px; color: #4A90E2; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 18px; font-weight: 400; color: #333; margin-bottom: 8px;">地図リンク</h3>
                    <p style="font-size: 14px; color: #999;">Google Mapsで即ナビ</p>
                </div>

                <div class="card" style="text-align: center;">
                    <i class="fas fa-list-ul" style="font-size: 40px; color: #4A90E2; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 18px; font-weight: 400; color: #333; margin-bottom: 8px;">目次機能</h3>
                    <p style="font-size: 14px; color: #999;">スクロール連動で快適</p>
                </div>

                <div class="card" style="text-align: center;">
                    <i class="fas fa-mobile-alt" style="font-size: 40px; color: #4A90E2; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 18px; font-weight: 400; color: #333; margin-bottom: 8px;">レスポンシブ</h3>
                    <p style="font-size: 14px; color: #999;">PC・スマホ両対応</p>
                </div>
            </div>
        </section>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
