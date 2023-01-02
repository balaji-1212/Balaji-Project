<?php
/**
 * The template created for displaying single product variation gallery options
 *
 * @version 0.0.1
 * @since   2.2.2
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'variations-gallery' => array(
			'name'       => 'variations-gallery',
			'title'      => esc_html__( 'Variation gallery', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-images-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/variations-gallery', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'enable_variation_gallery' => array(
			'name'        => 'enable_variation_gallery',
			'type'        => 'toggle',
			'settings'    => 'enable_variation_gallery',
			'label'       => esc_html__( 'Variation gallery', 'xstore-core' ),
			'description' => esc_html__( 'Turn on to use separate gallery for product variations.', 'xstore-core' ),
			'section'     => 'variations-gallery',
			'default'     => 0,
		),
	);
	
	return array_merge( $fields, $args );
	
} );