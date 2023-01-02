<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

get_header();

$full_width = etheme_get_option('portfolio_fullwidth', 0);

$class = ( $full_width ) ? 'port-full-width' : 'container';

?>

<?php do_action( 'etheme_page_heading' ); ?>

	<div class="<?php echo esc_attr($class); ?>">
		<div class="page-content sidebar-position-without">
			<div class="content">
				<?php if ( ! etheme_xstore_plugin_notice() ):
					if( have_posts() && get_query_var( 'portfolio_category' ) == '' ): while( have_posts() ) : the_post();
	                    the_content();
	                endwhile; endif;
	                
					 if ( get_query_var( 'portfolio_category' ) && term_description()):
						 echo '<div class="portfolio-category-description">' . term_description() . '</div>';
					 endif;

					 if ( get_query_var( 'et_portfolio-projects', false ) ) {
                        etheme_portfolio();
                     }
					?>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php
get_footer();
?>