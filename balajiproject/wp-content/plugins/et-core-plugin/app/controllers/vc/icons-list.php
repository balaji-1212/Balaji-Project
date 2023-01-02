<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
* Menu List shortcode.
*
* @since      1.4.4
* @package    ETC
* @subpackage ETC/Controllers/VC
*/
class Icons_List extends VC {

	function hooks() {
		$this->register_menu_list();
	}

	function register_menu_list() {

		// align
		// rows gap
		// cols gap
		
		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			array(
				'type' => 'xstore_button_set',
				'heading' => $strings['heading']['align'],
				'param_name' => 'align',
				'value' => $strings['value']['align'],
//				'edit_field_class' => 'vc_col-md-12 vc_column',
			),
			array(
				'type' => 'xstore_slider',
				'heading' => esc_html__('Rows gap', 'xstore-core'),
				'param_name' => 'rows_gap',
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => '',
				'units' => 'px',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_slider',
				'heading' => esc_html__('Cols gap', 'xstore-core'),
				'param_name' => 'cols_gap',
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => '',
				'units' => 'px',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => $strings['heading']['el_class'],
				'param_name' => 'class',
				'hint' => $strings['hint']['el_class']
			),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'xstore-core' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design', 'xstore-core' )
			)
		);

		$menu_list_params = array(
			'name' => 'Icons List',
			'base' => 'et_icons_list',
			'category' => $strings['category'], 
			'content_element' => true,
			'is_container' => true,
			'icon' => ETHEME_CODE_IMAGES . 'vc/Icon-Box.png',
			'description' => esc_html__('Display icons', 'xstore-core'),
			'show_settings_on_create' => true,
			'as_parent' => array(
				'only' => 'vc_icon, mpc_icon',
			),
			'js_view' => 'VcColumnView',
			'params' => $params
		);

		vc_map($menu_list_params);
	}

}
