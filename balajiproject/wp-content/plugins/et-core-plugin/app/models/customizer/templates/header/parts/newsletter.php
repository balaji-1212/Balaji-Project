<?php
	/**
	 * The template for displaying header newsletter block
	 *
	 * @since   1.4.0
	 * @version 1.0.3
	 * last changes in 1.5.5
	 */
 ?>

<?php 

	global $et_builder_globals;

	$element_options = array();
	
	$element_options['icon_type_et-desktop'] = get_theme_mod( 'newsletter_icon_et-desktop', 'type1' );
	$element_options['newsletter_icons_et-desktop'] = array (
		'type1' => (!get_theme_mod('bold_icons', 0) ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M23.928 5.424c-0.024-0.648-0.552-1.152-1.176-1.152h-21.504c-0.648 0-1.176 0.528-1.176 1.176v13.128c0 0.648 0.528 1.176 1.176 1.176h21.504c0.648 0 1.176-0.528 1.176-1.176v-13.152zM22.512 5.4l-10.512 6.576-10.512-6.576h21.024zM1.248 16.992v-10.416l7.344 4.584-7.344 5.832zM1.224 18.456l8.352-6.624 2.064 1.32c0.192 0.12 0.432 0.12 0.624 0l2.064-1.32 8.4 6.648 0.024 0.096c0 0 0 0.024-0.024 0.024h-21.48c-0.024 0-0.024 0-0.024-0.024v-0.12zM22.752 6.648v10.344l-7.344-5.808 7.344-4.536z"></path></svg>' : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="M15.952 3.744c-0.016-0.544-0.464-0.976-0.992-0.976h-13.92c-0.544 0-0.992 0.448-0.992 0.992v8.496c0 0.544 0.448 0.992 0.992 0.992h13.904c0.544 0 0.992-0.448 0.992-0.992l0.016-8.512zM13.984 3.968l-5.984 3.744-5.984-3.744h11.968zM1.28 10.752v-5.84l4.112 2.56-4.112 3.28zM6.448 8.176l1.2 0.768c0.208 0.128 0.448 0.128 0.656 0l1.2-0.768 4.88 3.856h-12.8l4.864-3.856zM14.72 4.96v5.792l-4.112-3.248 4.112-2.544z"></path></svg>'),
		'none' => ''
	);
	$element_options['newsletter_icon_et-desktop'] = $element_options['newsletter_icons_et-desktop'][$element_options['icon_type_et-desktop']];
	$element_options['newsletter_shown_on_et-desktop'] = get_theme_mod( 'newsletter_shown_on_et-desktop', 'click' );

	$element_options['newsletter_label_et-desktop'] = get_theme_mod( 'newsletter_label_et-desktop', 'Newsletter' );
	$element_options['newsletter_background_et-desktop'] = get_theme_mod( 'newsletter_background_et-desktop', array(
			'background-color'      => '#ffffff',
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center center',
			'background-size'       => '',
			'background-attachment' => '',
		)
	);
	$element_options['newsletter_label_show_et-desktop'] = get_theme_mod( 'newsletter_label_show_et-desktop', 1 );

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array(
		'data-type="newsletter"',
		'data-popup-on="' . $element_options['newsletter_shown_on_et-desktop'] . '"',
	);
	if ( $element_options['newsletter_shown_on_et-desktop'] == 'delay' ) {
		$element_options['attributes'][] = 'data-popup-delay="' . get_theme_mod( 'newsletter_delay_et-desktop', '300' ) . '"';
	}
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array_merge(
			$element_options['attributes'],
			array(
				'data-title="' . esc_html__( 'Newsletter', 'xstore-core' ) . '"',
				'data-element="newsletter"'
			)
		);

$element_options['newsletter_section_et-desktop']             = ( get_theme_mod( 'newsletter_sections_et-desktop', 0 ) ) ? get_theme_mod( 'newsletter_section_et-desktop', '' ) : '';

// tweak to init Elementor built content before ajax load popup
if ( $element_options['newsletter_section_et-desktop'] != '' ) {
	if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {
		$elementor_instance = Elementor\Plugin::instance();
		$output = $elementor_instance->frontend->get_builder_content_for_display( (int)$element_options['newsletter_section_et-desktop'] );
	}
}
?>

<div class="et_element et_b_header-newsletter et-popup_toggle align-items-center flex-inline pointer" <?php echo implode( ' ', $element_options['attributes'] ); ?>>	
	<span class="align-items-center flex-inline et-toggle">
		<?php if ( $element_options['newsletter_icon_et-desktop'] != '' || $et_builder_globals['is_customize_preview'] ) { ?>
			<span class="et_b-icon">
				<?php echo $element_options['newsletter_icon_et-desktop']; ?>
			</span>
		<?php } ?>
		
		<?php if ( $element_options['newsletter_label_show_et-desktop'] || $et_builder_globals['is_customize_preview'] ) { ?>
			<span class="et-element-label <?php echo ($et_builder_globals['is_customize_preview'] && !$element_options['newsletter_label_show_et-desktop'] ) ? 'none' : ''; ?>">
				<?php echo $element_options['newsletter_label_et-desktop']; ?>
			</span>
		<?php } ?>
	</span>
</div>

<?php unset($element_options); ?>