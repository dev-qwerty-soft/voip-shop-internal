export function initProductTabs() {
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabPanels = document.querySelectorAll('.tab-panel');

  if (!tabButtons.length || !tabPanels.length) return;

  tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const targetTab = button.getAttribute('data-tab');

      tabButtons.forEach((btn) => btn.classList.remove('active'));
      tabPanels.forEach((panel) => panel.classList.remove('active'));

      button.classList.add('active');
      const targetPanel = document.getElementById(targetTab);
      if (targetPanel) {
        targetPanel.classList.add('active');
      }
    });
  });

  function initReadMore() {
    const contentWrappers = document.querySelectorAll('.product-description, .product-key-features, .product-details');

    contentWrappers.forEach((wrapper) => {
      const content = wrapper.innerHTML;
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = content;
      const textContent = tempDiv.textContent || tempDiv.innerText;

      if (textContent.length > 400) {
        wrapper.classList.add('has-read-more');
        wrapper.classList.add('collapsed');

        const readMoreBtn = document.createElement('button');
        readMoreBtn.className = 'read-more-btn';
        readMoreBtn.textContent = 'Read more';

        wrapper.parentElement.appendChild(readMoreBtn);

        readMoreBtn.addEventListener('click', () => {
          wrapper.classList.toggle('collapsed');
          readMoreBtn.textContent = wrapper.classList.contains('collapsed') ? 'Read more' : 'Read less';
        });
      }
    });
  }

  initReadMore();
}
