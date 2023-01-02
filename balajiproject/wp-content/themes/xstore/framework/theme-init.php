<?php
if ( ! defined( 'ETHEME_FW' ) ) {
	exit( 'No direct script access allowed' );
}

// **********************************************************************//
// ! Set Content Width
// Do not remove it
// important Envato!
// **********************************************************************//
if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

// **********************************************************************//
// ! Include CSS and JS
// **********************************************************************//
if ( ! function_exists( 'etheme_enqueue_scripts' ) ) {
	function etheme_enqueue_scripts() {
		if ( ! is_admin() ) {

			$theme = wp_get_theme();

//			$etheme_scripts = array( 'jquery' );
//			$is_masonry = etheme_masonry();

			$load_wc_cart_fragments = etheme_get_option( 'load_wc_cart_fragments', 0 );
			
//			$is_woocommerce = class_exists('WooCommerce');
//			$is_product = $is_woocommerce && is_product();
			$post_id      = get_the_ID();
			
			foreach (etheme_config_js_files() as $script){
				wp_register_script(
					$script['name'],
					get_template_directory_uri() . $script['file'],
					(isset($script['deps']) ? $script['deps'] : array('jquery', 'etheme')),
					(isset($script['version']) ? $script['version'] : $theme->version),
					$script['in_footer']
				);
				
				if ( isset($script['localize']) ) {
					wp_localize_script( $script['name'], $script['localize']['name'], $script['localize']['params'] );
				}
			}

			if ( get_option( 'thread_comments' ) && is_singular() ) {
				wp_enqueue_script( 'comment-reply' );
			}
			
			if ( !etheme_get_option( 'disable_old_browsers_support', get_theme_mod('et_optimize_js', 0) ? false : true ) ) {
				wp_enqueue_script( 'etheme_optimize');
			}
			
			wp_enqueue_script( 'et_imagesLoaded');
			
			if ( etheme_get_option('flying_pages', false) ) {
				if ( get_query_var('et_is-woocommerce', false) && (get_query_var('et_is-cart', false) || get_query_var('et_is-checkout', false)) ){
				}
				elseif ( function_exists( 'run_litespeed_cache' ) || defined('WP_ROCKET_VERSION') ) {
				}
				else {
					wp_enqueue_script( 'et_flying_pages' );
				}
			}
			
			if ( get_query_var('et_is-woocommerce', false)) {
				wp_enqueue_script( 'et_woocommerce');

				if ( get_query_var('et_product-hover', 'slider') == 'carousel' ) {
                    wp_enqueue_script( 'et_product_hover_slider');
                }
					
 				if ( get_query_var('et_is-cart', false) ) {
				    wp_enqueue_script( 'cart_page');
			    }
				
				if ( get_query_var('et_is-cart-checkout-advanced', false) ) {
//					if ( get_query_var('et_cart-checkout-layout', 'default') != 'default')
						wp_enqueue_script( 'sticky-kit' );
					wp_enqueue_script( 'cart_checkout_advanced_layout');
				}
 				
 				if ( class_exists('YITH_Woocompare_Frontend')) {
				    wp_enqueue_script( 'et_yith_compare');
			    }
 				
				if ( get_query_var('et_widgets-show-more', false) ) {
					wp_enqueue_script( 'widgets_show_more' );
				}
				
 				if ( get_query_var('et_is-woocommerce-archive', false) ) {
				    if ( get_query_var('et_is-products-masonry', false) ) {
					    wp_enqueue_script( 'et_isotope');
				    }
				    if ( get_query_var('et_sb_infinite_scroll', false) ) {
					    wp_enqueue_script( 'et_sb_infinite_scroll_load_more' );
				    }
			    }
			}
			
			if ( get_query_var('is_single_product', false) ) {
				wp_enqueue_script( 'photoswipe_optimize');
				wp_enqueue_script( 'et_single_product');
				if ( get_query_var('etheme_single_product_vertical_slider', false) ) {
					wp_enqueue_script( 'et_slick_slider');
					wp_enqueue_script( 'et_single_product_vertical_gallery');
				}
				if ( get_query_var( 'etheme_single_product_builder', false ) ) {
					wp_enqueue_script( 'et_single_product_builder');
				}
				elseif ( !get_query_var('is_mobile', false ) ) {
					if ( get_query_var('et_product-layout') != 'large') {
						if ( get_query_var('et_product-layout') != 'fixed' && etheme_get_option( 'fixed_images', 0 ) ) {
							wp_enqueue_script( 'sticky-kit' );
							wp_enqueue_script( 'et_single_product_sticky_images');
						} else if ( get_query_var('et_product-layout') == 'fixed' || etheme_get_option( 'fixed_content', 0 ) ) {
							wp_enqueue_script( 'sticky-kit' );
							wp_enqueue_script( 'et_single_product_sticky_images');
						}
					}
				}
			}

			$single_template = get_query_var( 'et_post-template', 'default' );

			if ( in_array( $single_template, array(
					'large',
					'large2'
				) ) && has_post_thumbnail() && is_singular( apply_filters( 'etheme_backstretch_enqueue', array( 'post', 'wpsl_stores' ) ) ) ) {
				wp_enqueue_script( 'backstretch_single_postImg');
			}

			// @todo check if only is_product()/is_quick_view()//
			if ( get_query_var( 'etheme_single_product_variation_gallery', false ) ) {
				wp_enqueue_script( 'et_single_product_variation_gallery');
			}

			if ( get_query_var('et_is-woocommerce', false) && ! $load_wc_cart_fragments && ! WC()->cart->cart_contents_count){
				wp_enqueue_script( 'etheme_mini_cart'); // maybe wp-util depends
			}
			if ( get_query_var('is_single_product', false) && etheme_get_option( 'ajax_add_to_cart', 1 ) ) {
				$product = wc_get_product ( $post_id );
				if( $product->is_type( 'variable' ) ) {
					wp_enqueue_script( 'etheme_spv_ajax_add_to_cart'); // maybe wp-util depends
				}
			}
			
			// speed optimization
			wp_enqueue_script( 'jquery_lazyload' );

            wp_enqueue_script( 'et_swiper-slider' );

			wp_enqueue_script( 'etheme-tabs' );
			wp_enqueue_script( 'etheme' );
			
			if ( get_query_var('et_portfolio-page', false) == $post_id ) {
				wp_enqueue_script( 'portfolio');
			}
			
			if ( get_query_var('et_fixed-footer', false ) ) {
				wp_enqueue_script( 'fixed-footer');
			}
			
			if ( get_query_var('et_breadcrumbs', false ) ) {
				if ( in_array(get_query_var('et_breadcrumbs-effect', 'mouse'), array('text-scroll', 'mouse'))) {
					wp_enqueue_script( 'breadcrumbs-effect-'. get_query_var('et_breadcrumbs-effect', 'mouse'));
				}
			}
			
			if ( in_array('off_canvas', array(get_query_var( 'et_sidebar', 'left' ), get_query_var( 'et_sidebar-mobile', 'bottom' )))) {
				wp_enqueue_script('canvas_sidebar');
			}
			
			if ( get_query_var('et_widgets-open-close', false)) {
				wp_enqueue_script('widgets_open_close');
			}
			
			$cartUrl = '#';

			if ( get_query_var('et_is-woocommerce', false) ) {
				$cartUrl               = esc_url( wc_get_cart_url() );

				// dequeue woocommerce zoom scripts
				if (
					( ! get_query_var( 'etheme_single_product_builder', false ) && ! etheme_get_option( 'product_zoom', true ) ) ||
					( get_query_var( 'etheme_single_product_builder', false ) && ! etheme_get_option( 'product_gallery_zoom_et-desktop', true ) ) ||
					get_query_var( 'is_mobile' ) ) {
					wp_deregister_script( 'zoom' );
					wp_dequeue_script( 'zoom' );
				}

				if ( get_query_var('et_account-registration', false) && ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'Dokan_Pro' )) ) {

					wp_enqueue_script( 'dokan-form-validate' );
					wp_enqueue_script( 'speaking-url' );
					wp_enqueue_script( 'dokan-vendor-registration' );

					wp_enqueue_script( 'wc-password-strength-meter' );

					$form_validate_messages = array(
						'required'        => __( "This field is required", 'xstore' ),
						'remote'          => __( "Please fix this field.", 'xstore' ),
						'email'           => __( "Please enter a valid email address.", 'xstore' ),
						'url'             => __( "Please enter a valid URL.", 'xstore' ),
						'date'            => __( "Please enter a valid date.", 'xstore' ),
						'dateISO'         => __( "Please enter a valid date (ISO).", 'xstore' ),
						'number'          => __( "Please enter a valid number.", 'xstore' ),
						'digits'          => __( "Please enter only digits.", 'xstore' ),
						'creditcard'      => __( "Please enter a valid credit card number.", 'xstore' ),
						'equalTo'         => __( "Please enter the same value again.", 'xstore' ),
						'maxlength_msg'   => __( "Please enter no more than {0} characters.", 'xstore' ),
						'minlength_msg'   => __( "Please enter at least {0} characters.", 'xstore' ),
						'rangelength_msg' => __( "Please enter a value between {0} and {1} characters long.", 'xstore' ),
						'range_msg'       => __( "Please enter a value between {0} and {1}.", 'xstore' ),
						'max_msg'         => __( "Please enter a value less than or equal to {0}.", 'xstore' ),
						'min_msg'         => __( "Please enter a value greater than or equal to {0}.", 'xstore' ),
					);

					wp_localize_script( 'dokan-form-validate', 'DokanValidateMsg', apply_filters( 'DokanValidateMsg_args', $form_validate_messages ) );
				}

			}
			
			$menu_hash_transient = get_transient( 'xstore-menu-hash-latest-time' ); //
			$hash_prefix = get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template();
			if ( false === $menu_hash_transient ) {
				$menu_hash_transient = time();
				set_transient( 'xstore-menu-hash-latest-time', $menu_hash_transient );
			}
			
			$etGlobalConf = array(
				'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
				'woocommerceSettings'     => array(
					'is_woocommerce'  => false,
					'is_swatches'     => false,
					'ajax_filters'    => false,
					'ajax_pagination' => false,
					'is_single_product_builder' => get_query_var( 'etheme_single_product_builder', false ),
					'mini_cart_content_quantity_input' => (etheme_get_option('cart_content_quantity_input_et-desktop', false) && !get_query_var('is_mobile', false)) || (etheme_get_option('cart_content_quantity_input_et-mobile', false) && get_query_var('is_mobile', false)),
					'widget_show_more_text' => esc_html__('more', 'xstore'),
					'widget_show_less_text' => esc_html__('Show less', 'xstore'),
					'sidebar_off_canvas_icon' => '<svg version="1.1" width="1em" height="1em" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M94.8,0H5.6C4,0,2.6,0.9,1.9,2.3C1.1,3.7,1.3,5.4,2.2,6.7l32.7,46c0,0,0,0,0,0c1.2,1.6,1.8,3.5,1.8,5.5v37.5c0,1.1,0.4,2.2,1.2,3c0.8,0.8,1.8,1.2,3,1.2c0.6,0,1.1-0.1,1.6-0.3l18.4-7c1.6-0.5,2.7-2.1,2.7-3.9V58.3c0-2,0.6-3.9,1.8-5.5c0,0,0,0,0,0l32.7-46c0.9-1.3,1.1-3,0.3-4.4C97.8,0.9,96.3,0,94.8,0z M61.4,49.7c-1.8,2.5-2.8,5.5-2.8,8.5v29.8l-16.8,6.4V58.3c0-3.1-1-6.1-2.8-8.5L7.3,5.1h85.8L61.4,49.7z"></path></svg>',
					'ajax_add_to_cart_archives' => get_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' ) == 'yes',
					'cart_url'                => false,
					'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes',
				),
				'notices'                 => array(
					'ajax-filters'         => esc_html__( 'Ajax error: cannot get filters result', 'xstore' ),
					'post-product'         => esc_html__( 'Ajax error: cannot get post/product result', 'xstore' ),
					'products'             => esc_html__( 'Ajax error: cannot get products result', 'xstore' ),
					'posts'                => esc_html__( 'Ajax error: cannot get posts result', 'xstore' ),
					'element'              => esc_html__( 'Ajax error: cannot get element result', 'xstore' ),
					'portfolio'            => esc_html__( 'Ajax error: problem with ajax et_portfolio_ajax action', 'xstore' ),
					'portfolio-pagination' => esc_html__( 'Ajax error: problem with ajax et_portfolio_ajax_pagination action', 'xstore' ),
					'menu'                 => esc_html__( 'Ajax error: problem with ajax menu_posts action', 'xstore' ),
					'noMatchFound'         => esc_html__( 'No matches found', 'xstore' ),
					'variationGalleryNotAvailable' => esc_html__('Variation Gallery not available on variation id', 'xstore'),
					'localStorageFull'         => esc_html__( 'Seems like your localStorage is full', 'xstore' ),
				),
				'layoutSettings'          => array(
					'layout'            => get_query_var('et_main-layout', 'wide'),
					'is_rtl'            => get_query_var('et_is-rtl', false),
					'is_mobile' => get_query_var('is_mobile', false),
					'mobHeaderStart' => (int)get_theme_mod('mobile_header_start_from', 992),
					'menu_storage_key'                       => apply_filters( 'etheme_menu_storage_key', 'etheme_' . md5( $hash_prefix . $menu_hash_transient ) ),
					'ajax_dropdowns_from_storage'            => apply_filters( 'etheme_ajax_dropdowns_storage', etheme_get_option( 'menu_dropdown_ajax_cache', 1 ) ),
				),
				'sidebar' => array(
					'closed_pc_by_default' => etheme_get_option('first_catItem_opened'),
				),
				'et_global' => array(
					'classes' => array(
						'skeleton' => 'skeleton-body',
						'mfp' => 'et-mfp-opened'
					),
					'is_customize_preview' => get_query_var('et_is_customize_preview', false),
					'mobHeaderStart' => (int)get_theme_mod('mobile_header_start_from', 992),
				)
			);

			$etPortfolioConf = array(
				'ajaxurl' => $etGlobalConf['ajaxurl'],
				'layoutSettings'          => array(
					'is_rtl'            => $etGlobalConf['layoutSettings']['is_rtl']
				),
			);

			$etConf = array(
				'noresults'               => esc_html__( 'No results were found!', 'xstore' ),
				'ajaxSearchResultsArrow' => '<svg version="1.1" width="1em" height="1em" class="arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M99.1186676,94.8567734L10.286458,6.0255365h53.5340881c1.6616173,0,3.0132561-1.3516402,3.0132561-3.0127683
	S65.4821625,0,63.8205452,0H3.0137398c-1.6611279,0-3.012768,1.3516402-3.012768,3.0127683v60.8068047
	c0,1.6616135,1.3516402,3.0132523,3.012768,3.0132523s3.012768-1.3516388,3.012768-3.0132523V10.2854862L94.8577423,99.117691
	C95.4281311,99.6871109,96.1841202,100,96.9886856,100c0.8036041,0,1.5595856-0.3128891,2.129982-0.882309
	C100.2924805,97.9419327,100.2924805,96.0305862,99.1186676,94.8567734z"></path></svg>',
				'successfullyAdded'       => esc_html__( 'Product added.', 'xstore' ),
				'successfullyCopied' => esc_html__('Copied to clipboard', 'xstore'),
				'saleStarts' => esc_html__('Sale starts in:', 'xstore'),
				'saleFinished' => esc_html__('This sale already finished', 'xstore'),
				'confirmQuestion' => esc_html__('Are you sure?', 'xstore'),
				'checkCart'               => esc_html__( 'Please check your ', 'xstore' ) . "<a href='" . $cartUrl . "'>" . esc_html__( 'cart.', 'xstore' ) . "</a>",
				'contBtn'                 => esc_html__( 'Continue shopping', 'xstore' ),
				'checkBtn'                => esc_html__( 'Checkout', 'xstore' ),
				'ajaxProductAddedNotify' => array(
					'type' => get_theme_mod( 'ajax_added_product_notify_type', 'alert' ),
					'linked_products_type' => get_theme_mod('ajax_added_product_notify_popup_products_type', 'upsell')
				),
				'variationGallery'        => get_query_var( 'etheme_single_product_variation_gallery', false ),
				'quickView'               => array(
					'type'     => get_query_var('et_is-quick-view-type', 'popup'),
					'position' => etheme_get_option( 'quick_view_content_position', 'right' ),
					'layout'   => etheme_get_option( 'quick_view_layout', 'default' ),
					'variationGallery' => etheme_get_option('enable_variation_gallery', 0),
				),
				'speedOptimization' => array(
					'imageLoadingOffset' => get_theme_mod('images_loading_type_et-desktop', 'lazy') != 'default' ? get_theme_mod('images_loading_offset_et-desktop', 200) . 'px' : '200px'
				),
				'popupAddedToCart' => array(),
				'builders' => array(
					'is_wpbakery' => ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ),
				),
				
				// core
				'Product' 				 => esc_html__('Products', 'xstore'),
				'Pages' 				 => esc_html__('Pages', 'xstore'),
				'Post' 					 => esc_html__('Posts', 'xstore'),
				'Portfolio' 			 => esc_html__('Portfolio', 'xstore'),
				'Product_found' => esc_html__('{{count}} Products found', 'xstore'),
				'Pages_found' => esc_html__('{{count}} Pages found', 'xstore'),
				'Post_found' => esc_html__('{{count}} Posts found', 'xstore'),
				'Portfolio_found' => esc_html__('{{count}} Portfolio found', 'xstore'),
				'show_more' => esc_html__('Show {{count}} more', 'xstore'),
				'show_all' => esc_html__('View all results', 'xstore'),
				'items_found' => esc_html__('{{count}} items found', 'xstore'),
				'item_found' => esc_html__('{{count}} item found', 'xstore'),
				'single_product_builder' => get_query_var( 'etheme_single_product_builder', false ),
				'fancy_select_categories' => get_theme_mod('search_category_fancy_select_et-desktop', false), //
				'is_search_history' => get_theme_mod( 'search_ajax_history_et-desktop', '0' ),
				'search_history_length' => get_theme_mod( 'search_ajax_history_length-desktop', '7' ),
				'search_type' => get_theme_mod( 'search_type_et-desktop', 'input' ),
				'search_ajax_history_time' => get_theme_mod( 'search_ajax_history_time-desktop', '5' ),
			);
			
			if ( get_query_var('et_is-quick-view', false) ) {
				$quickView_css_list = array(
					'quick-view',
				);
				if ( get_query_var('et_is-quick-view-type', 'popup') == 'off_canvas' ) {
					$quickView_css_list[] = 'off-canvas';
				}
				$quickView_css_list = array_merge($quickView_css_list, array(
					'skeleton',
					'single-product',
					'single-product-elements',
					'single-post-meta'
				));
				
				foreach ( $quickView_css_list as $quickView_css_list_item ) {
					ob_start();
					etheme_enqueue_style( $quickView_css_list_item, true, false );
					$etConf['quickView']['css'][ $quickView_css_list_item ] = ob_get_clean();
				}
			}
			
			if ( get_theme_mod( 'ajax_added_product_notify_type', 'alert' ) == 'popup' ) {
				$popupAddedToCart_css_list = array(
					'skeleton',
					'popup-added-to-cart',
					'single-product',
					'single-product-elements',
					'single-post-meta'
				);
				foreach ( $popupAddedToCart_css_list as $popupAddedToCart_css_list_item ) {
					ob_start();
					etheme_enqueue_style( $popupAddedToCart_css_list_item, true, false );
					$etConf['popupAddedToCart']['css'][ $popupAddedToCart_css_list_item ] = ob_get_clean();
				}
			}
			
			$etConf['noSuggestionNoticeWithMatches'] = $etConf['noresults'] . '<p>' . __( 'No items matched your search {{search_value}}.', 'xstore' ) . '</p>';

			$etGlobalConf['woocommerceSettings']['home_url'] = home_url( '/' );
			$etGlobalConf['woocommerceSettings']['shop_url'] = $etGlobalConf['woocommerceSettings']['home_url'];

			if ( get_query_var('et_is-woocommerce', false) && current_theme_supports( 'woocommerce' ) ) {
				$etGlobalConf['woocommerceSettings']['shop_url'] = wc_get_page_permalink( 'shop' );
				$etGlobalConf['woocommerceSettings']['is_woocommerce'] = true;
				$etGlobalConf['woocommerceSettings']['is_swatches']    = get_query_var('et_is-swatches', false);
				if ( get_query_var('et_is-woocommerce-archive', false) ) {
					$etGlobalConf['woocommerceSettings']['ajax_filters']    = etheme_get_option( 'ajax_product_filter', 0 );
					$etGlobalConf['woocommerceSettings']['ajax_pagination'] = etheme_get_option( 'ajax_product_pagination', 0 );
					$etGlobalConf['woocommerceSettings']['sidebar_widgets_dropdown_limit'] = get_query_var('et_widgets-show-more-after', 3);
					$etGlobalConf['woocommerceSettings']['sidebar_widgets_dropdown_less_link'] = get_query_var('et_widgets-show-less', false);
					$etGlobalConf['woocommerceSettings']['wishlist_for_variations'] = etheme_get_option('wishlist_for_variations_new',1);
				}

                // @uses not only for progress bar but for bought together products on single product page
				$etGlobalConf['woocommerceSettings']['cart_progress_currency_pos'] = get_option( 'woocommerce_currency_pos' );
				$etGlobalConf['woocommerceSettings']['cart_progress_thousand_sep'] = get_option( 'woocommerce_price_thousand_sep' );
				$etGlobalConf['woocommerceSettings']['cart_progress_decimal_sep']  = get_option( 'woocommerce_price_decimal_sep' );
				$etGlobalConf['woocommerceSettings']['cart_progress_num_decimals'] = get_option( 'woocommerce_price_num_decimals' );
				$etGlobalConf['woocommerceSettings']['is_smart_addtocart'] = etheme_get_option('product_page_smart_addtocart', 0);

				$etGlobalConf['woocommerceSettings']['primary_attribute'] = etheme_get_option('primary_attribute', 'et_none');
				
				$etGlobalConf['woocommerceSettings']['cart_url'] = apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null );
				if ( etheme_get_option('sidebar_for_mobile', 'off_canvas') == 'off_canvas' ) {
					$sidebar_off_canvas_icon = etheme_get_option('sidebar_for_mobile_icon', '');
					$sidebar_off_canvas_icon = isset($sidebar_off_canvas_icon['id']) ? $sidebar_off_canvas_icon['id'] : '';
					if ( function_exists('etheme_fgcontent') && $sidebar_off_canvas_icon != '' ) {
						$type      = get_post_mime_type( $sidebar_off_canvas_icon );
						$mime_type = explode( '/', $type );
						if ( $mime_type['1'] == 'svg+xml' ) {
							$svg = get_post_meta( $sidebar_off_canvas_icon, '_xstore_inline_svg', true );

							if ( ! empty( $svg ) ) {
								$etGlobalConf['woocommerceSettings']['sidebar_off_canvas_icon'] = $svg;
							} else {

								$attachment_file = get_attached_file( $sidebar_off_canvas_icon );

								if ( $attachment_file ) {

									$svg = etheme_fgcontent( $attachment_file , false, null);

									if ( ! empty( $svg ) ) {
										update_post_meta( $sidebar_off_canvas_icon, '_xstore_inline_svg', $svg );
									}

									$etGlobalConf['woocommerceSettings']['sidebar_off_canvas_icon'] = $svg;

								}

							}
						}
						else {
							$etGlobalConf['woocommerceSettings']['sidebar_off_canvas_icon'] = etheme_get_image($sidebar_off_canvas_icon, 'thumbnail' );
						}
					}
				}
			}
			
			$etConf = array_merge($etConf, $etGlobalConf);
			
			if ( $etGlobalConf['woocommerceSettings']['ajax_filters'] || $etGlobalConf['woocommerceSettings']['ajax_pagination'] ) {
				$etAjaxFiltersConfig = array(
					'scroll_top_after_ajax' => etheme_get_option('ajax_product_filter_scroll_top', 1),
					'ajax_categories' => etheme_get_option('ajax_categories', 1),
					'product_page_banner_pos' => etheme_get_option( 'product_bage_banner_pos', 1 ),
					'loop_prop_columns' => etheme_get_option('woocommerce_catalog_columns', 4),
					'loop_prop_columns_category' => etheme_get_option('category_page_columns', 'inherit'),
					'builder' => '',
				);
				
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					$etAjaxFiltersConfig['builder'] .= '/elementor';
				}
				
				if ( defined( 'WPB_VC_VERSION' ) ) {
					$etAjaxFiltersConfig['builder'] .= '/wpb';
				}
				
				if ( get_query_var('view_mode_smart', false) ) {
					$etAjaxFiltersConfig['loop_prop_columns'] = etheme_get_option('view_mode_smart_active', 4);
					$etAjaxFiltersConfig['loop_prop_columns_category'] = etheme_get_option('categories_view_mode_smart_active', 4);
				}
				
				$etAjaxFiltersConfig = array_merge($etGlobalConf, $etAjaxFiltersConfig);
				
				wp_enqueue_script( 'ajaxFilters', get_template_directory_uri() . '/js/ajax-filters.min.js',array('etheme'), $theme->version, true );
				wp_localize_script( 'ajaxFilters', 'etAjaxFiltersConfig', $etAjaxFiltersConfig );
				
			}

			wp_localize_script( 'etheme', 'etConfig', apply_filters( 'etheme_et_js_config', $etConf) );
			wp_localize_script( 'portfolio', 'etPortfolioConfig', $etPortfolioConf );
			wp_dequeue_script( 'prettyPhoto-init' );

			if ( class_exists( 'Vc_Manager' ) ) {
				// fix for scripts in static blocks
				wp_enqueue_script( 'wpb_composer_front_js' );
			}

			if ( get_query_var('et_is-woocommerce', false) && ! $load_wc_cart_fragments && ! WC()->cart->cart_contents_count){
				wp_dequeue_script( 'wc-cart-fragments' );
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'etheme_enqueue_scripts', 30 );

