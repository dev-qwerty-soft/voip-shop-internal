<?php
if (!function_exists('get_sub_field')) {
  return;
}

$title = get_sub_field('title');
$text = get_sub_field('text');
$bg = get_sub_field('bg');
?>

<section class="action">
    <div class="container">
        <div class="action__wrap">
            <?php
            if ($title) {
              echo '<h2 class="title" data-aos="fade-up" data-aos-delay="200">' . esc_html($title) . '</h2>';
            }
            if ($text) {
              echo '<div class="simple-text" data-aos="fade-up" data-aos-delay="400">' . wp_kses_post($text) . '</div>';
            }
            ?>
            <div class="action__buttons">
                <?php
                $link = get_sub_field('button_1');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <button class="btn open-popup-chat" data-aos="fade-up" data-aos-delay="600"><?php echo esc_html($link_title); ?></button>
                <?php
                endif;
                $link = get_sub_field('button_2');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <span class="btn btn--light" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;" data-aos="fade-up" data-aos-delay="800"><?php echo esc_html($link_title); ?></span>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php if ($bg): ?>
        <div class="action__bg">
            <img src="<?= $bg['url'] ?>" alt="<?= $bg['alt'] ?>">
        </div>
    <?php endif; ?>
</section>