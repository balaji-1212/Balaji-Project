<?php
/**
 * Description
 *
 * @package    wpml.php
 * @since      8.1.1
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

add_filter( 'wpml_decode_custom_field', function( $value, $key ) {
	if ( 'viwec_email_structure' === $key ) {
		$value = json_decode( html_entity_decode( $value ) );
	}
	
	return $value;
}, 10, 2 );

add_filter( 'wpml_encode_custom_field', function( $value, $key ) {
	if ( 'viwec_email_structure' === $key ) {
		$value = htmlentities( wp_json_encode( $value ) );
	}
	return $value;
}, 10, 2 );