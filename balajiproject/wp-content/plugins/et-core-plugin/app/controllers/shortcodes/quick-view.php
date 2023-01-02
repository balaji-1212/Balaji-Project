<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Quick View shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Quick_View extends Shortcodes {

    function hooks() {}

    function quick_view_shortcodes( $atts, $content = null ){

	    if ( xstore_notice() )
		    return;

        $atts = shortcode_atts(array( 
            'id' => '',
            'class' => ''
        ), $atts );
        
        
        return '<div class="show-quickly-btn '.$atts['class'].'" data-prodid="'.$atts['id'].'">'. do_shortcode($content) .'</div>';
    }
}