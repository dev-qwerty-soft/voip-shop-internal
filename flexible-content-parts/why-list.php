<?php
if (!function_exists('get_sub_field')) {
    return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');

?>


<section class="why-list" id="whySticky">
    <div class="why-list__sticky">
        <div class="container">
            <div class="why-list__inner">
                <div class="why-list__inner-top">
                    <?php
                    if ($label) {
                        echo '<div class="title-label" data-aos="fade-up">' . esc_html($label) . '</div>';
                    }
                    if ($title) {
                        echo '<div class="title" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($title) . '</div>';
                    }
                    ?>
                </div>
                <div class="why-list__inner-bottom">
                    <div class="why-list__inner-buttons" data-aos="fade-up" data-aos-delay="600">
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
                <div class="why-list__images">
                    <?php
                    $i = 0;
                    while (have_rows('list')): the_row();
                        $image = get_sub_field('image');
                        $title = get_sub_field('title');
                    ?>
                        <div class="img-slide<?php if ($i == 0) echo ' active'; ?>" data-index="<?= $i; ?>">
                            <?php if ($image): ?>
                                <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($title); ?>">
                            <?php endif; ?>
                        </div>
                    <?php
                        $i++;
                    endwhile; ?>
                </div>
            <?php endif; ?>
            <?php if (have_rows('list')): ?>
                <div class="why-list__list">
                    <?php
                    $i = 0;
                    while (have_rows('list')): the_row();
                        $image = get_sub_field('image');
                        $title = get_sub_field('title');
                        $text = get_sub_field('text');
                    ?>
                        <div class="why-list__item<?php if ($i == 0) echo ' active'; ?>" data-index="<?= $i; ?>">
                            <?php if ($image): ?>
                                <div class="why-list__item-image">
                                    <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($title); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="why-list__item-head">
                                <div class="why-list__item-title"><?= esc_html($title); ?></div>
                                <div class="why-list__item-arrow">
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.0533 12.0533L24.5399 24.5399M24.5399 24.5399L11.5104 24.5399M24.5399 24.5399L24.5399 11.5104" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="why-list__item-body simple-text">
                                <?= esc_html($text); ?>
                            </div>
                        </div>
                    <?php
                        $i++;
                    endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>