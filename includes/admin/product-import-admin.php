<?php

/**
 * Admin Page for Product Import
 */

class Product_Import_Admin {
  private $scraper;
  private $importer;

  public function __construct() {
    add_action('admin_menu', [$this, 'add_admin_menu']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    add_action('wp_ajax_scrape_products', [$this, 'ajax_scrape_products']);
    add_action('wp_ajax_import_products', [$this, 'ajax_import_products']);
    add_action('wp_ajax_test_single_product', [$this, 'ajax_test_single_product']);
    add_action('wp_ajax_debug_html', [$this, 'ajax_debug_html']);
    add_action('wp_ajax_parse_manual_urls', [$this, 'ajax_parse_manual_urls']);
    add_action('wp_ajax_scrape_categories', [$this, 'ajax_scrape_categories']);
    add_action('wp_ajax_import_categories', [$this, 'ajax_import_categories']);
    add_action('wp_ajax_scrape_products_by_categories', [$this, 'ajax_scrape_products_by_categories']);
    add_action('wp_ajax_test_category_products', [$this, 'ajax_test_category_products']);
    add_action('wp_ajax_debug_category_html', [$this, 'ajax_debug_category_html']);

    require_once get_template_directory() . '/includes/classes/product-scraper.php';
    require_once get_template_directory() . '/includes/classes/product-importer.php';

    $this->scraper = new Product_Scraper();
    $this->importer = new Product_Importer();
  }

  /**
   * Add admin menu
   */
  public function add_admin_menu() {
    add_menu_page(
      'Product Import', // Page title
      'Product Import', // Menu title
      'manage_options', // Capability
      'product-import', // Menu slug
      [$this, 'render_admin_page'], // Callback
      'dashicons-download', // Icon
      56 // Position
    );
  }

  /**
   * Connecting scripts and styles
   */
  public function enqueue_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_product-import') {
      return;
    }

    wp_enqueue_style('product-import-admin', get_template_directory_uri() . '/includes/admin/product-import-admin.css', [], '1.0.0');

    wp_enqueue_script('product-import-admin', get_template_directory_uri() . '/includes/admin/product-import-admin.js', ['jquery'], '1.0.0', true);

