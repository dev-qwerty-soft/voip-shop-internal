<?php
$link_1 = get_field('button_1', 'options');
$link_2 = get_field('button_2', 'options');
$help_center_link = get_field('help_center_link', 'options');
$contact_support_link = get_field('contact_support_link', 'options');
$open_support_link = get_field('support_ticket_link', 'options');

$support_group = get_field('support_dropdown_menu', 'options');
$support_group_links = isset($support_group['support_dropdown_menu_links']) ? $support_group['support_dropdown_menu_links'] : [];

$solutions_group = get_field('solutions_dropdown_menu', 'options');
$solutions_group_links = isset($solutions_group['solutions_dropdown_menu_links']) ? $solutions_group['solutions_dropdown_menu_links'] : [];

$login_group = get_field('login_dropdown_menu', 'options');
$login_group_links = isset($login_group['login_dropdown_menu_links']) ? $login_group['login_dropdown_menu_links'] : [];
?>
<!DOCTYPE html>
<html lang="<?= get_bloginfo('language') ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">

<head>
    <link rel="preload" href="<?= getUrl('style.css') ?>" as="style">
    <link rel="preconnect" href="<?= home_url('/') ?>">
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= title() ?></title>
    <?php preload_fonts(); ?>
    <script>
        window.ajaxurl = '<?= admin_url('admin-ajax.php') ?>';
        window.supportForm = {
            url: '<?= admin_url('admin-ajax.php') ?>',
            nonce: '<?= wp_create_nonce('support_form') ?>',
        };
    </script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <header class="header" role="banner">
        <div class="container">
            <div class="header__content">
                <div class="header__logo">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <a href="<?= esc_url(home_url('/')) ?>" rel="home">
                            <h1><?php bloginfo('name'); ?></h1>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="header__inner">
                    <nav class="header__nav" role="navigation">
                        <?php wp_nav_menu([
                            'theme_location' => 'header-menu',
                            'container' => false,
                            'menu_class' => 'header__menu',
                            'fallback_cb' => function () {
                                echo '<ul class="header__menu">';
                                echo '<li><a href="' . home_url() . '">Home</a></li>';
                                echo '<li><a href="' . home_url('/about') . '">About</a></li>';
                                echo '<li><a href="' . home_url('/contact') . '">Contact</a></li>';
                                echo '</ul>';
                            },
                        ]); ?>
                        <?php if ($help_center_link || $contact_support_link || $open_support_link || ($support_group_links && is_array($support_group_links) && count($support_group_links) > 0)): ?>
                            <div class="header-menu-dropdown header-menu-dropdown--support">
                                <?php if ($support_group_links && is_array($support_group_links) && count($support_group_links) > 0): ?>
                                    <ul class="header-menu-dropdown__links">
                                        <?php foreach ($support_group_links as $link) {
                                            $text = isset($link['text']) ? $link['text'] : '';
                                            $link_obj = isset($link['link']) ? $link['link'] : '';
                                            $target_link = isset($link['target']) ? $link['target'] : '';
                                            $title_link = isset($link_obj['title']) ? $link_obj['title'] : '';
                                            $url_link = isset($link_obj['url']) ? $link_obj['url'] : '';
                                            $icon = isset($link['icon']) ? $link['icon'] : '';
                                            $icon_url = isset($icon['url']) ? $icon['url'] : '';
                                            $img = displaySvg($icon_url);

                                            $has_text = !empty($text);
                                            $text_class = $has_text ? 'text' : 'text text--title';
                                            $item_class = $has_text ? 'header-menu-dropdown__links-item' : 'header-menu-dropdown__links-item header-menu-dropdown__links-item--center';

                                            echo "<li class='$item_class'>
                                                    <a target='$target_link' href='$url_link'>
                                                        <div class='icon'>
                                                            $img
                                                        </div>
                                                        <div class='$text_class'>
                                                            <span>$title_link</span>";

                                            if ($has_text) {
                                                echo "<p>$text</p>";
                                            }

                                            echo        "</div>
                                                    </a>
                                                </li>";
                                        } ?>
                                    </ul>
                                <?php endif; ?>
                                <?php if ($help_center_link || $contact_support_link || $open_support_link): ?>
                                    <ul class="header-menu-dropdown__bottom">
                                        <?php if ($help_center_link): ?>
                                            <li>
                                                <a href="<?= esc_url($help_center_link['url']) ?>" target="<?= esc_attr($help_center_link['target'] ? $help_center_link['target'] : '_self') ?>">
                                                    <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1491_645)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 17.25C4.44353 17.25 0.75 13.5565 0.75 9C0.75 4.44353 4.44353 0.75 9 0.75C13.5565 0.75 17.25 4.44353 17.25 9C17.25 13.5565 13.5565 17.25 9 17.25ZM6.525 7.17015H7.84995C7.89532 6.47137 8.36392 6.02588 9.09075 6.02588C9.8019 6.02588 10.2754 6.45983 10.2754 7.05713C10.2754 7.61565 10.0387 7.92007 9.33908 8.34412C8.5611 8.80035 8.23358 9.30772 8.28473 10.1426L8.2905 10.5427H9.59895V10.2161C9.59895 9.65175 9.80767 9.35805 10.546 8.92987C11.3133 8.47283 11.7423 7.86975 11.7423 7.0068C11.7415 5.766 10.7086 4.875 9.16335 4.875C7.49025 4.875 6.57037 5.8452 6.525 7.17015ZM8.96122 13.1935C9.41827 13.1935 9.7788 12.8387 9.7788 12.3932C9.7788 11.9477 9.41827 11.5971 8.96205 11.5971C8.50418 11.5971 8.13787 11.9469 8.13787 12.3924C8.13787 12.8379 8.50417 13.1935 8.96122 13.1935Z" fill="#D4D6E2" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_1491_645">
                                                                <rect width="18" height="18" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <span><?= isset($help_center_link['title']) ? $help_center_link['title'] : '' ?></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($contact_support_link): ?>
                                            <li>
                                                <a href="<?= esc_url($contact_support_link['url']) ?>" target="<?= esc_attr($contact_support_link['target'] ? $contact_support_link['target'] : '_self') ?>">
                                                    <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.8557 13.1485C15.2974 12.586 13.9453 11.7651 13.2893 11.4343C12.435 11.004 12.3647 10.9688 11.6932 11.4677C11.2453 11.8006 10.9475 12.098 10.4233 11.9862C9.89915 11.8745 8.76008 11.2441 7.7627 10.2499C6.76532 9.25566 6.0984 8.08355 5.98626 7.56113C5.87411 7.03871 6.17645 6.74445 6.50622 6.29551C6.97098 5.66269 6.93583 5.55723 6.53856 4.70293C6.22883 4.03848 5.38403 2.69902 4.81942 2.14355C4.21544 1.54695 4.21544 1.65242 3.82626 1.81414C3.5094 1.94742 3.20544 2.10947 2.91817 2.29824C2.35567 2.67195 2.04348 2.98238 1.82516 3.44891C1.60684 3.91543 1.50876 5.00914 2.63622 7.05734C3.76368 9.10555 4.55469 10.1529 6.19192 11.7855C7.82915 13.4182 9.08809 14.296 10.9285 15.3282C13.2052 16.6033 14.0785 16.3548 14.5465 16.1368C15.0144 15.9188 15.3262 15.6095 15.7006 15.047C15.8899 14.7602 16.0523 14.4565 16.1858 14.1399C16.3479 13.7521 16.4533 13.7521 15.8557 13.1485Z" fill="#D4D6E2" />
                                                    </svg>
                                                    <span><?= isset($contact_support_link['title']) ? $contact_support_link['title'] : '' ?></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($open_support_link): ?>
                                            <li>
                                                <a href="<?= esc_url($open_support_link['url']) ?>" target="<?= esc_attr($open_support_link['target'] ? $open_support_link['target'] : '_self') ?>">
                                                    <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16.5 8.0625C16.8075 8.0625 17.0625 7.8075 17.0625 7.5V6.75C17.0625 3.4425 16.0575 2.4375 12.75 2.4375H8.0625V4.125C8.0625 4.4325 7.8075 4.6875 7.5 4.6875C7.1925 4.6875 6.9375 4.4325 6.9375 4.125V2.4375H5.25C1.9425 2.4375 0.9375 3.4425 0.9375 6.75V7.125C0.9375 7.4325 1.1925 7.6875 1.5 7.6875C2.22 7.6875 2.8125 8.28 2.8125 9C2.8125 9.72 2.22 10.3125 1.5 10.3125C1.1925 10.3125 0.9375 10.5675 0.9375 10.875V11.25C0.9375 14.5575 1.9425 15.5625 5.25 15.5625H6.9375V13.875C6.9375 13.5675 7.1925 13.3125 7.5 13.3125C7.8075 13.3125 8.0625 13.5675 8.0625 13.875V15.5625H12.75C16.0575 15.5625 17.0625 14.5575 17.0625 11.25C17.0625 10.9425 16.8075 10.6875 16.5 10.6875C15.78 10.6875 15.1875 10.095 15.1875 9.375C15.1875 8.655 15.78 8.0625 16.5 8.0625ZM8.0625 10.6275C8.0625 10.935 7.8075 11.19 7.5 11.19C7.1925 11.19 6.9375 10.935 6.9375 10.6275V7.3725C6.9375 7.065 7.1925 6.81 7.5 6.81C7.8075 6.81 8.0625 7.065 8.0625 7.3725V10.6275Z" fill="white" />
                                                    </svg>
                                                    <span><?= isset($open_support_link['title']) ? $open_support_link['title'] : '' ?></span>
                                                    <svg class="chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5 2.93L8.035 5.965L5 9" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </nav>
                    <?php if (function_exists('WC') && WC()->cart): ?>
                        <a href="<?= esc_url(wc_get_cart_url()) ?>" class="header__cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M0.75 2.20325C0.75 1.8136 1.05913 1.50012 1.44338 1.50012H2.75791C3.39351 1.50012 3.95688 1.87512 4.21979 2.43762H16.0939C16.8537 2.43762 17.4085 3.17004 17.2091 3.91418L16.0246 8.3761C15.779 9.29602 14.9556 9.93762 14.0167 9.93762H5.68166L5.83768 10.7726C5.90124 11.1036 6.18725 11.3439 6.5195 11.3439H14.8487C15.233 11.3439 15.5421 11.6573 15.5421 12.047C15.5421 12.4366 15.233 12.7501 14.8487 12.7501H6.5195C5.51988 12.7501 4.66182 12.0294 4.47692 11.0363L2.98615 3.0968C2.96593 2.98547 2.87059 2.90637 2.75791 2.90637H1.44338C1.05913 2.90637 0.75 2.5929 0.75 2.20325ZM4.44803 15.0939C4.44803 14.9092 4.4839 14.7263 4.55359 14.5557C4.62328 14.3851 4.72543 14.2301 4.8542 14.0995C4.98297 13.9689 5.13585 13.8653 5.3041 13.7947C5.47235 13.724 5.65267 13.6876 5.83479 13.6876C6.0169 13.6876 6.19723 13.724 6.36548 13.7947C6.53373 13.8653 6.6866 13.9689 6.81537 14.0995C6.94415 14.2301 7.04629 14.3851 7.11598 14.5557C7.18568 14.7263 7.22155 14.9092 7.22155 15.0939C7.22155 15.2785 7.18568 15.4614 7.11598 15.632C7.04629 15.8026 6.94415 15.9577 6.81537 16.0882C6.6866 16.2188 6.53373 16.3224 6.36548 16.3931C6.19723 16.4637 6.0169 16.5001 5.83479 16.5001C5.65267 16.5001 5.47235 16.4637 5.3041 16.3931C5.13585 16.3224 4.98297 16.2188 4.8542 16.0882C4.72543 15.9577 4.62328 15.8026 4.55359 15.632C4.4839 15.4614 4.44803 15.2785 4.44803 15.0939ZM14.1553 13.6876C14.5231 13.6876 14.8759 13.8358 15.1359 14.0995C15.396 14.3632 15.5421 14.7209 15.5421 15.0939C15.5421 15.4668 15.396 15.8245 15.1359 16.0882C14.8759 16.352 14.5231 16.5001 14.1553 16.5001C13.7876 16.5001 13.4348 16.352 13.1748 16.0882C12.9147 15.8245 12.7686 15.4668 12.7686 15.0939C12.7686 14.7209 12.9147 14.3632 13.1748 14.0995C13.4348 13.8358 13.7876 13.6876 14.1553 13.6876Z" fill="#D4D6E2"/>
                            </svg>
                            Cart (<span class="header__cart-price"><?= number_format((float) WC()->cart->get_subtotal(), 2) ?></span>)
                        </a>
                    <?php endif; ?>
                    <?php if ($link_1): ?>
                        <button class="btn phone open-popup-chat open-popup-chat--header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M14.0939 11.6875C13.5977 11.1875 12.3958 10.4578 11.8127 10.1638C11.0533 9.78125 10.9908 9.75 10.3939 10.1934C9.9958 10.4894 9.73112 10.7538 9.26518 10.6544C8.79924 10.555 7.78674 9.99469 6.90018 9.11094C6.01362 8.22719 5.4208 7.18531 5.32112 6.72094C5.22143 6.25656 5.49018 5.995 5.7833 5.59594C6.19643 5.03344 6.16518 4.93969 5.81205 4.18031C5.53674 3.58969 4.7858 2.39906 4.28393 1.90531C3.74705 1.375 3.74705 1.46875 3.40112 1.6125C3.11947 1.73097 2.84928 1.87501 2.59393 2.04281C2.09393 2.375 1.81643 2.65094 1.62237 3.06563C1.4283 3.48031 1.34112 4.4525 2.3433 6.27313C3.34549 8.09375 4.04862 9.02469 5.50393 10.4759C6.95924 11.9272 8.0783 12.7075 9.71424 13.625C11.738 14.7584 12.5142 14.5375 12.9302 14.3438C13.3461 14.15 13.6233 13.875 13.9561 13.375C14.1243 13.1201 14.2687 12.8502 14.3874 12.5688C14.5314 12.2241 14.6252 12.2241 14.0939 11.6875Z" fill="white" />
                            </svg>
                            <?= esc_html($link_1['title']) ?>
                        </button>
                    <?php endif; ?>
                    <?php if ($link_2): ?>
                        <div class="login-menu">
                            <span class="btn login btn--light" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;" target="<?= esc_attr($link_2['target'] ? $link_2['target'] : '_self') ?>">
                                <?= esc_html($link_2['title']) ?>
                                <?php if ($login_group_links && is_array($login_group_links) && count($login_group_links) > 0): ?>
                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.55833 4.16667L5.02917 6.69584L2.5 4.16667" stroke="#01033E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                <?php endif; ?>
                            </span>
                            <?php if ($login_group_links && is_array($login_group_links) && count($login_group_links) > 0): ?>
                                <div class="login-menu-dropdown">
                                    <ul class="login-menu-dropdown__links">
                                        <?php foreach ($login_group_links as $link) {
                                            $text = isset($link['text']) ? $link['text'] : '';
                                            $link_obj = isset($link['link']) ? $link['link'] : '';
                                            $target_link = isset($link['target']) ? $link['target'] : '';
                                            $title_link = isset($link_obj['title']) ? $link_obj['title'] : '';
                                            $url_link = isset($link_obj['url']) ? $link_obj['url'] : '';
                                            $icon = isset($link['icon']) ? $link['icon'] : '';
                                            $icon_url = isset($icon['url']) ? $icon['url'] : '';
                                            $img = displaySvg($icon_url);
                                            echo "<li>
                                                <a target='$target_link' href='$url_link'>
                                                    <div class='icon'>
                                                        $img
                                                    </div>
                                                    <div class='text'>
                                                        <span>$title_link</span>
                                                        <p>$text</p>
                                                    </div>
                                                </a>
                                            </li>";
                                        } ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="header__mobile-actions">
                    <?php if (function_exists('WC') && WC()->cart): ?>
                        <a href="<?= esc_url(wc_get_cart_url()) ?>" class="header__cart-mobile" aria-label="Cart">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                                <path d="M0.75 2.20325C0.75 1.8136 1.05913 1.50012 1.44338 1.50012H2.75791C3.39351 1.50012 3.95688 1.87512 4.21979 2.43762H16.0939C16.8537 2.43762 17.4085 3.17004 17.2091 3.91418L16.0246 8.3761C15.779 9.29602 14.9556 9.93762 14.0167 9.93762H5.68166L5.83768 10.7726C5.90124 11.1036 6.18725 11.3439 6.5195 11.3439H14.8487C15.233 11.3439 15.5421 11.6573 15.5421 12.047C15.5421 12.4366 15.233 12.7501 14.8487 12.7501H6.5195C5.51988 12.7501 4.66182 12.0294 4.47692 11.0363L2.98615 3.0968C2.96593 2.98547 2.87059 2.90637 2.75791 2.90637H1.44338C1.05913 2.90637 0.75 2.5929 0.75 2.20325ZM4.44803 15.0939C4.44803 14.9092 4.4839 14.7263 4.55359 14.5557C4.62328 14.3851 4.72543 14.2301 4.8542 14.0995C4.98297 13.9689 5.13585 13.8653 5.3041 13.7947C5.47235 13.724 5.65267 13.6876 5.83479 13.6876C6.0169 13.6876 6.19723 13.724 6.36548 13.7947C6.53373 13.8653 6.6866 13.9689 6.81537 14.0995C6.94415 14.2301 7.04629 14.3851 7.11598 14.5557C7.18568 14.7263 7.22155 14.9092 7.22155 15.0939C7.22155 15.2785 7.18568 15.4614 7.11598 15.632C7.04629 15.8026 6.94415 15.9577 6.81537 16.0882C6.6866 16.2188 6.53373 16.3224 6.36548 16.3931C6.19723 16.4637 6.0169 16.5001 5.83479 16.5001C5.65267 16.5001 5.47235 16.4637 5.3041 16.3931C5.13585 16.3224 4.98297 16.2188 4.8542 16.0882C4.72543 15.9577 4.62328 15.8026 4.55359 15.632C4.4839 15.4614 4.44803 15.2785 4.44803 15.0939ZM14.1553 13.6876C14.5231 13.6876 14.8759 13.8358 15.1359 14.0995C15.396 14.3632 15.5421 14.7209 15.5421 15.0939C15.5421 15.4668 15.396 15.8245 15.1359 16.0882C14.8759 16.352 14.5231 16.5001 14.1553 16.5001C13.7876 16.5001 13.4348 16.352 13.1748 16.0882C12.9147 15.8245 12.7686 15.4668 12.7686 15.0939C12.7686 14.7209 12.9147 14.3632 13.1748 14.0995C13.4348 13.8358 13.7876 13.6876 14.1553 13.6876Z" fill="#D4D6E2"/>
                            </svg>
                            <?php $cart_count = WC()->cart->get_cart_contents_count(); ?>
                            <span class="header__cart-mobile-count<?= $cart_count > 0 ? '' : ' header__cart-mobile-count--empty' ?>"><?= (int) $cart_count ?></span>
                        </a>
                    <?php endif; ?>
                    <button type="button" aria-label="Toggle mobile menu" class="burger" id="mobile-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
            <div class="line-gradient"></div>
        </div>
        <div class="mobile-menu" id="mobile-menu">
            <nav class="mobile-menu__nav">
                <?php wp_nav_menu([
                    'theme_location' => 'header-menu',
                    'container' => false,
                    'menu_class' => 'mobile-menu__list',
                    'fallback_cb' => function () {
                        echo '<ul class="mobile-menu__list">';
                        echo '<li><a href="' . home_url() . '">Home</a></li>';
                        echo '<li><a href="' . home_url('/about') . '">About</a></li>';
                        echo '<li><a href="' . home_url('/contact') . '">Contact</a></li>';
                        echo '</ul>';
                    },
                ]); ?>
            </nav>
            <div class="mobile-menu__inner">
                <?php if ($link_1): ?>
                    <a class="btn phone open-popup-chat open-popup-chat--header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M14.0939 11.6875C13.5977 11.1875 12.3958 10.4578 11.8127 10.1638C11.0533 9.78125 10.9908 9.75 10.3939 10.1934C9.9958 10.4894 9.73112 10.7538 9.26518 10.6544C8.79924 10.555 7.78674 9.99469 6.90018 9.11094C6.01362 8.22719 5.4208 7.18531 5.32112 6.72094C5.22143 6.25656 5.49018 5.995 5.7833 5.59594C6.19643 5.03344 6.16518 4.93969 5.81205 4.18031C5.53674 3.58969 4.7858 2.39906 4.28393 1.90531C3.74705 1.375 3.74705 1.46875 3.40112 1.6125C3.11947 1.73097 2.84928 1.87501 2.59393 2.04281C2.09393 2.375 1.81643 2.65094 1.62237 3.06563C1.4283 3.48031 1.34112 4.4525 2.3433 6.27313C3.34549 8.09375 4.04862 9.02469 5.50393 10.4759C6.95924 11.9272 8.0783 12.7075 9.71424 13.625C11.738 14.7584 12.5142 14.5375 12.9302 14.3438C13.3461 14.15 13.6233 13.875 13.9561 13.375C14.1243 13.1201 14.2687 12.8502 14.3874 12.5688C14.5314 12.2241 14.6252 12.2241 14.0939 11.6875Z" fill="white" />
                        </svg>
                        <?= esc_html($link_1['title']) ?>
                    </a>
                <?php endif; ?>
                <?php if ($link_2): ?>
                    <a class="btn btn--light" href="<?= esc_url($link_2['url']) ?>" target="<?= esc_attr($link_2['target'] ? $link_2['target'] : '_self') ?>"><?= esc_html($link_2['title']) ?></a>
                <?php endif; ?>
            </div>
        </div>
    </header>