<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Testimonials widget.
 *
 * @since      4.0.6
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Testimonials extends \Elementor\Widget_Base {
	
	use Elementor;
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'testimonials';
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
		return __( 'Testimonials', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-testimonials';
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
		return [ 'testimonials' ];
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
	public function get_script_depends() {
		$scripts = [];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
			$scripts[] = 'etheme_elementor_slider';
		return $scripts;
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
		return ['etheme-elementor-testimonials'];
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
	 * Register Testimonials widget controls.
	 *
	 * @since 4.0.6
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'type',
			[
				'label'         =>  esc_html__( 'Type', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SELECT,
				'options'       =>  [
					'slider'   =>  esc_html__('Slider', 'xstore-core'),
					'grid'  =>  esc_html__('Grid', 'xstore-core'),
				],
				'default'       => 'slider'
			]
		);
		
		$this->add_control(
			'style',
			[
				'label'         =>  esc_html__( 'Style', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SELECT,
				'options'       =>  [
					'style-1'   =>  esc_html__('Style 1', 'xstore-core'),
					'style-2'   =>  esc_html__('Style 2', 'xstore-core'),
					'style-3'   =>  esc_html__('Style 3', 'xstore-core'),
				],
				'default'       => 'style-1'
			]
		);
		
		$this->add_responsive_control(
			'cols',
			[
				'label' => __( 'Columns', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '4',
				'tablet_default' => '3',
				'mobile_default' => '1',
				'selectors' => [
					'{{WRAPPER}}' => '--cols: {{VALUE}};',
				],
				'condition' => [
					'type' => 'grid'
				]
			]
		);
		
		$this->add_control(
			'image_position',
			[
				'label' 		=>	__( 'Image Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default'		=> 'top',
				'condition' => [
					'style!' => 'style-3'
				]
			]
		);
		
		$this->add_control(
			'add_shadow',
			[
				'label'         =>  esc_html__( 'Drop-Shadow', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>  'yes',
				'default'       =>  '',
				'selectors' => [
					'{{WRAPPER}} .testimonial .content-wrapper' => 'padding: 25px;',
				],
			]
		);
		
		$this->add_control(
			'add_border',
			[
				'label'         =>  esc_html__( 'With Border', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>  'yes',
				'default'       =>  '',
				'selectors' => [
					'{{WRAPPER}} .testimonial .content-wrapper' => 'padding: 25px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'image', [
				'label' => esc_html__('Image', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'         => 'image_size',
				'label'        => __( 'Image Size', 'xstore-core' ),
				'default'      => 'thumbnail',
				'condition' => [
					'image[url]!' => ''
				]
			]
		);
		
		
		$repeater->add_control(
			'rating',
			[
				'label' 		=> esc_html__( 'Show Stars', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  esc_html__( 'None', 'xstore-core' ),
					'1'	=>	'1',
					'2'	=>	'2',
					'3'	=>	'3',
					'4'	=>	'4',
					'5'	=>	'5',
				],
				'default' => '4'
			]
		);
		
		$repeater->add_control(
			'name', [
				'label' => esc_html__('Name', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Name', 'xstore-core'),
			]
		);
		
		$repeater->add_control(
			'country', [
				'label' => esc_html__('Country', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Country', 'xstore-core'),
			]
		);
		
		$repeater->add_control(
			'content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Some promo words', 'xstore-core' ),
			]
		);
		
		$this->start_controls_section(
			'et_section_tabs_content_settings',
			[
				'label' => esc_html__('Items', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'testimonials_tab',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'name' => esc_html__('Savannah Fox', 'xstore-core'),
						'content' => esc_html__('I must say the theme is awesome. If somebody bought it just have in mind to correctly configure it and to contact support if facing a problem; support is fast and realiable. Thanks!', 'xstore-core'),
						'country' => 'Nauru',
					],
					[
						'name' => esc_html__('Judith Mckinney', 'xstore-core'),
						'content' => esc_html__('This is by far the best theme on Themeforest. It adapts to a lot of the plugins, and their customer support is great. I really love this theme! Thanks 8theme.', 'xstore-core'),
						'country' => 'Seychelles',
					],
					[
						'name' => esc_html__('Harold Nguyen', 'xstore-core'),
						'content' => esc_html__('As always a 5 star! i bought this theme the third or fourth time so far... really loving it. the new update from 6.0 is awesome', 'xstore-core'),
						'country' => 'Syrian Arab Republic',
					],
					[
						'name' => esc_html__('Savannah Fox', 'xstore-core'),
						'content' => esc_html__('I must say the theme is awesome. If somebody bought it just have in mind to correctly configure it and to contact support if facing a problem; support is fast and realiable. Thanks!', 'xstore-core'),
						'country' => 'Nauru',
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'type' => 'grid'
				]
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
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 30
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
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 30
				],
				'selectors' => [
					'{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'sorting_section',
			[
				'label' => esc_html__( 'Order Elements', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'image_order',
			[
				'label'		 =>	esc_html__( 'Image Order', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> -5,
						'max' 	=> 5,
						'step' 	=> 1
					],
				],
			]
		);
		
		$this->add_control(
			'rating_order',
			[
				'label'		 =>	esc_html__( 'Rating Order', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> -5,
						'max' 	=> 5,
						'step' 	=> 1
					],
				],
			]
		);
		
		$this->add_control(
			'content_order',
			[
				'label'		 =>	esc_html__( 'Content Order', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> -5,
						'max' 	=> 5,
						'step' 	=> 1
					],
				],
			]
		);
		
		$this->add_control(
			'name_order',
			[
				'label'		 =>	esc_html__( 'Name Order', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> -5,
						'max' 	=> 5,
						'step' 	=> 1
					],
				],
			]
		);
		
		$this->add_control(
			'country_order',
			[
				'label'		 =>	esc_html__( 'Country Order', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> -5,
						'max' 	=> 5,
						'step' 	=> 1
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		// slider global settings
		Elementor::get_slider_general_settings($this, [
			'type' => 'slider'
		]);
		
		$this->start_injection( [
			'type' => 'section',
			'at' => 'start',
			'of' => 'space_between',
		] );
		
		$this->add_responsive_control(
			'slides_inner_spacing',
			[
				'label'		 =>	esc_html__( 'Slides Inner Spacing', 'xstore-core' ),
				'description' => esc_html__('May be usefull with combination of box-shadow option', 'xstore-core'),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_injection();
		
		$this->update_control( 'slides_per_view', [
			'default' => 4,
			'tablet_default' => 3,
			'mobile_default' => 2,
		] );
		
		$this->start_controls_section(
			'element_style_section',
			[
				'label' => esc_html__( 'Item', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
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
				'default'		=> 'left',
				'selectors' => [
					'{{WRAPPER}} .testimonial .content-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'quotes_size_proportion',
			[
				'label'		 =>	esc_html__( 'Quotes Size', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 1,
						'max' 	=> 200,
						'step' 	=> 1
					],
				],
				'condition' => ['style' => ['style-2', 'style-3' ]],
				'selectors' => [
					'{{WRAPPER}} .etheme-testimonials .testimonial' => '--size-quotes-proportion: {{SIZE}};',
				],
			]
		);
		
		$this->add_control(
			'quotes_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'condition' => ['style' => ['style-2', 'style-3' ]],
				'selectors' => [
					'{{WRAPPER}} .etheme-testimonials .quotes' => 'color: {{VALUE}}; opacity: 1;',
				],
			]
		);
		
		$this->add_control(
			'separator_quotes_style',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .testimonial .content-wrapper'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .testimonial .content-wrapper',
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
				'condition'     => ['add_border' => 'yes' ]
			]
		);
		
		
		$this->add_control(
			'content_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '30',
					'right' => '30',
					'bottom' => '30',
					'left' => '30',
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial .content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .testimonial .content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

//		$this->add_responsive_control(
//			'content_margin',
//			[
//				'label' => esc_html__('Margin', 'xstore-core'),
//				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
//				'size_units' => ['px', 'em', '%'],
//				'selectors' => [
//					'{{WRAPPER}} .testimonial .content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//				],
//			]
//		);
		
		$this->start_controls_tabs( 'tabs_content_shadow', [
			'condition'     => [
				'add_shadow' => 'yes'
			]
		] );
		
		$this->start_controls_tab(
			'tab_content_shadow_normal',
			[
				'label'                 => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_shadow',
				'selector' => '{{WRAPPER}} .testimonial .content-wrapper',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'vertical' => 4,
							'blur' => 4,
							'color' => 'rgba(0,0,0,0.25)',
						],
					],
				],
			]
		);
		
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_content_shadow_hover',
			[
				'label'                 => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_shadow_hover',
				'selector' => '{{WRAPPER}} .testimonial:hover .content-wrapper',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'vertical' => 14,
							'blur' => 34,
							'color' => 'rgba(0,0,0,0.08)',
						],
					],
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

//		$this->add_control(
//			'size',
//			[
//				'label' 	=>	__( 'Size', 'xstore-core' ),
//				'type' 		=>	\Elementor\Controls_Manager::TEXT,
//				'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
//			]
//		);
		
		$this->add_control(
			'image_max_width',
			[
				'label' => __( 'Max Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 300,
						'min' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'separator_panel_image_style',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
		
		$this->start_controls_tabs( 'image_effects' );
		
		$this->start_controls_tab( 'image_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
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
					'{{WRAPPER}} .testimonial img' => 'opacity: {{SIZE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .testimonial img',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'image_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'image_opacity_hover',
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
					'{{WRAPPER}} .testimonial:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .testimonial:hover img',
			]
		);
		
		$this->add_control(
			'image_background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .testimonial img',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .testimonial img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'rating_style_section',
			[
				'label' => esc_html__( 'Rating', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__('Rating Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating-wrapper .star-rating span:before' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'rating_margin',
			[
				'label' => esc_html__('Margin', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .star-rating-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_style_section',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .content',
			]
		);
		
		$this->add_control(
			'content_wrapper_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .content' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_wrapper_margin',
			[
				'label' => esc_html__('Margin', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 20,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'name_style_section',
			[
				'label' => esc_html__( 'Name', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .name',
			]
		);
		
		$this->add_control(
			'name_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .name' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'name_margin',
			[
				'label' => esc_html__('Margin', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'country_style_section',
			[
				'label' => esc_html__( 'Country', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'country_typography',
				'selector' => '{{WRAPPER}} .country',
			]
		);
		
		$this->add_control(
			'country_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .country' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'country_margin',
			[
				'label' => esc_html__('Margin', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .country' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// slider style settings
		Elementor::get_slider_style_settings($this, [
			'type' => 'slider'
		] );
		
	}
	
	/**
	 * Render Testimonials widget output on the frontend.
	 *
	 * @since 4.0.6
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		$this->add_render_attribute( 'wrapper', 'class', 'etheme-testimonials' );
		
		if ( $settings['type'] == 'slider' ) {
			
			wp_enqueue_script('etheme_elementor_slider');
			
			$this->add_render_attribute( 'wrapper', [
				'class' => [
					'etheme-elementor-swiper-entry',
					'swiper-entry',
					$settings['arrows_position'],
					$settings['arrows_position_style']
				]
			]);
			
			
			$this->add_render_attribute( 'wrapper-inner',
				[
					'class' =>
						[
							'swiper-container',
							'etheme-elementor-slider',
						]
				]
			);
			
			$this->add_render_attribute( 'testimonials-wrapper', 'class', 'swiper-wrapper');
			
		}
		
		else {
			$this->add_render_attribute( 'wrapper', 'class', 'etheme-testimonials-grid' );
		}
		
		$this->add_render_attribute( 'testimonial', [
			'class' =>
				[
					'testimonial',
//					$settings['image_position'] == 'top' ? 'layout-grid' : 'layout-list',
					$settings['style'],
				]
		]);

//		if ( $settings['add_shadow'] )
//			$this->add_render_attribute( 'testimonial', 'class', 'with-shadow');
//
//		if ( $settings['add_border'] )
//			$this->add_render_attribute( 'testimonial', 'class', 'with-border');
		
		if ( $settings['testimonials_tab'] ) {

//			$item_class = $settings['image_position'] == 'top' ? 'layout-grid' : 'layout-list';
//			$item_class .= $settings['type'] == 'slider' ? '' : ' ' . str_replace( 'col-xs-6', 'col-xs-12', etheme_get_product_class( $settings['columns'] ) );
//			$item_class .= ' ' . $settings['style'];
//			if ( $settings['add_shadow'] ) {
//				$item_class .= ' with-shadow';
//			}
//			if ( $settings['add_border'] ) {
//				$item_class .= ' with-border';
//			}
			
			$sorting   = array();
			$sorting[] = array( 'type' => 'image', 'order' => $settings['image_order']['size'] );
			$sorting[] = array( 'type' => 'rating', 'order' => $settings['rating_order']['size'] );
			$sorting[] = array( 'type' => 'content', 'order' => $settings['content_order']['size'] );
			$sorting[] = array( 'type' => 'name', 'order' => $settings['name_order']['size'] );
			$sorting[] = array( 'type' => 'country', 'order' => $settings['country_order']['size'] );
			
			usort( $sorting, function ( $item1, $item2 ) {
				return $item1['order'] <=> $item2['order'];
			} );
			
			?>
			
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				
				<?php if ( $settings['type'] == 'slider' ) : ?>
				
				<div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
					
					<div <?php $this->print_render_attribute_string( 'testimonials-wrapper' ); ?>>
						
						<?php endif;
						
						foreach (  $settings['testimonials_tab'] as $item ) {
							
							$elements = array();
							
							ob_start(); ?>
							<svg class="quotes" width=".75em" height=".62em" viewBox="0 0 75 62" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M58.5 61.8C53.9 61.8 50 60.1 46.8 56.7C43.6 53.1 42 48.3 42 42.3C42 33.7 43.3 26.7 45.9 21.3C48.5 15.7 51.4 11.3 54.6 8.09999C58.2 4.49999 62.5 1.79999 67.5 -1.14441e-05L70.5 4.8C67.1 6.39999 64.1 8.29999 61.5 10.5C59.3 12.5 57.2 14.9 55.2 17.7C53.4 20.3 52.5 23.5 52.5 27.3C52.5 28.7 52.7 29.8 53.1 30.6C53.3 31.2 53.6 31.6 54 31.8C54.4 31.6 55 31.4 55.8 31.2C56.4 31 57 30.8 57.6 30.6C58.4 30.4 59.2 30.3 60 30.3C64.6 30.3 68.2 31.7 70.8 34.5C73.6 37.1 75 40.7 75 45.3C75 49.9 73.4 53.8 70.2 57C67 60.2 63.1 61.8 58.5 61.8ZM16.5 61.8C11.9 61.8 8 60.1 4.8 56.7C1.6 53.1 8.49366e-07 48.3 8.49366e-07 42.3C8.49366e-07 33.7 1.3 26.7 3.9 21.3C6.5 15.7 9.4 11.3 12.6 8.09999C16.2 4.49999 20.5 1.79999 25.5 -1.14441e-05L28.5 4.8C25.1 6.39999 22.1 8.29999 19.5 10.5C17.3 12.5 15.2 14.9 13.2 17.7C11.4 20.3 10.5 23.5 10.5 27.3C10.5 28.7 10.7 29.8 11.1 30.6C11.3 31.2 11.6 31.6 12 31.8C12.4 31.6 13 31.4 13.8 31.2C14.4 31 15 30.8 15.6 30.6C16.4 30.4 17.2 30.3 18 30.3C22.6 30.3 26.2 31.7 28.8 34.5C31.6 37.1 33 40.7 33 45.3C33 49.9 31.4 53.8 28.2 57C25 60.2 21.1 61.8 16.5 61.8Z" fill="currentColor"></path>
							</svg>
							<?php
							$elements['quotes'] = ob_get_clean();
							
							ob_start();
							if ( $item['rating'] ) : ?>
								<span class="star-rating-wrapper"><span class="star-rating"><span style="width: <?php echo ( ( $item['rating'] / 5 ) * 100 ) . '%'; ?>"></span></span></span>
							<?php endif;
							$elements['rating'] = ob_get_clean();
							
							ob_start();
							if ( $item['name'] != '' ) : ?>
								<span class="name"><?php echo $item['name']; ?></span>
							<?php
							endif;
							$elements['name'] = ob_get_clean();
							
							ob_start();
							if ( $item['content'] != '' ) : ?>
								<span class="content"><?php echo (( $settings['style'] == 'style-2') ? $elements['quotes'] : '') . $item['content']; ?></span>
							<?php endif; ?>
							<?php $elements['content'] = ob_get_clean();
							
							ob_start();
							if ( $item['country'] != '' ) : ?>
								<span class="country"><?php echo $item['country']; ?></span>
							<?php endif; ?>
							<?php $elements['country'] = ob_get_clean();
							
							ob_start();
							if ( $settings['style'] != 'style-3' ) {
								if ( isset( $item['image']['url'] ) && $item['image']['url'] != '' ) {
									if ( $settings['image_position'] == 'top' )
										echo '<span>';
									
									echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'image_size', 'image' );
									
									if ( $settings['image_position'] == 'top' )
										echo '</span>';
								}
							}
							$elements['image'] = ob_get_clean();
							
							if ( $settings['type'] == 'slider' ) {
								echo '<div class="swiper-slide">';
							}
							
							?>
							
							<div <?php $this->print_render_attribute_string( 'testimonial' ); ?>>
								
								<div class="content-wrapper">
									
									<?php if ( $settings['image_position'] == 'left' ) {
										echo $elements['image'];
									}
									
									if ( $settings['style'] == 'style-3') :
										echo $elements['quotes'];
									endif; ?>
									
									<div class="inner-content">
										
										<?php foreach ($sorting as $sorting_item) {
											if ( $sorting_item['type'] == 'image' && in_array($settings['image_position'], array('left', 'right')) ) continue;
											echo $elements[$sorting_item['type']];
										} ?>
									
									</div>
									
									<?php if ( $settings['image_position'] == 'right' ) {
										echo $elements['image'];
									} ?>
								
								</div>
							
							</div>
							
							<?php
							if ( $settings['type'] == 'slider' ) {
								echo '</div>'; // .swiper-slide
							}
							?>
						<?php }
						
						if ( $settings['type'] == 'slider' ) : ?>
					</div> <?php // testimonials-wrapper
					endif;
					
					if ( $settings['type'] == 'slider' && 1 < count($settings['testimonials_tab']) ) {
						
						if ( in_array($settings['navigation'], array('both', 'dots')) )
							Elementor::get_slider_pagination($this, $settings, $edit_mode);
						
					}
					
					if ( $settings['type'] == 'slider' ) : ?>
				
				</div> <?php // wrapper-inner
			
			endif;
			
			if ( $settings['type'] == 'slider' && 1 < count($settings['testimonials_tab']) ) :
				
				if ( in_array($settings['navigation'], array('both', 'arrows')) )
					Elementor::get_slider_navigation($settings, $edit_mode);
			
			endif;
			
			?>
			
			</div>
			
			<?php
			
		}
		
	}
	
}
