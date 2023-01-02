<?php
/**
 * Handles CSS output for typography fields.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license    https://opensource.org/licenses/MIT
 * @since       2.2.0
 */

/**
 * Output overrides.
 */
class Kirki_Output_Field_Box_Model extends Kirki_Output {

	/**
	 * Processes a single item from the `output` array.
	 *
	 * @access protected
	 * @param array $output The `output` item.
	 * @param array $value  The field's value.
	 */
	protected function process_output( $output, $value ) {
		$output['media_query'] = ( isset( $output['media_query'] ) ) ? $output['media_query'] : 'global';
		$output['prefix']      = ( isset( $output['prefix'] ) ) ? $output['prefix'] : '';
		$output['suffix']      = ( isset( $output['suffix'] ) ) ? $output['suffix'] : '';

		// Early exit if element is not defined.
		if ( ! isset( $output['element'] ) ) {
			return;
		}

		$properties = [
			'margin-top',
			'margin-right',
			'margin-bottom',
			'margin-left',
			'border-top-width',
			'border-right-width',
			'border-bottom-width',
			'border-left-width',
			'padding-top',
			'padding-right',
			'padding-bottom',
			'padding-left',
		];

		foreach ( $properties as $property ) {

			// Early exit if the value is not in the defaults.
			if ( ! isset( $this->field['default'][ $property ] ) ) {
				continue;
			}

			// Early exit if the value is not saved in the values.
			if ( ! isset( $value[ $property ] ) || ! $value[ $property ] ) {
				continue;
			}

			// Early exit if we use "choice" but not for this property.
			if ( isset( $output['choice'] ) && $output['choice'] !== $property ) {
				continue;
			}

			$property_value = $this->process_property_value( $property, $value[ $property ] );
			$property       = ( isset( $output['choice'] ) && isset( $output['property'] ) ) ? $output['property'] : $property;
			$property_value = ( is_array( $property_value ) && isset( $property_value[0] ) ) ? $property_value[0] : $property_value;
			$this->styles[ $output['media_query'] ][ $output['element'] ][ $property ] = $output['prefix'] . $property_value . $output['suffix'];
		}
	}
}
