<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}

add_filter( 'cf7d_entry_value', 'filter_cf7d_entry_value', 10, 2 );
if ( ! function_exists( 'filter_cf7d_entry_value' ) ) {
	function filter_cf7d_entry_value( $value, $name ) {
		$value = preg_replace( "/\r?\n|\r/", '', $value );
		if( esc_url_raw( $value ) === $value ) {
			$value = '<a href="'. esc_url( $value ) .'" target="_blank" rel="nofollow noopener noreferrer">'. $value .'</a>';
		}
		return $value;
	}
}
add_filter( 'cf7d_ad_before_printing_data', 'filter_cf7d_ad_before_printing_data', 10, 2 );
if ( ! function_exists( 'filter_cf7d_ad_before_printing_data' ) ) {
	function filter_cf7d_ad_before_printing_data( $data, $fid ) {
		foreach ( $data as $k => $v ) {
			$data[ $k ]['data_id'] = $k;
		}
		return $data;
	}
}
add_filter( 'cf7d_admin_fields', 'filter_cf7d_admin_fields', 10, 2 );
if ( ! function_exists( 'filter_cf7d_admin_fields' ) ) {
	function filter_cf7d_admin_fields( $fields, $fid ) {
		return array( 'data_id' => 'ID' ) + $fields;
	}
}
