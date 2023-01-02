<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Brands filter.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
if( ! class_exists( 'WC_Widget' ) ) return;
class Brands_Filter extends WC_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'sidebar-widget etheme-brands-filter';
        $this->widget_description = esc_html__( 'Widget to filtering products by brands', 'xstore-core' );
        $this->widget_id          = 'etheme_brands_filter';
        $this->widget_name        = '8theme - ' . esc_html__( 'Filter Products by Brands', 'xstore-core' );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => esc_html__( 'Filter by brand', 'xstore-core' ),
                'label' => esc_html__( 'Title', 'xstore-core' ),
            ),
            'display_type' => array(
                'type'    => 'select',
                'std'     => 'list',
                'label'   => esc_html__( 'Display type', 'xstore-core' ),
                'options' => array(
                    'list'     => esc_html__( 'List', 'xstore-core' ),
                    'dropdown' => esc_html__( 'Dropdown', 'xstore-core' ),
                ),
            ),
            'count' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Show product counts', 'xstore-core' )
            ),
            'ajax' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Use ajax preload for this widget', 'xstore-core' )
            )
        );

        parent::__construct();
    }

    /**
     * Output widget.
     *
     * @see WP_Widget
     *
     * @param array $args Arguments.
     * @param array $instance Instance.
     */
    public function widget( $args, $instance ) {
	    if ( xstore_notice() ) return;

        if ( ! is_shop() && ! is_product_taxonomy() ) {
            return;
        }

        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
        $count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
        $taxonomy           = 'brand';
        $query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
        $display_type       = isset( $instance['display_type'] ) ? $instance['display_type'] : $this->settings['display_type']['std'];
        $ajax               = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];

	    if (apply_filters('et_ajax_widgets', $ajax)){
            $instance['selector'] = '.etheme_widget_brands_filter';
            echo et_ajax_element_holder( 'Brands_Filter', $instance, '', '', 'widget_filter', $args );
            return;
        }

        $hide_empty = get_option( 'woocommerce_hide_out_of_stock_items' ) === 'yes';

        $terms = get_terms(
            array(
                'taxonomy' => 'brand',
                'hide_empty' => $hide_empty,
                'operator'         => 'IN',
                'include_children' => false,
            )
        );

        if ( is_wp_error( $terms ) || 0 === count( $terms ) ) {
            return;
        }


        $class = '';
        $shop_url = '';

        if ( is_tax( 'brand' ) ) {
            $class = 'on_brand';
            $shop_url = 'data-shop-url="' . get_permalink( wc_get_page_id( 'shop' ) ) . '"';
        }

        $items = '';

        if( count( $terms ) > 0 ) {
            foreach ( $terms as $brand ) {

                $class = 'cat-item';
                $stock = $brand->count;

                $term_counts  = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );

                if ( ! array_key_exists( $brand->term_id, $term_counts) ) {
                    continue;
                }

                if ( $hide_empty && is_tax( 'product_cat' ) ) {
                    global $wp_query;
                    $cat    = $wp_query->get_queried_object();
                    $stock  = parent::etheme_stock_taxonomy( $brand->term_id, 'brand', $cat->slug );
                } elseif( $hide_empty ) {
                    $stock  = parent::etheme_stock_taxonomy( $brand->term_id, 'brand' );
                } elseif( is_tax( 'product_cat' ) ) {
                    global $wp_query;
                    $cat    = $wp_query->get_queried_object();
                    $stock  = parent::etheme_stock_taxonomy( $brand->term_id, 'brand', $cat->slug, false );
                }

                if (!$stock || $stock ==''){
                    continue;
                }

                $thumbnail_id = absint(get_term_meta($brand->term_id, 'thumbnail_id', true));

                $link = remove_query_arg( 'filter_brand', $this->get_current_page_url() );


                $current_filter = isset( $_GET['filter_brand'] ) ? explode( ',', wc_clean( wp_unslash( $_GET['filter_brand'] ) ) ) : array();
                $current_filter = array_map( 'sanitize_title', $current_filter );

                $all_filters = $current_filter;

                if ( ! in_array( $brand->slug, $current_filter, true ) ) {
                    $all_filters[] = $brand->slug;
                } else {
                    $key = array_search( $brand->slug, $all_filters );
                    unset( $all_filters[$key] );
                    $class .= ' current-item';
                }

                if ( ! empty($all_filters) ) {
                    $link = add_query_arg( 'filter_brand', implode( ',', $all_filters ), $link );
                }

                // Render widget items
                if ( $display_type == 'dropdown' ) {
                    $link = remove_query_arg( 'filter_brand', $this->get_current_page_url() );
                    $link = add_query_arg( 'filter_brand', $brand->slug, $link );

                    $selected = ( is_tax( 'brand' , $brand->term_id ) ) ? ' selected' : '' ;
                    $items .= '<option class="level-0" value="' . esc_html( $brand->name ) . '" data-url="' . $link . '"' . $selected . '>' . esc_html( $brand->name .( $count == 1 ? ' '. $stock : '' ) ) . '</option>';
                } else {
                    $items .= '<li class="' . $class . '">';
                    $items .= '<a href="' . $link . '">';
                    $items .= esc_html( $brand->name );
                    if ( $count == 1 ) {
                        $items .= apply_filters( 'etheme_brands_widget_count', '<span class="count">(' . esc_html( $stock ) . ')</span>', $stock, $brand );
                    }
                    $items .= '</a>';
                    $items .= '</li>';
                }
            }
        }

        // Render widget
        $out = '';
        $out .= '<div class="sidebar-widget etheme_widget_brands_filter etheme_widget_brands ' . $class . '" ' . $shop_url . '>';
        $out .= parent::etheme_widget_title($args, $instance);
        if ( $display_type == 'dropdown' ) {
            $out .= '<select name="product_brand" class="dropdown_product_brand">';
            $out .= '<option value="" selected="selected" data-url="">'.esc_html__('Select a brand', 'xstore-core').'</option>';
            $out .= $items;
            $out .= '</select>';
            if (! etheme_get_option( 'ajax_product_filter', 0 )){
                wc_enqueue_js( "
	                        jQuery( '.dropdown_product_brand' ).change( function() {
	                            var url = jQuery(this).find( 'option:selected' ).data( 'url' );
	                            if ( url != '' ) location.href = url;
	                        });
	                    " );
            }
        } else {

            $out .= '<ul>';

            $out .= $items;

            $out .= '</ul>';
        }
        $out .= '</div>';

        echo $out;
    }
    protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
        global $wpdb;
        $tax_query  = \WC_Query::get_main_tax_query();
        $meta_query = \WC_Query::get_main_meta_query();
        if ( 'or' === $query_type ) {
            foreach ( $tax_query as $key => $query ) {
                if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
                    unset( $tax_query[ $key ] );
                }
            }
        }
        $meta_query     = new \WP_Meta_Query( $meta_query );
        $tax_query      = new \WP_Tax_Query( $tax_query );
        $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
        // Generate query.
        $query           = array();
        $query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
        $query['from']   = "FROM {$wpdb->posts}";
        $query['join']   = "
            INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
            INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
            INNER JOIN {$wpdb->terms} AS terms USING( term_id )
            " . $tax_query_sql['join'] . $meta_query_sql['join'];
        $query['where'] = "
            WHERE {$wpdb->posts}.post_type IN ( 'product' )
            AND {$wpdb->posts}.post_status = 'publish'"
            . $tax_query_sql['where'] . $meta_query_sql['where'] .
            'AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';
        $search = \WC_Query::get_main_search_query_sql();
        if ( $search ) {
            $query['where'] .= ' AND ' . $search;
        }
        $query['group_by'] = 'GROUP BY terms.term_id';
        $query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
        $query             = implode( ' ', $query );
        // We have a query - let's see if cached results of this query already exist.
        $query_hash    = md5( $query );
        // Maybe store a transient of the count values.
        $cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
        $cache = false;
        if ( true === $cache ) {
            $cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
        } else {
            $cached_counts = array();
        }
        if ( ! isset( $cached_counts[ $query_hash ] ) ) {
            $results                      = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
            $counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
            $cached_counts[ $query_hash ] = $counts;
            if ( true === $cache ) {
                set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
            }
        }
        return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
    }
}
