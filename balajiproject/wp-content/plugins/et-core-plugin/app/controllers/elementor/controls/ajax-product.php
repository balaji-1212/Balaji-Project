<?php
namespace ETC\App\Controllers\Elementor\Controls;

/**
 * Ajax select2 control.
 *
 * @since 2.3.9
 */
class Ajax_Product extends \Elementor\Base_Data_Control {

 
	/**
	 * Get select control type.
	 *
	 * @since 2.3.9
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'etheme-ajax-product';
	}

	public function enqueue() {

		wp_register_script( 
			'etheme-ajax-product',
			ET_CORE_URL . 'app/assets/js/ajax-product.js', 
			[  'jquery' ],
			ET_CORE_VERSION
		);

		wp_localize_script( 'etheme-ajax-product', 'ajaxproduct', array(
			'ajaxurl'                => admin_url( 'admin-ajax.php' ),
		) );
		wp_enqueue_script( 'etheme-ajax-product' );

	}

	/**
	 * Get select2 control default settings.
	 *
	 * Retrieve the default settings of the select2 control. Used to return the
	 * default settings while initializing the select2 control.
	 *
	 * @since 2.3.9
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
			'multiple' => false,
			'select2options' => [],
		];
	}


	/**
	 * Render icons control output in the editor.
	 *
	 * @since 2.3.9
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field ajax-product-tab">
			<# if ( data.label ) {#>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5" data-nonce="<?php echo wp_create_nonce( 'select2_ajax_control' ); ?>">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
