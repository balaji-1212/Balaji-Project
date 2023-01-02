<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');


if(!function_exists('etheme_page_config')) {
	function etheme_page_config() {
		$layout = array(
			'sidebar' => 'left',
			'sidebar-mobile' => 'bottom',
			'sidebar-size' => 3,
			'content-size' => 9,
			'heading' => true,
			'slider' => false,
			'banner' => false,
			'sidebar-name' => '',
			'breadcrumb' => 'default',
			'widgetarea' => ''
		);

		$layout = apply_filters('etheme_page_config', $layout);

		$page = (array) get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$page_id = isset($page['id']) ? $page['id'] : false;

		$posts_page_id = get_option( 'page_for_posts' );

		if ( $page_id === $posts_page_id && etheme_get_option('only_blog_sidebar', 0) ) {
			$layout['sidebar'] = etheme_get_option('blog_sidebar', 'right');
			$layout['sidebar-mobile'] = etheme_get_option('blog_sidebar_for_mobile', 'bottom');
		}
        elseif ( !etheme_get_option('only_blog_sidebar', 0) ) {
			$layout['sidebar'] = etheme_get_option('blog_sidebar', 'right');
			$layout['sidebar-mobile'] = etheme_get_option('blog_sidebar_for_mobile', 'bottom');
		}
		else {
			$layout['sidebar'] = 'without';
		}
		if ( is_singular('post') ) {
			$layout['sidebar'] = etheme_get_option('post_sidebar', 'right');
		}

		// Get settings from Theme Options
		$layout['breadcrumb'] = etheme_get_option('breadcrumb_type', 'left2');
		$layout['bc_color'] = etheme_get_option('breadcrumb_color', 'dark');
		$layout['bc_effect'] = etheme_get_option('breadcrumb_effect', 'mouse');
		$layout['product_layout'] = etheme_get_option('single_layout', 'default');

		if(get_query_var('et_is-woocommerce', false)) {
			$grid_sidebar = get_query_var('et_grid-sidebar');
			if ( get_query_var('et_is-woocommerce-archive', false) ) {
				$layout['sidebar'] = get_query_var('et_cat-sidebar', $grid_sidebar);
				$layout['sidebar-mobile'] = etheme_get_option( 'sidebar_for_mobile', 'off_canvas' );
			}
		}

		// Get specific custom options from meta boxes for this $page_id

		$page_breadcrumb = etheme_get_custom_field('breadcrumb_type', $page_id);
		$breadcrumb_effect = etheme_get_custom_field('breadcrumb_effect', $page_id);
		$page_sidebar = etheme_get_custom_field('sidebar_state', $page_id);
		$sidebar_width = etheme_get_custom_field('sidebar_width', $page_id);
		$widgetarea = etheme_get_custom_field('widget_area', $page_id);
		$slider = etheme_get_custom_field('page_slider', $page_id);
		$banner = etheme_get_custom_field('page_banner', $page_id);
		$heading = etheme_get_custom_field('page_heading', $page_id);
		$single_layout = etheme_get_custom_field('single_layout');
		$product_disable_sidebar = etheme_get_custom_field('disable_sidebar');

		if(!empty($page_sidebar) && $page_sidebar != 'default') {
			$layout['sidebar'] = $page_sidebar;
		}

		if(!empty($sidebar_width) && $sidebar_width != 'default') {
			$layout['sidebar-size'] = $sidebar_width;
		}

		if(!empty($page_breadcrumb) && $page_breadcrumb != 'inherit') {
			$layout['breadcrumb'] = $page_breadcrumb;
		}

		if(!empty($breadcrumb_effect) && $breadcrumb_effect != 'inherit') {
			$layout['bc_effect'] = $breadcrumb_effect;
		}

		if(!empty($widgetarea) && $widgetarea != 'default') {
			$layout['widgetarea'] = $widgetarea;
		}

		if(!empty($slider) && $slider != 'no_slider') {
			$layout['slider'] = $slider;
		}

		if(!empty($banner) ) {
			$layout['banner'] = $banner;
		}

		if(!empty($heading) && $heading != 'enable') {
			$layout['heading'] = $heading;
		}


		if(!empty($single_layout) && $single_layout != 'standard') {
			$layout['product_layout'] = $single_layout;
		}

		// Thats all about custom options for the particular page

		if(get_query_var('et_is-woocommerce', false) && get_query_var('is_single_product', false) ) {
			if ( !get_query_var('etheme_single_product_builder', false) ) {
				$layout['sidebar'] = etheme_get_option('single_sidebar', 'without');
			}
			if( $product_disable_sidebar ) {
				$layout['sidebar'] = 'without';
			}
			if ( get_query_var('etheme_single_product_builder', false) ) {
				$layout['sidebar'] = etheme_get_option('single_product_sidebar_et-desktop', 'without');
				$single_product_builder_breadcrumbs = get_theme_mod( 'product_breadcrumbs_style_et-desktop', 'left2' );
				if ( $single_product_builder_breadcrumbs != 'inherit' ) {
					$layout['breadcrumb'] = $single_product_builder_breadcrumbs;
				}
			}
		}
		
		if ( is_search() ) {
			$search_sidebar = etheme_get_option('search_page_sidebar_et-desktop', 'without');
			if ( $search_sidebar != 'inherit' ) {
				$layout['sidebar'] = $search_sidebar;
				if ( $search_sidebar == 'without' ) $layout['sidebar-size'] = 0;
			}
		}

		if(!$layout['sidebar'] || in_array($layout['sidebar'], array('without', 'no_sidebar', 'off_canvas')) ) {
			$layout['sidebar-size'] = 0;
		}

		// Remove sidebar on login page
		if( function_exists( 'is_account_page' ) && is_account_page() && ! get_query_var( 'et_is-loggedin', false) ) {
			$layout['sidebar-size'] = 0;
		}

		if($layout['sidebar-size'] == 0 && $layout['sidebar'] != 'off_canvas') {
			$layout['sidebar'] = 'without';
		}

		$layout['content-size'] = 12 - $layout['sidebar-size'];

		$layout['sidebar-class'] = 'col-md-' . $layout['sidebar-size'];
		$layout['content-class'] = 'col-md-' . $layout['content-size'];

		if ( $layout['sidebar'] == 'off_canvas' ) {
			$layout['sidebar-class'] .= ' et-mini-content et-content-left';
		}

		if($layout['sidebar'] == 'left') {
			$layout['sidebar-class'] .= ' col-md-pull-' . $layout['content-size'];
			$layout['content-class'] .= ' col-md-push-' . $layout['sidebar-size'];
		}

		if ( in_array($layout['sidebar'], array('left', 'right', 'off_canvas')) ) {
			$layout['sidebar-class'] .= ' sidebar-enabled ';
		}

		return apply_filters( 'etheme_page_config', $layout );
	}
}

if(!function_exists('etheme_get_page_id')) {
	function etheme_get_page_id( $singular = false ) {
		global $post;

		$page = array(
			'id' => 0,
			'type' => 'page'
		);

		if(isset($post->ID) && is_singular('post')) {
			$page = array(
				'id' => $post->ID,
				'type' => ( $singular ? 'post' : 'blog' )
			);
		} else if( ( get_query_var('et_portfolio-page', '') != '' && is_page( get_query_var('et_portfolio-page', '') ) ) || get_post_type() == 'etheme_portfolio' || is_singular( 'etheme_portfolio' ) || get_query_var('portfolio_category') ) {
			$page = array(
				'id' => get_query_var('et_portfolio-page', ''),
				'type' => 'portfolio'
			);
		} else if( is_home() || is_archive('post') || is_search() ) {
			$page = array(
				'id' => $id = get_option( 'page_for_posts' ),
				'type' => 'blog'
			);
		} else if(isset($post->ID) && is_singular('page')) {
			$page = array(
				'id' => $post->ID,
				'type' => 'page'
			);
		}

		if ( get_query_var('et_is-woocommerce-archive', false) || get_query_var('is_single_product', false)) {
			$page = array(
				'id' => get_option('woocommerce_shop_page_id'),
				'type' => ( ( $singular && is_singular( "product" ) ) ? 'product' : 'shop' )
			);
		}

		return $page;
	}
}
// **********************************************************************// 
// ! Register Sidebars
// **********************************************************************// 

