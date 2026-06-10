<?php

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined('ABSPATH') || exit();

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
  return;
}

global $product;

$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
?>

<div class="custom-product__gallery">

	<!-- Main slider -->
	<div class="swiper custom-product__swiper" data-aos="fade-up" data-aos-delay="200">
		<div class="swiper-wrapper">
			<?php
   if ($post_thumbnail_id) {
     $main_img_url = wp_get_attachment_image_url($post_thumbnail_id, 'woocommerce_single');
     echo '<div class="swiper-slide"><img src="' . esc_url($main_img_url) . '" alt="' . esc_attr(get_the_title($post_thumbnail_id)) . '" /></div>';
   }

   if ($gallery_ids) {
     foreach ($gallery_ids as $img_id) {
       $img_url = wp_get_attachment_image_url($img_id, 'woocommerce_single');
       echo '<div class="swiper-slide"><img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title($img_id)) . '" /></div>';
     }
   }
   ?>
		</div>

		<!-- Navigation -->
		<div class="custom-product__arrow custom-product__arrow-next">
			<?= displaySvg('src/svg/chevron.svg') ?>
		</div>
		<div class="custom-product__arrow custom-product__arrow-prev">
			<?= displaySvg('src/svg/chevron.svg') ?>
		</div>
	</div>

	<!-- Thumbnails slider -->
	<div class="swiper custom-product__thumb" data-aos="fade-up" data-aos-delay="300">
		<div class="swiper-wrapper">
			<?php
   if ($post_thumbnail_id) {
     $thumb_url = wp_get_attachment_image_url($post_thumbnail_id, 'thumbnail');
     echo '<div class="swiper-slide"><img src="' . esc_url($thumb_url) . '" alt="' . esc_attr(get_the_title($post_thumbnail_id)) . '" /></div>';
   }
   if ($gallery_ids) {
     foreach ($gallery_ids as $img_id) {
       $thumb_url = wp_get_attachment_image_url($img_id, 'thumbnail');
       echo '<div class="swiper-slide"><img src="' . esc_url($thumb_url) . '" alt="' . esc_attr(get_the_title($img_id)) . '" /></div>';
     }
   }
   ?>
		</div>
	</div>

</div>