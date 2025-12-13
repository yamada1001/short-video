<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンバー管理 | BNI Slide System V2</title>

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

        /* Header */
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

        /* Container */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Actions Bar */
        .actions-bar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #C8102E;
            color: white;
        }

        .btn-primary:hover {
            background: #a00a24;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 300px;
            font-size: 14px;
        }

        /* Members Table */
        .members-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
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
            padding: 15px;
            text-align: left;
            font-weight: 500;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        tbody td {
            padding: 15px;
            font-size: 14px;
        }

        .member-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #eee;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
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

        /* Modal */
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
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input[type="file"] {
            padding: 8px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        /* Count Badge */
        .count-badge {
            background: #C8102E;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-users"></i> メンバー管理</h1>
        <div class="subtitle">BNI Slide System V2 - Member Management</div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="名前・会社名で検索...">
                <span class="count-badge">全 <span id="memberCount">0</span> 名</span>
            </div>
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus"></i> 新規メンバー追加
            </button>
        </div>

        <div class="members-table">
            <table>
                <thead>
                    <tr>
                        <th>写真</th>
                        <th>名前</th>
                        <th>会社名</th>
                        <th>カテゴリ（業種）</th>
                        <th>誕生日</th>
                        <th>ステータス</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody id="membersTableBody">
                    <!-- データをJavaScriptで読み込み -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="memberModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">新規メンバー追加</h2>
            </div>
            <form id="memberForm">
                <input type="hidden" id="memberId">

                <div class="form-group">
                    <label>名前 <span style="color: #C8102E;">*</span></label>
                    <input type="text" id="memberName" required>
                </div>

                <div class="form-group">
                    <label>会社名</label>
                    <input type="text" id="companyName">
                </div>

                <div class="form-group">
                    <label>カテゴリ（業種）</label>
                    <input type="text" id="category" placeholder="例: IT・Web開発">
                </div>

                <div class="form-group">
                    <label>誕生日</label>
                    <input type="date" id="birthday">
                </div>

                <div class="form-group">
                    <label>写真</label>
                    <input type="file" id="photo" accept="image/*">
                </div>

                <div class="form-group">
                    <label>ステータス</label>
                    <select id="isActive">
                        <option value="1">在籍中</option>
                        <option value="0">退会</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">キャンセル</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = '../api/members_crud.php';
        let members = [];

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();

            // 検索機能
            document.getElementById('searchInput').addEventListener('input', filterMembers);

            // フォーム送信
            document.getElementById('memberForm').addEventListener('submit', handleSubmit);
        });

        // メンバー一覧取得
        async function loadMembers() {
            try {
                const response = await fetch(API_BASE + '?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members;
                    renderMembers(members);
                    document.getElementById('memberCount').textContent = members.length;
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
                alert('データの読み込みに失敗しました');
            }
        }

        // メンバー一覧表示
        function renderMembers(memberList) {
            const tbody = document.getElementById('membersTableBody');
            tbody.innerHTML = '';

            memberList.forEach(member => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        ${member.photo_path
                            ? `<img src="${member.photo_path}" class="member-photo" alt="${member.name}">`
                            : '<div class="member-photo"></div>'}
                    </td>
                    <td><strong>${member.name}</strong></td>
                    <td>${member.company_name || '-'}</td>
                    <td>${member.category || '-'}</td>
                    <td>${member.birthday || '-'}</td>
                    <td>
                        <span class="badge ${member.is_active == 1 ? 'badge-active' : 'badge-inactive'}">
                            ${member.is_active == 1 ? '在籍中' : '退会'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editMember(${member.id})">
                                <i class="fas fa-edit"></i> 編集
                            </button>
                            <button class="btn-delete" onclick="deleteMember(${member.id}, '${member.name}')">
                                <i class="fas fa-trash"></i> 削除
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // 検索フィルター
        function filterMembers() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const filtered = members.filter(m =>
                (m.name && m.name.toLowerCase().includes(query)) ||
                (m.company_name && m.company_name.toLowerCase().includes(query))
            );
            renderMembers(filtered);
        }

        // モーダル開く（追加）
        function openAddModal() {
            document.getElementById('modalTitle').textContent = '新規メンバー追加';
            document.getElementById('memberForm').reset();
            document.getElementById('memberId').value = '';
            document.getElementById('memberModal').classList.add('active');
        }

        // モーダル開く（編集）
        function editMember(id) {
            const member = members.find(m => m.id == id);
            if (!member) return;

            document.getElementById('modalTitle').textContent = 'メンバー編集';
            document.getElementById('memberId').value = member.id;
            document.getElementById('memberName').value = member.name;
            document.getElementById('companyName').value = member.company_name || '';
            document.getElementById('category').value = member.category || '';
            document.getElementById('birthday').value = member.birthday || '';
            document.getElementById('isActive').value = member.is_active;

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
            const memberId = document.getElementById('memberId').value;

            formData.append('action', memberId ? 'update' : 'create');
            if (memberId) formData.append('id', memberId);
            formData.append('name', document.getElementById('memberName').value);
            formData.append('company_name', document.getElementById('companyName').value);
            formData.append('category', document.getElementById('category').value);
            formData.append('birthday', document.getElementById('birthday').value);
            formData.append('is_active', document.getElementById('isActive').value);

            const photoFile = document.getElementById('photo').files[0];
            if (photoFile) {
                formData.append('photo', photoFile);
            }

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert(memberId ? '更新しました' : '追加しました');
                    closeModal();
                    loadMembers();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('送信エラー:', error);
                alert('送信に失敗しました');
            }
        }

        // 削除
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
                    loadMembers();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました');
            }
        }
    </script>
</body>
</html>
