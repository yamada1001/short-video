// ナビゲーション制御
(function() {
  const hamburger = document.getElementById('hamburger');
  const navList = document.getElementById('navList');
  const header = document.getElementById('header');

  if (hamburger && navList) {
    hamburger.addEventListener('click', function() {
      hamburger.classList.toggle('hamburger--active');
      navList.classList.toggle('nav__list--active');
    });
  }

  if (header) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        header.classList.add('header--scrolled');
      } else {
        header.classList.remove('header--scrolled');
      }
    });
  }
})();
