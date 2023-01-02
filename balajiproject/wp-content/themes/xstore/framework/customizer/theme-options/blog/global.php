<?php
/**
 * The template created for enqueueing all files for blog panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'panel',
	'layout',
	'single-post'
);

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/blog/' . $key . '.php' );
}