<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
	add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'etheme_vc_custom_css_class', 10, 3 );
	if ( ! function_exists( 'etheme_vc_custom_css_class' ) ) {
		function etheme_vc_custom_css_class( $classes, $base, $atts = array() ) {
			if ( ! empty( $atts['fixed_background'] ) ) {
				$classes .= ' et-attachment-fixed';
			}
			if ( ! empty( $atts['fixed_background'] ) ) {
				$classes .= ' et-parallax et-parallax-' . $atts['fixed_background'];
			} elseif ( ! empty( $atts['background_position'] ) ) {
				$classes .= ' et-parallax et-parallax-' . $atts['background_position'];
			}
			if ( ! empty( $atts['off_center'] ) ) {
				$classes .= ' off-center-' . $atts['off_center'];
			}
			if ( ! empty( $atts['columns_reverse'] ) ) {
				$classes .= ' columns-mobile-reverse';
			}
			
			if ( ! empty( $atts['md_bg_off'] ) ) {
				$classes .= ' et-md-no-bg';
			}
			
			if ( ! empty( $atts['sm_bg_off'] ) ) {
				$classes .= ' et-sm-no-bg';
			}
			
			if ( ! empty( $atts['xs_bg_off'] ) ) {
				$classes .= ' et-xs-no-bg';
			}
			
			if ( ! empty( $atts['align'] ) ) {
				$classes .= ' align-' . $atts['align'];
			}
			
			if ( ! empty( $atts['mob_align'] ) ) {
				$classes .= ' mob-align-' . $atts['mob_align'];
			}
			
			if ( ! empty( $atts['_et_uniqid'] ) ) {
				$classes .= ' ' . $atts['_et_uniqid'];
			} elseif ( ! empty( $atts['et_uniqid'] ) ) {
				$classes .= ' ' . $atts['et_uniqid'];
			}
			
			return $classes;
		}
	}
}

add_action( 'wp_head', function () {
	
	if ( is_admin() ) {
		return;
	}
	
	global $post;
	
	if ( ! is_object( $post ) ) {
		return;
	}
	
	$css = array(
		'global' => array(),
		'md'     => array(),
		'sm'     => array(),
		'xs'     => array(),
	);
	
	$css2 = et_custom_shortcodes_css( $post->post_content );
	
	if ( is_array( $css2 ) ) {
		$css = array(
			'global' => array_unique(array_merge( $css['global'], $css2['global'] )),
			'md'     => array_unique(array_merge( $css['md'], $css2['md'] )),
			'sm'     => array_unique(array_merge( $css['sm'], $css2['sm'] )),
			'xs'     => array_unique(array_merge( $css['xs'], $css2['xs'] )),
		);
	}
	
	$css['xs'][] = 'div.et-xs-no-bg { background-image: none !important; }';
	
	echo '<style type="text/css" data-type="et_vc_shortcodes-custom-css">';
	
	if ( count( $css['global'] ) ) {
		echo implode( '', $css['global'] );
	}
	
	if ( count( $css['md'] ) ) {
		echo '@media only screen and (max-width: 1199px) {' . implode( '', $css['md'] ) . '}';
	}
	
	echo '@media only screen and (max-width: 1199px) and (min-width: 769px) { div.et-md-no-bg { background-image: none !important; } }';
	
	if ( count( $css['sm'] ) ) {
		echo '@media only screen and (max-width: 768px) {' . implode( '', $css['sm'] ) . '}';
	}
	
	echo '@media only screen and (max-width: 768px) and (min-width: 480px) { div.et-sm-no-bg { background-image: none !important; } }';
	
	if ( count( $css['xs'] ) ) {
		echo '@media only screen and (max-width: 480px) {' . implode( '', $css['xs'] ) . '}';
	}
	
	echo '</style>';
	
}, 1001 );

