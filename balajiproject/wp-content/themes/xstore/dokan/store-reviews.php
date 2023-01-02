<?php
/**
 * The Template for displaying all reviews.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

$page_id = wc_get_page_id('shop');
$l = array();

$l['sidebar'] = etheme_get_option('grid_sidebar', 'left');
$l['breadcrumb'] = etheme_get_option('breadcrumb_type', 'left2');
$l['bc_color'] = etheme_get_option('breadcrumb_color', 'dark');
$l['bc_effect'] = etheme_get_option('breadcrumb_effect', 'mouse');
$l['sidebar-mobile'] = etheme_get_option( 'sidebar_for_mobile', 'off_canvas' );
$l['slider'] = false;

$page_breadcrumb = etheme_get_custom_field('breadcrumb_type', $page_id);
$breadcrumb_effect = etheme_get_custom_field('breadcrumb_effect', $page_id);
$page_sidebar = etheme_get_custom_field('sidebar_state', $page_id);
$sidebar_width = etheme_get_custom_field('sidebar_width', $page_id);
$widgetarea = etheme_get_custom_field('widget_area', $page_id);
$slider = etheme_get_custom_field('page_slider', $page_id);
$product_disable_sidebar = etheme_get_custom_field('disable_sidebar');
$l['sidebar-size'] = 3;

if(!empty($page_sidebar) && $page_sidebar != 'default') {
	$l['sidebar'] = $page_sidebar;
}

if(!empty($sidebar_width) && $sidebar_width != 'default') {
	$l['sidebar-size'] = $sidebar_width;
}

if(!empty($page_breadcrumb) && $page_breadcrumb != 'inherit') {
	$l['breadcrumb'] = $page_breadcrumb;
}

if(!empty($breadcrumb_effect) && $breadcrumb_effect != 'inherit') {
	$l['bc_effect'] = $breadcrumb_effect;
}

if(!empty($widgetarea) && $widgetarea != 'default') {
	$l['widgetarea'] = $widgetarea;
}

if(!empty($slider) && $slider != 'no_slider') {
	$l['slider'] = $slider;
}

// Thats all about custom options for the particular page

if(!$l['sidebar'] || $l['sidebar'] == 'without' || $l['sidebar'] == 'no_sidebar') {
	$l['sidebar-size'] = 0;
}

if($l['sidebar-size'] == 0) {
	$l['sidebar'] = 'without';
}


$l['content-size'] = 12 - $l['sidebar-size'];

$l['sidebar-class'] = 'col-md-' . $l['sidebar-size'];
$l['content-class'] = 'col-md-' . $l['content-size'];

if($l['sidebar'] == 'left') {
	$l['sidebar-class'] .= ' col-md-pull-' . $l['content-size'];
	$l['content-class'] .= ' col-md-push-' . $l['sidebar-size'];
}
$full_width = etheme_get_option('shop_full_width', 0);

if($full_width) {
	$content_span = 'col-md-12';
}

add_filter('etheme_page_config', function($layout) use ($l) {return $l; });

$store_user = get_userdata( get_query_var( 'author' ) );
$store_info = dokan_get_store_info( $store_user->ID );
$map_location = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';

$scheme = is_ssl() ? 'https' : 'http';
wp_enqueue_script( 'google-maps', $scheme . '://maps.google.com/maps/api/js?sensor=true' );

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

?>

<div class="<?php echo (!$full_width) ? 'container' : 'shop-full-width'; ?> sidebar-mobile-<?php echo esc_attr( $l['sidebar-mobile'] ); ?> content-page">
    <div class="sidebar-position-<?php echo esc_attr( $l['sidebar'] ); ?>">
        <div class="row">
            <div id="dokan-primary" class="content-area dokan-single-store <?php echo esc_attr( $l['content-class'] ); ?>">
                <div id="dokan-content" class="store-review-wrap woocommerce" role="main">
					
					<?php dokan_get_template_part( 'store-header' ); ?>
					
					
					<?php
					if ( class_exists('dokan_pro')) {
						$dokan_template_reviews = dokan_pro()->review;
						$dokan_id               = $store_user->ID;
						$dokan_post_type        = 'product';
						$dokan_limit            = 20;
						$dokan_status           = '1';
						$dokan_comments               = $dokan_template_reviews->comment_query( $dokan_id, $dokan_post_type, $dokan_limit, $dokan_status );
						?>

                        <div id="reviews">
                            <div id="comments">
								
								<?php do_action( 'dokan_review_tab_before_comments' ); ?>

                                <h2 class="headline"><?php _e( 'Vendor Review', 'xstore' ); ?></h2>

                                <ol class="commentlist">
									<?php echo $dokan_template_reviews->render_store_tab_comment_list( $dokan_comments, $store_user->ID ); ?>
                                </ol>

                            </div>
                        </div>
						
						<?php
					}
					echo $dokan_template_reviews->review_pagination( $dokan_id, $dokan_post_type, $dokan_limit, $dokan_status );
					?>

                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
			<?php if(!$l['sidebar'] || $l['sidebar'] == 'without' || $l['sidebar'] == 'no_sidebar')// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
			{}
            elseif ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) == 'off' ) {
				?>
                <div id="dokan-secondary" class="<?php echo esc_attr( $l['sidebar-class'] ); ?> sidebar dokan-clearfix dokan-store-sidebar sidebar-<?php echo esc_attr( $l['sidebar'] ); ?> <?php echo (etheme_get_option('shop_sidebar_hide_mobile', 0)) ? 'hidden-xs' : '' ; ?>" role="complementary">
                    <div class="dokan-widget-area widget-collapse">
						<?php
						if ( ! dynamic_sidebar( 'sidebar-store' ) ) {
							
							$args = array(
								'before_widget' => '<div class="sidebar-widget">',
								'after_widget'  => '</div>',
								'before_title'  => '<h3 class="widget-title">',
								'after_title'   => '</h3>',
							);
							
							if ( class_exists( 'Dokan_Store_Location' ) ) {
								the_widget( 'Dokan_Store_Category_Menu', array( 'title' => __( 'Store Category', 'xstore' ) ), $args );
								
								if ( dokan_get_option( 'store_map', 'dokan_appearance', 'on' ) == 'on' ) {
									the_widget( 'Dokan_Store_Location', array( 'title' => __( 'Store Location', 'xstore' ) ), $args );
								}
								
								if ( dokan_get_option( 'store_open_close', 'dokan_appearance', 'on' ) == 'on' ) {
									the_widget( 'Dokan_Store_Open_Close', array( 'title' => __( 'Store Time', 'xstore' ) ), $args );
								}
								
								if( dokan_get_option( 'contact_seller', 'dokan_appearance', 'on' ) == 'on' ) {
									the_widget( 'Dokan_Store_Contact_Form', array( 'title' => __( 'Contact Vendor', 'xstore' ) ), $args );
								}
							}
						}
						?>
						
						<?php do_action( 'dokan_sidebar_store_after', $store_user, $store_info ); ?>
                    </div>
                </div><!-- #secondary .widget-area -->
				<?php
			}else {
				?>
                <div class="<?php echo esc_attr( $l['sidebar-class'] ); ?>">
					<?php
					get_sidebar( 'store' );
					?>
                </div>
				<?php
			}?>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>
