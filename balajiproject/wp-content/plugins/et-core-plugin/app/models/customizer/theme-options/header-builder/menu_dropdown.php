<?php
/**
 * The template created for displaying header dropdown menu options
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'menu_dropdown' => array(
			'name'       => 'menu_dropdown',
			'title'      => esc_html__( 'Dropdown menu', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-images-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/menu_dropdown', function ( $fields ) use ( $separators, $strings, $choices, $menu_settings ) {
	$args = array();
	// Array of fields
	$args = array(
		
		// content separator
		'menu_dropdown_content_separator'              => array(
			'name'     => 'menu_dropdown_content_separator',
			'type'     => 'custom',
			'settings' => 'menu_dropdown_content_separator',
			'section'  => 'menu_dropdown',
			'default'  => $separators['content'],
		),
		
		// menu_dropdown_font_size
		'menu_dropdown_zoom_et-desktop'                => array(
			'name'      => 'menu_dropdown_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'menu_dropdown_zoom_et-desktop',
			'label'     => $strings['label']['content_size'],
			'section'   => 'menu_dropdown',
			'default'   => 100,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown, .site-header .widget_nav_menu .menu > li > .sub-menu, .site-header .etheme_widget_menu .nav-sublist-dropdown',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// style separator
		'menu_dropdown_style_separator'                => array(
			'name'     => 'menu_dropdown_style_separator',
			'type'     => 'custom',
			'settings' => 'menu_dropdown_style_separator',
			'section'  => 'menu_dropdown',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// menu_dropdown_fonts
		'menu_dropdown_fonts_et-desktop'               => array(
			'name'      => 'menu_dropdown_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'menu_dropdown_fonts_et-desktop',
			'section'   => 'menu_dropdown',
			'default'   => array(
				'font-family'    => '',
				'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'none',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link, .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li > a, .site-header .widget_nav_menu .menu > li > .sub-menu a, .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li > a',
				),
			),
		),
		
		// menu_dropdown_background_custom
		'menu_dropdown_background_custom_et-desktop'   => array(
			'name'      => 'menu_dropdown_background_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'menu_dropdown_background_custom_et-desktop',
			'label'     => $strings['label']['bg_color'],
			'section'   => 'menu_dropdown',
			'default'   => '#ffffff',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown:not(.nav-sublist), .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul, .site-header .widget_nav_menu .menu > li > .sub-menu, .site-header .etheme_widget_menu .nav-sublist-dropdown:not(.nav-sublist), .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul',
					'property' => 'background-color',
				),
			),
		),
		
		// menu_dropdown_color
		'menu_dropdown_color_et-desktop'               => array(
			'name'        => 'menu_dropdown_color_et-desktop',
			'settings'    => 'menu_dropdown_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'menu_dropdown',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(menu_dropdown)(menu_dropdown_background_custom_et-desktop)',
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
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link, .et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link:hover, .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li > a:hover,
					.site-header .widget_nav_menu .menu > li > .sub-menu a, .site-header .widget_nav_menu .menu > li > .sub-menu a:hover,
					.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link, .site-header .etheme_widget_menu .nav-sublist-dropdown .item-link:hover, .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li > a:hover',
					'property' => 'color'
				)
			)
		),
		
		// menu_dropdown_box_model
		'menu_dropdown_box_model_et-desktop'           => array(
			'name'        => 'menu_dropdown_box_model_et-desktop',
			'settings'    => 'menu_dropdown_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'menu_dropdown',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '.6em',
				'padding-right'       => '1.9em',
				'padding-bottom'      => '.6em',
				'padding-left'        => '1.9em',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $menu_settings['dropdown_selectors'],
				),
				array(
					'choice'        => 'padding-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => $menu_settings['dropdown_selectors'],
					'property'      => 'padding-left',
					'value_pattern' => '0px'
				),
				array(
					'choice'        => 'padding-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => $menu_settings['dropdown_selectors'],
					'property'      => 'padding-right',
					'value_pattern' => '0px'
				),
				array(
					'choice'        => 'padding-top',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li ul,
					.site-header .widget_nav_menu .menu > li > .sub-menu .sub-menu,
					.site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li ul',
					'property'      => '--nav-sublist-dropdown-top',
					'value_pattern' => '-$'
				),
				array(
					'choice'   => 'padding-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link,
					.site-header .widget_nav_menu .menu > li > .sub-menu a,
					.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link',
					'property' => 'padding-left',
				),
				array(
					'choice'   => 'padding-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link,
					.site-header .widget_nav_menu .menu > li > .sub-menu a,
					.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link',
					'property' => 'padding-right',
				),
				
				array(
					'choice'   => 'padding-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link,
					.site-header .widget_nav_menu .menu > li > .sub-menu a,
					.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link',
					'property' => 'padding-top',
				),
				array(
					'choice'   => 'padding-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link,
					.site-header .widget_nav_menu .menu > li > .sub-menu a,
					.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link',
					'property' => 'padding-bottom',
				),
				
				array(
					'choice'   => 'padding-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after,
					.site-header .widget_nav_menu .menu > li > .sub-menu li.menu-item-has-children > a:after,
					.site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after',
					'property' => 'right',
				),
				// rtl
				array(
					'choice'   => 'padding-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body.rtl .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after,
					body.rtl .site-header .widget_nav_menu .menu > li > .sub-menu li.menu-item-has-children > a:after,
					body.rtl .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after',
					'property' => 'left',
				),
				array(
					'choice'        => 'padding-right',
					'element'       => 'body.rtl .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after,
						body.rtl .site-header .widget_nav_menu .menu > li > .sub-menu li.menu-item-has-children > a:after,
						body.rtl .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after',
					'function'      => 'css',
					'property'      => 'right',
					'value_pattern' => 'auto'
				),
				array(
					'choice'        => 'border-top-width',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li ul,
					.site-header .widget_nav_menu .menu > li > .sub-menu .sub-menu,
					.site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li ul',
					'property'      => 'top',
					'value_pattern' => 'calc(var(--nav-sublist-dropdown-top) - $)'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => array_merge(
				box_model_output( $menu_settings['dropdown_selectors'] ),
				array(
					array(
						'choice'        => 'padding-left',
						'element'       => $menu_settings['dropdown_selectors'],
						'function'      => 'css',
						'property'      => 'padding-left',
						'value_pattern' => '0px'
					),
					array(
						'choice'        => 'padding-right',
						'element'       => $menu_settings['dropdown_selectors'],
						'function'      => 'css',
						'property'      => 'padding-right',
						'value_pattern' => '0px'
					),
					array(
						'choice'        => 'padding-top',
						'element'       => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li ul,
						.site-header .widget_nav_menu .menu > li > .sub-menu .sub-menu,
						.site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li ul',
						'function'      => 'css',
						'property'      => '--nav-sublist-dropdown-top',
						'value_pattern' => '-$'
					),
					array(
						'choice'   => 'padding-left',
						'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link,
						.site-header .widget_nav_menu .menu > li > .sub-menu a,
						.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link',
						'function' => 'css',
						'property' => 'padding-left',
					),
					array(
						'choice'   => 'padding-right',
						'element'  => '.et_b_header-menu.et_element-top-level .nav-sublist-dropdown .item-link,
						.site-header .widget_nav_menu .menu > li > .sub-menu a,
						.site-header .etheme_widget_menu .nav-sublist-dropdown .item-link',
						'function' => 'css',
						'property' => 'padding-right',
					),
					array(
						'choice'   => 'padding-right',
						'element'  => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after,
						.site-header .widget_nav_menu .menu > li > .sub-menu li.menu-item-has-children > a:after,
						.site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after',
						'function' => 'css',
						'property' => 'right',
					),
					// rtl
					array(
						'choice'   => 'padding-left',
						'element'  => 'body.rtl .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after,
						body.rtl .site-header .widget_nav_menu .menu > li > .sub-menu li.menu-item-has-children > a:after,
						body.rtl .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after',
						'function' => 'css',
						'property' => 'left',
					),
					array(
						'choice'        => 'padding-right',
						'element'       => 'body.rtl .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after,
						body.rtl .site-header .widget_nav_menu .menu > li > .sub-menu li.menu-item-has-children > a:after,
						body.rtl .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li.menu-item-has-children > a:after',
						'function'      => 'css',
						'property'      => 'right',
						'value_pattern' => 'auto'
					),
					array(
						'choice'        => 'border-top-width',
						'element'       => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li ul,
						.site-header .widget_nav_menu .menu > li > .sub-menu .sub-menu,
						.site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li ul',
						'function'      => 'css',
						'property'      => 'top',
						'value_pattern' => 'calc(var(--nav-sublist-dropdown-top) - $)'
					),
				)
			)
		),
		
		// menu_dropdown_border
		'menu_dropdown_border_et-desktop'              => array(
			'name'      => 'menu_dropdown_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'menu_dropdown_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'menu_dropdown',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $menu_settings['dropdown_selectors'],
					'property' => 'border-style',
				),
			)
		),
		
		// menu_dropdown_border_color_custom
		'menu_dropdown_border_color_custom_et-desktop' => array(
			'name'        => 'menu_dropdown_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'menu_dropdown_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'menu_dropdown',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $menu_settings['dropdown_selectors'],
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
