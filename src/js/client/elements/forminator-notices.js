document.addEventListener('DOMContentLoaded', function () {
  let blockScroll = false;
  let noticeCooldown = false;
  const NOTICE_TIMEOUT = 5000;

  const originalScrollIntoView = Element.prototype.scrollIntoView;

  Element.prototype.scrollIntoView = function () {
    if (blockScroll) return;
    return originalScrollIntoView.apply(this, arguments);
  };

  const releaseScroll = () => {
    setTimeout(() => {
      blockScroll = false;
    }, 400);
  };

  if (typeof jQuery !== 'undefined') {
    jQuery(document).on('before:forminator:form:submit forminator:form:submit:success', function () {
      blockScroll = true;
      releaseScroll();
    });
  }

  const forms = document.querySelectorAll('form.forminator-custom-form');

  forms.forEach((form) => {
    const success = form.querySelector('.forminator-response-message');
    if (!success) return;

    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class' && success.classList.contains('forminator-success')) {
          if (noticeCooldown) return;

          noticeCooldown = true;

          const scrollX = window.scrollX;
          const scrollY = window.scrollY;

          success.style.display = 'none';
          window.scrollTo(scrollX, scrollY);

          let wrapper = document.querySelector('.forminator-notices-wrapper');

          if (!wrapper) {
            wrapper = document.createElement('div');
            wrapper.className = 'forminator-notices-wrapper';
            document.body.prepend(wrapper);
          }

          const notice = document.createElement('div');
          notice.className = 'woocommerce-message';
          notice.setAttribute('role', 'alert');
          notice.innerHTML = `
                        <span>
                            Thank you! Your message has been successfully sent.
                        </span>
                    `;

          wrapper.appendChild(notice);

          // auto hide + reset cooldown
          setTimeout(() => {
            notice.remove();
            noticeCooldown = false;
          }, NOTICE_TIMEOUT);

          requestAnimationFrame(() => {
            window.scrollTo(scrollX, scrollY);
          });
        }
      });
    });

    observer.observe(success, {
      attributes: true,
      attributeFilter: ['class'],
    });
  });

  // Stop jQuery scrolling
  if (typeof jQuery !== 'undefined') {
    jQuery(document).on('before:forminator:form:submit forminator:form:submit:success', function () {
      if (jQuery('html, body').is(':animated')) {
        jQuery('html, body').stop(true, false);
      }
    });
  }
});
