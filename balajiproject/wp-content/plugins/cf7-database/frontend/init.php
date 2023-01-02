<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}
/*
 * Installing data to database
 */
add_action( 'wpcf7_before_send_mail', 'cf7d_before_send_email', 10, 3 );
if ( ! function_exists( 'cf7d_before_send_email' ) ) {
	function cf7d_before_send_email( $contact_form, &$abort, $submission ) {
		global $wpdb;
		$service = WPCF7_Stripe::get_instance();
		if ( $service->is_active() ) {
			if ( empty( $submission->payment_intent ) ) {
				return;
			}
		}
		do_action( 'cf7d_before_insert_db', $contact_form );

		$cf7_id       = $contact_form->id();
		$contact_form = cf7d_get_posted_data( $contact_form );

		// for database installion
		$contact_form = cf7d_add_more_fields( $contact_form );

		// Modify $contact_form
		$contact_form = apply_filters( 'cf7d_modify_form_before_insert_data', $contact_form );
		// Type's $contact_form->posted_data is array
		$contact_form->posted_data = apply_filters( 'cf7d_posted_data', $contact_form->posted_data );
		$time                      = date( 'Y-m-d H:i:s' );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'cf7_data(`created`) VALUES (%s)', $time ) );
		$data_id = $wpdb->insert_id;
		// install to database
		$cf7d_no_save_fields = cf7d_no_save_fields();
		foreach ( $contact_form->posted_data as $k => $v ) {
			if ( in_array( $k, $cf7d_no_save_fields ) ) {
				continue;
			} else {
				if ( is_array( $v ) ) {
					$v = implode( "\n", $v );
				}
				if ( ! empty( $v ) ) {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'cf7_data_entry(`cf7_id`, `data_id`, `name`, `value`) VALUES (%d,%d,%s,%s)', $cf7_id, $data_id, $k, $v ) );
				}
			}
		}
		do_action( 'cf7d_after_insert_db', $contact_form, $cf7_id, $data_id );
	}
}
