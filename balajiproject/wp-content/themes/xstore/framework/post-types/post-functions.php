<?php
/**
 * Description
 *
 * @package    post-functions.php
 * @since      1.0.0
 * @author     andrey
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// **********************************************************************//
// ! Get gallery from content
// **********************************************************************//
if(!function_exists('etheme_gallery_from_content')) {
	function etheme_gallery_from_content($content) {
		
		$result = array(
			'ids' => array(),
			'filtered_content' => ''
		);
		
		preg_match('/\[gallery.*ids=.(.*).\]/', $content, $ids);
		if(!empty($ids)) {
			$result['ids'] = explode(",", $ids[1]);
			$content =  str_replace($ids[0], "", $content);
			$result['filtered_content'] = apply_filters( 'the_content', $content);
		}
		
		return $result;
		
	}
}

// **********************************************************************//
// ! Get post classes
// **********************************************************************//
if(!function_exists('etheme_post_class')) {
	function etheme_post_class( $layout = false ) {
		global $et_loop;
		
		$classes = array();
		$classes[] = 'blog-post';
		
		if ( ! empty( $et_loop['columns'] ) ) {
			if( $et_loop['columns'] < 1 ) $et_loop['columns'] = 1;
			$cols = 12/$et_loop['columns'];
			$classes[] = 'post-grid';
			$classes[] = 'col-md-' . $cols;
		}
		
		if ( isset($et_loop['loop'])) {
		    if ( $et_loop['loop'] < 1) {
			    $classes[] = 'grid-sizer';
            }
        }
		
		if ( ! empty( $et_loop['isotope'] ) ) {
			$classes[] = 'et-isotope-item';
        }
		
		$classes[] = 'byline-'.(etheme_get_option('blog_byline', 1) ? 'on' : 'off');
		
		$classes[] = 'content-'.(!$layout ? etheme_get_option('blog_layout', 'default') : $layout);
		
		if( ! empty( $et_loop['slide_view'] ) ) {
			$classes[] = 'slide-view-' . $et_loop['slide_view'];
		}
		
		if( ! empty( $et_loop['blog_align'] ) ) {
			$classes[] = 'blog-align-' . $et_loop['blog_align'];
		}
		return $classes;
	}
}

// **********************************************************************//
// ! Views counter
// **********************************************************************//
if(!function_exists('etheme_get_views')) {
	function etheme_get_views($id = false, $echo = false) {
		if( ! $id ) $id = get_the_ID();
		$number = get_post_meta( $id, '_et_views_count', true );
		if( empty($number) ) $number = 0;
		
		if ( $echo ) {
			echo '<span class="views-count">' . $number . '</span>';
		} else {
			return $number;
		}
	}
}

if(!function_exists('etheme_update_views')) {
	function etheme_update_views() {
		if( ! is_single() || ! is_singular( 'post' ) ) return;
		
		$id = get_the_ID();
		
		$number = etheme_get_views( $id );
		if( empty($number) ) {
			$number = 1;
			add_post_meta( $id, '_et_views_count', $number );
		} else {
			$number++;
			update_post_meta( $id, '_et_views_count', $number );
		}
	}
}
add_action( 'wp', 'etheme_update_views');


// **********************************************************************//
// ! Set excerpt
// **********************************************************************//
if(!function_exists('etheme_excerpt_length')) {
	function etheme_excerpt_length( $length ) {
		return (int)etheme_get_option('excerpt_length', 25);
	}
}
add_filter( 'excerpt_length', 'etheme_excerpt_length', 999 );

if(!function_exists('etheme_excerpt_length_sliders')) {
	function etheme_excerpt_length_sliders( $length ) {
		return (int)etheme_get_option('excerpt_length_sliders', 25);
	}
}

if( ! function_exists( 'etheme_excerpt_more' ) ) {
	function etheme_excerpt_more( $more ) {
		return etheme_get_option( 'excerpt_words', '...' );
	}
}

add_filter( 'excerpt_more', 'etheme_excerpt_more', 9999 );


