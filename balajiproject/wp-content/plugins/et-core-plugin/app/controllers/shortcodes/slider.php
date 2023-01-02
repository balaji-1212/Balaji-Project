<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Slider shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Slider extends Shortcodes {

	function hooks() {}

	function slider_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;

		if (  ( isset( $_GET[ 'vc_editable' ] ) && $_GET[ 'vc_editable' ] == 'true' ) || 
			( isset( $_POST[ 'action' ] ) && isset( $_POST[ 'vc_inline' ] ) && 
			$_POST[ 'action' ] == 'vc_load_shortcode' && $_POST[ 'vc_inline' ] == 'true' ) ) { 
			return '<div class="woocommerce-info">'.esc_html__('Unfortunately this element isn\'t available in', 'xstore-core').
			' <em>'.esc_html__('Frontend Editor', 'xstore-core').'</em> '.
			esc_html__('at the moment.', 'xstore-core').' '.esc_html__('Use the', 'xstore-core').
			' <em>'.esc_html__('Backend Editor', 'xstore-core').'</em> '.esc_html__('to change the options. We are sorry for any inconvenience.', 'xstore-core').'</div>';
		}

		$atts = shortcode_atts(array(
			'height' => 'full',
			'height_value' => '',
			'height_value_mobile' => '',
			'stretch' => '',
			'nav' => 'arrows_bullets',
			'nav_color' => '#222',
			'arrows_bg_color' => '#e1e1e1',
			'default_color' => '',
			'active_color' => '',
			'slider_autoplay' => false,
			'slider_speed' => 300,
			'slider_loop' => 'yes',
			'slider_mousewheel' => false,
			'slider_stop_on_hover' => 'yes',
			'slider_interval' => 5000,
			'nav_on_hover' => '',
			'transition_effect' => '',
			'bg_color' => '',
			'el_class' => '',
			'is_preview' => false
		), $atts);

		$options = array();

		$options['box_id'] = rand(1000,10000);
		
		// backup for wpb
		$atts['slider_stop_on_hover'] = $atts['slider_stop_on_hover'] == 'no' ? false : $atts['slider_stop_on_hover'];

		if ( $atts['slider_autoplay'] ) 
			$atts['slider_autoplay'] = $atts['slider_interval'];

		// selectors 
        $options['selectors'] = array();
        
        $options['selectors']['slider'] = '.slider-'.$options['box_id'];
        $options['selectors']['loader'] = '.slider-'.$options['box_id'] . ' .et-loader:before';
        $options['selectors']['pagination'] = $options['selectors']['slider'] . ' span.swiper-pagination-bullet';
        $options['selectors']['pagination_active'] = $options['selectors']['pagination'] . ':hover, ' . $options['selectors']['pagination'] . '-active'; 
        $options['selectors']['navigation'] = $options['selectors']['slider'] . ' .swiper-custom-left, ' . ' ' . $options['selectors']['slider'] . ' .swiper-custom-right';
        $options['selectors']['navigation_hover'] = $options['selectors']['slider'] . ' .swiper-custom-left:hover, ' . ' ' . $options['selectors']['slider'] . ' .swiper-custom-right:hover';

        // create css data for selectors
        $options['css'] = array(
            'slider' => array(),
            'loader' => array(),
            'pagination' => array(),
            'pagination_active' => array(),
            'navigation' => array(),
            'navigation_hover' => array()
        );

        if ( $atts['height'] != '' && $atts['height_value'] != '' ) 
			$options['css']['slider'][] = 'height:'.$atts['height_value'];

		if ( $atts['bg_color'] != '' )
			$options['css']['slider'][] = $options['css']['loader'][] = 'background-color:'.$atts['bg_color'];

		if ( $atts['nav_color'] != '' ) 
			$options['css']['navigation'][] = 'color:'.$atts['nav_color'] . ' !important';

		if ( $atts['arrows_bg_color'] != '' ) 
			$options['css']['navigation'][] = $options['css']['navigation_hover'][] = 'background-color: '.$atts['arrows_bg_color'].' !important';

		if ( $atts['default_color'] != '' )
			$options['css']['pagination'][] = 'background-color:'.$atts['default_color'];

		if ( $atts['active_color'] != '' )
			$options['css']['pagination_active'][] = 'background-color:'.$atts['active_color'];

		// create output css 
        $options['output_css'] = array();

        if ( count( $options['css']['pagination'] ) )
            $options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css']['pagination']).'}';

        if ( count( $options['css']['pagination_active'] ) )
            $options['output_css'][] = $options['selectors']['pagination_active'] . '{'.implode(';', $options['css']['pagination_active']).'}';

        if ( count( $options['css']['navigation'] ) )
            $options['output_css'][] = $options['selectors']['navigation'] . '{'.implode(';', $options['css']['navigation']).'}';

        if ( count( $options['css']['navigation_hover'] ) )
            $options['output_css'][] = $options['selectors']['navigation_hover'] . '{'.implode(';', $options['css']['navigation_hover']).'}';

        $options['frontend_css'] = array();

        if ( count( $options['css']['loader'] ) )
			$options['frontend_css'][] = $options['selectors']['loader'] . '{'.implode(';', $options['css']['loader']).'}';
   		
   		if ( $atts['height'] != '' && $atts['height_value_mobile'] != '' ) 
   			$options['frontend_css'][] = '@media only screen and (max-width: 992px) {' . $options['selectors']['slider'] . '{' . 'height:' . $atts['height_value_mobile'] . '!important;';

		$atts['el_class'] .= ' slider-' . esc_attr($options['box_id']);

		if ( $atts['height'] == 'full' ) 
			$atts['el_class'] .= ' full-height';

		if ( $atts['nav_on_hover'] ) 
			$atts['el_class'] .= ' arrows-long-path nav-on-hover';

		$options['wrapper_attr'] = array();

		if ( count($options['output_css']) ) {
			$atts['el_class'] .= ' etheme-css';
			$options['wrapper_attr'][] = 'data-css="' . implode(' ', $options['output_css']) . '"';
		}

		if ( count( $options['css']['slider'] ) )
        	$options['wrapper_attr'][] = 'style="' . implode(';', $options['css']['slider']) . '"';


		$options['attr'] = array();
		$options['attr'][] = 'data-autoplay="'.esc_attr($atts['slider_autoplay']).'"';
		$options['attr'][] = 'data-speed="' . esc_attr($atts['slider_speed']) . '"';
		$options['attr'][] = 'data-effect="' . esc_attr($atts['transition_effect']) . '"';

		if ( $atts['slider_loop'] )
			$options['attr'][] = 'data-loop="true"';
		
		if ( $atts['slider_mousewheel'])
		    $options['attr'][] = 'data-mousewheel="true"';
				
		ob_start();
		
		if ( function_exists('etheme_enqueue_style'))
		    etheme_enqueue_style('et-slider', true);

		if ( count($options['frontend_css']) ) { ?>
			<style><?php echo implode(' ', $options['frontend_css']); ?></style>
		<?php } ?>

		<div class="swiper-entry et-slider <?php echo esc_attr($atts['el_class']); ?>" <?php echo implode(' ', $options['wrapper_attr']); ?>>
			<div class="swiper-container <?php if ($atts['slider_autoplay'] && $atts['slider_stop_on_hover']) { echo 'stop-on-hover'; } ?>" data-centeredSlides="1" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1" data-md-slides="1" data-lt-slides="1" data-slides-per-view="1" data-space="0" <?php echo implode(' ', $options['attr']); ?>>
				<div class="et-loader swiper-lazy-preloader"></div>
				<!-- Additional required wrapper -->
				<div class="swiper-wrapper">
					<!-- Slides -->
					<?php
						etheme_override_shortcodes();
						echo do_shortcode($content);
						etheme_restore_shortcodes(); 
					?>
				</div>
				<?php if ( in_array( $atts['nav'], array( 'bullets', 'arrows_bullets' ) ) ) { ?>
					<div class="swiper-pagination swiper-nav"></div>
				<?php } 

				if ( in_array( $atts['nav'], array( 'arrows', 'arrows_bullets' ) ) ) { ?>
					<div class="swiper-custom-left swiper-nav"></div>
					<div class="swiper-custom-right swiper-nav"></div>
				<?php } ?>
			</div>
		</div>

		<?php 

        if ( $atts['is_preview'] ) 
            echo parent::initPreviewJs();

		unset($options);
		unset($atts); 
		
		return ob_get_clean();
	}
}
