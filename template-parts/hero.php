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
	'title'        => '',
	'text'         => '',
	'button_label' => '',
	'button_url'   => '',
	'image_id'     => 0,
	'post_id' => 0,
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
if ( ! empty( $hero['eyebrow'] ) ) {
	$output_inner_content .= '<div class="rbf-hero__eyebrow">' . esc_html( $hero['eyebrow'] ) . '</div>';
}
//title
if ( ! empty( $hero['title'] ) ) {
	$output_inner_content .= '<h1 class="rbf-hero__title">' . esc_html( $hero['title'] ) . '</h1>';
}
//text
if ( ! empty( $hero['text'] ) ) {
	$output_inner_content .= '<div class="rbf-hero__text">' . wp_kses_post( $hero['text'] ) . '</div>';
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

if ( empty( $image_id ) && ! empty( $post_id ) ) {
	$image_id = get_post_thumbnail_id( $post_id );
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



// $output =
// 	'<section class="rbf-hero rbf-hero--' . esc_attr( $hero['case'] ) . '">'.
// 		'<div class="rbf-hero__stage">'.
// 			'<div class="rbf-hero__shape rbf-hero__shape--left"></div>'.
// 			'<div class="rbf-hero__shape rbf-hero__shape--right"></div>'.
// 			'<div class="rbf-hero__container container">'.
// 				'<div class="rbf-hero__content">'.
// 					$output_inner_content.
// 				'</div>'.
// 				'<div class="rbf-hero__media">'.
// 					$output_media_content.
// 				'</div>'.
// 			'</div>'.
// 		'</div>'.
// 	'</section>';

// echo $output;
$output =
	'<section class="rbf-hero rbf-hero--' . esc_attr( $hero['case'] ) . '">'.

		'<div class="rbf-hero__stage">'.

			'<div class="rbf-hero__media">'.
				$output_media_content.
			'</div>'.

			'<div class="rbf-hero__overlay" aria-hidden="true"></div>'.

			'<div class="rbf-hero__container container">'.
				'<div class="rbf-hero__content">'.
					$output_inner_content.
				'</div>'.
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