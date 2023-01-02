<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         http://docs.woothemes.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $etheme_global, $post_id, $product, $is_IE, $main_slider_on, $attachment_ids, $main_attachment_id;

$with_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
if ( !$with_ajax && !isset($etheme_global['gallery_slider'])) {
	$layout             = get_query_var( 'et_product-layout', 'default' );
	$thumbs_slider_mode = etheme_get_option( 'thumbs_slider_mode', 'enable' );
	
	if ( $thumbs_slider_mode == 'enable' || ( $thumbs_slider_mode == 'enable_mob' && get_query_var( 'is_mobile' ) ) ) {
		$gallery_slider = true;
	} else {
		$gallery_slider = false;
	}
// $gallery_slider = (etheme_get_option('thumbs_slider') == 'enable') ? true : false ;
	
	$thumbs_slider = etheme_get_option( 'thumbs_slider_vertical', 'horizontal' );
	
	$enable_slider = etheme_get_custom_field( 'product_slider', get_the_ID() );
	
	$stretch_slider = etheme_get_option( 'stretch_product_slider', 1 );
	
	$slider_direction = etheme_get_custom_field( 'slider_direction', get_the_ID() );
	
	$vertical_slider = $thumbs_slider == 'vertical';
	
	if ( $slider_direction == 'vertical' ) {
		$vertical_slider = true;
	} elseif ( $slider_direction == 'horizontal' ) {
		$vertical_slider = false;
	}
	
	$show_thumbs = $thumbs_slider != 'disable';
	
	if ( $layout == 'large' && $stretch_slider ) {
		$show_thumbs            = false;
		$etheme_global['class'][] = 'stretch-swiper-slider ';
	}
	if ( $slider_direction == 'disable' ) {
		$show_thumbs = false;
	} elseif ( in_array( $slider_direction, array( 'vertical', 'horizontal' ) ) ) {
		$show_thumbs = true;
	}
	if ( $enable_slider == 'on' || ( $enable_slider == 'on_mobile' && get_query_var( 'is_mobile' ) ) ) {
		$gallery_slider = true;
	} elseif ( $enable_slider == 'off' || ( $enable_slider == 'on_mobile' && ! get_query_var( 'is_mobile' ) ) ) {
		$gallery_slider = false;
		$show_thumbs    = false;
	}
	$etheme_global['gallery_slider']  = $gallery_slider;
	$etheme_global['vertical_slider'] = $vertical_slider;
	$etheme_global['show_thumbs']     = $show_thumbs;
}

if ( get_option( 'etheme_single_product_builder', false ) && ! isset( $etheme_global['quick_view'] ) ) {
	wc_get_template( 'single-product/product-image-builder.php' );
	return;
}

$post_id = get_the_ID(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$variable_products_detach = etheme_get_option('variable_products_detach', false);
$product_type = $product->get_type();

if ( $with_ajax && ! isset( $etheme_global['quick_view'] ) ) {
	if ( ! empty( $_REQUEST['product_id'] ) ) {
		$post_id = (int) $_REQUEST['product_id']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post_id );
		$main_attachment_id = get_post_thumbnail_id( $post_id );
		$product            = wc_get_product( $post_id );
		if ( ! empty( $_REQUEST['option'] ) ) {
			$option         = esc_attr( $_REQUEST['option'] );
			$attributes     = $product->get_attributes();
			$variations     = $product->get_available_variations();
			$images         = '';
			$thumb          = '';
			$attachment_ids = array();
			
			foreach ( $variations as $variation ) {
				if ( isset( $variation['attributes'][ 'attribute_' . $swatch ] ) && $variation['attributes'][ 'attribute_' . $swatch ] == $option && has_post_thumbnail( $variation['variation_id'] ) ) {
					$main_attachment_id = get_post_thumbnail_id( $variation['variation_id'] );
				}
			}
			
		}
	}
	
} else {
	$attachment_ids     = $product->get_gallery_image_ids();
	$main_attachment_id = get_post_thumbnail_id( $post_id );
	if ( $with_ajax && isset( $etheme_global['quick_view'] ) ) {
		if ( $product_type == 'variation' ) {
			if ( $main_attachment_id < 1) { // does not have own image of variation
				$main_attachment_id = get_post_thumbnail_id( $product->get_parent_id() );
			}
			if ( etheme_get_option('enable_variation_gallery', false) && $variable_products_detach ) {
				// take images from variation gallery meta
				$variation_attachment_ids = get_post_meta( $product->get_id(), 'et_variation_gallery_images', true );
				if ( (bool) $variation_attachment_ids && count( (array) $variation_attachment_ids ) ) {
					$attachment_ids = $variation_attachment_ids;
				}
			}
		}
	}
}


