<?php
/**
 * BNI Slide System - Member Photos Management
 * メンバー写真管理画面
 */

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// 管理者チェック
requireAdmin();

// データベース接続
$db = getDBConnection();

// メンバーデータ取得
$stmt = $db->query('SELECT * FROM member_photos ORDER BY display_order ASC, id ASC');
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ページタイトル
$pageTitle = 'メンバー写真管理';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - BNI Slide System</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .member-photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .member-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .member-card h3 {
            margin: 0 0 15px 0;
            color: #C8102E;
            font-size: 1.3em;
        }

        .member-photo-preview {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 15px;
            background: #f5f5f5;
        }

        .member-info {
            margin-bottom: 15px;
        }

        .member-info label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .member-info input,
        .member-info select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-upload {
            background: #C8102E;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .btn-upload:hover {
            background: #A00C1E;
        }

        .btn-save {
            background: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            margin-left: 10px;
        }

        .btn-save:hover {
            background: #218838;
        }

        .add-member-btn {
            background: #007bff;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .add-member-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
            <nav class="admin-nav">
                <a href="index.php">ダッシュボード</a>
                <a href="member_photos_admin.php" class="active">メンバー写真管理</a>
                <a href="../logout.php">ログアウト</a>
            </nav>
        </header>

        <main class="admin-main">
            <button class="add-member-btn" onclick="addNewMember()">新規メンバー追加</button>

            <div class="member-photos-grid" id="memberGrid">
                <?php foreach ($members as $member): ?>
                <div class="member-card" data-member-id="<?= $member['id'] ?>">
                    <h3><?= htmlspecialchars($member['name']) ?></h3>

                    <?php if ($member['photo_url']): ?>
                        <img src="../<?= htmlspecialchars($member['photo_url']) ?>"
                             alt="<?= htmlspecialchars($member['name']) ?>"
                             class="member-photo-preview"
                             id="preview-<?= $member['id'] ?>">
                    <?php else: ?>
                        <img src="../assets/images/placeholder-member.jpg"
                             alt="写真なし"
                             class="member-photo-preview"
                             id="preview-<?= $member['id'] ?>">
                    <?php endif; ?>

                    <div class="member-info">
                        <label>名前</label>
                        <input type="text" value="<?= htmlspecialchars($member['name']) ?>"
                               data-field="name" class="member-field">
                    </div>

                    <div class="member-info">
                        <label>名前強調部分（赤文字）</label>
                        <input type="text" value="<?= htmlspecialchars($member['name_highlight'] ?? '') ?>"
                               data-field="name_highlight" class="member-field">
                    </div>

                    <div class="member-info">
                        <label>会社名</label>
                        <input type="text" value="<?= htmlspecialchars($member['company'] ?? '') ?>"
                               data-field="company" class="member-field">
                    </div>

                    <div class="member-info">
                        <label>業種</label>
                        <input type="text" value="<?= htmlspecialchars($member['industry'] ?? '') ?>"
                               data-field="industry" class="member-field">
                    </div>

                    <div class="member-info">
                        <label>役職（日本語）</label>
                        <input type="text" value="<?= htmlspecialchars($member['position_title'] ?? '') ?>"
                               data-field="position_title" class="member-field">
                    </div>

                    <div class="member-info">
                        <label>役職（英語）</label>
                        <input type="text" value="<?= htmlspecialchars($member['position_title_en'] ?? '') ?>"
                               data-field="position_title_en" class="member-field">
                    </div>

                    <div class="member-info">
                        <label>表示順</label>
                        <input type="number" value="<?= $member['display_order'] ?>"
                               data-field="display_order" class="member-field">
                    </div>

                    <input type="file" accept="image/*" style="display: none;"
                           id="file-<?= $member['id'] ?>"
                           onchange="uploadPhoto(<?= $member['id'] ?>)">

                    <button class="btn-upload" onclick="document.getElementById('file-<?= $member['id'] ?>').click()">
                        写真をアップロード
                    </button>

                    <button class="btn-save" onclick="saveMember(<?= $member['id'] ?>)">
                        保存
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <script>
        // 写真アップロード
        async function uploadPhoto(memberId) {
            const fileInput = document.getElementById(`file-${memberId}`);
            const file = fileInput.files[0];

            if (!file) return;

            const formData = new FormData();
            formData.append('member_id', memberId);
            formData.append('photo', file);

            try {
                const response = await fetch('../api_upload_member_photo.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('写真をアップロードしました');
                    // プレビュー更新
                    const preview = document.getElementById(`preview-${memberId}`);
                    preview.src = `../${result.photo_url}?t=${Date.now()}`;
                } else {
                    alert('エラー: ' + result.error);
                }
            } catch (error) {
                alert('アップロードに失敗しました: ' + error.message);
            }
        }

        // メンバー情報保存
        async function saveMember(memberId) {
            const card = document.querySelector(`[data-member-id="${memberId}"]`);
            const fields = card.querySelectorAll('.member-field');

            const formData = new FormData();
            formData.append('member_id', memberId);

            fields.forEach(field => {
                const fieldName = field.getAttribute('data-field');
                formData.append(fieldName, field.value);
            });

            try {
                const response = await fetch('../api_upload_member_photo.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('保存しました');
                } else {
                    alert('エラー: ' + result.error);
                }
            } catch (error) {
                alert('保存に失敗しました: ' + error.message);
            }
        }

        // 新規メンバー追加（簡易版）
        function addNewMember() {
            alert('新規メンバー追加機能は後で実装します。\n現在はデータベースに直接追加してください。');
        }
    </script>
</body>
</html>
