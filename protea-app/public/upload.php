<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Protea\ExcelParser;
use Protea\KeywordRepository;

$error = null;
$success = null;

// POSTリクエスト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    try {
        $file = $_FILES['excel_file'];

        // バリデーション
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("ファイルアップロードエラー");
        }

        $allowedExtensions = ['xlsx'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            throw new Exception("許可されていないファイル形式です。.xlsx ファイルをアップロードしてください。");
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($file['size'] > $maxSize) {
            throw new Exception("ファイルサイズが大きすぎます（最大10MB）");
        }

        // ファイルを保存
        $uploadDir = __DIR__ . '/../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = date('YmdHis') . '_' . basename($file['name']);
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception("ファイル保存に失敗しました");
        }

        // Excelパース
        $parser = new ExcelParser();
        $parser->load($uploadPath);
        $data = $parser->parseAll();

        // DB登録
        $repository = new KeywordRepository();
        $result = $repository->importExcelData($data, $filename);

        $success = [
            'message' => "「{$data['keyword']}」のデータを登録しました",
            'keyword_id' => $result['keyword_id'],
            'counts' => $result['counts'],
        ];

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excelアップロード - Protea</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .upload-area {
            border: 2px dashed var(--color-border);
            background: white;
            padding: var(--spacing-xl);
            text-align: center;
            transition: all var(--transition-base);
            cursor: pointer;
        }

        .upload-area:hover,
        .upload-area.drag-over {
            border-color: var(--color-natural-brown);
            background: var(--color-off-white);
        }

        .upload-area i {
            font-size: 48px;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-md);
        }

        .upload-area p {
            color: var(--color-text-light);
            margin-bottom: var(--spacing-sm);
        }

        .file-info {
            margin-top: var(--spacing-md);
            padding: var(--spacing-sm);
            background: var(--color-off-white);
            border-left: 3px solid var(--color-natural-brown);
        }

        #file-input {
            display: none;
        }

        .result-table {
            margin-top: var(--spacing-lg);
        }

        .result-table th {
            width: 200px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Protea Webアプリ</h1>
            <p class="subtitle">Excelアップロード</p>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">キーワード一覧</a></li>
                <li><a href="upload.php" class="active">Excelアップロード</a></li>
                <li><a href="dashboard.php">ダッシュボード</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success['message']) ?>
            </div>

            <div class="card">
                <h3>登録結果</h3>
                <table class="result-table">
                    <tr>
                        <th>キーワードID</th>
                        <td><?= $success['keyword_id'] ?></td>
                    </tr>
                    <tr>
                        <th>ブログ記事</th>
                        <td><?= $success['counts']['blog_articles'] ?>件</td>
                    </tr>
                    <tr>
                        <th>共起語</th>
                        <td><?= $success['counts']['cooccurrence_words'] ?>件</td>
                    </tr>
                    <tr>
                        <th>サジェスト</th>
                        <td><?= $success['counts']['suggestions'] ?>件</td>
                    </tr>
                    <tr>
                        <th>競合記事本文</th>
                        <td><?= $success['counts']['article_contents'] ?>件</td>
                    </tr>
                    <tr>
                        <th>Yahoo知恵袋</th>
                        <td><?= $success['counts']['yahoo_qa'] ?>件</td>
                    </tr>
                    <tr>
                        <th>goo Q&A</th>
                        <td><?= $success['counts']['goo_qa'] ?>件</td>
                    </tr>
                </table>

                <p style="margin-top: var(--spacing-md);">
                    <a href="detail.php?id=<?= $success['keyword_id'] ?>" class="btn">詳細を見る</a>
                    <a href="index.php" class="btn btn-secondary">キーワード一覧に戻る</a>
                </p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3>Excelファイルをアップロード</h3>

            <form method="POST" enctype="multipart/form-data" id="upload-form">
                <div class="upload-area" id="upload-area">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p><strong>ここにファイルをドラッグ&ドロップ</strong></p>
                    <p>または</p>
                    <button type="button" class="btn" onclick="document.getElementById('file-input').click()">
                        ファイルを選択
                    </button>
                    <input type="file" name="excel_file" id="file-input" accept=".xlsx" required>
                </div>

                <div id="file-info" class="file-info" style="display: none;">
                    <i class="fas fa-file-excel"></i>
                    <span id="file-name"></span>
                    <span id="file-size"></span>
                </div>

                <div style="margin-top: var(--spacing-md);">
                    <button type="submit" class="btn" id="submit-btn" disabled>
                        <i class="fas fa-upload"></i> アップロード
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3>アップロード要件</h3>
            <ul>
                <li>ファイル形式: .xlsx（Excel 2007以降）</li>
                <li>最大ファイルサイズ: 10MB</li>
                <li>必須シート: ブログ記事、共起語、サジェスト、URL本文、Yahoo知恵袋、goo Q&A</li>
                <li>ファイル名からキーワードを自動抽出します（例: 開業医_患者_来ない.xlsx）</li>
            </ul>
        </div>
    </div>

    <script>
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const submitBtn = document.getElementById('submit-btn');

        // ドラッグ&ドロップ
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateFileInfo(files[0]);
            }
        });

        // ファイル選択
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                updateFileInfo(e.target.files[0]);
            }
        });

        function updateFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            fileInfo.style.display = 'block';
            submitBtn.disabled = false;
        }
    </script>
</body>
</html>
