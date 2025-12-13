/**
 * Networking Learning Corner Management
 * ネットワーキング学習コーナー管理画面のJavaScript
 */

let membersData = [];

document.addEventListener('DOMContentLoaded', function() {
  // Load members from JSON
  loadMembers();

  // Load weeks for dropdown
  loadWeeks();

  // Event listeners
  document.getElementById('loadDataBtn').addEventListener('click', loadPresenterData);
  document.getElementById('presenterForm').addEventListener('submit', handleSubmit);
  document.getElementById('clearBtn').addEventListener('click', clearSelection);
});

/**
 * Load members from JSON file
 */
async function loadMembers() {
  try {
    const response = await fetch('../data/members.json');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    membersData = data.members || [];

    // Populate member select dropdown
    const memberSelect = document.getElementById('memberSelect');
    memberSelect.innerHTML = '<option value="">-- メンバーを選択 --</option>';

    membersData.forEach(member => {
      const option = document.createElement('option');
      option.value = member;
      option.textContent = member;
      memberSelect.appendChild(option);
    });

  } catch (error) {
    console.error('Error loading members:', error);
    showMessage('メンバーリストの読み込みに失敗しました: ' + error.message, 'error');
  }
}

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
      loadPresenterData();
    }

  } catch (error) {
    console.error('Error loading weeks:', error);
    showMessage('週リストの読み込みに失敗しました: ' + error.message, 'error');
  }
}

/**
 * Load presenter data for selected week
 */
async function loadPresenterData() {
  const weekDate = document.getElementById('weekSelector').value;
  if (!weekDate) {
    showMessage('週を選択してください', 'error');
    return;
  }

  try {
    const response = await fetch(`../api_load_networking_learning.php?week_date=${weekDate}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (!result.success) {
      // No data found - clear the form
      document.getElementById('memberSelect').value = '';
      document.getElementById('currentPresenterSection').style.display = 'none';
      return;
    }

    // Display current presenter
    if (result.presenter) {
      document.getElementById('currentPresenterName').textContent = result.presenter.presenter_name;
      document.getElementById('currentPresenterSection').style.display = 'block';

      // Set the select value
      document.getElementById('memberSelect').value = result.presenter.presenter_name;
    } else {
      document.getElementById('memberSelect').value = '';
      document.getElementById('currentPresenterSection').style.display = 'none';
    }

  } catch (error) {
    console.error('Error loading presenter data:', error);
    showMessage('担当者データの読み込みに失敗しました: ' + error.message, 'error');
  }
}

/**
 * Handle form submission
 */
async function handleSubmit(e) {
  e.preventDefault();

  const weekDate = document.getElementById('weekSelector').value;
  const memberName = document.getElementById('memberSelect').value;

  if (!weekDate) {
    showMessage('週を選択してください', 'error');
    return;
  }

  if (!memberName) {
    showMessage('担当メンバーを選択してください', 'error');
    return;
  }

  const formData = {
    week_date: weekDate,
    presenter_name: memberName
  };

  try {
    const response = await fetch('../api_save_networking_learning.php', {
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
      throw new Error(result.error || 'Failed to save presenter');
    }

    // Success
    showMessage('担当者を保存しました', 'success');

    // Reload presenter data
    loadPresenterData();

  } catch (error) {
    console.error('Error saving presenter:', error);
    showMessage('担当者の保存に失敗しました: ' + error.message, 'error');
  }
}

/**
 * Clear selection
 */
function clearSelection() {
  document.getElementById('memberSelect').value = '';
  document.getElementById('currentPresenterSection').style.display = 'none';
}

/**
 * Show message to user
 */
function showMessage(text, type) {
  const container = document.getElementById('messageContainer');
  const messageDiv = document.createElement('div');
  messageDiv.className = `message ${type}`;
  messageDiv.textContent = text;

  container.innerHTML = '';
  container.appendChild(messageDiv);

  // Auto-hide after 5 seconds
  setTimeout(() => {
    messageDiv.style.display = 'none';
  }, 5000);
}
