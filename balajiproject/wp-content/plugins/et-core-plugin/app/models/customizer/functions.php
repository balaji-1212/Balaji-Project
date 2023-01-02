<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Customizer builder functions
 *
 * @since   1.4.0
 * @version 1.0.0
 */

/**
 * Return header top html.
 *
 * @return  {html} html of header-top part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_top() {
	require_once( ET_CORE_DIR . 'app/models/customizer/templates/header/header-top.php' );
}

/**
 * Return header main html.
 *
 * @return  {html} html of header-main part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_main() {
	require_once( ET_CORE_DIR . 'app/models/customizer/templates/header/header-main.php' );
}

/**
 * Return header bottom html.
 *
 * @return  {html} html of header-bottom part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_bottom() {
	require_once( ET_CORE_DIR . 'app/models/customizer/templates/header/header-bottom.php' );
}

/**
 * Return mobile header top html.
 *
 * @return  {html} html of mobile-header-top part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_mobile_header_top() {
	require_once( ET_CORE_DIR . 'app/models/customizer/templates/header/mobile/mobile-top.php' );
}

/**
 * Return mobile header main html.
 *
 * @return  {html} html of mobile-header-main part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_mobile_header_main() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/mobile/mobile-main.php' );
}

/**
 * Return mobile header bottom html.
 *
 * @return  {html} html of mobile-header-bottom part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_mobile_header_bottom() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/mobile/mobile-bottom.php' );
}

/**
 * Return header account element html.
 *
 * @return  {html} html of account part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_account() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/account.php' );
}

/**
 * Return header button element html.
 *
 * @return  {html} html of button part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_button() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/button.php' );
}

/**
 * Return header promo-text element html.
 *
 * @return  {html} html of promo_text part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_promo_text() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/promo_text.php' );
}

/**
 * Return header cart element html.
 *
 * @return  {html} html of cart part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_cart() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/cart.php' );
}

/**
 * Return header main menu element html.
 *
 * @return  {html} html of menu part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_menu() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/menu.php' );
}

/**
 * Return header newsletter element html.
 *
 * @return  {html} html of newsletter part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_newsletter() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/newsletter.php' );
}

/**
 * Return header search element html.
 *
 * @return  {html} html of search part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_search() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/search.php' );
}

/**
 * Return header socials element html.
 *
 * @return  {html} html of socials part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_socials() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/socials.php' );
}

/**
 * Return header wishlist element html.
 *
 * @return  {html} html of wishlist part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_wishlist() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/wishlist.php' );
}

/**
 * Return header compare element html.
 *
 * @return  {html} html of compare part
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_parts_compare() {
	require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/compare.php' );
}

/**
 * Return mobile panel html.
 *
 * @return  {html} html of mobile panel
 * @version 1.0.0
 * @since   2.3.1
 */
function etheme_mobile_panel() {
	if ( get_query_var( 'et_mobile-optimization', false ) && ! get_query_var( 'is_mobile', false ) ) {
		return;
	}
	require( ET_CORE_DIR . 'app/models/customizer/templates/mobile-panel/mobile-panel.php' );
}

/**
 * Return header wrapper start html.
 *
 * @return  {html} html of header wrapper start
 * @version 1.0.2
 *          last changes in 1.5.5
 * @since   1.4.0
 */
function etheme_header_wrapper_start() {
	global $et_builder_globals;
	$et_builder_globals['in_mobile_menu']       = false;
	$et_builder_globals['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
	$class                                      = $sticky_attr = '';
	$is_mobile                                  = get_query_var( 'is_mobile', false );
	$sticky_header_mob                          = false;
	$sticky_header_dt                           = false;
	if ( $is_mobile ) {
		// mobile sticky header
		$sticky_header_mob = get_theme_mod( 'top_header_sticky_et-mobile', '0' )
		                     || get_theme_mod( 'main_header_sticky_et-mobile', '1' )
		                     || get_theme_mod( 'bottom_header_sticky_et-mobile', '0' );
	} else {
		$sticky_header_dt = get_theme_mod( 'top_header_sticky_et-desktop', '0' )
		                    || get_theme_mod( 'main_header_sticky_et-desktop', '1' )
		                    || get_theme_mod( 'bottom_header_sticky_et-desktop', '0' );
	}
	$sticky_header = ( $sticky_header_mob || $sticky_header_dt ) ? 'sticky' : '';
	$class         .= $sticky_header;
	if ( $sticky_header != '' ) {
		$sticky_type = ( get_theme_mod( 'header_sticky_type_et-desktop', 'sticky' ) );
		$sticky_attr .= ' data-type="' . $sticky_type . '"';
		if ( $sticky_type == 'custom' ) {
			$sticky_attr .= ' data-start= "' . get_theme_mod( 'headers_sticky_start_et-desktop', 80 ) . '"';
		}
		wp_enqueue_script( 'fixed-header' );
	}
	echo '<header id="header" class="site-header ' . $class . '" ' . $sticky_attr . '>';
}

/**
 * Return header wrapper end html.
 *
 * @return  {html} html of header wrapper end
 * @version 1.0.1
 *          last changes in 1.5.4
 * @since   1.4.0
 */
function etheme_header_wrapper_end() {
	global $et_builder_globals;
	$et_builder_globals['in_mobile_menu'] = false;
	echo '</header>';
}

/**
 * Return header desktop wrapper start html.
 *
 * @return  {html} html of header desktop wrapper start
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_dt_wrapper_start() {
	echo '<div class="header-wrapper">';
}

/**
 * Return header desktop wrapper end html.
 *
 * @return  {html} html of header desktop wrapper end
 * @version 1.0.0
 * @since   1.4.0
 */
function etheme_header_dt_wrapper_end() {
	echo '</div>';
}

/**
 * Return header mobile wrapper start html with filters for mobile header content.
 *
 * @return  {html} html of header mobile wrapper start
 * @version 1.0.2
 * @since   1.4.0
 */
function etheme_header_mob_wrapper_start() {
	echo '<div class="mobile-header-wrapper">';
}

/**
 * Return header mobile wrapper start html with removed filters for mobile header content.
 *
 * @return  {html} html of header mobile wrapper end
 * @version 1.0.2
 * @since   1.4.0
 */
function etheme_header_mob_wrapper_end() {
	echo '</div>';
}

/**
 * Return header vertical html.
 *
 * @return  {html} html of header-vertical part
 * @version 1.0.0
 * @since   1.4.2
 */
function etheme_vertical_header() {
	if ( ( ! get_query_var( 'is_mobile', false ) || apply_filters( 'etheme_force_enqueue_header_vertical_on_mobile', false ) ) && get_theme_mod( 'header_vertical_et-desktop', '0' ) ) {
		require( ET_CORE_DIR . 'app/models/customizer/templates/header/header-vertical.php' );
	}
}


/**
 * Actions of header parts.
 *
 * @since   1.4.0
 * @version 1.0.0
 */
add_action( 'init', function () {
	if ( apply_filters( 'xstore_theme_amp', false ) ) {
		return;
	}
	$header_actions = array(
		array(
			'action'   => 'etheme_header',
			'function' => 'etheme_vertical_header',
			'priority' => 1
		),
		array(
			'action'   => 'etheme_header_start',
			'function' => 'etheme_header_wrapper_start',
			'priority' => 4,
		),
		array(
			'action'   => 'etheme_header',
			'function' => 'etheme_header_dt_wrapper_start',
			'priority' => 5,
		),
		array(
			'action'   => 'etheme_header',
			'function' => 'etheme_header_top',
			'priority' => 10
		),
		array(
			'action'   => 'etheme_header',
			'function' => 'etheme_header_main',
			'priority' => 20
		),
		array(
			'action'   => 'etheme_header',
			'function' => 'etheme_header_bottom',
			'priority' => 30
		),
		array(
			'action'   => 'etheme_header',
			'function' => 'etheme_header_dt_wrapper_end',
			'priority' => 35,
		),
		// mobile header
		array(
			'action'   => 'etheme_header_mobile',
			'function' => 'etheme_header_mob_wrapper_start',
			'priority' => 5,
		),
		array(
			'action'   => 'etheme_header_mobile',
			'function' => 'etheme_mobile_header_top',
			'priority' => 10
		),
		array(
			'action'   => 'etheme_header_mobile',
			'function' => 'etheme_mobile_header_main',
			'priority' => 20
		),
		array(
			'action'   => 'etheme_header_mobile',
			'function' => 'etheme_mobile_header_bottom',
			'priority' => 30
		),
		array(
			'action'   => 'etheme_header_mobile',
			'function' => 'etheme_header_mob_wrapper_end',
			'priority' => 32,
		),
		array(
			'action'   => 'etheme_header_end',
			'function' => 'etheme_header_wrapper_end',
			'priority' => 35,
		),
	);
	
	if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
		WPBMap::addAllMappedShortcodes();
	}
	
	// if ( class_exists( 'Kirki' ) ) {
	
	switch ( get_theme_mod( 'header_banner_position', 'disable' ) ) {
		
		case 'top':
			$header_actions[] = array(
				'action'   => 'etheme_header',
				'function' => 'etheme_header_banner',
				'priority' => 2
			);
			break;
		case 'bottom':
			$header_actions[] = array(
				'action'   => 'etheme_header_mobile',
				'function' => 'etheme_header_banner',
				'priority' => 40
			);
			break;
		
		default:
			break;
	}
	
	foreach ( $header_actions as $key ) {
		add_action( $key['action'], $key['function'], $key['priority'] );
	}
	
	// }
	
	unset( $header_actions );
} );

if ( ! function_exists( 'etheme_header_banner' ) ) {
	function etheme_header_banner() {
		if ( ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'header-banner' ) ) ):
		endif;
	}
}

/**
 * Cart fragment
 * @since   1.4.0
 * @version 1.0.0
 * @see     etheme_cart_link_fragment()
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'etheme_cart_link_fragment' );

if ( ! function_exists( 'etheme_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 *
	 * @param   {array} $cart_fragments Fragments to refresh via AJAX.
	 *
	 * @return  {array} fragments to refresh via AJAX
	 * @since   1.4.0
	 * @version 1.0.1
	 */
	function etheme_cart_link_fragment( $cart_fragments ) {
		global $woocommerce;
		
		ob_start();
		etheme_cart_total();
		$cart_fragments['span.et-cart-total-inner'] = ob_get_clean();
		
		ob_start();
		etheme_cart_quantity();
		$cart_fragments['span.et-cart-quantity'] = ob_get_clean();
		
		ob_start();
		etheme_woocomerce_mini_cart_footer();
		$cart_fragments['div.product_list-popup-footer-inner'] = ob_get_clean();
		
		return $cart_fragments;
	}
}

if ( ! function_exists( 'etheme_cart_total' ) ) {
	/**
	 * Cart total
	 *
	 * @return  {html} cart total
	 * @version 1.0.0
	 * @since   1.4.0
	 */
	function etheme_cart_total() {
		if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) || ! method_exists( WC()->cart, 'get_cart_subtotal' ) ) {
			return;
		} ?>
        <span class="et-cart-total-inner">
              <?php echo wp_specialchars_decode( WC()->cart->get_cart_subtotal() ); ?>
            </span>
		<?php
	}
}

if ( ! function_exists( 'etheme_cart_quantity' ) ) {
	/**
	 * Cart total
	 *
	 * @return  {html} cart quantity
	 * @version 1.0.0
	 * @since   1.4.0
	 */
	function etheme_cart_quantity() {
		
		if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) || ! method_exists( WC()->cart, 'get_cart_contents_count' ) ) {
			return;
		}
		
		$count = get_query_var('et_woo_products_count', false);
		if ( !$count ) {
			$count = WC()->cart->get_cart_contents_count();
			set_query_var('et_woo_products_count', $count);
		} ?>
        <span class="et-cart-quantity et-quantity count-<?php echo $count; ?>">
              <?php echo wp_specialchars_decode( $count ); ?>
            </span>
		<?php
	}
}

if ( ! function_exists( 'etheme_woocomerce_mini_cart_footer' ) ) {
	/**
	 * Cart footer
	 *
	 * @return  {html} cart footer
	 * @version 1.0.0
	 * @since   2.3.1
	 */
	function etheme_woocomerce_mini_cart_footer() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		if( !WC()->cart ){
			return;
		}
		
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
		
		if ( function_exists( 'etheme_get_option' ) && etheme_get_option( 'cart_content_view_cart_button_et-desktop', false ) ) {
			add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
		}
		
		$count = get_query_var('et_woo_products_count', false);
		if ( !$count ) {
			$count = WC()->cart->get_cart_contents_count();
			set_query_var('et_woo_products_count', $count);
		}
		
		$amount = '';
		if ( ! wc_tax_enabled() ) {
			$amount = WC()->cart->cart_contents_total;
		} else {
			$amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;
		}
		
		// if ( ! WC()->cart->is_empty() ) : ?>

        <div class="product_list-popup-footer-inner" <?php echo ( $count > 0 ) ? '' : ' style="display: none;"'; ?>>

            <div class="cart-popup-footer">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="btn-view-cart wc-forward"><?php esc_html_e( 'Shopping cart ', 'xstore-core' ); ?>
                    (<?php echo $count; ?>)</a>
                <div class="cart-widget-subtotal woocommerce-mini-cart__total total"
                     data-amount="<?php echo $amount; ?>">
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
				<?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?>
            </p>
			
			<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

        </div>
		
		<?php // endif;
	}
}

// mobile panel
add_action( 'init', function () {
	if ( apply_filters( 'xstore_theme_amp', false ) ) {
		return;
	}
//	$specific_key_amp = md5(get_site_url( get_current_blog_id(), '/' ));
//	if ( !empty($_SESSION['xstore-amp-'.$specific_key_amp]) && $_SESSION['xstore-amp-'.$specific_key_amp] ) {
//		return false;
//	}
	add_action( 'after_page_wrapper', 'etheme_mobile_panel', 1 );
} );

if ( ! function_exists( 'etheme_wishlist_quantity' ) ) {
	/**
	 * Wishlist quantity
	 *
	 * @return  {html} html of wishlist quantity
	 * @version 1.0.0
	 * @since   1.4.0
	 */
	function etheme_wishlist_quantity() {
		
		if ( ! class_exists( 'YITH_WCWL' ) ) {
			return;
		}
		
		$count = get_query_var('et_yith_wcwl_products_count', false);
		
		if ( !$count ) {
			$args = array();
			if ( defined( 'YITH_WCWL_PREMIUM' ) && get_query_var( 'et_is-loggedin', false) ) {
				$args['wishlist_id'] = 'all';
			} else {
				$args['is_default'] = true;
			}
			
			$products = YITH_WCWL()->get_products( $args );
			
			$count = count( $products );
			
			set_query_var('et_yith_wcwl_products_count', $count);
		} ?>
        <span class="et-wishlist-quantity et-quantity count-<?php echo $count; ?>">
          <?php echo wp_specialchars_decode( $count ); ?>
        </span>
		<?php
	}
}

if ( ! function_exists( 'etheme_mini_wishlist_content' ) ) {
	/**
	 * Wishlist dropdown products list
	 *
	 * @return  {html} html content of mini-wishlist products
	 * @version 1.0.1
	 * @since   2.3.1
	 */
	function etheme_mini_wishlist_content() {
		
		if ( ! class_exists( 'YITH_WCWL' ) ) {
			return;
		}
		
		$args = array();
		if ( defined( 'YITH_WCWL_PREMIUM' ) && is_user_logged_in() ) {
			$args['wishlist_id'] = 'all';
		} else {
			$args['is_default'] = true;
		}
		
		
		$products = YITH_WCWL()->get_products( $args );
		
		$limit = get_theme_mod( 'mini-wishlist-items-count', 3 );
		$limit = is_numeric( $limit ) ? $limit : 3;
		
		if ( ! defined( 'YITH_WCWL_PREMIUM' ) && apply_filters( 'et_mini_cart_reverse', false ) ) {
			$products = array_reverse( $products );
		}
		
		$add_remove_ajax = false;
		$wishlist_class  = 'et_b_wishlist-dropdown product_list_widget cart_list';
		$wishlist_attr   = array();
		
		if ( class_exists( 'YITH_WCWL_Wishlist_Factory' ) ) {
			
			$wishlist = YITH_WCWL_Wishlist_Factory::get_current_wishlist();
			
			if ( is_object( $wishlist ) ) {
				
				$wishlist_attr[] = 'data-token="' . $wishlist->get_token() . '"';
				$wishlist_attr[] = 'data-id="' . $wishlist->get_id() . '"';
				
				$wishlist_class .= ' cart wishlist_table';
				
				$add_remove_ajax = true;
				
			}
			
		}
		
		?>
        <div class="<?php esc_attr_e( $wishlist_class ); ?>" <?php echo implode( ' ', $wishlist_attr ); ?>>
			<?php if ( ! empty( $products ) ) : ?>

                <?php $is_yith_wcbm_frontend = class_exists('YITH_WCBM_Frontend'); ?>

                <?php
                    if ($is_yith_wcbm_frontend) {
                        remove_filter( 'woocommerce_product_get_image', array( YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product' ), 999 );
                    }
                ?>

                <ul class="cart-widget-products">
					<?php
					$i = 0;
					$wishlist_url = YITH_WCWL()->get_wishlist_url();
					$trash_bin = defined( 'ETHEME_BASE_URI' ) ? ETHEME_BASE_URI . 'theme/assets/images/trash-bin.gif' : '';
                    $sku_enabled = in_array('mini-wishlist', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart'))) && wc_product_sku_enabled();
					foreach ( $products as $item ) {
						$i ++;
						if ( $i > $limit ) {
							break;
						}
						
						if ( function_exists( 'yit_wpml_object_id' ) ) {
							$item['prod_id'] = yit_wpml_object_id( $item['prod_id'], 'product', true );
						}
						
						if ( function_exists( 'wc_get_product' ) ) {
							$_product = wc_get_product( $item['prod_id'] );
						} else {
							$_product = get_product( $item['prod_id'] );
						}
						
						if ( ! $_product ) {
							continue;
						}
						
						$product_name = $_product->get_title();
						$thumbnail    = $_product->get_image();
						?>
                        <li class="">
							<?php if ( ! $_product->is_visible() ) : ?>
                                <a class="product-mini-image">
									<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '&nbsp;'; ?>
                                </a>
							<?php else : ?>
                                <a href="<?php echo esc_url( $_product->get_permalink() ); ?>"
                                   class="product-mini-image">
									<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '&nbsp;'; ?>
                                </a>
							<?php endif; ?>

                            <div class="product-item-right" data-row-id="<?php esc_attr_e( $item['prod_id'] ); ?>">

                                <h4 class="product-title"><a
                                            href="<?php echo esc_url( $_product->get_permalink() ); ?>"><?php echo wp_specialchars_decode( $product_name ); ?></a>
                                </h4>
								
								<?php if ( $add_remove_ajax ) : ?>
                                    <a href="<?php echo add_query_arg( 'remove_from_wishlist', $item['prod_id'], esc_url( $wishlist_url ) ); ?>"
                                       class="remove remove_from_wishlist"
                                       title="<?php echo esc_attr__( 'Remove this product', 'xstore-core' ); ?>"><i
                                                class="et-icon et-delete et-remove-type1"></i><i
                                                class="et-trash-wrap et-remove-type2"><img
                                                    src="<?php echo $trash_bin; ?>"
                                                    alt="<?php echo esc_attr__( 'Remove this product', 'xstore-core' ); ?>"></i></a>
								<?php endif; ?>

                                <div class="descr-box">
									<?php echo WC()->cart->get_product_price( $_product ); ?>

                                    <?php if ( $sku_enabled && $_product->get_sku() ) : ?>
                                    <div class="product_meta">
                                            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'xstore-core' ); ?>
                                                <span class="sku"><?php echo esc_html( ( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) ); ?></span>
                                            </span>
                                    </div>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </li>
						<?php
					}
					?>
                </ul>
				<?php
				if ($is_yith_wcbm_frontend) {
					add_filter( 'woocommerce_product_get_image', array( YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product' ), 999 );
				}
				?>
			<?php else : ?>
                <p class="empty"><?php esc_html_e( 'No products in the wishlist.', 'xstore-core' ); ?></p>
			<?php endif; ?>
        </div><!-- end product list -->
	<?php }
}

if ( ! function_exists( 'etheme_mini_wishlist' ) ) {
	/**
	 * Wishlist dropdown content
	 *
	 * @return  {html} html content of mini-wishlist
	 * @version 1.0.1
	 * @since   1.4.0
	 */
	function etheme_mini_wishlist() {
		
		if ( ! class_exists( 'YITH_WCWL' ) ) {
			return;
		} ?>
		
		<?php etheme_mini_wishlist_content(); ?>

        <div class="woocommerce-mini-cart__footer-wrapper">
            <div class="product_list-popup-footer-wrapper">
                <p class="buttons mini-cart-buttons">
                    <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>"
                       class="button btn-view-wishlist"><?php _e( 'View Wishlist', 'xstore-core' ); ?></a>
                </p>
            </div>
        </div>
	<?php }
}

/**
 * Wishlist notice on ajax
 * @since   2.2.4
 * @version 1.0.0
 * @see     etheme_wishlist_notice_ajax()
 */

add_action( 'wp_ajax_etheme_wishlist_notice_ajax', 'etheme_wishlist_notice_ajax' );
add_action( 'wp_ajax_nopriv_etheme_wishlist_notice_ajax', 'etheme_wishlist_notice_ajax' );

if ( ! function_exists( 'etheme_wishlist_notice_ajax' ) ) {
	/**
	 * Wishlist notice on ajax
	 * @since   2.2.4
	 * @version 1.0.0
	 */
	function etheme_wishlist_notice_ajax() {
		
		$notices = WC()->session->get( 'wc_notices', array() );
		if ( isset( $notices['success'] ) && count( $notices['success'] ) ) {
			array_pop( $notices['success'] );
		}
		WC()->session->set( 'wc_notices', $notices );
		
	}
}

/**
 * Wishlist fragment
 * @since   1.4.0
 * @version 1.0.0
 * @see     etheme_wishlist_link_fragment()
 */

add_action( 'wp_ajax_etheme_wishlist_link_fragment', 'etheme_wishlist_link_fragment' );
add_action( 'wp_ajax_nopriv_etheme_wishlist_link_fragment', 'etheme_wishlist_link_fragment' );

if ( ! function_exists( 'etheme_wishlist_link_fragment' ) ) {
	/**
	 * Wishlist Fragments
	 *
	 * @return  {array} fragments to refresh via AJAX
	 * @version 1.0.0
	 * @since   1.4.0
	 */
	function etheme_wishlist_link_fragment() {
		
		$data = array(
			'fragments' => array()
		);
		
		if ( ! function_exists( 'wc_setcookie' ) || ! function_exists( 'YITH_WCWL' ) ) {
			return;
		}
		
		ob_start();
		etheme_wishlist_quantity();
		$data['fragments']['span.et-wishlist-quantity'] = ob_get_clean();
		
		ob_start();
		etheme_mini_wishlist_content();
		$data['fragments']['.et_b_wishlist-dropdown'] = ob_get_clean();
		
		wp_send_json( $data );
	}
}

/**
 * Header account content part
 *
 * @param   {bool} echo and return content.
 *
 * @return  {html} account content html
 * @since   1.4.0
 * @version 1.0.2
 */
if ( ! function_exists( 'et_b_account_link' ) ) {
	function et_b_account_link( $echo = true, $off_canvas = false, $element_options = array() ) {
		$is_woocommerce   = ( class_exists( 'WooCommerce' ) ) ? true : false;
		$account_dropdown = '';
		$login_options    = array();
		
		$canvas = array( 'close' => '', 'label' => '' );
		if ( $off_canvas ) :
			ob_start(); ?>
            <span class="et-toggle pos-absolute et-close full-<?php echo $element_options['etheme_mini_account_content_position']; ?> top">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.8em" height="0.8em" viewBox="0 0 24 24">
                    <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                </svg>
            </span>
			<?php
			
			$canvas['close'] = ob_get_clean();
			ob_start(); ?>

            <div class="et-mini-content-head">
                <a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                   class="account-type2 flex justify-content-center flex-wrap">
					<?php if ( $element_options['account_icon_et-desktop'] == '' ) {
						$element_options['account_icon_et-desktop'] = $element_options['account_icons_et-desktop']['type1'];
					}
					?>
                    <span class="et_b-icon">
                            <?php echo $element_options['account_icon_et-desktop']; ?>
                        </span>

                    <span class="et-element-label pos-relative inline-block">
                            <?php echo esc_html__( 'My Account', 'xstore-core' ); ?>
                        </span>
                </a>
            </div>
			<?php $canvas['label'] = ob_get_clean();
		
		endif;
		
		if ( is_user_logged_in() ) {
			if ( $is_woocommerce ) {
				ob_start(); ?>
                <div class="et-mini-content">
					<?php echo $canvas['close']; ?>
                    <div class="et-content">
						<?php echo $canvas['label']; ?>
						<?php echo account_menu_callback(); ?>
                    </div>
                </div>
				<?php $account_dropdown = ob_get_clean();
			}
		} else {
			$account_dropdown = '';
			if ( $is_woocommerce ) {
				$with_tabs = get_query_var('et_account-registration', false);
				ob_start(); ?>
				<?php
				$login_options['form_tabs']       = $login_options['form_tabs_start'] = $login_options['form_tabs_end'] = '';
				$login_options['form_tabs_start'] = '<div class="et_b-tabs-wrapper">';
				$login_options['form_tabs_end']   = '</div>';
				ob_start(); ?>
                <div class="et_b-tabs">
                        <span class="et-tab active" data-tab="login">
                            <?php esc_html_e( 'Login', 'xstore-core' ); ?>
                        </span>
                    <span class="et-tab" data-tab="register">
                            <?php esc_html_e( 'Register', 'xstore-core' ); ?>
                        </span>
                </div>
				<?php
				$login_options['form_tabs'] = ob_get_clean();
				?>

                <div class="header-account-content et-mini-content">
					<?php echo $canvas['close']; ?>
                    <div class="et-content">
						<?php echo $canvas['label']; ?>
						<?php
						if ( $with_tabs ) {
							echo $login_options['form_tabs_start'];
							echo $login_options['form_tabs'];
						}
						?>
                        <form class="woocommerce-form woocommerce-form-login login <?php if ( $with_tabs ) {
							echo 'et_b-tab-content active';
						} ?>" data-tab-name="login" autocomplete="off" method="post"
                              action="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>">
							
							<?php do_action( 'woocommerce_login_form_start' ); ?>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="username"><?php esc_html_e( 'Username or email', 'xstore-core' ); ?>
                                    &nbsp;<span class="required">*</span></label>
                                <input type="text" title="username"
                                       class="woocommerce-Input woocommerce-Input--text input-text"
                                       name="username" id="username"
                                       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                            </p>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="password"><?php esc_html_e( 'Password', 'xstore-core' ); ?>&nbsp;<span
                                            class="required">*</span></label>
                                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                                       name="password" id="password" autocomplete="current-password"/>
                            </p>
							
							<?php do_action( 'woocommerce_login_form' ); ?>

                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
                               class="lost-password"><?php esc_html_e( 'Lost password ?', 'xstore-core' ); ?></a>

                            <p>
                                <label for="rememberme"
                                       class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox"
                                           name="rememberme" type="checkbox" id="rememberme" value="forever"/>
                                    <span><?php esc_html_e( 'Remember Me', 'xstore-core' ); ?></span>
                                </label>
                            </p>

                            <p class="login-submit">
								<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                                <button type="submit" class="woocommerce-Button button" name="login"
                                        value="<?php esc_attr_e( 'Log in', 'xstore-core' ); ?>"><?php esc_html_e( 'Log in', 'xstore-core' ); ?></button>
                            </p>
							
							<?php do_action( 'woocommerce_login_form_end' ); ?>

                        </form>
						
						<?php if ( $with_tabs ) : ?>
                            <form method="post" autocomplete="off"
                                  class="woocommerce-form woocommerce-form-register et_b-tab-content register"
                                  data-tab-name="register" <?php do_action( 'woocommerce_register_form_tag' ); ?>
                                  action="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>">
								
								<?php do_action( 'woocommerce_register_form_start' ); ?>
								
								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
                                        <label for="reg_username"><?php esc_html_e( 'Username', 'xstore-core' ); ?>
                                            &nbsp;<span class="required">*</span></label>
                                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="username" id="reg_username" autocomplete="username"
                                               value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                                    </p>
								
								<?php endif; ?>

                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
                                    <label for="reg_email"><?php esc_html_e( 'Email address', 'xstore-core' ); ?>
                                        &nbsp;<span class="required">*</span></label>
                                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text"
                                           name="email" id="reg_email" autocomplete="email"
                                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                                </p>
								
								<?php if ( !get_query_var('et_account-registration-generate-pass', false) ) : ?>

                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
                                        <label for="reg_password"><?php esc_html_e( 'Password', 'xstore-core' ); ?>
                                            &nbsp;<span class="required">*</span></label>
                                        <input type="password"
                                               class="woocommerce-Input woocommerce-Input--text input-text"
                                               name="password" id="reg_password" autocomplete="new-password"/>
                                    </p>
								
								<?php else : ?>

                                    <p><?php esc_html_e( 'A password will be sent to your email address.', 'xstore-core' ); ?></p>
								
								<?php endif; ?>
								
								<?php do_action( 'woocommerce_register_form' ); ?>

                                <p class="woocommerce-FormRow">
									<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce', false ); ?>
                                    <input type="hidden" name="_wp_http_referer"
                                           value="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                                    <button type="submit" class="woocommerce-Button button" name="register"
                                            value="<?php esc_attr_e( 'Register', 'xstore-core' ); ?>"><?php esc_html_e( 'Register', 'xstore-core' ); ?></button>
                                </p>
								
								<?php do_action( 'woocommerce_register_form_end' ); ?>

                            </form>
							
							<?php
							echo $login_options['form_tabs_end'];
						endif; ?>

                    </div>

                </div>
				<?php $account_dropdown .= ob_get_clean();
			} else {
				ob_start(); ?>
                <div class="et-mini-content header-account-content">
					<?php echo $canvas['close']; ?>
                    <div class="et-content">
						<?php echo $canvas['label']; ?>
						<?php
						wp_login_form(
							array(
								'echo'           => true,
								'label_username' => esc_html__( 'Username or email *', 'xstore-core' ),
								'label_password' => esc_html__( 'Password *', 'xstore-core' )
							)
						);
						?>
                    </div>
                </div>
				<?php
				$account_dropdown = ob_get_clean();
			}
		}
		if ( ! $echo ) {
			return $account_dropdown;
		}
		echo $account_dropdown;
	}
}

/**
 * Action for ajax popup.
 *
 * @since   1.4.0
 * @version 1.0.0
 */
add_action( 'wp_ajax_nopriv_etheme_ajax_popup_content', 'etheme_ajax_popup_content' );
add_action( 'wp_ajax_etheme_ajax_popup_content', 'etheme_ajax_popup_content' );

/**
 * Ajax popup with multicontent set up by parameter in js.
 *
 * @return  {html} popup content
 * @version 1.0.3
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_ajax_popup_content' ) ) {
	function etheme_ajax_popup_content() {
		if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
			WPBMap::addAllMappedShortcodes();
		}
		switch ( $_POST['type'] ) {
			case 'newsletter':
				$html = et_b_newsletter_content();
				break;
			case 'mobile_menu':
				$html = et_b_mobile_menu_content();
				break;
			case 'size_guide':
				$html = etheme_size_guide_content( $_POST['id'], $_POST['multiple'] );
				break;
			default:
				$html = '';
				break;
		}
		
		echo json_encode( $html );
		die();
		
	}
}

/**
 * Ajax mobile menu popup.
 *
 * @return  {html} popup content
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'et_b_mobile_menu_content' ) ) {
	function et_b_mobile_menu_content() {
		
		global $et_builder_globals;
		
		$et_builder_globals['in_mobile_menu'] = true;
		
		$mob_menu_element_options['mobile_menu_content'] = get_theme_mod( 'mobile_menu_content',
			array(
				'logo',
				'search',
				'menu',
				'header_socials'
			)
		);
		
		$mob_menu_element_options['mobile_menu_logo_type']   = get_theme_mod( 'mobile_menu_logo_type_et-desktop', 'simple' );
		$mob_menu_element_options['mobile_menu_logo_filter'] = ( $mob_menu_element_options['mobile_menu_logo_type'] == 'sticky' ) ? 'simple' : 'sticky';
		
		$mob_menu_element_options['mobile_menu_classes'] = ' justify-content-center';
		$mob_menu_element_options['mobile_menu_classes'] .= ' toggles-by-arrow';
		
		$mob_menu_element_options['mobile_menu_2']       = get_theme_mod( 'mobile_menu_2', 'none' );
		$mob_menu_element_options['mobile_menu_2_state'] = ( $mob_menu_element_options['mobile_menu_2'] != 'none' ) ? true : false;
		
		$mob_menu_element_options['mobile_menu_2_term']      = ( $mob_menu_element_options['mobile_menu_2'] == 'menu' ) ? get_theme_mod( 'mobile_menu_2_term' ) : '';
		$mob_menu_element_options['mobile_menu_2_term_name'] = $mob_menu_element_options['mobile_menu_2_term'] == '' ? 'main-menu' : $mob_menu_element_options['mobile_menu_2_term'];
		
		$mob_menu_element_options['mobile_menu_tab_2_text']           = get_theme_mod( 'mobile_menu_tab_2_text', 'Categories' );
		$mob_menu_element_options['mobile_menu_2_categories_primary'] = get_theme_mod( 'mobile_menu_2_categories_primary', false );
		
		$mob_menu_element_options['mobile_menu_term']      = get_theme_mod( 'mobile_menu_term' );
		$mob_menu_element_options['mobile_menu_term_name'] = $mob_menu_element_options['mobile_menu_term'] == '' ? 'main-menu' : $mob_menu_element_options['mobile_menu_term'];
		$mob_menu_element_options['mobile_menu_one_page']  = get_theme_mod( 'mobile_menu_one_page', '0' ) ? ' one-page-menu' : '';
		
		$mob_menu_element_options['mobile_menu_categories_tabs'] = $mob_menu_element_options['mobile_menu_categories_wrapper_start'] = $mob_menu_element_options['mobile_menu_categories_wrapper_end'] = '';
		if ( $mob_menu_element_options['mobile_menu_2_state'] ) {
			$mob_menu_element_options['mobile_menu_2_wrapper_start'] = '<div class="et_b-tabs-wrapper">';
			$mob_menu_element_options['mobile_menu_2_wrapper_end']   = '</div>';
			ob_start(); ?>
            <div class="et_b-tabs">
				<?php if ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) : ?>
                    <span class="et-tab <?php echo ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                          data-tab="menu">
                        <?php esc_html_e( 'Menu', 'xstore-core' ); ?>
                    </span>
				<?php endif; ?>
                <span class="et-tab <?php echo ( $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                      data-tab="menu_2">
                    <?php echo esc_html( $mob_menu_element_options['mobile_menu_tab_2_text'] ); ?>
                </span>
				<?php if ( $mob_menu_element_options['mobile_menu_2_categories_primary'] ) : ?>
                    <span class="et-tab <?php echo ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                          data-tab="menu">
                        <?php esc_html_e( 'Menu', 'xstore-core' ); ?>
                    </span>
				<?php endif; ?>
            </div>
			<?php
			$mob_menu_element_options['mobile_menu_2_tabs'] = ob_get_clean();
		}
		
		$args = array(
			'menu'            => $mob_menu_element_options['mobile_menu_term_name'],
			'before'          => '',
			'container_class' => 'menu-main-container' . $mob_menu_element_options['mobile_menu_one_page'],
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'depth'           => 4,
			'echo'            => false,
			'fallback_cb'     => false,
			'walker'          => new ETheme_Navigation
		);
		
		$mob_menu_element_options['etheme_filters'] = array(
			"etheme_logo_{$mob_menu_element_options['mobile_menu_logo_filter']}" => 'etheme_return_false',
			'logo_align'                                                         => 'etheme_return_align_center',
			'etheme_mini_content'                                                => 'etheme_return_false',
			'etheme_search_results'                                              => 'etheme_return_true',
			'search_category'                                                    => 'etheme_return_false',
			'cart_off_canvas'                                                    => 'etheme_return_false',
			'etheme_mini_cart_content'                                           => 'etheme_return_false',
			'menu_dropdown_ajax'                                                 => 'etheme_return_false',
			'etheme_mini_account_content'                                        => 'etheme_return_false',
			'et_mobile_menu'                                                     => 'etheme_return_true',
			'etheme_use_desktop_style'                                           => 'etheme_return_true',
			'search_type'                                                        => 'etheme_mobile_type_input',
			'search_by_icon'                                                     => 'etheme_return_false',
			'cart_style'                                                         => 'etheme_mobile_content_element_type1',
			'account_style'                                                      => 'etheme_mobile_content_element_type1',
			'header_socials_direction'                                           => 'etheme_return_false',
			'contacts_icon_position'                                             => 'etheme_mobile_icon_left',
			
			'etheme_output_shortcodes_inline_css' => 'etheme_return_true',
			'search_ajax_with_tabs'               => 'etheme_return_false',
			'search_mode_is_popup'                => 'etheme_return_false'
		);
		
		foreach ( $mob_menu_element_options['etheme_filters'] as $key => $value ) {
			add_filter( $key, $value, 15 );
		}
		
		ob_start();
		
		if ( is_array( $mob_menu_element_options['mobile_menu_content'] ) && count( $mob_menu_element_options['mobile_menu_content'] ) == 1 ) {
			echo '<div style="height: 0px;margin: 0px; visibility: hidden;">Fix for iphone submenus</div>';
		} // fix for iphones when scroll down submenus and only one element is shown in content ?>
		
		<?php foreach ( $mob_menu_element_options['mobile_menu_content'] as $key => $value ) {
			if ( $value == 'menu' && $mob_menu_element_options['mobile_menu_2_state'] ) {
				echo $mob_menu_element_options['mobile_menu_2_wrapper_start'];
				echo $mob_menu_element_options['mobile_menu_2_tabs'];
				?>
                <div class="et_b-tab-content <?php echo ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                     data-tab-name="menu">
					<?php
					$mob_menu_element_options['menu_getter'] = wp_nav_menu( $args );
					if ( $mob_menu_element_options['menu_getter'] != '' ) {
						?>
                        <div class="et_element et_b_header-menu header-mobile-menu flex align-items-center"
                             data-title="<?php esc_html_e( 'Menu', 'xstore-core' ); ?>">
							<?php echo $mob_menu_element_options['menu_getter']; ?>
                        </div>
					<?php } else { ?>
                        <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                                    <?php esc_html_e( 'Mobile menu ', 'xstore-core' ); ?>
                                    <span class="mtips" style="text-transform: none;">
                                        <i class="et-icon et-exclamation"
                                           style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                                        <span class="mt-mes"><?php esc_html_e( 'To use Mobile menu please select your menu in dropdown', 'xstore-core' ); ?></span>
                                    </span>
                                </span>
						<?php
					} ?>
                </div>
                <div class="et_b-tab-content <?php echo ( $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                     data-tab-name="menu_2">
					<?php
					if ( $mob_menu_element_options['mobile_menu_2'] == 'categories' ) {
						$mob_menu_element_options['mobile_menu_2_categories_params'] = array(
							'title'   => '',
							'orderby' => 'order'
						);
						if ( get_theme_mod( 'mobile_menu_2_categories_hide_empty', false ) ) {
							$mob_menu_element_options['mobile_menu_2_categories_params']['hide_empty'] = 1;
						}
						the_widget( 'WC_Widget_Product_Categories', $mob_menu_element_options['mobile_menu_2_categories_params'] );
					} else {
						$args_2 = array(
							'menu'            => $mob_menu_element_options['mobile_menu_2_term_name'],
							'before'          => '',
							'container_class' => 'menu-main-container',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'depth'           => 4,
							'echo'            => false,
							'fallback_cb'     => false,
							'walker'          => new ETheme_Navigation
						);
						$mob_menu_element_options['menu_getter'] = wp_nav_menu( $args_2 );
						if ( $mob_menu_element_options['menu_getter'] != '' ) {
							?>
                            <div class="et_element et_b_header-menu header-mobile-menu flex align-items-center"
                                 data-title="<?php esc_html_e( 'Menu', 'xstore-core' ); ?>">
								<?php echo $mob_menu_element_options['menu_getter']; ?>
                            </div>
						<?php } else { ?>
                            <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                                    <?php esc_html_e( 'Mobile menu 2', 'xstore-core' ); ?>
                                    <span class="mtips" style="text-transform: none;">
                                        <i class="et-icon et-exclamation"
                                           style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                                        <span class="mt-mes"><?php esc_html_e( 'To use Mobile menu 2 please select your menu in dropdown', 'xstore-core' ); ?></span>
                                    </span>
                                </span>
						<?php }
					}
					?>
                </div>
				<?php
				echo $mob_menu_element_options['mobile_menu_2_wrapper_end'];
			} else {
				if ( $value == 'menu' ) {
					$mob_menu_element_options['menu_getter'] = wp_nav_menu( $args );
					if ( $mob_menu_element_options['menu_getter'] != '' ) {
						?>
                        <div class="et_element et_b_header-menu header-mobile-menu flex align-items-center"
                             data-title="<?php esc_html_e( 'Menu', 'xstore-core' ); ?>">
							<?php echo $mob_menu_element_options['menu_getter']; ?>
                        </div>
					<?php } else { ?>
                        <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                                <?php esc_html_e( 'Mobile menu ', 'xstore-core' ); ?>
                                <span class="mtips" style="text-transform: none;">
                                    <i class="et-icon et-exclamation"
                                       style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                                    <span class="mt-mes"><?php esc_html_e( 'To use Mobile menu please select your menu in dropdown', 'xstore-core' ); ?></span>
                                </span>
                            </span>
						<?php
					}
				} else {
					require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $value . '.php' );
				}
			}
		}
		
		$html = ob_get_clean();
		
		foreach ( $mob_menu_element_options['etheme_filters'] as $key => $value ) {
			remove_filter( $key, $value, 15 );
		}
		
		unset( $mob_menu_element_options );
		$et_builder_globals['in_mobile_menu'] = false;
		
		return array(
			'html'    => $html,
			'classes' => $mob_menu_element_options['mobile_menu_classes'],
		);
	}
}

/**
 * Ajax newsletter menu popup.
 *
 * @return  {html} popup content
 * @version 1.0.1
 *          last changes in 1.5.5
 * @since   1.4.0
 */
if ( ! function_exists( 'et_b_newsletter_content' ) ) {
	function et_b_newsletter_content() {
		
		$element_options                                              = array();
		$element_options['newsletter_title_et-desktop']               = get_theme_mod( 'newsletter_title_et-desktop', 'Title' );
		$element_options['newsletter_content_et-desktop']             = get_theme_mod( 'newsletter_content_et-desktop', '<p>You can add any HTML here (admin -&gt; Theme Options -&gt; Header builder -&gt; Newsletter).<br /> We suggest you create a static block and use it by turning on the settings below</p>' );
		$element_options['newsletter_content_alignment_et-desktop']   = ' align-' . get_theme_mod( 'newsletter_content_alignment_et-desktop', 'start' );
		$element_options['newsletter_section_et-desktop']             = ( get_theme_mod( 'newsletter_sections_et-desktop', 0 ) ) ? get_theme_mod( 'newsletter_section_et-desktop', '' ) : '';
		$element_options['newsletter_content_et-desktop']             = ( $element_options['newsletter_section_et-desktop'] != '' && $element_options['newsletter_section_et-desktop'] > 0 ) ? $element_options['newsletter_section_et-desktop'] : $element_options['newsletter_content_et-desktop'];
		$element_options['newsletter_close_button_action_et-desktop'] = get_theme_mod( 'newsletter_close_button_action_et-desktop', 1 );
		
		ob_start();
		$element_options['class'] = ( $element_options['newsletter_section_et-desktop'] != '' ) ? 'with-static-block' : '';
		$element_options['class'] .= $element_options['newsletter_content_alignment_et-desktop'];
		$element_options['class'] .= get_theme_mod( 'newsletter_content_width_height_et-desktop', 'auto' ) == 'custom' ? ' et-popup-content-custom-dimenstions' : '';
		?>
        <div class="et-popup-wrapper header-newsletter-popup">
            <div class="et-popup">
                <div class="et-popup-content <?php esc_attr_e( $element_options['class'] ); ?>">
					<?php echo header_newsletter_content_callback(); ?>
                </div>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		unset( $element_options );
		
		return $html;
	}
}

/**
 * Ajax size guide popup.
 *
 * @return  {html} popup content
 * @version 1.0.2
 *          last changes in 1.5.5
 * @since   1.5.0
 */
if ( ! function_exists( 'etheme_size_guide_content' ) ) {
	function etheme_size_guide_content( $id, $multiple ) {
		
		$element_options                                                    = array();
		$element_options['product_size_guide_img_et-desktop']               = get_theme_mod( 'product_size_guide_img_et-desktop', 'https://xstore.8theme.com/wp-content/uploads/2018/08/Size-guide.jpg' );
		$element_options['product_size_guide_title_et-desktop']             = get_theme_mod( 'product_size_guide_title_et-desktop', 'Title' );
		$element_options['product_size_guide_section_et-desktop']           = ( get_theme_mod( 'product_size_guide_sections_et-desktop', 0 ) ) ? get_theme_mod( 'product_size_guide_section_et-desktop', '' ) : '';
		$element_options['product_size_guide_content_et-desktop']           = ( $element_options['product_size_guide_section_et-desktop'] != '' && $element_options['product_size_guide_section_et-desktop'] > 0 ) ? $element_options['product_size_guide_section_et-desktop'] : '<img src="' . $element_options['product_size_guide_img_et-desktop'] . '" alt="' . esc_html__( 'sizing guide', 'xstore-core' ) . '">';
		$element_options['product_size_guide_content_alignment_et-desktop'] = ' align-' . get_theme_mod( 'product_size_guide_content_alignment_et-desktop', 'start' );
		
		$element_options['class'] = ( $element_options['product_size_guide_section_et-desktop'] != '' ) ? 'with-static-block' : '';
		$element_options['class'] .= $element_options['product_size_guide_content_alignment_et-desktop'];
		$element_options['class'] .= get_theme_mod( 'product_size_guide_content_width_height_et-desktop', 'auto' ) == 'custom' ? ' et-popup-content-custom-dimenstions' : '';
		
		ob_start();
		
		?>
        <div class="et-popup-wrapper size-guide-popup">
            <div class="et-popup">
                <div class="et-popup-content <?php esc_attr_e( $element_options['class'] ); ?>">
					<?php echo product_size_guide_content_callback( $id, $multiple ); ?>
                </div>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		unset( $element_options );
		
		return $html;
	}
}

/**
 * Ajax age verify popup.
 *
 * @return  {html} popup content
 * @version 1.0.0
 * @since   4.3.5
 */
if ( ! function_exists( 'et_age_verify_popup_content' ) ) {
	function et_age_verify_popup_content() {
		
		if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
			WPBMap::addAllMappedShortcodes();
		}
		
		$element_options = array();
//		$element_options['age_verify_popup_title']               = get_theme_mod( 'age_verify_popup_title', 'Title' );
//		$element_options['age_verify_popup_content']             = get_theme_mod( 'age_verify_popup_content', '<p>You can add any HTML here (admin -&gt; Theme Options -&gt; Age verify popup).<br /> We suggest you create a static block and use it by turning on the settings below</p>' );
		$element_options['age_verify_popup_content_alignment']  = ' align-' . get_theme_mod( 'age_verify_popup_content_alignment', 'center' );
		$element_options['age_verify_popup_section_et-desktop'] = ( get_theme_mod( 'age_verify_popup_sections_et-desktop', 0 ) ) ? get_theme_mod( 'age_verify_popup_section_et-desktop', '' ) : '';
//		$element_options['age_verify_popup_content']             = ( $element_options['age_verify_popup_section_et-desktop'] != '' && $element_options['age_verify_popup_section_et-desktop'] > 0 ) ? $element_options['age_verify_popup_section_et-desktop'] : $element_options['age_verify_popup_content'];
		
		ob_start();
		$element_options['class'] = $element_options['age_verify_popup_content_alignment'];
		$element_options['class'] .= get_theme_mod( 'age_verify_popup_content_width_height', 'auto' ) == 'custom' ? ' et-popup-content-custom-dimenstions' : '';
		?>
        <div class="et-popup-wrapper et-age-verify-popup">
            <div class="et-popup">
                <div class="et-popup-content <?php esc_attr_e( $element_options['class'] ); ?>">
					<?php echo et_age_verify_popup_content_callback(); ?>
                </div>
                <div class="et-popup-content hidden <?php esc_attr_e( $element_options['class'] ); ?>">
					<?php echo et_age_verify_popup_error_content_callback(); ?>
                </div>
            </div>
        </div>
		<?php
		unset( $element_options );
		
		return ob_get_clean();
	}
}

add_action( 'wp_body_open', 'et_age_verify_anchor', - 999 );
if ( ! function_exists( 'et_age_verify_anchor' ) ) {
	function et_age_verify_anchor() {
		if ( ! get_theme_mod( 'age_verify_popup_switcher', 0 ) ) {
			return;
		}
		if ( !get_theme_mod( 'age_verify_popup_switcher_dev_mode', 0 ) && isset( $_COOKIE['age_verify_popup_shows'] ) && $_COOKIE['age_verify_popup_shows'] == 'false' ) {
			return;
		}
		echo et_age_verify_popup_content();
	}
}

/**
 * Return true.
 * In most case uses in filters.
 *
 * @param   {string} content
 *
 * @return  {bool} true
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_return_true' ) ) {
	function etheme_return_true( $content = '' ) {
		return true;
	}
}

/**
 * Return "yes".
 * In most case uses in filters.
 *
 * @param   {string} content
 *
 * @return  {bool} true
 * @version 1.0.0
 * @since   2.3.8
 */
if ( ! function_exists( 'etheme_return_yes' ) ) {
	function etheme_return_yes( $content ) {
		return "yes";
	}
}

/**
 * Return false.
 * In most case uses in filters.
 *
 * @param   {string} content
 *
 * @return  {bool} false
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_return_false' ) ) {
	function etheme_return_false( $content ) {
		return false;
	}
}

/**
 * Return none.
 * In most case uses in filters.
 *
 * @param   {string} content
 *
 * @return  {string} none
 * @version 1.0.0
 * @since   1.5.4
 */
if ( ! function_exists( 'etheme_return_none' ) ) {
	function etheme_return_none( $content ) {
		return 'none';
	}
}

/**
 * Return classes without selected ones.
 * In most case uses in filters.
 *
 * @param   {array} params
 *
 * @return  {array} params
 * @version 1.0.0
 * @since   3.2.4
 */
if ( ! function_exists( 'etheme_filter_widgets_classes' ) ) {
	function etheme_filter_widgets_classes( $params ) {
		if ( isset( $params[0]['before_widget'] ) ) {
			$params[0]['before_widget'] = str_replace( array(
				'sidebar-widget',
				'topbar-widget',
				'mobile-sidebar-widget',
				'top-panel-widget',
				'footer-widget',
				'copyrights-widget'
			), array( '' ), $params[0]['before_widget'] );
		}
		
		return $params;
	}
}

/**
 * Return type.
 * In most case uses in filters.
 *
 * @param   {string} content
 *
 * @return  {string} type1
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_mobile_content_element_type1' ) ) {
	function etheme_mobile_content_element_type1( $el_type ) {
		return 'type1';
	}
}

/**
 * Return input type - input.
 * It uses in filters for mobile menu.
 *
 * @param   {string} content
 *
 * @return  {string} input
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_mobile_type_input' ) ) {
	function etheme_mobile_type_input( $search_type ) {
		return 'input';
	}
}

/**
 * Return mobile icon position - left.
 * It uses in filters for mobile menu.
 *
 * @param   {string} content
 *
 * @return  {string} left
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_mobile_icon_left' ) ) {
	function etheme_mobile_icon_left( $position ) {
		return 'left';
	}
}

/**
 * Return align center content.
 * It uses in filters for mobile menu.
 *
 * @param   {string} content
 *
 * @return  {string} align of content
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_return_align_center' ) ) {
	function etheme_return_align_center( $align ) {
		return 'justify-content-center';
	}
}

/**
 * Return align inherit for content.
 * It uses in filters for mobile menu.
 *
 * @param   {string} content
 *
 * @return  {string} align of content
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_return_align_inherit' ) ) {
	function etheme_return_align_inherit( $align ) {
		return 'align-inherit justify-content-inherit';
	}
}

/**
 * Return mobile search type.
 *
 * @return  {string} search type for mobile header
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_mobile_search_type' ) ) {
	function etheme_mobile_search_type() {
		return get_theme_mod( 'search_type_et-mobile', 'icon' );
	}
}

/**
 * Return mobile account icon type.
 *
 * @return  {string} mobile account icon type for mobile header
 * @version 1.0.0
 * @since   1.4.0
 */
if ( ! function_exists( 'etheme_mobile_account_icon' ) ) {
	function etheme_mobile_account_icon() {
		return get_theme_mod( 'account_icon_et-mobile', 'type1' );
	}
}

/**
 * Return mobile account custom icon.
 *
 * @return  {string} mobile account custom icon for mobile header
 * @version 1.0.0
 * @since   3.2.6
 */
if ( ! function_exists( 'etheme_mobile_account_icon_custom' ) ) {
	function etheme_mobile_account_icon_custom() {
		return get_theme_mod( 'account_icon_custom_svg_et-mobile', get_theme_mod( 'account_icon_custom_svg_et-desktop', '' ) );
	}
}

/**
 * Return mobile account content type.
 *
 * @return  {string} mobile account content type for mobile header
 * @version 1.0.0
 * @since   2.2.4
 */
if ( ! function_exists( 'etheme_mini_account_content_mobile' ) ) {
	function etheme_mini_account_content_mobile() {
		return get_theme_mod( 'account_content_type_et-mobile', 'off_canvas' );
	}
}

/**
 * Return mobile account content position.
 *
 * @return  {string} mobile account content position for mobile header
 * @version 1.0.0
 * @since   2.2.4
 */
if ( ! function_exists( 'etheme_mini_account_content_position_mobile' ) ) {
	function etheme_mini_account_content_position_mobile() {
		return get_theme_mod( 'account_content_position_et-mobile', 'right' );
	}
}

/**
 * Return mobile cart content type.
 *
 * @return  {string} mobile cart content type for mobile header
 * @version 1.0.0
 * @since   2.2.4
 */
if ( ! function_exists( 'etheme_mini_cart_content_mobile' ) ) {
	function etheme_mini_cart_content_mobile() {
		return get_theme_mod( 'cart_content_type_et-mobile', 'off_canvas' );
	}
}

/**
 * Return mobile cart content position.
 *
 * @return  {string} mobile cart content position for mobile header
 * @version 1.0.0
 * @since   2.2.4
 */
if ( ! function_exists( 'etheme_mini_cart_content_position_mobile' ) ) {
	function etheme_mini_cart_content_position_mobile() {
		return get_theme_mod( 'cart_content_position_et-mobile', 'right' );
	}
}

/**
 * Return mobile wishlist content type.
 *
 * @return  {string} mobile wishlist content type for mobile header
 * @version 1.0.0
 * @since   2.2.4
 */
if ( ! function_exists( 'etheme_mini_wishlist_content_mobile' ) ) {
	function etheme_mini_wishlist_content_mobile() {
		return get_theme_mod( 'wishlist_content_type_et-mobile', 'off_canvas' );
	}
}

/**
 * Return mobile wishlist content position.
 *
 * @return  {string} mobile wishlist content position for mobile header
 * @version 1.0.0
 * @since   2.2.4
 */
if ( ! function_exists( 'etheme_mini_wishlist_content_position_mobile' ) ) {
	function etheme_mini_wishlist_content_position_mobile() {
		return get_theme_mod( 'wishlist_content_position_et-mobile', 'right' );
	}
}

/**
 * Return mobile compare content type.
 *
 * @return  {string} mobile compare content type for mobile header
 * @version 1.0.0
 * @since   4.3.9
 */
if ( ! function_exists( 'etheme_mini_compare_content_mobile' ) ) {
    function etheme_mini_compare_content_mobile() {
        return get_theme_mod( 'compare_content_type_et-mobile', 'off_canvas' );
    }
}

/**
 * Return mobile compare content position.
 *
 * @return  {string} mobile compare content position for mobile header
 * @version 1.0.0
 * @since   4.3.9
 */
if ( ! function_exists( 'etheme_mini_compare_content_position_mobile' ) ) {
    function etheme_mini_compare_content_position_mobile() {
        return get_theme_mod( 'compare_content_position_et-mobile', 'right' );
    }
}

/**
 * Return mobile search icon.
 *
 * @return  {string} mobile search icon for mobile header
 * @version 1.0.0
 * @since   3.1.3
 */
if ( ! function_exists( 'etheme_search_icon_mobile' ) ) {
	function etheme_search_icon_mobile() {
		return get_theme_mod( 'search_icon_et-mobile', 'type1' );
	}
}

/**
 * Return mobile search custom icon.
 *
 * @return  {string} mobile search custom icon for mobile header
 * @version 1.0.0
 * @since   3.1.3
 */
if ( ! function_exists( 'etheme_search_icon_custom_mobile' ) ) {
	function etheme_search_icon_custom_mobile() {
		return get_theme_mod( 'search_icon_custom_et-mobile', get_theme_mod( 'search_icon_custom_et-desktop', '' ) );
	}
}

/**
 * Header vertical logo.
 *
 * @return  {string} logo type in vertical header
 * @see     templates/header/header-vertical
 * @version 1.0.0
 * @since   1.4.2
 */
if ( ! function_exists( 'etheme_vertical_header_logo' ) ) {
	function etheme_vertical_header_logo( $logo ) {
		return get_theme_mod( 'header_vertical_logo_img_et-desktop', '' );
	}
}

/**
 * Menu item design dropdown.
 *
 * @return  {string} item-design-dropdown for any menu item type
 * @see     templates/header/header-vertical
 * @version 1.0.0
 * @since   1.4.2
 */
if ( ! function_exists( 'etheme_menu_item_design_dropdown' ) ) {
	function etheme_menu_item_design_dropdown( $design ) {
		return 'item-design-dropdown';
	}
}

/**
 * Custom svg code from id of svg file.
 *
 * @return  {string} html of svg icon
 * @see     callbacks.php
 * @version 1.0.1
 * @since   3.2.8
 */
if ( ! function_exists( 'etheme_get_svg_icon' ) ) {
	function etheme_get_svg_icon( $icon_id = '' ) {
		$element_options                          = array();
		$element_options['icon_custom_type']      = get_post_mime_type( $icon_id );
		$element_options['icon_custom_mime_type'] = explode( '/', $element_options['icon_custom_type'] );
		if (
			isset($element_options['icon_custom_mime_type'])
			&& isset($element_options['icon_custom_mime_type']['1'])
            && $element_options['icon_custom_mime_type']['1'] == 'svg+xml'
        ) {
			$element_options['rendered_svg'] = get_post_meta( $icon_id, '_xstore_inline_svg', true );
			
			if ( ! empty( $element_options['rendered_svg'] ) ) {
				return $element_options['rendered_svg'];
			} else {
				
				$element_options['attachment_file'] = get_attached_file( $icon_id );
				
				if ( $element_options['attachment_file'] ) {
					
					$element_options['rendered_svg'] = file_get_contents( $element_options['attachment_file'] );
					
					if ( ! empty( $element_options['rendered_svg'] ) ) {
						update_post_meta( $icon_id, '_xstore_inline_svg', $element_options['rendered_svg'] );
					}
					
					return $element_options['rendered_svg'];
					
				}
				
			}
		} elseif ( function_exists( 'etheme_get_image' ) ) {
			return etheme_get_image( $icon_id, 'thumbnail' );
		}
	}
}


if ( ! function_exists( 'etheme_woocommerce_template_single_title' ) ) {
	/**
	 * Single product title function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_title() {
		woocommerce_template_single_title();
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_single_rating' ) ) {
	/**
	 * Single product rating function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_rating() {
		woocommerce_template_single_rating();
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_single_price' ) ) {
	/**
	 * Single product price function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_price() {
		if ( ! ( etheme_is_catalog() && get_theme_mod( 'just_catalog_price', 0 ) && get_theme_mod( 'ltv_price', esc_html__( 'Login to view price', 'xstore-core' ) ) == '' ) ) {
			woocommerce_template_single_price();
		}
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_single_excerpt' ) ) {
	/**
	 * Single product short description function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_excerpt() {
		woocommerce_template_single_excerpt();
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_single_add_to_cart' ) ) {
	/**
	 * Single product cart form function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_add_to_cart() {
		
		$is_builder = apply_filters( 'etheme_woocommerce_template_single_add_to_cart_hooks', true );
		$quantity_type_o = get_theme_mod('shop_quantity_type', 'input');
        $quantity_type = get_theme_mod( 'product_quantity_style_et-desktop', 'simple' );
        $quantity_type = apply_filters( 'product_quantity_style', $quantity_type );
		
		if ( $is_builder ) :
			// remove default icons and add it after action
			remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
			remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
			
			add_action( 'woocommerce_before_quantity_input_field', 'etheme_woocommerce_before_add_to_cart_quantity', 10 );
			add_action( 'woocommerce_after_quantity_input_field', 'etheme_woocommerce_after_add_to_cart_quantity', 10 );
			
			add_filter( 'woocommerce_cart_item_quantity', 'etheme_woocommerce_cart_item_quantity', 3, 20 );
			if ( in_array('select', array($quantity_type, $quantity_type_o) ) ) {
                $select_type_ranges_o = get_theme_mod('shop_quantity_select_ranges', '1-5');
                $select_type_ranges = get_theme_mod('product_quantity_select_ranges', $select_type_ranges_o);
			    add_filter('theme_mod_shop_quantity_type', function ($value) use ($quantity_type) {
			        return $quantity_type == 'select' ? 'select' : 'input';
                });
                add_filter('theme_mod_shop_quantity_select_ranges', function ($value) use ($select_type_ranges) {
                    return $select_type_ranges;
                });
            }
		endif;
		
		$just_catalog = etheme_is_catalog();
		if ( $just_catalog ) {
			remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
		}
		woocommerce_template_single_add_to_cart();
		
		if ( $is_builder ) :
			remove_filter( 'woocommerce_cart_item_quantity', 'etheme_woocommerce_cart_item_quantity', 3, 20 );
			
			remove_action( 'woocommerce_before_quantity_input_field', 'etheme_woocommerce_before_add_to_cart_quantity', 10 );
			remove_action( 'woocommerce_after_quantity_input_field', 'etheme_woocommerce_after_add_to_cart_quantity', 10 );
			
			add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
			add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
            if ( in_array('select', array($quantity_type, $quantity_type_o) ) ) {
                add_filter('theme_mod_shop_quantity_type', function ($value) use ($quantity_type_o) {
                    return $quantity_type_o;
                });
                add_filter('theme_mod_shop_quantity_select_ranges', function ($value) use ($select_type_ranges_o) {
                    return $select_type_ranges_o;
                });
            }
		endif;
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_single_meta' ) ) {
	/**
	 * Single product meta function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_meta() {
		woocommerce_template_single_meta();
	}
}

if ( ! function_exists( 'etheme_product_single_sharing' ) ) {
	/**
	 * Single product sharing function (theme socials).
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_sharing() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-sharing.php' );
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_single_sharing' ) ) {
	/**
	 * Single product sharing function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_single_sharing() {
		woocommerce_template_single_sharing();
	}
}

if ( ! function_exists( 'etheme_woocommerce_show_product_images' ) ) {
	/**
	 * Single product gallery function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_show_product_images() {
		/**
		 * Single sale label
		 * @see etheme_woocommerce_sale_flash()
		 */
		$sale_show             = ( get_theme_mod( 'product_sale_label_type_et-desktop', 'square' ) != 'none' );
		$sale_label_percentage = get_theme_mod( 'product_sale_label_percentage_et-desktop', 0 );
		if ( ! $sale_show ) {
			add_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		} elseif ( $sale_label_percentage ) {
			add_filter( 'etheme_sale_label_percentage', 'etheme_return_true', 15 );
		} else {
			add_filter( 'etheme_sale_label_percentage', 'etheme_return_false', 15 );
		}
		add_filter( 'etheme_sale_label_single', 'etheme_return_true', 15 );
		
		woocommerce_show_product_images();
		
		remove_filter( 'etheme_sale_label_single', 'etheme_return_true', 15 );
		if ( ! $sale_show ) {
			remove_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		} elseif ( $sale_label_percentage ) {
			remove_filter( 'etheme_sale_label_percentage', 'etheme_return_true', 15 );
		} else {
			remove_filter( 'etheme_sale_label_percentage', 'etheme_return_false', 15 );
		}
	}
}

if ( ! function_exists( 'etheme_woocommerce_output_product_data_tabs' ) ) {
	/**
	 * Single product tabs function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_output_product_data_tabs() {
		woocommerce_output_product_data_tabs();
	}
}


if ( ! function_exists( 'etheme_woocommerce_output_upsell_products' ) ) {
	/**
	 * Single product upsell products function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.2
	 */
	
	function etheme_woocommerce_output_upsell_products() {
		global $woocommerce_loop;
		$products_upsells_sale_label  = get_theme_mod( 'products_upsells_sale_label_et-desktop', 0 );
		$products_upsells_view        = get_theme_mod( 'products_upsell_product_view_et-desktop', 'inherit' );
		$products_upsells_view_color  = get_theme_mod( 'products_upsell_product_view_color_et-desktop', 'inherit' );
		$products_upsells_img_hover   = get_theme_mod( 'products_upsell_product_img_hover_et-desktop', 'inherit' );
		$products_upsell_out_of_stock = get_theme_mod( 'products_upsell_out_of_stock_et-desktop', 0 );
		
		if ( $products_upsells_sale_label ) {
			/**
			 * Upsell product sale label
			 * @see etheme_return_false()
			 */
			
			add_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		}
		
		if ( $products_upsells_view != 'inherit' ) {
			$before_view = false;
			if ( isset( $woocommerce_loop['product_view'] ) ) {
				$before_view         = true;
				$before_upsells_view = $woocommerce_loop['product_view'];
			}
			$woocommerce_loop['product_view'] = $products_upsells_view;
		}
		
		if ( $products_upsells_view_color != 'inherit' ) {
			$before_view_color = false;
			if ( isset( $woocommerce_loop['product_view_color'] ) ) {
				$before_view_color         = true;
				$before_upsells_view_color = $woocommerce_loop['product_view_color'];
			}
			$woocommerce_loop['product_view_color'] = $products_upsells_view_color;
		}
		
		if ( $products_upsells_img_hover != 'inherit' ) {
			$before_img_hover = false;
			if ( isset( $woocommerce_loop['hover'] ) ) {
				$before_img_hover         = true;
				$before_related_img_hover = $woocommerce_loop['hover'];
			}
			$woocommerce_loop['hover'] = $products_upsells_img_hover;
		}
		
		/**
		 * Upsell products args
		 * @see etheme_set_upsells_product_args()
		 */
		
		add_filter( 'product_type_grid', 'etheme_return_true' );
		if ( $products_upsell_out_of_stock ) {
			add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'etheme_return_yes' );
		}
		add_filter( 'woocommerce_upsell_display_args', 'etheme_set_upsells_product_args' );
		add_filter( 'upsell_slides', 'etheme_set_upsells_product_slider_args' );
		
		woocommerce_upsell_display();
		
		remove_filter( 'upsell_slides', 'etheme_set_upsells_product_slider_args' );
		remove_filter( 'woocommerce_upsell_display_args', 'etheme_set_upsells_product_args' );
		if ( $products_upsell_out_of_stock ) {
			remove_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'etheme_return_yes' );
		}
		remove_filter( 'product_type_grid', 'etheme_return_true' );
		
		if ( $products_upsells_view != 'inherit' ) {
			if ( $before_view ) {
				$woocommerce_loop['product_view'] = $before_upsells_view;
			} else {
				unset( $woocommerce_loop['product_view'] );
			}
		}
		
		if ( $products_upsells_view_color != 'inherit' ) {
			if ( $before_view_color ) {
				$woocommerce_loop['product_view_color'] = $before_upsells_view_color;
			} else {
				unset( $woocommerce_loop['product_view_color'] );
			}
		}
		
		if ( $products_upsells_img_hover != 'inherit' ) {
			if ( $before_img_hover ) {
				$woocommerce_loop['hover'] = $before_related_img_hover;
			} else {
				unset( $woocommerce_loop['hover'] );
			}
		}
		
		if ( $products_upsells_sale_label ) {
			remove_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		}
		
		unset( $products_upsells_sale_label );
	}
}

