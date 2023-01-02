<?php
namespace ETC\App\Traits;

/**
 * Elementor Trait
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Core
 */
trait Elementor {

	/**
	 * Get brands terms.
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_terms( $taxonomy, $top_level_only = false, $with_empty = true ) {
		$args = array(
			'taxonomy'      => $taxonomy,
			'hide_empty'    =>	false,
			'include' 		=> 'all',
		);
		
		if ( $top_level_only )
			$args['parent'] = 0;

		$the_query = new \WP_Term_Query($args);
		$list = array();
		if ( $with_empty ) 
			$list[] = __( 'Select Option', 'xstore-core' );

		foreach( $the_query->get_terms() as $term ) { 
			$id = $term->term_id;
			$list[$id] = $term->name . ' (id - ' . $id . ')';
		}

		return $list;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_products_args() {
		$args = array(
			'post_type'   			=> array( 'product_variation', 'product' ),
			'post_status'           => 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page'		=> 200,
		);

		return $args;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_products() {
		$args = self::get_products_args();

		$the_query       = new \WP_Query( $args );
		$list = array();
		$list[] = __( 'Select Option', 'xstore-core' );

		if ( $the_query->have_posts() ) : 
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$id = get_the_ID();
				$list[$id] = get_the_title() . ' (id - ' . $id . ')';
			endwhile;
			wp_reset_postdata();
		endif;

		return $list;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_post_pages_args($array) {
		$args = array(
			'post_type'   			=> $array,
			'post_status'           => 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page'		=> 200,
		);

		return $args;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_post_pages( $array = array('post') ) {
		$args = self::get_post_pages_args($array);

		$the_query       = new \WP_Query( $args );
		$list = array();
		$list[] = __( 'Select Option', 'xstore-core' );

		if ( $the_query->have_posts() ) : 
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$id = get_the_ID();
				$list[$id] = get_the_title() . ' (id - ' . $id . ')';
			endwhile;
			wp_reset_postdata();
		endif;

		return $list;
	}

	/**
	 * Get static block id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_static_blocks() {
		if ( ! function_exists( 'etheme_get_static_blocks' ) ) {
			return;
		}

		$static_blocks = array();
		$static_blocks[] = "--choose--";
		
		foreach ( etheme_get_static_blocks() as $block ) {
			$static_blocks[$block['value']] = $block['label'];
		}

		return $static_blocks;
	}

	/**
	 * Get instagram_api_data.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function instagram_api_data() {
		$api_data = get_option( 'etheme_instagram_api_data' );
		$api_data = json_decode($api_data, true);
		$users    = array( '' => '' );

		if ( is_array($api_data) && count( $api_data ) ) {
			foreach ( $api_data as $key => $value ) {
				$value = json_decode( $value, true );
				if ( isset($value['data']['username']) ) {
					$users[$key] = $value['data']['username'] . ' (old API)';
				} else {
					$users[$key] = $value['username'];
				}
			}
		}

		return $users;
	}

	/**
	 * Get menu params.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function menu_params() {
		$menus = wp_get_nav_menus();
		$menu_params = array();
		foreach ( $menus as $menu ) {
			$menu_params[$menu->term_id] = $menu->name;
		}

		return $menu_params;
	}
	
	/**
	 * Create new controls for requested widgets
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_slider_params( $control ) {

		$control->add_control(
			'hide_buttons',
			[
				'label' 		=> __( 'Hide Navigation', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
			]
		);

		$control->add_control(
			'hide_buttons_for',
			[
				'label' 		=> __( 'Hide Navigation Only For', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  __( 'Both', 'xstore-core' ),
					'mobile'	=>	__( 'Mobile', 'xstore-core' ),
					'desktop'	=>	__( 'Desktop', 'xstore-core' ),
				],
				'condition' => ['hide_buttons' => 'true'],
			]
		);
		
		$control->add_control(
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
		
		$control->add_control(
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
		
		$control->add_control(
			'navigation_position',
			[
				'label' 		=> esc_html__( 'Navigation Position', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'middle' 			=>	esc_html__( 'Middle', 'xstore-core' ),
					'middle-inside' 	=>	esc_html__( 'Middle Inside', 'xstore-core' ),
					'bottom' 			=>	esc_html__( 'Bottom', 'xstore-core' ),
				],
				'default'	=> 'middle',
				'condition' => ['hide_buttons' => '']
			]
		);
		
		$control->add_control(
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
							'operator'  => '=',
							'value' 	=> ''
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
		
		$control->add_responsive_control(
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
				'conditions' 	=> [
					'relation' 	=> 'and',
					'terms' 	=> [
						[
							'name' 		=> 'hide_buttons',
							'operator'  => '=',
							'value' 	=> ''
						],
					]
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'navigation_space',
			[
				'label'		 =>	esc_html__( 'Navigation Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 200,
						'step' 	=> 1
					],
				],
				'conditions' 	=> [
					'relation' 	=> 'and',
					'terms' 	=> [
						[
							'name' 		=> 'hide_buttons',
							'operator'  => '=',
							'value' 	=> ''
						],
						[
							'name' 		=> 'navigation_position',
							'operator'  => '=',
							'value' 	=> 'bottom'
						],
					]
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-space: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'pagination_type',
			[
				'label' 		=> __( 'Pagination Type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'hide' 		=>	__( 'Hide', 'xstore-core' ),
					'bullets' 	=>	__( 'Bullets', 'xstore-core' ),
					'lines' 	=>	__( 'Lines', 'xstore-core' ),
				],
				'default' 		=> 'hide',
			]
		);
		
		$control->add_control(
			'hide_fo',
			[
				'label' 		=> __( 'Hide Pagination Only For', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  __( 'Select option', 'xstore-core' ),
					'mobile'	=>	__( 'Mobile', 'xstore-core' ),
					'desktop'	=>	__( 'Desktop', 'xstore-core' ),
				],
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$control->add_control(
			'slider_autoplay',
			[
				'label' 		=> __( 'Autoplay', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
			]
		);

		$control->add_control(
			'slider_stop_on_hover',
			[
				'label' 		=> __( 'Pause On Hover', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
				'condition' 	=> ['slider_autoplay' => 'true'],
			]
		);

		$control->add_control(
			'slider_interval',
			[
				'label' 		=> __( 'Autoplay Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> __( 'Interval between slides. In milliseconds.', 'xstore-core' ),
				'return_value' 	=> 'true',
				'default' 		=> 3000,
				'condition' 	=> ['slider_autoplay' => 'true'],
			]
		);
		
		$control->add_control(
			'slider_loop',
			[
				'label' 		=> __( 'Infinite Loop', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);

		$control->add_control(
			'slider_speed',
			[
				'label' 		=> __( 'Transition Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> __( 'Duration of transition between slides. In milliseconds.', 'xstore-core' ),
				'default' 		=> '300',
			]
		);

		$control->add_responsive_control(
			'slides',
			[
				'label' 	=>	__( 'Slider Items', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::NUMBER,
				'default' 	=>	4,
//				'tablet_default' => 3,
//				'mobile_default' => 2,
				'min' => 1,
			]
		);
		
		$control->add_control(
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
				'default'	=> 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper' => 'align-items: {{VALUE}};',
				],
			]
		);
		
		$control->start_controls_tabs('arrows_style_tabs');
		$control->start_controls_tab( 'arrows_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);

		$control->add_control(
			'nav_color',
			[
				'label' 	=> __( 'Navigation Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-nav, {{WRAPPER}} .et-swiper-elementor-nav' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'arrows_bg_color',
			[
				'label' 	=> __( 'Arrows Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-nav, {{WRAPPER}} .et-swiper-elementor-nav' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$control->add_control(
			'arrows_border_color',
			[
				'label' 	=> esc_html__( 'Nav Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-nav, {{WRAPPER}} .et-swiper-elementor-nav' => 'border-color: {{VALUE}};',
				]
			]
		);
		
		$control->end_controls_tab();
		
		$control->start_controls_tab(
			'arrows_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$control->add_control(
			'nav_color_hover',
			[
				'label' 	=> __( 'Navigation Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-nav:hover, {{WRAPPER}} .et-swiper-elementor-nav:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_control(
			'arrows_bg_color_hover',
			[
				'label' 	=> __( 'Arrows Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-nav:hover, {{WRAPPER}} .et-swiper-elementor-nav:hover' => 'opacity: 1; background-color: {{VALUE}};',
				],
			]
		);
		
		$control->add_control(
			'arrows_border_color_hover',
			[
				'label' 	=> esc_html__( 'Nav Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-nav:hover, {{WRAPPER}} .et-swiper-elementor-nav:hover' => 'border-color: {{VALUE}};',
				]
			]
		);
		
		$control->end_controls_tab();
		$control->end_controls_tabs();

		$control->start_controls_tabs( 'pagination_color_settings' );

		$control->start_controls_tab(
			'pagination_color_settings_regular',
			[
				'label' => __( 'Regular', 'xstore-core' ),
			]
		);

		$control->add_control(
			'default_color',
			[
				'label' 	=> __( 'Pagination Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab(
			'pagination_color_settings_active',
			[
				'label' => __( 'Active', 'xstore-core' ),
			]
		);

		$control->add_control(
			'active_color',
			[
				'label' 	=> __( 'Pagination Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

	}	

	/**
	 * Create new controls for requested widgets
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_slider_params_repeater( $repeater ) {

		$repeater->add_control(
			'navigation_header',
			[
				'label' => __( 'Navigation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hide_buttons',
			[
				'label' 		=> __( 'Hide Navigation', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
			]
		);

		$repeater->add_control(
			'navigation_style',
			[
				'label' 		=> __( 'Navigation Style', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'arrow-style-1' 	=>	__( 'Arrow Style 1', 'xstore-core' ),
					'arrow-style-2' 	=>	__( 'Arrow Style 2', 'xstore-core' ),
					'arrow-style-3' 	=>	__( 'Arrow Style 3', 'xstore-core' ),
					'arrow-style-4' 	=>	__( 'Arrow Style 4', 'xstore-core' ),
					'arrow-style-5' 	=>	__( 'Arrow Style 5', 'xstore-core' ),
					'arrow-style-6' 	=>	__( 'Arrow Style 6', 'xstore-core' ),
					'archery-style-1' 	=>	__( 'Archery Style 1', 'xstore-core' ),
					'archery-style-2' 	=>	__( 'Archery Style 2', 'xstore-core' ),
					'archery-style-3' 	=>	__( 'Archery Style 3', 'xstore-core' ),
					'archery-style-4' 	=>	__( 'Archery Style 4', 'xstore-core' ),
					'archery-style-5' 	=>	__( 'Archery Style 5', 'xstore-core' ),
					'archery-style-6' 	=>	__( 'Archery Style 6', 'xstore-core' ),
				],
				'condition' => ['hide_buttons' => ''],
				'default'	=> 'arrow-style-1',
			]
		);

		$repeater->add_control(
			'navigation_position',
			[
				'label' 		=> __( 'Navigation Position', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'nav-bar' 			=>	__( 'Nav Bar', 'xstore-core' ),
					'middle' 			=>	__( 'Middle', 'xstore-core' ),
					'middle-inside' 	=>	__( 'Middle Inside', 'xstore-core' ),
				],
				'condition' => ['hide_buttons' => ''],
				'default'	=> 'middle',
			]
		);

		$repeater->add_control(
			'navigation_position_style',
			[
				'label' 		=> __( 'Nav Hover Style', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'arrows-hover' 	=>	__( 'Display On Hover', 'xstore-core' ),
					'arrows-always' 	=>	__( 'Always Display', 'xstore-core' ),
				],
				'default'		=> 'arrows-hover',
				'conditions' 	=> [
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
						]
					]
				]
			]
		);

		$repeater->add_responsive_control(
			'navigation_arrow_size',
			[

				'label'	=>	__( 'Nav Arrow Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'conditions' 	=> [
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
						]
					]
				]
			]
		);

		$repeater->add_control(
			'nav_color',
			[
				'label' 	=> __( 'Nav Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'selectors' => [
					'{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-next.navbar,
					{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-prev.navbar,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-next,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-prev,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-next.bottom,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-prev.bottom' => 'color: {{VALUE}};'
				],
				'condition' => ['hide_buttons' => ''],
			]
		);

		$repeater->add_control(
			'arrows_bg_color',
			[
				'label' 	=> __( 'Nav Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'selectors' => [
					'{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-next.navbar,
					{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-prev.navbar,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-next,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-prev,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-next.bottom,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-prev.bottom' => 'background-color: {{VALUE}};'
				],
				'condition' => ['hide_buttons' => ''],
			]
		);

		$repeater->add_control(
			'nav_color_hover',
			[
				'label' 	=> __( 'Nav Color Hover', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'selectors' => [
					'{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-next.navbar:hover,
					{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-prev.navbar:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-next:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-prev:hov,,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-next.bottom:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-prev.bottom:hover' => 'color: {{VALUE}};'
				],
				'condition' => ['hide_buttons' => ''],
			]
		);

		$repeater->add_control(
			'arrows_bg_color_hover',
			[
				'label' 	=> __( 'Nav Background Hover', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'selectors' => [
					'{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-next.navbar:hover,
					{{WRAPPER}} .et-advance-product-tabs .et-tabs-nav .swiper-button-prev.navbar:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-next:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-entry .swiper-button-prev:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-next.bottom:hover,
					{{WRAPPER}} .et-advance-product-tabs .swiper-button-prev.bottom:hover' => 'background-color: {{VALUE}};'
				],
				'condition' => ['hide_buttons' => ''],
			]
		);

		$repeater->add_control(
			'hide_buttons_for',
			[
				'label' 		=> __( 'Hide Navigation Only For', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  __( 'Both', 'xstore-core' ),
					'mobile'	=>	__( 'Mobile', 'xstore-core' ),
					'desktop'	=>	__( 'Desktop', 'xstore-core' ),
				],
				'condition' => ['hide_buttons' => 'true'],
			]
		);

		$repeater->add_control(
			'pagination_header',
			[
				'label' => __( 'Pagination', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'pagination_type',
			[
				'label' 		=> __( 'Pagination Type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'hide' 		=>	__( 'Hide', 'xstore-core' ),
					'bullets' 	=>	__( 'Bullets', 'xstore-core' ),
					'lines' 	=>	__( 'Lines', 'xstore-core' ),
				],
				'default' 		=> 'hide',
			]
		);

		$repeater->add_control(
			'hide_fo',
			[
				'label' 		=> __( 'Hide Pagination Only For', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  __( 'Select option', 'xstore-core' ),
					'mobile'	=>	__( 'Mobile', 'xstore-core' ),
					'desktop'	=>	__( 'Desktop', 'xstore-core' ),
				],
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$repeater->add_control(
			'default_color',
			[
				'label' 	=> __( 'Pagination Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '#e1e1e1',
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$repeater->add_control(
			'active_color',
			[
				'label' 	=> __( 'Pagination color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '#222',
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$repeater->add_control(
			'Settings_header',
			[
				'label' => __( 'Settings', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'slider_autoplay',
			[
				'label' 		=> __( 'Autoplay', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
			]
		);

		$repeater->add_control(
			'slider_stop_on_hover',
			[
				'label' 		=> __( 'Pause On Hover', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> '',
				'condition' 	=> ['slider_autoplay' => 'true'],
			]
		);

		$repeater->add_control(
			'slider_interval',
			[
				'label' 		=> __( 'Autoplay Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> __( 'Interval between slides. In milliseconds.', 'xstore-core' ),
				'return_value' 	=> 'true',
				'default' 		=> 3000,
				'condition' 	=> ['slider_autoplay' => 'true'],
			]
		);

		$repeater->add_control(
			'slider_loop',
			[
				'label' 		=> __( 'Infinite Loop', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);

		$repeater->add_control(
			'slider_speed',
			[
				'label' 		=> __( 'Transition Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> __( 'Duration of transition between slides. In milliseconds.', 'xstore-core' ),
				'default' 		=> '300',
			]
		);

		$repeater->add_responsive_control(
			'slides',
			[
				'label' 	=>	__( 'Slider Items', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::NUMBER,
				'default' 	=>	4,
//				'tablet_default' => 3,
//				'mobile_default' => 2,
				'min' => 0,
			]
		);

	}

	public static function slider_navigation( $widget_settings, $tab_settings, $visibility = 'none' ){

		$navigation_class = '';
		if ( $tab_settings['hide_buttons_for'] == 'desktop' ) 
			$navigation_class = ' dt-hide';
		elseif ( $tab_settings['hide_buttons_for'] == 'mobile' ) 
			$navigation_class = ' mob-hide';
		
		$navigation_class_left  = 'swiper-custom-left' . ' ' . $navigation_class;
		$navigation_class_right = 'swiper-custom-right' . ' ' . $navigation_class;
		
		$navigation_class_left .= ' type-' . $widget_settings['navigation_type'] . ' ' . $widget_settings['navigation_style'];
		$navigation_class_right .= ' type-' . $widget_settings['navigation_type'] . ' ' . $widget_settings['navigation_style'];

		if ( 'nav-bar' === $widget_settings['navigation_position'] ) {

			$style = 'style="display: '. $visibility . ';"';

			return'
			<li '. $style .' data-id="'. $tab_settings['_id'] .'" class="skip swiper-button-prev navbar ' . $navigation_class_left .' '. $navigation_class . '" ></li>
			<li style="display:'. $visibility . ';" data-id="'. $tab_settings['_id'] .'" class="skip swiper-button-next navbar ' . $navigation_class_right .' '. $navigation_class . '"></li>';
		}		

		if ( 'bottom' === $widget_settings['navigation_position'] ) {
			return'
			<div class="swiper-button-prev bottom ' . $navigation_class_left .' '. $navigation_class . '"></div>
			<div class="swiper-button-next bottom ' . $navigation_class_right .' '. $navigation_class . '"></div>';
		}

		return false;
	}

	/**
	 * Create menu list item widget
	 *
	 * @since 2.1.3
	 * @access public
	 */
  	public static function get_menu_list_item( $repeater ) {

      $divider = 0;

      $repeater->start_controls_tabs( 'menu_list_item_tabs' );

      $repeater->start_controls_tab(
        'menu_list_item_content',
        [
          'label' => __( 'Content', 'xstore-core' ),
        ]
      );

      $repeater->add_control(
        'divider'.$divider++,
        [
          'label' => __( 'Title', 'xstore-core' ),
          'type' => \Elementor\Controls_Manager::HEADING,
          'separator' => 'before',
        ]
      );

      $repeater->add_control(
        'title',
        [
          'label' => __( 'Title', 'xstore-core' ),
          'type'  => \Elementor\Controls_Manager::TEXT,
        ]
      );

      $repeater->add_control(
        'title_custom_tag',
        [
          'label'     =>  __( 'Title HTML Tag', 'xstore-core' ),
          'type'      =>  \Elementor\Controls_Manager::SELECT,
          'options'     =>  [
            'h1'      => 'H1',
            'h2'      => 'H2',
            'h3'      => 'H3',
            'h4'      => 'H4',
            'h5'      => 'H5',
            'h6'      => 'H6',
            'p'       => 'p',
            'div'     => 'div',
          ],
          'default'   => 'h3',
        ]
      );

      $repeater->add_control(
        'link',
        [
          'label' => __( 'Link', 'xstore-core' ),
          'type'  => \Elementor\Controls_Manager::URL,
        ]
      );

      $repeater->add_control(
        'label',
        [
          'label'     =>  __( 'Label', 'xstore-core' ),
          'type'      =>  \Elementor\Controls_Manager::SELECT,
          'options'     =>  [
            ''    =>  esc_html__( 'Select Label', 'xstore-core' ),
            'hot' =>  esc_html__( 'Hot', 'xstore-core' ),
            'sale'  =>  esc_html__( 'Sale', 'xstore-core' ),
            'new' =>  esc_html__( 'New', 'xstore-core' ),
          ],
        ]
      );

		$repeater->add_control(
			'divider'.$divider++,
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'class',
			[
				'label' => __( 'CSS Classes', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

      	$repeater->end_controls_tab();

      	$repeater->start_controls_tab(
        	'menu_list_item_style',
        	[
          		'label' => __( 'Style', 'xstore-core' ),
        	]
      	);

      	$repeater->add_control(
	        'divider'.$divider++,
	        [
          		'label' => __( 'Title', 'xstore-core' ),
	          	'type' => \Elementor\Controls_Manager::HEADING,
	          	'separator' => 'before',
	        ]
      	);

      	$repeater->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'          => 'title_typography',
                'label'         => __( 'Typography', 'xstore-core' ),
                'selector'      => '{{WRAPPER}} {{CURRENT_ITEM}}  .menu-title',
          		'separator'     => 'before',
            ]
    	);

      $repeater->add_control(
        	'color',
        	[
	          	'label'   => __( 'Color', 'xstore-core' ),
	          	'type'    => \Elementor\Controls_Manager::COLOR,
	            'selectors'    => [
	                '{{WRAPPER}} {{CURRENT_ITEM}} .menu-title' => 'color: {{VALUE}};',
	            ],
        	]
      	);

      $repeater->add_control(
        'hover_color',
        [
          'label'   => __( 'Color (hover)', 'xstore-core' ),
          'type'    => \Elementor\Controls_Manager::COLOR,
          'selectors'    => [
            '{{WRAPPER}} {{CURRENT_ITEM}}:hover .menu-title' => 'color: {{VALUE}};',
          ],
        ]
      	);

	    $repeater->add_control(
	        'item_paddings',
	        [
	          'label' => __( 'Padding', 'xstore-core' ),
	          'type' => \Elementor\Controls_Manager::DIMENSIONS,
	          'size_units' => [ 'px', '%' ],
	          'selectors' => [
	            '{{WRAPPER}} .et-menu-list {{CURRENT_ITEM}} .menu-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	          ],
	        ]
	      );

      	$repeater->end_controls_tab();

  		$repeater->end_controls_tabs();

	}

	/**
	 * Create menu list item widget
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_scroll_text_item( $repeater ) {

        $repeater->add_control(
            'content',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'placeholder' => __( 'Lorem ipsum dolor ...', 'xstore-core' ),
            ]
        );

        $repeater->add_control(
            'tooltip',
            [
                'label'         =>  __( 'Use Tooltip Instead Of Link', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'return_value'  =>  'true',
                'default'       =>  '',
            ]
        );        

        $repeater->add_control(
            'tooltip_title',
            [
                'label' => __( 'Tooltip Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition'     =>  ['tooltip' => 'true'],
            ]
        );

        $repeater->add_control(
            'tooltip_content',
            [
                'label' => __( 'Tooltip Content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'placeholder'   => __( 'Lorem ipsum dolor ...', 'xstore-core' ),
                'condition'     =>  ['tooltip' => 'true'],
            ]
        );

        $repeater->add_control(
            'tooltip_content_pos',
            [
                'label'         =>  __( 'Tooltip Content Position', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SELECT,
                'options'       =>  [
                    'bottom' => esc_html__( 'Bottom', 'xstore-core' ),
                    'top' 	 => esc_html__( 'Top', 'xstore-core' ),
                ],
            ]
        );

        $repeater->add_control(
        	'button_link',
        	[
        		'label' => __( 'Button Link', 'xstore-core' ),
        		'type' => \Elementor\Controls_Manager::URL,
        		'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
        		'show_external' => true,
        		'default' => [
        			'url' => '',
        			'is_external' => true,
        			'nofollow' => true,
        		],
        		'condition' => [
        			'tooltip!' => 'true'
		        ]
        	]
        );

        $repeater->add_control(
            'el_class',
            [
                'label' => __( 'CSS Classes', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

	}

	/**
	 * Create slider item
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_slider_item( $repeater ) {

		$repeater->start_controls_tabs( 'slide_settings' );

		$repeater->start_controls_tab(
			'content_settings',
			[
				'label' => __( 'Content', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'divider_title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' 		=>	__( 'Title', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Slide Heading', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'title_class',
			[
				'label' 		=>	__( 'CSS Classes', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'divider_subtitle',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' 		=>	__( 'Subtitle', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Slide Subtitle', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'subtitle_class',
			[
				'label' 		=>	__( 'CSS Classes', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);


		$repeater->add_control(
			'subtitle_above',
			[
				'label' 		=>	__( 'Show Subtitle Above Title ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'divider_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'divider_button',
			[
				'label' => __( 'Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
	
		$repeater->add_control(
			'button_title',
			[
				'label' => __( 'Button Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Click here', 'xstore-core' )
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => __( 'Button Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::URL,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' 		=>	__( 'Apply For Slide', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'divider_class',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'el_class',
			[
				'label' 		=>	__( 'CSS Classes', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'background_settings',
			[
				'label' => __( 'Background', 'xstore-core' ),
			]
		);

		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic' ], // classic, gradient, video, slideshow
				'selector'    => '{{WRAPPER}} {{CURRENT_ITEM}}',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
						'default' => 'classic'
					],
					'color' => [
						'default' => '#fafafa'
					],
					'position' => [
						'default' => 'center center'
					],
					'repeat' => [
						'default' => 'no-repeat'
					],
					'size' => [
						'default' => 'cover'
					],
				],
			]
		);

		$repeater->add_control(
			'bg_overlay',
			[
				'label' 	=> __( 'Background Overlay', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'style_settings',
			[
				'label' => __( 'General', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'divider_content_style',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'content_width',
			[

				'label'	=>	__( 'Content Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
						'step' => 10
					],
				],
			]
		);

		$repeater->add_control(
			'align',
			[
				'label' 		=>	__( 'Horizontal Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'start'		=>	esc_html__( 'Left', 'xstore-core' ),
					'end'		=>	esc_html__( 'Right', 'xstore-core' ),
					'center'	=>	esc_html__( 'Center', 'xstore-core' ),
					'between'	=>	esc_html__( 'Stretch', 'xstore-core' ),
					'around'	=>	esc_html__( 'Stretch (no paddings)', 'xstore-core' ),
				],
				'default' => 'center'
			]
		);

		$repeater->add_control(
			'v_align',
			[
				'label' 		=>	__( 'Vertical Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'start'		=>	esc_html__( 'Top', 'xstore-core' ),
					'end'		=>	esc_html__( 'Bottom', 'xstore-core' ),
					'center'	=>	esc_html__( 'Middle', 'xstore-core' ),
					'stretch'	=>	esc_html__( 'Full height', 'xstore-core' ),
				],
				'default' => 'center'
			]
		);

		$repeater->add_responsive_control(
			'text_align',
			[
				'label' 		=>	__( 'Text Align', 'xstore-core' ),
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
					'justify' => [
						'title' => __( 'Justified', 'xstore-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'content_paddings',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'divider_title_style',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_group_control(
	        \Elementor\Group_Control_Typography::get_type(),
	        [
	            'name'        	=> 'title_typography',
	            'label'       	=> __( 'Typography', 'xstore-core' ),
	            'selector'    	=> '{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-title',
				'separator'   	=> 'before',
	        ]
	    );

		$repeater->add_control(
			'color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-title' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'title_css_animation',
			[
				'label' => __( 'Animation Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'render_type' => 'ui',
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-title' => 'animation-name: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'title_animation_duration',
			[
				'label' 		=>	__( 'Animation Duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 500,
				'condition' => [
					'title_css_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-title' => 'animation-duration: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'title_animation_delay',
			[
				'label' 		=>	__( 'Animation Delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 0,
				'condition' => [
					'title_css_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-title' => 'animation-delay: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'divider_subtitle_style',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_group_control(
	        \Elementor\Group_Control_Typography::get_type(),
	        [
	            'name'        	=> 'subtitle_typography',
	            'label'       	=> __( 'Typography', 'xstore-core' ),
	            'selector'    	=> '{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-subtitle',
				'separator'   	=> 'before',
	        ]
	    );

		$repeater->add_control(
			'subtitle_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'subtitle_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'subtitle_css_animation',
			[
				'label' => __( 'Animation Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'render_type' => 'ui',
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-subtitle' => 'animation-name: {{VALUE}};',
				],

			]
		);

		$repeater->add_control(
			'subtitle_animation_duration',
			[
				'label' 		=>	__( 'Animation duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 500,
				'condition' => [
					'subtitle_css_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-subtitle' => 'animation-duration: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'subtitle_animation_delay',
			[
				'label' 		=>	__( 'Animation Delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 0,
				'condition' => [
					'subtitle_css_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-subtitle' => 'animation-delay: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'divider_description_style',
			[
				'label' => __( 'Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_group_control(
	        \Elementor\Group_Control_Typography::get_type(),
	        [
	            'name'        	=> 'description_typography',
	            'label'       	=> __( 'Typography', 'xstore-core' ),
	            'selector'    	=> '{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .description',
				'separator'   	=> 'before',
	        ]
	    );

		$repeater->add_control(
			'description_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .description' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'description_animation',
			[
				'label' => __( 'Animation Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'render_type' => 'ui',
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .description' => 'animation-name: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'description_animation_duration',
			[
				'label' 		=>	__( 'Animation duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 500,
				'condition' => [
					'description_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .description' => 'animation-duration: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'description_animation_delay',
			[
				'label' 		=>	__( 'Animation Delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 0,
				'condition' => [
					'description_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .description' => 'animation-delay: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'divider_button_style',
			[
				'label' => __( 'Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_color',
			[
				'label' 	=> __( 'Button Text Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);
		
		$repeater->add_control(
			'button_bg',
			[
				'label' 	=> __( 'Button  Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);
		
		$repeater->add_control(
			'button_hover_color',
			[
				'label' 	=> __( 'Button Text Color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);
		
		$repeater->add_control(
			'button_hover_bg',
			[
				'label' 	=> __( 'Button Background Color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$repeater->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'button_typography',
				'label' 		=> __( 'Button Typography', 'xstore-core' ),
				'description' 	=> __( 'Use this field to add element font size. For example 20px', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-button',
				'separator'   	=> 'before',
			]
		);

		$repeater->add_responsive_control(
			'button_paddings',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'default' => [
					'unit' => 'px',
					'top' => '10',
					'right' => '25',
					'bottom' => '10',
					'left' => '15',
					'isLinked' => false
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$repeater->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'default' => [
					'unit' => 'px',
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'button_animation',
			[
				'label' => __( 'Animation Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'render_type' => 'ui',
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-button' => 'animation-name: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'button_animation_duration',
			[
				'label' 		=>	__( 'Animation Duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 500,
				'condition' => [
					'button_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-button' => 'animation-duration: {{VALUE}}ms;',
				],
			]
		);

		$repeater->add_control(
			'button_animation_delay',
			[
				'label' 		=>	__( 'Animation Delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default' 		=> 0,
				'condition' => [
					'button_animation!' => 'none'
				],
				'selectors'    => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-content .slide-button' => 'animation-delay: {{VALUE}}ms;',
				],
			]
		);
		
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();
	}

	/**
	 * Create banner with mask control
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_banner_with_mask( $control, $repeater = false, $global_style = false ) {

		if ( !$repeater ) {
			$control->start_controls_section(
				'settings',
				[
					'label' => __( 'General', 'xstore-core' ),
					'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);
		}

		$control->add_control(
			'img',
			[
				'label' => __( 'Banner Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$control->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'xstore-core' ),
				'default' => __( 'Banner title', 'xstore-core' )
			]
		);

		$control->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'xstore-core' ),
				'default' => __( 'Banner subtitle', 'xstore-core' )
			]
		);

		$control->add_control(
			'content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Some promo words', 'xstore-core' ),
			]
		);

		$control->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$control->add_control(
			'button_title',
			[
				'label' => __( 'Button Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_external' => true,
				'default' => __( 'Button Title', 'xstore-core' )
			]
		);

		$control->add_control(
			'button_link',
			[
				'label' => __( 'Button link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		// if using placeholder with ajax loading it won't be shown correctly
		// $control->add_control(
		// 	'ajax',
		// 	[
		// 		'label' => __( 'Lazy loading for this element', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'description' 	=>	__( 'Works for live mode, not for the preview', 'xstore-core' ),
		// 		'label_on' => __( 'Yes', 'xstore-core' ),
		// 		'label_off' => __( 'No', 'xstore-core' ),
		// 		'return_value' => 'true',
		// 		'default' => '',
		// 	]
		// );
		
		if ( !$global_style )
			self::get_banner_with_mask_style($control, $repeater);

		
	}
	
	public static function get_banner_with_mask_style($control, $repeater = false, $global_style = false) {
		if ( !$repeater ) {
			if ( !$global_style ) {
				$control->end_controls_section();
			}
			$control->start_controls_section(
				'style_section',
				[
					'label' => __( 'General', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_responsive_control(
			'align',
			[
				'label' =>	__( 'Content Alignment', 'xstore-core' ),
				'type' 	=>	\Elementor\Controls_Manager::CHOOSE,
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
				'default'	=> 'center',
				'selectors' => [
					'{{WRAPPER}} .banner .banner-content, {{WRAPPER}} .banner .banner-content .banner-title, {{WRAPPER}} .banner .banner-content .banner-subtitle' => 'text-align: {{VALUE}} !important;',
				],
			]
		);
		
		$control->add_control(
			'valign',
			[
				'label' => __( 'Content Vertical Align', 'xstore-core' ),
				'type' 	=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top'    => [
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
				'default'	=> 'middle',
			]
		);
		
		$control->add_control(
			'type',
			[
				'label' 	=> __( 'Hover Animation', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::SELECT,
				'default' 	=> 6,
				'label_block'	=> 'true',
				'options' 	=> [
					2 => __('Zoom In', 'xstore-core'),
					6 => __('Slide right', 'xstore-core'),
					4 => __('Zoom out', 'xstore-core'),
					5 => __('Scale out', 'xstore-core'),
					3 => __('None', 'xstore-core'),
				],
			]
		);
		
		$control->add_control(
			'content_on_hover',
			[
				'label' => __( 'Button On Hover', 'xstore-core' ), // text this but key of option is another because of it's work
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$control->add_control(
			'is_active',
			[
				'label' => __( 'Hovered State By Default', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'description' => __( 'Make banner with hovered effects by default', 'xstore-core' ),
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		$control->add_control(
			'type_with_diagonal',
			[
				'label' => __( 'With Diagonal', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Image effect with diagonal', 'xstore-core' ),
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		$control->add_control(
			'type_with_border',
			[
				'label' => __( 'With Border Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Image effect with border inside', 'xstore-core' ),
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		if ( !$repeater ) {
			$control->end_controls_section();
			$control->start_controls_section(
				'title_section',
				[
					'label' => __( 'Title', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .banner-title',
			]
		);
		
		$control->add_control(
			'title_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .banner-content .banner-title span'
			]
		);
		
		$control->add_control(
			'title_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'xstore-core' ),
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
				'default' => 'h2',
			]
		);
		
		$control->add_responsive_control(
			'title_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-title span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-title span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'title_space',
			[
				'label'		 =>	esc_html__( 'Bottom Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 70,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'hide_title_responsive',
			[
				'label' => __( 'Hide On Mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		if ( !$repeater ) {
			$control->end_controls_section();
			$control->start_controls_section(
				'subtitle_section',
				[
					'label' => __( 'Subtitle', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'separator' => 'before',
				'label' => __( 'Typography', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .banner-subtitle',
			]
		);
		
		$control->add_control(
			'subtitle_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'subtitle_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .banner-content .banner-subtitle span'
			]
		);
		
		$control->add_control(
			'subtitle_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'xstore-core' ),
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
				'default' => 'h2',
			]
		);
		
		$control->add_responsive_control(
			'subtitle_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'subtitle_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'subtitle_space',
			[
				'label'		 =>	esc_html__( 'Bottom Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 70,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'hide_subtitle_responsive',
			[
				'label' => __( 'Hide On Mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		if ( !$repeater ) {
			$control->end_controls_section();
			$control->start_controls_section(
				'content_section',
				[
					'label' => __( 'Content', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'separator' => 'before',
				'label' => __( 'Typography', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .content-inner',
			]
		);
		
		$control->add_control(
			'content_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-content .content-inner' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'content_margin',
			[
				'label' => __( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'content_border',
			[
				'label' => __( 'Border', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'content_paddings',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'content_space',
			[
				'label'		 =>	esc_html__( 'Bottom Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 70,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-content .content-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'hide_description_responsive',
			[
				'label' => __( 'Hide On Mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		if ( !$repeater ) {
			$control->end_controls_section();
			$control->start_controls_section(
				'image_section',
				[
					'label' => __( 'Image', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_control(
			'img_size',
			[
				'label' 	=>	__( 'Size', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
			]
		);
		
		// $control->add_control(
		// 	'img_size_dimension',
		// 	[
		// 		'label' => __( 'Image Dimension', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
		// 		'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'xstore-core' ),
		// 		'default' => [
		// 			'width' => '200',
		// 			'height' => '100',
		// 		],
		// 		'condition' => ['img_size' => 'custom'],
		// 	]
		// );
		
		$control->add_responsive_control(
			'img_min_size',
			[
				'label' => __( 'Image Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter image min height. Example in pixels: 200px', 'xstore-core' ),
				'selectors' => [
					'{{WRAPPER}} .banner img' => 'min-height: {{VALUE}} !important; object-fit: cover;',
				],
			]
		);
		
		$control->add_responsive_control(
			'img_object_fit_position',
			[
				'label' 	=> __( 'Image Position', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::SELECT,
				'default' 	=> '',
				'label_block'	=> 'true',
				'options' 	=> [
					'' => __( 'Default', 'xstore-core' ),
					'left top' => __( 'Left Top', 'xstore-core' ),
					'left center' => __( 'Left Center', 'xstore-core' ),
					'left bottom' => __( 'Left Bottom', 'xstore-core' ),
					'right top' => __( 'Right Top', 'xstore-core' ),
					'right center' => __( 'Right Center', 'xstore-core' ),
					'right bottom' => __( 'Right Bottom', 'xstore-core' ),
					'center top' => __( 'Center Top', 'xstore-core' ),
					'center center'  => __( 'Center Center', 'xstore-core' ),
					'center bottom' => __( 'Center Bottom', 'xstore-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .banner img' => 'object-position: {{VALUE}} !important;',
				],
			]
		);
		
		$control->start_controls_tabs( 'image_settings' );
		
		$control->start_controls_tab(
			'image_settings_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .banner img',
			]
		);
		
		$control->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => __( 'Enter value between 0.0 to 1 (0 is maximum transparency, while 1 is lowest)', 'xstore-core' ),
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01
					],
				],
			]
		);
		
		$control->add_control(
			'banner_color_bg',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'Use image opacity option to add overlay effect with background', 'xstore-core' ),
			]
		);
		
		$control->end_controls_tab();
		
		$control->start_controls_tab(
			'image_settings_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .banner:hover img',
			]
		);
		
		$control->add_control(
			'image_opacity_on_hover',
			[
				'label' => __( 'Opacity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => __( 'Enter value between 0.0 to 1 (0 is maximum transparency, while 1 is lowest)', 'xstore-core' ),
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01
					],
				],
			]
		);
		
		$control->end_controls_tab();
		
		$control->end_controls_tabs();
		
		if ( !$repeater ) {
			$control->end_controls_section();
			$control->start_controls_section(
				'button_section',
				[
					'label' => __( 'Button', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'button_typography',
				'label' 		=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .banner-content .button-wrap .banner-button',
				'separator'   	=> 'before',
			]
		);
		
		$control->start_controls_tabs( 'button_settings' );
		
		$control->start_controls_tab(
			'button_settings_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$control->add_control(
			'button_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner .banner-content .button-wrap .banner-button' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_control(
			'button_bg',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner .banner-content .button-wrap .banner-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$control->end_controls_tab();
		
		$control->start_controls_tab(
			'button_settings_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$control->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner .banner-content .button-wrap .banner-button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_control(
			'button_hover_bg',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner  .banner-content .button-wrap .banner-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$control->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .banner .banner-content .button-wrap .banner-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$control->end_controls_tabs();
		
		$control->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'label'     => esc_html__( 'Border', 'xstore-core' ),
				'selector'  => '{{WRAPPER}} .banner .banner-content .button-wrap .banner-button',
			]
		);
		
		$control->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content .button-wrap .banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'button_paddings',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .banner-content .button-wrap .banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'hide_button_responsive',
			[
				'label' => __( 'Hide On Mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		if ( !$repeater ) {
			$control->end_controls_section();
		}
	}

	/**
	 * get contact for 7 items
	 * @return items
	 */
	function get_contact_form_7() {
		if ( ! function_exists( 'wpcf7' ) ) {
			return array();
		}

		$options = array();

		$args = array(
			'post_type'         => 'wpcf7_contact_form',
			'posts_per_page'    => -1
		);

		$contact_forms = get_posts( $args );

		if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

			$i = 0;

			foreach ( $contact_forms as $post ) {	
				if ( $i == 0 ) {
					$options[0] = esc_html__( 'Select Contact Form', 'xstore-core' );
				}
				$options[ $post->ID ] = $post->post_title;
				$i++;
			}
		}

		return $options;
	}
	
	/**
	 * Facebook widgets function.
	 * @see ETC\App\Controllers\Elementor\General\Facebook_Comments
	 * @see ETC\App\Controllers\Elementor\General\Facebook_Embed
	 *
	 * @param string $type
	 * @return void
	 *
	 * @since 4.0.11
	 *
	 */
	public static function get_facebook_sdk_info($type = 'validate', $params = array()) {
		
		switch ($type) {
			case 'validate_sdk':
				$errors = [];
				
				if ( ! empty( $params['app_id'] ) ) {
					$response = wp_remote_get( 'https://graph.facebook.com/' . $params['app_id'] );
					
					if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) )
						$errors[] = __( 'Facebook App ID is not valid', 'xstore-core' );
				}
				else
					$errors[] = __( 'Facebook App ID is not set', 'xstore-core' );
				return $errors;
				break;
			case 'render_error':
				$params['error_type'] = isset($params['error_type']) ? $params['error_type'] : 'warning';
				return '<div class="elementor-panel-alert elementor-panel-alert-'.$params['error_type'].'">'. $params['error_content']. '</div>';
				break;
			case 'get_permalink':
				$post_id = get_the_ID();
				
				if ( isset( $params['settings']['url_format'] ) && 'pretty' === $params['settings']['url_format'] ) {
					return get_permalink( $post_id );
				}
				
				// Use plain url to avoid losing comments after change the permalink.
				return add_query_arg( 'p', $post_id, home_url() );
				break;
			case 'get_app_settings_url':
				$app_id = self::get_facebook_sdk_info('get_app_id');
				if ( $app_id ) {
					return sprintf( 'https://developers.facebook.com/apps/%d/settings/', $app_id );
				} else {
					return 'https://developers.facebook.com/apps/';
				}
				break;
			case 'get_app_id':
				// with backward compatibility for XStore theme
				return get_theme_mod('facebook_app_id', '');
				break;
		}
	}
	
	public static function get_lottie_settings($control, $condition = false) {
		if ( $condition ) {
			$control->start_controls_section(
				'section_lottie_general',
				[
					'label' => esc_html__( 'Lottie', 'xstore-core' ),
					'condition' => $condition
				]
			);
		}
		else {
			$control->start_controls_section(
				'section_lottie_general',
				[
					'label' => esc_html__( 'Lottie', 'xstore-core' ),
				]
			);
		}
		
		$control->add_control(
			'lottie_source',
			[
				'label' => esc_html__( 'Source File', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'media_file',
				'options' => [
					'media_file' => esc_html__( 'Media File', 'xstore-core' ),
					'external_url' => esc_html__( 'External URL', 'xstore-core' ),
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_source_external_url',
			[
				'label' => esc_html__( 'External URL', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'condition' => [
					'lottie_source' => 'external_url',
				],
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your URL', 'xstore-core' ),
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_source_json',
			[
				'label' => esc_html__( 'Upload JSON File', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'media_type' => 'application/json',
				'frontend_available' => true,
				'condition' => [
					'lottie_source' => 'media_file',
				],
			]
		);
		
		$control->add_responsive_control(
			'lottie_align',
			[
				'label' => esc_html__( 'Alignment', 'xstore-core' ),
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
				'prefix_class' => 'elementor%s-align-',
				'default' => 'center',
			]
		);
		
		$control->add_control(
			'lottie_link_to',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'xstore-core' ),
					'custom' => esc_html__( 'Custom URL', 'xstore-core' ),
				],
				'default' => 'none',
				'frontend_available' => true
			]
		);
		
		$control->add_control(
			'lottie_custom_link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Enter your URL', 'xstore-core' ),
				'condition' => [
					'lottie_link_to' => 'custom',
				],
				'dynamic' => [
					'active' => true,
				],
				'show_label' => false,
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_points',
			[
				'label' => __( 'Start/End Points', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'start' => 0,
						'end' => 100,
					],
					'unit' => '%',
				],
				'labels' => [
					esc_html__( 'Start', 'xstore-core' ),
					esc_html__( 'End', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_renderer',
			[
				'label' => __( 'Renderer', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'svg',
				'options' => [
					'svg' => __( 'SVG', 'xstore-core' ),
					'canvas' => __( 'Canvas', 'xstore-core' ),
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_play_speed',
			[
				'label' => __( 'Play Speed', 'xstore-core' ) . ' (x)',
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_loop',
			[
				'label' => __( 'Loop', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_pause_on_hover',
			[
				'label' => __( 'Pause On Hover', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_hover_area',
			[
				'label' => __( 'Hover Area', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'after',
				'default' => 'widget',
				'options' => [
					'widget' => __( 'Widget', 'xstore-core' ),
					'column' => __( 'Column', 'xstore-core' ),
					'section' => __( 'Section', 'xstore-core' ),
				],
				'condition' => [
					'lottie_pause_on_hover!' => ''
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'lottie_lazyload',
			[
				'label' => __( 'Lazy Loading', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		
		$control->end_controls_section();
		
		if ( $condition ) {
			$control->start_controls_section(
				'lottie_style',
				[
					'label' => __( 'Lottie', 'xstore-core' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => $condition
				]
			);
		}
		else {
			$control->start_controls_section(
				'lottie_style',
				[
					'label' => __( 'Lottie', 'xstore-core' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_responsive_control(
			'lottie_max_width',
			[
				'label' => __( 'Max Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--lottie-max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'separator_lottie_panel_style',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
		
		$control->start_controls_tabs( 'lottie_image_effects' );
		
		$control->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$control->add_control(
			'lottie_opacity',
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
					'{{WRAPPER}}' => '--lottie-opacity: {{SIZE}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'lottie_css_filters',
				'selector' => '{{WRAPPER}} .etheme-lottie',
			]
		);
		
		// Normal.
		$control->end_controls_tab();
		
		$control->start_controls_tab( 'lottie_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$control->add_control(
			'lottie_opacity_hover',
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
					'{{WRAPPER}}' => '--lottie-opacity-hover: {{SIZE}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'lottie_css_filters_hover',
				'selector' => '{{WRAPPER}} .etheme-lottie:hover',
			]
		);
		
		$control->add_control(
			'lottie_hover_transition',
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
					'{{WRAPPER}}' => '--lottie-transition-duration-hover: {{SIZE}}s',
				],
			]
		);
		
		$control->end_controls_tab();
		
		$control->end_controls_tabs();
		
		$control->end_controls_section();
	}

	// new universal slider functions
	public static function get_slider_general_settings($control, $condition = false) {
		
		$items_per_view = range( 1, 10 );
		$items_per_view = array_combine( $items_per_view, $items_per_view );
		
		if ( $condition ) {
			$control->start_controls_section(
				'section_slider',
				[
					'label' => esc_html__( 'Slider', 'xstore-core' ),
					'condition' => $condition
				]
			);
        }
		else {
			$control->start_controls_section(
				'section_slider',
				[
					'label' => esc_html__( 'Slider', 'xstore-core' ),
				]
			);
		}
		
		$control->add_responsive_control(
			'slides_per_view',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Slides Per View', 'xstore-core' ),
				'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $items_per_view,
				'frontend_available' => true,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--slides-per-view: {{VALUE}};', // for init slides width
				],
			]
		);
		
		$control->add_responsive_control(
			'slides_per_group',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Slides To Scroll', 'xstore-core' ),
				'description' => __( 'Set how many slides are scrolled per swipe.', 'xstore-core' ),
				'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $items_per_view,
				'frontend_available' => true,
			]
		);
		
		$control->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 20
				],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => 0
					],
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'slider_vertical_align',
			[
				'label' => __( 'Vertical Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'flex-start' => [
						'title' => __( 'Start', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'End', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper' => 'align-items: {{VALUE}}',
				],
			]
		);
		
		$control->add_control(
			'loop',
			[
				'label' => __('Infinity Loop', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default' => 'yes'
			]
		);
		
		$control->add_control(
			'autoplay',
			[
				'label' => __('Autoplay', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'autoplay_speed',
			[
				'label' => __('Autoplay Speed', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 10000,
				'step' => 5,
				'default' => 5000,
				'description' => __('Autoplay speed in milliseconds', 'xstore-core'),
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$control->add_control(
			'autoheight',
			[
				'label' => esc_html__( 'Auto Height', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause On Hover', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'pause_on_interaction',
			[
				'label' => __( 'Pause On Interaction', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'navigation',
			[
				'label' => esc_html__( 'Navigation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'default' => 'arrows',
				'options' => [
					'both' => esc_html__( 'Arrows and Dots', 'xstore-core' ),
					'arrows' => esc_html__( 'Arrows', 'xstore-core' ),
					'dots' => esc_html__( 'Dots', 'xstore-core' ),
					'none' => esc_html__( 'None', 'xstore-core' ),
				],
				'frontend_available' => true,
			]
		);
		
		$control->add_control(
			'arrows_header',
			[
				'label' => esc_html__( 'Arrows', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => ['both', 'arrows']
				],
			]
		);
		
		$control->add_control(
			'arrows_type',
			[
				'label' 		=> esc_html__( 'Type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'arrow' 	=>	esc_html__( 'Arrow', 'xstore-core' ),
					'archery' 	=>	esc_html__( 'Archery', 'xstore-core' ),
				],
				'default'	=> 'arrow',
				'condition' => [ 'navigation' => ['both', 'arrows'] ]
			]
		);
		
		$control->add_control(
			'arrows_style',
			[
				'label' 		=> esc_html__( 'Style', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'style-1' 	=>	esc_html__( 'Style 1', 'xstore-core' ),
					'style-2' 	=>	esc_html__( 'Style 2', 'xstore-core' ),
					'style-3' 	=>	esc_html__( 'Style 3', 'xstore-core' ),
					'style-4' 	=>	esc_html__( 'Style 4', 'xstore-core' ),
					'style-5' 	=>	esc_html__( 'Style 5', 'xstore-core' ),
					'style-6' 	=>	esc_html__( 'Style 6', 'xstore-core' ),
				],
				'default'	=> 'style-4',
				'condition' => [ 'navigation' => ['both', 'arrows'] ]
			]
		);
		
		$control->add_control(
			'arrows_position',
			[
				'label' 		=> esc_html__( 'Position', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'middle' 			=>	esc_html__( 'Middle Outside', 'xstore-core' ),
					'middle-inside' 	=>	esc_html__( 'Middle Center', 'xstore-core' ),
					'middle-inbox' 	=>	esc_html__( 'Middle Inside', 'xstore-core' ),
					'bottom' 			=>	esc_html__( 'Bottom', 'xstore-core' ),
				],
				'default'	=> 'middle',
				'condition' => [ 'navigation' => ['both', 'arrows'] ]
			]
		);
		
		$control->add_control(
			'arrows_position_style',
			[
				'label' 		=> esc_html__( 'Hover Style', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'arrows-hover' 	=>	esc_html__( 'Display On Hover', 'xstore-core' ),
					'arrows-always' 	=>	esc_html__( 'Always Display', 'xstore-core' ),
				],
				'default'		=> 'arrows-hover',
				'condition' => [
					'navigation' => ['both', 'arrows'],
					'arrows_position' => ['middle', 'middle-inside', 'middle-inbox']
				],
			]
		);
		
		$control->end_controls_section();
	}
	
	public static function get_slider_style_settings($control, $condition = false) {
		
		if ( $condition ) {
			$control->start_controls_section(
				'section_style_slider',
				[
					'label' => esc_html__( 'Slider', 'xstore-core' ),
					'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => $condition
				]
			);
		}
		else {
			$control->start_controls_section(
				'section_style_slider',
				[
					'label' => esc_html__( 'Slider', 'xstore-core' ),
					'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_control(
			'arrows_style_header',
			[
				'label' => esc_html__( 'Arrows', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'navigation' => ['both', 'arrows']
				],
			]
		);
		
		$control->add_responsive_control(
			'arrows_size',
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
					'navigation' => ['both', 'arrows']
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'arrows_space',
			[
				'label'		 =>	esc_html__( 'Navigation Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 200,
						'step' 	=> 1
					],
				],
				'condition' => [
					'navigation' => ['both', 'arrows'],
					'arrows_position' => 'bottom'
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'arrows_top_space',
			[
				'label'		 =>	esc_html__( 'Navigation Top Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 200,
						'step' 	=> 1
					],
				],
				'condition' => [
					'navigation' => ['both', 'arrows'],
					'arrows_position' => 'bottom'
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-entry' => '--arrow-top-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->start_controls_tabs('arrows_style_tabs', [
			'condition' => [
				'navigation' => ['both', 'arrows'],
			]
		]);
		
		$control->start_controls_tab( 'arrows_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$control->add_control(
			'arrows_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .et-swiper-elementor-nav' => 'color: {{VALUE}};',
				]
			]
		);
		
		$control->add_control(
			'arrows_bg_color',
			[
				'label' 	=> esc_html__( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .et-swiper-elementor-nav' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$control->add_control(
			'arrows_br_color',
			[
				'label' 	=> esc_html__( 'Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .et-swiper-elementor-nav' => 'border-color: {{VALUE}};',
				]
			]
		);
		
		$control->end_controls_tab();
		
		$control->start_controls_tab(
			'arrow_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$control->add_control(
			'arrow_color_hover',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .et-swiper-elementor-nav:hover' => 'color: {{VALUE}};',
				]
			]
		);
		
		$control->add_control(
			'arrows_bg_color_hover',
			[
				'label' 	=> esc_html__( 'Background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .et-swiper-elementor-nav:hover' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$control->add_control(
			'arrows_br_color_hover',
			[
				'label' 	=> esc_html__( 'Border', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-entry .et-swiper-elementor-nav:hover' => 'border-color: {{VALUE}};',
				]
			]
		);
		
		$control->end_controls_tab();
		$control->end_controls_tabs();
		
		$control->add_control(
			'arrows_responsive_description',
			[
				'raw' => esc_html__( 'Responsive visibility will take effect only on preview or live page, and not while editing in Elementor.', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'navigation' => ['both', 'arrows']
				],
			]
		);
		
		$control->add_control(
			'arrows_hide_desktop',
			[
				'label'         =>  esc_html__( 'Hide Desktop', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'navigation' => ['both', 'arrows']
				],
			]
		);
		
		$control->add_control(
			'arrows_hide_mobile',
			[
				'label'         =>  esc_html__( 'Hide Mobile', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'navigation' => ['both', 'arrows']
				],
			]
		);
		
		$control->add_control(
			'navigation_style_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'navigation' => ['both']
				]
			]
		);
		
		$control->add_control(
			'dots_style_header',
			[
				'label' => esc_html__( 'Pagination', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->start_controls_tabs( 'dots_colors' );
		
		$control->start_controls_tab( 'dots_color_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->add_control(
			'dots_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}; opacity: 1;',
				],
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->end_controls_tab();
		
		$control->start_controls_tab( 'dots_color_active',
			[
				'label' => __( 'Active', 'xstore-core' ),
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->add_control(
			'dots_active_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->end_controls_tab();
		
		$control->end_controls_tabs();
		
		$control->add_control(
			'dots_responsive_description',
			[
				'raw' => esc_html__( 'Responsive visibility will take effect only on preview or live page, and not while editing in Elementor.', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->add_control(
			'dots_hide_desktop',
			[
				'label'         =>  esc_html__( 'Hide Desktop', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->add_control(
			'dots_hide_mobile',
			[
				'label'         =>  esc_html__( 'Hide Mobile', 'xstore-core' ),
				'type'          =>  \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'navigation' => ['both', 'dots']
				],
			]
		);
		
		$control->end_controls_section();
	}
	
	public static function get_slider_pagination($class, $settings, $edit_mode) {
		
		$class->add_render_attribute( 'dots', [
			'class' => [
				'swiper-pagination',
				$settings['dots_hide_desktop'] && !$edit_mode ? 'elementor-hidden-desktop' : '',
				$settings['dots_hide_mobile'] && !$edit_mode ? 'elementor-hidden-mobile' : ''
			]
		] );
		
		if ( isset($settings['dots_position']) && $settings['dots_position'] == 'inside') {
			$class->add_render_attribute( 'dots', [
				'class' => [
					'swiper-pagination-inside',
                ]
            ]);
        }
		
		echo '<div ' . $class->get_render_attribute_string( 'dots' ) . '></div>';
	}
	
	public static function get_slider_navigation($settings, $edit_mode) {
	 
		$arrows_class = 'et-swiper-elementor-nav';
		
		if ( !$edit_mode ) {
			if ( $settings['arrows_hide_desktop'] ) {
				$arrows_class .= ' elementor-hidden-desktop';
			}
			
			if ( $settings['arrows_hide_mobile'] ) {
				$arrows_class .= ' elementor-hidden-mobile';
			}
		}
		
		$arrows_class_left  = 'swiper-custom-left ' . $arrows_class;
		$arrows_class_right = 'swiper-custom-right ' . $arrows_class;
		
		$arrows_class_left .= ' type-' . $settings['arrows_type'] . ' ' . $settings['arrows_style'];
		$arrows_class_right .= ' type-' . $settings['arrows_type'] . ' ' . $settings['arrows_style'];
		
		if ( $settings['arrows_position'] == 'bottom' )
			echo '<div class="swiper-navigation">';
		?>
		<div class="swiper-button-prev <?php echo $arrows_class_left; ?>"></div>
		<div class="swiper-button-next <?php echo $arrows_class_right; ?>"></div>
		<?php
		if ( $settings['arrows_position'] == 'bottom' )
			echo '</div>';
	}
	
	/**
	 * Function to limit any string chars limit.
	 *
	 * @param $string
	 * @param $limit
	 * @return mixed
	 *
	 * @since 4.1
	 *
	 */
	public static function limit_string_by_chars($string, $limit) {
		$rendered_string = unicode_chars(strip_tags($string));
		
		if ( strlen( $rendered_string ) > $limit ) {
			$split         = preg_split( '/(?<!^)(?!$)/u', $rendered_string );
			$rendered_string = ( $limit != '' && $limit > 0 && ( count( $split ) >= $limit ) ) ? '' : $rendered_string;
			if ( $rendered_string == '' ) {
				for ( $i = 0; $i < $limit; $i ++ ) {
					$rendered_string .= $split[ $i ];
				}
				$rendered_string .= '...';
			}
		}
		
		return $rendered_string;
	}
	
	/**
	 * Function to limit any string by words limit.
	 *
	 * @param $string
	 * @param $limit
	 * @return mixed
	 *
	 * @since 4.1
	 *
	 */
	public static function limit_string_by_words($string, $limit) {
		if ( strlen( $string ) > 0 ) {
			$string = wp_trim_words( $string, $limit, '...' );
			return apply_filters( 'wp_trim_excerpt', $string, $string );
		} else {
			return $string;
		}
	}
	
	public static function get_countdown_settings($control, $condition = []) {
	    
        $control->start_controls_section(
            'section_countdown',
            [
                'label' => esc_html__( 'Countdown', 'xstore-core' ),
            ]
        );
		
		$control->add_control(
			'countdown_stretch_items',
			[
				'label' 		=> esc_html__( 'Stretch items', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => 'flex: 1;',
				],
                'condition' => $condition
			]
		);
		
		$control->add_responsive_control(
			'countdown_align',
			[
				'label' => esc_html__( 'Horizontal Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'' => esc_html__( 'Default', 'xstore-core' ),
					'flex-start' => esc_html__( 'Start', 'xstore-core' ),
					'center' => esc_html__( 'Center', 'xstore-core' ),
					'flex-end' => esc_html__( 'End', 'xstore-core' ),
					'space-between' => esc_html__( 'Space Between', 'xstore-core' ),
					'space-around' => esc_html__( 'Space Around', 'xstore-core' ),
					'space-evenly' => esc_html__( 'Space Evenly', 'xstore-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => 'justify-content: {{VALUE}}',
				],
				'condition' => array_merge(array(
					    'countdown_stretch_items!' => 'yes',
                    ),
					$condition
                )
			]
		);
		
		$control->add_control(
			'countdown_label_position',
			[
				'label' 		=>	__( 'Label Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default'		=> 'bottom',
                'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_add_delimiter',
			[
				'label' 		=> esc_html__( 'Add Delimiter', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
                'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_delimiter',
			[
				'label' 		=>	__( 'Delimiter', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => ':',
				'condition' => array_merge(array(
					    'countdown_add_delimiter' => 'yes',
                    ),
                    $condition
                ),
			]
		);
		
		$control->add_control(
			'show_empty_counter',
			[
				'label' 		=>	__( 'Show 00 Counter', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'frontend_available' => true,
				'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_show_days',
			[
				'label' 		=> esc_html__( 'Show Days', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_show_hours',
			[
				'label' 		=> esc_html__( 'Show Hours', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
                'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_show_minutes',
			[
				'label' 		=> esc_html__( 'Show Minutes', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
                'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_show_seconds',
			[
				'label' 		=> esc_html__( 'Show Seconds', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
                'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_show_labels',
			[
				'label' 		=> esc_html__( 'Show Labels', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'condition' => $condition
			]
		);
		
		$control->add_control(
			'countdown_custom_labels',
			[
				'label' 		=> esc_html__( 'Custom Labels', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' => array_merge(array(
                        'countdown_show_labels' => 'yes'
                    ),
                    $condition
                ),
			]
		);
		
		$control->add_control(
			'countdown_label_days',
			[
				'label' 		=>	__( 'Label Days', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Days', 'xstore-core' ),
				'condition' => array_merge(array(
					'countdown_show_labels!' => '',
					'countdown_custom_labels!' => '',
					'countdown_show_days' => 'yes',
                ), $condition),
			]
		);
		
		$control->add_control(
			'countdown_label_hours',
			[
				'label' 		=>	__( 'Label Hours', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Hours', 'xstore-core' ),
				'condition' => array_merge(array(
					'countdown_show_labels!' => '',
					'countdown_custom_labels!' => '',
					'countdown_show_hours' => 'yes',
				), $condition),
			]
		);
		
		$control->add_control(
			'countdown_label_minutes',
			[
				'label' 		=>	__( 'Label Minutes', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Minutes', 'xstore-core' ),
				'condition' => array_merge(array(
					'countdown_show_labels!' => '',
					'countdown_custom_labels!' => '',
					'countdown_show_minutes' => 'yes',
				), $condition),
			]
		);
		
		$control->add_control(
			'countdown_label_seconds',
			[
				'label' 		=>	__( 'Label Seconds', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Seconds', 'xstore-core' ),
				'condition' => array_merge(array(
					'countdown_show_labels!' => '',
					'countdown_custom_labels!' => '',
					'countdown_show_seconds' => 'yes',
				), $condition),
			]
		);
		
		$control->end_controls_section();
		
		if ( $condition ) {
			$control->start_controls_section(
				'section_countdown_style',
				[
					'label' => esc_html__( 'Countdown', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => $condition
				]
			);
		}
		else {
			$control->start_controls_section(
				'section_countdown_style',
				[
					'label' => esc_html__( 'Countdown', 'xstore-core' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
		}
		
		$control->add_responsive_control(
			'countdown_gap',
			[
				'label' => __( 'Items Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => '--gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'countdown_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-wrapper' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'countdown_item_heading',
			[
				'label' => __( 'Items Settings', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$control->add_responsive_control(
			'countdown_inner_gap',
			[
				'label' => __( 'Items Inner Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => '--inner-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'countdown_border',
				'label' => esc_html__('Items Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-countdown-item',
			]
		);
		
		$control->add_control(
			'countdown_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'countdown_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-countdown-item'
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'countdown_shadow',
				'selector' => '{{WRAPPER}} .etheme-countdown-item',
				'separator' => 'before',
			]
		);
		
		$control->add_responsive_control(
			'countdown_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'countdown_min_width',
			[
				'label' => __( 'Min Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'vw' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 200,
						'step' 	=> 1
					],
					'vh' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'vw' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => '--item-min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_responsive_control(
			'countdown_min_height',
			[
				'label' => __( 'Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'vw' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 200,
						'step' 	=> 1
					],
					'vh' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'vw' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => '--item-min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$control->add_control(
			'heading_digits',
			[
				'label' => __( 'Digits', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$control->add_control(
			'digits_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'digits_typography',
				'selector' => '{{WRAPPER}} .etheme-countdown-digits',
			]
		);
		
		$control->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'countdown_show_labels!' => ''
				]
			]
		);
		
		$control->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'countdown_show_labels!' => ''
				]
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .etheme-countdown-label',
				'condition' => [
					'countdown_show_labels!' => ''
				]
			]
		);
		
		$control->add_control(
			'delimiter_label',
			[
				'label' => __( 'Delimiter', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'countdown_add_delimiter!' => ''
				]
			]
		);
		
		$control->add_control(
			'delimiter_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-delimiter' => 'color: {{VALUE}};',
				],
				'condition' => [
					'countdown_add_delimiter!' => ''
				]
			]
		);
		
		$control->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'delimiter_typography',
				'selector' => '{{WRAPPER}} .etheme-countdown-delimiter',
				'condition' => [
					'countdown_add_delimiter!' => ''
				]
			]
		);
		
		$control->end_controls_section();
	}
}
