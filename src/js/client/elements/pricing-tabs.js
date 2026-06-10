document.addEventListener('DOMContentLoaded', function () {
  // Declared here so initTabsSlider (and moveSlider inside it) can access them
  const billingToggleCheckbox = document.getElementById('billingSwitch');
  const plansContentBlock = document.querySelector('.pricing-plans__content');

  function initTabsSlider(containerSelector, contentSelector, priceWrapperSelector = null) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const tabs = container.querySelectorAll('span');
    const slider = container.querySelector('.slider');
    const contents = document.querySelectorAll(contentSelector);

    const priceWrappers = priceWrapperSelector ? document.querySelectorAll(priceWrapperSelector) : null;

    const settingsBlock = document.querySelector('.pricing-plans__settings');

    function moveSlider(tab) {
      const rect = tab.getBoundingClientRect();
      const parentRect = container.getBoundingClientRect();
      const left = rect.left - parentRect.left;
      const width = rect.width;

      slider.style.width = width + 'px';
      slider.style.transform = `translateX(${left}px)`;

      tabs.forEach((t) => t.classList.remove('active'));
      tab.classList.add('active');

      const index = [...tabs].indexOf(tab);

      contents.forEach((c) => c.classList.remove('active'));
      if (contents[index]) {
        contents[index].classList.add('active');
      }

      if (priceWrappers) {
        priceWrappers.forEach((wrapper) => {
          const priceBlocks = wrapper.querySelectorAll('.plan__price');
          priceBlocks.forEach((pb) => pb.classList.remove('active'));
          if (priceBlocks[index]) priceBlocks[index].classList.add('active');
        });
      }

      if (settingsBlock) {
        settingsBlock.classList.toggle(
          'users-hidden',
          tab.dataset.userDisable !== undefined
        );

        const isSaveDisabled = tab.dataset.saveDisable !== undefined;

        settingsBlock.classList.toggle('save-hidden', isSaveDisabled);

        // If save is disabled for this tab — reset switch to off and force base price display
        if (isSaveDisabled) {
          if (billingToggleCheckbox) billingToggleCheckbox.checked = false;
          if (plansContentBlock) plansContentBlock.classList.add('pricing-plans__content--save');
        }
      }
    }

    moveSlider(container.querySelector('span.active') || tabs[0]);

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => moveSlider(tab));
    });

    window.addEventListener('resize', () => {
      const activeTab = container.querySelector('span.active');
      if (activeTab) moveSlider(activeTab);
    });
  }

  initTabsSlider('.pricing-plans__tabs', '.pricing-plans__list');

  initTabsSlider('.pricing-plans__users-tabs', '.pricing-users__list', '.plan__price-wrapper');

  initTabsSlider('.faq__tabs', '.faq__wrap');

  if (billingToggleCheckbox && plansContentBlock) {
    function toggleSaveClassForPlans() {
      if (billingToggleCheckbox.checked) {
        plansContentBlock.classList.remove('pricing-plans__content--save');
      } else {
        plansContentBlock.classList.add('pricing-plans__content--save');
      }
    }

    billingToggleCheckbox.addEventListener('change', toggleSaveClassForPlans);

    toggleSaveClassForPlans();
  }
});