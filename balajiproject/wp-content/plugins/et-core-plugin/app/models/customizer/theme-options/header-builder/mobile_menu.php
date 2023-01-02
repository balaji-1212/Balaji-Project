<?php
/**
 * The template created for displaying header mobile menu element options
 *
 * @version 1.0.6
 * @since   1.4.0
 * last changes in 2.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'mobile_menu' => array(
			'name'       => 'mobile_menu',
			'title'      => esc_html__( 'Mobile menu', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-menu',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/mobile_menu', function ( $fields ) use ( $separators, $strings, $choices, $sep_style, $box_models ) {
	$menus = et_b_get_terms( 'nav_menu' );
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'mobile_menu_content_separator'                      => array(
			'name'     => 'mobile_menu_content_separator',
			'type'     => 'custom',
			'settings' => 'mobile_menu_content_separator',
			'section'  => 'mobile_menu',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// mobile_menu_off_canvas
		'mobile_menu_type_et-desktop'                        => array(
			'name'     => 'mobile_menu_type_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'mobile_menu_type_et-desktop',
			'label'    => esc_html__( 'Off-canvas type', 'xstore-core' ),
			'section'  => 'mobile_menu',
			'default'  => 'off_canvas_left',
			'choices'  => array(
				'off_canvas_left'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/mobile_menu/Style-mobile-menu-1.svg',
				'popup'            => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/mobile_menu/Style-mobile-menu-2.svg',
				'off_canvas_right' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/mobile_menu/Style-mobile-menu-3.svg',
			),
			// need time to improve and check more
			// 'transport' => 'postMessage',
			// 'partial_refresh' => array(
			// 	'mobile_menu_type_et-desktop' => array(
			// 		'selector'  => '.et_b_header-mobile-menu',
			// 		'render_callback' => 'mobile_menu_callback'
			// 	),
			// ),
			// 'js_vars'     => array(
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu',
			// 		'function' => 'toggleClass',
			// 		'class' => 'et-content-left',
			// 		'value' => 'off_canvas_left'
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu',
			// 		'function' => 'toggleClass',
			// 		'class' => 'et-content-right',
			// 		'value' => 'off_canvas_right'
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu .et-mini-content .et-close',
			// 		'function' => 'toggleClass',
			// 		'class' => 'full-right',
			// 		'value' => 'off_canvas_left'
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu .et-mini-content .et-close',
			// 		'function' => 'toggleClass',
			// 		'class' => 'full-left',
			// 		'value' => 'off_canvas_right'
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu',
			// 		'function' => 'toggleClass',
			// 		'class' => 'et-content_toggle',
			// 		// 'value' => array('off_canvas_left', 'off_canvas_right') not working
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu',
			// 		'function' => 'toggleClass',
			// 		'class' => 'et-off-canvas',
			// 		// 'value' => array('off_canvas_left', 'off_canvas_right') not working
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper .et-element-label',
			// 		'function' => 'toggleClass',
			// 		'class' => 'et-toggle',
			// 		// 'value' => array('off_canvas_left', 'off_canvas_right') not working
			// 	),
			// 	array(
			// 		'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper .et-element-label',
			// 		'function' => 'toggleClass',
			// 		'class' => 'et-popup_toggle',
			// 		'value' => 'popup'
			// 	),
			// ),
		),
		
		// mobile_menu_icon
		'mobile_menu_icon_et-desktop'                        => array(
			'name'            => 'mobile_menu_icon_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'mobile_menu_icon_et-desktop',
			'label'           => $strings['label']['icon'],
			'section'         => 'mobile_menu',
			'default'         => 'icon1',
			'choices'         => array(
				'icon1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/mobile_menu/Icon-1.svg',
				'icon2'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/mobile_menu/Icon-2.svg',
				'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'icon1' => array(
					'selector'        => '.et_b_header-mobile-menu .et-element-label-wrapper .et-element-label .et_b-icon',
					'render_callback' => function () {
						$type = get_theme_mod( 'mobile_menu_icon_et-desktop', 'type1' );
						if ( $type == 'custom' && get_theme_mod( 'mobile_menu_icon_custom_et-mobile', '' ) != '' ) {
							return get_post_meta( get_theme_mod( 'mobile_menu_icon_custom_et-mobile', '' ), '_xstore_inline_svg', true );
						}
						switch ( $type ) {
							case 'icon1':
								$img = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M0.792 5.904h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744zM23.208 11.256h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744zM23.208 18.096h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744z"></path></svg>';
								break;
							case 'icon2':
								$img = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1em" height="1em" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g><path d="M26,37h47.7c1.7,0,3.3-1.3,3.3-3.3s-1.3-3.3-3.3-3.3H26c-1.7,0-3.3,1.3-3.3,3.3S24.3,37,26,37z"/><path d="M74,46.7H26c-1.7,0-3.3,1.3-3.3,3.3s1.3,3.3,3.3,3.3h47.7c1.7,0,3.3-1.3,3.3-3.3S75.7,46.7,74,46.7z"/><path d="M74,63H26c-1.7,0-3.3,1.3-3.3,3.3s1.3,3.3,3.3,3.3h47.7c1.7,0,3.3-1.3,3.3-3.3S75.7,63,74,63z"/></g><path d="M50,0C22.3,0,0,22.3,0,50s22.3,50,50,50s50-22.3,50-50S77.7,0,50,0z M50,93.7C26,93.7,6.3,74,6.3,50S26,6.3,50,6.3S93.7,26,93.7,50S74,93.7,50,93.7z"/></svg>';
								break;
							default;
						}
						
						return $img;
					},
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper .et-element-label .et_b-icon',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => 'none'
				),
			),
		),
		
		// mobile_menu_icon_custom_svg
		'mobile_menu_icon_custom_svg_et-desktop'             => array(
			'name'            => 'mobile_menu_icon_custom_svg_et-desktop',
			'type'            => 'image',
			'settings'        => 'mobile_menu_icon_custom_svg_et-desktop',
			'label'           => $strings['label']['custom_image_svg'],
			'description'     => $strings['description']['custom_image_svg'],
			'section'         => 'mobile_menu',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_icon_custom_svg_et-desktop' => array(
					'selector'        => '.et_b_header-mobile-menu',
					'render_callback' => 'mobile_menu_callback'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			)
		
		),
		
		// mobile_menu_icon_zoom
		'mobile_menu_icon_zoom_et-desktop'                   => array(
			'name'            => 'mobile_menu_icon_zoom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_icon_zoom_et-desktop',
			'label'           => esc_html__( 'Icon zoom (proportion)', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => 1,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.7',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_icon_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu > span svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu > span svg',
					'property' => 'height',
					'units'    => 'em'
				)
			)
		),
		
		// mobile_menu_icon_zoom
		'mobile_menu_icon_zoom_et-mobile'                    => array(
			'name'            => 'mobile_menu_icon_zoom_et-mobile',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_icon_zoom_et-mobile',
			'label'           => $strings['label']['icons_zoom'],
			'section'         => 'mobile_menu',
			'default'         => 1,
			'choices'         => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_icon_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-mobile-menu > span svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-mobile-menu > span svg',
					'property' => 'height',
					'units'    => 'em'
				)
			)
		),
		
		// mobile_menu_label
		'mobile_menu_label_et-desktop'                       => array(
			'name'      => 'mobile_menu_label_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'mobile_menu_label_et-desktop',
			'label'     => esc_html__( 'Show label', 'xstore-core' ),
			'section'   => 'mobile_menu',
			'default'   => '0',
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper .et-element-label > span:not(.et_b-icon)',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => false
				),
			),
		),
		
		// mobile_menu_text
		'mobile_menu_text'                                   => array(
			'name'            => 'mobile_menu_text',
			'type'            => 'etheme-text',
			'settings'        => 'mobile_menu_text',
			'section'         => 'mobile_menu',
			'default'         => esc_html__( 'Menu', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_label_et-desktop',
					'operator' => '==',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper .et-element-label > span:not(.et_b-icon)',
					'function' => 'html',
				),
			),
		),
		
		// elements separator
		'mobile_menu_elements_separator'                     => array(
			'name'     => 'mobile_menu_elements_separator',
			'type'     => 'custom',
			'settings' => 'mobile_menu_elements_separator',
			'section'  => 'mobile_menu',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-list-view"></span> <span style="padding-left: 3px;">' . esc_html__( 'Elements', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		// mobile_menu_content
		'mobile_menu_content'                                => array(
			'name'            => 'mobile_menu_content',
			'type'            => 'sortable',
			'label'           => $strings['label']['elements'],
			'description'     => esc_html__( 'On/Off elements you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'priority'        => 10,
			'settings'        => 'mobile_menu_content',
			'default'         => array(
				'logo',
				'search',
				'menu',
				'header_socials'
			),
			'choices'         => array(
				'logo'           => esc_html__( 'Logo', 'xstore-core' ),
				'search'         => esc_html__( 'Search', 'xstore-core' ),
				'menu'           => esc_html__( 'Menu', 'xstore-core' ),
				'wishlist'       => esc_html__( 'Wishlist', 'xstore-core' ),
				'cart'           => esc_html__( 'Cart', 'xstore-core' ),
				'account'        => esc_html__( 'Account', 'xstore-core' ),
				'header_socials' => esc_html__( 'Socials', 'xstore-core' ),
				'button'         => esc_html__( 'Button', 'xstore-core' ),
				'html_block1'    => esc_html__( 'HTML Block 01', 'xstore-core' ),
				'html_block2'    => esc_html__( 'HTML Block 02', 'xstore-core' ),
				'html_block3'    => esc_html__( 'HTML Block 03', 'xstore-core' ),
				'html_block4'    => esc_html__( 'HTML Block 04', 'xstore-core' ),
				'html_block5'    => esc_html__( 'HTML Block 05', 'xstore-core' ),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_content' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_content
		'mobile_menu_2'                                      => array(
			'name'            => 'mobile_menu_2',
			'type'            => 'select',
			'settings'        => 'mobile_menu_2',
			'label'           => esc_html__( 'Extra tab content', 'xstore-core' ),
			'description'     => esc_html__( 'Displays the product categories', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => 'none',
			'choices'         => array(
				'none'       => esc_html__( 'None', 'xstore-core' ),
				'categories' => esc_html__( 'Categories', 'xstore-core' ),
				'menu'       => esc_html__( 'Menu', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_2' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		
		// mobile_menu_categories_hide_empty
		'mobile_menu_2_categories_hide_empty'                => array(
			'name'            => 'mobile_menu_2_categories_hide_empty',
			'type'            => 'toggle',
			'settings'        => 'mobile_menu_2_categories_hide_empty',
			'label'           => esc_html__( 'Hide empty categories', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
				array(
					'setting'  => 'mobile_menu_2',
					'operator' => '==',
					'value'    => 'categories'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_categories_hide_empty' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_categories_primary
		'mobile_menu_2_categories_primary'                   => array(
			'name'            => 'mobile_menu_2_categories_primary',
			'type'            => 'toggle',
			'settings'        => 'mobile_menu_2_categories_primary',
			'label'           => esc_html__( 'Make categories menu as primary', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
				array(
					'setting'  => 'mobile_menu_2',
					'operator' => '==',
					'value'    => 'categories'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_2_categories_primary' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_2_term
		'mobile_menu_2_term'                                 => array(
			'name'            => 'mobile_menu_2_term',
			'type'            => 'select',
			'settings'        => 'mobile_menu_2_term',
			'label'           => $strings['label']['select_menu_extra'],
			'section'         => 'mobile_menu',
			'choices'         => $menus,
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
				array(
					'setting'  => 'mobile_menu_2',
					'operator' => '==',
					'value'    => 'menu'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_2_term' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_tab_2_text
		'mobile_menu_tab_2_text'                             => array(
			'name'            => 'mobile_menu_tab_2_text',
			'type'            => 'etheme-text',
			'settings'        => 'mobile_menu_tab_2_text',
			'label'           => esc_html__( 'Extra tab title', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => esc_html__( 'Categories', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
				array(
					'setting'  => 'mobile_menu_2',
					'operator' => '!=',
					'value'    => 'none'
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.mobile-menu-content .et_b-tabs .et-tab[data-tab="menu_2"]',
					'function' => 'html',
				),
			),
		),
		
		// mobile_menu_item_click
		'mobile_menu_item_click_et-desktop'                  => array(
			'name'            => 'mobile_menu_item_click_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'mobile_menu_item_click_et-desktop',
			'label'           => esc_html__( 'Dropdown opening action', 'xstore-core' ),
			'description'     => esc_html__( 'Open dropdown only on arrow click. Check on real devices, please.', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_item_click_et-desktop' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_term
		'mobile_menu_term'                                   => array(
			'name'            => 'mobile_menu_term',
			'type'            => 'select',
			'settings'        => 'mobile_menu_term',
			'label'           => $strings['label']['select_menu'],
			'section'         => 'mobile_menu',
			'choices'         => $menus,
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_term' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_one_page
		'mobile_menu_one_page'                               => array(
			'name'            => 'mobile_menu_one_page',
			'type'            => 'toggle',
			'settings'        => 'mobile_menu_one_page',
			'label'           => esc_html__( 'One page menu', 'xstore-core' ),
			'description'     => esc_html__( 'Enable when your menu is working only for one page by anchors', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'menu'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_one_page' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// mobile_menu_logo_type
		'mobile_menu_logo_type_et-desktop'                   => array(
			'name'            => 'mobile_menu_logo_type_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'mobile_menu_logo_type_et-desktop',
			'label'           => esc_html__( 'Logo', 'xstore-core' ),
			'description'     => sprintf( esc_html__( 'Choose the header logo from %s main %s or %s sticky %s header ', 'xstore-core' ),
				'<span class="et_edit" data-parent="logo" data-section="logo_content_separator" data-option="#customize-control-logo_img" style="color: #222;">', '</span>',
				'<span class="et_edit" data-parent="header_sticky" data-section="header_sticky_content_separator" data-option="#customize-control-headers_sticky_logo_img" style="color: #222;">', '</span>' ),
			'section'         => 'mobile_menu',
			'default'         => 'simple',
			'multiple'        => 1,
			'choices'         => array(
				'simple' => esc_html__( 'Main header', 'xstore-core' ),
				'sticky' => esc_html__( 'Sticky header', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'logo'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_menu_logo_type_et-desktop' => array(
					'selector'        => '.mobile-menu-content',
					'render_callback' => 'mobile_menu_content_callback'
				),
			),
		),
		
		// logo_width
		'mobile_menu_logo_width_et-desktop'                  => array(
			'name'            => 'mobile_menu_logo_width_et-desktop',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_logo_width_et-desktop',
			'label'           => esc_html__( 'Logo width (px)', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => 200,
			'choices'         => array(
				'min'  => '10',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_content',
					'operator' => 'in',
					'value'    => 'logo'
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-menu-content .et_b_header-logo img',
					'property' => 'width',
					'units'    => 'px'
				)
			)
		),
		
		// style separator
		'mobile_menu_style_separator'                        => array(
			'name'     => 'mobile_menu_style_separator',
			'type'     => 'custom',
			'settings' => 'mobile_menu_style_separator',
			'section'  => 'mobile_menu',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// mobile_menu_content_alignment
		'mobile_menu_content_alignment_et-desktop'           => array(
			'name'      => 'mobile_menu_content_alignment_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'mobile_menu_content_alignment_et-desktop',
			'label'     => esc_html__( 'Alignment of the menu icon', 'xstore-core' ),
			'section'   => 'mobile_menu',
			'default'   => 'start',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// mobile_menu_content_alignment
		'mobile_menu_content_alignment_et-mobile'            => array(
			'name'      => 'mobile_menu_content_alignment_et-mobile',
			'type'      => 'radio-buttonset',
			'settings'  => 'mobile_menu_content_alignment_et-mobile',
			'label'     => esc_html__( 'Alignment of the menu icon', 'xstore-core' ),
			'section'   => 'mobile_menu',
			'default'   => 'start',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.et_b_header-mobile-menu .et-element-label-wrapper',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// mobile_menu_box_model
		'mobile_menu_box_model_et-desktop'                   => array(
			'name'        => 'mobile_menu_box_model_et-desktop',
			'settings'    => 'mobile_menu_box_model_et-desktop',
			'label'       => esc_html__( 'Computed box of the menu icon', 'xstore-core' ),
			'description' => esc_html__( 'You can select the margin, border-width and padding for menu icon element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'mobile_menu',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle'
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle' )
		),
		
		// mobile_menu_box_model
		'mobile_menu_box_model_et-mobile'                    => array(
			'name'        => 'mobile_menu_box_model_et-mobile',
			'settings'    => 'mobile_menu_box_model_et-mobile',
			'label'       => esc_html__( 'Computed box of the menu icon', 'xstore-core' ),
			'description' => esc_html__( 'You can select the margin, border-width and padding for menu icon element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'mobile_menu',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .mobile-header-wrapper .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle'
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.mobile-header-wrapper .et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .mobile-header-wrapper .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle' )
		),
		
		// mobile_menu_border
		'mobile_menu_border_et-desktop'                      => array(
			'name'      => 'mobile_menu_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'mobile_menu_border_et-desktop',
			'label'     => esc_html__( 'Border style of the menu icon', 'xstore-core' ),
			'section'   => 'mobile_menu',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle',
					'property' => 'border-style'
				)
			),
		),
		
		// mobile_menu_border_color_custom
		'mobile_menu_border_color_custom_et-desktop'         => array(
			'name'        => 'mobile_menu_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'mobile_menu_border_color_custom_et-desktop',
			'label'       => esc_html__( 'Border color of the menu icon', 'xstore-core' ),
			'description' => $strings['description']['border_color'],
			'section'     => 'mobile_menu',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle',
					'property' => 'border-color',
				)
			),
		),
		
		// mobile_border_radius
		'mobile_border_radius_et-desktop'                    => array(
			'name'      => 'mobile_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'mobile_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'mobile_menu',
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
					'element'  => '.et_b_header-mobile-menu > .et-element-label-wrapper .et-toggle, .et_b_header-mobile-menu > .et-element-label-wrapper .et-popup_toggle',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		
		// separator content
		'mobile_menu_dropdown_separator'                     => array(
			'name'            => 'mobile_menu_dropdown_separator',
			'type'            => 'custom',
			'settings'        => 'mobile_menu_dropdown_separator',
			'section'         => 'mobile_menu',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-outdent"></span> <span style="padding-left: 3px;">' . esc_html__( 'Off-Canvas content', 'xstore-core' ) . '</span></div>',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup'
				),
			),
		),
		
		// separator content
		'mobile_menu_popup_separator'                        => array(
			'name'            => 'mobile_menu_popup_separator',
			'type'            => 'custom',
			'settings'        => 'mobile_menu_popup_separator',
			'section'         => 'mobile_menu',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-external"></span> <span style="padding-left: 3px;">' . esc_html__( 'Popup content', 'xstore-core' ) . '</span></div>',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup'
				),
			),
		),
		
		// mobile_menu_content_fonts
		'mobile_menu_content_fonts_et-desktop'               => array(
			'name'      => 'mobile_menu_content_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'mobile_menu_content_fonts_et-desktop',
			'section'   => 'mobile_menu',
			'default'   => array(
				'font-family'    => '',
				'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'inherit',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-menu-content',
				),
			),
		),
		
		// mobile_menu_zoom
		'mobile_menu_zoom_dropdown_et-desktop'               => array(
			'name'            => 'mobile_menu_zoom_dropdown_et-desktop',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_zoom_dropdown_et-desktop',
			'label'           => $strings['label']['content_size'],
			'section'         => 'mobile_menu',
			'default'         => 100,
			'choices'         => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup'
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-mobile-menu > .et-mini-content',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// mobile_menu_zoom_popup
		'mobile_menu_zoom_popup_et-desktop'                  => array(
			'name'            => 'mobile_menu_zoom_popup_et-desktop',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_zoom_popup_et-desktop',
			'label'           => $strings['label']['content_size'],
			'section'         => 'mobile_menu',
			'default'         => 100,
			'choices'         => array(
				'min'  => '0',
				'max'  => '200',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-menu-popup',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// mobile_menu_zoom_popup
		'mobile_menu_items_space_popup_et-desktop'           => array(
			'name'            => 'mobile_menu_items_space_popup_et-desktop',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_items_space_popup_et-desktop',
			'label'           => esc_html__( 'Items space (px)', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => 10,
			'choices'         => array(
				'min'  => '0',
				'max'  => '50',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-menu-content > .et_element:not(:last-child),
						.et-mobile-tabs-wrapper:not(:last-child), 
						.mobile-menu-content .et_b_header-contacts .contact:not(:last-child),
						.mobile-menu-content .et_b_header-button',
					'property'      => 'margin-bottom',
					'value_pattern' => 'calc(2 * $px)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-menu-content > .et_element > .menu-main-container,
						.et-mobile-tab-content',
					'property'      => 'margin-top',
					'value_pattern' => '-$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-menu-content > .et_element > .menu-main-container,
						.et-mobile-tab-content',
					'property'      => 'margin-bottom',
					'value_pattern' => '-$px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'div.mobile-menu-content .et_b_header-menu .menu li a, .et-mobile-tab-content .widget .cat-item a',
					'property' => 'padding-top',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'div.mobile-menu-content .et_b_header-menu .menu li a, .et-mobile-tab-content .widget .cat-item a',
					'property' => 'padding-bottom',
					'units'    => 'px'
				)
			),
		),
		
		// mobile_menu_overlay
		'mobile_menu_overlay_et-desktop'                     => array(
			'name'            => 'mobile_menu_overlay_et-desktop',
			'settings'        => 'mobile_menu_overlay_et-desktop',
			'label'           => esc_html__( 'Overlay background', 'xstore-core' ),
			'description'     => esc_html__( 'Select a background color for your content.', 'xstore-core' ),
			'type'            => 'color',
			'section'         => 'mobile_menu',
			'default'         => 'rgba(0,0,0,.3)',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-popup-wrapper.mobile-menu-popup:before',
					'property' => 'background-color'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-menu-popup .et-close-popup',
					'property' => 'color'
				)
			)
		),
		
		// mobile_menu_background_color
		'mobile_menu_background_color_et-desktop'            => array(
			'name'            => 'mobile_menu_background_color_et-desktop',
			'settings'        => 'mobile_menu_background_color_et-desktop',
			'label'           => esc_html__( 'Off-Canvas content WCAG Control (background)', 'xstore-core' ),
			'description'     => $strings['description']['wcag_bg_color'],
			'type'            => 'color',
			'section'         => 'mobile_menu',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu .et-mini-content',
					'property' => 'background-color'
				),
				// array(
				// 'context'	=> array('editor', 'front'),
				// 	'element' => '.et_b_header-mobile-menu .et-mini-content > .et-toggle',
				// 	'property' => 'color'
				// )
			),
		),
		
		// mobile_menu_color
		'mobile_menu_color_et-desktop'                       => array(
			'name'            => 'mobile_menu_color_et-desktop',
			'settings'        => 'mobile_menu_color_et-desktop',
			'label'           => esc_html__( 'Off-Canvas content WCAG Color', 'xstore-core' ),
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'mobile_menu',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(mobile_menu)(mobile_menu_background_color_et-desktop)',
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
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-menu-content',
					'property' => 'color'
				),
				// array(
				// 'context'	=> array('editor', 'front'),
				// 	'element' => '.et_b_header-mobile-menu .et-mini-content > .et-toggle',
				// 	'property' => 'background-color'
				// )
			),
		),
		
		// mobile_menu_color2
		'mobile_menu_color2_et-desktop'                      => array(
			'name'            => 'mobile_menu_color2_et-desktop',
			'settings'        => 'mobile_menu_color2_et-desktop',
			'label'           => esc_html__( 'Popup content WCAG Color', 'xstore-core' ),
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'mobile_menu',
			'default'         => '#ffffff',
			'choices'         => array(
				'setting' => 'setting(mobile_menu)(mobile_menu_overlay_et-desktop)',
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
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-menu-content',
					'property' => 'color'
				),
				// array(
				// 'context'	=> array('editor', 'front'),
				// 	'element' => '.mobile-menu-popup .et-close-popup',
				// 	'property' => 'background-color'
				// ),
			),
		),
		
		// mobile_menu_max_height
		'mobile_menu_max_height_et-desktop'                  => array(
			'name'            => 'mobile_menu_max_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'mobile_menu_max_height_et-desktop',
			'label'           => esc_html__( 'Max height (%)', 'xstore-core' ),
			'section'         => 'mobile_menu',
			'default'         => 70,
			'choices'         => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_menu_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element'  => '.mobile-menu-popup .et-popup-content',
					'property' => 'max-height',
					'units'    => 'vh'
				),
			),
		),
		
		// mobile_menu_content_box_model
		'mobile_menu_content_box_model_et-desktop'           => array(
			'name'        => 'mobile_menu_content_box_model_et-desktop',
			'settings'    => 'mobile_menu_content_box_model_et-desktop',
			'label'       => esc_html__( 'Off-Canvas content Computed box', 'xstore-core' ),
			'description' => esc_html__( 'You can select the margin, border-width and padding for off-canvas content element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'mobile_menu',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '',
				'margin-bottom'       => '0px',
				'margin-left'         => '',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '10px',
				'padding-right'       => '20px',
				'padding-bottom'      => '10px',
				'padding-left'        => '20px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-mobile-menu > .et-mini-content,
						.mobile-menu-popup .et-popup-content',
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_header-mobile-menu > .et-mini-content,
					.mobile-menu-popup .et-popup-content' )
		),
		
		// mobile_menu_content_border
		'mobile_menu_content_border_et-desktop'              => array(
			'name'      => 'mobile_menu_content_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'mobile_menu_content_border_et-desktop',
			'label'     => esc_html__( 'Off-Canvas/Popup content Border style', 'xstore-core' ),
			'section'   => 'mobile_menu',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu > .et-mini-content,
						.mobile-menu-popup .et-popup-content',
					'property' => 'border-style'
				)
			),
		),
		
		// mobile_menu_content_border_color_custom
		'mobile_menu_content_border_color_custom_et-desktop' => array(
			'name'        => 'mobile_menu_content_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'mobile_menu_content_border_color_custom_et-desktop',
			'label'       => esc_html__( 'Off-Canvas/Popup content Border color', 'xstore-core' ),
			'description' => $strings['description']['border_color'],
			'section'     => 'mobile_menu',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-mobile-menu > .et-mini-content,
						.mobile-menu-popup .et-popup-content',
					'property' => 'border-color',
				)
			),
		),
	
	);
	
	unset($menus);
	
	return array_merge( $fields, $args );
	
} );
