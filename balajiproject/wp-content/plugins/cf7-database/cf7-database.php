<?php
/*
 * Plugin Name: Contact Form 7 Database Lite
 * Plugin URI: http://ninjateam.org
 * Description: Contact Form 7 Database is a plugin for WordPress allows you save all submitted from contact form 7 to database and display in Contact > Database menu
 * Version: 3.0.5
 * Author: NinjaTeam
 * Author URI: http://ninjateam.org
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}

if ( function_exists( 'wpcf7db_plugin_init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/Fallback.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

if ( ! defined( 'CF7D_PREFIX' ) ) {
	define( 'CF7D_PREFIX', 'cf7-database' );
}
if ( ! defined( 'CF7D_FILE' ) ) {
	define( 'CF7D_FILE', __FILE__ );
}
if ( ! defined( 'CF7D_PLUGIN_DIR' ) ) {
	define( 'CF7D_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'CF7D_PLUGIN_URL' ) ) {
	define( 'CF7D_PLUGIN_URL', plugins_url( '', __FILE__ ) );
}
if ( ! defined( 'CF7D_PLUGIN_PATH' ) ) {
	define( 'CF7D_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CF7D_PLUGIN_BASENAME' ) ) {
	define( 'CF7D_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! function_exists( 'wpcf7db_plugin_init' ) ) {
	function wpcf7db_plugin_init() {
		require_once CF7D_PLUGIN_DIR . '/functions.php';
		require_once CF7D_PLUGIN_DIR . '/frontend/init.php';
		require_once CF7D_PLUGIN_DIR . '/frontend/save-files.php';

		require_once CF7D_PLUGIN_DIR . '/admin/I18n.php';
		require_once CF7D_PLUGIN_DIR . '/admin/init.php';
		require_once CF7D_PLUGIN_DIR . '/admin/Helper.php';
		require_once CF7D_PLUGIN_DIR . '/admin/Ajax.php';
		require_once CF7D_PLUGIN_DIR . '/admin/unique-id.php';
	}
}
add_action( 'plugins_loaded', 'wpcf7db_plugin_init' );

/*
 * Creating tables when plugin is actived
 */

if ( ! function_exists( 'cf7d_table_func' ) ) {
	function cf7d_table_func() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$cf7d_table      = $wpdb->prefix . 'cf7_data';
		if ( $wpdb->get_var( "show tables like '$cf7d_table'" ) != $cf7d_table ) {
			$sql = 'CREATE TABLE ' . $cf7d_table . ' (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `created` timestamp NOT NULL,
            UNIQUE KEY id (id)
            ) ' . $charset_collate . ';';
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		$cf7d_table_entry = $wpdb->prefix . 'cf7_data_entry';
		if ( $wpdb->get_var( "show tables like '$cf7d_table_entry'" ) != $cf7d_table_entry ) {
			$sql = 'CREATE TABLE ' . $cf7d_table_entry . ' (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cf7_id` int(11) NOT NULL,
            `data_id` int(11) NOT NULL,
            `name` varchar(250),
            `value` text,
            UNIQUE KEY id (id)
            ) ' . $charset_collate . ';';
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		} else {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			maybe_convert_table_to_utf8mb4( $cf7d_table_entry );
			$sql = 'ALTER TABLE ' . $cf7d_table_entry . ' change name name VARCHAR(250) character set utf8, change value value text character set utf8;';
			$wpdb->query( $sql );

			// remove fields cf7mls_step-1... in database for version old.
			// cf7mls_step-1, cf7mls_step-2... by plugin contact form 7 multi step pro.
			$wpdb->query( "DELETE  FROM $cf7d_table_entry WHERE `name` LIKE 'cf7mls_step-%'" );
		}
	}
}

register_activation_hook( CF7D_FILE, 'cf7d_table_func' );
