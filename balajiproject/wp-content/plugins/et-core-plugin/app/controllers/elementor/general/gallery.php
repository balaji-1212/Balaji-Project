<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Gallery widget.
 *
 * @since      4.0.8
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Gallery extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_gallery';
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
		return __( 'Gallery', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-gallery';
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
		return [ 'image', 'video', 'gallery', 'lightbox' ];
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
		return [ 'etheme_gallery', 'elementor-gallery' ];
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
		return [ 'etheme-elementor-gallery', 'elementor-gallery' ];
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
			'gallery_type',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Gallery Type', 'xstore-core' ),
				'default' => 'single',
				'options' => [
					'single' => __( 'Single', 'xstore-core' ),
					'multiple' => __( 'Multiple', 'xstore-core' ),
				],
			]
		);
		
		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'masonry' => esc_html__('Masonry', 'xstore-core'),
					'grid' => esc_html__('Grid', 'xstore-core'),
					'justified' => esc_html__('Justified', 'xstore-core')
				),
				'default' => 'masonry',
                'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'gallery',
			[
				'type' => \Elementor\Controls_Manager::GALLERY,
				'condition' => [
					'gallery_type' => 'single',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'open_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'xstore-core' ),
					'yes' => esc_html__( 'Yes', 'xstore-core' ),
					'no' => esc_html__( 'No', 'xstore-core' ),
				],
			]
		);
		
		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 4,
				],
				'tablet_default' => [
					'size' => 3,
				],
				'mobile_default' => [
					'size' => 2,
				],
				'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'gallery_type',
                            'operator' => '==',
                            'value' => 'multiple',
                        ],
                        [
                            'name' => 'type',
                            'operator' => '!=',
                            'value' => 'justified',
                        ],
                    ],
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'columns_description',
			[
				'raw' => esc_html__( 'Set Columns for using in multiple galleries here. It will work for grid and masonry types.', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'gallery_type',
							'operator' => '==',
							'value' => 'multiple',
						],
//						[
//							'name' => 'type',
//							'operator' => '!=',
//							'value' => 'justified',
//						],
					],
				],
			]
		);
		
		$this->add_responsive_control(
			'ideal_row_height',
			[
				'label' => __( 'Row Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 200,
				],
				'tablet_default' => [
					'size' => 150,
				],
				'mobile_default' => [
					'size' => 150,
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'gallery_type',
							'operator' => '==',
							'value' => 'multiple',
						],
						[
							'name' => 'type',
							'operator' => '===',
							'value' => 'justified',
						],
					],
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'ideal_row_height_description',
			[
				'raw' => esc_html__( 'Set Row Height for using in multiple galleries here. It will work only for justified type.', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'gallery_type',
							'operator' => '===',
							'value' => 'multiple',
						],
//						[
//							'name' => 'type',
//							'operator' => '===',
//							'value' => 'justified',
//						],
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'lightbox_icon',
			[
				'label' 		=>	__( 'Lightbox Icon', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'open_lightbox!' => 'no'
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
			'content_position',
			[
				'label'   => esc_html__( 'Position', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'inside' => esc_html__('On image', 'xstore-core'),
					'under' => esc_html__('Under image', 'xstore-core'),
				),
				'default' => 'inside',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'type',
							'operator' => '==',
							'value' => 'grid',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' 		=> 'title',
									'operator'  => '==',
									'value' 	=> 'yes'
								],
								[
									'name' 		=> 'caption',
									'operator'  => '==',
									'value' 	=> 'yes'
								],
								[
									'name' 		=> 'date',
									'operator'  => '==',
									'value' 	=> 'yes'
								],
							],
						],
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_items',
			[
				'label' => esc_html__( 'Items', 'xstore-core' ),
				'condition' => [
					'gallery_type' => 'multiple',
				],
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->start_controls_tabs( 'gallery_tabs' );
		
		$repeater->start_controls_tab(
			'gallery_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'gallery_title',
			[
				'type' => \Elementor\Controls_Manager::TEXT,
				'label' => __( 'Title', 'xstore-core' ),
				'default' => __( 'New Gallery', 'xstore-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$repeater->add_control(
			'multiple_gallery',
			[
				'type' => \Elementor\Controls_Manager::GALLERY,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->start_controls_tab(
			'gallery_advanced',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'multiple_gallery_type',
			[
				'label'   => esc_html__( 'Custom Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'inherit' => esc_html__('Inherit', 'xstore-core'),
					'masonry' => esc_html__('Masonry', 'xstore-core'),
					'grid' => esc_html__('Grid', 'xstore-core'),
					'justified' => esc_html__('Justified', 'xstore-core')
				),
				'default' => 'inherit',
			]
		);
		
		$repeater->add_responsive_control(
			'multiple_gallery_justified_item_equal_height',
			[
				'label' => esc_html__( 'Item Height', 'xstore-core' ),
				'description' => esc_html__('Makes each item same height. Reset to have origin one.', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--item-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'justified'
				]
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->end_controls_tabs();
		
		$this->add_control(
			'galleries',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ gallery_title }}}',
				'default' => [
					[
						'gallery_title' => __( 'New Gallery', 'xstore-core' ),
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'columns_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
                    'size' => 10
                ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_responsive_control(
			'rows_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
//		$this->add_control(
//			'heading_img_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Image', 'xstore-core' ),
//			]
//		);
		
		$this->start_controls_tabs( 'img_style_tabs' );
		
		$this->start_controls_tab( 'img_style_tab_normal',
			[
				'label' => __( 'Normal', 'xstore-core' )
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .etheme-gallery-image',
			]
		);
		
		$this->add_control(
			'image_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'xstore-core' ),
					'multiply' => esc_html__( 'Multiply', 'xstore-core' ),
					'screen' => esc_html__( 'Screen', 'xstore-core' ),
					'overlay' => esc_html__( 'Overlay', 'xstore-core' ),
					'darken' => esc_html__( 'Darken', 'xstore-core' ),
					'lighten' => esc_html__( 'Lighten', 'xstore-core' ),
					'color' => esc_html__( 'Color', 'xstore-core' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'xstore-core' ),
					'color-burn' => esc_html__('Color burn', 'xstore-core'),
					'hard-light' => esc_html__('Hard light', 'xstore-core'),
					'soft-light' => esc_html__('Soft light', 'xstore-core'),
					'difference' => esc_html__('Difference', 'xstore-core'),
					'exclusion' => esc_html__('Exclusion', 'xstore-core'),
					'hue' => esc_html__('Hue', 'xstore-core'),
					'saturation' => esc_html__( 'Saturation', 'xstore-core' ),
					'luminosity' => esc_html__( 'Luminosity', 'xstore-core' ),
				],
				
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-image' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'img_style_tab_hover',
			[
				'label' => __( 'Hover', 'xstore-core' )
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .etheme-gallery-item:hover .etheme-gallery-image',
			]
		);
		
		$this->add_control(
			'image_blend_mode_hover',
			[
				'label' => esc_html__( 'Blend Mode', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'xstore-core' ),
					'multiply' => esc_html__( 'Multiply', 'xstore-core' ),
					'screen' => esc_html__( 'Screen', 'xstore-core' ),
					'overlay' => esc_html__( 'Overlay', 'xstore-core' ),
					'darken' => esc_html__( 'Darken', 'xstore-core' ),
					'lighten' => esc_html__( 'Lighten', 'xstore-core' ),
					'color' => esc_html__( 'Color', 'xstore-core' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'xstore-core' ),
					'color-burn' => esc_html__('Color burn', 'xstore-core'),
					'hard-light' => esc_html__('Hard light', 'xstore-core'),
					'soft-light' => esc_html__('Soft light', 'xstore-core'),
					'difference' => esc_html__('Difference', 'xstore-core'),
					'exclusion' => esc_html__('Exclusion', 'xstore-core'),
					'hue' => esc_html__('Hue', 'xstore-core'),
					'saturation' => esc_html__( 'Saturation', 'xstore-core' ),
					'luminosity' => esc_html__( 'Luminosity', 'xstore-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-item:hover .etheme-gallery-image' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'image_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-image, {{WRAPPER}} .etheme-gallery-item-overlay:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'image_spacing',
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
					'{{WRAPPER}} .etheme-gallery-item-details' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_position' => 'under',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'alignment',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-gallery-item .etheme-gallery-item-details.with-alignment' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'content_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-item-details-inside' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
				'conditions' 	=> [
					'relation' => 'or',
					'terms' 	=> [
						[
							'name' 		=> 'content_position',
							'operator'  => '=',
							'value' 	=> 'inside'
						],
						[
							'name' 		=> 'type',
							'operator'  => '!=',
							'value' 	=> 'justified'
						],
						[
							'name' 		=> 'gallery_type',
							'operator'  => '=',
							'value' 	=> 'multiple'
						],
					]
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_tabs',
			[
				'label' => __( 'Tabs', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_type' => 'multiple',
				]
			]
		);
		
//		$this->add_control(
//			'heading_tabs_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Tabs', 'xstore-core' ),
//				'condition' => [
//					'gallery_type' => 'multiple',
//				]
//			]
//		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} .etheme-gallery-tab',
//				'condition' => [
//					'gallery_type' => 'multiple',
//				]
			]
		);
		
		$this->start_controls_tabs('tabs_style_tabs' );
		
		$this->start_controls_tab( 'tabs_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'tab_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-tab' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tabs_style_active',
			[
				'label' => esc_html__('Active', 'xstore-core')
			]
		);
		
		$this->add_control(
			'tab_color_active',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-tab.active' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'tabs_spacing',
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
					'{{WRAPPER}}' => '--tabs-spacing: {{SIZE}}{{UNIT}};',
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => __( 'Overlay', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
//		$this->add_control(
//			'heading_overlay_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Overlay', 'xstore-core' ),
//			]
//		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-gallery-item-overlay:after',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => __( 'Lightbox', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'open_lightbox!' => 'no',
					'lightbox_icon!' => ''
				]
			]
		);
		
//		$this->add_control(
//			'heading_icon_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Lightbox Icon', 'xstore-core' ),
//				'separator' => 'before',
//				'condition' => [
//					'open_lightbox!' => 'no',
//                    'lightbox_icon!' => ''
//				]
//			]
//		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-lightbox-icon' => 'color: {{VALUE}}',
				],
//				'condition' => [
//					'open_lightbox!' => 'no',
//					'lightbox_icon!' => ''
//				]
			]
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-lightbox-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
//				'condition' => [
//					'open_lightbox!' => 'no',
//					'lightbox_icon!' => ''
//				]
			]
		);
		
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-lightbox-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
//				'condition' => [
//					'open_lightbox!' => 'no',
//					'lightbox_icon!' => ''
//				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				],
			]
		);
		
//		$this->add_control(
//			'heading_title_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Title', 'xstore-core' ),
//				'separator' => 'before',
//				'condition' => [
//					'title!' => '',
//				],
//			]
//		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-item-title' => 'color: {{VALUE}}',
				],
//				'condition' => [
//					'title!' => '',
//				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-gallery-item-title',
//				'condition' => [
//					'title!' => '',
//				],
			]
		);
		
		$this->add_responsive_control(
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
					'{{WRAPPER}} .etheme-gallery-item-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
//				'condition' => [
//					'title!' => '',
//				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => __( 'Caption', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption!' => '',
				],
			]
		);
		
