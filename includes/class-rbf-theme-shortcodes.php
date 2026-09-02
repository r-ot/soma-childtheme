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

			case 'product-family':
				$term = get_queried_object();

				if (
					!$term instanceof WP_Term
					|| !class_exists('RbfSiteCoreDataKeys')
				) {
					return '';
				}

				$taxonomy = RbfSiteCoreDataKeys::get_taxonomy_key(
					RbfSiteCoreDataKeys::TAXONOMY_PRODUCT_FAMILY
				);

				if ($taxonomy === '' || $term->taxonomy !== $taxonomy) {
					return '';
				}



				//viewer NEEDS term ID to find subdirectory in /uploads
				$xr_manifest = '';

				if ( class_exists( 'RbfXrViewer' ) ) {
					$xr_manifest = RbfXrViewer::get_manifest_url( (int) $term->term_id );
				}

				if ($xr_manifest !== '') {
					wp_enqueue_script( 'rbf-xr-viewer');
				}


				//_______________________________________



				wp_enqueue_script('rbf-site-core-product-family-data');

				return $this->render_hero_template(
					[
						'case'                => 'product-family',
						'title'               => $term->name,
						'text'                => $term->description,
						//neuen learn endpoint reichen wir auch durch bis ins template
						'dimensions_endpoint' => rest_url(
							'rbf-site-core/v1/product-families/' . (int) $term->term_id . '/learn'
						),
							'xr_manifest'         => $xr_manifest,
						'usps'                => [],
					]
				);


			case 'compact':
				$post_id = get_queried_object_id();

				if ( ! $post_id || ! is_singular() ) {
					return '';
				}

				$hero = [
					'case'     => 'compact',
					'title'    => get_the_title( $post_id ),
					'text'     => '',
					'post_id'  => $post_id,
					'eyebrow'  => 'crumbs',
					'usps'     => [],
				];

				$hero['breadcrumbs'] = $this->get_hero_breadcrumbs( $hero );

				return $this->render_hero_template( $hero );



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
		if (
			function_exists( 'is_cart' )&&
			( is_cart() || is_checkout() )
		) {
			return 'compact';
		}


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


	private function get_hero_breadcrumbs( array $hero ) {
		$breadcrumbs = [];
		$case = $hero['case'] ?? 'default';

		switch ( $case ) {

			case 'compact':

				$post_id = isset( $hero['post_id'] )
					? (int) $hero['post_id']
					: 0;

				if ( function_exists( 'wc_get_page_permalink' ) ) {

					$shop_url = wc_get_page_permalink( 'shop' );

					if ( ! empty( $shop_url ) ) {
						$breadcrumbs[] = [
							'label' => 'Shop',
							'url'   => $shop_url,
						];
					}
				}

				if ( $post_id > 0 ) {
					$breadcrumbs[] = [
						'label' => get_the_title( $post_id ),
						'url'   => '',
					];
				}

				break;
		}

		return $breadcrumbs;
	}
}