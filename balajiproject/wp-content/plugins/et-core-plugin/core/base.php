<?php
namespace ETC\Core;

/**
 * Base Registry Trait
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Core/Registry
 */
trait Base {

	/**
	 * Variable that holds all objects in registry.
	 *
	 * @var array
	 */
	protected static $stored_objects = [];

	/**
	 * Add object to registry
	 *
	 * @since      1.4.4
	 * @package    ETC
	 */
	public static function set( $key, $value ) {
		if ( ! is_string( $key ) ) {
			trigger_error( __( 'Key passed to `set` method must be key', 'xstore-core' ), E_USER_ERROR );
		}
		static::$stored_objects[ $key ] = $value;

	}

	/**
	 * Get object from registry
	 *
	 * @since      1.4.4
	 * @package    ETC
	 */
	public static function get( $key ) {
		if ( ! is_string( $key ) ) {
			trigger_error( __( 'Key passed to `get` method must be key', 'xstore-core' ), E_USER_ERROR );
		}

		if ( ! isset( static::$stored_objects[ $key ] ) ) {
			return null;
		}

		return static::$stored_objects[ $key ];
	}

	/**
	 * Returns all objects
	 *
	 * @since      1.4.4
	 * @package    ETC
	 */
	public static function get_all_objects() {
		return static::$stored_objects;
	}
}
