<?php

/**
 * Output overrides.
 */

class Etheme_Output_Field_Sortable extends Kirki_Output {

	/**
	 * Processes a single item from the `output` array.
	 *
	 * @access protected
	 * @param array $output The `output` item.
	 * @param array $value  The field's value.
	 */
	protected function process_output( $output, $value ) {
		foreach ($value as $option) {
			foreach ($option['fields'] as $field ) {
				if ( isset( $field['output'] ) ) {
					foreach ($field['output'] as $output) {

						$output['media_query'] = ( isset( $output['media_query'] ) ) ? $output['media_query'] : 'global';
						$output['prefix']      = ( isset( $output['prefix'] ) ) ? $output['prefix'] : '';
						$output['suffix']      = ( isset( $output['suffix'] ) ) ? $output['suffix'] : '';
						$output['units'] 	   = ( isset( $output['units'] ) ) ? $output['units'] : '';

						$property_value = $this->process_property_value( $output['property'], $field['value'] );

						$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $output['prefix'] . $property_value .$output['units'] . $output['suffix'];
					}
				}
			}
		}
	}
}
