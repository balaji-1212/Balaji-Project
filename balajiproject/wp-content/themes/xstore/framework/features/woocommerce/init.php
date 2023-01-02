<?php
/**
 * Description
 *
 * @package    init.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

/*
* Cart Checkout
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/cart-checkout.php') );

/*
* Estimated Delivery (sales booster)
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/estimated-delivery.php') );

/*
* Safe & Secure Checkout (sales booster)
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/safe-checkout.php') );

/*
* Reviews Images
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/reviews-images.php') );

/*
* Order email sku
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/emails.php') );

/*
* Quantity select type
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/quantity-select.php') );

