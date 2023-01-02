<?php
/**
 * Description
 *
 * @package    search-page.php
 * @since      8.3.6
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

add_action( 'wp', 'etheme_modify_search_page', 9 );

function etheme_modify_search_page(){
	
	if ( ( isset( $_GET['et_result'] ) && $_GET['et_result'] == 'products' ) || ! is_search() || !class_exists('WooCommerce') ) {
		return;
	}
	
	$search_content = etheme_get_option( 'search_results_content_et-desktop',
		array(
			'products',
			'posts'
		)
	);
	
	$search_aditional = etheme_get_option('search_page_custom_area_position_et-desktop', 'none');
	
	if ( ! is_array( $search_content ) ) {
		return;
	}
	
	if ( isset($_GET['et_search']) && is_search() && $search_aditional != 'none' ) {
		
		$custom_area = etheme_get_option('search_page_custom_area', '');
		$search_section = etheme_get_option('search_page_sections', 0);
		if ( $search_section ) {
			
			$custom_area = etheme_static_block( etheme_get_option('search_page_section', '') );
		}
		
		if ( $search_aditional == 'before' ) {
			add_action('etheme_before_product_loop_start', function($out) use($custom_area){
				echo do_shortcode( $custom_area );
				return;
			}, 5);
		} else {
			add_action('etheme_after_product_loop_end', function($out) use($custom_area){
				echo do_shortcode( $custom_area );
				return;
			}, 15);
		}
	}
	
	if ( in_array('products', $search_content) && woocommerce_product_loop() ) {
		add_filter('theme_mod_ajax_product_pagination', '__return_false');
		add_action( 'etheme_before_product_loop_start', function($count){
			printf(
				'<h2 class="products-title"><span>%s </span><span>%s</span></h2>',
				$count,
				_nx( 'Product found', 'Products found', $count, 'Search results page - products found text', 'xstore' )
			);
		}, 20 );
	}
	
	
	
	$i = 10;
	foreach ( $search_content as $key => $value ) {
		if ( $value == 'products' && woocommerce_product_loop() ) {
			$i = 20;
		} elseif( isset($_GET['et_search']) && $value != 'products' ) {
			if ($i == 10) {
				if ( in_array($value, $search_content) ) {
					add_action('etheme_before_product_loop_start','etheme_' . $value . '_in_search_results', $key + 10);
				}
			} else {
				if ( in_array($value, $search_content) ) {
					add_action('etheme_after_product_loop_end','etheme_' . $value . '_in_search_results', $key + 10);
				}
			}
		}
	}
}

function etheme_pages_in_search_results(){
	if(!is_search()) return;
	global $post;
	
	?>
	<?php if( get_search_query() ) : ?>
		<?php
		$args = array(
			's'                   => get_search_query(),
			'post_type'           => 'page',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			// 'posts_per_page'      => 50,
			'orderby'             => '',
			// 'post_type'           => array(),
			// 'post_status'         => 'publish',
			'posts_per_page'      => 50,
			// 'ignore_sticky_posts' => 1,
			'post_password'       => '',
			// 'suppress_filters'    => false,
		);
		
		$posts = get_posts( $args );
		$box_id      = rand( 1000, 10000 );
		
		if ( count($posts) ) {
			remove_action('woocommerce_no_products_found', 'wc_no_products_found', 10);
			
			printf(
				'<h2 class="products-title"><span>%s </span><span>%s</span></h2>',
				count($posts),
				_nx( 'Page found', 'Pages found', count($posts), 'Search results page - pages found text', 'xstore' )
			);
			
			$mobile = 1;
			$tablet_land = 2;
			$notebook = 3;
			$large = 3;
			
			$backup_style = get_query_var('is_mobile', false) ? $mobile : $large;
			$backup_style = 'style="width:'.(100/$backup_style).'%"';
			
			$swiper_slide_class_css = '.swiper-container.slider-'.$box_id.':not(.initialized) .swiper-slide';
			
			$media = $swiper_slide_class_css .' {width: '.(100/$mobile).'% !important;}';
			$media .= '@media only screen and (min-width: 640px) { '.$swiper_slide_class_css.' {width: '.(100/$tablet_land).'% !important;}}';
			$media .= '@media only screen and (min-width: 1024px) { '.$swiper_slide_class_css.' {width: '.(100/$notebook).'% !important;}}';
			$media .= '@media only screen and (min-width: 1370px) { '.$swiper_slide_class_css.' {width: '.(100/$large).'% !important;}}';
			
			wp_add_inline_style( 'xstore-inline-css', $media);
			
			echo '<div class="swiper-entry pages-result-slider"><div
                        class="swiper-container posts-slider carousel-area slider-' . $box_id . '"
                        data-breakpoints="1"
                        data-xs-slides="'.esc_js($mobile).'"
                        data-sm-slides="'.esc_js($tablet_land).'"
                        data-md-slides="'.esc_js($notebook).'"
                        data-lt-slides="'.esc_js($large).'"
                        data-slides-per-view="'.esc_js($large).'"
                    >';
			echo '<div class="swiper-wrapper">';
			foreach ($posts as $key => $value) {
				
				$postClass      = etheme_post_class( 'grid' );
				$postClass[] = 'col-md-6';
				
				echo '<div class="swiper-slide" '.$backup_style.'>';
				?>
				<article <?php post_class( $postClass ); ?> id="post-<?php echo esc_attr( $value->ID ); ?>">
					<div>
						
						<?php if ( ! empty( $et_loop['slide_view'] ) && $et_loop['slide_view'] == 'timeline2' ): ?>
							<div class="meta-post-timeline">
								<span class="time-day"><?php the_time('d'); ?></span>
								<span class="time-mon"><?php the_time('M'); ?></span>
							</div>
						<?php endif; ?>
						<?php
						//                                        $excerpt_length = etheme_get_option('excerpt_length', 25);
						etheme_post_thumb( array('size' => 'woocommerce_thumbnail', 'in_slider' => true, 'ID' => $value->ID) );
						
						?>
						
						<div class="grid-post-body">
							<div class="post-heading">
								<h2><a href="<?php the_permalink($value->ID); ?>"><?php echo esc_html( $value->post_title ); ?></a></h2>
								<?php if(etheme_get_option('blog_byline', 1)): ?>
									<?php etheme_byline( array( 'author' => 0, 'ID' => $value->ID, 'views_counter' => false, 'in_slider' => true ) );  ?>
								<?php endif; ?>
							</div>
							
							<div class="content-article">
								<?php etheme_read_more( get_the_permalink($value->ID), true ) ?>
							</div>
						</div>
					</div>
				</article>
				
				<?php
				echo '</div>';
			}
			
			echo '</div><!-- slider wrapper-->';
			echo '</div>';
			echo '<div class="swiper-button-prev swiper-custom-left"></div>
	                    <div class="swiper-button-next swiper-custom-right"></div>';
			echo '</div><div class="clear"></div><!-- slider-entry -->';
		}
		?>
	<?php endif; ?>
	
	<?php
}

function etheme_portfolio_in_search_results(){
	if(!is_search()) return;
	global $post;
	
	?>
	<?php if( get_search_query() ) : ?>
		<?php
		$args = array(
			's'                   => get_search_query(),
			'post_type'           => 'etheme_portfolio',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			// 'posts_per_page'      => 50,
			'orderby'             => '',
			// 'post_type'           => array(),
			// 'post_status'         => 'publish',
			'posts_per_page'      => 50,
			// 'ignore_sticky_posts' => 1,
			'post_password'       => '',
			// 'suppress_filters'    => false,
		);
		
		$posts = get_posts( $args );
		$box_id      = rand( 1000, 10000 );
		
		if ( count($posts) ) {
			remove_action('woocommerce_no_products_found', 'wc_no_products_found', 10);
			
			printf(
				'<h2 class="products-title"><span>%s </span><span>%s</span></h2>',
				count($posts),
				_nx( 'Portfolio found', 'Portfolios found', count($posts), 'Search results page - portfolios found text', 'xstore' )
			);
			
			$mobile = 1;
			$tablet_land = 2;
			$notebook = 3;
			$large = 3;
			
			$backup_style = get_query_var('is_mobile', false) ? $mobile : $large;
			$backup_style = 'style="width:'.(100/$backup_style).'%"';
			
			$swiper_slide_class_css = '.swiper-container.slider-'.$box_id.':not(.initialized) .swiper-slide';
			
			$media = $swiper_slide_class_css .' {width: '.(100/$mobile).'% !important;}';
			$media .= '@media only screen and (min-width: 640px) { '.$swiper_slide_class_css.' {width: '.(100/$tablet_land).'% !important;}}';
			$media .= '@media only screen and (min-width: 1024px) { '.$swiper_slide_class_css.' {width: '.(100/$notebook).'% !important;}}';
			$media .= '@media only screen and (min-width: 1370px) { '.$swiper_slide_class_css.' {width: '.(100/$large).'% !important;}}';
			
			wp_add_inline_style( 'xstore-inline-css', $media);
			
			echo '<div class="swiper-entry portfolios-result-slider"><div
                        class="swiper-container posts-slider carousel-area slider-' . $box_id . '"
                        data-breakpoints="1"
                        data-xs-slides="'.esc_js($mobile).'"
                        data-sm-slides="'.esc_js($tablet_land).'"
                        data-md-slides="'.esc_js($notebook).'"
                        data-lt-slides="'.esc_js($large).'"
                        data-slides-per-view="'.esc_js($large).'"
                    >';
			echo '<div class="swiper-wrapper">';
			foreach ($posts as $key => $value) {
				
				$postClass      = etheme_post_class( 'grid' );
				$postClass[] = 'col-md-6';
				
				echo '<div class="swiper-slide" '.$backup_style.'>';
				?>
				<article <?php post_class( $postClass ); ?> id="post-<?php echo esc_attr( $value->ID ); ?>">
					<div>
						
						<?php if ( ! empty( $et_loop['slide_view'] ) && $et_loop['slide_view'] == 'timeline2' ): ?>
							<div class="meta-post-timeline">
								<span class="time-day"><?php the_time('d'); ?></span>
								<span class="time-mon"><?php the_time('M'); ?></span>
							</div>
						<?php endif; ?>
						<?php
						//                                        $excerpt_length = etheme_get_option('excerpt_length' , 25);
						etheme_post_thumb( array('size' => 'woocommerce_thumbnail', 'in_slider' => true, 'ID' => $value->ID) );
						
						?>
						
						<div class="grid-post-body">
							<div class="post-heading">
								<h2><a href="<?php the_permalink($value->ID); ?>"><?php echo esc_html( $value->post_title ); ?></a></h2>
								<?php if(etheme_get_option('blog_byline', 1)): ?>
									<?php etheme_byline( array( 'author' => 0, 'ID' => $value->ID, 'views_counter' => false ) );  ?>
								<?php endif; ?>
							</div>
							
							<div class="content-article">
								<?php etheme_read_more( get_the_permalink($value->ID), true ) ?>
							</div>
						</div>
					</div>
				</article>
				
				<?php
				echo '</div>';
			}
			
			echo '</div><!-- slider wrapper-->';
			echo '</div>';
			echo '<div class="swiper-button-prev swiper-custom-left"></div>
	                    <div class="swiper-button-next swiper-custom-right"></div>';
			echo '</div><div class="clear"></div><!-- slider-entry -->';
		}
		?>
	<?php endif; ?>
	
	<?php
}

function etheme_posts_in_search_results(){
	if(!is_search()) return;
	global $post;
	?>
	<?php if( get_search_query() ) : ?>
		<?php
		// wp_reset_postdata();
		$args = array(
			's'                   => get_search_query(),
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => 50,
		);
		
		$posts = get_posts( $args );
		$box_id      = rand( 1000, 10000 );
		
		// echo '<h4>' . esc_html__( 'Posts found', 'xstore' ) .  '</h4>';
		if ( count($posts) ) {
			remove_action('woocommerce_no_products_found', 'wc_no_products_found', 10);
			printf(
				'<h2 class="products-title"><span>%s </span><span>%s</span></h2>',
				count($posts),
				_nx( 'Post found', 'Posts found', count($posts), 'Search results page - posts found text', 'xstore' )
			);
			
			$mobile = 1;
			$tablet_land = 2;
			$notebook = 3;
			$large = 3;
			
			$backup_style = get_query_var('is_mobile', false) ? $mobile : $large;
			$backup_style = 'style="width:'.(100/$backup_style).'%"';
			
			$swiper_slide_class_css = '.swiper-container.slider-'.$box_id.':not(.initialized) .swiper-slide';
			
			$media = $swiper_slide_class_css .' {width: '.(100/$mobile).'% !important;}';
			$media .= '@media only screen and (min-width: 640px) { '.$swiper_slide_class_css.' {width: '.(100/$tablet_land).'% !important;}}';
			$media .= '@media only screen and (min-width: 1024px) { '.$swiper_slide_class_css.' {width: '.(100/$notebook).'% !important;}}';
			$media .= '@media only screen and (min-width: 1370px) { '.$swiper_slide_class_css.' {width: '.(100/$large).'% !important;}}';
			
			wp_add_inline_style( 'xstore-inline-css', $media);
			
			echo '<div class="swiper-entry posts-result-slider"><div
                        class="swiper-container posts-slider carousel-area slider-' . $box_id . '"
                        data-breakpoints="1"
                        data-xs-slides="'.esc_js($mobile).'"
                        data-sm-slides="'.esc_js($tablet_land).'"
                        data-md-slides="'.esc_js($notebook).'"
                        data-lt-slides="'.esc_js($large).'"
                        data-slides-per-view="'.esc_js($large).'"
                    >';
			echo '<div class="swiper-wrapper">';
			foreach ($posts as $key => $value) {
				
				$postClass      = etheme_post_class( 'grid' );
				
				$postClass[] = 'col-md-6';
				
				echo '<div class="swiper-slide" '.$backup_style.'>';
				?>
				<article <?php post_class( $postClass ); ?> id="post-<?php echo esc_attr( $value->ID ); ?>">
					<div>
						
						<?php if ( ! empty( $et_loop['slide_view'] ) && $et_loop['slide_view'] == 'timeline2' ): ?>
							<div class="meta-post-timeline">
								<span class="time-day"><?php the_time('d'); ?></span>
								<span class="time-mon"><?php the_time('M'); ?></span>
							</div>
						<?php endif; ?>
						<?php
						$excerpt_length = etheme_get_option('excerpt_length', 25);
						etheme_post_thumb( array('size' => 'woocommerce_thumbnail', 'in_slider' => true, 'ID' => $value->ID) );
						
						?>
						
						<div class="grid-post-body">
							<div class="post-heading">
								<h2><a href="<?php the_permalink($value->ID); ?>"><?php echo esc_html( $value->post_title ); ?></a></h2>
								<?php if(etheme_get_option('blog_byline', 1)): ?>
									<?php etheme_byline( array( 'author' => 0, 'ID' => $value->ID, 'in_slider' => true ) );  ?>
								<?php endif; ?>
							</div>
							
							<div class="content-article">
								<?php if ( $excerpt_length > 0 ) {
									if ( strlen(get_the_excerpt($value->ID)) > 0 ) {
										$excerpt_length = apply_filters( 'excerpt_length', $excerpt_length );
										$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
										$text         = wp_trim_words( get_the_excerpt($value->ID), $excerpt_length, $excerpt_more );
										echo apply_filters( 'wp_trim_excerpt', $text, $text );
									}
									else {
										echo apply_filters( 'the_excerpt', get_the_excerpt($value->ID) );
									}
								}  ?>
								<?php etheme_read_more( get_the_permalink($value->ID), true ) ?>
							</div>
						</div>
					</div>
				</article>
				
				<?php
				echo '</div>';
			}
			
			echo '</div><!-- slider wrapper-->';
			echo '</div>';
			echo '<div class="swiper-button-prev swiper-custom-left"></div>
	                    <div class="swiper-button-next swiper-custom-right"></div>';
			echo '</div><div class="clear"></div><!-- slider-entry -->';
		}
		// wp_reset_query();
		?>
	<?php endif; ?>
	
	<?php
}