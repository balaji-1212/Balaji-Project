<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Testimonials shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Testimonials extends VC {

	public function hooks() {
		if( !function_exists('etheme_get_option') || ! etheme_get_option( 'testimonials_type', 1 ) ) {
			return;
		} 
		
		$this->register_vc_testimonials();
	}

	public function register_vc_testimonials() {

		$counter = 0;

		$strings = $this->etheme_vc_shortcodes_strings();
		$testimonials_params = array(
			'name' => 'Testimonials Widget',
			'base' => 'testimonials',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Testimonials-widget.png',
			'description' => esc_html__('Display XStore testimonial with slider or grid', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Limit', 'xstore-core'),
					'param_name' => 'limit',
					'hint' => esc_html__('How many testimonials to show? Enter number.', 'xstore-core')
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Layout settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'dropdown', 
					'heading' => esc_html__('Display type', 'xstore-core' ),
					'param_name' => 'type',
					'value' => array( 
						esc_html__('Slider', 'xstore-core') => 'slider',
						esc_html__('Slider with grid', 'xstore-core') => 'slider-grid',
						esc_html__('Grid', 'xstore-core') => 'grid'
					),
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Slider settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++,
					'dependency' => array('element' => 'type', 'value' => array('slider', 'slider-grid'))
                ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Interval', 'xstore-core'),
					'param_name' => 'interval',
					'hint' => esc_html__('Interval between slides. In milliseconds. Default: 10000', 'xstore-core'),
					'dependency' => Array('element' => 'type', 'value' => array('slider', 'slider-grid'))
				),
                array(
                    'type' => 'xstore_slider',
                    'heading' => esc_html__( 'Columns', 'xstore-core' ),
                    'param_name' => 'slider_columns',
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 2,
                    'units' => '',
                    'dependency' => array('element' => 'type', 'value' => array('slider-grid')),
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Show Control Navigation', 'xstore-core' ),
					'param_name' => 'navigation',
					'dependency' => Array('element' => 'type', 'value' => array('slider', 'slider-grid')),
					'value' => array(
						esc_html__('Hide', 'xstore-core') => false,
						esc_html__('Show', 'xstore-core') => true
					)
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Styles settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++,
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Color scheme', 'xstore-core' ),
					'param_name' => 'color_scheme',
					'value' => $strings['value']['font_style']['type1']
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Advanced settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++,
					'dependency' => array('element' => 'type', 'value' => array('slider', 'slider-grid'))
                ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Category', 'xstore-core'),
					'param_name' => 'category',
					'hint' => esc_html__('Display testimonials from category.', 'xstore-core')
				),
			)

		);  

		vc_map($testimonials_params);
	}
}