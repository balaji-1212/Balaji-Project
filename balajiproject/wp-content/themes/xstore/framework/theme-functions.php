<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************//
// ! Add custom query data
// **********************************************************************//
add_action('wp', 'et_custom_query', 1);
if ( ! function_exists( 'et_custom_query' ) ) {
	function et_custom_query(){
		if ( is_admin() ) return;
		
		global $wp;
		global $post;

//		$old_options = get_option('et_options', array());
//		set_query_var( 'et_redux_options', $old_options );
		
		$is_woocommerce = class_exists('WooCommerce');
		set_query_var('et_is-woocommerce', $is_woocommerce);
		$etheme_single_product_builder = false;

//		$id = $post_id['id'];
		$id = ( $post && is_object($post) && $post->ID) ? $post->ID : 0;
		$is_mobile_device = wp_is_mobile();
		$is_customize_preview = is_customize_preview();
		$fixed_footer = ( ( etheme_get_option('footer_fixed', 0) || etheme_get_custom_field('footer_fixed', $id) == 'yes' ) );
		
		set_query_var('et_fixed-footer', $fixed_footer);
		
		set_query_var('et_is_customize_preview', $is_customize_preview);
		set_query_var('et_btt', etheme_get_option('to_top', 1) );
		set_query_var('et_btt-mobile', etheme_get_option('to_top_mobile', 1) );
		
		$template = etheme_get_option('post_template', 'default');
		
		$custom = etheme_get_custom_field('post_template', $id);
		
		$is_logged_in = is_user_logged_in();
		set_query_var( 'et_is-loggedin', $is_logged_in);
		
		if( ! empty( $custom ) ) {
			$template = $custom;
		}
		
		if ( $is_woocommerce ) {

			$etheme_single_product_builder = get_option( 'etheme_single_product_builder', false );
			
			$grid_sidebar = etheme_get_option('grid_sidebar', 'left');
			set_query_var('et_grid-sidebar', $grid_sidebar);
			
			// set catalog mode query
			set_query_var('et_is-catalog', etheme_is_catalog());
			
			if ( !$is_logged_in ) {
				set_query_var( 'et_account-registration', 'yes' === get_option( 'woocommerce_enable_myaccount_registration', 'no' ) );
				set_query_var( 'et_account-registration-generate-pass', 'yes' === get_option( 'woocommerce_registration_generate_password', 'yes' ) );
			}
			
			set_query_var('et_is-swatches', etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ));
			set_query_var('et_is-quick-view', etheme_get_option('quick_view', 1));
			set_query_var('et_is-quick-view-type', etheme_get_option('quick_view_content_type', 'popup'));
			
			if ( etheme_get_option('advanced_stock_status', false) ) {
				set_query_var( 'et_product-advanced-stock', true );
//				set_query_var( 'et_product-archive-advanced-stock', etheme_get_option( 'advanced_stock_status_archive', false ) );
				set_query_var( 'et_product-advanced-stock-locations', etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') ) );
			}
			
			$is_product_cat = is_product_category();
			
			if  (is_shop() || $is_product_cat || is_product_tag() || is_product_taxonomy() || is_tax('brand') || is_post_type_archive( 'product' ) ||
			     ( defined('WC_PRODUCT_VENDORS_TAXONOMY') && is_tax( WC_PRODUCT_VENDORS_TAXONOMY ) ) ||
			     (function_exists('dokan_is_store_page') && dokan_is_store_page()) ||
			     apply_filters('etheme_is_shop_page', false) ) {
				$view_mode = etheme_get_view_mode();
				set_query_var( 'et_view-mode', $view_mode );
				
				set_query_var('et_is-woocommerce-archive', true);
				set_query_var('et_is-products-masonry', etheme_get_option( 'products_masonry', 0 ));
				
				if ( etheme_get_option('sidebar_widgets_scroll', 0) ) {
					set_query_var('et_sidebar-widgets-scroll', true);
				}
				if ( etheme_get_option('sidebar_widgets_open_close', 0) ) {
					set_query_var('et_widgets-open-close', true);
					set_query_var('et_sidebar-widgets-open-close', true);
				}
				if ( etheme_get_option('filters_area_widgets_open_close', 0) ) {
					set_query_var('et_widgets-open-close', true);
					set_query_var('et_filters-area-widgets-open-close', true);
				}
				$filters_area_widgets_open_close_type = etheme_get_option('filters_area_widgets_open_close_type', 'open');
				if ($filters_area_widgets_open_close_type == 'closed' || (($filters_area_widgets_open_close_type == 'closed_mobile') && $is_mobile_device) ) {
					set_query_var('et_filters-area-widgets-open-close-default', true);
				}
				
				$sidebar_widgets_open_close_type = etheme_get_option('sidebar_widgets_open_close_type', 'open');
				if ($sidebar_widgets_open_close_type == 'closed' || (($sidebar_widgets_open_close_type == 'closed_mobile') && $is_mobile_device) ) {
					set_query_var('et_sidebar-widgets-open-close-default', true);
				}
				if ( etheme_get_option('show_plus_filters',0) ) {
					set_query_var('et_widgets-show-more', true);
					set_query_var('et_widgets-show-more-after', etheme_get_option('show_plus_filter_after',3));
					set_query_var('et_widgets-show-less', get_theme_mod('show_plus_less_filters', false));
				}
				
				// set product options
				$product_settings = etheme_get_option('product_page_switchers', array(
						'product_page_productname',
						'product_page_cats',
						'product_page_price',
						'product_page_addtocart',
						'product_page_productrating',
						'hide_buttons_mobile')
				);
				$product_settings = !is_array( $product_settings ) ? array() : $product_settings;
				set_query_var('et_product-variable-detach', etheme_get_option('variable_products_detach', false));
				set_query_var('et_product-variable-name-attributes', etheme_get_option('variation_product_name_attributes', true));
				
				set_query_var('et_product-variable-price-from', etheme_get_option('product_variable_price_from', false));
				
				set_query_var('et_product-hover', etheme_get_option('product_img_hover', 'slider'));
				set_query_var('et_product-view', etheme_get_option('product_view', 'disable'));
				set_query_var('et_product-view-color', etheme_get_option('product_view_color', 'white'));
				set_query_var('et_product-excerpt', etheme_get_option('product_page_excerpt', false));
				
				set_query_var('et_product-excerpt-length', etheme_get_option('product_page_excerpt_length', 120));
				set_query_var('et_product-switchers', $product_settings);
				set_query_var('et_product-with-quantity', etheme_get_option('product_page_smart_addtocart', 0));
				
				set_query_var('et_product-new-label-range', etheme_get_option('product_new_label_range', 0));
				set_query_var('et_product-featured-label', etheme_get_option('featured_label', false));
				
				set_query_var('et_product-title-limit-type', etheme_get_option('product_title_limit_type', 'chars'));
				set_query_var('et_product-title-limit', etheme_get_option('product_title_limit', 0));
				
				set_query_var('et_product-bordered-layout', etheme_get_option('product_bordered_layout', 0));
				set_query_var('et_product-no-space', etheme_get_option('product_no_space', 0));
				set_query_var('et_product-shadow-hover', etheme_get_option('product_with_box_shadow_hover', 0));
				
				// set shop products custom template
				$grid_custom_template = etheme_get_option('custom_product_template', 'default');
				$list_custom_template = etheme_get_option('custom_product_template_list', 'default');
				$list_custom_template = ( $list_custom_template != '-1' ) ? $list_custom_template : $grid_custom_template;
				
				set_query_var('et_custom_product_template', ( $view_mode == 'grid' ? (int)$grid_custom_template : (int)$list_custom_template ) );
				
				$view_mode_smart = etheme_get_option('view_mode', 'grid_list') == 'smart';
				set_query_var('view_mode_smart', $view_mode_smart);
				$view_mode_smart_active = etheme_get_option('view_mode_smart_active', 4);
				set_query_var('view_mode_smart_active', $view_mode_smart_active);
			}
			
			if ( $is_product_cat ) {
				$categories_sidebar = etheme_get_option('category_sidebar', 'left');
				set_query_var('et_cat-sidebar', $categories_sidebar);
				if ( $view_mode_smart ) {
					$view_mode_smart_active = etheme_get_option('categories_view_mode_smart_active', 4);
					set_query_var('view_mode_smart_active', $view_mode_smart_active);
				}
				$category_cols = (int)etheme_get_option('category_page_columns', 'inherit');
				if ( $category_cols >= 1 ) {
					set_query_var('et_cat-cols', $category_cols);
				}
			}

            elseif ( is_tax('brand') ) {
				$brand_sidebar = etheme_get_option('brand_sidebar', 'left');
				set_query_var('et_cat-sidebar', $brand_sidebar);
				if ( $view_mode_smart ) {
					$view_mode_smart_active = etheme_get_option('brands_view_mode_smart_active', 4);
					set_query_var('view_mode_smart_active', $view_mode_smart_active);
				}
				$brand_cols = (int)etheme_get_option('brand_page_columns', 'inherit');
				if ( $brand_cols >= 1 ) {
					set_query_var('et_cat-cols', $brand_cols);
				}
			}

            elseif ( is_cart() ) {
				set_query_var('et_is-cart', true);
				if ( !WC()->cart->is_empty() )
					set_query_var('et_is-cart-checkout-advanced', get_theme_mod('cart_checkout_advanced_layout', false));
			}
            elseif ( is_checkout() ) {
				set_query_var('et_is-checkout', true);
				set_query_var('et_is-cart-checkout-advanced', get_theme_mod('cart_checkout_advanced_layout', false));
			}
			
			if ( get_query_var('et_is-cart-checkout-advanced', false ) ) {
				set_query_var( 'et_cart-checkout-layout', get_theme_mod( 'cart_checkout_layout_type', 'default' ) );
				set_query_var( 'et_cart-checkout-header-builder', get_theme_mod( 'cart_checkout_header_builder', false ) );
				set_query_var( 'et_cart-checkout-default-footer', get_theme_mod( 'cart_checkout_default_footer', false ) );
				global $wp;
				// Handle checkout actions.
				if ( ! empty( $wp->query_vars['order-pay'] ) ) {
//					$is_order_pay = true;
					set_query_var( 'et_cart-checkout-layout', 'default' );
				} elseif ( isset( $wp->query_vars['order-received'] ) ) {
					set_query_var( 'et_cart-checkout-layout', 'default' );
				}
			}

//             if ( is_product() ) {
			
			if ( !$etheme_single_product_builder ) {

//				$layout = $l['product_layout'];
				$layout = etheme_get_option('single_layout', 'default');
				$single_layout = etheme_get_custom_field('single_layout');
				if(!empty($single_layout) && $single_layout != 'standard') {
					$layout = $single_layout;
				}
				
				$thumbs_slider_mode = etheme_get_option('thumbs_slider_mode', 'enable');
				
				if ( $thumbs_slider_mode == 'enable' || ( $thumbs_slider_mode == 'enable_mob' && $is_mobile_device ) ) {
					$gallery_slider = true;
				}
				else {
					$gallery_slider = false;
				}
				
				$thumbs_slider = etheme_get_option('thumbs_slider_vertical', 'horizontal');
				
				$enable_slider = etheme_get_custom_field('product_slider', $id);
				
				$stretch_slider = etheme_get_option('stretch_product_slider', 1);
				
				$slider_direction = etheme_get_custom_field('slider_direction', $id);
				
				$vertical_slider = $thumbs_slider == 'vertical';
				
				if ( $slider_direction == 'vertical' ) {
					$vertical_slider = true;
				}
                elseif($slider_direction == 'horizontal') {
					$vertical_slider = false;
				}
				
				$show_thumbs = $thumbs_slider != 'disable';
				
				if ( $layout == 'large' && $stretch_slider ) {
					$show_thumbs = false;
				}
				if ( $slider_direction == 'disable' ) {
					$show_thumbs = false;
				}
                elseif ( in_array($slider_direction, array('vertical', 'horizontal') ) ) {
					$show_thumbs = true;
				}
				if ( $enable_slider == 'on' || ($enable_slider == 'on_mobile' && $is_mobile_device ) ) {
					$gallery_slider = true;
				}
                elseif ( $enable_slider == 'off' || ($enable_slider == 'on_mobile' && !$is_mobile_device ) ) {
					$gallery_slider = false;
					$show_thumbs = false;
				}

//                    $etheme_single_product_variation_gallery = $gallery_slider && $show_thumbs && etheme_get_option('enable_variation_gallery');
			
			}
			else {
				
				$gallery_type = etheme_get_option('product_gallery_type_et-desktop', 'thumbnails_bottom');
				$vertical_slider = $gallery_type == 'thumbnails_left';
				
				$gallery_slider = ( !in_array($gallery_type, array('one_image', 'double_image')) );
				$show_thumbs = ( in_array($gallery_type, array('thumbnails_bottom', 'thumbnails_bottom_inside', 'thumbnails_left')));
//				$thumbs_slider = etheme_get_option('product_gallery_thumbnails_et-desktop', 1);
				
				if( defined('DOING_AJAX') && DOING_AJAX ) {
					$gallery_slider = true;
				}

//                    $etheme_single_product_variation_gallery = etheme_get_option('enable_variation_gallery');
				
			}
			
			set_query_var( 'etheme_single_product_gallery_type', $gallery_slider );
			set_query_var( 'etheme_single_product_vertical_slider', $vertical_slider );
			set_query_var( 'etheme_single_product_show_thumbs', $show_thumbs );
			
			$single_page_shortcode = ( ! empty( $post->post_content ) && strstr( $post->post_content, '[product_page' ) );
			
			if ( $single_page_shortcode ) {
				set_query_var('is_single_product_shortcode', true);
			}
			
			if ( is_product() ) {
				set_query_var( 'etheme_single_product_variation_gallery', apply_filters('etheme_single_product_variation_gallery', etheme_get_option('enable_variation_gallery', 0) ) );
				set_query_var('is_single_product', true);
				if ( etheme_get_option('single_product_widget_area_1_widget_scroll_et-desktop', 0) ) {
					set_query_var('et_sidebar-widgets-scroll', true);
				}
				
				if ( etheme_get_option('single_product_widget_area_1_widget_toggle_et-desktop', 0) ) {
					set_query_var('et_widgets-open-close', true);
					set_query_var('et_sidebar-widgets-open-close', true);
				}
				$single_product_widget_area_1_widget_toggle_actions = etheme_get_option('single_product_widget_area_1_widget_toggle_actions_et-desktop', 'opened');
				if ($single_product_widget_area_1_widget_toggle_actions == 'closed' || (($single_product_widget_area_1_widget_toggle_actions == 'mob_closed') && $is_mobile_device) ) {
					set_query_var('et_sidebar-widgets-open-close-default', true);
				}
			}
			
			set_query_var('etheme_variable_products_detach', etheme_get_option('variable_products_detach', false));
			set_query_var('etheme_variation_product_parent_hidden', etheme_get_option('variation_product_parent_hidden', true));
			set_query_var('etheme_variation_product_name_attributes', etheme_get_option('variation_product_name_attributes', true));
			
			// }
            
            if ( class_exists( 'SB_WooCommerce_Infinite_Scroll' ) || class_exists('SBWIS_WooCommerce_Infinite_Scroll') ) {
	            set_query_var('et_sb_infinite_scroll', true);
            }
		}
		
		if ( etheme_get_option('portfolio_projects', 1) ) {
			set_query_var( 'et_portfolio-projects', true );
			set_query_var( 'et_portfolio-page', get_theme_mod( 'portfolio_page', '' ) );
		}
		
		// placed here to make work ok with query vars set above
		$post_id = etheme_get_page_id();
		
		if ( in_array($post_id['type'], array('post', 'blog')) || is_search() || is_tag() || is_category() || is_date() || is_author() ) {
			set_query_var('et_is-blog-archive', true);
		}
		
		// ! set-query-var
		set_query_var( 'is_yp', isset($_GET['yp_page_type'])); // yellow pencil
		set_query_var( 'et_post-template', $template );
		set_query_var( 'is_mobile', $is_mobile_device );
		set_query_var('et_mobile-optimization', get_theme_mod('mobile_optimization', false) && !$is_customize_preview);
		if ( get_query_var('et_is-cart-checkout-advanced', false ) ) {
			set_query_var('et_mobile-optimization', false);
		}
		set_query_var( 'et_page-id', $post_id );
		set_query_var( 'etheme_single_product_builder', $etheme_single_product_builder );
		
		// after all of that because this requires some query vars are set above
		$l = etheme_page_config();
		
		if ($l['breadcrumb'] !== 'disable' && !$l['slider']) {
			set_query_var('et_breadcrumbs', true);
			set_query_var('et_breadcrumbs-type', $l['breadcrumb']);
			set_query_var('et_breadcrumbs-effect', $l['bc_effect']);
			set_query_var('et_breadcrumbs-color', $l['bc_color']);
		}
		
		set_query_var('et_page-slider', $l['slider']);
		set_query_var('et_page-banner', $l['banner']);
		
		set_query_var('et_content-class', $l['content-class']);
		set_query_var('et_sidebar', $l['sidebar']);
		set_query_var('et_sidebar-mobile', $l['sidebar-mobile']);
		set_query_var('et_sidebar-class', $l['sidebar-class']);
		set_query_var('et_widgetarea', $l['widgetarea']);
		
		set_query_var('et_product-layout', $l['product_layout']);
		
		if ( $is_mobile_device && etheme_get_option('footer_widgets_open_close', 1) ) {
			set_query_var('et_widgets-open-close', true);
		}
		
		set_query_var('et_main-layout', etheme_get_option( 'main_layout' ));
		set_query_var('et_is-rtl', is_rtl());
		set_query_var('et_is-single', is_single());
	}
}

