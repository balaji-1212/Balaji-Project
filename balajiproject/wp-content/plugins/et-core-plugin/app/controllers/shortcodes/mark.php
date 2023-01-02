<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Mark shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Mark extends Shortcodes {

    function hooks() {}

    function mark_shortcode( $atts, $content = null ) {
	    if ( xstore_notice() )
		    return;
        $atts = shortcode_atts( array(
           'style' => '',
           'color' => '',
        ), $atts );
	
        if ( function_exists('etheme_enqueue_style') )
	        etheme_enqueue_style('mark-text', true);

        $style = '';

        if( ! empty( $atts['color'] ) ) 
            $style = 'style="' . ( ( $atts['style'] == 'paragraph' ) ? '' : 'background-' ) . 'color:' . $atts['color'] . ';"';
       
        return '<span class="mark-text ' . $atts['style'] . '" ' . $style . '>' . $content . '</span>';
    }
}