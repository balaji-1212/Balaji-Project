<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Featured post.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */class Featured_Posts extends Widgets {

	function __construct() {
		$widget_ops = array('classname' => 'etheme_widget_featured', 'description' => esc_html__( "Show featured posts", 'xstore-core') );
		parent::__construct('etheme-featured-posts', '8theme - '.esc_html__('Featured Posts', 'xstore-core'), $widget_ops);
		$this->alt_option_name = 'etheme_widget_featured';
	}

	function widget($args, $instance) {
		if (parent::admin_widget_preview(esc_html__('Featured Posts', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		$ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

		if (apply_filters('et_ajax_widgets', $ajax)){
			echo et_ajax_element_holder( 'Featured_Posts', $instance, '', '', 'widget', $args );
			return;
		}

		global $et_loop;
		if (!is_null($args)) {
			extract($args);
		}

		$box_id = rand(1000,10000);

		$ids = empty($instance['ids']) ? '' : $instance['ids'];
		$excerpt = empty($instance['excerpt']) ? false : true;

		$post_args = array(
			'posts_per_page' 	  => 10, 
			'post_type'        	  => 'post', 
			'post_status'   	  => 'publish', 
			'ignore_sticky_posts' => 1
		);

		if( ! empty( $ids ) ) {
			$post_args['post__in'] = explode(',', $ids);
		}

		$query = new \WP_Query( $post_args );

		$size = 'medium';

		$et_loop['blog_layout'] = 'default';
		$et_loop['columns'] = 1;

		if ($query->have_posts()) : ?>

			<?php echo (isset($before_widget)) ? $before_widget : ''; ?>
			<?php echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>

			<div class="featured-posts-widget <?php if( $excerpt ) echo 'hide-excerpt'; ?>">
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<?php get_template_part( 'content' ); ?>
				<?php endwhile; ?>
			</div>
			 
			<?php echo (isset($after_widget)) ? $after_widget : ''; ?>

		<?php endif;

		unset($et_loop);

		wp_reset_query();  // Restore global post data stomped by the_post().
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 	 = strip_tags($new_instance['title']);
		$instance['ids'] 	 = strip_tags($new_instance['ids']);
		$instance['excerpt'] = isset($new_instance['excerpt']) ? (int) $new_instance['excerpt'] : 30;
		$instance['ajax'] = isset($new_instance['ajax']) ? (bool) $new_instance['ajax'] : false;

		return $instance;
	}


	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'Featured Posts',
			'ids'   => '',
		) );

		$title = $instance['title'];
		$ids = $instance['ids'];
		$excerpt = isset($instance['excerpt']) ? (int) $instance['excerpt'] : 0;
		$ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;

		parent::widget_input_text( esc_html__( 'Title:', 'xstore-core' ), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $title );
		parent::widget_input_text( esc_html__( 'Post IDs, separated by commas:', 'xstore-core' ), $this->get_field_id( 'ids' ), $this->get_field_name( 'ids' ), $ids );
		parent::widget_input_checkbox( esc_html__( 'Hide excerpt', 'xstore-core' ), $this->get_field_id( 'excerpt' ), $this->get_field_name( 'excerpt' ),checked( $excerpt, true, false ), 1);
		parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );
	}
}