<?php
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_vc_carousel_video extends WPBakeryShortCode {
	}
}

if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "vc_carousel_video",
		"name" 			=> __( 'Add Video Slide', 'wdo-carousel' ),
		"as_child" 		=> array('only' => 'vc_carousel_parent'),
		"description" 	=> __('Add video slide to carousel.', 'wdo-carousel'),
		"icon" => plugin_dir_url( __FILE__ ).'../icons/admin-video-icon2.png',
		'params' => array(
			array(
				"type"             => "textfield",
				"heading"          => __( "Video URL", "wdo-carousel" ),
				"description"	=>	__('Give complete url for YouTube/Vimeo/Vzaar videos', 'wdo-carousel'),
				"param_name"       => "wdo_video_url",
				"value"            => "",
			),
		)
	) );
}
?>