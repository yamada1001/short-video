/**
 * BNI Slide System - HTML/CSS Slide Generator
 * Modern Enterprise Presentation Design
 */

/**
 * Generate all slides from data using HTML/CSS
 */
async function generateSVGSlides(data, stats) {
  const slideContainer = document.getElementById('slideContainer');
  const today = new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let slides = '';

  // ============================================
  // Slide 1: Title Slide
  // ============================================
  slides += `
    <section class="title-slide-modern">
      <div class="title-content">
        <h1 class="main-title">BNI週次レポート</h1>
        <p class="date-text">${today}</p>
        <div class="brand-footer">
          <span>Givers Gain® | BNI Slide System</span>
        </div>
      </div>
    </section>
  `;

  // ============================================
  // Slide 2: Summary Slide
  // ============================================
  slides += `
    <section class="summary-slide-modern">
      <h2 class="section-title-center">今週のサマリー</h2>
      <div class="stats-grid-modern">
        <div class="stat-card-modern">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <div class="stat-number">${stats.total_visitors || 0}</div>
          <div class="stat-label">ビジター紹介数</div>
        </div>
        <div class="stat-card-modern">
          <div class="stat-icon stat-icon-green"><i class="fas fa-yen-sign"></i></div>
          <div class="stat-number stat-number-green">¥${formatNumber(stats.total_referral_amount || 0)}</div>
          <div class="stat-label">総リファーラル金額</div>
        </div>
        <div class="stat-card-modern">
          <div class="stat-icon stat-icon-blue"><i class="fas fa-check-circle"></i></div>
          <div class="stat-number stat-number-blue">${stats.total_attendance || 0}</div>
          <div class="stat-label">出席者数</div>
        </div>
        <div class="stat-card-modern">
          <div class="stat-icon stat-icon-orange"><i class="fas fa-handshake"></i></div>
          <div class="stat-number stat-number-orange">${stats.total_one_to_one || 0}</div>
          <div class="stat-label">ワンツーワン実施数</div>
        </div>
        <div class="stat-card-modern">
          <div class="stat-icon stat-icon-purple"><i class="fas fa-clipboard-check"></i></div>
          <div class="stat-number stat-number-purple">${data.length || 0}</div>
          <div class="stat-label">回答者数</div>
        </div>
      </div>
    </section>
  `;

  // ============================================
  // Slide 3-8: Existing HTML/CSS slides
  // ============================================

  // Visitor Introductions
  if (data.length > 0) {
    slides += `
      <section>
        <h2>ビジター紹介一覧</h2>
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

    data.forEach(row => {
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

  // Referral Amount Breakdown
  slides += `
    <section>
      <h2>リファーラル金額内訳</h2>
      <div class="highlight-box">
        <h3>総額: <span class="currency">¥${formatNumber(stats.total_referral_amount)}</span></h3>
      </div>
  `;

  if (Object.keys(stats.categories).length > 0) {
    slides += `<div style="margin-top: 40px;">`;
    Object.entries(stats.categories).forEach(([category, amount]) => {
      const percentage = stats.total_referral_amount > 0
        ? ((amount / stats.total_referral_amount) * 100).toFixed(1)
        : 0;

      slides += `
        <div style="margin-bottom: 20px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
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

  // Member Contributions
  if (Object.keys(stats.members).length > 0) {
    slides += `
      <section>
        <h2>メンバー別貢献度</h2>
        <div class="member-list">
    `;

    Object.entries(stats.members).forEach(([member, memberStats]) => {
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

  // Detailed Referral List
  if (data.length > 0) {
    slides += `
      <section>
        <h2>リファーラル詳細</h2>
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

    data.forEach(row => {
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

  // Activity Summary
  slides += `
    <section>
      <h2>アクティビティサマリー</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">${stats.total_thanks_slips}</div>
          <div class="stat-label">サンクスリップ提出数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${stats.total_one_to_one}</div>
          <div class="stat-label">ワンツーワン実施数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${stats.total_attendance}</div>
          <div class="stat-label">今週の出席者数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${data.length}</div>
          <div class="stat-label">回答者数</div>
        </div>
      </div>
    </section>
  `;

  // ============================================
  // Slide 9: Thank You Slide
  // ============================================
  slides += `
    <section class="title-slide-modern thankyou-slide">
      <div class="title-content">
        <h1 class="main-title">ありがとうございました</h1>
        <p class="date-text">来週もよろしくお願いします</p>
        <div class="brand-footer">
          <span>Givers Gain®</span>
        </div>
      </div>
    </section>
  `;

  // Insert slides into container
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
