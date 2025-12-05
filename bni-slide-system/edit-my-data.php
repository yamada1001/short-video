<?php
/**
 * BNI Slide System - Edit My Data Page
 * ユーザー自身のデータ編集画面
 */

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    http_response_code(403);
    die('<h1>アクセスエラー</h1><p>ユーザー情報が見つかりません。</p>');
}

$userName = htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');

// Get week parameter
$csvFile = $_GET['week'] ?? '';
if (empty($csvFile)) {
    die('<h1>エラー</h1><p>編集する週が指定されていません。</p>');
}

// Load data from CSV
$csvPath = __DIR__ . '/data/' . basename($csvFile) . '.csv';
if (!file_exists($csvPath)) {
    die('<h1>エラー</h1><p>データファイルが見つかりません。</p>');
}

// Read user's data from CSV
$userData = [];
if (($handle = fopen($csvPath, 'r')) !== false) {
    $header = fgetcsv($handle);
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) >= count($header)) {
            $rowData = array_combine($header, $row);
            if ($rowData['メールアドレス'] === $currentUser['email']) {
                $userData[] = $rowData;
            }
        }
    }
    fclose($handle);
}

if (empty($userData)) {
    die('<h1>エラー</h1><p>データが見つかりません。</p>');
}

// Extract data
$baseData = $userData[0];
$inputDate = htmlspecialchars($baseData['入力日'], ENT_QUOTES, 'UTF-8');
$attendance = htmlspecialchars($baseData['出席状況'], ENT_QUOTES, 'UTF-8');
$thanksSlips = intval($baseData['サンクスリップ数']);
$oneToOne = intval($baseData['ワンツーワン数']);
$activities = $baseData['アクティビティ'];
$comments = htmlspecialchars($baseData['コメント'], ENT_QUOTES, 'UTF-8');

// Extract visitors
$visitors = [];
foreach ($userData as $row) {
    if (!empty($row['ビジター名'])) {
        $visitorKey = $row['ビジター名'] . '|' . $row['ビジター会社名'];
        if (!isset($visitors[$visitorKey])) {
            $visitors[$visitorKey] = [
                'name' => $row['ビジター名'],
                'company' => $row['ビジター会社名'],
                'industry' => $row['ビジター業種']
            ];
        }
    }
}
$visitors = array_values($visitors);

// Extract referrals
$referrals = [];
foreach ($userData as $row) {
    if (!empty($row['案件名']) && $row['案件名'] !== '-') {
        $referralKey = $row['案件名'] . '|' . $row['リファーラル金額'];
        if (!isset($referrals[$referralKey])) {
            $referrals[$referralKey] = [
                'name' => $row['案件名'],
                'amount' => $row['リファーラル金額'],
                'provider' => $row['リファーラル提供者']
            ];
        }
    }
}
$referrals = array_values($referrals);
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
          <li><a href="index.php">アンケート</a></li>
          <li><a href="my-data.php" class="active">マイデータ</a></li>
          <li><a href="profile.php">プロフィール</a></li>
          <li><a href="logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
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
            <input type="hidden" name="csv_file" value="<?php echo htmlspecialchars($csvFile, ENT_QUOTES, 'UTF-8'); ?>">
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

            <!-- Section 2: リファーラル金額 -->
            <div class="form-section">
              <h2 class="form-section-title">2. リファーラル金額</h2>
              
              <div id="referralContainer">
                <?php foreach ($referrals as $index => $referral): ?>
                <div class="referral-item" data-index="<?php echo $index; ?>">
                  <div class="form-group">
                    <label class="form-label">案件名</label>
                    <input type="text" name="referral_name[]" class="form-input" value="<?php echo htmlspecialchars($referral['name'], ENT_QUOTES, 'UTF-8'); ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">金額（円）</label>
                    <input type="text" name="referral_amount[]" class="form-input amount-input" value="<?php echo number_format($referral['amount']); ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">提供者</label>
                    <input type="text" name="referral_provider[]" class="form-input" value="<?php echo htmlspecialchars($referral['provider'], ENT_QUOTES, 'UTF-8'); ?>">
                  </div>
                  <button type="button" class="btn-remove" onclick="removeReferral(this)">削除</button>
                </div>
                <?php endforeach; ?>
              </div>

              <button type="button" class="btn-add" onclick="addReferral()">+ リファーラル追加</button>
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
    let referralIndex = <?php echo count($referrals); ?>;

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

    function addReferral() {
      const container = document.getElementById('referralContainer');
      const html = `
        <div class="referral-item" data-index="${referralIndex}">
          <div class="form-group">
            <label class="form-label">案件名</label>
            <input type="text" name="referral_name[]" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label">金額（円）</label>
            <input type="text" name="referral_amount[]" class="form-input amount-input">
          </div>
          <div class="form-group">
            <label class="form-label">提供者</label>
            <input type="text" name="referral_provider[]" class="form-input">
          </div>
          <button type="button" class="btn-remove" onclick="removeReferral(this)">削除</button>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);

      // Add comma formatting to new amount input
      const newInputs = container.querySelectorAll('.amount-input');
      const newInput = newInputs[newInputs.length - 1];
      addCommaFormatting(newInput);

      referralIndex++;
    }

    function removeReferral(btn) {
      btn.closest('.referral-item').remove();
    }

    // Add comma formatting to amount inputs
    function addCommaFormatting(input) {
      input.addEventListener('input', function(e) {
        // Remove non-digit characters except for existing value
        let value = e.target.value.replace(/,/g, '');

        // Only allow digits
        value = value.replace(/\D/g, '');

        // Add comma formatting
        if (value) {
          e.target.value = parseInt(value).toLocaleString('ja-JP');
        } else {
          e.target.value = '';
        }
      });
    }

    // Initialize comma formatting for existing amount inputs
    document.addEventListener('DOMContentLoaded', function() {
      const amountInputs = document.querySelectorAll('.amount-input');
      amountInputs.forEach(input => {
        addCommaFormatting(input);
      });
    });

    // Form submission
    document.getElementById('editForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      // Remove commas from amount inputs before submission
      const amountInputs = this.querySelectorAll('.amount-input');
      amountInputs.forEach(input => {
        input.value = input.value.replace(/,/g, '');
      });

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
          // Re-add commas to amount inputs
          amountInputs.forEach(input => {
            if (input.value) {
              input.value = parseInt(input.value).toLocaleString('ja-JP');
            }
          });
          submitBtn.disabled = false;
          submitBtn.textContent = '更新する';
        }
      } catch (error) {
        console.error('Update error:', error);
        showMessage('error', 'エラーが発生しました。');
        // Re-add commas to amount inputs
        amountInputs.forEach(input => {
          if (input.value) {
            input.value = parseInt(input.value).toLocaleString('ja-JP');
          }
        });
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
</body>
</html>
