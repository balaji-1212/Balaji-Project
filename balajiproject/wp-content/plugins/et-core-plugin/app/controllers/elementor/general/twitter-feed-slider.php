<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Twitter Feed Slider widget.
 *
 * @since      4.0.11
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Twitter_Feed_Slider extends \Elementor\Widget_Base {
	
	use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_twitter_feed_slider';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Twitter Feed Slider', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-twitter-feed-slider';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.11
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'slider', 'twitter', 'social', 'embed', 'video', 'comment', 'hashtag' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.11
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
	 * @since 4.0.11
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'etheme_elementor_slider' ];
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
		return [ 'etheme-elementor-twitter-feed' ];
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
	 * @since 4.0.11
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
			'consumer_key',
			[
				'label' 		=> __( 'Consumer Key', 'xstore-core' ),
				'description' => '<a href="https://apps.twitter.com/" target="_blank">Get Consumer Key </a> by creating a new app or selecting an existing app',
				'type'			=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
		$this->add_control(
			'consumer_secret',
			[
				'label' 		=> __( 'Consumer Secret', 'xstore-core' ),
				'description' => '<a href="https://apps.twitter.com/" target="_blank">Get Consumer Secret</a> by creating a new app or selecting an existing app',
				'type'			=> \Elementor\Controls_Manager::TEXT,
			]
		);
		
//      not vital for this
//		$this->add_control(
//			'access_token',
//			[
//				'label' 		=> __( 'Access token', 'xstore-core' ),
//				'type'			=> \Elementor\Controls_Manager::TEXT,
//			]
//		);
//
//		$this->add_control(
//			'access_token_secret',
//			[
//				'label' 		=> __( 'Access token secret', 'xstore-core' ),
//				'type'			=> \Elementor\Controls_Manager::TEXT,
//			]
//		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_tweets_settings',
			[
				'label' => esc_html__( 'Tweets', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bordered',
				'options' => [
					'default' => esc_html__('Default', 'xstore-core'),
					'bordered' => esc_html__('Bordered', 'xstore-core'),
				],
			]
		);
		
		$this->add_control(
			'tweets_type',
			[
				'label' => __( 'Type Of Tweets', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'account',
				'options' => [
					'account' => esc_html__('Account Timeline', 'xstore-core'),
					'hashtag' => esc_html__('Hashtag Timeline', 'xstore-core'),
				],
			]
		);
		
		$this->add_control(
			'username',
			[
				'label' 		=> __( 'Username', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'default' => 'WooCommerce',
				'condition' => [
					'tweets_type' => 'account'
				]
			]
		);
		
		$this->add_control(
			'hashtag',
			[
				'label' 		=> __( 'Hashtag', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'default' => '#wordpress',
				'condition' => [
					'tweets_type' => 'hashtag'
				]
			]
		);
		
		$this->add_control(
			'limit',
			[
				'label' => __('Tweets Limit', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'default' => 9
			]
		);
		
		$this->add_control(
			'cache_results',
			[
				'label' 		=>	__( 'Cache Results', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_settings',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'time',
			[
				'label' 		=>	__( 'Time Posted', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		
		$this->add_control(
			'time_posted_type',
			[
				'label' => __( 'Time Posted Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'ago' => __('3 hours ago', 'xstore-core'),
					'date' => __('12 January 2021', 'xstore-core'),
				],
				'condition' => [
					'time' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
//					'0' => __( 'March 6, 2018 (F j, Y)', 'xstore-core' ),
//					'1' => '2018-03-06 (Y-m-d)',
//					'2' => '03/06/2018 (m/d/Y)',
//					'3' => '06/03/2018 (d/m/Y)',
					'custom' => __( 'Custom', 'xstore-core' ),
				],
				'condition' => [
					'time' => 'yes',
					'time_posted_type' => 'date'
				],
			]
		);
		
		$this->add_control(
			'custom_date_format',
			[
				'label' => __( 'Custom Date Format', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'g:i A F j, Y',
				'condition' => [
					'time' => 'yes',
					'time_posted_type' => 'date',
					'date_format' => 'custom',
				],
				'description' => sprintf(
				/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					__( 'Use the letters: %s', 'xstore-core' ),
					'l D d j S F m M n Y y'
				),
			]
		);
		
		$this->add_control(
			'time_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'time' => 'yes',
					'time_posted_type' => 'date'
				],
			]
		);
		
		$this->add_control(
			'avatar_type',
			[
				'label' => __( 'Avatar', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'avatar',
				'options' => [
					'icon' => __('Twitter Icon', 'xstore-core'),
					'avatar' => __('Account avatar', 'xstore-core'),
					'none' => __('None', 'xstore-core'),
				],
			]
		);
		
		$this->add_control(
			'author_name',
			[
				'label' 		=>	__( 'Author Name', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		
		$this->add_control(
			'text_limit_type',
			[
				'label'       => esc_html__( 'Limit Text By', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'chars' => esc_html__('Chars', 'xstore-core'),
					'words' => esc_html__('Words', 'xstore-core'),
					'none' => esc_html__('None', 'xstore-core'),
				],
				'default' => 'none',
			]
		);
		
		$this->add_control(
			'text_limit',
			[
				'label'      => esc_html__( 'Limit', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'condition' => [
					'text_limit_type' => ['chars', 'words']
				]
			]
		);
		
		$this->add_control(
			'read_more',
			[
				'label' 		=>	__( 'Read More', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes'
			]
		);
		
		$this->add_control(
			'footer',
			[
				'label' 		=>	__( 'Footer', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		
		$this->add_control(
			'footer_share_socials',
			[
				'label' 		=>	__( 'Share Socials', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> true,
				'multiple' 	=>	true,
				'options' 		=> array(
                    'facebook' => __('Facebook', 'xstore-core'),
                    'twitter' => __('Twitter', 'xstore-core'),
                    'linkedin' => __('Linkedin', 'xstore-core'),
                    'vk' => __('Vk', 'xstore-core'),
                    'pinterest' => __('Pinterest', 'xstore-core'),
                    'whatsapp' => __('Whatsapp', 'xstore-core'),
                ),
                'default' => array(
                    'facebook',
                    'twitter',
                    'linkedin'
                ),
                'condition' => [
                    'footer!' => ''
                ]
			]
		);
		
		$this->end_controls_section();
		
		// slider settings
		Elementor::get_slider_general_settings($this);
		
		$this->start_controls_section(
			'section_tweet_style',
			[
				'label' => __( 'Tweet', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'alignment',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
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
				'prefix_class' => 'elementor%s-align-',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-tweet'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-tweet',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => 1,
							'left' => 1,
							'right' => 1,
							'bottom' => 1,
						],
					],
					'color' => [
						'default' => '#e1e1e1',
					]
				],
				'condition' => [
					'style' => 'bordered'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-tweet',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-tweet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-tweet' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_avatar_style',
			[
				'label' => __( 'Avatar', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'avatar_type!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'avatar_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-author-avatar' => 'color: {{VALUE}};',
				],
				'condition' => [
					'avatar_type' => 'icon'
				]
			]
		);
		
		$this->add_responsive_control(
			'avatar_size',
			[
				'label'		 =>	esc_html__( 'Size', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 70,
						'step' 	=> 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-author-avatar' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme-twitter-feed-author-avatar img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'avatar_space',
			[
				'label'		 =>	esc_html__( 'Aside Space', 'xstore-core' ),
				'type' 		 => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' 		=> [
						'min' 	=> 0,
						'max' 	=> 50,
						'step' 	=> 1
					],
				],
				'condition' => [
					'author_name!' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-author-avatar' => 'margin-inline-end: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_author_style',
			[
				'label' => __( 'Author', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'author_name!' => ''
                ]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'author_name_typography',
				'label' => esc_html__('Author Name', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-author-name',
			]
		);
		
		$this->start_controls_tabs('author_name_style_tabs');
		
		$this->start_controls_tab( 'author_name_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'author_name_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-author-name' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'author_name_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'author_name_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-author-name:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__('Typography', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-text',
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-text, {{WRAPPER}} .etheme-twitter-feed-text a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->start_controls_tabs('text_links_style_tabs');
		
		$this->start_controls_tab( 'text_links_style_normal',
			[
				'label' => esc_html__('Links Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'text_link_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-text a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'text_links_style_hover',
			[
				'label' => esc_html__('Links Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'text_link_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-text a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'text_space',
			[
				'label'		 =>	esc_html__( 'Bottom Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-twitter-feed-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// time
		$this->start_controls_section(
			'section_time_style',
			[
				'label' => __( 'Date / Time', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'time!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'time_typography',
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-time',
			]
		);
		
		$this->start_controls_tabs('time_style_tabs');
		
		$this->start_controls_tab( 'time_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'time_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-time' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'time_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'time_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-time:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'time_space',
			[
				'label'		 =>	esc_html__( 'Bottom Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-twitter-feed-time' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// read more
		$this->start_controls_section(
			'section_read_more_style',
			[
				'label' => __( 'Read More', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'read_more!' => ''
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typography',
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-more',
			]
		);
		
		$this->start_controls_tabs('read_more_style_tabs');
		
		$this->start_controls_tab( 'read_more_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'read_more_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-more' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'read_more_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'read_more_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-more:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'read_more_space',
			[
				'label'		 =>	esc_html__( 'Bottom Space', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-twitter-feed-more' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// footer links
		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => __( 'Footer', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'footer!' => ''
				]
			]
		);
		
		$this->add_responsive_control(
			'footer_align',
			[
				'label' => esc_html__( 'Horizontal Align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
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
					'{{WRAPPER}} .etheme-twitter-feed-footer' => 'justify-content: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'footer_items_gap',
			[
				'label' => __( 'Items Gap', 'xstore-core' ),
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
					'{{WRAPPER}} .etheme-twitter-feed-footer' => '--footer-items-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'footer_typography',
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-footer',
			]
		);
		
		$this->start_controls_tabs('footer_style_tabs');
		
		$this->start_controls_tab( 'footer_style_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'footer_color',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-footer' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'footer_style_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'footer_color_hover',
			[
				'label' => esc_html__('Color', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-icon:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'footer_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-twitter-feed-footer',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => 1,
                            'left' => 0,
                            'right' => 0,
                            'bottom' => 0,
						],
					],
					'color' => [
						'default' => '#e1e1e1',
					]
				],
			]
		);
		$this->add_responsive_control(
			'footer_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-twitter-feed-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// slider style settings
		Elementor::get_slider_style_settings($this);
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.11
	 * @access protected
	 */
	public function render() {
	 
		if ( ! class_exists( 'TwitterOAuth' ) ) {
			return;
		}
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', [
			'class' =>
                [
                    'etheme-twitter-feed-tweets-slider',
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
		$this->add_render_attribute( 'tweets-wrapper', 'class', 'swiper-wrapper');
		
		$this->add_render_attribute( 'tweet', [
			'class' =>
                [
                    'etheme-twitter-feed-tweet',
                    'swiper-slide'
                ]
		]);
		
		$this->add_render_attribute( 'tweet-header', [
			'class' => 'etheme-twitter-feed-header',
		]);
		
		$this->add_render_attribute( 'tweet-text', [
			'class' => 'etheme-twitter-feed-text',
		]);
		
		$this->add_render_attribute( 'tweet-more-wrapper', [
			'class' => 'etheme-twitter-feed-more-wrapper',
		]);
		
		$this->add_render_attribute( 'tweet-footer', [
			'class' => 'etheme-twitter-feed-footer',
		]);
		
		$this->add_render_attribute( 'tweet-icon', [
			'class' => 'etheme-twitter-feed-icon',
		]);
		
		$this->add_render_attribute( 'tweet-icon-count', [
			'class' => 'etheme-twitter-feed-icon-count',
		]);
		
		$this->add_render_attribute( 'tweet-icon-popup', [
			'class' => 'etheme-twitter-feed-icon-popup',
		]);
		
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		$tweets = $this->get_tweets($settings, $edit_mode);
		
		if ( !$tweets ) return; ?>
            
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

                <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
                    
                    <div <?php $this->print_render_attribute_string( 'tweets-wrapper' ); ?>>
                    
                        <?php foreach ( $tweets as $t ) { ?>
                        
                        <div <?php $this->print_render_attribute_string( 'tweet' ); ?>>
                            
                            <?php if ( $settings['avatar_type'] != 'none' || $settings['author_name'] ) : ?>
    
                                <div <?php $this->print_render_attribute_string( 'tweet-header' ); ?>>
                                    <?php // if ( $show_avatar ) : ?>
                                        <?php if ( $settings['avatar_type'] != 'none' ) : ?>
                                            <a <?php $this->print_render_attribute_string( 'tweet-author-avatar'.$t['id_str'] ); ?>>
                                            <?php if ( $settings['avatar_type'] == 'avatar' ) : ?>
                                                <img <?php $this->print_render_attribute_string( 'tweet-author-avatar-image'.$t['id_str'] ); ?>>
                                            <?php else: ?>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M24 4.56c-0.888 0.384-1.848 0.648-2.832 0.768 1.032-0.6 1.8-1.56 2.16-2.712-0.96 0.576-1.992 0.96-3.12 1.2-0.912-0.96-2.184-1.56-3.6-1.56-2.712 0-4.92 2.208-4.92 4.92 0 0.384 0.024 0.768 0.12 1.128-4.080-0.192-7.704-2.16-10.152-5.136-0.432 0.744-0.672 1.584-0.672 2.496 0 1.704 0.888 3.216 2.184 4.080-0.768-0.024-1.56-0.264-2.208-0.624 0 0.024 0 0.024 0 0.048 0 2.4 1.704 4.368 3.936 4.824-0.384 0.12-0.84 0.168-1.296 0.168-0.312 0-0.624-0.024-0.936-0.072 0.648 1.944 2.448 3.384 4.608 3.432-1.68 1.32-3.792 2.088-6.096 2.088-0.408 0-0.792-0.024-1.176-0.072 2.184 1.416 4.752 2.208 7.56 2.208 9.048 0 14.016-7.512 14.016-13.992 0-0.216 0-0.432-0.024-0.624 0.96-0.72 1.776-1.584 2.448-2.568z"></path>
                                                </svg>
                                            <?php endif; ?>
                                        </a>
                                        <?php endif;
                        
                                    if ( $settings['author_name'] ) : ?>
                                        <a <?php $this->print_render_attribute_string( 'tweet-author-name'.$t['id_str'] ); ?>>
                                            <?php echo esc_html($t['name']); ?>
                                            <?php if ( $t['verified'] ) : ?>
                                                <svg viewBox="0 0 24 24" width="1em" height="1em" fill="#1d9bf0" style="vertical-align: middle;">
                                                    <path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path>
                                                </svg>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                    
                                </div>
                                
                            <?php endif; ?>
                            
                            <div <?php $this->print_render_attribute_string( 'tweet-text' ); ?>>
                                <?php
                                echo wp_kses(
                                    $t['text'],
                                    array(
                                        'a' => array(
                                            'href'   => true,
                                            'target' => true,
                                            'rel'    => true,
                                        ),
                                    )
                                );
                                
                                ?>
                            </div>
    
                            <?php if ( $settings['read_more'] ) : ?>
                                <div <?php $this->print_render_attribute_string( 'tweet-more-wrapper' ); ?>>
                                    <a <?php $this->print_render_attribute_string( 'tweet-more'.$t['id_str'] ); ?>>
                                        <?php echo esc_html__('Read More', 'xstore-core'); ?>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M23.928 11.832c-0.048-0.12-0.12-0.216-0.168-0.264l-7.776-8.16c-0.12-0.144-0.288-0.216-0.48-0.24-0.168 0-0.36 0.048-0.48 0.168-0.288 0.24-0.312 0.672-0.048 0.984l6.648 6.984h-20.904c-0.384 0-0.696 0.312-0.696 0.696s0.312 0.672 0.696 0.672h20.904l-6.624 7.008c-0.12 0.144-0.192 0.312-0.168 0.48 0 0.192 0.096 0.36 0.216 0.48 0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.48-0.192l7.776-8.16c0 0 0.168-0.264 0.168-0.336 0.024-0.12 0.024-0.216-0.024-0.336z"></path>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $t['time'] ) : ?>
                                <a <?php $this->print_render_attribute_string( 'tweet-time'.$t['id_str'] ); ?>>
                                    <?php echo $t['time']; ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( $settings['footer'] ) : ?>
    
                                <div <?php $this->print_render_attribute_string( 'tweet-footer' ); ?>>
                                    
                                    <a <?php $this->print_render_attribute_string( 'tweet-in_reply_to'.$t['id_str'] ); ?>>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21.288 0.528h-18.6c-1.44 0-2.64 1.176-2.64 2.64v12.744c0 1.44 1.176 2.64 2.64 2.64h2.52l2.256 4.56c0.096 0.216 0.336 0.384 0.6 0.384 0.24 0 0.456-0.12 0.6-0.36l2.256-4.536h10.368c1.44 0 2.64-1.176 2.64-2.64v-12.792c0-1.44-1.176-2.64-2.64-2.64zM22.632 3.168v12.744c0 0.72-0.576 1.296-1.296 1.296h-10.824c-0.264 0-0.504 0.144-0.6 0.36l-1.848 3.696-1.848-3.696c-0.096-0.216-0.336-0.384-0.6-0.384h-2.928c-0.696 0-1.272-0.576-1.272-1.272v-12.744c0-0.72 0.576-1.296 1.296-1.296h18.624c0.72 0 1.296 0.576 1.296 1.296z"></path>
                                        </svg>
                                    </a>
                                    
                                    <a <?php $this->print_render_attribute_string( 'tweet-retweet'.$t['id_str'] ); ?>>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M22.32 6.12c0.216-0.936 0.672-2.808 0.792-3.744 0.048-0.384-0.048-0.648-0.408-0.744-0.384-0.12-0.624 0.072-0.792 0.504-0.216 0.576-0.576 2.472-0.576 2.472-1.56-2.016-3.936-3.552-6.528-4.2l-0.096-0.024c-3.096-0.744-6.24-0.192-8.928 1.416-5.352 3.264-6.816 9.504-5.064 14.256 0.072 0.168 0.264 0.312 0.48 0.36 0.168 0.048 0.336-0.024 0.456-0.072l0.024-0.024c0.312-0.144 0.456-0.504 0.288-0.888-1.536-4.392 0-9.744 4.656-12.504 2.352-1.392 5.040-1.824 7.824-1.152 2.088 0.504 4.296 1.776 5.664 3.6 0 0-1.92 0-2.568 0.024-0.48 0-0.72 0.12-0.792 0.456-0.096 0.36 0.096 0.744 0.456 0.768 1.176 0.072 4.248 0.096 4.248 0.096 0.12 0 0.144 0 0.288-0.024s0.312-0.144 0.408-0.264c0.072-0.12 0.168-0.312 0.168-0.312zM1.608 17.952c-0.216 0.936-0.648 2.808-0.792 3.744-0.048 0.384 0.048 0.648 0.408 0.744 0.384 0.096 0.624-0.096 0.792-0.528 0.216-0.576 0.576-2.472 0.576-2.472 1.56 2.016 3.96 3.552 6.552 4.2l0.096 0.024c3.096 0.744 6.24 0.192 8.928-1.416 5.352-3.24 6.816-9.504 5.064-14.256-0.072-0.168-0.264-0.312-0.48-0.36-0.168-0.048-0.336 0.024-0.456 0.072l-0.024 0.024c-0.312 0.144-0.456 0.504-0.288 0.888 1.536 4.392 0 9.744-4.656 12.504-2.352 1.392-5.040 1.824-7.824 1.152-2.088-0.504-4.296-1.776-5.664-3.6 0 0 1.92 0 2.568-0.024 0.48 0 0.72-0.12 0.792-0.456 0.096-0.36-0.096-0.744-0.456-0.768-1.176-0.072-4.248-0.096-4.248-0.096-0.12 0-0.144 0-0.288 0.024s-0.312 0.144-0.408 0.264c-0.072 0.12-0.192 0.336-0.192 0.336z"></path>
                                        </svg>
                                        <span <?php $this->print_render_attribute_string( 'tweet-icon-count' ); ?>>
                                            <?php echo esc_attr($this->unit_converter($t['retweet_count']));?>
                                        </span>
                                    </a>
                                    
                                    <a <?php $this->print_render_attribute_string( 'tweet-like'.$t['id_str'] ); ?>>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M23.88 7.56c-0.264-3.432-3.168-6.192-6.624-6.288-1.944-0.072-3.912 0.768-5.256 2.208-1.368-1.464-3.264-2.28-5.256-2.208-3.456 0.096-6.36 2.856-6.624 6.288-0.024 0.288-0.024 0.6-0.024 0.888 0.048 1.224 0.576 2.448 1.464 3.432l9.408 10.416c0.264 0.288 0.648 0.456 1.032 0.456s0.768-0.168 1.056-0.456l9.384-10.416c0.888-0.984 1.392-2.184 1.464-3.432 0-0.288 0-0.6-0.024-0.888zM11.904 21.408l-9.384-10.416c-0.672-0.744-1.080-1.68-1.128-2.616 0-0.24 0-0.48 0.024-0.72 0.216-2.76 2.568-5.016 5.352-5.088 0.048 0 0.12 0 0.168 0 1.776 0 3.48 0.864 4.512 2.328 0.12 0.168 0.312 0.264 0.528 0.264s0.408-0.096 0.528-0.264c1.080-1.512 2.832-2.376 4.704-2.328 2.784 0.096 5.136 2.328 5.376 5.088 0.024 0.24 0.024 0.48 0.024 0.72v0c-0.048 0.936-0.432 1.872-1.128 2.616l-9.384 10.416c-0.048 0.048-0.168 0.024-0.192 0z"></path>
                                        </svg>
                                        <span <?php $this->print_render_attribute_string( 'tweet-icon-count' ); ?>>
                                            <?php echo esc_attr($this->unit_converter($t['favorite_count']));?>
                                        </span>
                                    </a>
                                    
                                    <?php if ( count($settings['footer_share_socials']) ) : ?>
    
                                    <span <?php $this->print_render_attribute_string( 'tweet-icon' ); ?>>
                                        
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M23.952 9.144c-0.024-0.096-0.096-0.216-0.168-0.288-1.992-2.232-8.664-8.256-8.664-8.256-0.192-0.168-0.456-0.192-0.6-0.096-0.24 0.12-0.36 0.312-0.36 0.528v4.2c-7.944 0.672-14.16 7.416-14.16 15.432v2.376c0 0.288 0.168 0.504 0.456 0.576h0.144c0.192 0 0.408-0.12 0.528-0.336l0.432-0.936c2.328-5.016 7.152-8.4 12.6-8.88v4.080c0 0.216 0.144 0.432 0.336 0.528 0.216 0.096 0.456 0.048 0.624-0.096l8.64-8.256c0.12-0.12 0.192-0.312 0.192-0.48zM15.12 12.408c-0.144-0.144-0.336-0.168-0.432-0.144-5.616 0.264-10.752 3.408-13.536 8.28 0.072-7.608 6.024-13.824 13.608-14.208 0.312 0 0.552-0.24 0.552-0.576v-3.384l7.224 6.912-7.224 6.888v-3.336c0-0.144-0.072-0.312-0.192-0.432z"></path>
                                        </svg>
                                        <span <?php $this->print_render_attribute_string( 'tweet-icon-count' ); ?>>
                                            <?php echo esc_html__('Share', 'xstore-core'); ?>
                                        </span>
                                        
                                        <span <?php $this->print_render_attribute_string( 'tweet-icon-popup' ); ?>>
                                    
                                        <?php if ( in_array('facebook', $settings['footer_share_socials']) ) : ?>
                                            <a href="https://www.facebook.com/sharer.php?u=<?php echo esc_url( $t['permalink'] ); ?>" title="<?php echo esc_attr__('Share on Facebook', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path d="M13.488 8.256v-3c0-0.84 0.672-1.488 1.488-1.488h1.488v-3.768h-2.976c-2.472 0-4.488 2.016-4.488 4.512v3.744h-3v3.744h3v12h4.512v-12h3l1.488-3.744h-4.512z"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( in_array('twitter', $settings['footer_share_socials']) ) : ?>
                                          <a href="https://twitter.com/share?url=<?php echo esc_url( $t['permalink'] ); ?>&text=<?php echo esc_attr__('Share on Twitter', 'xstore-core'); ?>" title="<?php echo esc_html__('Share on Twitter', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path d="M24 4.56c-0.888 0.384-1.848 0.648-2.832 0.768 1.032-0.6 1.8-1.56 2.16-2.712-0.96 0.576-1.992 0.96-3.12 1.2-0.912-0.96-2.184-1.56-3.6-1.56-2.712 0-4.92 2.208-4.92 4.92 0 0.384 0.024 0.768 0.12 1.128-4.080-0.192-7.704-2.16-10.152-5.136-0.432 0.744-0.672 1.584-0.672 2.496 0 1.704 0.888 3.216 2.184 4.080-0.768-0.024-1.56-0.264-2.208-0.624 0 0.024 0 0.024 0 0.048 0 2.4 1.704 4.368 3.936 4.824-0.384 0.12-0.84 0.168-1.296 0.168-0.312 0-0.624-0.024-0.936-0.072 0.648 1.944 2.448 3.384 4.608 3.432-1.68 1.32-3.792 2.088-6.096 2.088-0.408 0-0.792-0.024-1.176-0.072 2.184 1.416 4.752 2.208 7.56 2.208 9.048 0 14.016-7.512 14.016-13.992 0-0.216 0-0.432-0.024-0.624 0.96-0.72 1.776-1.584 2.448-2.568z"></path>
                                            </svg>
                                          </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( in_array('linkedin', $settings['footer_share_socials']) ) : ?>
                                          <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( $t['permalink'] ); ?>&title=<?php echo esc_attr__('Share on Linkedin', 'xstore-core'); ?>" title="<?php echo esc_attr__('Share on Linkedin', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path d="M0 7.488h5.376v16.512h-5.376v-16.512zM19.992 7.704c-0.048-0.024-0.12-0.048-0.168-0.048-0.072-0.024-0.144-0.024-0.216-0.048-0.288-0.048-0.6-0.096-0.96-0.096-3.12 0-5.112 2.28-5.76 3.144v-3.168h-5.4v16.512h5.376v-9c0 0 4.056-5.64 5.76-1.488 0 3.696 0 10.512 0 10.512h5.376v-11.16c0-2.496-1.704-4.56-4.008-5.16zM5.232 2.616c0 1.445-1.171 2.616-2.616 2.616s-2.616-1.171-2.616-2.616c0-1.445 1.171-2.616 2.616-2.616s2.616 1.171 2.616 2.616z"></path>
                                            </svg>
                                          </a>
                                        <?php endif; ?>
                                    
                                        <?php if ( in_array('vk', $settings['footer_share_socials']) ) : ?>
                                            <a href="https://vk.com/share.php?url=<?php echo esc_url( $t['permalink'] ); ?>?&title=<?php echo esc_attr__('Share on Vk', 'xstore-core'); ?>" title="<?php echo esc_attr__('Share on Vk', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path d="M23.784 17.376c-0.072-0.12-0.456-0.984-2.376-2.76-2.016-1.872-1.752-1.56 0.672-4.8 1.464-1.968 2.064-3.168 1.872-3.672-0.168-0.48-1.272-0.36-1.272-0.36l-3.6 0.024c0 0-0.264-0.048-0.456 0.072s-0.312 0.384-0.312 0.384-0.576 1.512-1.344 2.808c-1.608 2.736-2.256 2.88-2.52 2.712-0.6-0.384-0.456-1.584-0.456-2.424 0-2.64 0.408-3.744-0.792-4.032-0.384-0.096-0.672-0.168-1.68-0.168-1.296-0.024-2.376 0-3 0.312-0.408 0.192-0.72 0.648-0.528 0.672 0.24 0.024 0.768 0.144 1.056 0.528 0.36 0.504 0.36 1.632 0.36 1.632s0.216 3.12-0.504 3.504c-0.48 0.264-1.152-0.288-2.592-2.76-0.744-1.272-1.296-2.664-1.296-2.664s-0.096-0.264-0.288-0.408c-0.24-0.168-0.552-0.216-0.552-0.216l-3.384 0.024c0 0-0.504 0.024-0.696 0.24-0.168 0.192-0.024 0.6-0.024 0.6s2.688 6.288 5.712 9.456c2.784 2.904 5.952 2.712 5.952 2.712h1.44c0 0 0.432-0.048 0.648-0.288 0.216-0.216 0.192-0.624 0.192-0.624s-0.024-1.92 0.864-2.208c0.888-0.288 2.016 1.872 3.216 2.688 0.912 0.624 1.584 0.48 1.584 0.48l3.216-0.048c0 0 1.68-0.096 0.888-1.416z"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    
                                        <?php if ( in_array('pinterest', $settings['footer_share_socials']) ) : ?>
                                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( $t['permalink'] ); ?>" title="<?php echo esc_attr__('Share on Pinterest', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path d="M12.336 0c-6.576 0-10.080 4.224-10.080 8.808 0 2.136 1.2 4.8 3.096 5.64 0.288 0.12 0.456 0.072 0.504-0.192 0.048-0.216 0.312-1.176 0.432-1.656 0.048-0.144 0.024-0.288-0.096-0.408-0.624-0.744-1.128-2.064-1.128-3.312 0-3.216 2.544-6.312 6.888-6.312 3.744 0 6.384 2.448 6.384 5.928 0 3.936-2.088 6.672-4.8 6.672-1.488 0-2.616-1.176-2.256-2.64 0.432-1.728 1.272-3.6 1.272-4.848 0-1.128-0.624-2.040-1.92-2.040-1.536 0-2.76 1.512-2.76 3.528 0 1.296 0.456 2.16 0.456 2.16s-1.512 6.096-1.8 7.224c-0.48 1.92 0.072 5.040 0.12 5.328 0.024 0.144 0.192 0.192 0.288 0.072 0.144-0.192 1.968-2.808 2.496-4.68 0.192-0.696 0.96-3.456 0.96-3.456 0.504 0.912 1.944 1.68 3.504 1.68 4.608 0 7.92-4.032 7.92-9.048-0.072-4.848-4.2-8.448-9.48-8.448z"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    
                                        <?php if ( in_array('whatsapp', $settings['footer_share_socials']) ) : ?>
                                            <a href="https://api.whatsapp.com/send?text=<?php echo esc_url( $t['permalink'] ); ?>" title="<?php echo esc_attr__('Share on Whatsapp', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path d="M23.952 11.688c0 6.432-5.256 11.64-11.712 11.64-2.064 0-3.984-0.528-5.664-1.44l-6.48 2.064 2.112-6.24c-1.056-1.752-1.68-3.816-1.68-6 0-6.432 5.256-11.64 11.712-11.64 6.456-0.024 11.712 5.184 11.712 11.616zM12.216 1.92c-5.424 0-9.864 4.368-9.864 9.768 0 2.136 0.696 4.128 1.872 5.736l-1.224 3.624 3.792-1.2c1.56 1.032 3.432 1.608 5.424 1.608 5.424 0.024 9.864-4.368 9.864-9.768s-4.44-9.768-9.864-9.768zM18.144 14.376c-0.072-0.12-0.264-0.192-0.552-0.336s-1.704-0.84-1.968-0.936c-0.264-0.096-0.456-0.144-0.648 0.144s-0.744 0.936-0.912 1.128c-0.168 0.192-0.336 0.216-0.624 0.072s-1.224-0.432-2.304-1.416c-0.864-0.744-1.44-1.68-1.608-1.968s-0.024-0.432 0.12-0.576c0.12-0.12 0.288-0.336 0.432-0.504s0.192-0.288 0.288-0.48c0.096-0.192 0.048-0.36-0.024-0.504s-0.648-1.536-0.888-2.112c-0.24-0.576-0.48-0.48-0.648-0.48s-0.36-0.024-0.552-0.024c-0.192 0-0.504 0.072-0.768 0.36s-1.008 0.984-1.008 2.376c0 1.392 1.032 2.76 1.176 2.952s1.992 3.168 4.92 4.296c2.928 1.152 2.928 0.768 3.456 0.72s1.704-0.696 1.944-1.344c0.24-0.672 0.24-1.248 0.168-1.368z"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                      </span>
                                      
                                    </span>
                        
                                    <?php endif; ?>
                                    
                                </div>
                    
                            <?php endif; ?>
                            
                        </div> <?php // .etheme-twitter-feed-tweet ?>
                    
                    <?php } ?>
                    
                    </div>
                    
                    <?php

                    if ( 1 < count($tweets) ) {
	                    if ( in_array($settings['navigation'], array('both', 'dots')) )
		                    Elementor::get_slider_pagination($this, $settings, $edit_mode);
                    }
                    
                    ?>
    
                </div>
                
                <?php

                if ( 1 < count($tweets) ) {
	                if ( in_array( $settings['navigation'], array( 'both', 'arrows' ) ) )
		                Elementor::get_slider_navigation( $settings, $edit_mode );
                } ?>
                
            </div>
        
        <?php
		
	}
	
	/**
	 * Get tweets by user configuration data.
	 *
	 * @param $settings
	 * @return array|false|void
	 *
	 * @since 4.0.11
	 *
	 */
	public function get_tweets($settings, $edit_mode) {
  
		if ( !$settings['consumer_key'] || !$settings['consumer_secret'] ) {
			echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
			     sprintf(esc_html__('Please, enter %1$sConsumer key%2$s and %1$sConsumer secret%2$s', 'xstore-core'), '<strong>', '</strong>') .
			     '</div>';
			return;
		}
		
		$connection = new \TwitterOAuth(
			$settings['consumer_key'],          // Consumer key
			$settings['consumer_secret']       // Consumer secret
//			$access_token,          // Access token
//			$access_token_secret // Access token secret
		);
		
		$params = array(
			'count'           => $settings['limit']
		);
		if ( $settings['tweets_type'] == 'account' ) {
			$connection_type = 'statuses/user_timeline';
			$params['screen_name'] = $settings['username'];
			$cache_value = $settings['username'];
		}
		else {
			$connection_type = 'search/tweets';
			$params['q'] = $settings['hashtag'];
			$cache_value = $settings['username'];
		}
		
		
		$posts_data_transient_name = 'etheme-twitter-feed-posts-data-' . sanitize_title_with_dashes( $settings['tweets_type'] . $cache_value . $settings['limit'] );
		$readyTweets             =
			($settings['cache_results'] && !$edit_mode) ?
				maybe_unserialize( base64_decode( get_transient( $posts_data_transient_name ) ) ) : false;
		
		if ( ! $readyTweets ) {
			$readyTweets = $connection->get(
				$connection_type,
				$params
			);
			
			if ( $settings['tweets_type'] == 'hashtag' ) {
				$readyTweets = $readyTweets->statuses;
			}
			
			if ( $connection->http_code != 200 ) {
				echo '<div class="elementor-panel-alert elementor-panel-alert-danger">'.
				     esc_html__('Twitter not return 200', 'xstore-core') .
				     '</div>';
				return false;
			}
			
			$encode_posts = base64_encode( maybe_serialize( $readyTweets ) );
			set_transient( $posts_data_transient_name, $encode_posts, apply_filters( 'etheme_twitter_feed_cache_time', HOUR_IN_SECONDS * 2 ) );
		}
		
		if ( ! $readyTweets ) {
			echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
			     esc_html__('Twitter did not return any data', 'xstore-core') .
			     '</div>';
			return false;
		}

		$is_ssl = is_ssl();
		
		$format_options = [
			'default' => 'g:i A F j, Y',
		];
		
		$format_options['custom'] =
			empty( $settings['custom_date_format'] ) ? $format_options['default'] : $settings['custom_date_format'];
		
		$current_time = current_time( 'timestamp' );
		
		$tweets = array();
		
		foreach ($readyTweets as $tweet) {
			
			$screen_name = $tweet->user->screen_name;
			
			$text = $this->parse_tweet( $tweet, $settings );
			
			// lets strip 4-byte emojis
//			$text = preg_replace( '/[\xF0-\xF7][\x80-\xBF]{3}/', '', $text );
			
			$time = false;
			
			if ( $settings['time'] ) {
				$time = $tweet->created_at;
				if ( $settings['time_posted_type'] == 'ago' ) {
					$time  = date_parse( $time );
					$time = mktime( $time['hour'], $time['minute'], $time['second'], $time['month'], $time['day'], $time['year'] );
					
					$time_ago = human_time_diff( $time, $current_time );
					$ago  = _x( ' ago', 'leave space for correct view', 'xstore-core' );
					$time = sprintf( esc_html__( '%1$s%2$s', 'xstore-core' ), $time_ago, $ago );
				}
				else {
					$time = date_format( date_create( $time ), $format_options[ $settings['date_format'] ] );
				}
			}
			
			$id_str = $tweet->id_str;
			$permalink = 'https://twitter.com/' . $screen_name . '/status/' . $id_str;
			
			$tweets[] = array(
				'text'      => $text,
				'name'      => $tweet->user->name,
				'screen_name'      => $screen_name,
				'verified' => $tweet->user->verified,
				'id_str' => $id_str,
				'permalink' => $permalink,
				'time'      => $time,
				'favorite_count' => $tweet->favorite_count,
				'retweet_count' => $tweet->retweet_count,
			);
			
			// add render attributes
			$this->add_render_attribute( 'tweet-author-avatar'.$id_str, [
				'class' => 'etheme-twitter-feed-author-avatar',
                'href' => 'https://twitter.com/'. $screen_name,
                'target' => '_blank'
			]);
			
			$this->add_render_attribute( 'tweet-author-avatar-image'.$id_str, [
				'src' => $is_ssl ? $tweet->user->profile_image_url_https : $tweet->user->profile_image_url,
				'alt' => $tweet->user->name
			]);
			
			$this->add_render_attribute( 'tweet-author-name'.$id_str, [
				'class' => 'etheme-twitter-feed-author-name',
				'href' => 'https://twitter.com/'. $screen_name,
                'target' => '_blank'
			]);
			
			$this->add_render_attribute( 'tweet-more'.$id_str, [
				'class' => 'etheme-twitter-feed-more',
				'href' => $permalink,
                'target' => '_blank'
			]);
			
			$this->add_render_attribute( 'tweet-time'.$id_str, [
				'class' => 'etheme-twitter-feed-time',
				'href' => $permalink,
                'target' => '_blank'
			]);
			
			// icons
			$this->add_render_attribute( 'tweet-in_reply_to'.$id_str, [
				'class' => 'etheme-twitter-feed-icon',
				'href' => 'https://twitter.com/intent/tweet?in_reply_to=' . $id_str . '&related=' . $screen_name,
				'target' => '_blank',
                'title' => esc_html__('Comments', 'xstore-core')
			]);
			
			$this->add_render_attribute( 'tweet-retweet'.$id_str, [
				'class' => 'etheme-twitter-feed-icon',
				'href' => 'https://twitter.com/intent/retweet?tweet_id=' . $id_str . '&related=' . $screen_name,
				'target' => '_blank',
				'title' => esc_html__('Retweet', 'xstore-core')
			]);
			
			$this->add_render_attribute( 'tweet-like'.$id_str, [
				'class' => 'etheme-twitter-feed-icon',
				'href' => 'https://twitter.com/intent/like?tweet_id=' . $id_str . '&related=' . $screen_name,
				'target' => '_blank',
				'title' => esc_html__('Like', 'xstore-core')
			]);
		}
		
		return $tweets;
	}
	
	/**
	 * Parse tweet content with replacing some content with links.
	 *
	 * @param $tweet
	 * @param $settings
	 * @return string|string[]|null
	 *
	 * @since 4.0.11
	 *
	 */
	public function parse_tweet($tweet, $settings) {
		
		// If the Tweet a ReTweet - then grab the full text of the original Tweet
		if( isset( $tweet->retweeted_status ) ) {
			// Split it so indices count correctly for @mentions etc.
			$rt_section = current( explode( ":", $tweet->text ) );
			$text = $rt_section.": ";
			// Get Text
			$text .= $tweet->retweeted_status->text;
		} else {
			// Not a retweet - get Tweet
			$text = $tweet->text;
		}
		
		if ( $settings['text_limit_type'] != 'none')
			$text = $this->limit_text($text);
		
		// Link Creation from clickable items in the text
		$text = preg_replace( '/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank" rel="nofollow noopener">$0</a>', $text );
		// Clickable Twitter names
		$text = preg_replace( '/[@]+([A-Za-z0-9-_]+)/', '<a href="https://twitter.com/$1" target="_blank" rel="nofollow noopener">@\\1</a>', $text );
		// Clickable Twitter hash tags
		$text = preg_replace( '/[#]+([A-Za-z0-9-_]+)/', '<a href="https://twitter.com/hashtag/$1?src=hashtag_click" target="_blank" rel="nofollow noopener">$0</a>', $text );
		
		return $text;
		
	}
	
	/**
	 * Function that returns rendered title by chars/words limit.
	 *
	 * @param $title
	 * @return mixed|string
	 *
	 * @since 4.0.11
	 *
	 */
	public function limit_text($title) {
		$settings = $this->get_settings_for_display();
		if ( $settings['text_limit']['size'] > 0) {
			if ( $settings['text_limit_type'] == 'chars' ) {
				return Elementor::limit_string_by_chars($title, $settings['text_limit']['size']);
			}
            elseif ( $settings['text_limit_type'] == 'words' ) {
				return Elementor::limit_string_by_words($title, $settings['text_limit']['size']);
			}
		}
		return $title;
	}
	
	/**
	 * Convert big values of likes/shares to small number with suffix.
	 *
	 * @param $unit
	 * @return string
	 *
	 * @since 4.0.11
	 *
	 */
	protected function unit_converter($unit) {
		
		if ( $unit >= 1000000000 )
			return round(floor($unit / 1000000000), 1) . 'T';
		if ( $unit >= 100000000 )
			return round(floor($unit / 100000000), 1) . 'B';
		if ( $unit >= 1000000 )
			return round(($unit / 1000000), 1) . 'M';
		if ( $unit >= 10000 )
			return round(floor($unit / 1000), 1) . 'K';
		else
			return number_format($unit);
	}
	
}
