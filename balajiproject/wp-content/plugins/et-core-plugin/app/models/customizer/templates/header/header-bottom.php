<?php
/**
 * The template for displaying theme header-bottom
 *
 * @since   1.4.1
 * @version 1.0.0
 * last changes in 1.5.5
 */
?>

<?php
	$data = json_decode( get_theme_mod( 'header_bottom_elements', '' ), true );

	if ( ! is_array( $data ) ) {
		$data = array();
	}

    uasort( $data, function ( $item1, $item2 ) {
	    return $item1['index'] <=> $item2['index'];
	});

	if ( count($data) < 1 && !get_query_var('et_is_customize_preview', false) ) return;

	$header_options = array();
	$header_options['class'] = get_theme_mod( 'bottom_header_sticky_et-desktop', '0' ) ? 'sticky' : '';

?>

<div class="header-bottom-wrapper <?php echo esc_attr($header_options['class']); ?>">
	<div class="header-bottom" data-title="<?php esc_html_e( 'Header bottom', 'xstore-core' ); ?>">
		<div class="et-row-container<?php echo !(get_theme_mod( 'bottom_header_wide_et-desktop', '0' )) ? ' et-container' : ''; ?>">
			<div class="et-wrap-columns flex align-items-center"><?php echo header_bottom_callback(); ?></div><?php // to prevent empty spaces in DOM content ?>
		</div>
	</div>
</div>