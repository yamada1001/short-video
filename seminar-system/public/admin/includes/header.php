<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'セミナー管理システム'; ?> - 管理画面</title>
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
            transition: color 0.2s, border-color 0.2s;
        }

        .admin-nav a:hover {
            color: #333;
        }

        .admin-nav a.active {
            color: #333;
            border-bottom-color: #333;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 32px;
            border: 1px solid #e0e0e0;
            background: #fff;
        }

        .alert-success {
            border-color: #4caf50;
            background: #f1f8f4;
            color: #2e7d32;
        }

        .alert-error {
            border-color: #f44336;
            background: #fef5f5;
            color: #c62828;
        }

        .card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 48px 32px;
            margin-bottom: 32px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 32px;
            letter-spacing: 0.05em;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
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

        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 400;
            border-radius: 0;
        }

        .badge-success {
            background: #f1f8f4;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }

        .badge-warning {
            background: #fff;
            color: #999;
            border: 1px solid #e0e0e0;
        }

        .badge-info {
            background: #e3f2fd;
            color: #1976d2;
            border: 1px solid #2196f3;
        }

        .badge-muted {
            background: #fafafa;
            color: #999;
            border: 1px solid #e0e0e0;
        }

        .btn {
            display: inline-block;
            padding: 12px 32px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #333;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
            cursor: pointer;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .btn:hover {
            background: #000;
        }

        .btn-small {
            padding: 8px 20px;
            font-size: 13px;
        }

        .btn-secondary {
            background: #fff;
            color: #333;
            border: 1px solid #e0e0e0;
        }

        .btn-secondary:hover {
            background: #fafafa;
        }

        .btn-danger {
            background: #f44336;
        }

        .btn-danger:hover {
            background: #d32f2f;
        }

        .actions {
            margin-bottom: 32px;
        }

        .text-muted {
            color: #999;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .form-label.required::after {
            content: " *";
            color: #f44336;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            color: #333;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #333;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: #fff;
            padding: 32px 24px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
            letter-spacing: 0.1em;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 300;
            color: #333;
            letter-spacing: -0.02em;
        }

        @media (max-width: 640px) {
            .admin-container {
                padding: 40px 16px;
            }

            .card {
                padding: 32px 24px;
            }

            .data-table {
                font-size: 13px;
            }

            .data-table th,
            .data-table td {
                padding: 12px 8px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- ヘッダー -->
        <header class="admin-header">
            <h1>セミナー管理システム</h1>
            <p class="admin-subtitle">管理画面</p>
        </header>

        <!-- ナビゲーション -->
        <nav class="admin-nav">
            <a href="/seminar-system/public/admin/index.php" class="<?php echo ($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>">ダッシュボード</a>
            <a href="/seminar-system/public/admin/seminars.php" class="<?php echo ($currentPage ?? '') === 'seminars' ? 'active' : ''; ?>">セミナー管理</a>
            <a href="/seminar-system/public/admin/attendees.php" class="<?php echo ($currentPage ?? '') === 'attendees' ? 'active' : ''; ?>">参加者管理</a>
            <a href="/seminar-system/public/index.php" target="_blank">申込ページ</a>
        </nav>

        <!-- フラッシュメッセージ -->
        <?php if (isset($flash) && $flash): ?>
            <div class="alert alert-<?php echo h($flash['type']); ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
