<?php
/**
 * The template created for displaying header cart options when woocommerce plugin is installed
 *
 * @version 1.0.8
 * @since   1.4.0
 * last changes in 2.3.9
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'cart' => array(
			'name'       => 'cart',
			'title'      => esc_html__( 'Cart', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-cart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/cart', function ( $fields ) use ( $separators, $strings, $choices, $box_models, $sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'cart_content_separator'          => array(
			'name'     => 'cart_content_separator',
			'type'     => 'custom',
			'settings' => 'cart_content_separator',
			'section'  => 'cart',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// cart_style
		'cart_style_et-desktop'           => array(
			'name'            => 'cart_style_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'cart_style_et-desktop',
			'label'           => $strings['label']['style'],
			'description'     => esc_html__( 'Take a look on the video tutorial "How to ..." set up cart style2 and style3 ', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'type1',
			'choices'         => et_b_element_styles( 'cart' ),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_style_et-desktop' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'cart-type1',
					'value'    => 'type1'
				),
				array(
					'element'  => '.et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'cart-type2',
					'value'    => 'type2'
				),
				array(
					'element'  => '.et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'cart-type3',
					'value'    => 'type3'
				),
			),
		),
		
		// cart_icon
		'cart_icon_et-desktop'            => array(
			'name'            => 'cart_icon_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'cart_icon_et-desktop',
			'label'           => $strings['label']['icon'],
			'description'     => $strings['description']['icons_style'],
			'section'         => 'cart',
			'default'         => 'type1',
			'choices'         => array(
				'type1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-1.svg',
				'type2'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-2.svg',
				'type3'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-3.svg',
				'type4'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/cart/Cart-4.svg',
				'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_icon' => array(
					'selector'        => '.et_b_header-cart > a .et_b-icon .et-svg',
					'render_callback' => function () {
						global $et_cart_icons;
						$type = get_theme_mod( 'cart_icon_et-desktop', 'type1' );
						if ( $type == 'custom' && get_theme_mod( 'cart_icon_custom_svg_et-desktop', '' ) != '' ) {
							return get_post_meta( get_theme_mod( 'cart_icon_custom_svg_et-desktop', '' ), '_xstore_inline_svg', true );
						}
						
						return $et_cart_icons['light'][ $type ];
					},
				),
			),
		),
		
		// cart_icon_custom_svg
		'cart_icon_custom_svg_et-desktop' => array(
			'name'            => 'cart_icon_custom_svg_et-desktop',
			'type'            => 'image',
			'settings'        => 'cart_icon_custom_svg_et-desktop',
			'label'           => $strings['label']['custom_image_svg'],
			'description'     => $strings['description']['custom_image_svg'],
			'section'         => 'cart',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_icon_custom_svg_et-desktop' => array(
					'selector'        => '.header-wrapper .et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		
		// cart_icon_zoom 
		'cart_icon_zoom_et-desktop'       => array(
			'name'            => 'cart_icon_zoom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_icon_zoom_et-desktop',
			'label'           => $strings['label']['icons_zoom'],
			'section'         => 'cart',
			'default'         => 1.3,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_icon_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level > a svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level > a svg',
					'property' => 'height',
					'units'    => 'em'
				),
			)
		),
		
		// cart_icon_zoom 
		'cart_icon_zoom_et-mobile'        => array(
			'name'            => 'cart_icon_zoom_et-mobile',
			'type'            => 'slider',
			'settings'        => 'cart_icon_zoom_et-mobile',
			'label'           => $strings['label']['icons_zoom'],
			'section'         => 'cart',
			'default'         => 1.4,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_icon_et-mobile',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level > a svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level > a svg',
					'property' => 'height',
					'units'    => 'em'
				),
			)
		),
		
		// cart_label
		'cart_label_et-desktop'           => array(
			'name'      => 'cart_label_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'cart_label_et-desktop',
			'label'     => $strings['label']['show_title'],
			'section'   => 'cart',
			'default'   => '1',
			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-cart.et_element-top-level > a .et-element-label',
//					'function' => 'toggleClass',
//					'class'    => 'dt-hide',
//					'value'    => false
//				),
//			),
            'partial_refresh' => array(
                'cart_label_et-desktop' => array(
                    'selector'        => '.et_b_header-cart.et_element-top-level',
                    'render_callback' => 'header_cart_callback'
                ),
            ),
		),
		
		// cart_label
		'cart_label_et-mobile'            => array(
			'name'      => 'cart_label_et-mobile',
			'type'      => 'toggle',
			'settings'  => 'cart_label_et-mobile',
			'label'     => $strings['label']['show_title'],
			'section'   => 'cart',
			'default'   => '0',
			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-cart.et_element-top-level > a .et-element-label',
//					'function' => 'toggleClass',
//					'class'    => 'mob-hide',
//					'value'    => false
//				),
//			),
            'partial_refresh' => array(
                'cart_label_et-mobile' => array(
                    'selector'        => '.et_b_header-cart.et_element-top-level',
                    'render_callback' => 'header_cart_callback'
                ),
            ),
		),
		
		// cart_label_custom
		'cart_label_custom'               => array(
			'name'      => 'cart_label_custom',
			'type'      => 'etheme-text',
			'settings'  => 'cart_label_custom',
			'section'   => 'cart',
			'default'   => esc_html__( 'Cart', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-cart > a .et-element-label',
					'function' => 'html',
				),
			),
		),
		
		// cart_total
		'cart_total_et-desktop'           => array(
			'name'      => 'cart_total_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'cart_total_et-desktop',
			'label'     => esc_html__( 'Show total price', 'xstore-core' ),
			'section'   => 'cart',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a .et-cart-total',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
			),
		),
		
		// cart_total
		'cart_total_et-mobile'            => array(
			'name'      => 'cart_total_et-mobile',
			'type'      => 'toggle',
			'settings'  => 'cart_total_et-mobile',
			'label'     => esc_html__( 'Show total price', 'xstore-core' ),
			'section'   => 'cart',
			'default'   => 0,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a .et-cart-total',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
		),
		
		// cart_content_type
		'cart_content_type_et-desktop'    => array(
			'name'            => 'cart_content_type_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'cart_content_type_et-desktop',
			'label'           => esc_html__( 'Mini-cart type', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'dropdown',
			'multiple'        => 1,
			'choices'         => array(
				'none'       => esc_html__( 'None', 'xstore-core' ),
				'dropdown'   => esc_html__( 'Dropdown', 'xstore-core' ),
				'off_canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_content_type_et-desktop' => array(
					'selector'        => '.header-wrapper .et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'et-content-toTop',
					'value'    => 'dropdown'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'et-content_toggle',
					'value'    => 'off_canvas'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'et-off-canvas',
					'value'    => 'off_canvas'
				),
			),
		),
		
		// cart_content_type 
		'cart_content_type_et-mobile'     => array(
			'name'            => 'cart_content_type_et-mobile',
			'type'            => 'radio-buttonset',
			'settings'        => 'cart_content_type_et-mobile',
			'label'           => esc_html__( 'Mini-cart type', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'off_canvas',
			'multiple'        => 1,
			'choices'         => array(
				'none'       => esc_html__( 'None', 'xstore-core' ),
				'off_canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_content_type_et-mobile' => array(
					'selector'        => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'et-content-toTop',
					'value'    => 'dropdown'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'et-content_toggle',
					'value'    => 'off_canvas'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'et-off-canvas',
					'value'    => 'off_canvas'
				),
			),
		),
		
		'mini-cart-items-count'                  => array(
			'name'            => 'mini-cart-items-count',
			'type'            => 'slider',
			'settings'        => 'mini-cart-items-count',
			'label'           => esc_html__( 'Number of products in mini-cart', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 3,
			'choices'         => array(
				'min'  => 1,
				'max'  => 30,
				'step' => 1,
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mini-cart-items-count' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
		
		// cart_link_to
		'cart_link_to'                           => array(
			'name'            => 'cart_link_to',
			'type'            => 'select',
			'settings'        => 'cart_link_to',
			'label'           => esc_html__( 'Link to', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'cart_url',
			'priority'        => 10,
			'choices'         => array(
				'cart_url'     => esc_html__( 'Cart page', 'xstore-core' ),
				'checkout_url' => esc_html__( 'Checkout page', 'xstore-core' ),
				'custom_url'   => $strings['label']['custom_link'],
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_link_to' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
			'active_callback' => array(
				array(
					array(
						'setting'  => 'cart_content_type_et-desktop',
						'operator' => '!=',
						'value'    => 'off_canvas',
					),
					array(
						'setting'  => 'cart_content_type_et-mobile',
						'operator' => '!=',
						'value'    => 'off_canvas',
					),
				)
			),
		),
		
		// cart_custom_url
		'cart_custom_url'                        => array(
			'name'            => 'cart_custom_url',
			'type'            => 'etheme-link',
			'settings'        => 'cart_custom_url',
			'label'           => $strings['label']['custom_link'],
			'section'         => 'cart',
			'default'         => '#',
			'active_callback' => array(
				array(
					'setting'  => 'cart_link_to',
					'operator' => '==',
					'value'    => 'custom_url',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-cart > a',
					'attr'     => 'href',
					'function' => 'html',
				),
			),
		),
		
		// cart_content_quantity_input
		'cart_content_quantity_input_et-desktop' => array(
			'name'            => 'cart_content_quantity_input_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_content_quantity_input_et-desktop',
			'label'           => esc_html__( 'Show quantity input for products', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_content_quantity_input_et-desktop' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
		),
		
		'cart_content_quantity_input_et-mobile'                       => array(
			'name'            => 'cart_content_quantity_input_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'cart_content_quantity_input_et-mobile',
			'label'           => esc_html__( 'Show quantity input for products', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_content_quantity_input_et-mobile' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
		),
		
		// cart_content_linked_products
		'cart_content_linked_products_et-desktop'                     => array(
			'name'     => 'cart_content_linked_products_et-desktop',
			'type'     => 'toggle',
			'settings' => 'cart_content_linked_products_et-desktop',
			'label'    => esc_html__( 'Show Linked Products (off-canvas)', 'xstore-core' ),
			'section'  => 'cart',
			'default'  => '0',
		),
		
		// cart_content_linked_products
		'cart_content_linked_products_et-mobile'                      => array(
			'name'     => 'cart_content_linked_products_et-mobile',
			'type'     => 'toggle',
			'settings' => 'cart_content_linked_products_et-mobile',
			'label'    => esc_html__( 'Show Linked Products (off-canvas)', 'xstore-core' ),
			'section'  => 'cart',
			'default'  => '0',
		),
		
		// cart_content_linked_products_type
		'cart_content_linked_products_type_et-desktop'                => array(
			'name'            => 'cart_content_linked_products_type_et-desktop',
			'type'            => 'select',
			'settings'        => 'cart_content_linked_products_type_et-desktop',
			'label'           => esc_html__( 'Linked Products type', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'upsell',
			'choices'         => array(
				'upsell'     => esc_html__( 'Upsells', 'xstore-core' ),
				'cross-sell' => esc_html__( 'Cross-sells', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					array(
						'setting'  => 'cart_content_linked_products_et-desktop',
						'operator' => '==',
						'value'    => 1,
					),
					array(
						'setting'  => 'cart_content_linked_products_et-mobile',
						'operator' => '==',
						'value'    => 1,
					)
				),
			),
		),
		
		// cart_content_view_cart_button
		'cart_content_view_cart_button_et-desktop'                    => array(
			'name'            => 'cart_content_view_cart_button_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_content_view_cart_button_et-desktop',
			'label'           => esc_html__( 'Show view cart button', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_content_view_cart_button_et-desktop' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
		),
		
		// go_to_section_ajax_added_product_notify_popup_products_type
		'go_to_section_ajax_added_product_notify_popup_products_type' => array(
			'name'     => 'go_to_section_ajax_added_product_notify_popup_products_type',
			'type'     => 'custom',
			'settings' => 'go_to_section_ajax_added_product_notify_popup_products_type',
			'section'  => 'cart',
			'default'  => '<span class="et_edit" data-parent="shop-page" data-section="ajax_added_product_notify_popup_products_type" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Added to cart notification', 'xstore-core' ) . '</span>',
		),
		
		// content separator 
		'cart_quantity_separator'                                     => array(
			'name'     => 'cart_quantity_separator',
			'type'     => 'custom',
			'settings' => 'cart_quantity_separator',
			'section'  => 'cart',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-image-filter"></span> <span style="padding-left: 3px;">' . esc_html__( 'Quantity options', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		// cart_quantity
		'cart_quantity_et-desktop'                                    => array(
			'name'            => 'cart_quantity_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_quantity_et-desktop',
			'label'           => esc_html__( 'Show cart quantity', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => '1',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_quantity_et-desktop' => array(
					'selector'        => '.et_b_header-cart.et_element-top-level',
					'render_callback' => 'header_cart_callback'
				),
			),
		),
		
		// cart_quantity_position
		'cart_quantity_position_et-desktop'                           => array(
			'name'            => 'cart_quantity_position_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'cart_quantity_position_et-desktop',
			'label'           => esc_html__( 'Label position', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'right',
			'multiple'        => 1,
			'choices'         => array(
				'top'   => esc_html__( 'Top', 'xstore-core' ),
				'right' => esc_html__( 'Right', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_quantity_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-cart',
					'function' => 'toggleClass',
					'class'    => 'et-quantity-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et_b_header-cart',
					'function' => 'toggleClass',
					'class'    => 'et-quantity-top',
					'value'    => 'top'
				),
			),
		),
		
		// cart_quantity_size
		'cart_quantity_size_et-desktop'                               => array(
			'name'            => 'cart_quantity_size_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_quantity_size_et-desktop',
			'label'           => esc_html__( 'Quantity font size (em)', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 0.75,
			'choices'         => array(
				'min'  => '.3',
				'max'  => '3',
				'step' => '.01',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_quantity_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level .et-quantity',
					'property' => 'font-size',
					'units'    => 'em'
				),
			),
		),
		
		// cart_quantity_proportions
		'cart_quantity_proportions_et-desktop'                        => array(
			'name'            => 'cart_quantity_proportions_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_quantity_proportions_et-desktop',
			'label'           => esc_html__( 'Quantity background size (em)', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 1.5,
			'choices'         => array(
				'min'  => '0.1',
				'max'  => '5',
				'step' => '0.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_quantity_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level .et-quantity',
					'property' => '--et-quantity-proportion',
					'units'    => 'em'
				),
			),
		),
		
		// cart_quantity_active_background_custom
		'cart_quantity_active_background_custom_et-desktop'           => array(
			'name'            => 'cart_quantity_active_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'cart_quantity_active_background_custom_et-desktop',
			'label'           => esc_html__( 'Quantity Background (active)', 'xstore-core' ),
			'section'         => 'cart',
			'choices'         => array(
				'alpha' => true
			),
			'default'         => '#ffffff',
			'active_callback' => array(
				array(
					'setting'  => 'cart_quantity_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level .et-quantity',
					'property' => 'background-color',
				),
			),
		),
		
		// cart_quantity_active_color
		'cart_quantity_active_color_et-desktop'                       => array(
			'name'            => 'cart_quantity_active_color_et-desktop',
			'settings'        => 'cart_quantity_active_color_et-desktop',
			'label'           => esc_html__( 'WCAG Quantity Color (active)', 'xstore-core' ),
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'cart',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(cart)(cart_quantity_active_background_custom_et-desktop)',
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
					'setting'  => 'cart_quantity_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level .et-quantity',
					'property' => 'color'
				)
			)
		),
		
		// style separator
		'cart_style_separator'                                        => array(
			'name'     => 'cart_style_separator',
			'type'     => 'custom',
			'settings' => 'cart_style_separator',
			'section'  => 'cart',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// cart_content_alignment
		'cart_content_alignment_et-desktop'                           => array(
			'name'        => 'cart_content_alignment_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'cart_content_alignment_et-desktop',
			'label'       => $strings['label']['alignment'],
			'description' => esc_html__( 'Attention: if your element size bigger than the column width where the element is placed, element positioning may be a little bit different than as expected.', 'xstore-core' ),
			'section'     => 'cart',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// cart_content_alignment
		'cart_content_alignment_et-mobile'                            => array(
			'name'      => 'cart_content_alignment_et-mobile',
			'type'      => 'radio-buttonset',
			'settings'  => 'cart_content_alignment_et-mobile',
			'label'     => $strings['label']['alignment'],
			'section'   => 'cart',
			'default'   => 'start',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// cart_background
		'cart_background_et-desktop'                                  => array(
			'name'     => 'cart_background_et-desktop',
			'type'     => 'select',
			'settings' => 'cart_background_et-desktop',
			'label'    => $strings['label']['colors'],
			'section'  => 'cart',
			'default'  => 'current',
			'choices'  => $choices['colors'],
			'output'   => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-cart.et_element-top-level > a',
					'property'      => 'color',
					'value_pattern' => 'var(--$-color)'
				),
			),
		),
		
		// cart_background_custom
		'cart_background_custom_et-desktop'                           => array(
			'name'            => 'cart_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'cart_background_custom_et-desktop',
			'label'           => esc_html__( 'Background', 'xstore-core' ),
			'section'         => 'cart',
			'choices'         => array(
				'alpha' => true
			),
			'default'         => '#ffffff',
			'active_callback' => array(
				array(
					'setting'  => 'cart_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'property' => 'background-color',
				),
			),
		),
		
		// cart_color
		'cart_color_et-desktop'                                       => array(
			'name'            => 'cart_color_et-desktop',
			'settings'        => 'cart_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'cart',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(cart)(cart_background_custom_et-desktop)',
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
					'setting'  => 'cart_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'property' => 'color'
				)
			),
		),
		
		// cart_overlay_background_custom
		'cart_overlay_background_custom_et-desktop'                   => array(
			'name'            => 'cart_overlay_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'cart_overlay_background_custom_et-desktop',
			'label'           => esc_html__( 'Item Background (hover)', 'xstore-core' ),
			'section'         => 'cart',
			'choices'         => array(
				'alpha' => true
			),
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'cart_content_type_et-desktop',
					'operator' => '==',
					'value'    => 'off_canvas',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.header-wrapper .et_b_header-cart.et-off-canvas .cart_list.product_list_widget li:hover,
					.header-wrapper .et_b_header-cart.et-off-canvas .cart_list.product_list_widget .quantity-wrapper,
					.header-wrapper .et-off-canvas.et-off-canvas .cart_list.product_list_widget .quantity-wrapper span,
					.header-wrapper .et-off-canvas.et-off-canvas .cart_list.product_list_widget .quantity-wrapper input',
					'property' => 'background-color',
					'suffix'   => '!important'
				),
			),
		),
		
		// cart_border_radius
		'cart_border_radius_et-desktop'                               => array(
			'name'      => 'cart_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'cart_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'cart',
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
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		
		// cart_box_model
		'cart_box_model_et-desktop'                                   => array(
			'name'        => 'cart_box_model_et-desktop',
			'settings'    => 'cart_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'cart',
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
					'element' => '.et_b_header-cart.et_element-top-level > a'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_header-cart.et_element-top-level > a' )
		),
		
		// cart_box_model
		'cart_box_model_et-mobile'                                    => array(
			'name'        => 'cart_box_model_et-mobile',
			'settings'    => 'cart_box_model_et-mobile',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'cart',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level > a'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.mobile-header-wrapper .et_b_header-cart.et_element-top-level > a' )
		),
		
		// cart_border
		'cart_border_et-desktop'                                      => array(
			'name'      => 'cart_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'cart_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'cart',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'property' => 'border-style',
				),
			),
		),
		
		// cart_border_color_custom
		'cart_border_color_custom_et-desktop'                         => array(
			'name'        => 'cart_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'cart_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'cart',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level > a',
					'property' => 'border-color',
				),
			),
		),
		
		// content separator 
		'cart_content_dropdown_separator'                             => array(
			'name'     => 'cart_content_dropdown_separator',
			'type'     => 'custom',
			'settings' => 'cart_content_dropdown_separator',
			'section'  => 'cart',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-outdent"></span> <span style="padding-left: 3px;">' . esc_html__( 'Mini-cart Dropdown', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		// cart_zoom
		'cart_zoom_et-desktop'                                        => array(
			'name'      => 'cart_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'cart_zoom_et-desktop',
			'label'     => $strings['label']['content_size'],
			'section'   => 'cart',
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
					'element'       => '.et_b_header-cart.et_element-top-level .et-mini-content',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// cart_zoom
		'cart_zoom_et-mobile'                                         => array(
			'name'      => 'cart_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'cart_zoom_et-mobile',
			'label'     => $strings['label']['content_size'],
			'section'   => 'cart',
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
					'element'       => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level .et-mini-content',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// cart_dropdown_position
		'cart_dropdown_position_et-desktop'                           => array(
			'name'            => 'cart_dropdown_position_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'cart_dropdown_position_et-desktop',
			'label'           => esc_html__( 'Mini-cart Dropdown position', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 'right',
			'multiple'        => 1,
			'choices'         => $choices['dropdown_position'],
			'active_callback' => array(
				array(
					'setting'  => 'cart_content_type_et-desktop',
					'operator' => '==',
					'value'    => 'dropdown',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-cart',
					'function' => 'toggleClass',
					'class'    => 'et-content-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et_b_header-cart',
					'function' => 'toggleClass',
					'class'    => 'et-content-left',
					'value'    => 'left'
				),
			),
		),
		
		// cart_dropdown_position_custom
		'cart_dropdown_position_custom_et-desktop'                    => array(
			'name'            => 'cart_dropdown_position_custom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_dropdown_position_custom_et-desktop',
			'label'           => esc_html__( 'Mini-cart Dropdown offset', 'xstore-core' ),
			'section'         => 'cart',
			'default'         => 0,
			'choices'         => array(
				'min'  => '-300',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_content_type_et-desktop',
					'operator' => '==',
					'value'    => 'dropdown',
				),
				array(
					'setting'  => 'cart_dropdown_position_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level.et-content-toTop .et-mini-content',
					'property' => 'left',
					'units'    => 'px'
				),
			),
		),
		
		// cart_dropdown_background_custom
		'cart_dropdown_background_custom_et-desktop'                  => array(
			'name'      => 'cart_dropdown_background_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'cart_dropdown_background_custom_et-desktop',
			'label'     => esc_html__( 'Mini-cart Background', 'xstore-core' ),
			'section'   => 'cart',
			'choices'   => array(
				'alpha' => true
			),
			'default'   => '#ffffff',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level .et-mini-content, .et_b_mobile-panel-cart .et-mini-content',
					'property' => 'background-color',
				),
			),
		),
		
		// cart_dropdown_color 
		'cart_dropdown_color_et-desktop'                              => array(
			'name'        => 'cart_dropdown_color_et-desktop',
			'settings'    => 'cart_dropdown_color_et-desktop',
			'label'       => esc_html__( 'Mini-cart WCAG Color', 'xstore-core' ),
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'cart',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(cart)(cart_dropdown_background_custom_et-desktop)',
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
					'element'  => '.et_b_header-cart.et_element-top-level .et-mini-content, .et_b_mobile-panel-cart .et-mini-content',
					'property' => 'color'
				)
			),
		),
		
		// canvas type
		
		// cart_content_position
		'cart_content_position_et-desktop'                            => array(
			'name'        => 'cart_content_position_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'cart_content_position_et-desktop',
			'label'       => esc_html__( 'Mini-cart Off-canvas position', 'xstore-core' ),
			'description' => esc_html__( 'This option will work only if content type is set to Off-Canvas', 'xstore-core' ),
			'section'     => 'cart',
			'default'     => 'right',
			'multiple'    => 1,
			'choices'     => array(
				'left'  => esc_html__( 'Left side', 'xstore-core' ),
				'right' => esc_html__( 'Right side', 'xstore-core' ),
			),
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-left',
					'value'    => 'left'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-left',
					'value'    => 'left'
				),
			),
		),
		
		// cart_content_position
		'cart_content_position_et-mobile'                             => array(
			'name'        => 'cart_content_position_et-mobile',
			'type'        => 'radio-buttonset',
			'settings'    => 'cart_content_position_et-mobile',
			'label'       => esc_html__( 'Mini-cart Off-canvas position', 'xstore-core' ),
			'description' => esc_html__( 'This option will work only if content type is set to Off-Canvas', 'xstore-core' ),
			'section'     => 'cart',
			'default'     => 'right',
			'multiple'    => 1,
			'choices'     => array(
				'left'  => esc_html__( 'Left side', 'xstore-core' ),
				'right' => esc_html__( 'Right side', 'xstore-core' ),
			),
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas .et-close, .et-mobile-panel .et_b_mobile-panel-cart.et-off-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas .et-close, .et-mobile-panel .et_b_mobile-panel-cart.et-off-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-left',
					'value'    => 'left'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas, .et-mobile-panel .et_b_mobile-panel-cart.et-off-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-cart.et_element-top-level.et-off-canvas, .et-mobile-panel .et_b_mobile-panel-cart.et-off-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-left',
					'value'    => 'left'
				),
			),
		),
		
		// cart_content_box_model
		'cart_content_box_model_et-desktop'                           => array(
			'name'        => 'cart_content_box_model_et-desktop',
			'settings'    => 'cart_content_box_model_et-desktop',
			'label'       => esc_html__( 'Mini-cart Computed box', 'xstore-core' ),
			'description' => esc_html__( 'You can select the margin, border-width and padding for mini-cart element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'cart',
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
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-cart.et_element-top-level .et-mini-content, .et_b_mobile-panel-cart .et-mini-content',
				),
				array(
					'choice'        => 'padding-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
					'property'      => 'margin-left',
					'value_pattern' => '-$'
				),
				array(
					'choice'   => 'padding-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart .et-cart-progress, .et_b_mobile-panel-cart .et-cart-progress',
					'property' => 'padding-left',
				),
				array(
					'choice'        => 'padding-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
					'property'      => 'margin-right',
					'value_pattern' => '-$'
				),
				array(
					'choice'   => 'padding-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart .et-cart-progress, .et_b_mobile-panel-cart .et-cart-progress',
					'property' => 'padding-right',
				),
				array(
					'choice'        => 'padding-bottom',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
					'property'      => 'margin-bottom',
					'value_pattern' => '-$'
				),
				array(
					'choice'   => 'padding-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
					'property' => 'margin-top',
				),
				array(
					'choice'   => 'padding-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et-off-canvas .woocommerce-mini-cart__footer-wrapper, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer-wrapper',
					'property' => 'padding-top',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => array_merge(
				box_model_output( '.et_b_header-cart.et_element-top-level .et-mini-content, .et_b_mobile-panel-cart .et-mini-content' ),
				array(
					array(
						'choice'        => 'padding-left',
						'function'      => 'css',
						'element'       => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
						'property'      => 'margin-left',
						'value_pattern' => '-$'
					),
					array(
						'choice'   => 'padding-left',
						'context'  => array( 'editor', 'front' ),
						'element'  => '.et_b_header-cart .et-cart-progress, .et_b_mobile-panel-cart .et-cart-progress',
						'property' => 'padding-left',
					),
					array(
						'choice'        => 'padding-right',
						'function'      => 'css',
						'element'       => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
						'property'      => 'margin-right',
						'value_pattern' => '-$'
					),
					array(
						'choice'   => 'padding-right',
						'context'  => array( 'editor', 'front' ),
						'element'  => '.et_b_header-cart .et-cart-progress, .et_b_mobile-panel-cart .et-cart-progress',
						'property' => 'padding-right',
					),
					array(
						'choice'        => 'padding-bottom',
						'function'      => 'css',
						'element'       => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
						'property'      => 'margin-bottom',
						'value_pattern' => '-$'
					),
					array(
						'choice'   => 'padding-bottom',
						'function' => 'css',
						'element'  => '.et_b_header-cart .woocommerce-mini-cart__footer, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
						'property' => 'margin-top',
					),
					array(
						'choice'   => 'padding-bottom',
						'function' => 'css',
						'element'  => '.et_b_header-cart.et-off-canvas .woocommerce-mini-cart__footer-wrapper, .et_b_mobile-panel-cart .woocommerce-mini-cart__footer',
						'property' => 'padding-top',
					),
				)
			)
		),
		
		// cart_content_border
		'cart_content_border_et-desktop'                              => array(
			'name'      => 'cart_content_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'cart_content_border_et-desktop',
			'label'     => esc_html__( 'Mini-cart Border style', 'xstore-core' ),
			'section'   => 'cart',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart.et_element-top-level .et-mini-content, .et_b_mobile-panel-cart .et-mini-content',
					'property' => 'border-style',
				),
			),
		),
		
		// cart_content_border_color_custom
		'cart_content_border_color_custom_et-desktop'                 => array(
			'name'        => 'cart_content_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'cart_content_border_color_custom_et-desktop',
			'label'       => esc_html__( 'Mini-cart Border color', 'xstore-core' ),
			'description' => $strings['description']['border_color'],
			'section'     => 'cart',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-cart .et-mini-content, .et_b_header-cart .cart-widget-products, .et_b_header-cart.et-off-canvas .product_list_widget li:not(:last-child), .et_b_mobile-panel-cart .et-mini-content, .et_b_mobile-panel-cart .cart-widget-products, .et_b_mobile-panel-cart.et-off-canvas .product_list_widget li:not(:last-child)',
					'property' => 'border-color',
				),
			),
		),
		
		// advanced separator
		'cart_advanced_separator'                                     => array(
			'name'     => 'cart_advanced_separator',
			'type'     => 'custom',
			'settings' => 'cart_advanced_separator',
			'section'  => 'cart',
			'default'  => $separators['advanced'],
			'priority' => 10,
		),
		
		// cart_footer_content
		'cart_footer_content_et-desktop'                              => array(
			'name'            => 'cart_footer_content_et-desktop',
			'type'            => 'editor',
			'settings'        => 'cart_footer_content_et-desktop',
			'label'           => esc_html__( 'Mini-cart promo message', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'cart',
			'default'         => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.448 7.248h-3.24v-1.032c0-0.528-0.432-0.96-0.96-0.96h-11.784c-0.528 0-0.96 0.432-0.96 0.96v2.304h-3.048c0 0 0 0 0 0-0.192 0-0.384 0.096-0.48 0.264l-1.56 2.736h-0.864c-0.312 0-0.552 0.24-0.552 0.552v4.416c0 0.288 0.24 0.552 0.552 0.552h1.032c0.264 1.032 1.176 1.728 2.208 1.728 0.144 0 0.288-0.024 0.432-0.048 0.888-0.168 1.584-0.816 1.8-1.68h1.032c0.048 0 0.12-0.024 0.168-0.024 0.072 0.024 0.168 0.024 0.24 0.024h5.040c0.288 1.176 1.44 1.92 2.64 1.68 0.888-0.168 1.584-0.816 1.8-1.68h2.328c0.528 0 0.96-0.432 0.96-0.96v-3.48h2.4c0.312 0 0.552-0.24 0.552-0.552s-0.24-0.552-0.552-0.552h-2.4v-1.032h0.288c0.312 0 0.552-0.24 0.552-0.552s-0.24-0.552-0.552-0.552h-0.288v-1.032h3.24c0.312 0 0.552-0.24 0.552-0.552-0.024-0.288-0.264-0.528-0.576-0.528zM16.848 7.8c0 0.312 0.24 0.552 0.552 0.552h1.728v1.032h-4.68c-0.312 0-0.552 0.24-0.552 0.552s0.24 0.552 0.552 0.552h4.656v1.032h-2.568c-0.144 0-0.288 0.048-0.384 0.168-0.096 0.096-0.168 0.24-0.168 0.384 0 0.312 0.24 0.552 0.552 0.552h2.544v3.312h-2.16c-0.144-0.552-0.456-1.008-0.936-1.344-0.504-0.336-1.104-0.48-1.704-0.36-0.888 0.168-1.584 0.816-1.8 1.68l-4.92-0.024 0.024-9.552 11.496 0.024v0.888h-1.728c-0.264 0-0.504 0.24-0.504 0.552zM14.712 15.288c0.648 0 1.2 0.528 1.2 1.2 0 0.648-0.528 1.2-1.2 1.2-0.648 0-1.2-0.528-1.2-1.2 0.024-0.672 0.552-1.2 1.2-1.2zM3.792 15.288c0.648 0 1.2 0.528 1.2 1.2 0 0.648-0.528 1.2-1.2 1.2s-1.2-0.528-1.2-1.2c0.024-0.672 0.552-1.2 1.2-1.2zM6.48 12.6v3.312h-0.48c-0.144-0.552-0.456-1.008-0.936-1.344-0.504-0.336-1.104-0.48-1.704-0.36-0.888 0.168-1.584 0.816-1.8 1.68h-0.48v-3.288h5.4zM6.48 9.624v1.896h-3.792l1.080-1.872h2.712z"></path></svg>' . esc_html__( 'Free shipping over 49$', 'xstore-core' ),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_footer_content_et-desktop' => array(
					'selector'        => '.woocommerce-mini-cart__footer',
					'render_callback' => function () {
						if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
							WPBMap::addAllMappedShortcodes();
						}
						$content = get_theme_mod( 'cart_footer_content_et-desktop', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.448 7.248h-3.24v-1.032c0-0.528-0.432-0.96-0.96-0.96h-11.784c-0.528 0-0.96 0.432-0.96 0.96v2.304h-3.048c0 0 0 0 0 0-0.192 0-0.384 0.096-0.48 0.264l-1.56 2.736h-0.864c-0.312 0-0.552 0.24-0.552 0.552v4.416c0 0.288 0.24 0.552 0.552 0.552h1.032c0.264 1.032 1.176 1.728 2.208 1.728 0.144 0 0.288-0.024 0.432-0.048 0.888-0.168 1.584-0.816 1.8-1.68h1.032c0.048 0 0.12-0.024 0.168-0.024 0.072 0.024 0.168 0.024 0.24 0.024h5.040c0.288 1.176 1.44 1.92 2.64 1.68 0.888-0.168 1.584-0.816 1.8-1.68h2.328c0.528 0 0.96-0.432 0.96-0.96v-3.48h2.4c0.312 0 0.552-0.24 0.552-0.552s-0.24-0.552-0.552-0.552h-2.4v-1.032h0.288c0.312 0 0.552-0.24 0.552-0.552s-0.24-0.552-0.552-0.552h-0.288v-1.032h3.24c0.312 0 0.552-0.24 0.552-0.552-0.024-0.288-0.264-0.528-0.576-0.528zM16.848 7.8c0 0.312 0.24 0.552 0.552 0.552h1.728v1.032h-4.68c-0.312 0-0.552 0.24-0.552 0.552s0.24 0.552 0.552 0.552h4.656v1.032h-2.568c-0.144 0-0.288 0.048-0.384 0.168-0.096 0.096-0.168 0.24-0.168 0.384 0 0.312 0.24 0.552 0.552 0.552h2.544v3.312h-2.16c-0.144-0.552-0.456-1.008-0.936-1.344-0.504-0.336-1.104-0.48-1.704-0.36-0.888 0.168-1.584 0.816-1.8 1.68l-4.92-0.024 0.024-9.552 11.496 0.024v0.888h-1.728c-0.264 0-0.504 0.24-0.504 0.552zM14.712 15.288c0.648 0 1.2 0.528 1.2 1.2 0 0.648-0.528 1.2-1.2 1.2-0.648 0-1.2-0.528-1.2-1.2 0.024-0.672 0.552-1.2 1.2-1.2zM3.792 15.288c0.648 0 1.2 0.528 1.2 1.2 0 0.648-0.528 1.2-1.2 1.2s-1.2-0.528-1.2-1.2c0.024-0.672 0.552-1.2 1.2-1.2zM6.48 12.6v3.312h-0.48c-0.144-0.552-0.456-1.008-0.936-1.344-0.504-0.336-1.104-0.48-1.704-0.36-0.888 0.168-1.584 0.816-1.8 1.68h-0.48v-3.288h5.4zM6.48 9.624v1.896h-3.792l1.080-1.872h2.712z"></path></svg>' . esc_html__( 'Free shipping over 49$', 'xstore-core' ) );
						
						return do_shortcode( $content );
					},
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.woocommerce-mini-cart__footer',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => ''
				),
				array(
					'element'  => '.woocommerce-mini-cart__footer',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => ''
				),
			),
		),
		
		// cart_footer_background_custom
		'cart_footer_background_custom_et-desktop'                    => array(
			'name'        => 'cart_footer_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'cart_footer_background_custom_et-desktop',
			'label'       => esc_html__( 'WCAG Mini-cart promo message Control', 'xstore-core' ),
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'cart',
			'default'     => '#f5f5f5',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-mini-cart__footer',
					'property' => 'background-color'
				)
			)
		),
		
		// cart_footer_color
		'cart_footer_color_et-desktop'                                => array(
			'name'        => 'cart_footer_color_et-desktop',
			'settings'    => 'cart_footer_color_et-desktop',
			'label'       => esc_html__( 'WCAG Mini-cart promo message Color', 'xstore-core' ),
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'cart',
			'default'     => '#555555',
			'choices'     => array(
				'setting' => 'setting(cart)(cart_footer_background_custom_et-desktop)',
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
					'element'  => '.woocommerce-mini-cart__footer',
					'property' => 'color'
				)
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );
