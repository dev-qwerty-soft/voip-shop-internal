<?php
if (!function_exists('get_sub_field')) {
  return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$text = get_sub_field('text');
?>

<section class="accordion">
    <div class="container">
        <div class="accordion__top">
            <?php
            if ($label) {
              echo '<div class="title-label" data-aos="fade-up">' . esc_html($label) . '</div>';
            }
            if ($title) {
              echo '<h2 class="title" data-aos="fade-up" data-aos-delay="200">' . esc_html($title) . '</h2>';
            }
            if ($text) {
              echo '<div class="text simple-text" data-aos="fade-up" data-aos-delay="300">' . wp_kses_post($text) . '</div>';
            }
            ?>
        </div>
        <div class="line-gradient"></div>
        <?php if (have_rows('list')):
          $i = 0; ?>
            <div class="accordion__wrap">
                <?php while (have_rows('list')):

                  the_row();
                  $num = get_sub_field('num');
                  $title = get_sub_field('title');
                  $image = get_sub_field('image');
                  $label = get_sub_field('label');
                  $text = get_sub_field('text');
                  ?>
                    <div class="accordion__item accordion-item<?php if ($i == 0) {
                      echo ' active';
                    } ?>" data-aos="fade-up" data-aos-delay="400">
                        <div class="accordion__item-top accordion-head">
                            <div class="accordion__item-l"><?php if ($num) {
                              echo '<div class="accordion__item-num">' . esc_html($num) . '</div>';
                            } ?></div>
                            <div class="accordion__item-r">
                                <?php if ($title) {
                                  echo '<div class="accordion__item-title">' . esc_html($title) . '</div>';
                                } ?>
                                <div class="accordion__item-arrow">
                                    <?= displaySvg('src/svg/ic_arrow.svg') ?>
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item-content">
                            <div class="accordion__item-l">
                                <?php if ($image): ?>
                                    <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="accordion__item-r">
                                <?php if ($label) {
                                  echo '<div class="title-label title-label--blue">' . esc_html($label) . '</div>';
                                } ?>
                                <?php if ($text) {
                                  echo '<div class="accordion__item-text simple-text">' . wp_kses_post($text) . '</div>';
                                } ?>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endwhile; ?>
            </div>
            <div class="line-gradient"></div>
        <?php
        endif; ?>
    </div>
</section>