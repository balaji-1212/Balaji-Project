<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $wp_query;

$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$delimeter = '<span class="delimeter"><i class="et-icon et-'.(get_query_var('et_is-rtl', false) ? 'left' : 'right').'-arrow"></i></span>';
$title_tag = get_theme_mod('breadcrumb_title_tag', 'h1');
$title_tag = !$title_tag ? 'h1' : $title_tag;
$product_name_single = !get_option( 'etheme_single_product_builder', false ) && get_theme_mod('product_name_signle', 0);
$product_name_single = apply_filters('product_name_single', $product_name_single);
$breadcrumb_stretch = 'default';
$breadcrumb_stretch = apply_filters('woocommerce_breadcrumb_stretch', $breadcrumb_stretch);
$return_to_previous = get_theme_mod('return_to_previous', 1);
$return_to_previous = apply_filters('return_to_previous', $return_to_previous);
$breadcrumb_params = apply_filters('breadcrumb_params', array(
	'type' => get_query_var('et_breadcrumbs-type', 'left2'),
	'effect' => get_query_var('et_breadcrumbs-effect', 'mouse'),
	'color' => get_query_var('et_breadcrumbs-color', 'dark')
));

$class = array();
$class[] = 'bc-type-'.$breadcrumb_params['type'];
$class[] = 'bc-effect-'.$breadcrumb_params['effect'];
$class[] = 'bc-color-'.$breadcrumb_params['color'];

$bread_bg = '';
$is_category = is_category() || is_tax('product_cat');
$is_tag = is_tag() || is_tax('product_tag');
if( $is_category || $is_tag) {
	$title_tag = get_theme_mod('breadcrumb_category_title_tag', 'h1');
	$title_tag = !$title_tag ? 'h1' : $title_tag;
}
if( $is_category || $is_tag ) {
	$term_id = get_queried_object()->term_id;
	if( $term_id && $image = get_term_meta( $term_id, '_et_page_heading', true ) ) {
		$bread_bg = $image;
	}
}

$is_taxonomy = is_product_taxonomy();

if (get_query_var('et_breadcrumbs', false) ): ?>
    <div
            class="page-heading <?php echo implode(' ', $class); ?>"
		<?php if($bread_bg): ?>
			<?php echo 'style="background-image:url('. $bread_bg .'); margin-bottom: 25px;"'; ?>
		<?php endif; ?>
    >
        <div class="<?php if ( $breadcrumb_stretch != 'full_width_content' ) echo 'container '; ?>">
            <div class="row">
                <div class="col-md-12 a-center">
					
					<?php do_action('etheme_before_breadcrumbs'); ?>
					
					<?php if ( $breadcrumb ) : ?>
						
						<?php echo wp_specialchars_decode($wrap_before); ?>
						
						<?php foreach ( $breadcrumb as $key => $crumb ) : ?>
							
							<?php echo wp_specialchars_decode($before); ?>
							
							<?php if ( apply_filters('etheme_breadcrumbs_shop_step', true) && $key == 1 && sizeof( $breadcrumb ) >= $key + 1 && $is_taxonomy ) {
								$term = get_queried_object_id();
								$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
								
								if ( ! $_name ) {
									$product_post_type = get_post_type_object( 'product' );
									$_name             = $product_post_type->labels->name;
								}
								if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
                                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><?php esc_html_e('Shop', 'xstore') ?></a>
									<?php echo apply_filters('woocommerce_breadcrumb_delimiter', $delimeter); ?>
								<?php endif;
							} ?>
							
							<?php if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) : ?>
								<?php echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>'; ?>
							<?php elseif ( ($breadcrumb_params['type'] == 'default' && !is_shop()) || ( (is_product_category() || is_shop()) && $current > 1) ) : ?>
								<?php echo '<span class="span-title">'.esc_html( $crumb[0] ).'</span>'; ?>
							<?php endif; ?>
							
							<?php echo wp_specialchars_decode($after); ?>
							
							<?php if ( sizeof( $breadcrumb ) !== $key + 1 ) : ?>
								<?php echo apply_filters('woocommerce_breadcrumb_delimiter', $delimeter); ?>
							<?php endif; ?>
						
						<?php endforeach; ?>
						
						<?php if( $product_name_single && is_single() && ! is_attachment() ):
							echo '<'.$title_tag.' class="title">' . get_the_title() . '</'.$title_tag.'>';
                        elseif( ! is_single()):
							echo '<'.$title_tag.' class="title">';
							if ( !empty($crumb[0])) {
								echo esc_html($crumb[0]);
							}
							else {
								woocommerce_page_title();
							}
							echo '</'.$title_tag.'>';
						endif; ?>
						
						<?php echo wp_specialchars_decode($wrap_after); ?>
					
					<?php endif; ?>
					
					<?php if( $return_to_previous ) etheme_back_to_page(); ?>
                </div>
            </div>
        </div>
    </div>
	
	<?php if ( $breadcrumb_stretch != 'default' ) { ?>
        <div class="vc_row-full-width vc_clearfix"></div>
	<?php }
endif; ?>
<?php if(get_query_var('et_page-slider', false)): ?>
    <div class="page-heading-slider">
		<?php echo do_shortcode('[rev_slider alias="'.get_query_var('et_page-slider', false).'"][/rev_slider]'); ?>
    </div>
<?php endif; ?>