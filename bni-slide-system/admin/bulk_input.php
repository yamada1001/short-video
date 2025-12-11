<?php
/**
 * BNI Slide System - Bulk Input (Admin Only)
 * 管理者専用 - 全メンバー一括入力画面
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/date_helper.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRFトークン生成
$csrfToken = generateCSRFToken();

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

// 座席表からメンバーリスト取得
$seatingFile = __DIR__ . '/../data/seating_chart.json';
$seatingData = json_decode(file_get_contents($seatingFile), true);
$tables = $seatingData['tables'] ?? [];

// 全テーブルからメンバー名を抽出
$seatingMemberNames = [];
foreach ($tables as $tableId => $tableData) {
    $positions = $tableData['positions'] ?? [];
    foreach ($positions as $position) {
        $memberName = $position['member_name'] ?? '';
        if (!empty($memberName) && $memberName !== '空席') {
            $seatingMemberNames[] = $memberName;
        }
    }
}

// members.jsonからメンバー情報取得
$membersFile = __DIR__ . '/../data/members.json';
$membersData = json_decode(file_get_contents($membersFile), true);
$members = $membersData['users'] ?? [];

// 座席表に存在するメンバーのみを表示（名前でマッチング）
$activeMembers = [];
foreach ($members as $email => $member) {
    $memberName = $member['name'] ?? '';
    // 座席表に名前が存在し、かつ管理者以外の場合
    if (in_array($memberName, $seatingMemberNames) && ($member['role'] ?? 'member') === 'member') {
        $activeMembers[] = $member;
    }
}

// 座席表の順序でソート（オプション）
usort($activeMembers, function($a, $b) use ($seatingMemberNames) {
    $posA = array_search($a['name'], $seatingMemberNames);
    $posB = array_search($b['name'], $seatingMemberNames);
    return $posA - $posB;
});
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
  <title>全メンバー一括入力 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">
  <style>
    .bulk-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .week-selector-section {
      background-color: var(--bg-white);
      padding: 25px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      margin-bottom: 30px;
    }

    .week-selector-section h2 {
      margin-bottom: 15px;
      color: var(--bni-red);
    }

    .week-selector-section select {
      width: 100%;
      max-width: 400px;
      padding: 12px;
      font-size: 16px;
      border: 2px solid var(--border-color);
      border-radius: 5px;
      background-color: var(--bg-white);
    }

    .member-cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .member-card {
      background-color: var(--bg-white);
      border: 2px solid var(--border-color);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--shadow);
    }

    .member-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid var(--bni-red);
    }

    .member-card-header h3 {
      margin: 0;
      color: var(--bni-red);
      font-size: 20px;
    }

    .member-card-header .member-info {
      font-size: 14px;
      color: var(--text-secondary);
    }

    .form-section {
      margin-bottom: 20px;
    }

    .form-section h4 {
      margin-bottom: 10px;
      color: var(--text-primary);
      font-size: 16px;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: var(--text-primary);
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid var(--border-color);
      border-radius: 5px;
      font-size: 14px;
    }

    .form-group textarea {
      min-height: 80px;
      resize: vertical;
    }

    .radio-group {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .radio-group label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 400;
    }

    .radio-group input[type="radio"] {
      width: 18px;
      height: 18px;
    }

    .checkbox-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .checkbox-group label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 400;
    }

    .checkbox-group input[type="checkbox"] {
      width: 18px;
      height: 18px;
    }

    .dynamic-list {
      margin-top: 10px;
    }

    .dynamic-item {
      background-color: var(--bg-light);
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 10px;
      position: relative;
    }

    .dynamic-item .remove-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: var(--danger-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
    }

    .dynamic-item .remove-btn:hover {
      background-color: #c82333;
    }

    .add-btn {
      background-color: var(--bni-red);
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      margin-top: 10px;
    }

    .add-btn:hover {
      background-color: #A00915;
    }

    .submit-section {
      position: sticky;
      bottom: 0;
      background-color: var(--bg-white);
      padding: 20px;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      text-align: center;
      z-index: 100;
    }

    .submit-btn {
      background-color: var(--success-color);
      color: white;
      border: none;
      padding: 15px 50px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 18px;
      font-weight: 600;
    }

    .submit-btn:hover {
      background-color: #218838;
    }

    .message {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    @media (max-width: 1200px) {
      .member-cards-container {
        grid-template-columns: 1fr;
      }
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
      <div class="site-logo">BNI Slide System</div>
      <nav class="site-nav">
        <ul>
          <li><a href="slide.php">スライド表示</a></li>
          <li><a href="edit.php">編集</a></li>
          <li><a href="bulk_input.php" class="active">一括入力</a></li>
          <li><a href="referrals.php">リファーラル管理</a></li>
          <li><a href="seating.php">座席表編集</a></li>
          <li><a href="monthly_ranking.php">月間ランキング</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="bulk-container">
        <div class="card">
          <h1>全メンバー一括入力</h1>
          <p class="mb-3">週を選択し、全メンバーの出席状況・ビジター・リファーラルなどを一括で入力できます。</p>

          <!-- Success/Error Messages -->
          <div id="message" class="message" style="display: none;"></div>

          <!-- Week Selector -->
          <div class="week-selector-section">
            <h2>対象週の選択</h2>
            <select id="weekSelector">
              <option value="">読み込み中...</option>
            </select>
          </div>

          <!-- Member Forms -->
          <form id="bulkInputForm">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <input type="hidden" id="selectedWeek" name="selected_week">

            <div id="memberCardsContainer" class="member-cards-container">
              <!-- Member cards will be dynamically generated here -->
            </div>

            <!-- Submit Button -->
            <div class="submit-section">
              <button type="submit" class="submit-btn">一括保存</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
    const members = <?php echo json_encode(array_values($activeMembers)); ?>;
    let selectedWeekDate = '';

    // Load weeks
    async function loadWeeks() {
      try {
        const response = await fetch('../api_list_weeks.php');
        const result = await response.json();

        const weekSelector = document.getElementById('weekSelector');
        weekSelector.innerHTML = '<option value="">週を選択してください</option>';

        if (result.success && result.weeks && result.weeks.length > 0) {
          result.weeks.forEach((week, index) => {
            const option = document.createElement('option');
            option.value = week.value;
            option.textContent = week.label;
            if (index === 0) {
              option.selected = true;
            }
            weekSelector.appendChild(option);
          });

          // Auto-select first week and load data
          if (result.weeks.length > 0) {
            selectedWeekDate = result.weeks[0].value;
            document.getElementById('selectedWeek').value = selectedWeekDate;
            loadExistingData(selectedWeekDate);
          }
        }
      } catch (error) {
        console.error('週リストの読み込みに失敗しました:', error);
      }
    }

    // Load existing data for selected week
    async function loadExistingData(weekDate) {
      try {
        const response = await fetch(`../api_load.php?week=${weekDate}`);
        const result = await response.json();

        if (result.success) {
          generateMemberCards(result.data || []);
        } else {
          generateMemberCards([]);
        }
      } catch (error) {
        console.error('データの読み込みに失敗しました:', error);
        generateMemberCards([]);
      }
    }

    // Generate member cards
    function generateMemberCards(existingData) {
      const container = document.getElementById('memberCardsContainer');
      container.innerHTML = '';

      members.forEach((member, index) => {
        // Find existing data for this member
        const existingRecord = existingData.find(row =>
          row['メールアドレス'] === member.email || row['紹介者名'] === member.name
        );

        const card = document.createElement('div');
        card.className = 'member-card';
        card.innerHTML = `
          <div class="member-card-header">
            <h3>${member.name}</h3>
            <div class="member-info">
              ${member.company || '所属なし'}<br>
              ${member.category || 'カテゴリなし'}
            </div>
          </div>

          <input type="hidden" name="members[${index}][name]" value="${member.name}">
          <input type="hidden" name="members[${index}][email]" value="${member.email}">

          <!-- 出席状況 -->
          <div class="form-section">
            <h4>出席状況</h4>
            <div class="radio-group">
              <label>
                <input type="radio" name="members[${index}][attendance]" value="出席" ${existingRecord && existingRecord['出席状況'] === '出席' ? 'checked' : ''} required>
                出席
              </label>
              <label>
                <input type="radio" name="members[${index}][attendance]" value="欠席" ${existingRecord && existingRecord['出席状況'] === '欠席' ? 'checked' : ''}>
                欠席
              </label>
              <label>
                <input type="radio" name="members[${index}][attendance]" value="代理出席" ${existingRecord && existingRecord['出席状況'] === '代理出席' ? 'checked' : ''}>
                代理出席
              </label>
            </div>
          </div>

          <!-- サンクスリップ -->
          <div class="form-section">
            <h4>サンクスリップ</h4>
            <div class="form-group">
              <input type="number" name="members[${index}][thanks_slip]" value="${existingRecord ? existingRecord['サンクスリップ'] || 0 : 0}" min="0" placeholder="枚数">
            </div>
          </div>

          <!-- 121 -->
          <div class="form-section">
            <h4>121実施</h4>
            <div class="form-group">
              <input type="number" name="members[${index}][one_to_one]" value="${existingRecord ? existingRecord['121'] || 0 : 0}" min="0" placeholder="回数">
            </div>
          </div>

          <!-- シェアストーリー -->
          <div class="form-section">
            <h4>シェアストーリー（2分間）</h4>
            <div class="checkbox-group">
              <label>
                <input type="checkbox" name="members[${index}][is_share_story]" value="1" ${existingRecord && existingRecord['is_share_story'] == 1 ? 'checked' : ''}>
                この週の担当者
              </label>
            </div>
          </div>

          <!-- エデュケーション -->
          <div class="form-section">
            <h4>エデュケーション</h4>
            <div class="checkbox-group">
              <label>
                <input type="checkbox" name="members[${index}][is_education_presenter]" value="1" ${existingRecord && existingRecord['is_education_presenter'] == 1 ? 'checked' : ''}>
                この週の担当者
              </label>
            </div>
          </div>

          <!-- ビジター -->
          <div class="form-section">
            <h4>ビジター紹介</h4>
            <div id="visitors_${index}" class="dynamic-list">
              <!-- Visitor items will be added here -->
            </div>
            <button type="button" class="add-btn" onclick="addVisitor(${index})">+ ビジター追加</button>
          </div>

          <!-- コメント -->
          <div class="form-section">
            <h4>今週のコメント</h4>
            <div class="form-group">
              <textarea name="members[${index}][comment]" placeholder="今週のコメントや気づきなど">${existingRecord ? existingRecord['今週のコメント'] || '' : ''}</textarea>
            </div>
          </div>
        `;

        container.appendChild(card);

        // Load existing visitors if any
        if (existingRecord && existingRecord['ビジター名']) {
          addVisitor(index, {
            name: existingRecord['ビジター名'],
            company: existingRecord['ビジター会社名（屋号）'],
            category: existingRecord['ビジター業種']
          });
        }
      });
    }

    // Add visitor
    let visitorCounter = 0;
    function addVisitor(memberIndex, visitorData = null) {
      const visitorList = document.getElementById(`visitors_${memberIndex}`);
      const visitorId = `visitor_${memberIndex}_${visitorCounter++}`;

      const visitorItem = document.createElement('div');
      visitorItem.className = 'dynamic-item';
      visitorItem.id = visitorId;
      visitorItem.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeVisitor('${visitorId}')">削除</button>
        <div class="form-group">
          <label>ビジター名</label>
          <input type="text" name="members[${memberIndex}][visitors][${visitorCounter}][name]" value="${visitorData ? visitorData.name : ''}" placeholder="ビジター名">
        </div>
        <div class="form-group">
          <label>会社名（屋号）</label>
          <input type="text" name="members[${memberIndex}][visitors][${visitorCounter}][company]" value="${visitorData ? visitorData.company : ''}" placeholder="会社名">
        </div>
        <div class="form-group">
          <label>業種</label>
          <input type="text" name="members[${memberIndex}][visitors][${visitorCounter}][category]" value="${visitorData ? visitorData.category : ''}" placeholder="業種">
        </div>
      `;

      visitorList.appendChild(visitorItem);
    }

    // Remove visitor
    function removeVisitor(visitorId) {
      const visitorItem = document.getElementById(visitorId);
      if (visitorItem) {
        visitorItem.remove();
      }
    }

    // Week selector change
    document.getElementById('weekSelector').addEventListener('change', function() {
      selectedWeekDate = this.value;
      document.getElementById('selectedWeek').value = selectedWeekDate;
      loadExistingData(selectedWeekDate);
    });

    // Form submission
    document.getElementById('bulkInputForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const messageDiv = document.getElementById('message');

      try {
        const response = await fetch('../api_bulk_save.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          messageDiv.textContent = '保存しました！';
          messageDiv.className = 'message success';
          messageDiv.style.display = 'block';
          window.scrollTo(0, 0);
        } else {
          messageDiv.textContent = '保存に失敗しました: ' + (result.message || '不明なエラー');
          messageDiv.className = 'message error';
          messageDiv.style.display = 'block';
        }
      } catch (error) {
        messageDiv.textContent = '保存に失敗しました: ' + error.message;
        messageDiv.className = 'message error';
        messageDiv.style.display = 'block';
      }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      loadWeeks();
    });
  </script>
</body>
</html>
