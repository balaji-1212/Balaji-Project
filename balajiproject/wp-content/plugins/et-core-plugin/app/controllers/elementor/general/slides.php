<?php

namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Slides widget.
 *
 * @since      4.2.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Slides extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_slides';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Slides', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-slider';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'slides', 'carousel', 'image', 'title', 'slider', 'slideshow' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.10
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
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-slides' ];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'imagesloaded', 'etheme_elementor_slider' ];
	}
	
	public static function get_button_sizes() {
		return [
			'xs' => esc_html__( 'Extra Small', 'xstore-core' ),
			'sm' => esc_html__( 'Small', 'xstore-core' ),
			'md' => esc_html__( 'Medium', 'xstore-core' ),
			'lg' => esc_html__( 'Large', 'xstore-core' ),
			'xl' => esc_html__( 'Extra Large', 'xstore-core' ),
		];
	}
	
	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'xstore-core' ),
			]
		);

        $this->add_control(
            'content_animation',
            [
                'label' => esc_html__( 'Content Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fadeIn',
                'options' => [
                    '' => esc_html__( 'None', 'xstore-core' ),
                    'fadeIn' => esc_html__('Fade', 'xstore-core'),
                    'fadeInDown' => esc_html__( 'Down', 'xstore-core' ),
                    'fadeInUp' => esc_html__( 'Up', 'xstore-core' ),
                    'fadeInRight' => esc_html__( 'Right', 'xstore-core' ),
                    'fadeInLeft' => esc_html__( 'Left', 'xstore-core' ),
                    'zoomIn' => esc_html__( 'Zoom', 'xstore-core' ),
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'boxed',
            [
                'label' 		=>	__( 'Boxed content', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
                'description' => __('Useful in combination of stretch section', 'xstore-core'),
            ]
        );

        $this->add_responsive_control(
            'slides_height',
            [
                'label' => esc_html__( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 400,
                ],
                'size_units' => [ 'px', 'vh', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->start_controls_tabs( 'slides_repeater' );
		
		$repeater->start_controls_tab( 'background', [ 'label' => esc_html__( 'Background', 'xstore-core' ) ] );
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg',
				'fields_options' => [
					'image' => [
						'responsive' => false,
						'selectors' => [],
					],
                ]
			]
		);
		
//		$repeater->add_control(
//			'background_color',
//			[
//				'label' => esc_html__( 'Color', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::COLOR,
//				'default' => '#bbbbbb',
//				'selectors' => [
//					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-color: {{VALUE}}',
//				],
//			]
//		);
//
//		$repeater->add_control(
//			'background_image',
//			[
//				'label' => __( 'Image', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::MEDIA,
//				'selectors' => [
//					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-image: url({{URL}})',
//				],
//			]
//		);
//
//		$repeater->add_control(
//			'background_size',
//			[
//				'label' => __( 'Size', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => 'cover',
//				'options' => [
//					'cover' => __( 'Cover', 'xstore-core' ),
//					'contain' => __( 'Contain', 'xstore-core' ),
//					'auto' => __( 'Auto', 'xstore-core' ),
//				],
//				'selectors' => [
//					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-size: {{VALUE}}',
//				],
//				'conditions' => [
//					'terms' => [
//						[
//							'name' => 'background_image[url]',
//							'operator' => '!=',
//							'value' => '',
//						],
//					],
//				],
//			]
//		);
//
		$repeater->add_control(
			'background_ken_burns',
			[
				'label' => esc_html__( 'Ken Burns Effect', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'overflow: hidden;',
				],
				'condition' => [
					'background_background' => ['classic'],
					'background_image[url]!' => ''
                ]
			]
		);

		$repeater->add_control(
			'zoom_direction',
			[
				'label' => esc_html__( 'Zoom Direction', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'in',
				'options' => [
					'in' => esc_html__( 'In', 'xstore-core' ),
					'out' => esc_html__( 'Out', 'xstore-core' ),
				],
				'condition' => [
					'background_background' => ['classic'],
					'background_image[url]!' => '',
                    'background_ken_burns!' => ''
				]
			]
		);

		$repeater->add_control(
			'background_overlay',
			[
				'label' => esc_html__( 'Background Overlay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'background_background' => ['classic'],
					'background_image[url]!' => '',
				]
			]
		);
		
		$repeater->add_control(
			'background_overlay_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'condition' => [
					'background_background' => ['classic'],
					'background_image[url]!' => '',
                    'background_overlay' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .etheme-background-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'background_overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'xstore-core' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn' => 'Color Burn',
					'hue' => 'Hue',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'exclusion' => 'Exclusion',
					'luminosity' => 'Luminosity',
				],
				'condition' => [
					'background_background' => ['classic'],
					'background_image[url]!' => '',
					'background_overlay' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .etheme-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->start_controls_tab( 'content', [ 'label' => esc_html__( 'Content', 'xstore-core' ) ] );
		
		$repeater->add_control(
			'subheading',
			[
				'label' => esc_html__( 'SubTitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Sub heading here', 'xstore-core' ),
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'heading',
			[
				'label' => esc_html__( 'Title & Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Slide Heading', 'xstore-core' ),
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'xstore-core' ),
				'show_label' => false,
			]
		);
		
		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Read more', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'link_click',
			[
				'label' => esc_html__( 'Apply Link On', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'slide' => esc_html__( 'Whole Slide', 'xstore-core' ),
					'button' => esc_html__( 'Button Only', 'xstore-core' ),
				],
				'default' => 'button',
				'condition' => [
                    'link[url]!' => ''
                ]
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->start_controls_tab( 'style', [ 'label' => esc_html__( 'Style', 'xstore-core' ) ] );
		
		$repeater->add_control(
			'custom_style',
			[
				'label' => esc_html__( 'Custom', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Set custom style that will only affect this specific slide.', 'xstore-core' ),
                'render_type' => 'none'
			]
		);
		
		$repeater->add_control(
			'text_align',
			[
				'label' => esc_html__( 'Text Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'custom_style' => 'yes'
				]
			]
		);
		
		$repeater->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Content Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .etheme-slide-subheading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .etheme-slide-heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .etheme-slide-description' => 'color: {{VALUE}}',
//					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .etheme-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				'condition' => [
					'custom_style' => 'yes'
				]
			]
		);
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'repeater_text_shadow',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents',
				'condition' => [
					'custom_style' => 'yes'
				]
			]
		);
		
		$repeater->add_responsive_control(
			'content_max_width',
			[
				'label' => esc_html__( 'Content Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'size' => '70',
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_style' => 'yes'
				]
			]
		);
		
		$repeater->add_control(
			'horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto; margin-left: 0',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto; margin-right: 0',
				],
				'condition' => [
                   'custom_style' => 'yes'
                ]
			]
		);
		
		$repeater->add_control(
			'vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'condition' => [
					'custom_style' => 'yes'
				]
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->end_controls_tabs();
		
		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'show_label' => true,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
                        'subheading' => esc_html__( 'Slide 1 Sub Heading', 'xstore-core' ),
						'heading' => esc_html__( 'Slide 1 Heading', 'xstore-core' ),
						'description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
						'button_text' => esc_html__( 'Read More', 'xstore-core' ),
                        'link' => '#',
                        'background_background' => 'classic',
						'background_color' => '#a4004f',
					],
					[
                        'subheading' => esc_html__( 'Slide 2 Sub Heading', 'xstore-core' ),
						'heading' => esc_html__( 'Slide 2 Heading', 'xstore-core' ),
						'description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
						'button_text' => esc_html__( 'Read More', 'xstore-core' ),
                        'link' => '#',
                        'background_background' => 'classic',
						'background_color' => '#1565c0',
					],
					[
                        'subheading' => esc_html__( 'Slide 3 Sub Heading', 'xstore-core' ),
						'heading' => esc_html__( 'Slide 3 Heading', 'xstore-core' ),
						'description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
						'button_text' => esc_html__( 'Read More', 'xstore-core' ),
                        'link' => '#',
                        'background_background' => 'classic',
						'background_color' => '#2e7d32',
					],
				],
				'title_field' => '{{{ heading }}}',
			]
		);
		
		$this->end_controls_section();
		
