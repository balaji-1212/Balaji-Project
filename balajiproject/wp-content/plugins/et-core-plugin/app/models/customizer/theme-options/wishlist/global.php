<?php
/**
 * The template created for enqueueing all files for built-in wishlist options
 *
 * @version 0.0.1
 * @since   4.3.8
 */

$elements = array(
	'wishlist'
);

foreach ( $elements as $key ) {
	require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/wishlist/' . $key . '.php' );
}