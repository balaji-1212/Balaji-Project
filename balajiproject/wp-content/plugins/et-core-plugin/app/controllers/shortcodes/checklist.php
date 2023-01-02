<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Checklist shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Checklist extends Shortcodes {

	function hooks(){}

	function checklist_shortcode( $atts, $content = null ) {
		if ( xstore_notice() )
			return;

		$atts = shortcode_atts( array(
			'style' => 'arrow'
		), $atts);

		switch($atts['style']) {
			case 'circle':
				$class = 'circle';
			break;
			case 'star':
				$class = 'star';
			break;
			case 'square':
				$class = 'square';
			break;
			case 'dash':
				$class = 'dash';
			break;
			default:
				$class = 'arrow';
			break;
		}
		return '<div class="list list-' . $class . '">' . do_shortcode($content) . '</div>';
	}
}
