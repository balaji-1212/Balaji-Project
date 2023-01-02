<?php
/**
 * Adding actions/filters for quantity options on Single product page
 *
 * @package    quantity-select.php
 * @since      8.3.9
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
if ( is_admin() ) {
    // add quantity options
    add_action( 'woocommerce_product_write_panel_tabs', 'et_quantity_panel_tab' );
    add_action( 'woocommerce_product_data_panels', 'et_quantity_panel_data' );
    add_action( 'woocommerce_process_product_meta', 'et_quantity_save_panel_data' );

    function et_quantity_panel_tab() {
        $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
        $label = 'XStore';
        if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
            if ( $xstore_branding_settings['control_panel']['icon'] )
                $icon = $xstore_branding_settings['control_panel']['icon'];
            if ( $xstore_branding_settings['control_panel']['label'] )
                $label = $xstore_branding_settings['control_panel']['label'];
        }
        ?>
        <li class="et_quantity_options et_quantity_tab hide_if_virtual hide_if_external">
            <a href="#et_quantity_product_data"><span>
            <?php echo esc_html__( 'Quantity', 'xstore' ); ?>
            <?php echo '<span class="et-brand-label" style="margin: 0; margin-inline-start: 5px; background: var(--et_admin_main-color, #A4004F); letter-spacing: 1px; font-weight: 400; display: inline-block; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.$label.'</span>'; ?>
            </span></a>
        </li>
        <?php
    }

    function et_quantity_panel_data() { ?>
        <div id="et_quantity_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <?php
                    woocommerce_wp_select( [
                        'id'      => '_et_quantity_type',
                        'label'   => __( 'Type', 'xstore' ),
                        'options' => [
                            ''       => __( 'Inherit', 'xstore' ),
                            'input'  => __( 'Input', 'xstore' ),
                            'select' => __( 'Select', 'xstore' ),
                        ],
                        'custom_attributes' => array('data-theme_mod-value' => get_theme_mod('shop_quantity_type', 'input'))
                    ] );

                    woocommerce_wp_textarea_input( [
                        'id'          => '_et_quantity_ranges',
                        'label'       => __( 'Range values', 'xstore' ),
                        'cols'        => 50,
                        'rows'        => 5,
                        'style'       => 'height: 100px;',
                        'placeholder' => get_theme_mod('shop_quantity_select_ranges', '1-10'),
                        'desc_tip' => 'true',
                        'description' => __( 'This value will be used for select type ranged. Enter each value in one line and can use the range e.g "1-5".', 'xstore' ),
                    ] );

                    ?>
                </p>
            </div>
        </div>
        <?php
    }

    function et_quantity_save_panel_data( $post_id ) {
        if ( isset( $_POST['_et_quantity_type'] ) ) {
            update_post_meta( $post_id, '_et_quantity_type', sanitize_text_field( $_POST['_et_quantity_type'] ) );
        } else {
            delete_post_meta( $post_id, '_et_quantity_type' );
        }

        if ( isset( $_POST['_et_quantity_values_type'] ) ) {
            update_post_meta( $post_id, '_et_quantity_values_type', sanitize_text_field( $_POST['_et_quantity_values_type'] ) );
        } else {
            delete_post_meta( $post_id, '_et_quantity_values_type' );
        }

        if ( isset( $_POST['_et_quantity_ranges'] ) ) {
            update_post_meta( $post_id, '_et_quantity_ranges', sanitize_textarea_field( $_POST['_et_quantity_ranges'] ) );
        } else {
            delete_post_meta( $post_id, '_et_quantity_ranges' );
        }

    }
}

/**
 * Filter to add attribute with values for quantity select
 */
add_filter('woocommerce_quantity_input_args', function ($args, $_product) {
    $product_id = $_product->get_ID();
    $quantity_type = get_post_meta($product_id, '_et_quantity_type', true);
    if ( empty($quantity_type) )
        $quantity_type = get_theme_mod('shop_quantity_type', 'input');
    $args['quantity_type'] = $quantity_type;
    if ( $quantity_type == 'select' ) {

        $quantity_range_values = get_post_meta($product_id, '_et_quantity_ranges', true);
        if ( empty($quantity_range_values) )
            $quantity_range_values = get_theme_mod('shop_quantity_select_ranges', '1-5');

        $quantity_ranges  = explode( "\n", str_replace( "\r", "", $quantity_range_values ) );
        $quantity_range_options = [];

        if ( empty( $quantity_range_values ) ) {
            $quantity_range_options[] = 1;
        } else {
            foreach ( $quantity_ranges as $value ) {
                $value = trim($value);
                if ( is_numeric( $value ) ) {
                    $quantity_range_options[] = intval( $value );
                } elseif ( strpos( $value, '-' ) !== false ) {
                    $range = explode( '-', $value );

                    if ( count( $range ) === 2 ) {
                        $min = intval( $range[0] );
                        $max = intval( $range[1] );

                        $quantity_range_options = array_merge( $quantity_range_options, range( $min, $max ) );
                    }
                }
            }

            $quantity_range_options = array_unique( $quantity_range_options );
            asort($quantity_range_options);
            $min_value = isset($args['min_value']) ? $args['min_value'] : 0;
            $max_value = isset($args['max_value']) ? $args['max_value'] : '';
            foreach ( $quantity_range_options as $key => $number ) {
                if ( $min_value > $number || ( '' !== $max_value && $max_value > 0 && $max_value < $number ) ) {
                    unset( $quantity_range_options[ $key ] );
                }
            }
        }
        $args['quantity_values'] = $quantity_range_options;
    }
    return $args;
}, 10, 2);

/**
 * Filter template path
 * include quantity-select file only in case it is really needed
 */
add_filter('wc_get_template', function ($template, $template_name, $args) {
    if ( isset($args['quantity_type']) && $args['quantity_type'] == 'select' && basename( $template ) == 'quantity-input.php') {
        $template = ETHEME_CHILD . 'woocommerce/global/quantity-select.php';
        if ( !file_exists($template) )
            $template = ETHEME_BASE . 'woocommerce/global/quantity-select.php';
    }
    return $template;
}, 10, 3);