<?php
if (!function_exists('get_sub_field')) {
    return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
?>

<section class="reviews">
    <div class="container">
        <div class="reviews__inner">
            <div class="reviews__inner-top">
                <?php
                if ($label) {
                    echo '<div class="title-label" data-aos="fade-up">' . esc_html($label) . '</div>';
                }
                if ($title) {
                    echo '<div class="title" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($title) . '</div>';
                }
                ?>
            </div>
            <div class="reviews__inner-nav">
                <div class="reviews__arrow reviews__arrow--prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                        <path d="M24.4587 18.3841L11.7051 18.3841M11.7051 18.3841L18.3591 11.73M11.7051 18.3841L18.3591 25.0381" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="reviews__arrow reviews__arrow--next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                        <path d="M12.3109 18.3855L25.0645 18.3855M25.0645 18.3855L18.4104 25.0395M25.0645 18.3855L18.4104 11.7314" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <?php if (have_rows('reviews')): ?>
            <div class="reviews__slider swiper">
                <div class="swiper-wrapper">
                    <?php while (have_rows('reviews')): the_row();
                        $logo = get_sub_field('logo');
                        $name = get_sub_field('name');
                        $text = get_sub_field('text');
                    ?>
                        <div class="reviews__slide swiper-slide">
                            <div class="reviews__slide-inner">
                                <div class="reviews__slide-text simple-text">
                                    <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 20V13.3913C0 10.8986 0.391608 8.57971 1.17483 6.43478C2.01399 4.23188 3.44056 2.08695 5.45455 0L8.89511 2.86956C7.32867 4.55072 6.26573 6.11594 5.70629 7.56522C5.14685 8.95652 4.86713 10.4348 4.86713 12L2.76923 10.3478H9.31469V20H0ZM14.6853 20V13.3913C14.6853 10.8986 15.0769 8.57971 15.8601 6.43478C16.6993 4.23188 18.1259 2.08695 20.1399 0L23.5804 2.86956C22.014 4.55072 20.951 6.11594 20.3916 7.56522C19.8322 8.95652 19.5524 10.4348 19.5524 12L17.4545 10.3478H24V20H14.6853Z" fill="#807DFE" />
                                    </svg>
                                    <?= $text; ?>
                                </div>
                                <div class="reviews__slide-bottom">
                                    <?php if ($logo): ?>
                                        <div class="reviews__slide-logo">
                                            <img
                                                src="<?= esc_url($logo['url']); ?>"
                                                alt="<?= esc_attr($logo['alt'] ?: ''); ?>">
                                        </div>
                                    <?php else: ?>
                                        <div class="reviews__slide-logo">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="32" height="32" rx="16" fill="#232235" />
                                                <path d="M17.3519 7.19385C18.153 7.20383 18.11 7.85445 18.0988 8.44701C18.093 8.75599 18.0939 9.07528 18.0948 9.3847L18.0952 12.8718L18.0972 23.0126C18.0953 23.4507 18.0482 23.9123 17.4893 23.9765C17.1814 24.0118 16.8319 23.994 16.5198 23.994L14.8073 23.9936C13.1531 23.9935 11.4274 24.0188 9.77834 23.9924C8.95862 23.9681 8.99069 23.4658 8.99438 22.8184C8.99613 22.5108 8.99531 22.1944 8.99563 21.8862L8.99693 18.7152L8.9964 13.9114L8.99463 12.4201C8.99417 11.9637 8.96733 11.4142 9.10333 10.9789C9.23744 10.5716 9.60067 10.0907 9.97483 9.88817C10.6863 9.4863 11.5121 9.32952 12.2547 9.01749C12.8217 8.77923 13.366 8.61601 13.9401 8.40622L15.9173 7.68855C16.4134 7.50481 16.8059 7.28625 17.3519 7.19385ZM14.5926 13.4813C15.2133 13.404 15.4643 12.9362 15.1597 12.3567C15.0122 12.076 14.4952 12.1075 14.2258 12.1048C13.6512 12.113 13.064 12.0918 12.4903 12.1187C11.5969 12.1321 11.5793 13.2847 12.331 13.4569C12.5473 13.5065 12.9869 13.4858 13.2238 13.4854L14.5926 13.4813ZM14.5928 16.2812C14.7893 16.2566 15.0302 16.1985 15.15 16.0294C15.4379 15.623 15.2628 14.929 14.6986 14.9189C13.996 14.9064 13.1837 14.8811 12.4847 14.9186C12.3205 14.9216 12.0875 14.9585 11.9778 15.0902C11.6549 15.4778 11.7794 16.168 12.3319 16.2548C12.6388 16.3029 12.9415 16.2861 13.2477 16.2855L14.5928 16.2812ZM14.5929 19.0811C15.2799 18.9952 15.518 18.4023 15.0535 17.8402C14.9993 17.7747 14.7508 17.7255 14.6697 17.7169C13.9555 17.7063 13.1946 17.6858 12.4835 17.7185C11.5061 17.7355 11.6626 19.0412 12.3922 19.0668C13.0946 19.0915 13.8872 19.0945 14.5929 19.0811Z" fill="#807DFE" />
                                                <path d="M19.4888 12.9434C19.7296 12.9795 20.072 13.185 20.301 13.2855C20.9803 13.5909 21.8049 13.7931 22.3617 14.3092C23.0511 14.9482 22.9979 15.6933 22.9961 16.5467L22.9958 17.7421L22.997 22.2378C22.9973 22.4962 22.9996 22.7552 22.997 23.0135C22.9937 23.3467 22.998 23.5907 22.7487 23.8407C22.3769 24.0575 21.7362 23.995 21.3026 23.9948L19.375 23.9897C19.5496 23.5419 19.4923 22.3247 19.4921 21.78L19.492 18.8482V14.9876C19.4921 14.3283 19.5079 13.5979 19.4888 12.9434Z" fill="#807DFE" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($name) echo '<div class="reviews__slide-name">' . $name . '</div>'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="reviews__mobile">
                    <div class="reviews__arrow reviews__arrow--prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                            <path d="M24.4587 18.3841L11.7051 18.3841M11.7051 18.3841L18.3591 11.73M11.7051 18.3841L18.3591 25.0381" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="reviews__arrow reviews__arrow--next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                            <path d="M12.3109 18.3855L25.0645 18.3855M25.0645 18.3855L18.4104 25.0395M25.0645 18.3855L18.4104 11.7314" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>