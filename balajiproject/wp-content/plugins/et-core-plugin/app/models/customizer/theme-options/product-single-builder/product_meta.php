<?php
/**
 * The template created for displaying single product meta options
 *
 * @since   1.5.0
 * @version 1.0.1
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_meta' => array(
			'name'       => 'product_meta',
			'title'      => esc_html__( 'Product meta', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-format-aside',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_meta', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_meta_content_separator' => array(
			'name'     => 'product_meta_content_separator',
			'type'     => 'custom',
			'settings' => 'product_meta_content_separator',
			'section'  => 'product_meta',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// product_meta_content
		'product_meta_content'           => array(
			'name'        => 'product_meta_content',
			'type'        => 'sortable',
			'label'       => $strings['label']['elements'],
			'description' => esc_html__( 'On/Off elements you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'     => 'product_meta',
			'priority'    => 10,
			'settings'    => 'product_meta_content',
			'default'     => array(
				'sku',
				'gtin',
				'categories',
				'tags',
			),
			'choices'     => array(
				'sku'        => esc_html__( 'SKU', 'xstore-core' ),
				'gtin'       => esc_html__( 'GTIN', 'xstore-core' ),
				'categories' => esc_html__( 'Categories', 'xstore-core' ),
				'tags'       => esc_html__( 'Tags', 'xstore-core' ),
			),
		),
		
		// style separator
		'product_meta_style_separator'   => array(
			'name'     => 'product_meta_style_separator',
			'type'     => 'custom',
			'settings' => 'product_meta_style_separator',
			'section'  => 'product_meta',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		'product_meta_zoom_et-desktop'      => array(
			'name'      => 'product_meta_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_meta_zoom_et-desktop',
			'label'     => esc_html__( 'Content size (%)', 'xstore-core' ),
			'section'   => 'product_meta',
			'default'   => 100,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.single-product .et_product-block .product_meta',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// product_title_align
		'product_meta_align_et-desktop'     => array(
			'name'      => 'product_meta_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_meta_align_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'product_meta',
			'default'   => 'inherit',
			'choices'   => $choices['alignment2_with_inherit'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .product_meta',
					'property' => 'justify-content',
				),
			),
		),
		
		// product_meta_direction
		'product_meta_direction_et-desktop' => array(
			'name'      => 'product_meta_direction_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_meta_direction_et-desktop',
			'label'     => $strings['label']['direction'],
			'section'   => 'product_meta',
			'default'   => 'column',
			'choices'   => $choices['direction']['type2'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .product_meta',
					'property' => 'flex-direction',
				),
			),
		),
		
		'product_meta_color_01_custom_et-desktop' => array(
			'name'      => 'product_meta_color_01_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_meta_color_01_custom_et-desktop',
			'label'     => esc_html__( 'Color 01', 'xstore-core' ),
			'section'   => 'product_meta',
			'choices'   => array(
				'alpha' => true
			),
			'default'   => '#222222',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .product_meta, .single-product .et_product-block .product_meta a:hover',
					'property' => 'color',
				),
			),
		),
		
		'product_meta_color_02_custom_et-desktop'     => array(
			'name'      => 'product_meta_color_02_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_meta_color_02_custom_et-desktop',
			'label'     => esc_html__( 'Color 02', 'xstore-core' ),
			'section'   => 'product_meta',
			'choices'   => array(
				'alpha' => true
			),
			'default'   => '#888888',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .product_meta span a, .single-product .et_product-block .product_meta span span',
					'property' => 'color',
				),
			),
		),
		'product_meta_box_model_et-desktop'           => array(
			'name'        => 'product_meta_box_model_et-desktop',
			'settings'    => 'product_meta_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_meta',
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
					'element' => '.single-product .et_product-block .product_meta',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.single-product .et_product-block .product_meta' )
		),
		
		// product_title_border
		'product_meta_border_et-desktop'              => array(
			'name'      => 'product_meta_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_meta_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_meta',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .product_meta',
					'property' => 'border-style'
				),
			),
		),
		
		// product_title_border_color_custom
		'product_meta_border_color_custom_et-desktop' => array(
			'name'      => 'product_meta_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_meta_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_meta',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .product_meta',
					'property' => 'border-color',
				),
			),
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
