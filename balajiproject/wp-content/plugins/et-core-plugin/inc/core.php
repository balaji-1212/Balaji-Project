<?php
namespace ETC\Inc;

use ETC\Inc\i18n;
use ETC\Core\Controller;
use ETC\Core\Model;
use ETC\Inc\Router;
use ETC\Inc\Compatibility;

/**
 * The main plugin class
 *
 * @since      1.4.4
 * @version    1.0.1
 * @package    ETC
 * @subpackage ETC/includes
 */
class Core {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.4.4
	 */
	const PLUGIN_ID         =	'xstore-core';

	/**
	 * The name identifier of this plugin.
	 *
	 * @since    1.4.4
	 */
	const PLUGIN_NAME       =	'XStore Core';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.4.4
	 */

	const PLUGIN_VERSION    =	ET_CORE_VERSION;

	/**
	 * Holds instance of this class.
	 *
	 * @since    1.4.4
	 * @access   private
	 * @var      ETC    $instance    Instance of this class.
	 */
	private static $instance;

	/**
	 * Main plugin path.
	 *
	 * @since    1.4.4
	 * @access   private
	 * @var      string    $plugin_path    Main path.
	 */
	private static $plugin_path;

	/**
	 * Absolute plugin url.
	 *
	 * @since    1.4.4
	 * @access   private
	 * @var      string    $plugin_url    Main path.
	 */
	private static $plugin_url;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.4.4
	 */
	public function __construct() {
		// Plugin Id and Path
		self::$plugin_path = plugin_dir_path( dirname( __FILE__ ) );
		self::$plugin_url  = plugin_dir_url( dirname( __FILE__ ) );
		// Load depency
		$this->autoload_dependencies();
		$this->set_locale();

		// Import base funcs
		include_once ET_CORE_DIR . 'app/traits/base.php';

		include_once ET_CORE_DIR . 'packages/cmb2/init.php';
		
		include_once ET_CORE_DIR . 'packages/cmb2-tabs/plugin.php';
		
		if ( get_option('etheme_built_in_email_builder', false) ) {
			
			if ( !function_exists('is_plugin_active')) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			
			if ( ! is_plugin_active( 'woocommerce-email-template-customizer/woocommerce-email-template-customizer.php' ) ) {
				include_once ET_CORE_DIR . 'packages/woocommerce-email-template-customizer/woocommerce-email-template-customizer.php';
			}
			
		}

		add_action( 'admin_notices', array( $this, 'required_theme_notice' ), 50 );
		add_action( 'wp_body_open', array( $this, 'required_theme_notice_frontend' ), 50 );

		// Load Router
		$router = new Router;

		/**
		 * Load plugin testimonials.
		 * Do it to prevent errors with old 8theme themes
		 * @since 1.1
		 */
		add_action( 'init', array( $this, 'third_party' ) );

		// load xstore wishlist and xstore compare features
        $this->load_built_in_features();

		add_action( 'after_setup_theme', array( $this, 'load_swatches' ) , 999 );

		add_filter('pre_set_site_transient_update_plugins', array( $this, 'check_for_plugin_update' ) );

		// Load models and controllers
		$this->controllers  = $this->get_all_controllers();
		$this->models 		= $this->get_all_models();
	}

    /**
     * Load built-in theme features
     * @since 4.3.9
     */
	public function load_built_in_features() {

        // Load wishlist
        include ET_CORE_DIR . 'packages/xstore-wishlist/xstore-wishlist.php';

        // Load compare
        include ET_CORE_DIR . 'packages/xstore-compare/xstore-compare.php';
    }

