/**
 * BNI Slide System - Form Validation & Submission
 */

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('surveyForm');
  const messageDiv = document.getElementById('message');

  // Auto-set today's date
  const dateInput = document.querySelector('input[name="introduction_date"]');
  if (dateInput && !dateInput.value) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
  }

  // Form submission handler
  form.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Validate form
    if (!validateForm()) {
      return;
    }

    // Get form data
    const formData = new FormData(form);

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;

    try {
      // Submit form
      const response = await fetch('api_save.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        showMessage('success', result.message || 'アンケートを送信しました！');
        form.reset();
        // Reset date to today
        if (dateInput) {
          dateInput.value = new Date().toISOString().split('T')[0];
        }
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else {
        showMessage('error', result.message || '送信に失敗しました。もう一度お試しください。');
      }
    } catch (error) {
      console.error('Form submission error:', error);
      showMessage('error', 'エラーが発生しました。もう一度お試しください。');
    } finally {
      // Remove loading state
      submitBtn.classList.remove('loading');
      submitBtn.disabled = false;
    }
  });

  // Real-time validation
  const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
  inputs.forEach(input => {
    input.addEventListener('blur', function() {
      validateField(this);
    });

    input.addEventListener('input', function() {
      if (this.parentElement.classList.contains('error')) {
        validateField(this);
      }
    });
  });

  /**
   * Validate entire form
   */
  function validateForm() {
    let isValid = true;
    const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');

    requiredFields.forEach(field => {
      if (!validateField(field)) {
        isValid = false;
      }
    });

    // Check radio buttons
    const radioGroups = form.querySelectorAll('input[type="radio"][required]');
    const checkedGroups = new Set();

    radioGroups.forEach(radio => {
      if (radio.checked) {
        checkedGroups.add(radio.name);
      }
    });

    radioGroups.forEach(radio => {
      const formGroup = radio.closest('.form-group');
      if (!checkedGroups.has(radio.name)) {
        formGroup.classList.add('error');
        isValid = false;
      } else {
        formGroup.classList.remove('error');
      }
    });

    return isValid;
  }

  /**
   * Validate single field
   */
  function validateField(field) {
    const formGroup = field.closest('.form-group');
    const value = field.value.trim();

    // Required check
    if (field.hasAttribute('required') && !value) {
      formGroup.classList.add('error');
      formGroup.classList.remove('success');
      return false;
    }

    // Email validation
    if (field.type === 'email' && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        formGroup.classList.add('error');
        formGroup.classList.remove('success');
        return false;
      }
    }

    // Number validation
    if (field.type === 'number' && value) {
      const min = field.getAttribute('min');
      const max = field.getAttribute('max');
      const numValue = parseFloat(value);

      if (min !== null && numValue < parseFloat(min)) {
        formGroup.classList.add('error');
        formGroup.classList.remove('success');
        return false;
      }

      if (max !== null && numValue > parseFloat(max)) {
        formGroup.classList.add('error');
        formGroup.classList.remove('success');
        return false;
      }
    }

    // Valid
    formGroup.classList.remove('error');
    if (value) {
      formGroup.classList.add('success');
    }
    return true;
  }

  /**
   * Show message
   */
  function showMessage(type, text) {
    messageDiv.className = `message message-${type} show`;
    messageDiv.textContent = text;

    // Auto-hide after 5 seconds
    setTimeout(() => {
      messageDiv.classList.remove('show');
    }, 5000);
  }

  /**
   * Number input formatting
   */
  const amountInput = document.querySelector('input[name="referral_amount"]');
  if (amountInput) {
    amountInput.addEventListener('blur', function() {
      if (this.value) {
        // Format as currency (add commas)
        const formatted = parseInt(this.value).toLocaleString('ja-JP');
        // Store original value
        this.dataset.originalValue = this.value;
        // Display formatted value (for visual only, actual value remains numeric)
      }
    });
  }
});
