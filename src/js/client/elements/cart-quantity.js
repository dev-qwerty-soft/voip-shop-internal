document.addEventListener('DOMContentLoaded', () => {
  const cartContainer = document.querySelector('.custom-cart__items');
  if (!cartContainer) return;
  let isProcessing = false;

  function updateCartAjax(cartItemKey, quantity) {
    const formData = new FormData();
    formData.append('action', 'update_cart_quantity');
    formData.append('cart_item_key', cartItemKey);
    formData.append('quantity', quantity);

    const totalsTable = document.querySelector('.custom-cart__totals-table');
    if (totalsTable) totalsTable.classList.add('custom-cart__totals-table--loading');

    fetch(window.ajaxurl || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (totalsTable) totalsTable.classList.remove('custom-cart__totals-table--loading');
        if (data.success) {
          if (quantity === 0) {
            document.querySelectorAll(`[data-cart-item-key="${cartItemKey}"]`).forEach((el) => el.remove());
            const remaining = document.querySelectorAll('.custom-cart__item');
            if (remaining.length === 0) {
              location.reload();
              return;
            }
          }

          // Update cart totals table
          if (data.data.cart_subtotal) {
            const subtotalEl = document.querySelector('.custom-cart__subtotal-value');
            if (subtotalEl) subtotalEl.innerHTML = data.data.cart_subtotal;
          }
          if (data.data.cart_total) {
            const totalEl = document.querySelector('.custom-cart__total-value');
            if (totalEl) totalEl.innerHTML = data.data.cart_total;
          }
          // Update header cart price
          if (data.data.cart_header_price !== undefined) {
            const headerPrice = document.querySelector('.header__cart-price');
            if (headerPrice) headerPrice.textContent = data.data.cart_header_price;
          }
          // Update shipping label and value
          if (data.data.shipping_label !== undefined) {
            const shippingLabel = document.querySelector('.custom-cart__shipping-label');
            if (shippingLabel) shippingLabel.textContent = data.data.shipping_label;
          }
          if (data.data.shipping_html !== undefined) {
            const shippingEl = document.querySelector('.custom-cart__shipping-value');
            if (shippingEl) shippingEl.innerHTML = data.data.shipping_html;
          }
          // Update item subtotal
          if (data.data.item_subtotal) {
            const itemPrices = document.querySelectorAll(`[data-cart-item-key="${cartItemKey}"] .custom-cart__item-price`);
            itemPrices.forEach((price) => (price.innerHTML = data.data.item_subtotal));
          }
        }
        isProcessing = false;
      })
      .catch((error) => {
        if (totalsTable) totalsTable.classList.remove('custom-cart__totals-table--loading');
        console.error('Cart update error:', error);
        isProcessing = false;
      });
  }

  cartContainer.addEventListener('click', function (e) {
    const button = e.target.closest('.quantity-btn');

    if (!button || isProcessing) return;

    e.preventDefault();
    e.stopPropagation();

    const cartItem = button.closest('.custom-cart__item');
    if (!cartItem || getComputedStyle(cartItem).display === 'none') return;

    const quantityDiv = button.closest('.quantity');
    const input = quantityDiv.querySelector('.qty');
    const currentValue = parseInt(input.value) || 0;
    const min = parseInt(input.getAttribute('min')) || 0;
    const maxAttr = input.getAttribute('max');
    const max = maxAttr && maxAttr !== '' ? parseInt(maxAttr) : 999999;

    let newValue = currentValue;

    if (button.classList.contains('quantity-minus')) {
      if (currentValue > min) {
        newValue = currentValue - 1;
      }
    } else if (button.classList.contains('quantity-plus')) {
      if (max === 999999 || currentValue < max) {
        newValue = currentValue + 1;
      }
    }

    if (newValue !== currentValue) {
      const nameAttr = input.getAttribute('name');
      const match = nameAttr.match(/cart\[([^\]]+)\]/);
      if (match) {
        const cartItemKey = match[1];

        isProcessing = true;

        // Update all inputs with same cart item key (desktop + mobile)
        const allInputs = document.querySelectorAll(`input[name="cart[${cartItemKey}][qty]"]`);
        allInputs.forEach((inp) => (inp.value = newValue));

        updateCartAjax(cartItemKey, newValue);
      }
    }
  });

  const quantityInputs = document.querySelectorAll('.custom-cart__item-quantity .qty');
  quantityInputs.forEach((input) => {
    let timeout;
    input.addEventListener('change', function () {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        const nameAttr = input.getAttribute('name');
        const match = nameAttr.match(/cart\[([^\]]+)\]/);
        if (match) {
          const cartItemKey = match[1];
          const quantity = parseInt(input.value) || 1;
          updateCartAjax(cartItemKey, quantity);
        }
      }, 500);
    });
  });
});
