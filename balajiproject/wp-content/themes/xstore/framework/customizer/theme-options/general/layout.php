<?php
/**
 * The template created for displaying general options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section general
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'general' => array(
			'name'       => 'general',
			'title'      => esc_html__( 'General / Layout', 'xstore' ),
			'icon'       => 'dashicons-schedule',
			'priority'   => $priorities['general'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/general' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'main_layout' => array(
			'name'        => 'main_layout',
			'type'        => 'select',
			'settings'    => 'main_layout',
			'label'       => esc_html__( 'Site Layout', 'xstore' ),
			'description' => esc_html__( 'Choose the type of layout you want your site to display.', 'xstore' ),
			'section'     => 'general',
			'default'     => 'wide',
			'choices'     => array(
				'wide'     => esc_html__( 'Wide layout', 'xstore' ),
				'boxed'    => esc_html__( 'Boxed', 'xstore' ),
				'framed'   => esc_html__( 'Framed', 'xstore' ),
				'bordered' => esc_html__( 'Bordered', 'xstore' ),
			),
		),
		
		'site_width' => array(
			'name'        => 'site_width',
			'type'        => 'slider',
			'settings'    => 'site_width',
			'label'       => esc_html__( 'Site width', 'xstore' ),
			'description' => esc_html__( 'Controls the content width. In pixels, default: 1170px.', 'xstore' ),
			'section'     => 'general',
			'default'     => 1170,
			'choices'     => array(
				'min'  => 970,
				'max'  => 3000,
				'step' => 1,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.boxed #header.sticky-on:not([data-type="sticky"]) > [class*=header-wrapper], .boxed #header > [class*=header-wrapper] .sticky-on > div,
					.framed #header.sticky-on:not([data-type="sticky"]) > [class*=header-wrapper], .framed #header > [class*=header-wrapper] .sticky-on > div',
					'property'      => 'max-width',
					'value_pattern' => 'calc($px + 30px - ( 2 * var(--sticky-on-space-fix, 0px)) )'
				),
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.container, div.container, .et-container',
					'media_query' => '@media only screen and (min-width: 1200px)',
					'property'    => 'max-width',
					'units'       => 'px'
				),
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product .woocommerce-message, .single-product .woocommerce-error, .single-product .woocommerce-info',
					'media_query' => '@media only screen and (min-width: 1200px)',
					'property'    => 'width',
					'units'       => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.footer:after',
					'media_query'   => '@media only screen and (min-width: 1200px)',
					'property'      => 'width',
					'value_pattern' => 'calc($px - 30px)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.boxed .template-container, .framed .template-container',
					'media_query'   => '@media only screen and (min-width: 1200px)',
					'property'      => 'width',
					'value_pattern' => 'calc($px + 30px)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.boxed .header-wrapper, .framed .header-wrapper',
					'media_query'   => '@media only screen and (min-width: 1200px)',
					'property'      => 'width',
					'value_pattern' => 'calc($px + 30px)'
				),
			),
		),
		
		'mobile_header_start_from' => array(
			'name'            => 'mobile_header_start_from',
			'type'            => 'slider',
			'settings'        => 'mobile_header_start_from',
			'label'           => esc_html__( 'Mobile header starts from', 'xstore' ),
			'description'     => esc_html__( 'Controls the mobile header start point. In pixels, default: 992px.', 'xstore' ),
			'section'         => 'general',
			'default'         => 992,
			'choices'         => array(
				'min'  => 320,
				'max'  => 1440,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_optimization',
					'operator' => '!=',
					'value'    => true,
				),
			)
		),
		
		'mobile_optimization' => array(
			'name'        => 'mobile_optimization',
			'type'        => 'toggle',
			'settings'    => 'mobile_optimization',
			'label'       => esc_html__( 'Mobile content optimization', 'xstore' ),
			'description' => esc_html__( 'Helps to disable unused HTML, CSS, JS of the desktop elements for mobile version. Keep disabled if you use cache for the mobile devices of the cache plugin.', 'xstore' ),
			'section'     => 'general',
			'default'     => 0,
		),
		
		'mobile_scalable' => array(
			'name'        => 'mobile_scalable',
			'type'        => 'toggle',
			'settings'    => 'mobile_scalable',
			'label'       => esc_html__( 'Mobile User-scalable', 'xstore' ),
			'description' => esc_html__( 'Enable if you want to allow users to scale content on mobile device.', 'xstore' ),
			'section'     => 'general',
			'default'     => 0,
		),
		
		'site_preloader' => array(
			'name'        => 'site_preloader',
			'type'        => 'toggle',
			'settings'    => 'site_preloader',
			'label'       => esc_html__( 'Site preloader', 'xstore' ),
			'description' => esc_html__( 'Enable nice loading effect while your site or page is in loading mode.', 'xstore' ),
			'section'     => 'general',
			'default'     => 0,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et-preloader-on',
					'value'    => true,
				),
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et-preloader-off',
					'value'    => false,
				),
			),
		),
		
		'preloader_img' => array(
			'name'            => 'preloader_img',
			'type'            => 'image',
			'settings'        => 'preloader_img',
			'label'           => esc_html__( 'Site Preloader image', 'xstore' ),
			'description'     => esc_html__( 'Upload an interesting png, jpg or gif file to make the waiting time less of a hassle for site visitors.', 'xstore' ),
			'section'         => 'general',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_preloader',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		'preloader_images' => array(
			'name'        => 'preloader_images',
			'type'        => 'image',
			'settings'    => 'preloader_images',
			'label'       => esc_html__( 'Images loader', 'xstore' ),
			'description' => esc_html__( 'Upload an interesting png, jpg or gif file to make the waiting time less of a hassle for site visitors.', 'xstore' ),
			'section'     => 'general',
			'default'     => '',
			'choices'     => array(
				'save_as' => 'array',
			),
		),
		
		'static_blocks' => array(
			'name'        => 'static_blocks',
			'type'        => 'toggle',
			'settings'    => 'static_blocks',
			'label'       => esc_html__( 'Static blocks', 'xstore' ),
			'description' => esc_html__( 'Enable this option if you want to use static blocks functionality to create an advanced content of footer, newsletter, mega menu, widgets etc.', 'xstore' ),
			'section'     => 'general',
			// 'transport'	  => 'auto',
			'default'     => 1,
		),
		
		'testimonials_type' => array(
			'name'        => 'testimonials_type',
			'type'        => 'toggle',
			'settings'    => 'testimonials_type',
			'label'       => esc_html__( 'Testimonials', 'xstore' ),
			'description' => esc_html__( 'Enable this option if you collect written recommendations from customers, clients and want to display them on your site in different ways.', 'xstore' ),
			'section'     => 'general',
			// 'transport'	  => 'auto',
			'default'     => 1,
		),
		
		'old_widgets_panel_type' => array(
			'name'        => 'old_widgets_panel_type',
			'type'        => 'toggle',
			'settings'    => 'old_widgets_panel_type',
			'label'       => esc_html__( 'Enable Classic widgets panel', 'xstore' ),
			'description' => esc_html__( 'Enable this option if you want use classic widgets panel.', 'xstore' ),
			'section'     => 'general',
			// 'transport'	  => 'auto',
			'default'     => 0,
		),
		'etheme_studio_on'       => array(
			'name'        => 'etheme_studio_on',
			'type'        => 'toggle',
			'settings'    => 'etheme_studio_on',
			'label'       => esc_html__( 'Enable ETheme studio', 'xstore' ),
			'description' => esc_html__( 'Enable this option if you want use ETheme studio for Elementor page builder.', 'xstore' ),
			'section'     => 'general',
			// 'transport'	  => 'auto',
			'default'     => 1,
		),
	
	);
	
	
	if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
		
		$args['et_wpbakery_css_module'] = array(
			'name'        => 'et_wpbakery_css_module',
			'type'        => 'toggle',
			'settings'    => 'et_wpbakery_css_module',
			'label'       => esc_html__( 'WPBakery responsive CSS box-module', 'xstore' ),
			'description' => esc_html__( 'Enable responsive CSS boxes for columns and rows in WPBakery builder.', 'xstore' ),
			'section'     => 'general',
			'default'     => 0,
		);
		
	}
	
	$args['et_menu_options'] = array(
		'name'        => 'et_menu_options',
		'type'        => 'toggle',
		'settings'    => 'et_menu_options',
		'label'       => esc_html__( 'Theme menu options', 'xstore' ),
		'description' => esc_html__( 'Enable to get additional menu settings to build mega menus, upload menu images, icons etc.', 'xstore' ),
		'section'     => 'general',
		'default'     => 1,
	);
	
	$args['et_seo_noindex'] = array(
		'name'        => 'et_seo_noindex',
		'type'        => 'toggle',
		'settings'    => 'et_seo_noindex',
		'label'       => esc_html__( 'SEO: "noindex" tag for url\'s', 'xstore' ),
		'description' => esc_html__( 'Enable this option if you want to use "noindex" tag for shop url\'s with parameters.', 'xstore' ),
		'section'     => 'general',
		'default'     => 0,
	);
	
	
	return array_merge( $fields, $args );
	
} );