<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Countdown shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Countdown extends VC {

	function hooks(){
		$this->register_countdown();
	}

	function register_countdown() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$countdown_params = array(
			'name' => 'Countdown',
			'base' => 'countdown',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Countdown.png',
			'description' => esc_html__('Display countdown timer', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array(
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'The endpoint', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
                array(
                  	'type' => 'xstore_slider',
                  	'heading' => esc_html__('Year', 'xstore-core'),
                  	'param_name' => 'year',
                  	'min' => date('Y'),
                  	'max' => (date('Y') + 10),
                  	'step' => 1,
                  	'default' => (date('Y') + 1),
                  	'units' => '',
                ),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Month', 'xstore-core'),
					'param_name' => 'month',
					'value' => array(
						esc_html__('January', 'xstore-core') => 'January',
						esc_html__('February', 'xstore-core') => 'February',
						esc_html__('March', 'xstore-core') => 'March',
						esc_html__('April', 'xstore-core') => 'April',
						esc_html__('May', 'xstore-core') => 'May',
						esc_html__('June', 'xstore-core') => 'June',
						esc_html__('July', 'xstore-core') => 'July',
						esc_html__('August', 'xstore-core') => 'August',
						esc_html__('September', 'xstore-core') => 'September',
						esc_html__('October', 'xstore-core') => 'October',
						esc_html__('November', 'xstore-core') => 'November',
						esc_html__('December', 'xstore-core') => 'December',
					)
				),
                array(
                  	'type' => 'xstore_slider',
                  	'heading' => esc_html__('Day', 'xstore-core'),
                  	'param_name' => 'day',
                  	'min' => 1,
                  	'max' => 31,
                  	'step' => 1,
                  	'default' => 1,
                  	'units' => '',
                ),
                array(
                  	'type' => 'xstore_slider',
                  	'heading' => esc_html__('Hour', 'xstore-core'),
                  	'param_name' => 'hour',
                  	'min' => 0,
                  	'max' => 23,
                  	'step' => 1,
                  	'default' => 0,
                  	'units' => '',
                ),
                array(
	                'type' => 'xstore_slider',
	                'heading' => esc_html__('Minutes', 'xstore-core'),
	                'param_name' => 'minute',
	                'min' => 0,
	                'max' => 59,
	                'step' => 1,
	                'default' => 0,
	                'units' => '',
                ),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Layout', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_image_select',
					'heading' => esc_html__('Display type', 'xstore-core'),
					'param_name' => 'type',
				    'value' => array( 
						esc_html__( 'Circle type', 'xstore-core' ) => 'type1',
						esc_html__( 'Simple type', 'xstore-core' ) => 'type2',
					),
					'images_value' => array(
						'type1'   => ET_CORE_SHORTCODES_IMAGES . 'countdown/Circle.svg',
						'type2'   => ET_CORE_SHORTCODES_IMAGES . 'countdown/Simple.svg',
					),
					'et_tooltip' => true,
					'hint' => esc_html__('Select display type', 'xstore-core'),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Color scheme', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'param_name' => 'scheme',
					'value' => array(
						esc_html__('Light', 'xstore-core') => 'white',
						esc_html__('Dark', 'xstore-core') => 'dark',
					)
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Advanced', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'class',
					'description' => $strings['hint']['el_class']
				)
			)

		);

		vc_map($countdown_params);

	}
}