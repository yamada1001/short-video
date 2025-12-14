<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ビジター管理 | BNI Slide System V2</title>

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
            max-width: 1600px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Card */
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

        /* Actions Bar */
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

        /* Buttons */
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

        /* Info Box */
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

        /* Visitors Table */
        .visitors-table {
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
            font-size: 14px;
        }

        .form-group .required {
            color: #C8102E;
            margin-left: 4px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
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

        .slide-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .slide-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-user-friends"></i> ビジター管理</h1>
        <div class="subtitle">BNI Slide System V2 - Visitor Management</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> ビジター情報管理</h2>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> ビジター管理について</h4>
                <ul>
                    <li>同じビジター情報から4つのスライドを自動生成（p.19, p.169-180, p.213-224, p.235）</li>
                    <li>p.19: ビジター紹介テーブル（6名ごとにページ分割）</li>
                    <li>p.169-180: ビジター自己紹介（1人につき1ページ、23秒カウントダウン）</li>
                    <li>p.213-224: ビジター感想（1人につき1ページ、23秒カウントダウン）</li>
                    <li>p.235: ビジターへの感謝（全員をテーブル表示）</li>
                    <li>ビジターは毎週変わるため、削除機能も活用してください</li>
                </ul>
            </div>

            <div class="actions-bar">
                <div class="date-selector">
                    <label><i class="fas fa-calendar"></i> 開催日:</label>
                    <input type="date" id="weekDate">
                    <span class="count-badge">ビジター数: <span id="visitorCount">0</span>名</span>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> ビジター追加
                    </button>
                    <button class="btn btn-danger" onclick="deleteAllVisitors()">
                        <i class="fas fa-trash-alt"></i> 全削除
                    </button>
                </div>
            </div>

            <div class="visitors-table">
                <table id="visitorsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>お名前</th>
                            <th>会社名</th>
                            <th>専門分野</th>
                            <th>スポンサー</th>
                            <th>アテンド</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="visitorsTableBody">
                        <!-- データをJavaScriptで読み込み -->
                    </tbody>
                </table>
            </div>

            <div class="slide-buttons" style="margin-top: 30px;">
                <button class="btn btn-success" onclick="openSlide('visitor_intro')">
                    <i class="fas fa-play"></i> p.19 ビジター紹介スライド
                </button>
                <button class="btn btn-success" onclick="openSlide('visitor_self_intro')">
                    <i class="fas fa-play"></i> p.169-180 自己紹介スライド
                </button>
                <button class="btn btn-success" onclick="openSlide('visitor_feedback')">
                    <i class="fas fa-play"></i> p.213-224 感想スライド
                </button>
                <button class="btn btn-success" onclick="openSlide('visitor_thanks')">
                    <i class="fas fa-play"></i> p.235 感謝スライド
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="visitorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">ビジター追加</h2>
            </div>
            <form id="visitorForm">
                <input type="hidden" id="visitorId">

                <div class="form-row">
                    <div class="form-group">
                        <label>No <span class="required">*</span></label>
                        <input type="number" id="visitorNo" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>お名前 <span class="required">*</span></label>
                        <input type="text" id="visitorName" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>会社名</label>
                    <input type="text" id="companyName">
                </div>

                <div class="form-group">
                    <label>専門分野</label>
                    <input type="text" id="specialty" placeholder="例: IT・Webマーケティング">
                </div>

                <div class="form-group">
                    <label>スポンサー</label>
                    <input type="text" id="sponsor">
                </div>

                <div class="form-group">
                    <label>アテンド（メンバー選択）</label>
                    <select id="attendMemberId">
                        <option value="">メンバーを選択してください</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>お仕事内容 <small>（自己紹介スライドで表示）</small></label>
                    <textarea id="jobDescription" placeholder="例: Webサイト制作、SEO対策、SNS運用代行"></textarea>
                </div>

                <div class="form-group">
                    <label>ご紹介してほしい方・職業 <small>（自己紹介スライドで表示）</small></label>
                    <textarea id="referralRequest" placeholder="例: 飲食店オーナー、不動産会社の方"></textarea>
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
        const API_BASE = '../api/visitors_crud.php';
        const MEMBERS_API = '../api/members_crud.php';
        let visitors = [];
        let members = [];

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            setDefaultDate();
            setupEventListeners();
            loadVisitors();
        });

        // イベントリスナー設定
        function setupEventListeners() {
            // 日付変更時
            document.getElementById('weekDate').addEventListener('change', loadVisitors);

            // フォーム送信
            document.getElementById('visitorForm').addEventListener('submit', handleSubmit);
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

        // メンバー一覧取得
        async function loadMembers() {
            try {
                const response = await fetch(MEMBERS_API + '?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    renderMemberOptions();
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
            }
        }

        // メンバー選択肢表示
        function renderMemberOptions() {
            const select = document.getElementById('attendMemberId');
            const optionHTML = '<option value="">メンバーを選択してください</option>' +
                members.map(member =>
                    `<option value="${member.id}">${member.name}${member.company_name ? ' (' + member.company_name + ')' : ''}</option>`
                ).join('');
            select.innerHTML = optionHTML;
        }

        // ビジター一覧取得
        async function loadVisitors() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) return;

            try {
                const response = await fetch(`${API_BASE}?action=get_by_date&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success) {
                    visitors = data.visitors;
                    renderVisitors();
                    document.getElementById('visitorCount').textContent = visitors.length;
                }
            } catch (error) {
                console.error('ビジター読み込みエラー:', error);
                alert('データの読み込みに失敗しました');
            }
        }

        // ビジター一覧表示
        function renderVisitors() {
            const tbody = document.getElementById('visitorsTableBody');
            tbody.innerHTML = '';

            if (visitors.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>この日付のビジターはまだ登録されていません</p>
                        </td>
                    </tr>
                `;
                return;
            }

            visitors.forEach(visitor => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${visitor.visitor_no}</strong></td>
                    <td><strong>${visitor.name}</strong></td>
                    <td>${visitor.company_name || '-'}</td>
                    <td>${visitor.specialty || '-'}</td>
                    <td>${visitor.sponsor || '-'}</td>
                    <td>${visitor.attend_member_name || '-'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editVisitor(${visitor.id})">
                                <i class="fas fa-edit"></i> 編集
                            </button>
                            <button class="btn-delete" onclick="deleteVisitor(${visitor.id}, '${visitor.name}')">
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

            document.getElementById('modalTitle').textContent = 'ビジター追加';
            document.getElementById('visitorForm').reset();
            document.getElementById('visitorId').value = '';

            // 次のNo自動取得
            try {
                const response = await fetch(`${API_BASE}?action=get_next_visitor_no&week_date=${weekDate}`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('visitorNo').value = data.next_no;
                }
            } catch (error) {
                console.error('次のNo取得エラー:', error);
            }

            document.getElementById('visitorModal').classList.add('active');
        }

        // モーダル開く（編集）
        function editVisitor(id) {
            const visitor = visitors.find(v => v.id == id);
            if (!visitor) return;

            document.getElementById('modalTitle').textContent = 'ビジター編集';
            document.getElementById('visitorId').value = visitor.id;
            document.getElementById('visitorNo').value = visitor.visitor_no;
            document.getElementById('visitorName').value = visitor.name;
            document.getElementById('companyName').value = visitor.company_name || '';
            document.getElementById('specialty').value = visitor.specialty || '';
            document.getElementById('sponsor').value = visitor.sponsor || '';
            document.getElementById('attendMemberId').value = visitor.attend_member_id || '';
            document.getElementById('jobDescription').value = visitor.job_description || '';
            document.getElementById('referralRequest').value = visitor.referral_request || '';

            document.getElementById('visitorModal').classList.add('active');
        }

        // モーダル閉じる
        function closeModal() {
            document.getElementById('visitorModal').classList.remove('active');
        }

        // フォーム送信
        async function handleSubmit(e) {
            e.preventDefault();

            const formData = new FormData();
            const visitorId = document.getElementById('visitorId').value;
            const weekDate = document.getElementById('weekDate').value;

            formData.append('action', visitorId ? 'update' : 'create');
            if (visitorId) formData.append('id', visitorId);
            formData.append('week_date', weekDate);
            formData.append('visitor_no', document.getElementById('visitorNo').value);
            formData.append('name', document.getElementById('visitorName').value);
            formData.append('company_name', document.getElementById('companyName').value);
            formData.append('specialty', document.getElementById('specialty').value);
            formData.append('sponsor', document.getElementById('sponsor').value);
            formData.append('attend_member_id', document.getElementById('attendMemberId').value);
            formData.append('job_description', document.getElementById('jobDescription').value);
            formData.append('referral_request', document.getElementById('referralRequest').value);

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert(visitorId ? '更新しました' : '追加しました');
                    closeModal();
                    loadVisitors();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('送信エラー:', error);
                alert('送信に失敗しました');
            }
        }

        // ビジター削除
        async function deleteVisitor(id, name) {
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
                    loadVisitors();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました');
            }
        }

        // 全ビジター削除
        async function deleteAllVisitors() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('開催日を選択してください');
                return;
            }

            if (visitors.length === 0) {
                alert('削除するビジターがありません');
                return;
            }

            if (!confirm(`${weekDate}のビジター全${visitors.length}名を削除してもよろしいですか？\nこの操作は取り消せません。`)) return;

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_by_date', week_date: weekDate })
                });
                const data = await response.json();

                if (data.success) {
                    alert('全ビジターを削除しました');
                    loadVisitors();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました');
            }
        }

        // スライド表示
        function openSlide(slideType) {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('開催日を選択してください');
                return;
            }

            if (visitors.length === 0) {
                alert('ビジターが登録されていません');
                return;
            }

            const url = `../slides/${slideType}.php?date=${weekDate}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
