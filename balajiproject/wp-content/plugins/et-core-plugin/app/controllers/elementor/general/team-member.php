<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Controllers\Shortcodes\Team_Member as Team_Member_Shortcodes;

/**
 * Team Member widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Team_Member extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'team_member';
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
		return __( 'Team Member', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-team-member';
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
		return [ 'team', 'member', 'user', 'image', 'social', 'instagram', 'skype', 'facebook' ];
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
		return [ 'etheme-banners-global', 'etheme-team-member' ];
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
	 * Register Team Member widget controls.
	 *
	 * @since 2.1.3
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
			'img',
			[
				'label' => __( 'Avatar', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'img_size',
			[
				'label' 	  => __( 'Image Size', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
				'default'	  => '270x170',
			]
		);

		$this->add_control(
			'img_effect',
			[
				'label' 		=>	__( 'Image Effect', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'2'	=>	__('Zoom In', 'xstore-core'),
					'4'	=>	__('Zoom out', 'xstore-core'),
					'5'	=>	__('Scale out', 'xstore-core'),
					'3'	=>	__('None', 'xstore-core'),
				],
				'default'	  => 2,
			]
		);

		$this->add_control(
			'img_position',
			[
				'label' 		=>	__( 'Image Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right'    => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition'   	=> ['type' => ['2']],
				'default' 		=> 'left',
			]
		);

		$this->add_control(
			'xstore_title_divider_elements',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name',
			[
				'label' 	  => __( 'Member Name', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
				'default'	  => __( 'Member name', 'xstore-core' ),
			]
		);

		$this->add_control(
			'email',
			[
				'label' 	  => __( 'Member Email', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
				'default'	  => __( 'Member email', 'xstore-core' ),
			]
		);

		$this->add_control(
			'position',
			[
				'label' 	  => __( 'Member Position', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
				'default'	  => __( 'Member position', 'xstore-core' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label' 	=> __( 'Member Information', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::WYSIWYG,
				'default' 	=> 'Member information',
				'rows' 		=> 10,
			]
		);

		$this->add_control(
			'xstore_title_divider_layout',
			[
				'label' => __( 'Layout', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Display Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'1' 	=>	esc_html__('Vertical', 'xstore-core'),
					'2' 	=>	esc_html__('Horizontal', 'xstore-core'),
					'3' 	=>	esc_html__('Overlayed content', 'xstore-core'),
				],
				'default' 		=> '1',
			]
		);

		$this->add_control(
			'text_align',
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
					'justify' => [
						'title' => __( 'Justified', 'xstore-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default'	  => 'center',
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' 		=>	__( 'Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top'    => [
						'title' => __( 'Top', 'xstore-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'xstore-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'xstore-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' 		=> 'top',
				'condition'   => ['type' => ['2']],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icons_section',
			[
				'label' => __( 'Social Icons', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'icons_position',
			[
				'label' 		=>	__( 'Icons Position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'img'		=>	esc_html__('On image', 'xstore-core'),
					'content'	=>  esc_html__('In content', 'xstore-core'),
				],
				'default' 		=> 'img',
			]
		);

		$this->add_control(
			'facebook',
			[
				'label' => __( 'Facebook Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => '#'
			]
		);

		$this->add_control(
			'twitter',
			[
				'label' => __( 'Twitter Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => '#'
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
			'skype',
			[
				'label' => __( 'Skype Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
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
			'target',
			[
				'label' 		=>	__( 'Links Target', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'_self'		=>	esc_html__('Current Window', 'xstore-core'),
					'_blank'	=>	esc_html__('Blank', 'xstore-core'),
				],
				'default' => '_blank'
			]
		);

		$this->add_control(
			'xstore_title_divider_icon_style',
			[
				'label' => __( 'Icons', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
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
					'large'		=>	esc_html__('Large', 'xstore-core') 
				],
				'default' 		=> 'normal',
			]
		);

		$this->add_control(
			'align',
			[
				'label' 		=>	__( 'Icons Align', 'xstore-core' ),
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
				'default'	  => 'center',
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

		$this->add_control(
			'tooltip',
			[
				'label' 		=>	__( 'Tooltips For Icons', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);
		
		$this->start_controls_tabs('icons_style_tabs');
		$this->start_controls_tab( 'icons_style_tab_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'icons_color',
			[
				'label' 	=> __( 'Icons Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icons_bg',
			[
				'label' 	=> __( 'Icons Background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);
		
		$this->end_controls_tab();
		$this->start_controls_tab( 'icons_style_tab_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'icons_color_hover',
			[
				'label' 	=> __( 'Icons Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);
		
		$this->add_control(
			'icons_bg_hover',
			[
				'label' 	=> __( 'Icons Background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' 	=> __( 'Primary Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'condition' => [
					'type' => '2'
				],
				'selectors'    => [
					'{{WRAPPER}} .team-member.member-type-2:hover .content-section' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .team-member.member-type-2:hover .content-section:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' 	=> __( 'Member Name Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' 	=> __( 'Position Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'email_color',
			[
				'label' 	=> __( 'Member Email Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'email_link_color',
			[
				'label' 	=> __( 'Member Email Link Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' 	=> __( 'Content Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'et_overlay_bg',
			[
				'label' 	=> __( 'Overlay Background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'et_content_bg',
			[
				'label' 	=> __( 'Content Background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '#fff',
				'condition' => ['type' => ['2', '3']],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Team Member widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}

			if ( 'img' == $key ) {
				$atts[$key] = isset( $setting['id'] ) ? $setting['id'] : '';

				if ( empty($atts[$key]) ) 
					$atts['img_backup'] = '<img src="'.\Elementor\Utils::get_placeholder_image_src().'"/>';
				continue;
			}

			if ( 'content' == $key ) {
				continue;
			}

			if ( 'et_content_bg' == $key ) {
				$atts['content_bg'] = $setting;
				continue;
			}

			if ( 'et_overlay_bg' == $key ) {
				$atts['overlay_bg'] = $setting;
				continue;
			}

			if ( $setting ) {
				$atts[$key] = $setting;
			}
		}

		$atts['is_preview'] = \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false;
		$atts['prevent_load_inline_style'] = true;

		$Team_Member_Shortcodes = Team_Member_Shortcodes::get_instance();
		echo $Team_Member_Shortcodes->team_member_shortcode( $atts, $settings['content'] );

	}

}
