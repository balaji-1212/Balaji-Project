<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}
// add View Database link to page install plugin
add_filter( 'plugin_action_links_' . CF7D_PLUGIN_BASENAME, 'wpcf7db_plugin_action_links', 10, 2 );
function wpcf7db_plugin_action_links( $links, $file ) {
	if ( $file != CF7D_PLUGIN_BASENAME ) {
		return $links;
	}

	if ( ! current_user_can( 'wpcf7_read_contact_forms' ) ) {
		return $links;
	}

	$view_database_link = wpcf7_link(
		menu_page_url( 'cf7-data', false ),
		__( 'View Database', 'cf7-database' )
	);

	array_unshift( $links, $view_database_link );

	return $links;
}

// add go pro link to page install plugin
add_filter( 'plugin_action_links_' . CF7D_PLUGIN_BASENAME, 'wpcf7db_plugin_action_links_gopro' );
function wpcf7db_plugin_action_links_gopro( $links ) {
	$links[] = '<a target="_blank" href="https://1.envato.market/Contact-Form-7-Database-GoPro" style="color: #43B854; font-weight: bold">' . __( 'Go Pro', 'cf7-database' ) . '</a>';
	return $links;
}
// add js, css
add_action( 'admin_enqueue_scripts', 'cf7d_custom_wp_admin_style' );
if ( ! function_exists( 'cf7d_custom_wp_admin_style' ) ) {
	function cf7d_custom_wp_admin_style( $hook_suffix ) {
		// Check page admin current for Language right to left.
		$check_page_cur  = false;
		$is_rtl          = apply_filters( 'cf7d_is_rtl', is_rtl() );
		$check_character = strrpos( $hook_suffix, 'page_cf7-data' );
		if ( $is_rtl == '1' && $check_character ) {
			$check_page_cur = true;
		}

		// $hook_suffix Check page admin current for Language left to right.
		if ( $hook_suffix === $GLOBALS['njt_cf7d_hook_suffix'] || $check_page_cur ) {
			// list formArr
			$forms        = get_posts(
				array(
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'offset'         => 0,
					'orderby'        => apply_filters( 'cf7-db-forms-orderby', 'ID' ),
					'order'          => apply_filters( 'cf7-db-forms-order', 'ASC' ),
					'post_type'      => 'wpcf7_contact_form',
				)
			);
			$list_formArr = array();
			foreach ( $forms as $k => $v ) {
				if ( ! empty( $v->ID ) ) {
					$list_formArr[] = array(
						'id_form'    => $v->ID,
						'title_form' => $v->post_title,
					);
				}
			}

						global $wpdb;
			$list_field_settingArr = array();

			foreach ( $list_formArr as $key_list_form => $list_form ) {
				$sql  = sprintf( 'SELECT `name` FROM `' . $wpdb->prefix . 'cf7_data_entry` WHERE cf7_id = %d GROUP BY `name`', $list_form['id_form'] );
				$data = $wpdb->get_results( $sql );

				$fields = array();
				foreach ( $data as $k => $v ) {
					$fields[ $v->name ] = $v->name;
				}

				$field_settings = get_option( 'cf7d_settings_field_' . $list_form['id_form'], array() );
				if ( $field_settings == '' ) {
					$field_settings = array();
				}

				if ( count( $field_settings ) == 0 ) { // no settings found
					foreach ( $fields as $k => $v ) {
						$show                                    = 1;
						$label                                   = $v;
						$id_form                                 = $list_form['id_form'];
						$list_field_settingArr[ $id_form ][ $k ] = array(
							'label' => $label,
							'show'  => $show,
						);
					}
				} else {
					foreach ( $field_settings as $k => $v ) {
						if ( isset( $fields[ $k ] ) ) {
							$show    = $field_settings[ $k ]['show'];
							$label   = $field_settings[ $k ]['label'];
							$id_form = $list_form['id_form'];

							$list_field_settingArr[ $id_form ][ $k ] = array(
								'label' => $label,
								'show'  => $show,
							);
							unset( $fields[ $k ] );
						}
					}
					if ( count( $fields ) > 0 ) {
						foreach ( $fields as $k => $v ) {
							$show    = 1;
							$label   = $v;
							$id_form = $list_form['id_form'];

							$list_field_settingArr[ $id_form ][ $k ] = array(
								'label' => $label,
								'show'  => $show,
							);
						}
					}
				}
			}
			if ( isset( $_GET['fid'] ) || ! empty( $_GET['fid'] ) ) {
				$fid = (int) sanitize_text_field( $_GET['fid'] );
			} else {
				$fid = $list_formArr[0]['id_form'];
			}

			$setting_nav_arr = get_option( 'cf7d_settings_nav_table' . $fid );
			$navDefault      = array(
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
			if ( $setting_nav_arr === false ) {
				$setting_nav_arr = $navDefault;
				add_option( 'cf7d_settings_nav_table' . $fid, cf7d_sanitize_arr( $setting_nav_arr ), '', 'no' );
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

			wp_enqueue_script( 'cf7d_apps_js', CF7D_PLUGIN_URL . '/admin/assets/dist/js/main.js', array(), '', true );
			wp_localize_script(
				'cf7d_apps_js',
				'njt_cfd_data',
				array(
					'home_url'              => home_url(),
					'cf7d_page_url'         => admin_url( 'admin.php?page=cf7-data&fid=' ),
					'list_formArr'          => $list_formArr,
					'list_field_settingArr' => $list_field_settingArr,
					'id_form_current'       => $fid,
					'page'                  => 1,
					'per_page'              => 15,
					'setting_nav_arr'       => $setting_nav_arr,
					'nonce'                 => wp_create_nonce( 'cf7d-nonce' ),
					'is_rtl'                => apply_filters( 'cf7d_is_rtl', is_rtl() ),
					'html_fields'           => apply_filters( 'cf7d_html_fields', array() ),
					'translate' 			=> getTranslation()
				)
			);
			// File css of ant design.
			// wp_register_style( 'cf7d_apps_css_ant', CF7D_PLUGIN_URL . '/admin/assets/css/ant/antd.min.css' );
			// wp_enqueue_style( 'cf7d_apps_css_ant' );

			// File css of apps.
			wp_register_style( 'cf7d_apps_css', CF7D_PLUGIN_URL . '/admin/assets/dist/css/main.css' );
			wp_enqueue_style( 'cf7d_apps_css' );

			// css for language right to left.
			if ( is_rtl() == '1' ) {
				// File support for language right to left.
				wp_register_style( 'cf7d_apps_css_su_rtl', CF7D_PLUGIN_URL . '/admin/assets/css/css_support_rtl.css' );
				wp_enqueue_style( 'cf7d_apps_css_su_rtl' );

				// File for language right to left.
				wp_style_add_data( 'cf7d_apps_css', 'rtl', 'replace' );

				// File for language right to left.
				wp_style_add_data( 'cf7d_apps_css_ant', 'rtl', 'replace' );
			}
		}

	}
}
// add admin menu page
add_action( 'admin_menu', 'cf7d_register_custom_submenu_page' );
if ( ! function_exists( 'cf7d_register_custom_submenu_page' ) ) {
	function cf7d_register_custom_submenu_page() {
		$GLOBALS['njt_cf7d_hook_suffix'] = add_submenu_page( 'wpcf7', 'Database', 'Database', apply_filters( 'cf7d_options_page_capabilities', 'manage_options' ), 'cf7-data', 'cf7d_custom_submenu_page_callback' );
	}
}

if ( ! function_exists( 'cf7d_custom_submenu_page_callback' ) ) {
	function cf7d_custom_submenu_page_callback() {
		?> 
			<div id="cf7d-apps"></div>
		<?php
	}
}