function etheme_child_styles() {
	// files:
	// parent-theme/style.css, parent-theme/bootstrap.css (parent-theme/xstore.css), secondary-menu.css, options-style.min.css, child-theme/style.css
	$theme = wp_get_theme();
		
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
//			array('parent-style', 'bootstrap'),
        array( 'etheme-parent-style' ),
        $theme->version
    );
}

// **********************************************************************//
// ! Add classes to body
// **********************************************************************//
add_filter('body_class', 'etheme_add_body_classes');
if(!function_exists('etheme_add_body_classes')) {
	function etheme_add_body_classes($classes) {
		$post_id = (array)get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$post_template  = get_query_var('et_post-template', 'default');
		
		$id = $post_id['id'];
		$etheme_single_product_builder = get_query_var('etheme_single_product_builder', false);
		
		// portfolio page asap fix
		$portfolio_page_id = get_query_var('et_portfolio-page', false);
		
		if ( get_query_var( 'et_portfolio-projects', false ) && $portfolio_page_id ) {
			
			if ( function_exists('icl_object_id') ) {
				global $sitepress;
				if ( ! empty( $sitepress )  ) {
					$multy_id = icl_object_id ( $id, "page", false, $sitepress->get_default_language() );
				} elseif( function_exists( 'pll_current_language' ) ) {
					$multy_id = icl_object_id ( $id, "page", false, pll_current_language() );
				} else {
					$multy_id = false;
				}
				
				if (  $id == $portfolio_page_id || $portfolio_page_id == $multy_id ) {
					foreach ( $classes as $key => $value ) {
						if ( in_array($value, array('page-template-default', 'page-template-portfolio') ) ) unset( $classes[ $key ] );
					}
					$classes[] = 'page-template-portfolio';
					etheme_enqueue_style( 'portfolio' );
					// mostly filters are not shown on portfolio category
					if ( ! get_query_var( 'portfolio_category' ) ) {
						etheme_enqueue_style( 'isotope-filters' );
					}
				}
			} else {
				if (  $id == $portfolio_page_id ) {
					foreach ( $classes as $key => $value ) {
						if ( in_array($value, array('page-template-default', 'page-template-portfolio') ) ) unset( $classes[ $key ] );
					}
					$classes[] = 'page-template-portfolio';
					etheme_enqueue_style( 'portfolio' );
					// mostly filters are not shown on portfolio category
					if ( ! get_query_var( 'portfolio_category' ) ) {
						etheme_enqueue_style( 'isotope-filters' );
					}
				}
			}
		}
		
		if ( get_query_var('et_is-woocommerce', false)) {
			$cart = etheme_get_option( 'cart_icon_et-desktop', 'type1' );
			switch ( $cart ) {
				case 'type1':
					$classes[] = 'et_cart-type-1';
					break;
				case 'type2':
					$classes[] = 'et_cart-type-4';
					break;
				case 'type4':
					$classes[] = 'et_cart-type-3';
					break;
				default:
					$classes[] = 'et_cart-type-2';
					break;
			}
		}
		
		$classes[] = (etheme_get_option('header_overlap_et-desktop', 0)) ? 'et_b_dt_header-overlap' : 'et_b_dt_header-not-overlap';
		$classes[] = (etheme_get_option('header_overlap_et-mobile', 0)) ? 'et_b_mob_header-overlap' : 'et_b_mob_header-not-overlap';
		
		// on hard testing
		if ( get_query_var('et_breadcrumbs', false) ) {
			$classes[] = 'breadcrumbs-type-' . get_query_var( 'et_breadcrumbs-type', 'none' );
		}
		$classes[] = get_query_var('et_main-layout', 'wide');
		if ( get_query_var('et_is-cart', false) || get_query_var('et_is-checkout', false) ) {
			if ( !get_query_var('et_is-cart-checkout-advanced', false) )
				$classes[] = ( etheme_get_option( 'cart_special_breadcrumbs', 1 ) ) ? 'special-cart-breadcrumbs' : '';
		}
		$classes[] = (etheme_get_option('site_preloader', 0)) ? 'et-preloader-on' : 'et-preloader-off';
		$classes[] = (get_query_var('et_is-catalog', false)) ? 'et-catalog-on' : 'et-catalog-off';
		$classes[] = ( get_query_var('is_mobile', false) ) ? 'mobile-device' : '';
		if ( get_query_var('is_mobile', false) && etheme_get_option('footer_widgets_open_close', 1) ) {
			$classes[] = 'f_widgets-open-close';
			$classes[] = (etheme_get_option('footer_widgets_open_close_type', 'closed_mobile') == 'closed_mobile') ? 'fwc-default' : '';
		}
		
		// globally because conditions are set properly
		if ( get_query_var('et_sidebar-widgets-scroll', false) ) {
			$classes[] = 's_widgets-with-scroll';
		}
		if ( get_query_var('et_sidebar-widgets-open-close', false) ) {
			$classes[] = 's_widgets-open-close';
			if ( get_query_var('et_sidebar-widgets-open-close-default', false) ) {
				$classes[] = 'swc-default';
			}
		}
		
		if ( get_query_var('et_is-woocommerce', false)) {
			if (get_query_var('et_filters-area-widgets-open-close', false)) {
				$classes[] = 'fa_widgets-open-close';
				if (get_query_var('et_filters-area-widgets-open-close-default', false)) {
					$classes[] = 'fawc-default';
				}
			}
			
			if (get_query_var('is_single_product', false)) {
				$classes[] = 'sticky-message-' . (etheme_get_option('sticky_added_to_cart_message', 1) ? 'on' : 'off');
				if (!$etheme_single_product_builder) {
					$classes[] = 'global-product-name-' . (etheme_get_option('product_name_signle', 0) && !etheme_get_option('product_name_single_duplicated', 0) ? 'off' : 'on');
				}
			} elseif (get_query_var('et_is-cart-checkout-advanced', false)) { // keeps inside condition of is_cart || is_checkout
				$classes[] = 'cart-checkout-advanced-layout';
				$classes[] = 'cart-checkout-' . get_query_var( 'et_cart-checkout-layout', 'default' );
				if ( !get_query_var('et_cart-checkout-header-builder', false) ) {
					$classes[] = 'cart-checkout-light-header';
				}
				if ( !get_query_var('et_cart-checkout-default-footer', false) ) {
					$classes[] = 'cart-checkout-light-footer';
				}
			}
		}
		
		if ( did_action('etheme_load_all_departments_styles') ) {
			// secondary
			$classes[] = 'et-secondary-menu-on';
			$classes[] = 'et-secondary-visibility-' . etheme_get_option( 'secondary_menu_visibility', 'on_hover' );
			if ( etheme_get_option( 'secondary_menu_visibility', 'on_hover' ) == 'opened' ) {
				$classes[] = ( etheme_get_option( 'secondary_menu_home', '1' ) ) ? 'et-secondary-on-home' : '';
				$classes[] = ( etheme_get_option( 'secondary_menu_subpages' ) ) ? 'et-secondary-on-subpages' : '';
			}
		}
		
		if ( !get_query_var('is_single_product', false) && get_query_var('et_is-single', false) ) {
			if ( $post_template == 'large2' ) {
				$post_template = 'large global-post-template-large2';
			}
			$classes[] = 'global-post-template-' . $post_template;
		}
		
		if ( class_exists( 'WooCommerce_Quantity_Increment' ) ) $classes[] = 'et_quantity-off';
		
		if ( get_query_var('et_is-swatches', false) ) {
			$classes[] = 'et-enable-swatch';
		}
		
		if ( !etheme_get_option( 'disable_old_browsers_support', get_theme_mod('et_optimize_js', 0) ? false : true ) ) {
			$classes[] = 'et-old-browser';
		}
		
		return $classes;
	}
}

