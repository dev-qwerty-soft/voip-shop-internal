// Quantity buttons functionality (for product pages, NOT for cart)
document.addEventListener('DOMContentLoaded', function () {
  // Exclude cart quantity buttons - they have their own handler
  const quantityBtns = document.querySelectorAll('.quantity-btn:not(.custom-cart__item-quantity .quantity-btn)');
  const priceElement = document.querySelector('.summary .price .woocommerce-Price-amount');
  let basePrice = null;

  if (priceElement) {
    const priceText = priceElement.textContent.replace(/[^0-9.]/g, '');
    basePrice = parseFloat(priceText);
  }

  function updatePrice(quantity) {
    if (!priceElement || !basePrice) return;

    const newTotal = (basePrice * quantity).toFixed(2);
    const currencySymbol = priceElement.querySelector('.woocommerce-Price-currencySymbol');
    const symbol = currencySymbol ? currencySymbol.textContent : '$';

    priceElement.innerHTML = `<bdi><span class="woocommerce-Price-currencySymbol">${symbol}</span>${newTotal}</bdi>`;
  }

  quantityBtns.forEach((btn) => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();

      // Skip if this is a cart item
      if (this.closest('.custom-cart__item-quantity')) return;

      const quantityWrapper = this.closest('.quantity');
      const input = quantityWrapper.querySelector('.qty');

      if (!input) return;

      const currentValue = parseInt(input.value) || 1;
      const min = parseInt(input.getAttribute('min')) || 1;
      const maxAttr = input.getAttribute('max');
      const max = maxAttr && maxAttr !== '' ? parseInt(maxAttr) : 999999;
      const step = parseInt(input.getAttribute('step')) || 1;

      let newValue = currentValue;

      if (this.classList.contains('quantity-plus')) {
        newValue = currentValue + step;
        if (max !== 999999 && newValue > max) newValue = max;
      } else if (this.classList.contains('quantity-minus')) {
        newValue = currentValue - step;
        if (newValue < min) newValue = min;
      }

      input.value = newValue;
      updatePrice(newValue);

      const event = new Event('change', { bubbles: true });
      input.dispatchEvent(event);
    });
  });

  document.querySelectorAll('.qty').forEach((input) => {
    input.addEventListener('change', function () {
      const min = parseInt(this.getAttribute('min')) || 1;
      const max = parseInt(this.getAttribute('max')) || 999999;
      let value = parseInt(this.value) || min;

      if (value < min) value = min;
      if (value > max) value = max;

      this.value = value;
      updatePrice(value);
    });
  });
});
