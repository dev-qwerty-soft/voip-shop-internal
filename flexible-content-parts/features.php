<?php
if (!function_exists('get_sub_field')) {
    return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$text = get_sub_field('text');
$button_2 = get_sub_field('button_2');
?>

<section class="features">
    <div class="container">
        <div class="features__inner">
            <div class="features__inner-top">
                <?php
                if ($label) {
                    echo '<div class="title-label" data-aos="fade-up">' . esc_html($label) . '</div>';
                }
                if ($title) {
                    echo '<div class="title" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($title) . '</div>';
                }
                ?>
            </div>
            <div class="features__inner-bottom">
                <?php
                if ($text) {
                    echo '<div class="features__text simple-text" data-aos="fade-up" data-aos-delay="400">' . esc_html($text) . '</div>';
                }
                ?>
                <div class="features__inner-buttons" data-aos="fade-up" data-aos-delay="600">
                    <?php
                    $link = get_sub_field('button_1');
                    if ($link):
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                        <button class="btn" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;"><?php echo esc_html($link_title); ?></button>
                    <?php endif; ?>
                    <?php
                    $link = get_sub_field('button_2');
                    if ($link):
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                        <a class="btn btn--light" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <?php if (have_rows('list')): ?>
            <ul class="features__list" data-aos="fade-up" data-aos-delay="800">
                <?php
                while (have_rows('list')): the_row();
                    $icon = get_sub_field('icon');
                    $title = get_sub_field('title');
                    $text = get_sub_field('text');
                ?>
                    <li class="features__item">
                        <div class="features__item-num">
                            <?php if ($icon): ?>
                                <img src="<?= esc_url($icon['url']);?>" alt="<?= esc_attr($icon['alt']);?>">
                            <?php endif; ?>
                        </div>
                        <div class="features__item-inner">
                            <?php
                            if ($title) echo '<div class="features__item-title">' . esc_html($title) . '</div>';
                            if ($text) echo '<div class="features__item-text simple-text">' . esc_html($text) . '</div>';
                            ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
<div id="schedule-anchor"></div>