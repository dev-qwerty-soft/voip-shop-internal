(function () {
  'use strict';

  function updateMainPrice() {
    if (typeof jQuery === 'undefined') return;

    const $container = jQuery('.yith-wapo-container');
    if (!$container.length) return;

    const basePrice = parseFloat($container.data('product-price')) || 0;
    const $selects = jQuery('select.yith-wapo-option-value');

    if (!$selects.length) return;

    let totalAddonPrice = 0;

    $selects.each(function () {
      const $selectedOption = jQuery(this).find('option:selected');
      const priceModifier = parseFloat($selectedOption.data('price')) || 0;
      const priceType = $selectedOption.data('price-type') || 'fixed';
      const priceMethod = $selectedOption.data('price-method') || 'increase';

      if (priceMethod === 'increase' && priceModifier > 0) {
        if (priceType === 'percentage') {
          totalAddonPrice += (basePrice * priceModifier) / 100;
        } else {
          totalAddonPrice += priceModifier;
        }
      }
    });

    const newPrice = basePrice + totalAddonPrice;

    const $mainPrice = jQuery('.summary .price .woocommerce-Price-amount');
    if ($mainPrice.length && newPrice > 0) {
      $mainPrice.html('<bdi><span class="woocommerce-Price-currencySymbol">$</span>' + newPrice.toFixed(2) + '</bdi>');
    }
  }

  function initYithWapo() {
    if (typeof jQuery === 'undefined') return;

    const $wapoContainer = jQuery('.yith-wapo-container');
    if (!$wapoContainer.length) return;

    jQuery(document).on('change', 'select.yith-wapo-option-value', function () {
      updateMainPrice();
    });

    jQuery(document).on('yith_wapo_option_change', function () {
      updateMainPrice();
    });

    jQuery(document).on('yith_wapo_after_calculate', function () {
      updateMainPrice();
    });

    setTimeout(() => {
      updateMainPrice();
    }, 500);

    //  Delete inline width from yith
    const WAPO_WIDTH_SELECTOR = '.yith-wapo-block .options, .yith-wapo-block select';

    const removeInlineWidth = () => {
      jQuery(WAPO_WIDTH_SELECTOR).each(function () {
        if (this.style && this.style.getPropertyValue('width')) {
          this.style.removeProperty('width');
        }
      });
    };

    removeInlineWidth();

    if (typeof MutationObserver !== 'undefined') {
      const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
          if (mutation.addedNodes && mutation.addedNodes.length) {
            removeInlineWidth();
            break;
          }
        }
      });

      observer.observe(document.body, {
        childList: true,
        subtree: true,
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initYithWapo);
  } else {
    initYithWapo();
  }

  if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(initYithWapo);
  } else {
    const checkJQuery = setInterval(() => {
      if (typeof jQuery !== 'undefined') {
        clearInterval(checkJQuery);
        initYithWapo();
      }
    }, 100);

    setTimeout(() => clearInterval(checkJQuery), 5000);
  }
})();
