<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = [];
}


/*
 * Basic template data
 */
$title = ! empty( $args['title'] ) ? $args['title'] : 'Angebot';
$text = ! empty( $args['text'] ) ? $args['text'] : '';


/*
 * Fonts
 */
$font_regular_path = realpath(
	get_stylesheet_directory() . '/assets/fonts/static/Jost-Regular.ttf'
);

$font_bold_path = realpath(
	get_stylesheet_directory() . '/assets/fonts/static/Jost-Bold.ttf'
);

$font_regular_uri = '';
$font_bold_uri = '';

if ( $font_regular_path ) {
	$font_regular_uri = str_replace( '\\', '/', $font_regular_path );

	if ( preg_match( '/^[A-Za-z]:\//', $font_regular_uri ) ) {
		$font_regular_uri = 'file:///' . $font_regular_uri;
	} else {
		$font_regular_uri = 'file://' . $font_regular_uri;
	}
}

if ( $font_bold_path ) {
	$font_bold_uri = str_replace( '\\', '/', $font_bold_path );

	if ( preg_match( '/^[A-Za-z]:\//', $font_bold_uri ) ) {
		$font_bold_uri = 'file:///' . $font_bold_uri;
	} else {
		$font_bold_uri = 'file://' . $font_bold_uri;
	}
}

$font_regular_exists = $font_regular_path && file_exists( $font_regular_path );
$font_regular_is_file = $font_regular_path && is_file( $font_regular_path );
$font_regular_readable = $font_regular_path && is_readable( $font_regular_path );

$font_bold_exists = $font_bold_path && file_exists( $font_bold_path );
$font_bold_is_file = $font_bold_path && is_file( $font_bold_path );
$font_bold_readable = $font_bold_path && is_readable( $font_bold_path );


/*
 * Quote
 */
$quote = ! empty( $args['quote'] ) && is_array( $args['quote'] )
	? $args['quote']
	: [];

$quote_items = ! empty( $quote['items'] ) && is_array( $quote['items'] )
	? $quote['items']
	: [];

$quote_currency = ! empty( $quote['currency'] )
	? $quote['currency']
	: 'EUR';

$recipient = ! empty( $quote['recipient'] ) && is_array( $quote['recipient'] )
	? $quote['recipient']
	: [];


/*
 * Font debug
 */
$output_font_debug =
	'<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #999; font-size: 10px;">'.
		'<strong>Font Debug</strong><br><br>'.

		'<strong>Regular</strong><br>'.
		'Path: ' . esc_html( $font_regular_path ?: 'not found' ) . '<br>'.
		'URI: ' . esc_html( $font_regular_uri ?: 'not found' ) . '<br>'.
		'Exists: ' . ( $font_regular_exists ? 'YES' : 'NO' ) . '<br>'.
		'Is file: ' . ( $font_regular_is_file ? 'YES' : 'NO' ) . '<br>'.
		'Readable: ' . ( $font_regular_readable ? 'YES' : 'NO' ) . '<br><br>'.

		'<strong>Bold</strong><br>'.
		'Path: ' . esc_html( $font_bold_path ?: 'not found' ) . '<br>'.
		'URI: ' . esc_html( $font_bold_uri ?: 'not found' ) . '<br>'.
		'Exists: ' . ( $font_bold_exists ? 'YES' : 'NO' ) . '<br>'.
		'Is file: ' . ( $font_bold_is_file ? 'YES' : 'NO' ) . '<br>'.
		'Readable: ' . ( $font_bold_readable ? 'YES' : 'NO' ) . '<br>'.
	'</div>';


/*
 * Dompdf font registration debug
 */
$output_dompdf_debug = '';

if ( ! empty( $args['font_debug'] ) && is_array( $args['font_debug'] ) ) {
	$font_debug = $args['font_debug'];

	$output_dompdf_debug =
		'<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #999; font-size: 10px;">'.
			'<strong>Dompdf Font Registration</strong><br>'.
			'Regular registered: ' . ( ! empty( $font_debug['regular_registered'] ) ? 'YES' : 'NO' ) . '<br>'.
			'Bold registered: ' . ( ! empty( $font_debug['bold_registered'] ) ? 'YES' : 'NO' ) . '<br>'.
			'Regular resolved: ' . esc_html( $font_debug['regular_resolved'] ?? 'NULL' ) . '<br>'.
			'Bold resolved: ' . esc_html( $font_debug['bold_resolved'] ?? 'NULL' ) . '<br>'.
		'</div>';
}


/*
 * Recipient
 */
$output_recipient = '';

