<?php
/**
 * The Template for displaying single portfolio project.
 *
 */

get_header();

?>

<?php do_action( 'etheme_page_heading' ); ?>

	<div class="container">
		<div class="page-content sidebar-position-without">
			<div class="row">
				<div class="content col-md-12">

					<?php if ( have_posts() ) : ?>

						<?php while ( have_posts() ) : the_post(); ?>

							<div class="portfolio-single-item">
								<?php the_content(); ?>
							</div>

						<?php endwhile; // End the loop. Whew. ?>

					<?php else: ?>

						<h3><?php esc_html_e('No pages were found!', 'xstore') ?></h3>

					<?php endif; ?>

					<?php if ( etheme_get_option( 'port_single_nav', 0 ) ) etheme_project_links( array() ); ?>

					<div class="clear"></div>

					<?php
						comments_template( '', true );
					?>

				</div>
			</div>

		</div>
	</div>

<?php
get_footer();
?>