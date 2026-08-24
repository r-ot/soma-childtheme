<?php

defined('ABSPATH') || exit;

if (!defined('WP_DEBUG') || !WP_DEBUG) {
	return;
}

// add_action('wp', function() {

// 	if (!is_tax('product_family')) {
// 		return;
// 	}

// 	if (
// 		!isset($_GET['rbf_family_rebuild'])
// 		|| '1' !== sanitize_text_field(wp_unslash($_GET['rbf_family_rebuild']))
// 	) {
// 		return;
// 	}

// 	if (!current_user_can('manage_options')) {
// 		return;
// 	}

// 	$term = get_queried_object();

// 	if (!$term instanceof WP_Term) {
// 		return;
// 	}

// 	$rebuild = RbfSiteCoreFamilyIndex::rebuild($term->term_id);

// 	if (is_wp_error($rebuild)) {
// 		do_action('qm/error', [
// 			'rbf_debug' => 'family index rebuild failed',
// 			'error'     => $rebuild->get_error_message(),
// 		]);

// 		return;
// 	}

// 	$stored_index = RbfSiteCoreFamilyIndex::get($term->term_id);

// 	do_action('qm/debug', [
// 		'rbf_debug' => 'family index rebuilt',
// 		'term_id'   => $term->term_id,
// 		'term_name' => $term->name,
// 		'index'     => $stored_index,
// 	]);

// });