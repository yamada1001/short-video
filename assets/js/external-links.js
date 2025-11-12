/**
 * 外部リンク自動制御
 * yojitu.com以外のリンクを新しいタブで開く
 */

document.addEventListener('DOMContentLoaded', function() {
  // すべてのリンクを取得
  const links = document.querySelectorAll('a[href]');

  links.forEach(link => {
    const href = link.getAttribute('href');

    // 相対パスまたはアンカーリンクの場合はスキップ
    if (!href || href.startsWith('#') || href.startsWith('/') || href.startsWith('./') || href.startsWith('../')) {
      return;
    }

    try {
      const url = new URL(href, window.location.href);
      const currentDomain = window.location.hostname;

      // yojitu.com以外の外部リンクの場合
      if (url.hostname !== currentDomain && url.hostname !== 'yojitu.com' && url.hostname !== 'www.yojitu.com') {
        // target="_blank"を追加
        link.setAttribute('target', '_blank');
        // セキュリティ対策でrel属性を追加
        link.setAttribute('rel', 'noopener noreferrer');

        // 外部リンクアイコンを追加（オプション）
        if (!link.querySelector('.external-icon')) {
          const icon = document.createElement('span');
          icon.className = 'external-icon';
          icon.innerHTML = ' <i class="fas fa-external-link-alt" style="font-size: 0.8em; margin-left: 4px; opacity: 0.6;"></i>';
          link.appendChild(icon);
        }
      }
    } catch (e) {
      // URLのパースに失敗した場合はスキップ
      console.warn('Invalid URL:', href);
    }
  });
});
