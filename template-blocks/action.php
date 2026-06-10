<?php
$title = get_field('action_title', 'options');
$text = get_field('action_text', 'options');
$bg = get_field('action_bg', 'options');
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
                $link = get_field('action_button_1', 'options');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <a class="btn open-popup-chat" data-aos="fade-up" data-aos-delay="600"><?php echo esc_html($link_title); ?></a>
                <?php
                endif;
                $link = get_field('action_button_2', 'options');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <span class="btn btn--light" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;" data-aos="fade-up" data-aos-delay="800" ><?php echo esc_html($link_title); ?></span>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php if ($bg): ?>
        <div class="action__bg">
            <img width="1200" height="400" loading="lazy" fetchpriority="low" decoding="async" src="<?= esc_url($bg['url']); ?>" alt="<?= $bg['alt'] ?: $bg['title'] ?>">
        </div>
    <?php endif; ?>
</section>