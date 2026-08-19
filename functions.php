<?php
add_action( 'wp_enqueue_scripts', function() {
	// Parent-Stylesheet laden (falls du es brauchst)
	wp_enqueue_style(
		'twentytwentyfive-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'twentytwentyfive' )->get( 'Version' )
	);

	// Child-Stylesheet laden
	wp_enqueue_style(
		'twentytwentyfive-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'twentytwentyfive-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
	// Child-Stylesheet für woo commerce elemente
	wp_enqueue_style(
		'rbf-woo-style',
		get_stylesheet_directory_uri() . '/woo-style.css',
		array( 'twentytwentyfive-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
} );



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

