<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Swatches Filter Widget.
 * 
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
if( ! class_exists( 'WC_Widget' ) ) return;

class Swatches_Filter extends WC_Widget {

    public function __construct() {
        // ! Get the taxonomies
        $attribute_array      = array();
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ( ! empty( $attribute_taxonomies ) ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
            }
        }

        $this->widget_cssclass    = 'sidebar-widget etheme-swatches-filter';
        $this->widget_description = esc_html__( 'Widget to filtering products by swatches attributes', 'xstore-core' );
        $this->widget_id          = 'etheme_swatches_filter';
        $this->widget_name        = '8theme - ' . esc_html__( 'Swatches filter', 'xstore-core' );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => esc_html__( 'Filter by', 'xstore-core' ),
                'label' => esc_html__( 'Title', 'xstore-core' ),
            ),
            'attribute' => array(
                'type'    => 'select',
                'std'     => '',
                'label'   => esc_html__( 'Attribute', 'xstore-core' ),
                'options' => $attribute_array,
            ),
            'query_type' => array(
                'type'    => 'select',
                'std'     => '',
                'label'   => esc_html__( 'Query type', 'xstore-core' ),
                'options' => array(
                    'and' => 'AND',
                    'or'  => 'OR'
                ),
            ),
            'ajax' => array(
	            'type'  => 'checkbox',
	            'std'   => 0,
	            'label' => esc_html__( 'Use ajax preload for this widget', 'xstore-core' )
            ),
        );
        parent::__construct();
    }

    public function widget( $args, $instance ) {
	    if (parent::admin_widget_preview(esc_html__('Swatches filter', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;
	    if (is_admin()){
		    return;
	    }

	    $ajax               = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];

	    $unique = $instance["attribute"] . '-' . $instance["query_type"];

	    if (apply_filters('et_ajax_widgets', $ajax)){
		    $instance['selector'] = '.etheme_swatches_filter.' . $unique;
		    echo et_ajax_element_holder( 'Swatches_Filter', $instance, '', '', 'widget_filter', $args );
		    return;
	    }
	    
        if ( ! is_shop() && ! is_product_taxonomy() ) return;

        global $wpdb;
        // ! Set main variables
        $html               = '';
        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
        $taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
        $query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];
	    $orderby            = wc_attribute_orderby( $taxonomy );

        // ! Set get_terms args
        $terms = get_terms( $taxonomy );

        // ! Set class
        $class = '';
        $class .= 'st-swatch-size-large';

        // ! Get the taxonomies attribute 
	    $origin_attr = substr( $taxonomy, 3 );
	    $attr = get_query_var('et_swatch_tax-'.$origin_attr, false);
	    if ( !$attr ) {
		    $attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$origin_attr'" );
		    set_query_var('et_swatch_tax-'.$origin_attr, $attr);
	    }


        if ( ! $attr || ! $attr->attribute_type ) {
            return;
        }

        $attribute_type = $attr->attribute_type;
	    $subtype      = '';
	    $sw_shape = get_theme_mod('swatch_shape', 'default');
	    $sw_custom_shape = $sw_shape != 'default' ? $sw_shape : false;

        if ( strpos( $attribute_type, '-sq') !== false ) {
            $et_attribute_type = str_replace( '-sq', '', $attribute_type );
	        if ( !$sw_custom_shape || $sw_custom_shape == 'square' ) {
		        $class .= ' st-swatch-shape-square';
		        $subtype      = 'subtype-square';
	        }
	        else if ( $sw_custom_shape == 'circle' ) {
		        $class .= ' st-swatch-shape-circle';
	        }
        } else {
            $et_attribute_type = $attribute_type;
	        if ( !$sw_custom_shape || $sw_custom_shape == 'circle' ) {
		        $class .= ' st-swatch-shape-circle';
	        }
        }

        // ! Get current filter
        $filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', urldecode($taxonomy) ) );
        $current_filter = isset( $_GET[ urldecode($filter_name) ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ urldecode($filter_name) ] ) ) ) : array();

        if ( ! is_rtl() ) {
            $current_filter = array_map( 'sanitize_title', $current_filter );
        }

        if ( is_product_category() || is_tax( 'brand' ) || is_product_tag() || is_search() ) {
            $term_counts  = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );

            if ( ! count( $term_counts ) ) {
                return;
            }
        }

        foreach( $terms as $taxonomy ) {

            if ( is_product_category() || is_tax( 'brand' ) || is_product_tag() || is_search() ) {
                if ( ! array_key_exists( $taxonomy->term_id, $term_counts) ) {
                    continue;
                }
            }

            $all_filters = $current_filter;
            $metadata    = get_term_meta( $taxonomy->term_id, '', true );
            $link        = remove_query_arg( urldecode($filter_name), $this->get_current_page_url() );

            $data_tooltip = $taxonomy->name;
            $li_class  = '';

            // ! Generate link
            if ( ! in_array( urldecode($taxonomy->slug), $current_filter, true ) ) {
                $all_filters[] = urldecode($taxonomy->slug);
            } else {
                $key = array_search( urldecode($taxonomy->slug), $all_filters );
                unset( $all_filters[$key] );
                $li_class .= ' selected';
            }

            if ( ! empty( $all_filters ) ) {
                asort( $all_filters );
                $link = add_query_arg( $filter_name, implode( ',', $all_filters ), $link );

                if ( ! strpos($link, 'query_type_' . sanitize_title( str_replace( 'pa_', '', urldecode($taxonomy->taxonomy) ) )) && 'or' === $query_type && ! ( 1 === count( $all_filters ) ) ) {
                    $link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', urldecode($taxonomy->taxonomy) ) ), 'or', $link );
                }
                $link = str_replace( '%2C', ',', $link );
            }

            // ! Generate html
            switch ( $et_attribute_type ) {
                case 'st-color-swatch':
                    $value = ( isset( $metadata['st-color-swatch'] ) && isset( $metadata['st-color-swatch'][0] ) ) ? $metadata['st-color-swatch'][0] : '#fff';
                    $html .= '<li class="type-color ' . $subtype . $li_class . '"  data-tooltip="'.$data_tooltip.'">
                    <a href="' . $link . '">
	                    <span class="st-custom-attribute" style="background-color:' . $value . '">
	                        <span class="screen-reader-text hidden">'.$data_tooltip.'</span>
	                    </span>
                    </a></li>';
                    break;

                case 'st-image-swatch':
                    $value = $metadata['st-image-swatch'][0];
                    $image = ( $value ) ? wp_get_attachment_image( $value ) : wc_placeholder_img();
                    $html .= '<li class="type-image ' . $subtype . $li_class . '"  data-tooltip="'.$data_tooltip.'">
                    <a href="' . $link . '">
						<span class="st-custom-attribute">'
                             . $image .
                             '<span class="screen-reader-text hidden">'.$data_tooltip.'</span>' .
                         '</span>
					</a>
					</li>';
                    break;

                case 'st-label-swatch':
                    $value = ( isset( $metadata['st-label-swatch'] ) && $metadata['st-label-swatch'][0] ) ? $metadata['st-label-swatch'][0] : false;

                    if ( ! $value ) {
                        $value = $taxonomy->name;
                    }

                    $html .= '<li class="type-label ' . $subtype . $li_class . '"><a href="' . $link . '"><span class="st-custom-attribute">' . $value . '</span></a></li>';
                    break;
                
                default:
                    $html .= '<li class="type-select ' . $li_class . '"><a href="' . $link . '"><span class="st-custom-attribute">' . $taxonomy->name . '</span></a></li>';
                    break;
            }
        }

        if ( $html == '' ) return;

        echo '
            <div class="sidebar-widget etheme_swatches_filter '.$unique.'">
                ' . parent::etheme_widget_title($args, $instance) . '
                <ul class="st-swatch-preview st-color-swatch ' . esc_attr( $class ) . '">
                    ' . $html . '
                </ul>
            </div>
        ';
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
		$term_ids_sql   = '(' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		if (strlen($term_ids_sql) < 3) {
			return array();
		}

		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) AS term_count, terms.term_id AS term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']}
			AND terms.term_id IN $term_ids_sql";

		$search = \WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query_sql         = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query_sql );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$results                      = $wpdb->get_results( $query_sql, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
			}
		}
		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
    }
}