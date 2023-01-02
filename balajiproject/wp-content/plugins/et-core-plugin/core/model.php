<?php
namespace ETC\Core;

/**
 * Abstract class to define/implement base methods for model classes
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/models
 */
class Model {
	use base;

	/**
	 * Provides access to a single instance of a module.
	 *
	 * @since    1.0.0
	 * @return object
	 */
	public static function get_instance() {
		$classname = get_called_class();
		$instance = self::get( $classname );

		if ( null === $instance ) {
			$instance = new $classname();
			self::set( $classname, $instance );
		}

		return $instance;
	}

}
