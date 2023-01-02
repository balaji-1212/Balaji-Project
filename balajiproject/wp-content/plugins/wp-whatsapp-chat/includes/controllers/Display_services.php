<?php

class QLWAPP_DisplayServices_Controller {

	public function is_show_view( $display ) {

		global $wp_query;
		$show = true;
		if ( is_customize_preview() ) {
			return true;
		}
		// general
		if ( is_front_page() || is_home() || is_search() || is_404() ) {

			if ( ( isset( $display['target']['ids'] ) ) ? count( $display['target']['ids'] ) : 0 ) {
				$show = ! (bool) $display['target']['include'];
				if ( is_front_page() && in_array( 'home', $display['target']['ids'] ) ) {
					$show = ! $show;
				} elseif ( is_home() && in_array( 'blog', $display['target']['ids'] ) ) {
					$show = ! $show;
				} elseif ( is_search() && in_array( 'search', $display['target']['ids'] ) ) {
					$show = ! $show;
				} elseif ( is_404() && in_array( 'error', $display['target']['ids'] ) ) {
					$show = ! $show;
				} elseif ( in_array( 'all', $display['target']['ids'] ) ) {
					$show = ! $show;
				}
			}

			return $show;
		}
		// shop fix
		if ( function_exists( 'is_shop' ) && is_shop() ) {
			if ( isset( $display['entries']['page']['ids'] ) && count( $display['entries']['page']['ids'] ) ) {
				$show = ! $display['entries']['page']['include'];
				if ( in_array( 'all', $display['entries']['page']['ids'] ) ) {
					return ! $show;
				}
				if ( in_array( get_option( 'woocommerce_shop_page_id' ), $display['entries']['page']['ids'] ) ) {
					$show = ! $show;
				}
			}
			return $show;
		}
		// post
		if ( ! is_front_page() && is_singular() && isset( $wp_query->get_queried_object()->post_type ) ) {

			if ( isset( $display['entries'][ $wp_query->get_queried_object()->post_type ]['ids'] ) && count( $display['entries'][ $wp_query->get_queried_object()->post_type ]['ids'] ) ) {
				$show = ! $display['entries'][ $wp_query->get_queried_object()->post_type ]['include'];
				if ( in_array( 'all', $display['entries'][ $wp_query->get_queried_object()->post_type ]['ids'] ) ) {
					return ! $show;
				}
				if ( in_array( $wp_query->get_queried_object()->ID, $display['entries'][ $wp_query->get_queried_object()->post_type ]['ids'] ) ) {
					$show = ! $show;
				}
				// backward compatibility for $post->post_name
				if ( in_array( $wp_query->get_queried_object()->post_name, $display['entries'][ $wp_query->get_queried_object()->post_type ]['ids'] ) ) {
					$show = ! $show;
				}
			}
			return $show;
		}
		// taxonomies
		if ( is_archive() && isset( $wp_query->get_queried_object()->taxonomy ) ) {

			if ( isset( $display['taxonomies'][ $wp_query->get_queried_object()->taxonomy ]['ids'] ) && count( $display['taxonomies'][ $wp_query->get_queried_object()->taxonomy ]['ids'] ) ) {

				$show = ! $display['taxonomies'][ $wp_query->get_queried_object()->taxonomy ]['include'];
				if ( in_array( 'all', $display['taxonomies'][ $wp_query->get_queried_object()->taxonomy ]['ids'] ) ) {
					return ! $show;
				}

				if ( in_array( $wp_query->get_queried_object()->term_id, $display['taxonomies'][ $wp_query->get_queried_object()->taxonomy ]['ids'] ) ) {
					$show = ! $show;
				}
				// backward compatibility for $term->name
				if ( in_array( $wp_query->get_queried_object()->slug, $display['taxonomies'][ $wp_query->get_queried_object()->taxonomy ]['ids'] ) ) {
					$show = ! $show;
				}
			}
			return $show;
		}

		return $show;
	}

}

// QLWAPP_DisplayServices_Controller::instance();
