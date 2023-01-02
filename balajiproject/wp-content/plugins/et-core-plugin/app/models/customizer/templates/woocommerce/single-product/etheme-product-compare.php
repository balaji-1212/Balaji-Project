<?php 
/**
 * The template for single product compare
 *
 * @since   2.2.4
 * @version 1.0.0
 */

$element_options = array();
if ( get_theme_mod('xstore_compare', false) ) {
    $element_options['built_in_compare_instance'] = XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance();
    $custom_icon = false;
    $custom_options = array(
        'is_single' => true,
        'is_spb' => true,
        'has_tooltip' => get_theme_mod('product_compare_tooltip', false),
        'redirect_on_remove' => get_theme_mod('product_compare_redirect_on_remove', false),
        'add_text' => get_theme_mod('product_compare_label_add_to_compare', esc_html__('Add to compare', 'xstore-core')),
        'remove_text' => get_theme_mod('product_compare_label_browse_compare', esc_html__('Delete from compare', 'xstore-core')),
        'only_icon' => get_theme_mod('product_compare_only_icon', false),
    );
    switch ( get_theme_mod( 'product_compare_icon_et-desktop', 'type1' ) ) {
        case 'type1':
            $custom_options['custom_icon'] = false;
            $custom_options['add_icon_class'] = 'et-compare';
            $custom_options['remove_icon_class'] = 'et-compare';
            break;
        case 'custom':
            $icon_custom = get_theme_mod( 'product_compare_icon_custom_svg_et-desktop', '' );
            $icon_custom = isset( $icon_custom['id'] ) ? $icon_custom['id'] : '';
            if ( $icon_custom != '' ) {
                $custom_options['custom_icon'] = etheme_get_svg_icon( $icon_custom );
                $custom_options['add_icon_class'] = false;
                $custom_options['remove_icon_class'] = false;
            }
            else {
                $custom_options['custom_icon'] = false;
                $custom_options['add_icon_class'] = 'et-compare';
                $custom_options['remove_icon_class'] = 'et-compare';
            }
            break;
        case 'none':
            $custom_options['show_icon'] = false;
            $custom_options['custom_icon'] = false;
            $custom_options['add_icon_class'] = false;
            $custom_options['remove_icon_class'] = false;
            break;
    }

    $element_options['built_in_compare_instance']->print_button(null, $custom_options);
    unset($element_options);
    return;
}

ob_start();

	echo '<div class="single-compare">';

	if ( defined('YITH_WOOCOMPARE') && class_exists('YITH_Woocompare_Frontend') ) {
	    $obj = new YITH_Woocompare_Frontend();
	    echo $obj->add_compare_link();
	}
	else { ?>
		<a class="flex flex-wrap align-items-center">
			<span class="flex-inline justify-content-center align-items-center flex-nowrap">
	            <?php esc_html_e( 'Compare', 'xstore-core' ); ?> 
		            <span class="mt-mes"><?php echo current_user_can( 'edit_theme_options' ) ? sprintf(
                        /* translators: %s: URL to header image configuration in Customizer. */
                            __( 'Please, enable <a style="text-decoration: underline" href="%s" target="_blank">Built-in Compare</a>.', 'xstore-core'),
                            admin_url( 'customize.php?autofocus[section]=xstore-compare' )) :
                            __( 'Please, enable Built-in Compare.', 'xstore-core'); ?></span>
		        </span>
			</a>
		<?php 
	}

	echo '</div>';

echo ob_get_clean(); 