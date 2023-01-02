<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Carousel shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Carousel extends Shortcodes {

	function hooks(){}

	function carousel_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;
		
		$atts = shortcode_atts( array(
			'space' => 'yes',
			'large' => 4,
			'notebook' => 3,
			'tablet_land' => 2,
			'tablet_portrait' => 2,
			'mobile' => 1,
			'slider_autoplay' => false,
			'slider_speed' => 300,
			'slider_loop' => false,
			'slider_interval' => 1000,
			'slider_stop_on_hover' => false,
			'pagination_type' => 'hide', 
	        'nav_color' 		 => '',
			'arrows_bg_color' 	 => '',
			'default_color' => '#e1e1e1',
			'active_color' => '#222',
			'transparent_arrows' => false,
			'nav_bg_color' => '#f0f0f0',
			'nav_color' => '#555',
			'hide_fo' => '',
			'hide_buttons' => false,
			'per_move' => 1,
			'el_class' => '',
			'is_preview' => false
		), $atts );

		$options = array();
		$options['box_id'] = rand(1000,10000);
		$options['wrapper_attr'] = array();

		ob_start();

		if ( $atts['space'] == 'yes' ) 
			$atts['el_class'] .= ' slider-with-space';

		if ( $atts['slider_autoplay'] ) 
			$atts['slider_autoplay'] = $atts['slider_interval'];

        $options['selectors']['slider'] = '.slider-'.$options['box_id'];

        $options['selectors']['navigation'] = $options['selectors']['slider'] . ' ~ .swiper-button-prev,' . $options['selectors']['slider'] . ' ~ .swiper-button-next';

        $options['selectors']['pagination'] = $options['selectors']['slider'] . ' .swiper-pagination-bullet';
        $options['selectors']['pagination_hover'] = $options['selectors']['pagination'].':hover';
        $options['selectors']['pagination_hover'] .= ', ' . $options['selectors']['pagination'] . '-active';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['slider'] => array(),
            'navigation' => array(),
            $options['selectors']['pagination'] => array(),
            $options['selectors']['pagination_hover'] => array(),
        );

		if ($atts['pagination_type'] != 'hide' ) {
           	$options['css'][$options['selectors']['pagination']][] = 'background-color:'.$atts['default_color'];
           	$options['css'][$options['selectors']['pagination_hover']][] = 'background-color:'.$atts['active_color'];
		}

		// create output css 
        $options['output_css'] = array();

        if ( !empty($atts['arrows_bg_color']) ) 
        	$options['css']['navigation'][] = 'background-color: ' .$atts['arrows_bg_color']; 

        if ( !empty($atts['nav_color']) ) 
        	$options['css']['navigation'][] = 'color: ' .$atts['nav_color']; 

        if ( count($options['css']['navigation']) ) 
        	$options['output_css'][] = $options['selectors']['navigation'] . '{' . implode(';', $options['css']['navigation']) . '}';

        if ( count( $options['css'][$options['selectors']['pagination']] ) )
            $options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';

        if ( count( $options['css'][$options['selectors']['pagination_hover']] ) )
            $options['output_css'][] = $options['selectors']['pagination_hover'] . '{'.implode(';', $options['css'][$options['selectors']['pagination_hover']]).'}';

		$options['wrapper_attr'] = array_merge( $options['wrapper_attr'], array(
			'data-breakpoints="1"',
			'data-centeredSlides="1"',
			'data-xs-slides="' . esc_js( $atts['mobile'] ) . '"',
			'data-sm-slides="' . esc_js( $atts['tablet_land']) . '"',
			'data-md-slides="' . esc_js( $atts['notebook'] ) . '"',
			'data-lt-slides="' . esc_js( $atts['large'] ) . '"',
			'data-slides-per-view="' . esc_js( $atts['large'] ) . '"',
			'data-autoplay="' . esc_attr( $atts['slider_autoplay'] ) . '"',
			'data-slides-per-group="' . esc_attr($atts['per_move']) . '"',
			'data-speed="' . esc_attr( $atts['slider_speed'] ) . '"',
			'data-space="' . ( ( $atts['space'] == 'yes' ) ? '30' : '0' ) . '"',
		) );

		if ( $atts['slider_loop'] ) 
			$options['wrapper_attr'][] = 'data-loop="true"';

		$atts['el_class'] .= ' slider-' . esc_attr($options['box_id']);

		if ( $atts['pagination_type'] == 'lines' ) 
			$atts['el_class'] .= ' swiper-pagination-lines';

		if ( $atts['slider_stop_on_hover'] ) 
			$atts['el_class'] .= ' stop-on-hover';

		?>
			<div class="swiper-entry et-custom-carousel">

				<div class="swiper-container <?php echo esc_attr($atts['el_class']); ?>" <?php echo implode(' ', $options['wrapper_attr']); ?>>

					<div class="swiper-wrapper">
						<?php
							etheme_override_shortcodes();
							echo do_shortcode($content);
							etheme_restore_shortcodes(); 
						?>
					</div>

					<?php if ($atts['pagination_type'] != "hide") { ?>

					<div class="swiper-pagination"></div>

					<?php } ?>

				</div>

				<?php if (!$atts['hide_buttons']) { ?>

					<div class="swiper-custom-left"></div>
					<div class="swiper-custom-right"></div>

				<?php } ?>
			</div>
		
		<?php 

		if ( $atts['is_preview'] ) {
			echo parent::initPreviewCss($options['output_css']);
			echo parent::initPreviewJs();
		}
		else 
			parent::initCss($options['output_css']);
		
		unset($atts);
		unset($options);
		
		return ob_get_clean();
	}
}
