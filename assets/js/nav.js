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

      // body のスクロールを制御（スクロール位置を維持）
      if (navList.classList.contains('nav__list--active')) {
        const scrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = '100%';
        document.body.style.overflow = 'hidden';
      } else {
        const scrollY = document.body.style.top;
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        window.scrollTo(0, parseInt(scrollY || '0') * -1);
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
          const scrollY = document.body.style.top;
          document.body.style.position = '';
          document.body.style.top = '';
          document.body.style.width = '';
          document.body.style.overflow = '';
          window.scrollTo(0, parseInt(scrollY || '0') * -1);
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
