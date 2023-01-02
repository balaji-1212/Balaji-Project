<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Layered Navigation Filters Widget..
 *
 * @since      1.4.4
 * @version    1.0.2
 * @package    ETC
 * @subpackage ETC/Models/Admin
 * @log 1.0.1
 * @ADDED filter_stock
 * @ADDED filter_sale
 */
class Layered_Nav_Filters extends WC_Widget {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'sidebar-widget etheme-active-filters widget_layered_nav_filters';
		$this->widget_description = esc_html__( 'Display a list of active product filters.', 'xstore-core' );
		$this->widget_id          = 'et_layered_nav_filters';
		$this->widget_name        = '8theme - ' . esc_html__( 'Active Product Filters', 'xstore-core' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Active filters', 'xstore-core' ),
				'label' => esc_html__( 'Title', 'xstore-core' ),
			),
			'clear' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => esc_html__( 'Clear all filters button', 'xstore-core' ),
			),
			'ajax' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Use ajax preload for this widget', 'xstore-core' )
			),
		);
		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( xstore_notice() ) return;

		$ajax               = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];
		$clear = isset( $instance['clear'] ) ? $instance['clear'] : $this->settings['clear']['std'];

		if (apply_filters('et_ajax_widgets', $ajax)){
			$instance['selector'] = '.etheme-active-filters';
			echo et_ajax_element_holder( 'Layered_Nav_Filters', $instance, '', '', 'widget_filter', $args );
			return;
		}
		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		$filter_brand = isset( $_GET['filter_brand'] ) ? $_GET['filter_brand'] : '' ;
		$filter_stock = isset( $_GET['stock_status'] ) ? $_GET['stock_status'] : '' ;
		$filter_sale  = isset( $_GET['sale_status'] ) ? $_GET['sale_status'] : '' ;
		$_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
		$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
		$base_link          = $this->get_current_page_url();
		$strings = array(
			'remove_filter' => esc_attr__( 'Remove filter', 'xstore-core' ),
			'in_stock' => esc_html__( 'In stock', 'xstore-core' ),
			'out_of_stock' => esc_html__( 'Out of stock', 'xstore-core' )
		);

		$links = array();

		// ! Fix for brands
		if ( $filter_brand ) {
			$base_link = add_query_arg( 'filter_brand', $filter_brand, $base_link );
		}

		if (
			0 < count( $_chosen_attributes )
			|| 0 < $min_price
			|| 0 < $max_price
			|| ! empty( $rating_filter )
			|| ! empty( $filter_brand )
			|| ! empty( $filter_stock )
			|| ! empty( $filter_sale )
		) {
			$this->widget_start( $args, $instance );
			echo '<ul>';
			// Attributes.
			if ( ! empty( $_chosen_attributes ) ) {
				foreach ( $_chosen_attributes as $taxonomy => $data ) {
					foreach ( $data['terms'] as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, $taxonomy );
						if ( ! $term ) {
							continue;
						}
						$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
						$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						$current_filter = array_map( 'sanitize_title', $current_filter );
						$new_filter     = array_diff( $current_filter, array( $term_slug ) );
						$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );
						if ( count( $new_filter ) > 0 ) {
							$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
						}

						if (in_array($link, $links)) {
							continue;
						}

						$links[] = $link;
						echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a></li>';
					}
				}
			}
			if ( $min_price ) {
				$link = remove_query_arg( 'min_price', $base_link );
				/* translators: %s: minimum price */
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Min %s', 'xstore-core' ), wc_price( $min_price ) ) . '</a></li>'; // WPCS: XSS ok.
			}
			if ( $max_price ) {
				$link = remove_query_arg( 'max_price', $base_link );
				/* translators: %s: maximum price */
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Max %s', 'xstore-core' ), wc_price( $max_price ) ) . '</a></li>'; // WPCS: XSS ok.
			}
			if ( ! empty( $rating_filter ) ) {
				foreach ( $rating_filter as $rating ) {
					$link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
					$link         = $link_ratings ? add_query_arg( 'rating_filter', $link_ratings ) : remove_query_arg( 'rating_filter', $base_link );
					/* translators: %s: rating */
					echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Rated %s out of 5', 'xstore-core' ), esc_html( $rating ) ) . '</a></li>';
				}
			}

			if ($filter_stock){
				switch ($filter_stock) {
					case 'in_stock':
						$link = remove_query_arg( 'stock_status', $base_link );
						echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . $strings['in_stock'] . '</a></li>';

						break;
					case 'out_of_stock':
						$link = remove_query_arg( 'stock_status', $base_link );
						echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . $strings['out_of_stock'] . '</a></li>';
						break;
					case 'out_of_stock,in_stock':
						$out_of_stock = add_query_arg( 'stock_status', 'in_stock', $base_link );
						$in_stock = add_query_arg( 'stock_status', 'out_of_stock', $base_link );

						echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $in_stock ) . '">' . $strings['in_stock'] . '</a></li>';
						echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $out_of_stock ) . '">' . $strings['out_of_stock'] . '</a></li>';

						break;
				}
			}
			if ( $filter_sale ) {
				$link = remove_query_arg( 'sale_status', $base_link );
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . esc_html__( 'On sale', 'xstore-core' ) . '</a></li>';
			}

			// ! Use for brand
			if ( $filter_brand ) {
				$barnds = explode(',', $filter_brand);
				$link = remove_query_arg( 'filter_brand', $base_link );

				foreach ( $barnds as $key => $value ) {
					$all = $barnds;
					$bk  = array_search($value, $barnds);

					unset($all[$bk]);

					if ( $all ) {
						$link = add_query_arg( 'filter_brand', implode( ',', $all ), $link );
					}

					if (in_array($link, $links)) {
						continue;
					}
					$links[] = $link;

					$term = get_term_by( 'slug', $value, 'brand' );

					/* translators: %s: maximum price */
					echo '<li class="chosen"><a class="remove-brand" rel="nofollow" aria-label="' . $strings['remove_filter'] . '" href="' . esc_url( $link ) . '">' . $term->name . '</a></li>'; // WPCS: XSS ok.
				}

			}

			echo '</ul>';
			if ($clear){
				$obj_id = get_queried_object_id();
				$url = get_term_link( $obj_id );
				if ( is_wp_error($url)){
					$url = get_permalink( wc_get_page_id( 'shop' ) );
				}

				if (!$url){
					$url = get_home_url();
				}
				echo '<a class="etheme-ajax-filter etheme-clear-all-filters btn btn-black button" href="' . $url . '" style="height: auto;"><span class="et-icon et_b-icon et-clear-filter" style="transform: scale(1.2);"></span><span>' . esc_html__('Clear All Filters', 'xstore-core') . '</span></a>';
			}
			$this->widget_end( $args );
		}
	}
}