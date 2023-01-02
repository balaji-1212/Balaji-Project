<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Etheme_ajax_search.
 *
 * Return data for ajax search request.
 *
 * @since   1.4.3
 * @version 1.0.2
 * @var     $request {array} list of all $_REQUEST data.
 * @log     1.0.1
 * ADDED: search_product_variation
 * @log     1.0.2
 * ADDED: etheme_ajax_search_posts_query filter
 * FIXED: hidden products
 */
class Etheme_ajax_search {
	// ! Declare default variables
	public $request = array();
	
	// ! Main construct/ setup variables
	function __construct() {
		$this->request = $_REQUEST;
	}
	
	/**
	 * Add actions.
	 *
	 * Actions for ajax search.
	 *
	 * @since   1.4.3
	 * @version 1.0.0
	 */
	public function actions() {
		add_action( 'wp_ajax_etheme_ajax_search', array( $this, 'search_results' ) );
		add_action( 'wp_ajax_nopriv_etheme_ajax_search', array( $this, 'search_results' ) );
	}
	
	/**
	 * Search for posts and pages.
	 *
	 * @param   {array} $args Query args.
	 *
	 * @return  {array}       Posts.
	 * @log     1.0.2
	 * ADDED: etheme_ajax_search_posts_query filter
	 * @since   1.4.3
	 * @version 1.0.2
	 */
	public function get_posts_results( $args ) {
		$args['s'] = trim( $this->request['query'] );
		$args      = apply_filters( 'etheme_ajax_search_posts_query', $args );
		
		return get_posts( http_build_query( $args ) );
	}
	
