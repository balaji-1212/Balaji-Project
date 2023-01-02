<?php
/**
 * The template created for enqueueing all files for footer panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'panel',
	'layout',
	'styling',
	'copyrights',
	'back-2-top'
);

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/footer/' . $key . '.php' );
}