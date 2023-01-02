<?php

    $title = $el_class = ''; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited valid use case
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

	etheme_enqueue_style('tabs', true);

    $el_class = $this->getExtraClass( $el_class );

    // Extract tab titles
    preg_match_all( '/et_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $tab_titles = array();

    if ( isset( $matches[1] ) ) {
      $tab_titles = $matches[1];
    }
    $tabs_nav = '';
    $tabs_nav .= '<ul class="tabs-nav">';
    foreach ( $tab_titles as $tab ) // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited valid use case
    {
      $tab_atts = shortcode_parse_atts( $tab[0] );
      if ( isset( $tab_atts['title'] ) ) {
        $tab_id = ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) );
        $image = '';
        if( isset( $tab_atts['img'] ) ) {
          $size = isset( $tab_atts['img_size'] ) ? $tab_atts['img_size'] : 'full';
          $image = etheme_get_image($tab_atts['img'], $size );
        }

        if ( $atts['title_style'] == 'hover' ) {
          $tabs_nav .= '<li>' . $image . '<a href="#' . $tab_id . '" id="' . $tab_id . '" class="tab-title"><span>' . $tab_atts['title'] . '</span></a></li>';
        } else {
          $tabs_nav .= '<li><a href="#' . $tab_id . '" id="' . $tab_id . '" class="tab-title">' . $image . '<span>' . $tab_atts['title'] . '</span></a></li>';
        }

        $tabs_nav .= ( $atts['title_style'] == 'hover' ) ? '<span class="delimiter"></span>' : '';
      }
    }
    $tabs_nav .= '</ul>';

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( ' ' . $el_class ), $this->settings['base'], $atts );

    if ( ! empty( $css_class ) ) $css_class .= ' ';

    $css_class .= 'title-' . $atts['title_style'];

    printf(
      '<div class="et-tabs-wrapper %s">
        <div class="tabs">
          %s
          <div class="tab-contents">
          %s
          </div>
        </div>
      </div>',
      $css_class,
      $tabs_nav,
      wpb_js_remove_wpautop( $content )
    );