// **********************************************************************//
// ! Get read more button text
// **********************************************************************//
if(!function_exists('etheme_read_more')) {
	function etheme_read_more( $link = false, $echo = false ) {
		$btn = etheme_get_option( 'read_more', 'link' );
		
		if ( $btn == 'off' || ! $link ) return;
		
		if ( $echo ) {
			printf(
				'<a href="%s" class="more-button"><span class="read-more%s">%s</span></a>',
				esc_url( $link ),
				( $btn == 'btn' ) ? ' btn medium active' : '',
				esc_html__( 'Continue reading', 'xstore' )
			);
		} else {
			return sprintf(
				'<a href="%s" class="more-button"><span class="read-more%s">%s</span></a>',
				esc_url( $link ),
				( $btn == 'btn' ) ? ' btn medium active' : '',
				esc_html__( 'Continue reading', 'xstore' )
			);
		}
	}
}

// **********************************************************************//
// ! Related posts
// **********************************************************************//
if(!function_exists('etheme_get_related_posts')) {
	function etheme_get_related_posts($postId = false, $limit = 5){
		global $post;
		if(!$postId) {
			$postId = $post->ID;
		}
		
		$query_type = etheme_get_option('related_query', 'categories');
		$atts = array(
			'title' => esc_html__( 'Related posts', 'xstore' ),
			'echo' => true,
			'large' => 3,
			'notebook' => 3,
			'tablet_land' => 2,
			'tablet_portrait' => 2,
			'mobile' => 1,
			'size' => etheme_get_option('blog_related_images_size', 'medium'),
			'autoheight' => false,
			'slider_autoplay' => false,
			'slider_speed' => false,
		);
		$args = array();
		if($query_type == 'tags') {
			$tags = get_the_tags($postId);
			if ($tags) {
				$tags_ids = array();
				foreach($tags as $tag) $tags_ids[] = $tag->term_id;
				
				$args = array(
					'tag__in' => $tags_ids,
					'post__not_in' => array($postId),
					'showposts'=>$limit, // Number of related posts that will be shown.
				);
			}
		} else {
			$categories = get_the_category($postId);
			if ($categories) {
				$category_ids = array();
				foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
				
				$args = array(
					'category__in' => $category_ids,
					'post__not_in' => array($postId),
					'showposts'=>$limit, // Number of related posts that will be shown.
				);
			}
		}
		etheme_slider( $args, 'post' , $atts );
	}
}

if( ! function_exists( 'etheme_blog_header' ) ){
	function etheme_blog_header() {
		$banner = etheme_get_option( 'blog_page_banner', '' );
		if( $banner != '' ){
			echo '<div class="category-description">';
			echo do_shortcode( $banner );
			echo '</div>';
		} else {
			return;
		}
	}
}

// **********************************************************************//
// ! Post content image
// **********************************************************************//

