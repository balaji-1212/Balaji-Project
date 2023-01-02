<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * The Look shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Title extends VC {

	public function hooks() {
		$this->register_vc_title();
	}

	public function register_vc_title() {

		require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-custom-heading-element.php' );

		$strings = $this->etheme_vc_shortcodes_strings();

		$title = $this->heading_options('title', esc_html__( 'Title font', 'xstore-core' ), $strings);

		$subtitle = $this->heading_options('subtitle', esc_html__( 'Subtitle font', 'xstore-core' ), $strings);

		$counter = 0;
		$params = array(
			'name' => 'Title With Text',
			'base' => 'title',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Title-with-text.png',
			'description' => esc_html__('Heading with Additional text', 'xstore-core'),
			'category' => $strings['category'],
			'params' =>  array_merge( array(
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
					'edit_field_class' => 'vc_col-sm-3 vc_column',
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
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Divider', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Type', 'xstore-core'),
					'param_name' => 'divider',
					'value' => array( 
						__('Unset', 'xstore-core') => '', 
						__('Line through', 'xstore-core') => 'line-through', 
						__('Line through short', 'xstore-core') => 'line-through-short',
						__('Line under', 'xstore-core') => 'line-under'
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Color', 'xstore-core'),
					'param_name' => 'divider_color',
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array(
						'element' => 'divider',
						'value_not_equal_to' => '',
					),
				),
				array (
					'type' => 'textfield',
					'heading' => esc_html__('Width', 'xstore-core'),
					'value' => '1px',
					'param_name' => 'divider_width',
					'edit_field_class' => 'vc_col-md-6 vc_column',
					'dependency' => array(
						'element' => 'divider',
						'value_not_equal_to' => '',
					),
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
				)
			), $title, $subtitle)
		);

		vc_map($params);
	}

	function heading_options($type, $text, $strings) {

		$heading = vc_map_integrate_shortcode( vc_custom_heading_element_params(), $type.'_', $text, array(
			'exclude' => array(
				'vc_link',
				'source',
				'text',
				'css',
				'el_class',
			),
		), array(
			'element' => 'use_custom_fonts_'.$type,
			'value' =>'true',
		) );

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
