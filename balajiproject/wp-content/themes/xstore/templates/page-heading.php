<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme page heading
 *
 * @since   6.4.5
 * @version 1.0.1
 */
//$l = etheme_page_config();

if ( get_query_var('et_page-banner', false) ) {
	echo '<div class="container">';
		etheme_static_block(get_query_var('et_page-banner', false), true);
	echo '</div>';
}

if (get_query_var('et_breadcrumbs', false)): ?>

    <?php
	
//	wp_enqueue_script( 'breadcrumbs' );

    ?>

	<div class="page-heading bc-type-<?php echo esc_attr( get_query_var('et_breadcrumbs-type', false) ); ?> bc-effect-<?php echo esc_attr( get_query_var('et_breadcrumbs-effect', false) ); ?> bc-color-<?php echo esc_attr( get_query_var('et_breadcrumbs-color', false) ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 a-center">
					<?php etheme_breadcrumbs(); ?>
				</div>
			</div>
		</div>
	</div>

<?php endif;

if(get_query_var('et_page-slider', false)): ?>
	<div class="page-heading-slider">
		<?php echo do_shortcode('[rev_slider alias="'.get_query_var('et_page-slider', false).'"][/rev_slider]'); ?>
	</div>
<?php endif;