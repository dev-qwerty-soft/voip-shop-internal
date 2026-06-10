<?php
if (!function_exists('get_sub_field')) {
    return;
}
$logo = get_sub_field('logo');
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$text = get_sub_field('text');
$bg = get_sub_field('bg');
?>

<ssection class="promo">
    <div class="container">
        <div class="promo__inner">
            <?php if ($logo): ?>
                <img class="promo__logo" data-aos="fade-up" src="<?= esc_url($logo['url']); ?>" alt="<?= esc_attr($logo['alt']); ?>">
            <?php endif; ?>
            <?php
            if ($title) {
                echo '<h2 class="title" data-aos="fade-up" data-aos-delay="200">' . esc_html($title) . '</h2>';
            }
            if ($subtitle) {
                echo '<div class="promo__subtitle subtitle" data-aos="fade-up" data-aos-delay="400">' . esc_html($subtitle) . '</div>';
            }
            if ($text) {
                echo '<div class="promo__text simple-text" data-aos="fade-up" data-aos-delay="600">' . esc_html($text) . '</div>';
            }
            ?>
        </div>
        <div class="promo__bg">
            <?php if ($bg): ?>
                <img src="<?= esc_url($bg['url']); ?>" alt="<?= esc_attr($bg['alt']); ?>">
            <?php endif; ?>
        </div>
    </div>
</ssection>