if(function_exists('register_sidebar')) {

	if(!function_exists('etheme_sidebars')) {

		add_action('after_setup_theme', 'etheme_sidebars');

		function etheme_sidebars(){
			register_sidebar(array(
				'name' => esc_html__('Main Sidebar', 'xstore'),
				'id' => 'main-sidebar',
				'description' => esc_html__('The main sidebar area', 'xstore'),
				'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
				'after_widget' => '</div><!-- //sidebar-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			register_sidebar(array(
				'name' => esc_html__('Left side top bar area', 'xstore'),
				'id' => 'languages-sidebar',
				'description' => esc_html__('Can be used for placing languages switcher of some contacts information.', 'xstore'),
				'before_widget' => '<div id="%1$s" class="topbar-widget %2$s">',
				'after_widget' => '</div><!-- //topbar-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			register_sidebar(array(
				'name' => esc_html__('Right side top bar area', 'xstore'),
				'id' => 'top-bar-right',
				'before_widget' => '<div id="%1$s" class="topbar-widget %2$s">',
				'after_widget' => '</div><!-- //topbar-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			register_sidebar(array(
				'name' => esc_html__('Mobile sidebar area', 'xstore'),
				'id' => 'mobile-sidebar',
				'before_widget' => '<div id="%1$s" class="mobile-sidebar-widget %2$s">',
				'after_widget' => '</div><!-- //mobile-sidebar-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			register_sidebar(array(
				'name' => esc_html__('Top panel', 'xstore'),
				'id' => 'top-panel',
				'before_widget' => '<div id="%1$s" class="top-panel-widget %2$s">',
				'after_widget' => '</div><!-- //top-panel-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			register_sidebar(array(
				'name' => esc_html__('Header banner', 'xstore'),
				'id' => 'header-banner',
				'before_widget' => '<div id="%1$s" class="header-banner %2$s">',
				'after_widget' => '</div><!-- //header-banner-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			if(class_exists('WooCommerce')) {
				register_sidebar(array(
					'name' => esc_html__('Shop Sidebar', 'xstore'),
					'id' => 'shop-sidebar',
					'description' => esc_html__('Shop page widget area', 'xstore'),
					'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
					'after_widget' => '</div><!-- //sidebar-widget -->',
					'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
					'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
				));

				register_sidebar(array(
					'name' => esc_html__('Shop filters', 'xstore'),
					'id' => 'shop-filters-sidebar',
					'description' => esc_html__('Widget area that appears above the products on Shop page', 'xstore'),
					'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
					'after_widget' => '</div><!-- //sidebar-widget -->',
					'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
					'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
				));

				register_sidebar(array(
					'name' => esc_html__('After the products', 'xstore'),
					'id' => 'shop-after-products',
					'description' => esc_html__('Widget area that appears after the products on Shop page', 'xstore'),
					'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
					'after_widget' => '</div><!-- //sidebar-widget -->',
					'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
					'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
				));

				register_sidebar(array(
					'name' => esc_html__('Single product page Sidebar', 'xstore'),
					'id' => 'single-sidebar',
					'description' => esc_html__('Single product page widget area', 'xstore'),
					'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
					'after_widget' => '</div><!-- //sidebar-widget -->',
					'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
					'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
				));
				register_sidebar(array(
					'name' => esc_html__('Cart area', 'xstore'),
					'id' => 'cart-area',
					'description' => esc_html__('Widget area that appears on the shopping cart page', 'xstore'),
					'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
					'after_widget' => '</div><!-- //sidebar-widget -->',
					'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
					'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
				));
			}

			register_sidebar(array(
				'name' => esc_html__('Pre Footer Area', 'xstore'),
				'id' => 'prefooter',
				'description' => esc_html__('The prefooter footer area', 'xstore'),
				'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
				'after_widget' => '</div><!-- //sidebar-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
			));

			$footer_columns = (int) etheme_get_option('footer_columns', 4);

			if( $footer_columns < 1 || $footer_columns > 4) $footer_columns = 4;

			for($_i=1; $_i<=$footer_columns; $_i++) {
				register_sidebar(array(
					'name' => 'Footer Column ' . $_i,
					'id' => 'footer-'.$_i,
					'description' => esc_html__('The main footer widgets area', 'xstore'),
					'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
					'after_widget' => '</div><!-- //footer-widget -->',
					'before_title' => apply_filters('etheme_sidebar_before_title', '<p class="widget-title"><span>' ),
					'after_title' => apply_filters('etheme_sidebar_after_title', '</span></p>'),
				));
			}

			register_sidebar(array(
				'name' => esc_html__('Footer Copyrights Left', 'xstore'),
				'id' => 'footer-copyrights',
				'description' => esc_html__('Footer area for copyrights', 'xstore'),
				'before_widget' => '<div id="%1$s" class="copyrights-widget %2$s">',
				'after_widget' => '</div><!-- //copyrights-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<p class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></p>'),
			));

			register_sidebar(array(
				'name' => esc_html__('Footer Copyrights Right', 'xstore'),
				'id' => 'footer-copyrights2',
				'description' => esc_html__('Footer area for copyrights right', 'xstore'),
				'before_widget' => '<div id="%1$s" class="copyrights-widget %2$s">',
				'after_widget' => '</div><!-- //copyrights-widget -->',
				'before_title' => apply_filters('etheme_sidebar_before_title', '<p class="widget-title"><span>' ),
				'after_title' => apply_filters('etheme_sidebar_after_title', '</span></p>'),
			));
		}
	}

}

