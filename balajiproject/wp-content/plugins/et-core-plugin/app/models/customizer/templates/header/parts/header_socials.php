<?php
	/**
	 * The template for displaying header socials block
	 *
	 * @since   1.4.0
	 * @version 1.0.2
  	 * last changes in 1.5.5
 	*/
 ?>

<?php 

	global $et_builder_globals;

	$element_options = array();
	$element_options['header_socials_direction_et-desktop'] = get_theme_mod( 'header_socials_direction_et-desktop', 'hor' );
	$element_options['header_socials_direction_et-desktop'] = apply_filters('header_socials_direction', $element_options['header_socials_direction_et-desktop']);

	$element_options['header_socials_content_alignment'] = get_theme_mod( 'header_socials_content_alignment_et-desktop', 'start' );
	$element_options['header_socials_content_alignment_et-mobile'] = get_theme_mod( 'header_socials_content_alignment_et-mobile', 'start' );

	$element_options['header_socials_content_alignment'] = ' justify-content-' . $element_options['header_socials_content_alignment'];
	$element_options['header_socials_content_alignment'] .= ' mob-justify-content-' . $element_options['header_socials_content_alignment_et-mobile'];

	if ( $et_builder_globals['in_mobile_menu'] ) {
        $element_options['header_socials_content_alignment'] = ' justify-content-center';
    }

    $element_options['header_socials_content_alignment'] = apply_filters('header_socials_content_alignment', $element_options['header_socials_content_alignment']);

    $element_options['class'] = $element_options['header_socials_content_alignment'];
    $element_options['class'] .= ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' );
    $element_options['class'] .= ( $element_options['header_socials_direction_et-desktop'] == 'ver' ) ? ' flex-col' : ' flex-row';

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Socials', 'xstore-core' ) . '"',
			'data-element="header_socials"'
		); 

?>

<div class="et_element et_b_header-socials et-socials flex flex-nowrap align-items-center <?php echo $element_options['class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo header_socials_callback(); ?>
</div>

<?php unset($element_options); ?>