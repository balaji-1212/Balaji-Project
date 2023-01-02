<?php
/**
 * The template created for displaying custom css options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/panels', function ( $panels ) use ( $priorities ) {
	
	$args = array(
		'style-custom_css' => array(
			'id'          => 'style-custom_css',
			'title'       => esc_html__( 'Theme Custom CSS', 'xstore' ),
			'description' => esc_html__( 'Once you\'ve isolated a part of theme that you\'d like to change, enter your CSS code to the fields below. Do not add JS or HTML to the fields. Custom CSS, entered here, will override a theme CSS. In some cases, the !important tag may be needed.', 'xstore' ),
			'icon'        => 'dashicons-admin-customizer',
			'priority'    => $priorities['custom-css']
		)
	);
	
	return array_merge( $panels, $args );
} );

add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'global-custom_css' => array(
			'name'     => 'global-custom_css',
			'title'    => esc_html__( 'Global CSS', 'xstore' ),
			'icon'     => 'dashicons-admin-customizer',
			'type'     => 'outer',
			'panel'    => 'style-custom_css',
			'priority' => $priorities['custom-css']
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'global-custom_css' => array(
			'name'     => 'global-custom_css',
			'type'     => 'code',
			'settings' => 'custom_css_global',
			'section'  => 'global-custom_css',
			'default'  => '',
			'choices'  => array(
				'language' => 'css',
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );

add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'desktop-custom_css' => array(
			'name'     => 'desktop-custom_css',
			'title'    => esc_html__( 'Desktop (992px+)', 'xstore' ),
			'icon'     => 'dashicons-admin-customizer',
			'type'     => 'outer',
			'panel'    => 'style-custom_css',
			'priority' => $priorities['custom-css']
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'custom_css_desktop' => array(
			'name'     => 'custom_css_desktop',
			'type'     => 'code',
			'settings' => 'custom_css_desktop',
			'section'  => 'desktop-custom_css',
			'default'  => '',
			'choices'  => array(
				'language' => 'css',
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );

add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'tablet-custom_css' => array(
			'name'     => 'tablet-custom_css',
			'title'    => esc_html__( 'Tablet (768px - 991px)', 'xstore' ),
			'icon'     => 'dashicons-admin-customizer',
			'type'     => 'outer',
			'panel'    => 'style-custom_css',
			'priority' => $priorities['custom-css']
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'custom_css_tablet' => array(
			'name'     => 'custom_css_tablet',
			'type'     => 'code',
			'settings' => 'custom_css_tablet',
			'section'  => 'tablet-custom_css',
			'default'  => '',
			'choices'  => array(
				'language' => 'css',
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );

add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'wide_mobile-custom_css' => array(
			'name'     => 'wide_mobile-custom_css',
			'title'    => esc_html__( 'Mobile landscape (481px - 767px)', 'xstore' ),
			'icon'     => 'dashicons-admin-customizer',
			'type'     => 'outer',
			'panel'    => 'style-custom_css',
			'priority' => $priorities['custom-css']
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'custom_css_wide_mobile' => array(
			'name'     => 'custom_css_wide_mobile',
			'type'     => 'code',
			'settings' => 'custom_css_wide_mobile',
			'section'  => 'wide_mobile-custom_css',
			'default'  => '',
			'choices'  => array(
				'language' => 'css',
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );

add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'mobile-custom_css' => array(
			'name'     => 'mobile-custom_css',
			'title'    => esc_html__( 'Mobile (0 - 480px)', 'xstore' ),
			'icon'     => 'dashicons-admin-customizer',
			'type'     => 'outer',
			'panel'    => 'style-custom_css',
			'priority' => $priorities['custom-css']
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'custom_css_mobile' => array(
			'name'     => 'custom_css_mobile',
			'type'     => 'code',
			'settings' => 'custom_css_mobile',
			'section'  => 'mobile-custom_css',
			'default'  => '',
			'choices'  => array(
				'language' => 'css',
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );