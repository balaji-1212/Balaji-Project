<?php
/**
 * The template created for displaying product stock options
 *
 * @version 0.0.1
 * @since   6.2.2
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product-stock' => array(
			'name'       => 'product-stock',
			'title'      => esc_html__( 'Advanced product stock', 'xstore' ),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-chart-line',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/product-stock' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'advanced_stock_status' => array(
			'name'        => 'advanced_stock_status',
			'type'        => 'toggle',
			'settings'    => 'advanced_stock_status',
			'label'       => esc_html__( 'Enable advanced stock', 'xstore' ),
			'description' => esc_html__( 'Turn on to enable stock line for products managed stock option is enabled.', 'xstore' ),
			'section'     => 'product-stock',
			'default'     => 0,
		),
		
		// advanced_stock_locations
		'advanced_stock_locations'  => array(
			'name'        => 'advanced_stock_locations',
			'type'        => 'select',
			'settings'    => 'advanced_stock_locations',
			'label'       => esc_html__( 'Advanced stock locations', 'xstore' ),
			'section'     => 'product-stock',
			'placeholder' => esc_html__( 'Show advanced stock in ...', 'xstore' ),
			'multiple'    => 3,
			'default'     => array(
				'single_product',
				'quick_view'
			),
			'choices'     => array(
				'single_product'                => esc_html__( 'Single product', 'xstore' ),
				'quick_view'                => esc_html__( 'Quick view', 'xstore' ),
				'product_archives' => esc_html__( 'Product archives', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'advanced_stock_status',
					'operator' => '!=',
					'value'    => '0',
				),
			),
		),
		
		'product_stock_colors' => array(
			'name'        => 'product_stock_colors',
			'type'        => 'multicolor',
			'settings'    => 'product_stock_colors',
			'label'       => esc_html__( 'Stock colors', 'xstore' ),
			'description' => '<a href="' . admin_url( "admin.php?page=wc-settings&tab=products&section=inventory" ) . '" target="_blank">' . esc_html__( 'Low stock threshold value', 'xstore' ) . '</a>',
			'section'     => 'product-stock',
			'choices'     => array(
				'step1' => esc_html__( 'Full stock', 'xstore' ),
				'step2' => esc_html__( 'Middle stock (sold more than 50%)', 'xstore' ),
				'step3' => esc_html__( 'Low stock', 'xstore' ),
			),
			'default'     => array(
				'step1' => '#2e7d32',
				'step2' => '#f57f17',
				'step3' => '#c62828',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'step1',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-stock-step-1-active-color',
				),
				array(
					'choice'   => 'step2',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-stock-step-2-active-color',
				),
				array(
					'choice'   => 'step3',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-stock-step-3-active-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );