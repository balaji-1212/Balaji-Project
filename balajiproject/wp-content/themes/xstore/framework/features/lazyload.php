<?php
/**
 * Description
 *
 * @package    lazyload.php
 * @since      8.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

if ( ! class_exists( 'XStore_LazyLoad' ) ) :
	class XStore_LazyLoad {
		
		static function init() {
			$l_type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
			if ( $l_type == 'default' ) {
				return;
			}
			add_action( 'wp_head', array( __CLASS__, 'setup' ), 99 );
			
			add_filter(
				'wp_lazy_loading_enabled',
				function( $default, $tag_name ) {
					if ( 'img' === $tag_name ) {
						return false;
					}
					return $default;
				},
				10,
				2
			);
		}
		static function setup() {
			add_filter( 'the_content', array( __CLASS__, 'add_image_placeholders' ), 9999 );
			add_filter( 'post_thumbnail_html', array( __CLASS__, 'add_image_placeholders' ), 11 );
//			add_filter( 'get_avatar', array( __CLASS__, 'add_image_placeholders' ), 11 );
			add_filter( 'woocommerce_single_product_image_html', array( __CLASS__, 'add_image_placeholders' ), 9999 );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( __CLASS__, 'add_image_placeholders' ), 9999 );
			
//			wp_enqueue_script( 'jquery-lazyload' );
		}
		static function add_image_placeholders( $content ) {
			
			if ( is_feed() || is_preview() ) {
				return $content;
			}
			
			if (defined( 'DOING_AJAX' ) && DOING_AJAX){
				return $content;
			}
			
			$matches = array();
			preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );
			
			$search  = array();
			$replace = array();
			
			$dark_version = get_theme_mod('dark_styles', 0);
			$lazy_image = ETHEME_BASE_URI . 'images/lazy' . ( $dark_version ? '-dark' : '' ) . '.png';
			$l_type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
			$lazy_class = 'lazyload lazyload-' . ($l_type == 'lazy' ? 'simple' : 'lqip');
			
			foreach ( $matches[0] as $img_html ) {
				if ( false !== strpos( $img_html, 'data-src' ) ||
				     false !== strpos( $img_html, 'data-original' ) ||
				     false !== strpos( $img_html, 'data-src' ) ||
				     preg_match( "/src=['\"]data:image/is", $img_html ) ||
				     false !== strpos( $img_html, 'rev-slidebg' ) ||
				     false !== strpos( $img_html, 'rs-lazyload' ) ||
				     false !== strpos( $img_html, 'avatar' )) {
					continue;
				}
				
				if ( preg_match( '/width=["\']/i', $img_html ) && preg_match( '/height=["\']/i', $img_html ) ) {
					preg_match( '/width=(["\'])(.*?)["\']/is', $img_html, $match_width );
					preg_match( '/height=(["\'])(.*?)["\']/is', $img_html, $match_height );
					if ( isset( $match_width[2] ) && isset( $match_height[2] ) ) {
						if ( $match_width[2] != $match_height[2] && $match_width[2] < 100 ) {
							continue;
						}
						$lazy_image = etheme_placeholder_image($match_width[2] . 'x' . $match_height[2]);
					}
				} else {
					continue;
				}
				
				$replace_html = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . esc_url( $lazy_image ) . '" data-src=', $img_html );
				$replace_html = preg_replace( '/<img(.*?)srcset=/is', '<img$1srcset="' . esc_url( $lazy_image ) . ' 100w" data-srcset=', $replace_html );
				if ( preg_match( '/class=["\']/i', $replace_html ) ) {
					$replace_html = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1'.$lazy_class.' $2$1', $replace_html );
				} else {
					$replace_html = preg_replace( '/<img/is', '<img class="'.$lazy_class.'"', $replace_html );
				}
				
				array_push( $search, $img_html );
				array_push( $replace, $replace_html );
			}
			
			$search  = array_unique( $search );
			$replace = array_unique( $replace );
			
			$content = str_replace( $search, $replace, $content );
			
			return $content;
		}
	}
	
	if ( ! (is_admin() || get_query_var('et_is_customize_preview', false) || ( defined('DOING_AJAX') && DOING_AJAX ) ) ) {
		add_action( 'init', array( 'XStore_LazyLoad', 'init' ) );
	}
endif;
