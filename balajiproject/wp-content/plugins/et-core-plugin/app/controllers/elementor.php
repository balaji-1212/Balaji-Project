<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;
use ETC\App\Controllers\Shortcodes\Products as Products_Shortcode;
use ETC\Views\Elementor as View;

/**
 * Elementor initial class.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controller
 */
final class Elementor extends Base_Controller {

    /**
     * Minimum Elementor Version Supp
     *
     * @since 2.0.0
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.5.0';

    /**
     * Registered modules.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $modules = NULL;

    /**
     * Registered widgets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $widgets = NULL;

    /**
     * Registered controls.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $controls = NULL;

    /**
     * Registered google_map_api.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $google_map_api = NULL;

    /**
     * Constructor
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function __construct() {
        // Register ajax

        add_action( 'wp_ajax_select2_control', array( $this, '_maybe_post_terms' ) );
        add_action( 'wp_ajax_nopriv_select2_control', array( $this, '_maybe_post_terms' ) );
        
        add_action( 'wp_ajax_et_advanced_tab', array( $this, 'et_advanced_tab' ) );
        add_action( 'wp_ajax_nopriv_et_advanced_tab', array( $this, 'et_advanced_tab' ) );
	
	    add_action( 'wp_ajax_etheme_elementor_lazy_load', array( $this, 'etheme_elementor_lazy_load' ) );
	    add_action( 'wp_ajax_nopriv_etheme_elementor_lazy_load', array( $this, 'etheme_elementor_lazy_load' ) );
	    
        add_action( 'plugins_loaded', array( $this, 'hooks' ) );

    }

    /**
     * Fired elementor options by `plugins_loaded` action hook.
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function hooks() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Check for elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_version' ) );
            return;
        }

        $this->register_modules();

        // Register categories, widgets, controls 
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );
	    // before 3.5.0
		// add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	    // after 3.5.0 because 'elementor/widgets/widgets_registered' action became deprecated
	    add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	    add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );
        
        // Elementor editor

		// studio
        add_action( 'init', array( $this, 'enqueue_studio' ) );
	
	    // cross copy paste
	    add_action( 'init', array( $this, 'enqueue_cross_domain_cp' ) );

        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_styles' ) );
        // Enqueue front end js
        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ) );
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'enqueue_scripts' ) );

        add_filter( 'elementor/icons_manager/native', array( $this, 'etc_elementor_icons' ) );
        add_filter( 'elementor/fonts/groups', array( $this, 'add_custom_font_group' ) );
        add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_custom_font' ) );

    }
    
    public function enqueue_studio(){
        if ( get_theme_mod( 'etheme_studio_on', 1) ){
            require_once( ET_CORE_DIR . 'app/models/studio/studio.php' );
        }
    }
	
	/**
	 * Cross Domain Copy Paste.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_cross_domain_cp(){
		require_once( ET_CORE_DIR . 'app/models/cross-domain-cp/cross-domain-cp.php' );
	}

    /**  
     * Register widget args  
     *  
     * @return mixed|null|void  
     */  
    public static function module_args() {  
      
        if ( ! is_null( self::$modules ) ) {
            return self::$modules;
        }

        return self::$modules = apply_filters( 'etc/add/elementor/modules', array() );
    }

    /**  
     * Register widget args  
     *  
     * @return mixed|null|void  
     */  
    public static function widgets_args() {  
      
        if ( ! is_null( self::$widgets ) ) {
            return self::$widgets;
        }

        return self::$widgets = apply_filters( 'etc/add/elementor/widgets', array() );
    }

    /**  
     * Register controls args  
     *  
     * @return mixed|null|void  
     */  
    public static function controls_args() {  
      
        if ( ! is_null( self::$controls ) ) {
            return self::$controls;
        }

        return self::$controls = apply_filters( 'etc/add/elementor/controls', array() );
    }

