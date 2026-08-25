<?php

//bootstrap and theme styles
add_action( 'wp_enqueue_scripts', function() {
	$child_dir = get_stylesheet_directory();
	$child_uri = get_stylesheet_directory_uri();

	$parent_dir = get_template_directory();
	$parent_uri = get_template_directory_uri();

	// Bootstrap CSS aus dem Child Theme.
	wp_enqueue_style(
		'bootstrap',
		$child_uri . '/assets/bootstrap/css/bootstrap.min.css',
		array(),
		filemtime( $child_dir . '/assets/bootstrap/css/bootstrap.min.css' )
	);

	//fonts
	wp_enqueue_style(
		'rbf-fonts',
		$child_uri . '/assets/fonts/fonts.css',
		[],
		filemtime($child_dir . '/assets/fonts/fonts.css')
	);

	// Parent Stylesheet.
	wp_enqueue_style(
		'twentytwentyfive-parent-style',
		$parent_uri . '/style.css',
		array(),
		wp_get_theme( 'twentytwentyfive' )->get( 'Version' )
	);

	// Child Stylesheet.
	wp_enqueue_style(
		'twentytwentyfive-child-style',
		$child_uri . '/style.css',
		array(  'bootstrap',
				'twentytwentyfive-parent-style',
				'rbf-fonts',
		),
		filemtime( $child_dir . '/style.css' )
	);

	// WooCommerce / Product Styles.
	wp_enqueue_style(
		'rbf-woo-style',
		$child_uri . '/woo-style.css',
		array( 'twentytwentyfive-child-style' ),
		filemtime( $child_dir . '/woo-style.css' )
	);


	// Bootstrap JS inkl. Popper.
	wp_enqueue_script(
		'bootstrap',
		$child_uri . '/assets/bootstrap/js/bootstrap.bundle.min.js',
		array(),
		filemtime( $child_dir . '/assets/bootstrap/js/bootstrap.bundle.min.js' ),
		true
	);




	//product families endpoint call
	//MOVED TO PLUGIN RBF_SITE_CORE
	//MOVED TO PLUGIN RBF_SITE_CORE
	//MOVED TO PLUGIN RBF_SITE_CORE
	//MOVED TO PLUGIN RBF_SITE_CORE
	// wp_enqueue_script(
	// 	'rbf-product-families',
	// 	$child_uri . '/assets/js/product-families.js',
	// 	[],
	// 	filemtime( $child_dir . '/assets/js/product-families.js' ),
	// 	true
	// );
}, 20 );





function rot_wp_admin_style() {
	wp_enqueue_style('rot-admin-styles', get_stylesheet_directory_uri().'/style-admin.css');
	if(wp_get_current_user()->roles[0]!='administrator'){
		wp_enqueue_style('rot-admin-styles-editor', get_stylesheet_directory_uri().'/style-admin-noadmin.css');
	}
}
add_action('admin_enqueue_scripts', 'rot_wp_admin_style');
function rot_gutenberg_css(){
	add_theme_support( 'editor-styles' ); // if you don't add this line, your stylesheet won't be added
	add_editor_style( 'rot-editor-style.css' ); // tries to include style-editor.css directly from your theme folder
}
add_action( 'after_setup_theme', 'rot_gutenberg_css' );



//remove autop from shortcode calls
add_filter( 'pre_render_block', function( $pre_render, $parsed_block ) {

	if ( 'core/shortcode' !== $parsed_block['blockName'] ) {
		return $pre_render;
	}

	return do_shortcode( $parsed_block['innerHTML'] );

}, 10, 2 );





//INCLUDES
//INCLUDES
//INCLUDES
//INCLUDES
//INCLUDES
require_once get_stylesheet_directory() . '/includes/rot-core-helpers.php';
require_once get_stylesheet_directory() . '/includes/class-rbf-theme-shortcodes.php';
require_once get_stylesheet_directory() . '/rbf_filters.php';
require_once get_stylesheet_directory() . '/rbf-debug.php';

add_action( 'init', function() {
	Rbf_Theme_Shortcodes::instance();
} );



/**
 * Favicons/Manifest in den <head> injizieren.
 * (Paths sind root-relativ, so wie du sie gepostet hast.)
 */
add_action('wp_head', function() {

	// Optional: Wenn du das WP Site Icon (Customizer) NICHT verwenden willst,
	// kannst du es zusätzlich per Filter deaktivieren (siehe unten).

	echo "\n".'<!-- Rot Favicons -->'."\n".
		'<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />'."\n".
		'<link rel="icon" type="image/svg+xml" href="/favicon.svg" />'."\n".
		'<link rel="shortcut icon" href="/favicon.ico" />'."\n".
		'<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />'."\n".
		'<meta name="apple-mobile-web-app-title" content="rbf bframe" />'."\n".
		'<link rel="manifest" href="/site.webmanifest" />'."\n";

}, 2);

/**
 * Optional, aber oft sinnvoll:
 * Wenn im WP Customizer ein "Website-Icon" gesetzt ist, spuckt WP selbst Favicons aus.
 * Damit du keine doppelten Tags hast, kannst du es deaktivieren:
 */
add_filter('site_icon_meta_tags', function($meta_tags) {
	return [];
}, 999);












//METAS
//METAS
//METAS
//METAS
//METAS

//obj_pos für attchments
function rbf_register_attachment_meta() {
	register_post_meta(
		'attachment',
		'obj_pos',
		[
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		]
	);
}
add_action( 'init', 'rbf_register_attachment_meta' );


function rbf_attachment_fields_to_edit( $form_fields, $post ) {
	$obj_pos = get_post_meta( $post->ID, 'obj_pos', true );

	$form_fields['obj_pos'] = [
		'label' => 'Object Position',
		'input' => 'text',
		'value' => $obj_pos,
		'helps' => 'CSS object-position, z. B. center center, 50% 30%, left top',
	];

	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'rbf_attachment_fields_to_edit', 10, 2 );


function rbf_attachment_fields_to_save( $post, $attachment ) {
	if ( isset( $attachment['obj_pos'] ) ) {
		update_post_meta(
			$post['ID'],
			'obj_pos',
			sanitize_text_field( $attachment['obj_pos'] )
		);
	}

	return $post;
}
add_filter( 'attachment_fields_to_save', 'rbf_attachment_fields_to_save', 10, 2 );