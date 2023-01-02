<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Blog carousel shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Blog_Carousel extends Shortcodes {

    function hooks() {}

    function blog_carousel_shortcode( $atts, $content ) {

	    if ( xstore_notice() )
		    return;

        $atts = shortcode_atts( array(
			'post_type'  => 'post',
			'items_limit' => 10,
			'blog_hover'  => 'zoom',
            'paged' => 0,
            'html_type' => false,
			'include'  => '',
			'custom_query'  => '',
			'taxonomies'  => '',
			'items_per_page'  => 10,
			'orderby'  => 'date',
			'order'  => 'DESC',
			'meta_key'  => '',
			'exclude'  => '',
			'slide_view'  => 'vertical',
            'hide_img' => false,
			'size' => 'medium',
			'class'  => '',
			'ajax' => false,
			'blog_layout' => '',
			'blog_align' => 'left',
			'large' => 4,
			'notebook' => 3,
			'tablet_land' => 2,
			'tablet_portrait' => 2,
			'mobile' => 1,
			'slider_autoplay' => false,
			'slider_speed' => 300,
			'slider_interval' => 3000,
			'slider_stop_on_hover' => false,
			'slider_loop' => false,
			'pagination_type' => 'hide',
			'autoheight' => true, // it is default in slider
			'hide_fo' => '',
			'nav_color' => '',
			'arrows_bg_color' => '',
			'default_color' => '#e1e1e1',
			'active_color' => '#222',
			'hide_buttons' => false,
			'navigation_type'      => 'arrow',
			'navigation_position_style' => 'arrows-hover',
			'navigation_style'     => '',
			'navigation_position'  => 'middle',
            'hide_buttons_for'   => '',
			'per_move' => 1,
			'echo' => false,
			'elementor' => false,
			'is_preview' => false
		), $atts );

        $options = array();

        if ( !$atts['items_limit'] ) 
        	$atts['items_limit'] = 10;

        if ( !$atts['items_per_page'] ) 
        	$atts['items_per_page'] = 10;

		$options['wp_query_args'] = array(
			'post_type' => 'post',
			'post_status' =>'publish',
			'paged' => $atts['paged'],
			'posts_per_page' => $atts['items_per_page'],
			// 'include'  => $atts['include'],
			// 'custom_query'  => $atts['custom_query'],
			// 'taxonomies'  => $atts['taxonomies'],
			// 'orderby'  => $atts['orderby'],
			// 'order'  => $atts['order'],
			// 'meta_key'  => $atts['meta_key'],
			// 'exclude'  => $atts['exclude'],
		);

		if( $atts['post_type'] == 'ids' && $atts['include'] != '' ) 
			$options['wp_query_args']['post__in'] = explode(',', $atts['include']);

		if( !empty( $atts['exclude'] ) ) 
			$options['wp_query_args']['post__not_in'] = explode(',', $atts['exclude']);

		if( !empty( $atts['taxonomies'] ) ) {
			if ( is_array($atts['taxonomies']) ) 
				$atts['taxonomies'] =  explode(',', $atts['taxonomies']);

			$options['taxonomy_names'] = get_object_taxonomies( 'post' );
			$options['terms'] = get_terms( $options['taxonomy_names'], array(
				'orderby' => 'name',
				'include' => $atts['taxonomies']
			));

			if( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
				$options['wp_query_args']['tax_query'] = array('relation' => 'OR');
				foreach ($options['terms'] as $key => $term) {
					$options['wp_query_args']['tax_query'][] = array(
						'taxonomy' => $term->taxonomy,
						'field' => 'slug',
						'terms' => array( $term->slug ),
						'include_children' => true,
						'operator' => 'IN'
					);
				}
			}
		}

		if( !empty( $atts['order'] ) ) 
			$options['wp_query_args']['order'] = $atts['order'];

		if( !empty( $atts['meta_key'] ) )
			$options['wp_query_args']['meta_key'] = $atts['meta_key'];

		if( !empty( $atts['orderby'] ) )
			$options['wp_query_args']['orderby'] = $atts['orderby'];
		
		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'blog-global', $atts['is_preview'] );
			if ( $atts['slide_view'] == 'timeline2' ) {
				etheme_enqueue_style( 'post-timeline', $atts['is_preview'] );
			}
		}
		
		if ($atts['ajax'] && ! $atts['is_preview']){
			return et_ajax_element_holder( 'et_blog_carousel', $atts, 'slider' );
		}


		$output = function_exists('etheme_slider') ? etheme_slider( $options['wp_query_args'], 'post', $atts ) : '';

		if ( $atts['is_preview'] ) 
			echo parent::initPreviewJs();

        unset($atts);
        unset($options);

		return $output;
    }
}