//		$this->start_controls_section(
//			'section_slider_options',
//			[
//				'label' => esc_html__( 'Slider Options', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SECTION,
//			]
//		);
//
//		$this->add_control(
//			'navigation',
//			[
//				'label' => esc_html__( 'Navigation', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => 'both',
//				'options' => [
//					'both' => esc_html__( 'Arrows and Dots', 'xstore-core' ),
//					'arrows' => esc_html__( 'Arrows', 'xstore-core' ),
//					'dots' => esc_html__( 'Dots', 'xstore-core' ),
//					'none' => esc_html__( 'None', 'xstore-core' ),
//				],
//				'frontend_available' => true,
//			]
//		);
//
//		$this->add_control(
//			'autoplay',
//			[
//				'label' => esc_html__( 'Autoplay', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'default' => 'yes',
//				'frontend_available' => true,
//			]
//		);
//
//		$this->add_control(
//			'pause_on_hover',
//			[
//				'label' => esc_html__( 'Pause on Hover', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'default' => 'yes',
//				'render_type' => 'none',
//				'frontend_available' => true,
//				'condition' => [
//					'autoplay!' => '',
//				],
//			]
//		);
//
//		$this->add_control(
//			'pause_on_interaction',
//			[
//				'label' => esc_html__( 'Pause on Interaction', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'default' => 'yes',
//				'render_type' => 'none',
//				'frontend_available' => true,
//				'condition' => [
//					'autoplay!' => '',
//				],
//			]
//		);
//
//		$this->add_control(
//			'autoplay_speed',
//			[
//				'label' => esc_html__( 'Autoplay Speed', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::NUMBER,
//				'default' => 5000,
//				'condition' => [
//					'autoplay' => 'yes',
//				],
//				'selectors' => [
//					'{{WRAPPER}} .swiper-slide' => 'transition-duration: calc({{VALUE}}ms*1.2)',
//				],
//				'render_type' => 'none',
//				'frontend_available' => true,
//			]
//		);
//
//		$this->add_control(
//			'infinite',
//			[
//				'label' => esc_html__( 'Infinite Loop', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'default' => 'yes',
//				'frontend_available' => true,
//			]
//		);
//
//		$this->add_control(
//			'transition',
//			[
//				'label' => esc_html__( 'Transition', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => 'slide',
//				'options' => [
//					'slide' => esc_html__( 'Slide', 'xstore-core' ),
//					'fade' => esc_html__( 'Fade', 'xstore-core' ),
//				],
//				'frontend_available' => true,
//			]
//		);
//
//		$this->add_control(
//			'transition_speed',
//			[
//				'label' => esc_html__( 'Transition Speed', 'xstore-core' ) . ' (ms)',
//				'type' => \Elementor\Controls_Manager::NUMBER,
//				'default' => 500,
//				'render_type' => 'none',
//				'frontend_available' => true,
//			]
//		);
		
		// slider settings
		Elementor::get_slider_general_settings($this);

		
		$this->update_control( 'arrows_style', [
			'default' 	=>	'style-2',
		] );
		
		$this->update_control( 'arrows_position', [
			'default' 	=>	'middle-inbox',
			'options'		=> [
				'middle' 			=>	esc_html__( 'Middle Outside', 'xstore-core' ),
				'middle-inside' 	=>	esc_html__( 'Middle Center', 'xstore-core' ),
				'middle-inbox' 	=>	esc_html__( 'Middle Inside', 'xstore-core' ),
            ]
		] );

        $this->update_control( 'arrows_position_style', [
            'default' 	=>	'arrows-always',
        ] );

		
		$this->update_control( 'slides_per_view', [
			'default' 	=>	'1',
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
		
		$this->start_injection( [
			'type' => 'section',
			'at' => 'start',
			'of' => 'section_slider',
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
		
		$this->end_injection();
		
		$this->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'space_between',
		] );
		
		$this->add_control(
			'overflow',
			[
				'label' 		=>	__( 'Overflow visible', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'effect' => ['slide']
				],
				'render_type' => 'template', // reinit slider js to create few extra duplicate slides if loop mode
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .swiper-container' => 'overflow: visible;',
				],
			]
		);
		
		$this->add_control(
			'lazyload',
			[
				'label' 		=>	__( 'Lazyload', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
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
		
		$this->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'arrows_position_style',
		] );
		
		$this->add_control(
			'dots_header',
			[
				'label' => esc_html__( 'Dots', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$this->add_control(
			'dots_type',
			[
				'label' 		=> esc_html__( 'Type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'bullets' 	=>	esc_html__( 'Bullets', 'xstore-core' ),
					'fraction' 	=>	esc_html__( 'Fraction', 'xstore-core' ),
					'numbers' 	=>	esc_html__( 'Numbers', 'xstore-core' ),
				],
				'frontend_available' => true,
				'default'	=> 'numbers',
				'condition' => [ 'navigation' => ['both', 'dots'] ]
			]
		);
		
		$this->add_control(
			'dots_position',
			[
				'label' 		=> esc_html__( 'Position', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'inside' 			=>	esc_html__( 'Inside', 'xstore-core' ),
					'outside' 			=>	esc_html__( 'Outside', 'xstore-core' ),
				],
				'default'	=> 'inside',
				'condition' => [ 'navigation' => ['both', 'dots'] ]
			]
		);

        $this->add_control(
            'dots_color_schema',
            [
                'label' 		=> esc_html__( 'Color Schema', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SELECT,
                'options'		=> [
                    'dark' 	=>	esc_html__( 'Dark', 'xstore-core' ),
                    'white' 	=>	esc_html__( 'White', 'xstore-core' ),
                ],
                'default'	=> 'white',
                'condition' => [ 'navigation' => ['both', 'dots'] ],
                'selectors_dictionary'  => [
                    'dark'          => '',
                    'white'         => '#fff',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-fraction, {{WRAPPER}} .swiper-pagination .swiper-pagination-number' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		
		$this->end_injection();
		
//		$this->end_controls_section();
		
		// slider style settings
		Elementor::get_slider_style_settings($this);
		
//		$this->start_controls_section(
//			'section_style_slides',
//			[
//				'label' => esc_html__( 'Slides', 'xstore-core' ),
//				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//			]
//		);
		
		$this->remove_control('slider_vertical_align');
		$this->remove_control('autoheight');
		
//		$this->update_control( 'arrows_color', [
//			'default' => '#ffffff'
//		] );
		
		$this->update_control( 'dots_color', [
			'condition' => [
				'navigation' => ['both', 'dots'],
				'dots_type' => ['bullets']
			],
		] );
		
		$this->update_control( 'dots_active_color', [
			'condition' => [
				'navigation' => ['both', 'dots'],
				'dots_type' => ['bullets']
			],
		] );
		
		$this->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'dots_color',
		] );
		
		$this->add_control(
			'dots_text_color',
			[
				'label' 	=> esc_html__( 'Text Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-fraction, {{WRAPPER}} .swiper-pagination .swiper-pagination-number' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => ['both', 'dots'],
					'dots_type' => ['fraction', 'numbers']
				],
			]
		);
		
		$this->end_injection();
		
		$this->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'dots_active_color',
		] );
		
		$this->add_control(
			'dots_active_text_color',
			[
				'label' 	=> esc_html__( 'Text Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-current,
					{{WRAPPER}} .swiper-pagination .swiper-pagination-number:hover,
					{{WRAPPER}} .swiper-pagination .swiper-pagination-number-active' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => ['both', 'dots'],
					'dots_type' => ['fraction', 'numbers']
				],
			]
		);
		
		$this->end_injection();
		
//		$this->start_injection( [
//			'type' => 'control',
//			'at'   => 'after',
//			'of'   => 'space_between',
//		] );
		
		$this->start_controls_section(
			'section_style_slide',
			[
				'label' => esc_html__( 'Slides', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'slides_text_align',
			[
				'label' => esc_html__( 'Text Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_max_width',
			[
				'label' => esc_html__( 'Content Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'size' => '70',
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-contents' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'slides_horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-contents' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto',
				],
			]
		);
		
		$this->add_control(
			'slides_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'slides_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

//		$this->add_group_control(
//			\Elementor\Group_Control_Text_Shadow::get_type(),
//			[
//				'name' => 'text_shadow',
//				'selector' => '{{WRAPPER}} .swiper-slide-contents',
//			]
//		);
		
//		$this->end_injection();

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_subtitle',
			[
				'label' => esc_html__( 'Sub Title', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'subheading_spacing',
			[
				'label' => esc_html__( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner .etheme-slide-subheading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_control(
			'subheading_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-subheading' => 'color: {{VALUE}}',
				
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subheading_typography',
				'selector' => '{{WRAPPER}} .etheme-slide-subheading',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_spacing',
			[
				'label' => esc_html__( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner .etheme-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .etheme-slide-heading',
				'fields_options' => [
					'typography' => [
                        'default' => 'custom'
                    ],
					'font_weight' => [
						'default' => 'bold',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_spacing',
			[
				'label' => esc_html__( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner .etheme-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-description' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .etheme-slide-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .etheme-slide-button',
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'xstore-core' ) ] );

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-slide-button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [ 'label' => esc_html__( 'Hover', 'xstore-core' ) ] );

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-slide-button:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-slide-button',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

//		$this->start_controls_section(
//			'section_style_navigation',
//			[
//				'label' => esc_html__( 'Navigation', 'xstore-core' ),
//				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//				'condition' => [
//					'navigation' => [ 'arrows', 'dots', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'heading_style_arrows',
//			[
//				'label' => esc_html__( 'Arrows', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'separator' => 'before',
//				'condition' => [
//					'navigation' => [ 'arrows', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'arrows_position',
//			[
//				'label' => esc_html__( 'Arrows Position', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => 'inside',
//				'options' => [
//					'inside' => esc_html__( 'Inside', 'xstore-core' ),
//					'outside' => esc_html__( 'Outside', 'xstore-core' ),
//				],
//				'prefix_class' => 'elementor-arrows-position-',
//				'condition' => [
//					'navigation' => [ 'arrows', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'arrows_size',
//			[
//				'label' => esc_html__( 'Arrows Size', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SLIDER,
//				'range' => [
//					'px' => [
//						'min' => 20,
//						'max' => 60,
//					],
//				],
//				'selectors' => [
//					'{{WRAPPER}} .etheme-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
//				],
//				'condition' => [
//					'navigation' => [ 'arrows', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'arrows_color',
//			[
//				'label' => esc_html__( 'Arrows Color', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::COLOR,
//				'selectors' => [
//					'{{WRAPPER}} .etheme-swiper-button' => 'color: {{VALUE}}',
//					'{{WRAPPER}} .etheme-swiper-button svg' => 'fill: {{VALUE}}',
//				],
//				'condition' => [
//					'navigation' => [ 'arrows', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'heading_style_dots',
//			[
//				'label' => esc_html__( 'Dots', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'separator' => 'before',
//				'condition' => [
//					'navigation' => [ 'dots', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'dots_position',
//			[
//				'label' => esc_html__( 'Dots Position', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => 'inside',
//				'options' => [
//					'outside' => esc_html__( 'Outside', 'xstore-core' ),
//					'inside' => esc_html__( 'Inside', 'xstore-core' ),
//				],
//				'prefix_class' => 'elementor-pagination-position-',
//				'condition' => [
//					'navigation' => [ 'dots', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'dots_size',
//			[
//				'label' => esc_html__( 'Dots Size', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SLIDER,
//				'range' => [
//					'px' => [
//						'min' => 5,
//						'max' => 15,
//					],
//				],
//				'selectors' => [
//					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
//					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
//					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
//				],
//				'condition' => [
//					'navigation' => [ 'dots', 'both' ],
//				],
//			]
//		);
//
//		$this->add_control(
//			'dots_color',
//			[
//				'label' => esc_html__( 'Dots Color', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::COLOR,
//				'selectors' => [
//					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
//				],
//				'condition' => [
//					'navigation' => [ 'dots', 'both' ],
//				],
//			]
//		);
//
//		$this->end_controls_section();
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		if ( empty( $settings['slides'] ) ) {
			return;
		}
		
		$this->add_render_attribute( 'button', 'class', [ 'elementor-button', 'etheme-slide-button' ] );
		
		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}
		
		$slides = [];
		$slide_count = 0;
		
		foreach ( $settings['slides'] as $slide ) {
			$slide_html = '';
			$btn_attributes = '';
			$slide_attributes = '';
			$slide_element = 'div';
			$btn_element = 'div';
			
			if ( ! empty( $slide['link']['url'] ) ) {
				$this->add_link_attributes( 'slide_link' . $slide_count, $slide['link'] );
				
				if ( 'button' === $slide['link_click'] ) {
					$btn_element = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				} else {
					$slide_element = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				}
			}
			
			$slide_html .= '<' . $slide_element . ' class="swiper-slide-inner'.($settings['boxed'] ? ' container' : '').'" ' . $slide_attributes . '>';
			
			$slide_html .= '<div class="swiper-slide-contents">';
			
            if ( $slide['subheading'] ) {
	            $slide_html .= '<div class="etheme-slide-subheading">' . $slide['subheading'] . '</div>';
            }
            
			if ( $slide['heading'] ) {
				$slide_html .= '<div class="etheme-slide-heading">' . $slide['heading'] . '</div>';
			}
			
			if ( $slide['description'] ) {
				$slide_html .= '<div class="etheme-slide-description">' . $slide['description'] . '</div>';
			}
			
			if ( $slide['button_text'] ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button_text'] . '</' . $btn_element . '>';
			}
			
			$slide_html .= '</div></' . $slide_element . '>';
			
			if ( 'yes' === $slide['background_overlay'] ) {
				$slide_html = '<div class="etheme-background-overlay"></div>' . $slide_html;
			}
			
			$ken_class = '';
			
			if ( $slide['background_ken_burns'] ) {
				$ken_class = ' elementor-ken-burns elementor-ken-burns--' . $slide['zoom_direction'];
			}
			
			$bg_image = '';
			if ( isset($slide['background_image']) && isset($slide['background_image']['url']) && !empty($slide['background_image']['url']) ) {
			    if ( $settings['lazyload'] && $slide_count > 0) {
				    $ken_class .= ' swiper-lazy';
				    $bg_image = ' data-background="' . esc_url( $slide['background_image']['url'] ) . '"';
			    }
                else
			    $bg_image = ' style="background-image: url('.esc_url($slide['background_image']['url']).');"';
            }
			
			$slide_html = '<div class="swiper-slide-bg' . $ken_class . '"' . $bg_image .'></div>' . $slide_html;
			
			$slides[] = '<div class="elementor-repeater-item-' . $slide['_id'] . ' swiper-slide">' . $slide_html . '</div>';
			$slide_count++;
		}
		
		$direction = is_rtl() ? 'rtl' : 'ltr';
		
		$show_dots = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );
		
		$slides_count = count( $settings['slides'] );
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		
		$this->add_render_attribute( 'wrapper', [
				'class' =>
					[
						'etheme-elementor-swiper-entry',
						'swiper-entry',
						$settings['arrows_position'],
						$settings['arrows_position_style'],
					]
			]
		);
		
		$this->add_render_attribute( 'wrapper-inner',
			[
				'class' =>
					[
						'swiper-container',
						'etheme-elementor-slider',
						'swiper-container-main'
					],
				'data-animation' => $settings['content_animation']
			]
		);
		$this->add_render_attribute( 'items-wrapper', 'class', ['swiper-wrapper', 'etheme-elementor-slides']);
		
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
                <div <?php $this->print_render_attribute_string( 'items-wrapper' ); ?>>
					<?php // PHPCS - Slides for each is safe. ?>
					<?php echo implode( '', $slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
                
                <?php
                    if ( 1 < $slides_count ) {
                        if ( in_array($settings['navigation'], array('both', 'dots')) )
                            Elementor::get_slider_pagination($this, $settings, $edit_mode);
                    }
                ?>
			</div>
	
	        <?php
	
	        if ( 1 < $slides_count ) {
                if ( in_array($settings['navigation'], array('both', 'arrows')) )
                    Elementor::get_slider_navigation( $settings, $edit_mode );
	        } ?>
         
		</div>
		<?php
	}
}
