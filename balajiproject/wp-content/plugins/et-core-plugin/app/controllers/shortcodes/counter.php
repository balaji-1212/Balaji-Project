<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Counter shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Counter extends Shortcodes {

	function hooks(){}

	function counter_shortcode( $atts, $content = null ) {

		if ( xstore_notice() )
			return;

		$atts = shortcode_atts( array(
			'init_value' => 1,
			'final_value' => 100,
			'class' => ''
		), $atts);

		return '<span id="animatedCounter" class="animated-counter '.$atts['class'].'" data-value='.$atts['final_value'].'>'.$atts['init_value'].'</span>';
	}

}