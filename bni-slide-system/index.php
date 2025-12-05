<?php
/**
 * BNI Slide System - Weekly Survey Form
 * ログインユーザー情報を自動入力
 */

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info from Basic Auth
$currentUser = getCurrentUserInfo();

// If user not found, show error
if (!$currentUser) {
    http_response_code(403);
    die('<h1>アクセスエラー</h1><p>ユーザー情報が見つかりません。管理者にお問い合わせください。</p>');
}

$userName = htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>BNI週次アンケート | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">

  <style>
    /* Select2 custom styling */
    .select2-container--default .select2-selection--single {
      height: 48px;
      border: 2px solid #DDDDDD;
      border-radius: 4px;
      padding: 8px 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 30px;
      font-size: 16px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 46px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
      border-color: #CF2030;
    }
    .select2-dropdown {
      border: 2px solid #CF2030;
    }
    .select2-container {
      width: 100% !important;
    }

    /* Referral item styling */
    .referral-item {
      background: #F9F9F9;
      border: 2px solid #E0E0E0;
      border-radius: 8px;
      padding: 24px;
      margin-bottom: 20px;
      position: relative;
    }

    .referral-item-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .referral-item-header h3 {
      margin: 0;
      font-size: 18px;
      font-weight: 600;
      color: #CF2030;
    }

    .btn-remove-referral {
      background: #DC3545;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .btn-remove-referral:hover {
      background: #C82333;
    }

    .btn-add-referral {
      background: #CF2030;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-add-referral:hover {
      background: #A01828;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <div class="site-logo">BNI Slide System</div>
      <nav class="site-nav">
        <ul>
          <li><a href="index.php" class="active">アンケート</a></li>
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
            <h1>BNI週次アンケート</h1>
            <p>毎週のビジター紹介・リファーラル情報をご入力ください</p>
          </div>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Survey Form -->
          <form id="surveyForm" method="POST" action="api_save.php">

            <!-- Section 0: 基本情報 -->
            <div class="form-section">
              <h2 class="form-section-title">基本情報</h2>

              <div class="form-group">
                <label class="form-label">
                  入力日（会議実施日）<span class="required">*</span>
                </label>
                <input type="date" name="input_date" class="form-input" required id="inputDate" readonly>
                <span class="form-error">入力日を選択してください</span>
                <p class="form-hint">この日付でデータが週ごとに管理されます（自動設定）</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  あなたの名前<span class="required">*</span>
                </label>
                <input type="text" name="introducer_name" class="form-input" value="<?php echo $userName; ?>" readonly required style="background-color: #F5F5F5; cursor: not-allowed;">
                <span class="form-help">ログイン情報から自動設定されています</span>
              </div>

              <div class="form-group">
                <label class="form-label">
                  メールアドレス<span class="required">*</span>
                </label>
                <input type="email" name="email" class="form-input" value="<?php echo $userEmail; ?>" readonly required style="background-color: #F5F5F5; cursor: not-allowed;">
                <span class="form-help">ログイン情報から自動設定されています（サンクスメール送信先）</span>
              </div>
            </div>

            <!-- Section 1: ビジター紹介情報（任意） -->
            <div class="form-section">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="form-section-title" style="margin-bottom: 0;">1. ビジター紹介情報（任意）</h2>
                <button type="button" class="btn-add-referral" id="addVisitorBtn">
                  <span>+ ビジター追加</span>
                </button>
              </div>

              <div id="visitorContainer">
                <!-- ビジター項目1 -->
                <div class="referral-item" data-index="0">
                  <div class="referral-item-header">
                    <h3>ビジター #1</h3>
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      ビジター名
                    </label>
                    <input type="text" name="visitor_name[]" class="form-input" placeholder="紹介がある場合のみ入力">
                    <span class="form-help">紹介したビジターの氏名を入力してください</span>
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      会社名（屋号）
                    </label>
                    <input type="text" name="visitor_company[]" class="form-input" placeholder="例: 株式会社〇〇">
                    <span class="form-help">ビジターの会社名または屋号を入力してください</span>
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      ビジターの業種・職種
                    </label>
                    <input type="text" name="visitor_industry[]" class="form-input" placeholder="例: 不動産仲介業">
                  </div>
                </div>
              </div>
            </div>

            <!-- Section 2: リファーラル金額情報 -->
            <div class="form-section">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="form-section-title" style="margin-bottom: 0;">2. リファーラル金額情報</h2>
                <button type="button" class="btn-add-referral" id="addReferralBtn">
                  <span>+ リファーラル追加</span>
                </button>
              </div>

              <div id="referralContainer">
                <!-- リファーラル項目1 -->
                <div class="referral-item" data-index="0">
                  <div class="referral-item-header">
                    <h3>リファーラル #1</h3>
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      案件名・内容
                    </label>
                    <input type="text" name="referral_name[]" class="form-input" placeholder="例: ○○社のWebサイト制作案件">
                    <span class="form-help">リファーラルがある場合のみ入力してください</span>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">
                        リファーラル金額（円）
                      </label>
                      <input type="text" name="referral_amount_display[]" class="form-input referral-amount-display" placeholder="例: 500,000">
                      <input type="hidden" name="referral_amount[]" class="referral-amount-hidden">
                      <span class="form-help">カンマは自動で挿入されます</span>
                    </div>

                    <div class="form-group">
                      <label class="form-label">
                        カテゴリ
                      </label>
                      <select name="referral_category[]" class="form-select">
                        <option value="">選択してください</option>
                        <option value="成約">成約</option>
                        <option value="商談中">商談中</option>
                        <option value="見込み">見込み</option>
                        <option value="その他">その他</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      リファーラル提供者
                    </label>
                    <select name="referral_provider[]" class="form-select referral-provider-select">
                      <option value="">選択してください（任意）</option>
                      <!-- メンバーリストは JavaScript で動的に読み込み -->
                    </select>
                    <span class="form-help">あなたにリファーラルを提供してくれたメンバー名</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Section 3: メンバー情報 -->
            <div class="form-section">
              <h2 class="form-section-title">3. メンバー情報</h2>

              <div class="form-group">
                <label class="form-label">
                  今週の出席状況<span class="required">*</span>
                </label>
                <div class="form-radio-group">
                  <div class="form-radio">
                    <input type="radio" id="attendance_yes" name="attendance" value="出席" required>
                    <label for="attendance_yes">出席</label>
                  </div>
                  <div class="form-radio">
                    <input type="radio" id="attendance_substitute" name="attendance" value="代理出席">
                    <label for="attendance_substitute">代理出席</label>
                  </div>
                  <div class="form-radio">
                    <input type="radio" id="attendance_absent" name="attendance" value="欠席">
                    <label for="attendance_absent">欠席</label>
                  </div>
                </div>
                <span class="form-error">出席状況を選択してください</span>
              </div>

              <div class="form-group">
                <label class="form-label">
                  サンクスリップ提出数
                </label>
                <input type="number" name="thanks_slips" class="form-input" min="0" value="0">
              </div>

              <div class="form-group">
                <label class="form-label">
                  ワンツーワン実施数（今週）
                </label>
                <input type="number" name="one_to_one_count" class="form-input" min="0" value="0">
              </div>

              <div class="form-group">
                <label class="form-label">
                  今週のアクティビティ
                </label>
                <div class="form-checkbox-group">
                  <div class="form-checkbox">
                    <input type="checkbox" id="activity_networking" name="activities[]" value="ネットワーキング">
                    <label for="activity_networking">ネットワーキング</label>
                  </div>
                  <div class="form-checkbox">
                    <input type="checkbox" id="activity_education" name="activities[]" value="教育セッション参加">
                    <label for="activity_education">教育セッション参加</label>
                  </div>
                  <div class="form-checkbox">
                    <input type="checkbox" id="activity_presentation" name="activities[]" value="プレゼンテーション実施">
                    <label for="activity_presentation">プレゼンテーション実施</label>
                  </div>
                  <div class="form-checkbox">
                    <input type="checkbox" id="activity_event" name="activities[]" value="イベント参加">
                    <label for="activity_event">イベント参加</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  その他コメント・特記事項
                </label>
                <textarea name="comments" class="form-textarea" placeholder="今週の活動や気づきなど、自由にご記入ください"></textarea>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="form-submit">
              <button type="submit" class="btn btn-primary">送信する</button>
            </div>
          </form>
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

  <!-- Scripts -->
  <!-- jQuery (required for Select2) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    // ビジター追加機能 & リファーラル追加機能
    $(document).ready(function() {
      let visitorIndex = 1;
      let referralIndex = 1;

      // 入力日に今日の日付をデフォルト設定
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0');
      const day = String(today.getDate()).padStart(2, '0');
      const todayString = `${year}-${month}-${day}`;
      document.getElementById('inputDate').value = todayString;

      // ビジター追加ボタン
      $('#addVisitorBtn').on('click', function() {
        visitorIndex++;

        const newVisitorItem = `
          <div class="referral-item" data-index="${visitorIndex}">
            <div class="referral-item-header">
              <h3>ビジター #${visitorIndex}</h3>
              <button type="button" class="btn-remove-referral" onclick="removeVisitor(this)">
                <span>削除</span>
              </button>
            </div>

            <div class="form-group">
              <label class="form-label">
                ビジター名
              </label>
              <input type="text" name="visitor_name[]" class="form-input" placeholder="紹介がある場合のみ入力">
              <span class="form-help">紹介したビジターの氏名を入力してください</span>
            </div>

            <div class="form-group">
              <label class="form-label">
                会社名（屋号）
              </label>
              <input type="text" name="visitor_company[]" class="form-input" placeholder="例: 株式会社〇〇">
              <span class="form-help">ビジターの会社名または屋号を入力してください</span>
            </div>

            <div class="form-group">
              <label class="form-label">
                ビジターの業種・職種
              </label>
              <input type="text" name="visitor_industry[]" class="form-input" placeholder="例: 不動産仲介業">
            </div>
          </div>
        `;

        $('#visitorContainer').append(newVisitorItem);
      });

      // リファーラル追加ボタン
      $('#addReferralBtn').on('click', function() {
        referralIndex++;

        const newReferralItem = `
          <div class="referral-item" data-index="${referralIndex}">
            <div class="referral-item-header">
              <h3>リファーラル #${referralIndex}</h3>
              <button type="button" class="btn-remove-referral" onclick="removeReferral(this)">
                <span>削除</span>
              </button>
            </div>

            <div class="form-group">
              <label class="form-label">
                案件名・内容
              </label>
              <input type="text" name="referral_name[]" class="form-input" placeholder="例: ○○社のWebサイト制作案件">
              <span class="form-help">リファーラルがある場合のみ入力してください</span>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">
                  リファーラル金額（円）
                </label>
                <input type="text" name="referral_amount_display[]" class="form-input referral-amount-display" placeholder="例: 500,000">
                <input type="hidden" name="referral_amount[]" class="referral-amount-hidden">
                <span class="form-help">カンマは自動で挿入されます</span>
              </div>

              <div class="form-group">
                <label class="form-label">
                  カテゴリ
                </label>
                <select name="referral_category[]" class="form-select">
                  <option value="">選択してください</option>
                  <option value="成約">成約</option>
                  <option value="商談中">商談中</option>
                  <option value="見込み">見込み</option>
                  <option value="その他">その他</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">
                リファーラル提供者
              </label>
              <select name="referral_provider[]" class="form-select referral-provider-select">
                <option value="">選択してください（任意）</option>
              </select>
              <span class="form-help">あなたにリファーラルを提供してくれたメンバー名</span>
            </div>
          </div>
        `;

        $('#referralContainer').append(newReferralItem);

        // 新しく追加した項目にもメンバーリストを適用
        const lastSelect = $('.referral-provider-select').last();
        if (window.membersList) {
          window.membersList.forEach(function(member) {
            lastSelect.append(new Option(member, member));
          });
        }
      });
    });

    // ビジター削除機能
    function removeVisitor(button) {
      const visitorItem = $(button).closest('.referral-item');
      visitorItem.remove();

      // 番号を振り直し
      $('#visitorContainer .referral-item').each(function(index) {
        $(this).find('.referral-item-header h3').text('ビジター #' + (index + 1));
      });
    }

    // リファーラル削除機能
    function removeReferral(button) {
      const referralItem = $(button).closest('.referral-item');
      referralItem.remove();

      // 番号を振り直し
      $('#referralContainer .referral-item').each(function(index) {
        $(this).find('.referral-item-header h3').text('リファーラル #' + (index + 1));
      });
    }
  </script>

  <script src="assets/js/form.js"></script>
</body>
</html>
