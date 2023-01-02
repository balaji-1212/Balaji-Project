<?php
/**
 * The template created for enqueueing all files for typography panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

$elements = array(
	'content',
);

foreach ( $elements as $key ) {
	require_once apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/theme-options/typography/' . $key . '.php' );
}