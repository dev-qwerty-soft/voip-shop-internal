<?php

/**
 * Product Importer Class
 * Importing products into WooCommerce
 */

class Product_Importer {
  /**
   * WooCommerce Category Import
   */
  public function import_category($category_data) {
    if (!class_exists('WooCommerce')) {
      return ['success' => false, 'message' => 'WooCommerce not activated'];
    }

    if (empty($category_data['name'])) {
      return ['success' => false, 'message' => 'Missing category name'];
    }

    // Checking if the category already exists
    $existing_term = term_exists($category_data['name'], 'product_cat');

    if ($existing_term) {
      return [
        'success' => false,
        'message' => 'Category "' . $category_data['name'] . '" already exists',
        'term_id' => $existing_term['term_id'],
      ];
    }

    // not activated
    $term_data = [
      'description' => !empty($category_data['description']) ? $category_data['description'] : '',
      'slug' => !empty($category_data['slug']) ? $category_data['slug'] : sanitize_title($category_data['name']),
    ];

    $term = wp_insert_term($category_data['name'], 'product_cat', $term_data);

    if (is_wp_error($term)) {
      return [
        'success' => false,
        'message' => 'Error creating category: ' . $term->get_error_message(),
      ];
    }

    $term_id = $term['term_id'];

    // Loading category images
    if (!empty($category_data['image'])) {
      require_once ABSPATH . 'wp-admin/includes/media.php';
      require_once ABSPATH . 'wp-admin/includes/file.php';
      require_once ABSPATH . 'wp-admin/includes/image.php';

      $tmp = download_url($category_data['image']);

      if (!is_wp_error($tmp)) {
        $file_array = [
          'name' => sanitize_file_name($category_data['name']) . '.png',
          'tmp_name' => $tmp,
        ];

        $attachment_id = media_handle_sideload($file_array, 0);

        if (file_exists($tmp)) {
          @unlink($tmp);
        }

        if (!is_wp_error($attachment_id)) {
          update_term_meta($term_id, 'thumbnail_id', $attachment_id);
        }
      }
    }

    // We keep the original URL
    update_term_meta($term_id, '_scraped_from_url', $category_data['url']);
    update_term_meta($term_id, '_scraped_date', current_time('mysql'));

    return [
      'success' => true,
      'message' => 'Category "' . $category_data['name'] . '" successfully imported',
      'term_id' => $term_id,
      'category_url' => get_term_link($term_id, 'product_cat'),
    ];
  }

  /**
   * Importing an array of categories
   */
  public function import_multiple_categories($categories_array) {
    $results = [
      'total' => count($categories_array),
      'imported' => 0,
      'skipped' => 0,
      'errors' => 0,
      'details' => [],
    ];

    foreach ($categories_array as $category_data) {
      $result = $this->import_category($category_data);

      if ($result['success']) {
        $results['imported']++;
      } else {
        if (strpos($result['message'], 'already exists') !== false) {
          $results['skipped']++;
        } else {
          $results['errors']++;
        }
      }

      $results['details'][] = $result;
    }

    return $results;
  }

  /**
   * Find or create a category by slug
   */
  private function find_or_create_category($category_slug, $category_name = '') {
    if (empty($category_slug)) {
      return null;
    }

    // Searching for a category by slug
    $term = get_term_by('slug', $category_slug, 'product_cat');

    if ($term) {
      return $term->term_id;
    }

    // If not found, create a new one
    if (empty($category_name)) {
      $category_name = ucfirst(str_replace('-', ' ', $category_slug));
    }

    $term_data = wp_insert_term($category_name, 'product_cat', ['slug' => $category_slug]);

    if (is_wp_error($term_data)) {
      return null;
    }

    return $term_data['term_id'];
  }

  /**
   * Loading an image from a URL and attaching it to a product
   */
  private function import_product_image($image_url, $product_id, $product_title) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Loading images
    $tmp = download_url($image_url);

    if (is_wp_error($tmp)) {
      return false;
    }

    // Getting the file extension
    $file_array = [
      'name' => sanitize_file_name($product_title) . '.png',
      'tmp_name' => $tmp,
    ];

    // Upload as an attachment
    $attachment_id = media_handle_sideload($file_array, $product_id);

    // Delete the temporary file
    if (file_exists($tmp)) {
      @unlink($tmp);
    }

    if (is_wp_error($attachment_id)) {
      return false;
    }

