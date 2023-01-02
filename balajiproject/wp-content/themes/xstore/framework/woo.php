<?php

// @todo Remove it in 2023, use only add_filter( 'woocommerce_enqueue_styles', 'etheme_return_empty_array');
if ( etheme_woo_version_check('6.9.0')) {
    add_filter( 'woocommerce_enqueue_styles', 'etheme_return_empty_array');
} else {
   add_filter( 'woocommerce_enqueue_styles', '__return_false' );
}

function etheme_woo_version_check($required = '7.0.1') {
    return defined('WC_VERSION') && version_compare( WC_VERSION, $required, '>=' );
}

function etheme_return_empty_array(){
    return array();
};

add_filter( 'pre_option_woocommerce_enable_lightbox', 'return_no' ); // Remove woocommerce prettyphoto

function return_no( $option ) {
	return 'no';
}

// **********************************************************************// 
// ! Template hooks
// **********************************************************************//

add_action( 'wp', 'etheme_template_hooks', 60 );
if ( ! function_exists( 'etheme_template_hooks' ) ) {
	function etheme_template_hooks() {

		if (
		        get_query_var( 'etheme_single_product_builder', false ) ) {
			
			add_action( 'woocommerce_product_meta_start', function () {
				$class = 'et-ghost-' . ( etheme_get_option( 'product_meta_direction_et-desktop', 'column' ) == 'column' ? 'block' : 'inline-block' );
				echo '<span class="hidden ' . $class . '"></span>';
			}, 1 );
			
			if ( etheme_get_option( 'product_navigation_et-desktop', 1 ) ) {
				add_action( 'woocommerce_after_single_product', 'etheme_project_links', 10 );
			}
			
		}

        else {
            add_action( 'woocommerce_after_single_product_summary', 'etheme_bought_together', 30 );
        }
		// wc demo store 
		remove_action( 'wp_footer', 'woocommerce_demo_store' );
		add_action( 'et_after_body', 'woocommerce_demo_store', 2 );
		
		// uses in plugin also 
		add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
		add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
		
		add_filter( 'woocommerce_product_description_heading', '__return_false' );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
		
		// add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 40 ); // add pagination above the products
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		
		add_action( 'woocommerce_before_shop_loop', function () {
			if ( wc_get_loop_prop( 'is_shortcode' ) ) {
				etheme_shop_filters_sidebar();
			}
		}, 100000 );
		
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
		
		if ( etheme_get_option('cart_content_view_cart_button_et-desktop', false) ) {
			add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
		}
		
		// ! Change single product main gallery image size
		add_filter( 'woocommerce_gallery_image_size', function ( $size ) {
			return 'woocommerce_single';
		} );
		
		if ( ! get_query_var('et_sb_infinite_scroll', false) ) {
			add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 5 );
		}
		
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		
		/* Remove link open and close on product content */
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		
		
		if ( ! get_query_var( 'etheme_single_product_builder' ) ) {
			// Change rating position on the single product page
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
			add_action( 'woocommerce_share', 'etheme_product_share', 50 );
			
			$single_layout        = etheme_get_option( 'single_layout', 'default' );
			$single_layout_custom = etheme_get_custom_field( 'single_layout' );
			
			if ( etheme_get_option( 'tabs_location', 'after_content' ) == 'after_image' && etheme_get_option( 'tabs_type', 'tabs-default' ) != 'disable' && $single_layout != 'large' ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 61 );
				add_filter( 'et_option_tabs_type', function () {
					return "accordion";
				} );
				if ( etheme_get_option( 'reviews_position', 'tabs' ) == 'outside' ) {
					add_action( 'woocommerce_single_product_summary', 'comments_template', 110 );
				}
			}
			
			if ( ( $single_layout == 'fixed' && ! in_array( $single_layout_custom, array(
						'small',
						'default',
						'xsmall',
						'large',
						'center',
						'wide',
						'right',
						'booking'
					) ) ) || $single_layout_custom == 'fixed' ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			} else if ( ( $single_layout == 'center' && $single_layout_custom == 'standard' ) || $single_layout_custom == 'center' ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			} else if ( ( $single_layout == 'wide' && $single_layout_custom == 'standard' ) ||
			            $single_layout_custom == 'wide' ||
			            ( $single_layout == 'right' && $single_layout_custom == 'standard' ) ||
			            $single_layout_custom == 'right' ) {
				if ( is_singular( 'product' ) ) {
					remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
				}
				remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
				add_action( 'woocommerce_single_product_summary', 'wc_print_notices', 1 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );
				add_action( 'woocommerce_single_product_summary', 'etheme_size_guide', 21 );
			} else if ( ( $single_layout == 'booking' && $single_layout_custom == 'standard' ) || $single_layout_custom == 'booking' ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
				if ( etheme_get_option( 'tabs_location', 'after_content' ) == 'after_image' && etheme_get_option( 'tabs_type', 'tabs-default' ) != 'disable' ) {
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 61 );
				}
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
                add_action( 'woocommerce_single_product_summary', 'etheme_size_guide', 21 );
			} else {
				// Add product categories after title
				add_action( 'woocommerce_single_product_summary', 'etheme_size_guide', 21 );
			}
			
			if ( etheme_get_option( 'reviews_position', 'tabs' ) == 'outside' ) {
				add_filter( 'woocommerce_product_tabs', 'etheme_remove_reviews_from_tabs', 98 );
				add_action( 'woocommerce_after_single_product_summary', 'comments_template', 30 );
				// maybe replace with origin wp_is_mobile()
				if ( get_theme_mod('product_reviews_collapsed_et-mobile', false) && get_query_var('is_mobile', false) )
				    add_action( 'woocommerce_after_single_product_summary', 'etheme_comments_template_mobile_button', 25 );
			}
			
			if ( get_option( 'yith_wcwl_button_position' ) == 'shortcode' ) {
				add_action( 'woocommerce_after_add_to_cart_button', 'etheme_wishlist_btn', 30 );
			}
		}
		if ( in_array( etheme_get_custom_field( 'sale_counter' ), array('single', 'single_list', 'all') ) ) {
			if ( ! get_query_var( 'etheme_single_product_builder' ) ) {
				add_action( 'woocommerce_single_product_summary', 'etheme_product_countdown', 29 );
			}
			else {
				add_action( 'etheme_woocommerce_template_single_add_to_cart', 'etheme_product_countdown', 5 );
			}
		}
		if ( etheme_get_option( 'enable_brands', 1 ) && etheme_get_option( 'show_brand', 1 ) && etheme_get_option( 'brands_location', 'sidebar' ) == 'content' ) {
			if ( ! get_query_var( 'etheme_single_product_builder' ) ) {
				add_action( 'woocommerce_single_product_summary', 'etheme_single_product_brands', 11 );
			} else {
				add_action( 'etheme_woocommerce_template_single_excerpt', 'etheme_single_product_brands', 9 );
			}
		}
		if ( etheme_get_option( 'enable_brands',1 ) && etheme_get_option( 'show_brand' , 1) && etheme_get_option( 'brands_location', 1 ) == 'under_content' ) {
			add_action( 'woocommerce_product_meta_start', 'etheme_single_product_brands', 2 );
		}
		
		remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
		
		/* Increase avatar size for reviews on product page */
		
		add_filter( 'woocommerce_review_gravatar_size', function () {
			return 80;
		}, 30 );
		
		// ! Remove empty cart message
		remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
		
		// Pagination shop
		add_filter( 'woocommerce_pagination_args', 'et_woocommerce_pagination' );
		
		add_action( 'template_redirect', 'et_wc_track_product_view', 20 );
		
		// 360 view plugin
		if ( class_exists( 'SmartProductPlugin' ) ) {
			remove_filter( 'woocommerce_single_product_image_html', array(
				'SmartProductPlugin',
				'wooCommerceImage'
			), 999, 2 );
		}
		
		remove_action( 'woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10 );
		add_action( 'woocommerce_widget_shopping_cart_total', 'etheme_woocommerce_widget_shopping_cart_subtotal', 10 );
		
        if ( get_query_var('et_product-advanced-stock', false) ) {
		    add_filter( 'woocommerce_get_stock_html', 'etheme_advanced_stock_status_html', 2, 10);
        }

        if (! etheme_get_option( 'show_single_stock', 0 )){
            add_filter( 'woocommerce_get_stock_html', '__return_empty_string', 2, 100);
        }

//        if ( apply_filters('etheme_single_product_variation_gallery', get_query_var( 'etheme_single_product_variation_gallery', false ) ) ) {
		add_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
//		add_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		
		add_action( 'woocommerce_admin_process_variation_object', 'et_clear_default_variation_transient_by_variation' );
		add_action( 'woocommerce_delete_product_transients', 'et_clear_default_variation_transient_by_product' );
		
		// this add variation gallery filters at loop start and remove it after loop end
		add_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
		add_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
//        }
		
		add_filter( 'woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2 );
		
		add_action( 'et_woocomerce_mini_cart_footer', 'et_woocomerce_mini_cart_footer', 10 );
		
		if ( ! get_query_var( 'etheme_single_product_builder' ) ) {
			add_action( 'woocommerce_single_product_summary', 'etheme_dokan_seller', 11 );
		} else {
			add_action( 'etheme_woocommerce_template_single_excerpt', 'etheme_dokan_seller', 9 );
		}
		
		// set 4 columns for all products output in wc-tabs
		add_action( 'woocommerce_product_tabs', 'etheme_set_loop_props_product_tabs', 1 );
		
		add_action('woocommerce_product_after_tabs', 'etheme_reset_loop_props_product_tabs', 99999);
		
		add_filter('wcfm_store_wrapper_class', function($classes) {
			return $classes . ' container';
		});
		
		add_action('etheme_page_heading', 'et_cart_checkout_breadcrumbs', 20);
		
		add_filter('woocommerce_breadcrumb_main_term', 'etheme_breadcrumbs_primary_category', 10, 2);

		if ( get_query_var('et_is-catalog', false) && etheme_get_option( 'just_catalog_price', 0 ) && etheme_get_option( 'ltv_price', esc_html__( 'Login to view price', 'xstore' ) ) == '' ){
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		}
		
		// plugins compatibles
//        add_filter('wcmp_load_default_vendor_list', '__return_true');
//		add_filter('wcmp_load_default_vendor_store', '__return_true');
        
        add_action('wcmp_before_main_content', 'woocommerce_breadcrumb');
        add_action('wcmp_before_main_content', function(){
            echo '<div class="container content-page products-hover-only-icons">';
        });
		
		add_action( 'wcmp_after_main_content', function (){
		    echo '</div>';
        } );
		
		add_filter('wcmp_show_page_title', '__return_false');
		
		add_filter('theme_mod_product_gallery_type_et-desktop', function($value) {
			// maybe replace with origin wp_is_mobile()
			return in_array($value, array('one_image', 'double_image')) && get_query_var('is_mobile', false) ? 'thumbnails_bottom' : $value;
		});
		
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
		add_action( 'woocommerce_widget_shopping_cart_buttons', 'etheme_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
		
		add_filter('woocommerce_grouped_product_columns', function ($columns) {
		    return array(
			    'label',
                'price',
                'quantity'
            );
		});
		
		// makes separator between product categories & products for cases they are shown together and option is enabled
        add_filter( 'woocommerce_after_output_product_categories', function ( $value ) {
            if ( wc_get_loop_prop( 'is_shortcode' ) && ! WC_Template_Loader::in_content_filter() ) {
                return $value;
            }
            if ( get_option( 'woocommerce_shop_page_categories_appearance', '' ) ) {
                if ( woocommerce_get_loop_display_mode() == 'both' ) {
                    return '<div class="etheme-woocommerce-categories-products-separator"></div>';
                }
            }
            
            return $value;
        } );
        $fake_live_viewing = get_option('xstore_sales_booster_settings_fake_live_viewing');
        $fake_product_sales = get_option('xstore_sales_booster_settings_fake_product_sales');
        if ( $fake_live_viewing || $fake_product_sales ) {
            $is_singular = is_singular('product');
            $product_id = get_the_ID();
             if ( $fake_live_viewing && $is_singular ) {
    
                $rendered_string = etheme_set_fake_live_viewing_count($product_id);
    
                $single_product_builder_hook_for_fake_live_viewing = apply_filters('single_product_builder_hook_for_fake_live_viewing', 'etheme_woocommerce_template_single_excerpt');
                add_action( $single_product_builder_hook_for_fake_live_viewing, function ($id) use ($rendered_string) {
                    echo '<p class="sales-booster-live-viewing">' . $rendered_string . '</p>';
                }, apply_filters('single_product_builder_hook_priority_for_fake_live_viewing', 5));
                add_action( 'woocommerce_single_product_summary', function ($id) use ($rendered_string) {
                    echo '<p class="sales-booster-live-viewing">' . $rendered_string . '</p>';
                }, 21 );
            }
             
             if ( $fake_product_sales ) {

                 if ( $is_singular ) {
                    $rendered_string = etheme_get_fake_product_sales_count($product_id);
                    if ( $rendered_string ) {
                        $single_product_builder_hook_for_fake_live_viewing = apply_filters('single_product_builder_hook_for_fake_product_sales', 'etheme_woocommerce_template_single_excerpt');
                        add_action( $single_product_builder_hook_for_fake_live_viewing, function ($id) use ($rendered_string) {
                            echo '<p class="sales-booster-total-sales">' . $rendered_string . '</p>';
                        }, apply_filters('single_product_builder_hook_priority_for_fake_product_sales', 5));
                        add_action( 'woocommerce_single_product_summary', function ($id) use ($rendered_string) {
                            echo '<p class="sales-booster-total-sales">' . $rendered_string . '</p>';
                        }, 21 );
                    }
                }
                
                
                 // if show on shop page @todo
                //Display the Sales Count in other pages.
                $xstore_sales_booster_settings = (array)get_option('xstore_sales_booster_settings', array());

                $xstore_sales_booster_settings_default = array(
                    'message' => esc_html__('{fire} {count} items sold in {hours} hours', 'xstore'),
                    'sales_type' => 'fake',
                    'show_on_shop' => false,
                    'min_count' => 3,
                    'max_count' => 12,
                    'hours' => 12,
                    'transient_hours' => 24
                );
        
                $xstore_sales_booster_settings_fake_product_sales = $xstore_sales_booster_settings_default;
        
                if (count($xstore_sales_booster_settings) && isset($xstore_sales_booster_settings['fake_product_sales'])) {
                    $xstore_sales_booster_settings = wp_parse_args($xstore_sales_booster_settings['fake_product_sales'],
                        $xstore_sales_booster_settings_default);
        
                    $xstore_sales_booster_settings_fake_product_sales = $xstore_sales_booster_settings;
                }
                
                if ( !!$xstore_sales_booster_settings_fake_product_sales['show_on_shop'] ) {
                    add_action( 'woocommerce_after_shop_loop_item_title', function () {
                        global $product;
                        $local_rendered_string = etheme_get_fake_product_sales_count($product->get_ID());
                        if ( $local_rendered_string )
                            {echo '<p class="sales-booster-total-sales">' . $local_rendered_string . '</p>';}
                    }, 3 );
                    
                    // Elementor product grid / product carousel widgets
                    add_action('after_etheme_product_grid_list_product_element_title', function () {
                        global $product;
                        $local_rendered_string = etheme_get_fake_product_sales_count($product->get_ID());
                        if ( $local_rendered_string )
                            {echo '<p class="sales-booster-total-sales">' . $local_rendered_string . '</p>';}
                    });
                }
                
            }
            
        }
		
		$cart_checkout = Etheme_WooCommerce_Cart_Checkout::get_instance();

        if ( get_option('xstore_sales_booster_settings_cart_checkout_progress_bar') ) {
//            $cart_checkout_class = Etheme_WooCommerce_Cart_Checkout::get_instance();
            $action = 'woocommerce_before_cart_table';
            if ( get_query_var('et_is-cart-checkout-advanced', false) )
                $action = 'etheme_woocommerce_before_cart_form';
            add_action($action, array($cart_checkout, 'sales_booster_progress_bar'), 3);
        }

        add_action( 'woocommerce_proceed_to_checkout', 'etheme_woocommerce_continue_shopping', 21 );
        
        add_filter('woocommerce_order_button_html', function ($button_html) use ($cart_checkout) {
            if ( !(get_theme_mod('cart_checkout_advanced_layout', false) && get_theme_mod('cart_checkout_layout_type', 'default') != 'default') )
                return $button_html;
		    ob_start();
		    ?>
            <div class="etheme-checkout-multistep-footer-links">
				<?php
				$cart_checkout->return_to_shop('payment');
            return ob_get_clean() . str_replace('</button>', ' <i class="et-icon et-' . ( get_query_var( 'et_is-rtl', false ) ? 'left' : 'right' ) . '-arrow"></i></button>', $button_html) . '</div>';
        });
        
        if ( get_query_var('et_is-cart-checkout-advanced', false) ) {

            if ( get_query_var('et_cart-checkout-layout', 'default') != 'default' )
                add_filter('etheme_form_billing_title', '__return_false');
            
            $cart_checkout->advanced_layout();
        
        }
	}
}

if ( !function_exists('etheme_sales_booster_cart_checkout_progress_bar') ) {
    function etheme_sales_booster_cart_checkout_progress_bar() {
        etheme_enqueue_style( 'sale-booster-cart-checkout-progress-bar', true );
        $cart_checkout = Etheme_WooCommerce_Cart_Checkout::get_instance();
        ob_start();
        echo '<div class="etheme_sales_booster_progress_bar_shortcode">';
            $cart_checkout->sales_booster_progress_bar();
        echo '</div>';
        return ob_get_clean();
    }
}

if ( !function_exists('etheme_comments_template_mobile_button') ) {
    function etheme_comments_template_mobile_button() {
        ?>
	<button class="btn medium open-reviews" data-open-text="<?php echo esc_attr('Open reviews', 'xstore'); ?>"
	data-close-text="<?php echo esc_attr('Close reviews', 'xstore'); ?>">
	    <?php echo esc_html__('Open Reviews', 'xstore'); ?>
	</button>
	<?php
    }
}

$cart_checkout = Etheme_WooCommerce_Cart_Checkout::get_instance();
add_filter('woocommerce_update_order_review_fragments', function ($fragments) use ($cart_checkout)  {
	$fragments['.etheme-shipping-fields'] = $cart_checkout->cart_totals_shipping_html(true);
	return $fragments;
});

add_action( 'wp_ajax_etheme_update_cart_checkout_progress_bar', array($cart_checkout, 'update_progress_bar') );
add_action( 'wp_ajax_nopriv_etheme_update_cart_checkout_progress_bar', array($cart_checkout, 'update_progress_bar') );

add_action( 'wp_ajax_etheme_bought_together_price', 'etheme_bought_together_price');
add_action( 'wp_ajax_nopriv_etheme_bought_together_price', 'etheme_bought_together_price');

if ( !function_exists('etheme_bought_together_price') ) {
    function etheme_bought_together_price() {
        if ( !isset($_POST['total_price'])) die();

        wp_send_json(array(
                'price_html' => wc_price($_POST['total_price'])
            ));
    }
}

if ( !function_exists('et_get_product_bought_together_ids') ) {
    function et_get_product_bought_together_ids($product) {
        $product_id = $product->get_id();
        $bought_together_ids = get_post_meta($product_id, '_et_bought_together_ids', true);
        $bought_together_ids = $bought_together_ids != '' ? $bought_together_ids : array();
        return apply_filters('etheme_product_bought_together_ids', (array)maybe_unserialize($bought_together_ids), $product);
    }
}

if ( !function_exists('etheme_bought_together') ) {
    function etheme_bought_together($args = array()) {
        $args = wp_parse_args( (array) $args, array(
            'title' => esc_html__('Frequently bought together', 'xstore'),
            'large'           => 3,
            'mobile'          => 2,
        ) );
        wc_get_template('single-product/bought-together.php', $args);
    }
}

add_filter('woocommerce_add_to_cart_handler', function ($type) {
    // add all products just like default grouped product makes
    if ( isset($_POST['et_bought_together_add_to_cart'])) {
        return 'grouped';
    }
    return $type;
});

if( ! function_exists('etheme_is_catalog') ){
	function etheme_is_catalog(){
		if ( etheme_get_option( 'just_catalog', 0 ) ){
			if ( etheme_get_option( 'just_catalog_type', 'all' ) == 'unregistered' && is_user_logged_in()){
				return false;
			} else {
				return true;
			}
		}
		return false;
	}
}

if ( !function_exists('etheme_breadcrumbs_primary_category') ) {
	function etheme_breadcrumbs_primary_category( $term, $terms ) {
		global $post;
		$cat = etheme_get_custom_field( 'primary_category' );
		if ( ! empty( $cat ) && $cat != 'auto' ) {
			$new_term = get_term_by( 'slug', $cat, 'product_cat' );
			if ( $new_term ) {
				return $new_term;
			}
		}
		
		return $term;
	}
}

// @todo could be in core
if(!function_exists('etheme_products_menu_layout')) {
	function etheme_products_menu_layout($args,$title = false, $columns = 4, $img_pos = 'left', $extra = array(), $is_preview = false ){
		global $wpdb, $woocommerce_loop;
		$output = '';
		
		if ( isset( $extra['navigation'] ) && $extra['navigation'] != 'off' ){
			$args['no_found_rows'] = false;
			$args['posts_per_page'] = $extra['per-page'];
		}

//		$variable_products_detach = etheme_get_option('variable_products_detach', false);
//		if ( $variable_products_detach ) {
//			$variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);
//            $args['post_type'][] = 'product_variation';
//			$args['post_type'] = array_unique($args['post_type']);
//			$posts_not_in = etheme_product_variations_excluded();
//			if ( array_key_exists('post__not_in', $args) ) {
//				$args['post__not_in'] = array_unique( array_merge((array)$args['post__not_in'], $posts_not_in) );
//			}
//			else {
//				$args['post__not_in'] = array_unique( $posts_not_in );
//			}
//			// hides all variable products
//			if ( $variable_products_no_parent ) {
//				$args['tax_query'][] = array(
//					array(
//						'taxonomy' => 'product_type',
//						'field'    => 'slug',
//						'terms'    => 'variable',
//						'operator' => 'NOT IN',
//					),
//				);
//			}
//		}
		
		$products = new WP_Query( $args );
		
		wc_setup_loop( array(
			'columns'      => $columns,
			'name'         => 'product',
			'is_shortcode' => true,
			'total'        => $args['posts_per_page'],
		) );
		
		if ( $products->have_posts() ) :
			if ( wc_get_loop_prop( 'total' ) ) {
//                etheme_enqueue_style( 'product-view-menu', true );
				if ( $title != '' ) {
					echo '<h2 class="products-title"><span>' . esc_html( $title ) . '</span></h2>';
				}
				?>
				<?php woocommerce_product_loop_start(); ?>
				
				<?php while ( $products->have_posts() ) : $products->the_post();
					global $product;
					$product_type = $product->get_type();
					$local_options = array();
					$local_options['classes'] = get_post_class();
					$local_options['classes'][] = 'product';
					$local_options['classes'][] = 'product-view-menu';
					$local_options['classes'][] = etheme_get_product_class( $columns );
					$local_options['thumb_id'] = get_post_thumbnail_id();
					$local_options['url'] = $product->get_permalink();
					$local_options['excerpt'] = get_the_excerpt();
//					if ( $variable_products_detach && $product_type == 'variation') {
//						$custom_excerpt = $product->get_description();
//						if ( !empty($custom_excerpt)) {
//							$local_options['excerpt'] = $custom_excerpt;
//						}
//					}
					if ( $woocommerce_loop['excerpt_length'] > 0 && strlen($local_options['excerpt']) > 0 && ( strlen($local_options['excerpt']) > $woocommerce_loop['excerpt_length'])) {
						$local_options['excerpt']         = substr($local_options['excerpt'],0,$woocommerce_loop['excerpt_length']) . '...';
					}
					
					?>
                    <div <?php post_class($local_options['classes']); ?>>
                        <div class="content-product">
							<?php
							/**
							 * woocommerce_before_shop_loop_item hook.
							 *
							 * @hooked woocommerce_template_loop_product_link_open - 10
							 */
							remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
							remove_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10);
							do_action( 'woocommerce_before_shop_loop_item' );
							add_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10);
							add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
							
							// ! Remove image from title action
							remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
							
							if ( $woocommerce_loop['show_image']) :
								
								$woocommerce_loop['size'] = empty($woocommerce_loop['size']) ? '120x120' : $woocommerce_loop['size'];
							
							endif;
							
							if ( $woocommerce_loop['show_image'] && $img_pos != 'right' ) :
								
								?>

                                <a class="product-content-image" href="<?php echo esc_url($local_options['url']); ?>">
									<?php
									if ( $local_options['thumb_id'] ) {
										$local_options['img'] = etheme_get_image( $local_options['thumb_id'], $woocommerce_loop['size'] );
										echo !empty($local_options['img']) ? $local_options['img'] : wc_placeholder_img();
									}
									else
										echo wc_placeholder_img();
									
									do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
                                </a>
							
							<?php endif; ?>

                            <div class="product-details">
								
								<?php
								
								if ( $woocommerce_loop['show_category']) :
									
									$cat  = etheme_get_custom_field( 'primary_category', ( $product_type == 'variation') ? $product->get_parent_id() : false );
									
									if ( ! empty( $cat ) && $cat != 'auto' ) {
										$primary = get_term_by( 'slug', $cat, 'product_cat' );
										if ( ! is_wp_error( $primary ) ) {
											$term_link = get_term_link( $primary );
											if ( ! is_wp_error( $term_link ) ) {
												echo '<span class="category"><a href="' . esc_url( $term_link ) . '">' . $primary->name . '</a></span>';
											}
										}
									} else {
										echo '<span class="category">' . wc_get_product_category_list( $product->get_id(), ' ' ) . '</span>';
									}
								
								endif;
								?>

                                <div class="product-main-details">

                                    <p class="product-title">
                                        <a href="<?php echo esc_url($local_options['url']); ?>"><?php
											//					                            if ( $variable_products_detach && $product_type == 'variation' ) {
											//					                                echo wp_specialchars_decode($product->get_name());
											//                                                }
											//					                            else {
											echo wp_specialchars_decode( get_the_title() );
											// } ?>
                                        </a>
                                    </p>

                                    <span class="separator"></span>
									
									<?php woocommerce_template_loop_price(); ?>

                                </div>
								
								<?php if ( $woocommerce_loop['show_excerpt']) : ?>

                                    <div class="product-info-details">

                                        <div class="product-excerpt">
											<?php echo do_shortcode($local_options['excerpt']); ?>
                                        </div>

                                        <span style="visibility: hidden">
			                                    <?php woocommerce_template_loop_price(); ?>
			                                </span>

                                    </div>
								
								<?php endif; ?>

                            </div>
							
							<?php if ( $woocommerce_loop['show_image'] && $img_pos == 'right' ) : ?>

                                <a class="product-content-image" href="<?php echo esc_url($local_options['url']); ?>">
									<?php
									if ( $local_options['thumb_id'] ) {
										$local_options['img'] = etheme_get_image( $local_options['thumb_id'], $woocommerce_loop['size'] );
										echo !empty($local_options['img']) ? $local_options['img'] : wc_placeholder_img();
									}
									else
										echo wc_placeholder_img();
									
									do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
                                </a>
							
							<?php endif; ?>
							
							<?php
							add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
							?>

                        </div>
                    </div>
				<?php
				
				endwhile; // end of the loop. ?>
				
				<?php woocommerce_product_loop_end(); ?>
			<?php } ?>
		<?php endif;
		
		wp_reset_postdata();
		wc_reset_loop();
		
		// ! Do it for load more button
		if ( isset( $extra['navigation'] ) && $extra['navigation'] != 'off' ) {
			if ( $products->max_num_pages > 1 && $extra['limit'] > $extra['per-page'] ) {
				$attr = 'data-paged="1"';
				$attr .= ' data-max-paged="' . $products->max_num_pages . '"';
				
				if ( isset( $extra['limit'] ) && $extra['limit'] != -1 ) {
					$attr .= ' data-limit="' . $extra['limit'] . '"';
				}
				
				$ajax_nonce = wp_create_nonce( 'etheme_products' );
				
				$attr .= ' data-nonce="' . $ajax_nonce . '"';
				
				$type = ( $extra['navigation'] == 'lazy' ) ? 'lazy-loading' : 'button-loading';
				
				$output .= '
		        <div class="et-load-block text-center et_load-products ' . $type . '">
		        	' . etheme_loader(false, 'no-lqip') . '
		        	<span class="btn">
		        		<a ' . $attr . '>' . esc_html__( 'Load More', 'xstore' ) . '</a>
		        	</span>
		        </div>';
			}
		}
		if ( $is_preview )
			$output .= '<script>jQuery(document).ready(function(){
                    etTheme.swiperFunc();
			        etTheme.secondInitSwipers();
			        etTheme.global_image_lazy();
			        if ( etTheme.contentProdImages !== undefined )
	                    etTheme.contentProdImages();
                    if ( window.hoverSlider !== undefined ) { 
			            window.hoverSlider.init({});
                        window.hoverSlider.prepareMarkup();
                    }
			        if ( etTheme.countdown !== undefined )
	                    etTheme.countdown();
			        etTheme.customCss();
			        etTheme.customCssOne();
			        if ( etTheme.reinitSwatches !== undefined )
	                    etTheme.reinitSwatches();
                });</script>';
		
		return $output;
	}
}

// **********************************************************************//
// ! Create products grid by args
// **********************************************************************//
if(!function_exists('etheme_products')) {
	function etheme_products($args,$title = false, $columns = 4, $extra = array() ){
		global $wpdb, $woocommerce_loop;
		$output = '';
		
		if ( isset( $extra['navigation'] ) && $extra['navigation'] != 'off' ){
			$args['no_found_rows'] = false;
			$args['posts_per_page'] = $extra['per-page'];
		}

//	    $variable_products_detach = etheme_get_option('variable_products_detach', false);
//	    if ( $variable_products_detach ) {
//		    $variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);
//            $args['post_type'][] = 'product_variation';
//		    $posts_not_in = etheme_product_variations_excluded();
//		    if ( array_key_exists('post__not_in', $args) ) {
//			    $args['post__not_in'] = array_unique( array_merge((array)$args['post__not_in'], $posts_not_in) );
//		    }
//		    else {
//			    $args['post__not_in'] = array_unique( $posts_not_in );
//		    }
//		    // hides all variable products
//		    if ( $variable_products_no_parent ) {
//			    $args['tax_query'][] = array(
//				    array(
//					    'taxonomy' => 'product_type',
//					    'field'    => 'slug',
//					    'terms'    => 'variable',
//					    'operator' => 'NOT IN',
//				    ),
//			    );
//		    }
//	    }
		
		$products = new WP_Query( $args );
		
		wc_setup_loop( array(
			'columns'      => $columns,
			'name'         => 'product',
			'is_shortcode' => true,
			'total'        => $args['posts_per_page'],
		) );
		
		if ( $products->have_posts() ) :
			if ( wc_get_loop_prop( 'total' ) ) {
				if ( $title != '' ) {
					echo '<h2 class="products-title"><span>' . esc_html( $title ) . '</span></h2>';
				}
				?>
				<?php woocommerce_product_loop_start(); ?>
				
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					
					<?php $output .= wc_get_template_part( 'content', 'product' ); ?>
				
				<?php endwhile; // end of the loop. ?>
				
				<?php woocommerce_product_loop_end(); ?>
			<?php } ?>
		<?php endif;
		
		wp_reset_postdata();
		wc_reset_loop();
		
		// ! Do it for load more button
		if ( isset( $extra['navigation'] ) && $extra['navigation'] != 'off' ) {
			if ( $products->max_num_pages > 1 && $extra['limit'] > $extra['per-page'] ) {
				$attr = 'data-paged="1"';
				$attr .= ' data-max-paged="' . $products->max_num_pages . '"';
				
				if ( isset( $extra['limit'] ) && $extra['limit'] != -1 ) {
					$attr .= ' data-limit="' . $extra['limit'] . '"';
				}
				
				$ajax_nonce = wp_create_nonce( 'etheme_products' );
				
				$attr .= ' data-nonce="' . $ajax_nonce . '"';
				
				$type = ( $extra['navigation'] == 'lazy' ) ? 'lazy-loading' : 'button-loading';
				
				$output .= '
		        <div class="et-load-block text-center et_load-products ' . $type . '">
		        	' . etheme_loader(false, 'no-lqip') . '
		        	<span class="btn">
		        		<a ' . $attr . '>' . esc_html__( 'Load More', 'xstore' ) . '</a>
		        	</span>
		        </div>';
			}
		}
		return $output;
	}
}

if( wp_doing_ajax() ){
	add_action( 'wp_ajax_etheme_ajax_products', 'etheme_ajax_products');
	add_action( 'wp_ajax_nopriv_etheme_ajax_products', 'etheme_ajax_products');
}

if(!function_exists('etheme_ajax_products')) {
	function etheme_ajax_products( $args = array() ){
		if( isset( $_POST['_wpnonce'] ) ) return;
		if( $_POST['context'] !== 'frontend' ) return;
		
		global $wpdb, $woocommerce_loop;
		
		$attr = array();
		$attr = $_POST['attr'];
		$output = '';
		$args = Array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows' => 1,
			'posts_per_page' => $attr['per-page'],
			'paged' => $attr['paged'],
			'orderby' =>'',
			'order' => 'ASC',
		);

//	    $variable_products_detach = etheme_get_option('variable_products_detach', false);
//	    if ( $variable_products_detach ) {
//		    $variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);
//		    $args['post_type'][] = 'product_variation';
//		    $posts_not_in = etheme_product_variations_excluded();
//		    if ( array_key_exists('post__not_in', $args) ) {
//			    $args['post__not_in'] = array_unique( array_merge((array)$args['post__not_in'], $posts_not_in) );
//		    }
//		    else {
//			    $args['post__not_in'] = array_unique( $posts_not_in );
//		    }
//		    // hides all variable products
//		    if ( $variable_products_no_parent ) {
//			    $args['tax_query'][] = array(
//				    array(
//					    'taxonomy' => 'product_type',
//					    'field'    => 'slug',
//					    'terms'    => 'variable',
//					    'operator' => 'NOT IN',
//				    ),
//			    );
//		    }
//	    }
		
		if ( $attr['orderby'] ) {
			$args['orderby'] = $attr['orderby'];
		} else {
			$args['orderby'] = '';
		}
		
		if ( $attr['order'] ) {
			$args['order'] = $attr['order'];
		} else {
			$args['order'] = 'ASC';
		}
		
		if ( isset( $attr['stock'] ) ) {
			$args['meta_query'] = array(
				array (
					'key' => '_stock_status',
					'value' => 'instock',
					'compare' => '='
				),
			);
		}
		
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'hidden',
			'operator' => 'NOT IN',
		);
		
		if (  isset( $attr['type'] ) ) {
			switch ($attr['type']) {
				case 'featured':
					$args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					);
					break;
				case 'bestsellings':
					$args['meta_key'] = 'total_sales';
					$args['orderby'] = 'meta_value_num';
					break;
				default:
					
					break;
			}
		}
		
		if ($attr['orderby'] == 'price') {
			$args['meta_key'] = '_price';
			$args['orderby'] = 'meta_value_num';
		}
		
		if ( isset($attr['ids']) ) {
			$ids = explode( ',', $attr['ids'] );
			$ids = array_map('trim', $ids);
			$args['post__in'] = $ids;
		}
		
		// Narrow by categories
		if( ! empty( $attr['taxonomies'] ) ) {
			$taxonomy_names = get_object_taxonomies( 'product' );
			$terms = get_terms( $taxonomy_names, array(
				'orderby' => 'name',
				'include' => $attr['taxonomies']
			));
			
			if( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$args['tax_query'] = array('relation' => 'OR');
				foreach ($terms as $key => $term) {
					$args['tax_query'][] = array(
						'taxonomy' => $term->taxonomy,
						'field' => 'slug',
						'terms' => array( $term->slug ),
						'include_children' => true,
						'operator' => 'IN'
					);
				}
			}
		}
		
		$products = new WP_Query( $args );
		$class = '';
		
		wc_setup_loop( array(
			'columns'      => $attr['columns'],
			'name'         => 'product',
			'is_shortcode' => true,
			'total'        => $args['posts_per_page'],
		) );
		
		if ( isset( $attr['limit'] ) ) {
			$_i = 0;
		}
		
		if ( isset($attr['product_view']) )
			$woocommerce_loop['product_view'] = $attr['product_view'];
		if ( isset($attr['custom_template']) )
			$woocommerce_loop['custom_template'] = $attr['custom_template'];
		if ( isset($attr['product_view_color']) )
			$woocommerce_loop['product_view_color'] = $attr['product_view_color'];
		if ( isset($attr['hover']) )
			$woocommerce_loop['hover'] = $attr['hover'];
		if ( isset($attr['size']) )
			$woocommerce_loop['size'] = $attr['size'];
		if ( isset($attr['show_counter']) )
			$woocommerce_loop['show_counter'] = $attr['show_counter'];
		if ( isset($attr['show_stock']) )
			$woocommerce_loop['show_stock'] = $attr['show_stock'];
		
		if ( isset($attr['product_content_elements']) ) {
			$product_content_elements = explode(',', $attr['product_content_elements']);
			$woocommerce_loop['product_content_elements'] = $product_content_elements;
			if ( in_array('product_page_product_excerpt', $product_content_elements) ) {
				$woocommerce_loop['show_excerpt']       = true;
			}
			if ( isset($attr['product_add_to_cart_quantity']))
			    $woocommerce_loop['product_add_to_cart_quantity'] = $attr['product_add_to_cart_quantity'];
		}
		
		$woocommerce_loop['loading_class'] = 'productAnimated product-fade';
		
		if ( $products->have_posts() ) :
			if ( wc_get_loop_prop( 'total' ) ) {
				
				?>
				<?php woocommerce_product_loop_start(false); ?>
				
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					
					<?php
					if ( isset( $attr['limit'] ) ) {
						if ( $_i >= $attr['limit'] ) {
							break;
						}
						$_i++;
					}
					?>
					
					<?php $output .= wc_get_template_part( 'content', 'product' ); ?>
				
				<?php endwhile; // end of the loop. ?>
				
				<?php woocommerce_product_loop_end(false); ?>
			<?php } ?>
		<?php endif;
		
		if ( isset($woocommerce_loop['product_view']) )
			unset($woocommerce_loop['product_view']);
		if ( isset($woocommerce_loop['custom_template']) )
			unset($woocommerce_loop['custom_template']);
		if ( isset($woocommerce_loop['product_view_color']) )
			unset($woocommerce_loop['product_view_color']);
		if ( isset($woocommerce_loop['hover']) )
			unset($woocommerce_loop['hover']);
		if ( isset($woocommerce_loop['size']) )
			unset($woocommerce_loop['size']);
		if ( isset($woocommerce_loop['show_counter']) )
			unset($woocommerce_loop['show_counter']);
		if ( isset($woocommerce_loop['show_stock']) )
			unset($woocommerce_loop['show_stock']);
		
		if ( isset($woocommerce_loop['product_content_elements']))
		    unset($woocommerce_loop['product_content_elements']);
		
		if ( isset($woocommerce_loop['product_add_to_cart_quantity']))
		    unset($woocommerce_loop['product_add_to_cart_quantity']);
		
		if ( isset($woocommerce_loop['show_excerpt']))
		    unset($woocommerce_loop['show_excerpt']);
		
		unset($woocommerce_loop['loading_class']);
		
		wp_reset_postdata();
		wc_reset_loop();
		
		echo $output; // All data escaped
		
		die();
	}
}

// @todo could be in core
if( ! function_exists( 'etheme_fullscreen_products' ) ) {
	function etheme_fullscreen_products( $args, $slider_args = array() ) {
		global $woocommerce_loop;
		
		extract($slider_args);
		
		ob_start();
		
		$products = new WP_Query( $args );
		
		$images_slider_items = array();
		
		if ( $products->have_posts() ) :
            
            etheme_enqueue_style( 'products-full-screen', true );
			etheme_enqueue_style('single-product', true);
			etheme_enqueue_style('single-post-meta');
			// if comments allowed
			etheme_enqueue_style('star-rating');
            if ( etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
                etheme_enqueue_style( "swatches-style" );
            }
            
			?>

            <div class="et-full-screen-products <?php echo esc_attr( $class ); ?>">
                <div class="et-self-init-slider et-products-info-slider swiper-container">
                    <div class="swiper-wrapper">
						<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <div class="et-product-info-slide swiper-slide swiper-no-swiping">
                                <div class="product-info-wrapper">
                                    <p class="product-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </p>
									
									<?php
									
									woocommerce_template_single_rating();
									
									if ( !get_query_var('et_is-catalog', false) || ! etheme_get_option( 'just_catalog_price', 0 ) ){
										woocommerce_template_single_price();
									}
									
									woocommerce_template_single_excerpt();
									
									if( isset($woocommerce_loop['show_counter']) && $woocommerce_loop['show_counter'] ) etheme_product_countdown('type2', false);
									
									woocommerce_template_loop_add_to_cart();
									
									if( get_option('yith_wcwl_button_position') == 'shortcode' ) {
										etheme_wishlist_btn();
									}
									
									if ( isset($woocommerce_loop['show_stock']) && $woocommerce_loop['show_stock'] && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
										$id = get_the_ID();
										$product = wc_get_product($id);
										echo et_product_stock_line($product);
									}
									
									woocommerce_template_single_meta();
									
									if(etheme_get_option('share_icons', 1)): ?>
                                        <div class="product-share">
											<?php echo do_shortcode('[share title="'.__('Share: ', 'xstore').'" text="'.get_the_title().'"]'); ?>
                                        </div>
									<?php endif;?>
                                </div>
                            </div>
							
							<?php
							$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							$images_slider_items[] = '<div class="et-product-image-slide swiper-slide swiper-no-swiping" style="background-image: url(' . $image[0] . ');"></div>';
							?>
						
						<?php endwhile; // end of the loop. ?>
                    </div>
                </div>
                <div class="et-self-init-slider et-products-images-slider swiper-container">
                    <div class="swiper-wrapper">
						<?php echo implode( '', array_reverse( $images_slider_items) ); ?>
                    </div>
                    <div class="et-products-navigation">
                        <div class="et-swiper-next">
                            <span class="swiper-nav-title"></span>
                            <span class="swiper-nav-price"></span>
                            <span class="swiper-nav-arrow et-icon et-up-arrow"></span>
                        </div>
                        <div class="et-swiper-prev">
                            <span class="swiper-nav-arrow et-icon et-down-arrow"></span>
                            <span class="swiper-nav-title"></span>
                            <span class="swiper-nav-price"></span>
                        </div>
                    </div>
                </div>
            </div>
        
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var slidesCount = $('.et-product-info-slide').length;

                    var infoSwiper = new Swiper('.et-products-info-slider', {
                        pagination: {
                            clickable : true
                        },
                        direction: 'vertical',
                        slidesPerView: 1,
                        initialSlide: slidesCount,
                        // simulateTouch: false,
                        noSwiping: true,
                        loop: true,
                        on : {
                            init: function(swiper) {
                                updateNavigation();
                            }
                        }
                    });

                    var imagesSwiper = new Swiper('.et-products-images-slider', {
                        direction: 'vertical',
                        slidesPerView: 1,
                        loop: true,
                        // simulateTouch: false,
                        noSwiping: true,
                        navigation: {
                            nextEl: '.et-products-navigation .et-swiper-next',
                            prevEl: '.et-products-navigation .et-swiper-prev',
                        },
                        pagination: {
                            clickable : true
                        },
                        on : {
                            slideNextTransitionStart: function(swiper) {
                                infoSwiper.slidePrev();
                                updateNavigation();
                            },
                            slidePrevTransitionStart: function(swiper) {
                                infoSwiper.slideNext();
                                updateNavigation();
                            }
                        }
                    });

                    function updateNavigation() {
                        var $nextBtn = $('.et-products-navigation .et-swiper-next'),
                            $prevBtn = $('.et-products-navigation .et-swiper-prev'),
                            currentIndex = $('.et-product-info-slide.swiper-slide-active').data('swiper-slide-index'),
                            prevIndex = ( currentIndex >= slidesCount - 1 ) ? 0 : currentIndex + 1,
                            nextIndex = ( currentIndex <= 0 ) ? slidesCount - 1 : currentIndex - 1,
                            $nextProduct = $('.et-product-info-slide[data-swiper-slide-index="' + nextIndex + '"]'),
                            nextTitle = $nextProduct.find('.product-title a').first().text(),
                            nextPrice = $nextProduct.find('.price').html(),
                            $prevProduct = $('.et-product-info-slide[data-swiper-slide-index="' + prevIndex + '"]'),
                            prevTitle = $prevProduct.find('.product-title a').first().text(),
                            prevPrice = $prevProduct.find('.price').html();

                        $nextBtn.find('.swiper-nav-title').text(nextTitle);
                        $nextBtn.find('.swiper-nav-price').html(nextPrice);

                        $prevBtn.find('.swiper-nav-title').text(prevTitle);
                        $prevBtn.find('.swiper-nav-price').html(prevPrice);
                    }
					<?php if( isset($woocommerce_loop['show_counter']) ) : ?>
                    if ( etTheme.countdown !== undefined )
                        etTheme.countdown(); // refresh product timers
					<?php endif; ?>
                });
            </script>
		
		<?php endif;
		wp_reset_postdata();
		return ob_get_clean();
	}
}


if ( !function_exists('et_cart_checkout_breadcrumbs') ) {
	function et_cart_checkout_breadcrumbs() {
		if (!class_exists('WooCommerce')) return;
		
		global $wp;
		$page_id = get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$page_id = $page_id['id'];
		$is_checkout = $page_id == wc_get_page_id( 'checkout' ) || get_query_var( 'et_is-checkout', false );
		$is_cart = $page_id == wc_get_page_id( 'cart' ) || get_query_var( 'et_is-cart', false );
		$is_order = false;
		
		// Handle checkout actions.
		if ( ! empty( $wp->query_vars['order-pay'] ) ) {
		} elseif ( isset( $wp->query_vars['order-received'] ) ) {
			$is_order = true;
		}
		
		if ( !($is_checkout || $is_cart || $is_order) ) return;
		
		$countdown = array();

        if ( get_option('xstore_sales_booster_settings_cart_checkout_countdown') && !WC()->cart->is_empty() ) {

            $cart_checkout_class = Etheme_WooCommerce_Cart_Checkout::get_instance();
            $countdown = $cart_checkout_class->sales_booster_countdown();

        }
		
		if ( etheme_get_option('cart_special_breadcrumbs', 1) ) {
		
            etheme_enqueue_style('special-cart-breadcrumbs', true);

            $classes = array(
                'cart' => '',
                'checkout' => '',
                'order' => 'no-click'
            );

            if ( $is_order ) {
                $classes['cart'] = $classes['checkout'] = $classes['order'] = 'active';
            }
            elseif ( $is_checkout ) {
                $classes['cart'] = $classes['checkout'] = 'active';
                $classes['checkout'] .= ' no-click';
            }
            elseif ( $is_cart ) {
                $classes['cart'] = 'active no-click';
            }

            ob_start(); ?>
            <div class="cart-checkout-nav">
                <a href="<?php echo wc_get_cart_url(); ?>" class="<?php echo esc_attr($classes['cart']); ?>" data-step="1"> <?php esc_html_e('Shopping cart', 'xstore'); ?></a>

                <a href="<?php echo wc_get_checkout_url(); ?>" class="<?php echo esc_attr($classes['checkout']); ?>" data-step="2"> <?php esc_html_e('Checkout', 'xstore'); ?></a>

                <a href="#" class="<?php echo esc_attr($classes['order']); ?>" data-step="3"> <?php esc_html_e('Order status', 'xstore'); ?></a>

                <?php if ( count($countdown) ) :
                    echo '<div '.$countdown['attributes'].'>'.$countdown['content'].'</div>';
                endif; ?>

            </div>
            <?php echo ob_get_clean();
        }

        else {
            if (count($countdown)) :
                echo '<div ' . $countdown['attributes'] . '>' . $countdown['content'] . '</div>';
            endif;
        }

	}
}

