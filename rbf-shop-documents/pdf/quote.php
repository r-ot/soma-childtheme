<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = [];
}

$title = ! empty( $args['title'] ) ? $args['title'] : 'Angebot';
$text = ! empty( $args['text'] ) ? $args['text'] : '';




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

$font_filename = $font_regular_path ? basename($font_regular_path ) : 'not found';


?>
<!doctype html>
<html lang="de">
<head>
	<meta charset="UTF-8">

	<style>
		@font-face {
			font-family: 'RBF Jost';
			font-style: normal;
			font-weight: 400;
			src: url('<?php echo esc_attr( $font_regular_uri ); ?>') format('truetype');
		}

		@font-face {
			font-family: 'RBF Jost';
			font-style: normal;
			font-weight: 700;
			src: url('<?php echo esc_attr( $font_bold_uri ); ?>') format('truetype');
		}

		body {
			font-family: 'RBF Jost', sans-serif;
			font-size: 12px;
			font-weight: 400;
			line-height: 1.5;
			color: #111;
		}

		h1 {
			font-family: 'RBF Jost', sans-serif;
			font-size: 30px;
			font-weight: 700;
		}
	</style>
</head>
<body>

	<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #999; font-size: 10px;">
	<strong>Font Debug</strong><br><br>

	<strong>Regular</strong><br>
		Path: <?php echo esc_html( $font_regular_path ?: 'not found' ); ?><br>
		URI: <?php echo esc_html( $font_regular_uri ?: 'not found' ); ?><br>
		Exists: <?php echo $font_regular_exists ? 'YES' : 'NO'; ?><br>
		Is file: <?php echo $font_regular_is_file ? 'YES' : 'NO'; ?><br>
		Readable: <?php echo $font_regular_readable ? 'YES' : 'NO'; ?><br><br>

		<strong>Bold</strong><br>
		Path: <?php echo esc_html( $font_bold_path ?: 'not found' ); ?><br>
		URI: <?php echo esc_html( $font_bold_uri ?: 'not found' ); ?><br>
		Exists: <?php echo $font_bold_exists ? 'YES' : 'NO'; ?><br>
		Is file: <?php echo $font_bold_is_file ? 'YES' : 'NO'; ?><br>
		Readable: <?php echo $font_bold_readable ? 'YES' : 'NO'; ?><br>
	</div>

	<?php if ( ! empty( $args['font_debug'] ) ) : ?>
		<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #999; font-size: 10px;">
			<strong>Dompdf Font Registration</strong><br>
			Regular registered:
			<?php echo ! empty( $args['font_debug']['regular_registered'] ) ? 'YES' : 'NO'; ?><br>

			Bold registered:
			<?php echo ! empty( $args['font_debug']['bold_registered'] ) ? 'YES' : 'NO'; ?><br>

			Regular resolved:
			<?php echo esc_html( $args['font_debug']['regular_resolved'] ?: 'NULL' ); ?><br>

			Bold resolved:
			<?php echo esc_html( $args['font_debug']['bold_resolved'] ?: 'NULL' ); ?><br>
		</div>
	<?php endif; ?>

	<h1><?php echo esc_html( $title ); ?></h1>


	<?php if ( ! empty( $args['quote']['recipient'] ) ) : ?>

		<?php $recipient = $args['quote']['recipient']; ?>

		<h2>Empfänger</h2>

		<p>
			<?php echo esc_html( $recipient['company'] ); ?><br>
			<?php echo esc_html( $recipient['first_name'] . ' ' . $recipient['last_name'] ); ?><br>
			<?php echo esc_html( $recipient['address_1'] ); ?><br>
			<?php echo esc_html( $recipient['postcode'] . ' ' . $recipient['city'] ); ?>
		</p>

	<?php endif; ?>

	<?php if ( ! empty( $text ) ) : ?>
		<p><?php echo esc_html( $text ); ?></p>
	<?php endif; ?>

</body>
</html>