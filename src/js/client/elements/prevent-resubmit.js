// Prevent form resubmission on page reload
document.addEventListener('DOMContentLoaded', () => {
  const addToCartForm = document.querySelector('.cart');

  if (addToCartForm) {
    // Check if page was reloaded
    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_RELOAD) {
      // Page was reloaded - prevent form submission
      const submitButton = addToCartForm.querySelector('[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
        setTimeout(() => {
          submitButton.disabled = false;
        }, 100);
      }
    }

    // Mark form as submitted
    addToCartForm.addEventListener('submit', function () {
      // Set flag that form was submitted
      sessionStorage.setItem('formSubmitted', 'true');
    });

    // Check if form was already submitted
    if (sessionStorage.getItem('formSubmitted') === 'true') {
      sessionStorage.removeItem('formSubmitted');

      // Clear any POST data by replacing state
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    }
  }
});

// Prevent back/forward cache from resubmitting
window.addEventListener('pageshow', function (event) {
  if (event.persisted) {
    // Page was loaded from cache
    const forms = document.querySelectorAll('.cart');
    forms.forEach((form) => {
      form.reset();
    });
  }
});
