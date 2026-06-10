<?php

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

add_action(
  'wp_enqueue_scripts',
  function () {
    if (is_admin() || is_cart() || is_checkout() || is_account_page() || is_product()) {
      return;
    }
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_script('wp-embed');
    wp_dequeue_script('wp-hooks');
    wp_dequeue_script('wp-i18n');
    wp_dequeue_script('wp-polyfill');
    wp_dequeue_script('wp-dom-ready');
    wp_dequeue_script('wp-a11y');
  },
  20
);

add_action(
  'wp_print_scripts',
  function () {
    if (!is_admin() && !is_cart() && !is_checkout() && !is_account_page() && !is_product()) {
      wp_deregister_script('wp-i18n');
      wp_deregister_script('wp-hooks');
    }
  },
  999
);

remove_action('wp_head', 'wp_resource_hints', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);

add_action(
  'template_redirect',
  function () {
    if (is_user_logged_in()) {
      return;
    }
    if (wp_doing_ajax() || (defined('DOING_AJAX') && DOING_AJAX)) {
      return;
    }
    if (defined('REST_REQUEST') && REST_REQUEST) {
      return;
    }
    ob_start(function ($html) {
      $patterns = [
        '/<link[^>]*id=["\']forminator-module-css-85-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']forminator-icons-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']dashicons-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']buttons-css["\'][^>]*>/is',
        '/\/\*#\s*sourceURL=.*?\*\//is',
        '/<style[^>]*>\s*<\/style>/is',
        '/<noscript><style>\.woocommerce-product-gallery\{[^}]*\}<\/style><\/noscript>/is',
        '/<style[^>]*type=["\']text\/css["\'][^>]*>\.wpgs-for.*?min-height:500px;\}<\/style>/is',
        '/<!--(.|\s)*?-->/is',
        '/\/\*\s*<!\[CDATA\[\s*\*\//is',
        '/\/\*\s*\]\]>\s*\*\//is',
        '/\/\/#\s*sourceURL=.*?$/im',
      ];
      $patterns_home = [
        '/<style[^>]*id=["\']yith_wapo_front-inline-css["\'][^>]*>.*?<\/style>/is',
        '/<style[^>]*id=["\']woocommerce-inline-inline-css["\'][^>]*>.*?<\/style>/is',
        '/<style[^>]*id=["\']woocommerce-smallscreen-css["\'][^>]*>.*?<\/style>/is',
        '/<style[^>]*id=["\']global-styles-inline-css["\'][^>]*>.*?<\/style>/is',
        '/<style[^>]*id=["\']dashicons-inline-css["\'][^>]*>.*?<\/style>/is',
        '/<style[^>]*id=["\']wp-img-auto-sizes-contain-inline-css["\'][^>]*>.*?<\/style>/is',
        '/<link[^>]*id=["\']woocommerce-layout-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']woocommerce-smallscreen-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']woocommerce-general-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']yith_wapo_front-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']yith-plugin-fw-icon-font-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']wc-blocks-style-css["\'][^>]*>/is',
        '/<link[^>]*id=["\']wc-stripe-blocks-checkout-style-css["\'][^>]*>/is',
      ];
      foreach ($patterns as $pattern) {
        $html = preg_replace($pattern, '', $html);
      }
      if (is_front_page()) {
        foreach ($patterns_home as $pattern) {
          $html = preg_replace($pattern, '', $html);
        }
      }
      $html = preg_replace('/\n\s*\n/', "\n", $html);
      $html = preg_replace('/>\s+</s', '><', $html);
      $html = preg_replace('/^\s+|\s+$/m', '', $html);
      return $html;
    });
  },
  1
);
