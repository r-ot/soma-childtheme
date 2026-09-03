<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = array();
}

$defaults = array(
	'case'         => 'default',
	'eyebrow'      => '',
	'breadcrumbs'  => '',
	'title'        => '',
	'text'         => '',
	'button_label' => '',
	'button_url'   => '',
	'image_id'     => 0,
	'post_id' => 0,
	'xr_manifest' => '',
	'variant' => '',
	'cart_item_count'   => 0,
	// 'usps' => [],
	//testweise
	'usps' => [
		[
			'key'   => 'forest',
			'icon'  => 'forest',
			'label' => 'Kompetenz in',
			'title' => 'Forsttechnik',
		],
		[
			'key'   => 'agriculture',
			'icon'  => 'tractor',
			'label' => 'Expertise in',
			'title' => 'Landtechnik',
		],
		[
			'key'   => 'personal',
			'icon'  => 'personal',
			'label' => 'persönlich',
			'title' => 'menschlich',
		],
	],
	// 'dimensions' => [],
	'dimensions_endpoint' => '',
);

$hero = wp_parse_args( $args, $defaults );


//post id
$post_id = isset( $hero['post_id'] ) && is_numeric( $hero['post_id'] ) && (int) $hero['post_id'] > 0
	? (int) $hero['post_id']
	: 0;


$output_inner_content = '';
$output_media_content = '';
$output_usp_content = '';

//usp s
if ( ! empty( $hero['usps'] ) && is_array( $hero['usps'] ) ) {
	foreach ( $hero['usps'] as $usp ) {
		$usp_label = ! empty( $usp['label'] ) ? $usp['label'] : '';
		$usp_title = ! empty( $usp['title'] ) ? $usp['title'] : '';

		if ( empty( $usp_label ) && empty( $usp_title ) ) {
			continue;
		}

		$output_usp_content .=
			'<div class="rbf-hero__usp-item">'.
				'<div class="rbf-hero__usp-icon" aria-hidden="true"></div>'.
				'<div class="rbf-hero__usp-content">'.
					( ! empty( $usp_label ) ? '<div class="rbf-hero__usp-label">' . esc_html( $usp_label ) . '</div>' : '' ).
					( ! empty( $usp_title ) ? '<div class="rbf-hero__usp-title">' . esc_html( $usp_title ) . '</div>' : '' ).
				'</div>'.
			'</div>';
	}
}
//eybrow / crumbs
//*eyebrow / crumbs*
$eyebrow = $hero['eyebrow'] ?? '';

if (
	$eyebrow === 'crumbs'
	&& ! empty( $hero['breadcrumbs'] )
	&& is_array( $hero['breadcrumbs'] )
) {

	$output_inner_content .=
		'<div class="rbf-hero__eyebrow">'.
			'<i class="rbf-hero__eyebrow-mark" aria-hidden="true"></i>'.
			'<nav class="rbf-hero__breadcrumbs" aria-label="Breadcrumb">';

	foreach ( $hero['breadcrumbs'] as $index => $breadcrumb ) {

		$label = $breadcrumb['label'] ?? '';
		$url = $breadcrumb['url'] ?? '';

		if ( $index > 0 ) {
			$output_inner_content .=
				'<span class="rbf-hero__breadcrumb-separator" aria-hidden="true">/</span>';
		}

		if ( ! empty( $url ) ) {
			$output_inner_content .=
				'<a href="' . esc_url( $url ) . '">'.
					esc_html( $label ).
				'</a>';
		} else {
			$output_inner_content .=
				'<span>'.
					esc_html( $label ).
				'</span>';
		}
	}

	$output_inner_content .=
			'</nav>'.
		'</div>';

} elseif ( is_string( $eyebrow ) && trim( $eyebrow ) !== '' ) {

	$output_inner_content .=
		'<div class="rbf-hero__eyebrow">'.
			esc_html( $eyebrow ).
		'</div>';
}


//title
if ( ! empty( $hero['title'] ) ) {
	$output_inner_content .= '<h1 class="rbf-hero__title">' . esc_html( $hero['title'] ) . '</h1>';
}
//text
if ( ! empty( $hero['text'] ) ) {
	$output_inner_content .= '<div class="rbf-hero__text">' . wp_kses_post( $hero['text'] ) . '</div>';
}
//dimensions
// if ( ! empty( $hero['dimensions'] ) && is_array( $hero['dimensions'] ) ) {
// 	$output_inner_content .=
// 		'<div class="rbf-hero__dimensions">'.
// 			'<div class="rbf-hero__dimensions-label">'.
// 				esc_html__( 'Verfügbare Reifendimensionen', 'twentytwentyfive-child' ).
// 			'</div>'.
// 			'<div class="rbf-hero__dimensions-list">';
// 	foreach ( $hero['dimensions'] as $dimension ) {
// 		$dimension = trim( (string) $dimension );
// 		if ( '' === $dimension ) {
// 			continue;
// 		}
// 		$output_inner_content .=
// 			'<span class="rbf-hero__dimension">'.
// 				esc_html( $dimension ).
// 			'</span>';
// 	}
// 	$output_inner_content .=
// 			'</div>'.
// 		'</div>';
// }
if ( ! empty( $hero['dimensions_endpoint'] ) ) {

	$output_inner_content .=
		'<div class="rbf-hero__dimensions" data-rbf-family-dimensions data-endpoint="' . esc_url( $hero['dimensions_endpoint'] ) . '" data-visible-count="3">'.
			'<div class="rbf-hero__dimensions-label">'.
				esc_html__( 'Verfügbare Reifendimensionen', 'twentytwentyfive-child' ).
			'</div>'.

			'<div class="rbf-hero__dimensions-list" data-rbf-family-dimensions-list>'.

				'<template data-rbf-family-dimension-template>'.
					'<span class="rbf-hero__dimension" data-rbf-family-dimension-item></span>'.
				'</template>'.

				'<button class="rbf-hero__dimensions-toggle" type="button" hidden data-rbf-family-dimensions-toggle data-collapsed-text="…" data-expanded-text="–" data-label-expand="' . esc_attr__( 'Weitere Reifendimensionen anzeigen', 'twentytwentyfive-child' ) . '" data-label-collapse="' . esc_attr__( 'Reifendimensionen reduzieren', 'twentytwentyfive-child' ) . '" aria-expanded="false" aria-label="' . esc_attr__( 'Weitere Reifendimensionen anzeigen', 'twentytwentyfive-child' ) . '">…</button>'.

			'</div>'.
		'</div>';
}


