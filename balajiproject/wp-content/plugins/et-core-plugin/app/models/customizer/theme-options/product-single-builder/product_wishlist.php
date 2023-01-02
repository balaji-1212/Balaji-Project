<?php
/**
 * The template created for displaying product wishlist options
 *
 * @since   1.5.0
 * @version 1.0.1
 * last changes in 4.3.8 built-in wishlist options added
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_wishlist' => array(
			'name'       => 'product_wishlist',
			'title'      => esc_html__( 'Wishlist', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-heart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_wishlist', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_wishlist_content_separator'                  => array(
			'name'     => 'product_wishlist_content_separator',
			'type'     => 'custom',
			'settings' => 'product_wishlist_content_separator',
			'section'  => 'product_wishlist',
			'default'  => $separators['content'],
		),
		
		// product_wishlist_icon
		'product_wishlist_icon_et-desktop'                    => array(
			'name'     => 'product_wishlist_icon_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'product_wishlist_icon_et-desktop',
			'label'    => $strings['label']['icon'],
			'description' => sprintf(__('Note: Inherit and Custom SVG options work only for <span %s>Built-in Wishlist</span>', 'xstore-core'), 'class="et_edit" data-parent="xstore-wishlist" data-section="xstore_wishlist" style="color: #222; text-decoration: underline;"'),
			'section'  => 'product_wishlist',
			'default'  => 'type1',
			'choices'  => array(
				'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/wishlist/Wishlist-1.svg',
				'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/wishlist/Wishlist-2.svg',
                'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
                'inherit'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-inherit.svg',
				'none'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg',
			),
			'js_vars'  => array(
				array(
					'element'  => '.et_product-block .single-wishlist .et_b-icon',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => 'none'
				),
			),
		),

        // wishlist_icon_custom_svg
        'product_wishlist_icon_custom_svg_et-desktop'                   => array(
            'name'            => 'product_wishlist_icon_custom_svg_et-desktop',
            'type'            => 'image',
            'settings'        => 'product_wishlist_icon_custom_svg_et-desktop',
            'label'           => $strings['label']['custom_image_svg'],
            'description'     => $strings['description']['custom_image_svg'] . ' ' . sprintf(__('Note: it works only for <span %s>Built-in Wishlist</span>', 'xstore-core'), 'class="et_edit" data-parent="xstore-wishlist" data-section="xstore_wishlist" style="color: #222; text-decoration: underline;"'),
            'section'         => 'product_wishlist',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'product_wishlist_icon_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),
		
		// product_wishlist_label_add_to_wishlist
		'product_wishlist_label_add_to_wishlist'              => array(
			'name'     => 'product_wishlist_label_add_to_wishlist',
			'type'     => 'etheme-text',
			'settings' => 'product_wishlist_label_add_to_wishlist',
			'label'    => esc_html__( 'Add to wishlist text', 'xstore-core' ),
			'section'  => 'product_wishlist',
			'default'  => esc_html__( 'Add to wishlist', 'xstore-core' ),
			// 'transport' => 'postMessage',
			// 'js_vars' => array(
			// 	array(
			// 		'element'  => '.et_product-block .single-wishlist .yith-wcwl-add-button a',
			// 		'attr' => 'data-hover',
			// 		'function' => 'html',
			// 	),
			// 	array(
			// 		'element'  => '.et_product-block .single-wishlist .yith-wcwl-add-button a .et_b-icon + span',
			// 		'function' => 'html',
			// 	),
			// ),
		),
		
		// product_wishlist_label_browse_wishlist
		'product_wishlist_label_browse_wishlist'              => array(
			'name'     => 'product_wishlist_label_browse_wishlist',
			'type'     => 'etheme-text',
			'settings' => 'product_wishlist_label_browse_wishlist',
			'label'    => esc_html__( 'Browse wishlist text', 'xstore-core' ),
			'section'  => 'product_wishlist',
			'default'  => esc_html__( 'Browse wishlist', 'xstore-core' ),
			// 'transport' => 'postMessage',
			// 'js_vars' => array(
			// 	array(
			// 		'element'  => '.et_product-block .single-wishlist .yith-wcwl-wishlistaddedbrowse a .et_b-icon + span, .et_product-block .single-wishlist .yith-wcwl-wishlistexistsbrowse a .et_b-icon + span',
			// 		'function' => 'html',
			// 	),
			// ),
		),

        'product_wishlist_tooltip' => array(
            'name'     => 'product_wishlist_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_tooltip',
            'label'    => __( 'Add tooltip', 'xstore-core' ),
            'section'  => 'product_wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_only_icon' => array(
            'name'     => 'product_wishlist_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_only_icon',
            'label'    => __( 'Only icon', 'xstore-core' ),
            'section'  => 'product_wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_redirect_on_remove' => array(
            'name'     => 'product_wishlist_redirect_on_remove',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_redirect_on_remove',
            'label'    => __( 'Redirect to wishlist on remove', 'xstore-core' ),
            'section'  => 'product_wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product single product wishlist
        'go_to_section_xstore_wishlist'                 => array(
            'name'     => 'go_to_section_xstore_wishlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_wishlist',
            'section'  => 'product_wishlist',
            'default'  => '<span class="et_edit" data-parent="xstore-wishlist" data-section="xstore_wishlist" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Global wishlist settings', 'xstore-core' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
		
		// style separator
		'product_wishlist_style_separator'                    => array(
			'name'     => 'product_wishlist_style_separator',
			'type'     => 'custom',
			'settings' => 'product_wishlist_style_separator',
			'section'  => 'product_wishlist',
			'default'  => $separators['style'],
		),
		
		// product_wishlist_align
		'product_wishlist_align_et-desktop'                   => array(
			'name'        => 'product_wishlist_align_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'product_wishlist_align_et-desktop',
			'label'       => $strings['label']['alignment'],
			'description' => $strings['description']['size_bigger_attention'],
			'section'     => 'product_wishlist',
			'default'     => 'start',
			'choices'     => $choices['alignment_with_inherit'],
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-wishlist',
					'property' => 'text-align'
				)
			)
		),
		
		// product_wishlist_proportion
		'product_wishlist_proportion_et-desktop'              => array(
			'name'      => 'product_wishlist_proportion_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_wishlist_proportion_et-desktop',
			'label'     => $strings['label']['size_proportion'],
			'section'   => 'product_wishlist',
			'default'   => 1,
			'choices'   => array(
				'min'  => '0',
				'max'  => '5',
				'step' => '.01',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-product-wishlist-proportion',
				),
			),
		),
		
		// product_wishlist_background
		// 'product_wishlist_background_et-desktop'	=>	 array(
// 'name'		  => 'product_wishlist_background_et-desktop',
		// 	'type'        => 'select',
		// 	'settings'    => 'product_wishlist_background_et-desktop',
		// 	'label'       => esc_html__( 'Background', 'xstore-core' ),
		// 	'section'     => 'product_wishlist',
		// 	'default'     => 'transparent',
		// 	'choices'     => $choices['colors'],
		// 	'transport' => 'auto',
		// 	'output'      => array(
		// 		array(
		// 'context'   => array('editor', 'front'),
		// 			'element' => '.et_product-block .tinvwl-shortcode-add-to-cart > a',
		// 			'property' => 'background-color',
		// 			'value_pattern' => 'var(--$-color)'
		// 		),
		// 	),
		// ),
		
		// product_wishlist_background_custom
		'product_wishlist_background_custom_et-desktop'       => array(
			'name'        => 'product_wishlist_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_wishlist_background_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_wishlist',
			'choices'     => array(
				'alpha' => true
			),
			'default'     => '#ffffff',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div,
					.et_product-block .xstore-wishlist-single',
					'property' => 'background-color',
				),
			),
		),
		'product_wishlist_color_et-desktop'                   => array(
			'name'        => 'product_wishlist_color_et-desktop',
			'settings'    => 'product_wishlist_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_wishlist',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(product_wishlist)(product_wishlist_background_custom_et-desktop)',
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
					'element'  => '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div,
					.et_product-block .single-wishlist .wishlist-fragment > div a,
					.et_product-block .xstore-wishlist-single,
					.et_product-block .xstore-wishlist-single .et-icon',
					'property' => 'color'
				)
			),
		),
		
		// product_wishlist_background_hover_custom
		'product_wishlist_background_hover_custom_et-desktop' => array(
			'name'        => 'product_wishlist_background_hover_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_wishlist_background_hover_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color_hover'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_wishlist',
			'choices'     => array(
				'alpha' => true
			),
			'default'     => '#ffffff',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-wishlist .show:hover, .et_product-block .single-wishlist .wishlist-fragment > div:hover, .et_product-block .xstore-wishlist-single:hover',
					'property' => 'background-color',
				),
			),
		),
		'product_wishlist_hover_color_et-desktop'             => array(
			'name'        => 'product_wishlist_hover_color_et-desktop',
			'settings'    => 'product_wishlist_hover_color_et-desktop',
			'label'       => $strings['label']['wcag_color_hover'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_wishlist',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(product_wishlist)(product_wishlist_background_hover_custom_et-desktop)',
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
					'element'  =>
						'.et_product-block .single-wishlist .show:hover, .et_product-block .single-wishlist .wishlist-fragment > div:hover,
						.et_product-block .single-wishlist .wishlist-fragment > div a:hover,
						.et_product-block .xstore-wishlist-single:hover, .et_product-block .xstore-wishlist-single:hover .et-icon',
					'property' => 'color'
				)
			),
		),
		
		// product_wishlist_border_radius
		'product_wishlist_border_radius_et-desktop'           => array(
			'name'      => 'product_wishlist_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_wishlist_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'product_wishlist',
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
					'element'  => '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div, .et_product-block .xstore-wishlist-single',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		'product_wishlist_box_model_et-desktop'               => array(
			'name'        => 'product_wishlist_box_model_et-desktop',
			'settings'    => 'product_wishlist_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_wishlist',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '10px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '0px',
				'padding-right'       => '0px',
				'padding-bottom'      => '0px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div, .et_product-block .xstore-wishlist-single'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div, .et_product-block .xstore-wishlist-single' )
		),
		
		// product_wishlist_border
		'product_wishlist_border_et-desktop'                  => array(
			'name'      => 'product_wishlist_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_wishlist_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_wishlist',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div, .et_product-block .xstore-wishlist-single',
					'property' => 'border-style',
				),
			),
		),
		
		// product_wishlist_border_color_custom
		'product_wishlist_border_color_custom_et-desktop'     => array(
			'name'      => 'product_wishlist_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_wishlist_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_wishlist',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-wishlist .show, .et_product-block .single-wishlist .wishlist-fragment > div, .et_product-block .xstore-wishlist-single',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
