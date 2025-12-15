<?php
/**
 * メンバー管理ページ
 * CRUD操作（作成・読取・更新・削除）
 */
require_once __DIR__ . '/../../config/config.php';

use BNI\Member;
use BNI\Logger;

$error = null;
$success = null;

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $id = (int)($_POST['id'] ?? 0);
        if (Member::delete($id)) {
            $success = 'メンバーを削除しました';
        } else {
            $error = 'メンバーが見つかりません';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        Logger::error('Member deletion failed', ['error' => $error, 'id' => $id ?? null]);
    }
}

// 作成・更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['create', 'update'])) {
    try {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'active' => isset($_POST['active']) ? 1 : 0,
        ];

        if ($_POST['action'] === 'create') {
            Member::create($data);
            $success = 'メンバーを追加しました';
        } else {
            $id = (int)($_POST['id'] ?? 0);
            Member::update($id, $data);
            $success = 'メンバー情報を更新しました';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        Logger::error('Member save failed', ['error' => $error, 'action' => $_POST['action']]);
    }
}

// 全メンバー取得（非アクティブも含む）
$members = Member::getAll(false);
$totalMembers = count($members);
$activeMembers = array_filter($members, fn($m) => $m['active'] == 1);
$activeCount = count($activeMembers);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンバー管理 - BNI Payment System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.8;
            color: #333;
            background: #fafafa;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .admin-header {
            margin-bottom: 60px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .admin-header h1 {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .admin-subtitle {
            font-size: 13px;
            color: #999;
        }

        .admin-nav {
            margin-bottom: 40px;
            border-bottom: 1px solid #e0e0e0;
        }

        .admin-nav a {
            display: inline-block;
            padding: 12px 24px;
            font-size: 14px;
            color: #666;
            text-decoration: none;
            border-bottom: 2px solid transparent;
        }

        .admin-nav a:hover {
            color: #333;
        }

        .admin-nav a.active {
            color: #DC143C;
            border-bottom-color: #DC143C;
        }

        .sidebar {
            display: none;
        }

        .admin-main {
            width: 100%;
        }

        .table-card {
            background: #fff;
            padding: 48px 32px;
            border: 1px solid #e0e0e0;
            margin-bottom: 32px;
        }

        .table-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 32px;
            letter-spacing: 0.05em;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 16px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            color: #999;
            border-bottom: 1px solid #e0e0e0;
            letter-spacing: 0.1em;
        }

        .data-table td {
            padding: 16px 12px;
            font-size: 15px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:hover {
            background: #fafafa;
        }

        .member-name {
            font-weight: 400;
            color: #333;
        }

        .member-email {
            font-size: 13px;
            color: #999;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 400;
            border-radius: 0;
        }

        .badge-success {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #e0e0e0;
        }

        .badge-warning {
            background: #fff;
            color: #999;
            border: 1px solid #e0e0e0;
        }

        .actions {
            margin-bottom: 32px;
        }

        .btn {
            display: inline-block;
            padding: 16px 32px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #DC143C;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
            cursor: pointer;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .btn:hover {
            background: #A01225;
        }

        .btn-group {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            width: auto;
        }

        .btn-danger {
            background: #DC143C;
        }

        .btn-danger:hover {
            background: #A01225;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        @media (max-width: 640px) {
            .admin-container {
                padding: 40px 16px;
            }

            .table-card {
                padding: 32px 24px;
            }

            .data-table {
                font-size: 13px;
            }

            .data-table th,
            .data-table td {
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- サイドバー -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>BNI管理</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin/index.php" class="nav-link">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>ダッシュボード</span>
                </a>
                <a href="/admin/members.php" class="nav-link active">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>メンバー管理</span>
                </a>
                <a href="/index.php" class="nav-link" target="_blank">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span>支払いページ</span>
                </a>
            </nav>
        </aside>

        <!-- メインコンテンツ -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>メンバー管理</h1>
                <button class="btn btn-primary" onclick="openModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>新規メンバー追加</span>
                </button>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo h($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert" style="background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7;">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo h($success); ?></span>
                </div>
            <?php endif; ?>

            <!-- 統計 -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-total">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">総メンバー数</div>
                        <div class="stat-value"><?php echo $totalMembers; ?>人</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-paid">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">アクティブ</div>
                        <div class="stat-value"><?php echo $activeCount; ?>人</div>
                    </div>
                </div>
            </div>

            <!-- メンバーテーブル -->
            <div class="table-card">
                <h2 class="table-title">メンバー一覧</h2>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名前</th>
                                <th>メールアドレス</th>
                                <th>ステータス</th>
                                <th>登録日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><?php echo h($member['id']); ?></td>
                                    <td class="member-name"><?php echo h($member['name']); ?></td>
                                    <td class="member-email"><?php echo h($member['email']); ?></td>
                                    <td>
                                        <?php if ($member['active']): ?>
                                            <span class="badge badge-success">アクティブ</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: var(--gray-200); color: var(--gray-700);">非アクティブ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="payment-date">
                                        <?php echo h(date('Y/m/d', strtotime($member['created_at']))); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-secondary" onclick='editMember(<?php echo json_encode($member); ?>)'>
                                                編集
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteMember(<?php echo $member['id']; ?>, '<?php echo h($member['name']); ?>')">
                                                削除
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- モーダル: メンバー追加・編集 -->
    <div id="memberModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">新規メンバー追加</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>

            <form method="POST" class="modal-body" id="memberForm">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="memberId">

                <div class="form-group">
                    <label for="name" class="form-label">名前 *</label>
                    <input type="text" name="name" id="name" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">メールアドレス *</label>
                    <input type="email" name="email" id="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-checkbox">
                        <input type="checkbox" name="active" id="active" checked>
                        <span>アクティブ</span>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">キャンセル</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 削除フォーム（非表示） -->
    <form method="POST" id="deleteForm" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteId">
    </form>

    <script src="/assets/js/app.js"></script>
    <script>
        // モーダル開閉
        function openModal() {
            document.getElementById('memberModal').classList.add('active');
            document.getElementById('modalTitle').textContent = '新規メンバー追加';
            document.getElementById('formAction').value = 'create';
            document.getElementById('memberForm').reset();
            document.getElementById('active').checked = true;
        }

        function closeModal() {
            document.getElementById('memberModal').classList.remove('active');
        }

        // メンバー編集
        function editMember(member) {
            document.getElementById('memberModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'メンバー編集';
            document.getElementById('formAction').value = 'update';
            document.getElementById('memberId').value = member.id;
            document.getElementById('name').value = member.name;
            document.getElementById('email').value = member.email;
            document.getElementById('active').checked = member.active == 1;
        }

        // メンバー削除
        function deleteMember(id, name) {
            if (confirm(`本当に「${name}」を削除しますか？\nこの操作は取り消せません。`)) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }

        // モーダル外クリックで閉じる
        document.getElementById('memberModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
