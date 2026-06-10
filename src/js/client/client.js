import '@client/animation/aos-amin.js';
import '@client/elements/swiper';
import '@client/elements/mobile-menu.js';
import '@client/elements/pricing-more.js';
import '@client/elements/chat-widget.js';
import '@client/elements/accordion.js';
import '@client/elements/pricing-tabs.js';
import '@client/page/shop-filters.js';
import '@client/elements/quantity-buttons.js';
import '@client/elements/cart-quantity.js';
import '@client/elements/yith-wapo-fix.js';
import '@client/elements/woocommerce-notices.js';
import '@client/elements/forminator-notices.js';
import '@client/elements/prevent-resubmit.js';
import '@client/elements/prevent-resubmit.js';
import '@client/elements/why-scroll.js';
import '@client/elements/modal/schedule-modal.js';
import { initPhoneSelect, SupportPopup, SupportFormSend } from '@client/elements/modal.js';
import { initProductTabs } from '@client/elements/product-tabs.js';
import '../../scss/style.scss';

document.addEventListener('DOMContentLoaded', () => {
  initProductTabs();
  initPhoneSelect();
  SupportPopup.init();
  SupportFormSend.init();
});