	/**
	 * Gets products based on the search type specified.
	 *
	 * @param   {string} $type Type of search.
	 * @param   {array}  $args Query args.
	 *
	 * @return  {array}        Posts.
	 * @log     1.0.1
	 * ADDED: search_product_variation
	 * @log     1.0.2
	 * FIXED: hidden product
	 * @log     1.0.3
	 * ADDED: search_product_hide_variation_parent (option)
	 * @version 1.0.3
	 * @since   1.4.3
	 */
	public function get_products_results( $type, $args = array() ) {
		global $woocommerce;
		$order_by      = 'relevance';
		$ordering_args = $woocommerce->query->get_catalog_ordering_args( $order_by, 'ASC' );
		$defaults      = $args;
		$visibility    = wc_get_product_visibility_term_ids();
		
		$args['post_type']  = array( 'product' );
		$args['orderby']    = $ordering_args['orderby'];
		$args['order']      = $ordering_args['order'];
		$args['meta_query'] = WC()->query->get_meta_query(); // WPCS: slow query ok.
		$args['tax_query']  = array(); // WPCS: slow query ok.
		
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => array_merge( (array) $visibility['exclude-from-catalog'], (array) $visibility['exclude-from-search'] ),
			'operator' => 'NOT IN',
		);

// removed and made as array merge above in 4.1.4
//		$args['tax_query'][] = array(
//			'taxonomy' => 'product_visibility',
//			'field'    => 'term_taxonomy_id',
//			'terms'    => $visibility['exclude-from-search'],
//			'operator' => 'NOT IN',
//		);
		
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $visibility['outofstock'],
				'operator' => 'NOT IN',
			);
		}
		
		if ( isset( $this->request['product_cat'] ) && $this->request['product_cat'] && $this->request['product_cat'] !== '0' ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => esc_attr( $this->request['product_cat'] ),
			);
		}
		
		switch ( $type ) {
			case 'product':
				$args['s']         = trim( $this->request['query'] );
				$args['post_type'] = array( 'product' );
				break;
			case 'sku':
				$query                = trim( $this->request['query'] );
				$args['s']            = '';
				$args['post_type']    = array( 'product' );
				$args['meta_query'][] = array(
					'key'     => '_sku',
					'value'   => $query,
					'compare' => 'LIKE',
				);
				break;
		}
		
		if ( etheme_get_option( 'search_product_variation_et-desktop', 0 ) ) {
			$args['post_type'][] = 'product_variation';
			if ( get_theme_mod( 'search_product_hide_variation_parent_et-desktop', 0 ) ) {
				$args['tax_query'][] = array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'variable',
						'operator' => 'NOT IN',
					),
				);
			}
		}
		
		if ( isset( $this->request['lang'] ) && ! empty( $this->request['lang'] ) ) {
			$args['lang'] = $this->request['lang'];
		}
		
		$args = apply_filters( 'etheme_ajax_search_products_query', $args );
		
		return get_posts( http_build_query( $args ) );
	}
	
	/**
	 * Search_results.
	 *
	 * etheme_ajax_search callback.
	 * This function in "app/models/customizer/frontend/frontend-script.js".
	 *
	 * @return  {json} array of suggestions for search
	 * @log     1.0.1
	 * ADDED: search_product_variation
	 * @version 1.0.1
	 * @since   1.4.3
	 */
	public function search_results() {
		$query                        = trim( $this->request['query'] );
		$products                     = array();
		$posts                        = array();
		$pages                        = array();
		$portfolio                    = array();
		$post_types_results           = array();
		$sku_products                 = array();
		$suggestions                  = array();
		$results                      = array();
		$search_options               = get_theme_mod( 'search_results_content_et-desktop', array(
			'products',
			'posts'
		) );
		$search_sku                   = get_theme_mod( 'search_by_sku_et-desktop', 1 );
		$post_types_counts            = array();
		$post_types_limits            = array();
		$custom_post_types_limits     = false;
		$custom_post_types_limits_val = 5;
		$is_shortcode                 = isset( $this->request['shortcode'] );
		
		$cache = get_theme_mod( 'ajax_search_cache', 0 );
		
		if ( $cache && $this->request_in_cache( $query ) ) {
			wp_send_json( array( 'suggestions' => $this->request_in_cache( $query ) ) );
		}
		
		if ( isset( $this->request['custom_post_types'] ) ) {
			$search_options = explode( ',', $this->request['custom_post_types'] );
		}
		
		if ( isset( $this->request['custom_post_limits'] ) ) {
			$custom_post_types_limits_val = $this->request['custom_post_limits'];
			$custom_post_types_limits     = true;
		}
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			$search_options = array_diff( $search_options, array( 'products', 'product' ) );
		}
		
		$args = array(
			's'                   => $query,
			'orderby'             => '',
			'post_type'           => array(),
			'post_status'         => 'publish',
			'posts_per_page'      => isset( $this->request['posts_per_page'] ) ? $this->request['posts_per_page'] : 100,
			'ignore_sticky_posts' => 1,
			'post_password'       => '',
			'suppress_filters'    => false,
		);
		
		foreach ( $search_options as $key => $value ) {
			
			if ( in_array( $value, array( 'products', 'product' ) ) ) {
				
				// WooCommerce German Market plugin compatibility
				add_filter( 'german_market_wp_bakery_price_html_exception', '__return_true' );
				
				// WCMp vendor plugin compatibility
				if ( function_exists( 'get_wcmp_vendor_settings' ) && get_transient( 'wcmp_spmv_exclude_products_data' ) ) {
					$spmv_excludes        = get_transient( 'wcmp_spmv_exclude_products_data' );
					$excluded_order       = ( get_wcmp_vendor_settings( 'singleproductmultiseller_show_order', 'general' ) ) ? get_wcmp_vendor_settings( 'singleproductmultiseller_show_order', 'general' ) : 'min-price';
					$post__not_in         = ( isset( $spmv_excludes[ $excluded_order ] ) ) ? $spmv_excludes[ $excluded_order ] : array();
					$args['post__not_in'] = $post__not_in;
				}
				
				$post_types_results[ $value ] = $this->get_products_results( 'product', $args );
				if ( $search_sku ) {
					$sku_products                 = $this->get_products_results( 'sku', $args );
					$post_types_results[ $value ] = $post_types_results[ $value ] + $sku_products;
				}
				if ( isset( $this->request['show_count'] ) ) {
					$post_types_counts[ $value ] = count( $post_types_results[ $value ] );
				}
				if ( $custom_post_types_limits ) {
					$post_types_limits['products'] = $custom_post_types_limits_val;
				} else {
					$post_types_limits['products'] = 5;
				}
			} else {
				$value                        = str_replace( array( 'posts', 'pages', 'portfolio' ), array(
					'post',
					'page',
					'etheme_portfolio'
				), $value );
				$args['post_type']            = $value;
				$post_types_results[ $value ] = $this->get_posts_results( $args );
				if ( isset( $this->request['show_count'] ) ) {
					$post_types_counts[ $value ] = count( $post_types_results[ $value ] );
				}
				
				if ( $custom_post_types_limits ) {
					$post_types_limits[ $value ] = $custom_post_types_limits_val;
				} else {
					$post_types_limits[ $value ] = $value == 'page' ? 6 : 5;
				}
			}
		}

