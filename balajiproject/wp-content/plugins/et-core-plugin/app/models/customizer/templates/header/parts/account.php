<?php
    /**
     * The template for displaying header account block
     *
     * @since   1.4.0
     * @version 1.0.5
     * last changes in 2.2.4
     */
 ?>

<?php 

    global $et_builder_globals;

    $element_options = array();
    $element_options['account_style_et-desktop'] = get_theme_mod( 'account_style_et-desktop', 'type1' );
    $element_options['account_style_et-desktop'] = apply_filters('account_style', $element_options['account_style_et-desktop']);

    $element_options['account_content_position_et-desktop'] = get_theme_mod( 'account_content_position_et-desktop', 'right' );
    $element_options['account_dropdown_position_et-desktop'] =  get_theme_mod( 'account_dropdown_position_et-desktop', 'right' );

    $element_options['wrapper_class'] = '';

    $element_options['account_content_type_et-desktop'] = get_theme_mod( 'account_content_type_et-desktop', 'dropdown' );

    $element_options['not_account'] = function_exists( 'is_account_page' ) && is_account_page() ? false : true;

    if ( $et_builder_globals['in_mobile_menu'] ) {
        $element_options['account_content_type_et-desktop'] = 'none';
        $element_options['wrapper_class'] .= ' justify-content-inherit';
    }
    else {
        $element_options['wrapper_class'] .= ' login-link';
    }

    // filters 
    
    $element_options['etheme_mini_account_content_type'] = apply_filters('etheme_mini_account_content_type', $element_options['account_content_type_et-desktop']);

    $element_options['etheme_mini_account_content'] = $element_options['etheme_mini_account_content_type'] != 'none';
    $element_options['etheme_mini_account_content'] = apply_filters('etheme_mini_account_content', $element_options['etheme_mini_account_content']);

    $element_options['etheme_mini_account_content_position'] = apply_filters('etheme_mini_account_content_position', $element_options['account_content_position_et-desktop']);

    $element_options['account_off_canvas'] = $element_options['etheme_mini_account_content_type'] == 'off_canvas';

    $element_options['account_off_canvas'] = apply_filters('account_off_canvas', $element_options['account_off_canvas']);

    if ( $element_options['account_off_canvas'] && !class_exists('WooCommerce') && get_query_var( 'et_is-loggedin', false)) {
        $element_options['account_off_canvas'] = false;
    }

    $element_options['wrapper_class'] .= ' account-' . $element_options['account_style_et-desktop'];
    $element_options['wrapper_class'] .= ( $element_options['account_off_canvas'] ) ? ' et-content-' . $element_options['etheme_mini_account_content_position'] : '';
    $element_options['wrapper_class'] .= ( !$element_options['account_off_canvas'] && $element_options['account_dropdown_position_et-desktop'] != 'custom' ) ? ' et-content-' . $element_options['account_dropdown_position_et-desktop'] : '';
    $element_options['wrapper_class'] .= ( $element_options['account_off_canvas'] && $element_options['etheme_mini_account_content'] && $element_options['not_account']) ? ' et-off-canvas et-off-canvas-wide et-content_toggle' : ' et-content-dropdown et-content-toTop';
    $element_options['wrapper_class'] .= ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' );

    $element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
    $element_options['attributes'] = array();
    if ( $element_options['is_customize_preview'] ) 
        $element_options['attributes'] = array(
            'data-title="' . esc_html__( 'Account', 'xstore-core' ) . '"',
            'data-element="account"'
        );

    if ( $element_options['account_off_canvas'] || $element_options['is_customize_preview'] ) {
        // could be via default wp
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'off-canvas' );
	    }
	    
	    // Load it on each page because it needed for off canvas
	    if ( get_query_var('et_account-registration', false) && !get_query_var('et_account-registration-generate-pass', false)){
		    wp_enqueue_script( 'wc-password-strength-meter' );
	    }
    }
    if ( $element_options['etheme_mini_account_content'] || $element_options['is_customize_preview'] ) {
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'header-account' );
	    }
    }
    

?>  

<div class="et_element et_b_header-account flex align-items-center <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo header_account_callback(); ?>
</div>

<?php unset($element_options);