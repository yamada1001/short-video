<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スピーカーローテーション管理 | BNI Slide System V2</title>

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

        /* Rotation Table */
        .rotation-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .rotation-table thead {
            background: #C8102E;
            color: white;
        }

        .rotation-table thead th {
            padding: 12px;
            text-align: left;
            font-weight: 500;
            font-size: 14px;
        }

        .rotation-table tbody tr {
            border-bottom: 1px solid #eee;
        }

        .rotation-table tbody tr:hover {
            background: #f9f9f9;
        }

        .rotation-table tbody td {
            padding: 12px;
        }

        .rotation-table input[type="date"],
        .rotation-table select,
        .rotation-table textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .rotation-table textarea {
            resize: vertical;
            min-height: 60px;
        }

        .rotation-table input:focus,
        .rotation-table select:focus,
        .rotation-table textarea:focus {
            outline: none;
            border-color: #C8102E;
        }

        .week-label {
            font-weight: 500;
            color: #C8102E;
            white-space: nowrap;
        }

        .week-label.current {
            background: #C8102E;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
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
            margin-top: 20px;
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
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
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
            width: 100%;
        }

        .slide-title {
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .preview-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .preview-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .preview-table thead {
            background: #2c3e50;
            color: white;
        }

        .preview-table thead th {
            padding: 10px;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
        }

        .preview-table tbody tr {
            border-bottom: 1px solid #eee;
        }

        .preview-table tbody tr:last-child {
            border-bottom: none;
        }

        .preview-table tbody td {
            padding: 10px;
            font-size: 13px;
            color: #333;
        }

        .preview-placeholder {
            text-align: center;
            padding: 40px;
        }

        .preview-placeholder i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .preview-placeholder p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                grid-template-columns: 1fr;
            }

            .preview-container {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-calendar-alt"></i> スピーカーローテーション管理</h1>
        <div class="subtitle">BNI Slide System V2 - Speaker Rotation Management (p.9-14, p.199-203, p.297-301)</div>
    </div>

    <div class="container">
        <!-- Form Section -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> 6週分のスピーカーローテーション</h2>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> スピーカーローテーションについて</h4>
                <ul>
                    <li>6週分のデータを管理します（過去3週 + 今週 + 未来2週）</li>
                    <li>日付は自動で毎週金曜日を計算します</li>
                    <li>同じスライドが3箇所に表示されます（p.9-14, p.199-203, p.297-301）</li>
                    <li>変更後は「保存」ボタンを押してください</li>
                </ul>
            </div>

            <form id="rotationForm">
                <table class="rotation-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">週</th>
                            <th style="width: 150px;">日程</th>
                            <th style="width: 200px;">メインプレゼン</th>
                            <th>ご紹介してほしい人</th>
                        </tr>
                    </thead>
                    <tbody id="rotationTableBody">
                        <!-- データをJavaScriptで生成 -->
                    </tbody>
                </table>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 一括保存
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> リセット
                    </button>
                    <button type="button" class="btn btn-success" onclick="previewSlide()">
                        <i class="fas fa-eye"></i> スライドプレビュー
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="preview-container">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-eye"></i> プレビュー（p.9-14）</h2>
                </div>

                <div class="slide-preview">
                    <div class="slide-content" id="slideContent">
                        <div class="preview-placeholder">
                            <i class="fas fa-calendar-alt"></i>
                            <p>スピーカーローテーション<br>プレビュー</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/speaker_rotation_crud.php';
        let members = [];
        let currentWeekIndex = 3; // 今週は4番目（0-indexed）

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            setupEventListeners();
        });

        // イベントリスナー設定
        function setupEventListeners() {
            // フォーム送信
            document.getElementById('rotationForm').addEventListener('submit', handleSubmit);

            // プレビュー自動更新（入力変更時）
            document.getElementById('rotationTableBody').addEventListener('change', updatePreview);
        }

        // メンバー一覧取得
        async function loadMembers() {
            try {
                const response = await fetch('../api/members_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    await loadRotationData();
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
                alert('メンバーデータの読み込みに失敗しました');
            }
        }

        // スピーカーローテーションデータ取得
        async function loadRotationData() {
            try {
                const response = await fetch(API_BASE + '?action=get_six_weeks');
                const data = await response.json();

                if (data.success) {
                    renderRotationTable(data.weeks);
                    updatePreview();
                }
            } catch (error) {
                console.error('ローテーションデータ読み込みエラー:', error);
                alert('データの読み込みに失敗しました');
            }
        }

        // ローテーションテーブル表示
        function renderRotationTable(weeks) {
            const tbody = document.getElementById('rotationTableBody');
            tbody.innerHTML = '';

            weeks.forEach((week, index) => {
                const tr = document.createElement('tr');

                // 週ラベル
                let weekLabel = '';
                if (index < currentWeekIndex) {
                    weekLabel = `過去${currentWeekIndex - index}週`;
                } else if (index === currentWeekIndex) {
                    weekLabel = '<span class="week-label current">今週</span>';
                } else {
                    weekLabel = `未来${index - currentWeekIndex}週`;
                }

                tr.innerHTML = `
                    <td class="week-label">${weekLabel}</td>
                    <td>
                        <input type="date"
                               name="rotation_date[]"
                               value="${week.rotation_date}"
                               required
                               data-index="${index}">
                    </td>
                    <td>
                        <select name="member_id[]" data-index="${index}">
                            <option value="">選択してください</option>
                            ${members.map(m => `
                                <option value="${m.id}" ${m.id == week.main_presenter_id ? 'selected' : ''}>
                                    ${m.name}${m.company_name ? ' (' + m.company_name + ')' : ''}
                                </option>
                            `).join('')}
                        </select>
                    </td>
                    <td>
                        <textarea name="referral_target[]"
                                  placeholder="ご紹介してほしい人・職業を入力..."
                                  data-index="${index}">${week.referral_target || ''}</textarea>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // プレビュー更新
        function updatePreview() {
            const dates = Array.from(document.querySelectorAll('input[name="rotation_date[]"]'));
            const memberIds = Array.from(document.querySelectorAll('select[name="member_id[]"]'));
            const referralTargets = Array.from(document.querySelectorAll('textarea[name="referral_target[]"]'));

            const slideContent = document.getElementById('slideContent');

            // テーブルデータ作成
            let tableRows = '';
            dates.forEach((dateInput, index) => {
                const date = dateInput.value;
                const memberId = memberIds[index].value;
                const member = members.find(m => m.id == memberId);
                const memberName = member ? member.name : '-';
                const referralTarget = referralTargets[index].value || '-';

                const formattedDate = date ? formatDate(date) : '-';

                tableRows += `
                    <tr>
                        <td><strong>${formattedDate}</strong></td>
                        <td>${memberName}</td>
                        <td>${referralTarget}</td>
                    </tr>
                `;
            });

            slideContent.innerHTML = `
                <div class="slide-title">スピーカーローテーション</div>
                <div class="preview-table">
                    <table>
                        <thead>
                            <tr>
                                <th>日程</th>
                                <th>メインプレゼン</th>
                                <th>ご紹介してほしい人</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                </div>
            `;
        }

        // 日付フォーマット（YYYY-MM-DD → MM/DD）
        function formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${month}/${day}`;
        }

        // フォーム送信
        async function handleSubmit(e) {
            e.preventDefault();

            const dates = Array.from(document.querySelectorAll('input[name="rotation_date[]"]'));
            const memberIds = Array.from(document.querySelectorAll('select[name="member_id[]"]'));
            const referralTargets = Array.from(document.querySelectorAll('textarea[name="referral_target[]"]'));

            // データ構築
            const weeks = dates.map((dateInput, index) => ({
                rotation_date: dateInput.value,
                main_presenter_id: memberIds[index].value || null,
                referral_target: referralTargets[index].value || null
            }));

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save_six_weeks',
                        weeks: weeks
                    })
                });
                const data = await response.json();

                if (data.success) {
                    alert('保存しました');
                    loadRotationData();
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
            if (confirm('入力内容をリセットしてもよろしいですか？')) {
                loadRotationData();
            }
        }

        // スライドプレビュー（別ウィンドウ）
        function previewSlide() {
            window.open('../slides/speaker_rotation.php', '_blank', 'width=1280,height=720');
        }
    </script>
</body>
</html>
