<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Static Block widget.
 *
 * @since      4.0.6
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Static_Block extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_static_block';
	}

	/**
	 * Get widget title.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Static Block', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-static_block';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.6
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'Static_Block', 'Static Block', 'StaticBlock'];
	}

	/**
	 * Get widget categories.
	 *
	 * @since 4.0.6
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
	 * @since 4.0.7
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		$scripts = [];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
			$scripts[] = 'etheme_static_block';
		return $scripts;
	}

	public function get_style_depends() {
		return [ 'etheme-elementor-static_block' ];
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
	 * Register widget controls.
	 *
	 * @since 4.0.6
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
			]
		);

		$this->add_control(
			'et_block_id',
			[
				'label'       => esc_html__( 'Static Block', 'xstore-core' ),
				'description' => esc_html__( 'Select Static Block by it name.', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> false,
				'placeholder' 	=> esc_html__('Enter Name of Static Block', 'xstore-core'),
				'data_options' 	=> [
					'post_type' => array( 'staticblocks' ),
				],

			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.6
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'etheme-static_block-wrapper' ); ?>
	    <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
	            <?php the_widget( 'ETC\App\Models\Widgets\Static_Block', array( 'block_id' => $settings['et_block_id'] ) ); ?>
		</div>
		<?php
	}
}
