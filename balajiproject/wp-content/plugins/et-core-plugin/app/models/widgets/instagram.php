<?php

namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Instagram widget.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Instagram extends Widgets {


	function __construct() {
		$token 			 = false;
		$API_type        = 'personal';
		$account_type    = 'PERSONAL';
		$this->force_tag = apply_filters( 'et_instagram_force_tag', false );
		$global_user     = '';
		$widget_ops      = array(
			'classname'   => 'null-instagram-feed',
			'description' => esc_html__( 'Displays your latest Instagram photos', 'xstore-core' )
		);
		parent::__construct( 'null-instagram-feed', '8theme - ' . esc_html__( 'Instagram', 'xstore-core' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		if (parent::admin_widget_preview(esc_html__('Instagram', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		$ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'instagram', !apply_filters('et_ajax_widgets', $ajax) );
			if ( class_exists( 'WPBMap' ) ) {
				etheme_enqueue_style( 'wpb-instagram', !apply_filters('et_ajax_widgets', $ajax) );
			}
		}

		if (apply_filters('et_ajax_widgets', $ajax)){
			echo et_ajax_element_holder( 'Instagram', $instance, '', '', 'widget', $args );
			return;
		}


		extract( $args, EXTR_SKIP );

		$username = empty( $instance['username'] ) ? '' : $instance['username'];
		$limit    = empty( $instance['number'] ) ? 9 : $instance['number'];
		$columns  = empty( $instance['columns'] ) ? 3 : (int) $instance['columns'];
		$size     = empty( $instance['size'] ) ? 'thumbnail' : $instance['size'];
		$img_type = empty( $instance['img_type'] ) ? 'default' : $instance['img_type'];
		$target   = empty( $instance['target'] ) ? '_self' : $instance['target'];
		$user     = empty( $instance['user'] ) ? '' : $instance['user'];
		$link     = empty( $instance['link'] ) ? '' : $instance['link'];
		$slider   = (empty( $instance['slider'] ) || $instance['slider'] == 'false') ? false : true;
		$spacing  = (empty( $instance['spacing'] )|| $instance['spacing'] == 'false') ? false : true;
		$type     = empty( $instance['type'] ) ? 'widget' : $instance['type'];
		$tag_type = empty( $instance['tag_type'] ) ? false : $instance['tag_type'];

		// slider args
		$large                     = empty( $instance['large'] ) ? 5 : $instance['large'];
		$notebook                  = empty( $instance['notebook'] ) ? 4 : $instance['notebook'];
		$tablet_land               = empty( $instance['tablet_land'] ) ? 3 : $instance['tablet_land'];
		$tablet_portrait           = empty( $instance['tablet_portrait'] ) ? 2 : $instance['tablet_portrait'];
		$mobile                    = empty( $instance['mobile'] ) ? 1 : $instance['mobile'];
		$slider_autoplay           = empty( $instance['slider_autoplay'] ) ? false : true;
		$slider_stop_on_hover      = empty( $instance['slider_stop_on_hover'] ) ? false : true;
		$slider_speed              = empty( $instance['slider_speed'] ) ? 300 : $instance['slider_speed'];
		$slider_interval           = empty( $instance['slider_interval'] ) ? 3000 : $instance['slider_interval'];
		$slider_loop               = ( ! isset( $instance['slider_loop'] ) || empty( $instance['slider_loop'] ) ) ? false : true;
		$pagination_type           = empty( $instance['pagination_type'] ) ? 'hide' : $instance['pagination_type'];
		$default_color             = empty( $instance['default_color'] ) ? '#e6e6e6' : $instance['default_color'];
		$active_color              = empty( $instance['active_color'] ) ? '#b3a089' : $instance['active_color'];
		$hide_fo                   = empty( $instance['hide_fo'] ) ? '' : $instance['hide_fo'];
		$navigation_type           = empty( $instance['navigation_type'] ) ? 'arrow' : $instance['navigation_type'];
		$navigation_position_style = empty( $instance['navigation_position_style'] ) ? 'arrows-hover' : $instance['navigation_position_style'];
		$navigation_style          = empty( $instance['navigation_style'] ) ? '' : $instance['navigation_style'];
		$navigation_position       = empty( $instance['navigation_position'] ) ? 'middle' : $instance['navigation_position'];
		$hide_buttons              = empty( $instance['hide_buttons'] ) ? false : true;
		$hide_buttons_for          = empty( $instance['hide_buttons_for'] ) ? '' : $instance['hide_buttons_for'];
		$is_preview                = empty( $instance['is_preview'] ) ? false : true;


		if ( $type == 'widget' ) {
			$before_widget = str_replace( 'widget', 'sidebar-widget', $before_widget );
			$before_widget = str_replace( array(
				'sidebar-sidebar-widget',
				'footer-sidebar-widget',
				'prefooter-sidebar-widget'
			), array( 'sidebar-widget', 'footer-widget', 'footer-widget' ), $before_widget );
			$img_type      = 'squared';
		}

		echo $before_widget;
		echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

		do_action( 'etheme_before_widget', $instance );

		$instagram['data'] = array();

		$tag = str_replace( '#', '', $username );

		// Remove it after instagram update
		if ( ! $user ) {
			$user = apply_filters( 'et_escape_instagram_user', $user );
		}

		if ( ! $user ) {
			echo '<div class="woocommerce-info">' . esc_html__( 'To use this element select instagram user', 'xstore-core' ) . '</div>';
		} else {
			$instagram = $this->et_get_instagram( $limit, $tag, '', $user, $type, $tag_type );

			if ( is_wp_error( $instagram ) ) {
				echo $instagram->get_error_message();
			} else {

				$box_id = rand( 1000, 10000 );

				$imgclass = $swiper_container = $swiper_wrapper = '';
				$swiper_entry_class = array();
				$attr     = array();

				if ( $slider && $type != 'widget' ) {
					$swiper_container = 'swiper-container';
					$swiper_container .= ( $slider_stop_on_hover ) ? ' stop-on-hover' : '';
					$swiper_container .= ' slider-' . $box_id;
					$swiper_container .= ( $pagination_type == 'lines' ) ? ' swiper-pagination-lines' : '';
					$swiper_wrapper   = 'swiper-wrapper';

					$autoplay = '';
					$speed    = '300';

					if ( $slider_autoplay ) {
						$autoplay = $slider_interval;
						$speed    = $slider_speed;
					}

					$attr = array(
						'data-breakpoints="1"',
						'data-xs-slides="' . esc_attr( $mobile ) . '"',
						'data-sm-slides="' . esc_attr( $tablet_land ) . '"',
						'data-md-slides="' . esc_attr( $notebook ) . '"',
						'data-lt-slides="' . esc_attr( $large ) . '"',
						'data-slides-per-view="' . esc_attr( $large ) . '"',
						'data-autoplay="' . esc_attr( $autoplay ) . '"',
						'data-speed="' . esc_attr( $speed ) . '"'
					);
					if ( $slider_loop ) {
						$attr[] = 'data-loop="true"';
					}

					if ( $spacing ) {
						$attr[] = 'data-space="0"';
					}

					$swiper_entry_class[] = $navigation_position;
					$swiper_entry_class[] = $navigation_position_style;
				}

				?>
                <div class="swiper-entry <?php echo implode(' ', $swiper_entry_class); ?>">

                    <div class="widget null-instagram-feed <?php echo esc_attr( $swiper_container ); ?><?php if ( $spacing ) {
						echo ' instagram-no-space';
					} ?>"
						<?php echo implode( ' ', $attr ); ?>
                    >

                        <ul class="<?php echo esc_attr( $swiper_wrapper ); ?> instagram-pics clearfix instagram-size-<?php echo esc_attr( $size ); ?> instagram-columns-<?php echo esc_attr( $columns ); ?>"
                            data-force-tag="<?php echo esc_attr( $this->force_tag ); ?>">
							<?php

							ob_start();

							foreach ( $instagram['data'] as $item ) {

								if ( $this->API_type == 'old' || $this->force_tag ) {
									esc_html_e( 'Error: Old API not supported', 'xstore-core' );
									continue;
								} else {
									if ( isset( $item['thumbnail_url'] ) ) {
										$image_src = $item['thumbnail_url'];
									} elseif ( isset( $item['media_url'] ) && ! empty( $item['media_url'] ) ) {
										$image_src = $item['media_url'];
									} else {
										$image_src = ET_CORE_URL . 'app/controllers/images/instagram/placeholder.jpg';
									}
								}


								if ( $link != '' ) {
									if ( $this->API_type == 'old' ) {
										$username = $item['user']['username'];
									} else {
										$username = $this->global_user;
									}
								}

								$src = 'src="' . esc_url( $image_src ) . '"';

								$slider_src = 'class="swiper-lazy" data-src="' . esc_url( $image_src ) . '"';

								$style = 'background: url(' . esc_url( $image_src ) . ');background-size: cover;height: 100%;background-repeat: no-repeat;background-position: center;width: 100%;padding: 100% 0 0;';

								$img_style = 'visibility: hidden; opacity: 0; position: absolute;';

								if ( $img_type == 'default' ) {
									$style = $img_style = '';
								}

								$likes = '';

								if ( isset($item['like_count']) && $item['like_count'] > 0) {
									$likes = '<span class="insta-likes">'.$item['like_count'].'</span>';
								}

								if ( $this->API_type == 'old' || $this->force_tag ) {
									esc_html_e( 'Error: Old API not supported', 'xstore-core' );
								} else {
									if ( $this->account_type == 'PERSONAL' ) {
										printf(
											'<li %s>
                                                    <a href="%s" target="%s" rel="noopener" style="%s">
                                                        %s
                                                        <img %s  alt="%s" title="%s" width="1080" height="1080" class="%s" style="%s"/>
                                                        <i class="et-inst-media-type media-type-%s"></i>
                                                    </a>
                                                </li>',
											( $slider ) ? 'class="swiper-slide"' : '',
											esc_url( $item['permalink'] ),
											esc_attr( $target ),
											$style,
											( $slider ) ? etheme_loader( false, 'swiper-lazy-preloader' ) : '',
											( $slider ) ? $slider_src : $src,
											// esc_attr( $item['caption'] ),
											'',
											// esc_attr( $item['caption'] ),
											'',
											$imgclass,
											$img_style,
											strtolower( $item['media_type'] )
										);
									} else {
										printf(
											'<li %s>
                                                    <a href="%s" target="%s" rel="noopener" style="%s">
                                                        %s
                                                        <img %s  alt="%s" title="%s" width="1080" height="1080" class="%s" style="%s"/>
                                                         <div class="insta-info">
                                                            %s
                                                            <span class="insta-comments">%s</span>
                                                        </div>
                                                        <i class="et-inst-media-type media-type-%s"></i>
                                                    </a>
                                                </li>',
											( $slider ) ? 'class="swiper-slide"' : '',
											esc_url( $item['permalink'] ),
											esc_attr( $target ),
											$style,
											( $slider ) ? etheme_loader( false, 'swiper-lazy-preloader' ) : '',
											( $slider ) ? $slider_src : $src,
											'',
											'',
											$imgclass,
											$img_style,
											$likes,
											isset($item['comments_count']) ? $item['comments_count'] : 0,
											strtolower( $item['media_type'] )
										);
									}

								}
							}


							echo apply_filters('etheme_instagram_content', ob_get_clean()); ?>

                        </ul>
						<?php if ( $slider && $pagination_type != "hide" ) {
							$pagination_class = '';
							if ( $hide_fo == 'mobile' ) {
								$pagination_class = ' mob-hide';
							} elseif ( $hide_fo == 'desktop' ) {
								$pagination_class = ' dt-hide';
							}
							echo '<div class="swiper-pagination etheme-css ' . esc_html( $pagination_class ) . '" data-css=".slider-' . $box_id . ' .swiper-pagination-bullet{background-color:' . $default_color . ';} .slider-' . $box_id . ' .swiper-pagination-bullet:hover{ background-color:' . $active_color . '; } .slider-' . $box_id . ' .swiper-pagination-bullet-active{ background-color:' . $active_color . '; }"></div>';
						} ?>
                    </div>
					<?php
					if ( $slider && ( ! $hide_buttons || ( $hide_buttons && $hide_buttons_for != 'both' ) ) ) {
						$navigation_class = 'swiper-nav';
						if ( $hide_buttons_for == 'desktop' ) {
							$navigation_class .= ' dt-hide';
						} elseif ( $hide_buttons_for == 'mobile' ) {
							$navigation_class .= ' mob-hide';
						}

						$navigation_left_class  = 'swiper-custom-left' . ' ' . $navigation_class;
						$navigation_right_class = 'swiper-custom-right' . ' ' . $navigation_class;

						$navigation_left_class .= ' type-' . $navigation_type . ' ' . $navigation_style;
						$navigation_right_class .= ' type-' . $navigation_type . ' ' . $navigation_style;
						?>
                        <div class="swiper-button-prev <?php echo esc_attr( $navigation_left_class ); ?>"></div>
                        <div class="swiper-button-next <?php echo esc_attr( $navigation_right_class ); ?>"></div>
					<?php } ?>
                </div>
				<?php
			}
		}

		if ( $link != '' ) { ?>
            <p class="clear">
            <a href="//instagram.com/<?php echo trim( $username ); ?>" rel="me"
               target="<?php echo esc_attr( $target ); ?>">
				<?php echo $link; ?>
            </a>
            </p><?php
		}

		do_action( 'etheme_after_widget', $instance );

		if ( $is_preview ) {
			echo '<script>jQuery(document).ready(function(){ etTheme.global_image_lazy(); etTheme.swiperFunc(); }); </script>';
		}

		echo $after_widget;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'    => esc_html__( 'Instagram', 'xstore-core' ),
			'username' => '',
			'link'     => esc_html__( 'Follow Us', 'xstore-core' ),
			'number'   => 12,
			'size'     => 'thumbnail',
			'target'   => '_self',
			'user'     => ''
		) );
		$title    = esc_attr( $instance['title'] );
		$username = esc_attr( $instance['username'] );
		$number   = absint( $instance['number'] );
		$size     = esc_attr( $instance['size'] );
		$columns  = ( ! isset( $instance['columns'] ) ) ? '' : $instance['columns'];
		$target   = esc_attr( $instance['target'] );
		$user     = esc_attr( $instance['user'] );
		$link     = esc_attr( $instance['link'] );
		$spacing  = ( ! isset( $instance['spacing'] ) ) ? '' : esc_attr( $instance['spacing'] );
		$tag_type = ( ! isset( $instance['tag_type'] ) ) ? 'recent_media' : esc_attr( $instance['tag_type'] );
		$ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;

		?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'xstore-core' ); ?>:
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo $title; ?>"/></label></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php esc_html_e( 'Choose Instagram account', 'xstore-core' ); ?>
                :</label>
            <select id="<?php echo $this->get_field_id( 'user' ); ?>"
                    name="<?php echo $this->get_field_name( 'user' ); ?>" class="widefat">
                <option value="" <?php selected( '', $user ); ?>></option>
				<?php
				$api_data = get_option( 'etheme_instagram_api_data' );
				$api_data = json_decode( $api_data, true );


				if ( is_array( $api_data ) && count( $api_data ) ) {
					foreach ( $api_data as $key => $value ) {
						$value          = json_decode( $value, true );
						$value_username = __( 'Can not get the username', 'xstore-core' );

						if ( isset( $value['data'] ) && isset( $value['data']['username'] ) ) {
							$value_username = $value['data']['username'] . ' (old API)';
						} elseif ( isset( $value['username'] ) ) {
							$value_username = $value['username'];
						}

						?>
                        <option value="<?php echo $key ?>" <?php selected( $key, $user ); ?>><?php esc_html_e( $value_username ); ?></option>
						<?php
					}
				}
				?>
            </select>
        </p>
        <p>
            <a href="<?php echo admin_url( 'admin.php?page=et-panel-social' ) ?>"
               target="_blank"><?php esc_html_e( 'Add Instagram account?', 'xstore-core' ); ?></a>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php esc_html_e( 'Hashtag (Only for Instagram business users) ', 'xstore-core' ); ?>
                : <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>"
                         name="<?php echo $this->get_field_name( 'username' ); ?>" type="text"
                         value="<?php echo $username; ?>"/></label></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'tag_type' ); ?>"><?php esc_html_e( 'Sort by', 'xstore-core' ); ?>
                :</label>
            <select id="<?php echo $this->get_field_id( 'tag_type' ); ?>"
                    name="<?php echo $this->get_field_name( 'tag_type' ); ?>" class="widefat">
                <option value="recent_media" <?php selected( 'recent_media', $tag_type ) ?>>Recent media</option>
                <option value="top_media" <?php selected( 'top_media', $tag_type ) ?>>Top media</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of photos', 'xstore-core' ); ?>
                : <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>"
                         name="<?php echo $this->get_field_name( 'number' ); ?>" type="text"
                         value="<?php echo $number; ?>"/></label></p>

        <p><label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php esc_html_e( 'Image size', 'xstore-core' ); ?>
                :</label>
            <select id="<?php echo $this->get_field_id( 'size' ); ?>"
                    name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat">
                <option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php esc_html_e( 'Thumbnail', 'xstore-core' ); ?></option>
                <option value="medium" <?php selected( 'medium', $size ) ?>><?php esc_html_e( 'Medium', 'xstore-core' ); ?></option>
                <option value="large" <?php selected( 'large', $size ) ?>><?php esc_html_e( 'Large', 'xstore-core' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php esc_html_e( 'Open links in', 'xstore-core' ); ?>
                :</label>
            <select id="<?php echo $this->get_field_id( 'target' ); ?>"
                    name="<?php echo $this->get_field_name( 'target' ); ?>" class="widefat">
                <option value="_self" <?php selected( '_self', $target ) ?>><?php esc_html_e( 'Current window (_self)', 'xstore-core' ); ?></option>
                <option value="_blank" <?php selected( '_blank', $target ) ?>><?php esc_html_e( 'New window (_blank)', 'xstore-core' ); ?></option>
            </select>
        </p>
        <p><label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php esc_html_e( 'Columns', 'xstore-core' ); ?>
                :</label>
            <select id="<?php echo $this->get_field_id( 'columns' ); ?>"
                    name="<?php echo $this->get_field_name( 'columns' ); ?>" class="widefat">
                <option value="2" <?php selected( 2, $columns ) ?>>2</option>
                <option value="3" <?php selected( 3, $columns ) ?>>3</option>
                <option value="4" <?php selected( 4, $columns ) ?>>4</option>
            </select>
        </p>
        <p><label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php esc_html_e( 'Link text', 'xstore-core' ); ?>:
                <input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>"
                       name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>"/></label>
        </p>

        <p>
            <input type="checkbox" <?php checked( true, $spacing, true ); ?>
                   id="<?php echo $this->get_field_id( 'spacing' ); ?>"
                   name="<?php echo $this->get_field_name( 'spacing' ); ?>">
            <label for="<?php echo $this->get_field_id( 'spacing' ); ?>"><?php esc_html_e( 'Without spacing', 'xstore-core' ); ?></label>
        </p>
		<?php parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );


	}

	function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['username'] = trim( strip_tags( $new_instance['username'] ) );
		$instance['tag_type'] = ( $new_instance['tag_type'] ) ? $new_instance['tag_type'] : 'recent_media';
		$instance['number']   = ! absint( $new_instance['number'] ) ? 12 : $new_instance['number'];
		$instance['columns']  = ! absint( $new_instance['columns'] ) ? 4 : $new_instance['columns'];
		$instance['size']     = ( in_array( $new_instance['size'], array(
			'thumbnail',
			'medium',
			'large',
			'small'
		) ) ) ? $new_instance['size'] : 'thumbnail';
		$instance['target']   = ( ( in_array( $new_instance['target'], array(
			'_self',
			'_blank'
		) ) ) ? $new_instance['target'] : '_self' );
		$instance['user']     = ( $new_instance['user'] ) ? $new_instance['user'] : '';
		$instance['link']     = strip_tags( $new_instance['link'] );
		//$instance['slider']   = ( $new_instance['slider'] != '' ) ? true : false;
