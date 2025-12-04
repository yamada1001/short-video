/**
 * BNI Slide System - D3.js SVG Slide Generator
 * 4K SVG Slides with D3.js Programmatic Generation
 */

/**
 * Create Title Slide with D3.js
 */
function createTitleSlideSVG(date) {
  const svg = d3.create('svg')
    .attr('width', 3840)
    .attr('height', 2160)
    .attr('viewBox', '0 0 3840 2160')
    .attr('xmlns', 'http://www.w3.org/2000/svg');

  // Define gradients
  const defs = svg.append('defs');

  // Background gradient
  const bgGradient = defs.append('linearGradient')
    .attr('id', 'titleBgGradient')
    .attr('x1', '0%').attr('y1', '0%')
    .attr('x2', '100%').attr('y2', '100%');

  bgGradient.append('stop')
    .attr('offset', '0%')
    .attr('style', 'stop-color:#CF2030;stop-opacity:1');
  bgGradient.append('stop')
    .attr('offset', '50%')
    .attr('style', 'stop-color:#A01828;stop-opacity:1');
  bgGradient.append('stop')
    .attr('offset', '100%')
    .attr('style', 'stop-color:#8B1420;stop-opacity:1');

  // Decorative circles
  const circle1 = defs.append('radialGradient').attr('id', 'titleCircle1');
  circle1.append('stop').attr('offset', '0%').attr('style', 'stop-color:#ffffff;stop-opacity:0.12');
  circle1.append('stop').attr('offset', '70%').attr('style', 'stop-color:#ffffff;stop-opacity:0');

  const circle2 = defs.append('radialGradient').attr('id', 'titleCircle2');
  circle2.append('stop').attr('offset', '0%').attr('style', 'stop-color:#ffffff;stop-opacity:0.08');
  circle2.append('stop').attr('offset', '70%').attr('style', 'stop-color:#ffffff;stop-opacity:0');

  // Background
  svg.append('rect')
    .attr('width', 3840)
    .attr('height', 2160)
    .attr('fill', 'url(#titleBgGradient)');

  // Decorative circles
  svg.append('circle')
    .attr('cx', 3200).attr('cy', 400).attr('r', 800)
    .attr('fill', 'url(#titleCircle1)');

  svg.append('circle')
    .attr('cx', 500).attr('cy', 1800).attr('r', 700)
    .attr('fill', 'url(#titleCircle2)');

  // Main content group
  const content = svg.append('g')
    .attr('text-anchor', 'middle')
    .attr('font-family', "'Noto Sans JP', sans-serif");

  // Title
  content.append('text')
    .attr('x', 1920).attr('y', 900)
    .attr('font-size', 180)
    .attr('font-weight', 800)
    .attr('letter-spacing', -2)
    .attr('fill', '#FFFFFF')
    .text('BNI週次レポート');

  // Date
  content.append('text')
    .attr('x', 1920).attr('y', 1100)
    .attr('font-size', 100)
    .attr('font-weight', 500)
    .attr('letter-spacing', 1)
    .attr('fill', '#FFFFFF')
    .attr('opacity', 0.95)
    .text(date);

  // Footer
  content.append('text')
    .attr('x', 1920).attr('y', 1450)
    .attr('font-size', 48)
    .attr('font-weight', 500)
    .attr('letter-spacing', 1)
    .attr('fill', '#FFFFFF')
    .attr('opacity', 0.9)
    .text('Givers Gain® | BNI Slide System');

  return svg.node().outerHTML;
}

/**
 * Create Summary Slide with D3.js
 */
