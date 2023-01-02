<?php
/**
 * The template created for configurate vars in theme options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
$sep = 0;

$sep_style = 'padding: 7px 15px;background-color: #e1e1e1;color: #000;margin: 0 -15px;text-align: center;text-transform: uppercase;font-size: 14px;';

$light_sep_style = $dark_sep_style = $active_sep_style = $bordered_sep_style = 'padding: 7px 15px;margin: 0 -15px;text-align: center;font-size: 14px;text-transform: uppercase;';

$light_sep_style    .= 'background-color: #f2f2f2;color: #222;';
$dark_sep_style     .= 'background-color: #000;color: #fff;';
$active_sep_style   .= 'background-color: #c62828;color: #fff;';
$bordered_sep_style .= 'border: 1px solid #e1e1e1; color: #222; background: #fafafa;';

$priority_i = 0;

$priorities = array(
	'general'             => $priority_i ++,
	'header'              => $priority_i ++,
	'breadcrumbs'         => $priority_i ++,
	'footer'              => $priority_i ++,
	'mobile-panel'        => $priority_i ++,
	'sales-booster'       => $priority_i ++,
	'styling'             => $priority_i ++,
	'typography'          => $priority_i ++,
	'woocommerce'         => $priority_i ++,
	'shop'                => $priority_i ++,
	'single-product-page' => $priority_i ++,
	'shop-elements'       => $priority_i ++,
	'blog'                => $priority_i ++,
	'portfolio'           => $priority_i ++,
	'social-sharing'      => $priority_i ++,
	'facebook-login'      => $priority_i ++,
	'instagram-api'       => $priority_i ++,
	'mail-chimp'          => $priority_i ++,
	'google-map'          => $priority_i ++,
	'404'                 => $priority_i ++,
	'custom-css'          => $priority_i ++,
	'speed-optimization'  => $priority_i ++,
	'import-export'       => $priority_i ++
);

$sidebars = array(
	'without' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/full-width.svg',
	'left'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/left-sidebar.svg',
	'right'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/right-sidebar.svg'
);

$woocommerce_sidebars               = $sidebars;
$woocommerce_sidebars['off_canvas'] = ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/off-canvas.svg';

$single_product_layout = array(
	'small'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-small.svg',
	'default' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-medium.svg',
	'xsmall'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-thin.svg',
	'large'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-large.svg',
	'fixed'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-fixed.svg',
	'center'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-center.svg',
	'wide'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-wide.svg',
	'right'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-right.svg',
	'booking' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/Product-booking.svg',
);

$blog_layout = array(
	'default'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts1-1.svg',
	'center'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts-center.svg',
	'grid'        => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts2-1.svg',
	'grid2'       => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts2-2.svg',
	'timeline'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts5-1.svg',
	'timeline2'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Timeline2.svg',
	'small'       => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts3-1.svg',
	'chess'       => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts-chess.svg',
	'framed'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts-framed.svg',
	'with-author' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/Posts-with-author.svg',
);

$post_template = array(
	'default'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/3.svg',
	'full-width' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/2.svg',
	'large'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/1.svg',
	'large2'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/5.svg',
	'framed'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/blog/6.svg',
);

$text_color_scheme = array(
	'dark'  => esc_html__( 'Dark', 'xstore' ),
	'white' => esc_html__( 'White', 'xstore' ),
);

$text_color_scheme2 = array(
	'dark'  => $text_color_scheme['dark'],
	'light' => esc_html__( 'Light', 'xstore' ),
);

$shopping_carts = array(
	1 => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-1.svg',
	5 => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-0.svg',
	2 => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-3.svg',
	3 => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-4.svg',
	4 => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-2.svg'
);

$product_settings = array(
	'view'       => array(
		'disable' => esc_html__( 'Disable', 'xstore' ),
		'default' => esc_html__( 'Default', 'xstore' ),
		'mask3'   => esc_html__( 'Buttons on hover middle', 'xstore' ),
		'mask'    => esc_html__( 'Buttons on hover bottom', 'xstore' ),
		'mask2'   => esc_html__( 'Buttons on hover right', 'xstore' ),
		'info'    => esc_html__( 'Information mask', 'xstore' ),
		'booking' => esc_html__( 'Booking', 'xstore' ),
		'light'   => esc_html__( 'Light', 'xstore' ),
		'overlay' => esc_html__( 'Overlay content on image', 'xstore' ),
		'custom'  => esc_html__( 'Custom', 'xstore' )
	),
	'view_color' => array(
		'white'       => esc_html__( 'White', 'xstore' ),
		'dark'        => esc_html__( 'Dark', 'xstore' ),
		'transparent' => esc_html__( 'Transparent', 'xstore' )
	),
	'img_hover'  => array(
		'disable' => esc_html__( 'Disable', 'xstore' ),
		'swap'    => esc_html__( 'Swap', 'xstore' ),
		'slider'  => esc_html__( 'Images Slider', 'xstore' ),
        'carousel'  => esc_html__( 'Automatic Carousel', 'xstore' ),
	)
);

$paddings_empty = $paddings_top_bottom_empty = array(
	'padding-top'    => '',
	'padding-right'  => '',
	'padding-bottom' => '',
	'padding-left'   => '',
);

unset( $paddings_top_bottom_empty['padding-right'] );
unset( $paddings_top_bottom_empty['padding-left'] );

$padding_labels = $padding_top_bottom_labels = array(
	'padding-top'    => esc_html__( 'Padding top', 'xstore' ),
	'padding-right'  => esc_html__( 'Padding right', 'xstore' ),
	'padding-bottom' => esc_html__( 'Padding bottom', 'xstore' ),
	'padding-left'   => esc_html__( 'Padding left', 'xstore' ),
);

unset( $padding_top_bottom_labels['padding-right'] );
unset( $padding_top_bottom_labels['padding-left'] );

$borders_empty = array(
	'border-top'    => '',
	'border-right'  => '',
	'border-bottom' => '',
	'border-left'   => '',
);

$border_labels = array(
	'border-top'    => esc_html__( 'Border top', 'xstore' ),
	'border-right'  => esc_html__( 'Border right', 'xstore' ),
	'border-bottom' => esc_html__( 'Border bottom', 'xstore' ),
	'border-left'   => esc_html__( 'Border left', 'xstore' ),
);

$border_styles = array(
	'solid'  => esc_html__( 'Solid', 'xstore' ),
	'dashed' => esc_html__( 'Dashed', 'xstore' ),
	'dotted' => esc_html__( 'Dotted', 'xstore' ),
	'double' => esc_html__( 'Double', 'xstore' ),
	'none'   => esc_html__( 'None', 'xstore' ),
);

$border_radius = array(
	'border-top-left-radius'     => '',
	'border-top-right-radius'    => '',
	'border-bottom-right-radius' => '',
	'border-bottom-left-radius'  => '',
);

$border_radius_labels = array(
	'border-top-left-radius'     => esc_html__( 'Border top left radius', 'xstore' ),
	'border-top-right-radius'    => esc_html__( 'Border top right radius', 'xstore' ),
	'border-bottom-right-radius' => esc_html__( 'Border bottom right radius', 'xstore' ),
	'border-bottom-left-radius'  => esc_html__( 'Border bottom left radius', 'xstore' ),
);

function theme_box_model_output( $selector ) {
	$properties = array(
		'margin-top',
		'margin-right',
		'margin-bottom',
		'margin-left',
		
		'padding-top',
		'padding-right',
		'padding-bottom',
		'padding-left',
		
		'border-top-width',
		'border-right-width',
		'border-bottom-width',
		'border-left-width',
	);
	
	$return = array();
	
	foreach ( $properties as $key ) {
		$return[] = array(
			'choice'   => $key,
			'element'  => $selector,
			'type'     => 'css',
			'property' => $key
		);
	}
	
	return $return;
}

$light_buttons = array(
	'regular' => '.btn:not(.black):not(.active):not(.bordered):not(.style-custom), .content-product .product-details .button, .content-product .quantity-wrapper .button.et-st-disabled:hover, .woocommerce-Button, .et_load-posts .btn a, .sb-infinite-scroll-load-more:not(.finished) a, form #qna-ask-input button, body #wcmp-store-conatiner input[type="submit"]',
	'hover'   => '.btn:not(.black):not(.active):not(.bordered):not(.style-custom):hover, .content-product .product-details .button:hover, .woocommerce-Button:hover, .et_load-posts .btn a:hover, .sb-infinite-scroll-load-more:not(.finished) a:hover, form #qna-ask-input button:hover, body #wcmp-store-conatiner input[type="submit"]:hover',
);

$bordered_buttons = array(
	'regular' => '.btn.bordered, .btn.bordered.small, .btn.bordered.medium, .btn.bordered.big',
	'hover'   => '.btn.bordered:hover',
);

$bordered_buttons['hover'] = $bordered_buttons['hover'] . ',' . str_replace( ':hover', ':focus', $bordered_buttons['hover'] );

$dark_buttons = array(
	'regular' => '.btn.small.black,
                        .btn.medium.black, 
                        .btn.big.black, 
                        .before-checkout-form .button, 
                        .checkout-button, .shipping-calculator-form .button, 
                        .single_add_to_cart_button.button,
                        .single_add_to_cart_button.button:focus,
                        .single_add_to_cart_button.button.disabled,
                        .single_add_to_cart_button.button.disabled:hover,
                        .et-quick-view-wrapper .single_add_to_cart_button.button,
                        .et-quick-view-wrapper .single_add_to_cart_button.button:focus,
                        .et-quick-view-wrapper .single_add_to_cart_button.button.disabled,
                        .et-quick-view-wrapper .single_add_to_cart_button.button.disabled:hover,
                        form.login .button,
                        form.register .button, form.register .button.woocommerce-Button,
                        form.lost_reset_password .button,
                        .woocommerce-EditAccountForm .woocommerce-Button,
                        .empty-cart-block .btn,
                        .empty-wishlist-block .btn,
                        .empty-compare-block .btn,
                        .empty-category-block .btn,
                        .woocommerce-mini-cart__empty-message .btn,
                        .form-submit input[type="submit"],
                        #commentform input[type="button"],
                        .form-submit input[type="submit"]:focus,
                        .my_account_orders .view,
                        .et-quick-view-wrapper .product_type_variable,
                        .et-quick-view-wrapper .product_type_variation,
                        .coupon input[type="submit"], 
                        .widget_search button, 
                        .widget_product_search button, 
                        .woocommerce-product-search button,
                        form.wpcf7-form .wpcf7-submit:not(.active),
                        .woocommerce table.wishlist_table td.product-add-to-cart a,
                        .wcmp-quick-info-wrapper form input[type=submit],
                        
                        .product_list_widget .buttons a,
                        .et-wishlist-widget .wishlist-dropdown .buttons .btn-view-wishlist,
                        .btn-checkout,
                        .mini-cart-buttons .button:not(.btn-checkout),
                        .mini-cart-buttons a, .form-row.place-order .button,
                        .search-full-width form .btn,
                        .xstore-wishlist-action .button,
                        .xstore-compare-button .button,
                        .form-actions .add-all-products,
                        .form-actions .compare-more-products,
                        .form-actions .xstore-wishlist-actions,
                        .form-actions .xstore-compare-actions',
	'hover'   => '.btn.small.black:hover,
                        .btn.medium.black:hover,
                        .btn.big.black:hover,
                        .before-checkout-form .button:hover,
                        .checkout-button:hover, .shipping-calculator-form .button:hover,
                        .single_add_to_cart_button.button:hover,
                        .single_add_to_cart_button.button:hover:focus,
                        .et-quick-view-wrapper .single_add_to_cart_button.button:hover,
                        .et-quick-view-wrapper .single_add_to_cart_button.button:hover:focus,
                        form.login .button:hover,
                        form.register .button:hover, form.register .button.woocommerce-Button:hover,
                        form.lost_reset_password .button:hover,
                        .woocommerce-EditAccountForm .woocommerce-Button:hover,
                        .empty-cart-block .btn:hover,
                        .empty-wishlist-block .btn:hover,
                        .empty-compare-block .btn:hover,
                        .empty-category-block .btn:hover,
                        .woocommerce-mini-cart__empty-message .btn:hover,
                        .form-submit input[type="submit"]:hover,
                        #commentform input[type="button"]:hover,
                        .my_account_orders .view:hover,
                        .et-quick-view-wrapper .product_type_variable:hover,
                        .et-quick-view-wrapper .product_type_variation:hover,
                        .coupon input[type="submit"]:hover,
                        .widget_search button:hover,
                        .widget_product_search button:hover,
                        .widget_search button:hover,
                        .woocommerce-product-search button:hover, form.wpcf7-form .wpcf7-submit:not(.active):hover,
                        .woocommerce table.wishlist_table td.product-add-to-cart a:hover,
                        .wcmp-quick-info-wrapper form input[type=submit]:hover,
                        
                        .product_list_widget .buttons a:hover,
                        .et-wishlist-widget .wishlist-dropdown .buttons .btn-view-wishlist:hover,
                        .btn-checkout:hover,
                        .mini-cart-buttons .button:not(.btn-checkout):hover,
                        .mini-cart-buttons a, .form-row.place-order .button:hover,
                        .search-full-width form .btn:hover,
                        .xstore-wishlist-action .button:hover,
                        .xstore-compare-button .button:hover,
                        .form-actions .add-all-products:hover,
                        .form-actions .compare-more-products:hover,
                        .form-actions .xstore-wishlist-actions:hover,
                        .form-actions .xstore-compare-actions:hover',
);

$dark_buttons['hover'] = $dark_buttons['hover'] . ',' . str_replace( ':hover', ':focus', $dark_buttons['hover'] );

$active_buttons = array(
	'regular' => '.btn.active, .button.active, input[type="submit"].dokan-btn-success, a.dokan-btn-success, .dokan-btn-success, .dokan-dashboard-content .add_note',
);

$active_buttons['hover']          = explode( ',', $active_buttons['regular'] );
$active_buttons['hover_rendered'] = array();
foreach ( $active_buttons['hover'] as $selector ) {
	$active_buttons['hover_rendered'][] = $selector . ':hover';
}
$active_buttons['hover'] = implode( ',', $active_buttons['hover_rendered'] );
unset( $active_buttons['hover_rendered'] );

$active_buttons['hover'] = $active_buttons['hover'] . ',' . str_replace( ':hover', ':focus', $active_buttons['hover'] );


function et_customizer_get_posts( $args ) {
	if ( is_string( $args ) ) {
		$args = add_query_arg(
			array(
				'suppress_filters' => false,
			)
		);
	} elseif ( is_array( $args ) && ! isset( $args['suppress_filters'] ) ) {
		$args['suppress_filters'] = false;
	}
	
	// Get the posts.
	// TODO: WordPress.VIP.RestrictedFunctions.get_posts_get_posts.
	$posts = get_posts( $args );
	
	// Properly format the array.
	$items = array();
	foreach ( $posts as $post ) {
		$items[ $post->ID ] = $post->post_title;
	}
	wp_reset_postdata();
	
	return $items;
}