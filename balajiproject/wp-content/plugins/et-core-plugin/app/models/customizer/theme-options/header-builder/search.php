<?php
/**
 * The template created for displaying header search element options
 *
 * @version 1.0.8
 * @since   1.4.0
 * last changes in 4.0.9
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'search' => array(
			'name'       => 'search',
			'title'      => esc_html__( 'Search', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-search',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/search', function ( $fields ) use ( $separators, $strings, $choices, $sep_style, $sidebars_with_inherit, $box_models ) {
	$sections = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	);
	
	$product_categories = et_b_get_terms( 'product_cat' );
	
	$args = array();
	// Array of fields
	$args = array(
		
		// content separator
		'search_content_separator'           => array(
			'name'     => 'search_content_separator',
			'type'     => 'custom',
			'settings' => 'search_content_separator',
			'section'  => 'search',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// search_type
		'search_type_et-desktop'             => array(
			'name'     => 'search_type_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'search_type_et-desktop',
			'label'    => $strings['label']['type'],
			'section'  => 'search',
			'default'  => 'input',
			'multiple' => 1,
			'choices'  => array(
				'icon'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Style-search-icon-1.svg',
				'input' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Style-search-icon-2.svg',
				'popup' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Style-search-icon-3.svg',
			),
		),
		
		// search_type
		'search_type_et-mobile'              => array(
			'name'     => 'search_type_et-mobile',
			'type'     => 'radio-image',
			'settings' => 'search_type_et-mobile',
			'label'    => $strings['label']['type'],
			'section'  => 'search',
			'default'  => 'icon',
			'multiple' => 1,
			'choices'  => array(
				'icon'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Style-search-icon-1.svg',
				'input' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Style-search-icon-2.svg',
				'popup' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Style-search-icon-3.svg',
			),
		),
		
		// search_icon
		'search_icon_et-desktop'             => array(
			'name'        => 'search_icon_et-desktop',
			'type'        => 'radio-image',
			'settings'    => 'search_icon_et-desktop',
			'label'       => $strings['label']['icon'],
			'description' => $strings['description']['icons_style'],
			'section'     => 'search',
			'default'     => 'type1',
			'choices'     => array(
				'type1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/search/Search.svg',
				'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
			),
		),
		
		// search_icon_custom
		'search_icon_custom_et-desktop'      => array(
			'name'            => 'search_icon_custom_et-desktop',
			'type'            => 'image',
			'settings'        => 'search_icon_custom_et-desktop',
			'label'           => $strings['label']['custom_image_svg'],
			'description'     => $strings['description']['custom_image_svg'],
			'section'         => 'search',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			)
		),
		
		// search_label
		'search_label_et-desktop'            => array(
			'name'            => 'search_label_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_label_et-desktop',
			'label'           => $strings['label']['show_title'],
			'section'         => 'search',
			'default'         => false,
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-search .et_b_search-icon > .et-element-label',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'input',
				),
			),
		),
		
		// search_label
		'search_label_et-mobile'             => array(
			'name'            => 'search_label_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'search_label_et-mobile',
			'label'           => $strings['label']['show_title'],
			'section'         => 'search',
			'default'         => 0,
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-search .et_b_search-icon > .et-element-label',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'input',
				),
			),
		),
		
		// search_label_custom
		'search_label_custom_et-desktop'     => array(
			'name'            => 'search_label_custom_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'search_label_custom_et-desktop',
			'section'         => 'search',
			'default'         => esc_html__( 'Search', 'xstore-core' ),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-search .et_b_search-icon > .et-element-label',
					'function' => 'html',
				),
			),
			'active_callback' => array(
				array(
					array(
						'setting'  => 'search_type_et-desktop',
						'operator' => '!=',
						'value'    => 'input',
					),
					array(
						'setting'  => 'search_type_et-mobile',
						'operator' => '!=',
						'value'    => 'input',
					),
				),
			),
		),
		
		// search_ajax
		'search_ajax_et-desktop'             => array(
			'name'     => 'search_ajax_et-desktop',
			'type'     => 'toggle',
			'settings' => 'search_ajax_et-desktop',
			'label'    => esc_html__( 'Ajax search', 'xstore-core' ),
			'section'  => 'search',
			'default'  => '1',
		),

		// search_ajax_history
		'search_ajax_history_et-desktop'             => array(
			'name'     => 'search_ajax_history_et-desktop',
			'type'     => 'toggle',
			'settings' => 'search_ajax_history_et-desktop',
			'label'    => esc_html__( 'Ajax search history', 'xstore-core' ),
			'description' => esc_html__( 'Add user search requests history. It will add additional cookie named: "et_search_history", purpose: "Store user search requests", expiry"5 days by default". Don\'t forget to mention it in the security policy (GDPR)', 'xstore-core' ),
			'section'  => 'search',
			'default'  => '0',
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),

		// search_ajax_history time
		'search_ajax_history_time-desktop' => array(
			'name'            => 'search_ajax_history_time-desktop',
			'type'            => 'slider',
			'settings'        => 'search_ajax_history_time-desktop',
			'label'           => esc_html__( 'How long save cookies', 'xstore-core' ),
			'description'     => esc_html__( 'Time during which the cookie will be stored. Don\'t forget to mention it in the security policy (GDPR).', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 5,
			'choices'         => array(
				'min'  => '1',
				'max'  => '30',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_ajax_history_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// search_ajax_history title
		'search_ajax_history_title_et-desktop' => array(
			'name'      => 'search_ajax_history_title_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'search_ajax_history_title_et-desktop',
			'label'     => esc_html__( '"Search history:" text', 'xstore-core' ),
			'section'   => 'search',
			'default'   => esc_html__( 'Search history:', 'xstore-core' ),
			'priority'  => 10,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-search .et_history-title',
					'function' => 'html',
					'attr'     => 'placeholder',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_ajax_history_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// search_ajax_clear_history_title
//		'search_ajax_clear_history_title_et-desktop' => array(
//			'name'      => 'search_ajax_clear_history_title_et-desktop',
//			'type'      => 'etheme-text',
//			'settings'  => 'search_ajax_clear_history_title_et-desktop',
//			'label'     => esc_html__( '"Clear History:" text', 'xstore-core' ),
//			'section'   => 'search',
//			'default'   => esc_html__( 'clear history', 'xstore-core' ),
//			'priority'  => 10,
//			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.et_b_header-search .et_clear-history',
//					'function' => 'html',
//					'attr'     => 'placeholder',
//				),
//			),
//			'active_callback' => array(
//				array(
//					'setting'  => 'search_ajax_history_et-desktop',
//					'operator' => '==',
//					'value'    => 1,
//				),
//			),
//		),

		// search_ajax_history length-
		'search_ajax_history_length-desktop' => array(
			'name'            => 'search_ajax_history_length-desktop',
			'type'            => 'slider',
			'settings'        => 'search_ajax_history_length-desktop',
			'label'           => esc_html__( 'How many history requests to show?', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 7,
			'choices'         => array(
				'min'  => '1',
				'max'  => '20',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_ajax_history_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		//! end of ajax search history

		// search_ajax_min_symbols
		'search_ajax_min_symbols_et-desktop' => array(
			'name'            => 'search_ajax_min_symbols_et-desktop',
			'type'            => 'slider',
			'settings'        => 'search_ajax_min_symbols_et-desktop',
			'label'           => esc_html__( 'Do Ajax Search after ... characters', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 3,
			'choices'         => array(
				'min'  => '2',
				'max'  => '10',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_ajax_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// search_ajax_posts_per_page
		'search_ajax_posts_per_page'         => array(
			'name'            => 'search_ajax_posts_per_page',
			'type'            => 'slider',
			'settings'        => 'search_ajax_posts_per_page',
			'label'           => esc_html__( 'Search results limit', 'xstore-core' ),
			'description'     => esc_html__( 'Useful to limit the number of results if you have thousands of posts/products. By default 100. Use -1 to display unlimited results.', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 100,
			'choices'         => array(
				'min'  => '-1',
				'max'  => '1000',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_ajax_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// search_placeholder
		'search_placeholder_et-desktop'      => array(
			'name'      => 'search_placeholder_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'search_placeholder_et-desktop',
			'label'     => esc_html__( 'Placeholder text', 'xstore-core' ),
			'section'   => 'search',
			'default'   => esc_html__( 'Search for...', 'xstore-core' ),
			'priority'  => 10,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-search .input-row input[type="text"]',
					'function' => 'html',
					'attr'     => 'placeholder',
				),
			)
		),
		
		// search_tags
		'search_tags_et-desktop'             => array(
			'name'            => 'search_tags_et-desktop',
			'type'            => 'etheme-textarea',
			'settings'        => 'search_tags_et-desktop',
			'label'           => esc_html__( 'Trending searches', 'xstore-core' ),
			'description'     => esc_html__( 'Please, write values with comma separator', 'xstore-core' ),
			'section'         => 'search',
			'default'         => esc_html__( 'Shirt, Shoes, Cap, Shoes, Skirt', 'xstore-core' ),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		'search_extra_content_et-desktop'                 => array(
			'name'            => 'search_extra_content_et-desktop',
			'type'            => 'select',
			'settings'        => 'search_extra_content_et-desktop',
			'label'           => esc_html__( 'Search Extra content', 'xstore-core' ),
			'section'         => 'search',
			'priority'        => 10,
			'multiple'        => 1,
			'choices'         => array(
				'product_categories' => esc_html__( 'Product categories', 'xstore-core' ),
				'custom_html'        => esc_html__( 'Custom html', 'xstore-core' ),
				'staticblock'        => esc_html__( 'Staticblock', 'xstore-core' ),
				'none'               => esc_html__( 'No content', 'xstore-core' ),
			),
			'default'         => 'product_categories',
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		// search_categories
		'search_categories_et-desktop'                    => array(
			'name'            => 'search_categories_et-desktop',
			'type'            => 'select',
			'settings'        => 'search_categories_et-desktop',
			'label'           => esc_html__( 'Product categories', 'xstore-core' ),
			'section'         => 'search',
			'placeholder'     => esc_html__( 'Select product categories...', 'xstore-core' ),
			'priority'        => 10,
			'multiple'        => 6,
			'choices'         => $product_categories,
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
				array(
					'setting'  => 'search_extra_content_et-desktop',
					'operator' => '==',
					'value'    => 'product_categories',
				),
			),
		),
		
		// html_block1
		'search_html_block_et-desktop'                    => array(
			'name'            => 'search_html_block_et-desktop',
			'type'            => 'editor',
			'settings'        => 'search_html_block_et-desktop',
			'label'           => esc_html__( 'HTML block', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'search',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
				array(
					'setting'  => 'search_extra_content_et-desktop',
					'operator' => '==',
					'value'    => 'custom_html',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'search_html_block_et-desktop' => array(
					'selector'        => '.ajax-search-form .ajax-extra-content',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'html_backup' => 'search_html_block_et-desktop',
						) );
					},
				),
			),
		),
		
		// html_block1_section
		'search_staticblock'                              => array(
			'name'            => 'search_staticblock',
			'type'            => 'select',
			'settings'        => 'search_staticblock',
			'label'           => sprintf( esc_html__( 'Choose %1s for search extra content', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'search',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '==',
					'value'    => 'popup',
				),
				array(
					'setting'  => 'search_extra_content_et-desktop',
					'operator' => '==',
					'value'    => 'staticblock',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'search_staticblock' => array(
					'selector'        => '.ajax-search-form .ajax-extra-content',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'search_staticblock',
							'force_sections'  => true,
							'section_content' => true
						) );
					},
				),
			),
		),
		
		// content separator
		'search_results_content_separator'                => array(
			'name'     => 'search_results_content_separator',
			'type'     => 'custom',
			'settings' => 'search_results_content_separator',
			'section'  => 'search',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Search results', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		// search_results_content
		'search_results_content_et-desktop'               => array(
			'name'        => 'search_results_content_et-desktop',
			'type'        => 'sortable',
			'label'       => esc_html__( 'Results sorting', 'xstore-core' ),
			'description' => esc_html__( 'On/Off post types you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'     => 'search',
			'settings'    => 'search_results_content_et-desktop',
			'default'     => array(
				'products',
				'posts',
			),
			'choices'     => array(
				'products'  => esc_html__( 'Products', 'xstore-core' ),
				'posts'     => esc_html__( 'Posts', 'xstore-core' ),
				'pages'     => esc_html__( 'Pages', 'xstore-core' ),
				'portfolio' => esc_html__( 'Portfolio', 'xstore-core' ),
			),
		),
		
		// search_by_sku
		'search_by_sku_et-desktop'                        => array(
			'name'            => 'search_by_sku_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_by_sku_et-desktop',
			'label'           => esc_html__( 'Search by SKU', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => 'products'
				),
			),
		),
		
		// search_category
		'search_category_et-desktop'                      => array(
			'name'            => 'search_category_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_category_et-desktop',
			'label'           => esc_html__( 'Search by category', 'xstore-core' ),
			'section'         => 'search',
			'default'         => '1',
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => array( 'posts', 'products' )
				),
			),
		),
		
		// search product variations
		'search_product_variation_et-desktop'             => array(
			'name'            => 'search_product_variation_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_product_variation_et-desktop',
			'label'           => esc_html__( 'Search product variations', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => 'products'
				),
			),
		),
		
		// search product variations
		'search_product_hide_variation_parent_et-desktop' => array(
			'name'            => 'search_product_hide_variation_parent_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_product_hide_variation_parent_et-desktop',
			'label'           => esc_html__( 'Hide parent product of variations', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => 'products'
				),
				array(
					'setting'  => 'search_product_variation_et-desktop',
					'operator' => '==',
					'value'    => '1'
				),
			),
		),
		
		// search_category_fancy_select
		'search_category_fancy_select_et-desktop'         => array(
			'name'            => 'search_category_fancy_select_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_category_fancy_select_et-desktop',
			'label'           => esc_html__( 'Fancy Select dropdown', 'xstore-core' ),
			'section'         => 'search',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => array( 'posts', 'products' )
				),
				array(
					'setting'  => 'search_category_et-desktop',
					'operator' => '==',
					'value'    => '1'
				)
			),
		),
		
		// search_category_select_color_scheme
		'search_category_select_color_scheme_et-desktop'  => array(
			'name'            => 'search_category_select_color_scheme_et-desktop',
			'type'            => 'select',
			'settings'        => 'search_category_select_color_scheme_et-desktop',
			'label'           => esc_html__( 'Search dropdown color scheme', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 'dark',
			'multiple'        => 1,
			'choices'         => array(
				'dark'  => esc_html__( 'Dark', 'xstore-core' ),
				'white' => esc_html__( 'White', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => array( 'posts', 'products' )
				),
				array(
					'setting'  => 'search_category_et-desktop',
					'operator' => '==',
					'value'    => '1'
				)
			),
		),
		
		// search_ajax_with_tabs
		'search_ajax_with_tabs_et-desktop'                => array(
			'name'            => 'search_ajax_with_tabs_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'search_ajax_with_tabs_et-desktop',
			'label'           => esc_html__( 'Display with tabs', 'xstore-core' ),
			'section'         => 'search',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'search_ajax_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
		),
		
		// search_all_categories_text
		'search_all_categories_text_et-desktop'           => array(
			'name'            => 'search_all_categories_text_et-desktop',
			'type'            => 'etheme-text',
			'settings'        => 'search_all_categories_text_et-desktop',
			'label'           => esc_html__( 'All categories text', 'xstore-core' ),
			'section'         => 'search',
			'default'         => esc_html__( 'All categories', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'search_results_content_et-desktop',
					'operator' => 'in',
					'value'    => array( 'posts', 'products' )
				),
				array(
					'setting'  => 'search_category_et-desktop',
					'operator' => '==',
					'value'    => '1'
				)
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-search .input-row select option:first-child',
					'function' => 'html',
				),
			)
		),
		
		// search_page_sidebar
		'search_page_sidebar_et-desktop'                  => array(
			'name'        => 'search_page_sidebar_et-desktop',
			'type'        => 'radio-image',
			'settings'    => 'search_page_sidebar_et-desktop',
			'label'       => esc_html__( 'Sidebar position', 'xstore-core' ),
			'description' => esc_html__( 'Choose the position of the sidebar for the search results page.', 'xstore-core' ),
			'section'     => 'search',
			'default'     => 'without',
			'choices'     => $sidebars_with_inherit,
		),
		
		// search_page_custom_area_position
		'search_page_custom_area_position_et-desktop'     => array(
			'name'        => 'search_page_custom_area_position_et-desktop',
			'type'        => 'select',
			'settings'    => 'search_page_custom_area_position_et-desktop',
			'label'       => esc_html__( 'Custom area position on page', 'xstore-core' ),
			'description' => esc_html__( 'Choose the position of the custom area for the search results page.', 'xstore-core' ),
			'section'     => 'search',
			'default'     => 'none',
			'choices'     => array(
				'none'   => esc_html__( 'None', 'xstore-core' ),
				'before' => esc_html__( 'Before results', 'xstore-core' ),
				'after'  => esc_html__( 'After results', 'xstore-core' )
			),
		),
		
		// search_page_custom_area
		'search_page_custom_area'                         => array(
			'name'            => 'search_page_custom_area',
			'type'            => 'editor',
			'settings'        => 'search_page_custom_area',
			'label'           => esc_html__( 'Custom area content', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'search',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'search_page_custom_area_position_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
				array(
					'setting'  => 'search_page_sections',
					'operator' => '!=',
					'value'    => 1,
				),
			),
		),
		
		// search_page_sections
		'search_page_sections'                            => array(
			'name'            => 'search_page_sections',
			'type'            => 'toggle',
			'settings'        => 'search_page_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'search',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'search_page_custom_area_position_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
		
		// search_page_section
		'search_page_section'                             => array(
			'name'            => 'search_page_section',
			'type'            => 'select',
			'settings'        => 'search_page_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Search page custom area', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'search',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'search_page_custom_area_position_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
				array(
					'setting'  => 'search_page_sections',
					'operator' => '==',
					'value'    => 1,
				),
			)
		),
		
		// style separator
		'search_style_separator'                          => array(
			'name'     => 'search_style_separator',
			'type'     => 'custom',
			'settings' => 'search_style_separator',
			'section'  => 'search',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// search_icon_zoom
		'search_icon_zoom_et-desktop'                     => array(
			'name'        => 'search_icon_zoom_et-desktop',
			'type'        => 'slider',
			'settings'    => 'search_icon_zoom_et-desktop',
			'label'       => $strings['label']['icons_zoom'],
			'description' => esc_html__( 'Only for search type icon', 'xstore-core' ),
			'section'     => 'search',
			'default'     => 1,
			'choices'     => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level > span svg, .et_b_header-search.et_element-top-level .search-button svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level > span img, .et_b_header-search.et_element-top-level .search-button img',
					'property' => 'max-width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level > span svg, .et_b_header-search.et_element-top-level .search-button svg',
					'property' => 'height',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level > span img, .et_b_header-search.et_element-top-level .search-button img',
					'property' => 'max-height',
					'units'    => 'em'
				),
			)
		),
		
		// search_icon_zoom
		'search_icon_zoom_et-mobile'                      => array(
			'name'      => 'search_icon_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'search_icon_zoom_et-mobile',
			'label'     => $strings['label']['icons_zoom'],
			'section'   => 'search',
			'default'   => 1,
			'choices'   => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level > span svg, .mobile-header-wrapper .et_b_header-search.et_element-top-level .search-button svg',
					'property' => 'width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level > span img, .mobile-header-wrapper .et_b_header-search.et_element-top-level .search-button img',
					'property' => 'max-width',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level > span svg, .mobile-header-wrapper .et_b_header-search.et_element-top-level .search-button svg',
					'property' => 'height',
					'units'    => 'em'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level > span img, .mobile-header-wrapper .et_b_header-search.et_element-top-level .search-button img',
					'property' => 'max-height',
					'units'    => 'em'
				),
			)
		),
		
		// search_content_alignment
		'search_content_alignment_et-desktop'             => array(
			'name'      => 'search_content_alignment_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'search_content_alignment_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'search',
			'default'   => 'center',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.header-wrapper .et_b_header-search.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-search.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-search.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// search_content_alignment
		'search_content_alignment_et-mobile'              => array(
			'name'      => 'search_content_alignment_et-mobile',
			'type'      => 'radio-buttonset',
			'settings'  => 'search_content_alignment_et-mobile',
			'label'     => $strings['label']['alignment'],
			'section'   => 'search',
			'default'   => 'center',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// search_width
		'search_width_et-desktop'                         => array(
			'name'            => 'search_width_et-desktop',
			'type'            => 'slider',
			'settings'        => 'search_width_et-desktop',
			'label'           => esc_html__( 'Width (%)', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 100,
			'choices'         => array(
				'min'  => '10',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .input-row',
					'property' => 'width',
					'units'    => '%'
				),
			),
		),
		
		// search_width
		'search_width_et-mobile'                          => array(
			'name'            => 'search_width_et-mobile',
			'type'            => 'slider',
			'settings'        => 'search_width_et-mobile',
			'label'           => esc_html__( 'Width (%)', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 100,
			'choices'         => array(
				'min'  => '10',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level .input-row',
					'property' => 'width',
					'units'    => '%'
				),
			),
		),
		
		// search_height
		'search_height_et-desktop'                        => array(
			'name'            => 'search_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'search_height_et-desktop',
			'label'           => esc_html__( 'Height (px)', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 40,
			'choices'         => array(
				'min'  => '10',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level input[type="text"], .et_b_header-search.et_element-top-level select, .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text, .et_b_header-search.et_element-top-level .search-button',
					'property' => 'height',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-search.et_element-top-level input[type="text"], .et_b_header-search.et_element-top-level select, .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text, .et_b_header-search.et_element-top-level .search-button',
					'property'      => 'line-height',
					'value_pattern' => 'calc($px / 2)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-search.et_element-top-level input[type="text"]',
					'property'      => 'max-width',
					'value_pattern' => 'calc(100% - $px)'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .search-button',
					'property' => 'width',
					'units'    => 'px'
				)
			),
		),
		
		'search_height_et-mobile'                    => array(
			'name'            => 'search_height_et-mobile',
			'type'            => 'slider',
			'settings'        => 'search_height_et-mobile',
			'label'           => esc_html__( 'Height (px)', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 40,
			'choices'         => array(
				'min'  => '10',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level input[type="text"],
						.mobile-header-wrapper .et_b_header-search.et_element-top-level select, .mobile-header-wrapper .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text, .mobile-header-wrapper  .et_b_header-search.et_element-top-level .search-button',
					'property' => 'height',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et_b_header-search.et_element-top-level input[type="text"],
						.mobile-header-wrapper .et_b_header-search.et_element-top-level select, .mobile-header-wrapper .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text, .mobile-header-wrapper  .et_b_header-search.et_element-top-level .search-button',
					'property'      => 'line-height',
					'value_pattern' => 'calc($px / 2)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et_b_header-search.et_element-top-level input[type="text"]',
					'property'      => 'max-width',
					'value_pattern' => 'calc(100% - $px)'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level .search-button',
					'property' => 'width',
					'units'    => 'px'
				)
			),
		),
		
		// search_border_radius
		'search_border_radius_et-desktop'            => array(
			'name'            => 'search_border_radius_et-desktop',
			'type'            => 'slider',
			'settings'        => 'search_border_radius_et-desktop',
			'label'           => $strings['label']['border_radius'],
			'section'         => 'search',
			'default'         => 0,
			'choices'         => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .input-row, .et_b_header-search.et_element-top-level .input-row .search-button',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
		),
		
		// search_border_radius
		'search_border_radius_et-mobile'             => array(
			'name'            => 'search_border_radius_et-mobile',
			'type'            => 'slider',
			'settings'        => 'search_border_radius_et-mobile',
			'label'           => $strings['label']['border_radius'],
			'section'         => 'search',
			'default'         => 0,
			'choices'         => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .et_b_header-search.et_element-top-level .input-row, .mobile-header-wrapper .et_b_header-search.et_element-top-level .input-row .search-button',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
		),
		
		// search_color
		'search_color_et-desktop'                    => array(
			'name'            => 'search_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'search_color_et-desktop',
			'label'           => esc_html__( 'Text color', 'xstore-core' ),
			'description'     => esc_html__( 'Background controls are pretty complex - but extremely useful if properly used.', 'xstore-core' ),
			'section'         => 'search',
			'default'         => '#888888',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level input[type="text"], .et_b_header-search.et_element-top-level input[type="text"]::-webkit-input-placeholder',
					'property' => 'color'
				),
			),
		),
		
		// search_background_color_custom
		'search_background_color_custom_et-desktop'  => array(
			'name'            => 'search_background_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'search_background_color_custom_et-desktop',
			'label'           => esc_html__( 'Input Background color', 'xstore-core' ),
			'description'     => esc_html__( 'Background controls are pretty complex - but extremely useful if properly used.', 'xstore-core' ),
			'section'         => 'search',
			'default'         => '#fff',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .input-row, .et_b_header-search.et_element-top-level input[type="text"]',
					'property' => 'background-color'
				),
			),
		),
		
		
		// search_button_background_custom
		'search_button_background_custom_et-desktop' => array(
			'name'            => 'search_button_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'search_button_background_custom_et-desktop',
			'label'           => esc_html__( 'Button background color', 'xstore-core' ),
			'section'         => 'search',
			'default'         => '#000000',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .search-button',
					'property' => 'background-color'
				),
			),
		),
		
		'search_button_color_et-desktop' => array(
			'name'            => 'search_button_color_et-desktop',
			'settings'        => 'search_button_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'search',
			'default'         => '#ffffff',
			'choices'         => array(
				'setting' => 'setting(search)(search_button_background_custom_et-desktop)',
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
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .search-button, .et_b_header-search.et_element-top-level .clear',
					'property' => 'color'
				)
			),
		),
		
		// content separator
		'search_input_separator'         => array(
			'name'            => 'search_input_separator',
			'type'            => 'custom',
			'settings'        => 'search_input_separator',
			'section'         => 'search',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-removeformatting"></span> <span style="padding-left: 3px;">' . esc_html__( 'Input boxes', 'xstore-core' ) . '</span></div>',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
		),
		
		'search_input_box_model_et-desktop'             => array(
			'name'            => 'search_input_box_model_et-desktop',
			'type'            => 'kirki-box-model',
			'settings'        => 'search_input_box_model_et-desktop',
			'label'           => esc_html__( 'Input Computed box', 'xstore-core' ),
			'section'         => 'search',
			'default'         => array(
				'margin-top'          => '0px',
				'margin-right'        => '',
				'margin-bottom'       => '0px',
				'margin-left'         => '',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '0px',
				'padding-right'       => '0px',
				'padding-bottom'      => '0px',
				'padding-left'        => '10px',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-search.et_element-top-level .input-row',
				),
				array(
					'choice'        => 'padding-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => 'body:not(.rtl) .et_b_header-search.et_element-top-level .buttons-wrapper',
					'property'      => 'right',
					'value_pattern' => '-$'
				),
				array(
					'choice'        => 'padding-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => 'body.rtl .et_b_header-search.et_element-top-level .buttons-wrapper',
					'property'      => 'left',
					'value_pattern' => '-$'
				),
				array(
					'choice'   => 'border-right-width',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body:not(.rtl) .et_b_header-search.et_element-top-level select, body:not(.rtl) .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
					'property' => 'border-right-width'
				),
				array(
					'choice'   => 'border-left-width',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body.rtl .et_b_header-search.et_element-top-level select, body.rtl .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
					'property' => 'border-left-width'
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.et_b_header-search.et_element-top-level .input-row' ),
				array(
					array(
						'choice'        => 'padding-right',
						'function'      => 'css',
						'element'       => 'body:not(.rtl) .et_b_header-search.et_element-top-level .buttons-wrapper',
						'property'      => 'right',
						'value_pattern' => '-$'
					),
					array(
						'choice'        => 'padding-left',
						'function'      => 'css',
						'element'       => 'body.rtl .et_b_header-search.et_element-top-level .buttons-wrapper',
						'property'      => 'left',
						'value_pattern' => '-$'
					),
					array(
						'choice'   => 'border-right-width',
						'function' => 'css',
						'element'  => 'body:not(.rtl) .et_b_header-search.et_element-top-level select, body:not(.rtl) .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
						'property' => 'border-right-width'
					),
					array(
						'choice'   => 'border-left-width',
						'function' => 'css',
						'element'  => 'body.rtl .et_b_header-search.et_element-top-level select, body.rtl .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
						'property' => 'border-left-width'
					),
				)
			)
		),
		
		
		// search_box_model
		'search_input_box_model_et-mobile'              => array(
			'name'            => 'search_input_box_model_et-mobile',
			'type'            => 'kirki-box-model',
			'settings'        => 'search_input_box_model_et-mobile',
			'label'           => esc_html__( 'Input Computed box', 'xstore-core' ),
			'section'         => 'search',
			'default'         => array(
				'margin-top'          => '0px',
				'margin-right'        => '',
				'margin-bottom'       => '0px',
				'margin-left'         => '',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '0px',
				'padding-right'       => '0px',
				'padding-bottom'      => '0px',
				'padding-left'        => '10px',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .et_b_header-search.et_element-top-level .input-row',
				),
				array(
					'choice'        => 'padding-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => 'body:not(.rtl) .mobile-header-wrapper .et_b_header-search.et_element-top-level .buttons-wrapper',
					'property'      => 'right',
					'value_pattern' => '-$'
				),
				array(
					'choice'        => 'padding-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => 'body.rtl .mobile-header-wrapper .et_b_header-search.et_element-top-level .buttons-wrapper',
					'property'      => 'left',
					'value_pattern' => '-$'
				),
				array(
					'choice'   => 'border-right-width',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body:not(.rtl) .mobile-header-wrapper .et_b_header-search.et_element-top-level select, body:not(.rtl) .mobile-header-wrapper .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
					'property' => 'border-right-width'
				),
				array(
					'choice'   => 'border-left-width',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body.rtl .mobile-header-wrapper .et_b_header-search.et_element-top-level select, body.rtl .mobile-header-wrapper .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
					'property' => 'border-left-width'
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.mobile-header-wrapper .et_b_header-search.et_element-top-level .input-row' ),
				array(
					array(
						'choice'        => 'padding-right',
						'function'      => 'css',
						'element'       => 'body:not(.rtl) .mobile-header-wrapper .et_b_header-search.et_element-top-level .buttons-wrapper',
						'property'      => 'right',
						'value_pattern' => '-$'
					),
					array(
						'choice'        => 'padding-left',
						'function'      => 'css',
						'element'       => 'body.rtl .mobile-header-wrapper .et_b_header-search.et_element-top-level .buttons-wrapper',
						'property'      => 'left',
						'value_pattern' => '-$'
					),
					array(
						'choice'   => 'border-right-width',
						'function' => 'css',
						'element'  => 'body:not(.rtl) .mobile-header-wrapper .et_b_header-search.et_element-top-level select, body:not(.rtl) .mobile-header-wrapper .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
						'property' => 'border-right-width'
					),
					array(
						'choice'   => 'border-left-width',
						'function' => 'css',
						'element'  => 'body.rtl .mobile-header-wrapper .et_b_header-search.et_element-top-level select, body.rtl .mobile-header-wrapper .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
						'property' => 'border-left-width'
					),
				)
			),
		),
		
		// search_border
		'search_input_border_et-desktop'                => array(
			'name'            => 'search_input_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'search_input_border_et-desktop',
			'label'           => esc_html__( 'Input Border style', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .input-row, .ajax-search-form input[type="text"]',
					'property' => 'border-style'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level select, .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
					'property' => 'border-style'
				),
			),
		),
		
		// search_border_color_custom
		'search_input_border_color_custom_et-desktop'   => array(
			'name'            => 'search_input_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'search_input_border_color_custom_et-desktop',
			'label'           => esc_html__( 'Input Border color', 'xstore-core' ),
			'description'     => $strings['description']['border_color'],
			'section'         => 'search',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .input-row, .ajax-search-form input[type="text"], .ajax-search-form input[type="text"]:focus',
					'property' => 'border-color',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level select, .et_b_header-search.et_element-top-level .fancy-select .fancy-placeholder-text',
					'property' => 'border-color'
				),
			),
		),
		
		// content separator
		'search_icon_separator'                         => array(
			'name'            => 'search_icon_separator',
			'type'            => 'custom',
			'settings'        => 'search_icon_separator',
			'section'         => 'search',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-code-standards"></span> <span style="padding-left: 3px;">' . esc_html__( 'Icon', 'xstore-core' ) . '</span></div>',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
		),
		
		// search_icon_box_model
		'search_icon_box_model_et-desktop'              => array(
			'name'            => 'search_icon_box_model_et-desktop',
			'type'            => 'kirki-box-model',
			'settings'        => 'search_icon_box_model_et-desktop',
			'label'           => esc_html__( 'Icon Computed box', 'xstore-core' ),
			'section'         => 'search',
			'default'         => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '10px',
				'padding-right'       => '0px',
				'padding-bottom'      => '10px',
				'padding-left'        => '0px',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_header-search.et_element-top-level .et_b_search-icon',
				),
				array(
					'choice'        => 'padding-bottom',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.ajax-search-form.input-icon:before',
					'property'      => 'top',
					'value_pattern' => 'calc(-$ - 3px)'
				),
				array(
					'choice'        => 'padding-bottom',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.ajax-search-form.input-icon:before',
					'property'      => 'height',
					'value_pattern' => 'calc($ + 3px)'
				)
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.et_b_header-search.et_element-top-level .et_b_search-icon' ),
				array(
					array(
						'choice'        => 'padding-bottom',
						'element'       => '.ajax-search-form.input-icon:before',
						'function'      => 'css',
						'property'      => 'top',
						'value_pattern' => 'calc(-$ - 3px)'
					),
					array(
						'choice'        => 'padding-bottom',
						'element'       => '.ajax-search-form.input-icon:before',
						'function'      => 'css',
						'property'      => 'height',
						'value_pattern' => 'calc($ + 3px)'
					)
				)
			),
		),
		
		// search_box_model
		'search_icon_box_model_et-mobile'               => array(
			'name'            => 'search_icon_box_model_et-mobile',
			'type'            => 'kirki-box-model',
			'settings'        => 'search_icon_box_model_et-mobile',
			'label'           => esc_html__( 'Icon Computed box', 'xstore-core' ),
			'section'         => 'search',
			'default'         => $box_models['empty'],
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .et_b_header-search.et_element-top-level .et_b_search-icon',
				)
			),
			'transport'       => 'postMessage',
			'js_vars'         => box_model_output( '.mobile-header-wrapper .et_b_header-search.et_element-top-level .et_b_search-icon' )
		),
		
		// search_border
		'search_icon_border_et-desktop'                 => array(
			'name'            => 'search_icon_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'search_icon_border_et-desktop',
			'label'           => esc_html__( 'Icon Border style', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .et_b_search-icon',
					'property' => 'border-style'
				)
			),
		),
		
		// search_border_color_custom
		'search_icon_border_color_custom_et-desktop'    => array(
			'name'            => 'search_icon_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'search_icon_border_color_custom_et-desktop',
			'label'           => esc_html__( 'Icon Border color', 'xstore-core' ),
			'description'     => $strings['description']['border_color'],
			'section'         => 'search',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level .et_b_search-icon',
					'property' => 'border-color',
				)
			),
		),
		
		// content separator
		'search_content_dropdown_separator'             => array(
			'name'     => 'search_content_dropdown_separator',
			'type'     => 'custom',
			'settings' => 'search_content_dropdown_separator',
			'section'  => 'search',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-images-alt"></span> <span style="padding-left: 3px;">' . esc_html__( 'Results Dropdown', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
//			'active_callback' => array(
//				array(
//					'setting'  => 'search_type_et-desktop',
//					'operator' => '!=',
//					'value'    => 'popup',
//				),
//				array(
//					'setting'  => 'search_type_et-mobile',
//					'operator' => '!=',
//					'value'    => 'popup',
//				),
//			),
		),
		
		// search_zoom
		'search_zoom_et-desktop'                        => array(
			'name'      => 'search_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'search_zoom_et-desktop',
			'label'     => $strings['label']['content_size'],
			'section'   => 'search',
			'default'   => 100,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
//			'active_callback' => array(
//				array(
//					'setting'  => 'search_type_et-desktop',
//					'operator' => '!=',
//					'value'    => 'popup',
//				),
//			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.ajax-search-form:not(.input-icon) .autocomplete-suggestions, .ajax-search-form.input-icon, .search-full-width .et-mini-content',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// search_zoom
		'search_zoom_et-mobile'                         => array(
			'name'      => 'search_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'search_zoom_et-mobile',
			'label'     => $strings['label']['content_size'],
			'section'   => 'search',
			'default'   => 100,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
//			'active_callback' => array(
//				array(
//					'setting'  => 'search_type_et-mobile',
//					'operator' => '!=',
//					'value'    => 'popup',
//				),
//			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .ajax-search-form:not(.input-icon) .autocomplete-suggestions, .mobile-header-wrapper .ajax-search-form.input-icon, .mobile-header-wrapper .search-full-width .et-mini-content',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// search_content_position
		'search_content_position_et-desktop'            => array(
			'name'            => 'search_content_position_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'search_content_position_et-desktop',
			'label'           => esc_html__( 'Results Dropdown Position', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 'right',
			'multiple'        => 1,
			'choices'         => $choices['dropdown_position'],
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.et_b_header-search',
					'function' => 'toggleClass',
					'class'    => 'et-content-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et_b_header-search',
					'function' => 'toggleClass',
					'class'    => 'et-content-left',
					'value'    => 'left'
				),
			),
		),
		
		// search_content_position
		'search_content_position_custom_et-desktop'     => array(
			'name'            => 'search_content_position_custom_et-desktop',
			'type'            => 'slider',
			'settings'        => 'search_content_position_custom_et-desktop',
			'label'           => esc_html__( 'Results Dropdown offset', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 0,
			'choices'         => array(
				'min'  => '-300',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_content_position_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_header-search.et_element-top-level.et-content-dropdown form.et-mini-content, .et_b_header-search.et_element-top-level.et-content-dropdown form:not(.et-mini-content) .ajax-results-wrapper',
					'property' => 'right',
					'units'    => 'px'
				),
			),
		),
		
		// search_content_box_model
		'search_content_box_model_et-desktop'           => array(
			'name'            => 'search_content_box_model_et-desktop',
			'type'            => 'kirki-box-model',
			'settings'        => 'search_content_box_model_et-desktop',
			'label'           => esc_html__( 'Results Dropdown Computed box', 'xstore-core' ),
			'section'         => 'search',
			'default'         => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '20px',
				'padding-right'       => '30px',
				'padding-bottom'      => '30px',
				'padding-left'        => '30px',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
				),
				array(
					'choice'        => 'padding-top',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.header-wrapper .et-content-dropdown .ajax-results-title:first-child',
					'property'      => 'margin-top',
					'value_pattern' => '-$'
				),
				array(
					'choice'        => 'padding-bottom',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.header-wrapper .et-content-dropdown .ajax-results-more:last-child',
					'property'      => 'margin-bottom',
					'value_pattern' => '-$'
				),
				array(
					'choice'        => 'padding-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
					'property'      => 'padding-left',
					'value_pattern' => '0px'
				),
				array(
					'choice'        => 'padding-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
					'property'      => 'padding-right',
					'value_pattern' => '0px'
				),
				array(
					'choice'   => 'padding-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.ajax-search-form .autocomplete-suggestion > a, .ajax-search-form .autocomplete-no-suggestion, .ajax-search-tabs, .ajax-results-title',
					'property' => 'padding-left'
				),
				array(
					'choice'   => 'padding-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.ajax-search-form .autocomplete-suggestion > a, .ajax-search-form .autocomplete-no-suggestion, .ajax-search-tabs, .ajax-results-title',
					'property' => 'padding-right'
				),
				array(
					'choice'  => 'border-top-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.ajax-search-form.input-icon'
				),
				array(
					'choice'  => 'border-right-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.ajax-search-form.input-icon'
				),
				array(
					'choice'  => 'border-bottom-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.ajax-search-form.input-icon'
				),
				array(
					'choice'  => 'border-left-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.ajax-search-form.input-icon'
				)
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions' ),
				array(
					array(
						'choice'        => 'padding-top',
						'type'          => 'css',
						'element'       => '.header-wrapper .et-content-dropdown .ajax-results-title:first-child',
						'property'      => 'margin-top',
						'value_pattern' => '-$'
					),
					array(
						'choice'        => 'padding-bottom',
						'type'          => 'css',
						'element'       => '.header-wrapper .et-content-dropdown .ajax-results-more:last-child',
						'property'      => 'margin-bottom',
						'value_pattern' => '-$'
					),
					array(
						'choice'        => 'padding-left',
						'element'       => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
						'type'          => 'css',
						'property'      => 'padding-left',
						'value_pattern' => '0px'
					),
					array(
						'choice'        => 'padding-right',
						'element'       => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
						'type'          => 'css',
						'property'      => 'padding-right',
						'value_pattern' => '0px'
					),
					array(
						'choice'   => 'padding-left',
						'type'     => 'css',
						'element'  => '.ajax-search-form .autocomplete-suggestion > a, .ajax-search-form .autocomplete-no-suggestion, .ajax-search-tabs, .ajax-results-title',
						'property' => 'padding-left'
					),
					array(
						'choice'   => 'padding-right',
						'type'     => 'css',
						'element'  => '.ajax-search-form .autocomplete-suggestion > a, .ajax-search-form .autocomplete-no-suggestion, .ajax-search-tabs, .ajax-results-title',
						'property' => 'padding-right'
					),
					array(
						'choice'   => 'border-top-width',
						'type'     => 'css',
						'property' => 'border-top-width',
						'element'  => '.ajax-search-form.input-icon'
					),
					array(
						'choice'   => 'border-right-width',
						'type'     => 'css',
						'property' => 'border-right-width',
						'element'  => '.ajax-search-form.input-icon'
					),
					array(
						'choice'   => 'border-bottom-width',
						'type'     => 'css',
						'property' => 'border-bottom-width',
						'element'  => '.ajax-search-form.input-icon'
					),
					array(
						'choice'   => 'border-left-width',
						'type'     => 'css',
						'property' => 'border-left-width',
						'element'  => '.ajax-search-form.input-icon'
					)
				)
			),
		),
		
		// search_content_box_model
		'search_content_box_model_et-mobile'            => array(
			'name'            => 'search_content_box_model_et-mobile',
			'type'            => 'kirki-box-model',
			'settings'        => 'search_content_box_model_et-mobile',
			'label'           => esc_html__( 'Results Dropdown Computed box', 'xstore-core' ),
			'section'         => 'search',
			'default'         => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '10px',
				'padding-right'       => '10px',
				'padding-bottom'      => '10px',
				'padding-left'        => '10px',
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-mobile',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
				),
				array(
					'choice'        => 'padding-top',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et-content-dropdown .ajax-results-title:first-child',
					'property'      => 'margin-top',
					'value_pattern' => '-$'
				),
				array(
					'choice'        => 'padding-bottom',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et-content-dropdown .ajax-results-more:last-child',
					'property'      => 'margin-bottom',
					'value_pattern' => '-$'
				),
				array(
					'choice'        => 'padding-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
					'property'      => 'padding-left',
					'value_pattern' => '0px'
				),
				array(
					'choice'        => 'padding-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
					'property'      => 'padding-right',
					'value_pattern' => '0px'
				),
				array(
					'choice'   => 'padding-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .ajax-search-form .autocomplete-suggestion a, .mobile-header-wrapper .ajax-search-form .autocomplete-no-suggestion, .mobile-header-wrapper .ajax-search-tabs',
					'property' => 'padding-left'
				),
				array(
					'choice'   => 'padding-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.mobile-header-wrapper .ajax-search-form .autocomplete-suggestion a, .mobile-header-wrapper .ajax-search-form .autocomplete-no-suggestion, .mobile-header-wrapper .ajax-search-tabs',
					'property' => 'padding-right'
				),
				array(
					'choice'  => 'border-top-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .ajax-search-form.input-icon'
				),
				array(
					'choice'  => 'border-right-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .ajax-search-form.input-icon'
				),
				array(
					'choice'  => 'border-bottom-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .ajax-search-form.input-icon'
				),
				array(
					'choice'  => 'border-left-width',
					'context' => array( 'editor', 'front' ),
					'element' => '.mobile-header-wrapper .ajax-search-form.input-icon'
				)
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.mobile-header-wrapper .ajax-search-form .ajax-results-wrapper .autocomplete-suggestions' ),
				array(
					array(
						'choice'        => 'padding-top',
						'type'          => 'css',
						'element'       => '.mobile-header-wrapper .et-content-dropdown .ajax-results-title:first-child',
						'property'      => 'margin-top',
						'value_pattern' => '-$'
					),
					array(
						'choice'        => 'padding-bottom',
						'type'          => 'css',
						'element'       => '.mobile-header-wrapper .et-content-dropdown .ajax-results-more:last-child',
						'property'      => 'margin-bottom',
						'value_pattern' => '-$'
					),
					array(
						'choice'        => 'padding-left',
						'type'          => 'css',
						'element'       => '.mobile-header-wrapper .ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
						'property'      => 'padding-left',
						'value_pattern' => '0px'
					),
					array(
						'choice'        => 'padding-right',
						'type'          => 'css',
						'element'       => '.mobile-header-wrapper .ajax-search-form .ajax-results-wrapper .autocomplete-suggestions',
						'property'      => 'padding-right',
						'value_pattern' => '0px'
					),
					array(
						'choice'   => 'padding-left',
						'type'     => 'css',
						'element'  => '.mobile-header-wrapper .ajax-search-form .autocomplete-suggestion a, .mobile-header-wrapper .ajax-search-form .autocomplete-no-suggestion',
						'property' => 'padding-left'
					),
					array(
						'choice'   => 'padding-right',
						'type'     => 'css',
						'element'  => '.mobile-header-wrapper .ajax-search-form .autocomplete-suggestion a, .mobile-header-wrapper .ajax-search-form .autocomplete-no-suggestion',
						'property' => 'padding-right'
					),
					array(
						'choice'   => 'border-top-width',
						'type'     => 'css',
						'property' => 'border-top-width',
						'element'  => '.mobile-header-wrapper .ajax-search-form.input-icon'
					),
					array(
						'choice'   => 'border-right-width',
						'type'     => 'css',
						'property' => 'border-right-width',
						'element'  => '.mobile-header-wrapper .ajax-search-form.input-icon'
					),
					array(
						'choice'   => 'border-bottom-width',
						'type'     => 'css',
						'property' => 'border-bottom-width',
						'element'  => '.mobile-header-wrapper .ajax-search-form.input-icon'
					),
					array(
						'choice'   => 'border-left-width',
						'type'     => 'css',
						'property' => 'border-left-width',
						'element'  => '.mobile-header-wrapper .ajax-search-form.input-icon'
					)
				)
			),
		),
		
		// search_content_border
		'search_content_border_et-desktop'              => array(
			'name'            => 'search_content_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'search_content_border_et-desktop',
			'label'           => esc_html__( 'Results Dropdown Border style', 'xstore-core' ),
			'section'         => 'search',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					// 'element' => '.ajax-search-form:not(.input-icon) .autocomplete-suggestions, .ajax-search-form.input-icon',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions, .ajax-search-form.input-icon',
					'property' => 'border-style',
				),
			),
		),
		
		// search_content_border_color_custom
		'search_content_border_color_custom_et-desktop' => array(
			'name'            => 'search_content_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'search_content_border_color_custom_et-desktop',
			'label'           => esc_html__( 'Results Dropdown Border color', 'xstore-core' ),
			'description'     => $strings['description']['border_color'],
			'section'         => 'search',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_type_et-desktop',
					'operator' => '!=',
					'value'    => 'popup',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					// 'element' => '.ajax-search-form:not(.input-icon) .autocomplete-suggestions, .ajax-search-form.input-icon',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.ajax-search-form .ajax-results-wrapper .autocomplete-suggestions, .ajax-search-form.input-icon',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	unset($sections);
	unset($product_categories);
	
	return array_merge( $fields, $args );
	
} );
