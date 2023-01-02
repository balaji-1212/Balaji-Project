<?php
/**
 * The template created for displaying cart options
 *
 * @version 1.0.0
 * @since   7.2.3
 *
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'cart-cross-sell' => array(
			'name'       => 'cart-cross-sell',
			'title'      => esc_html__( 'Cross-sell products', 'xstore' ),
			'panel'      => 'cart-page',
			'icon'       => 'dashicons-tag',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/cart-cross-sell' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'cart_products_cross_sell_type_et-desktop'     => array(
			'name'     => 'cart_products_cross_sell_type_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'cart_products_cross_sell_type_et-desktop',
			'label'    => esc_html__( 'Type', 'xstore' ),
			'section'  => 'cart-cross-sell',
			'default'  => 'slider',
			'multiple' => 1,
			'choices'  => array(
				'grid'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/grid.svg',
				'slider' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/slider.svg',
				//		'widget' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/widget.svg',
			)
		),
		'cart_products_cross_sell_per_view_et-desktop' => array(
			'name'     => 'cart_products_cross_sell_per_view_et-desktop',
			'type'     => 'slider',
			'settings' => 'cart_products_cross_sell_per_view_et-desktop',
			'label'    => esc_html__( 'Cross-sell products per view', 'xstore' ),
			'section'  => 'cart-cross-sell',
			'default'  => 4,
			'choices'  => array(
				'min'  => '1',
				'max'  => '6',
				'step' => '1',
			),
		),
		'cart_products_cross_sell_limit_et-desktop'    => array(
			'name'     => 'cart_products_cross_sell_limit_et-desktop',
			'type'     => 'slider',
			'settings' => 'cart_products_cross_sell_limit_et-desktop',
			'label'    => esc_html__( 'Cross-sell products limit', 'xstore' ),
			'section'  => 'cart-cross-sell',
			'default'  => 7,
			'choices'  => array(
				'min'  => '1',
				'max'  => '30',
				'step' => '1',
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );