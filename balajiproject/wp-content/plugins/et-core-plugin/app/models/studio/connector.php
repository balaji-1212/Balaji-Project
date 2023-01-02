<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Elementor\TemplateLibrary\Source_Base;
class Studio_Source extends Source_Base {

	public  $API_URL = 'http://8theme.com/import/xstore-studio/studio/';

	/**
	 * Get elementor instance
	 *
	 * @return \Elementor\Plugin
	 */
	public function instance_elementor() {
		return \Elementor\Plugin::instance();
	}

	/**
	 * Set id
	 *
	 * elementor abstract
	 *
	 * @return id
	 */
	public function get_id() {
		return 'xstore-studio';
	}

	/**
	 * Set title
	 *
	 * elementor abstract
	 *
	 * @return title
	 */
	public function get_title() {
		return __( 'Xstore studio', 'xstore-core' );
	}

	/**
	 * Set data
	 *
	 * elementor abstract
	 */
	public function register_data() {}

	/**
	 * Set save_item error text
	 *
	 * elementor abstract
	 *
	 * @return WP_Error
	 */
	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a Xstore Studio' );
	}

	/**
	 * Set update_item error text
	 *
	 * elementor abstract
	 *
	 * @return WP_Error
	 */
	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a Xstore Studio' );
	}

	/**
	 * Set delete_template error text
	 *
	 * elementor abstract
	 *
	 * @return WP_Error
	 */
	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a Xstore Studio' );
	}

	/**
	 * Set export_template error text
	 *
	 * elementor abstract
	 *
	 * @return WP_Error
	 */
	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a Xstore Studio' );
	}

	/**
	 * get_items error text
	 *
	 * elementor abstract
	 */
	public function get_items( $args = [] ) {}

	/**
	 * get_items error text
	 *
	 * @param string $url remote url
	 * @return array|\WP_Error response data;
	 */
	public function get_remote_data($url = ''){
		$response = wp_remote_get($url);
		$code     = wp_remote_retrieve_response_code($response);

		if ($code!=200){
			throw new \Exception( __( 'can not connect to 8theme.com.', 'xstore-core' ) );
		}
		$data = wp_remote_retrieve_body($response);
		$data = json_decode($data, true);
		return $data;
	}

	/**
	 * Get library data
	 *
	 * @param array $args ajax args
	 * @return array response data;
	 */
	public function get_library_data($args) {
		$data = get_option('et_studio_data',true);
		if ( ! is_array($data) || isset($args['sync']) ){
			$url  = $this->API_URL . '?type=data&id=false&folder=false';
			$data = $this->get_remote_data($url);
			update_option('et_studio_data',$data, false);
		}
		if ( ! isset($args['init']) ){
			unset($data['filters']);
		}
		return $data;
	}

	/**
	 * Get remote template.
	 *
	 * elementor abstract
	 *
	 * @param int $template_id The template ID.
	 */
	public function get_item( $template_id ) {}

	/**
	 * Get remote template data.
	 *
	 * elementor abstract
	 *
	 * @param string $context
	 * @param array $data ajax data
	 * @return array|\WP_Error Remote Template data.
	 */
	public function get_data( array $data, $context = 'display' ) {
		$url = $this->API_URL . '?type=content&id=' . $data['template_id'] . '&folder='. $data['folder'];
		$data = $this->get_remote_data($url);
		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		if ( isset($data['editor_post_id']) ){
			$post_id = $data['editor_post_id'];
			$document = $this->instance_elementor()->documents->get( $post_id );

			if ( $document ) {
				$data['content'] = $document->get_elements_raw_data( $data['content'], true );
			}
		}
		return  $data;
	}
}