<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Instagram widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Instagram extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-instagram';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Instagram', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-instagram';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'instagram', 'image', 'video', 'social', 'chat', 'story', 'stories' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['eight_theme_general'];
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
	 * Register Instagram widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'user',
			[
				'label' 		=>	__( 'Instagram Account', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> '<a href="' . admin_url('admin.php?page=et-panel-social'). '" target="_blank">'. esc_html__('Add Instagram account?', 'xstore-core') . '</a>',
				'options' 		=>	Elementor::instagram_api_data(),
			]
		);

		$this->add_control(
			'username',
			[
				'label' => __( 'Hashtag', 'xstore-core' ),
				'description' => __( 'Only for Instagram business users', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'tag_type',
			[
				'label' => __( 'Sort By', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'recent_media' => esc_html__( 'Recent media', 'xstore-core' ),
					'top_media' 	=> esc_html__( 'Top media', 'xstore-core' ),
				],
				'default' => 'recent_media'
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Number Of Photos', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => 12
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Photo Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'thumbnail' => esc_html__( 'Thumbnail', 'xstore-core' ),
					'medium' 	=> esc_html__( 'Medium', 'xstore-core' ),
					'large' 	=> esc_html__( 'Large', 'xstore-core' ),
				],
				'default' => 'thumbnail'
			]
		);

		$this->add_control(
			'img_type',
			[
				'label' 		=>	__( 'Image Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'squared'  	 =>	esc_html__( 'Squared', 'xstore-core' ),
					'default'	 =>	esc_html__( 'Default', 'xstore-core' ),
				],
				'default' => 'squared'
			]
		);

		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'element' => esc_html__( 'Element', 'xstore-core' ),
					'widget'  => esc_html__( 'Widget', 'xstore-core' ),
				],
				'default' => 'element'
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' => esc_html__('Works only with disabled Slider option', 'xstore-core'),
				'options' 		=>	[
					'2'	=>	esc_html__('2', 'xstore-core'),
					'3'	=>	esc_html__('3', 'xstore-core'),
					'4'	=>	esc_html__('4', 'xstore-core'),
					'5'	=>	esc_html__('5', 'xstore-core'),
					'6'	=>	esc_html__('6', 'xstore-core'),
				],
				'default' => '4',
			]
		);

		$this->add_control(
			'target',
			[
				'label' 		=>	__( 'Open Links In', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'_self'		=>	esc_html__('Current window', 'xstore-core'),
					'_blank'	=>	esc_html__('Blank', 'xstore-core'),
				],
				'default' => '_self'
			]
		);

		$this->add_control(
			'link',
			[
				'label' 	  => __( '"Follow Us" Text', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Leave field empty to not showing this link after images', 'xstore-core'),
			]
		);

		$this->add_control(
			'spacing',
			[
				'label' 		=>	__( 'Without Spacing', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'ajax',
			[
				'label' 		=>	__( 'Lazy Loading', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'slider',
			[
				'label' 		=>	__( 'Slider', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
				'condition' 	=> [ 'type' => 'element' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings',
			[
				'label' => __( 'Slider', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'type' => 'element',
					'slider' => 'true',
				]
			]
		);

		// Get slider controls from trait
		Elementor::get_slider_params( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style_section',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
	        \Elementor\Group_Control_Typography::get_type(),
	        [
	            'name'        	=> 'title_typography',
	            'label'       	=> __( 'Typography', 'xstore-core' ),
	            'selector'    	=> '{{WRAPPER}} h2.widgettitle',
				'separator'   	=> 'before',
	        ]
	    );

		$this->add_control(
			'title_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} h2.widgettitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default'		=> 'center',
				'selectors'    => [
					'{{WRAPPER}} h2.widgettitle' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Instagram widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[instagram 
			title="'. $settings['title'] .'"
			user="'. $settings['user'] .'"
			username="'. $settings['username'] .'"
			tag_type="'. $settings['tag_type'] .'"
			number="'. $settings['number'] .'"
			size="'. $settings['size'] .'"
			img_type="'. $settings['img_type'] .'"
			type="'. $settings['type'] .'"
			columns="'. $settings['columns'] .'"
			target="'. $settings['target'] .'"
			link="'. $settings['link'] .'"
			slider="'. $settings['slider'] .'"
			spacing="'. $settings['spacing'] .'"
			ajax="'. $settings['ajax'] .'"
			slider_speed="'. $settings['slider_speed'] .'"
			slider_autoplay="'. $settings['slider_autoplay'] .'"
			slider_stop_on_hover="'. $settings['slider_stop_on_hover'] .'"
			slider_interval="'. $settings['slider_interval'] .'"
			slider_loop="'. $settings['slider_loop'] .'"
			hide_buttons="'. $settings['hide_buttons'] .'"
	        navigation_type="' . $settings['navigation_type'] . '"
	        navigation_position_style="'. $settings['navigation_position_style'] . '"
	        navigation_style="'.$settings['navigation_style'].'"
	        navigation_position="'.$settings['navigation_position'].'"
			pagination_type="'. $settings['pagination_type'] .'"
			hide_fo="'. $settings['hide_fo'] .'"
			default_color="'. $settings['default_color'] .'"
			active_color="'. $settings['active_color'] .'"
			large="' . (!empty($settings['slides']) ? $settings['slides'] : 4) . '"
			notebook="' . (!empty($settings['slides']) ? $settings['slides'] : 4) . '"
			tablet_portrait="' . (!empty($settings['slides_tablet']) ? $settings['slides_tablet'] : 2) . '"
			tablet_land="' . (!empty($settings['slides_tablet']) ? $settings['slides_tablet'] : 2) . '"
			mobile="' . (!empty($settings['slides_mobile']) ? $settings['slides_mobile'] : 1) . '"
			is_preview="'. \Elementor\Plugin::$instance->editor->is_edit_mode() .'"
			is_elementor="true"]'
		);

	}

}