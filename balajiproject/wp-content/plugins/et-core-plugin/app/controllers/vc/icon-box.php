<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Icon Box shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Icon_Box extends VC {

	function hooks() {
		$this->register_vc_icon_box();
	}

	function register_vc_icon_box() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			'name' => 'Icon Box',
			'base' => 'icon_box',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Icon-Box.png',
			'description' => esc_html__('Text block with icon', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array(
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Content', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'textfield',
					'heading' => 'Title',
					'param_name' => 'title',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Title text transform', 'xstore-core'),
					'param_name' => 'title_text_transform', // radio-buttons type 
					'value' => array( 
						esc_html__('Uppercase', 'xstore-core') => 'uppercase', 
						esc_html__('Capitalize', 'xstore-core') => 'capitalize', 
						esc_html__('Lowercase', 'xstore-core') => 'lowercase',
						esc_html__('None', 'xstore-core') => 'none', 
					),
				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => 'Content text',
					'param_name' => 'content'
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Content style', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['align'],
					'param_name' => 'align', // radio-buttons type 
					'value' => array( 
						esc_html__('Default', 'xstore-core') => '', 
						esc_html__('Start', 'xstore-core') => 'start', 
						esc_html__('Center', 'xstore-core') => 'center', 
						esc_html__('End', 'xstore-core') => 'end'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['valign'],
					'param_name' => 'valign', // radio-buttons type 
					'value' => $strings['value']['valign2'],
					'dependency' => array( 
						'element' => 'position', 
						'value_not_equal_to' => 'top' 
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Spacing',
					'param_name' => 'content_spacing',
					'hint' => esc_html__( 'Space between content elements.', 'xstore-core' ),
					'value' => '10px',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Advanced', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('On click action', 'xstore-core'),
					'param_name' => 'on_click',
					'value' => array( 
						esc_html__('None', 'xstore-core') => 'none', 
						esc_html__('Open custom link', 'xstore-core') => 'link',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => 'Custom Link',
					'param_name' => 'custom_link',
					'dependency' => array( 
						'element' => 'on_click', 
						'value_not_equal_to' => 'none' 
					),
					'hint' => esc_html__('Enter URL if you want this Icon Box to have a link (Note: parameters like "mailto:" are also accepted).', 'xstore-core')
				),
				array(
					'type' => 'xstore_button_set',
					'param_name' => 'target',
					'value' => array(
						esc_html__('Current window', 'xstore-core') => '_self',
						esc_html__('Blank', 'xstore-core') => '_blank',
					),
					'dependency' => array(
						'element' => 'on_click',
						'value_not_equal_to' => 'none'
					),
				),
	          	array(
	          		'type' => 'textfield',
	          		'heading' => $strings['heading']['el_class'],
	          		'param_name' => 'class',
	          		'hint' => $strings['hint']['el_class']
	          	),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Icon settings', 'xstore-core' ),
					'group' => esc_html__('Icon', 'xstore-core'),
					'param_name' => 'divider'.$counter++
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
					'admin_label' => true,
					'param_name' => 'type',
					'group' => esc_html__('Icon', 'xstore-core'),
					'hint' => esc_html__( 'Select icon library.', 'xstore-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'xstore-core' ),
					'param_name' => 'icon_fontawesome',
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'title' => esc_html__( 'SVG code', 'xstore-core' ),
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
					'dependency' => array(
						'element' => 'type',
						'value' => 'svg',
					),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Image', 'xstore-core' ),
					'group' => esc_html__('Icon', 'xstore-core'),
					'param_name' => 'divider'.$counter++,
					'dependency' => array(
						'element' => 'type',
						'value' => 'image',
					),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Icon', 'xstore-core' ),
					'param_name' => 'img',
					'group' => esc_html__('Icon', 'xstore-core'),
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
					'group' => esc_html__('Icon', 'xstore-core'),
					'dependency' => array(
						'element' => 'type',
						'value' => 'image',
					),
					'hint' => $strings['hint']['img_size']
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Icon styles', 'xstore-core' ),
					'group' => esc_html__('Icon', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'View', 'xstore-core' ),
					'param_name' => 'view',
					'group' => esc_html__('Icon', 'xstore-core'),
					'value' => array(
						esc_html__( 'Default', 'xstore-core' ) => 'default',
						esc_html__( 'Stacked', 'xstore-core' ) => 'stacked',
						esc_html__( 'Framed', 'xstore-core' ) => 'framed',
					),
					'admin_label' => true,
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icon color 01', 'xstore-core'),
					'param_name' => 'color',
					'group' => esc_html__('Icon', 'xstore-core'),
					'value' => '#555',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icon color 01 (hover)', 'xstore-core'),
					'param_name' => 'color_hover',
					'group' => esc_html__('Icon', 'xstore-core'),
					'value' => '#888',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icon background color', 'xstore-core'),
					'param_name' => 'bg_colour',
					'value' => '#e1e1e1',
					'group' => esc_html__('Icon', 'xstore-core'),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'view',
						'value_not_equal_to' => 'default',
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icon background color (hover)', 'xstore-core'),
					'param_name' => 'bg_color_hover',
					'value' => '#fafafa',
					'group' => esc_html__('Icon', 'xstore-core'),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'view',
						'value_not_equal_to' => 'default',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => 'Icon size',
					'group' => esc_html__('Icon', 'xstore-core'),
					'param_name' => 'size',
					'dependency' => array(
						'element' => 'type',
						'value_not_equal_to' => 'image',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Layout', 'xstore-core' ),
					'group' => esc_html__('Icon', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Position of the icon', 'xstore-core'),
					'param_name' => 'position',
					'admin_label' => true,
					'group' => esc_html__('Icon', 'xstore-core'),
					'value' => array( 
						esc_html__('Left', 'xstore-core') => 'left', 
						esc_html__('Top', 'xstore-core') => 'top',
						esc_html__('Right', 'xstore-core') => 'right'
					),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Icon advanced styles', 'xstore-core' ),
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				// spacing
				array(
					'type' => 'textfield',
					'heading' => 'Spacing',
					'param_name' => 'icon_spacing',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Paddings', 'xstore-core' ),
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'textfield',
					'heading' => 'Top',
					'param_name' => 'padding_top',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Right',
					'param_name' => 'padding_right',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Bottom',
					'param_name' => 'padding_bottom',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Left',
					'param_name' => 'padding_left',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '',
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Border radius', 'xstore-core' ),
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'param_name' => 'divider'.$counter++,
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
				),
				array(
					'type' => 'textfield',
					'heading' => 'Top left',
					'param_name' => 'border_radius_top_left',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Top right',
					'param_name' => 'border_radius_top_right',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Bottom right',
					'param_name' => 'border_radius_bottom_right',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Bottom left',
					'param_name' => 'border_radius_bottom_left',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					// 'dependency' => array(
					// 	'element' => 'type',
					// 	'value_not_equal_to' => 'image',
					// ),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Borders', 'xstore-core' ),
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'param_name' => 'divider'.$counter++,
					'dependency' => array(
						'element' => 'view',
						'value' => 'framed',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => 'Top',
					'param_name' => 'border_top',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '2px',
					'dependency' => array(
						'element' => 'view',
						'value' => 'framed',
					),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Right',
					'param_name' => 'border_right',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '2px',
					'dependency' => array(
						'element' => 'view',
						'value' => 'framed',
					),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Bottom',
					'param_name' => 'border_bottom',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '2px',
					'dependency' => array(
						'element' => 'view',
						'value' => 'framed',
					),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Left',
					'param_name' => 'border_left',
					'group' => esc_html__('Icon advanced', 'xstore-core'),
					'value' => '2px',
					'dependency' => array(
						'element' => 'view',
						'value' => 'framed',
					),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Button', 'xstore-core' ),
					'group' => esc_html__('Button', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
	          	array(
	          		'type' => 'textfield',
	          		'heading' => 'Button text',
	          		'param_name' => 'btn_text',
	          		'group' => esc_html__('Button', 'xstore-core')
	          	),
	          	array(
	          		'type' => 'textfield',
	          		'heading' => 'Button link',
	          		'param_name' => 'link',
	          		'group' => esc_html__('Button', 'xstore-core')
	          	),
					array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Button Styles', 'xstore-core' ),
					'group' => esc_html__('Button', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
	          	array(
	          		'type' => 'xstore_button_set',
	          		'heading' => esc_html__('Style', 'xstore-core'),
	          		'param_name' => 'btn_style',
	          		'value' => array(
	          			esc_html__('Default', 'xstore-core') => 'default',
	          			esc_html__('Active', 'xstore-core') => 'active',
	          			esc_html__('Border', 'xstore-core') => 'border',
	          			esc_html__('White', 'xstore-core') => 'white',
	          			esc_html__('Black', 'xstore-core') => 'black'
	          		),
	          		'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
	          	),
	          	array(
	          		'type' => 'xstore_button_set',
	          		'heading' => esc_html__('Size', 'xstore-core'),
	          		'param_name' => 'btn_size',
	          		'value' => array(
	          			esc_html__('Default', 'xstore-core') => 'default',
	          			esc_html__('Small', 'xstore-core') => 'small',
	          			esc_html__('Large', 'xstore-core') => 'large'
	          		),
	          		'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
	          	),
				// array(
				// 	'type' => 'xstore_title_divider',
				// 	'title' => esc_html__( 'Css box', 'xstore-core' ),
				// 	'group' => esc_html__( 'Design', 'xstore-core' ),
				// 	'edit_field_class' => 'vc_col-xs-12 css_box_tabs',
				// 	'param_name' => 'divider'.$counter++,
				// ),
	          	array(
	          		'type' => 'css_editor',
	          		'heading' => esc_html__( 'CSS box (Desktop)', 'xstore-core' ),
	          		'param_name' => 'css',
	          		'group' => esc_html__( 'Design', 'xstore-core' ),
	          		'edit_field_class' => 'vc_col-xs-12 vc_column et_css-query et_css-query-global',
	          	),
	   //        	array(
	   //        		'type' => 'css_editor',
	   //        		'heading' => esc_html__( 'CSS box (Tablet landscape)', 'xstore-core' ),
	   //        		'param_name' => 'css_md',
	   //        		'group' => esc_html__( 'Design', 'xstore-core' ),
	   //        		'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-1199',
	   //        	),
	   //        	array(
	   //        		'type' => 'css_editor',
	   //        		'heading' => esc_html__( 'CSS box (Tablet portrait)', 'xstore-core' ),
	   //        		'param_name' => 'css_sm',
	   //        		'group' => esc_html__( 'Design', 'xstore-core' ),
	   //        		'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-768',
	   //        	),
				// array(
	   //        		'type' => 'css_editor',
	   //        		'heading' => esc_html__( 'CSS box (Mobile)', 'xstore-core' ),
	   //        		'param_name' => 'css_xs',
	   //        		'group' => esc_html__( 'Design', 'xstore-core' ),
	   //        		'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-480',
	   //        	),	        
			)
		);  
		vc_map($params);
	}
}