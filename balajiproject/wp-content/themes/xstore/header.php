<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo (get_query_var('et_is_customize_preview', false)) ? 'class="no-scrollbar"' : ''; ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0<?php if ( !get_theme_mod('mobile_scalable', false) ) : ?>, maximum-scale=1.0, user-scalable=0<?php endif; ?>"/>
	<?php wp_head(); ?>
</head>
<?php $mode = etheme_get_option('dark_styles', 0) ? 'dark' : 'light'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited valid use case ?>
<body <?php body_class(); ?> data-mode="<?php echo esc_attr( $mode ); ?>">
<?php if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
	} else {
		do_action( 'wp_body_open' );
} ?>

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>

<?php do_action( 'et_after_body', true ); ?>

<div class="template-container">

	<?php
		/**
		 * Hook: etheme_header_before_template_content.
		 *
		 * @hooked etheme_top_panel_content - 10
		 * @hooked etheme_mobile_menu_content - 20
		 *
		 * @version 6.0.0 +
		 * @since 6.0.0 +
		 *
		 */
		do_action( 'etheme_header_before_template_content' );
	 ?>
	<div class="template-content">
		<div class="page-wrapper">
			<?php 
			/**
			 * Hook: etheme_header.
			 *
			 * @hooked etheme_header_content - 10
			 *
			 * @version 6.0.0 +
			 * @since 6.0.0 +
			 *
			 */
            do_action( 'etheme_header_start' );
			if ( get_query_var('et_mobile-optimization', false) ) {
			    if ( get_query_var('is_mobile', false) ) {
				    do_action( 'etheme_header_mobile' );
                }
			    else {
				    do_action( 'etheme_header' );
                }
            }
			else {
				do_action( 'etheme_header' );
				do_action( 'etheme_header_mobile' );
			}
            do_action( 'etheme_header_end' );
			
endif;