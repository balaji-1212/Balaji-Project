<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
/**
 * Lottie widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Lottie_Animation extends \Elementor\Widget_Base {
	
    use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_lottie_animation';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Lottie Animation', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-lottie';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'animation', 'lottie', 'svg', 'canvas', 'image', 'icon' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.12
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
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'etheme_lottie' ];
	}
	
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-lottie' ];
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
	 * @since 4.0.12
	 * @access protected
	 */
	protected function register_controls() {
		Elementor::get_lottie_settings($this);
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', [
			'class' => 'etheme-lottie',
		]);
		
		$this->add_render_attribute( 'lottie-animation', [
			'class' => 'etheme-lottie-animation',
		]);
		
		$this->add_render_attribute( 'lottie-link', [
			'class' => 'etheme-lottie-link',
		]);
		
		$animation_content = '<div ' . $this->get_render_attribute_string( 'lottie-animation' ) . '></div>';
		
		?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<?php
                    if ( $settings['lottie_link_to'] === 'custom' && ! empty( $settings['lottie_custom_link']['url'] ) ) {
                        $this->add_link_attributes( 'lottie-link', $settings['lottie_custom_link'] );
                        echo sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'lottie-link' ), $animation_content );
                    }
                    else {
                        echo $animation_content;
                    }
                ?>
			</div>
		<?php
		
	}
	
}
