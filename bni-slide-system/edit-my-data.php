<?php
/**
 * BNI Slide System - Edit My Data Page
 * ユーザー自身のデータ編集画面
 */

// Load dependencies
require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/db.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    http_response_code(403);
    die('<h1>アクセスエラー</h1><p>ユーザー情報が見つかりません。</p>');
}

$userName = htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');
$userRole = $currentUser['role'] ?? 'member'; // デフォルトはmember

// Get week parameter
$weekDate = $_GET['week'] ?? '';
if (empty($weekDate)) {
    die('<h1>エラー</h1><p>編集する週が指定されていません。</p>');
}

// Load data from SQLite database
try {
    $db = getDbConnection();

    // Get survey data for this week and user
    $query = "
        SELECT
            s.id,
            s.week_date,
            s.timestamp,
            s.input_date,
            s.user_name,
            s.user_email,
            s.attendance,
            s.thanks_slips,
            s.one_to_one,
            s.activities,
            s.comments,
            s.is_pitch_presenter,
            s.pitch_file_path,
            s.pitch_file_original_name,
            s.pitch_file_type,
            s.youtube_url
        FROM survey_data s
        WHERE s.week_date = :week_date
        AND s.user_email = :email
        LIMIT 1
    ";

    $surveyData = dbQueryOne($db, $query, [
        ':week_date' => $weekDate,
        ':email' => $currentUser['email']
    ]);

    if (!$surveyData) {
        dbClose($db);
        die('<h1>エラー</h1><p>データが見つかりません。</p>');
    }

    $surveyDataId = $surveyData['id'];

    // Extract base data
    $inputDate = htmlspecialchars($surveyData['input_date'], ENT_QUOTES, 'UTF-8');
    $attendance = htmlspecialchars($surveyData['attendance'], ENT_QUOTES, 'UTF-8');
    $thanksSlips = intval($surveyData['thanks_slips']);
    $oneToOne = intval($surveyData['one_to_one']);
    $activities = $surveyData['activities'];
    $comments = htmlspecialchars($surveyData['comments'], ENT_QUOTES, 'UTF-8');

    // Extract pitch presenter data
    $isPitchPresenter = intval($surveyData['is_pitch_presenter']);
    $pitchFileOriginalName = $surveyData['pitch_file_original_name'];
    $youtubeUrl = $surveyData['youtube_url'];

    // Get visitors
    $visitorsQuery = "
        SELECT visitor_name, visitor_company, visitor_industry
        FROM visitors
        WHERE survey_data_id = :survey_data_id
        ORDER BY id
    ";
    $visitorsData = dbQuery($db, $visitorsQuery, [':survey_data_id' => $surveyDataId]);

    $visitors = [];
    foreach ($visitorsData as $row) {
        $visitors[] = [
            'name' => $row['visitor_name'],
            'company' => $row['visitor_company'],
            'industry' => $row['visitor_industry']
        ];
    }

    dbClose($db);

} catch (Exception $e) {
    if (isset($db)) {
        dbClose($db);
    }
    error_log('[EDIT MY DATA] Error: ' . $e->getMessage());
    die('<h1>エラー</h1><p>データの読み込み中にエラーが発生しました。</p>');
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
  <title>データ編集 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">
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
          <li><a href="dashboard.php">ダッシュボード</a></li>
          <li><a href="index.php">アンケート</a></li>
          <li><a href="my-data.php" class="active">マイデータ</a></li>
          <li><a href="manual.php">マニュアル</a></li>
        </ul>
      </nav>
      <div class="user-menu">
        <button class="hamburger-btn" id="hamburgerBtn">
          <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <span>メニュー</span>
        </button>
        <div class="dropdown-menu" id="dropdownMenu">
          <ul>
            <?php if ($userRole === 'admin'): ?>
            <li><a href="admin/slide.php" style="color: #FFD700;">📊 スライド</a></li>
            <li><div class="divider"></div></li>
            <?php endif; ?>
            <li><a href="profile.php">👤 プロフィール</a></li>
            <li><div class="divider"></div></li>
            <li><a href="logout.php" style="color: #CF2030;">🚪 ログアウト</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="form-container">
        <div class="card">
          <div class="form-header">
            <h1><i class="fas fa-edit"></i> データ編集</h1>
            <p>入力日: <?php echo $inputDate; ?></p>
          </div>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Edit Form -->
          <form id="editForm">
            <input type="hidden" name="week_date" value="<?php echo htmlspecialchars($weekDate, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="input_date" value="<?php echo $inputDate; ?>">

            <!-- Section 1: ビジター紹介 -->
            <div class="form-section">
              <h2 class="form-section-title">1. ビジター紹介</h2>
              
              <div id="visitorContainer">
                <?php foreach ($visitors as $index => $visitor): ?>
                <div class="visitor-item" data-index="<?php echo $index; ?>">
                  <div class="form-group">
                    <label class="form-label">ビジター名</label>
                    <input type="text" name="visitor_name[]" class="form-input" value="<?php echo htmlspecialchars($visitor['name'], ENT_QUOTES, 'UTF-8'); ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">会社名</label>
                    <input type="text" name="visitor_company[]" class="form-input" value="<?php echo htmlspecialchars($visitor['company'], ENT_QUOTES, 'UTF-8'); ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">業種</label>
                    <input type="text" name="visitor_industry[]" class="form-input" value="<?php echo htmlspecialchars($visitor['industry'], ENT_QUOTES, 'UTF-8'); ?>">
                  </div>
                  <button type="button" class="btn-remove" onclick="removeVisitor(this)">削除</button>
                </div>
                <?php endforeach; ?>
              </div>

              <button type="button" class="btn-add" onclick="addVisitor()">+ ビジター追加</button>
            </div>

            <!-- Section 2: ピッチプレゼンター情報 -->
            <div class="form-section">
              <h2 class="form-section-title">2. メインプレゼンテーション</h2>

              <div class="form-group">
                <label class="form-label">
                  <input type="checkbox" name="is_pitch_presenter" id="pitchPresenterCheckbox" value="1" <?php echo ($isPitchPresenter == 1) ? 'checked' : ''; ?>>
                  次の会でピッチを担当する
                </label>
              </div>

              <div id="pitchFileSection" style="display: <?php echo ($isPitchPresenter == 1) ? 'block' : 'none'; ?>;">
                <?php if (!empty($pitchFileOriginalName)): ?>
                <div style="margin-bottom: 15px; padding: 10px; background: #F0F8FF; border: 1px solid #B0D4FF; border-radius: 4px;">
                  <strong>現在のファイル:</strong> <?php echo htmlspecialchars($pitchFileOriginalName, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <?php endif; ?>

                <div class="form-group">
                  <label class="form-label">ピッチ資料（PDF）</label>
                  <input type="file" name="pitch_file" id="pitch_file" accept=".pdf" class="form-input">
                  <span class="form-help">
                    対応形式: PDF (.pdf)<br>
                    最大ファイルサイズ: 30MB<br>
                    <?php if (!empty($pitchFileOriginalName)): ?>
                    <strong style="color: #CF2030;">※ファイルを選択すると既存のファイルが置き換えられます</strong>
                    <?php endif; ?>
                  </span>
                </div>

                <div class="form-group">
                  <label class="form-label">YouTube動画URL（オプション）</label>
                  <input type="url" name="youtube_url" id="youtube_url" class="form-input" value="<?php echo htmlspecialchars($youtubeUrl ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://www.youtube.com/watch?v=...">
                  <span class="form-help">
                    ピッチで使用するYouTube動画のURLを入力してください。<br>
                    <strong>例:</strong> https://www.youtube.com/watch?v=xxxxx または https://youtu.be/xxxxx<br>
                    動画はスライドに自動埋め込みされます。
                  </span>
                </div>
              </div>
            </div>

            <!-- Section 3: メンバー情報 -->
            <div class="form-section">
              <h2 class="form-section-title">3. メンバー情報</h2>

              <div class="form-group">
                <label class="form-label">出席状況</label>
                <div class="form-radio-group">
                  <div class="form-radio">
                    <input type="radio" id="attendance_yes" name="attendance" value="出席" <?php echo ($attendance === '出席') ? 'checked' : ''; ?> required>
                    <label for="attendance_yes">出席</label>
                  </div>
                  <div class="form-radio">
                    <input type="radio" id="attendance_substitute" name="attendance" value="代理出席" <?php echo ($attendance === '代理出席') ? 'checked' : ''; ?> required>
                    <label for="attendance_substitute">代理出席</label>
                  </div>
                  <div class="form-radio">
                    <input type="radio" id="attendance_absent" name="attendance" value="欠席" <?php echo ($attendance === '欠席') ? 'checked' : ''; ?> required>
                    <label for="attendance_absent">欠席</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">サンクスリップ提出数</label>
                <input type="number" name="thanks_slips" class="form-input" value="<?php echo $thanksSlips; ?>" min="0">
              </div>

              <div class="form-group">
                <label class="form-label">ワンツーワン実施数</label>
                <input type="number" name="one_to_one_count" class="form-input" value="<?php echo $oneToOne; ?>" min="0">
              </div>

              <div class="form-group">
                <label class="form-label">コメント</label>
                <textarea name="comments" class="form-input" rows="4"><?php echo $comments; ?></textarea>
              </div>
            </div>

            <!-- Submit Buttons -->
            <div class="form-submit">
              <button type="submit" class="btn btn-primary">更新する</button>
              <a href="my-data.php" class="btn btn-outline" style="margin-left: 10px;">キャンセル</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 BNI Slide System. All rights reserved.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    let visitorIndex = <?php echo count($visitors); ?>;

    function addVisitor() {
      const container = document.getElementById('visitorContainer');
      const html = `
        <div class="visitor-item" data-index="${visitorIndex}">
          <div class="form-group">
            <label class="form-label">ビジター名</label>
            <input type="text" name="visitor_name[]" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label">会社名</label>
            <input type="text" name="visitor_company[]" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label">業種</label>
            <input type="text" name="visitor_industry[]" class="form-input">
          </div>
          <button type="button" class="btn-remove" onclick="removeVisitor(this)">削除</button>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
      visitorIndex++;
    }

    function removeVisitor(btn) {
      btn.closest('.visitor-item').remove();
    }

    // Toggle pitch file section
    document.addEventListener('DOMContentLoaded', function() {
      const pitchCheckbox = document.getElementById('pitchPresenterCheckbox');
      const pitchFileSection = document.getElementById('pitchFileSection');

      if (pitchCheckbox) {
        pitchCheckbox.addEventListener('change', function() {
          pitchFileSection.style.display = this.checked ? 'block' : 'none';
        });
      }
    });

    // Form submission
    document.getElementById('editForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');

      submitBtn.disabled = true;
      submitBtn.textContent = '更新中...';

      try {
        const response = await fetch('api_update_my_data.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          showMessage('success', result.message || 'データを更新しました！');
          setTimeout(() => {
            window.location.href = 'my-data.php';
          }, 2000);
        } else {
          showMessage('error', result.message || '更新に失敗しました。');
          submitBtn.disabled = false;
          submitBtn.textContent = '更新する';
        }
      } catch (error) {
        console.error('Update error:', error);
        showMessage('error', 'エラーが発生しました。');
        submitBtn.disabled = false;
        submitBtn.textContent = '更新する';
      }
    });

    function showMessage(type, text) {
      const messageDiv = document.getElementById('message');
      messageDiv.className = `message message-${type} show`;
      messageDiv.textContent = text;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>

  <script>
// Hamburger menu toggle - Modern Animation
(function() {
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const dropdownMenu = document.getElementById('dropdownMenu');

  if (hamburgerBtn && dropdownMenu) {
    hamburgerBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = dropdownMenu.classList.toggle('show');
      hamburgerBtn.classList.toggle('active', isOpen);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!dropdownMenu.contains(e.target) && !hamburgerBtn.contains(e.target)) {
        dropdownMenu.classList.remove('show');
        hamburgerBtn.classList.remove('active');
      }
    });

    // Close dropdown when clicking a link
    dropdownMenu.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        dropdownMenu.classList.remove('show');
        hamburgerBtn.classList.remove('active');
      });
    });

    // Close dropdown on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
        dropdownMenu.classList.remove('show');
        hamburgerBtn.classList.remove('active');
      }
    });
  }
})();
  </script>
</body>
</html>
