<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for displaying single product builder of Wordpress customizer
 *
 * @since   1.0.0
 * @version 1.0.2
 * @log
 * 1.0.1
 * Added panel resizer
 * 1.0.2
 * Added count condition
 * Added multiple-header-name
 */

$ajax_local     = false;
$ajax_local     = apply_filters( 'Etheme_Customize_Builder_ajax', $ajax_local );

$Etheme_Customize_Builder = new Etheme_Customize_header_Builder();
$elements = $Etheme_Customize_Builder->elements;

$product_single_elements = '{"element-oCMF7":{"title":"Section1","width":"100","index":1,"align":"start","sticky":"false","data":{"element-lpYyv":{"element":"etheme_woocommerce_template_woocommerce_breadcrumb","index":0}}},"element-raHwF":{"title":"Section2","width":"30","index":2,"align":"start","sticky":"false","data":{"sA6vX":{"element":"etheme_woocommerce_show_product_images","index":0}}},"element-TFML4":{"title":"Section3","width":"35","index":3,"align":"start","sticky":"false","data":{"su2ri":{"element":"etheme_woocommerce_template_single_title","index":0},"pcrn2":{"element":"etheme_woocommerce_template_single_price","index":1},"ZhZAb":{"element":"etheme_woocommerce_template_single_rating","index":2},"DBsjn":{"element":"etheme_woocommerce_template_single_excerpt","index":3},"oXjuP":{"element":"etheme_woocommerce_template_single_add_to_cart","index":4},"element-Zwwrj":{"element":"etheme_product_single_wishlist","index":5},"4XneW":{"element":"etheme_woocommerce_template_single_meta","index":6},"WP7Ne":{"element":"etheme_woocommerce_template_single_sharing","index":7}}},"element-fgcNP":{"title":"Section4","width":"25","index":4,"align":"start","sticky":"element-TFML4","data":{"HK48p":{"element":"etheme_product_single_widget_area_01","index":0}}},"element-nnrkj":{"title":"Section5","width":"100","index":5,"align":"start","sticky":"false","data":{"BJZsk":{"element":"etheme_woocommerce_output_product_data_tabs","index":0}}},"element-aKxrL":{"title":"Section6","width":"100","index":6,"align":"start","sticky":"false","data":{"qyJz2":{"element":"etheme_woocommerce_output_related_products","index":0}}},"element-a8Rd9":{"title":"Section7","width":"100","index":7,"align":"start","sticky":"false","data":{"sbu5J":{"element":"etheme_woocommerce_output_upsell_products","index":0}}}}';





if (isset($this->post['et_multiple']) && $this->post['et_multiple']){
	$headers = get_option('et_multiple_single_product', false);
	$headers = json_decode($headers, true);


	$ajax_local = array();
	$ajax_local['header_top_elements'] = '';


	$ajax_local = $this->decode_special_options($headers[$this->post['et_multiple']]['options']);

}




$columns = ( $ajax_local ) ? json_decode($ajax_local['product_single_elements'], true) : json_decode( get_theme_mod( 'product_single_elements', $product_single_elements ), true );



// $columns = json_decode( get_theme_mod( 'product_single_elements' ), true );

if ( ! is_array( $columns ) ) {
    $columns = array();
}


$blocks = ( $ajax_local ) ? json_decode($ajax_local['connect_block_product_single_package'], true) : get_theme_mod( 'connect_block_product_single_package' );

// $blocks = get_theme_mod( 'connect_block_product_single_package' );

if ( $blocks && count( $blocks ) ) {
    foreach ( $blocks as $block ) {
        if ( $block['data'] ) {
            $inside = json_decode( $block['data'] );
            foreach ( $inside as $key => $value ) {
                unset( $elements[$key] );
            }
        }
    }
}
$conditions = $Etheme_Customize_Builder->get_json_option('et_multiple_single_product');

?>



