<?php
namespace ETC\Inc;

use ETC\Inc\Core;

/**
 * Class Responsible for registering Routes
 *
 * @since      1.0.0
 * @package    ETC
 * @subpackage ETC/Core
 */
class Router {

	/**
	 * Use this route type if a controller/model needs to be loaded on every
	 * request
	 *
	 * @since    1.4.4
	 */
	const ANY = 'any';

	/**
	 * Use this route type if a controller/model needs to be loaded only on
	 * admin/dashboard request
	 *
	 * @since    1.4.4
	 */
	const ADMIN = 'admin';

	/**
	 * Use this route type if a controller/model needs to be loaded only on
	 * Ajax Requests
	 *
	 * @since    1.4.4
	 */
	const AJAX = 'ajax';

	/**
	 * Use this route type if a controller/model needs to be loaded only on
	 * Cron Requests
	 *
	 * @since    1.4.4
	 */
	const CRON = 'cron';

	/**
	 * Use this route type if a controller/model needs to be loaded only on
	 * Frontend
	 *
	 * @since    1.4.4
	 */
	const FRONTEND = 'frontend';

	/**
	 * Holds List of Models used for 'Model Only' Routes
	 *
	 * @var array
	 * @since    1.4.4
	 */
	private static $models = [];

	/**
	 * Holds Model, View & Controllers triad for All routes except 'Model Only' Routes
	 *
	 * @var array
	 * @since    1.4.4
	 */
	private static $components = NULL;

    /**
     * Register components args
     *
     * @return mixed|null|void
     */
    public static function components_args() {

        if ( ! is_null( self::$components ) ) {
            return self::$components;
        }

        return self::$components = apply_filters( 'etc/add/components', array() );
    }

	/**
	 * Constructor
	 *
	 * @since    1.4.4
	 */
	public function __construct() {
		// Check if theme is not defiend return.
		include_once( ET_CORE_DIR . 'config/routes.php' );
		$this->_register_routes();
	}

	/**
	 * Registers Enqueued Routes
	 *
	 * @return void
	 * @since    1.4.4
	 */
	private function _register_routes() {
		//Compontents
		$components = self::components_args();

		foreach ( $components as $mvc_component ) {
			if ( $this->is_request( $mvc_component[ 'type' ] ) && ! empty( $mvc_component[ 'type' ] ) ) {
				$this->dispatch( $mvc_component, $mvc_component[ 'type' ] );
			}
		}
	}

	/**
	 * Dispatches the route of specified $route_type by creating a controller object
	 *
	 * @param array  $mvc_component Model-View-Controller triads for all registered routes.
	 * @param string $route_type Route Type.
	 * @return void
	 * @since    1.4.4
	 */
	private function dispatch( $mvc_component, $route_type ) {
		$model = false;
		$view = false;

		if ( isset( $mvc_component['controller'] ) && false === $mvc_component['controller'] ) {
			return;
		}

		if ( is_callable( $mvc_component['controller'] ) ) {
			$mvc_component['controller'] = call_user_func( $mvc_component['controller'] );

			if ( false === $mvc_component['controller'] ) {
				return;
			}
		}

		if ( isset( $mvc_component['model'] ) && false !== $mvc_component['model'] ) {
			if ( is_callable( $mvc_component['model'] ) ) {
				$mvc_component['model'] = call_user_func( $mvc_component['model'] );
			}

			$model = $this->get_fully_qualified_class_name( $mvc_component['model'], 'model', $route_type );
		}

		if ( isset( $mvc_component['view'] ) && false !== $mvc_component['view'] ) {
			if ( is_callable( $mvc_component['view'] ) ) {
				$mvc_component['view'] = call_user_func( $mvc_component['view'] );
			}

			$view = $this->get_fully_qualified_class_name( $mvc_component['view'], 'view', $route_type );
		}

		$controller_exp = explode( '@', $mvc_component['controller'] );
		$controller 	= isset( $controller_exp[0] ) ? $controller_exp[0] : '';
		$action 		= isset( $controller_exp[1] ) ? $controller_exp[1] : null;

		$controller = $this->get_fully_qualified_class_name( $controller, 'controller', $route_type );

		if ( isset( $mvc_component['config'] ) && false !== $mvc_component['config'] ) {
			$config = $mvc_component['config'];
		} else {
			$config = false;
		}

		$controller_instance = $controller::get_instance( $model, $view, $config );
		
		if ( null !== $action ) {
			$controller_instance->$action();
		}
	}

	/**
	 * Returns the Full Qualified Class Name for given class name
	 *
	 * @param string $mvc_component_type Could be between 'model', 'view' or 'controller'.
	 * @param string $route_type Could be 'admin' or 'frontend'.
	 * @return string Retuns Full Qualified Class Name.
	 * @since    1.4.4
	 */
	private function get_fully_qualified_class_name( $class, $mvc_component_type, $route_type ) {
		// If route type is admin.
		if ( \strpos( $route_type, 'admin' ) !== false ) {
			
			$addr = '\ETC\App\\';
			if ( 'view' === $mvc_component_type ) {
				$addr = '\ETC\\';
			}
			$addr .= \ucfirst( $mvc_component_type ) . 's\\';
			$addr .= \strpos( $route_type, 'admin' ) !== false ? 'Admin\\' : '';

			if ( class_exists( $addr . $class ) ) {
				return $addr . $class;
			}
		}		

		// If route type is general.
		if ( \strpos( $route_type, 'any' ) !== false ) {
			
			$addr = '\ETC\App\\';
			if ( 'view' === $mvc_component_type ) {
				$addr = '\ETC\\';
			}
			$addr .= \ucfirst( $mvc_component_type ) . 's\\';

			if ( class_exists( $addr . $class ) ) {
				return $addr . $class;
			}
		}

		return $class;
	}

	/**
	 * Identifies Request Type
	 *
	 * @param string $route_type Route Type to identify.
	 * @return boolean
	 * @since    1.4.4
	 */
	private function is_request( $route_type ) {
		switch ( $route_type ) {
			case self::ANY:
			return true;
			case self::ADMIN:
			return is_admin();
			case self::FRONTEND:
			return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
			case self::AJAX:
			return defined( 'DOING_AJAX' );
			case self::CRON:
			return defined( 'DOING_CRON' );
		}
	}
}
