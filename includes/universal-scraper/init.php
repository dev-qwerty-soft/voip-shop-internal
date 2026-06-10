<?php

/**
 * Universal Site Scraper - Init File
 *
 * INSTALLATION INSTRUCTIONS FOR NEW THEME:
 * 1. Copy universal-scraper folder to includes/ directory in your new theme
 * 2. In functions.php add: require_once get_template_directory() . '/includes/universal-scraper/init.php';
 * 3. Done! Admin page will appear in WordPress Admin menu
 *
 * @package Universal_Site_Scraper
 * @version 1.0.0
 */

// Check file is called from WordPress
if (!defined('ABSPATH')) {
  exit();
}

// Include main classes
require_once __DIR__ . '/site-scraper.php';
require_once __DIR__ . '/content-importer.php';

// Include admin page
if (is_admin()) {
  require_once __DIR__ . '/admin-page.php';
  new Scraper_Admin_Page();
}

// Initialization
add_action('admin_init', function () {
  // Additional initialization can be added here
});
