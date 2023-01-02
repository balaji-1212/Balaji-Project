<?php
/**
 * The template created for displaying header secondary menu element options
 *
 * @version 1.0.5
 * @since   1.4.0
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'main_menu_2' => array(
			'name'       => 'main_menu_2',
			'title'      => esc_html__( 'Secondary menu', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-menu',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/main_menu_2', function ( $fields ) use ( $separators, $strings, $choices, $menu_settings ) {
	$menus = et_b_get_terms( 'nav_menu' );
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		
		// content separator
		'menu_2_content_separator'              => array(
			'name'     => 'menu_2_content_separator',
			'type'     => 'custom',
			'settings' => 'menu_2_content_separator',
			'section'  => 'main_menu_2',
			'default'  => $separators['content'],
			'priority' => 1,
		),
		
		// menu_2_item_style
		'menu_2_item_style_et-desktop'          => array(
			'name'            => 'menu_2_item_style_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'menu_2_item_style_et-desktop',
			'label'           => $strings['label']['style'],
			'section'         => 'main_menu_2',
			'default'         => 'underline',
			'choices'         => $menu_settings['style'],
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.header-main-menu2',
					'function' => 'toggleClass',
					'class'    => 'menu-items-none',
					'value'    => 'none'
				),
				array(
					'element'  => '.header-main-menu2',
					'function' => 'toggleClass',
					'class'    => 'menu-items-underline',
					'value'    => 'underline'
				),
				array(
					'element'  => '.header-main-menu2',
					'function' => 'toggleClass',
					'class'    => 'menu-items-overline',
					'value'    => 'overline'
				),
				array(
					'element'  => '.header-main-menu2',
					'function' => 'toggleClass',
					'class'    => 'menu-items-dots',
					'value'    => 'dots'
				),
				array(
					'element'  => '.header-main-menu2',
					'function' => 'toggleClass',
					'class'    => 'menu-items-arrow',
					'value'    => 'arrow'
				),
				array(
					'element'  => '.header-main-menu2',
					'function' => 'toggleClass',
					'class'    => 'menu-items-custom',
					'value'    => 'custom'
				),
			),
			'partial_refresh' => array(
				'menu_2_item_style_et-desktop' => array(
					'selector'        => '.header-main-menu2',
					'render_callback' => 'main_menu2_callback'
				),
			),
			'priority'        => 2,
		),
		
		// menu_item_separator 
		'menu_2_item_dots_separator_et-desktop' => array(
			'name'            => 'menu_2_item_dots_separator_et-desktop',
			'type'            => 'select',
			'settings'        => 'menu_2_item_dots_separator_et-desktop',
			'label'           => $menu_settings['strings']['label']['sep_type'],
			'section'         => 'main_menu_2',
			'default'         => '2022',
			'choices'         => $menu_settings['separators'],
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'dots',
				),
			),
			'transport'       => 'auto',
			'partial_refresh' => array(
				'menu_2_item_dots_separator_et-desktop' => array(
					'selector'        => '.header-main-menu2',
					'render_callback' => 'main_menu2_callback'
				),
			),
			'priority'        => 3,
		),
		
		// main_menu_2_term 
		'main_menu_2_term'                      => array(
			'name'            => 'main_menu_2_term',
			'type'            => 'select',
			'settings'        => 'main_menu_2_term',
			'label'           => $strings['label']['select_menu'],
			'section'         => 'main_menu_2',
			'choices'         => $menus,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'main_menu_2_term' => array(
					'selector'        => '.header-main-menu2',
					'render_callback' => 'main_menu2_callback'
				),
			),
			'priority'        => 4,
		),
		
		// menu_2_one_page
		'menu_2_one_page'                       => array(
			'name'            => 'menu_2_one_page',
			'type'            => 'toggle',
			'settings'        => 'menu_2_one_page',
			'label'           => $menu_settings['strings']['label']['one_page'],
			'description'     => $menu_settings['strings']['description']['one_page'],
			'section'         => 'main_menu_2',
			'default'         => '0',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'menu_2_one_page' => array(
					'selector'        => '.header-main-menu2',
					'render_callback' => 'main_menu2_callback'
				),
			),
			'priority'        => 5,
		),
		
		'menu_2_dropdown_full_width'                           => array(
			'name'            => 'menu_2_dropdown_full_width',
			'type'            => 'toggle',
			'settings'        => 'menu_2_dropdown_full_width',
			'label'           => $menu_settings['strings']['label']['menu_dropdown_full_width'],
			'description'     => $menu_settings['strings']['description']['menu_dropdown_full_width'],
			'section'         => 'main_menu_2',
			'default'         => '0',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'menu_2_dropdown_full_width' => array(
					'selector'        => '.header-main-menu2',
					'render_callback' => 'main_menu2_callback'
				),
			),
			'priority'        => 6,
		),
		
		// menu_2_arrows 
		'menu_2_arrows'                                        => array(
			'name'      => 'menu_2_arrows',
			'type'      => 'toggle',
			'settings'  => 'menu_2_arrows',
			'label'     => $menu_settings['strings']['label']['arrows'],
			'section'   => 'main_menu_2',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.header-main-menu2 .item-level-0 > a > svg.arrow',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => false
				),
			),
			'priority'  => 7,
		),
		
		// style separator
		'menu_2_style_separator'                               => array(
			'name'     => 'menu_2_style_separator',
			'type'     => 'custom',
			'settings' => 'menu_2_style_separator',
			'section'  => 'main_menu_2',
			'default'  => $separators['style'],
			'priority' => 8,
		),
		
		// menu_2_zoom
		'menu_2_zoom_et-desktop'                               => array(
			'name'      => 'menu_2_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'menu_2_zoom_et-desktop',
			'label'     => $strings['label']['content_zoom'],
			'section'   => 'main_menu_2',
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
					'element'       => '.header-main-menu2.et_element-top-level',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
			'priority'  => 9,
		),
		
		// menu_2_zoom
		'menu_2_zoom_et-mobile'                                => array(
			'name'      => 'menu_2_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'menu_2_zoom_et-mobile',
			'label'     => $strings['label']['content_zoom'],
			'section'   => 'main_menu_2',
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
					'element'       => '.mobile-device .header-main-menu2.et_element-top-level',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
			'priority'  => 10,
		),
		
		// menu_2_alignment
		'menu_2_alignment_et-desktop'                          => array(
			'name'      => 'menu_2_alignment_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'menu_2_alignment_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'main_menu_2',
			'default'   => 'center',
			'choices'   => $choices['alignment2'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level',
					'property' => 'justify-content',
				),
			),
			'priority'  => 11,
		),
		
		// menu_2_item_settings
		'menu_2_item_fonts_et-desktop'                         => array(
			'name'      => 'menu_2_item_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'menu_2_item_fonts_et-desktop',
			'section'   => 'main_menu_2',
			'default'   => $menu_settings['fonts'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-main-menu2.et_element-top-level .menu > li > a',
				),
			),
			'priority'  => 12,
		),
		
		// menu_2_item_border_radius
		'menu_2_item_border_radius_et-desktop'                 => array(
			'name'            => 'menu_2_item_border_radius_et-desktop',
			'type'            => 'slider',
			'settings'        => 'menu_2_item_border_radius_et-desktop',
			'label'           => $strings['label']['border_radius'],
			'section'         => 'main_menu_2',
			'default'         => 30,
			'choices'         => array(
				'min'  => '0',
				'max'  => '70',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level.menu-items-custom .menu > li > a',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
			'priority'        => 13,
		),
		
		// menu_item_color
		'menu_2_item_color_custom_et-desktop'                  => array(
			'name'            => 'menu_2_item_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_color_custom_et-desktop',
			'label'           => $menu_settings['strings']['label']['color'],
			'section'         => 'main_menu_2',
			'default'         => '#555555',
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element'  => '.header-main-menu2.et_element-top-level.menu-items-custom .menu > li > a',
					'property' => 'color',
				),
			),
			'priority'        => 14,
		),
		
		// menu_item_element_color_custom
		'menu_2_item_background_color_custom_et-desktop'       => array(
			'name'            => 'menu_2_item_background_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_background_color_custom_et-desktop',
			'label'           => $strings['label']['bg_color'],
			'section'         => 'main_menu_2',
			'default'         => '#c62828',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level.menu-items-custom .menu > li > a',
					'property' => 'background-color',
				),
			),
			'priority'        => 15,
		),
		
		// menu_2_item_hover_color_custom
		'menu_2_item_hover_color_custom_et-desktop'            => array(
			'name'      => 'menu_2_item_hover_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'menu_2_item_hover_color_custom_et-desktop',
			'label'     => $menu_settings['strings']['label']['hover_color'],
			'section'   => 'main_menu_2',
			'default'   => '#222222',
			'transport' => 'auto',
			'output'    => array(
				array(
					'element'  => '.header-main-menu2.et_element-top-level .menu > li > a:hover, .header-main-menu2.et_element-top-level .menu > .current-menu-item > a, .header-main-menu2.et_element-top-level.menu-items-custom .menu > li > a:hover, .header-main-menu2.et_element-top-level.menu-items-custom .menu > .current-menu-item > a',
					'property' => 'color',
				),
			),
			'priority'  => 16,
		),
		
		// menu_item_line_hover_color_custom
		'menu_2_item_line_hover_color_custom_et-desktop'       => array(
			'name'            => 'menu_2_item_line_hover_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_line_hover_color_custom_et-desktop',
			'label'           => $menu_settings['strings']['label']['line_color'],
			'description'     => $menu_settings['strings']['description']['line_color'],
			'section'         => 'main_menu_2',
			'default'         => '#555555',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => 'in',
					'value'    => array( 'underline', 'overline' ),
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level .menu > li > a:before, .header-main-menu2.et_element-top-level .menu > .current-menu-item > a:before',
					'property' => 'background-color'
				),
			),
			'priority'        => 17,
		),
		
		// menu_item_dots_color
		'menu_2_item_dots_color_et-desktop'                    => array(
			'name'            => 'menu_2_item_dots_color_et-desktop',
			'type'            => 'select',
			'settings'        => 'menu_2_item_dots_color_et-desktop',
			'label'           => $menu_settings['strings']['label']['dots_color'],
			'description'     => $menu_settings['strings']['description']['dots_color'],
			'section'         => 'main_menu_2',
			'default'         => 'current',
			'choices'         => $choices['colors'],
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'dots',
				),
			),
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.header-main-menu2.et_element-top-level .menu > li .et_b_header-menu-sep',
					'property'      => 'color',
					'value_pattern' => 'var(--$-color)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.header-main-menu2.et_element-top-level .menu > li .et_b_header-menu-sep',
					'property'      => 'opacity',
					'value_pattern' => '.5'
				)
			),
			'priority'        => 18,
		),
		
		// menu_item_dots_color_custom
		'menu_2_item_dots_color_custom_et-desktop'             => array(
			'name'            => 'menu_2_item_dots_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_dots_color_custom_et-desktop',
			'label'           => $menu_settings['strings']['label']['dots_color'],
			'description'     => $menu_settings['strings']['description']['dots_color'],
			'section'         => 'main_menu_2',
			'default'         => '#555555',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'dots',
				),
				array(
					'setting'  => 'menu_2_item_dots_color_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level .menu > li .et_b_header-menu-sep',
					'property' => 'color'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.header-main-menu2.et_element-top-level .menu > li .et_b_header-menu-sep',
					'property'      => 'opacity',
					'value_pattern' => '1'
				),
			),
			'priority'        => 19,
		),
		
		// menu_item_arrow_hover_color_custom
		'menu_2_item_arrow_hover_color_custom_et-desktop'      => array(
			'name'            => 'menu_2_item_arrow_hover_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_arrow_hover_color_custom_et-desktop',
			'label'           => $menu_settings['strings']['label']['arrow_color'],
			'description'     => $menu_settings['strings']['description']['arrow_color'],
			'section'         => 'main_menu_2',
			'default'         => '#555555',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => 'in',
					'value'    => array( 'arrow' ),
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level .menu > li > a:before, .header-main-menu2.et_element-top-level .menu > .current-menu-item > a:before',
					'property' => 'border-bottom-color'
				),
			),
			'priority'        => 20,
		),
		
		// menu_item_background_hover_color_custom
		'menu_2_item_background_hover_color_custom_et-desktop' => array(
			'name'            => 'menu_2_item_background_hover_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_background_hover_color_custom_et-desktop',
			'label'           => $menu_settings['strings']['label']['bg_hover_color'],
			'description'     => $menu_settings['strings']['description']['bg_hover_color'],
			'section'         => 'main_menu_2',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level.menu-items-custom .menu > li > a:hover, .header-main-menu2.et_element-top-level.menu-items-custom .menu > .current-menu-item > a',
					'property' => 'background-color'
				),
			),
			'priority'        => 21,
		),
		'menu_2_item_box_model_et-desktop'                     => array(
			'name'        => 'menu_2_item_box_model_et-desktop',
			'settings'    => 'menu_2_item_box_model_et-desktop',
			'label'       => $menu_settings['strings']['label']['item_box_model'],
			'description' => $menu_settings['strings']['description']['item_box_model'],
			'type'        => 'kirki-box-model',
			'section'     => 'main_menu_2',
			'default'     => $menu_settings['item_box_model'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-main-menu2.et_element-top-level .menu > li > a',
				),
			),
			'transport'   => 'postMessage',
			'priority'    => 22,
			'js_vars'     => box_model_output( '.header-main-menu2.et_element-top-level .menu > li > a' )
		),
		
		// menu_2_nice_space
		'menu_2_nice_space_et-desktop'                         => array(
			'name'     => 'menu_2_nice_space_et-desktop',
			'type'     => 'toggle',
			'settings' => 'menu_2_nice_space_et-desktop',
			'label'    => $menu_settings['strings']['label']['nice_space'],
			'section'  => 'main_menu_2',
			'default'  => '0',
			'priority' => 23,
		),
		
		// menu_2_item_border
		'menu_2_item_border_et-desktop'                        => array(
			'name'            => 'menu_2_item_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'menu_2_item_border_et-desktop',
			'label'           => $strings['label']['border_style'],
			'section'         => 'main_menu_2',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '!=',
					'value'    => 'dots',
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level .menu > li > a',
					'property' => 'border-style',
				),
			),
			'priority'        => 24,
		),
		
		// menu_2_item_border_color_custom
		'menu_2_item_border_color_custom_et-desktop'           => array(
			'name'            => 'menu_2_item_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_border_color_custom_et-desktop',
			'label'           => $menu_settings['strings']['label']['border_hover_color'],
			'description'     => $strings['description']['border_color'],
			'section'         => 'main_menu_2',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '!=',
					'value'    => 'dots',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level .menu > li > a',
					'property' => 'border-color',
				),
			),
			'priority'        => 25,
		),
		
		// menu_item_border_hover_color_custom
		'menu_2_item_border_hover_color_custom_et-desktop'     => array(
			'name'            => 'menu_2_item_border_hover_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'menu_2_item_border_hover_color_custom_et-desktop',
			'label'           => esc_html__( 'Border color (hover, active)', 'xstore-core' ),
			'description'     => esc_html__( 'You have to set up border width via Computed box below. To have correct invisible border, please set up alpha chanel to 0', 'xstore-core' ),
			'section'         => 'main_menu_2',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'menu_2_item_style_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-main-menu2.et_element-top-level .menu > li > a:hover, .header-main-menu2.et_element-top-level .menu > .current-menu-item > a',
					'property' => 'border-color',
				),
			),
			'priority'        => 26,
		),
		
		// go_to_menu_2_dropdown
		'go_to_section_secondary_menu2'                        => array(
			'name'     => 'go_to_section_secondary_menu2',
			'type'     => 'custom',
			'settings' => 'go_to_section_secondary_menu2',
			'section'  => 'main_menu_2',
			'default'  => '<span class="et_edit" data-parent="menu_dropdown" data-section="menu_2_dropdown_style_separator" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Dropdown settings', 'xstore-core' ) . '</span>',
			'priority' => 27,
		),
	
	);
	
	unset($menus);
	
	return array_merge( $fields, $args );
	
} );
