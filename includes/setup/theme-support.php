<?php

add_action('after_setup_theme', function () {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
});

add_action('init', function () {
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
      'page_title' => __('General theme settings', 'acf'),
      'menu_title' => __('Theme settings', 'acf'),
      'menu_slug' => 'theme-general-settings',
      'capability' => 'edit_posts',
      'redirect' => false,
    ]);
  }
});

add_filter('woocommerce_coupons_enabled', 'disable_coupons_on_checkout');
function disable_coupons_on_checkout($enabled) {
  if (is_checkout()) {
    return false;
  }
  return $enabled;
}

add_filter('render_block', 'remove_checkout_order_note_block', 10, 2);
function remove_checkout_order_note_block($block_content, $block) {
  if (is_checkout() && !is_admin() && isset($block['blockName']) && $block['blockName'] === 'woocommerce/checkout-order-note-block') {
    return '';
  }

  return $block_content;
}

add_filter('woocommerce_package_rates', 'hide_flat_rate_when_free_shipping', 100, 2);
function hide_flat_rate_when_free_shipping($rates, $package) {
  $free = [];
  foreach ($rates as $rate_id => $rate) {
    if ('free_shipping' === $rate->method_id) {
      $free[$rate_id] = $rate;
      break;
    }
  }

  if (!empty($free)) {
    foreach ($rates as $rate_id => $rate) {
      if ('flat_rate' === $rate->method_id) {
        unset($rates[$rate_id]);
      }
    }
  }

  return $rates;
}
