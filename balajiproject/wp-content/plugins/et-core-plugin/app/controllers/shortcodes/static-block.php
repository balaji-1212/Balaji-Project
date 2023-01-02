<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Static Block shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Static_Block extends Shortcodes {

	function hooks() {}

	function block_shortcode( $atts ) {
		if ( xstore_notice() )
			return;

		$atts = shortcode_atts(array(
			'class' => '',
			'id' => ''
		),$atts);

		return etheme_static_block($atts['id'], false);
	}
}