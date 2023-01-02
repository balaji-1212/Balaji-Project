<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Recent Portfolio widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Portfolio_Recent extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'portfolio_recent';
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
		return __( 'Recent Portfolio', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-etheme-portfolio';
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
		return [ 'portfolio', 'masonry', 'isotope', 'posts', 'query', 'post type', 'grid', 'layout', 'date', 'recent' ];
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
	 * Register Recent Portfolio widget controls.
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
			'limit',
			[
				'label' => __( 'Limit', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					2	=>	esc_html__('2', 'xstore-core'),
					3	=>	esc_html__('3', 'xstore-core'),
					4	=>	esc_html__('4', 'xstore-core'),
					5	=>	esc_html__('5', 'xstore-core'),
					6	=>	esc_html__('6', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' 		=>	__( 'Order Way', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					 'DESC'	=>	esc_html__( 'Descending', 'xstore-core') ,
					 'ASC'	=>	esc_html__( 'Ascending', 'xstore-core' ),
				],
				'default'		=> 'DESC',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Recent Portfolio widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[portfolio_recent
			title="'. $settings['title'] .'"
			limit="'. $settings['limit'] .'"
			columns="'. $settings['columns'] .'"
			order="'. $settings['order'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
