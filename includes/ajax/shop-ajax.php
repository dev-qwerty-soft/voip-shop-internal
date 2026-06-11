<?php

// Set to true to enable debug logging in error log
define('SHOP_AJAX_DEBUG', false);

if (!function_exists('shop_ajax_log')) {
  function shop_ajax_log($message) {
    if (SHOP_AJAX_DEBUG) {
      error_log($message);
    }
  }
}

if (!function_exists('parse_weight_related')) {
  function parse_weight_related($label)
  {
    $label = strtolower($label);
    if (preg_match('/(\d+)[\s-]*(kg|kilogram|kilograms|kilo|kilos)/', $label, $matches)) {
      return (int) $matches[1] * 1000;
    }
    if (preg_match('/(\d+)[\s-]*(gram|grams)/', $label, $matches)) {
      return (int) $matches[1];
    }
    if (preg_match('/(\d+)[\s-]*(bottle|bottles)/', $label, $matches)) {
      return (int) $matches[1];
    }
    return 999999;
  }
}

function generate_shop_pagination($current_page, $max_pages)
{
  if ($max_pages <= 1) {
    return '';
  }

  $range = 2;
  $start = max(1, $current_page - $range);
  $end = min($max_pages, $current_page + $range);

  ob_start();
?>
  <ul class="page-numbers">

    <!-- PREV -->
    <li class="shop-pagination__arrow shop-pagination__arrow--prev">
      <a href="#" class="page-number prev<?php echo $current_page <= 1 ? ' disabled' : ''; ?>"
        data-page="<?php echo max(1, $current_page - 1); ?>">
        <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.7536 7.65358L1 7.65358M1 7.65358L7.65404 0.999534M1 7.65358L7.65404 14.3076"
            stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </li>

    <!-- FIRST PAGE -->
    <?php if ($start > 1): ?>
      <li>
        <a href="#" class="page-number" data-page="1">01</a>
      </li>
      <?php if ($start > 2): ?>
        <li class="dots">...</li>
      <?php endif; ?>
    <?php endif; ?>

    <!-- MAIN RANGE -->
    <?php for ($i = $start; $i <= $end; $i++):
      $label = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
      <li>
        <?php if ($i == $current_page): ?>
          <span class="current"><?php echo $label; ?></span>
        <?php else: ?>
          <a href="#" class="page-number" data-page="<?php echo $i; ?>"><?php echo $label; ?></a>
        <?php endif; ?>
      </li>
    <?php
    endfor; ?>

    <!-- LAST PAGE -->
    <?php if ($end < $max_pages): ?>
      <?php if ($end < $max_pages - 1): ?>
        <li class="dots">...</li>
      <?php endif; ?>
      <li>
        <a href="#" class="page-number" data-page="<?php echo $max_pages; ?>">
          <?php echo str_pad($max_pages, 2, '0', STR_PAD_LEFT); ?>
        </a>
      </li>
    <?php endif; ?>

    <!-- NEXT -->
    <li class="shop-pagination__arrow shop-pagination__arrow--next">
      <a href="#" class="page-number next<?php echo $current_page >= $max_pages ? ' disabled' : ''; ?>"
        data-page="<?php echo min($max_pages, $current_page + 1); ?>">
        <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.7536 7.65358L1 7.65358M1 7.65358L7.65404 0.999534M1 7.65358L7.65404 14.3076"
            stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </li>

  </ul>
<?php return ob_get_clean();
}

add_action('wp_ajax_load_shop_categories', 'load_shop_categories');
add_action('wp_ajax_nopriv_load_shop_categories', 'load_shop_categories');

function load_shop_categories()
{
  $categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => 0,
  ]);

  $result = [];

  if (!empty($categories) && !is_wp_error($categories)) {
    foreach ($categories as $category) {
      $result[] = [
        'id' => $category->term_id,
        'slug' => $category->slug,
        'name' => $category->name,
        'count' => $category->count,
      ];
    }
  }

  wp_send_json_success($result);
}

add_action('wp_ajax_load_shop_subcategories', 'load_shop_subcategories');
add_action('wp_ajax_nopriv_load_shop_subcategories', 'load_shop_subcategories');

function load_shop_subcategories()
{
  $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;

  $subcategories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => $parent_id,
  ]);

  $result = [];

  if (!empty($subcategories) && !is_wp_error($subcategories)) {
    foreach ($subcategories as $subcategory) {
      $result[] = [
        'id' => $subcategory->term_id,
        'slug' => $subcategory->slug,
        'name' => $subcategory->name,
        'count' => $subcategory->count,
        'parent' => $subcategory->parent,
      ];
    }
  }

  wp_send_json_success($result);
}

add_action('wp_ajax_load_shop_products', 'load_shop_products');
add_action('wp_ajax_nopriv_load_shop_products', 'load_shop_products');

function get_price_range_for_tax_query($tax_query = [])
{
  global $wpdb;

  $has_tax_filter = count($tax_query) > 1;

  if (!$has_tax_filter) {
    if (function_exists('voip_get_price_range')) {
      return voip_get_price_range();
    }
    $min = (float) $wpdb->get_var("SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = '_price' AND p.post_status = 'publish' AND p.post_type = 'product' AND pm.meta_value != '' AND CAST(pm.meta_value AS DECIMAL(10,2)) > 0");
    $max = (float) $wpdb->get_var("SELECT MAX(CAST(meta_value AS DECIMAL(10,2))) FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = '_price' AND p.post_status = 'publish' AND p.post_type = 'product' AND pm.meta_value != ''");
    return ['min' => $min ? (int) floor($min) : 0, 'max' => $max ? (int) ceil($max) : 1000];
  }

  $ids_query = new WP_Query([
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'tax_query'      => $tax_query,
    'no_found_rows'  => true,
  ]);

  if (empty($ids_query->posts)) {
    return function_exists('voip_get_price_range') ? voip_get_price_range() : ['min' => 0, 'max' => 1000];
  }

  $ids = implode(',', array_map('intval', $ids_query->posts));
  $min = (float) $wpdb->get_var("SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND post_id IN ($ids) AND meta_value != '' AND CAST(meta_value AS DECIMAL(10,2)) > 0");
  $max = (float) $wpdb->get_var("SELECT MAX(CAST(meta_value AS DECIMAL(10,2))) FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND post_id IN ($ids) AND meta_value != ''");

  return [
    'min' => $min ? (int) floor($min) : 0,
    'max' => $max ? (int) ceil($max) : 1000,
  ];
}