    /**
     * Admin notice when minimum required Elementor version not activating.
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function admin_notice_version() {
    	
	    $view = new View;
	    $view->elementor_version_requirement(
            array(
                'error_message' => sprintf(esc_html__( 'Your Elementor version is too old, Please update your Elementor plugin to at least %s Version', 'xstore-core' ), self::MINIMUM_ELEMENTOR_VERSION ),
            )
        );

    }

    /**
     * Add eight theme Widgets Category
     *
     * @since 2.0.0
     */
    function register_categories( $categories_manager ) {
	    $categories_manager->add_category(
            'eight_theme_general',
            array(
                'title' => __( 'XStore Widgets', 'xstore-core' ) . ' (60)',
                'icon' => 'fa fa-plug',
            )
        );
	
	    $categories_manager->add_category(
		    'eight_theme_deprecated',
		    array(
			    'title' => __( 'XStore Widgets (deprecated)', 'xstore-core' ),
			    'icon' => 'fa fa-plug',
		    )
	    );

    }

    /**
     * Ajax select2 control handler
     *
     * @since 2.3.9
     * @return object
     */
    function _maybe_post_terms() {
        
        check_ajax_referer( 'select2_ajax_control', 'security' );

        if ( isset( $_POST['options']['post_type'] ) ) {
            $return = $this->process_post_ajax_select_control();
        }

        wp_send_json( $return );
    }

    /**
     * Get post type for ajax select2 control
     *
     * @since 2.3.9
     * @return return posttype
     */
    function process_post_ajax_select_control() {

        $return = array();

        $args = array( 
            'post_status'           => 'publish', 
            'post_type'             => $_POST['options']['post_type'],
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => apply_filters('etheme_elementor_post_ajax_results_per_page', 20, $_POST['options']['post_type'])
        );

        // Search
        if ( isset( $_POST['search'] ) && '' != $_POST['search'] ) {
            $args['s'] =  sanitize_text_field( $_POST['search'] );

            $search = $this->_post_get_data_select_control( $args );

            unset( $args['s'] );
        }    

        // Get selected id
        if ( isset( $_POST['id'] ) ) {
            $args['post__in'] =  $_POST['id'];

            $selected = $this->_post_get_data_select_control( $args );
        }

        // Get old options again
        if ( isset( $_POST['old_option'] ) && '' != $_POST['old_option'] ) {
            $args['post__in'] =  $_POST['old_option'] ;

            $old_option = $this->_post_get_data_select_control( $args );
        }

        if ( isset( $selected ) ) {
            return $selected;
        }

        if ( isset( $old_option ) && is_array( $old_option ) && isset( $search ) && is_array( $search ) ) {
            return $old_option + $search;
        } elseif ( isset( $search ) && is_array( $search ) ) {
            return $search;
        } elseif ( isset( $old_option ) && is_array( $old_option ) ) {
            return $old_option;
        }

    }

    protected function _post_get_data_select_control( $args ) {
        $return = array();

        $search_results = new \WP_Query( $args );

        if( $search_results->have_posts() ) :

            while( $search_results->have_posts() ) : $search_results->the_post();   

                $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;

                $return[$search_results->post->ID] = $title . ' (id - ' . $search_results->post->ID . ')' .
                     (( $search_results->post->post_type == 'product_variation' ) ? ' ' . esc_html__('variation', 'xstore-core') : '' );

            endwhile;

            wp_reset_postdata();

        endif;

        return $return;
    }

    /**
     * Advanced tabs widget
     * 
     * @since 2.3.9
     * @return return tab content
     */
    function et_advanced_tab() {
        // check nonce
    	check_ajax_referer( 'etheme_advancedtabnonce', 'security' );
        // sanitizing
    	$tab_id    = isset( $_POST['tabid'] )    ? sanitize_key( $_POST['tabid'] )    : null;
    	$data_json = isset( $_POST['tabjson'] )  ? $_POST['tabjson']   : null;

        // simple check
    	if ( null === $tab_id ) {
    		wp_send_json_error( array( 'Do not change html via inspect element :)' ) );
    	}

        // Json data
    	if ( is_string( $data_json ) && ! empty( $data_json ) ) {
    		$data_json = json_decode( wp_unslash( $data_json ) , true );
    	}

    	$view = new View;
    	$Products_Shortcode = Products_Shortcode::get_instance();

    	$out = $view->advanced_tabs_ajax(
    		array(
    			'tabs'  				=> $data_json,
    			'Products_Shortcode'  	=> $Products_Shortcode,
    			'is_preview'  			=> ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ),
    		)
    	);

