<?php
/**
 * The template created for displaying cart page panel
 *
 * @version 0.0.1
 * @since   7.2.3
 */

add_filter( 'et/customizer/add/panels', function ( $panels ) {
	
	$args = array(
		'cart-page' => array(
			'id'    => 'cart-page',
			'title' => esc_html__( 'Cart Page', 'xstore' ),
			'icon'  => 'dashicons-cart',
			'panel' => 'woocommerce'
		)
	);
	
	return array_merge( $panels, $args );
} );