if ( ! function_exists( 'etheme_woocommerce_output_cross_sells_products' ) ) {
	/**
	 * Single product cross_sells products function.
	 *
	 * @return string
	 * @since   3.1.4
	 * @version 1.0.1
	 */
	
	function etheme_woocommerce_output_cross_sells_products() {
		global $woocommerce_loop;
		$products_cross_sells_sale_label   = get_theme_mod( 'products_cross_sells_sale_label_et-desktop', 0 );
		$products_cross_sells_view         = get_theme_mod( 'products_cross_sells_product_view_et-desktop', 'inherit' );
		$products_cross_sells_view_color   = get_theme_mod( 'products_cross_sells_product_view_color_et-desktop', 'inherit' );
		$products_cross_sells_img_hover    = get_theme_mod( 'products_cross_sells_product_img_hover_et-desktop', 'inherit' );
		$products_cross_sells_out_of_stock = get_theme_mod( 'products_cross_sell_out_of_stock_et-desktop', 0 );
		
		if ( $products_cross_sells_sale_label ) {
			/**
			 * cross_sells product sale label
			 * @see etheme_return_false()
			 */
			
			add_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		}
		
		
		if ( $products_cross_sells_view != 'inherit' ) {
			$before_view = false;
			if ( isset( $woocommerce_loop['product_view'] ) ) {
				$before_view             = true;
				$before_cross_sells_view = $woocommerce_loop['product_view'];
			}
			$woocommerce_loop['product_view'] = $products_cross_sells_view;
		}
		
		if ( $products_cross_sells_view_color != 'inherit' ) {
			$before_view_color = false;
			if ( isset( $woocommerce_loop['product_view_color'] ) ) {
				$before_view_color             = true;
				$before_cross_sells_view_color = $woocommerce_loop['product_view_color'];
			}
			$woocommerce_loop['product_view_color'] = $products_cross_sells_view_color;
		}
		
		if ( $products_cross_sells_img_hover != 'inherit' ) {
			$before_img_hover = false;
			if ( isset( $woocommerce_loop['hover'] ) ) {
				$before_img_hover         = true;
				$before_related_img_hover = $woocommerce_loop['hover'];
			}
			$woocommerce_loop['hover'] = $products_cross_sells_img_hover;
		}
		
		/**
		 * cross_sells products args
		 * @see etheme_set_cross_sells_product_args()
		 */
		
		add_filter( 'product_type_grid', 'etheme_return_true' );
		if ( $products_cross_sells_out_of_stock ) {
			add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'etheme_return_yes' );
		}
		add_filter( 'woocommerce_cross_sells_display_args', 'etheme_set_cross_sells_product_args' );
		add_filter( 'cross_sell_slides', 'etheme_set_cross_sells_product_slider_args' );

//      start
		global $product;
		
		if ( ! $product ) {
			return;
		}
		
		// Handle the legacy filter which controlled posts per page etc.
		$args = apply_filters(
			'woocommerce_cross_sells_display_args',
			array(
				'posts_per_page' => - 1,
				'orderby'        => 'rand',
				'order'          => 'desc',
				'columns'        => 4,
			)
		);
		wc_set_loop_prop( 'name', 'cross-sells' );
		wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_cross_sells_columns', isset( $args['columns'] ) ? $args['columns'] : 4 ) );
		
		$orderby = apply_filters( 'woocommerce_cross_sells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : 'rand' );
		$order   = apply_filters( 'woocommerce_cross_sells_order', isset( $args['order'] ) ? $args['order'] : 'desc' );
		$limit   = apply_filters( 'woocommerce_cross_sells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : '-1' );
		
		// Get visible upsells then sort them at random, then limit result set.
//		$cross_sells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_cross_sell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
		$cross_sells = $product->get_cross_sell_ids();
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;
		
		wc_get_template(
			'cart/cross-sells.php',
			array(
				'cross_sells'    => $cross_sells,
				
				// Not used now, but used in previous version of cross-sells.php.
				'posts_per_page' => $limit,
				'orderby'        => $orderby,
				'columns'        => 4,
			)
		);
//      end
		
		remove_filter( 'cross_sell_slides', 'etheme_set_cross_sells_product_slider_args' );
		remove_filter( 'woocommerce_cross_sells_display_args', 'etheme_set_cross_sells_product_args' );
		if ( $products_cross_sells_out_of_stock ) {
			remove_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'etheme_return_yes' );
		}
		remove_filter( 'product_type_grid', 'etheme_return_true' );
		
		if ( $products_cross_sells_view != 'inherit' ) {
			if ( $before_view ) {
				$woocommerce_loop['product_view'] = $before_cross_sells_view;
			} else {
				unset( $woocommerce_loop['product_view'] );
			}
		}
		
		if ( $products_cross_sells_view_color != 'inherit' ) {
			if ( $before_view_color ) {
				$woocommerce_loop['product_view_color'] = $before_cross_sells_view_color;
			} else {
				unset( $woocommerce_loop['product_view_color'] );
			}
		}
		
		if ( $products_cross_sells_img_hover != 'inherit' ) {
			if ( $before_img_hover ) {
				$woocommerce_loop['hover'] = $before_related_img_hover;
			} else {
				unset( $woocommerce_loop['hover'] );
			}
		}
		
		if ( $products_cross_sells_sale_label ) {
			remove_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		}
		
		unset( $products_cross_sells_sale_label );
	}
}

