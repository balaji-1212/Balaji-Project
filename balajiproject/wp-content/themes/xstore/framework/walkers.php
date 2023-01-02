<?php if ( ! defined( 'ETHEME_FW' ) ) {
	exit( 'No direct script access allowed' );
}
// **********************************************************************// 
// ! Custom navigation
// **********************************************************************//

class ETheme_Navigation extends Walker_Nav_Menu {
	public $styles = '';
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		
		if ( isset( $this->visible ) ) {
			$visibility = $this->visible;
			if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
				if ( $depth > 0 ) {
					// $output .= "\n$indent<div class=\"nav-sublist\">\n";
					$output .= '';
				}
			} else {
				if ( $depth > 0 ) {
					$output .= "\n$indent<div class=\"nav-sublist\">\n";
				}
				$output .= "\n$indent<ul>\n";
			}
		} else {
			if ( $depth > 0 ) {
				$output .= "\n$indent<div class=\"nav-sublist\">\n";
			}
			$output .= "\n$indent<ul>\n";
		}
	}
	
	function end_lvl( &$output, $depth = 1, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		
		if ( isset( $this->visible ) ) {
			$visibility = $this->visible;
			if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
				if ( $depth > 0 ) {
					// $output .= "\n$indent</div>\n";
					$output .= '';
				}
			} else {
				$output .= "$indent</ul>\n";
				if ( $depth > 0 ) {
					$output .= "\n$indent</div>\n";
				}
			}
		} else {
			$output .= "$indent</ul>\n";
			if ( $depth > 0 ) {
				$output .= "\n$indent</div>\n";
			}
		}
	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		
		$item_id = $item->ID;
		
		$visibility    = get_post_meta( $item_id, '_menu-item-item_visibility', true );
		$this->visible = $visibility;
		if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
			return;
		}
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
		$value = $label_text = '';
		
		$anchor = get_post_meta( $item_id, '_menu-item-anchor', true );
		
		if ( ! empty( $anchor ) ) {
			$item->url = $item->url . '#' . $anchor;
			if ( ( $key = array_search( 'current_page_item', $item->classes ) ) !== false ) {
				unset( $item->classes[ $key ] );
			}
			if ( ( $key = array_search( 'current-menu-item', $item->classes ) ) !== false ) {
				unset( $item->classes[ $key ] );
			}
		}
		
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'item-level-' . $depth;
		$arrow     = '';
		
		$design         = get_post_meta( $item_id, '_menu-item-design', true );
		$design2        = get_post_meta( $item_id, '_menu-item-design2', true );
		$columns        = get_post_meta( $item_id, '_menu-item-columns', true );
		$icon_type      = get_post_meta( $item_id, '_menu-item-icon_type', true );
		$icon           = get_post_meta( $item_id, '_menu-item-icon', true );
		$label          = get_post_meta( $item_id, '_menu-item-label', true );
		$disable_titles = get_post_meta( $item_id, '_menu-item-disable_titles', true );
		$widget_area    = get_post_meta( $item_id, '_menu-item-widget_area', true );
		$static_block   = get_post_meta( $item_id, '_menu-item-static_block', true );
		$open_by_click  = get_post_meta( $item_id, '_menu-item-open_by_click', true );
		// $block = get_post_meta($item_id, '_menu-item-block', true);
		
		$widgets_content = $block_html = $postCategories = '';
		
		if ( $depth == 0 ) {
			if ( $design != '' ) {
				$classes[] = apply_filters( 'menu_item_design', 'item-design-' . $design );
			} else {
				$classes[] = apply_filters( 'menu_item_design', 'item-design-dropdown' );
			}
			if ( $columns != '' ) {
				$classes[] = 'columns-' . $columns;
			}
			if ( $open_by_click == 1 ) {
				$classes[] = 'menu-open-by-click';
				wp_enqueue_script('menu_item_on_click');
			}
			
			if ( apply_filters( 'menu_item_with_sublists', true ) ) {
				
				if ( apply_filters('menu_item_dropdown_full_width', false) ) {
					$classes[] = 'mega-menu-full-width';
				}
				
				if ( $static_block != '' ) {
					$classes[] = 'item-with-block';
					$classes[] = 'menu-item-has-children';
					$classes[] = 'menu-parent-item';
					ob_start();
					if ( ! apply_filters('menu_dropdown_ajax', etheme_get_option( 'menu_dropdown_ajax', true ) ) ) {
						etheme_static_block( $static_block, true );
					} else {
						echo et_ajax_element_holder(
							'block',
							array( 'id' => $static_block, 'ajax' => true ),
							'',
							false,
							'menu_static_block_dropdown' );
						$classes[] = 'menu-item-ajax-dropdown';
					}
					$block_html = ob_get_contents();
					ob_end_clean();
					if ( $block_html == '' ) {
						$block_html = '<p class="woocommerce-info m0">' . esc_html__( 'Oops, looks that mega-menu was not imported correctly. Go to Appearance > Menus > edit menu and use the 8theme menu options to select the Static Block for this menu item and show the mega-menu content', 'xstore' ) . '</p>';
					}
					$block_html = '<div class="menu-static-block nav-sublist-dropdown"><div class="block-container container">' . $block_html . '</div></div>';
				}
			}
			
			if ( $design == 'posts-subcategories' && apply_filters( 'menu_item_with_sublists', true ) ) {
				wp_enqueue_script( 'menu_posts' );
				$postCategories = '<div class="nav-sublist-dropdown"><div class="container">' . $this->getPostsSubcategories( $item ) . '</div></div>';
			}
			
		} else {
			if ( $design2 != '' ) {
				$classes[] = 'item-design2-' . $design2;
			}
			if ( $widget_area != '' ) {
				$classes[] = 'item-with-widgets';
				ob_start();
				dynamic_sidebar( $widget_area );
				$widgets = ob_get_contents();
				ob_end_clean();
				$widgets_content = '<div class="menu-widgets">' . $widgets . '</div>';
			}
			if ( ! empty( $block ) ) {
				$classes[] = 'item-with-block';
				ob_start();
				if ( ! apply_filters('menu_dropdown_ajax', etheme_get_option( 'menu_dropdown_ajax', true ) ) ) {
					etheme_static_block( $block, true );
				}
				else {
					echo et_ajax_element_holder(
						'block',
						array( 'id'   => $block, 'ajax' => true ),
						'',
						false,
						'menu_static_block_dropdown' );
					$classes[] = 'menu-item-ajax-dropdown';
				}
				$block_html = ob_get_contents();
				ob_end_clean();
				if ( $block_html == '' ) {
					$block_html = '<p class="woocommerce-info m0">' . esc_html__( 'Oops, looks that mega-menu was not imported correctly. Go to Appearance > Menus > edit menu and use the 8theme menu options to select the Static Block for this menu item and show the mega-menu content', 'xstore' ) . '</p>';
				}
				$block_html = '<div class="menu-block">' . $block_html . '</div>';
			}
		}
		
		if ( $depth < 3 ) {
			if ( $disable_titles == 1 ) {
				$classes[] = 'menu-disable_title';
			}
		}
		
		if ( $icon != '' ) {
			switch ( $icon_type ) {
				case 'xstore-icons':
					$icon = '<i class="et-icon et-' . $icon . '"></i>';
					break;
				case 'fontawesome-5':
					$icon = '<i class="' . $icon . '"></i>';
					break;
				default:
					$icon = '<i class="fa fa-' . $icon . '"></i>';
					break;
			}
		}
		if ( $label != '' ) {
			$classes[]  = 'menu-label-' . $label;
			$label_text .= '<span class="label-text">';
			switch ( $label ) {
				case 'sale':
					$label_text .= __( 'Sale', 'xstore' );
					break;
				case 'hot':
					$label_text .= __( 'Hot', 'xstore' );
					break;
				default:
					$label_text .= __( 'New', 'xstore' );
					break;
			}
			$label_text .= '</span>';
		}
		
		if ( $design != '' ) {
			$design = apply_filters( 'menu_item_design', 'item-design-' . $design );
			if ( in_array($design, array('item-design-mega-menu', 'item-design-posts-subcategories'))) {
				wp_enqueue_script('mega_menu');
				etheme_enqueue_style('mega-menu');
			}
			elseif ( $design2 == 'image-no-borders' ) {
				etheme_enqueue_style('mega-menu');
			}
		}
		
		if ( ! apply_filters( 'menu_item_with_sublists', true ) ) {
			unset( $classes[ array_search( 'menu-item-has-children', $classes ) ] );
			unset( $classes[ array_search( 'menu-parent-item', $classes ) ] );
		}
		
		if ( $depth == 0 ) {
			if ( in_array( 'menu-item-has-children', $classes ) ) {
				if ( get_query_var('is_mobile', false) ) {
					wp_enqueue_script( 'menu_item_on_touch' );
				}
				if ( apply_filters( 'menu_item_with_svg_arrow', false ) ) {
					$arrow .= '<svg class="arrow ' . apply_filters( 'menu_item_with_svg_arrow_class', '' ) . '" xmlns="http://www.w3.org/2000/svg" width="0.5em" height="0.5em" viewBox="0 0 24 24"><path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path></svg>';
				}
			}
		} elseif ( ! apply_filters( 'menu_subitem_with_arrow', true ) ) {
			unset( $classes[ array_search( 'menu-item-has-children', $classes ) ] );
			unset( $classes[ array_search( 'menu-parent-item', $classes ) ] );
		}
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		
		
		$use_img = get_post_meta( $item_id, '_menu-item-use_img', true );
		
