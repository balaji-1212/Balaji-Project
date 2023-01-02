<?php 
/**
 * The template for single product custom html
 *
 * @since   1.5.0
 * @version 1.0.0
 * last changes in 1.5.5
 */

	$element_options = array();

	$element_options['attributes'] = array();
	if ( apply_filters('is_customize_preview', false) ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Single product custom HTML 2', 'xstore-core' ) . '"',
			'data-element="single_product_html_blocks"'
		); 
?>

<div class="et_element single_product-html_block single_product-html_block2" <?php echo implode( ' ', $element_options['attributes'] ); ?>><?php echo html_blocks_callback(array(
    	'section' => 'single_product_html_block2_section',
    	'sections' => 'single_product_html_block2_sections',
    	'html_backup' => 'single_product_html_block2',
    	'section_content' => true
	) ); ?></div>

<?php unset($element_options); ?>