if ( ! function_exists( 'etheme_woocommerce_output_related_products' ) ) {
	/**
	 * Single product related products function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.3
	 * @log     slider args via filter function
	 */
	
	function etheme_woocommerce_output_related_products() {
		global $woocommerce_loop;
		$products_related_sale_label   = get_theme_mod( 'products_related_sale_label_et-desktop', 1 );
		$products_related_view         = get_theme_mod( 'products_related_product_view_et-desktop', 'inherit' );
		$products_related_view_color   = get_theme_mod( 'products_related_product_view_color_et-desktop', 'inherit' );
		$products_related_img_hover    = get_theme_mod( 'products_related_product_img_hover_et-desktop', 'inherit' );
		$products_related_out_of_stock = get_theme_mod( 'products_related_out_of_stock_et-desktop', 0 );
		
		if ( $products_related_sale_label ) {
			/**
			 * Related product sale label
			 * @see etheme_return_false()
			 */
			
			add_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		}
		
		/**
		 * Related products args
		 * @see etheme_set_related_product_args()
		 */
		
		if ( $products_related_view != 'inherit' ) {
			$before_view = false;
			if ( isset( $woocommerce_loop['product_view'] ) ) {
				$before_view         = true;
				$before_related_view = $woocommerce_loop['product_view'];
			}
			$woocommerce_loop['product_view'] = $products_related_view;
		}
		
		if ( $products_related_view_color != 'inherit' ) {
			$before_view_color = false;
			if ( isset( $woocommerce_loop['product_view_color'] ) ) {
				$before_view_color         = true;
				$before_related_view_color = $woocommerce_loop['product_view_color'];
			}
			$woocommerce_loop['product_view_color'] = $products_related_view_color;
		}
		
		if ( $products_related_img_hover != 'inherit' ) {
			$before_img_hover = false;
			if ( isset( $woocommerce_loop['hover'] ) ) {
				$before_img_hover         = true;
				$before_related_img_hover = $woocommerce_loop['hover'];
			}
			$woocommerce_loop['hover'] = $products_related_img_hover;
		}
		
		add_filter( 'product_type_grid', 'etheme_return_true' );
		if ( $products_related_out_of_stock ) {
			add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'etheme_return_yes' );
		}
		add_filter( 'woocommerce_output_related_products_args', 'etheme_set_related_product_args' );
		add_filter( 'related_slides', 'etheme_set_related_product_slider_args' );
		
		woocommerce_output_related_products();
		
		remove_filter( 'related_slides', 'etheme_set_related_product_slider_args' );
		remove_filter( 'woocommerce_output_related_products_args', 'etheme_set_related_product_args' );
		if ( $products_related_out_of_stock ) {
			remove_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'etheme_return_yes' );
		}
		remove_filter( 'product_type_grid', 'etheme_return_true' );
		
		if ( $products_related_view != 'inherit' ) {
			if ( $before_view ) {
				$woocommerce_loop['product_view'] = $before_related_view;
			} else {
				unset( $woocommerce_loop['product_view'] );
			}
		}
		
		if ( $products_related_view_color != 'inherit' ) {
			if ( $before_view_color ) {
				$woocommerce_loop['product_view_color'] = $before_related_view_color;
			} else {
				unset( $woocommerce_loop['product_view_color'] );
			}
		}
		
		if ( $products_related_img_hover != 'inherit' ) {
			if ( $before_img_hover ) {
				$woocommerce_loop['hover'] = $before_related_img_hover;
			} else {
				unset( $woocommerce_loop['hover'] );
			}
		}
		
		if ( $products_related_sale_label ) {
			remove_filter( 'woocommerce_sale_flash', 'etheme_return_false', 20, 3 );
		}
		
		unset( $products_related_sale_label );
	}
}

