<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Changelog" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.0
 */

$out = '';
$out .= '<h2 class="etheme-page-title etheme-page-title-type-2">' . esc_html__( 'Changelog', 'xstore' ) . '</h2><br/>';

$check_update = new ETheme_Version_Check();
if( $check_update->is_update_available() ) {
	$out .= '<p class="et-message et-success">' . esc_html__('New update available with new features, performance improvements and bug fixes.', 'xstore') .
	        '<a style="line-height: 0.7; padding: 8px 12px; float: right;" class="et-button et-button-green no-loader" href="'.admin_url('update-core.php').'" style="float: right;">'.esc_html__('Update Now', 'xstore').'</a>' .
	        '</p>';
}
	
	if ( function_exists( 'wp_remote_get' ) ) {
	$response = wp_remote_get( 'https://xstore.8theme.com/change-log.php?type=panel' );
	$response = wp_remote_retrieve_body( $response );
	$response = str_replace( 'class="arrow"', '', $response );
	$response = str_replace( '<h2>', '<h4>', $response );
	$response = str_replace( '</h2>', '</h4>', $response );
	$response = str_replace( '[vc_column_text]', '', $response);
	$response = str_replace( '<div></div>', '', $response);
	$response = str_replace( '<div class="row"></div>', '', $response);
	$response = str_replace( '<p></p>', '', $response);
	$out .= $response;
	$out .= '<div>'; // Open div to fix structure in $response
} else {
	$out .= '<p class="et-message et-error">' . esc_html__( 'Can not get changelog data', 'xstore' ) . '</p>';
}

echo '<div class="etheme-div etheme-changelog">' . $out . '</div>';