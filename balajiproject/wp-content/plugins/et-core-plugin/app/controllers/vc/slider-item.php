<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Slider Item shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Slider_Item extends VC {

	function hooks(){
		add_filter('vc_param_animation_style_list', array($this, 'animations'), 1, 20);
		$this->register_et_slider_item();
		// remove_filter('vc_param_animation_style_list', array($this, 'animations'), 1, 20);
	}

	function register_et_slider_item() {
		require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-custom-heading-element.php' );

		$strings = $this->etheme_vc_shortcodes_strings();
		$title = $this->heading_options('title', esc_html__( 'Title font', 'xstore-core' ), 
			// before main title settings
			array(
				// old
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['font_size'],
					'param_name' => 'size',
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'hint' => esc_html__('Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core'),
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' ),
					'edit_field_class' => 'vc_col-md-12 et_old-font_size-wrapper et_old-title-font_size-wrapper',
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['font_size'],
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'param_name' => 'title_responsive_font_size',
					'edit_field_class' => 'vc_col-md-12 et_font-size-wrapper',
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' ),
					'hint' => $strings['hint']['rs_font_size']
				),
				// old
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['line_height'],
					'param_name' => 'line_height',
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'hint' => esc_html__('Write line height for element with dimentions or without. Example 14px, 15em, 2', 'xstore-core'),
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' ),
					'edit_field_class' => 'vc_col-md-12 et_old-line_height-wrapper et_old-title-line_height-wrapper',
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['line_height'],
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'param_name' => 'title_responsive_line_height',
					'edit_field_class' => 'vc_col-md-12 et_line-height-wrapper',
					'empty_units' => true,
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' ),
					'hint' => $strings['hint']['rs_line_height']
				),
				// old
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['letter_spacing'],
					'param_name' => 'spacing',
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'hint' => esc_html__('Write letter spacing for element with dimentions. Example 2px, 0.2em', 'xstore-core'),
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' ),
					'edit_field_class' => 'vc_col-md-12 et_old-letter_spacing-wrapper et_old-title-letter_spacing-wrapper',
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['letter_spacing'],
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'param_name' => 'title_responsive_letter_spacing',
					'edit_field_class' => 'vc_col-md-12 et_letter-spacing-wrapper',
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' ),
					'hint' => $strings['hint']['rs_letter_spacing']
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Color', 'xstore-core'),
					'param_name' => 'color',
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' )
				),
			),
			// after main title settings
			array(
				array(
					'type' => 'textfield',
					'heading' => __('Animation duration', 'xstore-core'),
					'param_name' => 'title_animation_duration',
					'hint' => esc_html__('Default 500ms. Write number in ms','xstore-core'),
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Animation delay', 'xstore-core'),
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'param_name' => 'title_animation_delay',
					'hint' => esc_html__('Write number in ms','xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' )
				),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'title_class',
					'group' => esc_html__( 'Title font', 'xstore-core' ),
					'hint' => $strings['hint']['el_class'],
					'dependency' => array( 'element' => 'use_custom_fonts_title', 'value' =>'true' )
				),
			)
		);

		$subtitle = $this->heading_options('subtitle', esc_html__( 'Subtitle font', 'xstore-core' ), 
			// before main title settings
			array(
				// old
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['font_size'],
					'param_name' => 'subtitle_size',
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'hint' => esc_html__('Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core'),
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' ),
					'edit_field_class' => 'vc_col-md-12 et_old-font_size-wrapper et_old-subtitle-font_size-wrapper',
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['font_size'],
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'param_name' => 'subtitle_responsive_font_size',
					'edit_field_class' => 'vc_col-md-12 et_font-size-wrapper',
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' ),
					'hint' => $strings['hint']['rs_font_size']
				),
				// old
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['line_height'],
					'param_name' => 'subtitle_line_height',
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'hint' => esc_html__('Write line height for element with dimentions or without. Example 14px, 15em, 2', 'xstore-core'),
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' ),
					'edit_field_class' => 'vc_col-md-12 et_old-line_height-wrapper et_old-subtitle-line_height-wrapper',
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['line_height'],
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'param_name' => 'subtitle_responsive_line_height',
					'edit_field_class' => 'vc_col-md-12 et_line-height-wrapper',
					'empty_units' => true,
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' ),
					'hint' => $strings['hint']['rs_line_height']
				),
				// old
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['letter_spacing'],
					'param_name' => 'subtitle_spacing',
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'hint' => esc_html__('Write letter spacing for element with dimentions. Example 2px, 0.2em', 'xstore-core'),
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' ),
					'edit_field_class' => 'vc_col-md-12 et_old-letter_spacing-wrapper et_old-subtitle-letter_spacing-wrapper'
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['letter_spacing'],
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'param_name' => 'subtitle_responsive_letter_spacing',
					'edit_field_class' => 'vc_col-md-12 et_letter-spacing-wrapper',
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' ),
					'hint' => $strings['hint']['rs_letter_spacing']
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'xstore-core'),
					'param_name' => 'subtitle_color',
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' )
				),
			),
			// after main title settings
			array(
				array(
					'type' => 'textfield',
					'heading' => __('Animation duration', 'xstore-core'),
					'param_name' => 'subtitle_animation_duration',
					'hint' => esc_html__('Default 500ms. Write number in ms','xstore-core'),
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Animation delay', 'xstore-core'),
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'param_name' => 'subtitle_animation_delay',
					'hint' => esc_html__('Write number in ms','xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' )
				),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'subtitle_class',
					'hint' => $strings['hint']['el_class'],
					'group' => esc_html__( 'Subtitle font', 'xstore-core' ),
					'dependency' => array( 'element' => 'use_custom_fonts_subtitle', 'value' =>'true' )
				),
			)
		);

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array_merge(
			array(
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Title', 'xstore-core' ),
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
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Subtitle', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textfield',
					'heading' => 'Subtitle',
					'param_name' => 'subtitle',
					'edit_field_class' => 'vc_col-sm-9 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => $strings['heading']['use_custom_font'],
					'param_name' => 'use_custom_fonts_subtitle',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show subtitle above title ?', 'xstore-core' ),
					'param_name' => 'subtitle_above',
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Description', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'param_name' => 'content',
					'value' => 'Some promo words'
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Description styles', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'animation_style',
					'heading' => esc_html__('Type', 'xstore-core'),
					'value' => '',
					'param_name' => 'description_animation',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Animation duration', 'xstore-core'),
					'param_name' => 'description_animation_duration',
					'hint' => esc_html__('Default 500. Write number in ms','xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array('element' => 'description_animation', 'value_not_equal_to' => 'none')
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Animation delay', 'xstore-core'),
					'param_name' => 'description_animation_delay',
					'hint' => esc_html__('Write number in ms','xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array('element' => 'description_animation', 'value_not_equal_to' => 'none')
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Content styles', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Content width', 'xstore-core'),
					'param_name' => 'content_width',
					'value' => array(
						esc_html__( 'Unset', 'xstore-core' ) => '',
						esc_html__( '100%', 'xstore-core' ) => '100',
						esc_html__( '90%', 'xstore-core' ) => '90',
						esc_html__( '80%', 'xstore-core' ) => '80',
						esc_html__( '70%', 'xstore-core' ) => '70',
						esc_html__( '60%', 'xstore-core' ) => '60',
						esc_html__( '50%', 'xstore-core' ) => '50',
						esc_html__( '40%', 'xstore-core' ) => '40',
						esc_html__( '30%', 'xstore-core' ) => '30',
						esc_html__( '20%', 'xstore-core' ) => '20',
						esc_html__( '10%', 'xstore-core' ) => '10',
					)
				),
				array(
					'type' => 'xstore_button_set',
					'heading' =>$strings['heading']['align'],
					'param_name' => 'align',
					'value' => array(
						esc_html__( 'Left', 'xstore-core' ) => 'start',
						esc_html__( 'Right', 'xstore-core' ) => 'end',
						esc_html__( 'Center', 'xstore-core' ) => 'center',
						esc_html__( 'Stretch', 'xstore-core' ) => 'between',
						esc_html__( 'Stretch (no paddings)', 'xstore-core' ) => 'around',
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['valign'],
					'param_name' => 'v_align',
					'value' => array(
						esc_html__( 'Top', 'xstore-core' ) => 'start',
						esc_html__( 'Bottom', 'xstore-core' ) => 'end',
						esc_html__( 'Middle', 'xstore-core' ) => 'center',
						esc_html__( 'Full height', 'xstore-core' ) => 'stretch',
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'Text align', 'xstore-core' ),
					'param_name' => 'text_align',
					'value' => array(
	                    '<span class="dashicons dashicons-editor-alignleft"></span>' => 'left',
	                    '<span class="dashicons dashicons-editor-aligncenter"></span>' => 'center',
	                    '<span class="dashicons dashicons-editor-alignright"></span>' => 'right',
	                    '<span class="dashicons dashicons-editor-justify"></span>' => 'justify'
					),
					'tooltip_values' => $strings['tooltip']['align']
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Advanced', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'el_class',
					'hint' => $strings['hint']['el_class']
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Background', 'xstore-core' ),
		            'group' => esc_html__('Background', 'xstore-core'),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__('Image', 'xstore-core'),
					'param_name' => 'bg_img',
					'group' => esc_html__('Background', 'xstore-core'),
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Image size', 'xstore-core' ),
					'param_name' => 'bg_size',
					'value' => array(
						esc_html__('Cover', 'xstore-core') => 'cover',
						esc_html__('Contain', 'xstore-core') => 'contain',
						esc_html__('Auto', 'xstore-core') => 'auto',
					),
					'group' => esc_html__('Background', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Background position', 'xstore-core'),
					'param_name' => 'background_position',
					'group' => esc_html__('Background', 'xstore-core'),
					'value' => array(
						esc_html__('Unset', 'xstore-core') => '',
						esc_html__('Left top', 'xstore-core') => 'left_top',
						esc_html__('Left center', 'xstore-core') => 'left',
						esc_html__('Left bottom', 'xstore-core') => 'left_bottom',
						esc_html__('Right top', 'xstore-core') => 'right_top',
						esc_html__('Right center', 'xstore-core') => 'right',
						esc_html__('Right bottom', 'xstore-core') => 'right_bottom',
						esc_html__('Center top', 'xstore-core') => 'center_top',
						esc_html__('Center center', 'xstore-core') => 'center',
						esc_html__('Center bottom', 'xstore-core') => 'center_bottom',
						esc_html__('(x% y%)', 'xstore-core') => 'custom',
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Axis X', 'xstore-core'),
					'param_name' => 'bg_pos_x',
					'group' => esc_html__('Background', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'hint' => esc_html__('Use this field to add background position by X axis. For example 50', 'xstore-core'),
					'dependency' => array('element' => 'background_position', 'value' => 'custom')
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Axis Y', 'xstore-core'),
					'param_name' => 'bg_pos_y',
					'group' => esc_html__('Background', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'hint' => esc_html__('Use this field to add background position by Y axis. For example 50', 'xstore-core'),
					'dependency' => array('element' => 'background_position', 'value' => 'custom')
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Background repeat', 'xstore-core'),
					'param_name' => 'background_repeat',
					'group' => esc_html__('Background', 'xstore-core'),
					'value' => array(
						esc_html__('Unset', 'xstore-core') => '',
						esc_html__('No repeat', 'xstore-core') => 'no-repeat',
						esc_html__('Repeat', 'xstore-core') => 'repeat',
						esc_html__('Repeat x', 'xstore-core') => 'repeat-x',
						esc_html__('Repeat y', 'xstore-core') => 'repeat-y',
						esc_html__('Round', 'xstore-core') => 'round',
						esc_html__('Space', 'xstore-core') => 'space',
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Background Color', 'xstore-core'),
					'param_name' => 'bg_color',
					'group' => esc_html__('Background', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Background Overlay', 'xstore-core'),
					'param_name' => 'bg_overlay',
					'group' => esc_html__('Background', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
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
					'edit_field_class' => 'vc_col-md-8 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__('Link for all slide', 'xstore-core'),
					'param_name' => 'link',
					'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-4 vc_column',
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Button colors', 'xstore-core' ),
		            'group' => esc_html__('Button', 'xstore-core'),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Text color', 'xstore-core'),
					'param_name' => 'button_color',
					'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Text color (hover)', 'xstore-core'),
					'param_name' => 'button_hover_color',
					'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Background color', 'xstore-core'),
					'param_name' => 'button_bg',
					'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Background color (hover)', 'xstore-core'),
					'param_name' => 'button_hover_bg',
					'group' => esc_html__('Button', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Font size', 'xstore-core' ),
		            'group' => esc_html__('Button', 'xstore-core'),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textfield',
					'param_name' => 'button_font_size',
					'group' => esc_html__('Button', 'xstore-core'),
					'hint' => esc_html__('Use this field to add element font size. For example 20px', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-12 et_old-font_size-wrapper et_old-button-font_size-wrapper',
				),
				array( 
					'type' => 'xstore_responsive_size',
					'heading' => $strings['heading']['font_size'],
					'group' => esc_html__('Button', 'xstore-core'),
					'param_name' => 'button_responsive_font_size',
					'edit_field_class' => 'vc_col-md-12 et_font-size-wrapper',
					'default' => '12px',
					'hint' => $strings['hint']['rs_font_size']
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Border settings', 'xstore-core' ),
		            'group' => esc_html__('Button', 'xstore-core'),
		            'param_name' => 'divider'.$counter++
		        ),
        		array (
        			'type' => 'textfield',
        			'heading' => esc_html__('Border width', 'xstore-core'),
        			'param_name' => 'button_border_width',
        			'default' => '1px',
        			'group' => esc_html__('Button', 'xstore-core'),
        			'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
        		array (
        			'type' => 'dropdown',
        			'heading' => esc_html__('Border style', 'xstore-core'),
        			'param_name' => 'button_border_style',
        			'default' => 'none',
        			'value' => array(
        				__('Unset', 'xstore-core') => '',
        				__('None', 'xstore-core') => 'none',
        				__('Solid', 'xstore-core') => 'solid',
        				__('Dashed', 'xstore-core') => 'dashed',
        				__('Dotted', 'xstore-core') => 'dotted',
        				__('Double', 'xstore-core') => 'double',
        				__('Groove', 'xstore-core') => 'groove',
        				__('Ridge', 'xstore-core') => 'ridge',
        				__('Inset', 'xstore-core') => 'inset',
        				__('Outset', 'xstore-core') => 'outset',
        			),
        			'group' => esc_html__('Button', 'xstore-core'),
        			'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
        		array (
        			'type' => 'colorpicker',
        			'heading' => esc_html__('Border color', 'xstore-core'),
        			'param_name' => 'button_border_color',
        			'group' => esc_html__('Button', 'xstore-core'),
        			'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
        		array (
        			'type' => 'colorpicker',
        			'heading' => esc_html__('Border color (hover)', 'xstore-core'),
        			'param_name' => 'button_border_color_hover',
        			'group' => esc_html__('Button', 'xstore-core'),
        			'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
				array(
					'type' => 'textfield',
					'param_name' => 'button_border_radius',
					'heading' => esc_html__('Border radius', 'xstore-core'),
					'group' => esc_html__('Button', 'xstore-core'),
					'hint' => esc_html__('Use this field to add element border radius. For example 3px 7px', 'xstore-core'),
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Spacing', 'xstore-core' ),
		            'group' => esc_html__('Button', 'xstore-core'),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Paddings (top right bottom left)', 'xstore-core'),
					'param_name' => 'button_paddings',
					'group' => esc_html__('Button', 'xstore-core'),
					'hint' => esc_html__('Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Margins (top right bottom left)', 'xstore-core'),
					'param_name' => 'button_margins',
					'group' => esc_html__('Button', 'xstore-core'),
					'hint' => esc_html__('Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Button animation', 'xstore-core' ),
		            'group' => esc_html__('Button', 'xstore-core'),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'animation_style',
					'heading' => esc_html__('Type' , 'xstore-core'),
					'group' => esc_html__('Button', 'xstore-core'),
					'param_name' => 'button_animation',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__('Animation duration', 'xstore-core'),
					'group' => esc_html__('Button', 'xstore-core'),
					'param_name' => 'button_animation_duration',
					'hint' => esc_html__('Default 500. Write number in ms','xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array('element' => 'button_animation', 'value_not_equal_to' => 'none')
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Animation delay', 'xstore-core'),
					'group' => esc_html__('Button', 'xstore-core'),
					'param_name' => 'button_animation_delay',
					'hint' => esc_html__('Write number in ms','xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array('element' => 'button_animation', 'value_not_equal_to' => 'none')
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design for slide content', 'xstore-core' )
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Background position', 'xstore-core'),
					'param_name' => 'content_bg_position',
					'group' => esc_html__( 'Design for slide content', 'xstore-core' ),
					'value' => array(
						__('Inherit from slide settings', 'xstore-core') => '',
						__('Left top', 'xstore-core') => 'left_top',
						__('Left center', 'xstore-core') => 'left',
						__('Left bottom', 'xstore-core') => 'left_bottom',
						__('Right top', 'xstore-core') => 'right_top',
						__('Right center', 'xstore-core') => 'right',
						__('Right bottom', 'xstore-core') => 'right_bottom',
						__('Center top', 'xstore-core') => 'center_top',
						__('Center center', 'xstore-core') => 'center',
						__('Center bottom', 'xstore-core') => 'center_bottom',
					)
				),
			),
			$title,
			$subtitle
		);

		$slider_item_params = array(
			'name' => 'Slider Item',
			'base' => 'etheme_slider_item',
			'category' => $strings['category'],
			'content_element' => true,
			'icon' => ETHEME_CODE_IMAGES . 'vc/Slider.png',
			'description' => esc_html__('Display slider item with image, text and button', 'xstore-core'),
			'as_child' => array('only' => 'etheme_slider'),            
			'is_container' => false,
			'params' => $params
		);

		vc_map($slider_item_params);
	}

	function heading_options($type, $text, $extra_before = array(), $extra_after = array()) {

		$heading = vc_map_integrate_shortcode( vc_custom_heading_element_params(), $type.'_', $text, array(
			'exclude' => array(
				'vc_link',
				'source',
				'text',
				'css',
				'el_class',
				'el_id',
			),
		), array(
			'element' => 'use_custom_fonts_'.$type,
			'value' =>'true',
		) );

		// This is needed to remove custom heading _tag and _align options.
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
						$sub_key = array_search( 'font_size', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['font_size'] ) ) {
							unset( $heading[ $key ]['settings']['fields']['font_size'] );
						}
						$sub_key = array_search( 'line_height', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['line_height'] ) ) {
							unset( $heading[ $key ]['settings']['fields']['line_height'] );
						}
						$sub_key = array_search( 'color', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['color'] ) ) {
							unset( $heading[ $key ]['settings']['fields']['color'] );
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
			$extra_before,
			$heading,
			$extra_after
		);

		return $heading;
	}

	function animations($styles) {
		$styles = array(
			array(
				'label' => esc_html__('Animation types', 'xstore-core'),
				'values' => array(
					esc_html__('None', 'xstore-core') => 'none',
					esc_html__('FadeIn', 'xstore-core') => 'fadeIn',
					esc_html__('FadeInDown', 'xstore-core') => 'fadeInDown',
					esc_html__('FadeInUp', 'xstore-core') => 'fadeInUp',
					esc_html__('FadeInRight', 'xstore-core') => 'fadeInRight',
					esc_html__('FadeInLeft', 'xstore-core') => 'fadeInLeft',
					esc_html__('Zoom in', 'xstore-core' ) => 'zoomIn',
					esc_html__('SlideInDown', 'xstore-core') => 'slideInDown',
					esc_html__('SlideInUp', 'xstore-core') => 'slideInUp',
					esc_html__('SlideInRight', 'xstore-core') => 'slideInRight',
					esc_html__('SlideInLeft', 'xstore-core') => 'slideInLeft',
					esc_html__('Top to bottom', 'xstore-core') => 'top-to-bottom',
					esc_html__('Bottom to top', 'xstore-core') => 'bottom-to-top',
					esc_html__('Left to right', 'xstore-core') => 'left-to-right',
					esc_html__('Right to left', 'xstore-core') => 'right-to-left',
				),
			),
		);
		return $styles;
	}

}