if ( ! empty( $recipient ) ) {
	$recipient_name = trim(
		( $recipient['first_name'] ?? '' ) . ' ' .
		( $recipient['last_name'] ?? '' )
	);

	$recipient_city = trim(
		( $recipient['postcode'] ?? '' ) . ' ' .
		( $recipient['city'] ?? '' )
	);

	$output_recipient =
		'<section class="rbf-quote-recipient">'.
			'<h2>Empfänger</h2>'.
			'<p>'.
				( ! empty( $recipient['company'] ) ? esc_html( $recipient['company'] ) . '<br>' : '' ).
				( ! empty( $recipient_name ) ? esc_html( $recipient_name ) . '<br>' : '' ).
				( ! empty( $recipient['address_1'] ) ? esc_html( $recipient['address_1'] ) . '<br>' : '' ).
				( ! empty( $recipient_city ) ? esc_html( $recipient_city ) : '' ).
			'</p>'.
		'</section>';
}


/*
 * Intro text
 */
$output_text = '';

if ( ! empty( $text ) ) {
	$output_text =
		'<div class="rbf-quote-text">'.
			'<p>' . esc_html( $text ) . '</p>'.
		'</div>';
}


/*
 * Quote items
 */
$output_quote_item_rows = '';

if ( ! empty( $quote_items ) ) {
	foreach ( $quote_items as $index => $item ) {
		$unit_price = wp_kses_post(
			wc_price(
				$item['unit_price'] ?? 0,
				[
					'currency' => $quote_currency,
				]
			)
		);

		$line_total = wp_kses_post(
			wc_price(
				$item['line_total'] ?? 0,
				[
					'currency' => $quote_currency,
				]
			)
		);

		$output_quote_item_rows .=
			'<tr>'.
				'<td>' . esc_html( $index + 1 ) . '</td>'.
				'<td>' . esc_html( $item['name'] ?? '' ) . '</td>'.
				'<td>' . esc_html( $item['sku'] ?? '' ) . '</td>'.
				'<td>' . esc_html( $item['quantity'] ?? 0 ) . '</td>'.
				'<td>' . $unit_price . '</td>'.
				'<td>' . $line_total . '</td>'.
			'</tr>';
	}
}

$output_quote_items = '';

if ( ! empty( $output_quote_item_rows ) ) {
	$output_quote_items =
		'<table class="rbf-quote-items">'.
			'<thead>'.
				'<tr>'.
					'<th>Pos.</th>'.
					'<th>Artikel</th>'.
					'<th>SKU</th>'.
					'<th>Menge</th>'.
					'<th>Einzelpreis</th>'.
					'<th>Gesamt</th>'.
				'</tr>'.
			'</thead>'.
			'<tbody>'.
				$output_quote_item_rows.
			'</tbody>'.
		'</table>';
}


/*
 * Final document
 */
$html =
	'<!doctype html>'.
	'<html lang="de">'.

		'<head>'.
			'<meta charset="UTF-8">'.

			'<style>'.
				'@font-face {'.
					'font-family: "RBF Jost";'.
					'font-style: normal;'.
					'font-weight: 400;'.
					'src: url("' . esc_attr( $font_regular_uri ) . '") format("truetype");'.
				'}'.

				'@font-face {'.
					'font-family: "RBF Jost";'.
					'font-style: normal;'.
					'font-weight: 700;'.
					'src: url("' . esc_attr( $font_bold_uri ) . '") format("truetype");'.
				'}'.

				'body {'.
					'font-family: "RBF Jost", sans-serif;'.
					'font-size: 12px;'.
					'font-weight: 400;'.
					'line-height: 1.5;'.
					'color: #111;'.
				'}'.

				'h1 {'.
					'font-family: "RBF Jost", sans-serif;'.
					'font-size: 30px;'.
					'font-weight: 700;'.
				'}'.

				'.rbf-quote-items {'.
					'width: 100%;'.
					'margin-top: 30px;'.
					'border-collapse: collapse;'.
				'}'.

				'.rbf-quote-items th,'.
				'.rbf-quote-items td {'.
					'padding: 8px 6px;'.
					'border-bottom: 1px solid #ddd;'.
					'text-align: left;'.
					'vertical-align: top;'.
				'}'.

				'.rbf-quote-items th {'.
					'font-weight: 700;'.
				'}'.
			'</style>'.
		'</head>'.

		'<body>'.
			$output_font_debug.
			$output_dompdf_debug.

			'<h1>' . esc_html( $title ) . '</h1>'.

			$output_recipient.
			$output_text.
			$output_quote_items.
		'</body>'.

	'</html>';

echo $html;