    return $attachment_id;
  }

  /**
   * Importing a single product
   */
  public function import_product($product_data) {
    // Checking if WooCommerce exists
    if (!class_exists('WooCommerce')) {
      return ['success' => false, 'message' => 'WooCommerce is not activated.'];
    }

    // Checking required fields
    if (empty($product_data['title'])) {
      return ['success' => false, 'message' => 'Missing product name'];
    }

    // Checking if the product already exists
    $existing_product = get_page_by_title($product_data['title'], OBJECT, 'product');

    if ($existing_product) {
      return [
        'success' => false,
        'message' => 'Product "' . $product_data['title'] . '" already exists',
        'product_id' => $existing_product->ID,
      ];
    }

    // Creating a WooCommerce product
    $product = new WC_Product_Simple();

    // Setting the name
    $product->set_name($product_data['title']);

    // Setting the price
    if (!empty($product_data['price'])) {
      $price = floatval($product_data['price']);
      $product->set_regular_price($price);
      $product->set_price($price);
    }

    // Setting the publication status
    $product->set_status('publish');

    // We store the product
    $product_id = $product->save();

    if (!$product_id) {
      return ['success' => false, 'message' => 'Error creating product'];
    }

    // Loading images
    if (!empty($product_data['image'])) {
      $attachment_id = $this->import_product_image($product_data['image'], $product_id, $product_data['title']);

      if ($attachment_id) {
        $product->set_image_id($attachment_id);
        $product->save();
      }
    }

    // Keep the original URL as meta
    update_post_meta($product_id, '_scraped_from_url', $product_data['url']);
    update_post_meta($product_id, '_scraped_date', current_time('mysql'));

    // We bind the category, if it is specified
    if (!empty($product_data['category_slug'])) {
      $term_id = $this->find_or_create_category($product_data['category_slug'], !empty($product_data['category_name']) ? $product_data['category_name'] : '');

      if ($term_id) {
        wp_set_object_terms($product_id, [$term_id], 'product_cat');
      }
    }

    // Store data in ACF fields (if ACF is enabled)
    if (function_exists('update_field')) {
      // We store the full description in the ACF field product_description
      if (!empty($product_data['description'])) {
        update_field('product_description', $product_data['description'], $product_id);
      }

      // Store features as a list in the ACF field key_features
      if (!empty($product_data['features']) && is_array($product_data['features'])) {
        // Formatting features as an HTML list
        $features_html = '<ul>' . "\n";
        foreach ($product_data['features'] as $feature) {
          $features_html .= '<li>' . esc_html($feature) . '</li>' . "\n";
        }
        $features_html .= '</ul>';

        update_field('key_features', $features_html, $product_id);
      }
    }

    return [
      'success' => true,
      'message' => 'Product "' . $product_data['title'] . '" successfully imported',
      'product_id' => $product_id,
      'product_url' => get_permalink($product_id),
    ];
  }

  /**
   * Importing an array of products
   */
  public function import_multiple_products($products_array) {
    $results = [
      'total' => count($products_array),
      'imported' => 0,
      'skipped' => 0,
      'errors' => 0,
      'details' => [],
    ];

    foreach ($products_array as $product_data) {
      $result = $this->import_product($product_data);

      if ($result['success']) {
        $results['imported']++;
      } else {
        if (strpos($result['message'], 'already exists') !== false) {
          $results['skipped']++;
        } else {
          $results['errors']++;
        }
      }

      $results['details'][] = $result;
    }

    return $results;
  }

  /**
   * Updating an existing product
   */
  public function update_product($product_id, $product_data) {
    $product = wc_get_product($product_id);

    if (!$product) {
      return ['success' => false, 'message' => 'Product not found'];
    }

    // Updating the name
    if (!empty($product_data['title'])) {
      $product->set_name($product_data['title']);
    }

    // Updating the price
    if (!empty($product_data['price'])) {
      $price = floatval($product_data['price']);
      $product->set_regular_price($price);
      $product->set_price($price);
    }

    // Updating the image
    if (!empty($product_data['image'])) {
      $attachment_id = $this->import_product_image($product_data['image'], $product_id, $product_data['title']);

      if ($attachment_id) {
        $product->set_image_id($attachment_id);
      }
    }

    $product->save();

    // Updating meta data
    update_post_meta($product_id, '_scraped_date', current_time('mysql'));

    return [
      'success' => true,
      'message' => 'Product updated',
      'product_id' => $product_id,
    ];
  }
}