// **********************************************************************//
// ! core plugin active notice
// **********************************************************************//
if( ! function_exists('etheme_xstore_plugin_notice') ) {
	function etheme_xstore_plugin_notice($notice = '') {
		if ( ! defined( 'ET_CORE_DIR' ) ) {
			if ( $notice == '' ) $notice = esc_html__( 'To use this element install or activate XStore Core plugin', 'xstore' );
			echo '<p class="woocommerce-warning">' . $notice . '</p>';
			return true;
		} else {
			return false;
		}
	}
}

// **********************************************************************//
// ! Get column class bootstrap
// **********************************************************************//
// @todo product_functions/portfolio ?
if(!function_exists('etheme_get_product_class')) {
	function etheme_get_product_class($columns = 3 ) {
		$columns = intval($columns);
		
		if (! $columns ) {
			$columns = 3;
		}
		$cols = 12 / $columns;
		
		$small = 6;
		$extra_small = 6;
		
		$class = 'col-md-' . $cols;
		$class .= ' col-sm-' . $small;
		$class .= ' col-xs-' . $extra_small;
		
		return $class;
	}
}

// **********************************************************************//
// ! Custom Comment Form
// **********************************************************************//
if(!function_exists('etheme_custom_comment_form')) {
	function etheme_custom_comment_form($defaults) {
		$defaults['comment_notes_before'] = '
			<p class="comment-notes">
				<span id="email-notes">
				' . esc_html__( 'Your email address will not be published. Required fields are marked', 'xstore' ) . '
				</span>
			</p>
		';
		$defaults['comment_notes_after'] = '';
		
		$defaults['comment_field'] = '
			<div class="form-group">
				<label for="comment" class="control-label">'.esc_html__('Your Comment', 'xstore').'</label>
				<textarea placeholder="' . esc_html__('Comment', 'xstore') . '" class="form-control required-field"  id="comment" name="comment" cols="45" rows="12" aria-required="true"></textarea>
			</div>
		';
		
		return $defaults;
	}
}

