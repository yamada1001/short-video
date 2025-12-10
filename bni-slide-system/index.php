<?php
/**
 * BNI Slide System - Weekly Survey Form
 * ログインユーザー情報を自動入力
 */

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/csrf.php';

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Get current user info from Basic Auth
$currentUser = getCurrentUserInfo();

// If user not found, show error
if (!$currentUser) {
    http_response_code(403);
    die('<h1>アクセスエラー</h1><p>ユーザー情報が見つかりません。管理者にお問い合わせください。</p>');
}

$userName = htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');
$userRole = $currentUser['role'] ?? 'member'; // デフォルトはmember
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
          <li><a href="index.php" class="active">アンケート</a></li>
          <li><a href="my-data.php">マイデータ</a></li>
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
            <h1>BNI週次アンケート</h1>
            <p>毎週のビジター紹介・リファーラル情報をご入力ください</p>
          </div>

          <!-- Survey Form -->
          <form id="surveyForm" method="POST" action="api_save.php" enctype="multipart/form-data">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

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
                    <input type="radio" id="attendance_substitute" name="attendance" value="代理出席" required>
                    <label for="attendance_substitute">代理出席</label>
                  </div>
                  <div class="form-radio">
                    <input type="radio" id="attendance_absent" name="attendance" value="欠席" required>
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

            <!-- Section 4: ピッチ担当者情報 -->
            <div class="form-section">
              <h2 class="form-section-title">4. ピッチ担当者情報</h2>

              <div class="form-group">
                <label class="form-label">
                  次の会でピッチを担当する方ですか？<span class="required">*</span>
                </label>
                <div class="form-radio-group">
                  <div class="form-radio">
                    <input type="radio" id="pitch_yes" name="is_pitch_presenter" value="1" required>
                    <label for="pitch_yes">はい（ピッチ資料をアップロードします）</label>
                  </div>
                  <div class="form-radio">
                    <input type="radio" id="pitch_no" name="is_pitch_presenter" value="0" required checked>
                    <label for="pitch_no">いいえ</label>
                  </div>
                </div>
                <span class="form-error">ピッチ担当の可否を選択してください</span>
              </div>

              <!-- ファイルアップロード欄（ピッチ担当者の場合のみ表示） -->
              <div id="pitchFileUploadSection" style="display: none;">
                <div class="form-group">
                  <label class="form-label">
                    ピッチ資料をアップロード<span class="required">*</span>
                  </label>
                  <input type="file" name="pitch_file" id="pitch_file" class="form-input" accept=".pdf,.pptx,.ppt">
                  <span class="form-help">
                    対応形式: PDF (.pdf) または PowerPoint (.pptx, .ppt)<br>
                    最大ファイルサイズ: 30MB<br>
                    <strong>推奨:</strong> PDF形式でアップロードすると、スライドに直接埋め込み表示されます。<br>
                    PowerPoint形式の場合は、ダウンロードリンクのみ表示されます。
                  </span>
                  <div id="filePreview" style="margin-top: 10px; padding: 10px; background: #F0F8FF; border: 1px solid #B0D4FF; border-radius: 4px; display: none;">
                    <p style="margin: 0; font-size: 14px; color: #333;">
                      <strong>選択されたファイル:</strong> <span id="fileName"></span> (<span id="fileSize"></span>)
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Success/Error Messages -->
            <div id="message" class="message"></div>

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

  <!-- Auto-Save Feature -->
  <script>
    (function() {
      'use strict';

      const AUTOSAVE_KEY = 'bni_survey_autosave_' + '<?php echo $userEmail; ?>';
      const AUTOSAVE_INTERVAL = 3000; // 3秒ごとに保存
      let autosaveTimeout = null;
      let isSubmitting = false;

      // 下書きバナー要素を作成
      function createDraftBanner() {
        const banner = document.createElement('div');
        banner.id = 'draftBanner';
        banner.style.cssText = `
          position: fixed;
          top: 60px;
          left: 50%;
          transform: translateX(-50%);
          background: #FFF3CD;
          color: #856404;
          padding: 12px 24px;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          z-index: 1000;
          display: none;
          font-size: 14px;
          font-weight: 600;
        `;
        banner.innerHTML = `
          <span style="margin-right: 15px;">📝 下書きが保存されています</span>
          <button id="restoreDraftBtn" style="
            background: #856404;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 8px;
            font-size: 13px;
          ">復元する</button>
          <button id="discardDraftBtn" style="
            background: #DC3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
          ">削除する</button>
        `;
        document.body.appendChild(banner);
        return banner;
      }

      // フォームデータを収集
      function collectFormData() {
        const form = document.getElementById('surveyForm');
        const formData = {};

        // テキスト入力
        form.querySelectorAll('input[type="text"], input[type="number"], textarea, input[type="date"]').forEach(input => {
          if (!input.readOnly && input.name) {
            if (input.name.includes('[]')) {
              if (!formData[input.name]) formData[input.name] = [];
              formData[input.name].push(input.value);
            } else {
              formData[input.name] = input.value;
            }
          }
        });

        // セレクトボックス
        form.querySelectorAll('select').forEach(select => {
          if (select.name) {
            if (select.name.includes('[]')) {
              if (!formData[select.name]) formData[select.name] = [];
              formData[select.name].push(select.value);
            } else {
              formData[select.name] = select.value;
            }
          }
        });

        // ラジオボタン
        form.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
          formData[radio.name] = radio.value;
        });

        // チェックボックス
        const activities = [];
        form.querySelectorAll('input[name="activities[]"]:checked').forEach(checkbox => {
          activities.push(checkbox.value);
        });
        if (activities.length > 0) {
          formData['activities[]'] = activities;
        }

        // ビジターとリファーラルの数を保存
        formData._visitorCount = document.querySelectorAll('#visitorContainer .referral-item').length;
        formData._referralCount = document.querySelectorAll('#referralContainer .referral-item').length;

        return formData;
      }

      // フォームデータを復元
      function restoreFormData(data) {
        const form = document.getElementById('surveyForm');

        // ビジターとリファーラルの項目を追加
        if (data._visitorCount > 1) {
          for (let i = 1; i < data._visitorCount; i++) {
            $('#addVisitorBtn').click();
          }
        }
        if (data._referralCount > 1) {
          for (let i = 1; i < data._referralCount; i++) {
            $('#addReferralBtn').click();
          }
        }

        // 少し待ってから値を復元（動的要素の生成を待つ）
        setTimeout(function() {
          // テキスト入力とテキストエリア
          Object.keys(data).forEach(key => {
            if (key.startsWith('_')) return; // メタデータはスキップ

            if (Array.isArray(data[key])) {
              const inputs = form.querySelectorAll(`[name="${key}"]`);
              data[key].forEach((value, index) => {
                if (inputs[index]) inputs[index].value = value;
              });
            } else if (key === 'activities[]') {
              // チェックボックス
              data[key].forEach(value => {
                const checkbox = form.querySelector(`input[name="activities[]"][value="${value}"]`);
                if (checkbox) checkbox.checked = true;
              });
            } else {
              // ラジオボタン
              const radio = form.querySelector(`input[name="${key}"][value="${data[key]}"]`);
              if (radio) {
                radio.checked = true;
              } else {
                // 通常の入力
                const input = form.querySelector(`[name="${key}"]`);
                if (input && !input.readOnly) input.value = data[key];
              }
            }
          });

          console.log('✅ 下書きデータを復元しました');
        }, 300);
      }

      // LocalStorageに保存
      function saveToLocalStorage() {
        if (isSubmitting) return;

        const formData = collectFormData();
        const saveData = {
          data: formData,
          timestamp: new Date().toISOString()
        };

        try {
          localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(saveData));
          console.log('💾 自動保存完了:', new Date().toLocaleTimeString());
        } catch (e) {
          console.warn('自動保存に失敗しました:', e);
        }
      }

      // 自動保存をトリガー（debounce処理）
      function triggerAutosave() {
        if (autosaveTimeout) {
          clearTimeout(autosaveTimeout);
        }
        autosaveTimeout = setTimeout(saveToLocalStorage, AUTOSAVE_INTERVAL);
      }

      // 下書きデータの確認と復元
      function checkAndRestoreDraft() {
        const savedData = localStorage.getItem(AUTOSAVE_KEY);
        if (!savedData) return;

        try {
          const { data, timestamp } = JSON.parse(savedData);
          const savedDate = new Date(timestamp);
          const now = new Date();
          const hoursDiff = (now - savedDate) / (1000 * 60 * 60);

          // 24時間以上古い下書きは削除
          if (hoursDiff > 24) {
            localStorage.removeItem(AUTOSAVE_KEY);
            return;
          }

          // 下書きバナーを表示
          const banner = createDraftBanner();
          banner.style.display = 'block';

          const savedTime = savedDate.toLocaleString('ja-JP');
          banner.querySelector('span').textContent = `📝 下書きが保存されています（${savedTime}）`;

          // 復元ボタン
          document.getElementById('restoreDraftBtn').addEventListener('click', function() {
            restoreFormData(data);
            banner.style.display = 'none';
          });

          // 削除ボタン
          document.getElementById('discardDraftBtn').addEventListener('click', function() {
            localStorage.removeItem(AUTOSAVE_KEY);
            banner.style.display = 'none';
            console.log('🗑️ 下書きを削除しました');
          });

        } catch (e) {
          console.warn('下書きデータの読み込みに失敗しました:', e);
          localStorage.removeItem(AUTOSAVE_KEY);
        }
      }

      // フォーム送信時の処理
      const form = document.getElementById('surveyForm');
      const originalSubmitHandler = form.onsubmit;

      form.addEventListener('submit', function(e) {
        isSubmitting = true;

        // フォーム送信が成功したら下書きを削除
        setTimeout(function() {
          const messageDiv = document.getElementById('message');
          if (messageDiv && messageDiv.classList.contains('success')) {
            localStorage.removeItem(AUTOSAVE_KEY);
            console.log('✅ 送信完了 - 下書きを削除しました');
          }
        }, 1000);
      });

      // フォーム要素の変更を監視
      function attachAutosaveListeners() {
        const form = document.getElementById('surveyForm');

        // 入力フィールドの変更を監視
        form.addEventListener('input', triggerAutosave);
        form.addEventListener('change', triggerAutosave);

        console.log('🔄 自動保存機能が有効になりました');
      }

      // ピッチファイルアップロードセクションの動的制御
      function setupPitchFileUpload() {
        const pitchYes = document.getElementById('pitch_yes');
        const pitchNo = document.getElementById('pitch_no');
        const pitchFileUploadSection = document.getElementById('pitchFileUploadSection');
        const pitchFileInput = document.getElementById('pitch_file');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');

        // ラジオボタンの変更イベント
        function togglePitchFileUpload() {
          if (pitchYes.checked) {
            pitchFileUploadSection.style.display = 'block';
            pitchFileInput.setAttribute('required', 'required');
          } else {
            pitchFileUploadSection.style.display = 'none';
            pitchFileInput.removeAttribute('required');
            pitchFileInput.value = ''; // ファイル選択をクリア
            filePreview.style.display = 'none';
          }
        }

        pitchYes.addEventListener('change', togglePitchFileUpload);
        pitchNo.addEventListener('change', togglePitchFileUpload);

        // ファイル選択時のプレビュー表示
        pitchFileInput.addEventListener('change', function() {
          const file = this.files[0];
          if (file) {
            // ファイルサイズチェック (30MB)
            const maxSize = 30 * 1024 * 1024; // 30MB in bytes
            if (file.size > maxSize) {
              alert('ファイルサイズが大きすぎます。30MB以下のファイルを選択してください。\n現在のファイルサイズ: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
              this.value = '';
              filePreview.style.display = 'none';
              return;
            }

            // ファイル形式チェック
            const allowedExts = ['pdf', 'pptx', 'ppt'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedExts.includes(ext)) {
              alert('対応していないファイル形式です。PDF (.pdf) または PowerPoint (.pptx, .ppt) をアップロードしてください。');
              this.value = '';
              filePreview.style.display = 'none';
              return;
            }

            // プレビュー表示
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            filePreview.style.display = 'block';
          } else {
            filePreview.style.display = 'none';
          }
        });

        // 初期状態設定
        togglePitchFileUpload();
      }

      // ページ読み込み時に実行
      document.addEventListener('DOMContentLoaded', function() {
        checkAndRestoreDraft();
        attachAutosaveListeners();
        setupPitchFileUpload(); // ピッチファイルアップロードの設定
      });

    })();
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
