<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Rbf_Theme_Shortcodes {

	private static $instance = null;

	private function __construct() {
		add_shortcode( 'rbf_hero', array( $this, 'shortcode_hero' ) );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function shortcode_hero( $atts = array() ) {
		//Debug
		// static $rbf_hero_call = 0;
		// $rbf_hero_call++;
		// $output_debug = '<!-- RBF HERO CALL ' . $rbf_hero_call . ' -->';


		$atts = shortcode_atts(
			array(
				'case'      => '',
				'post_type' => '',
			),
			$atts,
			'rbf_hero'
		);

		$case = sanitize_key( $atts['case'] );

		if ( empty( $case ) ) {
			$case = $this->get_current_hero_case();
		}

		// do_action('qm/debug',['case'=>$case,]);
		switch ( $case ) {
			case 'frontpage':
				// return $output_debug . $this->render_hero_template(
				return $this->render_hero_template(
					array(
						'case'         => 'frontpage',
						'eyebrow'      => 'SCHNEEKETTEN-SUCHE',
						'title'        => 'VERIGA NEWS 2026',
						'text'         => 'Finde deine Kette in den brandaktuellen Angeboten des Top-Marke VERIGA',
						'button_label' => 'Reifendimension eingeben',
						'button_url'   => '#',
					)
				);

			default:
				return '<!--shortcode_hero-->';
		}


		/*
		 * Erstmal absichtlich sichtbar,
		 * damit wir sofort sehen, dass der Controller läuft.
		 */
		// $output  = '<section class="rbf-hero" data-rbf-hero-case="' . esc_attr( $case ) . '">';
		// $output .= '<div class="container">';
		// $output .= '<strong>RBF Hero Controller:</strong> ' . esc_html( $case );
		// $output .= '</div>';
		// $output .= '</section>';

		// return $output;
	}

	private function get_current_hero_case() {
		if ( is_front_page() ) {
			return 'frontpage';
		}

		if ( is_singular( 'product' ) ) {
			return 'single-product';
		}

		return 'default';
	}



	private function render_hero_template( $args = array() ) {
		ob_start();
		// echo '<!-- getting template part hero-->';
		get_template_part(
			'template-parts/hero',
			null,
			$args
		);

		return ob_get_clean();
	}
}