<?php
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_vc_carousel_testimonial extends WPBakeryShortCode {
	}
}

if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "vc_carousel_testimonial",
		"name" 			=> __( 'Add testimonial', 'wdo-carousel' ),
		"as_child" 		=> array('only' => 'vc_carousel_parent'),
		"description" 	=> __('Add testimonial slide.', 'wdo-carousel'),
		"icon" => plugin_dir_url( __FILE__ ).'../icons/admin-icon-testimonial.png',
		'params' => array(
			array(
				"type"             => "attach_image",
				"heading"          => __( "Author Avatar", "wdo-carousel" ),
				"description"	=>	__('Add author photo', 'wdo-carousel'),
				"param_name"       => "wdo_author_avatar",
			),

			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Author Name', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_author_name",
				"description"	=>	__( 'Enter the author name', 'wdo-carousel' ),
			),

			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Author Postion <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_author_postion",
				"description"	=>	__( 'Enter postion of author e.g webdesigner', 'wdo-carousel' ),
			),

			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Author Website Link <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_author_link",
				"description"	=>	__( 'Enter full URL of the authors website', 'wdo-carousel' ),
			),

			array(
				"type" 			=> 	"textarea",
				"heading" 		=> 	__( 'Testimonial Text', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_test_text",
				"description"	=>	__( 'Enter the testimonial text.', 'wdo-carousel' ),
			),

			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Testimonial Style', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_testimonial_style",
				'save_always' 	=> true,
				"group" 		=> 'Styles',
				"value" 		=> 	array(
					"Style 1" 	=> 		"style1",
					"Style 2" 	=> 		"style2",
					"Style 3" 	=> 		"style3",
					"Style 4 (In Pro Version)" 	=> 		"style1",
					"Style 5 (In Pro Version)" 	=> 		"style1",
					"Style 6 (In Pro Version)" 	=> 		"style1",
				)
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Author Name Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_name_color",
				"description"	=>	__( 'Choose color for author name.', 'wdo-carousel' ),
				"group" 		=> 'Colors',
			),

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Author Postion Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_post_color",
				"description"	=>	__( 'Choose color for author position.', 'wdo-carousel' ),
				"group" 		=> 'Colors',
			), 

			array(
				"type" 			=> 	"colorpicker",
				"heading" 		=> 	__( 'Testimonial Text Color <strong><a href="https://1.envato.market/kXZrz">(PRO Feature)</a></strong>', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_text_color",
				"description"	=>	__( 'Choose color for testimonial text.', 'wdo-carousel' ),
				"group" 		=> 'Colors',
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