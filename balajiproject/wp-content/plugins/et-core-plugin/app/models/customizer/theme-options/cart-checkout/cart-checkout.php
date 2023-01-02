<?php
/**
 * The template created for displaying cart/checkout options
 *
 * @version 1.0.1
 * @since   4.3
 * @log     last changes in 4.3.2
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'cart-checkout-layout' => array(
			'name'       => 'cart-checkout-layout',
			'title'      => esc_html__( 'Cart/Checkout layout', 'xstore-core' ),
			'panel'      => 'woocommerce',
			'icon'       => 'dashicons-cart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/cart-checkout-layout', function ( $fields ) use ( $sep_style, $strings, $box_models, $choices ) {
	$args = array();
	
	$header_box_model                        = $box_models['empty'];
	$header_box_model['border-bottom-width'] = '1px';
	$header_box_model['padding-top']         = '10px';
	$header_box_model['padding-bottom']      = '10px';
	
	$dark_mode = get_theme_mod( 'dark_styles', false );
	
	$sections = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	);
	
	// Array of fields
	$args = array(
		
		// main_header_wide
		'cart_checkout_advanced_layout' => array(
			'name'     => 'cart_checkout_advanced_layout',
			'type'     => 'toggle',
			'settings' => 'cart_checkout_advanced_layout',
			'label'    => __( 'Advanced layout', 'xstore-core' ),
			'section'  => 'cart-checkout-layout',
			'default'  => '0',
		),
		
		'cart_checkout_layout_type'                => array(
			'name'            => 'cart_checkout_layout_type',
			'type'            => 'select',
			'settings'        => 'cart_checkout_layout_type',
			'label'           => esc_html__( 'Layout', 'xstore-core' ),
			'description'     => esc_html__( 'Choose the type of page on cart/checkout pages.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => 'default',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			),
			'choices'         => array(
				'default'   => __( 'Classic', 'xstore-core' ),
				'multistep' => __( 'Multistep', 'xstore-core' ),
				'separated' => __( 'Separated', 'xstore-core' ),
			)
		),
		
		
		// go_to_sticky_logo
		'cart_checkout_layout_type_separated_info' => array(
			'name'            => 'cart_checkout_layout_type_separated_info',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_type_separated_info',
			'section'         => 'cart-checkout-layout',
			'default'         => '<em>' . esc_html__( 'This layout could have some issues with payment plugins', 'xstore-core' ) . '</em>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_layout_type',
					'operator' => '==',
					'value'    => 'separated',
				),
			)
		),
		
		'cart_checkout_order_product_images' => array(
			'name'            => 'cart_checkout_order_product_images',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_order_product_images',
			'label'           => esc_html__( 'Product images on order', 'xstore-core' ),
			'description'     => esc_html__( 'Images will be shown on checkout and thank you page.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '1',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		'cart_checkout_advanced_form_label' => array(
			'name'            => 'cart_checkout_advanced_form_label',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_advanced_form_label',
			'label'           => esc_html__( 'Advanced form label', 'xstore-core' ),
			'description'     => esc_html__( 'Enable animated form label on checkout page.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		// go_to_sticky_logo
		'cart_checkout_sales_booster'       => array(
			'name'            => 'cart_checkout_sales_booster',
			'type'            => 'custom',
			'label'           => esc_html__( 'Enable sales booster features', 'xstore-core' ),
			'description'     => esc_html__( 'Sales booster features can help your store make products sales quicker.', 'xstore-core' ),
			'settings'        => 'cart_checkout_sales_booster',
			'section'         => 'cart-checkout-layout',
			'default'         => '<a href="' . admin_url( 'admin.php?page=et-panel-sales-booster&etheme-sales-booster-tab=cart_checkout' ) . '" target="_blank" style="padding: 5px 7px; border-radius: var(--sm-border-radius); background: #222; color: #fff; text-decoration: none; box-shadow: none;">' . esc_html__( 'Sales Booster features', 'xstore-core' ) . '</a>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		'cart_checkout_layout_header_separator'                    => array(
			'name'            => 'cart_checkout_layout_header_separator',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_header_separator',
			'section'         => 'cart-checkout-layout',
			'default'         => '<div style="' . $sep_style . '">' . esc_html__( 'Header', 'xstore-core' ) . '</div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		// cart_checkout_header_builder
		'cart_checkout_header_builder'                             => array(
			'name'            => 'cart_checkout_header_builder',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_header_builder',
			'label'           => __( 'Use header builder', 'xstore-core' ),
			'description'     => __( 'Create multiple header and set cart and checkout pages condition for it or show default one', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			),
		),
		
		// logo_img
		'cart_checkout_logo_img_et-desktop'                        => array(
			'name'            => 'cart_checkout_logo_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'cart_checkout_logo_img_et-desktop',
			'label'           => esc_html__( 'Site logo', 'xstore-core' ),
			'description'     => esc_html__( 'Upload logo image for the main header area.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => get_theme_mod( 'logo_img_et-desktop', '' ),
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			)
		),
		
		// retina_logo_img
		'cart_checkout_retina_logo_img_et-desktop'                 => array(
			'name'            => 'cart_checkout_retina_logo_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'cart_checkout_retina_logo_img_et-desktop',
			'label'           => esc_html__( 'Retina logo', 'xstore-core' ),
			'description'     => esc_html__( 'Upload retina logo image for the main header area.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => get_theme_mod( 'retina_logo_img_et-desktop', '' ),
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			)
		),
		
		// logo_width
		'cart_checkout_logo_width_et-desktop'                      => array(
			'name'            => 'cart_checkout_logo_width_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_checkout_logo_width_et-desktop',
			'label'           => esc_html__( 'Logo width (px)', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => 140,
			'choices'         => array(
				'min'  => '20',
				'max'  => '1000',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .et_b_header-logo.et_element-top-level img',
					'property' => 'width',
					'units'    => 'px'
				)
			)
		),
		
		
		// main_header_wide
		'cart_checkout_main_header_wide_et-desktop'                => array(
			'name'            => 'cart_checkout_main_header_wide_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_main_header_wide_et-desktop',
			'label'           => $strings['label']['wide_header'],
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_layout_type',
					'operator' => '!=',
					'value'    => 'separated',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.cart-checkout-light-header .header-main-wrapper .header-main > .et-row-container',
					'function' => 'toggleClass',
					'class'    => 'et-container',
					'value'    => true
				),
				array(
					'element'  => '.cart-checkout-light-header .header-main-wrapper .header-main > .et-row-container',
					'function' => 'toggleClass',
					'class'    => 'et-container',
					'value'    => false
				),
			),
		),
		
		// main_header_sticky
		'cart_checkout_main_header_sticky_et-desktop'              => array(
			'name'            => 'cart_checkout_main_header_sticky_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_main_header_sticky_et-desktop',
			'label'           => esc_html__( 'Main header sticky', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '1',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_layout_type',
					'operator' => '!=',
					'value'    => 'separated',
				),
			)
		),
		
		// header height
		// main_header_height
		'cart_checkout_main_header_height_et-desktop'              => array(
			'name'            => 'cart_checkout_main_header_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_checkout_main_header_height_et-desktop',
			'label'           => $strings['label']['min_height'],
			'section'         => 'cart-checkout-layout',
			'default'         => 90,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main .et-wrap-columns, .header-main .widget_nav_menu .menu > li > a',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main .widget_nav_menu .menu > li > a, .header-main #lang_sel a.lang_sel_sel, .header-main .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
				
				// sticky header same min-height
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .sticky-on .header-main .et-wrap-columns, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header #header.sticky-on .header-main .widget_nav_menu .menu > li > a, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main .widget_nav_menu .menu > li > a,
									.cart-checkout-light-header #header.sticky-on .header-main #lang_sel a.lang_sel_sel, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main #lang_sel a.lang_sel_sel,
									.cart-checkout-light-header #header.sticky-on .header-main .wcml-dropdown a.wcml-cs-item-toggle, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// main_header_background
		'cart_checkout_main_header_background_et-desktop'          => array(
			'name'            => 'cart_checkout_main_header_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'cart_checkout_main_header_background_et-desktop',
			'label'           => $strings['label']['wcag_bg_color'],
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'cart-checkout-layout',
			'default'         => array(
				'background-color'      => $dark_mode ? '#1f1f1f' : '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cart-checkout-light-header .header-main, .cart-checkout-light-header .sticky-on .header-main',
				),
			),
		),
		
		// main_header_box_model
		'cart_checkout_main_header_box_model_et-desktop'           => array(
			'name'            => 'cart_checkout_main_header_box_model_et-desktop',
			'settings'        => 'cart_checkout_main_header_box_model_et-desktop',
			'label'           => $strings['label']['computed_box'],
			'description'     => $strings['description']['computed_box'],
			'type'            => 'kirki-box-model',
			'section'         => 'cart-checkout-layout',
			'default'         => $header_box_model,
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cart-checkout-light-header .header-main',
				),
				array(
					'choice'        => 'margin-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.cart-checkout-light-header .sticky-on .header-main',
					'property'      => '--sticky-on-space-fix',
					'value_pattern' => 'calc(var(--sticky-on-space-fix2, 0px) + $)',
				),
				array(
					'choice'        => 'margin-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.cart-checkout-light-header .sticky-on .header-main',
					'property'      => 'max-width',
					'value_pattern' => 'calc(100% - var(--sticky-on-space-fix, 0px) - $)'
				)
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.cart-checkout-light-header .header-main' ),
				array(
					array(
						'choice'        => 'margin-left',
						'element'       => '.cart-checkout-light-header .sticky-on .header-main',
						'property'      => '--sticky-on-space-fix',
						'type'          => 'css',
						'value_pattern' => 'calc(var(--sticky-on-space-fix2, 0px) + $)',
					),
					array(
						'choice'        => 'margin-right',
						'element'       => '.cart-checkout-light-header .sticky-on .header-main',
						'property'      => 'max-width',
						'type'          => 'css',
						'value_pattern' => 'calc(100% - var(--sticky-on-space-fix, 0px) - $)'
					)
				)
			),
		),
		
		// main_header_border
		'cart_checkout_main_header_border_et-desktop'              => array(
			'name'            => 'cart_checkout_main_header_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'cart_checkout_main_header_border_et-desktop',
			'label'           => $strings['label']['border_style'],
			'section'         => 'cart-checkout-layout',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main',
					'property' => 'border-style'
				)
			)
		),
		
		// main_header_border_color_custom
		'cart_checkout_main_header_border_color_custom_et-desktop' => array(
			'name'            => 'cart_checkout_main_header_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'cart_checkout_main_header_border_color_custom_et-desktop',
			'label'           => $strings['label']['border_color'],
			'description'     => $strings['description']['border_color'],
			'section'         => 'cart-checkout-layout',
			'default'         => $dark_mode ? '#2f2f2f' : '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main',
					'property' => 'border-color',
				),
			),
		),
		
		'cart_checkout_layout_footer_separator'      => array(
			'name'            => 'cart_checkout_layout_footer_separator',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_footer_separator',
			'section'         => 'cart-checkout-layout',
			'default'         => '<div style="' . $sep_style . '">' . esc_html__( 'Footer', 'xstore-core' ) . '</div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		// cart_checkout_header_builder
		'cart_checkout_default_footer'               => array(
			'name'            => 'cart_checkout_default_footer',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_default_footer',
			'label'           => __( 'Use default footer', 'xstore-core' ),
			'description'     => __( 'Enable to show default global footer from other pages', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			),
		),
		
		// main_header_background
		'cart_checkout_footer_background_et-desktop' => array(
			'name'            => 'cart_checkout_footer_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'cart_checkout_footer_background_et-desktop',
			'label'           => $strings['label']['wcag_bg_color'],
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'cart-checkout-layout',
			'default'         => array(
				'background-color'      => $dark_mode ? '#1f1f1f' : '#222222',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cart-checkout-light-footer .footer',
				),
			),
		),
		
		// cart_checkout_footer_color
		'cart_checkout_footer_color_et-desktop'      => array(
			'name'            => 'cart_checkout_footer_color_et-desktop',
			'settings'        => 'cart_checkout_footer_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'cart-checkout-layout',
			'default'         => '#ffffff',
			'choices'         => array(
				'setting' => 'setting(cart-checkout-layout)(cart_checkout_footer_background_et-desktop)[background-color]',
				// 'maxHueDiff'          => 60,   // Optional.
				// 'stepHue'             => 15,   // Optional.
				// 'maxSaturation'       => 0.5,  // Optional.
				// 'stepSaturation'      => 0.1,  // Optional.
				// 'stepLightness'       => 0.05, // Optional.
				// 'precissionThreshold' => 6,    // Optional.
				// 'contrastThreshold'   => 4.5   // Optional.
				'show'    => array(
					// 'auto'        => false,
					// 'custom'      => false,
					'recommended' => false,
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-footer .footer',
					'property' => 'color'
				)
			)
		),
		
		//
		// cart_checkout_footer_content
		'cart_checkout_footer_content'               => array(
			'name'            => 'cart_checkout_footer_content',
			'type'            => 'editor',
			'settings'        => 'cart_checkout_footer_content',
			'label'           => esc_html__( 'Footer content', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'cart-checkout-layout',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_footer_content_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
		),
		
		// html_block1_sections
		'cart_checkout_footer_content_sections'      => array(
			'name'            => 'cart_checkout_footer_content_sections',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_footer_content_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'cart-checkout-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
		),
		
		// html_block1_section
		'cart_checkout_footer_content_section'       => array(
			'name'            => 'cart_checkout_footer_content_section',
			'type'            => 'select',
			'settings'        => 'cart_checkout_footer_content_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Footer content', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_footer_content_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		'cart_checkout_layout_copyrights_separator' => array(
			'name'            => 'cart_checkout_layout_copyrights_separator',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_copyrights_separator',
			'section'         => 'cart-checkout-layout',
			'default'         => '<div style="' . $sep_style . '">' . esc_html__( 'Copyrights', 'xstore-core' ) . '</div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
		),
		
		'cart_checkout_copyrights_content' => array(
			'name'            => 'cart_checkout_copyrights_content',
			'type'            => 'editor',
			'settings'        => 'cart_checkout_copyrights_content',
			'label'           => esc_html__( 'Copyrights content', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'cart-checkout-layout',
			'default'         => esc_html__( 'Ⓒ Created by 8theme - Power Elite ThemeForest Author.', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_checkout_copyrights_content' => array(
					'selector'        => '.cart-checkout-light-footer .footer .copyrights',
					'render_callback' => function () {
						return do_shortcode( get_theme_mod( 'cart_checkout_copyrights_content', esc_html__( 'Ⓒ Created by 8theme - Power Elite ThemeForest Author.', 'xstore-core' ) ) );
					},
				),
			),
		),
	
	);
	
	unset($header_box_model);
	unset($dark_mode);
	unset($sections);
	
	return array_merge( $fields, $args );
	
} );