<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Menu shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Menu extends VC {

	function hooks() {
		$this->register_menu();
	}

	function register_menu() {
		
		$is_admin = $this->is_admin();
		
		$menu_params = array();
		if ( $is_admin ) {
			$menus       = wp_get_nav_menus();
			foreach ( $menus as $menu ) {
				$menu_params[ $menu->name ] = $menu->term_id;
			}
		}

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
	        array(
	            'type' => 'xstore_title_divider',
	            'title' => esc_html__( 'Content', 'xstore-core' ),
	            'param_name' => 'divider'.$counter++
	        ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'xstore-core' ),
				'param_name' => 'title'
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Menu', 'xstore-core' ),
				'param_name' => 'menu',
				'value' => $menu_params,
				'admin_label' => true
			),
	        array(
	            'type' => 'xstore_title_divider',
	            'title' => esc_html__( 'Style', 'xstore-core' ),
	            'param_name' => 'divider'.$counter++
	        ),
			array(
				'type' => 'xstore_image_select',
				'heading' => esc_html__( 'Type', 'xstore-core' ),
				'param_name' => 'style',
				'value' => array(
					esc_html__( 'Vertical', 'xstore-core' ) => 'vertical',
					esc_html__( 'Horizontal', 'xstore-core' ) => 'horizontal',
					esc_html__( 'Simple list', 'xstore-core' ) => 'menu-list',
				),
				'images_value' => array(
					'vertical'   => ET_CORE_SHORTCODES_IMAGES . 'menu/Vertical.svg',
					'horizontal'   => ET_CORE_SHORTCODES_IMAGES . 'menu/Horizontal.svg',
					'menu-list'   => ET_CORE_SHORTCODES_IMAGES . 'menu/Simple-list.svg',
				),
				'et_tooltip' => true,
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__( 'Align', 'xstore-core' ),
				'param_name' => 'align',
				'value' => $strings['value']['align'],
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'tooltip_values' => $strings['tooltip']['align']
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide submenus', 'xstore-core' ),
				'param_name' => 'hide_submenus',
				'dependency' => array('element' => 'style', 'value_not_equal_to' => 'menu-list'),
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__( 'Submenu side', 'xstore-core' ),
				'param_name' => 'submenu_side',
				'value' => array(
					esc_html__( 'Bottom', 'xstore-core' ) => 'bottom',
					esc_html__( 'Top', 'xstore-core' ) => 'top',
				),
				'dependency' => array('element' => 'style', 'value' => 'horizontal'),
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__( 'Submenu side', 'xstore-core' ),
				'param_name' => 'submenu_side_vertical',
				'value' => array(
					esc_html__( 'Right', 'xstore-core' ) => 'right',
					esc_html__( 'Left', 'xstore-core' ) => 'left',
				),
				'dependency' => array('element' => 'style', 'value' => 'vertical'),
			),
	        array(
	            'type' => 'xstore_title_divider',
	            'title' => esc_html__( 'Advanced', 'xstore-core' ),
	            'param_name' => 'divider'.$counter++
	        ),
			array(
				'type' => 'textfield',
				'heading' => $strings['heading']['el_class'],
				'param_name' => 'class'
			),
		);
		
		$params = array(
			'name' => 'Menu',
			'base' => 'menu',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Menu.png',
			'description' => esc_html__('Display menu', 'xstore-core'),
			'category' => $strings['category'],
			'params' => $params
		);

		vc_map( $params );
	}
}