<?php
defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

get_header();

$page_content = etheme_get_option('404_text', '');

?>

<?php do_action( 'etheme_page_heading' ); ?>

	<div class="container">
		<div class="page-content page-404">
			<div class="row">
				<div class="col-md-12">
					<?php if ( ! empty( $page_content ) ): ?>
						<?php echo do_shortcode( $page_content ); ?>
					<?php else: ?>
						<h2 class="largest">404</h2>
						<h1><?php esc_html_e('That Page Can\'t Be Found', 'xstore') ?></h1>
						<p><?php esc_html_e('It looks like nothing was found at this location. Try searching.', 'xstore') ?></p>
						<?php get_search_form( true ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();
?>