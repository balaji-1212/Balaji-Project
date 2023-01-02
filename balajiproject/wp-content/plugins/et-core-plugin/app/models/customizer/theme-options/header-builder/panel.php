<?php
/**
 * The template created for displaying header panel
 *
 * @version 1.0.0
 * @since   1.4.0
 */
add_filter( 'et/customizer/add/panels', function ( $panels ) {
	
	$args = array(
		'header-builder' => array(
			'id'       => 'header-builder',
			'title'    => esc_html__( 'Header Builder', 'xstore-core' ),
			'icon'     => 'dashicons-arrow-up-alt',
			'priority' => 3
		)
	);
	
	return array_merge( $panels, $args );
} );