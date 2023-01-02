<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Define base constants
// **********************************************************************//

define('ETHEME_FW', '1.0');
define('ETHEME_BASE', get_template_directory() .'/');
define('ETHEME_CHILD', get_stylesheet_directory() .'/');
define('ETHEME_BASE_URI', get_template_directory_uri() .'/');

define('ETHEME_CODE', 'framework/');
define('ETHEME_CODE_DIR', ETHEME_BASE.'framework/');
define('ETHEME_TEMPLATES', ETHEME_CODE . 'templates/');
define('ETHEME_THEME', 'theme/');
define('ETHEME_THEME_DIR', ETHEME_BASE . 'theme/');
define('ETHEME_TEMPLATES_THEME', ETHEME_THEME . 'templates/');
define('ETHEME_CODE_3D', ETHEME_CODE .'thirdparty/');
define('ETHEME_CODE_3D_URI', ETHEME_BASE_URI.ETHEME_CODE .'thirdparty/');
define('ETHEME_CODE_WIDGETS', ETHEME_CODE .'widgets/');
define('ETHEME_CODE_POST_TYPES', ETHEME_CODE .'post-types/');
define('ETHEME_CODE_SHORTCODES', ETHEME_CODE .'shortcodes/');
define('ETHEME_CODE_CSS', ETHEME_BASE_URI . ETHEME_CODE .'assets/admin-css/');
define('ETHEME_CODE_JS', ETHEME_BASE_URI . ETHEME_CODE .'assets/js/');
define('ETHEME_CODE_IMAGES', ETHEME_BASE_URI . ETHEME_THEME .'assets/images/');
define('ETHEME_CODE_CUSTOMIZER_IMAGES', ETHEME_BASE_URI . ETHEME_CODE . 'customizer/images/theme-options');
define('ETHEME_BASE_URL', 'https://www.8theme.com/');
define('ETHEME_API', ETHEME_BASE_URL . 'themes/api/');


define('ETHEME_PREFIX', '_et_');

define( 'ETHEME_THEME_VERSION', '8.3.9' );
define( 'ETHEME_CORE_MIN_VERSION', '4.3.9' );
define( 'ETHEME_MIN_CSS', get_theme_mod( 'et_load_css_minify', true ) ? '.min' : '' );
// **********************************************************************// 
// ! Helper Framework functions
// **********************************************************************//
require_once( ETHEME_BASE . ETHEME_CODE . 'helpers.php' );

/*
* Theme features
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/init.php') );

/*
* Theme f-ns
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'theme-functions.php') );

/*
* Theme template elements
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'template-elements.php') );

// global functions for post types
require_once( apply_filters('etheme_file_url', ETHEME_CODE_POST_TYPES . 'post-functions.php') );
require_once( apply_filters('etheme_file_url', ETHEME_CODE_POST_TYPES . 'menu-functions.php') );

/*
* Menu walkers
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'walkers.php') );

// **********************************************************************// 
// ! Framework setup
// **********************************************************************//
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'theme-init.php') );


/*
* Post types
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE_POST_TYPES . 'static-blocks.php') );
if ( get_theme_mod('portfolio_projects', true) ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE_POST_TYPES . 'portfolio.php' ) );
}

/*
* Plugin compatibilities
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'compatibility/init.php') );

/*
* Plugins activation
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE_3D . 'tgm-plugin-activation/class-tgm-plugin-activation.php') );

/*
* Taxonomy metadat
* ******************************************************************* */
require_once apply_filters('etheme_file_url', ETHEME_CODE_3D . 'cmb2-taxonomy/init.php');

/*
* WooCommerce f-ns
* ******************************************************************* */
if(class_exists('WooCommerce') && current_theme_supports('woocommerce') ) {
	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'woo.php') );
}

/* 
*
* Theme Options 
* ******************************************************************* */

require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'class-kirki-installer-section.php') );

if ( is_customize_preview() ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/init.php' ) );
}

require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'theme-options.php' ) );

/*
* Sidebars
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'sidebars.php') );

/*
* Custom Metaboxes for pages
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'custom-metaboxes.php') );

/*
* Admin panel setup
* ******************************************************************* */
if ( is_admin() ) {
	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'system-requirements.php') );

	// require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'thirdparty/fonts_uploader/etheme_fonts_uploader.php') );
	
	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'admin.php') );

	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'admin/widgets/class-admin-sidebasr.php') );

	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'panel/panel.php') );

	require_once( apply_filters('etheme_file_url', ETHEME_CODE_3D . 'menu-images/nav-menu-images.php'));

	/*
	* Check theme version
	* ******************************************************************* */
	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'version-check.php') );

}

/*
* without core plugin functionality
* ******************************************************************* */
if (! defined('ET_CORE_VERSION')){
	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'plugin-disabled/init.php') );
}
