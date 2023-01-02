<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Facebook Embed widget.
 *
 * @since      4.0.11
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Facebook_Embed extends \Elementor\Widget_Base {
	
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
		return 'etheme_facebook_embed';
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
		return __( 'Facebook Embed', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-facebook-embed';
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
		return [ 'facebook', 'social', 'embed', 'video', 'comment', 'network', 'meta' ];
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
			'type',
			[
				'label' => __( 'Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'post',
				'options' => [
					'post' => __( 'Post', 'xstore-core' ),
					'video' => __( 'Video', 'xstore-core' ),
					'comment' => __( 'Comment', 'xstore-core' ),
				],
			]
		);
		
		$this->add_control(
			'post_url',
			[
				'label' => __( 'URL', 'xstore-core' ),
				'default' => 'https://www.facebook.com/8theme/posts/3364665816977594',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'post',
				],
				'description' => __( 'Hover over the date next to the post, and copy its link address.', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'video_url',
			[
				'label' => __( 'URL', 'xstore-core' ),
				'default' => 'https://www.facebook.com/hm/videos/843106403026918/',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'video',
				],
				'description' => __( 'Hover over the date next to the video, and copy its link address.', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'comment_url',
			[
				'label' => __( 'URL', 'xstore-core' ),
				'default' => 'https://www.facebook.com/8theme/posts/2294988803945306?comment_id=2525505290893655&reply_comment_id=2701431476634368',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'comment',
				],
				'description' => __( 'Hover over the date next to the comment, and copy its link address.', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'include_parent',
			[
				'label' => __( 'Parent Comment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Set to include parent comment (if URL is a reply).', 'xstore-core' ),
				'condition' => [
					'type' => 'comment',
				],
			]
		);
		
		$this->add_control(
			'show_text',
			[
				'label' => __( 'Full Post', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Include the text from the Facebook post, if any.', 'xstore-core' ),
				'condition' => [
					'type' => [ 'post', 'video' ],
				],
			]
		);
		
		$this->add_control(
			'video_allowfullscreen',
			[
				'label' => __( 'Allow Full Screen', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Allow the video to be played in fullscreen mode', 'xstore-core'),
				'condition' => [
					'type' => 'video',
				],
			]
		);
		
		$this->add_control(
			'video_autoplay',
			[
				'label' => __( 'Autoplay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' =>
					__('Automatically start playing the video when the page loads. The video will be played without sound (muted).', 'xstore-core'),
				'condition' => [
					'type' => 'video',
				],
			]
		);
		
		$this->add_control(
			'video_show_captions',
			[
				'label' => __( 'Captions', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show captions if available (only on desktop).', 'xstore-core' ),
				'condition' => [
					'type' => 'video',
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
			if ( defined( 'ETHEME_THEME_VERSION' ) )
				$errors = Elementor::get_facebook_sdk_info('validate_sdk', array('app_id' => $app_id) );
			elseif ( isset( $settings['app_id'] ) )
				$errors = Elementor::get_facebook_sdk_info('validate_sdk', array('app_id' => $settings['app_id']) );
			
			if ( count( $errors ) )
				echo Elementor::get_facebook_sdk_info('render_error', array('error_type'=> 'danger', 'error_content' => implode( '<br>', $errors )));
		}
		
		if ( empty( $settings['type'] ) ) {
			echo Elementor::get_facebook_sdk_info('render_error', array('error_content' => esc_html__( 'Please set the embed type', 'xstore-core' )));
			return;
		}
		
		if ( 'comment' === $settings['type'] && empty( $settings['comment_url'] ) ||
		     'post' === $settings['type'] && empty( $settings['post_url'] ) ||
		     'video' === $settings['type'] && empty( $settings['video_url'] ) ) {
			echo Elementor::get_facebook_sdk_info('render_error', array('error_content' => esc_html__( 'Please enter a valid URL', 'xstore-core' )));
			return;
		}
		
		$attributes = [
			// The style prevent's the `widget.handleEmptyWidget` to set it as an empty widget
			'style' => 'min-height: 1px',
			'data-href' => $settings[$settings['type'].'_url'],
			'data-lazy' => $settings['lazy'] == 'yes' ? 'true' : 'false',
		];
		
		switch ( $settings['type'] ) {
			case 'comment':
				$attributes['class'] = 'etheme-facebook-widget fb-comment-embed';
				$attributes['data-include-parent'] = $settings['include_parent'] === 'yes' ? 'true' : 'false';
				break;
			case 'post':
				$attributes['class'] = 'etheme-facebook-widget fb-post';
				$attributes['data-show-text'] = $settings['show_text'] === 'yes' ? 'true' : 'false';
				break;
			case 'video':
				$attributes['class'] = 'etheme-facebook-widget fb-video';
				$attributes['data-show-text'] = $settings['show_text'] === 'yes' ? 'true' : 'false';
				$attributes['data-allowfullscreen'] = $settings['video_allowfullscreen'] === 'yes' ? 'true' : 'false';
				$attributes['data-autoplay'] = $settings['video_autoplay'] === 'yes' ? 'true' : 'false';
				$attributes['data-show-captions'] = $settings['video_show_captions'] === 'yes' ? 'true' : 'false';
				break;
		}
		
		$this->add_render_attribute( 'embed_div', $attributes );
		
		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
		
	}
	
}
