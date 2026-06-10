<?php
/**
 * Template Name: Cart Page
 * Template Post Type: page
 */
get_header(); ?>
<?php if (function_exists('WC')) {
  echo do_shortcode('[woocommerce_cart]');
} else {
  echo '<p>WooCommerce is not active.</p>';
} ?>
<?php get_footer(); ?>
