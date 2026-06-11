<?php

if (!defined('ABSPATH')) {
  exit();
}

class ThemeSetup
{
  public function __construct()
  {
    add_action('after_setup_theme', [$this, 'setup']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    add_action('init', [$this, 'init']);
    $setup_files = ['/includes/setup/menus.php', '/includes/setup/theme-support.php', '/includes/setup/clean-head.php', '/includes/setup/disable-comments.php'];
    foreach ($setup_files as $file) {
      if (file_exists(THEME_PATH . $file)) {
        require_once THEME_PATH . $file;
      }
    }
    add_filter(
      'script_loader_tag',
      function ($tag, $handle) {
        if ('theme-scripts' === $handle) {
          return str_replace(' src', ' defer src', $tag);
        }
        return $tag;
      },
      10,
      2
    );
    add_action('login_head', 'add_icon');
    add_action('admin_head', 'add_icon');
    add_action('wp_head', 'add_icon');
    add_action(
      'login_enqueue_scripts',
      function () {
        wp_enqueue_script('login-js-theme', getUrl('dist/js/login.min.js'), [], false, true);
        wp_enqueue_style('login-css-theme', getUrl('dist/css/login.min.css'), false, '1.0.0');
      },
      100
    );
  }

  public function setup()
  {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_image_size('hero-image', 1920, 1080, true);
    add_image_size('card-image', 400, 300, true);
  }

  public function enqueue_assets()
  {
    $css_file = THEME_PATH . '/dist/css/client.min.css';
    $css_version = file_exists($css_file) ? filemtime($css_file) : THEME_VERSION;
    wp_enqueue_style('theme-styles', THEME_URL . '/dist/css/client.min.css', [], $css_version);
    $fonts_file = THEME_PATH . '/dist/css/fonts.min.css';
    $fonts_version = file_exists($fonts_file) ? filemtime($fonts_file) : THEME_VERSION;
    wp_enqueue_style('theme-fonts', THEME_URL . '/dist/css/fonts.min.css', [], $fonts_version);
    $js_file = THEME_PATH . '/dist/js/client.min.js';
    $js_version = file_exists($js_file) ? filemtime($js_file) : THEME_VERSION;
    wp_enqueue_script('theme-scripts', THEME_URL . '/dist/js/client.min.js', [], $js_version, true);
    wp_enqueue_style(
      'calendly-widget-css',
      'https://assets.calendly.com/assets/external/widget.css',
      [],
      null
    );

    wp_enqueue_script(
      'calendly-widget-js',
      'https://assets.calendly.com/assets/external/widget.js',
      [],
      null,
      true
    );
    wp_localize_script('theme-scripts', 'themeAjax', [
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('theme_nonce'),
      'price_range' => function_exists('voip_get_price_range') ? voip_get_price_range() : ['min' => 0, 'max' => 1000],
    ]);
  }

  public function init()
  {
    if (function_exists('acf_add_options_page')) {
      acf_add_options_page([
        'page_title' => __('General theme settings', 'custom-theme'),
        'menu_title' => __('Theme settings', 'custom-theme'),
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
      ]);
    }
  }
}
