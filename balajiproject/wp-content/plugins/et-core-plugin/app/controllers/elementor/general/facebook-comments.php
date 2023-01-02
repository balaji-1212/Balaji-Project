<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Facebook Comments widget.
 *
 * @since      4.0.11
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Facebook_Comments extends \Elementor\Widget_Base {
	
	use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_facebook_comments';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Facebook Comments', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-facebook-comments';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'facebook', 'comments', 'embed', 'social', 'network', 'meta' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'etheme_facebook_sdk' ];
	}
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
	
	/**
	 * Register Media Carousel controls.
	 *
	 * @since 4.0.11
	 * @access protected
	 */
	protected function register_controls() {
//		if ( ! self::get_app_id() ) {
		if ( !Elementor::get_facebook_sdk_info('get_app_id') ) {
			
			$this->start_controls_section(
				'section_api',
				[
					'label' => esc_html__( 'Configuration', 'xstore-core' ),
				]
			);
			
			// Check if XStore theme is activated
			if ( defined('ETHEME_THEME_VERSION') ) {
				$this->add_control(
					'app_id_heading',
					[
						'type'            => \Elementor\Controls_Manager::RAW_HTML,
						'raw' => sprintf( __( 'Set your Facebook App ID in the <a href="%s" target="_blank">Authorization APIs</a> -> Facebook Loginization', 'xstore-core' ), admin_url('admin.php?page=et-panel-social') ),
						'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					]
				);
			}
			else {
				
				$this->add_control(
					'app_id_heading',
					[
						'type'            => \Elementor\Controls_Manager::RAW_HTML,
						'raw'             => __( 'Set your Facebook App ID', 'xstore-core' ),
						'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					]
				);
				
				$this->add_control(
					'app_id',
					[
						'label'       => __( 'App ID', 'xstore-core' ),
						'type'        => \Elementor\Controls_Manager::TEXT,
						'label_block' => 'true',
					]
				);
			}
			
			$this->add_control(
				'app_id_info',
				[
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             =>
						sprintf( __( 'Facebook SDK lets you connect to your <a href="%s" target="_blank">dedicated application</a> so you can track the Facebook Widgets analytics on your site.', 'xstore-core' ), 'https://developers.facebook.com/docs/apps/register/' ) .
						'<br>' .
						'<br>' .
						__( 'If you are using the Facebook Comments Widget, you can add moderating options through your application. Note that this option will not work on local sites and on domains that don\'t have public access.', 'xstore-core' ) .
						sprintf( __( 'Remember to add the domain to your <a href="%s" target="_blank">App Domains</a>', 'xstore-core' ), Elementor::get_facebook_sdk_info('get_app_settings_url') ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
			);
			
			$this->end_controls_section();
			
		}
		
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'comments_number',
			[
				'label' => __( 'Comment Count', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 100,
				'default' => 10,
				'description' => __( 'Minimum number of comments: 5', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'order_by',
			[
				'label' => __( 'Order By', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'reverse_time',
				'options' => [
					'reverse_time' => __( 'Reverse Time', 'xstore-core' ),
					'time' => __( 'Time', 'xstore-core' ),
				],
			]
		);
		
//		$this->add_control(
//			'colorscheme',
//			[
//				'label' => __( 'Color Scheme', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => 'light',
//				'options' => [
//					'light' => __( 'light', 'xstore-core' ),
//					'dark' => __( 'Dark', 'xstore-core' ),
//				],
//			]
//		);
		
		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'current' => __( 'Current Page', 'xstore-core' ),
					'custom' => __( 'Custom', 'xstore-core' ),
				],
				'default' => 'current',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'url_format',
			[
				'label' => __( 'URL Format', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'plain' => __( 'Plain Permalink', 'xstore-core' ),
					'pretty' => __( 'Pretty Permalink', 'xstore-core' ),
				],
				'default' => 'plain',
				'condition' => [
					'url_type' => 'current',
				],
			]
		);
		
		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'label_block' => true,
				'condition' => [
					'url_type' => 'custom'
				],
			]
		);
		
		$this->add_control(
			'lazy',
			[
				'label' => __( 'Lazy', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->end_controls_section();
		
	}
	
	public function render() {
		
		$settings = $this->get_settings_for_display();
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		$app_id = Elementor::get_facebook_sdk_info('get_app_id');
		
		if ( $edit_mode ) {
			$errors = array();
			if ( defined( 'ETHEME_THEME_VERSION' ) ) {
				$errors = Elementor::get_facebook_sdk_info('validate_sdk', array('app_id' => $app_id) );
			} elseif ( isset( $settings['app_id'] ) ) {
				$errors = Elementor::get_facebook_sdk_info('validate_sdk', array('app_id' => $settings['app_id']) );
			}
			
			if ( count( $errors ) ) {
				echo Elementor::get_facebook_sdk_info('render_error', array('error_type'=> 'danger', 'error_content' => implode( '<br>', $errors )));
			}
		}
		
		if ( $settings['url_type'] == 'current' ) {
			$permalink = Elementor::get_facebook_sdk_info('get_permalink', array('settings' => $settings));
		}
		else {
			if ( ! filter_var( $settings['url'], FILTER_VALIDATE_URL ) ) {
				echo Elementor::get_facebook_sdk_info('render_error', array('error_content' => $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'xstore-core' )));
				return;
			}
			
			$permalink = esc_url( $settings['url'] );
		}
		
		
		$attributes = [
			'class' => 'elementor-facebook-widget fb-comments',
			'data-href' => $permalink,
			'data-numposts' => $settings['comments_number'],
			'data-order-by' => $settings['order_by'],
			// The style prevent's the `widget.handleEmptyWidget` to set it as an empty widget
			'style' => 'min-height: 1px',
			'data-lazy' => $settings['lazy'] == 'yes' ? 'true' : 'false'
		];
		
		$this->add_render_attribute( 'embed_div', $attributes );
		
		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
		
	}
	
}
