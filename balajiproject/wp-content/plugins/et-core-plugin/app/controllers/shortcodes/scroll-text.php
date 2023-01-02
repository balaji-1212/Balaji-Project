<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Scroll Text shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Scroll_Text extends Shortcodes {

    function hooks() {}

    function scroll_text_shortcode( $atts, $content ) {

	    if ( xstore_notice() )
		    return;

        $atts = shortcode_atts( array(
            'height_value' => '',
            'transition_effect' => 'slide',
            'slider_interval' => 7000,
            'bg_color' => '#222',
            'color' => '#fff',
            'el_class' => '',
            'is_preview' => false
        ), $atts );

        $options = array();

        $options['wrapper_style'] = array();
        $options['wrapper_style'][] = 'background-color:' . esc_attr($atts['bg_color']);
        $options['wrapper_style'][] = 'color:' . esc_attr($atts['color']);

        $options['slider_attr'] = array(
            'data-centeredSlides="1"',
            'data-breakpoints="1"',
            'data-xs-slides="1"',
            'data-sm-slides="1"',
            'data-md-slides="1"',
            'data-lt-slides="1"',
            'data-slides-per-view="1"',
            'data-autoplay="' . esc_attr( $atts['slider_interval'] ) . '"',
            'data-speed="1200"',
            'data-effect="' . esc_attr($atts['transition_effect']) . '"',
            'data-loop="true"',
        );

        if ( !empty($atts['height_value']) )
            $options['slider_attr'][] = 'style="height:'.$atts['height_value'].'"';

        ob_start();
	
	    if ( function_exists('etheme_enqueue_style')) {
            etheme_enqueue_style( 'wpb-autoscrolling-text', true );
	    } ?>

        <div class="swiper-entry autoscrolling-text-wrapper <?php echo esc_attr($atts['el_class']); ?>" style="<?php echo implode(' ;', $options['wrapper_style']); ?>">
            <div class="swiper-container stop-on-hover" <?php echo implode(' ', $options['slider_attr']); ?>>
                <div class="swiper-wrapper">
                    <?php
                    etheme_override_shortcodes();
                    echo do_shortcode($content);
                    etheme_restore_shortcodes(); 
                    ?>
                </div>
            </div>
        </div>

        <?php 

        if ( $atts['is_preview'] ) 
            echo parent::initPreviewJs();

        unset($atts);
        unset($options); 
        
        return ob_get_clean();
    }
}
