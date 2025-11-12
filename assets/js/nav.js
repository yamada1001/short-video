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

  // スクロール制御 - スクロール停止時にヘッダー表示
  if (header) {
    window.addEventListener('scroll', function() {
      const currentScrollY = window.scrollY;

      // スクロール中はヘッダーを隠す（トップ以外）
      if (currentScrollY > 100) {
        isScrolling = true;
        header.style.transform = 'translateY(-100%)';
        header.style.transition = 'transform 0.3s ease';
      } else {
        header.style.transform = 'translateY(0)';
      }

      // スクロール停止検知
      if (scrollTimer !== null) {
        clearTimeout(scrollTimer);
      }

      scrollTimer = setTimeout(function() {
        // スクロールが停止したらヘッダーを表示
        header.style.transform = 'translateY(0)';
        isScrolling = false;

        // スクロール時の影
        if (currentScrollY > 50) {
          header.classList.add('header--scrolled');
        } else {
          header.classList.remove('header--scrolled');
        }
      }, 150); // 150ms後にスクロール停止と判定

      lastScrollY = currentScrollY;
    });
  }
})();
