<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Portfolio Post Type functions
// **********************************************************************//
if(!function_exists('etheme_project_categories')) {
	function etheme_project_categories($id) {
		if ( etheme_xstore_plugin_notice() ) {
			return;
		}
		$term_list = wp_get_post_terms($id, 'portfolio_category');
		$_i = 0;
		foreach ($term_list as $value) { 
			$_i++;
			echo '<a href="'.get_term_link($value, 'portfolio_category').'">';
				echo esc_html($value->name);
			echo '</a>';
			if($_i != count($term_list)) 
				echo ', ';
		}
	}
}

if(!function_exists('etheme_portfolio')) {
	function etheme_portfolio($categories = false, $limit = false, $show_pagination = true, $columns = false, $show_filters = true, $custom_class = '', $all = true, $is_category = false, $cat_name = '', $url = '', $paged = 1 ) {
			if ( etheme_xstore_plugin_notice() ) {
				return;
			}
		
		    wp_enqueue_script( 'et_isotope' );
            wp_enqueue_script( 'portfolio' );
		    wp_enqueue_style( 'portfolio' );
		    
			global $et_portfolio_loop;
			$et_portfolio_loop['one_project'] = false;

			$paged = ( isset( $_GET['et-paged'] ) && ! empty( $_GET['et-paged'] ) ) ? $_GET['et-paged'] : $paged;
			$url = ( $url != '' ) ? $url : get_permalink();
			$cat = get_query_var('portfolio_category');
			$class = ( $custom_class != '' ) ? $custom_class : 'portfolio-'.rand(100, 9999);
			$pagination_args = array();

			if ( !$columns ) 
				$columns = ( isset( $et_portfolio_loop['columns'] ) && $et_portfolio_loop['columns'] ) ? $et_portfolio_loop['columns'] : etheme_get_option('portfolio_columns', 3);
			else 
				$et_portfolio_loop['columns'] = $columns;

			$filters_type = etheme_get_option( 'portfolio_filters_type', 'all' );

			$category_page = ( get_query_var('portfolio_category') ) ? true : $is_category;
			$category_page_name = ( get_query_var('portfolio_category') && $cat_name == '' ) ? get_query_var('portfolio_category') : $cat_name;

			$_categories = $categories;

			if ( $is_category ) {
				if ( $all ) {
					$cat = $cat_name;
				}
				else {
					$cat = get_term( (int)$categories[0], 'portfolio_category' );
					$cat = $cat->slug;
				}
			}

			$tax_query = array();

			if(!$limit) {
				$limit = etheme_get_option('portfolio_count', '');
			}

			$order = etheme_get_option( 'portfolio_order', 'DESC' );
			$orderby = etheme_get_option( 'portfolio_orderby', 'title' );
			$spacing = etheme_get_option( 'portfolio_margin', 15 );

			if ( is_array($categories) && !empty($categories) ) {
				if ( $cat_name != null && $cat_name != '' ) {
					$categories[] = $cat_name;
				}
			}
			elseif ( $cat_name != null && $cat_name != '' ) {
				$categories = array($cat_name);
			}

			if ( $is_category ) {
				$tax_query = array(
					array(
						'taxonomy' => 'portfolio_category',
						'field' => 'slug',
						'terms' => $cat
					)
				);
			}
			else {
				if( is_array($categories) && !empty($categories)) {
					$tax_query = array(
						array(
							'taxonomy' => 'portfolio_category',
							'field' => 'term_id',
							'terms' => $categories,
							'operator' => 'IN'
						)
					);
				} else if(!empty($cat)) {
					$tax_query = array(
						array(
							'taxonomy' => 'portfolio_category',
							'field' => 'slug',
							'terms' => $cat
						)
					);
				}
			}

			$args = array(
				'post_type' => 'etheme_portfolio',
				'paged' => $paged,	
				'posts_per_page' => $limit,
				'tax_query' => $tax_query,
				'order' => $order,
				'orderby' => $orderby,
			);

			$loop = new WP_Query($args);

			if ( $loop->post_count == 1) $et_portfolio_loop['one_project'] = true;
			
			if ( $loop->have_posts() ) : ?>
				<?php if( $show_filters ) : 
					if ( get_query_var('portfolio_category') ) {
						$queried_object = get_queried_object();
						$term_id = $queried_object->term_id;
						$_terms = array();
						$_categories = get_term_children( $term_id, 'portfolio_category' );
						foreach ($_categories as $key) {
							$_terms[] = get_term( $key, 'portfolio_category' );
						}
						$_categories = $_terms;
					}
					else {

						
						$_args = array(
							'include' => $_categories,
						);

						if ( $filters_type == 'parent' ) {
							$_args['parent'] = false;
						} elseif( $filters_type == 'child' ){
							$_args['childless'] = true;
						}

						$_categories = get_terms( 'portfolio_category', $_args );
					} 
					if ( count($_categories) > 0) :
						wp_enqueue_style( 'isotope-filters' ); ?>
						<ul class="portfolio-filters et-masonry-filters-list">
							<li><a href="#" data-category-id="all" data-filter="*" class="btn-filter <?php echo ( !isset($_GET['et-cat']) ) ? 'active' : ''; ?>"><?php echo ( get_query_var('portfolio_category') ) ? $category_page_name : esc_html__('Show All', 'xstore'); ?></a></li>
								<?php
								foreach($_categories as $category) {
									?>
										<li><a href="#" data-category-id="<?php echo esc_attr($category->term_id); ?>" data-filter=".portfolio_category-<?php echo esc_attr($category->slug); ?>" class="filter-btn <?php echo ( isset($_GET['et-cat']) && $_GET['et-cat'] == $category->term_id ) ? 'active' : ''; ?>"><?php echo esc_html($category->name); ?></a></li>
									<?php
								}

								?>
						</ul>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<div class="portfolio-wrapper clearfix spacing-<?php echo esc_attr( $spacing ); ?> <?php echo esc_attr( $class ); ?>">
			<?php if ( $loop->have_posts() ) : ?>
					<div class="portfolio">
						<?php while ( $loop->have_posts() ) : $loop->the_post();
						
								get_template_part( 'content', 'portfolio' );
							
                            endwhile; ?>
					</div>

				<?php if ( $show_pagination && $limit != -1 ){
					$pagination_args = array(
						'pages'  => $loop->max_num_pages,
						'paged'  => $paged,
						'class'  => 'portfolio-pagination',
						'type' => 'custom',
						'url' => $url
					);
					etheme_pagination( $pagination_args );
				} ?>
			
		<?php else: ?>

			<h3><?php esc_html_e('No projects were found!', 'xstore') ?></h3>

		<?php endif; ?>
			<div
				class="et-load-portfolio"
				data-class="<?php echo esc_attr( $class ); ?>"
				data-portfolio-category-page="<?php echo esc_attr( $category_page ); ?>"
				data-portfolio-category-page-name="<?php echo esc_attr( $category_page_name ); ?>"
				data-limit="<?php echo esc_attr( $limit ); ?>"
				data-columns="<?php echo esc_attr( $columns ); ?>">
				<span class="hidden et-element-args" type="text/template" data-element="et_portfolio">
                    <?php echo json_encode( $pagination_args ); ?>  
                </span>
			</div>
		</div><?php // end .portfolio-wrapper
		wp_reset_postdata();
		wp_reset_query();  // Restore global post data stomped by the_post().
	}
}

