<?php
/**
 * The template created for displaying single product navigation options
 *
 * @version 1.0.0
 * @since   0.0.1
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_navigation' => array(
			'name'       => 'product_navigation',
			'title'      => esc_html__( 'Prev/Next product navigation', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-sort',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_navigation', function ( $fields ) use ( $separators ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_navigation_content_separator' => array(
			'name'     => 'product_navigation_content_separator',
			'type'     => 'custom',
			'settings' => 'product_navigation_content_separator',
			'section'  => 'product_navigation',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		'product_navigation_et-desktop'        => array(
			'name'     => 'product_navigation_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_navigation_et-desktop',
			'label'    => esc_html__( 'Show navigation', 'xstore-core' ),
			'section'  => 'product_navigation',
			'default'  => 1,
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );