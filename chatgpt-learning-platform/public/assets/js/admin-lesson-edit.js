/**
 * 管理画面 - レッスン編集用JavaScript
 */

// レッスンタイプに応じたコンテンツエディタの切り替え
function toggleContentEditor() {
    const type = document.getElementById('type').value;
    document.querySelectorAll('.type-editor').forEach(el => el.classList.add('hidden'));
    document.getElementById('editor-' + type).classList.remove('hidden');
}

// スライド追加
function addSlide() {
    const container = document.getElementById('slides-container');
    const countInput = document.getElementById('slide_count');
    const count = parseInt(countInput.value) + 1;
    countInput.value = count;

    const slideHtml = `
        <div class="slide-item">
            <h4>スライド ${count}</h4>
            <div class="form-group">
                <label class="form-label">タイトル</label>
                <input type="text" name="slide_${count}_title" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">内容</label>
                <textarea name="slide_${count}_content" class="form-textarea"></textarea>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', slideHtml);
}

// 問題追加（簡易版）
function addQuestion() {
    const container = document.getElementById('questions-container');
    const countInput = document.getElementById('question_count');
    const count = parseInt(countInput.value) + 1;
    countInput.value = count;

    const questionHtml = `
        <div class="question-item">
            <h4>問題 ${count}</h4>
            <div class="form-group">
                <label class="form-label">問題文</label>
                <textarea name="question_${count}_text" class="form-textarea"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">タイプ</label>
                <select name="question_${count}_type" class="form-select">
                    <option value="multiple_choice">選択式</option>
                    <option value="text">記述式</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">選択肢1</label>
                <input type="text" name="question_${count}_option_1" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">選択肢2</label>
                <input type="text" name="question_${count}_option_2" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">選択肢3</label>
                <input type="text" name="question_${count}_option_3" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">選択肢4</label>
                <input type="text" name="question_${count}_option_4" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">正解（1〜4）</label>
                <input type="number" name="question_${count}_correct" class="form-input" value="1" min="1" max="4">
            </div>
            <div class="form-group">
                <label class="form-label">解説</label>
                <textarea name="question_${count}_explanation" class="form-textarea"></textarea>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', questionHtml);
}

// 初期表示
toggleContentEditor();
