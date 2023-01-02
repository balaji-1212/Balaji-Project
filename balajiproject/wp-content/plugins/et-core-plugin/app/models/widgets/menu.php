<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;
use ETC\App\Controllers\Shortcodes\Menu as Menu_Shortcode;

/**
 * Menu widget.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Menu extends Widgets {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_menu', 'description' => esc_html__( "Menu", 'xstore-core') );
        parent::__construct('etheme-menu', '8theme - '.esc_html__('Menu', 'xstore-core'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_menu';
    }

    function widget($args, $instance) {
	    if (parent::admin_widget_preview(esc_html__('Menu', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;
    	
	    $args = null == $args ? array('before_widget' => '', 'after_widget' => '', 'before_title' => '', 'after_title' => '') : $args;

	    if (!is_null($args)) {
		    extract($args);
	    }

	    $ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

	    if (apply_filters('et_ajax_widgets', $ajax)){
		    echo et_ajax_element_holder( 'Menu', $instance, '', '', 'widget', $args );
		    return;
	    }

        if ( empty( $instance['number'] ) || !$number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;
        else if ( $number > 15 )
            $number = 15;

        $menu  = ( ! empty( $instance['menu'] ) ) ? $instance['menu'] : '';
        $style = ( ! empty( $instance['style'] ) ) ? $instance['style'] : '';
        $align = ( ! empty( $instance['align'] ) ) ? $instance['align'] : '';
        $class = ( ! empty( $instance['class'] ) ) ? $instance['class'] : '';

	    echo (isset($before_widget)) ? $before_widget : '';
	    echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

        $Menu = Menu_Shortcode::get_instance();

        echo $Menu->menu_shortcode(array(
            'menu'  => $menu,
            'style' => $style,
            'align' => $align,
            'class' => $class,
        ));

	    echo (isset($after_widget)) ? $after_widget : '';

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['menu']  = strip_tags( $new_instance['menu'] );
        $instance['style'] = strip_tags( $new_instance['style'] );
        $instance['align'] = strip_tags( $new_instance['align'] );
        $instance['class'] = strip_tags( $new_instance['class'] );
	    $instance['ajax'] = (isset($new_instance['ajax'])) ? (bool) $new_instance['ajax'] : false;

        return $instance;
    }

    function form( $instance ) {
        $title = ( ! isset( $instance['title'] ) ) ? '' : esc_attr( $instance['title'] );
        $menu  = ( ! isset( $instance['menu'] ) ) ? '' : esc_attr( $instance['menu'] );
        $style = ( ! isset( $instance['style'] ) ) ? '' : esc_attr( $instance['style'] );
        $align = ( ! isset( $instance['align'] ) ) ? '' : esc_attr( $instance['align'] );
        $class = ( ! isset( $instance['class'] ) ) ? '' : esc_attr( $instance['class'] );
	    $ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;

        $menus = wp_get_nav_menus();
        $menu_params = array();
        foreach ( $menus as $menu_param ) {
            $menu_params[$menu_param->term_id] = $menu_param->name;
        }

        parent::widget_input_text( esc_html__( 'Title', 'xstore-core' ), $this->get_field_id( 'title' ),$this->get_field_name( 'title' ), $title );
        
        parent::widget_input_dropdown( esc_html__( 'Menu', 'xstore-core' ), $this->get_field_id('menu'),$this->get_field_name('menu'), $menu, $menu_params );

        parent::widget_input_dropdown( esc_html__( 'Style', 'xstore-core' ), $this->get_field_id('style'),$this->get_field_name('style'), $style, array(
            'vertical'   => 'Vertical',
            'horizontal' => 'Horizontal',
            'menu-list'  => 'Simple List',
        ));

        parent::widget_input_dropdown( esc_html__( 'Align', 'xstore-core' ), $this->get_field_id( 'align' ),$this->get_field_name( 'align' ), $align, array(
            'left'   => 'Left',
            'center' => 'Center',
            'right'  => 'Right',
        ));

        parent::widget_input_text( esc_html__( 'Extra class name', 'xstore-core' ), $this->get_field_id( 'class' ),$this->get_field_name( 'class' ), $class );
	    parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );

    }
}