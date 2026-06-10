<?php
if (!function_exists('get_sub_field')) {
    return;
}

$title = get_sub_field('title');
?>

<section class="faq">
    <div class="container">
        <div class="faq__top">
            <?php
            if ($title) {
                echo '<h2 class="title" data-aos="fade-up">' . esc_html($title) . '</h2>';
            }

            $i = 0;
            if (have_rows('faq_list')):  ?>
                <div class="faq__tabs-wrap">
                    <div class="faq__tabs">
                        <?php while (have_rows('faq_list')): the_row();
                            $tab = get_sub_field('tab');
                            $i++;
                        ?>
                            <span
                                <?= $i == 1 ? 'class="active"' : '' ?>>
                                <?= esc_html($tab) ?>
                            </span>
                        <?php
                        endwhile; ?>
                        <div class="slider"></div>
                    </div>
                </div>
            <?php endif;
            ?>
        </div>

        <?php if (have_rows('faq_list')): ?>
            <div class="faq__content">
                <?php
                $y = 0;
                while (have_rows('faq_list')): the_row(); ?>

                    <?php if (have_rows('list')): ?>
                        <div class="faq__wrap<?php if ($y == 0) echo ' active'; ?>">
                            <?php
                            $i = 0;
                            $delay = 100;
                            while (have_rows('list')): the_row();
                                $title = get_sub_field('title');
                                $text = get_sub_field('text');
                            ?>
                                <div class="faq__item accordion-item<?php if ($i == 0) echo ' active'; ?>">
                                    <div class="faq__item-top accordion-head">
                                        <div class="faq__item-title"><?= esc_html($title); ?></div>
                                        <div class="faq__item-icon">
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="faq__item-content simple-text">
                                        <?= wp_kses_post($text); ?>
                                    </div>
                                </div>
                            <?php
                                $i++;
                                $delay += 50;
                            endwhile; ?>
                        </div>
                    <?php endif; ?>

                <?php
                    $y++;
                endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>