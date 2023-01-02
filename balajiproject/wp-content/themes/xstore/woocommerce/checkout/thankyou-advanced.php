<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="woocommerce-order">
	
	<?php
	if ( $order ) :
		
		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>
		
		<?php if ( $order->has_status( 'failed' ) ) : ?>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'xstore' ); ?></p>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'xstore' ); ?></a>
			<?php if ( get_query_var( 'et_is-loggedin', false) ) : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'xstore' ); ?></a>
			<?php endif; ?>
        </p>
	
	<?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you!', 'xstore' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <p class="text-center"><?php echo esc_html__('Your order details:', 'xstore'); ?></p>
        <div class="woocommerce-order-overview-wrapper">
            
            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
    
                <li class="woocommerce-order-overview__order order">
                    <h5><?php esc_html_e( 'Order number:', 'xstore' ); ?></h5>
                    <span><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </li>
    
                <li class="woocommerce-order-overview__date date">
                    <h5><?php esc_html_e( 'Date:', 'xstore' ); ?></h5>
                    <span><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </li>
                
                <?php if ( get_query_var( 'et_is-loggedin', false) && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <li class="woocommerce-order-overview__email email">
                        <h5><?php esc_html_e( 'Email:', 'xstore' ); ?></h5>
                        <span><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </li>
                <?php endif; ?>
    
                <li class="woocommerce-order-overview__total total">
                    <h5><?php esc_html_e( 'Total:', 'xstore' ); ?></h5>
                    <span><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </li>
                
                <?php if ( $order->get_payment_method_title() ) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <h5><?php esc_html_e( 'Payment method:', 'xstore' ); ?></h5>
                        <span><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
                    </li>
                <?php endif; ?>
    
            </ul>
            
        </div>

        <div class="text-center">
            <?php
                $terms_page_id = wc_terms_and_conditions_page_id();
                if ( $terms_page_id ) {
                    echo '<a href="' . esc_url( get_permalink( $terms_page_id ) ) . '" class="woocommerce-privacy-policy-link text-underline" target="_blank">' . __( 'Read about our privacy policy', 'xstore' ) . '</a>';
                }
            ?>
        </div>
	
	<?php endif; ?>
		
        <?php
		// keep direct check with theme mode because mostly this area is refreshed by ajax and query vars don't work
		$cart_checkout_advanced_layout = get_theme_mod('cart_checkout_advanced_layout', false);
		$product_image_checkout_details = $cart_checkout_advanced_layout && get_theme_mod('cart_checkout_order_product_images', true);
        ?>
		<?php if ( $product_image_checkout_details ) :
                        add_filter('woocommerce_order_item_name', function ($item_name, $item, $is_visible) {
                            $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
	                        $product = wc_get_product($product_id);
                            
                            return $product->get_image() . '<span class="product-name-info">'. $item_name;
                        }, 1, 3);
                        
                        add_action('woocommerce_order_item_meta_end', function () {
                            echo '</span>';
                        }, 999);
            endif;
        ?>
		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
	
	<?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'xstore' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	
	<?php endif; ?>

</div>