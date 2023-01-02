<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Blockquote widget.
 *
 * @since      4.0.5
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Blockquote extends \Elementor\Widget_Base {
	
    use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'blockquote';
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
		return __( 'Blockquote', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-blockquote';
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
		return [ 'blockquote', 'quote', 'paragraph', 'testimonial', 'text', 'twitter', 'tweet' ];
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
	 * Get widget dependency.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		$scripts = [];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
		    $scripts[] = 'etheme_elementor_slider';
		return $scripts;
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
		return [ 'etheme-elementor-blockquote' ];
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
	 * Register Blockquote widget controls.
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
			'type',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'grid'	=>	__('Grid', 'xstore-core'),
					'slider'	=>	__('Slider', 'xstore-core'),
				],
				'default'	  => 'grid',
			]
		);
		
		$this->add_responsive_control(
			'cols',
			[
				'label' => __( 'Columns', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '1',
				'selectors' => [
					'{{WRAPPER}}' => '--cols: {{VALUE}};',
				],
				'condition' => [
					'type' => 'grid'
				]
			]
		);
		
		$this->add_control(
			'style',
			[
				'label' 		=>	__( 'Style', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'default'	=>	__('Classic', 'xstore-core'),
					'border_top'	=>	__('Border top', 'xstore-core'),
					'border_left'	=>	__('Border left', 'xstore-core'),
				],
				'default'	  => 'default',
			]
		);
		
		$this->start_controls_tabs( 'style_border', [
            'condition' => [
                    'style' => ['border_top', 'border_left']
                ],
            ]
        );
		
		$this->start_controls_tab( 'style_border_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		$this->add_control(
			'style_border_width',
			[
				'label' => __( 'Main Border Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'default' => [
                    'unit' => 'px',
                    'size' => 7
                ],
				'selectors' => [
					'{{WRAPPER}} .style-border_left .etheme-blockquote' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .style-border_top .etheme-blockquote' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .style-border_top.quote-top .quotes' => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
			]
		);
		
		$this->add_control(
			'style_border_color',
			[
				'label' 	=> esc_html__( 'Main Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-border_left .etheme-blockquote ' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .style-border_top .etheme-blockquote' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'style_border_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'style_border_width_hover',
			[
				'label' => __( 'Main Border Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .style-border_left .etheme-blockquote:hover' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .style-border_top .etheme-blockquote:hover' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .style-border_top.quote-top .etheme-blockquote:hover .quotes' => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
			]
		);
		
		$this->add_control(
			'style_border_color_hover',
			[
				'label' 	=> esc_html__( 'Main Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-border_left .etheme-blockquote:hover' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .style-border_top .etheme-blockquote:hover' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'quote_position',
			[
				'label' 		=>	__( 'Icon Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' 		=>	[
					'above_content'	=>	__('Before Content', 'xstore-core'),
					'under_content'	=>	__('After Content', 'xstore-core'),
					'top'	=>	__('Absolute Top', 'xstore-core'),
				],
				'default'	  => 'above_content',
			]
		);
		
		$this->add_control(
			'custom_quote_icon',
			[
				'label' 		=> esc_html__( 'Custom Icon', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
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
				'condition' => ['custom_quote_icon' => ['yes']],
			]
		);
		
		$this->add_control(
			'quote_proportion',
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
					'{{WRAPPER}} .etheme-blockquotes-wrapper' => '--quote-proportion: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'quote_min_dimensions',
			[
				'label'		 =>	esc_html__( 'Icon Min Width/Min Height', 'xstore-core' ),
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
//					'size' => .4,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-blockquotes-wrapper' => '--quote-min-dimensions: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'author',
			[
				'label' 		=>	__( 'Author', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Author', 'xstore-core' ),
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
			'button_text',
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
		
		$repeater->add_control(
			'button_link',
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
		
		$this->start_controls_section(
			'content_settings',
			[
				'label' => esc_html__('Items', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'blockquote_item',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'author' => 'Judith Flores',
						'content' => 'Tortor, eget erat ac molestie lacus pretium nulla. Leo ante lectus a non velit venenatis. Est commodo a amet dignissim congue eu tellus id duis. Laoreet ac, ut urna, consectetur.',
                        'button_text' => ''
					],
					[
						'author' => 'Judith Flores',
						'content' => 'Tortor, eget erat ac molestie lacus pretium nulla. Leo ante lectus a non velit venenatis. Est commodo a amet dignissim congue eu tellus id duis. Laoreet ac, ut urna, consectetur.',
						'button_text' => ''
					],
				],
				'title_field' => '{{{ author }}}',
			]
		);
		
		$this->end_controls_section();
		
		// slider global settings
        Elementor::get_slider_general_settings($this, [
	        'type' => 'slider'
        ]);
        
        $this->start_injection( [
			'type' => 'section',
			'at' => 'start',
			'of' => 'space_between',
		] );
        
        $this->add_responsive_control(
			'slides_inner_spacing',
			[
				'label'		 =>	esc_html__( 'Slides Inner Spacing', 'xstore-core' ),
				'description' => esc_html__('May be usefull with combination of box-shadow option', 'xstore-core'),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_injection();
        
		$this->update_control( 'slides_per_view', [
			'default' 	=>	3,
            'tablet_default' => 3,
            'mobile_default' => 2,
		] );
		
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'type' => 'grid'
				]
			]
		);
		
		$this->add_responsive_control(
			'cols_gap',
			[
				'label' => __( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 30
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'rows_gap',
			[
				'label' => __( 'Rows Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 30
				],
				'selectors' => [
					'{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Blockquote', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'color',
			[
				'label' => esc_html__('Text Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-blockquote' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .etheme-blockquote',
			]
		);
		
		$this->add_control(
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
				'prefix_class' => 'elementor-align-',
				'default' => '',
			]
		);
		
		$this->add_control(
			'content_gap',
			[
				'label' => __( 'Content Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .etheme-blockquote' => '--gap-inner: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-blockquote'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-blockquote',
			]
		);
		
		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label'		 =>	esc_html__( 'Border Radius', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'em' 		=> [
						'min' 	=> 0,
						'max' 	=> 30,
						'step' 	=> .1
					],
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-blockquote' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'shadow',
				'selector' => '{{WRAPPER}} .etheme-blockquote',
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_quote_section',
			[
				'label' => esc_html__( 'Icon', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'quote_color',
			[
				'label' => esc_html__('Icon Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .quotes' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'quote_bg_color',
			[
				'label' => esc_html__('Icon Background Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'default' => '#e1e1e1',
				'selectors' => [
					'{{WRAPPER}} .quotes' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'quote_spacing',
			[
				'label'		 =>	esc_html__( 'Icon Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 50,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .quote-above_content .quotes' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .quote-top .blockquote-content' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['quote_position' => ['above_content', 'top']],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_author_section',
			[
				'label' => esc_html__( 'Author', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'author_color',
			[
				'label' => esc_html__('Text Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .author',
			]
		);
		
		$this->end_controls_section();
		
		// slider style settings
        Elementor::get_slider_style_settings($this, [
            'type' => 'slider'
        ] );
		
	}
	
	/**
	 * Render blockquote widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.5
	 * @access protected
	 */
	protected function render() {
	    $settings = $this->get_settings_for_display();
	    $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
	    
	    if ( $settings['type'] == 'slider' ) {
	        
	        wp_enqueue_script('etheme_elementor_slider');
	        
	        $this->add_render_attribute( 'wrapper', [
	            'class' => [
	                    'etheme-elementor-swiper-entry',
                        'swiper-entry',
                        $settings['arrows_position'],
                        $settings['arrows_position_style']
                    ]
             ]);
	        
	        $this->add_render_attribute( 'wrapper-inner',
				[
					'class' =>
						[
							'swiper-container',
							'etheme-elementor-slider',
						]
				]
			);
	        
	        $this->add_render_attribute( 'items-wrapper', 'class', 'swiper-wrapper');
	        
        }
	    
	    else {
		    $this->add_render_attribute( 'wrapper', [
			    'class' => [
				    'etheme-blockquotes-wrapper-grid',
			    ]
		    ]);
        }
	    
	    $this->add_render_attribute( 'wrapper', [
	            'class' => [
	                    'etheme-blockquotes-wrapper',
	                    'quote-'.$settings['quote_position']
                    ]
             ]);
	    
	    if ( $settings['style'] != 'default' ) {
		    $this->add_render_attribute( 'wrapper', 'class', 'style-'.$settings['style'] );
		}
	    
	    $this->add_render_attribute( 'blockquote', 'class', 'etheme-blockquote' );
		
		$this->add_render_attribute( 'content', 'class', 'blockquote-content' );
		
		?>
		
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
		
		    <?php if ( $settings['type'] == 'slider' ) : ?>
                
                <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>

                    <div <?php $this->print_render_attribute_string( 'items-wrapper' ); ?>>
                    
            <?php endif;
		
		        foreach ( $settings['blockquote_item'] as $item ) :
                    if ( $settings['type'] == 'slider' ) {
                        echo '<div class="swiper-slide">';
                    }
                ?>
                    <blockquote <?php echo $this->get_render_attribute_string( 'blockquote' ); ?>>
                        <?php
                            if ( $settings['quote_position'] != 'under_content' ) {
                                $this->render_quote_icon($settings);
                            }
                        ?>
                        <p <?php echo $this->get_render_attribute_string( 'content' ); ?>>
                            <?php echo $item['content']; ?>
                        </p>
                        <?php if ( $item['button_text'] || $item['author'] || $settings['quote_position'] == 'under_content') : ?>
                            <footer>
                               <?php
                                    if ( $settings['quote_position'] == 'under_content' || $item['author'] ) : ?>
                                        <div class="author-wrapper">
                                            <?php
                                            if ( $settings['quote_position'] == 'under_content' ) {
                                                $this->render_quote_icon($settings);
                                            }
                                            if ( $item['author'] ) {
                                                echo '<span class="author">'.$item['author'].'</span>';
                                            } ?>
                                        </div>
                                    <?php endif;
                                    if ( $item['button_text'] ) {
                                        if ( ! empty( $item['button_link']['url'] ) ) {
                                            $this->add_link_attributes( 'button', $item['button_link'] );
                                        }
                                        $this->add_render_attribute( 'button', 'class', 'elementor-button' );
                                        $this->add_render_attribute( 'button', 'role', 'button' );
                                        ?>
                                        <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                                            <?php echo $item['button_text']; ?>
                                        </a>
                                    <?php } ?>
                            </footer>
                        <?php endif; ?>
                    </blockquote>
                <?php
                    if ( $settings['type'] == 'slider' ) {
                         echo '</div>'; // .swiper-slide
                    }
                endforeach;
                
               if ( $settings['type'] == 'slider' ) : ?>
                    </div> <?php // testimonials-wrapper
                endif;
                
                if ( $settings['type'] == 'slider' && 1 < count($settings['blockquote_item']) ) {
                    if ( in_array($settings['navigation'], array('both', 'dots')) )
                        Elementor::get_slider_pagination($this, $settings, $edit_mode);
                }
	
            if ( $settings['type'] == 'slider' ) : ?>
                
                </div> <?php // wrapper-inner
            
            endif;
	
	            if ( $settings['type'] == 'slider' && 1 < count($settings['blockquote_item']) ) {
		            if ( in_array( $settings['navigation'], array( 'both', 'arrows' ) ) )
			            Elementor::get_slider_navigation( $settings, $edit_mode );
	            }
            
            ?>
            
            </div>
            
        <?php
        
	}
	
	/**
	 * Render quote icon.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.5
	 *
	 */
	public function render_quote_icon($settings) {
	    if ( $settings['custom_quote_icon'] ) :
	            if (! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
	            <span class="quotes">
                    <span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
                        <?php
                            \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                        ?>
                    </span>
                </span>
            <?php endif;
        else: ?>
			<span class="quotes">
                <svg width="1em" height="1em" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.15905 15.084C3.98571 15.084 2.97371 14.6587 2.12305 13.808C1.27238 12.9573 0.847045 11.9453 0.847045 10.772C0.847045 10.508 0.891045 10.2147 0.979045 9.892C1.06705 9.56933 1.16971 9.232 1.28705 8.88L4.49905 0.607998H8.45905L6.12705 6.636C7.06571 6.84133 7.84305 7.32533 8.45905 8.088C9.10438 8.85067 9.42705 9.74533 9.42705 10.772C9.42705 11.9453 9.00171 12.9573 8.15105 13.808C7.32971 14.6587 6.33238 15.084 5.15905 15.084ZM14.883 15.084C13.7097 15.084 12.6977 14.6587 11.847 13.808C11.0257 12.9573 10.615 11.9453 10.615 10.772C10.615 10.508 10.6444 10.244 10.703 9.98C10.7617 9.716 10.835 9.46667 10.923 9.232L14.223 0.607998H18.227L15.851 6.636C16.7897 6.84133 17.567 7.32533 18.183 8.088C18.8284 8.85067 19.151 9.74533 19.151 10.772C19.151 11.9453 18.7404 12.9573 17.919 13.808C17.0977 14.6587 16.0857 15.084 14.883 15.084Z" fill="currentColor"></path>
                </svg>
            </span>
        <?php endif;
	}
}
