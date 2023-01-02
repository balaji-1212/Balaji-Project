<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Brands List shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Brands_List extends Shortcodes {
	
	public function hooks() {}
	
	function brands_list_shortcode( $atts ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;
		
		if ( function_exists('etheme_get_option') && ! etheme_get_option( 'enable_brands', 1 ) )
			return '<div class="woocommerce-info">'.esc_html__('Enable brands in Theme options -> Shop elements -> Brands to use this element', 'xstore-core').'</div>';
		
		$atts = shortcode_atts( array(
			'ids'               	=>	false,
			'columns'           	=>	'',
			'hide_a_z'          	=>	false,
			'alignment'         	=>	'left',
			'capital_letter'    	=>	false,
			'display_type'      	=>	'',
			'brand_title'       	=>	'yes',
			'brand_image'       	=>	false,
			'brand_desc'        	=>	false,
			'tooltip'           	=>	false,
			'hide_empty'        	=>	false,
			'show_product_counts' 	=>	false,
			'size' 					=>	'',
			'class'      			=>	'',
			'is_preview'			=> false
		), $atts );
		
		$options = array();
		
		$options['terms_args'] = array(
			'hide_empty' => $atts['hide_empty']
		);
		
		if ( $atts['ids'] )
			$options['terms_args']['include'] = explode( ',' , $atts['ids'] );
		
		$options['product_brands'] = get_terms( 'brand', $options['terms_args'] );
		
		$options['brands'] = array();
		
		foreach ( $options['product_brands'] as $brand ) {
			
			$options['firstLetter'] = strtoupper( mb_substr( $brand->name, 0, 1, 'UTF-8' ) );
			
			$options['brand_obj'] = (object)array(
				'id' => $brand->term_id,
				'name' => $brand->name,
				'desc' => $brand->description,
				'count' => $brand->count
			);
			
			$options['brands'][$options['firstLetter']][] = $options['brand_obj'];
			
			unset($options['firstLetter']);
			unset($options['brand_obj']);
		}
		
		if ( ! $atts['size'] )
			$atts['size'] = array(50,50);
        elseif ( strpos($atts['size'], 'x') != false )
			$atts['size'] = explode( 'x', $atts['size'] );
		
		wp_enqueue_script( 'et_isotope');
		
		if ( function_exists('etheme_enqueue_style')) {
			if ( $atts['hide_a_z'] ) {
				etheme_enqueue_style( 'isotope-filters' );
			}
			etheme_enqueue_style('brands-list', true);
		}
		
		ob_start();
		
		?>

        <div class="container brands-list">
			
			<?php if ( $atts['hide_a_z'] ) : ?>

                <ul class="brands-filters et-masonry-filters-list">

                    <li>
                        <a href="#" data-filter="*" class="filter-btn active">
							<?php esc_html_e( 'All', 'xstore-core' ); ?>
                        </a>
                    </li>
					
					<?php foreach ( $options['brands'] as $letter => $value ) : ?>
                        <li>
                            <a href="#" data-filter=".<?php echo esc_attr( $letter ); ?>" class="filter-btn">
								<?php echo esc_html( $letter ); ?>
                            </a>
                        </li>
					<?php endforeach; ?>

                </ul>
			
			<?php endif; ?>

            <div class="et-masonry-filters et-isotope brand-list">
				
				<?php $options['i'] = 0; ?>
				
				<?php foreach ( $options['brands'] as $letter => $value ) {
					
					$options['class'] = '';
					
					switch ( $atts['columns'] ) {
						case 1:
							$options['class'] = 'col-md-12 col-sm-12 col-xs-12';
							break;
						case 2:
							$options['class'] = 'col-md-6 col-sm-6 col-xs-12';
							break;
						case 3:
							$options['class'] = 'col-md-4 col-sm-6 col-xs-12';
							break;
						case 4:
							$options['class'] = 'col-md-3 col-sm-3 col-xs-12';
							break;
						case 5:
							$options['class'] = 'еt_col-5 col-sm-3 col-xs-12';
							break;
						case 6:
							$options['class'] = 'col-md-2 col-sm-3 col-xs-12';
							break;
						default:
					}
					
					$options['class'] .= ' ' . $letter;
					if ( $options['i'] < 1) {
						$options['class'] .= ' grid-sizer';
					}
					
					?>

                    <div class="et-isotope-item brand-list-item <?php echo esc_attr($options['class']);?>">
						
						<?php if ( $atts['capital_letter'] ) { ?>

                            <div class="firstLetter text-<?php echo esc_attr($atts['alignment']); ?>">
								<?php echo esc_html($letter); ?>
                            </div>
						
						<?php }
						
						foreach ($value as $brand) { ?>

                            <div class="work-item text-<?php echo esc_attr($atts['alignment']); ?>">
								
								<?php
								
								$options['thumb_id'] = absint( get_term_meta( $brand->id, 'thumbnail_id', true ) );
								$options['image'] = wp_get_attachment_image_url( $options['thumb_id'], $atts['size'] );
								
								if ( $atts['brand_image'] && $options['image'] ) { ?>

                                    <a href="<?php echo esc_url( get_term_link( $brand->id ) ); ?>">
                                        <img src="<?php echo esc_url($options['image']); ?>" alt="<?php echo esc_attr($brand->name); ?>" class="brand-img">
                                    </a>
								
								<?php } ?>

                                <div class="vertical-align full">

                                    <a href="<?php echo esc_url( get_term_link( $brand->id ) ); ?>" class="brand-desc">
										
										<?php if ( $atts['brand_title'] ) : ?>

                                            <h4 class="title">
												
												<?php
												
												echo esc_html($brand->name);
												
												if ( $atts['show_product_counts'] )
													echo '<span class="colorGrey">' . esc_html('('.$brand->count.')') . '</span>';
												
												if ( $atts['tooltip'] && $options['image'] ) { ?>

                                                    <div class="brand-tooltip">

                                                        <img src="<?php echo esc_url($options['image']); ?>" alt="<?php echo esc_attr($brand->name); ?>" class="brand-img-tooltip">
                                                        <div class="sub-title colorGrey">
															<?php echo wp_specialchars_decode($brand->desc); ?>
                                                        </div>

                                                    </div>
												
												<?php } ?>
                                            </h4>
										
										<?php endif; ?>

                                    </a>
									
									<?php if ( $atts['brand_desc'] )
										echo '<div class="sub-title">' . wp_specialchars_decode($brand->desc) . '</div>';
									?>
                                </div>

                            </div> <?php // .work-item ?>
						
						<?php } ?>

                    </div> <?php // .portfolio-item ?>
					
					<?php $options['i']++; } ?>

            </div> <?php // .brand-list ?>

        </div>	<?php // .container ?>
		
		<?php
		
		if ( $atts['is_preview'] )
			echo parent::initPreviewJs();
		
		unset($atts);
		unset($options);
		
		return ob_get_clean();
		
	}
}