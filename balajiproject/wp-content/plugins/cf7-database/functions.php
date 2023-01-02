<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}
/*
 * Support functions
 */
if ( ! function_exists( 'cf7d_get_posted_data' ) ) {
	function cf7d_get_posted_data( $cf7 ) {
		if ( ! isset( $cf7->posted_data ) && class_exists( 'WPCF7_Submission' ) ) {
			// Contact Form 7 version 3.9 removed $cf7->posted_data and now
			// we have to retrieve it from an API
			$submission = WPCF7_Submission::get_instance();
			if ( $submission ) {
				$data                      = array();
				$data['title']             = $cf7->title();
				$data['posted_data']       = $submission->get_posted_data();
				$data['uploaded_files']    = $submission->uploaded_files();
				$data['WPCF7_ContactForm'] = $cf7;
				$cf7                       = (object) $data;
			}
		}
		return $cf7;
	}
}

if ( ! function_exists( 'cf7d_no_save_fields' ) ) {
	function cf7d_no_save_fields() {
		$cf7d_no_save_fields = array( '_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag', '_wpcf7_is_ajax_call' );
		return apply_filters( 'cf7d_no_save_fields', $cf7d_no_save_fields );
	}
}
if ( ! function_exists( 'cf7d_add_more_fields' ) ) {
	function cf7d_add_more_fields( $cf7 ) {
		$submission = WPCF7_Submission::get_instance();
		// time
		// $cf7->posted_data['submit_time'] = date_i18n('Y-m-d H:i:s');
		$cf7->posted_data['submit_time'] = date_i18n( 'Y-m-d H:i:s', $submission->get_meta( 'timestamp' ) );
		// ip
		$cf7->posted_data['submit_ip'] = ( isset( $_SERVER['X_FORWARDED_FOR'] ) ) ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		// user id
		// $cf7->posted_data['submit_user_id'] = 0;
		// if (function_exists('is_user_logged_in') && is_user_logged_in()) {
		// $current_user = wp_get_current_user(); // WP_User
		// $cf7->posted_data['submit_user_id'] = $current_user->ID;
		// }
		return $cf7;
	}
}
/*
 * $data: rows from database
 * $fid: form id
 */
if ( ! function_exists( 'cf7d_sortdata' ) ) {
	function cf7d_sortdata( $data ) {
		$data_sorted = array();
		foreach ( $data as $k => $v ) {
			if ( ! isset( $data_sorted[ $v->data_id ] ) ) {
				$data_sorted[ $v->data_id ] = array();
			}
			$data_sorted[ $v->data_id ][ $v->name ] = apply_filters( 'cf7d_entry_value', $v->value, $v->name );
		}

		return $data_sorted;
	}
}
if ( ! function_exists( 'cf7d_get_db_fields' ) ) {
	function cf7d_get_db_fields( $fid, $filter = true ) {
		global $wpdb;
		$sql  = sprintf( 'SELECT `name` FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE cf7_id = %d GROUP BY `name`', $fid );
		$data = $wpdb->get_results( $sql );

		$fields = array();
		foreach ( $data as $k => $v ) {
			$fields[ $v->name ] = $v->name;
		}
		if ( $fields ) {
			$fields = apply_filters( 'cf7d_admin_fields', $fields, $fid );
		}
		return $fields;
	}
}
if ( ! function_exists( 'cf7d_get_entrys' ) ) {
	function cf7d_get_entrys( $fid, $entry_ids = '', $cf7d_entry_order_by = '' ) {
		global $wpdb;
		if ( empty( $cf7d_entry_order_by ) ) {
			$cf7d_entry_order_by = '`data_id` DESC';
		}
		$query = sprintf( 'SELECT * FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = %d AND data_id IN(SELECT * FROM (SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE 1 = 1 ' . ( ( ! empty( $entry_ids ) ) ? 'AND `data_id` IN (' . $entry_ids . ')' : '' ) . ' GROUP BY `data_id` ORDER BY ' . $cf7d_entry_order_by . ') temp_table) ORDER BY ' . $cf7d_entry_order_by, $fid );
		$data  = $wpdb->get_results( $query );
		return $data;
	}
}
if ( ! function_exists( 'cf7d_upload_folder' ) ) {
	function cf7d_upload_folder() {
		return apply_filters( 'cf7d_upload_folder', 'cf7-database' );
	}
}
if ( ! function_exists( 'cf7d_sanitize_arr' ) ) {
	function cf7d_sanitize_arr( $arr ) {
		return is_array( $arr ) ? array_map( 'cf7d_sanitize_arr', $arr ) : sanitize_text_field( $arr );
	}
}

add_filter( 'cf7d_admin_fields', 'cf7d_admin_fields_cb', 10, 2 );
if ( ! function_exists( 'cf7d_admin_fields_cb' ) ) {
	function cf7d_admin_fields_cb( $fields, $fid ) {
		$return         = array();
		$field_settings = get_option( 'cf7d_settings_field_' . $fid, array() );
		if ( $field_settings == '' ) {
			$field_settings = array();
		}
		if ( count( $field_settings ) == 0 ) { // no settings found
			$return = $fields;
		} else {
			foreach ( $field_settings as $k => $v ) {
				if ( isset( $fields[ $k ] ) ) {
					$show = $field_settings[ $k ]['show'];
					if ( $show == 1 ) {
						$label        = $field_settings[ $k ]['label'];
						$return[ $k ] = $label;
					}
					unset( $fields[ $k ] );
				}
			}
			if ( count( $fields ) > 0 ) {
				foreach ( $fields as $k => $v ) {
					$return[ $k ] = $v;
				}
			}
		}
		return $return;
	}
}

add_filter( 'cf7d_no_save_fields', 'cf7d_no_save_fields_hook', 10, 1 );
if ( ! function_exists( 'cf7d_no_save_fields_hook' ) ) {
	function cf7d_no_save_fields_hook( $cf7d_no_save_fields ) {
		array_push( $cf7d_no_save_fields, 'submit_ip' );
		return $cf7d_no_save_fields;
	}
}
