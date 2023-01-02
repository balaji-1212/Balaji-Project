<?php

namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Categories_lists as Categories_lists_Shortcodes;

/**
 * Categories Lists widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Categories_lists extends \Elementor\Widget_Base {
	
	use Elementor;
	
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since  2.1.3
	 * @access public
	 *
	 */
	public function get_name() {
		return 'etheme_categories_lists';
	}
	
	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since  2.1.3
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Product Categories Lists', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since  2.1.3
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-categories-list';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 * @since  2.1.3
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [ 'woocommerce-elements', 'shop', 'store', 'categories', 'query', 'term', 'list', 'product' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since  2.1.3
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'eight_theme_general' ];
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
	 * Register Categories Lists widget controls.
	 *
	 * @since  2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'display_type',
			[
				'label'    => __( 'Display Type', 'xstore-core' ),
				'type'     => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options'  => [
					'grid'   => esc_html__( 'Grid', 'xstore-core' ),
					'slider' => esc_html__( 'Slider', 'xstore-core' ),
				],
				'default'  => 'grid'
			]
		);
		
		$this->add_control(
			'img_position',
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
					'without' => [
						'title' => __( 'Without', 'xstore-core' ),
						'icon' => 'eicon-editor-close',
					],
				],
				'default'		=> 'left',
			]
		);
		
		$this->add_control(
			'image_size',
			[
				'label' 		=>	__( 'Image Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
//					'shop_catalog'                  => esc_html__('Shop_catalog', 'xstore-core'),
					'woocommerce_thumbnail'         => esc_html__('Woocommerce_thumbnail', 'xstore-core'),
//					'woocommerce_gallery_thumbnail' => esc_html__('Woocommerce_gallery_thumbnail', 'xstore-core'),
//					'woocommerce_single'            => esc_html__('Woocommerce_single', 'xstore-core'),
//					'shop_thumbnail'                => esc_html__('Shop_thumbnail', 'xstore-core'),
//					'shop_single'                   => esc_html__('Shop_single', 'xstore-core'),
					'thumbnail'                     => esc_html__('Thumbnail', 'xstore-core'),
//					'medium'                        => esc_html__('Medium', 'xstore-core'),
//					'large'                         => esc_html__('Large', 'xstore-core'),
					'full'                          => esc_html__('Full', 'xstore-core'),
				],
				'default'		=> 'woocommerce_thumbnail',
			]
		);
		
		$this->add_control(
			'columns',
			[
				'label'      => __( 'Columns', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SELECT,
				'options'    => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'display_type' => 'grid'
				],
				'default'    => '3',
			]
		);
		
		$this->add_control(
			'grid_spacing',
			[
				'label'		 =>	esc_html__( 'Grid Items Spacing', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'condition' => [
					'display_type' => 'grid'
				],
				'selectors' => [
					'{{WRAPPER}} .category-list-item-wrapper' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-grid' => 'margin-bottom: calc(2 * {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}} .categories-grid' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'slides',
			[
				'label'          => esc_html__( 'Slider Items', 'xstore-core' ),
				'type'           => \Elementor\Controls_Manager::NUMBER,
				'default'        => 4,
//				'tablet_default' => 3,
//				'mobile_default' => 2,
				'min'            => 0,
				'condition' => [
					'display_type' => 'slider'
				],
			]
		);
		
		$this->add_control(
			'slider_spacing',
			[
				'label'		 =>	esc_html__( 'Slider Items Spacing', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'condition' => [
					'display_type' => 'slider'
				],
			]
		);
		
		$this->add_control(
			'number',
			[
				'label' => __( 'Limit', 'xstore-core' ),
				'type'  => \Elementor\Controls_Manager::NUMBER,
				'min'   => '',
				'max'   => '',
				'step'  => '1',
			]
		);
		
		$this->add_control(
			'quantity',
			[
				'label' => __( 'Limit Of Subcategories', 'xstore-core' ),
				'type'  => \Elementor\Controls_Manager::NUMBER,
				'min'   => '',
				'max'   => '',
				'step'  => '1',
			]
		);
		
		$this->add_control(
			'more_link',
			[
				'label'        => esc_html__( 'More Link', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$this->add_control(
			'more_link_type',
			[
				'label' 		=>	__( 'More Link Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'link'                  => esc_html__('Link', 'xstore-core'),
					'button'         => esc_html__('Button', 'xstore-core'),
				],
				'default'		=> 'link',
				'condition' => ['more_link' => ['yes']],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'hover_effect_settings',
			[
				'label' => __( 'Hover Effects', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'image_hover',
			[
				'label'       => esc_html__( 'Image Effect', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'zoom-in' => esc_html__( 'Zoom In', 'xstore-core' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'xstore-core' ),
					'rtl' => esc_html__( 'RTL', 'xstore-core' ),
					'ltr' => esc_html__( 'LTR', 'xstore-core' ),
					'border-in' => esc_html__( 'Border In', 'xstore-core' ),
					'random' => esc_html__('Random', 'xstore-core'),
					'none' => esc_html__( 'None', 'xstore-core' ),
				),
				'default'     => 'zoom-in',
			]
		);
		
		$this->add_control(
			'zoom_percent',
			[
				'label'		 =>	esc_html__( 'Zoom Percent', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 1,
						'max' 	=> 2,
						'step' 	=> .1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1.2,
				],
				'condition' => ['image_hover' => ['zoom-in', 'zoom-out', 'border-in', 'random']],
				'selectors' => [
					'{{WRAPPER}} .category-grid[data-hover="zoom-in"]:hover .category-bg,
					{{WRAPPER}} .category-grid[data-hover="zoom-in"]:hover img,
                    {{WRAPPER}} .category-grid[data-hover="zoom-out"]:not(:hover) .category-bg,
                    {{WRAPPER}} .category-grid[data-hover="zoom-out"]:not(:hover) img,
                    {{WRAPPER}} .category-grid[data-hover="border-in"]:not(:hover) .category-bg,
                    {{WRAPPER}} .category-grid[data-hover="border-in"]:not(:hover) img' => 'transform: scale({{SIZE}});',
				],
			]
		);
		
		$this->add_control(
			'move_percent',
			[
				'label'		 =>	esc_html__( 'Move Value', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 50,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'condition' => ['image_hover' => ['rtl', 'ltr', 'random']],
				'selectors' => [
					'{{WRAPPER}} .category-grid[data-hover="ltr"] img,
                    {{WRAPPER}} .category-grid[data-hover="rtl"] img' => 'width: calc(100% + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .category-grid[data-hover="rtl"]:not(:hover) img' => 'transform: translateX(-{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .category-grid[data-hover="ltr"]:not(:hover) img' => 'transform: translateX({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .category-grid[data-hover="ltr"] img' => 'left: -{{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'transition',
			[
				'label'		 =>	esc_html__( 'Transition', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 2,
						'step' 	=> .1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.3,
				],
				'condition' => [
					'image_hover!' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .category-grid img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_hover_border_in',
				'label' => esc_html__('Border In Hover', 'xstore-core'),
				'separator' => 'before',
				'condition' => ['image_hover' => ['border-in', 'random']],
				'selector' => '{{WRAPPER}} .category-grid[data-hover="border-in"] > a:after',
			]
		);
		
		$this->add_control(
			'add_overlay',
			[
				'label' 		=> __( 'Add Overlay', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$this->start_controls_tabs('overlay_tabs');
		
		$this->start_controls_tab( 'overlay_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core'),
				'condition' => ['add_overlay' => ['yes']],
			]
		);
		
		$this->add_control(
			'overlay_color',
			[
				'label' 	=> esc_html__( 'Overlay Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'condition' => ['add_overlay' => ['yes']],
				'selectors' => [
					'{{WRAPPER}} .category-grid[data-overlay] > a:before' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'overlay_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core'),
				'condition' => ['add_overlay' => ['yes']]
			]
		);
		
		$this->add_control(
			'overlay_color_hover',
			[
				'label' 	=> esc_html__( 'Overlay Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'condition' => ['add_overlay' => ['yes']],
				'selectors' => [
					'{{WRAPPER}} .category-grid[data-overlay]:hover > a:before' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'data_settings',
			[
				'label' => __( 'Query', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'ids',
			[
				'label'       => __( 'Categories', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'description' => esc_html__( 'Enter categories.', 'xstore-core' ),
				'label_block' => 'true',
				'multiple'    => true,
				'options'     => Elementor::get_terms( 'product_cat' ),
			]
		);
		
		$this->add_control(
			'exclude',
			[
				'label'       => __( 'Exclude', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'description' => esc_html__( 'Enter categories.', 'xstore-core' ),
				'label_block' => 'true',
				'multiple'    => true,
				'options'     => Elementor::get_terms( 'product_cat' ),
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label'       => __( 'Order By', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple'    => true,
				'options'     => array(
					'ids_order' => esc_html__( 'As IDs provided order', 'xstore-core' ),
					'ID'        => esc_html__( 'ID', 'xstore-core' ),
					'name'      => esc_html__( 'Title', 'xstore-core' ),
					'count'     => esc_html__( 'Quantity', 'xstore-core' ),
				),
				'default'     => 'name',
			]
		);
		
		$this->add_control(
			'order',
			[
				'label'       => __( 'Order Way', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options'     => array(
					'ASC'  => esc_html__( 'Ascending', 'xstore-core' ),
					'DESC' => esc_html__( 'Descending', 'xstore-core' ),
				),
				'default'     => 'ASC',
			]
		);
		
		$this->add_control(
			'hide_empty',
			[
				'label'        => __( 'Hide Empty', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'slider_settings',
			[
				'label'     => __( 'Slider', 'xstore-core' ),
				'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [ 'display_type' => 'slider' ],
			]
		);
		
		$this->add_control(
			'slider_autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
			]
		);
		
		$this->add_control(
			'slider_stop_on_hover',
			[
				'label'        => esc_html__( 'Pause On Hover', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
				'condition' => [
					'slider_autoplay' => 'true'
				],
			]
		);
		
		$this->add_control(
			'slider_interval',
			[
				'label'        => esc_html__( 'Autoplay Speed', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::NUMBER,
				'description'  => esc_html__( 'Interval between slides. In milliseconds.', 'xstore-core' ),
				'return_value' => 'true',
				'default'      => 3000,
				'condition' => [
					'slider_autoplay' => 'true'
				],
			]
		);
		
		$this->add_control(
			'slider_loop',
			[
				'label'        => esc_html__( 'Infinite Loop', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
			]
		);
		
		$this->add_control(
			'slider_speed',
			[
				'label'       => esc_html__( 'Transition Speed', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => esc_html__( 'Duration of transition between slides. In milliseconds.', 'xstore-core' ),
				'default'     => '300',
			]
		);
		
		$this->add_control(
			'slider_valign',
			[
				'label'     => __( 'Vertical Align', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper' => 'align-items: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'slider_content_section',
			[
				'label' => esc_html__( 'Navigation & Pagination', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [ 'display_type' => 'slider' ],
			]
		);
		
		$this->add_control(
			'navigation_header',
			[
				'label'     => esc_html__( 'Navigation', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'hide_buttons',
			[
				'label'        => esc_html__( 'Hide Navigation', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
		
		$this->add_control(
			'hide_buttons_for',
			[
				'label'     => esc_html__( 'Hide Navigation Only For', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''        => esc_html__( 'Both', 'xstore-core' ),
					'mobile'  => esc_html__( 'Mobile', 'xstore-core' ),
					'desktop' => esc_html__( 'Desktop', 'xstore-core' ),
				],
				'condition' => [ 'hide_buttons' => 'yes' ]
			]
		);
		
		$this->add_control(
			'navigation_type',
			[
				'label'     => esc_html__( 'Navigation Type', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'arrow'   => esc_html__( 'Arrow', 'xstore-core' ),
					'archery' => esc_html__( 'Archery', 'xstore-core' ),
				],
				'default'   => 'arrow',
				'condition' => [ 'hide_buttons' => '' ]
			]
		);
		
		$this->add_control(
			'navigation_style',
			[
				'label'     => esc_html__( 'Navigation Style', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'style-1' => esc_html__( 'Style 1', 'xstore-core' ),
					'style-2' => esc_html__( 'Style 2', 'xstore-core' ),
					'style-3' => esc_html__( 'Style 3', 'xstore-core' ),
					'style-4' => esc_html__( 'Style 4', 'xstore-core' ),
					'style-5' => esc_html__( 'Style 5', 'xstore-core' ),
					'style-6' => esc_html__( 'Style 6', 'xstore-core' ),
				],
				'default'   => 'style-1',
				'condition' => [ 'hide_buttons' => '' ]
			]
		);
		
		$this->add_control(
			'navigation_position',
			[
				'label'     => esc_html__( 'Navigation Position', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'middle'        => esc_html__( 'Middle', 'xstore-core' ),
					'middle-inside' => esc_html__( 'Middle Inside', 'xstore-core' ),
				],
				'default'   => 'middle',
				'condition' => [ 'hide_buttons' => '' ]
			]
		);
		
		$this->add_control(
			'navigation_position_style',
			[
				'label'      => esc_html__( 'Nav Hover Style', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SELECT,
				'options'    => [
					'arrows-hover'  => esc_html__( 'Display On Hover', 'xstore-core' ),
					'arrows-always' => esc_html__( 'Always Display', 'xstore-core' ),
				],
				'default'    => 'arrows-hover',
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'hide_buttons',
							'operator' => '!=',
							'value'    => 'yes'
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'navigation_position',
									'operator' => '=',
									'value'    => 'middle'
								],
								[
									'name'     => 'navigation_position',
									'operator' => '=',
									'value'    => 'middle-inside'
								],
							]
						]
					]
				]
			]
		);
		
		$this->add_responsive_control(
			'navigation_size',
			[
				'label'      => esc_html__( 'Navigation Size', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 120,
						'step' => 1
					],
				],
				'condition' => [
					'hide_buttons!' => 'yes'
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'navigation_style_tabs' );
		$this->start_controls_tab( 'navigation_style_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' )
			]
		);
		
		$this->add_control(
			'nav_color',
			[
				'label'     => esc_html__( 'Nav Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left,
					{{WRAPPER}} .swiper-entry .swiper-custom-right' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'nav_bg_color',
			[
				'label'     => esc_html__( 'Nav Background Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left,
					{{WRAPPER}} .swiper-entry .swiper-custom-right' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'arrows_border_color',
			[
				'label'     => esc_html__( 'Nav Border Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left,
					{{WRAPPER}} .swiper-entry .swiper-custom-right' => 'border-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'navigation_style_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' )
			]
		);
		
		$this->add_control(
			'nav_color_hover',
			[
				'label'     => esc_html__( 'Nav Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left:hover,
					{{WRAPPER}} .swiper-entry .swiper-custom-right:hover' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'arrows_bg_color_hover',
			[
				'label'     => esc_html__( 'Nav Background', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left:hover,
					{{WRAPPER}} .swiper-entry .swiper-custom-right:hover' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'arrows_br_color_hover',
			[
				'label'     => esc_html__( 'Nav Border', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left:hover,
					{{WRAPPER}} .swiper-entry .swiper-custom-right:hover' => 'border-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'pagination_header',
			[
				'label'     => esc_html__( 'Pagination', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'pagination_type',
			[
				'label'   => esc_html__( 'Pagination Type', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'hide'    => esc_html__( 'Hide', 'xstore-core' ),
					'bullets' => esc_html__( 'Bullets', 'xstore-core' ),
					'lines'   => esc_html__( 'Lines', 'xstore-core' ),
				],
				'default' => 'hide',
			]
		);
		
		$this->add_control(
			'hide_fo',
			[
				'label'     => esc_html__( 'Hide Pagination Only For', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''        => esc_html__( 'Select option', 'xstore-core' ),
					'mobile'  => esc_html__( 'Mobile', 'xstore-core' ),
					'desktop' => esc_html__( 'Desktop', 'xstore-core' ),
				],
				'condition' => [ 'pagination_type' => [ 'bullets', 'lines' ] ],
			]
		);
		
		$this->start_controls_tabs('pagination_style_tabs');
		$this->start_controls_tab( 'pagination_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core'),
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);
		
		$this->add_control(
			'default_color',
			[
				'label' 	=> esc_html__( 'Pagination Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}; opacity: 1;',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->start_controls_tab( 'pagination_style_active',
			[
				'label' => esc_html__('Hover/Active', 'xstore-core'),
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);
		
		$this->add_control(
			'active_color',
			[
				'label' 	=> esc_html__( 'Pagination Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}}; opacity: 1;',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'General Title', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'global_title_typography',
				'label' => esc_html__('Title', 'xstore-core'),
				'selector' => '{{WRAPPER}} .title',
			]
		);
		
		$this->add_control(
			'global_title_color',
			[
				'label' => esc_html__('Title color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'global_title_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'global_title_align',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default'		=> 'center',
				'selectors' => [
					'{{WRAPPER}} .title' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'image_spacing',
			[
				'label'      => esc_html__( 'Image Spacing', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .image-top .category-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .image-right .category-image' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .image-left .category-image' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .category-list-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'Item', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
		
		$this->add_control(
			'item_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border_in',
				'label' => esc_html__('Border', 'xstore-core'),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .category-list-item',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .category-list-item'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_shadow',
				'selector' => '{{WRAPPER}} .category-list-item',
				'condition' => [ 'display_type' => 'grid' ],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Categories List', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category-list-item > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'items_spacing',
			[
				'label'      => esc_html__( 'Items Spacing', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .category-list-item > ul > li > a, {{WRAPPER}} .category-list-item ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-list-item ul li ul' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-list-item .limit-link.button' => 'margin-top: calc( 10px + {{SIZE}}{{UNIT}});'
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__('Title', 'xstore-core'),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .category-list-item > ul > li > a',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'label' => esc_html__('Subtitle', 'xstore-core'),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .category-list-item ul li ul a',
			]
		);
		
		$this->start_controls_tabs( 'content_colors' );
		
		$this->start_controls_tab( 'content_colors_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Title', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item > ul > li > a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__('Subtitle', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item ul li ul a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'content_colors_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__('Title', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item > ul > li > a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'subtitle_color_hover',
			[
				'label' => esc_html__('Subtitle', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item ul li ul a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_view_more',
			[
				'label' => __( 'View More', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'more_typography',
				'label' => esc_html__('Typography', 'xstore-core'),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .category-list-item .limit-link.button, {{WRAPPER}} .category-list-item .read-more',
			]
		);
		
		$this->start_controls_tabs( 'view_more_colors' );
		
		$this->start_controls_tab( 'view_more_colors_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
				'condition' => ['more_link' => 'yes'],
			]
		);
		
		$this->add_control(
			'view_more_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item .limit-link.button, {{WRAPPER}} .category-list-item .read-more' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-list-item .read-more:before' => 'background-color: {{VALUE}};',
				],
				'condition' => ['more_link' => ['yes']],
			]
		);
		
		$this->add_control(
			'view_more_background_color',
			[
				'label' => esc_html__('Background Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item .limit-link.button' => 'background-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'more_link',
							'operator' => '=',
							'value'    => 'yes'
						],
						[
							'name'     => 'more_link_type',
							'operator' => '=',
							'value'    => 'button'
						],
					]
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'view_more_colors_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
				'condition' => ['more_link' => ['yes']],
			]
		);
		
		$this->add_control(
			'view_more_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item .limit-link.button:hover, {{WRAPPER}} .category-list-item .read-more:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-list-item .read-more:hover:before' => 'background-color: {{VALUE}};',
				],
				'condition' => ['more_link' => ['yes']],
			]
		);
		
		$this->add_control(
			'view_more_background_color_hover',
			[
				'label' => esc_html__('Background Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-list-item .limit-link.button:hover' => 'background-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'more_link',
							'operator' => '=',
							'value'    => 'yes'
						],
						[
							'name'     => 'more_link_type',
							'operator' => '=',
							'value'    => 'button'
						],
					]
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
	}
	
	/**
	 * Render Categories Lists widget output on the frontend.
	 *
	 * @since  2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( !class_exists('WooCommerce') ) {
			echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
			     esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core') .
			     '</div>';
			return;
		}
		
		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1 ) ) {
				continue;
			}
			
			if ( $setting ) {
				
				switch ( $key ) {
					case 'ids':
					case 'exclude':
						$atts[ $key ] = ! empty( $setting ) ? implode( ',', $setting ) : '';
						// $atts[$key] = $setting;
						break;
					case 'slides':
						$atts['large'] = $atts['notebook'] = ! empty( $setting ) ? $setting : 4;
						break;
					case 'slides_tablet':
						$atts['tablet_land'] = $atts['tablet_portrait'] = ! empty( $setting ) ? $setting : 2;
						break;
					case 'slides_mobile':
						$atts['mobile'] = ! empty( $setting ) ? $setting : 1;
						break;
					case 'slider_spacing':
						$atts['slider_spacing'] = $setting['size'];
						break;
					default:
						$atts[ $key ] = $setting;
						break;
				}
				
			}
			
			if ( in_array($key, array('hide_empty', 'more_link') ) ) {
				$atts[ $key ] = $setting == 'yes' ? $setting : false;
			}
			
		}
		
		$atts['hover_type'] = 'classic';
		$atts['is_preview'] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
		$atts['is_elementor'] = true;
		
		$Categories_lists_Shortcodes = Categories_lists_Shortcodes::get_instance();
		echo $Categories_lists_Shortcodes->categories_lists_shortcode( $atts );
		
	}
	
}
