<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Product filters widget.
 *
 * @since      4.0.8
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */

class Product_Filters extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_product_filters';
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
		return __( 'Product Filters', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-product-filter';
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
		return [ 'product', 'filter', 'attributes', 'categories', 'price', 'select', 'woocommerce' ];
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
		return [ 'eight_theme_general' ];
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
		return [ 'etheme_product_filters' ];
	}
	
	public function get_style_depends() {
		return [ 'etheme-elementor-product-filters' ];
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
	 * Register the widget controls.
	 *
	 * @since 4.0.8
	 * @access protected
	 */
	protected function register_controls() {
	    if ( !class_exists('WooCommerce') ) {
		    $this->start_controls_section(
			    'section_general',
			    [
				    'label' => esc_html__( 'General', 'xstore-core' ),
			    ]
		    );
		    $this->add_control(
			    'required_plugin_info',
			    [
				    'type'            => \Elementor\Controls_Manager::RAW_HTML,
				    'raw' => sprintf( __( 'Please, install <a href="%s" target="_blank">WooCommerce</a> plugin to use this widget', 'xstore-core' ), admin_url('plugin-install.php?s=woocommerce&tab=search&type=term') ),
				    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			    ]
		    );
		    $this->end_controls_section();
        }
	    else {
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
				    'default' => 'separated',
			    ]
		    );
		
		    $repeater = new \Elementor\Repeater();
		
		    $filtered_taxonomies = $this->product_taxonomies_to_filter();
		
		    $repeater_options = array(
			    'attributes' => esc_html__( 'Attributes', 'xstore-core' ),
			    'price'      => esc_html__( 'Price', 'xstore-core' ),
			    'rating'     => esc_html__( 'Rating', 'xstore-core' ),
			    'orderby'    => esc_html__( 'Order By', 'xstore-core' ),
		    );
		
		    $repeater_options = $filtered_taxonomies + $repeater_options;
		
		    $repeater->add_control(
			    'filter_title',
			    [
				    'label'     => esc_html__( 'Title', 'xstore-core' ),
				    'type'      => \Elementor\Controls_Manager::TEXT,
				    'default'   => esc_html__('Filter','xstore-core')
			    ]
		    );
		
		    $repeater->add_control(
			    'filter_type',
			    [
				    'label'   => esc_html__( 'Filter type', 'xstore-core' ),
				    'type'    => \Elementor\Controls_Manager::SELECT,
				    'options' => $repeater_options,
				    'default' => array_key_exists('product_cat', $repeater_options) ? 'product_cat' : $repeater_options[array_key_first($repeater_options)],
			    ]
		    );
		
		    foreach ($filtered_taxonomies as $taxonomy_key => $taxonomy_title) {
			    $taxonomy = get_taxonomy($taxonomy_key);
			
			    if ( $taxonomy->hierarchical ) {
				    $repeater->add_control(
					    $taxonomy_key . '_hierarchical',
					    [
						    'label'     => esc_html__( 'Show Hierarchy', 'xstore-core' ),
						    'type'      => \Elementor\Controls_Manager::SWITCHER,
						    'condition' => [
							    'filter_type' => $taxonomy_key,
						    ],
					    ]
				    );
			    }
			
			    $repeater->add_control(
				    $taxonomy_key.'_hide_empty',
				    [
					    'label'        => esc_html__( 'Hide Empty', 'xstore-core' ),
					    'type'         => \Elementor\Controls_Manager::SWITCHER,
					    'condition'    => [
						    'filter_type' => $taxonomy_key,
					    ],
				    ]
			    );
			
			    $repeater->add_control(
				    $taxonomy_key.'_show_count',
				    [
					    'label'        => esc_html__( 'Show Count', 'xstore-core' ),
					    'type'         => \Elementor\Controls_Manager::SWITCHER,
					    'condition'    => [
						    'filter_type' => $taxonomy_key,
					    ],
				    ]
			    );
		    }
		
		    $repeater->add_control(
			    'attribute',
			    [
				    'label'     => esc_html__( 'Attribute', 'xstore-core' ),
				    'type'      => \Elementor\Controls_Manager::SELECT,
				    'options'   => $this->get_attributes(),
				    'default'   => '',
				    'condition' => [
					    'filter_type' => 'attributes',
				    ],
			    ]
		    );
		
		    $repeater->add_control(
			    'attribute_query_type',
			    [
				    'label'     => esc_html__( 'Query Type', 'xstore-core' ),
				    'type'      => \Elementor\Controls_Manager::SELECT,
				    'default'   => 'and',
				    'options'   => array(
					    'or'  => esc_html__( 'OR', 'xstore-core' ),
					    'and' => esc_html__( 'AND', 'xstore-core' ),
				    ),
				    'condition' => [
					    'filter_type' => 'attributes',
				    ],
			    ]
		    );
		
		    $this->end_controls_section();
		
		    $this->start_controls_section(
			    'section_items',
			    [
				    'label' => esc_html__( 'Items', 'xstore-core' ),
			    ]
		    );
		
		    //	Repeater
		    $this->add_control(
			    'items',
			    [
				    'type'        => \Elementor\Controls_Manager::REPEATER,
				    'title_field' => '{{{ filter_title }}}',
				    'fields'      => $repeater->get_controls(),
				    'default'     => [
					    [
						    'filter_type' => 'product_cat',
						    'filter_title' => esc_html__('Categories', 'xstore-core')
					    ],
					    [
						    'filter_type' => 'rating',
						    'filter_title' => esc_html__('Rating filter', 'xstore-core')
					    ],
					    [
						    'filter_type' => 'price',
						    'filter_title' => esc_html__('Price filter', 'xstore-core')
					    ],
				    ],
			    ]
		    );
		
		    $this->end_controls_section();
		
		    $this->start_controls_section(
			    'button_section',
			    [
				    'label' => __( 'Button', 'xstore-core' ),
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
				    'label' => esc_html__( 'Icon Position', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::SELECT,
				    'default' => 'left',
				    'options' => [
					    'left' => esc_html__( 'Before', 'xstore-core' ),
					    'right' => esc_html__( 'After', 'xstore-core' ),
				    ],
				    'condition' => [
					    'button_selected_icon[value]!' => '',
					    'button_text!' => '',
				    ],
			    ]
		    );
		
		    $this->add_control(
			    'button_icon_indent',
			    [
				    'label' => esc_html__( 'Icon Spacing', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::SLIDER,
				    'range' => [
					    'px' => [
						    'max' => 50,
					    ],
				    ],
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				    ],
				    'condition' => [
					    'button_text!' => '',
					    'button_selected_icon[value]!' => '',
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
		
		    $this->add_control(
			    'cols_gap',
			    [
				    'label' => __( 'Spacing To Button', 'xstore-core' ),
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
					    '{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
				    ],
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Background::get_type(),
			    [
				    'name'     => 'items_wrapper_background',
				    'types'    => [ 'classic', 'gradient' ],
				    'selector' => '{{WRAPPER}} .etheme-product-filters-items',
				    'condition' => [
					    'type' => 'inline'
				    ]
			    ]
		    );
		
		    $this->add_control(
			    'separator_items_wrapper_background_style',
			    [
				    'type' => \Elementor\Controls_Manager::DIVIDER,
				    'condition' => [
					    'type' => 'inline'
				    ]
			    ]
		    );
		
		    $this->add_control(
			    'items_wrapper_border_radius',
			    [
				    'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%' ],
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    ],
				    'condition' => [
					    'type' => 'inline'
				    ],
				    'default' => [
					    'top' => 30,
					    'left' => 30,
					    'right' => 30,
					    'bottom' => 30,
					    'unit' => 'px'
				    ]
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Border::get_type(),
			    [
				    'name'      => 'items_wrapper_border',
				    'label'     => esc_html__( 'Border', 'xstore-core' ),
				    'selector'  => '{{WRAPPER}} .etheme-product-filters-items',
				    'condition' => [
					    'type' => 'inline'
				    ],
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
			    'delimiter_style_heading',
			    [
				    'label' => __( 'Delimiter', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::HEADING,
				    'separator' => 'before',
				    'condition' => [
					    'type' => 'inline'
				    ]
			    ]
		    );
		
		    $this->add_control(
			    'delimiter_width',
			    [
				    'label' => __( 'Width', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::SLIDER,
				    'size_units' => [ 'px' ],
				    'range' => [
					    'px' => [
						    'min' => 0,
						    'max' => 5,
						    'step' => 1,
					    ],
				    ],
				    'selectors' => [
					    '{{WRAPPER}}' => '--delimiter-width: {{SIZE}}{{UNIT}};',
				    ],
				    'condition' => [
					    'type' => 'inline'
				    ]
			    ]
		    );
		
		    $this->add_control(
			    'delimiter_style',
			    [
				    'label' => __( 'Style', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::SELECT,
				    'options' => [
					    'solid' => __( 'Solid', 'xstore-core' ),
					    'double' => __( 'Double', 'xstore-core' ),
					    'dotted' => __( 'Dotted', 'xstore-core' ),
					    'dashed' => __( 'Dashed', 'xstore-core' ),
				    ],
				    'selectors' => [
					    '{{WRAPPER}}' => '--delimiter-style: {{VALUE}};',
				    ],
				    'condition' => [
					    'type' => 'inline'
				    ]
			    ]
		    );
		
		    $this->add_control(
			    'delimiter_color',
			    [
				    'label' => __( 'Color', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::COLOR,
				    'selectors' => [
					    '{{WRAPPER}}' => '--delimiter-color: {{VALUE}}',
				    ],
				    'condition' => [
					    'type' => 'inline'
				    ]
			    ]
		    );
		
		    $this->end_controls_section();
		
		    $this->start_controls_section(
			    'item_style_section',
			    [
				    'label' => esc_html__( 'Items', 'xstore-core' ),
				    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			    ]
		    );
		
		    $this->add_control(
			    'item_spacing',
			    [
				    'label' => __( 'Spacing', 'xstore-core' ),
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
					    '{{WRAPPER}}' => '--inner-cols-gap: {{SIZE}}{{UNIT}};',
				    ],
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Typography::get_type(),
			    [
				    'name' => 'item_typography',
				    'selector' => '{{WRAPPER}} .etheme-product-filters-item-title',
			    ]
		    );
		
		    $this->start_controls_tabs( 'item_tabs' );
		
		    $this->start_controls_tab(
			    'item_tab_normal',
			    [
				    'label' => __( 'Normal', 'xstore-core' ),
			    ]
		    );
		
		    $this->add_control(
			    'item_color',
			    [
				    'label' => esc_html__( 'Text Color', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::COLOR,
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-item-title' => 'color: {{VALUE}};',
				    ],
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Background::get_type(),
			    [
				    'name' => 'item_background',
				    'label' => esc_html__( 'Background', 'xstore-core' ),
				    'types' => [ 'classic', 'gradient' ],
				    'exclude' => [ 'image' ],
				    'selector' => '{{WRAPPER}} .etheme-product-filters-item-title',
			    ]
		    );
		
		    $this->end_controls_tab();
		
		    $this->start_controls_tab(
			    'item_tab_active',
			    [
				    'label' => __( 'Active', 'xstore-core' ),
			    ]
		    );
		
		    $this->add_control(
			    'item_color_active',
			    [
				    'label' => esc_html__( 'Text Color', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::COLOR,
				    'selectors' => [
					    '{{WRAPPER}} .opened .etheme-product-filters-item-title' => 'color: {{VALUE}};',
				    ],
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Background::get_type(),
			    [
				    'name' => 'item_background_active',
				    'label' => esc_html__( 'Background', 'xstore-core' ),
				    'types' => [ 'classic', 'gradient' ],
				    'exclude' => [ 'image' ],
				    'selector' => '{{WRAPPER}} .opened .etheme-product-filters-item-title',
			    ]
		    );
		
		    $this->add_control(
			    'item_border_color_active',
			    [
				    'label' => esc_html__( 'Border Color', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::COLOR,
				    'condition' => [
					    'type!' => 'inline',
					    'item_border_border!' => '',
				    ],
				    'selectors' => [
					    '{{WRAPPER}} .opened .etheme-product-filters-item-title' => 'border-color: {{VALUE}};',
				    ],
			    ]
		    );
		
		    $this->end_controls_tab();
		
		    $this->end_controls_tabs();
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Border::get_type(),
			    [
				    'name' => 'item_border',
				    'selector' => '{{WRAPPER}} .etheme-product-filters-item-title',
				    'separator' => 'before',
				    'condition' => [
					    'type!' => 'inline',
				    ],
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
			    'item_padding',
			    [
				    'label' => esc_html__( 'Padding', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%', 'em' ],
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					    '{{WRAPPER}} .etheme-product-filters-item-title:not(:last-child):after' => 'top: {{TOP}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}};',
				    ],
			    ]
		    );
		
		    $this->add_control(
			    'item_border_radius',
			    [
				    'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%' ],
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-item-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    ],
				    'condition' => [
					    'type!' => 'inline'
				    ],
				    'default' => [
					    'top' => 3,
					    'left' => 3,
					    'right' => 3,
					    'bottom' => 3,
					    'unit' => 'px'
				    ]
			    ]
		    );
		
		    $this->end_controls_section();
		
		    $this->start_controls_section(
			    'item_content_style_section',
			    [
				    'label' => esc_html__( 'Item Content', 'xstore-core' ),
				    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			    ]
		    );
		
		    $this->add_control(
			    'item_content_color',
			    [
				    'label' => esc_html__( 'Text Color', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::COLOR,
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-item-content' => 'color: {{VALUE}};',
				    ],
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Background::get_type(),
			    [
				    'name' => 'item_content_background',
				    'label' => esc_html__( 'Background', 'xstore-core' ),
				    'types' => [ 'classic', 'gradient' ],
				    'exclude' => [ 'image' ],
				    'selector' => '{{WRAPPER}} .etheme-product-filters-item-content',
			    ]
		    );
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Border::get_type(),
			    [
				    'name' => 'item_content_border',
				    'selector' => '{{WRAPPER}} .etheme-product-filters-item-content',
				    'separator' => 'before',
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
				    'selector' => '{{WRAPPER}} .etheme-product-filters-button .elementor-button',
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
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
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
				    'selector' => '{{WRAPPER}} .etheme-product-filters-button .elementor-button',
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
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-button:hover, {{WRAPPER}} .etheme-product-filters-button .elementor-button:focus' => 'color: {{VALUE}};',
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-button:hover svg, {{WRAPPER}} .etheme-product-filters-button .elementor-button:focus svg' => 'fill: {{VALUE}};',
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
				    'selector' => '{{WRAPPER}} .etheme-product-filters-button .elementor-button:hover, {{WRAPPER}} .etheme-product-filters-button .elementor-button:focus',
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
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-button:hover, {{WRAPPER}} .etheme-product-filters-button .elementor-button:focus' => 'border-color: {{VALUE}};',
				    ],
			    ]
		    );
		
		    $this->end_controls_tab();
		
		    $this->end_controls_tabs();
		
		    $this->add_group_control(
			    \Elementor\Group_Control_Border::get_type(),
			    [
				    'name' => 'button_border',
				    'selector' => '{{WRAPPER}} .etheme-product-filters-button .elementor-button',
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
		
		    $this->add_control(
			    'button_border_radius',
			    [
				    'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%', 'em' ],
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    ],
			    ]
		    );
		
		    $this->add_responsive_control(
			    'button_padding',
			    [
				    'label' => esc_html__( 'Padding', 'xstore-core' ),
				    'type' => \Elementor\Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', 'em', '%' ],
				    'selectors' => [
					    '{{WRAPPER}} .etheme-product-filters-button .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    ],
				    'separator' => 'before',
			    ]
		    );
		
		    $this->end_controls_section();
        }
	}
	
	/**
	 * Render product filters widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.8
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
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$migrated = isset( $settings['__fa4_migrated']['button_selected_icon'] );
		$is_new = empty( $settings['button_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		
		global $wp;
		
		$form_action = wc_get_page_permalink( 'shop' );
		
		if ( get_query_var('et_is-woocommerce-archive', false) && apply_filters( 'xstore_filters_form_action_without_cat_widget', false ) ) {
			if ( '' === get_option( 'permalink_structure' ) ) {
				$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			} else {
				$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
			}
		}
		
		$this->add_render_attribute(
			[
				'wrapper' => [
					'class'  => [
						'etheme-product-filters',
					],
					'action' => [
						$form_action,
					],
					'data-origin-action' => [
						$form_action,
					],
					'method' => [
						'GET',
					],
                    'data-type' => [
                        $settings['type']
                    ]
				],
			]
		);
		
		if ( get_query_var('et_is-woocommerce-archive', false) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'with-ajax' );
		}
		
		?>
		<form <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="etheme-product-filters-items">
                <?php
                    foreach ( $settings['items'] as $index => $item ) :
                        
                        switch ($item['filter_type']) {
                            case 'attributes':
                                $this->attributes_filter_template( $item );
                                break;
                            case 'price':
                                $this->price_filter_template( $item );
                                break;
                            case 'orderby':
                                $this->orderby_filter_template( $item );
                                break;
                            case 'rating':
                                $this->rating_filter_template( $item, $form_action );
                                break;
                            default:
                                if ( in_array($item['filter_type'], array_keys($this->product_taxonomies_to_filter())) ) {
                                    $this->taxonomy_filter_template( $item );
                                }
                                break;
                        }
                    
                    endforeach;
                ?>
            </div>
            
            <?php

            $this->add_render_attribute( [
                'button-wrapper' => [
                    'class' => [
                        'etheme-product-filters-button',
                    ]
                ],
	            'button' => [
		            'class' => [
                        'elementor-button'
                    ]
	            ],
	            'button-icon-align' => [
		            'class' => [
			            'elementor-button-icon',
			            'elementor-align-icon-' . $settings['button_icon_align'],
		            ],
	            ],
                'content-wrapper' => [
                    'class' => 'elementor-button-content-wrapper',
                ],
	            'text' => [
		            'class' => 'elementor-button-text',
	            ],
            ] );
            // $this->add_render_attribute( 'button', 'class', 'elementor-button' );
            $this->add_render_attribute( 'button', 'role', 'button' );
            $this->add_render_attribute( 'button', 'type', 'submit' );
            ?>
        
            <div <?php $this->print_render_attribute_string( 'button-wrapper' ); ?>>
                <button <?php $this->print_render_attribute_string( 'button' ); ?>>
                    <span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
                        <?php if ( ! empty( $settings['button_icon'] ) || ! empty( $settings['button_selected_icon']['value'] ) ) : ?>
                            <span <?php $this->print_render_attribute_string( 'button-icon-align' ); ?>>
                            <?php if ( $is_new || $migrated ) :
                                \Elementor\Icons_Manager::render_icon( $settings['button_selected_icon'], [ 'aria-hidden' => 'true' ] );
                            else : ?>
                                <i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                        </span>
                        <?php endif; ?>
                        <span <?php $this->print_render_attribute_string( 'text' ); ?>>
                            <?php echo $settings['button_text'] ?? esc_html__( 'Search', 'xstore-core' ); ?>
                        </span>
                    </span>
                </button>
            </div>
            
		</form>
		<?php
		
	}
	
	/**
	 * Render Rating filter item.
	 *
	 * @param $settings
	 * @param $link
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function rating_filter_template($settings, $link) {
		
		$rating_filter = array();
		$html = '';
		for ( $rating = 5; $rating >= 1; $rating-- ) {
		 
			$link_ratings = implode( ',', array_merge( $rating_filter, array( $rating ) ) );
			$class       = in_array( $rating, $rating_filter, true ) ? 'wc-layered-nav-rating chosen' : 'wc-layered-nav-rating';
			$link        = apply_filters( 'woocommerce_rating_filter_link', $link_ratings ? add_query_arg( 'rating_filter', $link_ratings, $link ) : remove_query_arg( 'rating_filter' ) );
			$rating_html = wc_get_star_rating_html( $rating );
			$count_html  = '';
			
			$html .= sprintf( '<li class="%s"><a class="filter-item" href="%s" data-value="%s"><span class="star-rating">%s</span> %s</a></li>', esc_attr( $class ), esc_url( $link ), $rating, $rating_html, $count_html ); // WPCS: XSS ok.
		}
		
	    $this->render_item_content(
            $settings,
            $html,
            array(
                'name' => 'rating_filter',
            )
        );
		
	}
	
	/**
	 * Render Price filter item.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function price_filter_template( $settings ) {
		
		$this->get_price_scripts();
		
		$prices = $this->get_filtered_price();
		
		$step = max( apply_filters( 'woocommerce_price_filter_widget_step', 10 ), 1 );
		
		$min = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $prices->min_price ) );
		$max = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $prices->max_price ) );
		
		if ( $min === $max ) {
			return;
		}
		
		if ( ( get_query_var('et_is-woocommerce-archive', false) ) && ! WC()->query->get_main_query()->post_count ) {
			return;
		}
		
		$min_price = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : $min;
		$max_price = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : $max;
		
        $html = '<div class="price_slider_wrapper">';
    
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                    $html .= __('Price filter is not available in edit mode', 'xstore-core');
                    }
            else {
                $html .=
                    '<div class="etheme_price_slider" style="display:none;"></div>'.
                    '<div class="price_slider_amount" data-step="'. esc_attr( $step ).'">'.
                        '<input type="text" id="min_price" name="min_price" value="'. esc_attr( $min_price ) . '" data-min="' . esc_attr( $min ) . '" placeholder="' . esc_attr__( 'Min price', 'xstore-core' ) . '" />'.
                        '<input type="text" id="max_price" name="max_price" value="'. esc_attr( $max_price ) .'" data-max="' . esc_attr( $max ) . '" placeholder="' . esc_attr__( 'Max price', 'xstore-core' ) . '" />'.
                        '<div class="price_label" style="display:none;">' .
                            esc_html__( 'Price:', 'xstore-core' ) . '<span class="from"></span> &mdash; <span class="to"></span>' .
                         '</div>' .
                         wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ).
                        '<div class="clear"></div>'.
                      '</div>';
            }
        
        $html .= '</div>';
		
		$this->render_item_content($settings, $html, array('html_tag' => 'div', 'result_input' => false));
	}
	
	/**
	 * Render Order By filter item.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function orderby_filter_template($settings) {
		$options = apply_filters(
			'woocommerce_catalog_orderby',
			array(
				'menu_order' => __( 'Default sorting', 'xstore-core' ),
				'popularity' => __( 'Sort by popularity', 'xstore-core' ),
				'rating'     => __( 'Sort by average rating', 'xstore-core' ),
				'date'       => __( 'Sort by latest', 'xstore-core' ),
				'price'      => __( 'Sort by price: low to high', 'xstore-core' ),
				'price-desc' => __( 'Sort by price: high to low', 'xstore-core' ),
			)
		);
		
		$html = '';
		foreach ( $options as $key => $value ) {
			$html .=
				'<li>' .
                    '<span class="filter-item" data-value="' . esc_attr( $key ) . '">' .
                        esc_html( $value ) .
                    '</span>' .
				'</li>';
		}
        
        $this->render_item_content($settings, $html, array('attr' => array('data-limit="1"')));
	}
	
	/**
	 * Render Attributes filter item.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function attributes_filter_template( $settings ) {
		
		if ( !$settings['attribute'] ) {
		    return;
		}
		
		$html_tag = 'ul';
		
		global $wpdb;
		$taxonomy           = wc_attribute_taxonomy_name( $settings['attribute'] );
		
		// ! Set get_terms args
		$terms = get_terms( $taxonomy );
		
		$origin_attr = substr( $taxonomy, 3 );
		$attr = get_query_var('et_swatch_tax-'.$origin_attr, false);
		if ( !$attr ) {
			$attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$origin_attr'" );
			set_query_var('et_swatch_tax-'.$origin_attr, $attr);
		}
		
		
		if ( ! $attr || ! $attr->attribute_type ) {
			return;
		}
		
		$attribute_type = $attr->attribute_type;
		
		$html = '';
		
		// if swatches
		if ( get_query_var( 'et_is-swatches', false ) ||
             ( get_theme_mod( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) )
		    ) {
			
			$html_tag = 'div';
			
			if ( function_exists('etheme_enqueue_style') )
                etheme_enqueue_style( "swatches-style");
            
			$class = 'st-swatch-size-large';
			$subtype      = '';
			
			$sw_shape = get_theme_mod('swatch_shape', 'default');
			$sw_custom_shape = $sw_shape != 'default' ? $sw_shape : false;
			
			if ( strpos( $attribute_type, '-sq') !== false ) {
				$et_attribute_type = str_replace( '-sq', '', $attribute_type );
				if ( !$sw_custom_shape || $sw_custom_shape == 'square' ) {
					$class .= ' st-swatch-shape-square';
					$subtype      = 'subtype-square';
				}
				else if ( $sw_custom_shape == 'circle' ) {
					$class .= ' st-swatch-shape-circle';
				}
			} else {
				$et_attribute_type = $attribute_type;
				if ( !$sw_custom_shape || $sw_custom_shape == 'circle' ) {
					$class .= ' st-swatch-shape-circle';
				}
			}
			
			$html .= '<ul class="st-swatch-preview st-color-swatch '. esc_attr( $class ). '">';
			
			    foreach( $terms as $taxonomy ) {
				$metadata    = get_term_meta( $taxonomy->term_id, '', true );
				$data_tooltip = $taxonomy->name;
				$data_slug = $taxonomy->slug;
				$link_class = 'filter-item';
				$validator_hidden_text = '<span class="screen-reader-text hidden">'.$data_tooltip.'</span>';
				$li_class = '';
				// ! Generate html
				switch ( $et_attribute_type ) {
					case 'st-color-swatch':
						$value = ( isset( $metadata['st-color-swatch'] ) && isset( $metadata['st-color-swatch'][0] ) ) ? $metadata['st-color-swatch'][0] : '#fff';
						$html .= '<li class="type-color ' . $subtype . $li_class . '"  data-tooltip="'.$data_tooltip.'">
                        <a class="'.$link_class.'" data-value="'.$data_slug.'">
                            <span class="st-custom-attribute" style="background-color:' . $value . '">
                                '.$validator_hidden_text.'
                            </span>
                        </a></li>';
						break;
					
					case 'st-image-swatch':
						$value = $metadata['st-image-swatch'][0];
						$image = ( $value ) ? wp_get_attachment_image( $value ) : wc_placeholder_img();
						$html .=
                        '<li class="type-image ' . $subtype . $li_class . '"  data-tooltip="'.$data_tooltip.'">
                            <a class="'.$link_class.'" data-value="'.$data_slug.'">
                                <span class="st-custom-attribute">'
                                     . $image . $validator_hidden_text.
                                 '</span>
                            </a>
                        </li>';
						break;
					
					case 'st-label-swatch':
						$value = ( isset( $metadata['st-label-swatch'] ) && $metadata['st-label-swatch'][0] ) ? $metadata['st-label-swatch'][0] : false;
						
						if ( ! $value ) {
							$value = $taxonomy->name;
						}
						
						$html .= '<li class="type-label ' . $subtype . $li_class . '"><a class="'.$link_class.'" data-value="'.$data_slug.'"><span class="st-custom-attribute">' . $value . '</span></a></li>';
						break;
					
					default:
						$html .= '<li class="type-select ' . $li_class . '"><a class="'.$link_class.'" data-value="'.$data_slug.'"><span class="st-custom-attribute">' . $taxonomy->name . '</span></a></li>';
						break;
				}
            }
			    
			$html .= '</ul>';
			
        }
		
		else {
			foreach( $terms as $taxonomy ) {
                $html .=
                    '<li>'.
                        '<span class="filter-item" data-value="'.$taxonomy->slug.'">'.
                            $taxonomy->name .
                        '</span>'.
                    '</li>';
			}
		}
		
		$this->render_item_content(
		        $settings,
                $html,
                array(
                    'name' => 'filter_'.$settings['attribute'],
                    'html_tag' => $html_tag,
                    'query_type_name' => 'query_type_'.$settings['attribute'],
                    'query_type_value' => $settings['attribute_query_type'],
                )
        );
	}
	
	/**
	 * Render Taxonomy filter item.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function taxonomy_filter_template( $settings ) {
		global $wp_query;
		
		$list_args = [
			'taxonomy'           => $settings['filter_type'],
			'hide_empty'         => !!$settings[$settings['filter_type'].'_hide_empty'],
			'title_li'           => false,
            'show_count' => !!$settings[$settings['filter_type'].'_show_count'],
			'use_desc_for_title' => false,
			'echo'               => false,
		];
		
		switch ($settings['filter_type']) {
            case 'product_tag':
                $list_args['show_option_none'] = __('No product tags', 'xstore-core');
                break;
			case 'brand':
				$list_args['show_option_none'] = __('No brands', 'xstore-core');
				break;
            default:
                break;
		}
		
		if ( isset($settings[$settings['filter_type'].'_hierarchical']) ) {
		    $list_args['hierarchical'] = !!$settings[$settings['filter_type'].'_hierarchical'];
		}
		
		$cat_ancestors = [];
		
		$list_args['current_category_ancestors'] = $cat_ancestors;
		
		$extra = array(
		        'attr' => array(
                    'data-limit="1"'
                )
        );
		
		// product_cat || product_tag
		if ( get_query_var( $settings['filter_type'] ) ) {
		    $term = get_term_by('slug', get_query_var( $settings['filter_type'] ), $settings['filter_type']);
			$extra['is_active'] = true;
			$extra['active']['value'] = get_query_var( $settings['filter_type'] );
			$extra['active']['label'] = $term->name;
		}
		
		add_filter( 'category_list_link_attributes', array($this, 'filter_wp_list_categories'), 10, 2 );
		
        $this->render_item_content($settings,  wp_list_categories( $list_args ), $extra);
		
		remove_filter( 'category_list_link_attributes', array($this, 'filter_wp_list_categories'), 10, 2 );
		
	}
	
	/**
	 * Get Price scripts.
	 *
	 * @param $settings
	 * @param $link
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	protected function get_price_scripts() {
		wp_localize_script(
			'wc-price-slider',
			'woocommerce_price_slider_params',
			[
				'currency_format_num_decimals' => 0,
				'currency_format_symbol'       => get_woocommerce_currency_symbol(),
				'currency_format_decimal_sep'  => esc_attr( wc_get_price_decimal_separator() ),
				'currency_format_thousand_sep' => esc_attr( wc_get_price_thousand_separator() ),
				'currency_format'              => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
			]
		);
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wc-jquery-ui-touchpunch' );
		wp_enqueue_script( 'accounting' );
		wp_enqueue_script( 'wc-price-slider' );
	}
	
	/**
	 * Get filtered min price for current products.
	 *
	 * @return int
     *
	 * @since 4.0.8
     *
	 */
	protected function get_filtered_price() {
		global $wpdb;
		$tax_query  = array();
		$meta_query = array();
		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}
		
		$meta_query = new \WP_Meta_Query( $meta_query );
		$tax_query  = new \WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
			AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];
		
		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );
		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}
	
	/**
	 * Add class for wp list categories via filter.
	 *
	 * @param $atts
	 * @param $category
	 * @return mixed
	 *
	 * @since 4.0.8
	 *
	 */
	public function filter_wp_list_categories( $atts, $category ) {
        if ( $category->slug )
		    $atts['data-value'] = $category->slug;
      
		$atts['data-name'] = $category->name;
		
		$atts['class'] = isset($atts['class']) ? isset($atts['class']). ' filter-item' : 'filter-item';
		
		return $atts;
	}
	
	/**
	 * Render Item Content.
	 *
	 * @param       $settings
	 * @param       $html
	 * @param array $extra
	 * @return void
	 *
	 * @since 4.0.8
	 *
	 */
	public function render_item_content($settings, $html, $extra = array()) {
	    $extra = shortcode_atts(array(
            'name' => $settings['filter_type'],
            'html_tag' => 'ul',
            'result_input' => true,
            'attr' => array(),
            'query_type_name' => false,
            'query_type_value' => false,
            'is_active' => false,
            'active' => array(
                'value' => '',
                'label' => ''
            )
        ), $extra);
	    
	    ?>
        <div class="etheme-product-filters-item" <?php echo implode(' ', $extra['attr']); ?>>
            <?php if ( $extra['result_input']) : ?>
                <input type="hidden" class="result-input" name="<?php echo $extra['name']; ?>" value="<?php echo $extra['is_active'] ? $extra['active']['value'] : ''; ?>">
            <?php endif; ?>
            <?php if ( $extra['query_type_name'] && $extra['query_type_value'] ) : ?>
                <input type="hidden" name="<?php echo $extra['query_type_name']; ?>" value="<?php echo $extra['query_type_value']; ?>">
            <?php endif; ?>
            <div class="etheme-product-filters-item-title">
                    <span class="title-text">
                        <?php echo esc_html( $settings['filter_title'] ); ?>
                    </span>
    
                <span class="etheme-product-filters-quick-results">
                    <?php if ( $extra['is_active'] ) : ?>
                        <span data-q-value="<?php echo esc_attr($extra['active']['value']); ?>">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".75em" height=".75em" viewBox="0 0 24 24">
                                <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                            </svg><?php echo $extra['active']['label'] ?></span>
                    <?php endif; ?>
                    <?php if ( $settings['filter_type'] == 'price' ) { ?>
                        <span style="display:none;" data-q-value="price">
                            <span class="from"></span> &mdash; <span class="to"></span>
                        </span>
                    <?php } ?>
                </span>
            </div>

            <<?php echo $extra['html_tag']; ?> class="etheme-product-filters-item-content" style="display: none">
                <?php echo $html; ?>
            </<?php echo $extra['html_tag']; ?>>
        </div>
        <?php
	}
	
	/**
	 * Return filtered product taxonomies for filters.
	 *
	 * @since 4.0.8
	 *
	 * @return mixed
	 */
	public function product_taxonomies_to_filter() {
	    return apply_filters('etheme_product_filters_taxonomies', array(
		    'product_cat' => esc_html__('Categories', 'xstore-core'),
		    'product_tag' => esc_html__('Product tag', 'xstore-core'),
	    ) );
	}
	
	/**
	 * Get product attributes.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_attributes() {
		$output = [
			'' => esc_html__( 'Select', 'xstore-core' ),
		];
		
        $taxonomies = wc_get_attribute_taxonomies();
        
        if ( $taxonomies ) {
            foreach ( $taxonomies as $tax ) {
                $output[ $tax->attribute_name ] = $tax->attribute_name;
            }
        }
		
		return $output;
	}
}
