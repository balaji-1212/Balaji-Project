<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Instagram shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Instagram extends Shortcodes {

    function hooks() {}

	function instagram_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;

	    $args = shortcode_atts(array(
	        'title'  => '',
	        'user' => '',
	        'username'  => '',
	        'number'  => 12,
	        'columns'  => 4,
	        'size'  => 'thumbnail',
	        'img_type' => 'squared',
	        'target'  => '',
	        'slider'  => '',
	        'spacing'  => 0,
	        'type' => 'element',
	        'link'  => '',
	        'large' => 5,
	        'notebook' => 3,
	        'tablet_land' => 2,
	        'tablet_portrait' => 2,
	        'mobile' => 1,
	        'slider_autoplay' => false,
	        'slider_stop_on_hover' => false,
	        'slider_speed' => 300,
			'slider_interval' => 3000,
			'slider_loop' => false,
	        'pagination_type' => 'hide',
	        'default_color' => '#e1e1e1',
	        'active_color' => '#222',
	        'hide_fo' => '',
	        'hide_buttons' => false,
	        'navigation_type'      => 'arrow',
	        'navigation_position_style' => 'arrows-hover',
	        'navigation_style'     => '',
	        'navigation_position'  => 'middle',
            'hide_buttons_for'   => '',
	        'ajax' => false,
	        'is_preview' => false,
	        'is_elementor' => false,
	        'tag_type' => 'recent_media',
		    // Fix for "wp-fastest-cache-premium plugin" to not catch the widget if it uses for shortcode
	        'wpfcnot' => 1
	    ), $atts);
	    
	    if ( $args['ajax'] ) {
	    	$extra = '';
	    	if ( $args['slider'] != '' ) $extra = 'slider';
		    if ( function_exists('etheme_enqueue_style')) {
			    etheme_enqueue_style( 'instagram' );
			    if ( class_exists( 'WPBMap' ) ) {
				    etheme_enqueue_style( 'wpb-instagram' );
			    }
		    }
		    $output = et_ajax_element_holder( 'instagram', $args, $extra );
	    } else {
	    	ob_start();
	    		the_widget( 'ETC\App\Models\Widgets\Instagram', $args );
		    $output = ob_get_contents();
		    ob_end_clean();
	    }

        if ( $args['is_preview'] ) {
        	ob_start();
            	echo parent::initPreviewJs();
            $output .= ob_get_clean();
        }

	    return $output;
	}
}
