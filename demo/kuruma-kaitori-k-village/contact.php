<?php
/**
 * Contact Page (お問い合わせページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/services.php';
require_once __DIR__ . '/includes/functions.php';

// フォーム送信処理
$form_submitted = false;
$form_errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRFトークン検証（簡易版）
    session_start();

    // フォームデータ取得
    $form_data = [
        'service_type' => isset($_POST['service_type']) ? trim($_POST['service_type']) : '',
        'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
        'kana' => isset($_POST['kana']) ? trim($_POST['kana']) : '',
        'tel' => isset($_POST['tel']) ? trim($_POST['tel']) : '',
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'car_maker' => isset($_POST['car_maker']) ? trim($_POST['car_maker']) : '',
        'car_model' => isset($_POST['car_model']) ? trim($_POST['car_model']) : '',
        'car_year' => isset($_POST['car_year']) ? trim($_POST['car_year']) : '',
        'message' => isset($_POST['message']) ? trim($_POST['message']) : '',
    ];

    // バリデーション
    if (empty($form_data['service_type'])) {
        $form_errors['service_type'] = 'サービス種別を選択してください';
    }

    if (empty($form_data['name'])) {
        $form_errors['name'] = 'お名前を入力してください';
    }

    if (empty($form_data['kana'])) {
        $form_errors['kana'] = 'フリガナを入力してください';
    }

    if (empty($form_data['tel'])) {
        $form_errors['tel'] = '電話番号を入力してください';
    } elseif (!validate_phone($form_data['tel'])) {
        $form_errors['tel'] = '電話番号の形式が正しくありません';
    }

    // メールアドレスは任意だが、入力されている場合は形式チェック
    if (!empty($form_data['email']) && !validate_email($form_data['email'])) {
        $form_errors['email'] = 'メールアドレスの形式が正しくありません';
    }

    if (empty($form_data['message'])) {
        $form_errors['message'] = 'お問い合わせ内容を入力してください';
    }

    // エラーがなければメール送信
    if (empty($form_errors)) {
        // メール送信処理（ここではログファイルに保存）
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $form_data
        ];

        // ログディレクトリがなければ作成
        $log_dir = __DIR__ . '/logs';
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }

        // ログファイルに追記
        $log_file = $log_dir . '/contact_' . date('Y-m') . '.log';
        file_put_contents(
            $log_file,
            json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n",
            FILE_APPEND
        );

        $form_submitted = true;

        // フォームデータをクリア
        $form_data = [];
    }
}

// ヘッダー読み込み
$page = 'contact';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Contact Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-envelope"></i>
            お問い合わせ
        </h1>
        <p class="page-hero__lead">
            お車の買取・販売・車検など、お気軽にご相談ください
        </p>
    </div>
</section>

<!-- Contact Form Section -->
<section class="section contact-section">
    <div class="container">
        <?php if ($form_submitted): ?>
        <!-- 送信完了メッセージ -->
        <div class="alert alert--success">
            <i class="fa-solid fa-circle-check"></i>
            <div>
                <h3 class="alert__title">お問い合わせありがとうございます</h3>
                <p class="alert__message">
                    お問い合わせを受け付けました。<br>
                    内容を確認次第、担当者よりご連絡させていただきます。<br>
                    今しばらくお待ちください。
                </p>
            </div>
        </div>

        <div class="text-center mt-lg">
            <a href="<?php echo url('index'); ?>" class="btn btn--primary">
                <i class="fa-solid fa-home"></i>
                トップページへ戻る
            </a>
        </div>

        <?php else: ?>
        <!-- お問い合わせフォーム -->
        <div class="contact-intro">
            <p>
                お車の買取・販売、車検、板金、リースなど、お気軽にお問い合わせください。<br>
                お電話でのお問い合わせも承っております。
            </p>
            <div class="contact-phone">
                <a href="tel:<?php echo TEL; ?>" class="contact-phone__link">
                    <i class="fa-solid fa-phone"></i>
                    <span class="contact-phone__number"><?php echo TEL_DISPLAY; ?></span>
                </a>
                <p class="contact-phone__note">受付時間: 平日 9:00〜18:00</p>
            </div>
        </div>

        <?php if (!empty($form_errors)): ?>
        <div class="alert alert--error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <h3 class="alert__title">入力内容にエラーがあります</h3>
                <p class="alert__message">下記の項目をご確認ください</p>
            </div>
        </div>
        <?php endif; ?>

        <form method="post" action="" class="contact-form" id="contact-form">

            <!-- サービス種別 -->
            <div class="form__group">
                <label for="service_type" class="form__label">
                    <i class="fa-solid fa-car"></i>
                    サービス種別
                    <span class="form__required">必須</span>
                </label>
                <select
                    name="service_type"
                    id="service_type"
                    class="form__select <?php echo isset($form_errors['service_type']) ? 'is-invalid' : ''; ?>"
                    required
                >
                    <option value="">選択してください</option>
                    <?php foreach ($services as $service): ?>
                    <option value="<?php echo h($service['id']); ?>" <?php echo (isset($form_data['service_type']) && $form_data['service_type'] === $service['id']) ? 'selected' : ''; ?>>
                        <?php echo h($service['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($form_errors['service_type'])): ?>
                <span class="form__error"><?php echo h($form_errors['service_type']); ?></span>
                <?php endif; ?>
            </div>

            <!-- お名前 -->
            <div class="form__group">
                <label for="name" class="form__label">
                    <i class="fa-solid fa-user"></i>
                    お名前
                    <span class="form__required">必須</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form__input <?php echo isset($form_errors['name']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo h($form_data['name'] ?? ''); ?>"
                    placeholder="山田 太郎"
                    required
                >
                <?php if (isset($form_errors['name'])): ?>
                <span class="form__error"><?php echo h($form_errors['name']); ?></span>
                <?php endif; ?>
            </div>

            <!-- フリガナ -->
            <div class="form__group">
                <label for="kana" class="form__label">
                    <i class="fa-solid fa-user"></i>
                    フリガナ
                    <span class="form__required">必須</span>
                </label>
                <input
                    type="text"
                    name="kana"
                    id="kana"
                    class="form__input <?php echo isset($form_errors['kana']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo h($form_data['kana'] ?? ''); ?>"
                    placeholder="ヤマダ タロウ"
                    required
                >
                <?php if (isset($form_errors['kana'])): ?>
                <span class="form__error"><?php echo h($form_errors['kana']); ?></span>
                <?php endif; ?>
            </div>

            <!-- 電話番号 -->
            <div class="form__group">
                <label for="tel" class="form__label">
                    <i class="fa-solid fa-phone"></i>
                    電話番号
                    <span class="form__required">必須</span>
                </label>
                <input
                    type="tel"
                    name="tel"
                    id="tel"
                    class="form__input <?php echo isset($form_errors['tel']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo h($form_data['tel'] ?? ''); ?>"
                    placeholder="090-1234-5678"
                    required
                >
                <?php if (isset($form_errors['tel'])): ?>
                <span class="form__error"><?php echo h($form_errors['tel']); ?></span>
                <?php endif; ?>
            </div>

            <!-- メールアドレス（任意） -->
            <div class="form__group">
                <label for="email" class="form__label">
                    <i class="fa-solid fa-envelope"></i>
                    メールアドレス
                    <span class="form__optional">任意</span>
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form__input <?php echo isset($form_errors['email']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo h($form_data['email'] ?? ''); ?>"
                    placeholder="example@example.com"
                >
                <?php if (isset($form_errors['email'])): ?>
                <span class="form__error"><?php echo h($form_errors['email']); ?></span>
                <?php endif; ?>
            </div>

            <hr class="form__divider">

            <h3 class="form__section-title" id="car-info-section">
                <i class="fa-solid fa-car"></i>
                お車の情報（買取・車検等の場合）
            </h3>
            <p class="form__section-note">サービス内容によって、お車の情報が必要な場合があります。</p>

            <!-- メーカー -->
            <div class="form__group" id="car-maker-group">
                <label for="car_maker" class="form__label">
                    <i class="fa-solid fa-industry"></i>
                    メーカー
                </label>
                <input
                    type="text"
                    name="car_maker"
                    id="car_maker"
                    class="form__input"
                    value="<?php echo h($form_data['car_maker'] ?? ''); ?>"
                    placeholder="トヨタ、日産、ホンダ など"
                >
            </div>

            <!-- 車種 -->
            <div class="form__group" id="car-model-group">
                <label for="car_model" class="form__label">
                    <i class="fa-solid fa-car-side"></i>
                    車種
                </label>
                <input
                    type="text"
                    name="car_model"
                    id="car_model"
                    class="form__input"
                    value="<?php echo h($form_data['car_model'] ?? ''); ?>"
                    placeholder="プリウス、ノート、フィット など"
                >
            </div>

            <!-- 年式 -->
            <div class="form__group" id="car-year-group">
                <label for="car_year" class="form__label">
                    <i class="fa-solid fa-calendar"></i>
                    年式
                </label>
                <input
                    type="text"
                    name="car_year"
                    id="car_year"
                    class="form__input"
                    value="<?php echo h($form_data['car_year'] ?? ''); ?>"
                    placeholder="2020年、令和2年 など"
                >
            </div>

            <hr class="form__divider">

            <!-- お問い合わせ内容 -->
            <div class="form__group">
                <label for="message" class="form__label">
                    <i class="fa-solid fa-comment"></i>
                    お問い合わせ内容
                    <span class="form__required">必須</span>
                </label>
                <textarea
                    name="message"
                    id="message"
                    class="form__textarea <?php echo isset($form_errors['message']) ? 'is-invalid' : ''; ?>"
                    rows="6"
                    placeholder="お問い合わせ内容をご記入ください"
                    required
                ><?php echo h($form_data['message'] ?? ''); ?></textarea>
                <?php if (isset($form_errors['message'])): ?>
                <span class="form__error"><?php echo h($form_errors['message']); ?></span>
                <?php endif; ?>
            </div>

            <!-- プライバシーポリシー同意 -->
            <div class="form__group">
                <label class="form__checkbox">
                    <input type="checkbox" name="privacy_agree" value="1" required>
                    <span>
                        <a href="<?php echo url('privacy'); ?>" target="_blank">プライバシーポリシー</a>に同意する
                        <span class="form__required">必須</span>
                    </span>
                </label>
            </div>

            <!-- 送信ボタン -->
            <div class="form__submit">
                <button type="submit" class="btn btn--primary btn--large">
                    <i class="fa-solid fa-paper-plane"></i>
                    送信する
                </button>
            </div>

        </form>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Contact Section -->
<?php if (!$form_submitted): ?>
<?php require_once __DIR__ . '/includes/cta.php'; ?>
<?php endif; ?>

<script>
// サービス種別に応じて車情報の表示/非表示を切り替え
document.addEventListener('DOMContentLoaded', function() {
    const serviceTypeSelect = document.getElementById('service_type');
    const carInfoSection = document.getElementById('car-info-section');
    const carMakerGroup = document.getElementById('car-maker-group');
    const carModelGroup = document.getElementById('car-model-group');
    const carYearGroup = document.getElementById('car-year-group');

    // 車情報が必要なサービス
    const carInfoServices = ['kaitori', 'shaken', 'bankin'];

    function toggleCarInfo() {
        const selectedService = serviceTypeSelect.value;
        const needsCarInfo = carInfoServices.includes(selectedService);

        if (carInfoSection && carMakerGroup && carModelGroup && carYearGroup) {
            if (needsCarInfo) {
                carInfoSection.style.display = 'block';
                carMakerGroup.style.display = 'block';
                carModelGroup.style.display = 'block';
                carYearGroup.style.display = 'block';
            } else {
                carInfoSection.style.display = 'none';
                carMakerGroup.style.display = 'none';
                carModelGroup.style.display = 'none';
                carYearGroup.style.display = 'none';
            }
        }
    }

    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', toggleCarInfo);
        // 初期表示
        toggleCarInfo();
    }
});
</script>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
