<?php
/**
 * The template created for displaying header button options
 *
 * @version 1.0.3
 * @since   1.4.0
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'button' => array(
			'name'       => 'button',
			'title'      => esc_html__( 'Button', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-editor-removeformatting',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/button', function ( $fields ) use ( $separators, $strings, $choices ) {
	$pages = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'page',
			'with_custom' => true
		)
	);
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'button_content_separator'                  => array(
			'name'     => 'button_content_separator',
			'type'     => 'custom',
			'settings' => 'button_content_separator',
			'section'  => 'button',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// button_text
		'button_text_et-desktop'                    => array(
			'name'      => 'button_text_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'button_text_et-desktop',
			'label'     => $strings['label']['button_text'],
			'section'   => 'button',
			'default'   => esc_html__( 'Button', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-button',
					'function' => 'html',
				),
			),
		),
		
		// button_link
		'button_link_et-desktop'                    => array(
			'name'            => 'button_link_et-desktop',
			'type'            => 'select',
			'settings'        => 'button_link_et-desktop',
			'label'           => $strings['label']['page_links'],
			'section'         => 'button',
			'multiple'        => 1,
			'choices'         => $pages,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'button_link_et-desktop' => array(
					'selector'        => '.header-button-wrapper',
					'render_callback' => 'header_button_callback'
				),
			),
		),
		
		// button_link
		'button_custom_link_et-desktop'             => array(
			'name'            => 'button_custom_link_et-desktop',
			'type'            => 'etheme-link',
			'settings'        => 'button_custom_link_et-desktop',
			'label'           => $strings['label']['custom_link'],
			'section'         => 'button',
			'default'         => '#',
			'active_callback' => array(
				array(
					'setting'  => 'button_link_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-button',
					'attr'     => 'href',
					'function' => 'html',
				),
			),
		),
		
		// style separator
		'button_style_separator'                    => array(
			'name'     => 'button_style_separator',
			'type'     => 'custom',
			'settings' => 'button_style_separator',
			'section'  => 'button',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// button_fonts
		'button_fonts_et-desktop'                   => array(
			'name'      => 'button_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'button_fonts_et-desktop',
			'label'     => $strings['label']['fonts'],
			'section'   => 'button',
			'default'   => array(
				'font-family'    => '',
				'variant'        => 'regular',
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
					'element' => '.et_b_header-button',
				),
			),
		),
		
		// button_zoom 
		'button_zoom_et-desktop'                    => array(
			'name'      => 'button_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'button_zoom_et-desktop',
			'label'     => $strings['label']['button_size_proportion'],
			'section'   => 'button',
			'default'   => 1,
			'choices'   => array(
				'min'  => '.2',
				'max'  => '3',
				'step' => '.1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-button',
					'property'      => 'font-size',
					'value_pattern' => 'calc(var(--content-zoom) * $)'
				),
			),
		),
		
		// button_content_align
		'button_content_align_et-desktop'           => array(
			'name'        => 'button_content_align_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'button_content_align_et-desktop',
			'label'       => $strings['label']['alignment'],
			'description' => $strings['description']['size_bigger_attention'],
			'section'     => 'button',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.header-button-wrapper.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.header-button-wrapper.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.header-button-wrapper.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// button_content_align
		'button_content_align_et-mobile'            => array(
			'name'        => 'button_content_align_et-mobile',
			'type'        => 'radio-buttonset',
			'settings'    => 'button_content_align_et-mobile',
			'label'       => $strings['label']['alignment'],
			'description' => $strings['description']['size_bigger_attention'],
			'section'     => 'button',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.header-button-wrapper.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.header-button-wrapper.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.header-button-wrapper.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// button_background_custom
		'button_background_custom_et-desktop'       => array(
			'name'      => 'button_background_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'button_background_custom_et-desktop',
			'label'     => $strings['label']['bg_color'],
			'section'   => 'button',
			'default'   => '#000000',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-button',
					'property' => 'background-color',
				),
			),
		),
		'button_color_et-desktop'                   => array(
			'name'        => 'button_color_et-desktop',
			'settings'    => 'button_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'button',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(button)(button_background_custom_et-desktop)',
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
					'element'  => '.et_b_header-button',
					'property' => 'color',
					'suffix'   => '!important'
				),
			),
		),
		
		// button_background_hover_custom
		'button_background_hover_custom_et-desktop' => array(
			'name'      => 'button_background_hover_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'button_background_hover_custom_et-desktop',
			'label'     => esc_html__( 'Background color (hover)', 'xstore-core' ),
			'section'   => 'button',
			'default'   => '#ffffff',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-button:hover',
					'property' => 'background-color',
				),
			),
		),
		'button_hover_color_et-desktop'             => array(
			'name'        => 'button_hover_color_et-desktop',
			'settings'    => 'button_hover_color_et-desktop',
			'label'       => $strings['label']['wcag_color_hover'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'button',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(button)(button_background_hover_custom_et-desktop)',
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
					'element'  => '.et_b_header-button:hover',
					'property' => 'color',
					'suffix'   => '!important'
				),
			),
		),
		
		// button_border_radius
		'button_border_radius_et-desktop'           => array(
			'name'      => 'button_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'button_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'button',
			'default'   => 0,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-button',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
		),
		'button_box_model_et-desktop'               => array(
			'name'        => 'button_box_model_et-desktop',
			'settings'    => 'button_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'button',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '5px',
				'padding-right'       => '10px',
				'padding-bottom'      => '5px',
				'padding-left'        => '10px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-button'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_header-button' )
		),
		
		// button_border
		'button_border_et-desktop'                  => array(
			'name'      => 'button_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'button_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'button',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-button',
					'property' => 'border-style'
				),
			),
		),
		
		// button_border_color_custom
		'button_border_color_custom_et-desktop'     => array(
			'name'        => 'button_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'button_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'button',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-button',
					'property' => 'border-color',
				),
			),
		),
		
		// advanced separator
		'button_advanced_separator'                 => array(
			'name'     => 'button_advanced_separator',
			'type'     => 'custom',
			'settings' => 'button_advanced_separator',
			'section'  => 'button',
			'default'  => $separators['advanced'],
			'priority' => 10,
		),
		
		// button_target
		'button_target_et-desktop'                  => array(
			'name'            => 'button_target_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'button_target_et-desktop',
			'label'           => $strings['label']['target_blank'],
			'section'         => 'button',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'button_target_et-desktop' => array(
					'selector'        => '.header-button-wrapper',
					'render_callback' => 'header_button_callback'
				),
			),
		),
		
		// button_no_follow
		'button_no_follow_et-desktop'               => array(
			'name'            => 'button_no_follow_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'button_no_follow_et-desktop',
			'label'           => $strings['label']['rel_no_follow'],
			'section'         => 'button',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'button_no_follow_et-desktop' => array(
					'selector'        => '.header-button-wrapper',
					'render_callback' => 'header_button_callback'
				),
			),
		),
	
	);
	
	unset($pages);
	
	return array_merge( $fields, $args );
	
} );