if ( ! function_exists( 'etheme_set_related_product_args' ) ) {
	/**
	 * Single product related product args function.
	 *
	 * @param array - default woocommerce settings of product loop for related products
	 *
	 * @return array - settings
	 * @version 1.0.1
	 * @since   1.4.5
	 * @log     added mobile per view slides
	 */
	
	function etheme_set_related_product_args( $args ) {
		
		$args['posts_per_page'] = get_theme_mod( 'products_related_limit_et-desktop', 7 );
		$args['columns']        = get_theme_mod( 'products_related_per_view_et-desktop', 4 );
		$args['columns']        = get_query_var( 'is_mobile', false ) ? get_theme_mod( 'products_related_per_view_et-mobile', 2 ) : $args['columns'];
		
		return $args;
	}
}

if ( ! function_exists( 'etheme_set_related_product_slider_args' ) ) {
	/**
	 * Single product cross sells product args function.
	 *
	 * @param array - default slider args cross sells products
	 *
	 * @return array - settings
	 * @version 1.0.1
	 * @since   3.2.4
	 */
	
	function etheme_set_related_product_slider_args( $slider_args ) {
		
		$slider_args['large']           = $slider_args['notebook'] = get_theme_mod( 'products_related_per_view_et-desktop', 4 );
		$slider_args['tablet_land']     = 3;
		$slider_args['tablet_portrait'] = $slider_args['mobile'] = get_theme_mod( 'products_related_per_view_et-mobile', 2 );
		
		return $slider_args;
	}
}

if ( ! function_exists( 'etheme_set_upsells_product_args' ) ) {
	/**
	 * Single product upsell product args function.
	 *
	 * @param array - default woocommerce settings of product loop for upsell products
	 *
	 * @return array - settings
	 * @version 1.0.1
	 * @since   1.4.5
	 * @log     added mobile per view slides
	 */
	
	function etheme_set_upsells_product_args( $args ) {
		
		$args['posts_per_page'] = get_theme_mod( 'products_upsell_limit_et-desktop', 7 );
		$args['columns']        = get_theme_mod( 'products_upsell_per_view_et-desktop', 4 );
		$args['columns']        = get_query_var( 'is_mobile', false ) ? get_theme_mod( 'products_upsell_per_view_et-mobile', 2 ) : $args['columns'];
		
		return $args;
	}
}

if ( ! function_exists( 'etheme_set_upsells_product_slider_args' ) ) {
	/**
	 * Single product upsell product args function.
	 *
	 * @param array - default slider args upsell products
	 *
	 * @return array - settings
	 * @version 1.0.2
	 * @since   3.2.2
	 * @log     added mobile per view slides
	 */
	
	function etheme_set_upsells_product_slider_args( $slider_args ) {
		
		$slider_args['large']           = $slider_args['notebook'] = get_theme_mod( 'products_upsell_per_view_et-desktop', 4 );
		$slider_args['tablet_land']     = 3;
		$slider_args['tablet_portrait'] = $slider_args['mobile'] = get_theme_mod( 'products_upsell_per_view_et-mobile', 2 );
		
		return $slider_args;
	}
}

if ( ! function_exists( 'etheme_set_cross_sells_product_args' ) ) {
	/**
	 * Single product cross_sell product args function.
	 *
	 * @param array - default woocommerce settings of product loop for cross_sell products
	 *
	 * @return array - settings
	 * @version 1.0.1
	 * @since   3.1.4
	 * @log     added mobile per view slides
	 */
	
	function etheme_set_cross_sells_product_args( $args ) {
		
		$args['posts_per_page'] = get_theme_mod( 'products_cross_sell_limit_et-desktop', 7 );
		$args['columns']        = get_theme_mod( 'products_cross_sell_per_view_et-desktop', 4 );
		$args['columns']        = get_query_var( 'is_mobile', false ) ? get_theme_mod( 'products_cross_sell_per_view_et-mobile', 2 ) : $args['columns'];
		
		return $args;
	}
}

if ( ! function_exists( 'etheme_set_cross_sells_product_slider_args' ) ) {
	/**
	 * Single product cross sells product args function.
	 *
	 * @param array - default slider args cross sells products
	 *
	 * @return array - settings
	 * @version 1.0.1
	 * @since   3.2.2
	 * @log     added mobile per view slides
	 */
	
	function etheme_set_cross_sells_product_slider_args( $slider_args ) {
		
		$slider_args['large']           = $slider_args['notebook'] = get_theme_mod( 'products_cross_sell_per_view_et-desktop', 4 );
		$slider_args['tablet_land']     = 3;
		$slider_args['tablet_portrait'] = $slider_args['mobile'] = get_theme_mod( 'products_cross_sell_per_view_et-mobile', 2 );
		
		return $slider_args;
	}
}

