<?php
/**
 * The template created for enqueueing all files for shop panel
 *
 * @version 0.0.2
 * @since   6.0.0
 * @log     0.0.2
 * ADDED: filters
 */

$elements = array(
	'panel',
	'layout',
	'filters',
	'products-style',
	'catalog-mode',
);

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/woocommerce/shop/' . $key . '.php' );
}