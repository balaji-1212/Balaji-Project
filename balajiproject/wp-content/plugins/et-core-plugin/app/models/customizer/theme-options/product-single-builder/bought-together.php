<?php
/**
 * The template created for displaying single button options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-bought-together' => array(
			'name'       => 'single-bought-together',
			'title'      => esc_html__( 'Bought Together', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-products',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/single-bought-together', function ( $fields ) use ( $separators, $strings, $choices, $box_models ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'single_product_bought_together_content_separator' => array(
			'name'     => 'single_product_bought_together_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_bought_together_content_separator',
			'section'  => 'single-bought-together',
			'default'  => $separators['content'],
			'priority' => 10,
		),

// single_product_bought_together_per_view
//        'single_product_bought_together_per_view_et-desktop'	=>	 array(
//            'name'		  => 'single_product_bought_together_per_view_et-desktop',
//            'type'        => 'slider',
//            'settings'    => 'single_product_bought_together_per_view_et-desktop',
//            'label'       => esc_html__( 'Products per view', 'xstore-core' ),
//            'section'     => 'single-bought-together',
//            'default'     => 3,
//            'choices'     => array(
//                'min'  => '1',
//                'max'  => '6',
//                'step' => '1',
//            ),
//        ),
//
//        // single_product_bought_together_per_view
//        'single_product_bought_together_per_view_et-mobile'	=>	 array(
//            'name'		  => 'single_product_bought_together_per_view_et-mobile',
//            'type'        => 'slider',
//            'settings'    => 'single_product_bought_together_per_view_et-mobile',
//            'label'       => esc_html__( 'Products per view', 'xstore-core' ),
//            'section'     => 'single-bought-together',
//            'default'     => 2,
//            'choices'     => array(
//                'min'  => '1',
//                'max'  => '6',
//                'step' => '1',
//            ),
//        ),

//				// single_product_bought_together_products_title
		'single_product_bought_together_products_title'    => array(
			'name'      => 'single_product_bought_together_products_title',
			'type'      => 'etheme-text',
			'settings'  => 'single_product_bought_together_products_title',
			'label'     => __( 'Title', 'xstore-core' ),
			'section'   => 'single-bought-together',
			'default'   => esc_html__( 'Frequently bought together', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.bought-together-products > .title span',
					'function' => 'html',
				),
			),
		),
		
		'single_product_bought_together_redirect'        => array(
			'name'     => 'single_product_bought_together_redirect',
			'type'     => 'select',
			'settings' => 'single_product_bought_together_redirect',
			'label'    => __( 'Action after add to cart', 'xstore-core' ),
			'section'  => 'single-bought-together',
			'default'  => 'cart',
			'choices'  => array(
				'cart'     => __( 'Cart Page', 'xstore-core' ),
				'checkout' => __( 'Checkout Page', 'xstore-core' ),
				'product'  => __( 'Product Page', 'xstore-core' ),
			),
		),
		
		// style separator
		'single_product_bought_together_style_separator' => array(
			'name'     => 'single_product_bought_together_style_separator',
			'type'     => 'custom',
			'settings' => 'single_product_bought_together_style_separator',
			'section'  => 'single-bought-together',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		'single_product_bought_together_box_model_et-desktop' => array(
			'name'        => 'single_product_bought_together_box_model_et-desktop',
			'settings'    => 'single_product_bought_together_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => esc_html__( 'You can select the margin, border-width and padding for account element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'single-bought-together',
			'default'     => array(
				'margin-top'          => '60px',
				'margin-right'        => '0px',
				'margin-bottom'       => '80px',
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
					'element' => '.single-product-builder .bought-together-products',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.single-product-builder .bought-together-products' )
		),
	);
	
	return array_merge( $fields, $args );
	
} );
