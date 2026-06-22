document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form.cart:not(.variations_form)');
  if (!form) return;

  const addToCartBtn = form.querySelector('.single_add_to_cart_button');
  if (!addToCartBtn) return;

  form.addEventListener('submit', function (e) {
    const productId =
      form.querySelector('[name="add-to-cart"]')?.value ||
      form.querySelector('[name="product_id"]')?.value;

    if (!productId) return;

    e.preventDefault();

    const quantity = form.querySelector('[name="quantity"]')?.value || 1;
    const originalText = addToCartBtn.textContent;

    addToCartBtn.disabled = true;
    addToCartBtn.classList.add('loading');

    const formData = new FormData();
    formData.append('action', 'custom_add_to_cart');
    formData.append('nonce', window.themeAjax?.nonce || '');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch(window.ajaxurl || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: formData,
    })
      .then((r) => r.json())
      .then((data) => {
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('loading');

        if (data.success) {
          updateHeaderCart(data.data.cart_count, data.data.cart_price);
          showNotice('success', data.data.message);
        } else {
          showNotice('error', data.data?.message || 'Could not add product to cart.');
        }
      })
      .catch(() => {
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('loading');
        showNotice('error', 'Something went wrong. Please try again.');
      });
  });

  function updateHeaderCart(count, price) {
    if (price !== undefined) {
      const cartPrice = document.querySelector('.header__cart-price');
      if (cartPrice) cartPrice.textContent = price;
    }

    if (count !== undefined) {
      const mobileCount = document.querySelector('.header__cart-mobile-count');
      if (mobileCount) {
        mobileCount.textContent = count;
        mobileCount.classList.toggle('header__cart-mobile-count--empty', count === 0);
      }
    }
  }

  function showNotice(type, message) {
    const wrapper = document.querySelector('.woocommerce-notices-wrapper');
    if (!wrapper) return;

    const cls = type === 'success' ? 'woocommerce-message' : 'woocommerce-error';
    const notice = document.createElement('div');
    notice.className = cls;
    notice.setAttribute('role', 'alert');
    notice.innerHTML = message;

    wrapper.innerHTML = '';
    wrapper.appendChild(notice);

    setTimeout(() => {
      notice.style.animation = 'fadeOut 0.3s ease-out forwards';
      setTimeout(() => notice.remove(), 300);
    }, 5000);
  }
});
