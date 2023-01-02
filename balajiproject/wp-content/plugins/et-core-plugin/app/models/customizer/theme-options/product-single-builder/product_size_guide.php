<?php
/**
 * The template created for displaying product size guide options
 *
 * @version 1.0.2
 * @since   1.5
 * last changes in 2.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_size_guide' => array(
			'name'       => 'product_size_guide',
			'title'      => esc_html__( 'Sizing guide', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-image-crop',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/product_size_guide', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {
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
		'product_size_guide_content_separator'       => array(
			'name'     => 'product_size_guide_content_separator',
			'type'     => 'custom',
			'settings' => 'product_size_guide_content_separator',
			'section'  => 'product_size_guide',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// product_size_guide_icon
		'product_size_guide_icon_et-desktop'         => array(
			'name'            => 'product_size_guide_icon_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'product_size_guide_icon_et-desktop',
			'label'           => $strings['label']['icon'],
			'section'         => 'product_size_guide',
			'default'         => 'type1',
			'choices'         => array(
				'type1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/sizing-guide/Sizing-guide-1.svg',
				'type2'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/sizing-guide/Sizing-guide-2.svg',
				'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.single-product-size-guide .et-icon',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => 'none'
				),
				array(
					'element'  => '.single-product-size-guide .et-icon',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => 'none'
				),
			),
			'partial_refresh' => array(
				'product_size_guide_icon_et-desktop' => array(
					'selector'        => '.single-product-size-guide .et-icon',
					'render_callback' => function () {
						$type  = get_theme_mod( 'product_size_guide_icon_et-desktop', 'type1' );
						$icons = array(
							'type1'  => '<svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100"  xml:space="preserve"><path d="M100,27.7c0-0.3-0.2-0.6-0.4-0.8c-0.2-0.3-0.3-0.3-0.5-0.5c-0.3-0.3-1.2-0.6-1.9-0.6H2.1c-1.4,0-2.1,1.1-2.1,2.1v43.9
							c0,0.5,0.2,0.9,0.4,1.2c0.1,0.1,0.2,0.3,0.2,0.4l0.1,0.1c0.3,0.3,1,0.7,1.7,0.7h95.1c1.4,0,2.4-1.1,2.4-2.4v-44V27.7z M18.4,41.1
							c1.4,0,2.1-1.1,2.1-2.1v-8.3h11.1V46c0,1.5,1.1,2.4,2.1,2.4c1,0,2.1-1,2.1-2.4V30.7h11.1V39c0,1.3,1,2.1,2.4,2.1
							c1.5,0,2.4-1.1,2.4-2.1v-8.3h11.1V46c0,1.4,1.1,2.4,2.4,2.4c1.4,0,2.4-1.1,2.4-2.4V30.7h11.1V39c0,1.3,1,2.1,2.4,2.1
							c1.5,0,2.4-1.1,2.4-2.1v-8.3h11.7V70H4.3V30.7H16V39C16,40,16.9,41.1,18.4,41.1z"/></svg>',
							'type2'  => '<svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve"><g><path d="M49.5,37.7c0-6.5-6.7-11.6-15.3-11.6s-15.3,5.1-15.3,11.6s6.7,11.6,15.3,11.6S49.5,44.2,49.5,37.7z M44.9,37.7
							c0,3.8-4.9,7-10.7,7s-10.7-3.2-10.7-7c0-3.8,4.9-7,10.7-7S44.9,33.9,44.9,37.7z"/><path d="M97.7,58.3H68.4V37.7c0-13.9-15.3-25.2-34.2-25.2C15.3,12.5,0,23.8,0,37.7v24.6c0,12.5,12.8,23.2,29.9,24.9
							c0.6,0,1.2,0.1,1.9,0.2c0.8,0.1,1.5,0.2,2.4,0.2h63.5c1.2,0,2.3-1.1,2.3-2.3V60.6C100,59.1,98.8,58.3,97.7,58.3z M4.6,37.7
							c-0.1-4.1,1.6-8.2,4.9-11.6c5.5-5.7,14.8-9.1,24.7-9.1c16.3,0,29.6,9.3,29.6,20.6S50.5,58.3,34.2,58.3S4.6,49.1,4.6,37.7z
							M63.8,50.2v8.1h-10C57.6,56.5,61.1,53.7,63.8,50.2z M30.2,75.3c-1.2,0-2.3,1.1-2.3,2.3v4.6c-13.6-2.1-23-10.3-23-19.9V50.2
							c6,7.8,17.3,12.7,29.6,12.7h61.2v20H92v-10c0-1.2-1.1-2.3-2.3-2.3c-1.2,0-2.3,1.1-2.3,2.3v10h-7.4v-5.4c0-1.2-1.1-2.3-2.3-2.3
							c-1.2,0-2.3,1.1-2.3,2.3v5.4h-7.4v-5.4c0-1.2-1.1-2.3-2.3-2.3c-1.2,0-2.3,1.1-2.3,2.3v5.4h-7.4v-10c0-1.2-1.1-2.3-2.3-2.3
							s-2.3,1.1-2.3,2.3v10h-7.1v-5.4c0-1.2-1.1-2.3-2.3-2.3s-2.3,1.1-2.3,2.3v5.4h-7.4v-5.4C32.5,76.4,31.4,75.3,30.2,75.3z"/></g></svg>',
							'custom' => get_theme_mod( 'product_size_guide_icon_custom_et-desktop' ),
							'none'   => ''
						);
						
						return $icons[ $type ];
					}
				)
			)
		),
		
		// product_size_guide_icon_custom
		'product_size_guide_icon_custom_et-desktop'  => array(
			'name'            => 'product_size_guide_icon_custom_et-desktop',
			'type'            => 'code',
			'settings'        => 'product_size_guide_icon_custom_et-desktop',
			'label'           => $strings['label']['custom_icon_svg'],
			'section'         => 'product_size_guide',
			'default'         => '',
			'choices'         => array(
				'language' => 'html'
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.single-product-size-guide .et-icon',
					'function' => 'html',
				),
			),
		),
		
		// product_size_guide_label_show
		'product_size_guide_label_show_et-desktop'   => array(
			'name'      => 'product_size_guide_label_show_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'product_size_guide_label_show_et-desktop',
			'label'     => esc_html__( 'Show label', 'xstore-core' ),
			'section'   => 'product_size_guide',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-product-size-guide .et-element-label',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
				array(
					'element'  => '.single-product-size-guide .et-element-label',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
		),
		
		// product_size_guide_label
		'product_size_guide_label_et-desktop'        => array(
			'name'            => 'product_size_guide_label_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'product_size_guide_label_et-desktop',
			'section'         => 'product_size_guide',
			'default'         => esc_html__( 'Sizing guide', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_label_show_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.single-product-size-guide .et-element-label',
					'function' => 'html',
				),
			),
		),
		
		// content separator
		'product_size_guide_content_popup_separator' => array(
			'name'     => 'product_size_guide_content_popup_separator',
			'type'     => 'custom',
			'settings' => 'product_size_guide_content_popup_separator',
			'section'  => 'product_size_guide',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-left: 3px;">' . esc_html__( 'Popup content', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		'product_size_guide_type_et-desktop'                        => array(
			'name'     => 'product_size_guide_type_et-desktop',
			'type'     => 'radio-buttonset',
			'settings' => 'product_size_guide_type_et-desktop',
			'label'    => esc_html__( 'Type', 'xstore-core' ),
			'section'  => 'product_size_guide',
			'default'  => 'popup',
			'multiple' => 1,
			'choices'  => array(
				'popup'           => esc_html__( 'Popup', 'xstore-core' ),
				'download_button' => esc_html__( 'Download Button', 'xstore-core' ),
			),
		),
		
		// product_size_guide_content_alignment
		'product_size_guide_content_alignment_et-desktop'           => array(
			'name'            => 'product_size_guide_content_alignment_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'product_size_guide_content_alignment_et-desktop',
			'label'           => $strings['label']['alignment'],
			'section'         => 'product_size_guide',
			'default'         => 'start',
			'choices'         => $choices['alignment'],
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.size-guide-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.size-guide-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.size-guide-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'align-end',
					'value'    => 'end'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		// product_size_guide_content_width_height
		'product_size_guide_content_width_height_et-desktop'        => array(
			'name'            => 'product_size_guide_content_width_height_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'product_size_guide_content_width_height_et-desktop',
			'label'           => esc_html__( 'Popup width and height', 'xstore-core' ),
			'section'         => 'product_size_guide',
			'default'         => 'auto',
			'multiple'        => 1,
			'choices'         => array(
				'auto'   => esc_html__( 'Auto', 'xstore-core' ),
				'custom' => esc_html__( 'Custom', 'xstore-core' ),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.size-guide-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'et-popup-content-custom-dimenstions',
					'value'    => 'custom'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		// product_size_guide_content_width_height_custom
		'product_size_guide_content_width_height_custom_et-desktop' => array(
			'name'            => 'product_size_guide_content_width_height_custom_et-desktop',
			'type'            => 'dimensions',
			'settings'        => 'product_size_guide_content_width_height_custom_et-desktop',
			'section'         => 'product_size_guide',
			'default'         => array(
				'width'  => '550px',
				'height' => '250px',
			),
			'choices'         => array(
				'labels' => array(
					'width'  => esc_html__( 'Width (for custom only)', 'xstore-core' ),
					'height' => esc_html__( 'Height (for custom only)', 'xstore-core' ),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_size_guide_content_width_height_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'custom',
			// 	),
			// ),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'choice'   => 'width',
					'element'  => '.size-guide-popup .et-popup-content-custom-dimenstions',
					'property' => 'width',
				),
				array(
					'choice'   => 'height',
					'element'  => '.size-guide-popup .et-popup-content-custom-dimenstions',
					'property' => 'height',
				)
			),
		),
		
		// product_size_guide_title
		'product_size_guide_title_et-desktop'                       => array(
			'name'            => 'product_size_guide_title_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'product_size_guide_title_et-desktop',
			'label'           => esc_html__( 'Title', 'xstore-core' ),
			'section'         => 'product_size_guide',
			'default'         => esc_html__( 'Title', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_sections_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'product_size_guide_title_et-desktop' => array(
					'selector'        => '.size-guide-popup .et-popup-content',
					'render_callback' => function () {
						return product_size_guide_content_callback( $_POST['id'] );
					}
				)
			),
		),
		
		// product_size_guide_img
		'product_size_guide_img_et-desktop'                         => array(
			'name'            => 'product_size_guide_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'product_size_guide_img_et-desktop',
			'label'           => esc_html__( 'Image', 'xstore-core' ),
			'description'     => esc_html__( 'Upload size guide image.', 'xstore-core' ),
			'section'         => 'product_size_guide',
			'default'         => 'https://xstore.8theme.com/wp-content/uploads/2018/08/Size-guide.jpg',
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_sections_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'product_size_guide_img_et-desktop' => array(
					'selector'        => '.size-guide-popup .et-popup-content',
					'render_callback' => function () {
						return product_size_guide_content_callback( $_POST['id'] );
					}
				)
			)
		),
		
		// product_size_guide_sections
		'product_size_guide_sections_et-desktop'                    => array(
			'name'            => 'product_size_guide_sections_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_size_guide_sections_et-desktop',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'product_size_guide',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'product_size_guide_sections_et-desktop' => array(
					'selector'        => '.size-guide-popup .et-popup-content',
					'render_callback' => function () {
						return product_size_guide_content_callback( $_POST['id'] );
					}
				)
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		// product_size_guide_section
		'product_size_guide_section_et-desktop'                     => array(
			'name'            => 'product_size_guide_section_et-desktop',
			'type'            => 'select',
			'settings'        => 'product_size_guide_section_et-desktop',
			'label'           => sprintf( esc_html__( 'Choose %1s', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'product_size_guide',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_sections_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'product_size_guide_section_et-desktop' => array(
					'selector'        => '.size-guide-popup .et-popup-content',
					'render_callback' => function () {
						return product_size_guide_content_callback( $_POST['id'] );
					}
				)
			)
		),
		
		'product_size_guide_file_et-desktop'             => array(
			'name'            => 'product_size_guide_file_et-desktop',
			'type'            => 'upload',
			'settings'        => 'product_size_guide_file_et-desktop',
			'label'           => esc_html__( 'File', 'xstore-core' ),
			'description'     => esc_html__( 'Upload size guide file.', 'xstore-core' ),
			'section'         => 'product_size_guide',
			'active_callback' => array(
				array(
					'setting'  => 'product_size_guide_type_et-desktop',
					'operator' => '==',
					'value'    => 'download_button',
				),
			),
		),
		
		// style separator
		'product_size_guide_style_separator'             => array(
			'name'     => 'product_size_guide_style_separator',
			'type'     => 'custom',
			'settings' => 'product_size_guide_style_separator',
			'section'  => 'product_size_guide',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// product_size_guide_align
		'product_size_guide_align_et-desktop'            => array(
			'name'      => 'product_size_guide_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_size_guide_align_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'product_size_guide',
			'default'   => 'inherit',
			'choices'   => $choices['alignment_with_inherit'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-product-size-guide',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.single-product-size-guide',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.single-product-size-guide',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
				array(
					'element'  => '.single-product-size-guide',
					'function' => 'toggleClass',
					'class'    => 'justify-content-inherit',
					'value'    => 'inherit'
				),
			),
		),
		
		// product_size_guide_label_proportion
		'product_size_guide_label_proportion_et-desktop' => array(
			'name'      => 'product_size_guide_label_proportion_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_size_guide_label_proportion_et-desktop',
			'label'     => $strings['label']['size_proportion'],
			'section'   => 'product_size_guide',
			'default'   => 1,
			'choices'   => array(
				'min'  => '0',
				'max'  => '5',
				'step' => '.01',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'element'  => 'body',
					'property' => '--single-product-size-guide-proportion',
				),
			),
		),
		
		// product_size_guide_space_between
		'product_size_guide_space_between_et-mobile'     => array(
			'name'      => 'product_size_guide_space_between_et-mobile',
			'type'      => 'slider',
			'settings'  => 'product_size_guide_space_between_et-mobile',
			'label'     => esc_html__( 'Space between (px)', 'xstore-core' ),
			'section'   => 'product_size_guide',
			'default'   => 10,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'element'       => '.single-product-size-guide',
					'property'      => 'padding',
					'value_pattern' => '$px 0'
				),
			),
		),
		
		'product_size_guide_content_style_separator' => array(
			'name'     => 'product_size_guide_content_style_separator',
			'type'     => 'custom',
			'settings' => 'product_size_guide_content_style_separator',
			'section'  => 'product_size_guide',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-left: 3px;">' . esc_html__( 'Popup styles', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		'product_size_guide_popup_box_model_et-desktop'           => array(
			'name'        => 'product_size_guide_popup_box_model_et-desktop',
			'settings'    => 'product_size_guide_popup_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_size_guide',
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
					'element' => '.size-guide-popup .et-popup-content',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.size-guide-popup .et-popup-content' )
		),
		
		// product_size_guide_border
		'product_size_guide_popup_border_et-desktop'              => array(
			'name'      => 'product_size_guide_popup_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_size_guide_popup_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_size_guide',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'element'  => '.size-guide-popup .et-popup-content',
					'property' => 'border-style'
				),
			),
		),
		
		// product_size_guide_border_color_custom
		'product_size_guide_popup_border_color_custom_et-desktop' => array(
			'name'      => 'product_size_guide_popup_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_size_guide_popup_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_size_guide',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'element'  => '.size-guide-popup .et-popup-content',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	unset($sections);
	
	return array_merge( $fields, $args );
	
} );
