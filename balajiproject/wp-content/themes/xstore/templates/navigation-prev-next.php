<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying post/project/product prev-next navigation
 *
 * @since   8.0.3
 * @version 1.0.1
 */

global $post;
$is_product = false;

if ( $post->post_type == 'product' ) {
	$is_product = true;

	$next_post = get_adjacent_post( 1, '', 0, 'product_cat' );
	$prev_post = get_adjacent_post( 1, '', 1, 'product_cat' );

	if ( ! empty( $next_post ) ) {
		$next_post = et_visible_product( $next_post->ID, 'next' );
	}

	if ( ! empty( $prev_post ) ) {
		$prev_post = et_visible_product( $prev_post->ID, 'prev' );
	}

	if (is_null($prev_post)){
		$prev_post_id = '';
	} else {
		if ( empty($prev_post) && !is_object(get_previous_post())) {
			$prev_post_id = '';
		}
		else {
			$prev_post_id = empty( $prev_post ) ? get_previous_post()->ID : $prev_post->ID;
		}
	}
	if (is_null($next_post)){
		$next_post_id = '';
	} else {
		if ( empty($next_post) && !is_object(get_next_post())) {
			$next_post_id = '';
		}
		else {
			$next_post_id = empty($next_post) ? get_next_post()->ID : $next_post->ID;
		}
	}

	if ( empty($next_post) || empty($prev_post)) {
		$post_id        = $post->ID; // current post ID
		$product = wc_get_product($post_id);
		$args = array(
			'post_type'             => 'product',
			'post_status'           => 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page'        => '12',
			'order' => 'ASC',
			'orderby'    => 'date',
			'tax_query'             => array(
				array(
					'taxonomy'      => 'product_cat',
					'field' => 'term_id', //This is optional, as it defaults to 'term_id'
					'terms'         => $product->get_category_ids(),
					'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
				),
				array(
					'taxonomy'      => 'product_visibility',
					'field'         => 'slug',
					'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
					'operator'      => 'NOT IN'
				),
			)
		);
		$products = new WP_Query($args);

		// get IDs of posts retrieved from get_posts
		$ids = array();
		while ( $products->have_posts() ) : $products->the_post(); global $product;
			$ids[] = $product->get_ID();
		endwhile;

		// get and echo previous and next post in the same category
//				$index    = array_search( $post_id, $ids );

		if ( ( count($ids) - 1 ) > 2 ) {
			if ( empty($prev_post) ) {
				// $prev_post = $prev_post_id = isset( $ids[ $index - 1 ] ) ? $ids[ $index - 1 ] : 0;
				$prev_post = $prev_post_id = end($ids);

				if ( !empty($prev_post)) {
					$prev_post    = et_visible_product( $prev_post, 'prev' );
					$prev_post_id = is_object($prev_post) ? $prev_post->ID : '';
				}
			}
			if ( empty($next_post)) {
				//$next_post = $next_post_id = isset( $ids[ $index + 1 ] ) ? $ids[ $index + 1 ] : 0;
				$next_post = $next_post_id = $ids[0];
				if ( !empty($next_post)) {
					$next_post    = et_visible_product( $next_post, 'next' );
					$next_post_id = is_object($next_post) ? $next_post->ID : '';
				}
			}
		}
	}

} else {
	$next_post = get_next_post();
	$prev_post = get_previous_post();

	if ($next_post){
		$next_post_id = $next_post->ID;
	}

	if ($prev_post){
		$prev_post_id = $prev_post->ID;
	}
}

if (class_exists('YITH_WCBM_Frontend')) {
	remove_filter( 'post_thumbnail_html', array( YITH_WCBM_Frontend(), 'show_badge_on_product' ), 999 );
}

?>
    <div class="posts-navigation hidden">
		<?php if(!empty($prev_post)) :
			if ( function_exists('mb_strlen') ) {
				$prev_symbols = (mb_strlen(get_the_title($prev_post_id)) > 30) ? '...' : '';
				$title = mb_substr(get_the_title($prev_post_id),0,30) . $prev_symbols;
			}
			else {
				$prev_symbols = (strlen(get_the_title($prev_post_id)) > 30) ? '...' : '';
				$title = substr(get_the_title($prev_post_id),0,30) . $prev_symbols;
			}
			?>
            <div class="posts-nav-btn prev-post">
                <div class="post-info">
                    <div class="post-details">
                        <a href="<?php echo get_permalink($prev_post_id); ?>" class="post-title">
							<?php echo apply_filters('etheme_prev_next_title', esc_attr( $title ), get_the_title($prev_post_id)); ?>
                        </a>
						<?php if ( $is_product ) {
							$p = wc_get_product($prev_post);
							echo '<p class="price">'.$p->get_price_html().'</p>';
						} ?>
                    </div>
                    <a href="<?php echo get_permalink($prev_post_id); ?>">
						<?php $img = get_the_post_thumbnail( $prev_post_id, array(90, 90));
						echo (!empty($img) ) ? $img : '<img src="'.ETHEME_BASE_URI.'images/placeholder.jpg">';  ?>
                    </a>
                </div>
                <span class="post-nav-arrow">
                        <i class="et-icon et-<?php echo get_query_var('et_is-rtl', false) ? 'right' : 'left'; ?>-arrow"></i>
                    </span>
            </div>
		<?php endif; ?>

		<?php if(!empty($next_post)) :
			if ( function_exists('mb_strlen') ) {
				$next_symbols = (mb_strlen(get_the_title($next_post_id)) > 30) ? '...' : '';
				$title = mb_substr(get_the_title($next_post_id),0,30) . $next_symbols;
			}
			else {
				$next_symbols = (strlen(get_the_title($next_post_id)) > 30) ? '...' : '';
				$title = substr(get_the_title($next_post_id),0,30) . $next_symbols;
			} ?>
            <div class="posts-nav-btn next-post">
					<span class="post-nav-arrow">
                        <i class="et-icon et-<?php echo get_query_var('et_is-rtl', false) ? 'left' : 'right'; ?>-arrow"></i>
                    </span>
                <div class="post-info">
                    <a href="<?php echo get_permalink($next_post_id); ?>">
						<?php $img = get_the_post_thumbnail( $next_post_id, array(90, 90));
						echo (!empty($img) ) ? $img : '<img src="'.ETHEME_BASE_URI.'images/placeholder.jpg">';  ?>
                    </a>
                    <div class="post-details">
                        <a href="<?php echo get_permalink($next_post_id); ?>" class="post-title">
							<?php echo apply_filters('etheme_prev_next_title', esc_attr( $title ), get_the_title($next_post_id)); ?>
                        </a>
						<?php if ( $is_product ) {
							$p = wc_get_product($next_post);
							echo '<p class="price">'.$p->get_price_html().'</p>';
						} ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php
if (class_exists('YITH_WCBM_Frontend')) {
	remove_filter( 'post_thumbnail_html', array( YITH_WCBM_Frontend(), 'show_badge_on_product' ), 999 );
}
?>
<?php wp_reset_query();