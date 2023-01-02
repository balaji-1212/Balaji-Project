<?php
/**
 * The template created for displaying header newsletter element options
 *
 * @version 1.0.3
 * @since   1.4.0
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'newsletter' => array(
			'name'       => 'newsletter',
			'title'      => esc_html__( 'Newsletter', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-email-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/newsletter', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {
	$sections = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	);
	
	$args = array();
	// Array of fields
	$args = array(
		// content separator
		'newsletter_content_separator'                      => array(
			'name'     => 'newsletter_content_separator',
			'type'     => 'custom',
			'settings' => 'newsletter_content_separator',
			'section'  => 'newsletter',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// newsletter_shown_on
		'newsletter_shown_on_et-desktop'                    => array(
			'name'     => 'newsletter_shown_on_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'newsletter_shown_on_et-desktop',
			'label'    => esc_html__( 'Newsletter on', 'xstore-core' ),
			'section'  => 'newsletter',
			'default'  => 'click',
			'multiple' => 1,
			'choices'  => array(
				'delay'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/newsletter/Delay.svg',
				'click'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/newsletter/Click.svg',
				'mouse_out' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/newsletter/Mouse-out.svg',
			),
		),
		
		// newsletter_delay
		'newsletter_delay_et-desktop'                       => array(
			'name'            => 'newsletter_delay_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'newsletter_delay_et-desktop',
			'label'           => esc_html__( 'Delay (ms)', 'xstore-core' ),
			'section'         => 'newsletter',
			'default'         => esc_html__( '300', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_shown_on_et-desktop',
					'operator' => '==',
					'value'    => 'delay',
				),
			)
		),
		
		// newsletter_close_button_action
		'newsletter_close_button_action_et-desktop'         => array(
			'name'            => 'newsletter_close_button_action_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'newsletter_close_button_action_et-desktop',
			'label'           => esc_html__( 'Close forever', 'xstore-core' ),
			'description'     => esc_html__( 'Close till the clearing of the browser cookies', 'xstore-core' ),
			'section'         => 'newsletter',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_shown_on_et-desktop',
					'operator' => '!=',
					'value'    => 'click',
				),
			)
		),
		
		// newsletter_icon
		'newsletter_icon_et-desktop'                        => array(
			'name'            => 'newsletter_icon_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'newsletter_icon_et-desktop',
			'label'           => $strings['label']['icon'],
			'section'         => 'newsletter',
			'default'         => 'type1',
			'choices'         => array(
				'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/newsletter/Newsletter.svg',
				'none'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'newsletter_icon' => array(
					'selector'        => '.et_b_header-newsletter .et_b-icon',
					'render_callback' => function () {
						$type = get_theme_mod( 'newsletter_icon_et-desktop', 'type1' );
						if ( $type != 'none' ) {
							return ( ! get_theme_mod( 'bold_icons', 0 ) ) ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M23.928 5.424c-0.024-0.648-0.552-1.152-1.176-1.152h-21.504c-0.648 0-1.176 0.528-1.176 1.176v13.128c0 0.648 0.528 1.176 1.176 1.176h21.504c0.648 0 1.176-0.528 1.176-1.176v-13.152zM22.512 5.4l-10.512 6.576-10.512-6.576h21.024zM1.248 16.992v-10.416l7.344 4.584-7.344 5.832zM1.224 18.456l8.352-6.624 2.064 1.32c0.192 0.12 0.432 0.12 0.624 0l2.064-1.32 8.4 6.648 0.024 0.096c0 0 0 0.024-0.024 0.024h-21.48c-0.024 0-0.024 0-0.024-0.024v-0.12zM22.752 6.648v10.344l-7.344-5.808 7.344-4.536z"></path></svg>' : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="M15.952 3.744c-0.016-0.544-0.464-0.976-0.992-0.976h-13.92c-0.544 0-0.992 0.448-0.992 0.992v8.496c0 0.544 0.448 0.992 0.992 0.992h13.904c0.544 0 0.992-0.448 0.992-0.992l0.016-8.512zM13.984 3.968l-5.984 3.744-5.984-3.744h11.968zM1.28 10.752v-5.84l4.112 2.56-4.112 3.28zM6.448 8.176l1.2 0.768c0.208 0.128 0.448 0.128 0.656 0l1.2-0.768 4.88 3.856h-12.8l4.864-3.856zM14.72 4.96v5.792l-4.112-3.248 4.112-2.544z"></path></svg>';
						}
						
						return '';
					},
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-newsletter .et_b-icon',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => 'none'
				),
			),
		),
		
		// newsletter_label_show
		'newsletter_label_show_et-desktop'                  => array(
			'name'      => 'newsletter_label_show_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'newsletter_label_show_et-desktop',
			'label'     => esc_html__( 'Show label', 'xstore-core' ),
			'section'   => 'newsletter',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-newsletter .et-element-label',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => false
				),
			),
		),
		
		// newsletter_label
		'newsletter_label_et-desktop'                       => array(
			'name'            => 'newsletter_label_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'newsletter_label_et-desktop',
			'section'         => 'newsletter',
			'default'         => esc_html__( 'Newsletter', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_label_show_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-newsletter .et-element-label',
					'function' => 'html',
				),
			),
		),
		
		// content separator
		'newsletter_content_popup_separator'                => array(
			'name'     => 'newsletter_content_popup_separator',
			'type'     => 'custom',
			'settings' => 'newsletter_content_popup_separator',
			'section'  => 'newsletter',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-left: 3px;">' . esc_html__( 'Content', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		// newsletter_title
		'newsletter_title_et-desktop'                       => array(
			'name'            => 'newsletter_title_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'newsletter_title_et-desktop',
			'label'           => esc_html__( 'Title', 'xstore-core' ),
			'section'         => 'newsletter',
			'default'         => esc_html__( 'Title', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_sections_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'newsletter_title_et-desktop' => array(
					'selector'        => '.header-newsletter-popup .et-popup-content',
					'render_callback' => 'header_newsletter_content_callback',
				),
			),
		),
		
		// newsletter_content
		'newsletter_content_et-desktop'                     => array(
			'name'            => 'newsletter_content_et-desktop',
			'type'            => 'editor',
			'settings'        => 'newsletter_content_et-desktop',
			'label'           => esc_html__( 'Text', 'xstore-core' ),
			'section'         => 'newsletter',
			'default'         => '<p>You can add any HTML here (admin -&gt; Theme Options -&gt; Header builder -&gt; Newsletter).<br /> We suggest you create a static block and use it by turning on the settings below</p>',
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_sections_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'newsletter_content_et-desktop' => array(
					'selector'        => '.header-newsletter-popup .et-popup-content',
					'render_callback' => 'header_newsletter_content_callback',
				),
			),
		),
		
		// newsletter_sections
		'newsletter_sections_et-desktop'                    => array(
			'name'            => 'newsletter_sections_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'newsletter_sections_et-desktop',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'newsletter',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'newsletter_sections_et-desktop' => array(
					'selector'        => '.header-newsletter-popup .et-popup-content',
					'render_callback' => 'header_newsletter_content_callback',
				),
			),
		),
		
		// newsletter_section
		'newsletter_section_et-desktop'                     => array(
			'name'            => 'newsletter_section_et-desktop',
			'type'            => 'select',
			'settings'        => 'newsletter_section_et-desktop',
			'label'           => sprintf( esc_html__( 'Choose %1s', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'newsletter',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_sections_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'newsletter_section_et-desktop' => array(
					'selector'        => '.header-newsletter-popup .et-popup-content',
					'render_callback' => 'header_newsletter_content_callback',
				),
			),
		),
		
		// style separator
		'newsletter_style_separator'                        => array(
			'name'     => 'newsletter_style_separator',
			'type'     => 'custom',
			'settings' => 'newsletter_style_separator',
			'section'  => 'newsletter',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// newsletter_content_alignment
		'newsletter_content_alignment_et-desktop'           => array(
			'name'      => 'newsletter_content_alignment_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'newsletter_content_alignment_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'newsletter',
			'default'   => 'start',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.header-newsletter-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.header-newsletter-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.header-newsletter-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-end',
					'value'    => 'end'
				),
			),
		),
		
		// newsletter_content_width_height
		'newsletter_content_width_height_et-desktop'        => array(
			'name'      => 'newsletter_content_width_height_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'newsletter_content_width_height_et-desktop',
			'label'     => esc_html__( 'Popup width and height', 'xstore-core' ),
			'section'   => 'newsletter',
			'default'   => 'auto',
			'multiple'  => 1,
			'choices'   => array(
				'auto'   => esc_html__( 'Auto', 'xstore-core' ),
				'custom' => esc_html__( 'Custom', 'xstore-core' ),
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.header-newsletter-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'et-popup-content-custom-dimenstions',
					'value'    => 'custom'
				),
			),
		),
		
		// newsletter_content_width_height_custom
		'newsletter_content_width_height_custom_et-desktop' => array(
			'name'      => 'newsletter_content_width_height_custom_et-desktop',
			'type'      => 'dimensions',
			'settings'  => 'newsletter_content_width_height_custom_et-desktop',
			'section'   => 'newsletter',
			'default'   => array(
				'width'  => '550px',
				'height' => '250px',
			),
			'choices'   => array(
				'labels' => array(
					'width'  => esc_html__( 'Width (for custom only)', 'xstore-core' ),
					'height' => esc_html__( 'Height (for custom only)', 'xstore-core' ),
				),
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'newsletter_content_width_height_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'custom',
			// 	),
			// ),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'width',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-newsletter-popup .et-popup-content-custom-dimenstions',
					'property' => 'width',
				),
				array(
					'choice'   => 'height',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-newsletter-popup .et-popup-content-custom-dimenstions',
					'property' => 'height',
				)
			),
		),
		
		// newsletter_background
		'newsletter_background_et-desktop'                  => array(
			'name'        => 'newsletter_background_et-desktop',
			'type'        => 'background',
			'settings'    => 'newsletter_background_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'newsletter',
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
					'element' => '.header-newsletter-popup .et-popup-content',
				),
			),
		),
		
		'newsletter_color_et-desktop' => array(
			'name'        => 'newsletter_color_et-desktop',
			'settings'    => 'newsletter_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'newsletter',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(newsletter)(newsletter_background_et-desktop)[background-color]',
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
					'element'  => '.header-newsletter-popup .et-popup-content, .header-newsletter-popup .et-close-popup',
					'property' => 'color'
				)
			)
		),
		
		'newsletter_box_model_et-desktop'           => array(
			'name'        => 'newsletter_box_model_et-desktop',
			'settings'    => 'newsletter_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'newsletter',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '15px',
				'padding-right'       => '15px',
				'padding-bottom'      => '15px',
				'padding-left'        => '15px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.header-newsletter-popup .et-popup-content',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.header-newsletter-popup .et-popup-content' )
		),
		
		// newsletter_border
		'newsletter_border_et-desktop'              => array(
			'name'      => 'newsletter_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'newsletter_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'newsletter',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-newsletter-popup .et-popup-content',
					'property' => 'border-style'
				),
			),
		),
		
		// newsletter_border_color_custom
		'newsletter_border_color_custom_et-desktop' => array(
			'name'        => 'newsletter_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'newsletter_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'newsletter',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-newsletter-popup .et-popup-content',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	unset($sections);
	
	return array_merge( $fields, $args );
	
} );
