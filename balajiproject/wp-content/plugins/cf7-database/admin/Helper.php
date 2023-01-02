<?php
if ( ! defined('ABSPATH') ) {
	exit('Direct\'s not allowed');
}
if ( ! function_exists('cf7d_checkNonce') ) {
	function cf7d_checkNonce() {
		$nonce = sanitize_text_field($_POST['nonce']);
		if ( ! wp_verify_nonce($nonce, 'cf7d-nonce') ) {
			wp_send_json_error(array( 'mess' => 'Nonce is invalid' ));
		}
	}
}
