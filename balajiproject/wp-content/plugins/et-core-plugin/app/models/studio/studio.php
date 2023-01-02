<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Etheme_Studio {

	private $source = null;
	public function __construct() {
		require_once( ET_CORE_DIR . 'app/models/studio/connector.php' );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
		add_action( 'elementor/editor/footer', array( $this, 'print_template_views' ) );
	}


	public function instance_elementor() {
		return \Elementor\Plugin::instance();
	}

	public function print_template_views(){
		$this->get_source();
		require_once( ET_CORE_DIR . 'app/models/studio/templates.php' );
	}

	public function get_source() {
		if ( is_null( $this->source ) ) {
			$this->source = new Studio_Source();
		}
		return $this->source;
	}

	public function enqueue_assets() {
		wp_enqueue_style('etheme-studio-css', ET_CORE_URL.'app/models/studio/css/style.css');
		wp_enqueue_script(
			'etheme-studio-isotope', ET_CORE_URL . 'app/models/studio/js/scripts.js',
			array(
				'jquery',
				'elementor-editor',
			),
			'',
			true
		);

		if ( defined( 'ETHEME_BASE_URI' ) ) {
			wp_enqueue_script(
				'etheme-studio-js', ETHEME_BASE_URI . 'js/libs/isotope.js',
				array(
					'jquery',
					'elementor-editor',
					'etheme-studio-isotope'
				),
				'',
				true
			);
		}

		$localize_data = array(
			'Texts' => array(
				'EmptyTitle' => esc_html__( 'No Templates Found', 'xstore-core' ),
				'EmptyMessage' => esc_html__( 'Try different category or sync for new templates.', 'xstore-core' ),
				'NoResultsTitle' => esc_html__( 'No Results Found', 'xstore-core' ),
				'NoResultsMessage' => esc_html__( 'Please make sure your search is spelled correctly or try a different words.', 'xstore-core' ),
			),
			'Btn' => array(
				'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
				'Html' => '<div class="elementor-add-section-area-button elementor-add-et-button" title="XStore Studio Library"><i class="eicon">
<svg version="1.1" id="Layer_1" width="1em" height="1em" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 39.995121 39.995121" style="enable-background:new 0 0 39.995121 39.995121; vertical-align: text-bottom;" xml:space="preserve">
<g id="lato">
<path class="st0" d="M27.3709564,19.2625465L39.5855293,1.574048h-4.7565956c-0.1780739,0-0.3357773,0.0305225-0.4731445,0.1068442c-0.0661087,0.0254302-0.127182,0.0661371-0.183136,0.1119053c-0.183136,0.1475201-0.345932,0.335778-0.5036354,0.5799577l-8.2668381,12.2756205l-0.8088608,1.1954994c-0.2852173,0.4265661-0.0301971,0.0518732-0.6107826,0.8756781l-0.590065,0.8961906l-1.1800289,1.6468029l12.6164913,19.1434517c0.0711975,0.0101547,0.1475182,0.0152473,0.2289009,0.0152473h4.9397659L27.3709564,19.2625465z"/><g class="st1"><path class="st0" d="M12.6745758,19.4963245L0.5120417,1.5735823H5.474628c0.3601837,0,0.6257262,0.0601134,0.7971239,0.1798434c0.1711493,0.1202269,0.3256555,0.2916247,0.4630222,0.5141934l9.6169052,14.7600794
c0.11973-0.3601837,0.2998219-0.7541504,0.5400276-1.1828928l9.0773754-13.4738512c0.1540089-0.2399569,0.3209362-0.4327173,0.501276-0.578778c0.1798439-0.1455638,0.3984375-0.2185942,0.6557827-0.2185942h4.7569084L19.6688461,19.2648125l12.6255569,19.1568069h-4.9372482c-0.3770752,0-0.6726742-0.0983696-0.886797-0.2956009c-0.2146206-0.1967354-0.3899918-0.415329-0.5271111-0.6557808L16.0687485,22.016119c-0.119978,0.3601837-0.2742357,0.70298-0.4627733,1.0283871L5.9890699,37.4702377c-0.1542583,0.2404518-0.3303757,0.4590454-0.5271106,0.6557808c-0.1974797,0.1972313-0.4756908,0.2956009-0.8358746,0.2956009H-0.0024L12.6745758,19.4963245z"/></g></g></svg>
</i></div>'
			),
			'Error' => array()
		);
		wp_localize_script(
			'elementor-editor',
			'EthemeStudioJsData',
			$localize_data
		);
	}


	public function register_ajax_actions( $ajax ) {
		$ajax->register_ajax_action( 'get_et_library_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found.', 'xstore-core' ) );
				}

				$this->instance_elementor()->db->switch_to_post( $editor_post_id );
			}
			$source = $this->get_source();
			return $source->get_library_data($data);
		} );

		$ajax->register_ajax_action( 'get_et_filters_data', function( $data ) {
			$data = get_option('et_studio_data',true);
			if (! is_array($data)) {
				$source = $this->get_source();
				$data = $source->get_library_data(array('init'=>true));
			}
			return $data['filters'];
		} );

		$ajax->register_ajax_action( 'get_et_template_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found', 'xstore-core' ) );
				}

				$this->instance_elementor()->db->switch_to_post( $editor_post_id );

			}

			if ( empty( $data['template_id'] ) ) {
				throw new \Exception( __( 'Template id missing', 'xstore-core' ) );
			}
			$source = $this->get_source();
			return $source->get_data( $data );
		} );
	}
} // class


new Etheme_Studio();

