<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Scroll Progress widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Scroll_Progress extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_scroll_progress';
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
		return __( 'Scroll Progress', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-circle-progress-bar';
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
		return [ 'progress', 'bar', 'count', 'percent', 'window', 'page', 'scroll' ];
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
		return [ 'etheme_scroll_progress' ];
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
		return [ 'etheme-elementor-scroll-progress' ];
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
	 * Register Scroll Progress controls.
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
			'position',
			[
				'label' => esc_html__( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'top'    => esc_html__( 'Fixed to Top', 'xstore-core' ),
					'content'   => esc_html__( 'Content Flow', 'xstore-core' ),
					'bottom' => esc_html__( 'Fixed to Bottom', 'xstore-core' ),
				],
				'default'   => 'content',
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'percentage',
			[
				'label' 		=> esc_html__( 'Show Percentage', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'percentage_auto_position',
			[
				'label' 		=> esc_html__( 'Auto Percentage Position', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-align-',
                'return_value' => 'center',
                'default' => 'center',
                'condition' => [
                    'percentage!' => ''
                ]
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
						'max' => 70,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-scroll-progress' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'progress_vertical_offset',
			[
				'label' => __( 'Vertical Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'vh' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'vw' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--v-offset: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'position!' => 'content'
				]
			]
		);
		
		$this->add_responsive_control(
			'progress_horizontal_offset',
			[
				'label' => __( 'Horizontal Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'vh' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'vw' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--h-offset: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'position!' => 'content'
				]
			]
		);
		
		$this->add_control(
			'progress_background_color',
			[
				'label' => __( 'Default Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-scroll-progress-wrapper' => 'background-color: {{VALUE}};',
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
					],
                    'color' => [
                        'default' => '#2962FF'
                    ],
				],
				'selector' => '{{WRAPPER}} .etheme-scroll-progress',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'progress_border',
				'label'     => esc_html__( 'Border', 'xstore-core' ),
				'selector'  => '{{WRAPPER}} .etheme-scroll-progress-wrapper',
			]
		);
		
		$this->add_responsive_control(
			'progress_border_radius',
			[
				'label'                 => __( 'Border Radius', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-scroll-progress-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'progress_box_shadow',
				'selector' => '{{WRAPPER}} .etheme-scroll-progress-wrapper',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_percentage',
			[
				'label' => __( 'Percentage', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'percentage!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'percentage_typography',
				'selector' => '{{WRAPPER}} .etheme-scroll-progress-value',
			]
		);
		
		$this->add_control(
			'percentage_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-scroll-progress-value' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render scroll progress widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'wrapper', 'class', 'etheme-scroll-progress-wrapper' );
        if ( $settings['position'] != 'content') {
	        $this->add_render_attribute( 'wrapper', 'class', [
                $settings['position'],
                'invisible'
            ] );
        }
		$this->add_render_attribute( 'progress', 'class', 'etheme-scroll-progress' );
		
		if ( $settings['loading_animation'] )
			$this->add_render_attribute( 'progress', 'class', 'loading' );
		
		$this->add_render_attribute( 'percentage', 'class', 'etheme-scroll-progress-value' );
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'progress' ); ?>>
                <?php if ( $settings['percentage'] ) : ?>
                    <span <?php $this->print_render_attribute_string( 'percentage' ); ?>></span>
                <?php endif; ?>
            </div>
        </div>
        
        <?php
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['position'] != 'content' ) { ?>
                <div class="elementor-panel-alert elementor-panel-alert-info">
                    <?php esc_html_e('Placeholder for Scroll Progress widget to quick find and edit from clicking here. Shown only in Elementor Editor.', 'xstore-core'); ?>
                    </div>
            <?php }
        ?>
        
		<?php
    
    }
}