if (!function_exists('et_mini_cart_linked_products')) {
	function et_mini_cart_linked_products($cart_content_linked_products_type = 'upsell', $etheme_mini_cart_global = array(), $extra = array() ) {
	 
		$extra = shortcode_atts(
			array(
				'posts_per_page' => 5,
				'slides_per_view' => 3,
				'slides_per_view_mobile' => 2,
				'as_widget' => true,
                'hide_out_stock' => false
			),
            $extra
        );
		
//		global $etheme_mini_cart_global;
		if (! isset($etheme_mini_cart_global[$cart_content_linked_products_type.'_ids'])){
			$etheme_mini_cart_global[$cart_content_linked_products_type.'_ids'] = array();
		}
		if (! isset($etheme_mini_cart_global[$cart_content_linked_products_type.'_ids_not_in'])){
			$etheme_mini_cart_global[$cart_content_linked_products_type.'_ids_not_in'] = array();
		}
		$etheme_mini_cart_global[$cart_content_linked_products_type.'_ids'] =
			array_diff(
				$etheme_mini_cart_global[$cart_content_linked_products_type.'_ids'],
				$etheme_mini_cart_global[$cart_content_linked_products_type.'_ids_not_in']
			);
		
		if (! is_array($etheme_mini_cart_global[$cart_content_linked_products_type.'_ids'])){
			return;
		}
		
		if ( count($etheme_mini_cart_global[$cart_content_linked_products_type.'_ids']) ) {
			
			$meta_query = WC()->query->get_meta_query();
			
			$args = array(
				'post_type'           => array( 'product', 'product_variation' ),
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => 1,
				'posts_per_page'      => $extra['posts_per_page'],
				'post__in'            => array_unique($etheme_mini_cart_global[$cart_content_linked_products_type.'_ids']),
				'post__not_in'        => array_unique($etheme_mini_cart_global[$cart_content_linked_products_type.'_ids_not_in']),
				'meta_query'          => $meta_query,
			);
			
			if ( $extra['hide_out_stock'] ) {
				$args['meta_query'] = array_merge($args['meta_query'], array(
					array(
						'key'     => '_stock_status',
						'value'   => 'instock',
						'compare' => '='
					),
				));
			}
			
			$slider_args = array(
				'before'          => '<h4 class="widget-title"><span>' . (($cart_content_linked_products_type == 'upsell') ?
						esc_html__( 'You may also like...', 'xstore' ) :
						apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'xstore' ) ) ) . '</span></h4>',
				'slider_autoplay' => false,
				'slider_speed'    => '0',
				'autoheight'      => false,
				'large'           => $extra['slides_per_view'],
				'notebook'        => $extra['slides_per_view'],
				'tablet_land'     => $extra['slides_per_view'],
				'tablet_portrait' => $extra['slides_per_view_mobile'],
				'mobile'          => $extra['slides_per_view_mobile'],
				'navigation_position' => 'middle-inside',
				'echo'            => false
			);
			
			if ( $extra['as_widget'] ) {
				$slider_args = array_merge($slider_args,
                    array(
	                    'attr'            => 'data-space="0"',
	                    'widget'          => true,
	                    'wrap_widget_items_in_div' => true
                    ) );
            }
			
			$slider_args['wrapper_class'] = $cart_content_linked_products_type.'-products';
            
            add_filter('woocommerce_product_title', 'etheme_mini_cart_linked_products_title_limit', 10);
			
			if ( $extra['as_widget'] ) {
				echo '<div class="sidebar-slider">' . etheme_slider( $args, 'product', $slider_args ) . '</div>';
            }
			else {
				echo etheme_slider( $args, 'product', $slider_args );
            }
			
			remove_filter('woocommerce_product_title', 'etheme_mini_cart_linked_products_title_limit', 10);
		}
	}
}

if ( !function_exists('etheme_mini_cart_linked_products_title_limit') ) {
    function etheme_mini_cart_linked_products_title_limit($title) {
	    $product_title = unicode_chars($title);
	    $product_title_limit = 20;
	    if ( $product_title_limit && strlen( $product_title ) > $product_title_limit ) {
		    $split         = preg_split( '/(?<!^)(?!$)/u', $product_title );
		    $product_title = ( $product_title_limit != '' && $product_title_limit > 0 && ( count( $split ) >= $product_title_limit ) ) ? '' : $product_title;
		    if ( $product_title == '' ) {
			    for ( $i = 0; $i < $product_title_limit; $i ++ ) {
				    $product_title .= $split[ $i ];
			    }
			    $product_title .= '...';
		    }
		    return $product_title;
	    }
	    return $title;
    }
}

add_action('wp_loaded', function() {
	
    add_filter('woocommerce_add_to_cart_redirect', function($url) {
		if (isset($_REQUEST['et_buy_now']) && etheme_get_option('buy_now_btn',0)) {
			$url = wc_get_checkout_url();
		}
		return $url;
	}, 10);
	
    do_action( 'litespeed_nonce', 'woocommerce-login' ); // 2 priority for wp-loaded
}, 10);

function etheme_set_loop_props_product_tabs($tabs = array()) {
	wc_set_loop_prop( 'columns_old', wc_get_loop_prop('columns') );
	wc_set_loop_prop( 'columns', 4 );
	return $tabs;
}

function etheme_reset_loop_props_product_tabs () {
	wc_set_loop_prop( 'columns', wc_get_loop_prop('columns_old') );
}

if ( !function_exists('etheme_dokan_seller')) {
	function etheme_dokan_seller() {
		if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'Dokan_Pro' ) ) {
			global $product;
			$seller = get_post_field( 'post_author', $product->get_id() );
			$author = get_user_by( 'id', $seller );
			
			$store_info = dokan_get_store_info( $author->ID );
			
			if ( ! empty( $store_info['store_name'] ) ) { ?>
                <span class="product_seller">
	            <?php printf( '<a href="%s" class="by-vendor-name-link">%s %s</a>', dokan_get_store_url( $author->ID ), esc_html__( 'Sold by', 'xstore' ), $store_info['store_name'] ); ?>
	        </span>
				<?php
			}
		}
		
	}
}

if ( ! function_exists( 'etheme_shop_filters_sidebar' ) ) {
	function etheme_shop_filters_sidebar() {
		if ( is_active_sidebar( 'shop-filters-sidebar' ) ):
			etheme_enqueue_style('filter-area', true );
			wp_enqueue_script( 'filters_area'); ?>
            <div class="shop-filters widget-columns-<?php echo etheme_get_option( 'filters_columns', 3 ); ?><?php echo ( etheme_get_option( 'filter_opened', 0 ) ) ? ' filters-opened' : ''; ?>">
                <div class="shop-filters-area">
					<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'shop-filters-sidebar' ) ): ?>
					<?php endif; ?>
                </div>
            </div>
		<?php endif;
	}
}

if ( ! function_exists( 'et_woocomerce_mini_cart_footer' ) ) {
	function et_woocomerce_mini_cart_footer() {
		global $woocommerce;
		if ( ! WC()->cart->is_empty() ) :
			
			remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
			
			if ( etheme_get_option('cart_content_view_cart_button_et-desktop', false) ) {
				add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
			}
			
			?>

            <div class="product_list-popup-footer-wrapper">

                <div class="product_list-popup-footer-inner">

                    <div class="cart-popup-footer">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                           class="btn-view-cart wc-forward"><?php esc_html_e( 'Shopping cart ', 'xstore' ); ?>
                            (<?php echo WC()->cart->cart_contents_count; ?>)</a>
                        <div class="cart-widget-subtotal woocommerce-mini-cart__total total">
							<?php
							/**
							 * Woocommerce_widget_shopping_cart_total hook.
							 *
							 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
							 */
							do_action( 'woocommerce_widget_shopping_cart_total' );
							?>
                        </div>
                    </div>
					
					<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

                    <p class="buttons mini-cart-buttons">
						<?php do_action( 'woocommerce_widget_shopping_cart_buttons
						' ); ?>
                    </p>
					
					<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

                </div>

            </div>
		
		<?php endif;
	}
}

if ( ! function_exists( 'etheme_wc_get_availability_class' ) ) {
	function etheme_wc_get_availability_class( $class, $product ) {
		$stock_quantity = $product->get_stock_quantity();
		$stock_class    = 'step-1';
		$already_sold   = get_post_meta( $product->get_ID(), 'total_sales', true );
		
		if ( ! empty( $stock_quantity ) && (int) $stock_quantity > 0 && get_option( 'woocommerce_manage_stock' ) ) {
			$already_sold     = empty( $already_sold ) ? 0 : $already_sold;
			$all_stock        = $stock_quantity + $already_sold;
			$stock_line_inner = 100 - ( ( $already_sold * 100 ) / $all_stock );
			if ( $stock_quantity <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
				$stock_class = 'step-3';
			} elseif ( $stock_line_inner > 50 ) {
				$stock_class = 'step-1';
			} else {
				$stock_class = 'step-2';
			}
		}
		
		if ( $product->is_in_stock() ) {
			$class .= ' ' . $stock_class;
		}
		
		return $class;
	}
}

function et_product_stock_line( $product, $default_html = false ) {
	$stock_line     = '';
	$stock_quantity = $product->get_stock_quantity();
	$already_sold   = get_post_meta( $product->get_ID(), 'total_sales', true );
	
	if ( ! empty( $stock_quantity ) ) {
		$already_sold = empty( $already_sold ) ? 0 : $already_sold;
		$all_stock    = $stock_quantity + $already_sold;
		ob_start();
		$stock_line_inner = ( ( $already_sold * 100 ) / $all_stock );
		if ( $stock_quantity <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
			$stock_class = 'step-3';
		} elseif ( ( 100 - $stock_line_inner ) > 50 ) {
			$stock_class = 'step-1';
		} else {
			$stock_class = 'step-2';
		}
		?>
        <div class="product-stock <?php echo esc_attr( $stock_class ); ?>">
            <span class="stock-in"><?php echo esc_html__( 'Available:', 'xstore' ) . ' <span class="stock-count">' . $stock_quantity . '</span>'; ?></span>
            <span class="stock-out"><?php echo esc_html__( 'Sold:', 'xstore' ) . ' <span class="stock-count">' . $already_sold . '</span>'; ?></span>
            <span class="stock-line"><span class="stock-line-inner"
                                           style="width: <?php echo esc_attr( $stock_line_inner ); ?>%"></span></span>
        </div>
		<?php $stock_line = ob_get_clean();
	}
	
	elseif ( $default_html ) {
	    return $default_html;
	}
	
	return $stock_line;
}

function et_quantity_plus_icon() {
	echo '<span class="plus"><i class="et-icon et-plus"></i></span>';
}

function et_quantity_minus_icon() {
	echo '<span class="minus"><i class="et-icon et-minus"></i></span>';
}

if ( ! function_exists( 'et_wc_track_product_view' ) ) {
	function et_wc_track_product_view() {
		if ( ! is_singular( 'product' ) && ! get_query_var( 'recently_viewed', 0 ) ) {
			return;
		}
		
		global $post;
		
		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
			$viewed_products = array();
		} else {
			$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
		}
		
		// Unset if already in viewed products list.
		$keys = array_flip( $viewed_products );
		
		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}
		
		$viewed_products[] = $post->ID;
		
		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}
		
		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );

	}
}

if ( !function_exists('etheme_woocommerce_continue_shopping') ) {
    function etheme_woocommerce_continue_shopping() {
        if ( wc_get_page_id( 'shop' ) > 0 )
            echo '<a class="return-shop button btn bordered full-width" href="' . get_permalink(wc_get_page_id('shop')) . '">' . esc_html__('Continue shopping', 'xstore') . '</a>';
    }
}
// because of btn-checkout class name
function etheme_woocommerce_widget_shopping_cart_proceed_to_checkout() {
	echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button btn-checkout wc-forward">' . esc_html__( 'Checkout', 'xstore' ) . '</a>';
}

if ( ! function_exists( 'etheme_woocommerce_widget_shopping_cart_subtotal' ) ) {
	function etheme_woocommerce_widget_shopping_cart_subtotal() {
		echo '<span class="small-h">' . esc_html__( 'Subtotal:', 'xstore' ) . '</span> <span class="big-coast">' . WC()->cart->get_cart_subtotal() . '</span>';
	}
}

if ( ! function_exists( 'etheme_product_share' ) ) {
	function etheme_product_share() {
		if ( etheme_get_option( 'share_icons', 1 ) ):
			global $product; ?>
            <div class="product-share">
				<?php echo do_shortcode( '[share title="' . __( 'Share: ', 'xstore' ) . '"]' ); ?>
            </div>
		<?php endif;
	}
}

if ( ! function_exists( 'etheme_single_product_brands' ) ) :
	function etheme_single_product_brands() {
		if ( etheme_xstore_plugin_notice() ) {
			return;
		}
		global $post;
		$terms = wp_get_post_terms( $post->ID, 'brand' );
		$brand = etheme_get_option( 'brand_title',1 );
		$show_image = etheme_get_option('show_brand_image', 1);
		if ( count( $terms ) < 1 ) {
			return;
		}
		$_i = 0;
		?>
        <span class="product_brand">
			<?php if ( $brand ) {
				esc_html_e( 'Brand: ', 'xstore' );
			} ?>
			<?php foreach ( $terms as $brand ) : $_i ++; ?>
				<?php
				$thumbnail_id = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) ); ?>
                <a href="<?php echo get_term_link( $brand ); ?>">
							<?php if ( $thumbnail_id && $show_image ) {
								echo wp_get_attachment_image( $thumbnail_id, 'full' );
							} else { ?>
								<?php echo esc_html( $brand->name ); ?>
							<?php } ?>
						</a>
				<?php if ( count( $terms ) > $_i ) {
					echo ", ";
				} ?>
			<?php endforeach; ?>
			</span>
		<?php
	}
endif;

// Woocommerce pagination

if ( ! function_exists( 'et_woocommerce_pagination' ) ) {
	function et_woocommerce_pagination() {
		
		$args = array(
			'total'   => wc_get_loop_prop( 'total_pages' ),
			'current' => wc_get_loop_prop( 'current_page' ),
			'base'    => esc_url_raw( add_query_arg( 'product-page', '%#%', false ) ),
			'format'  => '?product-page=%#%',
		);
		
		// @todo make push ajax loaded styles/scripts in footer and prevent load next times
		etheme_enqueue_style( 'pagination', isset($_GET['et_ajax']));
		
		$format = isset( $format ) ? $format : '';
		$base   = esc_url_raw( add_query_arg( 'product-page', '%#%', false ) );
		
		if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
			$format = '';
			$base   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', wp_specialchars_decode( get_pagenum_link( 999999999 ) ) ) ) );
		}
		
		$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
		$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );

//		$et_per_page  = ( isset( $_REQUEST['et_per_page'] ) ) ? $_REQUEST['et_per_page'] : etheme_get_option( 'products_per_page', 12 );
		$selected_val = ( isset( $_GET['et_per_page'] ) ) ? $_GET['et_per_page'] : false;
		
		return array(
			'base'      => $base,
			'format'    => $format,
			'add_args'  => ( ( ! wc_get_loop_prop( 'is_shortcode' ) && $selected_val ) ? array( 'et_per_page' => $selected_val ) : false ),
			'current'   => max( 1, $current ),
			'total'     => $total,
			'prev_text' => '<i class="et-icon et-'.(is_rtl() ? 'right' : 'left').'-arrow"><span class="screen-reader-text hidden">'.esc_html__('prev', 'xstore').'</span></i>',
			'next_text' => '<i class="et-icon et-'.(is_rtl() ? 'left' : 'right').'-arrow"><span class="screen-reader-text hidden">'.esc_html__('next', 'xstore').'</span></i>',
			'type'      => 'list',
			'end_size'  => 1,
			'mid_size'  => 1,
		);
	}
}

if ( ! function_exists( 'etheme_additional_information' ) ) {
	function etheme_additional_information() {
		global $product;
		?>
        <div class="product-attributes"><?php do_action( 'woocommerce_product_additional_information', $product ); ?></div>
		<?php
	}
}

if ( ! function_exists( 'etheme_get_single_product_class' ) ) {
	function etheme_get_single_product_class( $layout ) {
		$classes           = array();
		$classes['layout'] = $layout;
		$classes['class'][] = 'tabs-' . etheme_get_option( 'tabs_location', 'after_content' );
		$classes['class'][] = 'single-product-' . $layout;
		$classes['class'][] = 'reviews-position-' . etheme_get_option( 'reviews_position', 'tabs' );
		if ( etheme_get_option('stretch_add_to_cart_et-desktop', false) ) {
			$classes['class'][] = 'stretch-add-to-cart-button';
		}
		
		if ( etheme_get_option( 'ajax_addtocart' ) ) {
			$classes['class'][] = 'ajax-cart-enable';
		}
		if ( etheme_get_option( 'single_product_hide_sidebar', 0 ) ) {
			$classes['class'][] = 'sidebar-mobile-hide';
		}
		if ( etheme_get_option( 'product_name_signle', 0 ) && !etheme_get_option('product_name_single_duplicated', 0)) {
			$classes['class'][] = 'hide-product-name';
		}
		
		if ( $layout != 'large' ) {
			if ( etheme_get_option( 'fixed_images', 0 ) && $layout != 'fixed' ) {
				$classes['class'][] = 'product-fixed-images';
			} else if ( etheme_get_option( 'fixed_content', 0 ) || $layout == 'fixed' ) {
				$classes['class'][] = 'product-fixed-content';
			}
		}
		
		switch ( $layout ) {
			case 'small':
				$classes['image_class'] = 'col-lg-4 col-md-5 col-sm-12';
				$classes['infor_class'] = 'col-lg-8 col-md-7 col-sm-12';
				break;
			case 'large':
				$classes['image_class'] = 'col-sm-12';
				$classes['infor_class'] = 'col-lg-6 col-md-6 col-sm-12';
				break;
			case 'xsmall':
				$classes['image_class'] = 'col-lg-9 col-md-8 col-sm-12';
				$classes['infor_class'] = 'col-lg-3 col-md-4 col-sm-12';
				break;
			case 'fixed':
				$classes['image_class'] = 'col-sm-6';
				$classes['infor_class'] = 'col-lg-3 col-md-3 col-sm-12';
				break;
			case 'center':
				$classes['image_class'] = 'col-lg-4 col-md-4 col-sm-12';
				$classes['infor_class'] = 'col-lg-4 col-md-4 col-sm-12';
				break;
			default:
				$classes['image_class'] = 'col-lg-6 col-md-6 col-sm-12';
				$classes['infor_class'] = 'col-lg-6 col-md-6 col-sm-12';
				break;
		}
		
		return $classes;
	}
}

if ( ! function_exists( 'etheme_360_view_block' ) ) {
	function etheme_360_view_block() {
		global $post;
		$post_id = $post->ID;
		
		if ( ! class_exists( 'SmartProductPlugin' ) ) {
			return;
		}
		
		$smart_product = get_post_meta( $post_id, "smart_product_meta", true );
		
		// Check if id set
		if ( ! isset( $smart_product['id'] ) || $smart_product['id'] == "" ) {
			return '';
		}
		
		// Create slider instance
		$slider = new ThreeSixtySlider( $smart_product );
		
		?>
        <a href="#product-360-popup" class="open-360-popup"><?php esc_html_e( 'Open 360 view', 'xstore' ); ?></a>
		
		<?php echo '<div id="product-360-popup" class="product-360-popup mfp-hide">';
		echo $slider->show();
		echo '</div>'; ?>
	<?php }
}

// **********************************************************************//
// ! After products widget area
// **********************************************************************//

if ( ! function_exists( 'etheme_after_products_widgets' ) ) {
	function etheme_after_products_widgets() {
		echo '<div class="after-products-widgets">';
		dynamic_sidebar( 'shop-after-products' );
		echo '</div>';
	}
}


// **********************************************************************//
// ! Product sale countdown
// **********************************************************************//

if ( ! function_exists( 'etheme_product_countdown' ) ) {
	function etheme_product_countdown( $type = 'type2', $is_single = true ) {
	    global $product;
	    $type = isset($type) && !empty($type) ? $type : 'type2';
	    $timer_title = false;
	    $remove_on_finish = true;
	    $hide_title = true;
	    if ( $is_single ) {
	        $type = get_theme_mod('single_countdown_type', 'type2');
        }
	    if ( $type == 'type3') {
	        $remove_on_finish = false;
	        $hide_title = false;
            $timer_title = esc_html__('{fire} Hurry up! Sale ends in:', 'xstore');
            if ( $is_single ) {
                $timer_title = get_theme_mod('single_countdown_title', $timer_title);
            }
            $timer_title = str_replace('{fire}', '🔥', $timer_title);
        }
	    $product_id = get_the_ID();
	    $product_countdown_class = 'product-sale-counter';
	    $date       = get_post_meta( $product_id, '_sale_price_dates_to', true );
	    $date_from  = get_post_meta( $product_id, '_sale_price_dates_from', true );
        $time_start = get_post_meta( $product_id, '_sale_price_time_start', true );
        $time_start = explode( ':', $time_start );
        $time_end   = get_post_meta( $product_id, '_sale_price_time_end', true );
        $time_end   = explode( ':', $time_end );
	    if( $product && is_object($product) && $product->is_type('variable') ) {
	        $has_variation_on_sale = false;
            $variation_ids = $product->get_visible_children();
            foreach( $variation_ids as $variation_id ) {
                if ( $has_variation_on_sale ) break;
                $variation = wc_get_product( $variation_id );

                if ( $variation->is_on_sale() ) {
                    $has_variation_on_sale = true;
                    $remove_on_finish = false;
                    $date       = get_post_meta( $variation_id, '_sale_price_dates_to', true );
                    $date_from  = get_post_meta( $variation_id, '_sale_price_dates_from', true );
                    $time_start = get_post_meta( $variation_id, '_sale_price_time_start', true );
                    $time_start = explode( ':', $time_start );
                    $time_end   = get_post_meta( $variation_id, '_sale_price_time_end', true );
                    $time_end   = explode( ':', $time_end );
                }
            }
            if ( $has_variation_on_sale )
                $product_countdown_class .= ' hidden';
        }

	    if ( ! $date || ! class_exists( 'ETC\App\Controllers\Shortcodes\Countdown' ) ) {
            return false;
        }

		echo ETC\App\Controllers\Shortcodes\Countdown::countdown_shortcode( array(
			'year'   => date( 'Y', $date ),
			'month'  => date( 'M', $date ),
			'day'    => date( 'd', $date ),
			'hour'   => ( isset( $time_end[0] ) && $time_end[0] != 'Array' && $time_end[0] > 0 ) ? $time_end[0] : '00',
			'minute' => isset( $time_end[1] ) ? $time_end[1] : '00',
			
			'start_year'   => date( 'Y', (int) $date_from ),
			'start_month'  => date( 'M', (int) $date_from ),
			'start_day'    => date( 'd', (int) $date_from ),
			'start_hour'   => ( isset( $time_start[0] ) && $time_start[0] != 'Array' && $time_start[0] > 0 ) ? $time_start[0] : '00',
			'start_minute' => isset( $time_start[1] ) ? $time_start[1] : '00',
			'type'         => $type,
			'scheme'       => etheme_get_option( 'dark_styles', 0 ) ? 'white' : 'dark',
			'title'        => $timer_title,
			'remove_finish'=> $remove_on_finish,
			'hide_title'   => $hide_title,
			'class'        => $product_countdown_class
		) );
	}
}


// **********************************************************************//
// ! Wishlist
// **********************************************************************//

