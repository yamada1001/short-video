/**
 * Proposal LP - JavaScript
 * YOJITU.COM
 */

// ========================================
// Smooth Scroll
// ========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const headerOffset = 80;
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  });
});

// ========================================
// Header Scroll Effect
// ========================================
const header = document.querySelector('.header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
  const currentScroll = window.pageYOffset;

  if (currentScroll > 100) {
    header.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
  } else {
    header.style.boxShadow = 'none';
  }

  lastScroll = currentScroll;
});

// ========================================
// Form Validation & Submit
// ========================================
const contactForm = document.querySelector('.contact-form');

if (contactForm) {
  contactForm.addEventListener('submit', function(e) {
    e.preventDefault();

    // Simple validation
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();

    if (!name || !email) {
      alert('お名前とメールアドレスは必須項目です。');
      return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('正しいメールアドレスを入力してください。');
      return;
    }

    // In production, send to server
    // For now, just show success message
    alert('お問い合わせありがとうございます！\n担当者より折り返しご連絡いたします。');

    // Reset form
    contactForm.reset();
  });
}

// ========================================
// Scroll Reveal Animation (Optional)
// ========================================
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, observerOptions);

// Observe sections
document.querySelectorAll('.section').forEach(section => {
  section.style.opacity = '0';
  section.style.transform = 'translateY(20px)';
  section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(section);
});

// ========================================
// Plan Card Hover Effect Enhancement
// ========================================
const planCards = document.querySelectorAll('.plan-card');

planCards.forEach(card => {
  card.addEventListener('mouseenter', function() {
    this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
  });
});

console.log('Proposal LP - JavaScript loaded');
