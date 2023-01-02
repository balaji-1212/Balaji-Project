<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Dropcap shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Dropcap extends Shortcodes {

	function hooks(){}

	function dropcap_shortcode( $atts,$content=null ) {
		if ( xstore_notice() )
			return;

		$atts = shortcode_atts( array(
				'style' => '',
				'color' => '',
			), $atts );
		
		if ( function_exists('etheme_enqueue_style'))
			etheme_enqueue_style('dropcap', true);
		
		$style = '';

		if( ! empty( $atts['color'] ) )
			$style = 'style="color:' . $atts['color'] . ';"';

		return '<span class="dropcap ' . $atts['style'] . '" ' . $style . '>' . $content . '</span>';
	}
}
