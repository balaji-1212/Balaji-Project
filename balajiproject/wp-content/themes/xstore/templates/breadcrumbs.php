<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme breadcrumbs
 *
 * @since   6.4.5
 * @version 1.0.0
 */

global $post;

    if( function_exists( 'is_bbpress' ) && is_bbpress() ) {
        bbp_breadcrumb();
        return;
    }

    $args = array(
        'delimiter'   => '<span class="delimeter"><i class="et-icon et-'.(get_query_var('et_is-rtl', false) ? 'left' : 'right' ).'-arrow"></i></span>',
        'home'        => esc_html__( 'Home', 'xstore' ),
        'showCurrent' => 0,
        'before'      => '<span class="current">',
        'after'       => '</span>',
	    'title_tag' => get_theme_mod('breadcrumb_title_tag', 'h1'),
	    'category_title_tag' =>  get_theme_mod('breadcrumb_category_title_tag', 'h1'),
    );
    
    if ( !$args['title_tag'] ){
	    $args['title_tag'] = 'h1';
    }

	if ( !$args['category_title_tag'] ){
		$args['category_title_tag'] = 'h1';
	}

    $post_page    = get_option( 'page_for_posts' );
    $title_at_end = '<a href="' . get_permalink( $post_page ) . '">' . esc_html__( 'Blog', 'xstore' ) . '</a>';
    $homeLink     = home_url();
    $xstore_title = '';
    $html         = '';

    if( is_home() ) {
        if( empty( $post_page ) && ! get_query_var('et_is-single', false) && ! is_page() ) $xstore_title = esc_html__( 'Blog', 'xstore' );
        $xstore_title = get_the_title( $post_page );
    }

    if ( is_front_page() ) {
        $xstore_title = '';
    } else if ( class_exists( 'bbPress' ) && is_bbpress() ) {
        $xstore_title    = esc_html__( 'Forums', 'xstore' );
        $bbp_args = array(
            'before' => '<div class="breadcrumbs" id="breadcrumb">',
            'after'  => '</div>'
        );
        bbp_breadcrumb($bbp_args);
    } else {
        $html .= '<div class="breadcrumbs">';
        $html .= '<div id="breadcrumb">';
        $html .= '<a href="' . $homeLink . '">' . $args['home'] . '</a> ' . $args['delimiter'] . ' ';

        if ( is_category() ) {
	        $args['title_tag'] = $args['category_title_tag'];
            $xstore_title = esc_html__( 'Category: ', 'xstore' ) . single_cat_title( '', false );
            $title_at_end = '';
            $thisCat      = get_category( get_query_var( 'cat' ), false );
            $cat_id       = get_cat_ID( single_cat_title( '', false ) );

            if ( $thisCat->parent != 0 ){
                $html .= get_category_parents( $thisCat->parent, true, ' ' . $args['delimiter'] . ' ' );
            }

            $html .= sprintf(
                '<a class="current" href="%s">%s%s "%s"%s</a>',
                get_category_link( $cat_id ),
                $args['before'],
                esc_html__( 'Archive by category', 'xstore' ),
                single_cat_title( '', false ),
                $args['after']
            );
        } elseif ( is_search() ) {
            $xstore_title = esc_html__( 'Search Results for: ', 'xstore' ) . get_search_query();
        } elseif ( is_day() ) {
            $xstore_title = esc_html__( 'Daily Archives: ', 'xstore' ) . get_the_date();
            $title_at_end = '';

            $html .= sprintf(
                '<a href="%s">%s</a> %s',
                get_year_link( get_the_time( 'Y' ) ),
                get_the_time( 'Y' ),
                $args['delimiter']
            );
            $html .= sprintf(
                '<a href="%s">%s</a> %s',
                get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                get_the_time( 'F' ),
                $args['delimiter']
            );
            $html .= sprintf(
                '<a class="current" href="%s">%s%s%s</a>',
                get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'j' ) ),
                $args['before'],
                get_the_time( 'd' ),
                $args['after']
            );
        } elseif ( is_month() ) {
            $xstore_title = esc_html__( 'Monthly Archives: ', 'xstore') . get_the_date( _x( 'F Y', 'monthly archives date format', 'xstore' ) );
            $title_at_end = '';

            $html .= sprintf(
                '<a href="%s">%s</a> %s',
                get_year_link( get_the_time( 'Y' ) ),
                get_the_time( 'Y' ),
                $args['delimiter']
            );
            $html .= sprintf(
                '<a class="current" href="%s">%s%s%s</a>',
                get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                $args['before'],
                get_the_time( 'F' ),
                $args['after']
            );
        } elseif ( is_year() ) {
            $xstore_title = esc_html__( 'Yearly Archives: ', 'xstore' ) . get_the_date( _x( 'Y', 'yearly archives date format', 'xstore' ) );
            $title_at_end = '';

            $html .= sprintf(
                '<a class="current" href="%s">%s%s%s</a>',
                get_year_link( get_the_time( 'Y' ) ),
                $args['before'],
                get_the_time( 'Y' ),
                $args['after']
            );
        } elseif ( get_query_var('et_is-single', false) && ! is_attachment() ) {
            $xstore_title = get_the_title();
            if ( get_post_type() == 'etheme_portfolio' ) {
                $portfolioId   = get_theme_mod( 'portfolio_page', '' );
                $portfolioLink = get_permalink( $portfolioId );
                $post_type     = get_post_type_object( get_post_type() );
                $page          = get_page( $portfolioId );
                $slug          = $post_type->rewrite;
                $title_at_end  = $page->post_title;

                $html .= '<a href="' . $portfolioLink . '">' . $title_at_end . '</a>';
                $title_at_end = '<a href="' . $portfolioLink . '">' . $title_at_end . '</a>';

                if ( $args['showCurrent'] == 1 ){
                    $html .= ' ' . $args['delimiter'] . ' ' . $args['before'] . get_the_title() . $args['after'];
                }
            } elseif ( get_post_type() != 'post' ) {
                $post_type    = get_post_type_object( get_post_type() );
                $slug         = $post_type->rewrite;
                $title_at_end = $post_type->labels->singular_name;
//                $title_at_end = '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $title_at_end . '</a>';
	            $href = $homeLink;
	            // fix for elementor_library post type
	            if ( is_array($slug) ) {
		            $href = $homeLink . '/' . $slug['slug'];
	            }
	            $title_at_end = '<a href="' . $href . '/">' . $title_at_end . '</a>';
	
	            $html .= '<a href="' . $href . '/">' . $title_at_end . '</a>';
	            if ( $args['showCurrent'] == 1 ){
		            $html .= ' ' . $args['delimiter'] . ' ' . $args['before'] . get_the_title() . $args['after'];
	            }
            } else {
                $cat = get_the_category();
                if( isset( $cat[0] ) ) {
                    $cat  = $cat[0];
                    $cats = get_category_parents($cat, TRUE, ' ' . $args['delimiter'] . ' ');

                    if ( $args['showCurrent'] == 0 ) {
                        $cats = preg_replace("#^(.+)\s" . $args['delimiter'] . "\s$#", "$1", $cats);
                    }
                    $html .= ' ' . $title_at_end . ' ' . $args['delimiter'] . ' ';
                    $html .= $cats;
                }
                if ( $args['showCurrent'] == 1 ) {
                    $html .= $args['before'] . get_the_title() . $args['after'];
                }
            }
        } elseif ( is_tax('portfolio_category') ) {
            $xstore_title  = single_term_title( '', false );
            $portfolioId   = get_theme_mod( 'portfolio_page', '' );
            $post          = get_page( $portfolioId );
            $portfolioLink = get_permalink($portfolioId);
            $title_at_end  = $post->post_title;

            $html .= '<a href="' . $portfolioLink . '">' . $title_at_end . '</a>' . $args['delimiter'];
            $title_at_end = '<a href="' . $portfolioLink . '">' . $title_at_end . '</a>' . $args['delimiter'];
        } elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
            $post_type    = get_post_type_object( get_post_type() );
