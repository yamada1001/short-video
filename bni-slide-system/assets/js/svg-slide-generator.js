/**
 * BNI Slide System - Simple HTML Slide Generator
 * Using Reveal.js White Theme + BNI Colors
 */

/**
 * Generate all slides from data
 */
async function generateSVGSlides(data, stats) {
  const slideContainer = document.getElementById('slideContainer');
  const today = new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let slides = '';

  // Slide 1: Title
  slides += `
    <section class="title-slide">
      <h1>BNI週次レポート</h1>
      <p class="subtitle">${today}</p>
      <p class="branding">Givers Gain® | BNI Slide System</p>
    </section>
  `;

  // Slide 2: Summary
  slides += `
    <section>
      <h2>今週のサマリー</h2>
      <div class="stats-badge-container">
        <div class="stat-badge">
          <i class="fas fa-users stat-badge-icon"></i>
          <span class="stat-badge-number">${stats.total_visitors || 0}</span>
          <span class="stat-badge-label">ビジター紹介</span>
        </div>
        <div class="stat-badge">
          <i class="fas fa-yen-sign stat-badge-icon"></i>
          <span class="stat-badge-number">¥${formatNumber(stats.total_referral_amount || 0)}</span>
          <span class="stat-badge-label">リファーラル金額</span>
        </div>
        <div class="stat-badge">
          <i class="fas fa-check-circle stat-badge-icon"></i>
          <span class="stat-badge-number">${stats.total_attendance || 0}</span>
          <span class="stat-badge-label">出席者数</span>
        </div>
        <div class="stat-badge">
          <i class="fas fa-handshake stat-badge-icon"></i>
          <span class="stat-badge-number">${stats.total_one_to_one || 0}</span>
          <span class="stat-badge-label">121実施数</span>
        </div>
      </div>
    </section>
  `;

  // Slide 3: Visitor Introductions (split into multiple pages if needed)
  if (data.length > 0) {
    const visitorsWithData = data.filter(row => row['ビジター名']);
    const itemsPerPage = 5;
    const totalPages = Math.ceil(visitorsWithData.length / itemsPerPage);

    for (let page = 0; page < totalPages; page++) {
      const start = page * itemsPerPage;
      const end = start + itemsPerPage;
      const pageData = visitorsWithData.slice(start, end);

      slides += `
        <section>
          <h2>ビジター紹介一覧${totalPages > 1 ? ` (${page + 1}/${totalPages})` : ''}</h2>
          <table>
            <thead>
              <tr>
                <th>紹介者</th>
                <th>ビジター名</th>
                <th>業種</th>
                <th>紹介日</th>
              </tr>
            </thead>
            <tbody>
      `;

      pageData.forEach(row => {
        slides += `
          <tr>
            <td>${escapeHtml(row['紹介者名'] || '')}</td>
            <td><strong>${escapeHtml(row['ビジター名'] || '')}</strong></td>
            <td>${escapeHtml(row['ビジター業種'] || '-')}</td>
            <td>${escapeHtml(row['紹介日'] || '')}</td>
          </tr>
        `;
      });

      slides += `
            </tbody>
          </table>
        </section>
      `;
    }
  }

  // Slide 4: Referral Amount Breakdown
  slides += `
    <section>
      <h2>リファーラル金額内訳</h2>
      <div class="highlight-box">
        <h3>総額: <span class="currency">¥${formatNumber(stats.total_referral_amount)}</span></h3>
      </div>
  `;

  if (Object.keys(stats.categories).length > 0) {
    slides += `<div style="margin-top: 30px; max-width: 85%; margin-left: auto; margin-right: auto;">`;
    Object.entries(stats.categories).forEach(([category, amount]) => {
      const percentage = stats.total_referral_amount > 0
        ? ((amount / stats.total_referral_amount) * 100).toFixed(1)
        : 0;

      slides += `
        <div style="margin-bottom: 16px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.85em;">
            <span style="font-weight: 600;">${escapeHtml(category)}</span>
            <span style="color: #27AE60; font-weight: 700;">¥${formatNumber(amount)}</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" style="width: ${percentage}%;">
              ${percentage}%
            </div>
          </div>
        </div>
      `;
    });
    slides += `</div>`;
  }

  slides += `</section>`;

  // Slide 5: Member Contributions (split into multiple pages if needed)
  if (Object.keys(stats.members).length > 0) {
    const memberEntries = Object.entries(stats.members);
    const itemsPerPage = 6;
    const totalPages = Math.ceil(memberEntries.length / itemsPerPage);

    for (let page = 0; page < totalPages; page++) {
      const start = page * itemsPerPage;
      const end = start + itemsPerPage;
      const pageMembers = memberEntries.slice(start, end);

      slides += `
        <section>
          <h2>メンバー別貢献度${totalPages > 1 ? ` (${page + 1}/${totalPages})` : ''}</h2>
          <div class="member-list">
      `;

      pageMembers.forEach(([member, memberStats]) => {
        slides += `
          <div class="member-card">
            <div class="member-name">${escapeHtml(member)}</div>
            <div class="member-stats">
              <p>ビジター: <strong>${memberStats.visitors}名</strong></p>
              <p>リファーラル: <strong>¥${formatNumber(memberStats.referral_amount)}</strong></p>
            </div>
          </div>
        `;
      });

      slides += `
          </div>
        </section>
      `;
    }
  }

  // Slide 6: Detailed Referral List (split into multiple pages if needed)
  if (data.length > 0) {
    const itemsPerPage = 5;
    const totalPages = Math.ceil(data.length / itemsPerPage);

    for (let page = 0; page < totalPages; page++) {
      const start = page * itemsPerPage;
      const end = start + itemsPerPage;
      const pageData = data.slice(start, end);

      slides += `
        <section>
          <h2>リファーラル詳細${totalPages > 1 ? ` (${page + 1}/${totalPages})` : ''}</h2>
          <table>
            <thead>
              <tr>
                <th>案件名</th>
                <th>金額</th>
                <th>カテゴリ</th>
                <th>提供者</th>
              </tr>
            </thead>
            <tbody>
      `;

      pageData.forEach(row => {
        const amount = parseInt(row['リファーラル金額'] || 0);
        slides += `
          <tr>
            <td>${escapeHtml(row['案件名'] || '')}</td>
            <td style="color: #27AE60; font-weight: 700;">¥${formatNumber(amount)}</td>
            <td>${escapeHtml(row['カテゴリ'] || '')}</td>
            <td>${escapeHtml(row['リファーラル提供者'] || '-')}</td>
          </tr>
        `;
      });

      slides += `
            </tbody>
          </table>
        </section>
      `;
    }
  }

  // Slide 7: Activity Summary
  slides += `
    <section>
      <h2>アクティビティサマリー</h2>
      <div class="stats-badge-container">
        <div class="stat-badge">
          <i class="fas fa-clipboard-check stat-badge-icon"></i>
          <span class="stat-badge-number">${stats.total_thanks_slips}</span>
          <span class="stat-badge-label">サンクスリップ</span>
        </div>
        <div class="stat-badge">
          <i class="fas fa-handshake stat-badge-icon"></i>
          <span class="stat-badge-number">${stats.total_one_to_one}</span>
          <span class="stat-badge-label">121実施数</span>
        </div>
        <div class="stat-badge">
          <i class="fas fa-check-circle stat-badge-icon"></i>
          <span class="stat-badge-number">${stats.total_attendance}</span>
          <span class="stat-badge-label">出席者数</span>
        </div>
        <div class="stat-badge">
          <i class="fas fa-users stat-badge-icon"></i>
          <span class="stat-badge-number">${data.length}</span>
          <span class="stat-badge-label">回答者数</span>
        </div>
      </div>
    </section>
  `;

  // Slide 8: Thank You
  slides += `
    <section class="title-slide">
      <h1>ありがとうございました</h1>
      <p class="subtitle">来週もよろしくお願いします</p>
      <p class="branding">Givers Gain®</p>
    </section>
  `;

  slideContainer.innerHTML = slides;
}

/**
 * Format number with commas
 */
function formatNumber(num) {
  return parseInt(num).toLocaleString('ja-JP');
}

/**
 * Escape HTML special characters
 */
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
