<?php
if (!function_exists('get_sub_field')) {
    return;
} ?>

<section class="pricing" id="solutions">
    <div class="container">
        <?php if (have_rows('list')):
            $i = 0; ?>
            <div class="pricing__list">
                <?php while (have_rows('list')):

                    the_row();
                    $title = get_sub_field('title');
                    $text = get_sub_field('text');
                    $image = get_sub_field('image');
                ?>
                    <div class="pricing-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="pricing-card__inner">
                            <div class="pricing-card__inner-top">
                                <?php
                                if ($title) {
                                    echo '<h2 class="title">' . esc_html($title) . '</h2>';
                                }
                                if ($text) {
                                    echo '<div class="simple-text">' . wp_kses_post($text) . '</div>';
                                }
                                ?>
                                <div class="pricing-card__grid-more">more</div>
                            </div>

                            <?php if ($image): ?>
                                <div class="pricing-card__image">
                                    <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>">
                                    <div class="pricing-card__image-buttons">
                                        <?php
                                        $link = get_sub_field('btn_1');
                                        if ($link):
                                            $link_url = $link['url'];
                                            $link_title = $link['title'];
                                            $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                            <a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                                        <?php endif; ?>

                                        <?php
                                        $link = get_sub_field('btn_2');
                                        if ($link):
                                            $link_url = $link['url'];
                                            $link_title = $link['title'];
                                            $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                            <a class="btn btn--light" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (have_rows('features_list')): ?>
                                <div class="pricing-card__grid">
                                    <?php while (have_rows('features_list')): the_row();
                                        $icon = get_sub_field('icon');
                                        $title = get_sub_field('title');
                                        $text = get_sub_field('text');
                                    ?>
                                        <div class="pricing-card__grid-item">
                                            <div class="pricing-card__grid-inner">
                                                <?php if ($icon): ?>
                                                    <span class="pricing-card__grid-icon">
                                                        <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>">
                                                    </span>
                                                <?php endif;
                                                if ($title) echo '<div class="pricing-card__grid-title">' . esc_html($title) . '</div>'; ?>
                                            </div>
                                            <?php if ($text) echo '<div class="pricing-card__grid-text">' . esc_html($text) . '</div>';
                                            ?>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php $i++;
                endwhile; ?>
            </div>
        <?php
        endif; ?>

    </div>
</section>