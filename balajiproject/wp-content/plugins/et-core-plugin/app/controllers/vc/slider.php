<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Slider shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Slider extends VC {

	public function hooks() {
		$this->register_et_slider();
	}

	public function register_et_slider() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			'name' => 'Slider',
			'base' => 'etheme_slider',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Slider.png',
			'description' => esc_html__('Display slider with images and texts', 'xstore-core'),
			'content_element' => true,
			'is_container' => true,
			'js_view' => 'VcColumnView',
			'show_settings_on_create' => true,
			'as_parent' => array(
				'only' => 'etheme_slider_item',
			),
			'category' => $strings['category'],
			'params' => array(
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Style', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Height', 'xstore-core'),
					'param_name' => 'height',
					'value' => array(
						esc_html__('Full height', 'xstore-core') => 'full',
						esc_html__('Custom height', 'xstore-core') => 'custom',
						esc_html__('Height of content', 'xstore-core') => 'auto',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Custom height value', 'xstore-core'),
					'hint' => esc_html__( 'Allowed units px, em, vh, vw, %', 'xstore-core' ),
					'param_name' => 'height_value',
					'dependency' => array ('element' => 'height', 'value' => 'custom'),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Custom height value (0 - 992px)', 'xstore-core'),
					'hint' => esc_html__( 'Allowed units px, em, vh, vw, %', 'xstore-core' ),
					'param_name' => 'height_value_mobile',
					'dependency' => array ('element' => 'height', 'value' => 'custom'),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Navigation settings', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Type', 'xstore-core'),
					'param_name' => 'nav',
					'value' => array(
						esc_html__('Arrows + Bullets', 'xstore-core') => 'arrows_bullets',
						esc_html__('Arrows', 'xstore-core') => 'arrows',
						esc_html__('Bullets', 'xstore-core') => 'bullets', 
						esc_html__('Disable', 'xstore-core') => 'disable'           
					)
				),
				array (
					'type' => 'checkbox',
					'heading' => esc_html__('Show navigation on hover', 'xstore-core'),
					'param_name' => 'nav_on_hover',
					'dependency' => array ('element' => 'nav', 'value_not_equal_to' => 'disable'),
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Navigation colors', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++,
		            'dependency' => array ('element' => 'nav', 'value_not_equal_to' => 'disable'),
		        ),
				array (
					'type' => 'colorpicker',
					'heading' => esc_html__('Navigation color', 'xstore-core'),
					'param_name' => 'nav_color',
					'value' => '#222',
					'dependency' => array ('element' => 'nav', 'value_not_equal_to' => 'disable'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array (
					'type' => 'colorpicker',
					'heading' => esc_html__('Arrows background color', 'xstore-core'),
					'param_name' => 'arrows_bg_color',
					'value' => '#e1e1e1',
					'dependency' => array ('element' => 'nav', 'value_not_equal_to' => 'disable'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Slider settings', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__('Slider autoplay', 'xstore-core'),
					'param_name' => 'slider_autoplay',
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__('Slider loop', 'xstore-core'),
					'param_name' => 'slider_loop',
					'value' => array( esc_html__( 'Yes', 'xstore-core' ) => 'yes' ),
					'std' => 'yes',
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Slider stop on hover', 'xstore-core'),
					'param_name' => 'slider_stop_on_hover',
					'dependency' => array(
						'element' => 'slider_autoplay',
						'value' => 'true',
					),
					'default'=> 'yes',
					'value' => array(
						esc_html__('Yes', 'xstore-core') => 'yes',
						esc_html__('No', 'xstore-core') => 'no',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Interval', 'xstore-core'),
					'param_name' => 'slider_interval',
					'hint' => esc_html__( 'Interval between slides. In milliseconds. Default: 5000', 'xstore-core' ),
					'dependency' => array(
						'element' => 'slider_autoplay',
						'value' => 'true',
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__('Mousewheel control', 'xstore-core'),
					'param_name' => 'slider_mousewheel',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Slider transitions', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array (
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Transition style', 'xstore-core'),
					'param_name' => 'transition_effect',
					'value' => array(
						esc_html__('Slide', 'xstore-core') => 'slide',
						esc_html__('Fade', 'xstore-core') => 'fade',
						esc_html__('Cube', 'xstore-core') => 'cube',
						esc_html__('Coverflow', 'xstore-core') => 'coverflow',
						esc_html__('Flip', 'xstore-core') => 'flip',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Transition speed', 'xstore-core'),
					'param_name' => 'slider_speed',
					'hint' => esc_html__( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column'
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Style', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array (
					'type' => 'colorpicker',
					'heading' => esc_html__('Background color', 'xstore-core'),
					'hint' => esc_html__('Apply for slider and loader background colors', 'xstore-core'),
					'param_name' => 'bg_color',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Advanced', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'el_class',
					'description' => $strings['hint']['el_class']
				),
			),
		);

		vc_map($params);
	}

}
