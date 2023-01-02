<?php
/**
 * The template created for displaying copyright styling options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'copyright-styling' => array(
			'name'       => 'copyright-styling',
			'title'      => esc_html__( 'Copyrights styling', 'xstore' ),
			'panel'      => 'footer',
			'icon'       => 'dashicons-nametag',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/copyright-styling' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $text_color_scheme, $paddings_empty, $padding_labels ) {
	
	$args = array();
	
	// Array of fields
	$args = array(
		'copyrights_color' => array(
			'name'        => 'copyrights_color',
			'type'        => 'select',
			'settings'    => 'copyrights_color',
			'label'       => esc_html__( 'Copyrights text color scheme', 'xstore' ),
			'description' => esc_html__( 'Choose copyrights text color scheme.', 'xstore' ),
			'section'     => 'copyright-styling',
			'default'     => 'dark',
			'choices'     => $text_color_scheme,
			// 'transport' => 'postMessage',
			// 'js_vars'     => array(
			// 	array(
			// 'context'   => array('editor', 'front'),
			// 		'element'  => '.footer-bottom',
			// 		'function' => 'toggleClass',
			// 		'class' => 'text-color-dark',
			// 		'value' => 'dark'
			// 	),
			// 	array(
			// 'context'   => array('editor', 'front'),
			// 		'element'  => '.footer-bottom',
			// 		'function' => 'toggleClass',
			// 		'class' => 'text-color-white',
			// 		'value' => 'white'
			// 	),
			// ),
		),
		
		'copyrights-links' => array(
			'name'        => 'copyrights-links',
			'type'        => 'multicolor',
			'settings'    => 'copyrights-links',
			'label'       => esc_html__( 'Copyrights Links', 'xstore' ),
			'description' => esc_html__( 'Choose copyrights links colors.', 'xstore' ),
			'section'     => 'copyright-styling',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
				'active'  => esc_html__( 'Active', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
				'active'  => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'element'  => '.footer-bottom.text-color-light a, .footer-bottom.text-color-dark a, .footer-bottom a',
					'property' => 'color',
				),
				array(
					'choice'   => 'hover',
					'element'  => '.footer-bottom.text-color-light a:hover, .footer-bottom.text-color-dark a:hover, .footer-bottom a:hover',
					'property' => 'color',
				),
				array(
					'choice'   => 'active',
					'element'  => '.footer-bottom.text-color-light a:active, .footer-bottom.text-color-dark a:active, .footer-bottom a:active',
					'property' => 'color',
				),
			),
		),
		
		'copyrights_bg_color' => array(
			'name'        => 'copyrights_bg_color',
			'type'        => 'background',
			'settings'    => 'copyrights_bg_color',
			'label'       => esc_html__( 'Copyrights Background Color', 'xstore' ),
			'description' => esc_html__( 'Controls the the copyrights background color.', 'xstore' ),
			'section'     => 'copyright-styling',
			'default'     => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => '',
				'background-position'   => '',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.footer-bottom, [data-mode="dark"] .footer-bottom',
				),
			),
		),
		
		'copyrights_padding' => array(
			'name'        => 'copyrights_padding',
			'type'        => 'dimensions',
			'settings'    => 'copyrights_padding',
			'label'       => esc_html__( 'Copyrights paddings', 'xstore' ),
			'description' => esc_html__( 'Controls the paddings for the copyright area. Leave empty to use default values.', 'xstore' ),
			'section'     => 'copyright-styling',
			'default'     => $paddings_empty,
			'choices'     => array(
				'labels' => $padding_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'padding-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.footer-bottom',
					'property' => 'padding-top'
				),
				array(
					'choice'   => 'padding-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.footer-bottom',
					'property' => 'padding-bottom'
				),
				array(
					'choice'   => 'padding-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.footer-bottom',
					'property' => 'padding-left'
				),
				array(
					'choice'   => 'padding-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.footer-bottom',
					'property' => 'padding-right'
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );