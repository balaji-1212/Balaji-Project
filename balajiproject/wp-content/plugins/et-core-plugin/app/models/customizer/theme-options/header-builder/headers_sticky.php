<?php
/**
 * The template created for displaying header sticky options
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.4
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'headers_sticky' => array(
			'name'       => 'headers_sticky',
			'title'      => esc_html__( 'Header sticky', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-paperclip',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/headers_sticky', function ( $fields ) use ( $separators, $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'header_sticky_content_separator'              => array(
			'name'     => 'header_sticky_content_separator',
			'type'     => 'custom',
			'settings' => 'header_sticky_content_separator',
			'section'  => 'headers_sticky',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// top_header_sticky
		'top_header_sticky_et-desktop'                 => array(
			'name'     => 'top_header_sticky_et-desktop',
			'type'     => 'toggle',
			'settings' => 'top_header_sticky_et-desktop',
			'label'    => esc_html__( 'Top header sticky', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => '0',
		),
		
		// top_header_sticky
		'top_header_sticky_et-mobile'                  => array(
			'name'     => 'top_header_sticky_et-mobile',
			'type'     => 'toggle',
			'settings' => 'top_header_sticky_et-mobile',
			'label'    => esc_html__( 'Top header sticky', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => '0',
		),
		
		// main_header_sticky
		'main_header_sticky_et-desktop'                => array(
			'name'     => 'main_header_sticky_et-desktop',
			'type'     => 'toggle',
			'settings' => 'main_header_sticky_et-desktop',
			'label'    => esc_html__( 'Main header sticky', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => '1',
		),
		
		// main_header_sticky
		'main_header_sticky_et-mobile'                 => array(
			'name'     => 'main_header_sticky_et-mobile',
			'type'     => 'toggle',
			'settings' => 'main_header_sticky_et-mobile',
			'label'    => esc_html__( 'Main header sticky', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => '1',
		),
		
		// bottom_header_sticky
		'bottom_header_sticky_et-desktop'              => array(
			'name'     => 'bottom_header_sticky_et-desktop',
			'type'     => 'toggle',
			'settings' => 'bottom_header_sticky_et-desktop',
			'label'    => esc_html__( 'Bottom header sticky', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => '0',
		),
		
		// bottom_header_sticky
		'bottom_header_sticky_et-mobile'               => array(
			'name'     => 'bottom_header_sticky_et-mobile',
			'type'     => 'toggle',
			'settings' => 'bottom_header_sticky_et-mobile',
			'label'    => esc_html__( 'Bottom header sticky', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => '0',
		),
		
		// header_sticky_type
		'header_sticky_type_et-desktop'                => array(
			'name'     => 'header_sticky_type_et-desktop',
			'type'     => 'radio-buttonset',
			'settings' => 'header_sticky_type_et-desktop',
			'label'    => esc_html__( 'Sticky type', 'xstore-core' ),
			'section'  => 'headers_sticky',
			'default'  => 'sticky',
			'multiple' => 1,
			'choices'  => array(
				'sticky' => esc_html__( 'Sticky', 'xstore-core' ),
				'smart'  => esc_html__( 'Smart', 'xstore-core' ),
				'custom' => esc_html__( 'Custom', 'xstore-core' ),
			),
		),
		
		// headers_sticky_animation
		'headers_sticky_animation_et-desktop'          => array(
			'name'            => 'headers_sticky_animation_et-desktop',
			'type'            => 'select',
			'settings'        => 'headers_sticky_animation_et-desktop',
			'label'           => esc_html__( 'Animation type', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 'toBottomFull',
			'choices'         => array(
				'toBottomFull' => esc_html__( 'Jump down', 'xstore-core' ),
				'fadeIn'       => esc_html__( 'Fade in', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element'  => '#header.sticky-on .header-wrapper, #header.sticky-on .mobile-header-wrapper',
					'property' => 'animation-name',
					'prefix'   => 'et-',
					'context'  => array( 'editor', 'front' )
				),
			),
		),
		
		// headers_sticky_animation_duration
		'headers_sticky_animation_duration_et-desktop' => array(
			'name'            => 'headers_sticky_animation_duration_et-desktop',
			'type'            => 'slider',
			'settings'        => 'headers_sticky_animation_duration_et-desktop',
			'label'           => esc_html__( 'Animation duration (sec)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => .7,
			'choices'         => array(
				'min'  => '.1',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element'  => '#header.sticky-on .header-wrapper, #header.sticky-on .mobile-header-wrapper',
					'property' => 'animation-duration',
					'units'    => 's',
					'context'  => array( 'editor', 'front' )
				),
			),
		),
		
		// headers_sticky_start
		'headers_sticky_start_et-desktop'              => array(
			'name'            => 'headers_sticky_start_et-desktop',
			'type'            => 'slider',
			'settings'        => 'headers_sticky_start_et-desktop',
			'label'           => esc_html__( 'Start sticky header on scroll (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 80,
			'choices'         => array(
				'min'  => '0',
				'max'  => '500',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_sticky_on_scroll_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		
		// headers_sticky_logo_img
		'headers_sticky_logo_img_et-desktop'           => array(
			'name'            => 'headers_sticky_logo_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'headers_sticky_logo_img_et-desktop',
			'label'           => $strings['label']['sticky_logo'],
			'description'     => $strings['description']['sticky_logo'] . '<span class="et_edit" data-parent="logo" data-section="logo_content_separator">' . esc_html__( 'Width settings', 'xstore-core' ) . '</span>',
			'section'         => 'headers_sticky',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'headers_sticky_logo_img_et-desktop' => array(
					'selector'        => '.et_b_header-logo.et_element-top-level span.fixed',
					'render_callback' => function () {
						
						$default_logo = array(
							'url' => ETHEME_BASE_URI . 'theme/assets/images/logo.png',
							'alt' => 'header logo'
						);
						$logo         = get_theme_mod( "headers_sticky_logo_img_et-desktop", '' );
						
						if ( ! is_array( $logo ) || empty( $logo ) ) {
							$logo = get_theme_mod( "logo_img_et-desktop", '' );
						}
						
						if ( ! isset( $logo['url'] ) || $logo['url'] == '' ) {
							$logo['url'] = $default_logo['url'];
						}
						
						if ( isset( $logo['id'] ) && $logo['id'] != '' ) {
							$logo['alt'] = get_post_meta( $logo['id'], '_wp_attachment_image_alt', true );
						}
						
						$logo['alt'] = ! empty( $logo['alt'] ) ? $logo['alt'] : '';
						
						echo '<img src="' . esc_url( $logo['url'] ) . '" alt="' . $logo['alt'] . '">';
					},
				),
			),
		),
		
		// style separator
		'headers_sticky_style_separator'               => array(
			'name'     => 'headers_sticky_style_separator',
			'type'     => 'custom',
			'settings' => 'headers_sticky_style_separator',
			'section'  => 'headers_sticky',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// top_header_sticky_height
		'top_header_sticky_height_et-desktop'          => array(
			'name'            => 'top_header_sticky_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'top_header_sticky_height_et-desktop',
			'label'           => esc_html__( 'Top header sticky min height (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 60,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'top_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '!=',
					'value'    => 'sticky',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .header-top .et-wrap-columns, #header[data-type="smart"].sticky-on .header-top .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '#header.sticky-on .header-top .widget_nav_menu .menu > li > a, #header[data-type="smart"].sticky-on .header-top .widget_nav_menu .menu > li > a,
									#header.sticky-on .header-top #lang_sel a.lang_sel_sel, #header[data-type="smart"].sticky-on .header-top #lang_sel a.lang_sel_sel,
									#header.sticky-on .header-top .wcml-dropdown a.wcml-cs-item-toggle, #header[data-type="smart"].sticky-on .header-top .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px',
				),
			),
		),
		
		// top_header_sticky_height
		'top_header_sticky_height_et-mobile'           => array(
			'name'            => 'top_header_sticky_height_et-mobile',
			'type'            => 'slider',
			'settings'        => 'top_header_sticky_height_et-mobile',
			'label'           => esc_html__( 'Top header sticky min height (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 60,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'top_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '!=',
					'value'    => 'sticky',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .mobile-header-wrapper .header-top .et-wrap-columns,
											  #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-top .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .mobile-header-wrapper .header-top .widget_nav_menu .menu > li > a, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-top .widget_nav_menu .menu > li > a,
						.sticky-on .mobile-header-wrapper .header-top #lang_sel a.lang_sel_sel, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-top #lang_sel a.lang_sel_sel,
						.sticky-on .mobile-header-wrapper .header-top .wcml-dropdown a.wcml-cs-item-toggle, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-top .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// top_header_sticky_background
		'top_header_sticky_background_et-desktop'      => array(
			'name'            => 'top_header_sticky_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'top_header_sticky_background_et-desktop',
			'label'           => esc_html__( 'Top header WCAG Control', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'headers_sticky',
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
					'setting'  => 'top_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.sticky-on .header-top',
				),
			),
		),
		
		// top_header_sticky_background
		'top_header_sticky_background_et-mobile'       => array(
			'name'            => 'top_header_sticky_background_et-mobile',
			'type'            => 'background',
			'settings'        => 'top_header_sticky_background_et-mobile',
			'label'           => esc_html__( 'Top header WCAG Control', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'headers_sticky',
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
					'setting'  => 'top_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .sticky-on .header-top, .sticky-on .mobile-header-wrapper .header-top',
				),
			),
		),
		'top_header_sticky_color_et-desktop'           => array(
			'name'            => 'top_header_sticky_color_et-desktop',
			'settings'        => 'top_header_sticky_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'headers_sticky',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(headers_sticky)(top_header_sticky_background_et-desktop)[background-color]',
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
					'setting'  => 'top_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .header-top',
					'property' => 'color'
				)
			),
		),
		
		// top_header_sticky_color
		'top_header_sticky_color_et-mobile'            => array(
			'name'            => 'top_header_sticky_color_et-mobile',
			'settings'        => 'top_header_sticky_color_et-mobile',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'headers_sticky',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(headers_sticky)(top_header_sticky_background_et-mobile)[background-color]',
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
					'setting'  => 'top_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .sticky-on .header-top, .sticky-on .mobile-header-wrapper .header-top',
					'property' => 'color'
				)
			),
		),
		
		// main_header_sticky_height
		'main_header_sticky_height_et-desktop'         => array(
			'name'            => 'main_header_sticky_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'main_header_sticky_height_et-desktop',
			'label'           => esc_html__( 'Main header sticky min height (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 60,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'main_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '!=',
					'value'    => 'sticky',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .header-main .et-wrap-columns, #header[data-type="smart"].sticky-on .header-main .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '#header.sticky-on .header-main .widget_nav_menu .menu > li > a, #header[data-type="smart"].sticky-on .header-main .widget_nav_menu .menu > li > a,
									#header.sticky-on .header-main #lang_sel a.lang_sel_sel, #header[data-type="smart"].sticky-on .header-main #lang_sel a.lang_sel_sel,
									#header.sticky-on .header-main .wcml-dropdown a.wcml-cs-item-toggle, #header[data-type="smart"].sticky-on .header-main .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// main_header_sticky_height
		'main_header_sticky_height_et-mobile'          => array(
			'name'            => 'main_header_sticky_height_et-mobile',
			'type'            => 'slider',
			'settings'        => 'main_header_sticky_height_et-mobile',
			'label'           => esc_html__( 'Main header sticky min height (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 60,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'main_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '!=',
					'value'    => 'sticky',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .mobile-header-wrapper .header-main .et-wrap-columns,
								  	#header[data-type="smart"].sticky-on .mobile-header-wrapper .header-main .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .mobile-header-wrapper .header-main .widget_nav_menu .menu > li > a, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-main .widget_nav_menu .menu > li > a,
						.sticky-on .mobile-header-wrapper .header-main #lang_sel a.lang_sel_sel, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-main #lang_sel a.lang_sel_sel,
						.sticky-on .mobile-header-wrapper .header-main .wcml-dropdown a.wcml-cs-item-toggle, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-main .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// main_header_sticky_background
		'main_header_sticky_background_et-desktop'     => array(
			'name'            => 'main_header_sticky_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'main_header_sticky_background_et-desktop',
			'label'           => esc_html__( 'Main header WCAG Control', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'headers_sticky',
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
					'setting'  => 'main_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.sticky-on .header-main',
				),
			),
		),
		
		// main_header_sticky_background
		'main_header_sticky_background_et-mobile'      => array(
			'name'            => 'main_header_sticky_background_et-mobile',
			'type'            => 'background',
			'settings'        => 'main_header_sticky_background_et-mobile',
			'label'           => esc_html__( 'Main header WCAG Control', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'headers_sticky',
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
					'setting'  => 'main_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .sticky-on .header-main, .sticky-on .mobile-header-wrapper .header-main',
				),
			),
		),
		
		// main_header_sticky_color
		'main_header_sticky_color_et-desktop'          => array(
			'name'            => 'main_header_sticky_color_et-desktop',
			'settings'        => 'main_header_sticky_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'headers_sticky',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(headers_sticky)(main_header_sticky_background_et-desktop)[background-color]',
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
					'setting'  => 'main_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .header-main',
					'property' => 'color'
				)
			),
		),
		
		// main_header_sticky_color
		'main_header_sticky_color_et-mobile'           => array(
			'name'            => 'main_header_sticky_color_et-mobile',
			'settings'        => 'main_header_sticky_color_et-mobile',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'headers_sticky',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(headers_sticky)(main_header_sticky_background_et-mobile)[background-color]',
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
					'setting'  => 'main_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .sticky-on .header-main, .sticky-on .mobile-header-wrapper .header-main',
					'property' => 'color'
				)
			),
		),
		
		// bottom_header_sticky_height
		'bottom_header_sticky_height_et-desktop'       => array(
			'name'            => 'bottom_header_sticky_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'bottom_header_sticky_height_et-desktop',
			'label'           => esc_html__( 'Bottom header sticky min height (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 60,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'bottom_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '!=',
					'value'    => 'sticky',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .header-bottom .et-wrap-columns, #header[data-type="smart"].sticky-on .header-bottom .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '#header.sticky-on .header-bottom .widget_nav_menu .menu > li > a, #header[data-type="smart"].sticky-on .header-bottom .widget_nav_menu .menu > li > a,
									#header.sticky-on .header-bottom #lang_sel a.lang_sel_sel, #header[data-type="smart"].sticky-on .header-bottom #lang_sel a.lang_sel_sel,
									#header.sticky-on .header-bottom .wcml-dropdown a.wcml-cs-item-toggle, #header[data-type="smart"].sticky-on .header-bottom .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// bottom_header_sticky_height
		'bottom_header_sticky_height_et-mobile'        => array(
			'name'            => 'bottom_header_sticky_height_et-mobile',
			'type'            => 'slider',
			'settings'        => 'bottom_header_sticky_height_et-mobile',
			'label'           => esc_html__( 'Bottom header sticky min height (px)', 'xstore-core' ),
			'section'         => 'headers_sticky',
			'default'         => 60,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'bottom_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_sticky_type_et-desktop',
					'operator' => '!=',
					'value'    => 'sticky',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .mobile-header-wrapper .header-bottom .et-wrap-columns,
								  	#header[data-type="smart"].sticky-on .mobile-header-wrapper .header-bottom .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .mobile-header-wrapper .header-bottom .widget_nav_menu .menu > li > a, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-bottom .widget_nav_menu .menu > li > a,
						.sticky-on .mobile-header-wrapper .header-bottom #lang_sel a.lang_sel_sel, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-bottom #lang_sel a.lang_sel_sel,
						.sticky-on .mobile-header-wrapper .header-bottom .wcml-dropdown a.wcml-cs-item-toggle, #header[data-type="smart"].sticky-on .mobile-header-wrapper .header-bottom .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// bottom_header_sticky_background
		'bottom_header_sticky_background_et-desktop'   => array(
			'name'            => 'bottom_header_sticky_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'bottom_header_sticky_background_et-desktop',
			'label'           => esc_html__( 'Bottom header WCAG Control', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'headers_sticky',
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
					'setting'  => 'bottom_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.sticky-on .header-bottom',
				),
			),
		),
		
		// bottom_header_sticky_background
		'bottom_header_sticky_background_et-mobile'    => array(
			'name'            => 'bottom_header_sticky_background_et-mobile',
			'type'            => 'background',
			'settings'        => 'bottom_header_sticky_background_et-mobile',
			'label'           => esc_html__( 'Bottom header WCAG Control', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'section'         => 'headers_sticky',
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
					'setting'  => 'bottom_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .sticky-on .header-bottom, .sticky-on .mobile-header-wrapper .header-bottom',
				),
			),
		),
		'bottom_header_sticky_color_et-desktop'        => array(
			'name'            => 'bottom_header_sticky_color_et-desktop',
			'settings'        => 'bottom_header_sticky_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'headers_sticky',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(headers_sticky)(bottom_header_sticky_background_et-desktop)[background-color]',
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
					'setting'  => 'bottom_header_sticky_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.sticky-on .header-bottom',
					'property' => 'color'
				)
			),
		),
		
		// bottom_header_sticky_color
		'bottom_header_sticky_color_et-mobile'         => array(
			'name'            => 'bottom_header_sticky_color_et-mobile',
			'settings'        => 'bottom_header_sticky_color_et-mobile',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'headers_sticky',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(headers_sticky)(bottom_header_sticky_background_et-mobile)[background-color]',
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
					'setting'  => 'bottom_header_sticky_et-mobile',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .sticky-on .header-bottom, .sticky-on .mobile-header-wrapper .header-bottom',
					'property' => 'color'
				)
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
