<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Advanced Headline widget.
 *
 * @since      4.0.11
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Advanced_Headline extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_advanced_headline';
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
		return __( 'Advanced Headline', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-advanced-headline';
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
		return [ 'headline', 'heading', 'animation', 'title', 'text' ];
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
	public function get_style_depends() {
		return [ 'etheme-elementor-advanced-headline' ];
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
	 * @since 4.0.11
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
			'text_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'style_01' => __( 'Classic', 'xstore-core' ),
					'style_02' => __( 'Centered With Divider', 'xstore-core' ),
					'style_03' => __( 'Background Text', 'xstore-core' ),
					'style_04' => __( 'Left With Divider', 'xstore-core' ),
					'style_05' => __( 'Right With Divider', 'xstore-core' ),
					'style_06' => __( 'With Highlighted Text', 'xstore-core' ),
					'style_07' => __( 'With Underline', 'xstore-core' ),
				],
				'default' => 'style_01',
			]
		);
		
		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'condition' => [
					'text_style' => ['style_01', 'style_03', 'style_06', 'style_07']
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);
		
		$this->add_control(
			'text_before',
			[
				'label' => __( 'Subheading Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'rows' => 2,
				'separator' => 'before',
				'default' => esc_html__('Subheading Text', 'xstore-core'),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'text_before_html_tag',
			[
				'label' => esc_html__( 'Subheading HTML Tag', 'xstore-core' ),
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
				],
				'default' => 'span',
			]
		);
		
		$this->add_control(
			'text_before_with_bg',
			[
				'label' => __( 'Add Background', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'text_style!' => ['style_03']
				],
			]
		);
		
		$this->add_control(
			'text',
			[
				'label' => __( 'Heading', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'rows' => 2,
				'separator' => 'before',
				'default' => esc_html__('Headline', 'xstore-core'),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'text_html_tag',
			[
				'label' => esc_html__( 'Heading HTML Tag', 'xstore-core' ),
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
				],
				'default' => 'h2',
			]
		);
		
		$this->add_control(
			'text_separator_type',
			[
				'label' => __( 'Add Icon/Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'xstore-core' ),
					'icon' => __( 'Icon', 'xstore-core' ),
					'image' => __( 'Image', 'xstore-core' ),
				],
				'default' => 'none',
			]
		);
		
		$this->add_control(
			'separator_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'separator_fa_icon',
				'skin' => 'inline',
				'label_block' => false,
                'condition' => [
                    'text_separator_type' => 'icon'
                ]
			]
		);
		
		$this->add_control(
			'separator_image',
			[
				'label' => esc_html__( 'Choose File', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'text_separator_type' => 'image'
				]
			]
		);
		
		$this->add_control(
			'text_styled',
			[
				'label' => __( 'Text Styled', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => '2',
                'separator' => 'before',
				'default' => __('Style', 'xstore-core'),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'text_style' => 'style_06',
				],
			]
		);
		
		$this->add_control(
			'text_after',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'condition' => [
					'text_style!' => 'style_03',
				],
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'xstore-core' ),
			]
		);
		
		$this->end_controls_section();
		
        $this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'Heading', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
  
		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-advanced-headline' => 'color: {{VALUE}}',
				],
				'condition' => ['text_style!' => 'style_01'],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'heading_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-advanced-headline',
				'condition' => ['text_style' => 'style_01'],
				'fields_options' => [
					'background' => [
						'default' => 'gradient'
					],
					'image' => [
						'default' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
                    ],
					'color' => [
						'default' => '#2962FF',
					],
					'color_b' => [
						'default' => '#0013BD'
					]
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .etheme-advanced-headline',
			]
		);
		
		$this->add_responsive_control(
			'heading_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--heading-space: {{SIZE}}{{UNIT}};',
				],
			]
		);

		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_text_before_style',
			[
				'label' => __( 'Subheading', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_before!' => ''
				],
			]
		);

		$this->add_control(
			'text_before_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_before_typography',
				'selector' => '{{WRAPPER}} .etheme-a-h-text-before',
			]
		);
		
		$this->add_control(
			'text_before_bg_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => 'background-color: {{VALUE}}',
				],
				'condition' => ['text_before_with_bg!' => ''],
			]
		);
		
		$this->add_responsive_control(
			'text_before_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'condition' => [
					'text_style!' => 'style_03',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_before_background_style_heading',
			[
				'label' => __( 'Background Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'text_style' => 'style_03',
				],
			]
		);
