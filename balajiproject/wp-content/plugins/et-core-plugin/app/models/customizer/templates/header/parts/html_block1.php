<?php
/**
 * The template for displaying header html_block1 block
 *
 * @since   1.4.0
 * @version 1.0.1
 * last changes in 1.5.5
 */
 ?>

<?php 
	$element_options = array();

	$element_options['attributes'] = array();
	if ( apply_filters('is_customize_preview', false) ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Html block 1', 'xstore-core' ) . '"',
			'data-element="html_blocks"'
		); 
?>

<div class="et_element et_b_header-html_block header-html_block1" <?php echo implode( ' ', $element_options['attributes'] ); ?>><?php echo html_blocks_callback(array(
    	'section' => 'html_block1_section',
    	'sections' => 'html_block1_sections',
    	'html_backup' => 'html_block1',
    	'section_content' => true
	) ); ?></div>

<?php unset($element_options); ?>