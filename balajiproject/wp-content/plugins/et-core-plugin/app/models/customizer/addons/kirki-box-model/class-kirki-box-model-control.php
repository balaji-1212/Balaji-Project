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
class Kirki_Box_Model_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-box-model';

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

		// Enqueue the script and style.
		wp_enqueue_script( 'kirki_box_model_control', apply_filters( 'kirki_box_model_control_url', plugins_url( __FILE__ ) ) . '/script.js', [ 'jquery', 'customize-base', 'customize-controls' ], '1.0', false );
		wp_enqueue_style( 'kirki_box_model_control', apply_filters( 'kirki_box_model_control_url', plugins_url( __FILE__ ) ) . '/styles.css', [], '1.0' );
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
		<!-- Label. -->
		<# if ( data.label ) { #>
			<label><span class="customize-control-title">{{{ data.label }}}</span></label>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<#
		var levels = [ 'margin', 'border', 'padding' ];
		if ( data.choices && ( data.choices.margin || data.choices.border || data.choices.padding ) ) {
			levels = [];
			if ( data.choices.margin ) {
				levels.push( 'margin' );
			}
			if ( data.choices.border ) {
				levels.push( 'border' );
			}
			if ( data.choices.padding ) {
				levels.push( 'padding' );
			}
		}

		var labelsL10n = {
			margin: '<?php esc_html_e( 'Margin', 'xstore-core' ); ?>',
			border: '<?php esc_html_e( 'Border', 'xstore-core' ); ?>',
			padding: '<?php esc_html_e( 'Padding', 'xstore-core' ); ?>',
			'margin-top': '<?php esc_html_e( 'Margin Top', 'xstore-core' ); ?>',
			'margin-right': '<?php esc_html_e( 'Margin Right', 'xstore-core' ); ?>',
			'margin-bottom': '<?php esc_html_e( 'Margin Bottom', 'xstore-core' ); ?>',
			'margin-left': '<?php esc_html_e( 'Margin Left', 'xstore-core' ); ?>',
			'border-top': '<?php esc_html_e( 'Border Top', 'xstore-core' ); ?>',
			'border-right': '<?php esc_html_e( 'Border Right', 'xstore-core' ); ?>',
			'border-bottom': '<?php esc_html_e( 'Border Bottom', 'xstore-core' ); ?>',
			'border-left': '<?php esc_html_e( 'Border Left', 'xstore-core' ); ?>',
			'padding-top': '<?php esc_html_e( 'Padding Top', 'xstore-core' ); ?>',
			'padding-right': '<?php esc_html_e( 'Padding Right', 'xstore-core' ); ?>',
			'padding-bottom': '<?php esc_html_e( 'Padding Bottom', 'xstore-core' ); ?>',
			'padding-left': '<?php esc_html_e( 'Padding Left', 'xstore-core' ); ?>',
		};
		#>

		<div class="box levels-{{ levels.length }}">
			<# _.each( levels, function( context, level ) { #>
				<div class="{{ context }} level-{{ level }}">
					<span class="label">{{ labelsL10n[ context ] }}</span>
					<# _.each( [ 'top', 'right', 'bottom', 'left' ], function( side ) { #>
						<# var key = ( 'border' === context ) ? context + '-' + side + '-width' : context + '-' + side; #>
						<label class="screen-reader-text" for="{{ data.id }}-{{ key }}">{{ labelsL10n[ context + '-' + side ] }}</label>
						<input
							class="box-model-input {{ side }} {{ key }}"
							type="text"
							value="{{ data.value[ key ] }}"
							id="{{ data.id }}-{{ key }}"
							size="1"
							data-target-option="{{ key }}"/>
					<# }); #>
				</div>
			<# }); #>
		</div>
		<input class="hidden-value" type="hidden" {{{ data.link }}}/>
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
