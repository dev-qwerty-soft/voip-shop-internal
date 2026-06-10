<?php
if (!function_exists('get_sub_field')) {
  return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$text = get_sub_field('text');
$card_img = get_sub_field('card_img');
?>

<section class="how-works">
    <div class="container">
        <div class="how-works__inner">
            <div class="how-works__inner-t">
                <?php
                if ($label) {
                  echo '<div class="title-label" data-aos="fade-up" data-aos-delay="100">' . esc_html($label) . '</div>';
                }
                if ($title) {
                  echo '<h2 class="title" data-aos="fade-up" data-aos-delay="200">' . esc_html($title) . '</h2>';
                }
                ?>
            </div>
            <div class="how-works__inner-b">
                <?php
                if ($text) {
                  echo '<div class="how-works__inner-text simple-text" data-aos="fade-up" data-aos-delay="300">' . wp_kses_post($text) . '</div>';
                }
                $link = get_sub_field('button');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <button class="btn open-popup-chat" data-aos="fade-up" data-aos-delay="400"><?php echo esc_html($link_title); ?></button>
                <?php
                endif;
                ?>
            </div>
        </div>
        <div class="how-works__content">
            <div class="how-works__card" data-aos="fade-up" data-aos-delay="500">
                <div class="how-works__card-img">
                    <?php if ($card_img): ?>
                        <img fetchpriority="low" decoding="async" loading="lazy" width="400" height="560" src="<?= esc_url($card_img['url']); ?>" alt="<?= $card_img['title'] ?: $card_img['alt'] ?>">
                    <?php endif; ?>
                </div>
            </div>
            <?php if (have_rows('list')): ?>
                <div class="how-works__list">
                    <?php
                    $delay = 200;
                    while (have_rows('list')):

                      the_row();
                      $title = get_sub_field('title');
                      $text = get_sub_field('text');
                      $i = get_row_index();
                      $num = sprintf('%02d', $i);
                      ?>
                        <div class="how-works__list-item" data-aos="fade-left" data-aos-delay="<?= $delay; ?>">
                            <div class="how-works__list-inner">
                                <div class="how-works__list-num"><?= $num; ?></div>
                                <div class="how-works__list-title"><?= esc_html($title); ?></div>
                            </div>
                            <div class="how-works__list-text"><?= wp_kses_post($text); ?></div>
                        </div>
                    <?php $delay += 100;
                    endwhile;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>