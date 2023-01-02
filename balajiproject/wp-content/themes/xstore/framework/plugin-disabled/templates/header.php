<?php
/**
 * The template for displaying theme header while core plugin is disabled.
 *
 * @since   7.1.0
 * @version 1.0.0
 */
?>
<header id="header-default" class="site-header header-default">
	<div class="container">
		<div class="row">
			<div class="col-md-2 col-sm-4 col-xs-12 logo-default">
				<div>
                    <a href="<?php echo get_home_url(); ?>">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.png' ); ?>" alt="logo-default">
                    </a>
				</div>
			</div>
			<div class="col-md-10 col-sm-8 col-xs-12 menu-default">
				<?php add_filter('menu_item_with_svg_arrow', '__return_true'); ?>
                <?php wp_nav_menu(
                        array(
	                        'before' => '',
	                        'container_class' => 'menu-main-container',
	                        'after' => '',
	                        'link_before' => '',
	                        'link_after' => '',
	                        'depth' => 5,
	                        'fallback_cb' => false,
	                        'walker' => new ETheme_Navigation
                        )
                    ); ?>
				<?php remove_filter('menu_item_with_svg_arrow', '__return_true'); ?>
            </div>
		</div>
	</div>
</header>