if ( ! function_exists( 'etheme_product_single_size_guide' ) ) {
	/**
	 * Single product sizing guide function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_size_guide() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-size-guide.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_button' ) ) {
	/**
	 * Single product button function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_button() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/button.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_request_quote' ) ) {
	/**
	 * Single product request quote function.
	 *
	 * @return string
	 * @since   3.2.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_request_quote() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/request-quote.php' );
	}
}

if ( ! function_exists( 'etheme_product_bought_together' ) ) {
	/**
	 * Single product bought together function.
	 *
	 * @return string
	 * @since   4.1.7
	 * @version 1.0.0
	 */
	
	function etheme_product_bought_together() {
		$title = get_theme_mod( 'single_product_bought_together_title' );
		$args  = array(
			'large'  => get_theme_mod( 'single_product_bought_together_per_view_et-desktop', 3 ),
			'mobile' => get_theme_mod( 'single_product_bought_together_per_view_et-mobile', 2 )
		);
		if ( $title ) {
			$args['title'] = $title;
		}
		
		etheme_bought_together( $args );
		// require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/request-quote.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_widget_area_01' ) ) {
	/**
	 * Single product widget area function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_widget_area_01() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-widget-area-1.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_custom_html_01' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_custom_html_01() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-custom-html-01.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_custom_html_02' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_custom_html_02() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-custom-html-02.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_custom_html_03' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_custom_html_03() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-custom-html-03.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_custom_html_04' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   4.3.2
	 * @version 1.0.0
	 */
	
	function etheme_product_single_custom_html_04() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-custom-html-04.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_custom_html_05' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   4.3.2
	 * @version 1.0.0
	 */
	
	function etheme_product_single_custom_html_05() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-custom-html-05.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_additional_custom_block' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   2.2.1
	 * @version 1.0.0
	 */
	
	function etheme_product_single_additional_custom_block() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-single-additional-custom-block.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_product_description' ) ) {
	/**
	 * Single product custom html function.
	 *
	 * @return string
	 * @since   2.2.1
	 * @version 1.0.0
	 */
	
	function etheme_product_single_product_description() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-single-description.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_wishlist' ) ) {
	/**
	 * Single product wishlist function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_product_single_wishlist() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-wishlist.php' );
	}
}

if ( ! function_exists( 'etheme_product_single_compare' ) ) {
	/**
	 * Single product compare function.
	 *
	 * @return string
	 * @since   2.2.4
	 * @version 1.0.0
	 */
	
	function etheme_product_single_compare() {
		require( ET_CORE_DIR . 'app/models/customizer/templates/woocommerce/single-product/etheme-product-compare.php' );
	}
}

if ( ! function_exists( 'etheme_woocommerce_template_woocommerce_breadcrumb' ) ) {
	/**
	 * Single product breadcrumbs function.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_template_woocommerce_breadcrumb() {
		/**
		 * Single product breadcrumbs args
		 * @see single_product_breadcrumb_delimiter()
		 */
		add_filter( 'woocommerce_breadcrumb_stretch', 'etheme_return_single_product_breadcrumbs_width', 1, 10 );
		
		add_filter( 'woocommerce_breadcrumb_delimiter', 'single_product_breadcrumb_delimiter', 1, 10 );
		
		add_filter( 'product_name_single', function () {
			return ( get_theme_mod( 'product_breadcrumbs_mode_et-desktop', 'element' ) != 'element' && get_theme_mod( 'product_breadcrumbs_product_title_et-desktop', 0 ) );
		} );
		
		woocommerce_breadcrumb();
		
		remove_filter( 'woocommerce_breadcrumb_delimiter', 'single_product_breadcrumb_delimiter', 1, 10 );
		
		remove_filter( 'woocommerce_breadcrumb_stretch', 'etheme_return_single_product_breadcrumbs_width', 1, 10 );
	}
}

/**
 * Single product breadcrumbs width function.
 *
 * @return string
 * @since   1.4.5
 * @version 1.0.0
 */
function etheme_return_single_product_breadcrumbs_width() {
	return ( get_theme_mod( 'product_breadcrumbs_mode_et-desktop', 'element' ) != 'element' ) ? get_theme_mod( 'product_breadcrumbs_width_et-desktop', 'full_width' ) : 'default';
}

// filters

if ( ! function_exists( 'single_product_breadcrumb_delimiter' ) ) {
	/**
	 * Single product breadcrumbs settings function.
	 *
	 * @param array - $args of woocommerce single product breadcrumbs
	 *
	 * @return array
	 * @version 1.0.0
	 * @since   1.4.5
	 */
	
	function single_product_breadcrumb_delimiter( $sep ) {
		$options                              = array();
		$options['product_breadcrumbs_type']  = get_theme_mod( 'product_breadcrumbs_type_et-desktop', 'type2' );
		$options['product_breadcrumbs_types'] = array(
			'type1' => '<span class="delimeter" style="vertical-align: middle;"><i style="font-family: auto; font-size: 2em;">&#8226;</i></span>',
			'type2' => '<span class="delimeter"><i class="et-icon et-' . ( ! get_query_var( 'et_is-rtl', false ) ? 'right' : 'left' ) . '-arrow"></i></span>',
			'type3' => '<span class="delimeter" style="vertical-align: middle;"><i style="font-family: auto; font-size: 2em;">&nbsp;&#47;&nbsp;</i></span>',
		);
		
		$separator = $options['product_breadcrumbs_types'][ $options['product_breadcrumbs_type'] ];
		
		unset( $options );
		
		return $separator;
	}
}

function etheme_woocommerce_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
	ob_start();
	etheme_woocommerce_before_add_to_cart_quantity();
	echo $product_quantity;
	etheme_woocommerce_after_add_to_cart_quantity();
	
	return ob_get_clean();
}

if ( ! function_exists( 'etheme_woocommerce_before_add_to_cart_quantity' ) ) {
	/**
	 * Single product quantity wrapper start and minus icon.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_before_add_to_cart_quantity( $style = '' ) {
		$element_option = ( $style != '' ) ? $style : get_theme_mod( 'product_quantity_style_et-desktop', 'simple' );
		$element_option = apply_filters( 'product_quantity_style', $element_option );
		$ghost          = get_query_var( 'et_is_customize_preview', false ) && $element_option == 'none';
		// start of wrapper to make quantity correct showing ?>
        <div class="quantity-wrapper type-<?php echo $element_option; ?>" data-label="<?php echo esc_html__( 'Quantity:', 'xstore-core' ); ?>">
		<?php if ( !in_array($element_option, array('none', 'select')) || $ghost ) : ?>
            <span class="minus et-icon et_b-icon <?php echo ( $ghost ) ? 'none' : ''; ?>">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24">
                    <path d="M23.52 11.4h-23.040c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h23.040c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                </svg>
            </span>
		<?php endif;
		unset( $element_option );
	}
}

if ( ! function_exists( 'etheme_woocommerce_after_add_to_cart_quantity' ) ) {
	/**
	 * Single product quantity wrapper end and plus icon.
	 *
	 * @return string
	 * @since   1.4.5
	 * @version 1.0.0
	 */
	
	function etheme_woocommerce_after_add_to_cart_quantity( $style = '' ) {
		$element_option = ( $style != '' ) ? $style : get_theme_mod( 'product_quantity_style_et-desktop', 'simple' );
		$element_option = apply_filters( 'product_quantity_style', $element_option );
		$ghost          = get_query_var( 'et_is_customize_preview', false ) && $element_option == 'none';
		if ( !in_array($element_option, array('none', 'select')) || $ghost ) : ?>
            <span class="plus et-icon et_b-icon <?php echo ( $ghost ) ? 'none' : ''; ?>">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24">
                    <path d="M23.52 11.4h-10.92v-10.92c0-0.264-0.216-0.48-0.48-0.48h-0.24c-0.264 0-0.48 0.216-0.48 0.48v10.92h-10.92c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h10.92v10.92c0 0.264 0.216 0.48 0.48 0.48h0.24c0.264 0 0.48-0.216 0.48-0.48v-10.92h10.92c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                    </svg>
                </span>
		<?php endif; ?>
        </div>
		<?php // end of wrapper to make quantity correct showing
		unset( $element_option );
	}
}

if ( get_option( 'etheme_single_product_builder', false ) ) {
	
	add_action( 'wp', function () {
		
		if ( apply_filters( 'xstore_theme_amp', false ) ) {
			return;
		}
//		$specific_key_amp = md5(get_site_url( get_current_blog_id(), '/' ));
//		if ( !empty($_SESSION['xstore-amp-'.$specific_key_amp]) && $_SESSION['xstore-amp-'.$specific_key_amp] ) {
//			return false;
//		}
		
		$actions = $filters = array();
		
		$actions['add'] = array(
			array(
				'action'   => 'etheme_woocommerce_template_single_title',
				'function' => 'etheme_woocommerce_template_single_title',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_template_single_rating',
				'function' => 'etheme_woocommerce_template_single_rating',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_template_single_price',
				'function' => 'etheme_woocommerce_template_single_price',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_template_single_excerpt',
				'function' => 'etheme_woocommerce_template_single_excerpt',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_template_single_add_to_cart',
				'function' => 'etheme_woocommerce_template_single_add_to_cart',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_template_single_meta',
				'function' => 'etheme_woocommerce_template_single_meta',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_template_woocommerce_breadcrumb',
				'function' => 'etheme_woocommerce_template_woocommerce_breadcrumb',
				'priority' => 10
			),
			
			array(
				'action'   => 'woocommerce_share',
				'function' => 'etheme_product_single_sharing',
				'priority' => 20
			),
			array(
				'action'   => 'etheme_woocommerce_template_single_sharing',
				'function' => 'etheme_woocommerce_template_single_sharing',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_show_product_images',
				'function' => 'etheme_woocommerce_show_product_images',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_output_product_data_tabs',
				'function' => 'etheme_woocommerce_output_product_data_tabs',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_output_upsell_products',
				'function' => 'etheme_woocommerce_output_upsell_products',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_output_cross_sells_products',
				'function' => 'etheme_woocommerce_output_cross_sells_products',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_woocommerce_output_related_products',
				'function' => 'etheme_woocommerce_output_related_products',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_size_guide',
				'function' => 'etheme_product_single_size_guide',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_button',
				'function' => 'etheme_product_single_button',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_request_quote',
				'function' => 'etheme_product_single_request_quote',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_bought_together',
				'function' => 'etheme_product_bought_together',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_widget_area_01',
				'function' => 'etheme_product_single_widget_area_01',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_custom_html_01',
				'function' => 'etheme_product_single_custom_html_01',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_custom_html_02',
				'function' => 'etheme_product_single_custom_html_02',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_custom_html_03',
				'function' => 'etheme_product_single_custom_html_03',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_custom_html_04',
				'function' => 'etheme_product_single_custom_html_04',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_custom_html_05',
				'function' => 'etheme_product_single_custom_html_05',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_additional_custom_block',
				'function' => 'etheme_product_single_additional_custom_block',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_product_description',
				'function' => 'etheme_product_single_product_description',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_wishlist',
				'function' => 'etheme_product_single_wishlist',
				'priority' => 10
			),
			array(
				'action'   => 'etheme_product_single_compare',
				'function' => 'etheme_product_single_compare',
				'priority' => 10
			),
		);
		
		if ( function_exists( 'ywdpd_is_true' ) && class_exists( 'YITH_WC_Dynamic_Pricing' ) ) {
			if ( class_exists( 'YITH_WC_Dynamic_Pricing_Frontend' ) ) {
				$position = YITH_WC_Dynamic_Pricing()->get_option( 'show_quantity_table_place' );
				
				$action = '';
				switch ( $position ) {
					case 'before_add_to_cart':
						$action   = 'etheme_woocommerce_template_single_add_to_cart';
						$priority = 9;
						break;
					case 'after_add_to_cart':
						$action   = 'etheme_woocommerce_template_single_add_to_cart';
						$priority = 11;
						break;
					case 'before_excerpt':
						$action   = 'etheme_woocommerce_template_single_excerpt';
						$priority = 9;
						break;
					case 'after_excerpt':
						$action   = 'etheme_woocommerce_template_single_excerpt';
						$priority = 11;
						break;
					case 'after_meta':
						$action   = 'etheme_woocommerce_template_single_meta';
						$priority = 11;
						break;
					default:
						break;
				}
				
				if ( $action ) {
					$actions['add'][] = array(
						'action'   => $action,
						'function' => 'etheme_product_single_yith_wc_dynamic_pricing_show_table_quantity',
						'priority' => $priority
					);
				}
				
			}
		}
		
		if ( class_exists( 'YITH_WCBK_Frontend' ) ) {
			$booking_form_position = get_option( 'yith-wcbk-booking-form-position', 'default' );
			$instance              = YITH_WCBK_Frontend::get_instance();
			switch ( $booking_form_position ) {
				case 'before_summary':
				case 'after_summary':
					add_action( 'etheme_woocommerce_template_single_add_to_cart', array(
						$instance,
						'print_add_to_cart_template'
					) );
					break;
				case 'after_title':
					add_action( 'etheme_woocommerce_template_single_add_to_cart', array(
						$instance,
						'print_add_to_cart_template'
					), 7 );
					break;
				case 'before_description':
					add_action( 'etheme_woocommerce_template_single_add_to_cart', array(
						$instance,
						'print_add_to_cart_template'
					), 15 );
					break;
				case 'after_description':
					add_action( 'etheme_woocommerce_template_single_add_to_cart', array(
						$instance,
						'print_add_to_cart_template'
					), 25 );
					break;
			}
		}
		
		if ( function_exists( 'get_query_var' ) && ! get_query_var( 'etheme_shop_archive_product_variation_gallery', false ) ) {
			if ( function_exists( 'remove_et_variation_gallery_filter' ) ) {
				add_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
			}
			if ( function_exists( 'add_et_variation_gallery_filter' ) ) {
				add_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
			}
		}
		
		foreach ( $actions['add'] as $key ) {
			add_action( $key['action'], $key['function'], $key['priority'] );
		}
		
		add_action( 'connect_block', 'etheme_connect_block_product_single', 10, 1 );
		
		unset( $actions );
		unset( $filters );
		
	}, 20 );
	
	function etheme_connect_block_product_single( $blockID ) {
//		add_filter( 'et_connect_block_id', function ( $id ) use ( $blockID ) {
//			return $blockID;
//		} );
		require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/connect_block.php' );
		// get_template_part( 'template-parts/header/parts/connect_block' );
	}
}

/**
 * YITH_WC_Dynamic_Pricing_Frontend table .
 *
 * Show YITH_WC_Dynamic_Pricing_Frontend table on single product .
 *
 * @return  {html} [yith_ywdpd_quantity_table]
 *
 * @version 1.0.0
 * @since   2.3.3
 */
if ( ! function_exists( 'etheme_product_single_yith_wc_dynamic_pricing_show_table_quantity' ) ) {
	function etheme_product_single_yith_wc_dynamic_pricing_show_table_quantity() {
		if ( class_exists( 'YITH_WC_Dynamic_Pricing_Frontend' ) ) {
			echo do_shortcode( '[yith_ywdpd_quantity_table]' );
		}
	}
}