function et_custom_shortcodes_css( $content ) {
	
	$css = array(
		'global' => array(),
		'md'     => array(),
		'sm'     => array(),
		'xs'     => array()
	);
	
	$et_wpbakery_css_module = etheme_get_option( 'et_wpbakery_css_module', 0 );
	
	if ( ! class_exists( 'WPBMap' ) || ! method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
		return;
	}
	
	WPBMap::addAllMappedShortcodes();
	preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
	foreach ( $shortcodes[2] as $index => $tag ) {
		
		$shortcode  = WPBMap::getShortCode( $tag );
		$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
		$prefix     = '';
		
		if ( in_array($tag, array('vc_column', 'vc_column_inner'))) {
			$prefix = ' > .vc_column-inner';
		}
		
		if ( isset( $shortcode['params'] ) && ! empty( $shortcode['params'] ) ) {
			foreach ( $shortcode['params'] as $param ) {
				if ( isset( $param['type'] ) && isset( $attr_array[ $param['param_name'] ] ) ) {
					
					$translate = $local_css = '';
					
					$box_shadow = array(
						'box_shadow_inset'  => '',
						'box_shadow_h'      => '0',
						'box_shadow_v'      => '0',
						'box_shadow_blur'   => '0',
						'box_shadow_spread' => '0',
						'box_shadow_color'  => '',
					);
					
					$add_box_shadow = false;
					
					if ( isset( $attr_array['translate_x'] ) ) {
						$translate .= ' translateX(' . $attr_array['translate_x'] . ')';
					}
					
					if ( isset( $attr_array['translate_y'] ) ) {
						$translate .= ' translateY(' . $attr_array['translate_y'] . ')';
					}
					
					if ( $translate != '' ) {
						$local_css .= 'transform: ' . $translate . ';';
					}
					
					if ( isset( $attr_array['box_shadow_inset'] ) ) {
						$box_shadow['box_shadow_inset'] = 'inset';
					}
					
					if ( isset( $attr_array['box_shadow_h'] ) ) {
						$add_box_shadow             = true;
						$box_shadow['box_shadow_h'] = $attr_array['box_shadow_h'];
					}
					
					if ( isset( $attr_array['box_shadow_v'] ) ) {
						$add_box_shadow             = true;
						$box_shadow['box_shadow_v'] = $attr_array['box_shadow_v'];
					}
					
					if ( isset( $attr_array['box_shadow_blur'] ) ) {
						$add_box_shadow                = true;
						$box_shadow['box_shadow_blur'] = $attr_array['box_shadow_blur'];
					}
					
					if ( isset( $attr_array['box_shadow_spread'] ) ) {
						$add_box_shadow                  = true;
						$box_shadow['box_shadow_spread'] = $attr_array['box_shadow_spread'];
					}
					
					if ( isset( $attr_array['box_shadow_color'] ) ) {
						$add_box_shadow                 = true;
						$box_shadow['box_shadow_color'] = $attr_array['box_shadow_color'];
					}
					
					if ( $add_box_shadow ) {
						$local_css .= 'box-shadow: ' . implode( ' ', $box_shadow ) . ';';
					}
					
					$isset_uniq = isset( $attr_array['_et_uniqid'] ) || isset( $attr_array['et_uniqid'] );
					
					if ( isset( $attr_array['_et_uniqid'] ) ) {
						$selector = $attr_array['_et_uniqid'];
					} elseif ( isset( $attr_array['et_uniqid'] ) ) {
						$selector = $attr_array['et_uniqid'];
					}
					
					if ( $isset_uniq ) {
						if ( $local_css != '' ) {
							$css['global'][] = '.' . $selector . $prefix . '{ ' . $local_css . ' }';
						}
						if ( isset( $attr_array['z_index'] ) ) {
							$css['global'][] = '.' . $selector . ' { z-index: ' . $attr_array['z_index'] . '; }';
						}
					}
					
					if ( 'css_editor' === $param['type'] && $et_wpbakery_css_module ) {
						
						if ( $isset_uniq ) {
							
							if ( isset( $attr_array['css_md'] ) ) {
								$class       = vc_shortcode_custom_css_class( $attr_array['css_md'] );
								$css['md'][] = str_replace( $class, $selector . $prefix, $attr_array['css_md'] );
							}
							
							if ( isset( $attr_array['css_sm'] ) ) {
								$class       = vc_shortcode_custom_css_class( $attr_array['css_sm'] );
								$css['sm'][] = str_replace( $class, $selector . $prefix, $attr_array['css_sm'] );
							}
							
							if ( isset( $attr_array['css_xs'] ) ) {
								$class       = vc_shortcode_custom_css_class( $attr_array['css_xs'] );
								$css['xs'][] = str_replace( $class, $selector . $prefix, $attr_array['css_xs'] );
							}
							
						}
						
					}
					
				}
			}
		}
	}
	foreach ( $shortcodes[5] as $shortcode_content ) {
		$css2 = et_custom_shortcodes_css( $shortcode_content );
		if ( is_array( $css2 ) ) {
			$css = array(
				'global' => array_merge( $css['global'], $css2['global'] ),
				'md'     => array_merge( $css['md'], $css2['md'] ),
				'sm'     => array_merge( $css['sm'], $css2['sm'] ),
				'xs'     => array_merge( $css['xs'], $css2['xs'] ),
			);
		}
	}
	
	return $css;
}

