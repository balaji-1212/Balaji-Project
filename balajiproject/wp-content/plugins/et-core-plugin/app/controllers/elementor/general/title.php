<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Title widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Title extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-title';
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
		return __( 'Title With Text', 'xstore-core' );
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
		return 'eicon-post-title eight_theme-element-icon';
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
		return [ 'title' ];
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
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'settings_title',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'use_custom_fonts_title',
			[
				'label' 		=>	__( 'Use Custom Font ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'title_custom_tag',
			[
				'label' 		=>	__( 'Title HTML Tag', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'condition' => ['use_custom_fonts_title' => 'true'],
				'options' 		=>	[
					'h1'			=> esc_html__( 'H1', 'xstore-core' ), 
					'h2'			=> esc_html__( 'H2', 'xstore-core' ), 
					'h3'			=> esc_html__( 'H3', 'xstore-core' ), 
					'h4'			=> esc_html__( 'H4', 'xstore-core' ), 
					'h5'			=> esc_html__( 'H5', 'xstore-core' ), 
					'h6'			=> esc_html__( 'H6', 'xstore-core' ), 
					'p'				=> esc_html__( 'P', 'xstore-core' ), 
					'div'			=> esc_html__( 'DIV', 'xstore-core' ), 
				],
				'default'		=> 'h3',
			]
		);

		$this->add_control(
			'title_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['use_custom_fonts_title' => 'true'],
				'selectors' => [
					'{{WRAPPER}} .banner-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'condition' => ['use_custom_fonts_title' => 'true'],
				'selector' => '{{WRAPPER}} .banner-title',
			]
		);

		$this->add_control(
			'title_textalign',
			[
				'label' 		=>	__( 'Text Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'condition' 	=> ['use_custom_fonts_title' => 'true'],
				'options' 		=>	[
					'left'				=> esc_html__( 'Left', 'xstore-core' ), 
					'right'				=> esc_html__( 'Right', 'xstore-core' ), 
					'center'			=> esc_html__( 'Center', 'xstore-core' ), 
					'justify'			=> esc_html__( 'Justify', 'xstore-core' ), 
				],
				'selectors' => [
					'{{WRAPPER}} .banner-title' => 'text-align: {{VALUE}};',
				],
				'default'		=> 'left',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings_subtitle',
			[
				'label' => __( 'Subtitle', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subitle', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'use_custom_fonts_subtitle',
			[
				'label' 		=>	__( 'Use Custom Font ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'subtitle_custom_tag',
			[
				'label' 		=>	__( 'Subtitle HTML Tag', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'options' 		=>	[
					'h1'			=> esc_html__( 'H1', 'xstore-core' ), 
					'h2'			=> esc_html__( 'H2', 'xstore-core' ), 
					'h3'			=> esc_html__( 'H3', 'xstore-core' ), 
					'h4'			=> esc_html__( 'H4', 'xstore-core' ), 
					'h5'			=> esc_html__( 'H5', 'xstore-core' ), 
					'h6'			=> esc_html__( 'H6', 'xstore-core' ), 
					'p'				=> esc_html__( 'P', 'xstore-core' ), 
					'div'			=> esc_html__( 'DIV', 'xstore-core' ), 
				],
				'default'		=> 'h3',
			]
		);

		$this->add_control(
			'subtitle_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typo',
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'selector' => '{{WRAPPER}} .banner-subtitle',
			]
		);


		$this->add_control(
			'subtitle_textalign',
			[
				'label' 		=>	__( 'Text Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'condition' 	=> ['use_custom_fonts_subtitle' => 'true'],
				'options' 		=>	[
					'left'				=> esc_html__( 'Left', 'xstore-core' ), 
					'right'				=> esc_html__( 'Right', 'xstore-core' ), 
					'center'			=> esc_html__( 'Center', 'xstore-core' ), 
					'justify'			=> esc_html__( 'Justify', 'xstore-core' ), 
				],
				'selectors' => [
					'{{WRAPPER}} .banner-subtitle' => 'text-align: {{VALUE}};',
				],
				'default'		=> 'left',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings_divider',
			[
				'label' => __( 'Divider', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'divider',
			[
				'label' 		=>	__( 'Divider Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'0'					 => __('unset', 'xstore-core'), 
					'line-through' 		 => __('Line through', 'xstore-core'), 
					'line-through-short' => __('Line through short', 'xstore-core'),
					'line-under'		 => __('Line under', 'xstore-core'),
				],
				'default'		=> '0'
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' 	=> __( 'Divider Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'divider',
							'operator'  => '!=',
							'value' 	=> '0'
						]
					]
				]
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Divider Width', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'divider',
							'operator'  => '!=',
							'value' 	=> '0'
						]
					]
				]
			]
		);

		$this->add_control(
			'class',
			[
				'label' => __( 'Extra Class', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title_google_fonts 	= ( 'true' ==  $settings['use_custom_fonts_title'] ) ? "title_google_fonts=''" : '';
		$subtitle_google_fonts  = ( 'true' ==  $settings['use_custom_fonts_subtitle'] ) ? "subtitle_google_fonts=''" : '';
		$title_custom_tag  		= isset( $settings['title_custom_tag'] ) ? "title_font_container=tag:" . $settings['title_custom_tag'] : '';
		$subtitle_custom_tag  	= isset( $settings['subtitle_custom_tag'] ) ? "subtitle_font_container=tag:" . $settings['subtitle_custom_tag'] : '';

		echo do_shortcode( '[title 
			title="'. $settings['title'] .'"
			use_custom_fonts_title="'. $settings['use_custom_fonts_title'] .'"
			'. $title_google_fonts . '
			'. $title_custom_tag . '
			'. $subtitle_custom_tag . '
			subtitle="'. $settings['subtitle'] .'"
			use_custom_fonts_subtitle="'. $settings['use_custom_fonts_subtitle'] .'"
			'. $subtitle_google_fonts . '
			divider="'. $settings['divider'] .'"
			divider_color="'. $settings['divider_color'] .'"
			divider_width="'. $settings['divider_width'] .'"
			class="'. $settings['class'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
