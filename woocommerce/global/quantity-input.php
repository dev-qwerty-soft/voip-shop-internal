<?php

/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 */

defined('ABSPATH') || exit();

/* translators: %s: Quantity. */
$label = !empty($args['product_name']) ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($args['product_name'])) : esc_html__('Quantity', 'woocommerce');
?>
<div class="quantity">
    <button type="button" class="quantity-btn quantity-minus" aria-label="<?php esc_attr_e('Decrease quantity', 'woocommerce'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="2" viewBox="0 0 12 2" fill="none">
            <path d="M1 1H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        </svg>
    </button>

    <?php do_action('woocommerce_before_quantity_input_field'); ?>

    <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_attr($label); ?></label>
    <input
        type="<?php echo esc_attr($type); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
        id="<?php echo esc_attr($input_id); ?>"
        class="<?php echo esc_attr(join(' ', (array) $classes)); ?>"
        name="<?php echo esc_attr($input_name); ?>"
        value="<?php echo esc_attr($input_value); ?>"
        aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>"
        size="4"
        min="<?php echo esc_attr($min_value); ?>"
        max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
        <?php if (!$readonly): ?>
        step="<?php echo esc_attr($step); ?>"
        placeholder="<?php echo esc_attr($placeholder); ?>"
        inputmode="<?php echo esc_attr($inputmode); ?>"
        autocomplete="<?php echo esc_attr(isset($autocomplete) ? $autocomplete : 'on'); ?>"
        <?php endif; ?> />

    <?php do_action('woocommerce_after_quantity_input_field'); ?>

    <button type="button" class="quantity-btn quantity-plus" aria-label="<?php esc_attr_e('Increase quantity', 'woocommerce'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
            <path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        </svg>
    </button>
</div>