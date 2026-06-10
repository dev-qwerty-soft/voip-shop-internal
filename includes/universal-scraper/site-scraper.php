<?php

/**
 * Site Content Scraper Class
 * Universal scraper for any website content (products, articles, etc.)
 *
 * @package Universal_Site_Scraper
 * @version 1.0.0
 */

class Site_Content_Scraper {
  private $base_url = 'https://voipx3.com';
  private $items = [];

  /**
   * Fetch HTML content from page
   */
  private function fetch_html($url) {
    $args = [
      'timeout' => 30,
      'sslverify' => false,
      'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    ];

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
      return false;
    }

    return wp_remote_retrieve_body($response);
  }

  /**
   * Debug method - saves HTML to file for checking
   */
  public function debug_html($url) {
    $html = $this->fetch_html($url);
    if ($html) {
      $debug_file = get_template_directory() . '/debug-html.txt';
      file_put_contents($debug_file, $html);
      return ['success' => true, 'file' => $debug_file, 'size' => strlen($html)];
    }
    return ['success' => false];
  }

  /**
   * Scrape all products from shop page (with pagination)
   */
  public function scrape_products_page($url = 'https://voipx3.com/webstore') {
    // NEW APPROACH: Use sitemap to get product URLs
    $sitemap_url = 'https://voipx3.com/sitemap.ols.xml';
    $product_urls = $this->get_product_urls_from_sitemap($sitemap_url);

    if (!empty($product_urls)) {
      return $product_urls;
    }

    // If sitemap failed, return error
    return ['error' => 'Failed to get product list from sitemap'];
  }

  /**
   * Get product URLs from sitemap
   */
  private function get_product_urls_from_sitemap($sitemap_url) {
    $xml = $this->fetch_html($sitemap_url);

    if (!$xml) {
      return [];
    }

    // Parse XML
    $dom = new DOMDocument();
    @$dom->loadXML($xml);
    $xpath = new DOMXPath($dom);

    // Register namespace for sitemap
    $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

    // Find all <loc> elements
    $locations = $xpath->query('//sm:url/sm:loc');

    $product_urls = [];

    foreach ($locations as $loc) {
      $url = trim($loc->textContent);

      // Filter only product URLs (/ols/products/)
      if (strpos($url, '/ols/products/') !== false) {
        $product_urls[] = ['url' => $url];
      }
    }

    return $product_urls;
  }

