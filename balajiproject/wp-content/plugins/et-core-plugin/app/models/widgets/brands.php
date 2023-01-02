<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Brands widget.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
if( ! class_exists( 'WC_Widget' ) ) return;
class Brands extends WC_Widget {
    /**
     * Current Brand.
     *
     * @var bool
     */
     public $current_cat;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'sidebar-widget etheme_widget_brands';
        $this->widget_description = esc_html__( 'A list or dropdown of product brands.', 'xstore-core' );
        $this->widget_id          = 'etheme_widget_brands';
        $this->widget_name        = '8theme - ' . __( 'Brand List', 'xstore-core' );
        $this->settings           = array(
            'title'  => array(
                'type'  => 'text',
                'std'   => esc_html__( 'Brands list', 'xstore-core' ),
                'label' => esc_html__( 'Title', 'xstore-core' )
            ),
            'displayType' => array(
                'type'    => 'select',
                'std'     => 'name',
                'label'   => esc_html__( 'Display type:', 'xstore-core' ),
                'options' => array(
                    'name'   => esc_html__( 'Name', 'xstore-core' ),
                    'image'  => esc_html__( 'Image', 'xstore-core' )
                )
            ),
            'dropdown' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Show as dropdown (Only for Display type: Name)', 'xstore-core' )
            ),
            'count' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Show product counts', 'xstore-core' )
            ),
            'hide_empty' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Hide empty brands', 'xstore-core' )
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
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
	    if (parent::admin_widget_preview(esc_html__('Brand List', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;
        $count       = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
        $dropdown    = isset( $instance['dropdown'] ) ? $instance['dropdown'] : $this->settings['dropdown']['std'];
        $displayType = isset( $instance['displayType'] ) ? $instance['displayType'] : $this->settings['displayType']['std'];
        $hide_empty  = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
	    $ajax        = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];

	    if (apply_filters('et_ajax_widgets', $ajax)){
		    echo et_ajax_element_holder( 'Brands', $instance, '', '', 'widget', $args );
		    return;
	    }

        // Setup Current Category
        $this->current_cat   = false;
        $terms_args = array(
            'taxonomy' => 'brand',
            'hide_empty' => $hide_empty,
        );
        $terms = get_terms($terms_args);

        $out = '';

        // Dropdown
        $out .= '<div class="sidebar-widget etheme_widget_brands">';
            $out .= parent::etheme_widget_title($args, $instance);

            if ( $dropdown ) { 
                    $out .= '<select name="product_brand" class="dropdown_product_brand">';
                        $out .= '<option value="" selected="selected" data-url="">'.esc_html__('Select a brand', 'xstore-core').'</option>';
                        $out .= '<option value="" data-url="' . get_permalink( wc_get_page_id( 'shop' ) ). '">'.esc_html__('All brands', 'xstore-core').'</option>';

                        foreach ( $terms as $brand ) {

                            $selected = ( is_tax( 'brand' , $brand->term_id ) ) ? ' selected' : '' ;
                            $stock = $brand->count;

                            if ( $stock && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
                                $stock  = parent::etheme_stock_taxonomy( $brand->term_id, 'brand' );
                                if ( $hide_empty && $stock < 1 ) continue;
                            }

                            $countProd = ($count == 1) ? "({$stock})" : '';
                            
                            $out .= '<option class="level-0" value="' . esc_html( $brand->name ) . '" data-url="' . esc_url( get_term_link( $brand ) ) . '"' . $selected . '>' . esc_html( $brand->name .' '. $countProd ) . '</option>';
                         }
                         
                    $out .= '</select>';

	            if (! etheme_get_option( 'ajax_product_filter', 0 )){
		            wc_enqueue_js( "
                        jQuery( '.dropdown_product_brand' ).change( function() {
                            var url = jQuery(this).find( 'option:selected' ).data( 'url' );
                            if ( url != '' ) location.href = url;
                        });
                    " );
	            }
            // List
            } else {
                $out .= '<ul>';
                $out .= '<li class="cat-item all-items"><a href="' . get_permalink( wc_get_page_id( 'shop' ) ). '">' . esc_html__('All brands', 'xstore-core') . '</a></li>';
                if( ! is_wp_error( $terms ) && count( $terms ) > 0 ) {
                    foreach ( $terms as $brand ) {

                        $class = 'cat-item';
                        $stock = $brand->count;

                        if ( is_tax( 'brand' , $brand->term_id ) ) $class .= ' current-item';

                        if ( $stock && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
                            $stock  = parent::etheme_stock_taxonomy( $brand->term_id, 'brand' );
                            if ( $hide_empty && $stock < 1 ) continue;
                        }

                        $thumbnail_id = absint(get_term_meta($brand->term_id, 'thumbnail_id', true)); 

                        $out .= '<li class="' . $class . '">';

                            $countProd = ( $count == 1 ) ? "({$stock})" : '';

                            if ( $displayType == 'name' ) { 
                                $out .= '<a href="' . get_term_link( $brand ) . '">';
                                    $out .= esc_html( $brand->name );
                                    $out .= '<span class="count">' . esc_html( $countProd ) . '</span>';
                                $out .= '</a>';
                             } else {
                                $brandImg = wp_get_attachment_image( $thumbnail_id, array( 100,50 ) );
                                if ( ! empty( $brandImg ) ) { 
                                    $out .= '<a href="' . get_term_link( $brand ) .'">';
                                        $out .= $brandImg;
                                        $out .= '<span class="count">' . esc_html($countProd) . '</span>';
                                    $out .= '</a>';
                                } else {
	                                $out .= '<a href="' . get_term_link( $brand ) . '">';
		                                $out .= esc_html( $brand->name );
		                                $out .= '<span class="count">' . esc_html( $countProd ) . '</span>';
	                                $out .= '</a>';
                                }
                            } 
                        $out .= '</li>';
                    }
                }                
                $out .= '</ul>';
            }
        $out .= '</div>';

        echo $out;
    }
}
