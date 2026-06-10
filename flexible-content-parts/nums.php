<?php
if (!function_exists('get_sub_field')) {
  return;
} ?>

<section class="nums">
    <div class="container">
        <?php if (have_rows('list')): ?>
            <div class="nums__wrap">
                <?php
                $delay = 100;
                while (have_rows('list')):

                  the_row();
                  $number = get_sub_field('number');
                  $title = get_sub_field('title');
                  $text = get_sub_field('text');
                  ?>
                    <div class="nums__item" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                        <div class="nums__item-number"><?= esc_html($number); ?></div>
                        <div class="nums__item-title"><?= esc_html($title); ?></div>
                        <div class="nums__item-text simple-text"><?= wp_kses_post($text); ?></div>
                    </div>
                <?php $delay += 150;
                endwhile;
                ?>
            </div>
        <?php endif; ?>
        <div class="line-gradient" data-aos="zoom-in" data-aos-delay="1000"></div>
    </div>
</section>