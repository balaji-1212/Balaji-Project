// header elements 

(function($, api) {

	var $document = $(document);

	api.bind('preview-ready', function() {
		var target = window.parent === window ? null : window.parent;

		var elements = {
			'.top-bar' : { 'title': 'Top bar', 'section' : '' },
			'.header-wrapper .main-header' : { 'title': 'Header', 'section' : '' },
			'.header-logo' : { 'title': 'Logo', 'section' : 'logo' },
			'.menu-wrapper' : { 'title': 'Main menu', 'section' : 'main_menu' },
			'.header-bg-block .secondary-menu-wrapper' : { 'title': 'Secondary menu', 'section' : 'main_menu_2' },
			'.site-header .et_b_header-menu .nav-sublist-dropdown' : { 'title': 'Dropdown menu', 'section' : 'secondary_menu' },
			'.shopping-container' : { 'title': 'Cart', 'section' : 'cart_off' },
			'.et-wishlist-widget' : { 'title': 'Wishlist', 'section' : 'wishlist' },
			'.navbar-header .login-link' : { 'title': 'Sign in', 'section' : 'account' },
			'.header-search' : { 'title': 'Search', 'section' : 'search' },
			'.popup_link:not(.et_b_element)' : { 'title': 'Promo popup', 'section' : 'promo_text' },
			'.page-heading, .cart-checkout-nav' : { 'title': 'Breadcrumbs', 'section' : 'breadcrumbs' },

			'.footer' : { 'title': 'Footer', 'section' : 'footer-styling' },
			'.footer-bottom' : { 'title': 'Copyrights', 'section' : 'copyright-styling' },
			
			'.product-category' : { 'title': 'Categories', 'section' : 'shop-categories' },
			'.content-product' : { 'title': 'Product', 'section' : 'products-style' },

			'.filter-wrap' : { 'title': 'Shop toolbar', 'section' : 'shop-page-filters' },
			
			'.st-swatch-in-loop, .st-swatch-preview-single-product' : { 'title': 'Variation swatches', 'section' : 'shop-color-swatches' },

			'.cart-empty' : { 'title': 'Empty cart', 'section' : 'empty-cart' },

			'.product_brand, .products-page-brands' : { 'title': 'Brands', 'section' : 'shop-brands' },

			'.product-information .product-sale-counter, .et_product-block .product-sale-counter' : { 'title': 'Countdown', 'section' : 'single-product-page-countdown' },

			'.content-product .sale-wrapper, .content-product .stock, .content-product .available-on-backorder' : { 'title': 'Sale & Out of stock', 'section' : 'shop-icons' },

			'.single .type-product > .related-products' : { 'title': 'Related products', 'section' : 'shop-related-products' },
			'.single.single-product .woocommerce-tabs' : { 'title': 'Tabs', 'section' : 'product_tabs' },
			'.et-request-quote-popup' : { 'title' : 'Request a quote popup', 'section' : 'single-request-quote' },
			'.et-request-quote' : { 'title': 'Request a quote', 'section' : 'single-request-quote' },

			'.woocommerce-checkout' : { 'title': 'Checkout', 'section' : '' },

			'.product-share, .share-post' : { 'title': 'Socials', 'section' : 'product_sharing' },

			'.page-404' : { 'title': '404 page', 'section' : 'general-page-not-found' },

			'.sidebar-widget[data-customize-partial-id]' : { 'title': 'Sidebar widget', 'section' : 'single_product_layout' },
			'.footer-widget[data-customize-partial-id]:not(.etheme_widget_satick_block)' : { 'title': 'Footer widget', 'section' : '' },

			'.topbar-widget[data-customize-partial-id]' : { 'title': 'Top bar widget', 'section' : '' },
			'.top-panel-widget[data-customize-partial-id]' : { 'title': 'Top panel widget', 'section' : '' },
			'.mobile-sidebar-widget' : { 'title': 'Mobile menu widget', 'section' : '' },
			'.header-banner-widget' : { 'title': 'Header banner widget', 'section' : '' },
			'.copyrights-widget[data-customize-partial-id]' : { 'title': 'Copyrights widget', 'section' : '' },

			// single product elements
			'.single-product-builder h1.product_title' : { 'title' : 'Product title', 'section' : 'product_title' },
			'.product-content p.price:not(.price-in-nav), .et_product-block > .price, .et_element > .price' : { 'title' : 'Product price', 'section' : 'product_price' },
			'.single-product-builder .product_meta' : { 'title' : 'Product meta', 'section' : 'product_meta' },
			'.single-product-builder form.cart' : { 'title' : 'Form cart', 'section' : 'product_cart_form' },
			'.single-product-builder .woocommerce-product-gallery.images-wrapper' : { 'title' : 'Product gallery', 'section' : 'product_gallery' },
			'.single-product-builder .woocommerce-product-details__short-description' : { 'title' : 'Short description', 'section' : 'product_short_description' },
			'.single-product-builder .woocommerce-product-rating' : { 'title' : 'Product rating', 'section' : 'product_rating' },
			'.single-product-builder .woocommerce-breadcrumb-wrapper' : { 'title' : 'Breadcrumbs', 'section' : 'product_breadcrumbs' },
			'.single-wishlist' : { 'title' : 'Wishlist', 'section' : 'product_wishlist' },
			'.single-compare' : { 'title' : 'Compare', 'section' : 'product_compare' },
			'.single-product-builder .bought-together-products' : { 'title' : 'Bought Together', 'section' : 'single-bought-together' },
			'.et_product-block .upsell-products' : { 'title' : 'Upsell products', 'section' : 'products_upsell' },
			'.et_product-block .cross-sell-products' : { 'title' : 'Cross-sell products', 'section' : 'products_cross_sell' },
			'.et_product-block .related-products' : { 'title' : 'Related products', 'section' : 'products_related' },

			// woocommerce
			'.woocommerce-cart .cross-sell-products' : { 'title' : 'Cross-sell products', 'section' : 'cart-cross-sell' },
		};

		if ( jQuery('body').hasClass('cart-checkout-advanced-layout') ) {
			if ( jQuery('body').hasClass('cart-checkout-light-header') ) {
				jQuery('.et_b_header-logo').attr('data-element', 'cart-checkout-layout');
			}
			if ( jQuery('body').hasClass('cart-checkout-light-footer') ) {
				elements['.footer'].section = 'cart-checkout-layout';
			}
		}

		jQuery.each(elements, function(el, item) {
			jQuery(document).on( 'mouseenter', el, function(e){
				jQuery(this).addClass('et-element-active');
				jQuery(this).prepend('<div class="et_edit-shortcut" data-element="'+item.section+'"><span class="dashicons dashicons-admin-generic"></span><span class="et-title">'+item.title+'</span></div>');
			});
			jQuery(document).on( 'mouseleave', el, function(e){
				jQuery(this).removeClass('et-element-active');
				jQuery('.et_edit-shortcut').remove();
			});
		});

		jQuery(document).on( 'mouseenter', '.header-top, .header-main, .header-bottom', function(e){
			jQuery(this).addClass('et-element-active');
			jQuery(this).prepend('<div class="et_edit-shortcut"><span class="dashicons dashicons-admin-generic"></span><span class="et-title">' + jQuery(this).data( 'title' ) + '</span></div>');
		});

		jQuery(document).on( 'mouseleave', '.header-top, .header-main, .header-bottom', function(e){
			jQuery(this).removeClass('et-element-active');
			jQuery('.et_edit-shortcut').remove();
		});
		jQuery(document).on( 'mouseenter', '.et_element:not(.et_connect-block, .woocommerce-tabs), #header-vertical, ' +
			'.et-mobile-panel-wrapper, ' +
			'.xstore-wishlist-form, .xstore-wishlist-page .empty-wishlist-block, .xstore-compare-form, .xstore-compare-page .empty-compare-block, .et-call-popup[data-type="ask-wishlist-estimate"], .xstore-wishlist-share', function(e){
			jQuery(this).addClass('et-element-active');
			jQuery(this).prepend('<div class="et_edit-shortcut"><span class="dashicons dashicons-admin-generic"></span><span class="et-title">' + jQuery(this).data( 'title' ) + '</span></div>');
		});

		jQuery(document).on( 'mouseleave', '.et_element:not(.et_connect-block), #header-vertical, .et-mobile-panel-wrapper', function(e){
			jQuery(this).removeClass('et-element-active');
			jQuery(this).find('.et_edit-shortcut').remove();
		});

		jQuery(document).on( 'mouseenter', '.et_element.et_connect-block', function(e){
			jQuery(this).addClass('et-element-active builder-connect-block');
		});

		jQuery(document).on( 'mouseleave', '.et_element.et_connect-block', function(e){
			jQuery(this).removeClass('et-element-active builder-connect-block');
		});

		$document.on('click', '.et_edit-shortcut', function(e) {
				e.preventDefault();
				var section_id = $(this).parents('[data-element]').attr('data-element') || '';

				if (!section_id) {
					section_id = $(this).attr('data-element') || '';					
				}

				if (!section_id) {
					if ( $(this).parent().hasClass('header-main') ) {
						section_id = 'main_header';
						if ( $('body').hasClass('cart-checkout-light-header') ) {
							section_id = 'cart-checkout-layout';
						}
					}					
					if ( $(this).parent().hasClass('header-top') ) {
						section_id = 'top_header';
					}
					if ( $(this).parent().hasClass('header-bottom') ) {
						section_id = 'bottom_header';
					}
				}

				if (section_id && target.wp.customize.section(section_id) ){
					target.wp.customize.section(section_id).focus();
				}

			}
		);

	});

})(jQuery, wp.customize);