<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Button shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Button extends Shortcodes {

	function hooks(){}

	function btn_shortcode($atts){

		if ( xstore_notice() )
			return;

		$atts = shortcode_atts( 
			array(
				'title' => 'Button',
				'url' => '#',
				'icon' => '',
				'size' => '',
				'rel' => '',
				'style' => '',
				'et_class' => '',
				'type' => '',
				'target' => ''
		), $atts );
	
		$icon = ( $atts['icon'] != '' ) ? '<i class="et-icon et-'.$atts['icon'].'" style="vertical-align: middle;"></i>' : '';

		if ( $atts['style'] != '') 
			$atts['et_class'] .= ' ' . $atts['style'];

		if ( $atts['type'] != '') 
			$atts['et_class'] .= ' ' . $atts['type'];

		if ( $atts['size'] != '') 
			$atts['et_class'] .= ' ' . $atts['size'];

		$a_attr = array(
			'target="' . $atts['target'] . '"',
			'class="btn '. $atts['et_class'] .'"',
			'href="' . $atts['url'] . '"'
		);
		
		if ( $atts['rel'] ) {
			$a_attr[] = 'rel="'.$atts['rel'].'"';
		}


		return '<a ' . implode(' ', $a_attr) . '><span>'. $icon . ' ' . $atts['title'] . '</span></a>';
	}

}