if ( ! function_exists( 'etheme_request_quote' ) ) {
	function etheme_request_quote() {
		$element_options                                          = array();
		$element_options['xstore_sales_booster_settings']         = (array) get_option( 'xstore_sales_booster_settings', array() );
		$element_options['xstore_sales_booster_settings_default'] = array(
			'request_quote'                  => get_option( 'xstore_sales_booster_settings_request_quote', false ),
			'show_all_pages'                 => false,
			'show_as_button'                 => false,
			'icon'                           => false,
			'label'                          => esc_html__( 'Ask an expert', 'xstore-core' ),
			'popup_content'                  => '',
			'popup_dimensions_custom_width'  => '',
			'popup_dimensions_custom_height' => '',
			'popup_background_color'         => '#fff',
			'popup_background_image'         => '',
			'popup_background_repeat'        => 'no-repeat',
			'popup_background_position'      => 'center center',
			'popup_background_size'          => 'cover',
			'popup_color'                    => '#000'
		);
		
		$is_product = class_exists( 'WooCommerce' ) && is_product();
		
		$element_options['request_quote_settings'] = $element_options['xstore_sales_booster_settings_default'];
		
		if ( ! $element_options['request_quote_settings']['request_quote'] ) {
			return;
		}
		
		global $et_request_quote_icons;
		
		if ( count( $element_options['xstore_sales_booster_settings'] ) && isset( $element_options['xstore_sales_booster_settings']['request_quote'] ) ) {
			$element_options['xstore_sales_booster_settings'] = wp_parse_args( $element_options['xstore_sales_booster_settings']['request_quote'],
				$element_options['xstore_sales_booster_settings_default'] );
			
			$element_options['request_quote_settings'] = $element_options['xstore_sales_booster_settings'];
		}
		
		if ( ! $element_options['request_quote_settings']['show_all_pages'] ) {
			if ( ! $is_product ) {
				return;
			}
		}
		
		$element_options['request_quote_settings']['show_as_button'] = $element_options['request_quote_settings']['show_as_button'] && $is_product;
		
		if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
			$element_options['ask_icons'] = $et_request_quote_icons['light'];
		} else {
			$element_options['ask_icons'] = $et_request_quote_icons['bold'];
		}
		
		$element_options['ask_icon'] = $element_options['ask_icons']['type1'];
		
		if ( $element_options['request_quote_settings']['icon'] != '' ) {
			$element_options['ask_icon'] = '<img src="' . $element_options['request_quote_settings']['icon'] . '" alt="' . esc_attr( 'Request a quote', 'xstore-core' ) . '">';
		}
		
		$element_options['output_css'] = array();
		
		if ( $element_options['request_quote_settings']['popup_dimensions_custom_width'] != '' ) {
			$element_options['output_css'][] = 'width:' . $element_options['request_quote_settings']['popup_dimensions_custom_width'];
		}
		
		if ( $element_options['request_quote_settings']['popup_dimensions_custom_height'] != '' ) {
			$element_options['output_css'][] = 'height:' . $element_options['request_quote_settings']['popup_dimensions_custom_height'];
		}
		
		if ( $element_options['request_quote_settings']['popup_background_color'] != '' ) {
			$element_options['output_css'][] = 'background-color:' . $element_options['request_quote_settings']['popup_background_color'];
		}
		
		if ( $element_options['request_quote_settings']['popup_background_image'] != '' ) {
			$element_options['output_css'][] = 'background-image: url("' . $element_options['request_quote_settings']['popup_background_image'] . '")';
			if ( $element_options['request_quote_settings']['popup_background_repeat'] != '' ) {
				$element_options['output_css'][] = 'background-repeat:' . $element_options['request_quote_settings']['popup_background_repeat'];
			}
			
			if ( $element_options['request_quote_settings']['popup_background_position'] != '' ) {
				$element_options['output_css'][] = 'background-position:' . $element_options['request_quote_settings']['popup_background_position'];
			}
			
			if ( $element_options['request_quote_settings']['popup_background_size'] != '' ) {
				$element_options['output_css'][] = 'background-size:' . $element_options['request_quote_settings']['popup_background_size'];
			}
		}
		
		if ( $element_options['request_quote_settings']['popup_color'] != '' ) {
			$element_options['output_css'][] = 'color:' . $element_options['request_quote_settings']['popup_color'];
		}
		
		wp_enqueue_script( 'call_popup' );
		if ( function_exists( 'etheme_enqueue_style' ) ) {
			etheme_enqueue_style( 'single-product-request-quote' );
		}
		
		ob_start();
		
		?>
		
		<?php if ( $element_options['request_quote_settings']['show_as_button'] ) : ?>
            <div class="single-product-request-quote-wrapper flex justify-content-start mob-justify-content-start">
		<?php endif; ?>
        <div class="et-request-quote-popup et-called-popup" data-type="single-product-quote">
            <div class="et-popup">
                <div class="et-popup-content  <?php echo( isset( $element_options['request_quote_settings']['popup_dimensions'] ) && $element_options['request_quote_settings']['popup_dimensions'] == 'custom' ? 'et-popup-content-custom-dimenstions' : '' ); ?> with-static-block">
                        <span class="et-close-popup et-toggle pos-fixed full-left top"
                              style="margin-<?php echo is_rtl() ? 'right' : 'left'; ?>: 5px;">
                          <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                          </svg>
                        </span>
					<?php if ( $element_options['request_quote_settings']['popup_content'] != '' ) {
						echo do_shortcode( $element_options['request_quote_settings']['popup_content'] );
					} else { ?>
                        <h2><?php esc_html_e( 'You may add any content here from XStore Control Panel->Sales booster->Request a quote->Ask a question notification', 'xstore-core' ); ?></h2>
                        <p>At sem a enim eu vulputate nullam convallis Iaculis vitae odio faucibus adipiscing urna.</p>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php if ( $element_options['request_quote_settings']['show_as_button'] ) : ?>
            <span class="inline-block pos-relative et-call-popup btn black medium" data-type="single-product-quote">
            <?php if ( $element_options['ask_icon'] != '' ) {
	            echo '<span class="et_b-icon">' . $element_options['ask_icon'] . '</span>';
            } ?>
            <?php if ( $element_options['request_quote_settings']['label'] != '' ) {
	            echo '<span>' . esc_html( $element_options['request_quote_settings']['label'] ) . '</span>';
            } ?>
        </span>
		<?php else: ?>
            <span class="et-request-quote et-call-popup inactive mtips mtips-left"
                  data-type="single-product-quote">
                <?php echo '<span class="et_b-icon">' . $element_options['ask_icon'] . '</span>'; ?>
                <span class="mt-mes">
                    <?php echo $element_options['request_quote_settings']['label']; ?>
                </span>
            </span>
		<?php endif; ?>
		<?php if ( $element_options['request_quote_settings']['show_as_button'] ) : ?>
            </div>
		<?php endif; ?>
		<?php if ( count( $element_options['output_css'] ) ) : ?>
            <style>
                .et-request-quote-popup .et-popup-content {
                <?php echo implode(';', $element_options['output_css']); ?>
                }
            </style>
		<?php endif; ?>
		<?php echo ob_get_clean();
	}
}

add_action( 'wp', function () {
	if ( get_option( 'xstore_sales_booster_settings_request_quote', false ) ) {
		$options        = (array) get_option( 'xstore_sales_booster_settings', array() );
		$show_as_button = false;
		if ( count( $options ) && isset( $options['request_quote'] ) ) {
			$show_as_button = isset( $options['request_quote']['show_as_button'] ) &&
			                  $options['request_quote']['show_as_button']
			                  && class_exists( 'WooCommerce' ) && is_product();
		}
		
		if ( ! $show_as_button ) {
			add_action( 'after_page_wrapper', 'etheme_request_quote', 50 );
		} else {
			add_action( 'woocommerce_single_product_summary', 'etheme_request_quote', 30 );
		}
	}
} );

add_action( 'after_page_wrapper', 'etheme_sticky_add_to_cart', 1 );

/**
 * Sticky add to cart.
 *
 * Show add to cart with current img and price.
 *
 * @return  {html} sticky cart content
 *
 * @version 1.0.0
 * @since   1.4.5
 */

if ( ! function_exists( 'etheme_sticky_add_to_cart' ) ) {
	function etheme_sticky_add_to_cart() {
		if ( ! get_query_var( 'is_single_product', false ) ) {
			return;
		}
		$element_option = get_theme_mod( 'sticky_add_to_cart_et-desktop', 0 );
		$ghost          = get_query_var( 'et_is_customize_preview', false ) && ! $element_option;
		$is_mobile      = get_query_var( 'is_mobile', false );
		if ( ! $element_option && ! $ghost ) {
			return;
		}
		do_action('etheme_sticky_add_to_cart_before');
		
		if ( function_exists( 'etheme_enqueue_style' ) ) {
			etheme_enqueue_style( 'single-product-sticky-cart' );
		}
		
		$advanced_stock = get_query_var( 'et_product-advanced-stock', false );
		if ( $advanced_stock ) {
			remove_filter( 'woocommerce_get_stock_html', 'etheme_advanced_stock_status_html', 2, 10 );
		}
		
		$with_buy_now      = $is_mobile && has_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn' );
		$with_buy_now      = apply_filters( 'etheme_sticky_add_to_cart_buy_now_hide_mobile', $with_buy_now );
		$with_price_mobile = apply_filters( 'etheme_sticky_add_to_cart_price_hide_mobile', $is_mobile );
//		remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
//		remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 ); // test
//		remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
		add_action( 'woocommerce_grouped_add_to_cart', 'etheme_custom_variable_add_to_cart', 30 );
		add_action( 'woocommerce_variable_add_to_cart', 'etheme_custom_variable_add_to_cart', 30 ); // test
//		add_action( 'woocommerce_external_add_to_cart', 'etheme_custom_add_to_cart', 30 );
		remove_action( 'woocommerce_before_add_to_cart_button', 'etheme_show_single_stock', 10 );
		if ( $with_buy_now ) {
			remove_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
		} ?>
        <div class="etheme-sticky-cart etheme-sticky-panel outside flex align-items-center container-width-inherit <?php echo ( $ghost ) ? ' dt-hide mob-hide' : ''; ?>">
            <div class="et-row-container et-container pos-relative">
                <div class="et-wrap-columns flex align-items-center">
                    <div class="et_column et_col-xs-5 flex-inline align-items-center mob-hide">
						<?php
						if ( has_post_thumbnail() ) { ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
                               class="flex-inline"><?php the_post_thumbnail(); ?></a> <?php
						} else {
							?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
                               class="flex-inline"><?php echo wc_placeholder_img(); ?></a> <?php
						}
						?>
                        <span class="sticky_product_title">
                                <?php
                                $origin_title = get_the_title();
                                if ( function_exists( 'unicode_chars' ) ) {
	                                $product_title = unicode_chars( $origin_title );
	                                $title_limit   = 30;
	
	                                if ( $title_limit && strlen( $product_title ) > $title_limit ) {
		                                $split         = preg_split( '/(?<!^)(?!$)/u', $product_title );
		                                $product_title = ( $title_limit != '' && $title_limit > 0 && ( count( $split ) >= $title_limit ) ) ? '' : $product_title;
		                                if ( $product_title == '' ) {
			                                for ( $i = 0; $i < $title_limit; $i ++ ) {
				                                $product_title .= $split[ $i ];
			                                }
			                                $product_title .= '...';
		                                }
	                                }
                                } else {
	                                $product_title = $origin_title;
                                }
                                echo apply_filters( 'etheme_sticky_cart_title', esc_attr( $product_title ), $origin_title ); ?>
                            </span>
						<?php
						?>
                    </div>
                    <div class="pos-static et_column et_col-xs-7 flex-inline align-items-center justify-content-end mob-full-width mob-justify-content-center">
						<?php
						if ( ! $with_price_mobile ) {
							woocommerce_template_single_price();
						}
						
						if ( ! function_exists( 'etheme_is_catalog' ) || ! etheme_is_catalog() ) {
							if ( $is_mobile ) {
								add_filter( 'woocommerce_get_stock_html', 'etheme_return_false' );
							}
							etheme_woocommerce_template_single_add_to_cart();
							if ( $is_mobile ) {
								remove_filter( 'woocommerce_get_stock_html', 'etheme_return_false' );
							}
						} ?>
                    </div>
                </div>
            </div>
        </div>
		<?php
		if ( $with_buy_now ) {
			add_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
		}
		add_action( 'woocommerce_before_add_to_cart_button', 'etheme_show_single_stock', 10 );
		remove_action( 'woocommerce_grouped_add_to_cart', 'etheme_custom_variable_add_to_cart', 30 );
		remove_action( 'woocommerce_variable_add_to_cart', 'etheme_custom_variable_add_to_cart', 30 );
//		remove_action( 'woocommerce_external_add_to_cart', 'etheme_custom_add_to_cart', 30 );
//		add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
//		add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
//		add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
		
		if ( $advanced_stock ) {
			add_filter( 'woocommerce_get_stock_html', 'etheme_advanced_stock_status_html', 2, 10 );
		}
		
		add_action( 'woocommerce_before_add_to_cart_button', 'etheme_show_single_stock', 10 );

        do_action('etheme_sticky_add_to_cart_after');
		unset( $element_option );
	}
}

/**
 * Sticky add to cart button.
 *
 * Element for trigger scroll to real cart form - uses for not simple products.
 *
 * @return  {html} sticky add to cart button
 *
 * @version 1.0.0
 * @since   1.4.5
 */

if ( ! function_exists( 'etheme_custom_add_to_cart' ) ) {
	
	function etheme_custom_add_to_cart() {
		?>
        <div class="etheme_custom_add_to_cart button single_add_to_cart_button pointer"><?php esc_html_e( 'Buy now', 'xstore-core' ); ?></div>
		<?php
	}
	
}

/**
 * Sticky add to cart button.
 *
 * Element for trigger scroll to real cart form - uses for not simple products.
 *
 * @return  {html} sticky add to cart button
 *
 * @version 1.0.0
 * @since   1.4.5
 */

if ( ! function_exists( 'etheme_custom_variable_add_to_cart' ) ) {
	
	function etheme_custom_variable_add_to_cart() {
		global $product;
		if ( ! $product->is_in_stock() ) {
			etheme_custom_add_to_cart();
			
			return;
		}
		?>
        <div class="etheme_custom_add_to_cart_toggle button single_add_to_cart_button pointer"><?php echo esc_html( $product->add_to_cart_text() ); ?></div>
		<?php
	}
	
}

/**
 * Custom product tabs.
 *
 * Function to add custom tabs inside defalt woocommerce tabs and sort them.
 *
 * @param   {array} woocommerce tabs
 *
 * @return  {array} - sorted and added/removed tabs
 * @since   1.4.5
 * @version 1.0.1
 */

if ( ! function_exists( 'etheme_single_product_custom_tabs' ) ) {
	function etheme_single_product_custom_tabs( $tabs ) {
		
		$element_options                                     = array();
		$element_options['product_tabs_sortable']            = get_theme_mod( 'product_tabs_sortable',
			array(
				'description',
				'additional_information',
				'reviews',
				'et_custom_tab_01',
				'et_custom_tab_02',
				'single_custom_tab_01',
			)
		);
		$element_options['product_tabs_custom_tab_01_title'] = get_theme_mod( 'product_tabs_custom_tab_01_title_et-desktop', get_theme_mod( 'custom_tab_title', '' ) );
		$element_options['product_tabs_custom_tab_02_title'] = get_theme_mod( 'product_tabs_custom_tab_02_title_et-desktop', '' );
		// single product custom tab
		$element_options['custom_tab']         = ( function_exists( 'etheme_get_custom_field' ) ) ? etheme_get_custom_field( 'custom_tab1_title' ) : false;
		$element_options['custom_tab_content'] = ( function_exists( 'etheme_get_custom_field' ) ) ? etheme_get_custom_field( 'custom_tab1' ) : false;
		
		if ( ! in_array( 'description', $element_options['product_tabs_sortable'] ) ) {
			unset( $tabs['description'] );
		}
		if ( ! in_array( 'additional_information', $element_options['product_tabs_sortable'] ) ) {
			unset( $tabs['additional_information'] );
		}
		if ( ! in_array( 'reviews', $element_options['product_tabs_sortable'] ) ) {
			unset( $tabs['reviews'] );
		}
		
		// Adds the new tab
		if ( in_array( 'et_custom_tab_01', $element_options['product_tabs_sortable'] ) && $element_options['product_tabs_custom_tab_01_title'] ) {
			
			$tabs['et_custom_tab_01'] = array(
				'title'    => $element_options['product_tabs_custom_tab_01_title'],
				'priority' => 40,
				'callback' => 'etheme_single_product_custom_tab_01_content'
			);
		}
		
		if ( in_array( 'et_custom_tab_02', $element_options['product_tabs_sortable'] ) && $element_options['product_tabs_custom_tab_02_title'] ) {
			$tabs['et_custom_tab_02'] = array(
				'title'    => $element_options['product_tabs_custom_tab_02_title'],
				'priority' => 50,
				'callback' => 'etheme_single_product_custom_tab_02_content'
			);
		}
		
		if ( in_array( 'single_custom_tab_01', $element_options['product_tabs_sortable'] ) && $element_options['custom_tab'] ) {
			$custom_content               = $element_options['custom_tab_content'];
			$tabs['single_custom_tab_01'] = array(
				'title'    => $element_options['custom_tab'],
				'priority' => 60,
				'callback' => function ( $custom ) use ( $custom_content ) {
					echo do_shortcode( $custom_content );
				}
			);
		}
		
		$priority      = 10;
		$tabs_filtered = array();
		foreach ( (array) $element_options['product_tabs_sortable'] as $key ) {
			if ( ! isset( $tabs[ $key ] ) ) {
				continue;
			}
			$tabs[ $key ]['priority'] = $priority;
			$tabs_filtered[ $key ]    = $tabs[ $key ];
			$priority                 += 10;
		}
		
		unset( $custom_content );
		unset( $element_options );
		
		$tabs = is_array( $tabs ) ? $tabs : array(); // in some cases $tabs return null
		
		return apply_filters( 'etheme_single_product_builder_tabs', array_merge( $tabs_filtered, $tabs ) );
		
	}
}