function createSummarySlideSVG(stats, responseCount) {
  const svg = d3.create('svg')
    .attr('width', 3840)
    .attr('height', 2160)
    .attr('viewBox', '0 0 3840 2160')
    .attr('xmlns', 'http://www.w3.org/2000/svg');

  // Define gradients
  const defs = svg.append('defs');

  // Background mesh
  const mesh1 = defs.append('radialGradient').attr('id', 'summaryMesh1');
  mesh1.append('stop').attr('offset', '0%').attr('style', 'stop-color:#CF2030;stop-opacity:0.08');
  mesh1.append('stop').attr('offset', '50%').attr('style', 'stop-color:#CF2030;stop-opacity:0');

  const mesh2 = defs.append('radialGradient').attr('id', 'summaryMesh2');
  mesh2.append('stop').attr('offset', '0%').attr('style', 'stop-color:#3498DB;stop-opacity:0.06');
  mesh2.append('stop').attr('offset', '50%').attr('style', 'stop-color:#3498DB;stop-opacity:0');

  // Card gradient
  const cardGradient = defs.append('linearGradient')
    .attr('id', 'summaryCardGradient')
    .attr('x1', '0%').attr('y1', '0%')
    .attr('x2', '100%').attr('y2', '0%');
  cardGradient.append('stop').attr('offset', '0%').attr('style', 'stop-color:#CF2030;stop-opacity:1');
  cardGradient.append('stop').attr('offset', '100%').attr('style', 'stop-color:#FF4858;stop-opacity:1');

  // Background
  svg.append('rect')
    .attr('width', 3840)
    .attr('height', 2160)
    .attr('fill', '#FAFBFC');

  // Background decorative circles
  svg.append('circle')
    .attr('cx', 800).attr('cy', 600).attr('r', 600)
    .attr('fill', 'url(#summaryMesh1)');

  svg.append('circle')
    .attr('cx', 3000).attr('cy', 1500).attr('r', 700)
    .attr('fill', 'url(#summaryMesh2)');

  // Title
  const title = svg.append('text')
    .attr('x', 1920).attr('y', 300)
    .attr('font-family', "'Noto Sans JP', sans-serif")
    .attr('font-size', 120)
    .attr('font-weight', 700)
    .attr('letter-spacing', 2)
    .attr('fill', '#1a1a1a')
    .attr('text-anchor', 'middle')
    .text('今週のサマリー');

  // Title underline
  svg.append('rect')
    .attr('x', 1840).attr('y', 340)
    .attr('width', 160).attr('height', 8)
    .attr('fill', '#CF2030')
    .attr('rx', 4);

  // Card data
  const cards = [
    { x: 400, y: 500, icon: '👥', value: stats.total_visitors || 0, label: 'ビジター紹介数', color: '#CF2030', bgColor: '#FFF5F5' },
    { x: 1320, y: 500, icon: '💰', value: '¥' + formatNumber(stats.total_referral_amount || 0), label: '総リファーラル金額', color: '#27AE60', bgColor: '#F0FFF4' },
    { x: 2240, y: 500, icon: '✓', value: stats.total_attendance || 0, label: '出席者数', color: '#3498DB', bgColor: '#F0F8FF' },
    { x: 860, y: 1080, icon: '🤝', value: stats.total_one_to_one || 0, label: 'ワンツーワン実施数', color: '#F39C12', bgColor: '#FFF5F0' },
    { x: 1780, y: 1080, icon: '📝', value: responseCount || 0, label: '回答者数', color: '#9B59B6', bgColor: '#F5F0FF' }
  ];

  // Create cards
  cards.forEach((card, i) => {
    const cardGroup = svg.append('g').attr('id', `summaryCard${i + 1}`);

    // Card background
    cardGroup.append('rect')
      .attr('x', card.x).attr('y', card.y)
      .attr('width', 800).attr('height', 450)
      .attr('fill', '#FFFFFF')
      .attr('rx', 24)
      .attr('stroke', '#E5E7EB')
      .attr('stroke-width', 2);

    // Top border
    cardGroup.append('rect')
      .attr('x', card.x).attr('y', card.y)
      .attr('width', 800).attr('height', 8)
      .attr('fill', 'url(#summaryCardGradient)')
      .attr('rx', 24);

    // Icon circle
    const iconCenterX = card.x + 400;
    const iconCenterY = card.y + 150;

    cardGroup.append('circle')
      .attr('cx', iconCenterX).attr('cy', iconCenterY)
      .attr('r', 80)
      .attr('fill', card.bgColor);

    cardGroup.append('text')
      .attr('x', iconCenterX).attr('y', iconCenterY + 30)
      .attr('font-family', "'Noto Sans JP', sans-serif")
      .attr('font-size', 80)
      .attr('fill', card.color)
      .attr('text-anchor', 'middle')
      .text(card.icon);

    // Value
    cardGroup.append('text')
      .attr('x', iconCenterX).attr('y', card.y + 330)
      .attr('font-family', "'Inter', sans-serif")
      .attr('font-size', 110)
      .attr('font-weight', 800)
      .attr('letter-spacing', -2)
      .attr('fill', card.color)
      .attr('text-anchor', 'middle')
      .text(card.value);

    // Label
    cardGroup.append('text')
      .attr('x', iconCenterX).attr('y', card.y + 400)
      .attr('font-family', "'Noto Sans JP', sans-serif")
      .attr('font-size', 40)
      .attr('font-weight', 600)
      .attr('letter-spacing', 1)
      .attr('fill', '#4a4a4a')
      .attr('text-anchor', 'middle')
      .text(card.label);
  });

  return svg.node().outerHTML;
}

