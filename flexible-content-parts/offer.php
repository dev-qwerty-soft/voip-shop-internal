<?php
if (!function_exists('get_sub_field')) {
  return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$text = get_sub_field('text');
$bg = get_sub_field('bg');
?>

<section class="offer">
    <div class="container">
        <div class="offer__wrap">
            <?php
            if ($label) {
              echo '<div class="title-label" data-aos="fade-up" data-aos-delay="200">' . esc_html($label) . '</div>';
            }
            if ($title) {
              echo '<h2 class="title" data-aos="fade-up" data-aos-delay="400">' . esc_html($title) . '</h2>';
            }
            if ($subtitle) {
              echo '<div class="subtitle" data-aos="fade-up" data-aos-delay="600">' . esc_html($subtitle) . '</div>';
            }
            if ($text) {
              echo '<div class="simple-text" data-aos="fade-up" data-aos-delay="800">' . wp_kses_post($text) . '</div>';
            }
            ?>
        </div>
    </div>
    <img class="offer__bg" src="<?= esc_url($bg); ?>" alt="offer bg">
</section>