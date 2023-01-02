<?php
/**
 * Description
 *
 * @package    wcfmmp.php
 * @since      8.3.6
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

add_action( 'wcfmmp_woocommerce_before_shop_loop_before', function () {
	etheme_enqueue_style('filter-area', true ); ?>
	<div class="filter-wrap">
	<div class="filter-content">
<?php }, 0 );

add_action( 'wcfmmp_woocommerce_before_shop_loop_after', function () { ?>
	</div>
	</div>
<?php }, 100000 );