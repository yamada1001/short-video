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
    <link rel="stylesheet" href="/bni-payment-system/public/assets/css/style.css">
    <link rel="stylesheet" href="/bni-payment-system/public/admin/assets/css/admin.css">
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
