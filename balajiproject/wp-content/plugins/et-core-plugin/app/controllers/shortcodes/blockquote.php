<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Blockquote shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Blockquote extends Shortcodes {

    public function hooks() {}

    function blockquote_shortcode( $atts, $content = null ) {
	    if ( xstore_notice() )
		    return;

        $atts = shortcode_atts( array(
            'align' => 'left',
            'class' => ''
        ), $atts);

        switch($atts['align']) {
            case 'right':
                $atts['class'] .= ' fl-r';
            break;
            case 'center':
                $atts['class'] .= ' fl-none';
            break;
            default:
                $atts['class'] .= ' fl-l';        
        }

        $content = wpautop(trim($content));

        return '<blockquote class="' . $atts['class'] . '">' . $content . '</blockquote>';
    }
}