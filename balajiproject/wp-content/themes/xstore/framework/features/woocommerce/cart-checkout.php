<?php
/**
 * Description
 *
 * @package    cart-checkout.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_WooCommerce_Cart_Checkout {
	
	public static $instance = null;
	
	public function check_page() {
	    global $wp;
		$page_id = get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$page_id = $page_id['id'];
		$is_checkout = $page_id == wc_get_page_id( 'checkout' ) || get_query_var( 'et_is-checkout', false );
		$is_cart = $page_id == wc_get_page_id( 'cart' ) || get_query_var( 'et_is-cart', false );
		$is_order_pay = false;
		$is_order = false;

        // Handle checkout actions.
		if ( ! empty( $wp->query_vars['order-pay'] ) ) {
			$is_order_pay = true;
		} elseif ( isset( $wp->query_vars['order-received'] ) ) {
			$is_order = true;
		}
		
		return array(
			'is_checkout' => $is_checkout,
            'is_billing' => $is_checkout && !$is_order && (!isset($_GET['step']) || $_GET['step'] == 'billing'),
            'is_shipping' => $is_checkout && isset($_GET['step']) && $_GET['step'] == 'shipping',
            'is_payment' => $is_checkout && isset($_GET['step']) && $_GET['step'] == 'payment',
            'is_cart' => $is_cart,
            'is_order_pay' => $is_order_pay,
            'is_order' => $is_order,
        );
    }
	
	public function advanced_layout() {
		$this->disable_advanced_layout_actions();
		$this->enable_advanced_layout_actions();
		$this->advanced_layout_scripts();
	}
	
	public function disable_advanced_layout_actions() {
		$actions_list = array();
		if ( !get_query_var('et_cart-checkout-header-builder', false) ) {
		    $actions_list = array(
			    array(
				    'action' => 'etheme_vertical_header',
				    'hook' => 'etheme_header',
				    'priority' => 1
			    ),
			    array(
				    'action' => 'etheme_header_top',
				    'hook' => 'etheme_header',
				    'priority' => 10
			    ),
			    array(
				    'action' => 'etheme_header_main',
				    'hook' => 'etheme_header',
				    'priority' => 20
			    ),
			    array(
				    'action' => 'etheme_header_bottom',
				    'hook' => 'etheme_header',
				    'priority' => 30
			    ),
			    array(
				    'action' => 'etheme_header_banner',
				    'hook' => 'etheme_header',
				    'priority' => 2
			    ),
			
			    // mobile header
//			array(
//				'action' => 'etheme_header_top',
//				'hook' => 'etheme_header_mobile',
//				'priority' => 10
//			),
//			array(
//				'action' => 'etheme_header_main',
//				'hook' => 'etheme_header_mobile',
//				'priority' => 20
//			),
//			array(
//				'action' => 'etheme_header_bottom',
//				'hook' => 'etheme_header_mobile',
//				'priority' => 30
//			),
//			array(
//				'action' => 'etheme_header_banner',
//				'hook' => 'etheme_header_mobile',
//				'priority' => 40
//			),
       
			    array(
				    'actions' => true,
				    'hook' => 'etheme_header_mobile',
			    ),
            );
        }
		$actions_list = array_merge($actions_list, array(
			// remove specific breadcrumbs and sales booster countdown
			array(
				'action' => 'et_cart_checkout_breadcrumbs',
				'hook' => 'etheme_page_heading',
				'priority' => 20
			),
			array(
				'action' => 'etheme_mobile_panel',
				'hook' => 'after_page_wrapper',
				'priority' => 1
			),
			array(
				'action' => 'etheme_btt_button',
				'hook' => 'after_page_wrapper',
				'priority' => 30
			),
			array(
				'action' => 'etheme_photoswipe_template',
				'hook' => 'after_page_wrapper',
				'priority' => 30
			),
			array(
				'action' => 'et_notify',
				'hook' => 'after_page_wrapper',
				'priority' => 40
			),
			array(
				'action' => 'et_buffer',
				'hook' => 'after_page_wrapper',
				'priority' => 40
			),
			
			array(
				'action' => 'etheme_bordered_layout',
				'hook' => 'et_after_body',
			),
   
		));
		
		if ( !get_query_var('et_cart-checkout-default-footer', false) ) {
			$actions_list = array_merge( $actions_list, array(
				array(
					'actions' => true,
					'hook'    => 'etheme_prefooter',
				),
				array(
					'actions' => true,
					'hook'    => 'etheme_footer',
				),
			) );
		}
		
		if ( get_query_var('et_cart-checkout-layout', 'default') != 'default') {
			$actions_list[] = array(
				'action'   => 'woocommerce_checkout_payment',
				'hook'     => 'woocommerce_checkout_order_review',
				'priority' => 20
			);
//			$actions_list[] = array(
//				'action' => 'woocommerce_order_review',
//				'hook' => 'woocommerce_checkout_order_review',
//				'priority' => 10
//			);
		}
//			add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
//			add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
			$actions_list[] = array(
				'action' => 'woocommerce_checkout_login_form',
				'hook' => 'woocommerce_before_checkout_form',
				'priority' => 10
			);
			$actions_list[] = array(
				'action' => 'woocommerce_checkout_coupon_form',
				'hook' => 'woocommerce_before_checkout_form',
				'priority' => 10
			);
//        }
		
		foreach ($actions_list as $action_details) {
			if ( isset( $action_details['actions'] ) ) {
				remove_all_actions( $action_details['hook'] );
			} elseif ( isset( $action_details['priority'] ) ) {
				remove_action( $action_details['hook'], $action_details['action'], $action_details['priority'] );
			} else {
				remove_action( $action_details['hook'], $action_details['action'] );
			}
		}
	}
	
	public function header_steps($no_wrapper = false) {
		$check_pages = $this->check_page();
		$classes = array(
			'cart' => '',
			'checkout' => '',
			'order' => 'no-click'
		);
		
		if ( $check_pages['is_order'] ) {
			$classes['cart'] = $classes['checkout'] = $classes['order'] = 'active';
		}
        elseif ( $check_pages['is_checkout'] ) {
			$classes['cart'] = $classes['checkout'] = 'active';
			$classes['checkout'] .= ' no-click';
		}
        elseif ( $check_pages['is_cart'] ) {
			$classes['cart'] = 'active no-click';
		}
		
		$cart_url = wc_get_cart_url();
		$checkout_url = wc_get_checkout_url();
		$arrow = '<span class="et-icon et-'.(get_query_var('et_is-rtl', false) ? 'left' : 'right').'-arrow"></span>';
		
		$no_wrapper = !!$no_wrapper;
		
		if ( !$no_wrapper )
		    echo '<div class="cart-checkout-nav'.(( get_query_var('et_cart-checkout-layout', 'default') != 'default' ) ? '-simple': '').'">';
		
		if ( $check_pages['is_order'] ) :
			echo '<a class="active"><i class="et-icon et_b-icon et-checked"></i><span>' . esc_html__('Your order has been received', 'xstore') . '</span></a>';
        elseif ( get_query_var('et_cart-checkout-layout', 'default') == 'default' ) :
			echo '<a href="'. $cart_url .'" class="' . esc_attr($classes['cart']) . '" data-step="1"> ' . esc_html__('Shopping cart', 'xstore') . '</a>' . $arrow;
			
			echo '<a href="' . $checkout_url . '" class="'. esc_attr($classes['checkout']) . '" data-step="2"> ' . esc_html__('Checkout', 'xstore') . '</a>' . $arrow;
			
			echo '<a href="#" class="'. esc_attr($classes['order']) . '" data-step="3">'. esc_html__('Order status', 'xstore') . '</a>';
		else :
			
			echo '<a href="'. $cart_url .'" class="' . ($check_pages['is_cart'] ? 'active' : '') . '" data-step="shopping-cart"> ' . esc_html__('Shopping cart', 'xstore') . '</a>' . $arrow;
			
			echo '<a href="' . $checkout_url . '" class="' . ($check_pages['is_billing'] ? 'active' : '') . '" '.($check_pages['is_checkout'] ? 'data-step="billing"' : '') . '> ' . esc_html__('Billing details', 'xstore') . '</a>' . $arrow;
			
			if ( WC()->cart->needs_shipping() )
				echo '<a href="' . add_query_arg('step', 'shipping', $checkout_url) . '" class="' . ($check_pages['is_shipping'] ? 'active' : '') . '"' . ($check_pages['is_checkout'] ? 'data-step="shipping"' : '') . '> ' . esc_html__('Shipping', 'xstore') . '</a>' . $arrow;
			
			echo '<a href="' . add_query_arg('step', 'payment', $checkout_url) . '" class="' . ($check_pages['is_payment'] ? 'active' : '') . '"' . ($check_pages['is_checkout'] ? 'data-step="payment"' : '') . '> ' . esc_html__('Payment', 'xstore') . '</a>' . $arrow;
			
			echo '<a href="#" class="'. esc_attr($classes['order']) . '" data-step="order">'. esc_html__('Order status', 'xstore') . '</a>';
		
		endif;
		
		if ( !$no_wrapper )
		    echo '</div>';
    }
	
	public function update_progress_bar() {
	    ob_start();
		    $this->sales_booster_progress_bar();
		wp_send_json(ob_get_clean());
	}
	
	public function enable_advanced_layout_actions() {
		
	    if ( !get_query_var('et_cart-checkout-header-builder', false) ) {
		    add_action( 'etheme_header_start', function () {
			    $sticky_header = get_theme_mod( 'cart_checkout_main_header_sticky_et-desktop', '1' ) && get_query_var( 'et_cart-checkout-layout', 'default' ) != 'separated';
			    add_filter( 'theme_mod_top_header_sticky_et-mobile', '__return_false' );
			    add_filter( 'theme_mod_main_header_sticky_et-mobile', function ( $value ) use ( $sticky_header ) {
				    return $sticky_header;
			    } );
			    add_filter( 'theme_mod_bottom_header_sticky_et-mobile', '__return_false' );
			
			    add_filter( 'theme_mod_top_header_sticky_et-desktop', '__return_false' );
			    add_filter( 'theme_mod_main_header_sticky_et-desktop', function ( $value ) use ( $sticky_header ) {
				    return $sticky_header;
			    } );
			    add_filter( 'theme_mod_bottom_header_sticky_et-desktop', '__return_false' );
			
			    add_filter( 'theme_mod_header_sticky_type_et-desktop', function () {
				    return 'sticky';
			    } );
		    }, 3 );
		    if ( get_query_var( 'et_cart-checkout-layout', 'default' ) != 'separated' || $this->check_page()['is_order'] ) {
//			etheme_woocommerce_above_checkout_form
			    add_action( 'etheme_header', array( $this, 'etheme_cart_checkout_header_content' ), 10 );
		    } else {
			    add_action( 'etheme_woocommerce_above_checkout_form', array(
				    $this,
				    'etheme_cart_checkout_header_content_separated'
			    ), - 10 );
			    // cart page use-case
			    add_action( 'etheme_woocommerce_before_cart_form', array(
				    $this,
				    'etheme_cart_checkout_header_content_separated'
			    ), - 10 );
		    }
	    }
	    else {
		    add_action( 'etheme_woocommerce_above_checkout_form', array(
			    $this,
			    'header_steps'
		    ), - 10 );
		    add_action( 'etheme_woocommerce_before_cart_form', array(
			    $this,
			    'header_steps'
		    ), - 10 );
        }
		if ( !get_query_var('et_cart-checkout-default-footer', false) ) {
			add_action( 'etheme_footer', array( $this, 'etheme_cart_checkout_footer_content' ), 10 );
		}
		set_query_var('et_page-banner', false);
		set_query_var('et_breadcrumbs', false);
		
		if ( get_option('xstore_sales_booster_settings_cart_checkout_countdown') && !WC()->cart->is_empty() ) {
			add_action('etheme_woocommerce_before_cart_form', array($this, 'sales_booster_countdown_output'), 2);
			add_action('etheme_woocommerce_above_checkout_form', array($this, 'sales_booster_countdown_output'), 2);
//			add_action('woocommerce_checkout_before_customer_details', array($this, 'sales_booster_countdown_output'), 2);
		}
		
		if ( get_theme_mod('cart_checkout_advanced_form_label', false) ) {
		    add_filter('woocommerce_default_address_fields', function ($fields) {
		        $new_fields = array();
		       foreach ($fields as $field_key => $field) {
		           if ( isset($field['label']) && $field['label'] != '' ) {
		               if ( isset($field['label_class']) ) {
		                   if ( !in_array( 'screen-reader-text', $field['label_class'] ) )
			                    $field['placeholder'] = '';
                       }
		               elseif ( isset($field['placeholder']) ) {
			               $field['placeholder'] = '';
		               }
                   }
			       $new_fields[$field_key] = $field;
               }
		       return $new_fields;
            });
			add_filter( 'woocommerce_form_field_args', function ( $args, $key, $value ) {
				if ( $args['label'] != '' && ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
					$args['class'][] = 'et-advanced-label';
					$args['placeholder'] = '';
				}
				
				if ( $args['type'] == 'textarea' ) {
					$args['label_class'][] = 'textarea-label';
				}
				
				if ( ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
					$args['class'][] = 'et-validated';
				}
				
				return $args;
			}, 3, 100 );
		}
		
		$actions_list = array();
		
		if ( get_query_var('et_cart-checkout-layout', 'default') == 'multistep') {
			if ( WC()->cart->needs_shipping() ) {
				$actions_list[] = array(
					'action'   => array( $this, 'cart_totals_shipping_wrapper_html' ),
					'hook'     => 'woocommerce_checkout_after_customer_details',
					'priority' => 10
				);
			}
			$actions_list[] = array(
				'action'   => array( $this, 'checkout_payment' ),
				'hook'     => 'woocommerce_checkout_after_customer_details',
				'priority' => 20
			);
		}
			$actions_list[] = array(
				'action' => 'woocommerce_checkout_login_form',
				'hook' => 'etheme_woocommerce_above_checkout_form',
				'priority' => 10
			);
			$actions_list[] = array(
				'action' => 'woocommerce_checkout_coupon_form',
				'hook' => 'etheme_woocommerce_above_checkout_form',
				'priority' => 10
			);
		if ( get_query_var('et_cart-checkout-layout', 'default') != 'default') {
			$actions_list[] = array(
				'action'   => array( $this, 'woocommerce_checkout_billing_footer_links' ),
				'hook'     => 'woocommerce_checkout_shipping',
				'priority' => 100
			);
			add_filter('etheme_proceed_to_checkout_button_text', function ($text) {
			    return '<span>'.$text.'</span>' . '<span class="et-icon et_b-icon et-'.(get_query_var('et_is-rtl', false) ? 'left':'right').'-arrow-2"></span>';
            });
			add_action('woocommerce_review_order_before_shipping', function () {
			  add_filter('etheme_show_chosen_shipping_method', '__return_true');
            });
		}
		
		foreach ($actions_list as $action_details) {
			if ( isset( $action_details['priority'] ) ) {
				add_action( $action_details['hook'], $action_details['action'], $action_details['priority'] );
			} else {
				add_action( $action_details['hook'], $action_details['action'] );
			}
		}
	}
	
	public function etheme_cart_checkout_header_content_separated() {
		etheme_header_wrapper_start();
		$this->etheme_cart_checkout_header_content();
		etheme_header_wrapper_end();
    }
	public function woocommerce_checkout_billing_footer_links($step = '') {
	    $step = $step ? $step : 'billing'; ?>
	   <div class="etheme-checkout-multistep-footer-links">
           <?php
                $this->return_to_shop($step);
           ?>
           <?php
                $this->next_step($step);
           ?>
       </div>
    <?php }
    public function return_to_shop($active_step = 'billing') {
	    
	    $steps_titles = array(
            'billing' => esc_html__('Return to shop', 'xstore'),
            'shipping' => esc_html__('Return to billing details', 'xstore'),
            'payment' => esc_html__('Return to shipping', 'xstore')
        );
	    $steps = array(
		    'billing',
	    );
	    if ( WC()->cart->needs_shipping() ) {
		    $steps[] = 'shipping';
	    }
	    else {
		    $steps_titles['payment'] = $steps_titles['shipping'];
	    }
	    
	    $steps[] = 'payment';
	
	    if ( $steps[0] == $active_step ) { // if first then it is billing
		    if ( wc_get_page_id( 'shop' ) > 0 ) {
			    echo '<a class="etheme-checkout-footer-step" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '"><i class="et-icon et-' . ( get_query_var( 'et_is-rtl', false ) ? 'right' : 'left' ) . '-arrow"></i><span>' . $steps_titles[$active_step] . '</span></a>';
		    }
		    return;
	    }
	    
	    $step = $steps[array_search($active_step, $steps) - 1];
//	    switch ($active_step) {
//		    case 'shipping':
//			    $step_title = esc_html__('Return to billing details', 'xstore');
//			    break;
//		    case 'payment':
//			    $step_title = esc_html__('Return to shipping', 'xstore');
//			    break;
//	    }
	    
		    echo '<a class="etheme-checkout-footer-step" data-step="'.$step.'"><i class="et-icon et-' . ( get_query_var( 'et_is-rtl', false ) ? 'right' : 'left' ) . '-arrow"></i><span>' . $steps_titles[$active_step] . '</span></a>';
	    
	}
	
	public function next_step($active_step = 'billing') {
		
		$steps_titles = array(
			'billing' => esc_html__('Continue to shipping', 'xstore'),
			'shipping' => esc_html__('Continue to payment', 'xstore'),
			'payment' => esc_html__('Place order', 'xstore')
		);
		
		$steps = array(
			'billing',
		);
		if ( WC()->cart->needs_shipping() ) {
			$steps[] = 'shipping';
		}
		else {
			$steps_titles['billing'] = $steps_titles['shipping'];
		}
		
		$steps[] = 'payment';
//		$step_title = isset($steps['shipping']) ? esc_html__('Continue to shipping', 'xstore') : esc_html__('Continue to payment', 'xstore');
//		if ( !WC()->cart->needs_shipping() && $active_step == 'billing' ) {
//		    $active_step = 'shipping'
//		}
		
		if ( $steps[count($steps)-1] == $active_step ) { // if last then it is payment
		    return;
		}
		$step = $steps[array_search($active_step, $steps) + 1];
//	    switch ($active_step) {
//		    case 'shipping':
//			    $step_title = esc_html__('Continue to payment', 'xstore');
//			    break;
//            case 'payment':
//	            $step_title = esc_html__('Place order', 'xstore');
//            break;
//	    }
	    echo '<a class="etheme-checkout-footer-step button btn black medium" data-step="'.$step.'"><span>' . $steps_titles[$active_step] . '</span><i class="et-icon et-' . ( get_query_var( 'et_is-rtl', false ) ? 'left' : 'right' ) . '-arrow-2"></i></a>';
	}
	
	public function cart_totals_shipping_html($return = false) { ob_start(); ?>
        <div class="etheme-shipping-fields">
            <table>
                <tbody>
                    <?php
                        add_filter('etheme_show_chosen_shipping_method', '__return_false');
                        if ( count(WC()->shipping()->get_packages()) ) {
	                        wc_cart_totals_shipping_html();
                        }
                        else {
	                        echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' .
	                             esc_html__( 'Sorry, it seems that there are no shipping options available. Please contact us if you require assistance or wish to make alternate arrangements.', 'xstore' ) .
                             '</li>'; // @codingStandardsIgnoreLine
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
        if ( !$return )
            echo ob_get_clean();
        else
            return ob_get_clean();
	}
	public function cart_totals_shipping_wrapper_html() {
		$is_shipping_step = $this->check_page()['is_shipping']; ?>
        <div class="etheme-shipping-fields-wrapper etheme-cart-checkout-accordion <?php if ( $is_shipping_step ) echo ' active'; ?>" data-step="shipping">
            <h3 class="accordion-title"><span><?php esc_html_e( 'Shipping', 'xstore' ); ?></span></h3>
            <div class="accordion-content"<?php if ( !$is_shipping_step ) : ?> style="display: none;" <?php endif; ?>>
                <?php $this->cart_totals_shipping_html(); ?>
                <?php $this->woocommerce_checkout_billing_footer_links('shipping'); ?>
            </div>
        </div>
    <?php }
    
	public function checkout_payment() {
	    $is_payment_step = $this->check_page()['is_payment']; ?>
        <div class="etheme-shipping-fields-wrapper etheme-cart-checkout-accordion <?php if ( $is_payment_step ) echo ' active'; ?>" data-step="payment">
            <h3 class="accordion-title"><span><?php esc_html_e( 'Payment', 'xstore' ); ?></span></h3>
            <div class="accordion-content"<?php if ( !$is_payment_step ) : ?> style="display: none;" <?php endif; ?>>
                <div class="etheme-payment-fields">
                    <?php
                        woocommerce_checkout_payment();
                    ?>
                </div>
            </div>
        </div>
    <?php }
	
	public function advanced_layout_scripts() {
		add_action( 'wp_enqueue_scripts', function (){
			// disable all styles
			$styles_remove = array(
				'back-top',
				'mobile-panel',
				'header-vertical',
				'header-search'
			);
			foreach ($styles_remove as $script){
				wp_dequeue_style($script);
			}
//                $scripts_remove = array(
//                    'fixed-header',
//                    'back-top',
//                    'mobile_panel',
//                );
//                // disable all scripts
//                foreach ($scripts_remove as $script){
//                    wp_dequeue_script($script);
//                }
		
		}, 3000);
		
		etheme_enqueue_style('special-cart-breadcrumbs');
	}
	
	public function etheme_cart_checkout_header_content(){
		get_template_part( 'templates/woocommerce/cart-checkout/header');
	}
	
	public function etheme_cart_checkout_footer_content(){
		get_template_part( 'templates/woocommerce/cart-checkout/footer');
	}
	
	public function footer_default_content() {
	    ?>
        <div class="text-center">
			<?php
			foreach (array(
				'american-express',
				'master-card',
				'paypal',
                'maestro',
				'visa'
			) as $icon) : ?>
                &nbsp;
                <img src="<?php echo ETHEME_BASE_URI.'images/woocommerce/payment-icons/'.$icon.'.jpeg'; ?>" alt="<?php echo esc_attr($icon); ?>" style="max-width: 45px; border: 1px solid var(--et_border-color); border-radius: 4px;">
                &nbsp;
			<?php endforeach; ?>
            <br/><br/>
        </div>
        <?php
	}
	
	public function sales_booster_countdown_output() {
		$countdown = $this->sales_booster_countdown();
		if ( count( $countdown ) ) :
			echo '<div ' . $countdown['attributes'] . '>' . $countdown['content'] . '</div>';
		endif;
	}
	public function sales_booster_countdown() {
		$xstore_sales_booster_settings = (array)get_option( 'xstore_sales_booster_settings', array() );
		
		$xstore_sales_booster_settings_default = array(
			'countdown_loop' => false,
			'countdown_message' => esc_html__('{fire} Hurry up, these products are limited, checkout within {timer}', 'xstore'),
			'countdown_expired_message' => esc_html__('You are out of time! Checkout now to avoid losing your order!', 'xstore'),
			'countdown_minutes' => 5,
		);
		
		$xstore_sales_booster_settings_cart_checkout_countdown = $xstore_sales_booster_settings_default;
		
		if (count($xstore_sales_booster_settings) && isset($xstore_sales_booster_settings['cart_checkout'])) {
			$xstore_sales_booster_settings = wp_parse_args($xstore_sales_booster_settings['cart_checkout'],
				$xstore_sales_booster_settings_default);
			
			$xstore_sales_booster_settings_cart_checkout_countdown = $xstore_sales_booster_settings;
		}
		
		$xstore_sales_booster_settings_cart_checkout_countdown = array(
			'countdown_loop' => $xstore_sales_booster_settings_cart_checkout_countdown['countdown_loop'],
			'countdown_message' => $xstore_sales_booster_settings_cart_checkout_countdown['countdown_message'],
			'countdown_expired_message' => $xstore_sales_booster_settings_cart_checkout_countdown['countdown_expired_message'],
			'countdown_minutes' => $xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'],
		);
		
		$xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] *= 60; // convert in secs
		$default_countdown_time = $xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'];
		
		$last_time_added = WC()->session->get( 'etheme_last_added_cart_time');
		
		$countdown = array();
		
		if ( $last_time_added ) {
			
			$now = strtotime( 'now', current_time( 'timestamp' ) );
			
			$finished = false;
			$diff     = $now - $last_time_added;
			if ( $diff > $xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] ) {
				if ( $xstore_sales_booster_settings_cart_checkout_countdown['countdown_loop'] ) {
					WC()->session->set( 'etheme_last_added_cart_time', $now );
				} else {
					$finished = true;
				}
			} else {
				$xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] = (int) $xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] - $diff;
			}
			
			$xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] = gmdate( "i:s", (int) $xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] );
			
			$attr    = array();
			$classes = array(
				'sales-booster-cart-countdown'
			);
			if ( $finished ) {
				$classes[] = 'finished';
			}
			if ( $xstore_sales_booster_settings_cart_checkout_countdown['countdown_loop'] ) {
				$classes[] = 'infinite';
				$attr[]    = 'data-time="' . gmdate( "i:s", (int) $default_countdown_time ) . '"';
			}
			$attr[]                  = 'class="' . implode( ' ', $classes ) . '"';
			$countdown['attributes'] = implode( ' ', $attr );
			ob_start();
			echo '<span class="cart-countdown-message">' . str_replace(
					array( '{timer}', '{fire}' ),
					array(
						'<span class="cart-countdown-time">' . $xstore_sales_booster_settings_cart_checkout_countdown['countdown_minutes'] . '</span>',
						'&#128293;'
					),
					$xstore_sales_booster_settings_cart_checkout_countdown['countdown_message'] ) . '</span>' .
			     '<span class="cart-countdown-expired-message">' .
			     str_replace(
				     array( '{fire}' ), array( '&#128293;' ), $xstore_sales_booster_settings_cart_checkout_countdown['countdown_expired_message'] ) . '</span>';
			$countdown['content'] = ob_get_clean();
			
			wp_enqueue_script( 'cart_checkout_countdown' );
			
		}
		
		return $countdown;
	}
	
	public function sales_booster_progress_bar() {
		
		// in case it was on different hood added where not refreshing by woocomerce ajax
        // wp_enqueue_script( 'cart_progress_bar');
		
		$element_options = array();
		$element_options['xstore_sales_booster_settings'] = (array)get_option( 'xstore_sales_booster_settings', array() );
		$element_options['xstore_sales_booster_settings_default'] = array(
			'progress_bar_message_text' => get_theme_mod( 'booster_progress_content_et-desktop', esc_html__('Spend {{et_price}} to get free shipping', 'xstore') ),
			'progress_bar_process_icon' => get_theme_mod( 'booster_progress_icon_et-desktop', 'et_icon-delivery' ),
			'progress_bar_process_icon_position' => get_theme_mod('booster_progress_icon_position_et-desktop', 'before') != 'after' ? 'before' : 'after',
			'progress_bar_price' => get_theme_mod( 'booster_progress_price_et-desktop', 350 ),
			'progress_bar_message_success_text' => get_theme_mod( 'booster_progress_content_success_et-desktop', esc_html__('Congratulations! You\'ve got free shipping.', 'xstore') ),
			'progress_bar_success_icon' => get_theme_mod( 'booster_progress_success_icon_et-desktop', 'et_icon-star' ),
			'progress_bar_success_icon_position' => get_theme_mod('booster_progress_success_icon_position_et-desktop', 'before'),
		);
		
		$element_options['xstore_sales_booster_settings_cart_checkout'] = $element_options['xstore_sales_booster_settings_default'];
		
		if ( count($element_options['xstore_sales_booster_settings']) && isset($element_options['xstore_sales_booster_settings']['cart_checkout'])) {
			$element_options['xstore_sales_booster_settings'] = wp_parse_args( $element_options['xstore_sales_booster_settings']['cart_checkout'],
				$element_options['xstore_sales_booster_settings_default'] );
			
			$element_options['xstore_sales_booster_settings_cart_checkout'] = $element_options['xstore_sales_booster_settings'];
		}
		
		$element_options['cart_options'] = array(
			'booster_progress_content' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_message_text'],
			'booster_progress_icon' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_process_icon'],
			'booster_progress_icon_position' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_process_icon_position'],
			'booster_progress_content_success' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_message_success_text'],
			'booster_progress_success_icon' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_success_icon'],
			'booster_progress_success_icon_position' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_success_icon_position'],
			'booster_progress_price' => $element_options['xstore_sales_booster_settings_cart_checkout']['progress_bar_price']
		);
		
		$amount = '';
		if ( ! wc_tax_enabled() ) {
			$amount = WC()->cart->cart_contents_total;
		} else {
			$amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;
		}
		
		$element_options['cart_options']['price_diff'] = $element_options['cart_options']['booster_progress_price'] - $amount;
		$element_options['cart_options']['price_diff'] = $element_options['cart_options']['price_diff'] > 0 ? $element_options['cart_options']['price_diff'] : 0;
		$element_options['cart_options']['cart_progress_bar_content'] = '<span class="et-cart-progress-amount" data-amount="'.$element_options['cart_options']['booster_progress_price'].'" data-currency="' . get_woocommerce_currency_symbol() . '">'.wc_price($element_options['cart_options']['price_diff']).'</span>';
		
		$percent_sold = ($amount/$element_options['cart_options']['booster_progress_price'])*100;
		$finished = false;
		if ( $amount >= $element_options['cart_options']['booster_progress_price'] )
			$finished = true;
		?>
		<div class="et-cart-progress flex justify-content-start align-items-center" data-percent-sold="<?php if ( $finished ) : echo '100'; else: echo (int)number_format($percent_sold, 3); endif; ?>">

            <?php
            $element_options['cart_options']['booster_progress_content'] = '<span>' . str_replace(array('{{et_price}}'), array($element_options['cart_options']['cart_progress_bar_content']), $element_options['cart_options']['booster_progress_content']) . '</span>';
            if ( $element_options['cart_options']['booster_progress_icon'] != 'none') {
                if ( $element_options['cart_options']['booster_progress_icon_position'] == 'before')
                    $element_options['cart_options']['booster_progress_content'] = '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $element_options['cart_options']['booster_progress_icon']).'"></span>'. $element_options['cart_options']['booster_progress_content'];
                else
                    $element_options['cart_options']['booster_progress_content'] .= '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $element_options['cart_options']['booster_progress_icon']).'"></span>';
            }
            echo '<span class="et-cart-in-progress">' . $element_options['cart_options']['booster_progress_content'] . '</span>';
            ?>

            <?php
            $element_options['cart_options']['booster_progress_content_success'] = '<span>'.$element_options['cart_options']['booster_progress_content_success'].'</span>';
            if ( $element_options['cart_options']['booster_progress_success_icon'] != 'none') {
                if ( $element_options['cart_options']['booster_progress_success_icon_position'] == 'before')
                    $element_options['cart_options']['booster_progress_content_success'] = '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $element_options['cart_options']['booster_progress_success_icon']).'"></span>'. $element_options['cart_options']['booster_progress_content_success'];
                else
                    $element_options['cart_options']['booster_progress_content_success'] .= '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $element_options['cart_options']['booster_progress_success_icon']).'"></span>';
            }
            echo '<span class="et-cart-progress-success">' . $element_options['cart_options']['booster_progress_content_success'] . '</span>';
            ?>

			<progress class="et_cart-progress-bar" max="100" value="<?php if ( $finished ) : echo '100'; else: echo (int)number_format($percent_sold, 3); endif; ?>"></progress>
			<span class="hidden cart-widget-subtotal-ghost" data-amount="<?php echo esc_attr($amount); ?>"></span>
		</div>
		<?php
	}
 
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  4.1
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
	
}