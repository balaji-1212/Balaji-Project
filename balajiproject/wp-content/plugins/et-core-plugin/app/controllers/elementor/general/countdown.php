<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Countdown widget.
 *
 * @since      4.0.5
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Countdown extends \Elementor\Widget_Base {

    public $instance = null;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.5
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-countdown';
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
		return __( 'Countdown Timer', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-countdown';
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
		return [ 'circle', 'count', 'percent', 'number', 'date', 'time', 'sale', 'evergreen', 'woocommerce' ];
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
	 * @since 4.0.5
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'etheme_countdown' ];
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
		return [ 'etheme-elementor-countdown' ];
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
	 * Register countdown widget controls.
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
			'due_date',
			[
				'label' => __( 'Due Date', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'default' => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'xstore-core' ), \Elementor\Utils::get_timezone_string() ),
			]
		);
		
		$this->add_control(
			'stretch_items',
			[
				'label' 		=> esc_html__( 'Stretch items', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => 'flex: 1;',
				],
			]
		);
		
		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Horizontal Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'' => esc_html__( 'Default', 'xstore-core' ),
					'flex-start' => esc_html__( 'Start', 'xstore-core' ),
					'center' => esc_html__( 'Center', 'xstore-core' ),
					'flex-end' => esc_html__( 'End', 'xstore-core' ),
					'space-between' => esc_html__( 'Space Between', 'xstore-core' ),
					'space-around' => esc_html__( 'Space Around', 'xstore-core' ),
					'space-evenly' => esc_html__( 'Space Evenly', 'xstore-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => 'justify-content: {{VALUE}}',
				],
				'condition' => [
					'stretch_items!' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'label_position',
			[
				'label' 		=>	__( 'Label Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default'		=> 'bottom',
			]
		);
		
		$this->add_control(
			'add_delimiter',
			[
				'label' 		=> esc_html__( 'Add Delimiter', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$this->add_control(
			'delimiter',
			[
				'label' 		=>	__( 'Delimiter', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => ':',
				'condition' => [
					'add_delimiter' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'show_empty_counter',
			[
				'label' 		=>	__( 'Show 00 Counter', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'show_days',
			[
				'label' 		=> esc_html__( 'Show Days', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
			'show_hours',
			[
				'label' 		=> esc_html__( 'Show Hours', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
			'show_minutes',
			[
				'label' 		=> esc_html__( 'Show Minutes', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
			'show_seconds',
			[
				'label' 		=> esc_html__( 'Show Seconds', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
			'show_labels',
			[
				'label' 		=> esc_html__( 'Show Labels', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
			'custom_labels',
			[
				'label' 		=> esc_html__( 'Custom Labels', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' => ['show_labels' => ['yes']],
			]
		);
		
		$this->add_control(
			'label_days',
			[
				'label' 		=>	__( 'Label Days', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Days', 'xstore-core' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_days' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'label_hours',
			[
				'label' 		=>	__( 'Label Hours', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Hours', 'xstore-core' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_hours' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'label_minutes',
			[
				'label' 		=>	__( 'Label Minutes', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Minutes', 'xstore-core' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_minutes' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'label_seconds',
			[
				'label' 		=>	__( 'Label Seconds', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Seconds', 'xstore-core' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_seconds' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'expire_actions',
			[
				'label' => __( 'Actions After Expire', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => [
					'redirect' => __( 'Redirect', 'xstore-core' ),
					'hide' => __( 'Hide', 'xstore-core' ),
					'message' => __( 'Show Message', 'xstore-core' ),
					'widget' => __( 'Show Widget with specific ID', 'xstore-core' ),
				],
				'label_block' => true,
				'separator' => 'before',
				'render_type' => 'none',
				'multiple' => true,
                'default' => ['hide'],
			]
		);
		
		$this->add_control(
			'message_after_expire',
			[
				'label' => __( 'Message', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'message',
				],
			]
		);
		
		$this->add_control(
			'widget_after_expire',
			[
				'label' 		=>	__( 'Widget ID', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'condition' => [
					'expire_actions' => 'widget',
				],
			]
		);
		
		$this->add_control(
			'expire_redirect_url',
			[
				'label' => __( 'Redirect URL', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'separator' => 'before',
				'options' => false,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'redirect',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'xstore-core' ),
				'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'gap',
			[
				'label' => __( 'Items Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => '--gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_item_style',
			[
				'label' => __( 'Item', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'inner_gap',
			[
				'label' => __( 'Items Inner Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'%' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => '--inner-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__('Items Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-countdown-item',
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-countdown-item'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'shadow',
				'selector' => '{{WRAPPER}} .etheme-countdown-item',
				'separator' => 'before',
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
					'{{WRAPPER}} .etheme-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'min_width',
			[
				'label' => __( 'Min Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'vw' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 500,
						'step' 	=> 1
					],
					'vh' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'vw' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
                    'unit' => 'px',
                    'size' => 100
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => '--item-min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'min_height',
			[
				'label' => __( 'Min Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'vw' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 500,
						'step' 	=> 1
					],
					'vh' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
					'vw' 		=> [
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown' => '--item-min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'heading_digits',
			[
				'label' => __( 'Digits', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);
		
		$this->add_control(
			'digits_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'digits_typography',
				'selector' => '{{WRAPPER}} .etheme-countdown-digits',
			]
		);
		
		$this->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'show_labels!' => ''
                ]
			]
		);
		
		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-label' => 'color: {{VALUE}};',
				],
                'condition' => [
                    'show_labels!' => ''
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .etheme-countdown-label',
                'condition' => [
                    'show_labels!' => ''
                ]
			]
		);
		
		$this->add_control(
			'delimiter_label',
			[
				'label' => __( 'Delimiter', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'add_delimiter!' => ''
                ]
			]
		);
		
		$this->add_control(
			'delimiter_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-countdown-delimiter' => 'color: {{VALUE}};',
				],
                'condition' => [
                    'add_delimiter!' => ''
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'delimiter_typography',
				'selector' => '{{WRAPPER}} .etheme-countdown-delimiter',
				'condition' => [
                    'add_delimiter!' => ''
                ]
			]
		);
		
		$this->end_controls_section();

	}

	/**
	 * Render countdown widget output on the frontend.
	 *
	 * @since 4.0.5
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'div', [
			'class' => 'etheme-countdown-wrapper',
			'data-date' => strtotime(get_gmt_from_date( $settings['due_date'] . ':00')),
		] );
		
		$actions = false;
		
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$actions = $this->get_actions( $settings );
		}
		
		if ( $actions ) {
			$this->add_render_attribute( 'div', 'data-expire-actions', json_encode( $actions ) );
		}
        
        ?>

        <div <?php echo $this->get_render_attribute_string( 'div' ); ?>>
            
            <div class="etheme-countdown">
			    <?php echo $this->render_countdown($settings); ?>
			</div>
      
		    <?php
            if ( $actions && is_array( $actions ) ) {
                foreach ( $actions as $action ) {
                    switch ($action['type']) {
                        case 'message':
                            ?>
                            <div class="etheme-countdown-expire--message hidden">
                                <?php echo do_shortcode($settings['message_after_expire']); ?>
                            </div>
                            <?php
                            break;
                        case 'widget':
                            if ( $settings['widget_after_expire'] != '') {
                                $this->add_render_attribute( 'div', [
                                    'data-widget-id' => $settings['widget_after_expire']
                                ] );
                            }
                            ?>
                            <style>
                                #<?php echo $settings['widget_after_expire']; ?>:not(.done) {
                                    display: none !important;
                                }
                            </style>
                            <?php
                            break;
                    }
                }
            }
		
		?>
        
        </div>
        
        <?php
	}
	
	/**
	 * Return actions after countdown expired.
	 *
	 * @param $settings
	 * @return array|false
	 *
	 * @since 4.0.5
	 *
	 */
	private function get_actions( $settings ) {
		if ( empty( $settings['expire_actions'] ) || ! is_array( $settings['expire_actions'] ) ) {
			return false;
		}
		
		$actions = [];
		
		foreach ( $settings['expire_actions'] as $action ) {
			$action_to_run = [ 'type' => $action ];
			if ( 'redirect' === $action ) {
				if ( empty( $settings['expire_redirect_url']['url'] ) ) {
					continue;
				}
				$action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
			}
			if ( 'widget' === $action ) {
				if ( empty( $settings['widget_after_expire'] ) ) {
					continue;
				}
				$action_to_run['widget_id'] = $settings['widget_after_expire'];
			}
			$actions[] = $action_to_run;
		}
		
		return $actions;
	}
	
	/**
	 * Render Full Countdown by pieces (days, hours, minutes, seconds).
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.5
	 *
	 */
	public static function render_countdown($settings) {
	    $items = [];
		if ( $settings['show_days'] ) {
			$items[] = self::render_item($settings, 'label_days', 'days');
		}
		if ( $settings['show_hours'] ) {
			$items[] = self::render_item($settings, 'label_hours', 'hours');
		}
		if ( $settings['show_minutes'] ) {
			$items[] = self::render_item($settings, 'label_minutes', 'minutes');
		}
		if ( $settings['show_seconds'] ) {
			$items[] = self::render_item($settings, 'label_seconds', 'seconds');
		}
		echo implode(($settings['add_delimiter'] ? '<span class="etheme-countdown-delimiter">'.$settings['delimiter'].'</span>' : ''), $items);
	}
	
	/**
	 * Return labels with string translations for them.
	 *
	 * @since 4.0.5
	 *
	 * @return array
	 */
	public static function get_default_countdown_labels() {
		return [
			'label_days' => __( 'Days', 'xstore-core' ),
			'label_hours' => __( 'Hours', 'xstore-core' ),
			'label_minutes' => __( 'Minutes', 'xstore-core' ),
			'label_seconds' => __( 'Seconds', 'xstore-core' ),
		];
	}
	
	/**
	 * Render Count Item.
	 *
	 * @param $instance
	 * @param $label
	 * @param $part_class
	 * @return string
	 *
	 * @since 4.0.5
	 *
	 */
	public static function render_item( $instance, $label, $part_class ) {
	 
		$string = '<div class="etheme-countdown-item '.$part_class.' label-'.$instance['label_position'] . '">';
		
		$string .= '<div class="etheme-countdown-item-inner">';
                if ( $instance['label_position'] != 'left' ) {
                    $string .= '<span class="etheme-countdown-digits">0</span>';
                }
				if ( $instance['show_labels'] ) {
					$default_labels = self::get_default_countdown_labels();
					$label = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
					$string .= ' <span class="etheme-countdown-label">' . $label . '</span>';
				}
                if ( $instance['label_position'] == 'left' ) {
                    $string .= '<span class="etheme-countdown-digits">0</span>';
                }
				$string .= '</div>';
		$string .= '</div>';
		
		return $string;
	}

}
