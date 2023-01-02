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
class Categories extends Shortcodes {
	
	function hooks() {
	}
	
	public static function categories_shortcode( $atts ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;
		
		global $woocommerce_loop;
		
		$atts = shortcode_atts( array(
			'number'       => null,
			'title'        => '',
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => 1,
			'columns'      => 3,
			'parent'       => '',
			'display_type' => 'grid',
			'valign'       => 'center',
			'no_space'     => 0,
			'text_color'   => 'white',
			'style'        => 'default',
			
			'text_align'     => 'center',
			'text_transform' => 'uppercase',
			'count_label'    => '',
			'sorting'        => '',
			'hide_all'       => '',
			
			'bg_color'             => '',
			'title_color'          => '',
			'subtitle_color'       => '',
			'title_size'           => '',
			'subtitle_size'        => '',
			'ids'                  => '',
			'large'                => 4,
			'notebook'             => 3,
			'tablet_land'          => 2,
			'tablet_portrait'      => 2,
			'mobile'               => 1,
			'slider_autoplay'      => false,
			'slider_speed'         => 300,
			'slider_loop'          => false,
			'slider_interval'      => 3000,
			'slider_stop_on_hover' => false,
			'slider_spacing' => 30,
			'pagination_type'      => 'hide',
			'default_color'        => '#e1e1e1',
			'active_color'         => '#222',
			'hide_fo'              => '',
			'hide_buttons'         => false,
			'hide_buttons_for'     => '',
			'navigation_position' => 'middle',
			'navigation_position_style' => 'arrows-hover',
			'navigation_type' => 'arrow',
			'navigation_style' => 'style-1',
			'class'                => '',
			'ajax' => false,
			'content_position' => 'inside',
			'image_size' => 'woocommerce_thumbnail',
			'image_hover' => '',
			'add_overlay' => false,
			'content_hover' => '',
			'view_more' => false,
			'content_type_new' => false,
			'image_circle' => false,
			'stretch_images' => false,
			'exclude'          => '',
			
			// extra settings
			'is_preview'       => false,
            'is_elementor' => false
		), $atts );
		
		// backward compatibility to woocommerce <= 3.8.0 because in newest versions few sizes were removed
		if ( $atts['image_size'] ) {
			$atts['image_size'] =
				str_replace(
					array( 'shop_thumbnail', 'shop_catalog', 'shop_single' ),
					array( 'woocommerce_gallery_thumbnail', 'woocommerce_thumbnail', 'woocommerce_single' ),
					$atts['image_size']
				);
		}
		
		$options = array(
			'output'       => '',
			'wrapper_attr' => array(),
			'wrapper_class' => array(),
			'cat_ids'      => $atts['ids']
		);
		
        $atts['is_preview'] = $atts['is_preview'] || defined( 'DOING_AJAX' ) && DOING_AJAX;
        
        if ( $atts['image_circle'] ) {
//	        $atts['content_position'] = 'under';
//	        $atts['content_hover'] = 'none';
	        $atts['stretch_images'] = false;
        }

		if ($atts['ajax'] && ! $atts['is_preview']){
		    if ($atts['is_elementor']){ // because elementor - array, wpb - string
			    $atts['sorting'] = implode(',', $atts['sorting']);
			    if ( is_array($atts['exclude']))
				    $atts['exclude'] = implode(',', $atts['exclude']);
		    }
		    $is_slider = ($atts['display_type']) ? 'slider' : '';
			return et_ajax_element_holder( 'etheme_categories', $atts, $is_slider );
		} else {
		    if ($atts['is_elementor'] ) {
		        if ( ! is_array( $atts['sorting'] ) ) { // because elementor - array, vpb - string
			        $atts['sorting'] = explode( ',', $atts['sorting'] );
		        }
			    if ( !is_array($atts['exclude']))
				    $atts['exclude'] = explode(',', $atts['exclude']);
		    }
        }

		$options['p_exploded'] = explode( ",", $atts['parent'] );
		
		if ( count( $options['p_exploded'] ) > 1 ) {
			$atts['ids'] = array_map( 'trim', $options['p_exploded'] );
		} else {
			$atts['ids'] = array();
		}
		
		$atts['slider_spacing'] = $atts['no_space'] ? 0 : $atts['slider_spacing'];
		
		$atts['hide_empty'] = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;
		
		$options['is_slider'] = $atts['display_type'] == 'slider';
		
		if ( !$atts['is_elementor'] ) {
			$atts['content_position'] = $atts['style'] == 'classic' ? 'under' : $atts['content_position'];
		}
		
		// get terms and workaround WP bug with parents/pad counts
		if ( $atts['ids'] ) {
			
			$options['args'] = $options['result'] = $options['p_cats'] = array();
			
			$options['_i'] = 0;
			
			foreach ( $atts['ids'] as $key => $value ) {
				
				$options['args'][ $options['_i'] ] = array(
					'orderby'    => $atts['orderby'],
					'order'      => $atts['order'],
					'hide_empty' => $atts['hide_empty'],
					'exclude'    => $atts['exclude'],
					'pad_counts' => true,
					'child_of'   => $value
				);
				
				$options['p_cats'][ $options['_i'] ] = get_terms( 'product_cat', $options['args'][ $options['_i'] ] );
				
				if ( $atts['parent'] !== '' ) {
					$options['p_cats'][ $options['_i'] ] = wp_list_filter( $options['p_cats'][ $options['_i'] ], array( 'parent' => $value ) );
				}
				
				if ( $atts['hide_empty'] ) {
					foreach ( $options['p_cats'][ $options['_i'] ] as $key => $category ) {
						if ( $category->count == 0 ) {
							unset( $options['p_cats'][ $options['_i'] ][ $key ] );
						}
					}
				}
				
				$options['result'][] = $options['p_cats'][ $options['_i'] ];
				
				$options['_i'] ++;
			}
			
			if ( $atts['number'] ) {
				$options['p_cats'] = array_slice( $options['result'], 0, $atts['number'] );
			}
		} else {
			
			$options['cat_ids'] = array_filter( array_map( 'trim', explode( ',', $options['cat_ids'] ) ) );
			
			if ( $options['cat_ids'] ) {
				
				array_push( $options['cat_ids'], $atts['parent'] );
				
				$options['args'] = array(
					'orderby'    => $atts['orderby'],
					'order'      => $atts['order'],
					'hide_empty' => $atts['hide_empty'],
					'include'    => $options['cat_ids'],
					'exclude'    => $atts['exclude'],
					'pad_counts' => true
				);
				
			} else {
				
				$options['args'] = array(
					'orderby'    => $atts['orderby'],
					'order'      => $atts['order'],
					'hide_empty' => $atts['hide_empty'],
					'include'    => $atts['ids'],
					'exclude'    => $atts['exclude'],
					'pad_counts' => true,
					'child_of'   => $atts['parent']
				);
				
			}
			
			$options['p_cats'] = get_terms( 'product_cat', $options['args'] );
			
			if ( $atts['parent'] !== '' && ! ( $options['cat_ids'] ) ) {
				$options['p_cats'] = wp_list_filter( $options['p_cats'], array( 'parent' => $atts['parent'] ) );
			}
			
			if ( $atts['hide_empty'] ) {
				foreach ( $options['p_cats'] as $key => $category ) {
					if ( $category->count == 0 ) {
						unset( $options['p_cats'][ $key ] );
					}
				}
			}
			
			if ( $atts['number'] ) {
				$options['p_cats'] = array_slice( $options['p_cats'], 0, $atts['number'] );
			}
		}
		
		$options['box_id'] = rand( 1000, 10000 );
		
		if ( $options['is_slider'] && $atts['slider_stop_on_hover'] ) {
			$atts['class'] .= ' stop-on-hover';
		}
		
		if ( !$atts['is_elementor'] ) :
			
			// selectors
			$options['selectors'] = array();
			
			$options['selectors']['slider']           = '.slider-' . $options['box_id'];
			$options['selectors']['pagination']       = $options['selectors']['slider'] . ' .swiper-pagination-bullet';
			$options['selectors']['pagination_hover'] = $options['selectors']['pagination'] . ':hover';
			$options['selectors']['pagination_hover'] .= ', ' . $options['selectors']['pagination'] . '-active';
			
			$options['selectors']['mask'] = $options['selectors']['slider'] . ' .category-grid .categories-mask';
			
			$options['selectors']['title'] = $options['selectors']['mask'] . ' h4';
			$options['selectors']['count'] = $options['selectors']['mask'] . ' .count';
			$options['selectors']['count'] .= ', ' . $options['selectors']['mask'] . ' h4 sup';
			
			// create css data for selectors
			$options['css'] = array(
				$options['selectors']['slider']           => array(),
				$options['selectors']['pagination']       => array(),
				$options['selectors']['pagination_hover'] => array(),
				$options['selectors']['mask']             => array(),
				$options['selectors']['title']            => array(),
				$options['selectors']['count']            => array()
			);
			
			if ( $atts['pagination_type'] != 'hide' && $options['is_slider'] ) {
				$options['css'][ $options['selectors']['pagination'] ][]       = 'background-color:' . $atts['default_color'];
				$options['css'][ $options['selectors']['pagination_hover'] ][] = 'background-color:' . $atts['active_color'];
			}
			
			// title styles
			if ( $atts['title_color'] != '' ) {
				$options['css'][ $options['selectors']['title'] ][] = 'color:' . $atts['title_color'];
			}
			
			if ( $atts['title_size'] != '' ) {
				$options['css'][ $options['selectors']['title'] ][] = 'font-size:' . $atts['title_size'];
			}
			
			// count styles
			if ( $atts['subtitle_color'] != '' ) {
				$options['css'][ $options['selectors']['count'] ][] = 'color:' . $atts['subtitle_color'];
			}
			
			if ( $atts['subtitle_size'] != '' ) {
				$options['css'][ $options['selectors']['count'] ][] = 'font-size:' . $atts['subtitle_size'];
			}
			
			if ( $atts['bg_color'] != '' ) {
				$options['css'][ $options['selectors']['mask'] ][] = 'background-color:' . $atts['bg_color'];
			}
			
			// create output css
			$options['output_css'] = array();
			
			if ( count( $options['css'][ $options['selectors']['pagination'] ] ) ) {
				$options['output_css'][] = $options['selectors']['pagination'] . '{' . implode( ';', $options['css'][ $options['selectors']['pagination'] ] ) . '}';
			}
			
			if ( count( $options['css'][ $options['selectors']['pagination_hover'] ] ) ) {
				$options['output_css'][] = $options['selectors']['pagination_hover'] . '{' . implode( ';', $options['css'][ $options['selectors']['pagination_hover'] ] ) . '}';
			}
			
			if ( count( $options['css'][ $options['selectors']['title'] ] ) ) {
				$options['output_css'][] = $options['selectors']['title'] . '{' . implode( ';', $options['css'][ $options['selectors']['title'] ] ) . '}';
			}
			
			if ( count( $options['css'][ $options['selectors']['count'] ] ) ) {
				$options['output_css'][] = $options['selectors']['count'] . '{' . implode( ';', $options['css'][ $options['selectors']['count'] ] ) . '}';
			}
			
			if ( count( $options['css'][ $options['selectors']['mask'] ] ) ) {
				$options['output_css'][] = $options['selectors']['mask'] . '{' . implode( ';', $options['css'][ $options['selectors']['mask'] ] ) . '}';
			}
		
		endif;
		
		// Reset loop/columns globals when starting a new loop
		$woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';
		
		$woocommerce_loop['display_type'] = $atts['display_type'];
		
		$woocommerce_loop['content_position'] = $atts['content_position'];
		
		$woocommerce_loop['image_hover'] = $atts['image_hover'];
		$woocommerce_loop['add_overlay'] = $atts['add_overlay'];
		$woocommerce_loop['content_hover'] = $atts['content_hover'];
		$woocommerce_loop['view_more'] = $atts['view_more'];
		
		wc_set_loop_prop( 'is_shortcode', true );
		
		if ( ! empty( $atts['columns'] ) ) 
			$woocommerce_loop['categories_columns'] = $atts['columns'];
		
		add_filter( 'subcategory_archive_thumbnail_size', function($size) use ($atts){ return $atts['image_size']; } );
		
		if ( $options['p_cats'] ) {
			
			if ( $atts['display_type'] == 'menu' ) {
				
				$options['instance'] = array(
					'style'        => 'list',
					'title_li'     => '',
					'hierarchical' => true,
					'hide_empty'   => $atts['hide_empty'],
					'use_desc_for_title' => false,
					'pad_counts'   => true,
					'orderby'      => $atts['orderby'],
					'order'        => $atts['order'],
					'exclude'      => $atts['exclude'],
					'echo'         => 1,
					'taxonomy'     => 'product_cat'
				);
				
				if ( ! ( empty( $atts['parent'] ) && count( $options['p_exploded'] ) == 1 ) ) {
					$options['instance'] = array_merge( $options['instance'],
						array(
							'child_of' => $atts['parent']
						)
					);
				} else {
					$options['instance'] = array_merge( $options['instance'],
						array(
							'include' => $options['cat_ids'],
							'number'  => null
						)
					);
				}
				
				ob_start();
				
				if ( function_exists('etheme_enqueue_style')) {
					etheme_enqueue_style('woocommerce-archive', true);
					etheme_enqueue_style( 'categories-list-grid', true);
					etheme_enqueue_style( 'categories-menu-element', true);
				}
				
				?>

                <div class="categories-menu-element product-categories <?php echo esc_attr( $atts['class'] ); ?>">
					<?php
					echo esc_html( $atts['title'] );
					echo '<ul>';
					wp_list_categories( $options['instance'] );
					echo '</ul>';
					?>
                </div>
				
				<?php
				$options['output'] = ob_get_clean();
			} else {
				
				$atts['class'] .= ' slider-' . $options['box_id'];
				
				if ( $options['is_slider'] ) {
					
					if ( $atts['slider_autoplay'] ) {
						$atts['slider_autoplay'] = $atts['slider_interval'];
					}
					
					$atts['class'] .= ( $atts['pagination_type'] == 'lines' ) ? ' swiper-pagination-lines' : '';
					$atts['class'] .= ( $atts['no_space'] ) ? ' no-space' : '';
					
					$options['wrapper_attr'] = array_merge( $options['wrapper_attr'], array(
						'data-breakpoints="1"',
						'data-xs-slides="' . esc_js( $atts['mobile'] ) . '"',
						'data-sm-slides="' . esc_js( $atts['tablet_land'] ) . '"',
						'data-md-slides="' . esc_js( $atts['notebook'] ) . '"',
						'data-lt-slides="' . esc_js( $atts['large'] ) . '"',
						'data-slides-per-view="' . esc_js( $atts['large'] ) . '"',
						'data-autoplay="' . esc_attr( $atts['slider_autoplay'] ) . '"',
						'data-speed="' . esc_attr( $atts['slider_speed'] ) . '"',
                        'data-space="'. esc_attr( $atts['slider_spacing']) . '"'
					) );
					
					$atts['class'] .= ' categoriesCarousel swiper-container';
					
					if ( $atts['slider_loop'] ) {
						$options['wrapper_attr'][] = 'data-loop="true"';
					}
					
					if ( $atts['is_elementor']) {
					    $options['wrapper_class'][] = $atts['navigation_position_style'];
					    if ( 'middle-inside' === $atts['navigation_position'] )
						    $options['wrapper_class'][] = 'middle-inside';
                    }
					
				} elseif ( $atts['display_type'] == 'grid' ) {
					$atts['class'] .= ' categories-grid row';
					$atts['class'] .= ( $atts['no_space'] ) ? ' no-space' : '';
				}
				
				$options['styles'] = array();
				
				$options['styles']['style']          = $atts['style'];
				$options['styles']['text_color']     = $atts['text_color'];
				$options['styles']['valign']         = $atts['valign'];
				$options['styles']['text-align']     = $atts['text_align'];
				$options['styles']['text-transform'] = $atts['text_transform'];
				$options['styles']['count_label']    = $atts['count_label'];
				$options['styles']['sorting']        = $atts['sorting'];
				$options['styles']['hide_all']       = $atts['hide_all'];
				$options['styles']['image_circle']       = $atts['image_circle'];
				$options['styles']['stretch_images'] = $atts['stretch_images'];
				$options['styles']['is_elementor']     = $atts['is_elementor'];
				$options['styles']['content_type_new']     = $atts['content_type_new'];
				
				ob_start();
				
				if ( function_exists('etheme_enqueue_style')) {
					etheme_enqueue_style( 'woocommerce', true );
					etheme_enqueue_style( 'woocommerce-archive', true );
					etheme_enqueue_style( 'categories-carousel', true );
					
					if ( $atts['is_elementor'] ) {
						etheme_enqueue_style( 'elementor-categories', true );
					}
				}
				
				if ( $atts['title'] != '' ) { ?>

                    <h3 class="title"><span><?php echo esc_html( $atts['title'] ); ?></span></h3>
				
				<?php } ?>

                <div class="swiper-entry <?php echo implode(' ', $options['wrapper_class']); ?>">

                    <div class="<?php echo esc_attr( $atts['class'] ); ?>" <?php echo implode( ' ', $options['wrapper_attr'] ); ?>>
						
						<?php if ( $options['is_slider'] ) { ?>

                        <div class="swiper-wrapper">
							
							<?php }
							
							foreach ( $options['p_cats'] as $category2 ) {
								if ( is_array($category2) ) {
									foreach ($category2 as $category_obj) {
										if ( $options['is_slider'] ) { ?>
                                            <div class="swiper-slide">
										<?php }
										wc_get_template( 'content-product-cat.php', array(
											'category' => $category_obj,
											'styles'   => $options['styles']
										) );
										
										if ( $options['is_slider'] ) { ?>
                                            </div>
										<?php }
									}
								}
								else {
									if ( $options['is_slider'] ) { ?>
                                        <div class="swiper-slide">
									<?php }
									wc_get_template( 'content-product-cat.php', array(
										'category' => $category2,
										'styles'   => $options['styles']
									) );
									
									if ( $options['is_slider'] ) { ?>
                                        </div>
									<?php }
								}
								
							}
							
							if ( $options['is_slider'] ) { ?>
                        </div> <?php // .swiper-wrapper ?>
					<?php } ?>
						
						<?php if ( $atts['pagination_type'] != 'hide' && $options['is_slider'] ) {
							$options['pagination_class'] = '';
							if ( $atts['hide_fo'] == 'mobile' ) {
								$options['pagination_class'] = ' mob-hide';
							} elseif ( $atts['hide_fo'] == 'desktop' ) {
								$options['pagination_class'] = ' dt-hide';
							}
							?>

                            <div class="swiper-pagination<?php esc_html_e( $options['pagination_class'] ); ?>"></div>
						
						<?php } ?>

                    </div> <?php // .swiper-container ?>
					
					<?php
					if ( $options['is_slider'] && ( ! $atts['hide_buttons'] || ( $atts['hide_buttons'] && $atts['hide_buttons_for'] != '' ) ) ) {
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

                </div> <?php // .swiper-entry ?>
                
                <?php
				
				$options['swiper_slide_class_css'] = '.swiper-container.slider-'.$options['box_id'].':not(.initialized) .swiper-slide';
				
				$options['swiper_slide_media'] = $options['swiper_slide_class_css'] .' {width: '.(100/$atts['mobile']).'% !important;}';
				$options['swiper_slide_media'] .= '@media only screen and (min-width: 640px) { '.$options['swiper_slide_class_css'].' {width: '.(100/$atts['tablet_land']).'% !important;}}';
				$options['swiper_slide_media'] .= '@media only screen and (min-width: 1024px) { '.$options['swiper_slide_class_css'].' {width: '.(100/$atts['notebook']).'% !important;}}';
				$options['swiper_slide_media'] .= '@media only screen and (min-width: 1370px) { '.$options['swiper_slide_class_css'].' {width: '.(100/$atts['large']).'% !important;}}';
				if ( !$atts['is_elementor']) {
					$options['output_css'][] = $options['swiper_slide_media'];
				}
				else {
					if ( $atts['is_preview'] ) {
						echo '<style>' . $options['swiper_slide_media'] . '</style>';
					}
					else {
						wp_add_inline_style( 'xstore-inline-css', $options['swiper_slide_media'] );
					}
				}
                
                ?>
				
				<?php $options['output'] = ob_get_clean(); ?>
			<?php }
		}
		
		add_filter( 'subcategory_archive_thumbnail_size', function() { return 'woocommerce_thumbnail'; } );
		
		if ( $atts['is_preview'] ) {
			if ( !$atts['is_elementor'] )
				echo parent::initPreviewCss( $options['output_css'] );
			echo parent::initPreviewJs();
		} elseif ( !$atts['is_elementor'] ) {
			parent::initCss( $options['output_css'] );
		}
		
		$output = $options['output'];
		
		unset( $atts );
		unset( $options );
		
		unset($woocommerce_loop['image_hover']);
		unset($woocommerce_loop['add_overlay']);
		unset($woocommerce_loop['content_hover']);
		unset($woocommerce_loop['view_more']);
		
		woocommerce_reset_loop();
		
		return $output;
	}
	
}