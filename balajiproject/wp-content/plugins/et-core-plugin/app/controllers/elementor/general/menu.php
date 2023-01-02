<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Menu widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Menu extends \Elementor\Widget_Base {

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
		return 'etheme-menu';
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
		return __( 'Display Menu', 'xstore-core' );
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
		return 'eicon-menu-bar eight_theme-element-icon';
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
		return [ 'menu', 'item', 'link', 'badge', 'label', 'list' ];
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
	 * Register Menu widget controls.
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
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'menu',
			[
				'label' 		=>	__( 'Menu', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	Elementor::menu_params(),
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
			'style',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'vertical' 	 =>	esc_html__( 'Vertical', 'xstore-core' ),
					'horizontal' =>	esc_html__( 'Horizontal', 'xstore-core' ),
					'menu-list'  =>	esc_html__( 'Simple list', 'xstore-core' ),
				],
				'default'		 => 'vertical',
			]
		);

		$this->add_control(
			'align',
			[
				'label' 		=>	__( 'Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'		=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'), 
					'right'		=>	esc_html__('Right', 'xstore-core'),
				],
				'default'		=> 'left',
			]
		);

		$this->add_control(
			'hide_submenus',
			[
				'label' 		=>	__( 'Hide Submenus', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
				'condition' => [
					'style!' => 'menu-list'
				]
			]
		);

		$this->add_control(
			'submenu_side',
			[
				'label' 		=>	__( 'Submenu Side', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'bottom'	=>	esc_html__( 'Bottom', 'xstore-core' ),
					'top'		=>	esc_html__( 'Top', 'xstore-core' ),
				],
				'condition'		=> [ 'style' => 'horizontal' ],
				'default'		=> 'bottom',
			]
		);

		$this->add_control(
			'submenu_side_vertical',
			[
				'label' 		=>	__( 'Submenu Side', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'right'	=>	esc_html__( 'Right', 'xstore-core' ),
					'left'	=>	esc_html__( 'Left', 'xstore-core' ),
				],
				'condition'		=> [ 'style' => 'vertical' ],
				'default'		=> 'right',
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
	 * Render Menu widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[menu
			title="'. $settings['title'] .'"
			menu="'. $settings['menu'] .'"
			style="'. $settings['style'] .'"
			align="'. $settings['align'] .'"
			hide_submenus="'. $settings['hide_submenus'] .'"
			submenu_side="'. $settings['submenu_side'] .'"
			submenu_side_vertical="'. $settings['submenu_side_vertical'] .'"
			class="'. $settings['class'] .'"]' 
		);

	}

}
