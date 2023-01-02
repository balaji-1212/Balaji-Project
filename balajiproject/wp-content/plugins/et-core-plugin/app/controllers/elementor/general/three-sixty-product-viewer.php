<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Three Sixty Product Viewer widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Three_Sixty_Product_Viewer extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_three_sixty_product_viewer';
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
		return __( '360° Product Viewer', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-product-viewer';
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
		return [ '360', 'three', 'sixty', 'degree', 'view', 'image', 'product', 'animation', 'woocommerce' ];
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
		return [ 'etheme_three_sixty_product_viewer' ];
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
		return [ 'etheme-elementor-three-sixty-product-viewer' ];
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
			'images',
			[
				'label'   => esc_html__( 'Images', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::GALLERY,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'      => 'images',
				'default'   => 'large',
			]
		);
		
		$this->add_control(
			'overlay',
			[
				'label' => __( 'Image Overlay', 'xstore-core' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'overlay_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,.02)',
				'condition' => [
                    'overlay!' => ''
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--overlay-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_animation_controls_settings',
			[
				'label' => __( 'Animation Controls', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'mouse_option',
			[
				'label'       => esc_html__( 'Mouse Option', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'drag',
				'options'     => [
					''      => esc_html__('None', 'xstore-core'),
					'drag'  => esc_html__('Drag', 'xstore-core'),
					'move'  => esc_html__('Mouse Move', 'xstore-core'),
					'wheel' => esc_html__('Wheel', 'xstore-core'),
				],
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'ease',
			[
				'label' => __( 'Easing', 'xstore-core' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Makes ease animation on dragging images', 'xstore-core'),
				'frontend_available' => true,
				'condition' => [
					'mouse_option' => 'drag'
				]
			]
		);
		
		$this->add_control(
			'autoplay',
			[
				'label'       => __( 'Autoplay', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Starts the animation automatically on load', 'xstore-core' ),
				'default'     => 'yes',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'loop',
			[
				'label'   => __( 'Loop', 'xstore-core' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'frame_time',
			[
				'label'       => __('Frame Time', 'xstore-core'),
				'description' => __( 'Time in ms between updates. e.g. 40 is exactly 25 FPS', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default' => 200,
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'full_screen',
			[
				'label' => __( 'Full Screen Button', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_autoplay_settings',
			[
				'label' => __( 'Autoplay Options', 'xstore-core' ),
				'condition' => [
					'autoplay!' => ''
				]
			]
		);
		
		$this->add_control(
			'pause_on_hover',
			[
				'label'       => __( 'Pause On Hover', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
 			]
		);
		
		$this->add_control(
			'start_on_leave',
			[
				'label'       => __( 'Start On Mouseleave', 'xstore-core' ),
				'default'     => 'yes',
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
                    'pause_on_hover!' => ''
                ],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'reverse',
			[
				'label'       => __( 'Reverse', 'xstore-core' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Animation playback is reversed', 'xstore-core' ),
				'frontend_available' => true
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'max_width',
			[
				'label' => __( 'Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => __( 'Navigation', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation!' => ''
                ]
			]
		);
		
		$this->add_responsive_control(
			'nav_size',
			[
				'label'      => esc_html__( 'Size', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1
					],
					'rem' => [
						'min'  => 0,
						'max'  => 10,
						'step' => .1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-nav span' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'nav_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-nav span' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'nav_bg_color',
			[
				'label' 	=> esc_html__( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-nav span' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'nav_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1
					],
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-nav span' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'nav_space_between',
			[
				'label'      => esc_html__( 'Space Between', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1
					],
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--nav-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_box_shadow',
				'selector' => '{{WRAPPER}} .etheme-360-product-viewer-nav span',
				'separator' => 'before',
                'fields_options' => [
	                'box_shadow_type' => [
		                'default' => 'yes',
	                ],
	                'box_shadow' => [
		                'default' => [
			                'blur' => 10,
			                'color' => 'rgba(0,0,0,0.3)',
		                ],
	                ],
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_full_screen_style',
			[
				'label' => __( 'Full Screen Button', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'full_screen!' => ''
				]
			]
		);
		
		$this->add_responsive_control(
			'full_screen_button_size',
			[
				'label'      => esc_html__( 'Size', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1
					],
					'rem' => [
						'min'  => 0,
						'max'  => 10,
						'step' => .1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-full-screen-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'full_screen_button_color',
			[
				'label' 	=> esc_html__( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-full-screen-button' => 'color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'full_screen_button_bg_color',
			[
				'label' 	=> esc_html__( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-full-screen-button' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_responsive_control(
			'full_screen_button_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1
					],
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-360-product-viewer-full-screen-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'full_screen_button_box_shadow',
				'selector' => '{{WRAPPER}} .etheme-360-product-viewer-full-screen-button',
				'separator' => 'before',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'blur' => 10,
							'color' => 'rgba(0,0,0,0.3)',
						],
					],
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
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$image_urls = [];
		if ( !count($settings['images']) ) {
			for ($i = 1; $i < 9; $i++) {
				$image_urls[] = ET_CORE_URL . 'app/assets/img/widgets/three-sixty-product-viewer/0'.$i.'.jpeg';
			}
		}
		else {
			foreach ( $settings['images'] as $index => $item ) :
				$image_urls[] = isset( $item['id'] ) ?
					\Elementor\Group_Control_Image_Size::get_attachment_image_src( $item['id'], 'images', $settings ) :
					$item['url'];
			endforeach;
		}
		
        $this->add_render_attribute( 'wrapper', [
            'class' => 'etheme-360-product-viewer-wrapper',
            'data-settings' => wp_json_encode(array_filter([
                'images' => $image_urls
            ]))
        ] );
		
		$this->add_render_attribute( 'images', 'class', 'etheme-360-product-viewer-images' );
		
		$this->add_render_attribute( 'navigation', [
		        'class' => [
                    'etheme-360-product-viewer-nav',
                    'hidden'
                ]
        ] );
		$this->add_render_attribute( 'previous', 'class', 'etheme-360-product-viewer-nav-previous' );
		$this->add_render_attribute( 'next', 'class', 'etheme-360-product-viewer-nav-next' );
		$this->add_render_attribute( 'play', 'class', 'etheme-360-product-viewer-nav-play' );
		$this->add_render_attribute( 'stop', 'class', 'etheme-360-product-viewer-nav-stop' );
		
		if ( !!$settings['autoplay'] ) {
			$this->add_render_attribute( 'play', 'class', 'hidden' );
			$this->add_render_attribute( 'previous', 'class', 'disabled' );
			$this->add_render_attribute( 'next', 'class', 'disabled' );
        }
		else {
			$this->add_render_attribute( 'stop', 'class', 'hidden' );
        }
		
		$this->add_render_attribute( 'full-screen-button', 'class', ['etheme-360-product-viewer-full-screen-button', 'hidden'] );
		
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'images' ); ?>></div>
            <?php if ( !!$settings['full_screen'] ) : ?>
                <div <?php $this->print_render_attribute_string( 'full-screen-button' ); ?>>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.976 0.624c-0.024-0.072-0.024-0.096-0.048-0.144-0.024-0.024 0 0-0.024-0.048l-0.024-0.048c-0.072-0.144-0.192-0.264-0.336-0.336-0.072-0.024-0.168-0.024-0.24-0.024h-6.984c-0.36 0-0.624 0.288-0.624 0.624 0 0.36 0.264 0.624 0.624 0.624h5.496l-7.512 7.536c-0.24 0.24-0.24 0.624 0 0.888 0.12 0.12 0.264 0.192 0.456 0.192 0.168 0 0.336-0.096 0.456-0.192l7.512-7.512v5.496c0 0.36 0.288 0.624 0.624 0.624 0.36 0 0.624-0.288 0.624-0.624v-7.056zM9.264 14.112c-0.168 0-0.312 0.072-0.408 0.216l-7.536 7.512v-5.52c0-0.36-0.288-0.624-0.624-0.624s-0.648 0.264-0.648 0.624v7.032c0 0.048 0 0.168 0.048 0.24 0.072 0.144 0.192 0.264 0.336 0.336 0.096 0.048 0.192 0.048 0.24 0.048h7.032c0.36 0 0.624-0.288 0.624-0.624 0-0.36-0.288-0.624-0.624-0.624h-5.496l7.512-7.512c0.24-0.24 0.24-0.624 0-0.888-0.12-0.168-0.312-0.24-0.456-0.216z"></path>
                    </svg>
                </div>
            <?php endif; ?>
            <?php if ( $settings['navigation'] ) : ?>
                <div <?php $this->print_render_attribute_string( 'navigation' ); ?>>
                    <span <?php $this->print_render_attribute_string( 'previous' ); ?>>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>
                        </svg>
                    </span>
                    <span <?php $this->print_render_attribute_string( 'play' ); ?>>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.251 9.771l-14.582-9.126c-0.673-0.422-1.352-0.645-1.916-0.645-1.090 0-1.765 0.8-1.765 2.139v19.725c0 1.338 0.674 2.136 1.761 2.136 0.565 0 1.233-0.223 1.907-0.646l14.589-9.126c0.938-0.588 1.458-1.379 1.458-2.229s-0.513-1.64-1.453-2.228z"></path>
                        </svg>
                    </span>
                    <span <?php $this->print_render_attribute_string( 'stop' ); ?>>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
                             viewBox="0 0 24 24">
                            <path d="M7.2446289-0.0077515c-0.295105,0.0061646-0.5457153,0.2546997-0.5875244,0.5836182v22.7503662
                                c0.0105591,0.3591919,0.2623291,0.6463013,0.5604858,0.6660156c0.3272095,0.0216064,0.626709-0.2839966,0.6326904-0.6800537
                                c0-7.5888672,0-15.1777954,0-22.7666626C7.7955933,0.2210693,7.5380249-0.013855,7.2446289-0.0077515z"/>
                            <path d="M16.7372437-0.0077515c-0.295166,0.0061646-0.5457153,0.2546997-0.5875244,0.5836182v22.7503662
                                c0.0105591,0.3591919,0.2622681,0.6463013,0.5604858,0.6660156c0.3272095,0.0216064,0.626709-0.2839966,0.6326904-0.6800537
                                c0-7.5888672,0-15.1777954,0-22.7666626C17.288208,0.2210693,17.0306396-0.013855,16.7372437-0.0077515z"/>
                        </svg>
                    </span>
                    <span <?php $this->print_render_attribute_string( 'next' ); ?>>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.88 11.496l-10.728-11.304c-0.264-0.264-0.672-0.264-0.96-0.024-0.144 0.12-0.216 0.312-0.216 0.504 0 0.168 0.072 0.336 0.192 0.48l10.272 10.8-10.272 10.8c-0.12 0.12-0.192 0.312-0.192 0.504s0.072 0.36 0.192 0.504c0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.456-0.192l0.024-0.024 10.752-11.328c0.264-0.264 0.24-0.672 0-0.936z"></path>
                        </svg>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
		<?php
    
    }
}
