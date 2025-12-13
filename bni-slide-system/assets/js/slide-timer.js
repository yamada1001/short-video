/**
 * Slide Timer Module
 * Automatically starts countdown timers when specific slides are displayed
 */

class SlideTimer {
  constructor() {
    this.timers = {
      weekly_presentation: 120,      // 2分 (120秒)
      visitor_intro: 23,               // 23秒
      business_breakout: 300           // 5分 (300秒)
    };

    this.currentTimer = null;
    this.intervalId = null;
    this.timerElement = null;

    this.init();
  }

  init() {
    // タイマー表示用のDOM要素を作成
    this.timerElement = document.createElement('div');
    this.timerElement.id = 'slide-timer';
    this.timerElement.className = 'slide-timer hidden';
    this.timerElement.innerHTML = `
      <div class="timer-content">
        <div class="timer-label">残り時間</div>
        <div class="timer-display">00:00</div>
      </div>
    `;
    document.body.appendChild(this.timerElement);
  }

  start(timerType) {
    // 既存のタイマーを停止
    this.stop();

    const duration = this.timers[timerType];
    if (!duration) {
      console.warn(`Unknown timer type: ${timerType}`);
      return;
    }

    this.currentTimer = duration;
    this.show();
    this.updateDisplay();

    // 1秒ごとにカウントダウン
    this.intervalId = setInterval(() => {
      this.currentTimer--;
      this.updateDisplay();

      if (this.currentTimer <= 0) {
        this.onTimerEnd();
      } else if (this.currentTimer <= 10) {
        // 残り10秒で警告表示
        this.timerElement.classList.add('warning');
      }
    }, 1000);
  }

  stop() {
    if (this.intervalId) {
      clearInterval(this.intervalId);
      this.intervalId = null;
    }
    this.hide();
    this.timerElement.classList.remove('warning', 'ended');
  }

  updateDisplay() {
    const minutes = Math.floor(this.currentTimer / 60);
    const seconds = this.currentTimer % 60;
    const display = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

    const displayElement = this.timerElement.querySelector('.timer-display');
    if (displayElement) {
      displayElement.textContent = display;
    }
  }

  onTimerEnd() {
    this.stop();
    this.timerElement.classList.add('ended');
    this.timerElement.querySelector('.timer-display').textContent = '終了';

    // 3秒後に非表示
    setTimeout(() => {
      this.hide();
      this.timerElement.classList.remove('ended');
    }, 3000);
  }

  show() {
    this.timerElement.classList.remove('hidden');
  }

  hide() {
    this.timerElement.classList.add('hidden');
  }
}

// グローバルインスタンスを作成
window.slideTimer = null;
document.addEventListener('DOMContentLoaded', () => {
  window.slideTimer = new SlideTimer();
});
