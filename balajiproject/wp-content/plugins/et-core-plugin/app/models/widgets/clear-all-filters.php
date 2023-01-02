<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Clear_all_filters Widget.
 *
 * @since      3.0.3
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Clear_all_filters extends Widgets {

	function __construct() {
		$widget_ops = array(
			'classname' => 'etheme_clear_all_filters',
			'description' => esc_html__( 'Clear all filters button', 'xstore-core')
		);
		parent::__construct('etheme-clear-all-filters', '8theme - '.esc_html__('Clear All Filters', 'xstore-core'), $widget_ops);
	}

	function widget($args, $instance) {
		if (parent::admin_widget_preview(esc_html__('Clear All Filters', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		if (!is_null($args)) {
			extract($args);
		}

		echo (isset($before_widget)) ? $before_widget : '';
		
			echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

			$obj_id = get_queried_object_id();
			$url = get_term_link( $obj_id );

			if ( is_wp_error($url)){
				$url = get_permalink( wc_get_page_id( 'shop' ) );
			}

			if (!$url){
				$url = get_home_url();
			}

			echo '<a class="etheme-ajax-filter etheme-clear-all-filters btn btn-black button" href="' . $url . '" style="height: auto;"><span class="et-icon et_b-icon et-clear-filter" style="transform: scale(1.2);"></span><span>' . esc_html__('Clear All Filters', 'xstore-core') . '</span></a>';

		echo (isset($after_widget)) ? $after_widget : '';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		//$instance['text']    = strip_tags( $new_instance['text'] );

		if ( function_exists ( 'icl_register_string' ) ){
			icl_register_string( 'Widgets', 'ETheme_Clear_All_Filters - title', $instance['title'] );
			//icl_register_string( 'Widgets', 'ETheme_Clear_All_Filters - text', $instance['text'] );
		}
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		//$text = isset( $instance['text'] ) ? $instance['text'] : esc_html__('Clear All Filters', 'xstore-core');
		parent::widget_input_text( esc_html__( 'Widget title:', 'xstore-core' ), $this->get_field_id( 'title' ),$this->get_field_name( 'title' ), $title );
		//parent::widget_input_text( esc_html__( 'Button text:', 'xstore-core' ), $this->get_field_id( 'text' ),$this->get_field_name( 'text' ), $text );
	}
}
