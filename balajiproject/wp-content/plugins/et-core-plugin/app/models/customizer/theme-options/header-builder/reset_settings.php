<?php
/**
 * The template created for displaying header panel
 *
 * @version 1.0.0
 * @since   1.4.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_builder_reset' => array(
			'name'        => 'header_builder_reset',
			'title'       => esc_html__( 'Reset header builder', 'xstore-core' ),
			'description' => esc_html__( 'This option will clear your prebuild header elements', 'xstore-core' ),
			'icon'        => 'dashicons-image-rotate',
			'panel'       => 'header-builder',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/header_builder_reset', function ( $fields ) use ( $index ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'et_placeholder_header_builder_reset' => array(
			'name'     => 'et_placeholder_header_builder_reset',
			'type'     => 'custom',
			'settings' => 'et_placeholder_header_builder_reset',
			'label'    => esc_html__( 'Reset header builder settings', 'xstore-core' ),
			'section'  => 'header_builder_reset',
			'default'  => '<span id="etheme-reset-header-builder" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Reset elements', 'xstore-core' ) . '</span>',
			'priority' => 10,
		)
	);
	
	return array_merge( $fields, $args );
	
} );