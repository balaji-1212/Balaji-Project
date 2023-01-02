<?php
/**
 * The template for displaying header widget2 
 *
 * @since   1.4.0
 * @version 1.0.1
 * last changes in 1.5.5
 */
 ?>

<?php 
	$header_widget2 = get_theme_mod( 'header_widget2', '' );

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Widget 2', 'xstore-core' ) . '"',
			'data-element="header_widgets"'
		);
    add_filter('dynamic_sidebar_params', 'etheme_filter_widgets_classes');
?>

<div class="et_element et_b_header-widget align-items-center header-widget2" <?php echo implode( ' ', $element_options['attributes'] ); ?>><?php
	if(!function_exists('dynamic_sidebar') || !dynamic_sidebar($header_widget2)):
 	endif; ?></div>

<?php
remove_filter('dynamic_sidebar_params', 'etheme_filter_widgets_classes');
unset($header_widget2); ?>