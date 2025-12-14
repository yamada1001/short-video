<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>QRコード管理 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 24px; font-weight: 600; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .actions-bar { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .btn-secondary { background: #6c757d; color: white; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .save-btn { width: 100%; padding: 12px; background: #C8102E; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .date-selector { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .preview { margin-top: 30px; text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px; }
        .preview img { max-width: 300px; border: 2px solid #ddd; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-qrcode"></i> QRコード管理</h1>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-secondary" onclick="location.href='index.php'"><i class="fas fa-arrow-left"></i> 戻る</button>
            <button class="btn btn-secondary" onclick="viewSlide()">
                <i class="fas fa-external-link-alt"></i> スライドを確認（p.242）
            </button>
        </div>

        <div class="date-selector">
            <label><i class="fas fa-calendar"></i> 対象週:</label>
            <input type="date" id="weekDate" onchange="loadQRCode()">
        </div>

        <div class="card">
            <h2 style="color: #C8102E; margin-bottom: 20px;">QRコードを生成</h2>
            <form id="qrForm">
                <div class="form-group">
                    <label>URL</label>
                    <input type="url" id="url" placeholder="https://example.com" required>
                </div>
                <button type="submit" class="save-btn"><i class="fas fa-qrcode"></i> QRコード生成</button>
            </form>

            <div id="preview" class="preview" style="display: none;">
                <h3 style="margin-bottom: 20px;">生成されたQRコード</h3>
                <img id="qrImage" src="" alt="QR Code">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('weekDate').value = new Date().toISOString().split('T')[0];
            loadQRCode();

            document.getElementById('qrForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const weekDate = document.getElementById('weekDate').value;
                const url = document.getElementById('url').value;

                try {
                    const response = await fetch('../api/qr_code_crud.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'create',
                            week_date: weekDate,
                            url: url
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        alert('QRコードを生成しました！');
                        loadQRCode();
                    } else {
                        alert('エラー: ' + data.error);
                    }
                } catch (error) {
                    alert('通信エラー: ' + error);
                }
            });
        });

        async function loadQRCode() {
            const weekDate = document.getElementById('weekDate').value;
            try {
                const response = await fetch(`../api/qr_code_crud.php?action=get&week_date=${weekDate}`);
                const data = await response.json();
                
                if (data.success && data.qr) {
                    document.getElementById('url').value = data.qr.url;
                    document.getElementById('qrImage').src = '../' + data.qr.qr_code_path;
                    document.getElementById('preview').style.display = 'block';
                } else {
                    document.getElementById('preview').style.display = 'none';
                }
            } catch (error) {
                console.error('データ取得エラー:', error);
            }
        }

        // スライドを確認
        function viewSlide() {
            const weekDate = document.getElementById('weekDate').value;
            if (!weekDate) {
                alert('対象週を選択してください');
                return;
            }

            const pageNumber = 242;
            const url = `../index.php?date=${weekDate}#${pageNumber}`;
            window.open(url, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
