<?php 
/**
 * The template for single product custom html
 *
 * @since   2.1.4
 * @version 1.0.0
 * last changes in 2.1.4
 */
	
	$element_options = array();

	$element_options['attributes'] = array();
	if ( apply_filters('is_customize_preview', false) ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Single product additional custom block', 'xstore-core' ) . '"',
		);

	$content = etheme_get_custom_field( 'additional_block', get_the_ID());

	if ( $content != '' && $content > 0 ) {

	?>

	<div class="et_element single_product-additional-custom-block" <?php echo implode( ' ', $element_options['attributes'] ); ?>><?php 
		if(class_exists('WPBMap') && method_exists('WPBMap', 'addAllMappedShortcodes'))
	        WPBMap::addAllMappedShortcodes();

				$section_css = get_post_meta($content, '_wpb_shortcodes_custom_css', true);
		        if(!empty($section_css)) {
		            echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
		            echo strip_tags($section_css);
		            echo '</style>';
		        }

		        etheme_static_block($content, true);

	?></div>

	<?php }

unset($element_options); ?>