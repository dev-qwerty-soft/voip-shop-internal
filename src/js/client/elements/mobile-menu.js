document.addEventListener('DOMContentLoaded', function () {
  let burgerBtn = document.querySelector('.burger'),
    mobileMenu = document.querySelector('.mobile-menu');

  let scrollPosition = 0;

  if (burgerBtn) {
    burgerBtn.addEventListener('click', () => {
      burgerBtn.classList.toggle('opened');
      mobileMenu.classList.toggle('opened');

      if (mobileMenu.classList.contains('opened')) {
        scrollPosition = window.scrollY || document.documentElement.scrollTop;

        // Block scroll
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollPosition}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
      } else {
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        window.scrollTo(0, scrollPosition);
      }
    });
  }
   
  const header = document.querySelector('.header');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
      header.classList.add('header--scroll');
    } else {
      header.classList.remove('header--scroll');
    }
  });
});
