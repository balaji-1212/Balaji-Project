<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Contact form 7 widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Contact_Form_7 extends \Elementor\Widget_Base {
    
    use Elementor;

    /**
     * Get widget name.
     *
     * @since 2.1.3
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'et-contact-form-7';
    }

    /**
     * Get widget title.
     *
     * @since 2.1.3
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Contact Form 7', 'xstore-core' );
    }

    /**
     * Get widget icon.
     *
     * @since 2.1.3
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eight_theme-elementor-icon et-elementor-contact-form';
    }

    /**
     * Get widget keywords.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'email', 'send', 'message', 'text', 'response' ];
    }

    /**
     * Get widget categories.
     *
     * @since 2.1.3
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
	public function get_style_depends() {
		return [ 'etheme-elementor-contact-form-7' ];
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
     * Register contact form 7 widget controls.
     *
     * @since 2.1.3
     * @access protected
     */
    protected function register_controls() {
        
        $this->start_controls_section(
            'section_info_box',
            [
                'label'                 => __( 'General', 'xstore-core' ),
            ]
        );
		
		$this->add_control(
			'contact_form_list',
			[
				'label'                 => esc_html__( 'Contact Form', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::SELECT,
				'label_block'           => true,
				'options'               => self::get_contact_form_7(),
                'default'               => '0',
			]
		);
        
        $this->add_control(
            'form_title',
            [
                'label'                 => __( 'Form Title', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SWITCHER,
                'return_value'          => 'yes',
            ]
        );
		
		$this->add_control(
			'form_title_text',
			[
				'label'                 => esc_html__( 'Title', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::TEXT,
				'label_block'           => true,
                'default'               => '',
                'condition'             => [
                    'form_title'   => 'yes',
                ],
			]
		);
        
        $this->add_control(
            'form_description',
            [
                'label'                 => __( 'Form Description', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SWITCHER,
                'return_value'          => 'yes',
            ]
        );
		
		$this->add_control(
			'form_description_text',
			[
				'label'                 => esc_html__( 'Description', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::TEXTAREA,
                'default'               => '',
                'condition'             => [
                    'form_description'   => 'yes',
                ],
			]
		);
        
        $this->add_control(
            'labels_switch',
            [
                'label'                 => __( 'Labels', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_errors',
            [
                'label'                 => __( 'Messages', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'error_messages',
            [
                'label'         => __( 'Error Messages', 'xstore-core' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'return_value'  => 'show',
                'default'       => 'show',
                'selectors_dictionary'  => [
                    'show'          => 'block',
                    'hide'          => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-not-valid-tip' => 'display: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'validation_errors',
            [
                'label'         => __( 'Validation Errors', 'xstore-core' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'return_value'  => 'show',
                'default'       => 'show',
                'selectors_dictionary'  => [
                    'show'          => 'block',
                    'hide'          => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-validation-errors' => 'display: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_fields_title_description',
            [
                'label'                 => __( 'Title & Description', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'heading_alignment',
            [
                'label'                 => __( 'Alignment', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::CHOOSE,
				'options'               => [
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
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .etheme-contact-form-7-heading' => 'text-align: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
            'title_heading',
            [
                'label'                 => __( 'Title', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::HEADING,
				'separator'             => 'before',
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .etheme-contact-form-7-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .etheme-contact-form-7-title',
            ]
        );
        
        $this->add_control(
            'description_heading',
            [
                'label'                 => __( 'Description', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::HEADING,
				'separator'             => 'before',
            ]
        );

        $this->add_control(
            'description_text_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .etheme-contact-form-7-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .etheme-contact-form-7-description',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_fields_style',
            [
                'label'                 => __( 'Input & Textarea', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_fields_style' );

        $this->start_controls_tab(
            'tab_fields_normal',
            [
                'label'                 => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'field_bg',
            [
                'label'                 => __( 'Background Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_spacing',
            [
                'label'                 => __( 'Spacing', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '20',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form .wpcf7-form-control-wrap, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form .form-group:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'field_padding',
			[
				'label'                 => __( 'Padding', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
	    $this->add_responsive_control(
		    'field_min_height',
		    [
			    'label'                 => __( 'Min Height', 'xstore-core' ),
			    'type'                  => \Elementor\Controls_Manager::SLIDER,
			    'range'                 => [
				    'px'        => [
					    'min'   => 0,
					    'max'   => 100,
					    'step'  => 1,
				    ],
				    'em'        => [
					    'min'   => 0,
					    'max'   => 20,
					    'step'  => .1,
				    ],
			    ],
			    'size_units'            => [ 'px', 'em' ],
			    'selectors'             => [
				    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'min-height: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );
        
        $this->add_responsive_control(
            'text_indent',
            [
                'label'                 => __( 'Text Indent', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                    '%'         => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_width',
            [
                'label'                 => __( 'Input Width', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'textarea_width',
            [
                'label'                 => __( 'Textarea Width', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'                  => 'field_border',
				'label'                 => __( 'Border', 'xstore-core' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'field_radius',
			[
				'label'                 => __( 'Border Radius', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'field_typography',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'field_box_shadow',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_fields_focus',
            [
                'label'                 => __( 'Focus', 'xstore-core' ),
            ]
        );
        
        $this->add_control(
            'field_bg_focus',
            [
                'label'                 => __( 'Background Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'                  => 'input_border_focus',
				'label'                 => __( 'Border', 'xstore-core' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form textarea:focus',
                'separator'             => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'focus_box_shadow',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form textarea:focus',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_alignment',
            [
                'label'                 => __( 'Alignment', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::CHOOSE,
                'options'               => [
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
                'default'               => 'left',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form p' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color_label',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form label, {{WRAPPER}} .etheme-contact-form-7 .wpcf7-form:not(input)' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'label_fields',
            [
                'label'                 => __( 'Spacing Fields', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );        

        $this->add_responsive_control(
            'label_spaceing',
            [
                'label'                 => __( 'Spacing Labels', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form label > span' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography_label',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form label',
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_placeholder_style',
            [
                'label'                 => __( 'Placeholder', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'placeholder_switch',
            [
                'label'                 => __( 'Show Placeholder', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'text_color_placeholder',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'placeholder_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography_placeholder',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder',
                'condition'             => [
                    'placeholder_switch'   => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_radio_checkbox_style',
            [
                'label'                 => __( 'Radio & Checkbox', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'custom_radio_checkbox',
            [
                'label'                 => __( 'Custom Styles', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SWITCHER,
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_responsive_control(
            'radio_checkbox_size',
            [
                'label'                 => __( 'Size', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '15',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_radio_checkbox_style' );

        $this->start_controls_tab(
            'radio_checkbox_normal',
            [
                'label'                 => __( 'Normal', 'xstore-core' ),
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_color',
            [
                'label'                 => __( 'Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'radio_checkbox_border_width',
            [
                'label'                 => __( 'Border Width', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 15,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_border_color',
            [
                'label'                 => __( 'Border Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'checkbox_heading',
            [
                'label'                 => __( 'Checkbox', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::HEADING,
				'condition'             => [
					'custom_radio_checkbox' => 'yes',
				],
            ]
        );

		$this->add_control(
			'checkbox_border_radius',
			[
				'label'                 => __( 'Border Radius', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
			]
		);
        
        $this->add_control(
            'radio_heading',
            [
                'label'                 => __( 'Radio Buttons', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::HEADING,
				'condition'             => [
					'custom_radio_checkbox' => 'yes',
				],
            ]
        );

		$this->add_control(
			'radio_border_radius',
			[
				'label'                 => __( 'Border Radius', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'radio_checkbox_checked',
            [
                'label'                 => __( 'Checked', 'xstore-core' ),
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_color_checked',
            [
                'label'                 => __( 'Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"]:checked:before, {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]:checked:before' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
	
	    $this->add_responsive_control(
		    'radio_checkbox_border_width_checked',
		    [
			    'label'                 => __( 'Border Width', 'xstore-core' ),
			    'type'                  => \Elementor\Controls_Manager::SLIDER,
			    'range'                 => [
				    'px'        => [
					    'min'   => 0,
					    'max'   => 15,
					    'step'  => 1,
				    ],
			    ],
			    'size_units'            => [ 'px' ],
			    'selectors'             => [
				    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"]:checked, {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]:checked' => 'border-width: {{SIZE}}{{UNIT}}',
			    ],
			    'condition'             => [
				    'custom_radio_checkbox' => 'yes',
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'radio_checkbox_border_color_checked',
		    [
			    'label'                 => __( 'Border Color', 'xstore-core' ),
			    'type'                  => \Elementor\Controls_Manager::COLOR,
			    'default'               => '',
			    'selectors'             => [
				    '{{WRAPPER}} .etheme-custom-radio-checkbox input[type="checkbox"]:checked, {{WRAPPER}} .etheme-custom-radio-checkbox input[type="radio"]:checked' => 'border-color: {{VALUE}}',
			    ],
			    'condition'             => [
				    'custom_radio_checkbox' => 'yes',
			    ],
		    ]
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label'                 => __( 'Submit Button', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'button_align',
			[
				'label'                 => __( 'Alignment', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::CHOOSE,
				'default'               => 'left',
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'xstore-core' ),
						'icon'    => 'eicon-h-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'xstore-core' ),
						'icon'    => 'eicon-h-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'xstore-core' ),
						'icon'    => 'eicon-h-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form > p:nth-last-of-type(1)'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'display:inline-block;'
				],
                'condition'             => [
                    'button_width_type' => 'custom',
                ],
			]
		);
        
        $this->add_control(
            'button_width_type',
            [
                'label'                 => __( 'Width Type', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SELECT,
                'default'               => 'custom',
                'options'               => [
                    'full-width'    => __( 'Full Width', 'xstore-core' ),
                    'custom'        => __( 'Custom', 'xstore-core' ),
                ],
                'prefix_class'          => 'etheme-contact-form-7-button-',
            ]
        );
        
        $this->add_responsive_control(
            'button_width',
            [
                'label'                 => __( 'Width', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '148',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'button_width_type' => 'custom',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'xstore-core' ),
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'button_margin',
            [
                'label'                 => __( 'Margin Top', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]',
				'separator'             => 'before',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_error_style',
            [
                'label'                 => __( 'Errors', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'error_messages_heading',
            [
                'label'                 => __( 'Error Messages', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::HEADING,
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_error_messages_style' );

        $this->start_controls_tab(
            'tab_error_messages_alert',
            [
                'label'                 => __( 'Alert', 'xstore-core' ),
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_alert_text_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-not-valid-tip' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );
        
        $this->add_responsive_control(
            'error_alert_spacing',
            [
                'label'                 => __( 'Spacing', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-not-valid-tip' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_error_messages_fields',
            [
                'label'                 => __( 'Fields', 'xstore-core' ),
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_field_bg_color',
            [
                'label'                 => __( 'Background Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-not-valid' => 'background: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_field_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-not-valid' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'                  => 'error_field_border',
				'label'                 => __( 'Border', 'xstore-core' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-not-valid',
				'separator'             => 'before',
				'condition'             => [
					'error_messages' => 'show',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_control(
            'validation_errors_heading',
            [
                'label'                 => __( 'Validation Errors', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::HEADING,
                'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_errors_bg_color',
            [
                'label'                 => __( 'Background Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-validation-errors' => 'background: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_errors_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-validation-errors' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'validation_errors_typography',
                'label'                 => __( 'Typography', 'xstore-core' ),
                'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-validation-errors',
				'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'                  => 'validation_errors_border',
				'label'                 => __( 'Border', 'xstore-core' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .etheme-contact-form-7 .wpcf7-validation-errors',
				'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'validation_errors_margin',
			[
				'label'                 => __( 'Margin', 'xstore-core' ),
				'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .etheme-contact-form-7 .wpcf7-validation-errors' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'validation_errors' => 'show',
				],
			]
		);
        
        $this->end_controls_section();

    }

    /**
     * Register contact form 7 widget controls.
     *
     * @since 2.1.3
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'contact-form', 'class', [
				'pp-contact-form',
				'etheme-contact-form-7',
			]
		);
        
        if ( $settings['labels_switch'] != 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'labels-hide' );
        }
        
        if ( $settings['placeholder_switch'] == 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'placeholder-show' );
        }
        
        if ( $settings['custom_radio_checkbox'] == 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'etheme-custom-radio-checkbox' );
        }
        
        if ( function_exists( 'wpcf7' ) ) {
            if ( ! empty( $settings['contact_form_list'] ) ) {
                ?>
                <div <?php echo $this->get_render_attribute_string( 'contact-form' ); ?>>
                    <?php if ( $settings['form_title'] == 'yes' || $settings['form_description'] == 'yes' ) { ?>
                        <div class="etheme-contact-form-7-heading">
                            <?php if ( $settings['form_title'] == 'yes' && $settings['form_title_text'] != '' ) { ?>
                                <h3 class="pp-contact-form-title etheme-contact-form-7-title">
                                    <?php echo esc_attr( $settings['form_title_text'] ); ?>
                                </h3>
                            <?php } ?>
                            <?php if ( $settings['form_description'] == 'yes' && $settings['form_description_text'] != '' ) { ?>
                                <div class="pp-contact-form-description etheme-contact-form-7-description">
                                    <?php echo $this->parse_text_editor( $settings['form_description_text'] ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php echo do_shortcode( '[contact-form-7 id="' . $settings['contact_form_list'] . '" ]' ); ?>
                </div>
                <?php
            }
        }
    }
}