<?php
/**
 * BNI Slide System V2 - Visitor Introduction Slide (p.19)
 * ビジター紹介スライド（テーブル形式、6名ごとにページ分割）
 */

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
$date = $_GET['date'] ?? null;

if (!$date) {
    die('日付パラメータが必要です');
}

try {
    $db = new SQLite3($dbPath);

    // ビジター情報取得
    $stmt = $db->prepare("
        SELECT
            v.*,
            m.name as attend_member_name
        FROM visitors v
        LEFT JOIN members m ON v.attend_member_id = m.id
        WHERE v.week_date = :week_date
        ORDER BY v.visitor_no ASC
    ");
    $stmt->bindValue(':week_date', $date, SQLITE3_TEXT);
    $result = $stmt->execute();

    $visitors = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $visitors[] = $row;
    }

    $db->close();
} catch (Exception $e) {
    die('データベースエラー: ' . $e->getMessage());
}

// 6名ごとにページ分割
$perPage = 6;
$pages = array_chunk($visitors, $perPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$totalPages = count($pages);

if ($totalPages === 0) {
    die('この日付のビジターは登録されていません');
}

if ($currentPage >= $totalPages) {
    $currentPage = 0;
}

$currentVisitors = $pages[$currentPage];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ビジター紹介 (p.19) | BNI Slide System V2</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .slide-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
            position: relative;
        }

        .slide-title {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 50px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .visitors-table {
            width: 100%;
            max-width: 1400px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #C8102E;
            color: white;
        }

        thead th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 24px;
            border-bottom: 3px solid #a00a24;
        }

        tbody tr {
            border-bottom: 2px solid #eee;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody td {
            padding: 20px 15px;
            font-size: 22px;
            color: #333;
        }

        tbody td:first-child {
            font-weight: 700;
            color: #C8102E;
            font-size: 26px;
            text-align: center;
            width: 80px;
        }

        tbody td:nth-child(2) {
            font-weight: 600;
            font-size: 26px;
        }

        .page-indicator {
            position: absolute;
            bottom: 30px;
            right: 40px;
            font-size: 24px;
            font-weight: 500;
            opacity: 0.9;
        }

        .nav-hint {
            position: absolute;
            bottom: 30px;
            left: 40px;
            font-size: 18px;
            opacity: 0.7;
        }

        /* ページ遷移時のフェードイン */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .visitors-table {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <h1 class="slide-title">ビジター紹介</h1>

        <div class="visitors-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>お名前</th>
                        <th>会社名</th>
                        <th>専門分野</th>
                        <th>スポンサー</th>
                        <th>アテンド</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentVisitors as $visitor): ?>
                    <tr>
                        <td><?= htmlspecialchars($visitor['visitor_no']) ?></td>
                        <td><?= htmlspecialchars($visitor['name']) ?></td>
                        <td><?= htmlspecialchars($visitor['company_name'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($visitor['specialty'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($visitor['sponsor'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($visitor['attend_member_name'] ?: '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="nav-hint">
            [←][→] ページ切り替え | [F] フルスクリーン
        </div>
        <div class="page-indicator">
            <?= $currentPage + 1 ?> / <?= $totalPages ?>
        </div>
        <?php else: ?>
        <div class="nav-hint">
            [F] フルスクリーン
        </div>
        <?php endif; ?>
    </div>

    <script>
        const totalPages = <?= $totalPages ?>;
        let currentPage = <?= $currentPage ?>;
        const date = '<?= htmlspecialchars($date) ?>';

        // キーボードナビゲーション
        document.addEventListener('keydown', (e) => {
            if (totalPages <= 1) return;

            if (e.key === 'ArrowRight') {
                // 次のページ
                currentPage = (currentPage + 1) % totalPages;
                window.location.href = `?date=${date}&page=${currentPage}`;
            } else if (e.key === 'ArrowLeft') {
                // 前のページ
                currentPage = (currentPage - 1 + totalPages) % totalPages;
                window.location.href = `?date=${date}&page=${currentPage}`;
            } else if (e.key === 'f' || e.key === 'F') {
                // フルスクリーン切り替え
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }
        });

        // 自動フルスクリーン（ユーザーインタラクション後）
        document.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        }, { once: true });
    </script>
</body>
</html>
