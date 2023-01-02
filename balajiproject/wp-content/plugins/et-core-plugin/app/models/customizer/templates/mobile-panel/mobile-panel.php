<?php
	/**
	 * The template for displaying mobile panel
	 *
	 * @since   2.3.1
	 * @version 1.0.0 
 	*/
 ?>

<?php 

	global $et_icons;

	$element_options = array();

	$element_options['is_customize_preview'] = get_query_var('et_is_customize_preview', false);
	$element_options['mobile_panel'] = get_theme_mod('mobile_panel_et-mobile', '0');

	if ( !$element_options['mobile_panel'] && !$element_options['is_customize_preview'] ) return;

	$element_options['class'] = array();
	if ( !get_query_var('et_mobile-optimization', false) ) {
		$element_options['class'][] = 'dt-hide';
    }
	if ( !$element_options['mobile_panel'] && $element_options['is_customize_preview']) {
		$element_options['class'][] = 'mob-hide';
    }
	$element_options['attributes'] = array();

	if ( $element_options['is_customize_preview'] ) {
		$element_options['attributes'][] = 'data-title="' . esc_html__( 'Mobile panel', 'xstore-core' ) . '"';
		$element_options['attributes'][] = ' data-element="mobile_panel"';
	}
	
?>

<div class="et-mobile-panel-wrapper dt-hide etheme-sticky-panel et_element pos-fixed bottom full-width" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<div class="et-mobile-panel">
		<div class="et-row-container et-container">
			<div class="et-wrap-columns flex align-items-stretch justify-content-between"><?php echo etheme_mobile_panel_callback(); ?></div><?php // to prevent empty spaces in DOM content ?>
		</div>
	</div>
</div>

<?php
	unset($element_options);
    wp_enqueue_script('mobile_panel');
?>