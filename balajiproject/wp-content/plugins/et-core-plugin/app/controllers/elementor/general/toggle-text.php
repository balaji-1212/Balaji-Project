<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Toggle Text widget.
 *
 * @since      4.1
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Toggle_Text extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_toggle_text';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Toggle text', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-toggle-button';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'content', 'text', 'less', 'more', 'show', 'hide', 'toggle', 'switch'];
    }
	/**
	 * Get widget categories.
	 *
	 * @since 4.1
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
	 * @since 4.1
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	
	public function get_script_depends() {
		return [ 'etheme_toggle_text' ];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-toggle-text' ];
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
	 * Register widget controls.
	 *
	 * @since 4.1
	 * @access protected
	 */
	protected function register_controls() {


        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'xstore-core'),
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => __('Button Type', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'button' => esc_html__('Button', 'xstore-core'),
                    'text' => esc_html__('Text', 'xstore-core'),
                ],
                'default' => 'button',
            ]
        );

        $this->add_control(
            'enabled_default',
            [
                'label' => __( 'Opened state', 'xstore-core' ),
                'description' => __('Make long text shown by default', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true
            ]
        );
		
		$this->add_control(
			'animated',
			[
				'label' => __( 'Animated', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_short_content',
            [
                'label' => esc_html__( 'Short Content', 'xstore-core' ),
            ]
        );

        $this->add_control(
            "short_content",
            [
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'label' => __('Content', 'xstore-core'),
                'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s...'
            ]
        );

        $text_columns = range( 1, 10 );
        $text_columns = array_combine( $text_columns, $text_columns );
        $text_columns[''] = esc_html__( 'Default', 'xstore-core' );

        $this->add_responsive_control(
            "short_content_text_columns",
            [
                'label' => esc_html__( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => $text_columns,
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-short-content" => 'columns: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            "short_content_column_gap",
            [
                'label' => esc_html__( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    '%' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'vw' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-short-content" => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'short_content_overlay',
            [
                'label' => __( 'Add Overlay', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'short_content_overlay_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'white' => __('White', 'xstore-core'),
                    'dark' => __('Dark', 'xstore-core'),
                ),
                'condition' => [
                    'short_content_overlay' => 'yes',
                ],
                'default' => 'white'
            ]
        );
		
		$this->add_responsive_control(
			"short_content_overlay_height",
			[
				'label' => esc_html__( 'Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'vw' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 10,
						'step' => 0.1,
					],
					'vw' => [
						'max' => 10,
						'step' => 0.1,
					],
					'em' => [
						'max' => 10,
						'step' => 0.1,
					],
				],
				'condition' => [
					'short_content_overlay' => 'yes',
				],
				'selectors' => [
					"{{WRAPPER}}" => '--overlay-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'button_more_text',
            [
                'label' => __( 'Button More Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'default' => esc_html__('Show more', 'xstore-core'),
            ]
        );

        $this->add_control(
            'button_more_custom_selected_icon',
            [
                'label' => __( 'Button Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'button_more_custom_icon',
                'default' => [
                    'value' => 'fas fa-long-arrow-alt-down',
                    'library' => 'fa-solid',
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'button_more_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'button_more_custom_selected_icon[value]!' => ''
                ],
            ]
        );

        $this->add_control(
            'button_more_custom_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 7
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-tt-button .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_more_custom_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_long_content',
            [
                'label' => esc_html__( 'Long Content', 'xstore-core' ),
            ]
        );

        $this->add_control(
            "long_content",
            [
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'label' => __('Content', 'xstore-core'),
                'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, 
                when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                It has survived not only five centuries, but also the leap into electronic typesetting, 
                remaining essentially unchanged.'
            ]
        );

        $this->add_responsive_control(
            "long_content_text_columns",
            [
                'label' => esc_html__( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => $text_columns,
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-long-content" => 'columns: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            "long_content_column_gap",
            [
                'label' => esc_html__( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    '%' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'vw' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-long-content" => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_less_text',
            [
                'label' => __( 'Button Less Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'default' => esc_html__('Show less', 'xstore-core'),
            ]
        );

        $this->add_control(
            'button_less_custom_selected_icon',
            [
                'label' => __( 'Button Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'button_less_custom_icon',
                'default' => [
                    'value' => 'fas fa-long-arrow-alt-up',
                    'library' => 'fa-solid',
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'button_less_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'button_less_custom_selected_icon[value]!' => ''
                ],
            ]
        );

        $this->add_control(
            'button_less_custom_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 7
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button-less .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-tt-button-less .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_less_custom_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            "section_style_short_content",
            [
                'label' => esc_html__('Short Content', 'xstore-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            "short_content_align",
            [
                'label' => esc_html__('Alignment', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'xstore-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'xstore-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'xstore-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'xstore-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-short-content" => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            "short_content_text_color",
            [
                'label' => esc_html__('Text Color', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-short-content" => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => "short_content_typography",
                'selector' => "{{WRAPPER}} .etheme-toggle-short-content",
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => "short_content_text_shadow",
                'selector' => "{{WRAPPER}} .etheme-toggle-short-content",
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            "section_style_long_content",
            [
                'label' => esc_html__('Long Content', 'xstore-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            "long_content_align",
            [
                'label' => esc_html__('Alignment', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'xstore-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'xstore-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'xstore-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'xstore-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-long-content" => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            "long_content_text_color",
            [
                'label' => esc_html__('Text Color', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    "{{WRAPPER}} .etheme-toggle-long-content" => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => "long_content_typography",
                'selector' => "{{WRAPPER}} .etheme-toggle-long-content",
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => "long_content_text_shadow",
                'selector' => "{{WRAPPER}} .etheme-toggle-long-content",
            ]
        );

        $this->end_controls_section();

        // button
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            "button_align",
            [
                'label' => esc_html__('Alignment', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'xstore-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'xstore-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'xstore-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    "{{WRAPPER}} .etheme-tt-button-wrapper" => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_min_width',
            [
                'label' => __( 'Min Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .etheme-tt-button',
                'fields_options' => [
                    'typography' => [
                        'default' => 'custom'
                    ],
                    'text_transform' => [
                        'default' => 'uppercase',
                    ],
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'button_type' => 'button'
                ],
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic'
//					],
//					'color' => [
//						'default' => '#222222'
//					],
//				],
                'selector' => '{{WRAPPER}} .etheme-tt-button',
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
                    '{{WRAPPER}} .etheme-tt-button:hover, {{WRAPPER}} .etheme-tt-button:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-tt-button:hover svg, {{WRAPPER}} .etheme-tt-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'button_type' => 'button'
                ],
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic'
//					],
//					'color' => [
//						'default' => '#444444'
//					],
//				],
                'selector' => '{{WRAPPER}} .etheme-tt-button:hover, {{WRAPPER}} .etheme-tt-button:focus',
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                    'button_type' => 'button'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button:hover, {{WRAPPER}} .etheme-tt-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .etheme-tt-button',
                'separator' => 'before',
                'condition' => [
                    'button_type' => 'button'
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'condition' => [
                    'button_type' => 'button'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'condition' => [
                    'button_type' => 'button'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_spacing',
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
                    '{{WRAPPER}} .etheme-tt-button-wrapper' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        // button less
        $this->start_controls_section(
            'section_button_less_style',
            [
                'label' => esc_html__( 'Button Less', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_less_custom_styles',
            [
                'label' => __( 'Custom styles', 'xstore-core' ),
                'description' => __('Enable custom styles for less button', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'none'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_less_typography',
                'selector' => '{{WRAPPER}} .etheme-tt-button-less',
                'condition' => [
                    'button_less_custom_styles' => 'yes'
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_less_style', [
            'condition' => [
                'button_less_custom_styles' => 'yes'
            ],
        ] );

        $this->start_controls_tab(
            'tab_button_less_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_less_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button-less' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_less_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'button_type' => 'button'
                ],
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic'
//					],
//					'color' => [
//						'default' => '#000000'
//					],
//				],
                'selector' => '{{WRAPPER}} .etheme-tt-button-less',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_less_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_less_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button-less:hover, {{WRAPPER}} .etheme-tt-button-less:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-tt-button-less:hover svg, {{WRAPPER}} .etheme-tt-button-less:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_less_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'button_type' => 'button'
                ],
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic'
//					],
//					'color' => [
//						'default' => '#444444'
//					],
//				],
                'selector' => '{{WRAPPER}} .etheme-tt-button-less:hover, {{WRAPPER}} .etheme-tt-button-less:focus',
            ]
        );

        $this->add_control(
            'button_less_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_less_border_border!' => '',
                    'button_type' => 'button'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button-less:hover, {{WRAPPER}} .etheme-tt-button-less:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_less_border',
                'selector' => '{{WRAPPER}} .etheme-tt-button-less',
                'separator' => 'before',
                'condition' => [
                    'button_type' => 'button',
                    'button_less_custom_styles' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'button_less_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'condition' => [
                    'button_type' => 'button',
                    'button_less_custom_styles' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button-less' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_less_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'condition' => [
                    'button_type' => 'button',
                    'button_less_custom_styles' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-tt-button-less' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * @since 4.1
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'short-content-wrapper', 'class', 'etheme-toggle-short-content-wrapper' );
        $this->add_render_attribute( 'long-content-wrapper', 'class', 'etheme-toggle-long-content-wrapper' );

        $this->add_render_attribute( 'short-content', 'class', 'etheme-toggle-short-content' );
        $this->add_render_attribute( 'long-content', 'class', 'etheme-toggle-long-content' );

        if ( $settings['short_content_overlay'] ) {
            $this->add_render_attribute( 'short-content', 'class', ['etheme-toggle-content-overlay', 'etheme-toggle-content-overlay-' . $settings['short_content_overlay_color']] );
        }
        $this->add_render_attribute( (!!!$settings['enabled_default'] ? 'short' : 'long') .'-content-wrapper', 'class', 'active');

        $this->add_render_attribute( (!!!$settings['enabled_default'] ? 'long' : 'short') .'-content-wrapper', 'style', 'display: none;');

//		if ( ! empty( $settings['link']['url'] ) ) {
//			$this->add_link_attributes( 'button', $settings['link'] );
//			$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
//		}
		
//		$this->add_render_attribute( 'button', 'class', ['elementor-button', 'etheme-modal-popup-button'] );
//		$this->add_render_attribute( 'button', 'role', 'button' );
//		$this->add_render_attribute( 'button', 'data-popup-id', $this->get_id());
		
//		if ( ! empty( $settings['button_css_id'] ) ) {
//			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
//		}
		
//		if ( ! empty( $settings['size'] ) ) {
//			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
//		}
		
//		if ( $settings['hover_animation'] ) {
//			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
//		}

        $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();

        ?>
        <div <?php $this->print_render_attribute_string( 'short-content-wrapper' ); ?>>

            <div <?php $this->print_render_attribute_string( 'short-content' ); ?>>
                <?php $this->print_unescaped_setting("short_content"); ?>
            </div>
            
        </div>

        <div <?php $this->print_render_attribute_string( 'long-content-wrapper' ); ?>>

            <div <?php $this->print_render_attribute_string( 'long-content' ); ?>>
                <?php $this->print_unescaped_setting("long_content"); ?>
            </div>

        </div>

        <div class="etheme-tt-button-wrapper etheme-tt-button-more-wrapper"<?php if ( !!$settings['enabled_default'] ) : ?> style="display: none" <?php endif; ?>>
            <span class="<?php echo ('button' == $settings['button_type'] ? 'elementor-button ' : ''); ?>etheme-tt-button etheme-tt-button-more">
                <?php
                if ( $settings['button_more_icon_align'] == 'left')
                    $this->render_icon( $settings );

                echo '<span class="button-text">' . $settings['button_more_text'] . '</span>';

                if ( $settings['button_more_icon_align'] == 'right')
                    $this->render_icon( $settings );

                ?>
            </span>
        </div>

        <div class="etheme-tt-button-wrapper etheme-tt-button-less-wrapper"<?php if ( !!!$settings['enabled_default'] ) : ?> style="display: none" <?php endif; ?>>
            <span class="<?php echo ('button' == $settings['button_type'] ? 'elementor-button ' : ''); ?>etheme-tt-button etheme-tt-button-less">
                <?php
                if ( $settings['button_less_icon_align'] == 'left')
                    $this->render_icon( $settings, 'less' );

                echo '<span class="button-text">' . $settings['button_less_text'] . '</span>';

                if ( $settings['button_less_icon_align'] == 'right')
                    $this->render_icon( $settings, 'less' );

                ?>
            </span>
        </div>
        
		<?php
    }

    public function render_icon($settings, $side = 'more') {

        $migrated = isset( $settings['__fa4_migrated']['button_'.$side.'_custom_selected_icon'] );
        $is_new = empty( $settings['button_'.$side.'_custom_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['button_'.$side.'_custom_icon'] ) || ! empty( $settings['button_'.$side.'_custom_selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings['button_'.$side.'_custom_selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings['button_'.$side.'_custom_icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }
}
