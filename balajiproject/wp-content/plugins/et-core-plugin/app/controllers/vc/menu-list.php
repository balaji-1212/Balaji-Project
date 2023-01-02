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
class Menu_List extends VC {

	function hooks() {
		$this->register_menu_list();
	}

	function register_menu_list() {

		require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-custom-heading-element.php' );

		$title = $this->heading_options('title', esc_html__( 'Typography', 'xstore-core' ));

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array_merge( array(
            array(
                'type' => 'xstore_title_divider',
                'title' => esc_html__( 'Title', 'xstore-core' ),
                'param_name' => 'divider'.$counter++
            ),
			array(
				'type' => 'textfield',
				'heading' => 'Title',
				'param_name' => 'title',
				'admin_label' => true,
				'edit_field_class' => 'vc_col-md-9 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => $strings['heading']['use_custom_font'],
				'param_name' => 'use_custom_fonts_title',
				'edit_field_class' => 'vc_col-md-3 vc_column',
			),
			array(
				'type' => 'vc_link',
				'heading' => 'Link',
				'param_name' => 'link',
				'admin_label' => true,
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('Label', 'xstore-core'),
				'param_name' => 'label',
				'admin_label' => true,
				'value' => array(
					esc_html__( 'Unset', 'xstore-core' ) => '',
					esc_html__( 'Hot', 'xstore-core' ) => 'hot',
					esc_html__( 'Sale', 'xstore-core' ) => 'sale',
					esc_html__( 'New', 'xstore-core' ) => 'new'
				),
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('Text transform', 'xstore-core'),
				'param_name' => 'transform',
				'value' => array( 
					esc_html__( 'Default', 'xstore-core' ) => '',
					esc_html__( 'Uppercase', 'xstore-core' ) => 'text-uppercase',
					esc_html__( 'Lowercase', 'xstore-core' ) => 'text-lowercase', 
					esc_html__( 'Capitalize', 'xstore-core' ) => 'text-capitalize', 
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => $strings['heading']['align'],
				'param_name' => 'align',
				'value' => $strings['value']['align'],
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
            array(
                'type' => 'xstore_title_divider',
                'title' => esc_html__( 'Title icon', 'xstore-core' ),
                'param_name' => 'divider'.$counter++
            ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Add icon ?', 'xstore-core' ),
				'param_name' => 'add_icon',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'xstore-core' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'xstore-core' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'xstore-core' ) => 'openiconic',
					esc_html__( 'Typicons', 'xstore-core' ) => 'typicons',
					esc_html__( 'Entypo', 'xstore-core' ) => 'entypo',
					esc_html__( 'Linecons', 'xstore-core' ) => 'linecons',
					esc_html__( 'Mono Social', 'xstore-core' ) => 'monosocial',
					esc_html__( 'XStore icons', 'xstore-core' ) => 'xstore-icons',
					esc_html__( 'Upload image', 'xstore-core' ) => 'image',
				),
				// 'admin_label' => true,
				'param_name' => 'type',
				'hint' => esc_html__( 'Select icon library.', 'xstore-core' ),
				'dependency' => array(
					'element' => 'add_icon', 
					'value' => 'true'
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-adjust', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false,
				// default true, display an 'EMPTY' icon?
					'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'fontawesome',
				),
				'hint' => esc_html__( 'Select icon from library.', 'xstore-core' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_openiconic',
				'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'openiconic',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'openiconic',
				),
				'hint' => esc_html__( 'Select icon from library.', 'xstore-core' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_typicons',
				'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'typicons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'typicons',
				),
				'hint' => esc_html__( 'Select icon from library.', 'xstore-core' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_entypo',
				'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
					'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'entypo',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_linecons',
				'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'linecons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'linecons',
				),
				'hint' => esc_html__( 'Select icon from library.', 'xstore-core' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_monosocial',
				'value' => 'vc-mono vc-mono-fivehundredpx', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'monosocial',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'monosocial',
				),
				'hint' => esc_html__( 'Select icon from library.', 'xstore-core' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'xstore-core' ),
				'param_name' => 'icon_xstore-icons',
			  	'value' => 'et-icon et-checked', // default value to backend editor admin_label
			  	'settings' => array(
				  	'emptyIcon' => false,
				  	'type' => 'xstore-icons',
					  // default true, display an 'EMPTY' icon?
				  	'iconsPerPage' => 4000,
					  // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
				),
				'dependency' => array(
					'element' => 'type',
				  	'value' => 'xstore-icons',
				),
				'hint' => esc_html__( 'Select icon from library.', 'xstore-core' ),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icon styles', 'xstore-core' ),
				'group' => esc_html__('Icon', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Icon size',
				'param_name' => 'icon_size',
				'group' => esc_html__('Icon', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Icon border radius',
				'param_name' => 'icon_border_radius',
				'group' => esc_html__('Icon', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Colors', 'xstore-core' ),
				'group' => esc_html__('Icon', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon color', 'xstore-core'),
				'param_name' => 'icon_color',
				'group' => esc_html__('Icon', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon color (hover)', 'xstore-core'),
				'param_name' => 'icon_color_hover',
				'group' => esc_html__('Icon', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon background color', 'xstore-core'),
				'param_name' => 'icon_bg_color',
				'group' => esc_html__('Icon', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon background color (hover)', 'xstore-core'),
				'param_name' => 'icon_bg_color_hover',
				'group' => esc_html__('Icon', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Image', 'xstore-core' ),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'type',
					'value' => 'image',
				),
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__( 'Image', 'xstore-core' ),
				'param_name' => 'img',
				'dependency' => array(
					'element' => 'type',
					'value' => 'image',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Image size', 'xstore-core' ),
				'param_name' => 'img_size',
				'dependency' => array(
					'element' => 'type',
					'value' => 'image',
				),
				'hint' => $strings['hint']['img_size']
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Position of the image', 'xstore-core'),
				'param_name' => 'position',
				'value' => array( 
					__('Select position', 'xstore-core') => '',
					__('Left top', 'xstore-core') => 'left-top', 
					__('Left center', 'xstore-core') => 'left-center', 
					__('Left bottom', 'xstore-core') => 'left-bottom', 
					__('Center center', 'xstore-core') => 'center-center',
					__('Center bottom', 'xstore-core') => 'center-bottom',
					__('Right top', 'xstore-core') => 'right-top',
					__('Right center', 'xstore-core') => 'right-center',
					__('Right bottom', 'xstore-core') => 'right-bottom',
				),
				'dependency' => array(
					'element' => 'type',
					'value' =>'image'
				),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Link Paddings', 'xstore-core' ),
				'hint' => esc_html__('Note: CSS measurement units allowed', 'xstore-core'),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top',
				'param_name' => 'padding_top',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Right', 'xstore-core'),
				'param_name' => 'padding_right',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Bottom', 'xstore-core'),
				'param_name' => 'padding_bottom',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Left', 'xstore-core'),
				'param_name' => 'padding_left',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
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
				'hint' => $strings['hint']['el_class']
			),
		), $title,
		array(
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Colors', 'xstore-core' ),
				'group' => esc_html__('Hover', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'use_custom_fonts_title',
					'value' => 'true'
				),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Text color', 'xstore-core'),
				'param_name' => 'hover_color',
				'group' => esc_html__('Hover', 'xstore-core'),
				'dependency' => array(
					'element' => 'use_custom_fonts_title',
					'value' => 'true'
				),
			),
		),
		array(
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'xstore-core' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design', 'xstore-core' )
			),
		)
		);

		$menu_list_params = array(
			'name' => 'Menu List',
			'base' => 'et_menu_list',
			'category' => $strings['category'], 
			'content_element' => true,
			'is_container' => true,
			'icon' => ETHEME_CODE_IMAGES . 'vc/Menu.png',
			'description' => esc_html__('Display items with subitems', 'xstore-core'),
			'show_settings_on_create' => true,
			'as_parent' => array(
				'only' => 'et_menu_list_item, vc_single_image',
			),
			'js_view' => 'VcColumnView',
			'params' => $params
		);

		vc_map($menu_list_params);
	}

	function heading_options($type, $text) {

		$heading = vc_map_integrate_shortcode( vc_custom_heading_element_params(), $type.'_', $text, array(
			'exclude' => array(
				'link',
				'source',
				'text',
				'css',
				'el_class',
				'css_animation'
			),
		), array(
			'element' => 'use_custom_fonts_'.$type,
			'value' =>'true',
		) );

		// This is needed to remove custom heading _align options.
		if ( is_array( $heading ) && ! empty( $heading ) ) {
			foreach ( $heading as $key => $param ) {
				if ( is_array( $param ) && isset( $param['type'] ) && 'font_container' === $param['type'] ) {
					$heading[ $key ]['value'] = '';
					if ( isset( $param['settings'] ) && is_array( $param['settings'] ) && isset( $param['settings']['fields'] ) ) {
						$sub_key = array_search( 'text_align', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['text_align'] ) ) {
							unset( $heading[ $key ]['settings']['fields']['text_align'] );
						}
					}
				}
			}
		}

		$heading = array_merge(
			array(
				array(
					'type' => 'xstore_title_divider',
					'title' => $text,
					'param_name' => 'divider'.$type,
					'group' => $text,
					'dependency' => array(
						'element' => 'use_custom_fonts_'.$type,
						'value' => 'true',
					),
				),
			),
			array(
				array(
					'type' => 'textfield',
					'heading' => 'Letter spacing',
					'param_name' => 'spacing',
					'group' => esc_html__( 'Typography', 'xstore-core' ),
					'hint' => esc_html__('Enter letter spacing', 'xstore-core'),
					'dependency' => array(
						'element' => 'use_custom_fonts_title',
						'value' =>'true'
					),
				),
			),
			$heading
		);

		return $heading;
	}

}
