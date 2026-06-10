document.addEventListener('DOMContentLoaded', function () {
  let accordionItems = document.querySelectorAll('.accordion-item');
  if (accordionItems) {
    accordionItems.forEach((el) => {
      let accHead = el.querySelector('.accordion-head');
      accHead.addEventListener('click', () => {
        el.classList.toggle('active');
      });
    });
  }
});
