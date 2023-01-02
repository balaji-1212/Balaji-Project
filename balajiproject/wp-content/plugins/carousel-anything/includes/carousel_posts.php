<?php
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_vc_carousel_post extends WPBakeryShortCode {
	}
}
  
if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "vc_carousel_post",
		"name" 			=> __( 'Post Carousel', 'wdo-carousel' ),
		"as_child" 		=> array('only' => 'vc_carousel_parent'),
		"description" 	=> __('Add posts in carousel.', 'wdo-carousel'),
		"icon" => plugin_dir_url( __FILE__ ).'../icons/admin-post-icon.png',
		'params' => array(
			array(
				"type" 			=> 	"loop",
				"heading" 		=> 	__( 'Select Posts/Pages', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_posts_query",
				"description"	=>	"Choose posts you want to display in carousel",
			),

			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Post Style', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_post_style",
				'save_always' 	=> true,
				"value" 		=> 	array(
					"Style 1" 	=> 		"style1",
					"Style 2" 	=> 		"style2",
					"Style 3" 	=> 		"style3",
					"Style 4 (In Pro Version)" 	=> 		"style1",
					"Style 5 (In Pro Version)" 	=> 		"style1",
					"Style 6 (In Pro Version)" 	=> 		"style1",
					"Style 7 (In Pro Version)" 	=> 		"style1",
					"Style 8 (In Pro Version)" 	=> 		"style1",
					"Style 9 (In Pro Version)" 	=> 		"style1",
					"Style 10 (In Pro Version)" 	=> 		"style1",
				)
			),

			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Title Font Size', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_title_font_size",
				"description"	=>	__('Font size for the post title. Default 17px', 'wdo-carousel'),
				"group" 		=> 'Post Content Settings',
			),

			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Description Font Size', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_desc_font_size",
				"description"	=>	__('Font size for the post description. Default 15px', 'wdo-carousel'),
				"group" 		=> 'Post Content Settings',
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Title Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_title_font_color",
				"description"	=>	__('Choose font-color for title', 'wdo-carousel'),
				"group" 		=> 'Post Content Settings',
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Hover Title Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_title_hover_color",
				"description"	=>	__('Choose color when hover on title', 'wdo-carousel'),
				"group" 		=> 'Post Content Settings',
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Description Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_description_color",
				"description"	=>	__('Choose font-color for description.', 'wdo-carousel'),
				"group" 		=> 'Post Content Settings', 
			),

			// array(
			// 	"type" 			=> 	"textfield",
			// 	"heading" 		=> 	__( 'Post Excerpt', 'wdo-carousel' ),
			// 	"param_name" 	=> 	"wdo_post_excerpt",
			// 	"description"	=>	__('Number of words want to show in post content. Default 120', 'wdo-carousel'),
			// 	"group" 		=> 'Post Content Settings',
			// ),

			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Read More Button Text', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_read_more_text",
				"value" 		=> 	"Read More",
				"description"	=>	__('Give text for the read more button.', 'wdo-carousel'),
				"group" 		=> 'Read More',
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Read More Text Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_readmore_color",
				"description"	=>	__('Color for the read more text.', 'wdo-carousel'),
				"group" 		=> 'Read More', 
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Read More Text Hover Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_readmore_hover_color",
				"description"	=>	__('Color for the read more text when hover over it.', 'wdo-carousel'),
				"group" 		=> 'Read More', 
			),



			array(
		        "type" => "html",
		        "heading" => "<h3 style='background:#2c4b7d;color:#fff;padding:20px;text-decoration:none;display:block;text-align:center;'>Your Feedback Matter to Us</h3>",
		        "param_name" => "wdo_feedback",
		        "description"	=>	"<p style='font-style:normal;'>If our plugin is helping you anyway in improving your webpage.Please don't forget to rate our plugin.It give us boost to improve our plugin.</p><h4><a style='background:#b0245a;font-style:normal;color:#fff;padding:20px;text-decoration:none;display:block;text-align:center;width:40%;margin:0 auto;border-radius:10px;' href='https://wordpress.org/support/plugin/carousel-anything/reviews/?rate=5#new-post' target='_blank' > Rate Now  <span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a></h3>",
		        "group" 		=> 'Support Us',
		    ),
		)
	) );
}
?>