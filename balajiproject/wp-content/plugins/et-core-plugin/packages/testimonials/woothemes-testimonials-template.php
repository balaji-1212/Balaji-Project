<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'woothemes_get_testimonials' ) ) {
/**
 * Wrapper function to get the testimonials from the WooDojo_Testimonials class.
 * @param  string/array $args  Arguments.
 * @since  1.0.0
 * @return array/boolean       Array if true, boolean if false.
 */
function woothemes_get_testimonials ( $args = '' ) {
	global $woothemes_testimonials;
	return $woothemes_testimonials->get_testimonials( $args );
} // End woothemes_get_testimonials()
}

/**
 * Enable the usage of do_action( 'woothemes_testimonials' ) to display testimonials within a theme/plugin.
 *
 * @since  1.0.0
 */
add_action( 'woothemes_testimonials', 'woothemes_testimonials' );

if ( ! function_exists( 'woothemes_testimonials' ) ) {
/**
 * Display or return HTML-formatted testimonials.
 * @param  string/array $args  Arguments.
 * @since  1.0.0
 * @return string
 */
function woothemes_testimonials ( $args = '' ) {
	global $post;

	$defaults = array(
		'limit' => 5,
		'orderby' => 'menu_order',
		'interval' => 10000,
		'order' => 'DESC',
		'id' => 0,
		'display_author' => true,
		'display_avatar' => true,
		'color_scheme' => 'dark',
		'display_url' => true,
		'pagination' => false,
		'echo' => true,
		'size' => 30,
		'title' => '',
		'before' => '',
		'after' => '',
		'before_title' => '',
		'after_title' => '',
		'type' => 'slider',
		'slider_columns' => 2,
		'auto_slide' => 0,
		'columns' => 3,
		'navigation' => false,
		'category' => 0
	);
	
	$img_size = 40;

	$args = wp_parse_args( $args, $defaults );

	// Allow child themes/plugins to filter here.
	$args = apply_filters( 'woothemes_testimonials_args', $args );
	$html = '';

	do_action( 'woothemes_testimonials_before', $args );
		// The Query.
		$query = woothemes_get_testimonials( $args );

    	$attr = '';
    	$class = '';
		if($args['type'] == 'slider' || $args['type'] == 'slider-grid') {
			if( $args['type'] == 'slider-grid' ) {
				$class .= ' with-grid';
            }
		} elseif($args['type'] == 'grid') {
			$img_size = 60;
			$class = 'testimonial-grid';
		}
		$auto_play = 0;
		if ($args['interval'] != 0) {
            $auto_play = 1;
		}

		$class .= ' testimonials-color-' . $args['color_scheme'];
		$class .= ' testimonials-col-' . $args['slider_columns'];

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			
			// only for wpb
			if ( function_exists('etheme_enqueue_style'))
				etheme_enqueue_style( 'wpb-testimonials' );

			$html .= $args['before'] . "\n";
			if ( '' != $args['title'] ) {
				$html .= $args['before_title'] . esc_html( $args['title'] ) . $args['after_title'] . "\n";
			}
            $html .='<div class="swiper-entry testimonials-slider">';
			if ( $args['type'] == 'slider' ){
				$html .= '<div id="owl-testimonials" class="swiper-container '.$class.' stop-on-hover testimonials" data-autoplay="'.(($args['interval']) ? $args['interval'] : "false").'"> '."\n";
            } elseif ( $args['type'] == 'slider-grid' ){
                $slidesPerColumn = ($args['slider_columns'] == 1) ? count( $query ) : 2;
                $html .= '<div id="owl-testimonials" class="swiper-container '.$class.' stop-on-hover testimonials" data-autoplay="'.(($args['interval']) ? $args['interval'] : "false").'" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1" data-md-slides="1" data-lt-slides="2" data-slides-per-view="'.esc_attr($args['slider_columns']).'" data-slidesPerColumn="'.$slidesPerColumn.'"> '."\n";
			}
            $html .= '<div class="swiper-wrapper">' . "\n";

			// Begin templating logic.

			if($args['type'] == 'slider' || $args['type'] == 'slider-grid') {
				$tpl = '<div id="quote-%%ID%%" class="item swiper-slide %%CLASS%%"><div class="testimonial-slider-item"><div class="testimonial-info"> %%AVATAR%% <div class="testimonial-author">%%AUTHOR%%</div></div> <blockquote class="testimonials-text">%%TEXT%%</blockquote> <div class="clear"></div></div></div>';
			} elseif($args['type'] == 'grid') {
				$tpl = '<div id="quote-%%ID%%" class="item col-lg-4 %%CLASS%%"><div class="testimonial-info">%%AVATAR%% <div class="testimonial-author">%%AUTHOR%%</div></div><blockquote class="testimonials-text">%%TEXT%%</blockquote><div class="clear"></div></div>';
			}
			$tpl = apply_filters( 'woothemes_testimonials_item_template', $tpl, $args );

			$count = 0;
			$per_slide = 2 * $args['slider_columns'];

			if ($args['type'] == 'grid') {
				$html .= '<div class="row">';
			}

			foreach ( $query as $post ) { $count++;

				$template = $tpl;

				$css_class = 'quote';
				if ( 1 == $count ) { $css_class .= ' first'; }
				if ( count( $query ) == $count ) { $css_class .= ' last'; }

				setup_postdata( $post );

				$author = '';
				$author_text = '';

				// If we need to display the author, get the data.
				if ( ( get_the_title( $post ) != '' ) && true == $args['display_author'] ) {
					$author .= '<cite class="author">';

					$author_name = get_the_title( $post );

					$author .= '<span class="author-name">'.$author_name.'</span>';

					if ( isset( $post->byline ) && '' != $post->byline ) {
						$author .= ' <span class="excerpt">' . $post->byline . '</span><!--/.excerpt-->' . "\n";
					}

					if ( true == $args['display_url'] && '' != $post->url ) {
						$author .= ' <span class="url"><a href="' . esc_url( $post->url ) . '">' . $post->url . '</a></span><!--/.excerpt-->' . "\n";
					}

					$author .= '</cite><!--/.author-->' . "\n";

					// Templating engine replacement.
					$template = str_replace( '%%AUTHOR%%', $author, $template );
				} else {
					$template = str_replace( '%%AUTHOR%%', '', $template );
				}

				// Templating logic replacement.
				$template = str_replace( '%%ID%%', get_the_ID(), $template );
				$template = str_replace( '%%CLASS%%', esc_attr( $css_class ), $template );

				if ( isset( $post->image ) && ( '' != $post->image ) && true == $args['display_avatar'] ) {
					$image = get_the_post_thumbnail( $post->ID, 'thumbnail' );
					$template = str_replace( '%%AVATAR%%', '<a class="avatar-link">' . $image . '</a>', $template );
				} else {
					$template = str_replace( '%%AVATAR%%', '', $template );
				}

				// Remove any remaining %%AVATAR%% template tags.
				$template = str_replace( '%%AVATAR%%', '', $template );
				
				$content = apply_filters( 'woothemes_testimonials_content', get_the_content(), $post );
				$template = str_replace( '%%TEXT%%', $content, $template );

				// Assign for output.
				$html .= $template;
				
				if ($count%$args['columns'] == 0 && count( $query ) != $count && $args['type'] == 'grid') {
					$html .= '<div class="clear"></div></div><div class="row">';
				}

			}
			if ($args['type'] == 'grid') {
				$html .= '<div class="clear"></div></div><!--row-->';
			}


			wp_reset_postdata();

            $html .= '</div><!--/.testimonials wrapper-->' . "\n";

            if ( count( $query ) > 1 ) {
                $html .= '<div class="swiper-pagination"></div>' . "\n";
            }
			$html .= '</div><!--/.testimonials container-->' . "\n";

            if ( $args['navigation'] ) {
                $html .= '<div class="swiper-custom-left swiper-nav"></div>';
                $html .= '<div class="swiper-custom-right swiper-nav"></div>';
            }

            $html .= '</div><!--/.testimonials wrapper entry -->' . "\n";
			$html .= $args['after'] . "\n";
		}

		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'woothemes_testimonials_html', $html, $query, $args );

		if ( $args['echo'] != true ) { return $html; }

		// Should only run is "echo" is set to true.
		echo $html;

		do_action( 'woothemes_testimonials_after', $args ); // Only if "echo" is set to true.
} // End woothemes_testimonials()
}

