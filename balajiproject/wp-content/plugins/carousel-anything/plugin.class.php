<?php 
class WDO_Carousel_Class {

	function __construct() {
		add_action( 'init', array($this, 'wdo_carousel_init' ));
		add_action( "admin_enqueue_scripts", array( $this, "custom_param_styles" ) );
		add_shortcode('vc_carousel_parent', array($this, 'wdo_carousel_parent_render'));
		add_shortcode('vc_carousel_child', array($this, 'wdo_carousel_child_render'));
		add_shortcode('vc_carousel_image_over_image', array($this, 'wdo_carousel_image_over_image_render'));
		add_shortcode('vc_carousel_video', array($this, 'wdo_carousel_video_render'));
		add_shortcode('vc_carousel_post', array($this, 'wdo_carousel_post_render'));
		add_shortcode('vc_carousel_testimonial', array($this, 'wdo_carousel_testimonial_render'));
		add_action( 'init', array( $this, 'check_if_vc_is_install' ) );
	}

		
	function wdo_carousel_init() {

		include 'includes/carousel_parent.php';
		include 'includes/carousel_child.php';
		include 'includes/carousel_video.php';
		include 'includes/carousel_posts.php';
		include 'includes/carousel_testimonials.php';
		include 'includes/carousel_image_over_image.php'; 
	}

