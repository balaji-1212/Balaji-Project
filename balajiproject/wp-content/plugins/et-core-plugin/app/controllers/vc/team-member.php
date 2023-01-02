<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Team Member shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Team_Member extends VC {

	function hooks() {
		$this->register_vc_team_member();
	}

	function register_vc_team_member() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$team_member_params = array(
			'name' => 'Team Member',
			'base' => 'team_member',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Team-member.png',
			'description' => esc_html__('Display image with text and socials', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array(
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Content', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Member name', 'xstore-core'),
					'param_name' => 'name'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Member email', 'xstore-core'),
					'param_name' => 'email'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Position', 'xstore-core'),
					'param_name' => 'position'
				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__('Member information', 'xstore-core' ),
					'param_name' => 'content',
					'value' => esc_html__('Member description', 'xstore-core' )
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Layout', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_image_select',
					'heading' => esc_html__('Display Type', 'xstore-core' ),
					'param_name' => 'type',
					'value' => array( 
						esc_html__('Vertical', 'xstore-core') => 1,
						esc_html__('Horizontal', 'xstore-core') => 2,
						esc_html__('Overlay content', 'xstore-core') => 3
					),
					'images_value' => array(
						1   => ET_CORE_SHORTCODES_IMAGES . 'team-member/Vertical.svg',
						2   => ET_CORE_SHORTCODES_IMAGES . 'team-member/Horizontal.svg',
						3   => ET_CORE_SHORTCODES_IMAGES . 'team-member/Overlay.svg',
					),
					'et_tooltip' => true,
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Image', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__('Avatar', 'xstore-core'),
					'param_name' => 'img'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Image size', 'xstore-core' ),
					'param_name' => 'img_size',
					'hint' => $strings['hint']['img_size']
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Image effect', 'xstore-core'),
					'param_name' => 'img_effect', // @radio-image type
					'value' => array( 
						__('Zoom In', 'xstore-core') => 2,
						// __('Slide right', 'xstore-core') => 6,
						__('Zoom out', 'xstore-core') => 4,
						__('Scale out', 'xstore-core') => 5,
						__('None', 'xstore-core') => 3,
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Image position', 'xstore-core' ),
					'param_name' => 'img_position',
					'dependency' => array('element' => 'type', 'value' => array('2')),
					'value' => array(
						esc_html__('Left', 'xstore-core') => 'left',
						esc_html__('Right', 'xstore-core') => 'right'
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Content styles', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Content Align', 'xstore-core'),
					'param_name' => 'text_align',
					'value' => $strings['value']['align'],
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Content position', 'xstore-core' ),
					'param_name' => 'content_position',
					'dependency' => array('element' => 'type', 'value' => array('2')),
					'value' => $strings['value']['valign'],
					'tooltip_values' => $strings['tooltip']['valign'],
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Social icons', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Icons position', 'xstore-core'),
					'param_name' => 'icons_position',
					'value' => array(
						esc_html__('On image', 'xstore-core') => 'img',
						esc_html__('In content', 'xstore-core') => 'content',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Facebook link', 'xstore-core'),
					'param_name' => 'facebook'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Twitter link', 'xstore-core'),
					'param_name' => 'twitter'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Instagram link', 'xstore-core'),
					'param_name' => 'instagram'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Skype name', 'xstore-core'),
					'param_name' => 'skype'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Pinterest link', 'xstore-core'),
					'param_name' => 'pinterest'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('LinkedIn link', 'xstore-core'),
					'param_name' => 'linkedin'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Tumblr link', 'xstore-core'),
					'param_name' => 'tumblr'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('YouTube link', 'xstore-core'),
					'param_name' => 'youtube'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Whatsapp link', 'xstore-core'),
					'param_name' => 'whatsapp'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Vimeo link', 'xstore-core'),
					'param_name' => 'vimeo'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('RSS link', 'xstore-core'),
					'param_name' => 'rss'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('VK link', 'xstore-core'),
					'param_name' => 'vk'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Houzz link', 'xstore-core'),
					'param_name' => 'houzz'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Tripadvisor link', 'xstore-core'),
					'param_name' => 'tripadvisor'
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Links target', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'param_name' => 'target',
					'value' => array(
						esc_html__('Current window', 'xstore-core') => '_self',
						esc_html__('Blank', 'xstore-core') => '_blank',
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
					'hint' => $strings['hint']['el_class']
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Element styles', 'xstore-core' ),
					'group' => esc_html__('Styles', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Name color', 'xstore-core'),
					'param_name' => 'name_color',
					'value' => '',
					'group' => esc_html__('Styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Position color', 'xstore-core'),
					'param_name' => 'position_color',
					'value' => '',
					'group' => esc_html__('Styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Email color', 'xstore-core'),
					'param_name' => 'email_color',
					'value' => '',
					'group' => esc_html__('Styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Email link color', 'xstore-core'),
					'param_name' => 'email_link_color',
					'value' => '',
					'group' => esc_html__('Styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Content color', 'xstore-core'),
					'param_name' => 'content_color',
					'value' => '',
					'group' => esc_html__('Styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'class' => '',
					'heading' => esc_html__('Overlay background', 'xstore-core'),
					'param_name' => 'overlay_bg',
					'value' => '',
					'group' => esc_html__('Styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_col-xs-6 vc_column',
				),				
				array(
					'type' => 'colorpicker',
					'class' => '',
					'heading' => esc_html__('Content background', 'xstore-core'),
					'param_name' => 'content_bg',
					'value' => '#fff',
					'group' => esc_html__('Styles', 'xstore-core'),
					'dependency' => array(
						'element' => 'type', 
						'value' => array('2', '3')
					),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__('Icons styles', 'xstore-core'),
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Size', 'xstore-core'),
					'param_name' => 'size',
					'value' => array(
						esc_html__('Normal', 'xstore-core') => 'normal',
						esc_html__('Small', 'xstore-core') => 'small',
						esc_html__('Large', 'xstore-core') => 'large'
					),
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Icons Align', 'xstore-core'),
					'param_name' => 'align',
					'value' => array(
	                    '<span class="dashicons dashicons-editor-aligncenter"></span>' => 'center', 
	                    '<span class="dashicons dashicons-editor-alignleft"></span>' => 'left', 
	                    '<span class="dashicons dashicons-editor-alignright"></span>' => 'right'
					),
					'tooltip_values' => $strings['tooltip']['align'],
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_col-xs-6 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__('Filled icons', 'xstore-core'),
					'param_name' => 'filled',
					'group' => esc_html__('Icons styles', 'xstore-core'),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__('Tooltips for icons', 'xstore-core'),
					'param_name' => 'tooltip',
					'group' => esc_html__('Icons styles', 'xstore-core'),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__('Icons colors', 'xstore-core'),
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'colorpicker',
					'class' => '',
					'heading' => esc_html__('Icons background', 'xstore-core'),
					'param_name' => 'icons_bg',
					'value' => '',
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icons background hover', 'xstore-core'),
					'param_name' => 'icons_bg_hover',
					'value' => '',
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icons color', 'xstore-core'),
					'param_name' => 'icons_color',
					'value' => '',
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Icons color hover', 'xstore-core'),
					'param_name' => 'icons_color_hover',
					'value' => '',
					'group' => esc_html__('Icons styles', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
			)
		);  
		vc_map($team_member_params);
	}

}
