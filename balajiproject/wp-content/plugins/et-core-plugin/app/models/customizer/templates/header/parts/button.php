<?php
	/**
	 * The template for displaying header button
	 *
	 * @since   1.4.0
	 * @version 1.0.3
	 * last changes in 1.5.5
	 */
 ?>

<?php 

	global $et_builder_globals;

	$element_options = array();
	$element_options['button_content_align_et-desktop'] = get_theme_mod( 'button_content_align_et-desktop', 'start' );
	$element_options['button_content_align_et-mobile'] = get_theme_mod( 'button_content_align_et-mobile', 'start' );

	$element_options['button_content_align'] = 'justify-content-'.$element_options['button_content_align_et-desktop'];
	$element_options['button_content_align'] .= ' mob-justify-content-'.$element_options['button_content_align_et-mobile'];

	if ( $et_builder_globals['in_mobile_menu'] ) {
		$element_options['button_content_align'] = 'justify-content-inherit';
	}

	$element_options['button_content_align'] = apply_filters('header_button_content_align', $element_options['button_content_align']);

	$element_options['wrapper_class'] = ' flex';
	$element_options['wrapper_class'] .= ' ' . $element_options['button_content_align'];
	$element_options['wrapper_class'] .= ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' );

?>
<div class="header-button-wrapper <?php echo $element_options['wrapper_class']; ?>">
	<?php echo header_button_callback(); ?>
</div>

<?php unset($element_options); ?>