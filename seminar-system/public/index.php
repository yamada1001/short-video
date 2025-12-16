<?php
/**
 * セミナー申込フォーム
 */
require_once __DIR__ . '/../config/config.php';

use Seminar\Seminar;
use Seminar\Attendee;
use Seminar\Survey;

// フラッシュメッセージ取得
$flash = getFlash();

// 申込受付中のセミナー取得
$seminars = Seminar::getOpenForRegistration();

// POSTリクエスト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF検証
    if (!verifyCsrfToken(post('csrf_token', ''))) {
        setFlash('error', '不正なリクエストです');
        redirect('/public/index.php');
    }

    // バリデーション
    $errors = [];
    $seminarId = (int)post('seminar_id');
    $name = trim(post('name', ''));
    $email = trim(post('email', ''));
    $phone = trim(post('phone', ''));

    if (!$seminarId) {
        $errors[] = 'セミナーを選択してください';
    }

    if (!$name) {
        $errors[] = '名前を入力してください';
    }

    if (!$email || !isValidEmail($email)) {
        $errors[] = '有効なメールアドレスを入力してください';
    }

    // 重複申込チェック
    if (Attendee::hasRegistered($seminarId, $email)) {
        $errors[] = 'このセミナーには既に申込済みです';
    }

    // セミナー存在チェック
    $seminar = Seminar::getById($seminarId);
    if (!$seminar) {
        $errors[] = 'セミナーが見つかりません';
    }

    // 申込受付チェック
    if ($seminar && !Seminar::isRegistrationOpen($seminarId)) {
        $errors[] = 'このセミナーは申込を受け付けていません';
    }

    if (empty($errors)) {
        try {
            // 参加者登録
            $attendeeId = Attendee::create([
                'seminar_id' => $seminarId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'status' => 'applied'
            ]);

            // 申込時アンケート回答保存
            $surveyAnswers = [];
            $questions = Survey::getQuestions('registration', $seminarId);

            foreach ($questions as $question) {
                $questionId = $question['id'];
                $answer = post("question_{$questionId}");

                // 必須チェック
                if ($question['is_required'] && isEmpty($answer)) {
                    throw new \Exception("必須項目を入力してください: {$question['question_text']}");
                }

                if (!isEmpty($answer)) {
                    $surveyAnswers[$questionId] = $answer;
                }
            }

            if (!empty($surveyAnswers)) {
                Survey::saveAnswers($attendeeId, $surveyAnswers);
            }

            // 成功メッセージ
            setFlash('success', 'お申込みを受け付けました。メールをご確認ください。');
            redirect('/public/thank-you.php?attendee_id=' . $attendeeId);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
    }

    // エラーがあればフラッシュメッセージに設定
    if (!empty($errors)) {
        setFlash('error', implode('<br>', $errors));
    }
}

// CSRFトークン生成
$csrfToken = generateCsrfToken();

