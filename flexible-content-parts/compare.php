<?php
if (!function_exists('get_sub_field')) {
    return;
}
$tt = get_sub_field('title');
$compare = get_sub_field('compare');
$reviews = get_sub_field('reviews');
?>

<section class="compare">
    <div class="container">
        <?php
        if ($reviews):
            $r_label = $reviews['label'];
            $r_text  = $reviews['text'];
            $r_items = $reviews['reviews_list'];
        ?>
            <div class="compare__reviews">
                <?php
                // if ($r_label) echo '<div class="compare__reviews-label" data-aos="fade-up">' . esc_html($r_label) . '</div>';
                // if ($r_text) echo '<div class="compare__reviews-text" data-aos="fade-up" data-aos-delay="100">' . wp_kses_post($r_text) . '</div>';
                ?>

                <?php if ($r_items): ?>
                    <div class="compare__reviews-list swiper" data-aos="fade-up" data-aos-delay="300">
                        <div class="swiper-wrapper">
                            <?php foreach ($r_items as $item):
                                $label = $item['label'];
                                $title = $item['title'];
                                $text = $item['text'];
                            ?>
                                <div class="swiper-slide compare__reviews-item">
                                    <?php
                                    if ($label) echo '<div class="compare__reviews-label" data-aos="fade-up">' . esc_html($label) . '</div>';
                                    if ($title) echo '<div class="compare__reviews-text" data-aos="fade-up" data-aos-delay="100">' . wp_kses_post($title) . '</div>';
                                    ?>
                                    <svg width="25" height="20" viewBox="0 0 25 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M-0.000999629 19.3201V12.9361C-0.000999629 10.5281 0.391 8.2881 1.175 6.2161C2.015 4.0881 3.443 2.0161 5.459 0.000104427L8.903 2.7721C7.335 4.3961 6.271 5.9081 5.711 7.30811C5.151 8.6521 4.871 10.0801 4.871 11.5921L2.771 9.9961H9.323V19.3201H-0.000999629ZM14.699 19.3201V12.9361C14.699 10.5281 15.091 8.2881 15.875 6.2161C16.715 4.0881 18.143 2.0161 20.159 0.000104427L23.603 2.7721C22.035 4.3961 20.971 5.9081 20.411 7.30811C19.851 8.6521 19.571 10.0801 19.571 11.5921L17.471 9.9961H24.023V19.3201H14.699Z" fill="#807DFE" />
                                    </svg>
                                    <?php if ($text) echo esc_html($text); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="compare__reviews-arrows">
                            <div class="compare__reviews-arrow compare__reviews-arrow--prev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                                    <path d="M24.4606 18.3841L11.707 18.3841M11.707 18.3841L18.3611 11.73M11.707 18.3841L18.3611 25.0381" stroke="#807DFE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="compare__reviews-arrow compare__reviews-arrow--next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                                    <path d="M12.3128 18.3852L25.0664 18.3852M25.0664 18.3852L18.4124 25.0393M25.0664 18.3852L18.4124 11.7312" stroke="#807DFE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        if ($compare):
            $c_title_1 = $compare['column_title_1'];
            $c_title_2 = $compare['column_title_2'];
            $c_title_3 = $compare['column_title_3'];
            $c_title_m = $compare['column_title_mobile'];
            $c_items = $compare['compare_list'];
        ?>
            <div class="compare__inner">
                <?php
                if ($tt) echo '<h2 class="title" data-aos="fade-up" data-aos-delay="400">' . esc_html($tt) . '</h2>';
                ?>

                <?php if ($c_items): ?>
                    <div class="compare__table" data-aos="fade-up" data-aos-delay="600">
                        <div class="compare__table-head">
                            <div class="compare__table-logo">
                                <?php if (has_custom_logo()): ?>
                                    <?php the_custom_logo(); ?>
                                <?php endif; ?>
                            </div>
                            <?php foreach ($c_items as $item):
                                $name = $item['name'];
                            ?>
                                <div class="compare__table-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                        <path d="M17.7704 6.76929L9.30889 15.2308L5.07812 11.0001" stroke="#2CFF56" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <?php if ($name) echo  esc_html($name); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="compare__table-main">
                            <div class="compare__table-title">
                                <?php
                                if ($c_title_1) echo '<span>' . esc_html($c_title_1) . '</span>';
                                if ($c_title_2) echo '<span>' . esc_html($c_title_2) . '</span>';
                                if ($c_title_3) echo '<span>' . esc_html($c_title_3) . '</span>';
                                if ($c_title_m) echo '<span class="compare__table-title--mobile">' . esc_html($c_title_m) . '</span>';
                                ?>
                            </div>
                            <div class="compare__table-body">
                                <?php foreach ($c_items as $item):
                                    $check_1 = $item['check_1'];
                                    $check_2 = $item['check_2'];
                                    $check_3 = $item['check_3'];
                                ?>
                                    <div class="compare__table-row">
                                        <?php if ($check_1): ?>
                                            <div class="compare__table-row--item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M16.1557 6.15381L8.46334 13.8461L4.61719 9.99996" stroke="#2CFF56" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="compare__table-row--item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M5 15L15 5M5 5L15 15" stroke="#FF5521" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($check_2): ?>
                                            <div class="compare__table-row--item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M16.1557 6.15381L8.46334 13.8461L4.61719 9.99996" stroke="#2CFF56" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="compare__table-row--item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M5 15L15 5M5 5L15 15" stroke="#FF5521" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($check_3): ?>
                                            <div class="compare__table-row--item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M16.1557 6.15381L8.46334 13.8461L4.61719 9.99996" stroke="#2CFF56" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="compare__table-row--item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M5 15L15 5M5 5L15 15" stroke="#FF5521" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <span class="compare__bg-el compare__bg-el--top">
                    <svg xmlns="http://www.w3.org/2000/svg" width="690" height="201" viewBox="0 0 690 201" fill="none">
                        <g filter="url(#filter0_f_1814_6851)" style="mix-blend-mode:plus-lighter">
                            <path d="M343.851 1L200 -16L490 -16L343.851 1Z" fill="#D4D6E2" />
                        </g>
                        <defs>
                            <filter id="filter0_f_1814_6851" x="0" y="-216" width="690" height="417" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_1814_6851" />
                            </filter>
                        </defs>
                    </svg>
                </span>

                <span class="compare__bg-el compare__bg-el--bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="700" height="204" viewBox="0 0 700 204" fill="none">
                        <g filter="url(#filter0_f_1814_6860)" style="mix-blend-mode:plus-lighter">
                            <path d="M351.188 200L500 210L200 210L351.188 200Z" fill="#D4D6E2" />
                        </g>
                        <defs>
                            <filter id="filter0_f_1814_6860" x="0" y="0" width="700" height="410" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_1814_6860" />
                            </filter>
                        </defs>
                    </svg>
                </span>

                <span class="compare__bg-el compare__bg-el--left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="211" height="460" viewBox="0 0 211 460" fill="none">
                        <g filter="url(#filter0_f_1814_6853)" style="mix-blend-mode:plus-lighter">
                            <path d="M11 221.634L-8 301L-8.00001 141L11 221.634Z" fill="#D4D6E2" />
                        </g>
                        <defs>
                            <filter id="filter0_f_1814_6853" x="-208" y="-59" width="419" height="560" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                <feGaussianBlur stdDeviation="100" result="effect1_foregroundBlur_1814_6853" />
                            </filter>
                        </defs>
                    </svg>
                </span>
            </div>
        <?php endif; ?>
    </div>
</section>