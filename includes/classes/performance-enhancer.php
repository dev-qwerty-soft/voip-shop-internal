<?php

if (!defined('ABSPATH')) {
  exit();
}

class PerformanceEnhancer {
  public function __construct() {
    add_action('wp_head', [$this, 'add_critical_css']);
    add_filter('the_content', [$this, 'add_lazy_loading']);
    add_action('wp_footer', [$this, 'add_preload_resources']);
  }

  public function add_critical_css() {
    $critical_css = "
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif}
        .header{background:#fff;border-bottom:1px solid #eee;position:sticky;top:0;z-index:100}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}
        ";

    echo "<style id='critical-css'>{$critical_css}</style>\n";
  }

  public function add_lazy_loading($content) {
    return preg_replace_callback(
      '/<img([^>]+)>/i',
      function ($matches) {
        $img = $matches[0];

        if (strpos($img, 'loading=') === false) {
          $img = str_replace('<img', '<img loading="lazy"', $img);
        }

        return $img;
      },
      $content
    );
  }

  public function add_preload_resources() {
    $preload_fonts = ['/assets/fonts/web/Inter-Regular.woff2', '/assets/fonts/web/Inter-Bold.woff2'];

    foreach ($preload_fonts as $font) {
      if (file_exists(THEME_PATH . $font)) {
        echo "<link rel='preload' href='" . THEME_URL . $font . "' as='font' type='font/woff2' crossorigin>\n";
      }
    }
  }
}
