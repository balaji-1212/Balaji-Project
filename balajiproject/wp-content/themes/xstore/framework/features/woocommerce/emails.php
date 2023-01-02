<?php
/**
 * Adding actions/filters for woocommerce emails
 *
 * @package    emails.php
 * @since      8.0.5
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

function etheme_product_sku_in_email_order( $args ) {
	if ( in_array('order-email', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart') ) ) )
		$args['show_sku'] = true;
	
	return $args;
}

add_filter( 'woocommerce_email_order_items_args', 'etheme_product_sku_in_email_order', 10, 1 );