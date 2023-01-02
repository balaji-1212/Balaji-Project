<?php
/**
 * Template Name: Maintenance page
 *
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

remove_all_actions( 'etheme_header' );
remove_all_actions( 'etheme_header_mobile' );
remove_all_actions('etheme_prefooter');
remove_all_actions('etheme_footer');
remove_action( 'after_page_wrapper', 'etheme_mobile_panel', 1 );
remove_action('after_page_wrapper', 'etheme_btt_button', 30);
remove_action('et_after_body', 'etheme_bordered_layout');
remove_action('after_page_wrapper', 'etheme_photoswipe_template', 30);
remove_action('after_page_wrapper', 'et_notify', 40);
remove_action('after_page_wrapper', 'et_buffer', 40);

add_action( 'wp_enqueue_scripts', function (){
    // disable all styles
    $styles_remove = array(
        'back-top',
        'mobile-panel',
        'header-vertical',
        'header-search'
    );
    foreach ($styles_remove as $script){
	    wp_dequeue_style($script);
    }
	$scripts_remove = array(
        'fixed-header',
		'back-top',
        'mobile_panel',
        
	);
    // disable all scripts
    foreach ($scripts_remove as $script){
	    wp_deregister_script($script);
    }
	etheme_enqueue_style('maintenance-page' );
}, 3000);

get_header();

?>

<div class="container content-page">
        <div class="row">

            <div class="content">
                <?php if(have_posts()): while(have_posts()) : the_post(); ?>

                    <?php the_content(); ?>

                <?php endwhile; else: ?>

                    <h3><?php esc_html_e('No pages were found!', 'xstore') ?></h3>

                <?php endif; ?>

            </div>

        </div><!-- end row-fluid -->

    </div>
</div><!-- end container -->

<?php
get_footer();
?>