<div class="et_customizer-builder et_customizer-builder-single_product hidden <?php echo (count( $conditions )) ? 'et_multiple-exist' : ''; echo ($Etheme_Customize_Builder->et_multiple)? ' current-multiple': ''; ?>" data-option="product_single_elements">
    <div class="et_panel-resizer"><span class="et_panel-resizer-inner"></span></div>
    <div class="et_header-head align-center flex valign-center equal-columns">
        <div class="flex-left align-left">
            <span class="et_button show-builder hidden">
                <span class="dashicons dashicons-schedule"></span>
                <span><?php esc_html_e( 'Builder', 'xstore-core' ); ?></span>        
            </span>
	        <?php if(! $Etheme_Customize_Builder->et_multiple): ?>
            <span class="et_button et_call-multiple-single-products">
                <span class="dashicons dashicons-rest-api"></span>
                <span>
                    <?php esc_html_e( 'Multiple Templates', 'xstore-core' ); ?>
                    (<span class="multiple-count"><?php echo (count( $conditions )) ? count( $conditions ) : 0?></span>)
                </span>
            </span>
            <?php endif; ?>
        </div>
        <div class="multiple-header-name" data-name="" data-name-prefix="You are editing"></div>

	    <?php if ($Etheme_Customize_Builder->et_multiple): ?>
            <div class="multiple-header-name" data-name="" data-name-prefix="You are editing multiple single product for <?php echo $Etheme_Customize_Builder->condition_path('single-product', $Etheme_Customize_Builder->et_multiple, '->'); ?>" ></div>
	    <?php elseif (count( $conditions )): ?>
            <div class="multiple-header-name" data-name="" data-name-prefix="You are editing Default single product -> All Site"><span class="tip">(IF YOU WANT TO EDIT ANOTHER SINGLE PRODUCT...)</span></div>
	    <?php endif; ?>

	    <?php if( $Etheme_Customize_Builder->et_multiple): ?>
            <div data-name="<?php esc_html_e('Multiple Single product builder', 'xstore-core'); ?>" data-mobile-name="<?php esc_html_e('Header builder (mobile)', 'xstore-core'); ?>"></div>
	    <?php else: ?>
            <div data-name="<?php esc_attr_e('Single product builder', 'xstore-core'); ?>"></div>
	    <?php endif;?>

        <div class="flex-right align-right">
            <a class="et_button hidden" href="https://www.youtube.com/watch?v=RbdKjQrFnO4&list=PLMqMSqDgPNmDu3kYqh-SAsfUqutW3ohlG&index=2&t=0s" target="_blank">
                <span class="dashicons">
                    <svg height="1.2em" viewBox="0 -77 512.00213 512" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="m501.453125 56.09375c-5.902344-21.933594-23.195313-39.222656-45.125-45.128906-40.066406-10.964844-200.332031-10.964844-200.332031-10.964844s-160.261719 0-200.328125 10.546875c-21.507813 5.902344-39.222657 23.617187-45.125 45.546875-10.542969 40.0625-10.542969 123.148438-10.542969 123.148438s0 83.503906 10.542969 123.148437c5.90625 21.929687 23.195312 39.222656 45.128906 45.128906 40.484375 10.964844 200.328125 10.964844 200.328125 10.964844s160.261719 0 200.328125-10.546875c21.933594-5.902344 39.222656-23.195312 45.128906-45.125 10.542969-40.066406 10.542969-123.148438 10.542969-123.148438s.421875-83.507812-10.546875-123.570312zm0 0" fill="#f00"></path><path d="m204.96875 256 133.269531-76.757812-133.269531-76.757813zm0 0" fill="#fff"></path></svg>
                </span>
                <span><?php esc_html_e('Tutorials', 'xstore-core'); ?></span>
            </a>
            <span class="et_button et_collapse-builder" data-panel="single_product">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </span>
        </div>
    </div>




