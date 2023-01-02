<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;
use ETC\App\Controllers\Customizer;

/**
 * Create post type controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Upgrade extends Base_Controller{

	public function hooks() {
		// 3.1 version
		if ( ET_CORE_VERSION === '3.1' && ! get_option( 'xstore_upgrade_v_3_1', false ) ) {
			$this->v_3_1();
		}
	}

	/**
	 * Register widget args
	 *
	 * @return mixed|null|void
	 */
	public function v_3_1() {
		require_once ET_CORE_DIR . 'packages/kirki/kirki.php';
		require_once( ET_CORE_DIR . 'app/models/customizer/webfont-extend.php' );
		/**
		 * Load customize-builder.
		 *
		 * @since 1.4.3
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/class-ajax-search.php' );
		
		/**
		 * Load builder functions.
		 *
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/functions.php' );
		
		/**
		 * Load customizer addons.
		 *
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/addons.php' );
		
		/**
		 * Customizer import/export plugin
		 *
		 * @since 2.1.4
		 */
		if ( ! defined( 'CEI_PLUGIN_DIR' ) ) {
			require_once( ET_CORE_DIR . 'packages/customizer-export-import/customizer-export-import.php' );
		}

		add_action( 'init', array( $this, 'v_3_1_init' ), 11 );

	}

	public function v_3_1_init() {

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

		if ( ! defined('ETHEME_CODE_CUSTOMIZER_IMAGES') ) return;

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
		 * Load sales-booster options.
		 *
		 * @since  3.1.1
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/sales-booster/sales-booster.php' );

		/**
		 * Load customize-builder options.
		 *
		 * @since 1.5
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/product-single-builder/global.php' );

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

		// Default Customizer
		$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );
		$Customizer->customizer_style('kirki-styles');
		update_option( 'xstore_kirki_styles_render', 'regenerated' );

		// Multiple header
		$Etheme_Customize_header_Builder = new \Etheme_Customize_header_Builder();
		$Etheme_Customize_header_Builder->generate_header_builder_style('all');
		update_option( 'xstore_kirki_hb_render', 'regenerated' );

		// Multiple products
		$Etheme_Customize_header_Builder->generate_single_product_style('all');
		update_option( 'xstore_kirki_sp_render', 'regenerated' );

		// Upgraded to 3.1
		update_option( 'xstore_upgrade_v_3_1', true );

	}

}

