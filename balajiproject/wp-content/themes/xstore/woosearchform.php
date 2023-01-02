<?php
/**
 * The template for displaying search forms
 *
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

?>

<form action="<?php echo ( class_exists('WooCommerce') ) ? esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) : esc_url( home_url( '/' ) ); ?>" role="search" class="" method="get">
	<div class="input-row">
		<input type="text" value="" placeholder="<?php esc_attr_e( 'Type here...', 'xstore' ); ?>" autocomplete="off" class="form-control" name="s" />
		<input type="hidden" name="post_type" value="product" />
		<?php if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) ) : ?>
			<input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/>
		<?php endif ?>
		<button type="submit"><?php esc_html_e( 'Search', 'xstore' ); ?><i class="et-icon et-zoom"></i></button>
	</div>
</form>