        echo $out;

        exit();

    }
    
    function etheme_elementor_lazy_load() {
	    check_ajax_referer( 'etheme_'.$_POST['widgetType'].'_nonce', 'security' );
     
	    $settings = json_decode( wp_unslash( $_POST['query'] ) , true );
	    $params_extra = array();
	
	    if ( isset($_POST['attr']['offset']) )
		    $params_extra['offset'] = $_POST['attr']['offset'] + ( ( $_POST['attr']['paged'] - 1 ) * $_POST['attr']['posts-per-page'] );
	
	    $query_args = array_merge(array(
		    'ignore_sticky_posts' => 1,
		    'no_found_rows' => 1,
		    'paged' => $_POST['attr']['paged'],
		    'posts_per_page' => $_POST['attr']['posts-per-page'],
	    ), $params_extra);
	    
	    $instance = false;
	    switch ($_POST['widgetType']) {
            case 'product-list':
	            $instance = \ETC\App\Controllers\Elementor\General\Product_List::get_instance();
	            $posts = \ETC\App\Controllers\Elementor\General\Product_List::get_query($settings, $query_args);
                break;
		    case 'product-grid':
			    $instance = \ETC\App\Controllers\Elementor\General\Product_Grid::get_instance();
			    $posts = \ETC\App\Controllers\Elementor\General\Product_Grid::get_query( $settings, $query_args);
			    break;
		    case 'posts':
		    case 'posts-tabs':
			    $instance = \ETC\App\Controllers\Elementor\General\Posts::get_instance();
			    
			    $posts = \ETC\App\Controllers\Elementor\General\Posts::get_query( $settings, $query_args);
			    break;
		    case 'posts-chess':
			    $instance = \ETC\App\Controllers\Elementor\General\Posts_Chess::get_instance();
			    $posts = \ETC\App\Controllers\Elementor\General\Posts_Chess::get_query( $settings, $query_args);
			    break;
        }
        
        if ( !$instance ) return;
        
        if ( in_array($_POST['widgetType'], array('product-list', 'product-grid')) ) {
	        wc_set_loop_prop( 'columns', 4 );
	        wc_set_loop_prop( 'etheme_elementor_product_widget', true );
	        wc_set_loop_prop( 'is_shortcode', true );
        }
        
        $data = [];
        $_i=0;
        
	    $new_limit = isset($_POST['attr']['limit']) ? $_POST['attr']['limit'] : 0;
	    if ( $_POST['attr']['paged'] > 1 ) {
		    $loaded_posts = ($_POST['attr']['paged'] - 1) * $_POST['attr']['posts-per-page'];
		    if ( $_POST['attr']['limit'] > $loaded_posts ) {
			    $new_limit = $_POST['attr']['limit'] - $loaded_posts;
		    }
	    }
	    
        ob_start();
	    if ( $posts && $posts->have_posts() ) {
		
		    while ( $posts->have_posts() ) {
			    $posts->the_post();
			
//			    if ( isset( $_POST['attr']['limit'] ) ) {
//				    if ( $_i >= $_POST['attr']['limit'] ) {
//					    break;
//				    }
//				    $_i++;
//			    }
			    if ( $new_limit > 0 ) {
				    if ( $_i >= $new_limit ) {
					    break;
				    }
				    $_i++;
			    }
			    if ( in_array($_POST['widgetType'], array('product-list', 'product-grid')) ) {
				    $instance->get_content_product(
					    json_decode( wp_unslash( $_POST['postSettings'] ), true )
				    );
			    }
			    else {
                    $instance->get_content_post(
	                    json_decode( wp_unslash( $_POST['postSettings'] ), true )
                    );
                }
		    }
	    }
	
	    else {
		    echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
		         esc_html__('No items were found matching your selection.', 'xstore-core') .
		         '</div>';
	    }
	
	    if ( in_array($_POST['widgetType'], array('product-list', 'product-grid')) ) {
		    wc_reset_loop();
	    }
	    
	    $data['content'] = json_encode(ob_get_clean());
	    
	    if ( $_POST['loading_type'] == 'pagination' ) {
	    	ob_start();
		    $is_rtl = is_rtl();
		    $left_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
		                  '<path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>' .
		                  '</svg>';
		    $right_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
		                   '<path d="M17.88 11.496l-10.728-11.304c-0.264-0.264-0.672-0.264-0.96-0.024-0.144 0.12-0.216 0.312-0.216 0.504 0 0.168 0.072 0.336 0.192 0.48l10.272 10.8-10.272 10.8c-0.12 0.12-0.192 0.312-0.192 0.504s0.072 0.36 0.192 0.504c0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.456-0.192l0.024-0.024 10.752-11.328c0.264-0.264 0.24-0.672 0-0.936z"></path>' .
		                   '</svg>';
		    
		    echo paginate_links( array(
                 'base'      => esc_url_raw( add_query_arg( 'etheme-'.$_POST['widgetType'].'-'.$_POST['widgetId'].'-page', '%#%', $_POST['permalink'] ) ),
			    'format'    => '?etheme-'.$_POST['widgetType'].'-' . $_POST['widgetId'] . '-page=%#%',
			    'add_args'  => false,
			    'current'   => $_POST['attr']['paged'],
			    'total'     => $_POST['totalPages'],
			    'prev_text' => $is_rtl ? $right_arrow : $left_arrow,
			    'next_text' => $is_rtl ? $left_arrow : $right_arrow,
			    'type'      => 'list',
			    'end_size'  => 2,
			    'mid_size'  => 2,
		    ) );
		    $data['pagination'] = json_encode(ob_get_clean());
	    }
	
	    wp_reset_postdata();
	
	    wp_send_json($data);
	    // die();
    }

    /**
     * Include modules
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function register_modules() {

        $modules = self::module_args();
        foreach ( $modules as $module ) {
            foreach ( $module as $class ) {
                new $class();
            }
        }

    }

    /**
     * Include widgets files and register them
     *
     * @since 2.0.0
     * @log last changes in 4.3.1 - since Elementor 3.5.0 uses register instead of register_widget_type
     *
     * @access public
     */
    public function register_widgets( $widgets_manager ) {

        $args = self::widgets_args();
        foreach ( $args as $widget_classes ) {
            foreach ( $widget_classes as $class ) {
	            $widgets_manager->register( new $class() );
            }
        }

    }

    /**
     * Register controls
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_controls( $controls_manager ) {

        $args = self::controls_args();
        foreach ( $args as $control_type => $control ) {
            $controls_manager->register_control( $control_type, new $control['class']() );
        }
    }

    public function etc_elementor_icons( $icons_library ) {

        $icons_library['xstore-icons'] = [
            'name' => 'xstore-icons',
            'label' => __( 'XStore Icons', 'xstore-core' ),
            'url' => self::get_et_asset_url( ( get_theme_mod('bold_icons', 0) ? 'bold' : 'light') ),
            'enqueue' => [ self::get_et_asset_url( 'xstore-icons' ) ],
            'prefix' => 'et-',
            'displayPrefix' => 'et-icon',
            'labelIcon' => 'et-icon et-star-o',
            'ver' => '1.3.0',
            'fetchJson' => self::get_et_asset_url( 'light', 'js', false ),
            'native' => true,
        ];

        return $icons_library;
    }

    private static function get_et_asset_url( $filename, $ext_type = 'css', $add_suffix = true ) {
        // static $is_test_mode = null;
        // if ( null === $is_test_mode ) {
        //     $is_test_mode = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS;
        // }
        $url = ET_CORE_URL . 'app/assets/lib/xstore-icons/' . $ext_type . '/' . $filename;
        // if ( ! $is_test_mode && $add_suffix ) {
        //     $url .= '.min';
        // }
        return $url . '.' . $ext_type;
    }

    /**
     * Add xstore custom fonts group
     * 
     * @since    2.5.0
     * @param new fonts
     */
    public function add_custom_font_group( $font_groups ) {
        // Get available fonts
        $uploaded_fonts = get_option( 'etheme-fonts', false );

        if ( ! $uploaded_fonts ) {
            return $font_groups;
        }

        $new_group =  array(
            'xstore' => 'XStore Fonts'
        );

        return array_merge( $font_groups, $new_group );
    }

    /**
     * add xstore custom font
     *
     * @since    2.5.0
     */
    public function add_custom_font( $additional_fonts ) {
        $uploaded_fonts = get_option( 'etheme-fonts', false );

        if ( false == $uploaded_fonts || is_null( $uploaded_fonts ) ) {
            return $additional_fonts;
        }

        $new_fonts = array();

        foreach ( $uploaded_fonts as $font ) {
            $new_fonts[$font['name']] = 'xstore';
        }

        return array_merge( $additional_fonts ,$new_fonts );
    }

    /**
     * Register the stylesheets for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_editor_styles() {

        wp_enqueue_style(
            'et-core-elementor-style',
            ET_CORE_URL . 'app/assets/css/elementor-editor.css',
            array(),
            ET_CORE_VERSION,
            'all'
        );

        wp_enqueue_style(
            'et-core-eight_theme-elementor-icon',
            ET_CORE_URL . 'app/assets/css/eight_theme-elementor-icon.css',
            array(),
            ET_CORE_VERSION,
            'all'
        );

        if ( get_theme_mod( 'google_map_api', '' ) ) {
            $this->google_map_api = get_theme_mod( 'google_map_api', '' );
        }

        if( $this->google_map_api != '' ) {
            $url = 'https://maps.googleapis.com/maps/api/js?key='. $this->google_map_api .'&language='.get_locale();
        } else {
            $url = 'https://maps.googleapis.com/maps/api/js?language='.get_locale();
        }

        wp_enqueue_script( 
            'etheme-google-map-admin-api', 
            $url, 
            ['elementor-editor'], 
            ET_CORE_VERSION, 
            true  
        );

        wp_enqueue_script( 
            'etheme-google-map-admin', 
            ET_CORE_URL . 'app/assets/js/google-map-admin.js', 
            array( 'etheme-google-map-admin-api' ), 
            ET_CORE_VERSION, 
            true 
        );

        wp_enqueue_script( 
            'et-elementor-editor', 
            ET_CORE_URL . 'app/assets/js/editor-before.js', 
            array(),
            ET_CORE_VERSION 
        );

        // icons 
        // wp_enqueue_script(
        //     'font-awesome-4-shim',
        //     self::get_fa_asset_url( 'v4-shims', 'js' ),
        //     [],
        //     ELEMENTOR_VERSION
        // );
        // wp_enqueue_style(
        //     'font-awesome-5-all',
        //     self::get_fa_asset_url( 'all' ),
        //     [],
        //     ELEMENTOR_VERSION
        // );
        // wp_enqueue_style(
        //     'font-awesome-4-shim',
        //     self::get_fa_asset_url( 'v4-shims' ),
        //     [],
        //     ELEMENTOR_VERSION
        // );

    }

    /**
     * Register the stylesheets for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_frontend_styles() {
    	if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()){
		    wp_enqueue_style( 'et-core-elementor-editor-style', ET_CORE_URL . 'app/assets/css/elementor-editor-preview.css', ['editor-preview'], '1.0' );
	    }
    }

    /**
     * Register the JavaScript for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts() {
    	
    	$locate_lang = get_locale();

        if ( get_theme_mod( 'google_map_api', '' ) ) {
            $this->google_map_api = get_theme_mod( 'google_map_api', '' );
        }


        if( $this->google_map_api != '' ) {
            $url = 'https://maps.googleapis.com/maps/api/js?key='. $this->google_map_api .'&language='.$locate_lang;
        } else {
            $url = 'https://maps.googleapis.com/maps/api/js?language='.$locate_lang;
        }

        wp_register_script( 
            'etheme-google-map-api', 
            $url, 
            array(), 
            ET_CORE_VERSION, 
            true  
        );

        wp_localize_script( 
            'etheme-google-map-api', 
            'etheme_google_map_loc', 
            array( 'plugin_url' => ET_CORE_URL )
        );

        wp_register_script( 
            'etheme-google-map', 
            ET_CORE_URL . 'app/assets/js/google-map.js', 
            array( 'etheme-google-map-api' ), 
            ET_CORE_VERSION, 
            true 
        );
	
    }

}