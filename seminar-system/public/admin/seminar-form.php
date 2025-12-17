<?php
/**
 * セミナー作成・編集フォーム
 */
require_once __DIR__ . '/../../config/config.php';

use Seminar\Seminar;

$currentPage = 'seminars';
$pageTitle = 'セミナー編集';

// セミナーID取得
$seminarId = (int)get('id');
$isEdit = $seminarId > 0;

if ($isEdit) {
    $seminar = Seminar::getById($seminarId);
    if (!$seminar) {
        setFlash('error', 'セミナーが見つかりません');
        redirect('/public/admin/seminars.php');
    }
    $pageTitle = 'セミナー編集';
} else {
    $seminar = null;
    $pageTitle = '新規セミナー作成';
}

// POSTリクエスト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // バリデーション
    $title = trim(post('title', ''));
    $description = trim(post('description', ''));
    $venue = trim(post('venue', ''));
    $startDatetime = post('start_datetime', '');
    $endDatetime = post('end_datetime', '');
    $registrationDeadline = post('registration_deadline', '');
    $price = (int)post('price', 1000);
    $thanksMailSubject = trim(post('thanks_mail_subject', ''));
    $thanksMailBody = trim(post('thanks_mail_body', ''));
    $mailSenderName = trim(post('mail_sender_name', ''));
    $isActive = (bool)post('is_active', 1);

    if (!$title) {
        $errors[] = 'セミナー名を入力してください';
    }

    if (!$startDatetime) {
        $errors[] = '開始日時を入力してください';
    }

    if (!$endDatetime) {
        $errors[] = '終了日時を入力してください';
    }

    if ($startDatetime && $endDatetime && strtotime($startDatetime) >= strtotime($endDatetime)) {
        $errors[] = '終了日時は開始日時より後にしてください';
    }

    // PDFアップロード処理
    $pdfPath = $seminar['pdf_path'] ?? '';

    if (!empty($_FILES['pdf_file']['name'])) {
        $file = $_FILES['pdf_file'];

        // PDFかチェック
        if (!isPdfFile($file['name'])) {
            $errors[] = 'PDFファイルのみアップロード可能です';
        }

        // ファイルサイズチェック（10MB）
        if ($file['size'] > 10 * 1024 * 1024) {
            $errors[] = 'ファイルサイズは10MB以下にしてください';
        }

        if (empty($errors)) {
            // アップロード先ディレクトリ作成
            $uploadDir = __DIR__ . '/../../uploads/seminars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // ファイル名生成
            $fileName = 'seminar_' . ($seminarId ?: 'new') . '_' . time() . '.pdf';
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $pdfPath = 'uploads/seminars/' . $fileName;

                // 古いファイル削除
                if ($isEdit && $seminar['pdf_path'] && file_exists(__DIR__ . '/../../' . $seminar['pdf_path'])) {
                    unlink(__DIR__ . '/../../' . $seminar['pdf_path']);
                }
            } else {
                $errors[] = 'ファイルのアップロードに失敗しました';
            }
        }
    }

    if (empty($errors)) {
        $data = [
            'title' => $title,
            'description' => $description,
            'venue' => $venue,
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            'registration_deadline' => $registrationDeadline ?: null,
            'price' => $price,
            'pdf_path' => $pdfPath ?: null,
            'thanks_mail_subject' => $thanksMailSubject,
            'thanks_mail_body' => $thanksMailBody,
            'mail_sender_name' => $mailSenderName,
            'is_active' => $isActive ? 1 : 0
        ];

        try {
            if ($isEdit) {
                Seminar::update($seminarId, $data);
                setFlash('success', 'セミナーを更新しました');
            } else {
                $newId = Seminar::create($data);
                setFlash('success', 'セミナーを作成しました');
            }

            redirect('/public/admin/seminars.php');
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!empty($errors)) {
        setFlash('error', implode('<br>', $errors));
    }
}

$flash = getFlash();

// ヘッダー読み込み
include __DIR__ . '/includes/header.php';
?>

