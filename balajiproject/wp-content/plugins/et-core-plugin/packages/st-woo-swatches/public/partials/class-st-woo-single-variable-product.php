<?php
/**
 * The single variable product public facing functionality.
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/public/partials
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Single_Variable_Product extends St_Woo_Swatches_Base {

	/**
	 * The public directory path url of a plugin.
	 */
	protected $plugin_public_dir_path_url;


	public function __construct( array $args = array() ) {

		parent::__construct();

		if (!empty($args)) {
			foreach ($args as $property => $arg) {
                $this->{$property} = $arg;
            }
        }

		add_action( 'woocommerce_before_variations_form', array( &$this, 'start_capture' ) );
		add_action( 'woocommerce_after_variations_form', array( &$this, 'stop_capture' ) );

		add_filter( 'sten_wc_attribute_html', array( &$this, 'swatch_html' ), 10, 5 );		
	}

	/**
	 *  Start capturing the variation form
	 */
	public function start_capture() {
		ob_start();
	}

	/**
	 * Stop capturing and print the variation form
	 */
	public function stop_capture() {
		global $product;

		$form = ob_get_contents();

		if( $form ) {

			ob_end_clean();
		}

		if ( $product->is_type( 'variable' ) ) {

			$attributes = $product->get_variation_attributes();

			foreach( array_keys( $attributes ) as $taxonomy ) {

				$attribute = $this->get_tax_attribute( $taxonomy );

				if ( ! $attribute ) {
					continue;
				}

				if( array_key_exists( $attribute->attribute_type,  $this->attribute_types ) ) {

					$available_options = $attributes[$taxonomy];

					// Get terms if this is a taxonomy - ordered. We need the names too.
					$terms    = wc_get_product_terms( $product->get_id(), $taxonomy, array( 'fields' => 'all' ) );

					// Generate request variable name.
					$key      = 'attribute_' . sanitize_title( $taxonomy );
					$selected = isset( $_REQUEST[ $key ] ) ? wc_clean( $_REQUEST[ $key ] ) : $product->get_variation_default_attribute( $taxonomy );

					$swatch = apply_filters( 'sten_wc_attribute_html', $attribute->attribute_type, $taxonomy, $terms, $available_options, $selected  );
				
					// update variation form
					$form = preg_replace(
						'/<select id="(' . $taxonomy . ')" class="([^"]*)" name="([^"]+)" data-attribute_name="([^"]+)"[^>]*>/',
						$swatch . '<select id="\\1" class="\\2" name="\\3" data-attribute_name="\\4" style="display: none;">',
						$form
					);

					$form = apply_filters( 'et_single_overwrite_select_swatches', $form, $swatch, $taxonomy );
				}
			}
		}

		print( $form );
	}

	/**
	 * Print HTML of swatches
	 */
	public function swatch_html( $attribute_type, $taxonomy, $terms, $variations, $selected ) {
		$html = '';
		$custom_class = '';
		$custom_class .= 'st-swatch-size-large';
		$subtype      = '';
		
		$sw_shape = get_theme_mod('swatch_shape', 'default');
		$sw_custom_shape = $sw_shape != 'default' ? $sw_shape : false;

		if ( strpos( $attribute_type, '-sq') !== false ) {
			$et_attribute_type = str_replace( '-sq', '', $attribute_type );
			if ( !$sw_custom_shape || $sw_custom_shape == 'square' ) {
				$custom_class .= ' st-swatch-shape-square';
				$subtype      = 'subtype-square';
			}
			else if ( $sw_custom_shape == 'circle' ) {
				$custom_class .= ' st-swatch-shape-circle';
			}
		} else {
			$et_attribute_type = $attribute_type;
			if ( !$sw_custom_shape || $sw_custom_shape == 'circle' ) {
				$custom_class .= ' st-swatch-shape-circle';
			}
		}
		
		$sw_design = get_theme_mod('swatch_design', 'default');
		$sw_disabled_design = get_theme_mod('swatch_disabled_design', 'line-thought');
		
		if ( $sw_design != 'default' )
			$custom_class .= ' st-swatch-'.$sw_design;
		
		if ( $sw_disabled_design != 'default' )
			$custom_class .= ' st-swatch-disabled-'.$sw_disabled_design;

		switch ( $et_attribute_type ) {

			case 'st-color-swatch':
				if( $terms ) {

					$html .= sprintf(
						'<ul class="st-swatch-preview st-swatch-preview-single-product st-color-swatch %1$s" data-attribute="%2$s">',
						esc_attr( $custom_class ),
						sanitize_title( $taxonomy )
					);

					foreach( $terms as $term ) {

						if ( in_array( $term->slug, $variations, true ) ) {

							$color = get_term_meta( $term->term_id, 'st-color-swatch', true );

							$class = ( $selected == $term->slug ) ? 'selected' : '';
							$class .= ( $color == '#ffffff' || $color == '#fcfcfc' || $color == '#f7f7f7' || $color == '#f4f4f4'  ) ?  ' st-swatch-white' : '';

							$html .= sprintf(
								'<li class="type-color %5$s %1$s" data-tooltip="%3$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s" 
								style="background-color:%4$s"></span> </li>',
								esc_attr( $class ),
								esc_attr( $term->slug ),
								esc_attr( $term->name ),
								esc_attr( $color ),
								esc_attr( $subtype )
							);
						}
					}
					$html .= sprintf('</ul>');
				}
			break;

			case 'st-label-swatch':
				
				if( $terms ) {

					$html .= sprintf(
						'<ul class="st-swatch-preview st-swatch-preview-single-product st-label-swatch %1$s" data-attribute="%2$s">',
						esc_attr( $custom_class ),
						sanitize_title( $taxonomy )
					);

					foreach( $terms as $term ) {

						if ( in_array( $term->slug, $variations, true ) ) {

							$label = get_term_meta( $term->term_id, 'st-label-swatch', true );
							$label = (!empty($label)) ? $label : $term->name;
							$class = ( $selected == $term->slug ) ? 'selected' : '';

							$html .= sprintf(
								'<li class="type-label %5$s %1$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span> </li>',
								esc_attr( $class ),
								esc_attr( $term->slug ),
								esc_attr( $term->name ),
								esc_attr( $label ),
								esc_attr( $subtype )
							);
						}		
					}
					$html .= sprintf('</ul>');
				}
			break;

			case 'st-image-swatch':
				if( $terms ) {

					$html .= sprintf(
						'<ul class="st-swatch-preview st-swatch-preview-single-product st-image-swatch %1$s" data-attribute="%2$s">',
						esc_attr( $custom_class ),
						sanitize_title( $taxonomy )
					);
					
					foreach( $terms as $term ) {

						if ( in_array( $term->slug, $variations, true ) ) {

							$image = get_term_meta( $term->term_id, 'st-image-swatch', true );
							$class = ( $selected == $term->slug ) ? 'selected' : '';

							$html .= sprintf(
								'<li class="type-image %5$s %1$s" data-tooltip="%3$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span> </li>',
								esc_attr( $class ),
								esc_attr( $term->slug ),
								esc_attr( $term->name ),
								( $image ) ? wp_get_attachment_image( $image ) : '<img src="'.esc_url( $this->plugin_public_dir_path_url . 'images/placeholder.png' ).'"/>',
								esc_attr( $subtype )
							);
						}
					}
					$html .= sprintf('</ul>');
				}
			break;
		}

		return $html;
	}
}