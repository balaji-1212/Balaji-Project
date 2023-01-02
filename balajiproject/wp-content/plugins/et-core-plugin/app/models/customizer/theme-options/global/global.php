<?php

/**
 * The template created for enqueueing all files for header panel
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.5
 */

function et_b_get_posts( $args ) {
	if ( is_string( $args ) ) {
		$args = add_query_arg(
			array(
				'suppress_filters' => false,
			)
		);
	} elseif ( is_array( $args ) && ! isset( $args['suppress_filters'] ) ) {
		$args['suppress_filters'] = false;
	}

	$add_none = isset($args['with_none']);
	$add_custom = isset($args['with_custom']);
	$add_select_page = isset($args['with_select_page']);
	if ( $add_none )
		unset($args['with_none']);
	if ( $add_custom )
		unset($args['with_custom']);
	if ( $add_select_page )
		unset($args['with_select_page']);
	// Get the posts.
	$posts = get_posts( $args );

	// Properly format the array.
	$items = array();
	foreach ( $posts as $post ) {
		$items[ $post->ID ] = $post->post_title . ' (id - ' . $post->ID . ')';
	}

	wp_reset_postdata();

	if ( $add_none )
		$items[0] = esc_html__( 'None', 'xstore-core' );

	if ( $add_custom )
		$items['custom'] = esc_html__( 'Custom', 'xstore-core' );

	if ( $add_select_page )
		$items[0] = esc_html__( 'Select page', 'xstore-core' );

	return $items;
}

function et_b_get_terms( $taxonomies ) {
	$items = array();

	// Get the post types.
	$terms = get_terms( $taxonomies );

	if ( is_wp_error( $terms ) ) {
		return $items;
	}

	// Build the array.
	foreach ( $terms as $term ) {
		$items[ $term->term_id ] = $term->name . ' (id - ' . $term->term_id . ')';;
	}

	if ( 'nav_menu' == $taxonomies )
		$items[0] = esc_html__( 'Select menu', 'xstore-core' );

	return $items;
}

function et_b_get_widgets() {
	global $wp_widget_factory;
	$field = array();
	foreach ( $wp_widget_factory->widgets as $widget ) {
		$widget_class           = get_class( $widget );
		$field[ $widget_class ] = $widget->name;
	}
	asort( $field );

	return $field;
}

//	function et_b_get_shortcodes() {
//		// Get the array of all the shortcodes
//		global $shortcode_tags;
//
//		$shortcodes = $shortcode_tags;
//
//		// sort the shortcodes with alphabetical order
//		ksort($shortcodes);
//
//		$shortcode_output = array();
//
//		foreach ($shortcodes as $shortcode => $value) {
//			$shortcode_output[$shortcode] = '['.$shortcode.']';
//		}
//
//		return $shortcode_output;
//	}

//$post_types = array(
//	'pages'              => et_b_get_posts(
//		array(
//			'post_per_page' => -1,
//			'nopaging'      => true,
//			'post_type'     => 'page'
//		)
//	),
//	'menus'              => et_b_get_terms( 'nav_menu' ),
//	'sections'           => et_b_get_posts(
//		array(
//			'post_per_page' => -1,
//			'nopaging'      => true,
//			'post_type'     => 'staticblocks'
//		)
//	),
//	'product_categories' => et_b_get_terms( 'product_cat' ),
//	'sidebars'           => etheme_get_sidebars(),
//);
//
//$post_types['pages_all'] = $post_types['pages'];
//
//$post_types['pages']['custom'] = esc_html__( 'Custom', 'xstore-core' );
//$post_types['pages'][0]        = $post_types['pages_all'][0] = esc_html__( 'Select page', 'xstore-core' );
//
//$post_types['menus'][0] = esc_html__( 'Select menu', 'xstore-core' );
//
//$post_types['sections'][0] = esc_html__( 'None', 'xstore-core' );

$is_customize_preview = is_customize_preview();
$mobile_panel_elements = array(
	'shop'           => esc_html__( 'Shop', 'xstore-core' ),
	'cart'           => esc_html__( 'Cart', 'xstore-core' ),
	'home'           => esc_html__( 'Home', 'xstore-core' ),
	'account'        => esc_html__( 'Account', 'xstore-core' ),
	'wishlist'       => esc_html__( 'Wishlist', 'xstore-core' ),
    'compare'        => esc_html__( 'Compare', 'xstore-core' ),
	'search'         => esc_html__( 'Search', 'xstore-core' ),
	'mobile_menu'    => esc_html__( 'Mobile menu', 'xstore-core' ),
	'more_toggle'    => esc_html__( 'More toggle 01', 'xstore-core' ),
	'more_toggle_02' => esc_html__( 'More toggle 02', 'xstore-core' ),
	'custom'         => esc_html__( 'Custom', 'xstore-core' ),
);

