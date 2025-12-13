/**
 * BNI Slide System - Slide Generation & Display
 */

(async function() {
  const loadingScreen = document.getElementById('loadingScreen');
  const slideContainer = document.getElementById('slideContainer');
  const weekSelector = document.getElementById('weekSelector');
  const slidePattern = document.getElementById('slidePattern');
  const controlButton = document.getElementById('controlButton');
  const controlPanel = document.getElementById('controlPanel');
  const closeControlPanel = document.getElementById('closeControlPanel');

  // Determine API base path based on current location
  const isInAdminDir = window.location.pathname.includes('/admin/');
  const apiBasePath = isInAdminDir ? '../' : '';

  // Load available weeks
  await loadWeeksList();

  // Load initial data
  await loadSlideData();

  // Control panel handlers
  controlButton.addEventListener('click', function() {
    controlPanel.classList.remove('hidden');
  });

  closeControlPanel.addEventListener('click', function() {
    controlPanel.classList.add('hidden');
  });

  // Close modal on background click
  controlPanel.addEventListener('click', function(e) {
    if (e.target === controlPanel) {
      controlPanel.classList.add('hidden');
    }
  });

  // Close modal on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !controlPanel.classList.contains('hidden')) {
      controlPanel.classList.add('hidden');
    }
  });

  // PDF Export button handler
  const exportPdfBtn = document.getElementById('exportPdfBtn');
  if (exportPdfBtn) {
    exportPdfBtn.addEventListener('click', function() {
      // Reveal.jsのPDF出力機能を使用
      // ?print-pdfパラメータを追加してページを再読み込み
      const url = new URL(window.location.href);
      url.searchParams.set('print-pdf', '');
      window.open(url.toString(), '_blank');
    });
  }

  // Week selector change handler
  weekSelector.addEventListener('change', async function() {
    controlPanel.classList.add('hidden');
    loadingScreen.classList.remove('hidden');
    await loadSlideData(this.value);
  });

  // Slide pattern change handler
  if (slidePattern) {
    slidePattern.addEventListener('change', async function() {
      controlPanel.classList.add('hidden');
      loadingScreen.classList.remove('hidden');
      await loadSlideData(weekSelector.value);
    });
  }

  /**
   * Load list of available weeks
   */
  async function loadWeeksList() {
    try {
      const response = await fetch(apiBasePath + 'api_list_weeks.php');
      const result = await response.json();

      if (result.success && result.weeks.length > 0) {
        weekSelector.innerHTML = '';

        // Get current week's Friday from API response (BNI週の定義に基づく)
        const currentWeekFriday = result.current_week_friday || '';
        const currentWeekTimestamp = currentWeekFriday
          ? new Date(currentWeekFriday + 'T00:00:00').getTime() / 1000
          : 0;

        // Filter weeks to show up to current week's Friday (今週の金曜日まで表示)
        const availableWeeks = result.weeks.filter(week => {
          // Check if it's Friday (金曜日)
          if (!week.label.includes('（金）')) return false;

          // Show weeks up to and including current week's Friday
          return week.timestamp <= currentWeekTimestamp;
        });

        if (availableWeeks.length === 0) {
          weekSelector.innerHTML = '<option value="">まだ開催されていません</option>';
          return;
        }

        availableWeeks.forEach((week, index) => {
          const option = document.createElement('option');
          option.value = week.filename;
          option.textContent = week.label + (index === 0 ? ' (最新)' : '');
          weekSelector.appendChild(option);
        });
      } else {
        weekSelector.innerHTML = '<option value="">データがありません</option>';
      }
    } catch (error) {
      console.error('Failed to load weeks list:', error);
    }
  }

  /**
   * Load slide data for selected week
   */
  async function loadSlideData(week = '') {
    try {
      // Fetch data from API
      const url = week ? `${apiBasePath}api_load.php?week=${week}` : `${apiBasePath}api_load.php`;
      const response = await fetch(url);
      const result = await response.json();

    if (!result.success) {
      throw new Error(result.message || 'データの読み込みに失敗しました');
    }

      const { data, stats, date, pitch_presenter, share_story_presenter, education_presenter, referral_total, slide_config, monthly_ranking_data } = result;

      // Check if monthly ranking pattern is selected
      let monthlyRankingData = null;
      const pattern = slidePattern ? slidePattern.value : 'normal';

      if (pattern === 'monthly_ranking') {
        // Load monthly ranking data (previous month)
        const today = new Date();
        const previousMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const yearMonth = previousMonth.toISOString().slice(0, 7); // YYYY-MM

        try {
          const rankingResponse = await fetch(`${apiBasePath}api_load_monthly_ranking.php?year_month=${yearMonth}`);
          const rankingResult = await rankingResponse.json();
          if (rankingResult.success) {
            monthlyRankingData = rankingResult.data;
          }
        } catch (error) {
          console.warn('Failed to load monthly ranking data:', error);
        }
      } else if (monthly_ranking_data) {
        // For normal slides, use ranking data from api_load.php if display_in_slide = 1
        monthlyRankingData = monthly_ranking_data;
      }

      // Generate slides using SVG templates
      // Note: slide_config may be null if slide_config.json doesn't exist
      await generateSVGSlides(data, stats, date, pitch_presenter, share_story_presenter, education_presenter, referral_total, slide_config || null, monthlyRankingData);

      // Initialize or sync Reveal.js
      if (!Reveal.isReady()) {
        // Initialize Reveal.js
        Reveal.initialize({
      hash: true,
      controls: false,
      progress: true,
      center: false,
      transition: 'fade',
      transitionSpeed: 'default',
      backgroundTransition: 'fade',
      slideNumber: 'c/t',
      keyboard: true,
      overview: true,
      touch: true,
      loop: false,
      rtl: false,
      navigationMode: 'default',
      shuffle: false,
      fragments: true,
      fragmentInURL: true,
      embedded: false,
      help: true,
      pause: true,
      showNotes: false,
      autoPlayMedia: null,
      preloadIframes: null,
      autoAnimate: true,
      autoAnimateMatcher: null,
      autoAnimateEasing: 'ease',
      autoAnimateDuration: 1.0,
      autoAnimateUnmatched: true,
      autoAnimateStyles: [
        'opacity',
        'color',
        'background-color',
        'padding',
        'font-size',
        'line-height',
        'letter-spacing',
        'border-width',
        'border-color',
        'border-radius',
        'outline',
        'outline-offset'
      ],
      autoSlide: 0,
      autoSlideStoppable: true,
      autoSlideMethod: null,
      defaultTiming: null,
      mouseWheel: false,
      previewLinks: false,
      postMessage: true,
      postMessageEvents: false,
      focusBodyOnPageVisibilityChange: true,
      width: 1920,
      height: 1080,
      margin: 0.05,
      minScale: 0.2,
      maxScale: 2.0,
      disableLayout: false
        });
      } else {
        // Reveal.js already initialized, just sync
        Reveal.sync();
        Reveal.slide(0); // Go to first slide
      }

      // Hide loading screen
      loadingScreen.classList.add('hidden');

      // Setup countdown timers for pitch slides
      setupCountdownTimers();

      // Setup number animations for referral amounts
      setupNumberAnimations();

      // Setup logo display
      setupLogoDisplay();

      // Setup sidebar table of contents
      setupSidebarTOC();

    } catch (error) {
      console.error('Error loading slide data:', error);
      slideContainer.innerHTML = `
        <section>
          <div class="error-message">
            <h2>エラー</h2>
            <p>${error.message}</p>
            <p><a href="index.php">アンケートフォームに戻る</a></p>
          </div>
        </section>
      `;
      loadingScreen.classList.add('hidden');
    }
  }

  /**
   * Setup countdown timers for pitch slides
   */
  function setupCountdownTimers() {
    let countdownInterval = null;

    Reveal.on('slidechanged', event => {
      // Clear any existing countdown
      if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
      }

      // Check if current slide is a pitch slide
      const currentSlide = event.currentSlide;
      if (currentSlide && currentSlide.classList.contains('pitch-slide')) {
        const timerElement = currentSlide.querySelector('.countdown-timer');
        const progressBar = currentSlide.querySelector('.countdown-progress-bar');

        if (timerElement && progressBar) {
          let seconds = parseInt(timerElement.dataset.seconds) || 30;
          const totalSeconds = seconds;

          // Reset display
          timerElement.textContent = seconds;
          progressBar.style.width = '100%';

          // Start countdown
          countdownInterval = setInterval(() => {
            seconds--;
            timerElement.textContent = seconds;

            // Update progress bar
            const percentage = (seconds / totalSeconds) * 100;
            progressBar.style.width = percentage + '%';

            // Change color when time is running out
            if (seconds <= 10) {
              timerElement.style.color = '#FFD700';
            }
            if (seconds <= 5) {
              timerElement.style.color = '#FF6B6B';
            }

            // Stop at 0
            if (seconds <= 0) {
              clearInterval(countdownInterval);
              countdownInterval = null;
              timerElement.textContent = '0';
              progressBar.style.width = '0%';
            }
          }, 1000);
        }
      }
    });
  }

  /**
   * Setup number count-up animations for referral amounts
   */
  function setupNumberAnimations() {
    Reveal.on('slidechanged', event => {
      const currentSlide = event.currentSlide;

      // Animate numbers with .animate-number class
      const animateNumbers = currentSlide.querySelectorAll('.animate-number');
      animateNumbers.forEach(element => {
        const targetValue = parseInt(element.dataset.value) || 0;
        const duration = 1500; // 1.5 seconds
        const startTime = performance.now();

        function updateNumber(currentTime) {
          const elapsed = currentTime - startTime;
          const progress = Math.min(elapsed / duration, 1);

          // Easing function (ease-out-cubic)
          const easeProgress = 1 - Math.pow(1 - progress, 3);

          const currentValue = Math.floor(targetValue * easeProgress);
          element.textContent = '¥' + currentValue.toLocaleString('ja-JP');

          if (progress < 1) {
            requestAnimationFrame(updateNumber);
          } else {
            element.textContent = '¥' + targetValue.toLocaleString('ja-JP');
          }
        }

        requestAnimationFrame(updateNumber);
      });

      // Animate progress bars
      const progressFills = currentSlide.querySelectorAll('.progress-fill[data-width]');
      progressFills.forEach((element, index) => {
        const targetWidth = parseFloat(element.dataset.width) || 0;

        // Stagger animation by 100ms per item
        setTimeout(() => {
          element.style.transition = 'width 1.2s cubic-bezier(0.4, 0, 0.2, 1)';
          element.style.width = targetWidth + '%';
        }, index * 100);
      });
    });
  }

  /**
   * Setup logo display based on slide type
   * ロゴは最初と最後のスライドのみ表示
   */
  function setupLogoDisplay() {
    const logoTopRight = document.getElementById('logoTopRight');
    const logoBottomRight = document.getElementById('logoBottomRight');

    if (!logoTopRight || !logoBottomRight) return;

    function updateLogoDisplay() {
      const currentSlide = Reveal.getCurrentSlide();
      const indices = Reveal.getIndices();
      const totalSlides = Reveal.getTotalSlides();
      const isFirstSlide = indices.h === 0 && indices.v === 0;
      const isLastSlide = indices.h === totalSlides - 1;

      if (isFirstSlide && currentSlide && currentSlide.classList.contains('title-slide')) {
        // 最初のスライド（タイトル）: bottom-rightロゴ表示
        logoTopRight.classList.add('hidden');
        logoBottomRight.classList.remove('hidden');
      } else if (isLastSlide) {
        // 最後のスライド: bottom-rightロゴ表示
        logoTopRight.classList.add('hidden');
        logoBottomRight.classList.remove('hidden');
      } else {
        // その他のスライド: ロゴ非表示
        logoTopRight.classList.add('hidden');
        logoBottomRight.classList.add('hidden');
      }
    }

    // Update on slide change
    Reveal.on('slidechanged', updateLogoDisplay);

    // Initial display
    updateLogoDisplay();
  }
})();

