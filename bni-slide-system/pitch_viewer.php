<?php
/**
 * BNI Slide System - PDF Pitch Viewer
 * PDF.jsを使用したフルスクリーンPDFビューアー
 */

require_once __DIR__ . '/includes/session_auth.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(403);
    die('アクセス権限がありません');
}

// Get file parameter
$file = $_GET['file'] ?? '';

if (empty($file)) {
    http_response_code(400);
    die('ファイルが指定されていません');
}

// ファイル名をエスケープ
$file = basename($file);
$pdfUrl = 'api_get_pitch_file.php?file=' . urlencode($file);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>ピッチ資料 - BNI Slide System</title>

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans JP", sans-serif;
            background: #2b2b2b;
            overflow: hidden;
        }

        #viewer-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #controls {
            background: #1a1a1a;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
            z-index: 1000;
        }

        #controls.hidden {
            transform: translateY(-100%);
            transition: transform 0.3s;
        }

        .controls-left, .controls-center, .controls-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        button {
            background: #FFD700;
            color: #1a1a1a;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        button:hover {
            background: #FFC700;
            transform: translateY(-2px);
        }

        button:disabled {
            background: #666;
            color: #999;
            cursor: not-allowed;
            transform: none;
        }

        #page-info {
            color: #ccc;
            font-size: 14px;
            min-width: 100px;
            text-align: center;
        }

        #pdf-canvas-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: auto;
            background: #2b2b2b;
        }

        #pdf-canvas {
            max-width: 100%;
            max-height: 100%;
            box-shadow: 0 0 20px rgba(0,0,0,0.8);
        }

        .fullscreen-mode {
            cursor: none;
        }

        .fullscreen-mode.show-controls {
            cursor: default;
        }

        .fullscreen-mode #controls {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            opacity: 0;
            transition: opacity 0.5s;
            pointer-events: none;
        }

        .fullscreen-mode.show-controls #controls {
            opacity: 1;
            pointer-events: auto;
        }

        #close-btn {
            background: #CF2030;
            color: white;
        }

        #close-btn:hover {
            background: #a01828;
        }

        .icon {
            font-size: 16px;
            margin-right: 5px;
        }

        #loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
        }

        /* フルスクリーン開始オーバーレイ */
        #fullscreen-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.3s;
        }

        #fullscreen-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        #fullscreen-start-btn {
            background: #FFD700;
            color: #1a1a1a;
            border: none;
            padding: 30px 60px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 700;
            font-size: 28px;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            transition: all 0.3s;
        }

        #fullscreen-start-btn:hover {
            background: #FFC700;
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 215, 0, 0.5);
        }

        #fullscreen-overlay p {
            color: #ccc;
            font-size: 18px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- フルスクリーン開始オーバーレイ -->
    <div id="fullscreen-overlay">
        <button id="fullscreen-start-btn" onclick="startFullscreen()">
            <span class="icon">⛶</span> フルスクリーンで開始
        </button>
        <p>キーボード操作: 矢印キー/Spaceでページ送り、Escで終了</p>
    </div>

    <div id="viewer-container">
        <div id="controls">
            <div class="controls-left">
                <button id="close-btn" onclick="window.close()">
                    <span class="icon">✕</span>閉じる
                </button>
            </div>

            <div class="controls-center">
                <button id="prev-btn" onclick="prevPage()" disabled>
                    <span class="icon">◀</span>前へ
                </button>
                <span id="page-info">1 / 1</span>
                <button id="next-btn" onclick="nextPage()" disabled>
                    <span class="icon">▶</span>次へ
                </button>
            </div>

            <div class="controls-right">
                <button id="fullscreen-btn" onclick="toggleFullscreen()">
                    <span class="icon">⛶</span>フルスクリーン
                </button>
            </div>
        </div>

        <div id="pdf-canvas-container">
            <div id="loading">PDFを読み込み中...</div>
            <canvas id="pdf-canvas"></canvas>
        </div>
    </div>

    <script>
        // PDF.js設定
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = null; // 初回に自動計算
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const loadingEl = document.getElementById('loading');

        // PDFを読み込む
        const loadingTask = pdfjsLib.getDocument('<?php echo $pdfUrl; ?>');

        loadingTask.promise.then(function(pdf) {
            pdfDoc = pdf;
            document.getElementById('page-info').textContent = `1 / ${pdf.numPages}`;

            // ボタンを有効化
            if (pdf.numPages > 1) {
                document.getElementById('next-btn').disabled = false;
            }

            // 最初のページをレンダリング
            renderPage(pageNum);
        }).catch(function(error) {
            loadingEl.textContent = 'PDFの読み込みに失敗しました: ' + error.message;
            console.error('Error loading PDF:', error);
        });

        // ページをレンダリング
        function renderPage(num) {
            pageRendering = true;
            loadingEl.style.display = 'block';

            pdfDoc.getPage(num).then(function(page) {
                // 初回のみスケールを計算
                if (scale === null) {
                    const viewport = page.getViewport({scale: 1});
                    const containerWidth = document.getElementById('pdf-canvas-container').clientWidth;
                    const containerHeight = document.getElementById('pdf-canvas-container').clientHeight;

                    // 画面に収まる最大スケールを計算
                    const scaleX = (containerWidth * 0.9) / viewport.width;
                    const scaleY = (containerHeight * 0.9) / viewport.height;
                    scale = Math.min(scaleX, scaleY);
                }

                const scaledViewport = page.getViewport({scale: scale});

                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: scaledViewport
                };

                const renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    loadingEl.style.display = 'none';

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }

                    // ページ情報を更新
                    document.getElementById('page-info').textContent = `${num} / ${pdfDoc.numPages}`;

                    // ボタンの有効/無効を更新
                    document.getElementById('prev-btn').disabled = (num <= 1);
                    document.getElementById('next-btn').disabled = (num >= pdfDoc.numPages);
                });
            });
        }

        // ページをキューに追加
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // 前のページへ
        function prevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        }

        // 次のページへ
        function nextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        }

        // フルスクリーン開始（オーバーレイから）
        function startFullscreen() {
            const overlay = document.getElementById('fullscreen-overlay');
            const container = document.getElementById('viewer-container');

            container.requestFullscreen().then(() => {
                document.body.classList.add('fullscreen-mode');
                initControlsAutoHide();
                overlay.classList.add('hidden');
            }).catch(err => {
                alert(`フルスクリーンの有効化に失敗しました: ${err.message}`);
            });
        }

        // フルスクリーン切り替え
        function toggleFullscreen() {
            const container = document.getElementById('viewer-container');

            if (!document.fullscreenElement) {
                container.requestFullscreen().then(() => {
                    document.body.classList.add('fullscreen-mode');
                    initControlsAutoHide();
                }).catch(err => {
                    alert(`フルスクリーンの有効化に失敗しました: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
                document.body.classList.remove('fullscreen-mode');
            }
        }

        // フルスクリーン時のコントロール自動非表示
        let controlsTimer = null;
        function initControlsAutoHide() {
            const body = document.body;

            // マウス移動時にコントロールを表示
            document.addEventListener('mousemove', function showControls() {
                if (!document.fullscreenElement) return;

                body.classList.add('show-controls');

                // タイマーをクリア
                if (controlsTimer) {
                    clearTimeout(controlsTimer);
                }

                // 3秒後に非表示
                controlsTimer = setTimeout(() => {
                    body.classList.remove('show-controls');
                }, 3000);
            });
        }

        // キーボードショートカット
        document.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowLeft':
                case 'PageUp':
                    prevPage();
                    break;
                case 'ArrowRight':
                case 'PageDown':
                case ' ':
                    e.preventDefault();
                    nextPage();
                    break;
                case 'Escape':
                    if (document.fullscreenElement) {
                        document.exitFullscreen();
                    } else {
                        window.close();
                    }
                    break;
                case 'f':
                case 'F':
                    toggleFullscreen();
                    break;
            }
        });

        // フルスクリーン終了時の処理
        document.addEventListener('fullscreenchange', function() {
            if (!document.fullscreenElement) {
                document.body.classList.remove('fullscreen-mode');
            }
        });

        // ウィンドウリサイズ時に再レンダリング
        window.addEventListener('resize', function() {
            queueRenderPage(pageNum);
        });
    </script>
</body>
</html>
