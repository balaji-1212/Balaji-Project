<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');
// **********************************************************************// 
// ! Static Blocks Post Type
// **********************************************************************// 

if(!function_exists('etheme_get_static_blocks')) {
    function etheme_get_static_blocks ($s = '') {
        $return_array = array();
        $args = array( 'post_type' => 'staticblocks', 'posts_per_page' => -1, 's'=> $s );
        
        $myposts = get_posts( $args );
        $i=0;
        foreach ( $myposts as $post ) {
            $i++;
            $return_array[$i]['label'] = $post->post_title;
            $return_array[$i]['value'] = $post->ID;
        } 
        wp_reset_postdata();

        return $return_array;
    }
}

// **********************************************************************// 
// ! Get Static Blocks
// **********************************************************************// 
if ( ! function_exists( 'etheme_static_block' ) ) {
    function etheme_static_block($id = false, $echo = false){
        if( ! $id ) return;
        global $etheme_global;
        global $post;

        // ! Check post password_required
        if ( post_password_required( $id ) ) {
            echo get_the_password_form( $id );
            return;
        }
	
	    $edit_mode = false;
        $elementor_instance = false;
        
        if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {
	        $elementor_instance = Elementor\Plugin::instance();
	        $edit_mode = Elementor\Plugin::instance()->preview->is_preview_mode();
        }
	    
//	    $cache = false;
//	    if ( !$edit_mode ) {
		    $cache = etheme_get_option( 'static_block_cache', 1 );
//	    }
	    
        $output = false;
	
	    $id = (string)$id;
	    
	    $prevent_setup_post = apply_filters('etheme_static_block_prevent_setup_post', false);

        if ( $cache ) {
        	if ( $edit_mode ) {
        		if ($id != '') wp_cache_delete($id, 'etheme_get_block');
	        }
        	else {
		        $output = ( $id != '' ) ? wp_cache_get( $id, 'etheme_get_block' ) : '';
	        }
        }
        
        if ( ! $output ) {
            $args = array( 'include' => $id,'post_type' => 'staticblocks', 'posts_per_page' => 1);
            $output = '';
            $myposts = get_posts( $args );
            foreach ( $myposts as $block ) {
	            if ( !$prevent_setup_post )
                    setup_postdata($block);

                if ( !!$elementor_instance ) {
                    $output = $elementor_instance->frontend->get_builder_content_for_display( $block->ID );
                }

                if ( $output == '' ) {
	                $shortcodes_custom_css = '';
	                $is_wpb = false;
                    if ( class_exists('WPBMap') && method_exists('WPBMap', 'addAllMappedShortcodes')){
                        WPBMap::addAllMappedShortcodes();
	                    $is_wpb = true;
                    }
                    $output = do_shortcode( $block->post_content );
        
                    $shortcodes_custom_css .= get_post_meta( $block->ID, '_wpb_shortcodes_custom_css', true );
                    
                    if ( $is_wpb ) {
	                    $css = array(
		                    'global' => array(),
		                    'md'     => array(),
		                    'sm'     => array(),
		                    'xs'     => array(),
	                    );
	
	                    $css2 = et_custom_shortcodes_css( $block->post_content );
	
	                    if ( is_array( $css2 ) ) {
		                    $css = array(
			                    'global' => array_merge( $css['global'], $css2['global'] ),
			                    'md'     => array_merge( $css['md'], $css2['md'] ),
			                    'sm'     => array_merge( $css['sm'], $css2['sm'] ),
			                    'xs'     => array_merge( $css['xs'], $css2['xs'] ),
		                    );
	                    }
	
	                    $css = array(
		                    'global' => array_unique( $css['global'] ),
		                    'md'     => array_unique( $css['md'] ),
		                    'sm'     => array_unique( $css['sm'] ),
		                    'xs'     => array_unique( $css['xs'] ),
	                    );
	
	                    if ( count( $css['global'] ) ) {
		                    $shortcodes_custom_css .= implode( '', $css['global'] );
	                    }
	
	                    if ( count( $css['md'] ) ) {
		                    $shortcodes_custom_css .= '@media only screen and (max-width: 1199px) {' . implode( '', $css['md'] ) . '}';
	                    }
	
	                    if ( count( $css['sm'] ) ) {
		                    $shortcodes_custom_css .= '@media only screen and (max-width: 768px) {' . implode( '', $css['sm'] ) . '}';
	                    }
	
	                    if ( count( $css['xs'] ) ) {
		                    $shortcodes_custom_css .= '@media only screen and (max-width: 480px) {' . implode( '', $css['xs'] ) . '}';
	                    }
                    }

                    if ( ! empty( $shortcodes_custom_css ) ) {
	                    if( defined('DOING_AJAX') && DOING_AJAX ) {
		                    $output .= '<style type="text/css" data-type="vc_shortcodes-custom-css">' . $shortcodes_custom_css . '</style>';
	                    }
	                    else {
		                    wp_add_inline_style( 'xstore-inline-css', $shortcodes_custom_css );
	                    }
                    }
                }
            }
            
	        if ( !$prevent_setup_post )
                wp_reset_postdata();

            if ( $cache && $id != '' ) {
            	wp_cache_add( $id, $output, 'etheme_get_block' );
            }
        }

        if ( $echo ) {
            echo wp_specialchars_decode($output);
        } else {
            return wp_specialchars_decode($output);
        }
    }
}


// **********************************************************************//
// ! Ajax get Static Blocks
// **********************************************************************//
add_action( 'wp_ajax_et_ajax_get_static_blocks', 'et_ajax_get_static_blocks' );
if ( ! function_exists('et_ajax_get_static_blocks')) {
	function et_ajax_get_static_blocks(){
		$s = '';
		if (isset($_REQUEST['search'])){
			$s= $_REQUEST['search'];
		}

		$static_blocks = etheme_get_static_blocks($s);
		$return = array();
		foreach ($static_blocks as $key => $value) {
			$return[] = array(
				'id' => $value['value'],
				'text' => $value['label']
			);
		}
		wp_send_json($return);
	}
}