/**
 * Visitor Introduction Management
 * ビジターご紹介管理画面のJavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
  // Load weeks for dropdown
  loadWeeks();

  // Load members for attendant dropdown
  loadMembers();

  // Event listeners
  document.getElementById('loadDataBtn').addEventListener('click', loadVisitors);
  document.getElementById('visitorForm').addEventListener('submit', handleSubmit);
});

/**
 * Load weeks into dropdown
 */
async function loadWeeks() {
  try {
    const response = await fetch('../api_list_weeks.php');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (!result.success) {
      throw new Error(result.error || 'Failed to load weeks');
    }

    const weekSelector = document.getElementById('weekSelector');
    weekSelector.innerHTML = '';

    result.weeks.forEach(week => {
      const option = document.createElement('option');
      option.value = week.value;
      option.textContent = week.label;
      weekSelector.appendChild(option);
    });

    // Auto-load data for the first week
    if (result.weeks.length > 0) {
      loadVisitors();
    }

  } catch (error) {
    console.error('Error loading weeks:', error);
    alert('週リストの読み込みに失敗しました: ' + error.message);
  }
}

/**
 * Load visitors for selected week
 */
async function loadVisitors() {
  const weekDate = document.getElementById('weekSelector').value;
  if (!weekDate) {
    alert('週を選択してください');
    return;
  }

  try {
    const response = await fetch(`../api_load_visitors.php?week_date=${weekDate}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (!result.success) {
      throw new Error(result.error || 'Failed to load visitors');
    }

    displayVisitors(result.visitors);

  } catch (error) {
    console.error('Error loading visitors:', error);
    alert('ビジターデータの読み込みに失敗しました: ' + error.message);
  }
}

/**
 * Display visitors in table
 */
function displayVisitors(visitors) {
  const container = document.getElementById('visitorListContainer');

  if (!visitors || visitors.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <i class="fas fa-user-friends"></i>
        <p>この週にはビジターの登録がありません</p>
      </div>
    `;
    return;
  }

  let html = `
    <table class="visitor-table">
      <thead>
        <tr>
          <th>お名前</th>
          <th>会社名</th>
          <th>専門分野</th>
          <th>スポンサー</th>
          <th>アテンド</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
  `;

  visitors.forEach(visitor => {
    const isReadOnly = visitor.source === 'survey';
    const deleteButton = isReadOnly
      ? `<span class="badge badge-info">アンケート由来</span>`
      : `<button class="btn btn-danger" onclick="deleteVisitor(${visitor.id}, '${visitor.source}')">
           <i class="fas fa-trash"></i> 削除
         </button>`;

    html += `
      <tr>
        <td><strong>${escapeHtml(visitor.visitor_name)}</strong></td>
        <td>${escapeHtml(visitor.company || '-')}</td>
        <td>${escapeHtml(visitor.specialty || '-')}</td>
        <td>${escapeHtml(visitor.sponsor)}</td>
        <td>${escapeHtml(visitor.attendant)}</td>
        <td>${deleteButton}</td>
      </tr>
    `;
  });

  html += `
      </tbody>
    </table>
  `;

  container.innerHTML = html;
}

/**
 * Handle form submission
 */
async function handleSubmit(e) {
  e.preventDefault();

  const weekDate = document.getElementById('weekSelector').value;
  if (!weekDate) {
    alert('週を選択してください');
    return;
  }

  const formData = {
    week_date: weekDate,
    visitor_name: document.getElementById('visitorName').value.trim(),
    company: document.getElementById('company').value.trim(),
    specialty: document.getElementById('specialty').value.trim(),
    sponsor: document.getElementById('sponsor').value.trim(),
    attendant: document.getElementById('attendant').value.trim()
  };

  // Validation
  if (!formData.visitor_name) {
    alert('お名前を入力してください');
    return;
  }

  if (!formData.sponsor) {
    alert('スポンサー（紹介者）を入力してください');
    return;
  }

  if (!formData.attendant) {
    alert('アテンド（同行者）を入力してください');
    return;
  }

  try {
    const response = await fetch('../api_save_visitor.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (!result.success) {
      throw new Error(result.error || 'Failed to save visitor');
    }

    // Success
    alert('ビジターを追加しました');

    // Clear form
    document.getElementById('visitorForm').reset();

    // Reload visitor list
    loadVisitors();

  } catch (error) {
    console.error('Error saving visitor:', error);
    alert('ビジターの保存に失敗しました: ' + error.message);
  }
}

/**
 * Delete visitor (only admin-managed visitors, not survey-based)
 */
async function deleteVisitor(id, source) {
  // Prevent deletion of survey-based visitors
  if (source === 'survey') {
    alert('アンケート由来のビジターは削除できません');
    return;
  }

  if (!confirm('このビジターを削除してもよろしいですか？')) {
    return;
  }

  try {
    const response = await fetch('../api_delete_visitor.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: id })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (!result.success) {
      throw new Error(result.error || 'Failed to delete visitor');
    }

    // Success
    alert('ビジターを削除しました');

    // Reload visitor list
    loadVisitors();

  } catch (error) {
    console.error('Error deleting visitor:', error);
    alert('ビジターの削除に失敗しました: ' + error.message);
  }
}

/**
 * Load members for attendant dropdown
 */
async function loadMembers() {
  try {
    const response = await fetch('/bni-slide-system/data/members.json');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    const members = Object.values(data.users || {});

    // Populate attendant dropdown
    const attendantSelect = document.getElementById('attendant');
    attendantSelect.innerHTML = '<option value="">選択してください</option>';

    members.forEach(member => {
      const option = document.createElement('option');
      option.value = member.name;
      option.textContent = member.name;
      attendantSelect.appendChild(option);
    });

    // Populate sponsor dropdown
    const sponsorSelect = document.getElementById('sponsor');
    sponsorSelect.innerHTML = '<option value="">選択してください</option>';

    members.forEach(member => {
      const option = document.createElement('option');
      option.value = member.name;
      option.textContent = member.name;
      sponsorSelect.appendChild(option);
    });

  } catch (error) {
    console.error('Error loading members:', error);
    alert('メンバー一覧の読み込みに失敗しました: ' + error.message);
  }
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
  if (text == null) return '';
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}
