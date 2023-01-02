<?php
/**
 * The template created for enqueueing all files for woocommerce panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'shop',
	'shop-elements',
);

if ( ! get_option( 'etheme_single_product_builder', false ) ) {
	$elements[] = 'single-product';
}

$elements[] = 'cart';

require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/woocommerce/panel.php' );

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/woocommerce/' . $key . '/global.php' );
}