$header_presets = array(
	'' => esc_html__( 'Select the header', 'xstore-core' ),
	'header-lifestyle-blog' => esc_html__( 'Lifestyle Blog', 'xstore-core' ),
	'header-beauty-and-cosmetics' => esc_html__( 'Beauty And Cosmetics', 'xstore-core' ),
	'header-rental-car' => esc_html__( 'Rental Car', 'xstore-core' ),
	'header-car-parts' => esc_html__( 'Car Parts', 'xstore-core' ),
	'header-phone-service' => esc_html__( 'Phone Service', 'xstore-core' ),
	'header-book-store' => esc_html__( 'Book Store', 'xstore-core' ),
	'header-niche-market04' => esc_html__( 'Niche Market04', 'xstore-core' ),
	'header-animals01' => esc_html__( 'Animals01', 'xstore-core' ),
	'header-business02' => esc_html__( 'Business02', 'xstore-core' ),
	'header-water-delivery' => esc_html__( 'Water Delivery', 'xstore-core' ),
	'header-minimal-electronics' => esc_html__( 'Minimal Electronics', 'xstore-core' ),
	'header-minimal-fashion03' => esc_html__( 'Minimal Fashion03', 'xstore-core' ),
	'header-minimal-fashion02' => esc_html__( 'Minimal Fashion02', 'xstore-core' ),
	'header-ecoenergy' => esc_html('Ecoenergy', 'xstore-core'),
	'header-sneakers' => esc_html('Sneakers', 'xstore-core'),
	'header-minimal-fashion' => esc_html('Minimal Fashion', 'xstore-core'),
	'header-flowers' => esc_html('Flowers', 'xstore-core'),
	'header-real-estate' => esc_html('Real Estate', 'xstore-core'),
	'header-niche-market03' => esc_html('Niche Market03', 'xstore-core'),
	'header-digital-marketing' => esc_html('Digital Marketing', 'xstore-core'),
	'header-coffee' => esc_html('Coffee', 'xstore-core'),
	'header-online-courses' => esc_html('Online Courses', 'xstore-core'),
	'header-headphone' => esc_html('Headphone', 'xstore-core'),
	'header-marseille03' => esc_html('Marseille03', 'xstore-core'),
	'header-niche-market02' => esc_html('Niche Market02', 'xstore-core'),
	'header-medical02' => esc_html('Medical02', 'xstore-core'),
	'header-restaurant' => esc_html('Restaurant', 'xstore-core'),
	'header-grocery-market' => esc_html__( 'Grocery Market', 'xstore-core' ),
	'header-agricultural' => esc_html__( 'Agricultural Demo Header', 'xstore-core' ),
	'header-animals' => esc_html__( 'Animals Demo Header', 'xstore-core' ),
	'header-artmaxy' => esc_html__( 'Artmaxy Demo Header', 'xstore-core' ),
	'header-baby-shop' => esc_html__( 'Baby-shop Demo Header', 'xstore-core' ),
	'header-babyland01' => esc_html__( 'Babyland01 Demo Header', 'xstore-core' ),
	'header-bakery' => esc_html__( 'Bakery Demo Header', 'xstore-core' ),
	'header-barbershop' => esc_html__( 'Barbershop Demo Header', 'xstore-core' ),
	'header-bicycle' => esc_html__( 'Bicycle Demo Header', 'xstore-core' ),
	'header-books' => esc_html__( 'Books Demo Header', 'xstore-core' ),
	'header-burger' => esc_html__( 'Burger Demo Header', 'xstore-core' ),
	'header-business' => esc_html__( 'Business Demo Header', 'xstore-core' ),
	'header-carwash' => esc_html__( 'Carwash Demo Header', 'xstore-core' ),
	'header-cleaning' => esc_html__( 'Cleaning Demo Header', 'xstore-core' ),
	'header-cocktails' => esc_html__( 'Cocktails Demo Header', 'xstore-core' ),
	'header-concert' => esc_html__( 'Concert Demo Header', 'xstore-core' ),
	'header-corporate' => esc_html__( 'Corporate Demo Header', 'xstore-core' ),
	'header-cosmetics' => esc_html__( 'Cosmetics Demo Header', 'xstore-core' ),
	'header-dark' => esc_html__( 'Dark Demo Header', 'xstore-core' ),
	'header-decor' => esc_html__( 'Home Decor Demo Header', 'xstore-core' ),
	'header-home-banners' => esc_html__( 'Home Banners Header', 'xstore-core' ),
	'header-home-boxed' => esc_html__( 'Home Boxed Header', 'xstore-core' ),
	'header-home-red' => esc_html__( 'Home Red Header', 'xstore-core' ),
	'header-niche-market' => esc_html__( 'Niche Market Demo Header', 'xstore-core' ),
	'header-default' => esc_html__( 'Default Demo Header', 'xstore-core' ),
	'header-delivery' => esc_html__( 'Delivery Demo Header', 'xstore-core' ),
	'header-drinks' => esc_html__( 'Drinks Demo Header', 'xstore-core' ),
	'header-eco-scooter' => esc_html__( 'Eco-scooter Demo Header', 'xstore-core'),
	'header-eco-transport' => esc_html__( 'Eco-transport Demo Header', 'xstore-core'),
	'header-electron01' => esc_html__( 'Electron01 Demo Header', 'xstore-core' ),
	'header-electron02' => esc_html__( 'Electron02 Demo Header', 'xstore-core' ),
	'header-electronics' => esc_html__( 'Electronics Demo Header', 'xstore-core' ),
	'header-engineer' => esc_html__( 'Engineer Demo Header', 'xstore-core' ),
	'header-fashion' => esc_html__( 'Fashion Demo Header', 'xstore-core' ),
	'header-finances' => esc_html__( 'Finances Demo Header', 'xstore-core' ),
	'header-freelance' => esc_html__( 'Freelance Demo Header', 'xstore-core' ),
	'header-furniture' => esc_html__( 'Furniture Demo Header', 'xstore-core' ),
	'header-furniture2' => esc_html__( 'Furniture2 Demo Header', 'xstore-core' ),
	'header-games' => esc_html__( 'Games Demo Header', 'xstore-core' ),
	'header-glasses' => esc_html__( 'Glasses Demo Header', 'xstore-core' ),
	'header-gym' => esc_html__( 'Gym Demo Header', 'xstore-core' ),
	'header-handmade' => esc_html__( 'Handmade Demo Header', 'xstore-core' ),
	'header-hipster' => esc_html__( 'Hipster Demo Header', 'xstore-core' ),
	'header-hosting' => esc_html__( 'Hosting Demo Header', 'xstore-core' ),
	'header-interior' => esc_html__( 'Interior Demo Header', 'xstore-core' ),
	'header-jewellery' => esc_html__( 'Jewellery Demo Header', 'xstore-core' ),
	'header-kids' => esc_html__( 'Kids Demo Header', 'xstore-core' ),
	'header-landing' => esc_html__( 'Landing Watches Demo Header', 'xstore-core' ),
	'header-language-courses' => esc_html__( 'Language-courses Demo Header', 'xstore-core' ),
	'header-lawyer' => esc_html__( 'Lawyer Demo Header', 'xstore-core' ),
	'header-lingerie' => esc_html__( 'Lingerie Demo Header', 'xstore-core' ),
	'header-makeup' => esc_html__( 'Makeup Demo Header', 'xstore-core' ),
	'header-marketing' => esc_html__( 'Marketing Demo Header', 'xstore-core' ),
	'header-marseille01' => esc_html__( 'Marseille01 Demo Header', 'xstore-core' ),
	'header-marseille02' => esc_html__( 'Marseille02 Demo Header', 'xstore-core' ),
	'header-medical' => esc_html__( 'Medical Demo Header', 'xstore-core' ),
	'header-minimal' => esc_html__( 'Minimal Demo Header', 'xstore-core' ),
	'header-minimalist-outfits' => esc_html__( 'Minimalist-outfits Demo Header', 'xstore-core' ),
	'header-mobile' => esc_html__( 'Mobile Demo Header', 'xstore-core' ),
	'header-organic' => esc_html__( 'Organic Demo Header', 'xstore-core' ),
	'header-organic01' => esc_html__( 'Organic01 Demo Header', 'xstore-core' ),
	'header-organic02' => esc_html__( 'Organic02 Demo Header', 'xstore-core' ),
	'header-organic-cosmetics' => esc_html__( 'Organic-Cosmetics Demo Header', 'xstore-core' ),
	'header-photographer' => esc_html__( 'Photographer Demo Header', 'xstore-core' ),
	'header-pizza' => esc_html__( 'Pizza Demo Header', 'xstore-core' ),
	'header-plumbing' => esc_html__( 'Plumbing Demo Header', 'xstore-core' ),
	'header-shoes' => esc_html__( 'Shoes Demo Header', 'xstore-core' ),
	'header-spa' => esc_html__( 'Spa Demo Header', 'xstore-core' ),
	'header-sushi' => esc_html__( 'Sushi Demo Header', 'xstore-core' ),
	'header-tea' => esc_html__( 'Tea Demo Header', 'xstore-core' ),
	'header-typography' => esc_html__( 'Typography Demo Header', 'xstore-core' ),
	'header-underwear' => esc_html__( 'Underwear Demo Header', 'xstore-core' ),
	'header-wedding' => esc_html__( 'Wedding Demo Header', 'xstore-core' ),
	'header-x-phone' => esc_html__( 'X-phone Demo Header', 'xstore-core' ),
);

