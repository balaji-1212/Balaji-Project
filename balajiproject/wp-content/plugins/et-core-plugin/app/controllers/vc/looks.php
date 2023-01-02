<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Looks shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Looks extends VC {

	function hooks() {
		$this->register_looks();
	}

	function register_looks() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			'name' => 'Product Looks',
			'base' => 'et_looks',
			'category' => $strings['category'], 
			'content_element' => true,
			'is_container' => true,
			'icon' => ETHEME_CODE_IMAGES . 'vc/Products.png',
			'description' => esc_html__('Display products with banner', 'xstore-core'),
			'show_settings_on_create' => true,
			'as_parent' => array(
				'only' => 'et_the_look',
			),
			'params' => array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Advanced', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'class',
					'hint' => $strings['hint']['el_class']
				)
			),
			'js_view' => 'VcColumnView'
			
		);  
		
		vc_map($params);
	}

}