add_filter('comment_form_defaults', 'etheme_custom_comment_form');

if(!function_exists('etheme_custom_comment_form_fields')) {
	function etheme_custom_comment_form_fields() {
		$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$reqT = '<span class="required">*</span>';
		$aria_req = ($req ? " aria-required='true'" : ' ');
		$consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields = array(
			'author' => '
				<div class="form-group comment-form-author">'.
			            '<label for="author" class="control-label">'.esc_html__('Name', 'xstore').' '.($req ? $reqT : '').'</label>'.
			            '<input id="author" name="author" placeholder="' . esc_html__('Your name (required)', 'xstore') . '" type="text" class="form-control ' . ($req ? ' required-field' : '') . '" value="' . esc_attr($commenter['comment_author']) . '" size="30" ' . $aria_req . '>'.
			            '</div>
			',
			'email' => '
				<div class="form-group comment-form-email">'.
			           '<label for="email" class="control-label">'.esc_html__('Email', 'xstore').' '.($req ? $reqT : '').'</label>'.
			           '<input id="email" name="email" placeholder="' . esc_html__('Your email (required)', 'xstore') . '" type="text" class="form-control ' . ($req ? ' required-field' : '') . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" ' . $aria_req . '>'.
			           '</div>
			',
			'url' => '
				<div class="form-group comment-form-url">'.
			         '<label for="url" class="control-label">'.esc_html__('Website', 'xstore').'</label>'.
			         '<input id="url" name="url" placeholder="' . esc_html__('Your website', 'xstore') . '" type="text" class="form-control" value="' . esc_attr($commenter['comment_author_url']) . '" size="30">'.
			         '</div>
			',
			'cookies' => '
				<p class="comment-form-cookies-consent">
					<label for="wp-comment-cookies-consent">
						<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' . '
						<span>' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'xstore' ) . '</span>
					</label>
				</p>'
		);
		
		return $fields;
	}
}

