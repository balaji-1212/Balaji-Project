<?php
/**
 * The template for displaying header html_block5 block
 *
 * @since   4.3.2
 * @version 1.0.0
 */
 ?>

<?php 
	$element_options = array();

	$element_options['attributes'] = array();
	if ( apply_filters('is_customize_preview', false) ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Html block 5', 'xstore-core' ) . '"',
			'data-element="html_blocks"'
		);
?>

<div class="et_element et_b_header-html_block header-html_block5" <?php echo implode( ' ', $element_options['attributes'] ); ?>><?php echo html_blocks_callback(array(
    	'section' => 'html_block5_section',
    	'sections' => 'html_block5_sections',
    	'html_backup' => 'html_block5',
    	'section_content' => true
	) ); ?></div>

<?php unset($element_options); ?>