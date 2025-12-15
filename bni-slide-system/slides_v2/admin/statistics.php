<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>統計情報管理 | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 24px; font-weight: 600; }
        .header .subtitle { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
        .actions-bar { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background: #C8102E; color: white; }
        .btn-primary:hover { background: #a00a24; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; }
        .stat-card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 25px; }
        .stat-card h2 { font-size: 18px; color: #C8102E; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #555; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .save-btn { width: 100%; margin-top: 10px; padding: 12px; background: #C8102E; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .save-btn:hover { background: #a00a24; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-chart-bar"></i> 統計情報管理</h1>
                <div class="subtitle">各種統計データを管理します</div>
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
            <button class="btn btn-secondary" onclick="viewSlide(188)">
                <i class="fas fa-external-link-alt"></i> p.188を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(189)">
                <i class="fas fa-external-link-alt"></i> p.189を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(190)">
                <i class="fas fa-external-link-alt"></i> p.190を確認
            </button>
            <button class="btn btn-secondary" onclick="viewSlide(302)">
                <i class="fas fa-external-link-alt"></i> p.302を確認
            </button>
        </div>

        <div class="stats-grid">
            <!-- p.188: ビジター統計 -->
            <div class="stat-card">
                <h2><i class="fas fa-users"></i> ビジター統計 (p.188)</h2>
                <form id="visitorStatsForm">
                    <div class="form-group">
                        <label>これまでのビジター数</label>
                        <input type="text" class="number-input" id="visitor_total" required>
                    </div>
                    <div class="form-group">
                        <label>先週の定例会の数</label>
                        <input type="text" class="number-input" id="visitor_last_week_meetings" required>
                    </div>
                    <div class="form-group">
                        <label>本日の定例会の数</label>
                        <input type="text" class="number-input" id="visitor_today_meetings" required>
                    </div>
                    <div class="form-group">
                        <label>現在のメンバー数</label>
                        <input type="text" class="number-input" id="visitor_current_members" required>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> 保存</button>
                </form>
            </div>

            <!-- p.189: リファーラル統計 -->
            <div class="stat-card">
                <h2><i class="fas fa-handshake"></i> リファーラル統計 (p.189)</h2>
                <form id="referralStatsForm">
                    <div class="form-group">
                        <label>日付</label>
                        <input type="date" id="referral_date" required>
                    </div>
                    <div class="form-group">
                        <label>これまでのリファーラル件数</label>
                        <input type="text" class="number-input" id="referral_total" required>
                    </div>
                    <div class="form-group">
                        <label>先週のリファーラル件数</label>
                        <input type="text" class="number-input" id="referral_last_week" required>
                    </div>
                    <div class="form-group">
                        <label>先週平均のリファーラル数</label>
                        <input type="text" class="decimal-input" id="referral_last_week_avg" required>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> 保存</button>
                </form>
            </div>

            <!-- p.190: 売上統計 -->
            <div class="stat-card">
                <h2><i class="fas fa-dollar-sign"></i> 売上統計 (p.190)</h2>
                <form id="salesStatsForm">
                    <div class="form-group">
                        <label>日付</label>
                        <input type="date" id="sales_date" required>
                    </div>
                    <div class="form-group">
                        <label>期間までの売上</label>
                        <input type="text" class="number-input" id="sales_total" required>
                    </div>
                    <div class="form-group">
                        <label>前期間との伸び率 (%)</label>
                        <input type="text" class="decimal-input" id="sales_growth_rate" required>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> 保存</button>
                </form>
            </div>

            <!-- p.302: 週次統計 -->
            <div class="stat-card">
                <h2><i class="fas fa-calendar-week"></i> 週次統計 (p.302)</h2>
                <form id="weeklyStatsForm">
                    <div class="form-group">
                        <label>先週のビジター数</label>
                        <input type="text" class="number-input" id="weekly_last_week_visitors" required>
                    </div>
                    <div class="form-group">
                        <label>今週のビジター数</label>
                        <input type="text" class="number-input" id="weekly_this_week_visitors" required>
                    </div>
                    <div class="form-group">
                        <label>150名までのカウントダウン</label>
                        <input type="text" class="number-input" id="weekly_countdown_150" required>
                    </div>
                    <div class="form-group">
                        <label>毎週の目標数</label>
                        <input type="text" class="number-input" id="weekly_target" required>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> 保存</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('referral_date').value = today;
            document.getElementById('sales_date').value = today;

            loadStatistics();

            // フォーム送信
            document.getElementById('visitorStatsForm').addEventListener('submit', (e) => saveStats(e, 'visitor_total'));
            document.getElementById('referralStatsForm').addEventListener('submit', (e) => saveStats(e, 'referral'));
            document.getElementById('salesStatsForm').addEventListener('submit', (e) => saveStats(e, 'sales'));
            document.getElementById('weeklyStatsForm').addEventListener('submit', (e) => saveStats(e, 'weekly_goal'));
        });

        async function loadStatistics() {
            try {
                const response = await fetch('../api/statistics_crud.php?action=get_latest');
                const data = await response.json();

                if (data.success && data.statistics) {
                    populateStatistics(data.statistics);
                }
            } catch (error) {
                console.error('統計取得エラー:', error);
            }
        }

        function populateStatistics(stats) {
            stats.forEach(stat => {
                const values = JSON.parse(stat.data_json);
                const type = stat.type;

                Object.keys(values).forEach(key => {
                    const element = document.getElementById(key);
                    if (element) {
                        // 数値フィールドの場合はカンマ区切りで表示
                        if (element.classList.contains('number-input')) {
                            element.value = formatNumber(values[key].toString());
                        } else if (element.classList.contains('decimal-input')) {
                            element.value = formatDecimal(values[key].toString());
                        } else {
                            element.value = values[key];
                        }
                    }
                });
            });
        }

        async function saveStats(e, type) {
            e.preventDefault();
            const formData = {};

            // フォームデータ取得
            const form = e.target;
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                // カンマを削除して数値として保存
                if (input.classList.contains('number-input') || input.classList.contains('decimal-input')) {
                    formData[input.id] = input.value.replace(/,/g, '');
                } else {
                    formData[input.id] = input.value;
                }
            });

            // week_dateを取得（フォーム内のdate inputから、なければ今日の日付）
            let weekDate = null;
            if (type === 'referral' && formData.referral_date) {
                weekDate = formData.referral_date;
            } else if (type === 'sales' && formData.sales_date) {
                weekDate = formData.sales_date;
            } else {
                weekDate = new Date().toISOString().split('T')[0];
            }

            try {
                const response = await fetch('../api/statistics_crud.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save',
                        week_date: weekDate,
                        stat_type: type,
                        value: JSON.stringify(formData)
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

        // 数字フォーマット（カンマ区切り）
        function formatNumber(value) {
            const num = value.replace(/,/g, '');
            if (isNaN(num) || num === '') return '';
            return parseInt(num).toLocaleString();
        }

        function formatDecimal(value) {
            const num = value.replace(/,/g, '');
            if (isNaN(num) || num === '') return '';
            return parseFloat(num).toLocaleString('ja-JP', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
        }

        // 整数入力フィールドにカンマ区切りを適用
        document.querySelectorAll('.number-input').forEach(input => {
            input.addEventListener('input', (e) => {
                const cursorPos = e.target.selectionStart;
                const oldValue = e.target.value;
                const newValue = formatNumber(oldValue);
                e.target.value = newValue;

                // カーソル位置を調整
                const diff = newValue.length - oldValue.length;
                e.target.setSelectionRange(cursorPos + diff, cursorPos + diff);
            });
        });

        // 小数入力フィールドにカンマ区切りを適用
        document.querySelectorAll('.decimal-input').forEach(input => {
            input.addEventListener('blur', (e) => {
                e.target.value = formatDecimal(e.target.value);
            });
        });
    </script>
</body>
</html>