//		if ( in_array( 'products', $search_options ) ) {
//			// WCMp vendor plugin compatibility
//			if ( function_exists('get_wcmp_vendor_settings') && get_transient('wcmp_spmv_exclude_products_data')) {
//				$spmv_excludes = get_transient('wcmp_spmv_exclude_products_data');
//				$excluded_order = (get_wcmp_vendor_settings('singleproductmultiseller_show_order', 'general')) ? get_wcmp_vendor_settings('singleproductmultiseller_show_order', 'general') : 'min-price';
//				$post__not_in = ( isset( $spmv_excludes[$excluded_order] ) ) ? $spmv_excludes[$excluded_order] : array();
//				$args['post__not_in'] = $post__not_in;
//			}
//
//			$products     = $this->get_products_results( 'product', $args );
//			if ( $search_sku ) {
//				$sku_products = $this->get_products_results( 'sku', $args );
//			}
//		}

//		if ( in_array( 'posts', $search_options ) ) {
//			$args['post_type'] = 'post';
//			$posts = $this->get_posts_results( $args );
//			if ( isset($this->request['show_count'])) {
//				$counts['post'] = count( $posts );
//			}
//		}
//
//		if ( in_array( 'pages', $search_options ) ) {
//			$args['post_type'] = 'page';
//			$pages = $this->get_posts_results( $args );
//			if ( isset($this->request['show_count'])) {
//				$counts['page'] = count( $pages );
//			}
//		}
//
//		if ( in_array( 'portfolio', $search_options ) ) {
//			$args['post_type'] = 'etheme_portfolio';
//			$portfolio = $this->get_posts_results( $args );
//			if ( isset($this->request['show_count'])) {
//				$counts['etheme_portfolio'] = count( $portfolio );
//			}
//		}

//		$products = array_merge( $products, $sku_products );