if ( !function_exists('etheme_config_js_files')) {
	function etheme_config_js_files() {
		return include get_template_directory() . '/framework/config-js.php';
	}
}

add_action('comment_form_after', function(){
	wp_enqueue_script( 'comments_form_validation' );
});

// **********************************************************************//
// ! Theme 3d plugins
// **********************************************************************//
add_action( 'init', 'etheme_3d_plugins' );
if ( ! function_exists( 'etheme_3d_plugins' ) ) {
	function etheme_3d_plugins() {
		if ( function_exists( 'set_revslider_as_theme' ) ) {
			set_revslider_as_theme();
		}
		if ( function_exists( 'set_ess_grid_as_theme' ) ) {
			set_ess_grid_as_theme();
		}
	}
}

add_action( 'vc_before_init', 'etheme_vcSetAsTheme' );
if ( ! function_exists( 'etheme_vcSetAsTheme' ) ) {
	function etheme_vcSetAsTheme() {
		if ( function_exists( 'vc_set_as_theme' ) ) {
			vc_set_as_theme();
		}
	}
}

// ! REFER for woo premium plugins
if ( ! defined( 'YITH_REFER_ID' ) ) {
	define( 'YITH_REFER_ID', '1028760' );
}

// REFER for yellow pencil
if ( ! defined( 'YP_THEME_MODE' ) ) {
	define( 'YP_THEME_MODE', "true" );
}

// **********************************************************************//
// ! Load theme translations
// **********************************************************************//
if ( ! function_exists( 'etheme_load_textdomain' ) ) {
	add_action( 'after_setup_theme', 'etheme_load_textdomain' );

	function etheme_load_textdomain() {
		load_theme_textdomain( 'xstore', get_template_directory() . '/languages' );

		$locale      = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable( $locale_file ) ) {
			require_once( $locale_file );
		}
	}
}