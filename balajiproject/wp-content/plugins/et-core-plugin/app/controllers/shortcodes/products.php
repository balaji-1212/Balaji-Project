<?php

namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Products shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Products extends Shortcodes {
	
	function hooks() {
	}
	
	function products_shortcode( $atts, $content ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;

		global $wpdb, $woocommerce_loop;
		
		$atts = shortcode_atts( array(
			'ids'                  => '',
			'columns'              => 4,
			'shop_link'            => 1,
			'limit'                => 20,
			'taxonomies'           => '',
			'brands' => '',
			'product_tags' => '',
			'type'                 => 'slider',
			'navigation'           => 'off',
			'per_iteration'        => '',
			// 'first_loaded' => 4,
			'style'                => 'default',
			'show_counter'         => '',
			'show_stock'           => '',
			'show_category'        => true,
			'products'             => '', //featured new sale bestsellings recently_viewed
			'title'                => '',
			'hide_out_stock'       => '',
			'large'                => 4,
			'notebook'             => 3,
			'tablet_land'          => 2,
			'tablet_portrait'      => 2,
			'mobile'               => 1,
			'slider_autoplay'      => false,
			'slider_interval'      => 3000,
			'slider_speed'         => 300,
			'slider_loop'          => false,
			'slider_stop_on_hover' => false,
			'pagination_type'      => 'hide',
			'nav_color'            => '',
			'arrows_bg_color'      => '',
			'default_color'        => '#e1e1e1',
			'active_color'         => '#222',
			'hide_fo'              => '',
			'hide_buttons'         => false,
			'navigation_type'      => 'arrow',
			'navigation_position_style' => 'arrows-hover',
			'navigation_style'     => '',
			'navigation_position'  => 'middle',
			'hide_buttons_for'     => '',
			'orderby'              => 'date',
			'no_spacing'           => '',
			'show_image'           => true,
			'image_position'       => 'left',
			'order'                => 'ASC',
			'product_view'         => '',
			'product_view_color'   => '',
			'product_img_hover'    => '',
			'product_img_size'     => '',
			'show_excerpt'         => false,
			'excerpt_length'       => 120,
			'custom_template'      => '',
			'custom_template_list' => '',
			'per_move'             => 1,
			'autoheight'           => false,
			'ajax'                 => false,
			'ajax_loaded'          => false,
			'no_spacing_grid'      => false,
			'bordered_layout'      => false,
			'hover_shadow'         => false,
			'class'                => '',
			'css'                  => '',
			'is_preview'           => isset( $_GET['vc_editable'] ),
			
			'product_content_custom_elements' => false,
			'product_content_elements' => '',
			'product_add_to_cart_quantity' => false,
			'elementor'            => false,
		), $atts );
		
		// backward compatibility to woocommerce <= 3.8.0 because in newest versions few sizes were removed
		if ( $atts['product_img_size'] ) {
			$atts['product_img_size'] =
				str_replace(
					array( 'shop_thumbnail', 'shop_catalog', 'shop_single' ),
					array( 'woocommerce_gallery_thumbnail', 'woocommerce_thumbnail', 'woocommerce_single' ),
					$atts['product_img_size']
				);
		}
		
		if ( $atts['is_preview'] ) {
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			
			add_filter( 'etheme_output_shortcodes_inline_css', function () {
				return true;
			} );
		}
		
		$options = array();
		
		$woocommerce_loop['doing_ajax'] = defined( 'DOING_AJAX' ) && DOING_AJAX;
		
		$woocommerce_loop['product_view']       = $atts['product_view'];
		$woocommerce_loop['product_view_color'] = $atts['product_view_color'];
		$woocommerce_loop['hover']              = $atts['product_img_hover'];
		$woocommerce_loop['size']               = $atts['product_img_size'];
		$woocommerce_loop['show_image']         = $atts['show_image'];
		$woocommerce_loop['show_counter']       = $atts['show_counter'];
		$woocommerce_loop['show_stock']         = $atts['show_stock'];
		$woocommerce_loop['show_category']      = $atts['show_category'];
		$woocommerce_loop['show_excerpt']       = $atts['show_excerpt'];
		$woocommerce_loop['excerpt_length']     = $atts['excerpt_length'];
		
		if ( $atts['product_content_custom_elements'] && count($atts['product_content_elements'])) {
			$woocommerce_loop['product_content_elements'] = $atts['product_content_elements'];
			if ( in_array('product_page_product_excerpt', $atts['product_content_elements']) ) {
				$woocommerce_loop['show_excerpt']       = true;
			}
			$woocommerce_loop['product_add_to_cart_quantity'] = $atts['product_add_to_cart_quantity'];
		}
		
		if ( in_array( $atts['type'], array( 'grid', 'list' ) ) ) {
			if ( $atts['bordered_layout'] ) {
//				$atts['class'] .= ' products-bordered-layout';
				$woocommerce_loop['bordered_layout'] = true;
			}
			if ( $atts['no_spacing_grid'] ) {
//				$atts['class'] .= ' products-no-space';
				$woocommerce_loop['no_spacing_grid'] = true;
			}
		}
		
		if ( $atts['hover_shadow'] ) {
//				$atts['class'] .= ' products-no-space';
			$woocommerce_loop['hover_shadow'] = true;
		}
		
		if ( in_array( $atts['type'], array( 'grid', 'slider' ) ) ) {
			if ( ! empty( $atts['custom_template'] ) ) {
				$woocommerce_loop['custom_template'] = $atts['custom_template'];
			}
		} elseif ( $atts['type'] == 'list' ) {
			if ( ! empty( $atts['custom_template_list'] ) ) {
				$woocommerce_loop['custom_template'] = $atts['custom_template_list'];
			} elseif ( ! empty( $atts['custom_template'] ) ) {
				$woocommerce_loop['custom_template'] = $atts['custom_template'];
			}
		}
		
		$options['lazy_load_element'] = ! $atts['is_preview'] && $atts['ajax'];

//        $options['lazy_load_element'] = ! $atts['is_preview'] && $atts['ajax'] && $atts['navigation'] != 'lazy';
		
		if ( $atts['show_counter'] ) {
			wp_enqueue_script( 'et_countdown');
		}
		
		if ( $options['lazy_load_element'] ) {
			
			if ( function_exists('etheme_enqueue_style')) {
				if ( $atts['show_counter'] ) {
					if ( class_exists( 'WPBMap' ) ) {
						etheme_enqueue_style( 'wpb-et-timer' );
					} else {
						etheme_enqueue_style( 'et-timer' );
					}
				}
				etheme_enqueue_style( 'woocommerce' );
				etheme_enqueue_style( 'woocommerce-archive' );
				if ( etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
					etheme_enqueue_style( "swatches-style" );
				}
				if ( $woocommerce_loop['product_view'] && ! in_array( $woocommerce_loop['product_view'], array( 'disable', 'custom' ) ) ) {
					etheme_enqueue_style( 'product-view-' . $woocommerce_loop['product_view'] );
				}
				else {
					$options['local_product_view'] = etheme_get_option('product_view', 'disable');
					if ( !in_array($options['local_product_view'], array('custom', 'disable')) )
						etheme_enqueue_style( 'product-view-' . $options['local_product_view'] );
					
					if ( $woocommerce_loop['product_view'] == 'custom' || $options['local_product_view'] == 'custom' ) {
						etheme_enqueue_style( 'content-product-custom' );
					}
				}
				if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
					etheme_enqueue_style( 'content-product-custom' );
				}
	
				if ( etheme_get_option('quick_view', 1) ) {
					etheme_enqueue_style( "quick-view" );
					if ( etheme_get_option('quick_view_content_type', 'popup') == 'off_canvas' ) {
						etheme_enqueue_style( "off-canvas" );
					}
				}
			}
			$options['extra'] = ( $atts['type'] == 'slider' ) ? 'slider' : '';
			return et_ajax_element_holder( 'etheme_products', $atts, $options['extra'] );
		}
		
		$options['product_visibility_term_ids'] = wc_get_product_visibility_term_ids();
		
		$options['wp_query_args'] = array(
			'post_type'           => array('product'),
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'posts_per_page'      => $atts['limit'],
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
		);
		
		if ( $atts['hide_out_stock'] ) {
			$options['wp_query_args']['meta_query'] = array(
				array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '='
				),
			);
		}
		
		$options['wp_query_args']['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => is_search() ? $options['product_visibility_term_ids']['exclude-from-search'] : $options['product_visibility_term_ids']['exclude-from-catalog'],
			'operator' => 'NOT IN',
		);
		
		switch ( $atts['products'] ) {
			
			case 'featured':
				$options['featured_product_ids']      = wc_get_featured_product_ids();
				$options['wp_query_args']['post__in'] = array_merge( array( 0 ), $options['featured_product_ids'] );
				break;
			
			case 'sale':
				$options['product_ids_on_sale']       = wc_get_product_ids_on_sale();
				$options['wp_query_args']['post__in'] = array_merge( array( 0 ), $options['product_ids_on_sale'] );
				break;
			
			case 'bestsellings':
				$options['wp_query_args']['meta_key'] = 'total_sales';
				$options['wp_query_args']['order']    = 'DESC';
				$options['wp_query_args']['orderby']  = 'meta_value_num';
				break;
			
			case 'recently_viewed':
				$options['viewed_products'] = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
				$options['viewed_products'] = array_filter( array_map( 'absint', $options['viewed_products'] ) );
				
				set_query_var( 'recently_viewed', true );
				
				if ( empty( $options['viewed_products'] ) ) {
					return;
				}
				
				$options['wp_query_args']['post__in'] = $options['viewed_products'];
				$options['wp_query_args']['orderby']  = 'rand';
				
				break;
			
		}
		
		// WCMp vendor plugin compatibility
		if ( function_exists( 'get_wcmp_vendor_settings' ) && get_transient( 'wcmp_spmv_exclude_products_data' ) ) {
			$options['wcmp_vendor_settings']                   = array();
			$options['wcmp_vendor_settings']['spmv_excludes']  = get_transient( 'wcmp_spmv_exclude_products_data' );
			$options['wcmp_vendor_settings']['excluded_order'] = ( get_wcmp_vendor_settings( 'singleproductmultiseller_show_order', 'general' ) ) ? get_wcmp_vendor_settings( 'singleproductmultiseller_show_order', 'general' ) : 'min-price';
			$options['wcmp_vendor_settings']['post__not_in']   = ( isset( $options['wcmp_vendor_settings']['spmv_excludes'][ $options['wcmp_vendor_settings']['excluded_order'] ] ) ) ? $options['wcmp_vendor_settings']['spmv_excludes'][ $options['wcmp_vendor_settings']['excluded_order'] ] : array();
			$options['wp_query_args']['post__not_in']          = ( isset( $options['wp_query_args']['post__not_in'] ) ) ? array_merge( $options['wp_query_args']['post__not_in'], $options['wcmp_vendor_settings']['post__not_in'] ) : $options['wcmp_vendor_settings']['post__not_in'];
		}
		
		if ( $atts['type'] == 'slider' ) {
			if ( $atts['slider_stop_on_hover'] ) {
				$atts['class'] .= ' stop-on-hover';
			}
		}
		
		if ( ! empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		}
		
		if ( $atts['orderby'] == 'price' ) {
			$options['wp_query_args']['meta_key'] = '_price';
			$options['wp_query_args']['orderby']  = 'meta_value_num';
		}
		
		if ( $atts['ids'] != '' ) {
			if ( ! is_array( $atts['ids'] ) ) {
				$atts['ids'] = explode( ',', $atts['ids'] );
			}
			$atts['ids']                          = array_map( 'trim', $atts['ids'] );
			$options['wp_query_args']['post_type'][] = 'product_variation';
			$options['wp_query_args']['post__in'] = $atts['ids'];
		}
		
		// Narrow by categories
		if ( ! empty( $atts['taxonomies'] ) ) {
			if ( $atts['elementor']) {
				$categories = array_map( 'sanitize_title', explode( ',', $atts['taxonomies'] ) );
				$field      = 'slug';
				
				if ( is_numeric( $categories[0] ) ) {
					$field      = 'term_id';
					$categories = array_map( 'absint', $categories );
					// Check numeric slugs.
					foreach ( $categories as $cat ) {
						$the_cat = get_term_by( 'slug', $cat, 'product_cat' );
						if ( false !== $the_cat ) {
							$categories[] = $the_cat->term_id;
						}
					}
				}
				
				$options['wp_query_args']['tax_query'][] = array(
					'taxonomy'         => 'product_cat',
					'terms'            => $categories,
					'field'            => $field,
					'operator'         => 'IN',
					'include_children' => true,
				);
			}
			else {
				$options['taxonomy_names'] = get_object_taxonomies( 'product' );
				$options['terms']          = get_terms( $options['taxonomy_names'], array(
					'orderby' => 'name',
					'include' => $atts['taxonomies']
				) );
				
				if ( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
					$options['wp_query_args']['tax_query'] = array( 'relation' => 'OR' );
					foreach ( $options['terms'] as $key => $term ) {
						$options['wp_query_args']['tax_query'][] = array(
							'taxonomy'         => $term->taxonomy,
							'field'            => 'slug',
							'terms'            => array( $term->slug ),
							'include_children' => true,
							'operator'         => 'IN'
						);
					}
				}
			}
		}
		
		if ( ! empty( $atts['product_tags'] ) ) {
			if ( $atts['elementor']) {
				$categories = array_map( 'sanitize_title', explode( ',', $atts['product_tags'] ) );
				$field      = 'slug';
				
				if ( is_numeric( $categories[0] ) ) {
					$field      = 'term_id';
					$categories = array_map( 'absint', $categories );
					// Check numeric slugs.
					foreach ( $categories as $cat ) {
						$the_cat = get_term_by( 'slug', $cat, 'product_tag' );
						if ( false !== $the_cat ) {
							$categories[] = $the_cat->term_id;
						}
					}
				}
				
				$options['wp_query_args']['tax_query'][] = array(
					'taxonomy'         => 'product_tag',
					'terms'            => $categories,
					'field'            => $field,
					'operator'         => 'IN',
					'include_children' => true,
				);
			}
			else {
				$options['taxonomy_names'] = get_object_taxonomies( 'product' );
				$options['terms']          = get_terms( $options['taxonomy_names'], array(
					'orderby' => 'name',
					'include' => $atts['taxonomies']
				) );
				
				if ( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
					$options['wp_query_args']['tax_query'] = array( 'relation' => 'OR' );
					foreach ( $options['terms'] as $key => $term ) {
						$options['wp_query_args']['tax_query'][] = array(
							'taxonomy'         => $term->taxonomy,
							'field'            => 'slug',
							'terms'            => array( $term->slug ),
							'include_children' => true,
							'operator'         => 'IN'
						);
					}
				}
			}
		}
		
		// Narrow by brands
		if ( ! empty( $atts['brands'] ) ) {
			if ( $atts['elementor']) {
				$brands = array_map( 'sanitize_title', explode( ',', $atts['brands'] ) );
				$field      = 'slug';
				
				if ( is_numeric( $brands[0] ) ) {
					$field      = 'term_id';
					$brands = array_map( 'absint', $brands );
					// Check numeric slugs.
					foreach ( $brands as $cat ) {
						$the_cat = get_term_by( 'slug', $cat, 'brand' );
						if ( false !== $the_cat ) {
							$brands[] = $the_cat->term_id;
						}
					}
				}
				$options['wp_query_args']['tax_query'][] = array(
					'taxonomy'         => 'brand',
					'terms'            => $brands,
					'field'            => $field,
					'operator'         => 'IN',
					'include_children' => true,
				);
			}
			else {
				$options['taxonomy_names'] = get_object_taxonomies( 'product' );
				$options['terms']          = get_terms( $options['taxonomy_names'], array(
					'orderby' => 'name',
					'include' => $atts['brands']
				) );
				
				if ( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
					$options['wp_query_args']['tax_query'] = array( 'relation' => 'OR' );
					foreach ( $options['terms'] as $key => $term ) {
						$options['wp_query_args']['tax_query'][] = array(
							'taxonomy'         => $term->taxonomy,
							'field'            => 'slug',
							'terms'            => array( $term->slug ),
							'include_children' => true,
							'operator'         => 'IN'
						);
					}
				}
			}
		}
		
		ob_start();
		
		if ( !$options['lazy_load_element'] && !$atts['ajax_loaded'] ) {
			if ( function_exists( 'etheme_enqueue_style' ) ) {
				if ( $atts['show_counter'] ) {
					if ( class_exists( 'WPBMap' ) ) {
						etheme_enqueue_style( 'wpb-et-timer', true );
					} else {
						etheme_enqueue_style( 'et-timer', true );
					}
				}
				etheme_enqueue_style( 'woocommerce', true );
				etheme_enqueue_style( 'woocommerce-archive', true );
				if ( etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
					etheme_enqueue_style( "swatches-style", true );
				}
				if ( $woocommerce_loop['product_view'] && ! in_array( $woocommerce_loop['product_view'], array( 'disable', 'custom' ) ) ) {
					etheme_enqueue_style( 'product-view-' . $woocommerce_loop['product_view'], true );
				}
				else {
					$options['local_product_view'] = etheme_get_option('product_view', 'disable');
					if ( !in_array($options['local_product_view'], array('custom', 'disable')) )
						etheme_enqueue_style( 'product-view-' . $options['local_product_view'], true );
					
					if ( $woocommerce_loop['product_view'] == 'custom' || $options['local_product_view'] == 'custom' ) {
						etheme_enqueue_style( 'content-product-custom', true );
						$options['content-product-custom-loaded'] = true;
					}
				}
				if ( !isset($options['content-product-custom-loaded']) || ! empty( $woocommerce_loop['custom_template'] ) ) {
					etheme_enqueue_style( 'content-product-custom', true );
				}
			}
		}
		
		switch ( $atts['type'] ) {
			case 'slider':
				$options['slider_args'] = array(
					'title'               => $atts['title'],
					'shop_link'           => $atts['shop_link'],
					'slider_type'         => false,
					'style'               => $atts['style'],
					'no_spacing'          => $atts['no_spacing'],
					'large'               => (int) $atts['large'],
					'notebook'            => (int) $atts['notebook'],
					'tablet_land'         => (int) $atts['tablet_land'],
					'tablet_portrait'     => (int) $atts['tablet_portrait'],
					'mobile'              => (int) $atts['mobile'],
					'slider_autoplay'     => $atts['slider_autoplay'],
					'slider_interval'     => $atts['slider_interval'],
					'slider_speed'        => $atts['slider_speed'],
					'slider_loop'         => $atts['slider_loop'],
					'pagination_type'     => $atts['pagination_type'],
					'nav_color'           => $atts['nav_color'],
					'arrows_bg_color'     => $atts['arrows_bg_color'],
					'default_color'       => $atts['default_color'],
					'active_color'        => $atts['active_color'],
					'hide_buttons'        => $atts['hide_buttons'],
					'navigation_type'     => $atts['navigation_type'],
					'navigation_position_style' => $atts['navigation_position_style'],
					'navigation_style'    => $atts['navigation_style'],
					'navigation_position' => $atts['navigation_position'],
					'hide_buttons_for'    => $atts['hide_buttons_for'],
					'hide_fo'             => $atts['hide_fo'],
					'per_move'            => $atts['per_move'],
					'autoheight'          => $atts['autoheight'],
					'class'               => ( ! empty( $atts['custom_template'] ) ) ? 'products-with-custom-template products-template-' . $atts['custom_template'] . $atts['class'] : $atts['class'],
					'attr'                => ( ! empty( $atts['custom_template'] ) ) ? 'data-post-id="' . $atts['custom_template'] . '"' : '',
					'echo'                => true,
					'elementor'           => $atts['elementor'],
					'is_preview'          => $atts['is_preview'],
				);
				etheme_slider( $options['wp_query_args'], 'product', $options['slider_args'] );
				break;
			case 'full-screen':
				$options['slider_args'] = array(
					'title' => $atts['title'],
					'class' => $atts['class']
				);
				echo etheme_fullscreen_products( $options['wp_query_args'], $options['slider_args'] );
				break;
			default:
				// ! Add attr for lazy loading
				$options['atts']                = $options['extra'] = array();
				$options['extra']['navigation'] = $atts['navigation'];
				$options['extra']['ajax_loaded'] = (bool)$atts['ajax_loaded'];
				
				if ( $atts['navigation'] != 'off' ) {
					if ( isset( $options['wp_query_args']['post__in'] ) ) {
						$options['atts'][] = 'data-ids="' . implode( ',', $options['wp_query_args']['post__in'] ) . '"';
					}
					
					if ( isset( $options['wp_query_args']['orderby'] ) ) {
						$options['atts'][] = 'data-orderby="' . $options['wp_query_args']['orderby'] . '"';
					}
					
					if ( isset( $options['wp_query_args']['order'] ) ) {
						$options['atts'][] = 'data-order="' . $options['wp_query_args']['order'] . '"';
					}
					
					if ( $atts['hide_out_stock'] ) {
						$options['atts'][] = 'data-stock="true"';
					}
					
					if ( $atts['products'] ) {
						$options['atts'][] = 'data-type="' . $atts['products'] . '"';
					}
					
					if ( ! empty( $atts['taxonomies'] ) ) {
						$options['atts'][] = 'data-taxonomies="' . $atts['taxonomies'] . '"';
					}
					
					if ( ! empty( $atts['brands'] ) ) {
						$options['atts'][] = 'data-brands="' . $atts['brands'] . '"';
					}
					
					if ( ! empty( $atts['product_tags'] ) ) {
						$options['atts'][] = 'data-product_tags="' . $atts['product_tags'] . '"';
					}
					
					if ( ! empty( $atts['product_view'] ) ) {
						$options['atts'][] = 'data-product_view="' . $atts['product_view'] . '"';
					}
					
					if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
						$options['atts'][] = 'data-custom_template="' . $woocommerce_loop['custom_template'] . '"';
					}
					
					if ( ! empty( $atts['product_view_color'] ) ) {
						$options['atts'][] = 'data-product_view_color="' . $atts['product_view_color'] . '"';
					}
					
					if ( ! empty( $atts['product_img_hover'] ) ) {
						$options['atts'][] = 'data-hover="' . $atts['product_img_hover'] . '"';
					}
					
					if ( ! empty( $atts['product_img_size'] ) ) {
						$options['atts'][] = 'data-size="' . $atts['product_img_size'] . '"';
					}
					
					if ( $atts['show_counter'] ) {
						$options['atts'][] = 'data-show_counter="true"';
					}
					
					if ( $atts['show_stock'] ) {
						$options['atts'][] = 'data-show_stock="true"';
					}
					
					// if ( $atts['first_loaded'] > $atts['limit'] )
					//     $atts['first_loaded'] = $atts['limit'];
					
					$options['extra']['per-page'] = ( $atts['limit'] != -1 && $atts['limit'] < $atts['columns'] ) ? $atts['limit'] : $atts['columns'];
					
					if ( $atts['per_iteration'] && ( $atts['limit'] == -1 || $atts['limit'] >= $atts['per_iteration'] ) ) {
						$options['extra']['per-page'] = $atts['per_iteration'];
					}
					
					$options['extra']['limit']   = $atts['limit'];
					$options['extra']['columns'] = $atts['columns'];
					
					$options['atts'][] = 'data-columns="' . $atts['columns'] . '"';
					$options['atts'][] = 'data-per-page="' . $options['extra']['per-page'] . '"';
					
					if ( $atts['product_content_custom_elements'] && count($atts['product_content_elements'])) {
						$options['atts'][] = 'data-product_content_elements="'. implode(',', $atts['product_content_elements']) . '"';
//						if ( in_array('product_page_product_excerpt', $atts['product_content_elements']) ) {
//							$woocommerce_loop['show_excerpt']       = true;
//						}
					}
				}
				
				if ( $atts['type'] == 'menu' ) {
					$atts['class'] .= ' products-layout-menu';
				}
				
				// ! Add attr for lazy loading end.
				$woocommerce_loop['view_mode'] = $atts['type'];

				echo '<div class="etheme_products etheme_products-r' . rand(1,999) . ' ' . $atts['class'] . '" ' . implode( ' ', $options['atts'] ) . '>';
				if ( $atts['type'] == 'menu' ) {
					echo etheme_products_menu_layout( $options['wp_query_args'], $atts['title'], $atts['columns'], $atts['image_position'], $options['extra'], $atts['is_preview']);
				} else {
					echo etheme_products( $options['wp_query_args'], $atts['title'], $atts['columns'], $options['extra'] );
				}
				echo '</div>';
				unset( $woocommerce_loop['view_mode'] );
				break;
		}
		
		unset( $woocommerce_loop['doing_ajax'] );
		unset( $woocommerce_loop['product_view'] );
		unset( $woocommerce_loop['product_view_color'] );
		unset( $woocommerce_loop['hover'] );
		unset( $woocommerce_loop['size'] );
		unset( $woocommerce_loop['show_image'] );
		unset( $woocommerce_loop['show_category'] );
		unset( $woocommerce_loop['show_excerpt'] );
		unset( $woocommerce_loop['excerpt_length'] );
		unset( $woocommerce_loop['show_counter'] );
		unset( $woocommerce_loop['show_stock'] );
		if ( ! empty( $atts['custom_template'] ) ) {
			unset( $woocommerce_loop['custom_template'] );
		}
		
		unset($woocommerce_loop['bordered_layout']);
		unset($woocommerce_loop['product_content_elements']);
		unset($woocommerce_loop['product_add_to_cart_quantity']);
		unset($woocommerce_loop['no_spacing_grid']);
		unset($woocommerce_loop['hover_shadow']);
		
		unset( $options );
		unset( $atts );
		
		return ob_get_clean();
	}
}