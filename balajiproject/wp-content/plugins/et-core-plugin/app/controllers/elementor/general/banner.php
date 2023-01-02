<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Controllers\Shortcodes\Banner as Banner_Shortcode;
use ETC\App\Traits\Elementor;

/**
 * banner widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Banner extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'banner';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Banners', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-banner';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'mask', 'image', 'effect', 'banner' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.0.0
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
		return [ 'etheme-banner', 'etheme-banners-global' ];
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
	 * Register banner widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {

		Elementor::get_banner_with_mask( $this, false );

	}

	/**
	 * Render banner widget output on the frontend.
	 *
	 * @since 2.0.0
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

					case 'button_link':
						// Button title
						$setting['title'] = isset( $settings['button_title'] ) ? $settings['button_title'] : '';
						$atts[$key] = $setting;
					break;

					case 'image_opacity':
					case 'image_opacity_on_hover':
						$atts[$key] = $setting['size'];
					break;

					case 'align':
						$atts[$key] = '';
					break;

					case 'button_border_radius':
					case 'button_paddings':
//					case 'button_color':
//					case 'button_bg':
//					case 'button_hover_color':
//					case 'button_hover_bg':
					break;

					case 'button_border_radius':
					case 'button_paddings':
						$atts[$key] = $setting['size'] . $setting['unit'];
					break;

					case 'img':
						$atts[$key] = isset( $setting['id'] ) ? $setting['id'] : '';

					if ( empty($atts[$key]) ) 
						$atts['img_backup'] = '<img src="'.\Elementor\Utils::get_placeholder_image_src().'"/>';
					break;

					default:
						$atts[$key] = $setting;
					break;
				}

				$atts['use_custom_fonts_title'] = true;
				$atts['use_custom_fonts_subtitle'] = true;
				$atts['responsive_fonts'] = false;

			}
		}
		
		$atts['prevent_load_inline_style'] = true;
		$atts['is_preview'] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
		$atts['is_elementor'] = true;
		$Banner_Shortcode = Banner_Shortcode::get_instance();
		echo $Banner_Shortcode->banner_shortcode( $atts, $settings['content'] );

	}

}
