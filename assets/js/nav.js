// ナビゲーション制御
(function() {
  const hamburger = document.getElementById('hamburger');
  const navList = document.getElementById('navList');
  const header = document.getElementById('header');

  let scrollTimer = null;
  let lastScrollY = window.scrollY;
  let isScrolling = false;
  let scrollPosition = 0;

  // メニューを閉じる関数
  function closeMenu() {
    hamburger.classList.remove('hamburger--active');
    navList.classList.remove('nav__list--active');
    document.body.style.overflow = '';
  }

  // メニューを開く関数
  function openMenu() {
    hamburger.classList.add('hamburger--active');
    navList.classList.add('nav__list--active');
    document.body.style.overflow = 'hidden';
  }

  // ハンバーガーメニュートグル
  if (hamburger && navList) {
    hamburger.addEventListener('click', function(e) {
      e.stopPropagation();
      if (navList.classList.contains('nav__list--active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    // メニュー外をクリックしたら閉じる
    document.addEventListener('click', function(e) {
      if (navList.classList.contains('nav__list--active')) {
        if (!navList.contains(e.target) && !hamburger.contains(e.target)) {
          closeMenu();
        }
      }
    });

    // モバイルメニュー内のリンクをクリックしたら閉じる
    const navLinks = navList.querySelectorAll('.nav__link');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
          closeMenu();
        }
      });
    });

    // ウィンドウリサイズ時の処理
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768 && navList.classList.contains('nav__list--active')) {
        closeMenu();
      }
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