function load_shop_products()
{
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'theme_nonce')) {
    wp_send_json_error('Invalid nonce');
  }

  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $posts_per_page = 16;
  $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'menu_order';
  $categories = isset($_POST['categories']) ? array_map('sanitize_text_field', $_POST['categories']) : [];
  $subcategories = isset($_POST['subcategories']) ? array_map('sanitize_text_field', $_POST['subcategories']) : [];

  $has_price_min = isset($_POST['priceMin']) && $_POST['priceMin'] !== '';
  $has_price_max = isset($_POST['priceMax']) && $_POST['priceMax'] !== '';
  $price_min = $has_price_min ? floatval($_POST['priceMin']) : null;
  $price_max = $has_price_max ? floatval($_POST['priceMax']) : null;

  shop_ajax_log("SHOP PRODUCTS DEBUG - Page: $page, Orderby: $orderby");
  shop_ajax_log('SHOP PRODUCTS DEBUG - Categories: ' . implode(', ', $categories));
  shop_ajax_log('SHOP PRODUCTS DEBUG - Subcategories: ' . implode(', ', $subcategories));
  shop_ajax_log('SHOP PRODUCTS DEBUG - Price range: ' . ($has_price_min ? $price_min : 'any') . ' - ' . ($has_price_max ? $price_max : 'any'));
  shop_ajax_log('SHOP PRODUCTS DEBUG - Categories empty? ' . (empty($categories) ? 'YES' : 'NO'));
  shop_ajax_log('SHOP PRODUCTS DEBUG - Subcategories empty? ' . (empty($subcategories) ? 'YES' : 'NO'));

  $args = [
    'post_type' => 'product',
    'posts_per_page' => $posts_per_page,
    'paged' => $page,
    'post_status' => 'publish',
  ];

  if ($has_price_min || $has_price_max) {
    $filter_min = $price_min ?? 0;
    $filter_max = $price_max ?? PHP_INT_MAX;
    $args['meta_query'][] = [
      'relation' => 'OR',
      [
        'key' => '_price',
        'value' => [$filter_min, $filter_max],
        'compare' => 'BETWEEN',
        'type' => 'NUMERIC',
      ],
      [
        'relation' => 'AND',
        [
          'key' => '_min_variation_price',
          'value' => $filter_max,
          'compare' => '<=',
          'type' => 'NUMERIC',
        ],
        [
          'key' => '_max_variation_price',
          'value' => $filter_min,
          'compare' => '>=',
          'type' => 'NUMERIC',
        ],
      ],
    ];
  }

  $tax_query = ['relation' => 'AND'];

  if (!empty($categories)) {
    $tax_query[] = [
      'taxonomy' => 'product_cat',
      'field' => 'slug',
      'terms' => $categories,
      'operator' => 'IN',
    ];
  }

  if (!empty($subcategories)) {
    $tax_query[] = [
      'taxonomy' => 'product_cat',
      'field' => 'slug',
      'terms' => $subcategories,
      'operator' => 'IN',
    ];
  }

  // Apply tax_query only if we have filters
  if (count($tax_query) > 1) {
    // > 1 because 'relation' is always there
    $args['tax_query'] = $tax_query;
  }

  shop_ajax_log('SHOP PRODUCTS DEBUG - Final args: ' . print_r($args, true));

  $args = applyShopOrderby($args, $orderby);

  $query = new WP_Query($args);
  $products_html = '';
  $total_products = $query->found_posts;
  $max_pages = $query->max_num_pages;

  shop_ajax_log("SHOP DEBUG - Page: $page, Total: $total_products, Max pages: $max_pages, Posts per page: $posts_per_page");
  shop_ajax_log('SHOP DEBUG - Query post count: ' . $query->post_count);
  shop_ajax_log('SHOP DEBUG - Found posts: ' . $query->found_posts);

  if ($query->have_posts()) {
    ob_start();
    while ($query->have_posts()) {
      $query->the_post();
      $product = wc_get_product(get_the_ID());

      if ($product) {
        set_query_var('product', $product);
        get_template_part('template-parts/product-card');
      }
    }
    wp_reset_postdata();
    $products_html = ob_get_clean();
  }

  wp_send_json_success([
    'products' => $products_html,
    'total' => $total_products,
    'max_pages' => $max_pages,
    'current_page' => $page,
    'pagination' => generate_shop_pagination($page, $max_pages),
    'price_range' => get_price_range_for_tax_query($tax_query),
  ]);
}

add_action('wp_ajax_get_shop_categories', 'get_shop_categories');
add_action('wp_ajax_nopriv_get_shop_categories', 'get_shop_categories');

function get_shop_categories()
{
  $categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => 0,
    'orderby' => 'count',
    'order' => 'DESC',
  ]);

  $result = [];

  if (!empty($categories) && !is_wp_error($categories)) {
    foreach ($categories as $category) {
      // Get subcategories
      $subcategories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => $category->term_id,
        'orderby' => 'name',
        'order' => 'ASC',
      ]);

      $subcategory_data = [];
      if (!empty($subcategories) && !is_wp_error($subcategories)) {
        foreach ($subcategories as $subcategory) {
          $subcategory_data[] = [
            'id' => $subcategory->term_id,
            'slug' => $subcategory->slug,
            'name' => $subcategory->name,
            'count' => $subcategory->count,
          ];
        }
      }

      $result[] = [
        'id' => $category->term_id,
        'slug' => $category->slug,
        'name' => $category->name,
        'count' => $category->count,
        'subcategories' => $subcategory_data,
      ];
    }
  }

  wp_send_json_success($result);
}

