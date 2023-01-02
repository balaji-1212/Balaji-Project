<?php
	/**
	 * The template for displaying header secondary menu block
	 *
	 * @since   1.4.0
	 * @version 1.0.5
	 * last changes in 1.5.5
	 */
 ?>

<?php 

    global $et_builder_globals;

	$element_options = array();
	$element_options['menu_2_item_style_et-desktop'] = get_theme_mod( 'menu_2_item_style_et-desktop', 'underline' );
	$element_options['menu_2_alignment_et-desktop'] = get_theme_mod( 'menu_2_alignment_et-desktop', 'center' );
	$element_options['menu_2_alignment_et-desktop'] = ' justify-content-'.str_replace('flex-', '', $element_options['menu_2_alignment_et-desktop']);
	$element_options['wrapper_class'] = 'menu-items-' . $element_options['menu_2_item_style_et-desktop'];
	$element_options['wrapper_class'] .= ' ' . $element_options['menu_2_alignment_et-desktop'];
	$element_options['wrapper_class'] .= ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' );

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Secondary menu', 'xstore-core' ) . '"',
			'data-element="main_menu_2"'
		); 
	
?>

<div class="et_element et_b_header-menu header-main-menu2 flex align-items-center <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo main_menu2_callback(); ?>
</div>

<?php 

unset($element_options); ?>