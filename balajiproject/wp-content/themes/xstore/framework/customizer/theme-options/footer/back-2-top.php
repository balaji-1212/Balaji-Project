<?php
/**
 * The template created for displaying back to top options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'back-2-top' => array(
			'name'       => 'back-2-top',
			'title'      => esc_html__( 'Back to top button', 'xstore' ),
			'panel'      => 'footer',
			'icon'       => 'dashicons-admin-collapse dashicons-rotate90',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/back-2-top' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'to_top' => array(
			'name'        => 'to_top',
			'type'        => 'toggle',
			'settings'    => 'to_top',
			'label'       => esc_html__( '"Back To Top" button', 'xstore' ),
			'description' => esc_html__( 'Turn on to have back to top button at the right bottom of the page.', 'xstore' ),
			'section'     => 'back-2-top',
			'default'     => 1,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.back-top',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
			),
		),
		
		'to_top_mobile' => array(
			'name'        => 'to_top_mobile',
			'type'        => 'toggle',
			'settings'    => 'to_top_mobile',
			'label'       => esc_html__( '"Back To Top" button on mobile', 'xstore' ),
			'description' => esc_html__( 'Turn on to have back to top button on mobile.', 'xstore' ),
			'section'     => 'back-2-top',
			'default'     => 1,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.back-top',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );