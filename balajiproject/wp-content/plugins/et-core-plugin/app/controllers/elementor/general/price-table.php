<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Price table widget.
 *
 * @since      4.0.6
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Price_Table extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_price_table';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Pricing Table', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-price-table';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'price', 'pricing', 'table', 'woocommerce' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.6
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
	public function get_style_depends() {
		return ['etheme-elementor-price-table'];
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
	 * @since 4.0.6
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'header_section',
			[
				'label' => __( 'Header', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Subscription Plan', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'title_html_tag',
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
				],
				'default' => 'h4',
				'condition' => [
					'title!' => ''
				]
			]
		);
		
		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'subtitle_html_tag',
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
				],
				'default' => 'h5',
                'condition' => [
                        'subtitle!' => ''
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'price_section',
			[
				'label' => __( 'Price', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'currency_symbol',
			[
				'label' => __( 'Currency Symbol', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'xstore-core' ),
					'dollar' => '&#36; ' . esc_html__( 'Dollar', 'xstore-core' ),
					'euro' => '&#128; ' . esc_html__( 'Euro', 'xstore-core' ),
					'baht' => '&#3647; ' . esc_html__( 'Baht', 'xstore-core' ),
					'franc' => '&#8355; ' . esc_html__( 'Franc', 'xstore-core' ),
					'guilder' => '&fnof; ' . esc_html__( 'Guilder', 'xstore-core' ),
					'krona' => 'kr ' . esc_html__( 'Krona', 'xstore-core' ),
					'lira' => '&#8356; ' . esc_html__( 'Lira', 'xstore-core' ),
					'peseta' => '&#8359 ' . esc_html__( 'Peseta', 'xstore-core' ),
					'peso' => '&#8369; ' . esc_html__( 'Peso', 'xstore-core' ),
					'pound' => '&#163; ' . esc_html__( 'Pound Sterling', 'xstore-core' ),
					'real' => 'R$ ' . esc_html__( 'Real', 'xstore-core' ),
					'ruble' => '&#8381; ' . esc_html__( 'Ruble', 'xstore-core' ),
					'hryvnia' => '&#8372; ' . esc_html__( 'Hryvnia', 'xstore-core' ),
					'rupee' => '&#8360; ' . esc_html__( 'Rupee', 'xstore-core' ),
					'indian_rupee' => '&#8377; ' . esc_html__( 'Rupee (Indian)', 'xstore-core' ),
					'shekel' => '&#8362; ' . esc_html__( 'Shekel', 'xstore-core' ),
					'yen' => '&#165; ' . esc_html__( 'Yen/Yuan', 'xstore-core' ),
					'won' => '&#8361; ' . esc_html__( 'Won', 'xstore-core' ),
					'custom' => esc_html__( 'Custom', 'xstore-core' ),
				],
				'default' => 'dollar',
			]
		);
		
		$this->add_control(
			'currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);
		
		$this->add_control(
			'price',
			[
				'label' => __( 'Price', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '39.99',
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'currency_format',
			[
				'label' => __( 'Currency Format', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => '1,234 ⁵⁶ (Default)',
					',' => '1.234.56',
				],
			]
		);
		
//		$this->add_control(
//			'currency_position',
//			[
//				'label' => __( 'Position', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::CHOOSE,
//				'default' => 'before',
//				'options' => [
//					'before' => [
//						'title' => __( 'Before', 'xstore-core' ),
//						'icon' => 'eicon-h-align-left',
//					],
//					'after' => [
//						'title' => __( 'After', 'xstore-core' ),
//						'icon' => 'eicon-h-align-right',
//					],
//				],
//			]
//		);
		
		$this->add_control(
			'sale',
			[
				'label' => __( 'Sale', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
		
		$this->add_control(
			'original_price',
			[
				'label' => __( 'Original Price', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '59',
				'condition' => [
					'sale' => 'yes',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'original_price_text_decoration',
			[
				'label' => __( 'Original Price Tag', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'xstore-core' ),
					'line-through' => __( 'Line-through', 'xstore-core' ),
					'underline' => __( 'Underline', 'xstore-core' ),
				],
				'default' => 'line-through',
			]
		);
		
		$this->add_control(
			'period',
			[
				'label' => __( 'Period', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '/ year', 'xstore-core' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_features',
			[
				'label' => __( 'Items', 'xstore-core' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'item_text',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Item', 'xstore-core' ),
			]
		);
		
		$default_icon = [
			'value' => 'et-icon et-checked',
			'library' => 'xstore-icons',
		];
		
		$repeater->add_control(
			'selected_item_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'item_icon',
				'default' => $default_icon,
			]
		);
		
		$repeater->add_control(
			'item_icon_color',
			[
				'label' => __( 'Icon Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'features_list',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_text' => __( 'List Item #1', 'xstore-core' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => __( 'List Item #2', 'xstore-core' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => __( 'List Item #3', 'xstore-core' ),
						'selected_item_icon' => $default_icon,
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_footer',
			[
				'label' => __( 'Footer', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Sign Up today', 'xstore-core' ),
			]
		);
		
		$this->add_control(
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
		
		$this->add_control(
			'footer_additional_info',
			[
				'label' => __( 'Additional Info', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 3,
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_ribbon',
			[
				'label' => __( 'Ribbon', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'show_ribbon',
			[
				'label' => __( 'Show Ribbon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'ribbon_title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Popular', 'xstore-core' ),
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'ribbon_horizontal_position',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control(
			'scale',
			[
				'label' => __( 'Scale', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
                        'step' => .1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scale({{SIZE}}); z-index: 3;',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-wrapper',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(), [
				'name' => 'border',
				'selector' => '{{WRAPPER}} .etheme-price-table-wrapper',
				'separator' => 'before',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid'
                    ],
					'width' => [
						'default' => [
                            'top' => 1,
                            'left' => 1,
                            'right' => 1,
                            'bottom' => 1
                        ]
					],
                    'color' => [
                        'default' => '#e1e1e1'
                    ]
				],
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'left' => 3,
					'right' => 3,
					'bottom' => 3
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_header_style',
			[
				'label' => __( 'Header', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'header_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-header',
			]
		);
		
		$this->add_responsive_control(
			'header_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
                    'top' => 30,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'title!' => ''
				]
			]
		);
		
		$this->add_control(
			'heading_title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'heading_title_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-title',
				'condition' => [
					'title!' => ''
				]
			]
		);
		
		$this->add_control(
			'heading_title_space',
			[
				'label' => __( 'Bottom Space (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'condition' => [
					'subtitle!' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_subtitle_style',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'subtitle!' => ''
				]
			]
		);
		
		$this->add_control(
			'heading_subtitle_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-subtitle' => 'color: {{VALUE}};',
				],
				'condition' => [
					'subtitle!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'heading_subtitle_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-subtitle',
				'condition' => [
					'subtitle!' => ''
				]
			]
		);
		
		$this->add_control(
			'header_add_image',
			[
				'label' => __( 'Add Image Block', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'header_image_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-image-wrapper',
                'fields_options' => [
	                'background' => [
		                'default' => 'classic'
	                ],
	                'image' => [
		                'default' => [
			                'url' => \Elementor\Utils::get_placeholder_image_src(),
		                ],
	                ],
                    'position' => [
                        'default' => 'center center'
                    ],
                ],
                'condition' => [
                    'header_add_image!' => ''
                ]
			]
		);
		
		$this->add_responsive_control(
			'header_image_min_height',
			[
				'label' => __( 'Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
                    'size' => 150
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-image-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'header_add_image!' => ''
				]
			]
		);
		
		$this->add_control(
			'header_image_position',
			[
				'label' => __( 'Image Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => __( 'Before', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'after' => [
						'title' => __( 'After', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'header_add_image!' => ''
				]
			]
		);
		
		$this->add_responsive_control(
			'image_wrapper_margin',
			[
				'label' => __( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'header_add_image!' => ''
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Pricing', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'price_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-price-wrapper',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
						'default' => 'classic'
					],
					'color' => [
						'default' => '#fafafa'
					],
				],
			]
		);
		
		$this->add_responsive_control(
			'price_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-price-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-price' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-price',
			]
		);
		
		$this->add_control(
			'heading_currency_style',
			[
				'label' => __( 'Currency Symbol', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);
		
		$this->add_control(
			'currency_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-currency' => 'font-size: calc({{SIZE}}em/100)',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);
		
		$this->add_control(
			'currency_position',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => __( 'Before', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => __( 'After', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);
		
		$this->add_control(
			'currency_vertical_position',
			[
				'label' => __( 'Vertical Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-currency' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);
		
		$this->add_control(
			'fractional_part_style',
			[
				'label' => __( 'Fractional Part', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_format' => ''
				]
			]
		);

		$this->add_control(
			'fractional_part_vertical_position',
			[
				'label' => __( 'Vertical Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-fraction' => 'align-self: {{VALUE}}',
				],
                'condition' => [
                        'currency_format' => ''
                ]
			]
		);
		
		$this->add_control(
			'heading_original_price_style',
			[
				'label' => __( 'Original Price', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);
		
		$this->add_control(
			'original_price_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-original-price' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'original_price_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-original-price',
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);
		
		$this->add_control(
			'original_price_vertical_position',
			[
				'label' => __( 'Vertical Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default' => 'bottom',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-original-price' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_period_style',
			[
				'label' => __( 'Period', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'period!' => '',
				],
			]
		);
		
		$this->add_control(
			'period_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-period',
				'condition' => [
					'period!' => '',
				],
			]
		);
		
		$this->add_control(
			'period_position',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'right',
				'condition' => [
					'period!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_features_list_style',
			[
				'label' => __( 'Items', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'features_list_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-features-list',
			]
		);
		
		$this->add_responsive_control(
			'features_list_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'features_list_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'features_list_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-features-list li',
			]
		);
		
		$this->add_control(
			'features_list_alignment',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
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
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->start_controls_tabs( 'features_list_link' );
		
		$this->start_controls_tab( 'features_list_link_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		$this->add_control(
			'features_list_link_color',
			[
				'label' => __( 'Link Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#1089EF',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'features_list_link_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'features_list_link_hover_color',
			[
				'label' => __( 'Link Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'list_divider',
			[
				'label' => __( 'Add Divider', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list li + li:before' => 'content: ""; display: block; margin: 0 auto;',
				],
			]
		);
		
		$this->add_control(
			'divider_style',
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
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list li + li:before' => 'border-top-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e1e1e1',
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list li + li:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list li + li:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Width (%)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list li + li:before' => 'width: {{SIZE}}%;',
				],
			]
		);
		
		$this->add_control(
			'divider_gap',
			[
				'label' => __( 'Gap (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-features-list li + li:before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => __( 'Footer', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'footer_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-footer',
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
					'color' => [
						'default' => '#fafafa'
					],
				],
			]
		);
		
		$this->add_responsive_control(
			'footer_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'footer_button',
			[
				'label' => __( 'Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);
		
		$this->add_control(
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
                'condition' => [
					'button_text!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style', [
			'condition' => [
				'button_text!' => '',
			],
        ] );
		
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button' => 'background-color: {{VALUE}};',
				],
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
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#555',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button:hover' => 'border-color: {{VALUE}};',
				],
                'condition' => [
                        'button_border_border!' => ''
                ]
			]
		);
		
		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etheme-price-table-button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'additional_info',
			[
				'label' => __( 'Additional Info', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);
		
		$this->add_control(
			'additional_info_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-additional-info' => 'color: {{VALUE}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'additional_info_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-additional-info',
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);
		
		$this->add_control(
			'additional_info_margin',
			[
				'label' => __( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-additional-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_ribbon_style',
			[
				'label' => __( 'Ribbon', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'ribbon_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-price-table-ribbon-inner',
			]
		);
		
		$this->add_responsive_control(
			'ribbon_distance',
			[
				'label' => __( 'Distance', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-wrapper' => '--ribbon-distance: {{SIZE}}{{UNIT}};'
				],
			]
		);
		
		$this->add_control(
			'ribbon_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .etheme-price-table-ribbon-inner' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .etheme-price-table-ribbon-inner',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .etheme-price-table-ribbon-inner',
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.6
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price = explode( $currency_format, $settings['price'] );
		$price_intpart = $price[0];
		$price_fraction = '';
		if ( 2 === count( $price ) ) {
			$price_fraction = '<span class="etheme-price-fraction">'.$price[1].'</span>';
		}
		
		$price = $price_intpart . $price_fraction;
		
		$migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
		
		$this->add_render_attribute( 'wrapper', 'class', 'etheme-price-table-wrapper' );
		
		// price
		$this->add_render_attribute( 'price_wrapper', 'class', 'etheme-price-table-price-wrapper' );
        $this->add_render_attribute( 'price_wrapper', 'class', 'period-'.(empty($settings['period_position']) ? 'right' : $settings['period_position']) );
		
		$this->add_render_attribute( 'price', 'class', [
			'etheme-price-table-price',
		] );
		
		$this->add_render_attribute( 'image_wrapper', 'class', 'etheme-price-table-image-wrapper' );
		
		$this->add_render_attribute( 'title', 'class', 'etheme-price-table-title' );
		$this->add_render_attribute( 'subtitle', 'class', 'etheme-price-table-subtitle' );
		$this->add_render_attribute( 'period', 'class', 'etheme-price-table-period' );
		$this->add_render_attribute( 'footer_additional_info', 'class', 'elementor-price-table__additional_info' );
		$this->add_render_attribute( 'ribbon_title', 'class', 'elementor-price-table__ribbon-inner' );
  
		// button
		$this->add_render_attribute( 'button_text', 'class', [
			'elementor-button',
			'etheme-price-table-button',
		] );
		
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_text', $settings['link'] );
		}
		
		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		
		$this->add_render_attribute( 'footer_additional_info', 'class', [
			'etheme-price-table-additional-info',
		] );
		
		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
		$this->add_inline_editing_attributes( 'period', 'none' );
		$this->add_inline_editing_attributes( 'footer_additional_info' );
		$this->add_inline_editing_attributes( 'button_text' );
		
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( $settings['title'] || $settings['subtitle'] || $settings['header_add_image'] ) {
				echo '<header class="etheme-price-table-header">';
				if ( $settings['header_image_position'] == 'before' ) { ?>
				    <div <?php echo $this->get_render_attribute_string( 'image_wrapper' ); ?>></div>
				<?php }
				if ( $settings['title'] ) {
					echo '<' . $settings['title_html_tag'] . ' ' . $this->get_render_attribute_string( 'title' ) . '>' . $settings['title'] . '</' . $settings['title_html_tag'] . '>';
				}
				if ( $settings['subtitle'] ) {
					echo '<' . $settings['subtitle_html_tag'] . ' ' . $this->get_render_attribute_string( 'subtitle' ) . '>' . $settings['subtitle'] . '</' . $settings['subtitle_html_tag'] . '>';
				}
				if ( $settings['header_image_position'] != 'before' ) { ?>
					<div <?php echo $this->get_render_attribute_string( 'image_wrapper' ); ?>></div>
				<?php }
				echo '</header>';
			}
			?>
            <div class="price-table-content">
				<div <?php echo $this->get_render_attribute_string( 'price_wrapper' ); ?>>
					<?php
					if ( in_array($settings['period_position'], array('top', 'left')) ) {
						echo '<span '.$this->get_render_attribute_string( 'period' ).'>'.$settings['period'].'</span>';
					}
					if ( $settings['sale'] ) {
						
						$this->add_render_attribute( 'original-price', 'class', [
							'etheme-price-table-original-price',
						] );
						
						if ( $settings['original_price_text_decoration'] != '' ) {
							$this->add_render_attribute( 'original-price', 'class', [
								'price-'.$settings['original_price_text_decoration'],
							] );
						}
						
						$original_price = explode( $currency_format, $settings['original_price'] );
						$original_price_intpart = $original_price[0];
						$original_price_fraction = '';
						if ( 2 === count( $original_price ) ) {
							$original_price_fraction = '<span class="etheme-price-fraction">'.$original_price[1].'</span>';
						}
						
						$original_price = $original_price_intpart . $original_price_fraction;
						
						echo '<span '. $this->get_render_attribute_string( 'original-price' ) .'>';
								echo $this->render_price($settings, $original_price);
						echo '</span>';
					}
					
					echo '<span '. $this->get_render_attribute_string( 'price' ) .'>'.$this->render_price($settings, $price).'</span>';
					if ( !in_array($settings['period_position'], array('top', 'left')) ) {
						echo '<span '.$this->get_render_attribute_string( 'period' ).'>'.$settings['period'].'</span>';
					}
					?>
				</div>
	   
				<?php if ( ! empty( $settings['features_list'] ) ) : ?>
				
				<ul class="etheme-price-table-features-list">
					<?php
					foreach ( $settings['features_list'] as $index => $item ) :
						$repeater_setting_key = $this->get_repeater_setting_key( 'item_text', 'features_list', $index );
						$this->add_inline_editing_attributes( $repeater_setting_key );

						$migrated = isset( $item['__fa4_migrated']['selected_item_icon'] );
						// add old default
						if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
							$item['item_icon'] = 'fa fa-check-circle';
						}
						$is_new = ! isset( $item['item_icon'] ) && $migration_allowed;
						?>
						<li class="elementor-repeater-item-<?php echo $item['_id']; ?>">
								<?php if ( ! empty( $item['item_icon'] ) || ! empty( $item['selected_item_icon'] ) ) :
									if ( $is_new || $migrated ) :
										\Elementor\Icons_Manager::render_icon( $item['selected_item_icon'], [ 'aria-hidden' => 'true' ] );
									else : ?>
										<i class="<?php echo esc_attr( $item['item_icon'] ); ?>" aria-hidden="true"></i>
										<?php
									endif;
								endif; ?>
								<?php if ( ! empty( $item['item_text'] ) ) : ?>
									<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
										<?php echo $item['item_text']; ?>
									</span>
									<?php
								else :
									echo '&nbsp;';
								endif;
								?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
				
            </div>
			<?php if ( $settings['button_text'] || $settings['footer_additional_info'] ) : ?>
	            <footer class="etheme-price-table-footer">
		            <?php if ( ! empty( $settings['button_text'] ) ) : ?>
			            <a <?php echo $this->get_render_attribute_string( 'button_text' ); ?>><?php echo $settings['button_text']; ?></a>
		            <?php endif; ?>
		
		            <?php if ( ! empty( $settings['footer_additional_info'] ) ) : ?>
			            <div <?php echo $this->get_render_attribute_string( 'footer_additional_info' ); ?>><?php echo $settings['footer_additional_info']; ?></div>
		            <?php endif; ?>
				</footer>
			<?php endif; ?>
			
			<?php if ( 'yes' === $settings['show_ribbon'] && ! empty( $settings['ribbon_title'] ) ) :
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'etheme-price-table-ribbon' );
				$this->add_render_attribute( 'ribbon_title', 'class', 'etheme-price-table-ribbon-inner' );
				$this->add_inline_editing_attributes( 'ribbon_title' );
				
				if ( ! empty( $settings['ribbon_horizontal_position'] ) ) :
					$this->add_render_attribute( 'ribbon-wrapper', 'class', 'ribbon-' . $settings['ribbon_horizontal_position'] );
				endif;
				
				?>
				<div <?php echo $this->get_render_attribute_string( 'ribbon-wrapper' ); ?>>
					<div <?php echo $this->get_render_attribute_string( 'ribbon_title' ); ?>><?php echo $settings['ribbon_title']; ?></div>
				</div>
			<?php endif; ?>
		</div>
		
    <?php
	}
	
	/**
	 * Render Price with Currency symbol.
	 *
	 * @param $settings
	 * @param $price
	 * @return string
	 *
	 * @since 4.0.6
	 *
	 */
	private function render_price( $settings, $price ) {
		
		$currency_position = !empty($settings['currency_position']) ? $settings['currency_position'] : 'before';
		$symbol = $this->get_currency_symbol($settings, $settings['currency_symbol']);
		$symbol = $symbol != '' ? '<span class="etheme-price-table-currency">'.$symbol.'</span>' : $symbol;
		
		return ($currency_position == 'before') ? $symbol . $price : $price . $symbol;
	}
	
	/**
	 * Get currency symbol.
	 *
	 * @param $settings
	 * @param $symbol_name
	 * @return mixed|string
	 *
	 * @since 4.0.6
	 *
	 */
	private function get_currency_symbol( $settings, $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'franc' => '&#8355;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'hryvnia' => '&#8372;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'yen' => '&#165;',
			'won' => '&#8361;',
			'guilder' => '&fnof;',
			'peso' => '&#8369;',
			'peseta' => '&#8359',
			'lira' => '&#8356;',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real' => 'R$',
			'krona' => 'kr',
			'custom' => $settings['currency_symbol_custom']
		];
		
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}
	
	
}
