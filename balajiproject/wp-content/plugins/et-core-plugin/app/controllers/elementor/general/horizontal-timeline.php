<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Horizontal Timeline widget.
 *
 * @since      4.0.8
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Horizontal_Timeline extends \Elementor\Widget_Base {
	
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
		return 'etheme_horizontal_timeline';
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
		return __( 'Horizontal Timeline', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-horizontal-timeline';
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
		return [ 'timeline', 'slider', 'progress', 'step', 'date' ];
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
		return [ 'etheme_horizontal_timeline' ];
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
	    $scripts = ['etheme-elementor-horizontal-timeline'];
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
		
		$items_per_view = range( 1, 10 );
		$items_per_view = array_combine( $items_per_view, $items_per_view );
		
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' => [
					'slider' => __('Slider', 'xstore-core'),
					'grid' => __('Grid', 'xstore-core'),
				],
				'default'		=> 'grid',
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label' 		=>	__( 'Layout', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'options' => [
					'top'    => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'chess' => [
						'title' => __( 'Chess', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default'		=> 'chess',
				'frontend_available' => true
			]
		);
		
		$this->add_responsive_control(
			'cols',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Columns', 'xstore-core' ),
				'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $items_per_view,
				'condition' => [
					'type' => 'grid'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cols: {{VALUE}};',
				],
                'render_type' => 'template'
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
				'label' 		=>	__( 'Date/Time Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'inside'		=>	esc_html__('Inside', 'xstore-core'),
					'outside'	=>	esc_html__('Outside', 'xstore-core'),
				],
				'default'		=> 'outside',
                'condition' => [
                    'layout!' => 'chess'
                ],
                'frontend_available' => true
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
						'title' => esc_html__('On connector line', 'xstore-core'),
						'icon' => 'eicon-ellipsis-h',
					],
					'content' => [
						'title' => __( 'In content', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-h-timeline-content-icon-bg' => $reset_style_icon_bg.'{{VALUE}};',
				],
				'condition' => [
					'icon_position' => 'content',
                    'icon_position_content' => 'background'
				],
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
				'raw' => esc_html__( 'Use this style tab ONLY in case you need specific styles for this item. General styles are in Style section on top.', 'xstore-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_custom_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .etheme-h-timeline-content',
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
					[
						'date' => '13 July 2022',
						'title' => 'Amet vivamus dictumst',
						'description' => 'Consequat sed scelerisque tellus, enim consectetur sed sit. Ultrices molestie neque, ultricies tincidunt sagittis, quam montes.',
						'selected_icon' => [
							'value' => 'fas fa-crosshairs',
							'library' => 'fa-solid',
						],
						'button_text' => ''
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);
		
		$this->end_controls_section();
		
		// slider settings
		Elementor::get_slider_general_settings($this, [
			'type' => 'slider'
		]);
		
		$this->update_control( 'slides_per_view', [
			'default' 	=>	3,
			'tablet_default' => 1,
			'mobile_default' => 1,
		] );
		
		$this->remove_control('slider_vertical_align');
		$this->remove_control('loop');
		$this->remove_control('autoheight');
		$this->remove_control('pause_on_hover');
		$this->remove_control('pause_on_interaction');
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'cols_gap',
			[
				'label' => __( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['type' => 'grid']
			]
		);
		
		$this->add_control(
			'rows_gap',
			[
				'label' => __( 'Rows Gap', 'xstore-core' ),
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
					'{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'space_to_scrollbar',
			[
				'label' => __( 'Space To Scrollbar', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'condition' => ['type' => 'grid'],
				'selectors' => [
					'{{WRAPPER}} .etheme-h-timeline-wrapper[data-type=grid]' => 'padding-bottom: {{SIZE}}{{UNIT}};',
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
		
		$this->add_responsive_control(
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
		
		$this->add_responsive_control(
			'item_cols_gap',
			[
				'label' => __( 'Inner Columns Gap', 'xstore-core' ),
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-content',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-content',
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
					'{{WRAPPER}} .etheme-h-timeline-content' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_shadow',
				'selector' => '{{WRAPPER}} .etheme-h-timeline-content',
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
					'{{WRAPPER}} .etheme-h-timeline-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-date',
			]
		);
		
		$this->add_control(
			'date_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-h-timeline-date' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .etheme-h-timeline-date' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-time',
			]
		);
		
		$this->add_control(
			'time_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-h-timeline-time' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .etheme-h-timeline-time' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .etheme-h-timeline-image'   => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .etheme-h-timeline-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .etheme-h-timeline-content-icon' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .etheme-h-timeline-content-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .etheme-h-timeline-content-icon-bg' => 'opacity: {{SIZE}};',
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
					'{{WRAPPER}} .etheme-h-timeline-content-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-title',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-h-timeline-title' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .etheme-h-timeline-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-description',
			]
		);
		
		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-h-timeline-description' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .etheme-h-timeline-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .etheme-h-timeline-button' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-button',
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
					'{{WRAPPER}} .etheme-h-timeline-button:hover' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .etheme-h-timeline-button:hover',
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etheme-h-timeline-button',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-h-timeline-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .etheme-h-timeline-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
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
					'{{WRAPPER}}' => '--line-h: {{SIZE}}{{UNIT}};',
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
				'selectors' => [
					'{{WRAPPER}}' => '--step-icon-size: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'icon_position' => 'step'
                ]
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
		
		$this->add_control(
			'step_position',
			[
				'label' => __( 'Step Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'label_block' => false,
				'options' => [
					'left' => [
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
				],
                'default' => 'left'
			]
		);
		
		$this->start_controls_tabs( 'connector_step_colors_tabs', [
			'condition' => [
				'icon_position' => 'step'
			]
        ] );
		
		$this->start_controls_tab(
			'connector_step_color_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'step_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
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
		
        // slider style settings
        Elementor::get_slider_style_settings($this, [
	        'type' => 'slider'
        ]);
        
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$slides_count = count($settings['custom_content_tabs']);
		
		$this->add_render_attribute( 'wrapper', [
			'class' => 'etheme-h-timeline-wrapper',
			'data-type' => $settings['type'],
			'data-layout' => $settings['layout'],
			'data-step-position' => $settings['step_position']
		] );
		
		if ( $settings['type'] == 'slider' ) {
			$this->add_render_attribute( 'wrapper', [
                'class' =>
                    [
                        'swiper-entry',
	                    $settings['arrows_position'],
	                    $settings['arrows_position_style']
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
                     ]
                ]
            );
            $this->add_render_attribute( 'items-wrapper', 'class', 'swiper-wrapper');
		}
		
		if ( $settings['item_arrow'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'etheme-h-timeline-has-arrows' );
		}
		
		// connector line attributes
		$this->add_render_attribute( 'connector-wrapper', 'class', 'etheme-h-timeline-connector-wrapper' );
		$this->add_render_attribute( 'connector', 'class', 'etheme-h-timeline-connector' );
		$this->add_render_attribute( 'connector-inner', 'class', 'etheme-h-timeline-connector-inner' );
		
		// items wrapper attributes
		$this->add_render_attribute( 'items-wrapper', 'class', 'etheme-h-timeline-items' );
		
		// image attributes
		$this->add_render_attribute( 'image-wrapper', 'class', 'etheme-h-timeline-image' );
		
		// title attributes
		$this->add_render_attribute( 'title', 'class', 'etheme-h-timeline-title' );
		
		// description attributes
		$this->add_render_attribute( 'description', 'class', 'etheme-h-timeline-description' );
		
		// dates wrapper attributes
		$this->add_render_attribute( 'dates-wrapper', 'class', [
			'etheme-h-timeline-item',
			'etheme-h-timeline-date-item',
		] );
		
		if ( $settings['date_position'] == 'inside' ) {
            $this->remove_render_attribute('dates-wrapper', 'class', 'etheme-h-timeline-item');
			$this->remove_render_attribute('dates-wrapper', 'class', 'etheme-h-timeline-date-item');
		}
		
		$this->add_render_attribute( 'dates-inner', 'class', [
			'etheme-h-timeline-date-inner',
		] );
		
		// date attributes
		$this->add_render_attribute( 'date', 'class', [
			'etheme-h-timeline-date',
		] );
		
		// time attributes
		$this->add_render_attribute( 'time', 'class', [
			'etheme-h-timeline-time',
		] );
		
		?>
        
        <?php // main wrapper for all content ?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            
            <?php if ( $settings['type'] == 'slider' ) : ?>
                <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
            <?php endif; ?>
                
                <?php
                switch ($settings['layout']) {
                    case 'bottom':
                        if ( $settings['date_position'] == 'outside' )
	                        $this->render_items_wrapper( array( 'content_type' => 'date' ) );
                        
                        $this->render_connector_line();
                        
                        $this->render_items_wrapper();
                        break;
                    case 'chess':
                        $this->render_items_wrapper(array('layout' => 'chess'));
                        
                        $this->render_connector_line();
                        
                        $this->render_items_wrapper(array('layout' => 'chess', 'step' => 2));
                        break;
                    case 'top':
                        $this->render_items_wrapper();
                        
                        $this->render_connector_line();
                        
                        if ( $settings['date_position'] == 'outside' )
	                        $this->render_items_wrapper( array( 'content_type' => 'date' ) );
                        
                        break;
                }
                
                if ( 1 < $slides_count ) {
                    
                    if ( $settings['type'] == 'slider' && in_array($settings['navigation'], array('both', 'dots')) ) {
	                    Elementor::get_slider_pagination($this, $settings, $edit_mode);
                    }
                    
                }
                ?>
		
            <?php if ( $settings['type'] == 'slider' ) : ?>
                </div>
            <?php endif;
            
            if ( 1 < $slides_count ) :
		
		        if ( $settings['type'] == 'slider' && in_array($settings['navigation'], array('both', 'arrows')) )
			        Elementor::get_slider_navigation($settings, $edit_mode);
	
	        endif; ?>
         
        </div>
        <?php
    }
	
	/**
	 * Renders all items content.
	 *
	 * @param array $options
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
    private function render_items_wrapper($options = array()) {
	    
	    $settings = $this->get_settings_for_display();
	    $counter = 0;
	    $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
	    $is_slider = $settings['type'] == 'slider';
	
	    $options = shortcode_atts(
		    array(
			    'layout' => 'default',
			    'step' => 1,
                'content_type' => 'item'
		    ), $options);
	    ?>
        
        <div <?php $this->print_render_attribute_string( 'items-wrapper' ); ?>>
        
		    <?php
		    foreach ( $settings['custom_content_tabs'] as $tab ) {
			    $counter++;
			
			    $this->add_render_attribute( 'item-wrapper'.$tab['_id'], 'class', [
				    'etheme-h-timeline-item',
				    'elementor-repeater-item-' . $tab['_id'],
			    ] );
			
			    $this->add_render_attribute( 'item-wrapper'.$tab['_id'], 'data-id', $tab['_id']);
			
			    // add global for data wrapper specific id and remove after output
			    if ( $settings['date_position'] == 'outside' || $settings['layout'] == 'chess' )
				    $this->add_render_attribute( 'dates-wrapper', 'class', 'elementor-repeater-item-' . $tab['_id'] );
			
			
			    $this->remove_render_attribute( 'item-content'.$tab['_id'], 'class', 'etheme-h-timeline-content' );
			    $this->add_render_attribute( 'item-content'.$tab['_id'], 'class', 'etheme-h-timeline-content' );
			
			    // button
			    $this->add_render_attribute( 'button_text'.$tab['_id'], 'class', [
				    'elementor-button',
				    'etheme-h-timeline-button',
			    ] );
			
			    if ( ! empty( $tab['link']['url'] ) ) {
				    $this->add_link_attributes( 'button_text'.$tab['_id'], $tab['link'] );
			    }
			
			    if ( $is_slider ) {
			        // add class swiper-slide for both and remove then according to layout
				    $this->add_render_attribute( 'item-wrapper' . $tab['_id'], [
                        'class' => 'swiper-slide',
                    ] );
				    $this->add_render_attribute( 'dates-wrapper', [
					    'class' => 'swiper-slide',
				    ] );
			    }
			
			    if ( $options['layout'] == 'chess' ) {
				    switch ( $counter % 2 ) {
					    // odd items
					    case 1:
					        if ( $options['step'] == 1 ) {
						        $this->render_item( $settings, $tab,
							        array(
								        'with_step'         => true,
								        'migration_allowed' => $migration_allowed
							        )
						        );
					        }
					        else {
						        // remove sliper-slide class for dates wrapper item
						        $this->remove_render_attribute( 'dates-wrapper', 'class', 'swiper-slide' );
						        $this->render_data_time( $tab,
							        array(
								        'migration_allowed' => $migration_allowed
							        )
						        );
					        }
						    break;
					    // even items
					    case 0:
						    if ( $options['step'] == 1 ) {
							    $this->render_data_time( $tab,
								    array(
									    'with_step'         => true,
									    'migration_allowed' => $migration_allowed
								    )
							    );
						    }
						    else {
							    // remove sliper-slide class for item wrapper
							    $this->remove_render_attribute( 'item-wrapper'.$tab['_id'], 'class', 'swiper-slide');
							    $this->render_item($settings, $tab,
								    array(
									    'migration_allowed' => $migration_allowed
								    )
							    );
						    }
						    break;
				    }
			    }
			    
			    else {
			        // remove sliper-slide class for dates wrapper item
				    $this->remove_render_attribute( 'dates-wrapper', 'class', 'swiper-slide' );
			        if ( $options['content_type'] == 'item' ) {
				        $this->render_item( $settings, $tab,
					        array(
						        'with_step'         => true,
						        'migration_allowed' => $migration_allowed
					        )
				        );
			        }
			        else {
				        $this->render_data_time( $tab,
					        array(
						        'migration_allowed' => $migration_allowed
					        )
				        );
			        }
			    }
			
			    $this->remove_render_attribute( 'item-wrapper'.$tab['_id'], 'class', 'swiper-slide');
			    $this->remove_render_attribute( 'item-wrapper'.$tab['_id'], 'data-id', $tab['_id']);
			    
			    // remove global for data wrapper specific id
			    $this->remove_render_attribute('dates-wrapper', 'class', 'elementor-repeater-item-' . $tab['_id']);
			    $this->remove_render_attribute('dates-wrapper', 'class', 'swiper-slide');
		    }
		    ?>
        </div>
        <?php
    }
	
	/**
	 * Render content of item.
	 *
	 * @param $settings
	 * @param $tab
	 * @param $options
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
    private function render_item($settings, $tab, $options) {
	
	    $options = shortcode_atts(
		    array(
			    'with_step' => false,
			    'migration_allowed' => false
		    ), $options);
	    
	    ?>
        <div <?php $this->print_render_attribute_string( 'item-wrapper'.$tab['_id'] ); ?>>
		
		    <?php
		    if ( $options['with_step'] ) :
                if ( $settings['icon_position'] != 'step' )
                    echo '<span class="etheme-h-timeline-step etheme-h-timeline-dot"></span>';
                else
                    $this->render_icon($settings, $tab, $options['migration_allowed'], true);
		    endif; ?>

            <div <?php $this->print_render_attribute_string( 'item-content'.$tab['_id'] ); ?>>
			
			    <?php
			    if ( $settings['icon_position'] == 'content' && $settings['icon_position_content'] == 'left' )
				    $this->render_icon($settings, $tab, $options['migration_allowed']);
			    ?>

                <div class="etheme-h-timeline-content-inner">
				
				    <?php
	
                    if ( $settings['icon_position'] == 'content' && $settings['icon_position_content'] != 'left' )
                        $this->render_icon($settings, $tab, $options['migration_allowed']);
	
                    if ( $settings['date_position'] == 'inside' )
                        $this->render_data_time($tab, true);
				
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
						    <?php $this->print_text_editor( $tab['description'] ); ?>
                        </div>
				    <?php endif;
				
				    if ( ! empty( $tab['button_text'] ) ) : ?>
                        <a <?php echo $this->get_render_attribute_string( 'button_text'.$tab['_id'] ); ?>>
						    <?php echo $tab['button_text']; ?>
                        </a>
				    <?php endif; ?>

                </div><?php // end .etheme-h-timeline-content-inner ?>

            </div><?php // end 'item-content'.$tab['_id'] ?>

        </div><?php // end '.item-wrapper'.$tab['_id'] ?>
        
        <?php
    }
	
	/**
	 * Render data/time html.
	 *
	 * @param       $tab
	 * @param array $options
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
    private function render_data_time($tab, $options = array()) {
	
	    $settings = $this->get_settings_for_display();
	
	    $options = shortcode_atts(
		    array(
			    'has_wrapper' => false,
//			    'loop_data' => false,
			    'with_step' => false,
                'migration_allowed' => false
		    ), $options);
	    
//            if ( $options['loop_data'] ) {
//                foreach ( $options['loop_data'] as $tab )
//	                $this->render_date_time_inner( $tab, $options);
//            }
//            else {
//                $this->render_date_time_inner( $tab, $options);
//            }
        
        ?>
        
	    <div <?php $this->print_render_attribute_string( 'dates-wrapper' ); ?>>

            <div <?php $this->print_render_attribute_string( 'dates-inner' ); ?>>
            
                <?php if ( !empty($tab['date']) ) : ?>
                    <span <?php $this->print_render_attribute_string( 'date' ); ?>>
                        <?php echo $tab['date']; ?>
                    </span>
                <?php endif;
            
                if ( !empty($tab['time']) ) : ?>
                    <span <?php $this->print_render_attribute_string( 'time' ); ?>>
                        <?php echo $tab['time']; ?>
                    </span>
                <?php endif; ?>
    
            </div>
        
            <?php
        
            if ( $options['with_step'] ) :
                if ( $settings['icon_position'] != 'step' )
                    echo '<span class="etheme-h-timeline-step etheme-h-timeline-dot"></span>';
                else
                    $this->render_icon($settings, $tab, $options['migration_allowed'], true);
            endif;
        
            ?>

        </div>
	
	    <?php
    }
    
    private function render_date_time_inner($item, $options) {
	
	    $settings = $this->get_settings_for_display(); ?>
	    
	    <div <?php $this->print_render_attribute_string( 'dates-wrapper' ); ?>>
            
            <div <?php $this->print_render_attribute_string( 'dates-inner' ); ?>>
	    
            <?php if ( !empty($item['date']) ) : ?>
                <span <?php $this->print_render_attribute_string( 'date' ); ?>>
                    <?php echo $item['date']; ?>
                </span>
            <?php endif;
        
            if ( !empty($item['time']) ) : ?>
                <span <?php $this->print_render_attribute_string( 'time' ); ?>>
                    <?php echo $item['time']; ?>
                </span>
            <?php endif; ?>

            </div>
            
            <?php
            
                if ( $options['with_step'] ) :
                    if ( $settings['icon_position'] != 'step' )
                        echo '<span class="etheme-h-timeline-step etheme-h-timeline-dot"></span>';
                    else
                        $this->render_icon($settings, $item, $options['migration_allowed'], true);
                endif;

            ?>
            
            </div>
        
        <?php
    }
	
	/**
	 * Render connector line html.
	 *
	 * @since 4.0.8
	 *
	 * @return void
	 */
    private function render_connector_line() {
	    ?>
        <div <?php $this->print_render_attribute_string( 'connector-wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'connector' ); ?>>
                <div <?php $this->print_render_attribute_string( 'connector-inner' ); ?>></div>
            </div>
        </div>
        <?php
    }
	
	/**
	 * Renders dot/icon html.
	 *
	 * @param       $settings
	 * @param       $tab
	 * @param       $migration_allowed
	 * @param false $for_step
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
    private function render_icon($settings, $tab, $migration_allowed, $for_step = false) {
	    
	    if ( ! empty( $tab['icon'] ) || ! empty( $tab['selected_icon'] ) ) { ?>
      
		    <?php if ( ( empty( $tab['icon'] ) && $migration_allowed ) || isset( $tab['__fa4_migrated']['selected_icon'] ) ) {
       
                    if ( !empty($tab['selected_icon']['value'])) {
                        
                        $classes = array();
	                    if ( !$for_step) {
		                    $classes[] = 'etheme-h-timeline-content-icon';
		                    if ( $settings['icon_position_content'] == 'background' ) {
		                        $classes[] = 'etheme-h-timeline-content-icon-bg';
		                    }
	                    }
	                    else {
		                    $classes[] = 'etheme-h-timeline-step';
		                    $classes[] = 'etheme-h-timeline-icon';
	                    }
                        ?>
                        <span class="<?php echo implode(' ', $classes); ?>">
                            <?php \Elementor\Icons_Manager::render_icon( $tab['selected_icon'] ); ?>
                        </span>
                        
                    <?php }
                    
                    elseif ( $for_step )
                        $this->render_step_dot();
			    
                elseif ( $for_step )
                    $this->render_step_dot();
            }
            
            elseif ( $for_step )
                $this->render_step_dot();
            
        }
	    
    }
	
	/**
	 * Render dot for step.
	 *
	 * @since 4.0.8
	 *
	 * @return void
	 */
    private function render_step_dot() { ?>
	    <span class="etheme-h-timeline-step etheme-h-timeline-dot"></span>
        <?php
    }
}
