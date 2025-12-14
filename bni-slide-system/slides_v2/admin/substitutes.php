<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>代理出席管理 | BNI Slide System V2</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .container {
            max-width: 1600px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .card-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #C8102E;
        }

        .card-header h2 {
            color: #C8102E;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .date-selector {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .date-selector label {
            font-weight: 500;
            font-size: 14px;
        }

        .date-selector input[type="date"] {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #C8102E;
            color: white;
        }

        .btn-primary:hover {
            background: #a00a24;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-edit {
            background: #007bff;
            color: white;
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .info-box h4 {
            color: #0066cc;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-box ul {
            margin-top: 8px;
            margin-left: 20px;
            font-size: 13px;
            color: #333;
        }

        .info-box ul li {
            margin-bottom: 4px;
        }

        .substitutes-table {
            overflow-x: auto;
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
            padding: 15px 10px;
            text-align: left;
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        tbody td {
            padding: 15px 10px;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .count-badge {
            background: #C8102E;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #C8102E;
        }

        .modal-header h2 {
            color: #C8102E;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-group .required {
            color: #C8102E;
            margin-left: 4px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #C8102E;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-user-tag"></i> 代理出席管理</h1>
        <div class="subtitle">BNI Slide System V2 - Substitute Management</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> 代理出席者情報管理</h2>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> 代理出席管理について</h4>
                <ul>
                    <li>p.22-24: 代理出席メンバー紹介スライド</li>
                    <li>代理出席者は最大3名まで登録可能</li>
                    <li>会社名と代理出席者のお名前を入力してください</li>
                    <li>代理出席者は毎週変わるため、削除機能も活用してください</li>
                </ul>
            </div>

            <div class="actions-bar">
                <div class="date-selector">
                    <label><i class="fas fa-calendar"></i> 開催日:</label>
                    <input type="date" id="weekDate">
                    <span class="count-badge">代理出席者: <span id="substituteCount">0</span>名 / 最大3名</span>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> 代理出席者追加
                    </button>
                    <button class="btn btn-danger" onclick="deleteAllSubstitutes()">
                        <i class="fas fa-trash-alt"></i> 全削除
                    </button>
                    <button class="btn btn-success" onclick="openSlide()">
                        <i class="fas fa-play"></i> スライド表示
                    </button>
                </div>
            </div>

            <div class="substitutes-table">
                <table id="substitutesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>会社名</th>
                            <th>代理出席者</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="substitutesTableBody">
                        <!-- データをJavaScriptで読み込み -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="substituteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">代理出席者追加</h2>
            </div>
            <form id="substituteForm">
                <input type="hidden" id="substituteId">

                <div class="form-group">
                    <label>No <span class="required">*</span></label>
                    <input type="number" id="substituteNo" min="1" max="3" required>
                </div>

                <div class="form-group">
                    <label>会社名 <span class="required">*</span></label>
                    <input type="text" id="companyName" placeholder="例: 株式会社BNI" required>
                </div>

                <div class="form-group">
                    <label>代理出席者のお名前 <span class="required">*</span></label>
                    <input type="text" id="substituteName" placeholder="例: 山田 太郎" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">キャンセル</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 保存
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = '../api/substitutes_crud.php';
        let substitutes = [];

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            setDefaultDate();
            setupEventListeners();
            loadSubstitutes();
        });

        // イベントリスナー設定
        function setupEventListeners() {
            document.getElementById('weekDate').addEventListener('change', loadSubstitutes);
            document.getElementById('substituteForm').addEventListener('submit', handleSubmit);
        }

        // デフォルト日付設定（次の金曜日）
        function setDefaultDate() {
            const today = new Date();
            const dayOfWeek = today.getDay();
            const daysUntilFriday = (5 - dayOfWeek + 7) % 7 || 7;
            const nextFriday = new Date(today);
            nextFriday.setDate(today.getDate() + daysUntilFriday);

            const formatted = nextFriday.toISOString().split('T')[0];
            document.getElementById('weekDate').value = formatted;
        }

        // 代理出席者一覧取得
        async function loadSubstitutes() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) return;

            try {
                const response = await fetch(`${API_BASE}?action=get_by_date&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success) {
                    substitutes = data.substitutes;
                    renderSubstitutes();
                    document.getElementById('substituteCount').textContent = substitutes.length;
                }
            } catch (error) {
                console.error('代理出席者読み込みエラー:', error);
                alert('データの読み込みに失敗しました');
            }
        }

        // 代理出席者一覧表示
        function renderSubstitutes() {
            const tbody = document.getElementById('substitutesTableBody');
            tbody.innerHTML = '';

            if (substitutes.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>この日付の代理出席者はまだ登録されていません</p>
                        </td>
                    </tr>
                `;
                return;
            }

            substitutes.forEach(substitute => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${substitute.substitute_no}</strong></td>
                    <td>${substitute.company_name}</td>
                    <td><strong>${substitute.name}</strong></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editSubstitute(${substitute.id})">
                                <i class="fas fa-edit"></i> 編集
                            </button>
                            <button class="btn-delete" onclick="deleteSubstitute(${substitute.id}, '${substitute.name}')">
                                <i class="fas fa-trash"></i> 削除
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // モーダル開く（追加）
        async function openAddModal() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('先に開催日を選択してください');
                return;
            }

            if (substitutes.length >= 3) {
                alert('代理出席者は最大3名までです');
                return;
            }

            document.getElementById('modalTitle').textContent = '代理出席者追加';
            document.getElementById('substituteForm').reset();
            document.getElementById('substituteId').value = '';

            // 次のNo自動取得
            try {
                const response = await fetch(`${API_BASE}?action=get_next_no&week_date=${weekDate}`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('substituteNo').value = data.next_no;
                }
            } catch (error) {
                console.error('次のNo取得エラー:', error);
            }

            document.getElementById('substituteModal').classList.add('active');
        }

        // モーダル開く（編集）
        function editSubstitute(id) {
            const substitute = substitutes.find(s => s.id == id);
            if (!substitute) return;

            document.getElementById('modalTitle').textContent = '代理出席者編集';
            document.getElementById('substituteId').value = substitute.id;
            document.getElementById('substituteNo').value = substitute.substitute_no;
            document.getElementById('companyName').value = substitute.company_name;
            document.getElementById('substituteName').value = substitute.name;

            document.getElementById('substituteModal').classList.add('active');
        }

        // モーダル閉じる
        function closeModal() {
            document.getElementById('substituteModal').classList.remove('active');
        }

        // フォーム送信
        async function handleSubmit(e) {
            e.preventDefault();

            const formData = new FormData();
            const substituteId = document.getElementById('substituteId').value;
            const weekDate = document.getElementById('weekDate').value;

            formData.append('action', substituteId ? 'update' : 'create');
            if (substituteId) formData.append('id', substituteId);
            formData.append('week_date', weekDate);
            formData.append('substitute_no', document.getElementById('substituteNo').value);
            formData.append('company_name', document.getElementById('companyName').value);
            formData.append('name', document.getElementById('substituteName').value);

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert(substituteId ? '更新しました' : '追加しました');
                    closeModal();
                    loadSubstitutes();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('送信エラー:', error);
                alert('送信に失敗しました');
            }
        }

        // 代理出席者削除
        async function deleteSubstitute(id, name) {
            if (!confirm(`「${name}」を削除してもよろしいですか？`)) return;

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id })
                });
                const data = await response.json();

                if (data.success) {
                    alert('削除しました');
                    loadSubstitutes();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました');
            }
        }

        // 全代理出席者削除
        async function deleteAllSubstitutes() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('開催日を選択してください');
                return;
            }

            if (substitutes.length === 0) {
                alert('削除する代理出席者がありません');
                return;
            }

            if (!confirm(`${weekDate}の代理出席者全${substitutes.length}名を削除してもよろしいですか？\nこの操作は取り消せません。`)) return;

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_by_date', week_date: weekDate })
                });
                const data = await response.json();

                if (data.success) {
                    alert('全代理出席者を削除しました');
                    loadSubstitutes();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました');
            }
        }

        // スライド表示
        function openSlide() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('開催日を選択してください');
                return;
            }

            if (substitutes.length === 0) {
                alert('代理出席者が登録されていません');
                return;
            }

            const url = `../slides/substitutes.php?date=${weekDate}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
