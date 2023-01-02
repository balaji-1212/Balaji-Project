<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel YouTube.
 *
 * Add admin panel dashboard pages to admin menu.
 * Output dashboard pages.
 *
 * @since   7.0.0
 * @version 1.0.0
 */
class YouTube{

	// ! Main construct
	function __construct(){
		$_POST['et_YouTube'] = $this->et_get_YouTube();
	}

	/**
	 * Get YouTube videos
	 *
	 * @version  1.0.0
	 * @since  6.3.6
	 */
	public function et_get_YouTube() {
		$videos = get_transient( 'etheme_YouTube_info' );

		if ( ! $videos || empty( $videos ) || isset($_GET['et_clear_YouTube_transient']) ) {
			$videos = array();
			// Try to get data from youtube API
			$api_response = wp_remote_post( 'https://www.googleapis.com/youtube/v3/playlistItems', array(
				'method' => 'GET',
				'body'   => array(
					'part'       => 'snippet',
					'maxResults' => 50,
					'playlistId' => 'PLMqMSqDgPNmCCyem_z9l2ZJ1owQUaFCE3',
					'order'      => 'date',
					'key'        => 'AIzaSyBNsAxteDRIwO1A6Ainv8u-_vVYcPPRYB8'
				)
			) );

			// Get response code
			$code = wp_remote_retrieve_response_code( $api_response );

			if ( $code == 200 ) {
				$api_response = wp_remote_retrieve_body( $api_response );
				$api_response = json_decode( $api_response, true );

				foreach ( $api_response['items'] as $key => $value ) {
					$title = $value['snippet']['title'];
					if ( strlen( $title ) > 40 ) {
						$title = substr( $value['snippet']['title'], 0, 40 ) . '...';
					}

					$videos[] = array(
						'id'    => $value['snippet']['resourceId']['videoId'],
						'title' => $title
					);
				}
				set_transient( 'etheme_YouTube_info', $videos, 24 * HOUR_IN_SECONDS );
			} else {
				$videos = array();
			}
		}
		return $videos;
	}

	public function et_documentation_beacon(){
		$_POST['value'] = $_POST['value'] == 'false' ? false : true;

		$value = ( $_POST['value'] ) ? 'on' : 'off';

		update_option( 'et_documentation_beacon', $value, 'no');
		die();
	}
}