<div class="et_product-single-wrapper">

    <div class="et_product-single">

        <div class="et_product-single-inner">

     


        <?php 

            uasort( $columns, function ( $item1, $item2 ) {
                return $item1['index'] <=> $item2['index'];
            });

            if ( count( $columns )  ) {
                foreach ($columns as $key => $value) {

                    $inside = '';

                    if ( $value['data'] ) {
                        uasort( $value['data'], function ( $item1, $item2 ) {
                            return $item1['index'] <=> $item2['index'];
                        });

                        foreach ($value['data'] as $id => $element) {

                            $args = array(
                                'id'       => $id,
                                'element'  => $element['element'],
                                'icon'     => $elements[$element['element']]['icon'],//$value['icon'],
                                'title'    => $elements[$element['element']]['title'], //$value['title'],
                                'section'  => $elements[$element['element']]['section'], //$value['section'],
                                'parent'   => isset( $elements[$element['element']]['parent'] ) ? $elements[$element['element']]['parent'] : '', //$value['parent'],
                                'section2' => '',
                            );

                            if ( $element['element'] != 'connect_block' ) {
                                unset( $elements[$element['element']] );
                            }

                            $inside .= $Etheme_Customize_Builder->generate_html($args);
                        }
                    }

                    printf(
                        '<div class="et_column et_col-sm-3 et_product-block" data-id="%s" data-index="%d" data-title="' . $value['title'] . '" data-sticky="' . $value['sticky'] . '">
                            <div class="et_column-actions">
                                <span class="dashicons dashicons-move"></span>
                                <span class="dashicons dashicons-admin-generic et_style-column"></span>
                                <span>%s</span>
                                <span class="et_remove-column dashicons dashicons-trash"></span>
                            </div>
                            <div class="et_column-content-wrapper"><div class="et_column-content" data-name="%s">%s</div></div>
                            <div class="et_column-settings">
                                <div class="customize-control-kirki-toggle et_column-edit et-custom-toggle">
                                    <label class="block-setting">
                                        <span class="et-title">%s</span>
                                        <span class="switch"></span>
                                    </label>
                                 </div>
                                <div class="block-setting block-align customize-control-kirki-radio-buttonset flex align-items-center">
                                    <span class="et-title">%s</span>
                                    <div class="buttonset">
                                

                                        <input class="switch-input screen-reader-text" type="radio" value="start" name="_customize-radio-block_align-' . $value['index'] . '" id="block_alignstart-' . $value['index'] . '" %s>

                                        <label for="block_alignstart-' . $value['index'] . '" class="switch-label switch-label-off">
                                            <span class="dashicons dashicons-editor-alignleft"></span>
                                            <span class="image-clickable"></span>
                                        </label>
                                
                                
                                        <input class="switch-input screen-reader-text" type="radio" value="center" name="_customize-radio-block_align-' . $value['index'] . '" id="block_aligncenter-' . $value['index'] . '" %s>
                                            <label for="block_aligncenter-' . $value['index'] . '" class="switch-label switch-label-off">
                                                <span class="dashicons dashicons-editor-aligncenter"></span>
                                            <span class="image-clickable"></span>
                                        </label>
                                
                                
                                        <input class="switch-input screen-reader-text" type="radio" value="end" name="_customize-radio-block_align-' . $value['index'] . '" id="block_alignend-' . $value['index'] . '" %s>
                                            <label for="block_alignend-' . $value['index'] . '" class="switch-label switch-label-off">
                                                <span class="dashicons dashicons-editor-alignright"></span>
                                            <span class="image-clickable"></span>
                                        </label>        
                                    </div>
                                </div>
                                        <!-- end align element -->

                                <div class="block-setting block-width customize-control-kirki-slider flex align-items-center">
                                    <div class="et-title">%s</div>
                                    <div class="wrapper">
                                        <input type="range" min="0" max="100" step="1" value="' . $value['width'] . '" data-customize-setting-link="top_header_height">
                                        <span class="value">
                                            <input type="text" value="' . $value['width'] . '">
                                            <span class="suffix"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>',
                        $key,
                        $value['index'],
                        $value['title'],
                        esc_html__('Drop here', 'xstore-core'),
                        $inside,
                        esc_html__('Sticky', 'xstore-core'),
                        esc_html__('Alignment', 'xstore-core'),
                        ( $value['align'] == 'start' ) ? 'checked' : '',
                        ( $value['align'] == 'center' ) ? 'checked' : '',
                        ( $value['align'] == 'end' ) ? 'checked' : '',
                        esc_html__('Width (%)', 'xstore-core')
                    );
                }
            }

            

         ?>
     
     </div>

    <div class="et_column et_col-sm-3 et_product-block et_new-column">
        <div>
            <span class="et_add-column dashicons dashicons-plus"></span>
            <span><?php echo esc_html__('Add new section', 'xstore-core'); ?></span>
        </div>
    </div>

    </div>

</div>

<?php 



 ?>

   <div class="et_column et_col-sm-12 align-left et_products-elements">
        <div class="et_column-inner">
            <?php foreach ( $elements as $key => $value ): ?>
                <?php 
                    if ( ! in_array( 'product-single', $value['location'] ) ) {
                        continue;
                    }
                ?>
                <div class="et_customizer-element <?php echo $value['class']; ?> ui-state-default" data-id="element-<?php echo $Etheme_Customize_Builder->generate_random( 5 ); ?>" data-size="1" data-element="<?php echo $key; ?>">
                    <span class="et_name">
                        <span class="dashicons <?php echo $value['icon']; ?>"></span>
                        <?php echo $value['title']; ?>
                    </span>
                    <span class="et_actions">
                        <span class="dashicons dashicons-admin-generic et_edit mtips" <?php echo isset( $value['parent'] )  ? 'data-parent='.$value["parent"].'' : ''; ?> data-section="<?php echo $value['section']; ?>"><span class="mt-mes"><?php esc_html_e( 'Settings', 'xstore-core' ); ?></span></span>
                        <?php if ( isset( $value['section2'] ) ) { ?>
                            <span class="dashicons dashicons-networking et_edit mtips" data-section="<?php echo $value['section2']; ?>">
                                <span class="mt-mes"><?php echo esc_html__( 'Dropdown settings', 'xstore-core' ); ?></span>
                            </span>
                        <?php } ?>
                        <span class="dashicons dashicons-trash et_remove mtips"><span class="mt-mes"><?php esc_html_e( 'Remove', 'xstore-core' ); ?></span></span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>  
    </div>
    <span class="et_options-data et_product-single-options-data hidden">
        <?php echo json_encode( require( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/product-single-options-data.php' ) ); ?>
    </span>
</div>


