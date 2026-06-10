<?php

/**
 * Assets Manager - handles CSS/JS optimization
 */

if (!defined('ABSPATH')) {
  exit();
}

class AssetsManager {
  public function __construct() {
    add_action('wp_enqueue_scripts', [$this, 'optimize_assets'], 100);
    add_filter('style_loader_tag', [$this, 'add_preload_css'], 10, 4);
    add_filter('script_loader_tag', [$this, 'add_defer_js'], 10, 3);
    add_action('wp_head', [$this, 'add_cache_headers']);
  }

  /**
   * Optimize assets loading
   */
  public function optimize_assets() {
    // Remove unnecessary scripts (but not on WooCommerce pages)
    if (!is_admin() && !is_cart() && !is_checkout() && !is_account_page()) {
      wp_deregister_script('wp-embed');
      wp_dequeue_style('wp-block-library');
    }

    // Critical CSS inline
    if (file_exists(THEME_PATH . '/dist/css/critical.css')) {
      echo '<style id="critical-css">';
      include THEME_PATH . '/dist/css/critical.css';
      echo '</style>';
    }
  }

  /**
   * Add preload to CSS
   */
  public function add_preload_css($html, $handle, $href, $media) {
    if (strpos($handle, 'theme-') === 0) {
      $html = str_replace('rel="stylesheet"', 'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"', $html);
      $html .= '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>';
    }
    return $html;
  }

  /**
   * Add defer to JS
   */
  public function add_defer_js($tag, $handle, $src) {
    if (strpos($handle, 'theme-') === 0) {
      return str_replace('<script ', '<script defer ', $tag);
    }
    return $tag;
  }

  /**
   * Add cache headers for better performance
   */
  public function add_cache_headers() {
    if (!is_admin() && !is_user_logged_in()) {
      echo "<meta http-equiv='Cache-Control' content='public, max-age=31536000'>\n";
      echo "<link rel='dns-prefetch' href='//fonts.googleapis.com'>\n";
    }
  }
}