add_filter('comment_form_default_fields', 'etheme_custom_comment_form_fields');

if ( ! function_exists( 'filter_login_form_middle' ) ) {
	function filter_login_form_middle( $content, $args ){
		$content .= '<a href="'.wp_lostpassword_url().'" class="lost-password">'.esc_html__('Lost Password ?', 'xstore').'</a>';
		return $content;
	}
}
add_filter( 'login_form_middle', 'filter_login_form_middle', 10, 2 );

// **********************************************************************//
// ! Enable shortcodes in text widgets
// **********************************************************************//
add_filter('widget_text', 'do_shortcode');

// **********************************************************************//
// ! Search, search SKU
// **********************************************************************/

add_action('pre_get_posts', 'etheme_search_all_sku_query');
if (! function_exists('etheme_search_all_sku_query')) {
	function etheme_search_all_sku_query($query){
		add_filter('posts_where', 'etheme_search_post_excerpt');
	}
}

if ( ! function_exists( 'etheme_search_post_excerpt' ) ) :
	
	function etheme_search_post_excerpt($where = ''){
		
		global $wp_the_query;
		global $wpdb;
		
		$prefix = 'wp_';
		if ( $wpdb->prefix ) {
			// current site prefix
			$prefix = $wpdb->prefix;
		} elseif ( $wpdb->base_prefix ) {
			// wp-config.php defined prefix
			$prefix = $wpdb->base_prefix;
		}
		
		// ! Filter by brands
		if ( isset( $_GET['filter_brand'] ) && ! empty($_GET['filter_brand']) ) {
			
			$ids = et_get_active_brand_ids($_GET['filter_brand']);
			
			if ($ids){
				$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id  IN (" . $ids . ") )";
			}
//			return $where;
		}
		
		$variable_products_detach = etheme_get_option('variable_products_detach', false);
//		$variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);
		
		// ! WooCommerce search query
		if (is_search()){
			if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ) return $where;
			
			$s = $wp_the_query->query_vars['s'];
			
			// ! Search by sku
			if (etheme_get_option('search_by_sku_et-desktop', 1)){
				if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) && ! defined( 'POLYLANG_PRO' ) ){
					$where .= " OR ( " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )
					AND " . $prefix . "posts.ID IN (
						SELECT ID FROM {$wpdb->prefix}posts
						LEFT JOIN {$wpdb->prefix}icl_translations ON {$wpdb->prefix}icl_translations.element_id = {$wpdb->prefix}posts.ID
						WHERE post_type = 'product'
						AND post_status = 'publish'
						AND {$wpdb->prefix}icl_translations.language_code = '". ICL_LANGUAGE_CODE ."'
					) )";
				} else {
					$where .= " OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )";
					$where .= " AND post_type = 'product' AND post_status = 'publish'";
					
					if (isset($_GET['product_cat'])) {
						$category = get_term_by( 'slug', $_GET['product_cat'], 'product_cat' );
						
						if ($category && isset($category->term_id)) {
							$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id = '".$category->term_id."' )";
						}
					}
				}
			}
			
			// ! Add product_variation to search result
			if ( etheme_get_option('search_product_variation_et-desktop', 0) || $variable_products_detach ){
//				if ( $variable_products_detach && $variable_products_no_parent ) {
//					$where .= "AND " . $prefix . "posts.ID NOT IN (SELECT posts.ID  FROM ".$prefix."posts AS posts
//                        INNER JOIN ".$prefix."term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//                        INNER JOIN ".$prefix."term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//                        INNER JOIN ".$prefix."terms AS terms ON term_taxonomy.term_id = terms.term_id
//                        WHERE
//                            term_taxonomy.taxonomy = 'product_type'
//                        AND terms.slug = 'variable')";
//                }
				$where .= " OR post_type = 'product_variation' AND post_status = 'publish' AND (
				    post_title LIKE '%$s%' OR post_excerpt LIKE '%$s%' OR post_content LIKE '%$s%'
				    OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )
				) ";
				
				if (isset($_GET['product_cat'])) {
					$category = get_term_by( 'slug', $_GET['product_cat'], 'product_cat' );
					
					if ($category && isset($category->term_id)) {
						$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id = '".$category->term_id."' )";
					}
				}
				
				//$where .= " OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )";
			}
		}
