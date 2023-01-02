<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;
use ETC\App\Controllers\Shortcodes\Banner;

/**
 * The Look shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class The_Look extends Shortcodes {

	function hooks() {}

	function the_look_shortcode( $atts, $content ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;

		global $woocommerce_loop;

		$atts = shortcode_atts(array(
			'post_type'  => 'product',
			'include'  => '',
			'custom_query'  => '',
			'taxonomies'  => '',
			'items_per_page'  => 10,
			'columns' => 3,
			'banner_double' => 0,
			'orderby'  => 'date',
			'order'  => 'DESC',
			'meta_key'  => '',
			'exclude'  => '',
			'class'  => '',
			'product_view' => '',
			'product_view_color' => '',
			'align'  => 'center',
			'valign'  => 'bottom',
			'link'  => '#',
			'img' => '',
			'img_size' => '360x790',
			'banner_pos' => 1,
			'type' => 3,
			'font_style' => 'dark',
			'css' => '',
			'is_preview' => false 
		), $atts);

		global $et_look_loop;
		if ( !isset($et_look_loop['item'])) {
			$et_look_loop['item'] = 0;
		}
		else {
			$et_look_loop['item']++;
		}
		
		$options = array();

		$options['id'] = rand(100,999);

		$options['paged'] = (get_query_var('page')) ? get_query_var('page') : 1;

		$options['wp_query_args'] = array(
			'post_type' => 'product',
			'status' => 'published',
			'paged' => $options['paged'],  
			'posts_per_page' => $atts['items_per_page']
		);

		if( $atts['post_type'] == 'ids' && $atts['include'] != '' ) {
			$options['wp_query_args']['post__in'] = explode(',', $atts['include']);
			$atts['orderby'] = 'post__in';
		}

		if( !empty( $atts['exclude'] ) ) 
			$options['wp_query_args']['post__not_in'] = explode(',', $atts['exclude']);

		if ( !empty( $atts['order'] ) ) 
			$options['wp_query_args']['order'] = $atts['order'];

		if ( !empty( $atts['meta_key'] ) ) 
			$options['wp_query_args']['meta_key'] = $atts['meta_key'];

		if ( !empty( $atts['orderby'] ) ) 
			$options['wp_query_args']['orderby'] = $atts['orderby'];


		if( ! empty( $atts['taxonomies'] ) ) {

			$options['taxonomies'] = get_taxonomies( array( 'public' => true ) );

			$options['terms'] = get_terms( array_keys( $options['taxonomies'] ), array(
				'orderby' => 'name',
				'include' => $atts['taxonomies']
			));

			if( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
				$options['wp_query_args']['tax_query'] = array('relation' => 'OR');
				foreach ($options['terms'] as $key => $term) {
					$options['wp_query_args']['tax_query'][] = array(
						'taxonomy' => $term->taxonomy,        // (string) - Taxonomy.
						'field' => 'slug',                    // (string) - Select taxonomy term by ('id' or 'slug')
						'terms' => array( $term->slug ),      // (int/string/array) - Taxonomy term(s).
						'include_children' => true,           // (bool) - Whether or not to include children for hierarchical taxonomies. Defaults to true.
						'operator' => 'IN'  
					);
				}
			}
		}

		$options['products'] = new \WP_Query( $options['wp_query_args'] );

		$options['wrapper_attr'] = array(
			'id="et-look-' . esc_attr( $options['id'] ) . '"',
			'class="et-look"'
		);

		$woocommerce_loop['columns'] = $atts['columns'];
		$woocommerce_loop['isotope'] = true;
		$woocommerce_loop['product_view'] = $atts['product_view'];
		$woocommerce_loop['product_view_color'] = $atts['product_view_color'];

		if( ! empty($atts['css']) && function_exists( 'vc_shortcode_custom_css_class' )) {
			$options['images_class'] = vc_shortcode_custom_css_class( $atts['css'] );
			$options['wrapper_attr'][] = 'data-class="'.$options['images_class'].'"';
			$options['images_style'] = explode('{', $atts['css']);
			$options['images_style'] = '[data-class="' . $options['images_class'] . '"] .product-content-image img {' . $options['images_style'][1];
			$atts['css'] = '<style>' . $options['images_style'] . '</style>';
		}

		if( $atts['banner_double'] ) 
			$atts['columns'] = $atts['columns'] / 2;

		$options['output'] = '';

		if ( $options['products']->have_posts() ) : 

			ob_start();
		
            if ( $et_look_loop['item'] == 0) {
                $options['wrapper_attr'] = str_replace('class="', 'class="active-look ', $options['wrapper_attr']);
            }
		
		    ?>

			<div <?php echo implode(' ', $options['wrapper_attr']); ?>>

				<?php 

				woocommerce_product_loop_start(); 				
				
				$local_options = array(
					'i' => 0
				);

				while ( $options['products']->have_posts() ) : 

					$options['products']->the_post(); 

					$local_options['i']++;

					if( $atts['banner_pos'] == $local_options['i'] ) {
						unset($atts['css']);
						$local_options['class'] = etheme_get_product_class( $atts['columns'] );
						
						?>
						<div class="<?php echo esc_attr($local_options['class']); ?> et-isotope-item et_banner-section" data-position="<?php echo esc_attr($atts['banner_pos']); ?>">
							<div class="content-product">
								<?php echo Banner::banner_shortcode( $atts, $content ); ?>
							</div>
						</div>

					<?php }

					wc_get_template_part( 'content', 'product' );
			
				endwhile; // end of the loop. ?>

				<?php 

				woocommerce_product_loop_end();

				if ( isset($atts['css']) )
					echo $atts['css'];
				
				if ( !in_array($woocommerce_loop['product_view'], array('disable', 'custom')) ) {
					if ( function_exists('etheme_enqueue_style')) {
						etheme_enqueue_style( 'product-view-' . $woocommerce_loop['product_view'], true );
					}
				}
				
				unset($woocommerce_loop['columns']); 
				unset($woocommerce_loop['isotope']); 
				unset($woocommerce_loop['size']); 
				unset($woocommerce_loop['product_view']); 
				unset($woocommerce_loop['product_view_color']); 
				?>

			</div> <?php // .et-look 

			$options['output'] = ob_get_clean();

//			wp_add_inline_script( 'et_isotope', "
//				jQuery(document).ready(function($){
//
//					banner_position();
//
//					function banner_position() {
//						var length = $( '#et-look-" . esc_attr( $options['id'] ) . " .et-isotope-item' ).length;
//						var position = $( '#et-look-" . esc_attr( $options['id'] ) . " .et-isotope-item.et_banner-section' ).data( 'position' );
//
//						if ( $(window).width() > 480 ) return;
//						if ( length < 1 || position <= 1 ) return;
//
//						length = length - 1;
//
//						if ( ! Number.isInteger( length ) ) return;
//
//						var i = 0;
//						var banner = '';
//
//						$.each( $( '#et-look-" . esc_attr( $options['id'] ) . ".et-isotope-item') , function( e, t ) {
//
//							if ( $(this).is( '.et_banner-section' ) ) {
//								banner = t;
//								$(this).remove();
//								$(this).removeClass( 'col-xs-6' ).addClass( 'col-xs-12 et_banner-moved' );
//							};
//
//							if ( i == length/2 ) {
//								$(this).after( banner );
//							}
//							i++;
//
//							setTimeout(function(){
//                                if ( etTheme.isotope !== undefined )
//                                    etTheme.isotope();
//                            }, 300);
//						});
//					}
//				});
//			", 'after' );
//
		endif;

		wp_reset_postdata();

		$output = $options['output'];

        if ( $atts['is_preview'] ) {
        	ob_start();
            	echo parent::initPreviewJs();
        	$output .= ob_get_clean();
        }

		unset($atts);
		unset($options);

		return $output;
	}
}
