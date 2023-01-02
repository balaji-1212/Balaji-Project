<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
/**
 * Icon Box widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Icon_Box extends \Elementor\Widget_Base {
	
	use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_icon_box';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Icon Box', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-icon-box';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'icon', 'list', 'info', 'image', 'order' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.12
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
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
	    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
		    return [ 'etheme_lottie' ];
	    return [];
	}
	
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
	    $styles = ['etheme-elementor-icon-box'];
		
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
			$styles[] = 'etheme-elementor-lottie';
		
		return $styles;
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
	 * Register controls.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	protected function register_controls() {
		
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'icon_type',
			[
				'label' => __( 'Icon Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'lottie',
				'options' => [
					'icon' => __( 'Icon', 'xstore-core' ),
					'lottie' => __( 'Lottie Icon', 'xstore-core' ),
					'image' => __( 'Image', 'xstore-core' ),
					'text' => __( 'Custom Text', 'xstore-core' ),
				],
			]
		);
		
		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-gift',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'condition' => ['icon_type' => 'icon'],
			]
		);
		
		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => ET_CORE_URL . 'app/assets/img/widgets/icon-box/default.png'
				],
				'condition' => ['icon_type' => 'image'],
			]
		);
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'         => 'image_size',
				'label'        => __( 'Image Size', 'xstore-core' ),
				'default'      => 'full',
				'condition' => [
                    'icon_type' => 'image',
                    'image[url]!' => ''
                ],
			]
		);
		
		$this->add_control(
			'icon_text',
            [
				'label' => esc_html__('Custom Text', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('AB', 'xstore-core'),
				'condition' => [
					'icon_type' => 'text',
				],
			]
		);
		
		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false,
			]
		);
		
		$this->add_control(
			'vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'xstore-core' ),
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
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'          => 'center',
                    'bottom' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box' => 'align-items: {{VALUE}};',
				],
                'condition' => [
                    'icon_position' => ['left', 'right']
                ],
			]
		);
		
		$this->add_responsive_control(
			'align',
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
				'prefix_class' => 'elementor%s-align-',
				'default' => 'center',
			]
		);
		
		$this->add_control(
			'icon_position_outside',
			[
				'label' => __( 'Icon Outside', 'xstore-core' ),
				'separator' => 'before',
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'bordered_layout',
			[
				'label' => __( 'Bordered Layout', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'selectors'				=> [
					'{{WRAPPER}} .etheme-icon-box' => 'padding: 15px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// place here general style to make good priority of settings in editor
		$this->start_controls_section(
			'general_style',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .etheme-icon-box',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .etheme-icon-box',
				'separator' => 'before',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => 1,
							'left' => 1,
							'right' => 1,
							'bottom' => 1
						],
					],
					'color' => [
						'default' => '#e1e1e1',
					]
				],
                'condition' => [
                    'bordered_layout!' => ''
                ]
			]
		);
		
		$this->add_responsive_control(
			'padding',
			[
				'label' 				=> esc_html__('Padding', 'xstore-core'),
				'type' 					=>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' 			=> ['px', 'em', '%'],
				'selectors'				=> [
					'{{WRAPPER}} .etheme-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .etheme-icon-box',
			]
		);
		
		$this->end_controls_section();
		
		Elementor::get_lottie_settings($this, [
			'icon_type' => 'lottie'
		]);
		
		$this->remove_responsive_control('lottie_align');
		$this->remove_control('lottie_link_to');
		$this->remove_control('lottie_custom_link');
  
		$this->update_control( 'lottie_max_width', [
			'default' => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
		] );
		
		$this->start_injection( [
			'type' => 'section',
			'at' => 'end',
			'of' => 'lottie_max_width',
		] );
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'lottie_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .etheme-icon-box-icon',
			]
		);
		
		$this->add_responsive_control(
			'lottie_padding',
			[
				'label' 				=> esc_html__('Padding', 'xstore-core'),
				'type' 					=>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' 			=> ['px', 'em', '%'],
				'selectors'				=> [
					'{{WRAPPER}} .etheme-icon-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'lottie_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => '%'
				],
				'tablet_default' => [
					'unit' => '%'
				],
				'mobile_default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'lottie_offset',
			[
				'label' => __( 'Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'em' => [
						'min' => -10,
						'max' => 10,
						'step' => .1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--icon-offset: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position_outside!' => ''
				]
			]
		);
		
		$this->add_control(
			'lottie_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--icon-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_injection();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'title',
            [
				'label' => esc_html__('Title', 'xstore-core'),
				'separator' => 'before',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Title', 'xstore-core'),
				'placeholder' => esc_html__( 'Enter your title', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
                'condition' => [
                    'title!' => ''
                ]
			]
		);
		
		$this->add_control(
			'subtitle',
            [
				'label' => esc_html__('Subtitle', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'default' => esc_html__('SUBTITLE', 'xstore-core'),
				'placeholder' => esc_html__( 'Enter your subtitle', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'subtitle_tag',
			[
				'label' => esc_html__( 'Subtitle HTML Tag', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h5',
				'condition' => [
					'subtitle!' => ''
				]
			]
		);
		
		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'xstore-core' ),
				'placeholder' => esc_html__( 'Enter your description', 'xstore-core' ),
				'rows' => 10,
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'default' => esc_html__('Read More', 'xstore-core'),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'default' => [
					'url' => '#',
				],
			]
		);
		
		$this->add_control(
			'button_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);
		
		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'xstore-core' ),
					'right' => esc_html__( 'After', 'xstore-core' ),
				],
				'condition' => [
					'button_selected_icon[value]!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_selected_icon[value]!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'icon_style',
			[
				'label' => __( 'Icon / Image / Text', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_type!' => 'lottie'
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-box-icon',
				'condition' => [
					'icon_type' => ['icon', 'text']
				]
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-icon' => 'color: {{VALUE}};',
				],
                'condition' => [
                    'icon_type' => ['icon', 'text']
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .etheme-icon-box-icon',
			]
		);
		
		$this->add_control(
			'icon_background_mask',
			[
				'label' => __( 'Background Mask', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-icon' => 'background-clip: text; text-fill-color: transparent; -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
				],
                'condition' => [
                    'icon_background_background' => 'gradient'
                ]
			]
		);
		
		$this->add_responsive_control(
			'image_max_width',
			[
				'label' => __( 'Image Max Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_type' => ['image']
				]
			]
		);
		
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' 				=> esc_html__('Padding', 'xstore-core'),
				'type' 					=>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' 			=> ['px', 'em', '%'],
				'selectors'				=> [
					'{{WRAPPER}} .etheme-icon-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
                    'unit' => '%'
                ],
				'tablet_default' => [
					'unit' => '%'
				],
				'mobile_default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_offset',
			[
				'label' => __( 'Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 100,
					],
					'em' => [
						'min' => -10,
						'max' => 5,
                        'step' => .1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--icon-offset: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position_outside!' => ''
				]
			]
		);
		
		$this->add_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--icon-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-box-title',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .etheme-icon-box-title span',
			]
		);
		
		$this->add_responsive_control(
			'title_padding',
			[
				'label' 				=> esc_html__('Padding', 'xstore-core'),
				'type' 					=>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' 			=> ['px', 'em', '%'],
				'selectors'				=> [
					'{{WRAPPER}} .etheme-icon-box-title span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'title_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-title span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'title_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Subtitle
		$this->start_controls_section(
			'subtitle_style',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'subtitle!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-box-subtitle',
			]
		);
		
		$this->add_control(
			'subtitle_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-subtitle' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'subtitle_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .etheme-icon-box-subtitle span',
			]
		);
		
		$this->add_responsive_control(
			'subtitle_padding',
			[
				'label' 				=> esc_html__('Padding', 'xstore-core'),
				'type' 					=>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' 			=> ['px', 'em', '%'],
				'selectors'				=> [
					'{{WRAPPER}} .etheme-icon-box-subtitle span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'subtitle_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-subtitle span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'subtitle_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// description style
		$this->start_controls_section(
			'description_style',
			[
				'label' => __( 'Description', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'description!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-box-description',
			]
		);
		
		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-description' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'description_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Button
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_text!' => ''       
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );
		
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
				],
				'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus',
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		
		// wrapper
		$this->add_render_attribute( 'wrapper', [
			'class' =>
                [
			        'etheme-icon-box',
                    'etheme-icon-box-icon-position-'.$settings['icon_position'],
                ]
		]);
		
		if ( !!$settings['icon_position_outside'] ) {
			$this->add_render_attribute( 'wrapper', [
				'class' => 'etheme-icon-box-icon-outside'
			]);
        }
		
		// content
		$this->add_render_attribute( 'content', [
			'class' => 'etheme-icon-box-content',
		]);
		
		// icon
		$this->add_render_attribute( 'box-icon', [
			'class' => 'etheme-icon-box-icon',
		]);
		
		// lottie animation
		$this->add_render_attribute( 'lottie-animation', [
			'class' => 'etheme-lottie-animation',
		]);
		
		// title
		$this->add_render_attribute( 'title', [
			'class' => 'etheme-icon-box-title',
		]);
		
		// subtitle
		$this->add_render_attribute( 'subtitle', [
			'class' => 'etheme-icon-box-subtitle',
		]);
		
		// description
		$this->add_render_attribute( 'description', 'class', 'etheme-icon-box-description' );
		
		// button text
		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
			$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
		}
		
		$this->add_render_attribute( 'button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );
		
		$this->add_render_attribute( 'button_text', 'class', 'button-text' );
		
		$this->add_render_attribute( 'button-icon-align', 'class', 'elementor-button-icon' );
		
		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		
		$migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
		
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && $migration_allowed;
		
		$this->add_render_attribute( 'box-icon', [
			'class' => 'etheme-icon-box-icon-type-'.$settings['icon_type'],
		]);
		
		$has_icon = ! empty( $settings['icon'] );
		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}
		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		
		?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
        
                <?php if ( $settings['icon_type'] == 'lottie') {
                    $this->add_render_attribute( 'box-icon', [
                        'class' => 'etheme-lottie',
                    ]);
                } ?>
            
                <div <?php $this->print_render_attribute_string( 'box-icon' ); ?>>
        
                <?php
                
                    switch ($settings['icon_type']) {
                        case 'icon':
                            if ( $has_icon ) :
                                if ( $is_new || $migrated ) {
                                    \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                                } elseif ( ! empty( $settings['icon'] ) ) {
	                                ?><i <?php $this->print_render_attribute_string( 'i' ); ?>></i><?php
                                }
                            endif;
                        break;
                        case 'image':
                            echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'image' );
                        break;
                        case 'lottie':
                            wp_enqueue_script('etheme_lottie');
                            wp_enqueue_style('etheme-elementor-lottie');
                            ?>
                            <div <?php $this->print_render_attribute_string( 'lottie-animation' ); ?>></div>
                            <?php
                            break;
                        case 'text': ?>
                            <span> <?php echo $settings['icon_text']; ?> </span>
                        <?php
                            break;
                        
                    }
                    ?>
        
                </div>
            
                <div <?php $this->print_render_attribute_string( 'content' ); ?>>
		
                    <?php if ( ! \Elementor\Utils::is_empty( $settings['subtitle'] ) ) : ?>
                        <<?php \Elementor\Utils::print_validated_html_tag( $settings['subtitle_tag'] ); ?> <?php $this->print_render_attribute_string( 'subtitle' ); ?>>
                        <span><?php $this->print_unescaped_setting( 'subtitle' ); ?></span>
                        </<?php \Elementor\Utils::print_validated_html_tag( $settings['subtitle_tag'] ); ?>>
                    <?php endif; ?>
                    
                    <?php if ( ! \Elementor\Utils::is_empty( $settings['title'] ) ) : ?>
                        <<?php \Elementor\Utils::print_validated_html_tag( $settings['title_tag'] ); ?> <?php $this->print_render_attribute_string( 'title' ); ?>>
                            <span><?php $this->print_unescaped_setting( 'title' ); ?></span>
                        </<?php \Elementor\Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
                    <?php endif; ?>
		
                    <?php if ( ! \Elementor\Utils::is_empty( $settings['description'] ) ) : ?>
                        <p <?php $this->print_render_attribute_string( 'description' ); ?>>
                            <?php $this->print_unescaped_setting( 'description' ); ?>
                        </p>
                    <?php endif; ?>

                <?php if ( ! \Elementor\Utils::is_empty($settings['button_text'])) :
                    $this->render_button($settings, array(
                        'is_new' => empty( $settings['button_icon'] ) && $migration_allowed,
                        'migrated' => isset( $settings['__fa4_migrated']['button_selected_icon'] )
                    ));
                endif; ?>
            
                </div>
			</div>
		<?php
		
	}
	
	/**
	 * Render button html content.
	 *
	 * @param       $settings
	 * @param array $extra
	 * @return void
	 *
	 * @since 4.0.12
	 *
	 */
	public function render_button($settings, $extra = array()) {
	    ?>
            <a <?php $this->print_render_attribute_string( 'button' ); ?>>
                
                <?php if ( $settings['button_icon_align'] == 'left') :
                    $this->render_button_icon($settings, $extra);
                endif; ?>
    
                <span <?php $this->print_render_attribute_string( 'button_text' ); ?>><?php $this->print_unescaped_setting( 'button_text' ); ?></span>
                
                <?php if ( $settings['button_icon_align'] == 'right') :
                    $this->render_button_icon($settings, $extra);
                endif; ?>
    
            </a>
        <?php
	}
	
	/**
	 * Render button icon html.
	 *
	 * @param $settings
	 * @param $extra
	 * @return void
	 *
	 * @since 4.0.12
	 *
	 */
	public function render_button_icon($settings, $extra) {
		$has_icon = ! empty( $settings['button_icon'] );
		if ( $has_icon ) {
			$this->add_render_attribute( 'button_i', 'class', $settings['button_icon'] );
			$this->add_render_attribute( 'button_i', 'aria-hidden', 'true' );
		}
		if ( ! $has_icon && ! empty( $settings['button_selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		
		if ( $has_icon ) : ?>
            <span <?php $this->print_render_attribute_string( 'button-icon-align' ); ?>>
                <?php
                if ( $extra['is_new'] || $extra['migrated'] ) {
                    \Elementor\Icons_Manager::render_icon( $settings['button_selected_icon'], [ 'aria-hidden' => 'true' ] );
                } elseif ( ! empty( $settings['button_icon'] ) ) {
                    ?><i <?php $this->print_render_attribute_string( 'button_i' ); ?>></i><?php
                } ?>
            </span>
		<?php endif;
	}
	
}
