<?php

defined('ABSPATH') || exit;

if (!defined('WP_DEBUG') || !WP_DEBUG) {
	return;
}

// add_action('admin_footer', function() {

// 	if (!function_exists('wc_get_product')) {
// 		return;
// 	}

// 	$screen = get_current_screen();

// 	if (
// 		!$screen
// 		|| $screen->post_type !== 'product'
// 		|| $screen->base !== 'post'
// 	) {
// 		return;
// 	}

// 	$product_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;

// 	if (!$product_id) {
// 		return;
// 	}

// 	$product = wc_get_product($product_id);

// 	if (!$product) {
// 		return;
// 	}

// 	$debug = [];

// 	foreach ($product->get_attributes() as $index => $attribute) {

// 		$debug[] = [
// 			'array_key'      => $index,
// 			'name'           => $attribute->get_name(),
// 			'sanitized_name' => sanitize_title($attribute->get_name()),
// 			'id'             => $attribute->get_id(),
// 			'is_taxonomy'    => $attribute->is_taxonomy(),
// 			'options'        => $attribute->get_options(),
// 		];
// 	}

// 	echo '<pre style="margin:20px;margin-left:180px;padding:20px;background:#242424;color:#7CFC00;font-family:monospace;font-size:12px;line-height:1.5;white-space:pre-wrap;">';
// 	echo esc_html(print_r($debug, true));
// 	echo '</pre>';
// });


// add_action('admin_footer', function() {

// 	if (
// 		!class_exists('RbfSiteCoreDataKeys')
// 		|| !class_exists('RbfSiteCoreProducts')
// 	) {
// 		return;
// 	}

// 	$screen = get_current_screen();

// 	if (
// 		!$screen
// 		|| $screen->post_type !== 'product'
// 		|| $screen->base !== 'post'
// 	) {
// 		return;
// 	}

// 	$product_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;

// 	if (!$product_id) {
// 		return;
// 	}

// 	$debug = [
// 		'matching_key' => RbfSiteCoreDataKeys::get_matching_attribute_key(
// 			'dimension',
// 			RbfSiteCoreDataKeys::ATTRIBUTE_TIRE_DIMENSION
// 		),
// 		'aggregated' => RbfSiteCoreProducts::get_attribute_options_by_products(
// 			[$product_id],
// 			[
// 				RbfSiteCoreDataKeys::ATTRIBUTE_TIRE_DIMENSION,
// 				RbfSiteCoreDataKeys::ATTRIBUTE_CHAIN_STRENGTH,
// 			]
// 		),
// 	];

// 	echo '<pre style="margin:20px;margin-left:180px;padding:20px;background:#242424;color:#7CFC00;font-family:monospace;font-size:12px;line-height:1.5;">';
// 	echo esc_html(print_r($debug, true));
// 	echo '</pre>';
// });


// add_action('admin_footer', function() {

// 	if (
// 		!class_exists('RbfSiteCoreDataKeys')
// 		|| !class_exists('RbfSiteCoreProducts')
// 	) {
// 		return;
// 	}

// 	$screen = get_current_screen();

// 	if (
// 		!$screen
// 		|| $screen->taxonomy !== 'product_family'
// 		|| $screen->base !== 'term'
// 	) {
// 		return;
// 	}

// 	$term_id = isset($_GET['tag_ID']) ? (int) $_GET['tag_ID'] : 0;

// 	if (!$term_id) {
// 		return;
// 	}

// 	$product_ids = RbfSiteCoreProducts::get_ids_by_term(
// 		RbfSiteCoreDataKeys::TAXONOMY_PRODUCT_FAMILY,
// 		$term_id
// 	);

// 	$debug = [
// 		'term_id' => $term_id,
// 		'product_ids' => $product_ids,
// 		'aggregated' => RbfSiteCoreProducts::get_attribute_options_by_products(
// 			$product_ids,
// 			[
// 				RbfSiteCoreDataKeys::ATTRIBUTE_TIRE_DIMENSION,
// 				RbfSiteCoreDataKeys::ATTRIBUTE_CHAIN_STRENGTH,
// 			]
// 		),
// 	];

// 	echo '<pre style="margin:20px; margin-left:180px;padding:20px;background:#242424;color:#7CFC00;font-family:monospace;font-size:12px;line-height:1.5;">';
// 	echo esc_html(print_r($debug, true));
// 	echo '</pre>';
// });