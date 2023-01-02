<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Scroll_Text as Scroll_Text_Shortcode;

/**
 * Autoscrolling Text widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Scroll_Text extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_scroll_text';
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
		return __( 'Autoscrolling Text', 'xstore-core' );
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
		return 'eicon-scroll eight_theme-element-icon';
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
		return [ 'autoscrolling-text', 'autoscrolling' ];
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
	 * Register Autoscrolling Text widget controls.
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
			'height_value',
			[
				'label' 		=> __( 'Custom Height Value', 'xstore-core' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'description'  	=> esc_html__( 'Enter height value with dimensions for ex. 30px.', 'xstore-core' ),
			]
		);

		$this->add_control(
			'transition_effect',
			[
				'label' 		=>	__( 'Transition Style', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'slide'		=>	esc_html__('Slide', 'xstore-core'),
					'fade'		=>	esc_html__('Fade', 'xstore-core'),
					'cube'		=>	esc_html__('Cube', 'xstore-core'),
					'coverflow'	=>	esc_html__('Coverflow', 'xstore-core'),
					'flip'		=>	esc_html__('Flip', 'xstore-core'),
				],
				'default'		=> 'slide',
			]
		);

		$this->add_control(
			'slider_interval',
			[
				'label' 		=>	__( 'Autoplay Speed', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default'		=> '7000',
			]
		);

		$this->add_control(
			'color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '#ffffff',
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' 	=> __( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '#222',
			]
		);

		$this->add_control(
			'el_class',
			[
				'label' 		=>	__( 'Extra Class', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'scroll_text_settings',
			[
				'label' => __( 'Scroll Text Content', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		Elementor::get_scroll_text_item( $repeater );

		$this->add_control(
			'scroll_text_content',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Scroll Text Content', 'xstore-core' ),
						'list_content' => __( 'Add scroll text from here.', 'xstore-core' ),
					],
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Autoscrolling Text widget output on the frontend.
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
				$atts[$key] = $setting;
			}
		}

		$content = '';
		if ( $settings['scroll_text_content'] ) {
			foreach (  $settings['scroll_text_content'] as $item ) {
				// Url link
				if ( $item['button_link']['nofollow'] ) {
					$item['button_link']['nofollow'] = 'nofollow';
				}		
				if ( $item['button_link']['is_external'] ) {
					$item['button_link']['is_external'] = '%20_blank';
				}
				$item['button_link'] = 'url:' . $item['button_link']['url'] . '|target:' . $item['button_link']['is_external'] . '|rel:' . $item['button_link']['nofollow'];

				$content .= '[etheme_scroll_text_item tooltip="'. $item['tooltip'] .'" tooltip_title="'. $item['tooltip_title'] .'" tooltip_content="'. $item['tooltip_content'] .'" tooltip_content_pos="'. $item['tooltip_content_pos'] .'" button_link="'. $item['button_link'] .'" el_class="'. $item['el_class'] .'"]'.$item['content'].'[/etheme_scroll_text_item]';
			}
		}

		$Scroll_Text = Scroll_Text_Shortcode::get_instance();
		echo $Scroll_Text->scroll_text_shortcode( $atts, $content );

	}

}