//		$id = 'menu-item-' . $item->ID;
//		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		
		$output .= $indent . '<li id="menu-item-' . $item->ID .'"'  . $value . $class_names . '>';
		
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		
		if ( $use_img == 'img' || ( $depth > 0 && get_post_thumbnail_id( $item_id, 'thumb' ) != '' ) ) {
			$bg_position = get_post_meta( $item_id, '_menu-item-background_position', true );
			$bg_position = str_replace( ' ', '-', $bg_position );
			
			$attributes .= ' class="item-link type-img position-' . $bg_position . '"';
		} else {
			$attributes .= ' class="item-link"';
		}
		
		$description = '';
//		if ( $item->description != '' ) {
//			$description = '<span class="menu-item-descr hidden">' . do_shortcode( $item->description ) . '</span>';
//		}
		$tooltip = '';
		
		if ( has_post_thumbnail( $item_id ) && $depth > 0 ) {
			$tooltip = $this->et_get_tooltip_html( $item_id );
		}
		
		$this->et_enque_styles( $item_id, $depth );
		
		
		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		if ( $depth > 0 ) {
			if ( is_rtl() ) {
				$icon = $arrow . $icon;
			} else {
				$description = $description . $arrow;
			}
		} else {
			$description = $description . $arrow;
		}
		$item_output .= $icon;
		$item_output .= $args->link_before;
		if ( !($depth < 3 && $disable_titles == 1) ) {
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		}
		$item_output .= $args->link_after;
		$item_output .= $description;
		$item_output .= $tooltip;
		$item_output .= $label_text;
		
		if ( $use_img == 'img' ) {
			$post_thumbnail     = get_post_thumbnail_id( $item_id, 'thumb' );
			$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail );
			if ( $post_thumbnail_url ) {
				$item_output .= '<img src="' . $post_thumbnail_url . '" alt="menu-item-img">';
			}
		}
		
		$item_output .= '</a>';
		$item_output .= $widgets_content;
		$item_output .= $block_html;
		$item_output .= $postCategories;
		$item_output .= $args->after;
		
		if ( $depth === 0 && ( in_array( 'menu-item-has-children', $item->classes ) ) && apply_filters( 'menu_item_with_sublists', true ) ) {
			$item_output .= "\n<div class=\"nav-sublist-dropdown\"><div class=\"container\">\n";
		}
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$visibility    = get_post_meta( $item->ID, '_menu-item-item_visibility', true );
		$this->visible = $visibility;
		if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
			return;
		}
		
		if ( $depth === 0 && ( in_array( 'menu-item-has-children', $item->classes ) ) && apply_filters( 'menu_item_with_sublists', true ) ) {
			$output .= "\n</div></div><!-- .nav-sublist-dropdown -->\n";
		}
		
		$output .= "</li>\n";
	}
	
	function et_enque_styles( $item_id, $depth ) {
		$visibility = get_post_meta( $item_id, '_menu-item-item_visibility', true );
		if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
			return;
		}
		$use_img            = get_post_meta( $item_id, '_menu-item-use_img', true );
		$post_thumbnail     = get_post_thumbnail_id( $item_id, 'thumb' );
		$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail );
		$bg_position        = get_post_meta( $item_id, '_menu-item-background_position', true );
		$bg_repeat          = get_post_meta( $item_id, '_menu-item-background_repeat', true );
		$column_width       = get_post_meta( $item_id, '_menu-item-column_width', true );
		$column_height      = get_post_meta( $item_id, '_menu-item-column_height', true );
		$sublist_width      = get_post_meta( $item_id, '_menu-item-sublist_width', true );
		
		$bg_pos = $bg_rep = $styles = $styles_sublist = $submenu_width = '';
		
		if ( $use_img != 'img' ) {
			if ( $bg_position != '' ) {
				$bg_pos = "background-position: " . $bg_position . ";";
			}
			
			if ( $bg_repeat != '' ) {
				$bg_rep = "background-repeat: " . $bg_repeat . ";";
			}
			
			if ( ! empty( $post_thumbnail_url ) ) {
				$styles_sublist .= $bg_pos . $bg_rep . " background-image: url(" . $post_thumbnail_url . ");";
			}
			
			if ( ! empty( $post_thumbnail_url ) && ! empty( $column_height ) ) {
				$styles_sublist .= " background-image: url(" . $post_thumbnail_url . ");";
			}
		}
		
		if ( $depth == 1 && ! empty( $column_width ) ) {
			$styles .= 'width:' . $column_width . '%!important';
		}
		
		if ( $depth == 0 && ! empty( $sublist_width ) ) {
			$submenu_width .= 'width:' . $sublist_width . 'px;';
		}
		
		if ( ! empty( $styles ) ) {
			wp_add_inline_style( 'xstore-inline-css', '.menu-item-' . $item_id . ' {' . $styles . '}' );
		}
		
		if ( ! empty( $column_height ) ) {
			wp_add_inline_style( 'xstore-inline-css', '.menu-item-' . $item_id . ' .nav-sublist-dropdown > .container > ul, .menu-item-' . $item_id . ' .menu-static-block {height: ' . $column_height . 'px!important;}' );
		}
		
		if ( ! empty( $styles_sublist ) ) {
			wp_add_inline_style( 'xstore-inline-css', '.menu-item-' . $item_id . ' .nav-sublist-dropdown {' . $styles_sublist . '}' );
		}
		
		if ( ! empty( $submenu_width ) ) {
//			wp_add_inline_style( 'xstore-inline-css', '.menu-item-' . $item_id . '.item-design-mega-menu.menu-item .nav-sublist-dropdown {' . $submenu_width . '}' );
			if ( apply_filters('menu_item_dropdown_full_width', false) ) {
				wp_add_inline_style( 'xstore-inline-css', '.menu-item-' . $item_id . '.menu-item-' . $item_id . '.mega-menu-full-width .nav-sublist-dropdown .container { max-' . $submenu_width . '}');
			}
			else {
				wp_add_inline_style( 'xstore-inline-css', '.et_b_header-menu .secondary-menu-wrapper .menu-item-'.$item_id.'.item-design-mega-menu .nav-sublist-dropdown, .menu-item-' . $item_id . '.item-design-mega-menu.menu-item .nav-sublist-dropdown {' . $submenu_width . '}' );
			}
		}
		
	}
	
	function et_get_tooltip_html( $item_id ) {
		$visibility = get_post_meta( $item_id, '_menu-item-item_visibility', true );
		if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
			return;
		}
		$output             = '';
		$post_thumbnail     = get_post_thumbnail_id( $item_id );
		$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail, 'large' );
		$output             .= '<div class="nav-item-image">';
		$output             .= '<img src="' . $post_thumbnail_url[0] . '" width="' . $post_thumbnail_url[1] . '" height="' . $post_thumbnail_url[2] . '" />';
		$output             .= '</div>';
		
		return $output;
	}
	
	function getPostsSubcategories( $item ) {
		
		if ( $item->object != 'category' ) {
			return '';
		}
		
		$cat_id   = $item->object_id;
		$children = get_categories( array( 'child_of' => $cat_id ) );
		
		$output = '<div class="posts-subcategories">';
		
		if ( ! empty( $children ) ) {
			$cat_id = $children[0]->term_id;
			$output .= '<div class="subcategories-tabs"><ul>';
			$_i     = 0;
			
			foreach ( $children as $child ) {
				$class  = $_i == 0 ? 'class="active"' : '';
				$output .= '<li ' . $class . ' data-cat="' . $child->term_id . '">' . $child->name . '</li>';
				$_i++;
			}
			
			$output .= '</ul></div><!-- .posts-subcategories -->';
		}
		
		
		$output .= '<div class="posts-content">';
		
		$output .= $this->getPostsByCategory( $cat_id );
		
		$output .= '</div><!-- .posts-content -->';
		
		
		$output .= '</div><!-- .posts-subcategories -->';
		
		return $output;
	}
	
	function getPostsByCategory( $cat = false ) {
		
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_GET['cat'] ) ) {
			$cat = (int) $_GET['cat'];
		}
		
		if ( ! $cat ) {
			return '';
		}
		
		$category = get_category( $cat );
		
		$posts = get_posts( array(
			'category'       => $cat,
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'post_type'      => 'post'
		) );
		
		if ( ! empty( $posts ) ) {
			
			ob_start();
			
			foreach ( $posts as $post ) {
				echo '<div class="post-preview">';
				
				echo '<div class="post-preview-thumbnail" onclick="window.location=\'' . get_the_permalink( $post->ID ) . '\'">';
				
				echo get_the_post_thumbnail( $post->ID, 'medium' );
				
				echo '<div class="post-category">' . $category->name . '</div>';
				
				echo '</div>';
				
				echo '<a href="' . get_the_permalink( $post->ID ) . '">' . $post->post_title . '</a>';
				
				echo '</div>';
			}
			
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				echo ob_get_clean();
				die();
			} else {
				return ob_get_clean();
			}
		}
		
		return '';
	}
}


