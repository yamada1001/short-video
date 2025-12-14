<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募集カテゴリ管理 | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 24px; font-weight: 600; }
        .header .subtitle { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .actions-bar { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background: #C8102E; color: white; }
        .btn-primary:hover { background: #a00a24; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .category-sections { display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 20px; }
        .category-card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 25px; }
        .category-card h2 { font-size: 18px; color: #C8102E; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #555; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .category-list { margin-bottom: 15px; }
        .category-item { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
        .category-item input { flex: 1; }
        .remove-btn { background: #dc3545; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .add-btn { background: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 15px; }
        .save-btn { width: 100%; padding: 12px; background: #C8102E; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .date-selector { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .survey-rank { display: grid; grid-template-columns: 80px 1fr 100px 40px; gap: 10px; align-items: center; margin-bottom: 10px; }
        .rank-label { font-weight: 600; color: #C8102E; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-list"></i> 募集カテゴリ管理</h1>
        <div class="subtitle">オープン募集カテゴリとアンケート結果を管理します</div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-secondary" onclick="location.href='index.php'">
                <i class="fas fa-arrow-left"></i> ダッシュボードに戻る
            </button>
            <button class="btn btn-primary" onclick="previewSlide()">
                <i class="fas fa-eye"></i> スライドをプレビュー
            </button>
        </div>

        <div class="date-selector">
            <label><i class="fas fa-calendar"></i> 対象週:</label>
            <input type="date" id="weekDate" onchange="loadCategories()">
        </div>

        <div class="category-sections">
            <!-- オープン募集カテゴリ (p.185) -->
            <div class="category-card">
                <h2><i class="fas fa-bullhorn"></i> オープン募集カテゴリ (p.185)</h2>
                <div id="openCategoriesList" class="category-list"></div>
                <button class="add-btn" onclick="addOpenCategory()">
                    <i class="fas fa-plus"></i> カテゴリ追加
                </button>
                <button class="save-btn" onclick="saveCategories('open')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>

            <!-- カテゴリアンケート (p.194) -->
            <div class="category-card">
                <h2><i class="fas fa-poll"></i> カテゴリアンケート結果 (p.194)</h2>
                <div id="surveyCategoriesList" class="category-list"></div>
                <button class="save-btn" onclick="saveCategories('survey')">
                    <i class="fas fa-save"></i> 保存
                </button>
            </div>
        </div>
    </div>

    <script>
        let openCategories = [];
        let surveyCategories = [];

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('weekDate').value = today;

            initializeSurveyCategories();
            loadCategories();
        });

        function initializeSurveyCategories() {
            const container = document.getElementById('surveyCategoriesList');
            container.innerHTML = '';

            for (let rank = 1; rank <= 4; rank++) {
                const div = document.createElement('div');
                div.className = 'survey-rank';
                div.innerHTML = `
                    <div class="rank-label">${rank}位</div>
                    <input type="text" class="survey-category" data-rank="${rank}" placeholder="カテゴリ名">
                    <input type="number" class="survey-votes" data-rank="${rank}" placeholder="得票数" min="0">
                    <div></div>
                `;
                container.appendChild(div);
            }
        }

        function addOpenCategory() {
            const container = document.getElementById('openCategoriesList');
            const index = container.children.length;

            const div = document.createElement('div');
            div.className = 'category-item';
            div.innerHTML = `
                <input type="text" class="open-category" placeholder="カテゴリ名">
                <button class="remove-btn" onclick="removeOpenCategory(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }

        function removeOpenCategory(button) {
            button.parentElement.remove();
        }

        async function loadCategories() {
            const weekDate = document.getElementById('weekDate').value;

            try {
                const response = await fetch(`../api/categories_crud.php?action=get&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success && data.categories) {
                    populateCategories(data.categories);
                } else {
                    // データがない場合は初期化
                    document.getElementById('openCategoriesList').innerHTML = '';
                    initializeSurveyCategories();
                }
            } catch (error) {
                console.error('カテゴリ取得エラー:', error);
            }
        }

        function populateCategories(categories) {
            // オープンカテゴリ
            const openContainer = document.getElementById('openCategoriesList');
            openContainer.innerHTML = '';

            const openCats = categories.filter(c => c.category_type === 'open');
            openCats.forEach(cat => {
                const div = document.createElement('div');
                div.className = 'category-item';
                div.innerHTML = `
                    <input type="text" class="open-category" value="${cat.category_name}">
                    <button class="remove-btn" onclick="removeOpenCategory(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                openContainer.appendChild(div);
            });

            // アンケートカテゴリ
            const surveyCats = categories.filter(c => c.category_type === 'survey');
            surveyCats.forEach(cat => {
                const categoryInput = document.querySelector(`.survey-category[data-rank="${cat.rank}"]`);
                const votesInput = document.querySelector(`.survey-votes[data-rank="${cat.rank}"]`);

                if (categoryInput) categoryInput.value = cat.category_name;
                if (votesInput) votesInput.value = cat.vote_count;
            });
        }

        async function saveCategories(type) {
            const weekDate = document.getElementById('weekDate').value;
            const categoriesData = [];

            if (type === 'open') {
                const inputs = document.querySelectorAll('.open-category');
                inputs.forEach((input, index) => {
                    if (input.value.trim()) {
                        categoriesData.push({
                            category_name: input.value.trim(),
                            rank: null,
                            vote_count: 0
                        });
                    }
                });
            } else if (type === 'survey') {
                for (let rank = 1; rank <= 4; rank++) {
                    const categoryInput = document.querySelector(`.survey-category[data-rank="${rank}"]`);
                    const votesInput = document.querySelector(`.survey-votes[data-rank="${rank}"]`);

                    if (categoryInput.value.trim()) {
                        categoriesData.push({
                            category_name: categoryInput.value.trim(),
                            rank: rank,
                            vote_count: parseInt(votesInput.value) || 0
                        });
                    }
                }
            }

            try {
                const response = await fetch('../api/categories_crud.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save',
                        week_date: weekDate,
                        category_type: type,
                        categories: categoriesData
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

        // スライドプレビュー
        function previewSlide() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('対象週を選択してください');
                return;
            }

            const slideUrl = `../slides/recruiting_categories.php?date=${encodeURIComponent(weekDate)}`;
            window.open(slideUrl, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