if( ! function_exists( 'etheme_post_thumb' ) ) {
	function etheme_post_thumb( $args = array() ) {
		global $et_loop;
		
		$defaults = array(
			'size' 		=> 'large',
			'in_slider' => false,
			'link' 		=> true,
			'ID'        => null
		);
		
		$args 		 = wp_parse_args( $args, $defaults );
		$post_format = get_post_format($args['ID']);
		$post_category = etheme_get_option('blog_categories', 1);
		$primary_category = ( $post_category ) ? etheme_primary_category() : '';
		
		?>
		<?php if( $post_format == 'gallery' && ! $args['in_slider'] ): ?>
			<?php $gallery_filter = etheme_gallery_from_content( get_the_content(null, false, $args['ID']) ); ?>
			
			<?php if( count( $gallery_filter['ids'] ) > 0 ): ?>
				<div class="swiper-entry et_post-slider arrows-effect-static">
					<div class="swiper-container slider_id-<?php echo rand( 100, 10000 ); ?>" data-autoheight="1">
						<?php if ( $post_category ) etheme_primary_category(true); ?>
						<div class="swiper-wrapper">
							<?php
							foreach ( $gallery_filter['ids'] as $attach_id ) {
								echo '<div class="swiper-slide">' . etheme_get_image( $attach_id, $args['size'] ) . '</div>';
							}
							?>
						</div>
						<div class="swiper-pagination"></div>
						<div class="swiper-custom-left"></div>
						<div class="swiper-custom-right"></div>
					</div>
				</div>
			<?php endif;
		
		elseif( $post_format == 'video' ):
			etheme_the_post_field( 'video', $args['ID'], $primary_category);
		
		elseif( $post_format == 'audio' ):
			etheme_the_post_field( 'audio', $args['ID'], $primary_category);
		
		elseif( has_post_thumbnail($args['ID']) ):
			$location = ( $args['in_slider'] ) ? 'slider' : '';
			$hover 	  = ( ! empty( $et_loop['blog_hover'] ) ) ? $et_loop['blog_hover'] : etheme_get_option( 'blog_hover', 'zoom' );
			?>
			
			<div class="wp-picture blog-hover-<?php echo esc_attr( $hover ); ?>">
				
				<?php if ( $args['link']): ?>
					<a href="<?php the_permalink($args['ID']); ?>">
						<?php echo etheme_get_image( get_post_thumbnail_id($args['ID']), $args['size'], $location ); ?>
					</a>
				<?php else:
					echo etheme_get_image( get_post_thumbnail_id($args['ID']), $args['size'], $location );
				endif;
				
				if ( $post_category ) etheme_primary_category(true);
				
				if ( (! is_single() || $args['in_slider']) && $hover != 'none' ): ?>
					<div class="blog-mask">
						<?php if( $post_format != 'quote' ): ?>
							<div class="blog-mask-inner">
								<div class="svg-wrapper">
									<a href="<?php the_permalink($args['ID']); ?>">
										<svg height="40" width="150" xmlns="http://www.w3.org/2000/svg">
											<rect class="shape" height="40" width="150" />
										</svg>
										<span class="btn btn-read-more style-custom"><?php esc_html_e( 'Read more', 'xstore' ); ?></span>
									</a>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php endif ?>
				
				<?php if( $post_format == 'quote' ): ?>
					<div class="featured-quote">
						<div class="quote-content">
							<?php etheme_the_post_field( 'quote', $args['ID'], $primary_category ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
	}
}

// **********************************************************************//
// ! Meta data block (byline)
// **********************************************************************//
if( ! function_exists( 'etheme_byline' ) ) {
	function etheme_byline($atts = array() ) {
		
		$atts = shortcode_atts( array(
			'author' => 0,
			'time' => 0,
			'slide_view' => 0,
			'ID' => null,
			'views_counter' => true,
            'in_slider' => false,
            'single' => false
		), $atts );
		
		$blog_layout 		   = etheme_get_option( 'blog_layout', 'default' );
		if ( $atts['in_slider'] ) {
			$atts['views_counter'] = false;
        }
		
		?>
		<div class="meta-post">
			<?php do_action('etheme_before_post_meta_content', $atts['single']); ?>
			<?php if( ! in_array( $blog_layout , array( 'timeline', 'timeline2', 'grid2' ) ) || $atts['single'] ): ?>
				<time class="entry-date published updated" datetime="<?php echo get_the_time('F j, Y', $atts['ID']); ?>">
					<?php echo get_the_time(get_option('date_format'), $atts['ID']); ?></time>
				
				<?php if ( $atts['time'] ):
					esc_html_e( 'at', 'xstore' );
					echo get_the_time( get_option( 'time_format' ), $atts['ID']);
				endif;
				
				if ( $atts['author'] ):
					esc_html_e( 'by', 'xstore' );
					the_author_posts_link();
				endif;
				
				if ( $atts['single'] ) :
                    echo '<span class="meta-divider">/</span>';
					echo '<span></span>';
                         esc_html_e( 'Posted by', 'xstore' );
                         the_author_posts_link();
				endif;
			
			elseif( $atts['slide_view'] == 'timeline2' ) :
				esc_html_e( 'Posted by', 'xstore' );
				the_author_posts_link();
			endif;
			
			if ( $atts['views_counter'] && etheme_get_option( 'views_counter', 1 ) ): ?>
				<span class="meta-divider">/</span>
				<?php etheme_get_views( $atts['ID'], true );
			endif;
			
			if(comments_open($atts['ID']) && ! post_password_required($atts['ID']) ) :
                if ( !$atts['in_slider']) { ?>
				    <span class="meta-divider">/</span>
                <?php }
                if ($atts['ID'] ):
					
					$comments_number = get_comments_number( $atts['ID'] );
					
					if ($comments_number === 0) {
						$comments_number = '<span>0</span>';
					} elseif($comments_number === 1){
						$comments_number = '<span>1</span>';
					} else{
						$comments_number = '<span>' . $comments_number . '</span>';
					}
					
					printf(
						'<a href="%s" class="post-comments-count">%s</a>',
						get_the_permalink($atts['ID']),
						$comments_number
					
					);
				
				else:
					comments_popup_link('<span>0</span>','<span>1</span>','<span>%</span>','post-comments-count');
				endif;
			endif; ?>
			<?php do_action('etheme_after_post_meta_content', $atts['single']); ?>
		</div>
		<?php
	}
}

// **********************************************************************//
// ! Display quantity of posts on the page.
// **********************************************************************//
if ( ! function_exists( 'etheme_count_posts' ) ) {
	
	function etheme_count_posts( $args = array() ) {
		$args = shortcode_atts( array(
			'skip_query'  => false,
			'total'       => 1,
			'first'       => '',
			'last'        => '',
			'echo'        => true
		), $args );
		
		if ( $args['skip_query'] ) {
			$total = $args['total'];
			$first = $args['first'];
			$last = $args['last'];
			$out = sprintf(
				esc_html_x(
					' %1$d&ndash;%2$d %4$s %3$d posts',
					'%1$d = first, %2$d = last, %3$d = total',
					'xstore'
				),
				$first,
				$last,
				$total,
				esc_html__( 'of', 'xstore' )
			);
		} else {
			global $wp_query;
			
			$paged    = max( 1, $wp_query->get( 'paged' ) );
			$per_page = $wp_query->get( 'posts_per_page' );
			$total    = $wp_query->found_posts;
			$first    = ( $per_page * $paged ) - $per_page + 1;
			$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );
			
			if ( $total == 1 ) {
				$out = esc_html__( 'the single result', 'xstore' );
			} elseif ( $total <= $per_page || -1 === $per_page ) {
				$out = sprintf( '%1$s %2$d %3$s' , esc_html__( 'all', 'xstore' ), $total, esc_html__( 'posts', 'xstore' ) );
			} else {
				$out = sprintf(
					esc_html_x(
						' %1$d&ndash;%2$d %4$s %3$d posts',
						'%1$d = first, %2$d = last, %3$d = total',
						'xstore'
					),
					$first,
					$last,
					$total,
					esc_html__( 'of', 'xstore' )
				);
			}
		}
		
		if ( $args['echo'] ) {
			return printf( '<p class="et_count-posts">%1$s %2$s</p>', esc_html__( 'Showing', 'xstore' ), $out );
		} else {
			return sprintf( '<p class="et_count-posts">%1$s %2$s</p>', esc_html__( 'Showing', 'xstore' ), $out );
		}
	}
};

