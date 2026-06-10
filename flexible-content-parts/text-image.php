<?php
if (!function_exists('get_sub_field')) {
  return;
}

$label = get_sub_field('label');
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$text = get_sub_field('text');
$img = get_sub_field('image');
?>

<section class="text-image">
    <div class="container">
        <div class="text-image__wrap">
            <div class="text-image__wrap-top">
                <?php
                if ($label) {
                  echo '<div class="title-label" data-aos="fade-up" data-aos-delay="100">' . esc_html($label) . '</div>';
                }
                if ($title) {
                  echo '<h2 class="title" data-aos="fade-up" data-aos-delay="200">' . esc_html($title) . '</h2>';
                }
                ?>
            </div>
            <?php if ($text) {
              echo '<div class="simple-text" data-aos="fade-up" data-aos-delay="400">' . wp_kses_post($text) . '</div>';
            } ?>
        </div>
        <?php if ($img): ?>
            <img src="<?= esc_url($img['url']); ?>" alt="<?= esc_attr($img['alt']); ?>" data-aos="fade-up" data-aos-delay="0">
        <?php endif; ?>
    </div>
</section>