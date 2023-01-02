<?php
/**
 * Description
 *
 * @package    config-css.php
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

return array(
	'parent-style' => array(
		'title' => esc_html__('Base', 'xstore'),
		'name' => 'parent-style',
		'file'=> '/xstore',
		'deps' => array()
	),
	'dark' => array(
		'title' => esc_html__('Dark version', 'xstore'),
		'name' => 'dark',
		'file'=> '/css/dark',
	),
	'et-core-plugin-off' => array(
		'title' => esc_html__('Et core plugin off', 'xstore'),
		'name' => 'et-core-plugin-off',
		'deps' => array(),
		'file'=> '/css/et-core-plugin-off',
	),
	'sidebar' => array(
		'title' => esc_html__('Sidebar', 'xstore'),
		'name' => 'sidebar',
		'file'=> '/css/modules/layout/sidebar',
	),
	'mobile-panel' => array(
		'title' => esc_html__('Mobile panel', 'xstore'),
		'name' => 'mobile-panel',
		'file'=> '/css/modules/layout/mobile-panel',
	),
	'header-vertical' => array(
		'title' => esc_html__('Header vertical', 'xstore'),
		'name' => 'header-vertical',
		'file'=> '/css/modules/layout/header/header-vertical',
	),
	// header parts
	'header-search' => array(
		'title' => esc_html__('Search', 'xstore'),
		'name' => 'header-search',
		'file'=> '/css/modules/layout/header/parts/search',
	),
	'ajax-search' => array(
		'title' => esc_html__('Ajax search', 'xstore'),
		'name' => 'ajax-search',
		'file'=> '/css/modules/layout/header/parts/ajax-search',
	),
	// full width search
	'full-width-search' => array(
		'title' => esc_html__('Full-width search', 'xstore'),
		'name' => 'full-width-search',
		'file'=> '/css/modules/layout/header/parts/full-width-search',
	),
	// mobile menu
	'header-mobile-menu' => array(
		'title' => esc_html__('Mobile menu', 'xstore'),
		'name' => 'header-mobile-menu',
		'file'=> '/css/modules/layout/header/parts/mobile-menu',
	),
	// account
	'header-account' => array(
		'title' => esc_html__('Account', 'xstore'),
		'name' => 'header-account',
		'file'=> '/css/modules/layout/header/parts/account',
	),
	// menu
	'header-menu' => array(
		'title' => esc_html__('Menu', 'xstore'),
		'name' => 'header-menu',
		'file'=> '/css/modules/layout/header/parts/menu',
	),
	// contacts
	'header-contacts' => array(
		'title' => esc_html__('Contacts', 'xstore'),
		'name' => 'header-contacts',
		'file'=> '/css/modules/layout/header/parts/contacts',
	),
	// all departments menu
	'all-departments-menu' => array(
		'title' => esc_html__('All departments menu', 'xstore'),
		'name' => 'all-departments-menu',
		'file'=> '/css/modules/layout/header/parts/all-departments-menu',
	),
	'toggles-by-arrow' => array(
		'title' => esc_html__('Toggles by arrow', 'xstore'),
		'name' => 'toggles-by-arrow',
		'file'=> '/css/modules/layout/toggles-by-arrow',
	),
	'off-canvas' => array(
		'title' => esc_html__('Off-canvas', 'xstore'),
		'name' => 'off-canvas',
		'file'=> '/css/modules/layout/off-canvas',
	),
	'sidebar-off-canvas' => array(
		'title' => esc_html__('Sidebar off-canvas', 'xstore'),
		'name' => 'sidebar-off-canvas',
		'file'=> '/css/modules/layout/sidebar-off-canvas',
	),
	'sidebar-widgets-with-scroll' => array(
		'title' => esc_html__('Sidebar widgets with scroll', 'xstore'),
		'name' => 'sidebar-widgets-with-scroll',
		'file'=> '/css/modules/layout/sidebar-widgets-with-scroll',
	),
	'widgets-open-close' => array(
		'title' => esc_html__('Widgets open/close', 'xstore'),
		'name' => 'widgets-open-close',
		'file'=> '/css/modules/layout/widgets-open-close',
	),
	'breadcrumbs' => array(
		'title' => esc_html__('Breadcrumbs', 'xstore'),
		'name' => 'breadcrumbs',
		'file'=> '/css/modules/breadcrumbs',
	),
	'mega-menu' => array(
		'title' => esc_html__('Mega menu', 'xstore'),
		'name' => 'mega-menu',
		'file'=> '/css/modules/mega-menu',
	),
	'portfolio' => array(
		'title' => esc_html__('Portfolio', 'xstore'),
		'name' => 'portfolio',
		'file'=> '/css/portfolio',
	),
	'search-page' => array(
		'title' => esc_html__('Search page', 'xstore'),
		'name' => 'search-page',
		'file'=> '/css/modules/search/global',
	),
//	'footer' => array(
//		'title' => esc_html__('Footer', 'xstore'),
//		'name' => 'footer',
//		'file'=> '/css/modules/footer',
//	),
	'fixed-footer' => array(
		'title' => esc_html__('Fixed Footer', 'xstore'),
		'name' => 'fixed-footer',
		'file'=> '/css/modules/fixed-footer',
	),
	'back-top' => array(
		'title' => esc_html__('Back to top', 'xstore'),
		'name' => 'back-top',
		'file'=> '/css/modules/back-top',
	),
	'skeleton' => array(
		'title' => esc_html__('Skeleton', 'xstore'),
		'name' => 'skeleton',
		'file'=> '/css/modules/skeleton',
	),
	// blog
	'blog-global' => array(
		'title' => esc_html__('Blog global', 'xstore'),
		'name' => 'blog-global',
		'file'=> '/css/modules/blog/blog-global',
	),
	'blog-masonry' => array(
		'title' => esc_html__('Blog masonry', 'xstore'),
		'name' => 'blog-masonry',
		'file'=> '/css/modules/blog/blog-masonry',
	),
	'blog-full-width' => array(
		'title' => esc_html__('Blog full-width', 'xstore'),
		'name' => 'blog-full-width',
		'file'=> '/css/modules/blog/blog-full-width',
	),
	'blog-ajax' => array(
		'title' => esc_html__('Blog ajax', 'xstore'),
		'name' => 'blog-ajax',
		'file'=> '/css/modules/blog/blog-ajax',
	),
	
	// blog types
	'post-global' => array(
		'title' => esc_html__('Post global', 'xstore'),
		'name' => 'post-global',
		'file'=> '/css/modules/blog/types/global',
	),
	'post-framed' => array(
		'title' => esc_html__('Post framed', 'xstore'),
		'name' => 'post-framed',
		'file'=> '/css/modules/blog/types/framed',
	),
	'post-grid-grid2' => array(
		'title' => esc_html__('Post grid-grid2', 'xstore'),
		'name' => 'post-grid-grid2',
		'file'=> '/css/modules/blog/types/grid-grid2',
	),
	'post-small-chess' => array(
		'title' => esc_html__('Post small chess', 'xstore'),
		'name' => 'post-small-chess',
		'file'=> '/css/modules/blog/types/small-chess',
	),
	'post-timeline' => array(
		'title' => esc_html__('Post timeline', 'xstore'),
		'name' => 'post-timeline',
		'file'=> '/css/modules/blog/types/timeline',
	),
//	'post-timeline2' => array(
//		'title' => esc_html__('Post timeline2', 'xstore'),
//		'name' => 'post-timeline2',
//		'file'=> '/css/modules/blog/types/timeline2',
//	),
	'post-with-author' => array(
		'title' => esc_html__('Post with-author', 'xstore'),
		'name' => 'post-with-author',
		'file'=> '/css/modules/blog/types/with-author',
	),
	// could be in global blog
	'post-quote' => array(
		'title' => esc_html__('Post quote', 'xstore'),
		'name' => 'post-quote',
		'file'=> '/css/modules/blog/formats/quote',
	),
	
	// single post
	'single-post-global' => array(
		'title' => esc_html__('Single post global', 'xstore'),
		'name' => 'single-post-global',
		'file'=> '/css/modules/blog/single-post/single-global',
	),
	'single-post-meta' => array(
		'title' => esc_html__('Single post meta', 'xstore'),
		'name' => 'single-post-meta',
		'file'=> '/css/modules/blog/single-post/meta',
	),
	'single-post-framed' => array(
		'title' => esc_html__('Single post framed', 'xstore'),
		'name' => 'single-post-framed',
		'file'=> '/css/modules/blog/single-post/framed',
	),
	'single-post-large' => array(
		'title' => esc_html__('Single post large', 'xstore'),
		'name' => 'single-post-large',
		'file'=> '/css/modules/blog/single-post/large',
	),
	'single-post-large2' => array(
		'title' => esc_html__('Single post large2', 'xstore'),
		'name' => 'single-post-large2',
		'file'=> '/css/modules/blog/single-post/large2',
	),
	'single-post-full-width' => array(
		'title' => esc_html__('Single post full-width', 'xstore'),
		'name' => 'single-post-full-width',
		'file'=> '/css/modules/blog/single-post/full-width',
	),
	
	// global modules
	'pagination' => array(
		'title' => esc_html__('Pagination', 'xstore'),
		'name' => 'pagination',
		'file'=> '/css/modules/pagination',
	),
	'navigation' => array(
		'title' => esc_html__('Navigation', 'xstore'),
		'name' => 'navigation',
		'file'=> '/css/modules/navigation',
	),
	'star-rating' => array(
		'title' => esc_html__('Star-rating', 'xstore'),
		'name' => 'star-rating',
		'file'=> '/css/modules/star-rating',
	),
	'comments' => array(
		'title' => esc_html__('Comments', 'xstore'),
		'name' => 'comments',
		'file'=> '/css/modules/comments',
	),
	
	// modules
	'banners-global' => array(
		'title' => esc_html__('Banners global', 'xstore'),
		'name' => 'banners-global',
		'file'=> '/css/modules/banners/banners-global',
	),
	'banner' => array(
		'title' => esc_html__('Banner', 'xstore'),
		'name' => 'banner',
		'file'=> '/css/modules/banners/banner',
	),
	'team-member' => array(
		'title' => esc_html__('Team member', 'xstore'),
		'name' => 'team-member',
		'file'=> '/css/modules/banners/team-member',
	),
	'et-slider' => array(
		'title' => esc_html__('Et-slider element', 'xstore'),
		'name' => 'et-slider',
		'file'=> '/css/modules/builders/et-slider',
	),
	'categories-carousel' => array(
		'title' => esc_html__('Categories carousel', 'xstore'),
		'name' => 'categories-carousel',
		'file'=> '/css/modules/builders/categories-carousel',
	),
	'et-timer' => array(
		'title' => esc_html__('Countdown element', 'xstore'),
		'name' => 'et-timer',
		'file'=> '/css/modules/et-timer',
	),
	'icon-boxes' => array(
		'title' => esc_html__('Icon-boxes element', 'xstore'),
		'name' => 'icon-boxes',
		'file'=> '/css/modules/builders/icon-boxes',
	),
	'et-blog' => array(
		'title' => esc_html__('Blog element', 'xstore'),
		'name' => 'et-blog',
		'file'=> '/css/modules/blog/et-blog',
	),
	
	// woocommerce modules
	'brands-list' => array(
		'title' => esc_html__('Brands list element', 'xstore'),
		'name' => 'brands-list',
		'file'=> '/css/modules/woocommerce/brands-list',
	),
	'brands-carousel' => array(
		'title' => esc_html__('Brands carousel element', 'xstore'),
		'name' => 'brands-carousel',
		'file'=> '/css/modules/woocommerce/brands-carousel',
	),
	'categories-list-grid' => array(
		'title' => esc_html__('Categories list/grid element', 'xstore'),
		'name' => 'categories-list-grid',
		'file'=> '/css/modules/woocommerce/categories-list-grid',
	),
	'categories-menu-element' => array(
		'title' => esc_html__('Categories menu element', 'xstore'),
		'name' => 'categories-menu-element',
		'file'=> '/css/modules/woocommerce/categories-menu-element',
	),
	'et-offer' => array(
		'title' => esc_html__('Et offer element', 'xstore'),
		'name' => 'et-offer',
		'file'=> '/css/modules/woocommerce/et-offer',
	),
	
	// builders
	'elementor-categories' => array(
		'title' => esc_html__('Elementor categories', 'xstore'),
		'name' => 'elementor-categories',
		'file'=> '/css/modules/builders/elementor/categories',
	),
	'elementor-custom-masonry' => array(
		'title' => esc_html__('Elementor Posts/Products/Product categories custom masonry', 'xstore'),
		'name' => 'elementor-custom-masonry',
		'file'=> '/css/modules/builders/elementor/etheme-custom-masonry',
	),
	'et-advance-tabs' => array(
		'title' => esc_html__('Elementor Etheme advance tabs', 'xstore'),
		'name' => 'et-advance-tabs',
		'file'=> '/css/modules/builders/elementor/et-advance-tabs',
	),
	'elementor-contact-form-7' => array(
		'title' => esc_html__('Elementor Etheme contact form 7', 'xstore'),
		'name' => 'elementor-contact-form-7',
		'file'=> '/css/modules/builders/elementor/etheme-contact-form-7',
	),
	'elementor-google-map' => array(
		'title' => esc_html__('Elementor Etheme google map', 'xstore'),
		'name' => 'elementor-google-map',
		'file'=> '/css/modules/builders/elementor/etheme-google-map',
	),
	
	'elementor-text-button' => array(
		'title' => esc_html__('Elementor Etheme text-button', 'xstore'),
		'name' => 'elementor-text-button',
		'file'=> '/css/modules/builders/elementor/etheme-text-button',
	),
	
	'elementor-blockquote' => array(
		'title' => esc_html__('Elementor Etheme blockquote', 'xstore'),
		'name' => 'elementor-blockquote',
		'file'=> '/css/modules/builders/elementor/etheme-blockquote',
	),
	
	'elementor-countdown' => array(
		'title' => esc_html__('Elementor Etheme countdown', 'xstore'),
		'name' => 'elementor-countdown',
		'file'=> '/css/modules/builders/elementor/etheme-countdown',
	),
	
	'elementor-price-table' => array(
		'title' => esc_html__('Elementor Etheme price table', 'xstore'),
		'name' => 'elementor-price-table',
		'file'=> '/css/modules/builders/elementor/etheme-price-table',
	),
	
	'elementor-hotspot' => array(
		'title' => esc_html__('Elementor Etheme hotspot', 'xstore'),
		'name' => 'elementor-hotspot',
		'file'=> '/css/modules/builders/elementor/etheme-hotspot',
	),
	
	'elementor-flipbox' => array(
		'title' => esc_html__('Elementor Etheme flipbox', 'xstore'),
		'name' => 'elementor-flipbox',
		'file'=> '/css/modules/builders/elementor/etheme-flipbox',
	),
	
	'elementor-animated-headline' => array(
		'title' => esc_html__('Elementor Etheme animated headline', 'xstore'),
		'name' => 'elementor-animated-headline',
		'file'=> '/css/modules/builders/elementor/etheme-animated-headline',
	),
	
	'elementor-advanced-headline' => array(
		'title' => esc_html__('Elementor Etheme headline', 'xstore'),
		'name' => 'elementor-advanced-headline',
		'file'=> '/css/modules/builders/elementor/etheme-advanced-headline',
	),
	
	'elementor-circle-progress-bar' => array(
		'title' => esc_html__('Elementor Etheme Circle progress bar', 'xstore'),
		'name' => 'elementor-circle-progress-bar',
		'file'=> '/css/modules/builders/elementor/etheme-circle-progress-bar',
	),
	
	'elementor-linear-progress-bar' => array(
		'title' => esc_html__('Elementor Etheme Linear progress bar', 'xstore'),
		'name' => 'elementor-linear-progress-bar',
		'file'=> '/css/modules/builders/elementor/etheme-linear-progress-bar',
	),
	
	'elementor-vertical-timeline' => array(
		'title' => esc_html__('Elementor Etheme vertical timeline', 'xstore'),
		'name' => 'elementor-vertical-timeline',
		'file'=> '/css/modules/builders/elementor/etheme-vertical-timeline',
	),
	
	'elementor-horizontal-timeline' => array(
		'title' => esc_html__('Elementor Etheme horizontal timeline', 'xstore'),
		'name' => 'elementor-horizontal-timeline',
		'file'=> '/css/modules/builders/elementor/etheme-horizontal-timeline',
	),
	
	'elementor-product-filters' => array(
		'title' => esc_html__('Elementor Etheme product filters', 'xstore'),
		'name' => 'elementor-product-filters',
		'file'=> '/css/modules/builders/elementor/etheme-product-filters',
	),
	
	'elementor-gallery' => array(
		'title' => esc_html__('Elementor Etheme gallery', 'xstore'),
		'name' => 'elementor-gallery',
		'file'=> '/css/modules/builders/elementor/etheme-gallery',
	),
	
	'elementor-media-carousel' => array(
		'title' => esc_html__('Elementor Etheme media carousel', 'xstore'),
		'name' => 'elementor-media-carousel',
		'file'=> '/css/modules/builders/elementor/etheme-media-carousel',
	),
	
	'elementor-wc-add-to-cart' => array(
		'title' => esc_html__('Elementor Etheme add to cart', 'xstore'),
		'name' => 'elementor-wc-add-to-cart',
		'file'=> '/css/modules/builders/elementor/etheme-wc-add-to-cart',
	),
	
	'elementor-search' => array(
		'title' => esc_html__('Elementor Etheme search', 'xstore'),
		'name' => 'elementor-search',
		'file'=> '/css/modules/builders/elementor/etheme-search',
	),
	
	'elementor-product-list' => array(
		'title' => esc_html__('Elementor Etheme Products list', 'xstore'),
		'name' => 'elementor-product-list',
		'file'=> '/css/modules/builders/elementor/etheme-product-list',
	),
	
	'elementor-product-grid' => array(
		'title' => esc_html__('Elementor Etheme Products Grid', 'xstore'),
		'name' => 'elementor-product-grid',
		'file'=> '/css/modules/builders/elementor/etheme-product-grid',
	),
	
	'elementor-twitter-feed' => array(
		'title' => esc_html__('Elementor Etheme Twitter Feed', 'xstore'),
		'name' => 'elementor-twitter-feed',
		'file'=> '/css/modules/builders/elementor/etheme-twitter-feed',
	),
	
	'elementor-image-comparison' => array(
		'title' => esc_html__('Elementor Etheme Image Comparison', 'xstore'),
		'name' => 'elementor-image-comparison',
		'file'=> '/css/modules/builders/elementor/etheme-image-comparison',
	),
	
	'elementor-icon-list' => array(
		'title' => esc_html__('Elementor Etheme Icon List', 'xstore'),
		'name' => 'elementor-icon-list',
		'file'=> '/css/modules/builders/elementor/etheme-icon-list',
	),
	
	'elementor-icon-box' => array(
		'title' => esc_html__('Elementor Etheme Icon Box', 'xstore'),
		'name' => 'elementor-icon-box',
		'file'=> '/css/modules/builders/elementor/etheme-icon-box',
	),
	
	'elementor-lottie' => array(
		'title' => esc_html__('Elementor Etheme Lottie', 'xstore'),
		'name' => 'elementor-lottie',
		'file'=> '/css/modules/builders/elementor/etheme-lottie',
	),
	
	'elementor-scroll-progress' => array(
		'title' => esc_html__('Elementor Etheme Scroll Progress', 'xstore'),
		'name' => 'elementor-scroll-progress',
		'file'=> '/css/modules/builders/elementor/etheme-scroll-progress',
	),
	
	'elementor-three-sixty-product-viewer' => array(
		'title' => esc_html__('Elementor Etheme 360 Product Viewer', 'xstore'),
		'name' => 'elementor-three-sixty-product-viewer',
		'file'=> '/css/modules/builders/elementor/etheme-three-sixty-product-viewer',
	),
	
	'elementor-modal-popup' => array(
		'title' => esc_html__('Elementor Etheme Modal Popup', 'xstore'),
		'name' => 'elementor-modal-popup',
		'file'=> '/css/modules/builders/elementor/etheme-modal-popup',
	),
	
	'elementor-posts' => array(
		'title' => esc_html__('Elementor Etheme Posts', 'xstore'),
		'name' => 'elementor-posts',
		'file'=> '/css/modules/builders/elementor/etheme-posts',
	),
	
	'elementor-slides' => array(
		'title' => esc_html__('Elementor slides', 'xstore'),
		'name' => 'elementor-slides',
		'file'=> '/css/modules/builders/elementor/etheme-slides',
	),

    'elementor-content-switcher' => array(
        'title' => esc_html__('Elementor content switcher', 'xstore'),
        'name' => 'elementor-content-switcher',
        'file'=> '/css/modules/builders/elementor/etheme-content-switcher',
    ),
	
	// wpbakery
	'wpb-autoscrolling-text' => array(
		'title' => esc_html__('WPBakery autoscrolling-text', 'xstore'),
		'name' => 'wpb-autoscrolling-text',
		'file'=> '/css/modules/builders/wpb/autoscrolling-text',
	),
	'wpb-banners' => array(
		'title' => esc_html__('WPBakery banners', 'xstore'),
		'name' => 'wpb-banners',
		'file'=> '/css/modules/builders/wpb/banners',
	),
	'wpb-twitter' => array(
		'title' => esc_html__('WPBakery twitter', 'xstore'),
		'name' => 'wpb-twitter',
		'file'=> '/css/modules/builders/wpb/twitter',
	),

//	'wpb-categories-lists' => array(
//		'title' => esc_html__('WPBakery categories-lists', 'xstore'),
//		'name' => 'wpb-categories-lists',
//		'file'=> '/css/modules/builders/wpb/categories-lists',
//	),
	'wpb-et-offer' => array(
		'title' => esc_html__('WPBakery et-offer', 'xstore'),
		'name' => 'wpb-et-offer',
		'file'=> '/css/modules/builders/wpb/et-offer',
	),
	'wpb-et-timer' => array(
		'title' => esc_html__('WPBakery et-timer', 'xstore'),
		'name' => 'wpb-et-timer',
		'file'=> '/css/modules/builders/wpb/et-timer',
	),
	'wpb-images-carousel' => array(
		'title' => esc_html__('WPBakery images-carousel', 'xstore'),
		'name' => 'wpb-images-carousel',
		'file'=> '/css/modules/builders/wpb/images-carousel',
	),
	'wpb-instagram' => array(
		'title' => esc_html__('WPBakery instagram', 'xstore'),
		'name' => 'wpb-instagram',
		'file'=> '/css/modules/builders/wpb/instagram',
	),
	// ??? no classes found in code
	'wpb-look-book' => array(
		'title' => esc_html__('WPBakery look-book', 'xstore'),
		'name' => 'wpb-look-book',
		'file'=> '/css/modules/builders/wpb/look-book',
	),
	'wpb-thelook' => array(
		'title' => esc_html__('WPBakery thelook', 'xstore'),
		'name' => 'wpb-thelook',
		'file'=> '/css/modules/builders/wpb/thelook',
	),
	'wpb-testimonials' => array(
		'title' => esc_html__('WPBakery testimonials', 'xstore'),
		'name' => 'wpb-testimonials',
		'file'=> '/css/modules/builders/wpb/testimonials',
	),
	
	// elementor
	'elementor-testimonials' => array(
		'title' => esc_html__('Elementor testimonials', 'xstore'),
		'name' => 'elementor-testimonials',
		'file'=> '/css/modules/builders/elementor/etheme-testimonials',
	),
	
	// photoswipe
	'photoswipe' => array(
		'title' => esc_html__('Photoswipe', 'xstore'),
		'name' => 'photoswipe',
		'file'=> '/css/modules/photoswipe',
	),

	'404-page' => array(
		'title' => esc_html__('404 Page', 'xstore'),
		'name' => '404-page',
		'file'=> '/css/modules/404-page',
	),
	'blank-page' => array(
		'title' => esc_html__('Blank Page', 'xstore'),
		'name' => 'blank-page',
		'file'=> '/css/modules/blank-page',
	),
	
	'contact-forms' => array(
		'title' => esc_html__('Contact forms', 'xstore'),
		'name' => 'contact-forms',
		'file'=> '/css/modules/contact-forms',
	),
	'dropcap' => array(
		'title' => esc_html__('Dropcap', 'xstore'),
		'name' => 'dropcap',
		'file'=> '/css/modules/dropcap',
	),
	'mark-text' => array(
		'title' => esc_html__('Mark text', 'xstore'),
		'name' => 'mark-text',
		'file'=> '/css/modules/mark-text',
	),
	
	'etheme-popup' => array(
		'title' => esc_html__('Etheme popup', 'xstore'),
		'name' => 'etheme-popup',
		'file'=> '/css/modules/etheme-popup',
	),
	
	'socials-login' => array(
		'title' => esc_html__('Socials login', 'xstore'),
		'name' => 'socials-login',
		'file'=> '/css/modules/socials/login',
	),
	
	'instagram' => array(
		'title' => esc_html__('Instagram', 'xstore'),
		'name' => 'instagram',
		'file'=> '/css/modules/instagram',
	),
	
	// isotope
	'isotope-filters' => array(
		'title' => esc_html__('Isotope filters', 'xstore'),
		'name' => 'isotope-filters',
		'file'=> '/css/modules/isotope/filters',
	),
	
	'tabs' => array(
		'title' => esc_html__('Tabs', 'xstore'),
		'name' => 'tabs',
		'file'=> '/css/modules/tabs',
	),
	
	'single-testimonials' => array(
		'title' => esc_html__('Single testimonials', 'xstore'),
		'name' => 'single-testimonials',
		'file'=> '/css/modules/testimonials/single',
	),
	'slick-library' => array(
		'title' => esc_html__('slick library', 'xstore'),
		'name' => 'slick-library',
		'file'=> '/css/libs/slick',
	),
	'woocommerce' => array(
		'title' => esc_html__('WooCommerce global', 'xstore'),
		'name' => 'woocommerce',
		'file'=> '/css/modules/woocommerce/global',
	),
	
	'woocommerce-archive' => array(
		'title' => esc_html__('Woocommerce archive', 'xstore'),
		'name' => 'woocommerce-archive',
		'file'=> '/css/modules/woocommerce/archive',
	),
	
	'cart-widget' => array(
		'title' => esc_html__('WooCommerce cart widget', 'xstore'),
		'name' => 'cart-widget',
		'file'=> '/css/modules/woocommerce/cart-widget',
	),
	
	// swatches
	'swatches-style' => array(
		'title' => esc_html__('Swatches style', 'xstore'),
		'name' => 'swatches-style',
		'file'=> '/css/swatches',
	),
	
	// yith compare
	'yith-compare' => array(
		'title' => esc_html__('Yith Compare', 'xstore'),
		'name' => 'yith-compare',
		'file'=> '/css/modules/woocommerce/yith-compare',
		'deps' => array()
	),
	
	'sb-infinite-scroll-load-more' => array(
		'title' => esc_html__('Infinite scroll load more', 'xstore'),
		'name' => 'sb-infinite-scroll-load-more',
		'file'=> '/css/modules/woocommerce/sb-infinite-scroll-load-more',
	),
	
	'catalog-mode' => array(
		'title' => esc_html__('Catalog mode', 'xstore'),
		'name' => 'catalog-mode',
		'file'=> '/css/modules/woocommerce/catalog-mode',
	),
	
	'shop-full-width' => array(
		'title' => esc_html__('Shop full width', 'xstore'),
		'name' => 'shop-full-width',
		'file'=> '/css/modules/woocommerce/shop-full-width',
	),
	
	'products-full-screen' => array(
		'title' => esc_html__('Products full screen', 'xstore'),
		'name' => 'products-full-screen',
		'file'=> '/css/modules/woocommerce/products-full-screen',
	),
	
	'special-cart-breadcrumbs' => array(
		'title' => esc_html__('Special cart/checkout breadcrumbs', 'xstore'),
		'name' => 'special-cart-breadcrumbs',
		'file'=> '/css/modules/woocommerce/pages/special-cart-breadcrumbs',
	),
	
	'filter-area' => array(
		'title' => esc_html__('Filter area', 'xstore'),
		'name' => 'filter-area',
		'file'=> '/css/modules/woocommerce/filter-area',
	),
	
	'quick-view' => array(
		'title' => esc_html__('Quick view', 'xstore'),
		'name' => 'quick-view',
		'file'=> '/css/modules/woocommerce/quick-view',
	),
	
	'popup-added-to-cart' => array(
		'title' => esc_html__('Popup added to cart', 'xstore'),
		'name' => 'popup-added-to-cart',
		'file'=> '/css/modules/woocommerce/popup-added-to-cart',
	),
	
	// pages
	'cart-page' => array(
		'title' => esc_html__('Cart page', 'xstore'),
		'name' => 'cart-page',
		'file'=> '/css/modules/woocommerce/pages/cart',
	),
	'checkout-page' => array(
		'title' => esc_html__('Checkout page', 'xstore'),
		'name' => 'checkout-page',
		'file'=> '/css/modules/woocommerce/pages/checkout',
	),
	'thank-you-page' => array(
		'title' => esc_html__('Thank you page', 'xstore'),
		'name' => 'thank-you-page',
		'file'=> '/css/modules/woocommerce/pages/thank-you',
	),
	'account-page' => array(
		'title' => esc_html__('Account page', 'xstore'),
		'name' => 'account-page',
		'file'=> '/css/modules/woocommerce/pages/account',
	),
	'wishlist-page' => array(
		'title' => esc_html__('wishlist page', 'xstore'),
		'name' => 'wishlist-page',
		'file'=> '/css/modules/woocommerce/pages/wishlist',
	),
	'no-products-found' => array(
		'title' => esc_html__('No products found', 'xstore'),
		'name' => 'no-products-found',
		'file'=> '/css/modules/woocommerce/no-products-found',
	),
	
	'cart-checkout-advanced-layout' => array(
		'title' => esc_html__('Cart/checkout advanced layout', 'xstore'),
		'name' => 'cart-checkout-advanced-layout',
		'file'=> '/css/modules/woocommerce/pages/cart-checkout-advanced-layout',
	),
	
	// product hovers
	'product-view-default' => array(
		'title' => esc_html__('Product view default', 'xstore'),
		'name' => 'product-view-default',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-default',
	),
	// almost same as default
	'product-view-mask' => array(
		'title' => esc_html__('Product view mask', 'xstore'),
		'name' => 'product-view-mask',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-default',
	),
	'product-view-mask2' => array(
		'title' => esc_html__('Product view mask2', 'xstore'),
		'name' => 'product-view-mask2',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-mask2',
	),
	'product-view-mask3' => array(
		'title' => esc_html__('Product view mask3', 'xstore'),
		'name' => 'product-view-mask3',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-mask3',
	),
	'product-view-light' => array(
		'title' => esc_html__('Product view light', 'xstore'),
		'name' => 'product-view-light',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-light',
	),
	'product-view-booking' => array(
		'title' => esc_html__('Product booking', 'xstore'),
		'name' => 'product-view-booking',
		'file'=> '/css/modules/woocommerce/product-hovers/product-booking',
	),
	'product-view-menu' => array(
		'title' => esc_html__('Product view menu', 'xstore'),
		'name' => 'product-view-menu',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-menu',
	),
	'product-view-info' => array(
		'title' => esc_html__('Product view info', 'xstore'),
		'name' => 'product-view-info',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-info',
	),
	'product-view-overlay' => array(
		'title' => esc_html__('Product view overlay', 'xstore'),
		'name' => 'product-view-overlay',
		'file'=> '/css/modules/woocommerce/product-hovers/product-view-overlay',
	),
	
	// custom view for wpbakery only
	'content-product-custom' => array(
		'title' => esc_html__('Product content custom', 'xstore'),
		'name' => 'content-product-custom',
		'file'=> '/css/modules/woocommerce/content-product-custom',
	),
	
	// single product
	'single-product' => array(
		'title' => esc_html__('Single product', 'xstore'),
		'name' => 'single-product',
		'file'=> '/css/modules/woocommerce/single-product/single-product',
	),
	'single-product-elements' => array(
		'title' => esc_html__('Single product elements', 'xstore'),
		'name' => 'single-product-elements',
		'file'=> '/css/modules/woocommerce/single-product/single-product-elements',
	),
	'single-product-booking' => array(
		'title' => esc_html__('Single product booking', 'xstore'),
		'name' => 'single-product-booking',
		'file'=> '/css/modules/woocommerce/single-product/single-product-booking',
	),
	'single-product-right' => array(
		'title' => esc_html__('Single product right', 'xstore'),
		'name' => 'single-product-right',
		'file'=> '/css/modules/woocommerce/single-product/single-product-right',
	),
	'single-product-wide' => array(
		'title' => esc_html__('Single product wide', 'xstore'),
		'name' => 'single-product-wide',
		'file'=> '/css/modules/woocommerce/single-product/single-product-wide',
	),
	'single-product-sticky-cart' => array(
		'title' => esc_html__('Single product sticky cart', 'xstore'),
		'name' => 'single-product-sticky-cart',
		'file'=> '/css/modules/woocommerce/single-product/sticky-cart',
	),
	'single-product-request-quote' => array(
		'title' => esc_html__('Single product request quote', 'xstore'),
		'name' => 'single-product-request-quote',
		'file'=> '/css/modules/woocommerce/single-product/request-quote',
	),
	
	// single product builder
	'single-product-builder' => array(
		'title' => esc_html__('Single product builder', 'xstore'),
		'name' => 'single-product-builder',
		'file'=> '/css/modules/woocommerce/single-product/single-product-builder',
	),
	
	// 3d-party plugins
	'forum-style' => array(
		'title' => esc_html__('bbPress styles', 'xstore'),
		'name' => 'forum-style',
		'file'=> '/css/forum',
	),
	
	'dokan-style' => array(
		'title' => esc_html__('Dokan styles', 'xstore'),
		'name' => 'dokan-style',
		'file'=> '/css/dokan',
	),
	
	'wcfmmp-style' => array(
		'title' => esc_html__('WCFMmp styles', 'xstore'),
		'name' => 'wcfmmp-style',
		'file'=> '/css/wcfmmp',
	),
	
	'wcmp-style' => array(
		'title' => esc_html__('Wcmp styles', 'xstore'),
		'name' => 'wcmp-style',
		'file'=> '/css/wcmp',
	),
	
	'cookie-notice-style' => array(
		'title' => esc_html__('Cookie-notice styles', 'xstore'),
		'name' => 'cookie-notice-style',
		'file'=> '/css/cookie-notice',
	),
	
	// builders
	'wpb-style' => array(
		'title' => esc_html__('WPBakery styles', 'xstore'),
		'name' => 'wpb-style',
		'file'=> '/css/wpb',
	),
	
	'elementor-style' => array(
		'title' => esc_html__('WPBakery styles', 'xstore'),
		'name' => 'elementor-style',
		'file'=> '/css/elementor',
	),
	
	// sales booster
	'sale-booster-cart-checkout-progress-bar' => array(
		'title' => esc_html__('Cart/Checkout progress bar shortcode', 'xstore'),
		'name' => 'sale-booster-cart-checkout-progress-bar',
		'file'=> '/css/modules/woocommerce/sales-booster/cart-checkout-progress-bar',
	),
);