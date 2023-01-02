<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;
$styles_default = array(
	'style' => etheme_get_option('cat_style', 'default'),
	'text_color' => etheme_get_option('cat_text_color', 'dark'),
	'valign' => etheme_get_option('cat_valign', 'center'),
	'content_position' => 'inside',
	'text-align' => 'center',
	'text-transform' => 'uppercase',
	'count_label' => false,
	'stretch_images' => false,
	'is_elementor' => false,
    'image_circle' => false,
    'content_type_new' => false
);

if ( !isset($styles) ) {
	$styles = $sorting = array();
}
if ( ! empty( $styles ) ) {
	$styles_default = wp_parse_args( $styles, $styles_default  );
}

// @todo beta testing for all with new type (better for images/hover effects)
$styles_default['content_type_new'] = true;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

if ( !empty($woocommerce_loop['content_position'])) {
	$styles_default['content_position'] = $woocommerce_loop['content_position'];
}

if ( empty( $woocommerce_loop['categories_columns'] ) )
	$woocommerce_loop['categories_columns'] = wc_get_loop_prop( 'columns' );

if ( !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) {
	$woocommerce_loop['columns']            = ( get_query_var( 'et_cat-cols' ) ) ? get_query_var( 'et_cat-cols' ) : $woocommerce_loop['columns'];
	$woocommerce_loop['categories_columns'] = ( get_query_var( 'et_cat-cols' ) ) ? get_query_var( 'et_cat-cols' ) : $woocommerce_loop['categories_columns'];
}

if ( get_query_var('view_mode_smart', false) && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) {
	if ( isset( $_GET['et_columns-count'] ) ) {
		$woocommerce_loop['columns'] = $woocommerce_loop['categories_columns'] = $_GET['et_columns-count'];
	}
	else {
		$view_mode_smart_active = get_query_var('view_mode_smart_active', 4);
		$woocommerce_loop['columns'] = $woocommerce_loop['categories_columns'] = $view_mode_smart_active != 'list' ? $view_mode_smart_active : 4;
	}
}

$mask_classes = '';

$classes = array();

$classes[] = 'category-grid';

if ( $styles_default['image_circle'] )
	$classes[] = 'category-image-circle';

if( !empty($woocommerce_loop['display_type']) && $woocommerce_loop['display_type'] == 'slider' ) {
	$classes[] = 'slide-item';
} else {
	$col_sm = 12 / $woocommerce_loop['categories_columns'];
	$classes[] = 'col-xs-12 col-sm-' . $col_sm . ' columns-' . $woocommerce_loop['categories_columns'];
}

if ( ! empty( $woocommerce_loop['isotope'] ) && $woocommerce_loop['isotope'] || etheme_get_option( 'products_masonry', 0 ) && get_query_var('et_is-woocommerce-archive', false) ) {
	$classes[] = 'et-isotope-item';
}

// if ( get_option( 'woocommerce_shop_page_display' ) == 'subcategories' && is_shop() && etheme_get_option( 'products_masonry' ) ) {
// 	if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
// 		$classes[] = 'grid-sizer';
// 	}
// }

if ( get_query_var('et_is-woocommerce-archive', false) && etheme_get_option( 'products_masonry',0 ) ) {
	$classes[] = 'grid-sizer';
}

if ( !$styles_default['is_elementor'] ) {
	$styles_default['content_position'] = $styles_default['style'] == 'classic' ? 'under' : $styles_default['content_position'];
}

$classes[] = 'text-color-' . $styles_default['text_color'];
$classes[] = 'valign-' . $styles_default['valign'];
$classes[] = 'style-' . $styles_default['style'];
if ( $styles_default['content_position'] ) {
	$classes[] = 'content-' . $styles_default['content_position'];
}
$mask_classes .= 'text-' . $styles_default['text-align'];
if ( $styles_default['text-transform'] != 'none') {
	$mask_classes .= ' text-' . $styles_default['text-transform'];
}
if ( isset($styles['sorting']) ) {
	$sorting = $styles['sorting'];
	if ( !is_array($sorting)) {
		$sorting = explode(',', $sorting);
	}
}
// Increase loop count
$woocommerce_loop['loop']++;

$category_bg_styles = '';

if ( $styles_default['stretch_images'] ) :
	
	$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
	$dimensions           = wc_get_image_size( $small_thumbnail_size );
	$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );
	
	if ( $thumbnail_id ) {
		$image_src        = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
		$image_src        = $image_src[0];
	} else {
		$image_src        = wc_placeholder_img_src();
	}
	
	if ( $image_src ) {
		// Prevent esc_url from breaking spaces in urls for image embeds.
		// Ref: https://core.trac.wordpress.org/ticket/23605.
		$image_src = str_replace( ' ', '%20', $image_src );
		$category_bg_styles = 'background-image: url('.$image_src.')';
		remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
        remove_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10);
		remove_action('woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10);
	}

