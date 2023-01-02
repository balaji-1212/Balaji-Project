<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Brands shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Brands extends Shortcodes {

	public function hooks() {}

	function brands_shortcode($atts) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;

		if ( function_exists('etheme_get_option') && ! etheme_get_option( 'enable_brands', 1 ) )
			return '<div class="woocommerce-info">'.esc_html__('Enable brands in Theme options -> Shop elements -> Brands to use this element', 'xstore-core').'</div>';

		$atts = shortcode_atts( array(
			'number'     => 12,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => 0,
			'ids'        => '',
			'large' => 4,
			'notebook' => 3,
			'tablet_land' => 2,
			'tablet_portrait' => 2,
			'mobile' => 1,
			'slider_autoplay' => false,
			'slider_speed' => 300,
			'slider_interval' => 1000,
			'slider_loop' => false,
			'slider_stop_on_hover' => false,
			'pagination_type' => 'hide',
			'hide_fo' => '',
			'default_color' => '#e1e1e1',
			'active_color' => '#222',
			'hide_buttons' => false,
			'navigation_type'      => 'arrow',
			'navigation_position_style' => 'arrows-hover',
			'navigation_style'     => '',
			'navigation_position'  => 'middle',
			'hide_buttons_for'   => '',
			'per_move' => 1,
			'class'      => '',
			'show_only_name' => false,
			'is_elementor' => false,
			'is_preview' => false
		), $atts );
		
		// not -1 but 0 to show all
		$atts['number'] = $atts['number'] && $atts['number'] < 0 ? 0 : $atts['number'];

		$options = array();

		if ( $atts['orderby'] == 'ids_order' )
			$atts['orderby'] = 'include';

		$options['terms_args'] = array(
			'orderby'    => $atts['orderby'],
			'order'      => $atts['order'],
			'pad_counts' => true,
			'include'    => $atts['ids'],
			'number' => $atts['number'],
			'hide_empty' => $atts['hide_empty']
		);

		$options['product_brands'] = get_terms( 'brand', $options['terms_args'] );

		$options['box_id'] = rand(1000,10000);

		if ( $atts['orderby'] == 'name' )
			$options['product_brands'] = $this->force_name_sort( $options['product_brands'], $atts['order'] );

		$atts['class'] .= ' brands-carousel-'.$options['box_id'];

		if ( $atts['slider_stop_on_hover'] )
			$atts['class'] .= ' stop-on-hover';

		$atts['class'] .= ( $atts['pagination_type'] == 'lines' ) ? ' swiper-pagination-lines' : '';

		$options['selectors'] = array();

		$options['selectors']['slider'] = '.brands-carousel-' . $options['box_id'];
		$options['selectors']['pagination'] = $options['selectors']['slider'] . ' .swiper-pagination-bullet';
		$options['selectors']['pagination_hover'] = $options['selectors']['pagination'].':hover';
		$options['selectors']['pagination_hover'] .= ', ' . $options['selectors']['pagination'] . '-active';

		// create css data for selectors
		$options['css'] = array(
			$options['selectors']['pagination'] => array(),
			$options['selectors']['pagination_hover'] => array(),
		);

		// create output css
		$options['output_css'] = array();

		if ($atts['pagination_type'] != 'hide') {
			$options['css'][$options['selectors']['pagination']][] = 'background-color:'.$atts['default_color'];
			$options['css'][$options['selectors']['pagination_hover']][] = 'background-color:'.$atts['active_color'];

			$options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';
			$options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';
		}

		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'brands-carousel', true );
		}

		ob_start();

		if ( is_array( $options['product_brands'] ) && count( $options['product_brands'] ) > 0 ) {

			$slider_autoplay = (  $atts['slider_autoplay'] ) ? $atts['slider_interval'] : '';

			$options['wrapper_attr'] = array(
				'data-breakpoints="1"',
				'data-xs-slides="' . esc_js( $atts['mobile'] ) . '"',
				'data-sm-slides="' . esc_js( $atts['tablet_land'] ) . '"',
				'data-md-slides="' . esc_js( $atts['notebook'] ) . '"',
				'data-lt-slides="' . esc_js( $atts['large'] ) . '"',
				'data-slides-per-view="' . esc_js( $atts['large'] ) . '"',
				'data-space="30"',
				'data-autoplay="' . esc_attr( $slider_autoplay ) . '"',
				'data-slides-per-group="' . esc_attr( $atts['per_move'] ) . '"',
			);

			if ( $atts['slider_speed'] )
				$options['wrapper_attr'][] = 'data-speed="'.$atts['slider_speed'].'"';

			if ( $atts['slider_loop'] )
				$options['wrapper_attr'][] = 'data-loop="true"';

			$options['swiper_entry_class'] = array();

			$options['swiper_entry_class'][] = $atts['navigation_position'];
			$options['swiper_entry_class'][] = $atts['navigation_position_style'];

			?>

            <div class="swiper-entry brands-carousel <?php echo implode(' ', $options['swiper_entry_class']); ?>">

                <div class="swiper-container <?php echo esc_attr($atts['class']); ?>" <?php echo implode(' ', $options['wrapper_attr']); ?>>

                    <div class="swiper-wrapper">

						<?php
						foreach ( $options['product_brands'] as $brand ) :

							$local_options = array();
							$local_options['thumb_id']   = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );

							if ( $atts['hide_empty'] && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && $this->etheme_stock_taxonomy( $brand->term_id, 'brand' ) < 1 ) continue;

							?>

                            <div class="swiper-slide">

                            <div class="categories-mask">

							<?php if( $local_options['thumb_id'] && !$atts['show_only_name'] ) :

							?>

                            <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" title="<?php echo esc_attr( $brand->name ); ?>">

								<?php
								if ( function_exists('etheme_get_image') ){
									echo etheme_get_image( $local_options['thumb_id'], 'medium' );
								}
								else {
									$local_options['image']     = wp_get_attachment_image_src( $local_options['thumb_id'], 'medium' );
									?>
                                    <img class="swiper-lazy" data-src="<?php echo esc_url($local_options['image'][0]); ?>" title="<?php echo esc_attr( $brand->name ); ?>" alt="<?php echo esc_attr( get_post_meta($brand->term_id, '_wp_attachment_image_alt', '') ); ?>/>
									<?php
								}
								?>

                            </a>

						<?php else: ?>

                            <h3>
                                <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" title="<?php echo sprintf(__('View all products from %s', 'xstore-core'), $brand->name); ?>">
									<?php echo esc_html($brand->name); ?>
                                </a>
                            </h3>

						<?php endif; ?>

                            </div><?php // .categories-mask ?>

                            </div><?php // .swiper-slide ?>

							<?php

							unset($local_options);
						endforeach;

						?>

                    </div><?php // .swiper-wrapper ?>

					<?php if ($atts['pagination_type'] != 'hide') {
						$options['pagination_class'] = '';
						if ( $atts['hide_fo'] == 'desktop' )
							$options['pagination_class'] = ' dt-hide';
                        elseif ( $atts['hide_fo'] == 'mobile' )
							$options['pagination_class'] = ' mob-hide';
						?>
                        <div class="swiper-pagination<?php esc_attr($options['pagination_class']); ?>"></div>
					<?php } ?>

                </div>

				<?php
				if ( !$atts['hide_buttons'] || ( $atts['hide_buttons'] && $atts['hide_buttons_for'] != '' ) ) {
					$options['nav_class'] = 'swiper-nav';
					if ( $atts['is_elementor'] ) {
                        $options['nav_class'] .= ' et-swiper-elementor-nav';
					}
					if ( $atts['hide_buttons_for'] == 'desktop' )
						$options['nav_class'] .= ' dt-hide';
                    elseif ( $atts['hide_buttons_for'] == 'mobile' )
						$options['nav_class'] .= ' mob-hide';

					$options['nav_left_class']  = 'swiper-custom-left' . ' ' . $options['nav_class'];
					$options['nav_right_class'] = 'swiper-custom-right' . ' ' . $options['nav_class'];

					$options['nav_left_class'] .= ' type-' . $atts['navigation_type'] . ' ' . $atts['navigation_style'];
					$options['nav_right_class'] .= ' type-' . $atts['navigation_type'] . ' ' . $atts['navigation_style'];

					if ( $atts['navigation_position'] == 'bottom' )
						echo '<div class="swiper-navigation">'; ?>

                    <div class="swiper-button-prev <?php echo esc_attr($options['nav_left_class']); ?>"></div>
                    <div class="swiper-button-next <?php echo esc_attr($options['nav_right_class']); ?>"></div>

					<?php
					if ( $atts['navigation_position'] == 'bottom' )
						echo '</div>'; ?>
				<?php } ?>
            </div>

		<?php }

		else
			echo '<div class="woocommerce-info">'.esc_html__('No brands are available', 'xstore-core').'</div>';

		if ( $atts['is_preview'] ) {
			echo parent::initPreviewCss($options['output_css']);
			echo parent::initPreviewJs();
		}
		else {
			parent::initCss($options['output_css']);
		}

		unset($atts);
		unset($options);

		return ob_get_clean();
	}

	public function etheme_stock_taxonomy( $term_id = false, $taxonomy = 'product_cat', $category = false, $stock = true ) {
		if ( $term_id === false ) return false;
		$args = array(
			'post_type'         => 'product',
			'posts_per_page'    => -1,
			'tax_query'         => array(
				array(
					'taxonomy'  => $taxonomy,
					'field'     => 'term_id',
					'terms'     => $term_id
				),
			),
		);

		if ( $category ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'slug',
				'terms'            => $category,
				'include_children' => true,
				'operator'         => 'IN'
			);
		}

		$cat_prods = get_posts( $args );
		$i = 0;

		foreach ( $cat_prods as $single_prod ) {
			$product = wc_get_product( $single_prod->ID );

			if ( ! $stock ) {
				$i++;
			} elseif( $product->is_in_stock() === true ){
				$i++;
			}
		}

		return $i;
	}
	
	// **********************************************************************//
    // ! Force name sorting
    // **********************************************************************//
	public function force_name_sort( $array, $order ){
		
		if ( is_wp_error( $array ) || count( $array ) <= 0 ) return;
		
		// ! Set values
		$to_sort = array();
		$sorted = array();
		
		// ! Set names array
		foreach ( $array as $key => $value ) {
			$to_sort[] = strtolower( $value->name );
		}
		
		// ! Sort names array
		sort( $to_sort );
		
		// ! Change order if need it
		if ( $order == 'DESC' ){
			$to_sort = array_reverse( $to_sort );
		}
		
		// ! Set new sorted array
		foreach ( $to_sort as $key => $value ) {
			foreach ( $array as $k => $v ) {
				if ( $value == strtolower( $v->name ) ) {
					$sorted[] = $v;
				}
			}
		}
		return $sorted;
	}

}
