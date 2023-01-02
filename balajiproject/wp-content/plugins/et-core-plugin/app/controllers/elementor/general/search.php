<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Search widget.
 *
 * @since      4.0.10
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Search extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_ajax_search';
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
		return __( 'Ajax Search', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-search';
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
		return [ 'search', 'form', 'typing', 'ajax', 'products', 'posts', 'query', 'results' ];
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
	public function get_script_depends() {
        return ['etheme_ajax_search'];
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
		return [ 'etheme-elementor-search' ];
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
	 * @since 4.0.10
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
			'type',
			[
				'label'   => esc_html__( 'Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'separated' => esc_html__('Separated', 'xstore-core'),
					'inline' => esc_html__('Inline', 'xstore-core')
				),
				'default' => 'inline',
			]
		);
		
		$this->add_control(
			'ajax_search',
			[
				'label'     => esc_html__( 'Ajax Search', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'categories',
			[
				'label'     => esc_html__( 'Show Categories', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			]
		);
		
		$search_types = $this->search_post_types();
		$default_post_types = [];
		if ( array_key_exists('product', $search_types) ) {
		    $default_post_types[] = 'product';
		}
		if ( array_key_exists('post', $search_types) ) {
		    $default_post_types[] = 'post';
		}
		
		$this->add_control(
			'post_types',
			[
				'label'   => esc_html__( 'Post Types', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $search_types,
				'default' => $default_post_types,
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search for products...', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search', 'xstore-core' ),
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
				'label' => __( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'xstore-core' ),
					'right' => __( 'After', 'xstore-core' ),
				],
				'condition' => [
					'button_selected_icon[value]!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_icon_indent',
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
					'{{WRAPPER}} .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'button_text!' => '',
					'button_selected_icon[value]!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_search_ajax_settings',
			[
				'label' => esc_html__( 'Ajax Search Results', 'xstore-core' ),
				'condition' => [
                    'ajax_search' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'ajax_search_results_heading_type',
			[
				'label'   => esc_html__( 'Heading Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'tabs' => esc_html__('Tabs', 'xstore-core'),
					'headings' => esc_html__('Headings', 'xstore-core'),
					'none' => esc_html__('None', 'xstore-core')
				),
				'default' => 'tabs',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'min_chars',
			[
				'label' => __( 'Search After x Symbols', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 20,
						'step' => 1
					],
				],
				'frontend_available' => true
			]
		);
		
		// make query for x count posts
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Limit Results', 'xstore-core' ),
				'description' => __( 'Limit results for each post types', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 300,
						'step' => 1
					],
				],
				'frontend_available' => true
			]
		);
		
		// output in search results
		$this->add_control(
			'post_limit',
			[
				'label' => __( 'Posts Count For View', 'xstore-core' ),
				'description' => __( 'Display View All Results button after this number of posts', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => -1,
						'max'  => 30,
						'step' => 1
					],
				],
				'default' => [
                    'size' => 5
                ],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'product_content_heading',
			[
				'label' => __( 'Product', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'product_stock',
			[
				'label'     => esc_html__( 'Show Stock Status', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'product_sku',
			[
				'label'     => esc_html__( 'Show Sku', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'product_price',
			[
				'label'     => esc_html__( 'Show Price', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'post_types' => 'product',
				],
			]
		);
		
		$this->add_control(
			'global_post_type_content_heading',
			[
				'label' => __( 'Global Post Types', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'post_types!' => '',
				],
			]
		);
		
		$this->add_control(
			'global_post_type_date',
			[
				'label'     => esc_html__( 'Show Date', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'post_types!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'border',
				'label'     => esc_html__( 'Border', 'xstore-core' ),
				'selector'  => '{{WRAPPER}}',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
						'selectors' => [
                            '{{SELECTOR}}' => '--s-border-style: {{VALUE}};',
                        ],
					],
					'width' => [
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'selectors' => [
                            '{{SELECTOR}}' => '--s-border-width: {{SIZE}}{{UNIT}};',
                        ],
                    ],
					'color' => [
						'default' => '#e1e1e1',
						'selectors' => [
                            '{{SELECTOR}}' => '--s-border-color: {{VALUE}};',
                        ],
					]
				],
			]
		);
		
		$this->add_responsive_control(
			'min_height',
			[
				'label' => __( 'Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 30,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--s-min-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}}' => '--s-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'space',
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
				'condition' => [
				    'type' => 'separated'
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--s-form-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_categories',
			[
				'label' => esc_html__( 'Categories', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'categories!' => ''
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'categories_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-select',
			]
		);
		
		$this->add_control(
			'categories_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-select' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Input', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'input_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-input',
			]
		);
		
		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-input, {{WRAPPER}} .etheme-search-form-clear' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-input::-webkit-input-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-input::-moz-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-input:-ms-input-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-input:-moz-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .etheme-search-form-submit',
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
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-submit' => 'fill: {{VALUE}}; color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .etheme-search-form-submit',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
                        'default' => '#000000',
					],
				],
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
					'{{WRAPPER}} .etheme-search-form-submit:hover, {{WRAPPER}} .etheme-search-form-submit:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-submit:hover svg, {{WRAPPER}} .etheme-search-form-submit:focus svg' => 'fill: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .etheme-search-form-submit:hover, {{WRAPPER}} .etheme-search-form-submit:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
                    'color' => [
                        'default' => '#3f3f3f'
                    ]
				],
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-submit:hover, {{WRAPPER}} .etheme-search-form-submit:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etheme-search-form-submit',
				'separator' => 'before',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
                        'default' => [
                            'top' => 0,
                            'left' => 0,
                            'right' => 0,
                            'bottom' => 0
                        ]
					],
				],
			]
		);
		
        $this->add_responsive_control(
			'button_min_width',
			[
				'label' => __( 'Min Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--s-button-min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_results',
			[
				'label' => esc_html__( 'Results Dropdown', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
                    'ajax_search' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'results_border',
				'selector' => '{{WRAPPER}} .etheme-search-ajax-results',
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
			]
		);
		
		$this->add_control(
			'results_offset',
			[
				'label' => __( 'Top offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--s-results-offset: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_max_height',
			[
				'label' => __( 'Max Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
                'frontend_available' => true
			]
		);
		
		// make it for wrapper because scrollbar will be ok then
		$this->add_control(
			'results_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-ajax-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$is_rtl = is_rtl();
		$this->add_control(
			'results_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .autocomplete-suggestions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
                    'left' => $is_rtl ? 1 : 0,
                    'right' => $is_rtl ? 0 : 1,
                    'isLinked' => false
                ],
              
			]
		);
		
		$this->add_control(
			'results_items_style_heading',
			[
				'label' => __( 'Items', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'results_title_v_space',
			[
				'label' => __( 'Title Vertical Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--v-title-space: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'ajax_search_results_heading_type' => 'headings'
                ]
			]
		);
		
		$this->add_control(
			'results_item_v_space',
			[
				'label' => __( 'Item Vertical Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--v-item-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_item_h_space',
			[
				'label' => __( 'Item Horizontal Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--h-item-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_item_min_height',
			[
				'label' => __( 'Item Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--item-min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'results_button_style_heading',
			[
				'label' => __( 'View All Results Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'results_button_typography',
				'selector' => '{{WRAPPER}} .etheme-search-form-more button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_results_button_style' );
		
		$this->start_controls_tab(
			'tab_results_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'results_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'results_button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-more button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
                        'default' => '#000000',
					],
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_results_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'results_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button:hover, {{WRAPPER}} .etheme-search-form-more button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-search-form-more button:hover svg, {{WRAPPER}} .etheme-search-form-more button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'results_button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-search-form-more button:hover, {{WRAPPER}} .etheme-search-form-more button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
                    'color' => [
                        'default' => '#3f3f3f'
                    ]
				],
			]
		);
		
		$this->add_control(
			'results_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'results_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button:hover, {{WRAPPER}} .etheme-search-form-more button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'results_button_border',
				'selector' => '{{WRAPPER}} .etheme-search-form-more button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'results_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'results_button_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-search-form-more button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * @since 4.0.10
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$is_woocommerce = class_exists('WooCommerce');
		
		$this->add_render_attribute(
			'form', [
				'class' => 'etheme-search-form',
				'role' => 'search',
				'action' => home_url(),
				'method' => 'get',
				'type' => $settings['type']
			]
		);
		
		$this->add_render_attribute(
			'input', [
				'placeholder' => $settings['placeholder'],
				'class' => 'etheme-search-form-input',
				'type' => 'search',
				'name' => 's',
				'title' => __( 'Search', 'xstore-core' ),
				'value' => get_search_query(),
			]
		);
		
		$this->add_render_attribute(
			'button', [
				'class' => 'etheme-search-form-submit',
				'type' => 'submit',
				'title' => __( 'Search', 'xstore-core' ),
				'aria-label' => __( 'Search', 'xstore-core' ),
			]
		);
		
		$categories = '';
		
		if ( $settings['categories'] ) { // show categories
			
			$taxonomy = ( $is_woocommerce ) ? 'product_cat' : 'category';
			
			$categories = wp_dropdown_categories(
				array(
					'show_option_all' => 'All categories',
					'taxonomy'        => $taxonomy,
					'hierarchical'    => true,
					'echo'            => false,
					'id'              => null,
					'class' => 'etheme-search-form-select',
					'name'            => $taxonomy,
					'orderby'         => 'name',
					'value_field'     => 'slug',
					'hide_if_empty'   => true
				)
			);
			
		}
		
		?>
		
		<form <?php echo $this->get_render_attribute_string( 'form' ); ?>>
		
		    <div class="etheme-search-input-form-wrapper">
			
                <?php echo str_replace( '<select', '<select style="width: 100%; max-width: calc(122px + 1.4em)"', $categories ); ?>
                
                <div class="etheme-search-input-wrapper">
                
                    <input <?php echo $this->get_render_attribute_string( 'input' ); ?>>
                    
                    <input type="hidden" name="et_search" value="true">
                    
                    <?php if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) ) : ?>
                        <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/>
                    <?php endif ?>
                    
                    <?php
                    if ( $is_woocommerce ): ?>
                        <input type="hidden" name="post_type" value="product">
                    <?php endif ?>
                    
                    <span class="etheme-search-form-clear">
                        <svg xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24" fill="currentColor"><path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path></svg>
                    </span>
                
                </div>
                
                <div class="etheme-search-form-button-wrapper">
                    
                    <button <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                        
                        <?php
                            if ( $settings['button_icon_align'] == 'left')
                                $this->render_icon( $settings );
                            
                            if ( ! empty( $settings['button_text'] ) )
                                echo '<span class="button-text">'.$settings['button_text'].'</span>';
                            else
                                echo '<span class="elementor-screen-only">' . esc_html__( 'Search', 'xstore-core' ). '</span>';
                        
                            if ( $settings['button_icon_align'] == 'right')
                                $this->render_icon( $settings );
                        ?>
                        
                    </button>
                </div>
            
            </div>

            <div class="etheme-search-ajax-results"></div>
			
		</form>
		<?php
    }
	
	/**
	 * Render Icon HTML.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.10
	 *
	 */
    public function render_icon($settings) {
	    $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
	    $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();

		if ( ! empty( $settings['button_icon'] ) || ! empty( $settings['button_selected_icon']['value'] ) ) : ?>
			<?php if ( $is_new || $migrated ) :
				\Elementor\Icons_Manager::render_icon( $settings['button_selected_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
				<i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i>
			<?php endif;
		endif;

    }
	
	/**
	 * Return available filtered post types.
	 *
	 * @since 4.0.10
	 *
	 * @return mixed
	 */
    private function search_post_types() {
	    $post_types = array();
	    if ( class_exists('WooCommerce') ) {
	        $post_types['product'] = __('Product', 'xstore-core');
		    $post_types['product_variation'] = __('Product Variation', 'xstore-core');
	    }
	    
	    $post_types['post'] = __('Post', 'xstore-core');
	    $post_types['page'] = __('Page', 'xstore-core');
	    
	    return apply_filters('etheme_search_post_types', $post_types);
	    
    }
}