endif;

$image_hover = (isset($woocommerce_loop['image_hover'])) ? $woocommerce_loop['image_hover'] : '';
if ( !empty($woocommerce_loop['image_hover']) && $woocommerce_loop['image_hover'] == 'random') {
	$image_hover_array = array(
		'zoom-in',
		'zoom-out',
		'rtl',
		'ltr',
		'border-in'
	);
	
	$image_hover_array = apply_filters('category_image_hover', $image_hover_array);
	
	$image_hover = $image_hover_array[ array_rand( $image_hover_array, 1 ) ];
}

$content_hover = (isset($woocommerce_loop['content_hover'])) ? $woocommerce_loop['content_hover'] : '';

?>
    <div <?php wc_product_cat_class( $classes, $category ); ?>
		<?php
		if ($image_hover != '') {
			echo 'data-hover="'.$image_hover.'"';
		}
		if ($content_hover != '') {
			echo 'data-content-hover="'.$content_hover.'"';
		}
		if ( ! empty($woocommerce_loop['add_overlay']) ) {
			echo 'data-overlay="true"';
		} ?>>
		<?php
		/**
		 * woocommerce_before_subcategory hook.
		 *
		 * @hooked woocommerce_template_loop_category_link_open - 10
		 */
		do_action( 'woocommerce_before_subcategory', $category );
		
		
		/**
		 * woocommerce_before_subcategory_title hook
		 *
		 * @hooked woocommerce_subcategory_thumbnail - 10
		 */
		do_action( 'woocommerce_before_subcategory_title', $category );
		
		ob_start();
		if ( $category_bg_styles != '' ) :
			woocommerce_template_loop_category_link_open( $category );
            echo '<div class="category-bg" style="' . $category_bg_styles . '"></div>';
			woocommerce_template_loop_category_link_close();
		endif; ?>

        <div class="categories-mask <?php echo esc_attr($mask_classes); ?>">
			<?php
			
			if ( (isset($styles['hide_all']) && !$styles['hide_all']) || count($styles) < 1 ) :
				if ( is_array($sorting) && (in_array('products', $sorting) && $sorting[0] == 'products') ) {
					if ( $category->count > 0 && !$styles_default['count_label'] ) {
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">' . sprintf( _n( '%s product', '%s products', $category->count, 'xstore' ), $category->count ). '</mark>', $category );
					}
				}
				?>
				<?php if ( empty($sorting[0]) || in_array('name', $sorting) ) {
				    if ( $styles_default['content_type_new'] ) woocommerce_template_loop_category_link_open( $category ); ?>
                        <h4><?php echo esc_html($category->name); ?><?php if ( $styles_default['count_label'] && $category->count > 0 ) { echo ' <sup>('.$category->count.')</sup>'; } ?></h4>
                    <?php if ( $styles_default['content_type_new'] ) woocommerce_template_loop_category_link_close(); ?>
			<?php } ?>
				<?php
				if ( is_array($sorting) && ( ( in_array('products', $sorting) && $sorting[1] == 'products') || empty($sorting[0]) ) ) {
					if ( $category->count > 0 && !$styles_default['count_label'] ) {
						if ( $styles_default['content_type_new'] ) woocommerce_template_loop_category_link_open( $category );
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">' . sprintf( _n( '%s product', '%s products', $category->count, 'xstore' ), $category->count ). '</mark>', $category );
						if ( $styles_default['content_type_new'] )
							woocommerce_template_loop_category_link_close();
					}
				}
			endif;
			if ( isset($woocommerce_loop['view_more']) && $woocommerce_loop['view_more'] ) {
				if ( $styles_default['content_type_new'] ) woocommerce_template_loop_category_link_open( $category );
				echo '<div class="read-more-wrapper"><span class="read-more">'.esc_html__('View more', 'xstore').'</span></div>';
				if ( $styles_default['content_type_new'] )
				    woocommerce_template_loop_category_link_close();
				
			}
			?>
			<?php
			/**
			 * woocommerce_after_subcategory_title hook
			 */
			
			do_action( 'woocommerce_after_subcategory_title', $category );
			?>
        </div>
		
		<?php
        $category_mask = ob_get_clean();
        
        echo false == $styles_default['content_type_new'] ? $category_mask : '';
		/**
		 * woocommerce_after_subcategory hook.
		 *
		 * @hooked woocommerce_template_loop_category_link_close - 10
		 */
		do_action( 'woocommerce_after_subcategory', $category );
		
		echo true == $styles_default['content_type_new'] ? $category_mask : '';
		
		?>
    
    
    </div>

<?php
if ( $category_bg_styles != '' ) :
	add_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
	add_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10);
	add_action('woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10);
endif;