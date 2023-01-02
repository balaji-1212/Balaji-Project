<?php
/**
 * The admin product add and edit page facing functionality.
 * ?post_type=product
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/admin/partials
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Product extends St_Woo_Swatches_Base {

	public function __construct() {

		parent::__construct();

		add_action( 'woocommerce_product_option_terms', array(&$this, 'wc_product_option_terms'), 10, 2 );
		add_action( 'admin_footer-post-new.php', array( &$this, 'add_attribute_term_modal' ) );
		add_action( 'admin_footer-post.php', array( &$this, 'add_attribute_term_modal' ) );
		add_action( 'wp_ajax_stwc_add_new_term', array( &$this, 'add_new_term_ajax' ) );
	}

	/**
	 * Add Selector in product edit screen for extra Product Attribute types
	 * New Attributes - Color, Image And Label
	 * Selecor - Select All, Select None and Add New 	
	 */
	public function wc_product_option_terms( $attribute_taxonomy, $index ) {

		if( !array_key_exists( $attribute_taxonomy->attribute_type, $this->attribute_types ) ) {
			return;
		}

		$taxonomy_name = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
		$all_terms     = get_terms( $taxonomy_name, apply_filters( 'woocommerce_product_attribute_terms', array( 'orderby' => 'name', 'hide_empty' => false ) ) );
		printf( '<select multiple="multiple" class="multiselect attribute_values wc-enhanced-select" 
			data-placeholder="%1$s" name="attribute_values[%2$s][]">',
			esc_attr__( 'Select terms', 'xstore-core'),
			esc_attr( $index )
		);

		if ( isset( $_POST['post_id'] ) ) {
			$post_id = $_POST['post_id'];
		} else {
			global $thepostid;
			$post_id = $thepostid;
		}

		if ( $all_terms ) {
			foreach( $all_terms as $term ) {
				printf( '<option value="%1$s" %2$s> %3$s </option>',
					esc_attr( $term->term_id ),
					selected( has_term( $term->term_id, $taxonomy_name, $post_id  ), true, false ),
					esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) )
				);
			}
		}

		print( '</select>' );
		printf( '<button class="button plus select_all_attributes"> %1$s </button>
			<button class="button minus select_no_attributes"> %2$s </button>
			<button class="button fr plus st-add-new-attribute" data-type="%3$s"> %4$s </button>',
			esc_html__( 'Select all', 'xstore-core' ),
			esc_html__( 'Select none', 'xstore-core' ),
			esc_attr( $attribute_taxonomy->attribute_type ),
			esc_html__( 'Add new', 'xstore-core' )
		);
	}

	/**
	 * Print HTML modal template to add term in admin product screen
	 */
	public function add_attribute_term_modal() {

		global $post_type;

		if( $post_type != 'product') {
			return;
		}?>

		<div id="st-term-modal-container">
			<div class="st-term-modal">
				<div class="st-term-modal-head">
					<div class="st-term-modal-title"><?php esc_html_e( 'Add new term', 'xstore-core' ); ?></div>
					<button type="button" class="media-modal-close">
						<span class="media-modal-icon"></span>						
					</button>
				</div>

				<div class="st-term-modal-content">
					<p class="st-term-name">
						<label>
							<?php esc_html_e( 'Name', 'xstore-core' ) ?>
							<input type="text" name="name" class="widefat st-term-name st-term-input">
						</label>
					</p>

					<p class="st-term-slug">
						<label>
							<?php esc_html_e( 'Slug', 'xstore-core' ) ?>
							<input type="text" name="slug" class="widefat st-term-slug st-term-input">
						</label>
					</p>

					<div class="st-term-swatch"></div>

					<div class="hidden st-term-tax"></div>

					<input type="hidden" class="st-term-input" name="st-term-nonce" value="<?php echo wp_create_nonce('_st_term_create_attribute');?>">
				</div>

				<div class="st-term-modal-footer">
					<button type="button" class="button button-primary button-large st-term-insert" data-label="<?php esc_attr_e( 'Add New', 'xstore-core' ); ?>">
						<?php esc_html_e( 'Add New', 'xstore-core' ) ?>
					</button>
					<button type="button" class="button button-secondary button-large st-term-cancel"><?php esc_html_e( 'Cancel', 'xstore-core' ) ?></button>
				</div>				
			</div>
			<div class="media-modal-backdrop"></div>
		</div>

		<script type="text/template" id="tmpl-st-color-swatch">
			<label>
				<?php esc_html_e( 'Color', 'xstore-core' ) ?>
				<input type="text" class="st-term-input st-color-swatch-picker" name="swatch"/>
			</label>
		</script>

		<script type="text/template" id="tmpl-st-label-swatch">
			<label>
				<?php esc_html_e( 'Label', 'xstore-core' ) ?>
				<input type="text" class="st-term-input widefat st-label-swatch-holder" name="swatch"/>
			</label>
		</script>

		<script type="text/template" id="tmpl-st-image-swatch">
			<label>
				<?php esc_html_e( 'Image', 'xstore-core' ); ?>
			</label>
			<div class="st-image-swatch-image-holder">
			</div>
			<div class="st-image-swatch-holder">
				<input type="hidden" readonly class="st-term-input st-image-swatch-id" name="swatch"/>
				<button class="button st-image-swatch-picker" 
					data-title="<?php esc_attr_e('Choose an Image', 'xstore-core');?>"
					data-button="<?php esc_attr_e('Set Image', 'xstore-core');?>"
					data-remove="<?php esc_attr_e('Remove Image', 'xstore-core');?>"><?php esc_html_e( 'Add Image', 'xstore-core' );?></button>
			</div>			
		</script>

		<script type="text/template" id="tmpl-st-input-term-tax">
			<input type="hidden" class="st-term-input" name="taxonomy" value="{{data.tax}}">
			<input type="hidden" class="st-term-input" name="type" value="{{data.type}}">
		</script>
		<?php
	}

	/**
	 * Ajax handler for adding new term in product add and edit screen.
	 * ?post_type=product
	 */
	public function add_new_term_ajax() {


		$nonce    = isset( $_POST['st-term-nonce'] ) ? $_POST['st-term-nonce'] : '';
		$taxonomy = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : '';
		$type     = isset( $_POST['type'] ) ? $_POST['type'] : '';
		$name     = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$slug     = isset( $_POST['slug'] ) ? $_POST['slug'] : '';
		$swatch   = isset( $_POST['swatch'] ) ? $_POST['swatch'] : '';

		if( !wp_verify_nonce( $nonce, '_st_term_create_attribute' ) ) {

			wp_send_json_error( array(
				'msg'      => esc_html__('Wrong Action', 'xstore-core'),
				'btn_span' => 'dashicons dashicons-no',
				'btn_txt'  => esc_html__('Try Again', 'xstore-core'),
			) );
		}

		if ( empty( $name ) || empty( $swatch ) || empty( $taxonomy ) || empty( $type ) ) {

			wp_send_json_error( array(
				'msg'      => esc_html__('Require more data', 'xstore-core'),
				'btn_span' => 'dashicons dashicons-no',
				'btn_txt'  => esc_html__('Try Again', 'xstore-core'),
			) );
		}

		if ( ! taxonomy_exists( $taxonomy ) ) {

			wp_send_json_error( array(
				'msg'      => esc_html__('Taxonomy is not exists', 'xstore-core'),
				'btn_span' => 'dashicons dashicons-no',
				'btn_txt'  => esc_html__('Try Again', 'xstore-core'),
			) );			
		}

		if ( term_exists( $name, $taxonomy ) ) {

			wp_send_json_error( array(
				'msg'      => esc_html__('This term is already exists', 'xstore-core'),
				'btn_span' => 'dashicons dashicons-no',
				'btn_txt'  => esc_html__('Try Again', 'xstore-core'),
			) );						
		}

		$term = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );

		if ( is_wp_error( $term ) ) {

			wp_send_json_error( array(
				'msg'      => $term->get_error_message(),
				'btn_span' => 'dashicons dashicons-no',
				'btn_txt'  => esc_html__('Try Again', 'xstore-core'),
			) );
		} else {
			$term = get_term_by( 'id', $term['term_id'], $taxonomy );
			update_term_meta( $term->term_id, $type, $swatch );			
		}

		wp_send_json_success(
			array(
				'msg'  => esc_html__( 'Added successfully', 'xstore-core' ),
				'btn_txt'  => esc_html__('Add New', 'xstore-core'),
				'id'   => $term->term_id,
				'slug' => $term->slug,
				'name' => $term->name,
			)
		);

		wp_die();
	}
}