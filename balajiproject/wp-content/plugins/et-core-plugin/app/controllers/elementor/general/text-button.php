<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Text button widget.
 *
 * @since      4.0.5
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Text_Button extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'text_button';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Text Button', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-text-button';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'text', 'button', 'animation', 'effect' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget style dependency.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return array|string[]
	 */
	public function get_style_depends() {
		return ['etheme-elementor-text-button'];
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
	 * @since 4.0.5
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_section',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'style',
			[
				'label' 		=>	__( 'Hover Effect', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'fill_ltr'	=>	__('Fill ltr', 'xstore-core'),
					'fill_rtl'	=>	__('Fill rtl', 'xstore-core'),
					'fill_bottom'	=>	__('Fill to bottom', 'xstore-core'),
					'fill_top'	=>	__('Fill to top', 'xstore-core'),
					'line'	=>	__('Aside line', 'xstore-core'),
					'circle'	=>	__('Circle background', 'xstore-core'),
					'underline'	=>	__('Underline', 'xstore-core'),
					'overline'	=>	__('Overline', 'xstore-core'),
					'none'	=>	__('None', 'xstore-core'),
				],
				'default'	  => 'fill_bottom',
			]
		);
		
		$this->add_control(
			'style_line_align',
			[
				'label' => __( 'Line Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'xstore-core' ),
					'right' => __( 'After', 'xstore-core' ),
				],
				'condition' => [
					'style' => 'line',
				],
			]
		);
		
		$this->add_control(
			'style_circle_proportion',
			[
				'label' => __( 'Circle Proportion', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 120,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fill-circle' => '--proportion: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'style' => 'circle',
				],
			]
		);
		
		$this->add_control(
			'style_border_type',
			[
				'label' => __( 'Border Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'solid' => __( 'Solid', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'dotted' => __( 'Dotted', 'xstore-core' ),
					'dashed' => __( 'Dashed', 'xstore-core' ),
					'groove' => __( 'Groove', 'xstore-core' ),
				],
				'condition' => [
					'style' => ['line', 'circle'],
				],
				'selectors' => [
					'{{WRAPPER}} .button-line' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .fill-circle:before' => 'border-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'style_border_width',
			[
				'label' => __( 'Border Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1
					],
				],
				'condition' => [
					'style' => ['line', 'circle', 'underline', 'overline'],
				],
				'selectors' => [
					'{{WRAPPER}} .button-line' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fill-circle:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fill-underline:before, {{WRAPPER}} .fill-overline:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'button_animation',
			[
				'label' 		=>	__( 'Animation Effects', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'zoom'	=>	__('Zoom In/Out', 'xstore-core'),
					'pulse' => __('Pulse', 'xstore-core'),
					'wobble-horizontal' => __('Wobble horizontal', 'xstore-core'),
					'none'	=>	__('None', 'xstore-core'),
				],
				'default'	  => 'none',
			]
		);
		
		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Click here', 'xstore-core' ),
				'placeholder' => __( 'Click here', 'xstore-core' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_responsive_control(
			'align',
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
					'justify' => [
						'title' => __( 'Justified', 'xstore-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);
		
		$this->add_responsive_control(
			'min_width',
			[
				'label' => __( 'Button Min Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1
					],
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_animation',
			[
				'label' => __( 'Icon Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'background_ltr',
				'options' => [
					'switch_side' => __( 'Switch position', 'xstore-core' ),
					'background_ltr' => __( 'Background LTR', 'xstore-core' ),
					'background_rtl' => __( 'Background RTL', 'xstore-core' ),
					'background_to_top' => __( 'Background to top', 'xstore-core' ),
					'background_to_bottom' => __( 'Background to bottom', 'xstore-core' ),
					'none' => __( 'None', 'xstore-core' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);
		
		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'xstore-core' ),
					'right' => __( 'After', 'xstore-core' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);
		
		$this->add_control(
			'icon_proportion',
			[
				'label'		 =>	esc_html__( 'Icon Size', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 50,
						'step' 	=> 1
					],
					'em' 		=> [
						'min' 	=> 0,
						'max' 	=> 10,
						'step' 	=> .1
					],
				],
				'default' => [
					'unit' => 'em',
//					'size' => 1.3,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_indent',
			[
				'label' => __( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button' => '--icon-indent: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#e1e1e1'
					],
				],
			]
		);

		$this->end_controls_tab();
//
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:hover:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover:focus svg' => 'fill: {{VALUE}};',
				],
                'default'=> '#ffffff'
			]
		);
		
		$this->add_control(
			'fill_hover_color',
			[
				'label' => __( 'Hover Animation Color', 'xstore-core' ),
				'description' => esc_html__('Overline, underline, circle colors', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => '--fill-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => __( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:hover:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
//
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_icon_style' );
		
		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-button-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_background_color',
			[
				'label' => __( 'Icon Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-button-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'icon_hover_color',
			[
				'label' => __( 'Icon Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover .elementor-button-icon, {{WRAPPER}} .elementor-button:focus .elementor-button-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_background_hover_color',
			[
				'label' => __( 'Icon Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover .elementor-button-icon, {{WRAPPER}} .elementor-button:focus .elementor-button-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'icon_dimensions',
			[
				'label' => __( 'Icon Min Height/Min Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 120,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-button-icon' => 'min-width: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_radius',
			[
				'label' => __( 'Icon Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 120,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-button-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
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
	 * @since 4.0.5
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'button_wrapper', 'class', 'etheme-button-wrapper' );
		
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'text_button', $settings['link'] );
		}
		
        if ( !in_array($settings['style'], array('line')) ) {
	        $this->add_render_attribute( 'text_button', 'class', 'fill-' . str_replace('fill_', '', $settings['style']) );
	        if ( !in_array($settings['style'], array('none')) ) {
		        $this->add_render_attribute( 'text_button', 'class', 'has-fill' );
	        }
        }
		
		$this->add_render_attribute( 'text_button', [
		        'class' => [
                    'etheme-text-button',
                    'elementor-button',
                ],
                'role' => 'button'
            ] );
		
		if ( $settings['button_animation'] != 'none' ) {
			$this->add_render_attribute( 'button_wrapper', [
			        'class' => [
                        'etheme-animated',
				        'etheme-animation-' . $settings['button_animation']
                    ]
            ] );
		}
		
		
		echo '<div ' . $this->get_render_attribute_string( 'button_wrapper' ). '>';
			echo '<a ' . $this->get_render_attribute_string( 'text_button' ) .'>';
				$this->render_text($settings);
			echo '</a>';
		echo '</div>';
  
	}
	
	/**
	 * Render button text.
	 *
	 * Render widget text.
	 *
	 * @since 4.0.5
	 * @access protected
	 */
	protected function render_text($settings) {
		
		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );
		
		if ( $settings['icon_align'] != '') {
			$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['icon_align'] );
		}
		if ( $settings['icon_animation'] != 'none' ) {
			$this->add_render_attribute( 'icon-align', 'class', 'animation-'.$settings['icon_animation'] );
		}
		
		if ( $settings['style'] == 'line' ) {
			$this->add_render_attribute( 'line', 'class', 'button-line' );
			$this->add_render_attribute( 'line', 'class', 'elementor-align-icon-' . $settings['style_line_align']);
        }
		
		$this->add_inline_editing_attributes( 'text', 'none' ); ?>

        <span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
            <?php
                if ( $settings['style'] == 'line' && $settings['style_line_align'] == 'left' ) {
                    ?>
                    <span <?php echo $this->get_render_attribute_string( 'line' ); ?>></span>
                    <?php
                }
            if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
				<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
                    <?php
                        \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                    ?>
                </span>
			<?php endif;
		   
            if ( $settings['style'] == 'line' && $settings['style_line_align'] == 'right' ) {
                ?>
                <span <?php echo $this->get_render_attribute_string( 'line' ); ?>></span>
                <?php
            }
            ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
        </span><?php
	}
	
	public function on_import( $element ) {
		return \Elementor\Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
	}
	
}
