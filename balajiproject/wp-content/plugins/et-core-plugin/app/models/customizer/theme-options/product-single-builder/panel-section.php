<?php
/**
 * The template created for displaying header panel
 *
 * @version 1.0.0
 * @since   1.4.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$checked = get_option( 'etheme_single_product_builder', false );
	
	$checked = ( $checked ) ? 'checked' : '';
	
	$args = array(
		'single_product_builder' => array(
			'name'        => 'single_product_builder',
			'title'       => esc_html__( 'Single Product Builder', 'xstore-core' ),
			'description' => esc_html__( 'Activating single product builder will deactivate your current single product options. Working with one will disable the other.', 'xstore-core' ) . '<br/><br/><span class="customize-control-kirki-toggle"> <label for="etheme-disable-default-single-product"> <span class="customize-control-title">' . esc_html__( 'Enable single product builder', 'xstore-core' ) . '</span> <input class="screen-reader-text" id="etheme-disable-default-single-product" name="etheme-disable-default-single-product" type="checkbox" ' . $checked . '><span class="switch"></span></label></span>',
			'panel'       => 'woocommerce',
			'icon'        => 'dashicons-align-left',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/single_product_builder', function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'et_placeholder_single_product_builder' => array(
			'name'     => 'et_placeholder_single_product_builder',
			'type'     => 'custom',
			'settings' => 'et_placeholder_single_product_builder',
			'label'    => false,
			'section'  => 'single_product_builder',
			'default'  => '',
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
