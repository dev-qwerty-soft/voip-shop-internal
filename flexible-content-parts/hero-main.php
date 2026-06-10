<?php
if (!function_exists('get_sub_field')) {
  return;
}
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$bg = get_sub_field('bg');
$planet_image = get_sub_field('planet_image');
?>
<section class="hero">
    <div class="container">
        <div class="hero__top">
            <?php if ($title) {
              echo '<h1 class="title title-gradient" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($title) . '</h1>';
            } ?>
            <?php if ($subtitle) {
              echo '<div class="subtitle simple-text" data-aos="fade-up" data-aos-delay="400">' . esc_html($subtitle) . '</div>';
            } ?>
            <div class="hero__buttons" data-aos="fade-up" data-aos-delay="600">
                <?php
                $link = get_sub_field('button_1');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <a class="btn" href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>"><?= esc_html($link_title) ?></a>
                <?php
                endif;
                $link = get_sub_field('button_2');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <a class="btn btn--light" href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>"><?= esc_html($link_title) ?></a>
                <?php
                endif;
                ?>
            </div>
            <div class="hero__element-wrap">
                <div class="hero__element">
                    <?= displaySvg('src/svg/hero-el.svg') ?>
                </div>
            </div>
            <?php if ($bg): ?>
                <div class="hero__bg" data-aos="hero-bg" data-aos-duration='2500'>
                    <img src="<?= esc_url($bg['url']); ?>" alt="<?= esc_attr($bg['alt']); ?>">
                </div>
            <?php endif; ?>
        </div>
        <?php if (have_rows('features')): ?>
            <div class="hero__bottom">
                <div class="hero__grid">
                    <?php while (have_rows('features')):

                      the_row();
                      $icon = get_sub_field('icon');
                      $text = get_sub_field('text');
                      ?>
                        <div class="hero__feature">
                            <div class="hero__feature-icon">
                                <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>">
                            </div>
                            <div class="hero__feature-text"><?= wp_kses_post($text); ?></div>
                        </div>
                    <?php
                    endwhile; ?>
                </div>
                <?php if ($planet_image): ?>
                    <div class="hero__planet" data-aos="hero-planet" data-aos-duration='3500'>
                        <img src="<?= esc_url($planet_image['url']); ?>" alt="<?= esc_attr($planet_image['alt']); ?>">
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>