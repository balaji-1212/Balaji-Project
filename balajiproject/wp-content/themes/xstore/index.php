<?php
/**
 * The main template file.
 *
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

get_header();

global $et_loop;

$content_layout = etheme_get_option('blog_layout', 'default');
$navigation_type = etheme_get_option( 'blog_navigation_type', 'pagination' );

$full_width = false;

$class = ' hfeed et_blog-ajax';

$banner_pos = etheme_get_option( 'blog_page_banner_pos', 1 );

if ( in_array($content_layout, array('grid', 'grid2'))  ) {
//	if($content_layout == 'grid') {
//		$full_width = etheme_get_option('blog_full_width', 0);
//		$content_layout = 'grid';
//	}

//	if ( $content_layout == 'grid2' ) {
		$full_width = etheme_get_option('blog_full_width', 0);
//		$content_layout = 'grid-2';
//	}
	$content_layout = str_replace('grid2', 'grid-2', $content_layout);
	$class .= ' row';
	if ( etheme_get_option( 'blog_masonry', 1 ) ) {
		wp_enqueue_script( 'et_isotope');
        $class .= ' blog-masonry';
        $class .= ' et-isotope';
		$et_loop['isotope'] = true;
    }
}

?>

<?php do_action( 'etheme_page_heading' ); ?>

<?php if ( $banner_pos == 4 ) { 
	if ( is_category() && category_description() ) : ?>
		<div class="blog-category-description"><?php echo do_shortcode( category_description() ); ?></div>
	<?php else:
		etheme_blog_header();
	endif;
} ?>

	<div class="content-page <?php echo ( ! $full_width ) ? 'container' : 'blog-full-width'; ?> sidebar-mobile-<?php echo esc_attr( get_query_var('et_sidebar-mobile', 'bottom') ); ?>">
		<?php if ( $banner_pos == 3 ) { 
			if ( is_category() && category_description() ) : ?>
				<div class="blog-category-description"><?php echo do_shortcode( category_description() ); ?></div>
			<?php else:
				etheme_blog_header();
			endif; 
		} ?>
		<div class="sidebar-position-<?php echo esc_attr( get_query_var('et_sidebar', 'left') ); ?>">
			<div class="row">
				<div class="content <?php echo esc_attr( get_query_var('et_content-class', 'col-md-9') ); ?>">
					<?php 
					if( $banner_pos == 1 ) {
						if ( is_category() && category_description() ) : ?>
							<div class="blog-category-description"><?php echo do_shortcode( category_description() ); ?></div>
						<?php else:
							etheme_blog_header();
						endif;
					} ?>
					<div class="<?php echo esc_attr($class); ?>">
						<?php if(have_posts()):
							while(have_posts()) : the_post(); ?>

								<?php get_template_part('content', $content_layout); ?>

							<?php endwhile; ?>
						<?php else: ?>

							<div class="col-md-12">

								<h2><?php esc_html_e('No posts were found!', 'xstore') ?></h2>

								<p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords', 'xstore') ?></p>

								<?php get_search_form(); ?>

							</div>

						<?php endif; ?>
					</div>

					<?php
						switch ( $navigation_type ) {
							case 'pagination':
								global $wp_query;
								$pag_align = etheme_get_option( 'blog_pagination_align', 'right' );

								$paginate_args = array(
									'pages'  => $wp_query->max_num_pages,
									'paged'  => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
									'class'  => 'articles-pagination align-' . esc_attr( $pag_align ),
									'before' => etheme_count_posts( array( 'echo' => false ) ),
									'prev_text' => esc_html__( 'Prev page', 'xstore' ),
									'next_text' => esc_html__( 'Next page', 'xstore' ),
									'prev_next' => true
								);

								etheme_pagination( $paginate_args );
							 	break;

							case 'button': ?>
								<?php if ( get_next_posts_link(null) != '' ) : ?>
									<div class="et-load-block text-center et_load-posts button-loading" data-loaded="<?php esc_html_e( 'No more posts to load', 'xstore' ) ?>">
										<?php etheme_loader(true, 'no-lqip'); ?>
										<span class="btn"><?php next_posts_link( esc_html__( 'Load More Posts', 'xstore' ) ); ?></span>
									</div>
								<?php endif; ?>
								<?php break;

							case 'lazy': ?>
								<?php if ( get_next_posts_link(null) != '' ) : ?>
									<div class="et-load-block et_load-posts lazy-loading" data-loaded="<?php esc_html_e( 'No more posts to load', 'xstore' ) ?>" data-loading="<?php esc_html_e( 'Loading', 'xstore' ) ?>">
										<?php etheme_loader(true, 'no-lqip'); ?>
										<span class="btn"><?php next_posts_link(); ?></span>
									</div>
								<?php endif; ?>
								<?php break;

							default: ?>
								
								<?php break;
						}
					if( $banner_pos == 2 ): 
						if ( is_category() && category_description() ) : ?>
							<div class="blog-category-description"><?php echo do_shortcode( category_description() ); ?></div>
						<?php else:
							etheme_blog_header();
						endif;
					endif;
		 			?>
				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>

<?php
get_footer();