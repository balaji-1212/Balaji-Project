<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Post Meta shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Post_Meta extends Shortcodes {

    function hooks() {}

    function post_meta_shortcode($atts) {
	    if ( xstore_notice() )
		    return;
        
        $atts = shortcode_atts(array(
            'time' => true,
            'time_details' => true,
            'author'  => true,
            'comments' => true,
            'count' => true,
            'class' => '',
        ), $atts);

        ob_start();

        ?>

        <div class="<?php echo esc_attr($atts['class']); ?> meta-post et-shortcode">

            <?php if ( $atts['time'] == 'true' ): ?>
                <time class="entry-date published updated" datetime="<?php the_time('F j, Y'); ?>">
                    <?php the_time( get_option( 'date_format' ) ); ?>
                </time>
            <?php endif;

            if ( $atts['time_details'] == 'true' ) {
                echo ' ' . esc_html__( 'at', 'xstore-core' ) . ' ';
                the_time( get_option( 'time_format' ) );
            }

            if ( $atts['author'] == 'true' ) {
                echo ' ' . esc_html__( 'by', 'xstore-core' ) . ' ';
                the_author_posts_link();
            } 
                    
            if ( $atts['count'] == 'true' ): ?>
                <span class="meta-divider">/</span>
                 <?php
                if ( function_exists('etheme_get_views')) {
	                etheme_get_views( '', true );
                } ?>
            <?php endif;

                // Comments
                if( $atts['comments'] == 'true' && comments_open() && !post_password_required()) { ?>
                    <span class="meta-divider">/</span>
                    <?php comments_popup_link('<span>0</span>','<span>1</span>','<span>%</span>','post-comments-count');
                }
            ?>
               
        </div>
        <?php
        return ob_get_clean();
    }
}