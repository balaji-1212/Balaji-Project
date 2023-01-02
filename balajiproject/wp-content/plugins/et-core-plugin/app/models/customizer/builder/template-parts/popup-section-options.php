<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for displaying single product column style popup of Wordpress customizer
 *
 * @since   2.1.1
 * @version 1.0.1
 */

$id = isset( $_POST['id'] ) ? $_POST['id'] : '';

if ( $id && ! $_POST['style'] ) {

    if ( $_POST['multiple'] ) {
        $data = json_decode( get_option( 'et_multiple_single_product', false ), true );
        $data = $data[$_POST['multiple']]['options']['product_single_elements'];
        $data = json_decode( base64_decode( $data ), true );
    } else {
        $product_single_elements = '{"element-oCMF7":{"title":"Section1","width":"100","index":1,"align":"start","sticky":"false","data":{"element-lpYyv":{"element":"etheme_woocommerce_template_woocommerce_breadcrumb","index":0}}},"element-raHwF":{"title":"Section2","width":"30","index":2,"align":"start","sticky":"false","data":{"sA6vX":{"element":"etheme_woocommerce_show_product_images","index":0}}},"element-TFML4":{"title":"Section3","width":"35","index":3,"align":"start","sticky":"false","data":{"su2ri":{"element":"etheme_woocommerce_template_single_title","index":0},"pcrn2":{"element":"etheme_woocommerce_template_single_price","index":1},"ZhZAb":{"element":"etheme_woocommerce_template_single_rating","index":2},"DBsjn":{"element":"etheme_woocommerce_template_single_excerpt","index":3},"oXjuP":{"element":"etheme_woocommerce_template_single_add_to_cart","index":4},"element-Zwwrj":{"element":"etheme_product_single_wishlist","index":5},"4XneW":{"element":"etheme_woocommerce_template_single_meta","index":6},"WP7Ne":{"element":"etheme_woocommerce_template_single_sharing","index":7}}},"element-fgcNP":{"title":"Section4","width":"25","index":4,"align":"start","sticky":"element-TFML4","data":{"HK48p":{"element":"etheme_product_single_widget_area_01","index":0}}},"element-nnrkj":{"title":"Section5","width":"100","index":5,"align":"start","sticky":"false","data":{"BJZsk":{"element":"etheme_woocommerce_output_product_data_tabs","index":0}}},"element-aKxrL":{"title":"Section6","width":"100","index":6,"align":"start","sticky":"false","data":{"qyJz2":{"element":"etheme_woocommerce_output_related_products","index":0}}},"element-a8Rd9":{"title":"Section7","width":"100","index":7,"align":"start","sticky":"false","data":{"sbu5J":{"element":"etheme_woocommerce_output_upsell_products","index":0}}}}';
        $data = json_decode( get_theme_mod( 'product_single_elements', $product_single_elements ), true );
    }
    $data = $data[$id];

    if ( isset( $data['style'] ) && ! empty( $data['style'] ) ) {
        $style = json_decode( $data['style'], true );
    } else {
        $style = array(
            'color'                 => '',
            'border-color'          => '',
            'border-radius'         => 0,
            'background-color'      => '',
            'background-image'      => '',
            'background-repeat'     => '',
            'background-position'   => '',
            'background-size'       => '',
            'background-attachment' => '',
            'margin-top'            => '0px', 
            'margin-right'          => '0px', 
            'margin-bottom'         => '0px', 
            'margin-left'           => '0px', 
            'border-top'            => '0px', 
            'border-right'          => '0px', 
            'border-bottom'         => '0px', 
            'border-left'           => '0px', 
            'padding-top'           => '0px', 
            'padding-right'         => '15px', 
            'padding-bottom'        => '0px', 
            'padding-left'          => '15px', 
            'border-style'          => '',
        );
    }
} else {
    $style = json_decode( base64_decode( $_POST['style'] ), true );
}

$options = array(
    'color'             => '',
    'border-color'      => '',
    'background-color'  => '',
    'border-radius'     => 0,
    'background-image'  => '',
    'background-repeat' => array(
        'no-repeat' => 'No Repeat',
        'repeat'    => 'Repeat All',
        'repeat-x'  => 'Repeat Horizontally',
        'repeat-y'  => 'Repeat Vertically',
    ),
    'background-position' => array(
        'left top'      => 'Left Top',
        'left center'   => 'Left Center',
        'left bottom'   => 'Left Bottom',
        'right top'     => 'Right Top',
        'right center'  => 'Right Center',
        'right bottom'  => 'Right Bottom',
        'center top'    => 'Center Top',
        'center center' => 'Center',
        'center bottom' => 'Center Bottom',
    ),
    'background-size' => array(
        'auto'    => 'Auto',
        'cover'   => 'Cover',
        'contain' => 'Contain',
    ),
    'background-attachment' => array(
        'scroll' => 'scroll',
        'fixed'  => 'fixed',
    ),
    'margin-top'     => '0px', 
    'margin-right'   => '0px', 
    'margin-bottom'  => '0px', 
    'margin-left'    => '0px', 
    'border-top'     => '0px', 
    'border-right'   => '0px', 
    'border-bottom'  => '0px', 
    'border-left'    => '0px', 
    'padding-top'    => '0px', 
    'padding-right'  => '0px', 
    'padding-bottom' => '0px', 
    'padding-left'   => '0px',
    'border-style' => array(
        'none'   => 'None',
        'solid'  => 'Solid',
        'dotted' => 'Dotted',
        'dashed' => 'Dashed',
        'double' => 'Double',
        'groove' => 'Groove',
        'ridge'  => 'Ridge',
        'inset'  => 'Inset',
        'outset' => 'Outset',
        'hidden' => 'Hidden',
    ),
);

 ?>
<div class="et_popup ui-draggable ui-draggable-handle et_sticky-popup" data-id="" data-size="lg">
    <div class="et_actions-1">
        <span class="dashicons dashicons-move"></span>
        <span>
            <span class="style-popup-title"></span>
            <span><?php esc_html_e( 'Settings', 'xstore-core' ); ?> </span>
        </span>
        <span class="dashicons dashicons-no-alt et_close"></span>
    </div>
    <div class="et_inside-wrapper">
        <div class="et_popup-info">
            <label>
                <?php esc_html_e( 'Click Save button to apply changes', 'xstore-core' ); ?> 
            </label>
        </div>
        <br>
        <div class="section-opt-group flex justify-content-between section-text-color">
            <label class=""><?php esc_html_e( 'Text Color', 'xstore-core' ); ?></label>
            <input type="text" class="section-option" data-option="color" value="<?php echo esc_attr( $style['color'] ); ?>" data-alpha="true">
        </div>
        <div class="section-opt-group flex justify-content-between section-border-color">
            <label class=""><?php esc_html_e( 'Border Color', 'xstore-core' ); ?></label>
            <input type="text" class="section-option" data-option="border-color" value="<?php echo esc_attr( $style['border-color'] ); ?>" data-alpha="true">
        </div>
        <div class="section-opt-group flex justify-content-between section-border-radius">
            <div class="block-setting block-width customize-control-kirki-slider flex align-items-center">
                <label class=""><?php esc_html_e( 'Border Radius (px)', 'xstore-core' ); ?></label>
                <div class="wrapper">
                    <input type="range" class="section-option" data-option="border-radius" min="0" max="50" step="1" value="<?php echo $style['border-radius']; ?>">
                    <span class="value">
                        <input type="text" value="<?php echo $style['border-radius']; ?>">
                        <span class="suffix"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="section-opt-group flex justify-content-between section-background-color">
            <label class=""><?php esc_html_e( 'Background Color', 'xstore-core' ); ?></label>
            <input type="text" class="section-option" data-option="background-color" value="<?php echo esc_attr( $style['background-color'] ); ?>" data-alpha="true">
        </div>
        <div class="section-opt-group flex justify-content-between section-background-image">
            <label class=""><?php esc_html_e( 'Background Image', 'xstore-core' ); ?></label>
            <div class="media-widget-preview etheme_media-image">
                <?php 
                    if ($style['background-image']) {
                        $url    = wp_get_attachment_image_url($style['background-image']);
                        $image  = '';
                        $placeholder = 'hidden';
                    } else {
                        $url    = '';
                        $image  = 'hidden';
                        $placeholder = '';
                    }
                ?>
                <div class="image-holder">
                    <img class="attachment-thumb <?php echo esc_attr( $image ); ?>" src="<?php echo esc_url( $url ); ?>">
                </div>
                <input type="hidden" class="section-option background-image" data-option="background-image" value="<?php echo esc_attr( $style['background-image'] ); ?>">
                <div class="attachment-media-view">
                    <div class="placeholder etheme_upload-image <?php echo esc_attr( $placeholder ); ?>"><?php esc_html_e( 'No image selected', 'xstore-core' )?></div>
                </div>
                <div class="actions">
                    <button class="button et-background-image-remove <?php echo esc_attr( $image ); ?>"><?php esc_html_e( 'Remove', 'xstore-core' ); ?></button>
                    <button class="button et-background-image-upload"><?php esc_html_e( 'Select image', 'xstore-core' ); ?></button>
                </div>
            </div>
        </div>
        <div class="section-opt-group flex justify-content-between section-background-repeat">
            <label class=""><?php esc_html_e( 'Background Repeat', 'xstore-core' ); ?></label>
            <select class="section-option" data-option="background-repeat">
                <?php 
                    foreach ($options['background-repeat'] as $key => $value) {
                        echo '<option value="' . $key . '" ' . selected( $key, $style['background-repeat'] ) . '>' . $value . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="section-opt-group flex justify-content-between section-background-position">
            <label class=""><?php esc_html_e( 'Background Position', 'xstore-core' ); ?></label>
            <select class="section-option" data-option="background-position">
                <?php 
                    foreach ($options['background-position'] as $key => $value) {
                        echo '<option value="' . $key . '" ' . selected( $key, $style['background-position'] ) . '>' . $value . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="section-opt-group flex justify-content-between section-background-size">
            <label class=""><?php esc_html_e( 'Background Size', 'xstore-core' ); ?></label>
            <select class="section-option" data-option="background-size">
                <?php 
                    foreach ($options['background-size'] as $key => $value) {
                        echo '<option value="' . $key . '" ' . selected( $key, $style['background-size'] ) . '>' . $value . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="section-opt-group flex justify-content-between section-background-attachment">
            <label class=""><?php esc_html_e( 'Background Attachment', 'xstore-core' ); ?></label>
            <select class="section-option" data-option="background-attachment">
                <?php 
                    foreach ($options['background-attachment'] as $key => $value) {
                        echo '<option value="' . $key . '" ' . selected( $key, $style['background-attachment'] ) . '>' . $value . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="section-opt-group section-computed-box customize-control-kirki-box-model">    
            <label class=""><?php esc_html_e( 'Computed Box', 'xstore-core' ); ?></label>
            <br>
            <div class="box levels-3">
                <div class="margin level-0">
                    <span class="label"><?php esc_html_e( 'Margin', 'xstore-core' ); ?></span>
                    <input class="section-option box-model-input top margin-top" type="text" data-option="margin-top" size="1" value="<?php echo esc_attr( $style['margin-top'] ); ?>">
                    <input class="section-option box-model-input right margin-right" type="text" data-option="margin-right" size="1" value="<?php echo esc_attr( $style['margin-right'] ); ?>">
                    <input class="section-option box-model-input bottom margin-bottom" type="text" data-option="margin-bottom" size="1" value="<?php echo esc_attr( $style['margin-bottom'] ); ?>">
                    <input class="section-option box-model-input left margin-left" type="text" data-option="margin-left" size="1" value="<?php echo esc_attr( $style['margin-left'] ); ?>">
                </div>
                <div class="border level-1">
                    <span class="label"><?php esc_html_e( 'Border', 'xstore-core' ); ?></span>
                    <input class="section-option box-model-input top border-top" type="text" data-option="border-top" size="1" value="<?php echo esc_attr( $style['border-top'] ); ?>">
                    <input class="section-option box-model-input right border-right" type="text" data-option="border-right" size="1" value="<?php echo esc_attr( $style['border-right'] ); ?>">
                    <input class="section-option box-model-input bottom border-bottom" type="text" data-option="border-bottom" size="1" value="<?php echo esc_attr( $style['border-bottom'] ); ?>">
                    <input class="section-option box-model-input left border-left" type="text" data-option="border-left" size="1" value="<?php echo esc_attr( $style['border-left'] ); ?>">
                </div>
                <div class="padding level-2">
                    <span class="label"><?php esc_html_e( 'Padding', 'xstore-core' ); ?></span>
                    <input class="section-option box-model-input top padding-top" type="text" data-option="padding-top" size="1" value="<?php echo esc_attr( $style['padding-top'] ); ?>">
                    <input class="section-option box-model-input right padding-right" type="text" data-option="padding-right" size="1" value="<?php echo esc_attr( $style['padding-right'] ); ?>">
                    <input class="section-option box-model-input bottom padding-bottom" type="text" data-option="padding-bottom" size="1" value="<?php echo esc_attr( $style['padding-bottom'] ); ?>">
                    <input class="section-option box-model-input left padding-left" type="text" data-option="padding-left" size="1" value="<?php echo esc_attr( $style['padding-left'] ); ?>">
                </div>
            </div>
        </div>
        <div class="section-opt-group flex justify-content-between section-border-style">
            <label class=""><?php esc_html_e( 'Border Style', 'xstore-core' ); ?></label>
            <select class="section-option" data-option="border-style">
                <?php 
                    foreach ($options['border-style'] as $key => $value) {
                        echo '<option value="' . $key . '" ' . selected( $key, $style['border-style'] ) . '>' . $value . '</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="et_actions-2 et_actions-1">
        <span class="et_button et_button-lg et_button-green et_column-style-save"><?php esc_html_e( 'Save', 'xstore-core' ); ?></span>
    </div>
</div>