//		elseif ( $variable_products_detach ) {
//			if ( empty( $wp_the_query->query_vars['wc_query'] ) ) return $where;
//
//			$visibility    = wc_get_product_visibility_term_ids();
//			if ( $variable_products_no_parent ) {
//				$where .= "AND " . $prefix . "posts.ID NOT IN (SELECT posts.ID  FROM " . $prefix . "posts AS posts
//                    INNER JOIN " . $prefix . "term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//                    INNER JOIN " . $prefix . "term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//                    INNER JOIN " . $prefix . "terms AS terms ON term_taxonomy.term_id = terms.term_id
//                    WHERE
//                        term_taxonomy.taxonomy = 'product_type'
//                    AND terms.slug = 'variable')";
//			}
////			$where .= " OR (post_type = 'product_variation' AND post_status = 'publish')";
//			$product_visibility_terms  = wc_get_product_visibility_term_ids();
//			$where .= " OR (post_type = 'product_variation' AND post_status = 'publish' AND post_parent NOT IN (
//			    SELECT object_id FROM ".$prefix."term_relationships AS term
//    WHERE term_taxonomy_id IN (".implode(',',(array)$product_visibility_terms['exclude-from-catalog']).")))";
//
//
//        }
		
		return $where;
	}
endif;

// **********************************************************************//
// ! Footer widgets class
// **********************************************************************//
if(!function_exists('etheme_get_footer_widget_class')) {
	function etheme_get_footer_widget_class($n) {
		$class = 'col-md-';
		switch ($n) {
			case 1:
				$class .= 12;
				break;
			case 2:
				$class .= 6;
				break;
			case 3:
				$class .= 4;
				break;
			case 4:
				$class .= 3;
				$class .= ' col-sm-6';
				break;
			default:
				$class .= 3;
				break;
		}
		return $class;
	}
}

// **********************************************************************//
// ! Get activated theme
// **********************************************************************//
if( ! function_exists( 'etheme_activated_theme' ) ) {
	function etheme_activated_theme() {
		$activated_data = get_option( 'etheme_activated_data' );
		
		// auto update option for old users
		if ( isset( $activated_data['purchase'] ) && $activated_data['purchase'] && get_option( 'envato_purchase_code_15780546', 'undefined' ) === 'undefined' ) {
			update_option( 'envato_purchase_code_15780546', $activated_data['purchase'] );
			
		}
		if( isset( $activated_data['purchase'] ) && $activated_data['purchase'] && $activated_data['purchase'] != get_option( 'envato_purchase_code_15780546', false )){
			return false;
		}
		
		$theme = ( isset( $activated_data['theme'] ) && ! empty( $activated_data['theme'] ) ) ? $activated_data['theme'] : false ;
		return $theme;
	}
	
}

// **********************************************************************//
// ! Is theme activated
// **********************************************************************//
if(!function_exists('etheme_is_activated')) {
	function etheme_is_activated() {
		if ( etheme_activated_theme() != ETHEME_PREFIX ) return false;
		if ( ! get_option( 'etheme_is_activated' ) ) update_option( 'etheme_is_activated', true );
		return get_option( 'etheme_is_activated', false );
	}
}

// **********************************************************************//
// ! Get image by size function
// **********************************************************************//
if( ! function_exists('etheme_get_image') ) {
	function etheme_get_image($attach_id, $size, $location = '') {
		
		$type   = '';
		if ( !(isset($_GET['vc_editable']) || (defined( 'DOING_AJAX' ) && DOING_AJAX) || is_admin()) ) {
			$type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
		}
		
		$class = '';
		
		if ($type == 'lqip') {
			$class .= ' lazyload lazyload-lqip et-lazyload-fadeIn';
		} elseif($type == 'lazy'){
			$class .= ' lazyload lazyload-simple et-lazyload-fadeIn';
		}
		
		if (function_exists('wpb_getImageBySize')) {
			$image = wpb_getImageBySize( array(
				'attach_id' => $attach_id,
				'thumb_size' => $size,
				'class' => $class
			) );
			$image = (isset($image['thumbnail'])) ? $image['thumbnail'] : false;
			// if image was false then take the image using origin wp function
			if ( !$image )
				$image = wp_get_attachment_image( $attach_id, $size, false, array('class' => $class) );
		} elseif (!empty($size) && ( ( !is_array($size) && strpos($size, 'x') !== false ) || is_array($size) ) && defined('ELEMENTOR_PATH') ) {
			$size = is_array($size) ? $size : explode('x', $size);
			if ( ! class_exists( 'Group_Control_Image_Size' ) ) {
				require_once ELEMENTOR_PATH . '/includes/controls/groups/image-size.php';
			}
			$image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
				array(
					'image' => array(
						'id' => $attach_id,
					),
					'image_custom_dimension' => array('width' => $size[0], 'height' => $size[1]),
					'image_size' => 'custom',
					'hover_animation' => ' ' . $class
				)
			);
		}
		else {
			$image = wp_get_attachment_image( $attach_id, $size, false, array('class' => $class) );
		}
		
		if ( $type && $type != 'default' ) {
			if ( $type == 'lqip') {
				if ( $size == 'woocommerce_thumbnail' ) {
					$placeholder = wp_get_attachment_image_src( $attach_id, 'etheme-woocommerce-nimi' );
				} else {
					$placeholder = wp_get_attachment_image_src( $attach_id, 'etheme-nimi' );
				}
				if ( isset( $placeholder[0] ) ) {
					$new_attr = 'src="' . $placeholder[0] . '" data-src';
					$image    = str_replace( 'src', $new_attr, $image );
				}
			}
			else {
				
				if (function_exists('wpb_getImageBySize')) {
					$placeholder_image_id = (int)get_option( 'xstore_placeholder_image', 0 );
					$placeholder_image = wpb_getImageBySize( array(
						'attach_id' => $placeholder_image_id,
						'thumb_size' => $size,
						'class' => $class
					) );
					
					$placeholder_image = $placeholder_image['thumbnail'] ?? false;
					if ( $placeholder_image ) {
						$placeholder_image = string_between_two_string( $placeholder_image, 'src="', '" ' );
					}
					else {
						$placeholder_image = etheme_placeholder_image($size, $attach_id);
					}
					
				} else {
					$placeholder_image = etheme_placeholder_image($size, $attach_id);
				}
				
				$new_attr = 'src="' . $placeholder_image . '" data-src';
				$image    = str_replace( 'src', $new_attr, $image );
			}
			$image = str_replace( 'sizes', 'data-sizes', $image );
			
		}
		
		return $image;
	}
}

