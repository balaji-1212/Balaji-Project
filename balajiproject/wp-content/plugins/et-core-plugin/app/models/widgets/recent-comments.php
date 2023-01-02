<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Recent comments Widget.
 * 
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Recent_Comments extends Widgets {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_recent_comments', 'description' => esc_html__( 'The most recent comments (Etheme edit)', 'xstore-core' ) );
        parent::__construct('etheme-recent-comments', '8theme - '.esc_html__('Recent Comments', 'xstore-core'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_recent_comments';

        if ( is_active_widget(false, false, $this->id_base) )
            add_action( 'wp_head', array(&$this, 'recent_comments_style') );

        add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
        add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
    }

    function recent_comments_style() {
        if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
            || ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
            return;
        ?>
<?php
    }

    function flush_widget_cache() {
        wp_cache_delete('etheme_widget_recent_comments', 'widget');
    }

    function widget( $args, $instance ) {
	    if (parent::admin_widget_preview(esc_html__('Recent Comments', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;
	    $ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

	    if (apply_filters('et_ajax_widgets', $ajax)){
		    echo et_ajax_element_holder( 'Recent_Comments', $instance, '', '', 'widget', $args );
		    return;
	    }
        global $comments, $comment;

        $cache = wp_cache_get('etheme_widget_recent_comments', 'widget');

        if ( ! is_array( $cache ) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

	    if (!is_null($args)) {
		    extract($args, EXTR_SKIP);
	    }

        $output = '';

        if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
            $number = 5;

        $comments = get_comments( array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) );
        $output .= (isset($before_widget)) ? $before_widget : '';

	    echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

        $output .= '<ul id="recentcomments">';
        if ( $comments ) {
            foreach ( (array) $comments as $comment) {
                //$output .=  '<li class="recentcomments"><div class="comment-date">' . get_comment_date('d') . ' <span>' . get_comment_date('M') . '</span>' . '</div>' . sprintf(_x('<span class="comment_author">%1$s</span> <br> %2$s', 'widgets'), get_comment_author_link(), '<span class="comment_link"><a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a></span>') . '<div class="clear"></div></li>';

                $output .=  '<li class="recentcomments">';
                    $output .=  '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '" class="post-title">' . get_the_title($comment->comment_post_ID) . '</a>';
                    $output .=  get_the_time('d M Y', $comment->comment_post_ID);
                    $output .=  ' @ '.get_the_time(get_option('time_format'), $comment->comment_post_ID);
                    $output .=  ' '.__('by', 'xstore-core').' <span class="comment_author">'.get_comment_author_link().'</span>';
                $output .=  '</li>';
            }
        }
        $output .= '</ul>';
        $output .= (isset($after_widget)) ? $after_widget : '';

        echo $output;
        $cache[$args['widget_id']] = $output;
        wp_cache_set('widget_recent_comments', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint( $new_instance['number'] );
	    $instance['ajax'] = (bool) $new_instance['ajax'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['etheme_widget_recent_comments']) )
            delete_option('etheme_widget_recent_comments');

        return $instance;
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
	    $ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'xstore-core'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of comments to show:', 'xstore-core'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php  	 parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );

    }
}