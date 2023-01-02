<?php
namespace ETC\App\Models;

use ETC\App\Models\Base_Model;

/**
 * Create customizer builder setup.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models
 */
class Customizer extends Base_Model {

	/**
	 * File path.
	 *
	 * Holds the file path.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $path;

	private static $wp_uploads_dir = [];

	/**
	 * Constructor
	 */
	protected function __construct() {}

	function _run(){

		/**
		 * Load customize-builder icons.
		 * 
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/icons.php' );

		/**
		 * Load customize-builder options callbacks.
		 * 
		 * @since 1.5
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/global/callbacks.php' );

		/**
		 * Load customize-builder options.
		 * 
		 * @since 1.4.4+
		 */
		// require_once( 'theme-options/product-archive-builder/global.php' );

		/**
		 * Load customize-builder.
		 * 
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/builder/class-customize-builder.php' );

	}

	function _customizer_field(){

		// if ( class_exists( 'Kirki' ) ) {

		/**
		 * Load customize-builder options.
		 * 
		 * @since 1.5
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/global/global.php' );

		/**
		 * Load customize-builder options.
		 * 
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/header-builder/global.php' );

		/**
		 * Load customize-mobile-panel options.
		 * 
		 * @since 2.3.1
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/mobile-panel/mobile-panel.php' );

		/**
		 * Load customize-builder options.
		 * 
		 * @since 1.5
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/product-single-builder/global.php' );

        /**
         * Load customize-cart-checkout options.
         *
         * @since 4.3
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/cart-checkout/global.php' );
		
		/**
		 * Load customize-site-sections options.
		 *
		 * @since 4.4
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/site-sections/sections.php' );
		
		/**
		 * Load customize-age-verify-popup options.
		 *
		 * @since 4.4
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/age-verify-popup/popup.php' );

        /**
         * Load customize-xstore-wishlist options.
         *
         * @since 4.3.8
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/wishlist/global.php' );

        /**
         * Load customize-xstore-compare options.
         *
         * @since 4.3.9
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/compare/global.php' );

		// }

	}

	/**
	 * Write to css file
	 *
	 * @since 3.0.1
	 */
	public function write( $content, $file_name ) {
		// set path
		$this->set_path( $file_name );

		update_option( 'xstore_kirki_css_version', round(microtime(true) * 1000) );

		return file_put_contents( $this->path, $content );
	}

	/**
	 * Delete css file
	 *
	 * @since 3.0.1
	 */
	public function delete( $file_name ) {
		$dir_path = self::get_base_uploads_dir() . 'xstore/';

		wp_delete_file( $dir_path . $file_name . '.css' );
	}

	/**
	 * Set path for css file
	 *
	 * @since 3.0.1
	 */
	private function set_path( $file_name ) {
		$dir_path = self::get_base_uploads_dir() . 'xstore/';

		if ( ! is_dir( $dir_path ) ) {
			wp_mkdir_p( $dir_path );
		}

		$this->path = $dir_path . $file_name . '.css';
	}

	/**
	 * Get base upload url
	 *
	 * @since 3.0.1
	 */
	public static function get_base_uploads_dir() {
		$wp_upload_dir = self::get_wp_uploads_dir();

		return $wp_upload_dir['basedir'] . '/';
	}

	/**
	 * Get style file url
	 /
	 * @since 3.0.1
	 */
	public function get_url() {
		return set_url_scheme( self::get_base_uploads_url() . 'xstore/kirki-styles.css' );
	}

	/**
	 * get base upload url
	 *
	 * @since 3.0.1
	 */
	public static function get_base_uploads_url() {
		$wp_upload_dir = self::get_wp_uploads_dir();

		return $wp_upload_dir['baseurl'] . '/';
	}

	/**
	 * Get wp upload dir
	 *
	 * @since 3.0.1
	 */
	private static function get_wp_uploads_dir() {
		global $blog_id;
		if ( empty( self::$wp_uploads_dir[ $blog_id ] ) ) {
			self::$wp_uploads_dir[ $blog_id ] = wp_upload_dir( null, false );
		}

		return self::$wp_uploads_dir[ $blog_id ];
	}

	/**
	 * Build css file from dynamic one.
	 *
	 * @since 3.0.1
	 */
	public function generate( $file_name ) {
		// Get kirki styles
		$kirki_styles = $this->render_kirki_style();

		// Write styles to css file
		$this->write($kirki_styles, $file_name);

		$this->clear_cache();
	}

	/**
	 * Render css files.
	 *
	 * @since 3.0.1
	 */
	public function render_kirki_style() {
		$css_module = \Kirki_Modules_CSS::get_instance();
		$fonts_google = \Kirki_Fonts_Google::get_instance();

		// Go through all configs.
		$configs = \Kirki::$config;
		$styles = '';
		foreach ( $configs as $config_id => $args ) {

			$styles .= $css_module::loop_controls( $config_id );
			$font_module = new \Etheme_Modules_Webfonts_Embed( $config_id, $fonts_google );
			$font_module = $font_module->the_css();
		}

		if ( ! empty( $styles ) ) {
			if ( !empty( $font_module ) ) {
				$styles .= $font_module;
			}
			return $styles;
		}

	}

	/**
	 * Clear all possible cache.
	 *
	 * @since 3.0.1
	 */
	public function clear_cache() {
		if ( ! apply_filters( 'et_customizer_flush_cache', true ) ) return;

		global $wp_fastest_cache, $kinsta_cache, $admin;

		if ( function_exists( 'w3tc_pgcache_flush' ) ) { // w3
			\w3tc_pgcache_flush(); 
		} else if ( function_exists( 'wp_cache_clean_cache' ) ) { // super cache
			global $file_prefix, $supercachedir;
			if ( empty( $supercachedir ) && function_exists( 'get_supercache_dir' ) ) {
				$supercachedir = \get_supercache_dir();
			}
			\wp_cache_clean_cache( $file_prefix );
		} else if ( class_exists( 'WpeCommon' ) ) { // WpeCommon
			if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
				\WpeCommon::purge_memcached();
			}
			if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {  
				\WpeCommon::clear_maxcdn_cache();
			}
			if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
				\WpeCommon::purge_varnish_cache();   
			}
		} else if ( method_exists( 'WpFastestCache', 'deleteCache' ) && !empty( $wp_fastest_cache ) ) { // WpFastestCache
			$wp_fastest_cache->deleteCache( true );
		} else if ( class_exists( '\Kinsta\Cache' ) && !empty( $kinsta_cache ) ) { // Kinsta Cache
			$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
		} else if ( class_exists( '\WPaaS\Cache' ) ) { // WPaaS Cache
			if ( \WPaaS\Cache::has_ban() ) {
				return;
			}
			remove_action( 'shutdown', [ '\WPaaS\Cache', 'purge' ], PHP_INT_MAX );
			add_action( 'shutdown', [ '\WPaaS\Cache', 'ban' ], PHP_INT_MAX );
		} else if ( class_exists( 'WP_Optimize' ) && defined( 'WPO_PLUGIN_MAIN_PATH' ) ) {
			if ( ! class_exists('WP_Optimize_Cache_Commands') ) include_once( WPO_PLUGIN_MAIN_PATH . 'cache/class-cache-commands.php' );

			if ( class_exists( 'WP_Optimize_Cache_Commands' ) ) {
				$wpoptimize_cache_commands = new \WP_Optimize_Cache_Commands();
				$wpoptimize_cache_commands->purge_page_cache();
			}
		} else if ( class_exists( 'Breeze_Admin' ) ) {
			do_action('breeze_clear_all_cache');
		} else if ( class_exists( 'LiteSpeed_Cache_Purge' ) ) {
			\LiteSpeed_Cache_Purge::purge_all( 'Clear Cache For Me' );
		} else if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			sg_cachepress_purge_cache();
		} else if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			sg_cachepress_purge_cache();
		} else if ( class_exists( 'autoptimizeCache' ) ) {
			\autoptimizeCache::clearall();
		} else if ( class_exists( 'Cache_Enabler' ) ) {
			\Cache_Enabler::clear_total_cache();
		}

	}
}