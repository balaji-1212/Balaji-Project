<?php
/**
 * The template for displaying single product button block
 *
 * @since   1.5.0
 * @version 1.0.2
 * last changes in 1.5.5
 */
 ?>

<?php 

	$element_options = array();
	$element_options['single_product_button_content_align_et-desktop'] = get_theme_mod( 'single_product_button_content_align_et-desktop', 'start' );

	$element_options['single_product_button_content_align'] = 'justify-content-'.$element_options['single_product_button_content_align_et-desktop'];
	$element_options['single_product_button_content_align'] .= ' mob-justify-content-start';

	$element_options['wrapper_class'] = ' flex';
	$element_options['wrapper_class'] .= ' ' . $element_options['single_product_button_content_align'];

?>
<div class="single-product-button-wrapper <?php echo $element_options['wrapper_class']; ?>">
	<?php echo single_product_button_callback(); ?>
</div>

<?php unset($element_options); ?>