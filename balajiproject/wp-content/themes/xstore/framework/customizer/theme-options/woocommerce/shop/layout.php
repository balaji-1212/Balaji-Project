<?php
/**
 * The template created for displaying shop page options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section shop-page
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-page' => array(
			'name'       => 'shop-page',
			'title'      => esc_html__( 'Shop page Layout', 'xstore' ),
			'panel'      => 'shop',
			'icon'       => 'dashicons-schedule',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-page' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $woocommerce_sidebars, $light_sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'products_per_page' => array(
			'name'        => 'products_per_page',
			'type'        => 'slider',
			'settings'    => 'products_per_page',
			'label'       => esc_html__( 'Products per page', 'xstore' ),
			'description' => esc_html__( 'Add the number of products to show per page before pagination appears. Use -1 to show "All"', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 12,
			'choices'     => array(
				'min'  => '-1',
				'max'  => 100,
				'step' => 1,
			),
		),
		
		'et_ppp_options' => array(
			'name'        => 'et_ppp_options',
			'type'        => 'etheme-text',
			'settings'    => 'et_ppp_options',
			'label'       => esc_html__( 'Per page variants separated by commas', 'xstore' ),
			'description' => esc_html__( 'Add variants and allow the customer to choose the products quantity shown per page. For ex.: 9,12,24,36,-1. Use -1 to show "All".', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => '12,24,36,-1',
		),
		
		'grid_sidebar' => array(
			'name'        => 'grid_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'grid_sidebar',
			'label'       => esc_html__( 'Sidebar position', 'xstore' ),
			'description' => esc_html__( 'Choose the position of the sidebar for the shop page and product categories.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 'left',
			'choices'     => $woocommerce_sidebars,
		),
		
		'category_sidebar' => array(
			'name'        => 'category_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'category_sidebar',
			'label'       => esc_html__( 'Sidebar position on category page', 'xstore' ),
			'description' => esc_html__( 'Choose the position of the sidebar for the product category page.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 'left',
			'choices'     => $woocommerce_sidebars,
		),
		
		'category_page_columns' => array(
			'name'            => 'category_page_columns',
			'type'            => 'select',
			'settings'        => 'category_page_columns',
			'label'           => esc_html__( 'Products per row on category page', 'xstore' ),
			'description'     => esc_html__( 'Choose the number of product per row on category pages or inherit it from the WooCommerce options for the shop page (Appearance > Customize > WooCommerce > Product catalog).', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => 'inherit',
			'choices'         => array(
				'inherit' => esc_html__( 'Inherit from shop settings', 'xstore' ),
				1         => 1,
				2         => 2,
				3         => 3,
				4         => 4,
				5         => 5,
				6         => 6,
			),
			'active_callback' => array(
				array(
					'setting'  => 'view_mode',
					'operator' => '!=',
					'value'    => 'smart',
				),
			),
		),
		
		'brand_sidebar' => array(
			'name'        => 'brand_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'brand_sidebar',
			'label'       => esc_html__( 'Sidebar position on brand page', 'xstore' ),
			'description' => esc_html__( 'Choose the position of the sidebar for the product brand page.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 'left',
			'choices'     => $woocommerce_sidebars,
		),
		
		'brand_page_columns' => array(
			'name'            => 'brand_page_columns',
			'type'            => 'select',
			'settings'        => 'brand_page_columns',
			'label'           => esc_html__( 'Products per row on brand page', 'xstore' ),
			'description'     => esc_html__( 'Choose the number of product per row on brand pages or inherit it from the WooCommerce options for the shop page (Appearance > Customize > WooCommerce > Product catalog).', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => 'inherit',
			'choices'         => array(
				'inherit' => esc_html__( 'Inherit from shop settings', 'xstore' ),
				1         => 1,
				2         => 2,
				3         => 3,
				4         => 4,
				5         => 5,
				6         => 6,
			),
			'active_callback' => array(
				array(
					'setting'  => 'view_mode',
					'operator' => '!=',
					'value'    => 'smart',
				),
			),
		),
		
		'shop_sticky_sidebar' => array(
			'name'        => 'shop_sticky_sidebar',
			'type'        => 'toggle',
			'settings'    => 'shop_sticky_sidebar',
			'label'       => esc_html__( 'Enable sticky sidebar', 'xstore' ),
			'description' => esc_html__( 'Turn on to make the sidebar permanently visible while scrolling at the shop page.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 0,
		),
		
		'sidebar_for_mobile' => array(
			'name'        => 'sidebar_for_mobile',
			'type'        => 'select',
			'settings'    => 'sidebar_for_mobile',
			'label'       => esc_html__( 'Sidebar position for mobile', 'xstore' ),
			'description' => esc_html__( 'Choose the sidebar position for the mobile devices.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 'off_canvas',
			'choices'     => array(
				'top'        => esc_html__( 'Top', 'xstore' ),
				'bottom'     => esc_html__( 'Bottom', 'xstore' ),
				'off_canvas' => esc_html__( 'Off-Canvas', 'xstore' )
			),
		),
		
		'sidebar_for_mobile_icon' => array(
			'name'            => 'sidebar_for_mobile_icon',
			'type'            => 'image',
			'settings'        => 'sidebar_for_mobile_icon',
			'label'           => esc_html__( 'Off-canvas icon', 'xstore' ),
			'description'     => esc_html__( 'Upload svg icon for the sidebar toggle on mobile. Install SVG Support plugin to be able to upload SVG files.', 'xstore' ) .
			                     '<a href="https://wordpress.org/plugins/svg-support/" rel="nofollow" target="_blank">' . esc_html__( 'Install plugin', 'xstore' ) . '</a>',
			'section'         => 'shop-page',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'sidebar_for_mobile',
					'operator' => '==',
					'value'    => 'off_canvas',
				),
			)
		),
		
		'shop_sidebar_hide_mobile' => array(
			'name'        => 'shop_sidebar_hide_mobile',
			'type'        => 'toggle',
			'settings'    => 'shop_sidebar_hide_mobile',
			'label'       => esc_html__( 'Hide sidebar for mobile devices', 'xstore' ),
			'description' => esc_html__( 'Turn on to hide sidebar on the mobile devices.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 0,
		),
		
		'shop_full_width' => array(
			'name'        => 'shop_full_width',
			'type'        => 'toggle',
			'settings'    => 'shop_full_width',
			'label'       => esc_html__( 'Full width', 'xstore' ),
			'description' => esc_html__( 'Turn on to stretch shop page container.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 0,
		),
		
		'products_masonry' => array(
			'name'        => 'products_masonry',
			'type'        => 'toggle',
			'settings'    => 'products_masonry',
			'label'       => esc_html__( 'Products masonry', 'xstore' ),
			'description' => esc_html__( 'Turn on placing products in optimal position based on available vertical space.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 0,
		),
		
		'view_mode' => array(
			'name'        => 'view_mode',
			'type'        => 'select',
			'settings'    => 'view_mode',
			'label'       => esc_html__( 'Products view mode', 'xstore' ),
			'description' => esc_html__( 'Choose the view mode for the shop page.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 'grid_list',
			'choices'     => array(
				'grid_list' => esc_html__( 'Grid/List', 'xstore' ),
				'list_grid' => esc_html__( 'List/Grid', 'xstore' ),
				'grid'      => esc_html__( 'Only Grid', 'xstore' ),
				'list'      => esc_html__( 'Only List', 'xstore' ),
				'smart'     => esc_html__( 'Advanced', 'xstore' )
			),
		),
		
		'view_mode_smart_active' => array(
			'name'            => 'view_mode_smart_active',
			'type'            => 'select',
			'settings'        => 'view_mode_smart_active',
			'label'           => esc_html__( 'Default view for smart grid', 'xstore' ),
			'description'     => esc_html__( 'Choose the default view for the shop page.', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => '4',
			'choices'         => array(
				'2'    => esc_html__( '2 columns grid', 'xstore' ),
				'3'    => esc_html__( '3 columns grid', 'xstore' ),
				'4'    => esc_html__( '4 columns grid', 'xstore' ),
				'5'    => esc_html__( '5 columns grid', 'xstore' ),
				'6'    => esc_html__( '6 columns grid', 'xstore' ),
				'list' => esc_html__( 'List', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'view_mode',
					'operator' => '=',
					'value'    => 'smart',
				),
			)
		),
		
		'categories_view_mode_smart_active' => array(
			'name'            => 'categories_view_mode_smart_active',
			'type'            => 'select',
			'settings'        => 'categories_view_mode_smart_active',
			'label'           => esc_html__( 'Default view for smart grid (category page)', 'xstore' ),
			'description'     => esc_html__( 'Choose the default view for the category page.', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => '4',
			'choices'         => array(
				'2'    => esc_html__( '2 columns grid', 'xstore' ),
				'3'    => esc_html__( '3 columns grid', 'xstore' ),
				'4'    => esc_html__( '4 columns grid', 'xstore' ),
				'5'    => esc_html__( '5 columns grid', 'xstore' ),
				'6'    => esc_html__( '6 columns grid', 'xstore' ),
				'list' => esc_html__( 'List', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'view_mode',
					'operator' => '=',
					'value'    => 'smart',
				),
			)
		),
		
		'brands_view_mode_smart_active' => array(
			'name'            => 'brands_view_mode_smart_active',
			'type'            => 'select',
			'settings'        => 'brands_view_mode_smart_active',
			'label'           => esc_html__( 'Default view for smart grid (brands page)', 'xstore' ),
			'description'     => esc_html__( 'Choose the default view for the brands page.', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => '4',
			'choices'         => array(
				'2'    => esc_html__( '2 columns grid', 'xstore' ),
				'3'    => esc_html__( '3 columns grid', 'xstore' ),
				'4'    => esc_html__( '4 columns grid', 'xstore' ),
				'5'    => esc_html__( '5 columns grid', 'xstore' ),
				'6'    => esc_html__( '6 columns grid', 'xstore' ),
				'list' => esc_html__( 'List', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'view_mode',
					'operator' => '=',
					'value'    => 'smart',
				),
			)
		),
		
		'product_bage_banner_pos' => array(
			'name'        => 'product_bage_banner_pos',
			'type'        => 'select',
			'settings'    => 'product_bage_banner_pos',
			'label'       => esc_html__( 'Shop Page Banner position', 'xstore' ),
			'description' => esc_html__( 'Controls the position of the shop page banner.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 1,
			'choices'     => array(
				1 => esc_html__( 'At the top of the page', 'xstore' ),
				2 => esc_html__( 'At the bottom of the page', 'xstore' ),
				3 => esc_html__( 'Above all the shop content', 'xstore' ),
				4 => esc_html__( 'Above all the shop content (full-width)', 'xstore' ),
				0 => esc_html__( 'Disable', 'xstore' ),
			),
		),
		
		'product_bage_banner' => array(
			'name'            => 'product_bage_banner',
			'type'            => 'editor',
			'settings'        => 'product_bage_banner',
			'label'           => esc_html__( 'Shop Page Banner content', 'xstore' ),
			'description'     => esc_html__( 'Controls the shop page banner content. Use HTML, static block or slider shortcode. Do not include JS in the field.', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'product_bage_banner_pos',
					'operator' => '!=',
					'value'    => 0,
				),
			)
		),
		
		'top_toolbar' => array(
			'name'     => 'top_toolbar',
			'type'     => 'toggle',
			'settings' => 'top_toolbar',
			'label'    => esc_html__( 'Show products toolbar on the shop page', 'xstore' ),
			'section'  => 'shop-page',
			'default'  => 1,
		),
		
		'ajax_product_pagination' => array(
			'name'        => 'ajax_product_pagination',
			'type'        => 'toggle',
			'settings'    => 'ajax_product_pagination',
			'label'       => esc_html__( 'Ajax product pagination', 'xstore' ),
			'description' => esc_html__( 'Turn on to use Ajax for product pagination.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 0,
		),
		
		'ajax_added_product_notify_type' => array(
			'name'        => 'ajax_added_product_notify_type',
			'type'        => 'select',
			'settings'    => 'ajax_added_product_notify_type',
			'label'       => esc_html__( 'Product added notification type', 'xstore' ),
			'description' => esc_html__( 'Turn on to use Ajax notification after once product was added to cart.', 'xstore' ),
			'section'     => 'shop-page',
			'default'     => 'alert',
			'choices'     => array(
				'none'      => esc_html__( 'None', 'xstore' ),
				'alert'     => esc_html__( 'Alert', 'xstore' ),
				'mini_cart' => esc_html__( 'Open cart Off-canvas/dropdown content', 'xstore' ),
				'popup'     => esc_html__( 'Popup', 'xstore' ),
			
			),
		),
		
		'separator_of_ajax_added_product_notify_type_popup' => array(
			'name'            => 'separator_of_ajax_added_product_notify_type_popup',
			'type'            => 'custom',
			'settings'        => 'separator_of_ajax_added_product_notify_type_popup',
			'section'         => 'shop-page',
			'default'         => '<div style="' . $light_sep_style . '">' . esc_html__( 'Popup Added to Cart Settings', 'xstore' ) . '</div>',
			'active_callback' => array(
				array(
					'setting'  => 'ajax_added_product_notify_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		'ajax_added_product_notify_popup_progress_bar'               => array(
			'name'            => 'ajax_added_product_notify_popup_progress_bar',
			'type'            => 'toggle',
			'settings'        => 'ajax_added_product_notify_popup_progress_bar',
			'label'           => esc_html__( 'Add progress bar', 'xstore' ),
			'description'     => esc_html__( 'Turn on to use Sales Booster Cart/Checkout progress bar. Note: You should keep Enable Progress Bar on Cart page option enabled.', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'ajax_added_product_notify_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		// go_to_sticky_logo
		'ajax_added_product_notify_popup_progress_bar_sales_booster' => array(
			'name'            => 'ajax_added_product_notify_popup_progress_bar_sales_booster',
			'type'            => 'custom',
			'settings'        => 'ajax_added_product_notify_popup_progress_bar_sales_booster',
			'section'         => 'shop-page',
			'default'         => '<a href="' . admin_url( 'admin.php?page=et-panel-sales-booster&etheme-sales-booster-tab=cart_checkout' ) . '" target="_blank" style="padding: 5px 7px; border-radius: var(--sm-border-radius); background: #222; color: #fff; text-decoration: none; box-shadow: none;">' . esc_html__( 'Configurate progress bar', 'xstore' ) . '</a>',
			'active_callback' => array(
				array(
					'setting'  => 'ajax_added_product_notify_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		// ajax_added_product_notify_popup_products_type
		'ajax_added_product_notify_popup_products_type'              => array(
			'name'            => 'ajax_added_product_notify_popup_products_type',
			'type'            => 'select',
			'settings'        => 'ajax_added_product_notify_popup_products_type',
			'label'           => esc_html__( 'Linked Products type', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => 'upsell',
			'choices'         => array(
				'upsell'     => esc_html__( 'Upsells', 'xstore' ),
				'cross-sell' => esc_html__( 'Cross-sells', 'xstore' ),
				'none'       => esc_html__( 'None', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'ajax_added_product_notify_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		'ajax_added_product_notify_popup_products_per_view_et-desktop' => array(
			'name'            => 'ajax_added_product_notify_popup_products_per_view_et-desktop',
			'type'            => 'slider',
			'settings'        => 'ajax_added_product_notify_popup_products_per_view_et-desktop',
			'label'           => esc_html__( 'Products per view', 'xstore' ),
			'section'         => 'shop-page',
			'default'         => 4,
			'choices'         => array(
				'min'  => '1',
				'max'  => '8',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'ajax_added_product_notify_type',
					'operator' => '==',
					'value'    => 'popup',
				),
				array(
					'setting'  => 'ajax_added_product_notify_popup_products_type',
					'operator' => '!=',
					'value'    => 'none',
				),
			)
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );