<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit();

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

get_template_part('template-blocks/breadcrumbs');
?>

<section id="product-<?php the_ID(); ?>" <?php wc_product_class('custom-product', $product); ?>>
	<div class="container">
		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */

		do_action('woocommerce_before_single_product_summary'); ?>

		<div class="summary entry-summary" data-aos="fade-up" data-aos-delay="500">
			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */

			do_action('woocommerce_single_product_summary'); ?>

			<?php if ($product->is_type('variable')): ?>
				<div class="product-configuration">
					<h3 class="product-configuration__title">Product Configuration</h3>
				</div>
			<?php endif; ?>

			<?php
			// Get ACF fields
			$product_description = get_field('product_description');
			$key_features = get_field('key_features');
			$details = get_field('details');

			// Check if at least one tab has content
			$has_tabs = !empty($product_description) || !empty($key_features) || !empty($details);

			if ($has_tabs): ?>
				<div class="custom-product-tabs">
					<div class="tabs-navigation">
						<div class="tabs-buttons">
							<?php
							$first_tab = true;
							if (!empty($product_description)): ?>
								<button class="tab-button<?php echo $first_tab ? ' active' : ''; ?>" data-tab="description">Description</button>
							<?php $first_tab = false;
							endif;

							if (!empty($key_features)): ?>
								<button class="tab-button<?php echo $first_tab ? ' active' : ''; ?>" data-tab="key-features">Key Features</button>
							<?php $first_tab = false;
							endif;

							if (!empty($details)): ?>
								<button class="tab-button<?php echo $first_tab ? ' active' : ''; ?>" data-tab="details">Details</button>
							<?php $first_tab = false;
							endif;
							?>
						</div>
					</div>

					<div class="tabs-content">
						<?php
						$first_tab = true;
						if (!empty($product_description)): ?>
							<div class="tab-panel<?php echo $first_tab ? ' active' : ''; ?>" id="description">
								<div class="product-description">
									<?php echo wpautop($product_description); ?>
								</div>
							</div>
						<?php $first_tab = false;
						endif;

						if (!empty($key_features)): ?>
							<div class="tab-panel<?php echo $first_tab ? ' active' : ''; ?>" id="key-features">
								<div class="product-key-features">
									<?php echo wpautop($key_features); ?>
								</div>
							</div>
						<?php $first_tab = false;
						endif;

						if (!empty($details)): ?>
							<div class="tab-panel<?php echo $first_tab ? ' active' : ''; ?>" id="details">
								<div class="product-details">
									<?php echo wpautop($details); ?>
								</div>
							</div>
						<?php $first_tab = false;
						endif;
						?>
					</div>
				</div>
			<?php endif;
			?>
		</div>

	</div>

</section>
<?php
/**
 * Hook: woocommerce_after_single_product_summary.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */ 
do_action('woocommerce_after_single_product_summary'); ?>

<?php do_action('woocommerce_after_single_product'); ?>