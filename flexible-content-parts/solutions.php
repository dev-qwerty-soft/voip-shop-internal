<?php
if (!function_exists('get_sub_field')) {
  return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$text = get_sub_field('text');
$card_2 = get_sub_field('card_2');
$card_3 = get_sub_field('card_3');
?>
<section class="solutions">
    <div class="container">
        <div class="solutions__grid solutions__slider swiper">
            <div class="swiper-wrapper">
                <div class="solutions__item swiper-slide" data-aos="fade-up" data-aos-delay="100">
                    <?php
                    if ($label) {
                      echo '<div class="title-label">' . esc_html($label) . '</div>';
                    }
                    if ($title) {
                      echo '<h2 class="title">' . esc_html($title) . '</h2>';
                    }
                    if ($text) {
                      echo '<div class="simple-text">' . wp_kses_post($text) . '</div>';
                    }
                    ?>
                </div>
                <?php if ($card_2):

                  $c_img = $card_2['image'];
                  $c_img_mob = $card_2['image_mob'];
                  $c_text = $card_2['text'];
                  ?>
                    <div class="solutions__item swiper-slide solutions__item-simple" data-aos="fade-up" data-aos-delay="200">
                        <?php if ($c_img): ?>
                            <img class="img-desc" src="<?= esc_url($c_img['url']); ?>" alt="<?= esc_attr($c_img['alt']); ?>">
                        <?php endif; ?>
                        <?php if ($c_img_mob): ?>
                            <img class="img-mob" src="<?= esc_url($c_img_mob['url']); ?>" alt="<?= esc_attr($c_img_mob['alt']); ?>">
                        <?php endif; ?>
                        <div class="simple-text"><?= wp_kses_post($c_text); ?></div>
                    </div>
                <?php
                endif; ?>
                <?php if ($card_3):

                  $c_img = $card_3['image'];
                  $c_img_mob = $card_3['image_mob'];
                  $c_text = $card_3['text'];
                  ?>
                    <div class="solutions__item swiper-slide solutions__item-simple solutions__item-simple--2" data-aos="fade-up" data-aos-delay="300">
                        <div class="simple-text"><?= wp_kses_post($c_text); ?></div>
                        <?php if ($c_img): ?>
                            <img class="img-desc" src="<?= esc_url($c_img['url']); ?>" alt="<?= esc_attr($c_img['alt']); ?>">
                        <?php endif; ?>
                        <?php if ($c_img_mob): ?>
                            <img class="img-mob" src="<?= esc_url($c_img_mob['url']); ?>" alt="<?= esc_attr($c_img_mob['alt']); ?>">
                        <?php endif; ?>
                    </div>
                <?php
                endif; ?>
                <?php if (have_rows('card_4')): ?>
                    <div class="solutions__item swiper-slide solutions__item--grid" data-aos="fade-up" data-aos-delay="400">
                        <?php while (have_rows('card_4')):

                          the_row();
                          $icon = get_sub_field('icon');
                          $text = get_sub_field('text');
                          ?>
                            <div class="solutions__item-child">
                                <?php if ($icon): ?>
                                    <div class="solutions__item-child--img">
                                        <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                <?= wp_kses_post($text); ?>
                            </div>
                        <?php
                        endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>