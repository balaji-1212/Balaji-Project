<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Products Widget.
 * 
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
if( ! class_exists( 'WC_Widget' ) ) return;
class Products extends WC_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'etheme_widget_products sidebar-slider';
        $this->widget_description = esc_html__( 'Display a list of your products on your site.', 'xstore-core' );
        $this->widget_id          = 'etheme_widget_products';
        $this->widget_name        = '8theme - ' . esc_html__('Products Widget', 'xstore-core');
        $this->settings           = array(
            'title'  => array(
                'type'  => 'text',
                'std'   => esc_html__( 'Products', 'xstore-core' ),
                'label' => esc_html__( 'Title', 'xstore-core' )
            ),
            'number' => array(
                'type'  => 'number',
                'step'  => 1,
                'min'   => 1,
                'max'   => '',
                'std'   => 5,
                'label' => esc_html__( 'Number of products to show', 'xstore-core' )
            ),
            'show' => array(
                'type'    => 'select',
                'std'     => '',
                'label'   => esc_html__( 'Show', 'xstore-core' ),
                'options' => array(
                    ''         => esc_html__( 'All Products', 'xstore-core' ),
                    'featured' => esc_html__( 'Featured Products', 'xstore-core' ),
                    'onsale'   => esc_html__( 'On-sale Products', 'xstore-core' ),
                )
            ),
            'orderby' => array(
                'type'    => 'select',
                'std'     => 'date',
                'label'   => esc_html__( 'Order by', 'xstore-core' ),
                'options' => array(
                    'date'   => esc_html__( 'Date', 'xstore-core' ),
                    'price'  => esc_html__( 'Price', 'xstore-core' ),
                    'rand'   => esc_html__( 'Random', 'xstore-core' ),
                    'sales'  => esc_html__( 'Sales', 'xstore-core' ),
                )
            ),
            'order' => array(
                'type'     => 'select',
                'std'      => 'desc',
                'label'    => esc_html_x( 'Order', 'Sorting order', 'xstore-core' ),
                'options'  => array(
                    'asc'  => esc_html__( 'ASC', 'xstore-core' ),
                    'desc' => esc_html__( 'DESC', 'xstore-core' ),
                )
            ),
            'slider' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Slider widget', 'xstore-core' )
            ),
            'hide_free' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Hide free products', 'xstore-core' )
            ),
            'show_hidden' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Show hidden products', 'xstore-core' )
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
     * Query the products and return them.
     * @param  array $args
     * @param  array $instance
     * @return WP_Query
     */

    public function get_products( $args, $instance ) {
	    if (parent::admin_widget_preview(esc_html__('Products Widget', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;
        $number  = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : $this->settings['number']['std'];
        $show    = ! empty( $instance['show'] ) ? sanitize_title( $instance['show'] ) : $this->settings['show']['std'];
        $orderby = ! empty( $instance['orderby'] ) ? sanitize_title( $instance['orderby'] ) : $this->settings['orderby']['std'];
        $order   = ! empty( $instance['order'] ) ? sanitize_title( $instance['order'] ) : $this->settings['order']['std'];

        $query_args = array(
            'posts_per_page' => $number,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => 1,
            'order'          => $order,
            'meta_query'     => array()
        );

        if ( empty( $instance['show_hidden'] ) ) {
            $query_args['meta_query'][] = WC()->query->visibility_meta_query();
            $query_args['post_parent']  = 0;
        }

        if ( ! empty( $instance['hide_free'] ) ) {
            $query_args['meta_query'][] = array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'DECIMAL',
            );
        }

        $query_args['meta_query'][] = WC()->query->stock_status_meta_query();
        $query_args['meta_query']   = array_filter( $query_args['meta_query'] );

        switch ( $show ) {
            case 'featured' :
                $query_args['tax_query'][] = array(
                  'taxonomy' => 'product_visibility',
                  'field'    => 'name',
                  'terms'    => 'featured',
                  'operator' => 'IN',
                );
                break;
            case 'onsale' :
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                $product_ids_on_sale[]  = 0;
                $query_args['post__in'] = $product_ids_on_sale;
                break;
        }

        switch ( $orderby ) {
            case 'price' :
                $query_args['meta_key'] = '_price';
                $query_args['orderby']  = 'meta_value_num';
                break;
            case 'rand' :
                $query_args['orderby']  = 'rand';
                break;
            case 'sales' :
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby']  = 'meta_value_num';
                break;
            default :
                $query_args['orderby']  = 'date';
        }
	
	    // WCMp vendor plugin compatibility
	    if ( function_exists('get_wcmp_vendor_settings') && get_transient('wcmp_spmv_exclude_products_data')) {
		    $spmv_excludes = get_transient('wcmp_spmv_exclude_products_data');
		    $excluded_order = (get_wcmp_vendor_settings('singleproductmultiseller_show_order', 'general')) ? get_wcmp_vendor_settings('singleproductmultiseller_show_order', 'general') : 'min-price';
		    $post__not_in = ( isset( $spmv_excludes[$excluded_order] ) ) ? $spmv_excludes[$excluded_order] : array();
		    $query_args['post__not_in'] = (isset($query_args['post__not_in'])) ? array_merge($query_args['post__not_in'], $post__not_in) : $post__not_in;
	    }

        return new \WP_Query( apply_filters( 'woocommerce_products_widget_query_args', $query_args ) );
    }

    /**
     * Output widget.
     *
     * @see WP_Widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        if ( $this->get_cached_widget( $args ) ) {
            return;
        }

	    $ajax     = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];
        $number = isset( $instance['number'] ) ? $instance['number'] : $this->settings['number']['std'];
	    $slider = isset( $instance['slider'] ) ? $instance['slider'] : $this->settings['slider']['std'];
	    
	    if (apply_filters('et_ajax_widgets', $ajax)){
		    echo et_ajax_element_holder( 'Products', $instance, 'slider', '', 'widget', $args );
		    return;
	    }

        ob_start();

        if ( ( $products = $this->get_products( $args, $instance ) ) && $products->have_posts() ) {
	
	        $slider = apply_filters('etheme_widget_slider', $slider);
	        
        	if( !$slider && isset($args['before']) ) {
		        $args['before'] = str_replace( 'sidebar-slider', '', $args['before'] );
	        }
        	
            $this->widget_start( $args, $instance );

            $id = rand(100,999);

            $tag = 'ul';

            if( $slider ) {
	            $tag = 'div';
            	echo '<div class="swiper-entry">';
                    echo '<div class="swiper-container products-widget-slider slider-' . $id . '" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1" data-md-slides="1" data-lt-slides="1" data-slides-per-view="1" data-slides-per-group="1" data-slidespercolumn="3">';
            }

            echo apply_filters( 'woocommerce_before_widget_product_list', '<'.$tag.' class="'.(($slider) ? 'swiper-wrapper ' : '' ). 'product_list_widget">' );

            $_i = 0;



            $slide_count = $number;
            while ( $products->have_posts() ) {
                $products->the_post();
                if( $slider ) {
                    wc_get_template( 'content-widget-product-slider.php', array( 'show_rating' => false, 'slider' => true) );
                } else {
                    wc_get_template( 'content-widget-product.php', array( 'show_rating' => false, 'slider' => true ) );
                }
                $_i++;
                if( $_i > 1 && $products->post_count != $_i && $_i%$slide_count == 0 ) {
                    echo apply_filters( 'woocommerce_after_widget_product_list', '</'. $tag .'>' );
                    echo apply_filters( 'woocommerce_before_widget_product_list', '<'. $tag .' class="product_list_widget">' );
                }
            }

            echo apply_filters( 'woocommerce_after_widget_product_list', '</'. $tag .'>' );

            if( $slider ) {
                echo '</div>';
	            echo '<div class="swiper-custom-left swiper-nav"></div>';
                echo '<div class="swiper-custom-right swiper-nav"></div>';
            echo '</div>';
            }


            $this->widget_end( $args );
        }

        wp_reset_postdata();

        echo $this->cache_widget( $args, ob_get_clean() );
    }
}