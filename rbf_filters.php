<?php

defined('ABSPATH') || exit;

add_filter('rbf_product_family_template', function($template) {

	$theme_template = get_stylesheet_directory()
		. '/template-parts/product-family-item.php';

	if (is_readable($theme_template)) {
		return $theme_template;
	}

	return $template;
});


//filter for cart checkout prepend header hero
add_filter('render_block_core/post-content', function($block_content, $block) {

	if (
		!function_exists('is_cart')
		|| (!is_cart() && !is_checkout())
	) {
		return $block_content;
	}

	$hero = do_shortcode('[rbf_hero]');

	if ($hero === '') {
		return $block_content;
	}

	return $hero . $block_content;

}, 10, 2);


/**
 * WooCommerce Cart / Checkout:
 * Woo's block templates render their own core/post-title before post-content.
 * These pages use the RBF compact hero, which already renders the page title
 * as the single H1. Suppress the Woo/Block-Theme post-title here to avoid
 * duplicate H1 output without overriding WooCommerce page templates.
 *
 * Keep this in sync with the automatic compact hero handling.
 */
add_filter('render_block_core/post-title', function($block_content, $block) {

	$is_cart = function_exists('is_cart') && is_cart();
	$is_checkout = function_exists('is_checkout') && is_checkout();

	if ($is_cart || $is_checkout) {
		return '';
	}

	return $block_content;

}, 10, 2);