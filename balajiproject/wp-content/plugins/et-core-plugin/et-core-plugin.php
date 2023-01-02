<?php
/**
 * @link              https://xstore.8theme.com
 * @since             1.4.4
 * @package           XStore Core
 *
 * Plugin Name:       XStore Core
 * Plugin URI:        http://8theme.com
 * Description:       8theme Core Plugin for XStore theme.
 * Version:           4.3.9
 * Author:            8theme
 * Author URI:        https://xstore.8theme.com
 * Text Domain:       xstore-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists('write_log')) {
    function write_log ( $log )  {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( 'File:' . basename( $caller['file'] ) .':'.$caller['line'] . ' ' . print_r( $log, true ) );
        } else {
            error_log( 'File:' . basename( $caller['file'] ) .':'.$caller['line'] . ' ' . $log );
        }
    }
}

/**
 *  Initial class
 */
class ETC_Initial {

	/**
	 * Minimum php version for plugin.
	 *
	 * @var string
	 * @since 1.4.4
	 */
	private $min_php_version = '5.3';

	/**
	 * Minimum wp version.
	 *
	 * @var string
	 * @since 1.4.4
	 */
	private $min_wp_version = '4.8';

	/**
	 * Compatible with Multisite.
	 *
	 * @var boolean
	 * @since 1.4.4
	 */
	private $multisite_compatible = true;


	/**
	 * Holds Error messages if dependencies are not met
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $errors = array();

    /**
	 * The single instance of the class.
	 *
	 * @var instance
	 * @since 1.4.4
	 */
    protected static $instance = null;

	/**
	 * Cloning is forbidden.
	 * @since 1.4.4
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheating huh?', 'xstore-core' ), '2.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.4.4
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheating huh?', 'xstore-core'), '2.0.0' );
	}

    /**
	 * Main ETCore Instance.
	 *
	 * Ensures only one instance of ETCore is loaded or can be loaded.
	 * @return ETCore - Main instance.
	 * @since 1.4.4
	 */
    public static function instance() {
        // Get an instance of Class
    	if( is_null( self::$instance ) ) self::$instance = new self();

        // Return the instance
    	return self::$instance;
    }

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.4.4
	 */
	public function __construct(){
		// Define constant
		$this->define_constants();
		// Run the plugin
		$this->run_etc();
	}

	/**
	 * Define Listdom Constants.
	 * @since 1.4.4
	 */
	private function define_constants() {
		/**
		* define ET_CORE_URL.
		* 
		* @since 1.1.1
		*/
		define( 'ET_CORE_VERSION', '4.3.9' );

		/**
		 * define ET_CORE_THEME_MIN_VERSION.
		 * 
		 * @since 1.5.3
		 */
		define( 'ET_CORE_THEME_MIN_VERSION', '8.3.9' );

		/**
		* define ET_CORE_URL.
		* 
		* @since 1.1.1
		*/
		define( 'ET_CORE_URL', plugin_dir_url( __FILE__ ) );

		/**
		* define ET_CORE_DIR.
		* 
		* @since 1.1.3
		*/
		define( 'ET_CORE_DIR', plugin_dir_path( __FILE__ ) );

		/**
		* define ET_CORE_SHORTCODES_IMAGES.
		* 
		* @since 2.2.3
		*/
		define( 'ET_CORE_SHORTCODES_IMAGES', ET_CORE_URL . 'app/controllers/images/' );
	}

	/**
	 * Begins execution of the plugin.
	 *
	 * @since    1.4.4
	 */
	public function run_etc() {

		// If Requirements are not met.
		if ( ! $this->plugin_requirements_checker() ) {

			add_action( 'admin_notices', array( $this, 'requirements_errors' ) );

			// Deactivate if requirements are not met.
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins( plugin_basename( __FILE__ ) );

			return false;
		}

		require_once ET_CORE_DIR . 'inc/core.php';

		// Fire the plugin
		new ETC\Inc\Core();

		register_activation_hook( __FILE__, array( new ETC\Inc\Activator(), 'activate' ) );
		register_deactivation_hook( __FILE__, array( new ETC\Inc\Deactivator(), 'deactivate' ) );
		
	}

	/**
	 * Creates/Maintains the object of Requirements Checker Class
	 *
	 * @return boolean
	 * @since 1.4.4
	 */
	function plugin_requirements_checker() {
		static $requirements_checker = null;

		if ( null === $requirements_checker ) {
			$requirements_checker = $this->requirements_met();
		}

		return $requirements_checker;
	}

	/**
	 * Checks if all plugins requirements are met or not
	 *
	 * @return boolean
	 * @since 1.4.4
	 */
	public function requirements_met() {

		if ( ! $this->is_php_version_ready() ) {
			return false;
		}

		if ( ! $this->is_wp_version_ready() ) {
			return false;
		}

		if ( ! $this->is_wp_multisite_ready() ) {
			return false;
		}

		if ( ! $this->plugin_compatibility() ) {
			return false;
		}
			
			// if ( ! $this->required_theme_version() ) {
			// return false;
		// }

		return true;
	}