class ETheme_Navigation_Mobile extends Walker_Nav_Menu {
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
		$item_id = $item->ID;
		
		$value = '';
		$visibility    = get_post_meta( $item_id, '_menu-item-item_visibility', true );
		$this->visible = $visibility;
		if ( ( $visibility == 'logged' && ! ( get_query_var( 'et_is-loggedin', false) ) ) || ( $visibility == 'unlogged' && get_query_var( 'et_is-loggedin', false) ) ) {
			return;
		}
		
		$anchor         = get_post_meta( $item_id, '_menu-item-anchor', true );
		$icon_type      = get_post_meta( $item_id, '_menu-item-icon_type', true );
		$icon           = get_post_meta( $item_id, '_menu-item-icon', true );
		$disable_titles = get_post_meta( $item_id, '_menu-item-disable_titles', true );
		
		if ( ! empty( $anchor ) ) {
			$item->url = $item->url . '#' . $anchor;
			if ( ( $key = array_search( 'current_page_item', $item->classes ) ) !== false ) {
				unset( $item->classes[ $key ] );
			}
			if ( ( $key = array_search( 'current-menu-item', $item->classes ) ) !== false ) {
				unset( $item->classes[ $key ] );
			}
		}
		
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'item-level-' . $depth;
		
		if ( $icon != '' ) {
			switch ( $icon_type ) {
				case 'xstore-icons':
					$icon = '<i class="et-icon et-' . $icon . '"></i>';
					break;
				default:
					$icon = '<i class="fa fa-' . $icon . '"></i>';
					break;
			}
		}
		
