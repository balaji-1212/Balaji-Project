=== STWooSwatches ===
Contributors: SThemes
Requires at least: 
	WordPress 4.7
	wooCommerce 3.3.X
Tested up to:
	WordPress 4.9.6
	wooCommerce 3.4.0

== Changelog ==

== 1.0.2 ==
* Released: June 14, 2018
	>> Fix - WooCommerce product loop - show variation default attribute
		>> Files Worked
		1. public\partials\class-st-woo-shop.php
		2. public\partials\js\frontend.js

== 1.0.1 ==
* Released: May 29, 2018
	>> Fix   - Added seperate JS listeners for Image attribute type in admin edit term and single prodcut page
	>> Tweak - Changed version number
	>> Tweak - Updated Text domain
	>> Tweek - Updated plugin action links

== 1.0.0 ==
* Released: May 18, 2018
	>> Initial release



included in to etheme-core plugin

change: text-domain

change: st-woo-swatches.php
	change: St_Woo_Swatches - class
	change: private check_requirement - function
	change: public st_wc_get_formatted_cart_item_data - function
	added: add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' ) - hook
	removed: public static activate_plugin - function
	removed: public static deactivate_plugin - function
	removed: private check_requirement - function
	removed: private set_locale - function
	load_dependencies - change
	removed: register_activation_hook - hook
	removed: register_deactivation_hook - hook
	change: swatch_html - function

change: class-st-woo-shop.php
	removed: sten_swatch_below_title - function
	removed: sten_swatch_above_title - function
	added: etheme_shop_swatches_position - function
	removed: woocommerce_before_shop_loop_item_title - hook
	added: et_after_shop_loop_image - hook

change: class-st-woo-swatches-public.php
	change: public load_template_hooks function

change: partials/class-st-woo-single-variable-product.php
	removed: enqueue_scripts - function

change: frontend.js
	change: addToCart - function

removed: st_swatch_shop_swatch_border_color 		  - option, get from theme options
removed: st_swatch_shop_swatch_active_border_color 	  - option, get from theme options
removed: st_swatch_archive_swatch_border_color 		  - option, get from theme options
removed: st_swatch_archive_swatch_active_border_color - option, get from theme options
removed: st_swatch_cart_chkout_swatch_border_color 	  - option, get from theme options
removed: st_swatch_archive_swatch_size 				  - option, get from theme options
removed: st_swatch_archive_swatch_shape 			  - option, get from theme options
removed: st_swatch_shop_swatch_size 				  - option, get from theme options
removed: st_swatch_shop_swatch_shape 				  - option, get from theme options
removed: st_swatch_product_single_swatch_size 		  - option, get from theme options
removed: st_swatch_product_single_swatch_shape 		  - option, get from theme options
removed: st_swatch_cart_chkout_swatch_size 			  - option, get from theme options
removed: st_swatch_cart_chkout_swatch_shape 		  - option, get from theme options


removed: class-st-woo-cart.php - file
removed: class-st-woo-checkout.php - file
removed: inc/st-woo-swatches/admin/js/customize-preview.js 				- file
removed: inc/st-woo-swatches/admin/partials/class-st-woo-customizer.php - file
removed: inc/st-woo-swatches/public/images/placeholder.png 				- file