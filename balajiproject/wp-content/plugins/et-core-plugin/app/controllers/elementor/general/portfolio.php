<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Portfolio widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Portfolio extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'portfolio';
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
		return __( 'Portfolio', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-masonry-product-categories';
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
		return [ 'portfolio', 'masonry', 'isotope', 'posts', 'query', 'post type', 'grid', 'layout' ];
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
	 * Register Portfolio widget controls.
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
			'filters',
			[
				'label' 		=> esc_html__( 'Show Filters', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
			]
		);
		
		$this->add_control(
			'pagination',
			[
				'label' 		=> esc_html__( 'Show Pagination', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'true',
				'default' 		=> 'true',
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
				'default' => 4,
				'options' 		=>	[
					2	=>	esc_html__('2', 'xstore-core'),
					3	=>	esc_html__('3', 'xstore-core'),
					4	=>	esc_html__('4', 'xstore-core'),
					5	=>	esc_html__('5', 'xstore-core'),
					6	=>	esc_html__('6', 'xstore-core'),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Portfolio widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[portfolio 
			limit="'. $settings['limit'] .'"
			columns="'. $settings['columns'] .'"
			filters="'. $settings['filters'] .'"
			pagination="'. $settings['pagination'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) .'"]'
		);

	}

}
