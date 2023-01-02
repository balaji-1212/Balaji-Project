<?php
/**
 * The template created for displaying header compare options when woocommerce plugin is installed
 *
 * @version 1.0.1
 * @since   2.3.7
 * @last changes in 4.3.9 - added Built-in Compare options
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'compare' => array(
			'name'       => 'compare',
			'title'      => esc_html__( 'Compare', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-update-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/compare', function ( $fields ) use ( $separators, $strings, $choices, $sep_style, $box_models ) {
	$args = array();
	// Array of fields
	$args = array(
		// content separator
		'compare_content_separator'            => array(
			'name'     => 'compare_content_separator',
			'type'     => 'custom',
			'settings' => 'compare_content_separator',
			'section'  => 'compare',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// compare_style
		'compare_style_et-desktop'             => array(
			'name'            => 'compare_style_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'compare_style_et-desktop',
			'label'           => $strings['label']['style'],
			'description'     => esc_html__( 'Take a look on the video tutorial "How to ..." set up compare style2 and style3 ', 'xstore-core' ),
			'section'         => 'compare',
			'default'         => 'type1',
			'choices'         => et_b_element_styles( 'compare' ),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'compare_style_et-desktop' => array(
					'selector'        => '.et_b_header-compare.et_element-top-level',
					'render_callback' => 'header_compare_callback'
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-compare.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'compare-type1',
					'value'    => 'type1'
				),
				array(
					'element'  => '.et_b_header-compare.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'compare-type2',
					'value'    => 'type2'
				),
				array(
					'element'  => '.et_b_header-compare.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'compare-type3',
					'value'    => 'type3'
				),
			),
		),
		
		// compare_icon
		'compare_icon_et-desktop'              => array(
			'name'            => 'compare_icon_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'compare_icon_et-desktop',
			'label'           => $strings['label']['icon'],
			'description'     => $strings['description']['icons_style'],
			'section'         => 'compare',
			'default'         => 'type1',
			'choices'         => array(
				'type1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/compare/Compare-1.svg',
				'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'compare_icon' => array(
					'selector'        => '.et_b_header-compare > a .et_b-icon .et-svg',
					'render_callback' => function () {
						global $et_compare_icons;
						$type = get_theme_mod( 'compare_icon_et-desktop', 'type1' );
						if ( $type == 'custom' && get_theme_mod( 'compare_icon_custom_svg_et-mobile', '' ) != '' ) {
							return get_post_meta( get_theme_mod( 'compare_icon_custom_svg_et-mobile', '' ), '_xstore_inline_svg', true );
						}
						
						return ( $type != '' ? $et_compare_icons['light'][$type] : '' );
					},
				),
			),
		),
		
		// compare_icon_custom_svg
		'compare_icon_custom_svg_et-desktop'   => array(
			'name'            => 'compare_icon_custom_svg_et-desktop',
			'type'            => 'image',
			'settings'        => 'compare_icon_custom_svg_et-desktop',
			'label'           => $strings['label']['custom_image_svg'],
			'description'     => $strings['description']['custom_image_svg'],
			'section'         => 'compare',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'compare_icon_custom_svg_et-desktop' => array(
					'selector'        => '.et_b_header-compare.et_element-top-level',
					'render_callback' => 'header_compare_callback'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'compare_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			)
		
		),
		
		// compare_icon_zoom  
		'compare_icon_zoom_et-desktop'         => array(
			'name'            => 'compare_icon_zoom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'compare_icon_zoom_et-desktop',
			'label'           => $strings['label']['icons_zoom'],
			'section'         => 'compare',
			'default'         => 1.3,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'compare_icon_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-compare.et_element-top-level > a svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-compare.et_element-top-level > a svg',
					'property' => 'height',
					'units'    => 'em'
				)
			)
		),
		
		// compare_icon_zoom
		'compare_icon_zoom_et-mobile'          => array(
			'name'            => 'compare_icon_zoom_et-mobile',
			'type'            => 'slider',
			'settings'        => 'compare_icon_zoom_et-mobile',
			'label'           => $strings['label']['icons_zoom'],
			'section'         => 'compare',
			'default'         => 1.4,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'compare_icon_et-mobile',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level > a svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level > a svg',
					'property' => 'height',
					'units'    => 'em'
				)
			)
		),
		
		// compare_label
		'compare_label_et-desktop'             => array(
			'name'      => 'compare_label_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'compare_label_et-desktop',
			'label'     => $strings['label']['show_title'],
			'section'   => 'compare',
			'default'   => '1',
			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-compare.et_element-top-level > a .et-element-label',
//					'function' => 'toggleClass',
//					'class'    => 'dt-hide',
//					'value'    => false
//				),
//			),
            'partial_refresh' => array(
                'compare_label_et-desktop' => array(
                    'selector'        => '.et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
		),
		
		// compare_label
		'compare_label_et-mobile'              => array(
			'name'      => 'compare_label_et-mobile',
			'type'      => 'toggle',
			'settings'  => 'compare_label_et-mobile',
			'label'     => $strings['label']['show_title'],
			'section'   => 'compare',
			'default'   => '0',
			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-compare.et_element-top-level > a .et-element-label',
//					'function' => 'toggleClass',
//					'class'    => 'mob-hide',
//					'value'    => false
//				),
//			),
            'partial_refresh' => array(
                'compare_label_et-mobile' => array(
                    'selector'        => '.et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
		),
		
		// compare_label_custom
		'compare_label_custom_et-desktop'      => array(
			'name'      => 'compare_label_custom_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'compare_label_custom_et-desktop',
			'section'   => 'compare',
			'default'   => esc_html__( 'Compare', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-compare > a .et-element-label',
					'function' => 'html',
				),
			),
		),

        // compare_content_type
        'compare_content_type_et-desktop'                      => array(
            'name'            => 'compare_content_type_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'compare_content_type_et-desktop',
            'label'           => esc_html__( 'Mini-compare type', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 'dropdown',
            'multiple'        => 1,
            'choices'         => array(
                'none'       => esc_html__( 'None', 'xstore-core' ),
                'dropdown'   => esc_html__( 'Dropdown', 'xstore-core' ),
                'off_canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'compare_content_type_et-desktop' => array(
                    'selector'        => '.header-wrapper .et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'js_vars'         => array(
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-toTop',
                    'value'    => 'dropdown'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content_toggle',
                    'value'    => 'off_canvas'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-off-canvas',
                    'value'    => 'off_canvas'
                ),
            ),
        ),

        // compare_content_type
        'compare_content_type_et-mobile'                       => array(
            'name'            => 'compare_content_type_et-mobile',
            'type'            => 'radio-buttonset',
            'settings'        => 'compare_content_type_et-mobile',
            'label'           => esc_html__( 'Mini-compare type', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 'off_canvas',
            'multiple'        => 1,
            'choices'         => array(
                'none'       => esc_html__( 'None', 'xstore-core' ),
                'off_canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'compare_content_type_et-mobile' => array(
                    'selector'        => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'js_vars'         => array(
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-toTop',
                    'value'    => 'dropdown'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-content_toggle',
                    'value'    => 'off_canvas'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-off-canvas',
                    'value'    => 'off_canvas'
                ),
            ),
        ),

        // compare_link_to
        'compare_link_to'                                      => array(
            'name'            => 'compare_link_to',
            'type'            => 'select',
            'settings'        => 'compare_link_to',
            'label'           => esc_html__( 'Link to', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 'compare_url',
            'priority'        => 10,
            'choices'         => array(
                'compare_url' => esc_html__( 'Compare page', 'xstore-core' ),
                'custom_url'   => $strings['label']['custom_link'],
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'compare_link_to' => array(
                    'selector'        => '.et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
            'active_callback' => function () {
                if ( get_theme_mod( 'xstore_compare', false ) && get_theme_mod( 'compare_content_type_et-desktop', 'dropdown' ) != 'off_canvas' || get_theme_mod( 'compare_content_type_et-mobile', 'dropdown' ) != 'off_canvas' ) {
                    return true;
                }

                return false;
            }
        ),

        // compare_custom_url
        'compare_custom_url'                                   => array(
            'name'            => 'compare_custom_url',
            'type'            => 'etheme-link',
            'settings'        => 'compare_custom_url',
            'label'           => $strings['label']['custom_link'],
            'section'         => 'compare',
            'default'         => '#',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_link_to',
                    'operator' => '==',
                    'value'    => 'custom_url',
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-compare > a',
                    'attr'     => 'href',
                    'function' => 'html',
                ),
            ),
        ),

        // mini-compare-items-count
        'mini-compare-items-count'                             => array(
            'name'            => 'mini-compare-items-count',
            'type'            => 'slider',
            'settings'        => 'mini-compare-items-count',
            'label'           => esc_html__( 'Number of products in mini-compare', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => get_theme_mod( 'mini-cart-items-count', '3' ),
            'choices'         => array(
                'min'  => 1,
                'max'  => 30,
                'step' => 1,
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'mini-compare-items-count' => array(
                    'selector'        => '.et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        // content separator
        'compare_quantity_separator'                           => array(
            'name'     => 'compare_quantity_separator',
            'type'     => 'custom',
            'settings' => 'compare_quantity_separator',
            'section'  => 'compare',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-image-filter"></span> <span style="padding-left: 3px;">' . esc_html__( 'Quantity options', 'xstore-core' ) . '</span></div>',
            'priority' => 10,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        // compare_quantity
        'compare_quantity_et-desktop'                          => array(
            'name'            => 'compare_quantity_et-desktop',
            'type'            => 'toggle',
            'settings'        => 'compare_quantity_et-desktop',
            'label'           => esc_html__( 'Show compare quantity', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => '1',
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'compare_quantity_et-desktop' => array(
                    'selector'        => '.et_b_header-compare.et_element-top-level',
                    'render_callback' => 'header_compare_callback'
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        // compare_quantity_position
        'compare_quantity_position_et-desktop'                 => array(
            'name'            => 'compare_quantity_position_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'compare_quantity_position_et-desktop',
            'label'           => esc_html__( 'Label position', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 'right',
            'multiple'        => 1,
            'choices'         => array(
                'top'   => esc_html__( 'Top', 'xstore-core' ),
                'right' => esc_html__( 'Right', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-quantity-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.et_b_header-compare.et_element-top-level',
                    'function' => 'toggleClass',
                    'class'    => 'et-quantity-top',
                    'value'    => 'top'
                ),
            ),
        ),

        // compare_quantity_size
        'compare_quantity_size_et-desktop'                     => array(
            'name'            => 'compare_quantity_size_et-desktop',
            'type'            => 'slider',
            'settings'        => 'compare_quantity_size_et-desktop',
            'label'           => esc_html__( 'Quantity font size (em)', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 0.75,
            'choices'         => array(
                'min'  => '.3',
                'max'  => '3',
                'step' => '.01',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level .et-quantity',
                    'property' => 'font-size',
                    'units'    => 'em'
                ),
            ),
        ),

        // compare_quantity_proportions
        'compare_quantity_proportions_et-desktop'              => array(
            'name'            => 'compare_quantity_proportions_et-desktop',
            'type'            => 'slider',
            'settings'        => 'compare_quantity_proportions_et-desktop',
            'label'           => esc_html__( 'Quantity background size (em)', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 1.5,
            'choices'         => array(
                'min'  => '0.1',
                'max'  => '5',
                'step' => '0.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level .et-quantity',
                    'property' => '--et-quantity-proportion',
                    'units'    => 'em'
                ),
            ),
        ),

        // compare_quantity_active_background_custom
        'compare_quantity_active_background_custom_et-desktop' => array(
            'name'            => 'compare_quantity_active_background_custom_et-desktop',
            'type'            => 'color',
            'settings'        => 'compare_quantity_active_background_custom_et-desktop',
            'label'           => esc_html__( 'compare quantity Background (active)', 'xstore-core' ),
            'section'         => 'compare',
            'choices'         => array(
                'alpha' => true
            ),
            'default'         => '#ffffff',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level .et-quantity',
                    'property' => 'background-color',
                ),
            ),
        ),

        'compare_quantity_active_color_et-desktop' => array(
            'name'            => 'compare_quantity_active_color_et-desktop',
            'settings'        => 'compare_quantity_active_color_et-desktop',
            'label'           => esc_html__( 'WCAG compare quantity Color (active)', 'xstore-core' ),
            'description'     => $strings['description']['wcag_color'],
            'type'            => 'kirki-wcag-tc',
            'section'         => 'compare',
            'default'         => '#000000',
            'choices'         => array(
                'setting' => 'setting(compare)(compare_quantity_active_background_custom_et-desktop)',
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
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_quantity_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level .et-quantity',
                    'property' => 'color'
                )
            )
        ),
		
		// style separator
		'compare_style_separator'              => array(
			'name'     => 'compare_style_separator',
			'type'     => 'custom',
			'settings' => 'compare_style_separator',
			'section'  => 'compare',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// compare_content_alignment
		'compare_content_alignment_et-desktop' => array(
			'name'        => 'compare_content_alignment_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'compare_content_alignment_et-desktop',
			'label'       => $strings['label']['alignment'],
			'description' => esc_html__( 'Attention: if your element size bigger than the column width where the element is placed, element positioning may be a little bit different than as expected', 'xstore-core' ),
			'section'     => 'compare',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// compare_content_alignment
		'compare_content_alignment_et-mobile'  => array(
			'name'        => 'compare_content_alignment_et-mobile',
			'type'        => 'radio-buttonset',
			'settings'    => 'compare_content_alignment_et-mobile',
			'label'       => $strings['label']['alignment'],
			'description' => esc_html__( 'Attention: if your element size bigger than the column width where the element is placed, element positioning may be a little bit different than as expected', 'xstore-core' ),
			'section'     => 'compare',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// compare_background
		'compare_background_et-desktop'        => array(
			'name'     => 'compare_background_et-desktop',
			'type'     => 'select',
			'settings' => 'compare_background_et-desktop',
			'label'    => $strings['label']['colors'],
			'section'  => 'compare',
			'default'  => 'current',
			'choices'  => $choices['colors'],
			'output'   => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-compare.et_element-top-level > a',
					'property'      => 'color',
					'value_pattern' => 'var(--$-color)'
				),
			),
		),
		
		// compare_background_custom
		'compare_background_custom_et-desktop' => array(
			'name'            => 'compare_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'compare_background_custom_et-desktop',
			'label'           => esc_html__( 'Background', 'xstore-core' ),
			'section'         => 'compare',
			'choices'         => array(
				'alpha' => true
			),
			'default'         => '#ffffff',
			'active_callback' => array(
				array(
					'setting'  => 'compare_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'property' => 'background-color',
				),
			),
		),
		
		'compare_color_et-desktop'         => array(
			'name'            => 'compare_color_et-desktop',
			'settings'        => 'compare_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'compare',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(compare)(compare_background_custom_et-desktop)',
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
					'setting'  => 'compare_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'property' => 'color'
				)
			),
		),

        // compare_overlay_background_custom
        'compare_overlay_background_custom_et-desktop' => array(
            'name'            => 'compare_overlay_background_custom_et-desktop',
            'type'            => 'color',
            'settings'        => 'compare_overlay_background_custom_et-desktop',
            'label'           => esc_html__( 'Item Background (hover)', 'xstore-core' ),
            'section'         => 'compare',
            'choices'         => array(
                'alpha' => true
            ),
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_content_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'off_canvas',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.header-wrapper .et_b_header-compare.et-off-canvas .cart_list.product_list_widget li:hover',
                    'property' => 'background-color',
                ),
            ),
        ),
		
		// compare_border_radius
		'compare_border_radius_et-desktop' => array(
			'name'      => 'compare_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'compare_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'compare',
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
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		
		'compare_box_model_et-desktop' => array(
			'name'        => 'compare_box_model_et-desktop',
			'settings'    => 'compare_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => esc_html__( 'You can select the margin, border-width and padding for compare element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'compare',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '5px',
				'padding-right'       => '0px',
				'padding-bottom'      => '5px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-compare.et_element-top-level > a'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_header-compare.et_element-top-level > a' )
		),
		
		'compare_box_model_et-mobile'            => array(
			'name'        => 'compare_box_model_et-mobile',
			'settings'    => 'compare_box_model_et-mobile',
			'label'       => $strings['label']['computed_box'],
			'description' => esc_html__( 'You can select the margin, border-width and padding for compare element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'compare',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level > a'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.mobile-header-wrapper .et_b_header-compare.et_element-top-level > a' )
		),
		
		// compare_border
		'compare_border_et-desktop'              => array(
			'name'      => 'compare_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'compare_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'compare',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'property' => 'border-style',
				),
			),
		),
		
		// compare_border_color_custom
		'compare_border_color_custom_et-desktop' => array(
			'name'        => 'compare_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'compare_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'compare',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-compare.et_element-top-level > a',
					'property' => 'border-color',
				),
			),
		),

        // content separator
        'compare_content_dropdown_separator'            => array(
            'name'     => 'compare_content_dropdown_separator',
            'type'     => 'custom',
            'settings' => 'compare_content_dropdown_separator',
            'section'  => 'compare',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-outdent"></span> <span style="padding-left: 3px;">' . esc_html__( 'Mini-compare Dropdown', 'xstore-core' ) . '</span></div>',
            'priority' => 10,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        // compare_zoom
        'compare_zoom_et-desktop'                       => array(
            'name'      => 'compare_zoom_et-desktop',
            'type'      => 'slider',
            'settings'  => 'compare_zoom_et-desktop',
            'label'     => esc_html__( 'Mini-compare Content size (%)', 'xstore-core' ),
            'section'   => 'compare',
            'default'   => 100,
            'choices'   => array(
                'min'  => '10',
                'max'  => '200',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'       => array( 'editor', 'front' ),
                    'element'       => '.et_b_header-compare.et_element-top-level .et-mini-content',
                    'property'      => '--content-zoom',
                    'value_pattern' => 'calc($em * .01)'
                ),
            ),
        ),

        // compare_zoom
        'compare_zoom_et-mobile'                        => array(
            'name'      => 'compare_zoom_et-mobile',
            'type'      => 'slider',
            'settings'  => 'compare_zoom_et-mobile',
            'label'     => esc_html__( 'Mini-compare Content size (%)', 'xstore-core' ),
            'section'   => 'compare',
            'default'   => 100,
            'choices'   => array(
                'min'  => '10',
                'max'  => '200',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'element'       => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level .et-mini-content',
                    'property'      => '--content-zoom',
                    'value_pattern' => 'calc($em * .01)'
                ),
            ),
        ),

        // compare_dropdown_position
        'compare_dropdown_position_et-desktop'          => array(
            'name'            => 'compare_dropdown_position_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'compare_dropdown_position_et-desktop',
            'label'           => esc_html__( 'Mini-compare Dropdown position', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 'right',
            'multiple'        => 1,
            'choices'         => $choices['dropdown_position'],
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_content_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'dropdown',
                ),
            ),
            'transport'       => 'postMessage',
            'js_vars'         => array(
                array(
                    'element'  => '.et_b_header-compare',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.et_b_header-compare',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-left',
                    'value'    => 'left'
                ),
            ),
        ),

        // compare_dropdown_position_custom
        'compare_dropdown_position_custom_et-desktop'   => array(
            'name'            => 'compare_dropdown_position_custom_et-desktop',
            'type'            => 'slider',
            'settings'        => 'compare_dropdown_position_custom_et-desktop',
            'label'           => esc_html__( 'Mini-compare Dropdown offset', 'xstore-core' ),
            'section'         => 'compare',
            'default'         => 0,
            'choices'         => array(
                'min'  => '-300',
                'max'  => '300',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'compare_content_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'dropdown',
                ),
                array(
                    'setting'  => 'compare_dropdown_position_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level.et-content-toTop .et-mini-content',
                    'property' => 'right',
                    'units'    => 'px'
                ),
            ),
        ),

        // compare_dropdown_background_custom
        'compare_dropdown_background_custom_et-desktop' => array(
            'name'      => 'compare_dropdown_background_custom_et-desktop',
            'type'      => 'color',
            'settings'  => 'compare_dropdown_background_custom_et-desktop',
            'label'     => esc_html__( 'Mini-compare Background', 'xstore-core' ),
            'section'   => 'compare',
            'choices'   => array(
                'alpha' => true
            ),
            'default'   => '#ffffff',
            'transport' => 'auto',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level .et-mini-content, .et_b_mobile-panel-compare .et-mini-content',
                    'property' => 'background-color',
                ),
            ),
        ),

        'compare_dropdown_color_et-desktop'              => array(
            'name'        => 'compare_dropdown_color_et-desktop',
            'settings'    => 'compare_dropdown_color_et-desktop',
            'label'       => esc_html__( 'WCAG Mini-compare Color', 'xstore-core' ),
            'description' => $strings['description']['wcag_color'],
            'type'        => 'kirki-wcag-tc',
            'section'     => 'compare',
            'default'     => '#000000',
            'choices'     => array(
                'setting' => 'setting(compare)(compare_dropdown_background_custom_et-desktop)',
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
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare.et_element-top-level .et-mini-content, .et_b_mobile-panel-compare .et-mini-content',
                    'property' => 'color'
                )
            ),
        ),

        // canvas type

        // compare_content_position
        'compare_content_position_et-desktop'            => array(
            'name'        => 'compare_content_position_et-desktop',
            'type'        => 'radio-buttonset',
            'settings'    => 'compare_content_position_et-desktop',
            'label'       => esc_html__( 'Mini-compare Off-canvas position', 'xstore-core' ),
            'description' => esc_html__( 'This option will work only if content type is set to Off-Canvas', 'xstore-core' ),
            'section'     => 'compare',
            'default'     => 'right',
            'multiple'    => 1,
            'choices'     => array(
                'left'  => esc_html__( 'Left side', 'xstore-core' ),
                'right' => esc_html__( 'Right side', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => array(
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-left',
                    'value'    => 'left'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-left',
                    'value'    => 'left'
                ),
            ),
        ),

        // compare_content_position
        'compare_content_position_et-mobile'             => array(
            'name'        => 'compare_content_position_et-mobile',
            'type'        => 'radio-buttonset',
            'settings'    => 'compare_content_position_et-mobile',
            'label'       => esc_html__( 'Mini-compare Off-canvas position', 'xstore-core' ),
            'description' => esc_html__( 'This option will work only if content type is set to Off-Canvas', 'xstore-core' ),
            'section'     => 'compare',
            'default'     => 'right',
            'multiple'    => 1,
            'choices'     => array(
                'left'  => esc_html__( 'Left side', 'xstore-core' ),
                'right' => esc_html__( 'Right side', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => array(
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas .et-close, .et-mobile-panel .et_b_mobile-panel-compare.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas .et-close, .et-mobile-panel .et_b_mobile-panel-compare.et-off-canvas .et-close',
                    'function' => 'toggleClass',
                    'class'    => 'full-left',
                    'value'    => 'left'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas, .et-mobile-panel .et_b_mobile-panel-compare.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-right',
                    'value'    => 'right'
                ),
                array(
                    'element'  => '.mobile-header-wrapper .et_b_header-compare.et_element-top-level.et-off-canvas, .et-mobile-panel .et_b_mobile-panel-compare.et-off-canvas',
                    'function' => 'toggleClass',
                    'class'    => 'et-content-left',
                    'value'    => 'left'
                ),
            ),
        ),

        'compare_content_box_model_et-desktop'           => array(
            'name'        => 'compare_content_box_model_et-desktop',
            'settings'    => 'compare_content_box_model_et-desktop',
            'label'       => esc_html__( 'Mini-compare Computed box', 'xstore-core' ),
            'description' => esc_html__( 'You can select the margin, border-width and padding for mini-compare element.', 'xstore-core' ),
            'type'        => 'kirki-box-model',
            'section'     => 'compare',
            'default'     => array(
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
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'output'      => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.et_b_header-compare.et_element-top-level .et-mini-content, .et-mobile-panel .et_b_mobile-panel-compare .et-mini-content',
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => box_model_output( '.et_b_header-compare.et_element-top-level .et-mini-content, .et-mobile-panel .et_b_mobile-panel-compare .et-mini-content' )
        ),

        // compare_content_border
        'compare_content_border_et-desktop'              => array(
            'name'      => 'compare_content_border_et-desktop',
            'type'      => 'select',
            'settings'  => 'compare_content_border_et-desktop',
            'label'     => esc_html__( 'Mini-compare Border style', 'xstore-core' ),
            'section'   => 'compare',
            'default'   => 'solid',
            'choices'   => $choices['border_style'],
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare .et-mini-content, .et-mobile-panel .et_b_mobile-panel-compare .et-mini-content',
                    'property' => 'border-style',
                ),
            ),
        ),

        // compare_content_border_color_custom
        'compare_content_border_color_custom_et-desktop' => array(
            'name'        => 'compare_content_border_color_custom_et-desktop',
            'type'        => 'color',
            'settings'    => 'compare_content_border_color_custom_et-desktop',
            'label'       => esc_html__( 'Mini-compare Border color', 'xstore-core' ),
            'description' => $strings['description']['border_color'],
            'section'     => 'compare',
            'default'     => '#e1e1e1',
            'choices'     => array(
                'alpha' => true
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.et_b_header-compare .et-mini-content, .et_b_header-compare .cart-widget-products, .et_b_header-compare.et-off-canvas .product_list_widget li:not(:last-child), .et_b_mobile-panel-compare .et-mini-content, .et_b_mobile-panel-compare .cart-widget-products, .et_b_mobile-panel-compare.et-off-canvas .product_list_widget li:not(:last-child)',
                    'property' => 'border-color',
                ),
            ),
        )
	
	);
	
	return array_merge( $fields, $args );
	
} );
