<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

class ETheme_Version_Check {

    private $current_version = '';
    private $new_version = '';
    private $theme_name = '';
    private $api_url = '';
    private $ignore_key = 'etheme_notice';
    public $information;
    public $api_key;
    public $url = '';
    public $notices;
    private $theme_id = 15780546;
    public $is_subscription = false;
    public $activated_data = array();
    public $is_license = true;


    function __construct($update_transient = true) {
        $theme_data = wp_get_theme('xstore');
	    $this->activated_data = $activated_data = get_option( 'etheme_activated_data' );
        $this->current_version = $theme_data->get('Version');
        $this->theme_name = strtolower($theme_data->get('Name'));
        $this->api_url = apply_filters('etheme_protocol_url', ETHEME_API);
	    $this->url = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/demo/xstore/change-log.php');
        $this->api_key = ( ! empty( $activated_data['api_key'] ) ) ? $activated_data['api_key'] : false;
	    $this->is_subscription = ( isset($activated_data['item']) && isset($activated_data['item']['license']) && $activated_data['item']['license'] == '8theme-subscription' );

        add_action('admin_init', array($this, 'dismiss_notices'));
        add_action('admin_notices', array($this, 'show_notices'), 50 );

	    add_action( 'wp_ajax_et_support_refresh', array($this, 'et_support_refresh') );

        if( ! etheme_is_activated() ) {
            #$this->activation_notice();
            return;
        }

        if( $this->is_update_available() ) {
            if ( $this->major_update( 'both' ) ) add_action( 'admin_head', array( $this, 'major_update_holder' ) );
            //$this->update_notice();
        }

        add_action( 'switch_theme', array( $this, 'update_dismiss' ) );

        add_action( 'current_screen', array( $this, 'api_results_init' ) );

	    if ($update_transient) {
		    add_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );
		    add_filter( 'pre_set_site_transient_update_themes', array( $this, 'set_update_transient' ) );
		    //add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);
	    }

    }
    
    public function api_results_init( $current_screen ) {
        if ( $current_screen->base !== 'woocommerce_page_wc-status' ) {
            add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);
        }
        
    }

    public function activation_page() {
        ?>
            
            <?php if ( etheme_is_activated() ): ?>
                <?php 
                    $activated_data = get_option( 'etheme_activated_data' );
                    $purchase = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';
		            $supported_until = ( isset( $activated_data['item'] ) && isset( $activated_data['item']['supported_until'] ) && ! empty( $activated_data['item']['supported_until'] ) ) ? $activated_data['item']['supported_until'] : '';

		            ?>

                    <p><?php esc_html_e('Your theme is activated! Now you have lifetime updates, top-notch 24/7 live support and much more.', 'xstore'); ?></p>
                    <?php $this->process_form(); ?>
                    <p class="etheme-purchase"><i class="et-admin-icon et-key"></i> <span><?php echo substr($purchase, 0, -8) . '********'; ?></span></p>

                    <?php //if (!$this->is_subscription): ?>
                        <span class="et-button et_theme-deactivator no-loader last-button"><?php esc_html_e( 'Unregister', 'xstore' ); ?></span>
                    <?php //endif; ?>


                        <?php $this->support_status($supported_until); ?>
		                <?php if (!$this->is_subscription): ?>
                            <p class="et-message et-info">
                            <?php //esc_html_e('One standard license is valid only for 1 project (1 live and 1 staging websites of the same project). Running multiple projects on a single license is a copyright violation. When moving a site from one domain to another please deactivate theme first.', 'xstore'); ?>
                            <?php esc_html_e('Due to the Envato\'s license policy one standard license is valid only for 1 project.', 'xstore'); ?>
                                <br>
                                <?php esc_html_e('Running multiple projects on a single license is a copyright violation.', 'xstore');?>
                                <a style="color: #2271b1;" href="https://themeforest.net/licenses/terms/regular" target="_blank">More info.</a>
<!--                                <br> If you want to use this theme more than one project or unlimited, please check our <a href="https://www.8theme.com/woocommerce-themes/#price-section-anchor" target="_blank">8theme's subscription plan.</a>-->

                            </p>
		                <?php endif; ?>
            <?php else: ?>
        
                <p class=""><?php echo sprintf(esc_html__('Your product should be activated so you may get the access to all the XStore %1$1sdemos%2$2s, auto theme %3$3s updates %4$4s and included premium %5$5splugins%6$6s. The instructions below in toggle format must be followed exactly.', 'xstore'), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>'); ?></p>
                <?php $this->process_form(); ?>

                <form class="xstore-form" method="post">
                    <input type="text" name="purchase-code" placeholder="Example: f20b1cdd-ee2a-1c32-a146-66eafea81761" id="purchase-code" />
                    <input class="et-button et-button-green no-transform no-loader active" name="xstore-purchase-code" type="submit" value="<?php esc_attr_e( 'Register', 'xstore' ); ?>" />
                    <p <?php if(!$this->is_license ) echo 'style = "color: #ff0000;"'; ?> >
                        <input type="checkbox" id="form-license" name="form-license"> <label for="form-license">Confirm that, according to the Envato's license policy one standard license is valid only for 1 project.
                            Running multiple projects on a single license is a copyright violation.</label> <a style="color: #2271b1;" href="https://themeforest.net/licenses/terms/regular" target="_blank">More info.</a>
                    </p>
                    <p class="et-message et-info"><?php esc_html_e( 'By clicking Register, you agree that your purchase code and your user data will be stored by 8theme.com', 'xstore' ); ?></p>

                </form>

            <?php endif; ?>
        <?php 
    }

    public function show_notices() {
        global $current_user;
        $user_id = $current_user->ID;
        if( ! empty( $this->notices ) ) {
            foreach ($this->notices as $key => $notice) {
                if ( ! get_user_meta($user_id, $this->ignore_key . $key) ) {
                    echo '<div class="et-message et-info">' . $notice['message'] . '</div>';
                }
            }
        }
    }

    public function dismiss_notices() {
        global $current_user;
        $user_id = $current_user->ID;
        if ( isset( $_GET['et-hide-notice'] ) && isset( $_GET['_et_notice_nonce'] ) ) {
            if ( ! wp_verify_nonce( $_GET['_et_notice_nonce'], 'etheme_hide_notices_nonce' ) ) {
                return;
            }

            add_user_meta($user_id, $this->ignore_key . '_' . $_GET['et-hide-notice'], 'true', true);
        }
    }

    public function setup_notice() {
        $this->notices['_setup'] = array(
            'message' => '
                <p><strong>Welcome to XStore</strong> – You‘re almost ready to start selling :)</p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=xstore-setup' ) . '" class="button-primary">Run the Setup Wizard</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'setup' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">Skip Setup</a></p>
            '
        );
    }

    public function activation_notice() {
        $this->notices['_activation'] = array(
            'message' => '
                <p><strong>You need to activate XStore</strong></p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=xstore-setup' ) . '" class="button-primary">Activate theme</a></p>
            '
        );
    }

    public function update_notice() {
        if( isset( $_GET['_wpnonce'] )) return;

        $this->notices['_update'] = array(
            'message' => '
                    <p>There is a new version of ' . ETHEME_THEME_NAME . ' Theme available.</p>' . $this->major_update( 'msg-b' ) . '
                    <p class="submit"><a href="' . admin_url( 'update-core.php?force-check=1&theme_force_check=1' ) . '" class="button-primary">Update now</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'update' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">Dismiss</a></p>
                ',
        );
    }

    private function api_get_version() {

        $raw_response = wp_remote_get($this->api_url . '?theme=' . ETHEME_THEME_SLUG);
        if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
            $response = json_decode($raw_response['body'], true);
            if(!empty($response['version'])) $this->new_version = $response['version'];
        }
    }

    public function update_dismiss() {
        global $current_user;
        #$user_id = $current_user->ID;
        #delete_user_meta($user_id, $this->ignore_key);
    }


    public function update_transient($value, $transient) {
        // if(isset($_GET['theme_force_check']) && $_GET['theme_force_check'] == '1') return false;
        if(isset($_GET['force-check']) && $_GET['force-check'] == '1') return false;
        return $value;
    }


    public function set_update_transient($transient) {
	    $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

	    if (
		    count($xstore_branding_settings)
		    && isset($xstore_branding_settings['control_panel'])
		    && isset($xstore_branding_settings['control_panel']['hide_updates'])
		    && $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
	    ){
		    return $transient;
	    }
    
        $this->check_for_update();

        if( isset( $transient ) && ! isset( $transient->response ) ) {
            $transient->response = array();
        }

        if( ! empty( $this->information ) && is_object( $this->information ) ) {
            if( $this->is_update_available() ) {
                $transient->response[ $this->theme_name ] = json_decode( json_encode( $this->information ), true );
            }
        }

        remove_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );

        return $transient;
    }


    public function api_results($result, $action, $args) {
    
        $this->check_for_update();

        if( isset( $args->slug ) && $args->slug == $this->theme_name && $action == 'theme_information') {
            if( is_object( $this->information ) && ! empty( $this->information ) ) {
                $result = $this->information;
            }
        }

        return $result;
    }


    protected function check_for_update() {
        $force = false;

        // if( isset( $_GET['theme_force_check'] ) && $_GET['theme_force_check'] == '1') $force = true;

        if( isset( $_GET['force-check'] ) && $_GET['force-check'] == '1') $force = true;

        // Get data
        if( empty( $this->information ) ) {
            $version_information = get_option( 'xstore-update-info', false );
            $version_information = $version_information ? $version_information : new stdClass;
            
            $this->information = is_object( $version_information ) ? $version_information : maybe_unserialize( $version_information );
            
        }
        
        $last_check = get_option( 'xstore-update-time' );
        if( $last_check == false ){ 
            update_option( 'xstore-update-time', time(), 'no' );
        }
        
        if( time() - $last_check > 172800 || $force || $last_check == false ){
            
            $version_information = $this->api_info();

            if( $version_information ) {
                update_option( 'xstore-update-time', time(), 'no' );
                
                $this->information          = $version_information;
                $this->information->checked = time();
                $this->information->url     = $this->url;
                $this->information->package = $this->download_url();

            }

        }
        
        // Save results
        update_option( 'xstore-update-info', $this->information );
    }

    public function api_info() {
        $version_information = new stdClass;

        $response = wp_remote_get( $this->api_url . 'info/' . $this->theme_name . '?plugin=et-core' );
        $response_code = wp_remote_retrieve_response_code( $response );

        if( $response_code != '200' ) {
            return false;
        }

        $response = json_decode( wp_remote_retrieve_body( $response ) );
        if( ! isset( $response ) || ! isset( $response->new_version ) || empty( $response->new_version ) ) {
            return $version_information;
        } 

        $version_information = $response;

        return $version_information;
    }

    public function is_update_available() {
        return version_compare( $this->current_version, $this->release_version(), '<' );
    }

    public function download_url() {
        $url = $this->api_url . 'files/get/' . $this->theme_name . '.zip?token=' . $this->api_key;
        return apply_filters( 'etheme_theme_url', $url );
    }
    public function release_version() {
        $this->check_for_update();

        if ( isset( $this->information ) && isset( $this->information->new_version ) ) {
            return $this->information->new_version;
        }
    }


    public function activate( $purchase, $args ) {

        $data = array(
            'api_key' => $args['token'],
            'theme' => ETHEME_PREFIX,
            'purchase' => $purchase,
        );

        foreach ( $args as $key => $value ) {
           $data['item'][$key] = $value;
        }

        update_option( 'envato_purchase_code_15780546', $purchase );
        update_option( 'etheme_activated_data', maybe_unserialize( $data ) );
        update_option( 'etheme_is_activated', true );
    }

    public function process_form() {

        if (
	        isset( $_POST['xstore-purchase-code'] )
	        && ! empty( $_POST['xstore-purchase-code'] )
            && ! isset($_POST['form-license'])
            && empty( $_POST['form-license'] )
        ){
	        $this->is_license = false;
        }

        if(
                isset( $_POST['xstore-purchase-code'] )
                && ! empty( $_POST['xstore-purchase-code'] )
                && isset($_POST['form-license'])
                && ! empty( $_POST['form-license'] )
        ) {
            $code = trim( $_POST['purchase-code'] );

            if( empty( $code ) ) {
               echo  '<p class="et-message et-error">Oops, the code is missing, please, enter it to continue.</p>';
                return;
            }

            $response = wp_remote_get( $this->api_url . 'activate/' . $code . '?envato_id='. $this->theme_id .'&domain=' .$this->domain() );
            if ( ! $response ) {
                echo  '<p class="et-message et-error">API request call error. Can not connect to 8theme.com</p>';
                return;
            }
            $response_code = wp_remote_retrieve_response_code( $response );

            if( $response_code != '200' ) {

	            if( is_wp_error( $response ) ) {
		            echo '<p class="et-message et-error">' . $response->get_error_message() . '</p>';
	            }

                if (!$response_code){
	                echo  '<p class="et-message et-error">API request call error. Common problem caused by SSL certificate. Please check it  <a href="https://www.sslshopper.com/ssl-checker.html" target="_blank" rel="nofollow">here</a>. Contact your server provider in case your certificate does not exist or has some errors.</p>';
	                return;
                }
                echo  '<p class="et-message et-error">API request call error. Response code - <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank" rel="nofollow">' . $response_code . '</a></p>';
                return;
            }

            $data = json_decode( wp_remote_retrieve_body($response), true );

            if( isset( $data['error'] ) ) {
               echo  '<p class="et-message et-error">' . $data['error'] . '</p>';
                return;
            } 

            if( ! $data['verified'] ) {
               echo  '<p class="et-message et-error">Sorry, the code is incorrect, try again.</p>';
                return;
            }

            $this->activate( $code, $data );

            echo '<div class="purchase-default"><p class="etheme-purchase"><i class="et-admin-icon et-key"></i> <span>' . substr($code, 0, -8) . '********' . '</span></p>
                <span class="et-button et-button-active et_theme-deactivator no-loader last-button">' . esc_html__( 'Unregister', 'xstore' ) . '</span>
                    <p class="et-message et-error">
                    ' . esc_html__('One standard license is valid only for 1 website. Running multiple websites on a single license is a copyright violation. When moving a site from one domain to another please deactivate the theme first.', 'xstore') . '
                </p></div>';
            sleep(2);
            // if ( !class_exists('ETheme_Import') )
            //     wp_safe_redirect(admin_url( 'admin.php?page=et-panel-welcome' ));
            // else 
            //     wp_safe_redirect(admin_url( 'admin.php?page=et-panel-demos' ));
            wp_safe_redirect(admin_url( 'admin.php?page=et-panel-demos&after_activate=true&et_clear_plugins_transient' ));
        }
    }
    
    public function domain() {
        $domain = get_option('siteurl'); //or home
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
        return urlencode($domain);
    }

    public function major_update( $type = 'msg' ) {

        // ! major update versions
        $versions = array( '4.0', '4.18', '5.0', '6.0', '7.0', '8.0', '9.0', '10.0', '11.0', '12.0' );

        // ! current release version
        $release = $this->release_version();

        if ( ! in_array( $release , $versions ) ) return;

	    $message = esc_html__( 'This is major theme update! Please, do the backup of your files and database before proceed to update. If you use WooCommerce plugin make sure that its latest version.', 'xstore' );

        switch ( $type ) {
            case 'msg':
                $return = $message;
                break;
            case 'msg-b':
                $return = '<p class="et_major-update">' . $message . '</p>';
                break;
            case 'ver':
                $return = $release;
                break;
            case 'both':
                $return['msg'] = $message;
                $return['ver'] = $release;
                break;
            default:
                $return = $release;
                break;
        }
        return $return;
    }

    public function major_update_holder() {
        $major_update = $this->major_update( 'both' );
        echo '<span class="hidden et_major-version" data-version="' . $major_update['ver'] . '" data-message="' . $major_update['msg'] . '"></span>';
    }

    public function et_support_refresh(){
	    $activated_data = get_option( 'etheme_activated_data' );
	    $purchase = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';

	    if (!$purchase){
		    wp_send_json( array( 'status' => 'error', 'msg' => __('Invalid purchase code', 'xstore') ) );
	    }

	    $remote_response = wp_remote_get( $this->api_url . 'support/' . $purchase . '?envato_id='. $this->theme_id );
	    $response_code = wp_remote_retrieve_response_code( $remote_response );

	    if( $response_code != '200' ) {
		    wp_send_json( array( 'status' => 'error', 'msg' => __('API request call error. Can not connect to 8theme.com', 'xstore') ) );
	    }

	    $remote_response = json_decode( wp_remote_retrieve_body( $remote_response ) );

	    if (isset($remote_response->error)){
		    wp_send_json( array( 'status' => 'error', 'msg' => $remote_response->error ) );
        }

	    $activated_data['item']['supported_until'] = $remote_response->supported_until;
	    update_option('etheme_activated_data', $activated_data);

        wp_send_json( array('status' => 'success', 'msg' => __('Successful updated', 'xstore'), 'html' => $this->support_status($remote_response->supported_until, false) ) );
    }

    public function get_support_day_left(){
	    $activated_data = get_option( 'etheme_activated_data' );
	    $supported_until = ( isset( $activated_data['item'] ) && isset( $activated_data['item']['supported_until'] ) && ! empty( $activated_data['item']['supported_until'] ) ) ? $activated_data['item']['supported_until'] : '';
	    $daysleft = round(((( strtotime($supported_until) - time() )/24)/60)/60);
	    return $daysleft;
    }

    public function get_support_status(){

        if (
                $this->is_subscription
                && isset($this->activated_data['item'])
                && isset($this->activated_data['item']['subscription_type'])
                && $this->activated_data['item']['subscription_type'] == 'lifetime'){
	        return 'lifetime';
        }

	    $daysleft = $this->get_support_day_left();

	    if ($daysleft <= 30 && $daysleft > 0) {
		    $status = 'expire-soon';
	    }else if ($daysleft <= 0) {
		    $status = 'expired';
	    } else {
		    $status = 'active';
	    }
	    return $status;
    }

	public function support_status($supported_until, $echo = true) {
        $support  = $this->get_support_status();
		$daysleft = $this->get_support_day_left();

		$left = $daysleft . ' ' . _nx( 'day left', 'days left', $daysleft, 'Support day/days left', 'xstore' );

		$icon = '<span style="cursor: pointer; font-size: 18px; padding-top: 3px; padding-left: 5px;" class="et_support-refresh dashicons dashicons-image-rotate"></span>';
		$renew = __('You can renew your support ', 'xstore') . '<a href="https://themeforest.net/item/xstore-responsive-woocommerce-theme/15780546" target="_blank">' . __('here', 'xstore') . '</a>';

		if ($support == 'expire-soon') {
			$status = 'et-warning';
			$left .= $icon . '</br>' . $renew;
		}else if ($support == 'expired') {
			$status = 'et-error';
			$left = __('Expired', 'xstore');
			$left .= $icon . '</br>' . $renew;
		} else if($support == 'lifetime'){
			$status = 'et-notice';
			$left = 'lifetime' . $icon;
        } else {
			$status = 'et-notice';
			$left .= $icon;
		}

		if ($echo){
			printf(
				'<div class="et_support-block"><p class="et_support-status et-message %s">%s %s </p><p class="temp-msg"></p></div>',
				$status,
				__('Support status:', 'xstore'),
				$left
			);
		} else {
		    return sprintf(
			    '<div class="et_support-block"><p class="et_support-status et-message %s">%s %s </p><p class="temp-msg"></p></div>',
			    $status,
			    __('Support status:', 'xstore'),
			    $left
		    );
        }
	}

}

if(!function_exists('etheme_check_theme_update')) {
    add_action('init', 'etheme_check_theme_update');
    function etheme_check_theme_update() {
        new ETheme_Version_Check();
    }
}