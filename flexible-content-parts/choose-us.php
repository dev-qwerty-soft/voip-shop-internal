<?php
if (!function_exists('get_sub_field')) {
  return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$text = get_sub_field('text');
$image = get_sub_field('image');

$users_title = get_sub_field('users_title');
$users_count = get_sub_field('users_count');

$quote = get_sub_field('quote');
?>

<section class="choose-us">
    <div class="container">
        <div class="choose-us__wrap">
            <div class="choose-us__box" data-aos="fade-up" data-aos-delay="100">
                <?php
                if ($label) {
                  echo '<div class="title-label">' . esc_html($label) . '</div>';
                }
                if ($title) {
                  echo '<h2 class="title">' . esc_html($title) . '</h2>';
                }
                if ($text) {
                  echo '<div class="text">' . wp_kses_post($text) . '</div>';
                }
                if ($image): ?>
                    <img class="choose-us__box-img" src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>">
                <?php endif;
                ?>
            </div>
            <div class="choose-us__box choose-us__box--users" data-aos="fade-left" data-aos-delay="300">
                <?php
                if (have_rows('avatars_list')): ?>
                    <div class="pricing-card__users">
                        <?php
                        while (have_rows('avatars_list')):

                          the_row();
                          $image = get_sub_field('image');
                          ?>
                            <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>">
                        <?php
                        endwhile;
                        if ($users_count) {
                          echo '<div class="pricing-card__users-more">' . $users_count . '</div>';
                        }
                        ?>
                    </div>
                <?php endif;
                if ($users_title) {
                  echo '<div class="title">' . esc_html($users_title) . '</div>';
                }
                ?>
            </div>
            <?php if ($quote): ?>
                <div class="choose-us__box choose-us__box--quote" data-aos="fade-left" data-aos-delay="400">
                    <?= displaySvg('src/svg/quote.svg') ?>
                    <?php if ($quote) {
                      echo '<div class="choose-us__quote">' . wp_kses_post($quote) . '<div>';
                    } ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>