$has_video = false;

$gallery_slider  = ( isset( $etheme_global['gallery_slider'] ) ) ? $etheme_global['gallery_slider'] : '';
$vertical_slider = ( isset( $etheme_global['vertical_slider'] ) ) ? $etheme_global['vertical_slider'] : '';
$show_thumbs     = ( isset( $etheme_global['show_thumbs'] ) ) ? $etheme_global['show_thumbs'] : '';
$video_position = etheme_get_option('product_video_position', 'last');

if ( $with_ajax ) {
	$gallery_slider = true;
}

$video_attachments = array();
$videos            = etheme_get_attach_video( $product->get_id() );
if ( isset( $videos[0] ) && $videos[0] != '' ) {
	$video_attachments = get_posts( array(
		'post_type' => 'attachment',
		'include'   => $videos[0]
	) );
}

if ( count( $video_attachments ) > 0 || etheme_get_external_video( $product->get_id() ) != '' ) {
	$has_video = true;
}

$force_check = false;

if ( ! $attachment_ids && ! $has_video && ( apply_filters( 'etheme_single_product_variation_gallery', get_query_var( 'etheme_single_product_variation_gallery', false ) ) && $product->get_type() == 'variable' ) ) {
	$force_check    = true;
	$attachment_ids = array( 0 );
}

$main_slider_on = ( count( $attachment_ids ) > 0 || $has_video || $force_check );

$class = '';
if ( $main_slider_on ) {
	$class .= ' main-slider-on';
}

$class .= ( $gallery_slider ) ? ' gallery-slider-on' : ' gallery-slider-off';

if ( $is_IE ) {
	$class .= ' ie';
}

$et_zoom       = etheme_get_option( 'product_zoom', 1 ) && ! isset( $etheme_global['quick_view'] );
$et_zoom_class = 'woocommerce-product-gallery__image';

if ( $et_zoom ) {
	$class .= ' zoom-on';
}

?>


<?php if ( !$with_ajax && ! isset( $etheme_global['quick_view'] ) ): ?>
    <div class="swiper-entry swipers-couple-wrapper images images-wrapper with-pswp woocommerce-product-gallery arrows-hovered <?php if ( $vertical_slider && $gallery_slider && $attachment_ids ) {
		echo 'swiper-vertical-images';
	} ?>">
<?php endif;

$swiper_container = '';
$swiper_wrapper   = '';
if ( $gallery_slider && ( count( $attachment_ids ) > 0 || $has_video || $force_check ) ) {
	$swiper_container = 'swiper-container';
	$swiper_wrapper   = 'swiper-wrapper';
}

$gallery_slider_class = ( $gallery_slider ) ? 'swiper-slide' : '';

$container_atts = array(
	'class="swiper-control-top ' . esc_attr( $swiper_container ) . ' ' . esc_attr( $class ) . '"',
	'data-effect="' . apply_filters( 'single_product_main_gallery_effect', 'slide' ) . '"'
);

if ( ! isset( $etheme_global['quick_view_gallery_grid'] ) ) {
	$container_atts[] = "data-space='10'";
} else {
	$container_atts[] = "data-space='0'";
	$container_atts[] = "data-free-mode='true'";
}

if ( etheme_get_option( 'thumbs_autoheight', '1' ) && ! isset( $etheme_global['quick_view_canvas'] ) ) {
	$container_atts[] = "data-autoheight='true'";
}

