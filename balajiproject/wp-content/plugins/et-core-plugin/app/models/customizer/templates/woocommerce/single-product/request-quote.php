<?php
/**
 * The template for displaying single product request quote button block
 *
 * @since   3.2.5
 * @version 1.0.0
 */
?>

<?php

$element_options = array();
$element_options['request_quote_button_content_align_et-desktop'] = get_theme_mod( 'request_quote_button_content_align_et-desktop', 'start' );

$element_options['request_quote_button_content_align'] = 'justify-content-'.$element_options['request_quote_button_content_align_et-desktop'];
$element_options['request_quote_button_content_align'] .= ' mob-justify-content-start';

$element_options['wrapper_class'] = ' flex';
$element_options['wrapper_class'] .= ' ' . $element_options['request_quote_button_content_align'];

wp_enqueue_script('call_popup');
if ( function_exists('etheme_enqueue_style')) {
	etheme_enqueue_style( 'single-product-request-quote', true );
}
?>
	<div class="single-product-request-quote-wrapper <?php echo $element_options['wrapper_class']; ?>">
		<?php echo single_product_request_quote_callback(); ?>
	</div>

<?php unset($element_options); ?>