<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Category shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Category extends Shortcodes {

    function hooks(){}

    function category_shortcode( $atts ) {
	    if ( parent::woocommerce_notice() || xstore_notice() )
		    return;

        global $wpdb;

        $atts = shortcode_atts(array(
            'taxonomies' => '',
            'products_type' => '', //featured new sale bestsellings recently_viewed
            'hide_out_stock' => 'no',
            'orderby' => '',
            'order' => 'ASC',
            'custom' => false,
            'fp_id' => '',
            'sp_id' => '',
            'tp_id' => '',
            'title_color' => '',
            'head_bg' => '',
            'content_bg' => '',
            'radius' => '',
            'sep_color' => '',
            'b_width' => '',
            'b_style' => '',
            'b_color' => '',

            // extra settings
            'is_preview' => false
        ), $atts);

        $options = array();

        $options['box_id'] = rand(999, 9999);

        // selectors 
        $options['selectors'] = array();
        
        $options['selectors']['category'] = '.categories-products-' . esc_attr($options['box_id']);
        $options['selectors']['title'] = $options['selectors']['category'] . ' .category-title';
        $options['selectors']['link'] = $options['selectors']['title'] . ' h4 a';
        $options['selectors']['button_link'] = $options['selectors']['category'] . ' .show-products a';

        $options['selectors']['top_section'] = $options['selectors']['category'] . ' .top-products';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['category'] => array(),
            $options['selectors']['title'] => array(),
            $options['selectors']['link'] => array(),
            $options['selectors']['button_link'] => array(),
            $options['selectors']['top_section'] => array()
        );

        if ( $atts['content_bg'] != '' ) 
            $options['css'][$options['selectors']['category']][] = 'background-color: '.$atts['content_bg'];

        if ( $atts['radius'] != '' ) 
            $options['css'][$options['selectors']['category']][] = 'border-radius: '.$atts['radius'];

        if ( $atts['b_width'] != '' ) 
            $options['css'][$options['selectors']['category']][] = 'border-width: '.$atts['b_width'];

       if ( $atts['b_style'] != '' ) 
            $options['css'][$options['selectors']['category']][] = 'border-style: '.$atts['b_style'];

        if ( $atts['b_color'] != '' ) 
            $options['css'][$options['selectors']['category']][] = 'border-color: '.$atts['b_color'];

        if ( !empty($atts['b_color']) || !empty($atts['b_style']) || !empty($atts['b_width']) ) 
            $options['css'][$options['selectors']['category']][] = 'box-shadow: unset;';

        if ( $atts['title_color'] != '' ) 
            $options['css'][$options['selectors']['link']][] = 'color: '.$atts['title_color'];

        if ( $atts['head_bg'] != '' ) {
            $options['css'][$options['selectors']['title']][] = 'background-color: '.$atts['head_bg'];
            $options['css'][$options['selectors']['button_link']][] = 'color: '.$atts['head_bg'];
            $options['css'][$options['selectors']['button_link']][] = 'border-bottom-color: '.$atts['head_bg'];
        }

        if ( $atts['sep_color'] != '' ) 
            $options['css'][$options['selectors']['top_section']][] = 'border-bottom-color: '.$atts['sep_color'];

        // create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['category']] ) )
            $options['output_css'][] = $options['selectors']['category'] . '{'.implode(';', $options['css'][$options['selectors']['category']]).'}';

        if ( count( $options['css'][$options['selectors']['title']] ) )
            $options['output_css'][] = $options['selectors']['title'] . '{'.implode(';', $options['css'][$options['selectors']['title']]).'}';

        if ( count( $options['css'][$options['selectors']['link']] ) )
            $options['output_css'][] = $options['selectors']['link'] . '{'.implode(';', $options['css'][$options['selectors']['link']]).'}';

        if ( count( $options['css'][$options['selectors']['button_link']] ) )
            $options['output_css'][] = $options['selectors']['button_link'] . '{'.implode(';', $options['css'][$options['selectors']['button_link']]).'}';

        if ( count( $options['css'][$options['selectors']['top_section']] ) )
            $options['output_css'][] = $options['selectors']['top_section'] . '{'.implode(';', $options['css'][$options['selectors']['top_section']]).'}';

        // Narrow by categories

        $options['product_objects_content'] = array(
            array(
                'to_show' => false,
            ),
            array(
                'to_show' => false,
            ),
            array(
                'to_show' => false,
            ),
        );
        
        $options['cat_name'] = esc_html__('Products', 'xstore-core');
        $options['cat_link'] = get_permalink(wc_get_page_id('shop'));
        $options['wp_query_args'] = array(
            'post_type'             => 'product',
            'ignore_sticky_posts'   => 1,
            'no_found_rows'         => 1,
            'posts_per_page'        => 3,
            'orderby'               => $atts['orderby'],
            'order'                 => $atts['order'],
        );

        $options['wp_query_args']['tax_query'][] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'hidden',
            'operator' => 'NOT IN',
        );

        if ( $atts['hide_out_stock'] == 'yes' ) 
            $options['wp_query_args']['meta_query'] = array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '='
                ),
            );

        switch ( $atts['products_type'] ) {

            case 'featured':
                $options['wp_query_args']['tax_query'][] = array(
                  'taxonomy' => 'product_visibility',
                  'field'    => 'name',
                  'terms'    => 'featured',
                  'operator' => 'IN',
                );
            break;

            case 'sale':
                $options['product_ids_on_sale'] = wc_get_product_ids_on_sale();
                $options['wp_query_args']['post__in'] = array_merge( array( 0 ), $options['product_ids_on_sale'] );
            break;

            case 'price':
                $options['wp_query_args']['meta_key'] = '_price';
                $options['wp_query_args']['orderby'] = 'meta_value_num';
            break;

            case 'bestsellings':
                $options['wp_query_args']['meta_key'] = 'total_sales';
                $options['wp_query_args']['orderby'] = 'meta_value_num';
            break;

            case 'recently_viewed':
                $options['viewed_products'] = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
                $options['viewed_products'] = array_filter( array_map( 'absint', $options['viewed_products'] ) );

                if ( empty( $options['viewed_products'] ) )
                    return;

                $options['wp_query_args']['post__in'] = $options['viewed_products'];
                $options['wp_query_args']['orderby'] = 'rand';
            break;
            
            default:
                break;
        }

        if( !empty( $atts['taxonomies'] ) ) {

            $options['taxonomy_names'] = get_object_taxonomies( 'product' );
            $options['terms'] = get_terms( $options['taxonomy_names'], array(
                'orderby' => 'name',
                'include' => $atts['taxonomies']
            ));

            if( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
                $options['wp_query_args']['posts_per_page'] = 3;
                $options['wp_query_args']['tax_query'] = array('relation' => 'OR');
                foreach ($options['terms'] as $key => $term) {
                    $options['wp_query_args']['tax_query'][] = array(
                        'taxonomy' => $term->taxonomy,
                        'field' => 'slug',
                        'terms' => array( $term->slug ),
                        'include_children' => true,
                        'operator' => 'IN'
                    );
                }
                $options['cat_name'] = (isset($options['terms'][0]->name) && $options['terms'][0]->name != '') ? $options['terms'][0]->name : $options['cat_name'];
                $options['cat_link'] = get_term_link( $options['terms'][0]->term_id, 'product_cat' );
            }
        } 

        if ( $atts['fp_id'] != '' && $atts['sp_id'] != '' && $atts['tp_id'] != '') {

            $options['product_objects'] = array(
                wc_get_product($atts['fp_id']),
                wc_get_product($atts['sp_id']),
                wc_get_product($atts['tp_id'])
            );

            $options['product_objects_content'] = array(
                array(
                    'to_show' => is_object($options['product_objects'][0]),
                    'id' => $atts['fp_id'],
                ),
                array(
                    'to_show' => is_object($options['product_objects'][1]),
                    'id' => $atts['sp_id'],
                ),
                array(
                    'to_show' => is_object($options['product_objects'][2]),
                    'id' => $atts['tp_id'],
                )
            );

            for ( $i=0; $i < 3; $i++ ) {

                if ( $options['product_objects_content'][$i]['to_show'] ) {

                    $options['product_objects_content'][$i]['title'] = $options['product_objects'][$i]->get_title();
                    $options['product_objects_content'][$i]['link'] = get_permalink($options['product_objects_content'][$i]['id']);

                    if ( has_post_thumbnail( $options['product_objects_content'][$i]['id'] ) ) {
                        
                        $options['product_objects_content'][$i]['thumb_id'] = get_post_thumbnail_id( $options['product_objects_content'][$i]['id'] );

                        $options['product_objects_content'][$i]['full_size_image']   = wp_get_attachment_image_src( $options['product_objects_content'][$i]['thumb_id'], 'full' );
                        $options['product_objects_content'][$i]['img_attr'] = array(
                            'title'                   => get_post_field( 'post_title', $options['product_objects_content'][$i]['thumb_id'] ),
                            'data-caption'            => get_post_field( 'post_excerpt', $options['product_objects_content'][$i]['thumb_id'] ),
                            'data-src'                => $options['product_objects_content'][$i]['full_size_image'][0],
                            'data-large_image'        => $options['product_objects_content'][$i]['full_size_image'][0],
                            'data-large_image_width'  => $options['product_objects_content'][$i]['full_size_image'][1],
                            'data-large_image_height' => $options['product_objects_content'][$i]['full_size_image'][2],
                        );
                        $options['product_objects_content'][$i]['img'] = get_the_post_thumbnail( $options['product_objects_content'][$i]['id'], 'woocommerce_thumbnail', $options['product_objects_content'][$i]['img_attr'] );
                    }
                    else 
                        $options['product_objects_content'][$i]['img'] = wc_placeholder_img();
                }

            }
        }
        elseif ( !empty($atts['taxonomies']) ) {

            $options['products'] = new \WP_Query( $options['wp_query_args'] );

            $i = 0;

            if ( $options['products']->have_posts() ) : ?>

                    <?php while ( $options['products']->have_posts() ) : $options['products']->the_post();

                        $id = get_the_ID();
                        
                        $options['product_objects_content'][$i]['to_show'] = true;
                        $options['product_objects_content'][$i]['title'] = get_the_title();
                        $options['product_objects_content'][$i]['link'] = get_permalink($id);

                        if ( has_post_thumbnail( $id ) ) {
                            
                            $options['product_objects_content'][$i]['thumb_id'] = get_post_thumbnail_id( $id );

                            $options['product_objects_content'][$i]['full_size_image']   = wp_get_attachment_image_src( $options['product_objects_content'][$i]['thumb_id'], 'full' );
                            $options['product_objects_content'][$i]['img_attr'] = array(
                                'title'                   => get_post_field( 'post_title', $options['product_objects_content'][$i]['thumb_id'] ),
                                'data-caption'            => get_post_field( 'post_excerpt', $options['product_objects_content'][$i]['thumb_id'] ),
                                'data-src'                => $options['product_objects_content'][$i]['full_size_image'][0],
                                'data-large_image'        => $options['product_objects_content'][$i]['full_size_image'][0],
                                'data-large_image_width'  => $options['product_objects_content'][$i]['full_size_image'][1],
                                'data-large_image_height' => $options['product_objects_content'][$i]['full_size_image'][2],
                            );
                            $options['product_objects_content'][$i]['img'] = get_the_post_thumbnail( $atts['fp_id'], 'woocommerce_thumbnail', $options['product_objects_content'][$i]['img_attr'] );
                        }
                        else 
                            $options['product_objects_content'][$i]['img'] = wc_placeholder_img();

                        unset($id);

                        $i++; ?>

                    <?php endwhile; // end of the loop. 

            unset($i);

            endif;

            wp_reset_postdata();

        } else 
            return;

        ob_start(); ?>
        
        <div class="categories-products-two-rows categories-products-<?php echo esc_attr($options['box_id']); ?>">
            <div class="category-title">
                <h4>
                    <a href="<?php echo esc_url($options['cat_link']); ?>">
                        <?php echo esc_html($options['cat_name']); ?>
                    </a>
                </h4>
            </div>
            <div class="products-group">
                <div class="top-products">

                    <?php if ( $options['product_objects_content'][0]['to_show'] ) : ?>

                        <div class="top-product">
                            <div class="content-product">
                                <div class="product-image-wrapper">
                                    <a href="<?php echo esc_url($options['product_objects_content'][0]['link']); ?>" class="product-content-image">
                                        <?php echo wp_specialchars_decode($options['product_objects_content'][0]['img']); ?>
                                    </a>
                                </div>
                                <h2 class="product-title">
                                    <a href="<?php echo esc_url($options['product_objects_content'][0]['link']); ?>">
                                        <?php echo esc_html($options['product_objects_content'][0]['title']); ?>
                                    </a>
                                </h2>
                            </div>
                        </div>

                    <?php endif; 

                    if ( $options['product_objects_content'][1]['to_show'] ) : ?>

                        <div class="top-product">
                            <div class="content-product">
                                <div class="product-image-wrapper">
                                    <a href="<?php echo esc_url($options['product_objects_content'][1]['link']); ?>" class="product-content-image">
                                        <?php echo wp_specialchars_decode($options['product_objects_content'][1]['img']); ?>
                                    </a>
                                </div>
                                <h2 class="product-title">
                                    <a href="<?php echo esc_url($options['product_objects_content'][1]['link']); ?>">
                                        <?php echo esc_html($options['product_objects_content'][1]['title']); ?>
                                    </a>
                                </h2>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <?php 
                if ( $options['product_objects_content'][2]['to_show'] ) : ?>
                    <div class="bottom-product">
                        <div class="content-product">
                            <div class="product-image-wrapper">
                                <a href="<?php echo esc_url($options['product_objects_content'][2]['link']); ?>" class="product-content-image">
                                   <?php echo wp_specialchars_decode($options['product_objects_content'][2]['img']); ?>
                                </a>
                            </div>
                            <h2 class="product-title">
                                <a href="<?php echo esc_url($options['product_objects_content'][2]['link']); ?>">
                                    <?php echo esc_html($options['product_objects_content'][2]['title']);?>
                                </a>
                            </h2>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="show-products">
                <a href="<?php echo esc_url($options['cat_link']); ?>" class="read-more">
                    <?php esc_html_e('See all', 'xstore-core'); ?>
                </a>
            </div>
        </div>

        <?php 

        if ( $atts['is_preview'] ) {
            echo parent::initPreviewCss($options['output_css']);
            echo parent::initPreviewJs();
        }
        else {
            parent::initCss($options['output_css']);
        }

        unset($atts);
        unset($options);
        
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}