if( ! function_exists( 'etheme_primary_category' ) ) {
	function etheme_primary_category( $echo = false ) {
		$primary = false;
		$cat = etheme_get_custom_field('primary_category');
		if(!empty($cat) && $cat != 'auto') {
			$primary = get_term_by( 'slug', $cat, 'category' );
		} else {
			$cats = wp_get_post_categories(get_the_ID());
			if( isset($cats[0]) ) {
				$primary = get_term_by( 'id', $cats[0], 'category' );
			}
		}
		if( $primary ) {
			$term_link = get_term_link( $primary );
			if ( $echo ) {
				echo '<div class="post-categories"><a href="' . esc_url( $term_link ) . '">' . $primary->name . '</a></div>';
			} else {
				return '<div class="post-categories"><a href="' . esc_url( $term_link ) . '">' . $primary->name . '</a></div>';
			}
		}
	}
}

if( ! function_exists( 'etheme_the_post_field' ) ) {
	function etheme_the_post_field( $field = false, $id = null, $html = '' ){
		if ( ! $field ) return;
		
		$data = etheme_get_custom_field( 'post_' . $field, $id );
		
		if ( empty( $data ) ) return;
		
		switch ( $field ) {
			case 'video':
				/*
                * Video parse from url
                * ******************************************************************* */
				require_once( apply_filters('etheme_file_url', ETHEME_CODE_3D . 'parse-video/VideoUrlParser.class.php') );
				$embed =  VideoUrlParser::get_url_embed( $data );
				if( ! empty( $embed ) ){
					echo '
						<div class="featured-' . $field . '">' . $html . '
							<iframe width="100%" height="560" src="' . $embed . '" frameborder="0" allowfullscreen></iframe>
						</div>
					';
				}
				break;
			case 'audio':
				echo '<div class="featured-' . $field . '">' . $html . do_shortcode( $data ) . '</div>';
				break;
			case 'quote':
				echo do_shortcode( $data );
				break;
			default:
				return;
				break;
		}
	}
}