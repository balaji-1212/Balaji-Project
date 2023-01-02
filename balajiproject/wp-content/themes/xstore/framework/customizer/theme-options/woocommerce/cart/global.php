<?php
/**
 * The template created for enqueueing all files for single product panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'panel',
	'cross-sell',
	'empty-cart',
);

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/woocommerce/cart/' . $key . '.php' );
}