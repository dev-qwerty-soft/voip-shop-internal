<?php
if (!function_exists('get_sub_field')) {
  return;
}
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$background_image = get_sub_field('background_image');
?>
<section class="hero" style="background-image: url('<?php echo esc_url($background_image['url'] ?? ''); ?>');">
    <div class="container">
        <div class="hero__content">
            <?php
            if ($title): ?>
                <h1 class="hero__title"><?php echo esc_html($title); ?></h1>
            <?php endif;
            if ($subtitle): ?>
                <p class="hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif;
            ?>
        </div>
    </div>
</section>