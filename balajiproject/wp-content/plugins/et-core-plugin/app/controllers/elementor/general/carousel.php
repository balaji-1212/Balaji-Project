<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Carousel as Carousel_Shortcodes;

/**
 * Carousel widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Carousel extends \Elementor\Widget_Base {

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
		return 'etheme_carousel';
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
		return __( 'Custom Carousel', 'xstore-core' );
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
		return 'eicon-carousel eight_theme-element-icon';
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
		return [ 'carousel' ];
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
	 * Register Carousel widget controls.
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
			'space',
			[
				'label' 	=>	__( 'Add Space Between Slides', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	array(
					'yes' 	=>  esc_html__('Yes', 'xstore-core'),
					'no' 	=>  esc_html__('No', 'xstore-core'),
				),
				'default' 	=>	'',
			]
		);

		$this->add_control(
			'el_class',
			[
				'label' 	=> __( 'Extra Class', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		// Get slider controls from trait
		Elementor::get_slider_params( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'banner_item_settings',
			[
				'label' => __( 'Items', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		Elementor::get_banner_with_mask( $repeater, true );

		$this->add_control(
			'banner_with_mask',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'Banner With Mask', 'xstore-core' ),
						'content' => __( 'Banner With Mask Item.', 'xstore-core' ),
					],
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Carousel widget output on the frontend.
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

			if ( $setting ) {

				switch ($key) {
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

		$content = '';
		if ( $settings['banner_with_mask'] ) {
			foreach (  $settings['banner_with_mask'] as $key => $item ) {

				$content .= '[banner title="'. $item_att[$key]['title'] .'" subtitle="'. $item_att[$key]['subtitle'] .'" subtitle="'. $item_att[$key]['subtitle'] .'"  is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]'. $item_att[$key]['content'] .'[/banner]';

			}
		}

		$Carousel_Shortcodes = Carousel_Shortcodes::get_instance();
		echo $Carousel_Shortcodes->carousel_shortcode( $atts, '' );

	}

}
