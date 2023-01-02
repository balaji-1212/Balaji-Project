<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The base plugin class.
 *
 * @since      1.0.2
 * @package    St_Woo_Swatches
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Swatches_Base {

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * The basename of a plugin.
	 */
	protected $plugin_basename;

	/**
	 * The directory path of a plugin.
	 */
	protected $plugin_dir_path;

	/**
	 * The directory path url of a plugin.
	 */
	protected $plugin_dir_path_url;

	/**
	 * Additional Product Attribute types
	 */
	protected $attribute_types;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {

		$this->plugin_name         = 'et-woo-swatches'; // st-woo-swatches
		$this->version             = '1.0'; // 1.0.2
		$this->plugin_basename     = plugin_basename( __FILE__ );
		$this->plugin_dir_path     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_path_url = plugin_dir_url( __FILE__ );

		$this->attribute_types     = array(
			'st-color-swatch' => esc_html__( 'Color (circle)', 'xstore-core' ),
			'st-color-swatch-sq' => esc_html__( 'Color (square)', 'xstore-core' ),
			'st-image-swatch' => esc_html__( 'Image (circle)', 'xstore-core' ),
			'st-image-swatch-sq' => esc_html__( 'Image (square)', 'xstore-core' ),
			'st-label-swatch' => esc_html__( 'Label (circle)', 'xstore-core' ),
			'st-label-swatch-sq' => esc_html__( 'Label (square)', 'xstore-core' ),			
		);

		add_filter( 'sten_wc_cart_attribute_html', array( &$this, 'cart_swatch_html' ), 10, 4 );
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
	}

	public function add_extra_class( $class, $cart_item, $cart_item_key ) {

		$class = $class .' '. 'st-item-meta';

		return $class;
	}

	/**
	 * Get attribute's properties
	 */
	public function get_tax_attribute( $taxonomy ) {
		
		global $wpdb;
		$origin_attr = substr( $taxonomy, 3 );
		$attr = get_query_var('et_swatch_tax-'.$origin_attr, false);
		if ( !$attr ) {
			$attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$origin_attr'" );
			set_query_var('et_swatch_tax-'.$origin_attr, $attr);
		}

		return $attr;
	}

	/**
	 * Gets and formats a list of cart item data + variations for display on the frontend.
	 * Cart and Checkout Page
	 */
	public function st_wc_get_formatted_cart_item_data( $cart_item ) {

		$item_data = array();

		// Variation values are shown only if they are not found in the title as of 3.0.
		// This is because variation titles display the attributes.
		if ( $cart_item['data']->is_type( 'variation' ) && is_array( $cart_item['variation'] ) ) {

			foreach ( $cart_item['variation'] as $name => $value ) {

				$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

				if ( taxonomy_exists( $taxonomy ) ) {
					// If this is a term slug, get the term's nice name.
					$term = get_term_by( 'slug', $value, $taxonomy );
					if ( ! is_wp_error( $term ) && $term && $term->name ) {
						$value = $term->name;
					}
					$label = wc_attribute_label( $taxonomy );
				} else {
					// If this is a custom option slug, get the options name.
					$value = apply_filters( 'woocommerce_variation_option_name', $value );
					$label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $cart_item['data'] );
				}

				// Check the nicename against the title.
				if ( '' === $value || wc_is_attribute_in_product_name( $value, $cart_item['data']->get_name() ) ) {
					continue;
				}

				$data = array(
					'key'   => $label,
					'value' => $value,
				);

				// Modified - Included by SThemes
				$attribute = $this->get_tax_attribute( $taxonomy );
				if( array_key_exists( $attribute->attribute_type,  $this->attribute_types ) ) {

					if ( taxonomy_exists( $taxonomy ) ) {

						// If this is a term slug, get the term's nice name.
						$term      = get_term_by( 'slug', $value, $taxonomy );
						$term_id   = $term->term_id;
						$term_name = $term->name;

						// Filter item data to allow swatch details to add to the array.
						$data = apply_filters( 'sten_wc_cart_attribute_html', $data, $attribute->attribute_type, $term_id, $term_name );
					}
				}

				$item_data[] = $data;
			}
		}

		// Filter item data to allow 3rd parties to add more to the array.
		$item_data = apply_filters( 'woocommerce_get_item_data', $item_data, $cart_item );

		// Format item data ready to display
		foreach ( $item_data as $key => $data ) {

			// Set hidden to true to not display meta on cart.
			if ( ! empty( $data['hidden'] ) ) {
				unset( $item_data[ $key ] );
				continue;
			}

			$item_data[ $key ]['key']     = ! empty( $data['key'] ) ? $data['key'] : $data['name'];
			$item_data[ $key ]['display'] = ! empty( $data['display'] ) ? $data['display'] : $data['value'];
		}

		ob_start();

		if ( count( $item_data ) > 0 ) {

			$html = sprintf('<dl class="variation">');
				foreach( $item_data as $data ) {

					$html .= sprintf('<dt class="%1$s">%2$s</dt>',
						esc_attr( sanitize_html_class( 'variation-' . $data['key'] ) ),
						wp_kses_post( $data['key'] )
					);

					if( isset( $data['swatch']) ) {

						$html .= sprintf('<dd class="%1$s">: %2$s</dd><br>',
							esc_attr( sanitize_html_class( 'variation-' . $data['key'] ) ),
							$data['swatch']							
						);
					} else {
						$html .= sprintf('<dd class="%1$s">: %2$s</dd><br>',
							esc_attr( sanitize_html_class( 'variation-' . $data['key'] ) ),
							wp_kses_post( $data['display'] )
						);
					}
				}
			$html .= sprintf('</dl>');

			echo $html;
		}

		return ob_get_clean();

	}

	/**
	 * Print HTML of swatches on the frontend.
	 * Cart and Checkout Page
	 */
	public function cart_swatch_html( $data, $attribute_type, $term_id, $term_name ) {

		$custom_class = '';
		$custom_class .= ' st-swatch-size-small';
		$custom_class .= ' st-swatch-shape-circle';

		switch ( $attribute_type ) {

			case 'st-color-swatch':
				$color  = get_term_meta( $term_id, 'st-color-swatch', true );
				$swatch = sprintf('<span class="st-swatch-preview st-color-swatch %1$s">
						<span class="st-custom-attribute" style="background-color:%2$s"></span>
					</span>',
					esc_attr( $custom_class ),
					esc_attr( $color )
				);
				$data['swatch'] = $swatch;
			break;

			case 'st-label-swatch':
				$label  = get_term_meta( $term_id, 'st-label-swatch', true );
				$swatch = sprintf('<span class="st-swatch-preview st-label-swatch %1$s">
						<span class="st-custom-attribute"> %2$s </span>
					</span>',
					esc_attr( $custom_class ),
					esc_attr( $label )
				);
				$data['swatch'] = $swatch;				
			break;

			case 'st-image-swatch':
				$image  = get_term_meta( $term_id, 'st-image-swatch', true );
				$swatch = sprintf('<span class="st-swatch-preview st-image-swatch %1$s">
						<span class="st-custom-attribute"> %2$s </span>
					</span>',
					esc_attr( $custom_class ),
					( $image ) ? wp_get_attachment_image( $image ) : '<img src="'.esc_url( $this->plugin_dir_path_url . 'public/images/placeholder.png' ).'"/>'
				);
				$data['swatch'] = $swatch;				
			break;			
		}

		return $data;
	}

	/**
	 * UTF8 URL decode
	 *
	 */
	public static function utf8_urldecode( $str ) {

		$str = preg_replace( "/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode( $str ) );

		return html_entity_decode( $str, null, 'UTF-8' );
	}			
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.2
 * @package    St_Woo_Swatches
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Swatches extends St_Woo_Swatches_Base {

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );
		add_filter( 'woocommerce_cart_item_class', array( $this, 'add_extra_class' ),10, 3 );

		parent::__construct();
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $this->plugin_dir_path . 'admin/class-st-woo-swatches-admin.php';
		new St_Woo_Swatches_Admin();

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $this->plugin_dir_path . 'public/class-st-woo-swatches-public.php';
		new St_Woo_Swatches_Public();		
	}
}
$st_woo_swatches = new St_Woo_Swatches();