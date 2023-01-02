<?php
/**
 * The template created for displaying global sections options
 *
 * @version 0.0.1
 * @since   4.4
 */

// section general
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'age_verify_popup' => array(
			'name'       => 'age_verify_popup',
			'title'      => esc_html__( 'Age Verify Popup', 'xstore-core' ),
			'icon'       => 'dashicons-lock',
			'priority'   => 7,
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/age_verify_popup', function ( $fields ) use ( $separators, $strings, $choices, $sep_style, $is_rtl ) {
//	$sections = et_b_get_posts(
//		array(
//			'post_per_page' => -1,
//			'nopaging'      => true,
//			'post_type'     => 'staticblocks',
//			'with_none' => true
//		)
//	);
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		// newsletter_close_button_action
		'age_verify_popup_switcher'          => array(
			'name'     => 'age_verify_popup_switcher',
			'type'     => 'toggle',
			'settings' => 'age_verify_popup_switcher',
			'label'    => esc_html__( 'Enable Age verify Popup', 'xstore-core' ),
			'section'  => 'age_verify_popup',
			'default'  => '0',
//			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et-age-verify-popup',
//					'function' => 'toggleClass',
//					'class'    => 'hidden',
//					'value'    => false
//				),
//			)
		),
		
		'age_verify_popup_switcher_dev_mode'          => array(
			'name'     => 'age_verify_popup_switcher_dev_mode',
			'type'     => 'toggle',
			'settings' => 'age_verify_popup_switcher_dev_mode',
			'label'    => esc_html__( 'Developer mode', 'xstore-core' ),
			'description' => esc_html__('If you need some time to test your Age verify Popup - use developer mode. Note: Popup will be shown on each page load in dev mode.', 'xstore-core'),
			'section'  => 'age_verify_popup',
			'default'  => '0',
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
		),
		
		// style separator
		'age_verify_popup_content_separator' => array(
			'name'            => 'age_verify_popup_content_separator',
			'type'            => 'custom',
			'settings'        => 'age_verify_popup_content_separator',
			'section'         => 'age_verify_popup',
			'default'         => $separators['content'],
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
		),
		
		// age_verify_popup_title
		'age_verify_popup_title'             => array(
			'name'            => 'age_verify_popup_title',
			'type'            => 'etheme-text',
			'settings'        => 'age_verify_popup_title',
			'label'           => esc_html__( 'Title', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => esc_html__( 'Before you enter', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_title' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:first-child',
					'render_callback' => 'et_age_verify_popup_content_callback',
				),
			),
		),
		
		// age_verify_popup_content
		'age_verify_popup_content'           => array(
			'name'            => 'age_verify_popup_content',
			'type'            => 'editor',
			'settings'        => 'age_verify_popup_content',
			'label'           => esc_html__( 'Content', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => '<p>' . esc_html__( 'Are you over 18 years old?', 'xstore-core' ) . '<br/>' .
			                     esc_html__( 'This website requires you to be 18 years or older to enter our website and see the content.', 'xstore-core' ) . '</p>',
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_content' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:first-child',
					'render_callback' => 'et_age_verify_popup_content_callback',
				),
			),
		),
		
		'age_verify_popup_agree_button_text' => array(
			'name'            => 'age_verify_popup_agree_button_text',
			'type'            => 'etheme-text',
			'settings'        => 'age_verify_popup_agree_button_text',
			'label'           => esc_html__( 'Agree button text', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => esc_html__( 'Yes, I am', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_agree_button_text' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:first-child',
					'render_callback' => 'et_age_verify_popup_content_callback',
				),
			),
		),
		
		'age_verify_popup_deny_button_text' => array(
			'name'            => 'age_verify_popup_deny_button_text',
			'type'            => 'etheme-text',
			'settings'        => 'age_verify_popup_deny_button_text',
			'label'           => esc_html__( 'Deny button text', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => esc_html__( 'No, I am not', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_deny_button_text' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:first-child',
					'render_callback' => 'et_age_verify_popup_content_callback',
				),
			),
		),
		
		'age_verify_popup_deny_button_redirect'        => array(
			'name'            => 'age_verify_popup_deny_button_redirect',
			'type'            => 'etheme-text',
			'settings'        => 'age_verify_popup_deny_button_redirect',
			'label'           => esc_html__( 'Deny button redirect', 'xstore-core' ),
			'description'     => esc_html__( 'Set custom link to redirect on button click or leave empty to open error content', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_deny_button_redirect' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:first-child',
					'render_callback' => 'et_age_verify_popup_content_callback',
				),
			),
		),
		
		// age_verify_popup_sections
