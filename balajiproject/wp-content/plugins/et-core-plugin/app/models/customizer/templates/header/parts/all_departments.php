<?php
	/**
	 * The template for displaying header all departments menu 
	 *
	 * @since   1.4.0
	 * @version 1.0.3
	 * last changes in 1.5.5
	 */
 ?>

 <?php 
 
    global $et_builder_globals;

    $element_options = array();
    $element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'All departments menu', 'xstore-core' ) . '"',
			'data-element="secondary_menu"'
		);

    wp_enqueue_script( 'all_departments_menu' );
    

?>

<div class="et_element et_b_header-menu flex align-items-center header-secondary-menu <?php echo ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' ); ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
    <?php echo all_departments_menu_callback(); ?>
</div>

<?php unset($element_options); ?>