<div class="card">
    <h2 class="card-title"><?php echo h($pageTitle); ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <!-- セミナー名 -->
        <div class="form-group">
            <label class="form-label required">セミナー名</label>
            <input type="text" name="title" class="form-input" value="<?php echo h($seminar['title'] ?? ''); ?>" required>
        </div>

        <!-- 説明 -->
        <div class="form-group">
            <label class="form-label">説明</label>
            <textarea name="description" class="form-textarea"><?php echo h($seminar['description'] ?? ''); ?></textarea>
        </div>

        <!-- 開催場所 -->
        <div class="form-group">
            <label class="form-label">開催場所</label>
            <input type="text" name="venue" class="form-input" value="<?php echo h($seminar['venue'] ?? ''); ?>" placeholder="東京都渋谷区〇〇ビル 5F">
        </div>

        <!-- 開始日時 -->
        <div class="form-group">
            <label class="form-label required">開始日時</label>
            <input type="datetime-local" name="start_datetime" class="form-input" value="<?php echo $seminar ? date('Y-m-d\TH:i', strtotime($seminar['start_datetime'])) : ''; ?>" required>
        </div>

        <!-- 終了日時 -->
        <div class="form-group">
            <label class="form-label required">終了日時</label>
            <input type="datetime-local" name="end_datetime" class="form-input" value="<?php echo $seminar ? date('Y-m-d\TH:i', strtotime($seminar['end_datetime'])) : ''; ?>" required>
        </div>

        <!-- 申込締切日時 -->
        <div class="form-group">
            <label class="form-label">申込締切日時</label>
            <input type="datetime-local" name="registration_deadline" class="form-input" value="<?php echo $seminar && $seminar['registration_deadline'] ? date('Y-m-d\TH:i', strtotime($seminar['registration_deadline'])) : ''; ?>">
            <p class="text-muted">未設定の場合、開始時刻まで申込可能です</p>
        </div>

        <!-- 価格 -->
        <div class="form-group">
            <label class="form-label required">価格（円）</label>
            <input type="number" name="price" class="form-input" value="<?php echo $seminar['price'] ?? 1000; ?>" min="0" step="100" required>
        </div>

        <!-- スライドPDF -->
        <div class="form-group">
            <label class="form-label">スライドPDF</label>
            <?php if ($seminar && $seminar['pdf_path']): ?>
                <p class="text-muted">
                    現在のファイル: <?php echo h(basename($seminar['pdf_path'])); ?>
                    <a href="/seminar-system/<?php echo h($seminar['pdf_path']); ?>" target="_blank">プレビュー</a>
                </p>
            <?php endif; ?>
            <input type="file" name="pdf_file" class="form-input" accept=".pdf">
            <p class="text-muted">サンクスメールに添付されます（最大10MB）</p>
        </div>

        <!-- サンクスメール件名 -->
        <div class="form-group">
            <label class="form-label">サンクスメール件名</label>
            <input type="text" name="thanks_mail_subject" class="form-input" value="<?php echo h($seminar['thanks_mail_subject'] ?? 'セミナーご参加ありがとうございました'); ?>">
        </div>

        <!-- サンクスメール本文 -->
        <div class="form-group">
            <label class="form-label">サンクスメール本文</label>
            <textarea name="thanks_mail_body" class="form-textarea"><?php echo h($seminar['thanks_mail_body'] ?? ''); ?></textarea>
        </div>

        <!-- メール送信者名 -->
        <div class="form-group">
            <label class="form-label">メール送信者名</label>
            <input type="text" name="mail_sender_name" class="form-input" value="<?php echo h($seminar['mail_sender_name'] ?? 'セミナー運営事務局'); ?>">
        </div>

        <!-- 有効/無効 -->
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" <?php echo (!$seminar || $seminar['is_active']) ? 'checked' : ''; ?>>
                有効
            </label>
            <p class="text-muted">無効にすると申込受付が停止されます</p>
        </div>

        <!-- ボタン -->
        <div class="actions">
            <button type="submit" class="btn"><?php echo $isEdit ? '更新' : '作成'; ?></button>
            <a href="/seminar-system/public/admin/seminars.php" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
