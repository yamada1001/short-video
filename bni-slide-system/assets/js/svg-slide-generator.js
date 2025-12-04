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
      <div class="stats-simple">
        <div class="stat-item">
          <div class="stat-item-number">${stats.total_visitors || 0}</div>
          <div class="stat-item-label">ビジター紹介数</div>
        </div>
        <div class="stat-item">
          <div class="stat-item-number">¥${formatNumber(stats.total_referral_amount || 0)}</div>
          <div class="stat-item-label">総リファーラル金額</div>
        </div>
        <div class="stat-item">
          <div class="stat-item-number">${stats.total_attendance || 0}</div>
          <div class="stat-item-label">出席者数</div>
        </div>
        <div class="stat-item">
          <div class="stat-item-number">${stats.total_one_to_one || 0}</div>
          <div class="stat-item-label">121実施数</div>
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
                <th>お名前</th>
                <th>会社名（屋号）</th>
                <th>業種</th>
              </tr>
            </thead>
            <tbody>
      `;

      pageData.forEach(row => {
        // Parse visitor name - format might be "会社名 名前様" or just "名前様"
        const fullVisitorName = row['ビジター名'] || '';
        const visitorCompany = row['ビジター会社名'] || '';

        // If company name is not in separate field, try to extract it
        let displayName = fullVisitorName;
        let displayCompany = visitorCompany;

        // If no separate company field and name contains space, split it
        if (!visitorCompany && fullVisitorName.includes(' ')) {
          const parts = fullVisitorName.split(' ');
          displayCompany = parts[0];
          displayName = parts.slice(1).join(' ');
        }

        slides += `
          <tr>
            <td>${escapeHtml(row['紹介者名'] || '')}</td>
            <td><strong>${escapeHtml(displayName)}</strong></td>
            <td>${escapeHtml(displayCompany || '-')}</td>
            <td>${escapeHtml(row['ビジター業種'] || '-')}</td>
          </tr>
        `;
      });

      slides += `
            </tbody>
          </table>
        </section>
      `;
    }

    // Slide 3.5: Visitor Introduction Slides (for self-introduction & feedback)
    visitorsWithData.forEach(row => {
      const visitorName = escapeHtml(row['ビジター名'] || '');
      const visitorCompany = escapeHtml(row['ビジター会社名'] || '');
      const visitorIndustry = escapeHtml(row['ビジター業種'] || '');

      slides += `
        <section class="visitor-slide">
          <h2 class="visitor-name">${visitorName}</h2>
          <p class="visitor-company">${visitorCompany}</p>
          <p class="visitor-industry">${visitorIndustry}</p>
          <div class="visitor-prompt">
            <p>自己紹介・感想をお願いします</p>
          </div>
        </section>
      `;
    });
  }

  // Slide 4: Referral Amount Breakdown
  slides += `
    <section data-auto-animate>

      <h2>リファーラル金額内訳</h2>
      <div class="highlight-box">
        <h3>総額: <span class="currency animate-number" data-value="${stats.total_referral_amount}">¥0</span></h3>
      </div>
  `;

  if (Object.keys(stats.categories).length > 0) {
    slides += `<div class="progress-section">`;
    Object.entries(stats.categories).forEach(([category, amount]) => {
      const percentage = stats.total_referral_amount > 0
        ? ((amount / stats.total_referral_amount) * 100).toFixed(1)
        : 0;

      slides += `
        <div class="progress-item">
          <div class="progress-item-header">
            <span class="progress-item-label">${escapeHtml(category)}</span>
            <span class="progress-item-value animate-number" data-value="${amount}">¥0</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-width="${percentage}" style="width: 0%;"></div>
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
          <div class="member-grid">
      `;

      pageMembers.forEach(([member, memberStats]) => {
        slides += `
          <div class="member-item">
            <div class="member-item-name">${escapeHtml(member)}</div>
            <div class="member-item-stats">
              <div>ビジター: <strong>${memberStats.visitors}名</strong></div>
            </div>
          </div>
        `;
      });

      slides += `
          </div>
        </section>
      `;
    }

    // Slide 5.5: Member Pitch Countdown (30 seconds for each member)
    const allMembers = Object.keys(stats.members);
    allMembers.forEach(member => {
      slides += `
        <section class="pitch-slide" data-member="${escapeHtml(member)}">
          <h2 class="pitch-member-name">${escapeHtml(member)}</h2>
          <p class="pitch-label">30秒ピッチ</p>
          <div class="countdown-timer" data-seconds="30">30</div>
          <div class="countdown-progress">
            <div class="countdown-progress-bar"></div>
          </div>
        </section>
      `;
    });
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
            <td style="color: #FFD700; font-weight: 700;">¥${formatNumber(amount)}</td>
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
      <div class="stats-simple">
        <div class="stat-item">
          <div class="stat-item-number">${stats.total_thanks_slips}</div>
          <div class="stat-item-label">サンクスリップ提出数</div>
        </div>
        <div class="stat-item">
          <div class="stat-item-number">${stats.total_one_to_one}</div>
          <div class="stat-item-label">121実施数</div>
        </div>
        <div class="stat-item">
          <div class="stat-item-number">${stats.total_attendance}</div>
          <div class="stat-item-label">出席者数</div>
        </div>
        <div class="stat-item">
          <div class="stat-item-number">${data.length}</div>
          <div class="stat-item-label">回答者数</div>
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
