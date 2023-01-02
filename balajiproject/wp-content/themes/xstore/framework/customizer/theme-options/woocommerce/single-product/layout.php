<?php
/**
 * The template created for displaying single product layout options
 *
 * @version 0.0.2
 * @since   6.0.0
 * @log     0.0.2
 * ADDED: buy_now_btn
 * ADDED: show single stock
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-layout' => array(
			'name'       => 'single-product-page-layout',
			'title'      => esc_html__( 'Single product layout', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-schedule',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-layout' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $single_product_layout, $sidebars ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'single_layout' => array(
			'name'        => 'single_layout',
			'type'        => 'radio-image',
			'settings'    => 'single_layout',
			'label'       => esc_html__( 'Page Layout', 'xstore' ),
			'description' => esc_html__( 'Choose the layout type for the single product page.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'default',
			'choices'     => $single_product_layout,
		),
		
		'single_sidebar' => array(
			'name'        => 'single_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'single_sidebar',
			'label'       => esc_html__( 'Sidebar position', 'xstore' ),
			'description' => esc_html__( 'Choose the position of the sidebar for the single product page.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'without',
			'choices'     => $sidebars,
		),
		
		'single_product_hide_sidebar' => array(
			'name'        => 'single_product_hide_sidebar',
			'type'        => 'toggle',
			'settings'    => 'single_product_hide_sidebar',
			'label'       => esc_html__( 'Hide sidebar on mobile', 'xstore' ),
			'description' => esc_html__( 'Turn on to hide sidebar on the mobile devices.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 0,
		),
		
		'fixed_images' => array(
			'name'            => 'fixed_images',
			'type'            => 'toggle',
			'settings'        => 'fixed_images',
			'label'           => esc_html__( 'Fixed product image', 'xstore' ),
			'description'     => esc_html__( 'Turn on to make the product image sticky. If the fixed product content option is enabled then keep this option disabled.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'single_layout',
					'operator' => 'in',
					'value'    => array( 'small', 'default', 'xsmall', 'wide', 'right' ),
				),
			)
		),
		
		'fixed_content' => array(
			'name'            => 'fixed_content',
			'type'            => 'toggle',
			'settings'        => 'fixed_content',
			'label'           => esc_html__( 'Fixed product content', 'xstore' ),
			'description'     => esc_html__( 'Turn on to make the product content sticky and image - scrollable. If the fixed product image option is enabled then keep this option disabled.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'single_layout',
					'operator' => 'in',
					'value'    => array( 'small', 'default', 'xsmall', 'wide', 'right' ),
				),
			)
		),
		
		'product_name_signle'            => array(
			'name'        => 'product_name_signle',
			'type'        => 'toggle',
			'settings'    => 'product_name_signle',
			'label'       => esc_html__( 'Move product name in breadcrumbs', 'xstore' ),
			'description' => esc_html__( 'Turn on to show the product title in breadcrumbs only and remove from the single product content.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 0,
		),
		
		// product_breadcrumbs_product_title_duplicated
		'product_name_single_duplicated' => array(
			'name'            => 'product_name_single_duplicated',
			'type'            => 'toggle',
			'settings'        => 'product_name_single_duplicated',
			'label'           => esc_html__( 'Leave product name in main content too', 'xstore' ),
			'description'     => esc_html__( 'Turn on to show the product title in content too.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_name_signle',
					'operator' => '!=',
					'value'    => 0
				)
			),
		),
		
		'share_icons' => array(
			'name'        => 'share_icons',
			'type'        => 'toggle',
			'settings'    => 'share_icons',
			'label'       => esc_html__( 'Show share buttons', 'xstore' ),
			'description' => esc_html__( 'Turn on to show the share buttons.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'ajax_add_to_cart' => array(
			'name'        => 'ajax_add_to_cart',
			'type'        => 'toggle',
			'settings'    => 'ajax_add_to_cart',
			'label'       => esc_html__( 'AJAX add to cart for simple and variable products', 'xstore' ),
			'description' => esc_html__( 'Turn on to enable adding to cart without page refresh for the simple and variable products. Important: third party plugins may have conflict with this.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'product_zoom' => array(
			'name'        => 'product_zoom',
			'type'        => 'toggle',
			'settings'    => 'product_zoom',
			'label'       => esc_html__( 'Zoom for product images', 'xstore' ),
			'description' => esc_html__( 'Turn on to enable the WooCommerce zoom feature. Important: Every product image you use must be larger than the image container for zoom to work correctly.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'thumbs_slider_mode' => array(
			'name'        => 'thumbs_slider_mode',
			'type'        => 'select',
			'settings'    => 'thumbs_slider_mode',
			'label'       => esc_html__( 'Product gallery slider', 'xstore' ),
			'description' => esc_html__( 'Turn on to display slider for the product gallery images.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'enable',
			'choices'     => array(
				'enable'     => esc_html__( 'Enable', 'xstore' ),
				'enable_mob' => esc_html__( 'Enable for mobile', 'xstore' ),
				'disable'    => esc_html__( 'Disable', 'xstore' )
			),
		),
		
		'thumbs_autoheight' => array(
			'name'            => 'thumbs_autoheight',
			'type'            => 'toggle',
			'settings'        => 'thumbs_autoheight',
			'label'           => esc_html__( 'Product gallery slider auto height', 'xstore' ),
			'description'     => esc_html__( 'Turn on to enable auto height for the slider of the product gallery images.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '1',
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
			)
		),
		
		'product_video_position' => array(
			'name'        => 'product_video_position',
			'type'        => 'select',
			'settings'    => 'product_video_position',
			'label'       => esc_html__( 'Product video position', 'xstore' ),
			'description' => esc_html__( 'Select position of product video for products which have at least one.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'last',
			'choices'     => array(
				'first' => esc_html__( 'First', 'xstore' ),
				'last'  => esc_html__( 'Last', 'xstore' ),
			),
		),
		
		'stretch_product_slider' => array(
			'name'            => 'stretch_product_slider',
			'type'            => 'toggle',
			'settings'        => 'stretch_product_slider',
			'label'           => esc_html__( 'Stretch main slider', 'xstore' ),
			'description'     => esc_html__( 'Turn on to enable slider stretch. Enabling stretch will display full with carousel and parts of previous and next gallery images on carousel sides.  If "On" then thumbnails won\'t be shown.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'single_layout',
					'operator' => '==',
					'value'    => 'large',
				),
			)
		),
		
		'thumbs_slider_vertical' => array(
			'name'            => 'thumbs_slider_vertical',
			'type'            => 'select',
			'settings'        => 'thumbs_slider_vertical',
			'label'           => esc_html__( 'Thumbnails', 'xstore' ),
			'description'     => esc_html__( 'Choose the direction of the gallery thumbnails or disable them at all.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 'horizontal',
			'choices'         => array(
				'horizontal' => esc_html__( 'Horizontal', 'xstore' ),
				'vertical'   => esc_html__( 'Vertical', 'xstore' ),
				'disable'    => esc_html__( 'Disable', 'xstore' )
			),
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
			)
		),
		
		'count_slides' => array(
			'name'            => 'count_slides',
			'type'            => 'slider',
			'settings'        => 'count_slides',
			'label'           => esc_html__( 'Number of slides per view', 'xstore' ),
			'description'     => esc_html__( 'Choose the number of slides.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 4,
			'choices'         => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
				array(
					'setting'  => 'thumbs_slider_vertical',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		// 'single_wishlist_type'	=> array(
		// 'name'		  => 'single_wishlist_type',
		// 	'type'        => 'select',
		// 	'settings'    => 'single_wishlist_type',
		// 	'label'       => esc_html__( 'Wishlist type', 'xstore' ),
		// 	'description' => esc_html__( 'Only for "Use shortcode" wislist position', 'xstore'),
		// 	'section'     => 'single-product-page-layout',
		// 	'default'     => 'icon',
		// 	'choices'     => array(
		// 		'icon' => esc_html__( 'Icon', 'xstore' ),
		//               'icon-text' => esc_html__( 'Icon + text', 'xstore' ),
		// 	),
		// ),
		
		// 'single_wishlist_position'	=> array(
		// 'name'		  => 'single_wishlist_position',
		// 	'type'        => 'select',
		// 	'settings'    => 'single_wishlist_position',
		// 	'label'       => esc_html__( 'Wishlist position', 'xstore' ),
		// 	'description' => esc_html__( 'Only for "Use shortcode" wislist position', 'xstore'),
		// 	'section'     => 'single-product-page-layout',
		// 	'default'     => 'after',
		// 	'choices'     => array(
		// 		'after' => esc_html__( 'After "add to cart" button', 'xstore' ),
		//               'under' => esc_html__( 'Under "add to cart" button', 'xstore' ),
		// 	),
		// ),
		
		'upsell_location' => array(
			'name'        => 'upsell_location',
			'type'        => 'select',
			'settings'    => 'upsell_location',
			'label'       => esc_html__( 'Location of upsell products', 'xstore' ),
			'description' => esc_html__( 'Controls the location of the up-sell products slider. If use "Sidebar" be sure that sidebar is enabled for the single product page.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'sidebar',
			'choices'     => array(
				'sidebar'       => esc_html__( 'Sidebar', 'xstore' ),
				'after_content' => esc_html__( 'After content', 'xstore' ),
				'none'          => esc_html__( 'None', 'xstore' ),
			),
		),
		
		'cross_sell_location' => array(
			'name'        => 'cross_sell_location',
			'type'        => 'select',
			'settings'    => 'cross_sell_location',
			'label'       => esc_html__( 'Location of cross-sells products', 'xstore' ),
			'description' => esc_html__( 'Controls the location of the cross-sells products slider. If use "Sidebar" be sure that sidebar is enabled for the single product page.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'none',
			'choices'     => array(
				'sidebar'       => esc_html__( 'Sidebar', 'xstore' ),
				'after_content' => esc_html__( 'After content', 'xstore' ),
				'none'          => esc_html__( 'None', 'xstore' ),
			),
		),
		
		'product_posts_links' => array(
			'name'        => 'product_posts_links',
			'type'        => 'toggle',
			'settings'    => 'product_posts_links',
			'label'       => esc_html__( 'Show Next/Previous product navigation', 'xstore' ),
			'description' => esc_html__( 'Turn on to show the navigation to next and previous product.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'size_guide_type' => array(
			'name'     => 'size_guide_type',
			'type'     => 'radio-buttonset',
			'settings' => 'size_guide_type',
			'label'    => esc_html__( 'Size guide type', 'xstore' ),
			'section'  => 'single-product-page-layout',
			'default'  => 'popup',
			'multiple' => 1,
			'choices'  => array(
				'popup'           => esc_html__( 'Lightbox', 'xstore' ),
				'download_button' => esc_html__( 'Download Button', 'xstore' ),
			),
		),
		
		'size_guide_img' => array(
			'name'            => 'size_guide_img',
			'type'            => 'image',
			'settings'        => 'size_guide_img',
			'label'           => esc_html__( 'Size guide image', 'xstore' ),
			'description'     => esc_html__( 'Upload size guide image to show size guide link and size guide image in lightbox after click.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'size_guide_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		'size_guide_file' => array(
			'name'            => 'size_guide_file',
			'type'            => 'upload',
			'settings'        => 'size_guide_file',
			'label'           => esc_html__( 'File', 'xstore' ),
			'description'     => esc_html__( 'Upload size guide file.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'active_callback' => array(
				array(
					'setting'  => 'size_guide_type',
					'operator' => '==',
					'value'    => 'download_button',
				),
			),
		),
		
		'sticky_added_to_cart_message'                           => array(
			'name'      => 'sticky_added_to_cart_message',
			'type'      => 'toggle',
			'settings'  => 'sticky_added_to_cart_message',
			'label'     => esc_html__( 'Fixed added to cart message', 'xstore' ),
			'section'   => 'single-product-page-layout',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => 'body.single-product',
					'function' => 'toggleClass',
					'class'    => 'sticky-message-on',
					'value'    => true
				),
				array(
					'element'  => 'body.single-product',
					'function' => 'toggleClass',
					'class'    => 'sticky-message-off',
					'value'    => false
				),
			),
		),
		
		// stretch_add_to_cart
		'stretch_add_to_cart_et-desktop'                         => array(
			'name'     => 'stretch_add_to_cart_et-desktop',
			'type'     => 'toggle',
			'settings' => 'stretch_add_to_cart_et-desktop',
			'label'    => esc_html__( 'Stretch add to cart button', 'xstore' ),
			'section'  => 'single-product-page-layout',
			'default'  => 0,
		),
		
		// sticky_add_to_cart
		'sticky_add_to_cart_et-desktop'                          => array(
			'name'      => 'sticky_add_to_cart_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'sticky_add_to_cart_et-desktop',
			'label'     => esc_html__( 'Sticky add to cart bar', 'xstore' ),
			'section'   => 'single-product-page-layout',
			'default'   => 0,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.etheme-sticky-cart',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
				array(
					'element'  => '.etheme-sticky-cart',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
		),
		
		// show single stock
		'show_single_stock'                                      => array(
			'name'     => 'show_single_stock',
			'type'     => 'toggle',
			'settings' => 'show_single_stock',
			'label'    => esc_html__( 'Show product stock status', 'xstore' ),
			'section'  => 'single-product-page-layout',
			'default'  => 0,
		),
		
		// buy now btn
		'buy_now_btn'                                            => array(
			'name'     => 'buy_now_btn',
			'type'     => 'toggle',
			'settings' => 'buy_now_btn',
			'label'    => esc_html__( 'Buy Now Button', 'xstore' ),
			'section'  => 'single-product-page-layout',
			'default'  => 0,
		),
		
		// style separator
		'buy_now_btn_style_separator'                            => array(
			'name'            => 'buy_now_btn_style_separator',
			'type'            => 'custom',
			'settings'        => 'buy_now_btn_style_separator',
			'section'         => 'single-product-page-layout',
			'default'         => '<div style="display: flex; justify-content: center; align-items: center; padding: 7px 15px;margin: 0 -15px;text-align: center;font-size: 12px;line-height: 1;text-transform: uppercase; letter-spacing: 1px;background-color: #f2f2f2;color: #222;"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-left: 3px;">' . esc_html__( 'Buy now styles', 'xstore' ) . '</span></div>',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// product_cart_buy_now_color
		'product_cart_buy_now_color_et-desktop'                  => array(
			'name'            => 'product_cart_buy_now_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_color_et-desktop',
			'label'           => esc_html__( 'Button color', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-color'
				),
			),
		),
		
		// product_cart_buy_now_background_color
		'product_cart_buy_now_background_color_et-desktop'       => array(
			'name'            => 'product_cart_buy_now_background_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_background_color_et-desktop',
			'label'           => esc_html__( 'Button background color', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#339438',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-background-color'
				),
			),
		),
		
		// product_cart_buy_now_color_hover
		'product_cart_buy_now_color_hover_et-desktop'            => array(
			'name'            => 'product_cart_buy_now_color_hover_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_color_hover_et-desktop',
			'label'           => esc_html__( 'Button color (hover)', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-color-hover',
				),
			),
		),
		
		// product_cart_buy_now_background_color_hover
		'product_cart_buy_now_background_color_hover_et-desktop' => array(
			'name'            => 'product_cart_buy_now_background_color_hover_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_background_color_hover_et-desktop',
			'label'           => esc_html__( 'Button background color (hover)', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#2e7d32',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-background-color-hover'
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );