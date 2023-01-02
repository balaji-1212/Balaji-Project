<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Yith wcwl wishlist shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Wishlist extends VC {

	function hooks() {
		$this->register_wcwl_wishlist();
	}

	function register_wcwl_wishlist() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$params = array(
			'name' => 'YITH Wishlist',
			'base' => 'yith_wcwl_wishlist',
			'category' => $strings['category'],
			'icon' => ETHEME_CODE_IMAGES . 'vc/Wishlist.png',
			'description' => esc_html__('Display product with accent design', 'xstore-core'),
			'show_settings_on_create' => false,
			'php_class_name' => 'Vc_WooCommerce_NotEditable',
		);  
		vc_map($params);
	}

}