//		$instance['spacing']  = ( $new_instance['spacing'] != '' ) ? true : false;
		$instance['spacing']  = ( ! empty( $new_instance['spacing'] ) ) ? (int) $new_instance['spacing'] : '';
		$instance['slider']   = ( ! empty( $new_instance['slider'] ) ) ? (int) $new_instance['slider'] : '';
		$instance['ajax'] = ( ! empty( $new_instance['ajax'] ) ) ? (bool) $new_instance['ajax']: '';

		return $instance;
	}

	function et_get_instagram( $number = '', $tag = false, $last = '', $token = false, $type = 'widget', $tag_type = false ) {
		$count    = $number;
		$api_data = get_option( 'etheme_instagram_api_data' );
		$api_data = json_decode( $api_data, true );
		$username = '';

		if ( ! is_array( $api_data ) ) {
			$api_data = array();
		}

		if ( ! count( $api_data ) ) {
			return new \WP_Error( 'error', '<div class="woocommerce-info">' . esc_html__( 'To use this element select instagram user', 'xstore-core' ) . '</div>' );
		}

		foreach ( $api_data as $key => $value ) {
			$value = json_decode( $value, true );

			if ( $key == $token ) {

				$this->api_type( $value );

				if ( isset( $value['token'] ) ) {
					$token = $value['token'];
					$this->token = $value['token'];
				}

				if ( $this->API_type == 'old' ) {
					$username = $value['data']['username'];
					$user_id  = '';

				} else {
					$username          = $value['username'];
					$user_id           = $value['id'];
					$this->global_user = $username;
				}
			}
		}

		if ( ! $username ) {
			return new \WP_Error( 'error', '<div class="woocommerce-info">' . esc_html__( 'To use this element select instagram user', 'xstore-core' ) . '</div>' );
		}

		if ( $tag ) {
			$instagram = get_transient( 'etheme-instagram-data-tag-' . $tag . '-' . $type );
		} else {
			if ( $username ) {
				$instagram = get_transient( 'etheme-instagram-data-user-' . $username . '-' . $type );
			} else {
				return new \WP_Error( 'error', '<div class="woocommerce-info">' . esc_html__( 'To use this element select instagram user', 'xstore-core' ) . '</div>' );
			}
		}

		$callback = $instagram;

		if ( $last ) {
			$instagram = false;
		}

		if ( $instagram === false || isset( $_GET['et_reinit_instagram'] ) ) {
			$api_settings = get_option( 'etheme_instagram_api_settings' );
			$api_settings = json_decode( $api_settings, true );

			$insta_time = function_exists( 'etheme_get_option' ) ? etheme_get_option( 'instagram_time' ) : 2000;

			switch ( $api_settings['time_type'] ) {
				case 'min':
					$insta_time = $api_settings['time'] * MINUTE_IN_SECONDS;
					break;

				case 'hour':
					$insta_time = $api_settings['time'] * HOUR_IN_SECONDS;
					break;

				case 'day':
					$insta_time = $api_settings['time'] * DAY_IN_SECONDS;
					break;
				default:
					$insta_time = 2 * HOUR_IN_SECONDS;
					break;
			}

			if ( ! $token ) {
				return new \WP_Error( 'error', esc_html__( 'Error: To use this element enter instagram access token', 'xstore-core' ) );
			}

			if ( ! $number ) {
				$number = '&count=33';
			} else {
				$number = '&count=' . $number;
			}

			if ( $last ) {
				$last = '&max_id=' . $last;
			}

			if ( $this->API_type == 'old' ) {
				$url = $this->old_api_url( $token, $last, $number, $tag );
			} else {
				$url = $this->new_api_url( $number, $user_id, $token, $tag, $tag_type );
			}


			if ( is_wp_error( $url ) ) {
				return $url;
			}

			// echo $url;
			// die();

			// if (!empty( $tag )) {
			if ( ! empty( $tag ) && ( $this->API_type == 'old' || $this->force_tag ) ) {
				$callback = $this->old_api_url( $token, $last, $number, $tag );

			} else {
				$callback = $this->instagram_callback( $url, $tag );
			}

			if ( $tag ) {
				set_transient( 'etheme-instagram-data-tag-' . $tag . '-' . $type, $callback, $insta_time );
			} else {
				set_transient( 'etheme-instagram-data-user-' . $username . '-' . $type, $callback, $insta_time );
			}
		}

		return $callback;
	}

	function api_type( $data ) {
		if ( isset( $data['data']['username'] ) ) {
			$this->API_type = 'old';
		} else {
			$this->API_type     = 'personal';
			$this->account_type = $data['account_type'];
		}
	}

	private function new_api_url( $number, $user_id, $token, $tag, $tag_type ) {
		$number = str_replace( 'count', 'limit', $number );

		if ( $this->account_type == 'PERSONAL' ) {
			$url = 'https://graph.instagram.com/' . $user_id . '/media?fields=id,media_type,media_url,permalink,thumbnail_url,caption&access_token=' . $token . $number;

			$callback = $personal_data_responce = wp_remote_get( $url );
			$callback = wp_remote_retrieve_body( $callback );
			$callback = json_decode( $callback, true );

			if ( 200 != wp_remote_retrieve_response_code( $personal_data_responce ) && ! isset( $callback['data'] ) ) {

				if ( isset( $tag_data['error'] ) && isset( $tag_data['error']['message'] ) && isset( $tag_data['error']['error_user_title'] ) ) {
					return new \WP_Error( 'error', 'Error: ' . $tag_data['error']['message'] . '. ' . $tag_data['error']['error_user_title'] );
				} else {
					return new \WP_Error( 'error', esc_html__( 'Error: There is no media to show', 'xstore-core' ) );
				}
			}
		} else {

			if ( ! empty( $tag ) ) {
				//hashtag ID by user ID
				$tag_url  = 'https://graph.facebook.com/v6.0/ig_hashtag_search?user_id=' . $user_id . '&q=' . $tag . '&access_token=' . $token;
				$tag_data = $tag_data_responce = wp_remote_get( $tag_url );
				$tag_data = wp_remote_retrieve_body( $tag_data );
				$tag_data = json_decode( $tag_data, true );


				if ( 200 != wp_remote_retrieve_response_code( $tag_data_responce ) ) {

					if ( isset( $tag_data['error'] ) && isset( $tag_data['error']['message'] ) && isset( $tag_data['error']['error_user_title'] ) ) {
						return new \WP_Error( 'error', 'Error: ' . $tag_data['error']['message'] . '. ' . $tag_data['error']['error_user_title'] );
					} elseif (
						isset( $tag_data['error'] )
						&& isset( $tag_data['error']['message'] )
						&& isset( $tag_data['error']['error_subcode'] )
						&& isset( $tag_data['error']['code'] )
						&& $tag_data['error']['error_subcode'] == '460'
						&& $tag_data['error']['code'] == 190
					){
						return new \WP_Error( 'error', 'Warning: Your Instagram token expired. Go to the XStore Authorization section, remove the old one and generate the new one.' );
					} else {
						return new \WP_Error( 'error', esc_html__( 'Error: Instagram "hashtag search" did not return a 200.', 'xstore-core' ) );
					}
				}

				$tag_type = ( $tag_type ) ? $tag_type : 'recent_media';

				$url = 'https://graph.facebook.com/' . $tag_data['data'][0]['id'] . '/' . $tag_type . '?user_id=' . $user_id . '&fields=id,media_type,comments_count,like_count,media_url,permalink&access_token=' . $token . $number;
			} else {
				$url = 'https://graph.facebook.com/v6.0/' . $user_id . '/media?fields=id,media_type,media_url,like_count,permalink,comments_count,thumbnail_url&access_token=' . $token . $number;
			}

			$callback = wp_remote_get( $url );

			if( is_wp_error( $callback ) ) {
				return new \WP_Error( 'error', $callback->get_error_message() );
			}

			if ( ! isset($callback['response']) || ! isset($callback['response']['code']) || $callback['response']['code'] != 200 ) {
				// echo "sems you connected your business acc";
				$url = 'https://graph.instagram.com/' . $user_id . '/media?fields=id,media_type,media_url,permalink,thumbnail_url,caption&access_token=' . $token . $number;
			}
		}

		return $url;
	}

	private function old_api_url( $token, $last, $number, $tag ) {
		return new \WP_Error( 'error', esc_html__( 'Error: Old API not supported', 'xstore-core' ) );
	}

	private function instagram_callback( $url, $tag ) {
		$callback = wp_remote_get( $url );

		if ( is_wp_error( $callback ) || ! isset( $callback['response'] ) || ! is_array( $callback ) ) {
			return new \WP_Error( 'error', esc_html__( 'Error: Can not get response', 'xstore-core' ) );
		} elseif ( ! isset( $callback['response']['code'] ) ) {
			return new \WP_Error( 'error', esc_html__( 'Error: Can not get response code', 'xstore-core' ) );
		} elseif ( $callback['response']['code'] != 200 ) {

			$callback = wp_remote_retrieve_body( $callback );
			$callback = json_decode( $callback );

			if ( isset($callback->meta->error_message) ) {
				return new \WP_Error( 'error', esc_html__( 'Error: ', 'xstore-core' ) . $callback->meta->error_message );
			}elseif(isset($callback->error->message)){
				return new \WP_Error( 'error', esc_html__( 'Error: ', 'xstore-core' ) . $callback->error->message );
			} else {
				return new \WP_Error( 'error', esc_html__( 'Error: Undefined error', 'xstore-core' ));
			}
		}

		$callback = wp_remote_retrieve_body( $callback );
		$callback = json_decode( $callback, true );

		if ( empty( $callback ) ) {
			return new \WP_Error( 'error', esc_html__( 'Error: instagram did not return any dada', 'xstore-core' ) );
		}

		if ( $tag ) {
			foreach ( $callback['data'] as $key => $value ) {
				if ( $value['media_type'] === 'CAROUSEL_ALBUM' || $value['media_type'] === 'VIDEO' ) {

					$api_settings = get_option( 'etheme_instagram_api_settings' );
					$api_settings = json_decode( $api_settings, true );

					if ( isset( $api_settings['escape_albums'] ) && $api_settings['escape_albums'] ) {
						unset( $callback['data'][ $key ] );
					} else {

						$image_url = 'https://graph.facebook.com/'.$value['id'].'/?fields=id,media_type,media_url&access_token=' . $this->token;
						$image_callback = wp_remote_get( $image_url );

						if(
							is_wp_error( $image_callback )
							|| ! isset( $image_callback['response'] )
							|| ! is_array( $image_callback )
							|| ! isset( $image_callback['response']['code'] )
							|| $image_callback['response']['code'] != 200
						){
							$callback['data'][ $key ]['media_url'] = ET_CORE_URL . 'app/controllers/images/instagram/placeholder.jpg';
						} else {
							$image_callback = wp_remote_retrieve_body( $image_callback );
							$image_callback = json_decode( $image_callback, true );

							if( isset( $image_callback['media_url'] ) ){
								$callback['data'][ $key ]['media_url'] = $image_callback['media_url'];
							} else {
								$callback['data'][ $key ]['media_url'] = ET_CORE_URL . 'app/controllers/images/instagram/placeholder.jpg';
							}
						}
					}
				}
			}
		}

		return $callback;
	}
}