//		'age_verify_popup_sections_et-desktop'                    => array(
//			'name'            => 'age_verify_popup_sections_et-desktop',
//			'type'            => 'toggle',
//			'settings'        => 'age_verify_popup_sections_et-desktop',
//			'label'           => $strings['label']['use_static_block'],
//			'section'         => 'age_verify_popup',
//			'default'         => 0,
//			'transport'       => 'postMessage',
//			'partial_refresh' => array(
//				'age_verify_popup_sections_et-desktop' => array(
//					'selector'        => '.et-age-verify-popup .et-popup-content',
//					'render_callback' => 'et_age_verify_popup_content_callback',
//				),
//			),
//		),
//
//		// age_verify_popup_section
//		'age_verify_popup_section_et-desktop'                     => array(
//			'name'            => 'age_verify_popup_section_et-desktop',
//			'type'            => 'select',
//			'settings'        => 'age_verify_popup_section_et-desktop',
//			'label'           => sprintf( esc_html__( 'Choose %1s created with WPBakery builder', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
//			'section'         => 'age_verify_popup',
//			'default'         => '',
//			'priority'        => 10,
//			'choices'         => $sections,
//			'active_callback' => array(
//				array(
//					'setting'  => 'age_verify_popup_sections_et-desktop',
//					'operator' => '==',
//					'value'    => 1,
//				),
//			),
//			'transport'       => 'postMessage',
//			'partial_refresh' => array(
//				'age_verify_popup_section_et-desktop' => array(
//					'selector'        => '.et-age-verify-popup .et-popup-content',
//					'render_callback' => 'et_age_verify_popup_content_callback',
//				),
//			),
//		),
		
		// style separator
		'age_verify_popup_error_content_separator'     => array(
			'name'            => 'age_verify_popup_error_content_separator',
			'type'            => 'custom',
			'settings'        => 'age_verify_popup_error_content_separator',
			'section'         => 'age_verify_popup',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-lock"></span> <span style="padding-' . ( $is_rtl ? 'right' : 'left' ) . ': 3px;">' . __( 'Error content settings' ) . '</span></div>',
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
				array(
					'setting'  => 'age_verify_popup_deny_button_redirect',
					'operator' => '==',
					'value'    => '',
				),
			),
		),
		
		// age_verify_popup_title
		'age_verify_popup_error_title'                 => array(
			'name'            => 'age_verify_popup_error_title',
			'type'            => 'etheme-text',
			'settings'        => 'age_verify_popup_error_title',
			'label'           => esc_html__( 'Error Title', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => esc_html__( 'Access forbidden', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
				array(
					'setting'  => 'age_verify_popup_deny_button_redirect',
					'operator' => '==',
					'value'    => '',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_error_title' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:last-child',
					'render_callback' => 'et_age_verify_popup_error_content_callback',
				),
			),
		),
		
		// age_verify_popup_content
		'age_verify_popup_error_content'               => array(
			'name'            => 'age_verify_popup_error_content',
			'type'            => 'editor',
			'settings'        => 'age_verify_popup_error_content',
			'label'           => esc_html__( 'Error Content', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => '<p>' . esc_html__( 'Your access is restricted because of your age.', 'xstore-core' ) . '</p>',
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
				array(
					'setting'  => 'age_verify_popup_deny_button_redirect',
					'operator' => '==',
					'value'    => '',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'age_verify_popup_error_content' => array(
					'selector'        => '.et-age-verify-popup .et-popup-content:last-child',
					'render_callback' => 'et_age_verify_popup_error_content_callback',
				),
			),
		),
		
		// style separator
		'age_verify_popup_style_separator'             => array(
			'name'            => 'age_verify_popup_style_separator',
			'type'            => 'custom',
			'settings'        => 'age_verify_popup_style_separator',
			'section'         => 'age_verify_popup',
			'default'         => $separators['style'],
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
		),
		
		// age_verify_popup_content_alignment
		'age_verify_popup_content_alignment'           => array(
			'name'            => 'age_verify_popup_content_alignment',
			'type'            => 'radio-buttonset',
			'settings'        => 'age_verify_popup_content_alignment',
			'label'           => $strings['label']['alignment'],
			'section'         => 'age_verify_popup',
			'default'         => 'center',
			'choices'         => $choices['alignment'],
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et-age-verify-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et-age-verify-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et-age-verify-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-end',
					'value'    => 'end'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
		),
		
		// age_verify_popup_content_width_height
		'age_verify_popup_content_width_height'        => array(
			'name'            => 'age_verify_popup_content_width_height',
			'type'            => 'radio-buttonset',
			'settings'        => 'age_verify_popup_content_width_height',
			'label'           => esc_html__( 'Popup width and height', 'xstore-core' ),
			'section'         => 'age_verify_popup',
			'default'         => 'auto',
			'multiple'        => 1,
			'choices'         => array(
				'auto'   => esc_html__( 'Auto', 'xstore-core' ),
				'custom' => esc_html__( 'Custom', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
		),
		
		// age_verify_popup_content_width_height_custom
		'age_verify_popup_content_width_height_custom' => array(
			'name'            => 'age_verify_popup_content_width_height_custom',
			'type'            => 'dimensions',
			'settings'        => 'age_verify_popup_content_width_height_custom',
			'section'         => 'age_verify_popup',
			'default'         => array(
				'width'  => '550px',
				'height' => '250px',
			),
//			'choices'   => array(
//				'labels' => array(
//					'width'  => esc_html__( 'Width', 'xstore-core' ),
//					'height' => esc_html__( 'Height', 'xstore-core' ),
//				),
//			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
				array(
					'setting'  => 'age_verify_popup_content_width_height',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'output'          => array(
				array(
					'choice'   => 'width',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-age-verify-popup .et-popup-content-custom-dimenstions',
					'property' => 'width',
				),
				array(
					'choice'   => 'height',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-age-verify-popup .et-popup-content-custom-dimenstions',
					'property' => 'height',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'choice'   => 'width',
					'type'     => 'css',
					'element'  => '.et-age-verify-popup .et-popup-content-custom-dimenstions',
					'property' => 'width',
				),
				array(
					'choice'   => 'height',
					'type'     => 'css',
					'element'  => '.et-age-verify-popup .et-popup-content-custom-dimenstions',
					'property' => 'height',
				),
			)
		),
		
		// age_verify_popup_background
		'age_verify_popup_background'                  => array(
			'name'            => 'age_verify_popup_background',
			'type'            => 'background',
			'settings'        => 'age_verify_popup_background',
			'label'           => $strings['label']['wcag_bg_color'],
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'age_verify_popup',
			'default'         => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et-age-verify-popup .et-popup-content',
				),
			),
		),
		
		'age_verify_popup_color' => array(
			'name'            => 'age_verify_popup_color',
			'settings'        => 'age_verify_popup_color',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'age_verify_popup',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(age_verify_popup)(age_verify_popup_background)[background-color]',
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
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-age-verify-popup .et-popup-content, .et-age-verify-popup span.et-close-popup',
					'property' => 'color'
				)
			)
		),
		
		'age_verify_popup_box_model_et-desktop' => array(
			'name'            => 'age_verify_popup_box_model_et-desktop',
			'settings'        => 'age_verify_popup_box_model_et-desktop',
			'label'           => $strings['label']['computed_box'],
			'description'     => $strings['description']['computed_box'],
			'type'            => 'kirki-box-model',
			'section'         => 'age_verify_popup',
			'default'         => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '30px',
				'padding-right'       => '30px',
				'padding-bottom'      => '30px',
				'padding-left'        => '30px',
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et-age-verify-popup .et-popup-content',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => box_model_output( '.et-age-verify-popup .et-popup-content' )
		),
		
		// age_verify_popup_border
		'age_verify_popup_border'               => array(
			'name'            => 'age_verify_popup_border',
			'type'            => 'select',
			'settings'        => 'age_verify_popup_border',
			'label'           => $strings['label']['border_style'],
			'section'         => 'age_verify_popup',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-age-verify-popup .et-popup-content',
					'property' => 'border-style'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
		),
		
		// age_verify_popup_border_color_custom
		'age_verify_popup_border_color_custom'  => array(
			'name'            => 'age_verify_popup_border_color_custom',
			'type'            => 'color',
			'settings'        => 'age_verify_popup_border_color_custom',
			'label'           => $strings['label']['border_color'],
			'description'     => $strings['description']['border_color'],
			'section'         => 'age_verify_popup',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'age_verify_popup_switcher',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-age-verify-popup .et-popup-content',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
//	unset($sections);
	
	return array_merge( $fields, $args );
} );