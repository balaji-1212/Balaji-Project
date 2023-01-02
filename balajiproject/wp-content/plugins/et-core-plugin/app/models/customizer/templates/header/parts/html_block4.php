<?php
/**
 * The template for displaying header html_block4 block
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
			'data-title="' . esc_html__( 'Html block 4', 'xstore-core' ) . '"',
			'data-element="html_blocks"'
		);
?>

<div class="et_element et_b_header-html_block header-html_block4" <?php echo implode( ' ', $element_options['attributes'] ); ?>><?php echo html_blocks_callback(array(
    	'section' => 'html_block4_section',
    	'sections' => 'html_block4_sections',
    	'html_backup' => 'html_block4',
    	'section_content' => true
	) ); ?></div>

<?php unset($element_options); ?>