<?php
if (!function_exists('get_sub_field')) {
  return;
}
$title = get_sub_field('title');
$text = get_sub_field('text');
$col = get_sub_field('columns');
$mobile_slider = get_sub_field('mobile_slider');

$class_sect = 'capabilities';
$class_grid = 'capabilities__wrap';
$class_item = 'capabilities__item';

if ($col == 4) {
  $class_grid .= ' capabilities__wrap--four';
}

if ($mobile_slider) {
  $class_sect .= ' capabilities--slider';
  $class_grid .= ' capabilities__slider';
  $class_item .= ' swiper-slide';
}
?>

<section class="<?= $class_sect ?>">
  <div class="container">
    <?php
    if ($title) {
      echo '<h2 class="title" data-aos="fade-up">' . wp_kses_post($title) . '</h2>';
    }
    if ($text) {
      echo '<div class="simple-text capabilities__text" data-aos="fade-up" data-aos-delay="50">' . wp_kses_post($text) . '</div>';
    }
    if (have_rows('list')): ?>
      <div class="<?= $class_grid ?>">
        <?php
        if ($mobile_slider) {
          echo '<div class="swiper-wrapper swiper-wrapper--four">';
        }
        $delay = 100;
        while (have_rows('list')):

          the_row();
          $icon = get_sub_field('icon');
          $title = get_sub_field('text');
          $text_area = get_sub_field('text_area');
        ?>
          <div class="capabilities__aos" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
            <div class="<?= $class_item ?>">
              <?php if ($icon): ?>
                <div class="capabilities__item-icon">
                  <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>">
                </div>
              <?php endif; ?>
              <div class="capabilities__bottom">
                <?php
                if ($title) {
                  echo '<div class="capabilities__item-title">' . esc_html($title) . '</div>';
                }
                if ($text_area) {
                  echo '<div class="capabilities__item-text simple-text">' . wp_kses_post($text_area) . '</div>';
                }
                ?>
              </div>
            </div>
          </div>
        <?php $delay += 100;
        endwhile;
        if ($mobile_slider) {
          echo '</div>';
        }
        ?>
      </div>
    <?php endif;
    ?>
    <?php
    $link = get_sub_field('button');
    if ($link):
      $link_url = $link['url'];
      $link_title = $link['title'];
      $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
      <div class="capabilities__bottom" data-aos="fade-up" data-aos-delay="800">
        <button class="btn" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;">
          <?php echo esc_html($link_title); ?>
        </button>
      </div>
    <?php endif; ?>
  </div>
</section>