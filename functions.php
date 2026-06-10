<?php

if (!defined('ABSPATH')) {
  exit();
}

define('THEME_VERSION', '1.0.0');
define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());

require_once THEME_PATH . '/includes/helpers.php';
require_once THEME_PATH . '/includes/ajax/support-ajax.php';
require_once THEME_PATH . '/includes/classes/theme-setup.php';
require_once THEME_PATH . '/includes/classes/assets-manager.php';
require_once THEME_PATH . '/includes/classes/ajax-controller.php';
require_once THEME_PATH . '/includes/classes/acf-settings.php';
require_once THEME_PATH . '/includes/classes/security-enhancer.php';
require_once THEME_PATH . '/includes/classes/seo-enhancer.php';
require_once THEME_PATH . '/includes/yoast-settings.php';
require_once THEME_PATH . '/includes/shop-settings.php';
require_once THEME_PATH . '/includes/yith-wapo-support.php';

add_filter(
  'is_email',
  function ($is_email, $email, $context) {
    if (strpos($email, '@localhost') !== false) {
      return $email;
    }
    return $is_email;
  },
  1,
  3
);

add_filter(
  'woocommerce_email_is_valid',
  function ($is_valid, $email) {
    if (strpos($email, '@localhost') !== false) {
      return true;
    }
    return $is_valid;
  },
  1,
  2
);

if (class_exists('WooCommerce')) {
  include_once THEME_PATH . '/includes/ajax/shop-ajax.php';
}

if (is_admin()) {
  require_once THEME_PATH . '/includes/admin/product-import-admin.php';
}

new ThemeSetup();
new AssetsManager();
new AjaxController();
new ACFSettings();
new SecurityEnhancer();
new SEOEnhancer();
