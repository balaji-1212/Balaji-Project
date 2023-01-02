<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}

add_action( 'wp_ajax_cf7d_get_rows_data', 'cf7d_get_rows_data' );
if ( ! function_exists( 'cf7d_get_rows_data' ) ) {
	function cf7d_get_rows_data() {
		try {
			if ( isset( $_POST['fid'] ) &&
				isset( $_POST['page'] ) &&
				isset( $_POST['per_page'] )
			) {
				cf7d_checkNonce();
				global $wpdb;
				$fid                 = (int) $_POST['fid'];
				$page                = (int) $_POST['page'];
				$cf7d_entry_order_by = '`data_id` DESC';
				$items_per_page      = (int) $_POST['per_page'];

				$limit_query = '';
				if ( is_numeric( $items_per_page ) ) {
					$offset      = ( $page * $items_per_page ) - $items_per_page;
					$limit_query = "LIMIT $offset, $items_per_page";
				}

				$query = sprintf( 'SELECT * FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = %d AND data_id IN(SELECT * FROM (SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE 1 = 1 AND `cf7_id` = ' . $fid . ' GROUP BY `data_id` ORDER BY ' . $cf7d_entry_order_by . ' %s) temp_table) ORDER BY ' . $cf7d_entry_order_by, $fid, $limit_query );
				$data  = $wpdb->get_results( $query );

				$data_sorted = apply_filters( 'cf7d_ad_before_printing_data', cf7d_sortdata( $data ), $fid );
				$fields      = cf7d_get_db_fields( $fid );

				$total = $wpdb->get_results( 'SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = ' . (int) $fid . ' GROUP BY `data_id`' );
				$total = count( $total );

				$setting_nav_arr = get_option( 'cf7d_settings_nav_table' . $fid );

				$navDefault = array(
					'bordered'          => 1,
					'loading'           => 0,
					'title'             => 0,
					'colHeader'         => 1,
					'expandedRowRender' => 0,
					'checkbox'          => 1,
					'fixedHeader'       => 1,
					'hasData'           => 1,
					'ellipsis'          => 1,
					'footer'            => 1,
					'size'              => 'default',
					'tableScroll'       => 'fixedColumn',
					'tableLayout'       => 'unset',
					'paginationTop'     => 'topRight',
					'paginationBottom'  => 'bottomRight',
				);
				// Up data when choose form new.
				if ( $setting_nav_arr === false ) {
					$setting_nav_arr = $navDefault;
					add_option( 'cf7d_settings_nav_table' . $fid, $setting_nav_arr, '', 'no' );
				}

				if ( is_array( $setting_nav_arr ) === false ) {
					$setting_nav_arr = $navDefault;
				}

				// convert to number
				$pro_set_navArr = array( 'bordered', 'loading', 'title', 'colHeader', 'expandedRowRender', 'checkbox', 'fixedHeader', 'hasData', 'ellipsis', 'footer' );

				foreach ( $pro_set_navArr as $pro_set_nav ) {
					if ( isset( $setting_nav_arr[ $pro_set_nav ] ) ) {
						$setting_nav_arr[ $pro_set_nav ] = (int) $setting_nav_arr[ $pro_set_nav ];
					}
				}

				wp_send_json_success(
					array(
						'mess' => __( 'success', 'cf7-database' ),
						array(
							'fields'          => $fields,
							'data_sorted'     => $data_sorted,
							'total_row'       => $total,
							'items_per_page'  => $items_per_page,
							'page'            => $page,
							'setting_nav_arr' => $setting_nav_arr,
						),
					)
				);
			}
			wp_send_json_error( array( 'mess' => __( 'Opp! Something went wrong.', 'cf7-database' ) ) );
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception.', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error.', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}

add_action( 'wp_ajax_cf7d_filter_date', 'cf7d_filter_date' );
if ( ! function_exists( 'cf7d_filter_date' ) ) {
	function cf7d_filter_date() {
		try {
			if ( isset( $_POST['fid'] ) &&
				isset( $_POST['page'] ) &&
				isset( $_POST['per_page'] ) &&
				isset( $_POST['from_date'] ) &&
				isset( $_POST['to_date'] )
			) {
				global $wpdb;
				cf7d_checkNonce();
				$fid                 = (int) $_POST['fid'];
				$page                = (int) $_POST['page'];
				$cf7d_entry_order_by = '`data_id` DESC';
				$items_per_page      = (int) $_POST['per_page'];
				$search_form_date    = sanitize_text_field( $_POST['from_date'] );
				$search_to_date      = sanitize_text_field( $_POST['to_date'] );

				$limit_query = '';
				if ( is_numeric( $items_per_page ) ) {
					$offset      = ( $page * $items_per_page ) - $items_per_page;
					$limit_query = "LIMIT $offset, $items_per_page";
				}

				$search_form_date = new DateTime( $search_form_date );
				$search_form_date = $search_form_date->format( 'Y-m-d' );
				$search_to_date   = new DateTime( $search_to_date );
				$search_to_date   = $search_to_date->format( 'Y-m-d' );
				$query            = sprintf( 'SELECT * FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = %d AND data_id IN(SELECT * FROM (SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE 1 = 1 AND `cf7_id` = ' . $fid . " AND name='submit_time' AND CONVERT(value , DATE) >= " . "'" . $search_form_date . "'" . ' AND CONVERT(value, DATE) <= ' . "'" . $search_to_date . "'" . ' GROUP BY `data_id` ORDER BY ' . $cf7d_entry_order_by . ' %s) temp_table) ORDER BY ' . $cf7d_entry_order_by, $fid, $limit_query );
				$data             = $wpdb->get_results( $query );
				$data_sorted      = apply_filters( 'cf7d_ad_before_printing_data', cf7d_sortdata( $data ), $fid );
				$fields           = cf7d_get_db_fields( $fid );

				$total = $wpdb->get_results( 'SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = ' . (int) $fid . " AND name='submit_time' AND CONVERT(value , DATE) >= " . "'" . $search_form_date . "'" . ' AND CONVERT(value, DATE) <= ' . "'" . $search_to_date . "'" . ' GROUP BY `data_id`' );
				$total = count( $total );
				wp_send_json_success(
					array(
						'mess' => __( 'Filter date success', 'cf7-database' ),
						array(
							'fields'         => $fields,
							'data_sorted'    => $data_sorted,
							'items_per_page' => $items_per_page,
							'page'           => $page,
							'total_row'      => $total,
						),
					)
				);

			}
			wp_send_json_error( array( 'mess' => __( 'Opp! Something went wrong.', 'cf7-database' ) ) );
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception.', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error.', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}

add_action( 'wp_ajax_cf7d_search_type_something', 'cf7d_search_type_something' );
if ( ! function_exists( 'cf7d_search_type_something' ) ) {
	function cf7d_search_type_something() {
		try {
			if ( isset( $_POST['fid'] ) &&
				isset( $_POST['page'] ) &&
				isset( $_POST['per_page'] ) &&
				isset( $_POST['search_type'] )
			) {
				global $wpdb;
				cf7d_checkNonce();
				$fid  = (int) $_POST['fid'];
				$page = (int) $_POST['page'];

				$cf7d_entry_order_by = '`data_id` DESC';
				$items_per_page      = (int) $_POST['per_page'];
				$search              = sanitize_text_field( addslashes( $_POST['search_type'] ) );

				$limit_query = '';
				if ( is_numeric( $items_per_page ) ) {
					$offset      = ( $page * $items_per_page ) - $items_per_page;
					$limit_query = "LIMIT $offset, $items_per_page";
				}
				$query = sprintf( 'SELECT * FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = %d AND data_id IN(SELECT * FROM (SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE 1 = 1 AND `cf7_id` = ' . $fid . ' ' . ( ( ! empty( $search ) ) ? "AND `value` LIKE '%%" . $search . "%%'" : '' ) . ' GROUP BY `data_id` ORDER BY ' . $cf7d_entry_order_by . ' %s) temp_table) ORDER BY ' . $cf7d_entry_order_by, $fid, $limit_query );
				$data  = $wpdb->get_results( $query );

				$data_sorted = apply_filters( 'cf7d_ad_before_printing_data', cf7d_sortdata( $data ), $fid );
				$fields      = cf7d_get_db_fields( $fid );

				$total = $wpdb->get_results( 'SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = ' . (int) $fid . ' ' . ( ( ! empty( $search ) ) ? "AND `value` LIKE '%%" . $search . "%%'" : '' ) . ' GROUP BY `data_id`' );
				$total = count( $total );

				wp_send_json_success(
					array(
						'mess' => __( 'Search type success', 'cf7-database' ),
						array(
							'fields'         => $fields,
							'data_sorted'    => $data_sorted,
							'total_row'      => $total,
							'items_per_page' => $items_per_page,
							'page'           => $page,
						),
					)
				);
			}
			wp_send_json_error( array( 'mess' => __( 'Opp! Something went wrong.', 'cf7-database' ) ) );
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception.', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}

add_action( 'wp_ajax_cf7d_delete_row', 'cf7d_delete_row' );
if ( ! function_exists( 'cf7d_delete_row' ) ) {
	function cf7d_delete_row() {
		try {
			if ( isset( $_POST['del_id'] )
			) {
								cf7d_checkNonce();

				global $wpdb;
				$del_id = cf7d_sanitize_arr( json_decode( stripslashes( $_POST['del_id'] ), true ) );
				$del_id = implode( ',', array_map( 'intval', $del_id ) );
				$wpdb->query( "DELETE FROM {$wpdb->prefix}cf7_data_entry WHERE data_id IN($del_id)" );
				$wpdb->query( "DELETE FROM {$wpdb->prefix}cf7_data WHERE id IN($del_id)" );
				wp_send_json_success( array( 'mess' => __( 'Delete success', 'cf7-database' ) ) );
				if ( $check_data && $check_data_entry ) {
					wp_send_json_success( array( 'mess' => __( 'Delete success', 'cf7-database' ) ) );
				} else {
					wp_send_json_error( array( 'mess' => __( 'Opp! Something went wrong.', 'cf7-database' ) ) );
				}
			}
			wp_send_json_error( array( 'mess' => __( 'Opp! Something went wrong.', 'cf7-database' ) ) );
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}

add_action( 'wp_ajax_cf7d_edit_row', 'cf7d_edit_row' );
if ( ! function_exists( 'cf7d_edit_row' ) ) {
	function cf7d_edit_row() {
		try {
			global $wpdb;
			if ( isset( $_POST['fid'] ) &&
				isset( $_POST['rid'] ) &&
				isset( $_POST['field'] )
			) {
				cf7d_checkNonce();
				$fid = (int) $_POST['fid'];
				$rid = (int) $_POST['rid'];

				$field = cf7d_sanitize_arr( json_decode( stripslashes( $_POST['field'] ), true ) );
				foreach ( $field as $key => $value ) {
					$check = $wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'cf7_data_entry SET `value` = %s WHERE `name` = %s AND `data_id` = %d', cf7d_sanitize_arr( $value ), cf7d_sanitize_arr( $key ), $rid ) );
				}
				wp_send_json_success( array( 'mess' => __( 'Edit row success', 'cf7-database' ) ) );
			}
			wp_send_json_error( array( 'mess' => __( 'Opp! Something went wrong.', 'cf7-database' ) ) );
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}

add_action( 'wp_ajax_cf7d_edit_setting_table', 'cf7d_edit_setting_table' );
if ( ! function_exists( 'cf7d_edit_setting_table' ) ) {
	function cf7d_edit_setting_table() {
		try {
			global $wpdb;
			if ( isset( $_POST['fid'] ) &&
				isset( $_POST['setting_nav_arr'] )
			) {
				cf7d_checkNonce();
				$fid             = (int) $_POST['fid'];
				$setting_nav_arr = json_decode( stripslashes( $_POST['setting_nav_arr'] ), true );
				add_option( 'cf7d_settings_nav_table' . $fid, cf7d_sanitize_arr( $setting_nav_arr ), '', 'no' );
				update_option( 'cf7d_settings_nav_table' . $fid, cf7d_sanitize_arr( $setting_nav_arr ) );
				wp_send_json_success(
					array(
						'mess' => __( 'Settings saved.', 'cf7-database' ),
					)
				);
			}
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}

add_action( 'wp_ajax_cf7d_edit_setting', 'cf7d_edit_setting' );
if ( ! function_exists( 'cf7d_edit_setting' ) ) {
	function cf7d_edit_setting() {
		try {
			if ( isset( $_POST['field'] ) &&
				isset( $_POST['fid'] ) &&
				isset( $_POST['page'] ) &&
				isset( $_POST['per_page'] )
			) {
				cf7d_checkNonce();
				// Up data edit setting
				$fid            = (int) $_POST['fid'];
				$field          = json_decode( stripslashes( $_POST['field'] ), true );
				$checkAddOption = add_option( 'cf7d_settings_field_' . $fid, cf7d_sanitize_arr( $field ), '', 'no' );
				$checkUpOption  = update_option( 'cf7d_settings_field_' . $fid, cf7d_sanitize_arr( $field ) );

				// Get Data
				if ( $checkUpOption || $checkAddOption ) {
					global $wpdb;

					$fid                 = (int) $_POST['fid'];
					$page                = (int) $_POST['page'];
					$cf7d_entry_order_by = '`data_id` DESC';
					$items_per_page      = (int) $_POST['per_page'];

					$limit_query = '';
					if ( is_numeric( $items_per_page ) ) {
						$offset      = ( $page * $items_per_page ) - $items_per_page;
						$limit_query = "LIMIT $offset, $items_per_page";
					}

					$query = sprintf( 'SELECT * FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = %d AND data_id IN(SELECT * FROM (SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE 1 = 1 AND `cf7_id` = ' . $fid . ' GROUP BY `data_id` ORDER BY ' . $cf7d_entry_order_by . ' %s) temp_table) ORDER BY ' . $cf7d_entry_order_by, $fid, $limit_query );
					$data  = $wpdb->get_results( $query );

					$data_sorted = apply_filters( 'cf7d_ad_before_printing_data', cf7d_sortdata( $data ), $fid );
					$fields      = cf7d_get_db_fields( $fid );

					$total = $wpdb->get_results( 'SELECT data_id FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE `cf7_id` = ' . (int) $fid . ' GROUP BY `data_id`' );
					$total = count( $total );

					wp_send_json_success(
						array(
							'mess' => __( 'Settings saved.', 'cf7-database' ),
							array(
								'fields'         => $fields,
								'data_sorted'    => $data_sorted,
								'total'          => $total,
								'items_per_page' => $items_per_page,
								'page'           => $page,
							),
						)
					);

				}

				wp_send_json_error( array( 'mess' => __( 'Error save data.', 'cf7-database' ) ) );
			}
		} catch ( \Exception $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error exception', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		} catch ( \Error $ex ) {
			wp_send_json_error(
				array(
					'mess' => __( 'Error', 'cf7-database' ),
					array(
						'error' => $ex,
					),
				)
			);
		}
	}
}
