<?php
/**
 * The template for displaying header vertical
 *
 * @since   1.4.3
 * @version 1.0.0
 * last changes in 1.5.4
 */

?>

<?php 	
	
	if ( !class_exists('ETheme_Navigation') ) return;

	$header_vertical_options = array();
	
    $header_vertical_options['wrapper_class'] = ' pos-fixed left top flex flex-col mob-hide full-height children-align-inherit justify-content-center';

?>

<div class="site-header-vertical <?php echo $header_vertical_options['wrapper_class']; ?>" id="header-vertical" data-title="<?php esc_html_e( 'Header vertical', 'xstore-core' ); ?>">
	<?php echo header_vertical_callback(); ?>
</div>

<?php unset($header_vertical_options); ?>