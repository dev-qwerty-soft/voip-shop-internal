<?php
if (!function_exists('get_sub_field')) {
  return;
}
$title = get_sub_field('title');
$text = get_sub_field('text');

$form = get_sub_field('form');
?>

<section class="contacts">
    <div class="container">
        <div class="contacts__grid">
            <div class="contacts__col contacts__col--head">
                <?php
                if ($title) {
                  echo '<h1 class="title" data-aos="fade-up" data-aos-delay="100">' . esc_html($title) . '</h1>';
                }
                if ($text) {
                  echo '<div class="simple-text" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($text) . '</div>';
                }
                $link = get_sub_field('button');
                if ($link):

                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                  ?>
                    <span class="btn btn--light" data-aos="fade-up" data-aos-delay="300" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;">
                        <?= displaySvg('src/svg/ic_calendly.svg') ?>
                        <?php echo esc_html($link_title); ?>
                    </span>
                <?php
                endif;
                ?>
            </div>
            <div class="contacts__col contacts__col--form" data-aos="fade-up" data-aos-delay="600">
                <?= $form ?>
            </div>
            <div class="contacts__col contacts__col--grid">
                <div class="contacts__col-grid">
                    <?php if (have_rows('list')):

                      $delay = 400;
                      while (have_rows('list')):

                        the_row();
                        $title = get_sub_field('title');
                        $text = get_sub_field('text');
                        ?>
                            <div class="contacts__item" data-aos="fade-up" data-aos-delay="<?= esc_attr($delay) ?>">
                                <div class="contacts__item-title simple-text"><?= esc_html($title); ?></div>
                                <div class="contacts__item-text"><?= wp_kses_post($text); ?></div>
                            </div>
                        <?php $delay += 100;
                      endwhile;
                      ?>
                    <?php
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>