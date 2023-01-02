<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Follow widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Follow extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-follow';
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
		return __( 'Social Links', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-social-links';
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
		return [ 'social-links', 'follow' ];
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
	 * Register follow widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'facebook',
			[
				'label' => __( 'Facebook Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'twitter',
			[
				'label' => __( 'Twitter Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'instagram',
			[
				'label' => __( 'Instagram Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => '#'
			]
		);

		$this->add_control(
			'pinterest',
			[
				'label' => __( 'Pinterest Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'linkedin',
			[
				'label' => __( 'LinkedIn Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'tumblr',
			[
				'label' => __( 'Tumblr Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'youtube',
			[
				'label' => __( 'YouTube Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => '#'
			]
		);

		$this->add_control(
			'telegram',
			[
				'label' => __( 'Telegram Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => '#'
			]
		);
		
		$this->add_control(
			'whatsapp',
			[
				'label' => __( 'Whatsapp Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'snapchat',
			[
				'label' => __( 'Snapchat Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'etsy',
			[
				'label' => __( 'Etsy Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'tik-tok',
			[
				'label' => __( 'Tik-Tok Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'strava',
			[
				'label' => __( 'Strava Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'cafecito',
			[
				'label' => __( 'Cafecito Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'dribbble',
			[
				'label' => __( 'Dribbble Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'kofi',
			[
				'label' => __( 'Kofi Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'line',
			[
				'label' => __( 'Line Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'patreon',
			[
				'label' => __( 'Patreon Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'reddit',
			[
				'label' => __( 'Reddit Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'discord',
			[
				'label' => __( 'Discord Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'vimeo',
			[
				'label' => __( 'Vimeo Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'rss',
			[
				'label' => __( 'RSS Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'vk',
			[
				'label' => __( 'VK Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'houzz',
			[
				'label' => __( 'Houzz Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'tripadvisor',
			[
				'label' => __( 'Tripadvisor Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'email',
			[
				'label' => __( 'Email Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'target',
			[
				'label' 		=>	__( 'Links target', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'_self'		=>	esc_html__('Current window', 'xstore-core'),
					'_blank'	=>	esc_html__('Blank', 'xstore-core'),
				],
				'default' => '_blank'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'normal'	=>	esc_html__('Normal', 'xstore-core'),
					'small'		=>	esc_html__('Small', 'xstore-core'),
					'large'		=>	esc_html__('Large', 'xstore-core'),
					'custom'	=>	esc_html__('Custom', 'xstore-core'),
				],
				'default'		=> 'normal',
			]
		);

		$this->add_responsive_control(
			'custom_size',
			[
				'label' => __( 'Custom Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
					'rem' => [
						'min' => 0,
						'max' => 50,
					],
					'%' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'condition' => ['size' => 'custom'],
				'selectors'    => [
					'{{WRAPPER}} .et-follow-buttons a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spacing',
			[
				'label' => __( 'Space Between', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
					'rem' => [
						'min' => 0,
						'max' => 50,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'    => [
					'{{WRAPPER}} .et-follow-buttons a' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default'		=> 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .et-follow-buttons' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filled',
			[
				'label' 		=>	__( 'Filled Icons', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
					'em' => [
						'min' => 0,
						'max' => 30,
					],
					'rem' => [
						'min' => 0,
						'max' => 30,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => ['filled' => 'true'],
				'selectors'    => [
					'{{WRAPPER}} .et-follow-buttons a' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'icons_style_settings',
			[
				'label' => __( 'Icons', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);

		$this->add_control(
			'icons_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icons_bg',
			[
				'label' 	=> __( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'condition' => [ 'filled' => 'true' ],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);

		$this->add_control(
			'icons_color_hover',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icons_bg_hover',
			[
				'label' 	=> __( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'condition' => [ 'filled' => 'true' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icons_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'condition' => [ 'filled' => 'true' ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .et-follow-buttons > a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render follow widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// unset value and make with css selectors 
		$settings['align'] = '';

		echo do_shortcode( '[follow 
			facebook="'. $settings['facebook'] .'"
			twitter="'. $settings['twitter'] .'"
			instagram="'. $settings['instagram'] .'"
			pinterest="'. $settings['pinterest'] .'"
			linkedin="'. $settings['linkedin'] .'"
			tumblr="'. $settings['tumblr'] .'"
			youtube="'. $settings['youtube'] .'"
			telegram="'. $settings['telegram'] .'"
			whatsapp="'. $settings['whatsapp'] .'"
			snapchat="'. $settings['snapchat'] .'"
			etsy="'. $settings['etsy'] .'"
			tik-tok="'. $settings['tik-tok'] .'"
			strava="'. $settings['strava'] .'"
			cafecito="'. $settings['cafecito'] .'"
			dribbble="'. $settings['dribbble'] .'"
			kofi="'. $settings['kofi'] .'"
			line="'. $settings['line'] .'"
			patreon="'. $settings['patreon'] .'"
			reddit="'. $settings['reddit'] .'"
			discord="'. $settings['discord'] .'"
			email="'. $settings['email'] .'"
			vimeo="'. $settings['vimeo'] .'"
			rss="'. $settings['rss'] .'"
			vk="'. $settings['vk'] .'"
			houzz="'. $settings['houzz'] .'"
			tripadvisor="'. $settings['tripadvisor'] .'"
			target="'. $settings['target'] .'"
			size="'. $settings['size'] .'"
			align="'. $settings['align'] .'"
			filled="'. $settings['filled'] .'"
			icons_color="'. $settings['icons_color'] .'"
			icons_color_hover="'. $settings['icons_color_hover'] .'"
			icons_bg="'. $settings['icons_bg'] .'"
			icons_bg_hover="'. $settings['icons_bg_hover'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