$strings = array(
	'label'           => array(
		'alignment'              => esc_html__( 'Alignment', 'xstore-core' ),
		'style'                  => esc_html__( 'Style', 'xstore-core' ),
		'mode'                   => esc_html__( 'Mode', 'xstore-core' ),
		'type'                   => esc_html__( 'Type', 'xstore-core' ),
		'icon'                   => esc_html__( 'Icon', 'xstore-core' ),
		'colors'                 => esc_html__( 'Colors', 'xstore-core' ),
		'color'                  => esc_html__( 'Color', 'xstore-core' ),
		'fonts'                  => esc_html__( 'Fonts', 'xstore-core' ),
		'elements'               => esc_html__( 'Elements', 'xstore-core' ),
		'elements_spacing'       => esc_html__( 'Elements spacing (px)', 'xstore-core' ),
		'wide_header'            => esc_html__( 'Full-width header', 'xstore-core' ),
		'select_menu'            => esc_html__( 'Select the menu', 'xstore-core' ),
		'select_menu_extra'      => esc_html__( 'Select Extra Tab Menu', 'xstore-core' ),
		'content_zoom'           => esc_html__( 'Content zoom (%)', 'xstore-core' ),
		'content_size'           => esc_html__( 'Content size (%)', 'xstore-core' ),
		'size_proportion'        => esc_html__( 'Size proportion', 'xstore-core' ),
		'title_size_proportion'  => esc_html__( 'Title size proportion', 'xstore-core' ),
		'title_sizes'            => esc_html__( 'Title sizes', 'xstore-core' ),
		'wcag_color'             => esc_html__( 'WCAG Color', 'xstore-core' ),
		'wcag_color_hover'       => esc_html__( 'WCAG Color (hover)', 'xstore-core' ),
		'wcag_color_active'      => esc_html__( 'WCAG Color (active)', 'xstore-core' ),
		'wcag_bg_color'          => esc_html__( 'WCAG Background control', 'xstore-core' ),
		'wcag_bg_color_hover'    => esc_html__( 'WCAG Background control (hover)', 'xstore-core' ),
		'wcag_bg_color_active'   => esc_html__( 'WCAG Background control (active)', 'xstore-core' ),
		'computed_box'           => esc_html__( 'Computed box', 'xstore-core' ),
		'border_radius'          => esc_html__( 'Border radius (px)', 'xstore-core' ),
		'border_style'           => esc_html__( 'Border style', 'xstore-core' ),
		'min_height'             => esc_html__( 'Min height (px)', 'xstore-core' ),
		'icons_zoom'             => esc_html__( 'Icons zoom (proportion)', 'xstore-core' ),
		'custom_icon_svg'        => esc_html__( 'Custom icon SVG code', 'xstore-core' ),
		'custom_image_svg'       => esc_html__( 'Custom icon SVG', 'xstore-core' ),
		'show_title'             => esc_html__( 'Show title', 'xstore-core' ),
		'bg_color'               => esc_html__( 'Background color', 'xstore-core' ),
		'border_color'           => esc_html__( 'Border color', 'xstore-core' ),
		'button_text'            => esc_html__( 'Button text', 'xstore-core' ),
		'button_size_proportion' => esc_html__( 'Button size (proportion)', 'xstore-core' ),
		'page_links'             => esc_html__( 'Page links', 'xstore-core' ),
		'custom_link'            => esc_html__( 'Custom link', 'xstore-core' ),
		'target_blank'           => esc_html__( 'Open in new window', 'xstore-core' ),
		'rel_no_follow'          => esc_html__( 'Add no-follow rel', 'xstore-core' ),
		'use_static_block'       => esc_html__( 'Use static block', 'xstore-core' ),
		'direction'              => esc_html__( 'Direction', 'xstore-core' ),
		'editor_control'         => esc_html__( 'This is an editor control.', 'xstore-core' ),
		'sticky_logo'            => esc_html__( 'Custom sticky logo', 'xstore-core' ),
		'paddings'               => array(
			'padding-top'    => esc_html__( 'Padding top', 'xstore-core' ),
			'padding-right'  => esc_html__( 'Padding right', 'xstore-core' ),
			'padding-bottom' => esc_html__( 'Padding bottom', 'xstore-core' ),
			'padding-left'   => esc_html__( 'Padding left', 'xstore-core' ),
		),
		'cols_gap'               => esc_html__( 'Columns gap (px)', 'xstore-core' ),
		'product_view'           => esc_html__( 'Product content effect', 'xstore-core' ),
		'product_view_color'     => esc_html__( 'Hover Color Scheme', 'xstore-core' ),
		'product_img_hover'      => esc_html__( 'Image hover effect', 'xstore-core' )
	),
	'separator_label' => array(
		'main_configuration' => esc_html__( 'Main configuration', 'xstore-core' ),
		'style'              => esc_html__( 'Style', 'xstore-core' ),
		'advanced'           => esc_html__( 'Advanced', 'xstore-core' )
	),
	'description'     => array(
		'wcag_color'            => esc_html__( 'Select the text color for your content. Please choose auto color to ensure readability with your selected background-color, or switch to the "Custom Color" tab to select any other color you want.', 'xstore-core' ),
		'wcag_bg_color'         => esc_html__( 'WCAG control is designed to be used by webdesigners, web developers or web accessibility professionals to compute the contrast between two colors (background color, text color)', 'xstore-core' ) . ' <a href="https://app.contrast-finder.org/?lang=en" rel="nofollow" target="_blank" style="text-decoration: none; color: #222;">' . esc_html__( 'More details', 'xstore-core' ) . '</a>',
		'icons_style'           => esc_html__( 'There are two types of icons (bold and thin). You can easily change it for the whole website in', 'xstore-core' ) . ' <span class="et_edit" data-parent="style" data-section="bold_icons" style="color: #222; ">Icons style</span>',
		'border_color'          => esc_html__( 'You have to set up border width via Computed box above. To have correct invisible border, please set up alpha chanel to 0', 'xstore-core' ),
		'computed_box'          => esc_html__( 'You can select the margin, border-width and padding for element.', 'xstore-core' ),
		'size_bigger_attention' => esc_html__( 'Attention, if your element will have the size bigger than the column this element is in, then your element positioning may be a bit not as aspected', 'xstore-core' ),
		'sticky_logo'           => esc_html__( 'Sticky header uses the site logo by default. Upload image to set up another logo for sticky header.', 'xstore-core' ),
		'custom_image_svg'      => esc_html__( 'Upload svg icon. Install SVG Support plugin to be able to upload SVG files.', 'xstore-core' ) .
		                           '<a href="https://wordpress.org/plugins/svg-support/" rel="nofollow" target="_blank">' . esc_html__( 'Install plugin', 'xstore-core' ) . '</a>',
		'product_view'          => esc_html__( 'Choose the design type for the related products.', 'xstore-core' ),
		'product_view_color'    => esc_html__( 'Choose the color scheme for the product design with buttons on hover.', 'xstore-core' ),
		'product_img_hover'     => esc_html__( 'Choose the type of the hover effect for the image or disable it at all.', 'xstore-core' )

	)
);

$sep_style = 'display: flex; justify-content: center; align-items: center; padding: 7px 15px;margin: 0 -15px;text-align: center;font-size: 12px;line-height: 1;text-transform: uppercase; letter-spacing: 1px;background-color: #f2f2f2;color: #222;';

$is_rtl = is_rtl();

$separators = array(
	'content'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-' . ( $is_rtl ? 'right' : 'left' ) . ': 3px;">' . $strings['separator_label']['main_configuration'] . '</span></div>',
	'style'    => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-' . ( $is_rtl ? 'right' : 'left' ) . ': 3px;">' . $strings['separator_label']['style'] . '</span></div>',
	'advanced' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-star-filled"></span> <span style="padding-' . ( $is_rtl ? 'right' : 'left' ) . ': 3px;">' . $strings['separator_label']['advanced'] . '</span></div>'
);

function et_b_element_styles( $element ) {
	return array(
		'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/' . $element . '/Style-' . $element . '-icon-1.svg',
		'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/' . $element . '/Style-' . $element . '-icon-2.svg',
		'type3' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/' . $element . '/Style-' . $element . '-icon-3.svg',
	);
}

$sidebars = array(
	'without' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/full-width.svg',
	'left'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/left-sidebar.svg',
	'right'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/right-sidebar.svg',
);

$sidebars_with_inherit = array(
	'inherit' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/layout/inherit.svg',
	'without' => $sidebars['without'],
	'left'    => $sidebars['left'],
	'right'   => $sidebars['right']
);

$menu_settings = array(
	'strings'            => array(
		'label'       => array(
			'sep_type'                 => esc_html__( 'Separator type', 'xstore-core' ),
			'one_page'                 => esc_html__( 'One page menu', 'xstore-core' ),
			'menu_dropdown_full_width' => esc_html__( 'Mega menu dropdown full-width', 'xstore-core' ),
			'arrows'                   => esc_html__( 'Add arrows for 1-level menu items with dropdowns', 'xstore-core' ),
			'color'                    => esc_html__( 'Text color', 'xstore-core' ),
			'hover_color'              => esc_html__( 'Text color (hover, active)', 'xstore-core' ),
			'line_color'               => esc_html__( 'Line color (hover, active)', 'xstore-core' ),
			'dots_color'               => esc_html__( 'Separator color', 'xstore-core' ),
			'arrow_color'              => esc_html__( 'Arrow color (hover, active)', 'xstore-core' ),
			'bg_hover_color'           => esc_html__( 'Background color (hover, active)', 'xstore-core' ),
			'item_box_model'           => esc_html__( 'Computed box for the menu item', 'xstore-core' ),
			'nice_space'               => esc_html__( 'Remove spacing on sides', 'xstore-core' ),
			'border_hover_color'       => esc_html__( 'Border color (hover, active)', 'xstore-core' ),
		),
		'description' => array(
			'one_page'                 => esc_html__( 'Enable when your menu is working only for one page by anchors', 'xstore-core' ),
			'menu_dropdown_full_width' => esc_html__( 'Enable when you want to make your dropdown block full-width', 'xstore-core' ),
			'line_color'               => esc_html__( 'This option will apply on specific hover element.', 'xstore-core' ),
			'arrow_color'              => esc_html__( 'This option will apply on specific hover/active element.', 'xstore-core' ),
			'dots_color'               => esc_html__( 'This option will apply on specific element separator', 'xstore-core' ),
			'bg_hover_color'           => esc_html__( 'This option will apply on specific hover element. If you use custom type it will appeare on your items background color', 'xstore-core' ),
			'item_box_model'           => esc_html__( 'You can select the margin, border-width and padding for menu item element.', 'xstore-core' ),
		)
	),
	'style'              => array(
		'none'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-1.svg',
		'underline' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-2.svg',
		'overline'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-3.svg',
		'dots'      => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-4.svg',
		'arrow'     => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-5.svg',
		'custom'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/menu/Style-hovers-icon-custom.svg',
	),
	'separators'         => array(
		'2502' => esc_html__( 'Icon 01', 'xstore-core' ),
		'2022' => esc_html__( 'Icon 02', 'xstore-core' ),
		'2044' => esc_html__( 'Icon 03', 'xstore-core' ),
		'2016' => esc_html__( 'Icon 04', 'xstore-core' ),
		'2059' => esc_html__( 'Icon 05', 'xstore-core' ),
		'2217' => esc_html__( 'Icon 06', 'xstore-core' ),
		'2248' => esc_html__( 'Icon 07', 'xstore-core' ),
		'2299' => esc_html__( 'Icon 08', 'xstore-core' ),
		'2301' => esc_html__( 'Icon 09', 'xstore-core' ),
		'2605' => esc_html__( 'Icon 10', 'xstore-core' ),
	),
	'fonts'              => array(
		'font-family'    => '',
		'variant'        => 'regular',
		// 'font-size'      => '15px',
		// 'line-height'    => '1.5',
		'letter-spacing' => '0',
		// 'color'          => '#555',
		'text-transform' => 'inherit',
		// 'text-align'     => 'left',
	),
	'item_box_model'     => array(
		'margin-top'          => '0px',
		'margin-right'        => '0px',
		'margin-bottom'       => '0px',
		'margin-left'         => '0px',
		'border-top-width'    => '0px',
		'border-right-width'  => '0px',
		'border-bottom-width' => '0px',
		'border-left-width'   => '0px',
		'padding-top'         => '10px',
		'padding-right'       => '10px',
		'padding-bottom'      => '10px',
		'padding-left'        => '10px',
	),
	'dropdown_selectors' => '.et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown:not(.nav-sublist),
	      .et_b_header-menu.et_element-top-level .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul,
	      .et_b_header-menu.et_element-top-level .item-design-mega-menu .nav-sublist-dropdown:not(.nav-sublist),

	      .site-header .widget_nav_menu .menu > li .sub-menu,

	      .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown:not(.nav-sublist),
	      .site-header .etheme_widget_menu .item-design-dropdown .nav-sublist-dropdown ul > li .nav-sublist ul,
	      .site-header .etheme_widget_menu .item-design-mega-menu .nav-sublist-dropdown:not(.nav-sublist)'
);

$choices = array(
	'alignment'                => array(
		'start'  => '<span class="dashicons dashicons-editor-alignleft"></span>',
		'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
		'end'    => '<span class="dashicons dashicons-editor-alignright"></span>',
	),
	'alignment2'               => array(
		'flex-start' => '<span class="dashicons dashicons-editor-alignleft"></span>',
		'center'     => '<span class="dashicons dashicons-editor-aligncenter"></span>',
		'flex-end'   => '<span class="dashicons dashicons-editor-alignright"></span>',
	),
	'direction'                => array(
		'type1' => array(
			'hor' => esc_html__( 'Horizontal', 'xstore-core' ),
			'ver' => esc_html__( 'Vertical', 'xstore-core' ),
		),
		'type2' => array(
			'column' => 'column',
			'row'    => 'row',
		),
	),
	'dropdown_position'        => array(
		'left'   => esc_html__( 'Left side', 'xstore-core' ),
		'right'  => esc_html__( 'Right side', 'xstore-core' ),
		'custom' => esc_html__( 'Custom', 'xstore-core' )
	),
	'header_vertical_elements' => array(
		'logo'           => esc_html__( 'Logo', 'xstore-core' ),
		'menu'           => esc_html__( 'Menu', 'xstore-core' ),
		'wishlist'       => esc_html__( 'Wishlist', 'xstore-core' ),
		'cart'           => esc_html__( 'Cart', 'xstore-core' ),
		'account'        => esc_html__( 'Account', 'xstore-core' ),
		'header_socials' => esc_html__( 'Socials', 'xstore-core' ),
		'html_block1'    => esc_html__( 'HTML block 1', 'xstore-core' ),
		'html_block2'    => esc_html__( 'HTML block 2', 'xstore-core' ),
		'html_block3'    => esc_html__( 'HTML block 3', 'xstore-core' ),
	),
	'border_style'             => array(
		'dotted' => esc_html__( 'Dotted', 'xstore-core' ),
		'dashed' => esc_html__( 'Dashed', 'xstore-core' ),
		'solid'  => esc_html__( 'Solid', 'xstore-core' ),
		'double' => esc_html__( 'Double', 'xstore-core' ),
		'groove' => esc_html__( 'Groove', 'xstore-core' ),
		'ridge'  => esc_html__( 'Ridge', 'xstore-core' ),
		'inset'  => esc_html__( 'Inset', 'xstore-core' ),
		'outset' => esc_html__( 'Outset', 'xstore-core' ),
		'none'   => esc_html__( 'None', 'xstore-core' ),
		'hidden' => esc_html__( 'Hidden', 'xstore-core' ),
	),
	'colors'                   => array(
		'current' => esc_html__( 'Default', 'xstore-core' ),
		'custom'  => esc_html__( 'Custom', 'xstore-core' ),
	),
	'product_types'            => array(
		'grid'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/grid.svg',
		'slider' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/slider.svg',
		'widget' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/widget.svg',
	)
);

$choices['alignment_with_inherit']  = $choices['alignment'];
$choices['alignment2_with_inherit'] = $choices['alignment2'];

$choices['alignment_with_inherit']['inherit'] = $choices['alignment2_with_inherit']['inherit'] = esc_html__( 'Inherit', 'xstore-core' );

$box_models = array(
	'empty' => array(
		'margin-top'          => '0px',
		'margin-right'        => '0px',
		'margin-bottom'       => '0px',
		'margin-left'         => '0px',
		'border-top-width'    => '0px',
		'border-right-width'  => '0px',
		'border-bottom-width' => '0px',
		'border-left-width'   => '0px',
		'padding-top'         => '0px',
		'padding-right'       => '0px',
		'padding-bottom'      => '0px',
		'padding-left'        => '0px',
	)
);

$box_models['col_paddings']                  = $box_models['empty'];
$box_models['col_paddings']['padding-right'] = $box_models['col_paddings']['padding-left'] = '15px';

function box_model_output( $selector ) {
	$properties = array(
		'margin-top',
		'margin-right',
		'margin-bottom',
		'margin-left',

		'padding-top',
		'padding-right',
		'padding-bottom',
		'padding-left',

		'border-top-width',
		'border-right-width',
		'border-bottom-width',
		'border-left-width',
	);

	$return = array();

	foreach ( $properties as $key ) {
		$return[] = array(
			'choice'   => $key,
			'element'  => $selector,
			'type'     => 'css',
			'property' => $key
		);
	}

	return $return;
}

$product_settings = array(
	'view'       => array(
		'disable' => esc_html__( 'Disable', 'xstore-core' ),
		'default' => esc_html__( 'Default', 'xstore-core' ),
		'mask3'   => esc_html__( 'Buttons on hover middle', 'xstore-core' ),
		'mask'    => esc_html__( 'Buttons on hover bottom', 'xstore-core' ),
		'mask2'   => esc_html__( 'Buttons on hover right', 'xstore-core' ),
		'info'    => esc_html__( 'Information mask', 'xstore-core' ),
		'booking' => esc_html__( 'Booking', 'xstore-core' ),
		'light'   => esc_html__( 'Light', 'xstore-core' ),
		'inherit' => esc_html__( 'Inherit', 'xstore-core' ),
//			'custom'  => esc_html__( 'Custom', 'xstore-core' )
	),
	'view_color' => array(
		'white'       => esc_html__( 'White', 'xstore-core' ),
		'dark'        => esc_html__( 'Dark', 'xstore-core' ),
		'transparent' => esc_html__( 'Transparent', 'xstore-core' ),
		'inherit'     => esc_html__( 'Inherit', 'xstore-core' )
	),
	'img_hover'  => array(
		'disable' => esc_html__( 'Disable', 'xstore-core' ),
		'swap'    => esc_html__( 'Swap', 'xstore-core' ),
		'slider'  => esc_html__( 'Images Slider', 'xstore-core' ),
        'carousel'=> esc_html__( 'Automatic Carousel', 'xstore-core' ),
		'inherit' => esc_html__( 'Inherit', 'xstore-core' )
	)
);

