<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Animated Headline widget.
 *
 * @since      4.0.7
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Animated_Headline extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.7
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_animated_headline';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.7
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Animated Headline', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.7
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-animated-headline';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.7
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'headline', 'heading', 'animation', 'title', 'text' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.7
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
	 * @since 4.0.7
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return ['etheme_animated_headline'];
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
		return [ 'etheme-elementor-animated-headline' ];
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
	 * @since 4.0.7
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
			'text_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'highlight' => __( 'Highlight', 'xstore-core' ),
					'animate' => __( 'Animation', 'xstore-core' ),
				],
				'default' => 'highlight',
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'animation_type',
			[
				'label' => __( 'Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
                    'flip' => 'flip',
                    'flip-2' => 'flip center',
                    'typing' => 'letters type',
                    'swirl' => 'letters swirl',
                    'loading-bar' => 'loading-bar',
                    'slide-down' => 'slide-down',
                    'clip' => 'clip is-full-width',
                    'drop-in' => 'drop-in',
                    'zoom' => 'zoom',
                    'swirl-2' => 'letters swirl-2',
                    'wave' => 'letters wave',
                    'slide' => 'slide',
				],
				'default' => 'typing',
				'condition' => [
					'text_style' => 'animate',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => __( 'Circle', 'xstore-core' ),
					'curly' => __( 'Curly', 'xstore-core' ),
					'underline' => __( 'Underline', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'double_underline' => __( 'Double Underline', 'xstore-core' ),
					'underline_zigzag' => __( 'Underline Zigzag', 'xstore-core' ),
					'diagonal' => __( 'Diagonal', 'xstore-core' ),
					'strikethrough' => __( 'Strikethrough', 'xstore-core' ),
//					'crossthrough' => __('Cross-through', 'xstore-core'), // cross-through
                    'custom' => __('Custom', 'xstore-core')
				],
				'condition' => [
					'text_style' => 'highlight',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'shape_advanced',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'text_style' => 'highlight',
					'shape' => 'custom',
				],
			]
		);
		
		$this->add_control(
			'shape_custom',
			[
				'label' => esc_html__( 'Custom SVG Icon Shape', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
                'exclude_inline_options' => [
                    'icon'
                ],
				'condition' => [
					'text_style' => 'highlight',
					'shape' => 'custom',
				],
			]
		);
		
		$this->add_control(
			'shape_custom_dasharray',
			[
				'label' => __( 'Shape Dasharray', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => esc_html__('Change this value if your custom svg has many paths and some of them are not visible during animation.', 'xstore-core') .
                esc_html__('If you select too much value, your icon could animate only as fadeIn/fadeOut', 'xstore-core'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 10000,
						'step' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--shape-dasharray: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'text_style' => 'highlight',
					'shape' => 'custom',
				],
			]
		);
		
		$this->add_control(
			'highlighted_text_before',
			[
				'label' => __( 'Before Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'animated_text',
			[
				'label' => __( 'Animated Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter each word in a separate line', 'xstore-core' ),
				'separator' => 'none',
				'default' => "Harder\nBetter\nFaster",
//				'dynamic' => [
//					'active' => true,
//					'categories' => [
//						TagsModule::TEXT_CATEGORY,
//					],
//				],
				'condition' => [
					'text_style' => 'animate',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'highlighted_text',
			[
				'label' => __( 'Highlighted Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Highlighted', 'xstore-core'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'highlighted_text_after',
			[
				'label' => __( 'After Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'highlighted_text_html_tag',
			[
				'label' => esc_html__( 'Highlighted Text HTML Tag', 'xstore-core' ),
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
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'Headline', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'headline_typography',
				'selector' => '{{WRAPPER}} .etheme-headline',
			]
		);
		
		$this->add_control(
			'headline_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-headline' => 'color: {{VALUE}};',
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'animated_text_style_heading',
			[
				'label' => __( 'Animated Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'animated_text_typography',
				'selector' => '{{WRAPPER}} .etheme-headline-text-wrapper',
				'separator' => 'before',
				'exclude' => ['font_size', 'line_height']
			]
		);
		
		$this->add_control(
			'animated_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-headline-text-wrapper' => '--text-color: {{VALUE}};',
				],
//				'condition' => [
//					'text_style' => 'animate',
//				],
			]
		);
		
		$this->add_control(
			'cursor_width',
			[
				'label' => __( 'Cursor Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cursor-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'text_style' => 'animate',
					'animation_type' => ['typing', 'clip'],
				],
			]
		);
		
		$this->add_control(
			'animated_loading_bar_bg_color',
			[
				'label' => __( 'Loading Bar Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}' => '--loading-bar-bg-color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animate',
					'animation_type' => 'loading-bar',
				],
			]
		);
		
		$this->add_control(
			'animated_loading_bar_size',
			[
				'label' => __( 'Loading Bar Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--loading-bar-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'text_style' => 'animate',
					'animation_type' => 'loading-bar',
				],
			]
		);
		
		$this->add_control(
			'typing_animation_highlight_colors',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => __( 'Selected Text', 'xstore-core' ),
				'separator' => 'before',
				'condition' => [
					'text_style' => 'animate',
					'animation_type' => 'typing',
				],
			]
		);
		
		$this->add_control(
			'animated_typing_text_background_color',
			[
				'label' => __( 'Selection Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--typing-selected-bg-color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animate',
					'animation_type' => 'typing',
				],
			]
		);
		
		$this->add_control(
			'animated_typing_text_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--typing-selected-color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animate',
					'animation_type' => 'typing',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_shape',
			[
				'label' => __( 'Shape', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style' => 'highlight',
				],
			]
		);
		
		$this->add_control(
			'shape_color',
			[
				'label' => __( 'Shape Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--shape-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shape_width',
			[
				'label' => __( 'Shape Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--shape-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'loop',
			[
				'label' => __( 'Infinite Loop', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--iteration-count: infinite',
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'duration',
			[
				'label' => __( 'Animation Duration', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--animation-duration: {{SIZE}}s;',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'delay',
			[
				'label' => __( 'Animation Delay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--animation-delay: {{SIZE}}s;',
				],
                'condition' => [
                    'loop!' => 'yes'
                ],
                'frontend_available' => true,
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render animated headline widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.7
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'wrapper', 'class', 'etheme-headline-wrapper' );
		$this->add_render_attribute( 'headline', 'class', 'etheme-headline' );
		$this->add_render_attribute( 'text-before', 'class', 'etheme-headline-text-before' );
		$this->add_render_attribute( 'text-wrapper', 'class', 'etheme-headline-text-wrapper' );
		
        $this->add_render_attribute( 'headline', 'data-style', $settings['text_style'] );
        if ( $settings['text_style'] == 'animate' ) {
            
            $this->add_render_attribute( 'headline', 'class', 'etheme-headline-animation-type-'.str_replace('flip-2', 'flip etheme-headline-animation-type-flip-2', $settings['animation_type']) );
        }
			
		$this->add_render_attribute( 'text-after', 'class', 'etheme-headline-text-after' );
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <<?php echo $settings['highlighted_text_html_tag']; ?> <?php $this->print_render_attribute_string( 'headline' ); ?>>
		        <?php
                    if ( $settings['highlighted_text_before'] ) { ?>
                        <span <?php $this->print_render_attribute_string( 'text-before' ); ?>>
	                        <?php echo $settings['highlighted_text_before']; ?>
                        </span>
                    <?php }
                ?>
                <span <?php $this->print_render_attribute_string( 'text-wrapper' ); ?>>
                
		        <?php if ( 'animate' === $settings['text_style'] && $settings['animated_text'] ) :
                
                    $rotating_text = explode( "\n", $settings['animated_text'] ); ?>
    
                    <?php foreach ( $rotating_text as $key => $text ) :
                        $status_class = 1 > $key ? 'etheme-headline-text-active' : '';
                        echo '<span class="etheme-headline-text ' . $status_class . '">'.
                             str_replace( ' ', '&nbsp;', trim($text) ) .
                        '</span>';
                    endforeach;
                    
                else :
                    echo $settings['highlighted_text'];
                    if ( $settings['text_style'] == 'highlight' ) {
                        if ( $settings['shape'] == 'custom' ) {
                            if ( $settings['shape_custom'] ) {
                                \Elementor\Icons_Manager::render_icon( $settings['shape_custom'] );
                            }
                        } else {
                            echo $this->renderSvg( $settings['shape'] );
                        }
                    }
                endif; ?>
                
                </span>
                <?php if ( $settings['highlighted_text_after'] ) { ?>
                    <span <?php $this->print_render_attribute_string( 'text-after' ); ?>>
                        <?php echo $settings['highlighted_text_after']; ?>
                    </span>
                <?php } ?>
            </<?php echo $settings['highlighted_text_html_tag']; ?>>
        </div>
        
		<?php
    }
	
	public function renderSvg($shape) {
		$svg = '';
		
		$svg .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none">';
		foreach ( $this->getSvgPath($shape) as $path ) {
			$svg .= '<path d="' . $path .'"></path>';
		}
		$svg .= '</svg>';
		
		return $svg;
	}
	
	/**
	 * Return paths for selected types.
	 *
	 * @param $path
	 * @return string[]
	 *
	 * @since 4.0.7
	 *
	 */
	public function getSvgPath($path) {
		$svgPaths = [
			'circle' => ['M385.6 48.191C347.6 37.191 298.601 33.0304 250.929 32.1983C115.64 29.8368 5.59529 49.1857 5.13745 75.4152C4.67962 101.645 112.811 117.829 248.1 120.191C383.389 122.552 494.605 110.196 495.063 83.9669C495.063 61.691 416.806 41.878 361.1 32.1983'],
			'underline_zigzag' => ['M5 135.5C196.331 122.525 303.619 120.745 495 122.5C330.99 127.16 247.119 131.658 126.742 146.5C177.434 143.05 205.695 141.087 305.062 146.5'],
//            'crossthrough' => ['M497.4,23.9C301.6,40,155.9,80.6,4,144.4', 'M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7'],
			'strikethrough' => ['M5 83.5454C192.706 70.1974 299.657 65.7638 495 66.0096'],
			'curly' => ['M5 146.575C5 146.575 44.2779 116.935 75.4221 117.006C106.397 117.078 114.378 146.794 145.352 146.575C175.818 146.36 193.202 117.497 223.663 117.006C255.312 116.496 254.05 146.789 285.704 146.575C316.847 146.365 334.84 117.006 365.985 117.006C397.129 117.006 395.406 146.867 426.548 146.575C457.013 146.29 495 117.006 495 117.006'],
			'diagonal' => ['M5 114.723C252.315 58.697 359.324 41.6284 495 36'],
			'double' => ['M5 33.0224C202.514 24.9982 311.787 22.4093 495 33.0224M5 124.429C250.938 111.632 368.657 109.185 495 124.429'],
			'double_underline' => ['M5 130.476C199.783 121.045 295.145 109.078 495 127.639M50.6522 146.306C165.797 137.383 343.333 128.045 457.464 142.755'],
			'underline' => ['M3.5 145.883C147.207 138.384 327.838 123.383 493.5 145.883'],
		];
		return $svgPaths[$path];
	}
}