	/**
	 * Checks if Installed WP Version is higher than required WP Version
	 *
	 * @return boolean
	 * @since 1.4.4
	 */
	private function is_php_version_ready() {

		if ( ! version_compare( $this->min_php_version ,  PHP_VERSION, '>=' ) ) {
			return true;
		}

		$this->add_error_notice(
			'PHP ' . $this->min_php_version . '+ is required',
			'You\'re running version ' . PHP_VERSION
		);

		return false;
	}

	/**
	 * Checks if Installed WP Version is higher than required WP Version
	 *
	 * @return boolean
	 * @since 1.4.4
	 */
	private function is_wp_version_ready() {
		global $wp_version;

		if ( ! version_compare( $this->min_wp_version, $wp_version, '>=' ) ) {
			return true;
		}

		$this->add_error_notice(
			'WordPress ' . $this->min_wp_version . '+ is required',
			'You\'re running version ' . $wp_version
		);

		return false;
	}

	/**
	 * Checks if Multisite Dependencies are met
	 *
	 * @return boolean
	 * @since 1.4.4
	 */
	private function is_wp_multisite_ready() {
		$wp_multisite = is_multisite() && ( false === $this->multisite_compatible ) ? false : true;

		if ( false == $wp_multisite ) {
			$this->add_error_notice(
				'Your site is set up as a Network (Multisite)',
				'This plugin is not compatible with multisite environment'
			);
		}

		return $wp_multisite;
	}

	/**
	 * Checks for compatibility
	 *
	 * @return boolean
	 * @since 1.4.4
	 */
	private function plugin_compatibility() {
		$plugins = array();

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_plugin_active( 'legenda-core/legenda-core.php' ) ) {
			$plugins[] = 'Legenda core';
		} 

		if ( is_plugin_active( 'woopress-core/woopress-core.php' ) ) {
			$plugins[] = 'Woopress core';
		} 

		if ( is_plugin_active( 'classico-core-plugin/post-types.php' ) ) {
			$plugins[] = 'Classico core';
		} 

		if ( is_plugin_active( 'royal-core/royal-core.php' ) ) {
			$plugins[] = 'Royal core';
		} 

		if ( count( $plugins ) ) {
			$html = '<div class="error">';
			$html .= '<p>' . esc_html__( 'Attention!', 'xstore-core' ) . '</p>';
			$html .= '<p>';
			$html .= '<strong>';
			$html .= '<span>' . esc_html__( 'Xstore Core plugin conflicts with the following plugins: ', 'xstore-core' ) . '</span>';
			$_i = 0;
			foreach ( $plugins as $value ) {
				$_i++;
				if ( $_i == count( $plugins ) ) {
					$html .= '<span>' . $value . '</span>.';
				} else {
					$html .= '<span>' . $value . '</span>, ';
				}
			}
			$html .= '</strong>';
			$html .= '</p>';
			$html .= '<p>' . esc_html__( 'Keep enabled only plugin that comes bundled with activated theme.', 'xstore-core' ) . '</p>';
			$html .= '</div>';

			$this->add_error_notice(
				$html,
				''
			);

			return false;
		}

		return true;
	}

	/**
	 * ! Notice "Theme version"
	 * @since 1.1
	 */
	function required_theme_version(){

		if ( get_template_directory() !== get_stylesheet_directory() ) {
			$theme = wp_get_theme( 'xstore' );
		} else {
			$theme = wp_get_theme();
		}

		if ( $theme->name == ('XStore') &&  version_compare( $theme->version, ET_CORE_THEME_MIN_VERSION, '<' ) ) {
			$this->add_error_notice(
				__( 'XStore Core plugin requires the following theme: XStore '. ET_CORE_THEME_MIN_VERSION .' or higher.', 'xstore-core' ),
				''
			);

			return false;
		}

		return true;
	}

	/**
	 * Adds Error message in $errors variable
	 *
	 * @param string $error_message Error Message.
	 * @param string $supportive_information.
	 * @return void
	 * @since 1.4.4
	 */
	private function add_error_notice( $error_message, $supportive_information ) {
		$this->errors[] = (object) [
			'error_message' => $error_message,
			'supportive_information' => $supportive_information,
		];
	}

	/**
	 * Prints an error that the system requirements weren't met.
	 *
	 * @since    1.4.4
	 */
	public function requirements_errors() {
		$errors = $this->errors;
		require_once( ET_CORE_DIR . 'templates/admin/errors/requirements-error.php' );
	}
}


/**
 * Main instance of etcore.
 *
 * Returns the main instance of etcore to prevent the need to use globals.
 *
 * @since  1.4.4
 * @return etcore
 */
function initial_ETC() {
	return ETC_Initial::instance();
}

// Init the etcore :)
initial_ETC();