// AJAX handler for category pages
add_action('wp_ajax_filter_category_products', 'handle_category_products_ajax');
add_action('wp_ajax_nopriv_filter_category_products', 'handle_category_products_ajax');


function handle_category_products_ajax()
{
  $nonce = $_POST['nonce'] ?? '';
  if (!wp_verify_nonce($nonce, 'theme_nonce')) {
    wp_send_json_error('Invalid nonce');
  }

  $page = intval($_POST['page'] ?? 1);
  $orderby = sanitize_text_field($_POST['orderby'] ?? 'menu_order');
  $category_id = intval($_POST['category_id'] ?? 0);

  shop_ajax_log("CATEGORY AJAX DEBUG - Category ID: $category_id, Page: $page, Orderby: $orderby");
  shop_ajax_log('CATEGORY AJAX DEBUG - POST data: ' . print_r($_POST, true));

  if (!$category_id) {
    shop_ajax_log('CATEGORY AJAX ERROR - No category ID provided');
    wp_send_json_error('Invalid category ID');
  }

  // Get the current category to check if it has children
  $current_category = get_term($category_id, 'product_cat');

  if (!$current_category || is_wp_error($current_category)) {
    shop_ajax_log("CATEGORY AJAX ERROR - Category not found: $category_id");
    wp_send_json_error('Category not found');
  }

  shop_ajax_log('CATEGORY DEBUG - Category name: ' . $current_category->name);
  shop_ajax_log('CATEGORY DEBUG - Category slug: ' . $current_category->slug);
  shop_ajax_log('CATEGORY DEBUG - Category parent: ' . $current_category->parent);

  // For subcategory pages, we want ONLY products from this exact category
  // Do not include children to avoid mixing different product types
  $args = [
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $page,
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => $category_id, // Only exact category match
        'include_children' => false, // Important: exclude children
      ],
    ],
    'meta_query' => [
      [
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '=',
      ],
    ],
  ]; // Handle sorting

  $args = applyShopOrderby($args, $orderby);

  $products_query = new WP_Query($args);
  $total_pages = $products_query->max_num_pages;
  $found_posts = $products_query->found_posts;

  shop_ajax_log("CATEGORY AJAX RESULT - Category ID: $category_id");
  shop_ajax_log("CATEGORY AJAX RESULT - Found posts: $found_posts, Total pages: $total_pages");
  shop_ajax_log('CATEGORY AJAX RESULT - Query args: ' . print_r($args, true));

  ob_start();

  if ($products_query->have_posts()):
    while ($products_query->have_posts()):
      $products_query->the_post();
      $product = wc_get_product(get_the_ID());
      if ($product):
        set_query_var('product', $product);
        get_template_part('template-parts/product-card');
      endif;
    endwhile;
    wp_reset_postdata();
  endif;

  $products_html = ob_get_clean();

  $pagination_html = '';
  if ($total_pages > 1) {
    $pagination_html = generate_shop_pagination($page, $total_pages);
  }

  $result = [
    'products' => $products_html,
    'pagination' => $pagination_html,
    'found_posts' => $found_posts,
    'current_page' => $page,
    'total_pages' => $total_pages,
  ];

  wp_send_json_success($result);
}

// Adding AJAX endpoint
add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart');

function custom_add_to_cart()
{

  $nonce = $_POST['nonce'] ?? '';
  if (!wp_verify_nonce($nonce, 'theme_nonce')) {
    wp_send_json_error('Invalid nonce');
  }

  $product_id = intval($_POST['product_id']);

  if (!$product_id) {
    wp_send_json_error(['message' => 'No product ID']);
  }

  $added = WC()->cart->add_to_cart($product_id);

  if ($added) {
    wp_send_json_success(['message' => 'Product added']);
  } else {
    wp_send_json_error(['message' => 'Failed to add']);
  }
}
?>