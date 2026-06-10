// Auto-hide WooCommerce notices after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
  const notices = document.querySelectorAll('.woocommerce-message, .woocommerce-info, .woocommerce-error');

  notices.forEach((notice) => {
    setTimeout(() => {
      notice.style.animation = 'fadeOut 0.3s ease-out forwards';
      setTimeout(() => {
        notice.remove();
      }, 300);
    }, 5000);
  });

  // Also handle notices added dynamically (e.g., after AJAX add to cart)
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (node.nodeType === 1) {
          const newNotices = node.querySelectorAll ? node.querySelectorAll('.woocommerce-message, .woocommerce-info, .woocommerce-error') : [];

          if (node.classList && (node.classList.contains('woocommerce-message') || node.classList.contains('woocommerce-info') || node.classList.contains('woocommerce-error'))) {
            setTimeout(() => {
              node.style.animation = 'fadeOut 0.3s ease-out forwards';
              setTimeout(() => {
                node.remove();
              }, 300);
            }, 5000);
          }

          newNotices.forEach((notice) => {
            setTimeout(() => {
              notice.style.animation = 'fadeOut 0.3s ease-out forwards';
              setTimeout(() => {
                notice.remove();
              }, 300);
            }, 5000);
          });
        }
      });
    });
  });

  const noticesWrapper = document.querySelector('.woocommerce-notices-wrapper');
  if (noticesWrapper) {
    observer.observe(noticesWrapper, { childList: true, subtree: true });
  }
});
