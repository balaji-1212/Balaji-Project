<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Blog list shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Blog_List extends Shortcodes {

    function hooks() {}

    function blog_list_shortcode( $atts, $content ) {
	    if ( xstore_notice() )
		    return;

        global $et_loop;

        $options = array();

        $atts = shortcode_atts( array(
			'post_type'  => 'post',
			'include'  => '',
			'custom_query'  => '',
			'taxonomies'  => '',
			'items_per_page'  => 10,
			'items_limit' => 10,
			'orderby'  => 'date',
			'order'  => 'DESC',
			'meta_key'  => '',
			'blog_hover'  => 'zoom',
			'blog_align' => 'left',
			'exclude'  => '',
            'paged' => 0,
            'html_type' => false,
            'hide_img' => false,
			'size' => 'medium',
			'class'  => '',
			'blog_layout' => 'small',
		), $atts );

        $options['_paged'] = ( isset( $_GET['et-paged'] ) && ! empty( $_GET['et-paged'] ) ) ? $_GET['et-paged'] : false;

        if ( !$atts['items_limit'] ) 
        	$atts['items_limit'] = 10;

        if ( !$atts['items_per_page'] ) 
        	$atts['items_per_page'] = 10;
        
        if ( $atts['paged'] ) 
            $options['_paged'] = $atts['paged'];

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

		// start_items_per_page
		$options['start_items_pp'] = $atts['items_per_page'];
		$options['max_pages'] = ceil( $atts['items_limit'] / $atts['items_per_page'] );

		if ( $options['_paged'] > $options['max_pages'] ) {
			return '<p class="error">' . esc_html__( 'This page does not exist', 'xstore-core' ) . '</p>';
		}

		if ( $atts['items_limit'] < $atts['items_per_page'] ) 
			$atts['items_per_page'] = $atts['items_limit'];

		if ( $options['_paged'] > 1 ) {
			// items_per_page_finished
			$options['items_pp_f'] = ( $atts['items_per_page']*$options['_paged'] ) - $atts['items_limit'];
			$options['items_pp_f'] = ( $atts['items_per_page'] - $options['items_pp_f'] );

			// if ( $options['items_pp_f'] && $options['items_pp_f'] < $atts['items_per_page'] ) {
				//$atts['items_per_page'] = $options['items_pp_f'];
			// }
		}

		$options['wp_query']    = new \WP_Query($options['wp_query_args']);
		$options['page_url'] = get_permalink();
	
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'blog-global', true );
		    etheme_enqueue_style( 'post-small-chess', true );
		    if ( ! $atts['html_type'] ) {
			    etheme_enqueue_style( 'et-blog', true );
		    }
	    }

		ob_start();

		$et_loop['columns'] = 2;
		$et_loop['loop'] = 0;
		$et_loop['blog_layout'] = $atts['blog_layout'];
		$et_loop['blog_align'] = $atts['blog_align'];
		$et_loop['blog_hover'] = $atts['blog_hover'];
		$et_loop['size'] = $atts['size'];
		$et_loop['hide_img'] = $atts['hide_img'];

		if ( in_array( $et_loop['blog_layout'], array( 'list', 'chess', 'grid', 'small') ) ) 
			$et_loop['columns'] = 1;

		$options['smaller_from'] = 8;

		if( ! empty( $atts['blog_layout'] ) ) 
			$options['smaller_from'] = 999;

		$options['_paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$options['_paged'] = ( isset( $_GET['et-paged'] ) && ! empty( $_GET['et-paged'] ) ) ? $_GET['et-paged'] : $options['_paged'] ;

        if ( $atts['paged'] ) 
            $options['_paged'] = $atts['paged'];

        if ( $options['_paged'] >= $options['max_pages'] ) {
             $options['start_post'] = $atts['items_limit'] - $options['wp_query']->post_count;
             $options['last_post'] = $atts['items_limit'];
        } else {
            $options['start_post'] = ($options['_paged'] - 1) * $atts['items_per_page'] + 1;
            $options['last_post'] = ($options['_paged'] - 1) * $atts['items_per_page']  + $options['wp_query']->post_count;

        }

        if ( ! $atts['html_type'] ) 
            echo '<div class="et-blog clearfix">';

		$options['_i'] = 0;

		while ( $options['wp_query']->have_posts() ) :

			$options['wp_query']->the_post();

            if ( $options['_paged'] > 1 && $options['items_pp_f'] && 
            	$options['items_pp_f'] < $atts['items_per_page'] && 
            	$options['items_pp_f'] == $options['_i'] ) {
                break;
            }

			$options['_i']++;

			if( $options['_i'] == $options['smaller_from'] ) {
				$et_loop['size'] = 'thumbnail';
				echo '<div class="posts-small">';
			}

			get_template_part( 'content' );

		endwhile;

		if( $options['_i'] >= $options['smaller_from'] )
			echo '</div>';

		if ( $atts['items_limit'] > $options['wp_query']->found_posts ) 
			$atts['items_limit'] = $options['wp_query']->found_posts;

		if ( $options['max_pages'] == $options['_paged'] )
			$options['start_post'] = $options['start_post'] + 1;

		$options['before'] = etheme_count_posts(
			array(
				'skip_query' => true,
				'total' 	 => $atts['items_limit'], //$options['wp_query']->found_posts,
				'first'		 => $options['start_post'],
				'last' 		 => $options['last_post'],
				'echo' 		 => false
			) 
		);

		if ( $options['max_pages'] > $options['wp_query']->max_num_pages  ) {
			$options['max_pages'] = $options['wp_query']->max_num_pages;
		}

		if ( $options['wp_query']->max_num_pages > 0 ) {
			if ( function_exists('etheme_enqueue_style')) {
				etheme_enqueue_style( 'blog-ajax' );
				etheme_enqueue_style( 'pagination' );
			}
			$options['pagination_args'] = array(
				'type'   => 'custom',
				'url'    => $options['page_url'],
				'pages'  => $options['max_pages'], //$options['wp_query']->max_num_pages,
				'paged'  => $options['_paged'],
				'class'  => 'align-right',
				'before' => $options['before']
			);
			etheme_pagination( $options['pagination_args'] );
		}

        $atts['items_per_page'] = $options['start_items_pp'];
        
		?>

        <div class="et-load-blog">
            <span
                class="hidden et-element-args"
                type="text/template"
                data-element="et_blog_list"
            >
                <?php echo json_encode( $atts ); ?>   
            </span>
        </div>
		<?php if ( ! $atts['html_type'] )
            echo '</div>'; 


		wp_reset_postdata();
	    unset($et_loop['columns']);
	    unset($et_loop['loop']);
	    unset($et_loop['blog_layout']);
	    unset($et_loop['blog_align']);
	    unset($et_loop['blog_hover']);
	    unset($et_loop['size']);
	    unset($et_loop['hide_img']);
		unset($options);
		unset($atts);

		$output = ob_get_clean();
		ob_flush();

		return $output;
    }
}