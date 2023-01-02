<?php
/**
 * The product attributes edit page admin facing functionality.
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/admin/partials
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Product_Attributes extends St_Woo_Swatches_Base {

	/**
	 * The admin directory path of a plugin.
	 */
	protected $plugin_admin_dir_path;

	/**
	 * The admin directory path url of a plugin.
	 */
	protected $plugin_admin_dir_path_url;

	public function __construct( array $args = array() ) {

		parent::__construct();

		if (!empty($args)) {
			foreach ($args as $property => $arg) {
                $this->{$property} = $arg;
            }
        }

		add_filter( 'product_attributes_type_selector', array( &$this, 'add_attribute_types' ) );
		add_action( 'admin_init', array( &$this, 'taxonomies_init' ) );
		add_action( 'sten_wc_attribute_field', array( &$this, 'print_attribute_field' ), 10, 3 );

		add_action( 'created_term', array( &$this, 'save_term_meta' ), 10, 2);
		add_action( 'edit_term', array( &$this, 'save_term_meta' ), 10, 2);
		add_action( 'delete_term', array( &$this, 'delete_term' ), 5 );		
	}

	/**
	 * Add extra Product Attribute types
	 * New Attributes - Color, Image And Label
	 */
	public function add_attribute_types( $types ) {
		$types = array_merge( $this->attribute_types, $types );
		return $types;
	}

	/**
	 * Call admin hooks to add, edit and display taxonomies
	 * Add form field in both add and edit term screen
	 */
	public function taxonomies_init() {

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( empty( $attribute_taxonomies ) ) {
			return;
		}

		foreach( $attribute_taxonomies as $taxonomy ) {

			// Add Form Fields
			add_action('pa_' . $taxonomy->attribute_name. '_add_form_fields', array( &$this, 'add_attribute_fields' ) );
			add_action('pa_' . $taxonomy->attribute_name. '_edit_form_fields', array( &$this, 'edit_attribute_fields' ), 10, 2 );

			// Manage Columns
			add_filter( 'manage_edit-pa_' . $taxonomy->attribute_name . '_columns', array( $this, 'add_attribute_columns' ) );
			add_filter( 'manage_pa_' . $taxonomy->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
		}
	}

	/**
	 * Hook to add fields to add attribute screen
	 */
	public function add_attribute_fields( $taxonomy ) {

		$attribute      = $this->get_tax_attribute( $taxonomy );
		$attribute_type = str_replace( '-sq', '', $attribute->attribute_type );

		do_action( 'sten_wc_attribute_field', $attribute_type, '', 'add' );
	}

	/**
	 * Hook to Add fields to edit attribute screen
	 */
	public function edit_attribute_fields( $term, $taxonomy ) {

		$attribute      = $this->get_tax_attribute( $taxonomy );
		$attribute_type = str_replace( '-sq', '', $attribute->attribute_type );
		$value          = get_term_meta( $term->term_id, $attribute_type, true );

		do_action( 'sten_wc_attribute_field', $attribute_type, $value, 'edit' );
	}

	/**
	 * Prints field on attribute screen
	 */
	public function print_attribute_field( $attribute_type, $value, $mode ) {

		if( in_array( $attribute_type, array( 'select', 'text' ) ) ) {
			return;
		}

		printf( '<%1s class="form-field">%2s<label for="term-%3s">%4s</label>%5s',
			'edit' == $mode ? 'tr' : 'div',
			'edit' == $mode ? '<th>' : '',
			esc_attr( $attribute_type ),
			$this->attribute_types[ $attribute_type ],
			'edit' == $mode ? '</th> <td>' : ''			
		);

		switch( $attribute_type ) {

			case 'st-color-swatch':

				printf(
					'<input type="text" id="term-%1$s" class="st-color-swatch-picker" name="%1$s" value="%2$s"/>',
					esc_attr( $attribute_type ),
					esc_attr( $value )
				);
			break;

			case 'st-color-swatch-sq':

				printf(
					'<input type="text" id="term-%1$s" class="st-color-swatch-picker" name="%1$s" value="%2$s"/>',
					esc_attr( $attribute_type ),
					esc_attr( $value )
				);
			break;

			case 'st-image-swatch':

				printf('<div class="st-image-swatch-image-holder">' );
					if( 'edit' == $mode && !empty( $value ) ) {

						$image = $value ? wp_get_attachment_image_src( $value, array( 100, 100 ) ) : '';
						$image = $image ? $image[0] : '';

						printf('<div class="st-image-swatch-image">
								<img src="%1$s"/>
								<a href="javascript:void(0);" class="st-image-swatch-image-remove" title="%2$s"></a>
							</div>',
							esc_url( $image ),
							esc_attr__( 'Remove Image', 'xstore-core' )
						);
					}
				printf('</div>');

				printf('<div class="st-image-swatch-holder">
						<input type="hidden" readonly id="term-%1$s" class="st-image-swatch-id" name="%1$s" value="%2$s"/>
						<button class="button button-secondary attribute-screen st-image-swatch-picker %7$s" data-title="%3$s" data-button="%4$s" data-remove="%5$s">%6$s</button>
					</div>',
					esc_attr( $attribute_type ),
					esc_attr( $value ),

					esc_attr__( 'Choose an Image', 'xstore-core' ),
					esc_attr__( 'Set Image', 'xstore-core' ),
					esc_attr__( 'Remove Image', 'xstore-core' ),
					esc_attr__( 'Add Image', 'xstore-core' ),
					''
				);
			break;

			case 'st-image-swatch-sq':

				printf('<div class="st-image-swatch-image-holder">' );
					if( 'edit' == $mode && !empty( $value ) ) {

						$image = $value ? wp_get_attachment_image_src( $value, array( 100, 100 ) ) : '';
						$image = $image ? $image[0] : '';

						printf('<div class="st-image-swatch-image">
								<img src="%1$s"/>
								<a href="javascript:void(0);" class="st-image-swatch-image-remove" title="%2$s"></a>
							</div>',
							esc_url( $image ),
							esc_attr__( 'Remove Image', 'xstore-core' )
						);
					}
				printf('</div>');

				printf('<div class="st-image-swatch-holder">
						<input type="hidden" readonly id="term-%1$s" class="st-image-swatch-id" name="%1$s" value="%2$s"/>
						<button class="button button-secondary attribute-screen st-image-swatch-picker %7$s" data-title="%3$s" data-button="%4$s" data-remove="%5$s">%6$s</button>
					</div>',
					esc_attr( $attribute_type ),
					esc_attr( $value ),

					esc_attr__( 'Choose an Image', 'xstore-core' ),
					esc_attr__( 'Set Image', 'xstore-core' ),
					esc_attr__( 'Remove Image', 'xstore-core' ),
					esc_attr__( 'Add Image', 'xstore-core' ),
					''
				);
			break;

			case 'st-label-swatch':
				printf(
					'<input type="text" id="term-%1$s" class="st-label-swatch-holder" name="%1$s" value="%2$s"/>',
					esc_attr( $attribute_type ),
					esc_attr( $value )
				);			
			break;

			case 'st-label-swatch-sq':
				printf(
					'<input type="text" id="term-%1$s" class="st-label-swatch-holder" name="%1$s" value="%2$s"/>',
					esc_attr( $attribute_type ),
					esc_attr( $value )
				);			
			break;
		}

		print( 'edit' == $mode ? '</td> </tr>' : '</div>' );
	}

	/**
	 * Save term meta
	 */
	public function save_term_meta( $term_id, $tt_id = '' ) {

		foreach( $this->attribute_types as $type => $label ) {

			if( isset( $_POST[ $type ] ) ) {
				if (function_exists( 'update_term_meta' )){
					update_term_meta( $term_id, $type, $_POST[ $type ], '' );
				} else {
					update_metadata( 'woocommerce_term', $term_id, $type, $_POST[ $type ], '' );
				}
			}
		}
	}

	/**
	 * When a term is deleted, delete its meta.
	 *
	 */
	public function delete_term( $term_id ) {
		global $wpdb;

		$term_id = absint( $term_id );

		if ( $term_id && get_option( 'db_version' ) < 34370 ) {
			$wpdb->delete( $wpdb->woocommerce_termmeta, array( 'woocommerce_term_id' => $term_id ), array( '%d' ) );
		}
	}	

	/**
	 * Add custom column to attribute list table
	 * 
	 */
	public function add_attribute_columns( $columns ) {

		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = '';
		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );		
	}

	/**
	 * Provide thumbnail HTML depend on attribute type
	 */
	public function add_attribute_column_content( $columns, $column, $term_id ) {

		$attribute      = $this->get_tax_attribute( $_REQUEST['taxonomy'] );
		$attribute_type = str_replace( '-sq', '', $attribute->attribute_type );
		$value          = get_term_meta( $term_id, $attribute_type, true );

		switch ( $attribute->attribute_type ) {

			case 'st-color-swatch':
				printf( '<div class="st-swatch-preview st-color-swatch" style="background-color:%s;"></div>', esc_html( $value ) );
			break;


			case 'st-color-swatch-sq':
				printf( '<div class="st-swatch-preview st-color-swatch" style="background-color:%s;"></div>', esc_html( $value ) );
			break;

			case 'st-image-swatch':
				$image = $value ? wp_get_attachment_image_src( $value, array( 100, 100 ) ) : '';
				$image = $image ? $image[0] : $this->plugin_admin_dir_path_url . 'images/placeholder.png';

				printf('<div class="st-swatch-preview st-image-swatch" style="background-image:url(%s);"></div>', esc_url( $image ) );
			break;

			case 'st-image-swatch-sq':
				$image = $value ? wp_get_attachment_image_src( $value, array( 100, 100 ) ) : '';
				$image = $image ? $image[0] : $this->plugin_admin_dir_path_url . 'images/placeholder.png';

				printf('<div class="st-swatch-preview st-image-swatch" style="background-image:url(%s);"></div>', esc_url( $image ) );
			break;

			case 'st-label-swatch':
				printf( '<div class="st-swatch-preview st-label-swatch"> %s </div>', esc_html( $value ) );
			break;

			case 'st-label-swatch-sq':
				printf( '<div class="st-swatch-preview st-label-swatch"> %s </div>', esc_html( $value ) );
			break;
		}
	}
}