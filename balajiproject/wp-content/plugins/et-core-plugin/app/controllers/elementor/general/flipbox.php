<?php
namespace ETC\App\Controllers\Elementor\General;


/**
 * FlipBox widget.
 *
 * @since      4.0.6
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class FlipBox extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_flipbox';
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
		return __( 'FlipBox', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-flipbox';
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
		return [ 'flip', '3d', 'rotate', 'banner', 'box', 'icon', 'image', 'effect' ];
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
		return ['etheme-elementor-flipbox'];
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
			'section_side_a',
			[
				'label' => esc_html__( 'Front Side', 'xstore-core' ),
			]
		);
		
		$this->start_controls_tabs( 'side_a_tabs' );
		
		$this->start_controls_tab( 'side_a_tab', [ 'label' => __( 'Content', 'xstore-core' ) ] );
		
		$this->add_control(
			'graphic_element_a',
			[
				'label' => __( 'Graphic Element', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => __( 'None', 'xstore-core' ),
						'icon' => 'eicon-ban',
					],
					'image' => [
						'title' => __( 'Image', 'xstore-core' ),
						'icon' => 'eicon-image',
					],
					'icon' => [
						'title' => __( 'Icon', 'xstore-core' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'icon',
			]
		);
		
		$this->add_control(
			'image_a',
			[
				'label' => __( 'Choose Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'graphic_element_a' => 'image',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_a', // Actually its `image_size`
				'default' => 'thumbnail',
				'condition' => [
					'graphic_element_a' => 'image',
				],
			]
		);
		
		$this->add_control(
			'selected_icon_a',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_a',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element_a' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'title_a',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Front side heading', 'xstore-core' ),
				'placeholder' => __( 'Enter your title', 'xstore-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'description_a',
			[
				'label' => __( 'Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
				'placeholder' => __( 'Enter your description', 'xstore-core' ),
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
				'rows' => 10,
				'show_label' => false,
			]
		);
		
		$this->add_control(
			'button_a_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'default' => __('Button', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'link_a',
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
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'side_a_style_tab', [ 'label' => __( 'Style', 'xstore-core' ) ] );
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'side_a_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-flipbox-side_a',
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
					'color' => [
						'default' => '#f7f7f7'
					],
				],
			]
		);
		
		$this->add_control(
			'side_a_overlay',
			[
				'label' => __( 'Overlay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_a:before' => 'content: "";background-color: {{VALUE}};',
				],
				'condition' => [
					'side_a_background_image[id]!' => '',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_side_b',
			[
				'label' => esc_html__( 'Back Side', 'xstore-core' ),
			]
		);
		
		$this->start_controls_tabs( 'side_b_tabs' );
		
		$this->start_controls_tab( 'side_b_tab', [ 'label' => __( 'Content', 'xstore-core' ) ] );
		
		$this->add_control(
			'graphic_element_b',
			[
				'label' => __( 'Graphic Element', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => __( 'None', 'xstore-core' ),
						'icon' => 'eicon-ban',
					],
					'image' => [
						'title' => __( 'Image', 'xstore-core' ),
						'icon' => 'eicon-image',
					],
					'icon' => [
						'title' => __( 'Icon', 'xstore-core' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'icon',
			]
		);
		
		$this->add_control(
			'image_b',
			[
				'label' => __( 'Choose Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'graphic_element_b' => 'image',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_b', // Actually its `image_size`
				'default' => 'thumbnail',
				'condition' => [
					'graphic_element_b' => 'image',
				],
			]
		);
		
		$this->add_control(
			'selected_icon_b',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_b',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element_b' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'title_b',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Back side heading', 'xstore-core' ),
				'placeholder' => __( 'Enter your title', 'xstore-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'description_b',
			[
				'label' => __( 'Description', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xstore-core' ),
				'placeholder' => __( 'Enter your description', 'xstore-core' ),
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
				'rows' => 10,
				'show_label' => false,
			]
		);
		
		$this->add_control(
			'button_b_text',
			[
				'label' => __( 'Button Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'link_b',
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
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'side_b_style_tab', [ 'label' => __( 'Style', 'xstore-core' ) ] );
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'side_b_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-flipbox-side_b',
				'fields_options' => [
					'background' => [
						'default' => 'classic'
					],
					'color' => [
						'default' => '#1a1a1a'
					],
				],
			]
		);
		
		$this->add_control(
			'side_b_overlay',
			[
				'label' => __( 'Overlay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_b:before' => 'content: "";background-color: {{VALUE}};',
				],
				'condition' => [
					'side_b_background_image[id]!' => '',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_box_settings',
			[
				'label' => __( 'Settings', 'xstore-core' ),
			]
		);
		
		$this->add_responsive_control(
			'box_height',
			[
				'label' => __( 'Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
					'vh' => [
						'min' => 3,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
                    'unit' => 'px',
                    'size' => 5
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$flip_effects = [
			'flip' => 'Flip',
			'flip-2' => 'Flip Bounced',
			'slide' => 'Slide',
			'slide-2' => 'Slide 2',
			'overlay' => 'Overlay',
			'zoom-in' => 'Zoom In',
			'zoom-in-2' => 'Zoom In 2',
            'zoom-out' => 'Zoom out',
            'zoom-out-2' => 'Zoom out 2',
			'fade' => 'Fade',
			'random' => 'Random'
		];
		
		$this->add_control(
			'flip_effect',
			[
				'label' => __( 'Flip Effect', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => $flip_effects,
			]
		);
		
		$flip_directions = [
			'left' => __( 'Left', 'xstore-core' ),
			'right' => __( 'Right', 'xstore-core' ),
			'up' => __( 'Up', 'xstore-core' ),
			'down' => __( 'Down', 'xstore-core' ),
            'random' => __( 'Random', 'xstore-core' )
		];
		
		$this->add_control(
			'flip_direction',
			[
				'label' => __( 'Flip Direction', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'right',
				'options' => $flip_directions,
				'condition' => [
					'flip_effect' => [
                        'flip',
						'flip-2',
                        'slide',
						'slide-2',
                        'overlay',
                        'random',
					],
				],
			]
		);
		
		$this->add_control(
			'flip_3d',
			[
				'label' => __( '3D Depth', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'flip_effect' => ['flip', 'flip-2', 'random'],
				],
			]
		);
		
		$this->add_control(
			'transition_duration',
			[
				'label' => __( 'Transition Duration', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
                        'step' => 0.1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-wrapper' => '--transition-duration: {{SIZE}}s;',
				],
			]
		);
		
		$this->add_control(
			'transition_timing_function',
			[
				'label' => __( 'Transition Timing Function', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
                    'linear' => 'linear',
                    'ease' => 'ease',
                    'ease-in' => 'ease-in',
                    'ease-out' => 'ease-out',
                    'ease-in-out' => 'ease-in-out',
                    '' => esc_html__('Default', 'xstore-core'),
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-wrapper' => '--transition-timing-fn: {{VALUE}};',
				],
                'condition' => [
                    'flip_effect!' => 'flip-2'
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .etheme-flipbox-wrapper',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .etheme-flipbox-wrapper',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_side_a',
			[
				'label' => __( 'Front Side', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'side_a_alignment',
			[
				'label' 		=>	__( 'Horizontal Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary'  => [
					'left'          => 'start',
					'right'         => 'end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_a' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'side_a_v_align',
			[
				'label' 		=>	__( 'Vertical Align', 'xstore-core' ),
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
				'default' => 'center',
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_a' => 'align-items: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'side_a_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_image_a_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Image', 'xstore-core' ),
				'condition' => [
					'graphic_element_a' => 'image',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_a_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-image-a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_a' => 'image',
				],
			]
		);
		
		$this->add_control(
			'image_a_width',
			[
				'label' => __( 'Width', 'xstore-core' ) . ' (%)',
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-image-a img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element_a' => 'image',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_a_border',
				'selector' => '{{WRAPPER}} .etheme-flipbox-image-a img',
				'condition' => [
					'graphic_element_a' => 'image',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_a_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-image-a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_a' => 'image',
				],
			]
		);
		
		$this->add_control(
			'heading_icon_a_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Icon', 'xstore-core' ),
				'condition' => [
					'graphic_element_a' => 'icon',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'icon_a_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_a' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'icon_a_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'graphic_element_a' => 'icon',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-flipbox-icon-a svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_a_bg_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'graphic_element_a' => 'icon',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_a_size',
			[
				'label' => __( 'Icon Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-a' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme-flipbox-icon-a svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_a' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'icon_a_padding',
			[
				'label' => __( 'Icon Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-a' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'graphic_element_a' => 'icon',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_a_border',
				'selector' => '{{WRAPPER}} .etheme-flipbox-icon-a',
				'condition' => [
					'graphic_element_a' => 'icon',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'icon_a_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_a' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'heading_title_a_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Title', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'title_a!' => '',
				],
			]
		);
		
		$this->add_control(
			'title_a_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-title-a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_a!' => '',
				],
			]
		);
		
		$this->add_control(
			'title_a_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-title-a' => 'color: {{VALUE}}',
				
				],
				'condition' => [
					'title_a!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_a_typography',
				'selector' => '{{WRAPPER}} .etheme-flipbox-title-a',
				'condition' => [
					'title_a!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_description_a_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Description', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'description_a!' => '',
				],
			]
		);
		
		$this->add_control(
			'description_a_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-description-a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'description_a!' => '',
				],
			]
		);
		
		$this->add_control(
			'description_a_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-description-a' => 'color: {{VALUE}}',
				
				],
				'condition' => [
					'description_a!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_a_typography',
				'selector' => '{{WRAPPER}} .etheme-flipbox-description-a',
				'condition' => [
					'description_a!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_button_a_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Button', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'button_a_text!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(), [
				'name' => 'button_a_border',
				'selector' => '{{WRAPPER}} .etheme-flipbox-button-a',
				'condition' => [
					'button_a_text!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_a_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_a_text!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_a_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_a_text!' => '',
				],
			]
		);
		
		$this->start_controls_tabs( 'button_a_style', [
			'condition' => [
				'button_a_text!' => '',
			],
        ] );
		
		$this->start_controls_tab(
			'button_a_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_a_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_a_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'button_a_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_a_hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_a_background_hover_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_a_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_a_typography',
				'selector' => '{{WRAPPER}} .etheme-flipbox-button-a',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_side_b',
			[
				'label' => __( 'Back Side', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'side_b_alignment',
			[
				'label' 		=>	__( 'Horizontal Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary'  => [
					'left'          => 'start',
					'right'         => 'end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_b' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'side_b_v_align',
			[
				'label' 		=>	__( 'Vertical Align', 'xstore-core' ),
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
				'default' => 'center',
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_b' => 'align-items: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'side_b_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-side_b' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_image_b_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Image', 'xstore-core' ),
				'condition' => [
					'graphic_element_b' => 'image',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_b_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-image-b' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_b' => 'image',
				],
			]
		);
		
		$this->add_control(
			'image_b_width',
			[
				'label' => __( 'Width', 'xstore-core' ) . ' (%)',
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-image-b img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element_b' => 'image',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_b_border',
				'selector' => '{{WRAPPER}} .etheme-flipbox-image-b img',
				'condition' => [
					'graphic_element_b' => 'image',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_b_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-image-b img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_b' => 'image',
				],
			]
		);
		
		$this->add_control(
			'heading_icon_b_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Icon', 'xstore-core' ),
				'condition' => [
					'graphic_element_b' => 'icon',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'icon_b_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-b' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_b' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'icon_b_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'condition' => [
					'graphic_element_b' => 'icon',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-b' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-flipbox-icon-b svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_b_bg_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'graphic_element_b' => 'icon',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-b' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_b_size',
			[
				'label' => __( 'Icon Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-b' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme-flipbox-icon-b svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_b' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'icon_b_padding',
			[
				'label' => __( 'Icon Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-b' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'graphic_element_b' => 'icon',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_b_border',
				'selector' => '{{WRAPPER}} .etheme-flipbox-icon-b',
				'condition' => [
					'graphic_element_b' => 'icon',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'icon_b_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-icon-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element_b' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'heading_title_b_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Title', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'title_b!' => '',
				],
			]
		);
		
		$this->add_control(
			'title_b_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-title-b' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_b!' => '',
				],
			]
		);
		
		$this->add_control(
			'title_b_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-title-b' => 'color: {{VALUE}}',
				],
				'condition' => [
					'title_b!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_b_typography',
				'selector' => '{{WRAPPER}} .etheme-flipbox-title-b',
				'condition' => [
					'title_b!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_description_b_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Description', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'description_b!' => '',
				],
			]
		);
		
		$this->add_control(
			'description_b_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-description-b' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'description_b!' => '',
				],
			]
		);
		
		$this->add_control(
			'description_b_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-description-b' => 'color: {{VALUE}}',
				],
				'condition' => [
					'description_b!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_b_typography',
				'selector' => '{{WRAPPER}} .etheme-flipbox-description-b',
				'condition' => [
					'description_b!' => '',
				],
			]
		);
		
		$this->add_control(
			'heading_button_b_style',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Button', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'button_b_text!' => '',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(), [
				'name' => 'button_b_border',
				'selector' => '{{WRAPPER}} .etheme-flipbox-button-b',
				'separator' => 'before',
				'condition' => [
					'button_b_text!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_b_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_b_text!' => '',
				],
			]
		);
		
		$this->add_control(
			'button_b_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_b_text!' => '',
				],
			]
		);
		
		$this->start_controls_tabs( 'button_b_style', [
			'condition' => [
				'button_b_text!' => '',
			],
        ] );
		
		$this->start_controls_tab(
			'button_b_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_b_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_b_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'button_b_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_b_hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_b_background_hover_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_b_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-flipbox-button-b:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_b_typography',
				'selector' => '{{WRAPPER}} .etheme-flipbox-button-b',
				'condition' => [
					'button_b_text!' => '',
				],
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

        $this->add_render_attribute( 'wrapper', 'class', 'etheme-flipbox-wrapper' );

        $flip_effects = [ 'flip', 'flip-2', 'slide', 'slide-2', 'overlay', 'zoom-in', 'zoom-in-2', 'zoom-out', 'zoom-out-2', 'fade' ];
        
        $flip_effect = $settings['flip_effect'];
        
        if ( $flip_effect == 'random' ) {
            $flip_effect = $flip_effects[rand(0, count($flip_effects) - 1)];
        }
    
        switch ($flip_effect) {
            case 'flip-2':
                $flip_effect_class = 'flip etheme-flip-box-effect-flip-bounced';
                break;
            case 'slide-2':
                $flip_effect_class = 'slide etheme-flip-box-effect-slide-delayed';
                break;
            default:
                $flip_effect_class = $flip_effect;
        }
        
        $this->add_render_attribute( 'wrapper', 'class', 'etheme-flip-box-effect-'.$flip_effect_class );
        
        if ( in_array($flip_effect, array('flip', 'flip-2')) && $settings['flip_3d'] ) {
            $this->add_render_attribute( 'wrapper', 'class', 'etheme-flip-box-3d' );
        }
    
        if ( in_array($flip_effect, array( 'flip', 'flip-2', 'slide', 'slide-2', 'overlay', 'random' ) ) ) {
            $flip_directions = [ 'left', 'right', 'up', 'down' ];
            $flip_direction = $settings['flip_direction'];
            if ( $flip_direction == 'random' ) {
                $flip_direction = $flip_directions[rand(0, count($flip_directions) - 1)];
            }
            $this->add_render_attribute( 'wrapper', 'class', 'etheme-flip-box-direction-'.$flip_direction );
        }
    
        // side a
        $this->add_render_attribute( 'side_a', 'class', 'etheme-flipbox-side_a' );
    
        $this->add_render_attribute( 'icon_wrapper_a', 'class', ['elementor-icon', 'etheme-flipbox-icon', 'etheme-flipbox-icon-a'] );
        $this->add_render_attribute( 'image_wrapper_a', 'class', ['etheme-flipbox-image', 'etheme-flipbox-image-a'] );
        
        $this->add_render_attribute( 'title_a', 'class', ['etheme-flipbox-title', 'etheme-flipbox-title-a'] );
        $this->add_render_attribute( 'description_a', 'class', ['etheme-flipbox-description', 'etheme-flipbox-description-a'] );
        // button
        $this->add_render_attribute( 'button_a_text', 'class', [
            'elementor-button',
            'etheme-flipbox-button',
            'etheme-flipbox-button-a',
        ] );
        
        if ( ! empty( $settings['link_a']['url'] ) ) {
            $this->add_link_attributes( 'button_a_text', $settings['link_a'] );
        }
        
        // side b
        $this->add_render_attribute( 'side_b', 'class', 'etheme-flipbox-side_b' );
        
        $this->add_render_attribute( 'icon_wrapper_b', 'class', ['elementor-icon', 'etheme-flipbox-icon', 'etheme-flipbox-icon-b'] );
        $this->add_render_attribute( 'image_wrapper_b', 'class', ['etheme-flipbox-image', 'etheme-flipbox-image-b'] );
        
        $this->add_render_attribute( 'title_b', 'class', ['etheme-flipbox-title', 'etheme-flipbox-title-b'] );
        $this->add_render_attribute( 'description_b', 'class', ['etheme-flipbox-description', 'etheme-flipbox-description-b'] );
        // button
        $this->add_render_attribute( 'button_b_text', 'class', [
            'elementor-button',
            'etheme-flipbox-button',
            'etheme-flipbox-button-b',
        ] );
        
        if ( ! empty( $settings['link_b']['url'] ) ) {
            $this->add_link_attributes( 'button_b_text', $settings['link_b'] );
        }
    
        $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
    
        ?>

			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <div <?php $this->print_render_attribute_string( 'side_a' ); ?>>
                    <div class="etheme-flipbox-inner">
                        <?php switch ($settings['graphic_element_a']) {
                            case 'icon':
                                if ( ! empty( $settings['icon_a'] ) || ! empty( $settings['selected_icon_a'] ) ) : ?>
                                    <div <?php echo $this->get_render_attribute_string( 'icon_wrapper_a' ); ?>>
                                        <?php if ( ( empty( $settings['icon_a'] ) && $migration_allowed ) || isset( $settings['__fa4_migrated']['selected_icon_a'] ) ) :
                                            \Elementor\Icons_Manager::render_icon( $settings['selected_icon_a'] );
                                        else : ?>
                                            <i <?php echo $this->get_render_attribute_string( 'icon_a' ); ?>></i>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                endif;
                                break;
                            case 'image': ?>
                                    <div <?php echo $this->get_render_attribute_string( 'image_wrapper_a' ); ?>>
                                        <div class="etheme-flipbox-image">
                                            <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_a' ); ?>
                                        </div>
                                    </div>
                                <?php
                                break;
                            default;
                        }
                        
                        if ( !empty($settings['title_a']) ) {
                            ?>
                                <h3 <?php echo $this->get_render_attribute_string( 'title_a' ); ?>>
                                    <?php echo $settings['title_a']; ?>
                                </h3>
                            <?php
                        }

                        if ( !empty($settings['description_a']) ) {
	                        ?>
                            <div <?php $this->print_render_attribute_string( 'description_a' ); ?>>
		                        <?php echo $settings['description_a']; ?>
                            </div>
	                        <?php
                        }
                        
                        if ( ! empty( $settings['button_a_text'] ) ) : ?>
                        
                        <a <?php echo $this->get_render_attribute_string( 'button_a_text' ); ?>><?php echo $settings['button_a_text']; ?></a>
	                    
                        <?php endif; ?>
                    </div>
                </div>
                <div <?php $this->print_render_attribute_string( 'side_b' ); ?>>
                    <div class="etheme-flipbox-inner">
						<?php switch ($settings['graphic_element_b']) {
							case 'icon':
								if ( ! empty( $settings['icon_b'] ) || ! empty( $settings['selected_icon_b'] ) ) : ?>
                                    <div <?php echo $this->get_render_attribute_string( 'icon_wrapper_b' ); ?>>
										<?php if ( ( empty( $settings['icon_b'] ) && $migration_allowed ) || isset( $settings['__fa4_migrated']['selected_icon_b'] ) ) :
											\Elementor\Icons_Manager::render_icon( $settings['selected_icon_b'] );
										else : ?>
                                            <i <?php echo $this->get_render_attribute_string( 'icon_b' ); ?>></i>
										<?php endif; ?>
                                    </div>
								<?php
								endif;
								break;
							case 'image': ?>
                                <div <?php echo $this->get_render_attribute_string( 'image_wrapper_b' ); ?>>
                                    <div class="etheme-flipbox-image">
										<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_b' ); ?>
                                    </div>
                                </div>
								<?php
								break;
							default;
						}
						
						if ( !empty($settings['title_b']) ) {
							?>
                            <h3 <?php echo $this->get_render_attribute_string( 'title_b' ); ?>>
								<?php echo $settings['title_b']; ?>
                            </h3>
							<?php
						}
						
						if ( !empty($settings['description_b']) ) {
							?>
                            <div <?php $this->print_render_attribute_string( 'description_b' ); ?>>
								<?php echo $settings['description_b']; ?>
                            </div>
							<?php
						}
						
						if ( ! empty( $settings['button_b_text'] ) ) : ?>

                            <a <?php echo $this->get_render_attribute_string( 'button_b_text' ); ?>><?php echo $settings['button_b_text']; ?></a>
						
						<?php endif; ?>
                    </div>
                </div>
			</div>
		<?php
	}
}