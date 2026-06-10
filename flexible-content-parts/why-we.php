<?php
if (!function_exists('get_sub_field')) {
    return;
}
$title = get_sub_field('title');
$txt = get_sub_field('txt');
?>

<section class="why-we">
    <div class="container">
        <?php
        if ($title) echo '<h2 class="title" data-aos="fade-up">' . esc_html($title) . '</h2>';
        if ($txt) echo '<p class="simple-text" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($txt) . '</p>';
        if (have_rows('list')):
            $i = 0;
            $d = 400;
        ?>
            <div class="why-we__grid">
                <?php while (have_rows('list')): the_row();
                    $image = get_sub_field('image');
                    $title = get_sub_field('title');
                    $text = get_sub_field('text');
                ?>
                    <div class="why-we__item" data-aos="fade-up" data-aos-delay="<?= $d; ?>">
                        <?php if ($image): ?>
                            <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>">
                        <?php
                        endif;
                        if ($title) echo '<h3 class="why-we__item-title">' . esc_html($title) . '</h3>';
                        if ($text) echo '<div class="why-we__item-text">' . esc_html($text) . '</div>';
                        ?>
                        <span class="why-we__item-more">
                            more
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <path d="M10.5817 5.8335L7.04083 9.37433L3.5 5.8335" stroke="#D4D6E2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                <?php
                    $i++;
                    $d += 100;
                endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>