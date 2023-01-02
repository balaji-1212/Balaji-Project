<?php
if (class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_vc_carousel_parent extends WPBakeryShortCodesContainer { 

	}
}

$animation_array = array(
	'No Animation' => 'no-animation',
	'slideInUp' => 'slideInUp',
	'slideInDown' => 'slideInDown',
	'slideInLeft' => 'slideInLeft',
	'slideInRight' => 'slideInRight',
	'slideOutUp' => 'slideOutUp',
	'slideOutDown' => 'slideOutDown',
	'slideOutLeft' => 'slideOutLeft',
	'slideOutRight' => 'slideOutRight',
	'flip' => 'flip',
	'flipInX' => 'flipInX',
	'flipInY' => 'flipInY',
	'flipOutX' => 'flipOutX',
	'flipOutY' => 'flipOutY',
	'bounceIn' => 'bounceIn',
	'bounceInDown' => 'bounceInDown',
	'bounceInUp' => 'bounceInUp',
	'bounceOut' => 'bounceOut',
	'bounceOutDown' => 'bounceOutDown',
	'bounceOutLeft' => 'bounceOutLeft',
	'bounceOutRight' => 'bounceOutRight',
	'bounceOutUp' => 'bounceOutUp',
	'lightSpeedIn' => 'lightSpeedIn',
	'lightSpeedOut' => 'lightSpeedOut',	
	'rotateIn' => 'rotateIn',
	'rotateInDownLeft' => 'rotateInDownLeft',
	'rotateInDownRight' => 'rotateInDownRight',
	'rotateInUpLeft' => 'rotateInUpLeft',
	'rotateInUpRight' => 'rotateInUpRight',
	'rotateOut' => 'rotateOut',
	'rotateOutDownLeft' => 'rotateOutDownLeft',
	'rotateOutDownRight' => 'rotateOutDownRight',
	'rotateOutUpLeft' => 'rotateOutUpLeft',
	'rotateOutUpRight' => 'rotateOutUpRight',
);
if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "vc_carousel_parent",
		"name" 			=> __( 'Carousel Anything', 'wdo-carousel' ),
		"as_parent" 	=> array('only' => 'vc_carousel_child,vc_carousel_video,vc_carousel_post,vc_carousel_testimonial,vc_carousel_image_over_image'),
		"content_element" => true,
		"js_view" 		=> 'VcColumnView',
		"category" 		=> __('by labibahmed'),
		"description" 	=> __('Carousel anything', ''),
		"icon" => plugin_dir_url( __FILE__ ).'../icons/carousel-parent-copy.png',
		'params' => array(
			array(
				"type"             => "text",
				"param_name"       => "wdo_title_text_typography",
				"heading"          => "<b>" . __( "Slides to Show‏", "wdo-carousel" ) . "</b>", 
				"value"            => "",
				"edit_field_class" => "vc_col-sm-12 wdo_margin_top",
				"group"            => "General"
			),
			array(
				"type"             => "textfield",
				"class"            => "",
				"edit_field_class" => "vc_col-sm-4 wdo_items_to_show wdo_margin_bottom",
				"heading"          => __( "On Desktop", "wdo-carousel" ),
				"param_name"       => "wdo_slides_on_desk",
				"value"            => "5",
				"min"              => "1",
				"max"              => "25",
				"step"             => "1",
				"group"            => "General",
			),
			array(
				"type"             => "textfield",
				"class"            => "",
				"edit_field_class" => "vc_col-sm-4 wdo_items_to_show wdo_margin_bottom",
				"heading"          => __( "On Tabs", "wdo-carousel" ),
				"param_name"       => "wdo_slides_on_tabs",
				"value"            => "3",
				"min"              => "1",
				"max"              => "25",
				"step"             => "1",
				"group"            => "General",
			),
			array(
				"type"             => "textfield",
				"class"            => "",
				"edit_field_class" => "vc_col-sm-4 wdo_items_to_show wdo_margin_bottom",
				"heading"          => __( "On Mobile", "wdo-carousel" ),
				"param_name"       => "wdo_slides_on_mob",
				"value"            => "2",
				"min"              => "1",
				"max"              => "25",
				"step"             => "1",
				"group"            => "General",
			),
			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Slides to Scroll', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_slides_scroll",
				"description"	=>	__( 'Set number of slides to scroll when click next or back.Default: 1', 'wdo-carousel' ),
				"group" 		=> 'General',
			),
			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Infinite Loop', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_slide_loop",
				'save_always' 	=> true,
				"description"	=>	__('Restart the slider automatically as it passes the last slide', 'wdo-carousel'),
				"value" 		=> 	array(
					"Enable" 	=> 		"true",
					"Disable" 	=> 		"false",
				),
				"group" 		=> 'General',
			),
			array(
				"type" 			=> 	"textfield",
				"heading" 		=> 	__( 'Margin', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_slide_margin",
				"description"	=>	__('Set margin from right between slides.Default: 10', 'wdo-carousel'),
				"group" 		=> 'General',
			),
			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'AutoPlay Slides', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_auto_play",
				'save_always' => true,
				"value" 		=> 	array(
					"Enable" 			=> 		"true",
					"Disable" 			=> 		"false",
				),
				"group" 		=> 'AutoPlay Settings',
			),

			array(
				"type"       => "textfield",
				"heading"    => __( "AutoPlay Speed <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>", "wdo-carousel" ),
				"param_name" => "wdo_autoplay_speed",
				"description"	=>	__('Set time interval between two slides in autoplay <i>5000 = 5 seconds</i>. <strong>Default:5000</strong>', 'wdo-carousel'),
				"value"      => "5000",
				"dependency" => Array(
					"element" => "wdo_auto_play",
					"value"   => array( "true" )
				),
				"group"      => "AutoPlay Settings",
			),

			array(
				"type"       => "dropdown",
				"heading"    => __( "AutoPlay HoverPause <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>", "wdo-carousel" ),
				"param_name" => "wdo_hover_pause",
				'save_always' => true,
				"description"	=>	__('Pause autoplay when mouse hover over the slide.', 'wdo-carousel'),
				"value" 		=> 	array(
					"Enable" 			=> 		"true",
					"Disable" 			=> 		"false",
				),
				"dependency" => Array(
					"element" => "wdo_auto_play",
					"value"   => array( "true" )
				),
				"group"      => "AutoPlay Settings",
			),

			array(
				"type"       => "textfield",
				"heading"    => __( "Navigation Speed <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>", "wdo-carousel" ),
				"param_name" => "wdo_nav_speed",
				"description"	=>	__('Set speed at which transtion take place between two slides.  <i>1000 = 1 seconds</i>. <strong>Default:250</strong>', 'wdo-carousel'),
				"value"      => "250",
				"group"      => "Navigation",
			),

			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Navigation Arrows', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_nav_arrows",
				'save_always' => true,
				"value" 		=> 	array(
					"Show" 			=> 		"true",
					"Hide" 			=> 		"false",
				),
				"group" 		=> 'Navigation',
			),

			array(
				"type"       => "colorpicker",
				"heading"    => __( "Arrows Background Color", "wdo-carousel" ),
				"param_name" => "wdo_arrow_bg_color",
				"value"      => "",
				"dependency" => Array(
					"element" => "wdo_nav_arrows",
					"value"   => array( "true" )
				),
				"group"      => "Navigation",
			),

			array(
				"type"       => "colorpicker",
				"heading"    => __( "Arrows Color", "wdo-carousel" ),
				"param_name" => "wdo_arrow_color",
				"value"      => "",
				"dependency" => Array(
					"element" => "wdo_nav_arrows",
					"value"   => array( "true" )
				),
				"group"      => "Navigation",
			),

			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Navigation Dots', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_nav_dots",
				"value" 		=> 	array(
					"Show" 		=> 		"true",
					"Hide" 		=> 		"false",
				),
				"group" 		=> 'Navigation',
			),

			array(
				"type"       => "colorpicker",
				"heading"    => __( "Dots Color", "wdo-carousel" ),
				"param_name" => "wdo_dots_color",
				"value"      => "",
				"dependency" => Array(
					"element" => "wdo_nav_dots",
					"value"   => array( "true" )
				),
				"group"      => "Navigation",
			),

			array(
				"type"       => "textfield",
				"heading"    => __( "Dots Pagination Speed <strong><a href='https://1.envato.market/kXZrz'>(PRO Feature)</a></strong>", "wdo-carousel" ),
				"param_name" => "wdo_dots_speed",
				"description"	=>	__('Set speed at which transtion take place between two slides when click over pagination dots <i>1000 = 1 second</i>. <strong>Default:300</strong>', 'wdo-carousel'),
				"value"      => "300",
				"dependency" => Array(
					"element" => "wdo_nav_dots",
					"value"   => array( "true" )
				),
				"group"      => "Navigation",
			),

			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Slide In Animation', 'wdo-carousel' ),
				"description"	=>	__('Animation when next slide comes in. <strong> Will work when "Slides to show" set to 1.</strong>', 'wdo-carousel'),
				"param_name" 	=> 	"wdo_slidein_animate",
				'save_always' 	=> true,
				'value' => $animation_array,
				"group" 		=> 'Animation',
			),

			array(
				"type" 			=> 	"dropdown",
				"heading" 		=> 	__( 'Slide Out Animation', 'wdo-carousel' ),
				"param_name" 	=> 	"wdo_slideout_animate",
				"description"	=>	__('Animation when slide goes out.<strong> Will work when "Slides to show" set to 1.</strong>', 'wdo-carousel'),
				'save_always' 	=> true,
				'value' => $animation_array,
				"group" 		=> 'Animation',
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