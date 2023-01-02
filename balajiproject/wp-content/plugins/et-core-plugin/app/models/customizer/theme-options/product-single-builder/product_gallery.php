<?php
/**
 * The template created for displaying single product gallery options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_gallery' => array(
			'name'       => 'product_gallery',
			'title'      => esc_html__( 'Gallery', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-images-alt2',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/product_gallery', function ( $fields ) use ( $separators, $strings, $choices, $box_models ) {
	$args = array();
	// Array of fields
	$args = array(
		
		// content separator
		'product_gallery_content_separator'     => array(
			'name'     => 'product_gallery_content_separator',
			'type'     => 'custom',
			'settings' => 'product_gallery_content_separator',
			'section'  => 'product_gallery',
			'default'  => $separators['content'],
			'priority' => 1,
		),
		
		// product_gallery_type
		'product_gallery_type_et-desktop'       => array(
			'name'     => 'product_gallery_type_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'product_gallery_type_et-desktop',
			'label'    => $strings['label']['type'],
			'section'  => 'product_gallery',
			'default'  => 'thumbnails_bottom',
			'choices'  => array(
				'thumbnails_bottom'        => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/gallery/Style-gallery-1.svg',
				'thumbnails_bottom_inside' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/gallery/Style-gallery-2.svg',
				'thumbnails_left'          => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/gallery/Style-gallery-3.svg',
				'one_image'                => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/gallery/Style-gallery-4.svg',
				'double_image'             => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/gallery/Style-gallery-5.svg',
				'full_width'               => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/gallery/Style-gallery-6.svg',
			),
			'priority' => 2,
		),
		
		// product_gallery_zoom
		'product_gallery_zoom_et-desktop'       => array(
			'name'            => 'product_gallery_zoom_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_gallery_zoom_et-desktop',
			'label'           => esc_html__( 'Zoom', 'xstore-core' ),
			'section'         => 'product_gallery',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'product_gallery_type_et-desktop',
					'operator' => '!=',
					'value'    => 'full_width',
				),
			),
			'priority'        => 3,
		),
		
		// product_gallery_lightbox
		'product_gallery_lightbox_et-desktop'   => array(
			'name'            => 'product_gallery_lightbox_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_gallery_lightbox_et-desktop',
			'label'           => esc_html__( 'Lightbox', 'xstore-core' ),
			'section'         => 'product_gallery',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_gallery_type_et-desktop',
					'operator' => '!=',
					'value'    => 'full_width',
				),
			),
			'priority'        => 4,
		),
		
		// product_gallery_thumbnails
		'product_gallery_thumbnails_et-desktop' => array(
			'name'            => 'product_gallery_thumbnails_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_gallery_thumbnails_et-desktop',
			'label'           => esc_html__( 'Slider for thumbnails', 'xstore-core' ),
			'section'         => 'product_gallery',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'product_gallery_type_et-desktop',
					'operator' => 'in',
					'value'    => array( 'thumbnails_bottom', 'thumbnails_bottom_inside', 'thumbnails_left' ),
				),
			),
			'priority'        => 5,
		),
		
		'product_video_position'                           => array(
			'name'        => 'product_video_position',
			'type'        => 'select',
			'settings'    => 'product_video_position',
			'label'       => esc_html__( 'Product video position', 'xstore-core' ),
			'description' => esc_html__( 'Select position of product video for products which have at least one.', 'xstore-core' ),
			'section'     => 'product_gallery',
			'default'     => 'last',
			'choices'     => array(
				'first' => esc_html__( 'First', 'xstore-core' ),
				'last'  => esc_html__( 'Last', 'xstore-core' ),
			),
		),
		
		// go to product sale label 
		'go_to_section_product_sale_label'                 => array(
			'name'     => 'go_to_section_product_sale_label',
			'type'     => 'custom',
			'settings' => 'go_to_section_product_sale_label',
			'section'  => 'product_gallery',
			'default'  => '<span class="et_edit" data-parent="product_sale_label" data-section="product_sale_label_content_separator" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Sale label', 'xstore-core' ) . '</span>',
			'priority' => 6,
		),
		
		// go to product images sizes 
		'go_to_section_woocommerce_product_images'         => array(
			'name'     => 'go_to_section_woocommerce_product_images',
			'type'     => 'custom',
			'settings' => 'go_to_section_woocommerce_product_images',
			'section'  => 'product_gallery',
			'default'  => '<span class="et_edit" data-parent="woocommerce_product_images" data-section="woocommerce_single_image_width" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Image sizes', 'xstore-core' ) . '</span>',
			'priority' => 7,
		),
		
		// go to product images sizes 
		'go_to_section_variations-gallery'                 => array(
			'name'     => 'go_to_section_variations-gallery',
			'type'     => 'custom',
			'settings' => 'go_to_section_variations-gallery',
			'section'  => 'product_gallery',
			'default'  => '<span class="et_edit" data-parent="variations-gallery" data-section="enable_variation_gallery" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Variations gallery', 'xstore-core' ) . '</span>',
			'priority' => 8,
		),
		
		// style separator
		'product_gallery_style_separator'                  => array(
			'name'     => 'product_gallery_style_separator',
			'type'     => 'custom',
			'settings' => 'product_gallery_style_separator',
			'section'  => 'product_gallery',
			'default'  => $separators['style'],
			'priority' => 9,
		),
		
		// product_gallery_width
		'product_gallery_width_et-desktop'                 => array(
			'name'     => 'product_gallery_width_et-desktop',
			'type'     => 'slider',
			'settings' => 'product_gallery_width_et-desktop',
			'label'    => esc_html__( 'Gallery width (%)', 'xstore-core' ),
			'section'  => 'product_gallery',
			'default'  => 100,
			'choices'  => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'output'   => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-product-gallery.images-wrapper',
					'property' => 'width',
					'units'    => '%'
				)
			)
		),
		
		// product_thumbnails_columns
		'product_thumbnails_columns_et-desktop'            => array(
			'name'            => 'product_thumbnails_columns_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_thumbnails_columns_et-desktop',
			'label'           => esc_html__( 'Thumbnails columns', 'xstore-core' ),
			'section'         => 'product_gallery',
			'default'         => 4,
			'choices'         => array(
				'min'  => '3',
				'max'  => '8',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_gallery_type_et-desktop',
					'operator' => 'in',
					'value'    => array( 'thumbnails_bottom', 'thumbnails_bottom_inside', 'thumbnails_left' ),
				),
				array(
					'setting'  => 'product_gallery_thumbnails_et-desktop',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		
		// product_gallery_spacing
		'product_gallery_spacing_et-desktop'               => array(
			'name'            => 'product_gallery_spacing_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_gallery_spacing_et-desktop',
			'label'           => esc_html__( 'Space between (px)', 'xstore-core' ),
			'section'         => 'product_gallery',
			'default'         => 10,
			'choices'         => array(
				'min'  => '0',
				'max'  => '80',
				'step' => '1',
			),
			'active_callback' => function () {
				if ( get_theme_mod( 'product_gallery_type_et-desktop', false ) != 'full_width' ) {
					if ( get_theme_mod( 'product_gallery_type_et-desktop', false ) == 'thumbnails_left' && get_theme_mod( 'product_gallery_thumbnails_et-desktop', false ) ) {
						return false;
					} else {
						return true;
					}
				}
				
				return false;
			},
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.swiper-control-bottom.swiper-container-grid',
					'property'      => 'margin',
					'value_pattern' => '-$px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.swiper-control-bottom.swiper-container-grid .swiper-slide',
					'property' => 'padding',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.swiper-vertical-images .slick-vertical-slider-grid li',
					'property' => 'margin-bottom',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.one_image .main-images > div, .one_image .main-images > img',
					'property' => 'margin-bottom',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.one_image .main-images',
					'property'      => 'margin-bottom',
					'value_pattern' => '-$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.double_image .main-images > div',
					'property'      => 'margin',
					'value_pattern' => '0 $px $px 0'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.double_image .main-images > div',
					'property'      => 'width',
					'value_pattern' => 'calc(50% - $px)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.double_image .main-images',
					'property'      => 'margin',
					'value_pattern' => '0 -$px -$px 0'
				)
			)
		),
		
		// product_gallery_arrow_size
		'product_gallery_arrow_size'                       => array(
			'name'            => 'product_gallery_arrow_size',
			'type'            => 'slider',
			'settings'        => 'product_gallery_arrow_size',
			'label'           => esc_html__( 'Arrows size (px)', 'xstore-core' ),
			'section'         => 'product_gallery',
			'default'         => 50,
			'choices'         => array(
				'min'  => '20',
				'max'  => '90',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_gallery_type_et-desktop',
					'operator' => 'in',
					'value'    => array(
						'thumbnails_left',
						'thumbnails_bottom',
						'thumbnails_bottom_inside',
						'full_width'
					),
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.swipers-couple-wrapper .swiper-container',
					'property' => '--arrow-size',
					'units'    => 'px'
				),
			)
		),
		
		// product_gallery_lightbox_background_color_custom
		'product_gallery_lightbox_background_color_custom' => array(
			'name'            => 'product_gallery_lightbox_background_color_custom',
			'type'            => 'color',
			'settings'        => 'product_gallery_lightbox_background_color_custom',
			'label'           => esc_html__( 'Lightbox background color', 'xstore-core' ),
			'section'         => 'product_gallery',
			'choices'         => array(
				'alpha' => true,
			),
			'default'         => 'rgba(0,0,0,.3)',
			'active_callback' => array(
				array(
					'setting'  => 'product_gallery_type_et-desktop',
					'operator' => '!=',
					'value'    => 'full_width',
				),
				array(
					'setting'  => 'product_gallery_lightbox_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element'  => '.pswp__bg',
					'property' => 'background-color',
				),
			),
		),
		
		'product_gallery_box_model_et-desktop'           => array(
			'name'        => 'product_gallery_box_model_et-desktop',
			'settings'    => 'product_gallery_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_gallery',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.woocommerce-product-gallery.images-wrapper',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.woocommerce-product-gallery.images-wrapper' )
		),
		
		// product_gallery_border
		'product_gallery_border_et-desktop'              => array(
			'name'      => 'product_gallery_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_gallery_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_gallery',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-product-gallery.images-wrapper',
					'property' => 'border-style'
				),
			),
		),
		
		// product_gallery_border_color_custom
		'product_gallery_border_color_custom_et-desktop' => array(
			'name'      => 'product_gallery_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_gallery_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_gallery',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-product-gallery.images-wrapper',
					'property' => 'border-color',
				),
			),
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