//		$this->add_control(
//			'heading_caption_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Caption', 'xstore-core' ),
//				'separator' => 'before',
//				'condition' => [
//					'caption!' => '',
//				],
//			]
//		);
		
		$this->add_control(
			'caption_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-item-caption' => 'color: {{VALUE}}',
				],
//				'condition' => [
//					'caption!' => '',
//				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .etheme-gallery-item-caption',
//				'condition' => [
//					'caption!' => '',
//				],
			]
		);
		
		$this->add_responsive_control(
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
					'{{WRAPPER}} .etheme-gallery-item-caption' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
//				'condition' => [
//					'caption!' => '',
//				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => __( 'Date', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'date!' => '',
				],
			]
		);
		
//		$this->add_control(
//			'heading_date_style',
//			[
//				'type' => \Elementor\Controls_Manager::HEADING,
//				'label' => __( 'Date', 'xstore-core' ),
//				'separator' => 'before',
//				'condition' => [
//					'date!' => '',
//				],
//			]
//		);
		
		$this->add_control(
			'date_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-gallery-item-date' => 'color: {{VALUE}}',
				],
//				'condition' => [
//					'date!' => '',
//				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .etheme-gallery-item-date',
//				'condition' => [
//					'date!' => '',
//				],
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.8
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', [
		        'class' => [
                    'etheme-gallery-wrapper',
                ],
            ]
        );
		
		$is_multiple = $settings['gallery_type'] == 'multiple';
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$galleries_init = array();
		
		if ( (!$is_multiple && !count($settings['gallery'])) || ($is_multiple && (!count($settings['galleries']) || count($settings['galleries']) == 1 && !count($settings['galleries'][0]['multiple_gallery']) )) ) {
            echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
                esc_html__('Please, add images to create gallery', 'xstore-core') .
                '</div>';
            return;
        }
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php
            $galleries = [];
            $galleries_settings = [];

            if ( $is_multiple ) {
                foreach ( array_values( $settings['galleries'] ) as $multi_gallery) {
                    if ( ! $multi_gallery['multiple_gallery'] ) {
                        continue;
                    }
    
                    $galleries[] = $multi_gallery['multiple_gallery'];
                    $galleries_settings[] = array(
                        'id' => $multi_gallery['_id'],
                        'title' => $multi_gallery['gallery_title'],
                        'type' => $multi_gallery['multiple_gallery_type'],
                    );
                }
            } else {
                $galleries[0] = $settings['gallery'];
	            $galleries_settings[0] = array();
            }
            
            if ( count($galleries_settings) > 1) { ?>
                
                <div class="etheme-gallery-tabs">
                
                <?php
                foreach ($galleries_settings as $gallery_key => $gallery_value) {
                    
                    $this->add_render_attribute( 'gallery_tab_'.$gallery_value['id'], [
                        'class' => [
                                'etheme-gallery-tab',
                                ( $gallery_key < 1) ? 'active' : ''
                            ],
                            'data-tab' => $gallery_value['id']
                        ] );
                    
                    if ( $edit_mode ) {
                        $this->add_render_attribute( 'gallery_tab_'.$gallery_value['id'], [
                            'class' => [
                                'elementor-clickable',
                            ],
                        ] );
                    }
                    
                    ?>
                    
                    <span <?php $this->print_render_attribute_string( 'gallery_tab_'.$gallery_value['id'] ); ?>>
                        <?php echo $gallery_value['title']; ?>
                    </span>
                    
                <?php } ?>
            
                </div>
        
            <?php }
    
            foreach ( $galleries as $gallery_key => $gallery_items ) {
                
                $gallery_type = $settings['type'];
                
                $uniq_id = rand(100, 9999);
                if ( count($galleries_settings[$gallery_key]) ) {
	                if ( $galleries_settings[$gallery_key]['type'] != 'inherit' ) {
		                $gallery_type = $galleries_settings[$gallery_key]['type'];
	                }
	                $uniq_id = $galleries_settings[$gallery_key]['id'];
                }
                
                if ( $edit_mode ) {
	                $galleries_init[] = array(
		                'container'     => '#etheme-gallery-' . $uniq_id,
		                'type'          => $gallery_type,
		                'columns'       => $settings['columns']['size'] ?? 4,
		                'horizontalGap' => $settings['columns_gap']['size'],
		                'verticalGap'   => $settings['rows_gap']['size'],
		                'idealRowHeight'   => $gallery_type == 'justified' ? $settings['ideal_row_height']['size'] : '',
	                );
                }
                
	            $this->add_render_attribute( 'gallery_'.$gallery_key, [
			            'class' => [
                            'e-gallery',
				            'etheme-gallery',
				            count($galleries) && $gallery_key < 1 ? 'active' : ''
			            ],
			            'id' => 'etheme-gallery-'.$uniq_id,
                        'data-item-settings' => json_encode(['type'=>$gallery_type])
		            ]
	            );
                
                if ( $uniq_id ) {
	                $this->add_render_attribute( 'gallery_'.$gallery_key, [
                        'data-uniq-id'=> $uniq_id
                    ]);
                }
                
                ?>
	            
	            <div <?php echo $this->get_render_attribute_string( 'gallery_'.$gallery_key ); ?>>
                    <?php
                        foreach ( $gallery_items as $item ) {
                            $this->render_image($item['id'], $settings, $gallery_type);
                        }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
        
        <?php
    
    }
	
	/**
	 * Render Image HTML.
	 *
	 * @param        $id
	 * @param        $settings
	 * @param string $gallery_type
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
    public function render_image($id, $settings, $gallery_type = 'grid') {
	    $post_data = array();
	    
	    if ( $settings['title'] ) {
	        $title = get_post_field('post_title', $id);
	        if ( $title )
		        $post_data[] = '<h4 class="etheme-gallery-item-title">'.$title.'</h4>';
	    }
	    if ( $settings['caption'] ) {
	        $caption = wp_get_attachment_caption($id);
            if ( $caption )
                $post_data[] = '<span class="etheme-gallery-item-caption">'.$caption.'</span>';
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
		    $post_data[] = '<span class="etheme-gallery-item-date">'.get_the_date( $format_options[ $settings['date_format'] ], $id ).'</span>';
        }
	
	    $image_src = wp_get_attachment_image_src( $id, 'full' );
	    
	    $image_data = [
		    'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
//		    'media' => wp_get_attachment_image_src( $id, 'full' )['0'],
		    'src' => $image_src['0'],
		    'width' => $image_src['1'],
		    'height' => $image_src['2'],
//		    'caption' => wp_get_attachment_caption($id),
//		    'description' => '',
//		    'title' => get_post_field('post_title', $id),
	    ];
	
	    $this->add_render_attribute( 'gallery_item_' . $id, [
		    'class' => [
			    'e-gallery-item',
			    'etheme-gallery-item',
		    ]
	    ]);
	
	    $this->add_render_attribute( 'gallery_item_image_overlay_' . $id, [
		    'class' => [
			    'etheme-gallery-item-overlay',
		    ]
	    ]);
	    
	    // make lightbox
	    $this->add_lightbox_data_attributes( 'gallery_item_image_' . $id, $id, $settings['open_lightbox'], 'all-' . $this->get_id() );
	
	    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		    $this->add_render_attribute( 'gallery_item_image_'.$id, [
			    'class' => 'elementor-clickable',
		    ] );
	    }
	
	    $this->remove_render_attribute( 'gallery_item_image_' . $id, 'alt' );
	    $this->remove_render_attribute( 'gallery_item_image_' . $id, 'href' );
	    $this->remove_render_attribute( 'gallery_item_image_' . $id, 'data-thumbnail' );
	    $this->remove_render_attribute( 'gallery_item_image_' . $id, 'data-width' );
	    $this->remove_render_attribute( 'gallery_item_image_' . $id, 'data-height' );
	    $this->remove_render_attribute( 'gallery_item_image_' . $id, 'style' );
	    
	    $this->add_render_attribute( 'gallery_item_image_' . $id,
		    [
			    'class' => [
				    'e-gallery-image',
				    'etheme-gallery-image',
				    'elementor-gallery-item__image',
			    ],
			    'data-thumbnail' => $image_data['src'],
			    'data-width' => $image_data['width'],
			    'data-height' => $image_data['height'],
			    'alt' => $image_data['alt'],
			    'style' => ["background-image: url('{$image_src[0]}')"],
			    'href' => wp_get_attachment_image_url($id, 'full'),
		    ]
	    ); ?>
            <div <?php echo $this->get_render_attribute_string( 'gallery_item_' . $id ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'gallery_item_image_overlay_' . $id ); ?>>
                    <a <?php echo $this->get_render_attribute_string( 'gallery_item_image_' . $id ); ?>></a>
	                <?php
	                if ( $gallery_type == 'grid' ) {
		                echo '<span class="etheme-gallery-item-details etheme-gallery-item-details-inside'.
                             (($settings['content_position'] == 'inside' && count($post_data)) ? ' with-alignment' : '').
                             '">';
		                if ( $settings['content_position'] == 'inside') {
			                $this->render_item_details($post_data, $settings['lightbox_icon'] == 'yes');
		                }
                        elseif ( $settings['lightbox_icon'] == 'yes' ) {
			                echo $this->render_lightbox_icon();
		                }
		                echo '</span>';
	                }
	                ?>
                </div>
	            <?php
                if ( $gallery_type != 'grid' ) {
	                echo '<span class="etheme-gallery-item-details etheme-gallery-item-details-inside with-alignment">';
                        $this->render_item_details($post_data, $settings['lightbox_icon'] == 'yes');
	                echo '</span>';
                }
	            ?>
	            <?php
	                if ( $gallery_type == 'grid' && $settings['content_position'] == 'under' && count($post_data) ) {
                        echo '<span class="etheme-gallery-item-details with-alignment">';
                            $this->render_item_details($post_data);
                        echo '</span>';
                    }
                ?>
            </div>
	    <?php
     
    }
	
	/**
	 * Render details HTML.
	 *
	 * @param array $post_data
	 * @param false $with_lightbox
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
    public function render_item_details($post_data = array(), $with_lightbox = false) {
	    if ( $with_lightbox ) {
		    echo $this->render_lightbox_icon();
	    }
	    echo implode( '', $post_data );
    }
	
	/**
	 * Render Lightbox Icon HTML.
	 *
	 * @since 4.0.8
	 *
	 * @return string
	 */
    public function render_lightbox_icon() {
        return '<span class="etheme-gallery-lightbox-icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.784 22.8l-6.168-6.144c1.584-1.848 2.448-4.176 2.448-6.576 0-5.52-4.488-10.032-10.032-10.032-5.52 0-10.008 4.488-10.008 10.008s4.488 10.032 10.032 10.032c2.424 0 4.728-0.864 6.576-2.472l6.168 6.144c0.144 0.144 0.312 0.216 0.48 0.216s0.336-0.072 0.456-0.192c0.144-0.12 0.216-0.288 0.24-0.48 0-0.192-0.072-0.384-0.192-0.504zM18.696 10.080c0 4.752-3.888 8.64-8.664 8.64-4.752 0-8.64-3.888-8.64-8.664 0-4.752 3.888-8.64 8.664-8.64s8.64 3.888 8.64 8.664z"></path>
            </svg>
        </span>';
    }
}
