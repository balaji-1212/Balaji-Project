<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( isset( $_GET['et_ajax'] ) && $_GET['et_ajax'] ) {
	et_ajax_shop();
}

get_header( 'shop' );

$full_width = etheme_get_option('shop_full_width', 0);

if($full_width) {
	$content_span = 'col-md-12';
}
$class = $sidebar_class = '';

if ( get_query_var('et_page-banner', false) && is_shop()) {
	echo '<div class="container">';
	etheme_static_block(get_query_var('et_page-banner', false), true);
	echo '</div>';
}

/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );
?>
<?php
$loop = wc_get_loop_prop( 'columns' );
$view_mode = get_query_var('et_view-mode');
$cat_sidebar = get_query_var('et_cat-sidebar');

if ( ($loop > 3 && ( get_query_var('et_sidebar', 'left') != 'without' || !$full_width ) ) || ( $view_mode == 'grid' && $loop > 5 ) ) {
	$class .= ' products-hover-only-icons';
}

?>

<?php if ( etheme_get_option( 'product_bage_banner_pos', 1 ) == 4 ) {
	etheme_category_header();
	do_action( 'woocommerce_archive_description' );
} ?>
    <div class="<?php echo (!$full_width) ? 'container' : 'shop-full-width'; ?> sidebar-mobile-<?php echo esc_attr( get_query_var('et_sidebar-mobile', 'off_canvas') ); ?> content-page <?php echo esc_attr($class); ?>">
		<?php if ( etheme_get_option( 'product_bage_banner_pos', 1 ) == 3 ) {
			etheme_category_header();
			do_action( 'woocommerce_archive_description' );
		}  ?>
        <div class="sidebar-position-<?php echo esc_attr( get_query_var('et_sidebar', 'left') ); ?>">
            <div class="row">

                <div class="content main-products-loop <?php echo esc_attr( get_query_var('et_content-class', 'col-md-9') ); ?>">
					
					<?php if ( woocommerce_product_loop() ) : ?>
						
						<?php wc_print_notices(); ?>
						
						<?php if ( etheme_get_option( 'product_bage_banner_pos', 1 ) == 1 ) {
							etheme_category_header();
							do_action( 'woocommerce_archive_description' );
						} ?>
						
						<?php if ( woocommerce_products_will_display() ): ?>
							<?php if ( etheme_get_option( 'top_toolbar', 1 ) ) {
								if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
									etheme_enqueue_style('filter-area', true ); ?>
                                    <div class="filter-wrap">
                                    <div class="filter-content">
								<?php }
								/**
								 * woocommerce_before_shop_loop hook
								 *
								 * @hooked woocommerce_result_count - 20
								 * @hooked woocommerce_catalog_ordering - 30
								 * @hooked etheme_grid_list_switcher - 35
								 */
								do_action( 'woocommerce_before_shop_loop' );
								if ( ! wc_get_loop_prop( 'is_shortcode' ) ) { ?>
                                    </div>
                                    </div>
								<?php }
							}
								etheme_shop_filters_sidebar();
						endif;   ?>

						<?php do_action('etheme_before_product_loop_start', wc_get_loop_prop( 'total' )); ?>
						
						<?php 
							$search_content = etheme_get_option( 'search_results_content_et-desktop',
								array(
									'products',
									'posts',
								)
							); 
						?>
						
						<?php if ( is_array($search_content) && is_search() && ! in_array('products', $search_content ) ): ?>
						
						<?php else: ?>
							<?php woocommerce_product_loop_start(); ?>
							
							<?php if ( wc_get_loop_prop( 'total' ) ) { ?>
								
								<?php while ( have_posts() ) : the_post(); ?>
									
									<?php do_action( 'woocommerce_shop_loop' ); ?>
									
									<?php wc_get_template_part( 'content', 'product' ); ?>
								
								<?php endwhile; // end of the loop. ?>
							
							<?php } ?>
							
							<?php woocommerce_product_loop_end(); ?>
						<?php endif; ?>
						
						<?php if ( is_array($search_content) && is_search() && ! in_array('products', $search_content ) ): ?>
						
						<?php else: ?>
                            <div class="after-shop-loop"><?php /*** woocommerce_after_shop_loop hook** @hooked woocommerce_pagination - 10*/ do_action( 'woocommerce_after_shop_loop' ); ?></div>
						<?php endif; ?>
						
						<?php do_action('etheme_after_product_loop_end'); ?>
						<?php etheme_second_cat_desc(); ?>
					
					<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
						<?php do_action( 'etheme_before_product_loop_start' ); ?>
						<?php do_action( 'woocommerce_no_products_found' ); ?>
						<?php do_action( 'etheme_after_product_loop_start' ); ?>
					
					
					<?php endif; ?>
					
					<?php
					/**
					 * woocommerce_after_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					etheme_after_products_widgets();
					if ( etheme_get_option( 'product_bage_banner_pos',1 ) == 2 ) {
						etheme_category_header();
						do_action( 'woocommerce_archive_description' );
					}
					do_action( 'woocommerce_after_main_content' );
					?>

                </div>
				
				<?php if ( woocommerce_products_will_display() || ( $cat_sidebar != 'without') ) { ?>
					
					<?php do_action( 'woocommerce_sidebar' ); ?>
				
				<?php } ?>
            </div>

        </div>
    </div>

<?php get_footer( 'shop' ); ?>