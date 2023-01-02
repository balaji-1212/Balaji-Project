<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );

$tab_id = ( isset( $atts['tab_id'] ) ? $atts['tab_id'] : sanitize_title( $atts['title'] ) );
$image = '';
if( isset( $atts['img'] ) ) {
  $size = isset( $atts['img_size'] ) ? $atts['img_size'] : 'full';
  $image = etheme_get_image($atts['img'], $size );
}

printf(
	'<div class="accordion-title" data-id="%s">%s %s</div><div id="content_%s" class="et-tab"><div class="et-tab-content">%s</div></div>',
	$tab_id,
	$image,
	$this->getTemplateVariable( 'heading' ),
	$tab_id,
	$this->getTemplateVariable( 'content' )
);