if ( ! function_exists( 'etheme_wishlist_btn' ) ) {
	function etheme_wishlist_btn( $args = array() ) {
		
		// $args['type'] = etheme_get_option('single_wishlist_type');
		// $args['position'] = etheme_get_option('single_wishlist_position');
		// $args['type'] = ( $args['type'] ) ? $args['type'] : 'icon-text';
		// $args['position'] = ( $args['position'] ) ? $args['position'] : 'under';

		$out = '';

		if ( ! is_array( $args ) ) {
			$args = array();
		}

		if (class_exists( 'YITH_WCWL_Shortcode' )) {

		    // global param for custom vc product grid
		    $args['type']     = ( isset( $args['type'] ) ) ? $args['type'] : 'icon-text';
            $args['position'] = ( isset( $args['position'] ) ) ? $args['position'] : 'under';
            $args['class']    = ( isset( $args['class'] ) ) ? $args['class'] : '';

            $out .= '<div class="et-wishlist-holder type-' . $args['type'] . ' position-' . $args['position'] . ' ' . $args['class'] . '">';
            $out .= do_shortcode( '[yith_wcwl_add_to_wishlist]' );
            $out .= '</div>';

		}
		else {
		    // global param for custom vc product grid
		    if ( isset( $args['type'] ) ) {
		        if ( isset($args['class'] ) )
		            $args['class'] .= ' type-' . $args['type'];
		        else
		            $args['class'] = 'type-'.$args['type'];
		        switch ($args['type']) {
                    case 'icon':
                        $args['only_icon'] = true;
                        $args['show_icon'] = true;
                    break;
                    case 'text':
                        $args['only_icon'] = false;
                        $args['show_icon'] = false;
                    break;
                    case 'icon-text':
                        $args['only_icon'] = false;
                        $args['show_icon'] = true;
                    break;
		        }
		    }
		}
		
		return apply_filters('etheme_wishlist_btn_output', $out, $args);
	}
}

if ( ! function_exists( 'etheme_remove_reviews_from_tabs' ) ) {
	function etheme_remove_reviews_from_tabs( $tabs ) {
		unset( $tabs['reviews'] );            // Remove the reviews tab
		
		return $tabs;
		
	}
}


if ( ! function_exists( 'etheme_compare_css' ) ) {
	add_action( 'wp_print_styles', 'etheme_compare_css', 200 );
	function etheme_compare_css() {
		if ( ! class_exists( 'YITH_Woocompare' ) ) {
			return;
		}
		if ( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'yith-woocompare-view-table' ) ) {
			return;
		}
		wp_enqueue_style( 'parent-style' );
	}
}

// **********************************************************************//
// ! Catalog setup
// **********************************************************************//

add_action( 'after_setup_theme', 'etheme_catalog_setup', 50 );

if ( ! function_exists( 'etheme_catalog_setup' ) ) {
	function etheme_catalog_setup() {
		if ( is_admin() ) {
			return;
		}
		
		if ( etheme_is_catalog() ) {
			#remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			// remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			//remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
			
			add_filter( 'woocommerce_loop_add_to_cart_link', 'etheme_add_to_cart_catalog_button', 50, 3 );
			
		}
		
		// **********************************************************************//
		// ! Set number of products per page
		// **********************************************************************//
		$products_per_page = isset( $_REQUEST['et_per_page'] ) ? $_REQUEST['et_per_page'] : etheme_get_option( 'products_per_page', 12 );
		add_filter( 'loop_shop_per_page', function () use ( $products_per_page ) {
			return $products_per_page;
		}, 50 );
	}
}

if ( ! function_exists( 'etheme_add_to_cart_catalog_button' ) ) {
	function etheme_add_to_cart_catalog_button( $sprintf, $product, $args ) {
		return sprintf( '<a rel="nofollow" href="%s" class="button show-product">%s</a>',
			esc_url( $product->get_permalink() ),
			__( 'Show details', 'xstore' )
		);
	}
}

if ( ! function_exists( 'etheme_before_fix_just_catalog_link' ) ) {
	function etheme_before_fix_just_catalog_link() {
		add_filter( 'woocommerce_loop_add_to_cart_link', 'etheme_add_to_cart_catalog_button', 50, 3 );
	}
}

if ( ! function_exists( 'etheme_after_fix_just_catalog_link' ) ) {
	function etheme_after_fix_just_catalog_link() {
		remove_filter( 'woocommerce_loop_add_to_cart_link', 'etheme_add_to_cart_catalog_button', 50, 3 );
	}
}

// popup added to cart
add_action('woocommerce_add_to_cart','set_last_added_cart_item_key',10,6);
function set_last_added_cart_item_key($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
	WC()->session->set('etheme_last_added_cart_key', $cart_item_key);
    WC()->session->set( 'etheme_last_added_cart_time', strtotime( 'now', current_time( 'timestamp' ) ));
}

add_action( 'wp_ajax_etheme_added_to_cart_popup', 'etheme_added_to_cart_popup' );
add_action( 'wp_ajax_nopriv_etheme_added_to_cart_popup', 'etheme_added_to_cart_popup' );
if ( !function_exists('etheme_added_to_cart_popup') ) {
    function etheme_added_to_cart_popup(){
	
        $cart_item_key = WC()->session->get('etheme_last_added_cart_key');

	    if(!$cart_item_key)
		    return;

	    // could remove but we may change quantity of product so keep it in transients

	    $args = array(
		    'cart_current_item_key' => $cart_item_key
	    );
	    
	    wc_get_template('product-added-to-cart.php', $args);
	    
	    die();
    }
}

// **********************************************************************//
// ! AJAX Quick View
// **********************************************************************//

add_action( 'wp_ajax_etheme_product_quick_view', 'etheme_product_quick_view' );
add_action( 'wp_ajax_nopriv_etheme_product_quick_view', 'etheme_product_quick_view' );
if ( ! function_exists( 'etheme_product_quick_view' ) ) {
	function etheme_product_quick_view() {
		if ( empty( $_POST['prodid'] ) ) {
			echo 'Error: Absent product id';
			die();
		}
		
		$args = array(
			'p'                => (int) $_POST['prodid'],
			'post_type'        => array('product', 'product_variation'),
		);
		
		if ( class_exists( 'SmartProductPlugin' ) ) {
			remove_filter( 'woocommerce_single_product_image_html', array(
				'SmartProductPlugin',
				'wooCommerceImage'
			), 999, 2 );
		}
		
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) : $the_query->the_post();
				if ( etheme_get_option( 'quick_view_content_type', 'popup' ) == 'off_canvas' ) {
					wc_get_template( 'product-quick-view-canvas.php' );
				} else {
					wc_get_template( 'product-quick-view.php' );
				}
			endwhile;
			wp_reset_query();
			wp_reset_postdata();
		} else {
			echo 'No posts were found!';
		}
		die();
	}
}

add_action( 'wp_ajax_etheme_update_cart_item_quantity', 'etheme_update_cart_item_quantity' );
add_action( 'wp_ajax_nopriv_etheme_update_cart_item_quantity', 'etheme_update_cart_item_quantity' );
if ( ! function_exists( 'etheme_update_cart_item_quantity' ) ) {
	function etheme_update_cart_item_quantity() {
		if ( ( isset( $_GET['item_id'] ) && $_GET['item_id'] ) && ( isset( $_GET['qty'] ) && $_GET['qty'] ) ) {
			global $woocommerce;
			WC()->cart->set_quantity( $_GET['item_id'], $_GET['qty'] );
		}
        WC_AJAX::get_refreshed_fragments();
	}
}

add_action( 'wp_ajax_etheme_clear_all_cart', 'etheme_clear_all_cart' );
add_action( 'wp_ajax_nopriv_etheme_clear_all_cart', 'etheme_clear_all_cart' );
if ( ! function_exists( 'etheme_clear_all_cart' ) ) {
	function etheme_clear_all_cart() {
		//For removing all the items from the cart
		global $woocommerce;
		WC()->cart->empty_cart();
		WC_AJAX::get_refreshed_fragments();
	}
}

add_action( 'wp_ajax_etheme_get_mini_cart', 'etheme_get_mini_cart' );
add_action( 'wp_ajax_nopriv_etheme_get_mini_cart', 'etheme_get_mini_cart' );
if ( ! function_exists( 'etheme_get_mini_cart' ) ) {
	function etheme_get_mini_cart() {
		$array = array();
		remove_action( 'woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10 );
		add_action( 'woocommerce_widget_shopping_cart_total', 'etheme_woocommerce_widget_shopping_cart_subtotal', 10 );
		
		$array['et_cart_mini_count'] =  WC()->cart->get_cart_contents_count();
		
		ob_start();
		wc_get_template( 'cart/mini-cart.php' );
		$array['et_mini_cart'] =  ob_get_clean();
		
		$array['et_cart_mini_content_callback'] = et_cart_mini_content_callback();
		wp_send_json($array);
	}
}

if ( !function_exists('etheme_category_size_guide') ) {
    function etheme_category_size_guide($product_id = null) {
        $primary_category  = etheme_get_custom_field( 'primary_category' );
        if ( ! empty( $primary_category ) && $primary_category != 'auto' ) {
            $primary = get_term_by( 'slug', $primary_category, 'product_cat' );
            if ( ! is_wp_error( $primary ) && isset($primary->term_id) ) {
                $product_primary_cat_size_guide_local_img = get_term_meta( $primary->term_id, '_et_size_guide', true );
                if ( $product_primary_cat_size_guide_local_img )
                    return $product_primary_cat_size_guide_local_img;
            }
        }
        else {
            $terms = get_the_terms ( $product_id, 'product_cat' );
            foreach ( $terms as $term ) {
                $product_cat_size_guide_local_img = get_term_meta( $term->term_id, '_et_size_guide', true );
                if ( $product_cat_size_guide_local_img ) {
                    return $product_cat_size_guide_local_img;
                }
            }
        }
    }
}

if ( ! function_exists( 'etheme_size_guide' ) ) {
	function etheme_size_guide() {
		
		$global_guide = etheme_get_option( 'size_guide_img', '' );
		$local_guide  = etheme_get_custom_field( 'size_guide_img' );
		$global_type  = etheme_get_option( 'size_guide_type', 'popup' );
		$local_type   = etheme_get_custom_field( 'size_guide_type' );
		$global_type  = $local_type != '' ? $local_type : $global_type;
		$size_file    = etheme_get_option( 'size_guide_file' );
		$attr         = array();
		
		if ( !$local_guide ) {
		    $category_size_guide = etheme_category_size_guide(get_the_ID());
		    if ( $category_size_guide )
		        $local_guide = $category_size_guide;
		}
		if ( $local_guide ) {
			$image = $local_guide;
		} elseif ( $global_type == 'popup' && isset( $global_guide['url'] ) ) {
			$image = $global_guide['url'];
		} elseif ( $global_type == 'download_button' && ! empty( $size_file ) ) {
			$image = $size_file;
		} else {
			$image = '';
		}
		
		if ( ! empty( $image ) ) : ?>
			<?php
			$attr[] = 'href="' . esc_url( $image ) . '"';
			if ( $global_type == 'popup' ) {
				$attr[] = 'rel="lightbox"';
			} else {
				$attr[] = 'download="' . wp_basename($image) . '"';
			}
			
			?>
            <div class="size-guide">
                <a <?php echo implode( ' ', $attr ); ?>><?php esc_html_e( 'Sizing guide', 'xstore' ); ?></a>
            </div>
		<?php endif;
	}
}

if ( ! function_exists( 'etheme_product_cats' ) ) {
	function etheme_product_cats( $single = false ) {
		global $post, $product;
		$cat  = etheme_get_custom_field( 'primary_category', ( $product->get_type() == 'variation') ? $product->get_parent_id() : false );
		$html = '';
		if (
			! empty( $cat )
			&& $cat != 'auto'
			&& $primary = get_term_by( 'slug', $cat, 'product_cat' )
		) {
			if ( ! is_wp_error( $primary ) ) {
				$term_link = get_term_link( $primary );
				if ( ! is_wp_error( $term_link ) ) {
					if ( $single ) {
						$html .= '<span class="posted_in">' . esc_html__( 'Category: ', 'xstore' ) . '<a href="' . esc_url( $term_link ) . '">' . $primary->name . '</a></span>';
					} else {
						$html .= '<a href="' . esc_url( $term_link ) . '">' . $primary->name . '</a>';
					}
				}
			}
		} else {
			if ( $single ) {
				$html .= wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'xstore' ) . ' ', '</span>' );
			} else {
				$html .= wc_get_product_category_list( $product->get_id(), ', ' );
			}
		}
		if ( $html ) {
			echo '<div class="products-page-cats">' . $html . '</div>';
		}
	}
}

// **********************************************************************//
// ! Get list of all product brands
// **********************************************************************//
if ( ! function_exists( 'etheme_product_brands' ) ) :
	function etheme_product_brands() {
		if ( etheme_xstore_plugin_notice() ) {
			return;
		}
//		global $post;
        global $product;
		$id = $product->get_id();
		if ( $product->get_type() == 'variation') {
			$id = $product->get_parent_id();
		}
		$terms = wp_get_post_terms( $id, 'brand' );
		if ( is_wp_error( $terms ) || $terms == '' || ( is_array( $terms ) && count( $terms ) < 1 ) ) {
			return;
		}
		$_i = 0;
		
		?>
        <div class="products-page-brands">
			<?php foreach ( $terms as $brand ) : $_i ++; ?>
                <a href="<?php echo get_term_link( $brand ); ?>"
                   class="view-products"><?php echo esc_html( $brand->name ); ?></a>
				<?php if ( count( $terms ) > $_i ) {
					echo ", ";
				} ?>
			<?php endforeach; ?>
        </div>
		<?php
	}
endif;

// **********************************************************************//
// ! Get list of all product images
// **********************************************************************//

if ( ! function_exists( 'etheme_get_image_list' ) ) {
	function etheme_get_image_list( $size = 'woocommerce_thumbnail', $include_main_image = true ) {
		global $post, $product, $woocommerce;
		$prod_images = array();
		
		$product_id = $post->ID;
		$attachment_ids = $product->get_gallery_image_ids();
		if ( etheme_get_option('enable_variation_gallery', false) &&
		     etheme_get_option('variable_products_detach', false) && $product->get_type() == 'variation' ) {
			// take images from variation gallery meta
			$variation_attachment_ids = get_post_meta( $product->get_id(), 'et_variation_gallery_images', true );

			// Compatibility with WooCommerce Additional Variation Images plugin
			if ( !(bool)$variation_attachment_ids )
			    $variation_attachment_ids = array_filter( explode( ',', get_post_meta( $product->get_id(), '_wc_additional_variation_images', true )));

			if ( (bool) $variation_attachment_ids && count((array) $variation_attachment_ids) ) {
				$attachment_ids = $variation_attachment_ids;
			}
			else {
				// if inherit parent second image
				$parent = wc_get_product( $product->get_parent_id() );
				$attachment_ids = $parent->get_gallery_image_ids();
			}
		}
		
		$_i = 0;
		
		if ( count( $attachment_ids ) > 0 ) {
		    if ( $include_main_image ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), $size );
                if ( is_array( $image ) && isset( $image[0] ) ) {
                    $prod_images[] = $image[0];
                }
			}
			foreach ( $attachment_ids as $id ) {
				$_i ++;
				$image = wp_get_attachment_image_src( $id, $size );
				if ( $image == '' ) {
					continue;
				}

				$prod_images[] = $image[0];
			}
			
		}
		
		return implode(';', $prod_images);
	}
}


// **********************************************************************//
// ! Display second image in the gallery
// **********************************************************************//

if ( ! function_exists( 'etheme_get_second_image' ) ) {
	function etheme_get_second_image( $size = 'woocommerce_thumbnail' ) {
		global $product, $woocommerce_loop;
		
		$attachment_ids = $product->get_gallery_image_ids();
		
		if ( etheme_get_option('enable_variation_gallery', false) &&
		     etheme_get_option('variable_products_detach', false) && $product->get_type() == 'variation' ) {
		    // take images from variation gallery meta
			$variation_attachment_ids = get_post_meta( $product->get_id(), 'et_variation_gallery_images', true );

			// Compatibility with WooCommerce Additional Variation Images plugin
			if ( !(bool)$variation_attachment_ids )
			    $variation_attachment_ids = array_filter( explode( ',', get_post_meta( $product->get_id(), '_wc_additional_variation_images', true )));

			if ( (bool) $variation_attachment_ids && count((array) $variation_attachment_ids) ) {
			    $attachment_ids = $variation_attachment_ids;
            }
			else {
			    // if inherit parent second image
				$parent = wc_get_product( $product->get_parent_id() );
				$attachment_ids = $parent->get_gallery_image_ids();
            }
        }
		
		$image = '';
		
		if ( ! empty( $attachment_ids[0] ) ) {
            $image = etheme_get_image($attachment_ids[0], $size);
		}
		
		if ( $image != '' ) {
			echo '<div class="image-swap">' . $image . '</div>';
		}
	}
}

// **********************************************************************//
// ! Get product availability
// **********************************************************************//

if ( ! function_exists( 'etheme_product_availability' ) ) {
	function etheme_product_availability() {
		if ( ! etheme_get_option( 'out_of_icon', 1 ) ) {
			return;
		}
		global $product;
		// Availability
		$availability      = $product->get_availability();
		$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
		
		echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
	}
}

// **********************************************************************//
// ! Grid/List switcher
// **********************************************************************//
add_action( 'woocommerce_before_shop_loop', 'etheme_grid_list_switcher', 35 );
if ( ! function_exists( 'etheme_grid_list_switcher' ) ) {
	function etheme_grid_list_switcher() {
		global $wp;
		
		// prevent filter button from products shortcode
		if ( wc_get_loop_prop( 'is_shortcode' ) || is_tax('dc_vendor_shop') ) {
			return;
		}
		
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		    return;
        }
		
		if ( (function_exists('wcfm_is_store_page') && wcfm_is_store_page()) || ( function_exists('wcfmmp_is_stores_list_page') && wcfmmp_is_stores_list_page()) ) {
			return;
		}
		
		$current_url = etheme_get_current_page_url();
		
		$view_mode = etheme_get_option( 'view_mode', 'grid_list' );
		$view_mode_smart = get_query_var('view_mode_smart', false);
		
		if ( in_array( $view_mode, array( 'grid', 'list' ) ) ) {
			return;
		}
		
		etheme_enqueue_style('filter-area', true );
		
		$url_grid = add_query_arg( 'view_mode', 'grid', remove_query_arg( 'view_mode', $current_url ) );
		$url_list = add_query_arg( 'view_mode', 'list', remove_query_arg( 'view_mode', $current_url ) );
		
		$is_mobile = get_query_var('is_mobile', false);
		$current = get_query_var( 'et_view-mode' );
		$current_view = isset($_GET['et_columns-count']) ? $_GET['et_columns-count'] : get_query_var('view_mode_smart_active', 4);
		
		?>
        <div class="view-switcher">
            <label><?php esc_html_e( 'View as:', 'xstore' ); ?></label>
			<?php
			if ( $view_mode_smart ) : ?>
				
				<?php if ( $is_mobile ) : ?>
                    <div class="switch-grid <?php if ( $current == 'grid' ) { echo 'switcher-active'; } ?>">
                        <a data-type="grid" data-row-count="<?php echo esc_attr($current_view); ?>" href="<?php echo esc_url( add_query_arg( 'et_columns-count', $current_view, remove_query_arg( 'et_columns-count', $url_grid ) ) ); ?>"></a>
                    </div>
				<?php else : ?>

                    <div class="switch-grid <?php if ( $current == 'grid' && $current_view == 2 ) { echo 'switcher-active'; } ?>">
                        <a data-type="grid" data-row-count="2" href="<?php echo esc_url( add_query_arg( 'et_columns-count', '2', remove_query_arg( 'et_columns-count', $url_grid ) ) ); ?>"><?php esc_html_e( '2 columns grid', 'xstore' ); ?></a>
                    </div>

                    <div class="switch-grid <?php if ( $current == 'grid' && $current_view == 3 ) { echo 'switcher-active'; } ?>">
                        <a data-type="grid" data-row-count="3" href="<?php echo esc_url( add_query_arg( 'et_columns-count', '3', remove_query_arg( 'et_columns-count', $url_grid ) ) ); ?>"><?php esc_html_e( '3 columns grid', 'xstore' ); ?></a>
                    </div>

                    <div class="switch-grid <?php if ( $current == 'grid' && $current_view == 4 ) { echo 'switcher-active'; } ?>">
                        <a data-type="grid" data-row-count="4" href="<?php echo esc_url( add_query_arg( 'et_columns-count', '4', remove_query_arg( 'et_columns-count', $url_grid ) ) ); ?>"><?php esc_html_e( '4 columns grid', 'xstore' ); ?></a>
                    </div>
				
				<?php endif; ?>

                <div class="switch-list <?php if ( $current == 'list' || $current_view == 'list' ) { echo 'switcher-active'; } ?>">
                    <a data-type="list" data-row-count="3" href="<?php echo esc_url( $url_list ); ?>"><?php esc_html_e( 'List', 'xstore' ); ?></a>
                </div>
				
				<?php if ( !$is_mobile ) : ?>

                    <div class="switch-more <?php if ( $current == 'more' || $current_view == 'more' ) { echo 'switcher-active'; } ?>">
                        <a data-type="more"><?php esc_html_e( 'More', 'xstore' ); ?></a>
                        <ul>
                            <li class="<?php if ( $current == 'grid' && $current_view == 5 ) { echo 'switcher-active'; } ?>"><a data-type="grid" data-row-count="5" href="<?php echo esc_url( add_query_arg( 'et_columns-count', '5', remove_query_arg( 'et_columns-count', $url_grid ) ) ); ?>"><?php esc_html_e( '5 columns grid', 'xstore' ); ?></a></li>
                            <li class="<?php if ( $current == 'grid' && $current_view == 6 ) { echo 'switcher-active'; } ?>"><a data-type="grid" data-row-count="6" href="<?php echo esc_url( add_query_arg( 'et_columns-count', '6', remove_query_arg( 'et_columns-count', $url_grid ) ) ); ?>"><?php esc_html_e( '6 columns grid', 'xstore' ); ?></a></li>
                        </ul>
                    </div>
				
				<?php endif; ?>
			
			<?php else :
				if ( $view_mode == 'grid_list' ): ?>
                    <div class="switch-grid <?php if ( $current == 'grid' ) {
						echo 'switcher-active';
					} ?>">
                        <a data-type="grid" href="<?php echo esc_url( $url_grid ); ?>"><?php esc_html_e( 'Grid', 'xstore' ); ?></a>
                    </div>
                    <div class="switch-list <?php if ( $current == 'list' ) {
						echo 'switcher-active';
					} ?>">
                        <a data-type="list" href="<?php echo esc_url( $url_list ); ?>"><?php esc_html_e( 'List', 'xstore' ); ?></a>
                    </div>
				<?php elseif ( $view_mode == 'list_grid' ): ?>
                    <div class="switch-list <?php if ( $current == 'list' ) {
						echo 'switcher-active';
					} ?>">
                        <a data-type="list" href="<?php echo esc_url( $url_list ); ?>"><?php esc_html_e( 'List', 'xstore' ); ?></a>
                    </div>
                    <div class="switch-grid <?php if ( $current == 'list' ) {
						echo 'switcher-active';
					} ?>">
                        <a data-type="grid" href="<?php echo esc_url( $url_grid ); ?>"><?php esc_html_e( 'Grid', 'xstore' ); ?></a>
                    </div>
				<?php endif;
			endif; ?>
        </div>
		<?php
	}
}

// **********************************************************************//
// ! View mode
// **********************************************************************//

// ! Get view mode
if ( ! function_exists( 'etheme_get_view_mode' ) ) {
	function etheme_get_view_mode() {
		$current = 'grid';
		if ( get_query_var('et_is_customize_preview', false) ) {
			$mode = etheme_get_option( 'view_mode', 'grid_list' );
			if ( $mode == 'list_grid' || $mode == 'list' ) {
				$current = 'list';
			}
			
			return $current;
		}
		if ( class_exists( 'WC_Session_Handler' ) && ! is_admin() ) {
			$s = WC()->session;
			
			if ( $s == null ) {
				return $current;
			}
			
			$s    = $s->__get( 'view_mode', 0 );
			$mode = etheme_get_option( 'view_mode', 'grid_list' );
			
			if ( isset( $_REQUEST['view_mode'] ) ) {
				$current = ( $_REQUEST['view_mode'] );
			} elseif ( isset( $s ) && ! empty( $s ) ) {
				$current = ( $s );
			} elseif ( $mode == 'list_grid' || $mode == 'list' ) {
				$current = 'list';
			}
		}
		
		return $current;
	}
}

