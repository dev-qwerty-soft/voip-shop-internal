<?php

add_filter('template_include', function ($template) {
  if (is_singular('product')) {
    $new_template = locate_template(['woocommerce/single-product.php']);
    if ($new_template) {
      return $new_template;
    }
  }
  return $template;
});

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

add_action('init', function () {
  remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
});

add_action(
  'woocommerce_before_main_content',
  function () {
    get_template_part('template-parts/breadcrumbs');
  },
  20
);

add_filter('woocommerce_cart_proceed_to_checkout_button_html', function ($button_html) {
  return str_replace('class="checkout-button', 'class="checkout-button btn', $button_html);
});

// Remove add-to-cart parameter from URL after adding to cart to prevent duplicate additions on page reload
add_filter('woocommerce_add_to_cart_redirect', function ($url) {
  // Remove query string to prevent re-adding on refresh
  return remove_query_arg('add-to-cart', $url);
});

// Alternative: Redirect to current page without parameters
add_action('template_redirect', function () {
  if (isset($_GET['add-to-cart'])) {
    global $wp;
    $current_url = home_url($wp->request);
    wp_safe_redirect($current_url);
    exit();
  }
});

// Clear WooCommerce notices after they are displayed to prevent them from showing on page reload
add_action(
  'wp_footer',
  function () {
    if (function_exists('wc_clear_notices')) {
      // Clear notices after displaying them
      echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                // Wait a bit to ensure notices are displayed
                setTimeout(function() {
                    fetch(window.ajaxurl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: "action=clear_wc_notices"
                    });
                }, 1000);
            });
        </script>';
    }
  },
  999
);

// AJAX handler to clear WooCommerce notices
add_action('wp_ajax_clear_wc_notices', 'clear_woocommerce_notices');
add_action('wp_ajax_nopriv_clear_wc_notices', 'clear_woocommerce_notices');

function clear_woocommerce_notices() {
  if (function_exists('wc_clear_notices')) {
    wc_clear_notices();
  }
  wp_die();
}

// Implement Post-Redirect-Get pattern for add to cart
add_action(
  'template_redirect',
  function () {
    // Check if this is a POST request with add-to-cart
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-to-cart'])) {
      // Get current URL
      $redirect_url = $_SERVER['REQUEST_URI'];

      // Remove query parameters
      $redirect_url = strtok($redirect_url, '?');

      // Redirect to prevent form resubmission
      wp_safe_redirect($redirect_url);
      exit();
    }
  },
  1
);

// AJAX handler for updating cart quantity
add_action('wp_ajax_update_cart_quantity', 'ajax_update_cart_quantity');
add_action('wp_ajax_nopriv_update_cart_quantity', 'ajax_update_cart_quantity');

function ajax_update_cart_quantity() {
  if (!isset($_POST['cart_item_key']) || !isset($_POST['quantity'])) {
    wp_send_json_error('Missing parameters');
    return;
  }

  $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
  $quantity = intval($_POST['quantity']);

  if ($quantity < 0) {
    wp_send_json_error('Invalid quantity');
    return;
  }

  // Update cart
  WC()->cart->set_quantity($cart_item_key, $quantity, true);

  // Get updated cart item
  $cart_item = WC()->cart->get_cart_item($cart_item_key);

  $response = [
    'cart_subtotal' => WC()->cart->get_cart_subtotal(),
    'cart_total' => WC()->cart->get_cart_total(),
    'cart_header_price' => number_format((float) WC()->cart->get_subtotal(), 2),
  ];

  // Get item subtotal
  if ($cart_item) {
    $product = $cart_item['data'];
    $response['item_subtotal'] = WC()->cart->get_product_subtotal($product, $quantity);
  }

  wp_send_json_success($response);
}

add_action('init', function () {
    remove_action(
        'woocommerce_after_shop_loop_item',
        'woocommerce_template_loop_add_to_cart',
        10
    );
});