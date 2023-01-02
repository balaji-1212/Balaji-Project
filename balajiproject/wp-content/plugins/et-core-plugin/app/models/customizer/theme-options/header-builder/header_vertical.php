<?php
/**
 * The template created for displaying header vertical options
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.4
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_vertical' => array(
			'name'       => 'header_vertical',
			'title'      => esc_html__( 'Header vertical', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-text-page',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/header_vertical', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {
	$menus = et_b_get_terms( 'nav_menu' );
	
	$args = array();
	// Array of fields
	$args = array(
		
		// header_vertical
		'header_vertical_et-desktop'                    => array(
			'name'     => 'header_vertical_et-desktop',
			'type'     => 'toggle',
			'settings' => 'header_vertical_et-desktop',
			'label'    => esc_html__( 'Enable vertical header', 'xstore-core' ),
			'section'  => 'header_vertical',
			'default'  => '0',
			// 'transport' => 'postMessage',
			// 'js_vars'     => array(
			// 	array(
			// 		'element'  => '.site-header-vertical',
			// 		'function' => 'toggleClass',
			// 		'class' => 'dt-hide',
			// 		'value' => true
			// 	),
			// ),
			// 'partial_refresh' => array(
			// 	'header_vertical_et-desktop' => array(
			// 		'selector'  => '.site-header-vertical',
			// 		'render_callback' => 'header_vertical_callback'
			// 	),
			// ),
		),
		
		// content separator
		'header_vertical_section1_separator'            => array(
			'name'            => 'header_vertical_section1_separator',
			'type'            => 'custom',
			'settings'        => 'header_vertical_section1_separator',
			'section'         => 'header_vertical',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-list-view"></span> <span style="padding-left: 3px;">' . esc_html__( 'Section 1', 'xstore-core' ) . '</span></div>',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		
		// header_vertical_section01_content
		'header_vertical_section1_content'              => array(
			'name'            => 'header_vertical_section1_content',
			'type'            => 'sortable',
			'label'           => $strings['label']['elements'],
			'description'     => esc_html__( 'On/Off elements you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'         => 'header_vertical',
			'settings'        => 'header_vertical_section1_content',
			'default'         => array(
				'logo',
			),
			'choices'         => $choices['header_vertical_elements'],
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_vertical_section1_content' => array(
					'selector'        => '.site-header-vertical',
					'render_callback' => 'header_vertical_callback'
				),
			),
		),
		
		// content separator
		'header_vertical_section2_separator'            => array(
			'name'            => 'header_vertical_section2_separator',
			'type'            => 'custom',
			'settings'        => 'header_vertical_section2_separator',
			'section'         => 'header_vertical',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-list-view"></span> <span style="padding-left: 3px;">' . esc_html__( 'Section 2', 'xstore-core' ) . '</span></div>',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		
		// header_vertical_section02_content
		'header_vertical_section2_content'              => array(
			'name'            => 'header_vertical_section2_content',
			'type'            => 'sortable',
			'label'           => $strings['label']['elements'],
			'description'     => esc_html__( 'On/Off elements you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'         => 'header_vertical',
			'settings'        => 'header_vertical_section2_content',
			'default'         => array(
				'menu',
			),
			'choices'         => $choices['header_vertical_elements'],
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_vertical_section2_content' => array(
					'selector'        => '.site-header-vertical',
					'render_callback' => 'header_vertical_callback'
				),
			),
		),
		
		// content separator
		'header_vertical_section3_separator'            => array(
			'name'            => 'header_vertical_section3_separator',
			'type'            => 'custom',
			'settings'        => 'header_vertical_section3_separator',
			'section'         => 'header_vertical',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-list-view"></span> <span style="padding-left: 3px;">' . esc_html__( 'Section 3', 'xstore-core' ) . '</span></div>',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		
		// header_vertical_section03_content
		'header_vertical_section3_content'              => array(
			'name'            => 'header_vertical_section3_content',
			'type'            => 'sortable',
			'label'           => $strings['label']['elements'],
			'description'     => esc_html__( 'On/Off elements you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'         => 'header_vertical',
			'settings'        => 'header_vertical_section3_content',
			'default'         => array(
				'cart',
			),
			'choices'         => $choices['header_vertical_elements'],
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_vertical_section3_content' => array(
					'selector'        => '.site-header-vertical',
					'render_callback' => 'header_vertical_callback'
				),
			),
		),
		
		// content separator
		'header_vertical_logo_separator'                => array(
			'name'            => 'header_vertical_logo_separator',
			'type'            => 'custom',
			'settings'        => 'header_vertical_logo_separator',
			'section'         => 'header_vertical',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Logo settings', 'xstore-core' ) . '</span></div>',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		
		// header_vertical_logo_img
		'header_vertical_logo_img_et-desktop'           => array(
			'name'            => 'header_vertical_logo_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'header_vertical_logo_img_et-desktop',
			'label'           => esc_html__( 'Logo', 'xstore-core' ),
			'description'     => esc_html__( 'Upload logo image for the header vertical area.', 'xstore-core' ),
			'section'         => 'header_vertical',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_vertical_logo_img_et-desktop' => array(
					'selector'        => '.site-header-vertical',
					'render_callback' => 'header_vertical_callback'
				),
			),
		),
		
		// header_vertical_logo_width
		'header_vertical_logo_width_et-desktop'         => array(
			'name'            => 'header_vertical_logo_width_et-desktop',
			'type'            => 'slider',
			'settings'        => 'header_vertical_logo_width_et-desktop',
			'label'           => esc_html__( 'Logo width (px)', 'xstore-core' ),
			'section'         => 'header_vertical',
			'default'         => 320,
			'choices'         => array(
				'min'  => '20',
				'max'  => '500',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '#header-vertical .et_b_header-logo.et_element-top-level img',
					'property' => 'width',
					'units'    => 'px',
				)
			)
		),
		
		// content separator
		'header_vertical_menu_separator'                => array(
			'name'            => 'header_vertical_menu_separator',
			'type'            => 'custom',
			'settings'        => 'header_vertical_menu_separator',
			'section'         => 'header_vertical',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Menu settings', 'xstore-core' ) . '</span></div>',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		
		// header_vertical_menu_type
		'header_vertical_menu_type_et-desktop'          => array(
			'name'            => 'header_vertical_menu_type_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'header_vertical_menu_type_et-desktop',
			'label'           => esc_html__( 'Menu type', 'xstore-core' ),
			'section'         => 'header_vertical',
			'default'         => 'icon',
			'choices'         => array(
				'classic' => esc_html__( 'Classic', 'xstore-core' ),
				'icon'    => esc_html__( 'Hamburger', 'xstore-core' )
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_vertical_menu_type_et-desktop' => array(
					'selector'        => '.site-header-vertical',
					'render_callback' => 'header_vertical_callback'
				),
			),
		),
		
		// header_vertical_menu_term
		'header_vertical_menu_term'                     => array(
			'name'            => 'header_vertical_menu_term',
			'type'            => 'select',
			'settings'        => 'header_vertical_menu_term',
			'label'           => $strings['label']['select_menu'],
			'section'         => 'header_vertical',
			'choices'         => $menus,
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_vertical_menu_term' => array(
					'selector'        => '.site-header-vertical',
					'render_callback' => 'header_vertical_callback'
				),
			),
		),
		
		// header_vertical_menu_content_fonts
		'header_vertical_menu_content_fonts_et-desktop' => array(
			'name'            => 'header_vertical_menu_content_fonts_et-desktop',
			'type'            => 'typography',
			'settings'        => 'header_vertical_menu_content_fonts_et-desktop',
			'section'         => 'header_vertical',
			'default'         => array(
				'font-family'    => '',
				// 'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'inherit',
				// 'text-align'     => 'left',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-vertical-menu',
				),
			),
		),
		
		// header_vertical_menu_icon_zoom 
		'header_vertical_menu_icon_zoom_et-desktop'     => array(
			'name'            => 'header_vertical_menu_icon_zoom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'header_vertical_menu_icon_zoom_et-desktop',
			'label'           => esc_html__( 'Menu icon zoom (proportion)', 'xstore-core' ),
			'section'         => 'header_vertical',
			'default'         => 1,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_menu_type_et-desktop',
					'operator' => '!=',
					'value'    => 'simple',
				),
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-vertical-menu-icon-wrapper .et-toggle, .header-vertical-menu-icon-wrapper .et-toggle svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-vertical-menu-icon-wrapper .et-toggle, .header-vertical-menu-icon-wrapper .et-toggle svg',
					'property' => 'height',
					'units'    => 'em'
				)
			)
		),
		
		// style separator
		'header_vertical_style_separator'               => array(
			'name'            => 'header_vertical_style_separator',
			'type'            => 'custom',
			'settings'        => 'header_vertical_style_separator',
			'section'         => 'header_vertical',
			'default'         => $separators['style'],
			'priority'        => 10,
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		
		// header_vertical_width
		'header_vertical_width_et-desktop'              => array(
			'name'            => 'header_vertical_width_et-desktop',
			'type'            => 'slider',
			'settings'        => 'header_vertical_width_et-desktop',
			'label'           => esc_html__( 'Header width (px)', 'xstore-core' ),
			'section'         => 'header_vertical',
			'default'         => 90,
			'choices'         => array(
				'min'  => '90',
				'max'  => '500',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--header-vertical-size',
					'units'    => 'px'
				),
//				array(
//					'context'	=> array('editor', 'front'),
//					'element' => '#header-vertical',
//					'property' => 'width',
//					'units' => 'px'
//				),
//				array(
//					'context'	=> array('editor', 'front'),
//					'element' => '.template-content',
//					'property' => '--header-vertical-size',
//					'media_query' => '@media (min-width: 993px)',
//					'units' => 'px',
//				),
//				array(
//					'media_query' => '@media (min-width: 993px)',
//					'context'	=> array('editor', 'front'),
//					'element' => '#header.sticky-on:not([data-type="sticky"]) > [class*=header-wrapper], #header > [class*=header-wrapper] .sticky-on > div',
//					'property' => 'width',
//					'value_pattern' => 'calc(100% - $px)'
//				),
//				array(
//					'media_query' => '@media (min-width: 993px)',
//					'context'	=> array('editor', 'front'),
//					'element' => '.et_b_dt_header-overlap #header.sticky-on:not([data-type="sticky"]) > [class*=header-wrapper], .et_b_dt_header-overlap #header > [class*=header-wrapper] .sticky-on > div',
////					'property' => 'left',
//					'property' => '--side-spacing',
//					'units' => 'px'
//				),
			)
		),
		
		// header_vertical_zoom
		'header_vertical_content_zoom_et-desktop'       => array(
			'name'            => 'header_vertical_content_zoom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'header_vertical_content_zoom_et-desktop',
			'label'           => $strings['label']['content_size'],
			'section'         => 'header_vertical',
			'default'         => 100,
			'choices'         => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '#header-vertical',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// header_vertical_elements_spacing
		'header_vertical_elements_spacing_et-desktop'   => array(
			'name'            => 'header_vertical_elements_spacing_et-desktop',
			'type'            => 'slider',
			'settings'        => 'header_vertical_elements_spacing_et-desktop',
			'label'           => $strings['label']['elements_spacing'],
			'section'         => 'header_vertical',
			'default'         => 10,
			'choices'         => array(
				'min'  => '0',
				'max'  => '60',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-vertical-section > .et_element + .et_element',
					'property' => 'margin-top',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '#header-vertical .et_b_header-menu li a',
					'property'      => 'padding',
					'value_pattern' => 'calc($px / 2) 0'
				)
			)
		),
		'header_vertical_background_color_et-desktop'   => array(
			'name'            => 'header_vertical_background_color_et-desktop',
			'settings'        => 'header_vertical_background_color_et-desktop',
			'label'           => esc_html__( 'Off-Canvas content WCAG Control (background)', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'type'            => 'color',
			'section'         => 'header_vertical',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '#header-vertical,
						#header-vertical .et_b_header-menu .nav-sublist-dropdown:not(.nav-sublist), 
						#header-vertical .et_b_header-menu .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul,
						#header-vertical .et-mini-content',
					'property' => 'background-color'
				),
			),
		),
		
		// header_vertical_color
		'header_vertical_color_et-desktop'              => array(
			'name'            => 'header_vertical_color_et-desktop',
			'settings'        => 'header_vertical_color_et-desktop',
			'label'           => esc_html__( 'Off-Canvas content WCAG Color', 'xstore-core' ),
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'header_vertical',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(header_vertical)(header_vertical_background_color_et-desktop)',
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
					'setting'  => 'header_vertical_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '#header-vertical',
					'property' => 'color'
				),
			),
		),
	
	);
	
	unset($menus);
	
	return array_merge( $fields, $args );
	
} );
