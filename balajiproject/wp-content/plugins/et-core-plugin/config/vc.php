<?php
/**
 *	Register shortcodes 
 */
add_filter( 'etc/add/vc', 'etc_add_vc' );
function etc_add_vc( $vc ) {

	if ( ! function_exists( 'etheme_load_shortcode' ) ) {
		return $vc;
	}

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Banner',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Blog',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Blog_Carousel',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Blog_List',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Blog_Timeline',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Brands',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Brands_List',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Carousel',
		'function' 	=> 'hooks',
		'extra' 	=> 'carousel',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Categories',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Categories_lists',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Category',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Countdown',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Custom_Tabs',
		'function' 	=> 'hooks',
		'extra' 	=> 'custom-tabs',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Fancy_Button',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Follow',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Icon_Box',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Instagram',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Looks',
		'function' 	=> 'hooks',
		'extra' 	=> 'looks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Menu',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Menu_List',
		'function' 	=> 'hooks',
		'extra' 	=> 'menu-list',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Menu_List_Item',
		'function' 	=> 'hooks',
		'extra' 	=> 'menu-list-item',
	);
	
	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Icons_List',
		'function' 	=> 'hooks',
		'extra' 	=> 'icons-list',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Portfolio',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Portfolio_Recent',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Products',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Scroll_Text',
		'function' 	=> 'hooks',
		'extra' 	=> 'scroll-text',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Scroll_Text_Item',
		'function' 	=> 'hooks',
		'extra' 	=> 'scroll-text-item',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Slider',
		'function' 	=> 'hooks',
		'extra' 	=> 'slider',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Slider_Item',
		'function' 	=> 'hooks',
		'extra' 	=> 'slider-item',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Special_Offer',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Tabs',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Team_Member',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Testimonials',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\The_Look',
		'function' 	=> 'hooks',
		'extra' 	=> 'the-look',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Twitter',
		'function' 	=> 'hooks',
	);

	$vc[] = array(
		'class' 	=> 'ETC\App\Controllers\Vc\Title',
		'function' 	=> 'hooks',
	);

	if ( class_exists('YITH_WCWL_Shortcode') ) {
		$vc[] = array(
			'class'		=> 'ETC\App\Controllers\Vc\Wishlist',
			'function' 	=> 'hooks',
		);
	}
	
	return $vc;
}