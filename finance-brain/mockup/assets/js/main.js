// ========================================
//  Tab Switching
// ========================================
document.addEventListener('DOMContentLoaded', function() {
  // Services tabs
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach(button => {
    button.addEventListener('click', () => {
      const tabName = button.getAttribute('data-tab');

      // Remove active class from all buttons and contents
      tabButtons.forEach(btn => btn.classList.remove('active'));
      tabContents.forEach(content => content.classList.remove('active'));

      // Add active class to clicked button and corresponding content
      button.classList.add('active');
      document.getElementById(tabName).classList.add('active');
    });
  });

  // ========================================
  //  Hamburger Menu
  // ========================================
  const hamburger = document.getElementById('hamburger');
  const headerNav = document.querySelector('.header-nav');

  if (hamburger) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      headerNav.classList.toggle('active');
    });
  }

  // ========================================
  //  Smooth Scroll
  // ========================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href === '#') return;

      e.preventDefault();
      const target = document.querySelector(href);
      if (target) {
        const headerHeight = document.querySelector('.header').offsetHeight;
        const targetPosition = target.offsetTop - headerHeight - 20;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });

        // Close mobile menu if open
        if (headerNav.classList.contains('active')) {
          hamburger.classList.remove('active');
          headerNav.classList.remove('active');
        }
      }
    });
  });

  // ========================================
  //  Contact Form
  // ========================================
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Get form data
      const formData = new FormData(contactForm);
      const data = {};
      formData.forEach((value, key) => {
        data[key] = value;
      });

      // Simple validation
      let isValid = true;
      const requiredFields = ['inquiry-type', 'name', 'name-kana', 'email', 'tel', 'message'];

      requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
          isValid = false;
          input.style.borderColor = 'var(--color-error)';
        } else {
          input.style.borderColor = 'var(--color-border)';
        }
      });

      if (!isValid) {
        alert('必須項目を全て入力してください。');
        return;
      }

      // Check privacy checkbox
      const privacyCheckbox = contactForm.querySelector('input[name="privacy"]');
      if (!privacyCheckbox.checked) {
        alert('個人情報保護方針に同意してください。');
        return;
      }

      // In production, send data to server
      console.log('Form data:', data);
      alert('お問い合わせを送信しました。\n\n※これはモックアップです。実際には送信されません。');

      // Reset form
      contactForm.reset();
    });
  }

  // ========================================
  //  Scroll Animation (Fade In)
  // ========================================
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);

  // Observe elements with fade-in class
  document.querySelectorAll('.service-card, .reason-card, .voice-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
});
