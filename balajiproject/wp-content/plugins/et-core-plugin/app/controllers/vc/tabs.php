<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Tabs shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/vc
 */
class Tabs extends VC {

	function hooks() {
		$this->register_vc_tabs();
	}

	function register_vc_tabs() {
		$setting_vc_tabs = array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Tabs type', 'xstore-core' ),
				'param_name' => 'type',
				'value' => array(__('Default', 'xstore-core' ) => '',
					esc_html__('Products Tabs', 'xstore-core' ) => 'products-tabs',
					esc_html__('Left bar', 'xstore-core' ) => 'left-bar',
					esc_html__('Right bar', 'xstore-core' ) => 'right-bar')
			),
		);
		vc_add_params('vc_tabs', $setting_vc_tabs);

		vc_map( array(
			'name' => esc_html__('Tab', 'xstore-core' ),
			'base' => 'vc_tab',
			'allowed_container_element' => 'vc_row',
			'description' => esc_html__('Tabbed content', 'xstore-core'),
			'is_container' => true,
			'content_element' => false,
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Title', 'xstore-core' ),
					'param_name' => 'title',
					'hint' => esc_html__('Tab title.', 'xstore-core' )
				),
				array(
					'type' => 'icon',
					'heading' => esc_html__('Icon', 'xstore-core'),
					'param_name' => 'icon'
				),
				array(
					'type' => 'tab_id',
					'heading' => esc_html__('Tab ID', 'xstore-core' ),
					'param_name' => 'tab_id'
				)
			),
			'js_view' => 'VcTabView'
		) );
	}
}