/**
 * Create Thank You Slide with D3.js
 */
function createThankYouSlideSVG() {
  const svg = d3.create('svg')
    .attr('width', 3840)
    .attr('height', 2160)
    .attr('viewBox', '0 0 3840 2160')
    .attr('xmlns', 'http://www.w3.org/2000/svg');

  // Define gradients
  const defs = svg.append('defs');

  const bgGradient = defs.append('linearGradient')
    .attr('id', 'thankyouBgGradient')
    .attr('x1', '0%').attr('y1', '0%')
    .attr('x2', '100%').attr('y2', '100%');

  bgGradient.append('stop')
    .attr('offset', '0%')
    .attr('style', 'stop-color:#CF2030;stop-opacity:1');
  bgGradient.append('stop')
    .attr('offset', '50%')
    .attr('style', 'stop-color:#A01828;stop-opacity:1');
  bgGradient.append('stop')
    .attr('offset', '100%')
    .attr('style', 'stop-color:#8B1420;stop-opacity:1');

  // Decorative circles
  const circle1 = defs.append('radialGradient').attr('id', 'thankyouCircle1');
  circle1.append('stop').attr('offset', '0%').attr('style', 'stop-color:#ffffff;stop-opacity:0.12');
  circle1.append('stop').attr('offset', '70%').attr('style', 'stop-color:#ffffff;stop-opacity:0');

  const circle2 = defs.append('radialGradient').attr('id', 'thankyouCircle2');
  circle2.append('stop').attr('offset', '0%').attr('style', 'stop-color:#ffffff;stop-opacity:0.08');
  circle2.append('stop').attr('offset', '70%').attr('style', 'stop-color:#ffffff;stop-opacity:0');

  // Background
  svg.append('rect')
    .attr('width', 3840)
    .attr('height', 2160)
    .attr('fill', 'url(#thankyouBgGradient)');

  // Decorative circles
  svg.append('circle')
    .attr('cx', 3200).attr('cy', 400).attr('r', 800)
    .attr('fill', 'url(#thankyouCircle1)');

  svg.append('circle')
    .attr('cx', 500).attr('cy', 1800).attr('r', 700)
    .attr('fill', 'url(#thankyouCircle2)');

  // Main content
  const content = svg.append('g')
    .attr('text-anchor', 'middle')
    .attr('font-family', "'Noto Sans JP', sans-serif");

  // Title
  content.append('text')
    .attr('x', 1920).attr('y', 950)
    .attr('font-size', 180)
    .attr('font-weight', 800)
    .attr('letter-spacing', -2)
    .attr('fill', '#FFFFFF')
    .text('ありがとうございました');

  // Subtitle
  content.append('text')
    .attr('x', 1920).attr('y', 1150)
    .attr('font-size', 90)
    .attr('font-weight', 500)
    .attr('letter-spacing', 2)
    .attr('fill', '#FFFFFF')
    .attr('opacity', 0.95)
    .text('来週もよろしくお願いします');

  // Footer
  content.append('text')
    .attr('x', 1920).attr('y', 1400)
    .attr('font-size', 56)
    .attr('font-weight', 500)
    .attr('letter-spacing', 1)
    .attr('fill', '#FFFFFF')
    .attr('opacity', 0.9)
    .text('Givers Gain®');

  return svg.node().outerHTML;
}

/**
 * Generate all slides from data using D3.js
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
  // Slide 1: Title Slide (D3.js SVG)
  // ============================================
  const titleSVG = createTitleSlideSVG(today);
  slides += `
    <section data-background-color="#CF2030">
      <div style="width: 100vw; height: 100vh; display: flex; align-items: center; justify-content: center;">
        ${titleSVG}
      </div>
    </section>
  `;

  // ============================================
  // Slide 2: Summary Slide (D3.js SVG)
  // ============================================
  const summarySVG = createSummarySlideSVG(stats, data.length);
  slides += `
    <section>
      <div style="width: 100vw; height: 100vh; display: flex; align-items: center; justify-content: center;">
        ${summarySVG}
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
  // Slide 9: Thank You Slide (D3.js SVG)
  // ============================================
  const thankYouSVG = createThankYouSlideSVG();
  slides += `
    <section data-background-color="#CF2030">
      <div style="width: 100vw; height: 100vh; display: flex; align-items: center; justify-content: center;">
        ${thankYouSVG}
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
