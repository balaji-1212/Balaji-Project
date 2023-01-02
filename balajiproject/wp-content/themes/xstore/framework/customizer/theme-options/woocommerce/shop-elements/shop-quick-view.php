<?php
/**
 * The template created for displaying shop quick view options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-quick-view' => array(
			'name'       => 'shop-quick-view',
			'title'      => esc_html__( 'Quick view', 'xstore' ),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-external',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-quick-view' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'quick_view' => array(
			'name'        => 'quick_view',
			'type'        => 'toggle',
			'settings'    => 'quick_view',
			'label'       => esc_html__( 'Enable quick view', 'xstore' ),
			'description' => esc_html__( 'Turn on to allow customers a quick preview of the product right from its respective category listing.', 'xstore' ),
			'section'     => 'shop-quick-view',
			'default'     => 1,
		),
		
		'quick_view_content_type' => array(
			'name'            => 'quick_view_content_type',
			'type'            => 'radio-buttonset',
			'settings'        => 'quick_view_content_type',
			'label'           => esc_html__( 'Quick view type', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'popup',
			'multiple'        => 1,
			'choices'         => array(
				'popup'      => esc_html__( 'Popup', 'xstore' ),
				'off_canvas' => esc_html__( 'Off-Canvas', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'quick_view_content_position' => array(
			'name'            => 'quick_view_content_position',
			'type'            => 'radio-buttonset',
			'settings'        => 'quick_view_content_position',
			'label'           => esc_html__( 'Off-canvas position', 'xstore' ),
			'description'     => esc_html__( 'This option will work only if content type is set to Off-Canvas', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'right',
			'multiple'        => 1,
			'choices'         => array(
				'left'  => esc_html__( 'Left side', 'xstore' ),
				'right' => esc_html__( 'Right side', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'off_canvas',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.et-quick-view-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et-quick-view-canvas .et-close',
					'function' => 'toggleClass',
					'class'    => 'full-left',
					'value'    => 'left'
				),
				array(
					'element'  => 'et-quick-view-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-right',
					'value'    => 'right'
				),
				array(
					'element'  => '.et-quick-view-canvas',
					'function' => 'toggleClass',
					'class'    => 'et-content-left',
					'value'    => 'left'
				),
			),
		),
		
		'quick_dimentions' => array(
			'name'            => 'quick_dimentions',
			'type'            => 'dimensions',
			'settings'        => 'quick_dimentions',
			'label'           => esc_html__( 'Quick view dimensions (width and height)', 'xstore' ),
			'description'     => esc_html__( 'Set height and width of the quick view lightbox.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => array(
				'width'  => '',
				'height' => '',
			),
			'choices'         => array(
				'labels' => array(
					'width'  => esc_html__( 'Width', 'xstore' ),
					'height' => esc_html__( 'Height', 'xstore' ),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		'quick_images' => array(
			'name'            => 'quick_images',
			'type'            => 'select',
			'settings'        => 'quick_images',
			'label'           => esc_html__( 'Product images', 'xstore' ),
			'description'     => esc_html__( 'Choose the way to display product image in the quick view window.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'slider',
			'choices'         => array(
				'slider' => esc_html__( 'Slider', 'xstore' ),
				'single' => esc_html__( 'Single', 'xstore' ),
				'grid'   => esc_html__( 'Horizontal scroll', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'quick_view_canvas_width' => array(
			'name'            => 'quick_view_canvas_width',
			'type'            => 'slider',
			'settings'        => 'quick_view_canvas_width',
			'label'           => esc_html__( 'Off-canvas width (px)', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 400,
			'choices'         => array(
				'min'  => '200',
				'max'  => '1000',
				'step' => '1',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-quick-view-canvas.et-off-canvas-wide > .et-mini-content',
					'property' => 'max-width',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-quick-view-canvas .swiper-container',
					'property'      => 'max-width',
					'value_pattern' => 'calc($px - 40px)',
				)
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'off_canvas',
				),
			)
		),
		
		'quick_image_height' => array(
			'name'            => 'quick_image_height',
			'type'            => 'etheme-text',
			'settings'        => 'quick_image_height',
			'label'           => esc_html__( 'Images height', 'xstore' ),
			'description'     => esc_html__( 'Minimum height for the images', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
			//			'transport' => 'auto',
			//			'output' => array(
			//				array(
			//					'element' => '.et-quick-view-canvas.et-off-canvas-wide .swiper-grid .main-images',
			//					'property' => 'height',
			//					'units' => 'px'
			//				),
			//			)
		),
		
		'quick_view_layout' => array(
			'name'            => 'quick_view_layout',
			'type'            => 'select',
			'settings'        => 'quick_view_layout',
			'label'           => esc_html__( 'Quick view layout', 'xstore' ),
			'description'     => esc_html__( 'Choose the design of the quick view window.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 'default',
			'choices'         => array(
				'default'  => esc_html__( 'Default', 'xstore' ),
				'centered' => esc_html__( 'Centered', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'quick_view_content_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			)
		),
		
		'quick_view_content' => array(
			'name'            => 'quick_view_content',
			'type'            => 'sortable',
			'settings'        => 'quick_view_content',
			'label'           => esc_html__( 'Quick view content', 'xstore' ),
			'description'     => esc_html__( 'Enable elements that you want to display in quick view window.', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => array(
				'quick_gallery',
				'quick_product_name',
				'quick_price',
				'quick_rating',
				'quick_short_descr',
				'quick_add_to_cart',
				'quick_wishlist',
				'quick_categories',
				'quick_share',
				'product_link'
			),
			'priority'        => 10,
			'choices'         => array(
				'quick_gallery'      => esc_html__( 'Gallery (drag for off-canvas only)', 'xstore' ),
				'quick_product_name' => esc_html__( 'Product Name', 'xstore' ),
				'quick_price'        => esc_html__( 'Price', 'xstore' ),
				'quick_rating'       => esc_html__( 'Product Star Rating', 'xstore' ),
				'quick_short_descr'  => esc_html__( 'Product Short Description', 'xstore' ),
				'quick_add_to_cart'  => esc_html__( 'Add To Cart', 'xstore' ),
				'quick_wishlist'     => esc_html__( 'Wishlist', 'xstore' ),
				'quick_categories'   => esc_html__( 'Product Meta', 'xstore' ),
				'quick_share'        => esc_html__( 'Product Share', 'xstore' ),
				'product_link'       => esc_html__( 'More Details Link', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
				//                array(
				//                    'setting'  => 'quick_view_content_type',
				//                    'operator' => '==',
				//                    'value'    => 'popup',
				//                ),
			)
		),
		
		'quick_descr' => array(
			'name'            => 'quick_descr',
			'type'            => 'toggle',
			'settings'        => 'quick_descr',
			'label'           => esc_html__( 'Product details toggle', 'xstore' ),
			'description'     => esc_html__( 'Enable details toggle for product', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'quick_descr_length' => array(
			'name'            => 'quick_descr_length',
			'type'            => 'etheme-text',
			'settings'        => 'quick_descr_length',
			'label'           => esc_html__( 'Details length', 'xstore' ),
			'description'     => esc_html__( 'Controls the length of the product details', 'xstore' ),
			'section'         => 'shop-quick-view',
			'default'         => 120,
			'active_callback' => array(
				array(
					'setting'  => 'quick_view',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );