import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('DOMContentLoaded', () => {
  const heroFeatures = document.querySelectorAll('.hero__feature');
  heroFeatures.forEach((el, i) => {
    el.setAttribute('data-aos', 'fade-up');
    el.setAttribute('data-aos-delay', i * 200);
  });
  AOS.init({
    duration: 700,
    once: true,
    easing: 'ease-out-cubic',
  });
});
