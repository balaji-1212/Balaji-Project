<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 

wc_print_notices();

$empty_cart_content = etheme_get_option('empty_cart_content', '<h1 style="text-align: center;">YOUR SHOPPING CART IS EMPTY</h1><p style="text-align: center;">We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!</p> ');

$woo_new_7_0_1_version = etheme_woo_version_check();
$button_class = '';
if ( $woo_new_7_0_1_version ) {
    $button_class = wc_wp_theme_get_element_class_name( 'button' );
}

?>

<?php do_action('woocommerce_cart_is_empty'); ?>

<div class="cart-empty empty-cart-block">
	<?php if( empty( $empty_cart_content ) ): ?>
		<h1 style="text-align: center;"><?php esc_html_e('Your shopping cart is empty', 'xstore') ?></h1>
		<p style="text-align: center;"><?php esc_html_e('We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!', 'xstore') ?></p>
	<?php else: ?>
		<?php echo do_shortcode( $empty_cart_content ); ?>
	<?php endif; ?>
	<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<p><a class="btn black<?php echo esc_attr( $button_class ? ' ' . $button_class : '' ); ?>" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore') ?></span></a></p>
	<?php endif; ?>
</div>