if ( !function_exists('string_between_two_string') ) {
	function string_between_two_string($str, $starting_word, $ending_word){
		$subtring_start = strpos($str, $starting_word);
		$subtring_start += strlen($starting_word);
		$size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
		return substr($str, $subtring_start, $size);
	}
}
if (! function_exists('unicode_chars')) {
	function unicode_chars( $source, $iconv_to = 'UTF-8' ) {
		$decodedStr = '';
		$pos        = 0;
		$len        = strlen( $source );
		while ( $pos < $len ) {
			$charAt     = substr( $source, $pos, 1 );
			$decodedStr .= $charAt;
			$pos ++;
		}

		if ( $iconv_to != "UTF-8" ) {
			$decodedStr = iconv( "UTF-8", $iconv_to, $decodedStr );
		}

		return $decodedStr;
	}
}
// For wpml test
apply_filters( 'wpml_current_language', NULL );

// **********************************************************************//
// ! Add custom fonts to customizer typography
// **********************************************************************//
function et_kirki_custom_fonts( $standard_fonts ){
	$etheme_fonts = get_option( 'etheme-fonts', false );
	if ( ! is_array($etheme_fonts) || count( $etheme_fonts ) < 1 ) {
		return $standard_fonts;
	}
	$custom_fonts = array();
	
	foreach ( $etheme_fonts as $value ) {
		$custom_fonts[$value['name']] = array(
			'label' => $value['name'],
			'variant' => '400',
			'stack' => '"'.$value['name'].'"'
		);
	}
	
	$std_fonts = array(
		"Arial, Helvetica, sans-serif",
		"Courier, monospace",
		"Garamond, serif",
		"Georgia, serif",
		"Impact, Charcoal, sans-serif",
		"Tahoma,Geneva, sans-serif",
		"Verdana, Geneva, sans-serif",
	);
	
	foreach ( $std_fonts as $value) {
		$custom_fonts[$value] = array(
			'label' => $value,
			'variant' => '400',
			'stack' => $value
		);
	}
	
	return array_merge_recursive( $custom_fonts, $standard_fonts );
}
add_filter( 'kirki/fonts/standard_fonts', 'et_kirki_custom_fonts', 20 );

// **********************************************************************//
// ! Visibility of next/prev pruduct
// **********************************************************************//

if ( ! function_exists('et_visible_product') ) :
	function et_visible_product( $id, $valid ){
		$product = wc_get_product( $id );
		
		// updated for woocommerce v3.0
		$visibility = $product->get_catalog_visibility();
		$stock = $product->is_in_stock();
		
		if (  $visibility  != 'hidden' &&  $visibility  != 'search' && $stock ) {
			return get_post( $id );
		}
		
		$the_query = new WP_Query( array( 'post_type' => 'product', 'p' => $id ) );
		
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$valid_post = ( $valid == 'next' ) ? get_adjacent_post( 1, '', 0, 'product_cat' ) : get_adjacent_post( 1, '', 1, 'product_cat' );
				if ( empty( $valid_post ) ) return;
				$next_post_id = $valid_post->ID;
				$visibility = wc_get_product( $next_post_id );
				$stock = $visibility->is_in_stock();
				$visibility = $visibility->get_catalog_visibility();
				
			}
			// Restore original Post Data
			wp_reset_postdata();
		}
		
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $stock ) {
			return et_visible_product( $next_post_id, $valid );
		}
		
		if ( $visibility == 'visible' || $visibility == 'catalog' && $stock ) {
			return $valid_post;
		} else {
			return et_visible_product( $next_post_id, $valid );
		}
		
	}
endif;

// **********************************************************************//
// ! Project links
// **********************************************************************//

if ( ! function_exists('etheme_project_links') ) :
	function etheme_project_links() {
		etheme_enqueue_style( 'navigation', true );
		get_template_part( 'templates/navigation', 'prev-next' );
	}
endif;

// **********************************************************************//
// ! Notice "Plugin version"
// **********************************************************************//
add_action( 'admin_notices', 'etheme_required_core_notice', 50 );
add_action( 'wp_body_open', 'etheme_required_plugin_notice_frontend', 50 );
add_action( 'admin_notices', 'etheme_api_connection_check', 60);

function etheme_api_connection_check(){
	$connection = get_transient( 'etheme_api_connection_check' );
	
	if (!$connection){
		$response = wp_remote_get( 'https://xstore.8theme.com/change-log.php?type=panel' );
		$response_code = wp_remote_retrieve_response_code( $response );
		$connection = true;
		
		if ( 200 !== $response_code ){
			echo '
                <div class="et-message et-warning">
                    <p>'.esc_html__('XStore theme can not connect to XStore API, please check you\'r SSL certificate or white lists.', 'xstore') . '</p>
                </div>
            ';
			$connection = false;
		}
		set_transient( 'etheme_api_connection_check', $connection, 10 * DAY_IN_SECONDS );
	}
}

