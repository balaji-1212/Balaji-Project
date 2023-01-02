<?php
	/**
	 * The template for displaying header contacts block
	 *
	 * @since   1.4.0
	 * @version 1.0.3
 	 * last changes in 1.5.5
 	*/
 ?>

<?php
	
	global $et_builder_globals;

	$element_options = array();

	$element_options['contacts_direction_et-desktop'] = get_theme_mod( 'contacts_direction_et-desktop', 'hor' );
	$element_options['contacts_alignment_et-desktop'] = get_theme_mod( 'contacts_alignment_et-desktop', 'start' );
	$element_options['contacts_content_alignment_et-desktop'] = $element_options['contacts_alignment_et-desktop'];

	$element_options['wrapper_class'] = '';

	if ( $et_builder_globals['in_mobile_menu'] ) {
        $element_options['contacts_direction_et-desktop'] = 'ver';
        $element_options['contacts_alignment_et-desktop'] = $element_options['contacts_content_alignment_et-desktop'] = 'start';
    }
    else {
        $element_options['wrapper_class'] .= ' et_element-top-level';
    }

	$element_options['wrapper_class'] .= ( $element_options['contacts_direction_et-desktop'] == 'hor' ) ? '' : ' flex-col';

	$element_options['contacts_direction_et-desktop'] = ( $element_options['contacts_direction_et-desktop'] == 'hor' ) ? ' flex-inline' : ' flex';
	$element_options['contacts_content_alignment_et-desktop'] = ($element_options['contacts_direction_et-desktop'] == 'hor') ? ' align-items-' . $element_options['contacts_content_alignment_et-desktop'] : 
	' justify-content-' . $element_options['contacts_content_alignment_et-desktop'];

	$element_options['wrapper_class'] .= ' ' . $element_options['contacts_content_alignment_et-desktop'];
	$element_options['wrapper_class'] .= ' ' . $element_options['contacts_direction_et-desktop'];
	$element_options['wrapper_class'] .= ' text-nowrap';

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Contacts', 'xstore-core' ) . '"',
			'data-element="contacts"'
		); 

?>

<div class="et_element et_b_header-contacts <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo header_contacts_callback(); ?>
</div>

<?php unset($element_options); ?>