// 申込時アンケート質問取得（共通質問）
$surveyQuestions = Survey::getQuestions('registration', null);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>セミナー申込 - セミナー管理システム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.8;
            color: #333;
            background: #fafafa;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .header {
            margin-bottom: 60px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .subtitle {
            font-size: 13px;
            color: #999;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 32px;
            border: 1px solid #e0e0e0;
            background: #fff;
        }

        .alert-success {
            border-color: #4caf50;
            background: #f1f8f4;
            color: #2e7d32;
        }

        .alert-error {
            border-color: #f44336;
            background: #fef5f5;
            color: #c62828;
        }

        .seminar-list {
            margin-bottom: 60px;
        }

        .seminar-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 32px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .seminar-card:hover {
            border-color: #333;
        }

        .seminar-card.selected {
            border-color: #333;
            background: #fafafa;
        }

        .seminar-title {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin-bottom: 12px;
        }

        .seminar-meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .seminar-venue {
            font-size: 13px;
            color: #999;
            margin-bottom: 12px;
        }

        .seminar-price {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .form-section {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 48px 32px;
            margin-bottom: 32px;
        }

        .form-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 32px;
            letter-spacing: 0.05em;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .form-label.required::after {
            content: " *";
            color: #f44336;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            color: #333;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #333;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-radio,
        .form-checkbox {
            margin-bottom: 12px;
        }

        .form-radio label,
        .form-checkbox label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .form-radio input,
        .form-checkbox input {
            margin-right: 8px;
        }

        .btn {
            display: inline-block;
            padding: 16px 48px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #333;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
            cursor: pointer;
            font-family: 'Noto Sans JP', sans-serif;
            width: 100%;
        }

        .btn:hover {
            background: #000;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .text-muted {
            font-size: 12px;
            color: #999;
            margin-top: 8px;
        }

        .no-seminars {
            text-align: center;
            padding: 60px 24px;
            color: #999;
        }

        @media (max-width: 640px) {
            .container {
                padding: 40px 16px;
            }

            .form-section {
                padding: 32px 24px;
            }

            .seminar-card {
                padding: 24px;
            }

            .btn {
                padding: 14px 32px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ヘッダー -->
        <header class="header">
            <h1>セミナー申込</h1>
            <p class="subtitle">参加したいセミナーを選択してください</p>
        </header>

        <!-- フラッシュメッセージ -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo h($flash['type']); ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($seminars)): ?>
            <!-- セミナーがない場合 -->
            <div class="no-seminars">
                <p>現在申込受付中のセミナーはありません</p>
            </div>
        <?php else: ?>
            <!-- 申込フォーム -->
            <form method="POST" action="/seminar-system/public/index.php" id="registrationForm">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">
                <input type="hidden" name="seminar_id" id="seminar_id" value="">

                <!-- セミナー選択 -->
                <div class="seminar-list">
                    <?php foreach ($seminars as $sem): ?>
                        <div class="seminar-card" data-seminar-id="<?php echo $sem['id']; ?>">
                            <div class="seminar-title"><?php echo h($sem['title']); ?></div>
                            <div class="seminar-meta">
                                <?php echo formatDatetime($sem['start_datetime'], 'Y年m月d日（' . getWeekday($sem['start_datetime']) . '）H:i'); ?>
                                〜
                                <?php echo date('H:i', strtotime($sem['end_datetime'])); ?>
                            </div>
                            <?php if ($sem['venue']): ?>
                                <div class="seminar-venue">📍 <?php echo h($sem['venue']); ?></div>
                            <?php endif; ?>
                            <div class="seminar-price"><?php echo formatPrice($sem['price']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 参加者情報入力 -->
                <div class="form-section" id="attendeeInfo" style="display: none;">
                    <h2 class="form-title">参加者情報</h2>

                    <div class="form-group">
                        <label class="form-label required">お名前</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">メールアドレス</label>
                        <input type="email" name="email" class="form-input" required>
                        <p class="text-muted">申込完了メールが送信されます</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">電話番号</label>
                        <input type="tel" name="phone" class="form-input">
                    </div>
                </div>

                <!-- アンケート -->
                <?php if (!empty($surveyQuestions)): ?>
                    <div class="form-section" id="surveySection" style="display: none;">
                        <h2 class="form-title">アンケート</h2>

                        <?php foreach ($surveyQuestions as $question): ?>
                            <div class="form-group">
                                <label class="form-label <?php echo $question['is_required'] ? 'required' : ''; ?>">
                                    <?php echo h($question['question_text']); ?>
                                </label>

                                <?php if ($question['question_type'] === 'text'): ?>
                                    <!-- テキスト -->
                                    <textarea name="question_<?php echo $question['id']; ?>"
                                              class="form-textarea"
                                              <?php echo $question['is_required'] ? 'required' : ''; ?>></textarea>

                                <?php elseif ($question['question_type'] === 'radio'): ?>
                                    <!-- ラジオボタン -->
                                    <?php
                                    $options = json_decode($question['options'], true);
                                    foreach ($options as $option):
                                    ?>
                                        <div class="form-radio">
                                            <label>
                                                <input type="radio"
                                                       name="question_<?php echo $question['id']; ?>"
                                                       value="<?php echo h($option); ?>"
                                                       <?php echo $question['is_required'] ? 'required' : ''; ?>>
                                                <?php echo h($option); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>

                                <?php elseif ($question['question_type'] === 'checkbox'): ?>
                                    <!-- チェックボックス -->
                                    <?php
                                    $options = json_decode($question['options'], true);
                                    foreach ($options as $option):
                                    ?>
                                        <div class="form-checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       name="question_<?php echo $question['id']; ?>[]"
                                                       value="<?php echo h($option); ?>">
                                                <?php echo h($option); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- 送信ボタン -->
                <div id="submitSection" style="display: none;">
                    <button type="submit" class="btn">申し込む</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // セミナーカード選択
        document.querySelectorAll('.seminar-card').forEach(card => {
            card.addEventListener('click', function() {
                // 選択状態をトグル
                document.querySelectorAll('.seminar-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');

                // セミナーID設定
                const seminarId = this.dataset.seminarId;
                document.getElementById('seminar_id').value = seminarId;

                // フォームセクション表示
                document.getElementById('attendeeInfo').style.display = 'block';
                <?php if (!empty($surveyQuestions)): ?>
                document.getElementById('surveySection').style.display = 'block';
                <?php endif; ?>
                document.getElementById('submitSection').style.display = 'block';

                // スムーズスクロール
                document.getElementById('attendeeInfo').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>