function etheme_required_core_notice(){
	
	$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
	
	if (
		count($xstore_branding_settings)
		&& isset($xstore_branding_settings['control_panel'])
		&& isset($xstore_branding_settings['control_panel']['hide_updates'])
		&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
	){
		return;
	}
	
	
	$file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';
	
	if ( ! file_exists($file) ) return;
	
	$plugin = get_plugin_data( $file, false, false );
	
	if ( version_compare( ETHEME_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
		$video = '<a class="et-button" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank" style="color: white!important; text-decoration: none"><span class="dashicons dashicons-video-alt3" style="color: var(--et_admin_red-color, #c62828);git "></span> Video tutorial</a>';
		
		echo '
        <div class="et-message et-warning">
            This theme version requires the following plugin <strong>XStore Core</strong> to be updated up to <strong>' . ETHEME_CORE_MIN_VERSION . ' version. </strong>You can install the updated version of XStore core plugin: <ul>
                <li>1) via <a href="'.admin_url('update-core.php').'">Dashboard</a> > Updates > click Check again button > update plugin</li>
                <li>2) via FTP using archive from <a href="https://www.8theme.com/downloads" target="_blank">Downloads</a></li>
                <li>3) via FTP using archive from the full theme package downloaded from <a href="https://themeforest.net/" target="_blank">ThemeForest</a></li>
                <li>4) via <a href="https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/" target="_blank">Easy Theme and Plugin Upgrades</a> WordPress Plugin</li>
                <li>5) Don\'t Forget To Clear <strong style="color:#c62828;"> Cache! </strong></li>
                </ul>
                <br>
                ' . $video . '
                <br><br>
        </div>
    ';
	}
}

function etheme_required_plugin_notice_frontend(){
	if ( get_query_var( 'et_is-loggedin', false) && current_user_can('administrator') ) {
		
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		
		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}
		
		if( !function_exists('get_plugin_data') ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		
		$file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';
		
		if ( ! file_exists($file) ) return;
		
		$plugin = get_plugin_data( $file, false, false );
		
		if ( version_compare( ETHEME_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
			$video = '<a class="et-button et-button-active" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank"> Video tutorial</a>';
			echo '
				</br>
				<div class="woocommerce-massege woocommerce-info error">
					XStore theme requires the following plugin: <strong>XStore Core plugin v.' . ETHEME_CORE_MIN_VERSION . '.</strong>
					'.$video.'. This warning is visible for <strong>administrator only</strong>.
				</div>
			';
		}
	}
}

function etheme_get_image_sizes( $size = '' ) {
	$wp_additional_image_sizes = wp_get_additional_image_sizes();
	
	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();
	
	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
		} elseif ( isset( $wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width' => $wp_additional_image_sizes[ $_size ]['width'],
				'height' => $wp_additional_image_sizes[ $_size ]['height'],
				'crop' =>  $wp_additional_image_sizes[ $_size ]['crop']
			);
		}
	}
	
	// Get only 1 size if found
	if ( $size ) {
		if( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}
	return $sizes;
}

function etheme_get_demo_versions(){
	$versions   = get_transient( 'etheme_demo_versions_info' );
	$url        = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/1/versions/');

	if ( ! $versions || empty( $versions ) || isset($_GET['etheme_demo_versions_info']) ) {
		$api_response = wp_remote_get( $url );
		$code         = wp_remote_retrieve_response_code( $api_response );
		
		if ( $code == 200 ) {
			$api_response = wp_remote_retrieve_body( $api_response );
			$api_response = json_decode( $api_response, true );
			$versions = $api_response;
			set_transient( 'etheme_demo_versions_info', $versions, 12 * HOUR_IN_SECONDS );
		} else {
			$versions = array();
		}
	}
	return $versions;
}

add_filter( 'woocommerce_create_pages', 'etheme_do_not_setup_demo_pages', 10 );
function etheme_do_not_setup_demo_pages($args){
	if (
		isset($_REQUEST['action'])
		&& $_REQUEST['action'] == 'install_pages'
		&& isset($_REQUEST['page'])
		&& $_REQUEST['page'] == 'wc-status'
	){
		return $args;
	}
	return array();
}

add_action('init', function () {
	$placeholder_image = get_option( 'xstore_placeholder_image', 0 );
	if ( ! empty( $placeholder_image ) ) {
		if ( ! is_numeric( $placeholder_image ) ) {
			return;
		} elseif ( $placeholder_image && wp_attachment_is_image( $placeholder_image ) ) {
			return;
		}
	}
	
	$upload_dir = wp_upload_dir();
	$source     = ETHEME_BASE . 'images/lazy' . ( get_theme_mod( 'dark_styles', 0 ) ? '-dark' : '' ) . '.png';
	$filename   = $upload_dir['basedir'] . '/xstore/xstore-placeholder.png';
	
	// let's create folder if not exists
	if ( ! file_exists( $upload_dir['basedir'] . '/xstore' ) ) {
		wp_mkdir_p( $upload_dir['basedir'] . '/xstore' );
	}
	
	if ( ! file_exists( $filename ) ) {
		copy( $source, $filename ); // @codingStandardsIgnoreLine.
	}
	
	if ( ! file_exists( $filename ) ) {
		update_option( 'xstore_placeholder_image', 0 );
		return;
	}
	
	$filetype   = wp_check_filetype( basename( $filename ), null );
	$attachment = array(
		'guid'           => $upload_dir['url'] . '/' . basename( $filename ),
		'post_mime_type' => $filetype['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$attach_id  = wp_insert_attachment( $attachment, $filename );
	
	update_option( 'xstore_placeholder_image', $attach_id );
	
	// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	require_once ABSPATH . 'wp-admin/includes/image.php';
	
	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );
});

// tweak to include pagination style for shortcodes
add_filter('paginate_links_output', function ($r) {
	if (!empty($r)) {
		etheme_enqueue_style( 'pagination' );
	}
	return $r;
});

// Show xstore avatars
add_filter('get_avatar_data', 'xstore_change_avatar', 100, 2);
function xstore_change_avatar($args, $id_or_email) {
	
	$xstore_avatar = get_user_meta( $id_or_email, 'xstore_avatar', true);
	if(get_theme_mod( 'load_social_avatar_value', 'off' ) === 'on' && $xstore_avatar) {
		$args['url'] = wp_get_attachment_url($xstore_avatar);
	}
	return $args;
}

// Maintenance mode
if ( get_option('etheme_maintenance_mode', false) ) {
	
	add_action( 'template_redirect', function () {
		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'maintenance.php'
		) );
		
		$return = array();
		
		foreach ( $pages as $page ) {
			$return[] = $page->ID;
		}
		$page_id = current( $return );
		
		if ( ! $page_id ) {
			return;
		}
		
		if ( ! is_page( $page_id ) && ! get_query_var( 'et_is-loggedin', false ) ) {
			wp_redirect( get_permalink( $page_id ) );
			exit();
		}
	}, 20 );
}

add_filter('etheme_protocol_url', 'etheme_protocol_url', 10);

add_action( 'init', 'instagram_request' );
function instagram_request() {
	if( isset( $_GET['et_remove_instagram'] ) ) {
		update_option('etheme_instagram_api_data',json_encode(array()));
	}
}