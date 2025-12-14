<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メインプレゼン管理 | BNI Slide System V2</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // PDF.js worker設定
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

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

        /* Header */
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

        /* Container */
        .container {
            max-width: 1600px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .card-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #C8102E;
        }

        .card-header h2 {
            color: #C8102E;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-group .required {
            color: #C8102E;
            margin-left: 4px;
        }

        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #C8102E;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px dashed #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #C8102E;
        }

        /* Member Selection */
        .member-selection {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .member-preview {
            display: none;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 2px solid #C8102E;
            margin-top: 15px;
        }

        .member-preview.active {
            display: block;
        }

        .member-preview-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .member-preview-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
        }

        .member-preview-info h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .member-preview-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 3px;
        }

        /* Presentation Type */
        .type-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .type-option {
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .type-option:hover {
            border-color: #C8102E;
            background: #fff5f5;
        }

        .type-option input[type="radio"] {
            display: none;
        }

        .type-option input[type="radio"]:checked + .type-option-content {
            color: #C8102E;
        }

        .type-option input[type="radio"]:checked ~ .type-option {
            border-color: #C8102E;
            background: #fff5f5;
        }

        .type-option.selected {
            border-color: #C8102E;
            background: #fff5f5;
        }

        .type-option-content h4 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .type-option-content p {
            font-size: 13px;
            color: #666;
        }

        /* Extended Options */
        .extended-options {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .extended-options.active {
            display: block;
        }

        /* Buttons */
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        /* Preview */
        .preview-container {
            position: sticky;
            top: 20px;
        }

        .slide-preview {
            width: 100%;
            aspect-ratio: 16 / 9;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .slide-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 100px 100px;
        }

        .slide-content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
        }

        .slide-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            margin-bottom: 30px;
            background: #ddd;
        }

        .slide-category {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .slide-name {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .slide-company {
            font-size: 32px;
            font-weight: 400;
            opacity: 0.95;
        }

        .slide-placeholder {
            font-size: 24px;
            opacity: 0.7;
        }

        /* Info Box */
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box h4 {
            color: #0066cc;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-box p {
            font-size: 13px;
            color: #333;
            line-height: 1.6;
        }

        .info-box ul {
            margin-top: 8px;
            margin-left: 20px;
            font-size: 13px;
            color: #333;
        }

        .info-box ul li {
            margin-bottom: 4px;
        }

        /* File Info */
        .file-info {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
            font-size: 13px;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                grid-template-columns: 1fr;
            }

            .preview-container {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-presentation"></i> メインプレゼン管理</h1>
        <div class="subtitle">BNI Slide System V2 - Main Presentation Management</div>
    </div>

    <div class="container">
        <!-- Form Section -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> プレゼン情報入力</h2>
            </div>

            <form id="presenterForm" enctype="multipart/form-data">
                <!-- Member Selection -->
                <div class="form-group">
                    <label>メインプレゼンター <span class="required">*</span></label>
                    <select id="memberId" required>
                        <option value="">メンバーを選択してください</option>
                    </select>

                    <!-- Member Preview -->
                    <div class="member-preview" id="memberPreview">
                        <div class="member-preview-content">
                            <img id="previewPhoto" class="member-preview-photo" alt="">
                            <div class="member-preview-info">
                                <h3 id="previewName"></h3>
                                <p id="previewCompany"></p>
                                <p id="previewCategory"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Presentation Type -->
                <div class="form-group">
                    <label>プレゼンタイプ <span class="required">*</span></label>
                    <div class="type-options">
                        <div class="type-option selected" onclick="selectType('simple')">
                            <input type="radio" name="presentationType" value="simple" id="typeSimple" checked>
                            <div class="type-option-content">
                                <h4><i class="fas fa-user"></i> シンプル版（p.8）</h4>
                                <p>写真・カテゴリ・名前・会社名のみ</p>
                            </div>
                        </div>
                        <div class="type-option" onclick="selectType('extended')">
                            <input type="radio" name="presentationType" value="extended" id="typeExtended">
                            <div class="type-option-content">
                                <h4><i class="fas fa-file-pdf"></i> 拡張版（p.204）</h4>
                                <p>PDF資料・動画対応</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extended Options -->
                <div class="extended-options" id="extendedOptions">
                    <div class="info-box">
                        <h4><i class="fas fa-info-circle"></i> 拡張版について</h4>
                        <ul>
                            <li>PDF資料を添付すると、画像変換して204ページ以降に挿入されます</li>
                            <li>YouTube動画（限定公開可）はiframeで埋め込まれます</li>
                            <li>どちらか一方、または両方を使用できます</li>
                        </ul>
                    </div>

                    <!-- PDF Upload -->
                    <div class="form-group">
                        <label><i class="fas fa-file-pdf"></i> PDF資料添付（任意）</label>
                        <input type="file" id="pdfFile" accept="application/pdf">
                        <div class="file-info" id="pdfFileInfo" style="display: none;"></div>
                    </div>

                    <!-- YouTube URL -->
                    <div class="form-group">
                        <label><i class="fab fa-youtube"></i> YouTube動画URL（任意）</label>
                        <textarea id="youtubeUrl" placeholder="https://www.youtube.com/watch?v=xxxxx&#10;または&#10;https://youtu.be/xxxxx"></textarea>
                        <div class="file-info" id="youtubeInfo" style="display: none;"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 保存
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> リセット
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="viewSlide(8)">
                        <i class="fas fa-external-link-alt"></i> p.8を確認
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="viewSlide(204)">
                        <i class="fas fa-external-link-alt"></i> p.204を確認
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="preview-container">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-eye"></i> プレビュー（p.8）</h2>
                </div>

                <div class="slide-preview">
                    <div class="slide-content" id="slideContent">
                        <div class="slide-placeholder">
                            <i class="fas fa-presentation" style="font-size: 64px; margin-bottom: 20px;"></i>
                            <p>メンバーを選択すると<br>プレビューが表示されます</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/main_presenter_crud.php';
        let members = [];
        let currentMember = null;
        let isEditMode = false;
        let currentData = null;

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            setupEventListeners();
            loadExistingData();
        });

        // イベントリスナー設定
        function setupEventListeners() {
            // メンバー選択
            document.getElementById('memberId').addEventListener('change', handleMemberChange);

            // フォーム送信
            document.getElementById('presenterForm').addEventListener('submit', handleSubmit);

            // PDFファイル選択
            document.getElementById('pdfFile').addEventListener('change', handlePdfChange);

            // YouTube URL入力
            document.getElementById('youtubeUrl').addEventListener('input', handleYoutubeChange);
        }

        // 既存データをロード（最新データを取得）
        async function loadExistingData() {
            try {
                const response = await fetch(`${API_BASE}?action=get_latest`);
                const data = await response.json();

                console.log('既存データ読み込み:', data);

                if (data.success && data.data) {
                    // 編集モードに切り替え
                    isEditMode = true;
                    currentData = data.data;

                    console.log('編集モード: currentData =', currentData);

                    // フォームに既存データをセット
                    document.getElementById('memberId').value = currentData.member_id;

                    // プレゼンタイプ設定
                    if (currentData.presentation_type === 'extended') {
                        selectType('extended');
                    } else {
                        selectType('simple');
                    }

                    // PDF情報表示とプレビュー
                    if (currentData.pdf_path) {
                        const pdfInfo = document.getElementById('pdfFileInfo');
                        pdfInfo.innerHTML = `<i class="fas fa-check-circle" style="color: #28a745;"></i> PDFデータ保存済み（${currentData.pdf_path}）`;
                        pdfInfo.style.display = 'block';

                        // PDF画像プレビューを読み込み
                        loadPdfPreviewFromServer(currentData.pdf_path);
                    }

                    // YouTube URL設定
                    if (currentData.youtube_url) {
                        document.getElementById('youtubeUrl').value = currentData.youtube_url;
                        handleYoutubeChange({ target: { value: currentData.youtube_url } });
                    }

                    // メンバープレビュー更新
                    handleMemberChange({ target: { value: currentData.member_id } });

                    // 保存ボタンのテキストを変更
                    document.querySelector('.btn-primary').innerHTML = '<i class="fas fa-save"></i> 更新';
                } else {
                    // 新規作成モード
                    isEditMode = false;
                    currentData = null;
                    document.querySelector('.btn-primary').innerHTML = '<i class="fas fa-save"></i> 保存';
                }
            } catch (error) {
                console.error('既存データ読み込みエラー:', error);
            }
        }

        // メンバー一覧取得
        async function loadMembers() {
            try {
                const response = await fetch('../api/members_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    renderMemberOptions();
                }
            } catch (error) {
                console.error('メンバー読み込みエラー:', error);
                alert('メンバーデータの読み込みに失敗しました');
            }
        }

        // メンバー選択肢表示
        function renderMemberOptions() {
            const select = document.getElementById('memberId');
            select.innerHTML = '<option value="">メンバーを選択してください</option>';

            members.forEach(member => {
                const option = document.createElement('option');
                option.value = member.id;
                option.textContent = `${member.name}${member.company_name ? ' (' + member.company_name + ')' : ''}`;
                select.appendChild(option);
            });
        }

        // メンバー変更時
        function handleMemberChange(e) {
            const memberId = e.target.value;

            if (!memberId) {
                document.getElementById('memberPreview').classList.remove('active');
                resetPreview();
                return;
            }

            currentMember = members.find(m => m.id == memberId);
            if (currentMember) {
                showMemberPreview(currentMember);
                updateSlidePreview(currentMember);
            }
        }

        // メンバープレビュー表示
        function showMemberPreview(member) {
            document.getElementById('previewPhoto').src = member.photo_path || '';
            document.getElementById('previewName').textContent = member.name;
            document.getElementById('previewCompany').textContent = member.company_name || '';
            document.getElementById('previewCategory').textContent = member.category || '';
            document.getElementById('memberPreview').classList.add('active');
        }

        // スライドプレビュー更新
        function updateSlidePreview(member) {
            const slideContent = document.getElementById('slideContent');
            slideContent.innerHTML = `
                ${member.photo_path ? `<img src="${member.photo_path}" class="slide-photo" alt="${member.name}">` : '<div class="slide-photo"></div>'}
                ${member.category ? `<div class="slide-category">${member.category}</div>` : ''}
                <div class="slide-name">${member.name}</div>
                ${member.company_name ? `<div class="slide-company">${member.company_name}</div>` : ''}
            `;
        }

        // PDFプレビュー更新（新規アップロード時）
        function updatePdfPreview() {
            if (convertedImages.length === 0) return;

            const slideContent = document.getElementById('slideContent');
            let html = '<div style="max-height: 600px; overflow-y: auto;">';

            convertedImages.forEach((img, index) => {
                const url = URL.createObjectURL(img.blob);
                html += `
                    <div style="margin-bottom: 20px; text-align: center;">
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">ページ ${index + 1}/${convertedImages.length}</div>
                        <img src="${url}" style="max-width: 100%; border: 1px solid #ddd; border-radius: 4px;" alt="PDF Page ${index + 1}">
                    </div>
                `;
            });

            html += '</div>';
            slideContent.innerHTML = html;
        }

        // サーバーに保存されたPDF画像をプレビュー表示
        async function loadPdfPreviewFromServer(pdfPath) {
            try {
                // pdf_pathがディレクトリの場合、そこから画像を取得
                const response = await fetch(`../${pdfPath}/`);
                const text = await response.text();

                // HTMLから画像ファイル名を抽出（簡易的な方法）
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const links = Array.from(doc.querySelectorAll('a'));
                const imageFiles = links
                    .map(a => a.getAttribute('href'))
                    .filter(href => href && href.endsWith('.png'))
                    .sort();

                if (imageFiles.length > 0) {
                    const slideContent = document.getElementById('slideContent');
                    let html = '<div style="max-height: 600px; overflow-y: auto;">';

                    imageFiles.forEach((filename, index) => {
                        const imagePath = `../${pdfPath}/${filename}`;
                        html += `
                            <div style="margin-bottom: 20px; text-align: center;">
                                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">ページ ${index + 1}/${imageFiles.length}</div>
                                <img src="${imagePath}" style="max-width: 100%; border: 1px solid #ddd; border-radius: 4px;" alt="PDF Page ${index + 1}">
                            </div>
                        `;
                    });

                    html += '</div>';
                    slideContent.innerHTML = html;
                }
            } catch (error) {
                console.error('PDF画像プレビュー読み込みエラー:', error);
            }
        }

        // プレビューリセット
        function resetPreview() {
            document.getElementById('slideContent').innerHTML = `
                <div class="slide-placeholder">
                    <i class="fas fa-presentation" style="font-size: 64px; margin-bottom: 20px;"></i>
                    <p>メンバーを選択すると<br>プレビューが表示されます</p>
                </div>
            `;
        }

        // プレゼンタイプ選択
        function selectType(type) {
            // ラジオボタン更新
            document.getElementById('typeSimple').checked = (type === 'simple');
            document.getElementById('typeExtended').checked = (type === 'extended');

            // ビジュアル更新
            document.querySelectorAll('.type-option').forEach(opt => {
                opt.classList.remove('selected');
            });

            // クリックされた要素を選択状態にする（イベントから呼ばれた場合のみ）
            if (typeof event !== 'undefined' && event.currentTarget) {
                event.currentTarget.classList.add('selected');
            } else {
                // プログラムから呼ばれた場合は、該当するtype-optionを選択
                const typeOptions = document.querySelectorAll('.type-option');
                typeOptions.forEach((opt, index) => {
                    const input = opt.querySelector('input[type="radio"]');
                    if (input && input.value === type) {
                        opt.classList.add('selected');
                    }
                });
            }

            // 拡張オプション表示/非表示
            const extendedOptions = document.getElementById('extendedOptions');
            if (type === 'extended') {
                extendedOptions.classList.add('active');
            } else {
                extendedOptions.classList.remove('active');
            }
        }

        // PDFファイル変更時
        async function handlePdfChange(e) {
            const file = e.target.files[0];
            const info = document.getElementById('pdfFileInfo');

            if (file) {
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                info.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${file.name} (${sizeMB} MB) - 画像変換中...`;
                info.style.display = 'block';

                // PDF→画像変換を実行
                try {
                    await convertPdfToImages(file);
                    info.innerHTML = `<i class="fas fa-check-circle" style="color: #28a745;"></i> ${file.name} (${sizeMB} MB) - 変換完了 (${convertedImages.length}ページ)`;

                    // プレビューを更新
                    updatePdfPreview();
                } catch (error) {
                    console.error('PDF変換エラー:', error);
                    info.innerHTML = `<i class="fas fa-exclamation-circle" style="color: #dc3545;"></i> ${file.name} - 変換失敗`;
                }
            } else {
                info.style.display = 'none';
            }
        }

        // PDF→画像変換（グローバル変数に保存）
        let convertedImages = [];

        async function convertPdfToImages(file) {
            convertedImages = []; // リセット

            const fileReader = new FileReader();

            return new Promise((resolve, reject) => {
                fileReader.onload = async function() {
                    const pdfData = new Uint8Array(this.result);

                    try {
                        const pdf = await pdfjsLib.getDocument(pdfData).promise;
                        const numPages = pdf.numPages;

                        console.log(`PDF変換開始: ${numPages}ページ`);

                        for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                            const page = await pdf.getPage(pageNum);
                            const viewport = page.getViewport({ scale: 2.0 });

                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            canvas.width = viewport.width;
                            canvas.height = viewport.height;

                            await page.render({
                                canvasContext: context,
                                viewport: viewport
                            }).promise;

                            // Canvasを画像（Blob）に変換
                            const blob = await new Promise(resolve => {
                                canvas.toBlob(resolve, 'image/png');
                            });

                            convertedImages.push({
                                pageNum: pageNum,
                                blob: blob,
                                filename: `page-${String(pageNum).padStart(3, '0')}.png`
                            });

                            console.log(`ページ ${pageNum}/${numPages} 変換完了`);
                        }

                        console.log('PDF変換完了:', convertedImages.length, 'ページ');
                        resolve(convertedImages);
                    } catch (error) {
                        reject(error);
                    }
                };

                fileReader.onerror = reject;
                fileReader.readAsArrayBuffer(file);
            });
        }

        // YouTube URL変更時
        function handleYoutubeChange(e) {
            const url = e.target.value.trim();
            const info = document.getElementById('youtubeInfo');

            if (url) {
                const videoId = extractYouTubeId(url);
                if (videoId) {
                    info.innerHTML = `<i class="fas fa-check-circle" style="color: #28a745;"></i> 動画ID: ${videoId}`;
                    info.style.display = 'block';
                } else {
                    info.innerHTML = `<i class="fas fa-exclamation-circle" style="color: #dc3545;"></i> 無効なYouTube URLです`;
                    info.style.display = 'block';
                }
            } else {
                info.style.display = 'none';
            }
        }

        // YouTube ID抽出
        function extractYouTubeId(url) {
            const patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                /^([a-zA-Z0-9_-]{11})$/
            ];

            for (const pattern of patterns) {
                const match = url.match(pattern);
                if (match) return match[1];
            }
            return null;
        }

        // フォーム送信
        async function handleSubmit(e) {
            e.preventDefault();

            const memberId = document.getElementById('memberId').value;
            const presentationType = document.querySelector('input[name="presentationType"]:checked').value;

            if (!memberId) {
                alert('メンバーは必須です');
                return;
            }

            const formData = new FormData();
            // 編集モードの場合は update、新規の場合は create
            formData.append('action', isEditMode ? 'update' : 'create');
            formData.append('member_id', memberId);
            formData.append('presentation_type', presentationType);

            // 拡張版の場合
            if (presentationType === 'extended') {
                // PDF画像をアップロード（変換済みの場合）
                if (convertedImages.length > 0) {
                    console.log(`変換済み画像をアップロード: ${convertedImages.length}枚`);
                    convertedImages.forEach((img, index) => {
                        formData.append(`pdf_images[]`, img.blob, img.filename);
                    });
                }

                const youtubeUrl = document.getElementById('youtubeUrl').value.trim();
                if (youtubeUrl) {
                    formData.append('youtube_url', youtubeUrl);
                }
            }

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert(isEditMode ? '更新しました' : '保存しました');
                    // 変換画像をリセット
                    convertedImages = [];
                    // 編集モードを維持したまま、データを再ロード
                    loadExistingData();
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                console.error('送信エラー:', error);
                alert('送信に失敗しました');
            }
        }

        // フォームリセット
        function resetForm() {
            document.getElementById('presenterForm').reset();
            document.getElementById('memberPreview').classList.remove('active');
            document.getElementById('pdfFileInfo').style.display = 'none';
            document.getElementById('youtubeInfo').style.display = 'none';
            resetPreview();
            selectType('simple');
            loadExistingData();
        }

        // スライドを確認
        function viewSlide(pageNumber) {
            const url = `../index.php#${pageNumber}`;
            window.open(url, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