  /**
   * Scrape specific product details
   */
  public function scrape_product_details($product_url) {
    $html = $this->fetch_html($product_url);

    if (!$html) {
      return ['error' => 'Failed to load product: ' . $product_url];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $product = [
      'url' => $product_url,
      'title' => '',
      'price' => '',
      'description' => '',
      'image' => '',
      'features' => [],
      'category_slug' => '',
    ];

    // Extract category from product URL
    $product['category_slug'] = $this->get_product_category_from_url($product_url);

    // PRIORITY 1: Extract from meta tags (most reliable way)

    // Category from product:category meta tag
    $category_meta = $xpath->query("//meta[@property='product:category']");
    if ($category_meta->length > 0) {
      $category_value = $category_meta->item(0)->getAttribute('content');
      if (!empty($category_value) && empty($product['category_slug'])) {
        $product['category_slug'] = sanitize_title($category_value);
        $product['category_name'] = $category_value;
      }
    }

    // Category from og:product:category
    if (empty($product['category_slug'])) {
      $og_category = $xpath->query("//meta[@property='og:product:category']");
      if ($og_category->length > 0) {
        $category_value = $og_category->item(0)->getAttribute('content');
        if (!empty($category_value)) {
          $product['category_slug'] = sanitize_title($category_value);
          $product['category_name'] = $category_value;
        }
      }
    }

    // Category from breadcrumbs (navigation breadcrumbs)
    if (empty($product['category_slug'])) {
      // Find links in breadcrumbs containing /categories/
      $breadcrumb_links = $xpath->query("//a[contains(@href, '/categories/')]");
      if ($breadcrumb_links->length > 0) {
        // Take last category link (closest to product)
        $last_link = $breadcrumb_links->item($breadcrumb_links->length - 1);
        $href = $last_link->getAttribute('href');
        $category_name = trim($last_link->textContent);

        if (preg_match('/\/categories\/([^\/\?]+)/', $href, $matches)) {
          $product['category_slug'] = $matches[1];
          if (!empty($category_name)) {
            $product['category_name'] = $category_name;
          }
        }
      }
    }

    // Title from og:title
    $og_title = $xpath->query("//meta[@property='og:title']");
    if ($og_title->length > 0) {
      $product['title'] = $og_title->item(0)->getAttribute('content');
    }

    // If no og:title, get from <title>
    if (empty($product['title'])) {
      $title_nodes = $xpath->query('//title');
      if ($title_nodes->length > 0) {
        $product['title'] = trim($title_nodes->item(0)->textContent);
      }
    }

    // Price from meta tag product:price:amount
    $price_meta = $xpath->query("//meta[@property='product:price:amount']");
    if ($price_meta->length > 0) {
      $product['price'] = $price_meta->item(0)->getAttribute('content');
    }

    // Description from meta description
    $meta_desc = $xpath->query("//meta[@name='description']");
    if ($meta_desc->length > 0) {
      $product['description'] = $meta_desc->item(0)->getAttribute('content');
    }

    // If no description, get from og:description
    if (empty($product['description'])) {
      $og_desc = $xpath->query("//meta[@property='og:description']");
      if ($og_desc->length > 0) {
        $product['description'] = $og_desc->item(0)->getAttribute('content');
      }
    }

    // Split description into features (by line breaks or sentences)
    if (!empty($product['description'])) {
      // Find patterns like "Feature1 Feature2 Feature3"
      $desc_parts = preg_split('/(?<=[a-z])(?=[A-Z])|[\r\n]+/', $product['description']);
      foreach ($desc_parts as $part) {
        $part = trim($part);
        if (!empty($part) && strlen($part) > 10) {
          // Only significant parts
          $product['features'][] = $part;
        }
      }
    }

    // Image from og:image
    $og_image = $xpath->query("//meta[@property='og:image']");
    if ($og_image->length > 0) {
      $img_url = $og_image->item(0)->getAttribute('content');
      // Add https if starts with //
      if (strpos($img_url, '//') === 0) {
        $img_url = 'https:' . $img_url;
      }
      $product['image'] = $img_url;
    }

    // Additional check - extract product URL from og:url
    $og_url = $xpath->query("//meta[@property='og:url']");
    if ($og_url->length > 0 && empty($product['url'])) {
      $product['url'] = $og_url->item(0)->getAttribute('content');
    }

    return $product;
  }

  /**
   * Get category URLs from sitemap
   */
  public function get_category_urls_from_sitemap($sitemap_url = 'https://voipx3.com/sitemap.ols.xml') {
    $xml = $this->fetch_html($sitemap_url);

    if (!$xml) {
      return [];
    }

    $dom = new DOMDocument();
    @$dom->loadXML($xml);
    $xpath = new DOMXPath($dom);

    $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    $locations = $xpath->query('//sm:url/sm:loc');

    $category_urls = [];

    foreach ($locations as $loc) {
      $url = trim($loc->textContent);

      // Filter only category URLs (/ols/categories/)
      if (strpos($url, '/ols/categories/') !== false) {
        $category_urls[] = ['url' => $url];
      }
    }

    return $category_urls;
  }

  /**
   * Get categories directly from webstore page
   */
  public function get_categories_from_webstore_page($webstore_url = 'https://voipx3.com/webstore') {
    $html = $this->fetch_html($webstore_url);

    if (!$html) {
      return [];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $categories = [];

    // Find all category links in navigation
    // <a data-ux="NavVerticalLink" data-aid="CATEGORY_LINK_*" href="/webstore/ols/categories/...">
    $category_links = $xpath->query("//a[contains(@data-aid, 'CATEGORY_LINK_') and contains(@href, '/categories/')]");

    foreach ($category_links as $link) {
      $href = $link->getAttribute('href');
      $name = trim($link->textContent);

      // Skip "All" category
      if (strpos($href, '/ols/all') !== false) {
        continue;
      }

      // Extract slug from URL
      if (preg_match('/\/ols\/categories\/(.+?)(?:\?|$)/', $href, $matches)) {
        $slug = $matches[1];

        // Form full URL
        $full_url = $href;
        if (strpos($href, 'http') !== 0) {
          $full_url = $this->base_url . $href;
        }

        $categories[] = [
          'name' => $name,
          'slug' => $slug,
          'url' => $full_url,
        ];
      }
    }

    return $categories;
  }

  /**
   * Scrape specific category details
   */
  public function scrape_category_details($category_url) {
    $html = $this->fetch_html($category_url);

    if (!$html) {
      return ['error' => 'Failed to load category: ' . $category_url];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $category = [
      'url' => $category_url,
      'name' => '',
      'description' => '',
      'slug' => '',
      'image' => '',
    ];

    // Title from og:title or title
    $og_title = $xpath->query("//meta[@property='og:title']");
    if ($og_title->length > 0) {
      $category['name'] = $og_title->item(0)->getAttribute('content');
    }

    if (empty($category['name'])) {
      $title_nodes = $xpath->query('//title');
      if ($title_nodes->length > 0) {
        $category['name'] = trim($title_nodes->item(0)->textContent);
      }
    }

    // Description with meta description
    $meta_desc = $xpath->query("//meta[@name='description']");
    if ($meta_desc->length > 0) {
      $category['description'] = $meta_desc->item(0)->getAttribute('content');
    }

    if (empty($category['description'])) {
      $og_desc = $xpath->query("//meta[@property='og:description']");
      if ($og_desc->length > 0) {
        $category['description'] = $og_desc->item(0)->getAttribute('content');
      }
    }

    // og:image
    $og_image = $xpath->query("//meta[@property='og:image']");
    if ($og_image->length > 0) {
      $img_url = $og_image->item(0)->getAttribute('content');
      if (strpos($img_url, '//') === 0) {
        $img_url = 'https:' . $img_url;
      }
      $category['image'] = $img_url;
    }

    // Generate slug from URL
    if (preg_match('/\/ols\/categories\/(.+?)\/?$/', $category_url, $matches)) {
      $category['slug'] = $matches[1];
    }

    return $category;
  }

  /**
   * Scrape all categories
   */
  public function scrape_all_categories($sitemap_url = 'https://voipx3.com/sitemap.ols.xml') {
    // FIRST: Try to get categories from webstore page
    $categories_from_page = $this->get_categories_from_webstore_page('https://voipx3.com/webstore');

    if (!empty($categories_from_page)) {
      // Parse details for each category
      $all_categories = [];

      foreach ($categories_from_page as $category_item) {
        $category_details = $this->scrape_category_details($category_item['url']);

        if (!isset($category_details['error']) && !empty($category_details['name'])) {
          // Use data from webstore page if available
          if (!empty($category_item['name'])) {
            $category_details['name'] = $category_item['name'];
          }
          if (!empty($category_item['slug'])) {
            $category_details['slug'] = $category_item['slug'];
          }

          $all_categories[] = $category_details;
        }

        usleep(300000); // 0.3 seconds delay
      }

      if (!empty($all_categories)) {
        return $all_categories;
      }
    }

    // FALLBACK OPTION: Use sitemap
    $category_list = $this->get_category_urls_from_sitemap($sitemap_url);

    if (empty($category_list)) {
      return ['error' => 'Failed to get category list'];
    }

    $all_categories = [];

    foreach ($category_list as $category_item) {
      $category_details = $this->scrape_category_details($category_item['url']);

      if (!isset($category_details['error']) && !empty($category_details['name'])) {
        $all_categories[] = $category_details;
      }

      usleep(500000); // 0.5 seconds delay
    }

    return $all_categories;
  }

  /**
   * Get product category from its URL
   */
  public function get_product_category_from_url($product_url) {
    // Extract category slug from product URL
    // Example: https://voipx3.com/ols/products/category-name/product-name
    if (preg_match('/\/ols\/products\/([^\/]+)\//i', $product_url, $matches)) {
      return $matches[1];
    }
    return null;
  }

  /**
   * Debug method - saves category HTML to file for checking
   */
  public function debug_category_html($category_url) {
    $html = $this->fetch_html($category_url);
    if ($html) {
      $debug_file = get_template_directory() . '/debug-category.txt';
      file_put_contents($debug_file, $html);

      // Also find all script tags with data
      $scripts_file = get_template_directory() . '/debug-category-scripts.txt';
      preg_match_all('/<script[^>]*>(.*?)<\/script>/is', $html, $scripts);
      file_put_contents($scripts_file, print_r($scripts[1], true));

      return [
        'success' => true,
        'file' => $debug_file,
        'scripts_file' => $scripts_file,
        'size' => strlen($html),
        'scripts_count' => count($scripts[1]),
      ];
    }
    return ['success' => false];
  }

  /**
   * Extract product names from category HTML (even if in JS)
   */
  public function extract_product_names_from_category($category_url) {
    $html = $this->fetch_html($category_url);

    if (!$html) {
      return [];
    }

    $product_identifiers = [];

    // Method 1: Find product URLs - most reliable way
    // Find all unique URLs in format /ols/products/something
    if (preg_match_all('/\/ols\/products\/([a-zA-Z0-9\-_]+)/i', $html, $url_matches)) {
      foreach ($url_matches[1] as $product_slug) {
        $product_identifiers[] = $product_slug;
      }
    }

    // Method 2: Find in JSON-LD structured data
    if (preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/is', $html, $json_matches)) {
      foreach ($json_matches[1] as $json_str) {
        $data = json_decode($json_str, true);
        if (isset($data['name'])) {
          $product_identifiers[] = trim($data['name']);
        }
        if (isset($data['itemListElement']) && is_array($data['itemListElement'])) {
          foreach ($data['itemListElement'] as $item) {
            if (isset($item['name'])) {
              $product_identifiers[] = trim($item['name']);
            }
            if (isset($item['url'])) {
              $product_identifiers[] = basename($item['url']);
            }
          }
        }
      }
    }

    // Method 3: Extract names from data-aid attributes
    if (preg_match_all('/data-aid=["\']PRODUCT[^"\']*["\'][^>]*>([^<]+)</i', $html, $aid_matches)) {
      foreach ($aid_matches[1] as $name) {
        $clean_name = trim(strip_tags($name));
        if (strlen($clean_name) > 5) {
          $product_identifiers[] = $clean_name;
        }
      }
    }

    // Method 4: Search for href="/webstore/ols/products/..."
    if (preg_match_all('/href=["\']([^"\']*\/ols\/products\/[^"\']+)["\']/i', $html, $href_matches)) {
      foreach ($href_matches[1] as $href) {
        $slug = basename($href);
        if (!empty($slug)) {
          $product_identifiers[] = $slug;
        }
      }
    }

    // Method 5: Extract from text blocks (headings h2, h3, h4 from product)
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $headings = $xpath->query("//h2 | //h3 | //h4 | //*[contains(@class, 'product')]");
    foreach ($headings as $heading) {
      $text = trim($heading->textContent);
      if (strlen($text) > 5 && strlen($text) < 200) {
        $product_identifiers[] = $text;
      }
    }

    return array_values(array_unique(array_filter($product_identifiers)));
  }

  /**
   * Parsing products from a specific category
   */
  public function scrape_products_from_category($category_url) {
    $html = $this->fetch_html($category_url);

    if (!$html) {
      return ['error' => 'Failed to load category: ' . $category_url];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $products = [];

    // We search for all product links in various ways
    // Method 1: via data-aid
    $product_links = $xpath->query("//a[contains(@data-aid, 'PRODUCT')]");

    // Method 2: all links containing /ols/products/ in the href
    if ($product_links->length === 0) {
      $product_links = $xpath->query("//a[contains(@href, '/ols/products/')]");
    }

    $seen_urls = [];

    foreach ($product_links as $link) {
      $href = $link->getAttribute('href');

      // Skip if not a link to the product
      if (strpos($href, '/ols/products/') === false) {
        continue;
      }

      // Skipping links to the general product page
      if ($href === '/webstore/ols/products' || $href === '/ols/products') {
        continue;
      }

      // Forming the full URL
      $full_url = $href;
      if (strpos($href, 'http') !== 0) {
        $full_url = $this->base_url . $href;
      }

      // Avoiding duplicates
      if (in_array($full_url, $seen_urls)) {
        continue;
      }

      $seen_urls[] = $full_url;
      $products[] = ['url' => $full_url];
    }

    return $products;
  }

  /**
   * Parsing of all products from all categories (with automatic matching)
   */
  public function scrape_products_by_categories() {
    // STEP 1: Get all products from sitemap
    $all_product_urls = $this->scrape_products_page();

    if (isset($all_product_urls['error']) || empty($all_product_urls)) {
      return ['error' => 'Failed to get product list from sitemap'];
    }

    // STEP 2: Get all categories
    $categories = $this->get_categories_from_webstore_page();

    if (empty($categories)) {
      // If we couldn't get the categories, we parse the products without categories
      $products_without_categories = [];
      foreach ($all_product_urls as $product_item) {
        $product_details = $this->scrape_product_details($product_item['url']);
        if (!isset($product_details['error']) && !empty($product_details['title'])) {
          $products_without_categories[] = $product_details;
        }
        usleep(300000);
      }
      return $products_without_categories;
    }

    // STEP 3: Create a keyword map for categories (with priorities)
    $category_keywords = [
      'headsets' => [
        // Models
        'bh72',
        'bh76',
        'uh38',
        'uh36',
        'uh34',
        'uh30',
        'bt-headset',
        'usb-headset',
        // Words
        'headset',
        'headphone',
        'earphone',
        'earbud',
      ],
      'ip-phones' => [
        // Models Yealink
        't54w',
        't46s',
        't48g',
        't53',
        't54',
        't56',
        't57',
        't58',
        'vvx',
        'ccx',
        'soundpoint',
        // Common words - but NOT for headset!
        'desk phone',
        'office phone',
        'business phone',
        'voip phone',
        'sip phone',
      ],
      'personal-collaboration' => ['studio', 'speakerphone', 'webcam', 'camera', 'p-series', 'sync', 'video bar'],
      'paging' => ['paging', 'horn', 'pa-', 'speaker system', 'overhead'],
      'conference-phones' => ['trio', 'conference', 'group', 'meeting phone', 'conference phone'],
      'wireless-dect' => ['dect', 'w60', 'w70', 'w80', 'cordless base'],
      'accessories' => ['cable', 'power supply', 'adapter', 'mount', 'bracket', 'expansion'],
      'networking' => ['switch', 'router', 'gateway', 'poe switch', 'network adapter'],
    ];

    // STEP 4: We parse each product and automatically match it to the category
    $all_products = [];
    $matched_count = 0;

    foreach ($all_product_urls as $product_item) {
      $product_details = $this->scrape_product_details($product_item['url']);

      if (!isset($product_details['error']) && !empty($product_details['title'])) {
        // We match the product with the category
        $matched_category = null;
        $product_slug = strtolower(basename($product_details['url']));
        $product_title = strtolower($product_details['title']);
        $search_text = $product_slug . ' ' . $product_title;

        // Special rule: if there is "headset" in the name - it is a headset, not a phone
        $is_headset = stripos($search_text, 'headset') !== false || stripos($search_text, 'headphone') !== false;

        // We go through all the categories and look for matches.
        foreach ($categories as $category) {
          $cat_slug = $category['slug'];

          // Skip IP phones if it is a headset
          if ($is_headset && $cat_slug === 'ip-phones') {
            continue;
          }

          // Checking category keywords
          if (isset($category_keywords[$cat_slug])) {
            foreach ($category_keywords[$cat_slug] as $keyword) {
              $keyword = strtolower($keyword);

              // We search for keywords in the product text
              if (stripos($search_text, $keyword) !== false) {
                $matched_category = [
                  'slug' => $cat_slug,
                  'name' => $category['name'],
                ];
                $matched_count++;
                break 2;
              }
            }
          }
        }

        // Add a category to the product
        if ($matched_category) {
          $product_details['category_slug'] = $matched_category['slug'];
          $product_details['category_name'] = $matched_category['name'];
        }

        $all_products[] = $product_details;
      }

      usleep(300000);
    }

    return [
      'products' => $all_products,
      'total' => count($all_products),
      'matched' => $matched_count,
      'unmatched' => count($all_products) - $matched_count,
    ];
  }

  /**
   * Old method of parsing by category (fallback)
   */
  public function scrape_products_by_categories_old() {
    // We get all categories
    $categories = $this->get_categories_from_webstore_page();

    if (empty($categories)) {
      return ['error' => 'Failed to get the list of categories.'];
    }

    $all_products = [];
    $seen_urls = [];

    foreach ($categories as $category) {
      // We parse products from the category
      $products_in_category = $this->scrape_products_from_category($category['url']);

      if (isset($products_in_category['error'])) {
        continue;
      }

      // We add category information to each product
      foreach ($products_in_category as $product_item) {
        $product_url = $product_item['url'];

        // Avoiding duplicates
        if (in_array($product_url, $seen_urls)) {
          continue;
        }

        $seen_urls[] = $product_url;

        // We parse the product details.
        $product_details = $this->scrape_product_details($product_url);

        if (!isset($product_details['error']) && !empty($product_details['title'])) {
          // We guarantee that the category is set
          if (empty($product_details['category_slug'])) {
            $product_details['category_slug'] = $category['slug'];
            $product_details['category_name'] = $category['name'];
          }

          $all_products[] = $product_details;
        }

        // Delay between products
        usleep(300000); // 0.3 sec
      }

      // Delay between categories
      usleep(500000); // 0.5 sec
    }

    return $all_products;
  }

  /**
   * Parsing all products from the store
   */
  public function scrape_all_products($shop_url = 'https://voipx3.com/webstore') {
    // We get a list of all products
    $product_list = $this->scrape_products_page($shop_url);

    if (isset($product_list['error'])) {
      return $product_list;
    }

    $all_products = [];

    // We parse the details of each product
    foreach ($product_list as $product_item) {
      $product_details = $this->scrape_product_details($product_item['url']);

      if (!isset($product_details['error']) && !empty($product_details['title'])) {
        $all_products[] = $product_details;
      }

      // A small delay to avoid overloading the server
      usleep(500000); // 0.5 sec
    }

    return $all_products;
  }

  /**
   * Parsing a specific product by URL
   */
  public function scrape_single_product($product_url) {
    return $this->scrape_product_details($product_url);
  }
}
