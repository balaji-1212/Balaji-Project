<?php
/**
 * The Order Item Meta functionality.
 * Front end: Order review
 * Back end: Order
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/public/partials
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_OrderItem extends St_Woo_Swatches_Base {

	/**
	 * The public directory path url of a plugin.
	 */
	protected $plugin_public_dir_path_url;

	public function __construct( array $args = array() ) {

		parent::__construct();

		// if (!empty($args)) {
		// 	foreach ($args as $property => $arg) {
  //               $this->{$property} = $arg;
  //           }
  //       }		

		//add_filter( 'woocommerce_order_item_display_meta_value', array( &$this, 'order_item_display_meta_value' ), 10, 2 );

		//add_filter( 'woocommerce_order_item_class', array( &$this, 'add_extra_class' ),10, 3 );

		//add_filter( 'woocommerce_email_styles', array( &$this, 'term_swatch_html_css' ) );
	}

	function order_item_display_meta_value( $display_value, $meta ) {

		$taxonomy  = str_replace( 'attribute_', '', $meta->key );

		if ( taxonomy_exists( $taxonomy ) ) {

			$attribute = $this->get_tax_attribute( $taxonomy );

			if( array_key_exists( $attribute->attribute_type,  $this->attribute_types ) ) {

				// If this is a term slug, get the term's nice name.
				$term      = get_term_by( 'slug', $meta->value, $taxonomy );

				// Filter item meta data to allow swatch details to set in display value.
				$display_value = $term->name;				
			}
		}

		return $display_value;
	}

	function add_extra_class( $class, $item, $order ) {

		$class = $class .' '. 'st-item-meta';
		return $class;
	}

	function term_swatch_html_css( $css ) {

		$css .= '.st-item-meta .st-swatch-preview { 
			display: inline-block;
			margin: 0 5px;
			border: 1px solid #dddddd;
			padding: 2px;
			cursor: pointer;	
			position: relative;
			border-radius: 26px;
			width: 26px;
			height: 26px;
			text-align: center;
			line-height: 26px;
		}';

		$css .= '.st-item-meta .st-swatch-preview span {
			display: inline-block;
			border-radius: 26px;
			width: 26px;
			height: 26px;
			font-size: 14px;
		}';

		$css .= '.st-item-meta .st-swatch-preview span img {
			width: 26px;
			height: 26px;
			border-radius: 26px;
		}';

		$css .= '.wc-item-meta li strong {
			display: inline-block;
		}';
		$css .= '.wc-item-meta li p {
			display: inline-block;
			position: relative;
			top: 9px;
		}';

		return $css;
	}
}