//*cart item count*

if ( ! empty( $hero['cart_item_count'] ) ) {

	$cart_item_count = (int) $hero['cart_item_count'];

	$product_label = $cart_item_count === 1
		? 'Produkt'
		: 'Produkte';

	$output_inner_content .=
		'<div class="rbf-hero__cart-count" data-rbf-cart-count>'.
			'<strong class="rbf-hero__cart-count-value">'.
				'<span data-rbf-cart-count-value>' . esc_html( $cart_item_count ) . '</span> '.
				'<span data-rbf-cart-count-product-label>' . esc_html( $product_label ) . '</span>'.
			'</strong>'.
			'<span class="rbf-hero__cart-count-label">im Warenkorb</span>'.
		'</div>';
}



//btn
if ( ! empty( $hero['button_label'] ) && ! empty( $hero['button_url'] ) ) {
	$output_inner_content .= '<a class="rbf-hero__button" href="' . esc_url( $hero['button_url'] ) . '">'.
		esc_html( $hero['button_label'] ).
	'</a>';
}


//img
$image_id = isset( $hero['image_id'] ) && is_numeric( $hero['image_id'] ) && (int) $hero['image_id'] > 0
	? (int) $hero['image_id']
	: 0;


// do_action('qm/debug', [
// 	'hero_case'              => $hero['case'] ?? null,
// 	'post_id'                => $post_id,
// 	'image_id'               => $image_id,
// 	'is_singular'            => is_singular(),
// 	'queried_object_id'      => get_queried_object_id(),
// 	'post_thumbnail_id'      => !empty($post_id) ? get_post_thumbnail_id($post_id) : null,
// ]);
// if ( empty( $image_id ) && ! empty( $post_id ) ) {
// 	$image_id = get_post_thumbnail_id( $post_id );
// }
//product families brauchen hier andere logik daher haben wir diesen auskommentiert!!!

//neu 0.3.3
if ( empty( $image_id ) && ! empty( $post_id ) && is_singular() ) {
	$image_id = get_post_thumbnail_id( $post_id );
}



if ( empty( $image_id ) && empty( $post_id ) && is_singular() ) {
	$queried_object_id = get_queried_object_id();

	if ( is_int( $queried_object_id ) && $queried_object_id > 0 ) {
		$image_id = get_post_thumbnail_id( $queried_object_id );
	}
}

if ( empty( $image_id ) && empty( $post_id ) ) {
	$queried_object_id = get_queried_object_id();

	if ( is_int( $queried_object_id ) && $queried_object_id > 0 ) {
		$image_id = get_post_thumbnail_id( $queried_object_id );
	}
}

if ( ! empty( $image_id ) ) {
	$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$image_title = get_the_title( $image_id );
	$image_obj_pos = get_post_meta( $image_id, 'obj_pos', true );
	$image_attrs = [
		'class' => 'rbf-hero__image',
		'alt'   => $image_alt,
		'title' => $image_title,
	];
	if ( ! empty( $image_obj_pos ) ) {
		$image_attrs['style'] = 'object-position: ' . esc_attr( $image_obj_pos ) . ';';
	}
	$output_media_content =
		'<figure class="rot-img-relative">'.
			wp_get_attachment_image(
				$image_id,
				'full',
				false,
				$image_attrs
			).
		'</figure>';
}

$output_product_media_content='';
if ( ! empty( $hero['xr_manifest'] ) ) {

	$output_product_media_content .=
		'<div class="rbf-hero__xr-viewer" data-rbf-xr-viewer data-manifest="' . esc_url( $hero['xr_manifest'] ) . '">'.
			'<img class="rbf-hero__xr-image" data-rbf-xr-image src="" alt="" draggable="false" />';

			if ( is_user_logged_in() ) {
				$output_product_media_content .=
					'<div class="rbf-hero__xr-status" data-rbf-xr-status>360°-Ansicht wird geladen …</div>';
			}

			$output_product_media_content .=
		'</div>';
}



/*OUTPUT*/
/*OUTPUT*/
/*OUTPUT*/
/*OUTPUT*/

$output =
	'<section class="rbf-hero alignfull rbf-hero--' . esc_attr( $hero['case'] ) . '">'.

		'<div class="rbf-hero__stage">'.

			'<div class="rbf-hero__media">'.
				$output_media_content.
			'</div>'.

			'<div class="rbf-hero__overlay" aria-hidden="true"></div>'.

			'<div class="rbf-hero__container container">'.
				'<div class="rbf-hero__content">'.
					$output_inner_content.
				'</div>'.
				$output_product_media_content.
			'</div>'.

		'</div>'.

		( ! empty( $output_usp_content )
			? '<div class="rbf-hero__usp-bar">'.
				'<div class="rbf-hero__usp-container container">'.
					$output_usp_content.
				'</div>'.
			'</div>'
			: ''
		).

	'</section>';

echo $output;