if ( $force_check ) {
	$container_atts[] = "data-default-empty='true'";
}

$is_yith_wcbm_frontend = class_exists('YITH_WCBM_Frontend');
$index             = 0;
?>
    <div <?php echo implode( ' ', $container_atts ); ?>>
        <div class="<?php echo esc_attr( $swiper_wrapper ); ?> main-images">
			<?php
			if ( $video_position == 'first') :
				if ( etheme_get_external_video( $product->get_id() ) ): ?>
                    <div class="<?php echo esc_attr( $gallery_slider_class ); ?> images woocommerce-product-gallery">
						<?php echo etheme_get_external_video( $product->get_id() ); ?>
                    </div>
				<?php endif;
				
				if ( count( $video_attachments ) > 0 ): ?>
                    <div class="<?php echo esc_attr( $gallery_slider_class ); ?> images woocommerce-product-gallery">
						<?php $autoplay = ( get_post_meta( $post->ID, '_product_video_autoplay', true ) ) ? 'autoplay="true" muted="muted"' : ''; ?>
                        <video controls="controls" <?php echo esc_html($autoplay); ?>>
							<?php foreach ( $video_attachments as $video ): ?>
								<?php $video_ogg = $video_mp4 = $video_webm = false; ?>
								<?php if ( $video->post_mime_type == 'video/mp4' && ! $video_mp4 ): $video_mp4 = true; ?>
                                    <source src="<?php echo esc_url( $video->guid ); ?>"
                                            type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
								<?php endif; ?>
								<?php if ( $video->post_mime_type == 'video/webm' && ! $video_webm ): $video_webm = true; ?>
                                    <source src="<?php echo esc_url( $video->guid ); ?>"
                                            type='video/webm; codecs="vp8, vorbis"'>
								<?php endif; ?>
								<?php if ( $video->post_mime_type == 'video/ogg' && ! $video_ogg ): $video_ogg = true; ?>
                                    <source src="<?php echo esc_url( $video->guid ); ?>"
                                            type='video/ogg; codecs="theora, vorbis"'>
									<?php esc_html_e( 'Video is not supporting by your browser', 'xstore' ); ?>
                                    <a href="<?php echo esc_url( $video->guid ); ?>"><?php esc_html_e( 'Download Video', 'xstore' ); ?></a>
								<?php endif; ?>
							<?php endforeach; ?>
                        </video>
                    </div>
				<?php endif;
			endif;
			?>
			<?php // if ( has_post_thumbnail( $post_id ) ) {
			if ( $main_attachment_id > 0) {
				
				$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
				$post_thumbnail_id = $main_attachment_id; // get_post_thumbnail_id( $post->ID );
				$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
				$placeholder       = 'with-images';
				
				// **********************************************************************************************
				// ! Main product image
				// **********************************************************************************************
				
				$attributes = array(
					'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
					'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
					'data-etheme-single-main' => true
				);
				
				$html           = '<a class="woocommerce-main-image pswp-main-image zoom" href="' . esc_url( $full_size_image[0] ) . '" data-width="' . esc_attr( $full_size_image[1] ) . '" data-height="' . esc_attr( $full_size_image[2] ) . '" data-index="' . $index . '">';
				
				// $type   = '';
				// if ( defined('ET_CORE_VERSION') ) {
				//    $type   = Kirki::get_option( 'images_loading_type_et-desktop' );
				// }
				// if ($type == 'lqip') {
				//     $placeholder = wp_get_attachment_image_src( get_post_thumbnail_id(), 'etheme-woocommerce-nimi' );
				//     $new_attr = 'src="' . $placeholder[0] . '" data-src';
				//     $image = get_the_post_thumbnail(
				//     $post->ID,
				//         apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ),
				//         array(
				//             'class' => 'lazyload lazyload-lqip',
				//         )
				//     );
				//     $html .= str_replace( 'src', $new_attr, $image );
				// } else {
				if ( $product_type == 'variation' && get_post_thumbnail_id( $post_id ) < 1) {
					$html .= get_the_post_thumbnail( $product->get_parent_id(), 'woocommerce_single', $attributes );
				}
				else {
					$html .= get_the_post_thumbnail( $post->ID, 'woocommerce_single', $attributes );
				}
				// }
				
				
				$html .= '</a>';
				
				echo '<div class="' . $gallery_slider_class . ' images woocommerce-product-gallery woocommerce-product-gallery__wrapper"><div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'woocommerce_thumbnail' ) . '" class="' . $et_zoom_class . '">';
				
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
				
				echo '</div></div>';
				
				// **********************************************************************************************
				// ! Product slider
				// **********************************************************************************************
				
				if ( $main_slider_on ) {
					
					if ( count( $attachment_ids ) > 0 ) {
						foreach ( $attachment_ids as $key => $attachment_id ) {
							$index ++;
							$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
							if ( !$full_size_image) continue;
							
							$attributes = array(
								'title'                   => esc_attr( get_the_title( $attachment_id ) ),
								'data-caption'            => esc_attr( get_the_excerpt( $attachment_id ) ),
								'data-src'                => $full_size_image[0],
								'data-large_image'        => $full_size_image[0],
								'data-large_image_width'  => $full_size_image[1],
								'data-large_image_height' => $full_size_image[2],
							);
							
							$html = '<a href="' . esc_url( $full_size_image[0] ) . '"  data-index="' . $index . '" itemprop="image" class="woocommerce-main-image zoom" >';
							
							$html .= wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), false, $attributes );
							
							$html .= '</a>';
							
							echo '<div class="' . esc_attr( $gallery_slider_class ) . ' images woocommerce-product-gallery woocommerce-product-gallery__wrapper"><div data-thumb="' . get_the_post_thumbnail_url( $attachment_id, 'woocommerce_thumbnail' ) . '" class="' . $et_zoom_class . '">';
							
							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $attachment_id ) );
							
							echo '</div></div>';
						}
					}
					
				}
				
			} else {
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src( 'woocommerce_single' ), esc_html__( 'Placeholder', 'xstore' ) ), $post_id );
			}
			
			if ($is_yith_wcbm_frontend) {
				remove_filter( 'woocommerce_single_product_image_thumbnail_html', array( YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product_thumbnail' ), 99 );
			}
			
			if ( $video_position == 'last') :
				if ( etheme_get_external_video( $product->get_id() ) ): ?>
                    <div class="<?php echo esc_attr( $gallery_slider_class ); ?> images woocommerce-product-gallery">
						<?php echo etheme_get_external_video( $product->get_id() ); ?>
                    </div>
				<?php endif;
				
				if ( count( $video_attachments ) > 0 ): ?>
                    <div class="<?php echo esc_attr( $gallery_slider_class ); ?> images woocommerce-product-gallery">
						<?php $autoplay = ( get_post_meta( $post->ID, '_product_video_autoplay', true ) ) ? 'autoplay="true" muted="muted"' : ''; ?>
                        <video controls="controls" <?php echo esc_html($autoplay); ?>>
							<?php foreach ( $video_attachments as $video ): ?>
								<?php $video_ogg = $video_mp4 = $video_webm = false; ?>
								<?php if ( $video->post_mime_type == 'video/mp4' && ! $video_mp4 ): $video_mp4 = true; ?>
                                    <source src="<?php echo esc_url( $video->guid ); ?>"
                                            type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
								<?php endif; ?>
								<?php if ( $video->post_mime_type == 'video/webm' && ! $video_webm ): $video_webm = true; ?>
                                    <source src="<?php echo esc_url( $video->guid ); ?>"
                                            type='video/webm; codecs="vp8, vorbis"'>
								<?php endif; ?>
								<?php if ( $video->post_mime_type == 'video/ogg' && ! $video_ogg ): $video_ogg = true; ?>
                                    <source src="<?php echo esc_url( $video->guid ); ?>"
                                            type='video/ogg; codecs="theora, vorbis"'>
									<?php esc_html_e( 'Video is not supporting by your browser', 'xstore' ); ?>
                                    <a href="<?php echo esc_url( $video->guid ); ?>"><?php esc_html_e( 'Download Video', 'xstore' ); ?></a>
								<?php endif; ?>
							<?php endforeach; ?>
                        </video>
                    </div>
				<?php endif;
			endif; ?>
        </div>
		<?php // if ( count( $attachment_ids ) > 0 || $force_check ) {
		// echo '<div class="swiper-pagination"></div>';
		// } ?>
		
		<?php etheme_360_view_block(); ?>
		
		<?php
		if ( $gallery_slider && ( count( $attachment_ids ) > 0 || $has_video || $force_check ) && ! isset( $etheme_global['quick_view_gallery_grid'] ) ) {
			$class = ( $force_check ) ? 'dt-hide mob-hide' : '';
			echo '<div class="swiper-custom-left ' . $class . '"></div>
                  <div class="swiper-custom-right ' . $class . '"></div>
            ';
		} ?>

    </div>

    <div class="empty-space col-xs-b15 col-sm-b30"></div>
<?php
if ( $gallery_slider && $show_thumbs ) {
	
	global $post_id, $product, $woocommerce, $main_slider_on, $attachment_ids, $main_attachment_id;
	
	$zoom_plugin = etheme_is_zoom_activated();
	
	$thums_size = apply_filters( 'single_product_small_thumbnail_size', 'woocommerce_thumbnail' );
	
	$ul_class = '';
	if ( ! $vertical_slider ) {
		$ul_class = 'swiper-wrapper ';
	}
	
	$ul_class .= 'right thumbnails-list';
	
	if ( $zoom_plugin ) {
		$ul_class .= ' yith_magnifier_gallery';
	}
	
	if ( $vertical_slider && get_query_var('et_is-rtl', false) ) {
		$ul_class .= ' slick-rtl';
	}
	
	if ( $vertical_slider ) {
		$ul_class .= ' vertical-thumbnails';
	} else {
		$ul_class .= ' thumbnails';
	}
	
	if ( empty( $attachment_ids ) ) {
		$attachment_ids = $product->get_gallery_image_ids();
	}
	
	if ( $attachment_ids || $has_video || $force_check ) {
		
		$loop       = 0;
		$columns    = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
		$res_slides = etheme_get_option( 'count_slides', 4 ) ? etheme_get_option( 'count_slides', 4 ) : 4;
		$class      = ( ! $vertical_slider ) ? 'swiper-container swiper-control-bottom ' : 'vertical-thumbnails-wrapper';
		$class      .= ' columns-' . $columns;
		$class      .= ( count( $attachment_ids ) + 1 <= $res_slides ) ? ' no-arrows' : '';
		$class      .= ( ! $gallery_slider ) ? ' noslider' : ' slider';
		$class      .= ( $force_check && ! $vertical_slider ) ? ' dt-hide mob-hide' : '';
		
		?>
        <div class="<?php echo esc_attr( $class ); ?>" <?php if ( ! $vertical_slider ) : ?>data-breakpoints="1"
             data-xs-slides="<?php echo ( etheme_get_option( 'responsive_slides' ) ) ? etheme_get_option( 'responsive_slides' ) : 3 ?>"
             data-sm-slides="<?php echo esc_attr( $res_slides ); ?>"
             data-md-slides="<?php echo esc_attr( $res_slides ); ?>"
             data-lt-slides="<?php echo esc_attr( $res_slides ); ?>"
             data-slides-per-view="<?php echo esc_attr( $res_slides ); ?>" data-clickedslide="1"
             data-spaceBetween="10" <?php endif; ?>>
			<?php etheme_loader(); ?>
            <ul
                    class="<?php echo esc_attr( $ul_class ); ?>"
				<?php if ( $vertical_slider ) { ?>
                    data-slick-slides-per-view="<?php echo esc_attr( $res_slides ); ?>"
				<?php } ?>>
				
				<?php
				
				if ( count( $attachment_ids ) > 0 || $has_video || $force_check ) {
					$type_slider = ( $vertical_slider ) ? 'slick-slide' : 'swiper-slide';
					$classes     = array( 'zoom' );
					
					$image       = wp_get_attachment_image( $main_attachment_id, $thums_size );
					$image_class = esc_attr( implode( ' ', $classes ) );
					$image_title = esc_attr( get_the_title( $main_attachment_id ) );
					
					
					$image_link = wp_get_attachment_image_src( $main_attachment_id, 'full' );
					
					list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( $main_attachment_id, "woocommerce_single" );
					list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( $main_attachment_id, "shop_magnifier" );
					
					if ( $video_position == 'first') :
						if ( etheme_get_external_video( $product->get_id() ) ): ?>
                            <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                                <a href="#product-video-popup" class="open-video-popup">
                                    <span class="et-icon et-play-button"></span>
                                    <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                                </a>
                            </li>
						<?php endif; ?>
						
						<?php if ( count( $video_attachments ) > 0 ): ?>
                        <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                            <a href="#product-video-popup" class="open-video-popup">
                                <span class="et-icon et-play-button"></span>
                                <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                            </a>
                        </li>
					<?php endif;
					endif;
					
					if ( has_post_thumbnail( $post_id ) ) {
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li class="' . $type_slider . ' thumbnail-item %s"><a class="pswp-additional %s" title="%s" data-small="%s" data-large="%s" data-width="%s" data-height="%s">%s</a></li>', $image_class, $image_class, $image_title, $thumbnail_url, $image_link[0], $image_link[1], $image_link[2], $image ), $post_id, $post_id, $image_class );
					}
				}
				
				if ( ! $force_check ) {
					foreach ( $attachment_ids as $attachment_id ) {
						$classes = array( 'zoom' );
						
						$image_link = wp_get_attachment_image_src( $attachment_id );
						
						if ( ! $image_link ) {
							continue;
						}
						
						$image       = wp_get_attachment_image( $attachment_id, $thums_size );
						$image_class = esc_attr( implode( ' ', $classes ) );
						$image_title = esc_attr( get_the_title( $attachment_id ) );
						$image_link  = wp_get_attachment_image_src( $attachment_id, 'full' );
						
						list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( $attachment_id, "woocommerce_single" );
						list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( $attachment_id, "shop_magnifier" );
						
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li class="' . $type_slider . ' thumbnail-item %s"><a data-large="%s" data-width="%s" data-height="%s" class="pswp-additional %s" title="%s" data-small="%s">%s</a></li>', $image_class, $image_link[0], $image_link[1], $image_link[2], $image_class, $image_title, $thumbnail_url, $image ), $attachment_id, $post_id, $image_class );
						$loop ++;
					}
				}
				
				if ( $video_position == 'last') :
					if ( etheme_get_external_video( $product->get_id() ) ): ?>
                        <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                            <a href="#product-video-popup" class="open-video-popup">
                                <span class="et-icon et-play-button"></span>
                                <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                            </a>
                        </li>
					<?php endif; ?>
					
					<?php if ( count( $video_attachments ) > 0 ): ?>
                    <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                        <a href="#product-video-popup" class="open-video-popup">
                            <span class="et-icon et-play-button"></span>
                            <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                        </a>
                    </li>
				<?php endif;
				endif; ?>

            </ul>
			<?php if ( $vertical_slider ) { ?>
                <div class="swiper-vertical-navig"></div> <?php }
			if ( ( count( $attachment_ids ) > 0 || $has_video || $force_check ) && ! $vertical_slider ) { ?>
                <div class="swiper-custom-left thumbnails-bottom"></div>
                <div class="swiper-custom-right thumbnails-bottom"></div>
			<?php } ?>

        </div>
		<?php
	}
}

if  ($is_yith_wcbm_frontend){
	add_filter( 'woocommerce_single_product_image_thumbnail_html', array( YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product_thumbnail' ), 99 );
}

if ( !$with_ajax && ! isset( $etheme_global['quick_view'] ) ): ?>
    </div>
<?php endif;