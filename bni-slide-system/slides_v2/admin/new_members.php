<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新入会メンバー管理 | BNI Slide System V2</title>

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

        .btn-delete {
            padding: 6px 12px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
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

        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .member-card {
            background: white;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
        }

        .member-card:hover {
            border-color: #C8102E;
            box-shadow: 0 4px 12px rgba(200,16,46,0.1);
        }

        .member-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
        }

        .member-info {
            flex: 1;
        }

        .member-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .member-company {
            font-size: 14px;
            color: #666;
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

        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group select:focus {
            outline: none;
            border-color: #C8102E;
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
            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .members-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-user-plus"></i> 新入会メンバー管理</h1>
                <div class="subtitle">BNI Slide System V2 - New Members Management</div>
            </div>
            <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-home"></i> 管理画面トップへ
            </a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> 新入会メンバー情報管理</h2>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> 新入会メンバー管理について</h4>
                <ul>
                    <li>p.25-27, p.100-102: 新入会メンバー紹介スライド</li>
                    <li>新入会メンバーは最大3名まで登録可能</li>
                    <li>既存のメンバーから選択してください</li>
                    <li>会社名と写真が自動で表示されます</li>
                </ul>
            </div>

            <div class="actions-bar">
                <div>
                    <span class="count-badge">新入会メンバー: <span id="memberCount">0</span>名 / 最大3名</span>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> メンバー追加
                    </button>
                    <button class="btn btn-danger" onclick="deleteAllMembers()">
                        <i class="fas fa-trash-alt"></i> 全削除
                    </button>
                    <button class="btn btn-success" onclick="openSlide()">
                        <i class="fas fa-play"></i> スライド表示
                    </button>
                </div>
            </div>

            <div class="members-grid" id="membersGrid">
                <!-- データをJavaScriptで読み込み -->
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal" id="memberModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>新入会メンバー追加</h2>
            </div>
            <form id="memberForm">
                <div class="form-group">
                    <label>メンバー選択 <span class="required">*</span></label>
                    <select id="memberId" required>
                        <option value="">メンバーを選択してください</option>
                    </select>
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
        const API_BASE = '../api/new_members_crud.php';
        const MEMBERS_API = '../api/members_crud.php';
        let newMembers = [];
        let allMembers = [];

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            setupEventListeners();
            loadMembers();
            loadNewMembers();
        });

        // イベントリスナー設定
        function setupEventListeners() {
            document.getElementById('memberForm').addEventListener('submit', handleSubmit);
        }

        // 全メンバー一覧取得
        async function loadMembers() {
            try {
                const response = await fetch(MEMBERS_API + '?action=list');
                const data = await response.json();

                if (data.success) {
                    allMembers = data.members.filter(m => m.is_active == 1);
                    renderMemberOptions();
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
            }
        }

        // メンバー選択肢表示
        function renderMemberOptions() {
            const select = document.getElementById('memberId');
            const optionHTML = '<option value="">メンバーを選択してください</option>' +
                allMembers.map(member =>
                    `<option value="${member.id}">${member.name}${member.company_name ? ' (' + member.company_name + ')' : ''}</option>`
                ).join('');
            select.innerHTML = optionHTML;
        }

        // 新入会メンバー一覧取得
        async function loadNewMembers() {
            try {
                const response = await fetch(`${API_BASE}?action=get_latest`);
                const data = await response.json();

                if (data.success) {
                    newMembers = data.new_members;
                    renderNewMembers();
                    document.getElementById('memberCount').textContent = newMembers.length;
                }
            } catch (error) {
                console.error('新入会メンバー読み込みエラー:', error);
                alert('データの読み込みに失敗しました');
            }
        }

        // 新入会メンバー一覧表示
        function renderNewMembers() {
            const grid = document.getElementById('membersGrid');
            grid.innerHTML = '';

            if (newMembers.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-inbox"></i>
                        <p>この日付の新入会メンバーはまだ登録されていません</p>
                    </div>
                `;
                return;
            }

            newMembers.forEach(member => {
                const card = document.createElement('div');
                card.className = 'member-card';
                card.innerHTML = `
                    ${member.photo_path ? `<img src="${member.photo_path}" class="member-photo" alt="${member.member_name}">` : '<div class="member-photo"></div>'}
                    <div class="member-info">
                        <div class="member-name">${member.member_name}</div>
                        <div class="member-company">${member.company_name || ''}</div>
                    </div>
                    <button class="btn-delete" onclick="deleteMember(${member.id}, '${member.member_name}')">
                        <i class="fas fa-trash"></i> 削除
                    </button>
                `;
                grid.appendChild(card);
            });
        }

        // モーダル開く
        function openAddModal() {
            if (newMembers.length >= 3) {
                alert('新入会メンバーは最大3名までです');
                return;
            }

            document.getElementById('memberForm').reset();
            document.getElementById('memberModal').classList.add('active');
        }

        // モーダル閉じる
        function closeModal() {
            document.getElementById('memberModal').classList.remove('active');
        }

        // フォーム送信
        async function handleSubmit(e) {
            e.preventDefault();

            const formData = new FormData();

            formData.append('action', 'create');
            formData.append('member_id', document.getElementById('memberId').value);

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert('追加しました');
                    closeModal();
                    loadNewMembers();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('送信エラー:', error);
                alert('送信に失敗しました');
            }
        }

        // メンバー削除
        async function deleteMember(id, name) {
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
                    loadNewMembers();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました');
            }
        }

        // 全メンバー削除
        async function deleteAllMembers() {
            if (newMembers.length === 0) {
                alert('削除するメンバーがありません');
                return;
            }

            if (!confirm(`新入会メンバー全${newMembers.length}名を削除してもよろしいですか？\nこの操作は取り消せません。`)) return;

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_all' })
                });
                const data = await response.json();

                if (data.success) {
                    alert('全メンバーを削除しました');
                    loadNewMembers();
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
            if (newMembers.length === 0) {
                alert('新入会メンバーが登録されていません');
                return;
            }

            const url = `../slides/new_members.php`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
