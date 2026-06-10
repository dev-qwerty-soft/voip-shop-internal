<?php get_header(); ?>
<main>
  <section class="error-section">
    <div class="container">
      <div class="error-section__number" data-aos="fade-up" data-aos-delay="200">404</div>
      <div class="error-section__wrap">
        <h1 class="error-section__title" data-aos="fade-up" data-aos-delay="400"><?php echo esc_html__('OOPS... Page not found', 'voip-theme'); ?></h1>
        <p class="error-section__message simple-text" data-aos="fade-up" data-aos-delay="500"><?php echo esc_html__('Lorem ipsum dolor sit amet consectetur. Sit tincidunt velit consectetur elit dapibus eu quam fermentum.', 'voip-theme'); ?></p>
        <a data-aos="fade-up" data-aos-delay="600" href="<?php echo esc_url(home_url('/')); ?>" class="btn"><?php echo esc_html__('Back to Home page', 'voip-theme'); ?></a>
      </div>
    </div>
  </section>
  <?php get_template_part('template-blocks/action'); ?>
</main>
<?php get_footer(); ?>
