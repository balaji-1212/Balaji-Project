<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Customize Search.
 *
 * Add custom search form for WordPress customizer.
 *
 * @since   0.0.1
 * @version 0.0.1
 */
class Etheme_Customize_Search {

    // ! Main construct/ just leave it empty
    function __construct(){}


    /**
     * Add search actions.
     *
     * Actions to add search template and scripts
     *
     * @since   0.0.1
     * @version 0.0.1
     */
    public function actions(){
        // // ! Add scripts and templates
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'customize_controls_print_footer_scripts', array( $this, 'template' ) );
    }

    /**
     * Add script and styles to WordPress Customizer
     *
     * @since   0.0.1
     * @version 0.0.1
     */
    public function scripts() {
        wp_enqueue_style( 'et_customize-search',  get_template_directory_uri() . '/framework/customizer/search/css/search.css', array( 'etheme_admin_css' ) );
        if ( is_rtl() ) {
	        wp_enqueue_style( 'et_customize-search-rtl', get_template_directory_uri() . '/framework/customizer/search/css/search-rtl.css', array( 'etheme_admin_css', 'et_customize-search' ) );
        }

        wp_enqueue_script( 'et_customize-search', get_template_directory_uri() . '/framework/customizer/search/js/search.js' );

        wp_localize_script( 'et_customize-search', 'xstoreCustomizerSearch', array(
            'ajaxUrl'   => add_query_arg( array( 'customizer' => 'et_core' ), esc_url( home_url( '/', 'relative' ) ) ),
            'nonce'     => wp_create_nonce( 'et_core_customizer' ),
            'etcCore'   => class_exists( 'ETC_Initial' ),
        ) );

    }

    /**
     * Get search template.
     *
     * @since   0.0.1
     * @version 0.0.1
     */
    public function template(){ 
        get_template_part( 'framework/customizer/search/template-parts/search' );
    }
}

$Etheme_Customize_header_Builder = new Etheme_Customize_Search();
$Etheme_Customize_header_Builder -> actions();