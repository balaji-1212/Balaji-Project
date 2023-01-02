<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Banner shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Banner extends VC {

	function hooks() {
		$this->register_vc_banner();
	}

	function register_vc_banner() {

		require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-custom-heading-element.php' );

		$strings = $this->etheme_vc_shortcodes_strings();

		$title = $this->heading_options('title', esc_html__( 'Title font', 'xstore-core' ), $strings);

		$subtitle = $this->heading_options('subtitle', esc_html__( 'Subtitle font', 'xstore-core' ), $strings);

		$counter = 0;
		$params = array_merge( array(
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Content', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'textfield',
				'heading' => 'Title',
				'param_name' => 'title',
				'edit_field_class' => 'vc_col-sm-9 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => $strings['heading']['use_custom_font'],
				'param_name' => 'use_custom_fonts_title',
				'edit_field_class' => 'vc_col-sm-3 vc_column on-side',
			),
			array(
				'type' => 'textfield',
				'heading' => 'Subitle',
				'param_name' => 'subtitle',
				'edit_field_class' => 'vc_col-sm-9 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => $strings['heading']['use_custom_font'],
				'param_name' => 'use_custom_fonts_subtitle',
				'edit_field_class' => 'vc_col-sm-3 vc_column on-side',
			),
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => 'Banner Mask Text',
				'param_name' => 'content',
				'value' => 'Some promo words'
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('Link', 'xstore-core'),
				'param_name' => 'link'
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Effects', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('Image hover effect', 'xstore-core'),
				'param_name' => 'type', 
				'value' => array( 
					__('Zoom In', 'xstore-core') => 2,
					__('Slide right', 'xstore-core') => 6,
					__('Zoom out', 'xstore-core') => 4,
					__('Scale out', 'xstore-core') => 5,
					__('None', 'xstore-core') => 3,
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('Text hover effect', 'xstore-core'),
				'param_name' => 'text_effect', 
				'value' => array( 
					__('None', 'xstore-core') => '',
					__('To top', 'xstore-core') => 1,
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'With diagonal (on hover)', 'xstore-core' ),
				'param_name' => 'type_with_diagonal',
				'hint' => esc_html__( 'Image effect with diagonal', 'xstore-core' ),
	   //        	'dependency' => array(
				// 	'element' => 'type',
				// 	'value_not_equal_to' => '' 
				// ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'With border animation (on hover)', 'xstore-core' ),
				'param_name' => 'type_with_border',
				'hint' => esc_html__( 'Image effect with border inside', 'xstore-core' ),
	   //        	'dependency' => array(
				// 	'element' => 'type',
				// 	'value_not_equal_to' => '' 
				// ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Style', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => $strings['heading']['align'],
				'param_name' => 'align',
				'value' => $strings['value']['align'],
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'tooltip_values' => $strings['tooltip']['align']
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => $strings['heading']['valign'],
				'param_name' => 'valign',
				'value' => $strings['value']['valign'],
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'tooltip_values' => $strings['tooltip']['valign']
			),
			array(
				'type' => 'xstore_button_set',
				'heading' => esc_html__('Font style', 'xstore-core'),
				'param_name' => 'font_style',
				'value' => $strings['value']['font_style']['type1'],
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			// fix backward compatibility from v.2.2.3
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Responsive fonts', 'xstore-core'),
				'param_name' => 'responsive_fonts',
				'value' => array( 
					__('Yes', 'xstore-core') => 1,
					__('No', 'xstore-core') => 0
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column hidden'
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Responsive', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide title on mobile', 'xstore-core' ),
				'param_name' => 'hide_title_responsive',
	          	'edit_field_class' => 'vc_col-sm-6 vc_column'
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide subtitle on mobile', 'xstore-core' ),
				'param_name' => 'hide_subtitle_responsive',
	          	'edit_field_class' => 'vc_col-sm-6 vc_column'
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide description on mobile', 'xstore-core' ),
				'param_name' => 'hide_description_responsive',
	          	'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide button on mobile', 'xstore-core' ),
				'param_name' => 'hide_button_responsive',
	          	'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Advanced', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hovered state by default', 'xstore-core' ),
				'param_name' => 'is_active',
				'hint' => esc_html__( 'Make banner with hovered effects by default', 'xstore-core' ),
			),
	        array(
	          	'type' => 'checkbox',
	          	'heading' => $strings['heading']['ajax'],
	          	'param_name' => 'ajax',
	        ),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Image', 'xstore-core' ),
				'group' => esc_html__( 'Image', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
         	array(
	          	'type' => 'attach_image',
	          	'heading' => esc_html__('Banner Image', 'xstore-core'),
	          	'param_name' => 'img',
	          	'group' => esc_html__( 'Image', 'xstore-core' ),
	        ),
	        array(
	          	'type' => 'textfield',
	          	'heading' => esc_html__('Banner image size', 'xstore-core' ),
	          	'param_name' => 'img_size',
	          	'hint' => $strings['hint']['img_size'],
	          	'group' => esc_html__( 'Image', 'xstore-core' ),
	          	'edit_field_class' => 'vc_col-sm-6 vc_column',
	        ),
	        array(
	          	'type' => 'textfield',
	          	'heading' => esc_html__('Banner image min height', 'xstore-core' ),
	          	'param_name' => 'img_min_size',
	          	'hint' => esc_html__('Enter image min-height. Example in pixels: 200px', 'xstore-core'),
	          	'group' => esc_html__( 'Image', 'xstore-core' ),
	          	'edit_field_class' => 'vc_col-sm-6 vc_column',
	        ),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Image Style', 'xstore-core' ),
				'group' => esc_html__( 'Image', 'xstore-core' ),
				'param_name' => 'divider'.$counter++
			),
			array(
				'type' => 'xstore_slider',
				'heading' => esc_html__('Image Opacity', 'xstore-core'),
				'param_name' => 'image_opacity',
				'min' => 0,
				'max' => 1,
				'step' => .1,
				'default' => 1,
				'units' => '',
				'description' => esc_html__('Enter value between 0.0 to 1 (0 is maximum transparency, while 1 is lowest)', 'xstore-core'),
				'group' => esc_html__( 'Image', 'xstore-core' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'xstore_slider',
				'heading' => esc_html__('Image Opacity on Hover', 'xstore-core'),
				'param_name' => 'image_opacity_on_hover',
				'min' => 0,
				'max' => 1,
				'step' => .1,
				'default' => 1,
				'units' => '',
				'description' => esc_html__('Enter value between 0.0 to 1 (0 is maximum transparency, while 1 is lowest)', 'xstore-core'),
				'group' => esc_html__( 'Image', 'xstore-core' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
	        array(
	          	'type' => 'colorpicker',
	          	'class' => '',
	          	'heading' => esc_html__('Background Color', 'xstore-core'),
	          	'hint' => esc_html__( 'Use image opacity option to add overlay effect with background', 'xstore-core' ),
	          	'param_name' => 'banner_color_bg',
	          	'value' => '',
	          	'group' => esc_html__( 'Image', 'xstore-core' ),
	        ),
			array(
				'type' => 'xstore_title_divider',
				'title' => esc_html__( 'Button', 'xstore-core' ),
				'group' => esc_html__('Button', 'xstore-core'),
				'param_name' => 'divider'.$counter++
			),
	        array(
				'type' => 'vc_link',
				'heading' => esc_html__('Button link', 'xstore-core'),
				'param_name' => 'button_link',
				'group' => esc_html__('Button', 'xstore-core'),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__('Button only on hover'),
				'param_name' => 'button_state',
				'group' => esc_html__('Button', 'xstore-core'),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button text color', 'xstore-core'),
				'param_name' => 'button_color',
				'group' => esc_html__('Button', 'xstore-core'),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button text color (hover)', 'xstore-core'),
				'param_name' => 'button_hover_color',
				'group' => esc_html__('Button', 'xstore-core'),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),

			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button background color', 'xstore-core'),
				'param_name' => 'button_bg',
				'group' => esc_html__('Button', 'xstore-core'),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button background color (hover)', 'xstore-core'),
				'param_name' => 'button_hover_bg',
				'group' => esc_html__('Button', 'xstore-core'),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Button border radius', 'xstore-core'),
				'param_name' => 'button_border_radius',
				'group' => esc_html__('Button', 'xstore-core'),
				'hint' => esc_html__('Use this field to add element border radius. For example 3px 7px', 'xstore-core'),
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Button paddings (top right bottom left)', 'xstore-core'),
				'param_name' => 'button_paddings',
				'group' => esc_html__('Button', 'xstore-core'),
				'hint' => esc_html__('Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core'),
				'value' => '',
				'edit_field_class' => 'vc_col-md-6 vc_column',
			),
			array(
				'type' => 'xstore_slider',
				'heading' => esc_html__('Button font size', 'xstore-core'),
				'param_name' => 'button_font_size',
				'min' => 0,
				'max' => 24,
				'step' => 1,
				'default' => '',
				'units' => 'px',
				'group' => esc_html__( 'Button', 'xstore-core' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
	      ), $title,
		array(
			array(
				'type' => 'textfield',
				'heading' => $strings['heading']['el_class'],
				'param_name' => 'title_class',
				'group' => 'Title font',
				'hint' => $strings['hint']['el_class'],
				'dependency' => array(
					'element' => 'use_custom_fonts_title',
					'value' => 'true',
				),
			),
		), $subtitle,
		array(
			array(
				'type' => 'textfield',
				'heading' => $strings['heading']['el_class'],
				'param_name' => 'subtitle_class',
				'group' => 'Subtitle font',
				'hint' => $strings['hint']['el_class'],
				'dependency' => array(
					'element' => 'use_custom_fonts_subtitle',
					'value' => 'true',
				),
			),
	        array(
	          	'type' => 'css_editor',
	          	'heading' => esc_html__( 'CSS box', 'xstore-core' ),
	          	'param_name' => 'css',
	          	'group' => esc_html__( 'Design for banner content', 'xstore-core' )
	        ),
		));

		$banner_params = array(
			'name' => 'Banner With Mask',
			'base' => 'banner',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Banner-with-mask.png',
			'description' => esc_html__('Display posts in grid', 'xstore-core'),
			'category' => $strings['category'],
			'params' => $params
		);

		vc_map($banner_params);
		
	}

	function heading_options($type, $text, $strings) {

		$heading = vc_map_integrate_shortcode( vc_custom_heading_element_params(), $type.'_', $text, array(
			'exclude' => array(
				'vc_link',
				'source',
				'text',
				'css',
				'el_class'
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
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['font_size'],
					'group' => $text,
					'param_name' => $type.'_responsive_font_size',
					'edit_field_class' => 'et_font-size-wrapper',
					'dependency' => array(
						'element' => 'use_custom_fonts_'.$type,
						'value' => 'true',
					),
					'hint' => $strings['hint']['rs_font_size']
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['line_height'],
					'group' => $text,
					'param_name' => $type.'_responsive_line_height',
					'edit_field_class' => 'et_line-height-wrapper',
					'empty_units' => true,
					'dependency' => array(
						'element' => 'use_custom_fonts_'.$type,
						'value' => 'true',
					), 
					'hint' => $strings['hint']['rs_line_height']
				),
			),
			$heading
		);

		return $heading;
	}

}
