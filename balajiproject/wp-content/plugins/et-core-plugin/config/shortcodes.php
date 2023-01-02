<?php
/**
 *	Register shortcodes 
 */
add_filter( 'etc/add/shortcodes', 'etc_add_shortcodes' );
function etc_add_shortcodes( $shortcodes ) {

	if ( ! function_exists( 'etheme_load_shortcode' ) ) {
		return $shortcodes;
	}

	$shortcodes['banner'] = array(
		'name' 		=> 'banner',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Banner',
		'function' 	=> 'banner_shortcode',
	);

	$shortcodes['carousel'] = array(
		'name' 		=> 'etheme_carousel',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Carousel',
		'function' 	=> 'carousel_shortcode',
	);

	$shortcodes['brands'] = array(
		'name' 		=> 'etheme_brands',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Brands',
		'function' 	=> 'brands_shortcode',
	);

	$shortcodes['category'] = array(
		'name' 		=> 'et_category',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Category',
		'function' 	=> 'category_shortcode',
	);

	$shortcodes['categories'] = array(
		'name' 		=> 'etheme_categories',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Categories',
		'function' 	=> 'categories_shortcode',
	);

	$shortcodes['categories-lists'] = array(
		'name' 		=> 'etheme_categories_lists',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Categories_lists',
		'function' 	=> 'categories_lists_shortcode',
	);

	$shortcodes['follow'] = array(
		'name' 		=> 'follow',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Follow',
		'function' 	=> 'follow_shortcode',
	);

	$shortcodes['icon-box'] = array(
		'name' 		=> 'icon_box',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Icon_Box',
		'function' 	=> 'icon_box_shortcode',
	);

	$shortcodes['instagram'] = array(
		'name' 		=> 'instagram',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Instagram',
		'function' 	=> 'instagram_shortcode',
	);

	$shortcodes['looks'] = array(
		'name' 		=> 'et_looks',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Looks',
		'function' 	=> 'looks_shortcode',
	);

	$shortcodes['products'] = array(
		'name' 		=> 'etheme_products',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Products',
		'function' 	=> 'products_shortcode',
	);

	$shortcodes['team-member'] = array(
		'name' 		=> 'team_member',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Team_Member',
		'function' 	=> 'team_member_shortcode',
	);

	$shortcodes['the-look'] = array(
		'name' 		=> 'et_the_look',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\The_Look',
		'function' 	=> 'the_look_shortcode',
	);

	$shortcodes['special-offer'] = array(
		'name' 		=> 'et_offer',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Special_Offer',
		'function' 	=> 'offer_shortcode',
	);

	$shortcodes['title'] = array(
		'name' 		=> 'title',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Title',
		'function' 	=> 'title_shortcode',
	);

	$shortcodes['twitter'] = array(
		'name' 		=> 'twitter',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Twitter',
		'function' 	=> 'twitter_shortcode',
	);

	$shortcodes['quick-view'] = array(
		'name' 		=> 'quick_view',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Quick_View',
		'function' 	=> 'quick_view_shortcodes',
	);

	$shortcodes['button'] = array(
		'name' 		=> 'button',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Button',
		'function' 	=> 'btn_shortcode',
	);

	$shortcodes['counter'] = array(
		'name' 		=> 'counter',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Counter',
		'function' 	=> 'counter_shortcode',
	);

	$shortcodes['dropcap'] = array(
		'name' 		=> 'dropcap',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Dropcap',
		'function' 	=> 'dropcap_shortcode',
	);

	$shortcodes['mark'] = array(
		'name' 		=> 'mark',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Mark',
		'function' 	=> 'mark_shortcode',
	);

	$shortcodes['blockquote'] = array(
		'name' 		=> 'blockquote',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Blockquote',
		'function' 	=> 'blockquote_shortcode',
	);

	$shortcodes['checklist'] = array(
		'name' 		=> 'checklist',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Checklist',
		'function' 	=> 'checklist_shortcode',
	);


	$shortcodes['countdown'] = array(
		'name' 		=> 'countdown',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Countdown',
		'function' 	=> 'countdown_shortcode',
	);

	$shortcodes['qrcode'] = array(
		'name' 		=> 'qrcode',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\QRCode',
		'function' 	=> 'qrcode_shortcode',
	);

	$shortcodes['tooltip'] = array(
		'name' 		=> 'tooltip',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\ToolTip',
		'function' 	=> 'tooltip_shortcode',
	);
	
	$shortcodes['share'] = array(
		'name' 		=> 'share',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Share',
		'function' 	=> 'share_shortcode',
	);

	$shortcodes['static-block'] = array(
		'name' 		=> 'block',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Static_Block',
		'function' 	=> 'block_shortcode',
	);

	$shortcodes['fancy-button'] = array(
		'name' 		=> 'et_fancy_button',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Fancy_Button',
		'function' 	=> 'fancy_button_shortcode',
	);

	$shortcodes['blog'] = array(
		'name' 		=> 'et_blog',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Blog',
		'function' 	=> 'blog_shortcode',
	);

	$shortcodes['blog-timeline'] = array(
		'name' 		=> 'et_blog_timeline',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Blog_Timeline',
		'function' 	=> 'blog_timeline_shortcode',
	);

	$shortcodes['blog-list'] = array(
		'name' 		=> 'et_blog_list',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Blog_List',
		'function' 	=> 'blog_list_shortcode',
	);

	$shortcodes['blog-carousel'] = array(
		'name' 		=> 'et_blog_carousel',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Blog_Carousel',
		'function' 	=> 'blog_carousel_shortcode',
	);

	$shortcodes['menu'] = array(
		'name' 		=> 'menu',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Menu',
		'function' 	=> 'menu_shortcode',
	);

	$shortcodes['brands_list'] = array(
		'name' 		=> 'etheme_brands_list',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Brands_List',
		'function' 	=> 'brands_list_shortcode',
	);

	$shortcodes['post-meta'] = array(
		'name' 		=> 'etheme_post_meta',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Post_Meta',
		'function' 	=> 'post_meta_shortcode',
	);

	$shortcodes['menu-list'] = array(
		'name' 		=> 'et_menu_list',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Menu_List',
		'function' 	=> 'menu_list_shortcode',
	);

	$shortcodes['menu-list-item'] = array(
		'name' 		=> 'et_menu_list_item',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Menu_List_Item',
		'function' 	=> 'menu_list_item_shortcode',
	);
	
	$shortcodes['icons-list'] = array(
		'name' 		=> 'et_icons_list',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Icons_List',
		'function' 	=> 'icons_list_shortcode',
	);

	$shortcodes['et-slider'] = array(
		'name' 		=> 'etheme_slider',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Slider',
		'function' 	=> 'slider_shortcode',
	);

	$shortcodes['et-slider-item'] = array(
		'name' 		=> 'etheme_slider_item',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Slider_Item',
		'function' 	=> 'slider_item_shortcode',
	);
	
	$shortcodes['scroll-text'] = array(
		'name' 		=> 'etheme_scroll_text',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Scroll_Text',
		'function' 	=> 'scroll_text_shortcode',
	);

	$shortcodes['scroll-text-item'] = array(
		'name' 		=> 'etheme_scroll_text_item',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Scroll_Text_Item',
		'function' 	=> 'scroll_text_item_shortcode',
	);

	$shortcodes['portfolio'] = array(
		'name' 		=> 'portfolio',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Portfolio',
		'function' 	=> 'portfolio_shortcode',
	);

	$shortcodes['portfolio-recent'] = array(
		'name' 		=> 'portfolio_recent',
		'class' 	=> 'ETC\App\Controllers\Shortcodes\Portfolio_Recent',
		'function' 	=> 'portfolio_recent_shortcode',
	);

	return $shortcodes;
}