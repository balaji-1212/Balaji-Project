<?php
/**
 * The template created for displaying bottom header options
 *
 * @version 1.0.2
 * @since   1.4.0
 * last changes in 1.5.5
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'bottom_header' => array(
			'name'       => 'bottom_header',
			'title'      => esc_html__( 'Bottom header', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-arrow-down-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/bottom_header', function ( $fields ) use ( $separators, $strings, $choices, $box_models ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator 
		'bottom_header_content_separator'     => array(
			'name'     => 'bottom_header_content_separator',
			'type'     => 'custom',
			'settings' => 'bottom_header_content_separator',
			'section'  => 'bottom_header',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// bottom_header_wide
		'bottom_header_wide_et-desktop'       => array(
			'name'      => 'bottom_header_wide_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'bottom_header_wide_et-desktop',
			'label'     => $strings['label']['wide_header'],
			'section'   => 'bottom_header',
			'default'   => '0',
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.header-bottom-wrapper .header-bottom > .et-row-container',
					'function' => 'toggleClass',
					'class'    => 'et-container',
					'value'    => false
				),
			),
		),
		
		// style separator
		'bottom_header_style_separator'       => array(
			'name'     => 'bottom_header_style_separator',
			'type'     => 'custom',
			'settings' => 'bottom_header_style_separator',
			'section'  => 'bottom_header',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// bottom_header_height
		'bottom_header_height_et-desktop'     => array(
			'name'      => 'bottom_header_height_et-desktop',
			'type'      => 'slider',
			'settings'  => 'bottom_header_height_et-desktop',
			'label'     => $strings['label']['min_height'],
			'section'   => 'bottom_header',
			'default'   => 40,
			'choices'   => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-bottom .et-wrap-columns, .header-bottom .widget_nav_menu .menu > li > a',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-bottom .widget_nav_menu .menu > li > a, .header-bottom #lang_sel a.lang_sel_sel, .header-bottom .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				)
			),
		),
		
		// bottom_header_height_et-mobile
		'bottom_header_height_et-mobile'      => array(
			'name'      => 'bottom_header_height_et-mobile',
			'type'      => 'slider',
			'settings'  => 'bottom_header_height_et-mobile',
			'label'     => $strings['label']['min_height'],
			'section'   => 'bottom_header',
			'default'   => 40,
			'choices'   => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .header-bottom .et-wrap-columns, .mobile-header-wrapper .header-bottom .widget_nav_menu .menu > li > a',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .header-bottom .widget_nav_menu .menu > li > a, .mobile-header-wrapper .header-bottom #lang_sel a.lang_sel_sel, .mobile-header-wrapper .header-bottom .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				)
			),
		),
		
		// bottom_header_font_size
		'bottom_header_fonts_et-desktop'      => array(
			'name'      => 'bottom_header_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'bottom_header_fonts_et-desktop',
			'label'     => $strings['label']['fonts'],
			'section'   => 'bottom_header',
			'default'   => array(
				// 'font-family'    => '',
				// 'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'none',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-bottom',
				),
			),
		),
		
		// bottom_header_zoom_et-desktop
		'bottom_header_zoom_et-desktop'       => array(
			'name'      => 'bottom_header_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'bottom_header_zoom_et-desktop',
			'label'     => $strings['label']['content_zoom'],
			'section'   => 'bottom_header',
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
					'element'       => '.header-bottom',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// bottom_header_zoom_et-desktop
		'bottom_header_zoom_et-mobile'        => array(
			'name'      => 'bottom_header_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'bottom_header_zoom_et-mobile',
			'label'     => $strings['label']['content_zoom'],
			'section'   => 'bottom_header',
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
					'element'       => '.mobile-header-wrapper .header-bottom',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// bottom_header_background
		'bottom_header_background_et-desktop' => array(
			'name'        => 'bottom_header_background_et-desktop',
			'type'        => 'background',
			'settings'    => 'bottom_header_background_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'bottom_header',
			'default'     => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-bottom',
				),
			),
		),
		
		// bottom_header_background
		'bottom_header_background_et-mobile'  => array(
			'name'        => 'bottom_header_background_et-mobile',
			'type'        => 'background',
			'settings'    => 'bottom_header_background_et-mobile',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'bottom_header',
			'default'     => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .header-bottom',
				),
			),
		),
		
		'bottom_header_color_et-desktop' => array(
			'name'        => 'bottom_header_color_et-desktop',
			'settings'    => 'bottom_header_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'bottom_header',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(bottom_header)(bottom_header_background_et-desktop)[background-color]',
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
					'element'  => '.header-bottom',
					'property' => 'color'
				)
			)
		),
		
		'bottom_header_color_et-mobile'                => array(
			'name'        => 'bottom_header_color_et-mobile',
			'settings'    => 'bottom_header_color_et-mobile',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'bottom_header',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(bottom_header)(bottom_header_background_et-mobile)[background-color]',
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
					'element'  => '.mobile-header-wrapper .header-bottom',
					'property' => 'color'
				)
			)
		),
		'bottom_header_box_model_et-desktop'           => array(
			'name'        => 'bottom_header_box_model_et-desktop',
			'settings'    => 'bottom_header_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'bottom_header',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-bottom',
				),
				array(
					'choice'        => 'margin-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.sticky-on .header-bottom',
					'property'      => '--sticky-on-space-fix',
					'value_pattern' => 'calc(var(--sticky-on-space-fix2, 0px) + $)',
				),
				array(
					'choice'        => 'margin-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.sticky-on .header-bottom',
					'property'      => 'max-width',
					'value_pattern' => 'calc(100% - var(--sticky-on-space-fix, 0px) - $)'
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => array_merge(
				box_model_output( '.header-bottom' ),
				array(
					array(
						'choice'        => 'margin-left',
						'element'       => '.sticky-on .header-bottom',
						'property'      => '--sticky-on-space-fix',
						'type'          => 'css',
						'value_pattern' => 'calc(var(--sticky-on-space-fix2, 0px) + $)',
					),
					array(
						'choice'        => 'margin-right',
						'element'       => '.sticky-on .header-bottom',
						'property'      => 'max-width',
						'type'          => 'css',
						'value_pattern' => 'calc(100% - var(--sticky-on-space-fix, 0px) - $)'
					)
				)
			),
		),
		
		// bottom_header_border
		'bottom_header_border_et-desktop'              => array(
			'name'      => 'bottom_header_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'bottom_header_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'bottom_header',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-bottom',
					'property' => 'border-style'
				)
			)
		),
		
		// bottom_header_border_color_custom
		'bottom_header_border_color_custom_et-desktop' => array(
			'name'        => 'bottom_header_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'bottom_header_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'bottom_header',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-bottom',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