if ( ! function_exists( 'woothemes_testimonials_shortcode' ) ) {
/**
 * The shortcode function.
 * @since  1.0.0
 * @param  array  $atts    Shortcode attributes.
 * @param  string $content If the shortcode is a wrapper, this is the content being wrapped.
 * @return string          Output using the template tag.
 */
function woothemes_testimonials_shortcode ( $atts, $content = null ) {
	$args = (array)$atts;

	$defaults = array(
		'limit' => 5,
		'orderby' => 'menu_order',
		'order' => 'DESC',
		'interval' => 10000,
		'id' => 0,
		'display_author' => true,
		'display_avatar' => true,
		'color_scheme' => 'dark',
		'display_url' => true,
		'effect' => 'fade', // Options: 'fade', 'none'
		'pagination' => false,
		'echo' => true,
		'size' => 50,
		'type' => 'slider',
		'slider_columns' => 2,
		'auto_slide' => 1,
		'navigation' => false,
		'category' => 0
	);

	$args = shortcode_atts( $defaults, $atts );

	// Make sure we return and don't echo.
	$args['echo'] = false;

	// Fix integers.
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );
	if ( isset( $args['id'] ) ) $args['id'] = intval( $args['id'] );
	if ( isset( $args['size'] ) &&  ( 0 < intval( $args['size'] ) ) ) $args['size'] = intval( $args['size'] );
	if ( isset( $args['category'] ) && is_numeric( $args['category'] ) ) $args['category'] = intval( $args['category'] );

	// Fix booleans.
	foreach ( array( 'display_author', 'display_url', 'pagination', 'display_avatar' ) as $k => $v ) {
		if ( isset( $args[$v] ) && ( 'true' == $args[$v] ) ) {
			$args[$v] = true;
		} else {
			$args[$v] = false;
		}
	}

	return woothemes_testimonials( $args );
} // End woothemes_testimonials_shortcode()
}

add_shortcode( 'testimonials', 'woothemes_testimonials_shortcode' );

if ( ! function_exists( 'woothemes_testimonials_content_default_filters' ) ) {
/**
 * Adds default filters to the "woothemes_testimonials_content" filter point.
 * @since  1.3.0
 * @return void
 */
function woothemes_testimonials_content_default_filters () {
	add_filter( 'woothemes_testimonials_content', 'do_shortcode' );
} // End woothemes_testimonials_content_default_filters()

add_action( 'woothemes_testimonials_before', 'woothemes_testimonials_content_default_filters' );
}
?>
