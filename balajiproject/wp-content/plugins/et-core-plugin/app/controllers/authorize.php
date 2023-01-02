<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;
use ETC\Core\Model;

/**
 * Import Authorize.
 *
 * @since      3.0.3
 * @version    1.0.0
 * @package    ETC
 * @subpackage ETC/Controller
 */
class Authorize extends Base_Controller {
	/**
	 * Authorization settings
	 *
	 * @since   3.0.3
	 * @version 1.0.0
	 */
	private $settings = array();

	/**
	 * Add actions/filters
	 *
	 * @since   3.0.3
	 * @version 1.0.0
	 */
	public function hooks() {
		if (!count($this->get_settings())) return;

		add_action('template_redirect', array($this, 'api_request'), 20);
		add_action( 'woocommerce_login_form_end', function() {
			$is_checkout = apply_filters( 'et_remove_checkout_authorization', get_theme_mod('social_login_on_checkout_value', 'off') == 'off' );
			if ($is_checkout && is_checkout()){
			} else {
				$this->get_view()->authorization_buttons($this->settings);
			}
		});
		add_action('template_redirect', array($this,'process_callback'), 30);
	}

	/**
	 * Get Authorization settings
	 *
	 * @since   3.0.3
	 * @version 1.0.0
	 * @return array settings
	 */
	public function get_settings(){
		$settings = array(
			'facebook' => array(
				'id' => get_theme_mod('facebook_app_id'),
				'secret' => get_theme_mod('facebook_app_secret'),
				'uri' => '/facebook/int_callback'
			),
			'google' =>  array(
				'id' => get_theme_mod('google_app_id'),
				'secret' => get_theme_mod('google_app_secret'),
				'uri' => '/google/oauth2callback'
			)
		);

		foreach ( $settings as $key => $value ) {
			if ( ! $value['id'] || !$value['secret']){
				unset($settings[$key]);
			}
		}

		$this->settings = $settings;
		return $this->settings;
	}

	/**
	 * check api callback -> register or login user
	 *
	 * @since   3.0.3
	 * @version 1.0.0
	 */
	public function process_callback() {
		if (
			isset($_GET['error'])
			&& isset($_GET['error_description'])
			&& isset($_GET['error_reason'])
			&& isset($_GET['error_code'])
		){
			$page = ( is_checkout() ) ? 'checkout' : 'myaccount';
			wp_safe_redirect(wc_get_page_permalink($page));
			exit;
		}

		if( empty( $_GET['opauth'] ) ) return;

		$redirect = true;

		$opauth = unserialize(etheme_decoding($_GET['opauth']));

		if( empty( $opauth['auth']['info'] ) ) {
			$error = sprintf(
				"%s %s %s",
				esc_html__( 'Can\'t login with.', 'xstore-core' ),
				$opauth['auth']['provider'],
				esc_html__( 'Please, try again later.', 'xstore-core' )
			);
			wc_add_notice( $error, 'error' );
			return;
		}

		$info = $opauth['auth']['info'];

		if( empty( $info['email'] ) ) {
			$error = sprintf(
				"%s %s",
				$opauth['auth']['provider'],
				esc_html__( 'doesn\'t provide your email. Try to register manually.', 'xstore-core' )
			);
			wc_add_notice( $error, 'error' );
			return;
		}

		add_filter('dokan_register_nonce_check', '__return_false');
		add_filter('pre_option_woocommerce_registration_generate_username', array($this,'generate_username_option'), 10);

		$password = wp_generate_password();

		if ( ! empty( $info['first_name'] ) && ! empty( $info['last_name'] ) ) {
			$udata = array(
				'first_name' => $info['first_name'],
				'last_name' => $info['last_name']
			);
		} else {
			$udata = array();
		}

		$customer = wc_create_new_customer( $info['email'], '', $password, $udata);

		$user = get_user_by('email', $info['email']);

		$image = false;

		if (isset($info['image'])){
			$image = $info['image'];
		} elseif (isset($info['picture'])){
			$image = $info['picture'];
		}

		if (get_theme_mod( 'load_social_avatar_value', 'off' ) === 'on' && $image){
			$this->setup_avatar($user, $image);
		}

		if( is_wp_error( $customer ) ) {
			if( isset( $customer->errors['registration-error-email-exists'] ) ) {
				wc_set_customer_auth_cookie( $user->ID );
			}
		} else {
			wc_set_customer_auth_cookie( $customer );
		}

		wc_add_notice( sprintf( '%s<strong>%s</strong>', esc_html__( 'You are now logged in as ', 'xstore-core' ), $user->display_name ) );

		remove_filter('dokan_register_nonce_check', '__return_false');
		remove_filter('pre_option_woocommerce_registration_generate_username', array($this,'generate_username_option'), 10);

		if ($redirect){
			$page = get_option('etheme_fb_login');
			$account_url = wc_get_page_permalink($page);
			wp_safe_redirect($account_url);
		}
	}