add_action( 'wp_ajax_et_portfolio_ajax', 'etheme_portfolio_ajax');
add_action( 'wp_ajax_nopriv_et_portfolio_ajax', 'etheme_portfolio_ajax');

if(!function_exists('etheme_portfolio_ajax')) {
	function etheme_portfolio_ajax () {
		$categories = ( $_POST['category'] != 'all') ? array($_POST['category']) : false;
		$url = $_POST['url'];
		if ( $categories ) {
			if ( isset($_GET['et-cat']) ) {
				$url = add_query_arg('et-cat', $_GET['et-cat'], $url);
			}
			else {
				foreach ($categories as $key) {
					$url = add_query_arg('et-cat', $key, $url);
				}
			}
		}

		$is_cat = $_POST['is_category'] ? true : false;
		$cat_name = $_POST['category_name'];
		$limit = $_POST['limit'];
		$all = ( $_POST['category'] == 'all' ) ? true : false;
		$class = $_POST['class'];
		$columns = $_POST['columns'];
		$result = array();
		ob_start();
		etheme_portfolio($categories, $limit, true, $columns, false, $class, $all, $is_cat, $cat_name, $url);

		$result['html'] = ob_get_clean();

		echo json_encode($result);
        die();
	}
}

add_action( 'wp_ajax_et_portfolio_ajax_pagination', 'etheme_portfolio_ajax_pagination');
add_action( 'wp_ajax_nopriv_et_portfolio_ajax_pagination', 'etheme_portfolio_ajax_pagination');

function etheme_portfolio_ajax_pagination() {
	$url = $_POST['url'];
	$paged = $_POST['paged'];
	$limit = $_POST['limit'];
	$categories = ( $_POST['category'] != null) ? array($_POST['category']) : false;
	if ( $categories ) {
		if ( isset($_GET['et-cat']) ) {
			$url = add_query_arg('et-cat', $_GET['et-cat'], $url);
		}
		else {
			foreach ($categories as $key) {
				$url = add_query_arg('et-cat', $key, $url);
			}
		}
	}
	elseif ( isset($_GET['et-cat']) ) {
		$url = add_query_arg('et-cat', $_GET['et-cat'], $url);
	}
	$is_cat = $_POST['is_category'] ? true : false;
	$cat_name = $_POST['cat'];
	$all = ( (isset($_GET['et-cat']) && !$is_cat) || ($is_cat && $categories) ) ? false : true;
	$class = $_POST['class'];
	$columns = $_POST['columns'];
	ob_start();
	etheme_portfolio($categories, $limit, true, $columns, false, $class, $all, $is_cat, $cat_name, $url, $paged);
	$result = ob_get_clean();

	echo json_encode($result);
    die();
}
