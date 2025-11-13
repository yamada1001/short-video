// ナビゲーション制御
(function() {
  const hamburger = document.getElementById('hamburger');
  const navList = document.getElementById('navList');
  const header = document.getElementById('header');

  let scrollTimer = null;
  let lastScrollY = window.scrollY;
  let isScrolling = false;

  // ハンバーガーメニュートグル
  if (hamburger && navList) {
    hamburger.addEventListener('click', function() {
      hamburger.classList.toggle('hamburger--active');
      navList.classList.toggle('nav__list--active');
      navList.classList.toggle('nav__list--mobile');

      // body のスクロールを制御
      if (navList.classList.contains('nav__list--active')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });

    // モバイルメニュー内のリンクをクリックしたら閉じる
    const navLinks = navList.querySelectorAll('.nav__link');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
          hamburger.classList.remove('hamburger--active');
          navList.classList.remove('nav__list--active');
          navList.classList.remove('nav__list--mobile');
          document.body.style.overflow = '';
        }
      });
    });
  }

  // スクロール制御 - ヘッダーを常時表示（影のみ変更）
  if (header) {
    window.addEventListener('scroll', function() {
      const currentScrollY = window.scrollY;

      // ヘッダーは常に表示し、スクロール時に影を追加
      if (currentScrollY > 50) {
        header.classList.add('header--scrolled');
      } else {
        header.classList.remove('header--scrolled');
      }

      lastScrollY = currentScrollY;
    });
  }
})();
