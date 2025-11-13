// フォームバリデーション
(function() {
  'use strict';

  const form = document.getElementById('contactForm');

  if (!form) return;

  // バリデーション関数
  const validators = {
    name: function(value) {
      return value.trim().length > 0;
    },
    email: function(value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(value);
    },
    'contact-method': function(value) {
      return value.trim().length > 0;
    },
    subject: function(value) {
      return value.trim().length > 0;
    },
    message: function(value) {
      return value.trim().length > 0;
    }
  };

  // エラー表示
  function showError(fieldId, show) {
    const errorElement = document.getElementById(fieldId + 'Error');
    const inputElement = document.getElementById(fieldId);

    if (errorElement && inputElement) {
      if (show) {
        errorElement.classList.add('form-error--show');
        inputElement.style.borderColor = '#e74c3c';
      } else {
        errorElement.classList.remove('form-error--show');
        inputElement.style.borderColor = '';
      }
    }
  }

  // リアルタイムバリデーション
  Object.keys(validators).forEach(function(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
      field.addEventListener('blur', function() {
        const isValid = validators[fieldId](this.value);
        showError(fieldId, !isValid);
      });

      field.addEventListener('input', function() {
        if (this.value.trim().length > 0) {
          const isValid = validators[fieldId](this.value);
          showError(fieldId, !isValid);
        }
      });
    }
  });

  // 送信時のバリデーション
  form.addEventListener('submit', function(e) {
    let isValid = true;

    Object.keys(validators).forEach(function(fieldId) {
      const field = document.getElementById(fieldId);
      if (field) {
        const fieldValid = validators[fieldId](field.value);
        showError(fieldId, !fieldValid);
        if (!fieldValid) {
          isValid = false;
        }
      }
    });

    if (!isValid) {
      e.preventDefault();
      // 最初のエラーフィールドにスクロール
      const firstError = form.querySelector('.form-error--show');
      if (firstError) {
        const errorField = firstError.previousElementSibling;
        if (errorField) {
          errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
          errorField.focus();
        }
      }
    }
  });
})();
