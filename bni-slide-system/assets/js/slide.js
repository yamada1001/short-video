/**
 * BNI Slide System - Enhanced Slide Generation & Display
 * with Chart.js, CountUp.js, Confetti, and unDraw illustrations
 */

(async function() {
  const loadingScreen = document.getElementById('loadingScreen');
  const slideContainer = document.getElementById('slideContainer');
  const weekSelector = document.getElementById('weekSelector');

  // Determine API base path based on current location
  const isInAdminDir = window.location.pathname.includes('/admin/');
  const apiBasePath = isInAdminDir ? '../' : '';

  // Load available weeks
  await loadWeeksList();

  // Load initial data
  await loadSlideData();

  // Week selector change handler
  weekSelector.addEventListener('change', async function() {
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

      // Generate slides
      await generateSlides(data, stats);

      // Initialize or sync Reveal.js
      if (!Reveal.isReady()) {
        // Initialize Reveal.js (Google Slides style - no animations)
        Reveal.initialize({
          hash: true,
          controls: true,
          progress: true,
          center: true,
          transition: 'none',  // No transition animation
          transitionSpeed: 'fast',
          backgroundTransition: 'none',
          slideNumber: 'c/t',
          keyboard: true,
          overview: true,
          touch: true,
          loop: false,
          rtl: false,
          navigationMode: 'default',
          shuffle: false,
          fragments: false,  // Disable fragment animations
          fragmentInURL: false,
          embedded: false,
          help: true,
          pause: true,
          showNotes: false,
          autoPlayMedia: null,
          preloadIframes: null,
          autoAnimate: false,  // Disable auto-animate
          autoSlide: 0,
          autoSlideStoppable: true,
          autoSlideMethod: null,
          defaultTiming: null,
          mouseWheel: false,
          previewLinks: false,
          postMessage: true,
          postMessageEvents: false,
          focusBodyOnPageVisibilityChange: true,
          width: '100%',  // Full width
          height: '100%',  // Full height
          margin: 0,  // No margin
          minScale: 1,  // No scaling
          maxScale: 1,
          disableLayout: false
        });

        // Add event listeners for slide changes
        Reveal.addEventListener('slidechanged', handleSlideChange);
      } else {
        // Reveal.js already initialized, just sync
        Reveal.sync();
        Reveal.slide(0); // Go to first slide
      }

      // Hide loading screen
      loadingScreen.classList.add('hidden');

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
   * Handle slide change events
   */
  function handleSlideChange(event) {
    const slideIndex = event.indexh;

    // Trigger confetti on title slide (first and last)
    if (slideIndex === 0 || event.currentSlide.classList.contains('title-slide')) {
      setTimeout(() => {
        confetti({
          particleCount: 100,
          spread: 70,
          origin: { y: 0.6 },
          colors: ['#CF2030', '#FF4858', '#FFFFFF']
        });
      }, 300);
    }

    // Trigger count-up animations
    triggerCountUpAnimations(event.currentSlide);
  }

  /**
   * Trigger count-up animations for stat cards
   */
  function triggerCountUpAnimations(slide) {
    const statValues = slide.querySelectorAll('.stat-value[data-count]');
    statValues.forEach(el => {
      if (el.dataset.counted) return; // Already animated

      const targetValue = parseInt(el.dataset.count);
      const countUp = new CountUp(el, targetValue, {
        duration: 2,
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.'
      });

      if (!countUp.error) {
        countUp.start();
        el.dataset.counted = 'true';
      }
    });
  }

})();

/**
 * Generate enhanced slides from data
 */
async function generateSlides(data, stats) {
  const slideContainer = document.getElementById('slideContainer');
  const today = new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let slides = '';

  // ============================================
  // Slide 1: Title Slide with Confetti
  // ============================================
  slides += `
    <section class="title-slide">
      <h1>BNI週次レポート</h1>
      <h3>${today}</h3>
      <p style="margin-top: 60px; font-size: 0.7em; opacity: 0.8;">Powered by BNI Slide System</p>
    </section>
  `;

  // ============================================
  // Slide 2: Overview with Count-Up Stats
  // ============================================
  slides += `
    <section>
      <h2>今週のサマリー</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_visitors}">0</div>
          <div class="stat-label">ビジター紹介数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_referral_amount}">0</div>
          <div class="stat-label">総リファーラル金額</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_attendance}">0</div>
          <div class="stat-label">出席者数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_one_to_one}">0</div>
          <div class="stat-label">ワンツーワン実施数</div>
        </div>
      </div>
      <img src="https://illustrations.popsy.co/amber/business-deal.svg"
           class="slide-illustration top-right" alt="">
    </section>
  `;

  // ============================================
  // Slide 3: Pie Chart - Category Breakdown
  // ============================================
  const categoryData = Object.entries(stats.categories);
  if (categoryData.length > 0) {
    const categoryLabels = categoryData.map(([cat]) => cat);
    const categoryValues = categoryData.map(([, val]) => val);
    const categoryColors = [
      '#CF2030', '#FF4858', '#3498DB', '#27AE60',
      '#F39C12', '#9B59B6', '#1ABC9C', '#E74C3C'
    ];

    slides += `
      <section>
        <h2>リファーラル内訳（カテゴリ別）</h2>
        <div class="chart-container">
          <canvas id="categoryChart"></canvas>
        </div>
        <img src="https://illustrations.popsy.co/amber/pie-chart-analysis.svg"
             class="slide-illustration bottom-left" alt="">
      </section>
    `;

    // Store chart data for later rendering
    setTimeout(() => {
      const ctx = document.getElementById('categoryChart');
      if (ctx) {
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: categoryLabels,
            datasets: [{
              data: categoryValues,
              backgroundColor: categoryColors.slice(0, categoryLabels.length),
              borderWidth: 2,
              borderColor: '#FFFFFF'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  font: { size: 14, family: 'Noto Sans JP' },
                  padding: 20
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = formatNumber(context.parsed);
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                    return `${label}: ¥${value} (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
      }
    }, 500);
  }

  // ============================================
  // Slide 4: Bar Chart - Member Contributions
  // ============================================
  const memberData = Object.entries(stats.members);
  if (memberData.length > 0) {
    const memberNames = memberData.map(([name]) => name);
    const memberAmounts = memberData.map(([, data]) => data.referral_amount);

    slides += `
      <section>
        <h2>メンバー別貢献度</h2>
        <div class="chart-container">
          <canvas id="memberChart"></canvas>
        </div>
        <img src="https://illustrations.popsy.co/amber/team-work.svg"
             class="slide-illustration top-right" alt="">
      </section>
    `;

    setTimeout(() => {
      const ctx = document.getElementById('memberChart');
      if (ctx) {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: memberNames,
            datasets: [{
              label: 'リファーラル金額',
              data: memberAmounts,
              backgroundColor: 'rgba(207, 32, 48, 0.8)',
              borderColor: '#CF2030',
              borderWidth: 2,
              borderRadius: 8
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    return '¥' + formatNumber(value);
                  },
                  font: { size: 12 }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
              },
              x: {
                ticks: { font: { size: 13, weight: 600 } },
                grid: { display: false }
              }
            },
            plugins: {
              legend: { display: false },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return '¥' + formatNumber(context.parsed.y);
                  }
                }
              }
            }
          }
        });
      }
    }, 500);
  }

  // ============================================
  // Slide 5: Visitor Introductions Table
  // ============================================
  if (data.length > 0) {
    const visitorsData = data.filter(row => row['ビジター名'] && row['ビジター名'].trim() !== '');

    if (visitorsData.length > 0) {
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

      visitorsData.forEach(row => {
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
          <img src="https://illustrations.popsy.co/amber/meeting.svg"
               class="slide-illustration bottom-left" alt="">
        </section>
      `;
    }
  }

  // ============================================
  // Slide 6: Referral Details Table
  // ============================================
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
          <td><span class="currency">¥${formatNumber(amount)}</span></td>
          <td><span class="emphasis">${escapeHtml(row['カテゴリ'] || '')}</span></td>
          <td>${escapeHtml(row['リファーラル提供者'] || '-')}</td>
        </tr>
      `;
    });

    slides += `
          </tbody>
        </table>
        <img src="https://illustrations.popsy.co/amber/financial-data.svg"
             class="slide-illustration top-right" alt="">
      </section>
    `;
  }

  // ============================================
  // Slide 7: Activity Summary with Stats
  // ============================================
  slides += `
    <section>
      <h2>アクティビティサマリー</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_thanks_slips}">0</div>
          <div class="stat-label">サンクスリップ</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_one_to_one}">0</div>
          <div class="stat-label">ワンツーワン</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" data-count="${stats.total_attendance}">0</div>
          <div class="stat-label">出席者数</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" data-count="${data.length}">0</div>
          <div class="stat-label">回答者数</div>
        </div>
      </div>
      <img src="https://illustrations.popsy.co/amber/growth-chart.svg"
           class="slide-illustration bottom-left" alt="">
    </section>
  `;

  // ============================================
  // Slide 8: Thank You with Confetti
  // ============================================
  slides += `
    <section class="title-slide">
      <h1>ありがとうございました</h1>
      <h3>来週もよろしくお願いします</h3>
      <p style="margin-top: 60px; font-size: 0.8em; opacity: 0.9;">Givers Gain®</p>
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
