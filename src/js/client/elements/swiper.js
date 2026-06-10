import Swiper from 'swiper';
import { g } from '@utils/function';
import { Navigation, Thumbs, Mousewheel } from 'swiper/modules';
import 'swiper/css';

document.addEventListener('DOMContentLoaded', function () {
  /* ====== SOLUTIONS SLIDER ====== */
  // let mobileSolutionsSwiper = null;
  // const solutionsSelector = ".solutions__slider";

  // function manageSolutionsSwiper() {
  //     const el = document.querySelector(solutionsSelector);
  //     if (!el) return;

  //     if (window.innerWidth < 768 && !mobileSolutionsSwiper) {
  //         mobileSolutionsSwiper = new Swiper(solutionsSelector, {
  //             slidesPerView: 1,
  //             spaceBetween: 16,
  //             loop: false,
  //         });
  //     } else if (window.innerWidth >= 768 && mobileSolutionsSwiper) {
  //         mobileSolutionsSwiper.destroy(true, true);
  //         mobileSolutionsSwiper = null;
  //     }
  // }

  /* ====== CAPABILITIES SLIDER ====== */
  let mobileCapabilitiesSwiper = null;
  const capabilitiesSelector = '.capabilities__slider';

  function manageCapabilitiesSwiper() {
    const el = document.querySelector(capabilitiesSelector);
    if (!el) return;

    if (window.innerWidth < 768 && !mobileCapabilitiesSwiper) {
      mobileCapabilitiesSwiper = new Swiper(capabilitiesSelector, {
        slidesPerView: 1,
        spaceBetween: 16,
        loop: false,
      });
    } else if (window.innerWidth >= 768 && mobileCapabilitiesSwiper) {
      mobileCapabilitiesSwiper.destroy(true, true);
      mobileCapabilitiesSwiper = null;
    }
  }

  /* INITIALIZE */
  // manageSolutionsSwiper();
  manageCapabilitiesSwiper();

  window.addEventListener('resize', function () {
    // manageSolutionsSwiper();
    manageCapabilitiesSwiper();
  });

  // Features Sliders
  const featuredSliders = document.querySelectorAll('.featured-products__slider');

  featuredSliders.forEach((slider, index) => {
    const container = slider.closest('.featured-products');
    const prevArrow = container.querySelector('.slider-arrows__prev');
    const nextArrow = container.querySelector('.slider-arrows__next');

    if (!slider || !prevArrow || !nextArrow) return;

    new Swiper(slider, {
      modules: [Navigation],
      slidesPerView: 5,
      spaceBetween: 20,
      navigation: {
        nextEl: nextArrow,
        prevEl: prevArrow,
      },
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 10 },
        768: { slidesPerView: 2, spaceBetween: 15 },
        1024: { slidesPerView: 3, spaceBetween: 20 },
        1200: { slidesPerView: 5, spaceBetween: 20 },
      },
    });
  });

  var swiperProductThumb = new Swiper('.custom-product__thumb', {
    spaceBetween: 20,
    slidesPerView: 5,
    freeMode: true,
    watchSlidesProgress: true,
    modules: [Thumbs],

    breakpoints: {
      320: { slidesPerView: 3, spaceBetween: 14 },
      768: { slidesPerView: 4 },
      1024: { slidesPerView: 5, spaceBetween: 20 },
    },
  });

  var swiperProductImages = new Swiper('.custom-product__swiper', {
    spaceBetween: 10,
    navigation: {
      nextEl: '.custom-product__arrow-next',
      prevEl: '.custom-product__arrow-prev',
    },
    thumbs: {
      swiper: swiperProductThumb,
    },
    modules: [Navigation, Thumbs],
  });


  var compareSlider = new Swiper('.compare__reviews-list', {
    spaceBetween: 30,
    loop: true,
    navigation: {
      nextEl: '.compare__reviews-arrow--next',
      prevEl: '.compare__reviews-arrow--prev',
    },
    modules: [Navigation],
  });
});


const reviewsSlider = document.querySelector('.reviews__slider');

if (reviewsSlider) {
  const nextRevButtons = document.querySelectorAll('.reviews__arrow--next');
  const prevRevButtons = document.querySelectorAll('.reviews__arrow--prev');

  const swiper = new Swiper(reviewsSlider, {
    modules: [Navigation, Mousewheel],

    direction: 'vertical',
    slidesPerView: 'auto',
    // centeredSlides: true,
    loop: true,
    // loopAdditionalSlides: 2,
    speed: 600,

    mousewheel: {
      forceToAxis: true,
    },

    breakpoints: {
      0: {
        direction: 'horizontal',
        mousewheel: false,
        centeredSlides: false,
        slidesPerView: 1,
      },
      768: {
        direction: 'vertical',
        mousewheel: true,
        centeredSlides: true,
        slidesPerView: 'auto',
      }
    }
  });

  nextRevButtons.forEach(btn => {
    btn.addEventListener('click', () => swiper.slideNext());
  });

  prevRevButtons.forEach(btn => {
    btn.addEventListener('click', () => swiper.slidePrev());
  });
}