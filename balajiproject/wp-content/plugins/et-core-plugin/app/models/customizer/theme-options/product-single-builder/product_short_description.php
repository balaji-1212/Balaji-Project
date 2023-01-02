<?php
/**
 * The template created for displaying product short description options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_short_description' => array(
			'name'       => 'product_short_description',
			'title'      => esc_html__( 'Short description', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-clipboard',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_short_description', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_short_description_style_separator' => array(
			'name'     => 'product_short_description_style_separator',
			'type'     => 'custom',
			'settings' => 'product_short_description_style_separator',
			'section'  => 'product_short_description',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		'product_short_description_zoom_et-desktop'  => array(
			'name'      => 'product_short_description_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_short_description_zoom_et-desktop',
			'label'     => esc_html__( 'Content size (%)', 'xstore-core' ),
			'section'   => 'product_short_description',
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
					'element'       => '.single-product .et_product-block .woocommerce-product-details__short-description',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// product_short_description_align
		'product_short_description_align_et-desktop' => array(
			'name'      => 'product_short_description_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_short_description_align_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'product_short_description',
			'default'   => 'inherit',
			'choices'   => $choices['alignment_with_inherit'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-details__short-description',
					'property' => 'text-align',
				),
			),
		),
		
		'product_short_color_custom_et-desktop' => array(
			'name'      => 'product_short_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_short_color_custom_et-desktop',
			'label'     => $strings['label']['color'],
			'section'   => 'product_short_description',
			'default'   => '#555555',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-details__short-description',
					'property' => 'color',
				),
			),
		),
		
		'product_short_description_box_model_et-desktop'           => array(
			'name'        => 'product_short_description_box_model_et-desktop',
			'settings'    => 'product_short_description_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_short_description',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '15px',
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
					'element' => '.single-product .et_product-block .woocommerce-product-details__short-description',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.single-product .et_product-block .woocommerce-product-details__short-description' )
		),
		
		// product_short_description_border
		'product_short_description_border_et-desktop'              => array(
			'name'      => 'product_short_description_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_short_description_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_short_description',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-details__short-description',
					'property' => 'border-style'
				),
			),
		),
		
		// product_short_description_border_color_custom
		'product_short_description_border_color_custom_et-desktop' => array(
			'name'      => 'product_short_description_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_short_description_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_short_description',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-details__short-description',
					'property' => 'border-color',
				),
			),
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
