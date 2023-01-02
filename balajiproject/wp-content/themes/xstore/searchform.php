<?php
/**
 * The template for displaying search forms
 *
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

?>
<div class="widget_search">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="hide-input" method="get">
		<div class="input-row">
			<input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e( 'Search...', 'xstore' ); ?>" />
			<button type="submit"><i class="et-icon et-zoom"></i><?php esc_html_e( 'Search', 'xstore' ); ?></button>
		</div>
	</form>
</div>