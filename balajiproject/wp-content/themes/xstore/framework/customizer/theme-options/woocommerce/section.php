<?php  
	/**
	 * The template created for displaying woocommerce section when WooCommerce plugin is inactive
	 *
	 * @version 0.0.1
	 * @since 6.0.0
	 */
	
add_filter( 'et/customizer/add/sections', function($sections) use($priorities){

	$args = array(
		'woocommerce_off'	 => array(
			'name'        => 'woocommerce_off',
			'title'          => esc_html__( 'WooCommerce (Shop)', 'xstore' ),
			'icon' => 'dashicons-cart',
			'priority' => $priorities['woocommerce'],
			'type'		=> 'kirki-lazy',
			'dependency'    => array()
		)
	);
	return array_merge( $sections, $args );
});

$hook = class_exists('ETC_Initial') ? 'et/customizer/add/fields/woocommerce_off' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();

		// Array of fields
	$args = array(
		'woocommerce_off_text' => array(
			'name'	   => 'woocommerce_off_text',
			'type'     => 'custom',
			'settings' => 'woocommerce_off_text',
			'section'  => 'woocommerce_off',
			'default'     => esc_html__('To use WooCommerce options please install ', 'xstore') . '<a href="https://uk.wordpress.org/plugins/woocommerce/" rel="nofollow" target="_blank">' . esc_html__('WooCommerce', 'xstore') . '</a>',
		),

	);

	return array_merge( $fields, $args );

});