//		if ( isset($post_types_results['products']) ) {
////			$post_types_results['products'] = $post_types_results['products'] + $sku_products;
//			if ( isset( $this->request['show_count'] ) ) {
//				$post_types_counts['products'] = count( $post_types_results['products'] );
//			}
//		}
		
		foreach ( $search_options as $key => $value ) {
			$value   = str_replace( array( 'posts', 'pages', 'portfolio' ), array(
				'post',
				'page',
				'etheme_portfolio'
			), $value );
			$results = array_merge( $results, $post_types_results[ $value ] );
//			switch ( $value ) {
//				case 'products':
//					$results = array_merge( $results, $products );
//					break;
//				case 'posts':
//					$results = array_merge( $results, $posts );
//					break;
//				case 'pages':
//					$results = array_merge( $results, $pages );
//					break;
//				case 'portfolio':
//					$results = array_merge( $results, $portfolio );
//					break;
//			}
		}
		
		$arrow = apply_filters( 'etheme_ajax_search_arrow', true ); // one user with china lang has true instead of normal arrow
		
		$just_catalog = function_exists( 'etheme_is_catalog' ) ? etheme_is_catalog() : false;
		
		foreach ( $results as $key => $post ) {
			if ( in_array( $post->post_type, array( 'product', 'product_variation' ) ) ) {
				if ( isset( $this->request['full_screen'] ) || $custom_post_types_limits ) {
					if ( $post_types_limits['products'] < 1 ) {
						continue;
					}
					$post_types_limits['products'] --;
				}
				$product       = wc_get_product( $post );
				$product_image = wp_get_attachment_image_src( $product->get_image_id(), 'woocommerce_thumbnail' );

//				if ($product->get_type() == 'variation'){
//					$product_image = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail');
//				}
				
				add_filter( 'woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2 );
				
				
				$all_count = '';
				
				if ( isset( $post_types_counts['product'] ) ) {
					$all_count = $post_types_counts['product'];
				}
				
				if ( isset( $post_types_counts['products'] ) ) {
					$all_count = $post_types_counts['products'];
				}
				
				
				$product_args = array(
					'type'      => 'Product',
					'id'        => $product->get_id(),
					'value'     => $product->get_title(),
					'url'       => $product->get_permalink(),
					'img'       => ( $product_image[0] ) ? $product_image[0] : wc_placeholder_img_src( 'woocommerce_thumbnail' ),
					'arrow'     => $arrow,
					'price'     => $product->get_price_html(),
					'stock'     => wc_get_stock_html( $product ),
					'in_stock'  => $product->is_in_stock(),
					'all_count' => $all_count,
					's'         => $query
//					'date'  => '',
				);
				
				if ( $just_catalog && get_theme_mod( 'just_catalog_price', 0 ) ) {
					$login_text = get_theme_mod( 'ltv_price', esc_html__( 'Login to view price', 'xstore-core' ) );
					if ( $login_text == '' ) {
						unset( $product_args['price'] );
					} else {
						$product_args['price'] = $login_text;
					}
				}
				
				// @deprecated 'etheme_ajax_search_products_sku' since v4.3.4 and used in_array('ajax-search-results', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart')))
				// @todo remove 'etheme_ajax_search_products_sku' in v4.6
				if ( ( in_array( 'ajax-search-results', (array) get_theme_mod( 'product_sku_locations', array(
							'cart',
							'popup_added_to_cart',
							'mini-cart'
						) ) ) || apply_filters( 'etheme_ajax_search_products_sku', false ) || $is_shortcode ) && wc_product_sku_enabled() && ( $product->get_sku() || $product->get_type() == 'variable' ) ) {
					
					$product_args = array_merge( $product_args,
						array(
							'sku' => esc_html__( 'SKU:', 'xstore-core' ) . '<span class="sku">' . ( ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) ) . '</span>'
						)
					);
					
				}
				
				remove_filter( 'woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2 );
				
				if ( isset( $this->request['full_screen'] ) ) {
					
					$product_args['arrow'] = false;
//					unset($product_args['arrow']);
					
					$product_attr = array(
						'href = "' . $product->add_to_cart_url() . '"',
						'quantity="1"',
						'class = "' . implode(
							' ',
							array_filter(
								array(
									'button',
									'product_type_' . $product->get_type(),
									$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
									$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
								)
							)
						) . '"',
						'data-product_id = "' . $product->get_id() . '"',
						'data-product_sku = "' . $product->get_sku() . '"',
						'aria-label = "' . $product->add_to_cart_description() . '"',
						'rel="nofollow"',
					);
					
					$product_args = array_merge( $product_args,
						array(
							'add_to_cart_text' => $product->add_to_cart_text(),
							'add_to_cart_args' => implode( ' ', $product_attr )
						) );
				}
				
				$suggestions[] = $product_args;
				
			} //			elseif( in_array( $post->post_type, array( 'page', 'post', 'etheme_portfolio' ) ) ) {
			else {
				if ( isset( $this->request['full_screen'] ) || $custom_post_types_limits ) {
					if ( $post_types_limits[ $post->post_type ] < 1 ) {
						continue;
					}
					$post_types_limits[ $post->post_type ] --;
				}
				$args = array(
					'type'      => ucfirst( str_replace( array( 'page', 'post', 'etheme_portfolio' ), array(
						'Pages',
						'Post',
						'Portfolio'
					), $post->post_type ) ),
					'id'        => $post->ID,
					'value'     => get_the_title( $post->ID ),
					'url'       => get_the_permalink( $post->ID ),
					'img'       => ( isset( $this->request['full_screen'] ) && $post->post_type == 'page' ) ? false : get_the_post_thumbnail_url( $post->ID, 'medium' ),
					'arrow'     => $arrow,
					'date'      => get_the_date( '', $post->ID ),
					'all_count' => isset($post_types_counts[ $post->post_type ]) ? $post_types_counts[ $post->post_type ] : 0,
					's'         => $query
				);
				
				if ( isset( $this->request['full_screen'] ) ) {
					if ( $post->post_type == 'post' ) {
						ob_start();
						etheme_byline(
							array(
								'author'        => 0,
								'time'          => 0,
								'ID'            => $post->ID,
								'views_counter' => true
							)
						);
						$args['meta'] = ob_get_clean();
//						$excerpt_length = etheme_get_option('excerpt_length', 25);
//						ob_start();
//						if ( $excerpt_length > 0 ) {
//							if ( strlen($post->post_excerpt) > 0 ) {
//								$excerpt_length = apply_filters( 'excerpt_length', $excerpt_length );
//								$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
//								$excerpt         = wp_trim_words( $post->post_excerpt, $excerpt_length, $excerpt_more );
//								echo do_shortcode(apply_filters( 'wp_trim_excerpt', $excerpt, $excerpt ));
//							}
//							else
//								echo do_shortcode($post->post_excerpt);
//						}
//						$args['excerpt'] = ob_get_clean();
						$args['post_class'] = implode( ' ', get_post_class( 'blog-post post-grid byline-on content-grid', $post->ID ) );
						
					} elseif ( $post->post_type == 'etheme_portfolio' && function_exists('etheme_project_categories') ) {
						$args['post_class'] = implode( ' ', get_post_class( 'portfolio-item port-style-default', $post->ID ) );
						ob_start();
						etheme_project_categories( $post->ID );
						$args['categories'] = ob_get_clean();
					}
				}
				
				$suggestions[] = $args;
			}
		}
		
		$suggestions = $this->parse_suggestions( $products, $suggestions );
		
		if ( $cache && ! $this->get_cache() ) {
			$this->set_cache( $suggestions );
		}
		
		wp_send_json( array( 'suggestions' => $suggestions ) );
	}
	
	
	/**
	 * Parse suggestions
	 *
	 * Parse products suggestions to prevent it duplication.
	 *
	 * @param   {array} $results     Search results.
	 * @param   {array} $suggestions Unparsed suggestions.
	 *
	 * @return  {array}              Unique(parsed) suggestions.
	 * @version 1.0.0
	 * @since   1.4.3
	 */
	public function parse_suggestions( $results = array(), $suggestions = array() ) {
		$results         = array_map( function ( $n ) {
			return $n ? true : false;
		}, $results );
		$needs_filtering = count( array_filter( $results ) ) > 1;
		
		if ( $needs_filtering ) {
			$suggestions = array_map( 'unserialize', array_unique( array_map( 'serialize', $suggestions ) ) );
		}
		
		return $suggestions;
	}
	
	
	public function get_cache() {
		return get_transient( 'et_ajax_search_cache' );
	}
	
	public function set_cache( $data ) {
		$cache = $this->get_cache();
		if ( is_array( $cache ) && count( $cache ) < 300 ) {
			$data = array_merge( $data, $cache );
		} else {
			$data = false;
		}
		set_transient( 'et_ajax_search_cache', $data, DAY_IN_SECONDS );
	}
	
	public function request_in_cache( $s = false ) {
		if ( $s ) {
			$cache       = $this->get_cache();
			$suggestions = array();
			if ( $cache ) {
				foreach ( $cache as $item ) {
					if ( strpos( $item['s'], $s ) !== false ) {
						$suggestions[] = $item;
					}
				}
			}
		}
		
		return false;
	}
}

$Etheme_ajax_search = new Etheme_ajax_search();
$Etheme_ajax_search->actions();