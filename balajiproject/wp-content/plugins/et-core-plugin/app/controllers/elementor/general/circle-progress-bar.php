<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Circle Progress Bar widget.
 *
 * @since      4.0.8
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Circle_Progress_Bar extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.8
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_circle_progress_bar';
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
		return __( 'Circle Progress Bar', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-circle-progress-bar';
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
		return [ 'progress', 'bar', 'circle', 'count', 'percent', 'number', 'time', 'sale', 'evergreen', 'woocommerce' ];
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
		return ['eight_theme_general'];
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
		return [ 'etheme_circle_progress_bar' ];
	}
	
	public function get_style_depends() {
		return [ 'etheme-elementor-circle-progress-bar' ];
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
	 * @since 4.0.8
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
			'progress_value',
			[
				'label' => __( 'Progress Value', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
                'default' => [
                    'size' => '75',
                    'unit' => 'px'
                ]
			]
		);
		
		$this->add_control(
			'content_settings_heading',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'percent_position',
			[
				'label' => __( 'Percent Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'middle'    => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'without' => [
						'title' => __( 'Without', 'xstore-core' ),
						'icon' => 'eicon-ban',
					],
				],
				'default' => 'middle',
			]
		);
		
		$this->add_control(
			'text_after',
			[
				'label' => __( 'Text After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'text_after_position',
			[
				'label' => __( 'Text After Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'middle'    => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'without' => [
						'title' => __( 'Without', 'xstore-core' ),
						'icon' => 'eicon-ban',
					],
				],
				'default' => 'bottom',
                'condition' => [
                    'text_after!' => ''
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'alignment',
			[
				'label'                 => __( 'Alignment', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::CHOOSE,
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'xstore-core' ),
						'icon'    => 'eicon-text-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'xstore-core' ),
						'icon'    => 'eicon-text-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'xstore-core' ),
						'icon'    => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary'  => [
					'left'          => '0 auto 0 0',
					'center'          => '0 auto',
					'right'          => '0 0 0 auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .etheme-progress-bar-wrapper'   => 'margin: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'max_width',
			[
				'label' => __( 'Max Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs('progress_colors_tabs');
		
		$this->start_controls_tab( 'progress_color_01_tab',
			[
				'label' => esc_html__('Normal', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'progress_color_01',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--progress-color-01: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'progress_color_02_tab',
			[
				'label' => esc_html__('Active', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'progress_color_02',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--progress-color-02: {{VALUE}}; --progress-color-2-opacity: 1;',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'progress_color_fill',
			[
				'label' => __( 'Fill Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--progress-color-fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'progress_lines_weight',
			[
				'label' => __( 'Lines Weight', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--progress-weight: {{SIZE}};',
				],
			]
		);
		
		$this->add_control(
			'progress_space',
			[
				'label' => __( 'Space Bottom', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--progress-space: {{SIZE}}{{UNIT}};',
				],
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'percent_position',
							'operator' => '==',
							'value'    => 'bottom'
						],
						[
							'name'     => 'text_after_position',
							'operator' => '==',
							'value'    => 'bottom'
						],
					]
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'percent_position',
							'operator' => '!=',
							'value'    => 'without'
						],
						[
							'name'     => 'text_after_position',
							'operator' => '!=',
							'value'    => 'without'
						],
						[
							'name'     => 'text_after',
							'operator' => '!=',
							'value'    => ''
						],
					]
				],
			]
		);
		
		$this->add_control(
			'content_space',
			[
				'label' => __( 'Elements Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--content-space: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
				        'percent_position' => 'middle',
				        'text_after!' => '',
				        'text_after_position' => 'middle',
                ]
			]
		);
		
		$this->add_control(
			'style_percentage_settings_heading',
			[
				'label' => __( 'Percentage', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
                'condition' => [
                    'percent_position!' => 'without'
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'percentage_typography',
				'selector' => '{{WRAPPER}} .etheme-progress-bar-value',
				'condition' => [
					'percent_position!' => 'without'
				]
			]
		);
		
		$this->add_control(
			'percentage_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-progress-bar-value' => 'color: {{VALUE}};',
				],
				'condition' => [
					'percent_position!' => 'without'
				]
			]
		);
		
		$this->add_control(
			'separator_style_text_after',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'percent_position!' => 'without',
					'text_after!' => '',
					'text_after_position!' => 'without',
				]
			]
		);
		
		$this->add_control(
			'style_text_after_settings_heading',
			[
				'label' => __( 'Text After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
                    'text_after!' => '',
                    'text_after_position!' => 'without',
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_after_typography',
				'selector' => '{{WRAPPER}} .etheme-progress-bar-text',
				'condition' => [
					'text_after!' => '',
					'text_after_position!' => 'without',
				]
			]
		);
		
		$this->add_control(
			'text_after_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-progress-bar-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'text_after!' => '',
					'text_after_position!' => 'without',
				]
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render circle progress bar widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.8
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'wrapper', 'class', 'etheme-progress-bar-wrapper' );
		$this->add_render_attribute( 'svg-wrapper', 'class', 'etheme-progress-bar-svg-wrapper' );
		$this->add_render_attribute( 'progress-bar', 'class', 'etheme-progress-bar' );
		$this->add_render_attribute( 'progress-bar', 'data-type', 'circle' );
		$this->add_render_attribute( 'progress-bar-text-wrapper', 'class', 'etheme-progress-bar-text-wrapper' );

        $this->add_render_attribute( 'progress-bar-text-wrapper-middle', 'class', 'etheme-progress-bar-text-wrapper-middle' );
        
		$this->add_render_attribute('progress-bar', 'data-percentage', $settings['progress_value']['size']);
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'progress-bar' ); ?>>
                <div <?php $this->print_render_attribute_string( 'svg-wrapper' ); ?>>
                    <svg viewBox="0 0 100 100">
                        <circle class="incomplete" cx="50" cy="50" r="50"></circle>
                        <circle class="complete" cx="50" cy="50" r="50"></circle>
                    </svg>
                </div>
                <?php
                if ( in_array('middle', array( $settings['percent_position'], $settings['text_after_position'] ) ) ) { ?>
                    <span <?php $this->print_render_attribute_string( 'progress-bar-text-wrapper-middle' ); ?>>
                        
                        <span <?php $this->print_render_attribute_string( 'progress-bar-text-wrapper' ); ?>>
                            <?php if ( $settings['percent_position'] == 'middle' ) { ?>
                                <span class="etheme-progress-bar-value" data-suffix="%"><?php echo $settings['progress_value']['size']; ?></span>
                            <?php } ?>
                            
                            <?php if ( $settings['text_after_position'] == 'middle' && $settings['text_after'] ) { ?>
                                <span class="etheme-progress-bar-text"><?php echo $settings['text_after']; ?></span>
                            <?php } ?>
                        </span>
                        
                    </span>
                <?php } ?>
                
            </div>
	
	        <?php if ( in_array('bottom', array( $settings['percent_position'], $settings['text_after_position'] ) ) ) { ?>
                <span <?php $this->print_render_attribute_string( 'progress-bar-text-wrapper' ); ?>>
                
                        <?php if ( $settings['percent_position'] == 'bottom' ) { ?>
                            <span class="etheme-progress-bar-value" data-suffix="%"><?php echo $settings['progress_value']['size']??75; ?></span>
                        <?php } ?>
			
			        <?php if ( $settings['text_after'] && $settings['text_after_position'] == 'bottom' ) { ?>
                        <span class="etheme-progress-bar-text"><?php echo $settings['text_after']; ?></span>
			        <?php } ?>
                    
                    </span>
	        <?php } ?>
         
        </div>
        
		<?php
    
    }
}
