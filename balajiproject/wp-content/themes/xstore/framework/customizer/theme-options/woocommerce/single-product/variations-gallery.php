<?php
/**
 * The template created for displaying single product variation gallery options
 *
 * @version 0.0.1
 * @since   6.2.12
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'variations-gallery' => array(
			'name'       => 'variations-gallery',
			'title'      => esc_html__( 'Variation gallery', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-images-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/variations-gallery' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'enable_variation_gallery' => array(
			'name'        => 'enable_variation_gallery',
			'type'        => 'toggle',
			'settings'    => 'enable_variation_gallery',
			'label'       => esc_html__( 'Variation gallery', 'xstore' ),
			'description' => esc_html__( 'Turn on to use separate gallery for product variations.', 'xstore' ),
			'section'     => 'variations-gallery',
			'default'     => 0,
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );