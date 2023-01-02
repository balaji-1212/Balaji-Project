<?php
/**
 *	Register routes 
 */
add_filter( 'etc/add/elementor/widgets', 'etc_elementor_widgets_routes' );
function etc_elementor_widgets_routes( $routes ) {
	
	$check_function = function_exists( 'etheme_get_option' );
	
	// sorted new
	$routes[] = array(
		'ETC\App\Controllers\Elementor\General\Advanced_Headline', // new
		'ETC\App\Controllers\Elementor\General\Animated_Headline', // new
		'ETC\App\Controllers\Elementor\General\Banner',
		'ETC\App\Controllers\Elementor\General\Static_Block',
		'ETC\App\Controllers\Elementor\General\Banner_Carousel',
		'ETC\App\Controllers\Elementor\General\FlipBox', // new
		'ETC\App\Controllers\Elementor\General\Team_Member',
		'ETC\App\Controllers\Elementor\General\Testimonials',
		'ETC\App\Controllers\Elementor\General\Blockquote', // new
		'ETC\App\Controllers\Elementor\General\Countdown', // new
		'ETC\App\Controllers\Elementor\General\Circle_Progress_Bar', // new
		'ETC\App\Controllers\Elementor\General\Linear_Progress_Bar',
	);
	
	$routes[] = array(
		'ETC\App\Controllers\Elementor\General\Categories',
		'ETC\App\Controllers\Elementor\General\Categories_lists',
		'ETC\App\Controllers\Elementor\General\Custom_Product_Categories_Masonry',
	);
	
	$routes[] = array(
		'ETC\App\Controllers\Elementor\General\Products',
		'ETC\App\Controllers\Elementor\General\Product_List',
		'ETC\App\Controllers\Elementor\General\Product_Grid',
		'ETC\App\Controllers\Elementor\General\Product_Carousel',
		'ETC\App\Controllers\Elementor\General\Custom_Products_Masonry',
		'ETC\App\Controllers\Elementor\General\Product_Menu_Layout',
		'ETC\App\Controllers\Elementor\General\Product_Filters', // -
		'ETC\App\Controllers\Elementor\General\Add_To_Cart',
		'ETC\App\Controllers\Elementor\General\Text_Button',
		'ETC\App\Controllers\Elementor\General\PayPal',
		'ETC\App\Controllers\Elementor\General\Advanced_Tabs',
		'ETC\App\Controllers\Elementor\General\Search', // -
		'ETC\App\Controllers\Elementor\General\Price_Table',
		'ETC\App\Controllers\Elementor\General\HotSpot',
	);
	
	if ( $check_function && get_theme_mod( 'enable_brands', 1 ) ) {
		$routes[] = array(
			'ETC\App\Controllers\Elementor\General\Brands',
			// 'ETC\App\Controllers\Elementor\General\Brands_List',
		);
	}
	
	$routes[] = array(
		'ETC\App\Controllers\Elementor\General\Blog_Carousel',
		// 'ETC\App\Controllers\Elementor\General\Blog',
		// 'ETC\App\Controllers\Elementor\General\Blog_List',
		// 'ETC\App\Controllers\Elementor\General\Blog_Timeline',
		'ETC\App\Controllers\Elementor\General\Custom_Posts_Masonry',
		'ETC\App\Controllers\Elementor\General\Posts',
		'ETC\App\Controllers\Elementor\General\Posts_Chess',
		'ETC\App\Controllers\Elementor\General\Posts_Tabs',
		'ETC\App\Controllers\Elementor\General\Posts_Carousel',
		'ETC\App\Controllers\Elementor\General\Posts_Timeline',
		'ETC\App\Controllers\Elementor\General\Vertical_Timeline', // new
		'ETC\App\Controllers\Elementor\General\Horizontal_Timeline', // -
		'ETC\App\Controllers\Elementor\General\Tabs',
		'ETC\App\Controllers\Elementor\General\Slider',
		'ETC\App\Controllers\Elementor\General\Slides',
		'ETC\App\Controllers\Elementor\General\Portfolio',
		'ETC\App\Controllers\Elementor\General\Gallery',
		'ETC\App\Controllers\Elementor\General\Media_Carousel', // -
		'ETC\App\Controllers\Elementor\General\Menu_List',
		'ETC\App\Controllers\Elementor\General\Follow',
		'ETC\App\Controllers\Elementor\General\Instagram',
		'ETC\App\Controllers\Elementor\General\Google_Map',
		'ETC\App\Controllers\Elementor\General\Contact_Form_7',
		'ETC\App\Controllers\Elementor\General\Image_Comparison',
		'ETC\App\Controllers\Elementor\General\Icon_list',
		'ETC\App\Controllers\Elementor\General\Lottie_Animation',
		'ETC\App\Controllers\Elementor\General\Icon_Box',
		'ETC\App\Controllers\Elementor\General\Icon_Box_Carousel',
		'ETC\App\Controllers\Elementor\General\Scroll_Progress',
		'ETC\App\Controllers\Elementor\General\Three_Sixty_Product_Viewer',
		
		'ETC\App\Controllers\Elementor\General\Modal_Popup',
        'ETC\App\Controllers\Elementor\General\Content_Switcher',
        'ETC\App\Controllers\Elementor\General\Toggle_Text',

    );
	
	$routes[] = array(
		'ETC\App\Controllers\Elementor\General\Facebook_Comments',
		'ETC\App\Controllers\Elementor\General\Facebook_Embed',
		'ETC\App\Controllers\Elementor\General\Twitter_Feed',
		'ETC\App\Controllers\Elementor\General\Twitter_Feed_Slider',
	);

	return $routes;
}

/**
 *	Register modules 
 */
add_filter( 'etc/add/elementor/modules', 'etc_elementor_modules' );
function etc_elementor_modules( $modules ) {
	
	$modules['general'] = array(
		'ETC\App\Controllers\Elementor\Modules\General'
	);
	$modules['parallax'] = array(
		'ETC\App\Controllers\Elementor\Modules\Parallax'
	);
	$modules['tooltip'] = array(
		'ETC\App\Controllers\Elementor\Modules\Tooltip'
	);
	$modules['conditional_display'] = array(
		'ETC\App\Controllers\Elementor\Modules\Conditional_Display'
	);
	$modules['sticky_column'] = array(
		'ETC\App\Controllers\Elementor\Modules\Sticky_Column'
	);
	$modules['grid_layer'] = array(
		'ETC\App\Controllers\Elementor\Modules\Grid_Layer'
	);
	$modules['css'] = array(
		'ETC\App\Controllers\Elementor\Modules\CSS'
	);

	return $modules;
}

/**
 *	Register controls 
 */
add_filter( 'etc/add/elementor/controls', 'etc_elementor_controls' );
function etc_elementor_controls( $controls ) {

	$controls['etheme-ajax-product'] = array(
		'class'	=>	'ETC\App\Controllers\Elementor\Controls\Ajax_Product',
	);

	return $controls;
}
