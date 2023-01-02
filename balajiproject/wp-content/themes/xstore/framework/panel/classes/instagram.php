<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Instagram class.
 *
 * @since   7.0.0
 * @version 1.0.2
 * @log 1.0.1
 * ADDED: etheme_update_network
 * @log 1.0.3
 * ADDED: escape_albums
 */
class Instagram{
	private $id = 223019305764117;
	private $business_id = 632563087558984;
	public $api_url = 'https://www.8theme.com/new-instagram-api/';

	// ! Main construct
	function __construct(){
		if ( isset( $_GET['et_reinit_instagram'] ) ) {
			$this->reinit_instagram();
		}
		if ( isset($_GET['token']) && $_GET['token'] != 'error' ) {
			$this->connect_user();
		}

		add_action( 'wp_ajax_etheme_update_network', array($this, 'etheme_update_network') );
	}

	/**
	 * Update network options
	 *
	 * @version  1.0.0
	 * @since   7.0.3
	 */
	public function etheme_update_network(){
		$response = array(
			'status' =>'error',
			'msg' => ''
		);

		if (! isset($_POST['form']) || ! count($_POST['form'])){
			$response['msg'] = esc_html__('Wrong data','xstore');
			$this->return($response);
		}

		foreach ( $_POST['form'] as $key => $value ) {
			set_theme_mod($value['name'],$value['value']);
		}

		$response['status'] = 'success';
		$response['msg'] = esc_html__('success','xstore');
		$this->return($response);
	}

	/**
	 * manual add instagram personal account
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function et_instagram_user_add() {
		if ( isset( $_POST['token'] ) && $_POST['token'] ) {
			$user_url = 'https://graph.instagram.com/me?fields=id,username,account_type&access_token=' . $_POST['token'];
			$api_data = get_option( 'etheme_instagram_api_data' );
			$api_data = json_decode($api_data, true);

			if ( ! is_array( $api_data ) ) {
				$api_data = array();
			}

			$user_data = wp_remote_get($user_url);

			$response = array(
				'status' =>'error',
				'msg'=>''
			);

			if ( ! $user_data ) {
				$response['msg'] = 'Unable to communicate with Instagram.';
				$this->return($response);
			}

			if ( is_wp_error( $user_data ) ) {
				$response['msg'] = 'Unable to communicate with Instagram.';
				$this->return($response);
			}

			if ( 200 !== wp_remote_retrieve_response_code( $user_data ) ) {
				if ( $this->et_instagram_add_user_business($api_data) ) {
					return;
				}
				$response['msg'] = 'Instagram did not return a 200.';
				$this->return($response);
			}

			$user_data = wp_remote_retrieve_body( $user_data );

			if ( ! isset( $api_data[$_POST['token']] ) ) {
				$api_data[$_POST['token']] = $user_data;
			} else {
				$response['msg'] = 'this token already exist';
				$this->return($response);
			}

			update_option('etheme_instagram_api_data',json_encode($api_data));

			$response['status'] = 'success';
			$this->return($response);
		}
		$response['msg'] = 'empty token';
		$this->return($response);
	}

	/**
	 * manual add instagram business account
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 * @param array $api_data api response data
	 */
	public function et_instagram_add_user_business($api_data){
		$user_url = 'https://graph.instagram.com/me?fields=id,username,account_type&access_token=' . $_POST['token'];
		$user_data = wp_remote_get($user_url);

		$user_url = 'https://graph.facebook.com/v14.0/me/accounts?access_token=' . $_POST['token'];
		$data     = wp_remote_get($user_url);
		$data     = wp_remote_retrieve_body( $data );
		$data     = json_decode( $data, true );
		$id       = $data['data'][0]['id'];// page id
		$user_url = 'https://graph.facebook.com/v14.0/'.$id.'?fields=instagram_business_account,username&access_token=' . $_POST['token'];

		$user_data = wp_remote_get($user_url);

		$response = array(
			'status' =>'error',
			'msg'=>''
		);

		if ( ! $user_data ) {
			$response['msg'] = 'Unable to communicate with Instagram';
			$this->return($response);
		}

		if ( is_wp_error( $user_data ) ) {
			$response['msg'] = 'Unable to communicate with Instagram.';
			$this->return($response);
		}

		if ( 200 !== wp_remote_retrieve_response_code( $user_data ) ) {
			$response['msg'] = 'Instagram did not return a 200.';
			$this->return($response);
		}

		$user_data = wp_remote_retrieve_body( $user_data );
		$user_data = json_decode($user_data, true);

		if ( ! isset( $user_data['username'] ) ) {
			$response['msg'] = 'Seems your user does not have correct permissions to display media of the business account.';
			$this->return($response);
		}

		$user_data['id'] = $user_data['instagram_business_account']['id'];

		$user_data['account_type'] = 'BUSINESS';
		$user_data = json_encode($user_data);

		$api_data[$_POST['token']] = $user_data;
		update_option('etheme_instagram_api_data',json_encode($api_data));

		$response['status'] = 'success';
		$this->return($response);
	}

	/**
	 * save instagram settings
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function et_instagram_save_settings() {
		$response = array(
			'status' =>'error',
			'msg' => ''
		);
		if ( isset( $_POST['time'] ) && isset( $_POST['time_type'] ) ) {
			$api_settings = get_option( 'etheme_instagram_api_settings' );
			$api_settings = json_decode($api_settings, true);

			$api_settings['time']      = ( $_POST['time'] && $_POST['time'] != 0 && $_POST['time'] !== '0' ) ? $_POST['time'] : 2;
			$api_settings['time_type'] = $_POST['time_type'];
			$api_settings['escape_albums'] = ( $_POST['escape_albums'] && $_POST['escape_albums'] !== 'false' ) ? $_POST['escape_albums'] : false;

			update_option('etheme_instagram_api_settings',json_encode($api_settings));
			$response['type'] = 'success';
			$response['msg'] = 'success';
			$this->return($response);
		}
		$response['msg'] = 'error';
		$this->return($response);
	}

	/**
	 * remove user accounts
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function et_instagram_user_remove() {
		$response = array(
			'status' =>'error',
			'msg'=>''
		);
		if ( isset( $_POST['token'] ) && $_POST['token'] ) {
			$api_data = get_option( 'etheme_instagram_api_data' );
			$api_data = json_decode($api_data, true);

			if ( isset($api_data[$_POST['token']]) ) {
				unset($api_data[$_POST['token']]);
				update_option('etheme_instagram_api_data',json_encode($api_data));
				$response['status'] = 'success';
				$response['msg'] = 'success';
				$this->return($response);
			}
			$response['msg'] = 'this token is not exist';
			$this->return($response);
		}
		$response['msg'] = 'empty token';
		$this->return($response);
	}

	/**
	 * generate personal/business connect urls
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function generate_urls(){
		return (object) array(
			'personal' => add_query_arg( array(
				'client_id' => $this->id,
				'redirect_uri' => $this->api_url,
				'scope' => 'user_profile,user_media',
				'response_type' => 'code',
				'state' => admin_url('admin.php?et-panel-social')
			), 'https://api.instagram.com/oauth/authorize' ),
			'business' => add_query_arg( array(
				'client_id' => $this->business_id,
				'redirect_uri' => $this->api_url,
				'scope' => 'pages_show_list,instagram_basic,pages_read_engagement',
				'response_type' => 'code',
				'state' => urlencode( admin_url('admin.php?et-panel-social') . '&business=true' )
			), 'https://www.facebook.com/dialog/oauth' )
		);
	}

	/**
	 * get personal/business connect urls
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function get_urls(){
		return $this->generate_urls();
	}

	/**
	 * return ajax data
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 * @param array $response response for ajax calls
	 */
	public function return($response){
		return wp_send_json($response);
	}

	/**
	 * get api data
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function get_api_data() {
		$api_data     = get_option( 'etheme_instagram_api_data' );
		$api_data     = json_decode( $api_data, true );
		return $api_data;
	}

	/**
	 * get api settings
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function get_api_settings() {
		$api_settings = get_option( 'etheme_instagram_api_settings' );
		$api_settings = json_decode( $api_settings, true );
		if ( ! $api_settings ) {
			$api_settings = array(
				'time' => 2,
				'time_type'=> 'hour',
				'escape_albums' => false,
			);
			update_option('etheme_instagram_api_settings',json_encode($api_settings), 'no');
		}

		return $api_settings;
	}

	/**
	 * reinit instagram accounts
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function reinit_instagram() {
		global $wpdb;
		$transients = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_etheme-instagram-data%'", ARRAY_A );

		foreach ($transients as $key => $value) {
			$name = str_replace('_transient_', '', $value['option_name']);
			delete_transient($name);
		}
		header('Location: '.admin_url( 'admin.php?page=et-panel-social' ) );
	}

	/**
	 * connect instagram user
	 *
	 * @version  1.0.0
	 * @since   7.0.0
	 */
	public function connect_user(){
		$api_data = $this->get_api_data();

		if ( ! is_array( $api_data ) ) {
			$api_data = array();
		}

		$token = $_GET['token'];

		if ( isset( $_GET['business'] ) && $_GET['business'] ) {
			// Get business user
			$user_url = 'https://graph.facebook.com/v14.0/me/accounts?access_token=' . $token;
			$data     = wp_remote_get($user_url);
			$data     = wp_remote_retrieve_body( $data );
			$data     = json_decode( $data, true );
			$id       = $data['data'][0]['id'];// page id
			$user_url = 'https://graph.facebook.com/v14.0/'.$id.'?fields=instagram_business_account,username,name&access_token=' . $token;

			foreach ($data['data'] as $key => $value) {

				$user_url = 'https://graph.facebook.com/v14.0/'.$value['id'].'?fields=instagram_business_account,username,name&access_token=' . $token;

				$user_data = wp_remote_get($user_url);
				$user_data = wp_remote_retrieve_body( $user_data );

				$user_data = $user_data_clear = json_decode($user_data, true);

				if (isset($_GET['expires_in']) && $_GET['expires_in']) {
					$user_data['expires_in'] = ( time() + $_GET['expires_in'] );
				}

				if ( isset($user_data['instagram_business_account']['id']) ) {
					$user_data['account_type'] = 'BUSINESS';
					$user_data['id'] = $user_data['instagram_business_account']['id'];
					$user_data['connection_type'] = 'BUSINESS';
				} else {
					continue;
					$user_data['account_type'] = 'PERSONAL';
					$user_data['id'] = $user_data['id'];
					$user_data['connection_type'] = 'BUSINESS';
				}

				$user_data['token'] = $token;

				$user_data['username'] = $value['name'];

				$user_data = json_encode($user_data);
				if ( ! isset( $api_data[$value['access_token']] ) ) {
					$api_data[$value['access_token']] = $user_data;
				}

			}

			update_option('etheme_instagram_api_data',json_encode($api_data));
			header('Location: '.admin_url( 'admin.php?page=et-panel-social' ) );
			return;
		} else {
			$user_url = 'https://graph.instagram.com/me?fields=id,username,account_type&access_token=' . $token;
		}

		$user_data = wp_remote_get($user_url);
		$user_data = wp_remote_retrieve_body( $user_data );

		$user_data = $user_data_clear = json_decode($user_data, true);

		if (isset($_GET['expires_in']) && $_GET['expires_in']) {
			$user_data['expires_in'] = ( time() + $_GET['expires_in'] );
		}



		if ( $user_data['account_type'] === 'BUSINESS' ) {

			$user_data['account_type'] = $user_data['account_type'];
			$user_data['connection_type'] = 'PERSONAL';
		} else {
			$user_data['connection_type'] = 'BUSINESS';
		}

		$user_data = json_encode($user_data);

		if ( ! isset( $api_data[$_GET['token']] ) ) {
			$api_data[$_GET['token']] = $user_data;
		}

		update_option('etheme_instagram_api_data',json_encode($api_data));

		header('Location: '.admin_url( 'admin.php?page=et-panel-social' ) );


	}
}
new Instagram();