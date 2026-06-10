<?php
if (!function_exists('get_sub_field')) {
  return;
}
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$bg = get_sub_field('bg');
$bg_mob = get_sub_field('bg_mob');
?>

<section class="hero hero-simple">
    <div class="container">
        <div class="hero__top">
            <?php if ($title) {
              echo '<h1 class="title" data-aos="fade-up" data-aos-delay="200">' . esc_html($title) . '</h1>';
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
                    <button class="btn open-popup-chat"><?php echo esc_html($link_title); ?></button>
                <?php
                endif;
                $link = get_sub_field('button_2');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <a class="btn btn--light" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                <?php
                endif;
                ?>
            </div>
            <div class="hero__element-wrap">
                <div class="hero__element">
                    <?= displaySvg('src/svg/floating-button.svg') ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($bg): ?>
        <img class="hero-simple__bg" data-aos="fade-bottom" data-aos-delay="0" src="<?= esc_url($bg['url']); ?>" alt="<?= esc_attr($bg['alt']); ?>">
    <?php endif; ?>
    <?php if ($bg_mob): ?> 
        <img class="hero-simple__bg hero-simple__bg--mob" data-aos="fade-bottom" data-aos-delay="0" src="<?= esc_url($bg_mob['url']); ?>" alt="<?= esc_attr($bg_mob['alt']); ?>">
    <?php endif; ?>
</section>