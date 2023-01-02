<?php
namespace ETC\Inc;

/**
 * Define the internationalization functionality
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/includes
 */
class i18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since    1.4.4
	 * @access   private
	 * @var      string    $domain
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.4.4
	 */
	public function load_plugin_textdomain() {
		
		$locale = apply_filters( 'plugin_locale', get_locale(), 'xstore-core' );

		load_textdomain( 'xstore-core', WP_LANG_DIR . '/xstore-core/xstore-core-' . $locale . '.mo' );

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Set the domain.
	 *
	 * @since    1.4.4
	 * @param    string $domain
	 */
	public function set_domain( $domain ) {
		$this->domain = $domain;
	}

}
