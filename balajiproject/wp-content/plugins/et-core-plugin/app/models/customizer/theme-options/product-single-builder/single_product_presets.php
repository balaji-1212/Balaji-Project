<?php
/**
 * The template created for displaying single product presets
 *
 * @version 1.0.0
 * @since   0.0.1
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single_product_presets' => array(
			'name'       => 'single_product_presets',
			'title'      => esc_html__( 'Presets', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-schedule',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/single_product_presets', function ( $fields ) use ( $separators ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'single_product_presets_content_separator' => array(
			'name'     => 'single_product_presets_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_presets_content_separator',
			'section'  => 'single_product_presets',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		'single_product_presets_package_et'        => array(
			'name'     => 'single_product_presets_package_et',
			'type'     => 'radio-image',
			'settings' => 'single_product_presets_package_et-desktop',
			'label'    => esc_html__( 'Presets', 'xstore-core' ),
			'section'  => 'single_product_presets',
			'default'  => 'default',
			'priority' => 10,
			'choices'  => array(
				'default'  => 'Default',
				'default2' => 'Wide',
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );
