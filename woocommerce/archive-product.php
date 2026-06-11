<?php

/**
 * Template Name: Custom Shop Page
 * Template Post Type: page
 */

defined('ABSPATH') || exit();
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

$args = [
  'post_type' => 'product',
  'posts_per_page' => 16,
  'post_status' => 'publish',
  'orderby' => 'menu_order',
  'order' => 'ASC',
];

$products_query = new WP_Query($args);

get_header();
?>

<main class="shop-page">
  <?php get_template_part('template-blocks/breadcrumbs'); ?>

  <!-- Hero Section -->
  <section class="shop-hero">
    <div class="container">
      <div class="shop-hero__content">
        <h1 class="shop-hero__title" data-aos="fade-up" data-aos-delay="100">Shop</h1>
        <p class="shop-hero__description simple-text" data-aos="fade-up" data-aos-delay="200">Browse the VOIPx3 equipment catalog for business communications: IP phones, DECT systems, headsets, video conferencing solutions, networking devices, and accessories. Filter by category and price to find the right fit for your needs.</p>
      </div>
    </div>
  </section>

  <section class="shop-content">
    <div class="shop-layout">
      <aside class="shop-sidebar">
        <div class="filters" data-aos="fade-up" data-aos-delay="300">
          <div class="filters__header">
            <h3>Filters</h3>
            <button class="filters__clear" id="clearAllFilters">Clear all</button>
          </div>
          <div class="filter-group price-filter-group active">
            <h4 class="filter-group__title" data-toggle="price">
              Price ($)
              <span class="toggle-icon">
                <?= displaySvg('src/svg/chevron-down.svg') ?>
              </span>
            </h4>
            <div class="filter-group__content visible" id="priceFilters">
              <div class="price-filter">
                <div class="price-inputs">
                  <input type="number" id="priceMin" placeholder="0" min="0" class="price-input">
                  <span></span>
                  <input type="number" id="priceMax" placeholder="1000" min="0" class="price-input">
                </div>
                <div class="price-slider">
                  <div class="slider-track"></div>
                  <input type="range" id="minRange" min="0" max="1000" value="0" class="slider-thumb slider-thumb-min">
                  <input type="range" id="maxRange" min="0" max="1000" value="1000" class="slider-thumb slider-thumb-max">
                </div>
                <div class="price-labels">
                  <span>$0</span>
                  <span>$1000</span>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Debug: Show all categories
          $all_categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
          ]);

          $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0,
            'orderby' => 'name',
            'order' => 'ASC',
          ]);

          if (!empty($categories) && !is_wp_error($categories)) {
            $desired_order = ['ip-phones', 'headsets', 'accessories', 'video-conferencing'];

            usort($categories, function ($a, $b) use ($desired_order) {
              $posA = array_search($a->slug, $desired_order);
              $posB = array_search($b->slug, $desired_order);

              if ($posA === false) $posA = 999;
              if ($posB === false) $posB = 999;

              return $posA - $posB;
            });

            foreach ($categories as $category) {

              if ($category->slug === 'uncategorized') {
                continue;
              }

              $subcategories = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $category->term_id,
                'orderby' => 'name',
                'order' => 'ASC',
              ]);
              $hasSubcategories = !empty($subcategories) && !is_wp_error($subcategories);
          ?>


              <div class="filter-group">
                <?php if ($hasSubcategories): ?>
                  <h4 class="filter-group__title" data-toggle="<?php echo $category->slug; ?>">
                    <label class="filter-checkbox">
                      <input type="checkbox" value="<?php echo $category->slug; ?>" name="category[]" class="all-checkbox" data-category="<?php echo $category->slug; ?>">
                      <span class="checkmark"></span>
                      <div class="checkmark-text"><?php echo $category->name; ?></div>
                      <span class="toggle-icon">
                        <?= displaySvg('src/svg/chevron-down.svg') ?>
                      </span>
                    </label>
                  </h4>
                  <div class="filter-group__content hidden" id="<?php echo $category->slug; ?>Filters">
                    <?php if (!empty($subcategories) && !is_wp_error($subcategories)) {
                      foreach ($subcategories as $subcategory) {

                        // Check if this subcategory has children (grandchildren)
                        $grandchildren = get_terms([
                          'taxonomy' => 'product_cat',
                          'hide_empty' => true,
                          'parent' => $subcategory->term_id,
                          'orderby' => 'name',
                          'order' => 'ASC',
                        ]);

                        $hasChildren = !empty($grandchildren) && !is_wp_error($grandchildren);

                        // Debug: show how many children
                        echo "<!-- Debug: Subcategory '{$subcategory->name}' has " . count($grandchildren) . ' children -->';
                        if ($hasChildren) {
                          foreach ($grandchildren as $gc) {
                            echo "<!-- Debug: Child: {$gc->name} (slug: {$gc->slug}) -->";
                          }
                        }
                    ?>

                        <?php if ($hasChildren) { ?>
                          <!-- Subcategory with children - has checkbox + arrow -->
                          <div class="filter-subgroup">
                            <label class="filter-checkbox expandable" data-toggle="<?php echo $subcategory->slug; ?>">
                              <input type="checkbox" value="<?php echo $subcategory->slug; ?>" name="category[]" class="sub-checkbox" data-parent="<?php echo $category->slug; ?>">
                              <span class="checkmark"></span>
                              <div class="checkmark-text"><?php echo $subcategory->name; ?></div>
                              <span class="toggle-icon">
                                <?= displaySvg('src/svg/chevron-down.svg') ?>
                              </span>
                            </label>
                            <div class="filter-subgroup__content hidden" id="<?php echo $subcategory->slug; ?>Filters">
                              <?php foreach ($grandchildren as $grandchild) { ?>
                                <label class="filter-checkbox grandchild">
                                  <input type="checkbox" value="<?php echo $grandchild->slug; ?>" name="category[]" class="grandchild-checkbox" data-parent="<?php echo $subcategory->slug; ?>" data-grandparent="<?php echo $category->slug; ?>">
                                  <span class="checkmark"></span>
                                  <div class="checkmark-text"><?php echo $grandchild->name; ?></div>
                                </label>
                              <?php } ?>
                            </div>
                          </div>
                        <?php } else { ?>
                          <!-- Subcategory without children - just checkbox -->
                          <label class="filter-checkbox">
                            <input type="checkbox" value="<?php echo $subcategory->slug; ?>" name="category[]" class="sub-checkbox" data-parent="<?php echo $category->slug; ?>">
                            <span class="checkmark"></span>
                            <div class="checkmark-text"><?php echo $subcategory->name; ?></div>
                          </label>
                        <?php } ?>
                    <?php
                      }
                    } ?>
                  </div>
                <?php else: ?>

                  <label class="filter-checkbox">
                    <input type="checkbox" value="<?php echo $category->slug; ?>" name="category[]" class="single-category">
                    <span class="checkmark"></span>
                    <div class="checkmark-text"><?php echo $category->name; ?></div>
                  </label>

                <?php endif; ?>
              </div>
          <?php
            }
          }
          ?>

        </div>
      </aside>
      <div class="shop-main">
        <!-- Header with title and sorting -->
        <div class="shop-header" data-aos="fade-up" data-aos-delay="400">
          <div class="shop-header__left">
            <h2 class="shop-title">Catalog</h2>
            <span class="shop-results-count" id="resultsCount">(<?php echo $products_query->found_posts; ?> results)</span>
          </div>
          <!-- Mobile filters trigger button -->
          <button class="mobile-filters-trigger" id="mobileFiltersBtn">
            <div class="filters-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M3.75 3H14.25C14.8004 3 15.1422 3.00191 15.3906 3.0332C15.5068 3.04785 15.5731 3.06568 15.6104 3.08008C15.6419 3.09227 15.6517 3.10115 15.6562 3.10547L15.6572 3.10645C15.6617 3.1106 15.666 3.11489 15.6738 3.13281C15.6844 3.15699 15.7023 3.20848 15.7168 3.30957C15.7486 3.53135 15.75 3.84093 15.75 4.36035V4.87793C15.75 5.28222 15.7489 5.52921 15.7295 5.7168C15.712 5.88579 15.6841 5.9457 15.6631 5.98047L15.6621 5.98242C15.6397 6.01947 15.5942 6.0788 15.4404 6.18555C15.3573 6.2432 15.258 6.30416 15.1338 6.37695L14.6758 6.6377L12.4912 7.86816H12.4902C12.0481 8.11771 11.7227 8.29569 11.4805 8.50586C11.0151 8.89938 10.6929 9.43622 10.5645 10.0322C10.4952 10.3456 10.5 10.6968 10.5 11.1543V13.1562C10.5 13.8988 10.4975 14.3655 10.4463 14.6904C10.4223 14.8425 10.3932 14.9176 10.375 14.9521C10.3629 14.9751 10.3583 14.9782 10.3516 14.9824C10.3437 14.9874 10.3287 14.9961 10.2812 14.999C10.223 15.0025 10.1224 14.9958 9.95508 14.9561C9.60364 14.8726 9.1333 14.6909 8.40723 14.4072H8.4082C8.05812 14.2702 7.84891 14.1872 7.7002 14.1104C7.63216 14.0752 7.59457 14.0502 7.57422 14.0342C7.55723 14.0208 7.55239 14.014 7.55078 14.0117L7.5498 14.0107C7.54862 14.0091 7.5455 14.0056 7.54102 13.9922C7.53522 13.9747 7.52639 13.9387 7.51855 13.8711C7.5011 13.7205 7.5 13.5154 7.5 13.1572V11.1543C7.5 10.6945 7.50399 10.3423 7.43359 10.0283H7.43457C7.29955 9.41984 6.99246 8.91691 6.51953 8.50684H6.52051C6.27718 8.29554 5.95156 8.11837 5.50879 7.86914L3.32422 6.63867L2.86719 6.37793C2.74278 6.30485 2.64357 6.24333 2.56055 6.18555C2.40682 6.07855 2.36024 6.01896 2.33691 5.98047C2.31582 5.94559 2.28805 5.88579 2.27051 5.7168C2.25107 5.52953 2.25 5.283 2.25 4.87891V4.36133C2.25 3.84147 2.25228 3.53146 2.28418 3.30957C2.29867 3.20882 2.31549 3.15732 2.32617 3.13281C2.33411 3.11471 2.33834 3.10963 2.34277 3.10547C2.34335 3.10492 2.37812 3.0624 2.60938 3.0332C2.85778 3.00188 3.19983 3 3.75 3Z" stroke="#fff" stroke-width="1.5" />
              </svg>
              Filters
            </div>
            <div class="filters-icon">
              <span class="filters-count" id="filtersCount">0</span>
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.605 6.75L9.0525 11.3025L4.5 6.75" stroke="#444444" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
          </button>
          <div class="shop-header__right">
            <div class="shop-sort">
              <div class="custom-dropdown" id="sortDropdown">
                <div class="dropdown-selected" data-value="popularity">
                  <span class="selected-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="9" viewBox="0 0 17 9" fill="none">
                      <path d="M15.75 0.75H0.75M13.5 4.5H3M11.25 8.25H5.25" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
                    </svg>Sort by popularity</span>
                  <svg class="dropdown-arrow" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
                <div class="dropdown-options" style="display: none;">
                  <div class="dropdown-option active" data-value="popularity">Sort by popularity</div>
                  <div class="dropdown-option" data-value="rating">Sort by rating</div>
                  <div class="dropdown-option" data-value="date">Sort by latest</div>
                  <div class="dropdown-option" data-value="price">Sort by price: low to high</div>
                  <div class="dropdown-option" data-value="price-desc">Sort by price: high to low</div>
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="products-container related-products" id="productsContainer">

          <div class="related-products__container" data-aos="fade-up" data-aos-delay="500">
            <div class="related-products__grid" id="productsGrid">
              <?php if ($products_query->have_posts()) {
                while ($products_query->have_posts()) {
                  $products_query->the_post();
                  $product = wc_get_product(get_the_ID());

                  if ($product) {

                    $id = $product->get_id();
                    $variations = $product->is_type('variable') ? $product->get_available_variations() : [];

                    $clean_variations = array_map(function ($v) {
                      return [
                        'variation_id' => $v['variation_id'],
                        'attributes' => $v['attributes'],
                        'display_price' => $v['display_price'],
                      ];
                    }, $variations);
                    $variations_json = esc_attr(wp_json_encode($clean_variations));

                    $img_url = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
                    $title = $product->get_name();
                    $price = $product->get_price_html();
                    $permalink = get_permalink($product->get_id());
              ?>
                    <div class="related-products__item <?php echo esc_attr(implode(' ', wc_get_product_class('', $product))); ?>">

                      <a href="<?php echo $permalink; ?>" class="related-products__link"></a>
                      <div class="related-products__product" data-product_id="<?php echo $id; ?>" data-variations='<?php echo $variations_json; ?>'>

                        <div class="related-products__image">
                          <img src="<?php echo $img_url; ?>" alt="<?php echo esc_attr($title); ?>" class="related-products__img">
                        </div>

                        <div class="related-products__content">
                          <div>
                            <h3 class="related-products__name"><?php echo $title; ?></h3>
                          </div>

                          <?php if (!empty($variations) && $id != 270): ?>
                            <div class="related-products__variations">
                              <?php
                              $variation_keys = array_keys($variations[0]['attributes']);
                              foreach ($variation_keys as $attr_name): ?>
                                <select name="<?php echo esc_attr($attr_name); ?>" class="related-products__variation-select">
                                  <?php
                                  $parsed_options = [];
                                  $unique_values = [];

                                  foreach ($variations as $variation) {
                                    if (isset($variation['attributes'][$attr_name])) {
                                      $val = $variation['attributes'][$attr_name];
                                      if (!in_array($val, $unique_values)) {
                                        $unique_values[] = $val;
                                      }
                                    }
                                  }

                                  foreach ($unique_values as $val) {
                                    foreach ($variations as $variation) {
                                      if (isset($variation['attributes'][$attr_name]) && $variation['attributes'][$attr_name] === $val) {
                                        $price = $variation['display_price'];
                                        $parts = explode('-', $val);
                                        $clean_label = implode(' ', array_slice($parts, 0, 2));
                                        $weight = parse_weight_related($clean_label);
                                        $parsed_options[] = [
                                          'value' => $val,
                                          'label' => ucwords($clean_label) . ' - ' . strip_tags(wc_price($price)),
                                          'weight' => $weight,
                                        ];
                                        break;
                                      }
                                    }
                                  }

                                  usort($parsed_options, fn($a, $b) => $a['weight'] <=> $b['weight']);
                                  foreach ($parsed_options as $i => $opt) {
                                    $selected = $i === 0 ? ' selected' : '';
                                    echo '<option value="' . esc_attr($opt['value']) . '"' . $selected . '>' . esc_html($opt['label']) . '</option>';
                                  }
                                  ?>
                                </select>
                              <?php endforeach;
                              ?>
                            </div>
                            <div class="related-products__bottom">
                            <?php else: ?>
                              <?php if ($id != 270): ?>
                                <div class="related-products__bottom">
                                  <div class="related-products__price-container">
                                    <div class="related-products__price-display">
                                      <?php echo $product->get_price_html(); ?>
                                    </div>
                                  </div>
                                <?php endif; ?>
                              <?php endif; ?>

                              <div class="related-products__actions">
                                <?php if ($id == 270): ?>
                                  <!-- Sample Pack - View Product button -->
                                  <span class="related-products__view-product related-products__btn">
                                    <span class="btn-content">
                                      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon">
                                        <path d="M8.14295 13.2854C10.9833 13.2854 13.2859 10.983 13.2859 8.14271C13.2859 5.30247 10.9833 3 8.14295 3C5.30258 3 3 5.30247 3 8.14271C3 10.983 5.30258 13.2854 8.14295 13.2854Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.9973 15.0001L12.4258 12.4287" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                      </svg>
                                      <?= displaySvg('src/svg/arrow.svg') ?>
                                      <span class="btn-text">View Product</span>
                                    </span>
                                  </span>
                                <?php else: ?>
                                  <!-- Regular Add to Cart button -->
                                  <button type="button" class="related-products__add-to-cart related-products__btn" data-product-id="<?php echo $id; ?>">
                                    <span class="btn-content">
                                      <?= displaySvg('src/svg/arrow.svg') ?>
                                    </span>
                                    <span class="btn-loading" style="display: none;">
                                      <?= displaySvg('src/svg/spinner.svg') ?>
                                    </span>
                                    <span class="btn-success" style="display: none;">
                                      <?= displaySvg('src/svg/check-success.svg') ?>
                                    </span>
                                  </button>
                                <?php endif; ?>
                              </div>
                                </div>
                            </div>
                        </div>
                        <?php do_action('woocommerce_after_shop_loop_item'); ?>
                      </div>
                <?php
                  }
                }
                wp_reset_postdata();
              } else {
                echo '<p>No products found</p>';
              } ?>
                    </div>
            </div>


            <div class="skeleton-grid" id="skeletonGrid">
              <?php for ($i = 0; $i < 16; $i++): ?>
                <div class="skeleton-card">
                  <div class="skeleton-card__image"></div>
                  <div class="skeleton-card__content">
                    <div class="skeleton-card__title"></div>
                    <div class="skeleton-card__title"></div>
                    <div class="skeleton-card__bottom">
                      <div class="skeleton-card__price"></div>
                      <div class="skeleton-card__btn"></div>
                    </div>
                  </div>
                </div>
              <?php endfor; ?>
            </div>

            <div class="no-products" id="noProducts" style="display: none;">
              <div class="no-products__content">
                <h3 class="no-products__title">No products found</h3>
                <p class="no-products__text simple-text">Try changing the filters or clearing them</p>
                <button class="no-products__button" id="clearFiltersButton">Clear filters</button>
              </div>
            </div>
          </div>

          <nav class="shop-pagination" id="shopPagination">
            <?php
            $total_products = $products_query->found_posts;
            $max_pages = (int) $products_query->max_num_pages;
            $current_page = 1;
            $range = 2;

            if ($max_pages > 1):

              $start = max(1, $current_page - $range);
              $end = min($max_pages, $current_page + $range);
            ?>
              <ul class="page-numbers">

                <!-- PREV -->
                <li class="shop-pagination__arrow shop-pagination__arrow--prev">
                  <a href="#"
                    class="page-number prev <?php echo $current_page === 1 ? 'disabled' : ''; ?>"
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
                    <?php if ($i === $current_page): ?>
                      <span class="current"><?php echo $label; ?></span>
                    <?php else: ?>
                      <a href="#" class="page-number" data-page="<?php echo $i; ?>">
                        <?php echo $label; ?>
                      </a>
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
                  <a href="#"
                    class="page-number next <?php echo $current_page === $max_pages ? 'disabled' : ''; ?>"
                    data-page="<?php echo min($max_pages, $current_page + 1); ?>">
                    <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M13.7536 7.65358L1 7.65358M1 7.65358L7.65404 0.999534M1 7.65358L7.65404 14.3076"
                        stroke="#8B8A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </a>
                </li>

              </ul>
            <?php
            endif;
            ?>
          </nav>

        </div>
      </div>
  </section>