//
		$this->add_responsive_control(
			'text_before_background_h_position',
			[
				'label' => __( 'Horizontal Position', 'xstore-core' ),
				'description' => __('Set background text position according to headline text', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
					'custom' => [
						'title' => __( 'Custom', 'xstore-core' ),
						'icon' => 'eicon-custom',
					],
				],
				'condition' => [
					'text_style' => 'style_03',
				],
				'selectors_dictionary'  => [
					'left'          => 'left: 0; right: auto; --translateX: 0;',
					'center'          => 'left: 50%; right: auto; --translateX: -50%;',
					'right'          => 'left: auto; right: 0; --translateX: 0;',
					'custom' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'text_before_background_h_offset',
			[
				'label' => __( 'Horizontal Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'em' ],
				'range' => [
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => -5,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'condition' => [
					'text_style' => 'style_03',
					'text_before_background_h_position' => 'custom'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => '--translateX: 0; left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_before_background_v_position',
			[
				'label' => __( 'Vertical Position', 'xstore-core' ),
				'description' => __('Set background text position according to headline text', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top'    => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'custom' => [
						'title' => __( 'Custom', 'xstore-core' ),
						'icon' => 'eicon-custom',
					],
				],
				'condition' => [
					'text_style' => 'style_03',
				],
				'selectors_dictionary'  => [
					'top'          => 'bottom: 100%; top: auto; --translateY: 0;',
					'middle'          => 'top: 50%; bottom: auto; --translateY: -50%;',
					'bottom'          => 'bottom: auto; top: 100%; --translateY: 0;',
					'custom' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'text_before_background_v_offset',
			[
				'label' => __( 'Vertical Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'em' ],
				'range' => [
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => -5,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'condition' => [
                    'text_style' => 'style_03',
					'text_before_background_v_position' => 'custom'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-before' => '--translateY: 0; bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_styled_style',
			[
				'label' => __( 'Text Styled', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style' => 'style_06',
					'text_styled!' => ''
				],
			]
		);
		
		$this->add_control(
			'text_styled_color',
			[
				'label' => __( 'Text Styled Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_styled_typography',
				'selector' => '{{WRAPPER}} .etheme-a-h-text',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'text_styled_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .etheme-a-h-text',
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
					'color' => [
						'default' => '#2962FF'
					],
					'color_b' => [
						'default' => '#0013BD'
					]
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_separator_type' => 'image'
				],
			]
		);
		
		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-image-wrapper img' => 'opacity: {{SIZE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .etheme-a-h-image-wrapper img',
			]
		);
		
		$this->add_responsive_control(
			'image_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-image-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_separator_type' => 'icon',
					'separator_icon[value]!' => ''
				],
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-icon-wrapper' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => .1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-icon-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_after_style',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style!' => 'style_03',
					'text_after!' => ''
				],
			]
		);

		$this->add_control(
			'text_after_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-a-h-text-after' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_after_typography',
				'selector' => '{{WRAPPER}} .etheme-a-h-text-after',
				'separator' => 'after',
				'condition' => [
					'text_after!' => ''
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_line_style',
			[
				'label' => __( 'Line', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style' => ['style_02', 'style_04', 'style_05', 'style_07'],
				],
			]
		);

		$this->add_control(
			'line_height',
			[
				'label' => __( 'Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'line_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--line-space: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'line_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'dotted' => __( 'Dotted', 'xstore-core' ),
					'dashed' => __( 'Dashed', 'xstore-core' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}}' => '--line-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--line-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'line_active_color',
			[
				'label' => __( 'Active Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--line-active-color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'style_07',
				],
			]
		);
		
		$this->add_control(
			'line_hide_mobile',
			[
				'label'         =>  esc_html__( 'Hide On Mobile', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'text_style' => ['style_02', 'style_04', 'style_05', 'style_07'],
				],
			]
		);

		$this->end_controls_section();
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.11
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper',
            [
		        'class' =>
                    [
                        'etheme-a-h-wrapper'
                    ]
            ]
        );
		
		$this->add_render_attribute( 'headline', 'class', 'etheme-advanced-headline' );
		
		// before text
		$this->add_render_attribute( 'text-before', 'class', 'etheme-a-h-text-before');
		
		// text styled
		$this->add_render_attribute( 'text-styled', 'class', 'etheme-a-h-text');
  
		// after text
		$this->add_render_attribute( 'text-after', 'class', 'etheme-a-h-text-after');
		
		$this->add_render_attribute( 'icon_wrapper', 'class', 'etheme-a-h-icon-wrapper');
		$this->add_render_attribute( 'image_wrapper', 'class', 'etheme-a-h-image-wrapper');
		
		switch ($settings['text_style']) {
            case 'style_01':
                $this->add_render_attribute('headline', 'class', 'etheme-advanced-headline-mask');
                break;
            case 'style_02':
	            $this->add_render_attribute( 'wrapper',
                    'class',
                    [
                        'elementor-align-center',
                        'etheme-a-h-line-both',
                        'etheme-a-h-line-before',
                        'etheme-a-h-line-after'
                    ]
                );
	
            break;
			case 'style_03':
				
				$this->add_render_attribute( 'wrapper',
					'class',
					[
//						'elementor-align-center',
						'etheme-a-h-with-bg',
						'etheme-a-h-lines-none',
					]
				);
				
				break;
            case 'style_04':
	            $this->add_render_attribute( 'wrapper',
		            'class',
		            [
			            'elementor-align-left',
			            'etheme-a-h-line-after'
		            ]
	            );
                break;
			case 'style_05':
				$this->add_render_attribute( 'wrapper',
					'class',
					[
						'elementor-align-right',
						'etheme-a-h-line-before',
					]
				);
				break;
            case 'style_07':
	            $this->add_render_attribute( 'wrapper', 'class', 'etheme-a-h-line-under' );
                break;
            default:
                break;
        }
        
        if ( $settings['line_hide_mobile'] )
	        $this->add_render_attribute( 'wrapper', 'class', 'etheme-a-h-line-mobile-hidden' );
	
	    if ( $settings['text_before_with_bg'] )
		    $this->add_render_attribute( 'text-before', 'class', 'etheme-a-h-text-before-with-bg' );
		
		$editor_content = $this->get_settings_for_display( 'text_after' );
		$editor_content = $this->parse_text_editor( $editor_content );
		
		$migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
		
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
        
            <?php
        
            if ( $settings['text_before'] && !in_array($settings['text_style'], array('style_03')) ) { ?>
                <<?php echo $settings['text_before_html_tag']; ?> <?php $this->print_render_attribute_string( 'text-before' ); ?>>
                    <?php echo $settings['text_before']; ?>
                </<?php echo $settings['text_before_html_tag']; ?>>
            <?php }
		
            if ( in_array($settings['text_style'], array('style_07'))) {
              echo '<div class="etheme-advanced-headline-heading-wrapper">';
            } ?>

        <<?php echo $settings['text_html_tag']; ?> <?php $this->print_render_attribute_string( 'headline' ); ?>>

            <?php

                if ( $settings['text_before'] && in_array($settings['text_style'], array('style_03')) ) { ?>
                    <<?php echo $settings['text_before_html_tag']; ?> <?php $this->print_render_attribute_string( 'text-before' ); ?>>
                    <?php echo $settings['text_before']; ?>
                    </<?php echo $settings['text_before_html_tag']; ?>>
                <?php }

                echo $settings['text'];

                // it is shown inline because of spaces that are between words
                if ( $settings['text_styled']) { ?><span <?php $this->print_render_attribute_string( 'text-styled' ); ?>><?php echo $settings['text_styled']; ?></span>
                <?php }
            ?>

        </<?php echo $settings['text_html_tag']; ?>>
		
            <?php
        
            if ( in_array($settings['text_style'], array('style_07'))) {
	            $this->render_divider();
	            echo '</div>';
            }
            
            switch ($settings['text_separator_type']) {
                case 'icon':
	                if ( ! empty( $settings['separator_fa_icon'] ) || ! empty( $settings['separator_icon'] ) ) : ?>
                        <div <?php echo $this->get_render_attribute_string( 'icon_wrapper' ); ?>>
			                <?php if ( ( empty( $settings['separator_fa_icon'] ) && $migration_allowed ) || isset( $settings['__fa4_migrated']['separator_icon'] ) ) :
				                \Elementor\Icons_Manager::render_icon( $settings['separator_icon'] );
			                else : ?>
                                <i <?php echo $this->get_render_attribute_string( 'separator_fa_icon' ); ?>></i>
			                <?php endif; ?>
                        </div>
	                <?php
	                endif;
                    break;
	            case 'image':
	                ?>
                    <div <?php echo $this->get_render_attribute_string( 'image_wrapper' ); ?>>
                        <?php
                            \Elementor\Group_Control_Image_Size::print_attachment_image_html( $settings, 'separator_image' );
                        ?>
                    </div>
                    <?php
		            break;
                default;
             }
            
             if ( $editor_content ) { ?>
                <p <?php $this->print_render_attribute_string( 'text-after' ); ?>>
                    <?php echo $editor_content; ?>
                </p>
            <?php } ?>
        
        </div>
        
		<?php
    }
    
    public function render_divider() {
	    echo '<div class="etheme-advanced-headline-divider"></div>';
    }
}
