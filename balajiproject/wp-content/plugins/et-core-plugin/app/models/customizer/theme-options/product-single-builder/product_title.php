<?php
/**
 * The template created for displaying single product title options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_title' => array(
			'name'       => 'product_title',
			'title'      => esc_html__( 'Title', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-welcome-write-blog',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_title', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// style separator
		'product_title_style_separator'       => array(
			'name'     => 'product_title_style_separator',
			'type'     => 'custom',
			'settings' => 'product_title_style_separator',
			'section'  => 'product_title',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// product_title_align
		'product_title_align_et-desktop'      => array(
			'name'      => 'product_title_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_title_align_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'product_title',
			'default'   => 'inherit',
			'choices'   => $choices['alignment_with_inherit'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .product_title',
					'property' => 'text-align',
				)
			)
		),
		
		// product_title_proportion
		'product_title_proportion_et-desktop' => array(
			'name'      => 'product_title_proportion_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_title_proportion_et-desktop',
			'label'     => $strings['label']['size_proportion'],
			'section'   => 'product_title',
			'default'   => 2,
			'choices'   => array(
				'min'  => '0',
				'max'  => '5',
				'step' => '.01',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .product_title',
					'property' => '--h1-size-proportion',
				),
			),
		),
		
		// product_title_fonts
		'product_title_fonts_et-desktop'      => array(
			'name'      => 'product_title_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'product_title_fonts_et-desktop',
			'section'   => 'product_title',
			'default'   => array(
				'font-family'    => '',
				'variant'        => 'regular',
				// 'font-size'      => '14px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				'color'          => '#222',
				'text-transform' => 'none',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product .product_title',
				),
			),
		),
		
		'product_title_box_model_et-desktop'           => array(
			'name'        => 'product_title_box_model_et-desktop',
			'settings'    => 'product_title_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_title',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '10px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '0px',
				'padding-right'       => '0px',
				'padding-bottom'      => '0px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product .product_title',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.single-product .product_title' )
		),
		
		// product_title_border
		'product_title_border_et-desktop'              => array(
			'name'      => 'product_title_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_title_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_title',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .product_title',
					'property' => 'border-style'
				),
			),
		),
		
		// product_title_border_color_custom
		'product_title_border_color_custom_et-desktop' => array(
			'name'      => 'product_title_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_title_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_title',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .product_title',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
