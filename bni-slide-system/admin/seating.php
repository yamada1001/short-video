<?php
/**
 * BNI Slide System - Seating Chart Editor (Admin Only)
 * 管理者専用 - 座席表編集画面
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/csrf.php';

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

// メンバーリストを読み込み
$membersPath = __DIR__ . '/../data/members.json';
$members = [];
if (file_exists($membersPath)) {
    $json = file_get_contents($membersPath);
    $membersData = json_decode($json, true);
    if ($membersData && isset($membersData['members'])) {
        $members = $membersData['members'];
    }
}
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
  <title>座席表編集 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Sortable.js -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">
  <style>
    .seating-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .seating-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .table-card {
      background-color: var(--bg-white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--shadow);
    }

    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--bni-red);
    }

    .table-header h3 {
      margin: 0;
      color: var(--bni-red);
      font-size: 20px;
    }

    .seats-list {
      min-height: 200px;
      background-color: #f8f9fa;
      border-radius: 4px;
      padding: 10px;
    }

    .seat-item {
      background-color: white;
      border: 2px solid #dee2e6;
      border-radius: 4px;
      padding: 10px;
      margin-bottom: 8px;
      cursor: move;
      transition: all 0.3s;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .seat-item:hover {
      border-color: var(--bni-red);
      box-shadow: 0 2px 8px rgba(208, 12, 36, 0.2);
    }

    .seat-item.sortable-ghost {
      opacity: 0.4;
      background-color: #e3f2fd;
    }

    .seat-item.sortable-drag {
      opacity: 1;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .seat-position {
      font-size: 12px;
      color: #666;
      font-weight: 500;
    }

    .seat-name {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-primary);
    }

    .member-pool {
      background-color: var(--bg-white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--shadow);
      margin-bottom: 30px;
    }

    .member-pool h3 {
      margin-bottom: 15px;
      color: var(--text-primary);
    }

    .member-pool-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 10px;
      min-height: 100px;
      background-color: #f8f9fa;
      border-radius: 4px;
      padding: 15px;
    }

    .member-chip {
      background-color: white;
      border: 2px solid #dee2e6;
      border-radius: 20px;
      padding: 8px 15px;
      cursor: move;
      transition: all 0.3s;
      text-align: center;
      font-size: 14px;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .member-chip:hover {
      border-color: var(--bni-red);
      background-color: #fff5f5;
    }

    .member-chip.sortable-ghost {
      opacity: 0.4;
    }

    .actions {
      margin-top: 30px;
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    .btn {
      padding: 12px 30px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background-color: var(--bni-red);
      color: var(--bni-white);
    }

    .btn-primary:hover {
      background-color: var(--bni-red-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-secondary {
      background-color: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
    }

    .message {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
      display: none;
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

    .info-box {
      background-color: #e7f3ff;
      border-left: 4px solid #2196F3;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }

    .info-box i {
      margin-right: 10px;
      color: #2196F3;
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
          <li><a href="bulk_input.php">一括入力</a></li>
          <li><a href="referrals.php">リファーラル管理</a></li>
          <li><a href="seating.php" class="active">座席表編集</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="seating-container">
        <h1 style="margin-bottom: 20px;">座席表編集</h1>

        <div class="info-box">
          <i class="fas fa-info-circle"></i>
          <strong>使い方:</strong> メンバープールから各テーブルにメンバーをドラッグ&ドロップしてください。テーブル内での順序も自由に変更できます。
        </div>

        <div id="message" class="message"></div>

        <!-- Member Pool -->
        <div class="member-pool">
          <h3><i class="fas fa-users"></i> メンバープール（未配置）</h3>
          <div id="memberPool" class="member-pool-list">
            <!-- Dynamically populated -->
          </div>
        </div>

        <!-- Seating Grid -->
        <div class="seating-grid">
          <!-- Table A-H -->
          <?php foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $table): ?>
            <div class="table-card">
              <div class="table-header">
                <h3><i class="fas fa-table"></i> テーブル <?php echo $table; ?></h3>
                <span class="seat-count">0/7</span>
              </div>
              <div id="table-<?php echo $table; ?>" class="seats-list" data-table="<?php echo $table; ?>">
                <!-- Dynamically populated -->
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Actions -->
        <div class="actions">
          <button type="button" class="btn btn-primary" onclick="saveSeatingChart()">
            <i class="fas fa-save"></i> 保存
          </button>
          <button type="button" class="btn btn-secondary" onclick="resetSeatingChart()">
            <i class="fas fa-undo"></i> リセット
          </button>
        </div>
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
    const csrfToken = '<?php echo $csrfToken; ?>';
    const members = <?php echo json_encode($members); ?>;
    let seatingData = null;

    // Load seating chart data
    async function loadSeatingChart() {
      try {
        const response = await fetch('../api_load_seating.php');
        const result = await response.json();

        if (result.success) {
          seatingData = result.data;
          renderSeatingChart();
        } else {
          showMessage('座席表データの読み込みに失敗しました', 'error');
        }
      } catch (error) {
        console.error('Error loading seating chart:', error);
        showMessage('座席表データの読み込みに失敗しました', 'error');
      }
    }

    // Render seating chart
    function renderSeatingChart() {
      // Clear all tables
      document.querySelectorAll('.seats-list').forEach(list => {
        list.innerHTML = '';
      });

      // Track assigned members
      const assignedMembers = new Set();

      // Populate tables
      Object.keys(seatingData.tables).forEach(tableName => {
        const table = seatingData.tables[tableName];
        const listEl = document.getElementById(`table-${tableName}`);

        table.positions.forEach((pos, index) => {
          if (pos.member_name) {
            const seatEl = createSeatElement(pos.member_name, pos.position, index);
            listEl.appendChild(seatEl);
            assignedMembers.add(pos.member_name);
          }
        });

        updateSeatCount(tableName);
      });

      // Populate member pool with unassigned members
      const memberPool = document.getElementById('memberPool');
      memberPool.innerHTML = '';

      members.forEach(member => {
        if (!assignedMembers.has(member.name)) {
          const chipEl = createMemberChip(member.name);
          memberPool.appendChild(chipEl);
        }
      });

      // Initialize Sortable for all lists
      initializeSortable();
    }

    // Create seat element
    function createSeatElement(memberName, position, index) {
      const div = document.createElement('div');
      div.className = 'seat-item';
      div.dataset.memberName = memberName;
      div.dataset.position = position;
      div.innerHTML = `
        <span class="seat-name">${memberName}</span>
        <span class="seat-position">${index + 1}番目</span>
      `;
      return div;
    }

    // Create member chip
    function createMemberChip(memberName) {
      const div = document.createElement('div');
      div.className = 'member-chip';
      div.dataset.memberName = memberName;
      div.textContent = memberName;
      return div;
    }

    // Initialize Sortable.js
    function initializeSortable() {
      // Member pool
      new Sortable(document.getElementById('memberPool'), {
        group: 'shared',
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onAdd: function (evt) {
          // Convert seat-item back to member-chip
          if (evt.item.classList.contains('seat-item')) {
            const memberName = evt.item.dataset.memberName;
            evt.item.remove();
            const chipEl = createMemberChip(memberName);
            document.getElementById('memberPool').appendChild(chipEl);
          }
        }
      });

      // Tables
      document.querySelectorAll('.seats-list').forEach(listEl => {
        new Sortable(listEl, {
          group: 'shared',
          animation: 150,
          ghostClass: 'sortable-ghost',
          dragClass: 'sortable-drag',
          onAdd: function (evt) {
            const tableName = evt.to.dataset.table;

            // Convert member-chip to seat-item
            if (evt.item.classList.contains('member-chip')) {
              const memberName = evt.item.dataset.memberName;
              evt.item.remove();
              const seatEl = createSeatElement(memberName, 'auto', evt.newIndex);
              evt.to.insertBefore(seatEl, evt.to.children[evt.newIndex]);
            }

            // Update seat positions
            updateSeatsInTable(tableName);
            updateSeatCount(tableName);
          },
          onUpdate: function (evt) {
            const tableName = evt.to.dataset.table;
            updateSeatsInTable(tableName);
          },
          onRemove: function (evt) {
            const tableName = evt.from.dataset.table;
            updateSeatCount(tableName);
          }
        });
      });
    }

    // Update seat positions in a table
    function updateSeatsInTable(tableName) {
      const listEl = document.getElementById(`table-${tableName}`);
      const seatItems = listEl.querySelectorAll('.seat-item');

      seatItems.forEach((item, index) => {
        const posLabel = item.querySelector('.seat-position');
        posLabel.textContent = `${index + 1}番目`;
      });
    }

    // Update seat count
    function updateSeatCount(tableName) {
      const listEl = document.getElementById(`table-${tableName}`);
      const count = listEl.querySelectorAll('.seat-item').length;
      const countEl = listEl.closest('.table-card').querySelector('.seat-count');
      countEl.textContent = `${count}/7`;
    }

    // Save seating chart
    async function saveSeatingChart() {
      const newSeatingData = {
        last_updated: new Date().toISOString(),
        tables: {},
        special_areas: seatingData.special_areas
      };

      // Collect data from each table
      document.querySelectorAll('.seats-list').forEach(listEl => {
        const tableName = listEl.dataset.table;
        const seatItems = listEl.querySelectorAll('.seat-item');
        const positions = [];

        seatItems.forEach((item, index) => {
          positions.push({
            position: `position-${index}`,
            member_name: item.dataset.memberName
          });
        });

        newSeatingData.tables[tableName] = { positions };
      });

      // Send to API
      try {
        const response = await fetch('../api_save_seating.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            csrf_token: csrfToken,
            seating_data: newSeatingData
          })
        });

        const result = await response.json();

        if (result.success) {
          showMessage('座席表を保存しました', 'success');
          seatingData = newSeatingData;
        } else {
          showMessage(result.message || '保存に失敗しました', 'error');
        }
      } catch (error) {
        console.error('Error saving seating chart:', error);
        showMessage('保存に失敗しました', 'error');
      }
    }

    // Reset seating chart
    function resetSeatingChart() {
      if (confirm('座席表をリセットしてよろしいですか？未保存の変更は失われます。')) {
        loadSeatingChart();
        showMessage('座席表をリセットしました', 'success');
      }
    }

    // Show message
    function showMessage(text, type) {
      const message = document.getElementById('message');
      message.textContent = text;
      message.className = 'message ' + type;
      message.style.display = 'block';

      if (type === 'success') {
        setTimeout(() => {
          message.style.display = 'none';
        }, 3000);
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      loadSeatingChart();
    });
  </script>
</body>
</html>
