<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Follow shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Follow extends VC {

	function hooks() {
		$this->register_follow();
	}

	function register_follow() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Social icons', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
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
				'heading' => esc_html__('Whatsapp link', 'xstore-core'),
				'param_name' => 'whatsapp'
			),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Snapchat link', 'xstore-core'),
                'param_name' => 'snapchat'
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
				'heading' => esc_html__('Telegram link', 'xstore-core'),
				'param_name' => 'telegram'
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
				'type' => 'textfield',
				'heading' => esc_html__('Etsy link', 'xstore-core'),
				'param_name' => 'etsy'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Tik-tok link', 'xstore-core'),
				'param_name' => 'tik-tok'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Strava link', 'xstore-core'),
				'param_name' => 'strava'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Cafecito link', 'xstore-core'),
				'param_name' => 'cafecito'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Dribbble link', 'xstore-core'),
				'param_name' => 'dribbble'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Kofi link', 'xstore-core'),
				'param_name' => 'kofi'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Line link', 'xstore-core'),
				'param_name' => 'line'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Patreon link', 'xstore-core'),
				'param_name' => 'patreon'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Reddit link', 'xstore-core'),
				'param_name' => 'reddit'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Discord link', 'xstore-core'),
				'param_name' => 'discord'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Email link', 'xstore-core'),
				'param_name' => 'email'
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Untapped link', 'xstore-core'),
				'param_name' => 'untapped'
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
				'title' => esc_html__( 'Icons styles', 'xstore-core' ),
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
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
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('Align', 'xstore-core'),
				'param_name' => 'align', 
				'value' => $strings['value']['align'],
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__('Filled icons', 'xstore-core'),
				'param_name' => 'filled',
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Icons colors', 'xstore-core' ),
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icons color', 'xstore-core'),
				'param_name' => 'icons_color',
				'value' => '',

				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icons color hover', 'xstore-core'),
				'param_name' => 'icons_color_hover',
				'value' => '',
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icons background', 'xstore-core'),
				'param_name' => 'icons_bg',
				'value' => '',
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'dependency' =>  array('element' => 'filled', 'value' => 'true' ),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icons background hover', 'xstore-core'),
				'param_name' => 'icons_bg_hover',
				'value' => '',
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'dependency' =>  array('element' => 'filled', 'value' => 'true' ),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Border radius', 'xstore-core' ),
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'param_name' => 'divider'.$counter++,
				'dependency' =>  array('element' => 'filled', 'value' => 'true' ),
			),
			array(
				'type' => 'textfield',
				'param_name' => 'icons_border_radius',
				'group' => esc_html__( 'Icons styles', 'xstore-core' ),
				'dependency' =>  array('element' => 'filled', 'value' => 'true' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'xstore-core' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design', 'xstore-core' )
			),
		);

		$banner_params = array(
			'name' => 'Social links',
			'base' => 'follow',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Social-links.png',
			'description' => esc_html__('Display social icons with links', 'xstore-core'),
			'category' => $strings['category'],
			'params' => $params
		);

		vc_map($banner_params);
	}
}
