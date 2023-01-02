<?php
/**
 * The Kirki API class.
 * Takes care of adding panels, sections & fields to the customizer.
 * For documentation please see https://github.com/aristath/kirki/wiki
 *
 * @package     Kirki
 * @category    Core
 * @author      Ari Stathopoulos (@aristath)
 * @copyright   Copyright (c) 2020, David Vongries
 * @license     https://opensource.org/licenses/MIT
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class acts as an interface.
 * Developers may use this object to add configurations, fields, panels and sections.
 * You can also access all available configurations, fields, panels and sections
 * by accessing the object's static properties.
 */
class Kirki_Extended extends Kirki {

	/**
	 * Create a new field.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The configuration ID for this field.
	 * @param array  $args      The field arguments.
	 */
	public static function add_field( $config_id, $args ) {
		// Early exit if 'type' is not defined.
		if ( ! isset( $args['type'] ) ) {
			return;
		}

		$str       = str_replace( array( '-', '_' ), ' ', $args['type'] );
		$classname = 'Kirki_Field_' . str_replace( ' ', '_', ucwords( $str ) );
		if ( class_exists( $classname ) ) {
			new $classname( $config_id, $args );
			return;
		}
		if ( false !== strpos( $classname, 'Kirki_Field_Kirki_' ) ) {
			$classname = str_replace( 'Kirki_Field_Kirki_', 'Kirki_Field_', $classname );
			if ( class_exists( $classname ) ) {
				new $classname( $config_id, $args );
				return;
			}
		}
		new Kirki_Field_Extended( $config_id, $args );
	}

}
