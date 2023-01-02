<?php
/**
 * Single Product title
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if( (!get_option( 'etheme_single_product_builder', false ) && etheme_get_option('product_name_signle', 0) && !etheme_get_option('product_name_single_duplicated', 0)) || (get_option('etheme_single_product_builder') && ( etheme_get_option('product_breadcrumbs_mode_et-desktop', 'element') != 'element' && etheme_get_option('product_breadcrumbs_product_title_et-desktop', 0) && !etheme_get_option('product_breadcrumbs_product_title_duplicated_et-desktop', 0) ) ) ) {
    return;
}
?>
<h1 class="product_title entry-title"><?php the_title(); ?></h1>
