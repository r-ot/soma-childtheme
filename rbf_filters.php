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