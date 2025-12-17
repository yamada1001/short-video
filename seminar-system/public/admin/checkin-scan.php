<?php
/**
 * QRコードスキャン画面（管理者用）
 * カメラでQRコードをスキャンして出席確認
 */
require_once __DIR__ . '/../../config/config.php';

$currentPage = 'checkin';
$pageTitle = 'QRチェックイン';

// ヘッダー読み込み
include __DIR__ . '/includes/header.php';
?>

<style>
.checkin-container {
    max-width: 800px;
    margin: 0 auto;
}

.camera-section {
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    margin-bottom: 24px;
}

#video {
    width: 100%;
    height: auto;
    display: block;
}

.camera-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 250px;
    height: 250px;
    border: 3px solid rgba(52, 152, 219, 0.8);
    border-radius: 12px;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    pointer-events: none;
}

.camera-overlay::before {
    content: '';
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border: 3px solid rgba(52, 152, 219, 0.3);
    border-radius: 12px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.05);
    }
}

.status-message {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    text-align: center;
    font-weight: 500;
    display: none;
}

.status-message.show {
    display: block;
}

.status-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.status-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.attendee-info {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 24px;
    display: none;
}

.attendee-info.show {
    display: block;
}

.attendee-info h3 {
    margin-bottom: 16px;
    color: #333;
    font-size: 18px;
}

.info-grid {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 12px;
    margin-bottom: 20px;
}

.info-label {
    font-weight: 500;
    color: #666;
}

.info-value {
    color: #333;
}

.btn-confirm {
    background: #28a745;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    margin-top: 16px;
}

.btn-confirm:hover {
    background: #218838;
}

.btn-confirm:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.controls {
    text-align: center;
    margin-bottom: 24px;
}

.btn-toggle {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
}

.btn-toggle:hover {
    background: #5a6268;
}

canvas {
    display: none;
}

.instruction {
    background: #f8f9fa;
    border-left: 4px solid #3498db;
    padding: 16px 20px;
    margin-bottom: 24px;
    font-size: 14px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .camera-overlay {
        width: 200px;
        height: 200px;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .info-label {
        font-size: 13px;
    }
}
</style>

<div class="checkin-container">
    <h2 class="page-title">QRチェックイン</h2>

    <div class="instruction">
        📱 参加者のQRコードをカメラでスキャンしてください。<br>
        QRコードが枠内に収まるように調整してください。
    </div>

    <div class="controls">
        <button id="startBtn" class="btn-toggle">カメラを起動</button>
        <button id="stopBtn" class="btn-toggle" style="display: none;">カメラを停止</button>
    </div>

    <div id="statusMessage" class="status-message"></div>

    <div class="camera-section" id="cameraSection" style="display: none;">
        <video id="video" playsinline autoplay></video>
        <div class="camera-overlay"></div>
    </div>

    <canvas id="canvas"></canvas>

    <div id="attendeeInfo" class="attendee-info"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const statusMessage = document.getElementById('statusMessage');
const attendeeInfo = document.getElementById('attendeeInfo');
const cameraSection = document.getElementById('cameraSection');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');

let stream = null;
let scanning = false;
let lastScannedToken = '';

// ステータスメッセージ表示
function showStatus(message, type = 'info') {
    statusMessage.textContent = message;
    statusMessage.className = 'status-message show status-' + type;
    setTimeout(() => {
        statusMessage.classList.remove('show');
    }, 5000);
}

// カメラ起動
startBtn.addEventListener('click', async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });
        video.srcObject = stream;
        cameraSection.style.display = 'block';
        startBtn.style.display = 'none';
        stopBtn.style.display = 'inline-block';
        scanning = true;
        requestAnimationFrame(scan);
        showStatus('カメラを起動しました。QRコードをスキャンしてください。', 'info');
    } catch (err) {
        showStatus('カメラの起動に失敗しました: ' + err.message, 'error');
    }
});

// カメラ停止
stopBtn.addEventListener('click', () => {
    stopCamera();
});

function stopCamera() {
    scanning = false;
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    cameraSection.style.display = 'none';
    startBtn.style.display = 'inline-block';
    stopBtn.style.display = 'none';
}

// QRコードスキャン
function scan() {
    if (!scanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code && code.data) {
            // 同じQRコードを連続スキャンしないように
            if (code.data !== lastScannedToken) {
                lastScannedToken = code.data;
                onQRCodeDetected(code.data);
            }
        }
    }

    requestAnimationFrame(scan);
}

// QRコード検出時の処理
async function onQRCodeDetected(token) {
    scanning = false; // スキャンを一時停止

    showStatus('QRコードを読み取りました。確認中...', 'info');

    try {
        const response = await fetch('/seminar-system/public/api/checkin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ token: token })
        });

        const result = await response.json();

        if (result.success) {
            showStatus('✓ チェックイン完了: ' + result.attendee.name, 'success');
            displayAttendeeInfo(result.attendee, result.seminar);

            // 2秒後にスキャン再開
            setTimeout(() => {
                attendeeInfo.classList.remove('show');
                lastScannedToken = '';
                scanning = true;
            }, 3000);
        } else {
            showStatus('エラー: ' + result.message, 'error');
            // 1秒後にスキャン再開
            setTimeout(() => {
                lastScannedToken = '';
                scanning = true;
            }, 2000);
        }
    } catch (err) {
        showStatus('通信エラー: ' + err.message, 'error');
        setTimeout(() => {
            lastScannedToken = '';
            scanning = true;
        }, 2000);
    }
}

// 参加者情報表示
function displayAttendeeInfo(attendee, seminar) {
    const statusLabels = {
        applied: '申込済',
        paid: '支払済',
        attended: '出席済',
        absent: '欠席'
    };

    attendeeInfo.innerHTML = `
        <h3>✓ チェックイン完了</h3>
        <div class="info-grid">
            <div class="info-label">お名前</div>
            <div class="info-value"><strong>${escapeHtml(attendee.name)}</strong></div>

            <div class="info-label">メール</div>
            <div class="info-value">${escapeHtml(attendee.email)}</div>

            <div class="info-label">セミナー</div>
            <div class="info-value">${escapeHtml(seminar.title)}</div>

            <div class="info-label">ステータス</div>
            <div class="info-value"><span class="badge badge-success">${statusLabels[attendee.status] || attendee.status}</span></div>
        </div>
    `;
    attendeeInfo.classList.add('show');
}

// HTMLエスケープ
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// ページ離脱時にカメラ停止
window.addEventListener('beforeunload', stopCamera);
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
