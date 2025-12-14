<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ネットワーキング学習 PDF管理 | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .actions-bar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #C8102E;
            color: white;
        }

        .btn-primary:hover {
            background: #a00a24;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-group input[type="date"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .pdf-list {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .pdf-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pdf-item:last-child {
            border-bottom: none;
        }

        .pdf-info {
            flex: 1;
        }

        .pdf-info h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #C8102E;
        }

        .pdf-info p {
            font-size: 14px;
            color: #666;
        }

        .pdf-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h2 {
            font-size: 20px;
            color: #C8102E;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .processing-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            font-size: 14px;
            color: #856404;
        }

        .image-preview {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .image-preview img {
            width: 150px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-file-pdf"></i> ネットワーキング学習 PDF管理</h1>
        <div class="subtitle">PDFをアップロードして画像に変換し、スライドに挿入します</div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus"></i> PDFを追加
            </button>
            <button class="btn btn-secondary" onclick="viewSlide()">
                <i class="fas fa-external-link-alt"></i> スライドを確認（p.86）
            </button>
            <button class="btn btn-secondary" onclick="location.href='index.php'">
                <i class="fas fa-arrow-left"></i> ダッシュボードに戻る
            </button>
        </div>

        <div class="pdf-list" id="pdfList">
            <!-- PDFリストがここに動的に表示されます -->
        </div>
    </div>

    <!-- 追加モーダル -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeAddModal()">&times;</span>
                <h2><i class="fas fa-plus-circle"></i> PDFを追加</h2>
            </div>
            <form id="addForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label>週の日付 *</label>
                    <input type="date" id="weekDate" name="week_date" required>
                </div>
                <div class="form-group">
                    <label>PDFファイル *</label>
                    <input type="file" id="pdfFile" name="pdf_file" accept=".pdf" required>
                </div>
                <div class="processing-info">
                    <i class="fas fa-info-circle"></i>
                    アップロードしたPDFは自動的に画像に変換され、86枚目以降のスライドに挿入されます。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">キャンセル</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> アップロード
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ページ読み込み時にPDFリストを取得
        document.addEventListener('DOMContentLoaded', function() {
            loadPdfList();

            // 今日の日付をデフォルト値に設定
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('weekDate').value = today;
        });

        // PDFリスト取得
        async function loadPdfList() {
            try {
                const response = await fetch('../api/networking_pdf_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    displayPdfList(data.pdfs);
                } else {
                    console.error('PDFリスト取得エラー:', data.error);
                }
            } catch (error) {
                console.error('通信エラー:', error);
            }
        }

        // PDFリスト表示
        function displayPdfList(pdfs) {
            const listContainer = document.getElementById('pdfList');

            if (pdfs.length === 0) {
                listContainer.innerHTML = '<div style="padding: 40px; text-align: center; color: #999;">まだPDFが登録されていません</div>';
                return;
            }

            listContainer.innerHTML = pdfs.map(pdf => {
                const imageCount = pdf.image_paths ? JSON.parse(pdf.image_paths).length : 0;
                return `
                    <div class="pdf-item">
                        <div class="pdf-info">
                            <h3><i class="fas fa-file-pdf"></i> ${pdf.week_date}</h3>
                            <p><i class="fas fa-images"></i> ${imageCount}ページ分の画像を生成済み</p>
                            <p style="font-size: 12px; color: #999; margin-top: 5px;">
                                登録日時: ${pdf.created_at}
                            </p>
                        </div>
                        <div class="pdf-actions">
                            <button class="btn btn-secondary btn-sm" onclick="viewImages(${pdf.id})">
                                <i class="fas fa-eye"></i> 画像確認
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deletePdf(${pdf.id})">
                                <i class="fas fa-trash"></i> 削除
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // モーダル開閉
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('addForm').reset();
        }

        // PDF追加
        document.getElementById('addForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'create');

            try {
                const response = await fetch('../api/networking_pdf_crud.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('PDFをアップロードし、画像に変換しました！');
                    closeAddModal();
                    loadPdfList();
                } else {
                    alert('エラー: ' + data.error);
                }
            } catch (error) {
                alert('通信エラーが発生しました: ' + error);
            }
        });

        // PDF削除
        async function deletePdf(id) {
            if (!confirm('このPDFを削除してもよろしいですか？')) {
                return;
            }

            try {
                const response = await fetch('../api/networking_pdf_crud.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=delete&id=' + id
                });

                const data = await response.json();

                if (data.success) {
                    alert('削除しました');
                    loadPdfList();
                } else {
                    alert('エラー: ' + data.error);
                }
            } catch (error) {
                alert('通信エラーが発生しました: ' + error);
            }
        }

        // 画像確認
        function viewImages(id) {
            window.open(`../slides/networking_slides.php?id=${id}`, '_blank');
        }

        // モーダル外クリックで閉じる
        window.onclick = function(event) {
            const modal = document.getElementById('addModal');
            if (event.target == modal) {
                closeAddModal();
            }
        }

        // スライドを確認
        function viewSlide() {
            const today = new Date();
            const weekDate = today.toISOString().split('T')[0];
            const pageNumber = 86;
            const url = `../index.php?date=${weekDate}#${pageNumber}`;
            window.open(url, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
