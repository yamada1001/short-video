/**
 * BNI Slide System - Simple HTML Slide Generator
 * Using Reveal.js White Theme + BNI Colors
 */

/**
 * Generate all slides from data
 */
async function generateSVGSlides(data, stats, slideDate = '', pitchPresenter = null, slideConfig = null) {
  const slideContainer = document.getElementById('slideContainer');

  // Use provided date from API, or fall back to today's date
  const displayDate = slideDate || new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let slides = '';

  // Slide 1: Title
  slides += `
    <section class="title-slide">
      <h1>BNI週次レポート</h1>
      <p class="subtitle">${displayDate}</p>
      <p class="branding">Givers Gain® | BNI Slide System</p>
    </section>
  `;

  // Phase 1: Opening Section
  if (slideConfig) {
    // 1.1: Attendance Check
    if (slideConfig.attendance_check) {
      slides += generateAttendanceCheckSlide(slideConfig);
    }

    // 1.2: Business Card Seating Chart
    if (slideConfig.business_card_seating) {
      slides += generateBusinessCardSeatingSlide(slideConfig);
    }

    // 1.3: President's Message
    if (slideConfig.president) {
      slides += generatePresidentMessageSlide(slideConfig);
    }

    // 1.4: Good & New
    slides += generateGoodAndNewSlide();
  }

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

        // Add space before 様 if it exists
        if (displayName && displayName.includes('様')) {
          displayName = displayName.replace(/([^\s])様/, '$1 様');
        }

        // Add space before 様 in 紹介者名 as well
        let introducerName = row['紹介者名'] || '';
        if (introducerName && introducerName.includes('様')) {
          introducerName = introducerName.replace(/([^\s])様/, '$1 様');
        }

        slides += `
          <tr>
            <td>${escapeHtml(introducerName)}</td>
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

    // Slide 3.5: Visitor Self-Introduction Slides
    visitorsWithData.forEach(row => {
      // Parse visitor name
      const fullVisitorName = row['ビジター名'] || '';
      const visitorCompany = row['ビジター会社名'] || '';

      let displayName = fullVisitorName;
      let displayCompany = visitorCompany;

      if (!visitorCompany && fullVisitorName.includes(' ')) {
        const parts = fullVisitorName.split(' ');
        displayCompany = parts[0];
        displayName = parts.slice(1).join(' ');
      }

      // Add space before 様 if it exists
      if (displayName && displayName.includes('様')) {
        displayName = displayName.replace(/([^\s])様/, '$1 様');
      }

      const visitorIndustry = escapeHtml(row['ビジター業種'] || '');

      slides += `
        <section class="visitor-slide">
          <h2 class="visitor-name">${escapeHtml(displayName)}</h2>
          <p class="visitor-company">${escapeHtml(displayCompany)}</p>
          <p class="visitor-industry">${visitorIndustry}</p>
          <div class="visitor-prompt">
            <p class="prompt-title">自己紹介をお願いします</p>
            <div class="prompt-themes">
              <div class="theme-item"><i class="fas fa-building"></i> 会社名（屋号）・事業内容</div>
              <div class="theme-item"><i class="fas fa-briefcase"></i> ご自身のお仕事について</div>
              <div class="theme-item"><i class="fas fa-bullseye"></i> 今日の参加目的</div>
              <div class="theme-item"><i class="fas fa-heart"></i> 趣味・好きなこと</div>
            </div>
          </div>
        </section>
      `;
    });
  }

  // Pitch Section: Insert between Visitors and Referrals
  if (pitchPresenter && pitchPresenter.file_path) {
    const presenterName = escapeHtml(pitchPresenter.name || 'メンバー');
    const fileType = pitchPresenter.file_type || 'unknown';
    const fileName = escapeHtml(pitchPresenter.file_original_name || 'ピッチ資料');
    const filePath = pitchPresenter.file_path;

    if (fileType === 'pdf') {
      // PDFの場合：iframe で埋め込み表示 + フルスクリーンボタン（PDF.jsビューアー）
      const pdfFile = encodeURIComponent(filePath.split('/').pop());
      const pdfUrl = `../api_get_pitch_file.php?file=${pdfFile}`;
      const viewerUrl = `../pitch_viewer.php?file=${pdfFile}`;
      slides += `
      <section>
        <h2>メンバーのピッチ</h2>
        <div class="pitch-presenter-info">
          <h3>${presenterName}さん</h3>
          <a
            href="${viewerUrl}"
            target="_blank"
            class="btn-fullscreen"
            title="フルスクリーンで開く"
          >
            <i class="fas fa-expand"></i> フルスクリーンで開く
          </a>
        </div>
        <div class="pitch-file-container">
          <iframe
            src="${pdfUrl}"
            width="100%"
            height="600"
            style="border: 1px solid #ddd; border-radius: 8px;"
            title="ピッチ資料 - ${fileName}"
          ></iframe>
        </div>
      `;
    } else {
      // PowerPointの場合：ダウンロードリンクのみ
      slides += `
      <section>
        <h2>メンバーのピッチ</h2>
        <div class="pitch-presenter-info">
          <h3>${presenterName}さん</h3>
        </div>
        <div class="pitch-download-container">
          <div class="download-box">
            <i class="fas fa-file-powerpoint" style="font-size: 64px; color: #CF2030; margin-bottom: 20px;"></i>
            <p style="font-size: 18px; margin-bottom: 20px;">${fileName}</p>
            <a
              href="../api_get_pitch_file.php?file=${encodeURIComponent(filePath.split('/').pop())}"
              class="btn-download"
              download
              target="_blank"
            >
              <i class="fas fa-download"></i> ダウンロード
            </a>
            <p style="font-size: 14px; color: #666; margin-top: 15px;">
              PowerPoint形式のファイルです。ダウンロードしてご覧ください。
            </p>
          </div>
        </div>
      `;
    }

    slides += `
      </section>
    `;
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
    // Filter out members with 0 referral amount AND 0 visitors
    const memberEntries = Object.entries(stats.members).filter(([member, memberStats]) => {
      return memberStats.referral_amount > 0 || memberStats.visitors > 0;
    });

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
        // Add space before 様 if it exists
        let memberName = member;
        if (memberName && memberName.includes('様')) {
          memberName = memberName.replace(/([^\s])様/, '$1 様');
        }

        slides += `
          <div class="member-item">
            <div class="member-item-name">${escapeHtml(memberName)}</div>
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

    // Slide 5.5: Member 60-Second Pitch Slides (Full version with photos)
    if (slideConfig && slideConfig.members && slideConfig.members.length > 0) {
      slideConfig.members.forEach(member => {
        const photoUrl = member.photo || 'assets/images/default-avatar.svg';
        const industryIcon = getIndustryIcon(member.industry_icon || 'briefcase');
        const pitchTime = member.pitch_time || 33;
        const timeDisplay = `<<00:${String(pitchTime).padStart(2, '0')}>>`;

        slides += `
          <section class="member-pitch-slide-full" data-member="${escapeHtml(member.name)}">
            <div class="pitch-layout-full">
              <div class="member-photo-large">
                <img src="${escapeHtml(photoUrl)}" alt="${escapeHtml(member.name)}" onerror="this.src='assets/images/default-avatar.svg'">
              </div>
              <div class="member-info-right">
                <div class="industry-badge-large">
                  <i class="fas fa-${escapeHtml(industryIcon)}"></i>
                  <span>${escapeHtml(member.category || '')}</span>
                </div>
                <h2 class="member-name-large">${escapeHtml(member.name)}</h2>
                <p class="member-company-large">${escapeHtml(member.company || '')}</p>
                <div class="pitch-timer-large" data-time="${pitchTime}">
                  ${timeDisplay}
                </div>
              </div>
            </div>
          </section>
        `;
      });
    } else {
      // Fallback: Use existing member list from stats
      const allMembers = Object.keys(stats.members);
      allMembers.forEach(member => {
        let memberName = member;
        if (memberName && memberName.includes('様')) {
          memberName = memberName.replace(/([^\s])様/, '$1 様');
        }

        slides += `
          <section class="pitch-slide" data-member="${escapeHtml(member)}">
            <h2 class="pitch-member-name">${escapeHtml(memberName)}</h2>
            <p class="pitch-label">30秒ピッチ</p>
            <div class="countdown-timer" data-seconds="30">30</div>
            <div class="countdown-progress">
              <div class="countdown-progress-bar"></div>
            </div>
          </section>
        `;
      });
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

  // Slide 6.5: Member Pitch Presentation (PDF only, one per week)
  if (pitchPresenter && pitchPresenter.file_path) {
    const presenterName = escapeHtml(pitchPresenter.name || 'メンバー');
    const fileType = pitchPresenter.file_type || 'unknown';
    const fileName = escapeHtml(pitchPresenter.file_original_name || 'ピッチ資料');
    const filePath = pitchPresenter.file_path;

    if (fileType === 'pdf') {
      // PDFの場合のみ表示
      const pdfFile = encodeURIComponent(filePath.split('/').pop());
      const pdfUrl = `../api_get_pitch_file.php?file=${pdfFile}`;
      const viewerUrl = `../pitch_viewer.php?file=${pdfFile}`;

      slides += `
        <section>
          <h2>メンバーのピッチ</h2>
          <div class="pitch-presenter-info">
            <h3>${presenterName}さん</h3>
            <a
              href="${viewerUrl}"
              target="_blank"
              class="btn-fullscreen"
              title="フルスクリーンで開く"
            >
              <i class="fas fa-expand"></i> フルスクリーンで開く
            </a>
          </div>
          <div class="pitch-file-container">
            <iframe
              src="${pdfUrl}"
              width="100%"
              height="600"
              style="border: 1px solid #ddd; border-radius: 8px;"
              title="ピッチ資料 - ${fileName}"
            ></iframe>
          </div>
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

  // Slide 7.5: Visitor Feedback Slides (at the end)
  if (data.length > 0) {
    const visitorsWithData = data.filter(row => row['ビジター名']);

    visitorsWithData.forEach(row => {
      // Parse visitor name
      const fullVisitorName = row['ビジター名'] || '';
      const visitorCompany = row['ビジター会社名'] || '';

      let displayName = fullVisitorName;
      let displayCompany = visitorCompany;

      if (!visitorCompany && fullVisitorName.includes(' ')) {
        const parts = fullVisitorName.split(' ');
        displayCompany = parts[0];
        displayName = parts.slice(1).join(' ');
      }

      // Add space before 様 if it exists
      if (displayName && displayName.includes('様')) {
        displayName = displayName.replace(/([^\s])様/, '$1 様');
      }

      const visitorIndustry = escapeHtml(row['ビジター業種'] || '');

      slides += `
        <section class="visitor-slide feedback-slide">
          <h2 class="visitor-name">${escapeHtml(displayName)}</h2>
          <p class="visitor-company">${escapeHtml(displayCompany)}</p>
          <p class="visitor-industry">${visitorIndustry}</p>
          <div class="visitor-prompt">
            <p class="prompt-title">ご感想をお聞かせください</p>
            <div class="prompt-themes">
              <div class="theme-item"><i class="fas fa-comment-dots"></i> 本日の会議の印象</div>
              <div class="theme-item"><i class="fas fa-star"></i> 印象に残ったこと</div>
              <div class="theme-item"><i class="fas fa-handshake"></i> ビジネスでご協力できそうなこと</div>
              <div class="theme-item"><i class="fas fa-bullhorn"></i> メンバーへのメッセージ</div>
            </div>
          </div>
        </section>
      `;
    });
  }

  // Phase 15: Speaker Rotation
  if (slideConfig && slideConfig.speaker_rotation) {
    slides += generateSpeakerRotationSlide(slideConfig);
  }

  // Phase 11: BNI Philosophy & Core Values
  slides += generateBNIPhilosophySlides();

  // Phase 19: Closing - Chapter Logo
  if (slideConfig && slideConfig.chapter) {
    slides += generateChapterClosingSlide(slideConfig);
  }

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

/**
 * Get Font Awesome icon name for industry category
 */
function getIndustryIcon(iconKey) {
  const iconMap = {
    'briefcase': 'briefcase',
    'utensils': 'utensils',
    'spa': 'spa',
    'paint-brush': 'paint-brush',
    'home': 'home',
    'shield-alt': 'shield-alt',
    'leaf': 'leaf',
    'hands-helping': 'hands-helping',
    'graduation-cap': 'graduation-cap',
    'store': 'store',
    'laptop-code': 'laptop-code',
    'calculator': 'calculator',
    'balance-scale': 'balance-scale',
    'chart-line': 'chart-line',
    'building': 'building'
  };
  return iconMap[iconKey] || 'briefcase';
}

/**
 * Phase 1.1: Generate Attendance Check Slide
 */
function generateAttendanceCheckSlide(config) {
  const instruction = config.attendance_check?.instruction || '各チームリーダーは20秒で出席確認と遅刻確認をお願いします';
  const teams = config.attendance_check?.team_leaders || ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

  return `
    <section class="opening-slide attendance-slide">
      <h2>出欠確認</h2>
      <p class="instruction">${escapeHtml(instruction)}</p>
      <div class="team-grid">
        ${teams.map(team => `
          <div class="team-box">
            <div class="team-label">チーム ${team}</div>
          </div>
        `).join('')}
      </div>
    </section>
  `;
}

/**
 * Phase 1.2: Generate Business Card Seating Chart
 */
function generateBusinessCardSeatingSlide(config) {
  const podiumMembers = config.business_card_seating?.podium_members || ['ポーディアム'];
  const screenLabel = config.business_card_seating?.screen_label || 'スクリーン';
  const teams = config.teams || {};

  return `
    <section class="opening-slide seating-slide">
      <div class="seating-header">
        <div class="podium-label">${podiumMembers.join(' ')}</div>
        <h2>名刺交換時</h2>
      </div>
      <div class="seating-chart">
        ${Object.entries(teams).map(([teamName, members]) => {
          if (members.length === 0) return '';
          return `
            <div class="team-circle">
              <div class="team-name">${teamName}</div>
              <div class="team-members">${members.map(m => m.split(' ')[1] || m).join('<br>')}</div>
            </div>
          `;
        }).join('')}
      </div>
      <div class="screen-label">${screenLabel}</div>
    </section>
  `;
}

/**
 * Phase 1.3: Generate President's Message Slide
 */
function generatePresidentMessageSlide(config) {
  const president = config.president || {};
  const presidentName = president.name || 'プレジデント';
  const message = president.message || '本日も宜しくお願いします';

  return `
    <section class="opening-slide president-slide">
      <h2>朝礼</h2>
      <div class="president-content">
        <div class="president-name">プレジ: ${escapeHtml(presidentName)}</div>
        <div class="president-message">${escapeHtml(message)}</div>
        <div class="timer-note">Good & New（30秒）</div>
      </div>
    </section>
  `;
}

/**
 * Phase 1.4: Generate Good & New Slide
 */
function generateGoodAndNewSlide() {
  return `
    <section class="opening-slide goodnew-slide">
      <h2>Good & New</h2>
      <div class="goodnew-content">
        <p class="goodnew-instruction">最近あった良いこと、新しいことを30秒で共有してください</p>
        <div class="countdown-display">30秒</div>
      </div>
    </section>
  `;
}

/**
 * Phase 15: Generate Speaker Rotation Slide
 */
function generateSpeakerRotationSlide(config) {
  const speakers = config.speaker_rotation || [];

  // 最新4-5件を表示
  const recentSpeakers = speakers.slice(0, 4);

  return `
    <section class="rotation-slide">
      <h2>スピーカーローテーション</h2>
      <table class="rotation-table">
        <thead>
          <tr>
            <th>日程</th>
            <th>メインプレゼン</th>
            <th>ご紹介してほしい方</th>
          </tr>
        </thead>
        <tbody>
          ${recentSpeakers.map(speaker => {
            const statusClass = speaker.status === 'current' ? 'current-row' :
                              speaker.status === 'future' ? 'future-row' : 'past-row';
            return `
              <tr class="${statusClass}">
                <td class="date-cell">${escapeHtml(speaker.date)}</td>
                <td class="presenter-cell">
                  <div class="presenter-name">${escapeHtml(speaker.presenter)}</div>
                  <div class="presenter-category">(${escapeHtml(speaker.category || '')})</div>
                </td>
                <td class="target-cell">${escapeHtml(speaker.target || '').replace(/\n/g, '<br>')}</td>
              </tr>
            `;
          }).join('')}
        </tbody>
      </table>
    </section>
  `;
}

/**
 * Phase 11: Generate BNI Philosophy & Core Values Slides
 */
function generateBNIPhilosophySlides() {
  let slides = '';

  // Slide 1: BNI Overview (World Statistics)
  slides += `
    <section class="bni-philosophy-slide">
      <h2>BNIの概観</h2>
      <h3 class="sub-heading">世界の統計</h3>
      <div class="bni-stats-grid">
        <div class="stat-row">
          <div class="stat-label">BNI参入国数</div>
          <div class="stat-value">77カ国</div>
        </div>
        <div class="stat-row">
          <div class="stat-label">総メンバー数</div>
          <div class="stat-value">328,456名超</div>
        </div>
        <div class="stat-row">
          <div class="stat-label">総チャプター数</div>
          <div class="stat-value">11,121超</div>
        </div>
        <div class="stat-row highlight">
          <div class="stat-label">リファーラル数</div>
          <div class="stat-value">約1400万件超</div>
        </div>
        <div class="stat-row highlight">
          <div class="stat-label">得られたビジネス金額</div>
          <div class="stat-value">約2兆2000億円</div>
        </div>
      </div>
      <p class="stat-note">※2024年6月1日付</p>
    </section>
  `;

  // Slide 2: 3つのお約束（BNI成功の鍵）
  slides += `
    <section class="bni-philosophy-slide">
      <h2>3つのお約束（BNI成功の鍵）</h2>
      <div class="promise-list">
        <div class="promise-item">
          <div class="promise-number">1</div>
          <div class="promise-content">
            <strong>出席と時間</strong>（年間約50回、金曜日 6:00-8:30）
            <p class="promise-detail">※詳しくは個別ミーティングにて</p>
          </div>
        </div>
        <div class="promise-item">
          <div class="promise-number">2</div>
          <div class="promise-content">
            <strong>学び</strong>（各種トレーニング等）
          </div>
        </div>
        <div class="promise-item">
          <div class="promise-number">3</div>
          <div class="promise-content">
            <strong>貢献</strong>（Givers Gainの実践、リファーラル・ビジター等）
          </div>
        </div>
      </div>
      <div class="member-rules">
        <h3>&lt;BNIメンバー規定&gt;</h3>
        <p>◆BNIメンバーは、定刻までに会場に到着し、<br>ミーティングが終了するまで退室は認められない。</p>
        <p>◆各メンバーは直近6か月間に3回までの欠席が認められる。<br>メンバーは、ミーティングに出席できない場合は、<br>代理人を立てるものとする。この場合、欠席扱いにはならない。</p>
      </div>
    </section>
  `;

  // Slide 3: 他の人の成功を助けることを通じて自分の成功を築いていく
  slides += `
    <section class="bni-philosophy-slide philosophy-main">
      <div class="philosophy-statement">
        <p class="philosophy-text-line1">他の人の<span class="highlight-red">成功を助ける</span>ことを通じて</p>
        <p class="philosophy-text-line2"><span class="highlight-red">自分の成功</span>を築いていく</p>
      </div>
    </section>
  `;

  // Slide 4: Benefit 3 - 年間50回継続的なビジネス機会
  slides += `
    <section class="bni-philosophy-slide benefit-slide">
      <div class="benefit-header">Benefit 3</div>
      <h2>年間50回<br><span class="highlight-red">継続的なビジネス機会</span></h2>
      <div class="benefit-list">
        <p>•リファーラル</p>
        <p>•ビジター</p>
      </div>
    </section>
  `;

  // Slide 5: Benefit 5 - 強い信頼関係から生まれるコーチングおよびビジネスサポート
  slides += `
    <section class="bni-philosophy-slide benefit-slide">
      <div class="benefit-header">Benefit 5</div>
      <h2><span class="highlight-red">強い信頼関係</span>から生まれる<br>コーチングおよびビジネスサポート</h2>
    </section>
  `;

  // Slide 6: Benefit +1 - 地域、日本、そして世界へ
  slides += `
    <section class="bni-philosophy-slide benefit-slide">
      <div class="benefit-header">Benefit +1</div>
      <h2>地域、<span class="highlight-red">日本</span>、そして<span class="highlight-red">世界へ</span></h2>
      <p class="japan-stats">1都1道2府23県　367チャプター</p>
    </section>
  `;

  // Slide 7: 売上獲得までのプロセス
  slides += `
    <section class="bni-philosophy-slide process-slide">
      <h2>売上獲得までのプロセス</h2>
      <div class="process-diagram">
        <div class="process-box marketing">マーケティング</div>
        <div class="process-arrow">→</div>
        <div class="process-box sales">セールス</div>
        <div class="process-arrow">→</div>
        <div class="process-box contract">成約</div>
      </div>
      <div class="process-description">
        <p class="desc-marketing"><span class="highlight-green">マーケティング</span>...見込み客を獲得する方法</p>
        <p class="desc-sales"><span class="highlight-blue">セールス</span>...見込み客を顧客に変える方法</p>
        <p class="desc-contract"><strong>成約...ビジネス獲得の成果</strong></p>
      </div>
    </section>
  `;

  return slides;
}

/**
 * Phase 19: Generate Chapter Closing Slide
 */
function generateChapterClosingSlide(config) {
  const chapter = config.chapter || {};
  const chapterName = chapter.name || '宗麟';
  const motto = chapter.motto || 'Keep growing';
  const subtitle = chapter.subtitle || '〜貢献の絆で未来を創る〜';

  return `
    <section class="chapter-closing-slide">
      <div class="chapter-logo-large">${escapeHtml(chapterName)}</div>
      <div class="chapter-motto">${escapeHtml(motto)}</div>
      <div class="chapter-subtitle">${escapeHtml(subtitle)}</div>
    </section>
  `;
}
