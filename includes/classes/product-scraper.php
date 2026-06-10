<?php

/**
 * Product Scraper Class
 * Parsing products from voipx3.com
 */

class Product_Scraper
{
  private $base_url = 'https://voipx3.com';
  private $products = [];

  /**
   * Get HTML content from a page
   */
  private function fetch_html($url)
  {
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
   * Debug method - saves HTML to a file for testing
   */
  public function debug_html($url)
  {
    $html = $this->fetch_html($url);
    if ($html) {
      $debug_file = get_template_directory() . '/debug-html.txt';
      file_put_contents($debug_file, $html);
      return ['success' => true, 'file' => $debug_file, 'size' => strlen($html)];
    }
    return ['success' => false];
  }

  /**
   * Parsing all products from a store page (with pagination)
   */
  public function scrape_products_page($url = 'https://voipx3.com/webstore')
  {
    // NEW APPROACH: Using sitemap to get product URLs
    $sitemap_url = 'https://voipx3.com/sitemap.ols.xml';
    $product_urls = $this->get_product_urls_from_sitemap($sitemap_url);

    if (!empty($product_urls)) {
      return $product_urls;
    }

    // If the sitemap did not work, we return an error
    return ['error' => 'Не вдалося отримати список товарів з sitemap'];
  }

  /**
   * Отримати URL товарів з sitemap
   */
  private function get_product_urls_from_sitemap($sitemap_url)
  {
    $xml = $this->fetch_html($sitemap_url);

    if (!$xml) {
      return [];
    }

    // Парсимо XML
    $dom = new DOMDocument();
    @$dom->loadXML($xml);
    $xpath = new DOMXPath($dom);

    // Реєструємо namespace для sitemap
    $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

    // Знаходимо всі <loc> елементи
    $locations = $xpath->query('//sm:url/sm:loc');

    $product_urls = [];

    foreach ($locations as $loc) {
      $url = trim($loc->textContent);

      // Фільтруємо тільки URL товарів (/ols/products/)
      if (strpos($url, '/ols/products/') !== false) {
        $product_urls[] = ['url' => $url];
      }
    }

    return $product_urls;
  }

  /**
   * Парсинг деталей конкретного продукту
   */
  public function scrape_product_details($product_url)
  {
    $html = $this->fetch_html($product_url);

    if (!$html) {
      return ['error' => 'Не вдалося завантажити продукт: ' . $product_url];
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

    // Витягуємо категорію з URL товару
    $product['category_slug'] = $this->get_product_category_from_url($product_url);

    // ПРІОРИТЕТ 1: Витягуємо з meta тегів (найнадійніший спосіб)

    // Категорія з product:category meta тегу
    $category_meta = $xpath->query("//meta[@property='product:category']");
    if ($category_meta->length > 0) {
      $category_value = $category_meta->item(0)->getAttribute('content');
      if (!empty($category_value) && empty($product['category_slug'])) {
        $product['category_slug'] = sanitize_title($category_value);
        $product['category_name'] = $category_value;
      }
    }

    // Категорія з og:product:category
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

    // Категорія з breadcrumbs (навігаційні хлібні крошки)
    if (empty($product['category_slug'])) {
      // Шукаємо посилання в breadcrumbs, що містить /categories/
      $breadcrumb_links = $xpath->query("//a[contains(@href, '/categories/')]");
      if ($breadcrumb_links->length > 0) {
        // Беремо останнє посилання на категорію (найближче до товару)
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

    // Назва з og:title
    $og_title = $xpath->query("//meta[@property='og:title']");
    if ($og_title->length > 0) {
      $product['title'] = $og_title->item(0)->getAttribute('content');
    }

    // Якщо немає og:title, беремо з <title>
    if (empty($product['title'])) {
      $title_nodes = $xpath->query('//title');
      if ($title_nodes->length > 0) {
        $product['title'] = trim($title_nodes->item(0)->textContent);
      }
    }

    // Ціна з meta тега product:price:amount
    $price_meta = $xpath->query("//meta[@property='product:price:amount']");
    if ($price_meta->length > 0) {
      $product['price'] = $price_meta->item(0)->getAttribute('content');
    }

    // Опис з meta description
    $meta_desc = $xpath->query("//meta[@name='description']");
    if ($meta_desc->length > 0) {
      $product['description'] = $meta_desc->item(0)->getAttribute('content');
    }

    // Якщо немає description, беремо з og:description
    if (empty($product['description'])) {
      $og_desc = $xpath->query("//meta[@property='og:description']");
      if ($og_desc->length > 0) {
        $product['description'] = $og_desc->item(0)->getAttribute('content');
      }
    }

    // Розбиваємо опис на features (по перенесенню рядка або по реченню)
    if (!empty($product['description'])) {
      // Шукаємо патерни як "Feature1 Feature2 Feature3"
      $desc_parts = preg_split('/(?<=[a-z])(?=[A-Z])|[\r\n]+/', $product['description']);
      foreach ($desc_parts as $part) {
        $part = trim($part);
        if (!empty($part) && strlen($part) > 10) {
          // Тільки значущі частини
          $product['features'][] = $part;
        }
      }
    }

    // Зображення з og:image
    $og_image = $xpath->query("//meta[@property='og:image']");
    if ($og_image->length > 0) {
      $img_url = $og_image->item(0)->getAttribute('content');
      // Додаємо https якщо починається з //
      if (strpos($img_url, '//') === 0) {
        $img_url = 'https:' . $img_url;
      }
      $product['image'] = $img_url;
    }

    // Додаткова перевірка - витягуємо product URL з og:url
    $og_url = $xpath->query("//meta[@property='og:url']");
    if ($og_url->length > 0 && empty($product['url'])) {
      $product['url'] = $og_url->item(0)->getAttribute('content');
    }

    return $product;
  }

  /**
   * Отримати URL категорій з sitemap
   */
  public function get_category_urls_from_sitemap($sitemap_url = 'https://voipx3.com/sitemap.ols.xml')
  {
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

      // Фільтруємо тільки URL категорій (/ols/categories/)
      if (strpos($url, '/ols/categories/') !== false) {
        $category_urls[] = ['url' => $url];
      }
    }

    return $category_urls;
  }

  /**
   * Отримати категорії безпосередньо зі сторінки webstore
   */
  public function get_categories_from_webstore_page($webstore_url = 'https://voipx3.com/webstore')
  {
    $html = $this->fetch_html($webstore_url);

    if (!$html) {
      return [];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $categories = [];

    // Шукаємо всі посилання на категорії в навігації
    // <a data-ux="NavVerticalLink" data-aid="CATEGORY_LINK_*" href="/webstore/ols/categories/...">
    $category_links = $xpath->query("//a[contains(@data-aid, 'CATEGORY_LINK_') and contains(@href, '/categories/')]");

    foreach ($category_links as $link) {
      $href = $link->getAttribute('href');
      $name = trim($link->textContent);

      // Пропускаємо "All" категорію
      if (strpos($href, '/ols/all') !== false) {
        continue;
      }

      // Витягуємо slug з URL
      if (preg_match('/\/ols\/categories\/(.+?)(?:\?|$)/', $href, $matches)) {
        $slug = $matches[1];

        // Формуємо повний URL
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
   * Парсинг деталей конкретної категорії
   */
  public function scrape_category_details($category_url)
  {
    $html = $this->fetch_html($category_url);

    if (!$html) {
      return ['error' => 'Не вдалося завантажити категорію: ' . $category_url];
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

    // Назва з og:title або title
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

    // Опис з meta description
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

    // Зображення з og:image
    $og_image = $xpath->query("//meta[@property='og:image']");
    if ($og_image->length > 0) {
      $img_url = $og_image->item(0)->getAttribute('content');
      if (strpos($img_url, '//') === 0) {
        $img_url = 'https:' . $img_url;
      }
      $category['image'] = $img_url;
    }

    // Генеруємо slug з URL
    if (preg_match('/\/ols\/categories\/(.+?)\/?$/', $category_url, $matches)) {
      $category['slug'] = $matches[1];
    }

    return $category;
  }

  /**
   * Парсинг всіх категорій
   */
  public function scrape_all_categories($sitemap_url = 'https://voipx3.com/sitemap.ols.xml')
  {
    // СПОЧАТКУ: Пробуємо отримати категорії зі сторінки webstore
    $categories_from_page = $this->get_categories_from_webstore_page('https://voipx3.com/webstore');

    if (!empty($categories_from_page)) {
      // Парсимо деталі кожної категорії
      $all_categories = [];

      foreach ($categories_from_page as $category_item) {
        $category_details = $this->scrape_category_details($category_item['url']);

        if (!isset($category_details['error']) && !empty($category_details['name'])) {
          // Використовуємо дані зі сторінки webstore, якщо вони є
          if (!empty($category_item['name'])) {
            $category_details['name'] = $category_item['name'];
          }
          if (!empty($category_item['slug'])) {
            $category_details['slug'] = $category_item['slug'];
          }

          $all_categories[] = $category_details;
        }

        usleep(300000); // 0.3 секунди затримка
      }

      if (!empty($all_categories)) {
        return $all_categories;
      }
    }

    // РЕЗЕРВНИЙ ВАРІАНТ: Використовуємо sitemap
    $category_list = $this->get_category_urls_from_sitemap($sitemap_url);

    if (empty($category_list)) {
      return ['error' => 'Не вдалося отримати список категорій'];
    }

    $all_categories = [];

    foreach ($category_list as $category_item) {
      $category_details = $this->scrape_category_details($category_item['url']);

      if (!isset($category_details['error']) && !empty($category_details['name'])) {
        $all_categories[] = $category_details;
      }

      usleep(500000); // 0.5 секунди затримка
    }

    return $all_categories;
  }

  /**
   * Отримати категорію товару з його URL
   */
  public function get_product_category_from_url($product_url)
  {
    // Витягуємо slug категорії з URL товару
    // Приклад: https://voipx3.com/ols/products/category-name/product-name
    if (preg_match('/\/ols\/products\/([^\/]+)\//i', $product_url, $matches)) {
      return $matches[1];
    }
    return null;
  }

  /**
   * Debug метод - зберігає HTML категорії у файл для перевірки
   */
  public function debug_category_html($category_url)
  {
    $html = $this->fetch_html($category_url);
    if ($html) {
      $debug_file = get_template_directory() . '/debug-category.txt';
      file_put_contents($debug_file, $html);

      // Також шукаємо всі script теги з даними
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
   * Витягти назви товарів з HTML категорії (навіть якщо вони в JS)
   */
  public function extract_product_names_from_category($category_url)
  {
    $html = $this->fetch_html($category_url);

    if (!$html) {
      return [];
    }

    $product_identifiers = [];

    // Спосіб 1: Шукаємо URL товарів - найнадійніший спосіб
    // Шукаємо всі унікальні URL формату /ols/products/something
    if (preg_match_all('/\/ols\/products\/([a-zA-Z0-9\-_]+)/i', $html, $url_matches)) {
      foreach ($url_matches[1] as $product_slug) {
        $product_identifiers[] = $product_slug;
      }
    }

    // Спосіб 2: Шукаємо в JSON-LD structured data
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

    // Спосіб 3: Витягуємо назви з data-aid атрибутів
    if (preg_match_all('/data-aid=["\']PRODUCT[^"\']*["\'][^>]*>([^<]+)</i', $html, $aid_matches)) {
      foreach ($aid_matches[1] as $name) {
        $clean_name = trim(strip_tags($name));
        if (strlen($clean_name) > 5) {
          $product_identifiers[] = $clean_name;
        }
      }
    }

    // Спосіб 4: Шукаємо href="/webstore/ols/products/..."
    if (preg_match_all('/href=["\']([^"\']*\/ols\/products\/[^"\']+)["\']/i', $html, $href_matches)) {
      foreach ($href_matches[1] as $href) {
        $slug = basename($href);
        if (!empty($slug)) {
          $product_identifiers[] = $slug;
        }
      }
    }

    // Спосіб 5: Витягуємо з текстових блоків (заголовки h2, h3, h4 з product)
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
   * Парсинг товарів з конкретної категорії
   */
  public function scrape_products_from_category($category_url)
  {
    $html = $this->fetch_html($category_url);

    if (!$html) {
      return ['error' => 'Не вдалося завантажити категорію: ' . $category_url];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $products = [];

    // Шукаємо всі посилання на товари різними способами
    // Спосіб 1: через data-aid
    $product_links = $xpath->query("//a[contains(@data-aid, 'PRODUCT')]");

    // Спосіб 2: всі посилання що містять /ols/products/ в href
    if ($product_links->length === 0) {
      $product_links = $xpath->query("//a[contains(@href, '/ols/products/')]");
    }

    $seen_urls = [];

    foreach ($product_links as $link) {
      $href = $link->getAttribute('href');

      // Пропускаємо якщо не посилання на товар
      if (strpos($href, '/ols/products/') === false) {
        continue;
      }

      // Пропускаємо посилання на загальну сторінку товарів
      if ($href === '/webstore/ols/products' || $href === '/ols/products') {
        continue;
      }

      // Формуємо повний URL
      $full_url = $href;
      if (strpos($href, 'http') !== 0) {
        $full_url = $this->base_url . $href;
      }

      // Уникаємо дублікатів
      if (in_array($full_url, $seen_urls)) {
        continue;
      }

      $seen_urls[] = $full_url;
      $products[] = ['url' => $full_url];
    }

    return $products;
  }

  /**
   * Парсинг всіх товарів з усіх категорій (з автоматичним співставленням)
   */
  public function scrape_products_by_categories()
  {
    // КРОК 1: Отримуємо всі товари з sitemap
    $all_product_urls = $this->scrape_products_page();

    if (isset($all_product_urls['error']) || empty($all_product_urls)) {
      return ['error' => 'Не вдалося отримати список товарів з sitemap'];
    }

    // КРОК 2: Отримуємо всі категорії
    $categories = $this->get_categories_from_webstore_page();

    if (empty($categories)) {
      // Якщо не вдалося отримати категорії, парсимо товари без категорій
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

    // КРОК 3: Створюємо мапу ключових слів для категорій (з пріоритетами)
    $category_keywords = [
      'headsets' => [
        // Моделі
        'bh72',
        'bh76',
        'uh38',
        'uh36',
        'uh34',
        'uh30',
        'bt-headset',
        'usb-headset',
        // Загальні слова
        'headset',
        'headphone',
        'earphone',
        'earbud',
      ],
      'ip-phones' => [
        // Моделі Yealink
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
        // Загальні слова - але НЕ для headset!
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

    // КРОК 4: Парсимо кожен товар і автоматично співставляємо з категорією
    $all_products = [];
    $matched_count = 0;

    foreach ($all_product_urls as $product_item) {
      $product_details = $this->scrape_product_details($product_item['url']);

      if (!isset($product_details['error']) && !empty($product_details['title'])) {
        // Зіставляємо товар з категорією
        $matched_category = null;
        $product_slug = strtolower(basename($product_details['url']));
        $product_title = strtolower($product_details['title']);
        $search_text = $product_slug . ' ' . $product_title;

        // Спеціальне правило: якщо є "headset" в назві - це headset, не phone
        $is_headset = stripos($search_text, 'headset') !== false || stripos($search_text, 'headphone') !== false;

        // Перебираємо всі категорії і шукаємо співпадіння
        foreach ($categories as $category) {
          $cat_slug = $category['slug'];

          // Пропускаємо ip-phones якщо це headset
          if ($is_headset && $cat_slug === 'ip-phones') {
            continue;
          }

          // Перевіряємо ключові слова категорії
          if (isset($category_keywords[$cat_slug])) {
            foreach ($category_keywords[$cat_slug] as $keyword) {
              $keyword = strtolower($keyword);

              // Шукаємо keyword в тексті товару
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

        // Додаємо категорію до товару
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
   * Старий метод парсингу по категоріям (резервний)
   */
  public function scrape_products_by_categories_old()
  {
    // Отримуємо всі категорії
    $categories = $this->get_categories_from_webstore_page();

    if (empty($categories)) {
      return ['error' => 'Не вдалося отримати список категорій'];
    }

    $all_products = [];
    $seen_urls = [];

    foreach ($categories as $category) {
      // Парсимо товари з категорії
      $products_in_category = $this->scrape_products_from_category($category['url']);

      if (isset($products_in_category['error'])) {
        continue;
      }

      // Додаємо інформацію про категорію до кожного товару
      foreach ($products_in_category as $product_item) {
        $product_url = $product_item['url'];

        // Уникаємо дублікатів
        if (in_array($product_url, $seen_urls)) {
          continue;
        }

        $seen_urls[] = $product_url;

        // Парсимо деталі товару
        $product_details = $this->scrape_product_details($product_url);

        if (!isset($product_details['error']) && !empty($product_details['title'])) {
          // Гарантуємо, що категорія встановлена
          if (empty($product_details['category_slug'])) {
            $product_details['category_slug'] = $category['slug'];
            $product_details['category_name'] = $category['name'];
          }

          $all_products[] = $product_details;
        }

        // Затримка між товарами
        usleep(300000); // 0.3 секунди
      }

      // Затримка між категоріями
      usleep(500000); // 0.5 секунди
    }

    return $all_products;
  }

  /**
   * Парсинг всіх продуктів з магазину
   */
  public function scrape_all_products($shop_url = 'https://voipx3.com/webstore')
  {
    // Отримуємо список всіх продуктів
    $product_list = $this->scrape_products_page($shop_url);

    if (isset($product_list['error'])) {
      return $product_list;
    }

    $all_products = [];

    // Парсимо деталі кожного продукту
    foreach ($product_list as $product_item) {
      $product_details = $this->scrape_product_details($product_item['url']);

      if (!isset($product_details['error']) && !empty($product_details['title'])) {
        $all_products[] = $product_details;
      }

      // Невелика затримка, щоб не перевантажувати сервер
      usleep(500000); // 0.5 секунди
    }

    return $all_products;
  }

  /**
   * Парсинг конкретного продукту за URL
   */
  public function scrape_single_product($product_url)
  {
    return $this->scrape_product_details($product_url);
  }
}
