<?php
/**
 * The template created for enqueueing all files for single product panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'cart-checkout'
);

foreach ( $elements as $key ) {
	require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/cart-checkout/' . $key . '.php' );
}