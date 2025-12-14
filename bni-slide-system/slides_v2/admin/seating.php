<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席管理 | BNI Slide System V2</title>

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
            gap: 20px;
        }

        .date-selector {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .date-selector label {
            font-weight: 500;
            color: #C8102E;
        }

        .date-selector input {
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

        .btn-clear {
            background: #6c757d;
            color: white;
        }

        .btn-clear:hover {
            background: #5a6268;
        }

        /* Layout */
        .seating-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
        }

        /* Member List */
        .member-pool {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: fit-content;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
        }

        .member-pool h3 {
            color: #C8102E;
            margin-bottom: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .member-pool-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .member-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 10px;
            cursor: move;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .member-card:hover {
            background: #e9ecef;
            border-color: #C8102E;
        }

        .member-card.dragging {
            opacity: 0.5;
        }

        .member-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
            flex-shrink: 0;
        }

        .member-info {
            flex: 1;
            min-width: 0;
        }

        .member-name {
            font-weight: 600;
            font-size: 13px;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .member-company {
            font-size: 11px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Seating Preview */
        .seating-preview {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #C8102E;
        }

        .preview-header h3 {
            color: #C8102E;
            font-size: 18px;
        }

        .preview-info {
            font-size: 13px;
            color: #6c757d;
        }

        /* Tables Grid */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .table-container {
            background: #f8f9fa;
            border: 2px dashed #C8102E;
            border-radius: 8px;
            padding: 15px;
            min-height: 200px;
        }

        .table-header {
            background: #C8102E;
            color: white;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .table-members {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 150px;
        }

        .table-members.drag-over {
            background: #e3f2fd;
        }

        .table-members .member-card {
            background: white;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150px;
            color: #adb5bd;
            font-size: 13px;
        }

        .empty-state i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        /* Statistics */
        .stats-bar {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            flex: 1;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #C8102E;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .seating-layout {
                grid-template-columns: 1fr;
            }

            .member-pool {
                max-height: 300px;
            }
        }

        @media (max-width: 768px) {
            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .tables-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-chair"></i> 座席管理</h1>
        <div class="subtitle">BNI Slide System V2 - Seating Arrangement</div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <div class="date-selector">
                <label><i class="fas fa-calendar"></i> 対象日:</label>
                <input type="date" id="targetDate">
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-clear" onclick="clearAllSeats()">
                    <i class="fas fa-eraser"></i> すべてクリア
                </button>
                <button class="btn btn-success" onclick="saveSeating()">
                    <i class="fas fa-save"></i> 座席配置を保存
                </button>
                <button class="btn btn-primary" onclick="previewSlide()">
                    <i class="fas fa-eye"></i> スライドをプレビュー
                </button>
            </div>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value" id="totalMembers">0</div>
                <div class="stat-label">総メンバー数</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="assignedMembers">0</div>
                <div class="stat-label">配置済み</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="unassignedMembers">0</div>
                <div class="stat-label">未配置</div>
            </div>
        </div>

        <div class="seating-layout">
            <!-- Member Pool -->
            <div class="member-pool">
                <h3><i class="fas fa-users"></i> メンバーリスト</h3>
                <div class="member-pool-list" id="memberPool">
                    <!-- メンバーカードがJavaScriptで追加される -->
                </div>
            </div>

            <!-- Seating Preview -->
            <div class="seating-preview">
                <div class="preview-header">
                    <h3>座席配置プレビュー</h3>
                    <div class="preview-info">
                        ドラッグ&ドロップでメンバーを配置してください
                    </div>
                </div>

                <div class="tables-grid" id="tablesGrid">
                    <!-- テーブルがJavaScriptで追加される -->
                </div>
            </div>
        </div>
    </div>

    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        const API_BASE = '../api/seating_crud.php';
        const MEMBERS_API = '../api/members_crud.php';

        // テーブル定義
        const TABLES = ['A', 'B', 'C', 'D', 'E', 'F'];

        let members = [];
        let currentSeating = {};

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            // 今日の日付を設定
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('targetDate').value = today;

            // データ読み込み
            loadMembers();
            loadSeating();

            // 日付変更時
            document.getElementById('targetDate').addEventListener('change', loadSeating);
        });

        // メンバー一覧取得
        async function loadMembers() {
            try {
                const response = await fetch(MEMBERS_API + '?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    renderMemberPool();
                    updateStats();
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
                alert('メンバーデータの読み込みに失敗しました');
            }
        }

        // 座席配置取得
        async function loadSeating() {
            const targetDate = document.getElementById('targetDate').value;
            if (!targetDate) return;

            try {
                const response = await fetch(`${API_BASE}?action=get&week_date=${targetDate}`);
                const data = await response.json();

                if (data.success) {
                    currentSeating = data.seating;
                    renderTables();
                    updateStats();
                }
            } catch (error) {
                console.error('座席配置読み込みエラー:', error);
                renderTables(); // 空のテーブルを表示
            }
        }

        // メンバープール表示
        function renderMemberPool() {
            const pool = document.getElementById('memberPool');
            pool.innerHTML = '';

            const unassignedMembers = getUnassignedMembers();

            unassignedMembers.forEach(member => {
                const card = createMemberCard(member);
                pool.appendChild(card);
            });

            // SortableJS設定
            new Sortable(pool, {
                group: 'shared',
                animation: 150,
                onEnd: function(evt) {
                    updateStats();
                }
            });
        }

        // テーブル表示
        function renderTables() {
            const grid = document.getElementById('tablesGrid');
            grid.innerHTML = '';

            TABLES.forEach(tableName => {
                const container = document.createElement('div');
                container.className = 'table-container';

                const header = document.createElement('div');
                header.className = 'table-header';
                header.innerHTML = `<i class="fas fa-table"></i> テーブル ${tableName}`;

                const membersList = document.createElement('div');
                membersList.className = 'table-members';
                membersList.dataset.table = tableName;

                // このテーブルに配置されているメンバーを取得
                const tableMembers = getTableMembers(tableName);

                if (tableMembers.length === 0) {
                    membersList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-chair"></i>
                            <div>メンバーをドロップ</div>
                        </div>
                    `;
                } else {
                    tableMembers.forEach(member => {
                        const card = createMemberCard(member);
                        membersList.appendChild(card);
                    });
                }

                // SortableJS設定
                new Sortable(membersList, {
                    group: 'shared',
                    animation: 150,
                    onAdd: function(evt) {
                        // 空の状態表示を削除
                        const emptyState = evt.to.querySelector('.empty-state');
                        if (emptyState) emptyState.remove();
                        updateStats();
                    },
                    onRemove: function(evt) {
                        // 空になったら空の状態表示
                        if (evt.from.children.length === 0) {
                            evt.from.innerHTML = `
                                <div class="empty-state">
                                    <i class="fas fa-chair"></i>
                                    <div>メンバーをドロップ</div>
                                </div>
                            `;
                        }
                        updateStats();
                    }
                });

                container.appendChild(header);
                container.appendChild(membersList);
                grid.appendChild(container);
            });
        }

        // メンバーカード作成
        function createMemberCard(member) {
            const card = document.createElement('div');
            card.className = 'member-card';
            card.dataset.memberId = member.id;

            card.innerHTML = `
                ${member.photo_path
                    ? `<img src="${member.photo_path}" class="member-photo" alt="${member.name}">`
                    : '<div class="member-photo"></div>'}
                <div class="member-info">
                    <div class="member-name">${member.name}</div>
                    <div class="member-company">${member.company_name || '未設定'}</div>
                </div>
            `;

            return card;
        }

        // テーブルに配置されているメンバーを取得
        function getTableMembers(tableName) {
            if (!currentSeating[tableName]) return [];

            return currentSeating[tableName]
                .map(memberId => members.find(m => m.id == memberId))
                .filter(m => m);
        }

        // 未配置のメンバーを取得
        function getUnassignedMembers() {
            const assignedIds = new Set();

            Object.values(currentSeating).forEach(tableMembers => {
                tableMembers.forEach(id => assignedIds.add(id));
            });

            return members.filter(m => !assignedIds.has(m.id));
        }

        // 統計更新
        function updateStats() {
            const assigned = countAssignedMembers();
            const total = members.length;
            const unassigned = total - assigned;

            document.getElementById('totalMembers').textContent = total;
            document.getElementById('assignedMembers').textContent = assigned;
            document.getElementById('unassignedMembers').textContent = unassigned;
        }

        // 配置済みメンバー数カウント
        function countAssignedMembers() {
            const tables = document.querySelectorAll('.table-members');
            let count = 0;

            tables.forEach(table => {
                const cards = table.querySelectorAll('.member-card');
                count += cards.length;
            });

            return count;
        }

        // 座席配置を保存
        async function saveSeating() {
            const targetDate = document.getElementById('targetDate').value;
            if (!targetDate) {
                alert('対象日を選択してください');
                return;
            }

            // 現在の配置を取得
            const seatingData = [];
            const tables = document.querySelectorAll('.table-members');

            tables.forEach(table => {
                const tableName = table.dataset.table;
                const memberCards = table.querySelectorAll('.member-card');

                memberCards.forEach((card, index) => {
                    seatingData.push({
                        table_name: tableName,
                        member_id: parseInt(card.dataset.memberId),
                        position: index + 1
                    });
                });
            });

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save',
                        week_date: targetDate,
                        seating: seatingData
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('座席配置を保存しました');
                    loadSeating(); // 再読み込み
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('保存エラー:', error);
                alert('保存に失敗しました');
            }
        }

        // すべての座席をクリア
        function clearAllSeats() {
            if (!confirm('すべての座席配置をクリアしてもよろしいですか？')) return;

            currentSeating = {};
            renderTables();
            renderMemberPool();
            updateStats();
        }

        // スライドプレビュー
        function previewSlide() {
            const targetDate = document.getElementById('targetDate').value;
            if (!targetDate) {
                alert('対象日を選択してください');
                return;
            }

            const slideUrl = `../slides/seating.php?date=${encodeURIComponent(targetDate)}`;
            window.open(slideUrl, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
