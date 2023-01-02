<?php
/**
 * Description
 *
 * @package    custom-masonry.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes;

/**
 * Custom masonry widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Custom_Posts_Masonry extends \Elementor\Widget_Base {
	
	use Elementor;
	
	/**
	 * Get widget name.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'et-custom-posts-masonry';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Posts by Layout', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-masonry-post';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'custom', 'masonry', 'isotope', 'posts', 'query', 'post type', 'grid', 'blog', 'layout' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 3.1.0
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
		return ['etheme-elementor-custom-masonry'];
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
	 * Register contact form 7 widget controls.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
	 
		$this->start_controls_section(
			'settings',
			[
				'label'                 => __( 'General', 'xstore-core' ),
			]
		);
			
        $this->add_control(
            'layout',
            [
                'label'       => esc_html__( 'Layout', 'xstore-core' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => array(
                    '1' => esc_html__( 'Type 01', 'xstore-core' ),
                    '2' => esc_html__( 'Type 02', 'xstore-core' ),
                    '3' => esc_html__( 'Type 03', 'xstore-core' ),
                    '4' => esc_html__( 'Type 04', 'xstore-core' ),
                    '5' => esc_html__( 'Type 05', 'xstore-core' ),
                ),
                'default'     => '1',
            ]
        );
		
		$this->add_control(
			'auto_rows_columns',
			[
				'label' 		=> esc_html__( 'Autofill', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Fills empty items with stretch existing ones.', 'xstore-core' ),
				'return_value' 	=> 'true',
				'default' 		=> '',
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry' => 'grid-template-columns: auto; grid-template-rows: auto;',
				],
			]
		);
		
		$this->add_control(
			'name',
			[
				'label' 		=> esc_html__( 'Name', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'name_limit',
			[
				'label'		 =>	esc_html__( 'Name limit', 'xstore-core' ),
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
					'size' => 20,
				],
				'condition' => ['name' => 'true']
			]
		);
		
		$this->add_control(
			'date',
			[
				'label' 		=> esc_html__( 'Date', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'separator' => 'before',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'time',
			[
				'label' 		=> esc_html__( 'Time', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'author',
			[
				'label' 		=> esc_html__( 'Author', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'views_counter',
			[
				'label' 		=> esc_html__( 'Views Counter', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'comments_counter',
			[
				'label' 		=> esc_html__( 'Comments Counter', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'meta_separator',
			[
				'label' 	=> __( 'Separator', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' => '/'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'thumbnail',
				'separator' => 'none',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'tabs_elements_settings',
			[
				'label' => esc_html__('Items', 'xstore-core'),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'post', [
				'label' 		=>	__( 'Post', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'multiple' 	=>	false,
				'options' 		=> Elementor::get_post_pages(),
			]
		);
		
		$this->add_control(
			'masonry_post_tab',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
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
					'{{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="zoom-in"]:hover .grid-img,
                    {{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="zoom-out"]:not(:hover) .grid-img,
                    {{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="border-in"]:not(:hover) .grid-img' => 'transform: scale({{SIZE}});',
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
					'{{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="ltr"] .grid-img,
                    {{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="rtl"] .grid-img' => 'width: calc(100% + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="rtl"]:not(:hover) .grid-img' => 'transform: translateX(-{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="ltr"]:not(:hover) .grid-img' => 'transform: translateX({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .etheme-custom-masonry .et-masonry-item[data-hover="ltr"] .grid-img' => 'left: -{{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .etheme-custom-masonry .et-masonry-item .grid-img' => 'transition-duration: {{SIZE}}s',
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
				'selector' => '{{WRAPPER}} .et-masonry-item[data-hover="border-in"] > a:after',
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
					'{{WRAPPER}} .et-masonry-item[data-overlay] > a:before' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .et-masonry-item[data-overlay]:hover > a:before' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'content_hover',
			[
				'label'       => esc_html__( 'Content Effect', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'reveal' => esc_html__( 'Reveal', 'xstore-core' ),
					'none' => esc_html__( 'None', 'xstore-core' ),
				),
				'default'     => 'reveal',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'gap',
			[
				'label'		 =>	esc_html__( 'Items Gap', 'xstore-core' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry' => 'grid-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme-custom-masonry + .etheme-custom-masonry' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'height',
			[
				'label'		 =>	esc_html__( 'Min Height', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 1000,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' =>   300,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry' => '--min-height: {{SIZE}}{{UNIT}};',
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
		
		$this->add_control(
			'content_elements_spacing',
			[
				'label'		 =>	esc_html__( 'Items Spacing', 'xstore-core' ),
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
					'size' =>  5,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner > *:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'content_position_h',
			[
				'label' 		=>	__( 'Horizontal Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default'		=> 'center',
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box' => 'align-items: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'content_position_v',
			[
				'label' 		=>	__( 'Vertical Position', 'xstore-core' ),
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
				'default'		=> 'center',
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box' => 'justify-content: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'align',
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
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner' => 'text-align: {{VALUE}};',
				]
			]
		);
		
		$this->start_controls_tabs('content_bg_style_tabs');
		
		$this->start_controls_tab( 'content_bg_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .et-masonry-item .info-box-inner'
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'content_bg_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_hover',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .et-masonry-item:hover .info-box-inner'
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__('Border Radius', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				]
			]
		);
		
		$this->add_control(
			'content_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'name_style_section',
			[
				'label' => esc_html__( 'Name', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
                    'name' => 'true'
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => esc_html__('Name', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-custom-masonry .info-box-inner .item-name',
			]
		);
		
		$this->start_controls_tabs( 'name_colors' );
		
		$this->start_controls_tab( 'name_colors_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'name_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner .item-name a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'name_colors_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'name_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner .item-name a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'meta_style_section',
			[
				'label' => esc_html__( 'Post Meta', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => esc_html__('Post Meta', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-custom-masonry .info-box-inner .meta-post',
			]
		);
		
		$this->start_controls_tabs( 'meta_colors' );
		
		$this->start_controls_tab(
			'meta_colors_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'meta_color',
			[
				'label'     => __( 'Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner .meta-post' => 'color: {{VALUE}};',
				],
                'default' => '#888888'
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'meta_colors_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'meta_color_hover',
			[
				'label'     => __( 'Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-custom-masonry .info-box-inner .meta-post a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Register custom masonry widget controls.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$skeleton = false;
		$structure = $settings['layout'];
		$post_type = 'post';
		$tabs = $settings['masonry_' . $post_type . '_tab'];
		$limit = 5;
		$date_format = get_option( 'date_format' );
        $time_format = get_option( 'time_format' );
		switch ($structure) {
            case '1':
			case '2':
                $limit = 5;
            break;
			case '3':
			case '4':
				$limit = 4;
            break;
            case '5':
				$limit = 6;
				break;
        }
        
        if ( !$tabs ) {
            $tabs = array();
	        for ($j = 1; $j <= $limit; $j++) {
		        $tabs[$j] = $j;
	        }
	        $skeleton = true;
	    }

        $title_limit = $settings['name'] ? $settings['name_limit']['size'] : 0;
		
		if ( $tabs ) {
		    $i = 0;?>
            <div class="etheme-custom-masonry<?php echo ( $skeleton ) ? ' skeleton' : ''; ?>" data-type="<?php echo $structure; ?>">
                <?php foreach ( $tabs as $tab ) {
                    $image_hover = $settings['image_hover'];
                    if ( $settings['image_hover'] == 'random' ) {
	                    $image_hover_array = array(
		                    'zoom-in',
		                    'zoom-out',
		                    'rtl',
		                    'ltr',
		                    'border-in'
	                    );
				        $image_hover = $image_hover_array[array_rand($image_hover_array, 1)];
                    }
                    
				    $content_hover = $settings['content_hover'];
                    $i++;
                    if ( $i > $limit) { $i = 1;?>
                        </div>
                        <div class="etheme-custom-masonry" data-type="<?php echo $structure; ?>">
                    <?php } ?>
                    <div class="et-masonry-item grid-<?php echo $i; ?>" data-hover="<?php echo $image_hover; ?>" data-content-hover="<?php echo $content_hover; ?>"
                    <?php
                    if ( $settings['add_overlay']) {
                        echo 'data-overlay="true"';
                    } ?>>
                        <?php
	
                        if ( !$skeleton && $tab[ $post_type ] ) {
                            $tab['link'] = get_permalink($tab[$post_type]);
                            $tab['image_rendered'] = \Elementor\Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id( $tab[ $post_type ] ), 'image', $settings );
                        }

                        ?>
                        <a class="grid-img"<?php if ( isset($tab['link']) && $tab['link'] ) : ?> href="<?php echo esc_url($tab['link']); ?>"<?php endif; ?>
                           <?php if ( isset($tab['image_rendered']) && $tab['image_rendered'] ) : ?>style="background-image: url(' <?php echo $tab['image_rendered']; ?> ');" <?php endif; ?>
                            <?php echo ( $skeleton ) ? 'data-number="'.$i.'"' : ''; ?>></a>
                        
                        <?php
                            $content = array();
                            $meta = array();
                            if ( !$skeleton && $tab[$post_type] ) {
	
	                            if ( $settings['name'] ) {
		                            $content['name'] = get_the_title( $tab[ $post_type ] );
	                            }
	
	                            if ( $settings['date'] ) :
		                            ob_start(); ?>
                                    <time class="entry-date published updated"
                                          datetime="<?php echo get_the_time( 'F j, Y', $tab[ $post_type ] ); ?>"><?php echo get_the_time( $date_format, $tab[ $post_type ] ); ?></time>
		                            <?php $meta['date'] = ob_get_clean();
	                            endif;
	
	                            if ( $settings['time'] ) :
		                            ob_start();
		                            echo esc_html__( 'at', 'xstore-core' ) . ' ' . get_the_time( $time_format, $tab[ $post_type ] );
		                            $meta['time'] = ob_get_clean();
	                            endif;
	
	                            if ( $settings['author'] ) :
		                            ob_start();
		                            $author_id = get_post_field( 'author', $tab[ $post_type ] ); ?>
                                    <a href="<?php echo get_author_posts_url( $author_id ); ?>"><?php echo esc_html__( 'by', 'xstore-core' ) . ' ';
			                            the_author_meta( 'display_name', $author_id ); ?></a>
		                            <?php $meta['author'] = ob_get_clean();
	                            endif;
	
	                            if ( $settings['views_counter'] ):
		                            ob_start();
	                                if ( function_exists('etheme_get_views')) {
		                                etheme_get_views( $tab[ $post_type ], true );
	                                }
		                            $meta['views_counter'] = ob_get_clean();
	                            endif;
	
	                            if ( $settings['comments_counter'] && comments_open( $tab[ $post_type ] ) && ! post_password_required( $tab[ $post_type ] ) ):
                                    $meta['comments_counter'] = '<a href="'.get_permalink( $tab[ $post_type ] ).'" class="post-comments-count"><span>'.get_comments_number( $tab[ $post_type ] ).'</span></a>';
	                            endif;
	
	                            if ( count( $meta ) ) {
		                            $content['meta'] = '<div class="meta-post">' . implode( '<span class="meta-divider"> ' . $settings['meta_separator'] . ' </span>', $meta ) . '</div>';
	                            }
                            }

                            if ( count($content) ) {
                                if ( isset($content['name']) ) {
	                                if ( $title_limit > 0 && strlen($content['name']) > $title_limit) {
		                                $split = preg_split('/(?<!^)(?!$)/u', $content['name']);
		                                $content['name'] = ($title_limit != '' && $title_limit > 0 && (count($split) >= $title_limit)) ? '' : $content['name'];
		                                if ( $content['name'] == '' ) {
			                                for ($k=0; $k < $title_limit; $k++) {
				                                $content['name'] .= $split[$k];
			                                }
			                                $content['name'] .= '...';
		                                }
                                    }
	                                if ( $tab['link'] )
		                                $content['name'] = '<a href="'.$tab['link'].'">' . $content['name'] . '</a>';
	
	                                $content['name'] = '<h2 class="item-name">' . $content['name'] . '</h2>';
                                }
                                echo '<div class="info-box">';
                                    echo '<div class="info-box-inner">';
                                        foreach ( $content as $item ) {
                                            echo $item;
                                        }
                                    echo '</div>';
	                            echo '</div>';
                            }
                        ?>
                    </div>
                <?php } ?>
            </div>
        <?php }
		
	}
}