	/**
	 * Get plugin's absolute path.
	 *
	 * @since    1.4.4
	 */
	public static function get_plugin_path() {
		return isset( self::$plugin_path ) ? self::$plugin_path : plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * Get plugin's absolute url.
	 *
	 * @since    1.4.4
	 */
	public static function get_plugin_url() {
		return isset( self::$plugin_url ) ? self::$plugin_url : plugin_dir_url( dirname( __FILE__ ) );
	}

	/**
	 * Method responsible to call all the dependencies
	 *
	 * @since 1.4.4
	 */
	protected function autoload_dependencies() {
		spl_autoload_register( array( $this, 'load' ) );
	}

	/**
	 * Loads all Plugin dependencies.
	 *
	 * @param string $class Class need to be loaded.
	 * @since    1.4.4
	 */
	public function load( $class ) {
		$parts = explode( '\\', $class );

		// Run this autoloader for classes related to this plugin only.
		if ( 'ETC' !== $parts[0] ) {
			return;
		}

		// Remove 'ETC' from parts.
		array_shift( $parts );

		$parts = array_map(
			function ( $part ) {
				return str_replace( '_', '-', strtolower( $part ) );
			}, $parts
		);

		$class_file_name = '/' . array_pop( $parts ) . '.php';
		$file_path = self::get_plugin_path() . implode( '/', $parts ) . $class_file_name;

		if ( \file_exists( $file_path ) ) {
			require_once( $file_path );
		}

		$trait_file_name = '/' . array_pop( $parts ) . '.php';

		$file_path = self::get_plugin_path() . implode( '/', $parts ) . $trait_file_name;

		if ( \file_exists( $file_path ) ) {
			require_once( $file_path );
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.4.4
	 */
	private function set_locale() {
		$etc_i18n = new i18n;
		$etc_i18n->set_domain( self::PLUGIN_ID );

		add_action( 'plugins_loaded', array( $etc_i18n, 'load_plugin_textdomain' ) );
	}

	/**
	 * Returns all controller objects used for current requests
	 *
	 * @since    1.4.4
	 * @return object
	 */
	private function get_all_controllers() {
		return (object) Controller::get_all_objects();
	}

	/**
	 * Returns all model objecs used for current requests
	 *
	 * @since   1.4.4
	 * @return object
	 */
	private function get_all_models() {
		return (object) Model::get_all_objects();
	}

	/**
	 * Load plugin testimonials.
	 * Do it to prevent errors with old 8theme themes
	 * @since 1.1
	 * @version 1.0.1
	 * @log 1.0.1
	 * ADDED: Composer autoloader for user Authorize
	 */
	public function third_party() {
		if ( get_theme_mod( 'testimonials_type', true ) && ! class_exists('Woothemes_Testimonials') ) {
			include ET_CORE_DIR . 'packages/testimonials/woothemes-testimonials.php';
		}

		if ( ! class_exists( 'TwitterOAuth' ) ) {
			include ET_CORE_DIR . 'packages/twitteroauth/twitteroauth.php';
		}
		
		include ET_CORE_DIR . 'packages/sales-booster/sales-booster.php';

		// ! Composer autoloader for user Authorize
		include ET_CORE_DIR . 'packages/vendor/autoload.php';
	}


	/**
	 * Load plugin st-woo-swatches.
	 * 
	 * @since 1.1
	 */
	function load_swatches(){
		// @todo check without AMP
//		if ( ! class_exists( 'Woocommerce' ) || ! function_exists( 'etheme_get_option' ) || ! etheme_get_option( 'enable_swatch', 1 ) ) {
//			return;
//		}
		if ( ! class_exists( 'Woocommerce' ) || ! get_theme_mod( 'enable_swatch', 1 ) ) {
			return;
		}

		include ET_CORE_DIR . 'packages/st-woo-swatches/st-woo-swatches.php';
	}

	/**
	 * ! Notice "Theme version"
	 * @since 1.1
	 */
	function required_theme_notice(){

		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}

		if ( get_template_directory() !== get_stylesheet_directory() ) {
	    	$theme = wp_get_theme( 'xstore' );
	    } else {
	    	$theme = wp_get_theme();
	    }

	    if (  $theme->name == ('XStore') &&  version_compare( $theme->version, ET_CORE_THEME_MIN_VERSION, '<' ) ) {
	    	$video = '<a class="et-button" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank" style="color: white!important; text-decoration: none"><span class="dashicons dashicons-video-alt3" style="var(--et_admin_main-color, #A4004F);"></span> Video tutorial</a>';

	      	echo '
				<div class="et-message et-warning">
					XStore Core plugin requires the following theme: <strong>XStore</strong> to be updated up to <strong>' . ET_CORE_THEME_MIN_VERSION . ' version.</strong> You can install the updated version of XStore theme: <ul>
						<li>1) via <a href="'.admin_url('update-core.php').'">Dashboard</a> > Updates > click Check again button > update theme</li>
						<li>2) via FTP using archive from <a href="https://www.8theme.com/downloads" target="_blank">Downloads</a></li>
						<li>3) via FTP using archive from the full theme package downloaded from <a href="https://themeforest.net/" target="_blank">ThemeForest</a></li>
						<li>4) via <a href="https://envato.com/market-plugin/" target="_blank">Envato Market</a> WordPress Plugin</li>
						<li>5) via <a href="https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/" target="_blank">Easy Theme and Plugin Upgrades</a> WordPress Plugin</li>
						<li>6) Don\'t Forget To Clear <strong style="color:#c62828;"> Cache! </strong></li>
		                </ul>
		                <br>
						' . $video . '
						<br><br>
				</div>
			';
	    }
	}

	/**
	 * Load plugin compatibility.
	 * 
	 * @since 1.5.3
	 */
	function required_theme_notice_frontend(){
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}

		if ( is_user_logged_in() && current_user_can('administrator') ) {
			if ( get_template_directory() !== get_stylesheet_directory() ) {
		    	$theme = wp_get_theme( 'xstore' );
		    } else {
		    	$theme = wp_get_theme();
		    }

		    if (  $theme->name == ('XStore') &&  version_compare( $theme->version, ET_CORE_THEME_MIN_VERSION, '<' ) ) {
		    	$video = '<a class="et-button et-button-active" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank"> Video tutorial</a>';
				echo '
					</br>
					<div class="woocommerce-massege woocommerce-info error">
						XStore Core plugin requires the following theme: <strong>XStore v.' . ET_CORE_THEME_MIN_VERSION . '.</strong>
						'.$video.'. This warning is visible for <strong>administrator only</strong>.
					</div>
					</br>
				';
			}
		}
	}

	/**
	 * Check for plugin update
	 *
	 * @since 1.0.20
	 */
	function check_for_plugin_update($checked_data){
		if ( ! defined( 'ETHEME_API' ) ) return $checked_data;

		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );


		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return $checked_data;
		}

		$activated_data = get_option( 'etheme_activated_data' );
		$update_info    = get_option( 'xstore-update-info', false );
		$key 			= $activated_data['api_key'];
		$plugins_dir 	= ETHEME_API . 'files/get/';
		$token 			= '?token=' . $key;
		$plugin_ver 	= ( isset( $update_info->plugin_version ) && ! empty( $update_info->plugin_version ) ) ? $update_info->plugin_version : false;

		if ( version_compare( ET_CORE_VERSION, $plugin_ver, '<' ) ) {
			$plugins_dir = ETHEME_API . 'files/get/';
			$plugins_dir . 'et-core-plugin.zip';

			$plugin = new \stdClass();
			$plugin->slug = 'et-core-plugin';
			$plugin->plugin = 'et-core-plugin/et-core-plugin.php';
			$plugin->new_version = $plugin_ver;
			$plugin->url = 'https://xstore.8theme.com/change-log.php';
			$plugin->package = apply_filters( 'etheme_plugin_url', $plugins_dir . 'et-core-plugin.zip' . $token );
			$plugin->tested = '5.8.1';
			$plugin->icons = Array(
				'2x' =>esc_url( ET_CORE_URL . '/images/256x256.png' ),
				'1x' =>esc_url( ET_CORE_URL . '/images/128x128.png' )
			);
			$checked_data->response['et-core-plugin/et-core-plugin.php'] = $plugin;
		}

		return $checked_data;
	}

}
