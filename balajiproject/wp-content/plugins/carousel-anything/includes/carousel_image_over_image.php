<?php
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_vc_carousel_image_over_image extends WPBakeryShortCode {
	}
}

$image_effects = array(
	'Select Effect'=>	'style1',
	'Style 1'	=>	'style1',
	'Style 2'	=>	'style2',
	'Style 3'	=>	'style3',
	'Style 4'	=>	'style4',
	'Style 5'	=>	'style5',
	'Style 6'	=>	'style6',
	'Style 7'	=>	'style7',
	'Style 8'	=>	'style8',
	'Style 9'	=>	'style9',
	'Style 10'	=>	'style10',
);

if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "vc_carousel_image_over_image", 
		"name" 			=> __( 'Image Over Image', 'wdo-carousel' ),
		"as_child" 		=> array('only' => 'vc_carousel_parent'),
		"description" 	=> __('Add image over image effect.', 'wdo-carousel'),
		"icon" => plugin_dir_url( __FILE__ ).'../icons/admin-icon-ioi.png',
		'params' => array(
			array(
				"type" 			=> 	"attach_image",
				"heading" 		=> 	__("Front Image"),
				"param_name" 	=> 	"wdo_front_image",
				"description" 	=> 	__("Select front image"),
			),

			array(
				"type" 			=> 	"attach_image",
				"heading" 		=> 	__("Image after Hover"),
				"param_name" 	=> 	"wdo_back_image",
				"description" 	=> 	__("Select image to show on hover"),
			),

			array(
				"type" 			=> "textfield",
				"heading" 		=> __("URL"),
				"param_name" 	=> "wdo_caption_url",
				"description" 	=> __("Leave blank to disable link"),
			),
			array(
				"type" 			=> "textfield", 
				"heading" 		=> __("Link Target"),
				"param_name" 	=> "wdo_url_target",
				"description" 	=> __("Write _blank for opening link in new window and _self for same window."),
			),

			

			// Hover Effects Settings

			array(
				"type" 			=> "dropdown",
				"heading" 		=> __("Image Change Effect"),
				"param_name" 	=> "wdo_image_effect",
				'save_always'   => true,
				"description" 	=> __("Select effect when image changes."),
				"group"         => "Styles",
				"value" 		=> $image_effects,
			),

			array(
				"type" 			=> "textfield", 
				"heading" 		=> __("Image Width <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>"),
				"param_name" 	=> "wdo_image_width",
				"description" 	=> __("Give width for the image use 'px' with the value."),
				"group"         => "Styling",
			),

			array(
				"type" 			=> "textfield", 
				"heading" 		=> __("Image Height <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>"),
				"param_name" 	=> "wdo_image_height",
				"description" 	=> __("Give height for the image use 'px' with the value."),
				"group"         => "Styling",
			),

			array(
				"type" 			=> "textfield", 
				"heading" 		=> __("Border Width <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>"),
				"param_name" 	=> "wdo_image_border",
				"description" 	=> __("Give border for the image use 'px' with the value."),
				"group"         => "Styling",
			),

			array(
				"type" 			=> "colorpicker", 
				"heading" 		=> __("Border Color <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>"),
				"param_name" 	=> "wdo_image_border_color",
				"group"         => "Styling",
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