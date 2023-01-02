<?php 
	$posttags = get_the_tags();
 ?>
<div class="style3">
    <div class="post-slide">
        <div class="post-img">
            <?php the_post_thumbnail(); ?>
            <div class="category">
                <?php $categories = get_the_category();
                    $separator = ' ';
                    $output = '';
                    if ( ! empty( $categories ) ) {
                        foreach( $categories as $category ) {
                            $output .= esc_html( $category->name );
                        }
                        echo $output;
                    } ?>
            </div>
        </div>
        <div class="post-review">
            <h3 class="post-title" style="font-size: <?php echo $wdo_desc_font_size; ?>">
                <a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
            </h3>
            <p class="post-description" style="font-size: <?php echo $wdo_desc_font_size; ?>">
                <?php the_excerpt(); ?>
            </p>   
            <div class="post-bar">
                <span><i class="fa fa-user"></i> <a href="javascript:void(0)"><?php echo get_the_author(); ?></a></span>
                <span class="comments"><i class="fa fa-comments"></i> <a href="javascript:void(0)"><?php echo get_comments_number(); ?> Comments</a></span>
            </div>
        </div>
    </div>
</div>

