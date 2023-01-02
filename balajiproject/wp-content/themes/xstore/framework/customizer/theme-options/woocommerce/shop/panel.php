<?php
/**
 * The template created for displaying shop panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/panels', function ( $panels ) {
	
	$args = array(
		'shop' => array(
			'id'    => 'shop',
			'title' => esc_html__( 'Shop', 'xstore' ),
			'icon'  => 'dashicons-store',
			'panel' => 'woocommerce'
		)
	);
	
	return array_merge( $panels, $args );
} );