$icons = array(
	'simple'  => array(
		'et_icon-delivery'        => esc_html__( 'Delivery', 'xstore-core' ),
		'et_icon-coupon'          => esc_html__( 'Coupon', 'xstore-core' ),
		'et_icon-calendar'        => esc_html__( 'Calendar', 'xstore-core' ),
		'et_icon-compare'         => esc_html__( 'Compare', 'xstore-core' ),
		'et_icon-checked'         => esc_html__( 'Checked', 'xstore-core' ),
		'et_icon-chat'            => esc_html__( 'Chat', 'xstore-core' ),
		'et_icon-phone'           => esc_html__( 'Phone', 'xstore-core' ),
		'et_icon-exclamation'     => esc_html__( 'Exclamation', 'xstore-core' ),
		'et_icon-gift'            => esc_html__( 'Gift', 'xstore-core' ),
		'et_icon-heart'           => esc_html__( 'Heart', 'xstore-core' ),
		'et_icon-message'         => esc_html__( 'Message', 'xstore-core' ),
		'et_icon-internet'        => esc_html__( 'Internet', 'xstore-core' ),
		'et_icon-account'         => esc_html__( 'Account', 'xstore-core' ),
		'et_icon-sent'            => esc_html__( 'Sent', 'xstore-core' ),
		'et_icon-home'            => esc_html__( 'Home', 'xstore-core' ),
		'et_icon-shop'            => esc_html__( 'Shop', 'xstore-core' ),
        'et_icon-shopping-basket' => esc_html__( 'Basket', 'xstore-core' ),
		'et_icon-shopping-bag'    => esc_html__( 'Bag', 'xstore-core' ),
		'et_icon-shopping-cart'   => esc_html__( 'Cart', 'xstore-core' ),
		'et_icon-shopping-cart-2' => esc_html__( 'Cart 2', 'xstore-core' ),
		'et_icon-burger'          => esc_html__( 'Burger', 'xstore-core' ),
		'et_icon-star'            => esc_html__( 'Star', 'xstore-core' ),
		'et_icon-time'            => esc_html__( 'Time', 'xstore-core' ),
		'et_icon-zoom'            => esc_html__( 'Search', 'xstore-core' ),
		'et_icon-size'            => esc_html__( 'Size', 'xstore-core' ),
		'et_icon-more'            => esc_html__( 'More', 'xstore-core' ),
		'none'                    => esc_html__( 'Without Icon', 'xstore-core' ),
	),
	'socials' => array(
		'et_icon-behance'     => esc_html__( 'Behance', 'xstore-core' ),
		'et_icon-facebook'    => esc_html__( 'Facebook', 'xstore-core' ),
		'et_icon-houzz'       => esc_html__( 'Houzz', 'xstore-core' ),
		'et_icon-instagram'   => esc_html__( 'Instagram', 'xstore-core' ),
		'et_icon-linkedin'    => esc_html__( 'Linkedin', 'xstore-core' ),
		'et_icon-pinterest'   => esc_html__( 'Pinterest', 'xstore-core' ),
		'et_icon-rss'         => esc_html__( 'Rss', 'xstore-core' ),
		'et_icon-skype'       => esc_html__( 'Skype', 'xstore-core' ),
		'et_icon-snapchat'    => esc_html__( 'Snapchat', 'xstore-core' ),
		'et_icon-tripadvisor' => esc_html__( 'Tripadvisor', 'xstore-core' ),
		'et_icon-telegram'    => esc_html__( 'Telegram', 'xstore-core' ),
		'et_icon-tumblr'      => esc_html__( 'Tumblr', 'xstore-core' ),
		'et_icon-twitter'     => esc_html__( 'Twitter', 'xstore-core' ),
		'et_icon-vimeo'       => esc_html__( 'Vimeo', 'xstore-core' ),
		'et_icon-etsy'        => esc_html__( 'Etsy', 'xstore-core' ),
		'et_icon-tik-tok'     => esc_html__( 'Tik-tok', 'xstore-core' ),
		'et_icon-twitch'      => esc_html__( 'Twitch', 'xstore-core' ),
		'et_icon-untapped'    => esc_html__( 'Untapped', 'xstore-core' ),
		'et_icon-vk'          => esc_html__( 'Vk', 'xstore-core' ),
		'et_icon-whatsapp'    => esc_html__( 'Whatsapp', 'xstore-core' ),
		'et_icon-youtube'     => esc_html__( 'Youtube', 'xstore-core' ),
		'et_icon-discord'     => esc_html__( 'Discord', 'xstore-core' ),
		'et_icon-reddit'      => esc_html__( 'Reddit', 'xstore-core' ),
		'et_icon-strava'      => esc_html__( 'Strava', 'xstore-core' ),
		'et_icon-patreon'     => esc_html__( 'Patreon', 'xstore-core' ),
		'et_icon-line'        => esc_html__( 'Line', 'xstore-core' ),
		'et_icon-kofi'        => esc_html__( 'Kofi', 'xstore-core' ),
		'et_icon-dribbble'    => esc_html__( 'Dribbble', 'xstore-core' ),
		'et_icon-cafecito'    => esc_html__( 'Cafecito', 'xstore-core' ),
	)
);