// ! Set view mode
if ( ! function_exists( 'etheme_view_mode_action' ) ) {
	add_action( 'init', 'etheme_view_mode_action', 100 );
	function etheme_view_mode_action() {
		if ( isset( $_REQUEST['view_mode'] ) && class_exists( 'WC_Session_Handler' ) ) {
			$s = WC()->session;
			if ( $s != null ) {
				$s->set( 'view_mode', ( $_REQUEST['view_mode'] ) );
			}
		}
	}
}

if ( !function_exists('etheme_get_current_page_url')) {
	function etheme_get_current_page_url() {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_shop() ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			if ( is_object( $queried_object ) ) {
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			} else {
				$link = home_url();
			}
		}
		
		// Min/Max.
		if ( isset( $_GET['min_price'] ) ) {
			$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
		}
		
		if ( isset( $_GET['max_price'] ) ) {
			$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
		}
		
		// Order by.
		if ( isset( $_GET['orderby'] ) ) {
			$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
		}
		
		// Brand
		if ( isset( $_GET['filter_brand'] ) ) {
			$link = add_query_arg( 'filter_brand', wc_clean( wp_unslash( $_GET['filter_brand'] ) ), $link );
		}
		
		// Stock Status
		if ( isset( $_GET['stock_status'] ) ) {
			$link = add_query_arg( 'stock_status', wc_clean( wp_unslash( $_GET['stock_status'] ) ), $link );
		}
		
		/**
		 * Search Arg.
		 * To support quote characters, first they are decoded from " entities, then URL encoded.
		 */
		if ( get_search_query() ) {
			$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
		}
		
		// Post Type Arg.
		if ( isset( $_GET['post_type'] ) ) {
			$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );
			
			// Prevent post type and page id when pretty permalinks are disabled.
			if ( is_shop() ) {
				$link = remove_query_arg( 'page_id', $link );
			}
		}
		
		// Min Rating Arg.
		if ( isset( $_GET['rating_filter'] ) ) {
			$link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
		}
		
		// All current filters.
		if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
			foreach ( $_chosen_attributes as $name => $data ) {
				$filter_name = wc_attribute_taxonomy_slug( $name );
				if ( ! empty( $data['terms'] ) ) {
					$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
				}
				if ( 'or' === $data['query_type'] ) {
					$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
				}
			}
		}
		
		if ( ! $link ) {
			global $wp;
			$link = home_url( $wp->request );
		}
		
		return apply_filters('etheme_current_page_url', $link);
	}
}

// **********************************************************************//
// ! Filters button
// **********************************************************************//

add_action( 'woocommerce_before_shop_loop', 'etheme_filters_btn', 11 );
if ( ! function_exists( 'etheme_filters_btn' ) ) {
	function etheme_filters_btn() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		    return;
        }
		if ( ! wc_get_loop_prop( 'is_shortcode' ) ) { // prevent filter button from products shortcode
			if ( is_active_sidebar( 'shop-filters-sidebar' ) ) {
				?>
                <div class="open-filters-btn">
                    <a href="#" class="<?php echo ( etheme_get_option( 'filter_opened', 0 ) ) ? ' active' : ''; ?>">
                        <i class="et-icon et-controls"></i>
						<?php esc_html_e( 'Filters', 'xstore' ); ?>
                    </a>
                </div>
				<?php
			}
		}
	}
}

// **********************************************************************//
// ! Products per page dropdown
// **********************************************************************//
add_action( 'woocommerce_before_shop_loop', 'etheme_products_per_page_select', 37 );
if ( ! function_exists( 'etheme_products_per_page_select' ) ) {
	function etheme_products_per_page_select() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		    return;
        }
		global $wp_query;
		
		// prevent filter button from products shortcode
		if ( wc_get_loop_prop( 'is_shortcode' ) ) {
			return;
		}
		
		$action = $out = $et_ppp = $per_page = $class = '';
		
		if ( is_active_sidebar( 'shop-filters-sidebar' ) ) {
			$class .= ' et-hidden-phone';
		}
		$et_ppp   = etheme_get_option( 'et_ppp_options', '12,24,36,-1' );
		$et_ppp   = ( ! empty( $et_ppp ) ) ? explode( ',', $et_ppp ) : array( 12, 24, 36, - 1 );
		$per_page = ( isset( $_REQUEST['et_per_page'] ) ) ? $_REQUEST['et_per_page'] : etheme_get_option( 'products_per_page', 12 );
		
		// $action   = etheme_get_current_page_url();
		// get the position where '/page.. ' text start.
		//$pos = strpos($action , '/page');
		// remove string from the specific postion
		//$action = substr($action,0,$pos);
		
		$action   = ( isset( $cat->term_id ) ) ? get_term_link( $cat->term_id ) : esc_url_raw( get_pagenum_link() );
		$action = remove_query_arg('et_ajax', $action);
		$action = str_replace('#038;', '&', $action);

		$action = str_replace('?=', '&=', $action);
		$action = str_replace('/&', '/?', $action);
		
		$onchange = (etheme_get_option( 'ajax_product_filter', 0 ))? '' : 'this.form.submit()';
		
		$out .= '<span class="mob-hide">' . esc_html__( 'Show', 'xstore' ) . '</span>';
		$out .= '<form method="get" action="' . esc_url( $action ) . '">';
		$out .= '<select name="et_per_page" onchange="'.$onchange.'" class="et-per-page-select">';
		foreach ( $et_ppp as $key => $value ) {
			$out .= sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $value ),
				selected( $value, $per_page, false ),
				( $value == - 1 ) ? esc_html__( 'All', 'xstore' ) : $value
			);
		}
		foreach ( $_GET as $key => $val ) {
			if ( 'et_per_page' === $key || 'submit' === $key ) {
				continue;
			}
			if ( is_array( $val ) ) {
				foreach ( $val as $inner_val ) {
					$out .= '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $inner_val ) . '" />';
				}
			} else {
				$out .= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
		$out .= '</select>';
		$out .= '</form>';
		echo '<div class="products-per-page ' . $class . '">' . $out . '</div>';
	}
}

// **********************************************************************//
// ! Category thumbnail
// **********************************************************************//
if ( ! function_exists( 'etheme_category_header' ) ) {
	function etheme_category_header() {
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		
		if ( ! property_exists( $cat, 'term_id' ) && ! is_search() && etheme_get_option( 'product_bage_banner', '' ) != '' ) {
			echo '<div class="category-description">';
			echo do_shortcode( etheme_get_option( 'product_bage_banner', '' ) );
			echo '</div>';
		}
	}
}

// **********************************************************************//
// ! Second product category description
// **********************************************************************//
if ( ! function_exists( 'etheme_second_cat_desc' ) ) {
	function etheme_second_cat_desc() {
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		
		if ( property_exists( $cat, 'term_id' ) && ! is_search() ) {
			$desc = get_term_meta( $cat->term_id, '_et_second_description', true );
		} else {
			return;
		}
		
		if ( ! empty( $desc ) ) {
			echo '<div class="term-description et_second-description">' . do_shortcode( $desc ) . '</div>';
		}
	}
}

// **********************************************************************//
// ! Wishlist Widget
// **********************************************************************//

if ( ! function_exists( 'etheme_support_multilingual_ajax' ) ) {
	add_filter( 'wcml_multi_currency_is_ajax', 'etheme_support_multilingual_ajax' );
	function etheme_support_multilingual_ajax( $functions ) {
		$functions[] = 'etheme_wishlist_fragments';
		
		return $functions;
	}
}

if ( ! function_exists( 'etheme_wishlist_fragments' ) ) {
	add_action( 'wp_ajax_etheme_wishlist_fragments', 'etheme_wishlist_fragments' );
	add_action( 'wp_ajax_nopriv_etheme_wishlist_fragments', 'etheme_wishlist_fragments' );
	
	function etheme_wishlist_fragments() {
		if ( ! function_exists( 'wc_setcookie' ) || ! function_exists( 'YITH_WCWL' ) ) {
			return;
		}
		$products = YITH_WCWL()->get_products( array(
			#'wishlist_id' => 'all',
			'is_default' => true
		) );
		
		// Get mini cart
		ob_start();
		
		etheme_wishlist_widget();
		
		$wishlist = ob_get_clean();
		
		// Fragments and mini cart are returned
		$data = array(
			'wishlist'      => $wishlist,
			'wishlist_hash' => md5( json_encode( $products ) )
		);
		
		wp_send_json( $data );
	}
}

// **********************************************************************//
// ! Is zoom plugin activated
// **********************************************************************//
if ( ! function_exists( 'etheme_is_zoom_activated' ) ) {
	function etheme_is_zoom_activated() {
		return class_exists( 'YITH_WCMG_Frontend' );
	}
}

if ( ! function_exists( 'etheme_cart_total' ) ) {
	function etheme_cart_total() {
		global $woocommerce;
		?>
        <span class="shop-text"><span class="cart-items"><?php esc_html_e( 'Cart', 'xstore' ) ?>:</span> <span
                    class="total"><?php echo wp_specialchars_decode( $woocommerce->cart->get_cart_subtotal() ); ?></span></span>
		<?php
	}
}

// to hide popup if empty
if ( ! function_exists( 'etheme_cart_items_count' ) ) {
	function etheme_cart_items_count() {
		global $woocommerce;
		?>
        <span class="popup-count popup-count-<?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?>"></span>
		<?php
	}
}


if ( ! function_exists( 'etheme_cart_number' ) ) {
	function etheme_cart_number() {
		global $woocommerce;
		?>
        <span class="badge-number number-value-<?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?>"
              data-items-count="<?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?>"><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?></span>
		<?php
	}
}

if ( ! function_exists( 'etheme_cart_items' ) ) {
	function etheme_cart_items( $limit = 3 ) {
		?>
		<?php if ( ! WC()->cart->is_empty() ) :
//			global $etheme_mini_cart_global;
			$cart_content_linked_products_dt = etheme_get_option('cart_content_linked_products_et-desktop', false);
			$cart_content_linked_products_mob = etheme_get_option('cart_content_linked_products_et-mobile', false);
			$cart_content_linked_products_type = etheme_get_option('cart_content_linked_products_type_et-desktop', 'upsell');
			$is_mobile = get_query_var('is_mobile', false);
			$show_cart_content_linked_products =
				($cart_content_linked_products_dt && !$is_mobile && get_theme_mod( 'cart_content_type_et-desktop', 'dropdown' ) == 'off_canvas') ||
				($cart_content_linked_products_mob && $is_mobile && get_theme_mod( 'cart_content_type_et-mobile', 'dropdown' ) == 'off_canvas' );
			$etheme_mini_cart_global = array();
			$etheme_mini_cart_global['upsell_ids'] = array();
			$etheme_mini_cart_global['upsell_ids_not_in'] = array();
			
			$etheme_mini_cart_global['cross-sell_ids'] = array();
			$etheme_mini_cart_global['cross-sell_ids_not_in'] = array();
			
			?>

            <ul class="cart-widget-products clearfix">
				<?php
				$i    = 0;
				$cart =  WC()->cart->get_cart();

				if  ( apply_filters('et_mini_cart_reverse', false) ){
					$cart = array_reverse($cart);
                }

				do_action( 'woocommerce_before_mini_cart_contents' );
				foreach ( $cart as $cart_item_key => $cart_item ) {
					
					if ( $i >= $limit ) {
						continue;
					}
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$i ++;
						$product_name        = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail           = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price       = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_remove_icon = apply_filters( 'woocommerce_cart_item_remove_icon_html', '<i class="et-icon et-delete et-remove-type1"></i><i class="et-trash-wrap et-remove-type2"><img src="' . ETHEME_BASE_URI . 'theme/assets/images/trash-bin.gif' . '" alt="'. esc_attr( 'Remove this product', 'xstore' ) .'"></i>' ); ?>
                        <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>"
                            data-key="<?php echo esc_attr( $cart_item_key ); ?>">
							<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">' . $product_remove_icon . '</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'xstore' ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
							?>
							<?php if ( ! $_product->is_visible() ) : ?>
                                <a class="product-mini-image">
								    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . ''; ?>
                                </a>
							<?php else : ?>
                                <a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>"
                                   class="product-mini-image">
									<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . ''; ?>
                                </a>
							<?php endif; ?>
                            <div class="product-item-right">
                                <h4 class="product-title">
                                    <a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
										<?php echo wp_specialchars_decode( $product_name ); ?>
                                    </a>
                                </h4>

                                <div class="descr-box">
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
									<?php
									if ( ! $_product->is_sold_individually() && $_product->is_purchasable() && ( ( etheme_get_option('cart_content_quantity_input_et-desktop', false) && !$is_mobile ) || ( etheme_get_option('cart_content_quantity_input_et-mobile', false) && $is_mobile ) ) ) {
										remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
										remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
										add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
										add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
										echo '<div class="quantity-wrapper clearfix">';
										woocommerce_quantity_input(
											array(
												'input_value' => $cart_item['quantity'],
												'min_value' => 1,
												'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
											),
											$_product
										);
										remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
										remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
										echo '<span class="quantity">' . ' &times; ' . $product_price . '</span>';
										echo '</div>';
									}
									
									echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key );
									
                                    if ( in_array('mini-cart', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart'))) && wc_product_sku_enabled() && $_product->get_sku() ) : ?>
                                        <div class="product_meta">
                                            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'xstore' ); ?>
                                                <span class="sku"><?php echo esc_html( ( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore' ) ); ?></span>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                
                                </div>
                            </div>
							
							<?php
							
							if ( $show_cart_content_linked_products ) {
								$_product_linked     = $_product;
								$_product_4_linked_ids = array( $product_id );
								if ( $_product->get_type() == 'variation' ) {
									$parent_id               = $_product->get_parent_id();
									$_product_4_linked_ids[] = $parent_id;
									$_product_linked       = wc_get_product( $parent_id );
								}
								
								if ( $cart_content_linked_products_type == 'upsell' ) {
									
									$etheme_mini_cart_global['upsell_ids']        =
										array_merge( $etheme_mini_cart_global['upsell_ids'], array_map( 'absint', $_product_linked->get_upsell_ids() ) );
									$etheme_mini_cart_global['upsell_ids_not_in'] =
										array_merge( $etheme_mini_cart_global['upsell_ids_not_in'], $_product_4_linked_ids );
									
								}
								else {
									$etheme_mini_cart_global['cross-sell_ids']        =
										array_merge( $etheme_mini_cart_global['cross-sell_ids'], array_map( 'absint', $_product_linked->get_cross_sell_ids() ) );
									$etheme_mini_cart_global['cross-sell_ids_not_in'] =
										array_merge( $etheme_mini_cart_global['cross-sell_ids_not_in'], $_product_4_linked_ids );
								}
								
							}
							
							?>

                        </li>
						<?php
					}
				}
				do_action( 'woocommerce_mini_cart_contents' );
				?>
            </ul>
			
			<?php
			if ( $show_cart_content_linked_products ) {
//					if ( (!$is_mobile && get_theme_mod( 'cart_content_type_et-desktop', 'dropdown' ) == 'off_canvas')
//					     || ( $is_mobile && get_theme_mod( 'cart_content_type_et-mobile', 'dropdown' ) == 'off_canvas') ) {
//						et_mini_cart_linked_products();
//					}
				et_mini_cart_linked_products($cart_content_linked_products_type, $etheme_mini_cart_global);
			}
			?>
		
		<?php else : ?>
			<?php etheme_get_mini_cart_empty(); ?>
		<?php endif;
	}
}


if ( ! function_exists( 'etheme_get_mini_cart_empty' ) ) {
	function etheme_get_mini_cart_empty(){
		?>
        <div class="woocommerce-mini-cart__empty-message empty">
            <p><?php esc_html_e( 'No products in the cart.', 'xstore' ); ?></p>
			<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
                <a class="btn" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore') ?></span></a>
			<?php endif; ?>
        </div>
		<?php
	}
}


if ( ! function_exists( 'etheme_get_fragments' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'etheme_get_fragments', 30 );
	function etheme_get_fragments( $array = array() ) {
		ob_start();
		etheme_cart_total();
		$cart_total = ob_get_clean();
		
		ob_start();
		etheme_cart_number();
		$cart_number = ob_get_clean();
		
		ob_start();
		etheme_cart_items_count();
		$cart_count = ob_get_clean();
		
		$array['span.shop-text']    = $cart_total;
		$array['span.badge-number'] = $cart_number;
		$array['span.popup-count']  = $cart_count;
		
		return $array;
	}
}

// **********************************************************************//
// ! Product brand label
// **********************************************************************//
if ( ! function_exists( 'etheme_product_brand_image' ) ) {
	function etheme_product_brand_image() {
		global $post;
		$terms = wp_get_post_terms( $post->ID, 'brand' );
		
		if ( ! is_wp_error( $terms ) && count( $terms ) > 0 && etheme_get_option( 'show_brand', 1 ) ) {
			?>
            <div class="sidebar-widget product-brands">
                <h4 class="widget-title"><span><?php esc_html_e( 'Product brand', 'xstore' ) ?></span></h4>
				<?php
				foreach ( $terms as $brand ) {
					$thumbnail_id = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );
					?>
                    <a href="<?php echo get_term_link( $brand ); ?>">
						<?php if ( etheme_get_option( 'show_brand_title', 1 ) ) : ?>
                            <div class="view-products-title colorGrey"><?php echo esc_html( $brand->name ); ?></div>
						<?php endif;
						if ( $thumbnail_id && etheme_get_option( 'show_brand_image', 1 ) ) :
							echo wp_get_attachment_image( $thumbnail_id, 'full' );
						else : ?>
                            <div class="view-products-title colorGrey"><?php echo esc_html( $brand->name ); ?></div>
						<?php endif; ?>
                    </a>
					<?php if ( etheme_get_option( 'show_brand_desc', 1 ) ) : ?>
                        <div class="short-description text-center colorGrey">
                            <p><?php echo wp_specialchars_decode( $brand->description ); ?></p></div>
					<?php endif; ?>
                    <a href="<?php echo get_term_link( $brand ); ?>"
                       class="view-products"><?php esc_html_e( 'View all products', 'xstore' ); ?></a>
					<?php
				}
				?>
            </div>
			<?php
		}
	}
}

function etheme_get_custom_product_template() {
	$view_mode = etheme_get_view_mode();
	$view_mode = apply_filters( 'et_view-mode-grid', $view_mode == 'grid' );
	// set shop products custom template
	$grid_custom_template = etheme_get_option( 'custom_product_template', 'default' );
	$list_custom_template = etheme_get_option( 'custom_product_template_list', 'default' );
	$list_custom_template = ( $list_custom_template != '-1' ) ? $list_custom_template : $grid_custom_template;
	
	return $view_mode == 'grid' ? (int) $grid_custom_template : (int) $list_custom_template;
}

// **********************************************************************//
// ! Load elements for ajax shop filters/pagination
// **********************************************************************//
function et_ajax_shop() { ?>
    <div>

	    <?php
	    // fix to show inlined styles after ajax filters
	    add_filter('etheme_output_shortcodes_inline_css', '__return_true');
        if (apply_filters( 'etheme_force_elementor_css', false )): ?>
		    <?php wp_head(); ?>
	    <?php endif ?>
		
		<?php etheme_grid_list_switcher(); ?>
		<?php etheme_products_per_page_select(); ?>
		<?php if ( woocommerce_product_loop() ) : ?>
			
			<?php if ( is_active_sidebar( 'shop-filters-sidebar' ) ): ?>
                <div class="shop-filters widget-columns-<?php echo etheme_get_option( 'filters_columns', 3 ); ?><?php echo ( etheme_get_option( 'filter_opened', 0 ) ) ? ' filters-opened' : ''; ?>">
                    <div class="shop-filters-area">
						<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'shop-filters-sidebar' ) ): ?>
						<?php endif; ?>
                    </div>
                </div>
			<?php endif; ?>
			<?php woocommerce_product_loop_start(); ?>
			
			<?php if ( wc_get_loop_prop( 'total' ) ) { ?>
				
				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php do_action( 'woocommerce_shop_loop' ); ?>
					
					<?php wc_get_template_part( 'content', 'product' ); ?>
				
				<?php endwhile; // end of the loop. ?>
			
			<?php } ?>
			
			<?php woocommerce_product_loop_end(); ?>
		
		<?php elseif ( ! woocommerce_product_subcategories( array(
			'before' => woocommerce_product_loop_start( false ),
			'after'  => woocommerce_product_loop_end( false )
		) ) ) : ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php endif; ?>
		<?php do_action( 'woocommerce_before_main_content' ); ?>
		<?php do_action( 'woocommerce_archive_description' ); ?>
		
		<?php do_action( 'woocommerce_sidebar' ); ?>

        <div class="after-shop-loop">
			<?php
			/*** woocommerce_after_shop_loop hook** @hooked woocommerce_pagination - 10 */
			do_action( 'woocommerce_after_shop_loop' );
			?>
        </div>
		
		<?php
		$woocommerce_price_slider_params = array(
            'min_price' => isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '',
            'max_price' => isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '',
            'currency_format_num_decimals' => 0,
            'currency_format_symbol' => get_woocommerce_currency_symbol(),
            'currency_format_decimal_sep' => esc_attr( wc_get_price_decimal_separator() ),
            'currency_format_thousand_sep' => esc_attr( wc_get_price_thousand_separator() ),
            'currency_format' => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
        );
		?>
		<?php etheme_second_cat_desc(); ?>
        <div class="et_woocommerce_price_slider_params">
            <script type="text/javascript" >
                /* <![CDATA[ */
                var woocommerce_price_slider_params = <?php echo json_encode($woocommerce_price_slider_params); ?>;
                /* ]]> */
            </script>
        </div>
        <?php
            global $wp_query;
            if ( $wp_query->max_num_pages > 1 ) {
                etheme_enqueue_style( 'pagination', true );
            }
        ?>
	    <?php if (apply_filters( 'etheme_force_elementor_css', false )): ?>
		    <?php wp_footer(); ?>
	    <?php endif ?>
    </div>
	<?php die;
}
add_filter( 'woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3 );
if ( ! function_exists( 'etheme_woocommerce_sale_flash' ) ) {
	/**
	 * Product sale label function.
	 *
	 * @param string - sale label content
	 * @param object - post
	 * @param object - product
	 *
	 * @return string
	 * @version 1.0.0
	 * @since   6.1.6
	 */
	
	function etheme_woocommerce_sale_flash( $span, $post, $product ) {
		$element_options                   = array();
		$element_options['single_product'] = false;
		$element_options['single_product'] = apply_filters( 'etheme_sale_label_single', $element_options['single_product'] );
		$element_options['in_percentage']  = etheme_get_option( 'sale_percentage', 0 );
		$element_options['in_percentage']  = apply_filters( 'etheme_sale_label_percentage', $element_options['in_percentage'] );
		
		$element_options['is_customize_preview'] = get_query_var('et_is_customize_preview', false);
		
		$element_options['sale_icon']       = etheme_get_option( 'sale_icon', 1 );
		$element_options['sale_label_text'] = etheme_get_option( 'sale_icon_text', 'Sale' );
		$element_options['show_label']      = $element_options['sale_icon'] || $element_options['is_customize_preview'];
		
		$element_options['wrapper_class'] = '';
		
		if ( $element_options['single_product'] ) {
			$element_options['sale_label_type']     = etheme_get_option( 'product_sale_label_type_et-desktop', 'square' );
			$element_options['show_label']          = $element_options['sale_label_type'] != 'none' || $element_options['is_customize_preview'];
			$element_options['sale_label_position'] = etheme_get_option( 'product_sale_label_position_et-desktop', 'right' );
			$element_options['sale_label_text']     = etheme_get_option( 'product_sale_label_text_et-desktop', 'Sale' );
			$element_options['sale_label_text']     = ( $element_options['in_percentage'] ) ? etheme_sale_label_percentage_text( $product, $element_options['sale_label_text'] ) : $element_options['sale_label_text'];
			
			$element_options['class'] = 'type-' . $element_options['sale_label_type'];
			$element_options['class'] .= ' ' . $element_options['sale_label_position'];
			$element_options['class'] .= ' single-sale';
		} else {
			$element_options['sale_label_type']     = $element_options['sale_icon'] ? 'square' : 'none';
			$element_options['sale_label_position'] = 'left';
			$element_options['sale_label_text']     = ( $element_options['in_percentage'] ) ? etheme_sale_label_percentage_text( $product, $element_options['sale_label_text'] ) : $element_options['sale_label_text'];
			
			$element_options['class'] = 'type-' . $element_options['sale_label_type'];
			$element_options['class'] .= ' ' . $element_options['sale_label_position'];
		}
		
		if ( $element_options['sale_label_type'] == 'none' && $element_options['is_customize_preview'] ) {
			$element_options['wrapper_class'] .= ' dt-hide mob-hide';
		}
		
		if ( strpos( $element_options['sale_label_text'], '%' ) != false ) {
			$element_options['class'] .= ' with-percentage';
		}
		
		ob_start();
		
		if ( $element_options['show_label'] ) {
			echo '<div class="sale-wrapper ' . esc_attr( $element_options['wrapper_class'] ) . '"><span class="onsale ' . esc_attr( $element_options['class'] ) . '">' . $element_options['sale_label_text'] . '</span></div>';
		}
		
		unset( $element_options );
		
		return ob_get_clean();
	}
}

if ( ! function_exists( 'etheme_sale_label_percentage_text' ) ) {
	
	/**
	 * Product sale label percentage.
	 *
	 * @param object - product
	 * @param string - sale text ( when product is not on sale yet )
	 *
	 * @return string
	 * @since   6.1.6
	 * @version 1.0.0
	 */
	 
	function etheme_sale_label_percentage_text( $product_object, $sale_text ) {
		$element_options = array();
		if ( ! $product_object->is_on_sale() ) {
			return $sale_text;
		}
		$element_options['sale_label_text'] = $sale_text;
		$sale_variables = etheme_get_option( 'sale_percentage_variable', 0 );
		$product_type = $product_object->get_type();
		if ( $product_type == 'variable' ) {
//			$element_options['sale_label_text'] = $sale_text;
			if ( $sale_variables ) {
				$element_options['variation_sale_prices'] = array();
				foreach ( $product_object->get_available_variations() as $key ) {
					if ( $key['display_regular_price'] == $key['display_price'] ) {
						continue;
					}
					$element_options['variation_sale_prices'][] = (float) round( ( ( $key['display_regular_price'] - $key['display_price'] ) / $key['display_regular_price'] ) * 100 );
				}
				if ( count($element_options['variation_sale_prices']) )
				    $element_options['sale_label_text'] = sprintf( esc_html__( 'Up to %s', 'xstore' ), max( $element_options['variation_sale_prices'] ) . '%' );
//				    $element_options['sale_label_text'] = esc_html__( 'Up to', 'xstore' ) . ' ' . max( $element_options['variation_sale_prices'] ) . '%';
			}
		} elseif ( $product_type == 'grouped' ) {
			if ( $sale_variables ) {
				$element_options['grouped_sale_prices'] = array();
				foreach ( $product_object->get_children() as $key ) {
					$_product = wc_get_product( $key );
					if ( $_product->get_type() == 'variable' && $_product->is_on_sale() ) {
						foreach ( $_product->get_available_variations() as $key ) {
							if ( $key['display_regular_price'] == $key['display_price'] ) {
								continue;
							}
							$element_options['grouped_sale_prices'][] = (float) round( ( ( $key['display_regular_price'] - $key['display_price'] ) / $key['display_regular_price'] ) * 100 );
						}
					} else {
						if ( $_product->is_on_sale() ) {
							$regular_price = (float) $_product->get_regular_price();
							$sale_price    = (float) $_product->get_sale_price();
							if ( $regular_price == $sale_price ) {
								continue;
							}
							$element_options['grouped_sale_prices'][] = (float) round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
						}
					}
				}
//				$element_options['sale_label_text'] = esc_html__( 'Up to', 'xstore' ) . ' ' . max( $element_options['grouped_sale_prices'] ) . '%';
				$element_options['sale_label_text'] = sprintf( esc_html__( 'Up to %s', 'xstore' ), max( $element_options['grouped_sale_prices'] ) . '%' );
			}
//			else {
//				$element_options['sale_label_text'] = $sale_text;
//			}
		} else {
			if ( apply_filters( 'etheme_sale_label_percentage', etheme_get_option( 'sale_percentage', 0 ) ) ){
				if ( $product_type == 'bundle') {
					$element_options['regular_price']   = (float) $product_object->min_raw_regular_price;
					$element_options['sale_price']   = (float) $product_object->min_raw_price;
				}
				else {
					$element_options['regular_price'] = (float) $product_object->get_regular_price();
					$element_options['sale_price']    = (float) $product_object->get_sale_price();
				}
//				$element_options['sale_label_text'] = $sale_text;
				if ( $element_options['regular_price'] && $element_options['sale_price'] ) {
//					$element_options['sale_label_text'] .= ' ' . round( ( ( $element_options['regular_price'] - $element_options['sale_price'] ) / $element_options['regular_price'] ) * 100 ) . '%';
					$element_options['sale_label_text'] = sprintf( str_replace('{sales_text}', $element_options['sale_label_text'], __( '{sales_text} %s', 'xstore' )),round( ( ( $element_options['regular_price'] - $element_options['sale_price'] ) / $element_options['regular_price'] ) * 100 ) . '%' );
				}
			}
		}
		
		if ( class_exists( 'WAD_Discount' ) ) {
			$all_discounts = wad_get_active_discounts( true );
			$product_id    = $product_object->get_id();
			if ( in_array( $product_type, array( 'variation', 'variable' ) ) ) {
				$product_id = $product_object->get_available_variations();
			}
			foreach ( $all_discounts as $discount_type => $discounts ) {
				foreach ( $discounts as $discount_id ) {
					$discount_obj = new WAD_Discount( $discount_id );
					if ( $discount_obj->is_applicable( $product_id ) ) {
						$settings                           = $discount_obj->settings;
//						$element_options['sale_label_text'] = $sale_text . ' ' . $settings['percentage-or-fixed-amount'] . '%';
						$element_options['sale_label_text'] = sprintf( esc_html__( 'Sale %s', 'xstore' ),$settings['percentage-or-fixed-amount'] . '%' );
					}
				}
			}
		}
		
		return $element_options['sale_label_text'];
	}
}

if ( ! function_exists( 'remove_et_variation_gallery_filter' ) ) {
	
	/**
	 * Variation galleries.
	 * remove filters for product variation props to js encoding
	 *
	 * @param string
	 *
	 * @return string
	 * @version 1.0.0
	 * @since   6.2.12
	 */
	
	function remove_et_variation_gallery_filter( $ob_get_clean ) {
		remove_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
//		remove_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		
		return $ob_get_clean;
	}
}

if ( ! function_exists( 'add_et_variation_gallery_filter' ) ) {
	
	/**
	 * Variation galleries.
	 * add filters for product variation props to js encoding
	 *
	 * @param string
	 *
	 * @return string
	 * @version 1.0.0
	 * @since   6.2.12
	 */
	function add_et_variation_gallery_filter( $ob_get_clean ) {
		add_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
//		add_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		
		return $ob_get_clean;
	}
}

if ( ! function_exists( 'etheme_get_external_video' ) ) {
	function etheme_get_external_video( $post_id ) {
		if ( ! $post_id ) {
			return false;
		}
		$product_video_code = get_post_meta( $post_id, '_product_video_code', true );
		
		return $product_video_code;
	}
}

if ( ! function_exists( 'etheme_get_attach_video' ) ) {
	function etheme_get_attach_video( $post_id ) {
		if ( ! $post_id ) {
			return false;
		}
		return get_post_meta( $post_id, '_product_video_gallery', false );
	}
}


// new variation gallery

if ( ! function_exists( 'etheme_available_variation_gallery' ) ):
	function etheme_available_variation_gallery( $available_variation, $variationProductObject, $variation ) {
		
		if ( !etheme_get_option('enable_variation_gallery', 0) )
			return $available_variation;

        // // second value is for ajax
		// if ( ! ( class_exists('WooCommerce') && is_product() ) ) {
		// 	return $available_variation;
		// } // makes some errors in some cases but fix some busg in another cases
		
		$product_id         = absint( $variation->get_parent_id() );
		$variation_id       = absint( $variation->get_id() );
		$variation_image_id = absint( $variation->get_image_id() );

		$variation_gallery_images_meta_key = 'et_variation_gallery_images';
		$has_variation_gallery_images = (bool) get_post_meta( $variation_id, $variation_gallery_images_meta_key, true );

		// Compatibility with WooCommerce Additional Variation Images plugin
		if ( !$has_variation_gallery_images ) {
		    $has_variation_gallery_images = (bool)get_post_meta( $variation_id, '_wc_additional_variation_images', true );
		    if ( $has_variation_gallery_images )
		        $variation_gallery_images_meta_key = '_wc_additional_variation_images';

        }
		//  $product                      = wc_get_product( $product_id );
		
		if ( $has_variation_gallery_images ) {
		    if ( $variation_gallery_images_meta_key == '_wc_additional_variation_images' )
		        $gallery_images = (array) array_filter( explode( ',', get_post_meta( $variation_id, $variation_gallery_images_meta_key, true )));
            else
			    $gallery_images = (array) get_post_meta( $variation_id, $variation_gallery_images_meta_key, true );
		} else {
			// $gallery_images = $product->get_gallery_image_ids();
			$gallery_images = $variationProductObject->get_gallery_image_ids();
		}
		
		
		if ( $variation_image_id ) {
			// Add Variation Default Image
			array_unshift( $gallery_images, $variation_image_id );
		} else {
			// Add Product Default Image
			
			/*if ( has_post_thumbnail( $product_id ) ) {
				array_unshift( $gallery_images, get_post_thumbnail_id( $product_id ) );
			}*/
			$parent_product          = wc_get_product( $product_id );
			$parent_product_image_id = $parent_product->get_image_id();
			
			if ( ! empty( $parent_product_image_id ) ) {
				array_unshift( $gallery_images, $parent_product_image_id );
			}
		}
		
		$available_variation['variation_gallery_images'] = array();
		
		$index = 0;
		foreach ( $gallery_images as $i => $variation_gallery_image_id ) {
			$available_variation['variation_gallery_images'][ $i ] = et_get_gallery_image_props( $variation_gallery_image_id, $index );
			$index++;
		}
		
		return apply_filters( 'etheme_available_variation_gallery', $available_variation, $variation, $product_id );
	}
endif;

add_action( 'wp_footer', 'slider_template_js' );
add_action( 'wp_footer', 'thumbnail_template_js' );

function slider_template_js() {
	if ( !get_query_var( 'etheme_single_product_variation_gallery', false ) ) return;
	ob_start();
	?>
    <script type="text/html" id="tmpl-et-variation-gallery-slider-template">
        <div class="swiper-slide">
            <div class="woocommerce-product-gallery__image">
                <a href="{{data.url}}" data-large="{{data.full_src}}" data-width="{{data.full_src_w}}" data-height="{{data.full_src_h}}" data-index="{{data.index}}" class="woocommerce-main-image <# if (data.index < 1) { #> pswp-main-image <# } #> zoom">
                    <img class="{{data.class}}" width="{{data.src_w}}" height="{{data.src_h}}" src="{{data.src}}" alt="{{data.alt}}" title="{{data.title}}" data-caption="{{data.caption}}" data-src="{{data.full_src}}" data-large_image="{{data.full_src}}" data-large_image_width="{{data.full_src_w}}" data-large_image_height="{{data.full_src_h}}" <# if( data.srcset ){ #> srcset="{{data.srcset}}" <# } #> sizes="{{data.sizes}}"/>
                </a>
            </div>
        </div>
    </script>
	<?php echo ob_get_clean();
}

function thumbnail_template_js() {
	if ( !get_query_var( 'etheme_single_product_variation_gallery', false ) ) return;
	ob_start();
	?>
    <script type="text/html" id="tmpl-et-variation-gallery-thumbnail-template">
        <li class="<?php echo get_query_var('etheme_single_product_vertical_slider', false) ? 'slick-slide' : 'swiper-slide'; ?> thumbnail-item" <?php echo get_query_var('etheme_single_product_vertical_slider', false) ? 'style="width: 100%;"' : ''; ?>>
            <a href="{{data.url}}" data-small="{{data.src}}" data-large="{{data.full_src}}" data-width="{{data.full_src_w}}" data-height="{{data.full_src_h}}" class="pswp-additional zoom" title="{{data.a_title}}">
                <img class="{{data.thumbnail_class}}" width="{{data.thumbnail_src_w}}" height="{{data.thumbnail_src_h}}" src="{{data.thumbnail_src}}" alt="{{data.alt}}" title="{{data.title}}" />
            </a>
        </li>
    </script>
	<?php echo ob_get_clean();
}

// Get Default Gallery Images
add_action( 'wp_ajax_nopriv_et_get_default_variation_gallery', 'et_get_default_variation_gallery' );

add_action( 'wp_ajax_et_get_default_variation_gallery', 'et_get_default_variation_gallery' );


// Get Default Gallery Images
add_action( 'wp_ajax_nopriv_et_get_available_variation_images', 'et_get_available_variation_images' );

add_action( 'wp_ajax_et_get_available_variation_images', 'et_get_available_variation_images' );

if ( ! function_exists( 'et_get_default_variation_gallery' ) ):
	function et_get_default_variation_gallery() {
		$product_id = absint( $_POST['product_id'] );
		
		$images = et_get_default_variation_gallery_images( $product_id );
		
		wp_send_json_success( apply_filters( 'et_get_default_variation_gallery', $images, $product_id ) );
	}
endif;

//-------------------------------------------------------------------------------
// Get Default Gallery Images
//-------------------------------------------------------------------------------
if ( ! function_exists( 'et_get_default_variation_gallery_images' ) ):
	function et_get_default_variation_gallery_images( $product_id ) {
		
		$product           = wc_get_product( $product_id );
//		$product_id        = $product->get_id();
		$attachment_ids    = $product->get_gallery_image_ids();
		$post_thumbnail_id = $product->get_image_id();
		
		$images = array();
		
		$post_thumbnail_id = (int) apply_filters( 'et_variation_gallery_post_thumbnail_id', $post_thumbnail_id, $attachment_ids, $product );
		$attachment_ids    = (array) apply_filters( 'et_variation_gallery_attachment_ids', $attachment_ids, $post_thumbnail_id, $product );
		
		
		if ( ! empty( $post_thumbnail_id ) ) {
			array_unshift( $attachment_ids, $post_thumbnail_id );
		}
		
		if ( is_array( $attachment_ids ) && ! empty( $attachment_ids ) ) {
			
			$index = 0;
			
			foreach ( $attachment_ids as $i => $image_id ) {
				$images[ $i ] = et_get_gallery_image_props( $image_id, $index );
				$index++;
			}
		}
		
		return apply_filters( 'et_get_default_variation_gallery_images', $images, $product );
	}
endif;

/**
 * Description of the function.
 *
 * @param       $attachment_id
 * @param       bool $index
 * @return      mixed
 *
 * @since 1.0.0
 *
 */
function et_get_gallery_image_props( $attachment_id, $index = false ) {
	
	$props      = array(
		'title'                   => '',
		'caption'                 => '',
		'url'                     => '',
		'alt'                     => '',
		'class' => 'attachment-woocommerce_single size-woocommerce_single wp-post-image',
		'full_src'                => '',
		'full_src_w'              => '',
		'full_src_h'              => '',
		'thumbnail_src'           => '',
		'thumbnail_src_w'         => '',
		'thumbnail_src_h'         => '',
		'thumbnail_class'         => '',
		'src'                     => '',
		'src_w'                   => '',
		'src_h'                   => '',
		'srcset'                  => '',
		'sizes'                   => '',
		'index'                   => $index
	);
	$attachment = get_post( $attachment_id );
	
	if ( $attachment ) {
		
		$props['title']    = _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true );
		$props['caption']  = _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true );
		$props['url']      = wp_get_attachment_url( $attachment_id );
		
		// Alt text.
		$alt_text = array(
			trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
			$props['caption'],
			wp_strip_all_tags( $attachment->post_title )
		);
		
		$alt_text     = array_filter( $alt_text );
		$props['alt'] = isset( $alt_text[0] ) ? $alt_text[0] : '';
		
		// Large version.
		$full_size           = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$full_size_src       = wp_get_attachment_image_src( $attachment_id, $full_size );
		$props['full_src']   = esc_url( $full_size_src[0] );
		$props['full_src_w'] = esc_attr( $full_size_src[1] );
		$props['full_src_h'] = esc_attr( $full_size_src[2] );
		
		
		// Gallery thumbnail.
		$thumbnail_size                = apply_filters('single_product_small_thumbnail_size', 'woocommerce_thumbnail' );
		$thumbnail_src            = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$props['thumbnail_src']   = esc_url( $thumbnail_src[0] );
		$props['thumbnail_src_w'] = esc_attr( $thumbnail_src[1] );
		$props['thumbnail_src_h'] = esc_attr( $thumbnail_src[2] );
		
		$image_size     = apply_filters( 'woocommerce_gallery_image_size', 'woocommerce_single' );
		$src            = wp_get_attachment_image_src( $attachment_id, $image_size );
		$props['src']   = esc_url( $src[0] );
		$props['src_w'] = esc_attr( $src[1] );
		$props['src_h'] = esc_attr( $src[2] );
		
		$props['srcset'] = wp_get_attachment_image_srcset( $attachment_id, $image_size );
		$props['sizes']  = wp_get_attachment_image_sizes( $attachment_id, $image_size );
		
	}
	
	return apply_filters( 'woo_variation_gallery_get_image_props', $props, $attachment_id );
}

//-------------------------------------------------------------------------------
// Ajax request of non ajax variation
//-------------------------------------------------------------------------------
if ( ! function_exists( 'et_get_available_variation_images' ) ):
	function et_get_available_variation_images( $product_id = false ) {
		$product_id           = $product_id ? $product_id : absint( $_POST[ 'product_id' ] );
		$images               = array();
		$available_variations = et_get_product_variations( $product_id );
		
		foreach ( $available_variations as $i => $variation ) {
			if ( !is_array($variation) || !isset($variation['variation_gallery_images']) || !isset($variation['image'])) continue;
			// on testing line above
			array_push( $variation[ 'variation_gallery_images' ], $variation[ 'image' ] );
		}
		
		foreach ( $available_variations as $i => $variation ) {
			if ( !is_array($variation) || !isset($variation['variation_gallery_images'])) continue;
			// on testing line above
			foreach ( $variation[ 'variation_gallery_images' ] as $image ) {
				if ( $image ) {
					// on testing line above
					array_push( $images, $image );
				}
			}
		}
		
		wp_send_json_success( apply_filters( 'et_get_available_variation_images', $images, $product_id ) );
	}
endif;

if ( ! function_exists( 'et_get_product_variations' ) ):
	/**
	 * get variations of product
	 */
	function et_get_product_variations( $product ) {
		
		if ( is_numeric( $product ) ) {
			$product = wc_get_product( absint( $product ) );
		}
		
		return $product->get_available_variations();
	}
endif;

function et_clear_default_variation_transient_by_product( $product_id ) {
	if ( $product_id > 0 ) {
		$transient_name = sprintf( 'et_get_product_default_variation_%s', $product_id );
		delete_transient( $transient_name );
	}
}

function et_clear_default_variation_transient_by_variation( $variation ) {
	$product_id = $variation->get_parent_id();
	et_clear_default_variation_transient_by_product( $product_id );
}

function et_add_action_to_multi_currency_ajax( $array ) {
	$array[] = 'etheme_product_quick_view'; // Add a AJAX action to the array
	return $array;
}

