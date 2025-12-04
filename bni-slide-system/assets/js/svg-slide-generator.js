/**
 * BNI Slide System - SVG Slide Generator
 * 4K SVG Templates with Dynamic Data Embedding
 */

/**
 * Load SVG template and replace placeholders with data
 */
async function loadSVGTemplate(templatePath, data) {
  try {
    const response = await fetch(templatePath);
    let svgContent = await response.text();

    // Replace all {{variable}} placeholders with actual data
    for (const [key, value] of Object.entries(data)) {
      const regex = new RegExp(`{{${key}}}`, 'g');
      svgContent = svgContent.replace(regex, value);
    }

    return svgContent;
  } catch (error) {
    console.error('Failed to load SVG template:', error);
    return null;
  }
}

/**
 * Generate all slides from data using SVG templates
 */
async function generateSVGSlides(data, stats) {
  const slideContainer = document.getElementById('slideContainer');
  const today = new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  // Determine API base path
  const isInAdminDir = window.location.pathname.includes('/admin/');
  const basePath = isInAdminDir ? '../' : '';

  let slides = '';

  // ============================================
  // Slide 1: Title Slide
  // ============================================
  const titleSVG = await loadSVGTemplate(
    basePath + 'assets/svg/title-slide.svg',
    { date: today }
  );

  if (titleSVG) {
    slides += `
      <section data-background-color="#CF2030">
        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
          ${titleSVG}
        </div>
      </section>
    `;
  }

  // ============================================
  // Slide 2: Summary Slide
  // ============================================
  const summarySVG = await loadSVGTemplate(
    basePath + 'assets/svg/summary-slide.svg',
    {
      total_visitors: stats.total_visitors || 0,
      total_referral_amount: formatNumber(stats.total_referral_amount || 0),
      total_attendance: stats.total_attendance || 0,
      total_one_to_one: stats.total_one_to_one || 0,
      response_count: data.length || 0
    }
  );

  if (summarySVG) {
    slides += `
      <section>
        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
          ${summarySVG}
        </div>
      </section>
    `;
  }

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
  const thankYouSVG = await loadSVGTemplate(
    basePath + 'assets/svg/title-slide.svg',
    { date: 'ありがとうございました' }
  );

  if (thankYouSVG) {
    const modifiedThankYouSVG = thankYouSVG
      .replace('BNI週次レポート', 'ありがとうございました')
      .replace('Givers Gain® | BNI Slide System', '来週もよろしくお願いします');

    slides += `
      <section data-background-color="#CF2030">
        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
          ${modifiedThankYouSVG}
        </div>
      </section>
    `;
  }

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
