<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャンピオン管理 | BNI Slide System V2</title>
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
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .champion-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .champion-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .champion-card h2 {
            font-size: 18px;
            color: #C8102E;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rank-section {
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .rank-section h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 500;
            color: #555;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .add-member-btn {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 5px;
        }

        .add-member-btn:hover {
            background: #218838;
        }

        .member-entry {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: flex-end;
        }

        .member-entry .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .remove-btn:hover {
            background: #c82333;
        }

        .save-btn {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            background: #C8102E;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .save-btn:hover {
            background: #a00a24;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-trophy"></i> チャンピオン管理</h1>
                <div class="subtitle">各チャンピオンの1位～3位を設定します（同率順位対応）</div>
            </div>
            <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-home"></i> 管理画面トップへ
            </a>
        </div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-secondary" onclick="location.href='index.php'">
                <i class="fas fa-arrow-left"></i> ダッシュボードに戻る
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(91)">
                <i class="fas fa-external-link-alt"></i> p.91を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(92)">
                <i class="fas fa-external-link-alt"></i> p.92を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(93)">
                <i class="fas fa-external-link-alt"></i> p.93を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(94)">
                <i class="fas fa-external-link-alt"></i> p.94を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(95)">
                <i class="fas fa-external-link-alt"></i> p.95を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(96)">
                <i class="fas fa-external-link-alt"></i> p.96を確認
            </button>
        </div>

        <div class="champion-types">
            <!-- Referral Champion -->
            <div class="champion-card">
                <h2><i class="fas fa-handshake"></i> リファーラルチャンピオン</h2>
                <div id="referral-champions"></div>
                <button class="save-btn" onclick="saveChampions('referral')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>

            <!-- Value Champion -->
            <div class="champion-card">
                <h2><i class="fas fa-dollar-sign"></i> バリューチャンピオン</h2>
                <div id="value-champions"></div>
                <button class="save-btn" onclick="saveChampions('value')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>

            <!-- Visitor Champion -->
            <div class="champion-card">
                <h2><i class="fas fa-users"></i> ビジターチャンピオン</h2>
                <div id="visitor-champions"></div>
                <button class="save-btn" onclick="saveChampions('visitor')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>

            <!-- 1to1 Champion -->
            <div class="champion-card">
                <h2><i class="fas fa-comments"></i> 1to1チャンピオン</h2>
                <div id="1to1-champions"></div>
                <button class="save-btn" onclick="saveChampions('1to1')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>

            <!-- CEU Champion -->
            <div class="champion-card">
                <h2><i class="fas fa-graduation-cap"></i> CEUチャンピオン</h2>
                <div id="ceu-champions"></div>
                <button class="save-btn" onclick="saveChampions('ceu')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>
        </div>
    </div>

    <script>
        let members = [];
        const championTypes = ['referral', 'value', 'visitor', '1to1', 'ceu'];

        document.addEventListener('DOMContentLoaded', function() {
            loadMembers();
            loadChampions();
        });

        async function loadMembers() {
            try {
                const response = await fetch('../api/members_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    initializeChampionForms();
                }
            } catch (error) {
                console.error('メンバー取得エラー:', error);
            }
        }

        function initializeChampionForms() {
            championTypes.forEach(type => {
                const container = document.getElementById(`${type}-champions`);
                container.innerHTML = '';

                for (let rank = 1; rank <= 3; rank++) {
                    const rankSection = createRankSection(type, rank);
                    container.appendChild(rankSection);
                }
            });
        }

        function createRankSection(type, rank) {
            const section = document.createElement('div');
            section.className = 'rank-section';
            section.id = `${type}-rank-${rank}`;

            const rankLabel = rank === 1 ? '1位' : rank === 2 ? '2位' : '3位';
            const medal = rank === 1 ? '🥇' : rank === 2 ? '🥈' : '🥉';

            section.innerHTML = `
                <h3>${medal} ${rankLabel}</h3>
                <div class="rank-members" id="${type}-rank-${rank}-members">
                    ${createMemberEntry(type, rank, 0)}
                </div>
                <button class="add-member-btn" onclick="addMemberEntry('${type}', ${rank})">
                    <i class="fas fa-plus"></i> 同率を追加
                </button>
            `;

            return section;
        }

        function createMemberEntry(type, rank, index) {
            return `
                <div class="member-entry" data-index="${index}">
                    <div class="form-group">
                        <label>メンバー</label>
                        <select class="member-select" data-type="${type}" data-rank="${rank}" data-index="${index}">
                            <option value="">選択してください</option>
                            ${members.map(m => `<option value="${m.id}">${m.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group" style="max-width: 100px;">
                        <label>件数</label>
                        <input type="number" min="0" class="count-input" data-type="${type}" data-rank="${rank}" data-index="${index}">
                    </div>
                    ${index > 0 ? '<button class="remove-btn" onclick="removeMemberEntry(this)"><i class="fas fa-times"></i></button>' : ''}
                </div>
            `;
        }

        function addMemberEntry(type, rank) {
            const container = document.getElementById(`${type}-rank-${rank}-members`);
            const entries = container.querySelectorAll('.member-entry');
            const newIndex = entries.length;

            const newEntry = document.createElement('div');
            newEntry.innerHTML = createMemberEntry(type, rank, newIndex);
            container.appendChild(newEntry.firstElementChild);
        }

        function removeMemberEntry(button) {
            button.parentElement.remove();
        }

        async function loadChampions() {
            try {
                const response = await fetch('../api/champions_crud.php?action=get_latest');
                const data = await response.json();

                if (data.success && data.champions) {
                    populateChampions(data.champions);
                } else {
                    // データがない場合はフォームをリセット
                    initializeChampionForms();
                }
            } catch (error) {
                console.error('チャンピオン取得エラー:', error);
            }
        }

        function populateChampions(champions) {
            // まずフォームをリセット
            initializeChampionForms();

            // 各タイプ・ランクごとにデータを配置
            championTypes.forEach(type => {
                for (let rank = 1; rank <= 3; rank++) {
                    const rankChampions = champions.filter(c => c.type === type && c.rank === rank);

                    if (rankChampions.length > 0) {
                        const container = document.getElementById(`${type}-rank-${rank}-members`);
                        container.innerHTML = '';

                        rankChampions.forEach((champion, index) => {
                            const entryHTML = createMemberEntry(type, rank, index);
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = entryHTML;
                            const entry = tempDiv.firstElementChild;
                            container.appendChild(entry);

                            // 値を設定
                            entry.querySelector('.member-select').value = champion.member_id;
                            entry.querySelector('.count-input').value = champion.count;
                        });
                    }
                }
            });
        }

        async function saveChampions(type) {
            const championsData = [];

            for (let rank = 1; rank <= 3; rank++) {
                const container = document.getElementById(`${type}-rank-${rank}-members`);
                const entries = container.querySelectorAll('.member-entry');

                entries.forEach(entry => {
                    const memberId = entry.querySelector('.member-select').value;
                    const count = entry.querySelector('.count-input').value;

                    if (memberId && count) {
                        championsData.push({
                            rank: rank,
                            member_id: memberId,
                            count: count
                        });
                    }
                });
            }

            try {
                const response = await fetch('../api/champions_crud.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'save',
                        type: type,
                        champions: championsData
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('保存しました！');
                } else {
                    alert('エラー: ' + data.error);
                }
            } catch (error) {
                alert('通信エラーが発生しました: ' + error);
            }
        }

        // スライドを確認
        function viewSlide(pageNumber) {
            window.open(`../index.php#${pageNumber}`, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
