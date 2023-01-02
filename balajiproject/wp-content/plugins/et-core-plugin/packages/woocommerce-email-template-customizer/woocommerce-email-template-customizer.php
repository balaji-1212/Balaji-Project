<?php
/**
 *Plugin Name: WooCommerce Email Template Customizer Premium
 *Plugin URI: https://villatheme.com/extensions/woocommerce-email-template-customizer/
 *Description: Make your WooCommerce emails become professional.
 *Version: 1.0.0.7
 *Author: VillaTheme
 *Author URI: https://villatheme.com
 *Text Domain: viwec-email-template-customizer
 *Domain Path: /languages
 *Copyright 2020 VillaTheme.com. All rights reserved.
 *Tested up to: 5.6
 *WC tested up to: 4.7
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce_Email_Template_Customizer' ) ) {
	
	class WooCommerce_Email_Template_Customizer {

		public $err_message;
		public $wp_version_require = '5.0';
		public $wc_version_require = '3.6';
		public $php_version_require = '7.0';

		public function __construct() {
			$this->condition_init();
			add_action( 'admin_notices', array( $this, 'admin_notices_condition' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_actions_link' ) );
		}

		public function condition_init() {
			global $wp_version;
			if ( version_compare( $this->wp_version_require, $wp_version, '>' ) ) {
				$this->err_message = esc_html__( 'Please upgrade WordPress version to', 'xstore-core' ) . ' ' . $this->wp_version_require . ' ' . esc_html__( 'to use', 'xstore-core' );

				return;
			}

			if ( version_compare( $this->php_version_require, phpversion(), '>' ) ) {
				$this->err_message = esc_html__( 'Please upgrade php version to', 'xstore-core' ) . ' ' . $this->php_version_require . ' ' . esc_html__( 'to use', 'xstore-core' );

				return;
			}

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				$this->err_message = esc_html__( 'Please install and activate WooCommerce to use', 'xstore-core' );
				unset( $_GET['activate'] );
				// deactivate_plugins( plugin_basename( __FILE__ ) );

				return;
			}

			$wc_version = get_option( 'woocommerce_version' );
			if ( version_compare( $this->wc_version_require, $wc_version, '>' ) ) {
				$this->err_message = esc_html__( 'Please upgrade WooCommerce version to', 'xstore-core' ) . ' ' . $this->wc_version_require . ' ' . esc_html__( 'to use', 'xstore-core' );

				return;
			}

			$this->define();

			if ( is_file( VIWEC_INCLUDES . 'init.php' ) ) {
				require_once VIWEC_INCLUDES . 'init.php';
//				register_activation_hook( __FILE__, array( $this, 'viwec_activate' ) );
				if ( !get_option('etheme_viwec_activate', false) ) {
					add_action( 'init', array( $this, 'viwec_activate' ) );
				}
			}
		}

		public function define() {
			$plugin_url = plugin_dir_url( __FILE__ );
			
			define( 'VIWEC_VER', '1.0.0.7' );
			define( 'VIWEC_NAME', 'Built-in Email Builder' );

			define( 'VIWEC_SLUG', 'woocommerce-email-template-customizer' );
			define( 'VIWEC_DIR', plugin_dir_path( __FILE__ ) );
			define( 'VIWEC_INCLUDES', VIWEC_DIR . "includes" . DIRECTORY_SEPARATOR );
			define( 'VIWEC_SUPPORT', VIWEC_INCLUDES . "support" . DIRECTORY_SEPARATOR );
			define( 'VIWEC_TEMPLATES', VIWEC_INCLUDES . "templates" . DIRECTORY_SEPARATOR );
			define( 'VIWEC_LANGUAGES', VIWEC_DIR . "languages" . DIRECTORY_SEPARATOR );

			define( 'VIWEC_CSS', $plugin_url . "assets/css/" );
			define( 'VIWEC_JS', $plugin_url . "assets/js/" );
			define( 'VIWEC_IMAGES', $plugin_url . "assets/img/" );

			global $wpdb;
			define( 'VIWEC_VIEW_PRODUCT_TB', $wpdb->prefix . 'viwec_clicked' );
		}

		public function viwec_activate() {
			$check_exist = get_posts( [ 'post_type' => 'viwec_template', 'numberposts' => 1 ] );

			if ( empty( $check_exist ) ) {
				$site_title      = get_option( 'blogname' );
				$default_subject = \VIWEC\INC\Email_Samples::default_subject();
				$templates       = \VIWEC\INC\Email_Samples::sample_templates();

				if ( empty( $templates ) || ! is_array( $templates ) ) {
					return;
				}

				foreach ( $templates as $key => $template ) {
					$args     = [
						'post_title'  => $default_subject[ $key ] ? str_replace( '{site_title}', $site_title, $default_subject[ $key ] ) : '',
						'post_status' => 'publish',
						'post_type'   => 'viwec_template',
					];
					$post_id  = wp_insert_post( $args );
					$template = $template['basic']['data'];
					$template = str_replace( '\\', '\\\\', $template );
					update_post_meta( $post_id, 'viwec_settings_type', $key );
					update_post_meta( $post_id, 'viwec_email_structure', $template );
				}
			}

			$this->create_table();
			
			update_option('etheme_viwec_activate', 'done');
		}

		public function create_table() {
			global $wpdb;
			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}

			$table_name = VIWEC_VIEW_PRODUCT_TB;

			$query = "CREATE TABLE IF NOT EXISTS {$table_name} (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `clicked_date` int(11) NOT NULL,
                             `product` int(11) NOT NULL,
                             PRIMARY KEY  (`id`)
                             ) {$collate}";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $query );
		}

		public function admin_notices_condition() {
			if ( $this->err_message ) {
				?>
                <div id="message" class="error">
                    <p><?php echo esc_html( $this->err_message ) . ' ' . esc_html__('Built-in Email Builder', 'xstore-core'); ?></p>
                </div>
				<?php
			}
		}

		public function plugin_actions_link( $links ) {
			if ( ! $this->err_message ) {
				$settings_link = '<a href="' . admin_url( 'edit.php?post_type=viwec_template' ) . '">' . esc_html__( 'Settings', 'xstore-core' ) . '</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
		}
	}

	new WooCommerce_Email_Template_Customizer();
}