<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Modal Popup widget.
 *
 * @since      4.1
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Modal_Popup extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_modal_popup';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Modal Popup', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-modal-popup';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'modal', 'dialog', 'popup box', 'overlay box', 'video', 'player', 'embed', 'youtube', 'vimeo', 'dailymotion' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.1
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
		return [ 'etheme_modal_popup' ];
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
		return [ 'etheme-elementor-modal-popup' ];
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
	 * @since 4.1
	 * @access protected
	 */
	protected function register_controls() {
		
		$this->register_button_section();
		
		$this->start_controls_section(
			'section_popup_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
            ]
		);
		
		$this->add_control(
			'popup_content_type',
			[
				'label' 		=>	__( 'Content Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' => $this->get_saved_content_list(),
				'default'	=> 'custom'
			]
		);
		
		$this->add_control(
			'popup_save_template_info',
			[
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => sprintf( __( 'Create template in Templates -> <a href="%s" target="_blank">Saved Templates</a> -> Choose ready to use template or go to Saved Templates and create new one.', 'xstore-core' ), admin_url('edit.php?post_type=elementor_library&tabs_group=library') ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'popup_content_type' => 'saved_template'
                ]
			]
		);
		
		$this->add_control(
			'popup_content',
			[
				'type'        => \Elementor\Controls_Manager::WYSIWYG,
				'label'       => __( 'Modal Content', 'xstore-core' ),
				'description' => __( 'Content that will be displayed in Modal Popup.', 'xstore-core' ),
				'condition'   => [
					'popup_content_type' => 'custom',
				],
                'default' => '<p>'.esc_html__('You can add any HTML here', 'xstore-core').'<br/>'.
                             __('We suggest you to create a Saved Template in Dashboard -> Templates -> Saved Templates and use it by switching content type above to Saved template.', 'xstore-core').'</p>'
			]
		);
		
		$this->add_control(
			'global_widget',
			[
				'label' => __( 'Global Widget', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_saved_content( 'widget' ),
				'default' => 'select',
				'condition' => [
					'popup_content_type' => 'global_widget'
				],
			]
		);
		
		$this->add_control(
			'saved_template',
			[
				'label' => __( 'Saved Template', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_saved_content(),
				'default' => 'select',
				'condition' => [
					'popup_content_type' => 'saved_template'
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_popup',
			[
				'label' => __( 'Popup', 'xstore-core' ),
			]
		);
		
		$this->add_responsive_control(
			'popup_width',
			[
				'label' => __( 'Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vw' ],
				'default' => [
					'size' => 640,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'popup_height_type',
			[
				'label' => __( 'Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'auto' => __( 'Fit To Content', 'xstore-core' ),
					'fit_to_screen' => __( 'Fit To Screen', 'xstore-core' ),
					'custom' => __( 'Custom', 'xstore-core' ),
				],
				'selectors_dictionary' => [
					'fit_to_screen' => '100vh',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'height: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'popup_height',
			[
				'label' => __( 'Custom Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'condition' => [
					'popup_height_type' => 'custom',
				],
				'default' => [
					'size' => 380,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'popup_popup_content_position',
			[
				'label' => __( 'Content Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'top' => __( 'Top', 'xstore-core' ),
					'center' => __( 'Center', 'xstore-core' ),
					'bottom' => __( 'Bottom', 'xstore-core' ),
				],
				'condition' => [
					'popup_height_type!' => 'fit_to_screen',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content-wrapper' => 'align-items: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'position_heading',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'popup_horizontal_position',
			[
				'label' => __( 'Horizontal', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content-wrapper' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
			]
		);
		
		$this->add_responsive_control(
			'popup_vertical_position',
			[
				'label' => __( 'Vertical', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
			]
		);
		
		$this->add_responsive_control(
			'popup_align',
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'popup_overlay',
			[
				'label' => __( 'Popup Overlay', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-overlay' => 'display: block;',
				],
				'separator' => 'before',
			]
		);
		
//		$this->add_control(
//			'popup_close_button',
//			[
//				'label' => __( 'Close Button', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SWITCHER,
//				'default' => 'yes',
//			]
//		);
		
		$this->add_responsive_control(
			'popup_entrance_animation',
			[
				'label' => __( 'Entrance Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'separator' => 'before',
                'default' => 'fadeIn'
			]
		);
		
		$this->add_responsive_control(
			'popup_exit_animation',
			[
				'label' => __( 'Exit Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::EXIT_ANIMATION,
				'frontend_available' => true,
                'default' => 'fadeInDown'
			]
		);
		
		$this->add_control(
			'popup_entrance_animation_duration',
			[
				'label' => __( 'Animation Duration', 'xstore-core' ) . ' (sec)',
				'type' => \Elementor\Controls_Manager::SLIDER,
				'frontend_available' => true,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'animation-duration: {{SIZE}}s',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'popup_entrance_animation',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'popup_exit_animation',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_popup_style',
			[
				'label' => __( 'Popup', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'popup_typography',
				'selector' => '{{WRAPPER}} .etheme-modal-popup-content',
			]
		);
		
		$this->add_control(
			'popup_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'  => 'popup_background',
				'selector' => '{{WRAPPER}} .etheme-modal-popup-content',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'  => 'popup_border',
				'selector' => '{{WRAPPER}} .etheme-modal-popup-content',
			]
		);
		
		$this->add_responsive_control(
			'popup_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'popup_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_box_shadow',
				'selector' => '{{WRAPPER}} .etheme-modal-popup-content',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'horizontal' => 2,
							'vertical' => 8,
							'blur' => 23,
							'spread' => 3,
							'color' => 'rgba(0,0,0,0.2)',
						],
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_popup_overlay',
			[
				'label' => __( 'Popup Overlay', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'popup_overlay' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'popup_overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .etheme-modal-popup-overlay',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(0,0,0,.8)',
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_popup_close_button',
			[
				'label' => __( 'Close Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//				'condition' => [
//					'popup_close_button!' => '',
//				],
			]
		);
		
		$this->add_control(
			'popup_close_button_position',
			[
				'label' => __( 'Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Inside', 'xstore-core' ),
					'outside' => __( 'Outside', 'xstore-core' ),
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_responsive_control(
			'popup_close_button_vertical',
			[
				'label' => __( 'Vertical Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'max' => 100,
						'min' => -100,
						'step' => 1,
					],
					'px' => [
						'max' => 100,
						'min' => -100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close' => 'top: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'popup_close_button_horizontal',
			[
				'label' => __( 'Horizontal Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'max' => 100,
						'min' => -100,
						'step' => 1,
					],
					'px' => [
						'max' => 100,
						'min' => -100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .etheme-modal-popup-close' => 'left: auto; right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .etheme-modal-popup-close' => 'right: auto; left: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->start_controls_tabs( 'close_button_style_tabs' );
		
		$this->start_controls_tab(
			'tab_x_button_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'popup_close_button_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'popup_close_button_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_x_button_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'popup_close_button_hover_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'close_button_hover_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'close_button_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'close_button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'close_button_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-modal-popup-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
	}
	
	/**
	 * Button settings.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function register_button_section() {
		
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Open Modal', 'xstore-core' ),
				'placeholder' => __( 'Open Modal', 'xstore-core' ),
			]
		);
		
		$this->add_responsive_control(
			'button_align',
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
			'button_min_width',
			[
				'label' => __( 'Min Width', 'xstore-core' ),
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
				'skin' => 'inline',
				'fa4compatibility' => 'icon',
				'label_block' => false,
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
			'icon_indent',
			[
				'label' => __( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'size' => 7
				],
				'selectors' => [
					'{{WRAPPER}} .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
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
				'name' => 'button_typography',
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
			'button_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button.button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
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
			'button_padding',
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
	
	}
	
	protected function get_modal_popup_content() {
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute(
			'modal-wrapper',
			[
				'class' => 'etheme-modal-popup-content-wrapper',
				'data-id' => $this->get_id(),
                'style' => 'display: none;',
//				'data-animation' => $settings['popup_animation']
			]
		);
		
		$this->add_render_attribute(
			'modal-close',
			[
				'class' => [
                    'etheme-modal-popup-close',
                    $settings['popup_close_button_position'] != '' ? 'outside' : 'inside'
                ]
			]
		);
		
//		if( 'pageload' == $settings['trigger_type'] ){
//			$delay = $settings['modal_box_popup_delay'];
//			$delay = $delay ? ( $delay * 1000 ) : 0;
//
//			$this->add_render_attribute( 'modal-content', 'data-display-delay', $delay );
//		}
		
		$this->add_render_attribute(
			'modal-content',
			[
				'class' => [
                    'etheme-modal-popup-content',
                    'animated'
                ]
			]
		);
		
		$this->add_render_attribute(
			'modal-content-inner',
			[
				'class' => [
					'etheme-modal-popup-inner',
				]
			]
		);
		
//		$is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		?>
        <div <?php $this->print_render_attribute_string( 'modal-wrapper' ); ?>>
        
			<?php if ( $settings['popup_overlay'] ) : ?>
                <div class="etheme-modal-popup-overlay"></div>
			<?php endif; ?>

            <div <?php $this->print_render_attribute_string( 'modal-content' ); ?>>
                <?php // if ( $settings['popup_close_button']) : ?>
                    <span <?php $this->print_render_attribute_string( 'modal-close' ); ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                      </svg>
                    </span>
                <?php // endif; ?>
                <div <?php $this->print_render_attribute_string( 'modal-content-inner' ); ?>>
					<?php
                        switch ($settings['popup_content_type']) {
                            case 'custom':
                                $this->print_unescaped_setting( 'popup_content' );
                                break;
                            case 'global_widget':
                            case 'saved_template':
                                if ( !empty( $settings[$settings['popup_content_type']] ) ):
    //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['popup_content_type']], true );
                                    $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings[$settings['popup_content_type']] );
                                    if (!$content) {
                                        echo esc_html__('We have imported popup template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                                    }
                                    else {
                                        echo $content;
                                    }
                                endif;
                                break;
                        }
					?>
                </div>
            </div>
        </div>
		<?php
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.1
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );
		
//		if ( ! empty( $settings['link']['url'] ) ) {
//			$this->add_link_attributes( 'button', $settings['link'] );
//			$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
//		}
		
		$this->add_render_attribute( 'button', 'class', ['elementor-button', 'etheme-modal-popup-button'] );
		$this->add_render_attribute( 'button', 'role', 'button' );
		$this->add_render_attribute( 'button', 'data-popup-id', $this->get_id());
		
//		if ( ! empty( $settings['button_css_id'] ) ) {
//			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
//		}
		
//		if ( ! empty( $settings['size'] ) ) {
//			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
//		}
		
//		if ( $settings['hover_animation'] ) {
//			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
//		}
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <a <?php $this->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text(); ?>
            </a>
        </div>
        
		<?php
        
        $this->get_modal_popup_content();
    }
	
	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'button_text',
			[
				'class' => 'button-text',
			]
		);
		
		if ( $settings['icon_align'] == 'left')
			$this->render_icon( $settings );
		
		?>
        <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>><?php echo $settings['button_text']; ?></span>
		
		<?php
		if ( $settings['icon_align'] == 'right')
			$this->render_icon( $settings );
	}
	
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
	
	/**
	 * Get all elementor page templates
	 *
	 * @return array
	 */
	protected function get_saved_content_list() {
		$content_list = [
			'custom'   => __( 'Custom', 'xstore-core' ),
			'saved_template' => __( 'Saved Template', 'xstore-core' ),
		];
		
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_list['global_widget'] = __( 'Global Widget', 'xstore-core' );
		}
		return $content_list;
	}
	
	protected function get_post_template( $term = 'page' ) {
		$posts = get_posts(
			[
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => [
					[
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $term,
					],
				],
			]
		);
		
		$templates = [];
		foreach ( $posts as $post ) {
			$templates[] = [
				'id'   => $post->ID,
				'name' => $post->post_title,
			];
		}
		return $templates;
	}
	
	protected function get_saved_content( $term = 'section' ) {
		$saved_contents = $this->get_post_template( $term );
		
		if ( count( $saved_contents ) > 0 ) {
			foreach ( $saved_contents as $saved_content ) {
				$content_id             = $saved_content['id'];
				$options[ $content_id ] = $saved_content['name'];
			}
		} else {
			$options['no_template'] = __( 'Nothing Found', 'xstore-core' );
		}
		return $options;
	}
	
	public function on_import( $element ) {
		return \Elementor\Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
	}
}
