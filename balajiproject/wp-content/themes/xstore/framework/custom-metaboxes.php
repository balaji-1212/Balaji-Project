<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 */


add_action( 'cmb2_admin_init', 'etheme_base_metaboxes');
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */

if(!function_exists('etheme_base_metaboxes')) {
	function etheme_base_metaboxes() {
		
		// Start with an underscore to hide fields from custom fields list
		$prefix = '_et_';
		
		$static_blocks_options = array();
		$static_blocks_options[''] = esc_html__('Inherit', 'xstore');
		$static_blocks_options['without'] = esc_html__('Without', 'xstore');
		$static_blocks = array();
		$static_blocks[] = "--choose--";
		
		foreach (etheme_get_static_blocks() as $block) {
			$static_blocks[$block['value']] = $block['label'];
			$static_blocks_options[$block['value']] = $block['label'];
		}
		
		$box_options = array(
			'id'           => 'page_metabox_tabs',
			'title'      => esc_html__( '[8theme] Layout Options', 'xstore' ),
			'object_types'      => array( 'page'), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // Keep the metabox closed by default
		);
		
		// Setup meta box
		$cmb = new_cmb2_box( $box_options );
		
		// Setting tabs
		$tabs_page_posts_setting           = array(
			'config' => $box_options,
			// 'layout' => 'vertical', // Default : horizontal
			'tabs'   => array()
		);
		
		$tabs_page_posts_setting['tabs']['layout'] = array(
			'id'     => 'layout',
			'title'  => __( 'Layout', 'xstore' ),
			'fields' => array(
				array(
					'id'          => ETHEME_PREFIX .'breadcrumb_type',
					'name'        => esc_html__('Breadcrumbs Style', 'xstore'),
					'type'        => 'select',
					'options'     => array(
						''   => '',
						'default'   => 'Center',
						'left'   => 'Align left',
						'left2' => 'Left inline',
						'disable'   => 'Disable',
					)
				),
				array(
					'id'          => ETHEME_PREFIX .'breadcrumb_effect',
					'name'        => esc_html__('Breadcrumbs Effect', 'xstore'),
					'type'        => 'select',
					'class'       => '',
					'options'     => array(
						''   => '',
						'none' => 'None',
						'mouse' => 'Parallax on mouse move',
						'text-scroll' => 'Text animation on scroll',
					)
				),
				array(
					'id'          => ETHEME_PREFIX .'page_banner',
					'name'        => esc_html__('Use custom banner above breadcrumbs', 'xstore'),
					'type'        => 'select',
					'options'     => $static_blocks,
				),
				array(
					'id'          => ETHEME_PREFIX .'page_slider',
					'name'        => esc_html__('Page slider', 'xstore'),
					'desc'        => esc_html__('Show revolution slider instead of breadcrumbs and page title', 'xstore'),
					'type'        => 'select',
					'options'     => etheme_get_revsliders()
				),
				array(
					'id'          => ETHEME_PREFIX .'sidebar_state',
					'name'        => esc_html__('Sidebar Position', 'xstore'),
					'type'        => 'radio',
					'options'     => array(
						'default' => 'Inherit',
						'without' => 'Without',
						'left' => 'Left',
						'right' => 'Right'
					)
				),
				array(
					'id'          => ETHEME_PREFIX .'widget_area',
					'name'        => esc_html__('Widget Area', 'xstore'),
					'type'        => 'select',
					'options'     => etheme_get_sidebars()
				),
				array(
					'id'          => ETHEME_PREFIX .'sidebar_width',
					'name'        => esc_html__('Sidebar width', 'xstore'),
					'type'        => 'radio',
					'options'     => array(
						'' => 'Inherit',
						2 => '1/6',
						3 => '1/4',
						4 => '1/3'
					)
				)
			)
		);
		
		$tabs_page_posts_setting['tabs']['style'] = array(
			'id'     => 'style',
			'title'  => __( 'Style', 'xstore' ),
			'fields' => array(
				array(
					'id'          => ETHEME_PREFIX .'bg_image',
					'name'        => esc_html__('Custom background image', 'xstore'),
					'desc' => 'Upload an image or enter an URL.',
					'type' => 'file',
					'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
				),
				array(
					'id'          => ETHEME_PREFIX .'bg_color',
					'name'        => esc_html__('Custom background color', 'xstore'),
					'type' => 'colorpicker',
				)
			)
		);
		
		$tabs_page_posts_setting['tabs']['footer'] = array(
			'id'     => 'footer',
			'title'  => __( 'Footer / Prefooter', 'xstore' ),
			'fields' => array(
				array(
					'id'          => ETHEME_PREFIX .'custom_prefooter',
					'name'        => esc_html__('Use custom pre footer for this page/post', 'xstore'),
					'type'        => 'select',
					'options'     => $static_blocks_options,
				),
				array(
					'id'          => ETHEME_PREFIX .'custom_footer',
					'name'        => esc_html__('Use custom footer for this page/post', 'xstore'),
					'type'        => 'select',
					'options'     => $static_blocks_options,
				),
				array(
					'id'          => ETHEME_PREFIX .'footer_fixed',
					'name'        => esc_html__('Fixed footer', 'xstore'),
					'type'        => 'radio',
					'options'     => array(
						'inherit' => 'Inherit',
						'yes' => 'yes',
						'no' => 'no',
					)
				),
				array(
					'id'          => ETHEME_PREFIX .'remove_copyrights',
					'name'        => esc_html__('Disable copyrights', 'xstore'),
					'default'     => false,
					'type'        => 'checkbox'
				)
			)
		);
		
		// Set tabs
		$cmb->add_field( array(
			'id'   => '__post_page_tabs',
			'type' => 'tabs',
			'tabs' => $tabs_page_posts_setting
		) );

	    // product metaboxes done
		$box_options = array(
			'id'           => 'product_metabox_tabs',
			'title'      => esc_html__( '[8theme] Product Options', 'xstore' ),
			'object_types'      => array( 'product' ), // Post type
			'context'    => 'normal',
			'priority'   => 'low',
			'show_names' => true, // Show field names on the left
		);
		
		// Setup meta box
		$cmb = new_cmb2_box( $box_options );
		
		// $static_blocks = array();
		// $static_blocks[] = "--choose--";
		
		// foreach (etheme_get_static_blocks() as $block) {
		// 	$static_blocks[$block['value']] = $block['label'];
		// }
		
		// Setting tabs
		$tabs_setting           = array(
			'config' => $box_options,
			// 'layout' => 'vertical', // Default : horizontal
			'tabs'   => array()
		);
		
		$product_category_options = array(
			'auto' => '--Auto--',
		);
		
		$terms = get_terms( 'product_cat', 'hide_empty=0' );
		
		if( ! is_wp_error( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				$product_category_options[$term->slug] = $term->name;
			}
		}
		
		$tabs_setting['tabs']['general'] = array(
			'id'     => 'general',
			'title'  => __( 'General', 'xstore' ),
			'fields' => array(
				array(
					'name' => esc_html__('Primary category', 'xstore'),
					'id' => $prefix . 'primary_category',
					'type' => 'select',
					'options' => $product_category_options
				),
				array(
					'name' => esc_html__('Sale countdown', 'xstore'),
					'id' => $prefix . 'sale_counter',
					'type' => 'select',
					'options'          => array(
						'disable' => esc_html__( 'Disable', 'xstore' ),
						'grid' => esc_html__( 'Grid', 'xstore' ),
						'list' => esc_html__( 'List', 'xstore' ),
						'single' => esc_html__( 'Single', 'xstore' ),
						'single_list' => esc_html__( 'Single/List', 'xstore' ),
						'all' => esc_html__( 'Single/List/Grid', 'xstore' ),
					),
				)
			)
		);
		
		$tabs_setting['tabs']['layout'] = array(
			'id'     => 'layout',
			'title'  => __( 'Single layout', 'xstore' ),
			'fields' => array(
				array(
					'name' => esc_html__('Product layout', 'xstore'),
					'id' => ETHEME_PREFIX . 'single_layout',
					'type' => 'radio_inline',
					'options'  => array (
						'small' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-small.png' . '" title="product-small" alt="product-small">',
						'default' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-medium.png' . '" title="product-medium" alt="product-medium">',
						'xsmall' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-thin.png' . '" title="product-thin" alt="product-thin">',
						'large' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-large.png' . '" title="product-large" alt="product-large">',
						'fixed' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-fixed.png' . '" title="product-fixed" alt="product-fixed">',
						'center' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-center.png' . '" title="product-center" alt="product-center">',
						'wide' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-wide.png' . '" title="product-wide" alt="product-wide">',
						'right' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-right.png' . '" title="product-right" alt="product-right">',
						'booking' => '<img src="' . ETHEME_CODE_IMAGES . 'layout/product-booking.png' . '" title="product-booking" alt="product-booking">',
						'standard' => 'Inherit'
					),
					'default' => 'standard',
					'classes' => 'et-image-metabox et-small-image-metabox',
				),
				array(
					'name' => esc_html__('Disable sidebar', 'xstore'),
					'id' => $prefix . 'disable_sidebar',
					'type'    => 'checkbox',
				),
				array(
					'name' => esc_html__( 'Additional custom block', 'xstore' ),
					'id' => $prefix . 'additional_block',
					'type'    => 'select',
					'options' => $static_blocks
				),
				array(
					'name' => esc_html__('Product gallery slider', 'xstore'),
					'id' => $prefix . 'product_slider',
					'type' => 'select',
					'options'          => array(
						'inherit' => esc_html__( 'Inherit from Theme Options', 'xstore' ),
						'on' => esc_html__( 'Enable', 'xstore' ),
						'on_mobile' => esc_html__( 'Enable on mobile', 'xstore' ),
						'off' => esc_html__( 'Disable', 'xstore' ),
					),
				),
				array(
					'name' => esc_html__('Thumbnails', 'xstore'),
					'id' => $prefix . 'slider_direction',
					'type' => 'select',
					'options'          => array(
						'' => esc_html__( 'Inherit from Theme Options', 'xstore' ),
						'horizontal' => esc_html__( 'Horizontal', 'xstore' ),
						'vertical' => esc_html__( 'Vertical', 'xstore' ),
						'disable' => esc_html__('Disable', 'xstore'),
					
					),
				),
				array(
					'name' => esc_html__('Size guide type', 'xstore'),
					'id' => $prefix . 'size_guide_type',
					'type' => 'select',
					'options'          => array(
						'' => esc_html__( 'Inherit from Theme Options', 'xstore' ),
						'popup' => esc_html__( 'Popup', 'xstore' ),
						'download_button' => esc_html__( 'Download button', 'xstore' ),
					
					),
				),
				array(
					'id'          => ETHEME_PREFIX .'size_guide_img',
					'name'        => esc_html__( 'Size guide image', 'xstore'),
					'desc' => esc_html__( 'Upload an image or enter an URL.', 'xstore'),
					'type' => 'file',
					'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
				),
				array(
					'name' => esc_html__( 'Custom tab title', 'xstore' ),
					'id' => $prefix . 'custom_tab1_title',
					'type' => 'text',
				),
				array(
					'name' => esc_html__( 'Custom tab', 'xstore' ),
					'id' => $prefix . 'custom_tab1',
					'type' => 'wysiwyg',
				)
			)
		);
		
		$tabs_setting['tabs']['archive'] = array(
			'id'     => 'archive',
			'title'  => __( 'Archive page', 'xstore' ),
			'fields' => array(
				array(
					'name' => esc_html__('Archive image hover effect', 'xstore'),
					'id' => ETHEME_PREFIX . 'single_thumbnail_hover',
					'type' => 'select',
					'options'          => array(
						'inherit' => esc_html__( 'Inherit', 'xstore' ),
						'disable' => esc_html__( 'Disable', 'xstore' ),
						'swap' => esc_html__( 'Swap', 'xstore' ),
						'slider' => esc_html__( 'Slider', 'xstore' ),
                        'carousel' => esc_html__( 'Automatic Carousel', 'xstore' ),
					),
				),
				array(
					'name' => esc_html__('Product content effect', 'xstore'),
					'id' => ETHEME_PREFIX . 'product_view_hover',
					'type' => 'select',
					'options'          => array(
						'inherit' => esc_html__(' Inherit', 'xstore'),
						'disable' => esc_html__( 'Disable', 'xstore' ),
						'default' => esc_html__( 'Default', 'xstore' ),
						'mask3'   => esc_html__( 'Buttons on hover middle', 'xstore' ),
						'mask'    => esc_html__( 'Buttons on hover bottom', 'xstore' ),
						'mask2'   => esc_html__( 'Buttons on hover right', 'xstore' ),
						'info'    => esc_html__( 'Information mask', 'xstore' ),
						'booking' => esc_html__( 'Booking', 'xstore' ),
						'light'   => esc_html__( 'Light', 'xstore' ),
					),
				),
				array(
					'name' => esc_html__( 'Hover Color Scheme', 'xstore' ),
					'id' => ETHEME_PREFIX . 'product_view_color',
					'type'    => 'select',
					'options' => array (
						'inherit'	  => esc_html__( 'Inherit', 'xstore' ),
						'white'       => esc_html__( 'White', 'xstore' ),
						'dark'        => esc_html__( 'Dark', 'xstore' ),
						'transparent' => esc_html__( 'Transparent', 'xstore' )
					),
				)
			)
		);
		
		// Set tabs
		$cmb->add_field( array(
			'id'   => '__product_tabs',
			'type' => 'tabs',
			'tabs' => $tabs_setting
		) );
		
		// post metaboxes
		$box_options = array(
			'id'           => 'post_metabox_tabs',
			'title'      => esc_html__( '[8theme] Post Options', 'xstore' ),
			'object_types'      => array( 'post', ), // Post type
			'context'    => 'normal',
			'priority'   => 'low',
			'show_names' => true, // Show field names on the left
		);
		
		// Setup meta box
		$cmb = new_cmb2_box( $box_options );
		
		// Setting tabs
		$tabs_setting           = array(
			'config' => $box_options,
			// 'layout' => 'vertical', // Default : horizontal
			'tabs'   => array()
		);
		
		$category_options = array(
			'auto' => '--Auto--',
		);
		
		$terms = get_terms( 'category', 'hide_empty=0' );
		
		foreach ( $terms as $term ) {
			$category_options[$term->slug] = $term->name;
		}
		
		$tabs_setting['tabs']['general'] = array(
			'id'     => 'general',
			'title'  => __( 'General', 'xstore' ),
			'fields' => array(
				array(
					'name' => esc_html__('Post featured video (for video post format)', 'xstore'),
					'id' => $prefix . 'post_video',
					'type' => 'text_medium',
					'desc' => 'Paste a link from Vimeo or Youtube, it will be embeded in the post'
				),
				array(
					'name' => esc_html__('Quote (for quote post format)', 'xstore'),
					'id' => $prefix . 'post_quote',
					'type' => 'wysiwyg',
					'rows'  => 5,
				),
				array(
					'name' => esc_html__('Primary category', 'xstore'),
					'id' => $prefix . 'primary_category',
					'type' => 'select',
					'options' => $category_options
				)
			)
		);
		
		$tabs_setting['tabs']['style'] = $tabs_page_posts_setting['tabs']['style'];
		
		$tabs_setting['tabs']['layout'] = array(
			'id'     => 'layout',
			'title'  => __( 'Layout', 'xstore' ),
			'fields' => array_merge($tabs_page_posts_setting['tabs']['layout']['fields'], array(
				array(
					'name' => esc_html__('Post template', 'xstore'),
					'id' => $prefix . 'post_template',
					'type' => 'select',
					'options'          => array(
						'' => esc_html__( 'Inherit', 'xstore' ),
						'default' => esc_html__( 'Default', 'xstore' ),
						'full-width' => esc_html__( 'Large', 'xstore' ),
						'large' => esc_html__( 'Full width', 'xstore' ),
						'large2' => esc_html__( 'Full width centered', 'xstore' ),
					),
				),
				array(
					'name' => esc_html__('Hide featured image on single', 'xstore'),
					'id' => $prefix . 'post_featured',
					'type' => 'checkbox',
					'value' => 'enable'
				)
			)),
		);
		
		$tabs_setting['tabs']['footer'] = $tabs_page_posts_setting['tabs']['footer'];
		
		// Set tabs
		$cmb->add_field( array(
			'id'   => '__post_tabs',
			'type' => 'tabs',
			'tabs' => $tabs_setting
		) );
		
    	// Categories metabox
	}
}

