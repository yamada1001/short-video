/**
 * BNI Slide System - Slide Generation & Display
 */

(async function() {
  const loadingScreen = document.getElementById('loadingScreen');
  const slideContainer = document.getElementById('slideContainer');
  const weekSelector = document.getElementById('weekSelector');
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

  // Week selector change handler
  weekSelector.addEventListener('change', async function() {
    controlPanel.classList.add('hidden');
    loadingScreen.classList.remove('hidden');
    await loadSlideData(this.value);
  });

  /**
   * Load list of available weeks
   */
  async function loadWeeksList() {
    try {
      const response = await fetch(apiBasePath + 'api_list_weeks.php');
      const result = await response.json();

      if (result.success && result.weeks.length > 0) {
        weekSelector.innerHTML = '';
        result.weeks.forEach((week, index) => {
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

      const { data, stats } = result;

      // Generate slides using SVG templates
      await generateSVGSlides(data, stats);

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
      width: 1200,
      height: 700,
      margin: 0.04,
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
