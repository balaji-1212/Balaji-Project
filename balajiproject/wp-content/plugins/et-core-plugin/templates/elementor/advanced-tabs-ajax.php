<?php 
use ETC\App\Traits\Elementor;
?>
<div data-id="<?php echo esc_attr( $tabs['_id'] ); ?>" class="clearfix active <?php echo esc_attr( $tabs['navigation_position_style'] );?> <?php echo ( 'middle-inside' === $tabs['navigation_position'] ) ? esc_attr( 'middle-inside' ): ''; ?>">
    <?php
    foreach ( $tabs as $key => $tab ):

        if ( $tab ) {
            switch ( $key ) {
                case 'ids':
                case 'taxonomies':
                $atts[$key] = !empty( $tab ) ? implode( ',',$tab ) : array();
                break;
                case 'slides':
                $atts['large'] = $atts['notebook'] = $tab;
                break;
                case 'slides_tablet':
                $atts['tablet_land'] = $atts['tablet_portrait'] = $tab;
                break;
                case 'slides_mobile':
                $atts['mobile'] = $tab;
                break;

                default:
                    $atts[ $key ] = $tab;
                break;
            }
        }

    endforeach;
    
    $atts['is_preview'] = $is_preview;
    $atts['elementor']  = true;

    add_filter('woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3);
//    add_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
//    add_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
    add_filter( 'etheme_output_shortcodes_inline_css', function() { return true; } );

    // this add variation gallery filters at loop start and remove it after loop end
    //        if ( !$_POST['archiveVariationGallery'] ) {
    add_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
    add_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
    //        }

    add_filter('woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2);
    
    echo $Products_Shortcode->products_shortcode( $atts, '' );

    echo '<script>jQuery(document).ready(function(){ 
        etTheme.swiperFunc();
        etTheme.secondInitSwipers();
        etTheme.global_image_lazy();
        if ( etTheme.contentProdImages !== undefined )
            etTheme.contentProdImages();
        if ( window.hoverSlider !== undefined ) { 
            window.hoverSlider.init({});
            window.hoverSlider.prepareMarkup();
        }
        if ( etTheme.countdown !== undefined )
            etTheme.countdown();
        etTheme.customCss();
        etTheme.customCssOne();
        if ( etTheme.reinitSwatches !== undefined )
            etTheme.reinitSwatches();
    });</script>';


   ?>
</div>