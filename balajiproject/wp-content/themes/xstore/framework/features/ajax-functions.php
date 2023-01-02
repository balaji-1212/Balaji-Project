<?php
/**
 * Description
 *
 * @package    ajax-functions.php
 * @since      8.0.0
 * @author     andrey
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );


// **********************************************************************//
// ! Ajax response for shortcodes/VC elements loading
// **********************************************************************//
add_action( 'wp_ajax_et_ajax_element', 'et_ajax_element');
add_action( 'wp_ajax_nopriv_et_ajax_element', 'et_ajax_element');
if ( ! function_exists( 'et_ajax_element' ) ) {
	function et_ajax_element(){
		if ( ! isset( $_POST[ 'element' ] ) ) die();
		
		$atts = '';
		$args = ( isset( $_POST['args'] ) ) ? $_POST['args'] : array() ;
		$args['ajax'] = false;
		$args['ajax_loaded'] = true;

        if ( class_exists('XStoreCore\Modules\WooCommerce\XStore_Wishlist')) {
            $wishlist_instance = XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();
            add_filter('etheme_wishlist_btn_output', array($wishlist_instance, 'old_wishlist_btn_filter'), 10, 2);
        }
		
		if (isset($_POST[ 'type' ]) ) {
			if ($_POST[ 'type' ] == 'widget'){
				$widget_args = (isset($_POST[ 'widget_args' ])) ? et_normalize_widget_args($_POST[ 'widget_args' ]) : array();
				the_widget( 'ETC\App\Models\Widgets\\' . $_POST[ 'element' ],$args, $widget_args );
				die();
			}
		}
		
		foreach ( $args as $key => $value ) {
			// ! Do it because js change data type
			if ( $value === 'false' ) $value = false;
			
			$atts .= $key . '="' . $value . '" ';
		}
		
		add_filter('etheme_output_shortcodes_inline_css', '__return_true');
		
		add_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		add_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		add_filter( 'etheme_output_shortcodes_inline_css', function() { return true; } );
		
		// this add variation gallery filters at loop start and remove it after loop end
//        if ( !$_POST['archiveVariationGallery'] ) {
		add_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
		add_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
//        }
		
		add_filter('woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2);
		
		if ( isset( $_POST[ 'content' ] ) && ! empty( $_POST[ 'content' ] ) ) {
			// ! Do it because js add backslash
			$content = stripslashes( $_POST[ 'content' ] );
			$content = do_shortcode( $content ) . '[/' . $_POST[ 'element' ] . ']';
		} else {
			$content = '';
		}
		echo do_shortcode( '[' . $_POST[ 'element' ] . ' ' . $atts . ' ]' . $content );
		die();
	}
}

function et_normalize_widget_args($args){
	$args = stripslashes($args);
	$args = json_decode( $args, true);
	$args = str_replace('u0022', '"',$args );
	if (isset($args['before_title'])){
		$args['before_title'] = html_entity_decode($args['before_title']);
	}
	if (isset($args['after_title'])){
		$args['after_title'] = html_entity_decode($args['after_title']);
	}
	if (isset($args['before_widget'])){
		$args['before_widget'] = html_entity_decode($args['before_widget']);
	}
	if (isset($args['after_widget'])){
		$args['after_widget'] = html_entity_decode($args['after_widget']);
	}
	return $args;
}

// **********************************************************************//
// ! Ajax holder for shortcodes/VC elements loading
// **********************************************************************//
if ( ! function_exists( 'et_ajax_element_holder' ) ) {
	function et_ajax_element_holder($element = false, $atts = array(), $extra = '', $content = false, $type = 'element', $widget = array()){
		if ( ! $element ) return;
		
		if ( $content ) {
			$content = '<span class="hidden et-element-content"><!--[if IE 6] --[et-ajax]--' . $content . '--[!et-ajax]-- ![endif]--></span>';
		}
		
		if ( ( is_array($widget) || is_object($widget) ) && count($widget)){
			$content .= '<span class="hidden et-element-args_widget"><!--[if IE 6] --[et-ajax]--' . esc_js(json_encode($widget, JSON_HEX_QUOT) ). '--[!et-ajax]-- ![endif]--></span>';
		}
		
		$output = '
			<div class="et-load-block lazy-loading et-ajax-element type-'.$type.'" data-type="'.$type.'" data-extra="' . $extra . '" data-element="' . $element . '">
				<!--googleoff: index-->
				<!--noindex-->
				' . etheme_loader(false, 'no-lqip') . '
				<span class="hidden et-element-args"><!--[if IE 6] --[et-ajax]--' . json_encode( $atts ) . '--[!et-ajax]-- ![endif]--></span>
				' . $content . '
				<!--/noindex-->
				<!--googleon: index-->
			</div>
		';
		return $output;
	}
}

add_filter('et_ajax_widgets', 'et_ajax_widgets');
if (! function_exists('et_ajax_widgets')){
	function et_ajax_widgets($ajax){
		if (isset($_GET['et_ajax'])){
			return false;
		}
		if ( get_query_var('is_mobile', false) && get_theme_mod('sidebar_for_mobile', 'off_canvas') == 'off_canvas' ) {
			//return false;
		}
		return $ajax;
	}
}

// post ajax
add_action( 'wp_ajax_et_ajax_blog_element', 'et_ajax_blog_element');
add_action( 'wp_ajax_nopriv_et_ajax_blog_element', 'et_ajax_blog_element');
if ( ! function_exists( 'et_ajax_blog_element' ) ) {
	function et_ajax_blog_element(){
		
		$atts = '';
		$args = ( isset( $_POST['args'] ) ) ? $_POST['args'] : array() ;
		$args['ajax'] = false;
		$args['ajax_loaded'] = true; // not used yet
		add_filter('etheme_output_shortcodes_inline_css', '__return_true');
		foreach ( $args as $key => $value ) {
			// ! Do it because js change data type
			if ( $value === 'false' ) $value = false;
			
			$atts .= $key . '="' . $value . '" ';
		}
		echo do_shortcode( '[' . $_POST[ 'element' ] . ' ' . $atts . ' paged="' . $_POST[ 'paged' ] . '" html_type="true" ]' );
		die();
	}
}