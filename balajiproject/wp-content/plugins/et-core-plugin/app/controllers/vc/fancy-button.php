<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Fancy button shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Fancy_Button extends VC {

	function hooks() {
		$this->register_vc_fancy_button();
	}

	function register_vc_fancy_button() {
		
		$is_admin = $this->is_admin();
		
		$static_blocks = array();
		$static_blocks[] = "--choose--";
		
		if ( $is_admin ) {
			foreach ( etheme_get_static_blocks() as $block ) {
				$static_blocks[ $block['label'] ] = $block['value'];
			}
		}

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Global settings', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'textfield',
				'heading' => 'Title',
				'param_name' => 'title',
				'dependency' => array( 
					'element' => 'on_click', 
					'value' => 'popup' 
				),
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('On click action', 'xstore-core'),
				'param_name' => 'on_click',
				'value' => array(  
					esc_html__('Custom link', 'xstore-core') => 'link',
					esc_html__('Promo popup', 'xstore-core') => 'popup',
				)
			),
			array(
				'type' => 'vc_link',
				'heading' => 'Custom Link',
				'param_name' => 'link',
				'dependency' => array( 
					'element' => 'on_click', 
					'value' => 'link' 
				),
			),
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => 'Content text',
				'param_name' => 'content',
				'dependency' => array( 
					'element' => 'on_click', 
					'value' => 'popup' 
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use staticblock instead of content ?', 'xstore-core' ),
				'param_name' => 'staticblocks',
				'dependency' => array( 
					'element' => 'on_click', 
					'value' => 'popup' 
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Choose prebuilt static blocks', 'xstore-core'),
				'param_name' => 'staticblock', 
				'value' => $static_blocks,
				'dependency' => array( 
					'element' => 'staticblocks', 
					'value' => 'true' 
				),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icon settings', 'xstore-core' ),
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
					esc_html__( 'SVG icon', 'xstore-core' ) => 'svg',
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
				'type' => 'dropdown',
				'heading' => esc_html__('Position of the icon', 'xstore-core'),
				'param_name' => 'position',
				'value' => array( 
					esc_html__('Left', 'xstore-core') => 'left', 
					esc_html__('Top', 'xstore-core') => 'top',
					esc_html__('Right', 'xstore-core') => 'right'
				),
				'dependency' => array(
					'element' => 'add_icon', 
					'value' => 'true'
				),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'SVG code', 'xstore-core' ),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'type',
					'value' => 'svg',
				),
			),
			array(
				'type' => 'textarea_raw_html',
				'heading' => 'SVG code',
				'param_name' => 'svg',
				'dependency' => array(
					'element' => 'type',
					'value' => 'svg',
				),
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
				'hint' => esc_html__( 'Select icon from media library.', 'xstore-core' ),
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
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Advanced', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
          	array(
          		'type' => 'textfield',
          		'heading' => $strings['heading']['el_class'],
          		'param_name' => 'class',
          		'description' => $strings['hint']['el_class']
          	),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icon Styles', 'xstore-core' ),
				'group' => esc_html__('Icon Styles', 'xstore-core'),
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
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Spacing between icon and title',
				'param_name' => 'icon_spacing',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '5px',
				'dependency' => array(
					'element' => 'add_icon', 
					'value' => 'true'
				),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Colors', 'xstore-core' ),
				'group' => esc_html__('Icon Styles', 'xstore-core'),
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
				'group' => esc_html__('Icon Styles', 'xstore-core'),
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
				'group' => esc_html__('Icon Styles', 'xstore-core'),
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
				'group' => esc_html__('Icon Styles', 'xstore-core'),
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
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'type',
					'value_not_equal_to' => 'image',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Advanced settings', 'xstore-core' ),
				'hint' => esc_html__( 'Enable to set up paddings, borders and border radius', 'xstore-core' ),
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'add_icon', 
					'value' => 'true'
				),
			),
			array(
				'type' => 'checkbox',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'param_name' => 'advanced_icon_settings',
				'dependency' => array(
					'element' => 'add_icon', 
					'value' => 'true'
				),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icon Paddings', 'xstore-core' ),
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top',
				'param_name' => 'icon_padding_top',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Right',
				'param_name' => 'icon_padding_right',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom',
				'param_name' => 'icon_padding_bottom',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Left',
				'param_name' => 'icon_padding_left',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icon Border radius', 'xstore-core' ),
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top left',
				'param_name' => 'icon_border_radius_top_left',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top right',
				'param_name' => 'icon_border_radius_top_right',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom right',
				'param_name' => 'icon_border_radius_bottom_right',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom left',
				'param_name' => 'icon_border_radius_bottom_left',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icon Borders', 'xstore-core' ),
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top',
				'param_name' => 'icon_border_top',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Right',
				'param_name' => 'icon_border_right',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom',
				'param_name' => 'icon_border_bottom',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Left',
				'param_name' => 'icon_border_left',
				'group' => esc_html__('Icon Styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
    		array(
    			'type' => 'dropdown',
    			'heading' => esc_html__('Border style', 'xstore-core'),
    			'group' => esc_html__('Icon Styles', 'xstore-core'),
    			'param_name' => 'icon_border_style',
    			'value' => array(
    				'' => __('Unset', 'xstore-core'),
    				'solid' => __('Solid', 'xstore-core'),
    				'dashed' => __('Dashed', 'xstore-core'),
    				'dotted' => __('Dotted', 'xstore-core'),
    				'double' => __('Double', 'xstore-core'),
    				'groove' => __('Groove', 'xstore-core'),
    				'ridge' => __('Ridge', 'xstore-core'),
    				'inset' => __('Inset', 'xstore-core'),
    				'outset' => __('Outset', 'xstore-core'),
    			),
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
    			'edit_field_class' => 'vc_col-sm-6 vc_column',
    		),
    		array(
    			'type' => 'colorpicker',
    			'heading' => esc_html__('Border color', 'xstore-core'),
    			'group' => esc_html__('Icon Styles', 'xstore-core'),
    			'param_name' => 'icon_border_color',
				'dependency' => array(
					'element' => 'advanced_icon_settings',
					'value' => 'true',
				),
    			'edit_field_class' => 'vc_col-sm-6 vc_column',
    		),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Button style', 'xstore-core' ),
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++
			),
			array(
          		'type' => 'xstore_button_set',
          		'heading' => esc_html__('Style', 'xstore-core'),
          		'param_name' => 'btn_style',
          		'value' => array(
          			esc_html__('Default', 'xstore-core') => 'default',
          			esc_html__('Active', 'xstore-core') => 'active',
          			esc_html__('Bordered', 'xstore-core') => 'bordered',
          			esc_html__('Light', 'xstore-core') => 'white',
          			esc_html__('Dark', 'xstore-core') => 'black',
          			esc_html__('Custom', 'xstore-core') => 'custom'
          		),
          		'group' => esc_html__('Button styles', 'xstore-core'),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
          	),
          	array(
          		'type' => 'xstore_button_set',
          		'heading' => esc_html__('Size', 'xstore-core'),
          		'param_name' => 'btn_size',
          		'value' => array(
          			esc_html__('Default', 'xstore-core') => 'default',
          			esc_html__('Small', 'xstore-core') => 'small',
          			esc_html__('Medium', 'xstore-core') => 'medium',
          			esc_html__('Big', 'xstore-core') => 'big',
          			esc_html__('Custom', 'xstore-core') => 'custom'
          		),
          		'group' => esc_html__('Button styles', 'xstore-core'),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
          	),
			array( 
				'type' => 'xstore_responsive_size',
				'heading' => $strings['heading']['font_size'],
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'btn_responsive_font_size',
				'edit_field_class' => 'vc_col-sm-6 et_font-size-wrapper',
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
				'hint' => $strings['hint']['rs_font_size']
			),
			array( 
				'type' => 'xstore_responsive_size',
				'heading' => $strings['heading']['line_height'],
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'btn_responsive_line_height',
				'edit_field_class' => 'vc_col-sm-6 et_line-height-wrapper',
				'empty_units' => true,
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
				'hint' => $strings['hint']['rs_line_height']
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Paddings', 'xstore-core' ),
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top',
				'param_name' => 'padding_top',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Right',
				'param_name' => 'padding_right',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom',
				'param_name' => 'padding_bottom',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Left',
				'param_name' => 'padding_left',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_size',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Border radius', 'xstore-core' ),
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top left',
				'param_name' => 'border_radius_top_left',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top right',
				'param_name' => 'border_radius_top_right',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom right',
				'param_name' => 'border_radius_bottom_right',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom left',
				'param_name' => 'border_radius_bottom_left',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Borders', 'xstore-core' ),
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => 'Top',
				'param_name' => 'border_top',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Right',
				'param_name' => 'border_right',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Bottom',
				'param_name' => 'border_bottom',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Left',
				'param_name' => 'border_left',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'value' => '',
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
    		array(
    			'type' => 'dropdown',
    			'heading' => esc_html__('Border style', 'xstore-core'),
    			'group' => esc_html__('Button styles', 'xstore-core'),
    			'param_name' => 'border_style',
    			'value' => array(
    				'' => __('Unset', 'xstore-core'),
    				'solid' => __('Solid', 'xstore-core'),
    				'dashed' => __('Dashed', 'xstore-core'),
    				'dotted' => __('Dotted', 'xstore-core'),
    				'double' => __('Double', 'xstore-core'),
    				'groove' => __('Groove', 'xstore-core'),
    				'ridge' => __('Ridge', 'xstore-core'),
    				'inset' => __('Inset', 'xstore-core'),
    				'outset' => __('Outset', 'xstore-core'),
    			),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
    			'edit_field_class' => 'vc_col-sm-6 vc_column',
    		),
    		array(
    			'type' => 'colorpicker',
    			'heading' => esc_html__('Border color', 'xstore-core'),
    			'group' => esc_html__('Button styles', 'xstore-core'),
    			'param_name' => 'border_color',
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
    			'edit_field_class' => 'vc_col-sm-3 vc_column',
    		),
    		array(
    			'type' => 'colorpicker',
    			'heading' => esc_html__('Border color (hover)', 'xstore-core'),
    			'group' => esc_html__('Button styles', 'xstore-core'),
    			'param_name' => 'border_color_hover',
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
    			'edit_field_class' => 'vc_col-sm-3 vc_column',
    		),
    		array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Colors', 'xstore-core' ),
				'group' => esc_html__('Button styles', 'xstore-core'),
				'param_name' => 'divider'.$counter++,
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button color', 'xstore-core'),
				'param_name' => 'color',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button color (hover)', 'xstore-core'),
				'param_name' => 'color_hover',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button background color', 'xstore-core'),
				'param_name' => 'bg_color',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button background color (hover)', 'xstore-core'),
				'param_name' => 'bg_color_hover',
				'group' => esc_html__('Button styles', 'xstore-core'),
				'dependency' => array(
					'element' => 'btn_style',
					'value' => 'custom',
				),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
		);

		$button_params = array(
			'name' => 'Fancy Button',
			'base' => 'et_fancy_button',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Fancy-button.png',
			'description' => esc_html__('Create advanced button with popup / custom link on click'),
			'category' => $strings['category'],
			'params' => $params
		);

		vc_map($button_params);

	}
	
}