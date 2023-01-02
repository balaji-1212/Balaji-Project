<?php
/**
 * Description
 *
 * @package    form-checkout-separated.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php wc_print_notices(); ?>
	
	<div class="before-checkout-form">
		<?php
		do_action( 'woocommerce_before_checkout_form', $checkout );
		?>
	</div>
<?php

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! get_query_var( 'et_is-loggedin', false) ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'xstore' ) ) );
	return;
}

$elementor_checkout_builder = false;
// check for PRO version of Elementor because there is available cart page editor
if ( defined('ELEMENTOR_PRO_VERSION') ) {
	if ( Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		$elementor_checkout_builder = true;
	}
}
$elementor_checkout_builder = apply_filters('etheme_elementor_checkout_page', $elementor_checkout_builder);


// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );

$cart_checkout = Etheme_WooCommerce_Cart_Checkout::get_instance();
$check_page = $cart_checkout->check_page();
$is_billing_step = $check_page['is_billing'];
$is_shipping_step = $check_page['is_shipping'];
$is_payment_step = $check_page['is_payment'];
?>

<?php if ( !$elementor_checkout_builder ) : ?>
	<div class="row checkout-columns-wrap">
	<div class="col-md-7 clearfix">
<?php endif; ?>
	
	<div class="etheme-above-checkout-form">
		<?php do_action( 'etheme_woocommerce_above_checkout_form' );  ?>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		
		<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
			
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
        
            <div class="etheme-cart-checkout-tab <?php if ( $is_billing_step ) echo ' active'; ?>" data-step="billing" <?php if ( !$is_billing_step ) : ?> style="display: none;" <?php endif; ?>>
                <div id="customer_details">
                    <h3 class="step-title">
                        <span>
                            <?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() )
                                esc_html_e( 'Billing &amp; Shipping', 'xstore' );
                            else
                                esc_html_e( 'Billing Details', 'xstore' );
                            ?>
                        </span>
                    </h3>
                    <div class="tab-content">
    
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
    
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
    
                    </div>
                </div>
            </div>

            <div class="etheme-shipping-fields-wrapper etheme-cart-checkout-tab <?php if ( $is_shipping_step ) echo ' active'; ?>" data-step="shipping" <?php if ( !$is_shipping_step ) : ?> style="display: none;" <?php endif; ?>>
                <h3 class="step-title"><span><?php esc_html_e( 'Shipping', 'xstore' ); ?></span></h3>
                <div class="tab-content">
					<?php $cart_checkout->cart_totals_shipping_html(); ?>
					<?php $cart_checkout->woocommerce_checkout_billing_footer_links('shipping'); ?>
                </div>
            </div>

            <div class="etheme-shipping-fields-wrapper etheme-cart-checkout-tab <?php if ( $is_payment_step ) echo ' active'; ?>" data-step="payment"  <?php if ( !$is_payment_step ) : ?> style="display: none;" <?php endif; ?>>
                <h3 class="step-title"><span><?php esc_html_e( 'Payment', 'xstore' ); ?></span></h3>
                <div class="tab-content">
                    <div class="etheme-payment-fields">
						<?php
						woocommerce_checkout_payment();
						?>
                    </div>
                </div>
            </div>
        
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
		
		<?php endif; ?>
	
	</form>

<?php if ( !$elementor_checkout_builder ) : ?>
	</div>
<?php endif; ?>
	<div class="<?php if ( !$elementor_checkout_builder ) : ?>col-md-5 <?php endif; ?>">
        <div class="cart-order-details">
            <div class="order-review">
                <h3 class="step-title"><span><?php esc_html_e( 'Your order', 'xstore' ); ?></span></h3>
                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                
                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>
                
                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </div>
        </div>
	</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>