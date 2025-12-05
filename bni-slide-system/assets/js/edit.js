/**
 * BNI Slide System - Edit Functionality
 */

let originalData = [];
let currentData = [];

// Determine API base path based on current location
const isInAdminDir = window.location.pathname.includes('/admin/');
const apiBasePath = isInAdminDir ? '../' : '';

document.addEventListener('DOMContentLoaded', async function() {
  await loadData();
});

/**
 * Load CSV data
 */
async function loadData() {
  try {
    const response = await fetch(apiBasePath + 'api_load.php');
    const result = await response.json();

    if (!result.success) {
      throw new Error(result.message || 'データの読み込みに失敗しました');
    }

    originalData = result.data || [];
    currentData = JSON.parse(JSON.stringify(originalData)); // Deep copy

    renderTable();
    updateDataCount();

  } catch (error) {
    console.error('Error loading data:', error);
    showMessage('error', error.message);
    document.getElementById('tableBody').innerHTML = `
      <tr>
        <td colspan="11" class="empty-state">
          <h3>エラー</h3>
          <p>${error.message}</p>
          <button onclick="location.reload()" class="btn btn-primary">再読み込み</button>
        </td>
      </tr>
    `;
  }
}

/**
 * Render table
 */
function renderTable() {
  const tbody = document.getElementById('tableBody');

  if (currentData.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="11" class="empty-state">
          <h3>データがありません</h3>
          <p>まずはアンケートフォームから回答を送信してください</p>
        </td>
      </tr>
    `;
    return;
  }

  let html = '';
  currentData.forEach((row, index) => {
    html += `
      <tr data-index="${index}">
        <td>${index + 1}</td>
        <td>
          <input type="date" value="${escapeHtml(row['入力日'] || '')}"
                 onchange="updateRow(${index}, '入力日', this.value)">
        </td>
        <td>
          <input type="text" value="${escapeHtml(row['紹介者名'] || '')}"
                 onchange="updateRow(${index}, '紹介者名', this.value)">
        </td>
        <td>
          <input type="email" value="${escapeHtml(row['メールアドレス'] || '')}"
                 onchange="updateRow(${index}, 'メールアドレス', this.value)">
        </td>
        <td>
          <input type="text" value="${escapeHtml(row['ビジター名'] || '')}"
                 onchange="updateRow(${index}, 'ビジター名', this.value)">
        </td>
        <td>
          <input type="text" value="${escapeHtml(row['ビジター会社名'] || '')}"
                 onchange="updateRow(${index}, 'ビジター会社名', this.value)">
        </td>
        <td>
          <input type="text" value="${escapeHtml(row['案件名'] || '')}"
                 onchange="updateRow(${index}, '案件名', this.value)">
        </td>
        <td>
          <input type="number" value="${row['リファーラル金額'] || 0}"
                 onchange="updateRow(${index}, 'リファーラル金額', this.value)">
        </td>
        <td>
          <select onchange="updateRow(${index}, 'カテゴリ', this.value)">
            <option value="成約" ${row['カテゴリ'] === '成約' ? 'selected' : ''}>成約</option>
            <option value="商談中" ${row['カテゴリ'] === '商談中' ? 'selected' : ''}>商談中</option>
            <option value="見込み" ${row['カテゴリ'] === '見込み' ? 'selected' : ''}>見込み</option>
            <option value="その他" ${row['カテゴリ'] === 'その他' ? 'selected' : ''}>その他</option>
          </select>
        </td>
        <td>
          <select onchange="updateRow(${index}, '出席状況', this.value)">
            <option value="出席" ${row['出席状況'] === '出席' ? 'selected' : ''}>出席</option>
            <option value="代理出席" ${row['出席状況'] === '代理出席' ? 'selected' : ''}>代理出席</option>
            <option value="欠席" ${row['出席状況'] === '欠席' ? 'selected' : ''}>欠席</option>
          </select>
        </td>
        <td>
          <button onclick="deleteRow(${index})" class="btn btn-danger btn-small" style="width: 100%;">削除</button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
}

/**
 * Update row data
 */
function updateRow(index, field, value) {
  currentData[index][field] = value;
  console.log(`Updated row ${index}, field ${field}:`, value);
}

/**
 * Delete row
 */
function deleteRow(index) {
  if (!confirm('この行を削除してもよろしいですか？')) {
    return;
  }

  currentData.splice(index, 1);
  renderTable();
  updateDataCount();
  showMessage('info', '行を削除しました（まだ保存されていません）');
}

/**
 * Save changes
 */
async function saveChanges() {
  if (!confirm('変更を保存してもよろしいですか？CSVファイルが上書きされます。')) {
    return;
  }

  try {
    const response = await fetch(apiBasePath + 'api_update.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        data: currentData
      })
    });

    const result = await response.json();

    if (result.success) {
      showMessage('success', '変更を保存しました！');
      originalData = JSON.parse(JSON.stringify(currentData));
    } else {
      throw new Error(result.message || '保存に失敗しました');
    }
  } catch (error) {
    console.error('Error saving data:', error);
    showMessage('error', error.message);
  }
}

/**
 * Update data count
 */
function updateDataCount() {
  const countElem = document.getElementById('dataCount');
  countElem.textContent = `全${currentData.length}件のデータ`;
}

/**
 * Show message
 */
function showMessage(type, text) {
  const messageDiv = document.getElementById('message');
  messageDiv.className = `message message-${type} show`;
  messageDiv.textContent = text;

  setTimeout(() => {
    messageDiv.classList.remove('show');
  }, 5000);
}

/**
 * Escape HTML
 */
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return String(text).replace(/[&<>"']/g, m => map[m]);
}

/**
 * Export to Excel
 */
async function exportToExcel() {
  try {
    // Get the current week parameter from URL or use latest week
    const urlParams = new URLSearchParams(window.location.search);
    let week = urlParams.get('week');

    // If no week specified, get the latest week from API
    if (!week) {
      const weeksResponse = await fetch(apiBasePath + 'api_list_weeks.php');
      const weeksData = await weeksResponse.json();

      if (weeksData.success && weeksData.weeks.length > 0) {
        week = weeksData.weeks[0].value; // Get the latest week
      } else {
        throw new Error('週データが見つかりません');
      }
    }

    // Open download URL in new window
    const downloadUrl = apiBasePath + 'api_export_excel.php?week=' + encodeURIComponent(week);
    window.open(downloadUrl, '_blank');

    showMessage('success', 'Excelファイルをダウンロードしています...');

  } catch (error) {
    console.error('Excel export error:', error);
    showMessage('error', 'エクスポートに失敗しました: ' + error.message);
  }
}