// **********************************************************************//
// ! Add new option to vc_column
// **********************************************************************//
add_action( 'init', 'etheme_columns_options' );
if ( ! function_exists( 'etheme_columns_options' ) ) {
	function etheme_columns_options() {
		if ( ! function_exists( 'vc_map' ) ) {
			return;
		}
		
		$css_devices = array(
			array(
				'type'             => 'xstore_title_divider',
				'title'            => esc_html__( 'Css box', 'xstore' ),
				'group'            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-12 css_box_tabs',
				'param_name'       => 'column_css_divider',
			),
			array(
				'type'             => 'css_editor',
				'heading'          => esc_html__( 'CSS box (Desktop)', 'xstore' ),
				'param_name'       => 'css',
				'group'            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column et_css-query et_css-query-global',
			),
			array(
				'type'             => 'css_editor',
				'heading'          => esc_html__( 'CSS box (Tablet landscape)', 'xstore' ),
				'param_name'       => 'css_md',
				'group'            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-tablet',
			),
			array(
				"type"             => "checkbox",
				"heading"          => esc_html__( "Disable background on tablet landscape", 'xstore' ),
				"param_name"       => "md_bg_off",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'value'            => array( esc_html__( 'Yes, please', 'xstore' ) => 'yes' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-tablet',
			),
			array(
				'type'             => 'css_editor',
				'heading'          => esc_html__( 'CSS box (Tablet portrait)', 'xstore' ),
				'param_name'       => 'css_sm',
				'group'            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-ipad',
			),
			array(
				"type"             => "checkbox",
				"heading"          => esc_html__( "Disable background on tablet portrait", 'xstore' ),
				"param_name"       => "sm_bg_off",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'value'            => array( esc_html__( 'Yes, please', 'xstore' ) => 'yes' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-ipad',
			),
			array(
				'type'             => 'css_editor',
				'heading'          => esc_html__( 'CSS box (Mobile)', 'xstore' ),
				'param_name'       => 'css_xs',
				'group'            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-mobile',
			),
			array(
				"type"             => "checkbox",
				"heading"          => esc_html__( "Disable background on mobile", 'xstore' ),
				"param_name"       => "xs_bg_off",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'value'            => array( esc_html__( 'Yes, please', 'xstore' ) => 'yes' ),
				'edit_field_class' => 'vc_col-xs-12 vc_column vc_dependent-hidden et_css-query et_css-query-mobile',
			),
		);
		
		if ( etheme_get_option( 'et_wpbakery_css_module', 0 ) ) {
			
			vc_remove_param( 'vc_column', 'css' );
			
			vc_add_params( 'vc_column', $css_devices );
			
			vc_remove_param( 'vc_column_inner', 'css' );
			
			vc_add_params( 'vc_column_inner', $css_devices );
			
			vc_remove_param( 'vc_row', 'css' );
			
			vc_add_params( 'vc_row', $css_devices );
			
			vc_remove_param( 'vc_row_inner', 'css' );
			
			vc_add_params( 'vc_row_inner', $css_devices );
			
		}
		
		vc_add_params( 'vc_row_inner', array(
			array(
				'type'       => 'hidden',
				'param_name' => 'et_uniqid',
				"group"      => esc_html__( 'Design Options', 'xstore' ),
				'value'      => 'et_custom_uniqid_' . uniqid(), // old
			),
			array(
				'type'       => 'xstore_uniqid',
				'param_name' => '_et_uniqid', // new
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			)
		) );
		
		vc_add_params( 'vc_column', array(
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'XStore options', 'xstore' ),
				'group'      => esc_html__( 'Design Options', 'xstore' ),
				'param_name' => 'column_xstore_options_divider',
			),
			array(
				"type"             => "dropdown",
				"heading"          => esc_html__( "Background position", 'xstore' ),
				"param_name"       => "background_position",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"value"            => array(
					''                              => '',
					__( "Left top", 'xstore' )      => 'left_top',
					__( "Left center", 'xstore' )   => 'left',
					__( "Left bottom", 'xstore' )   => 'left_bottom',
					__( "Right top", 'xstore' )     => 'right_top',
					__( "Right center", 'xstore' )  => 'right',
					__( "Right bottom", 'xstore' )  => 'right_bottom',
					__( "Center top", 'xstore' )    => 'center_top',
					__( "Center center", 'xstore' ) => 'center',
					__( "Center bottom", 'xstore' ) => 'center_bottom',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "dropdown",
				"heading"          => esc_html__( "Fixed background position (paralax effect)", 'xstore' ),
				"param_name"       => "fixed_background",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"value"            => array(
					''                              => '',
					__( "Left top", 'xstore' )      => 'left_top',
					__( "Left center", 'xstore' )   => 'left',
					__( "Left bottom", 'xstore' )   => 'left_bottom',
					__( "Right top", 'xstore' )     => 'right_top',
					__( "Right center", 'xstore' )  => 'right',
					__( "Right bottom", 'xstore' )  => 'right_bottom',
					__( "Center top", 'xstore' )    => 'center_top',
					__( "Center center", 'xstore' ) => 'center',
					__( "Center bottom", 'xstore' ) => 'center_bottom',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"       => "xstore_button_set",
				"heading"    => esc_html__( "Off center", 'xstore' ),
				"group"      => esc_html__( 'Design Options', 'xstore' ),
				"param_name" => "off_center",
				"value"      => array(
					__( "Unset", 'xstore' ) => '',
					__( "Left", 'xstore' )  => 'left',
					__( "Right", 'xstore' ) => 'right',
				)
			),
			array(
				"type"             => "xstore_button_set",
				"heading"          => esc_html__( "Align", 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"param_name"       => "align",
				"value"            => array(
					__( "Unset", 'xstore' )  => '',
					__( "Start", 'xstore' )  => 'start',
					__( "Center", 'xstore' ) => 'center',
					__( "End", 'xstore' )    => 'end',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "xstore_button_set",
				"heading"          => esc_html__( "Mobile Align", 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"param_name"       => "mob_align",
				"value"            => array(
					__( "Inherit", 'xstore' ) => '',
					__( "Start", 'xstore' )   => 'start',
					__( "Center", 'xstore' )  => 'center',
					__( "End", 'xstore' )     => 'end',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Advanced options', 'xstore' ),
				'group'      => esc_html__( 'Design Options', 'xstore' ),
				'param_name' => 'transform_xstore_options_divider',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Translate X", 'xstore' ),
				"param_name"       => "translate_x",
				'description'      => esc_html__( 'Examples: 0px, 10%, -15px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Translate Y", 'xstore' ),
				"param_name"       => "translate_y",
				'description'      => esc_html__( 'Examples: 0px, 10%, -15px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"       => "textfield",
				"heading"    => esc_html__( "Z-index", 'xstore' ),
				"param_name" => "z_index",
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			),
			array(
				'type'       => 'hidden',
				'param_name' => 'et_uniqid',
				"group"      => esc_html__( 'Design Options', 'xstore' ),
				'value'      => 'et_custom_uniqid_' . uniqid(), // old
			),
			array(
				'type'       => 'xstore_uniqid',
				'param_name' => '_et_uniqid', // new
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			)
		) );
		
		vc_add_params( 'vc_column_inner', array(
			array(
				'type'       => 'hidden',
				'param_name' => 'et_uniqid',
				"group"      => esc_html__( 'Design Options', 'xstore' ),
				'value'      => 'et_custom_uniqid_' . uniqid(), // old
			),
			array(
				'type'       => 'xstore_uniqid',
				'param_name' => '_et_uniqid', // new
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			)
		) );
		
		vc_add_params( 'vc_row', array(
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'XStore options', 'xstore' ),
				'group'      => esc_html__( 'Design Options', 'xstore' ),
				'param_name' => 'row_xstore_options_divider',
			),
			array(
				"type"             => "dropdown",
				"heading"          => esc_html__( "Background position", 'xstore' ),
				"param_name"       => "background_position",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"value"            => array(
					''                              => '',
					__( "Left top", 'xstore' )      => 'left_top',
					__( "Left center", 'xstore' )   => 'left',
					__( "Left bottom", 'xstore' )   => 'left_bottom',
					__( "Right top", 'xstore' )     => 'right_top',
					__( "Right center", 'xstore' )  => 'right',
					__( "Right bottom", 'xstore' )  => 'right_bottom',
					__( "Center top", 'xstore' )    => 'center_top',
					__( "Center center", 'xstore' ) => 'center',
					__( "Center bottom", 'xstore' ) => 'center_bottom',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "dropdown",
				"heading"          => esc_html__( "Fixed background position", 'xstore' ),
				"param_name"       => "fixed_background",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"value"            => array(
					''                              => '',
					__( "Left top", 'xstore' )      => 'left_top',
					__( "Left center", 'xstore' )   => 'left',
					__( "Left bottom", 'xstore' )   => 'left_bottom',
					__( "Right top", 'xstore' )     => 'right_top',
					__( "Right center", 'xstore' )  => 'right',
					__( "Right bottom", 'xstore' )  => 'right_bottom',
					__( "Center top", 'xstore' )    => 'center_top',
					__( "Center center", 'xstore' ) => 'center',
					__( "Center bottom", 'xstore' ) => 'center_bottom',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Columns reverse on mobile", 'xstore' ),
				"param_name" => "columns_reverse",
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Advanced options', 'xstore' ),
				'group'      => esc_html__( 'Design Options', 'xstore' ),
				'param_name' => 'transform_xstore_options_divider',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Translate X", 'xstore' ),
				"param_name"       => "translate_x",
				'description'      => esc_html__( 'Examples: 0px, 10%, -15px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Translate Y", 'xstore' ),
				"param_name"       => "translate_y",
				'description'      => esc_html__( 'Examples: 0px, 10%, -15px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"       => "textfield",
				"heading"    => esc_html__( "Z-index", 'xstore' ),
				"param_name" => "z_index",
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			),
			array(
				'type'       => 'hidden',
				'param_name' => 'et_uniqid',
				"group"      => esc_html__( 'Design Options', 'xstore' ),
				'value'      => 'et_custom_uniqid_' . uniqid(), // old
			),
			array(
				'type'       => 'xstore_uniqid',
				'param_name' => '_et_uniqid', // new
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			)
		) );
		
		vc_add_params( 'vc_section', array(
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'XStore options', 'xstore' ),
				'group'      => esc_html__( 'Design Options', 'xstore' ),
				'param_name' => 'section_xstore_options_divider',
			),
			array(
				"type"             => "dropdown",
				"heading"          => esc_html__( "Background position", 'xstore' ),
				"param_name"       => "background_position",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"value"            => array(
					''                              => '',
					__( "Left top", 'xstore' )      => 'left_top',
					__( "Left center", 'xstore' )   => 'left',
					__( "Left bottom", 'xstore' )   => 'left_bottom',
					__( "Right top", 'xstore' )     => 'right_top',
					__( "Right center", 'xstore' )  => 'right',
					__( "Right bottom", 'xstore' )  => 'right_bottom',
					__( "Center top", 'xstore' )    => 'center_top',
					__( "Center center", 'xstore' ) => 'center',
					__( "Center bottom", 'xstore' ) => 'center_bottom',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "dropdown",
				"heading"          => esc_html__( "Fixed background position", 'xstore' ),
				"param_name"       => "fixed_background",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				"value"            => array(
					''                              => '',
					__( "Left top", 'xstore' )      => 'left_top',
					__( "Left center", 'xstore' )   => 'left',
					__( "Left bottom", 'xstore' )   => 'left_bottom',
					__( "Right top", 'xstore' )     => 'right_top',
					__( "Right center", 'xstore' )  => 'right',
					__( "Right bottom", 'xstore' )  => 'right_bottom',
					__( "Center top", 'xstore' )    => 'center_top',
					__( "Center center", 'xstore' ) => 'center',
					__( "Center bottom", 'xstore' ) => 'center_bottom',
				),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Columns reverse on mobile", 'xstore' ),
				"param_name" => "columns_reverse",
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			),
			array(
				'type'       => 'hidden',
				'param_name' => 'et_uniqid',
				"group"      => esc_html__( 'Design Options', 'xstore' ),
				'value'      => 'et_custom_uniqid_' . uniqid(), // old
			),
			array(
				'type'       => 'xstore_uniqid',
				'param_name' => '_et_uniqid', // new
				"group"      => esc_html__( 'Design Options', 'xstore' ),
			)
		) );
		
		$box_shadow = array(
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Box shadow', 'xstore' ),
				'group'      => esc_html__( 'Design Options', 'xstore' ),
				'param_name' => 'box_shadow_options_divider',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Horizontal offset", 'xstore' ),
				"param_name"       => "box_shadow_h",
				'description'      => esc_html__( 'Examples: 0px, 3px, 5px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Vertical offset", 'xstore' ),
				"param_name"       => "box_shadow_v",
				'description'      => esc_html__( 'Examples: 0px, 3px, 5px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Blur", 'xstore' ),
				"param_name"       => "box_shadow_blur",
				'description'      => esc_html__( 'Examples: 0px, 3px, 5px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Spread", 'xstore' ),
				"param_name"       => "box_shadow_spread",
				'description'      => esc_html__( 'Examples: 0px, 3px, 5px', 'xstore' ),
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "checkbox",
				"heading"          => esc_html__( "Box shadow inset", 'xstore' ),
				"param_name"       => "box_shadow_inset",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
			array(
				"type"             => "colorpicker",
				"heading"          => __( "Box shadow color", "xstore" ),
				"param_name"       => "box_shadow_color",
				"group"            => esc_html__( 'Design Options', 'xstore' ),
				'edit_field_class' => 'vc_col-xs-6',
			),
		);
		
		vc_add_params( 'vc_column', $box_shadow );
		
		vc_add_params( 'vc_column_inner', $box_shadow );
		
		vc_add_params( 'vc_row', $box_shadow );
		
		vc_add_params( 'vc_row_inner', $box_shadow );
		
		vc_add_params( 'vc_section', $box_shadow );
		
	}
}

class ET_product_templates {
	
	protected $template = '';
	protected $html_template = false;
	protected $post = false;
	protected $grid_atts = array();
	protected $is_end = false;
	protected static $templates_added = false;
	protected $shortcodes = false;
	protected $found_variables = false;
	protected static $predefined_templates = false;
	protected $template_id = false;
	protected static $custom_fields_meta_data = false;
	
	/**
	 * Get shortcodes to build vc grid item templates.
	 *
	 * @return bool|mixed|void
	 */
	public function shortcodes() {
		if ( false === $this->shortcodes ) {
			$this->shortcodes = include vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/shortcodes.php' );
			$this->shortcodes = apply_filters( 'vc_grid_item_shortcodes', $this->shortcodes );
		}
		add_filter( 'vc_shortcode_set_template_vc_icon', array( $this, 'addVcIconShortcodesTemplates' ) );
		add_filter( 'vc_shortcode_set_template_vc_button2', array( $this, 'addVcButton2ShortcodesTemplates' ) );
		add_filter( 'vc_shortcode_set_template_vc_single_image', array(
			$this,
			'addVcSingleImageShortcodesTemplates',
		) );
		add_filter( 'vc_shortcode_set_template_vc_custom_heading', array(
			$this,
			'addVcCustomHeadingShortcodesTemplates',
		) );
		add_filter( 'vc_shortcode_set_template_vc_btn', array( $this, 'addVcBtnShortcodesTemplates' ) );
		
		add_filter( 'vc_gitem_template_attribute_post_image_background_image_css', array(
			$this,
			'vc_gitem_template_attribute_post_image_background_image_css'
		), 10, 2 );
		
		return $this->shortcodes;
	}
	
	/**
	 * Get post image url
	 *
	 * @param $value
	 * @param $data
	 *
	 * @return string
	 */
	public function vc_gitem_template_attribute_post_image_background_image_css( $value, $data ) {
		$output = '';
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $output;
		}
		
		global $post;
		/**
		 * @var null|Wp_Post $post ;
		 */
		extract( array_merge( array(
			'data' => '',
		), $data ) );
		$size = 'woocommerce_thumbnail'; // default size
		
		if ( ! empty( $data ) ) {
			
			// backward compatibility to woocommerce <= 3.8.0 because in newest versions few sizes were removed
			$data =
                str_replace(
                array( 'shop_thumbnail', 'shop_catalog', 'shop_single' ),
                array( 'woocommerce_gallery_thumbnail', 'woocommerce_thumbnail', 'woocommerce_single' ),
                $data
            );
			
			$size = $data;
		}
		
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		$src               = wp_get_attachment_image_src( $post_thumbnail_id, $size );
		
		if ( ! empty( $src ) ) {
			$output = 'background-image: url(\'' . ( is_array( $src ) ? $src[0] : $src ) . '\') !important;';
		} elseif ( class_exists( 'WooCommerce' ) ) {
			$output = 'background-image: url(\'' . wc_placeholder_img_src() . '\') !important;';
		}
		
		return apply_filters( 'vc_gitem_template_attribute_post_image_background_image_css_value', $output );
	}
	
	/**
	 * Used by filter vc_shortcode_set_template_vc_icon to set custom template for vc_icon shortcode.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function addVcIconShortcodesTemplates( $template ) {
		$file = vc_path_dir( 'TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_icon.php' );
		if ( is_file( $file ) ) {
			return $file;
		}
		
		return $template;
	}
	
	/**
	 * Used by filter vc_shortcode_set_template_vc_button2 to set custom template for vc_button2 shortcode.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function addVcButton2ShortcodesTemplates( $template ) {
		$file = vc_path_dir( 'TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_button2.php' );
		if ( is_file( $file ) ) {
			return $file;
		}
		
		return $template;
	}
	
	/**
	 * Used by filter vc_shortcode_set_template_vc_single_image to set custom template for vc_single_image shortcode.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function addVcSingleImageShortcodesTemplates( $template ) {
		$file = vc_path_dir( 'TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_single_image.php' );
		if ( is_file( $file ) ) {
			return $file;
		}
		
		return $template;
	}
	
	/**
	 * Used by filter vc_shortcode_set_template_vc_custom_heading to set custom template for vc_custom_heading
	 * shortcode.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function addVcCustomHeadingShortcodesTemplates( $template ) {
		$file = vc_path_dir( 'TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_custom_heading.php' );
		if ( is_file( $file ) ) {
			return $file;
		}
		
		return $template;
	}
	
	/**
	 * Used by filter vc_shortcode_set_template_vc_button2 to set custom template for vc_button2 shortcode.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function addVcBtnShortcodesTemplates( $template ) {
		$file = vc_path_dir( 'TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_btn.php' );
		if ( is_file( $file ) ) {
			return $file;
		}
		
		return $template;
	}
	
	/**
	 * Map shortcodes for vc_grid_item param type.
	 */
	public function mapShortcodes() {
		// @kludge
		// TODO: refactor with with new way of roles for shortcodes.
		// NEW ROLES like post_type for shortcode and access policies.
		$shortcodes = $this->shortcodes();
		foreach ( $shortcodes as $shortcode_settings ) {
			vc_map( $shortcode_settings );
		}
	}
	
	/**
	 * Get list of predefined templates.
	 *
	 * @return bool|mixed
	 */
	public static function predefinedTemplates() {
		if ( false === self::$predefined_templates ) {
			self::$predefined_templates = apply_filters( 'vc_grid_item_predefined_templates',
				include vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/templates.php' ) );
		}
		
		return self::$predefined_templates;
	}
	
	/**
	 * @param $id - Predefined templates id
	 *
	 * @return array|bool
	 */
	public static function predefinedTemplate( $id ) {
		if ( $id == '' ) {
			$id = etheme_get_custom_product_template();
		}
		$predefined_templates = self::predefinedTemplates();
		if ( isset( $predefined_templates[ $id ]['template'] ) ) {
			return $predefined_templates[ $id ];
		}
		
		return false;
	}
	
	/**
	 * Set template which should grid used when vc_grid_item param value is rendered.
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function setTemplateById( $id ) {
		if ( $id == '' ) {
			$id = etheme_get_custom_product_template();
		}
		require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/templates.php' );
		if ( 0 === strlen( $id ) ) {
			return false;
		}
		if ( preg_match( '/^\d+$/', $id ) ) {
			$post = get_post( (int) $id );
			$post && $this->setTemplate( $post->post_content, $post->ID );
			
			return true;
		} elseif ( false !== ( $predefined_template = $this->predefinedTemplate( $id ) ) ) {
			$this->setTemplate( $predefined_template['template'], $id );
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Setter for template attribute.
	 *
	 * @param $template
	 * @param $template_id
	 */
	public function setTemplate( $template, $template_id ) {
		$this->template    = $template;
		$this->template_id = $template_id;
		$this->parseTemplate( $template );
	}
	
	/**
	 * Add custom css from shortcodes that were mapped for vc grid item.
	 * @return string
	 */
	public function addShortcodesCustomCss( $id = '' ) {
		global $woocommerce_loop;
		$output = $shortcodes_custom_css = '';
		if ( preg_match( '/^\d+$/', $id ) ) {
			$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
		} elseif ( false !== ( $predefined_template = $this->predefinedTemplate( $id ) ) ) {
			$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( $predefined_template['template'] );
		}
		
		if ( ! empty( $shortcodes_custom_css ) ) {
			
			$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
			
			// add style one time on first load
			
			if ( ! apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) ) ) {
				
				if ( ! isset( $woocommerce_loop[ 'custom_template_' . $id . 'et_custom_template_css_added' ] ) ) {
					
					wp_add_inline_style( 'xstore-inline-css', $shortcodes_custom_css );
					
					$woocommerce_loop[ 'custom_template_' . $id . 'et_custom_template_css_added' ] = 'true';
					
				}
			} else {
				$output .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				$output .= $shortcodes_custom_css;
				$output .= '</style>';
			}
			
		}
		
		return $output;
	}
	
	/**
	 * Generates html with template's variables for rendering new project.
	 *
	 * @param $template
	 */
	public function parseTemplate( $template ) {
		$this->mapShortcodes();
		WPBMap::addAllMappedShortcodes();
		$attr                = ' width="' . $this->gridAttribute( 'element_width', 12 ) . '"'
		                       . ' is_end="' . ( 'true' === $this->isEnd() ? 'true' : '' ) . '"';
		$template            = preg_replace( '/(\[(\[?)vc_gitem\b)/', '$1' . $attr, $template );
		$this->html_template .= do_shortcode( $template );
	}
	
	/**
	 * Regexp for variables.
	 * @return string
	 */
	public function templateVariablesRegex() {
		return '/\{\{' . '\{?' . '\s*' . '([^\}\:]+)(\:([^\}]+))?' . '\s*' . '\}\}' . '\}?/';
	}
	
	/**
	 * Get default variables.
	 *
	 * @return array|bool
	 */
	public function getTemplateVariables() {
		if ( ! is_array( $this->found_variables ) ) {
			preg_match_all( $this->templateVariablesRegex(), $this->html_template, $this->found_variables, PREG_SET_ORDER );
		}
		
		return $this->found_variables;
	}
	
	/**
	 * Render item by replacing template variables for exact post.
	 *
	 * @param WP_Post $post
	 *
	 * @return mixed
	 */
	function renderItem( WP_Post $post, $content ) {
		$pattern     = array();
		$replacement = array();
		foreach ( $this->getTemplateVariables() as $var ) {
			$pattern[]     = '/' . preg_quote( $var[0], '/' ) . '/';
			$replacement[] = preg_replace( '/\\$/', '\\\$', $this->attribute( $var[1], $post, isset( $var[3] ) ? trim( $var[3] ) : '' ) );
		}
		
		return preg_replace( $pattern, $replacement, do_shortcode( $content ) );
	}
	
	/**
	 * Adds filters to build templates variables values.
	 */
	public function addAttributesFilters() {
		require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/attributes.php' );
	}
	
	/**
	 * Setter for Grid shortcode attributes.
	 *
	 * @param        $name
	 * @param string $default
	 *
	 * @return string
	 */
	public function gridAttribute( $name, $default = '' ) {
		return isset( $this->grid_atts[ $name ] ) ? $this->grid_atts[ $name ] : $default;
	}
	
	/**
	 * Get attribute value for WP_post object.
	 *
	 * @param        $name
	 * @param        $post
	 * @param string $data
	 *
	 * @return mixed|void
	 */
	public function attribute( $name, $post, $data = '' ) {
		$data = html_entity_decode( $data );
		
		return apply_filters( 'vc_gitem_template_attribute_' . trim( $name ),
			( isset( $post->$name ) ? $post->$name : '' ), array(
				'post' => $post,
				'data' => $data,
			) );
	}
	
	/**
	 * Checks is the end.
	 * @return bool
	 */
	public function isEnd() {
		return $this->is_end;
	}
	
}

// skeleton functions below @todo remove in 8.7
// added in v8.3.6
if (!function_exists('etheme_get_slider_params')) {
	function etheme_get_slider_params($dependency = false) {
		return array();
	}
}

if ( ! function_exists( 'etheme_get_brands_list_params' ) ) {
	function etheme_get_brands_list_params() {
		return array();
	}
}