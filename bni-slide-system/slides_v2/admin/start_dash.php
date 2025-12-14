<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタートダッシュプレゼン管理 | BNI Slide System V2</title>

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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
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

        /* Form */
        .form-group {
            margin-bottom: 25px;
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

        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #C8102E;
        }

        /* Page Selection */
        .page-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .page-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s;
        }

        .page-card:hover {
            border-color: #C8102E;
            box-shadow: 0 4px 12px rgba(200,16,46,0.1);
        }

        .page-card h3 {
            color: #C8102E;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Member Selection */
        .member-selection {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .member-preview {
            display: none;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 2px solid #C8102E;
            margin-top: 15px;
        }

        .member-preview.active {
            display: block;
        }

        .member-preview-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .member-preview-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
        }

        .member-preview-info h4 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .member-preview-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 3px;
        }

        /* Buttons */
        .btn {
            padding: 12px 30px;
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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        /* Preview */
        .preview-container {
            position: sticky;
            top: 20px;
        }

        .slide-preview {
            width: 100%;
            aspect-ratio: 16 / 9;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .slide-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 100px 100px;
        }

        .slide-content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
        }

        .slide-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 20px;
            background: #ddd;
        }

        .slide-name {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .slide-company {
            font-size: 20px;
            font-weight: 400;
            opacity: 0.95;
            margin-bottom: 20px;
        }

        .slide-timer {
            font-size: 64px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            margin-top: 20px;
            letter-spacing: 4px;
        }

        .slide-placeholder {
            font-size: 18px;
            opacity: 0.7;
        }

        .page-number {
            position: absolute;
            bottom: 15px;
            right: 20px;
            font-size: 18px;
            font-weight: 500;
            opacity: 0.8;
        }

        /* Info Box */
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box h4 {
            color: #0066cc;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-box p {
            font-size: 13px;
            color: #333;
            line-height: 1.6;
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

        /* Tab Buttons */
        .preview-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            background: #f5f5f5;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            text-align: center;
        }

        .tab-btn.active {
            background: #C8102E;
            color: white;
            border-color: #C8102E;
        }

        .tab-btn:hover {
            border-color: #C8102E;
        }

        .preview-content {
            display: none;
        }

        .preview-content.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                grid-template-columns: 1fr;
            }

            .preview-container {
                position: static;
            }

            .page-selection {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-rocket"></i> スタートダッシュプレゼン管理</h1>
        <div class="subtitle">BNI Slide System V2 - Start Dash Presentation Management</div>
    </div>

    <div class="container">
        <!-- Form Section -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> プレゼン情報入力</h2>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> スタートダッシュプレゼンについて</h4>
                <ul>
                    <li>p.15とp.107の2つのスライドで同じレイアウトを使用</li>
                    <li>それぞれ異なるメンバーを選択可能</li>
                    <li>2分間のカウントダウンタイマーが表示されます</li>
                </ul>
            </div>

            <form id="startDashForm">
                <!-- Week Date -->
                <div class="form-group">
                    <label>開催日 <span class="required">*</span></label>
                    <input type="date" id="weekDate" required>
                </div>

                <!-- Page 15 Selection -->
                <div class="page-card">
                    <h3><i class="fas fa-user"></i> p.15 プレゼンター</h3>
                    <div class="form-group">
                        <label>メンバー選択 <span class="required">*</span></label>
                        <select id="memberId15" required>
                            <option value="">メンバーを選択してください</option>
                        </select>

                        <!-- Member Preview -->
                        <div class="member-preview" id="memberPreview15">
                            <div class="member-preview-content">
                                <img id="previewPhoto15" class="member-preview-photo" alt="">
                                <div class="member-preview-info">
                                    <h4 id="previewName15"></h4>
                                    <p id="previewCompany15"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page 107 Selection -->
                <div class="page-card">
                    <h3><i class="fas fa-user"></i> p.107 プレゼンター</h3>
                    <div class="form-group">
                        <label>メンバー選択 <span class="required">*</span></label>
                        <select id="memberId107" required>
                            <option value="">メンバーを選択してください</option>
                        </select>

                        <!-- Member Preview -->
                        <div class="member-preview" id="memberPreview107">
                            <div class="member-preview-content">
                                <img id="previewPhoto107" class="member-preview-photo" alt="">
                                <div class="member-preview-info">
                                    <h4 id="previewName107"></h4>
                                    <p id="previewCompany107"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 保存
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> リセット
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="viewSlide(15)">
                        <i class="fas fa-external-link-alt"></i> p.15を確認
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="viewSlide(107)">
                        <i class="fas fa-external-link-alt"></i> p.107を確認
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="preview-container">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-eye"></i> スライドプレビュー</h2>
                </div>

                <!-- Tab Buttons -->
                <div class="preview-tabs">
                    <button class="tab-btn active" onclick="switchTab('p15')">p.15</button>
                    <button class="tab-btn" onclick="switchTab('p107')">p.107</button>
                </div>

                <!-- Preview for p.15 -->
                <div class="preview-content active" id="preview-p15">
                    <div class="slide-preview">
                        <div class="slide-content" id="slideContent15">
                            <div class="slide-placeholder">
                                <i class="fas fa-rocket" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>p.15メンバーを選択すると<br>プレビューが表示されます</p>
                            </div>
                        </div>
                        <div class="page-number">p.15</div>
                    </div>
                </div>

                <!-- Preview for p.107 -->
                <div class="preview-content" id="preview-p107">
                    <div class="slide-preview">
                        <div class="slide-content" id="slideContent107">
                            <div class="slide-placeholder">
                                <i class="fas fa-rocket" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>p.107メンバーを選択すると<br>プレビューが表示されます</p>
                            </div>
                        </div>
                        <div class="page-number">p.107</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/start_dash_crud.php';
        let members = [];
        let currentMember15 = null;
        let currentMember107 = null;

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            setupEventListeners();
            setDefaultDate();
            loadExistingData();
        });

        // イベントリスナー設定
        function setupEventListeners() {
            // メンバー選択
            document.getElementById('memberId15').addEventListener('change', (e) => handleMemberChange(e, 15));
            document.getElementById('memberId107').addEventListener('change', (e) => handleMemberChange(e, 107));

            // フォーム送信
            document.getElementById('startDashForm').addEventListener('submit', handleSubmit);
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
                const response = await fetch('../api/members_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    renderMemberOptions();
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
                alert('メンバーデータの読み込みに失敗しました');
            }
        }

        // メンバー選択肢表示
        function renderMemberOptions() {
            const select15 = document.getElementById('memberId15');
            const select107 = document.getElementById('memberId107');

            const optionHTML = '<option value="">メンバーを選択してください</option>' +
                members.map(member =>
                    `<option value="${member.id}">${member.name}${member.company_name ? ' (' + member.company_name + ')' : ''}</option>`
                ).join('');

            select15.innerHTML = optionHTML;
            select107.innerHTML = optionHTML;
        }

        // メンバー変更時
        function handleMemberChange(e, pageNumber) {
            const memberId = e.target.value;

            if (!memberId) {
                document.getElementById(`memberPreview${pageNumber}`).classList.remove('active');
                resetPreview(pageNumber);
                return;
            }

            const currentMember = members.find(m => m.id == memberId);
            if (currentMember) {
                if (pageNumber === 15) {
                    currentMember15 = currentMember;
                } else {
                    currentMember107 = currentMember;
                }
                showMemberPreview(currentMember, pageNumber);
                updateSlidePreview(currentMember, pageNumber);
            }
        }

        // メンバープレビュー表示
        function showMemberPreview(member, pageNumber) {
            document.getElementById(`previewPhoto${pageNumber}`).src = member.photo_path || '';
            document.getElementById(`previewName${pageNumber}`).textContent = member.name;
            document.getElementById(`previewCompany${pageNumber}`).textContent = member.company_name || '';
            document.getElementById(`memberPreview${pageNumber}`).classList.add('active');
        }

        // スライドプレビュー更新
        function updateSlidePreview(member, pageNumber) {
            const slideContent = document.getElementById(`slideContent${pageNumber}`);
            slideContent.innerHTML = `
                ${member.photo_path ? `<img src="${member.photo_path}" class="slide-photo" alt="${member.name}">` : '<div class="slide-photo"></div>'}
                <div class="slide-name">${member.name}</div>
                ${member.company_name ? `<div class="slide-company">${member.company_name}</div>` : ''}
                <div class="slide-timer">2:00</div>
            `;
        }

        // プレビューリセット
        function resetPreview(pageNumber) {
            document.getElementById(`slideContent${pageNumber}`).innerHTML = `
                <div class="slide-placeholder">
                    <i class="fas fa-rocket" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <p>p.${pageNumber}メンバーを選択すると<br>プレビューが表示されます</p>
                </div>
            `;
        }

        // タブ切り替え
        function switchTab(tabId) {
            // タブボタンの状態更新
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // プレビューコンテンツの表示切り替え
            document.querySelectorAll('.preview-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById('preview-' + tabId).classList.add('active');
        }

        // 既存データ読み込み
        async function loadExistingData() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) return;

            try {
                const response = await fetch(`${API_BASE}?action=get&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success && data.presenters) {
                    // p.15のデータ
                    const p15 = data.presenters.find(p => p.page_number == 15);
                    if (p15) {
                        document.getElementById('memberId15').value = p15.member_id;
                        document.getElementById('memberId15').dispatchEvent(new Event('change'));
                    }

                    // p.107のデータ
                    const p107 = data.presenters.find(p => p.page_number == 107);
                    if (p107) {
                        document.getElementById('memberId107').value = p107.member_id;
                        document.getElementById('memberId107').dispatchEvent(new Event('change'));
                    }
                }
            } catch (error) {
                console.error('データ読み込みエラー:', error);
            }
        }

        // フォーム送信
        async function handleSubmit(e) {
            e.preventDefault();

            const memberId15 = document.getElementById('memberId15').value;
            const memberId107 = document.getElementById('memberId107').value;
            const weekDate = document.getElementById('weekDate').value;

            if (!memberId15 || !memberId107 || !weekDate) {
                alert('すべての項目を入力してください');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'create');
            formData.append('week_date', weekDate);
            formData.append('member_id_15', memberId15);
            formData.append('member_id_107', memberId107);

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert('保存しました');
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('送信エラー:', error);
                alert('送信に失敗しました');
            }
        }

        // フォームリセット
        function resetForm() {
            document.getElementById('startDashForm').reset();
            document.getElementById('memberPreview15').classList.remove('active');
            document.getElementById('memberPreview107').classList.remove('active');
            resetPreview(15);
            resetPreview(107);
            setDefaultDate();
        }

        // スライドを確認
        function viewSlide(pageNumber) {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('開催日を選択してください');
                return;
            }

            const url = `../index.php?date=${weekDate}#${pageNumber}`;
            window.open(url, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
