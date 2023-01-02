<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Categories as Categories_Shortcodes;

/**
 * Categories widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Categories extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_categories';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Categories', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-product-categories';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'woocommerce-elements', 'shop', 'store', 'categories', 'query', 'term', 'product' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['eight_theme_general'];
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
	 * Register Categories widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'display_type',
			[
				'label' 		=>	__( 'Display Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 		=>	false,
				'options' 		=>	[
					'grid' 		=> esc_html__('Grid', 'xstore-core'),
					'slider' 	=> esc_html__('Slider', 'xstore-core'),
//					'menu' 		=> esc_html__('Menu', 'xstore-core'),
				],
				'default'		=> 'grid'
			]
		);
		
		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::NUMBER,
				'min' 	=> '1',
				'max' 	=> '6',
				'step' 	=> '1',
				'default' => '3',
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'display_type',
							'operator'  => '=',
							'value' 	=> 'grid'
						],
					]
				],
			]
		);
		
		$this->add_responsive_control(
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
					'{{WRAPPER}} .category-grid' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}; margin-bottom: calc(2 * {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}} .categories-grid' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
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
				'type' 	=> \Elementor\Controls_Manager::NUMBER,
				'min' 	=> '',
				'max' 	=> '',
				'default' => 6,
				'step' 	=> '1',
			]
		);
		
		$this->add_control(
			'image_circle',
			[
				'label' 		=>	__( 'Image Circle', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'stretch_images',
			[
				'label' 		=> esc_html__( 'Stretch images', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' => [
					'image_circle' => ''
				]
			]
		);
		
		$this->add_control(
			'stretch_images_height',
			[
				'label'		 =>	esc_html__( 'Min Height', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 500,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'condition' => [
					'stretch_images' => 'yes',
					'image_circle!' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .category-grid > a' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'stretch_images_pos_x',
			[
				'label'		 =>	esc_html__( 'Image Position X', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'px' 		=> [
						'min' 	=> -100,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'condition' => [
					'stretch_images' => 'yes',
					'image_circle!' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .category-bg' => 'background-position-x: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_control(
			'stretch_images_pos_y',
			[
				'label'		 =>	esc_html__( 'Image Position Y', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'separator' => 'after',
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'px' 		=> [
						'min' 	=> -100,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'condition' => [
					'stretch_images' => 'yes',
					'image_circle!' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .category-bg' => 'background-position-y: {{SIZE}}{{UNIT}};',
				],
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
			'ajax',
			[
				'label' 		=>	__( 'Lazy Loading', 'xstore-core' ),
				'description' 	=>	__( 'Works for live mode, not for the preview', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
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
					'{{WRAPPER}} .category-grid[data-hover="ltr"] .category-bg,
					{{WRAPPER}} .category-grid[data-hover="ltr"] img,
                    {{WRAPPER}} .category-grid[data-hover="rtl"] .category-bg,
                    {{WRAPPER}} .category-grid[data-hover="rtl"] img' => 'width: calc(100% + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .category-grid[data-hover="rtl"]:not(:hover) .category-bg,
					{{WRAPPER}} .category-grid[data-hover="rtl"]:not(:hover) img' => 'transform: translateX(-{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .category-grid[data-hover="ltr"]:not(:hover) .category-bg,
					{{WRAPPER}} .category-grid[data-hover="ltr"]:not(:hover) img' => 'transform: translateX({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .category-grid[data-hover="ltr"] .category-bg,
					{{WRAPPER}} .category-grid[data-hover="ltr"] img' => 'left: -{{SIZE}}{{UNIT}};',
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
					'image_hover!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .category-grid img, {{WRAPPER}} .category-grid .category-bg' => 'transition-duration: {{SIZE}}s',
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
				'fields_options' => [
					'border' => [
						'default' => 'solid',
						'options' => [
							'solid' => __( 'Solid', 'xstore-core' ),
							'double' => __( 'Double', 'xstore-core' ),
							'dotted' => __( 'Dotted', 'xstore-core' ),
							'dashed' => __( 'Dashed', 'xstore-core' ),
							'groove' => __( 'Groove', 'xstore-core' ),
						],
					]
				]
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
			'content_settings',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'display_type!' => 'menu'
				]
			]
		);
		
		$this->add_control(
			'style_type',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 		=>	false,
				'options' 		=>	[
					'style-1' 		=> esc_html__('Category + x products', 'xstore-core'),
					'style-2' 	=> esc_html__('Category + count label', 'xstore-core'),
					'style-3' 		=> esc_html__('Products + category', 'xstore-core'),
					'style-4' 		=> esc_html__('Category', 'xstore-core'),
				],
				'default'		=> 'style-1',
				'condition' => [
					'display_type!' => 'menu'
				]
			]
		);
		
		$this->add_control(
			'view_more',
			[
				'label' 		=> __( 'View More Button', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$this->add_control(
			'content_position',
			[
				'label' 		=>	__( 'Content Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 		=>	false,
				'options' 		=>	[
					'under' 		=> esc_html__('Under content', 'xstore-core'),
					'inside' 	=> esc_html__('In content', 'xstore-core'),
				],
				'default'		=> 'inside',
				'condition' => [
					'stretch_images!' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'text_align',
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
				'default'		=> 'center',
			]
		);
		
		$this->add_control(
			'valign',
			[
				'label' 	=>	__( 'Vertical Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
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
				'default'	=> 'center',
				'condition' => [
					'content_position' => 'inside'
				]
			]
		);
		
		$this->add_control(
			'content_hover',
			[
				'label'       => esc_html__( 'Content Effect', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'separator' => 'before',
				'options'     => array(
					'reveal' => esc_html__( 'Reveal', 'xstore-core' ),
					'none' => esc_html__( 'None', 'xstore-core' ),
				),
				'conditions' 	=> [
					'relation' => 'or',
					'terms' 	=> [
						[
							'name' 		=> 'content_position',
							'operator'  => '=',
							'value' 	=> 'inside'
						],
						[
							'name' 		=> 'stretch_images',
							'operator'  => '=',
							'value' 	=> 'yes'
						],
					]
				],
				'default'     => 'reveal',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'data_settings',
			[
				'label' => __( 'Query', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'ids',
			[
				'label' 		=>	__( 'Categories', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'description'   =>  esc_html__( 'Enter categories.', 'xstore-core' ),
				'label_block'	=> 'true',
				'multiple' 	=>	true,
				'options' 		=> Elementor::get_terms('product_cat'),
			]
		);
		
		$this->add_control(
			'exclude',
			[
				'label' 		=>	__( 'Exclude', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'description'   =>  esc_html__( 'Enter categories.', 'xstore-core' ),
				'label_block'	=> 'true',
				'multiple' 	=>	true,
				'options' 		=> Elementor::get_terms('product_cat'),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 	=>	__( 'Order By', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ids_order' => esc_html__( 'As IDs provided order', 'xstore-core' ),
					'ID'  		=> esc_html__( 'ID', 'xstore-core' ),
					'name'  	=> esc_html__( 'Title', 'xstore-core' ),
					'count' 	=> esc_html__( 'Quantity', 'xstore-core' ),
				),
				'default' 	=>	'name',
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=>	__( 'Order Way', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options' 	=>	array(
					'ASC' 	=> esc_html__( 'Ascending', 'xstore-core' ),
					'DESC' 	=> esc_html__( 'Descending', 'xstore-core' ),
				),
				'default' 	=>	'ASC',
			]
		);
		
		$this->add_control(
			'hide_empty',
			[
				'label' 		=> __( 'Hide Empty', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);

//		$this->add_control(
//			'parent',
//			[
//				'label' 		=> __( 'Parent ID', 'xstore-core' ),
//				'type' 			=> \Elementor\Controls_Manager::NUMBER,
//				'description' 	=> 'Get direct children of this term (only terms whose explicit parent is this value). If 0 is passed, only top-level terms are returned. Default is an empty string.',
//				'min' 			=> '',
//				'max' 			=> '',
//				'step' 			=> '1',
//			]
//		);
		
		$this->add_control(
			'parent',
			[
				'label'       => __( 'Parent ID', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'description' => esc_html__( 'Get direct children of this term (only terms whose explicit parent is this value).', 'xstore-core' ),
				'label_block' => 'true',
				'multiple'    => true,
				'options'     => Elementor::get_terms( 'product_cat', true ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings',
			[
				'label' => __( 'Slider', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => ['display_type' => 'slider'],
			]
		);
		
		$this->add_control(
			'slider_autoplay',
			[
				'label' 		=> esc_html__( 'Autoplay', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
			]
		);
		
		$this->add_control(
			'slider_stop_on_hover',
			[
				'label' 		=> esc_html__( 'Pause On Hover', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
				'condition' => [
					'slider_autoplay' => 'true'
				]
			]
		);
		
		$this->add_control(
			'slider_interval',
			[
				'label' 		=> esc_html__( 'Autoplay Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> esc_html__( 'Interval between slides. In milliseconds.', 'xstore-core' ),
				'return_value' 	=> 'true',
				'default' 		=> 3000,
				'condition' => [
					'slider_autoplay' => 'true'
				]
			]
		);
		
		$this->add_control(
			'slider_loop',
			[
				'label' 		=> esc_html__( 'Infinite Loop', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'slider_speed',
			[
				'label' 		=> esc_html__( 'Transition Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> esc_html__( 'Duration of transition between slides. In milliseconds.', 'xstore-core' ),
				'default' 		=> '300',
			]
		);
		
		$this->add_responsive_control(
			'slides',
			[
				'label' 	=>	esc_html__( 'Slider Items', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::NUMBER,
				'default' 	=>	4,
//				'tablet_default' => 3,
//				'mobile_default' => 2,
				'min' => 0,
			]
		);
		
		$this->add_control(
			'slider_valign',
			[
				'label' 	=>	__( 'Vertical Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'stretch_images!' => 'yes'
				],
				'default'	=> 'flex-start',
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
				'tab' =>  \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => ['display_type' => 'slider'],
			]
		);
		
		$this->add_control(
			'navigation_header',
			[
				'label' => esc_html__( 'Navigation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'hide_buttons',
			[
				'label' 		=> esc_html__( 'Hide Navigation', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$this->add_control(
			'hide_buttons_for',
			[
				'label' 		=> esc_html__( 'Hide Navigation Only For', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  esc_html__( 'Both', 'xstore-core' ),
					'mobile'	=>	esc_html__( 'Mobile', 'xstore-core' ),
					'desktop'	=>	esc_html__( 'Desktop', 'xstore-core' ),
				],
				'condition' => ['hide_buttons' => 'yes']
			]
		);
		
		$this->add_control(
			'navigation_type',
			[
				'label' 		=> esc_html__( 'Navigation Type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'arrow' 	=>	esc_html__( 'Arrow', 'xstore-core' ),
					'archery' 	=>	esc_html__( 'Archery', 'xstore-core' ),
				],
				'default'	=> 'arrow',
				'condition' => ['hide_buttons' => '']
			]
		);
		
		$this->add_control(
			'navigation_style',
			[
				'label' 		=> esc_html__( 'Navigation Style', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'style-1' 	=>	esc_html__( 'Style 1', 'xstore-core' ),
					'style-2' 	=>	esc_html__( 'Style 2', 'xstore-core' ),
					'style-3' 	=>	esc_html__( 'Style 3', 'xstore-core' ),
					'style-4' 	=>	esc_html__( 'Style 4', 'xstore-core' ),
					'style-5' 	=>	esc_html__( 'Style 5', 'xstore-core' ),
					'style-6' 	=>	esc_html__( 'Style 6', 'xstore-core' ),
				],
				'default'	=> 'style-1',
				'condition' => ['hide_buttons' => '']
			]
		);
		
		$this->add_control(
			'navigation_position',
			[
				'label' 		=> esc_html__( 'Navigation Position', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'middle' 			=>	esc_html__( 'Middle', 'xstore-core' ),
					'middle-inside' 	=>	esc_html__( 'Middle Inside', 'xstore-core' ),
				],
				'default'	=> 'middle',
				'condition' => ['hide_buttons' => '']
			]
		);
		
		$this->add_control(
			'navigation_position_style',
			[
				'label' 		=> esc_html__( 'Nav Hover Style', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'arrows-hover' 	=>	esc_html__( 'Display On Hover', 'xstore-core' ),
					'arrows-always' 	=>	esc_html__( 'Always Display', 'xstore-core' ),
				],
				'default'		=> 'arrows-hover',
				'conditions' 	=> [
					'relation' => 'and',
					'terms' 	=> [
						[
							'name' 		=> 'hide_buttons',
							'operator'  => '!=',
							'value' 	=> 'yes'
						],
						[
							'relation' => 'or',
							'terms' 	=> [
								[
									'name' 		=> 'navigation_position',
									'operator'  => '=',
									'value' 	=> 'middle'
								],
								[
									'name' 		=> 'navigation_position',
									'operator'  => '=',
									'value' 	=> 'middle-inside'
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
				'label'		 =>	esc_html__( 'Navigation Size', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 10,
						'max' 	=> 120,
						'step' 	=> 1
					],
				],
				'condition' => [
					'hide_buttons!' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs('navigation_style_tabs');
		$this->start_controls_tab( 'navigation_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'nav_color',
			[
				'label' 	=> esc_html__( 'Nav Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left,
					{{WRAPPER}} .swiper-entry .swiper-custom-right' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'nav_bg_color',
			[
				'label' 	=> esc_html__( 'Nav Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left,
					{{WRAPPER}} .swiper-entry .swiper-custom-right' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'arrows_border_color',
			[
				'label' 	=> esc_html__( 'Nav Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
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
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'nav_color_hover',
			[
				'label' 	=> esc_html__( 'Nav Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left:hover,
					{{WRAPPER}} .swiper-entry .swiper-custom-right:hover' => 'color: {{VALUE}}; opacity: 1;',
				]
			]
		);
		
		$this->add_control(
			'arrows_bg_color_hover',
			[
				'label' 	=> esc_html__( 'Nav Background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left:hover,
					{{WRAPPER}} .swiper-entry .swiper-custom-right:hover' => 'background-color: {{VALUE}}; opacity: 1;',
				]
			]
		);
		
		$this->add_control(
			'arrows_br_color_hover',
			[
				'label' 	=> esc_html__( 'Nav Border', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .swiper-custom-left:hover,
					{{WRAPPER}} .swiper-entry .swiper-custom-right:hover' => 'border-color: {{VALUE}}; opacity: 1;',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'pagination_header',
			[
				'label' => esc_html__( 'Pagination', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'pagination_type',
			[
				'label' 		=> esc_html__( 'Pagination Type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'hide' 		=>	esc_html__( 'Hide', 'xstore-core' ),
					'bullets' 	=>	esc_html__( 'Bullets', 'xstore-core' ),
					'lines' 	=>	esc_html__( 'Lines', 'xstore-core' ),
				],
				'default' 		=> 'hide',
			]
		);
		
		$this->add_control(
			'hide_fo',
			[
				'label' 		=> esc_html__( 'Hide Pagination Only For', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  esc_html__( 'Select option', 'xstore-core' ),
					'mobile'	=>	esc_html__( 'Mobile', 'xstore-core' ),
					'desktop'	=>	esc_html__( 'Desktop', 'xstore-core' ),
				],
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
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
			'section_style',
			[
				'label' => __( 'Item', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'item_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category-grid > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__('Item Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .category-grid > a',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'General Title', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => ''
				]
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
				'condition' => [
					'content_position!' => 'inside'
				]
			]
		);
		
		$this->add_control(
			'image_spacing',
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
					'{{WRAPPER}} .content-under img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .content-under > .categories-mask' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
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
				'selectors' => [
					'{{WRAPPER}} .categories-mask > *:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .categories-mask' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .categories-mask' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'content_offsets',
			[
				'label'		 =>	esc_html__( 'Content Offsets', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 50,
						'step' 	=> 1
					],
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'em' 		=> [
						'min' 	=> 0,
						'max' 	=> 20,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .categories-mask' => 'left: {{SIZE}}{{UNIT}} !important; right: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .valign-bottom .categories-mask' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .valign-top .categories-mask' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_position' => 'inside',
					'stretch_images!' => 'yes',
				]
			]
		);
		
		$this->start_controls_tabs('content_style_tabs');
		
		$this->start_controls_tab( 'content_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__('Text Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .categories-mask' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .categories-mask'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_shadow',
				'selector' => '{{WRAPPER}} .categories-mask',
				'separator' => 'before'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .categories-mask'
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'content_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'content_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .categories-mask:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_hover',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .categories-mask:hover'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_shadow_hover',
				'selector' => '{{WRAPPER}} .categories-mask:hover',
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'content_border_hover',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .categories-mask:hover',
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'products_style_section',
			[
				'label' => esc_html__( 'Products', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['style_type' => array('style-1', 'style-3')],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'products_typography',
				'selector' => '{{WRAPPER}} .categories-mask .count, {{WRAPPER}} .categories-mask sup',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'categories_style_section',
			[
				'label' => esc_html__( 'Categories', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'categories_typography',
				'selector' => '{{WRAPPER}} .categories-mask h4',
			]
		);
		
		$this->end_controls_section();

	}

	/**
	 * Render Categories widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		if ( !class_exists('WooCommerce') ) {
			echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
			     esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core') .
			     '</div>';
			return;
		}
		
		$settings = $this->get_settings_for_display();
		
		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}	

			if ( $setting ) {

				switch ($key) {
					case 'ids':
					case 'parent':
						$atts[$key] = !empty( $setting ) ? implode( ',',$setting ) : '';
					break;
					case 'sorting':
						if ( is_array( $setting ) ) {
							$atts[$key] = !empty( $setting ) ? implode( ',',$setting ) : '';
						}
						else {
							$atts[$key] = $setting;
						}
					break;
					case 'slides':
						$atts['large'] = $atts['notebook'] = !empty($setting) ? $setting : 4;
						break;
					case 'slides_tablet':
						$atts['tablet_land'] = $atts['tablet_portrait'] = !empty($setting) ? $setting : 2;
						break;
					case 'slides_mobile':
						$atts['mobile'] = !empty($setting) ? $setting : 1;
						break;
					case 'slider_spacing':
						$atts['slider_spacing'] = $setting['size'];
						break;
					case 'content_position':
						$atts['content_position'] = $settings['stretch_images'] == 'yes'  ? 'inside' : $setting;
						break;
					default:
						$atts[$key] = $setting;
						break;
				}

			}
			
			if ( $key == 'hide_empty' ) {
				$atts[ $key ] = $setting == 'yes' ? $setting : false;
			}
			
		}
		
		$atts['sorting'] = array('name');
		switch ($settings['style_type']) {
			case 'style-1':
				$atts['sorting'] = array('name', 'products');
			break;
			case 'style-2':
				$atts['sorting'] = array('name');
				$atts['count_label'] = true;
			break;
			case 'style-3':
				$atts['sorting'] = array('products', 'name');
			break;
			default;
			break;
		}


		$atts['text_color'] = 'dark';
		$atts['style'] = 'classic';
		$atts['is_preview'] = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$atts['is_elementor'] = true;
		
		// compatibility with old versions
		$atts['content_type_new'] = isset($atts['image_circle']) && !!$atts['image_circle'];
		
		$Categories_Shortcodes = Categories_Shortcodes::get_instance();
		echo $Categories_Shortcodes->categories_shortcode( $atts );

	}
	
	public function on_import( $element ) {
		$element['settings']['ids'] = [];
		$element['settings']['exclude'] = [];
		$element['settings']['parent'] = [];
		return $element;
	}

}
