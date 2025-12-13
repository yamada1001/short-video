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

      // PDF印刷用のウィンドウを開く
      const pdfWindow = window.open(url.toString(), '_blank');

      // ウィンドウが開いたら印刷ダイアログの説明を表示
      if (pdfWindow) {
        // 少し待ってから印刷ダイアログの案内を表示
        setTimeout(() => {
          alert(
            'PDF出力モードで開きました。\n\n' +
            '次の手順でPDFを保存してください：\n' +
            '1. Ctrl+P (Mac: Cmd+P) を押して印刷ダイアログを開く\n' +
            '2. 送信先を「PDFに保存」に変更\n' +
            '3. レイアウトを「横向き」に設定\n' +
            '4. 余白を「なし」に設定\n' +
            '5. 「保存」をクリック'
          );
        }, 500);
      }
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

      const { data, stats, date, pitch_presenter, referral_total, slide_config, monthly_ranking_data, visitor_introductions, networking_learning_presenter } = result;

      // ページタイトルをPDFファイル名に適したものに設定
      if (date) {
        const formattedDate = formatDateForFilename(date);
        document.title = `BNI_Slide_${formattedDate}`;
      }

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

      // Load VP Statistics data (Speaker Rotation & Presenter Detail)
      let rotationData = null;
      let presenterDetail = null;

      try {
        const rotationResponse = await fetch(`${apiBasePath}api_load_speaker_rotation.php?week_date=${date}`);
        const rotationResult = await rotationResponse.json();
        if (rotationResult.success) {
          rotationData = rotationResult.data;
        }
      } catch (error) {
        console.warn('Failed to load speaker rotation data:', error);
      }

      try {
        const presenterResponse = await fetch(`${apiBasePath}api_load_presenter_detail.php?week_date=${date}`);
        const presenterResult = await presenterResponse.json();
        if (presenterResult.success) {
          presenterDetail = presenterResult.data;
        }
      } catch (error) {
        console.warn('Failed to load presenter detail data:', error);
      }

      // Load Member Introduction data
      let memberData = null;
      try {
        const memberResponse = await fetch(`${apiBasePath}api_load_member_introductions.php`);
        const memberResult = await memberResponse.json();
        if (memberResult.success) {
          memberData = memberResult;
        }
      } catch (error) {
        console.warn('Failed to load member introduction data:', error);
      }

      // Generate slides using SVG templates
      // Note: slide_config may be null if slide_config.json doesn't exist
      await generateSVGSlides(data, stats, date, pitch_presenter, referral_total, slide_config || null, monthlyRankingData, visitor_introductions, networking_learning_presenter, rotationData, presenterDetail, memberData);

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
 * Setup Slide Timers
 * Automatically start timers when slides with data-timer attribute are displayed
 */
(function setupSlideTimers() {
  Reveal.on('slidechanged', event => {
    const currentSlide = event.currentSlide;

    // Check if slide has data-timer attribute
    if (currentSlide && currentSlide.dataset && currentSlide.dataset.timer) {
      const timerType = currentSlide.dataset.timer;
      if (window.slideTimer) {
        window.slideTimer.start(timerType);
      }
    } else {
      // Stop timer when moving to a slide without timer
      if (window.slideTimer) {
        window.slideTimer.stop();
      }
    }
  });
})();


/**
 * Format number with commas
 */
function formatNumber(num) {
  return parseInt(num).toLocaleString('ja-JP');
}

/**
 * Format date for PDF filename (YYYY-MM-DD)
 */
function formatDateForFilename(dateStr) {
  if (!dateStr) return '';

  // dateStrが "YYYY-MM-DD" の形式の場合はそのまま返す
  if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
    return dateStr;
  }

  // dateStrが日本語形式の場合は変換
  try {
    const date = new Date(dateStr);
    if (!isNaN(date.getTime())) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }
  } catch (error) {
    console.error('Failed to format date:', error);
  }

  return dateStr;
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
