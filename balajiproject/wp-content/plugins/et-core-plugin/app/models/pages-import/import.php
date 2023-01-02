<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

/**
 * Etheme Single pages Import.
 *
 * Enqueue sctipts and templates.
 * Declare callbacks for import ajax functions.
 *
 * @since   2.2.0
 * @version 1.0.2
 * @var     $post {array} list of all $_POST data.
 */
class Etheme_Single_pages_Import {
    // ! Declare default variables
    public $post  = array();
    private $import_url = 'http://8theme.com/import/xstore-demos/';
    private $loaded_image = array();
    
    // ! Main construct/ setup variables
    function __construct(){
        $this->post = $_POST;
    }

    /**
     * Add actions.
     *
     * Actions to scripts and ajax actions for single page import.
     *
     * @since   2.2.0
     * @version 1.0.0
     */
    public function actions(){
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 1210 );
        add_action( 'wp_ajax_et_ajax_single_product_import_template', array( $this, 'template' ) );
        add_action( 'wp_ajax_et_ajax_load_page_content', array( $this, 'load_page_content' ) );
        add_action( 'wp_ajax_et_ajax_import_page_sliders', array( $this, 'import_page_sliders' ) );
    }

    /**
     * Add script and styles to Wordpress Customizer
     *
     * @since   2.2.0
     * @version 1.0.0
     */
    public function scripts(){
        $screen = get_current_screen(); 
        if ( $screen->post_type != 'page' ) {
            return;
        }
        wp_enqueue_style('etheme_admin_import-css', ET_CORE_URL.'app/models/pages-import/css/import.css');
        wp_enqueue_script('etheme-pages-import', ET_CORE_URL . 'app/models/pages-import/js/import.js', array(
            'jquery'
        ));
    }

    /**
     * Get popup template.
     *
     * Used to display popup html.
     * callback for et_ajax_single_product_import_template().
     *
     * @since   2.2.0
     * @version 1.0.0
     * @return  {json} popup html.
     */
    public function template() {
        ob_start();
            require_once( ET_CORE_DIR . 'app/models/pages-import/template-parts/' . $this->post['template'] . '.php' );
        $response['template'] = ob_get_clean();
        wp_send_json( $response );
    }

    /**
     * Load page content.
     *
     * Get page content based on given page url
     * callback for et_ajax_load_page_content().
     *
     * @since   2.2.0
     * @version 1.0.0
     * @return  {json} page html/errors.
     */
    public function load_page_content(){
        $response = array(
            'succes' => true
        );
        $url = parse_url( $this->post['url'] );

        if ( isset( $url['query'] ) || isset( $url['fragment'] ) || strpos( $this->post['url'], 'https://xstore.8theme.com/' ) === false ) {
            $response['succes'] = false;
            $response['error']  = 'url-error';
            wp_send_json(json_encode($response));
        } else {
            $remote = wp_remote_get('https://www.8theme.com/pages-api/?url=' . $this->post['url']);

            if ( wp_remote_retrieve_response_code( $remote ) === 200 ){
                $body = wp_remote_retrieve_body( $remote );
                $body = json_decode($body, true);
                if ( ! isset( $body['error'] ) ) {
                    $body = $this->import_singular_images($body);
                    $body = $this->import_multiple_images($body);
                }
                $body = json_encode($body);
                wp_send_json($body);
            } else {
                $response['succes'] = false;
                $response['error']  = 'api-error';
                wp_send_json(json_encode($response));
            }
        }
    }

    /**
     * Import singular images.
     *
     * Import images saved in "id" format.
     * Replace old ids in content.
     *
     * @since   2.2.0
     * @version 1.0.1
     * @return  {array}
     */
    public function import_singular_images($body){
        $urls = $this->get_between_strings( ' img_import_url="', '"', $body['data'] );
        if ( count( $urls ) ) {
           foreach ($urls as $value) {
           	  if( in_array($value, $this->loaded_image) ) continue;
              $id = $this->import_media($value);
              if ( $id ) {
                $old_id = $this->get_between_strings( '="', '" img_import_url="' . $value . '"', $body['data'] );
                $body['data'] = str_replace( $old_id, $id, $body['data'] );
              }
	           $this->loaded_image[] = $value;
           }
        }
        return $body;
    }

    /**
     * Import multiple images.
     *
     * Import images saved in "id,id2,id3" format.
     * Replace old ids in content.
     *
     * @since   2.2.0
     * @version 1.0.1
     * @return  {array}
     */
    public function import_multiple_images($body){
        $urls = $this->get_between_strings( ' img_import_urls="', '"', $body['data'] );
        if ( count( $urls ) ) {
           foreach ( $urls as $value ) {
	           if( in_array($value, $this->loaded_image) ) continue;
                $ids     = explode( ',', $id );
                $new_ids = '';
                foreach ($ids as $v) {
                    if ( $v ) {
                        $image_id = $this->import_media($v);
                        if ( $image_id ) {
                            $new_ids .= $image_id . ',';
                        }
                    }
                }
                $old_id = $this->get_between_strings('="', '" img_import_urls="' . $value . '"', $body['data']);
                $body['data'] = str_replace( $old_id, $new_ids, $body['data'] );
	            $this->loaded_image[] = $value;
           }
        }
        return $body;
    }

    /**
     * Get data between selectors.
     *
     * @since   2.2.0
     * @version 1.0.0
     * @return  {array}
     */
    public function get_between_strings($start, $end, $str){
        $matches = array();
        $regex = "/$start([%.a-zA-Z0-9_-]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }

    /**
     * Import media by url.
     *
     * @since   2.2.0
     * @version 1.0.0
     * @return  {bool|integer}
     */
    public function import_media($url){
        $url    = urldecode($url);
        $desc   = 'imported image';
        $img_id = media_sideload_image( $url, 0, $desc, 'id' );
        if( is_wp_error( $img_id ) ){
            return false;
        } else {
            return $img_id;
        }
    }

    /**
     * Import sliders by url.
     *
     * @since   2.2.0
     * @version 1.0.1
     * @return  {bool|integer}
     */
    public function import_page_sliders(){

        $i = $this->post['i'];
        $i = intval( $i );
        if ( ! $i ) {
            $i = 0;
        }

        $zip_file = ( $i > 0 ) ? 'slider' . $i . '.zip' : 'slider.zip' ;

        $activated_data = get_option( 'etheme_activated_data' );
        $key = $activated_data['api_key'];

        if( ! $key || empty( $key ) ) return false;

        $slider_url = $this->post['url'];

        if ( strpos( $slider_url, 'https://xstore.8theme.com/demos/2/' ) !== false ) {
            $slider_url = str_replace( 'https://xstore.8theme.com/demos/2/', $this->import_url, $slider_url );
        } elseif( strpos( $slider_url, 'https://xstore.8theme.com/demos/' ) !== false ) {
            $slider_url = str_replace( 'https://xstore.8theme.com/demos/', $this->import_url, $slider_url );
        } else {
            $slider_url = str_replace( 'https://xstore.8theme.com/', $this->import_url, $slider_url );
        }

	    $slider_url .= 'wpb/';

        $slider_url = $slider_url . $zip_file;

        try {
            $zip_file = download_url( $slider_url );
        } catch( Exception $e ) {
            return false;
        }

        if(!class_exists('RevSlider')) return;

        $revapi = new \RevSlider();

        ob_start();

        $slider_result = $revapi->importSliderFromPost(true, true, $zip_file);

        ob_end_clean();

        if ($i!=0){
	        $i--;
	        $this->post['i'] = $i;
	        $this->import_page_sliders();
        }

        return $slider_result;
    }

    /**
     * is plugins enabled
     *
     * @since   2.2.0
     * @version 1.0.0
     * @return  {array}
     */
    public function is_plugins_enabled(){
        $plugins = array(
            'revslider',
            'contact-form-7',
            'mpc-massive'
        );

        $return   = array();
        $instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

        foreach ($plugins as $value) {
            $plugin = ( ( ! empty( $instance->plugins[$value]['is_callable'] ) && is_callable( $instance->plugins[$value]['is_callable'] ) ) || in_array( $instance->plugins[$value]['file_path'], (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $instance->plugins[$value]['file_path'] ) );

            if ( ! $plugin ) {
                $return[$value] = array(
                    'title'      => $instance->plugins[$value]['name'],
                    'text'       => ( $instance->is_plugin_installed( $value ) ) ? esc_html__( 'Activate', 'xstore-core' ) : esc_html__( 'Install & Activate', 'xstore-core' ),
                );
            }
        }
        return $return;
    }
}
        
$Etheme_Single_pages_Import = new Etheme_Single_pages_Import();
$Etheme_Single_pages_Import -> actions();