add_filter('cmb2-taxonomy_meta_boxes', 'xstore_cateogires_metaboxes');

if( ! function_exists( 'xstore_cateogires_metaboxes' ) ) {
	function xstore_cateogires_metaboxes() {
		$prefix = '_et_';
		$meta_boxes['category_meta'] = array(
			'id'            => 'category_meta',
			'title'         => __( 'Category Metabox', 'xstore' ),
			'object_types'  => array( 'category', 'product_cat' ), // Taxonomy
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'        => array(
				array(
		            'id'          => $prefix .'page_heading',
		            'name'        => __('Custom page heading image for this category', 'xstore'),
				    'desc' => __('Upload an image or enter an URL.', 'xstore'),
				    'type' => 'file',
				    'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
		        ),
				array(
					'id'          => $prefix .'size_guide',
					'name'        => __('Custom size guide image for products of this category', 'xstore'),
					'desc' => __('Upload an image or enter an URL.', 'xstore'),
					'type' => 'file',
					'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
				),
		        array(
		            'id'   => $prefix .'second_description',
		            'name' => __('Description after content', 'xstore'),
				    'desc' => __('The description is not prominent by default; however, some themes may show it.', 'xstore'),
				    'type' => 'wysiwyg',
		            'rows'  => 5,
		        )
			)
		);
		
		$meta_boxes['tag_meta'] = array(
			'id'            => 'tag_meta',
			'title'         => __( 'Tag Metabox', 'xstore' ),
			'object_types'  => array( 'product_tag' ), // Taxonomy
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'        => array(
				array(
					'id'          => $prefix .'page_heading',
					'name'        => __('Custom page heading image for this tag', 'xstore'),
					'desc' => __('Upload an image or enter an URL.', 'xstore'),
					'type' => 'file',
					'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
				),
				array(
					'id'   => $prefix .'second_description',
					'name' => __('Description after content', 'xstore'),
					'desc' => __('The description is not prominent by default; however, some themes may show it.', 'xstore'),
					'type' => 'wysiwyg',
					'rows'  => 5,
				)
			)
		);

		return $meta_boxes;


	}
}