<?php 
	$posttags = get_the_tags();
 ?>
<div class="style1">
    <div class="post-slide">
        <div class="post-img">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
        </div>
        <div class="post-content">
            <h3 class="post-title" style="font-size: <?php echo $wdo_desc_font_size; ?>">
            	<a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
            </h3>
            <p class="post-description" style="font-size: <?php echo $wdo_desc_font_size; ?>">
                <?php the_excerpt(); ?>
            </p>
            <ul class="post-bar">
                <li><i class="fa fa-calendar"></i> <?php echo get_the_date() ?></li>
                <li>
                    <i class="fa fa-folder"></i>
                    <?php 	
						if ($posttags) { ?>
							<?php foreach($posttags as $tag): ?>
					            <a href="<?php echo get_tag_link($tag->term_id);  ?>"><?php echo $tag->name . ' ';  ?></a>
					        <?php endforeach; ?>
						<?php }
                    ?>
                </li>
            </ul>
            <a href="<?php the_permalink(); ?>" class="read-more"><?php echo $wdo_read_more_text; ?></a>
        </div>
    </div>
</div>