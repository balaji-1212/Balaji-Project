<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Brands as Brands_Shortcodes;

/**
 * Brands widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Brands extends \Elementor\Widget_Base {

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
		return 'etheme_brands';
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
		return __( 'Brands Carousel', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-brands-carousel';
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
		return [ 'brands', 'item', 'loop', 'query', 'products', 'term', 'woocommerce' ];
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
	 * Register Brands widget controls.
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
			'number',
			[
				'label' 	=> __( 'Number Of Brands', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => __('Set 0 to show all brands', 'xstore-core'),
				'label_block'	=> 'true',
				'default' => [
					'size' => 12,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1
					],
				],
			]
		);

		$this->add_control(
			'ids',
			[
				'label' 		=>	__( 'Brands', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'List of product brands', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_terms('brand'),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 	=>	__( 'Order By', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ids_order' => esc_html__( 'As IDs provided order', 'xstore-core' ),
					'ID'  		=> esc_html__( 'ID', 'xstore-core' ),
					'name'  	=> esc_html__( 'Title', 'xstore-core' ),
					'count' 	=> esc_html__( 'Quantity', 'xstore-core' ),
				),
				'default' 	=>	'name',
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=>	__( 'Order Way', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
                    'ASC' 	=> esc_html__( 'Ascending', 'xstore-core' ),
                    'DESC' 	=> esc_html__( 'Descending', 'xstore-core' ),
				),
				'default' 	=>	'ASC',
			]
		);
		
		$this->add_control(
			'show_only_name',
			[
				'label' 		=> __( 'Show only name', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label' 		=> __( 'Hide Empty Brands', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
			]
		);

		$this->add_control(
			'class',
			[
				'label' => __( 'CSS Classes', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings',
			[
				'label' => __( 'Slider', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Get slider controls from trait
		Elementor::get_slider_params( $this );

		$this->end_controls_section();

	}

	/**
	 * Render Brands widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( !class_exists('WooCommerce') ) {
			echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
			     esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core') .
			     '</div>';
			return;
		}
		
		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}

			if ( $setting ) {
				switch ( $key ) {
					case 'ids':
						$atts[$key] = !empty( $setting ) ? implode( ',', $setting ) : '';
					break;
					case 'number':
						$atts[$key] = $setting['size'];
					break;
					case 'slides':
						$atts['large'] = $atts['notebook'] = !empty($setting) ? $setting : 4;
						break;
					case 'slides_tablet':
						$atts['tablet_land'] = $atts['tablet_portrait'] = !empty($setting) ? $setting : 2;
						break;
					case 'slides_mobile':
						$atts['mobile'] = !empty($setting) ? $setting : 1;
						break;
					default:
						$atts[$key] = $setting;
						break;
				}
			}

		}

		$atts['is_preview'] = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$atts['is_elementor'] = true;
		$Brands_Shortcodes = Brands_Shortcodes::get_instance();
		echo $Brands_Shortcodes->brands_shortcode( $atts );

	}

}
