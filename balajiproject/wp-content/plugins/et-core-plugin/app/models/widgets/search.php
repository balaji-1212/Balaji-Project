<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Search Widget.
 *
 * @since      3.2.6
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 * @todo Add search select options
 */
class Search extends Widgets {

	function __construct() {
		$widget_ops = array(
			'classname' => 'etheme_widget_search',
			'description' => 'A search form for your site. Additional settings available in Customizer > Header Builder > Search'
		);
		parent::__construct('etheme-search', '8theme - '.esc_html__('Search', 'xstore-core'), $widget_ops);
		$this->alt_option_name = 'etheme_widget_search';
	}

	function widget($args, $instance) {
		if (parent::admin_widget_preview(esc_html__('Search', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		$ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

        if (!is_null($args)) {
            extract($args);
        }

		if (apply_filters('et_ajax_widgets', $ajax)){
			echo et_ajax_element_holder( 'Search', $instance, '', '', 'widget', $args );
			return;
		}

		$placeholder = isset($instance['placeholder']) ? $instance['placeholder'] : '';
		//$products = (bool) $instance['products'];
		//$posts = (bool) $instance['posts'];
		//$pages = (bool) $instance['pages'];
		//$portfolio = (bool) $instance['portfolio'];

		//if (!$products){
		//	$post_type = 'post';
		//} else {
			$post_type = 'product';
		//}

		echo (isset($before_widget)) ? $before_widget : '';

		echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		?>

			<form role="search" method="get" class="woocommerce-product-search etheme-custom-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'xstore-core' ); ?></label>
				<input type="search" class="search-field" placeholder="<?php echo $placeholder; ?>" value="<?php echo get_search_query(); ?>" name="s" />
				<button type="submit" value=""><?php echo apply_filters('xstore_theme_amp', false) ? esc_html__('Search', 'xstore-core') : ''; ?></button>
                <input type="hidden" name="et_search" value="true">
				<input type="hidden" name="post_type" value="<?php echo $post_type; ?>" />
			</form>
		<?php echo (isset($after_widget)) ? $after_widget : '';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
        $instance['placeholder']  = strip_tags( $new_instance['placeholder'] );
		//$instance['products'] = isset( $new_instance['products'] ) ? (bool) $new_instance['products'] : false;
		$instance['ajax'] = isset( $new_instance['ajax'] ) ? (bool) $new_instance['ajax'] : false;
		//$instance['posts'] = isset( $new_instance['posts'] ) ? (bool) $new_instance['posts'] : false;
		//$instance['pages'] = isset( $new_instance['pages'] ) ? (bool) $new_instance['pages'] : false;
		//$instance['portfolio'] = isset( $new_instance['portfolio'] ) ? (bool) $new_instance['portfolio'] : false;

		if ( function_exists ( 'icl_register_string' ) ){
			icl_register_string( 'Widgets', 'ETheme_Search_Widget - title', $instance['title'] );
			icl_register_string( 'Widgets', 'ETheme_Search_Widget - placeholder', $instance['placeholder'] );
		}


		return $instance;
	}

	function form( $instance ) {

		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$placeholder    = isset( $instance['placeholder'] ) ? $instance['placeholder'] : esc_html__( 'Search...', 'xstore-core' );
		$products = isset( $instance['products'] ) ? (bool) $instance['products'] : false;
		$posts = isset( $instance['posts'] ) ? (bool) $instance['posts'] : false;
		$pages = isset( $instance['pages'] ) ? (bool) $instance['pages'] : false;
		$portfolio = isset( $instance['portfolio'] ) ? (bool) $instance['portfolio'] : false;
		$ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;

		?>
		<?php parent::widget_input_text( esc_html__( 'Widget title:', 'xstore-core' ), $this->get_field_id( 'title' ),$this->get_field_name( 'title' ), $title ); ?>

		<?php parent::widget_input_text( esc_html__( 'Placeholder text:', 'xstore-core' ), $this->get_field_id( 'placeholder' ),$this->get_field_name( 'placeholder' ), $placeholder ); ?>


		<?php //parent::widget_input_checkbox( esc_html__( 'Posts', 'xstore-core' ), $this->get_field_id( 'posts' ), $this->get_field_name( 'posts' ),checked( $posts, true, false ), 1 ); ?>

		<?php //parent::widget_input_checkbox( esc_html__( 'Pages', 'xstore-core' ), $this->get_field_id( 'pages' ), $this->get_field_name( 'pages' ),checked( $pages, true, false ), 1 ); ?>

		<?php //parent::widget_input_checkbox( esc_html__( 'Portfolio', 'xstore-core' ), $this->get_field_id( 'portfolio' ), $this->get_field_name( 'portfolio' ),checked( $portfolio, true, false ), 1 ); ?>

		<?php parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 ); ?>

		<?php
	}

}