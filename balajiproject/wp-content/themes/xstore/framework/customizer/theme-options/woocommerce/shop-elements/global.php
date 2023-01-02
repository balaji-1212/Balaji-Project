<?php
/**
 * The template created for enqueueing all files for header panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'panel',
	'shop-categories',
	'shop-icons',
	'shop-quick-view',
	'product-stock',
	'shop-color-swatches',
    'quantity',
	'shop-brands',
);

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/woocommerce/shop-elements/' . $key . '.php' );
}