//            $title_at_end = $post_type->labels->singular_name;
	        // code below was left after one client asked about php notices in debug log with unfound link/page but only error
	        if ( is_object($post_type) && property_exists($post_type, 'labels') ) {
		        if ( property_exists($post_type->labels, 'singular_name') ) {
			        $title_at_end = $post_type->labels->singular_name;
			        $html .= $args['before'] . $title_at_end . $args['after'];
		        }
	        }
	        
        } elseif ( is_attachment() ) {
            $parent        = get_post( $post->post_parent );
            $xstore_title  = get_the_title();

            if ( $args['showCurrent'] == 1 ) {
                $title_at_end = get_the_title();
                $html .= ' '  . $args['before'] . $title_at_end . $args['after'];
            }
        } elseif ( is_page() && ! $post->post_parent ) {
            $xstore_title = get_the_title();

            if ( $args['showCurrent'] == 1 ) {
                $title_at_end = get_the_title();
                $html .= $args['before'] . $title_at_end . $args['after'];
            }
        } elseif ( is_page() && $post->post_parent ) {
            $xstore_title= get_the_title();
            $parent_id   = $post->post_parent;
            $breadcrumbs = array();

            while ( $parent_id ) {
                $page          = get_page( $parent_id );
                $breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>' . $args['delimiter'];
                $parent_id     = $page->post_parent;
            }
            $breadcrumbs = array_reverse( $breadcrumbs) ;

            for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
                $html .= $breadcrumbs[$i];
                if ( $i != count( $breadcrumbs ) -1 ) $html .= ' ' . $args['delimiter'] . ' ';
            }
            if  ($args['showCurrent'] == 1 ) $html .= ' ' . $args['delimiter'] . ' ' . $args['before'] . get_the_title() . $args['after'];
        } elseif ( is_tag() ) {
            $xstore_title = esc_html__( 'Tag: ', 'xstore' ) . single_tag_title( '', false );
            $title_at_end = single_tag_title( '', false );

            $html .= $args['before'] . esc_html__('Posts tagged', 'xstore') . ' "' . $title_at_end . '"' . $args['after'];
        } elseif ( is_author() ) {
            global $author;

            $xstore_title = esc_html__( 'All posts by ', 'xstore' ) . get_the_author();
            $userdata     = get_userdata($author);
            $title_at_end = $userdata->display_name;

            $html .= $args['before'] . esc_html__('Articles posted by ', 'xstore') . $args['after'] . get_the_author_posts_link();
        } elseif ( is_404() ) {
            $xstore_title = esc_html__( 'Page not found', 'xstore' );
            $html .= $args['before'] . esc_html__('Error 404', 'xstore') . $args['after'];
        } elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $xstore_title = esc_html__( 'Asides', 'xstore' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $xstore_title = esc_html__( 'Videos', 'xstore' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $xstore_title = esc_html__( 'Audio', 'xstore' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $xstore_title = esc_html__( 'Quotes', 'xstore' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $xstore_title = esc_html__( 'Galleries', 'xstore' );
        } elseif( is_archive() ) {
            $xstore_title = esc_html__( 'Archives', 'xstore' );
        }

        if ( get_query_var( 'paged' ) ) {
            $xstore_title = esc_html__( 'Page', 'xstore' ) . ' ' . get_query_var( 'paged' );
            $html .= ( ! empty( $title_at_end ) ) ? $title_at_end . ' ' . $args['delimiter'] : '';
        }

        $html .= '</div>';
            if( get_theme_mod('return_to_previous', 1) ) $html .= etheme_back_to_page();
        $html .= '</div>';
	
	    $xstore_title = apply_filters('etheme_breadcrumbs_page_title', $xstore_title );
        $html .= ' <' . $args['title_tag'] . ' class="title"><span>' . $xstore_title . '</span></'.$args['title_tag'].'>';

        do_action( 'etheme_before_breadcrumbs' );
        echo $html; // All data escaped
        do_action( 'etheme_after_breadcrumbs' );

    }
