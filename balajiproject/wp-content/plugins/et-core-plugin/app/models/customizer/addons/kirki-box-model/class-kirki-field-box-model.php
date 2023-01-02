<?php
/**
 * Override field methods
 *
 * @package    Kirki
 * @subpackage Controls
 * @copyright  Copyright (c) 2017, Aristeides Stathopoulos
 * @license    https://opensource.org/licenses/MIT
 * @since      1.0
 */

/**
 * Field overrides.
 */
class Kirki_Field_Box_Model extends Kirki_Field {

	/**
	 * An array of keys for this object.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private static $valid_keys = [
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

	/**
	 * Sets the $sanitize_callback
	 *
	 * @access protected
	 */
	protected function set_sanitize_callback() {

		// If a custom sanitize_callback has been defined,
		// then we don't need to proceed any further.
		if ( ! empty( $this->sanitize_callback ) ) {
			return;
		}
		$this->sanitize_callback = [ __CLASS__, 'sanitize' ];
	}

	/**
	 * Sanitizes typography controls
	 *
	 * @static
	 * @since 2.2.0
	 * @param array $value The value.
	 * @return array
	 */
	public static function sanitize( $value ) {
		$sanitized = [];
		foreach ( self::$valid_keys as $key ) {
			if ( isset( $value[ $key ] ) ) {
				$sanitized[ $key ] = esc_attr( $value[ $key ] );
			}
		}
		return $sanitized;
	}

	/**
	 * Sets the $js_vars
	 *
	 * @access protected
	 */
	protected function set_js_vars() {
		if ( ! is_array( $this->js_vars ) ) {
			$this->js_vars = [];
		}

		// Check if transport is set to auto.
		// If not, then skip the auto-calculations and exit early.
		if ( 'auto' !== $this->transport ) {
			return;
		}

		// Set transport to refresh initially.
		// Serves as a fallback in case we failt to auto-calculate js_vars.
		$this->transport = 'refresh';

		$js_vars = [];

		// Try to auto-generate js_vars.
		// First we need to check if js_vars are empty, and that output is not empty.
		if ( ! empty( $this->output ) ) {

			// Start going through each item in the $output array.
			foreach ( $this->output as $output ) {

				// If 'element' or 'property' are not defined, skip this.
				if ( ! isset( $output['element'] ) ) {
					continue;
				}
				if ( is_array( $output['element'] ) ) {
					$output['element'] = implode( ',', $output['element'] );
				}

				// If we got this far, it's safe to add this.
				$js_vars[] = $output;
			}

			// Did we manage to get all the items from 'output'?
			// If not, then we're missing something so don't add this.
			if ( count( $js_vars ) !== count( $this->output ) ) {
				return;
			}
			$this->js_vars   = $js_vars;
			$this->transport = 'postMessage';
			foreach ( $this->js_vars as $var ) {
				if ( isset( $var['element'] ) && ! isset( $var['property'] ) ) {
					foreach ( self::$valid_keys as $valid_key ) {
						$this->js_vars[] = array_merge(
							$var,
							[
								'property' => $valid_key,
								'choice'   => $valid_key,
							]
						);
					}
				}
			}
		}
	}
}
