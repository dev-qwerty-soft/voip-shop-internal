<?php
$product = wc_get_product(get_the_ID());

if (! $product || ! $product instanceof WC_Product) {
    return;
}

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
    <a href="<?php echo esc_url($permalink); ?>" class="related-products__link"></a>
    <div class="related-products__product" data-product_id="<?php echo $id; ?>" data-variations='<?php echo $variations_json; ?>'>
        <div class="related-products__image">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" class="related-products__img">
        </div>

        <div class="related-products__content">
            <div>
                <h3 class="related-products__name"><?php echo esc_html($title); ?></h3>
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
    </div>