/**
 * Generate slides from data
 */
function generateSlides(data, stats) {
  const slideContainer = document.getElementById('slideContainer');
  const today = new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let slides = '';

  // Slide 1: Title - BNI Brand
  slides += `
    <section class="title-slide">
      <h1>BNI週次レポート</h1>
      <h3>${today}</h3>
      <p style="margin-top: 60px; font-size: 0.7em; opacity: 0.9; font-weight: 500;">Givers Gain® | BNI Slide System</p>
    </section>
  `;

  // Slide 2: Overview
  slides += `
    <section>
      <h2>今週のサマリー</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">${stats.total_visitors}</div>
          <div class="stat-label">ビジター紹介数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">¥${formatNumber(stats.total_referral_amount)}</div>
          <div class="stat-label">総リファーラル金額</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${stats.total_attendance}</div>
          <div class="stat-label">出席者数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${stats.total_one_to_one}</div>
          <div class="stat-label">ワンツーワン実施数</div>
        </div>
      </div>
    </section>
  `;

  // Slide 3: Visitor Introductions
  if (data.length > 0) {
    slides += `
      <section>
        <h2>ビジター紹介</h2>
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

  // Slide 4: Referral Amount Breakdown
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

  // Slide 5: Member Contributions
  if (Object.keys(stats.members).length > 0) {
    slides += `
      <section>
        <h2>メンバー別貢献度</h2>
        <div class="member-list">
    `;

    Object.entries(stats.members).forEach(([member, memberStats]) => {
      // ビジター0名の場合は表示しない
      if (memberStats.visitors === 0) {
        return;
      }

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

  // Slide 6: Detailed Referral List
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

  // Slide 7: Activity Summary
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

  // Slide 8: Thank You
  slides += `
    <section class="title-slide">
      <h1>ありがとうございました</h1>
      <h3>来週もよろしくお願いします</h3>
      <p style="margin-top: 50px; font-size: 0.8em;">Givers Gain®</p>
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

/**
 * Setup Sidebar Table of Contents
 */
function setupSidebarTOC() {
  const sidebar = document.getElementById('sidebarToc');
  const tocContent = document.getElementById('tocContent');
  const toggleBtn = document.getElementById('toggleSidebar');
  const showSidebarBtn = document.getElementById('showSidebarBtn');

  if (!sidebar || !tocContent || !toggleBtn) return;

  // Toggle sidebar
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    // Show/hide the show button
    if (showSidebarBtn) {
      showSidebarBtn.style.display = sidebar.classList.contains('collapsed') ? 'flex' : 'none';
    }
  });

  // Show sidebar button click handler
  if (showSidebarBtn) {
    showSidebarBtn.addEventListener('click', () => {
      sidebar.classList.remove('collapsed');
      showSidebarBtn.style.display = 'none';
    });
  }

  // Generate TOC from slides
  function generateTOC() {
    const slides = document.querySelectorAll('.reveal .slides > section');
    tocContent.innerHTML = '';

    slides.forEach((slide, index) => {
      const title = slide.querySelector('h1, h2');
      const subtitle = slide.querySelector('h3, .subtitle');

      if (title) {
        const tocItem = document.createElement('div');
        tocItem.className = 'toc-item';
        tocItem.dataset.slideIndex = index;

        const titleSpan = document.createElement('span');
        titleSpan.className = 'toc-item-title';
        titleSpan.textContent = title.textContent.trim();
        tocItem.appendChild(titleSpan);

        if (subtitle) {
          const subtitleSpan = document.createElement('span');
          subtitleSpan.className = 'toc-item-subtitle';
          subtitleSpan.textContent = subtitle.textContent.trim();
          tocItem.appendChild(subtitleSpan);
        }

        // Click handler
        tocItem.addEventListener('click', () => {
          Reveal.slide(index);
        });

        tocContent.appendChild(tocItem);
      }
    });

    // Update active item on slide change
    updateActiveTOCItem();
  }

  // Update active TOC item
  function updateActiveTOCItem() {
    const currentSlideIndex = Reveal.getIndices().h;
    const tocItems = tocContent.querySelectorAll('.toc-item');

    tocItems.forEach((item, index) => {
      if (parseInt(item.dataset.slideIndex) === currentSlideIndex) {
        item.classList.add('active');
        // Scroll active item into view
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      } else {
        item.classList.remove('active');
      }
    });
  }

  // Listen to slide changes
  Reveal.on('slidechanged', updateActiveTOCItem);

  // Generate TOC after slides are loaded
  setTimeout(generateTOC, 500);
}
