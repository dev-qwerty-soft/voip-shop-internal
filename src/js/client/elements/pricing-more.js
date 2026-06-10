import AOS from 'aos';

document.addEventListener('DOMContentLoaded', function () {

  function initShowMore({ btnSelector, itemSelector, itemsContainer, skipFirst = false }) {
    const moreBtn = document.querySelector(btnSelector);
    if (!moreBtn) return;

    let items = [];

    if (itemsContainer) {
      const container = document.querySelector(itemsContainer);
      if (!container) return;
      items = Array.from(container.querySelectorAll(itemSelector));
    } else {
      let currentNode = moreBtn.nextElementSibling;
      while (currentNode) {
        if (currentNode.matches(itemSelector)) {
          items.push(currentNode);
        }
        currentNode = currentNode.nextElementSibling;
      }
    }

    if (skipFirst) items = items.slice(1);

    function setInitialState() {
      if (window.innerWidth < 768) {
        moreBtn.classList.remove('opened');
        items.forEach((item) => item.classList.add('hidden'));
      } else {
        moreBtn.classList.remove('opened');
        items.forEach((item) => item.classList.remove('hidden'));
        if (typeof AOS !== 'undefined') AOS.refresh();
      }
    }

    setInitialState();
    setTimeout(() => AOS.refresh(), 100);
    window.addEventListener('resize', setInitialState);

    moreBtn.addEventListener('click', () => {
      const opened = moreBtn.classList.toggle('opened');
      items.forEach((item) => {
        item.classList.toggle('hidden', !opened);
      });
      setTimeout(() => AOS.refresh(), 100);
    });
  }

  // initShowMore({
  //   btnSelector: '.pricing__more',
  //   itemSelector: '.pricing-card',
  // });


  document.querySelectorAll('.pricing-card__grid-more').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const card = this.closest('.pricing-card');
      const grid = card.querySelector('.pricing-card__grid');

      if (grid) {
        grid.classList.toggle('opened');
      }

      this.textContent = grid.classList.contains('opened') ? 'less' : 'more';
    });
  });


});