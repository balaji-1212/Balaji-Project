<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Create post type controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Widgets extends Base_Controller{

	/**
     * Registered widgets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $widgets = NULL;

	public function hooks() {
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

    /**
     * Register widget args
     *
     * @return mixed|null|void
     */
    public static function widgets_args() {

        if ( ! is_null( self::$widgets ) ) {
            return self::$widgets;
        }

        return self::$widgets = apply_filters( 'etc/add/widget', array() );
    }

    /**
     * Register Widgets
     * @return null
     */
    public function register_widget() {
        // ! Register it only for XStore theme
        if ( ! defined( 'ETHEME_THEME_NAME' ) || ETHEME_THEME_NAME != 'XStore' ) return;

        $args = self::widgets_args();
        foreach ( $args as $widget_classes ) {
        	foreach ( $widget_classes as $class ) {
        		register_widget( $class );
        	}
        }

    }

}

