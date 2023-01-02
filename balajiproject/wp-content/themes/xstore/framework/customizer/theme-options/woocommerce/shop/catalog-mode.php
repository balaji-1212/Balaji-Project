<?php
/**
 * The template created for displaying catalog mode options
 *
 * @version 0.0.2
 * @since   6.0.0
 */

// section catalog-mode
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'catalog-mode' => array(
			'name'       => 'catalog-mode',
			'title'      => esc_html__( 'Catalog Mode', 'xstore' ),
			'panel'      => 'shop',
			'icon'       => 'dashicons-hidden', // dashicons-tickets-alt
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/catalog-mode' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'just_catalog' => array(
			'name'        => 'just_catalog',
			'type'        => 'toggle',
			'settings'    => 'just_catalog',
			'label'       => esc_html__( 'Just Catalog', 'xstore' ),
			'description' => esc_html__( 'Turn on to disable ability to buy products via removing "Add to Cart" buttons.', 'xstore' ),
			'section'     => 'catalog-mode',
			'default'     => 0,
		),
		
		'just_catalog_type' => array(
			'name'            => 'just_catalog_type',
			'type'            => 'select',
			'settings'        => 'just_catalog_type',
			'label'           => esc_html__( 'Permission restrictions just for:', 'xstore' ),
			'section'         => 'catalog-mode',
			'default'         => 'all',
			'choices'         => array(
				'all'          => esc_html__( 'All', 'xstore' ),
				'unregistered' => esc_html__( 'Unregistered', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'just_catalog',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),
		
		'just_catalog_price' => array(
			'name'            => 'just_catalog_price',
			'type'            => 'toggle',
			'settings'        => 'just_catalog_price',
			'label'           => esc_html__( 'Hide price', 'xstore' ),
			'description'     => esc_html__( 'Turn on to hide product price.', 'xstore' ),
			'section'         => 'catalog-mode',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'just_catalog',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),
		
		'ltv_price' => array(
			'name'            => 'ltv_price',
			'type'            => 'etheme-text',
			'settings'        => 'ltv_price',
			'label'           => esc_html__( 'Price text', 'xstore' ),
			'description'     => esc_html__( 'This text will be shown in place of price in archive and product pages. Leave empty to hide texts and prices.', 'xstore' ),
			'section'         => 'catalog-mode',
			'default'         => esc_html__( 'Login to view price', 'xstore' ),
			'active_callback' => array(
				array(
					'setting'  => 'just_catalog',
					'operator' => '=',
					'value'    => 1,
				),
				array(
					'setting'  => 'just_catalog_price',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );