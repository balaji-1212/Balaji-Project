<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Portfolio shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Portfolio extends Shortcodes {

	function hooks() {}

	function portfolio_shortcode( $atts, $content ) {
		if ( xstore_notice() )
			return;
		
		$atts = shortcode_atts(array(
			'limit'  => '',
			'columns' => '',
			'ajax' => false,
			'pagination' => true,
			'filters' => true,
			'is_preview' => false
		), $atts);

		global $et_portfolio_loop;

		$et_portfolio_loop['columns'] = $atts['columns'];
		
		wp_enqueue_script( 'et_isotope' );
		wp_enqueue_script( 'portfolio' );
		
		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'portfolio' );
			if ( $atts['filters'] ) {
				etheme_enqueue_style( 'isotope-filters' );
			}
		}
		
		ob_start();

		etheme_portfolio( false, $atts['limit'], $atts['pagination'], false, $atts['filters'] );

        if ( $atts['is_preview'] ) {
	        echo parent::initPreviewJs();
        }

		return ob_get_clean();
	}
}
