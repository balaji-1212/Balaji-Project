<?php defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );
/**
 * The template for displaying theme footer.
 * Close divs started at the header.
 *
 * @since   1.0.0
 * @version 1.0.1
 */

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
/**
 * Hook: etheme_prefooter.
 *
 * @hooked etheme_prefooter_content - 10
 *
 * @version 1.0.0
 * @since 6.2.12
 *
 */
do_action( 'etheme_prefooter' );

?>

</div> <!-- page wrapper -->

<div class="et-footers-wrapper">
	<?php 
		/**
		 * Hook: etheme_footer.
		 *
		 * @hooked etheme_footer_content - 10
		 * @hooked etheme_copyrights_content - 20
		 *
		 * @version 1.0.0
		 * @since 6.2.12
		 *
		 */
		do_action( 'etheme_footer' );
	 ?>
</div>

</div> <!-- template-content -->

<?php do_action('after_page_wrapper'); ?>
</div> <!-- template-container -->

<?php endif; ?>


<?php
/* Always have wp_footer() just before the closing </body>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to reference JavaScript files.
 */

wp_footer();
?>
</body>

</html>