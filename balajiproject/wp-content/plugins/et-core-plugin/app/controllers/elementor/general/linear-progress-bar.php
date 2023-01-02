<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Linear Progress Bar widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Linear_Progress_Bar extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_linear_progress_bar';
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
		return __( 'Linear Progress Bar', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-linear-progress-bar';
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
		return [ 'progress', 'linear', 'bar', 'count', 'percent', 'size', 'line', 'animation' ];
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
		return [ 'etheme_linear_progress_bar' ];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-linear-progress-bar' ];
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
			'progress_type',
			[
				'label' => esc_html__( 'Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'style-01' => esc_html__( 'Text Above', 'xstore-core' ),
                    'style-02' => esc_html__( 'Percentage Label', 'xstore-core' ),
					'style-03' => esc_html__( 'Text Aside', 'xstore-core' ),
					'style-04' => esc_html__( 'Text Inside', 'xstore-core' ),
					'style-05' => esc_html__( 'Text Below', 'xstore-core' ),
				],
				'default'   => 'style-01',
			]
		);
		
		$this->add_control(
			'progress_value',
			[
				'label' => __( 'Value', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
                'default' => [
                    'size' => 75
                ],
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'WooCommerce',
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'loading_animation',
			[
				'label' => __( 'Loading Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
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
		
		$this->add_control(
			'height',
			[
				'label' => __( 'Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--progress-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'progress_inner_space',
			[
				'label' => __( 'Inner Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--progress-inner-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'progress_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--progress-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'progress_background_color',
			[
				'label' => __( 'Default Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-linear-progress-bar' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'progress_background_active',
				'label' => esc_html__( 'Active Color', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
                        'label' => esc_html__( 'Active Color', 'xstore-core' )
					],
					'color' => [
						'default' => '#2962FF',
						'selectors' => [
							'{{SELECTOR}}' => 'background-color: {{VALUE}}; --progress-active-color: {{VALUE}}',
						],
					],
                    'gradient_angle' => [
	                    'default' => [
		                    'unit' => 'deg',
		                    'size' => 90,
	                    ],
                    ]
				],
				'selector' => '{{WRAPPER}} .etheme-linear-progress-bar-inner',
			]
		);
		
		$this->add_control(
			'progress_color_animation',
			[
				'label' => __( 'Animation Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--progress-color-animation: {{VALUE}};',
				],
                'condition' => [
                    'loading_animation!' => ''
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_percent',
			[
				'label' => __( 'Percent', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'percentage_typography',
				'selector' => '{{WRAPPER}} .etheme-linear-progress-bar-label',
			]
		);
		
		$this->add_control(
			'percentage_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-linear-progress-bar-label' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title!' => ''
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-linear-progress-bar-title',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-linear-progress-bar-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render linear progress bar widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
        $this->add_render_attribute( 'wrapper', 'class', [
                'etheme-linear-progress-bar-wrapper',
                $settings['progress_type']
        ] );
        
		$this->add_render_attribute( 'progress', [
		        'class' => 'etheme-linear-progress-bar',
		        'role' => 'progressbar',
		        'aria-valuemin' => '0',
		        'aria-valuemax' => $settings['progress_value']['size'],
		        'aria-valuenow' => 0,
		        'aria-valuetext' => $settings['title'],
        ] );
		
		$this->add_render_attribute( 'progress-inner', 'class', 'etheme-linear-progress-bar-inner' );
		
		if ( $settings['loading_animation'] )
			$this->add_render_attribute( 'progress-inner', 'class', 'loading' );
		
		$this->add_render_attribute( 'title', 'class', 'etheme-linear-progress-bar-title' );
		
		$this->add_render_attribute( 'progress_value', 'class', 'etheme-linear-progress-bar-label' );
		
		if ( $settings['progress_type'] == 'style-02' )
			$this->add_render_attribute( 'progress_value', 'class', 'with-tooltip' );
		elseif ($settings['progress_type'] == 'advanced') {
			$this->add_render_attribute( 'wrapper', 'class', '' );
        }
		
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php
                if ( in_array($settings['progress_type'], array('style-01', 'style-02', 'style-03') ) ) {
                    $this->render_progress_bar_title($settings);
                    if ( $settings['progress_type'] == 'style-01' )
                        $this->render_progress_bar_value($settings);
                }
            ?>
                
            <div <?php $this->print_render_attribute_string( 'progress' ); ?>>
                <div <?php $this->print_render_attribute_string( 'progress-inner' ); ?>>
                    <?php
                        if ( in_array($settings['progress_type'], array('style-02', 'style-03', 'style-04')) ) {
                            if ( $settings['progress_type'] == 'style-04')
	                            $this->render_progress_bar_title($settings);
                            $this->render_progress_bar_value($settings);
                        }
                    ?>
                </div>
            </div>
	
	        <?php
                if ( $settings['progress_type'] == 'style-05' ) {
                    $this->render_progress_bar_title($settings);
                    $this->render_progress_bar_value($settings);
                }
            ?>
                
        </div>
        
		<?php
    
    }
	
	/**
	 * Render Title Content.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.12
	 *
	 */
    public function render_progress_bar_title($settings) {
	    if ( ! \Elementor\Utils::is_empty( $settings['title'] ) ) { ?>
            <span <?php $this->print_render_attribute_string( 'title' ); ?>>
                <?php $this->print_unescaped_setting( 'title' ); ?>
            </span>
		    <?php
	    }
    }
	
	/**
	 * Render Value Content.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.12
	 *
	 */
	public function render_progress_bar_value($settings) {
	    ?>
		<span <?php $this->print_render_attribute_string( 'progress_value' ); ?>>
            <?php
                echo $settings['progress_value']['size'] . '%';
            ?>
        </span>
        <?php
	}
}
