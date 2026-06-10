<?php

/**
 * Empty cart page
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit();

do_action('woocommerce_before_cart');
get_template_part('template-blocks/breadcrumbs');
?>

<section class="custom-cart custom-cart--empty">
    <div class="container">
        <h1 class="custom-cart__title">Cart</h1>

        <div class="custom-cart__empty-state">
            <div class="custom-cart__empty-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.3135 31.062C24.6927 31.062 24.1895 30.5587 24.1895 29.938C24.1895 29.3172 24.6927 28.8139 25.3135 28.8139C25.9343 28.8139 26.4375 29.3172 26.4375 29.938C26.4375 30.5587 25.9343 31.062 25.3135 31.062Z" fill="#FF5521" stroke="#FF5521" stroke-width="1.48235" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.57912 31.0619C8.95836 31.0619 8.45512 30.5587 8.45512 29.9379C8.45512 29.3171 8.95836 28.8139 9.57912 28.8139C10.1999 28.8139 10.7031 29.3171 10.7031 29.9379C10.7031 30.5587 10.1999 31.0619 9.57912 31.0619Z" fill="#FF5521" stroke="#FF5521" stroke-width="1.48235" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M34.3047 6.33333H29.8087L26.4367 25.4419H8.45267" stroke="#FF5521" stroke-width="1.48235" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M26.4395 20.9457H8.91633C8.78635 20.9458 8.66036 20.9009 8.55981 20.8185C8.45927 20.7361 8.39037 20.6215 8.36486 20.494L6.34166 10.3777C6.32534 10.2962 6.32732 10.212 6.34748 10.1313C6.36763 10.0506 6.40546 9.97533 6.45822 9.91101C6.51097 9.84669 6.57735 9.79489 6.65256 9.75934C6.72777 9.72378 6.80994 9.70537 6.89313 9.70543H28.6875" fill="#FF5521" />
                    <path d="M26.4395 20.9457H8.91633C8.78635 20.9458 8.66036 20.9009 8.55981 20.8185C8.45927 20.7361 8.39037 20.6215 8.36486 20.494L6.34166 10.3777C6.32534 10.2962 6.32732 10.212 6.34748 10.1313C6.36763 10.0506 6.40546 9.97533 6.45822 9.91101C6.51097 9.84669 6.57735 9.79489 6.65256 9.75934C6.72777 9.72378 6.80994 9.70537 6.89313 9.70543H28.6875" stroke="#FF5521" stroke-width="1.48235" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <h2 class="custom-cart__empty-title">Your cart is empty</h2>
            <p class="custom-cart__empty-text">Looks like you haven't made your choice yet.</p>

            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn custom-cart__empty-button">
                Back to Shop page
            </a>
        </div>
    </div>
</section>

<?php do_action('woocommerce_after_cart'); ?>
