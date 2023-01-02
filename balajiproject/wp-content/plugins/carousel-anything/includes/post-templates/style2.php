<?php 
	$posttags = get_the_tags();
 ?>
<div class="style2">
    <div class="post-slide">
        <ul class="post-info">
            <li>
                <i class="fa fa-tag"></i>
                <?php   
                    if ($posttags) { ?>
                        <?php foreach($posttags as $tag): ?>
                            <a href="<?php echo get_tag_link($tag->term_id);  ?>"><?php echo $tag->name . ' ';  ?></a>,
                        <?php endforeach; ?>
                    <?php }
                ?>
            </li>
            <li><i class="fa fa-calendar"></i> <?php echo get_the_date() ?></li>
        </ul>
        <div class="post-img">
            <?php the_post_thumbnail(); ?>
            <a href="<?php the_permalink(); ?>" class="read"><?php echo $wdo_read_more_text; ?></a>
        </div>
        <div class="post-content">
            <h3 class="post-title" style="font-size: <?php echo $wdo_desc_font_size; ?>">
            	<a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
            </h3>
            <p class="post-description" style="font-size: <?php echo $wdo_desc_font_size; ?>">
                <?php the_excerpt(); ?>
            </p>   
        </div>
    </div>
</div>