// @todo mo it to class/ it used for import
if( ! function_exists('etheme_add_sidebar') ) {
	function etheme_add_sidebar($name) {
		$name = '8theme sidebar - ' . $name;
		$option_name = 'etheme_custom_sidebars';
		$result      = '';
		$result2     = '';
		if(get_option($option_name)) {
			$et_custom_sidebars = etheme_get_stored_sidebar();
			$et_custom_sidebars[] = trim($name);
			$result = update_option($option_name, $et_custom_sidebars);
		}else{
			$et_custom_sidebars[] = $name;
			$result2 = add_option($option_name, $et_custom_sidebars);
		}
		if($result) return 'Updated';
        elseif($result2) return 'added';
		else die('error');
	}
}

/**
 *   Function for registering previously stored sidebars
 */

if(!function_exists('etheme_register_stored_sidebar')) {
	function etheme_register_stored_sidebar(){
		$et_custom_sidebars = etheme_get_stored_sidebar();
		if(is_array($et_custom_sidebars)) {
			foreach($et_custom_sidebars as $name){
				register_sidebar( array(
					'name' => ''.$name.'',
					'id' => str_replace( ' ', '-', $name ),
					'class' => 'etheme_custom_sidebar',
					'before_widget' => '<div id="%1$s" class="sidebar-widget widget-container %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h3 class="widget-title"><span>',
					'after_title' => '</span></h3>',
				) );
			}
		}
	}
}
add_action( 'widgets_init', 'etheme_register_stored_sidebar' );

/**
 *   Function gets stored sidebar array
 */

if(!function_exists('etheme_get_stored_sidebar')) {
	function etheme_get_stored_sidebar(){
		$option_name = 'etheme_custom_sidebars';
		return get_option($option_name);
	}
}