	/**
	 * generate username for Woocommerce registration
	 *
	 * @since   3.0.3
	 * @version 1.0.0
	 */
	public function generate_username_option() {
		return 'yes';
	}

	/**
	 * Prepare data and send it to network apis
	 *
	 * @since   3.0.3
	 * @version 1.0.0
	 */
	public function api_request() {
		if(
			!is_admin()
			&& ! class_exists( 'WooCommerce' )
			|| ( empty( $_GET['etheme_authorize'] ) && empty( $_GET['code'] ) )
			|| isset($_GET['opauth'])
		) {
			return;
		}

		$network = '';

		if ( ! empty( $_GET['etheme_authorize'] ) ){
			$network = $_GET['etheme_authorize'];
		} else {
			foreach ($this->settings as $key => $value) {
				if ( strpos( $_SERVER['REQUEST_URI'], $value['uri']) !== false ){
					$network = $key;
					break;
				}
			}
		}

		if ( ! $network ){
			return;
		}

		$account_url    = wc_get_page_permalink( get_option('etheme_fb_login') );
		$config = array(
			'security_salt'      => apply_filters('et_facebook_salt', '2NlBUibcszrVtNmDnxqDbwCOpLWq91eatIz6O1O'),
			'host'               => $account_url,
			'path'               => '/',
			'callback_url'       => $account_url,
			'callback_transport' => 'get',
			'strategy_dir'       => ET_CORE_DIR . 'packages/vendor/opauth/',
			'Strategy'           => array(),
			'request_uri'        => ''
		);

		switch ($network){
			case 'google':
				$config['Strategy'] = array(
					'Google' => array(
						'client_id' => $this->settings['google']['id'],
						'client_secret' => $this->settings['google']['secret'],
					),
				);
				$config['request_uri'] = '/google/';
				break;
			case 'facebook':
				$config['Strategy'] = array(
					'Facebook' => array(
						'app_id' => $this->settings['facebook']['id'],
						'app_secret' => $this->settings['facebook']['secret'],
						'scope' => 'email'
					),
				);
				$config['request_uri'] = '/facebook/';
				break;
			default;
				break;
		}
		if (!empty($_GET['code'])){
			$config['request_uri'] = $this->settings[$network]['uri'] . '?code=' . $_GET['code'];
		}
		new \Opauth( $config );
	}

	/**
	 * Setup user avatar
	 *
	 * @since
	 * @version 1.0.0
	 */
	public function setup_avatar($user, $url){
		$avatar_id = $this->upload_image($url, false, $user);
		if ($avatar_id){
			update_user_meta($user->ID, 'xstore_avatar', $avatar_id);
		}
	}

	/**
	 * Upload user avatar
	 *
	 * @since
	 * @version 1.0.1
	 */
	public function upload_image($url, $post_id, $user) {
		if (!isset($user->display_name)) return false;

		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		$post_data = array();
		if ( isset( $post_data['post_date'] ) && substr( $post_data['post_date'], 0, 4 ) > 0 ) {
			$time = $post_data['post_date'];
		} else {
			$post = get_post( $post_id );
			if ( $post && substr( $post->post_date, 0, 4 ) > 0 ) {
				$time = $post->post_date;
			} else {
				$time = current_time( 'mysql' );
			}
		}

		$timeout_seconds = 5;
		$temp_file = download_url( $url, $timeout_seconds );

		if( ! is_wp_error( $temp_file ) ){
			$file = array(
				'name'     => 'user-avatar.jpg',
				'type'     => 'image/jpg',
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize($temp_file),
			);

			$overrides = array(
				'test_form' => false,
			);

			$results = wp_handle_sideload( $file, $overrides, $time );

			if ( isset( $results['error'] ) ) {
				unlink( $temp_file );
				return false;
			}

			$url     = $results['url'];
			$type    = $results['type'];
			$file    = $results['file'];
			$title   = 'avatar of ' . $user->display_name;
			$content = '';

			$image_meta = wp_read_image_metadata( $file );

			if ( $image_meta ) {
				$content = 'avatar of ' . $user->display_name;
			}

			// Construct the attachment array.
			$attachment = array_merge(
				array(
					'post_mime_type' => $type,
					'guid'           => $url,
					'post_parent'    => $post_id,
					'post_title'     => $title,
					'post_content'   => $content,
				),
				$post_data
			);

			// This should never be set as it would then overwrite an existing attachment.
			unset( $attachment['ID'] );

			// Save the attachment metadata.
			$attachment_id = wp_insert_attachment( $attachment, $file, $post_id, true );

			if ( ! is_wp_error( $attachment_id ) ) {
				wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $file ) );
			}

			if ( file_exists( $temp_file ) ){
				unlink( $temp_file );
			}

			return $attachment_id;
		}

		if ( ! is_wp_error( $temp_file ) ){
			if ( file_exists( $temp_file ) ){
				unlink( $temp_file );
			}
		}
		return false;
	}
}