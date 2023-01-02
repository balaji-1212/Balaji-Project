<?php
/**
 * The template created for enqueueing all files for header panel
 *
 * @version 1.0.0
 * @since   1.4.0
 */

$index = 0;

$elements = array(
	'panel',
	'logo',
	'header_presets',
	'top_header',
	'main_header',
	'bottom_header',
	'headers_sticky',
	'header_overlap',
	'header_vertical',
	'menu',
	'secondary_menu',
	'menu_dropdown',
	'all_departments',
	'mobile_menu',
	( class_exists( 'WooCommerce' ) ) ? 'cart' : 'cart_off',
	'wishlist',
	'compare',
	'account',
	'search',
	'header_socials',
	'contacts',
	'newsletter',
	'button',
	'promo_text',
	'html_blocks',
	'widgets',
	'builder_elements',
	'reset_settings'
);


foreach ( $elements as $key ) {
	require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/header-builder/' . $key . '.php' );
}