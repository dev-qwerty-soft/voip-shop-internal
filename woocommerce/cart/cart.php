<?php
defined('ABSPATH') || exit();
do_action('woocommerce_before_cart');
get_template_part('template-blocks/breadcrumbs');
?>

<section class="custom-cart">
    <div class="container">
        <h1 class="custom-cart__title">Cart</h1>
        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
            <?php do_action('woocommerce_before_cart_table'); ?>
            <div class="custom-cart__content">
                <div class="custom-cart__items">
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                      $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                      $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                      if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key); ?>
                            <div class="custom-cart__item custom-cart__item--desktop <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>" data-cart-item-key="<?php echo esc_attr(
  $cart_item_key
); ?>">

                                <div class="custom-cart__item-image">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                    if (!$product_permalink) {
                                      echo $thumbnail;
                                    } else {
                                      printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                    }
                                    ?>
                                </div>

                                <div class="custom-cart__item-details">
                                    <div class="custom-cart__item-info">
                                        <?php
                                        if (!$product_permalink) {
                                          echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                          echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="custom-cart__item-name">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }

                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                        // Meta data (for non-YITH products)
                                        echo wc_get_formatted_cart_item_data($cart_item);

                                        // Backorder notification
                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                          echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                        }
                                        ?>

                                        <div class="custom-cart__item-price">
                                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                        </div>
                                    </div>

                                    <div class="custom-cart__item-actions">
                                        <?php echo apply_filters(
                                          'woocommerce_cart_item_remove_link',
                                          sprintf(
                                            '<a href="%s" class="custom-cart__item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>',
                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                            esc_attr__('Remove this item', 'woocommerce'),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                          ),
                                          $cart_item_key
                                        ); ?>

                                        <div class="custom-cart__item-quantity">
                                            <?php
                                            if ($_product->is_sold_individually()) {
                                              $min_quantity = 1;
                                              $max_quantity = 1;
                                            } else {
                                              $min_quantity = 0;
                                              $max_quantity = $_product->get_max_purchase_quantity();
                                            }
                                            $product_quantity_max = $max_quantity < 0 ? '' : $max_quantity;
                                            ?>
                                            <div class="quantity">
                                                <button type="button" class="quantity-btn quantity-minus" aria-label="<?php esc_attr_e('Decrease quantity', 'woocommerce'); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M3.07812 8L12.9243 8" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>

                                                <input
                                                    type="number"
                                                    name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
                                                    value="<?php echo esc_attr($cart_item['quantity']); ?>"
                                                    class="input-text qty text"
                                                    step="1"
                                                    min="<?php echo esc_attr($min_quantity); ?>"
                                                    <?php echo $product_quantity_max !== '' ? 'max="' . esc_attr($product_quantity_max) . '"' : ''; ?>
                                                    inputmode="numeric"
                                                    autocomplete="off"
                                                    aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>" />

                                                <button type="button" class="quantity-btn quantity-plus" aria-label="<?php esc_attr_e('Increase quantity', 'woocommerce'); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.0012 12.9231L8.0012 8M8.0012 8L8.0012 3.07692M8.0012 8L12.9243 8M8.0012 8L3.07813 8" stroke="#807DFE" stroke-width="2" stroke-linecap="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-cart__item custom-cart__item--mobile <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>" data-cart-item-key="<?php echo esc_attr(
  $cart_item_key
); ?>">

                                <div class="custom-cart__item-header">
                                    <div class="custom-cart__item-name">
                                        <?php
                                        if (!$product_permalink) {
                                          echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                        } else {
                                          echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }

                                        // Meta data and variations for mobile
                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                        echo wc_get_formatted_cart_item_data($cart_item);
                                        ?>
                                    </div>

                                    <?php echo apply_filters(
                                      'woocommerce_cart_item_remove_link',
                                      sprintf(
                                        '<a href="%s" class="custom-cart__item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>',
                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                        esc_attr__('Remove this item', 'woocommerce'),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku())
                                      ),
                                      $cart_item_key
                                    ); ?>
                                </div>

                                <div class="custom-cart__item-content">
                                    <div class="custom-cart__item-image">
                                        <?php if (!$product_permalink) {
                                          echo $thumbnail;
                                        } else {
                                          printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                        } ?>
                                    </div>

                                    <div class="custom-cart__item-quantity">
                                        <div class="quantity">
                                            <button type="button" class="quantity-btn quantity-minus" aria-label="<?php esc_attr_e('Decrease quantity', 'woocommerce'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3.07812 8L12.9243 8" stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>

                                            <input
                                                type="number"
                                                name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
                                                value="<?php echo esc_attr($cart_item['quantity']); ?>"
                                                class="input-text qty text"
                                                step="1"
                                                min="<?php echo esc_attr($min_quantity); ?>"
                                                <?php echo $product_quantity_max !== '' ? 'max="' . esc_attr($product_quantity_max) . '"' : ''; ?>
                                                inputmode="numeric"
                                                autocomplete="off"
                                                aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>" />

                                            <button type="button" class="quantity-btn quantity-plus" aria-label="<?php esc_attr_e('Increase quantity', 'woocommerce'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M8.0012 12.9231L8.0012 8M8.0012 8L8.0012 3.07692M8.0012 8L12.9243 8M8.0012 8L3.07813 8" stroke="#807DFE" stroke-width="2" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="custom-cart__item-price">
                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                      }
                    } ?>
                </div>

                <div class="custom-cart__sidebar">
                    <div class="custom-cart__totals">
                        <h3 class="custom-cart__totals-title">Cart totals</h3>

                        <?php if (wc_coupons_enabled()) { ?>
                            <div class="custom-cart__coupon">
                                <input type="text" name="coupon_code" class="custom-cart__coupon-input" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
                                <button type="submit" class="custom-cart__coupon-button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                                    <?php esc_html_e('Apply', 'woocommerce'); ?>
                                </button>
                            </div>
                        <?php } ?>

                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>
                            <div class="custom-cart__shipping">
                                <div class="custom-cart__shipping-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M13 0C13.5304 0 14.0391 0.210714 14.4142 0.585786C14.7893 0.960859 15 1.46957 15 2V3H16.52C16.8198 3.00004 17.1157 3.06746 17.3859 3.19728C17.6561 3.3271 17.8936 3.51599 18.081 3.75L19.561 5.601C19.8451 5.95569 19.9999 6.39656 20 6.851V11C20 11.5304 19.7893 12.0391 19.4142 12.4142C19.0391 12.7893 18.5304 13 18 13H17C17 13.7956 16.6839 14.5587 16.1213 15.1213C15.5587 15.6839 14.7956 16 14 16C13.2044 16 12.4413 15.6839 11.8787 15.1213C11.3161 14.5587 11 13.7956 11 13H8C8 13.394 7.9224 13.7841 7.77164 14.1481C7.62087 14.512 7.3999 14.8427 7.12132 15.1213C6.84274 15.3999 6.51203 15.6209 6.14805 15.7716C5.78407 15.9224 5.39397 16 5 16C4.60603 16 4.21593 15.9224 3.85195 15.7716C3.48797 15.6209 3.15726 15.3999 2.87868 15.1213C2.6001 14.8427 2.37913 14.512 2.22836 14.1481C2.0776 13.7841 2 13.394 2 13C1.46957 13 0.960859 12.7893 0.585786 12.4142C0.210714 12.0391 0 11.5304 0 11V2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0H13ZM5 12C4.73478 12 4.48043 12.1054 4.29289 12.2929C4.10536 12.4804 4 12.7348 4 13C4 13.2652 4.10536 13.5196 4.29289 13.7071C4.48043 13.8946 4.73478 14 5 14C5.26522 14 5.51957 13.8946 5.70711 13.7071C5.89464 13.5196 6 13.2652 6 13C6 12.7348 5.89464 12.4804 5.70711 12.2929C5.51957 12.1054 5.26522 12 5 12ZM14 12C13.7348 12 13.4804 12.1054 13.2929 12.2929C13.1054 12.4804 13 12.7348 13 13C13 13.2652 13.1054 13.5196 13.2929 13.7071C13.4804 13.8946 13.7348 14 14 14C14.2652 14 14.5196 13.8946 14.7071 13.7071C14.8946 13.5196 15 13.2652 15 13C15 12.7348 14.8946 12.4804 14.7071 12.2929C14.5196 12.1054 14.2652 12 14 12ZM16.52 5H15V9H18V6.85L16.52 5Z" fill="#2CFF56" />
                                    </svg>
                                </div>
                                <span class="custom-cart__shipping-text">
                                    Free shipping if order is $250 or more
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="custom-cart__totals-table">
                            <div class="custom-cart__totals-row">
                                <span class="custom-cart__totals-label"><?php esc_html_e('Subtotal', 'woocommerce'); ?></span>
                                <span class="custom-cart__totals-value custom-cart__subtotal-value"><?php wc_cart_totals_subtotal_html(); ?></span>
                            </div>
                            <?php if (WC()->cart->needs_shipping()):
                                $shipping_label = esc_html__('Shipping', 'woocommerce');
                                $chosen_methods = WC()->session ? WC()->session->get('chosen_shipping_methods') : [];
                                if (!empty($chosen_methods)) {
                                    $packages = WC()->shipping()->get_packages();
                                    if (!empty($packages)) {
                                        $rates = current($packages)['rates'] ?? [];
                                        $chosen_id = current($chosen_methods);
                                        if (isset($rates[$chosen_id])) {
                                            $shipping_label = $rates[$chosen_id]->get_label();
                                        }
                                    }
                                }
                            ?>
                            <div class="custom-cart__totals-row">
                                <span class="custom-cart__totals-label custom-cart__shipping-label"><?php echo esc_html($shipping_label); ?></span>
                                <span class="custom-cart__totals-value custom-cart__shipping-value">
                                    <?php if (WC()->cart->show_shipping()):
                                        $shipping_total = WC()->cart->get_shipping_total();
                                        echo $shipping_total > 0 ? wc_price($shipping_total) : '<strong>' . esc_html__('Free', 'woocommerce') . '</strong>';
                                    else: ?>
                                        <em><?php esc_html_e('Calculated at checkout', 'woocommerce'); ?></em>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            <div class="custom-cart__totals-row custom-cart__totals-row--total">
                                <span class="custom-cart__totals-label"><?php esc_html_e('Estimated Total', 'woocommerce'); ?></span>
                                <span class="custom-cart__totals-value custom-cart__total-value"><?php wc_cart_totals_order_total_html(); ?></span>
                            </div>
                        </div>

                        <div class="custom-cart__actions">
                            <button type="submit" class="custom-cart__update-button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" disabled>
                                <?php esc_html_e('Update cart', 'woocommerce'); ?>
                            </button>

                            <?php do_action('woocommerce_proceed_to_checkout'); ?>
                        </div>

                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    </div>
                </div>
            </div>

            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <?php do_action('woocommerce_after_cart'); ?>
    </div>
</section>