</main>

<!-- Mobile Filters Modal -->
<div class="mobile-filters-modal" id="mobileFiltersModal">
  <div class="mobile-filters-content">
    <div class="mobile-filters-header">
      <h3>Filters</h3>
      <div class="mobile-filters-header__actions">
        <button class="filters__clear" id="mobileClearAllFilters">Clear all</button>
        <button class="close-filters" id="closeFilters">
          <?= displaySvg('src/svg/close.svg') ?>
        </button>
      </div>
    </div>


    <div class="filters">
      <div class="filter-group price-filter-group active">
        <h4 class="filter-group__title" data-toggle="price">
          Price ($)
          <span class="toggle-icon">
            <?= displaySvg('src/svg/chevron-down.svg') ?>
          </span>
        </h4>
        <div class="filter-group__content visible" id="mobilePriceFilters">
          <div class="price-filter">
            <div class="price-inputs">
              <input type="number" id="mobilePriceMin" placeholder="0" min="0" class="price-input">
              <span></span>
              <input type="number" id="mobilePriceMax" placeholder="1000" min="0" class="price-input">
            </div>
            <div class="price-slider">
              <div class="slider-track"></div>
              <input type="range" id="mobileMinRange" min="0" max="1000" value="0" class="slider-thumb slider-thumb-min">
              <input type="range" id="mobileMaxRange" min="0" max="1000" value="1000" class="slider-thumb slider-thumb-max">
            </div>
            <div class="price-labels">
              <span>$0</span>
              <span>$1000</span>
            </div>
          </div>
        </div>
      </div>
      <?php
      // Debug: Show all categories
      $all_categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
      ]);

      $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
      ]);

      if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {

          $subcategories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $category->term_id,
            'orderby' => 'name',
            'order' => 'ASC',
          ]);
          $hasSubcategories = !empty($subcategories) && !is_wp_error($subcategories);
      ?>


          <div class="filter-group">

            <?php if ($hasSubcategories): ?>

              <h4 class="filter-group__title" data-toggle="<?php echo $category->slug; ?>">
                <label class="filter-checkbox">
                  <input type="checkbox" value="<?php echo $category->slug; ?>" name="category[]" class="all-checkbox" data-category="<?php echo $category->slug; ?>">
                  <span class="checkmark"></span>
                  <div class="checkmark-text"><?php echo $category->name; ?></div>
                  <span class="toggle-icon">
                    <?= displaySvg('src/svg/chevron-down.svg') ?>
                  </span>
                </label>
              </h4>

              <div class="filter-group__content hidden" id="<?php echo $category->slug; ?>Filters">
                <?php foreach ($subcategories as $subcategory) {

                  $grandchildren = get_terms([
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => $subcategory->term_id,
                  ]);

                  $hasChildren = !empty($grandchildren) && !is_wp_error($grandchildren);
                ?>

                  <?php if ($hasChildren): ?>
                    <div class="filter-subgroup">
                      <label class="filter-checkbox expandable" data-toggle="<?php echo $subcategory->slug; ?>">
                        <input type="checkbox" value="<?php echo $subcategory->slug; ?>" name="category[]" class="sub-checkbox" data-parent="<?php echo $category->slug; ?>">
                        <span class="checkmark"></span>
                        <div class="checkmark-text"><?php echo $subcategory->name; ?></div>
                        <span class="toggle-icon">
                          <?= displaySvg('src/svg/chevron-down.svg') ?>
                        </span>
                      </label>

                      <div class="filter-subgroup__content hidden" id="<?php echo $subcategory->slug; ?>Filters">
                        <?php foreach ($grandchildren as $grandchild): ?>
                          <label class="filter-checkbox grandchild">
                            <input type="checkbox" value="<?php echo $grandchild->slug; ?>" name="category[]" class="grandchild-checkbox" data-parent="<?php echo $subcategory->slug; ?>" data-grandparent="<?php echo $category->slug; ?>">
                            <span class="checkmark"></span>
                            <div class="checkmark-text"><?php echo $grandchild->name; ?></div>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php else: ?>

                    <label class="filter-checkbox">
                      <input type="checkbox" value="<?php echo $subcategory->slug; ?>" name="category[]" class="sub-checkbox" data-parent="<?php echo $category->slug; ?>">
                      <span class="checkmark"></span>
                      <div class="checkmark-text"><?php echo $subcategory->name; ?></div>
                    </label>

                  <?php endif; ?>

                <?php
                } ?>

              </div>

            <?php else: ?>
              <label class="filter-checkbox">
                <input type="checkbox" value="<?php echo $category->slug; ?>" name="category[]" class="single-category">
                <span class="checkmark"></span>
                <div class="checkmark-text"><?php echo $category->name; ?></div>

              </label>

            <?php endif; ?>

          </div>

      <?php
        }
      }
      ?>

    </div>


    <div class="mobile-filters-footer">
      <button class="show-results-btn btn" id="showResultsBtn">
        Show results (<span id="mobileResultsCount"><?php echo $products_query->found_posts; ?></span>)
      </button>
    </div>
  </div>
</div>

<?php
get_template_part('template-blocks/action');

get_footer();


?>