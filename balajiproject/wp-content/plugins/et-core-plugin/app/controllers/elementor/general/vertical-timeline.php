<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Timeline widget.
 *
 * @since      4.0.8
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Vertical_Timeline extends \Elementor\Widget_Base {
	
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
		return 'etheme_timeline';
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
		return __( 'Vertical Timeline', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-timeline';
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
		return [ 'timeline', 'progress', 'step', 'date' ];
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
		return [ 'etheme_vertical_timeline' ];
	}
	
	/**
	 * Get widget style dependency.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return array|string[]
	 */
	public function get_style_depends() {
	    $scripts = ['etheme-elementor-vertical-timeline'];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$scripts += array(
                'elementor-icons-fa-solid',
	            'elementor-icons-fa-regular',
                'elementor-icons-fa-brands',
            );
		}
		return $scripts;
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
	 * @since 4.0.8
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
			'layout',
			[
				'label' 		=>	__( 'Layout', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Chess', 'xstore-core' ),
						'icon' => 'eicon-h-align-stretch',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default'		=> 'center',
			]
		);
		
		$this->add_control(
			'item_arrow',
			[
				'label' => __( 'Content Arrow', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'date_position',
			[
				'label' 		=>	__( 'Date / Time Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'inside'		=>	esc_html__('Inside', 'xstore-core'),
					'outside'	=>	esc_html__('Outside', 'xstore-core'),
				],
				'default'		=> 'outside',
                'condition' => [
                    'layout!' => 'center'
                ]
			]
		);
		
		$this->add_control(
			'icon_position',
			[
				'label' => __( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'label_block' => false,
				'separator' => 'before',
				'options' => [
					'step' => [
						'title' => esc_html__('On Connector Line', 'xstore-core'),
						'icon' => 'eicon-ellipsis-v',
					],
					'content' => [
						'title' => __( 'In Content', 'xstore-core' ),
						'icon' => 'eicon-post-content',
					],
					'none' => [
						'title' => __( 'Disable', 'xstore-core' ),
						'icon' => 'eicon-ban',
					],
				],
				'default' => 'none'
			]
		);
		
		$this->add_control(
			'icon_position_content',
			[
				'label' => __( 'Icon Content Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
                        'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'background' => [
						'title' => __( 'Background', 'xstore-core' ),
						'icon' => 'eicon-background',
					],
				],
				'default' => 'top',
				'condition' => [
                    'icon_position' => 'content'
                ]
			]
		);
		
		$reset_style_icon_bg = 'left: auto; top: auto; right: auto; bottom: auto; transform: none;';
		
		$this->add_control(
			'icon_position_content_bg',
			[
				'label' => __( 'Background Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'toggle' => false,
				'label_block' => false,
				'options' => [
					'center center' => __( 'Center Center', 'xstore-core' ),
					'center left' => __( 'Center Left', 'xstore-core' ),
					'center right' => __( 'Center Right', 'xstore-core' ),
					'top center' => __( 'Top Center', 'xstore-core' ),
					'top left' => __( 'Top Left', 'xstore-core' ),
					'top right' => __( 'Top Right', 'xstore-core' ),
					'bottom center' => __( 'Bottom Center', 'xstore-core' ),
					'bottom left' => __( 'Bottom Left', 'xstore-core' ),
					'bottom right' => __( 'Bottom Right', 'xstore-core' ),
				],
				'selectors_dictionary'  => [
					'center center'          => 'top: 50%; left: 50%; transform: translate(-50%, -50%);',
					'center left'       => 'top: 50%; left: 0; transform: translateY(-50%);',
					'center right'       => 'top: 50%; right: 0; transform: translateY(-50%);',
					
					'top center' => 'top: 0; left: 50%; transform: translateX(-50%);',
					'top left' => 'top: 0; left: 0;',
					'top right' => 'top: 0; right: 0;',
                    
                    'bottom center' => 'bottom: 0; left: 50%; transform: translateX(-50%);',
					'bottom left' => 'bottom: 0; left: 0;',
					'bottom right' => 'bottom: 0; right: 0;',
     
				],
				'default' => 'bottom right',
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-content-icon-bg' => $reset_style_icon_bg.'{{VALUE}};',
				],
				'condition' => [
					'icon_position' => 'content',
                    'icon_position_content' => 'background'
				],
			]
		);
		
		$this->add_control(
			'item_animation',
			[
				'label' => __( 'Animation On Scroll', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'auto' => __( 'Auto', 'xstore-core' ),
					'fade' => __( 'Fade in', 'xstore-core' ),
					'bottom' => __( 'Bottom', 'xstore-core' ),
					'none' => __( 'None', 'xstore-core' ),
				],
				'default' => 'auto',
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->start_controls_tabs( 'item_tabs' );
		
		$repeater->start_controls_tab(
			'item_tab_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'date', [
				'label' 		=>	__( 'Date', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'label_block'	=> 'true',
			]
		);
		
		$repeater->add_control(
			'time', [
				'label' 		=>	__( 'Time', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'label_block'	=> 'true',
			]
		);
		
		$repeater->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'separator' => 'before',
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
			]
		);
		
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'default' => 'thumbnail',
			]
		);
		
		$repeater->add_control(
			'title', [
				'label' 		=>	__( 'Title', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __('Item title', 'xstore-core'),
				'separator' => 'before',
				'label_block'	=> 'true',
			]
		);
		
		$repeater->add_control(
			'description', [
				'label' 		=>	__( 'Description', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'default' => __('Button', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'default' => [
					'url' => '#',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->start_controls_tab(
			'item_tab_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'item_style_info',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Use this style tab ONLY in case you need specific styles for this item. General styles are under Style tab at the top.', 'xstore-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_custom_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .etheme-timeline-content',
				'fields_options' => [
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}, {{SELECTOR}}:after' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
					],
					'image' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background-image: url("{{URL}}");',
							'{{SELECTOR}}:after' => 'background-image: none;',
						],
					],
				],
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Items', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'custom_content_tabs',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'date' => '19 July 2022',
						'title' => 'Amet vivamus dictumst',
						'description' => 'Consequat sed scelerisque tellus, enim consectetur sed sit. Ultrices molestie neque, ultricies tincidunt sagittis, quam montes.',
                        'selected_icon' => [
                            'value' => 'fas fa-crosshairs',
                            'library' => 'fa-solid',
                        ],
                        'button_text' => ''
                    ],
					[
						'date' => '21 July 2022',
						'title' => 'Ultrices elit vulputate',
						'description' => 'Nibh faucibus eget nunc dui. Amet tellus tincidunt dolor tincidunt integer amet faucibus. Morbi nisl, eget hac quam tristique semper nunc.',
						'selected_icon' => [
							'value' => 'far fa-lightbulb',
							'library' => 'fa-regular',
						],
                        'button_text' => ''
					],
					[
						'date' => '23 July 2022',
						'title' => 'Volutpat ridiculus ut',
						'description' => 'Cras dui semper cum lobortis quam malesuada. Accumsan rhoncus hendrerit urna, duis. Suspendisse nunc volutpat nibh.',
                        'button_text' => ''
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'cols_gap',
			[
				'label' => __( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 160,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'rows_gap',
			[
				'label' => __( 'Rows Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 160,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_item',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'item_align',
			[
				'label'                 => __( 'Alignment', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::CHOOSE,
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'xstore-core' ),
						'icon'    => 'eicon-text-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'xstore-core' ),
						'icon'    => 'eicon-text-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'xstore-core' ),
						'icon'    => 'eicon-text-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}}'   => '--item-text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'item_cols_gap',
			[
				'label' => __( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--item-cols-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => 'content',
					'icon_position_content!' => ['top', 'background']
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-timeline-content',
				'fields_options' => [
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}, {{SELECTOR}}:after' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
					],
					'image' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background-image: url("{{URL}}");',
							'{{SELECTOR}}:after' => 'background-image: none;',
						],
                    ],
                ],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-timeline-content',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
						'selectors' => [
							'{{SELECTOR}}' => 'border-style: {{VALUE}}; --border-style: {{VALUE}};',
						],
					],
					'width' => [
						'default' => [
							'top' => 1,
							'left' => 1,
							'right' => 1,
							'bottom' => 1
						],
						'selectors' => [
							'{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --border-width: {{RIGHT}}{{UNIT}};',
						],
					],
					'color' => [
						'default' => '#e1e1e1',
						'selectors' => [
							'{{SELECTOR}}' => 'border-color: {{VALUE}}; --border-color: {{VALUE}};',
						],
					]
				],
			]
		);
		
		$this->add_control(
			'item_border_radius',
			[
				'label'		 =>	esc_html__( 'Border Radius', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
                    'size' => 5,
                    'unit' => 'px'
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-content' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_shadow',
				'selector' => '{{WRAPPER}} .etheme-timeline-content',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'date_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Date', 'xstore-core' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .etheme-timeline-date',
			]
		);
		
		$this->add_control(
			'date_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-date' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'date_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-timeline-date' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'time_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Time', 'xstore-core' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'time_typography',
				'selector' => '{{WRAPPER}} .etheme-timeline-time',
			]
		);
		
		$this->add_control(
			'time_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-time' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'time_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-timeline-time' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'image_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Image', 'xstore-core' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_align',
			[
				'label'                 => __( 'Alignment', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::CHOOSE,
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'xstore-core' ),
						'icon'    => 'eicon-text-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'xstore-core' ),
						'icon'    => 'eicon-text-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'xstore-core' ),
						'icon'    => 'eicon-text-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .etheme-timeline-image'   => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'image_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-timeline-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		// icon
		$this->add_control(
			'icon_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Icon', 'xstore-core' ),
				'separator' => 'before',
                'condition' => [
                    'icon_position' => 'content'
                ]
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-content-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'icon_position' => 'content'
				]
			]
		);
		
		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
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
						'max' => 20,
						'step' => .1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-content-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => 'content'
				]
			]
		);
		
		$this->add_control(
			'icon_opacity',
			[
				'label' => __( 'Opacity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => .1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-content-icon-bg' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'icon_position' => 'content',
					'icon_position_content' => 'background',
				]
			]
		);
		
		$this->add_control(
			'icon_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-timeline-content-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'icon_position' => 'content',
                    'icon_position_content' => 'top',
                ]
			]
		);
		
		// title
		$this->add_control(
			'title_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Title', 'xstore-core' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-timeline-title',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-title' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'title_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-timeline-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		// description
		$this->add_control(
			'description_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Description', 'xstore-core' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .etheme-timeline-description',
			]
		);
		
		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-description' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'description_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-timeline-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		// button
		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->start_controls_tabs( 'button_style_tabs' );
		
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_color',
			[
				'label' => __( 'Button Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-button' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_bg',
				'label' => __( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-timeline-button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_color_hover',
			[
				'label' => __( 'Button Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_hover',
				'label' => __( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-timeline-button:hover',
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etheme-timeline-button',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-timeline-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
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
					'{{WRAPPER}} .etheme-timeline-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_connector',
			[
				'label' => __( 'Time Line', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'line_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Line', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'connector_width',
			[
				'label' => __( 'Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 2, // it is border and we need to divide properly
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--line-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'connector_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'dotted' => __( 'Dotted', 'xstore-core' ),
					'dashed' => __( 'Dashed', 'xstore-core' ),
				],
				'default' => 'dashed',
				'selectors' => [
					'{{WRAPPER}}' => '--line-style: {{VALUE}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'connector_colors_tabs' );
		
		$this->start_controls_tab(
			'connector_color_normal',
			[
				'label' => __( 'Default', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'connector_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--line-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'connector_color_active',
			[
				'label' => __( 'Active', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'connector_active_color',
			[
				'label' => __( 'Active Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--line-active-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'step_style_heading',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Step', 'xstore-core' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'step_proportion',
			[
				'label' => __( 'Proportion (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--step-proportion: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'step_icon_size',
			[
				'label' => __( 'Icon Size', 'xstore-core' ),
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
						'max' => 5,
						'step' => .1,
					],
				],
				'condition' => [
					'icon_position' => 'step'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--step-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'step_position',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
                'default' => 'top'
			]
		);
		
		$this->add_control(
			'step_br_size',
			[
				'label' => __( 'Border Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 1,
						'step' => .1,
					],
				],
				'default' => [
					'unit' => 'em'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--step-border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'connector_step_colors_tabs');
		
		$this->start_controls_tab(
			'connector_step_color_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'step_color',
			[
				'label' => __( 'Icon Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--step-color: {{VALUE}}',
				],
				'condition' => [
					'icon_position' => 'step'
				]
			]
		);
		
		$this->add_control(
			'step_bg_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--step-bg-color: {{VALUE}}',
				],
				'condition' => [
					'icon_position' => 'step'
				]
			]
		);
		
		$this->add_control(
			'step_br_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--step-br-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'connector_step_color_active',
			[
				'label' => __( 'Active', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'step_active_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--step-active-color: {{VALUE}}',
				],
				'condition' => [
					'icon_position' => 'step'
				]
			]
		);
		
		$this->add_control(
			'step_bg_active_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--step-bg-active-color: {{VALUE}}',
				],
				'condition' => [
					'icon_position' => 'step'
				]
			]
		);
		
		$this->add_control(
			'step_br_active_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--step-br-active-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.8
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', [
            'class' => 'etheme-timeline-wrapper',
            'data-layout' => $settings['layout'],
			'data-step-position' => $settings['step_position']
        ] );
		
		if ( $settings['item_animation'] != 'none' ) {
			$this->add_render_attribute( 'wrapper', 'data-item-animation', $settings['item_animation'] );
		}
		$this->add_render_attribute( 'connector', 'class', 'etheme-timeline-connector' );
		$this->add_render_attribute( 'connector-inner', 'class', 'etheme-timeline-connector-inner' );
		$this->add_render_attribute( 'items-wrapper', 'class', 'etheme-timeline-items' );

		$this->add_render_attribute( 'dates-items-wrapper', 'class', 'etheme-timeline-dates-items' );
		$this->add_render_attribute( 'dates-wrapper', 'class', 'etheme-timeline-dates-wrapper' );
		$this->add_render_attribute( 'date-time-wrapper', 'class', 'etheme-timeline-date-wrapper' );
		$this->add_render_attribute( 'date', 'class', 'etheme-timeline-date' );
		$this->add_render_attribute( 'time', 'class', 'etheme-timeline-time' );
		
		$this->add_render_attribute( 'image-wrapper', 'class', 'etheme-timeline-image' );
		$this->add_render_attribute( 'title', 'class', 'etheme-timeline-title' );
		$this->add_render_attribute( 'description', 'class', 'etheme-timeline-description' );
		
		$migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
		
		$local_date = array();
		
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            
            <div <?php $this->print_render_attribute_string( 'connector' ); ?>>
                <div <?php $this->print_render_attribute_string( 'connector-inner' ); ?>></div>
            </div>

            <div <?php $this->print_render_attribute_string( 'items-wrapper' ); ?>>
                <?php
                    
                    foreach ( $settings['custom_content_tabs'] as $tab ) {
                        
                        $local_date[] = array(
                            'date' => $tab['date'],
                            'time' => $tab['time'],
                        );
	
                    $this->add_render_attribute( 'item-wrapper'.$tab['_id'], 'class', [
                        'etheme-timeline-item',
                        'elementor-repeater-item-' . $tab['_id'],
                    ] );
	
                    $this->add_render_attribute( 'item-content'.$tab['_id'], 'class', 'etheme-timeline-content' );
	                   
                    if ( $settings['item_arrow'] ) {
                        $this->add_render_attribute( 'item-content'.$tab['_id'], 'class', 'etheme-timeline-content-with-arrow' );
                    }
	
                    // button
                    $this->add_render_attribute( 'button_text'.$tab['_id'], 'class', [
                        'elementor-button',
                        'etheme-timeline-button',
                    ] );
        
                    if ( ! empty( $tab['link']['url'] ) ) {
                        $this->add_link_attributes( 'button_text'.$tab['_id'], $tab['link'] );
                    } ?>
                    
                    <div <?php $this->print_render_attribute_string( 'item-wrapper'.$tab['_id'] ); ?>>
                        
                        <?php
                            if ( $settings['icon_position'] != 'step' )
                                echo '<span class="etheme-timeline-step etheme-timeline-dot"></span>';
                            else
                                $this->render_icon($settings, $tab, $migration_allowed, true);
                        ?>
	
	                    <?php
	                    if ( $settings['layout'] == 'center' ) {
	                        $this->render_data_time($tab, true);
                            ?>
	                    <?php } ?>
    
                        <div <?php $this->print_render_attribute_string( 'item-content'.$tab['_id'] ); ?>>
                            
                            <?php
                                if ( $settings['icon_position'] == 'content' && $settings['icon_position_content'] == 'left' )
	                                $this->render_icon($settings, $tab, $migration_allowed);
                            ?>
                            
                            <div class="etheme-timeline-content-inner">
	
	                            <?php
                                    if ( $settings['icon_position'] == 'content' && $settings['icon_position_content'] != 'left' )
                                        $this->render_icon($settings, $tab, $migration_allowed);
	
	                            if ( $settings['date_position'] == 'inside' ) {
		                            $this->render_data_time( $tab );
	                            }
	                            
                                if ( $tab['image']['id'] ) { ?>
                                    <div <?php $this->print_render_attribute_string( 'image-wrapper' ); ?>>
                                        <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $tab, 'image' ); ?>
                                    </div>
                                <?php }
                                
                                if ( $tab['title'] ) { ?>
                                    <h3 <?php $this->print_render_attribute_string( 'title' ); ?>>
                                        <?php echo $tab['title']; ?>
                                    </h3>
                                <?php }
                
                                if ( ! \Elementor\Utils::is_empty( $tab['description'] ) ) : ?>
                                    <div <?php $this->print_render_attribute_string( 'description' ); ?>>
                                        <?php
                                            $this->print_text_editor( $tab['description'] );
                                        ?>
                                    </div>
                                <?php endif;
            
                                if ( ! empty( $tab['button_text'] ) ) : ?>
                                
                                    <a <?php echo $this->get_render_attribute_string( 'button_text'.$tab['_id'] ); ?>>
                                        <?php echo $tab['button_text']; ?>
                                    </a>
                                
                                <?php endif; ?>
                        
                            </div>
                        
                        </div>
                        
                    </div>
                    
                <?php } ?>
                
            </div>

            <?php if ( $settings['date_position'] == 'outside' ) { ?>
                
                <div <?php $this->print_render_attribute_string( 'dates-items-wrapper' ); ?>>
                    <?php $this->render_data_time(false, true, $local_date); ?>
                </div>
                
            <?php } ?>
            
        </div>
        
		<?php
    }
    
    private function render_data_time($tab, $has_wrapper = false, $loop_data = false) {
	    
            if ( $loop_data ) {
                foreach ( $loop_data as $data )
	                $this->render_date_time_inner( $data, $has_wrapper );
            }
            else {
                $this->render_date_time_inner( $tab, $has_wrapper );
            }
    }
    
    private function render_date_time_inner($item, $wrapper = false) {
	    if ( $wrapper ) { ?>
            <div <?php $this->print_render_attribute_string( 'dates-wrapper' ); ?>>
                <div <?php $this->print_render_attribute_string( 'date-time-wrapper' ); ?>>
        <?php }
            
            if ( !empty($item['date']) ) : ?>
                <span <?php $this->print_render_attribute_string( 'date' ); ?>>
                    <?php echo $item['date']; ?>
                </span>
            <?php endif;
        
            if ( !empty($item['time']) ) : ?>
                <span <?php $this->print_render_attribute_string( 'time' ); ?>>
                    <?php echo $item['time']; ?>
                </span>
            <?php endif;
    
        if ( $wrapper ) { ?>
                </div>
            </div>
        <?php }
    }
    
    private function render_icon($settings, $tab, $migration_allowed, $for_step = false) {
	    
	    if ( ! empty( $tab['icon'] ) || ! empty( $tab['selected_icon'] ) ) { ?>
      
		    <?php if ( ( empty( $tab['icon'] ) && $migration_allowed ) || isset( $tab['__fa4_migrated']['selected_icon'] ) ) {
       
                    if ( !empty($tab['selected_icon']['value'])) { ?>
                        
                        <span class="
                            <?php if ( !$for_step) : ?>
                                etheme-timeline-content-icon
                                <?php echo $settings['icon_position_content'] == 'background' ?
                                'etheme-timeline-content-icon-bg' : '';
                            else: ?>
                                etheme-timeline-step etheme-timeline-icon
                            <?php endif;?>">
                            <?php \Elementor\Icons_Manager::render_icon( $tab['selected_icon'] ); ?>
                        </span>
                        
                    <?php }
                    
                    elseif ( $for_step ) {
                        $this->render_step_dot();
                    }
			    
                elseif ( $for_step ) {
                    $this->render_step_dot();
                }
            }
            
            elseif ( $for_step ) {
                $this->render_step_dot();
            }
            
        }
	    
    }
    
    private function render_step_dot() { ?>
	    <span class="etheme-timeline-step etheme-timeline-dot"></span>
        <?php
    }
}
