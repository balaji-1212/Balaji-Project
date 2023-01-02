<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Twitter Feed widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Icon_List extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_icon_list';
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
		return __( 'Icon List', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-icon-and-info-list';
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
		return [ 'icon', 'list', 'info', 'image', 'order' ];
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
		return [ 'etheme_icon_list' ];
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
		$scripts = [ 'etheme-elementor-icon-list' ];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$scripts += array(
				'elementor-icons-fa-solid',
				'elementor-icons-fa-regular',
				'elementor-icons-fa-brands',
			);
		}
		return $scripts;
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
			'view',
			[
				'label' => esc_html__( 'Layout', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'list',
				'options' => [
					'list' => [
						'title' => esc_html__( 'Default', 'xstore-core' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => esc_html__( 'Inline', 'xstore-core' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
			]
		);
		
		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Show Divider', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true
			]
		);
		
		$this->add_control(
			'show_more',
			[
				'label' => __( 'Show More Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'more_after',
			[
				'label' 		=>	__( 'Visible Items', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default'	 	=>	3,
				'min' 	=> 1,
				'max' 	=> '',
                'condition' => [
                    'show_more!' => ''
                ]
			]
		);
		
		$this->end_controls_section();
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'text',
			[
				'label' 		=>	__( 'Text', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Lorem ipsum dolor sit amet...', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'type',
			[
				'label' 		=>	__( 'Content Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
                    'numeric' => esc_html__('Numeric', 'xstore-core'),
                    'custom' => esc_html__('Custom', 'xstore-core'),
                    'icon' => esc_html__('Icon', 'xstore-core'),
                    'image' => esc_html__('Image', 'xstore-core'),
                    'none' => esc_html__('None', 'xstore-core'),
				],
				'default'		=> 'numeric'
			]
		);
		
		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => ET_CORE_URL . 'app/assets/img/widgets/icon-list/house-icon.png'
				],
                'condition' => [
                    'type' => 'image'
                ]
			]
		);
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
				'condition' => [
					'type' => 'image'
				]
			]
		);
		
		$repeater->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'fa4compatibility' => 'icon',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);
		
		$repeater->add_control(
			'custom_text',
			[
				'label' 		=>	__( 'Custom Text', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'condition' => [
					'type' => 'custom'
				]
			]
		);
		
		$repeater->add_control(
			'show_label',
			[
				'label' => __( 'Show Label', 'xstore-core' ),
				'separator' => 'before',
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$repeater->add_control(
			'label_text',
			[
				'label' 		=>	__( 'Label Text', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'HOT', 'xstore-core' ),
				'condition' => [
					'show_label!' => ''
				]
			]
		);
		
		$repeater->add_control(
			'label_color',
			[
				'label' 	=> __( 'Label Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .etheme-icon-list-item-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_label!' => ''
				]
			]
		);
		
		$repeater->add_control(
			'label_bg_color',
			[
				'label' 	=> __( 'Label Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .etheme-icon-list-item-label' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_label!' => ''
				]
			]
		);
		
		$repeater->add_control(
			'add_link',
			[
				'label' => __( 'Add link', 'xstore-core' ),
				'separator' => 'before',
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'condition' => [
					'add_link!' => ''
				]
			]
		);
		
		$this->start_controls_section(
			'content_settings',
			[
				'label' => esc_html__('Items', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'items',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => 'Roncus eget potenti viverra',
                        'custom_text' => esc_html__( 'Step 1.', 'xstore-core' )
					],
					[
						'text' => 'Aenean ut elementum',
						'custom_text' => esc_html__( 'Step 2.', 'xstore-core' ),
                        'show_label' => 'yes',
                        'label_text' => esc_html__( 'NEW', 'xstore-core' ),
                        'label_bg_color' => '#008C49'
					],
					[
						'text' => 'Met sit lorem metus sed',
						'custom_text' => esc_html__( 'Step 3.', 'xstore-core' )
					],
					[
						'text' => 'Non feugiat in quis nunc',
						'custom_text' => esc_html__( 'Step 4.', 'xstore-core' ),
						'show_label' => 'yes',
					],
					[
						'text' => 'Volutpat orci suspendisse',
						'custom_text' => esc_html__( 'Step 5.', 'xstore-core' )
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'general_style_settings',
			[
				'label' => esc_html__('General', 'xstore-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'item_align',
			[
				'label' => esc_html__( 'Horizontal Align', 'xstore-core' ),
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
				],
				'selectors_dictionary'  => [
					'left'          => 'flex-start',
					'right'          => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-inline, {{WRAPPER}} .etheme-icon-list-item, {{WRAPPER}} .etheme-icon-list-more-items' => 'justify-content: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'item_v_spacing',
			[
				'label'      => esc_html__( 'Vertical Spacing', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--v-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'item_h_spacing',
			[
				'label'      => esc_html__( 'Horizontal Spacing', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--h-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'item_style_settings',
			[
				'label' => esc_html__('Item', 'xstore-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-list-item',
			]
		);
		
		$this->add_control(
			'item_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'description' 	=> __( 'In case you have any link inside items', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'icon_style_settings',
			[
				'label' => esc_html__('Icon/Content', 'xstore-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-list-item-icon-inner',
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon-inner' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_image_max_width',
			[
				'label' => __( 'Image Max Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon-inner img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_proportion',
			[
				'label' => __( 'Width/Height', 'xstore-core' ),
				'description' => __('Useful to set if you need to have 1:1 proportion of square/circle icon', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--icon-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .etheme-icon-list-item-icon-inner',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'selector' => '{{WRAPPER}} .etheme-icon-list-item-icon-inner'
            ]
		);
		
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_advanced_style_heading',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'icon_type',
			[
				'label' 		=>	__( 'Icon Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'inline' => esc_html__('Default (inline)', 'xstore-core'),
					'block' => esc_html__('Block', 'xstore-core'),
				],
				'default'		=> 'inline'
			]
		);
		
		$this->add_control(
			'icon_type_description',
			[
				'raw' => esc_html__( 'Block type of icon allows you to set min-width for each icon area and make all look similar.', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'icon_type' => 'block'
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'selectors_dictionary'  => [
					'left'          => 'start',
					'right'          => 'end',
				],
				'condition'		=> [
                    'icon_type' => 'block'
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_basis',
			[
				'label' => __( 'Basis Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
                    'size' => 100
                ],
				'condition'		=> [
					'icon_type' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-item-icon' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'show_more_style_settings',
			[
				'label' => esc_html__('Show More', 'xstore-core'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_more!' => ''
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'show_more_typography',
				'selector' => '{{WRAPPER}} .etheme-icon-list-more-items',
			]
		);
		
		$this->add_control(
			'show_more_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-icon-list-more-items' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_divider',
			[
				'label' => __( 'Divider', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_divider!' => ''
				]
			]
		);
		
		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--line-weight: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'dotted' => __( 'Dotted', 'xstore-core' ),
					'dashed' => __( 'Dashed', 'xstore-core' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}}' => '--line-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--line-color: {{VALUE}}',
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
	 * @since 4.0.12
	 * @access protected
	 */
	public function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', [
			'class' => 'etheme-icon-list',
		]);
		
		if ( $settings['view'] == 'inline' ) {
			$this->add_render_attribute( 'wrapper', [
				'class' => 'etheme-icon-list-inline',
			]);
        }
		
		$this->add_render_attribute( 'item-icon', [
			'class' => 'etheme-icon-list-item-icon',
		]);
		
		$this->add_render_attribute( 'item-icon-inner', [
			'class' => 'etheme-icon-list-item-icon-inner',
		]);
		
//		$this->add_render_attribute( 'item-text', [
//			'class' => 'etheme-icon-list-item-text',
//		]);
		
		$this->add_render_attribute( 'item-label', [
			'class' => 'etheme-icon-list-item-label',
		]);
		
		$this->add_render_attribute( 'more-items', [
			'class' => 'etheme-icon-list-more-items',
		]);
		
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'more-items', [
				'class' => 'elementor-clickable',
			] );
		}
		
		?>
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <?php
                    foreach ( $settings['items'] as $item_key => $item_value ) {
                        $options = $settings['items'][$item_key];
                        $repeater_setting_key = $this->get_repeater_setting_key( 'items', 'list-items', $item_key );
	
	                    $this->add_render_attribute( $repeater_setting_key, [
		                    'class' => [
                                'etheme-icon-list-item',
			                    'elementor-repeater-item-' . $options['_id'],
		                    ]
	                    ]);
	
	                    $this->add_render_attribute( $repeater_setting_key.'item-text', [
		                    'class' => 'etheme-icon-list-item-text',
	                    ]);
	
	                    if ( $settings['show_divider'] && ($item_key+1) < count($settings['items']) ) {
		                    $this->add_render_attribute( $repeater_setting_key, 'class', 'has-divider');
	                    }
	                    
	                    if ( $settings['show_more'] ) {
	                        if ( ($item_key+1) > $settings['more_after'] ) {
		                        $this->add_render_attribute( $repeater_setting_key, [
			                        'style' => 'display: none;'
		                        ] );
	                        }
	                        
		                    if ( $item_key >= $settings['more_after'] )
		                        $this->remove_render_attribute( $repeater_setting_key, 'class', 'has-divider' );
	                    }
	                    
	                    $tag = 'span';
	
	                    if ( $options['add_link'] && ! empty( $options['link']['url'] ) ) {
		                    $tag = 'a';
		                    $this->add_link_attributes( $repeater_setting_key.'item-text', $options['link'] );
	                    }
                        
                        ?>
                        <div <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>>
                            <?php
                            
                                if ( $options['type'] != 'none' ) { ?>
                                    
                                    <span <?php $this->print_render_attribute_string( 'item-icon' ); ?>>
                                        <span <?php $this->print_render_attribute_string( 'item-icon-inner' ); ?>>
                                        <?php
                                            switch ($options['type']) {
                                                case 'numeric':
                                                    echo $item_key+1 . '.';
                                                break;
	                                            case 'custom':
		                                            echo $options['custom_text']??sprintf(esc_html__('Step %1s.', 'xstore-core'), $item_key+1);
                                                break;
                                                case 'icon':
//                                                    echo \Elementor\Icons_Manager::render_icon( $hotspot['hotspot_icon'] );
                                                    $this->render_icon($options);
                                                break;
                                                case 'image':
                                                    echo \Elementor\Group_Control_Image_Size::print_attachment_image_html( $item_value );
                                                    break;
                                            }
                                        ?>
                                        </span>
                                    </span>
                                    
                                <?php } ?>
                            
                            <<?php echo $tag; ?> <?php $this->print_render_attribute_string( $repeater_setting_key.'item-text' ); ?>>
                                <?php echo $options['text']; ?>
                            </<?php echo $tag; ?>>
                            <?php if ( !!$options['show_label'] ) { ?>
                                <span <?php $this->print_render_attribute_string( 'item-label' ); ?>>
                                    <?php echo $options['label_text']; ?>
                                </span>
                            <?php }
                            ?>
                        </div>
                    <?php }
                    
                    if ( $settings['show_more'] && count($settings['items'] ) > $settings['more_after']) : ?>
                    <div <?php $this->print_render_attribute_string( 'more-items' ); ?>>
                        <?php echo sprintf( esc_html__('+%1s More Options', 'xstore-core'), (count($settings['items']) - $settings['more_after'])); ?>
                    </div>
                    <?php endif;
                ?>
            </div>
        
        <?php
		
	}
	
	/**
	 * Render Icon HTML.
	 *
	 * @param $settings
	 * @return void
	 *
	 * @since 4.0.12
	 *
	 */
	protected function render_icon($settings) {
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
			<?php if ( $is_new || $migrated ) :
				\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
                <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
			<?php endif;
		endif;
	}
	
}
