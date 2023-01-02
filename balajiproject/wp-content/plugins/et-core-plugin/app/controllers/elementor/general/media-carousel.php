<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
/**
 * Media Carousel widget.
 *
 * @since      4.0.8
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Media_Carousel extends \Elementor\Widget_Base {
	
    use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_media_carousel';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Media Carousel', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-media-carousel';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'image', 'video', 'gallery', 'lightbox', 'carousel' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.8
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
	 * @since 4.0.8
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'etheme_media_carousel' ];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-media-carousel' ];
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
	 * @since 4.0.8
	 * @access protected
	 */
	protected function register_controls() {
		
		Elementor::get_slider_general_settings($this);
		
		$this->start_injection( [
			'type' => 'control',
			'at' => 'before',
			'of' => 'slides_per_view',
		] );
		
		$this->add_control(
			'effect',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Effect', 'xstore-core' ),
				'default' => 'slide',
				'options' => [
					'slide'  => __('Slide', 'xstore-core'),
					'fade' => __('Fade', 'xstore-core'),
					'cube' => __('Cube', 'xstore-core'),
					'coverflow' => __('Coverflow', 'xstore-core'),
					'flip' => __('Flip', 'xstore-core'),
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'slide_shadow_color',
			[
				'label' => __( 'Shadow Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-container-cube .swiper-cube-shadow' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'effect' => 'cube',
				]
			]
		);
		
		$this->end_injection();
		
		$this->update_control( 'section_slider', [
			'label' => esc_html__('General', 'xstore-core')
		] );
		
		$this->update_control( 'slides_per_view', [
			'condition' => [
                'effect' => ['slide', 'coverflow']
            ],
		] );
		
		$this->update_control( 'slides_per_group', [
			'condition' => [
				'effect' => ['slide']
			],
		] );
		
		$this->update_control( 'space_between', [
			'condition' => [
				'effect' => ['slide', 'coverflow']
			],
		] );
		
		$this->update_control( 'loop', [
			'default' => '',
		] );
		
		$this->update_control( 'arrows_style', [
			'default' => 'style-1'
		] );
		
		$this->start_injection( [
			'type' => 'control',
			'at' => 'after',
			'of' => 'space_between',
		] );
		
		$this->add_control(
			'image_stretch',
			[
				'label' => esc_html__( 'Image Stretch', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->end_injection();
  
		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );
		
		$this->start_injection( [
			'type' => 'section',
			'at' => 'end',
			'of' => 'arrows_position_style',
		] );
		
		$this->add_control(
			'thumbs_header',
			[
				'label' => esc_html__( 'Thumbnails', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'effect' => ['slide']
				],
			]
		);
		
		$this->add_control(
			'show_thumbs',
			[
				'label' 		=>	__( 'Show Thumbnails', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true,
				'condition' => [
					'effect' => ['slide']
				],
			]
		);
		
		$this->add_responsive_control(
			'thumbs_slides_per_view',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Slides Per View', 'xstore-core' ),
				'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $slides_per_view,
				'condition' => [
					'effect' => ['slide'],
					'show_thumbs!' => '',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_responsive_control(
			'thumbs_space_between',
			[
				'label' => esc_html__( 'Space Between', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 20
				],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => 0
					],
				],
				'condition' => [
					'effect' => ['slide'],
					'show_thumbs!' => '',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_responsive_control(
			'thumbs_space_before',
			[
				'label' => esc_html__( 'Space Before', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
						'min' => 0
					],
				],
				'condition' => [
					'effect' => ['slide'],
					'show_thumbs!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-thumbs-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'thumbs_autoheight',
			[
				'label' => esc_html__( 'Auto Height', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'effect' => ['slide'],
					'show_thumbs!' => '',
				],
				'frontend_available' => true,
			]
		);
		
		$this->end_injection();
		
		$this->start_controls_section(
			'section_items_general',
			[
				'label' => esc_html__( 'Items', 'xstore-core' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'item_type',
			[
				'label' => esc_html__( 'Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'xstore-core' ),
					'video' => esc_html__( 'Video', 'xstore-core' ),
				],
			]
		);
		
		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose File', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'item_type' => 'image',
				],
			]
		);
		
		$repeater->add_control(
			'video',
			[
				'label' => esc_html__( 'Choose File', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'media_type' => 'video',
				'default' => [
				        'url' => 'https://www.dailymotion.com/video/x6tqhqb'
                ],
				'condition' => [
					'item_type' => 'video',
				],
			]
		);
		
		$this->add_control(
			'items',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_type' => 'image',
						'default' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
					],
					[
						'item_type' => 'image',
						'default' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
					],
					[
						'item_type' => 'image',
						'default' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_item_content',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'open_lightbox',
			[
				'label' => esc_html__( 'Open Lightbox', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'xstore-core' ),
					'no' => esc_html__( 'No', 'xstore-core' ),
				],
			]
		);
		
		$this->add_control(
			'lightbox_icon',
			[
				'label' 		=>	__( 'Lightbox Icon', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'open_lightbox' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' 		=>	__( 'Title', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'caption',
			[
				'label' 		=>	__( 'Caption', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'date',
			[
				'label' 		=>	__( 'Date', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => __( 'March 6, 2018 (F j, Y)', 'xstore-core' ),
					'1' => '2018-03-06 (Y-m-d)',
					'2' => '03/06/2018 (m/d/Y)',
					'3' => '06/03/2018 (d/m/Y)',
					'custom' => __( 'Custom', 'xstore-core' ),
				],
				'condition' => [
					'date' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'custom_date_format',
			[
				'label' => __( 'Custom Date Format', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'F j, Y',
				'condition' => [
					'date' => 'yes',
					'date_format' => 'custom',
				],
				'description' => sprintf(
				/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					__( 'Use the letters: %s', 'xstore-core' ),
					'l D d j S F m M n Y y'
				),
			]
		);
		
		$this->add_control(
			'video_options',
			[
				'label' => esc_html__( 'Video Options', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'video_play_icon',
			[
				'label' => esc_html__( 'Play Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'open_lightbox' => 'yes'
				]
			]
		);
		
//		Maybe make it on each video repeater then
//		$this->add_control(
//			'video_autoplay',
//			[
//				'label' => esc_html__( 'Autoplay', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'frontend_available' => true,
//			]
//		);
		
//		$this->add_control(
//			'video_play_on_mobile',
//			[
//				'label' => esc_html__( 'Play On Mobile', 'xstore-core' ),
//				'type' => Controls_Manager::SWITCHER,
//				'condition' => [
//					'autoplay' => 'yes',
//				],
//				'frontend_available' => true,
//			]
//		);
		
		$this->add_control(
			'video_mute',
			[
				'label' => esc_html__( 'Mute', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'video_loop',
			[
				'label' => esc_html__( 'Loop', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'video_controls',
			[
				'label' => esc_html__( 'Player Controls', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'video_download_button',
			[
				'label' => esc_html__( 'Download Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->end_controls_section();
		
		Elementor::get_slider_style_settings($this);
		
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'heading_media_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Media', 'xstore-core' ),
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'media_css_filters',
				'selector' => '{{WRAPPER}} .swiper-slide-image, {{WRAPPER}} .swiper-slide-video',
			]
		);
		
		$this->add_control(
			'media_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-image, {{WRAPPER}} .swiper-slide-video, {{WRAPPER}} .etheme-media-carousel-item-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_lightbox_icon_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Lightbox Icon', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'open_lightbox!' => 'no',
					'lightbox_icon!' => ''
				]
			]
		);
		
		$this->add_control(
			'lightbox_icon_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-lightbox-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'open_lightbox!' => 'no',
					'lightbox_icon!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'lightbox_icon_typography',
				'selector' => '{{WRAPPER}} .etheme-media-carousel-lightbox-icon',
				'condition' => [
					'open_lightbox!' => 'no',
					'lightbox_icon!' => ''
				]
			]
		);
		
		$this->add_control(
			'lightbox_icon_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-lightbox-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'open_lightbox!' => 'no',
					'lightbox_icon!' => ''
				]
			]
		);
		
		$this->add_control(
			'heading_title_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Title', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'title!' => '',
				],
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-media-carousel-title',
				'condition' => [
					'title!' => '',
				],
			]
		);
		
		$this->add_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_caption_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Caption', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'caption!' => '',
				],
			]
		);
		
		$this->add_control(
			'caption_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-caption' => 'color: {{VALUE}}',
				],
				'condition' => [
					'caption!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .etheme-media-carousel-caption',
				'condition' => [
					'caption!' => '',
				],
			]
		);
		
		$this->add_control(
			'caption_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-caption' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'caption!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_date_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Date', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'date!' => '',
				],
			]
		);
		
		$this->add_control(
			'date_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-media-carousel-date' => 'color: {{VALUE}}',
				],
				'condition' => [
					'date!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .etheme-media-carousel-date',
				'condition' => [
					'date!' => '',
				],
			]
		);
		
		$this->add_control(
			'video_style_header',
			[
				'label' => esc_html__( 'Video', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
					'open_lightbox' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'video_play_icon_color',
			[
				'label' => esc_html__( 'Play Icon Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i, {{WRAPPER}} .elementor-custom-embed-play svg' => 'color: {{VALUE}}',
				],
				'condition' => [
                    'video_play_icon' => 'yes',
					'open_lightbox' => 'yes'
				]
			]
		);
		
		$this->add_responsive_control(
			'video_play_icon_size',
			[
				'label' => esc_html__( 'Play Icon Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i, {{WRAPPER}} .elementor-custom-embed-play svg' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'video_play_icon' => 'yes',
					'open_lightbox' => 'yes'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'video_play_icon_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-custom-embed-play i, {{WRAPPER}} .elementor-custom-embed-play svg',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => __('Play Icon Text Shadow', 'xstore-core' ),
					],
				],
				'condition' => [
					'video_play_icon' => 'yes',
					'open_lightbox' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'aspect_ratio',
			[
				'label' => esc_html__( 'Video Lightbox Aspect Ratio', 'xstore-core' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
					'916' => '9:16',
				],
				'default' => '169',
				'prefix_class' => 'elementor-aspect-ratio-',
				'frontend_available' => true,
				'condition' => [
					'open_lightbox' => 'yes'
				]
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render media carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.8
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		$slides_count = count( $settings['items'] );
		
		$this->add_render_attribute( 'wrapper', [
			'class' => [
				'etheme-media-carousel-wrapper',
			]
		]);
		
		$this->add_render_attribute( 'wrapper-entry', [
			'class' => [
				'swiper-entry',
				$settings['arrows_position'],
				$settings['arrows_position_style']
			]
		]);
  
		$this->add_render_attribute( 'carousel', [
			'class' => [
			        'swiper-container',
			        'etheme-media-carousel',
			        'etheme-elementor-slider',
                ]
		]);
		
		$this->add_render_attribute( 'thumbs-wrapper-entry', [
			'class' => [
				'swiper-entry',
				'etheme-media-carousel-thumbs-wrapper',
				$settings['arrows_position'],
				$settings['arrows_position_style']
			]
		]);
		
		$this->add_render_attribute( 'media-info', [
			'class' => [
				'etheme-media-carousel-info',
			]
		]);
		
		$this->add_render_attribute( 'thumbs-carousel', [
			'class' => [
				'swiper-container',
				'etheme-media-carousel-thumbs',
				'etheme-elementor-slider',
			]
		]);
		
//		$this->add_render_attribute( 'item_overlay', [
//			'class' => [
//				'etheme-media-carousel-item-overlay',
//			]
//		]);
		
		if ( $settings['image_stretch']) {
			$this->add_render_attribute( 'carousel', [
				'class' => [
					'swiper-image-stretch',
				]
			]);
			
			$this->add_render_attribute( 'thumbs-carousel', [
				'class' => [
					'swiper-image-stretch',
				]
			]);
        }
		
		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'wrapper-entry' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
                    <div class="swiper-wrapper">
                        <?php
                        foreach ( $settings['items'] as $index => $slide ) : ?>
                            <div class="swiper-slide">
                                <?php $this->render_media($slide); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php
                        if ( 1 < $slides_count && in_array($settings['navigation'], array('both', 'dots')) )
                            Elementor::get_slider_pagination($this, $settings, $edit_mode);
                    ?>
                </div>
	
	            <?php if ( 1 < $slides_count ) {
		            if ( in_array( $settings['navigation'], array( 'both', 'arrows' ) ) )
			            Elementor::get_slider_navigation( $settings, $edit_mode );
		            
	            } ?>
             
            </div>
    
                <?php if ( $settings['show_thumbs'] && 1 < $slides_count ) { ?>
                    <div <?php echo $this->get_render_attribute_string( 'thumbs-wrapper-entry' ); ?>>
                        <div <?php echo $this->get_render_attribute_string( 'thumbs-carousel' ); ?>>
                            <div class="swiper-wrapper">
                                <?php foreach ( $settings['items'] as $index => $slide ) : ?>
                                    <div class="swiper-slide">
                                        <?php $this->render_media($slide, true); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
            <?php } ?>
        </div>
		<?php
    
    }
	
	protected function render_media($item, $thumbnail = false) {
		
		$settings = $this->get_settings_for_display();
		
		$size = $thumbnail ? 'thumbnail' : 'full';
		$media_id = $item[$item['item_type']]['id'];
		
		$this->add_render_attribute( 'item_' . $item['_id'], [
			'class' => 'etheme-media-carousel-item'
		]);
		
		$this->add_render_attribute( 'gallery_item_image_' . $media_id,
			[
				'href' => $item[$item['item_type']]['url'],
			]
		);
		
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'item_'.$item['_id'], [
				'class' => 'elementor-clickable',
			] );
			$this->add_render_attribute( 'gallery_item_image_'.$media_id, [
				'class' => 'elementor-clickable',
			] );
		}
		
		if ( $settings['open_lightbox'] == 'yes' && ! $thumbnail ) :
			
            if ( $item['item_type'] == 'video' ) :
                   $lightbox_options = [
		            'type' => 'video',
		            'videoType' => 'hosted',
		            'url' => $item['video']['url'],
		            'modalOptions' => [
			            'id' => 'elementor-lightbox-' . $this->get_id(),
//					    'entranceAnimation' => $settings['lightbox_content_animation'],
//					    'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
//					    'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
			            'videoAspectRatio' => $settings['aspect_ratio'],
		            ],
		            'videoParams' => $this->get_hosted_params(),
	            ];
	
	            $this->add_render_attribute( 'item_'.$item['_id'], [
		            'class' => [
			            'e-hosted-video',
			            'elementor-open-lightbox'
		            ],
		            'data-elementor-open-lightbox' => 'yes',
		            'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
	            ] );
		
		    else :
        
                if ( $media_id ) {
	                $this->add_lightbox_data_attributes( 'gallery_item_image_' . $media_id, $media_id, $settings['open_lightbox'], 'all-' . $this->get_id() );
	
	                $image_src = wp_get_attachment_image_src( $media_id );
	
	                $image_data = [
		                'alt'    => get_post_meta( $media_id, '_wp_attachment_image_alt', true ),
		                'src'    => $image_src['0'],
		                'width'  => $image_src['1'],
		                'height' => $image_src['2'],
	                ];
	
	                $this->add_render_attribute( 'gallery_item_image_' . $media_id,
		                [
			                'data-thumbnail' => $image_data['src'],
			                'data-width'     => $image_data['width'],
			                'data-height'    => $image_data['height'],
			                'alt'            => $image_data['alt'],
		                ]
	                );
                }
                
            endif;
		
		endif;
		
		?>

		
        <div <?php echo $this->get_render_attribute_string( 'item_' . $item['_id'] ); ?>>
            <?php
            if ( !$thumbnail ) :
                
                if ( $settings['open_lightbox'] == 'yes' ) {
                    echo '<a ' . $this->get_render_attribute_string( 'gallery_item_image_' . $media_id ). '>';
                } ?>
                
            <?php endif;
            
                    if ( $item['item_type'] == 'video') {
                        
                        ob_start();
                            $this->render_hosted_video( $item['video']['url'], $thumbnail );
                        $video_html = ob_get_clean();
	                    
                        \Elementor\Utils::print_unescaped_internal_string( $video_html ); // XSS ok.
                        
                        if ( !$thumbnail ) {
	
                            $this->render_media_info( $media_id );
                            
//	                        if ( $settings['overlay'])
//	                            echo '<div ' . $this->get_render_attribute_string( 'item_overlay' ) . '>';
	
                                if ( 'yes' === $settings['video_play_icon'] ) {
                                    $this->get_video_play_icon();
                                }
	
//	                        if ( $settings['overlay'])
//	                            echo '</div>'; // item_overlay
	                    }
	                    
                    }
                    
                    else {
                        
                        add_filter('wp_get_attachment_image_attributes', array($this, 'filter_image_attr'));
	                    // tweak to set size setting for getting image via default Elementor functions below
	                    $item['image_size'] = $size;
                        echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );
	                    unset($item['image_size']);
                        remove_filter('wp_get_attachment_image_attributes', array($this, 'filter_image_attr'));
                        
                        if ( !$thumbnail ) {
                         
	                        $this->render_media_info( $media_id );
	                        
//                            if ( $settings['overlay']) {
//	                            echo '<div ' . $this->get_render_attribute_string( 'item_overlay' ) . '></div>';
//                            }
                        }
                        
                    }
                    
            if ( $settings['open_lightbox'] == 'yes' && !$thumbnail ) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
	}
	
	/**
	 * @since 4.0.8
	 * @access private
	 */
	private function get_hosted_params() {
		$settings = $this->get_settings_for_display();
		
		$params = [
//			'autoplay', // not yet implemented
            'loop',
            'controls'
        ];
		$video_params = [];
		
		foreach ( $params as $option_name ) {
			if ( $settings[ 'video_'.$option_name ] ) {
				$video_params[ $option_name ] = '';
			}
		}
		
		if ( $settings['video_mute'] ) {
			$video_params['muted'] = 'muted';
		}
//
//		if ( $settings['play_on_mobile'] ) {
//			$video_params['playsinline'] = '';
//		}
		
		if ( ! $settings['video_download_button'] ) {
			$video_params['controlsList'] = 'nodownload';
		}
		
		return $video_params;
	}
	
	/**
	 * Renders Video HTML content.
	 *
	 * @param       $video_url
	 * @param false $thumbnail
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	private function render_hosted_video($video_url, $thumbnail = false) {
		if ( empty( $video_url ) ) {
			return;
		}
		
		$video_params = !$thumbnail ? $this->get_hosted_params() : array();
		?>
        <video class="elementor-video swiper-slide-video" src="<?php echo esc_url( $video_url ); ?>" <?php \Elementor\Utils::print_html_attributes( $video_params ); ?>></video>
		<?php
	}
	
	/**
	 * Renders Play Icon HTML.
	 *
	 * @since 4.0.8
	 *
	 * @return void
	 */
	private function get_video_play_icon() {
	    ?>
        <div class="elementor-custom-embed-play" role="button">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M38 23.35L20.55 11.45C19.95 11.05 19.15 11 18.5 11.35C17.85 11.7 17.45 12.35 17.45 13.1V36.9C17.45 37.65 17.85 38.3 18.5 38.65C18.8 38.8 19.1 38.9 19.45 38.9C19.85 38.9 20.25 38.8 20.55 38.55L38 26.65C38.55 26.3 38.85 25.65 38.85 25C38.9 24.35 38.55 23.7 38 23.35ZM25 0C11.2 0 0 11.2 0 25C0 38.8 11.2 50 25 50C38.8 50 50 38.8 50 25C50 11.2 38.8 0 25 0ZM25 46C13.4 46 4 36.6 4 25C4 13.4 13.4 4 25 4C36.6 4 46 13.4 46 25C46 36.6 36.6 46 25 46Z" fill="currentColor"/>
            </svg>
            <span class="elementor-screen-only"><?php echo esc_html__( 'Play Video', 'xstore-core' ); ?></span>
        </div>
        <?php
	}
	
	/**
	 * Renders Media Info (caption, title, date).
	 *
	 * @param $id
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function render_media_info($id) {
		
		$settings = $this->get_settings_for_display();
		
		$post_data = array();
		
		if ( $settings['title'] ) {
			$title = get_post_field('post_title', $id);
			if ( $title )
				$post_data[] = '<h4 class="etheme-media-carousel-title">'.$title.'</h4>';
		}
		
		if ( $settings['caption'] ) {
			$caption = wp_get_attachment_caption($id);
			if ( $caption )
				$post_data[] = '<span class="etheme-media-carousel-caption">'.$caption.'</span>';
		}
		if ( $settings['date'] ) {
			$custom_date_format = empty( $settings['custom_date_format'] ) ? 'd F Y' : $settings['custom_date_format'];
			
			$format_options = [
				'default' => 'd F Y',
				'0' => 'F j, Y',
				'1' => 'Y-m-d',
				'2' => 'm/d/Y',
				'3' => 'd/m/Y',
				'custom' => $custom_date_format,
			];
			$post_data[] = '<span class="etheme-media-carousel-date">'.get_the_date( $format_options[ $settings['date_format'] ], $id ).'</span>';
		}
		
        ?>
        
        <div <?php echo $this->get_render_attribute_string( 'media-info' ); ?>>
            <?php
                if ( !!$settings['lightbox_icon'] )
                    echo $this->render_lightbox_icon();
                
                echo implode( '', $post_data );
                
            ?>
        </div>
        
        <?php
		
	}
	
	/**
	 * Lightbox Icon.
	 *
	 * @since 4.0.8
	 *
	 * @return string
	 */
	public function render_lightbox_icon() {
		return '<span class="etheme-media-carousel-lightbox-icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.784 22.8l-6.168-6.144c1.584-1.848 2.448-4.176 2.448-6.576 0-5.52-4.488-10.032-10.032-10.032-5.52 0-10.008 4.488-10.008 10.008s4.488 10.032 10.032 10.032c2.424 0 4.728-0.864 6.576-2.472l6.168 6.144c0.144 0.144 0.312 0.216 0.48 0.216s0.336-0.072 0.456-0.192c0.144-0.12 0.216-0.288 0.24-0.48 0-0.192-0.072-0.384-0.192-0.504zM18.696 10.080c0 4.752-3.888 8.64-8.664 8.64-4.752 0-8.64-3.888-8.64-8.664 0-4.752 3.888-8.64 8.664-8.64s8.64 3.888 8.64 8.664z"></path>
            </svg>
        </span>';
	}
	
	/**
	 * Adds swiper-slider-image class for images via filter.
	 *
	 * @param $attr
	 * @return mixed
	 *
	 * @since 4.0.8
	 *
	 */
	public function filter_image_attr($attr) {
        $attr['class'] = isset($attr['class']) ? $attr['class']. ' swiper-slide-image' : ' swiper-slide-image';
		return $attr;
	}
}