	function wdo_carousel_parent_render($atts, $content = null, $tag) {
		wp_enqueue_style( 'wdo-carousel-css', plugin_dir_url( __FILE__ ).'assets/css/owl.carousel.min.css' );
		wp_enqueue_style( 'wdo-owl-theme', plugin_dir_url( __FILE__ ).'assets/css/owl.theme.green.css' );
		wp_enqueue_style( 'wdo-font-awesome', plugin_dir_url( __FILE__ ).'assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'wdo-animate-css', plugin_dir_url( __FILE__ ).'assets/css/animate.css' );
		wp_enqueue_script( 'wdo-carousel-js', plugins_url( 'assets/js/owl.carousel.min.js' , __FILE__ ), array('jquery')); 

        $args = array(
        	'wdo_slides_on_desk'		=>		'5',
        	'wdo_slides_on_tabs'		=>		'3',
        	'wdo_slides_on_mob'			=>		'2',
        	'wdo_slides_scroll'			=>		'1', 
        	'wdo_slide_loop'			=>		'true',
        	'wdo_slide_margin'			=>		'10',
        	'wdo_auto_play'				=>		'true',
        	'wdo_nav_dots'				=>		'true',
        	'wdo_nav_arrows'			=>		'false', 
        	'wdo_arrow_bg_color'		=>		'',
        	'wdo_arrow_color'			=>		'',
        	'wdo_dots_color'			=>		'',
        	'wdo_slidein_animate'		=>		'',
        	'wdo_slideout_animate'		=>		'',
        );

        $params  = shortcode_atts($args, $atts);

        extract($params);

        $unique_id = rand(5, 500);

        ob_start(); ?>

        <style>
        	.unique-carousel-<?php echo $unique_id; ?> .owl-next, .unique-carousel-<?php echo $unique_id; ?> .owl-prev{
        		background: <?php echo $wdo_arrow_bg_color; ?>  !important;
        		color: <?php echo $wdo_arrow_color; ?> !important;
        	}

        	.unique-carousel-<?php echo $unique_id; ?>.owl-theme .owl-dots .owl-dot.active span, .unique-carousel-<?php echo $unique_id; ?>.owl-theme .owl-dots .owl-dot:hover span{
        		background: <?php echo $wdo_dots_color; ?> !important;
        	}

        	.owl-next, .owl-prev{
        		top: 40%;
        	}
        </style>
        <div class="wdo-carosuel-container unique-carousel-<?php echo $unique_id; ?> owl-carousel owl-theme">
        	<?php do_shortcode( $content ); ?>
        </div>
        <script type="text/javascript">
        	jQuery(document).ready(function ($) {
        		$('.unique-carousel-<?php echo $unique_id; ?>').owlCarousel({
        		    margin:<?php echo $wdo_slide_margin; ?>,
        		    touchDrag:true,
        		    mouseDrag:true,
        		    animateIn: <?php echo ( isset($wdo_slidein_animate) && $wdo_slidein_animate !='' ) ? '"'.$wdo_slidein_animate.'"' : '""' ; ?>,
        		    animateOut: <?php echo ( isset($wdo_slideout_animate) && $wdo_slideout_animate !='' ) ? '"'.$wdo_slideout_animate.'"' : '""' ; ?>,
        		    video:true,
        		    videoWidth:true,
        		    videoHeight:300,
        		    loop:<?php echo $wdo_slide_loop; ?>,
        		    slideBy:<?php echo $wdo_slides_scroll; ?>,
        		    autoplay:<?php echo $wdo_auto_play; ?>, 
        		    nav:<?php echo $wdo_nav_arrows; ?>,
        		    dots:<?php echo $wdo_nav_dots; ?>,
        		    navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
        		    responsive:{
        		        0:{
        		            items:<?php echo $wdo_slides_on_mob; ?>
        		        },
        		        600:{
        		            items:<?php echo $wdo_slides_on_tabs; ?>
        		        },
        		        1000:{
        		            items:<?php echo $wdo_slides_on_desk; ?>
        		        }
        		    }
        		});
        	});
        </script>

        <?php return ob_get_clean();
	}

	function wdo_carousel_child_render($atts, $content = null, $tag) {
		extract( shortcode_atts( array(
			), $atts ) );
		?>
			
		<div class="item">
			<?php echo do_shortcode( $content ); ?>
		</div>

		<?php
	}

	function wdo_carousel_image_over_image_render($atts, $content = null, $tag) {
		extract( shortcode_atts( array(
			'wdo_front_image'					=> "",
		    "wdo_back_image"					=> '',
		    "wdo_caption_url"					=> '',
		    "wdo_url_target"					=> '',
		    "wdo_image_effect"					=> '',
		), $atts ) );

		if (isset($wdo_front_image) &&  $wdo_front_image != '') {
			$front_image_url = wp_get_attachment_url( $wdo_front_image );		
		}
		if (isset($wdo_back_image) &&  $wdo_back_image != '') {
			$back_image_url = wp_get_attachment_url( $wdo_back_image );		
		}
		wp_enqueue_style( 'wdo-styles-css', plugins_url( 'assets/css/ioi.css' , __FILE__ ));
		?>
		<div class="item">
			<div class="ioi-container">
				<div class="ioi-<?php echo $wdo_image_effect; ?>">
				    <a class="he-box" href="<?php echo $wdo_caption_url; ?>" target="<?php echo $wdo_url_target; ?>">
				        <div class="box-img">
				        	<?php if ($wdo_image_effect == 'style7'): ?>
				        		<span class="he-over-layer">
                                    <img src="<?php echo $back_image_url; ?> " alt="">
                                </span>
				        	<?php endif ?>
				        	<?php if ( $wdo_image_effect == 'style8' ): ?>
				        		<span class="he-over-layer"></span>
				        	<?php endif ?>

				            <img src="<?php echo $front_image_url; ?> " alt="">

				        	<?php if ( $wdo_image_effect == 'style9' ): ?>
				        		<span class="he-over-layer"></span>
				        	<?php endif ?>
				        </div>
				        <div class="he-content">
				            <img src="<?php echo $back_image_url; ?> " alt="">
				        </div>
				    </a>
				</div>
			</div>
		</div>
		<?php 
	}

	function wdo_carousel_video_render($atts, $content = null, $tag) {
		extract( shortcode_atts( array(
				'wdo_video_url' =>	'',
			), $atts ) );
			?>
			<div class="item-video">
			  <a class="owl-video" href="<?php echo $wdo_video_url; ?>"></a> 
			</div>

		<?php
	}

	function wdo_carousel_post_render($atts, $content = null, $tag) {
		extract( shortcode_atts( array(
			'wdo_posts_query' 		=>	'',
			'wdo_post_style' 		=>	'style1',
			'wdo_title_font_size' 	=>	'17px',
			'wdo_desc_font_size' 	=>	'15px',
			'wdo_read_more_text' 	=>	'Read More',
		), $atts ) );
		wp_enqueue_style( 'wdo-post-css', plugins_url( 'assets/css/wdo-posts.css' , __FILE__ ));
		$wdo_post_query_array = explode('|', $wdo_posts_query);
		$query_args = array();

		foreach ($wdo_post_query_array as $wdo_query_param) {
			$query_params = explode(':', $wdo_query_param);

			if ($query_params[0] == 'size') {
				$query_args['posts_per_page'] = $query_params[1];
			} elseif($query_params[0] == 'categories') {
				$query_args['category__in'] = explode(',', $query_params[1]);
			} else {
				$query_args[$query_params[0]] = $query_params[1];
			}
		}
		$selected_style = dirname(__FILE__).'/includes/post-templates/'.$wdo_post_style.'.php';
		$new_query = new WP_Query( $query_args );
		if ( $new_query->have_posts() ) { ?>
		
			<?php while ( $new_query->have_posts() ) : $new_query->the_post(); ?>
				<div class="item">
					<?php include $selected_style; ?>
				</div>
			<?php endwhile; ?>
		
		<?php } ?>
		<?php
	}

	function wdo_carousel_testimonial_render($atts, $content = null, $tag) {
		extract( shortcode_atts( array(
			'wdo_author_avatar' =>	'',
			'wdo_author_name' =>	'',
			'wdo_test_text' =>	'',
			'wdo_testimonial_style' =>	'style1',
		), $atts ) );

		if (isset($wdo_author_avatar) &&  $wdo_author_avatar != '') {
			$avatar_url = wp_get_attachment_url( $wdo_author_avatar );		
		}
		wp_enqueue_style( 'wdo-testimonial-css', plugins_url( 'assets/css/wdo-testimonial.css' , __FILE__ ));
		$selected_style =dirname(__FILE__).'/includes/testimonial-templates/'.$wdo_testimonial_style.'.php';
		?>
		<div class="item">
			<?php include $selected_style; ?>
		</div>
		<?php 
	}

	function custom_param_styles() {
		echo '<style type="text/css">
				.wdo_items_to_show.vc_shortcode-param {
					background: #E6E6E6;
					padding-bottom: 10px;
				}
				.wdo_items_to_show.wdo_margin_bottom{
					margin-bottom: 15px;
				}
				.wdo_items_to_show.wdo_margin_top{
					margin-top: 15px;
				}
			</style>';
	}

	function check_if_vc_is_install(){
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }			
	}

	function showVcVersionNotice() { 
	    $plugin_name = 'Carousel Anything';
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="https://1.envato.market/93O7W" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'wdo-carousel'), $plugin_name).'</p>
        </div>';
	}

}
?>