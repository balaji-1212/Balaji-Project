<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Categories lists shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Categories_lists extends Shortcodes {

	function hooks(){}

	function categories_lists_shortcode( $atts ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;

		global $woocommerce_loop;

		$atts = shortcode_atts( array(
			'number'     => null,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'title' => '',
			'hide_empty' => 1,
			'show_count' => true,
			'columns' => 3,
			'parent'     => 0,
			'display_type' => 'grid',
			'img_position' => '',
			'image_size' => 'woocommerce_thumbnail',
			'hover_type' => 'default',
			'no_space'     => 0,
			'ids'        => '',
			'slug'       => '',
			'exclude'    => '',
			'large' => 4,
			'notebook' => 3,
			'tablet_land' => 2,
			'tablet_portrait' => 2,
			'mobile' => 1,
			'slider_autoplay' => false,
			'slider_speed' => 300,
			'slider_loop' => false,
			'slider_interval' => 3000,
			'slider_stop_on_hover' => false,
			'slider_spacing' => 30,
			'pagination_type' => 'hide',
	        'nav_color' 		 => '', 
			'arrows_bg_color' 	 => '',
			'default_color' => '#e1e1e1',
			'active_color' => '#222',
			'hide_fo' => '',
			'hide_buttons' => false,
            'hide_buttons_for'   => '',
			'navigation_position' => 'middle',
			'navigation_position_style' => 'arrows-hover',
			'navigation_type' => 'arrow',
			'navigation_style' => 'style-1',
			'quantity' => '',
			'more_link' => true,
			'more_link_type' => 'link',
			'class'      => '',
			
			'image_hover' => '',
			'add_overlay' => false,
			'stretch_images' => false,
			
			'is_preview' => false,
            'is_elementor' => false
		), $atts );
		
		// backward compatibility to woocommerce <= 3.8.0 because in newest versions few sizes were removed
		if ( $atts['image_size'] ) {
			$atts['image_size'] =
				str_replace(
					array( 'shop_catalog' ),
					array( 'woocommerce_thumbnail' ),
					$atts['image_size']
				);
		}
		
		$options = array(
			'box_id' => rand(1000,10000),
			'wrapper_attr' => array(),
			'wrapper_class' => array(),
			'item_class' => array(
				'category-list-item-wrapper'
			),
		);
		
		$atts['is_preview'] = $atts['is_preview'] || defined( 'DOING_AJAX' ) && DOING_AJAX;
		
		$options['with_ids'] = isset( $atts[ 'ids' ] ) && ! empty( $atts[ 'ids' ] );

		if ( $options['with_ids'] || ( isset( $atts[ 'slug' ] ) && ! empty( $atts[ 'slug' ]  ) ) ) {

          	if ( $options['with_ids'] ) 
	            $atts['ids'] = array_map( 'trim', explode( ',', $atts[ 'ids' ] ) );
          	else {
	            $atts['ids'] = array_map( 'trim', explode( ',', $atts[ 'slug' ] ) );
	            $options['new_ids'] = array();
	            foreach ($atts['ids'] as $key => $value) {
	                $term = get_term_by('slug', $value, 'product_cat');
	                if (isset($term->term_id)) {
	                  $options['new_ids'][] = $term->term_id;
	                }
	            }
	            $atts['ids'] = $options['new_ids'];
          	}
	        $atts['parent'] = '';
      	} else 
          	$atts['ids'] = false;

		$atts['hide_empty'] = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;
		$atts['slider_spacing'] = $atts['no_space'] ? 0 : $atts['slider_spacing'];

		$options['is_slider'] = ( $atts['display_type'] == 'slider' ) ? true : false;

		// get terms and workaround WP bug with parents/pad counts
		$options['terms_args'] = array(
			'orderby'    => $atts['orderby'],
			'order'      => $atts['order'],
			'hide_empty' => $atts['hide_empty'],
			'include'    => $atts['ids'],
			'exclude'    => $atts['exclude'],
			'pad_counts' => true,
			'parent' => $atts['parent']
		);

		$options['product_categories'] = get_terms( 'product_cat', $options['terms_args'] );

		if ( $atts['hide_empty'] && ! is_wp_error( $options['product_categories'] ) ) {
			foreach ( $options['product_categories'] as $key => $category ) {
				if ( $category->count == 0 )
					unset( $options['product_categories'][ $key ] );
			}
		}

		if ( $atts['number'] ) 
			$options['product_categories'] = array_slice( $options['product_categories'], 0, $atts['number'] );
		
		if ( !$atts['is_elementor']) :

            $options['selectors']['slider'] = '.slider-'.$options['box_id'];
    
            $options['selectors']['navigation'] = $options['selectors']['slider'] . ' ~ .swiper-button-prev,' . $options['selectors']['slider'] . ' ~ .swiper-button-next';
    
            $options['selectors']['pagination'] = $options['selectors']['slider'] . ' .swiper-pagination-bullet';
            $options['selectors']['pagination_hover'] = $options['selectors']['pagination'].':hover';
            $options['selectors']['pagination_hover'] .= ', ' . $options['selectors']['pagination'] . '-active';
    
            // create css data for selectors
            $options['css'] = array(
                $options['selectors']['slider'] => array(),
                'navigation' => array(),
                $options['selectors']['pagination'] => array(),
                $options['selectors']['pagination_hover'] => array(),
            );
    
            if ($atts['pagination_type'] != 'hide' && $options['is_slider']) {
                $options['css'][$options['selectors']['pagination']][] = 'background-color:'.$atts['default_color'];
                $options['css'][$options['selectors']['pagination_hover']][] = 'background-color:'.$atts['active_color'];
            }
    
            // create output css
            $options['output_css'] = array();
    
            if ( !empty($atts['arrows_bg_color']) )
                $options['css']['navigation'][] = 'background-color: ' .$atts['arrows_bg_color'];
    
            if ( !empty($atts['nav_color']) )
                $options['css']['navigation'][] = 'color: ' .$atts['nav_color'];
    
            if ( count($options['css']['navigation']) )
                $options['output_css'][] = $options['selectors']['navigation'] . '{' . implode(';', $options['css']['navigation']) . '}';
    
            if ( count( $options['css'][$options['selectors']['pagination']] ) )
                $options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';
    
            if ( count( $options['css'][$options['selectors']['pagination_hover']] ) )
                $options['output_css'][] = $options['selectors']['pagination_hover'] . '{'.implode(';', $options['css'][$options['selectors']['pagination_hover']]).'}';
        
        endif;
        
		ob_start(); 

		if ( $options['product_categories'] ) {
		    
		    if ( function_exists('etheme_enqueue_style') ) {
			    etheme_enqueue_style( 'woocommerce', true );
			    etheme_enqueue_style( 'woocommerce-archive', true );
			    etheme_enqueue_style( 'categories-list-grid', true );
		    }
			
			$atts['class'] .= ' slider-' . $options['box_id'];

//			$atts['class'] .= ' category-hover-'.$atts['hover_type'];

			$atts['class'] .= ( $atts['pagination_type'] == 'lines' ) ? ' swiper-pagination-lines' : '';
			$atts['class'] .= ( $atts['no_space'] ) ? ' no-space' : '';

			if ( $options['is_slider'] ) {
				$atts['class'] .= ' categories-lists-slider';
				if ( !$atts['is_elementor'] )
					$atts['class'] .= ' carousel-area';
				$options['item_class'][] = 'swiper-slide'; 
				if ( $atts['slider_stop_on_hover'] ) 
					$atts['class'] .= ' stop-on-hover';
			}
			else {
				$atts['class'] .= ' categories-lists-grid categories-columns-' . $atts['columns'];
			}

			if ( ! empty( $atts['quantity'] ) ) 
				$atts['class'] .= ' limit-enable';

			if ( $atts['slider_autoplay'] ) 
				$atts['slider_autoplay'] = $atts['slider_interval'];

			$options['wrapper_attr'] = array_merge( $options['wrapper_attr'], array(
				'data-breakpoints="1"',
				'data-xs-slides="' . esc_js( $atts['mobile'] ) . '"',
				'data-sm-slides="' . esc_js( $atts['tablet_land']) . '"',
				'data-md-slides="' . esc_js( $atts['notebook'] ) . '"',
				'data-lt-slides="' . esc_js( $atts['large'] ) . '"',
				'data-slides-per-view="' . esc_js( $atts['large'] ) . '"',
				'data-autoplay="' . esc_attr( $atts['slider_autoplay'] ) . '"',
				'data-speed="' . esc_attr( $atts['slider_speed'] ) . '"',
				'data-space="'. esc_attr( $atts['slider_spacing']) . '"'
			) );

			if ( $atts['slider_loop'] ) 
				$options['wrapper_attr'][] = 'data-loop="true"';
			
			if ( $atts['is_elementor']) {
				$options['wrapper_class'][] = $atts['navigation_position_style'];
				if ( 'middle-inside' === $atts['navigation_position'] )
					$options['wrapper_class'][] = 'middle-inside';
			}

			if ( $atts['img_position'] )
				$options['item_class'][] = 'image-' . (( $atts['img_position'] == 'background' ) ? 'top' : $atts['img_position']);
			
			add_filter( 'subcategory_archive_thumbnail_size', function($size) use ($atts){ return $atts['image_size']; } );

			if ( $atts['is_elementor'] && function_exists('etheme_enqueue_style') )
				etheme_enqueue_style('elementor-categories', true);
			?>

			<div class="clearfix <?php echo esc_attr($atts['class']); ?>">
                
                <?php if ( $atts['title'] != '' ) { ?>

                    <h3 class="title"><span><?php echo esc_html( $atts['title'] ); ?></span></h3>

                <?php } ?>

			<?php if ( $options['is_slider'] ) : ?>

				<div class="swiper-entry <?php echo implode(' ', $options['wrapper_class']); ?>">

					<div class="swiper-container <?php echo esc_attr( $atts['class'] ); ?>" <?php echo implode(' ', $options['wrapper_attr']); ?>>

					<?php endif; ?>

						<div class="categories-grid <?php echo ( $options['is_slider'] ) ? 'swiper-wrapper' : ''; echo ( !$options['is_slider'] && !$atts['no_space']) ? ' row' : '';?>">

							<?php foreach ( $options['product_categories'] as $category ) :
								
								$options['image_hover'] = $atts['image_hover'];
								
								if ( !empty($atts['image_hover']) && $atts['image_hover'] == 'random') {
									$options['image_hover_array'] = array(
										'zoom-in',
										'zoom-out',
										'rtl',
										'ltr',
										'border-in'
									);
									
									$options['image_hover_array'] = apply_filters('category_list_image_hover', $options['image_hover_array']);
									
									$options['image_hover'] = $options['image_hover_array'][ array_rand( $options['image_hover_array'], 1 ) ];
								}
								
								?>

								<div class="<?php echo implode(' ', $options['item_class']); ?>">

									<div class="category-list-item category-grid style-<?php echo $atts['hover_type']; ?>"
										<?php if ($options['image_hover'] != '') { echo 'data-hover="'.$options['image_hover'].'"'; } ?>
										<?php if ( $atts['add_overlay']) { echo 'data-overlay="true"'; } ?>>

                                        <?php if ( $atts['img_position'] != 'without') : ?>
                                            <a href="<?php echo get_term_link( $category, 'product_cat' ); ?>" class="category-image">
                                                <?php woocommerce_subcategory_thumbnail( $category ); ?>
                                            </a>
                                        <?php endif; ?>
										<ul>
											<?php self::show_category_in_the_list( $category, $atts['orderby'], $atts['order'], $atts['exclude'], $atts['hide_empty'], $atts['quantity'], $atts['show_count'], $atts['more_link'], $atts['more_link_type'] ) ?>
										</ul>

									</div>

								</div>

							<?php endforeach; ?>
						</div>

					<?php if ( $atts['pagination_type'] != 'hide' ) { 
							$options['pagination_class'] = '';
							if ( $atts['hide_fo'] == 'mobile' )
								$options['pagination_class'] = ' mob-hide';
							elseif ( $atts['hide_fo'] == 'desktop' )
								$options['pagination_class'] = ' dt-hide';
						?>
						<div class="swiper-pagination<?php esc_html_e( $options['pagination_class'] ); ?>"></div>
					<?php } ?>

				<?php if ( $options['is_slider'] ) : ?>

					</div><?php // .swiper-container ?>

	                <?php 
                    if ( !$atts['hide_buttons'] || ( $atts['hide_buttons'] && $atts['hide_buttons_for'] != '' ) ) {
	                    $options['nav_class'] = '';
	                    if ( $atts['is_elementor'] ) {
		                    $options['nav_class'] .= ' et-swiper-elementor-nav';
	                    }
	                    if ( $atts['hide_buttons_for'] == 'desktop' ) {
		                    $options['nav_class'] = ' dt-hide';
	                    } elseif ( $atts['hide_buttons_for'] == 'mobile' ) {
		                    $options['nav_class'] = ' mob-hide';
	                    }
	
	                    $options['nav_class_left']  = 'swiper-custom-left' . ' ' . $options['nav_class'];
	                    $options['nav_class_right'] = 'swiper-custom-right' . ' ' . $options['nav_class'];
	
	                    $options['nav_class_left'] .= ' type-' . $atts['navigation_type'] . ' ' . $atts['navigation_style'];
	                    $options['nav_class_right'] .= ' type-' . $atts['navigation_type'] . ' ' . $atts['navigation_style'];
                        ?>
                        <div class="swiper-button-prev swiper-nav <?php echo esc_attr( $options['nav_class_left'] ); ?>"></div>
                        <div class="swiper-button-next swiper-nav <?php echo esc_attr( $options['nav_class_right'] ); ?>"></div>
                <?php } ?>

				</div><?php // .swiper-entry ?>

				<?php endif; ?>
			</div>

		<?php }
		
		add_filter( 'subcategory_archive_thumbnail_size', function() { return 'woocommerce_thumbnail'; } );

		if ( $atts['is_preview'] ) {
		    if ( !$atts['is_elementor'] )
			    echo parent::initPreviewCss($options['output_css']);
			echo parent::initPreviewJs();
		}
		elseif ( !$atts['is_elementor'])
			parent::initCss($options['output_css']);

		unset($atts);
	    unset($options);

		return ob_get_clean();
	}

	public static function show_category_in_the_list( $category, $orderby, $order, $exclude, $hide_empty, $quantity, $show_count, $more_link, $more_link_type ) {
		?>
		<li>
			<a href="<?php echo get_term_link( $category, 'product_cat' ); ?>" class="category-name">
				<?php echo esc_html($category->name); 
				if ( $show_count && $category->count > 0 )
					echo ' <mark class="count">' . $category->count . '</mark>';
				?>
			</a>

			<?php 
				$subcategories =  get_terms( 'product_cat', array(
					'orderby'    => $orderby,
					'order'      => $order,
					'exclude'    => $exclude,
					'hide_empty' => $hide_empty,
					'pad_counts' => true,
					'parent' => $category->term_id
				) );
				$i=0;
			?>

			<?php if( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {
				echo '<ul>';
				foreach ($subcategories as $category) {
					$i++;
					if ( $i > $quantity && ! empty( $quantity ) ) {
					    if ( $more_link ) {
						    echo '<a href="' . get_category_link( $category->parent ) . '" class="limit-link' .
						         (($more_link_type == 'button') ? ' button btn-black' : '') . '">'.
						         (($more_link_type != 'button') ? '<span class="read-more">' : '') .
						         esc_html__( 'View All', 'xstore-core' ) .
						         (($more_link_type != 'button') ? '</span>' : '') .
						         '</a>';
					    }
						return;
					} 

					self::show_category_in_the_list( $category, $orderby, $order, $exclude, $hide_empty, $quantity, $show_count, $more_link, $more_link_type );
				}
				echo '</ul>';

			} ?>
		</li>
		<?php
	}
}
