<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

/*
* Load Shortcode file
* ******************************************************************* */

if(!function_exists('etheme_load_shortcode')) {
	function etheme_load_shortcode($name) {
		$file = ETHEME_CODE_SHORTCODES . $name.'.php';
		if ( ( ETHEME_BASE != ETHEME_CHILD ) && file_exists(trailingslashit(ETHEME_CHILD).$file) ) {
			$path = trailingslashit(ETHEME_CHILD).$file;
			require_once($path) ;
			return true;
		}
		// return false to load from core
		return false;
	}
}


/*
* Get theme option 
* ******************************************************************* */

if(!function_exists('etheme_get_option')) {
	function etheme_get_option($key, $default = '',$doshortcode = false) {
		global $et_options;
//		$old_options = get_query_var('et_redux_options', array());

		if ( ! defined('ET_CORE_VERSION') ) {
//			if ( is_array($old_options) && isset($old_options[$key]) ) {
//				$et_kirki_option = $old_options[$key];
//			}
//			else {
				$et_kirki_option = isset($et_options[$key]) ? $et_options[$key] : '';
//			}
		}
		else {
			$et_kirki_option = get_theme_mod($key, $default);
  		}
  		$result = '';
  		
  		if(!empty($et_kirki_option)) {
	    	if($doshortcode){
	        	$result = do_shortcode($et_kirki_option);
	    	}else{
	        	$result =  $et_kirki_option;
	    	}
  		}
    	return apply_filters('et_option_'.$key, $result);
	}
}

if(!function_exists('etheme_option')) {
	function etheme_option($key, $setting = null,$doshortcode = true) {
		echo etheme_get_option($key, $setting, $doshortcode);
	}
}

/*
* Get custom meta for posts
* ******************************************************************* */

if(!function_exists('etheme_get_custom_field')) {
	function etheme_get_custom_field($field, $postid = false) {
		global $post;

		if ( null === $post && !$postid) return FALSE;

		if(!$postid) {
			$postid = $post->ID;
		} 

		$custom_field = get_post_meta($postid, ETHEME_PREFIX . $field, true);
		
		if(is_array($custom_field)) {
			$custom_field = $custom_field[0];
		}
		if ( $custom_field ) {
			return stripslashes( wp_kses_decode_entities( $custom_field ) );
		}
		else {
			return FALSE;
		}
	}
}

// **********************************************************************//
// ! Get query custom field
// **********************************************************************//
if ( !function_exists('etheme_get_query_custom_field')) {
	function etheme_get_query_custom_field( $field ) {
		$page    = get_query_var( 'et_page-id', array( 'id' => 0, 'type' => 'page' ) );
		$page_id = ( isset( $page['id'] ) ) ? $page['id'] : false;
		
		if ( $page_id ) {
			$field = etheme_get_custom_field( $field, $page_id );
		} else {
			$field = false;
		}
		
		return $field;
	}
}

/*
* Get file from child theme
* ******************************************************************* */

if(!function_exists('etheme_childtheme_file')) {
	add_filter('etheme_file_url', 'etheme_childtheme_file', 10, 1);
	
	function etheme_childtheme_file($file) {
		if ( ( ETHEME_BASE != ETHEME_CHILD ) && file_exists(trailingslashit(ETHEME_CHILD).$file) )
			$url = trailingslashit(ETHEME_CHILD).$file;
		else 
			$url = trailingslashit(ETHEME_BASE).$file;
		return $url;
	}
}


/*
* Get sidebars list for options
* ******************************************************************* */

if(!function_exists('etheme_get_sidebars')) {
	function etheme_get_sidebars() {
		global $wp_registered_sidebars;
		$sidebars[] = '--Choose--';
		foreach( $wp_registered_sidebars as $id=>$sidebar ) {
			$sidebars[ $id ] = $sidebar[ 'name' ];
        }
        return $sidebars;
	}
}

/*
* Get revolution sliders list for options
* ******************************************************************* */

if(!function_exists('etheme_get_revsliders')) {
	function etheme_get_revsliders() {
		global $wpdb;
	    if(class_exists('RevSliderAdmin')) {
	    	
	    	$rs = $wpdb->get_results( 
	    		"
	    		SELECT id, title, alias
	    		FROM ".$wpdb->prefix."revslider_sliders
	    		ORDER BY id ASC LIMIT 100
	    		"
	    	);
	    	$revsliders = array(
	    		'no_slider' => 'No Slider'
	    	);
	    	if ($rs) {
		    	$_ri = 1;
		    	foreach ( $rs as $slider ) {
		    	  	$revsliders[$slider->alias] = $slider->title;
		    		$_ri++;
		    	}
	    	}
	    	
	        return $revsliders;
	    } else {
		    return array('' => esc_html__('You need to install Revolution Slider plugin', 'xstore'));
	    }
	}
}

/*
* Trunc string for some words number
* ******************************************************************* */

if(!function_exists('etheme_trunc')) {
    function etheme_trunc($phrase, $max_words) {
       $phrase_array = explode(' ',$phrase);
       if(count($phrase_array) > $max_words && $max_words > 0)
          $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).' ...';
       return $phrase;
    }
}

if(! function_exists('etheme_strip_shortcodes')) {
	function etheme_strip_shortcodes($content ) {
		if ( false === strpos( $content, '[' ) ) {
			return $content;
		}

		$content = preg_replace("/\[[^\]]*\]/", '', $content);  # strip shortcodes, keep shortcode content

		return $content;
	}
}

function etheme_protocol_url($url) {
	if ( ! is_ssl() ) {
		return str_replace( 'https:', 'http:', $url );
	} else {
		return str_replace( 'http:', 'https:', $url );
	}
}

if (! function_exists('etheme_documentation_url')){
	function etheme_documentation_url($article = false, $echo = true) {
		$url = 'https://xstore.helpscoutdocs.com/';
		if ( $article ) {
			$url .= 'article/' . $article . '/';
		}

		if ($echo){
			echo apply_filters('etheme_documentation_url',$url );
		} else {
			return apply_filters('etheme_documentation_url',$url );
		}
	}
}