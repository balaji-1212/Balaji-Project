<?php
/**
 * The Control class.
 *
 * @package KirkiAccessibleColorpicker
 * @since 1.0
 */

/**
 * The main control class.
 *
 * @since 1.0
 */
class Kirki_WCAG_Text_Color extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-wcag-tc';

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * @access public
	 * @var array
	 */
	public $output = [];

	/**
	 * Data type
	 *
	 * @access public
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * Option name (if using options).
	 *
	 * @access public
	 * @var string
	 */
	public $option_name = false;

	/**
	 * The kirki_config we're using for this control
	 *
	 * @access public
	 * @var string
	 */
	public $kirki_config = 'global';

	/**
	 * Whitelisting the "required" argument.
	 *
	 * @since 3.0.17
	 * @access public
	 * @var array
	 */
	public $required = [];

	/**
	 * Whitelisting the "preset" argument.
	 *
	 * @since 3.0.26
	 * @access public
	 * @var array
	 */
	public $preset = [];

	/**
	 * Whitelisting the "css_vars" argument.
	 *
	 * @since 3.0.28
	 * @access public
	 * @var string
	 */
	public $css_vars = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		$url = str_replace( ABSPATH, trailingslashit( home_url() ), __DIR__ ); // phpcs:ignore PHPCompatibility.Keywords
		wp_enqueue_script( 'wcag_colors', apply_filters( 'kirki_wcag_text_color_url', $url ) . '/wcagColors.js', [], '1.0', false );
		wp_enqueue_script( 'kirki_wcag_text_color', apply_filters( 'kirki_wcag_text_color_url', $url ) . '/script.js', [ 'jquery', 'customize-base', 'customize-controls', 'wcag_colors' ], '1.1.1', false );
		wp_enqueue_style( 'kirki_wcag_text_color', apply_filters( 'kirki_wcag_text_color_url', $url ) . '/styles.css', [ 'wp-color-picker' ], '1.1.1' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {

		// Get the basics from the parent class.
		parent::to_json();

		// Default value.
		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		// Required.
		$this->json['required'] = $this->required;

		// Output.
		$this->json['output'] = $this->output;

		// Value.
		$this->json['value'] = $this->value();

		// Choices.
		$this->json['choices'] = $this->choices;

		// The link.
		$this->json['link'] = $this->get_link();

		// The ID.
		$this->json['id'] = $this->id;

		// The kirki-config.
		$this->json['kirkiConfig'] = $this->kirki_config;

		// The option-type.
		$this->json['kirkiOptionType'] = $this->option_type;

		// The option-name.
		$this->json['kirkiOptionName'] = $this->option_name;

		// The CSS-Variables.
		$this->json['css-var'] = $this->css_vars;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# if ( data.label ) { #>
			<label><span class="customize-control-title">{{{ data.label }}}</span></label>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<# var modes = ( data.choices && data.choices.modes ) ? data.choices.modes : [ 'auto', 'recommended', 'custom' ]; #>

		<# if ( 2 <= modes.length ) { #>
			<div class="tabs">
				<# _.each( modes, function( mode ) { #>
					<# if ( 'auto' === mode ) { #>
						<button class="button kirki-a11y-text-colorpicker-toggle-auto" data-mode="auto">
							<?php esc_html_e( 'Auto', 'xstore-core' ); ?>
						</button>
					<# } else if ( 'recommended' === mode ) { #>
						<button class="button kirki-a11y-text-colorpicker-toggle-recommended" data-mode="recommended">
							<?php esc_html_e( 'Recommended', 'xstore-core' ); ?>
						</button>
					<# } else { #>
						<button class="button kirki-a11y-text-colorpicker-toggle-custom" data-mode="custom">
							<?php esc_html_e( 'Custom', 'xstore-core' ); ?>
						</button>
					<# } #>
				<# } ); #>
			</div>
		<# } #>
		<div class="mode-selectors">
			<# if ( -1 !== _.indexOf( modes, 'auto' ) ) { #>
				<div class="kirki-a11y-text-colorpicker-wrapper" data-id="auto">
					<?php 
					printf(
						/* translators: The selected Color. */
						esc_html__( 'Auto Color: %s', 'xstore-core' ),
						'<span class="selected-color-previewer-auto"></span>'
					);
					?>
				</div>
			<# } #>
			<# if ( -1 !== _.indexOf( modes, 'recommended' ) ) { #>
				<div class="kirki-a11y-text-colorpicker-wrapper" data-id="recommended">
					<?php
					printf(
						esc_html(
							/**
							 * Allows changing the "Selected Color: %s" text.
							 * Please note that this is escaped using esc_html() so no HTML markup will be accepted.
							 * 
							 * @since 1.0
							 * @param string The text to use. Use %s as a placeholder for the color value.
							 */
							apply_filters( 
								'kirki_wcag_text_color_text_selected_color', 
								/* translators: The selected color. */
								__( 'Selected Color: %s', 'xstore-core' )
							)
						),
						'<span class="selected-color">{{ data.value }}</span>'
					);
					?>
					<div class="wrapper"></div>
				</div>
			<# } #>
			<# if ( -1 !== _.indexOf( modes, 'custom' ) ) { #>
				<div class="kirki-a11y-text-colorpicker-wrapper kirki-input-container" data-id="custom">
					<input type="text" data-default-color="{{ data.default }}" value="{{ data.value }}" class="kirki-color-control" data-id="{{ data.id }}"/>
				</div>
			<# } #>
		</div>
		<?php
	}

	/**
	 * Adding an empty function here prevents PHP errors from to_json() in the parent class.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function render_content() {}
}
