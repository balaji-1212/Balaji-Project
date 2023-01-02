<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Image Comparison widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Image_Comparison extends \Elementor\Widget_Base {
 
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_image_comparison';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Image Comparison', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-image-comparison';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'before', 'after', 'image', 'compare', 'hover', 'mousemove', 'animation' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.12
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
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'imagesloaded', 'etheme_image_comparison' ];
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
		return [ 'etheme-elementor-image-comparison' ];
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
	 * @since 4.0.12
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
			'before_image',
			[
				'label'   => esc_html__( 'Before Image', 'xstore-core' ),
				'description' => esc_html__( 'Use same size image for before and after for better preview.', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
                    'url' => ET_CORE_URL . 'app/assets/img/widgets/image-comparison/before.jpeg'
				],
			]
		);
		
		$this->add_control(
			'after_image',
			[
				'label'   => esc_html__( 'After Image', 'xstore-core' ),
				'description' => esc_html__( 'Use same size image for before and after for better preview.', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => ET_CORE_URL . 'app/assets/img/widgets/image-comparison/after.jpeg'
				],
			]
		);
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'         => 'image_size',
				'label'        => __( 'Image Size', 'xstore-core' ),
				'default'      => 'full',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_settings',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'start_point',
			[
				'label' => __( 'Start Point (%)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1
					],
				],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'on_hover',
			[
				'label' => __( 'On Hover', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Show Labels', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'static' => __( 'Static', 'xstore-core' ),
					'on_hover' => __( 'On hover', 'xstore-core' ),
					'none' => __( 'None', 'xstore-core' ),
				],
				'default' => 'static',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'horizontal_labels_vertical_position',
			[
				'label' => __( 'Label Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'xstore-core' ),
					'bottom' => __( 'Bottom', 'xstore-core' ),
				],
				'default' => 'top',
				'condition' => [
					'show_labels!' => 'none'
				],
			]
		);
		
		$this->add_control(
			'before_label',
			[
				'label' 		=>	__( 'Before Label', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __('Before', 'xstore-core'),
				'condition' => [
					'show_labels!' => 'none'
				],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'after_label',
			[
				'label' 		=>	__( 'After Label', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __('After', 'xstore-core'),
				'condition' => [
					'show_labels!' => 'none'
				],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'show_labels_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'show_labels!' => 'none'
				],
			]
		);
		
		$this->add_control(
			'divider_type',
			[
				'label' => __( 'Divider Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'xstore-core' ),
					'circle' => __( 'Circle', 'xstore-core' ),
					'custom' => __( 'Custom', 'xstore-core' ),
				],
				'default' => 'default',
				'selectors_dictionary'  => [
					'default'          => '',
					'circle'          => '--divider-radius: 50%;',
					'custom'          => '',
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'add_overlay',
			[
				'label' => __( 'Add Overlay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'overlay',
			[
				'label' => __( 'Overlay Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}}' => '--overlay-color: {{VALUE}}',
				],
				'condition' => [
					'add_overlay!' => ''
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => __( 'Labels', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => 'none'
				]
			]
		);
		
		$this->add_responsive_control(
			'labels_vertical_offset',
			[
				'label' => __( 'Vertical Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--labels-v-offset: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'labels_horizontal_offset',
			[
				'label' => __( 'Horizontal Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'separator' => 'after',
				'size_units' => [ 'px', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--labels-h-offset: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        	=> 'labels_typography',
				'label'       	=> __( 'Typography', 'xstore-core' ),
				'selector'    	=> '{{WRAPPER}} .etheme-image-comparison-label',
			]
		);
		
		$this->add_control(
			'labels_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .etheme-image-comparison-label' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'labels_bg_color',
			[
				'label' 	=> __( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .etheme-image-comparison-label' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'labels_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-image-comparison-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'labels_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-image-comparison-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_divider',
			[
				'label' => __( 'Divider', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Active Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--divider-bg-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'divider_width_height',
			[
				'label' => __( 'Width/Height (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-width: {{SIZE}}{{UNIT}}; --divider-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'divider_type!' => 'custom'
				]
			]
		);
		
		$this->add_responsive_control(
			'divider_width',
			[
				'label' => __( 'Width (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1
					],
				],
				'default' => [
                    'size' => 30
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'divider_type' => 'custom'
				]
			]
		);
		
		$this->add_responsive_control(
			'divider_height',
			[
				'label' => __( 'Height (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1
					],
				],
				'default' => [
					'size' => 80
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'divider_type' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'divider_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
                    'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'divider_type' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'divider_line_heading',
			[
				'label' => esc_html__( 'Line', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'divider_line_width',
			[
				'label' => __( 'Width (px)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1
					],
				],
				'default' => [
					'size' => 2
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-line-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'divider_arrows_heading',
			[
				'label' => esc_html__( 'Arrows', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'divider_arrows_color',
			[
				'label' => __( 'Arrows Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--divider-arrows-color: {{VALUE}};'
				]
			]
		);
		
		$this->add_responsive_control(
			'divider_arrows_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-arrows-size: {{SIZE}}{{UNIT}};'
				]
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	public function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', [
			'class' => 'etheme-image-comparison'
		]);
		
		if ( $settings['show_labels'] != 'none' ) {
            $this->add_render_attribute( 'wrapper', [
                'class' => 'etheme-image-comparison-labels-'. $settings['horizontal_labels_vertical_position']
            ] );
		}
		
		?>
        
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <?php
                    echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'before_image' );
                    echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'after_image' );
                ?>
            </div>
        
        <?php
		
	}
	
}
