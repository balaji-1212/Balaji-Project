<?php
/**
 * The template created for enqueueing all files for built-in compare options
 *
 * @version 0.0.1
 * @since   4.3.9
 */

$elements = array(
	'compare'
);

foreach ( $elements as $key ) {
	require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/compare/' . $key . '.php' );
}