    wp_localize_script('product-import-admin', 'productImportData', [
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('product_import_nonce'),
    ]);
  }

  /**
   * AJAX: Scrape products
   */
  public function ajax_scrape_products() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : 'https://voipx3.com/webstore';

    $products = $this->scraper->scrape_all_products($url);

    if (isset($products['error'])) {
      wp_send_json_error($products);
    }

    // We store the results in a transient (temporary)
    set_transient('scraped_products_' . get_current_user_id(), $products, HOUR_IN_SECONDS);

    wp_send_json_success([
      'products' => $products,
      'count' => count($products),
    ]);
  }

  /**
   * AJAX: Import products
   */
  public function ajax_import_products() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    // Get saved products
    $products = get_transient('scraped_products_' . get_current_user_id());

    if (!$products) {
      wp_send_json_error(['message' => 'You need to scrape products first']);
    }

    $results = $this->importer->import_multiple_products($products);

    // Delete transient
    delete_transient('scraped_products_' . get_current_user_id());

    wp_send_json_success($results);
  }

  /**
   * AJAX: Test single product
   */
  public function ajax_test_single_product() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';

    if (empty($url)) {
      wp_send_json_error(['message' => 'URL not specified']);
    }

    $product = $this->scraper->scrape_single_product($url);

    if (isset($product['error'])) {
      wp_send_json_error($product);
    }

    wp_send_json_success($product);
  }

  /**
   * AJAX: Debug HTML
   */
  public function ajax_debug_html() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';

    if (empty($url)) {
      wp_send_json_error(['message' => 'URL not specified']);
    }

    $result = $this->scraper->debug_html($url);

    if ($result['success']) {
      wp_send_json_success($result);
    } else {
      wp_send_json_error(['message' => 'Error loading HTML']);
    }
  }

  /**
   * AJAX: Parse products from manually specified URLs
   */
  public function ajax_parse_manual_urls() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $urls = isset($_POST['urls']) ? $_POST['urls'] : '';

    if (empty($urls)) {
      wp_send_json_error(['message' => 'URL not specified']);
    }

    // Break it down into lines and clean it up
    $url_list = array_filter(array_map('trim', explode("\n", $urls)));

    if (empty($url_list)) {
      wp_send_json_error(['message' => 'No URLs found']);
    }

    $products = [];

    foreach ($url_list as $url) {
      // Skipping blank lines
      if (empty($url)) {
        continue;
      }

      // We parse each product
      $product = $this->scraper->scrape_single_product($url);

      if (!isset($product['error']) && !empty($product['title'])) {
        $products[] = $product;
      }

      // Slight delay
      usleep(300000); // 0.3 sec
    }

    // We store the results in a transient
    set_transient('scraped_products_' . get_current_user_id(), $products, HOUR_IN_SECONDS);

    wp_send_json_success([
      'products' => $products,
      'count' => count($products),
    ]);
  }

  /**
   * AJAX: Scrape categories
   */
  public function ajax_scrape_categories() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $categories = $this->scraper->scrape_all_categories();

    if (isset($categories['error'])) {
      wp_send_json_error($categories);
    }

    // We store the results in a transient
    set_transient('scraped_categories_' . get_current_user_id(), $categories, HOUR_IN_SECONDS);

    wp_send_json_success([
      'categories' => $categories,
      'count' => count($categories),
    ]);
  }

  /**
   * AJAX: Import categories
   */
  public function ajax_import_categories() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    // Get saved categories
    $categories = get_transient('scraped_categories_' . get_current_user_id());

    if (!$categories) {
      wp_send_json_error(['message' => 'You need to scrape categories first']);
    }

    $results = $this->importer->import_multiple_categories($categories);

    // Removing the transient
    delete_transient('scraped_categories_' . get_current_user_id());

    wp_send_json_success($results);
  }

  /**
   * AJAX: Scrape products by categories (with category binding)
   */
  public function ajax_scrape_products_by_categories() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $products = $this->scraper->scrape_products_by_categories();

    if (isset($products['error'])) {
      wp_send_json_error($products);
    }

    // We store the results in a transient
    set_transient('scraped_products_' . get_current_user_id(), $products, HOUR_IN_SECONDS);

    wp_send_json_success([
      'products' => $products,
      'count' => count($products),
    ]);
  }

  /**
   * AJAX: Test category products parsing
   */
  public function ajax_test_category_products() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : 'https://voipx3.com/webstore/ols/categories/headsets';

    // First, let's try the old method
    $products = $this->scraper->scrape_products_from_category($url);

    // We also extract product names
    $product_names = $this->scraper->extract_product_names_from_category($url);

    wp_send_json_success([
      'products' => $products,
      'product_names' => $product_names,
      'count' => count($products),
      'names_count' => count($product_names),
      'category_url' => $url,
    ]);
  }

  /**
   * AJAX: Debug category HTML
   */
  public function ajax_debug_category_html() {
    check_ajax_referer('product_import_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : 'https://voipx3.com/webstore/ols/categories/headsets';

    $result = $this->scraper->debug_category_html($url);

    if ($result['success']) {
      wp_send_json_success($result);
    } else {
      wp_send_json_error(['message' => 'Error loading category HTML']);
    }
  }

  /**
   * Render admin page
   */
  public function render_admin_page() {
    ?>
        <div class="wrap product-import-wrap">
            <h1>Import Products from VoIPX3.com</h1>

            <div class="product-import-container">

                <!-- Categories Section -->
                <div class="import-section">
                    <h2>Scrape & Import Categories</h2>
                    <p>Parse product categories from sitemap and import them to WooCommerce:</p>

                    <div class="import-form">
                        <button type="button" id="scrape-categories" class="button button-primary">
                            Scrape Categories
                        </button>
                    </div>

                    <div id="categories-progress" class="import-progress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                        <p class="progress-text">Scraping categories...</p>
                    </div>

                    <div id="scraped-categories" class="import-result" style="display: none;">
                        <h3>Categories Found: <span id="categories-count">0</span></h3>
                        <div id="categories-list"></div>
                        <button type="button" id="import-categories" class="button button-primary button-large">
                            Import All Categories to WooCommerce
                        </button>
                    </div>
                </div>

                <hr>

                <!-- Single Product Test Section -->
                <div class="import-section">
                    <h2>Test Single Product</h2>
                    <p>Enter the URL of a specific product to test parsing:</p>

                    <div class="import-form">
                        <input
                            type="text"
                            id="test-product-url"
                            class="regular-text"
                            placeholder="https://voipx3.com/webstore/ols/products/bh72-headset-bluetooth"
                            value="https://voipx3.com/webstore/ols/products/bh72-headset-bluetooth">
                        <button type="button" id="test-single-product" class="button button-secondary">
                            Test Product
                        </button>
                        <button type="button" id="debug-html" class="button button-secondary">
                            Debug HTML
                        </button>
                    </div>

                    <div id="test-result" class="import-result" style="display: none;">
                        <h3>Parsing Result:</h3>
                        <pre id="test-result-content"></pre>
                    </div>

                    <hr style="margin: 20px 0;">

                    <h3>Test Category Products</h3>
                    <p>Test parsing products from a category:</p>
                    <div class="import-form">
                        <input
                            type="text"
                            id="test-category-url"
                            class="regular-text"
                            placeholder="https://voipx3.com/webstore/ols/categories/headsets"
                            value="https://voipx3.com/webstore/ols/categories/headsets">
                        <button type="button" id="test-category-products" class="button button-secondary">
                            Test Category
                        </button>
                        <button type="button" id="debug-category-html" class="button button-secondary">
                            Debug Category HTML
                        </button>
                    </div>

                    <div id="category-test-result" class="import-result" style="display: none;">
                        <h3>Category Products Found:</h3>
                        <pre id="category-test-content"></pre>
                    </div>
                </div>

                <hr>

                <!-- All Products Scraping Section -->
                <div class="import-section">
                    <h2>Scrape All Products</h2>

                    <h3>Option 1: Parse by Categories (RECOMMENDED - with automatic category assignment)</h3>
                    <p>Parse all products from all categories and automatically assign them to correct categories:</p>
                    <div class="import-form">
                        <button type="button" id="scrape-by-categories" class="button button-primary button-large">
                            Scrape Products by Categories (with category assignment)
                        </button>
                    </div>

                    <hr style="margin: 20px 0;">

                    <h3>Option 2: Automatic Scraping from Sitemap</h3>
                    <p>Scrape products from sitemap (category assignment may not work):</p>

                    <div class="import-form">
                        <input
                            type="text"
                            id="shop-url"
                            class="regular-text"
                            placeholder="https://voipx3.com/webstore"
                            value="https://voipx3.com/webstore">
                        <button type="button" id="scrape-products" class="button button-secondary">
                            Scrape Products from Sitemap
                        </button>
                    </div>

                    <hr style="margin: 20px 0;">

                    <h3>Option 3: Manually Insert Product URLs</h3>
                    <p>Paste product URLs (one per line):</p>
                    <textarea id="manual-urls" rows="10" class="large-text" style="width:100%; font-family: monospace;" placeholder="https://voipx3.com/webstore/ols/products/bh72-headset-bluetooth
https://voipx3.com/webstore/ols/products/another-product
..."></textarea>
                    <button type="button" id="parse-manual-urls" class="button button-primary">
                        Parse Selected Products
                    </button>

                    <div id="scrape-progress" class="import-progress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                        <p class="progress-text">Scraping products...</p>
                    </div>

                    <div id="scraped-products" class="import-result" style="display: none;">
                        <h3>Products Found: <span id="products-count">0</span></h3>
                        <div id="products-list"></div>
                        <button type="button" id="import-products" class="button button-primary button-large">
                            Import All Products to WooCommerce
                        </button>
                    </div>
                </div>

                <hr>

                <!-- Import Results Section -->
                <div class="import-section">
                    <div id="import-results" class="import-result" style="display: none;">
                        <h2>Import Results</h2>
                        <div id="import-summary"></div>
                        <div id="import-details"></div>
                    </div>
                </div>

            </div>
        </div>
<?php
  }
}

// class init
new Product_Import_Admin();
