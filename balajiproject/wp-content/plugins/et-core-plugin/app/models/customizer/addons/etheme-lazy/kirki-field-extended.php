<?php
/**
 * Creates and validates field parameters.
 *
 * @package     Kirki
 * @category    Core
 * @author      Ari Stathopoulos (@aristath)
 * @copyright   Copyright (c) 2020, David Vongries
 * @license     https://opensource.org/licenses/MIT
 * @since       1.0
 */

/**
 * Please do not use this class directly.
 * You should instead extend it per-field-type.
 */
class Kirki_Field_Extended extends Kirki_Field {

	/**
	 * Processes the field arguments
	 *
	 * @access protected
	 */
	protected function set_field() {

		$properties = get_class_vars( __CLASS__ );

		// Some things must run before the others.
		$this->set_option_type();
		$this->set_settings();

		// Sanitize the properties, skipping the ones that have already run above.
		foreach ( $properties as $property => $value ) {
			if ( in_array( $property, array( 'option_name', 'option_type', 'settings' ), true ) ) {
				continue;
			}
			if ( method_exists( $this, 'set_' . $property ) ) {
				$method_name = 'set_' . $property;
				$this->$method_name();
			}
		}

		// Get all arguments with their values.
		$args = get_object_vars( $this );
		foreach ( array_keys( $args ) as $key ) {
			$args[ $key ] = $this->$key;
		}

		// Add the field to the static $fields variable properly indexed.
		Kirki_Extended::$fields[ $this->settings ] = $args;

	}

}
