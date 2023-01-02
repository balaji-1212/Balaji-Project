<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Products as Products_Shortcode;

/**
 * Products widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Menu_Layout extends \Elementor\Widget_Base {
	
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
		return 'etheme_product_menu_layout';
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
		return __( 'Price List', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-price-list';
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
		return [ 'products', 'categories', 'menu', 'layout', 'woocommerce', 'query' ];
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
	 * Get widget dependency.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-woocommerce', 'etheme-woocommerce-archive', 'etheme-product-view-menu' ];
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
	 * Register Products widget controls.
	 *
	 * @since 2.1.3
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
			'columns',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'1'	=>	esc_html__('1', 'xstore-core'),
					'2'	=>	esc_html__('2', 'xstore-core'),
					'3'	=>	esc_html__('3', 'xstore-core'),
					'4'	=>	esc_html__('4', 'xstore-core'),
					'5'	=>	esc_html__('5', 'xstore-core'),
					'6'	=>	esc_html__('6', 'xstore-core'),
				],
				'default'		=> '1'
			]
		);
		
		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'products'	=>	esc_html__('Products', 'xstore-core'),
					'custom'	=>	esc_html__('Custom', 'xstore-core'),
				],
				'default'		=> 'products'
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' 		=> __( 'Widget Title', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'default' 		=> '',
			]
		);
		
		$this->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Show Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'image_position',
			[
				'label' => __( 'Image Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'condition'		=> ['show_image' => 'yes']
			]
		);
		
		$this->add_control(
			'show_category',
			[
				'label' => esc_html__( 'Show Categories', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Show Short Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'excerpt_length',
			[
				'label' 		=> __( 'Short Description Length (symbols)', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> __( 'Controls the number of words in the product excerpt.', 'xstore-core' ),
				'default' 		=> '',
				'condition'		=> ['show_excerpt' => 'yes']
			]
		);
		
		$this->end_controls_section();
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'name', [
				'label' => esc_html__('Name', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Name', 'xstore-core'),
			]
		);
		
		$repeater->add_control(
			'categories', [
				'label' => esc_html__('Categories', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Category 01', 'xstore-core'),
			]
		);
		
		$repeater->add_control(
			'excerpt',
			[
				'label' => __( 'Excerpt', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Some promo words', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'price', [
				'label' => esc_html__('Price', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('20$', 'xstore-core'),
			]
		);
		
		$repeater->add_control(
			'image', [
				'label' => esc_html__('Image', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->start_controls_section(
			'et_section_custom_content_settings',
			[
				'label' => esc_html__('Items', 'xstore-core'),
				'condition'		=> ['type' => 'custom']
			]
		);
		
		$this->add_control(
			'custom_content_tab',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'name' => esc_html__('Caipirinha', 'xstore-core'),
						'excerpt' => esc_html('Egestas / Nullam / Egestas', 'xstore-core'),
						'country' => 'Nauru',
						'price' => '21$',
						'categories' => esc_html__('Chef’s Special', 'xstore-core')
					],
					[
						'name' => esc_html__('Guacamole', 'xstore-core'),
						'excerpt' => esc_html('Teger / Amet / Volutpa', 'xstore-core'),
						'price' => '9$',
					],
					[
						'name' => esc_html__('Caprese', 'xstore-core'),
						'excerpt' => esc_html('Teger / Amet / Volutpa', 'xstore-core'),
						'price' => '17$',
						'categories' => esc_html__('Chef’s Special', 'xstore-core')
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'product_data_section',
			[
				'label' => __( 'Query', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
				'condition'		=> ['type' => 'products']
			]
		);
		
		$this->add_control(
			'products',
			[
				'label' 		=>	__( 'Products Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''					=>	esc_html__('All', 'xstore-core'),
					'featured'			=>	esc_html__('Featured', 'xstore-core'),
					'sale'				=>	esc_html__('Sale', 'xstore-core'),
					'recently_viewed'	=>	esc_html__('Recently viewed', 'xstore-core'),
					'bestsellings'		=>	esc_html__('Bestsellings', 'xstore-core'),
				],
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label' 		=>	__( 'Order By', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description'  => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s. Default by Date', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options' 		=>	[
					'date'			=>	esc_html__( 'Date', 'xstore-core' ),
					'ID'			=>	esc_html__( 'ID', 'xstore-core' ),
					'post__in'		=>	esc_html__( 'As IDs provided order', 'xstore-core' ),
					'author'		=>	esc_html__( 'Author', 'xstore-core' ),
					'title'			=>	esc_html__( 'Title', 'xstore-core' ),
					'modified'		=>	esc_html__( 'Modified', 'xstore-core' ),
					'rand'			=>	esc_html__( 'Random', 'xstore-core' ),
					'comment_count'	=>	esc_html__( 'Comment count', 'xstore-core' ),
					'menu_order'	=>	esc_html__( 'Menu order', 'xstore-core' ),
					'price'			=>	esc_html__( 'Price', 'xstore-core' ),
				],
				'default'		=> 'date'
			]
		);
		
		$this->add_control(
			'order',
			[
				'label' 		=>	__( 'Order Way', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description'   => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s. Default by ASC', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options' 		=>	[
					'ASC'		=>	esc_html__( 'Ascending', 'xstore-core' ),
					'DESC'		=>	esc_html__( 'Descending', 'xstore-core' ),
				],
				'default'		=> 'ASC'
			]
		);
		
		$this->add_control(
			'hide_out_stock',
			[
				'label' 		=>	__( 'Hide Out Of Stock', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);
		
		$this->add_control(
			'ids',
			[
				'label' 		=> __( 'Products', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> esc_html__('Enter List of Products', 'xstore-core'),
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
			]
		);
		
		$this->add_control(
			'taxonomies',
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
			'limit',
			[
				'label' 		=>	__( 'Limit', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description'   =>  esc_html__( 'Use "-1" to show all products.', 'xstore-core' ),
				'default' 		=>	'5',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'global_title_style_section',
			[
				'label' => __( 'Widget Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'global_title_typography',
				'label'       	=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .products-title',
				'separator'   	=> 'before',
			]
		);
		
		$this->add_control(
			'global_title_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .products-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'global_title_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products-title' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'global_title_align',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
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
				'default' => 'left',
				'selectors'    => [
					'{{WRAPPER}} .products-title' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'global_title_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'global_title_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products-title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'title_typography',
				'label'       	=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .product-title a, {{WRAPPER}} .product-title',
				'separator'   	=> 'before',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .product-title a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'category_style_section',
			[
				'label' => __( 'Category', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'category_typography',
				'label'       	=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .category a',
				'separator'   	=> 'before',
			]
		);
		
		$this->add_control(
			'category_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .category a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'category_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'category_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'separator_style_section',
			[
				'label' => __( 'Separator', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'separator_position',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 20,
						'min' => -20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product-main-details .separator' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'separator_border_style',
			[
				'label' => __( 'Border Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'xstore-core' ),
					'solid' => __( 'Solid', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'dotted' => __( 'Dotted', 'xstore-core' ),
					'dashed' => __( 'Dashed', 'xstore-core' ),
					'groove' => __( 'Groove', 'xstore-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .product-main-details .separator' => 'border-bottom-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'separator_border_width',
			[
				'label' => __( 'Border Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 12,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product-main-details .separator' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'separator_border_color',
			[
				'label' 	=> __( 'Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .product-main-details .separator' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'price_style_section',
			[
				'label' => __( 'Price', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'price_typography',
				'label'       	=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .price',
				'separator'   	=> 'before',
			]
		);
		
		$this->start_controls_tabs( 'price_colors' );
		
		$this->start_controls_tab( 'price_colors_normal',
			[
				'label'      => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'price_colors_sale',
			[
				'label'      => __( 'Sale', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'price_sale_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .price ins .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'price_margin',
			[
				'label' => __( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$this->add_control(
			'product_img_size',
			[
				'label' 	=>	__( 'Size', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
			]
		);
		
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
					'{{WRAPPER}} .product-content-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'image_margin',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
						'min' => 0,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .product-content-image:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product-content-image:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .product-content-image img' => 'opacity: {{SIZE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .product-content-image img',
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
					'{{WRAPPER}} .content-product:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .content-product:hover img',
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
					'{{WRAPPER}} .product-content-image img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .product-content-image img',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-content-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'excerpt_style_section',
			[
				'label' => __( 'Excerpt', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition'		=> ['show_excerpt' => 'yes']
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'excerpt_typography',
				'label'       	=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .product-excerpt',
				'separator'   	=> 'before',
			]
		);
		
		$this->add_control(
			'excerpt_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .product-excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label' => __( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .content-product .product-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'element_style_section',
			[
				'label' => __( 'Element', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'element_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .content-product' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'element_border',
				'selector' => '{{WRAPPER}} .content-product',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'element_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .content-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'element_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .content-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'element_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .content-product' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
			]
		);
		
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render Products widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$atts = array();
		
		foreach ( $settings as $key => $setting ) {
			
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}
			
			$atts[$key] = $setting;
			
		}
		
		$atts['type'] = 'menu';
		
		$atts['is_preview'] = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		if ( $settings['type'] == 'products' ) {
			
			$Products_Shortcode = Products_Shortcode::get_instance();
			echo $Products_Shortcode->products_shortcode( $atts, '' );
			
		}
		else {
			if ( $settings['custom_content_tab'] ) { ?>
                <div class="etheme_products products-layout-menu">
                    <div class="row products-loop products-grid with-ajax row-count-<?php echo $settings['columns']; ?>">
						<?php foreach ( $settings['custom_content_tab'] as $item ) {
							$local_options = array();
							$local_options['classes'] = array(
								'product',
								'product-view-menu',
								function_exists('etheme_get_product_class') ? etheme_get_product_class( $settings['columns'] ) : ''
							);
							$local_options['thumb_id'] = $item['image']['id'];
							
							if ( ($settings['excerpt_length'] > 0 && strlen($item['excerpt']) > 0) && ( strlen($item['excerpt']) > $settings['excerpt_length'] ) ) {
								$item['excerpt']         = substr($item['excerpt'],0,$settings['excerpt_length']) . '...';
							}
							
							?>

                            <div class="<?php echo implode(' ', $local_options['classes']); ?>">
                                <div class="content-product">
									<?php
									if ( $settings['show_image']) :
										$settings['product_img_size'] = empty($settings['product_img_size']) ? '120x120' : $settings['product_img_size'];
									endif;
									
									if ( $settings['show_image'] && $settings['image_position'] != 'right' ) : ?>

                                        <a class="product-content-image">
											<?php
											if ( $local_options['thumb_id'] ) {
												$local_options['img'] = function_exists('etheme_get_image') ?
													etheme_get_image( $local_options['thumb_id'], $settings['product_img_size'] ) : '';
												if ( ! empty( $local_options['img'] ) ) {
													echo $local_options['img'];
												} elseif ( function_exists( 'wc_placeholder_img' ) ) {
													echo wc_placeholder_img();
												}
											}
                                            elseif ( function_exists('wc_placeholder_img') ) {
												echo wc_placeholder_img();
											}
											?>
                                        </a>
									
									<?php endif; ?>

                                    <div class="product-details">
										
										<?php if ( $settings['show_category'] && $item['categories'] ) : ?>
                                            <span class="category">
												<?php
												$local_options['categories'] = explode(',', $item['categories']);
												foreach ( $local_options['categories'] as $category ) { ?>
                                                    <a><?php echo $category; ?></a>
												<?php }
												?>
											</span>
										<?php endif; ?>

                                        <div class="product-main-details">

                                            <p class="product-title">
                                                <a><?php echo wp_specialchars_decode($item['name']); ?></a>
                                            </p>

                                            <span class="separator"></span>
											
											<?php echo '<span class="price">' . $item['price'] . '</span>'; ?>

                                        </div>
										
										<?php if ( $settings['show_excerpt']) : ?>

                                            <div class="product-info-details">

                                                <div class="product-excerpt">
													<?php echo do_shortcode($item['excerpt']); ?>
                                                </div>

                                                <span style="visibility: hidden">
				                                    <?php echo '<span class="price">' . $item['price'] . '</span>'; ?>
				                                </span>

                                            </div>
										
										<?php endif; ?>

                                    </div>
									
									<?php if ( $settings['show_image'] && $settings['image_position'] == 'right' ) : ?>

                                        <a class="product-content-image">
											<?php
											if ( $local_options['thumb_id'] ) {
												$local_options['img'] = function_exists('etheme_get_image') ?
													etheme_get_image( $local_options['thumb_id'], $settings['product_img_size'] ) : '';
												if (!empty($local_options['img']))
													echo $local_options['img'];
                                                elseif (function_exists('wc_placeholder_img'))
													echo wc_placeholder_img();
											}
                                            elseif ( function_exists('wc_placeholder_img') )
												echo wc_placeholder_img();
											?>
                                        </a>
									
									<?php endif; ?>
                                </div>
                            </div>
							<?php
						} ?>
                    </div>
                </div>
			<?php }
			
			if ( $atts['is_preview'] ) {
				echo '<script>jQuery(document).ready(function(){
                        etTheme.swiperFunc();
                        etTheme.secondInitSwipers();
                        etTheme.global_image_lazy();
                        if ( etTheme.contentProdImages !== undefined )
                            etTheme.contentProdImages();
                        if ( window.hoverSlider !== undefined ) { 
                            window.hoverSlider.init({});
                            window.hoverSlider.prepareMarkup();
                        }
                        if ( etTheme.countdown !== undefined )
                            etTheme.countdown();
                        etTheme.customCss();
                        etTheme.customCssOne();
                        if ( etTheme.reinitSwatches !== undefined )
                            etTheme.reinitSwatches();
                    });</script>';
			}
		}
	}
	
}
