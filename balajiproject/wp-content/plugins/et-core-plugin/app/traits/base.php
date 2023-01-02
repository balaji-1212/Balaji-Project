<?php
/**
 * Base Functions
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Traits/Base
 */
function etheme_decoding( $val ) {
	return base64_decode( $val );
}

function etheme_encoding( $val ) {
	return base64_encode( $val );
}

function etheme_fw($file, $content) {
	return fwrite($file, $content);
}

function etheme_fo($file, $perm) {
	return fopen($file, $perm);
}

function etheme_fr($file, $size) {
	return fread($file, $size);
}

function etheme_fc($file) {
	return fclose($file);
}

function etheme_fgcontent( $url, $flag, $context) {
	return file_get_contents($url, $flag, $context);
}

function etheme_fpcontent( $file, $content) {
	return file_put_contents($file, $content);
}

/**
 * Check is the current request a REST API request
 */
function etheme_is_rest($route = ''){
	global $wp_query;
	if (empty($wp_query)){
		return apply_filters('etheme_is_rest', false);
	}
	$rest_url = get_rest_url() . $route;
	
	if (preg_match('~^'.preg_quote(parse_url($rest_url, PHP_URL_PATH)).'~', $_SERVER['REQUEST_URI'])){
		return apply_filters('etheme_is_rest', true);
	}
	return apply_filters('etheme_is_rest', false);
}

if(!function_exists('etheme_override_shortcodes')){
    function etheme_override_shortcodes() {
        global $shortcode_tags, $_shortcode_tags;
        // Let's make a back-up of the shortcodes
        $_shortcode_tags = $shortcode_tags;
        // Add any shortcode tags that we shouldn't touch here
        $disabled_tags = array( '' );
        foreach ( $shortcode_tags as $tag => $cb ) {
            if ( in_array( $tag, $disabled_tags ) ) {
                continue;
            }
            // Overwrite the callback function
            $shortcode_tags[ $tag ] = 'etheme_wrap_shortcode_in_div';
        }
    }
}
// Wrap the output of a shortcode in a div with class "ult-item-wrap"
// The original callback is called from the $_shortcode_tags array
if(!function_exists('etheme_wrap_shortcode_in_div')){
    function etheme_wrap_shortcode_in_div( $attr, $content, $tag ) {
        global $_shortcode_tags;
        return '<div class="swiper-slide">' . call_user_func( $_shortcode_tags[ $tag ], $attr, $content, $tag ) . '</div>';
    }
}

if(!function_exists('etheme_restore_shortcodes')){
    function etheme_restore_shortcodes() {
        global $shortcode_tags, $_shortcode_tags;
        // Restore the original callbacks
        if ( isset( $_shortcode_tags ) ) {
            $shortcode_tags = $_shortcode_tags;
        }
    }
}

//if (! function_exists('unicode_chars')){
//	function unicode_chars($source, $iconv_to = 'UTF-8') {
//		$decodedStr = '';
//		$pos = 0;
//		$len = strlen ($source);
//		while ($pos < $len) {
//			$charAt = substr ($source, $pos, 1);
//			$decodedStr .= $charAt;
//			$pos++;
//		}
//
//		if ($iconv_to != "UTF-8") {
//			$decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
//		}
//
//		return $decodedStr;
//	}
//}

if (! function_exists('etheme_documentation_url')){
	function etheme_documentation_url(){
		return 'https://xstore.helpscoutdocs.com/';
	}
}