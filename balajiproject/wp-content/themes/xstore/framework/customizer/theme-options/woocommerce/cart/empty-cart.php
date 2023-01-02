<?php
/**
 * The template created for displaying empty cart options
 *
 * @version 1.0.0
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'empty-cart' => array(
			'name'       => 'empty-cart',
			'title'      => esc_html__( 'Empty cart content', 'xstore' ),
			'panel'      => 'cart-page',
			'icon'       => 'dashicons-cart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/empty-cart' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'empty_cart_content' => array(
			'name'        => 'empty_cart_content',
			'type'        => 'editor',
			'settings'    => 'empty_cart_content',
			'label'       => esc_html__( 'Text for empty cart', 'xstore' ),
			'description' => esc_html__( 'Add the content you need to display on the empty cart page instead of the default content.', 'xstore' ),
			'section'     => 'empty-cart',
			'default'     => '<h1 style="text-align: center;">YOUR SHOPPING CART IS EMPTY</h1><p style="text-align: center;">We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!</p> ',
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );