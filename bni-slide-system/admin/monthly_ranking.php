<?php
/**
 * BNI Slide System - Monthly Ranking Input (Admin Only)
 * 管理者専用 - 月間ランキング入力画面
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/db.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    die('<h1>アクセス拒否</h1><p>このページは管理者のみアクセス可能です。</p><a href="../index.php">ホームに戻る</a>');
}

// デフォルトは先月のデータ
$defaultYearMonth = date('Y-m', strtotime('last month'));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
  <!-- End Google Tag Manager -->

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>月間ランキング入力 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <style>
    .ranking-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .year-month-selector {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      margin-bottom: 30px;
    }

    .year-month-selector label {
      font-weight: 600;
      margin-right: 10px;
    }

    .year-month-selector input[type="month"] {
      padding: 8px 12px;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      font-size: 16px;
    }

    .ranking-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
      gap: 30px;
      margin-bottom: 30px;
    }

    .ranking-card {
      background: white;
      padding: 25px;
      border-radius: 8px;
      box-shadow: var(--shadow);
    }

    .ranking-card h3 {
      color: var(--bni-red);
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--bni-red);
    }

    .ranking-entry {
      display: grid;
      grid-template-columns: 60px 1fr 150px;
      gap: 10px;
      margin-bottom: 15px;
      align-items: center;
    }

    .ranking-entry label {
      font-weight: 600;
      color: #666;
    }

    .ranking-entry input {
      padding: 10px;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      font-size: 14px;
    }

    .save-button-container {
      text-align: center;
      padding: 30px;
      background: white;
      border-radius: 8px;
      box-shadow: var(--shadow);
    }

    .message {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: none;
    }

    .message.success {
      background: #D4EDDA;
      color: #155724;
      border: 1px solid #C3E6CB;
    }

    .message.error {
      background: #F8D7DA;
      color: #721C24;
      border: 1px solid #F5C6CB;
    }
  </style>
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <div class="site-logo">BNI Slide System - Admin</div>
      <nav class="site-nav">
        <ul>
          <li><a href="slide.php">スライド表示</a></li>
          <li><a href="edit.php">編集</a></li>
          <li><a href="bulk_input.php">一括入力</a></li>
          <li><a href="referrals.php">リファーラル管理</a></li>
          <li><a href="seating.php">座席表編集</a></li>
          <li><a href="monthly_ranking.php" class="active">月間ランキング</a></li>
          <li><a href="visitor_intro.php">ビジターご紹介</a></li>
          <li><a href="networking_learning.php">ネットワーキング学習</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="ranking-container">
        <h1>月間ランキング入力</h1>

        <div id="message" class="message"></div>

        <!-- Year-Month Selector -->
        <div class="year-month-selector">
          <label for="yearMonth">対象月:</label>
          <input type="month" id="yearMonth" value="<?php echo $defaultYearMonth; ?>">
          <button onclick="loadRankingData()" class="btn btn-secondary" style="margin-left: 10px;">
            <i class="fas fa-download"></i> データ読み込み
          </button>
          <span style="margin-left: 15px; color: #666;">※ 月初スライドで発表する先月のランキングデータを入力してください</span>
        </div>

        <!-- Ranking Forms -->
        <form id="rankingForm">
          <div class="ranking-grid">
            <!-- リファーラル金額ランキング -->
            <div class="ranking-card">
              <h3><i class="fas fa-dollar-sign"></i> リファーラル金額ランキング</h3>
              <?php for($i = 1; $i <= 5; $i++): ?>
              <div class="ranking-entry">
                <label><?php echo $i; ?>位</label>
                <input type="text" name="referral_amount[<?php echo $i; ?>][name]" placeholder="メンバー名">
                <input type="number" name="referral_amount[<?php echo $i; ?>][value]" placeholder="金額（円）" min="0">
              </div>
              <?php endfor; ?>
            </div>

            <!-- ビジター紹介数ランキング -->
            <div class="ranking-card">
              <h3><i class="fas fa-users"></i> ビジター紹介数ランキング</h3>
              <?php for($i = 1; $i <= 5; $i++): ?>
              <div class="ranking-entry">
                <label><?php echo $i; ?>位</label>
                <input type="text" name="visitor_count[<?php echo $i; ?>][name]" placeholder="メンバー名">
                <input type="number" name="visitor_count[<?php echo $i; ?>][value]" placeholder="人数" min="0">
              </div>
              <?php endfor; ?>
            </div>

            <!-- 出席率ランキング -->
            <div class="ranking-card">
              <h3><i class="fas fa-calendar-check"></i> 出席率ランキング</h3>
              <?php for($i = 1; $i <= 5; $i++): ?>
              <div class="ranking-entry">
                <label><?php echo $i; ?>位</label>
                <input type="text" name="attendance_rate[<?php echo $i; ?>][name]" placeholder="メンバー名">
                <input type="number" name="attendance_rate[<?php echo $i; ?>][value]" placeholder="出席率（%）" min="0" max="100" step="0.1">
              </div>
              <?php endfor; ?>
            </div>

            <!-- 121回数ランキング -->
            <div class="ranking-card">
              <h3><i class="fas fa-handshake"></i> 121回数ランキング</h3>
              <?php for($i = 1; $i <= 5; $i++): ?>
              <div class="ranking-entry">
                <label><?php echo $i; ?>位</label>
                <input type="text" name="one_to_one_count[<?php echo $i; ?>][name]" placeholder="メンバー名">
                <input type="number" name="one_to_one_count[<?php echo $i; ?>][value]" placeholder="回数" min="0">
              </div>
              <?php endfor; ?>
            </div>
          </div>

          <!-- Display in Slide Checkbox -->
          <div class="form-group" style="background: #f0f8ff; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 2px solid #4CAF50;">
            <label style="display: flex; align-items: center; font-size: 18px; font-weight: 600; cursor: pointer;">
              <input type="checkbox" id="displayInSlide" name="display_in_slide" value="1" style="width: 24px; height: 24px; margin-right: 12px; cursor: pointer;">
              <span>
                <i class="fas fa-presentation-screen" style="color: #4CAF50; margin-right: 8px;"></i>
                このランキングをスライドに表示する
              </span>
            </label>
            <p style="margin: 10px 0 0 36px; font-size: 14px; color: #666;">
              チェックを入れると、通常スライドの最後にこの月間ランキングが自動的に表示されます。
            </p>
          </div>

          <!-- Save Button -->
          <div class="save-button-container">
            <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 18px;">
              <i class="fas fa-save"></i> ランキングデータを保存
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2024 BNI Slide System. All rights reserved.</p>
    </div>
  </footer>

  <script>
    const form = document.getElementById('rankingForm');
    const yearMonthInput = document.getElementById('yearMonth');
    const messageDiv = document.getElementById('message');

    // フォーム送信
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const yearMonth = yearMonthInput.value;
      if (!yearMonth) {
        showMessage('対象月を選択してください', 'error');
        return;
      }

      // フォームデータを収集
      const formData = new FormData(form);
      const rankingData = {
        referral_amount: [],
        visitor_count: [],
        attendance_rate: [],
        one_to_one_count: []
      };

      // 各ランキング種類ごとにデータを整理
      for (let i = 1; i <= 5; i++) {
        const refName = formData.get(`referral_amount[${i}][name]`);
        const refValue = formData.get(`referral_amount[${i}][value]`);
        if (refName && refValue) {
          rankingData.referral_amount.push({ rank: i, name: refName, value: parseFloat(refValue) });
        }

        const visName = formData.get(`visitor_count[${i}][name]`);
        const visValue = formData.get(`visitor_count[${i}][value]`);
        if (visName && visValue) {
          rankingData.visitor_count.push({ rank: i, name: visName, value: parseInt(visValue) });
        }

        const attName = formData.get(`attendance_rate[${i}][name]`);
        const attValue = formData.get(`attendance_rate[${i}][value]`);
        if (attName && attValue) {
          rankingData.attendance_rate.push({ rank: i, name: attName, value: parseFloat(attValue) });
        }

        const onetoName = formData.get(`one_to_one_count[${i}][name]`);
        const onetoValue = formData.get(`one_to_one_count[${i}][value]`);
        if (onetoName && onetoValue) {
          rankingData.one_to_one_count.push({ rank: i, name: onetoName, value: parseInt(onetoValue) });
        }
      }

      // display_in_slide の値を取得
      const displayInSlide = document.getElementById('displayInSlide').checked ? 1 : 0;

      // 保存API呼び出し
      try {
        const response = await fetch('../api_save_monthly_ranking.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            year_month: yearMonth,
            ranking_data: rankingData,
            display_in_slide: displayInSlide
          })
        });

        const result = await response.json();

        if (result.success) {
          showMessage('ランキングデータを保存しました', 'success');
        } else {
          showMessage(result.message || '保存に失敗しました', 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        showMessage('保存中にエラーが発生しました', 'error');
      }
    });

    // データ読み込み
    async function loadRankingData() {
      const yearMonth = yearMonthInput.value;
      if (!yearMonth) {
        showMessage('対象月を選択してください', 'error');
        return;
      }

      try {
        const response = await fetch(`../api_load_monthly_ranking.php?year_month=${yearMonth}`);
        const result = await response.json();

        if (result.success && result.data) {
          const data = result.data;

          // フォームにデータを設定
          ['referral_amount', 'visitor_count', 'attendance_rate', 'one_to_one_count'].forEach(type => {
            if (data[type]) {
              data[type].forEach(entry => {
                const rank = entry.rank;
                const nameInput = document.querySelector(`input[name="${type}[${rank}][name]"]`);
                const valueInput = document.querySelector(`input[name="${type}[${rank}][value]"]`);
                if (nameInput) nameInput.value = entry.name || '';
                if (valueInput) valueInput.value = entry.value || '';
              });
            }
          });

          // チェックボックスの状態を復元
          const displayInSlide = document.getElementById('displayInSlide');
          if (displayInSlide && result.display_in_slide !== undefined) {
            displayInSlide.checked = result.display_in_slide == 1;
          }

          showMessage('データを読み込みました', 'success');
        } else {
          showMessage('データが見つかりません。新規作成します。', 'error');
          form.reset();
        }
      } catch (error) {
        console.error('Error:', error);
        showMessage('データの読み込みに失敗しました', 'error');
      }
    }

    function showMessage(text, type) {
      messageDiv.textContent = text;
      messageDiv.className = `message ${type}`;
      messageDiv.style.display = 'block';

      setTimeout(() => {
        messageDiv.style.display = 'none';
      }, 5000);
    }
  </script>
</body>
</html>
