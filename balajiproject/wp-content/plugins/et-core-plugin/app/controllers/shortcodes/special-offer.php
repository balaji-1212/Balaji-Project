<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Special Offer shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Special_Offer extends Shortcodes {

	function hooks() {}

	function offer_shortcode( $atts, $content ) {
        if ( parent::woocommerce_notice() || xstore_notice() )
            return;

		global $woocommerce_loop;

		$atts = shortcode_atts(array(
			'include'  => '',
			'items_per_page'  => 10,
			'hover' => 'disable',
			'hide_img' => false,
			'img_size' => 'medium',
			'is_preview' => isset($_GET['vc_editable']),
			'dis_type' => 'type1',
			'product_stock_color_step1' => '',
			'product_stock_color_step2' => '',
			'product_stock_color_step3' => '',
		), $atts);

		$atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);

		$options = array();

		$options['quick_view'] = function_exists('etheme_get_option') && etheme_get_option('quick_view',1);

		if ( ! in_array( $atts['img_size'], array(  'thumbnail', 'medium', 'large', 'full' ) ) ) {
			$options['size'] = explode( 'x', $atts['img_size'] );
		} else {
			$options['size'] = $atts['img_size'];
		}

		$options['paged'] = (get_query_var('page')) ? get_query_var('page') : 1;

		$options['wp_query_args'] = array(
			'post_type' => array('product', 'product_variation'),
			'status' => 'published',
			'paged' => $options['paged'],  
			'posts_per_page' => $atts['items_per_page'],
			'orderby' => 'date',
			'order'  => 'DESC',
			// 'meta_key'  => '',
			// 'post__not_in' => explode(',', $atts['exclude'])

		);

		if( $atts['include'] != '' ) {
			$options['wp_query_args']['post__in'] = explode(',', $atts['include']);
			$options['wp_query_args']['orderby'] = 'post__in';
		}

		$options['el_class'] = array();

		$options['box_id'] = rand( 1000,10000 );

		$options['products'] = new \WP_Query( $options['wp_query_args'] );

		$options['view'] = function_exists('etheme_get_option') ? etheme_get_option('product_view', 'disable') : 'default';
		$options['view_color'] = function_exists('etheme_get_option') ? etheme_get_option('product_view_color', 'white') : 'dark';

		if ( ! empty( $woocommerce_loop['product_view'] ) )
			$options['view'] = $woocommerce_loop['product_view'];

		if ( ! empty( $woocommerce_loop['product_view_color'] ) )
			$options['view_color'] = $woocommerce_loop['product_view_color'];

		$options['shop_url'] = get_permalink(wc_get_page_id('shop'));

		$options['type2'] = $atts['dis_type'] == 'type2';

		if ( $atts['hide_img'] ) 
			$options['el_class'][] = 'no-image';

		$options['el_class'][] = $atts['dis_type'];
		$options['el_class'][] = 'slider-'.esc_attr($options['box_id']);
		
		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'et-offer' );
			// if not elementor
			etheme_enqueue_style( 'wpb-et-offer' );
		}
		
		ob_start();
		
		if ( $options['products']->have_posts() ) : ?>
			<div class="et-offer <?php echo implode(' ', $options['el_class']); ?> clearfix">
				<?php 

				while ( $options['products']->have_posts() ) : 
					$options['products']->the_post(); 

					$local_options = array();

					$local_options['id'] = get_the_ID();
					$local_options['thumb_id'] = get_post_thumbnail_id();
					$local_options['url'] = get_the_permalink();
					$local_options['the_title'] = get_the_title();

					$local_options['wishlist_btn'] = etheme_wishlist_btn();

					$local_options['stock_line'] = '';
					if ( $options['type2'] && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
						$local_options['product'] = wc_get_product($local_options['id']);
						$local_options['stock_line'] = et_product_stock_line($local_options['product']);
					}

					$local_options['product_view'] = etheme_get_custom_field('product_view_hover');

					if ( $local_options['product_view'] && $local_options['product_view'] != 'inherit' ) 
						$options['view'] = $local_options['product_view'];

					$local_options['product_view_color'] = etheme_get_custom_field('product_view_color');

					if ( $local_options['product_view_color'] && $local_options['product_view_color'] != 'inherit' ) 
						$options['view_color'] = $local_options['product_view_color'];

                   	$local_options['hover_slider'] = in_array($atts['hover'], array('slider', 'carousel'));

                   	$local_options['view_mask3'] = $options['view'] == 'mask3';
                	$local_options['view_light'] = $options['view'] == 'light';

					$local_options['classes'] = get_post_class();
					$local_options['classes'][] = 'product-hover-' . $atts['hover'];
					$local_options['classes'][] = 'product-view-' . $options['view'];
					$local_options['classes'][] = 'view-color-' . $options['view_color'];
					if ( $local_options['hover_slider'] ) $local_options['classes'][] = 'arrows-hovered'; 

					$local_options['html'] = array(
						'title' => '<p class="product-title"><a href="' . $local_options['url'] . '">' . $local_options['the_title'] . '</a></p>',
						'quick_view' => ( $options['quick_view'] ) ?
                    	'<span class="show-quickly" data-prodid="' . esc_attr($local_options['id']) . '">' . esc_html__('Quick View', 'xstore-core') . '</span>' : ''
					);

					?>

					<div <?php post_class($local_options['classes']); ?>>
						<div class="content-product">
							<?php
	                      	/**
	                       	* woocommerce_before_shop_loop_item hook.
	                       	*
	                       	* @hooked woocommerce_template_loop_product_link_open - 10
	                       	*/
	                      	do_action( 'woocommerce_before_shop_loop_item' );

	                      	// ! Remove image from title action
	                      	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	                      	?>

	                      	<?php if ( !$options['type2'] ) : 

	                      		etheme_product_cats(); 
	                      		echo $local_options['html']['title'];
	                      		woocommerce_template_loop_rating();

	                  		endif; 

	                  		if ( !$atts['hide_img'] ) : ?>

		                      	<div class="product-image-wrapper hover-effect-<?php echo esc_attr( $atts['hover'] ); ?>">

		                      		<?php etheme_product_availability(); 

		                      		if ( $local_options['hover_slider'] ) 
		                      			echo '<div class="images-slider-wrapper">'; 
									?>

		                      		<a class="product-content-image" href="<?php echo $local_options['url']; ?>" data-images="<?php echo 'slider' == $local_options['hover'] ? etheme_get_image_list( $options['size'] ) : ''; ?>">
		                      			<?php if ( $atts['hover'] == 'swap' ) 
		                      				etheme_get_second_image( $options['size'] );

		                      				if ( $local_options['thumb_id'] && function_exists('etheme_get_image') )
		                      					echo etheme_get_image( $local_options['thumb_id'], $options['size'] ); 
		                      				else 
		                      					echo wc_placeholder_img();

                                        // set required attributes for hover effect
                                        if ('carousel' == $local_options['hover']) {
                                            $carousel_images = etheme_get_image_list($options['size'], false);
                                            add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) use ($carousel_images) {
                                                if ($carousel_images) {
                                                    $attr['data-hover-slides'] = str_replace(';', ',', $carousel_images);
                                                    $attr['data-options'] = '{"touch": "end", "preloadImages": true }';
                                                }
                                                return $attr;
                                            }, 10, 3);
                                        }
                                        do_action( 'woocommerce_before_shop_loop_item_title' );
                                        // reset required attributes for hover effect
                                        if ('carousel' == $local_options['hover']) {
                                            add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
                                                if (isset($attr['data-hover-slides']))
                                                    unset($attr['data-hover-slides']);
                                                if (isset($attr['data-options']))
                                                    unset($attr['data-options']);
                                                return $attr;
                                            }, 10, 3);
                                        }
                                        ?>
		                      		</a>
		                      		<?php if ( $local_options['hover_slider'] ) echo '</div>';

		                      		if ( $options['view'] == 'info' ): ?>
			                          	<div class="product-mask">

			                          		<?php echo $local_options['html']['title']; ?>

			                          		<?php
			                                    /**
			                                     * woocommerce_after_shop_loop_item_title hook
			                                     *
			                                     * @hooked woocommerce_template_loop_rating - 5
			                                     * @hooked woocommerce_template_loop_price - 10
			                                     */

			                                	do_action( 'woocommerce_after_shop_loop_item_title' );
			                                ?>
			                            </div>
		                            <?php endif;

		                            if ( in_array( $options['view'], array( 'mask', 'mask2', 'mask3', 'default', 'info' ) ) ) : ?>
		                            	<footer class="footer-product">

		                        			<?php 
			                        			if ( $local_options['view_mask3'] )
			                        				echo $local_options['wishlist_btn'];

			                        			else 
			                        				echo $local_options['html']['quick_view'];

			                        			if ( $options['view'] != 'default' ) 
			                        				do_action( 'woocommerce_after_shop_loop_item' );

			                        			if ( $local_options['view_mask3'] )
			                        				echo $local_options['html']['quick_view'];

		                        				elseif ( $options['view'] != 'default' )
		                        					echo $local_options['wishlist_btn'];
	                        				?>

		                        		</footer>
		                        	<?php endif; ?>
		                    	</div>
	                    	<?php endif; // to show image ?>

	                        <div class="product-details <?php echo ( $options['type2'] ) ? 'text-center' : ''; ?>">
								<?php 
	                        	if ( $options['type2'] ) {
	                        		if ( $local_options['view_light'] ) : ?>
	                        			<div class="light-right-side">
	                        				<?php 
		                        				echo $local_options['html']['quick_view'];
		                        				echo $local_options['wishlist_btn']; 
	                        				?>
	                        			</div><!-- .light-right-side -->
	                        		<?php endif; // $local_options['view_light'] 

	                        		if ( $local_options['view_light'] ) 
	                        			echo '<div class="light-left-side">';

	                        		etheme_product_cats(); 
	                        		echo $local_options['html']['title'];
                            		woocommerce_template_loop_rating();
                            	 	woocommerce_template_loop_price();
                            		echo $local_options['stock_line']; 

		                        	if ( $local_options['view_light'] )
		                        		echo '</div>';
		                        }
	                        	else {
	                        		woocommerce_template_loop_price();
	                        	}

	                        	etheme_product_countdown($atts['dis_type'], false);

	                        	if ( $options['type2'] ) : ?>
	                        		<a class="btn medium active" href="<?php echo $local_options['url']; ?>"><?php esc_html_e('Shop now', 'xstore-core'); ?></a>
	                        	<?php endif; ?>
	                        </div><!-- .product-details -->
	                        <?php add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 ); ?>
	                    </div><!-- .content-product -->
	                </div>
	            <?php unset($local_options);
	            endwhile; // end of the loop. 

                unset($woocommerce_loop['columns']); 
                unset($woocommerce_loop['isotope']); 
                unset($woocommerce_loop['size']); 
                unset($woocommerce_loop['product_view']); 
                unset($woocommerce_loop['product_view_color']); 
                ?>
            </div> <?php // .et-offer ?>
	
			<?php 
				if ( $options['type2'] && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {

					$options['output_css'] = array();

					$options['css'] = array(
						'slider_item' => array()
					);

					if ( $atts['product_stock_color_step1'] ) 
						$options['css']['slider_item'][] = '--product-stock-step-1-active-color: '.$atts['product_stock_color_step1'];
					if ( $atts['product_stock_color_step2'] )
						$options['css']['slider_item'][] = '--product-stock-step-2-active-color: '.$atts['product_stock_color_step2'];
					if ( $atts['product_stock_color_step3'] )
						$options['css']['slider_item'][] = '--product-stock-step-3-active-color: '.$atts['product_stock_color_step3'];

					$options['output_css'][] = '.slider-'.$options['box_id'].' {' . implode(';', $options['css']['slider_item']) . '}';

					if ( $atts['is_preview'] ) 
						echo parent::initPreviewCss($options['output_css']);
					else 
						parent::initCss($options['output_css']);
				} 

			if ( $atts['is_preview'] ) 
				echo parent::initPreviewJs();
			?>

        <?php endif; // $options['products']->have_posts()

        wp_reset_postdata();

        $output = ob_get_clean();
        unset($options);
        unset($atts);
        
        return $output;
    }
}