add_filter( 'wcml_multi_currency_ajax_actions', 'et_add_action_to_multi_currency_ajax');
if ( ! function_exists('etheme_widget_get_current_page_url') ){
	function etheme_widget_get_current_page_url( $link ) {
		if ( isset( $_GET['stock_status'] ) ) {
			$link = add_query_arg( 'stock_status', wc_clean( $_GET['stock_status'] ), $link );
		}
		if ( isset( $_GET['sale_status'] ) ) {
			$link = add_query_arg( 'sale_status', wc_clean( $_GET['sale_status'] ), $link );
		}
		if ( isset( $_GET['et_columns-count'] ) ) {
			$link = add_query_arg( 'et_columns-count', wc_clean( $_GET['et_columns-count'] ), $link );
		}
		return $link;
	}
}
add_filter( 'woocommerce_widget_get_current_page_url',  'etheme_widget_get_current_page_url' );
add_action( 'template_redirect', 'etheme_checkout_redirect' );
if (!function_exists('etheme_checkout_redirect')){
	function etheme_checkout_redirect() {
		if (isset($_REQUEST['et_buy_now']) && etheme_get_option('buy_now_btn',0)){
			wp_safe_redirect(wc_get_checkout_url());
			exit();
		}
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
if (!function_exists('etheme_buy_now_btn')) {
	function etheme_buy_now_btn($type = '') {
		if ( ! etheme_get_option( 'buy_now_btn', 0 ) ) {
			return;
		}

		$btn_text = __( 'Buy now', 'xstore' );

		global $product;
		if ( !in_array($product->get_type(), array('external')) ) { ?>
            <div class="text-center et-or-wrapper">
                <div>
                    <span><?php esc_html_e('or', 'xstore'); ?></span>
                </div>
            </div>
            <?php if($type == 'variable-quick-view') : ?>
                <a
                    href="<?php echo esc_attr( $product->get_permalink() ); ?>"
                    data-redirect="<?php echo esc_js(wc_get_checkout_url()); ?>"
                    data-text="<?php echo esc_attr($btn_text); ?>"
                    data-quantity="1"
                    data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                    class="et_product_variable-in-quick-view product_type_variable add_to_cart_button et-st-disabled et-single-buy-now single_add_to_cart_button button alt"
                >
                    <?php echo esc_html__( 'Select options', 'xstore' ); ?>
                </a>
			<?php else : ?>
                <button type="submit" data-quantity="" data-product_id="" class="et-single-buy-now single_add_to_cart_button button alt"><?php echo esc_html($btn_text); ?></button>
			<?php endif; ?>
		<?php }
	}
}

add_action( 'woocommerce_before_add_to_cart_button', 'etheme_show_single_stock', 10 );
function etheme_show_single_stock(){
	if ( ! etheme_get_option( 'show_single_stock', 0 ) ) {
		return;
	}
	global $product;
	if (
		! $product->get_stock_quantity()
		&& $product->get_stock_status() == 'instock'
        && !in_array($product->get_type(), array('variable', 'external'))
	){
	    if ( get_theme_mod('advanced_stock_status', false) && in_array('single_product', (array)etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') )) && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
	        echo et_product_stock_line($product, '<p class="et_stock et_in-stock stock in-stock step-1">'.esc_html__('In stock', 'xstore').'</p>');
	    }
	    else {
		    echo '<p class="et_stock et_in-stock stock in-stock step-1">'.esc_html__('In stock', 'xstore').'</p>';
		}
	}
}

add_filter( 'woocommerce_available_variation', 'etheme_show_single_variation_stock', 10, 2 );
function etheme_show_single_variation_stock($return,$variation){
    // @todo advanced_stock_status option will work correctly once when woocommerce fix the issue of total sales for variations ->
	// https://github.com/woocommerce/woocommerce/issues/25040
	if ( ! etheme_get_option( 'show_single_stock', 0 ) ) {
        if ( get_theme_mod('advanced_stock_status', false) && in_array('single_product', (array)etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') )) && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
            $return['availability_html'] = et_product_stock_line($variation, (isset($return['availability_html']) ? $return['availability_html'] : false));
        }
		return $return;
	}
	if(empty($return['availability_html'])){
		$return['availability_html'] = '<p class="et_stock et_in-stock stock in-stock step-1">'.esc_html__('In stock', 'xstore').'</p>';
	}
	if ( get_theme_mod('advanced_stock_status', false) && in_array('single_product', (array)etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') )) && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
        $return['availability_html'] = et_product_stock_line($variation, $return['availability_html']);
    }
	return $return;
}

// -----------------------------------------
// 3. Store custom field value into variation data

add_filter( 'woocommerce_available_variation', 'et_add_custom_field_variation_data' );

function et_add_custom_field_variation_data( $variations ) {
    // add sale dates params to reinit countdown on changing in single product
    $date_end = get_post_meta( $variations[ 'variation_id' ], '_sale_price_dates_to', true );
    if ( $date_end ) {
        $date_start = get_post_meta( $variations[ 'variation_id' ], '_sale_price_dates_from', true );
        $time_start = get_post_meta( $variations[ 'variation_id' ], '_sale_price_time_start', true );
        $time_start = explode( ':', $time_start );
        $time_end = get_post_meta( $variations[ 'variation_id' ], '_sale_price_time_end', true );
        $time_end = explode( ':', $time_end );
        $start_sale_date = array(
            'day'    => date( 'd', (int)$date_start ),
            'month'  => date( 'M', (int)$date_start ),
            'year'   => date( 'Y', (int)$date_start ),
            'hour_minute'   => (( isset( $time_start[0] ) && $time_start[0] != 'Array' && $time_start[0] > 0 ) ? $time_start[0] : '00') . ':' .
            (isset( $time_start[1] ) ? $time_start[1] : '00'),
        );
        $end_sale_date = array(
            'day'    => date( 'd', (int)$date_end ),
            'month'  => date( 'M', (int)$date_end ),
            'year'   => date( 'Y', (int)$date_end ),
            'hour_minute'   => (( isset( $time_end[0] ) && $time_end[0] != 'Array' && $time_end[0] > 0 ) ? $time_end[0] : '00') . ':' .
            (isset( $time_end[1] ) ? $time_end[1] : '00'),

        );
        $variations['_sale_price_start'] = implode(' ', $start_sale_date);
        $variations['_sale_price_end'] = implode(' ', $end_sale_date);
    }

    $gtin = get_post_meta( $variations[ 'variation_id' ], '_et_gtin', true );
    if ( $gtin )
        $variations['_et_gtin'] = $gtin;
    return $variations;
}

function etheme_cross_sell_display( $limit = '-1', $columns = 4, $orderby = 'rand', $order = 'desc' ) {
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	// Handle the legacy filter which controlled posts per page etc.
	$args = apply_filters(
		'woocommerce_cross_sells_display_args',
		array(
			'posts_per_page' => $limit,
			'orderby'        => $orderby,
			'order'          => $order,
			'columns'        => $columns,
		)
	);
	wc_set_loop_prop( 'name', 'cross-sells' );
	wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_cross_sells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns ) );
	
	$orderby = apply_filters( 'woocommerce_cross_sells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
	$order   = apply_filters( 'woocommerce_cross_sells_order', isset( $args['order'] ) ? $args['order'] : $order );
	$limit   = apply_filters( 'woocommerce_cross_sells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );
	
	// Get visible upsells then sort them at random, then limit result set.
//	$cross_sells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_cross_sell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
	$cross_sells = $product->get_cross_sell_ids();
	$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;
	
	wc_get_template(
		'cart/cross-sells.php',
		array(
			'cross_sells'    => $cross_sells,
			
			// Not used now, but used in previous version of cross-sells.php.
			'posts_per_page' => $limit,
			'orderby'        => $orderby,
			'columns'        => $columns,
		)
	);
}


add_action( 'wp_ajax_etheme_svp_cart', 'etheme_svp_cart' );
add_action( 'wp_ajax_nopriv_etheme_svp_cart', 'etheme_svp_cart' );
if ( ! function_exists( 'etheme_svp_cart' ) ) {
	function etheme_svp_cart() {

		$product_id   = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$quantity     = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
		$variation_id = $_POST['variation_id'];
		$variation    = array();
		$data         = array();
		$cart = $_POST;
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		foreach ($cart as $key => $value) {
			if (preg_match("/^attribute*/", $key)) {
				$variation[$key] = $value;
			}
		}

		foreach ($variation as $key=>$value) {
			$variation[$key] = stripslashes($value);
		}

		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10);
			add_action('woocommerce_widget_shopping_cart_total', 'etheme_woocommerce_widget_shopping_cart_subtotal', 10);

			if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
				wc_add_to_cart_message( $product_id );
			}

			$data = WC_AJAX::get_refreshed_fragments();
		} else {

			if (class_exists('WC_AJAX') && defined('WC_AJAX') && method_exists(WC_AJAX,'json_headers')){
				WC_AJAX::json_headers();
			}

			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
			);
		}

		wp_send_json( $data );
		wp_die();
	}
}


add_filter( 'woocommerce_loop_add_to_cart_args', 'etheme_product_loop_data_name', 10, 2 );
function etheme_product_loop_data_name($args, $product){
	$args['attributes']['data-product_name'] = $product->get_name();
	return $args;
}

add_filter( 'woocommerce_get_price_html', 'et_custom_price_html', 100, 2 );
add_filter( 'woocommerce_get_sale_price_html', 'et_custom_price_html', 100, 2 );
add_filter( 'woocommerce_variable_price_html', 'et_custom_price_html', 10, 2 );
add_filter( 'woocommerce_variable_sale_price_html', 'et_custom_price_html', 10, 2 );

function et_custom_price_html( $price, $product ) {
	$etheme_ltv_price = etheme_get_option( 'ltv_price', esc_html__( 'Login to view price', 'xstore' ) );
	$is_catalog = defined( 'DOING_AJAX' ) && DOING_AJAX ? etheme_is_catalog() : get_query_var('et_is-catalog', false);
 
	if ( $is_catalog && etheme_get_option('just_catalog_price', 0) && $etheme_ltv_price != '' ) {
		return $etheme_ltv_price;
	} else {
		return $price;
	}
}

function etheme_variation_title($title, $product) {
	if ( ! $product->is_type( 'variation' ) ) {
		return $title;
	}

	$saved_title = get_post_meta( $product->get_id(), '_et_product_variation_title', true );

	if ( ! empty( $saved_title ) ) {
		return $saved_title;
	}

	return $title;
}

add_action('init', function() {
	$variable_products_detach = etheme_get_option( 'variable_products_detach', false );
	
	if ( ! $variable_products_detach ) {
		return;
	}
	
	add_filter( 'woocommerce_product_title', 'etheme_variation_title', 10, 2 );
	
	add_filter( 'woocommerce_product_variation_title', 'etheme_variation_title', 10, 2 );
	
	add_action( 'woocommerce_product_query', function ( $q, $_this ) {
		
		$q->set( 'post_type', array_unique( array_merge( (array) $q->get( 'post_type' ), array(
			'product',
			'product_variation'
		) ) ) );
		$q->set( 'post_status', array( 'publish' ) );
		$q->set( 'single_variations_filter', 'yes' );
		
		// hide all variable products
        $variable_products_no_parent = etheme_get_option( 'variation_product_parent_hidden', true );
        if ( $variable_products_no_parent ) {
	        $q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in', true ), etheme_product_variable_with_children_excluded() ) );
        }
		
        
		$q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in', true ), etheme_product_variations_excluded() ) );

		return $q;
	}, 10, 2 );
	
	// filter woocommerce shortcode query
//	add_filter('woocommerce_shortcode_products_query', function($query_args, $attributes, $type) {
//		$post_type   = (array) $query_args['post_type'];
//		$post_type[] = 'product_variation';
//		$query_args['post_type'] = $post_type;
//		$query_args['single_variations_filter'] = 'yes';
//
//		// hide all variable products
//		$variable_products_no_parent = etheme_get_option( 'variation_product_parent_hidden', true );
//		if ( $variable_products_no_parent ) {
//			$query_args['post__not_in'] = array_merge((array)$query_args['post__not_in'], etheme_product_variable_with_children_excluded());
//		}
//
//		$query_args['post__not_in'] = array_merge((array)$query_args['post__not_in'], etheme_product_variations_excluded());
//
//		return $query_args;
//	}, 10, 3);
	
	// related posts query
//	add_filter( 'woocommerce_product_related_posts_query', function($query, $product_id){
//		$find    = "AND p.post_type = 'product'";
//		$replace = "AND ( p.post_type = 'product' OR p.post_type = 'product_variation' )";
//
//		$query['where'] = str_replace( $find, $replace, $query['where'] );
//
//		return $query;
//	}, 10, 2 );
//
//	add_filter('woocommerce_products_widget_query_args', function($query_args) {
//		$post_type   = (array) $query_args['post_type'];
//		$post_type[] = 'product_variation';
//		$query_args['post_type'] = $post_type;
//		return $query_args;
//	}, 10, 1);
	
	// price widget filter by variations price too
	add_filter('woocommerce_price_filter_post_type', function($post_type) {
		$post_type[] = 'product_variation';
		return $post_type;
	}, 10, 1);
	
	add_filter( 'posts_clauses', function ($clauses, $query){
		global $wpdb;
		if(isset($query->query_vars['single_variations_filter']) && $query->query_vars['single_variations_filter']=='yes'){
			$_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
			$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
			$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
			$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
			$clauses['where'] .= " OR ({$wpdb->posts}.post_type = 'product_variation' AND {$wpdb->posts}.post_status = 'publish' ";
//			$clauses['where'] .= " AND {$wpdb->posts}.post_parent IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'product' AND post_status = 'publish' ) ";
            if( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') && defined( 'ICL_LANGUAGE_CODE' ) ) {
//                $clauses['where'] .= " AND {$wpdb->posts}.post_parent IN (SELECT $wpdb->icl_translations.element_id FROM $wpdb->icl_translations WHERE language_code = " . ICL_LANGUAGE_CODE . " )";
	            $clauses['where'] .= " AND {$wpdb->posts}.post_parent IN (SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE language_code = '" . ICL_LANGUAGE_CODE . "' )";
            }
			if ( count((array)$query->query_vars['post__not_in'])) {
				$clauses['where'] .= "AND {$wpdb->posts}.ID NOT IN (".implode(',', $query->query_vars['post__not_in']).")";
			}
			if ( isset($query->queried_object->term_id ) ) {
				$clauses['where'] .= "AND {$wpdb->posts}.post_parent IN (SELECT $wpdb->term_relationships.object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $query->queried_object->term_id . ") ) ";
			}
			if ( $min_price ) {
				$clauses['where'] .= " AND {$wpdb->posts}.ID IN (SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta WHERE meta_key = '_price' AND meta_value >= $min_price)";
            }
			if ( $max_price ) {
				$clauses['where'] .= " AND {$wpdb->posts}.ID IN (SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta WHERE meta_key = '_price' AND meta_value <= $max_price)";
			}
			if ( $rating_filter ) {
				$posts_in = array();
				foreach ($rating_filter as $rating) {
					$posts_in = array_merge($posts_in, etheme_products_variable_excluded(
							array(
								'visibility' => 'rated-'.$rating
                            )
						)
					);
				}
				if ( count($posts_in) ) {
					$clauses['where'] .= "AND {$wpdb->posts}.post_parent IN (
                            " . implode( ',', $posts_in ) . "
                        ) ";
				}
			}
			if ( isset( $_GET['filter_brand'] ) && ! empty($_GET['filter_brand']) ) {
				
				$brands = explode(',', $_GET['filter_brand']);
				$ids    = array();
				
				foreach ($brands as $key => $value) {
					$term = get_term_by('slug', $value, 'brand');
					if ( ! isset( $term->term_taxonomy_id ) || empty( $term->term_taxonomy_id ) ) // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
					{
					} else {
						$ids[] = $term->term_taxonomy_id;
					}
				}
				
				if ( ! implode( ',', $ids ) ) {
					$ids = 0;
				} else {
					$ids = implode( ',', $ids );
				}
				
				$clauses['where'] .= " AND {$wpdb->posts}.post_parent IN ( SELECT {$wpdb->term_relationships}.object_id  FROM {$wpdb->term_relationships} WHERE term_taxonomy_id  IN (" . $ids . ") )";
				
			}
			if ( isset($_GET['stock_status'])) {
				$stock = str_replace('_','', $_GET['stock_status']);
				$stock = explode(',',$stock);
				$stock_by = array();
				
				foreach ($stock as $stock_val) {
					$stock_by[] = "'".$stock_val."'";
				}
				$clauses['where'] .= "AND {$wpdb->posts}.ID IN (SELECT {$wpdb->postmeta}.post_id FROM {$wpdb->postmeta}
		            WHERE ( meta_key = '_stock_status' AND meta_value IN (".implode(',', $stock_by).") ) ) ";
			}
			if ( isset($_GET['sale_status'])) {
				$clauses['where'] .= " AND {$wpdb->posts}.ID IN (
                    SELECT ID FROM $wpdb->posts AS p
                        INNER JOIN $wpdb->postmeta AS meta ON p.ID = meta.post_id
                            AND meta.meta_key = '_sale_price'
                            AND meta.meta_value != ''
			) ";
			}
			if ( ! empty( $_chosen_attributes ) ) {
				foreach ( $_chosen_attributes as $taxonomy => $data ) {
					$t_slugs = array();
					
					$query_type = $data['query_type'];
					
					foreach ( $data['terms'] as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, $taxonomy );
						if ( ! $term ) {
							continue;
						}
						
						if ( 0 === strpos( $term_slug, 'filter_' ) ) {
							$attribute = wc_sanitize_taxonomy_name( str_replace( 'filter_', '', $term_slug ) );
							$query_type = ! empty( $_GET[ 'query_type_' . $attribute ] ) && in_array( $_GET[ 'query_type_' . $attribute ], array(
								'and',
								'or'
							), true )
								?
								wc_clean( wp_unslash( $_GET[ 'query_type_' . $attribute ] ) ) :
								$query_type;
						}
						
						if ($query_type == 'and') {
							$clauses['where'] .=  " AND {$wpdb->posts}.ID IN (
	                                SELECT $wpdb->postmeta.post_id  FROM $wpdb->postmeta WHERE
	                                ( meta_key = 'attribute_".$taxonomy."' AND meta_value IN ('" . $term_slug . "') )
                                )";
						} else {
							$t_slugs[] = "'".$term_slug."'";
						}
					}
					
					if ($query_type == 'or'){
						if ( ! implode( ',', $t_slugs ) ) {
							$t_slugs = 0;
						} else {
							$t_slugs = implode( ',', $t_slugs );
						}
						$clauses['where'] .=  " AND {$wpdb->posts}.ID IN (
	                                SELECT $wpdb->postmeta.post_id  FROM $wpdb->postmeta WHERE
	                                ( meta_key = 'attribute_".$taxonomy."' AND meta_value IN (" . $t_slugs . ") )
                                )";
					}
				}
			}
			
			$clauses['where'] .= ")";
//            }

//			$outofstock .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . get_queried_object()->term_id . ") )";
		}
		return $clauses;
	}, 20, 2);
	
	add_filter( 'woocommerce_product_variation_get_average_rating', 'etheme_product_variation_rating', 10, 2 );
	
	$variation_product_widgets_recount = etheme_get_option('variation_product_widgets_recount', false);
	if ( $variation_product_widgets_recount ) {
		add_filter( 'woocommerce_layered_nav_count', 'etheme_woocommerce_layered_nav_count_filter', 10, 3 );
		
		add_filter('woocommerce_rating_filter_count', function($html, $count_old, $rating){
			$products = count(etheme_product_variations_excluded(array(
			        'visibility' => 'rated-'.$rating
            )));
			$count = $count_old;
			if ( $products ) {
				$variable_products_no_parent = etheme_get_option( 'variation_product_parent_hidden', true );
				$count+=$products;
				if ( $variable_products_no_parent && $products > 0 ) {
					$count --;
				}
            }
		    return str_replace( $count_old, $count, $html );
        }, 10, 3);
		
		add_filter('etheme_brands_widget_count', 'etheme_brands_widget_count_filter', 10, 3);
		
		// for product categories, product_tags count
		add_filter( 'get_terms', 'etheme_get_terms_filter', 100, 2 );
	}
	
    add_action( 'deleted_transient', function($transient){
        if ( $transient !== 'wc_term_counts' ) {
            return;
        }
        
        delete_transient( 'et_variations_ids' );
        delete_transient('et_variable_products_have_children_ids');
        delete_transient('et_variable_products_excluded_have_children_ids');
    }, 10, 1 );

});

function etheme_product_variations_excluded($args = array()) {
	$args = shortcode_atts( array(
		'taxonomy'            => 'product_visibility',
		'field'               => 'term_taxonomy_id',
		'terms'               => 'visibility',
		'visibility'          => 'exclude-from-catalog',
		'visibility_operator' => 'IN',
		'only_parent'         => false,
	), $args );
	if ( $args['terms'] == 'visibility' ) {
		$terms         = wc_get_product_visibility_term_ids();
		$args['terms'] = $terms[ $args['visibility'] ];
	}
	// hide product variation of hidden product on catalog
	$query_args = array(
		'post_type' => 'product',
		'numberposts'      => -1,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => 'variable',
				'operator' => 'IN',
			),
			array(
				'taxonomy' => $args['taxonomy'],
				'field'    => $args['field'],
				'terms'    => $args['terms'],
				'operator' => $args['visibility_operator'],
			),
		)
	);
	$query         = http_build_query( $query_args );
	$query_hash    = md5( $query );
	$variation_ids = (array) get_transient( 'et_variations_ids' );
	$hide_outofstock = get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes';
	if ( isset( $variation_ids[ $query_hash ] ) ) {
		$ids = $variation_ids[ $query_hash ];
	}
	else {
		$results = get_posts( $query );
		$ids     = array();
		foreach ( $results as $key => $post ) {
			if ( $args['only_parent'] ) {
				$ids[] = $post->ID;
			} else {
				$product = wc_get_product( $post->ID );
				$variations = $product->get_children();
				if ( count( $variations ) ) {
					foreach ( $variations as $variation_id ) {
						$ids[] = $variation_id;
					}
				}
            }
        }
		// new - hide out of stock variations if woocommerce option is enabled
		if ( !$args['only_parent'] ) {
			$query_args = array(
				'post_type' => 'product',
				'numberposts'      => -1,
				'post_status'    => 'any',
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'variable',
						'operator' => 'IN',
					),
				)
			);
			$query         = http_build_query( $query_args );
//			$query_hash    = md5( $query );
			$results = get_posts( $query );
			
            $attribute_to_limit = get_theme_mod('variation_product_primary_attribute', 'et_none');
            $attribute_to_limit_parse = $attribute_to_limit != 'et_none';
	
			foreach ( $results as $key => $post ) {
				$product = wc_get_product( $post->ID );
				$variations = $product->get_children();
				if ( count( $variations ) ) {
				    $ids_have_attributes = array();
					foreach ( $variations as $variation_id ) {
						$variation_product = wc_get_product( $variation_id );
						if ( !$variation_product || 'publish' != get_post_status($post->ID)) {
						    $ids[] = $variation_id;
						}
						elseif ( $hide_outofstock && (!$variation_product || ! $variation_product->is_in_stock() || !$variation_product->variation_is_visible()) ){
							$ids[] = $variation_id;
						}
						
						if ( $attribute_to_limit_parse ) {
                            $attributes = $variation_product->get_attributes();
                            if (array_key_exists('pa_'.$attribute_to_limit, $attributes)) {
                                if ( !empty($attributes['pa_'.$attribute_to_limit]) )
                                    $ids_have_attributes['pa_'.$attribute_to_limit][$attributes['pa_'.$attribute_to_limit]][] = $variation_id;
                                else
                                    $ids[] = $variation_id;
                            }
						}
					}
					
					if ( count($ids_have_attributes) ) {
                        foreach ($ids_have_attributes['pa_'.$attribute_to_limit] as $ids_have_attribute_key => $ids_have_attribute) {
                            array_shift($ids_have_attributes['pa_'.$attribute_to_limit][$ids_have_attribute_key]);
                            $ids = array_merge($ids, $ids_have_attributes['pa_'.$attribute_to_limit][$ids_have_attribute_key]);
                        }
					}
				}
            }
        }
		
		$variation_ids[ $query_hash ] = array_unique($ids);
		set_transient( 'et_variations_ids', $variation_ids, DAY_IN_SECONDS );
    }
	return $ids;
}

if ( !function_exists('etheme_products_variable_excluded') ) {
    function etheme_products_variable_excluded($args = array()) {
	    $args = shortcode_atts( array(
		    'taxonomy'            => 'product_visibility',
		    'field'               => 'term_taxonomy_id',
		    'terms'               => 'visibility',
		    'visibility'          => 'exclude-from-catalog',
		    'visibility_operator' => 'IN',
	    ), $args );
	    if ( $args['terms'] == 'visibility' ) {
		    $terms         = wc_get_product_visibility_term_ids();
		    $args['terms'] = $terms[ $args['visibility'] ];
	    }
	    // hide product variation of hidden product on catalog
	    $query_args = array(
		    'post_type' => 'product',
		    'numberposts'      => -1,
		    'tax_query' => array(
			    'relation' => 'AND',
			    array(
				    'taxonomy' => 'product_type',
				    'field'    => 'slug',
				    'terms'    => 'variable',
				    'operator' => 'IN',
			    ),
			    array(
				    'taxonomy' => $args['taxonomy'],
				    'field'    => $args['field'],
				    'terms'    => $args['terms'],
				    'operator' => $args['visibility_operator'],
			    ),
		    )
	    );
	    $query         = http_build_query( $query_args );
	    $query_hash    = md5( $query );
	    $variable_products_ids = (array) get_transient( 'et_variable_products_excluded_have_children_ids' );
	    if ( isset( $variable_products_ids[ $query_hash ] ) ) {
		    $ids = $variable_products_ids[ $query_hash ];
	    }
	    else {
		    $results = get_posts( $query );
		    $ids     = array();
		    foreach ( $results as $key => $post ) {
                $product = wc_get_product( $post->ID );
                if ( count( $product->get_children() ) ) {
                    $ids[] = $post->ID;
                }
		    }
		    $variable_products_ids[ $query_hash ] = $ids;
		    set_transient( 'et_variable_products_excluded_have_children_ids', $variable_products_ids, DAY_IN_SECONDS );
	    }
	    return $ids;
    }
}

if ( !function_exists('etheme_product_variable_with_children_excluded')) {
    function etheme_product_variable_with_children_excluded() {
	    $ids = get_transient('et_variable_products_have_children_ids');
	    if ( !$ids) {
		    $query_args = array(
			    'post_type' => 'product',
			    'numberposts'      => -1,
			    'tax_query' => array(
				    array(
					    'taxonomy' => 'product_type',
					    'field'    => 'slug',
					    'terms'    => 'variable',
					    'operator' => 'IN',
				    ),
			    )
		    );
		    $products = get_posts( http_build_query( $query_args ) );
		    $ids = array();
		    foreach ( $products as $key => $post ) {
			    $product = wc_get_product( $post->ID );
                if ( count($product->get_children()) ) {
                        $ids[] = $post->ID;
                }
            }
            set_transient('et_variable_products_have_children_ids', $ids, DAY_IN_SECONDS );
            
	    }
	    return $ids;
	    
    }
}

if ( !function_exists('etheme_product_variation_rating') ) {
	function etheme_product_variation_rating($value, $product) {
		$parent_product = wc_get_product( $product->get_parent_id() );
		
		if ( ! $parent_product ) {
			return $value;
		}
		
		return $parent_product->get_average_rating();
	}
}

if ( !function_exists('etheme_woocommerce_layered_nav_count_filter')) {
    function etheme_woocommerce_layered_nav_count_filter ( $html, $count_old, $term ) {
        $parents = wc_get_term_product_ids( $term->term_id, $term->taxonomy );
        $variable_products_no_parent = etheme_get_option( 'variation_product_parent_hidden', true );
        $count = $count_old;
        if ( count( $parents ) ) {
            foreach ( $parents as $parent ) {
                $product = wc_get_product( $parent );
	            if ( !$product )
		            continue;
                if ( ! $product->is_visible() || $product->get_type() != 'variable') {
                    continue;
                }
                $variations = $product->get_available_variations();
                if ( count( $variations ) ) {
                    if ( $variable_products_no_parent && $count > 0 ) {
                        $count --;
                    }
                    foreach ( $variations as $variation ) {
                        foreach ( $variation['attributes'] as $att_key => $att_value ) {
                            if ( $att_key == 'attribute_' . $term->taxonomy && $att_value == $term->slug ) {
                                $count ++;
                            }
                        }
                    }
                }
            }
        }
        return str_replace( $count_old, $count, $html );
    }
}

