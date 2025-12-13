/**
 * BNI Slide System - Simple HTML Slide Generator
 * Using Reveal.js White Theme + BNI Colors
 */

/**
 * Extract YouTube video ID from various URL formats
 * @param {string} url - YouTube URL
 * @returns {string|null} - Video ID or null if invalid
 */
function extractYouTubeVideoId(url) {
  if (!url) return null;

  // https://www.youtube.com/watch?v=VIDEO_ID
  const watchMatch = url.match(/[?&]v=([^&]+)/);
  if (watchMatch) return watchMatch[1];

  // https://youtu.be/VIDEO_ID
  const shortMatch = url.match(/youtu\.be\/([^?&]+)/);
  if (shortMatch) return shortMatch[1];

  // https://www.youtube.com/embed/VIDEO_ID
  const embedMatch = url.match(/youtube\.com\/embed\/([^?&]+)/);
  if (embedMatch) return embedMatch[1];

  return null;
}

/**
 * Generate all slides from data
 */
async function generateSVGSlides(data, stats, slideDate = '', pitchPresenter = null, referralTotal = null, slideConfig = null, monthlyRankingData = null, visitorIntroductions = null, networkingLearningPresenter = null, rotationData = null, presenterDetail = null) {
  const slideContainer = document.getElementById('slideContainer');

  // Use provided date from API, or fall back to today's date
  const displayDate = slideDate || new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let slides = '';

  // If no data, show only monthly ranking slides (for "monthly_ranking" pattern)
  if ((!data || data.length === 0) && monthlyRankingData) {
    slides += generateMonthlyRankingSlides(monthlyRankingData);
    slideContainer.innerHTML = slides;
    return;
  }

  // Slide 1: Title (for normal weekly report)
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

    // 1.3: President's Message
    if (slideConfig.president) {
      slides += generatePresidentMessageSlide(slideConfig);
    }
  }

  // Visitor Introductions (from visitor_introductions table) - MOVED BEFORE Business Card
  if (visitorIntroductions && visitorIntroductions.length > 0) {
    slides += generateVisitorIntroductionListSlides(visitorIntroductions);
    slides += generateVisitorSelfIntroductionSlides(visitorIntroductions);
  }

  // 1.2: Business Card Seating Chart - MOVED AFTER Visitor Introductions
  if (slideConfig && slideConfig.business_card_seating) {
    slides += generateBusinessCardSeatingSlide(slideConfig);
  }

  // Member Contributions (split into multiple pages if needed)
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
    // Use generateMemberPitchSlides function (33-second pitch with photos)
    if (slideConfig && slideConfig.members && slideConfig.members.length > 0) {
      slides += generateMemberPitchSlides(slideConfig.members);
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

  // Slide 6.5: Member Pitch Presentation (PDF or YouTube video)
  if (pitchPresenter && (pitchPresenter.file_path || pitchPresenter.youtube_url)) {
    const presenterName = escapeHtml(pitchPresenter.name || 'メンバー');

    // YouTube動画がある場合
    if (pitchPresenter.youtube_url) {
      const youtubeId = extractYouTubeVideoId(pitchPresenter.youtube_url);
      if (youtubeId) {
        const embedUrl = `https://www.youtube.com/embed/${youtubeId}`;
        slides += `
          <section>
            <h2>メインプレゼン</h2>
            <div class="pitch-presenter-info">
              <h3>${presenterName}さん</h3>
            </div>
            <div class="pitch-file-container">
              <iframe
                width="100%"
                height="600"
                src="${embedUrl}"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                style="border: 1px solid #ddd; border-radius: 8px;"
              ></iframe>
            </div>
          </section>
        `;
      }
    }

    // PDFファイルがある場合
    if (pitchPresenter.file_path) {
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
            <h2>メインプレゼン（資料）</h2>
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
  }

  // Slide 7: Activity Summary - Removed (not needed)

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

  // Phase 2: Main Presentation (Weekly Presentation)
  if (slideConfig && slideConfig.speaker_rotation) {
    const currentSpeaker = slideConfig.speaker_rotation.find(s => s.status === 'current');
    if (currentSpeaker) {
      slides += generateMainPresentationSlides(currentSpeaker);
    }
  }

  // Phase 3: Referral Announcements Template
  slides += generateReferralAnnouncementsSlide();

  // Phase 4: New Members
  if (slideConfig && slideConfig.new_members && slideConfig.new_members.length > 0) {
    slides += generateNewMembersSlides(slideConfig);
  }

  // Phase 5: Monthly Champions
  if (slideConfig && slideConfig.monthly_champions) {
    slides += generateMonthlyChampionsSlides(slideConfig);
  }

  // Phase 6: Happy Birthday (only if there are birthdays this week)
  if (slideConfig && slideConfig.birthdays_this_week && slideConfig.birthdays_this_week.length > 0) {
    slides += generateHappyBirthdaySlide(slideConfig.birthdays_this_week);
  }

  // Phase 7: Weekly NO.1 (Past Records)
  if (slideConfig && slideConfig.weekly_no1) {
    slides += generateWeeklyNo1Slide(slideConfig.weekly_no1);
  }

  // Phase 8: Secretary Message
  if (slideConfig && slideConfig.secretary) {
    slides += generateSecretarySlide(slideConfig.secretary);
  }

  // Phase 9: Director Message
  if (slideConfig && slideConfig.director) {
    slides += generateDirectorSlide(slideConfig.director);
  }

  // Phase 10: Visitor Hosts
  if (slideConfig && slideConfig.visitor_hosts && slideConfig.visitor_hosts.length > 0) {
    slides += generateVisitorHostsSlide(slideConfig);
  }

  // Phase 10.5: Core Values (PDF p.62)
  slides += generateCoreValuesSlide();

  // Phase 10.6: Share Story (PDF p.73)
  slides += generateShareStorySlide();

  // Phase 10.7: Member Introduction Slides (PDF p.42-59)
  slides += generateMemberIntroductionSlides();

  // Phase 10.8: BNI Purpose Slides (PDF p.65-71)
  slides += generateBNIPurposeSlides();

  // Phase 11: BNI Philosophy & Core Values
  slides += generateBNIPhilosophySlides();

  // Phase 11.5: Additional PDF Slides
  slides += generateVisitorSelfIntroTemplateSlide(); // p.182
  slides += generateBusinessBreakoutSlides(); // p.198-200
  slides += generateVicePresidentStatsSlides(rotationData, presenterDetail); // p.203-206
  slides += generateReferralTrustSlides(); // p.246-253
  slides += generateClosingSlides(); // p.303-328

  // Phase 12: Networking Education Corner
  // Use networkingLearningPresenter data if available, otherwise fall back to slideConfig
  if (networkingLearningPresenter) {
    slides += generateNetworkingLearningSlide(networkingLearningPresenter);
  } else if (slideConfig && slideConfig.networking_education) {
    slides += generateNetworkingEducationSlide(slideConfig.networking_education);
  }

  // Phase 13: Visitor Self-Introduction Template
  slides += generateVisitorIntroductionSlide();

  // Phase 14: Speaker Rotation
  if (slideConfig && slideConfig.speaker_rotation) {
    slides += generateSpeakerRotationSlide(slideConfig);
  }

  // Phase 15: Today's Comment Template
  slides += generateTodaysCommentSlide();

  // Phase 16: Visitor Orientation
  if (slideConfig && slideConfig.orientation_facilitator) {
    slides += generateVisitorOrientationSlide(slideConfig.orientation_facilitator);
  }

  // Phase 17: From Coordinators
  slides += generateCoordinatorsSlide();

  // Phase 18: Closing - Chapter Logo
  if (slideConfig && slideConfig.chapter) {
    slides += generateChapterClosingSlide(slideConfig);
  }

  // Slide 8: Thank You
  // Add monthly ranking slides if display_in_slide = 1
  if (monthlyRankingData) {
    slides += generateMonthlyRankingSlides(monthlyRankingData);
  }

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
  const specialAreas = config.business_card_seating?.special_areas || [];

  // テーブル配置: 本番PDFレイアウト（正確に）
  // 左列（上から下）: A, B, C
  // 中央列（上から下）: H（上）、F（下）
  // 右列（上から下）: E, D, G
  const tableLayout = {
    left: ['A', 'B', 'C'],
    center: ['H', '', 'F'],  // H は上、F は下（間は空白）
    right: ['E', 'D', 'G']
  };

  // 各テーブルのHTMLを生成
  function renderTable(tableName) {
    if (!tableName) return '<div class="seating-table-spacer"></div>'; // 空白スペース

    const members = teams[tableName] || [];
    if (members.length === 0 && tableName !== 'H') return ''; // H は空でも表示

    return `
      <div class="seating-table" data-table="${tableName}">
        <div class="table-circle">
          <div class="table-label">${tableName}</div>
        </div>
        ${members.map((name, idx) => {
          // 円周上に配置するため、角度を計算
          const angle = (idx * 360 / Math.max(members.length, 1)) - 90; // -90で12時方向スタート
          const radius = 140; // 円の半径 + オフセット
          const x = Math.cos(angle * Math.PI / 180) * radius;
          const y = Math.sin(angle * Math.PI / 180) * radius;
          return `<div class="member-name" style="transform: translate(${x}px, ${y}px);">${escapeHtml(name)}</div>`;
        }).join('')}
      </div>
    `;
  }

  return `
    <section class="opening-slide seating-slide-new">
      <!-- Top Area: Screen, Podium, Special Names -->
      <div class="seating-top-area">
        <div class="podium-area">${podiumMembers[0] || 'ポーディアム'}</div>
        <div class="screen-area">${screenLabel}</div>
        <div class="seating-title">名刺交換時</div>
        <div class="special-names-area">
          ${(config.business_card_seating?.top_right_names || ['山本', '花田', '佳子', '野口']).join(' ')}
        </div>
      </div>

      <!-- Main Seating Area -->
      <div class="seating-main-area">
        <!-- Left Column: A, B, C -->
        <div class="seating-column left-column">
          ${tableLayout.left.map(t => renderTable(t)).join('')}
        </div>

        <!-- Center Column: H, F -->
        <div class="seating-column center-column">
          ${tableLayout.center.map(t => renderTable(t)).join('')}
        </div>

        <!-- Right Column: E, D, G -->
        <div class="seating-column right-column">
          ${tableLayout.right.map(t => renderTable(t)).join('')}
        </div>
      </div>
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
 * Phase 2: Generate Main Presentation Slides
 */
function generateMainPresentationSlides(speaker) {
  let slides = '';

  // Intro slide: Weekly Presentation (with 2-minute timer)
  slides += `
    <section class="main-presentation-intro-slide" data-timer="weekly_presentation">
      <div class="presentation-photo-left">
        <img src="assets/images/presentation-people.jpg" alt="Presentation" class="presentation-bg-image" />
      </div>
      <div class="presentation-title-right">
        <h2 class="presentation-main-title">ウィークリー<br>プレゼンテーション</h2>
      </div>
    </section>
  `;

  // 4-minute presentation slide
  slides += `
    <section class="four-minute-presentation-slide">
      <div class="four-minute-header">4分プレゼンテーション</div>
      <div class="four-minute-presenter-box">
        <div class="presenter-name-large">${escapeHtml(speaker.presenter)}</div>
        <div class="presenter-category-large">${escapeHtml(speaker.category)}</div>
      </div>
    </section>
  `;

  return slides;
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
 * Phase 10.5: Generate Core Values Slide (PDF p.62)
 */
function generateCoreValuesSlide() {
  return `
    <section class="core-values-slide">
      <div class="core-values-container">
        <h1 class="core-values-title">
          <span class="bni-red">BNI</span>のコアバリュー<span class="subtitle-text">(基本的価値観)</span>
        </h1>

        <div class="core-values-content">
          <div class="core-values-icon">
            <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="40" cy="30" r="12" fill="#808080" opacity="0.6"/>
              <path d="M 20 50 Q 20 42 28 42 L 52 42 Q 60 42 60 50 L 60 65 L 20 65 Z" fill="#808080" opacity="0.6"/>
              <circle cx="75" cy="25" r="14" fill="#808080" opacity="0.7"/>
              <path d="M 52 47 Q 52 38 62 38 L 88 38 Q 98 38 98 47 L 98 65 L 52 65 Z" fill="#808080" opacity="0.7"/>
            </svg>
          </div>

          <div class="core-values-main">
            <h2 class="core-values-heading">Building Relationships</h2>
            <h3 class="core-values-heading-jp">(関係構築)</h3>

            <p class="core-values-text">
              ひとりで成功できる人はいません。質の高い信頼関係を築き、サポートネットワークを構築し、自分の人脈を常に成長させることが、ビジネスそして人生で成功する上で大切です。
            </p>
          </div>
        </div>

        <div class="core-values-diagonal"></div>
      </div>
    </section>
  `;
}

/**
 * Phase 10.6: Generate Share Story Slide (PDF p.73)
 */
function generateShareStorySlide() {
  return `
    <section class="share-story-slide">
      <div class="share-story-container">
        <h1 class="share-story-kanji">栄光</h1>

        <div class="share-story-content">
          <h2 class="share-story-heading">Keep growing</h2>
          <p class="share-story-subtitle">〜貢献の絆で未来を創る〜</p>
        </div>

        <div class="share-story-diagonal"></div>
      </div>
    </section>
  `;
}

/**
 * Phase 10.7: Generate Member Introduction Slides (PDF p.42-59)
 * 18 pages of member introductions with photos
 */
function generateMemberIntroductionSlides() {
  let slides = '';
  const pageNumbers = [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59];

  pageNumbers.forEach(pageNum => {
    slides += `
      <section class="pdf-image-slide">
        <img src="../pdf_analysis/required_pages/page_${String(pageNum).padStart(3, '0')}.png"
             alt="Member Introduction ${pageNum}"
             class="pdf-full-image">
      </section>
    `;
  });

  return slides;
}

/**
 * Phase 10.8: Generate BNI Purpose Slides (PDF p.65-71)
 * 7 pages explaining BNI's purpose
 */
function generateBNIPurposeSlides() {
  let slides = '';

  // p.65: BNIの目的と概要（タイトル + テキスト）
  slides += `
    <section class="bni-purpose-p65">
      <h1 class="bni-purpose-title"><span class="bni-red">BNI</span>の目的と概要</h1>
      <div class="bni-purpose-content">
        <h2 class="bni-purpose-number">2.</h2>
        <p class="bni-purpose-text">
          BNIは、プロフェッショナルのためのビジネスネットワーキング組織であり、各専門分野につき1名のみがメンバーとして加入することが出来るのが特徴です。チャプターの唯一の目的は、メンバーに多くのビジネスをもたらす事です。「与えるものは、与えられる」という考え方の基づいて運営されています。<br>
          あなたがチャプターメンバーにビジネスリファーラルを提供したら、他のチャプターメンバーもあなたにリファーラルを出したいと考えますよね？
        </p>
      </div>
    </section>
  `;

  // p.66: BNIの目的と概要のコピペページ（8項目のリスト + 赤枠ハイライト）
  slides += `
    <section class="bni-purpose-p66">
      <h2 class="bni-purpose-p66-title"><span class="bni-red">BNI</span>の目的と概要のコピペページ</h2>
      <div class="bni-purpose-p66-content">
        <div class="bni-purpose-p66-left">
          <div class="bni-purpose-item">
            <span class="item-number">1.</span>
            <p>各チャプターでは、1つの専門分野につき1名のみ加入することが出来ます。<br>
            チャプターに既に入っている複数のチャプターに従業員を加入させている複数拠点的な企業も存在します。チャプターが大きくなるに連れ、加入したくてもカテゴリーが埋まってしまっていて、加入できないビジターも増えてくるでしょう。</p>
          </div>

          <div class="bni-purpose-item bni-purpose-highlight">
            <span class="item-number">2.</span>
            <p>BNIは、プロフェッショナルのためのビジネスネットワーキング組織であり、各専門分野につき1名のみがメンバーとして加入することが出来るのが特徴です。<br>
            チャプターの唯一の目的は、メンバーに多くのビジネスをもたらす事です。「与えるものは、与えられる」という考え方の基づいて運営されています。<br>
            あなたがチャプターメンバーにビジネスリファーラルを提供したら、他のチャプターメンバーもあなたにリファーラルを出したいと考えますよね？</p>
            <div class="highlight-date">12月12日</div>
          </div>

          <div class="bni-purpose-item">
            <span class="item-number">3.</span>
            <p>BNIのメンバーになれば、全メンバーが、あなたの営業担当になってくれるようなものです。各メンバーはメンバー全員の名刺が入っている名刺ファイルを持ち歩きます。そして、自分のクライアント、知り合い、友人がチャプターメンバーの提供している商品やサービスを必要としている事がわかったら、適切なメンバーを紹介することが可能です。</p>
          </div>

          <div class="bni-purpose-item">
            <span class="item-number">4.</span>
            <p>口コミは、もっとも費用対効果が高い広告ですBNIは、質の高いビジネスリファーラルを生み出し、支えるための体系的なシステムを提供します。あなたのビジネスにとってリファーラルが必要不可欠だとしたらBNIはあなたにぴったりの組織です。</p>
          </div>
        </div>

        <div class="bni-purpose-p66-right">
          <div class="bni-purpose-item">
            <span class="item-number">5.</span>
            <p>各チャプターでのネットワークは、メンバーにとどまりません。それぞれのメンバーに、数十人の知人がいます。そしてその知人にはまた、数十人の知人がいます。毎週交わされるビジネスリファーラルは、このようなネットワークの中から生まれます。一般的な少なくとも発足から6か月過ぎているBNIチャプターでは、メンバー間で毎月50件以上のリファーラルが交わされます。</p>
          </div>

          <div class="bni-purpose-item">
            <span class="item-number">6.</span>
            <p>BNIでは、ネットワーキングは「狩猟」よりも「農耕」に近いとメンバーに伝えています。信頼関係をはぐくむことが、リファーラルにつながっていきます。</p>
          </div>

          <div class="bni-purpose-item">
            <span class="item-number">7.</span>
            <p>どのようなビジネスを行っているのか、どのようなリファーラルを求めているのか、BNIメンバーは、ウィークリープレゼンテーションを通じて、リファーラルでビジネスを継続的に深く伝えることが可能になります。チャプターミーティングの引き、あなたのビジネスの成功に深く関わっているのです。</p>
          </div>

          <div class="bni-purpose-item">
            <span class="item-number">8.</span>
            <p>私たちは、誰もがプロフェッショナルとして最高に対し優位に立つ必要があります。この世提で、どれほど多くの人があなたと競合する事業をしているでしょうか？BNIでは、競合に対し優位に立つ事が出来ます。なぜならBNIでは各専門分野につき1名しかメンバーになれないため、同じチャプター内に競合が存在しないからです。</p>
          </div>
        </div>
      </div>
    </section>
  `;

  // p.67: BNIの概観（リファーラルの重要性 + 2つの画像）
  slides += `
    <section class="bni-overview-p67">
      <h1 class="bni-overview-title">BNIの概観</h1>
      <h2 class="bni-overview-subtitle">
        ～ビジネスにおける「<span class="bni-red">リファーラル</span>」の重要性～<br>
        多くの人達が、<span class="bni-red">リファーラル（紹介）</span>を通して<br>
        ビジネスを獲得しています。
      </h2>
      <div class="bni-overview-images">
        <img src="assets/images/slides/bni-purpose-business-team.jpg" alt="Business Team" class="bni-overview-img-left">
        <img src="assets/images/slides/bni-purpose-business-growth.jpg" alt="Business Growth" class="bni-overview-img-right">
      </div>
      <p class="bni-overview-bottom-text">
        BNIでは <span class="bni-red underline">メンバーにビジネスを提供すること</span> が最大の目的なのです。
      </p>
    </section>
  `;

  // p.68: BNIの概観（世界の統計）
  slides += `
    <section class="bni-overview-p68">
      <h1 class="bni-overview-title">BNIの概観</h1>
      <h2 class="bni-stats-title">世界の統計</h2>
      <div class="bni-stats-content">
        <div class="bni-stats-row">
          <div class="bni-stats-label">BNI参入国数</div>
          <div class="bni-stats-value">: <span class="bni-red">77カ国</span></div>
        </div>
        <div class="bni-stats-row">
          <div class="bni-stats-label">総メンバー数</div>
          <div class="bni-stats-value">: <span class="bni-red">346,150名超</span></div>
        </div>
        <div class="bni-stats-row">
          <div class="bni-stats-label">総チャプター数</div>
          <div class="bni-stats-value">: <span class="bni-red">11,390超</span></div>
        </div>
        <div class="bni-stats-divider"></div>
        <div class="bni-stats-row">
          <div class="bni-stats-label"><span class="bni-red">リファーラル</span> 数</div>
          <div class="bni-stats-value">: <span class="bni-red">約1400万件超</span></div>
        </div>
        <div class="bni-stats-row">
          <div class="bni-stats-label">得られたビジネス金額</div>
          <div class="bni-stats-value">: <span class="bni-red">約2兆2000億円</span></div>
        </div>
      </div>
      <p class="bni-stats-date">※2024年8月1日付</p>
    </section>
  `;

  // p.69: BNIの概観（日本の統計）
  slides += `
    <section class="bni-overview-p69">
      <h1 class="bni-overview-title">BNIの概観</h1>
      <h2 class="bni-stats-title">日本の統計</h2>
      <div class="bni-stats-content">
        <div class="bni-stats-row">
          <div class="bni-stats-label">BNI参加都道府県数</div>
          <div class="bni-stats-value">: <span class="bni-red">全国27都道府県</span></div>
        </div>
        <div class="bni-stats-row">
          <div class="bni-stats-label">総メンバー数</div>
          <div class="bni-stats-value">: <span class="bni-red">12,571名</span></div>
        </div>
        <div class="bni-stats-row">
          <div class="bni-stats-label">総チャプター数</div>
          <div class="bni-stats-value">: <span class="bni-red">381チャプター</span></div>
        </div>
        <div class="bni-stats-divider"></div>
        <div class="bni-stats-row">
          <div class="bni-stats-label"><span class="bni-red">リファーラル</span> 数</div>
          <div class="bni-stats-value">: <span class="bni-red">約110万件以上</span></div>
        </div>
        <div class="bni-stats-row">
          <div class="bni-stats-label">得られたビジネス金額</div>
          <div class="bni-stats-value">: <span class="bni-red">約1,100億円超</span></div>
        </div>
      </div>
      <p class="bni-stats-date">※2024年9月29日付</p>
    </section>
  `;

  // p.70: BNIの理念（Givers Gain）
  slides += `
    <section class="bni-philosophy-p70">
      <h1 class="bni-philosophy-title">BNIの理念</h1>
      <h2 class="givers-gain-logo"><span class="bracket">[</span>Givers Gain<span class="bracket">]</span><sup>®</sup></h2>
      <p class="givers-gain-text">与えるものは与えられる</p>
      <p class="bni-philosophy-description">
        他の人に惜しみなくビジネスを提供すれば<br>
        自分も他の人からビジネスを提供してもらえる。
      </p>
    </section>
  `;

  // p.71: 栄勝（Keep growing）
  slides += `
    <section class="bni-motto-p71">
      <h1 class="eikachi-logo">栄勝</h1>
      <h2 class="keep-growing-title">Keep growing</h2>
      <p class="keep-growing-subtitle">～貢献の絆で未来を創る～</p>
    </section>
  `;

  return slides;
}

/**
 * Phase 11.1: Visitor Self-Introduction Template (PDF p.182)
 * HTML/CSS version - Business Breakout intro slide
 */
function generateVisitorSelfIntroTemplateSlide() {
  return `
    <section class="business-breakout-intro-slide" data-timer="visitor_intro">
      <div class="business-breakout-left">
        <img src="assets/images/slides/page_182.png" alt="Business Meeting" class="business-breakout-photo" />
      </div>
      <div class="business-breakout-right">
        <h1 class="business-breakout-title">ビジネス<br>ブレイクアウト</h1>
        <p class="business-breakout-subtitle">ウィークリープレゼンテーションを聴いて、<br>誰のためにどんな事ができるか<br>お互いにシェアしましょう。</p>
      </div>
    </section>
  `;
}

/**
 * Phase 11.2: Business Breakout Slides (PDF p.198-200)
 * HTML/CSS version - Main Presentation intro and Speaker Rotation title
 */
function generateBusinessBreakoutSlides() {
  let slides = '';

  // p.198: メインプレゼンテーション
  slides += `
    <section class="main-presentation-intro-slide-v2" data-timer="business_breakout">
      <div class="main-presentation-left">
        <img src="assets/images/slides/page_198.png" alt="Microphone" class="main-presentation-photo" />
      </div>
      <div class="main-presentation-right">
        <h1 class="main-presentation-title">メイン<br>プレゼンテーション</h1>
      </div>
    </section>
  `;

  // p.199: スピーカーローテーション（スキップ - 動的データ必要）
  // This slide will be handled by the existing generateSpeakerRotationSlide function

  // p.200: スピーカーローテーション（タイトルスライド）
  slides += `
    <section class="speaker-rotation-title-slide">
      <div class="speaker-rotation-title-bar">
        <h1 class="speaker-rotation-title-text">スピーカーローテーション</h1>
      </div>
    </section>
  `;

  return slides;
}

/**
 * Phase 11.3: Vice President Statistics Slides (PDF p.203-206)
 * HTML/CSS dynamic version with database integration
 */
function generateVicePresidentStatsSlides(rotationData = null, presenterDetail = null) {
  let slides = '';

  // Page 203: Speaker Rotation Table
  slides += generateSpeakerRotationSlide(rotationData);

  // Page 204: Main Presenter Detail
  slides += generateMainPresenterSlide(presenterDetail);

  // Page 205: Kanso (Complete Victory)
  slides += generateKansoSlide();

  // Page 206: Referral & Recommendation Message
  slides += generateReferralMessageSlide();

  return slides;
}

/**
 * Page 203: Speaker Rotation Table
 */
function generateSpeakerRotationSlide(rotationData) {
  let tableRows = '';

  if (rotationData && rotationData.length > 0) {
    rotationData.forEach(row => {
      const rowClass = row.is_current ? 'current-row' : '';
      const memberName = row.member_name || '';
      const topic = row.topic || '';
      const referralTarget = row.referral_target || '';

      tableRows += `
        <tr class="${rowClass}">
          <td>${row.date_display}</td>
          <td>
            <div class="presenter-name">${memberName}</div>
            ${topic ? `<div class="presenter-topic">(${topic})</div>` : ''}
          </td>
          <td>${referralTarget}</td>
        </tr>
      `;
    });
  } else {
    // データがない場合はダミー行を表示
    for (let i = 0; i < 4; i++) {
      tableRows += `
        <tr>
          <td>-</td>
          <td><div class="presenter-name">-</div></td>
          <td>-</td>
        </tr>
      `;
    }
  }

  return `
    <section class="speaker-rotation-slide">
      <h2 class="speaker-rotation-title">スピーカーローテーション</h2>
      <table class="speaker-rotation-table">
        <thead>
          <tr>
            <th>日 程</th>
            <th>メインプレゼン</th>
            <th>ご紹介してほしい方</th>
          </tr>
        </thead>
        <tbody>
          ${tableRows}
        </tbody>
      </table>
    </section>
  `;
}

/**
 * Page 204: Main Presenter Detail
 */
function generateMainPresenterSlide(presenterDetail) {
  // デフォルト値
  let memberName = '伊東 健太';
  let topic = 'ラジコン草刈り代行';
  let company = 'ブルーベリーファームうすき';
  let highlightChar = '健';
  let imagePath = '../pdf_analysis/required_pages/page_204.png';

  // データがあれば上書き
  if (presenterDetail && presenterDetail.member_name) {
    memberName = presenterDetail.member_name;
    topic = presenterDetail.topic || '';
    company = presenterDetail.user_company || '';

    // 名前をハイライト文字で分割
    if (presenterDetail.highlight_char) {
      highlightChar = presenterDetail.highlight_char;
    } else {
      // 自動検出: 名前の2文字目をデフォルトでハイライト
      if (memberName.length > 1) {
        highlightChar = memberName.charAt(1);
      }
    }
  }

  // 名前をハイライト処理
  const nameHTML = memberName.split('').map(char => {
    if (char === highlightChar) {
      return `<span class="name-highlight">${char}</span>`;
    }
    return char;
  }).join('');

  return `
    <section class="main-presenter-slide">
      <div class="main-presenter-container">
        <div class="presenter-image-container">
          <img src="${imagePath}" alt="${memberName}" class="presenter-image" />
        </div>
        <div class="presenter-info-container">
          <div class="presenter-label-top">メインプレゼンテーション</div>
          ${topic ? `<div class="presenter-topic-label">${topic}</div>` : ''}
          <div class="presenter-name-large">${nameHTML}</div>
          ${company ? `<div class="presenter-company">${company}</div>` : ''}
        </div>
      </div>
    </section>
  `;
}

/**
 * Page 205: Kanso (Complete Victory)
 */
function generateKansoSlide() {
  return `
    <section class="kanso-slide">
      <div class="kanso-main-text">完勝</div>
      <div class="kanso-english-text">Keep growing</div>
      <div class="kanso-subtitle">〜貢献の絆で未来を創る〜</div>
    </section>
  `;
}

/**
 * Page 206: Referral & Recommendation Message
 */
function generateReferralMessageSlide() {
  return `
    <section class="referral-message-slide">
      <div class="referral-message-left">
        <img src="../pdf_analysis/required_pages/page_206.png" alt="Referral" class="referral-message-image" />
      </div>
      <div class="referral-message-right">
        <div class="referral-message-text">
          リファーラル<br />と<br />推薦のことば
        </div>
      </div>
    </section>
  `;
}

/**
 * Phase 11.4: Referral Trust Slides (PDF p.246-253)
 * HTML/CSS version - 11 static slides
 */
function generateReferralTrustSlides() {
  let slides = '';

  // p.246: BNIとは？
  slides += `
    <section class="referral-trust-slide bni-overview-slide">
      <div class="bni-overview-container">
        <div class="bni-overview-left">
          <h2 class="bni-overview-title">BNIとは？</h2>
          <div class="bni-overview-list">
            <div class="bni-overview-item negative">
              <span class="marker">✕</span>
              <span class="text">異業種交流会</span>
            </div>
            <div class="bni-overview-item positive">
              <span class="marker">○</span>
              <span class="text">ビジネスチーム</span>
            </div>
          </div>
        </div>
        <div class="bni-overview-right">
          <img src="assets/images/slides/page_246.png" alt="BNI Business Team" class="bni-overview-image" />
        </div>
      </div>
      <div class="bni-logo-bottom">BNI</div>
    </section>
  `;

  // p.247: ビジネスの課題を解決できる場所
  slides += `
    <section class="referral-trust-slide business-solution-slide">
      <div class="business-solution-container">
        <div class="business-solution-title-box">
          <h2 class="business-solution-title">ビジネスの課題を解決できる場所</h2>
        </div>
        <div class="business-solution-image">
          <img src="assets/images/slides/page_247.png" alt="Business Solution" />
        </div>
      </div>
      <div class="bni-logo-bottom">BNI</div>
    </section>
  `;

  // p.248: 5つの BENEFITS
  slides += `
    <section class="referral-trust-slide benefits-title-slide">
      <div class="benefits-title-container">
        <h2 class="benefits-title-main">5つの <span class="benefits-word">BENEFITS</span></h2>
        <div class="benefits-hand-image">
          <img src="assets/images/slides/page_248.png" alt="5 Benefits" />
        </div>
      </div>
      <div class="bni-logo-bottom-right">BNI</div>
    </section>
  `;

  // p.249: あなたの営業チームは何人？
  slides += `
    <section class="referral-trust-slide sales-team-question-slide">
      <h1 class="sales-team-question">あなたの<span class="highlight-red">営業チーム</span>は何人？</h1>
      <div class="bni-logo-bottom-right">BNI</div>
    </section>
  `;

  // p.250: 売上獲得までのプロセス
  slides += `
    <section class="referral-trust-slide sales-process-slide">
      <h2 class="sales-process-title">売上獲得までのプロセス</h2>
      <div class="sales-process-diagram">
        <div class="process-step marketing-step">
          <div class="process-box-outer marketing-outer">
            <div class="process-box-inner">マーケティング</div>
          </div>
          <div class="process-arrow">→</div>
          <div class="process-box sales-box">セールス</div>
          <div class="process-arrow">→</div>
          <div class="process-box contract-box">成約</div>
        </div>
      </div>
      <div class="sales-process-descriptions">
        <p class="desc-marketing"><span class="highlight-green">マーケティング</span>...見込み客を獲得する方法</p>
        <p class="desc-sales"><span class="highlight-blue">セールス</span>...見込み客を顧客に変える方法</p>
        <p class="desc-contract"><strong>成約...ビジネス獲得の成果</strong></p>
      </div>
      <div class="bni-logo-bottom">BNI</div>
    </section>
  `;

  // p.251: メンバーの力を借りましょう
  slides += `
    <section class="referral-trust-slide member-power-slide">
      <h1 class="member-power-title"><span class="highlight-red">メンバー</span>の力を借りましょう</h1>
      <div class="bni-logo-bottom-right">BNI</div>
    </section>
  `;

  // p.252: Benefit 1 - 49人以上のマーケティングチーム
  slides += `
    <section class="referral-trust-slide benefit-one-slide">
      <div class="benefit-header-badge">Benefit 1</div>
      <h2 class="benefit-one-title">もしも...<br><span class="highlight-red">49人以上のマーケティングチーム</span></h2>
      <div class="benefit-one-images">
        <img src="assets/images/slides/page_252.png" alt="Marketing Team" />
      </div>
      <div class="bni-logo-bottom">BNI</div>
    </section>
  `;

  // p.253: あなたに代わって営業活動
  slides += `
    <section class="referral-trust-slide sales-activity-slide">
      <h2 class="sales-activity-title">あなたに<span class="highlight-red">代わって営業活動</span></h2>
      <div class="sales-activity-image">
        <img src="assets/images/slides/page_253.png" alt="Sales Activity" />
      </div>
      <div class="bni-logo-bottom">BNI</div>
    </section>
  `;

  return slides;
}

/**
 * Phase 11.5: Closing Slides (PDF p.303-309)
 * HTML/CSS version - 7 slides
 */
function generateClosingSlides() {
  let slides = '';

  // p.303: 書記兼会計より
  slides += `
    <section class="closing-slide closing-title-slide">
      <h1 class="closing-main-title">書記兼会計より</h1>
      <div class="closing-diagonal"></div>
    </section>
  `;

  // p.304: 終礼で伝えていきたいこと
  slides += `
    <section class="closing-slide closing-content-slide">
      <h2 class="closing-section-title">終礼で伝えていきたいこと</h2>
      <div class="closing-list">
        <div class="closing-list-item">40名リスト（メンバーシップの審査で新メンバーに質問してる）</div>
        <div class="closing-list-item">今週ある外部のイベント案内　（○月○日（○）○○時〜</div>
        <div class="closing-list-item">40秒プレゼンのクオリティアップ</div>
        <div class="closing-list-item">毎週6名のビジター　150名カウントダウン</div>
        <div class="closing-list-item">4月1日から11名のビジター参加者　139/150</div>
        <div class="closing-list-item">募集カテゴリーのアナウンス</div>
        <div class="closing-list-item">自分で呼べない人は、呼ぶのが得意な人に繋ぐ</div>
      </div>
      <div class="closing-diagonal"></div>
    </section>
  `;

  // p.305: 各コーディネーターより
  slides += `
    <section class="closing-slide closing-title-slide">
      <h1 class="closing-main-title">各コーディネーターより</h1>
      <div class="closing-diagonal"></div>
    </section>
  `;

  // p.306: その他連絡事項
  slides += `
    <section class="closing-slide closing-title-slide">
      <h1 class="closing-main-title">その他連絡事項</h1>
      <div class="closing-diagonal"></div>
    </section>
  `;

  // p.307: ディレクターより
  slides += `
    <section class="closing-slide closing-title-slide">
      <h1 class="closing-main-title">ディレクターより</h1>
      <div class="closing-diagonal"></div>
    </section>
  `;

  // p.308: 来週また元気にお会いしましょう
  slides += `
    <section class="closing-slide closing-message-slide">
      <h1 class="closing-message-text">来週また元気にお会いしましょう♪</h1>
      <div class="closing-diagonal"></div>
    </section>
  `;

  // p.309: 栄勝 Keep growing
  slides += `
    <section class="closing-slide closing-motto-slide">
      <h1 class="closing-motto-kanji">栄勝</h1>
      <h2 class="closing-motto-english">Keep growing</h2>
      <p class="closing-motto-subtitle">〜貢献の絆で未来を創る〜</p>
      <div class="closing-diagonal"></div>
    </section>
  `;

  return slides;
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
 * Phase 4: Generate New Members Slides
 */
function generateNewMembersSlides(config) {
  const newMembers = config.new_members || [];
  if (newMembers.length === 0) return '';

  let slides = '';

  // Intro Slide
  slides += `
    <section class="new-members-intro-slide">
      <h2 class="new-members-title">新入会メンバー<br>承認式</h2>
    </section>
  `;

  // Individual member slides
  newMembers.forEach(member => {
    const photoUrl = member.photo || 'assets/images/default-avatar.svg';
    const joinedDate = member.joined_date || '';

    slides += `
      <section class="new-member-slide">
        <h2 class="new-member-header">新入会メンバー</h2>
        ${joinedDate ? `<div class="new-member-date">(${escapeHtml(joinedDate)})</div>` : ''}

        <div class="new-member-content">
          <div class="new-member-photo-container">
            <img src="${escapeHtml(photoUrl)}" alt="${escapeHtml(member.name)}" class="new-member-photo" />
          </div>
          <div class="new-member-info">
            <div class="new-member-name">${escapeHtml(member.name)}</div>
            <div class="new-member-company">${escapeHtml(member.company)}</div>
            <div class="new-member-category">(${escapeHtml(member.category)})</div>
          </div>
        </div>
      </section>
    `;
  });

  return slides;
}

/**
 * Phase 12: Generate Networking Education Corner Slide
 */
function generateNetworkingEducationSlide(education) {
  const photoUrl = education.photo || 'assets/images/default-avatar.svg';

  return `
    <section class="networking-education-slide">
      <h2 class="networking-education-title">ネットワーキング学習コーナー</h2>
      <div class="networking-education-subtitle">Networking Education Corner</div>

      <div class="networking-education-content">
        <div class="networking-education-presenter-box">
          <div class="networking-education-presenter-photo">
            <img src="${escapeHtml(photoUrl)}" alt="${escapeHtml(education.presenter)}" />
          </div>
          <div class="networking-education-presenter-info">
            <div class="networking-education-presenter-name">${escapeHtml(education.presenter)}</div>
            <div class="networking-education-presenter-role">${escapeHtml(education.role)}</div>
            <div class="networking-education-presenter-category">(${escapeHtml(education.category)})</div>
          </div>
        </div>

        <div class="networking-education-topic">
          <i class="fas fa-book-open topic-icon"></i>
          <div class="topic-text">今週のテーマ: BNIのコアバリュー</div>
        </div>
      </div>
    </section>
  `;
}

/**
 * Phase 8: Generate Secretary Slide
 */
function generateSecretarySlide(secretary) {
  return `
    <section class="secretary-slide">
      <h2 class="secretary-title">書記兼会計より</h2>
      <div class="secretary-info-box">
        <div class="secretary-name">${escapeHtml(secretary.name)}</div>
        <div class="secretary-role">${escapeHtml(secretary.company)}</div>
        <div class="secretary-category">(${escapeHtml(secretary.category)})</div>
      </div>
      <div class="secretary-message">
        <p>${escapeHtml(secretary.message)}</p>
      </div>
    </section>
  `;
}

/**
 * Phase 6: Generate Happy Birthday Slide
 */
function generateHappyBirthdaySlide(birthdays) {
  const birthdayNames = birthdays.map(b => escapeHtml(b.name || b)).join('、');

  return `
    <section class="happy-birthday-slide">
      <div class="birthday-header">
        <i class="fas fa-birthday-cake cake-icon"></i>
        <h2 class="birthday-title">今週のハッピーバースデー</h2>
        <i class="fas fa-birthday-cake cake-icon"></i>
      </div>

      <div class="birthday-celebration">
        <i class="fas fa-gift party-icon"></i>
      </div>

      <div class="birthday-message">
        <i class="fas fa-heart heart-icon"></i>
        お誕生日おめでとうございます
        <i class="fas fa-heart heart-icon"></i>
      </div>

      ${birthdays.length > 0 ? `
        <div class="birthday-names">
          ${birthdayNames}
        </div>
      ` : ''}
    </section>
  `;
}

/**
 * Phase 7: Generate Weekly NO.1 Slide (Past Records)
 */
function generateWeeklyNo1Slide(weeklyNo1) {
  return `
    <section class="weekly-no1-slide">
      <div class="weekly-no1-header">
        <div class="weekly-no1-date">${escapeHtml(weeklyNo1.date)}のPALMSレポートより</div>
        <h2 class="weekly-no1-title">先週の週間NO.1</h2>
      </div>

      <div class="weekly-no1-grid">
        <div class="weekly-no1-item">
          <div class="weekly-no1-category">外部リファーラル</div>
          <div class="weekly-no1-count">${escapeHtml(weeklyNo1.referral.count)}<i class="fas fa-star sparkle-icon"></i></div>
          <div class="weekly-no1-winner">${escapeHtml(weeklyNo1.referral.name)}</div>
        </div>

        <div class="weekly-no1-item">
          <div class="weekly-no1-category">ビジター招待</div>
          <div class="weekly-no1-count">${escapeHtml(weeklyNo1.visitor.count)}<i class="fas fa-star sparkle-icon"></i></div>
          <div class="weekly-no1-winner">${escapeHtml(weeklyNo1.visitor.name)}</div>
        </div>

        <div class="weekly-no1-item">
          <div class="weekly-no1-category">1to1</div>
          <div class="weekly-no1-count">${escapeHtml(weeklyNo1.one_to_one.count)}<i class="fas fa-star sparkle-icon"></i></div>
          <div class="weekly-no1-winner">${escapeHtml(weeklyNo1.one_to_one.name)}</div>
        </div>
      </div>

      <div class="weekly-no1-footer">
        日々のメンバーへの貢献ありがとうございます<i class="fas fa-star sparkle-icon"></i>
      </div>
    </section>
  `;
}

/**
 * Phase 9: Generate Director Slide
 */
function generateDirectorSlide(director) {
  return `
    <section class="director-slide">
      <h2 class="director-title">ディレクターより</h2>
      <div class="director-info-box">
        <div class="director-name">${escapeHtml(director.name)}</div>
      </div>
      <div class="director-message">
        <p>${escapeHtml(director.message)}</p>
      </div>
    </section>
  `;
}

/**
 * Phase 10: Generate Visitor Hosts Slide
 */
function generateVisitorHostsSlide(config) {
  const visitorHosts = config.visitor_hosts || [];
  if (visitorHosts.length === 0) return '';

  return `
    <section class="visitor-hosts-slide">
      <h2 class="visitor-hosts-title">ビジターホスト</h2>
      <div class="visitor-hosts-grid">
        ${visitorHosts.map(host => {
          const photoUrl = host.photo || 'assets/images/default-avatar.svg';
          return `
            <div class="visitor-host-card">
              <div class="visitor-host-photo-container">
                <img src="${escapeHtml(photoUrl)}" alt="${escapeHtml(host.name)}" class="visitor-host-photo" />
              </div>
              <div class="visitor-host-info">
                <div class="visitor-host-name">${escapeHtml(host.name)}</div>
                <div class="visitor-host-company">${escapeHtml(host.company)}</div>
                <div class="visitor-host-category">${escapeHtml(host.category)}</div>
              </div>
            </div>
          `;
        }).join('')}
      </div>
    </section>
  `;
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

/**
 * Phase 3: Generate Referral Announcements Template Slide
 */
function generateReferralAnnouncementsSlide() {
  return `
    <section class="referral-template-slide">
      <h2 class="referral-template-title">リファーラル・推薦発表</h2>
      <div class="referral-template-subtitle">Referral Announcements</div>
      <div class="referral-template-instruction">
        <i class="fas fa-handshake"></i>
        <p>メンバーからのリファーラルと推薦を発表してください</p>
      </div>
    </section>
  `;
}

/**
 * Phase 14: Generate Visitor Self-Introduction Template Slide
 */
function generateVisitorIntroductionSlide() {
  return `
    <section class="visitor-intro-template-slide">
      <h2 class="visitor-intro-title">ビジター様による自己紹介</h2>
      <div class="visitor-intro-subtitle">Visitor Self-Introduction</div>
      <div class="visitor-intro-instruction">
        <i class="fas fa-users"></i>
        <p>ビジターの皆様、1分程度で自己紹介をお願いします</p>
      </div>
    </section>
  `;
}

/**
 * Phase 16: Generate Today's Comment Template Slide
 */
function generateTodaysCommentSlide() {
  return `
    <section class="todays-comment-template-slide">
      <h2 class="todays-comment-title">様による本日の一言感想</h2>
      <div class="todays-comment-subtitle">Today's Comment</div>
      <div class="todays-comment-instruction">
        <i class="fas fa-comment-dots"></i>
        <p>本日の感想を一言お願いします</p>
      </div>
    </section>
  `;
}

/**
 * Phase 17: Generate Visitor Orientation Slide
 */
function generateVisitorOrientationSlide(facilitator) {
  return `
    <section class="visitor-orientation-slide">
      <h2 class="visitor-orientation-title">ビジターオリエンテーション</h2>
      <div class="visitor-orientation-subtitle">Visitor Orientation</div>
      <div class="visitor-orientation-facilitator">
        <div class="facilitator-label">ファシリテーター</div>
        <div class="facilitator-name">${escapeHtml(facilitator.name)}</div>
        <div class="facilitator-title">${escapeHtml(facilitator.title)}</div>
      </div>
    </section>
  `;
}

/**
 * Phase 18: Generate Coordinators Slide
 */
function generateCoordinatorsSlide() {
  return `
    <section class="coordinators-template-slide">
      <h2 class="coordinators-title">各コーディネーターより</h2>
      <div class="coordinators-subtitle">From Coordinators</div>
      <div class="coordinators-instruction">
        <i class="fas fa-bullhorn"></i>
        <p>各コーディネーターからのお知らせ・報告をお願いします</p>
      </div>
    </section>
  `;
}

/**
 * Phase 5: Generate Monthly Champions Slides
 */
function generateMonthlyChampionsSlides(config) {
  const champions = config.monthly_champions || {};
  let slides = '';

  // Intro Slide: All Champion Categories
  slides += `
    <section class="champions-intro-slide">
      <h2 class="champions-main-title">月間チャンピオン</h2>
      <div class="champion-categories">
        <div class="category-badge">リファーラルチャンピオン</div>
        <div class="category-badge">バリューチャンピオン</div>
        <div class="category-badge">ビジターチャンピオン</div>
        <div class="category-badge">1to1チャンピオン</div>
        <div class="category-badge">CEUチャンピオン</div>
      </div>
    </section>
  `;

  // Individual Champion Slides
  const championTypes = ['referral', 'value', 'visitor', 'one_to_one', 'ceu'];

  championTypes.forEach(type => {
    const championData = champions[type];
    if (!championData) return;

    const winner1 = championData.winners[0] || {};
    const winner2 = championData.winners[1] || {};
    const winner3 = championData.winners[2] || {};

    slides += `
      <section class="champion-detail-slide">
        <div class="champion-header">${escapeHtml(championData.title)}</div>
        <div class="champion-summary">お言葉　${escapeHtml(championData.summary)}</div>

        ${winner1.category ? `<div class="winner-category">${escapeHtml(winner1.category)}</div>` : ''}

        <div class="winner-main">
          <div class="winner-rank-badge">【1位】</div>
          <div class="winner-info">
            <div class="winner-name">${escapeHtml(winner1.name)}さん</div>
            ${winner1.count ? `<div class="winner-count"></div>` : ''}
          </div>
        </div>

        <div class="runners-up">
          ${winner2.name ? `
            <div class="runner-up">
              <span class="runner-rank">【2位】</span>
              <span class="runner-name">: ${escapeHtml(winner2.name)}さん</span>
              ${winner2.count ? `<span class="runner-count">${escapeHtml(winner2.count)}</span>` : ''}
            </div>
          ` : ''}

          ${winner3.name ? `
            <div class="runner-up">
              <span class="runner-rank">【3位】</span>
              <span class="runner-name">: ${escapeHtml(winner3.name)}</span>
              ${winner3.count ? `<span class="runner-count">${escapeHtml(winner3.count)}</span>` : ''}
            </div>
          ` : ''}
        </div>
      </section>
    `;
  });

  return slides;
}


/**
 * Generate Member 60-second Pitch Slides
 * 各メンバーのメインプレゼンスライドを生成（カウントダウンタイマー付き）
 */
function generateMemberPitchSlides(members) {
  if (!members || members.length === 0) return '';

  let slides = '';

  // Title slide for pitch section
  slides += `
    <section class="title-slide">
      <h1>メンバー30秒ピッチ</h1>
      <p class="subtitle">各メンバー30秒</p>
    </section>
  `;

  // Generate individual pitch slides for each member
  members.forEach(member => {
    const pitchTime = member.pitch_time || 33;
    const industryIcon = member.industry_icon || 'briefcase';
    const photoUrl = member.photo || '../assets/images/profile-placeholder.svg';

    slides += `
      <section class="pitch-slide">
        <div class="pitch-slide-container">
          <div class="pitch-profile-section">
            <div class="pitch-photo">
              <img src="${escapeHtml(photoUrl)}" alt="${escapeHtml(member.name)}" />
            </div>
            <div class="pitch-info">
              <div class="pitch-header">
                <i class="fas fa-${escapeHtml(industryIcon)} pitch-icon"></i>
                <h2 class="pitch-member-name">${escapeHtml(member.name)}</h2>
              </div>
              <div class="pitch-details">
                <div class="pitch-company">
                  <i class="fas fa-building"></i>
                  <span>${escapeHtml(member.company)}</span>
                </div>
                <div class="pitch-category">
                  <i class="fas fa-tag"></i>
                  <span>${escapeHtml(member.category)}</span>
                </div>
                ${member.team ? `
                  <div class="pitch-team">
                    <i class="fas fa-users"></i>
                    <span>チーム ${escapeHtml(member.team)}</span>
                  </div>
                ` : ''}
              </div>
            </div>
          </div>

          <div class="countdown-container">
            <div class="countdown-label">残り時間</div>
            <div class="countdown-timer" data-seconds="${pitchTime}">${pitchTime}</div>
            <div class="countdown-progress">
              <div class="countdown-progress-bar" style="width: 100%;"></div>
            </div>
          </div>
        </div>
      </section>
    `;
  });

  return slides;
}

/**
 * Generate Monthly Ranking Slides
 * 月間ランキングスライドを生成（4種類）
 */
function generateMonthlyRankingSlides(rankingData) {
  if (!rankingData) return '';

  let slides = '';
  const today = new Date();
  const previousMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
  const monthName = previousMonth.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long' });

  // Title slide for rankings
  slides += `
    <section class="title-slide">
      <h1>月間ランキング発表</h1>
      <p class="subtitle">${monthName}</p>
      <p class="branding">Givers Gain® | BNI Slide System</p>
    </section>
  `;

  // 1. リファーラル金額ランキング
  if (rankingData.referral_amount && rankingData.referral_amount.length > 0) {
    slides += `
      <section>
        <h2><i class="fas fa-dollar-sign"></i> リファーラル金額ランキング</h2>
        <div class="ranking-table-container">
          <table class="ranking-table">
            <thead>
              <tr>
                <th style="width: 80px;">順位</th>
                <th>メンバー名</th>
                <th style="width: 200px;">金額</th>
              </tr>
            </thead>
            <tbody>
    `;

    rankingData.referral_amount.forEach((entry, index) => {
      const rankClass = index === 0 ? 'rank-1' : index === 1 ? 'rank-2' : index === 2 ? 'rank-3' : index === 3 ? 'rank-4' : 'rank-5';
      const medalIcon = index === 0 ? '<i class="fas fa-trophy" style="color: #FFD700;"></i> ' :
                        index === 1 ? '<i class="fas fa-medal" style="color: #C0C0C0;"></i> ' :
                        index === 2 ? '<i class="fas fa-medal" style="color: #CD7F32;"></i> ' : '';
      slides += `
        <tr class="${rankClass}">
          <td>${medalIcon}${entry.rank}</td>
          <td>${escapeHtml(entry.name)}</td>
          <td><span class="ranking-amount">¥${entry.value.toLocaleString()}</span></td>
        </tr>
      `;
    });

    slides += `
            </tbody>
          </table>
        </div>
      </section>
    `;
  }

  // 2. ビジター紹介数ランキング
  if (rankingData.visitor_count && rankingData.visitor_count.length > 0) {
    slides += `
      <section>
        <h2><i class="fas fa-users"></i> ビジター紹介数ランキング</h2>
        <div class="ranking-table-container">
          <table class="ranking-table">
            <thead>
              <tr>
                <th style="width: 80px;">順位</th>
                <th>メンバー名</th>
                <th style="width: 200px;">紹介数</th>
              </tr>
            </thead>
            <tbody>
    `;

    rankingData.visitor_count.forEach((entry, index) => {
      const rankClass = index === 0 ? 'rank-1' : index === 1 ? 'rank-2' : index === 2 ? 'rank-3' : index === 3 ? 'rank-4' : 'rank-5';
      const medalIcon = index === 0 ? '<i class="fas fa-trophy" style="color: #FFD700;"></i> ' :
                        index === 1 ? '<i class="fas fa-medal" style="color: #C0C0C0;"></i> ' :
                        index === 2 ? '<i class="fas fa-medal" style="color: #CD7F32;"></i> ' : '';
      slides += `
        <tr class="${rankClass}">
          <td>${medalIcon}${entry.rank}</td>
          <td>${escapeHtml(entry.name)}</td>
          <td><span class="ranking-amount">${entry.value}人</span></td>
        </tr>
      `;
    });

    slides += `
            </tbody>
          </table>
        </div>
      </section>
    `;
  }

  // 3. 1to1回数ランキング
  if (rankingData.one_to_one_count && rankingData.one_to_one_count.length > 0) {
    slides += `
      <section>
        <h2><i class="fas fa-handshake"></i> 1to1回数ランキング</h2>
        <div class="ranking-table-container">
          <table class="ranking-table">
            <thead>
              <tr>
                <th style="width: 80px;">順位</th>
                <th>メンバー名</th>
                <th style="width: 200px;">実施回数</th>
              </tr>
            </thead>
            <tbody>
    `;

    rankingData.one_to_one_count.forEach((entry, index) => {
      const rankClass = index === 0 ? 'rank-1' : index === 1 ? 'rank-2' : index === 2 ? 'rank-3' : index === 3 ? 'rank-4' : 'rank-5';
      const medalIcon = index === 0 ? '<i class="fas fa-trophy" style="color: #FFD700;"></i> ' :
                        index === 1 ? '<i class="fas fa-medal" style="color: #C0C0C0;"></i> ' :
                        index === 2 ? '<i class="fas fa-medal" style="color: #CD7F32;"></i> ' : '';
      slides += `
        <tr class="${rankClass}">
          <td>${medalIcon}${entry.rank}</td>
          <td>${escapeHtml(entry.name)}</td>
          <td><span class="ranking-amount">${entry.value}回</span></td>
        </tr>
      `;
    });

    slides += `
            </tbody>
          </table>
        </div>
      </section>
    `;
  }

  return slides;
}

/**
 * Generate Visitor Introduction List Slides
 * ビジターご紹介一覧スライドを生成（visitor_introductionsテーブルから）
 */
function generateVisitorIntroductionListSlides(visitorIntroductions) {
  let slides = "";

  const itemsPerPage = 5;
  const totalPages = Math.ceil(visitorIntroductions.length / itemsPerPage);

  for (let page = 0; page < totalPages; page++) {
    const start = page * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = visitorIntroductions.slice(start, end);

    slides += `
      <section>
        <h2>ビジターご紹介${totalPages > 1 ? ` (${page + 1}/${totalPages})` : ""}</h2>
        <table>
          <thead>
            <tr>
              <th>お名前</th>
              <th>会社名（屋号）</th>
              <th>専門分野</th>
              <th>スポンサー</th>
              <th>アテンド</th>
            </tr>
          </thead>
          <tbody>
    `;

    pageData.forEach(visitor => {
      slides += `
        <tr>
          <td><strong>${escapeHtml(visitor.visitor_name)}</strong></td>
          <td>${escapeHtml(visitor.company || "-")}</td>
          <td>${escapeHtml(visitor.specialty || "-")}</td>
          <td>${escapeHtml(visitor.sponsor)}</td>
          <td>${escapeHtml(visitor.attendant)}</td>
        </tr>
      `;
    });

    slides += `
          </tbody>
        </table>
      </section>
    `;
  }

  return slides;
}

/**
 * Generate Visitor Self-Introduction Slides
 * ビジター自己紹介スライドを生成（visitor_introductionsテーブルから）
 */
function generateVisitorSelfIntroductionSlides(visitorIntroductions) {
  let slides = "";

  visitorIntroductions.forEach(visitor => {
    slides += `
      <section class="visitor-slide">
        <h2 class="visitor-name">${escapeHtml(visitor.visitor_name)}</h2>
        <p class="visitor-company">${escapeHtml(visitor.company || "")}</p>
        <p class="visitor-industry">${escapeHtml(visitor.specialty || "")}</p>
        <div class="visitor-prompt">
          <p class="prompt-title">自己紹介をお願いします</p>
          <div class="prompt-themes">
            <div class="theme-item"><i class="fas fa-building"></i> 会社名（屋号）・事業内容</div>
            <div class="theme-item"><i class="fas fa-briefcase"></i> ご自身のお仕事について</div>
            <div class="theme-item"><i class="fas fa-bullseye"></i> 今日の参加目的</div>
            <div class="theme-item"><i class="fas fa-heart"></i> 趣味・好きなこと</div>
          </div>
        </div>
        <div class="visitor-meta">
          <p><i class="fas fa-handshake"></i> スポンサー: ${escapeHtml(visitor.sponsor)}</p>
          <p><i class="fas fa-user-friends"></i> アテンド: ${escapeHtml(visitor.attendant)}</p>
        </div>
      </section>
    `;
  });

  return slides;
}

/**
 * Generate Networking Learning Corner Slide
 * ネットワーキング学習コーナースライドを生成（networking_learning_presentersテーブルから）
 */
function generateNetworkingLearningSlide(presenter) {
  let slides = '';

  // タイトルスライドを追加
  slides += `
    <section class="networking-education-slide">
      <h2 class="networking-education-title">ネットワーキング学習コーナー</h2>
      <div class="networking-education-subtitle">Networking Education Corner</div>

      <div class="networking-education-content">
        <div class="networking-education-presenter-box">
          <div class="networking-education-presenter-info">
            <div class="networking-education-presenter-name">${escapeHtml(presenter.presenter_name)}</div>
            ${presenter.presenter_company ? `<div class="networking-education-presenter-role">${escapeHtml(presenter.presenter_company)}</div>` : ""}
            ${presenter.presenter_category ? `<div class="networking-education-presenter-category">(${escapeHtml(presenter.presenter_category)})</div>` : ""}
          </div>
        </div>

        <div class="networking-education-topic">
          <i class="fas fa-book-open topic-icon"></i>
          <div class="topic-text">今週の担当者</div>
        </div>
      </div>
    </section>
  `;

  // PDF画像がある場合、各ページを個別スライドとして追加
  if (presenter.pdf_image_paths_array && presenter.pdf_image_paths_array.length > 0) {
    presenter.pdf_image_paths_array.forEach((imagePath, index) => {
      slides += `
        <section class="networking-learning-image-slide" data-background-image="../${escapeHtml(imagePath)}" data-background-size="contain" data-background-color="#ffffff">
          <div style="position: absolute; top: 20px; left: 20px; background: rgba(207, 32, 48, 0.95); color: white; padding: 12px 20px; border-radius: 8px; font-size: 18px; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
            ネットワーキング学習 ${index + 1}/${presenter.pdf_image_paths_array.length}
          </div>
        </section>
      `;
    });
  }

  return slides;
}

