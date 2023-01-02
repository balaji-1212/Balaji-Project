<?php
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

remove_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
?>
<?php if ( !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) && (etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 )) ): ?>
	</div>
<?php endif; ?>
</div> <!-- .row -->