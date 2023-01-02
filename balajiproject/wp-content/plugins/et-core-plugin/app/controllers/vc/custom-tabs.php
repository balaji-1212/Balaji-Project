<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Custom Tabs shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/vc
 */
class Custom_Tabs extends VC {

	function hooks() {
		$this->register_tabs();
	}

	function register_tabs() {

		$strings = $this->etheme_vc_shortcodes_strings();

		$params_tabs = array(
			'name' => __( 'Tabs', 'xstore-core' ),
			'base' => 'et_tabs',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Tabs.png',
			'description' => esc_html__('Tabbed content', 'xstore-core'),
			'is_container' => true,
			'show_settings_on_create' => false,
			'as_parent' => array(
				'only' => 'et_tab',
			),
			'category' => $strings['category'], 
			'params' => array(
				array(
					"type" => "xstore_button_set",
					"heading" => __("Title style", 'xstore-core'),
					"param_name" => "title_style",
					"value" => array(
						__("Default", 'xstore-core') => 'default',
						__("On hover", 'xstore-core') => 'hover',

					),
				),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'el_class',
					'hint' => $strings['hint']['el_class'],
				),
			),
			'js_view' => 'VcBackendTtaTabsView',
			'custom_markup' => '
			<div class="vc_tta-container" data-vc-action="collapse">
			<div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
			<div class="vc_tta-tabs-container">'
			. '<ul class="vc_tta-tabs-list">'
			. '<li class="vc_tta-tab et-tab-label" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="et_tab"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
			. '</ul>
			</div>
			<div class="vc_tta-panels vc_clearfix {{container-class}}">
			{{ content }}
			</div>
			</div>
			</div>',
			'default_content' => '
			[et_tab title="' . sprintf( '%s %d', __( 'Tab', 'xstore-core' ), 1 ) . '"][/et_tab]
			[et_tab title="' . sprintf( '%s %d', __( 'Tab', 'xstore-core' ), 2 ) . '"][/et_tab]
			',
			'admin_enqueue_js' => array(
				vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
			)
		);

		vc_map($params_tabs);

		$params_tab = array(
			'name' => __( 'Tab', 'xstore-core' ),
			'base' => 'et_tab',
			'icon' => 'icon-wpb-ui-tta-section',
			'allowed_container_element' => 'vc_row',
			'is_container' => true,
			'show_settings_on_create' => false,
			'as_child' => array(
				'only' => 'et_tabs',
			),
			'category' => $strings['category'], 
			'params' => array(
				array(
					'type' => 'textfield',
					'param_name' => 'title',
					'heading' => __( 'Title', 'xstore-core' ),
					'hint' => __( 'Enter section title (Note: you can leave it empty).', 'xstore-core' ),
				),
				array(
					'type' => 'attach_image',
					"heading" => esc_html__("Tab Image", 'xstore-core'),
					"param_name" => "img",
					"group" => "Image",
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Image size", 'xstore-core' ),
					"param_name" => "img_size",
					"hint" => $strings['hint']['img_size'],
					"group" => "Image",
				),
				array(
					'type' => 'el_id',
					'param_name' => 'tab_id',
					'settings' => array(
						'auto_generate' => true,
					),
					'heading' => __( 'Section ID', 'xstore-core' ),
					'hint' => __( 'Enter section ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'xstore-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'el_class',
					'hint' => $strings['hint']['el_class'],
				),
			),
			'js_view' => 'VcBackendTtaSectionView',
			'custom_markup' => '
			<div class="vc_tta-panel-heading">
			<h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
			</div>
			<div class="vc_tta-panel-body">
			{{ editor_controls }}
			<div class="{{ container-class }}">
			{{ content }}
			</div>
			</div>',
			'default_content' => '',
		);

		vc_map($params_tab);
	}

}
