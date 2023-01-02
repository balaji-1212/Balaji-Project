<?php
/**
 * The template created for displaying single product page panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/panels', function ( $panels ) {
	
	$args = array(
		'single-product-page' => array(
			'id'    => 'single-product-page',
			'title' => esc_html__( 'Single Product Page', 'xstore' ),
			'icon'  => 'dashicons-align-left',
			'panel' => 'woocommerce'
		)
	);
	
	return array_merge( $panels, $args );
} );