/**
 * Custom tab 01 content.
 *
 * Custom tab 01 content for woocommerce custom tabs.
 *
 * @return Custom tab content.
 * @see   { etheme_single_product_custom_tabs } function
 * @uses  { etheme_single_product_custom_tabs } function
 *
 * @since 1.4.5
 *
 */
if ( ! function_exists( 'etheme_single_product_custom_tab_01_content' ) ) {
	
	function etheme_single_product_custom_tab_01_content() {
		
		$element_options                                                  = array();
		$element_options['product_tabs_custom_tab_01_content_et-desktop'] = get_theme_mod( 'product_tabs_custom_tab_01_content_et-desktop', get_theme_mod( 'custom_tab' ) );
		$element_options['product_tabs_custom_tab_01_section_et-desktop'] = ( get_theme_mod( 'product_tabs_custom_tab_01_sections_et-desktop', 0 ) ) ? get_theme_mod( 'product_tabs_custom_tab_01_section_et-desktop', '' ) : '';
		$element_options['product_tabs_custom_tab_01_content_et-desktop'] = ( $element_options['product_tabs_custom_tab_01_section_et-desktop'] != '' && $element_options['product_tabs_custom_tab_01_section_et-desktop'] > 0 ) ? $element_options['product_tabs_custom_tab_01_section_et-desktop'] : $element_options['product_tabs_custom_tab_01_content_et-desktop'];
		
		if ( $element_options['product_tabs_custom_tab_01_section_et-desktop'] != '' && function_exists( 'etheme_static_block' ) ) {
			$element_options['section_css'] = get_post_meta( $element_options['product_tabs_custom_tab_01_section_et-desktop'], '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $element_options['section_css'] ) ) {
				echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				echo strip_tags( $element_options['section_css'] );
				echo '</style>';
			}
			
			etheme_static_block( $element_options['product_tabs_custom_tab_01_section_et-desktop'], true );
			
		} else {
			echo do_shortcode( $element_options['product_tabs_custom_tab_01_content_et-desktop'] );
		}
		
	}
}

/**
 * Custom tab 02 content.
 *
 * Custom tab 02 content for woocommerce custom tabs.
 *
 * @return Custom tab content.
 * @see   { etheme_single_product_custom_tabs } function
 * @uses  { etheme_single_product_custom_tabs } function
 *
 * @since 1.4.5
 *
 */
if ( ! function_exists( 'etheme_single_product_custom_tab_02_content' ) ) {
	
	function etheme_single_product_custom_tab_02_content() {
		
		$element_options                                                  = array();
		$element_options['product_tabs_custom_tab_02_content_et-desktop'] = get_theme_mod( 'product_tabs_custom_tab_02_content_et-desktop', '' );
		$element_options['product_tabs_custom_tab_02_section_et-desktop'] = ( get_theme_mod( 'product_tabs_custom_tab_02_sections_et-desktop', 0 ) ) ? get_theme_mod( 'product_tabs_custom_tab_02_section_et-desktop', '' ) : '';
		$element_options['product_tabs_custom_tab_02_content_et-desktop'] = ( $element_options['product_tabs_custom_tab_02_section_et-desktop'] != '' && $element_options['product_tabs_custom_tab_02_section_et-desktop'] > 0 ) ? $element_options['product_tabs_custom_tab_02_section_et-desktop'] : $element_options['product_tabs_custom_tab_02_content_et-desktop'];
		
		if ( $element_options['product_tabs_custom_tab_02_section_et-desktop'] != '' && function_exists( 'etheme_static_block' ) ) {
			$element_options['section_css'] = get_post_meta( $element_options['product_tabs_custom_tab_02_section_et-desktop'], '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $element_options['section_css'] ) ) {
				echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				echo strip_tags( $element_options['section_css'] );
				echo '</style>';
			}
			
			etheme_static_block( $element_options['product_tabs_custom_tab_02_section_et-desktop'], true );
			
		} else {
			echo do_shortcode( $element_options['product_tabs_custom_tab_02_content_et-desktop'] );
		}
		
	}
}

if ( ! function_exists( 'etheme_yith_wcwl_add_to_wishlist_icon_html' ) ) {
	function etheme_yith_wcwl_add_to_wishlist_icon_html( $icon_html = '', $atts = array() ) {
		global $et_wishlist_icons;
		
		$element_options = array();
		
		$element_options['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
		
		$element_options['icon_type'] = function_exists( 'etheme_get_option' ) ? etheme_get_option( 'product_wishlist_icon_et-desktop', 'type1' ) : 'type2';
		
		switch ( $element_options['icon_type'] ) {
			case 'type1':
				$icon_html = 'et-icon et_b-icon et-heart';
				break;
			case 'type2':
				$icon_html = 'et-icon et_b-icon et-star';
				break;
			
			default:
				$icon_html = '';
				break;
		}
		
		$element_options['ghost_icon'] = empty( $icon_html ) && $element_options['is_customize_preview'];
		if ( ! empty( $icon_html ) || $element_options['ghost_icon'] ) {
			$icon_html = (empty($icon_html) ? 'et-icon et_b-icon ' : $icon_html) . ' ' . ( $element_options['ghost_icon'] ? ' none' : '' );
		}
		
		$icon_html = ( ! empty( $icon_html ) ) ? '<i class="' . $icon_html . '"></i>' : '';
		
		return $icon_html;
	}
}

/**
 * Single wishlist shortcode filter function.
 *
 * @param array - params of wishlist shorcode
 *
 * @return array
 * @since   1.4.5
 * @version 1.0.1
 * @see     templates/woocommerce/single-product/etheme-product-wishlist
 */
if ( ! function_exists( 'etheme_yith_wcwl_add_to_wishlist_params' ) ) {
	function etheme_yith_wcwl_add_to_wishlist_params( $params ) {
		
		$element_options = array();
		
		$element_options['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
		
		// fix for Yith wishlist version 3.12.0 icon cannot be empty/false so we keep the icon that will be from options
		$params['icon']                      = etheme_yith_wcwl_add_to_wishlist_icon_html( $params['icon'], $params );
		$params['label']                     = $params['browse_wishlist_text'] = '';
		$params['already_in_wishslist_text'] = $params['product_added_text'] = false;
		
		// if ( !get_theme_mod('bold_icons') ) {
		//     $element_options['wishlist_icons'] = $et_wishlist_icons['light'];
		// }
		// else {
		//     $element_options['wishlist_icons'] = $et_wishlist_icons['bold'];
		// }
		
		// $element_options['wishlist_icons']['custom'] = get_theme_mod( 'wishlist_icon_custom_et-desktop' );
		
		// $element_options['wishlist_icon'] = $element_options['wishlist_icons'][$element_options['icon_type']];
		
		$tips_class = '';
//		if ( function_exists('etheme_get_option') && etheme_get_option( 'product_wishlist_label_type_et-desktop' ) == 'tooltip' ) {
//			$tips_class = 'mt-mes';
//		}
		// $params['label'] = $element_options['wishlist_icon'] . '<span class="'.$tips_class.'">' . etheme_get_option('product_wishlist_label_add_to_wishlist') . '</span>';
		// $params['label'] = '<i class="et-icon et_b-icon ' . $element_options['wishlist_icon'] . '"></i>' . '<span class="'.$tips_class.'">' . etheme_get_option('product_wishlist_label_add_to_wishlist') . '</span>';
		// $params['browse_wishlist_text'] = $element_options['wishlist_icon'] . '<span class="'.$tips_class.'">' . etheme_get_option('product_wishlist_label_browse_wishlist') . '</span>';
		// $params['icon'] = $element_options['wishlist_icon'];
		$params['label']                = '<span class="' . $tips_class . '">' . ( get_theme_mod( 'product_wishlist_label_add_to_wishlist', esc_html__( 'Add to wishlist', 'xstore-core' ) ) ) . '</span>';
		$params['browse_wishlist_text'] = '<span class="' . $tips_class . '">' . ( get_theme_mod( 'product_wishlist_label_browse_wishlist', esc_html__( 'Browse wishlist', 'xstore-core' ) ) ) . '</span>';
		
		return $params;
	}
}

if ( ! function_exists( 'etheme_all_departments_limit_objects' ) ) {
	function etheme_all_departments_limit_objects( $items, $args ) {
		if ( ! get_theme_mod( 'secondary_menu_more_items_link', 0 ) ) {
			return $items;
		}
		$limit     = (int) get_theme_mod( 'secondary_menu_more_items_link_after', 10 );
		$toplinks  = 0;
		$max_count = count( $items );
		foreach ( $items as $k => $v ) {
			if ( $v->menu_item_parent == 0 ) {
				$toplinks ++;
				if ( $toplinks > $limit ) {
//			    unset($items[$k]);
					$items[ $k ]->classes[] = 'hidden';
				}
			}
		}
		
		return $items;
	}
}

if ( ! function_exists( 'etheme_all_departments_limit_items' ) ) {
	function etheme_all_departments_limit_items( $items, $args ) {
		if ( ! get_theme_mod( 'secondary_menu_more_items_link', 0 ) ) {
			return $items;
		}
		$show_less = get_theme_mod( 'secondary_menu_less_items_link', 0 );
		
		return $items . '<li class="menu-item show-more"' . ( $show_less ? ' data-reverse="true"' : '' ) . '><a>' . esc_html__( 'Show more', 'xstore-core' ) . '<i class="et-icon et-down-arrow"></i></a>' .
		       ( $show_less ? '<a>' . esc_html__( 'Show less', 'xstore-core' ) . '<i class="et-icon et-up-arrow"></i></a>' : '' ) . '</li>';
	}
}

/**
 * Actions and filters.
 * add/remove filter for header builder and single product builder
 *
 * @since 1.4.0
 */
add_action( 'wp', 'etheme_core_hooks', 70 );
if ( ! function_exists( 'etheme_core_hooks' ) ) {
	function etheme_core_hooks() {
		if ( get_option( 'etheme_single_product_builder', false ) ) {
			
			if ( apply_filters( 'xstore_theme_amp', false ) ) {
				return;
			}

//			if ( class_exists( 'WooCommerce' ) && is_product() ) {
			if ( get_query_var( 'is_single_product', false ) ) {
//
				$product_reviews = ! in_array( 'reviews', (array) get_theme_mod( 'product_tabs_sortable', array(
						'description',
						'additional_information',
						'reviews',
						'et_custom_tab_01',
						'et_custom_tab_02',
						'single_custom_tab_01'
					) ) ) && get_theme_mod( 'product_reviews_et-desktop', 0 );
				if ( $product_reviews && post_type_supports( 'product', 'comments' ) ) {
					// maybe replace with origin wp_is_mobile()
					if ( get_theme_mod( 'product_reviews_collapsed_et-mobile', false ) && get_query_var( 'is_mobile', false ) && function_exists( 'etheme_comments_template_mobile_button' ) ) {
						add_action( 'etheme_woocommerce_output_product_data_tabs', 'etheme_comments_template_mobile_button', 25 );
					}
					add_action( 'etheme_woocommerce_output_product_data_tabs', 'comments_template', 30 );
				}
				
				// related products
				add_filter( 'related_columns', function ( $columns ) {
					return get_theme_mod( 'products_related_per_view_et-desktop', 4 );
				} );
				
				add_filter( 'related_limit', function ( $limit ) {
					return get_theme_mod( 'products_related_limit_et-desktop', 7 );
				} );
				
				add_filter( 'related_type', function ( $type ) {
					return get_theme_mod( 'products_related_type_et-desktop', 'slider' );
				} );
				
				add_filter( 'related_cols_gap', function ( $cols_gap ) {
					return get_theme_mod( 'products_related_cols_gap_et-desktop', 15 );
				} );
				
				// upsell products
				
				add_filter( 'upsell_columns', function ( $columns ) {
					return get_theme_mod( 'products_upsell_per_view_et-desktop', 4 );
				} );
				
				add_filter( 'upsell_limit', function ( $limit ) {
					return get_theme_mod( 'products_upsell_limit_et-desktop', 7 );
				} );
				
				add_filter( 'upsell_type', function ( $type ) {
					return get_theme_mod( 'products_upsell_type_et-desktop', 'slider' );
				} );
				
				add_filter( 'upsell_cols_gap', function ( $cols_gap ) {
					return get_theme_mod( 'products_upsell_cols_gap_et-desktop', 15 );
				} );
				
				// cross_sell products
				
				add_filter( 'cross_sell_columns', function ( $columns ) {
					return get_theme_mod( 'products_cross_sell_per_view_et-desktop', 4 );
				} );
				
				add_filter( 'cross_sell_limit', function ( $limit ) {
					return get_theme_mod( 'products_cross_sell_limit_et-desktop', 7 );
				} );
				
				add_filter( 'cross_sell_type', function ( $type ) {
					return get_theme_mod( 'products_cross_sell_type_et-desktop', 'slider' );
				} );
				
				add_filter( 'cross_sell_cols_gap', function ( $cols_gap ) {
					return get_theme_mod( 'products_cross_sell_cols_gap_et-desktop', 15 );
				} );
				
				add_filter( 'woocommerce_product_tabs', 'etheme_single_product_custom_tabs', 98 );
				
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
				add_action( 'woocommerce_before_main_content', 'etheme_woocommerce_template_woocommerce_breadcrumb', 20 );
				if ( get_theme_mod( 'product_breadcrumbs_mode_et-desktop', 'element' ) == 'element' ) {
					remove_action( 'woocommerce_before_main_content', 'etheme_woocommerce_template_woocommerce_breadcrumb', 20 );
				}
				
				add_filter( 'return_to_previous', function () {
					return ( get_theme_mod( 'product_breadcrumbs_return_to_previous_et-desktop', 0 ) );
				} );
				
				add_filter( 'breadcrumb_params', function ( $params ) {
					$type = get_theme_mod( 'product_breadcrumbs_style_et-desktop', 'left2' );
					if ( $type != 'inherit' ) {
						$params['type'] = $type;
					}
					
					return $params;
				} );
				
				add_action( 'woocommerce_before_add_to_cart_button', function () {
					echo '<span class="hidden et-ghost-inline-block dir-' . apply_filters( 'product_quantity_direction', get_theme_mod( 'product_cart_form_direction_et-desktop', 'row' ) ) . '"></span>';
				} );
			}
		}
		
		$site_global_sections = get_theme_mod( 'site_sections', array() );
		if ( count( $site_global_sections ) ) {
			foreach ( $site_global_sections as $site_global_section ) {
				if ( $site_global_section['staticblock'] && $site_global_section['position'] ) {
					// before site wrapper => wp_body_open
					$action   = 'wp_footer';
					$priority = 999;
					switch ( $site_global_section['position'] ) {
						case 'before_site_wrapper':
							$action   = 'wp_body_open';
							$priority = - 999;
							break;
						case 'before_header':
							$action   = 'etheme_header_start';
							$priority = - 999;
							break;
						case 'after_header':
							$action   = 'etheme_header_end';
							$priority = 999;
							break;
						case 'before_template_content':
							$action   = 'etheme_header_before_template_content';
							$priority = - 999;
							break;
						case 'after_template_content':
							$action   = 'after_page_wrapper';
							$priority = 999;
							break;
						case 'before_prefooter':
							$action   = 'etheme_prefooter';
							$priority = - 999;
							break;
						case 'after_prefooter':
							$action   = 'etheme_prefooter';
							$priority = 999;
							break;
						case 'before_footer':
							$action   = 'etheme_footer';
							$priority = - 999;
							break;
						case 'after_footer':
							$action   = 'etheme_footer';
							$priority = 999;
							break;
						case 'after_site_wrapper':
							$action   = 'wp_footer';
							$priority = 999;
							break;
					}
					add_action( $action, function ( $params ) use ( $site_global_section ) {
						call_user_func_array( 'etheme_static_block', array(
							$site_global_section['staticblock'],
							true
						) );
					}, $priority );
				}
			}
		}
	}
}



function xstore_notice() {
	if ( ! function_exists( 'et_ajax_element_holder' ) ) {
		echo '<div class="woocommerce-info">' . sprintf(__( 'To use this element install or activate <a href="%s" target="_blank">XStore</a> theme', 'xstore-core' ), admin_url('themes.php')) . '</div>';
		return true;
	} else {
		return false;
	}
}