if ( !function_exists('etheme_brands_widget_count_filter')) {
	function etheme_brands_widget_count_filter ( $html, $count_old, $term ) {
		$parents = wc_get_term_product_ids( $term->term_id, $term->taxonomy );
		$variable_products_no_parent = etheme_get_option( 'variation_product_parent_hidden', true );
		$count = $count_old;
		if ( count( $parents ) ) {
			foreach ( $parents as $parent ) {
				$product = wc_get_product( $parent );
				if ( !$product )
					continue;
				if ( ! $product->is_visible() || $product->get_type() != 'variable') {
					continue;
				}
				$variations = $product->get_children();
				if ( count($variations)) {
					$count += count( $variations );
					if ( $variable_products_no_parent && $count > 0 ) {
						$count --;
					}
				}
			}
		}
		
		return str_replace( $count_old, $count, $html );
	}
}

if ( !function_exists('etheme_get_terms_filter')) {
    function etheme_get_terms_filter($terms, $taxonomies) {
	    if ( is_admin() || defined( 'DOING_AJAX' ) ) {
		    return $terms;
	    }
	
	    if ( ! isset( $taxonomies[0] ) ||
	         ! in_array( $taxonomies[0], apply_filters( 'woocommerce_change_term_counts', array(
		         'product_cat',
		         'product_tag',
	         ) ), true ) ) {
		    return $terms;
	    }
	
	    $variation_term_counts = array();
	
	    $term_counts = get_transient( 'wc_term_counts' );
	
	    foreach ( $terms as &$term ) {
		    if ( ! is_object( $term ) ) {
			    continue;
		    }
		
		    if ( ! isset( $term_counts[ $term->term_id ] ) ) {
			    continue;
		    }
		
		    $variation_term_counts[ $term->term_id ] = count( etheme_product_variations_excluded(
			    array(
				    'taxonomy' => $term->taxonomy,
				    'field'    => 'id',
				    'terms'    => $term->term_id,
			    )
		    ) );
		
		    $child_term_count = isset( $variation_term_counts[ $term->term_id ] ) ? (int) $variation_term_counts[ $term->term_id ] : 0;
		    $term_count       = (int) $term_counts[ $term->term_id ];
		
		    $term_counts[ $term->term_id ] = $term_count + $child_term_count;
		
		    if ( empty( $term_counts[ $term->term_id ] ) ) {
			    continue;
		    }
		
		    $term->count = absint( $term_counts[ $term->term_id ] );
	    }
	
	    return $terms;
    }
}

// optimization

// tweak to include script only when widget is used
add_filter('woocommerce_product_categories_widget_args', function($list_args) {
    if ( etheme_get_option( 'cats_accordion', 1 ) ) {
	    wp_enqueue_script( 'product_categories_widget' );
    }
	return $list_args;
});

add_filter('widget_nav_menu_args', function($nav_menu_args){
	wp_enqueue_script( 'nav_menu_widget' );
    return $nav_menu_args;
});

add_filter('woocommerce_widget_cart_is_hidden', function ($value) {
    if ( !$value ) {
	    etheme_enqueue_style( 'cart-widget' );
    }
    return $value;
});
add_filter( 'woocommerce_product_categories_widget_args', function ( $args ) {
    if ( ! is_tax( 'product_cat' ) || !get_theme_mod('widget_product_categories_advanced_mode', 0) ) {
        return $args;
    }

    // Setup Current Category
    $current_cat_parent = $args['current_category'];
    
    $current_cat_parent_new = get_term_by( 'id', (int) $args['current_category'], 'product_cat' )->parent;
    if ( $current_cat_parent_new > 0 ) {
        $current_cat_parent = $current_cat_parent_new;
    }

    $shop_link        = wc_get_page_id( 'shop' ) > 0 ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );
    $top_level        = wp_list_categories( array(
        'title_li'                   => sprintf( '<a href="%1s">%2$s</a>', $shop_link, esc_html__( 'Show All Categories', 'xstore' ) ),
        'taxonomy'                   => 'product_cat',
        'parent'                     => 0,
        'hierarchical'               => $args['hierarchical'],
        'hide_empty'                 => $args['hide_empty'],
        //'exclude'                    => $current_cat_parent, // removed by bug of wp that shows no categories
        'show_count'                 => $args['show_count'],
        'echo'                       => false,
        'walker'                     => $args['walker'],
        // new WC_Product_Cat_List_Walker(), // $args['walker'],
        'current_category_ancestors' => array(),
        //$args['current_category_ancestors'],
        'current_category'           => $current_cat_parent,
        'use_desc_for_title'         => 0,
    ) );
    $top_level        = str_replace( '<ul>', '<ul class="children">', $top_level );
    // fix for wp bug no categories found by exclude param above
    $top_level = str_replace(array('current-cat', 'current-cat-parent'), array('hidden', 'hidden'), $top_level);
    $args['title_li'] = $top_level;

    // old version
//    $siblings        = get_terms(
//        'product_cat',
//        array(
//            'fields'       => 'ids',
//            'parent'       => $current_cat_parent,
//            'hierarchical' => true,
//            'hide_empty'   => false,
//        )
//    );
//    $siblings2       = get_terms(
//        'product_cat',
//        array(
//            'fields'       => 'ids',
//            'hierarchical' => true,
//            'hide_empty'   => false,
//        )
//    );
//    $args['exclude'] = array_diff( $siblings2, $siblings, array( $current_cat_parent ) );

    // new version
    global $wp_query;
    // Direct children are wanted
    $direct_children = get_terms(
        'product_cat',
        array(
            'fields'       => 'ids',
            'parent'       => $wp_query->queried_object->term_id,
            'hierarchical' => true,
            'hide_empty'   => false
        )
    );
    $siblings = array();
    if( $current_cat_parent ) {
        // Siblings are wanted
        $siblings = get_terms(
            'product_cat',
            array(
                'fields'       => 'ids',
                'parent'       => $current_cat_parent,
                'hierarchical' => true,
                'hide_empty'   => false
            )
        );
    }
				
    $include = array_merge( array( $wp_query->queried_object->term_id, $current_cat_parent ), $direct_children, $siblings );

    $args['include']     = implode( ',', $include );
//    add_filter('wp_list_categories', function ($output) {
//        return $output;
//    });
    return $args;
}, 9999 );

// fix compare
add_action('yith_woocompare_popup_head', function (){
	$filenames = array(
		'parent-style',
		'woocommerce',
		'woocommerce-archive',
		'yith-compare',
	);
	$config = etheme_config_css_files();
	$is_rtl = is_rtl();
	$theme = wp_get_theme();
	foreach ( $filenames as $filename ) {
		if ( !isset($config[$filename])) return;
		if ( $is_rtl ) {
			$rtl_file = get_template_directory() . esc_attr( $config[$filename]['file']) . '-rtl'.ETHEME_MIN_CSS.'.css';
			if (file_exists($rtl_file)) {
				$config[$filename]['file'] .= '-rtl';
			}
		} ?>
        <link rel="stylesheet" id="<?php echo 'etheme-'.esc_attr( $config[$filename]['name'] ); ?>-css" href="<?php echo get_template_directory_uri() . esc_attr( $config[$filename]['file'] ) . ETHEME_MIN_CSS; ?>.css?ver=<?php echo esc_attr( $theme->version ); ?>" type="text/css" media="all" /> <?php // phpcs:ignore ?>
		<?php
	}
	?>
    <link rel="stylesheet" id="xstore-icons-css" href="<?php get_template_directory_uri() . '/css/xstore-icons.css'; ?>" type="text/css" media="all" /> <?php // phpcs:ignore ?>
    <style>
        @font-face {
            font-family: 'xstore-icons';
            src:
                    url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.ttf') format('truetype'),
                    url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.woff2') format('woff2'),
                    url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.woff') format('woff'),
                    url('<?php echo get_template_directory_uri(); ?>/fonts/xstore-icons-light.svg#xstore-icons') format('svg');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
    </style>
    <?php
});

add_filter('woocommerce_before_widget_product_list', function ($html){
	etheme_enqueue_style( 'cart-widget' );
	return $html;
});

add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );

// enqueue styles accordion to options
add_filter('woocommerce_product_loop_start', 'etheme_woocommerce_product_loop_start_filter');

function etheme_woocommerce_product_loop_start_filter($html) {
	global $woocommerce_loop;
	
	if ( !$woocommerce_loop['doing_ajax'] ) {
		$product_view = etheme_get_option('product_view', 'disable');
		if( !empty($woocommerce_loop['product_view'])) {
			$product_view = $woocommerce_loop['product_view'];
		}
		etheme_enqueue_style( 'woocommerce-archive', true );
		if ( $product_view && ! in_array( $product_view, array( 'disable', 'custom' ) ) ) {
			etheme_enqueue_style( 'product-view-' . $product_view, true );
		}
		else {
			// enqueue styles if nothing set via loop
			$local_product_view = etheme_get_option('product_view', 'disable');
			if ( $local_product_view != 'disable' )
				etheme_enqueue_style( 'product-view-' . $local_product_view, true );
		}
		$product_img_hover = etheme_get_option('product_img_hover', 'slider');
		if( !empty($woocommerce_loop['hover'])) {
			$product_img_hover = $woocommerce_loop['hover'];
		}

		if ( $product_img_hover == 'carousel' ) {
		    wp_enqueue_script( 'et_product_hover_slider');
		}
		
//		$custom_template = get_query_var( 'et_custom_product_template' );
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
//			$custom_template = $woocommerce_loop['custom_template'];
			etheme_enqueue_style( 'content-product-custom', true );
		}
		
		if ( get_query_var( 'et_is-swatches', false ) ) {
			etheme_enqueue_style( "swatches-style", true );
		}
		
//		if ( get_query_var('et_is-quick-view', false) ) {
//			etheme_enqueue_style( "quick-view", true );
//			if ( get_query_var('et_is-quick-view-type', 'popup') == 'off_canvas' ) {
//				etheme_enqueue_style( "off-canvas", true );
//			}
//		}
	}
	
	return $html;
}

// Attention Attention!
// Do not move it inside of etheme_search_post_excerpt
// Because etheme_search_post_excerpt called minimum 7 times
// And it will add the same query several times
add_filter( 'woocommerce_price_filter_sql', function( $sql, $meta_query_sql, $tax_query_sql ){
	if ( isset( $_GET['filter_brand'] ) && ! empty($_GET['filter_brand']) ) {
		global $wpdb;
		$ids = et_get_active_brand_ids($_GET['filter_brand']);
		if ($ids){
			// change to str_contains for php 8
			// else if dose not work here
			if (strpos($sql, "product_id") !== false){
				$sql .= "AND product_id IN(SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $ids . "))";
				return $sql;
				
			}
			if (strpos($sql, "{$wpdb->posts}.ID") !== false) {
				$sql .= "AND {$wpdb->posts}.ID IN(SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $ids . "))";
				return $sql;
			}
		}
	}
	return $sql;
}, 10, 3 );

if (!function_exists('et_get_active_brand_ids')){
	function et_get_active_brand_ids($filter_brand){
		if (! $filter_brand ) return false;
		$brands = explode(',', $filter_brand);
		$ids    = array();
		
		foreach ($brands as $key => $value) {
			$term = get_term_by('slug', $value, 'brand');
			if ( ! isset( $term->term_taxonomy_id ) || empty( $term->term_taxonomy_id ) ) // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
			{
			} else {
				$ids[] = $term->term_taxonomy_id;
			}
		}
		
		if ( ! implode( ',', $ids ) ) {
			$ids = 0;
		} else {
			$ids = implode( ',', $ids );
		}
		
		return $ids;
	}
}

add_action( 'template_redirect', 'et_wc_fake_live_viewing', 20 );
if ( !function_exists('et_wc_fake_live_viewing') ) {
    function et_wc_fake_live_viewing()
    {
        if ( !get_option('xstore_sales_booster_settings_fake_live_viewing') ) return;

        if (!is_singular('product')) {
            return;
        }
        global $post;
        $id = $post->ID;
        etheme_set_fake_live_viewing_count($id);
    }
}


function etheme_set_fake_live_viewing_count($id) {

        $xstore_sales_booster_settings = (array)get_option('xstore_sales_booster_settings', array());

        $xstore_sales_booster_settings_default = array(
            'message' => esc_html__('{eye} {count} people are viewing this product right now', 'xstore'),
            'min_count' => 8,
            'max_count' => 49,
            'minutes' => 2
        );

        $xstore_sales_booster_settings_fake_live_viewing = $xstore_sales_booster_settings_default;

        if (count($xstore_sales_booster_settings) && isset($xstore_sales_booster_settings['fake_live_viewing'])) {
            $xstore_sales_booster_settings = wp_parse_args($xstore_sales_booster_settings['fake_live_viewing'],
                $xstore_sales_booster_settings_default);

            $xstore_sales_booster_settings_fake_live_viewing = $xstore_sales_booster_settings;
        }

        if ($xstore_sales_booster_settings_fake_live_viewing['min_count'] >= $xstore_sales_booster_settings_fake_live_viewing['max_count']) {
            $xstore_sales_booster_settings_fake_live_viewing['min_count']--;
        }
    if ($average_count = get_transient( 'etheme_fake_live_viewing_' . $id ) ) {}
    else {
        $average_count = rand($xstore_sales_booster_settings_fake_live_viewing['min_count'], $xstore_sales_booster_settings_fake_live_viewing['max_count']);

        set_transient('etheme_fake_live_viewing_' . $id, $average_count, (int)$xstore_sales_booster_settings_fake_live_viewing['minutes'] * MINUTE_IN_SECONDS); // keep for 5 minutes
        $saved_ids = (array)get_transient('etheme_fake_live_viewing_ids', array());
        $saved_ids[] = $id;
        $saved_ids = array_unique($saved_ids);
        set_transient('etheme_fake_live_viewing_ids', $saved_ids);
    }
    return str_replace(array('{eye}', '{count}'), array('<i class="et-icon et-view"></i>', $average_count), $xstore_sales_booster_settings_fake_live_viewing['message']);;

}

if ( !function_exists('etheme_get_fake_product_sales_count') ) {
    function etheme_get_fake_product_sales_count($id) {
        
        $xstore_sales_booster_settings = (array)get_option('xstore_sales_booster_settings', array());

        $xstore_sales_booster_settings_default = array(
            'message' => esc_html__('{fire} {count} items sold in last {timeframe}', 'xstore'),
            'sales_type' => 'fake',
            'show_on_shop' => false,
            'hide_on_outofstock' => false,
            'min_count' => 3,
            'max_count' => 12,
            'shown_after' => 0,
            'timeframe' => 3,
            'timeframe_period' => 'hours',
            'transient_hours' => 24
        );
        
        $timeframe_period = $xstore_sales_booster_settings_default['timeframe'] . ' ' . esc_html__('hours', 'xstore');

        $xstore_sales_booster_settings_fake_product_sales = $xstore_sales_booster_settings_default;

        if (count($xstore_sales_booster_settings) && isset($xstore_sales_booster_settings['fake_product_sales'])) {
            $xstore_sales_booster_settings = wp_parse_args($xstore_sales_booster_settings['fake_product_sales'],
                $xstore_sales_booster_settings_default);

            $xstore_sales_booster_settings_fake_product_sales = $xstore_sales_booster_settings;
        }
        
        if ( $xstore_sales_booster_settings_fake_product_sales['hide_on_outofstock'] ) {
            $this_product = wc_get_product( $id );
            if ( !$this_product->is_in_stock() ) return false;
        }
        
        $average_count = get_transient( 'etheme_fake_product_sales_' . $id );

        $xstore_sales_booster_settings_fake_product_sales_timeframe = $xstore_sales_booster_settings_fake_product_sales['timeframe'];

        switch ($xstore_sales_booster_settings_fake_product_sales['timeframe_period']) {
            case 'minutes':
                $timeframe_period = ($xstore_sales_booster_settings_fake_product_sales_timeframe > 1 ? $xstore_sales_booster_settings_fake_product_sales_timeframe . ' ' : '')  . _n( 'minute', 'minutes', (int)$xstore_sales_booster_settings_fake_product_sales_timeframe, 'xstore' );
                break;
            case 'hours':
                $timeframe_period = ($xstore_sales_booster_settings_fake_product_sales_timeframe > 1 ? $xstore_sales_booster_settings_fake_product_sales_timeframe . ' ' : '')  . _n( 'hour', 'hours', (int)$xstore_sales_booster_settings_fake_product_sales_timeframe, 'xstore' );
                break;
            case 'days':
                $timeframe_period = ($xstore_sales_booster_settings_fake_product_sales_timeframe > 1 ? $xstore_sales_booster_settings_fake_product_sales_timeframe . ' ' : '')  . _n( 'day', 'days', (int)$xstore_sales_booster_settings_fake_product_sales_timeframe, 'xstore' );
                break;
            case 'weeks':
                $timeframe_period = ($xstore_sales_booster_settings_fake_product_sales_timeframe > 1 ? $xstore_sales_booster_settings_fake_product_sales_timeframe . ' ' : '')  . _n( 'week', 'weeks', (int)$xstore_sales_booster_settings_fake_product_sales_timeframe, 'xstore' );
                break;
            case 'months':
                $timeframe_period = ($xstore_sales_booster_settings_fake_product_sales_timeframe > 1 ? $xstore_sales_booster_settings_fake_product_sales_timeframe . ' ' : '')  . _n( 'month', 'months', (int)$xstore_sales_booster_settings_fake_product_sales_timeframe, 'xstore' );
                break;
        }
        
        if ( $average_count === false ) {
            if (!!$average_count) {}
            else {
                
                if ( $xstore_sales_booster_settings_fake_product_sales['sales_type'] == 'fake' ) {
                    $average_count = rand($xstore_sales_booster_settings_fake_product_sales['min_count'], $xstore_sales_booster_settings_fake_product_sales['max_count']);
                }
                else {
                    $date_before = strtotime('-' . $xstore_sales_booster_settings_fake_product_sales_timeframe *
                                str_replace(array(
                                    'minutes',
                                    'hours',
                                    'days',
                                    'weeks',
                                    'months',
                                ), array(
                                    MINUTE_IN_SECONDS,
                                    HOUR_IN_SECONDS,
                                    DAY_IN_SECONDS,
                                    WEEK_IN_SECONDS,
                                    MONTH_IN_SECONDS
                                ), $xstore_sales_booster_settings_fake_product_sales['timeframe_period'] ) . ' seconds');
        
                  $orders = get_posts( array(
                        'numberposts' => -1,
                        'post_type'   => array( 'shop_order' ),
                        'post_status' => array( 'wc-completed', 'wc-processing' ),
                        'date_query'  => array(
                            'after'   => date('Y-m-d H:i:s', $date_before),
                            'before'  => date('Y-m-d H:i:s', strtotime( 'now' )),
                        )
                    ));
        
                    $average_count = 0;
                    foreach ( $orders as $order_id ) {
                        $order = wc_get_order( $order_id );
                        foreach ( $order->get_items() as $item_id => $item_values ) {
                            $quantity = 0;
                            $product_id = $item_values->get_product_id();
                            if ( $product_id == $id )
                                {$quantity = $item_values->get_quantity();}
                            $average_count += $quantity;
                            
                        }
                    }
                
                }
        
                set_transient('etheme_fake_product_sales_' . $id, $average_count, (int)$xstore_sales_booster_settings_fake_product_sales['transient_hours'] * HOUR_IN_SECONDS); // keep for 5 minutes
                $saved_ids = (array)get_transient('etheme_fake_product_sales_ids', array());
                $saved_ids[] = $id;
                $saved_ids = array_unique($saved_ids);
                set_transient('etheme_fake_product_sales_ids', $saved_ids);
            }
        }
        
        if ( $xstore_sales_booster_settings_fake_product_sales['sales_type'] == 'orders' && $xstore_sales_booster_settings_fake_product_sales['shown_after'] > $average_count) {
            return false;
        }
        
        return $average_count ? str_replace(
                array('{fire}', '{bag}', '{count}', '{timeframe}'),
                array('🔥', '🛍️', $average_count, $timeframe_period),
                $xstore_sales_booster_settings_fake_product_sales['message']) : false;
    }
}

if ( !function_exists('etheme_advanced_stock_status_html') ) {
    function etheme_advanced_stock_status_html($html, $product) {
        if ( ! etheme_get_option( 'show_single_stock', 0 ) ) {
            return '';
        }
        if ( in_array($product->get_type(), array('simple', 'product_variation') ) && 'yes' === get_option( 'woocommerce_manage_stock' ) ){
            return et_product_stock_line($product, $html);
        }
        return $html;
    }
}

// Adding brands to woocommerce product application/ld+json schema
add_filter('woocommerce_structured_data_product', function ($markup, $product) {
	if ( etheme_xstore_plugin_notice() || !get_theme_mod( 'enable_brands', 1 ) ) {
		return $markup;
	}
	if ( isset($markup['brand'])) return $markup;
	$terms = wp_get_post_terms( $product->get_ID(), 'brand' );
	if ( count( $terms ) ) {
		if ( count( $terms ) > 1) {
			foreach ( $terms as $brand ) {
				$thumbnail_id = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );
				$description = get_term_meta($brand->term_id, 'description', true);
			    $parsed_brand = array(
				    '@type' => 'Brand',
                    'name'  => $brand->name
				);
			    if ( $thumbnail_id )
				    $parsed_brand['logo'] = wp_get_attachment_image_url( $thumbnail_id, 'full' );
			    if ( !empty(trim($description)) )
			        $parsed_brand['slogan'] = $description;
				$markup['brand'][] = $parsed_brand;
			}
		}
		else {
			$thumbnail_id = absint( get_term_meta( $terms[0]->term_id, 'thumbnail_id', true ) );
			$description = get_term_meta($terms[0]->term_id, 'description', true);
			$parsed_brand = array(
				'@type' => 'Brand',
				'name'  => $terms[0]->name
			);
			if ( $thumbnail_id )
				$parsed_brand['logo'] = wp_get_attachment_image_url( $thumbnail_id, 'full' );
			if ( !empty(trim($description)) )
				$parsed_brand['slogan'] = $description;
			$markup['brand'][] = $parsed_brand;
		}
	}
	return $markup;
}, 10, 2);

// Compatibility with WooCommerce Skroutz & BestPrice XML Feed
// Adds variation gallery images for xml file on creation
add_filter('webexpert_skroutz_xml_custom_gallery','etheme_compatibility_webexpert_skroutz_xml_variation_gallery_images',20,2);

/**
 * Returns variation gallery images ids.
 *
 * @param $gallery_image_ids
 * @param $product
 * @return array
 *
 * @since 8.1.5
 *
 */
function etheme_compatibility_webexpert_skroutz_xml_variation_gallery_images($gallery_image_ids,$product) {
	if ( $product->is_type( 'variation' ) ) {
		$has_variation_gallery_images = get_post_meta( $product->get_id(), 'et_variation_gallery_images', true );
		if ( (bool)$has_variation_gallery_images ) {
			$gallery_images = (array) $has_variation_gallery_images;
			return $gallery_images;
		}

		// Compatibility with WooCommerce Additional Variation Images plugin
        $has_variation_gallery_images_wc_additional_variation_images = get_post_meta( $product->get_id(), '_wc_additional_variation_images', true );
		if ( (bool)$has_variation_gallery_images_wc_additional_variation_images ) {
		    $gallery_images = (array) array_filter( explode( ',', $has_variation_gallery_images_wc_additional_variation_images));
			return $gallery_images;
		}
		return $gallery_image_ids;
	}
	return $gallery_image_ids;
}

add_filter('woocommerce_get_breadcrumb',function($crumbs,$_this){
    if ( etheme_get_option('bc_page_numbers', 0) ){
        if(strpos($crumbs[count($crumbs)-1][0],'Page ')===0){
            unset($crumbs[count($crumbs)-1]);
            $args["breadcrumb"][count($crumbs)-1][1]='';
        }
    }
	return $crumbs;
},10,2);

// new
if ( class_exists('Etheme_Sales_Booster_Estimated_Delivery') ) {
    $estimated_delivery = new Etheme_Sales_Booster_Estimated_Delivery();
    $estimated_delivery->init();
}

if ( class_exists('Etheme_Sales_Booster_Safe_Checkout') ) {
    $safe_checkout = Etheme_Sales_Booster_Safe_Checkout::get_instance();
    $safe_checkout->init();
}