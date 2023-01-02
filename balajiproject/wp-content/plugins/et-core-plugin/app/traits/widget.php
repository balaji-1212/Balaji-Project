<?php
namespace ETC\App\Traits;

/**
 * Widget Trait
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Core/Registry
 */
trait Widget {

	public static function widget_label( $label, $id ) {
		echo "<label for='{$id}'>{$label}</label>";
	}

	public static function widget_input_checkbox( $label, $id, $name, $checked, $value = 1 ) {
		echo "\n\t\t\t<p>";
		echo "<label for='{$id}'>";
		echo "<input type='checkbox' id='{$id}' value='{$value}' name='{$name}' {$checked} /> ";
		echo "{$label}</label>";
		echo '</p>';
	}

	public static function widget_textarea( $label, $id, $name, $value ) {
		echo "\n\t\t\t<p>";
		self::widget_label( $label, $id );
		echo "<textarea id='{$id}' name='{$name}' rows='3' cols='10' class='widefat'>" . ( $value ) . "</textarea>";
		echo '</p>';
	}

	public static function widget_input_text( $label, $id, $name, $value ) {
		echo "\n\t\t\t<p>";
		self::widget_label( $label, $id );
		echo "<input type='text' id='{$id}' name='{$name}' value='" . strip_tags( $value ) . "' class='widefat' />";
		echo '</p>';
	}

	public static function widget_input_image( $label, $id, $name, $value ) {
		$out = "\n\t\t\t<p>";
		self::widget_label( $label, $id );

		$class = ( $value ) ? 'selected' : '' ;

		$out .= '<div class="media-widget-control ' . $class . '">';
		$out .= '<div class="media-widget-preview etheme_media-image">';
		if ( $value ) {
			$out .= '<img class="attachment-thumb etheme_upload-image" src="' . $value . '">';
		} else {
			$out .= '<div class="attachment-media-view">';
			$out .= '<div class="placeholder etheme_upload-image">' . esc_html__( 'No image selected', 'xstore-core' ) . '</div>';
			$out .= '</div>';
		}
		$out .= '</div>';
		$out .= '<p class="media-widget-buttons">';
		if ( $value ) {
						//$out .= '<button type="button" class="button edit-media selected">Edit Image</button>';
			$out .= '<button type="button" class="button change-media select-media etheme_upload-image selected">' . esc_html__( 'Replace Image', 'xstore-core' ) . '</button>';
		} else {
			$out .= '<button type="button" class="button etheme_upload-image not-selected">' . esc_html__( 'Add Image', 'xstore-core' ) . '</button>';
		}
		$out .= '</p>';
		$out .= '<input type="hidden" id="' . $id . '" name="' . $name . '" value="' . strip_tags( $value ) . '" class="widefat" />';
		$out .= '</div>';
		$out .= '</p>';
		echo $out;
	}

	public static function widget_input_dropdown( $label, $id, $name, $value, $options ) {
		echo "\n\t\t\t<p>";
		self::widget_label( $label, $id );
		echo "<select id='{$id}' name='{$name}' class='widefat'>";
		$val = current( array_flip( $options ) );
		if( ! empty( $val ) ) echo '<option value=""></option>';
		foreach ($options as $key => $option) {
			echo '<option value="' . $key . '" ' . selected( strip_tags( $value ), $key ) . '>' . $option . '</option>';
		}
		echo "</select>";
		echo '</p>';
	}

	public static function etheme_stock_taxonomy( $term_id = false, $taxonomy = 'product_cat', $category = false, $stock = true ) {

		if ( $term_id === false ) return false;

		$type = apply_filters('etheme_stock_taxonomy_type', 'new');

		$args = array(
			'post_type'         => 'product',
			'posts_per_page'    => -1,
			'tax_query'         => array(
				array(
					'taxonomy'  => $taxonomy,
					'field'     => 'term_id',
					'terms'     => $term_id
				),

			),

		);


		// ! new meta
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ){
			$args['meta_query'][] = array(
				'key' => '_stock_status',
				'value' => 'instock'

			);
		}


		if ( $category ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'slug',
				'terms'            => $category,
				'include_children' => true,
				'operator'         => 'IN'

			);
		}


		if ($type == 'new') {
			$query = new \WP_Query( $args );
			return (int)$query->post_count;
		} else {
			// ! old meta

			$cat_prods = get_posts( $args );
			$i = 0;
			foreach ( $cat_prods as $single_prod ) {
				$product = wc_get_product( $single_prod->ID );
				if ( ! $stock ) {
					$i++;
				} elseif( $product->is_in_stock() === true ){

					$i++;
				}

			}
			return $i;
		}
		return 0;
	}

	// @todo Add pretty custom preview (title, image, description ...)
	public static function admin_widget_preview($name) {
		if (isset($_GET['legacy-widget-preview']) || defined( 'REST_REQUEST' ) && REST_REQUEST){
			echo '<div class="et-no-preview"><h3>8theme - '. $name .'</h3><p>No preview available.</p></div>';
			echo '<style>
                .et-no-preview{
                    font-size: 13px;
                    background: #f0f0f0;
                    padding: 8px 12px;
                    color: #000;
                    font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif;
                }
                .et-no-preview h3 {
                    font-size: 14px;
                    font-family: inherit;
                    font-weight: 600;
                    margin: 4px 0;
                }
                .et-no-preview p {
                    margin: 4px 0;
                    font-size: 13px;
                }
            </style>';
		} else {
			return false;
		}
	}

	public static function etheme_widget_title($args, $instance){
		$title = apply_filters( 'widget_title', ( $instance['title'] ?? '' ) );
		if ( $title ) {
			return $args['before_title'] . $title . $args['after_title'];
		}
		return '';
	}
}
