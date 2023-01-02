<?php
global $et_loop;

if( empty( $et_loop['columns'] ) ) {
    $et_loop['columns'] = etheme_get_option('blog_columns', 3);
}

if( empty( $et_loop['slider'] ) ) {
    $et_loop['slider'] = false;
}

if( empty( $et_loop['loop'] ) ) {
    $et_loop['loop'] = 0;
}

$options = array();

$options['layout'] = etheme_get_option('blog_layout', 'default');
$options['by_line'] = etheme_get_option('blog_byline', 1);
$options['size'] = etheme_get_option( 'blog_images_size', 'large' );
$options['hide_img'] = false;
$options['excerpt_length'] = etheme_get_option('excerpt_length', 25);

// get permalink before content because if content has products then link is bloken
$options['the_permalink'] = get_the_permalink();

if ( is_single() && $options['layout'] == 'timeline2' ) {
    $et_loop['slide_view'] = $options['layout'];
}

if( ! empty( $et_loop['blog_layout'] ) ) {
    $options['layout'] = $et_loop['blog_layout'];
}

if( ! empty( $et_loop['size'] ) ) {
    $options['size'] = $et_loop['size'];
}

if( ! empty( $et_loop['hide_img'] ) ) {
    $options['hide_img'] = $et_loop['hide_img'];
}

$options['postClass']      = etheme_post_class( $options['layout'] );

?>

<article <?php post_class( $options['postClass'] ); ?> id="post-<?php the_ID(); ?>" >
    <div>
        <div class="meta-post-timeline">
            <span class="time-day"><?php the_time('d'); ?></span>
            <span class="time-mon"><?php the_time('M'); ?></span>
        </div>

        <?php
            if ( !$options['hide_img'] ) { 
                etheme_post_thumb( array('size' => $options['size'], 'in_slider' => $et_loop['slider'] ) ); 
            }
        ?>

        <div class="grid-post-body">
            <div class="post-heading">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php if($options['by_line']): ?>
                    <?php etheme_byline( array( 'author' => 0, 'in_slider' => $et_loop['slider'] ) );  ?>
                <?php endif; ?>
            </div>

            <div class="content-article">
                <?php if ( $options['excerpt_length'] > 0 ) {
                    if ( strlen(get_the_excerpt()) > 0 ) {
                        $options['excerpt_length'] = apply_filters( 'excerpt_length', $options['excerpt_length'] );
                        $options['excerpt_more'] = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
                        $options['text']         = wp_trim_words( get_the_excerpt(), $options['excerpt_length'], $options['excerpt_more'] );
                        echo apply_filters( 'wp_trim_excerpt', $options['text'], $options['text'] );
                    }
                    else 
                        the_excerpt();
                }  ?>
                <?php etheme_read_more( $options['the_permalink'], true ) ?>
            </div>
        </div>
    </div>
</article>
<?php

$et_loop['loop']++;

unset($options); ?>