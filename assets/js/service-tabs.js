// サービスタブの切り替え機能
document.addEventListener('DOMContentLoaded', function() {
  const serviceTabs = document.querySelectorAll('.service-tab');

  if (serviceTabs.length === 0) return;

  serviceTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const targetSection = document.getElementById(targetId);

      if (!targetSection) return;

      // アクティブ状態の切り替え
      serviceTabs.forEach(t => t.classList.remove('service-tab--active'));
      this.classList.add('service-tab--active');

      // スムーズスクロール
      const headerOffset = 80;
      const elementPosition = targetSection.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    });
  });

  // ページ読み込み時にハッシュがある場合の処理
  const hash = window.location.hash.substring(1);
  if (hash) {
    const matchingTab = Array.from(serviceTabs).find(tab =>
      tab.getAttribute('data-target') === hash
    );

    if (matchingTab) {
      serviceTabs.forEach(t => t.classList.remove('service-tab--active'));
      matchingTab.classList.add('service-tab--active');
    }
  }
});