		if ( $depth < 3 ) {
			if ( $disable_titles == 1 ) {
				$classes[] = 'menu-disable_title';
			}
		}
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		
//		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
//		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		
//		$output .= $indent . '<li' . $id . $value . $class_names . '>';
		$output .= $indent . '<li id="menu-item-' . $item->ID .'"'  . $value . $class_names . '>';
		
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		
		$tooltip = '';
		
		if ( has_post_thumbnail( $item_id ) && $depth > 0 ) {
			$tooltip = $this->et_get_tooltip_html( $item_id );
		}
		
		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $icon;
		$item_output .= $args->link_before;
		if ( !($depth < 3 && $disable_titles == 1) ) {
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		}
		$item_output .= $args->link_after;
		$item_output .= $tooltip;
		$item_output .= '</a>';
		$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	function et_get_tooltip_html( $item_id ) {
		$output             = '';
		$post_thumbnail     = get_post_thumbnail_id( $item_id );
		$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail, 'large' );
		$output             .= '<div class="nav-item-image">';
		$output             .= '<img src="' . $post_thumbnail_url[0] . '" width="' . $post_thumbnail_url[1] . '" height="' . $post_thumbnail_url[2] . '" />';
		$output             .= '</div>';
		
		return $output;
	}
	
}

add_action( 'wp_ajax_menu_posts', array( new ETheme_Navigation(), 'getPostsByCategory' ) );
add_action( 'wp_ajax_nopriv_menu_posts', array( new ETheme_Navigation(), 'getPostsByCategory' ) );